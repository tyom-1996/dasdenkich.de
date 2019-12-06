<?php
session_start();
//echo "<pre>";var_dump($_SESSION);die;
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/constants.php";


if(empty($_SESSION['auth']) || $_SESSION['auth'] != $authcode) {
	header("location:/anmelden.php?msg=notauth");
	exit();
}

if(isset($_GET['search']) && !empty($_GET['search'])){
    $query = 'SELECT DISTINCT(messages.fromcellnumber), messages_contacts.name,messages_contacts.is_archive, messages.mynumber
    FROM messages
    LEFT JOIN messages_contacts 
    ON
    messages.fromcellnumber = messages_contacts.cellnumber  and  messages.store_id = messages_contacts.storeid
    WHERE messages.store_id = '.$_SESSION['user-id'].' 
    AND messages.fromcellnumber is not null
    AND messages_contacts.cellnumber LIKE "%'.$_GET['search'].'%"
    OR 
    messages.store_id = '.$_SESSION['user-id'].' 
    AND messages.fromcellnumber is not null
    and messages_contacts.name LIKE "%'.$_GET['search'].'%" 
    ORDER BY messages.id DESC';
} else {
    $query = 'SELECT DISTINCT(messages.fromcellnumber), messages_contacts.name, messages_contacts.is_archive, messages.mynumber
    FROM messages
    LEFT JOIN messages_contacts ON messages.fromcellnumber = messages_contacts.cellnumber and messages.store_id = messages_contacts.storeid
    WHERE messages.store_id = '.$_SESSION['user-id'].' and messages.fromcellnumber is not null
    ORDER BY messages.id DESC';
}


$result = $mysqli->query($query);
$num = $result->num_rows;

$names            = array();
$numbers          = array();
$messages         = '';
$archive_messages = '';

$msgs_array       = [];
$is_subuser = isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? true : false;


function abbrText($text, $length){
    if(strlen($text) > $length)
        return substr($text, 0, $length) ;
    else
        return $text;
}



// GET country code

function getCountryCode()
{   
    global $mysqli;
    
    $store_id  = $_SESSION['user-id'];  
    $msg_setup = $mysqli->query("SELECT * FROM messages_setup WHERE store_id = $store_id ")->fetch_assoc();
    return $msg_setup['country_code'];
}



//GET ALL SUB USERS

