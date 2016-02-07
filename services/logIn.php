<?php
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$email = $request->email;
$password = $request->password;
// setcookie('token', '1925484', time()+(60*60*24*1));
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
