<?php

	require_once '../Cassandra/Cassandra.php';
	require_once '../libLog.php';
	
	$o_cassandra = new Cassandra();
	
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	
	
	$imei = '861074026490527';
	$datetime1 = '2015-08-03 09:00:00';
	$datetime2 = '2015-08-03 10:00:00';
	$deviceTime = TRUE;	// TRUE for query on index dtime, otherwise stime	
	$orderAsc = FALSE;	// TRUE for ascending, otherwise descending (default) 

	$imei = $imeiArray[$i];
	$st_results = getImeiDateTimes($o_cassandra, $imei, $datetime1, $datetime2, $deviceTime, $orderAsc);
	$o_cassandra->close();
	
	//$full_params = array('a','b','c','d','e','f','i','j','k','l','m','n','o','p','q','r','ci','ax','ay','az','mx','my','mz','bx','by','bz');
	//echo "\nResultSize=".count($st_results->h);
	//print_r($st_results);
	
	$dArr=array();
	foreach($st_results as $item) {
		echo "\nDT=".$item->d;
	}

	echo "dtCountE=".count($dArr);

?>
