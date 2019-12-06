<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";

class CreateMsgViewFunctions {
    
    public $mysqli;
    public $store_id;
    public $storename;
    public $is_subuser;
    public $subuser_id;
    public $authcode;
    public $this_page;
    
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

$CreateMsgViewFunctions = new CreateMsgViewFunctions($mysqli,$authcode);

?>
