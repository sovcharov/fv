<?php
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$store =  $request->store;
$date =  $request->date;
require '../../dbconnectms.php';
$msquery="
    select sum(c.Summa) as total, count(c.Summa) as checks
    from ChequeHead as c
    where convert(date,c.DateOperation) = '".$date."'
    and c.Cash_Code = ".$store."
    ";
$msqresult = mssql_query($msquery);
$result = [];
while ($msrow = mssql_fetch_array($msqresult))
{
    $result['total'] = $msrow['total'];
    $result['checks'] = $msrow['checks'];
}
if(!$result['total']){
    $result['total'] = 0;
    $result['total'] = 0;
}
require '../../dbclosems.php';
echo json_encode($result);
?>
