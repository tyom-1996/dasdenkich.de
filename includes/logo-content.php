<?php

$is_subuser = isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? true : false;
    
function getLogo()
{   
    global $mysqli,$is_subuser;
    
    $store_id   = $_SESSION['user-id'];
    $subuser_id = isset($_SESSION['sub-user-id']) ? $_SESSION['sub-user-id'] : 0;
    $logo_query = $is_subuser  ? "SELECT * FROM messages_subusers  WHERE store_id = $store_id AND id = $subuser_id" : "SELECT * FROM messages_setup WHERE store_id = $store_id ";
  
    $msg_setup  = $mysqli->query($logo_query)->fetch_assoc();
    $content    = '';
    
    if(!empty($msg_setup)) {
        $name    = $msg_setup['logo'];
        $content =  "<img src='/logos/$name'>" ; 
    } else {
        $content = "<img src='/images/user.png'>";
    }
    
    return $content;
}


?>


<style>
    
/*UPLOAD FORM STYLE*/

.logo_wrap{
      overflow: hidden;
      width: 61px;
      height: 61px;
      cursor:pointer;
      border-radius: 50%;
  }

  .logo_wrap form{
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      top: -36px;
      background: #000000b3;
       display: none; 
      transition: 0.5s;
      height: 39px;
  }

 .logo_wrap form label{
      width: 17px;
      position: relative;
      top: 3px;
 }

 .logo_wrap form label svg {
    cursor:pointer;
 }
 .logo_wrap form label svg path{
    cursor:pointer;
    fill: #e8ecf1;
 }

 .logo_wrap form #fileToUpload{
    display: none;
 }
.submit-upload-form{
  display: none;
}
  
.logo_wrap:hover > form{
    display:flex;
   
}
.profile-info-left img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

</style>

<div class="logo_wrap" >
      <?php echo getLogo();?>
      <form id="image-upload-form" action="/members/messages-functions.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="action" value="upload_image">
          <label for="fileToUpload"> <?php echo $UPLOAD_PHOTO?> </label>
          <input type="file" name="fileToUpload" id="fileToUpload">
          <!--<input class="submit-upload-form" type="submit" value="Upload Image" name="submit" >-->
      </form>
</div>
<script>
    $(document).on('change','#fileToUpload',function(){
        $('#image-upload-form').submit()
    })
</script> 