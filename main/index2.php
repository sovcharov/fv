<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
	call addLog($user,'Old Main')
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
	<title>ФВ.net</title>
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
<body ng-controller="MainController" ng-cloak>
	<nav class="navbar navbar-default">
		<a class="navbar-brand" href="/"><img alt="Brand" height="30" width="34" src="../images/fvlogo.png"></a>
		<a class="navbar-brand" href="/">Пекарня Ф. Вольчека</a>

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

		$string = file_get_contents("../../../soft/bakerydata.json");
		// if ($string === false) {
		//     // deal with error...
		// }

		$json = json_decode($string, true);
		// if ($json_a === null) {
		//     // deal with error...
		// }
		// var_dump ($json);
		$timeCreated = $json[sizeof($json)-1];
		// echo $timeCreated;
		array_pop($json);
		// var_dump ($json);

		echo  "<p class=\"paraOverTable\" style=\"margin-left:10px;\">Добро пожаловать, " . $_COOKIE['userName']. "!<span style = 'font-size: 12px;'> Обновлено: " .$timeCreated. "</span></p>";
		// require '../../dbconnect.php';


		if($_COOKIE['userType']==4 || $_COOKIE['userID']==1  || $_COOKIE['userType']==5){
			$storesData = $json;
		}
		else{
			$query ="
			select distinct stores.id storeID, stores.bakery as bakery, stores.name as storeName, stores.venue as venue
			from stores, userStores, users
			where stores.bakery=userStores.storeID
			and  users.id=userStores.userID
			and users.id='".$_COOKIE['userID']."'
			order by stores.bakery
			";

			// $query ="
			// select distinct stores.id storeID, stores.bakery as bakery, stores.name as storeName, stores.venue as venue
			// from stores, userStores, users
			// where stores.bakery=userStores.storeID
			// and  users.id=userStores.userID
			// and users.id='10'
			// order by stores.bakery
			// ";

			$qresult = mysqli_query($db, $query);
			$i=0;
			$msqueryStores = '';
			$storesData = [];
			while ($row = mysqli_fetch_array($qresult))
			{
				while ((int)$json[$i]["id"] <> (int)$row['storeID']) {
					$i ++;
				}
				echo " ".$row['storeID'];
				$currentIndex = sizeof($storesData);
				$storesData[$currentIndex] = $json[$i];
			}
		}
		// var_dump ($storesData);

		$currentIndex = sizeof($storesData);

		for ($i=0; $i <= 7; $i++) {
			$storesData[$currentIndex]["bakeryData"]["eightDays"]["date"][$i] = $storesData[0]["bakeryData"]["eightDays"]["date"][$i];
			$storesData[$currentIndex]["bakeryData"]["eightDays"]["revenue"][$i] = 0;
			$storesData[$currentIndex]["bakeryData"]["eightDays"]["checks"][$i] = 0;
			$storesData[$currentIndex]["bakeryData"]["eightDays"]["average"][$i] = 0;
		}
		$storesData[$currentIndex]["bakeryData"]["thisMonth"]["revenue"] = 0;
		$storesData[$currentIndex]["bakeryData"]["thisMonth"]["checks"] = 0;
		$storesData[$currentIndex]["bakeryData"]["thisMonth"]["average"] = 0;
		$storesData[$currentIndex]["bakeryData"]["lastMonth"]["revenue"] = 0;
		$storesData[$currentIndex]["bakeryData"]["lastMonth"]["checks"] = 0;
		$storesData[$currentIndex]["bakeryData"]["lastMonth"]["average"] = 0;
		$storesData[$currentIndex]["bakeryData"]["monthBeforeLastMonth"]["revenue"] = 0;
		$storesData[$currentIndex]["bakeryData"]["monthBeforeLastMonth"]["checks"] = 0;
		$storesData[$currentIndex]["bakeryData"]["monthBeforeLastMonth"]["average"] = 0;
		$storesData[$currentIndex]["name"] = "Суммарная";


		for($j=0; $j<$currentIndex; $j++)
		{

			for ($i=0; $i <= 7; $i++) {
					$storesData[$currentIndex]["bakeryData"]["eightDays"]["revenue"][$i] += $storesData[$j]["bakeryData"]["eightDays"]["revenue"][$i];
					$storesData[$currentIndex]["bakeryData"]["eightDays"]["checks"][$i] += $storesData[$j]["bakeryData"]["eightDays"]["checks"][$i];
					$storesData[$currentIndex]["bakeryData"]["eightDays"]["average"][$i] += $storesData[$j]["bakeryData"]["eightDays"]["average"][$i];
			}
			$storesData[$currentIndex]["bakeryData"]["thisMonth"]["revenue"] += $storesData[$j]["bakeryData"]["thisMonth"]["revenue"];
			$storesData[$currentIndex]["bakeryData"]["thisMonth"]["checks"] += $storesData[$j]["bakeryData"]["thisMonth"]["checks"];
			$storesData[$currentIndex]["bakeryData"]["thisMonth"]["average"] += $storesData[$j]["bakeryData"]["thisMonth"]["average"];
			$storesData[$currentIndex]["bakeryData"]["lastMonth"]["revenue"] += $storesData[$j]["bakeryData"]["lastMonth"]["revenue"];
			$storesData[$currentIndex]["bakeryData"]["lastMonth"]["checks"] += $storesData[$j]["bakeryData"]["lastMonth"]["checks"];
			$storesData[$currentIndex]["bakeryData"]["lastMonth"]["average"] += $storesData[$j]["bakeryData"]["lastMonth"]["average"];
			$storesData[$currentIndex]["bakeryData"]["monthBeforeLastMonth"]["revenue"] += $storesData[$j]["bakeryData"]["monthBeforeLastMonth"]["revenue"];
			$storesData[$currentIndex]["bakeryData"]["monthBeforeLastMonth"]["checks"] += $storesData[$j]["bakeryData"]["monthBeforeLastMonth"]["checks"];
			$storesData[$currentIndex]["bakeryData"]["monthBeforeLastMonth"]["average"] += $storesData[$j]["bakeryData"]["monthBeforeLastMonth"]["average"];

		}


		for($j=$currentIndex; $j>=0; $j--)
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
			box-shadow: 0px 0px 15px black;">';
			echo '<div style="clear:both; text-align:center; font-family: Underdog;">'.$storesData[$j]["name"].'</div>';
			echo "<div id='divAllTables' style='height:320px; clear:both;'>";
			echo '
			<table style="width:; float:left;">
			<tr>
			<th style="width:80px;">Дата</th>
			<th style="">Сумма</th>
			<th style="font-size:8px;width:35px;">Чеков</th>
			<th style="font-size:8px;width:35px;">Средний</th>
			</tr>';
			for ($z = 0; $z <= 7; $z++) {
				$x = number_format(htmlspecialchars($storesData[$j]["bakeryData"]["eightDays"]["revenue"][$z]),2);
				$x2= htmlspecialchars($storesData[$j]["bakeryData"]["eightDays"]["checks"][$z]);
				$x3= number_format($storesData[$j]["bakeryData"]["eightDays"]["average"][$z]);
				$day = $storesData[$j]["bakeryData"]["eightDays"]["date"][$z]["day"];//$mass[$j][5][$z]['date'];
				$dw = $storesData[$j]["bakeryData"]["eightDays"]["date"][$z]["dw"];//(int) $mass[$j][5][$z]['dw'];
				$month = $storesData[$j]["bakeryData"]["eightDays"]["date"][$z]["month"];
				if($day==(int) date('d'))
				{
					echo '<tr style="background-color:#E3FFE3;">';//E3FFE3 B8FFDB
				}
				else if ($dw == 1 || $dw == 7)
				{
					echo '<tr style="background-color:#ffeee5;">';
				}
				else
				{
					echo '<tr>';
					//$totalAmount+=$msrow['cash'];
					//$totalChecks+=$msrow['checks'];
				}
				echo '
				<td>',$day,'/',$month,'</td>
				<td style="text-align:right;">',$x,'</td>
				<td style="text-align:right;">',$x2,'</td>
				<td style="text-align:right;">',$x3,'</td>
				</tr>';
			}

			echo '</table>';

			echo '<div style="font-size:11px; clear:both;">';
			echo '<table>';
			echo '<tr><th style="background:#E6E6E6;" colspan="4">Выручка/Чеков/Средний</th></tr>';
			echo '<tr><td style="width:29%;">Этот месяц:</td><td style="text-align:right;">'. number_format($storesData[$j]["bakeryData"]["thisMonth"]["revenue"]) . '  </td><td style="text-align:right;">'. number_format($storesData[$j]["bakeryData"]["thisMonth"]["checks"])  . '  </td><td style="text-align:right;">  ' .number_format($storesData[$j]["bakeryData"]["thisMonth"]["average"]).'</td></tr>';
			echo '<tr><td style="width:29%;">Прошлый:</td><td style="text-align:right;">'. number_format($storesData[$j]["bakeryData"]["lastMonth"]["revenue"]) . '  </td><td style="text-align:right;">'. number_format($storesData[$j]["bakeryData"]["lastMonth"]["checks"])  . '  </td><td style="text-align:right;">  ' .number_format($storesData[$j]["bakeryData"]["lastMonth"]["average"]). '</td></tr>';
			echo '<tr><td style="width:29%;">Позапрошлый:</td><td style="text-align:right;">'. number_format($storesData[$j]["bakeryData"]["monthBeforeLastMonth"]["revenue"]) . '  </td><td style="text-align:right;">'. number_format($storesData[$j]["bakeryData"]["monthBeforeLastMonth"]["checks"])  . '  </td><td style="text-align:right;">  ' .number_format($storesData[$j]["bakeryData"]["monthBeforeLastMonth"]["average"]). '</td></tr>';
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
// if($_COOKIE['userID']==1){
// 	$msquery="SELECT SIFR, CODE, NAME, NETNAME FROM CASHES;";
// 	$msqresult = mssql_query($msquery);
// 	while ($msrow = mssql_fetch_array($msqresult))
// 	{
// 		echo "<span style='clear:both'> NETNAME: ".$msrow["NETNAME"].", SIFR: ".$msrow["SIFR"]."</span><br/>";
// 	}
// }
?>
</body>
</html>
