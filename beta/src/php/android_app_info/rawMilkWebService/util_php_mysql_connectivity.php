<?php
	//include_once("PhpMysqlConnectivity.php");
	$HOST = "itrackdb.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
	$DBASE = "iespl_vts_beta";
	$USER = "bailoo";
	$PASSWD = "neon04$VTS";
	$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("could not connect to DB test");
	mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
?>
