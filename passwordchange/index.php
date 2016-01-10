<?php
	if(isset($_COOKIE['token']))
	{
		require '../../dbconnect.php';
		$query ="select users.id 
			from users, userToken 
			where users.id = '".$_COOKIE['userID']."' 
			and userToken.token='".$_COOKIE['token']."'
			and users.id=userToken.id";
		$qresult = mysqli_query($db, $query);
		$zcount=0;
		while ($row = mysqli_fetch_array($qresult)) 
		{
			$zcount+=1;
		}	
		require '../../dbclose.php';
		if($zcount!=1)
		{			
			if(isset($_COOKIE['userName'])) setcookie('userName',0, time()-1);
			if(isset($_COOKIE['userID'])) setcookie('userID',0, time()-1);
			if(isset($_COOKIE['token'])) setcookie('token',0, time()-1);
			header('Location: ../');
			exit();	
		}
		if(!empty($_POST))
		{
			require '../../dbconnect.php';
			$query ="
				update users
				set password='".SHA1($_POST['password3'])."'
				where id = '".$_COOKIE['userID']."'
			";
			mysqli_query($db, $query);
			require '../../dbclose.php';
			header('Location: ../');
			exit();
		}
	}
	else
	{
		if(isset($_COOKIE['userName'])) setcookie('userName',0, time()-1);
		if(isset($_COOKIE['userID'])) setcookie('userID',0, time()-1);
		if(isset($_COOKIE['token'])) setcookie('token',0, time()-1);
		header('Location: ../');
		exit();	
	}
?>

<!DOCTYPE html>
<html>

<head>
<title>noColor</title>
<link rel="stylesheet" type="text/css" href="css/login.css">
<link rel="icon" type="image/ico" href="../images/fvlogo.jpg">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="Sergei Ovcharov">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="js/login.js"></script>
</head>

<body>
<!-- This is a comment -->
<div id="divContainer"> 
	<div id="divTopBlock">
		<div id="divHeader">
			<div id="divPicHeaderLogo" style="padding:0px;margin:0px;height:40px;width:0px;float:left;">
				<a href="../"><img src="../images/fvlogo.jpg" alt="Seltex Logo" height="40" width="45"></a>
			</div>			
		</div>
		<div style="height:40px; margin:0px; background-color:red;">
		</div>
		<div style="height:10px; margin:0px; background-color:red;border-bottom-left-radius:5px;
		border-bottom-right-radius:5px;">
		</div>
	
	</div>
	<div style="height:60px;">
	</div>

	<div id="divPageContent">
		<h1 style="margin:10px;color:gray;">Смена пароля:</h2>
		<form id="formLogin" action="" method="post">
			<div style="height:25px;clear:both;">
				<p>Новый пароль:</p>
					<input style="float:left;"  type="password"  id="password2" name="password2" 
					onkeyup="checkEmail()" 
					onclick="checkEmail()"/>
				<p id="errorPass2" style="float:left; width:380px;"></p>
			</div>
			<div style="height:25px;clear:both;">
				<p>Подтвердить:</p>
					<input style="float:left;"  type="password"  id="password3" name="password3" 
					onkeyup="checkEmail()" 
					onclick="checkEmail()"/>
				<p id="errorPass3" style="float:left; width:380px;"></p>
			</div>
			<div id="divSubmitButton2">
			</div>
		</form>	
	</div>
	<div style="text-align:center;">
	<b>ВАЖНАЯ ИНФОРМАЦИЯ:</b>
	<br>
	Пароль должем быть не менее 5 символов! 
	Состоять только из букв и цифр!
	<br>
	Мы не храним Ваш пароль в чистом виде! 
	Пароль хранится как результат преобразования односторонней HASH-функцией и не может быть расшифрован обратно!
	Даже мы не можем расшифровать Ваш пароль!
	Так как Вы (и наш сайт) используете HTTPS (защищенное соединение) то никто (даже мы) не может перехватить введенный здесь пароль!
	<br><br>
	Спасибо за то, что вы с нами. 
	<br>
	Команда Ф.Вольчека.
	</div>
</div>
</body>
</html>