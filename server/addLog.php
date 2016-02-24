<?php
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $action =  $request->action;
	$user = $_COOKIE['userID'];
	require '../../pdodbconnect.php';
	$q=$dbh->query("call addLog('.$user.','$action');");
	$q->closeCursor();
	$q= null;
	$dbh = null;
?>
