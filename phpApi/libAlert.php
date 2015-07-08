<?php

require_once 'libCommon.php';


/***
* Returns filtered results based on given param and its min, max  
*
* @param object $o_cassandra	Cassandra object 
*
* @return object 	filtered results	
*/
function filter($st_results, $param, $minVal, $maxVal)
{
	foreach ($st_results as $key=>$row)
	{
		$col = $row[$param];	
		if ($col < $minVal || $col > $maxVal)
			unset($st_results[$key]); 	// delete row from array
	}

	return $st_results;
}

/***
* Returns Speed Violations for given imei, dtimes, minspeed, maxspeed, roadId
* 
* @param object $o_cassandra	Cassandra object 
* @param string $imei		IMEI
* @param string $dTime1		YYYY-MM-DD HH:mm:SS
* @param string $dTime2		YYYY-MM-DD HH:mm:SS
* @param int $minSpeed 		40	
* @param int $maxSpeed 		80	
* @param array $roadId		{ 'rd1', 'rd2', 'rd3' }
* 
* @return object 	Results of the query 
*/
function getSpeedAlerts($o_cassandra, $imei, $dTime1, $dTime2, $minSpeed, $maxSpeed, $roadId)
{
	$TZ='0530';	// Asia/Kolkata

	$dateList = getDateList($dTime1,$dTime2);
	$s_cql = "SELECT * FROM speedalert
		WHERE
		imei = '$imei'
		AND
		date IN $dateList
		AND
		dtime >= '$dTime1+$TZ'
		AND
		dtime <= '$dTime2+$TZ'
		;";
		
	$st_results = $o_cassandra->query($s_cql);
	$st_results = filter($st_results, 'speed', $minSpeed, $maxSpeed);
	return $st_results;
}

/***
* Returns Turn Violations for given imei, dtimes, minspeed, maxspeed, minAngle, maxAngle, roadId 
* 
* @param object $o_cassandra	Cassandra object 
* @param string $imei		IMEI
* @param string $dTime1		YYYY-MM-DD HH:mm:SS
* @param string $dTime2		YYYY-MM-DD HH:mm:SS
* @param int $minSpeed 		km/hr	
* @param int $maxSpeed 		km/hr	
* @param int $minAngle 		degrees	
* @param int $maxAngle 		degrees	
* @param array $roadId		{ 'rd1', 'rd2', 'rd3' }
* 
* @return object 	Results of the query 
*/
function getTurnAlerts($o_cassandra, $imei, $dTime1, $dTime2, $minSpeed, $maxSpeed, $minAngle, $maxAngle, $roadId)
{
	$TZ='0530';	// Asia/Kolkata

	$dateList = getDateList($dTime1,$dTime2);
	$s_cql = "SELECT * FROM turnalert
		WHERE
		imei = '$imei'
		AND
		date IN $dateList
		AND
		dtime >= '$dTime1+$TZ'
		AND
		dtime <= '$dTime2+$TZ'
		;";
	$st_results = $o_cassandra->query($s_cql);

	$param1 = 'speed';
	$minVal1 = $minSpeed;
	$maxVal1 = $maxSpeed;	
	$param2 = 'angle';
	$minVal2 = $minAngle;
	$maxVal2 = $maxAngle;	
	foreach ($st_results as $key=>$row)
	{
		$col1 = $row[$param1];
		$col2 = $row[$param2];
		if ($col1 < $minVal1 || $col1 > $maxVal1 || $col2 < $minVal2 || $col2 > $maxVal2)
			unset($st_results[$key]); 	// delete row from array
	}
	
	return $st_results;
}

/***
* Returns hourly distance travelled for given imei, startTimes 
* 
* @param object $o_cassandra	Cassandra object 
* @param string $imei		IMEI
* @param string $startTime1	YYYY-MM-DD HH:mm:SS
* @param string startTime2	YYYY-MM-DD HH:mm:SS
* 
* @return object 	Results of the query 
*/
function getDistanceLog($o_cassandra, $imei, $startTime, $endTime)
{
	$TZ='0530';	// Asia/Kolkata

	$dateList = getDateList($startTime, $endTime);
	$s_cql = "SELECT * FROM distancelog 
		WHERE
		imei = '$imei'
		AND
		date IN $dateList
		AND
		starttime >= '$startTime+$TZ'
		;";
	$st_results = $o_cassandra->query($s_cql);
	
	$param1 = 'endtime';
	$maxVal1 = strtotime($endTime) * 1000;	// cassandra timestamp is in milliseconds
	echo "maxVal1 = $maxVal1 ";	
	foreach ($st_results as $key=>$row)
	{
		$col1 = $row[$param1];
		if ($col1 > $maxVal1)
			unset($st_results[$key]); 	// delete row from array
	}
	
	return $st_results;
}


