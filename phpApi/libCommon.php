<?php

$s_server_host     = '52.74.33.255';
#$s_server_host     = '127.0.0.1';    // Localhost
$i_server_port     = 9042; 
$s_server_username = 'bailoo';  // We don't have username
$s_server_password = 'neon04$IC1';  // We don't have password
$s_server_keyspace = 'gps';  

$TZ='0530';	// Asia/Kolkata


/***
* Returns the list of dates for different days 
*
* @param string $datetime1 YYYY-MM-DD HH:MM:SS
* @param string $datetime2 YYYY-MM-DD HH:MM:SS
* 
* @return string	date list	
*
*/
function getDateList($datetime1,$datetime2)
{	
	$date1 = substr($datetime1,0,10);
	$date2 = substr($datetime2,0,10);

	$interval = new DateInterval('P1D');
	$start = new DateTime($date1);
	$end = new DateTime($date2);
	$end->add($interval);	

	//$period = new DatePeriod($start, $interval, $end);
	$date = $end;
	$dateList = "(";
	while ($date >= $start)
	{
		$dateList .= "'".$date->format('Y-m-d')."',";
		$date = $date->sub($interval);
	}

	/*foreach ($period as $date)
	{
		$dateList .= "'".$date->format('Y-m-d')."',";
	}*/
	$dateList = substr($dateList,0,-1) . ")";
	
	return $dateList;

}



?>
