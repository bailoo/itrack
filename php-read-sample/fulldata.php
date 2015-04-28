<?php

	require_once 'Cassandra/Cassandra.php';
	require_once 'libGPS.php';
	
	$o_cassandra = new Cassandra();
	
	$s_server_host     = '52.74.33.255';    // Localhost
	$i_server_port     = 9042; 
	$s_server_username = '';  // We don't have username
	$s_server_password = '';  // We don't have password
	$s_server_keyspace = 'gps';  
	
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	
	
	//$imei = '862170018323731';
	//$imei = '862170018378487';
	//$imei = '861074025248991';
	$imei = '862170011627815';
	$dateminute1 = '2015-01-29-04-00';
	$dateminute2 = '2015-01-29-23-00';
	//echo "dateminute1 = $dateminute1\n dateminute2 = $dateminute2\n";
	
	//make sure the imeih exist in cassandra
	//$st_results = DBQueryDateHour($o_cassandra,$imei,$date,$HH);
	
	$st_results = DBQueryDateTimeSlice($o_cassandra,$imei,$dateminute1,$dateminute2);

	//$params = array('a','b','c','d','e','f','g','i','j','l','m','o','p','r');
	$params = array('a','b','c','d');
	$st_obj = gpsParser($st_results,$params);
	print_r($st_obj);
		

	//printHTML($st_results);
	// echo 'Execution time: '.$i_execution_time."\n";
	
	$o_cassandra->close();

?>
