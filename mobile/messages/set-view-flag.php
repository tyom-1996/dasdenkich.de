<?php
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";
    
    $is_subuser  = isset($_SESSION['user-status']) && $_SESSION['user-status'] == 'sub_user' ? true : false;
    $storeId     = isset($_POST['storeId']) ? $_POST['storeId'] : 0;
    $storeNumber = isset($_POST['storeNumber']) ? $_POST['storeNumber'] : 0;
    $nowTime     = date('Y-m-d H:i:s');
    $updateQuery = "update messages set viewed='{$nowTime}' where store_id like '{$storeId}' and fromcellnumber like '{$storeNumber}' and sent is null";
    $result      = $mysqli->query($updateQuery);
    
    // Update incharge viewed
   
    $subuser_id     = $is_subuser ? $_SESSION['sub-user-id'] : 0;
    $incharge_query = "UPDATE messages_incharge SET viewed = '1' WHERE store_id like '{$_SESSION['user-id']}' AND fromcellnumber like '{$storeNumber}' AND status = '1' AND subuser_id = $subuser_id AND viewed = '0'";
   
    $mysqli->query($incharge_query);   
    

    if($mysqli->query($updateQuery)){
        $result['status'] = 'ok' ;
    }
    else{
        $result['status'] = 'error';
    }
 
    echo json_encode($result);
?>