<?php
	// require '../../pdodbconnect.php';
	require __DIR__ . '/../../pdodbconnect.php';
	$user = 1;//$_COOKIE['userID'];
    $token = 3151463;//$_COOKIE['token'];
		// Create connection
	$conn = new mysqli($servername, $username, $password);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	echo "Connected successfully";
?>
