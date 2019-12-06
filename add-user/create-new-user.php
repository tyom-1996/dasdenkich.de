<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";



class AddUser {
    
    public $mysqli;
    public $post;
    public $store_id;
    public $store_name;
    public $is_subuser;
 
    public function __construct($mysqli)
    {
        $this->mysqli     = $mysqli;
        $this->post       = $_POST;
        $this->store_id   = $_SESSION['user-id'];
        $this->store_name = $_SESSION['user-name'];
        $this->is_subuser = isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? true : false;
        
        if (empty($_POST)) {
            header("location:/members/");
            exit();
        } 
       
        $this->index();
    }
    
    
    public function index()
    {
        if (isset($_POST['action']) &&  $_POST['action'] == "create_sub_user") {
            
            if ($this->availableNumbersOfUsers() == 0) 
            {
                $_SESSION['errmsg'] = 'User Creation Limit Exceeded';
                header("location:/add-user/");
                exit();
            }
           
            $store_id         = $_SESSION['user-id'];
            $store_name       = $_SESSION['user-name'];
            $sub_name         = $_POST['name'];
            $sub_username     = $_POST['username'];
            $password         = $_POST['password'];
            $password_confirm = $_POST['password-confirm']; 
            
            // check unique username
            
            $user_data        = $this->mysqli->query("SELECT * FROM messages_subusers WHERE sub_username = '$sub_username'");
            
            if($password_confirm == $password) {
                
                if($user_data->num_rows > 0) {
                
                    $_SESSION['errmsg'] = 'User with username data exists';
                    header("location:/add-user/");
                    
                } else {
                    
                    $contact_cell = $_POST['contact_cell'];
                    
                    $sql = "INSERT INTO messages_subusers (store_id, storename, sub_name, sub_username,sub_pass,contact_cell) VALUES ($store_id,'$store_name','$sub_name','$sub_username',MD5('".$password."'),'$contact_cell')";
                    
                    if ($this->mysqli->query($sql)) 
                    {
                      if(!empty($_FILES['logoToUpload']['tmp_name'])) 
                      {
                          $this->uploadImage($this->mysqli->insert_id);
                      }
                      header("location:/users/");
                    }
                    
                }
                
            } else {
                
                $_SESSION['errmsg'] = 'Password and Confirm password not match';
                header("location:/add-user/");
                
            }
        }
    }
    
    
    
    public function availableNumbersOfUsers()
    {
        $admin_id      = $_SESSION['user-id'];
        $admin_data    = $this->mysqli->query("SELECT * FROM messages_setup WHERE store_id = '{$admin_id}'")->fetch_assoc();
        $exist_subuser = $this->mysqli->query("SELECT count(*) FROM messages_subusers WHERE store_id = '{$admin_id}'")->fetch_assoc();
        $limit_count   = $admin_data['howmany_seats'];
        
        foreach ($exist_subuser as $count){
            $exist_subuser_count = $count;
        }
        
        return (int)$limit_count - (int)$exist_subuser_count;
    }
    
    
    
    public function uploadImage($subuser_id)
    {
        $target_dir         = $_SERVER['DOCUMENT_ROOT']."/logos/";
        $store_data         = $this->mysqli->query("SELECT * FROM store WHERE str_id = $this->store_id")->fetch_assoc();
        $temp               = end(explode(".", $_FILES["logoToUpload"]["name"]));
      
        $image_name         = $store_data['str_id'].'-'.$subuser_id.'.'.$temp;
        $update_image_query = "UPDATE messages_subusers SET logo = '$image_name' WHERE store_id = $this->store_id AND id = $subuser_id ";    
       
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

$AddUser = new AddUser($mysqli);




    
    



