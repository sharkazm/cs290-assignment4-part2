<?php
ini_set('display_errors', 1);
echo '<html>
<head>
<title>Content 1 PHP</title>
<script src="assignment3.js"></script>
</head>
<body>';

echo "Test test";

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "herrinas-db", "3JCPnCFTmsZs8ASZ", "herrinas-db");
if(!$mysqli || $mysqli->connect_errno){
	echo "Connection error ".$mysqli->connect_errno . "".$mysqli->connect_error;
}


echo "<form>
<input type='button' value='Add new video' onclick='input_video()' />
</form>";

echo '<div id="dynamicSection">I should see more here: </div>';


echo '</body>
</html>'; 
?>