<?php
session_start();
var_dump($_SESSION);
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";

//echo "<pre>";var_dump($_SESSION);die;


// if(empty($_SESSION['auth']) || $_SESSION['auth'] != $authcode) {
// 	header("location:/anmelden.php?msg=notauth");
// 	exit();
// }




$thisPage = "add-user";
$title = $lang['title'][$l];
?><!DOCTYPE html>
<html lang="de">
<head>
    <title><?php echo $lang['title'][$l]; ?></title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>
    <style>  
    
        h1.storeDetail{text-align:left;}
        
        .sdId{float:right; font-size:0.5em; line-height:3;}
        .sdName{ font-size:1.2em; display:block; }
        .sdNumber{font-size:1.2em; }
        #members-nav li{
            margin:0 0.5em;
        }

        #wrap{
            /*margin-top:2em;*/
            background:white;
           padding: 85px 0;
        }
        
        #message-detail>div>h2{
            margin-top:0px !important;
        }
    
        li.showActive{font-weight:bold;} 
        #message-list li img:hover{cursor:pointer;}
        #message-list li img{margin-left:-16px;}
        #message-list .container{display:none;}
        #message-list li{list-style:none;}
        
        .toolTip{
            background-color:lightblue;
            padding:.5em;
            border-radius:4px;
            margin-left:2em;
            transition: .5s ease;
            font-size:0.7em;
            display: block;
            width: 70%;
        }

        .toolTip div i{margin-right:1em;}
        
        #detail-createMsg form.name-input input{
            width:100%;
            margin:5px 0px;
            padding: 5px;
        }
        
        @media screen and (max-width: 768px){
            header h1 span{
                display:block;
                margin:0px;
                float:none !important;
            }
            span.sdNumber{margin-left:0px;}
            #main .wrap {
                max-width:100% !important;
            }
            #main .wrap h1{
                padding:0 5%;
            }
            
            
            #members-nav{
                margin-right:2em;
            }
                    
            #detail-createMsg{margin-left:2em;}
            div.columns{margin-top:1em;}       
            
            #message-list{
                width:100%;
                margin-right: 0px;    
                background-color: #efefef; 
                border-top:1px solid #0f293c;
                border-bottom:1px solid #0f293c;
                margin-bottom:1em;
            }

          #message-list .container{ 
              text-align:center; 
              margin:.5em;
              font-weight:bold;
              letter-spacing:1px;
              font-size:1.3em;
              cursor: pointer;
              display:block;
          }
          
          #contactList{
              display:none;
              padding-left:0px;
              margin:0px;
          }

          #contactList li{
              padding:.5em;
              margin-bottom:0px !important;
              border-top:1px solid #0f293c;
          }
          .contactHidden{display:block !important;}
          #message-detail{width:100%;padding: .5em;}
          
          
        }
        
        
        *{
            padding:0;
            margin:0;
        }
        
        
        
        #create-new-user .card-6 .card-body {
            background: #fff;
            position: relative;
            border: 1px solid #e5e5e5;
            border-bottom: none;
            padding: 30px 0;
            padding-bottom: 0;
            -webkit-border-top-left-radius: 3px;
            -moz-border-radius-topleft: 3px;
            border-top-left-radius: 3px;
            -webkit-border-top-right-radius: 3px;
            -moz-border-radius-topright: 3px;
            border-top-right-radius: 3px;
        }
        
        #create-new-user .card-6 .card-body:before {
            bottom: 100%;
            left: 75px;
            border: solid transparent;
            content: "";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
            border-color: transparent;
            border-bottom-color: #fff;
            border-width: 10px;
        }
                
                
        #create-new-user .form-row {
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-flex-wrap: wrap;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -webkit-box-align: start;
            -webkit-align-items: flex-start;
            -moz-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
            padding: 24px 55px;
            border-bottom: 1px solid #e5e5e5;
        }        
        
        
       #create-new-user .form-row .name {
            width: 188px;
            color: #333;
            font-size: 15px;
            font-weight: 700;
            margin-top: 11px;
        }
        
        
        #create-new-user .form-row .value {
            width: -webkit-calc(100% - 188px);
            width: -moz-calc(100% - 188px);
            width: calc(100% - 188px);
        }
        
        
        #create-new-user .input--style-6 {
            background: 0 0;
            line-height: 38px;
            border: 1px solid #ccc;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            color: #666;
            font-size: 15px;
            -webkit-transition: all .4s ease;
            -o-transition: all .4s ease;
            -moz-transition: all .4s ease;
            transition: all .4s ease;
            padding: 0 20px;
            width:100%;
        }
        
        
        
       #create-new-user .card-6 .card-footer {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-top: none;
            -webkit-border-bottom-left-radius: 3px;
            -moz-border-radius-bottomleft: 3px;
            border-bottom-left-radius: 3px;
            -webkit-border-bottom-right-radius: 3px;
            -moz-border-radius-bottomright: 3px;
            border-bottom-right-radius: 3px;
            padding: 50px 55px;
        }
        
        
         #create-new-user .submit_btn {
            display: inline-block;
            line-height: 50px;
            padding: 0 30px;
            -webkit-transition: all .4s ease;
            -o-transition: all .4s ease;
            -moz-transition: all .4s ease;
            transition: all .4s ease;
            cursor: pointer;
            font-size: 15px;
            text-transform: capitalize;
            font-weight: 700;
            color: #fff;
            background: #0f293c;
            font-family: inherit;
        }
        
        .body_title{
            position: absolute;
            top: -16px;
            background: white;
            left: 33px;
            padding: 0 20px;
            font-weight: 600;
            font-size: 32px;
        }
        
        
    </style>  
</head>

<body id="<?php echo $thisPage; ?>">
	<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/banner.php"; ?>


    <div id=wrap>
      
      <div class="wrap">
           
         
            <!--from-->
            
            <div id="create-new-user" class="wrapper wrapper--w900">
                <div class="card card-6">
                    
                    <div class="card-body">
                        
                        <span class="body_title">Create New User</span>
                        
                        <form method="POST" name="create_sub_user" action="/add-user/create-new-user.php">
                            <input type="hidden" name="action" value="create_sub_user">
                            <div class="form-row">
                                <div class="name">Name</div>
                                <div class="value">
                                    <input class="input--style-6" type="text" name="name" placeholder="name">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="name">Username</div>
                                <div class="value">
                                    <div class="input-group">
                                        <input class="input--style-6" type="username" name="username" placeholder="username">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="name">Password</div>
                                <div class="value">
                                    <div class="input-group">
                                        <input class="input--style-6" type="password" name="password" placeholder="password">
                                    </div>
                                </div>
                            </div>
            
                           
                        </form>
                    </div>
                    <div class="card-footer">
                        <button class="submit_btn" type="submit">Create User</button>
                    </div>
                </div>
            </div>
            
            <!--form end-->
            
            
            
            
            
            
            
            

      </div>
        
    </div> 


<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
<script>
	$(document).ready(function(){
        
        $('.submit_btn').click(function(){
            $('#create-new-user form').submit()
        })

	});

  
	
</script>
</body>
</html>

