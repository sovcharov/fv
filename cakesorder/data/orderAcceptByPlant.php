<?php
	$postdata = file_get_contents("php://input");
    $request = json_decode($postdata);	
	$place =  (int)$request->acceptedByPlant;
	$user =  (int)$request->user;
	$id = (int)$request->id;
	require '../../../pdodbconnect.php';
	$q=$dbh->query("call orderAcceptOrWithdraw($id, $place,$user);");
	$q->setFetchMode(PDO::FETCH_ASSOC);
	while ($row = $q->fetch())
	{
		$place = (int)$row['place'];
		$baker = (int)$row['baker'];
	}	
	$q->closeCursor();
	$q= null;
	$dbh = null;
	$json_response=array();
	array_push($json_response,$place);
	array_push($json_response,$baker);
	echo json_encode($json_response);
?>