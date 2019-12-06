<?php

$host = 'localhost';
$username = 'incomc6_angie';
$password = 'angiePassword';
$database_name = 'incomc6_dasdenkich';

$mysqli = new mysqli($host, $username, $password, $database_name);
mysqli_set_charset($mysqli,"utf8");


class CronForArchivingMessages {
    
    public $mysqli;
    
    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
        $this->index();
    }

    public function index()
    {
        $query = "
            SELECT mc.*, ms.retire_days,ms.backtoadmin_days 
            FROM messages_contacts mc
            JOIN messages_setup ms
            ON mc.storeid = ms.store_id
        ";
     
        $result = $this->mysqli->query($query);
         
        while($msg = $result->fetch_assoc()) {
       
           if($msg['is_archive'] == 1){
               continue;
           }
          
           $msg_contacts_id = $msg['id'];
           $store_id        = $msg['storeid'];
           $fromcellnumber  = $msg['cellnumber'];
           $storename       = $msg['storename'];
           $retire_days     = $msg['retire_days'];
           $msg_date        = $this->getMessageDate($store_id,$fromcellnumber);
           
           $is_archive      = $this->isArchiveMsg($retire_days,$msg_date);
           
           if($is_archive) {
               $this->mysqli->query("UPDATE messages_contacts SET is_archive = '1' WHERE id = '{$msg_contacts_id}'");
               $this->insertInchargetData($store_id,$storename,$fromcellnumber);
           }
           
           
           //  Assign messages back to admin 
           
           $backtoadmin_days = $msg['backtoadmin_days'];
           $current_date    = date('Y-m-d H:i:s');
           $diff            = strtotime($current_date) - strtotime($msg_date);
           $days_passed     = (int)$diff/(60*60*24);
           $backtoadmin_msg     = round($days_passed) >= $backtoadmin_days ? true : false; 
           
           if($backtoadmin_msg) {
              
                $incharge_since = date("Y-m-d H:i:s");
                $update = $this->mysqli->query("UPDATE messages_incharge SET status = '0' WHERE fromcellnumber = '$fromcellnumber' AND store_id = $store_id ");
                
                if ($update) {
                    $res = $this->mysqli->query("INSERT INTO messages_incharge (store_id,storename,fromcellnumber,subuser_id,status,incharge_since) VALUES($store_id,'$storename','$fromcellnumber',0,'1','$incharge_since')");
                }
                  
           }
          
        }
        
      
    }
    
    public function insertInchargetData ($store_id,$storename,$fromcellnumber)
    {
        $incharge_since = date("Y-m-d H:i:s");
        $update = $this->mysqli->query("UPDATE messages_incharge SET status = '0' WHERE fromcellnumber = '$fromcellnumber' AND store_id = $store_id ");
        
        if ($update) {
            $res = $this->mysqli->query("INSERT INTO messages_incharge (store_id,storename,fromcellnumber,subuser_id,status,incharge_since) VALUES($store_id,'$storename','$fromcellnumber',-1,'1','$incharge_since')");
        }
    }
    
    
    public function isArchiveMsg($retire_days,$msg_date)
    {
        $current_date    = date('Y-m-d H:i:s');
        $diff            = strtotime($current_date) - strtotime($msg_date);
        $days_passed     = (int)$diff/(60*60*24);
        $archive_msg     = round($days_passed) >= $retire_days ? true : false; 
      
        return $archive_msg;
    }
    
    public function getMessageDate($store_id,$fromcellnumber)
    {
        $qw            = "select * from messages where  store_id like '{$store_id}' and fromcellnumber like '{$fromcellnumber}'  order by id desc limit 1";
        $messages_data = $this->mysqli->query($qw)->fetch_assoc();
        
        return $messages_data['date'];
    }
    
    
}

$CronForArchivingMessages = new CronForArchivingMessages($mysqli);