function subUsersLists($fromcellnumber)
{
    global $mysqli,$EDIT_USER,$ADD_USER,$USER_ICON,$is_subuser;
        
    $store_id         = $_SESSION['user-id'];     
    $archive_qwery    = "SELECT * FROM messages_contacts WHERE cellnumber = $fromcellnumber  AND storeid = $store_id";
    $archive_contacts = $mysqli->query($archive_qwery)->fetch_assoc();
    $is_archive       = $archive_contacts['is_archive'] == 1 ? true : false; 
    
    $incharge_data    = $mysqli->query("SELECT * FROM messages_subusers WHERE  id IN ( SELECT subuser_id FROM messages_incharge WHERE fromcellnumber = $fromcellnumber AND status = '1' AND store_id = $store_id ) ")->fetch_assoc();
    $sub_name         = $incharge_data['sub_name'];
    $sub_userID       = $_SESSION['sub-user-id'];
    
    if(isset($incharge_data['logo']) && !empty($incharge_data['logo'])) 
    {
        $logo_url      = $incharge_data['logo'];
        $selected_user = '<div class="img-wr"><img src="/logos/'.$logo_url.'"></div>'.$sub_name;
    } 
    
    else 
    {
        $logo_url      = "/images/user.png";
        $selected_user = '<div class="img-wr"><img src="'.$logo_url.'"></div>'.$sub_name;
    }
    
    // If the admin does not have a logo, show the default logo
    
    $admin_data  = $mysqli->query("SELECT * FROM messages_setup WHERE store_id = $store_id ")->fetch_assoc();
    
    if(isset($admin_data['logo']) && !empty($admin_data['logo'])) 
    {
        $admin_logo_url = $admin_data['logo'];
        $admin_logo     = '<div class="img-wr"><img src="/logos/'.$admin_logo_url.'"></div> Admin';
    } 
    
    else 
    {
        $admin_logo_url      = "/images/user.png";
        $admin_logo          = '<div class="img-wr"><img src="'.$admin_logo_url.'"></div> Admin';
    }
    
   
    $archive_msg_btn = '<li data-placement="top" data-toggle="modal" title="Archive" class="archive_message" data-target="#archive_message"  data-fromcellnumber="'.$fromcellnumber.'" >'
                            .'<button data-contactid="'.$archive_contacts['id'].'"  data-title="Archive"  >
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                            <span> Archive This Message</span>
                        </li>';
                        
    $subusers_sql    = "SELECT * FROM messages_subusers WHERE store_id ='{$_SESSION['user-id']}'";
    $subusers        = $mysqli->query($subusers_sql);
    
    $users_list      = '';
    $subusers_arr    = [];
    
    // Create SUB-USERS array;
    while($subus = $subusers->fetch_assoc()) {
        $subusers_arr[] = $subus;
    }
    
    // Sort SUB-USERS array;
    usort($subusers_arr, function($a, $b) {
        return $a['sub_name'] <=> $b['sub_name'];
    });
  
    // Create SUB-USERS LIST CONTENT
    foreach($subusers_arr as $subus ) { 
       
        $subus_logo_url = $subus['logo'];
        $subus_logo     = !empty($subus_logo_url) ? "<img src='/logos/$subus_logo_url'>" : "<img src='/images/user.png'>";
		$user_name      = $subus['sub_name'] == '' ? 'No name' : $subus['sub_name'];
        
        $users_list    .= '<li class="select_sub_user" data-id="'.$subus['id'].'"  data-fromcellnumber="'.$fromcellnumber.'">'.$subus_logo.$user_name.'</li>';
	}

    $return_content = '';
    
    if($is_subuser) {
        if(!$is_archive) {
            
            $users_list .= '<li class="select_sub_user back-to-admin" data-fromcellnumber="'.$fromcellnumber.'" >'.$USER_ICON.'Back to the Admin</li>'.$archive_msg_btn;
            
            if (empty($incharge_data)) {
                
                $return_content = " 
                    <div class='msg_user'>
                        <div  class='selected-user' > <span class='selected-user-name'>$admin_logo</span> </div>
                            <div> 
                                <span style='display: none;' class='open-users-list'> $EDIT_USER Change user </span>
                                <span  class='open-users-list'> $ADD_USER Add user </span>
                            </div>    
                        <ul class='users-list'> $users_list </ul>
                    </div>
                ";
                
            } else {
                
                $return_content =  "
                    <div class='msg_user'>
                        <div class='selected-user'>
                          <span class='selected-user-name'> $USER_ICON $selected_user</span>
                        </div>
                        <span class='open-users-list'> $EDIT_USER Change user </span>
                        <span style='display: none;' class='open-users-list'>Add user</span>
                        <ul class='users-list' data-user='true'> $users_list </ul>
                    </div>
                ";
                
            }
            
        }  else {
           
            $users_list    .= '<li class="select_sub_user back-to-admin" data-fromcellnumber="'.$fromcellnumber.'" >'.$USER_ICON.'Back to the Admin</li>';
            $return_content = " 
                <div class='msg_user'>
                    <div  class='selected-user' > <span class='selected-user-name'>Archived</span> </div>
                    <span style='display: none;' class='open-users-list'> $EDIT_USER Change user </span>
                    <span  class='open-users-list'> $ADD_USER Add user </span>
                    <ul class='users-list'> $users_list </ul>
                </div>
            ";
            
        } 
        
    } else {
        
        if(!$is_archive) {
          
           
           if (empty($incharge_data)) {
                
                $users_list    .= $archive_msg_btn;
                $return_content = " 
                    <div class='msg_user'>
                        <div  class='selected-user'> <span class='selected-user-name'>$admin_logo</span> </div>
                            <div> 
                                <span style='display: none;' class='open-users-list'> $EDIT_USER Change user </span>
                                <span  class='open-users-list'> $ADD_USER Add user </span>
                            </div>    
                        <ul class='users-list'> $users_list </ul>
                    </div>
                ";
    
            } else {
                
                $users_list    .= '<li class="select_sub_user back-to-admin" data-fromcellnumber="'.$fromcellnumber.'" >'.$USER_ICON.'Back to the Admin</li>'.$archive_msg_btn;
                $return_content =  "
                    <div class='msg_user'>
                        <div class='selected-user'>
                          <span class='selected-user-name'> $USER_ICON $selected_user </span>
                        </div>
                        <div>
                            <span class='open-users-list'> $EDIT_USER Change user </span>
                            <span style='display: none;' class='open-users-list'>Add user</span>
                        </div>
                        <ul class='users-list' > $users_list </ul>
                    </div>
                ";
            }
            
        } else {
            
           $users_list   .= '<li class="select_sub_user back-to-admin" data-fromcellnumber="'.$fromcellnumber.'" >'.$USER_ICON.'Back to the Admin</li>';
           $return_content = " 
                <div class='msg_user'>
                    <div  class='selected-user'> <span class='selected-user-name'>Archived</span> </div>
                    <span style='display: none;' class='open-users-list'> $EDIT_USER Change user </span>
                    <span  class='open-users-list'> $ADD_USER Add user </span>
                    <ul class='users-list'> $users_list </ul>
                </div>
            ";
          
        }
        
    }
    
    return $return_content;
    
}



function defaultMsg()
{
     // Default message
   
    global $mysqli;
    
    $store_id = $_SESSION['user-id'];
    $default  = [];
   
    $msg_query        = "SELECT * FROM messages_msg WHERE subuser_id = 0 AND is_default = '1' AND store_id = $store_id ";
    $default_msg      =  $mysqli->query($msg_query);
    
    if($default_msg->num_rows < 1) {
        
        $store_domain = $mysqli->query("SELECT * FROM store WHERE str_id = $store_id")->fetch_assoc();
        $url          = 'https://'.$_SERVER['SERVER_NAME'].'/'.$store_domain['str_domain'];
      
        $default_data = [
            'id' => 0,
            'store_id'       => $store_id,
            'storename'      => $_SESSION['user-name'],
            'storenumber'    => $_SESSION['mynumber'],
            'subuser_id'     => '0',
            'text'           => "Do you have 30 seconds to leave a review for us? Just click the link below  $url " ,
            'text_is_public' => '0',
            'is_default'     => 1
        ];
        
        $default = $default_data;
        
    }
    
    return $default;
}




function defaultTextsContent()
{
    global $mysqli,$is_subuser;
    
    $store_id = $_SESSION['user-id'];
    $mynumber = $_SESSION['mynumber'];
   
    if(isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user') {
        $subuser_id = $_SESSION['sub-user-id'];
        $query      = "SELECT * FROM messages_msg WHERE subuser_id = $subuser_id AND store_id = $store_id OR subuser_id = 0 AND store_id = $store_id AND text_is_public = 1 ";
    } else {
        $query = "SELECT * FROM messages_msg WHERE subuser_id = 0 AND store_id = $store_id";
    }
   
    $texts     = $mysqli->query($query);
    $texts_arr = [];
    $options   = "<option value='' >Select a default message</option>";
    
    while($row = $texts->fetch_assoc()) {
        $texts_arr[] = $row;
    };
    
    
    // Default text; 
    
    if(!$is_subuser) {
        if(!empty(defaultMsg())) {
           $texts_arr[] = defaultMsg();
        }
    }
    
    foreach($texts_arr as $row) {
        $options .="<option value='".$row['text']."'>".$row['text']."</option>";
    }
    
    $content .= "<select class='add_default_text'>$options</select>";
    
    return $content;
}



