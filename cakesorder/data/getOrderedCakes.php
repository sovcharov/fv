<?php
	$postdata = file_get_contents("php://input");
    $request = json_decode($postdata);		
	$id =  $request->id;
	$ordersArray=array();
	require '../../../pdodbconnect.php';
	$q=$dbh->query("call getOrderedCakes($id);");
	$q->setFetchMode(PDO::FETCH_ASSOC);
	$id=0;
	while ($row = $q->fetch())
	{
 		foreach ($row as $key => $value)
		{
			$ordersArray[$id]["$key"] = $value;
		} 
		$id++;
	}	
	$q->closeCursor();
	$q= null;
	$dbh = null;
	$json_response=array();
	array_push($json_response,$ordersArray);
	echo json_encode($json_response);	
?>