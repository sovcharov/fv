<?php
require __DIR__ . '/../../pdodbconnect.php';


try {
  $user = 1;//$_COOKIE['userID'];
    $token = 3151463;//$_COOKIE['token'];

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare("INSERT INTO MyGuests (firstname, lastname, email) VALUES (:firstname, :lastname, :email)");
    $stmt = $dbh->prepare("Call verifyUID(?, ?)");
    $stmt->bindParam(1, $user);
    $stmt->bindParam(2, $token);
    $stmt->execute();
    $rs = $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo $result[0];


    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>


    // require 'verifyID.php';
    // if ($count){
    //     echo 1;
    // } else echo 0;
?>
