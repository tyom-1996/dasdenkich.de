
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
    
    .welcome-message-block{
        
        display:none!important;
        
        position: absolute;
        width: 274px;
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
        /* border-right: 1px solid #000; */
        /* border-bottom: 1px solid #000; */
        background-color: #fff;
        transform: rotate(45deg);
        bottom: -8px;
        /* border-radius: 4px; */
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
        box-shadow: 0 0 20px 0px #0000004d;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-wrap: wrap;
        cursor: auto;
    }
    
    .message-modal-header{
        width: 100%;
        height: 50px;
        background: #4c76e0;
        display: flex;
        align-items: center;
        padding: 0 20px;
        justify-content: center;
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
    }
    
    .section-1{
        max-width: 205px;
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
    }
    
    .section-2{
        margin-top: 20px;
        width: 100%;
        display: flex;
        justify-content: flex-end;
    }
    
    .section-2 form{
        width: 100%;
        max-width: 205px;
        min-height: 60px;
        background: #ffffff;
        border-radius: 20px 20px 0 20px;
        padding: 14px;
    }
    
    .section-2 form .msg-form-input{
        padding-bottom: 10px;
        font-size: 11px;
        border-bottom: 1px solid #e5ecfc;
        width: 100%;
        margin-bottom: 15px;
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
        font-size: 9px;
        color: red;
        
    }
    
    .section-4{
        display: flex;
        justify-content: center;
    }
    
    .msg-form-submit{
        background: #b1c1ed;
        border-radius: 4px;
        padding: 5px 26px;
        color: white;
        cursor:pointer;
    }
    
    .msg-form-submit:hover{

        background:#4c76e0;
    
    }    
    
    </style>

<script>
    $(document).on('click','.open-msg-modal',function(){
        
    })
    
    $(document).on('click','.msg-form-submit',function(){
        $('#msg-form').submit()
        console.log('test')
    })
    
    
</script>

<div class="open-msg-modal">
    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="comment-dots" class="svg-inline--fa fa-comment-dots fa-w-16" role="img" viewBox="0 0 512 512"><path fill="currentColor" d="M256 32C114.6 32 0 125.1 0 240c0 49.6 21.4 95 57 130.7C44.5 421.1 2.7 466 2.2 466.5c-2.2 2.3-2.8 5.7-1.5 8.7S4.8 480 8 480c66.3 0 116-31.8 140.6-51.4 32.7 12.3 69 19.4 107.4 19.4 141.4 0 256-93.1 256-208S397.4 32 256 32zM128 272c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm128 0c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm128 0c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z"/></svg>
    
    <div class="welcome-message-block">   
        <div class="msg-bl-left"><img src='/images/msg-logo.png'> </div>
        <div class="msg-bl-right">
            Do you have any questions? You may text us here!
        </div>
    </div>
    
    <div class="message-modal">
        <div class="message-modal-header">
            <img src="/images/msg-logo.png">
            <span class="modal-header-title" > We'll text you.</span>
        </div>
        <div class="message-modal-body">
            <section class="section-1">
                 <span>Enter your information, and our team will text you shortly.</span>
            </section>
            
            <section class="section-2">
               <form id="msg-form" action="/" type="post">
                   <input type="hidden" name="" value="">
                   <input type="text" name="name" class="fr-name msg-form-input" placeholder="Name">
                   <input type="text" name="number" class="fr-number msg-form-input" placeholder="Mobile Number">
                   <input type="text" name="message" class="fr-message msg-form-input" placeholder="Message">
               </form>
            </section>
            
            <section class="section-3">
                <p>
                    By submitting you agree to receive text messages at the number provided. Message/data rates apply.
                </p>
            </section>
            
            <section class="section-4">
                <button class="msg-form-submit" >Send</button> 
            </section>
        </div>
    </div>
</div>







