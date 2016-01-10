<?php
	$postdata = file_get_contents("php://input");
    $request = json_decode($postdata);	
	$place =  (int)$request->confirmedByBakery;
	$id = (int)$request->id;
	require '../../../pdodbconnect.php';
	$q=$dbh->query("call orderPlaceOrWithdraw($id, $place);");
	$q->setFetchMode(PDO::FETCH_ASSOC);
	while ($row = $q->fetch())
	{
		$place = (int)$row['place'];
	}	
	$q->closeCursor();
	$q= null;
	$dbh = null;
	$json_response=array();
	array_push($json_response,$place);
	echo json_encode($json_response);
?>