<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->config('debug', true);

$app->get('/', function() use($app) {
    	$app->response->setStatus(200);
	echo "iTrack API <br/>\n";
	echo "<br/>\n";	
	echo "/getlog/:imei/:dtime1/:dtime2 <br/>\n";
	echo "<br/>\n";	
	echo "/getlastlog/:imei <br/>\n";
	echo "<br/>\n";	
}); 

$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});


$app->get('/getlog/:imei/:dtime1/:dtime2/', function ($imei, $dtime1, $dtime2) {
	
	require_once 'Cassandra/Cassandra.php';
	require_once 'libLog.php';

	$o_cassandra = new Cassandra();
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	$deviceTime = TRUE;	// TRUE for query on index dtime, otherwise stime	
	$orderAsc = FALSE;	// TRUE for ascending, otherwise descending (default) 
	$st_results = getImeiDateTimes($o_cassandra, $imei, $dtime1, $dtime2, $deviceTime, $orderAsc);
	echo json_encode($st_results);
	$o_cassandra->close();

});


$app->get('/getlastlog/:imei/', function ($imei) {
	
	require_once 'Cassandra/Cassandra.php';
	require_once 'libLog.php';
	
	$o_cassandra = new Cassandra();
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	$st_results = getLastSeen($o_cassandra,$imei);
	echo json_encode($st_results);
	$o_cassandra->close();

});



$app->run();



?>