function getSubuserName($subuser_id)
{
    global $mysqli;
    
    $subuser_id = (int) $subuser_id;
    $subuser    =  $mysqli->query("SELECT * FROM messages_subusers WHERE id = $subuser_id ")->fetch_assoc();
    $name       = !empty($subuser) ? $subuser['sub_name'] : 'admin';
   
    return $name ;
}



function checkUserByFromcellnumber($fromcellnumber)
{
    global $mysqli,$is_subuser;
    
    $bool = true;
    
    if($is_subuser   )
    {
        $subuser_id        = $_SESSION['sub-user-id'];
        $status            = '1';
        $messages_contacts = $mysqli->query("select * from messages_incharge where fromcellnumber ='{$fromcellnumber}' AND subuser_id = '{$subuser_id}' AND status = '{$status}' ")->fetch_assoc();
        $bool              = empty($messages_contacts) ? false : true;
    }
    return $bool;
}

function archiveMessage($msg_date)
{
    global $mysqli; 
    
    $msg_setup    = $mysqli->query("SELECT * FROM messages_setup WHERE store_id = '{$_SESSION['user-id']}'")->fetch_assoc();
    $retire_days  = $msg_setup['retire_days'];
    $current_date = $date = date('Y-m-d H:i:s');
    $diff         = strtotime($current_date) - strtotime($msg_date);
    $days_passed  = (int)$diff/(60*60*24);
    $archive_msg  = round($days_passed) >= $retire_days ? true : false; 
    
    return $archive_msg;
}


function recentSentMsg($fromcellnumber)
{
    global $mysqli;


	$recentSentQuery  = "SELECT message_out_sending FROM messages WHERE  store_id = '{$_SESSION['user-id']}' and fromcellnumber = '{$fromcellnumber}' AND message_incoming ='' order by id desc ";
	$recentSentResult = $mysqli->query($recentSentQuery);
	$recentSentRow    = $recentSentResult->fetch_assoc();
	$recentSentMsg    = $recentSentRow['message_out_sending'];
    $text             = abbrText($recentSentMsg, 30);
    
    if(strlen($recentSentMsg) > 30 ) {
        $text.= '...';
    }    
    
    return $text;
}


function recentRcvMsg($fromcellnumber)
{
    global $mysqli;
    
    $recentRcvQuery   = "select message_incoming from messages where  store_id = '{$_SESSION['user-id']}' and fromcellnumber = '{$fromcellnumber}' AND message_out_sending =''  order by id desc";
	$recentRcvResult  = $mysqli->query($recentRcvQuery);
	$recentRcvRow     = $recentRcvResult->fetch_assoc();
	$recentRcvMsg     = $recentRcvRow['message_incoming'];
    $text             = abbrText($recentRcvMsg, 30);
    
    if(strlen($recentRcvMsg) > 30 ) {
        $text.= '...';
    }    
    
    return $text;
    
}

function wrapLinkInTag($text){
    return preg_replace('/\b(https?:\/\/[\S]+)/si', '<a target="_blank" href="$1">$1</a>', htmlspecialchars($text));
}


function subuserMyMessage($fromcellnumber)
{
    global $mysqli;
    
    $subuser_id   = $_SESSION['sub-user-id'];
    $store_id     = $_SESSION['user-id'];
    $msg_incharge = $mysqli->query("SELECT * FROM messages_incharge WHERE store_id = $store_id AND fromcellnumber = '$fromcellnumber' AND subuser_id = $subuser_id AND status = '1' ");
    $my_msg       = $msg_incharge->num_rows > 0 ? true : false;
    
    return $my_msg;
}


function assignedMessages($fromcellnumber)
{
    global $mysqli;
    
    $store_id     = $_SESSION['user-id'];
    $msg_incharge = $mysqli->query("SELECT * FROM messages_incharge WHERE store_id = $store_id AND fromcellnumber = '$fromcellnumber' AND subuser_id != -1 AND subuser_id != 0 AND  status = '1' order by id desc ");
    $assigned     = $msg_incharge->num_rows > 0 ? true : false;
    
    return $assigned;
}


// Sub user and Admin Messages
// $archive_msgs          = [];

// Sub user messages; 
// $active_message        = [];
$subuser_mymessage     = [];

// Admin messages;
$not_assigned_messages = [];
// $assigned_messages     = [];


if( $num >=1 ) {
    
  
    while( $r = $result->fetch_assoc() ) {
        
	    $newQuery       = "select * from messages where store_id like '{$_SESSION['user-id']}' and fromcellnumber like '{$r['fromcellnumber']}' and sent is null and viewed is null and message_incoming != '' order by id desc";
		$newQueryResult = $mysqli->query($newQuery);
		$newQueryNum    = $newQueryResult->num_rows;
        $incom_message  = $newQueryResult->fetch_assoc();
       
        if ( $newQueryNum >= 1 ) {
            $r['send_date'] = $incom_message['date'];
        }
        
        $subuser_id         = $is_subuser ? $_SESSION['sub-user-id'] : 0;
        $incharge_query     = "SELECT * FROM messages_incharge WHERE store_id like '{$_SESSION['user-id']}' AND fromcellnumber like '{$r['fromcellnumber']}' AND status = '1' AND subuser_id = '{$subuser_id}' AND viewed = '0' order by id desc";
        $check_new_incharge = $mysqli->query($incharge_query);
        $incharge           = $check_new_incharge->fetch_assoc();
        
        if ($check_new_incharge->num_rows >= 1) {
            $r['send_date'] = $incharge['incharge_since'];
        }
        
        if ($r['is_archive'] == '1') {
            $archive_msgs[] = $r;
        } else {
            if ($is_subuser) {
                
                if (subuserMyMessage($r['fromcellnumber'])) {
                    
                    if ( $newQueryNum >= 1 || $check_new_incharge->num_rows >= 1 ) {
                        $subuser_mymessage['incom'][] = $r; 
                    } else {
                       $subuser_mymessage['outsending'][] = $r;
                    }
                    
                }
                
            } else {
                
                if (!assignedMessages($r['fromcellnumber'])) {
                    
                    if ($newQueryNum >= 1 || $check_new_incharge->num_rows >= 1) {
                       $not_assigned_messages['incom'][]      = $r; 
                    } else {
                       $not_assigned_messages['outsending'][] = $r;
                    }
                    
                } 
            }
        }
    }
}


