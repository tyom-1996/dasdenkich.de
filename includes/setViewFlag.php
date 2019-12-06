<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";
    
    //  echo 'trest';
    
    $is_subuser  = isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? true : false;
    $storeId     = isset($_POST['storeId']) ? $_POST['storeId'] : 0;
    $storeNumber = isset($_POST['storeNumber']) ? $_POST['storeNumber'] : 0;
    $nowTime     = date('Y-m-d H:i:s');
    $storename   = $_SESSION['user-name'];
    $updateQuery = "update messages set viewed='{$nowTime}', xxx_unread_msg_info_sent = '1' where store_id like '{$storeId}' and fromcellnumber like '{$storeNumber}' and sent is null";
    $result      = $mysqli->query($updateQuery);
    
    // Update incharge viewed

    $subuser_id  = $is_subuser ? $_SESSION['sub-user-id'] : 0;
    $msg_inch    = $mysqli->query("SELECT * FROM messages_incharge WHERE store_id = $storeId AND fromcellnumber = '{$storeNumber}'");
    
    if($msg_inch->num_rows > 0) {
        $incharge_query = "UPDATE messages_incharge SET viewed = '1' WHERE store_id = $storeId AND fromcellnumber = '{$storeNumber}' AND status = '1' AND subuser_id = $subuser_id AND viewed = '0'";
    } else {
        $incharge_query = "INSERT INTO messages_incharge (store_id,storename,fromcellnumber,subuser_id,status,incharge_since,viewed) VALUES($storeId,'$storename','$storeNumber',$subuser_id,'1','$nowTime','1')";
    }
   
    $mysqli->query($incharge_query);   
   
    if($mysqli->query($incharge_query)){
        $result['status'] = 'ok' ;
    }
    else{
        $result['status'] = 'error';
    }
 
    echo json_encode($result);
?>