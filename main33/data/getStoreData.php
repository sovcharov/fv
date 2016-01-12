<?php
    $postdata = file_get_contents("php://input");
    $id = json_decode($postdata);		
//	$id =  $request->id;
//    require '../../../pdodbconnect.php';
//    $stores = array();        
//    $q=$dbh->query("call getUserStores('.$user.');");
//    $q->setFetchMode(PDO::FETCH_ASSOC);
//    while ($row = $q->fetch())
//    {
//        $stores[$index] = (int)$row['store'];
//        $index++;
//    }
//    $q->closeCursor();
//    $q= null;
//    $dbh = null;
    echo (int)$id;
?>