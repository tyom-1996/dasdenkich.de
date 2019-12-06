<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/account-balance.php";

$store_id = $_SESSION['user-id'];
$mynumber = $_SESSION['mynumber'];

if(empty($_SESSION['auth']) || $_SESSION['auth'] != $authcode) {
    header("location:/anmelden.php?msg=notauth");
    exit();
}

if(isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user') {
    header("Location:https://dasdenkich.de/members/");
    exit();
}


function getStrDomainUrl()
{
    global $mysqli;
    
    $store_id     = $_SESSION['user-id'];
    $store_domain = $mysqli->query("SELECT * FROM store WHERE str_id = $store_id")->fetch_assoc();
    $url          = 'https://'.$_SERVER['SERVER_NAME'].'/'.$store_domain['str_domain'];
    return $url;
}

function defaultMsg()
{
    // Default message
   
    global $mysqli;
    
    $store_id = $_SESSION['user-id'];
    $default = [];
   
    $msg_query        = "SELECT * FROM messages_msg WHERE subuser_id = 0 AND is_default = '1' AND store_id = $store_id ";
    $default_msg      =  $mysqli->query($msg_query);
    
    if($default_msg->num_rows < 1) {
        
        
        $url = getStrDomainUrl();
      
        $default_data = [
            'id' => 0,
            'store_id' => $store_id,
            'storename' => $_SESSION['user-name'],
            'storenumber' => $_SESSION['mynumber'],
            'subuser_id' => '0',
            'text' => "Do you have 30 seconds to leave a review for us? Just click the link below  $url " ,
            'text_is_public' => '0',
            'is_default' => 1
        ];
        
        $default = $default_data;
    } 
    
    return $default;
}


function getMsgText()
{
    global $mysqli,$store_id;
    $texts_arr = [];
    $query     = "SELECT * FROM messages_msg WHERE subuser_id = 0 AND store_id = $store_id";
    $texts     = $mysqli->query($query);

    while($row = $texts->fetch_assoc()) {
        $texts_arr[] = $row;
    };
    
    if(!empty(defaultMsg())) {
        $texts_arr[] = defaultMsg();
    }
    
   
    return $texts_arr;
}

function widgetIframe()
{
    if(isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user') {
        $id   =  $_SESSION['sub-user-id'];
        $type =  'sub_user';
    } else {
        $id   =  $_SESSION['user-id'];
        $type =  'admin';
    }
    
    return htmlspecialchars('<iframe id="message-iframe" style="border: none;height: 461px;position: fixed;bottom: 0;right: 0;width: 293px;z-index:999999;" src="https://dasdenkich.de/msg-widget/msg-widget.php?id='.$id.'&type='.$type.'"></iframe>') ;
}


function myContact()
{
    global $mysqli;
    $store_id       = $_SESSION['user-id'];
    $messages_setup = $mysqli->query("SELECT * FROM messages_setup WHERE store_id = $store_id ");
    $my_contact     = $messages_setup->fetch_assoc();
    if($messages_setup->num_rows > 0) {
       return  $my_contact['contact_cell'];
    } 
  
}

$days_query = "
SELECT * FROM messages_setup 
WHERE mynumber = $mynumber
AND store_id = $store_id";

$days = $mysqli->query($days_query)->fetch_assoc();
$thisPage = "admin-settings";
$title = $lang['title'][$l];



