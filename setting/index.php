<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/account-balance.php";


$store_id = $_SESSION['user-id'];
$mynumber = $_SESSION['mynumber'];
$subuser_id = $_SESSION['sub-user-id'];


if(empty($_SESSION['auth']) || $_SESSION['auth'] != $authcode) {
    header("location:/anmelden.php?msg=notauth");
    exit();
}

if(!isset($_SESSION['user-status']) || $_SESSION['user-status'] != 'sub_user') {
    header("Location:https://dasdenkich.de/members/");
    exit();
}

if ( isset($_SESSION['sub-user-id']) && !empty($_SESSION['sub-user-id']) ) {
    $id = $_SESSION['sub-user-id'];
    $sql = "select * from messages_subusers where id='$id'";
    $user = $mysqli->query($sql)->fetch_assoc();
}

function showError($error)
{
    if(isset($_SESSION['errmsg'][$error]) && !empty($_SESSION['errmsg'][$error])){
        echo  $_SESSION['errmsg'][$error];
        unset($_SESSION['errmsg'][$error]);
    }
}


function getMsgText()
{
    global $mysqli,$store_id,$subuser_id;
    $texts_arr = [];
    $query     = "SELECT * FROM messages_msg WHERE subuser_id = $subuser_id AND store_id = $store_id OR subuser_id = 0 AND store_id = $store_id AND text_is_public = 1 ";
    $texts     = $mysqli->query($query);
    
    while($row = $texts->fetch_assoc()) {
        $texts_arr[] = $row;
    };
    
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
    global $mysqli,$subuser_id;
    
    $store_id       = $_SESSION['user-id'];
    $messages_setup = $mysqli->query("SELECT * FROM messages_subusers WHERE id = $subuser_id AND store_id = $store_id");
    $my_contact     = $messages_setup->fetch_assoc();
    
    if($messages_setup->num_rows > 0) {
       return  $my_contact['contact_cell'];
    } 
  
}


$thisPage = "setting";
$title = $lang['title'][$l];

