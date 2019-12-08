<?php
//session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";

class EditMsgViewFunctions {
    
    public $mysqli;
    public $store_id;
    public $storename;
    public $is_subuser;
    public $subuser_id;
    public $authcode;
    public $this_page;
    
    public $bulk_message;
    public $bulk_image;
    public $bulk_storename ;
    
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
        
        if(isset($_GET['id'])) {

            $bulk_id       = $_GET['id'];
            $bulk          = $this->mysqli->query("SELECT * FROM messages_bulk_table WHERE id = $bulk_id ");
            $bulk_finished = $bulk->fetch_assoc();
            
            if($bulk->num_rows < 1 || $bulk->num_rows >= 1 && $bulk_finished['date_finished'] !== null ) {
                $this->location("/bulk");
            } else {
                $this->bulk_message   = $bulk_finished['text'];
                $this->bulk_image     = !empty($bulk_finished['image_link']) ? $bulk_finished['image_link'] : '';
                $this->bulk_storename =  $bulk_finished['include_store_name'];
            }
            
        }
    }
    
    
    public function getPrices()
    {
       return $this->mysqli->query("SELECT * FROM `messages_pricing` WHERE is_us = (SELECT is_us FROM messages_setup WHERE store_id = $this->store_id)")->fetch_assoc(); 
    }
    
    public function location($url)
    {
        header("location:".$url);
    }

    public function GetUsedCharacters()
    {
        $used_characters = 0;

        if(strlen($this->bulk_message) > 0) {
            $used_characters += strlen($this->bulk_message);
        }
        if(strlen($this->bulk_image) > 0) {
            $used_characters += 10;
        }
        if(strlen($this->bulk_storename) > 0) {
            $used_characters += strlen($this->bulk_storename);
        }
        
        return $used_characters;
    }
    
  
}

$EditMsgViewFunctions = new EditMsgViewFunctions($mysqli,$authcode);

?>
