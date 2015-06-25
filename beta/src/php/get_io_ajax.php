<?php
	include_once('util_php_mysql_connectivity.php');
	include_once('get_io.php');
	$imei=$_GET['content'];
	$type="engine,engine_type";
	$finalIoType=get_io($imei, $type);  
	echo $finalIoType;  
?>