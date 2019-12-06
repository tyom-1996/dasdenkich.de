<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";



class EditUser {
    
    public $mysqli;
    public $subuser_id;
    public $store_id;
    public $store_name;
    public $is_subuser;
    
    public function __construct($mysqli)
    {
        $this->mysqli     = $mysqli;
        $this->subuser_id = $_POST['id'];
        $this->store_id   = $_SESSION['user-id'];
        $this->store_name = $_SESSION['user-name'];
        $this->is_subuser = isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? true : false;
        
        if (empty($_POST)) {
            $this->location('/members/');
            exit();
        } 
        
        $this->index();
    }
    
    public function index()
    {
        $sub_name     = $_POST['name'];
        $sub_username = $_POST['username'];

        $old_data     = $this->mysqli->query("SELECT sub_pass  FROM messages_subusers WHERE sub_username = '$sub_username'  and id = $this->subuser_id ")->fetch_assoc();

        if(!empty($_POST['password-confirm']) && !empty($_POST['password'])) {

            if($_POST['password-confirm'] == $_POST['password']) {
                
                $sub_pass = MD5($_POST['password']);
                 
            } else {
                
                $_SESSION['errmsg'] = 'Password and Confirm password not match';
                $this->location($_SERVER['HTTP_REFERER']);
                
            }
        } 
        
        elseif (empty($_POST['password-confirm']) || empty($_POST['password'])) {
            
            $_SESSION['errmsg'] = 'Please Confirm password';
            $this->location($_SERVER['HTTP_REFERER']);
            
        } elseif(empty($_POST['password-confirm']) && empty($_POST['password'])) {
            
            $sub_pass = $old_data['sub_pass'];
             
        }
           
        $user_data =  $this->mysqli->query("SELECT * FROM messages_subusers WHERE sub_username = '$sub_username'  and id != $this->subuser_id ");
        
        if($user_data->num_rows > 0) {
            
            $_SESSION['errmsg'] = 'User with username data exists';
            $this->location($_SERVER['HTTP_REFERER']);
            
        } else {
            
            $contact_cell = $_POST['contact_cell'];
            
            $sql = "UPDATE messages_subusers SET store_id = $this->store_id , storename = '$this->store_name', sub_name = '$sub_name', sub_username = '$sub_username', sub_pass = '$sub_pass', contact_cell='$contact_cell' where id = $this->subuser_id ";
            if ($this->mysqli->query($sql) === TRUE) {
               
                $this->uploadImage();       
                $this->location("/users/");
               
            } else {
                
                echo "Error: " . $sql . "<br>" . $conn->error;
                
            }
        }
    }
    
    
    
    // Upload image
    
    public function uploadImage()
    {
        $target_dir         = $_SERVER['DOCUMENT_ROOT']."/logos/";
        $store_data         = $this->mysqli->query("SELECT * FROM store WHERE str_id = $this->store_id")->fetch_assoc();
        $temp               = end(explode(".", $_FILES["logoToUpload"]["name"]));
      
        $image_name         = $store_data['str_id'].'-'.$this->subuser_id.'.'.$temp;
        $update_image_query = "UPDATE messages_subusers SET logo = '$image_name' WHERE store_id = $this->store_id AND id = $this->subuser_id ";    
       
        $target_file        = $target_dir.$image_name;
        
        $uploadOk           = 1;
        $imageFileType      = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check              = getimagesize($_FILES["logoToUpload"]["tmp_name"]);
        $uploadOk           = $check !== false ? 1 : 0;
        
        if (file_exists($target_file)) {
            unlink($target_file);
        }
        
        if ($_FILES["logoToUpload"]["size"] > 500000) {
            $uploadOk = 0;
        }
     
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" ) {
            $uploadOk = 0;
        }
      
        if ($uploadOk == 0) {
            
            $this->location($_SERVER['HTTP_REFERER']);
            
        } else {
            
            if (move_uploaded_file($_FILES["logoToUpload"]["tmp_name"], $target_file)) {
                
                $this->mysqli->query($update_image_query);
                $this->location($_SERVER['HTTP_REFERER']);
                
            } else {  
                
                $this->location($_SERVER['HTTP_REFERER']);
                
            }
        }
        
    }
    
    public function location($location)
    {
        header("location:".$location);    
    }
    
    
}


$EditUser = new EditUser($mysqli);







    
        
        
   