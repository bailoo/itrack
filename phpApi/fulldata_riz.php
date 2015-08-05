<?php

	require_once 'Cassandra/Cassandra.php';
	require_once 'libLog.php';
	
	$o_cassandra = new Cassandra();
	
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	
	
	$imeiArray = array( 
'861074026490527'
);
	$imei = '862170016970483';
	$datetime1 = '2015-08-03 09:00:00';
	$datetime2 = '2015-08-03 10:00:00';
	$deviceTime = TRUE;	// TRUE for query on index dtime, otherwise stime	
	$orderAsc = FALSE;	// TRUE for ascending, otherwise descending (default) 

	$len = count($imeiArray);
$j=0;
	for ($i=0; $i < $len; $i++)
	{
		$imei = $imeiArray[$i];
		//echo ("imei = $imei\n");
		$st_results = getImeiDateTimes($o_cassandra, $imei, $datetime1, $datetime2, $deviceTime, $orderAsc);
		//$full_params = array('a','b','c','d','e','f','i','j','k','l','m','n','o','p','q','r','ci','ax','ay','az','mx','my','mz','bx','by','bz');
		//echo "\nResultSize=".count($st_results->h);
//		print_r($st_results);
	}
	

	//$date = '2015-06-14';
	//$st_results = getLogByDate($o_cassandra, $imei, $date, $deviceTime, $orderAsc);
	//$full_params = array('a','b','c','d','e','f','i','j','k','l','m','n','o','p','q','r','ci','ax','ay','az','mx','my','mz','bx','by','bz');
	//print_r($st_results);
		

	//printHTML($st_results);
	//echo 'Execution time: '.$i_execution_time."\n";
	
	$o_cassandra->close();


$dArr=array();
foreach($st_results as $item) {
//$dArr[]=$item->d;
echo "\nDT=".$item->d;
}

echo "dtCountE=".count($dArr);
//              print_r($st_results);

?>
