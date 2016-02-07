<?php
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$email = $request->email;
$password = $request->password;
// require '../../pdodbconnect.php';
//
// $q=$dbh->query("call getUserStores('.$user.');");
// $q->setFetchMode(PDO::FETCH_ASSOC);
// while ($row = $q->fetch())
// {
//
// }
// $q->closeCursor();
// $q= null;
// $dbh = null;
echo json_encode(['hello',$email,$password]);
?>
