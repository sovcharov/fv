<?php
	$postdata = file_get_contents("php://input");
    $request = json_decode($postdata);	
	$user = (int)$request->user;
	$orders = $request->orders;
	require '../../../pdodbconnect.php';
	foreach($orders as $value){
			$q=$dbh->query("call ordersOpen($value,$user);");
	}
	$q->closeCursor();
	$q= null;
	$dbh = null;
?>