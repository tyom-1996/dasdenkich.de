<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/account-balance.php";

if(empty($_SESSION['auth']) || $_SESSION['auth'] != $authcode) {
	echo "<script>window.location = '/anmelden.php?msg=notauth'; </script>";
	exit();
}

$id        = $_SESSION['user-id'];
$users     = $mysqli->query("select * from messages_subusers where store_id = '$id' ");
$users_arr = [];

while($row = $users->fetch_assoc()) {
    $users_arr[] = $row;
};
 
$thisPage = "users";
$title    = $lang['title'][$l];



?><!DOCTYPE html>
<html lang="de">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $lang['title'][$l]; ?></title>
    <!--<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">-->
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css">-->
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.foundation.min.css">-->
    <?php  include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>
    <link rel="stylesheet" href="/css/users/users.css">

</head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.css">

<style>
    table#datatable td img {
        width: 30px;
        display: inline-block;
    }
</style>

<body id="<?php echo $thisPage; ?>">
	<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/banner.php"; ?>
    
    <div id="main">
        
        
            
        <div class="left">
            <div class="left-sidebar-header">
                <h3><?= $_SERVER['HTTP_HOST']?></h3>
            </div>
            <div class="left-sidebar-content">
                <div class="profile-info">
                    <div class="profile-info-left">
                       <?php  include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/logo-content.php"; ?>
                    </div>
                    <div class="profile-info-right">
                        <p><?=isset($_SESSION['sub-user-name']) ? $_SESSION['sub-user-name'] : 'Admin'  ?></p>
                        <p><?=$_SESSION['user-name']?></p>
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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="100" height="100" fill="#000000">
                          <path d="M 0 7.5 L 0 12.5 L 50 12.5 L 50 7.5 Z M 0 22.5 L 0 27.5 L 50 27.5 L 50 22.5 Z M 0 37.5 L 0 42.5 L 50 42.5 L 50 37.5 Z"/>
                        </svg>
                    </li>
                    <!--<li class="logout">-->
                    <!--    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16 9v-4l8 7-8 7v-4h-8v-6h8zm-16-7v20h14v-2h-12v-16h12v-2h-14z"/></svg>-->
                    <!--    <a href="/includes/script-logout.php">Logout</a>-->
                    <!--</li>-->
                </ul>
            </header>
        
        
        
            
            <div class="wrap">
                
               <div class="msg-filter-block" >
                    
                    <h1 class ='msg-filter-title'> Users Table</h1>
                    
                </div>
                
                
                <!--<div class="container" id="data-table-container">-->
                    
                    <div class="row" >
                        <div class="col-md-12">
                            <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Logo</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <?php 
                                            if( !isset($_SESSION['user-status']) ||  $_SESSION['user-status'] != 'sub_user' ){
                                                echo "<th>Edit</th>";
                                            }
                                        ?>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Logo</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <?php 
                                            if( !isset($_SESSION['user-status']) ||  $_SESSION['user-status'] != 'sub_user' ){
                                                echo "<th>Edit</th>";
                                            }
                                        ?>
                                    </tr>
                                </tfoot>
                                <tbody>
                
                                    <?php 
                                        for($i=0; $i < count($users_arr); $i++) {
                                            $id = $users_arr[$i]['id'];
                                            ?>
                                            <tr>
                                                <td><?=$id?></td>
                                                <td><?php echo !empty($users_arr[$i]['logo']) ? '<img src ="/logos/'.$users_arr[$i]['logo'].'" >' : "<img src ='/images/user.png' >"; ?></td>
                                                <td><?=$users_arr[$i]['sub_name']?></td>
                                                <td><?=$users_arr[$i]['sub_username']?></td>
                                                
                                                <?php
                                                    if( !isset($_SESSION['user-status']) ||  $_SESSION['user-status'] != 'sub_user' ){
                                                        ?>
                                                            <td class='edit_btn'>
                                                                <a href='/edit-user?id=<?php echo $id?>' >
                                                                    <svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' id='Capa_1' x='0px' y='0px' viewBox='0 0 383.947 383.947' style='enable-background:new 0 0 383.947 383.947;' xml:space='preserve'><g><g><g><polygon points='0,303.947 0,383.947 80,383.947 316.053,147.893 236.053,67.893'/><path d='M377.707,56.053L327.893,6.24c-8.32-8.32-21.867-8.32-30.187,0l-39.04,39.04l80,80l39.04-39.04 C386.027,77.92,386.027,64.373,377.707,56.053z'/></g></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                                                                </a>
                                                            </td>
                                                        <?php
                
                                                    }
                                                ?> 
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                
                        </div>
                    </div>
                <!--</div>-->
                
            </div>  
            
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
        </div>
        
        
        
    </div>
    
    
    
    
    
    
    



   <!--<script type="text/javascript" charset="utf-8" src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>-->
   <!--<script type="text/javascript" charset="utf-8" src="https://cdn.datatables.net/1.10.20/js/dataTables.foundation.min.js"></script>-->
   
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script language="JavaScript" src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="https://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>
    
   
   
   
    <script type="text/javascript" charset="utf-8">

        $(document).ready(function() {
            $('#datatable').dataTable();
            $("[data-toggle=tooltip]").tooltip();
        });

    </script> 
</body>
</html>

