<?php
	$HOST = "localhost";
	$DBASE = "iespl_vts_beta";
	$USER = "root";
	$PASSWD = "mysql";
	$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("UNABLE TO CONNECT");
	mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
?>
