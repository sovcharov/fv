<?php

	if(isset($_COOKIE['token']))
	{	
		require '../../../dbconnect.php';
		$query ="select users.id, users.typeID as userTypeID, users.lastName as lastName
			from users, userToken 
			where users.id = '".$_COOKIE['userID']."' 
			and userToken.token='".$_COOKIE['token']."'
			and users.id=userToken.id";
		$qresult = mysqli_query($db, $query);
		$zcount=0;
		while ($row = mysqli_fetch_array($qresult)) 
		{
			$zcount+=1;
			$userTypeID=htmlspecialchars($row['userTypeID']);
			$userLastName=htmlspecialchars($row['lastName']);
		}	
		if($zcount!=1 && $userTypeID!=2)
		{			
			if(isset($_COOKIE['userName'])) setcookie('userName',0, time()-1);
			if(isset($_COOKIE['userID'])) setcookie('userID',0, time()-1);
			if(isset($_COOKIE['token'])) setcookie('token',0, time()-1);
			require '../../../dbclose.php';
			header('Location: ../../');
			exit();	
		}		
				
	}
	else
	{
		if(isset($_COOKIE['userName'])) setcookie('userName',0, time()-1);
		if(isset($_COOKIE['userID'])) setcookie('userID',0, time()-1);
		if(isset($_COOKIE['token'])) setcookie('token',0, time()-1);
		require '../../../dbclose.php';
		header('Location: ../../');
		exit();	
	} 
	$query ="select id, name from inventory
		where groupID<>5
		and groupID<>8
		and name not like '%тестовый%'
		and name not like '%тест%'
		and name not like '%оплата%'
		and name not like '%украшение%'
		order by name
		";
	$qresult = mysqli_query($db, $query);
	//$i=0;
	$json_response=array();
	while ($row2 = mysqli_fetch_array($qresult)) 
	{
		$arrTest['id']= $row2['id'];
		$arrTest['name']= $row2['name'];
		//$i++;
		array_push($json_response,$arrTest);
	}
	require '../../../dbclose.php';
	echo json_encode($json_response);	
?>