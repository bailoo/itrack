<?php
include_once('../util_session_variable.php');
include_once('../util_php_mysql_connectivity.php');
$my_date=date("Y-m-d H:i:s");
//include('../coreDb.php');

$account_id=$_SESSION['account_id'];
$query="SELECT name from account_detail where account_id='$account_id'";
$result=mysql_query($query,$DbConnection);
$row=mysql_fetch_object($result);
//echo $query;
$user_name=$row->name;
/*
$to_city_name=array();$to_city_id=array();$serial_chk=array();
$query_list_dest="select city.city_name as cname, transporter_vehicle_route_assignment.to_city_id as cid ,transporter_vehicle_route_assignment.serial from transporter_vehicle_route_assignment,city where transporter_vehicle_route_assignment.to_city_id=city.city_id and transporter_vehicle_route_assignment.status=1 and city.status=1 and transporter_vehicle_route_assignment.create_id=$account_id";
//echo $query_list_dest;
$result_dest = mysql_query($query_list_dest,$DbConnection);
while($rowdest=mysql_fetch_object($result_dest))
{
	$tmp_serial=$rowdest->serial;
	$serial_chk[]=$tmp_serial;
	$to_city_name[$tmp_serial]=$rowdest->cname;
	$to_city_id[$tmp_serial]=$rowdest->cname;
}

$from_city_name=array();$from_city_id=array();
$query_list_dest="select city.city_name as cname, transporter_vehicle_route_assignment.from_city_id as cid ,transporter_vehicle_route_assignment.serial from transporter_vehicle_route_assignment,city where transporter_vehicle_route_assignment.from_city_id=city.city_id and transporter_vehicle_route_assignment.status=1 and city.status=1  and transporter_vehicle_route_assignment.create_id=$account_id";
$result_dest = mysql_query($query_list_dest,$DbConnection);
while($rowdest=mysql_fetch_object($result_dest))
{	
	$tmp_serial=$rowdest->serial;
	$from_city_name[$tmp_serial]=$rowdest->cname;
	$from_city_id[$tmp_serial]=$rowdest->cname;
}

//print_r($to_city_name);
//echo"<br>";
//print_r($from_city_name);
/*
foreach($serial_chk as $sn)
{
	echo $from_city_name[$sn].":".$to_city_name[$sn]."<br>";
}*/


include("main.php");
?>