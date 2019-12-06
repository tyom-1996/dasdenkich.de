<?php

$query = 'SELECT * FROM store where str_id = '.$id;
	$result = $mysqli->query($query);

if(!empty($mysqli->error)) {
	echo $mysqli->error;
	exit();
}

while($row = $result->fetch_assoc()) {
	//print_r($row);
	$title = $row['str_name'];
	$url = $row['str_url'];
	if(strlen($url) > 40) {
		$url2 = substr($url, 0, 41).'...';
	} else {
		$url2 = $url;
	}
}

$query = 'SELECT wpr_webid, wpr_id, user.* '
		.'FROM webstoreplatform '
		.'LEFT JOIN user ON wpr_storeid = id '
		.'WHERE wpr_storeid = '.$id.' '
		.'ORDER BY webstoreplatform.ord ASC'
		.'';

	$result = $mysqli->query($query);

$numPlatform = $result->num_rows;


$buttons = '';
if($numPlatform >= 1) {
	while($row = $result->fetch_assoc()) {
		extract($row);

		$buttons .= '<a href="/link/?link='.$wpr_id.'" title="'.$_SESSION['platforms'][$wpr_webid]['name'].'" target="_blank" class="button'.$wpr_webid.'">'
				  . '<img src="/images/'.$_SESSION['platforms'][$wpr_webid]['img'].'" alt="'.$_SESSION['platforms'][$wpr_webid]['name'].'">'
				  . '</a>';
	}
}

$thisPage = "store";

$d        = $_SERVER['REQUEST_URI'];
$domain   = str_replace('/', '', $d);
$str_name = $mysqli->query("SELECT * FROM store WHERE str_domain = '$domain' ")->fetch_assoc();


 ?><!DOCTYPE html>
<html lang="de">
<head>
    <title><?php echo $title; ?></title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>
</head>
<body id="<?php echo $thisPage; ?>">
	<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/banner.php"; ?>
	
	
	<div class="header" style="width: 100%;min-height: 95px;background: #3a579d;display: flex;justify-content: center;align-items: center;">
	    <span style="font-size: 38px;color: white;"><?php echo $str_name['str_name']?></span>
	</div>
	
	
	<div class="message">
		<?php
		if(!empty($invite_message)) {
			echo '<p>'.$invite_message.'</p>';
		} ?>
	</div>
	<div id="buttons">
		<?php echo $buttons; ?>
	</div>
	<div class="location">
		<p><?php
		echo '<a href="http://'.$url.'">'.$url2.'</a><br>'."\n"
			.$street.', '.$city.'<br>'."\n"
			.$postal
		?></p>
		<?php
		if(!empty($phone)) {
			echo '<p><a href="tel:'.$phone.'">'.$phone.'</a></p>';
		}
		?>
		</div>

	</div>
	<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
</body>
</html>
