<?php
	$ordersArray=array();
	$cakesArray=array();
	require '../../../pdodbconnect.php';
	$q=$dbh->query("call getOrdersToPrint();");
	$q->setFetchMode(PDO::FETCH_ASSOC);
	$id=0;
	while ($row = $q->fetch())
	{
 		foreach ($row as $key => $value)
		{
			$ordersArray[$id]["$key"] = $value;	
			
		} 
		$id++;
	}
	$q->closeCursor();
	$q= null;
	$dbh = null;
	require '../../../pdodbconnect.php';
	$id=0;
	foreach($ordersArray as $index=>$smallArray){		
		$id = $smallArray['id'];
		$q=$dbh->query("call getOrderedCakesToPrint('$id');");
		$q->setFetchMode(PDO::FETCH_ASSOC);
		$cake=0;
		while ($row = $q->fetch())
		{
			foreach ($row as $key => $value)
			{
				$cakesArray[$id][$cake]["$key"] = $value;					
			} 
			
			$cake++;
		}
		$id++;
		$q->closeCursor();
	}
	$q= null;
	$dbh = null;
	// print_r($ordersArray);
	// print_r($cakesArray);
	// exit();

	define('FPDF_FONTPATH','FPDF/font/');
	require('FPDF/fpdf.php');
	class PDF extends FPDF
	{
		// Page header
		function Header()
		{
			// Logo
			$this->Image('../../images/fvlogo.jpg',10,8,15);
			// Arial bold 15
			$this->SetFont('ArialTM','',15);
			// Move to the right
			$this->Cell(80);
			$txt = 'Пекарня Ф. Вольчека';
			$txt = iconv('utf-8', 'cp1251', $txt);
			// Title
			$this->Cell(30,10,$txt,0,0,'C');
			$this->Cell(50);
			$this->SetFont('Arial','',12);
			$this->Cell(32,8,'www.fvolchek.ru',0,0,'C');
			// Line break
			$this->Ln(15);
			$this->SetFont('ArialTM','',20);
			$txt = 'Заказы на '.date('d.m.Y', strtotime('+1 days'));
			$txt = iconv('utf-8', 'cp1251', $txt);
			$this->Cell(130,10,$txt,0,0,'R');
			$this->SetFont('ArialTM','',12);
			$txt = 'Страница '.$this->PageNo()." из ";
			$txt = iconv('utf-8', 'cp1251', $txt);
			// Page number		
			$this->Cell(0,10,$txt.'{nb}',0,0,'R');
			$this->Ln(12);
		}
		// Page footer
		// function Footer()
		// {
			// Position at 1.5 cm from bottom
			// $this->SetY(-15);
			// $this->SetFont('ArialTM','',12);
			// $txt = 'Страница '.$this->PageNo()." из ";
			// $txt = iconv('utf-8', 'cp1251', $txt);
			// Page number		
			// $this->Cell(0,10,$txt.'{nb}',0,0,'C');
		// }
		function printText($txt, $size, $align)
		{
			$this->SetFont('ArialTM','',$size);
			$txt = iconv('utf-8', 'cp1251', $txt);
			$this->MultiCell(0,5,$txt,0,$align);
		}
		function printTable($bank,$inn, $beneficiary, $bik, $kschet, $schet)
		{
			//$this->AddFont('ArialTM','','arial.php'); 
			$this->SetFont('ArialTM','',10);
			$x=$this->GetX();
			$y=$this->GetY();
			$txt = iconv('utf-8', 'cp1251', $bank);
			$this->MultiCell(110,5,$txt,'LTR','L');
			$this->SetFont('ArialTM','',8);
			$this->Cell(110,5,' ','LR',1,'l');
			$txt = iconv('utf-8', 'cp1251', 'Банк получателя');			
			$this->Cell(110,3,$txt,'LBR',1,'l');
			$txt = iconv('utf-8', 'cp1251', 'ИНН '.$inn);	
			$this->Cell(55,5,$txt,'LTR',0,'l');
			$txt = iconv('utf-8', 'cp1251', 'КПП ');
			$this->Cell(55,5,$txt,'LTR',1,'l');
			$this->SetFont('ArialTM','',10);
			$txt = iconv('utf-8', 'cp1251', $beneficiary);
			$this->MultiCell(110,5,$txt,'LTR','L');
			$this->SetFont('ArialTM','',8);
			$this->Cell(110,5,' ','LR',1,'l');
			$txt = iconv('utf-8', 'cp1251', 'Получатель');			
			$this->Cell(110,3,$txt,'LBR',1,'l');
			$this->SetXY($x+110,$y);
			$txt = iconv('utf-8', 'cp1251', 'БИК');
			$this->Cell(15,8,$txt,1,2,'l');
			$txt = iconv('utf-8', 'cp1251', 'Сч. №');
			$this->Cell(15,10,$txt,1,2,'l');
			$txt = iconv('utf-8', 'cp1251', 'Сч. №');
			$this->Cell(15,18,$txt,1,2,'l');
			$this->SetXY($x+110+15,$y);
			$txt = iconv('utf-8', 'cp1251', $bik);
			$this->Cell(0,8,$txt,1,2,'l');//zero in width means till the end of row
			$txt = iconv('utf-8', 'cp1251', $kschet);
			$this->Cell(0,10,$txt,1,2,'l');
			$txt = iconv('utf-8', 'cp1251', $schet);
			$this->Cell(0,18,$txt,1,1,'l');		
		}
		function printInvoice($array)
		{
			//head
			$txt = iconv('utf-8', 'cp1251', '№');	
			$this->Cell(10,7,$txt,1,0,'C');
			$txt = iconv('utf-8', 'cp1251', 'Товар');
			$this->Cell(100,7,$txt,1,0,'C');
			$txt = iconv('utf-8', 'cp1251', 'Кол-во');
			$this->Cell(20,7,$txt,1,0,'C');
			$txt = iconv('utf-8', 'cp1251', 'Ед.');
			$this->Cell(10,7,$txt,1,0,'C');
			$txt = iconv('utf-8', 'cp1251', 'Цена');
			$this->Cell(25,7,$txt,1,0,'C');
			$txt = iconv('utf-8', 'cp1251', 'Сумма');
			$this->Cell(25,7,$txt,1,1,'C');
			$this->SetFont('ArialTM','',8);
			$total=0;
			for($i=0;$i<sizeof($array);$i++)
			{	
				$this->Cell(10,5,$i+1,1,0,'C');
				$txt = iconv('utf-8', 'cp1251', $array[$i][1]);
				$this->Cell(100,5,$txt,1,0,'L');
				$txt = iconv('utf-8', 'cp1251', $array[$i][2]);
				$this->Cell(20,5,$txt,1,0,'R');
				$txt = iconv('utf-8', 'cp1251', 'Шт');
				$this->Cell(10,5,$txt,1,0,'C');
				$txt = iconv('utf-8', 'cp1251', $array[$i][3]);
				$this->Cell(25,5,$txt,1,0,'R');
				$txt = iconv('utf-8', 'cp1251', number_format($array[$i][4],2));
				$this->Cell(25,5,$txt,1,1,'R');			
				$total+=$array[$i][4];
			}
			$this->Ln(2);
			$this->SetFont('ArialTM','',12);
			$txt = iconv('utf-8', 'cp1251', 'Итого: ');
			$txt = $txt. number_format($total,2);
			$this->Cell(0,7,$txt,0,1,'R');
			$txt = 'Всего наименований '.$i.' на сумму '.number_format($total,2).' руб.';
			$this->SetFont('ArialTM','',10);
			$txt = iconv('utf-8', 'cp1251', $txt);
			$this->Cell(0,7,$txt,0,1,'L');
			$this->Ln(2);
			$this->Cell(0,0,'',1,1,'l');
			$this->Ln(10);
			$this->SetFont('ArialTM','',12);
			$txt = iconv('utf-8', 'cp1251', 'Руководитель:_________________');
			$this->Cell(68,7,$txt,0,0,'L');
			$this->SetFont('ArialTM','',8);
			$txt = iconv('utf-8', 'cp1251', '(Щербина Э.И.)');
			$this->Cell(30,7,$txt,0,0,'L');
			$this->SetFont('ArialTM','',12);
			$txt = iconv('utf-8', 'cp1251', 'Бухгалтер:____________________');
			$this->Cell(68,7,$txt,0,0,'L');
			$this->SetFont('ArialTM','',8);
			$txt = iconv('utf-8', 'cp1251', '(Щербина Э.И.)');
			$this->Cell(30,7,$txt,0,1,'L');
		}		
		function  printOrderHead($txt,$txt2){
			$this->SetFont('ArialTM','',14);
			$txt="Заказ № ".$txt;
			$txt2="Адрес: ".$txt2;
			$txt = iconv('utf-8', 'cp1251', $txt);
			$txt2 = iconv('utf-8', 'cp1251', $txt2);
			$this->Cell(50,8,$txt,1,0,'l');
			$this->Cell(0,8,$txt2,1,1,'l');
		}
		function  printCakes($array){
			
			foreach($array as $key=>$value){
				$this->Cell(0,1,'',1,1,'l');
				$this->SetFont('ArialTM','',12);
				$this->Cell(5,5,$key+1,1,0,'l');
				$txt=$value['name'];				
				$txt = iconv('utf-8', 'cp1251', $txt);				
				$this->Cell(80,5,$txt,1,0,'l');
				$this->SetFont('ArialTM','',10);
				$txt=$value['qty']." шт.";
				$txt = iconv('utf-8', 'cp1251', $txt);
				$this->Cell(20,5,$txt,1,0,'l');
				$txt="Вес,kg: ".$value['weight'];
				$txt = iconv('utf-8', 'cp1251', $txt);
				$this->Cell(40,5,$txt,1,0,'l');
				if($value['cut']){
					$txt = "Делаем нарезку";
				}else $txt = "Без нарезки";
				$txt = iconv('utf-8', 'cp1251', $txt);
				$this->Cell(0,5,$txt,1,1,'l');
				$this->SetFont('ArialTM','',8);
				$txt="Надпись: ".$value['text'];
				$txt = iconv('utf-8', 'cp1251', $txt);
				$this->Cell(100,5,$txt,1,0,'l');
				$txt="Коммент: ".$value['comment'];
				$txt = iconv('utf-8', 'cp1251', $txt);
				$this->Cell(0,5,$txt,1,1,'l');
				
			}
		}
	}

	$pdf = new PDF();
	$pdf->AddFont('ArialTM','','arial.php'); 
	$pdf->AliasNbPages();
	$pdf->AddPage();
	foreach($ordersArray as $smallArray){
		$txtID = $smallArray['id'];
		$txtName = $smallArray['name'];
		$pdf->printOrderHead($txtID,$txtName);
		$pdf->printCakes($cakesArray[$txtID]);
		$pdf->Cell(0,1,'',1,1,'l',true);
		$pdf->Ln(3);		
	}
	// exit();	
	$pdf->Output();
	exit();	
	$txt = $newArray;
	$pdf->printText($txt,8,'C');
	$pdf->Ln(3);
	$txt = 'Образец заполнения платежного поручения';
	$pdf->printText($txt,12,'C');
	$bank = 'ФИЛИАЛ "С-ПЕТЕРБУРГСКАЯ ДИРЕКЦИЯ ОАО "УРАЛСИБ" г. Санкт-Петербург Инженерная ,9';
	$inn = '781703404129';
	$beneficiary ='ООО Селтекс';
	$bik = '044030706';
	$kschet = '30101810800000000706';
	$schet = '40802810522080000030';
	$pdf->printTable($bank, $inn, $beneficiary, $bik, $kschet, $schet);
	// $pdf->Ln(5);
	// $txt = 'Счет № '.$schetNum.' от ' .$date;
	// $pdf->printText($txt,18,'L');
	// $pdf->Ln(2);
	// $pdf->Cell(0,0,'',1,1,'l');
	// $pdf->Ln(2);
	// $txt = 'Поставщик: ООО Селтекс';
	// $pdf->printText($txt,12,'L');
	// $pdf->Ln(2);
	// $txt = 'Покупатель: '.$agent;
	// $pdf->printText($txt,12,'L');
	// $pdf->Ln(2);
	// $pdf->printInvoice($array);
	$pdf->Output();
?>