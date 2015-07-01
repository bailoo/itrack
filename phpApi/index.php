<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->config('debug', true);

$app->get('/', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API";
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

	//$st_results = getLogByDate($o_cassandra, $imei, $date, $deviceTime, $orderAsc);
	//$full_params = array('a','b','c','d','e','f','i','j','k','l','m','n','o','p','q','r','ci','ax','ay','az','mx','my','mz','bx','by','bz');
	print_r($st_results);
	$o_cassandra->close();

});

$app->run();



?>
