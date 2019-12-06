<?php
if(!isset($_SESSION)) {
     session_start();
}
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";

$return = 'anmelden.php';


// login from form
if(!empty($_POST['username']) && !empty($_POST['password'])) {
	$getUsername =  $_POST['username'];
	$getPassword = $_POST['password'];
} else {
	header("location: /".$return."?msg=nouser2");
	exit();
}

$getPassword = MD5($getPassword);


$result  =  $mysqli->query("SELECT * FROM messages_subusers WHERE sub_username = '$getUsername' and sub_pass = '$getPassword' ");
$user    = $result->fetch_assoc();
$numrows = $result->num_rows;

if($numrows < 1) {
	header("location: /".$return."?msg=nouser");
	exit();
}

$store_id   = $user['store_id'];
$msg_setup  = $mysqli->query("SELECT * FROM messages_setup WHERE store_id = $store_id ")->fetch_assoc();
    
if(!empty($msg_setup)) {
    $expire_date     = $msg_setup['expire'];
    $current_date    = date('Y-m-d');
    
    if((int)strtotime($current_date) >= (int)strtotime($expire_date)){
        header("location: /".$return."?msg=expire_date");
        exit();
    } 
}    


$_SESSION['auth']          = $authcode;
$_SESSION['user-id']       = $user['store_id'];
$_SESSION['user-name']     = $user['storename'];
$_SESSION['user-status']   = 'sub_user';
$_SESSION['sub-user-id']   = $user['id'];
$_SESSION['sub-user-name'] = $user['sub_name'];

$queryNumber               = "SELECT mynumber FROM messages_setup where store_id='{$user['store_id']}'  and storename='{$user['storename']}'";
$resultNumber              = $mysqli->query($queryNumber);
$arrNumber                 = $resultNumber->fetch_array(MYSQLI_ASSOC);
$_SESSION['mynumber']      = $arrNumber['mynumber'];

$subuser_id                = $_SESSION['sub-user-id'] ;
$storename                 = $user['storename'];
$date                      = date('Y-m-d H:i:s');

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$insert_sql = "INSERT INTO messages_logins (store_id,subuser_id,admin_id,storename,date,ip) VALUES($store_id,$subuser_id,0,'$storename','$date','$ip')";
$mysqli->query($insert_sql);

header("location: /members/");
exit();











