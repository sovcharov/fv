<?php
	if(isset($_COOKIE['token']))
	{	
		require '../dbconnect.php';
		$query ="select users.id as id, users.typeID as typeID
			from users, userToken 
			where users.id = '".$_COOKIE['userID']."' 
			and userToken.token='".$_COOKIE['token']."'
			and users.id=userToken.id";
		$qresult = mysqli_query($db, $query);
		$zcount=0;
		while ($row = mysqli_fetch_array($qresult)) 
		{
			$zcount+=1;
			$userType=htmlspecialchars($row['typeID']);
			$cookieUserID=htmlspecialchars($row['id']);
		}	
		require '../dbclose.php';	
		if($zcount==1)
		{	
			if($userType==0) 
				header('Location: /main/');
			else if($userType==1)
				header('Location: /main/');
			else if($userType==2){
				require '../dbconnect.php';
				$query ="
					call addLog($cookieUserID)		
				";
				mysqli_query($db, $query);
				require '../dbclose.php';	
				header('Location: /cakesorder');
			}
			else if($userType==3){
				require '../dbconnect.php';
				$query ="
					call addLog($cookieUserID)		
				";
				mysqli_query($db, $query);
				require '../dbclose.php';	
				header('Location: /cakesorder');
			}
			else if($userType==4){
				if($cookieUserID==9)
					header('Location: /main/');
				else header('Location: /main/');
			}
			else if($userType==5)
				header('Location: /main/');	
			exit();
		}
		else
		{
			setcookie('userName',0, time()-1);
			setcookie('userLastName',0, time()-1);
			setcookie('userID',0, time()-1);
			setcookie('userType',0, time()-1);
			setcookie('token',0, time()-1);
			header('Location: /');
			exit();	
		}
	}
	if(!empty($_POST))
	{
		require '../dbconnect.php';
		$query ="select firstName, lastName, typeID, id from users where email = '".$_POST['userid']."' and password='".SHA1($_POST['password'])."'";
		$qresult = mysqli_query($db, $query);
		$zcount=0;
		while ($row = mysqli_fetch_array($qresult)) {
			$zcount+=1;
			$cookieUserName=htmlspecialchars($row['firstName']);
			$cookieUserLastName=htmlspecialchars($row['lastName']);
			$cookieUserID=htmlspecialchars($row['id']);
			$cookieUserType=htmlspecialchars($row['typeID']);
		}
		$userType=$cookieUserType;
		if($zcount==1)
		{			
			$token=rand(10000,9999999);
			setcookie('userName',$cookieUserName, time()+(60*60*24*1));
			setcookie('userLastName',$cookieUserLastName, time()+(60*60*24*1));
			setcookie('userID',$cookieUserID, time()+(60*60*24*1));
			setcookie('userType',$cookieUserType, time()+(60*60*24*1));			
			setcookie('token',$token, time()+(60*60*24*1));
			$query ="update userToken
				set token= '".$token."'
				where id = '".$cookieUserID."'";
			$qresult = mysqli_query($db, $query);
			require '../dbclose.php';
			if($userType==0) 
				header('Location: /main/');
			else if($userType==1)
				header('Location: /main/');
			else if($userType==2){
				require '../dbconnect.php';
				$query ="
					call addLog($cookieUserID)		
				";
				mysqli_query($db, $query);
				require '../dbclose.php';	
				header('Location: /cakesorder');
			}
			else if($userType==3){
				require '../dbconnect.php';
				$query ="
					call addLog($cookieUserID)		
				";
				mysqli_query($db, $query);
				require '../dbclose.php';	
				header('Location: /cakesorder');
			}
			else if($userType==4){
				if($cookieUserID==9)
					header('Location: /main/');
				else header('Location: /main/');
			}
			else if($userType==5)
				header('Location: /main/');	
			exit();
		}
		require '../dbclose.php';
	}
	if(isset($_COOKIE['userName'])) setcookie('userName',0, time()-1);
	if(isset($_COOKIE['userLastName'])) setcookie('userName',0, time()-1);
	if(isset($_COOKIE['userID'])) setcookie('userID',0, time()-1);
	if(isset($_COOKIE['userType'])) setcookie('userType',0, time()-1);
	if(isset($_COOKIE['token'])) setcookie('token',0, time()-1);
?>

<!DOCTYPE html>
<html>

<head>
<title>ФВ.net</title>
<link rel="stylesheet" type="text/css" href="css/login.css">
<link rel="icon" type="image/ico" href="images/fvlogo.png">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="Sergei Ovcharov">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="js/login.js"></script>



</head>

<body onload="checkEmail(document.getElementById('inputUID'),document.getElementById('errorEmail'))">
<!-- This is a comment -->


	
	<div id="divPageContent">
		<h1 style="margin:10px;color:gray;">Log in page:</h2>
		<form id="formLogin" action="" method="post">
			<div style="height:25px;">
				<p>Email</p>
				<input 	value="<?php if(isset($_POST['userid'])){print htmlspecialchars($_POST['userid']);}?>" 
						style="float:left;" 
						type="text" 
						id="inputUID" 
						name="userid"  
						onkeyup="checkEmail(this,document.getElementById('errorEmail'))" 
						onclick="checkEmail(this,document.getElementById('errorEmail'))" 
						onblur="checkEmail(this,document.getElementById('errorEmail'))"
						onfocus="checkEmail(this,document.getElementById('errorEmail'))"
						/>
				<p id="errorEmail" style="float:left; width:40%;"></p>
				<br/>
			</div>
			<div style="height:25px;clear:both;">
				<p>Password</p>
				<input style="float:left;"  type="password"  id="inputPasswordField" name="password" onkeyup="checkEmail(document.getElementById('inputUID'),document.getElementById('errorEmail'))" onclick="checkEmail(document.getElementById('inputUID'),document.getElementById('errorEmail'))"/>
				<p id="errorPass" style="float:left; width:40%;"></p>
			</div>
			<div id="divSubmitButton2">
			<!--<input type="submit" value="Log in" id="submitButton2" style="margin:5px; margin-left:10px;"/>			-->
			</div>
		</form>	
	</div>

</body>
</html>