<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<style>
    .open-msg-modal{
       width: 50px;
        height: 50px;
        background: #4c76e0;
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999999;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor:pointer;
    }  
    
    .open-msg-modal svg{
        width: 21px;
    }
    
    .open-msg-modal svg path{
        fill:white;
    }
    
    
    /*.open-msg-modal:hover .welcome-message-block{*/
    /*    display:flex!important;*/
    /*}*/
    
    .welcome-message-block{
        
        /*display:none!important;*/
        
        position: absolute;
        width: 269px;
        height: 91px;
        background: white;
        border-radius: 11px;
        /* left: -200px; */
        top: -110px;
        right: -8px;
        padding: 10px 16px;
        box-sizing: border-box;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        align-items: center;
        box-shadow: 0 0 3px black;
    }
    
    .welcome-message-block:before {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        display: block;
        right: 25px;
        border-right: 1px solid #0000009e;
        border-bottom: 1px solid #0000009e;
        background-color: #fff;
        transform: rotate(45deg);
        bottom: -10px;
    }
    
    .msg-bl-left{
        flex: 1;
    }
    
    .msg-bl-left img{
        width: 48px;
    }
    
    .msg-bl-right{
        flex: 3;
    }
    
    .message-modal{
       
        width: 258px;
        position: absolute;
        right: 0;
        bottom: 0;
        box-shadow: 0 0 3px 0px #0000004d;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-wrap: wrap;
        cursor: auto;
        box-sizing: border-box;
        display:none;
    }
    
    .message-modal-header{
        width: 100%;
        height: 50px;
        background: #4c76e0;
        display: flex;
        align-items: center;
        padding: 0 20px;
        justify-content: center;
        box-sizing: border-box;
    }
    
    .message-modal-header svg{
        width: 16px;
        position: absolute;
        top: 6px;
        right: 6px;
        cursor: pointer;
    }
    
    .message-modal-header img{
        width: 38px;
        position: relative;
        left: -18px;
    }
    
    .modal-header-title{
        color: white;
    }    
    
    .message-modal-body{
        background: #f5f5f7;
        width: 100%;
        height: auto;
        min-height: 342px;
        padding: 15px;
        box-sizing: border-box;
        padding-bottom: 18px;
    }
    
    .section-1{
        max-width: 177px;
        min-height: 60px;
        background: #e4e9f0;
        border-radius: 20px 20px 20px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
    }
    
    .section-1 > span{
        font-size: 13px;
        font-family: sans-serif;
    }
    
    .section-2{
        margin-top: 20px;
        width: 100%;
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
    }
    
    .section-2 form{
        width: 100%;
        max-width: 177px;
        min-height: 60px;
        background: #ffffff;
        border-radius: 20px 20px 0 20px;
        padding: 14px;
        margin-bottom: 0;
    }
    
    .section-2 form .msg-form-input{
        font-size: 11px;
        border: none;
        border-bottom: 1px solid #e5ecfc;
        width: 100%;
        margin-bottom: 15px;
        padding-left: 17px;
        padding: 5px 17px;
    }
    
    .section-2 form .msg-form-input:active, .section-2 form .msg-form-input:focus{
        outline:none;
    }
     
    .section-2 form .msg-form-input::placeholder{
        color:#535a5d;
    }
     
    
    .section-3{
        margin-top: 22px;
    }
    
    .section-3 > p{
        text-align: center;
        font-size: 10px;
        color: #a1a1a5;
    }
    
    .section-4{
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 21px;
    }
    
    .section-4 > span{
        position: absolute;
        font-size: 9px;
        bottom: 14px;
        color: #38383c;
        font-weight: 600;
    }
    
    .msg-form-submit{
        background: #b1c1ed;
        border-radius: 4px;
        padding: 5px 26px;
        color: white;
        cursor:pointer;
        border: none;
    }
    
    .msg-form-submit:hover{

        background:#4c76e0;
    
    }    
    
     .error_border::placeholder{
         color:red!important;
     }
    .error_border{
        border-bottom:1px solid red!important;
    }
    
    
    .msg-form-submit:active,.msg-form-submit:focus{
        outline:none;
    }
    
     
   .lds-dual-ring:active,.lds-dual-ring:focus{
        outline:none;
    }
    
    .msg-send{
        position: absolute;
        left: 0;
        padding: 20px 0;
        background: green;
        width: 100%;
        text-align: center;
        color: white;
        top: 50px;
        display:none;
    }
    
    
    /*LOADER*/
    
    
    .lds-dual-ring {
        width: 21px;
        height: 16px;
        position: relative;
        top: -2px;
    }
    .lds-dual-ring:after {
        content: " ";
        display: block;
        width: 17px;
        height: 17px;
        /* margin: 1px; */
        border-radius: 50%;
        border: 2px solid #fff;
        border-color: #fff transparent #fff transparent;
        animation: lds-dual-ring 1.2s linear infinite;
    }
    @keyframes lds-dual-ring {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }


    .input-block{
        position:relative;
    }
    
    </style>



