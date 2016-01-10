<?php
	$postdata = file_get_contents("php://input");
    $request = json_decode($postdata);		
	$dateFrom =  $request->dateFrom;
	$dateTo = $request->dateTo;
	require '../../../pdodbconnect.php';
	$user = $_COOKIE['userID'];
	$q=$dbh->query("select users.id, users.userWorkStore as store, users.typeID as type from users where id = $user;");
	$q->setFetchMode(PDO::FETCH_ASSOC);
	while ($row = $q->fetch())
	{
		$userID = (int)$row['id'];
		$userStore = (int)$row['store'];		
		$userType = (int)$row['type'];
	}
	$q->closeCursor();
	$q= null;
	$dbh = null;	
	$ordersArray=array();
	require '../../../pdodbconnect.php';
	$q=$dbh->query("call getOrders('$dateFrom', '$dateTo',$userID,$userStore,$userType);");
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