usort($subuser_mymessage['incom'],     function($a, $b) { return strtotime($a['send_date']) < strtotime($b['send_date']); }); 
usort($not_assigned_messages['incom'], function($a, $b) { return strtotime($a['send_date']) < strtotime($b['send_date']); }); 

$subuser_mymessage     = array_merge(isset($subuser_mymessage['incom'])     ? $subuser_mymessage['incom']     : [], isset($subuser_mymessage['outsending'])     ? $subuser_mymessage['outsending']     : []);
$not_assigned_messages = array_merge(isset($not_assigned_messages['incom']) ? $not_assigned_messages['incom'] : [], isset($not_assigned_messages['outsending']) ? $not_assigned_messages['outsending'] : []);

if( $num >= 1 ) {
	
   
	
	
	if(!$is_subuser) {
	    
    	
    	 //Create Not assigned msg contacts list  	
    	
    	$not_assigned_messages_content = '<div id="not-assigned-contactList">';
    	
    	foreach( $not_assigned_messages as  $row  ) {
    	    
    		if( !empty($row['name']) ) {
    			$name                          = $row['name'];
    			$names[$row['fromcellnumber']] = $row['name'];
    		} else {
    			$name = '<span style="font-size: .6em">(name not set)</span>';
    		}
    
    		$newQuery       = "select * from messages where store_id = '{$_SESSION['user-id']}' and fromcellnumber = '{$row['fromcellnumber']}' and sent is null and viewed is null and message_incoming != '' and message_out_sending = '' order by id desc";
    		$newQueryResult = $mysqli->query($newQuery);
    		$newQueryNum    = $newQueryResult->num_rows;
    		
    	    $recentSentMsg  = recentSentMsg($row['fromcellnumber']);
            $recentRcvMsg   = recentRcvMsg($row['fromcellnumber']); 
            
            $incharge_query     = "SELECT * FROM messages_incharge WHERE store_id like '{$_SESSION['user-id']}' AND fromcellnumber like '{$row['fromcellnumber']}' AND status = '1' AND subuser_id = 0 AND viewed = '1' order by id desc";
            $check_new_incharge = $mysqli->query($incharge_query);
         
    		if( $newQueryNum >= 1 || $check_new_incharge->num_rows < 1 ){
    		    $not_assigned_messages_content .= '
    		        <div class="contactList-msg-item">
                        <div class="newMsgList msg-item-title">
                                
                                <a href="#detail-'.$row['fromcellnumber'].'" class="showDetails open-msgs-block">'.$row['fromcellnumber'].'</a>
                                <span>'.$name.'</span>'
        		        .'</div>'
        		        .'<span class="toolTip">
            		          <div><i class="fas fa-arrow-right"></i><span class="toolTipIn">'.$recentRcvMsg.'</span></div>
            		          <div><i class="fas fa-arrow-left"></i><span class="toolTipIn">'.$recentSentMsg.'</span></div>
        		        </span>
        		        '.subUsersLists($row['fromcellnumber']).'
                   </div>';
    	    } else{
    		    $not_assigned_messages_content .= '
    		        <div class="contactList-msg-item">
    	                 <div class="msg-item-title">
    	                    <a href="#detail-'.$row['fromcellnumber'].'" class="showDetails open-msgs-block">'.$row['fromcellnumber'].'</a>
    	                    <span>'.$name.'</span>'
        		        .'</div>'
        		        .'<span class="toolTip">
        		              <div><i class="fas fa-arrow-left"></i><span class="toolTipIn">'.$recentSentMsg.'</span></div>
                              <div><i class="fas fa-arrow-right"></i><span class="toolTipIn">'.$recentRcvMsg.'</span></div>
                        </span>
                        '.subUsersLists($row['fromcellnumber']).'
                    </div>';
    	    }
    	    
    		$numbers[] = $row['fromcellnumber']; 
    	}
    	$not_assigned_messages_content .= '</div>';
    	
	    
	} else {
	    
	   // SUBUSER MESSAGES
	
    	
    	// Create My messages  contacts list 
	   
	    $my_messages = '<div id="my_messages">';
	
    	foreach( $subuser_mymessage as  $row  ) {
    	    
    		if( !empty($row['name']) ) {
    			$name                          = $row['name'];
    			$names[$row['fromcellnumber']] = $row['name'];
    		} else {
    			$name = '<span style="font-size: .6em">(name not set)</span>';
    		}
    		
    // 		$newQuery           = "select * from messages where store_id = '{$_SESSION['user-id']}' and fromcellnumber = '{$row['fromcellnumber']}' and sent is null and viewed is null and message_incoming is not null";
    		$newQuery           = "select * from messages where store_id = '{$_SESSION['user-id']}' and fromcellnumber = '{$row['fromcellnumber']}' and sent is null and viewed is null and message_incoming != '' and message_out_sending = '' order by id desc";
    		$newQueryResult     = $mysqli->query($newQuery);
    		$newQueryNum        = $newQueryResult->num_rows;
    	
    	    $recentSentMsg      = recentSentMsg($row['fromcellnumber']);
            $recentRcvMsg       = recentRcvMsg($row['fromcellnumber']); 
            $subuser_id         = $_SESSION['sub-user-id'];
            
            $incharge_query     = "SELECT * FROM messages_incharge WHERE store_id like '{$_SESSION['user-id']}' AND fromcellnumber like '{$row['fromcellnumber']}' AND status = '1' AND subuser_id = $subuser_id AND viewed = '1' order by id desc";
            $check_new_incharge = $mysqli->query($incharge_query);
         
    		if( $newQueryNum >= 1 || $check_new_incharge->num_rows < 1 ){
    		    $my_messages .= '<div class="contactList-msg-item">
                    <div class="newMsgList msg-item-title">
                            
                            <a href="#detail-'.$row['fromcellnumber'].'" class="showDetails open-msgs-block">'.$row['fromcellnumber'].'</a>
                            <span>'.$name.'</span>'
        	        .'</div>'
        	        .'<span class="toolTip">
        		        <div><i class="fas fa-arrow-right"></i><span class="toolTipIn">'.$recentRcvMsg.'</span></div>
        		        <div><i class="fas fa-arrow-left"></i><span class="toolTipIn">'.$recentSentMsg.'</span></div>  
        	        </span>
                   '.subUsersLists($row['fromcellnumber']).' 
                </div>';
    	    } else{
    		    $my_messages .= '<div class="contactList-msg-item">
    		        
                    <div class="msg-item-title">
                        <a href="#detail-'.$row['fromcellnumber'].'" class="showDetails open-msgs-block">'.$row['fromcellnumber'].'</a>
                        <span>'.$name.'</span>'
    		        .'</div>'
    		        .'<span class="toolTip">
    		            <div><i class="fas fa-arrow-left"></i><span class="toolTipIn">'.$recentSentMsg.'</span></div>
                        <div><i class="fas fa-arrow-right"></i><span class="toolTipIn">'.$recentRcvMsg.'</span></div>
                    </span>
                    '.subUsersLists($row['fromcellnumber']).'
                </div>';
    		     
    	    }
    	    
    		$numbers[] = $row['fromcellnumber'];
    	}
    	$my_messages  .= '</div>';
	}
	
}





