<?php

	require_once '../Cassandra/Cassandra.php';
	require_once '../libLog.php';
	
	$o_cassandra = new Cassandra();
	
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	
	
	$imei = '865733024479636';
	$datetime1 = '2015-08-31 00:00:00';
	$datetime2 = '2015-08-31 23:59:59';
	$st_results = getLastSeenDateTimes($o_cassandra, $imei, $datetime1, $datetime2);
	$o_cassandra->close();
	//$full_params = array('a','b','c','d','e','f','i','j','k','l','m','n','o','p','q','r','ci','ax','ay','az','mx','my','mz','bx','by','bz');
	print_r($st_results);
		

?>
