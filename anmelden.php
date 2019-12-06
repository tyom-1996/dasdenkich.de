<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";


$message = '';
if(!empty($_GET['msg'])) {
    if($_GET['msg'] == 'expire_date') {
       $message = "<div class='expire_date'> Expire date is passed </div>"; 
    } else {
        $message = '<div class="'.$errors[$l][$_GET['msg']][0].'">'
				.$errors[$l][$_GET['msg']][1]
				.'</div>';
    }

	
}


$thisPage = "inner";
?><!DOCTYPE html>
<html lang="de">
<head>
    <title><?php echo $lang['title'][$l]; ?></title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>
</head>

<style>
    .sub-user-login{
        margin-top: 72px!Important;
    }

    .expire_date{
        padding: 10px;
        background: #b33131;
        color: white;
        font-size: 16px;}
    }
</style>  

<body id="<?php echo $thisPage; ?>">

	<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/banner.php"; ?>

	<div id="mitmachen" class="home-section">
		<div>
<!--			<h2>--><?php //echo $lang['login-headline'][$l]; ?><!--</h2>-->

			<h2>ADMIN LOGIN </h2>
			<form method="post" action="/includes/script-login.php">
				<input type="hidden" name="site" value="<?php echo $lang['domain'][$l]; ?>">
			<?php echo $message; ?>
				<input type="text" name="subject" value="" class="gone">
				<div>
					<label>user</label>
					<span><input type="text" name="username" id="username" placeholder="username"></span>
				</div>
				<div>
					<label>password</label>
					<span><input type="password" name="password" id="password" placeholder="password"></span>
				</div>
				<div class="submit">
					<a href="#">forgot password?</a>
					<button><?php echo $lang['login-submit'][$l]; ?></button>
				</div>
			</form>
		</div>
		
		
		<div class="sub-user-login">
			<h2>USER LOGIN</h2>
			<form method="post" action="/includes/script-subuser-login.php">
				<input type="hidden" name="site" value="<?php echo $lang['domain'][$l]; ?>">
			<?php echo $message; ?>
				<input type="text" name="subject" value="" class="gone">
				<div>
					<label>user</label>
					<span><input type="text" name="username" id="username" placeholder="username"></span>
				</div>
				<div>
					<label>password</label>
					<span><input type="password" name="password" id="password" placeholder="password"></span>
				</div>
				<div class="submit">
					<a href="#">forgot password?</a>
					<button><?php echo $lang['login-submit'][$l]; ?></button>
				</div>
			</form>
		</div>
	</div>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>

</body>
</html>
