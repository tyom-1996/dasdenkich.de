<?php
session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . "/bulk/view-functions/create-view-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/account-balance.php";


$thisPage     = $CreateMsgViewFunctions->this_page;
$prices       = $CreateMsgViewFunctions->getPrices();
$title        = $lang['title'][$l];

$u160         = $prices['u160'];
$u160markup   = $prices['u160markup'];
$u160exchange = $prices['u160exchange'];

$u320         = $prices['u320'];
$u320markup   = $prices['u320markup'];
$u320exchange = $prices['u320exchange'];

$u160_price   = ($u160 * $u160markup * $u160exchange) * 500;
$u320_price   = ($u320 * $u320markup * $u320exchange) * 500;

include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/banner.php";

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $lang['title'][$l]; ?></title>
    <?php  include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>
    <link rel="stylesheet" href="/css/bulk/add-message.css">
</head>

<style>
    .offer_to_user{
        display:none;
    }
</style>

<body id="<?php echo $thisPage; ?>">

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
                    <h1 class ='msg-filter-title'> Create New Message </h1>
               </div>
              
                <div id="create-new-user" class="wrapper wrapper--w900">
                    <div class="card card-6">
                        <div class="card-body">
                            <form method="POST" name="create_sub_user" enctype="multipart/form-data" action="/bulk/bulk-functions.php">
                                <input type="hidden" name="action" value="create_bulk_msg">
    
                                <div class="form-row" style="display: flex;">
                                    
                                    <div class="name" style="width: 100%;display: flex;justify-content: space-between;align-items: center;">
                                        <div>Message</div>  
                                        <div style="font-size: 15px;color: red;max-width: 721px;" class="offer_to_user">
                                            
                                            You are using 
                                            <input disabled class="characters-count" value="0" style="background: white;padding: 2px;width: 32px;text-align: center;font-weight: 600;">
                                            characters in your message (including store name and image link)
                                            if you reduce the amount of characters below 160 you are able to reduce the cost of this message by 50%
                                                
                                        </div> 
                                    </div>
                                    
                                    <div class="value">
                                        <div class="input-group">
                                            
                                            <div style="display: flex;justify-content: space-between;align-items: center;/* margin-bottom: 13px; */background: white;border-radius: 10px 10px 0 0;border: 1px solid silver;border-bottom: none;">
                                                
                                                <div class="include-str-name-for-msg" style="background: none;margin-bottom: 0;border: none;">
                                                    
                                                    <input id="store_name_field" name="store_name_field" type="checkbox"   value="<?=$_SESSION['user-name']?>">
                                                    <label style="margin-left: 13px;font-size: 14px;" for="store_name_field">
                                                        Include store name in the message
                                                    </label>
                                                    <input type="hidden" name="store_id" value="<?=$_SESSION['user-id']?>" >
                                                    
                                                </div>
                                                
                                                <div class="characters-count-wrap" style="padding: 0px 12px;"> 
                                                    <input disabled class="characters-count" value="0" style="background: white;padding: 2px;width: 45px;text-align: center;font-weight: 600;">
                                                        characters are used, the price is 
                                                    <input disabled class="characters-price" value="0.00000" style="background: white;padding: 2px;width: 88px;text-align: center;font-weight: 600;" > 
                                                    for a block of 500
                                                </div>
                                                
                                                <input type="hidden" id="price_160" name="u160" value="<?=$u160_price?>">
                                                <input type="hidden" id="price_320" name="u320" value="<?=$u320_price?>">
                                                
                                               
                                            </div>
                                            
                                            <textarea id="new-message" class="input--style-6"  name="message" maxlength="320" placeholder="Reply to message (limit 320 characters)"></textarea>
                                            <div class="message-action-block" >
                                                
                                                <div class="msg-panel-left">     
                                                    <label for="upload-msg" style="display: flex;align-items: center;margin-bottom: 0;">
                                                        <i class="fas fa-image open-msg-image-url"></i>
                                                        <span class="attach-text">Attach a picture</span>
                                                    </label>
                                                    <input type="file" style="display:none;" class="upload-msg-image" name="upload-msg-image" id="upload-msg">
                                                    <div class="upload-preview-block"> 
                                                        <img src="#" class="upload-preview" alt="your image"> 
                                                        <span>There is one attachment</span> 
                                                        <i class="fas fa-times remove-upload-image"></i>
                                                    </div>     
                                                </div>
                                                
                                                <button class="submit_btn" type="submit">Save Message</button>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                       
                    </div>
                </div>
                
                <!--form end-->
    
          </div>
            
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
    
    </div>
    
</div>

