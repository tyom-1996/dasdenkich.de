<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";


class WidgetFunctions {
    
    public $mysqli;
    public $post;
    public $store_id;
   
    public function __construct($mysqli)
    {
        $this->mysqli     = $mysqli;
        $this->post       = $_POST;
        
        $this->index();
    }
    
    public function index()
    {
        $user_type = $this->post['type'];
        
        switch ($user_type) {
            case 'admin':
                $this->adminWidget();
                break;
            case 'sub_user':
                $this->subuserWidget();
                break;
        }
       
    }
    
    
    public function adminWidget()
    {   
        $admin_id            = $this->post['id'];
        $name                = $this->post['name']; 
        $message             = $this->post['message']; 
        
        $store_query         = "SELECT * FROM store WHERE str_id = $admin_id";
        $store_data          = $this->mysqli->query($store_query)->fetch_assoc();
        $this->store_id      = $store_data['str_id'];
        $store_name          = $store_data['str_name'];
    
        $mynumber            = $this->getAdminNumber($store_name);
        $date                = date('Y-m-d H:i:s');
        $fromcellnumber      = $this->useCountryCodeOrNot();
        
        // print "<pre>";
        // print_r($this->post);die;
        $this->createContact($store_name,$fromcellnumber,$name);
        $this->insertMessage($store_name,$mynumber,$fromcellnumber,$message,$date);
        
        header("location:".$_SERVER['HTTP_REFERER']);    
    }
    
    public function getAdminNumber($store_name)
    {
        $queryNumber = "SELECT mynumber FROM messages_setup where store_id='{$this->store_id}'  and storename='{$store_name}'";
    	$resultNumber = $this->mysqli->query($queryNumber);
    	$arrNumber = $resultNumber->fetch_array(MYSQLI_ASSOC);
    	return $arrNumber['mynumber'];    
    }
    
    
    public function createContact($store_name,$fromcellnumber,$name)
    {
        $exist_contract    = $this->mysqli->query("SELECT * FROM messages_contacts WHERE storeid = $this->store_id  AND cellnumber = '$fromcellnumber'");
        
        if($exist_contract->num_rows > 0) {
            $contact_query = "UPDATE messages_contacts SET name = '$name',is_archive = '0' WHERE storeid = $this->store_id AND cellnumber = '$fromcellnumber' " ;
        } else {
            $contact_query = "INSERT INTO messages_contacts (storeid, storename, cellnumber, name,is_archive) VALUES($this->store_id,'$store_name','$fromcellnumber','$name','0')" ;
        }
       
        $this->mysqli->query($contact_query);
    }
    
    
    public function insertMessage($store_name,$mynumber,$fromcellnumber,$message,$date)
    {
        $insert_query = "INSERT INTO messages (store_id,storename,mynumber,fromcellnumber,message_incoming,date) VALUES($this->store_id,'$store_name','$mynumber','$fromcellnumber','$message','$date')";
        $this->mysqli->query($insert_query);
    }
    
    
    public function useCountryCodeOrNot()
    {
        $number       = $this->post['number'];
        $country_code = $this->getCountryCode();
        $pos          = strpos($number, $country_code );
        
        if($pos === false) {
             return $country_code.$number;
        } else {
            if($pos != 0) {
                 return $country_code.$number;
            } else {
                return $number;
            }
        }
       
        return $number;
    }
    
    
    public function getCountryCode()
    {   
        $msg_setup = $this->mysqli->query("SELECT * FROM messages_setup WHERE store_id = $this->store_id ")->fetch_assoc();
        return $msg_setup['country_code'];
    }

    
    
    
    public function subuserWidget()
    {
        $subuser_id          = $this->post['id'];
        $name                = $this->post['name']; 
        $message             = $this->post['message']; 
        
        $store_query         = "SELECT * FROM messages_subusers WHERE id = $subuser_id";
        $store_data          = $this->mysqli->query($store_query)->fetch_assoc();
        $this->store_id      = $store_data['store_id'];
        $store_name          = $store_data['storename'];
       
        $fromcellnumber      = $this->useCountryCodeOrNot(); 
        $mynumber            = $this->getAdminNumber($store_name);
        $date                = date('Y-m-d H:i:s');
        
        $this->createContact($store_name,$fromcellnumber,$name);
        $this->insertMessage($store_name,$mynumber,$fromcellnumber,$message,$date);
        
        header("location:".$_SERVER['HTTP_REFERER']);    
    }
    
}

$WidgetFunctions = new WidgetFunctions($mysqli);









