<?php
session_start();

$return = 'anmelden.php';


foreach($_SESSION as $key => $value) {
	unset($_SESSION[$key]);
}

header("location:/".$return."?msg=loggedout");
?>
