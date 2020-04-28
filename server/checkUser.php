<?php
require __DIR__ . '/../../pdodbconnect.php';


try {
  $user = 1;//$_COOKIE['userID'];
    $token = 3151463;//$_COOKIE['token'];

    // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";


    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
    $dbh = null;
?>


    // require 'verifyID.php';
    // if ($count){
    //     echo 1;
    // } else echo 0;
?>
