<style>

.table_titles, .table_cells_odd, .table_cells_even {
		padding-right: 20px;
		padding-left: 20px;
		color: #000;
}
.table_titles {
	color: #FFF;
	background-color: #666;
}
.table_cells_odd {
	background-color: #CCC;
}
.table_cells_even {
	background-color: #FAFAFA;
}
table {
	border: 2px solid #333;
}




/* Style the tab */
.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons that are used to open the tab content */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
} 
</style>


<?php


function get_stores() {
	//Execute the SQL
	include 'db.php';

	$conn = new mysqli($dbserver, $username, $password, $database);

	if ($conn->connect_error) {
		die("You need to configure your database information in db.php.   Connection failed: " . $conn->connect_error);
	}
	
	$sql = 'SELECT * FROM `stores` ORDER BY "store_name"';
	
    $result = $conn->query($sql);
	
	return $result;
	
}

function process_csv() {
	
	include 'db.php';

	if ( isset($storagename) && $file = fopen( "upload/" . $storagename , r ) ) {

    //echo "File opened.<br />";

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
	 
	 
     $sql = 'INSERT INTO Transactions (date_sold, order_num, item_name, order_id, buyer, recipient, sale_price, mp_comission, net_price, location, store) VALUES ("'.$date_formatted.'", '.$number[1].', "'.$number[3].'", '.$number[4].', "'.$number[5].'", "'.$number[6].'", '.$number[7].', '.$number[9].', '.$number[10].', "Marketplace", "'.$_POST["store"].'")';
	 //echo $sql
	 //echo '<br>';
	
	
	 $conn->query($sql);
	
    }
	
	//db_disconnect($db);
    
}}

function get_store_data($sql) {

	$store_data = [];
	
	//Execute the SQL
	include 'db.php';

	$conn = new mysqli($dbserver, $username, $password, $database);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
    $result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// process every record
		while($row = $result->fetch_assoc())
		{
			$store_data[] = $row;
		}}
	else {
		$store_data = "Database Error";
	}
	
	
	return $store_data;
	
}

function displayresults($sales_data) {
	
	//foreach ($sales_data AS $tempvar) {
	//foreach ($tempvar AS $row) {
	//echo $row; }}
	
	
	echo '<h3>Second Life Sales Data</h3>	
	
	<table class="js-sort-table" border="0" cellspacing="0" cellpadding="4" >
      <thead><tr>
            <th class="table_titles">Date</th>
            <th class="js-sort-string table_titles">Item Name</th>
            <th class="js-sort-string table_titles">Buyer/Reciever</th>
            <th class="table_titles">Price</th>
			<th class="js-sort-string table_titles">Net</th>
			<th class="js-sort-string table_titles">Location</th>
			<th class="js-sort-string table_titles">Store</th>
      </tr></thead>
	<tbody>';
	
	$oddrow = TRUE;
	
    foreach ($sales_data AS $row) {
		
			if ($oddrow){$css_class=' class="table_cells_odd"'; }
			else { $css_class=' class="table_cells_even"'; }

			$oddrow = !$oddrow;
			echo '<tr>';
			echo '<td'.$css_class.'>'.$row["date_sold"].'</td>';
			echo '<td'.$css_class.'>'.$row["item_name"].'</td>';
			echo '<td'.$css_class.'>'.$row["buyer"].' / '.$row["recipient"].'</td>';
			echo '<td'.$css_class.'>'.$row["sale_price"].'</td>';
			echo '<td'.$css_class.'>'.$row["net_price"].'</td>';
			echo '<td'.$css_class.'>'.$row["location"].'</td>';
			echo '<td'.$css_class.'>'.$row["store_name"].'</td>';
			echo '</tr>';
		}
	echo '</tbody></table>'; }


$file_available = 0;

if ( isset($_POST["submit"]) ) {
	
   if ( isset($_FILES["file"]) && isset($_POST["store"])) {

            //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        }
        else {
             //Print file details
             // echo "Upload: " . $_FILES["file"]["name"] . "<br />";
             // echo "Type: " . $_FILES["file"]["type"] . "<br />";
             //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
             //echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

                 //if file already exists
             if (file_exists("upload/" . $_FILES["file"]["name"])) {
            echo $_FILES["file"]["name"] . " already exists. ";
             }
             else {
                    //Store file in directory "upload" with the name of "uploaded_file.txt"
            $storagename = "uploaded_file.txt";
            move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
            // echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />";
			
			$file_available = 1;
            }
        }
     } else {
             echo "No file selected <br />";
     }
 
}

if ($file_available == 1) {
	// echo "<br>Time to Process the File. <br>";
	//process_csv();	
	include 'parse_csv.php';
	$file_available = 0;
	echo "File Uploaded and Processed. <br>";
}

$store_names = get_stores(); 


$sql = 'SELECT *, stores.store_name FROM `Transactions` JOIN stores ON Transactions.store = stores.id ORDER BY `date_sold` ';
$sales_data = get_store_data($sql);
$numpages= ceil(sizeof($sales_data)/50);

$total_sales =0;

foreach ($sales_data AS $row) {
	$total_sales += $row["net_price"]; }



//Tablinks

echo '<div class="tab">';
echo '<button class="tablinks" onclick="openBinderTab(event, \'MainTab\')"  id="defaultOpen">Summary</button>';
  for ($i=1; $i<$numpages; $i++) {
	echo '<button class="tablinks" onclick="openBinderTab(event, \'Tab'.$i.'\')">'.$i.'</button>';  }
echo '</div>';
?>




<!-- Tab content -->
<div id="MainTab" class="tabcontent">


<h3>Upload CSV File</h3><br>
<table width="600">
<form action="index.php" method="post" enctype="multipart/form-data">

<tr>
<td width="20%">Select CSV File</td>
<td width="80%"><input type="file" name="file" id="file" accept=".csv" /></td>
</tr>
<tr>
<td>Select Store</td>
<td>
<select name = "store" id="store">
			<option value = "NULL" selected> </option>
            <?php 
				foreach($store_names as $row) {
					echo '<option value = "'.$row['id'].'">'.$row['store_name'].'</option>';
				}
            ?>
         </select>
</td></tr>
<tr>
<td>Submit</td>
<td><input type="submit" name="submit" /></td>
</tr>

</form>
</table>
<br> 
<br>
<?php

 echo 'Total Sales: '.$total_sales.'L$<br>'; 
 echo 'Approx USD:  $'.($total_sales/250).'<br>'; 
?> 
<br> 
<br> 
<br> 
<br> 


</div>




	<?php 
	$start=0;
	
	for ($j=1; $j<$numpages; $j++) {
		echo '<div id="Tab'.$j.'" class="tabcontent">';
		$subArray = array_slice($sales_data,$start,$start+50);
		displayresults($subArray);
		$start=$start+51; 
		echo '</div>';
		}

		
    ?>













<script>

function openBinderTab(evt, BinderTab) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(BinderTab).style.display = "block";
    evt.currentTarget.className += " active";
} 

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>