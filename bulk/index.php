<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/bulk/view-functions/bulk-view-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/account-balance.php";


$id                = $_SESSION['user-id'];
$bulk_messages_arr = $BulkViewFunctions->bulk_messages_arr;
$contacts_count    = $BulkViewFunctions->contacts_count;

$prices            = $BulkViewFunctions->getPrices();
$u160              = $prices['u160'];
$u160markup        = $prices['u160markup'];
$u160exchange      = $prices['u160exchange'];

$u320              = $prices['u320'];
$u320markup        = $prices['u320markup'];
$u320exchange      = $prices['u320exchange'];

$u160_price        = $u160 * $u160markup * $u160exchange;
$u320_price        = $u320 * $u320markup * $u320exchange;


$thisPage          = $BulkViewFunctions->this_page;
$title             = $lang['title'][$l];



?><!DOCTYPE html>
<html lang="de">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title><?php echo $lang['title'][$l]; ?></title>
    <?php  include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>
    <link rel="stylesheet" href="/css/bulk/bulk-messaging.css">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>-->
</head>
    <!--<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.css">

<style>
    table#datatable td img {
        width: 35px;
        display: block;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 20px;
        margin: auto;
    }
    table#datatable td{
        font-size: 14px;
        padding-left: 21px;
        position:relative;
    }
    .table-bordered>tfoot>tr>th  {
        padding:8px 18px;
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
            <?php  include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/right-header.php"; ?>
            
            <div class="wrap">
                
                <div class="msg-filter-block" >
                    <h1 class ='msg-filter-title'>Bulk Messaging</h1>
                    <a class="create-new-message" href="/bulk/add-message.php">create a new bulk message</a>
                </div>

                <div class="row" >
                    <div class="col-md-12">
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date created</th>
                                    <th>Message</th>
                                    <th>Image</th>
                                    <th>Date finished</th>
                                    <th>Date sent</th> 		
                                    <th>Edit</th>
                                    <th>Bulk send </th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Date created</th>
                                    <th>Message</th>
                                    <th>Image</th>
                                    <th>Date finished</th>
                                    <th>Date sent</th> 		
                                    <th>Edit</th>
                                    <th>Bulk send</th>
                                </tr>
                            </tfoot>
                            <tbody>
            
                                <?php 
                                    for($i=0; $i < count($bulk_messages_arr); $i++) {
                                        $id = $bulk_messages_arr[$i]['id'];
                                        $characters_count = $BulkViewFunctions->GetUsedCharacters($bulk_messages_arr[$i]['text'],$bulk_messages_arr[$i]['image_link'],$bulk_messages_arr[$i]['include_store_name']);
                                        
                                        if($characters_count > 160) {
                                           $characters_price = $u320_price * $contacts_count;
                                        } else {
                                           $characters_price = $u160_price * $contacts_count;  
                                        }
                                        
                                        
                                        ?>
                                        <tr>
                                            <td><?=$id?></td>
                                            <td><?=$bulk_messages_arr[$i]['date_created']?></td>
                                            <td><?= substr($bulk_messages_arr[$i]['text'], 0, 20); ?></td>
                                            <td><img src="<?=$bulk_messages_arr[$i]['image_link']?>"></td>
                                            <td><?=$bulk_messages_arr[$i]['date_finished']?></td> 	
                                            <td><?=$bulk_messages_arr[$i]['date_sent']?></td> 	
                                            
                                            <?php if($bulk_messages_arr[$i]['date_finished'] === null ): ?>
                                                <td class='edit_btn enable'>
                                                   
                                                    <button class="btn btn-primary btn-xs " data-title="Edit">
                                                        <a href="/bulk/edit-message.php?id=<?php echo $id?>">
                                                            <span class="glyphicon glyphicon-pencil" style="color: white;"></span>
                                                        </a>
                                                    </button>
                                                </td>
                                                
                                                <td class='send-bulk-message enable'>
                                                    <div style="width: 38px;height: 32px;position: relative;">
                                                        <span class="glyphicon glyphicon-send" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;margin: auto;color: white;width: 16px;height: 14px;"></span>
                                                       	<input class="btn btn-danger btn-xs delete-text-btn bulk-send" data-id="<?php echo $id?>" data-price="<?=$characters_price?>" type="button" data-placement="top" data-toggle="modal" title="Bulk" data-target="#bulk-message-modal" style="width: 100%;">
                                                    </div>
                                                
                                                </td>
                                            <?php else: ?>
                                                <td class='edit_btn disable'>
                                                   <button class="btn btn-primary btn-xs " data-title="Edit" disabled="">
                                                        <a>
                                                            <span class="glyphicon glyphicon-pencil" style="color: white;"></span>
                                                        </a>
                                                    </button>
                                                </td>
                                                <td class='send-bulk-message disable'>
                                                    <div style="width: 38px;height: 32px;position: relative;" class="DTFC_Cloned btn btn-danger btn-xs" disabled="">
                                                        <span class="glyphicon glyphicon-send" style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;margin: auto;color: white;width: 16px;height: 14px;font-size: 14px;"></span>
                                                    </div>
                                                </td>
                                            <?php endif; ?>
                                            
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
    
    
    
    
    
    <div class="modal fade" id="bulk-message-modal" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="/bulk/bulk-functions.php" method="POST">
                
                <input type="hidden" name="action" value="send_bulk">
                <input class="bulk-id" type="hidden" name="bulk_id" value="">
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title custom_align" id="Heading">Send bulk message</h4>
                </div>
                <div class="modal-body" style="padding: 0 15px;">
                    <div class=" success">
                        
                        You are about to send this message to <?=$contacts_count?> of you contacts, are you sure you want to proceed?
                   </div>
                   
                   <div class="success">
                        
                        The cost of sending a message to 
                        <span class="contacts_count"> 
                            <?=$contacts_count?> users is $ <span class="msg-price" >
                        </span>
                   </div>
                   
                </div>
                <div class="modal-footer ">
                    <button class="btn btn-success"><span class="glyphicon glyphicon-ok-sign"></span> Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
                </div>
                
            </form>
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
            $('#datatable').dataTable({
                 order: [ [0, 'desc'] ]
            });
            $("[data-toggle=tooltip]").tooltip();
            
            $(document).on('click','.bulk-send',function(){
                var bulk_id = $(this).data('id');
                $("#bulk-message-modal form .bulk-id").val(bulk_id)
                
                var characters_price     = $(this).data('price');
                $('.msg-price').html(characters_price);
                
                var system_wallet_money = $('.system-wallet-money-input').val();
              
                if(characters_price > system_wallet_money){
                    $('.modal-footer > .btn.btn-success').attr('disabled','true');
                } else {
                   $('.modal-footer > .btn.btn-success').removeAttr('disabled'); 
                }
                
            })
        });

    </script> 
</body>
</html>

