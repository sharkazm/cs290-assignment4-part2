<?php
ini_set('display_errors', 1);
header('Content-Type: text/plain');

$errorFree = true;

if(isset($_POST)){
    $name = $_POST['name'];
    $catagory = $_POST['catagory'];
    $length = $_POST['catagory'];
    echo "$name";
    if(!(strlen($name) > 0)){
    	echo "Error, name missing.\n";
    	$errorFree = false;
    }
    if(!(strlen($catagory) > 0)){
    	echo "Error, catagory missing.\n";
		$errorFree = false;
    }
    if($_POST['length'] == NULL){
		echo "Error, length missing.";
		$errorFree = false;
    }
}

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "herrinas-db", "3JCPnCFTmsZs8ASZ", "herrinas-db");
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error ".$mysqli->connect_errno . "".$mysqli->connect_error;
}

if($errorFree == true){
	
	//3 stages of $mysqli prep. taken from pnp.net manual
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


?>