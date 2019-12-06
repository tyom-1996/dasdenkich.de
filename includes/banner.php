<?php
// session_start();

include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/constants.php";
 

$headerContent = '<div id="logo">'."\n"
	. '<div>'."\n"
	. '	<img src="/images/daskennich-logo.png">'."\n"
	. '</div>'."\n"
	. '<div>'."\n"
	. '	<h1>'.$lang['domain'][$l].'</h1>'."\n"
	. '	<h2>'.$lang['tagline'][$l].'</h2>'."\n"
	. '</div>'."\n"
	. '</div>'."\n"
	. '<nav>'."\n"
	. '	<ul>'."\n"
	. '		<li><a href="https://'.$lang['domain'][$l].'">home</a></li>'."\n"
	. '		<li><a href="https://'.$lang['domain'][$l].'/anmelden.php">'.$lang['menu2'][$l].'</a></li>'."\n"
	. '		<li><a href="https://'.$lang['domain'][$l].'/#mitmachen">'.$lang['menu3'][$l].'</a></li>'."\n"
	. '	</ul>'."\n"
	. '</nav>';

if($thisPage=="inner") {
	$header = '<div class="wrap">'."\n"
			. $headerContent
			.'</div>'."\n"
			. '<picture>'."\n"
			. '	<source media="(min-width: 712px)" srcset="/images/header-back.jpg">'."\n"
			. '	<source media="(min-width: 474px)" srcset="/images/header-back3.jpg">'."\n"
			. '	<img src="/images/header-back3.jpg" alt="color gemstone jewelry">'."\n"
			. '</picture>';

} elseif($thisPage=="home") {
	$header = $headerContent;
} elseif($thisPage=="store" || $thisPage=="members") {
	$header = '<h1>'.$title.'</h1>';
}

$secondNav = '';

if($thisPage == "members"){
    
    if( isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ) {
	    
	    $add_user = '';
	    $setting = '<li><a href="/setting">'.$SETTING.'Setting</a></li>';
	    $user_name = '<span class="header-user-name">'.$USER_ICON.$_SESSION['sub-user-name'].'</span>' ;
	    
	} elseif( !isset($_SESSION['user-status']) ) {
	    
	    $add_user = '<li><a href="/add-user">'.$ADDUSER.'Add user</a></li>';
        $setting = '<li><a href="/admin-settings">'.$SETTING.'Settings</a></li>';
        $user_name = '<span class="header-user-name">'.$USER_ICON.'Admin</span>';
        
        $store_id = $_SESSION['user-id'];
        $bulk = $mysqli->query("SELECT * FROM messages_bulkaccount WHERE store_id = $store_id AND enabled = 1");
        
        if($bulk->num_rows > 0) {
            $bulk_content = '<li><a href="/bulk">'.$BULKTEXT.'Bulk Messaging</a></li>';
        } else {
            $bulk_content = '';
        }
       
       
	}
    
    $mynumber = isset($_SESSION['mynumber']) ? $_SESSION['mynumber'] : '';
	$header = '<h1 class="storeDetail">'
    .'<span class="sdName">'.$_SESSION['user-name'].'</span>'
    .'<span class="sdNumber">'.$mynumber.'</span>'
    .$user_name
    .'<span class="sdId">'.$_SESSION['user-id'].'</span>'
    .'</h1>';


  
	$secondNav = "
    	<nav id='members-nav'>
    	    <ul>
            	<li><a href='/members/' class='active-page'>".$MESSAGE."Messages</a></li>
            	".$add_user."
                <li><a href='/users'>".$USER."Users</a></li>
            	".$setting."
            	".$bulk_content."
                <li><a href='/includes/script-logout.php'>".$LOGOUT."Logout</a></li>
            </ul>
        </nav>	
	";
    
}


if($thisPage == 'add-user') {
    
    if( isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ) {
       $user_name = '<span class="header-user-name">'.$USER_ICON.$_SESSION['sub-user-name'].'</span>' ;
	   $add_user = '';
	} elseif ( !isset($_SESSION['user-status']) ) {
	   $user_name = '<span class="header-user-name">'.$USER_ICON.'Admin</span>';
	   $add_user = '<li><a class="active-page" href="/add-user">'.$ADDUSER.'Add user</a></li>';
	   $setting = '<li><a href="/admin-settings">'.$SETTING.'Settings</a></li>';
	   
	    $store_id = $_SESSION['user-id'];
        $bulk = $mysqli->query("SELECT * FROM messages_bulkaccount WHERE store_id = $store_id AND enabled = 1");
        
        if($bulk->num_rows > 0) {
            $bulk_content = '<li><a href="/bulk">'.$BULKTEXT.'Bulk Messaging</a></li>';
        } else {
            $bulk_content = '';
        }

	   
	}
   
    $mynumber = isset($_SESSION['mynumber']) ? $_SESSION['mynumber'] : '';
	$header = '<h1 class="storeDetail">'
    .'<span class="sdName">'.$_SESSION['user-name'].'</span>'
    .'<span class="sdNumber">'.$mynumber.'</span>'
     .$user_name
    .'<span class="sdId">'.$_SESSION['user-id'].'</span>'
    .'</h1>';
    
	$secondNav = "<nav id='members-nav' class='add-user-nav'>
	    <ul>
        	<li><a href='/members/'>".$MESSAGE."Messages</a></li>
        	$add_user
            <li><a href='/users'>".$USER."Users</a></li>
            $setting
            $bulk_content
            <li><a href='/includes/script-logout.php'>".$LOGOUT."Logout</a></li>
        </ul>
    </nav>	
	";
}



