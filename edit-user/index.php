<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/account-balance.php";

if(empty($_SESSION['auth']) || $_SESSION['auth'] != $authcode) {
    header("location:/anmelden.php?msg=notauth");
    exit();
}

if(isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user') {
    header("Location:https://dasdenkich.de/members/");
    exit();
}

if ( !empty($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "select * from messages_subusers where id='$id'";
    $user = $mysqli->query($sql)->fetch_assoc();
}

$thisPage = "edit-user";
$title = $lang['title'][$l];

?>
<!DOCTYPE html>
<html lang="de">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>
    <?php echo $lang['title'][$l]; ?>
</title>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>
    <link rel="stylesheet" href="/css/edit-user/edit-user.css">
</head>

<body id="<?php echo $thisPage; ?>">
<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/banner.php"; ?>

<div id=main>

    <div class="left">
        <div class="left-sidebar-header">
            <h3>dasdenkich.de</h3>
        </div>
        <div class="left-sidebar-content">
            <div class="profile-info">
                <div class="profile-info-left">
                   <?php  include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/logo-content.php"; ?>
                </div>
                <div class="profile-info-right">
                    <p> <?=isset($_SESSION['sub-user-name']) ? $_SESSION['sub-user-name'] : 'Admin'  ?> </p>
                    <p> <?=$_SESSION['user-name']?> </p>
                    <p><?=$mynumber?></p>
                </div>
            </div>
            
            <?php echo showAccountBalance();?>
            <div class="nav-header">MAIN NAVIGATION</div>
            <?php echo $secondNav;?>
        </div>
    </div>

    <div class="right">

        <header>
            <ul>
                <li class="toggle-sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="100" height="100" fill="#000000"><path d="M 0 7.5 L 0 12.5 L 50 12.5 L 50 7.5 Z M 0 22.5 L 0 27.5 L 50 27.5 L 50 22.5 Z M 0 37.5 L 0 42.5 L 50 42.5 L 50 37.5 Z" /></svg>
                </li>
                <!--<li class="logout">-->
                <!--    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16 9v-4l8 7-8 7v-4h-8v-6h8zm-16-7v20h14v-2h-12v-16h12v-2h-14z" /></svg>-->
                <!--    <a href="/includes/script-logout.php">Logout</a>-->
                <!--</li>-->
            </ul>
        </header>

        <div id="wrap">
            <div class="msg-filter-block">
                <h1 class='msg-filter-title'> Edit User</h1>
            </div>
            <!--from-->
            <div id="update-user" class="wrapper wrapper--w900">
                <div class="card card-6">

                    <div class="card-body">
                       
                        <form method="POST" name="create_sub_user" action="/edit-user/edit-user-functions.php" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="edit-user">
                            <input type="hidden" name="id" value="<?=$user['id']?>">

                            <div class="error_message">
                                <?php 
                                    if(!empty($_SESSION['errmsg'])){
                                        print_r($_SESSION['errmsg']);
                                        unset($_SESSION['errmsg']);
                                    }
                                ?>
                            </div>

                            <div class="form-row">
                                <div class="name">Name</div>
                                <div class="value">
                                    <input class="input--style-6" type="text" name="name" placeholder="name" value="<?php echo $user['sub_name']?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="name">Username</div>
                                <div class="value">
                                    <div class="input-group">
                                        <input class="input--style-6" type="text" name="username" placeholder="username" value="<?php echo $user['sub_username']?>">
                                    </div>
                                </div>
                            </div>
                            
                             <div class="form-row">
                                <div class="name">Phone Number</div>
                                <div class="value">
                                    <div class="input-group" style="position: relative;">
                                        <input class="input--style-6" type="text" name="contact_cell" placeholder="Phone number" value="<?php echo $user['contact_cell']?>" style="padding-left: 33px;">
                                        <div class="country_code">+1</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="name">Password</div>
                                <div class="value">
                                    <div class="input-group">
                                        <input id="password" class="input--style-6 password" type="password" name="password" placeholder="password">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="name">Confirm Password</div>
                                <div class="value">
                                    <div class="input-group">
                                        <input id="confirm-password" class="input--style-6 confirm-password" type="password" name="password-confirm" placeholder="confirm password">
                                    </div>
                                </div>
                            </div>
                            
                             <!---->
                                
                                <div class="form-row" style="padding: 24px 55px;">
                                    <div class="value" style="width: 100%;padding: 43px;border: 1px dashed #3c8dbc;background: #ecf0f5;">
                                        <div class="input-group" style="display: flex;justify-content: center;">
                                            <label for="logoToUpload" style="text-align: center;cursor: pointer;"> 
                                                <svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43" style="fill: #367fa9;margin-bottom: 10px;">
                                                    <path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"></path>
                                                </svg>
                                                <span style="display: block;font-weight: 600;">Choose a file</span>
                                            </label>
                                            <input type="file" name="logoToUpload" id="logoToUpload" style="display: none;">
                                        </div>
                                    </div>
                                </div>
                               
                               <!---->

                        </form>
                    </div>
                    <div class="card-footer">
                        <button class="submit_btn" type="submit">Update User</button>
                    </div>
                </div>
            </div>
            <!--form end-->
        </div>
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
    </div>
</div>

<script>
    $('.submit_btn').click(function() {

        var confirm_err = 'Password and Confirm password not match';
        var submit = true;

        $('#update-user .input--style-6').each(function(element) {

            if (!$(this).hasClass('password') && !$(this).hasClass('confirm-password')) {
                if ($(this).val() == '') {
                    $(this).addClass('error_border');
                    submit = false;
                }
            }

        })

        if ($('#confirm-password').val() != '' || $('#password').val() != '') {
            if ($('#confirm-password').val() != $('#password').val()) {
                $('#confirm-password').addClass('error_border');
                $('.error_message').text(confirm_err)
                return false;
            }
        }

        console.log(submit)

        if (!submit) {
            return false
        } else {
            $('#update-user form').submit()
        }

    })

    $(document).on('input', '#update-user .input--style-6', function() {
        $(this).removeClass('error_border');
    })
</script>
</body>

</html>