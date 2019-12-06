<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";



class SubuserSetting {

    public $id;
    public $error = false;
    public $current_psw;
    public $mysqli;
    public $new_psw;
    public $error_msg = [];
    public $post;
    public $store_id;
    public $store_name;
    public $mynumber;
    public $subuser_id;

    public function __construct($mysqli) {
        $this->mysqli     = $mysqli;
        $this->post       = $_POST;
        $this->store_id   = $_SESSION['user-id'];
        $this->mynumber   = $_SESSION['mynumber'];
        $this->store_name = $_SESSION['user-name'];
        $this->subuser_id = $_SESSION['sub-user-id'];
        
        $this->index();
    }
    
    public function index()
    {
        switch ($this->post['action']) {
            case 'change-password':
                $this->changePassword();
                break;
            case 'create_new_text':
              $this->createNewText();
                break;
            case 'delete_text':
              $this->deleteText();
                break;   
            case 'edit_text':
              $this->editText();
                break;  
            case 'change_my_contact':
              $this->changeContact();
                break;
        }
    }
    
    public function location($location)
    {
        header("location:".$location);    
    }
    
    
    // Change Password;
    
    public function changePassword()
    {
        $new_password = $this->post['new-password'];
        $id           = $this->post['id'];
      
        if(!empty($new_password)) {
            $new_md5_psw = MD5($new_password);
		    $update =  $this->mysqli->query("UPDATE messages_subusers SET sub_pass = '$new_md5_psw' WHERE id = $id");
        }

        $this->location($_SERVER['HTTP_REFERER']);
    }
    
    
    // Create new text
    
    public function createNewText()
    {
        $text = $this->post['text'];
        if(!empty($text)) {
            $insert_query = "
                INSERT INTO messages_msg ( store_id,storename,storenumber,subuser_id,text,text_is_public )
                VALUE($this->store_id,'$this->store_name','$this->mynumber',$this->subuser_id,'$text',0)
            ";
            $this->mysqli->query($insert_query);
        }
        
        header("location:".$_SERVER['HTTP_REFERER']);    
    }
    
    
    // Delete Text
    
    public function deleteText()
    {
        $id = $this->post['id'];
        $delete_query = "DELETE FROM messages_msg WHERE id=$id AND store_id = $this->store_id AND subuser_id = $this->subuser_id";
        $this->mysqli->query($delete_query);
        header("location:".$_SERVER['HTTP_REFERER']);    
    }
    
    // Edit Text
    
    public function editText()
    {
        $id             = $this->post['id'];
        $text           = $this->post['text'];
        $update_query   = "UPDATE messages_msg SET text = '$text' WHERE id = $id AND store_id = $this->store_id AND subuser_id = $this->subuser_id";
        $updated        = $this->mysqli->query($update_query);
        
        $this->location($_SERVER['HTTP_REFERER']);
    }
    
    
    
    public function changeContact()
    {
        $new_contact    = $this->useCountryCodeOrNot( $this->post['new-contact']);
        $update_query   = "UPDATE messages_subusers SET contact_cell = '{$new_contact}' WHERE  id = $this->subuser_id AND store_id = $this->store_id ";
        $update_status  = $this->mysqli->query($update_query);
        if($update_status) 
        {
            $this->location($_SERVER['HTTP_REFERER']);
        }
    }
    
    
     
    public function useCountryCodeOrNot($number)
    {
        $country_code = $this->getCountryCode();
        $pos          = strpos($number, $country_code );
        
        if($pos === false) {
             return $country_code.$number;
        } else {
            if($pos != 0) {
                 return $country_code.$number;
            } else {
                return $number;
            }
        }
       
        return $number;
    }
    
    
    public function getCountryCode()
    {   
        $msg_setup = $this->mysqli->query("SELECT * FROM messages_setup WHERE store_id = $this->store_id ")->fetch_assoc();
        return $msg_setup['country_code'];
    }
    
    

  }

   


$SubuserSetting = new SubuserSetting($mysqli);

