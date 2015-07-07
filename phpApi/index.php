<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->config('debug', true);

$app->get('/', function() use($app) {
    	$app->response->setStatus(200);
	echo "iTrack API";
	echo "<br/> <br/>";	
	echo "/getlog/:imei/:deviceTime/:orderAsc/:dtime1/:dtime2";
	echo "<br/>";	
	echo "/getlog/:imei/:deviceTime/:orderAsc/:day";
	echo "<br/> <br/>";	
	echo "/getlastlog/:imei";
	echo "<br/>";	
	echo "/getlastlog/:imei/:dtime";
	echo "<br/> <br/>";	
}); 

$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});


$app->get('/getlog/:imei/:deviceTime/:orderAsc/:dtime1/:dtime2/', function ($imei, $deviceTime, $orderAsc, $dtime1, $dtime2) {
	
	require_once 'Cassandra/Cassandra.php';
	require_once 'libLog.php';

	$o_cassandra = new Cassandra();
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	$st_results = getImeiDateTimes($o_cassandra, $imei, $dtime1, $dtime2, $deviceTime, $orderAsc);
	echo json_encode($st_results);
	$o_cassandra->close();

});

$app->get('/getlog/:imei/:deviceTime/:orderAsc/:day/', function ($imei, $deviceTime, $orderAsc, $date) {
	
	require_once 'Cassandra/Cassandra.php';
	require_once 'libLog.php';

	$o_cassandra = new Cassandra();
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	$st_results = getLogByDate($o_cassandra, $imei, $date, $deviceTime, $orderAsc);
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


$app->get('/getlastlog/:imei/:dtime/', function ($imei, $dtime) {
	
	require_once 'Cassandra/Cassandra.php';
	require_once 'libLog.php';
	
	$o_cassandra = new Cassandra();
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	$st_results = getLastSeenDateTime($o_cassandra, $imei, $dtime);
	echo json_encode($st_results);
	$o_cassandra->close();

});



$app->run();



?>
