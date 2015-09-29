<?php
$HOST = "itrackdb.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
$DBASE = "iespl_vts_beta";
$USER = "bailoo";
$PASSWD = 'neon04$VTS';
    //Open a new connection to the MySQL server
$mysqli = new mysqli($HOST,$USER,$PASSWD,$DBASE);

//$mysqliP = new E_mysqli($HOST,$USER,$PASSWD,$DBASE);

//Output any connection error
if ($mysqli->connect_error) 
{
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
	//include_once("PhpMysqlConnectivity.php");
	
//$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("could not connect to DB test");
//mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
?>