<script>
    
    
    $(document).ready(function(){
        
        var storename_checkbox = false;
        var messages_length    = $('#new-message').val().length;
        var old_value          = '';
        var upload_image       = $('.upload-msg-image').val().length > 0 ? true : false;
        console.log(upload_image);
        const PRICE_160        = $("#price_160").val()
        const PRICE_320        = $("#price_320").val()
        
        $('.characters-count').val(messages_length) 
       
        $(document).on('input','#new-message',function(e){
            
            var char_count;
            
            if(storename_checkbox && upload_image ) {
               char_count = $(this).val().length + $('#store_name_field').val().length + 10
            } else if( upload_image ){
               char_count = $(this).val().length + 10; 
            } else if(storename_checkbox){
               char_count = $(this).val().length + $('#store_name_field').val().length
            } else {
               char_count =  $(this).val().length;
            }
            
            $(".offer_to_user").hide()
            
            if(char_count >= 320){
                $('.characters-count').val('320')
            } else if(char_count <= 160) {
                $(".characters-price").val('$'+PRICE_160)
                $('.characters-count').val(char_count)
                
                $('.characters-count-wrap,.characters-count-wrap input').css({
                    'color':"green"
                })
                
            } else if(char_count > 160) {
                
                // if(char_count <= 170) {
                    $(".offer_to_user").show()
                    $('.remove-characters-count').val(char_count-160)
                // }
                
                $(".characters-price").val('$'+PRICE_320)
                $('.characters-count').val(char_count)
                
                $('.characters-count-wrap,.characters-count-wrap input').css({
                    'color':"red"
                })
                  
            } else {
                
                $('.characters-count').val(char_count)
                
            }
            
        })
        
        
        
        $(document).on('change','#store_name_field',function(e){
            
            var count_include_storename,count_not_include_storename,new_maxlength;
            
            if(this.checked) {
                
                storename_checkbox = true;
                
                if(upload_image) {
                    count_include_storename = $('#new-message').val().length + $(this).val().length + 10;
                    new_maxlength           = 320 - 10 - $(this).val().length; 
                    $("#new-message").attr("maxlength",new_maxlength);
                } else {
                    count_include_storename = $('#new-message').val().length + $(this).val().length; 
                    new_maxlength           = 320 - $(this).val().length ;
                    $("#new-message").attr("maxlength",new_maxlength);
                }
                
                
                if(count_include_storename > 320) {
                    alert('Please shorten the message length.')
                    $(this).prop('checked', false);
                    var max_lng   = Number($('#new-message').attr('maxlength'));   
                    new_maxlength = max_lng + $(this).val().length ;
                    $("#new-message").attr("maxlength",new_maxlength);
                    storename_checkbox = false;
                } else {
                    $('.characters-count').val(count_include_storename) 
                }
                
            } else {

                storename_checkbox = false;
                
                if(upload_image) {
                   
                    count_include_storename      = $('.characters-count').val() - $(this).val().length ;
                    $('.characters-count').val(count_include_storename);
                    
                    new_maxlength = 320 - 10
                    $("#new-message").attr("maxlength",new_maxlength)
                    
                } else {
                    count_not_include_storename = $('.characters-count').val() - $(this).val().length;
                    $('.characters-count').val(count_not_include_storename);
                    $("#new-message").attr("maxlength",320)
                }
               
                
               
            }
        })
        
        
        
        $('.submit_btn').click(function(){
            
            var confirm_err = 'Password and Confirm password not match';
            var submit      = true;
            
            $('#create-new-user .input--style-6').each(function(element){
                if($(this).val() == '') {
                   $(this).addClass('error_border'); 
                   submit = false;
                } 
            })
            
            if( $('#confirm-password').val() != $('#password').val() ) {
               $('#confirm-password').addClass('error_border'); 
               $('.error_message').text(confirm_err)
              
              return false; 
            }
           
            if(!submit) {
                return false
            } else {
                $('#create-new-user form').submit()
            }
            
        })
        
        
        $(document).on('input','#create-new-user .input--style-6',function(){
            $(this).removeClass('error_border');
        })
        
        
        
        $(document).on('change',".upload-msg-image",function() {
            
          var charactersCount = Number($('.characters-count').val()) + 10 ;
          
          if(charactersCount >= 320 ) {
              $(this).val('')
              alert('Please shorten the message length.');
              upload_image = false;
              return false
          }
          console.log(charactersCount, '>=', 320);
      
            
          var input = this;
          $('.attach-text').parent().hide()
          
          if (input.files && input.files[0]) {
            
            var reader    = new FileReader();
            reader.onload = function(e) {
                $(input).parent().find('.upload-preview-block').show()
                $(input).parent().find('.upload-preview').attr('src', e.target.result)
            }
            reader.readAsDataURL(input.files[0]);
            
            upload_image = true
            
            if(storename_checkbox && upload_image ) {
                char_count    = $("#new-message").val().length + $('#store_name_field').val().length + 10
                new_maxlength = (320 - 10) - $('#store_name_field').val().length ;
            } else if( upload_image ){
                char_count    = $("#new-message").val().length + 10; 
                new_maxlength = 320 - 10 ;
            } else if(storename_checkbox){
               char_count = $("#new-message").val().length + $('#store_name_field').val().length
            } else {
               char_count =  $("#new-message").val().length;
            }
            
            $("#new-message").attr("maxlength",new_maxlength);
            $('.characters-count').val(char_count)

          }
          
        })
       
        $(document).on('click',".remove-upload-image",function() {
            
            upload_image = false;
            
            if(storename_checkbox){
                char_count = $("#new-message").val().length + $('#store_name_field').val().length;
                new_maxlength = 320  - $('#store_name_field').val().length ;
            } else {
               char_count =  $("#new-message").val().length;
               new_maxlength = 320  
            }
            
            $("#new-message").attr("maxlength",new_maxlength);
            $('.characters-count').val(char_count)
            
            
            $('.attach-text').parent().show()
            $(this).closest('.msg-panel-left').find('.upload-msg-image').val('')
            $(this).parent().hide()
        })
       
        
    })
                                                
    
</script>


</body>
</html>

