<?php
	$DBASE = "iespl_vts_beta";
	$USER = "bailoo";
        //$USER = "root";
	$PASSWD = 'neon04$VTS';
        //$PASSWD = 'mysql';
	$HOST = "itrackdb.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
        //$HOST = "localhost";
	$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
	mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
?>