if($thisPage == 'users') {
   
    if( isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ) {
       $user_name = '<span class="header-user-name">'.$USER_ICON.$_SESSION['sub-user-name'].'</span>' ;
	   $add_user = '';
       $setting = '<li><a href="/setting">'.$SETTING.'Settings</a></li>';
	} elseif( !isset($_SESSION['user-status']) ) {
	    $user_name = '<span class="header-user-name">'.$USER_ICON.'Admin</span>';
	    $add_user = '<li><a href="/add-user">'.$ADDUSER.'Add user</a></li>';
        $setting = '<li><a href="/admin-settings">'.$SETTING.'Settings</a></li>';
        
        
        $store_id = $_SESSION['user-id'];
        $bulk = $mysqli->query("SELECT * FROM messages_bulkaccount WHERE store_id = $store_id AND enabled = 1");
        
        if($bulk->num_rows > 0) {
            $bulk_content = '<li><a href="/bulk">'.$BULKTEXT.'Bulk Messaging</a></li>';
        } else {
            $bulk_content = '';
        }
  
        
	}
   
    $mynumber = isset($_SESSION['mynumber']) ? $_SESSION['mynumber'] : '';
	$header = '<h1 class="storeDetail">'
    .'<span class="sdName">'.$_SESSION['user-name'].'</span>'
    .'<span class="sdNumber">'.$mynumber.'</span>'
    .$user_name
    .'<span class="sdId">'.$_SESSION['user-id'].'</span>'
    .'</h1>';
            
     
	$secondNav = "<nav id='members-nav'  class='users-nav'>
	    <ul>
        	<li><a href='/members/'>".$MESSAGE."Messages</a></li>
        	$add_user
            <li><a class='active-page' href='/users'>".$USER."Users</a></li>
        	".$setting ."
        	".$bulk_content."
            <li><a href='/includes/script-logout.php'>".$LOGOUT."Logout</a></li>
        </ul>
    </nav>	
	";
    
}




if($thisPage == 'edit-user') {
    
    if( isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ) {
        $user_name = '<span class="header-user-name">'.$USER_ICON.$_SESSION['sub-user-name'].'</span>' ;
    } else {
        $user_name = '<span class="header-user-name">'.$USER_ICON.'Admin</span>';
        $setting = '<li><a href="/admin-settings">'.$SETTING.'Settings</a></li>';
        
        $store_id = $_SESSION['user-id'];
        $bulk = $mysqli->query("SELECT * FROM messages_bulkaccount WHERE store_id = $store_id AND enabled = 1");
        
        if($bulk->num_rows > 0) {
            $bulk_content = '<li><a href="/bulk">'.$BULKTEXT.'Bulk Messaging</a></li>';
        } else {
            $bulk_content = '';
        }
	   
        
    }
    
    $mynumber = isset($_SESSION['mynumber']) ? $_SESSION['mynumber'] : '';
	$header = '<h1 class="storeDetail">'
    .'<span class="sdName">'.$_SESSION['user-name'].'</span>'
    .'<span class="sdNumber">'.$mynumber.'</span>'
    .$user_name
    .'<span class="sdId">'.$_SESSION['user-id'].'</span>'
    .'</h1>';

	$secondNav = '<nav id="members-nav" class="edit-user-nav">'
	.'<ul>'
	.'<li><a href="/members/">'.$MESSAGE.'Messages</a></li>'
	.'<li><a href="/add-user">'.$ADDUSER.'Add user</a></li>'
	.'<li><a href="/users">'.$USER.'Users</a></li>'
	.$setting
	.$bulk_content
	.'<li><a href="/includes/script-logout.php">'.$LOGOUT.'Logout</a></li>'
	.'</ul>'
	.'</nav>';
}


