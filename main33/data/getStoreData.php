<?php
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
	$store =  $request->store;
    $date =  $request->date;
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
    echo $store." ".$date;
?>