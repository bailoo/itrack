<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  include_once("get_io.php");
  
	$content = $_GET['content'];
	
	$string = explode(",", $content);
	$vehicle_serial = $string[0];
	$type1 = $string[1];
	
	$io = get_io($vehicle_serial,$type1);    // eg.type='fuel', 'engine', 'sos'
	echo $io;
	
?>