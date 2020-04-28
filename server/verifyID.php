<?php
	// require '../../pdodbconnect.php';
	require __DIR__ . '/../../pdodbconnect.php';
	$user = 1;//$_COOKIE['userID'];
    $token = 3151463;//$_COOKIE['token'];

	$q=$dbh->query("call verifyUID('.$user.','.$token.');");


	$q->setFetchMode(PDO::FETCH_ASSOC);
	while ($row = $q->fetch())
	{
		$count = (int)$row['outCount'];
	}
	$q->closeCursor();
	$q= null;
	$dbh = null;
