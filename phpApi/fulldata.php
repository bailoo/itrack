<?php

	require_once 'Cassandra/Cassandra.php';
	require_once 'libLog.php';
	
	$o_cassandra = new Cassandra();
	
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	
	
	$imei = '862170018383602';
	$datetime1 = '2015-08-08 23:59:50';
	$datetime2 = '2015-08-09 00:00:10';
	$deviceTime = TRUE;	// TRUE for query on index dtime, otherwise stime	
	$orderAsc = TRUE;	// TRUE for ascending, otherwise descending (default) 

	$st_results = getImeiDateTimes($o_cassandra, $imei, $datetime1, $datetime2, $deviceTime, $orderAsc);
	$o_cassandra->close();
	//$full_params = array('a','b','c','d','e','f','i','j','k','l','m','n','o','p','q','r','ci','ax','ay','az','mx','my','mz','bx','by','bz');
	print_r($st_results);
	

?>
