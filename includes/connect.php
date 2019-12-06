<?php
//Open a new connection to the MySQL server
$host = 'localhost';
$username = 'incomc6_angie';
$password = 'angiePassword';
$database_name = 'incomc6_dasdenkich';

$mysqli = new mysqli($host, $username, $password, $database_name);
mysqli_set_charset($mysqli,"utf8");

//Output any connection error
if ($mysqli->connect_error) {
// 	echo $mysqli->connect_error;
    header("location: /maintenance/");
}

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// if(!empty($_GET['session'])) {
// 	print_r($_SESSION);
// }
