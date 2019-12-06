<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";


class AdminSettings {
    
    public $mysqli;
    public $post;
    public $backtoadmin_days;
    public $retire_days;
    public $store_id;
    public $store_name;
    public $mynumber;
    public $validate; 
    public $errmsg ;

    
    public function __construct($mysqli)
    {
        $this->mysqli     = $mysqli;
        $this->post       = $_POST;
        $this->store_id   = $_SESSION['user-id'];
        $this->mynumber   = $_SESSION['mynumber'];
        $this->store_name = $_SESSION['user-name'];
        $this->validate   = true;
        
        $this->index();
    }
    
    public function index()
    {
        switch ($this->post['action']) {
            case 'change_dates':
                $this->changeDays();
                break;
            case 'change_password':
               $this->changePassword();
                break;
            case 'create_new_text':
               $this->createNewText();
                break;
            case 'change_text_status':
               $this->changeTextStatus();
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
    
    // Change Days 
    
    public function changeDays()
    {
        $this->backtoadmin_days = $this->post['backtoadmin_days'];
        $this->retire_days      = $this->post['retire_days'];
        
        if($this->validateDays()) {
            
            $update_query = "
                UPDATE messages_setup SET
                backtoadmin_days = $this->backtoadmin_days,
                retire_days = $this->retire_days
                WHERE mynumber = '$this->mynumber'
                AND store_id = $this->store_id
            ";
            
            $update = $this->mysqli->query($update_query);
            header("location:".$_SERVER['HTTP_REFERER']);    
        } 
        else {
            $_SESSION['days_errmsg'] = $this->errmsg;
            header("location:".$_SERVER['HTTP_REFERER']);    
        }
    }
    
    
    public function validateDays()
    {
        if(empty($this->retire_days ) || empty($this->backtoadmin_days )) {
            
            $this->validate = false;
            $this->errmsg = 'Please fill all fields';  
            
        }
        elseif(!empty($this->retire_days ) && !empty($this->backtoadmin_days)) {
            
            if($this->retire_days < $this->backtoadmin_days) {   
                $this->validate = false;
                $this->errmsg = "The number of days after which the message will be deleted cannot be less than the number of days after which the message will be returned to the administrator";
            }
            
        }
        return $this->validate;
    }
    

    
    
    
    
    
    // Change Password
    
    public function changePassword()
    {
        $new_password = $this->post['new-password'];
        $admin_id = $_SESSION['user-id'];
      
        if(!empty($new_password)) {
            $new_md5_psw = MD5($new_password);
		    $update = $this->mysqli->query("
		       UPDATE user SET
		       pass = '$new_md5_psw'
		       WHERE id = $admin_id
		    ");
        }
        
        header("location:".$_SERVER['HTTP_REFERER']);    
    }
    
    
    
    // Create new text
    
    public function createNewText()
    {
        $text = $this->post['text'];
        if(!empty($text)) {
            $insert_query = "
                INSERT INTO messages_msg ( store_id,storename,storenumber,subuser_id,text,text_is_public )
                VALUE($this->store_id,'$this->store_name','$this->mynumber',0,'$text',0)
            ";
            $this->mysqli->query($insert_query);
        }
        
        header("location:".$_SERVER['HTTP_REFERER']);    
    }
    
    
    
    // Change Text Status
    
    
    public function changeTextStatus()
    {
        $id             = $this->post['id'];
        $text_is_public = $this->post['text_is_public'];
        $update_query   = "UPDATE messages_msg SET text_is_public = $text_is_public WHERE id = $id AND store_id = $this->store_id";
        $updated        = $this->mysqli->query($update_query);
        
        if($updated) {
            print_r('updated');
        }
    }
    
    
    // Delete Text
    
    public function deleteText()
    {
        $id = $this->post['id'];
        $delete_query = "DELETE FROM messages_msg WHERE id=$id AND store_id = $this->store_id";
        $this->mysqli->query($delete_query);
        header("location:".$_SERVER['HTTP_REFERER']);    
    }
    
    // Edit Text
    
    public function editText()
    {
        $id             = $this->post['id'];
        $text           = $this->post['text'];
        
        // Check text default or not
        
        if($this->post['is_default'] == 1) {
             
            $domain_url         = $this->post['str_domain_url'];
            $full_text          = $text. $domain_url;
            $exist_default_text = $this->mysqli->query("SELECT * FROM messages_msg WHERE store_id = $this->store_id AND is_default = '1' ");
            
            if($exist_default_text->num_rows < 1){
                
                // Insert default text
                $insert_query = "INSERT INTO messages_msg ( store_id,storename,storenumber,subuser_id,text,text_is_public,is_default ) VALUE($this->store_id,'$this->store_name','$this->mynumber',0,'$full_text',1,'1') ";
                $this->mysqli->query($insert_query);
            
            } else {
                
                // Update default text
                $update_query   = "UPDATE messages_msg SET text = '$full_text' WHERE store_id = $this->store_id AND is_default = '1'";
                $updated        = $this->mysqli->query($update_query);
            
            }
            
        } else {
            // Update exist text
            $update_query   = "UPDATE messages_msg SET text = '$text' WHERE id = $id AND store_id = $this->store_id";
            $updated        = $this->mysqli->query($update_query);
        }
        
        $this->location($_SERVER['HTTP_REFERER']);
    }
    
    
    
    public function changeContact()
    {
        $new_contact    = $this->useCountryCodeOrNot( $this->post['new-contact']);
        $update_query   = "UPDATE messages_setup SET contact_cell = '{$new_contact}' WHERE store_id = $this->store_id ";
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

$AdminSettings = new AdminSettings($mysqli);









