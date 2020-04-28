<?php
require __DIR__ . '/../../pdodbconnect.php';


try {
    // $dbh = new PDO("mysql:host=$servername;dbname=myDB", $username, $password);
    // set the PDO error mode to exception
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
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
