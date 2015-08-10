<?php

	require_once '../Cassandra/Cassandra.php';
	require_once '../libLog.php';
	
	$o_cassandra = new Cassandra();
	
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	
	
	$imeiArray = array( 
		'862170018314698',
		'862170018383602',
		'862170016975417',
		'862170018382109',
		'861074027067878',
		'862170018313575',
		'862170018368140',
		'865733021569637',
		'865733021562954',
		'862170018315638',
		'862170016970533',
		'862170018364933',
		'862170014330649',
		'862170018322865',
		'862170018367001',
		'862170017811595',
		'862170018366912',
		'861074027068629',
		'861074027067696',
		'862170018366821',
		'862170017810589',
		'862170018316933',
		'865733021569579',
		'862170018368132',
		'862170018323731',
		'862170018320653',
		'862170014373573',
		'862170018323400'
		);
	$datetime1 = '2015-08-08 23:59:00';
	$datetime2 = '2015-08-09 00:01:00';
	$deviceTime = TRUE;	// TRUE for query on index dtime, otherwise stime	
	$orderAsc = FALSE;	// TRUE for ascending, otherwise descending (default) 

	$len = count($imeiArray);
	for ($i=0; $i < $len; $i++)
	{
		$imei = $imeiArray[$i];
		//echo ("imei = $imei\n");
		$st_results = getImeiDateTimes($o_cassandra, $imei, $datetime1, $datetime2, $deviceTime, $orderAsc);
		$o_cassandra->close();
		//$full_params = array('a','b','c','d','e','f','i','j','k','l','m','n','o','p','q','r','ci','ax','ay','az','mx','my','mz','bx','by','bz');
		print_r($st_results);
	}
	

?>