function getUserLogo($id,$store_id)
{
    global $mysqli;
    if($id != 0 ) {
        $select_query = "SELECT * FROM messages_subusers WHERE id = $id AND store_id = $store_id";
    } else {
        $select_query = "SELECT * FROM messages_setup WHERE store_id = $store_id ";
    }
   
    $user_data = $mysqli->query($select_query)->fetch_assoc();
    if(empty($user_data['logo'])) {
        $logo_url = "/images/user.png";
    } else {
        $logo_url = "/logos/".$user_data['logo'];
    }
    
    return $logo_url;
   
}


$details = "";

foreach($numbers as $number) {

    $query = 'SELECT * FROM messages WHERE store_id = '.$_SESSION['user-id'].' AND fromcellnumber = '.$number.' ORDER BY id ASC';
	$result = $mysqli->query($query);

	$nameEntry = '';
	$nameEntry = '<form name="frm_'.$number.'"class="name-input enter-a-name" method="post" action="/mobile/messages/add-name.php" onsubmit="return validateForm('.$number.')">'."\n"
					.'<input type="hidden" name="number" value="'.$number.'">'."\n";

	if(empty($names[$number])) {
		$nameEntry = $nameEntry.'<input type="text" placeholder="enter a name"  name="addname" maxlength="50" value="" required style="padding: 5px;"><input type="hidden"  type="text" name="updateFlag" value="0">';
	}
    else{
		$nameEntry  = $nameEntry.'<input type="text" placeholder="enter a name"  name="addname" value="'.$names[$number].'" maxlength="50" required  style="padding: 5px;">'."\n";
        $nameEntry  = $nameEntry.'<input type="hidden"  type="text" name="updateFlag" value="1">'."\n";
    }

    $nameEntry = $nameEntry.'</form>';
	$replyEntry = '<form class="message-reply" name="msgFrm_'.$number.'" method="post" action="/mobile/messages/send-message.php"  onsubmit="return validateForm('.$number.')">'
                    	.'<input type="hidden" name="number" value="'.$number.'"> '
                    	.'<input type="hidden" id="contacterName" name="contacterName" value=""> '
        				
        		    	.'<div class="msg-panel">'
        				    .defaultTextsContent()
        				    .'<input name="message" class="message-inp" placeholder="Reply to message" maxlength="150" >'
        				    .'<button><i class="fas fa-paper-plane"></i></button>'
        				.'</div>'    
				  .'</form>';

	$showName = '';
	if(!empty($names[$number])) {
		$showName = $names[$number];
	}

    $details .= "
        <div id='detail-$number' style='display: none;' class='hide-detail all-messages'>
        <div class='msg-item-info'>
    	    <h2><i class='fas fa-phone phone-icon'></i>$number</h2>
    	    <h2 class='open-name-inp'><i class='fas fa-user user-icon'></i>$names[$number]</h2>
            $nameEntry
        </div> 
        <div class='backforth'>
    ";
    
    
	while($row = $result->fetch_assoc()) {
	    
	    $subuser_id = $row['subuser_id'];
        $store_id   = $row['store_id'];
        
	    if(!empty($row['message_incoming'])) {
	        
		    $date             = $row['date'];
		    $subuser_name     = getSubuserName($subuser_id);
		    $message_incoming = $row['message_incoming'];
            $details .= "
                <div class='message-in'>
                    $message_incoming
                    <div class='message-date'> $date </div>    
                </div>
            ";
		}
		if(!empty($row['message_out_sending'])) {
		    $datasent            = $row['datesent'];
	        $sent                = $row['sent'];
		    $date                = empty($datasent) && empty($sent) ? 'scheduled' : $row['date'];
			$sent                = empty($row['datesent']) ? '(scheduled)' : $row['datesent'];
			$subuser_name        = getSubuserName($subuser_id);
            $subuser_logo        = getUserLogo($subuser_id,$store_id); 
            
			$message_out_sending = wrapLinkInTag($row['message_out_sending']);
		    
		    $short_url           = $row['image_url'];
			$image               = !empty($row['image_url']) ? "<a class='img-link' href =".$short_url." target='_blank'> ".$_SERVER['SERVER_NAME'].$short_url." </a>" : '';
		    
// 			$details .= "
// 			    <div class='message-out'>
//                     <div class='msg-sender'><img src='".$subuser_logo."'> $subuser_name says....</div>
//                     $message_out_sending
//                     <div class='message-date'> $date </div>    
//                 </div>
// 			";
			
			$details .= "
			    <div class='message-out'>
                    <div class='msg-sender'><img src='".$subuser_logo."'> $subuser_name says....</div>
                    <span class='message-text'>$message_out_sending</span>
                    $image
                    <div class='message-date'> $date </div>    
                </div>
			";
		}
	}
	$details .= "
	</div>
	   
     $replyEntry
	</div>";
}
			



