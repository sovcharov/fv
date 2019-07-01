<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

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
		if(isset($_COOKIE['userLastName'])) setcookie('userName',0, time()-1);
		if(isset($_COOKIE['userID'])) setcookie('userID',0, time()-1);
		if(isset($_COOKIE['userType'])) setcookie('userType',0, time()-1);
		if(isset($_COOKIE['token'])) setcookie('token',0, time()-1);
		require '../../dbclose.php';
		header('Location: ../');
		exit();
	}
	$user=(int)$_COOKIE['userID'];
	$query ="
	call addLog($user,'KV')
	";
	mysqli_query($db, $query);
	// require '../../dbclose.php';
}
else
{
	if(isset($_COOKIE['userName'])) setcookie('userName',0, time()-1);
	if(isset($_COOKIE['userLastName'])) setcookie('userName',0, time()-1);
	if(isset($_COOKIE['userID'])) setcookie('userID',0, time()-1);
	if(isset($_COOKIE['userType'])) setcookie('userType',0, time()-1);
	if(isset($_COOKIE['token'])) setcookie('token',0, time()-1);
	header('Location: ../');
	exit();
}
?>
<!DOCTYPE html>
<html ng-app="InvestorPanel">
<head>
	<title>КВ</title>
	<link rel="stylesheet" type="text/css" href="css/catalog.css">
	<link rel="icon" type="image/ico" href="../images/fvlogo.png">
	<meta name="description" content="Investors page">
	<meta name="keywords" content="Hey ho">
	<meta name="author" content="Sergei Ovcharov">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="https://fonts.googleapis.com/css?family=Underdog&subset=latin,cyrillic" rel="stylesheet" type="text/css">
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular.min.js"></script>
	<script src="controllers/MainController.js"></script>
