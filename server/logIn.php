<?php
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
// $email = $request->email;
// $password2 = $request->password;
require __DIR__ . '/../../pdodbconnect.php';


$regexEmail = '/^[A-Za-z0-9]+((([.\-_])[A-Za-z0-9]+)?)*@[A-Za-z0-9]+((([.\-_])[A-Za-z0-9]+)?)*\.[A-Za-z]{2,4}$/';
$regexPassword = '/^[A-Za-z0-9.\-_*$]{5,}$/';
if(preg_match($regexEmail,$email) && preg_match($regexPassword,$password2)){
  require __DIR__ . '/../../pdodbconnect.php';
    $password2 = SHA1($password);
    $token = rand(10000,9999999);

    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");

  	// Check connection
  	if ($conn->connect_error) {
  	    die("Connection failed: " . $conn->connect_error);
  	}
  	$sql = "call logIn('$email','$password2',$token)";
  	$result = $conn->query($sql);
  	if ($result->num_rows > 0) {
  	    // output data of each row
  	    while($row = $result->fetch_assoc()) {
          $res["userID"] = (int)$row['userID'];
          $res["userType"] = (int)$row['typeID'];
          $res["firstName"] = $row['firstName'];
          $res["lastName"] = $row['lastName'];
          $res["token"] = $token;
  	    }
        if ($res) echo json_encode($res);
        else echo false;
  	} else {
  		echo false;
  		// echo "0 results";
  	}
  	$conn->close();
} else echo false;
?>
