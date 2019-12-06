<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";

class BulkFunctions {
    
    public $mysqli;
    public $post;
    public $action;
    public $store_id;
    public $storename;
    public $is_subuser;
    public $subuser_id;
    public $contacts_count;
    
    
    
    public function __construct($mysqli)
    {
        $this->mysqli         = $mysqli;
        $this->post           = $_POST;
        $this->action         = $this->post['action'];
        $this->store_id       = $_SESSION['user-id'];
        $this->storename      = $_SESSION['user-name'];
        $this->subuser_id     = isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? $_SESSION['sub-user-id'] : 0;
        $this->is_subuser     = isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? true : false;
        
        $this->index();
    }
    
    
    public function index()
    {  
        switch ($this->action) {
            case 'create_bulk_msg':
                $this->CreateBulkMessage();
                break;
            case 'send_bulk':
                $this->SendBulkMessage();
                break; 
            case 'edit_bulk_msg':
                $this->EditBulkMessage();
                break;     
        }
    }
    
    public function CreateBulkMessage()
    {
        $total_text_length = strlen($this->post['store_name_field'].$text)+10;
        
        if(empty($this->post['message']) || $total_text_length > 320)
        {
            $this->location($_SERVER['HTTP_REFERER']);
        }
       
        $text         = $this->post['message'];
        $store_id     = $this->post['store_id'];
        $image_link   = $this->uploadImage();
        $d            = substr(date('F'), 0, 3);
        $date         = date(' d, Y h:i');
       
        $date_created = $d.$date;
        
        // echo $date_created;die;
        $store_name   = isset($this->post['store_name_field']) ? $this->post['store_name_field'] : null;
        $bulk_query = "INSERT INTO messages_bulk_table (store_id,include_store_name,text,image_link,date_created) VALUE($store_id,'{$this->storename}','{$text}','{$image_link}','{$date_created}')";
       
        $this->mysqli->query($bulk_query);
        $this->location('/bulk');
    }
    
    
   
   
    public function uploadImage($bulk_id = false)
    {
        $target_dir    = $_SERVER['DOCUMENT_ROOT']."/attachments/";
        $temp          = end(explode(".", $_FILES["upload-msg-image"]["name"]));
        $image_name    = time()."_img".'.'.$temp;
       
        $target_file   = $target_dir.$image_name;
        
        $uploadOk      = true;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check         = getimagesize($_FILES["upload-msg-image"]["tmp_name"]);
        
        $uploadOk      = $check == true ? true : false;
        $uploadOk      = $_FILES["upload-msg-image"]["size"] > 500000 ? false : true;
        $uploadOk      = $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" ? false : true;
     
        if(!$uploadOk) {
            return false;
        } 
        
        move_uploaded_file($_FILES["upload-msg-image"]["tmp_name"], $target_file);
        
        $img_new_path = $this->createImageShortUrl('/attachments/'.$image_name);
        
        // if($bulk_id !== false) {
        //     $old_bulk       = $this->mysqli->query("SELECT * FROM messages_bulk_table WHERE id = $bulk_id")->fetch_assoc();
        //     $old_bulk_image = $old_bulk['image_link'];
        // }
        // unset($old_bulk_image);
        
        return $img_new_path;

    }
    
    public function createImageShortUrl($url)
    {
        $short_url    = $this->generateUniqueID();
        $insert_query = "INSERT INTO url_shorten (url,short_code) VALUE('$url','$short_url')";
        $insert       = $this->mysqli->query($insert_query);
        
        if($insert) {
           return '/?l='.$short_url;
        }
        
        return false;
    }
    
    
    function generateUniqueID(){
        
        $token  = substr(md5(uniqid(rand(), true)),0,6); 
        $query  = "SELECT * FROM url_shorten WHERE short_code = '".$token."' ";
        $result = $this->mysqli->query($query); 
        
        if ($result->num_rows > 0) {
            generateUniqueID();
        } else {
            return $token;
        }
    }
    
    public function location($url)
    {
        header("location:".$url);
    }
    
   
    
