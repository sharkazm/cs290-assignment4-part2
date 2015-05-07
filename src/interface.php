<?php
ini_set('display_errors', 1);
header('Content-Type: text/plain');

$errorFree = true;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $catagory = $_POST['catagory'];
    $length = $_POST['length'];
    echo "$name";
    if(!(strlen($name) > 0)){
    	echo "Error, Video Name is a required field.\n";
    	$errorFree = false;
    }
   /* if(!(strlen($catagory) > 0)){
    	echo "Error, catagory missing.\n";
		$errorFree = false;
    }
    if($_POST['length'] == NULL){
		echo "Error, length missing.\n";
		$errorFree = false;
    } */
}

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "herrinas-db", "3JCPnCFTmsZs8ASZ", "herrinas-db");
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error ".$mysqli->connect_errno . "".$mysqli->connect_error;
}

if($errorFree == true && $_SERVER['REQUEST_METHOD'] == 'POST'){
	
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

?>