<?php

	require_once 'Cassandra/Cassandra.php';
	require_once 'libLog.php';
	
	$o_cassandra = new Cassandra();
	
	$s_server_host     = '52.74.33.255';
	//$s_server_host     = '127.0.0.1';    // Localhost
	$i_server_port     = 9042; 
	$s_server_username = '';  // We don't have username
	$s_server_password = '';  // We don't have password
	$s_server_keyspace = 'gps';  
	
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
	
	$len = count($imeiArray);
	for ($i=0; $i < $len; $i++)
	{
		$imei = $imeiArray[$i];
		$date = date("Y-m-d"); 
		$todayLogResult=hasImeiLogged($o_cassandra, $imei, $date);
		//$todayLogResult=hasImeiLoggedToday($o_cassandra, $imei);
		echo "imei = $imei todayLog = $todayLogResult\n";
		echo "\n";
	}	
	$o_cassandra->close();

?>
