<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/connect.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";

if(isset($_POST['updateFlag']) && $_POST['updateFlag'] == 1){
    $query = 'UPDATE messages_contacts SET name="'.$_POST['addname']
        .'" WHERE storeid like "'.$_SESSION['user-id'].'" and  storename like "'.$_SESSION['user-name'].'" and cellnumber like "'.$_POST['number'].'"';
}
else{
    $preQuery = 'select * from messages_contacts 
                WHERE storeid like "'.$_SESSION['user-id'].'" and  
                    storename like "'.$_SESSION['user-name'].'" and 
                    cellnumber like "'.$_POST['number'].'"';
    $preResult = $mysqli->query($preQuery);  
    $preNum    = $preResult->num_rows;
    
    if($preNum >= 1){
        $query = 'UPDATE messages_contacts SET name="'.$_POST['addname']
        .'" WHERE storeid like "'.$_SESSION['user-id'].'" and  storename like "'.$_SESSION['user-name'].'" and cellnumber like "'.$_POST['number'].'"';
    }
    else{
        $query = 'INSERT INTO messages_contacts '
        	   . '(storeid, storename, cellnumber, name) '
        	   . 'VALUES '
        	   .'('.$_SESSION['user-id'].',"'.$_SESSION['user-name'].'", '.$_POST['number'].', "'.$_POST['addname'].'")';
        
    }
    
}

// echo $query;
$result = $mysqli->query($query);

if($result){
	header("location: /mobile/messages/?msg=addname#detail-".$_POST['number']);
} else {
	header("location: /mobile/messages/?msg=addnameerror#detail-".$_POST['number']);
}

?>
