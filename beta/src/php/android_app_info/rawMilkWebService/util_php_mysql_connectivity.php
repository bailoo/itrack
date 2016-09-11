<?php
    $DBASE = "iespl_vts_beta";
	//include_once("PhpMysqlConnectivity.php");
    $USER = "nimbumirchidb";
    //$USER = "root";
    $PASSWD = 'nm#db0516';
    //$PASSWD = 'mysql';
    //$HOST = "nimbumirchi.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
    $HOST='';
    $DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("could not connect to DB test");
    mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
?>
