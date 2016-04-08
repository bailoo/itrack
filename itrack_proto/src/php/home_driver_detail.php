<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];
$vimei_no = $_GET['imei_no'];
//echo "imei_no=".$vimei_no."<br>";
$driver_name_mob=get_vehicle_number($root,$vimei_no);
echo $driver_name_mob;
?>