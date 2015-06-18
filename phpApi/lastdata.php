<?php

	require_once 'Cassandra/Cassandra.php';
	require_once 'libLog.php';
	
	
	$o_cassandra = new Cassandra();
	
	$s_server_host     = '52.74.33.255';    // itrackcass1 
	//$s_server_host     = '127.0.0.1';    // Localhost
	$i_server_port     = 9042; 
	$s_server_username = '';  // We don't have username
	$s_server_password = '';  // We don't have password
	$s_server_keyspace = 'gps';  
	
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	
	$imei = '865733021570015';
	$st_results = getLastSeen($o_cassandra,$imei);
	
	//$last_params = array('a','b','c','d','e','f','g','i','j','k','l','m','n','o','p','q','r','s','t','u','ci','ax','ay','az','mx','my','mz','bx','by','bz');
	print_r($st_results);
		
	$imei = '865733021570015';
	$st_results = getLastSeenDateTime($o_cassandra,$imei,"2015-06-18 20:55:20");
		
	//$full_params = array('a','b','c','d','e','f','i','j','k','l','m','n','o','p','q','r','ci','ax','ay','az','mx','my','mz','bx','by','bz');
	print_r($st_results);
		
	$o_cassandra->close();
	
?>	
