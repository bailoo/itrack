<?php

require_once 'Cassandra/Cassandra.php';
require_once 'libLog.php';


$o_cassandra = new Cassandra();

$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
$imei = "222333444";
$last_data_string = "N;v1.45C;1;46.25148;39.86157;0.06;2;5;3;5;6;6;3;5;0;12.88;abcd;1;0;0;1;0;0;1;0;0";
$full_data_string = "N;v1.45C;1;26.25858;79.82557;0.06;2016-01-27@15:56:09;2;5;3;5;6;6;3;5;0;12.88;21;13:09:10;20:20:20;abcd;1;0;0;1;0;0;1;0;0";
$DeviceTime = date('Y-m-d H:i:s');
insert_full_data($o_cassandra, $imei, $DeviceTime, $full_data_string);
insert_data_last($o_cassandra, $imei, $last_data_string);
$o_cassandra->close();

function insert_last_data($o_cassandra, $imei, $data) {

    $STime = date('Y-m-d H:i:s');
    $s_cql = "INSERT INTO lastlog (imei, stime, data) VALUES ('$imei','$STime','$data')";
    echo "QUERY=".$s_cql;
    try{
    $st_results = $o_cassandra->query($s_cql);
    }catch(Exception $e){ echo "Error:".$e;}
}

function insert_full_data($o_cassandra, $imei, $DeviceTime, $data) {

    $STime = date('Y-m-d H:i:s');
    $Date = explode(' ',$DeviceTime);
    echo "\nD1=".$Date[0]." ,D2=".$Date[1];
    $s_cql1 = "INSERT INTO log1 (imei, date, dtime, data, stime) VALUES ('$imei','$Date[0]','$DeviceTime','$data','$STime')";
    echo "QUERY=".$s_cql1;
    try{
    $st_results = $o_cassandra->query($s_cql1);
    }catch(Exception $e){ echo "Error:".$e;}
    
    $s_cql2 = "INSERT INTO log2 (imei, date, dtime, data, stime) VALUES ('$imei','$Date[0]','$DeviceTime','$data','$STime')";
    echo "QUERY=".$s_cql2;
    try{
    $st_results = $o_cassandra->query($s_cql2);
    }catch(Exception $e){ echo "Error:".$e;}    
}
?>	
