<?php
	require '../../../pdodbconnect.php';
	$user = $_COOKIE['userID'];
	$q=$dbh->query("select users.id, users.firstName, users.lastName, users.userWorkStore as store, users.typeID as type from users where id = $user;");
	$q->setFetchMode(PDO::FETCH_ASSOC);
	while ($row = $q->fetch())
	{
		$place['id'] = (int)$row['id'];
		$place['firstName'] = $row['firstName'];
		$place['lastName'] = $row['lastName'];
		$place['store'] = (int)$row['store'];		
		$place['type'] = (int)$row['type'];
	}
	$q->closeCursor();
	$q= null;
	$dbh = null;
	$json_response=array();
	//array_push($json_response,$place);
	echo json_encode($place);
?>