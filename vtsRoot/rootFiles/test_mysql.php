<?php
/*$servername = "localhost";
$username = "root";
$password = 'neon04$VTS';

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
{
echo "Connected successfully";
}*/


	$DBASE = "iespl_vts_beta";
	$USER = "root";
	$PASSWD = 'neon04$VTS';

	$HOST = "localhost";
	$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
	mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
?> 
