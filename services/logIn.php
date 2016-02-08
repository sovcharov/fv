<?php
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$email = $request->email;
$password = $request->password;
// $email = 'smaddrtauto@mail.ru';
// $password = 'junior';
$regexEmail = '/^[A-Za-z0-9]+((([.\-_])[A-Za-z0-9]+)?)*@[A-Za-z0-9]+((([.\-_])[A-Za-z0-9]+)?)*\.[A-Za-z]{2,4}$/';
$regexPassword = '/^[A-Za-z0-9.\-_*$]{5,}$/';
if(preg_match($regexEmail,$email) && preg_match($regexPassword,$password)){
    $password = SHA1($password);
    $token=rand(10000,9999999);
    require '../../pdodbconnect.php';
    $q=$dbh->query("call logIn('$email','$password','.$token.');");
    $q->setFetchMode(PDO::FETCH_ASSOC);
    while ($row = $q->fetch())
    {
        $result["userID"] = (int)$row['userID'];
        $result["userType"] = (int)$row['userType'];
        $result["firstName"] = $row['firstName'];
        $result["lastName"] = $row['lastName'];
        $result["token"] = $token;
    }
    $q->closeCursor();
    $q= null;
    $dbh = null;
    echo json_encode($result);
}
echo false;
?>
