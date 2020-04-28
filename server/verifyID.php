<?php
	// require '../../pdodbconnect.php';
	require __DIR__ . '/../../pdodbconnect.php';
	$user = $_COOKIE['userID'];
  $token = $_COOKIE['token'];

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "call verifyUID($user, $token)";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $count = $row["outCount"];
	    }
	} else {
		$count = 0;
		// echo "0 results";
	}
	$conn->close();
