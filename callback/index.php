<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";

$to      =  mysqli_real_escape_string($mysqli, $_GET['to']);
$query   = 'SELECT * FROM messages_setup WHERE mynumber = '.$to;
$result  = $mysqli->query($query);
$num     = $result->num_rows;

$from    = mysqli_real_escape_string($mysqli, $_GET['from']);
$message = mysqli_real_escape_string($mysqli, $_GET['text']);
$date    = mysqli_real_escape_string($mysqli, $_GET['datetime']);
$date    = date("Y-m-d H:i:s", strtotime($date));

if($num>=1) {
	while($row = $result->fetch_assoc()) {
		$id        = $row['store_id'];
		$storename = $row['storename'];
	}
} else {
	$id        = 0;
	$storename = NULL;
}

$query = 'INSERT INTO messages (store_id, storename, mynumber, fromcellnumber, message_incoming, date) VALUES ('.$id.', "'.$storename.'", '.$to.', '.$from.', "'.$message.'","'.$date.'")';
$result = $mysqli->query($query);


?>
