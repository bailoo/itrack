<?php
	//include_once("PhpMysqlConnectivity.php");
	$HOST = "localhost";
	$DBASE = "iespl_vts_beta";
	$USER = "root";
	$PASSWD = "mysql";
	$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("could not connect to DB test");
	mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
?>
