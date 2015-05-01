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
	
	$imei = '862170011627815';
	$st_results = dbQueryLastSeen($o_cassandra,$imei,'2015-01-29');
	
	//$last_params = array('a','b','c','d','e','f','g','i','j','k','l','m','n','o','p','q','r','s','t','u','ci','ax','ay','az','mx','my','mz','bx','by','bz');
	//$params = array('a','b','c','d','e','f','h','i','j','k','l','m','n','o','p','q','r','s','t','u','ci','ax','ay','az','mx','my','mz','bx','by','bz');
	$params = array('d','e','h');
	$st_obj = gpsParser($st_results,$params,FALSE);
	print_r($st_obj);
		
	$st_results = dbQueryLastSeen($o_cassandra,$imei,'2015-01-29');
	$st_obj = gpsParser($st_results,$params,FALSE);
	print_r($st_obj);

	$st_results = dbQueryLastSeen($o_cassandra,$imei,'2015-02-29');
	$st_obj = gpsParser($st_results,$params,FALSE);
	print_r($st_obj);

	$st_results = dbQueryLastSeen($o_cassandra,$imei,'2015-06-19');
	$st_obj = gpsParser($st_results,$params,FALSE);
	print_r($st_obj);

	$st_results = dbQueryLastSeen($o_cassandra,$imei,'2015-11-09');
	$st_obj = gpsParser($st_results,$params,FALSE);
	print_r($st_obj);

	$o_cassandra->close();
	
?>	