    public function getPrices()
    {
        $prices        = $this->mysqli->query("SELECT * FROM `messages_pricing` WHERE is_us = (SELECT is_us FROM messages_setup WHERE store_id = $this->store_id)")->fetch_assoc(); 
        $contacts      = $this->mysqli->query("SELECT count(id) FROM messages_contacts WHERE storeid = $this->store_id AND stop = 0");
        $contacts_data = $contacts->fetch_assoc();
        
        if($contacts->num_rows > 0 && $contacts_data['count(id)'] > 0) {
            $this->contacts_count = $contacts_data['count(id)'];
        } else {
            $this->contacts_count = '0';
        }
        
        $u160          = $prices['u160'];
        $u160markup    = $prices['u160markup'];
        $u160exchange  = $prices['u160exchange'];
        
        $u320          = $prices['u320'];
        $u320markup    = $prices['u320markup'];
        $u320exchange  = $prices['u320exchange'];
        
        $u160_price    = ($u160 * $u160markup * $u160exchange) * $this->contacts_count;
        $u320_price    = ($u320 * $u320markup * $u320exchange) * $this->contacts_count;
        
        return [
            "u160_price" => $u160_price,
            "u320_price" =>$u320_price 
        ]; 
    }
    
    public function getAccountBalance()
    {
        $balance = $this->mysqli->query("SELECT * FROM messages_bulkaccount WHERE store_id = $this->store_id ")->fetch_assoc();
        return $balance['amount_left'];
        
    }
    
    public function SendBulkMessage()
    {   
        $bulk_id       = $this->post['bulk_id'];
        $messages_bulk = $this->mysqli->query("SELECT * FROM messages_bulk_table WHERE id = $bulk_id");
        
        if($messages_bulk->num_rows < 1) {
            $this->location($_SERVER["HTTP_REFERER"]);
        } 
        
        $messages_bulk_data  = $messages_bulk->fetch_assoc();
        $messages_bulk_count = 0;
        
        if(!empty($messages_bulk_data['include_store_name'])) {
            $messages_bulk_count += strlen($messages_bulk_data['include_store_name']);
        }
        if(!empty($messages_bulk_data['text'])) {
            $messages_bulk_count += strlen($messages_bulk_data['text']);
        }
        if(!empty($messages_bulk_data['image_link'])) {
            $messages_bulk_count += strlen($messages_bulk_data['image_link']);
        }
        
        $my_balance = $this->getAccountBalance();
        $prices     = $this->getPrices();
        
        if($messages_bulk_count > 0 && $messages_bulk_count <= 160) {
            $price = $prices['u160_price'];  
        } elseif($messages_bulk_count >= 160 && $messages_bulk_count <= 320) {
            $price = $prices['u320_price'];
        }
       
        if($price > $my_balance) {
           $this->location($_SERVER['HTTP_REFERER']);
        }
        
        $d             = substr(date('F'), 0, 3);
        $date          = date(' d, Y h:i');
       
        $date_finished = $d.$date;
        $bulk_query    = "UPDATE messages_bulk_table SET date_finished = '{$date_finished}' WHERE id = $bulk_id";
       
        $this->mysqli->query($bulk_query);
        $this->location('/bulk');
    }
    
    public function EditBulkMessage()
    {
        $text              = $this->post['message'];
        $store_id          = $this->post['store_id'];
        $bulk_id           = $this->post['bulk_id'];
        $d                 = substr(date('F'), 0, 3);
        $date              = date(' d, Y h:i');
        $date_created      = $d.$date;
        $total_text_length = strlen($this->post['store_name_field'].$text)+10;
        
        if($total_text_length > 320) {
            $this->location($_SERVER['HTTP_REFERER']);
        } 
        
        if($_FILES['upload-msg-image']['size'] > 0) {
            $image_link = $this->uploadImage($bulk_id);
        } else {
            if($this->post['image_exist'] == 'true') {
                $image_link = $this->post['image_link'];
            } else {
                $image_link = '';
            }
        }
        
       
        
        $store_name     = isset($this->post['store_name_field']) ? $this->post['store_name_field'] : null;
        
        if(isset($this->post['store_name_field'])) {
            $storename  = $this->post['store_name_field'];
            $bulk_query = "UPDATE messages_bulk_table SET include_store_name = '{$storename}', text = '{$text}' ,image_link = '{$image_link}',date_created = '{$date_created}' WHERE id = $bulk_id";
        } else {
            $storename  = null;
            $bulk_query = "UPDATE messages_bulk_table SET include_store_name = '{$storename}', text = '{$text}' ,image_link = '{$image_link}',date_created = '{$date_created}' WHERE id = $bulk_id";
        }
        
        $this->mysqli->query($bulk_query);
        $this->location('/bulk');
    }
    
   
}

$BulkFunctions = new BulkFunctions($mysqli);

?>
