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
		require '../../dbclose.php';
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
<body ng-controller="MainController">
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
					if($_COOKIE['userID']==1 || $_COOKIE['userType']==1 || $_COOKIE['userID']==20) echo '<li><a href="/cakesorder/">orders</a></li>';
					if($_COOKIE['userID']==1) echo '<li><a href="../admin">admin</a></li>';
				?>
                <li><a href="../passwordchange/">Сменить Пароль</a></li>
                <li class=""><a href="exitapp.php">Выход</a></li>
            </ul>
        </div>
    </nav>
	<div id="divContainer">
		<?php
			echo  "<p class=\"paraOverTable\" style=\"margin-left:10px;\">Добро пожаловать, " . $_COOKIE['userName']. "!</p>";
			require '../../dbconnect.php';
			if($_COOKIE['userType']==4 || $_COOKIE['userID']==1  || $_COOKIE['userType']==5){
				$query ="
					select distinct stores.id storeID, stores.name as storeName, stores.venue as venue
					from stores
					";
			}
			else{
				$query ="
					select distinct stores.id storeID, stores.name as storeName, stores.venue as venue
					from stores, userStores, users
					where stores.id=userStores.storeID
					and  users.id=userStores.userID
					and users.id='".$_COOKIE['userID']."'
					";
			}
			$qresult = mysqli_query($db, $query);
			$i=0;
			while ($row = mysqli_fetch_array($qresult))
			{
				$mass[$i][0]=(int) $row['storeID'];
				$mass[$i][1]=$row['storeName'];
				$mass[$i][2]=(string) $row['venue'];
				//echo $mass[$i][0];
				$i++;
			}
			require '../../dbclose.php';
			require '../../dbconnectms.php';
			for($j=0; $j<$i; $j++)
			{
				echo '<div style="
							width:229px;
							height:367px;
							float:left;
							border-style:solid;
							border-width:1px;
							margin:5px;
							padding:3px;
							border-radius:5px;
							border-color:gray;
							box-shadow: 0px 0px 15px black;

							" id ="'.$mass[$i][0].'">';
				echo '<div style="clear:both; text-align:center; font-family: Underdog;">№ '.$mass[$j][0].' '.$mass[$j][1].'</div>';
				$msquery="
					select * from (select top(8) convert(date,GangDateStart) as date, day(GangDateStart) as day, CashNumber as number, Summa as cash, ChequeCountSale as checks, datepart(dw,GangDateStart) as dw
					from OperGang
					where cashNumber = '".$mass[$j][0]."'
					and Summa <> 0
                    and getdate() - GangDateStart < 15
					order by convert(date,GangDateStart) desc) as t1
					order by date
					";

				$msqresult = mssql_query($msquery);
				echo "<div id='divAllTables' style='height:320px; clear:both;'>";
				echo '
					<table style="width:; float:left;">
					<tr>
					<th style="width:80px;">Дата</th>
					<th style="">Сумма</th>
					<th style="font-size:8px;width:35px;">Средний</th>
					<th style="font-size:8px;width:35px;">Чеков</th>
					</tr>';
				$countRow=0;
				//$totalAmount=0;
				//$totalChecks=0;

				while ($msrow = mssql_fetch_array($msqresult))
				{

					$x = number_format(htmlspecialchars($msrow['cash']),2);
					$x2= htmlspecialchars($msrow['checks']);
					$x3= number_format($msrow['cash']/$msrow['checks']);
					$day = (int) $msrow['day'];
                    $dw = (int) $msrow['dw'];
					if($day==(int) date(d))
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
							<td>',htmlspecialchars($msrow['date']),'</td>
							<td style="text-align:right;">',$x,'</td>
							<td style="text-align:right;">',$x3,'</td>
							<td style="text-align:right;">',$x2,'</td>
						</tr>';
					$countRow++;
				}
				echo '</table>';
				$msquery="
					select OperGang.CashNumber as avID2, AVG(OperGang.Summa) as avCashMonth
						, SUM(Summa) as sumCashThisMonth,AVG(OperGang.ChequeCountSale) as avChecksThisMonth,
						SUM(Summa)/SUM(OperGang.ChequeCountSale) as avCheckAmountMonth
						from OperGang
						where CashNumber = '".$mass[$j][0]."'
						and YEAR(GangDateStart) = YEAR(getdate())
						and month(GangDateStart) = month(getdate())
						and Summa <> 0
						group by OperGang.CashNumber
					";

				$msqresult = mssql_query($msquery);

				$salesThisMonth = 0;
				$avCashMonth = 0;
				$avCheckAmountMonth = 0;
				$checksPerDayThisMonth = 0;
				while ($msrow = mssql_fetch_array($msqresult))
				{
					$salesThisMonth = number_format(htmlspecialchars($msrow['sumCashThisMonth']),2);
					$avCashMonth = number_format(htmlspecialchars($msrow['avCashMonth']),2);
					$avCheckAmountMonth = number_format(htmlspecialchars($msrow['avCheckAmountMonth']),0);
					$checksPerDayThisMonth = number_format(htmlspecialchars($msrow['avChecksThisMonth']),0);
				}
				$msquery="
					select OperGang.CashNumber as avID3, AVG(OperGang.Summa) as avCashLastMonth
						, SUM(Summa) as sumCashLastMonth,AVG(OperGang.ChequeCountSale) as avChecksLastMonth,
						SUM(Summa)/SUM(OperGang.ChequeCountSale) as avCheckAmountLastMonth
						from OperGang
						where CashNumber = '".$mass[$j][0]."'
						and YEAR(GangDateStart) = YEAR(getdate())-1
						and month(GangDateStart) = month(getdate())+11
						and Summa <> 0
						group by OperGang.CashNumber
					";

				$msqresult = mssql_query($msquery);
				$salesLastMonth = 0;
				$avCashLastMonth = 0;
				$avCheckAmountLastMonth = 0;
				$checksPerDayLastMonth = 0;
				while ($msrow = mssql_fetch_array($msqresult))
				{
					$salesLastMonth = number_format(htmlspecialchars($msrow['sumCashLastMonth']),2);
					$avCashLastMonth = number_format(htmlspecialchars($msrow['avCashLastMonth']),2);
					$avCheckAmountLastMonth = number_format(htmlspecialchars($msrow['avCheckAmountLastMonth']),0);
					$checksPerDayLastMonth = number_format(htmlspecialchars($msrow['avChecksLastMonth']),0);
				}
				$msquery="
					select OperGang.CashNumber as avID, AVG(OperGang.Summa) as avCash,
						AVG(OperGang.ChequeCountSale) as avChecks, SUM(Summa) as sumCash,
						SUM(Summa)/SUM(OperGang.ChequeCountSale) as avCheckAmountYear
						from OperGang
						where CashNumber = '".$mass[$j][0]."'
						and YEAR(GangDateStart) = YEAR(getdate())-1
						and month(GangDateStart) = month(getdate())+10
						and Summa <> 0
						group by OperGang.CashNumber
					";

				$msqresult = mssql_query($msquery);
				$sales2Month = 0;
				$avCash2Months = 0;
				$avCheckAmount2Month = 0;
				$checksPerDay2Month = 0;
				while ($msrow = mssql_fetch_array($msqresult))
				{
					$sales2Month = number_format(htmlspecialchars($msrow['sumCash']),2);
					$avCash2Months = number_format(htmlspecialchars($msrow['avCash']),2);
					$avCheckAmount2Month = number_format(htmlspecialchars($msrow['avCheckAmountYear']),0);
					$checksPerDay2Month = number_format(htmlspecialchars($msrow['avChecks']),0);
				}

				//additional information
					echo '<div style="font-size:10px; clear:both;">';
						echo '<table>';
						echo '<tr><th style="background:#E6E6E6" colspan="3">Выручка: Всего / Средняя</th></tr>';
						echo '<tr><td style="width:30%;">Этот месяц:</td><td style="text-align:right;">'. $salesThisMonth . '  </td><td style="text-align:right;">  ' .$avCashMonth. '</td></tr>';
						echo '<tr><td style="width:30%;">Прошлый:</td><td style="text-align:right;">'. $salesLastMonth . '  </td><td style="text-align:right;">  ' .$avCashLastMonth. '</td></tr>';
						echo '<tr><td style="width:30%;">Позапрошлый:</td><td style="text-align:right;">'. $sales2Month . '  </td><td style="text-align:right;">  ' .$avCash2Months. '</td></tr>';
						echo '</table>';
						echo '<table>';
						echo '<tr><th style="background:#E6E6E6" colspan="3">Средний чек  /  Чеков в день</th></tr>';
						echo '<tr><td style="width:50%;">Этот месяц:</td><td style="text-align:right; width:25%;">'. $avCheckAmountMonth . '  </td><td style="text-align:right;">  ' .$checksPerDayThisMonth. '</td></tr>';
						echo '<tr><td style="width:50%;">Прошлый месяц:</td><td style="text-align:right; width:25%;">'. $avCheckAmountLastMonth . '  </td><td style="text-align:right;">  ' .$checksPerDayLastMonth. '</td></tr>';
						echo '<tr><td style="width:50%;">Позапрошлый:</td><td style="text-align:right; width:25%;">'. $avCheckAmount2Month . '  </td><td style="text-align:right;">  ' .$checksPerDay2Month. '</td></tr>';
						echo '</table>';
					echo '</div>';
				echo "</div>";//divAllTables
				//end of additional information
				if($mass[$j][2])
				{
					?>
					<div>
						<input type="checkbox" ng-model="checked[<?php echo $mass[$j][0];?>]" ng-click="getAds('<?php echo $mass[$j][2];?>',<?php echo $mass[$j][0];?>);"> Отзывы Foursquare

					</div>
					<?php
				}
				echo '</div>';
				?>

					<div ng-show="checked[<?php echo $mass[$j][0];?>] && zero[<?php echo $mass[$j][0];?>]" style="
							width:99%;
							clear:both;
							border-style:solid;
							border-width:1px;
							margin:5px;
							padding:3px;
							border-radius:5px;
							border-color:gray;
							box-shadow: 0px 0px 15px black;
							background-color:white;">
						<div ng-show = "!zero[<?php echo $mass[$j][0];?>].length">Загрузка данных Foursquare
						</div>
						<div ng-repeat="tips in zero[<?php echo $mass[$j][0];?>]">

							<div style="
								margin:5px 10px 5px 10px;
							">
								<span style="color:blue;font-size:12px;">{{tips.name}} </span><span style="color:gray;font-size:12px;"> {{tips.date}}</span><br><span style="font-size:1em; font-style: italic; font-family:Underdog;">- {{tips.text}}</span>
							</div>
						</div>
					</div>

				<?php
			}
			require '../../dbclosems.php';

			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//start of TOTAL FOR ALL BAKERIES                                                                                               //
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if($_COOKIE['userType']==1 || $_COOKIE['userID']==4 || $_COOKIE['userID']==3 || $_COOKIE['userID']==8 || $_COOKIE['userID']==2)
			{
				require '../../dbconnectms.php';
				echo '<div style="
							width:229px;
							height:367px;
							float:left;
							border-style:solid;
							border-width:1px;
							margin:5px;
							padding:3px;
							border-radius:5px;
							border-color:gray;
							box-shadow: 0px 0px 15px black;
							background-color:;
							" id ="'.$mass[$i][0].'">';
				echo '<div style="clear:both; text-align:center; font-family: Underdog;">Суммарная выручка</div>';
				$msquery="
					select * from(select top(8) convert(date,GangDateStart) as date, day(convert(date,GangDateStart)) as day, SUM(Summa) as cash, SUM(ChequeCountSale) as checks, datepart(dw,convert(date,GangDateStart)) as dw
					from OperGang
					where convert(date,GangDateStart) > CONVERT(date,getdate()-9)
					and Summa <> 0
					group by convert(date,GangDateStart)
					order by convert(date,GangDateStart) desc) as t1
					order by date;
					";

				$msqresult = mssql_query($msquery);
				echo '
					<table style="width:; float:left;">
					<tr>
					<th style="width:80px;">Дата</th>
					<th style="">Сумма</th>
					<th style="font-size:8px;width:35px;">Средний</th>
					<th style="font-size:8px;width:35px;">Чеков</th>
					</tr>';
				$countRow=0;
				while ($msrow = mssql_fetch_array($msqresult))
				{

					$x = number_format(htmlspecialchars($msrow['cash']),2);
					$x2= htmlspecialchars($msrow['checks']);
					$x3= number_format($msrow['cash']/$msrow['checks']);
					$day = (int) $msrow['day'];
                    $dw = (int) $msrow['dw'];
					if($day==(int) date(d))
					{
						echo '<tr style="background-color:#E3FFE3;">';
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
							<td>',htmlspecialchars($msrow['date']),'</td>
							<td style="text-align:right;">',$x,'</td>
							<td style="text-align:right;">',$x3,'</td>
							<td style="text-align:right;">',$x2,'</td>
						</tr>';
					$countRow++;
				}
				echo '</table>';
				$msquery="
						select SUM(Summa)/day(getdate()) as avCashMonth
						, SUM(Summa) as sumCashThisMonth,sum(OperGang.ChequeCountSale)/day(getdate()) as avChecksThisMonth,
						SUM(Summa)/SUM(OperGang.ChequeCountSale) as avCheckAmountMonth,
						(SUM(Summa)/day(getdate()))*DATEPART(day, DATEADD(s,-1,DATEADD(mm, DATEDIFF(m,0,GETDATE())-1,0))) as plannedSumma
						from OperGang
						where YEAR(GangDateStart) = YEAR(getdate())
						and month(GangDateStart) = month(getdate())
						and Summa <> 0
					";

				$msqresult = mssql_query($msquery);

				$salesThisMonth = 0;
				$avCashMonth = 0;
				$avCheckAmountMonth = 0;
				$checksPerDayThisMonth = 0;
				$plannedSumma = 0;
				while ($msrow = mssql_fetch_array($msqresult))
				{
					$salesThisMonth = number_format(htmlspecialchars($msrow['sumCashThisMonth']),2);
					$avCashMonth = number_format(htmlspecialchars($msrow['avCashMonth']),2);
                    $avCashMonth2 = (int)$msrow['avCashMonth'];
					$avCheckAmountMonth = number_format(htmlspecialchars($msrow['avCheckAmountMonth']),0);
					$checksPerDayThisMonth = number_format(htmlspecialchars($msrow['avChecksThisMonth']),0);
					$plannedSumma = $msrow['plannedSumma'];
				}
				$msquery="
					select SUM(Summa)/DATEPART(day, DATEADD(s,-1,DATEADD(mm, DATEDIFF(m,0,GETDATE()),0))) as avCashLastMonth
						, SUM(Summa) as sumCashLastMonth,sum(OperGang.ChequeCountSale)/DATEPART(day, DATEADD(s,-1,DATEADD(mm, DATEDIFF(m,0,GETDATE()),0))) as avChecksLastMonth,
						SUM(Summa)/SUM(OperGang.ChequeCountSale) as avCheckAmountLastMonth
						from OperGang
						where YEAR(GangDateStart) = YEAR(getdate())
						and month(GangDateStart) = month(getdate())-1
						and Summa <> 0
					";

				$msqresult = mssql_query($msquery);
				$salesLastMonth = 0;
				$avCashLastMonth = 0;
				$avCheckAmountLastMonth = 0;
				$checksPerDayLastMonth = 0;
				while ($msrow = mssql_fetch_array($msqresult))
				{
					$salesLastMonth = number_format(htmlspecialchars($msrow['sumCashLastMonth']),2);
					$avCashLastMonth = number_format(htmlspecialchars($msrow['avCashLastMonth']),2);
					$avCheckAmountLastMonth = number_format(htmlspecialchars($msrow['avCheckAmountLastMonth']),0);
					$checksPerDayLastMonth = number_format(htmlspecialchars($msrow['avChecksLastMonth']),0);
				}
				$msquery="
					select SUM(Summa)/DATEPART(day, DATEADD(s,-1,DATEADD(mm, DATEDIFF(m,0,GETDATE())-1,0))) as avCash,
						sum(OperGang.ChequeCountSale)/DATEPART(day, DATEADD(s,-1,DATEADD(mm, DATEDIFF(m,0,GETDATE())-1,0))) as avChecks, SUM(Summa) as sumCash,
						SUM(Summa)/SUM(OperGang.ChequeCountSale) as avCheckAmountYear
						from OperGang
						where YEAR(GangDateStart) = YEAR(getdate())
						and month(GangDateStart) = month(getdate())-2
						and Summa <> 0
					";

				$msqresult = mssql_query($msquery);
				$sales2Month = 0;
				$avCash2Months = 0;
				$avCheckAmount2Month = 0;
				$checksPerDay2Month = 0;
				while ($msrow = mssql_fetch_array($msqresult))
				{
					$sales2Month = number_format(htmlspecialchars($msrow['sumCash']),2);
					$avCash2Months = number_format(htmlspecialchars($msrow['avCash']),2);
					$avCheckAmount2Month = number_format(htmlspecialchars($msrow['avCheckAmountYear']),0);
					$checksPerDay2Month = number_format(htmlspecialchars($msrow['avChecks']),0);
				}
				//additional information
				echo '<div style="font-size:10px; clear:both;">';
				echo '<table>';
				echo '<tr><th style="background:#E6E6E6" colspan="3">Выручка: Всего / Средняя</th></tr>';
				echo '<tr><td style="width:30%;">Этот месяц:</td><td style="text-align:right;">'. $salesThisMonth . '  </td><td style="text-align:right;">  ' .$avCashMonth. '</td></tr>';
				echo '<tr><td style="width:30%;">Прошлый:</td><td style="text-align:right;">'. $salesLastMonth . '  </td><td style="text-align:right;">  ' .$avCashLastMonth. '</td></tr>';
				echo '<tr><td style="width:30%;">Позапрошлый:</td><td style="text-align:right;">'. $sales2Month . '  </td><td style="text-align:right;">  ' .$avCash2Months. '</td></tr>';
				echo '</table>';
				echo '<table>';
				echo '<tr><th style="background:#E6E6E6" colspan="3">Средний чек  /  Чеков в день</th></tr>';
				echo '<tr><td style="width:50%;">Этот месяц:</td><td style="text-align:right; width:25%;">'. $avCheckAmountMonth . '  </td><td style="text-align:right;"> '.$checksPerDayThisMonth.' </td></tr>';
				echo '<tr><td style="width:50%;">Прошлый месяц:</td><td style="text-align:right; width:25%;">'. $avCheckAmountLastMonth . '  </td><td style="text-align:right;">'.$checksPerDayLastMonth.' </td></tr>';
				echo '<tr><td style="width:50%;">Позапрошлый:</td><td style="text-align:right; width:25%;">'. $avCheckAmount2Month . '  </td><td style="text-align:right;"> '.$checksPerDay2Month.'</td></tr>';
				echo '</table>';
                $daysInMonths = array(0,31,28,31,30,31,30,31,31,30,31,30,31);
                $daysThisMonth = $daysInMonths[(int)date('m')];
                if (!(int)date('y')%4 && date('m') == 2) $daysThisMonth += 1;
                $plannedSumma = $avCashMonth2 * $daysThisMonth;
				echo '<span style ="">Планируемая выручка: '.number_format($plannedSumma,2);
				echo '<br>Планируемая выручка производства: '.number_format($plannedSumma*0.37,2);
				echo '</span>';
				echo '</div>';
				echo '</div>';
				require '../../dbclosems.php';
			}
		?>
		<p class="paraOverTable" style="margin-left:10px;clear:both; padding-top:50px;"></p>
	</div>
</body>
</html>
