<?php
	$postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
	$id = (int)$request->id;
	require '../../../pdodbconnect.php';
	$q=$dbh->query("call orderDelete($id);");
	$q->closeCursor();
	$q= null;
	$dbh = null;
?>