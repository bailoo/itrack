<?php
    $DBASE = "iespl_vts_beta";
    $USER = "nimbumirchidb";
    //$USER = "root";
    $PASSWD = 'nm#db0516';
    //$PASSWD = 'mysql';
    $HOST = "nimbumirchi.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
    //$HOST = "localhost";
    $DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
    mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
?>
