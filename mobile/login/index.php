<?php
    session_start();
    
    if(isset($_GET['type'])){
        $user_type = $_GET['type'];
    } else {
        echo '<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">';
        echo "<h2 style='width: 90%;max-width: 400px;height: 91px;position: absolute;top: 0;bottom: 0;left: 0;right: 0;margin: auto;background: #ff000029;display: flex;justify-content: center;align-items: center;font-size: 20px;' class='wrong-url'>WRONG URL</h2>";die;
    }
?>

<!doctype html>
<html lang="`en`">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<link href="/mobile/login/login.css" rel="stylesheet">


<body>
   <header>
        <div class="logo">
            <img src="/images/daskennich-logo.png" >
        </div>
       <span class="site-name"> Dasdenkich.de</span>
    </header>
    <div class="container-wrap">
        <div class="form-section">
            <form method="post" action="/mobile/login/login-function.php">
                
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-block" style="padding-bottom: 15px;">
                            <strong style="color: #ff4747;"><?=$_SESSION['error']?></strong>
                        </div>
                    <?php endif; ?>
                    <?php unset($_SESSION['error']);?>
                    
                   <input type="hidden" name="user_type" value="<?=$user_type?>">
                   <input type="hidden" name="action" value="login">
                   <div class="form-group">
                       <input type="text" name="username" class="form-control email" placeholder="User name">
                       <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="envelope" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-envelope fa-w-16 fa-3x"><path fill="currentColor" d="M464 64H48C21.5 64 0 85.5 0 112v288c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM48 96h416c8.8 0 16 7.2 16 16v41.4c-21.9 18.5-53.2 44-150.6 121.3-16.9 13.4-50.2 45.7-73.4 45.3-23.2.4-56.6-31.9-73.4-45.3C85.2 197.4 53.9 171.9 32 153.4V112c0-8.8 7.2-16 16-16zm416 320H48c-8.8 0-16-7.2-16-16V195c22.8 18.7 58.8 47.6 130.7 104.7 20.5 16.4 56.7 52.5 93.3 52.3 36.4.3 72.3-35.5 93.3-52.3 71.9-57.1 107.9-86 130.7-104.7v205c0 8.8-7.2 16-16 16z" class=""></path></svg>
                   </div>
                   <div class="form-group">
                       <input type="password" name="password" class="form-control password" placeholder="Password">
                       <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="lock-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-lock-alt fa-w-14 fa-3x"><path fill="currentColor" d="M224 420c-11 0-20-9-20-20v-64c0-11 9-20 20-20s20 9 20 20v64c0 11-9 20-20 20zm224-148v192c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V272c0-26.5 21.5-48 48-48h16v-64C64 71.6 136-.3 224.5 0 312.9.3 384 73.1 384 161.5V224h16c26.5 0 48 21.5 48 48zM96 224h256v-64c0-70.6-57.4-128-128-128S96 89.4 96 160v64zm320 240V272c0-8.8-7.2-16-16-16H48c-8.8 0-16 7.2-16 16v192c0 8.8 7.2 16 16 16h352c8.8 0 16-7.2 16-16z" class=""></path></svg>
                   </div>

                    <div class="checkbox-block clearfix">
                        <div class="form-check checkbox-theme">
                            <input class="form-check-input" type="checkbox" value="" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">
                                <span>Remember me</span>
                            </label>
                        </div>
                        <a class="forgot-psw" href="forgot-password-1.html">Forgot Password</a>
                    </div>


                   <div class="form-group">
                       <input type="submit" name="login" class="submit" value="Login">
                   </div>
            </form>
        </div>
    </div>
</body>
</html>




