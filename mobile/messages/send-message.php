<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";


class SendMessage {
    
    public $mysqli;
    public $contacterName;
    public $message;
    public $where;      
    public $checkQ;
    public $checkR;  
    public $contacterQ;
    
    
    public $fromcellnumber;
    public $nowTime;
    public $store_id;
    public $storename;
    public $subuser_id;
    public $mynumber;
    public $is_subuser;
    
    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
        $this->fromcellnumber = $this->useCountryCodeOrNot();
        $this->contacterName  = isset($_POST['contacterName']) ? $_POST['contacterName'] : '';
        $this->message        = mysqli_real_escape_string($mysqli, $_POST['message']);
        $this->where          = ' WHERE storeid like "'.$_SESSION['user-id'].'" and cellnumber like "'.$this->fromcellnumber.'"';
        $this->checkQ         = 'select * from messages_contacts'.$this->where;
        $this->checkR         = $this->mysqli->query($this->checkQ);
        $this->is_subuser     = isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? true : false;
        $this->index();
    }
    
    
    public function useCountryCodeOrNot()
    {
        $number = $_POST['number'];
        
        if (isset($_POST['action']) && $_POST['action'] == "create-msg-form") {
            
            $country_code = $this->getCountryCode();
            $pos          = strpos($number, $country_code );
            
            if ($pos === false) {
                 return $country_code.$number;
            } else {
                if ($pos != 0) {
                     return $country_code.$number;
                } else {
                    return $number;
                }
            }
            
        } 
        
        return $number;
    }
    
    public function index()
    {
        // if ($this->checkR->num_rows >= 1) {
        //     $this->contacterQ = 'UPDATE messages_contacts SET name="'.$this->contacterName.'"'.$this->where;
        // }
        // else{
        //     $this->contacterQ = 'INSERT INTO messages_contacts '
        //         . '(storeid, storename, cellnumber, name) '
        //         . 'VALUES '
        //         .'('.$_SESSION['user-id'].',"'.$_SESSION['user-name'].'", '.$this->fromcellnumber.', "'.$this->contacterName.'")';
        // }
        
        
        if ($this->checkR->num_rows < 1) 
        {
            $this->contacterQ = 'INSERT INTO messages_contacts '
                . '(storeid, storename, cellnumber, name) '
                . 'VALUES '
                .'('.$_SESSION['user-id'].',"'.$_SESSION['user-name'].'", '.$this->fromcellnumber.', "'.$this->contacterName.'")';
        }
        
        $result               = $this->mysqli->query( $this->contacterQ);
        $this->nowTime        = date('Y-m-d H:i:s');
        $this->store_id       = $_SESSION['user-id'];
        $this->storename      = $_SESSION['user-name'];
        $this->mynumber       = $_SESSION['mynumber'];
        
        $this->subuser_id     = isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? $_SESSION['sub-user-id'] : 0;
        $insert_status        = $this->insertMessage();
        
        
        if ($insert_status) {
            
            $this->insertInchargeData();
            $this->activateArchivedMessage();
            // $this->increaseTheNumberOfSentMessages();
            
            $_SESSION['send_message'] = true ;
        	header("location: /mobile/messages/?msg=addmessage#detail-".$this->fromcellnumber);
        	
        } else {
            
        	$_SESSION['send_message'] = false ;
        	header("location: /mobile/messages/?msg=addmessageerror#detail-".$this->fromcellnumber);
    
        }

    }
    
    // public function increaseTheNumberOfSentMessages()
    // {
    //     if(!$this->is_subuser) 
    //     {
    //         $setup_query = "UPDATE messages_setup SET msg_per_day_today = msg_per_day_today + 1 WHERE store_id =  $this->store_id AND storename = '$this->storename'";
    //         $setup_data = $this->mysqli->query($setup_query);
         
    //     }
    // }
    
    public function getCountryCode()
    {   
        $store_id  = $_SESSION['user-id'];  
        $msg_setup = $this->mysqli->query("SELECT * FROM messages_setup WHERE store_id = $store_id ")->fetch_assoc();
        return $msg_setup['country_code'];
    }

    
    public function activateArchivedMessage()
    { 
        $activate_msg = $this->mysqli->query("UPDATE messages_contacts SET is_archive = '0' WHERE cellnumber = '{$this->fromcellnumber}' AND storeid = {$this->store_id} AND is_archive = '1'");
    }
    
    public function insertMessage() {
       
        $query = '';
        
        if(isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user') {
            $query = "INSERT INTO messages (store_id, storename, mynumber, fromcellnumber, message_out_sending,date, subuser_id) 
                      VALUES($this->store_id,'$this->storename','$this->mynumber',$this->fromcellnumber,'$this->message','$this->nowTime',$this->subuser_id)";
        } else {
            $query = "INSERT INTO messages (store_id, storename, mynumber, fromcellnumber, message_out_sending,date, subuser_id) 
                      VALUES($this->store_id,'$this->storename','$this->mynumber',$this->fromcellnumber,'$this->message','$this->nowTime',$this->subuser_id)";
        }
       
        $result = $this->mysqli->query($query);
        return $result;
    }
    
    public function insertInchargeData()
    {
        $store_id   = $this->store_id;
        $cellnumber = $this->fromcellnumber;
        $message    = $this->mysqli->query("SELECT * FROM messages_contacts WHERE cellnumber = $cellnumber AND storeid = $store_id")->fetch_assoc();
        $subuser_id = is_subuser ? $this->subuser_id : 0;
        
        if(!empty($message)) {
            $incharge_query  = "
            SELECT * FROM messages_incharge 
            WHERE status = '1' AND fromcellnumber = '$cellnumber' AND store_id = $store_id  AND subuser_id = $subuser_id
            OR status = '-1' AND fromcellnumber = '$cellnumber' AND store_id = $store_id  AND subuser_id = $subuser_id";
            $last_incharge   = $this->mysqli->query($incharge_query)->fetch_assoc();
          
            // do not add new data to the database when sending a message if, contacts are already associated with my account
            if(empty($last_incharge)) {
                $update_query    = "UPDATE messages_incharge SET status = '0' WHERE fromcellnumber = '$cellnumber' AND store_id = $store_id ";
                $insert_query    = "INSERT INTO messages_incharge (store_id,storename,fromcellnumber,subuser_id,status,incharge_since,viewed) VALUES($this->store_id,'$this->storename','$this->fromcellnumber',$subuser_id,'1','$this->nowTime','1')";
                $update_incharge = $this->mysqli->query($update_query);
                $insert_incharge = $this->mysqli->query($insert_query);
            }
        }
    }
    
   
}

$SendMessage = new SendMessage($mysqli);

?>
