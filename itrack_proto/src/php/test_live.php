<?php
	// error_reporting(~E_ALL);

	include_once('lib/BUG.php');
	include_once('lib/UTIL.php');
	include_once('lib/VTSXMLRead.php');

	$imei = '359231030206633';
	$fields = 'latlng:speed:io8'; $filedsMin = '-:5:50'; $fieldsMax = '-:300:999';

	$endDateTime = date('Y-m-d H:i:s');
	$endDateTime = "2011-07-12 08:00:00"; // Running
	$endDateTime = "2011-07-12 08:15:00"; // Idle
	$endDateTime = "2011-07-12 09:00:00"; // Stopped
	$startDateTime = date('Y-m-d H:i:s', strtotime($endDateTime)-(30*60));

	$fieldsDataAll = VTSXMLRead::getVTSFieldsData($imei, $startDateTime, $endDateTime, $fields, $filedsMin, $fieldsMax);

	// print_r($fieldsDataAll);

	$speed = $fieldsDataAll['speed']['datetimeTS'];
	$n = sizeof($speed);
	$speed_data = array_keys($speed);
	$datetime_last = $speed_data[$n-1];
	// print_r($speed);
	// print_r($fieldsDataAll['speed']['datetime']);
	// print ($datetime_last . "\n");
	$endDateTimeTS = strtotime($endDateTime);
	$deltaTS = $endDateTimeTS-$datetime_last;
	if($deltaTS < 2*60)
	{
		BUG::debug("Running");
	}
	elseif($deltaTS < 20*60)
	{
		BUG::debug("Idle");
	}
	else
	{
		BUG::debug("Stopped");
	}


	/*
	$fieldsKeys = explode(":", $fields); $dateType = 'datetime';
	print($dateType); foreach($fieldsKeys as $field) { print(" \t" . $field); } print("\n");
	foreach($fieldsDataAll[$fieldsKeys[0]][$dateType] as $datetime => $value)
	{
		print($datetime); foreach($fieldsKeys as $field) { print(" \t" . $fieldsDataAll[$field][$dateType][$datetime]); } print("\n");
	}
	*/

	
?>
