<?php
	if(isset($_COOKIE['userName'])) setcookie('userName',0, time()-1, "/");
	if(isset($_COOKIE['userLastName'])) setcookie('userName',0, time()-1, "/");
	if(isset($_COOKIE['userID'])) setcookie('userID',0, time()-1, "/");
	if(isset($_COOKIE['userType'])) setcookie('userType',0, time()-1, "/");
	if(isset($_COOKIE['token'])) setcookie('token',0, time()-1, "/");
	header('Location: ../');
	exit();
?>