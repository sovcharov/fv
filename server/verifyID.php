<?php
	// require '../../pdodbconnect.php';
	require __DIR__ . '/../../pdodbconnect.php';
	$user = 1;//$_COOKIE['userID'];
    $token = 3151463;//$_COOKIE['token'];
		// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "call verifyUID('1', '3151463')";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        echo $row["outCount"];
	    }
	} else {
	    echo "0 results";
	}
	$conn->close();
?>
