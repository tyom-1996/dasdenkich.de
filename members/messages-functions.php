<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";


class MessagesFunctions {
    
    public $mysqli;
    public $post;
    public $type;
    public $action;  
    public $store_id;
    public $store_name;
    public $mynumber;
    public $is_subuser;
 
    
    public function __construct($mysqli)
    {
        $this->mysqli     = $mysqli;
        $this->post       = $_POST;
        $this->type       = $_POST['type']; 
        $this->action     = $_POST['action']; 
        $this->store_id   = $_SESSION['user-id'];
        $this->mynumber   = $_SESSION['mynumber'];
        $this->store_name = $_SESSION['user-name'];
        $this->is_subuser = isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? true : false;
        $this->index();
    }
    
    
    public function dd($arr)
    {
        print "<pre>";
        print_r($arr);die;
    }
    
    public function index()
    {
        
        switch ($this->action) {
            case 'get_contact_list':
                $this->getContactsList();
                break;
            case 'archive_message':
                $this->archiveMessage();
                break;    
            case 'upload_image':
                $this->uploadImage();
                break;    
        } 
    }
    
    public function location($location)
    {
        header("location:".$location);    
    }
    
    public function getContactsList()
    {
       $value          = $this->post['value'];
       
       if($this->type == 'get_contact_by_number') {
           $contacts_query = "SELECT * FROM messages_contacts WHERE cellnumber LIKE '%$value%' AND storeid = $this->store_id ";
       } elseif($this->type == 'get_contact_by_name') {
           $contacts_query = "SELECT * FROM messages_contacts WHERE name LIKE '%$value%' AND storeid = $this->store_id ";
       }
       
       $contacts       = $this->mysqli->query($contacts_query);
       $contacts_list  = ''; 
       
       if($contacts->num_rows > 0) {
           while ($row = $contacts->fetch_assoc()){
                $id         = $row['id'];
                $cellnumber = $row['cellnumber'];
                $name       = $row['name'];
                
                $contacts_list .= "
                    <li class='contatc-list-item' data-id = '$id'>
                        <span class='cellnumber'>$cellnumber</span>
                        <span class='name'>$name</span>
                    </li>
                ";
           }
           
           echo " <ul>$contacts_list</ul> ";
       } else {
            echo 'empty';
       }
      
    }
    
    
    
    
    
    
    
    public function archiveMessage()
    {
        if(!empty($this->post['contact_id']))
        {
            $contact_id  = $this->post['contact_id'];
            $msg_contact = $this->mysqli->query("SELECT * FROM messages_contacts WHERE id = $contact_id")->fetch_assoc();
            
            if(!empty($msg_contact)) {
                
                $store_id       = $msg_contact['storeid'];
                $storename      = $msg_contact['storename'];
                $fromcellnumber = $msg_contact['cellnumber'];
                
                $this->mysqli->query("UPDATE messages_contacts SET is_archive = '1' WHERE id = '{$contact_id}'");
                $this->inserInchargetData($store_id,$storename,$fromcellnumber);
                
                $this->location('/members');
                // $this->dd($msg_contact);
            }
        }
    }
    
    public function inserInchargetData ($store_id,$storename,$fromcellnumber)
    {
        $incharge_since = date("Y-m-d H:i:s");
        $update = $this->mysqli->query("UPDATE messages_incharge SET status = '0' WHERE fromcellnumber = '$fromcellnumber' AND store_id = $store_id ");
        
        if ($update) {
            $res = $this->mysqli->query("INSERT INTO messages_incharge (store_id,storename,fromcellnumber,subuser_id,status,incharge_since) VALUES($store_id,'$storename','$fromcellnumber',-1,'1','$incharge_since')");
        }
    }
    
    
    
    
    public function uploadImage()
    {
        $target_dir    = $_SERVER['DOCUMENT_ROOT']."/logos/";
        $store_data    = $this->mysqli->query("SELECT * FROM store WHERE str_id = $this->store_id")->fetch_assoc();
        $temp          = end(explode(".", $_FILES["fileToUpload"]["name"]));
        
        if($this->is_subuser) {
            
            $subuser_id         = $_SESSION['sub-user-id'];
            $image_name         = $store_data['str_id'].'-'.$subuser_id.'.'.$temp;
            $update_image_query = "UPDATE messages_subusers SET logo = '$image_name' WHERE store_id = $this->store_id AND id = $subuser_id ";    
            
        } else {
            
            $image_name    = $store_data['str_id'].'.'.$temp;
            $update_image_query = "UPDATE messages_setup SET logo = '$image_name' WHERE store_id = $this->store_id ";    
        }
        
        $target_file   = $target_dir.$image_name;
        
        $uploadOk      = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check         = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        $uploadOk = $check !== false ? 1 : 0;
        
        
        if (file_exists($target_file)) {
            unlink($target_file);
        }
        
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $uploadOk = 0;
        }
     
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" ) {
            $uploadOk = 0;
        }
      
        if ($uploadOk == 0) {
            $this->location($_SERVER['HTTP_REFERER']);
        } else {
            
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                
                $this->mysqli->query($update_image_query);
                $this->location($_SERVER['HTTP_REFERER']);
                
            } else {    
                $this->location($_SERVER['HTTP_REFERER']);
            }
        }
        
    }
    
    
    
    
}

$MessagesFunctions = new MessagesFunctions($mysqli);









