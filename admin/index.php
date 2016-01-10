<?php
	if(isset($_COOKIE['token']))
	{	
		require '../../dbconnect.php';
		$query ="select users.id, users.typeID as userTypeID
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
		}	
		if($zcount!=1 && $userTypeID!=1)
		{			
			if(isset($_COOKIE['userName'])) setcookie('userName',0, time()-1);
			if(isset($_COOKIE['userID'])) setcookie('userID',0, time()-1);
			if(isset($_COOKIE['token'])) setcookie('token',0, time()-1);
			require '../../dbclose.php';
			header('Location: ../');
			exit();	
		}		
		require '../../dbclose.php';		
	}
	else
	{
		if(isset($_COOKIE['userName'])) setcookie('userName',0, time()-1);
		if(isset($_COOKIE['userID'])) setcookie('userID',0, time()-1);
		if(isset($_COOKIE['token'])) setcookie('token',0, time()-1);
		header('Location: ../');
		exit();	
	}
	if(!isset($_GET['do']))
	{
		$daysOffset=0;
		$store=9;
	}
	else 
	{	
		$daysOffset=$_GET['do'];
		$store=$_GET['store'];
	}
?>

<!DOCTYPE html>
<html>

<head>
	<title>ФВ.net</title>
	<link rel="stylesheet" type="text/css" href="css/catalog.css">
	<link rel="icon" type="image/ico" href="../images/fvlogo.jpg">
	<meta name="description" content="Investors page">
	<meta name="keywords" content="Hey ho">
	<meta name="author" content="Sergei Ovcharov">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>
