<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";

class BulkViewFunctions {
    
    public $mysqli;
    public $store_id;
    public $storename;
    public $is_subuser;
    public $subuser_id;
    public $authcode;
    public $this_page;
    
    public $bulk_messages_arr;
    public $contacts_count;
 
    
    public function __construct($mysqli,$authcode)
    {
        $this->mysqli     = $mysqli;
        $this->this_page  = 'add-bulk-message';
        $this->store_id   = $_SESSION['user-id'];
        $this->storename  = $_SESSION['user-name'];
        $this->subuser_id = isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? $_SESSION['sub-user-id'] : 0;
        $this->is_subuser = isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? true : false;
        $this->authcode   = $authcode;    
        
        $this->index();
    }
    
    
    public function index()
    {  
        if(empty($_SESSION['auth']) || $_SESSION['auth'] != $this->authcode) {
             $this->location('/anmelden.php?msg=notauth');
        }
        
        if($this->is_subuser) {
            $this->location('/members'); 
        }
        
        
       
        $bulk_messages           = $this->mysqli->query("select * from messages_bulk_table where store_id = '$this->store_id' ");
        $this->bulk_messages_arr = [];
        
        $contacts = $this->mysqli->query("SELECT count(id) FROM messages_contacts WHERE storeid = $this->store_id AND stop = 0");
        $contacts_data = $contacts->fetch_assoc();
        
        if($contacts->num_rows > 0 && $contacts_data['count(id)'] > 0) {
            $this->contacts_count = $contacts_data['count(id)'];
        } else {
            $this->contacts_count = '0';
        }
        
        while($row = $bulk_messages->fetch_assoc()) {
            $this->bulk_messages_arr[] = $row;
        };

   }
   
   
    public function GetUsedCharacters($bulk_message,$bulk_image,$bulk_storename)
    {
        $used_characters = 0;

        if(strlen($bulk_message) > 0) {
            $used_characters += strlen($bulk_message);
        }
        if(strlen($bulk_image) > 0) {
            $used_characters += 10;
        }
        if(strlen($bulk_storename) > 0) {
            $used_characters += strlen($bulk_storename);
        }
        
        return $used_characters;
    }
    
    
    public function getPrices()
    {
       return $this->mysqli->query("SELECT * FROM `messages_pricing` WHERE is_us = (SELECT is_us FROM messages_setup WHERE store_id = $this->store_id)")->fetch_assoc(); 
    }
    
    public function location($url)
    {
        header("location:".$url);
    }

    
}

$BulkViewFunctions = new BulkViewFunctions($mysqli,$authcode);

?>
