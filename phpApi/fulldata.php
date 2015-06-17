<?php

	require_once 'Cassandra/Cassandra.php';
	require_once 'libGPS.php';
	
	$o_cassandra = new Cassandra();
	
	$s_server_host     = '52.74.33.255';
	//$s_server_host     = '127.0.0.1';    // Localhost
	$i_server_port     = 9042; 
	$s_server_username = '';  // We don't have username
	$s_server_password = '';  // We don't have password
	$s_server_keyspace = 'gps';  
	
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	
	
	$imei = '865733021570015';
	$datetime1 = '2015-06-17 10:00:00';
	$datetime2 = '2015-06-17 10:10:00';
	//$imei = '359231030125239';
	//$datetime1 = '2014-12-31 00:00:00';
	//$datetime2 = '2015-01-01 23:59:59';
	$deviceTime = TRUE;	
	$st_results = getImeiDateTimes($o_cassandra, $imei, $datetime1, $datetime2, $deviceTime);

	//$full_params = array('a','b','c','d','e','f','i','j','k','l','m','n','o','p','q','r','ci','ax','ay','az','mx','my','mz','bx','by','bz');
	$params = array('a','b','c','d','ax','ay','az','ci');
	$dataType = TRUE;	// TRUE for fulldata, otherwise lastdata
	$orderAsc = FALSE;	// TRUE for ascending, otherwise descending (default) 
	$st_obj = gpsParser($st_results, $params, $dataType, $orderAsc);
	print_r($st_obj);
		

	//printHTML($st_results);
	//echo 'Execution time: '.$i_execution_time."\n";
	
	$o_cassandra->close();

?>
