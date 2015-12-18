<?php

require_once '../libCommon.php';

	$datetime1 = '2015-08-08 23:59:00';
	$datetime2 = '2015-08-10 00:01:00';
	$dateArray = getDateArray($datetime1,$datetime2);
	print_r($dateArray);	


	$dateList = getDateList($datetime1,$datetime2);
	echo "$dateList";	


?>

