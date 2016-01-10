<?php
	$postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
	$author = (int)$request->author;
    $customer = (string)$request->customer;
	$address = $request->address;
	$date =  (string)$request->dateStr;
	$time = $request->time;
	$place = $request->place;
	$cakes = $request->cakes;
	$id = (int)$request->id;
	if($id){
		require '../../../pdodbconnect.php';
		$q=$dbh->query("call orderChange($address,'$customer','$date','$time', $id);");
		$q->closeCursor();
		foreach($cakes as $index => $cake)
		{
			$qty = $cake->qty;
			$cakeID = $cake->cake;
			$weight = $cake->weight;
			$cut = $cake->cut;
			$text = $cake->text;
			$comment = $cake->comment;
			$q=$dbh->query("insert into orderDetails (orderID, cake, qty, weight, cut, text, comment) values 
				($id,$cakeID,$qty,'$weight','$cut','$text','$comment')");
			//foreach($cake as $key => $value){$arrTest[$key]=$value;}// test of what was in cakes
		}
		$q->closeCursor();
		$q= null;
		$dbh = null;
		//exit();
		//this below is to output data for test
		$json_response=array();
		array_push($json_response,$id);
		echo json_encode($json_response);
	}
	else{
		require '../../../pdodbconnect.php';
		$q=$dbh->query("call orderAddNew($author,$address,'$customer','$date','$time');");
		$q->setFetchMode(PDO::FETCH_ASSOC);
		while ($row = $q->fetch())
		{
			$id = (int)$row['id'];
			$date = $row['date'];
		}	
		$q->closeCursor();
		foreach($cakes as $index => $cake)
		{
			$qty = $cake->qty;
			$cakeID = $cake->cake;
			$weight = $cake->weight;
			$cut = $cake->cut;
			$text = $cake->text;
			$comment = $cake->comment;
			$q=$dbh->query("insert into orderDetails (orderID, cake,qty, weight, cut, text, comment) values 
				($id,$cakeID,$qty,'$weight','$cut','$text','$comment')");
			//foreach($cake as $key => $value){$arrTest[$key]=$value;}// test of what was in cakes
		}
		$q->closeCursor();
		$q= null;
		$dbh = null;
		//exit();
		//this below is to output data for test
		$json_response=array();
		array_push($json_response,$id);
		array_push($json_response,$date);
		echo json_encode($json_response);
	}
?>