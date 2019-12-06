<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";


if(empty($_SESSION['auth']) || $_SESSION['auth'] != $authcode) {
	header("location:/anmelden.php?msg=notauth");
	exit();
}

$message = '';
if($_GET['msg']=="success") {
	$message = '<div class="success">'."\n"
				.$lang['success'][$l]
				.'</div>';
} elseif($_GET['msg']=="error") {

	$message = '<div class="error">'."\n"
				.$lang['warn'][$l]
				.'</div>';
}

$number = $_GET['number'];


$query = 'SELECT * FROM messages WHERE store_id = '.$_SESSION['user-id'].' AND fromcellnumber = '.$number.' ORDER BY id DESC';
	$result = $mysqli->query($query);

$num = $result->num_rows;






$thisPage = "members";
$title = $lang['title'][$l];
?><!DOCTYPE html>
<html lang="de">
<head>
    <title><?php echo $lang['title'][$l]; ?></title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>
</head>

<body id="<?php echo $thisPage; ?>">
	<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/banner.php"; ?>
	<div id="main"><div class="wrap">
		<h1>Messages #<?php echo $number; ?></h1>
		<?php
		if(!empty($messages)) {
			echo $messages;
		} else {
			echo '<p>No messages</p>';
		}
		?>
		<h2>No name: would you like to attach a name</h2>
	</div></div>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>

</body>
</html>
