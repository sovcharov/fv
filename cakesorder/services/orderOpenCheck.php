<?php
	$postdata = file_get_contents("php://input");
    $request = json_decode($postdata);	
	$order = (int)$request->id;
	$user = (int)$request->user;
	require '../../../pdodbconnect.php';
	$q=$dbh->query("call orderOpenCheck($order, $user);");
	$q->setFetchMode(PDO::FETCH_ASSOC);
	while ($row = $q->fetch())
	{
		$status = (bool)$row['status'];
		$user = $row['firstName'];
		$userLastName = $row['lastName'];
	}	
	$q->closeCursor();
	$q= null;
	$dbh = null;
	$json_response=array();
	array_push($json_response,$status);
	array_push($json_response,$user);
	array_push($json_response,$userLastName);
	echo json_encode($json_response);
?>