</head>
<body ng-controller="MainController" ng-cloak style="background:#292d34;color:#e3e3e3;">
	<nav class="navbar navbar-default navbar-inverse">
		<!-- <a class="navbar-brand" href="/"><img alt="Brand" height="30" width="34" src="../images/fvlogo.png"></a> -->
		<a class="navbar-brand" href="/">кофеварим</a>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav pull-right">
				<li>
					<canvas id="myCanvas" width="48" height="48" style="margin:0px; padding:0px;">
					</canvas>
					<script type="text/javascript" src="js/mySpin.js"></script>
				</li>
				<?php
				// if($_COOKIE['userID']==1 || $_COOKIE['userType']==1 || $_COOKIE['userID']==20) echo '<li><a href="/cakesorder/">orders</a></li>';
				// if($_COOKIE['userID']==1) echo '<li><a href="../admin">admin</a></li>';
				?>
				<!-- <li><a href="../passwordchange/">Сменить Пароль</a></li> -->
				<li class=""><a href="exitapp.php">Выход</a></li>
			</ul>
		</div>
	</nav>
	<div id="divContainer">
		<?php
		$daysInMonths = array(0,29,28,31,30,31,30,31,31,30,31,30,31);//january has 29 days since 1st - 2nd store closed
		if ((int)date('y')%4 == 0) $daysInMonths[2] += 1;
		$daysThisMonth = $daysInMonths[(int)date('m')];
		if ((int)date('m') == 0) {
			$daysLastMonth = $daysInMonths[12];
			$days2MonthAgo = $daysInMonths[11];
		} else if ((int)date('m') == 1) {
			$daysLastMonth = $daysInMonths[1];
			$days2MonthAgo = $daysInMonths[12];
		} else {
			$daysLastMonth = $daysInMonths[(int)date('m')-1];
			$days2MonthAgo = $daysInMonths[(int)date('m')-2];
		}
		$today = (int) date('d');
		if ((int)date('H')<4) $today--; //
		// echo $today;
		echo  "<p class=\"paraOverTable\" style=\"margin-left:10px;\">Добро пожаловать, " . $_COOKIE['userName']. "!</p>";
		// require '../../dbconnect.php';
		if($_COOKIE['userType']==4 || $_COOKIE['userID']==1  || $_COOKIE['userType']==5){
			$query ="
			select distinct stores.id storeID, stores.bakery as bakery, stores.name as storeName, stores.venue as venue
			from storeskv as stores
			where bakery < 1000
			order by stores.bakery
			";
		}
		else{
			$query ="
			select distinct stores.id storeID, stores.bakery as bakery, stores.name as storeName, stores.venue as venue
			from storeskv as stores, userStores, users
			where stores.bakery=userStores.storeID
			and  users.id=userStores.userID
			and users.id='".$_COOKIE['userID']."'
			order by stores.bakery limit 0;
			";
		}
		$qresult = mysqli_query($db, $query);
		$i=0;
		$msqueryStores = '';

		while ($row = mysqli_fetch_array($qresult))
		{
			if ($i==0) {
				$msqueryStores = $msqueryStores . " (t1.IPRINTSTATION = ".$row['storeID'];
			} else {
				$msqueryStores = $msqueryStores . " or t1.IPRINTSTATION = ".$row['storeID']." ";
			}
			$mass[$i][0]=(int) $row['storeID'];
			$mass[$i][1]=$row['storeName'];
			$mass[$i][2]=(string) $row['venue'];
			$mass[$i][3]=(int) $row['bakery'];
			$mass[$i][4]=[];
			$mass[$i][5]=[];

			$mass[$i][4][0]=[];
			$mass[$i][4][0]["totalCash"] =0;
			$mass[$i][4][0]["totalChecks"] =0;
			$mass[$i][4][1]=[];
			$mass[$i][4][1]["totalCash"] =0;
			$mass[$i][4][1]["totalChecks"] =0;
			$mass[$i][4][2]=[];
			$mass[$i][4][2]["totalCash"] =0;
			$mass[$i][4][2]["totalChecks"] =0;

			$mass[$i][5][0]=[];
			$mass[$i][5][1]=[];
			$mass[$i][5][2]=[];
			$mass[$i][5][3]=[];
			$mass[$i][5][4]=[];
			$mass[$i][5][5]=[];
			$mass[$i][5][6]=[];

			//echo $mass[$i][0];
			$i++;
		}
		$msqueryStores = $msqueryStores . ")";

		$mass[$i][0]=0;
		$mass[$i][1]="СУММАРНАЯ";
		$mass[$i][2]="";
		$mass[$i][3]="";
		$mass[$i][4]=[];
		$mass[$i][5]=[];

		$mass[$i][4][0]=[];
		$mass[$i][4][0]["totalCash"] =0;
		$mass[$i][4][0]["totalChecks"] =0;
		$mass[$i][4][1]=[];
		$mass[$i][4][1]["totalCash"] =0;
		$mass[$i][4][1]["totalChecks"] =0;
		$mass[$i][4][2]=[];
		$mass[$i][4][2]["totalCash"] =0;
		$mass[$i][4][2]["totalChecks"] =0;

		$mass[$i][5][0]=[];
		$mass[$i][5][0]["cash"] = 0;
		$mass[$i][5][0]["checks"] = 0;

		$mass[$i][5][1]=[];
		$mass[$i][5][1]["cash"] = 0;
		$mass[$i][5][1]["checks"] = 0;
		$mass[$i][5][2]=[];
		$mass[$i][5][2]["cash"] = 0;
		$mass[$i][5][2]["checks"] = 0;
		$mass[$i][5][3]=[];
		$mass[$i][5][3]["cash"] = 0;
		$mass[$i][5][3]["checks"] = 0;
		$mass[$i][5][4]=[];
		$mass[$i][5][4]["cash"] = 0;
		$mass[$i][5][4]["checks"] = 0;
		$mass[$i][5][5]=[];
		$mass[$i][5][5]["cash"] = 0;
		$mass[$i][5][5]["checks"] = 0;
		$mass[$i][5][6]=[];
		$mass[$i][5][6]["cash"] = 0;
		$mass[$i][5][6]["checks"] = 0;
		$mass[$i][5][7]=[];
		$mass[$i][5][7]["cash"] = 0;
		$mass[$i][5][7]["checks"] = 0;


		// echo $msqueryStores;
