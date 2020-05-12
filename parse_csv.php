<?php

include 'db.php';

if ( isset($storagename) && $file = fopen( "upload/" . $storagename , r ) ) {

    $firstline = fgets ($file, 4096 );
        //Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
    $num = strlen($firstline) - strlen(str_replace(",", "", $firstline));

        //save the different fields of the firstline in an array called fields
    $fields = array();
    $fields = explode( ",", $firstline, ($num+1) );

    $line = array();
    $i = 0;

        //CSV: one line is one record and the cells/fields are seperated by ";"
        //so $dsatz is an two dimensional array saving the records like this: $dsatz[number of record][number of cell]
    while ( $line[$i] = fgets ($file, 4096) ) {

        $dsatz[$i] = array();
        $dsatz[$i] = explode( ",", $line[$i], ($num+1) );

        $i++;
    }


	$conn = new mysqli($dbserver, $username, $password, $database);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	
	foreach ($dsatz as $key => $number) {
		
	
	 $broken_date = explode( "/", $number[0]);
	 $date_formatted = substr($broken_date[2],0,4).'/'.$broken_date[0].'/'.$broken_date[1].substr($broken_date[2],4);
	 
	 
     $sql = 'INSERT INTO Transactions (date_sold, order_num, item_name, order_id, buyer, recipient, sale_price, mp_comission, net_price, location, store) VALUES ("'.$date_formatted.'", '.$number[1].', "'.$number[3].'", '.$number[4].', "'.$number[5].'", "'.$number[6].'", '.$number[7].', '.$number[9].', '.$number[11].', "Marketplace", "'.$_POST["store"].'")';
	 //echo $sql;
	 //echo '<br>';
	
	
	 $conn->query($sql);
	
    }
	
	//db_disconnect($db);
    
}
 
 ?>