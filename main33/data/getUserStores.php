<?php
    require 'verifyID.php';
    if ($count){
        require '../../../pdodbconnect.php';
        $index = 0;
        $stores = array();        
        $q=$dbh->query("call getUserStores('.$user.');");
        $q->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $q->fetch())
        {
            $stores[$index] = (int)$row['store'];
            $index++;
        }
        $q->closeCursor();
        $q= null;
        $dbh = null;
        echo json_encode($stores);
    }else{
        echo "wrong";
    }
?>