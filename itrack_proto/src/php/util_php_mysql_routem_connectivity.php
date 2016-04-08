<?php
//	$DBASE = "route_management";
$DBASE = "iespl_vts_beta";
	$USER = "root";
	$PASSWD = "mysql";
	$HOST = "localhost";
	$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("could not connect to DB test");
	mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
	$common_date=date("Y-m-d H:i:s");
?>