?>
<!DOCTYPE html>
<html lang="de">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $lang['title'][$l]; ?></title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/css/settings/settings.css">
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
            
            <ul class="nav nav-tabs dynamic-tabs">
               <li class="active"><a data-toggle="tab" href="#password">Change Password</a></li>
               <li><a data-toggle="tab" href="#text-table">Text Table</a></li>
               <li><a data-toggle="tab" href="#message-widget">Message Widget</a></li>
               <li><a data-toggle="tab" href="#my-contact">My contact</a></li>
               
            </ul>
            
            <div class="tab-content" >
                <div id="password" class="tab-pane fade in active">
                    <!--from-->
                    <div id="update-user" class="wrapper wrapper--w900">
                        <div class="card card-6">
                
                            <div class="card-body">
                
                                <form method="POST" name="create_sub_user" action="/setting/change-subuser-setting.php">
                                    <input type="hidden" name="action" value="change-password">
                                    <input type="hidden" name="id" value="<?=$user['id']?>">
                                  
                                    <div class="form-row">
                                        <h3 class="not_match_psw">
                                                <?php showError('not_match_psw'); ?>
                                            </h3>
                                        <div class="name">New Password</div>
                                        <div class="value">
                                            <div class="input-group" style="width: 100%;">
                                                <input id="new-password" class="input--style-6 new-password" type="password" name="new-password" placeholder="new password">
                                            </div>
                                        </div>
                                    </div>
                
                                </form>
                            </div>
                            <div class="card-footer">
                                <button class="submit_btn" type="submit">Update User</button>
                            </div>
                        </div>
                    </div>
                    <!--form end-->
                </div>    
                
                
                <div id="text-table" class="tab-pane fade">
                    <!--table-->

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
                                            <!--<th>Public</th>-->
                                        </tr>
                                    </thead>
                
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Text</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                            <!--<th>Public</th>-->
                                        </tr>
                                    </tfoot>
                
                                    <tbody>
                
                                        <?php
                                            $text = getMsgText();
                                            foreach($text as $key) {
                                                if($key['subuser_id'] == 0 ) {
                                                    $edit_btn   = 'Can not be changed';
                                                    $delete_btn = 'Can not be deleted';
                                                }  else {
                                                    $edit_btn   = '<button data-id="'.$key["id"].'" data-text="'.$key["text"].'" class="btn btn-primary btn-xs edit-text-btn" data-title="Edit" data-toggle="modal" data-target="#edit"><span class="glyphicon glyphicon-pencil"></span></button>';
                                                    $delete_btn = '<button  data-id="'.$key["id"].'" class="btn btn-danger btn-xs delete-text-btn" data-title="Delete" data-toggle="modal" data-target="#delete"><span class="glyphicon glyphicon-trash"></span></button>';
                                                }
                
                                        ?>
                                            <tr>
                                                <td>
                                                    <?=$key['id']?>
                                                </td>
                                                <td>
                                                    <?=$key['text']?>
                                                </td>
                
                                                <td>
                                                    <p data-placement="top" data-toggle="tooltip" title="Edit">
                                                        <?=$edit_btn?>
                                                    </p>
                                                </td>
                                                <td>
                                                    <p data-placement="top" data-toggle="tooltip" title="Delete">
                                                        <?=$delete_btn?>
                                                    </p>
                                                </td>
                                                <!--<td>-->
                                                <!--    <input class="text-public" type="checkbox" name="public" <?=$key['text_is_public'] == 1 ? 'checked' : '' ?> data-id="<?=$key['id']?>">-->
                                                <!--</td>-->
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
                              <p style="padding: 32px 23px 32px 23px;background: white;"><?php echo widgetIframe()?></p>
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
                
                                <form method="POST" name="change_my_number" action="/setting/change-subuser-setting.php">
                                    <input type="hidden" name="action" value="change_my_contact">
                                    <input type="hidden" name="id" value="<?=$user['id']?>">
                                  
                                    <div class="form-row">
                                       
                                        <div class="name">My contact</div>
                                        <div class="value">
                                            <div class="input-group" style="width: 100%;">
                                                <input id="new-contact" class="input--style-6 phone-number-input" value="<?=myContact()?>" type="number" name="new-contact" placeholder="new contact">
                                                <div class="country_code">+1</div>
                                            </div>
                                        </div>
                                    </div>
                
                                </form>
                            </div>
                            <div class="card-footer">
                                <button class="submit_btn" type="submit">Update Contact</button>
                            </div>
                        </div>
                    </div>
                    <!--form end-->
                </div>
                
                
                
            
            </div>    
            
            
            
    <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="/setting/change-subuser-setting.php">
                <input type="hidden" name="action" value="edit_text">
                <input class="text-id" type="hidden" name="id" value=''>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title custom_align" id="Heading">Edit Text</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input class="form-control edit-input" type="text" name="text" placeholder="Text...">
                    </div>
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
            <form class="modal-content" method="POST" action="/setting/change-subuser-setting.php">
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
            <form class="modal-content" action="/setting/change-subuser-setting.php" method="POST">
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

    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script language="JavaScript" src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="https://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>

        </div>    

    </div>            
            
</div>     






<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
<script>


    
    $(document).ready(function() {
        $('#datatable').dataTable();
        $("[data-toggle=tooltip]").tooltip();
        $('#datatable_filter').append('<button id="add-new-text-btn" data-title="Edit" data-toggle="modal" data-target="#create" >Add New Text</button>')
    } );
        
        
    
    $(document).on('click','.delete-text-btn',function(){
        $('#delete .text-id').val($(this).data('id'))
    })
    
    $(document).on('click','.edit-text-btn',function(){
        $('#edit .text-id').val($(this).data('id'))
        $('.edit-input').val($(this).data('text'));
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


    $('.submit_btn').click(function(){

        var confirm_err = 'Password and Confirm password not match';
        var submit = true;
        // var confirm_new_password = $('#confirm-new-password');
        var new_password = $('#new-password');
        var form = $('#update-user form');
        var inputs = $('#update-user .input--style-6');


        inputs.each(function(){
            if($(this).val() === '') {
                $(this).addClass('error_border');
                submit = false;
            }
        })

       
        if(!submit) {
            return false
        } else {
            form.submit()
        }
    })

    $(document).on('input','#update-user .input--style-6',function(){
        $(this).removeClass('error_border');
    })
</script>
</body>
</html>