if($thisPage == 'setting') {
    
    if( isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ) {
        $user_name = '<span class="header-user-name">'.$USER_ICON.$_SESSION['sub-user-name'].'</span>' ;
    } else {
        $user_name = '<span class="header-user-name">'.$USER_ICON.'Admin</span>';
        
        $store_id = $_SESSION['user-id'];
        $bulk = $mysqli->query("SELECT * FROM messages_bulkaccount WHERE store_id = $store_id AND enabled = 1");
        
        if($bulk->num_rows > 0) {
            $bulk_content = '<li><a href="/bulk">'.$BULKTEXT.'Bulk Messaging</a></li>';
        } else {
            $bulk_content = '';
        }

        
    }
    
    $mynumber = isset($_SESSION['mynumber']) ? $_SESSION['mynumber'] : '';
    $header = '<h1 class="storeDetail">'
    .'<span class="sdName">'.$_SESSION['user-name'].'</span>'
    .'<span class="sdNumber">'.$mynumber.'</span>'
    .$user_name
    .'<span class="sdId">'.$_SESSION['user-id'].'</span>'
    .'</h1>';


    $secondNav = '<nav id="members-nav"  class="setting-nav">'
        .'<ul>'
        .'<li><a href="/members/">'.$MESSAGE.'Messages</a></li>'
        .'<li><a href="/users">'.$USER.'Users</a></li>'
        .'<li><a href="/setting" class="active-page">'.$SETTING.'Setting</a></li>'
        .$bulk_content
        .'<li><a href="/includes/script-logout.php">'.$LOGOUT.'Logout</a></li>'
        .'</ul>'
        .'</nav>';
}










if($thisPage == 'admin-settings') {
    
   if( isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ) {
        $user_name = '<span class="header-user-name">'.$USER_ICON.$_SESSION['sub-user-name'].'</span>' ;
    } else {
        $user_name = '<span class="header-user-name">'.$USER_ICON.'Admin</span>';
        $setting = '<li><a class="active-page" href="/admin-settings">'.$SETTING.'Settings</a></li>';
        
        $store_id = $_SESSION['user-id'];
        $bulk = $mysqli->query("SELECT * FROM messages_bulkaccount WHERE store_id = $store_id AND enabled = 1");
        
        if($bulk->num_rows > 0) {
            $bulk_content = '<li><a href="/bulk">'.$BULKTEXT.'Bulk Messaging</a></li>';
        } else {
            $bulk_content = '';
        }

    }
    
    $mynumber = isset($_SESSION['mynumber']) ? $_SESSION['mynumber'] : '';
	$header = '<h1 class="storeDetail">'
    .'<span class="sdName">'.$_SESSION['user-name'].'</span>'
    .'<span class="sdNumber">'.$mynumber.'</span>'
    .$user_name
    .'<span class="sdId">'.$_SESSION['user-id'].'</span>'
    .'</h1>';

	$secondNav = '<nav id="members-nav" class="admin-settings-nav">'
	.'<ul>'
	.'<li><a href="/members/">'.$MESSAGE.'Messages</a></li>'
	.'<li><a href="/add-user">'.$ADDUSER.'Add user</a></li>'
	.'<li><a href="/users">'.$USER.'Users</a></li>'
	.$setting
	.$bulk_content
	.'<li><a href="/includes/script-logout.php">'.$LOGOUT.'Logout</a></li>'
	.'</ul>'
	.'</nav>';
}





if($thisPage == 'bulk-messaging') {
   
    if( isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ) {
       $user_name = '<span class="header-user-name">'.$USER_ICON.$_SESSION['sub-user-name'].'</span>' ;
	   $add_user = '';
       $setting = '<li><a href="/setting">'.$SETTING.'Settings</a></li>';
	} elseif( !isset($_SESSION['user-status']) ) {
	    $user_name = '<span class="header-user-name">'.$USER_ICON.'Admin</span>';
	    $add_user = '<li><a href="/add-user">'.$ADDUSER.'Add user</a></li>';
        $setting = '<li><a href="/admin-settings">'.$SETTING.'Settings</a></li>';
        
        
        $store_id = $_SESSION['user-id'];
        $bulk = $mysqli->query("SELECT * FROM messages_bulkaccount WHERE store_id = $store_id AND enabled = 1");
        
        if($bulk->num_rows > 0) {
            $bulk_content = '<li><a class="active-page" href="/bulk">'.$BULKTEXT.'Bulk Messaging</a></li>';
        } else {
            $bulk_content = '';
        }
  
        
	}
   
    $mynumber = isset($_SESSION['mynumber']) ? $_SESSION['mynumber'] : '';
	$header = '<h1 class="storeDetail">'
    .'<span class="sdName">'.$_SESSION['user-name'].'</span>'
    .'<span class="sdNumber">'.$mynumber.'</span>'
    .$user_name
    .'<span class="sdId">'.$_SESSION['user-id'].'</span>'
    .'</h1>';
            
     
	$secondNav = "<nav id='members-nav'  class='users-nav'>
	    <ul>
        	<li><a href='/members/'>".$MESSAGE."Messages</a></li>
        	$add_user
            <li><a  href='/users'>".$USER."Users</a></li>
        	".$setting ."
        	".$bulk_content."
            <li><a href='/includes/script-logout.php'>".$LOGOUT."Logout</a></li>
        </ul>
    </nav>	
	";
}


