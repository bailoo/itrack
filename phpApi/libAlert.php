<?php

require_once 'libCommon.php';


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
	$dateList = getDateList($dTime1,$dTime2);
	$s_cql = "SELECT * FROM speedlog
		WHERE
		imei = '$imei'
		AND
		date IN $dateList
		AND
		dtime >= '$dTime1+$TZ'
		AND
		dtime <= '$dTime2+$TZ'
		AND
		roadid IN '$roadId'
		;";
	$st_results = $o_cassandra->query($s_cql);
	$filter_res = filter($st_results, $minSpeed, $maxSpeed);
	return $filter_res;
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
	$dateList = getDateList($dTime1,$dTime2);
	$s_cql = "SELECT * FROM turnlog
		WHERE
		imei = '$imei'
		AND
		date IN $dateList
		AND
		dtime >= '$dTime1+$TZ'
		AND
		dtime <= '$dTime2+$TZ'
		AND
		roadid IN '$roadId'
		;";
	$st_results = $o_cassandra->query($s_cql);
	$filter_res = filter($st_results,$minSpeed, $maxSpeed, $minAngle, $maxAngle);
	return $filter_res;
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
function getHourlyDistance($o_cassandra, $imei, $startTime1, $startTime2)
{
	$dateList = getDateList($dTime1,$dTime2);
	$s_cql = "SELECT * FROM distancelog 
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
function getxRoadLogs($o_cassandra, $imei, $dTime1, $dTime2, $minSpeed, $maxSpeed, $minHalt, $maxHalt, $xRoadId)
{
	$dateList = getDateList($dTime1,$dTime2);
	$s_cql = "SELECT * FROM xroadlog 
		WHERE
		imei = '$imei'
		AND
		date IN $dateList
		AND
		dtime >= '$dTime1+$TZ'
		AND
		dtime <= '$dTime2+$TZ'
		AND
		xroadid IN '$xRoadId'
		;";
	$st_results = $o_cassandra->query($s_cql);
	$filter_res = filter($st_results,$minSpeed, $maxSpeed, $minHalt, $maxHalt);
	return $filter_res;

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
function getTravelLogs($o_cassandra, $imei, $startTime, $endTime, $minDuration, $maxDuration)
{
	$dateList = getDateList($dTime1,$dTime2);
	$s_cql = "SELECT * FROM travellog 
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
	$filter_res = filter($st_results,$minSpeed, $maxSpeed, $minDuration, $maxDuration);
	return $filter_res;

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
function getNightLogs($o_cassandra, $imei, $startTime, $endTime, $minDuration, $maxDuration)
{
	$dateList = getDateList($dTime1,$dTime2);
	$s_cql = "SELECT * FROM nightlog 
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
	$filter_res = filter($st_results,$minSpeed, $maxSpeed, $minDuration, $maxDuration);
	return $filter_res;

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
function getGapLogs($o_cassandra, $imei, $startTime, $endTime)
{
	$dateList = getDateList($dTime1,$dTime2);
	$s_cql = "SELECT * FROM gaplog 
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
	return $st_results;
}


?>
