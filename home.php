<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";

$message = '';

if(!empty($_GET['msg'])) {
	$message = '<div class="'.$errors[$l][$_GET['msg']][0].'">'
				.$errors[$l][$_GET['msg']][1]
				.'</div>';
}


$thisPage = "home";
?><!DOCTYPE html>
<html lang="de">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $lang['title'][$l]; ?></title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>
</head>

<body class='eeeeee' id="<?php echo $thisPage; ?>">
	<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/banner.php"; ?>


	<div id="hero">
		<div class="hero-content">

			<h1><?php echo $lang['headline1'][$l]; ?></h1>
			<img src="/images/phones-<?php echo $l; ?>.png" class="phones">
		</div>

			<video autoplay preload muted loop>
			 <source src="/video/background2.mp4" type="video/mp4">
			</video>

	</div>
	<div id="main" class="home-section">
		<div>
			<?php echo $lang['home-main'][$l]; ?>


		</div>
	</div>
	<div id="main2" class="home-section">
		<div>
			<?php echo $lang['home-main2'][$l]; ?>

		</div>
	</div>
	<div id="main3" class="home-section">
		<div>
			<img src="/images/phones-<?php echo $l; ?>.png" class="phones">
			<div>
				<?php echo $lang['home-main3'][$l]; ?>

			</div>
		</div>
	</div>
	<div id="main4" class="home-section">
		<div>
			<?php echo $lang['home-main4'][$l]; ?>
		</div>
	</div>
	<div id="mitmachen" class="home-section">
		<div>
			<h2><?php echo $lang['home-mitmachen'][$l]; ?></h2>
			<form method="post" action="/includes/send-contact.php">
				<input type="hidden" name="site" value="<?php echo $lang['domain'][$l]; ?>">
			<?php echo $message; ?>
				<input type="text" name="subject" value="" class="gone">
				<div>
					<label>name</label>
					<span><input type="text" name="name" id="name" placeholder="Name"></span>
				</div>
				<div>
					<label>email</label>
					<span><input type="email" name="email" id="email" placeholder="Email"></span>
				</div>
				<div>
					<label>message</label>
					<span><textarea name="message" id="message" placeholder="<?php echo $lang['message'][$l]; ?>"></textarea></span>
				</div>
				<div class="submit">
					<button><?php echo $lang['send'][$l]; ?></button>
				</div>
			</form>
		</div>
	</div>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
<iframe id="message-iframe" style="border: none;height: 461px;position: fixed;bottom: 0;right: 0;width: 293px;" src="https://dasdenkich.de/msg-widget/msg-widget.php?id=298148752&type=admin"></iframe>
</body>
</html>