<div class="open-msg-modal">
    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="comment-dots" class="svg-inline--fa fa-comment-dots fa-w-16" role="img" viewBox="0 0 512 512"><path fill="currentColor" d="M256 32C114.6 32 0 125.1 0 240c0 49.6 21.4 95 57 130.7C44.5 421.1 2.7 466 2.2 466.5c-2.2 2.3-2.8 5.7-1.5 8.7S4.8 480 8 480c66.3 0 116-31.8 140.6-51.4 32.7 12.3 69 19.4 107.4 19.4 141.4 0 256-93.1 256-208S397.4 32 256 32zM128 272c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm128 0c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm128 0c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z"/></svg>
    
    <div class="welcome-message-block">
        <div class="msg-bl-left"><img src='/images/msg-logo.png'> </div>
        <div class="msg-bl-right">
            Do you have any questions? You may text us here!
        </div>
    </div>
    
    <div class="message-modal" style="display:<?=isset($_SESSION['widg-msg-send']) ? 'flex' : 'none'?>;">
        <div class="message-modal-header">
            <img src="/images/msg-logo.png">
            <span class="modal-header-title" > Text us to leave a message</span>
            <svg class="close-msg-modal" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 174.239 174.239" style="enable-background:new 0 0 174.239 174.239;" xml:space="preserve" nighteye="disabled"><g><path d="M87.12,0C39.082,0,0,39.082,0,87.12s39.082,87.12,87.12,87.12s87.12-39.082,87.12-87.12S135.157,0,87.12,0z M87.12,159.305 c-39.802,0-72.185-32.383-72.185-72.185S47.318,14.935,87.12,14.935s72.185,32.383,72.185,72.185S126.921,159.305,87.12,159.305z"/><path d="M120.83,53.414c-2.917-2.917-7.647-2.917-10.559,0L87.12,76.568L63.969,53.414c-2.917-2.917-7.642-2.917-10.559,0 s-2.917,7.642,0,10.559l23.151,23.153L53.409,110.28c-2.917,2.917-2.917,7.642,0,10.559c1.458,1.458,3.369,2.188,5.28,2.188 c1.911,0,3.824-0.729,5.28-2.188L87.12,97.686l23.151,23.153c1.458,1.458,3.369,2.188,5.28,2.188c1.911,0,3.821-0.729,5.28-2.188 c2.917-2.917,2.917-7.642,0-10.559L97.679,87.127l23.151-23.153C123.747,61.057,123.747,56.331,120.83,53.414z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
        </div>
        <div class="message-modal-body">
            <section class="section-1">
                 <span>Enter your information, and our team will text you shortly.</span>
            </section>
            
            <section class="section-2">
                
                <span class="msg-send">The message was sent</span>
               <form id="msg-form" action="https://<?=$_SERVER['HTTP_HOST']?>/msg-widget/widget-functions.php" method="post">
                   <input type="hidden" name="id" value="<?=$_GET['id']?>">
                   <input type="hidden" name="type" value="<?=$_GET['type']?>">
                   <div class="input-block">
                        <input type="text" name="name" class="fr-name msg-form-input" placeholder="Name">
                   </div>
                   <div class="input-block">
                       <div style="position: absolute;left: 3px;top: 3px;font-size: 11px;font-family: cursive;">+1</div>
                       <input type="number" name="number" class="fr-number msg-form-input" placeholder="Mobile Number">
                   </div>
                   <div class="input-block">
                       <input type="text" name="message" class="fr-message msg-form-input" placeholder="Message">
                   </div>
                  
                   
                   
               </form>
            </section>
            
            <section class="section-3">
                <p>
                    By submitting you agree to receive text messages at the number provided. Message/data rates apply.
                </p>
            </section>
            
            <section class="section-4">
                <button class="msg-form-submit" >Send</button> 
                <span>powered by www.frogswing.com</span>
            </section>
        </div>
    </div>
</div>

<script>


    $(document).on('click','.open-msg-modal',function(){
        $('.message-modal').show()
        $('.welcome-message-block').hide()
    })
    
    $(document).on('click','.close-msg-modal',function(){
        $('.message-modal').fadeOut()
        
        setTimeout(function(){
           $('.welcome-message-block').show()
        },1000)
        
        
    })
    
   
    
    
    $(document).on('click','.msg-form-submit',function(){
            
        var submit = true;
        
        $('.msg-form-input').each(function(element){
            if($(this).val() == '') {
               $(this).addClass('error_border'); 
               submit = false;
            } 
        })
        
        if(!submit) {
            return false
        } else {
            
            $(this).html('<div class="lds-dual-ring"></div>');
            
            setTimeout(function(){
                 $('.msg-send').slideDown()
                //  $('.msg-form-input').val('')
            },1500)
            
            setTimeout(function(){
                 $('#msg-form').submit()
            },2500)
            
        }
        
    })
    
    
    $(document).on('input','.msg-form-input',function(){
         $(this).removeClass('error_border')
    })
            
    document.querySelector(".fr-number").addEventListener("keypress", function (evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57)
        {
            evt.preventDefault();
        }
    });
    
</script>





