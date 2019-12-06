<?php

// GET CURRENT PAGE URL
function curPageURL() {
	$pageURL = 'http';
	if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}

		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
	return $pageURL;
}


// SET LANGUAGE

$l = 'de';
$thisURL = curPageURL();

if (strpos($thisURL, "frogswing.com") !== false) {
	$l = 'en';
}



$authcode = 'fl1CVmcYYjQg84Q4Q1FJbkeNUU&ZkQR';
$now = time();
$dateFormat = "M j y - g:ia";



//if(empty($_SESSION['platforms'])) {
$_SESSION['platforms'] = array();
	$query = 'SELECT * FROM webplatform';
	$result = $mysqli->query($query);
	while($row=$result->fetch_assoc()) {
		$_SESSION['platforms'][$row['web_id']]['img'] = $row['web_imagefilename'];
		$_SESSION['platforms'][$row['web_id']]['name'] = $row['web_name'];
	}
//}

if(empty($_SESSION['ip'])) {
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    if (!filter_var($_SESSION['ip'], FILTER_VALIDATE_IP)) {
        $_SESSION['ip'] = '';
    }
}