$message = '';

if(!empty($_GET['msg'])) 
{
    if (isset($_SESSION['send_message'])) {
        $error_msg1 = $errors[$l][$_GET['msg']][0];
        $error_msg2 = $errors[$l][$_GET['msg']][1];
        $message    = "<div id='test' class='$error_msg1'>$error_msg2</div>";
        unset($_SESSION['send_message']);
    }
}


    
function getLogo()
{   
    global $mysqli,$is_subuser;
    
    $store_id   = $_SESSION['user-id'];
    $subuser_id = $_SESSION['sub-user-id'];
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




$thisPage = "members";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/banner.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/account-balance.php";
$title = $lang['title'][$l];


?>

<!DOCTYPE html>
<html lang="de">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $lang['title'][$l]; ?></title>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/mobile/messages/mob-messages.css">
</head>

<style>
    .nav.nav-tabs li{
        margin-bottom:0!important;
    }
</style>   

<body id="<?php echo $thisPage; ?>">
    
    <input type="hidden" class='user-type' value="<?php echo isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? subuser : admin ?>"></input>
    <div class="loader-bl"></div>

    <div id="main">
       
        
        <div class="right">
            
            <header> 
                <ul>
                    <li class="toggle-sidebar"> 
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 60.123 60.123" style="enable-background:new 0 0 60.123 60.123;" xml:space="preserve"><g><path d="M57.124,51.893H16.92c-1.657,0-3-1.343-3-3s1.343-3,3-3h40.203c1.657,0,3,1.343,3,3S58.781,51.893,57.124,51.893z"></path><path d="M57.124,33.062H16.92c-1.657,0-3-1.343-3-3s1.343-3,3-3h40.203c1.657,0,3,1.343,3,3 C60.124,31.719,58.781,33.062,57.124,33.062z"></path><path d="M57.124,14.231H16.92c-1.657,0-3-1.343-3-3s1.343-3,3-3h40.203c1.657,0,3,1.343,3,3S58.781,14.231,57.124,14.231z"></path><circle cx="4.029" cy="11.463" r="4.029"></circle><circle cx="4.029" cy="30.062" r="4.029"></circle> <circle cx="4.029" cy="48.661" r="4.029"></circle></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>S<g></g><g></g> <g></g><g></g><g></g><g></g></svg>
                    </li>
                    
                    <li class="search-li">
                        <div class="msg-filter-block" >
                            <form method="GET">
                                <input placeholder="search..." id="search" name="search" type="text">
                                <img src="/images/mobile/mob-search.png" style="position: absolute;left: 6px;width: 31px;">
                            </form>
                        </div>    
                    </li>
                    
                   
                    
                    
                    <li class="login-user" style="margin-bottom: 2px;">
                        <div class="img-block">
                            <?=getLogo()?>
                        </div>

                        <div class="user-modal" >
                            <div class="user-modal-row">
                                
                            </div>
                            
                            <div class="user-modal-content">
                                <div class="profile-info">
                                    <p><?=isset($_SESSION['sub-user-name']) ? $_SESSION['sub-user-name'] : 'Admin'  ?></p>
                                    <p><?=$_SESSION['user-name']?></p>
                                    <p><?=$mynumber?></p>
                                </div>
                               <form class="logout-form" method="POST" action="/mobile/login/login-function.php" style="
    padding: 7px 16px;
    border-top: 1px dashed white;
">
                            <input name="action" type="hidden" value="logout">
                            <button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="
    fill: white;
    margin-right: 8px;
    width: 17px;
"><path d="M16 9v-4l8 7-8 7v-4h-8v-6h8zm-16-7v20h14v-2h-12v-16h12v-2h-14z"></path></svg>
<span style="
    color: white;
">Logout
</span>
                            </button>
                            
                        </form>
                            </div>
                        </div>     
                    </li>
                    
                    
                    
                    <li class="logout" style="display:none;">
                        <form class="logout-form" method="POST" action="/mobile/login/login-function.php">
                            <input  name="action" type="hidden" name="action" value="logout">
                            <button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M16 9v-4l8 7-8 7v-4h-8v-6h8zm-16-7v20h14v-2h-12v-16h12v-2h-14z"></path></svg>
                            </button>
                            
                        </form>
                    </li>
                  
                </ul>
            </header>
            
            
            <div class="wrap">
                
        		<!--<a href="/members/" class="button" style="display:none;">Refresh page</a>-->
        	
        		<?php echo $message; ?>
        		<div class="columns" style="display: flex;justify-content: space-between;flex-wrap: wrap;">
        		    
        		    
                    <div class="tab-content">
                      
                        <?php if($is_subuser): ?>
                        
                            <div id="my-msgs" class="tab-pane fade in active">
                               <div id="message-list">
                                   <div class="create-new-msg">
                        		        <a href="#detail-createMsg" class="button showDetails">Create A New Message</a>
                        		   </div>
                    			    <?php echo !empty($subuser_mymessage) ? $my_messages : '<p>No messages</p>'; ?>
                    		    </div>	    
                            </div>
                           
                        <?php else: ?>
                        
                            <div id="notassigned-msgs" class="tab-pane fade in active">
                                <div id="message-list">
                                    <div class="create-new-msg">
                        		        <a href="#detail-createMsg" class="button showDetails">Create A New Message</a>
                        		    </div>
                    				<?php echo !empty($not_assigned_messages) ? $not_assigned_messages_content : '<p>No messages</p>'; ?>
                    			</div>
                            </div>
                                   
                        <?php endif; ?>
                      
                        
                    </div>
                 
        		    
        		    
        		    
        		    
        		
        			<div id="message-detail">
                        <div id="detail-createMsg" style="display: none;">
                            <h2>Select a Message From The Left Or....</h2>
                            <h2>Create A New Message</h2>
                            
                            <form name="frm_createMsg" class="name-input" style="display:block;" method="post" onsubmit="return validateForm('createMsg')">
                                <div class="inp-row-bl">
                                    <div class="country_code">+<?php echo getCountryCode(); ?></div>
                                    <input style="padding-left:41px;background:white" class="create-msg-input phone-number-input" type="number" name="number" value="" maxlength="50" required placeholder="Phone Number">
                                    <div class="cr-msg-contact-list"></div>
                                </div>
                                
                                <div class="inp-row-bl">
                                    
                                    <input class="create-msg-input" type="text" name="addname" value="" maxlength="50" required="" placeholder="Contact Name" style="background:white">
                                    <div class="cr-msg-contact-list"></div>
                                </div>
                                <input type="hidden" name="updateFlag" value="1">
                            </form>
                            
                            <form class="message-reply" name="msgFrm_createMsg" method="post" action="/mobile/messages/send-message.php" onsubmit="return validateForm('createMsg')">
                                <input type="hidden"  name="number" value="">
                                <input type="hidden"  id="contacterName" name="contacterName" value="">
                                <input type="hidden"  name="action" value="create-msg-form">
                                <textarea name="message" class="message-inp"  placeholder="Reply to message (limit 150 characters)" maxlength="150"></textarea>
                                
                                <div class="msg-panel tttttt">
                                    <?php echo defaultTextsContent()?>
                                    <button>Send...</button>
                                </div>
                            </form>
                        </div>
        				<?php echo $details; ?>
        			</div>
        			
        		</div>
              
        	</div>
    		<?php //include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
    	</div>
    	
    
	</div>

    
    <div class="modal fade" id="archive_message" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="/mobile/messages/messages-function.php" method="POST">
                <input type="hidden" name="action" value="archive_message">
                <input class="contact-id" type="hidden" name="contact_id" value=''>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title custom_align" id="Heading">Archive This Message</h4>
                </div>
                <div class="modal-body">
    
                    <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to archive this Message?</div>
    
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
    
                    
<script>

    $(document).on('click','.open-users-list',function(){
  
        var user_list = $(this).parent().parent().find('.users-list');
        
        if(!user_list.hasClass('open')) {
            user_list.addClass('open')
        } else {
            user_list.removeClass('open')
        }  
        
        $(".users-list").each(function() {
            console.log($(this),user_list)
            if($(this)[0] != user_list[0]) {
                $(this).removeClass('open')
            }
        });

    })

    $(document).on('click','.select_sub_user',function(){
        
        var $this          = $(this),
            user_type      = $('input.user-type').val(),
            user_icon      = '<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user" class="svg-inline--fa fa-user fa-w-14" role="img" viewBox="0 0 448 512"><path fill="currentColor" d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"></path></svg>',
            change_user    = '<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user-edit" class="svg-inline--fa fa-user-edit fa-w-20" role="img" viewBox="0 0 640 512"><path fill="currentColor" d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h274.9c-2.4-6.8-3.4-14-2.6-21.3l6.8-60.9 1.2-11.1 7.9-7.9 77.3-77.3c-24.5-27.7-60-45.5-99.9-45.5zm45.3 145.3l-6.8 61c-1.1 10.2 7.5 18.8 17.6 17.6l60.9-6.8 137.9-137.9-71.7-71.7-137.9 137.8zM633 268.9L595.1 231c-9.3-9.3-24.5-9.3-33.8 0l-37.8 37.8-4.1 4.1 71.8 71.7 41.8-41.8c9.3-9.4 9.3-24.5 0-33.9z"></path></svg>',
            fromcellnumber = $(this).data('fromcellnumber'), 
            action,
            data,
            subuser_id     
            
        $('.loader-bl').fadeIn();
 
        if($this.hasClass('back-to-admin')) {
            action = 'back-to-admin';
            data   = {fromcellnumber:fromcellnumber,action:action};
        } else {
            action         = 'assign-message-subusers';
            subuser_id     = $this.data('id');
            data           = {fromcellnumber:fromcellnumber,subuser_id:subuser_id,action:action}
        }
        
        console.log(data) 
    

        $.ajax({
            url:  '/mobile/messages/assign-msg.php',
            type: 'POST',
            data: data,
            success:function(r){
                if(user_type == 'subuser') {
                    $this.closest('.contactList-msg-item').remove()
                } else if(user_type == 'admin') {
                    $this.closest('.msg_user').find('.selected-user').find('.selected-user-name').html(user_icon + r);
                    $this.closest('.msg_user').find('.open-users-list').empty().html(change_user+'Change user');
                    $this.closest('.msg_user').find('.users-list').slideUp();
                }
                
                $('.loader-bl').fadeOut();
                
                window.location.reload()
            }
        })

    })


    $(document).on('change','.add_default_text',function(){
        var textarea = $(this).closest('form.message-reply').find('.message-inp'),
            new_text = textarea.val()+' '+$(this).val();
        textarea.val(new_text);
        console.log(new_text);
    })

    function getQuery(q) {
        return (window.location.search.match(new RegExp('[?&]' + q + '=([^&]+)')) || [, null])[1];
    }

	$(document).ready(function(){

       
        
	    setTimeout(function() {
            $('.success').slideUp();
        },3000);

        $("a[href='detail-createMsg']").addClass('showActive');

        if(getQuery('msg') === null || getQuery('msg') == 'addmessageerror') {
            $("#detail-createMsg").show();
        }
   
		var hash = window.location.hash;
		
		if(hash) {
			$(hash).slideDown();
		}
		
		$(document).on('click','.msg-item-title > span,.toolTip',function() {
		    $(this).parent().find('a.showDetails').click()
		})
		
// 		$(document).on('click','.create-new-msg > a',function() {
// 		   	$('#detail-createMsg').show();
// 		   	$('#message-detail').show();
//             $('.tab-content').hide();
//             $('#message-detail').hide()
// 		})
	
		
		

        $(document).on('click','.showDetails',function() {
		    event.preventDefault();
		    var $this = $(this)
		    
		    $(this).parent().removeClass('newMsgList')
		    $(".users-list").removeClass('open')
		    
			$('#message-list div.showActive').removeClass('showActive');
			$('#message-list div.active-contactList-msg').removeClass('active-contactList-msg');
            $(this).parent().addClass('showActive');
            $(this).closest('.contactList-msg-item').addClass('active-contactList-msg');
            $('.backforth').animate({ scrollTop: $(document).height()+$(window).height() });
            
			var href = $(this).attr("href");
		    var sdNumber = href;
			sdNumber = sdNumber.substr(8);
			$(href).show();
			$(".hide-detail").not(href).hide();
            console.log(sdNumber);
			var storeId = "<?php echo $_SESSION['user-id'] ?>";


            $.ajax({
                url: '/mobile/messages/set-view-flag.php',
                dataType: 'json',
                type: 'post',
                data:{ storeId: storeId, storeNumber: sdNumber},
                success: function( data, textStatus, jQxhr ){
                    if(data == true){
              
                        $this.parent().find("img").hide();
                        if($this.hasClass('open-msgs-block')){
                           $('#detail-createMsg').slideUp();
                        }
                       
                        // $("html, body").animate({ scrollTop: 0 }, "slow");
                        $('.tab-content').toggle();
                        $('#message-detail').toggle()
                    }
                    
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    console.log( errorThrown );
                }
            });

		});


	});

    function validateForm(frmNumber) {
        var frmName = "frm_" + frmNumber;
        var msgFrm = "msgFrm_" + frmNumber;

        var x = document.forms[frmName]["addname"].value;
        document.forms[msgFrm]["contacterName"].value = x;

        var msgContent = document.forms[msgFrm]["message"].value;
        console.log(document.forms[msgFrm]["contacterName"].value);
        console.log(msgContent);

        if(frmNumber == "createMsg"){
            var phoneNumber = document.forms[frmName]["number"].value;
            document.forms[msgFrm]["number"].value = phoneNumber;
            if(phoneNumber == ""){
                alert("Phone Number must be filled out");
                document.forms[frmName]["number"].focus();
                return false;
            }
        }

        // if (x == "") {
        //     alert("Name must be filled out");
        //     document.forms[frmName]["addname"].focus();
        //     return false;
        // }

        if (msgContent == "") {
            alert("Message content must be filled out");
            document.forms[frmName]["message"].focus();
            return false;
        }
    }

    function toggleContact(x) {
      x.classList.toggle("change");
      $("#contactList").toggleClass("contactHidden", 800, "easeOutQuint");
    }
    
    
    $(document).on('input','.create-msg-input',function(){
        var value = $(this).val();
        var type = $(this).attr('name') == 'number' ? 'get_contact_by_number' : 'get_contact_by_name'; 
        var $this = $(this);
       
        if(value.length > 1) {
            $.ajax({
                url:'/mobile/messages/messages-function.php',
                type:'post',
                data:{value:value,type:type,action:'get_contact_list'},
                success:function(r){
                    console.log(r)
                    if(r.length > 0) {
                        if(r != 'empty') {
                           $this.parent().find('.cr-msg-contact-list').show().html(r);  
                        } else {
                             $this.parent().find('.cr-msg-contact-list').hide()
                        }
                    } else {
                        $this.parent().find('.cr-msg-contact-list').html('No contacts');
                    }
                    
                }
            })  
        } 
        
        else if(value.length == 0) {
           $this.parent().find('.cr-msg-contact-list').hide().empty()
        }
        
    })
    
    
    $(document).on('click','.contatc-list-item',function(){
        var number = $(this).find('.cellnumber').html();
        var name   = $(this).find('.name').html();
        
        $(this).closest('.cr-msg-contact-list').hide()
        $(this).closest('form.name-input').find(".create-msg-input[name='number']").val(number)
        $(this).closest('form.name-input').find(".create-msg-input[name='addname']").val(name)
    })
    
    $(document).on('click','.archive_message',function(){
        var contact_id = $(this).find('button').data('contactid')
        console.log(contact_id)
        $('#archive_message input.contact-id').val(contact_id)
    })    

        
   document.querySelector(".phone-number-input").addEventListener("keypress", function (evt) {
        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57)
        {
            evt.preventDefault();
        }
    });
    
    $(document).on('click','.toggle-sidebar',function(){
        $('.tab-content').toggle();
        $('#message-detail').toggle()
         $('.backforth').animate({ scrollTop: $(document).height()+$(window).height() });
    })
    // $(document).on('click','.open-name-inp',function(){
    //   $(this).parent().find('form').toggle()
    // })
    
    
    
</script>

</body>
</html>
