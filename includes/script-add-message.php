<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";

$message =  mysqli_real_escape_string($mysqli, $_POST['message']);


$query = 'INSERT INTO messages '
	   . '(store_id, storename, mynumber, fromcellnumber, message_out_sending) '
	   . 'VALUES '
	   .'('.$_SESSION['user-id'].', "'.$_SESSION['user-name'].'" , 14168487447, '.$_POST['number'].', "'.$message.'")';


	$result = $mysqli->query($query);

	if($result){
		header("location: /members/?msg=addmessage#detail-".$_POST['number']);
	} else {
		header("location: /members/?msg=addmessageerror#detail-".$_POST['number']);
	}


?>
