<?php
include_once('util_session_variable.php');	
include_once('util_php_mysql_connectivity.php');
$query="Select device_imei_no from vehicle_assignment";
$result=mysql_query($query,$DbConnection);
while($row=mysql_fetch_object($result))
{
	/*$device_imei_no=$row->device_imei_no;
	echo "device_imei_no=".$device_imei_no."<br>";
	$query1="Insert into device_manufacturing_info(device_imei_no,manufacture_date,make,status,create_id,create_date) Values($device_imei_no,'2011-03-21 11:00:00','-','1','1','2011-03-21 11:00:00')";
	echo "query=".$query;
	$result1=mysql_query($query1,$DbConnection);
	$query2="INSERT into device_assignment(device_imei_no,account_id,status,create_id,create_date) Values($device_imei_no,'1','1','1','2011-03-21 11:00:00')";
	$result2=mysql_query($query2,$DbConnection);*/		
}
?>

