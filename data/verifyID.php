<?php
	require '../../pdodbconnect.php';
	$user = $_COOKIE['userID'];
    $token = $_COOKIE['token'];
	$q=$dbh->query("call verifyUID('.$user.','.$token.');");
	$q->setFetchMode(PDO::FETCH_ASSOC);
	while ($row = $q->fetch())
	{
		$count = (int)$row['outCount'];
	}
	$q->closeCursor();
	$q= null;
	$dbh = null;
