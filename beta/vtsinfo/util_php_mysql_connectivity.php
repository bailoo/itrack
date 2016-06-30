<?php
    //include_once("PhpMysqlConnectivity.php");
    $HOST = "nimbumirchi.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
    $DBASE = "iespl_vts_beta";
    $USER = "nimbumirchidb";
    $PASSWD = 'nm#db0516';
    $DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("could not connect to DB test");
    mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
?>