<!-- This is a comment -->
<div id="divContainer"> 
	<div id="divTopBlock">
		<div id="divHeader">
			<div id="divList">
				<ul>
					<li><a href="../passwordchange/">Сменить пароль</a></li>			
				</ul>
			</div>
			<div id="divPicHeaderLogo" style="padding:0px;margin:0px;height:40px;width:0px;float:left;">
				<a href="/"><img src="../images/fvlogo.jpg" alt="Seltex Logo" height="40" width="45"></a>
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

	<div id="divGlobslSPA">	
	<?php
		echo  "<p class=\"paraOverTable\" style=\"margin-left:10px\">Добро пожаловать, " . $_COOKIE['userName']. "</p>";
		require '../../dbconnectms.php';
		echo '<div style="
					width:100%;
					clear:both; 
					border-style:solid; 
					border-width:1px; 
					margin:5px; 
					padding:3px;
					border-radius:5px;
					border-color:gray;
					box-shadow: 0px 0px 15px black;
					">';
		$msquery="
			select sum(c.Summa) as total, count(c.Summa) as checks, convert(date,c.DateOperation) as date,DATEPART(hh,c.DateOperation)as time
			from ChequeHead as c
			where convert(date,c.DateOperation) > CONVERT(date,getdate()-8)
			and c.Cash_Code = ".$store."
			group by convert(date,c.DateOperation),DATEPART(hh,c.DateOperation)
			order by convert(date,c.DateOperation)
			";				
		$msqresult = mssql_query($msquery);
		$myArray = [];
		while ($msrow = mssql_fetch_array($msqresult)) 
		{
			$myArray[$msrow['date']][$msrow['time']][0] = $msrow['total'];
			$myArray[$msrow['date']][$msrow['time']][1] = $msrow['checks'];
		}
		echo '
			<table style="width:;font-size:10px;">
			<tr>
			<th style="width:;">Date</th>
			';
		for($i=7;$i<23;$i++)
		{
			echo '<th style="width:;" colspan="2">'.$i.'</th>';
		}
		echo'
			<th style="width:;" colspan="2">Total</th>
			</tr>';	
		foreach($myArray as $date => $dataArray)
		{
			echo '
				<tr>
				<td style="width:;">'.$date.'</td>
				';	
			$totalForADay=0;
			$totalChecksForADay=0;
			for($i=7;$i<23;$i++)
			{
				echo '<td style="width:;border-left:2px solid gray;">'.$dataArray[$i][0].'</td>';
				echo '<td style="width:;">'.$dataArray[$i][1].'</td>';
				$totalForADay+=$dataArray[$i][0];
				$totalChecksForADay+=$dataArray[$i][1];
			}
			echo'
				<td style="width:;border-left:2px solid gray;">'.$totalForADay.'</td>
				<td style="width:;">'.$totalChecksForADay.'</td>
				</tr>';
		}
		//print_r ($myArray);
		echo '</table>
		</div>';
		require '../../dbclosems.php';	
		///////////////////////////////////////////////////////
		///////////////////////////////////////////////////////
		///
		///
		///////////////////////////////////////////////////////
		///////////////////////////////////////////////////////		
		require '../../dbconnect.php';
		// $query ="
			// select users.firstName as firstName, users.lastName as lastName, log.date as date
			// from users, log
			// where users.id=log.userID
			// and date(log.date)>date(now())-2
			// order by log.date desc;
			// ";
		$query ="
			select max(log.date) as date, count(log.date) as qty, users.firstName as firstName, users.lastName as lastName from users, log
			where log.userID = users.id
			group by log.userID
			order by date desc;
			";
		$qresult = mysqli_query($db, $query);		
		echo '
			<div style="width:320px;
				float:left;							
				border-style:solid; 
				border-width:1px; 
				margin:5px; 
				padding:3px;
				border-radius:5px;
				border-color:gray;
				box-shadow: 0px 0px 15px black;
				">
			<table style="width:;">
			<tr>
			<th style="width:50px;">Имя</th>
			<th style="width:50px;">Фамилия</th>
			<th style="width:100px;">Дата</th>
			<th style="width:30px;">Кол</th>
			</tr>';	
		$qtyVisits=0;
		while ($row = mysqli_fetch_array($qresult)) 
		{	
			echo '<tr>
				<td>',htmlspecialchars($row['firstName']),'</td>
				<td>',htmlspecialchars($row['lastName']),'</td>
				<td style="text-align:right;">',htmlspecialchars($row['date']),'</td>	
				<td style="text-align:right;">',$row['qty'],'</td>
				</tr>';			
			$qtyVisits+=$row['qty'];
		}	
		echo '</table>
			<span>Total visits: '.$qtyVisits.'</span>
			</div>';
		require '../../dbclose.php';
		?>
		<form action="" method="GET">
			Days offset
			<input type="number" name="do" min="0" max="10" value="<?php echo $daysOffset?>">
			Store
			<input type="number" name="store" min="1" max="10" value="<?php echo $store?>">
			<input type="submit">
		</form>
		<?php
		/////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////
		////
		////
		/////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////		
		require '../../dbconnectms.php';
		echo '<div style="
					width:600px;
					float:left; 
					border-style:solid; 
					border-width:1px; 
					margin:5px; 
					padding:3px;
					border-radius:5px;
					border-color:gray;
					box-shadow: 0px 0px 15px black;
					">';
		$msquery="
			select c.Cash_Code as Store, c2.chequeID, 
			c.Ck_Number as Ch,c.Summa as Total, c.Disc_Sum as discount,
			g.Code as code, g.goodsName as gName, 
			c2.Quant as qty, c2.Price as price, c2.Summa as Sum,  
			convert(date,c.DateOperation) as date,convert(char(8),c.DateOperation,108)as time
			from Goods4 as g,ChequeHead as c,ChequePos as c2
			where g.Code=c2.Code 
			and c2.ChequeId=c.Id
			and convert(date,c.DateOperation) = CONVERT(date,getdate()-".$daysOffset.")
			--and convert(date,c.DateOperation) > CONVERT(date,getdate()-1)
			--AND DATEPART(hh,c.DateOperation) >= 19 
			--AND DATEPART(hh,c.DateOperation) < 23 
			--AND DATEPART(mi,c.DateOperation) >= 8 
			--AND DATEPART(mi,c.DateOperation) < 30 
			and c.Cash_Code = ".$store."
			order by c2.ChequeId desc
			";	
			
		$msqresult = mssql_query($msquery);
		echo '
			<table style="width:590px;">
			<tr>
			<th style="width:;">#</th>
			<th style="width:;">Date</th>
			<th style="width:;">Time</th>
			<th style="width:;">Total</th>
			<th style="width:;">Disc</th>
			<th style="">Name</th>
			<th style="font-size:8px;width:35px;">Qty</th>
			<th style="font-size:8px;width:35px;">Price</th>
			<th style="font-size:8px;width:35px;">Sum</th>
			</tr>';	
		$total = 0;
		$totalChecks = 0;
		$ch=0;
		while ($msrow = mssql_fetch_array($msqresult)) 
		{
			
			if($msrow['Ch']==$ch)
			{
				echo '<tr>';
				echo '
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				';
			}				
			else
			{	
				echo '<tr style="border-top:3px solid gray;">';
				echo'
					<td>',htmlspecialchars($msrow['Ch']),'</td>
					<td style="text-align:right; width:60px;">',htmlspecialchars($msrow['date']),'</td>
					<td style="text-align:right;">',htmlspecialchars($msrow['time']),'</td>
					<td style="text-align:right;">',htmlspecialchars($msrow['Total']),'</td>
					<td style="text-align:right;">',htmlspecialchars($msrow['discount']),'</td>
				';
				$ch=$msrow['Ch'];
				$totalChecks += 1;
			}
			echo '
					<td style="text-align:right;">',($msrow['gName']),'</td>
					<td style="text-align:right;">',htmlspecialchars($msrow['qty']),'</td>					
					<td style="text-align:right;">',htmlspecialchars($msrow['price']),'</td>	
					<td style="text-align:right;">',htmlspecialchars($msrow['Sum']),'</td>	
				</tr>';
			$total = $total + htmlspecialchars($msrow['Sum']);
		}
		echo '</table>
		</div>';
		require '../../dbclosems.php';	
	?>
	</div>
</div>
</body>
</html>