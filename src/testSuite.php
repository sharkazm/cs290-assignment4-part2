<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">      
	<title>Failbuster Video</title>  
	<style>
		table, td, th{border-style:solid; background-color:white;border-width: medium; border-color:blue;border-collapse: collapse; width: 1000px; text-align: center; padding: 10px;};
	</style>
</head>
  
<body style="background-color:grey">
	<div style="float:left; background-color: grey; width:60%; padding-top:25px">
	<img src="Failbuster.png" alt="FailBuster Video">
	</div>
	<div id="newVideo" style="float:right; background-color: white; padding:10px; margin:20px; border-style: solid; border-color:blue">
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

$errorFree = true;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if($_POST['type'] == 'add'){
	    $name = $_POST['name'];
	    $catagory = $_POST['catagory'];
	    $length = $_POST['length'];
	    if(!(strlen($name) > 0)){
	    	echo "Error, Video Name is a required field.\n";
	    	$errorFree = false;
	    }
	    /*if(!is_numeric($length) && ($length == (int)$length) && !$length){
	    	echo "Error, invalid length. $length\n";
	    	$errorFree = false;
	    }*/
	}
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if($_POST['type'] == 'remove'){
	  	
		$deleteName = $_POST['ToRemove'];
	  	$conn=mysqli_connect("oniddb.cws.oregonstate.edu","herrinas-db","3JCPnCFTmsZs8ASZ","herrinas-db");
   
    	if (mysqli_connect_errno($con)){
    			echo "Failed to connect to MySQL: " . mysqli_connect_error();
    	}    
		
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
    		echo "    ";
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
		//if (!$stmt->execute()) {
	    //echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		//}
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
	<div id='DynamicSection' style='background-color:yellow ; width:97%; height:1500px; display: inline-block;  padding:20px; margin-all:20px; border-style: solid; border-color:blue; margin-right:20px'>

		<table><caption><h2><font color='blue'>Failbuster Video Inventory</font></h2></caption>
			<tr><th>Name<th>Catagory<th>Length<th>Status</th>";
	
	
	
	$cats = 'SELECT DISTINCT catagory FROM video_inventory';
	if($row = $mysqli->query($cats)){
		echo "<form action= 'testSuite.php' method='POST'>";
		echo "<input type= 'hidden' name='type' value='filter'>";
		echo "<select name='var' onchange='this.form.submit()'>";
		$all = 'All movies';
		$choice = 'Filter Results';
		echo "<option value='".$choice."'>".$choice."</option>";
		echo "<option value='".$all."'>".$all."</option>";
		while($distinctCatagory = $row->fetch_array(MYSQL_NUM)){
			if(strlen($distinctCatagory[0]) > 0)
			echo "<option value='".$distinctCatagory[0]."'>".$distinctCatagory[0]."</option>";
		}
		echo'</select></form>';
	}

	$selection = "SELECT name, catagory, length, rented FROM video_inventory";
	$specificGenre = false;

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if($_POST['type'] == 'filter' || $_POST['type'] == 'edit'){
			if($_POST['var'] == "All movies"){
				$selection = "SELECT name, catagory, length, rented FROM video_inventory";
			}	
			else{
				$choice = $_POST['var'];
				$selection = "SELECT name, catagory, length, rented FROM video_inventory WHERE catagory='$choice'";
				$specificGenre = true;
			}
		}
	}

	$queryResults = $mysqli->query($selection);
	while($row = $queryResults->fetch_row()){
		if($row[3] == "in")
			$inStock = "Available";
		else
			$inStock = "Checked Out";

		echo "<tr><td>$row[0]<td>$row[1]<td>$row[2]";
		$editName = $row[0];
		echo "<td><form action= 'testSuite.php' method='POST'><input type= 'hidden' name='type' value='edit'>";
		if($specificGenre == false)
			$setGenre = "All movies";
		else
			$setGenre = $_POST['var'];
		echo "<input type='hidden' name='var' value='$setGenre'>";
		echo "<input type='hidden' name='ToEdit' value='$editName'><input type='hidden' name='stockStatus' value='$inStock'><input type='submit' value='$inStock'>
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




