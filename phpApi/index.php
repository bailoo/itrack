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
	
	echo "/getspeedalerts/:imei/:starttime/:endtime/:minspeed/:maxspeed/:roadid";
	echo "<br/> <br/>";
	
	echo "/getturnalerts/:imei/:starttime/:endtime/:minspeed/:maxspeed/:minangle/:maxangle/:roadid";
	echo "<br/> <br/>";
	
	echo "/getdistancelog/:imei/:starttime/:endtime";
	echo "<br/> <br/>";
	
	echo "/getxroadlog/:imei/:starttime/:endtime/:minspeed/:maxspeed/:minhalt/:maxhalt/:xroadid";
	echo "<br/> <br/>";
	
	echo "/gettravellog/:imei/:starttime/:endtime/:minduration/:maxduration";
	echo "<br/> <br/>";
	
	echo "/getnightlog/:imei/:starttime/:endtime/:minduration/:maxduration";
	echo "<br/> <br/>";
	
	echo "/getgaplog/:imei/:starttime/:endtime";
	echo "<br/> <br/>";
	
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


$app->get('/getspeedalerts/:imei/:starttime/:endtime/:minspeed/:maxspeed/:roadid', function ($imei, $startTime, $endTime, $minSpeed, $maxSpeed, $roadId) {
	
	require_once 'Cassandra/Cassandra.php';
	require_once 'libAlert.php';
	$o_cassandra = new Cassandra();
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);

	$st_results = getSpeedAlerts($o_cassandra, $imei, $startTime, $endTime, $minSpeed, $maxSpeed, $roadId);
	echo json_encode($st_results);
	$o_cassandra->close();

});


$app->get('/getturnalerts/:imei/:starttime/:endtime/:minspeed/:maxspeed/:minangle/:maxangle/:roadid', function ($imei, $startTime, $endTime, $minSpeed, $maxSpeed, $minAngle, $maxAngle, $roadId) {
	
	require_once 'Cassandra/Cassandra.php';
	require_once 'libAlert.php';
	$o_cassandra = new Cassandra();
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);

	$st_results = getTurnAlerts($o_cassandra, $imei, $startTime, $endTime, $minSpeed, $maxSpeed, $minAngle, $maxAngle, $roadId);
	echo json_encode($st_results);
	$o_cassandra->close();

});


$app->get('/getdistancelog/:imei/:starttime/:endtime/', function ($imei, $startTime, $endTime) {
	
	require_once 'Cassandra/Cassandra.php';
	require_once 'libAlert.php';
	$o_cassandra = new Cassandra();
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);

	$st_results = getDistanceLog($o_cassandra, $imei, $startTime, $endTime);
	echo json_encode($st_results);
	$o_cassandra->close();

});


$app->get('/getxroadlog/:imei/:starttime/:endtime/:minspeed/:maxspeed/:minhalt/:maxhalt/:xroadid', function ($imei, $startTime, $endTime, $minSpeed, $maxSpeed, $minHalt, $maxHalt, $xRoadId) {
	require_once 'Cassandra/Cassandra.php';
	require_once 'libAlert.php';
	$o_cassandra = new Cassandra();
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);

	$st_results = getxRoadLog($o_cassandra, $imei, $startTime, $endTime, $minSpeed, $maxSpeed, $minHalt, $maxHalt, $xRoadId);
	echo json_encode($st_results);
	$o_cassandra->close();

});


$app->get('/gettravellog/:imei/:starttime/:endtime/:minduration/:maxduration', function ($imei, $startTime, $endTime, $minDuration, $maxDuration) {
	require_once 'Cassandra/Cassandra.php';
	require_once 'libAlert.php';
	$o_cassandra = new Cassandra();
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);

	$st_results = getTravelLog($o_cassandra, $imei, $startTime, $endTime, $minDuration, $maxDuration);
	echo json_encode($st_results);
	$o_cassandra->close();

});


$app->get('/getnightlog/:imei/:starttime/:endtime/:minduration/:maxduration', function ($imei, $startTime, $endTime, $minDuration, $maxDuration) {
	require_once 'Cassandra/Cassandra.php';
	require_once 'libAlert.php';
	$o_cassandra = new Cassandra();
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);

	$st_results = getNightLog($o_cassandra, $imei, $startTime, $endTime, $minDuration, $maxDuration);
	echo json_encode($st_results);
	$o_cassandra->close();

});


$app->get('/getgaplog/:imei/:starttime/:endtime/', function ($imei, $startTime, $endTime) {
	
	require_once 'Cassandra/Cassandra.php';
	require_once 'libAlert.php';
	$o_cassandra = new Cassandra();
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);

	$st_results = getGapLog($o_cassandra, $imei, $startTime, $endTime);
	echo json_encode($st_results);
	$o_cassandra->close();

});



$app->run();



?>