if($thisPage == 'add-bulk-message') {
   
    if( isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ) {
       $user_name = '<span class="header-user-name">'.$USER_ICON.$_SESSION['sub-user-name'].'</span>' ;
	   $add_user = '';
       $setting = '<li><a href="/setting">'.$SETTING.'Settings</a></li>';
	} elseif( !isset($_SESSION['user-status']) ) {
	    $user_name = '<span class="header-user-name">'.$USER_ICON.'Admin</span>';
	    $add_user = '<li><a href="/add-user">'.$ADDUSER.'Add user</a></li>';
        $setting = '<li><a href="/admin-settings">'.$SETTING.'Settings</a></li>';
        
        
        $store_id = $_SESSION['user-id'];
        $bulk = $mysqli->query("SELECT * FROM messages_bulkaccount WHERE store_id = $store_id AND enabled = 1");
        
        if($bulk->num_rows > 0) {
            $bulk_content = '<li><a href="/bulk">'.$BULKTEXT.'Bulk Messaging</a></li>';
        } else {
            $bulk_content = '';
        }
  
        
	}
   
    $mynumber = isset($_SESSION['mynumber']) ? $_SESSION['mynumber'] : '';
	$header = '<h1 class="storeDetail">'
    .'<span class="sdName">'.$_SESSION['user-name'].'</span>'
    .'<span class="sdNumber">'.$mynumber.'</span>'
    .$user_name
    .'<span class="sdId">'.$_SESSION['user-id'].'</span>'
    .'</h1>';
            
     
	$secondNav = "
    	<nav id='members-nav'  class='users-nav'>
    	    <ul>
            	<li><a href='/members/'>".$MESSAGE."Messages</a></li>
            	$add_user
                <li><a  href='/users'>".$USER."Users</a></li>
            	".$setting ."
            	".$bulk_content."
                <li><a href='/includes/script-logout.php'>".$LOGOUT."Logout</a></li>
            </ul>
        </nav>	
	";
}


if($thisPage == 'edit-bulk-message') {
   
    if( isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ) {
       $user_name = '<span class="header-user-name">'.$USER_ICON.$_SESSION['sub-user-name'].'</span>' ;
	   $add_user = '';
       $setting = '<li><a href="/setting">'.$SETTING.'Settings</a></li>';
	} elseif( !isset($_SESSION['user-status']) ) {
	    $user_name = '<span class="header-user-name">'.$USER_ICON.'Admin</span>';
	    $add_user = '<li><a href="/add-user">'.$ADDUSER.'Add user</a></li>';
        $setting = '<li><a href="/admin-settings">'.$SETTING.'Settings</a></li>';
        
        
        $store_id = $_SESSION['user-id'];
        $bulk = $mysqli->query("SELECT * FROM messages_bulkaccount WHERE store_id = $store_id AND enabled = 1");
        
        if($bulk->num_rows > 0) {
            $bulk_content = '<li><a href="/bulk">'.$BULKTEXT.'Bulk Messaging</a></li>';
        } else {
            $bulk_content = '';
        }
  
        
	}
   
    $mynumber = isset($_SESSION['mynumber']) ? $_SESSION['mynumber'] : '';
	$header = '<h1 class="storeDetail">'
    .'<span class="sdName">'.$_SESSION['user-name'].'</span>'
    .'<span class="sdNumber">'.$mynumber.'</span>'
    .$user_name
    .'<span class="sdId">'.$_SESSION['user-id'].'</span>'
    .'</h1>';
            
     
	$secondNav = "
    	<nav id='members-nav'  class='users-nav'>
    	    <ul>
            	<li><a href='/members/'>".$MESSAGE."Messages</a></li>
            	$add_user
                <li><a  href='/users'>".$USER."Users</a></li>
            	".$setting ."
            	".$bulk_content."
                <li><a href='/includes/script-logout.php'>".$LOGOUT."Logout</a></li>
            </ul>
        </nav>	
	";
}





if($thisPage == 'home') {
    ?>
        <header><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><?=$header ?></header>
        <?=$secondNav ?>
    <?php
    
}
    

?>
<!--<header>-->
	<?php
	   // echo $header; 
	?>
<!--</header>-->
<?php
// 	echo $secondNav;
?>
