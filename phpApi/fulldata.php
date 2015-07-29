<?php

	require_once 'Cassandra/Cassandra.php';
	require_once 'libLog.php';
	
	$o_cassandra = new Cassandra();
	
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	
	
	$imei = '862170018383602';
	$datetime1 = '2015-07-28 18:00:00';
	$datetime2 = '2015-07-28 18:30:00';
	$deviceTime = TRUE;	// TRUE for query on index dtime, otherwise stime	
	$orderAsc = FALSE;	// TRUE for ascending, otherwise descending (default) 
	$st_results = getImeiDateTimes($o_cassandra, $imei, $datetime1, $datetime2, $deviceTime, $orderAsc);
	print_r($st_results);



	//$date = '2015-06-14';
	//$st_results = getLogByDate($o_cassandra, $imei, $date, $deviceTime, $orderAsc);
	//$full_params = array('a','b','c','d','e','f','i','j','k','l','m','n','o','p','q','r','ci','ax','ay','az','mx','my','mz','bx','by','bz');
	//print_r($st_results);
		

	//printHTML($st_results);
	//echo 'Execution time: '.$i_execution_time."\n";
	
	$o_cassandra->close();

?>
