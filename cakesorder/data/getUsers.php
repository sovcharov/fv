<?php
	require '../../../pdodbconnect.php';
	$user = $_COOKIE['userID'];
	$q=$dbh->query("select users.id, users.firstName, users.lastName from users 
		where users.typeID = 2 
		or users.typeID = 3
		or users.typeID = 1
		or users.typeID = 4
		;");
	$q->setFetchMode(PDO::FETCH_ASSOC);
	$index=0;
	while ($row = $q->fetch())
	{
		$place[$index]['id'] = (int)$row['id'];
		$place[$index]['firstName'] = $row['firstName'];
		$place[$index]['lastName'] = $row['lastName'];
		$index++;
	}
	$q->closeCursor();
	$q= null;
	$dbh = null;
	$json_response=array();
	echo json_encode($place);
?>