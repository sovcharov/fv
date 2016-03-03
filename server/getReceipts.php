<?php
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$store =  $request->store;
$date =  $request->date;
require '../../dbconnectms.php';
$msquery="
    select c.Cash_Code as Store, c2.chequeID,
    c.Ck_Number as Ch,c.Summa as Total, c.Disc_Sum as discount,
    g.Code as code, g.goodsName as gName,
    c2.Quant as qty, c2.Price as price, c2.Summa as Sum,
    convert(date,c.DateOperation) as date,convert(char(8),c.DateOperation,108)as time
    from Goods4 as g,ChequeHead as c,ChequePos as c2
    where g.Code=c2.Code
    and c2.ChequeId=c.Id
    and convert(date,c.DateOperation) = '".$date."'
    --and convert(date,c.DateOperation) > CONVERT(date,getdate()-1)
    --AND DATEPART(hh,c.DateOperation) >= 19
    --AND DATEPART(hh,c.DateOperation) < 23
    --AND DATEPART(mi,c.DateOperation) >= 8
    --AND DATEPART(mi,c.DateOperation) < 30
    and c.Cash_Code = ".$store."
    order by c2.ChequeId desc
    ";
$msqresult = mssql_query($msquery);
$myArray = [];
while ($msrow = mssql_fetch_array($msqresult))
{
    $myArray[$msrow['Ch']]['number'] = $msrow['Ch'];
    $myArray[$msrow['Ch']]['total'] = $msrow['Total'];
    $myArray[$msrow['Ch']]['discount'] = $msrow['discount'];
    $myArray[$msrow['Ch']]['time'] = $msrow['time'];
    $tempArray['code'] = $msrow['code'];
    $tempArray['name'] = $msrow['gName'];
    $tempArray['price'] = $msrow['price'];
    $tempArray['qty'] = $msrow['qty'];
    $tempArray['sum'] = $msrow['Sum'];
    if(count($myArray[$msrow['Ch']]['goods'])) {

        array_push($myArray[$msrow['Ch']]['goods'], $tempArray);
    } else {
        $myArray[$msrow['Ch']]['goods'] = [];
        array_push($myArray[$msrow['Ch']]['goods'], $tempArray);
    }
    // $myArray[$msrow['Ch']][$msrow['code']]['name'] = $msrow['gName'];
    // $myArray[$msrow['Ch']][$msrow['code']]['price'] = $msrow['price'];
    // $myArray[$msrow['Ch']][$msrow['code']]['qty'] = $msrow['qty'];
    // $myArray[$msrow['Ch']][$msrow['code']]['summa'] = $msrow['Sum'];
    // $myArray[$msrow['time']][$msrow['date']]['checks'] = $msrow['checks'];
    //
    // $tempArray[$msrow['date']] = $tempArray[$msrow['date']]+ $msrow['total'];
    // $myArray[$msrow['time']][$msrow['date']]['sum'] = $tempArray[$msrow['date']];
}
$result = [];
foreach($myArray as $value) {
    array_push($result, $value);
}
require '../../dbclosems.php';
echo json_encode($result);
?>