/***
* Returns xroads log for given imei, dtimes, minspeed, maxspeed, roadId 
* 
* @param object $o_cassandra	Cassandra object 
* @param string $imei		IMEI
* @param string $dTime1		YYYY-MM-DD HH:mm:SS
* @param string $dTime2		YYYY-MM-DD HH:mm:SS
* @param int $minSpeed 		km/hr	
* @param int $maxSpeed 		km/hr	
* @param int $minHalt		seconds	
* @param int $maxHalt		seconds	
* @param array $xRoadId		{ 'rd1', 'rd2', 'rd3' }
* 
* @return object 	Results of the query 
*/
function getxRoadLog($o_cassandra, $imei, $dTime1, $dTime2, $minSpeed, $maxSpeed, $minHalt, $maxHalt, $xRoadId)
{
	$TZ='0530';	// Asia/Kolkata

	$dateList = getDateList($dTime1, $dTime2);
	$s_cql = "SELECT * FROM xroadlog 
		WHERE
		imei = '$imei'
		AND
		date IN $dateList
		AND
		dtime >= '$dTime1+$TZ'
		AND
		dtime <= '$dTime2+$TZ'
		;";
	$st_results = $o_cassandra->query($s_cql);

	$param1 = 'speed';
	$minVal1 = $minSpeed;
	$maxVal1 = $maxSpeed;	
	$param2 = 'haltduration';
	$minVal2 = $minHalt;
	$maxVal2 = $maxHalt;
	foreach ($st_results as $key=>$row)
	{
		$col1 = $row[$param1];
		$col2 = $row[$param2];
		if ($col1 < $minVal1 || $col1 > $maxVal1 || $col2 < $minVal2 || $col2 > $maxVal2)
			unset($st_results[$key]); 	// delete row from array
	}
	return $st_results;

}

/***
* Returns travel log for given imei, dtimes, minDuration, maxDuration
* 
* @param object $o_cassandra	Cassandra object 
* @param string $imei		IMEI
* @param string $startTime	YYYY-MM-DD HH:mm:SS
* @param string $endTime	YYYY-MM-DD HH:mm:SS
* @param int $minDuration	seconds	
* @param int $maxDuration	seconds	
* @param array $xRoadId		{ 'rd1', 'rd2', 'rd3' }
* 
* @return object 	Results of the query 
*/
function getTravelLog($o_cassandra, $imei, $startTime, $endTime, $minDuration, $maxDuration)
{
	$TZ='0530';	// Asia/Kolkata

	$dateList = getDateList($startTime, $endTime);
	$s_cql = "SELECT * FROM travellog 
		WHERE
		imei = '$imei'
		AND
		date IN $dateList
		AND
		starttime >= '$startTime+$TZ'
		;";
	$st_results = $o_cassandra->query($s_cql);
	
	$param1 = 'endtime';
	$maxVal1 = $endTime;	
	$param2 = 'duration';
	$minVal2 = $minDuration;
	$maxVal2 = $maxDuration;
	foreach ($st_results as $key=>$row)
	{
		$col1 = $row[$param1];
		$col2 = $row[$param2];
		if ($col1 > $maxVal1 || $col2 < $minVal2 || $col2 > $maxVal2)
			unset($st_results[$key]); 	// delete row from array
	}
	
	return $st_results;

}

/***
* Returns night log for given imei, dtimes, minDuration, maxDuration
* 
* @param object $o_cassandra	Cassandra object 
* @param string $imei		IMEI
* @param string $startTime	YYYY-MM-DD HH:mm:SS
* @param string $endTime	YYYY-MM-DD HH:mm:SS
* @param int $minDuration	seconds	
* @param int $maxDuration	seconds	
* @param array $xRoadId		{ 'rd1', 'rd2', 'rd3' }
* 
* @return object 	Results of the query 
*/
function getNightLog($o_cassandra, $imei, $startTime, $endTime, $minDuration, $maxDuration)
{
	$TZ='0530';	// Asia/Kolkata

	$dateList = getDateList($startTime, $endTime);
	$s_cql = "SELECT * FROM nightlog 
		WHERE
		imei = '$imei'
		AND
		date IN $dateList
		AND
		starttime >= '$startTime+$TZ'
		;";
	$st_results = $o_cassandra->query($s_cql);
	
	$param1 = 'endtime';
	$maxVal1 = $endTime;	
	$param2 = 'duration';
	$minVal2 = $minDuration;
	$maxVal2 = $maxDuration;
	foreach ($st_results as $key=>$row)
	{
		$col1 = $row[$param1];
		$col2 = $row[$param2];
		if ($col1 > $maxVal1 || $col2 < $minVal2 || $col2 > $maxVal2)
			unset($st_results[$key]); 	// delete row from array
	}
	
	return $st_results;

}

/***
* Returns gap log for given imei, dtimes, minDuration, maxDuration
* 
* @param object $o_cassandra	Cassandra object 
* @param string $imei		IMEI
* @param string $startTime	YYYY-MM-DD HH:mm:SS
* @param string $endTime	YYYY-MM-DD HH:mm:SS
* @param int $minDuration	seconds	
* @param int $maxDuration	seconds	
* @param array $xRoadId		{ 'rd1', 'rd2', 'rd3' }
* 
* @return object 	Results of the query 
*/
function getGapLog($o_cassandra, $imei, $startTime, $endTime)
{
	$TZ='0530';	// Asia/Kolkata

	$dateList = getDateList($startTime, $endTime);
	$s_cql = "SELECT * FROM gaplog 
		WHERE
		imei = '$imei'
		AND
		date IN $dateList
		AND
		starttime >= '$startTime+$TZ'
		;";
	$st_results = $o_cassandra->query($s_cql);
	
	$param1 = 'endtime';
	$maxVal1 = $endTime;	
	foreach ($st_results as $key=>$row)
	{
		$col1 = $row[$param1];
		if ($col1 > $maxVal1)
			unset($st_results[$key]); 	// delete row from array
	}
	
	return $st_results;
}


?>
