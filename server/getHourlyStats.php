<?php
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$store =  $request->store;
$date =  $request->date;
require '../../dbconnectms.php';
$msquery="
    select sum(c.Summa) as total, count(c.Summa) as checks, convert(date,c.DateOperation) as date,DATEPART(hh,c.DateOperation)as time
    from ChequeHead as c
    where convert(date,c.DateOperation) > CONVERT(date,getdate()-8)
    and c.Cash_Code = ".$store."
    group by convert(date,c.DateOperation), DATEPART(hh,c.DateOperation)
    order by DATEPART(hh,c.DateOperation), convert(date,c.DateOperation)
    ";
$msqresult = mssql_query($msquery);
$myArray = [];
$tempArray = [];
while ($msrow = mssql_fetch_array($msqresult))
{
    $myArray[$msrow['time']][$msrow['date']]['cash'] = $msrow['total'];
    $myArray[$msrow['time']]['time'] = $msrow['time'];
    $myArray[$msrow['time']][$msrow['date']]['checks'] = $msrow['checks'];

    $tempArray[$msrow['date']] = $tempArray[$msrow['date']]+ $msrow['total'];
    $myArray[$msrow['time']][$msrow['date']]['sum'] = $tempArray[$msrow['date']];
}
$result = [];
require '../../dbclosems.php';
echo json_encode($myArray);
?>
