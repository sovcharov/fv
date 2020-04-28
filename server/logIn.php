<?php
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$email = $request->email;
$password = $request->password;
$regexEmail = '/^[A-Za-z0-9]+((([.\-_])[A-Za-z0-9]+)?)*@[A-Za-z0-9]+((([.\-_])[A-Za-z0-9]+)?)*\.[A-Za-z]{2,4}$/';
$regexPassword = '/^[A-Za-z0-9.\-_*$]{5,}$/';
if(preg_match($regexEmail,$email) && preg_match($regexPassword,$password)){
    $password = SHA1($password);
    $token = rand(10000,9999999);
    require '../../pdodbconnect.php';


    $conn = new mysqli($servername, $username, $password, $dbname);

  	// Check connection
  	if ($conn->connect_error) {
  	    die("Connection failed: " . $conn->connect_error);
  	}
  	$sql = "call logIn('$email','$password','.$token.');";
  	$result = $conn->query($sql);
  	if ($result->num_rows > 0) {
  	    // output data of each row
  	    while($row = $result->fetch_assoc()) {
          $result["userID"] = (int)$row['userID'];
          $result["userType"] = (int)$row['typeID'];
          $result["firstName"] = $row['firstName'];
          $result["lastName"] = $row['lastName'];
          $result["token"] = $token;
  	    }
  	} else {
  		$count = 0;
  		// echo "0 results";
  	}
  	$conn->close();
    // 
    //
    //
    // $q=$dbh->query("call logIn('$email','$password','.$token.');");
    // $q->setFetchMode(PDO::FETCH_ASSOC);
    // while ($row = $q->fetch())
    // {
    //     $result["userID"] = (int)$row['userID'];
    //     $result["userType"] = (int)$row['typeID'];
    //     $result["firstName"] = $row['firstName'];
    //     $result["lastName"] = $row['lastName'];
    //     $result["token"] = $token;
    // }
    // $q->closeCursor();
    // $q= null;
    // $dbh = null;
    if ($result) echo json_encode($result);
    else echo false;
} else echo false;
?>
