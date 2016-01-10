<?php
	$checkUser=false;
	if(isset($_COOKIE['token']) && isset($_COOKIE['token']))
	{
		require '../../../dbconnect.php';
		$query ="select users.id from users, userToken 
			where users.id = '".$_COOKIE['userID']."' 
			and userToken.token='".$_COOKIE['token']."'
			and users.id=userToken.id";
		$qresult = mysqli_query($db, $query);
		$zcount=0;
		while ($row = mysqli_fetch_array($qresult)) 
		{
			$zcount+=1;
		}	
		require '../../../dbclose.php';
		if($zcount==1 && ($_COOKIE['userType']==2 || $_COOKIE['userType']==3 || $_COOKIE['userType']==1 || $_COOKIE['userType']==4) )
		{	
			$checkUser=true;
		}
	}
	$json_response=array();
	array_push($json_response,$checkUser);
	echo json_encode($json_response);
?>