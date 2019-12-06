<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/short-url/index.php";

$numrows = 0;

$store = $_SERVER['REQUEST_URI'];
$store = str_replace("/", "", $store);
$query = 'SELECT str_id FROM store WHERE str_domain = ?';
$statement = $mysqli->prepare($query);
$statement->bind_param('s',$store);
$statement->execute();
$statement->store_result();
$statement->bind_result($str_id);
$numrows = $statement->num_rows;

while($statement->fetch()) {
	$id = $str_id;
}

if($numrows>=1) {
	include_once $_SERVER['DOCUMENT_ROOT'] . "/store.php";
} else {
    
    if (strpos($thisURL, "frogswing.com") || strpos($thisURL, "dasdenkich.de")) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/frg-home.php";
    } else {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/home.php";
    }
    
}

?>
