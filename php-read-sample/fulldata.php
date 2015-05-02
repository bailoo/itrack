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
	$dateminute1 = '2015-01-29-00-00';
	$dateminute2 = '2015-01-30-23-30';
	//echo "dateminute1 = $dateminute1\n dateminute2 = $dateminute2\n";
	
	//make sure the imeih exist in cassandra
	//$st_results = dbQueryDateHour($o_cassandra,$imei,$date,$HH);
	
	$st_results = dbQueryDateTimeSlice($o_cassandra,$imei,$dateminute1,$dateminute2);

	//$full_params = array('a','b','c','d','e','f','i','j','k','l','m','n','o','p','q','r','ci','ax','ay','az','mx','my','mz','bx','by','bz');
	$params = array('a','b','c','d','ax','ay','az','ci');
	$st_obj = gpsParser($st_results,$params,TRUE);
	print_r($st_obj);
		

	//printHTML($st_results);
	// echo 'Execution time: '.$i_execution_time."\n";
	
	$o_cassandra->close();

?>
