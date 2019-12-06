<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";

$id = $_GET['link'];




$query = 'SELECT str_name, wpr_storeid, wpr_webid, wpr_url '
		.'FROM webstoreplatform '
		.'LEFT JOIN store ON wpr_storeid = str_id '
		.'WHERE wpr_id = ?';

$statement = $mysqli->prepare($query);
$statement->bind_param('i',$id);
$statement->execute();
$statement->store_result();
$statement->bind_result($str_name, $wpr_storeid, $wpr_webid, $wpr_url);
$numrows = $statement->num_rows;


while($statement->fetch()) {
	$storeName = $str_name;
	$storeId = $wpr_storeid;
	$url = $wpr_url;
	$webid =  $wpr_webid;
}


$query = 'INSERT INTO clicks (date, userid, store, clicked_on, from_ip) VALUES ('
		//. '"'.date($dateFormat, $now).'", '
		. 'NOW(), '
		. $storeId.', '
		. '"'.$storeName.'", '
		. '"'.$_SESSION['platforms'][$wpr_webid]['name'].'", '
		. '"'.$_SESSION['ip'].'"'
		.')';
		$result = $mysqli->query($query);

if($result){
	header("location: ".$url);
} else {
	die('Error : ('. $mysqli->errno .') '. $mysqli->error);
}

//


?>