?>
<!DOCTYPE html>
<html lang="de">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title> <?php echo $lang['title'][$l]; ?> </title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/css/admin-settings/admin-settings.css">
</head>

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
                            <p>
                                <?=isset($_SESSION['sub-user-name']) ? $_SESSION['sub-user-name'] : 'Admin'  ?>
                            </p>
                            <p>
                                <?=$_SESSION['user-name']?>
                            </p>
                            <p>
                                <?=$mynumber?>
                            </p>
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
                                <path d="M 0 7.5 L 0 12.5 L 50 12.5 L 50 7.5 Z M 0 22.5 L 0 27.5 L 50 27.5 L 50 22.5 Z M 0 37.5 L 0 42.5 L 50 42.5 L 50 37.5 Z" />
                            </svg>
                        </li>
                        <!--<li class="logout">-->
                        <!--    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">-->
                        <!--        <path d="M16 9v-4l8 7-8 7v-4h-8v-6h8zm-16-7v20h14v-2h-12v-16h12v-2h-14z" />-->
                        <!--    </svg>-->
                        <!--    <a href="/includes/script-logout.php">Logout</a>-->
                        <!--</li>-->
                    </ul>
                </header>

                <div class="wrap">
                        

                    <div class="msg-filter-block" >
                        <h1 class ='msg-filter-title'> Settings</h1>
                    </div>
                    <!--from-->
                    
                    
                    
                <!--<div class="container">-->
                 
                  <ul class="nav nav-tabs dynamic-tabs">
                    <li class="active"><a data-toggle="tab" href="#days">Change Days</a></li>
                    <li><a data-toggle="tab" href="#password">Change Password</a></li>
                    <li><a data-toggle="tab" href="#text-table">Text Table</a></li>
                    <li><a data-toggle="tab" href="#message-widget">Message Widget</a></li>
                    <li><a data-toggle="tab" href="#my-contact">My contact</a></li>
                  </ul>
                
                  <div class="tab-content">
                      
                    <div id="days" class="tab-pane fade in active">
                        <div id="change-days" class="wrapper wrapper--w900">
                        <div class="card card-6">

                            <div class="card-body" >

                                <!--<span class="body_title">Change Days</span>-->

                                <form method="POST" name="change-admin-settings" action="/admin-settings/change-admin-settings.php">
                                    <input type="hidden" name="action" value="change_dates">

                                    <div class="error_message">
                                        <?php
                                            if(isset($_SESSION['days_errmsg']) && !empty($_SESSION['days_errmsg'])){
                                                echo  $_SESSION['days_errmsg'];
                                                unset($_SESSION['days_errmsg']);
                                            }
                                        ?>
                                    </div>
                                    <div class="form-row">
                                        <div class="name">Assign messages back to admin after</div>
                                        <div class="value">
                                            <div class="input-group">
                                                <input class="input--style-6 " type="number" name="backtoadmin_days" value="<?php echo $days['backtoadmin_days']; ?>" placeholder="Days">
                                                <div class="name2">Days</div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="form-row">

                                        <div class="name">Archive message</div>
                                        <div class="value">
                                            <div class="input-group">
                                                <input class="input--style-6 " type="number" name="retire_days" value="<?php echo $days['retire_days']; ?>" placeholder="Days">
                                                <div class="name2">Days</div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button class="submit_btn" type="submit">Update Days</button>
                            </div>
                        </div>
                    </div>
                    </div>
                    
                    <div id="password" class="tab-pane fade">
                       <!--Change password start-->
                        <div id="change-psw" class="wrapper wrapper--w900">
                            <div class="card card-6">
                                <div class="card-body">
                                    <!--<span class="body_title">Change Password</span>-->
                                    <form method="POST" name="create_sub_user" action="/admin-settings/change-admin-settings.php">
                                        <input type="hidden" name="action" value="change_password">
                                        <div class="form-row">
                                            <div class="name">New Password</div>
                                            <div class="value">
                                                <div class="input-group">
                                                    <input id="new-password" class="input--style-6 new-password" type="password" name="new-password" placeholder="new password">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer">
                                    <button class="submit_btn" type="submit">Update Password</button>
                                </div>
                            </div>
                        </div>
                        <!--Change password end-->
                    </div>
                    
                    <div id="text-table" class="tab-pane fade">
                           <div  id="data-table-container">
                        <!--<h2 id="datatable-title" class="text-center">Text Table</h2>-->
                        <div class="row" >
                            <div class="col-md-12">
                                <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Text</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                            <th>Public</th>
                                        </tr>
                                    </thead>

                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Text</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                            <th>Public</th>
                                        </tr>
                                    </tfoot>

                                    <tbody>

                                        <?php
                                        $text = getMsgText();
                                        foreach($text as $key) {
                                           $disable_default_msg = $key['is_default'] == 1 ? "disabled" : '';    
                                          
                                        ?>
                                            <tr>
                                                <td> <?=$key['id']?> </td>
                                                
                                                <td> <?=$key['text']?> </td>

                                                <td>
                                                    <p data-placement="top" data-toggle="tooltip" title="Edit">
                                                        <button data-id="<?=$key['id']?>" data-default="<?=$key['is_default']?>" data-text="<?=$key['text']?>" class="btn btn-primary btn-xs edit-text-btn" data-title="Edit" data-toggle="modal" data-target="#edit"><span class="glyphicon glyphicon-pencil"></span></button>
                                                    </p>
                                                </td>
                                                
                                                <td>
                                                    <p data-placement="top" data-toggle="tooltip" title="Delete">
                                                        <button <?= $disable_default_msg; ?> data-id="<?=$key['id']?>" class="btn btn-danger btn-xs delete-text-btn" data-title="Delete" data-toggle="modal" data-target="#delete"><span class="glyphicon glyphicon-trash"></span></button>
                                                    </p>
                                                </td>
                                                
                                                <td>
                                                    <input class="text-public" type="checkbox" name="public" <?=$key[ 'text_is_public']==1 ? 'checked' : '' ?> data-id="
                                                    <?=$key['id']?>">
                                                </td>
                                            </tr>
                                            <?php
                                        } 
                                        ?>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    </div>
                   
                   
                    <div id="message-widget" class="tab-pane fade">
                       <!--Change password start-->
                        <div id="change-psw" class="wrapper wrapper--w900">
                            <div class="card card-6">
                                <div class="card-body">
                                  <p style="padding: 20px;"><?php echo widgetIframe()?></p>
                                </div>
                            </div>
                        </div>
                        <!--Change password end-->
                    </div>
                    
                    
                    <div id="my-contact" class="tab-pane fade">
                       <!--from-->
                        <div id="my-number" class="wrapper wrapper--w900">
                            <div class="card card-6">
                    
                                <div class="card-body">
                    
                                    <form method="POST" name="change_my_number" action="/admin-settings/change-admin-settings.php">
                                        <input type="hidden" name="action" value="change_my_contact">
                                     
                                        <div class="form-row">
                                          
                                            <div class="name">My contact</div>
                                            <div class="value">
                                                <div class="input-group" style="width: 100%;">
                                                    <input id="new-contact" class="input--style-6 phone-number-input" type="number" name="new-contact" value="<?=myContact()?>" placeholder="new contact">
                                                    <div class="country_code" >+1</div>
                                                </div>
                                            </div>
                                        </div>
                    
                                    </form>
                                </div>
                                <div class="card-footer">
                                    <button class="submit_btn" >Update Contact</button>
                                </div>
                            </div>
                        </div>
                        <!--form end-->
                    </div>
                        
                    
                    
                    
                   
                  </div>
                <!--</div>-->
                    
                    
                    

                    

                    <!--form end-->

                   

               

                    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="/admin-settings/change-admin-settings.php">
                                <input type="hidden" name="action" value="edit_text">
                                <input class="text-id" type="hidden" name="id" value=''>
                                <input class="text-is-deault" type="hidden" name="is_default" value=''>
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title custom_align" id="Heading">Edit Text</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input class="form-control edit-input" type="text" name="text" placeholder="Text...">
                                    </div>
                                    <input name="str_domain_url" class="str_domain_url"  value="<?=getStrDomainUrl()?>" style="width: 100%;padding: 0 5px;text-align: right;background: white;color: silver;display:none">
                                    <script>
                                        $('.str_domain_url').keydown(function(e){
                                          e.preventDefault();
                                        });
                                    </script>
                                    
                                </div>
                                <div class="modal-footer ">
                                    <button class="btn btn-warning btn-lg" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span> UPDATE</button>
                                </div>
                            </form>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>

                    <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="create" aria-hidden="true">
                        <div class="modal-dialog">
                            <form class="modal-content" method="POST" action="/admin-settings/change-admin-settings.php">
                                <input type="hidden" name="action" value="create_new_text">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title custom_align" id="Heading">Create New Text</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input class="form-control " type="text" name="text" placeholder="Text">
                                    </div>
                                </div>
                                <div class="modal-footer ">
                                    <button class="btn btn-warning btn-lg" style="width: 100%;"><span class="glyphicon glyphicon-ok-sign"></span> Create</button>
                                </div>
                            </form>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>

                    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                        <div class="modal-dialog">
                            <form class="modal-content" action="/admin-settings/change-admin-settings.php" method="POST">
                                <input type="hidden" name="action" value="delete_text">
                                <input class="text-id" type="hidden" name="id" value=''>
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title custom_align" id="Heading">Delete this entry</h4>
                                </div>
                                <div class="modal-body">

                                    <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Record?</div>

                                </div>
                                <div class="modal-footer ">
                                    <button class="btn btn-success"><span class="glyphicon glyphicon-ok-sign"></span> Yes</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
                                </div>
                            </form>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>

                </div>

                <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>

            </div>
        </div>

        <!--table-->

        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
        <script language="JavaScript" src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script language="JavaScript" src="https://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>

        <!--table-->

        <script>
            $(document).ready(function() {
                $('#datatable').dataTable();
                $("[data-toggle=tooltip]").tooltip();
                $('#datatable_filter').append('<button id="add-new-text-btn" data-title="Edit" data-toggle="modal" data-target="#create" >Add New Text</button>')
            });

            $(document).on('click', '.delete-text-btn', function() {
                $('#delete .text-id').val($(this).data('id'))
            })

            $(document).on('click', '.edit-text-btn', function() {
                $('#edit .text-id').val($(this).data('id'))
                $("#edit .text-is-deault").val($(this).data('default'))
             
                
                if($(this).data('default') == 1){
                    
                    
                    $('.str_domain_url').show()
                    
                    var url = $('.str_domain_url').val();
                    var text = $(this).data('text').replace(url,'');
                    console.log(url,$(this).data('text'),text)
                    
                    
                    $('.edit-input').val(text)
                } else {
                    $('.edit-input').val($(this).data('text'));
                    $('.str_domain_url').hide()
                }
            })
            
            

            $(document).on('change', '.text-public', function() {

                var id = $(this).data('id'),
                    text_is_public = this.checked === true ? 1 : 0,
                    data = {
                        action: 'change_text_status',
                        id: id,
                        text_is_public: text_is_public
                    }

                $.ajax({
                    url: '/admin-settings/change-admin-settings.php',
                    type: 'POST',
                    data: data,
                    success: function(r) {
                        console.log(r)
                    }
                })

            })

            $('#change-days .submit_btn').click(function() {
                var form = $('#change-days form');
                form.submit()
            })

            $('#change-psw .submit_btn').click(function() {
                var form = $('#change-psw form');
                form.submit()
            })
            
             $('#my-contact .submit_btn').click(function() {
                var form = $('#my-contact form');
                form.submit()
            })
            
            
            document.querySelector(".phone-number-input").addEventListener("keypress", function (evt) {
                if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57)
                {
                    evt.preventDefault();
                }
            });

            // $('.submit_btn').click(function(){

            //     var confirm_err = 'Password and Confirm password not match';
            //     var submit = true;
            //     // var confirm_new_password = $('#confirm-new-password');
            //     var new_password = $('#new-password');
            //     var form = $('#update-user form');
            //     var inputs = $('#update-user .input--style-6');

            //     inputs.each(function(){
            //         if($(this).val() === '') {
            //             $(this).addClass('error_border');
            //             submit = false;
            //         }
            //     })

            //     // if( confirm_new_password.val() !== '' || new_password.val() !== '' )
            //     // {
            //     //     if( confirm_new_password.val() !== new_password.val() )
            //     //     {
            //     //         confirm_new_password.addClass('error_border');
            //     //         $('.error_message').text(confirm_err)
            //     //         return false;
            //     //     }
            //     // }

            //     if(!submit) {
            //         return false
            //     } else {
            //         form.submit()
            //     }
            // })

            // $(document).on('input','#update-user .input--style-6',function(){
            //     $(this).removeClass('error_border');
            // })
        </script>
</body>

</html>