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
		if($zcount!=1)
		{			
			if(isset($_COOKIE['userName'])) setcookie('userName',0, time()-1);
			if(isset($_COOKIE['userID'])) setcookie('userID',0, time()-1);
			if(isset($_COOKIE['token'])) setcookie('token',0, time()-1);
			require '../../dbclose.php';
			header('Location: ../');
			exit();	
		}		
		$user=(int)$_COOKIE['userID'];
		$query ="
			call addLog($user)		
		";
		mysqli_query($db, $query);
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
?>

<!DOCTYPE html>
<html>

<head>
	<title>noColor</title>
	<link rel="stylesheet" type="text/css" href="css/catalog.css">
	<link rel="icon" type="image/ico" href="images/seltexlogo.png">
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
				<a href=""><img src="images/seltexlogo.png" alt="Seltex Logo" height="40" width="51"></a>
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
		require '../../dbconnect.php';
		$query ="
			select distinct stores.id storeID, stores.name as storeName 
			from stores, userStores, users 
			where stores.id=userStores.storeID
			and  users.id=userStores.userID
			and users.id='".$_COOKIE['userID']."'
			";
		$qresult = mysqli_query($db, $query);		
		$i=0;
		while ($row = mysqli_fetch_array($qresult)) 
		{	
			$mass[$i][0]=$row['storeID'];
			$mass[$i][1]=$row['storeName'];
			$i++;			
		}	
		require '../../dbclose.php';
		require '../../dbconnectms.php';
		for($j=0; $j<$i; $j++)
		{
				echo '<div style="
							width:98%;
							height:290px;
							border-style:solid; 
							border-width:1px; 
							margin:5px; 
							margin-bottom:10px;
							padding:3px;
							border-radius:5px;
							border-color:gray;
							box-shadow: 0px 0px 15px black; 
							">';
				echo '<div style="
					float:left;
					"
				>';//div First Column
				echo '<p style="clear:both; margin-left:5px;">Булочная № '.$mass[$j][0].' '.$mass[$j][1].'</p>';
				echo '<div id="d123"
							style="
							width:225px;
							height:235px;
							
							border-style:solid; 
							border-width:1px; 
							margin:5px; 
							padding:3px;
							border-radius:5px;
							border-color:gray;
							box-shadow: 0px 0px 15px black; 
							">';
				
				$msquery="
					select convert(date,GangDateStart) as date,CashNumber as number,Summa as cash, ChequeCountSaleCash as checks,
					temp.avCash as averageCash, temp.avChecks as averageChecks, temp.sumCash as salesAll,
					temp2.sumCashThisMonth  as salesTM, temp3.sumCashLastMonth as salesLM
					from OperGang,
					(select OperGang.CashNumber as avID, AVG(OperGang.Summa) as avCash, 
						AVG(OperGang.ChequeCountSaleCash) as avChecks, SUM(Summa) as sumCash
						from OperGang 
						where CashNumber = '".$mass[$j][0]."'
						group by OperGang.CashNumber) as temp,
					(select OperGang.CashNumber as avID2, SUM(Summa) as sumCashThisMonth
						from OperGang 
						where CashNumber = '".$mass[$j][0]."'	
						and YEAR(GangDateStart) = YEAR(getdate())
						and month(GangDateStart) = month(getdate())
						group by OperGang.CashNumber) as temp2,
					(select OperGang.CashNumber as avID3, SUM(Summa) as sumCashLastMonth
						from OperGang 
						where CashNumber = '".$mass[$j][0]."'	
						and YEAR(GangDateStart) = YEAR(getdate())
						and month(GangDateStart) = month(getdate())-1
						group by OperGang.CashNumber) as temp3	
					where convert(date,GangDateStart) > CONVERT(date,getdate()-8)	
					and temp.avID=OperGang.CashNumber
					and temp2.avID2=OperGang.CashNumber
					and temp3.avID3=OperGang.CashNumber
					and cashNumber = '".$mass[$j][0]."'
					";
				$msqresult = mssql_query($msquery);
				echo '
					<table style="width:225px;">
					<tr>
					<th style="width:80px;">Дата</th>
					<th style="width:80px;">Сумма</th>
					<th style="width:70px;">Чеков</th>
					</tr>';	
				$countRow=0;
				$totalAmount=0;
				$totalChecks=0;
				while ($msrow = mssql_fetch_array($msqresult)) 
				{
					
					$x = number_format(htmlspecialchars($msrow['cash']),2);
					$x2= htmlspecialchars($msrow['checks']);
					if($countRow==0)
					{
						$totalAmountAll = number_format(htmlspecialchars($msrow['averageCash']),2);
						$totalChecksAll = number_format(htmlspecialchars($msrow['averageChecks']),0);
						$salesThisMonth = number_format(htmlspecialchars($msrow['salesTM']),2);
						$salesLastMonth = number_format(htmlspecialchars($msrow['salesLM']),2);
						$salesAll = number_format(htmlspecialchars($msrow['salesAll']),2);
					}
					if($countRow==7)
					{
						echo '<tr style="background-color:#B8FFDB;">';
					}
					else
					{
						echo '<tr>';
						$totalAmount+=$msrow['cash'];
						$totalChecks+=$msrow['checks'];
					}
					echo '
							<td>',htmlspecialchars($msrow['date']),'</td>
							<td style="text-align:right;">',$x,'</td>
							<td style="text-align:right;">',$x2,'</td>					
						</tr>';
					$countRow++;	
				}
				echo '</table>';
				echo '</div>';
				echo '</div>';
				//additional information
				echo '<div style="font-size:15px;
							width:225px;
							height:270px;
							float:left;
							border-style:solid; 
							border-width:1px; 
							margin:5px; 
							padding:3px;
							border-radius:5px;
							border-color:gray;
							box-shadow: 0px 0px 15px black;
							"
				>';
				echo '<span style="font-weight:bold;">';
				echo 'Продажи: <br>';
				echo '</span>';
				echo 'В этом месяце: '. $salesThisMonth . '<br>';
				echo 'В прошлом месяце: '. $salesLastMonth . '<br>';
				echo 'За всё время работы: '. $salesAll . '<br>';
				echo '<span style="font-weight:bold;">';
				echo 'Средние величины: <br>';
				echo '</span>';
				echo ' За последние 7 дней: <br>';
				echo '  Продажи: '. number_format($totalAmount/7,2) . '<br>';
				echo '  Чеков: '. number_format($totalChecks/7,0) . '<br>';
				echo ' За всё время работы точки: <br>';
				echo '  Продажи: '. $totalAmountAll . '<br>';
				echo '  Чеков: '. $totalChecksAll . '<br>';
				echo '</div>';
				//end of additional information
				//canvas start
				echo '<div style="
							font-size:15px;
							width:460px;
							height:270px;
							float:left;
							border-style:solid; 
							border-width:1px; 
							margin:5px; 
							padding:3px;
							border-radius:5px;
							border-color:gray;
							box-shadow: 0px 0px 15px black;
							"
				>';
				$msquery="
					select Summa as summ, ChequeCountSaleCash as checks 
					from OperGang 
					where convert(date,GangDateStart) between CONVERT(date,getdate()-60) and CONVERT(date,getdate()-1)
					and cashNumber = '".$mass[$j][0]."'
					";
				$msqresult = mssql_query($msquery);
				echo '
					<canvas id="myCanvas'.$mass[$j][0].'" width="450" height="260" style="border:1px solid #c3c3c3;">
					Your browser does not support the HTML5 canvas tag.
					</canvas>
					';	
				$countRow=0;
				$totalAmount=0;
				$totalChecks=0;
				$xx=0;
				$yy=0;
				echo '

					<script>

					var c = document.getElementById("myCanvas'.$mass[$j][0].'");
					var ctx = c.getContext("2d");					
					ctx.lineJoin=\'round\';	
					ctx.strokeStyle="gray";
					ctx.lineWidth=1;
					';
				for($iii=0;$iii<61;$iii++)
				{
					if(($iii%10)==0)
					{
						echo
						'
							ctx.beginPath(); 
							ctx.moveTo('.$iii*(7.5).',0);
							ctx.lineTo('.$iii*(7.5).',260);
							ctx.stroke();
						';	
					}
				}
				for($iii=0;$iii<61;$iii++)
				{
					if(($iii%10)==0)
					{
						echo
						'
							//ctx.font="10px Georgia";
							ctx.lineWidth=1;
							ctx.strokeStyle="black";
							ctx.fillText("'.-(60-$iii).'",'.$iii*(7.5).',255);
						';	
					}
				}
				echo
						'
							//ctx.font="10px Georgia";
							ctx.lineWidth=1;
							ctx.strokeStyle="black";
							ctx.fillText("Дней",420,255);
						';
				$total1=0;
				$total2=0;	
				while ($msrow = mssql_fetch_array($msqresult)) 
				{					
					$yy=$msrow['checks'];
					$zz=$msrow['summ'];
					$array1[$countRow]=(int)$yy;
					$array2[$countRow]=(double)$zz;
					$countRow+=1;
					$total1+=(int)$yy;
					$total2+=(double)$zz;
				}
				$avg1=$total1/$countRow;
				$avg2=$total2/$countRow;
				$max = max($array1);
				echo '
						ctx.beginPath(); 
						ctx.strokeStyle="gray";
						ctx.lineWidth=1;
						ctx.moveTo(0,260-'.(130)*$avg1/($max).');
						ctx.lineTo(450,260-'.(130)*$avg1/($max).');
						ctx.stroke();
						ctx.lineWidth=1;
						ctx.strokeStyle="black";
						ctx.fillText("'.(int)$avg1.'",3,255-'.(130)*$avg1/($max).');
						ctx.beginPath(); 
						ctx.strokeStyle="#0066FF";
						ctx.lineWidth=3;
					';				
				for($iii=0;$iii<61;$iii++)
				{
					echo '
						ctx.lineTo('.$xx.',260-'.(130)*$array1[$iii]/($max).');
					';	
					$xx=$xx+7.5;
				}
				$max = max($array2);
				
				echo '
					ctx.stroke();
					ctx.beginPath(); 
					ctx.strokeStyle="gray";
					ctx.lineWidth=1;
				';
				echo '
						ctx.moveTo(0,260-'.(250)*$avg2/($max).');
						ctx.lineTo(450,260-'.(250)*$avg2/($max).');
						ctx.stroke();
						ctx.lineWidth=1;
						ctx.strokeStyle="black";
						ctx.fillText("'.(int)$avg2.'",3,255-'.(250)*$avg2/($max).');
						ctx.beginPath();
						ctx.strokeStyle="#FF0000";
						ctx.lineWidth=3;
						ctx.moveTo(-10,-1);						
					';
				$xx=0;
				
				for($iii=0;$iii<61;$iii++)
				{
					echo '
						ctx.lineTo('.$xx.',260-'.(250)*$array2[$iii]/($max).');
					';		
					$xx=$xx+7.5;
				}
				echo '
					ctx.stroke();
					ctx.font="16px Georgia";
					ctx.lineWidth=1;
					ctx.strokeStyle="#0066FF";
					ctx.strokeText("Чеки",10,250);
					ctx.strokeStyle="#FF0000";
					ctx.strokeText("Суммы",10,25);
					</script>
				';					

				echo '</div>';
				//canvas end
				echo '</div>';
		}
		require '../../dbclosems.php';	
	?>
	<p style="clear:both; padding-top:50px;">Cпасибо за то, что вы с нами! <br> Филипп Вольчек.</p>
	</div>
</div>
</body>
</html>