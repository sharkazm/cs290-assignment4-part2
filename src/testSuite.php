<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">      
	<title>Failbuster Video</title>  
	<style>
		table, td, th{border-style:solid; border-width: medium; border-collapse: collapse; width: 800px; text-align: center;};
	</style>
</head>
  
<body style="background-color:grey">

	<div id="newVideo" style="float:right; background-color: white; padding:10px; margin:20px">
		<H4>ADD NEW INVENTORY:</H4>
		<form action="testSuite.php" method='POST'>
			Please enter the video's name:<br>
			<input type='text' name='name'><br>
			Please enter the new video's length:<br>
			<input type='number' name='length' min='0'><br>
			Please enter the new video's genre:<br>
			<input type= 'text' name='catagory'> <br><br>
			<input type= 'hidden' name='type' value='add'>
			<input type='submit' value ='Add new video' />
		</form><br>
			<form action="testSuite.php" method='GET'>
			<input type='hidden' name='clear' value='all'>
			<input type='submit' value="Clear inventory">
		</form>
	</div>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$errorFree = true;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if($_POST['type'] == 'add'){
	    $name = $_POST['name'];
	    $catagory = $_POST['catagory'];
	    $length = $_POST['length'];
	    echo "$name";
	    if(!(strlen($name) > 0)){
	    	echo "Error, Video Name is a required field.\n";
	    	$errorFree = false;
	    }
	    if(!is_numeric($length) && ($length == (int)$length) && !$length){
	    	echo "Error, invalid length. \n";
	    	$errorFree = false;
	    }
	}
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if($_POST['type'] == 'remove'){
	  	
		$deleteName = $_POST['ToRemove'];
	  	$conn=mysqli_connect("oniddb.cws.oregonstate.edu","herrinas-db","3JCPnCFTmsZs8ASZ","herrinas-db");
    // Check connection
    	if (mysqli_connect_errno($con)){
    			echo "Failed to connect to MySQL: " . mysqli_connect_error();
    	}    
		// sql to delete a record
		$sql = "DELETE FROM video_inventory WHERE name='$deleteName'";

		if ($conn->query($sql) === TRUE) {
		    echo "Record deleted successfully";
		} else {
		    echo "Error deleting record: " . $conn->error;
		}

		$conn->close(); 
	}
}

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "herrinas-db", "3JCPnCFTmsZs8ASZ", "herrinas-db");
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error ".$mysqli->connect_errno . "".$mysqli->connect_error;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if($_POST['type'] == 'edit'){
		$editName = $_POST['ToEdit'];
		$inOrOut = $_POST['stockStatus'];
		if($inOrOut == 'Available')
			$changeRented = 'out';
		else
			$changeRented = 'in';
		
		$lineInput = "UPDATE video_inventory SET rented='$changeRented' WHERE name='$editName'";
	if ($mysqli->query($lineInput) === TRUE) {
    		echo "Record updated successfully";
		} 
		else {
		    echo "Error updating record: " . $mysqli->error;
		}  
	}
}

if($errorFree == true && $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] == 'add'){
	
	//3 stages of $mysqli prep. taken from pnp.net manual
	
	if((strlen($catagory) > 0) && ($_POST['length'] == NULL)){
		
		if (!($stmt = $mysqli->prepare("INSERT INTO video_inventory(name, catagory) VALUES (?, ?)"))) {
		    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}	
		if (!$stmt->bind_param("ss", $name, $catagory)) {
	    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->execute()) {
	    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
	}
	
	else if((strlen($catagory) > 0) && ($_POST['length'] != NULL)){
		
		if (!($stmt = $mysqli->prepare("INSERT INTO video_inventory(name, length, catagory) VALUES (?, ?, ?)"))) {
		    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}	
		if (!$stmt->bind_param("sis", $name, $length, $catagory)) {
	    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->execute()) {
	    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}

	}

	else if((strlen($catagory) == 0) && ($_POST['length'] == NULL)){

		if (!($stmt = $mysqli->prepare("INSERT INTO video_inventory(name) VALUES (?)"))) {
		    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}	
		if (!$stmt->bind_param("s", $name)) {
	    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->execute()) {
	    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}

	}

	else if((strlen($catagory) == 0) && ($_POST['length'] != NULL)){

		if (!($stmt = $mysqli->prepare("INSERT INTO video_inventory(name, length) VALUES (?, ?)"))) {
		    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}	
		if (!$stmt->bind_param("si", $name, $length)) {
	    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		if (!$stmt->execute()) {
	    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}

	}
}


function deleteTable(){
	//code taken from http://stackoverflow.com/questions/15947536/clear-all-the-entries-from-a-table-with-php user siddharth
 	$con=mysqli_connect("oniddb.cws.oregonstate.edu","herrinas-db","3JCPnCFTmsZs8ASZ","herrinas-db");
    // Check connection
    if (mysqli_connect_errno($con))
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }    
    $sql = "TRUNCATE TABLE video_inventory";
    mysqli_query($con, $sql) or die(mysqli_error());
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){
	if($_GET['clear'] == 'all')
		deleteTable();
}

echo "<br><br>
	<div id='DynamicSection' style='background-color:white ; width:99%; height:1500px; display: inline-block;  padding:10px; margin-all:20px'>

		<form action= 'testSuite.php' method='POST'>
			<select name='var' onchange='this.form.submit()';>
			<select>
		</form>

		<table><caption>Failbuster Video Inventory</caption>
			<tr><th>Name<th>Catagory<th>Length<th>Status</th>";

	$categoryArray = array();

	$selection = "SELECT name, catagory, length, rented FROM video_inventory";
	
	$queryResults = $mysqli->query($selection);
	$cats = $mysqli->fetch_assoc($queryResults);


	foreach($categories as $catagory){
		echo '$catagory';
	}


	while($row = $queryResults->fetch_row()){
		if($row[3] == "in")
			$inStock = "Available";
		else
			$inStock = "Checked Out";

		echo "<tr><td>$row[0]<td>$row[1]<td>$row[2]";
		$editName = $row[0];
		echo "<td><form action= 'testSuite.php' method='POST'><input type= 'hidden' name='type' value='edit'>
			<input type='hidden' name='ToEdit' value='$editName'><input type='hidden' name='stockStatus' value='$inStock'><input type='submit' value='$inStock'>
		 	</form>";
		$removeName = $row[0];
		echo "<td><form action= 'testSuite.php' method='POST'><input type= 'hidden' name='type' value='remove'>
			<input type='hidden' name='ToRemove' value='$removeName'><input type='submit' value='Delete'>
		 	</form></td></tr>";
	}	
?>
		</table>
	
	</div>

</body>

<html>




