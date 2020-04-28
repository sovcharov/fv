<?php
// $postdata = file_get_contents("php://input");
// $request = json_decode($postdata);
// $email = $request->email;
// $password = $request->password;
require __DIR__ . '/../../pdodbconnect.php';


$regexEmail = '/^[A-Za-z0-9]+((([.\-_])[A-Za-z0-9]+)?)*@[A-Za-z0-9]+((([.\-_])[A-Za-z0-9]+)?)*\.[A-Za-z]{2,4}$/';
$regexPassword = '/^[A-Za-z0-9.\-_*$]{5,}$/';
if(preg_match($regexEmail,$email) && preg_match($regexPassword,$password2)){
  // require __DIR__ . '/../../pdodbconnect.php';

    echo $password2;
    echo $email;
    $password2 = SHA1($password2);
    $token = rand(10000,9999999);
    echo $password2;
    echo $email;
    echo $token;

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
        echo "ey";
  	    while($row = $result->fetch_assoc()) {
          // var_dump($row);
          $res["userID"] = (int)$row['userID'];
          $res["userType"] = (int)$row['typeID'];
          $res["firstName"] = $row['firstName'];
          $res["lastName"] = $row['lastName'];
          $res["token"] = $token;
  	    }
        var_dump($res);
        echo json_encode($res);
  	} else {
  		echo false;
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
    echo $res;
    if ($res) echo json_encode($res);
    else echo false;
} else echo false;
?>
