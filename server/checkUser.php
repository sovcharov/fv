<?php
require __DIR__ . '/../../pdodbconnect.php';


try {
  $user = 1;//$_COOKIE['userID'];
    $token = 3151463;//$_COOKIE['token'];

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("select * from users");
      $stmt->execute();

      // set the resulting array to associative
      $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
      foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
          echo $v;
      }

    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
    $dbh = null;



    // require 'verifyID.php';
    // if ($count){
    //     echo 1;
    // } else echo 0;
?>
