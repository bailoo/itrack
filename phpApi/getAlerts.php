<?php

	require_once 'Cassandra/Cassandra.php';
	require_once 'libAlert.php';
	
	$o_cassandra = new Cassandra();
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	
	
	$startTime = '2015-06-29 10:00:00';
	$endTime = '2015-06-29 16:00:00';
	$minSpeed = 20;
	$maxSpeed = 40;
	$imei = '123456';
	
	$st_results = getSpeedAlerts($o_cassandra, $imei, $startTime, $endTime, $minSpeed, $maxSpeed);
	print "Speed Alerts\n";
	print_r($st_results);
	print "\n";

	$minAngle = 30;
	$maxAngle = 150;
	$st_results = getTurnAlerts($o_cassandra, $imei, $startTime, $endTime, $minSpeed, $maxSpeed, $minAngle, $maxAngle);
	print "Turn Alerts\n";
	print_r($st_results);
	print "\n";

	$st_results = getDistanceLog($o_cassandra, $imei, $startTime, $endTime);
	print "Distance Log\n";
	print_r($st_results);
	print "\n";

	$minHalt = 30;
	$maxHalt = 150;
	$xRoadId = '1234';
	$st_results = getxRoadLog($o_cassandra, $imei, $startTime, $endTime, $minSpeed, $maxSpeed, $minHalt, $maxHalt, $xRoadId);
	print "Crossroad Log\n";
	print_r($st_results);
	print "\n";

	$minDuration = 30;
	$maxDuration = 150;
	$st_results = getTravelLog($o_cassandra, $imei, $startTime, $endTime, $minDuration, $maxDuration);
	print "Travel Log\n";
	print_r($st_results);
	print "\n";

	$minDuration = 30;
	$maxDuration = 150;
	$st_results = getNightLog($o_cassandra, $imei, $startTime, $endTime, $minDuration, $maxDuration);
	print "Night Log\n";
	print_r($st_results);
	print "\n";

	$st_results = getGapLog($o_cassandra, $imei, $startTime, $endTime);
	print "Gap Log\n";
	print_r($st_results);
	print "\n";
	

	$o_cassandra->close();

?>
