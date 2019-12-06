<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";



class AssignMessageSubusers {

    public $mysqli;
    public $fromcellnumber;
    public $store_id;
    public $storename;
    public $incharge_since;
    public $subuser_id;
    public $subuser_name;
    public $action;
    
    public function __construct($mysqli)
    {
        $this->mysqli         = $mysqli;
        $this->action         = $_POST['action'];
        $this->fromcellnumber = $_POST['fromcellnumber'];
        $this->index();
    }

    public function index()
    {
        if($this->action == 'back-to-admin') {
            $this->deleteUserFromIncharge();
        } 
        else if($this->action == 'assign-message-subusers') {
            $this->subuser_id     = $_POST['subuser_id'];
            $user                 = $this->getSubuser();
            $this->store_id       = $user['store_id'];
            $this->storename      = $user['storename'];
            $this->incharge_since = date("Y-m-d H:i:s");
            $this->subuser_name   = $user['sub_name'];
          
            // INSERT DATA 
            $this->insertData();
        }
    }

    public function insertData ()
    {
        $this->activateArchivedMessage();
        
        $update = $this->mysqli->query("UPDATE messages_incharge SET status = '0' WHERE fromcellnumber = '$this->fromcellnumber' AND store_id = $this->store_id AND status != -1 ");
        
        if ($update) {
            
            $res = $this->mysqli->query("INSERT INTO messages_incharge (store_id,storename,fromcellnumber,subuser_id,status,incharge_since) VALUES($this->store_id,'$this->storename','$this->fromcellnumber',$this->subuser_id,'1','$this->incharge_since')");
           
            if (!$res) {
                die('insert_error');
            } else {
                die($this->subuser_name);
            }
        }
    }
    
    
    public function deleteUserFromIncharge()
    {
        $this->activateArchivedMessage();
        $incharge_since = date("Y-m-d H:i:s");
        $fromcellnumber = $_POST['fromcellnumber'];
        $store_id       = $_SESSION['user-id'];
        $storename      = $_SESSION['user-name'];
        
        $update_query   = "UPDATE messages_incharge SET status = '0' WHERE fromcellnumber = '$fromcellnumber' AND store_id = $store_id AND subuser_id != -1 ";
        $update         = $this->mysqli->query($update_query);
        
        if ($update) {
            $res = $this->mysqli->query("INSERT INTO messages_incharge (store_id,storename,fromcellnumber,subuser_id,status,incharge_since) VALUES($store_id,'$storename','$fromcellnumber',0,'1','$incharge_since')");
            print('update');
        }
    }

    public function getSubuser()
    {
       return $this->mysqli->query("SELECT * from messages_subusers WHERE id = $this->subuser_id")->fetch_assoc();
    }
    
    public function activateArchivedMessage()
    {
        $store_id       = $_SESSION['user-id'];
        $activate_msg = $this->mysqli->query("UPDATE messages_contacts SET is_archive = '0' WHERE cellnumber = '{$this->fromcellnumber}' AND storeid = {$store_id} AND is_archive = '1'");
    }


}

$AssignMessageSubusers = new AssignMessageSubusers($mysqli);