// var_dump ($mass);
		require '../../dbclose.php';
		require '../../dbconnectmskv.php';
		$msquery="
		select sum(t1.nationalsum)as cash, count(t1.nationalsum) as checks,
	  t1.IPRINTSTATION as cassa,
	  day(t1.CLOSEDATETIME) as day, month(t1.CLOSEDATETIME) as month,
		DATEPART(dw,t1.CLOSEDATETIME) as dw
	  from PRINTCHECKS as t1
	  where year(t1.CLOSEDATETIME) = year(getdate())
	  and month(t1.CLOSEDATETIME) >= month(getdate())-2
	  and ".$msqueryStores."
		group by t1.IPRINTSTATION, day(t1.CLOSEDATETIME), month(t1.CLOSEDATETIME), DATEPART(dw,t1.CLOSEDATETIME)
	  order by cassa, month desc, day desc;
		";
		// $msquery="SELECT SIFR, CODE, NAME, NETNAME FROM CASHES;";
		// echo $msquery;
		// echo "<br>";
		$msqresult = mssql_query($msquery);
		$currentCassa = 0;
		$indexCurrentBakery = 0;
		$indexDay = 0;
		while ($msrow = mssql_fetch_array($msqresult))
		{
			// var_dump ($msrow);
			// echo "cassfdafdsafa ".$msrow["SIFR"]." ".$msrow["NETNAME"]."<br/>";

			if ($currentCassa != (int) $msrow["cassa"]) {
				// echo "<br>".$msrow["cassa"];
				for($j=0; $j<$i; $j++)
				{
					if($mass[$j][0] == (int) $msrow["cassa"])
					{
						$indexCurrentBakery = $j;
						$currentCassa = $msrow["cassa"];
						$indexDay = 0;
						$currentMonth = $msrow["month"];
						$indexMonth = 0;



						// var_dump ($mass[$indexCurrentBakery]);

						break;
					}
				}
			}
			if ($currentMonth != $msrow["month"]){
				$currentMonth = $msrow["month"];
				$indexMonth++;
			}
			if ($indexDay < 8) {
				$mass[$indexCurrentBakery][5][$indexDay]["cash"] = $msrow["cash"];
				$mass[$indexCurrentBakery][5][$indexDay]["checks"] = $msrow["checks"];
				$mass[$indexCurrentBakery][5][$indexDay]["date"] = $msrow["day"]."/".$msrow["month"];
				$mass[$indexCurrentBakery][5][$indexDay]["dw"] = $msrow["dw"];
				$mass[$i][5][$indexDay]["cash"] = $mass[$i][5][$indexDay]["cash"] + $msrow["cash"];
				$mass[$i][5][$indexDay]["checks"] = $mass[$i][5][$indexDay]["checks"] + $msrow["checks"];
				$mass[$i][5][$indexDay]["date"] = $msrow["day"]."/".$msrow["month"];
				$mass[$i][5][$indexDay]["dw"] = $msrow["dw"];
			}
			$indexDay++;
			// echo $indexMonth;
			$mass[$indexCurrentBakery][4][$indexMonth]["month"] = $msrow["month"];
			$mass[$indexCurrentBakery][4][$indexMonth]["totalCash"] = $mass[$indexCurrentBakery][4][$indexMonth]["totalCash"] + $msrow["cash"];

			$mass[$indexCurrentBakery][4][$indexMonth]["totalChecks"] = $mass[$indexCurrentBakery][4][$indexMonth]["totalChecks"] + $msrow["checks"];

		$mass[$i][4][$indexMonth]["totalCash"] = 	$mass[$i][4][$indexMonth]["totalCash"] + $msrow["cash"];
			$mass[$i][4][$indexMonth]["totalChecks"] = $mass[$i][4][$indexMonth]["totalChecks"] + $msrow["checks"];

			// echo $msrow['dw'];

		}
		// var_dump ($mass);


		for($j=0; $j<=$i; $j++)
		{
			echo '<div style="
			width:229px;
			height:280px;
			float:left;
			border-style:solid;
			border-width:1px;
			margin:5px;
			padding:3px;
			border-radius:5px;
			border-color:gray;
			box-shadow: 0px 0px 15px black;

			" id ="'.$mass[$j][0].'">';
			echo '<div style="clear:both; text-align:center; font-family: Underdog;">№ '.$mass[$j][3].' '.$mass[$j][1].'</div>';
			echo "<div style='height:320px; clear:both;'>";
			echo '
			<table style="float:left;">
			<tr style = "">
			<th style="width:80px;">Дата</th>
			<th style="">Сумма</th>
			<th style="font-size:8px;width:35px;">Чеков</th>
			<th style="font-size:8px;width:35px;">Средний</th>
			</tr>';
			for ($z = 7; $z >= 0; $z--) {
				$x = number_format(htmlspecialchars($mass[$j][5][$z]['cash']),2);
				$x2= htmlspecialchars($mass[$j][5][$z]['checks']);
				$x3= number_format($mass[$j][5][$z]['cash']/$mass[$j][5][$z]['checks']);
				$day = $mass[$j][5][$z]['date'];
				$dw = (int) $mass[$j][5][$z]['dw'];
				if($day==(int) date('d'))
				{
					echo '<tr style="background-color:#0d4f38;">';//E3FFE3 B8FFDB
				}
				else if ($dw == 1 || $dw == 7)
				{
					echo '<tr style="background-color:#692335;">';
				}
				else
				{
					echo '<tr>';
					//$totalAmount+=$msrow['cash'];
					//$totalChecks+=$msrow['checks'];
				}
				echo '
				<td>',$day,'</td>
				<td style="text-align:right;">',$x,'</td>
				<td style="text-align:right;">',$x2,'</td>
				<td style="text-align:right;">',$x3,'</td>
				</tr>';
			}

			echo '</table>';

			echo '<div style="font-size:11px; clear:both;">';
			echo '<table>';
			echo '<tr><th style="background:#292d34;" colspan="4">Выручка/Чеков/Средний</th></tr>';
			echo '<tr><td style="width:30%;">Этот месяц:</td><td style="text-align:right;">'. number_format($mass[$j][4][0]["totalCash"]) . '  </td><td style="text-align:right;">'. number_format($mass[$j][4][0]["totalChecks"])  . '  </td><td style="text-align:right;">  ' .number_format($mass[$j][4][0]["totalCash"]/$mass[$j][4][0]["totalChecks"]). '</td></tr>';
			echo '<tr><td style="width:30%;">Прошлый:</td><td style="text-align:right;">'. number_format($mass[$j][4][1]["totalCash"]) . '  </td><td style="text-align:right;">'. number_format($mass[$j][4][1]["totalChecks"])  . '  </td><td style="text-align:right;">  ' .number_format($mass[$j][4][1]["totalCash"]/$mass[$j][4][1]["totalChecks"]). '</td></tr>';
			echo '<tr><td style="width:30%;">Позапрошлый:</td><td style="text-align:right;">'. number_format($mass[$j][4][2]["totalCash"]) . '  </td><td style="text-align:right;">'. number_format($mass[$j][4][2]["totalChecks"])  . '  </td><td style="text-align:right;">  ' .number_format($mass[$j][4][2]["totalCash"]/$mass[$j][4][2]["totalChecks"]). '</td></tr>';
			echo '</table>';
			// echo '<table>';
			// echo '<tr><th style="background:#E6E6E6" colspan="3">Чеков / Средний чек</th></tr>';
			// echo '<tr><td style="width:50%;">Этот месяц:</td><td style="text-align:right; width:25%;">'. $mass[$j][4][0]["totalChecks"]  . '  </td><td style="text-align:right;">  ' .(int)$mass[$j][4][0]["totalCash"]/$mass[$j][4][0]["totalChecks"]. '</td></tr>';
			// echo '<tr><td style="width:50%;">Прошлый месяц:</td><td style="text-align:right; width:25%;">'. $mass[$j][4][1]["totalChecks"]  . '  </td><td style="text-align:right;">  ' . (int)$mass[$j][4][1]["totalCash"]/$mass[$j][4][1]["totalChecks"]. '</td></tr>';
			// echo '<tr><td style="width:50%;">Позапрошлый:</td><td style="text-align:right; width:25%;">'. $mass[$j][4][2]["totalChecks"]  . '  </td><td style="text-align:right;">  ' .(int)$mass[$j][4][2]["totalCash"]/$mass[$j][4][2]["totalChecks"] . '</td></tr>';
			// echo '</table>';
			echo '</div>';
			echo "</div>";//divAllTables
			echo '</div>';
		}
		// $msquery="
		// select sum(t1.nationalsum)as cash, count(t1.nationalsum) as checks,
		// t1.IPRINTSTATION as cassa,
		// day(t1.CLOSEDATETIME) as day, month(t1.CLOSEDATETIME) as month,
		// DATEPART(dw,t1.CLOSEDATETIME) as dw
		// from  [RK7].[dbo].[PRINTCHECKS] as t1
		// where year(t1.CLOSEDATETIME) = year(getdate())
		// and month(t1.CLOSEDATETIME) >= month(getdate())-2
		// and ".$msqueryStores."
		// group by t1.IPRINTSTATION, day(t1.CLOSEDATETIME), month(t1.CLOSEDATETIME), DATEPART(dw,t1.CLOSEDATETIME)
		// order by cassa, month desc, day desc;
		// ";
		// // echo $msquery;
		// // echo "<br>";
		// $msqresult = mssql_query($msquery);
		// $currentCassa = 0;
		// $indexCurrentBakery = 0;
		// $indexDay = 0;
		// while ($msrow = mssql_fetch_array($msqresult))
		// {
		// 	var_dump ($msrow);
		// }

?>
<p class="paraOverTable" style="margin-left:10px;clear:both; padding-top:50px;"></p>
</div>
<?php
if($_COOKIE['userID']==1){
	$msquery="SELECT SIFR, CODE, NAME, NETNAME FROM CASHES;";
	$msqresult = mssql_query($msquery);
	while ($msrow = mssql_fetch_array($msqresult))
	{
		echo "<span style='clear:both'> NETNAME: ".$msrow["NETNAME"].", SIFR: ".$msrow["SIFR"]."</span><br/>";
	}
}
?>
</body>
</html>
