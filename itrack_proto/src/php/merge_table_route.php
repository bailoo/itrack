<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

set_time_limit(0);

$vehicle_name_ev = array();
$route_name_ev = array();
$remark_ev = array();
$user_account_id_ev = array();
$status_ev = array();
$create_id_ev = array();
$create_date_ev = array();
$edit_id_ev = array();
$edit_date_ev = array();

$vehicle_name_mor = array();
$route_name_mor = array();
$remark_mor = array();
$user_account_idmor = array();
$status_mor = array();
$create_id_mor = array();
$create_date_mor = array();
$edit_id_mor = array();
$edit_date_mor = array();

$query1 = "SELECT vehicle_name,route_name,remark,user_account_id,status,create_id,create_date,edit_id,edit_date from route_assignment2 WHERE status=1";
echo "<br>Q1=".$query1;
$result1 = mysql_query($query1, $DbConnection);
while($row1=mysql_fetch_object($result1))
{
	$vehicle_name_ev[] = $row1->vehicle_name;
	$route_name_ev[] = $row1->route_name;
	$remark_ev[] = $row1->remark;
	$user_account_id_ev[] = $row1->user_account_id;
	$status_ev[] = $row1->status;
	$create_id_ev[] = $row1->create_id;
	$create_date_ev[] = $row1->create_date;
	$edit_id_ev[] = $row1->edit_id;
	$edit_date_ev[] = $row1->edit_date;	
}

$query2 = "SELECT vehicle_name,route_name,remark,user_account_id,status,create_id,create_date,edit_id,edit_date from route_assignment3 WHERE status=1";
echo "<br>Q2=".$query2;
$result2 = mysql_query($query2, $DbConnection);
while($row2=mysql_fetch_object($result2))
{
	$vehicle_name_mor[] = $row2->vehicle_name;
	$route_name_mor[] = $row2->route_name;
	$remark_mor[] = $row2->remark;	
	$user_account_id_mor[] = $row2->user_account_id;
	$status_mor[] = $row2->status;
	$create_id_mor[] = $row2->create_id;
	$create_date_mor[] = $row2->create_date;
	$edit_id_mor[] = $row2->edit_id;
	$edit_date_mor[] = $row2->edit_date;	
}

for($i=0;$i<sizeof($vehicle_name_ev);$i++)
{
	$query_insert1 = "INSERT INTO route_assignment_tmp(vehicle_name,user_account_id,status,create_id,create_date,edit_id,edit_date) values('$vehicle_name_ev[$i]','$user_account_id_ev[$i]','$status_ev[$i]','$create_id_ev[$i]','$create_date_ev[$i]','$edit_id_ev[$i]','$edit_date_ev[$i]')";
	$result1 = mysql_query($query_insert1, $DbConnection);
}

for($i=0;$i<sizeof($vehicle_name_mor);$i++)
{
	$query_insert2 = "INSERT INTO route_assignment_tmp(vehicle_name,user_account_id,status,create_id,create_date,edit_id,edit_date) values('$vehicle_name_mor[$i]','$user_account_id_mor[$i]','$status_mor[$i]','$create_id_mor[$i]','$create_date_mor[$i]','$edit_id_mor[$i]','$edit_date_mor[$i]')";
	$result2 = mysql_query($query_insert2, $DbConnection);
}

for($i=0;$i<sizeof($route_name_ev);$i++)
{
	if($route_name_ev[$i]!="")
	{
		$query1 = "UPDATE route_assignment_tmp SET route_name_ev='$route_name_ev[$i]',remark_ev='$remark_ev[$i]' where vehicle_name='$vehicle_name_ev[$i]'";
		$result1 = mysql_query($query1,$DbConnection);
	}
}

for($i=0;$i<sizeof($route_name_mor);$i++)
{
	if($route_name_mor[$i]!="")
	{	
		$query2 = "UPDATE route_assignment_tmp SET route_name_mor='$route_name_mor[$i]',remark_ev='$remark_mor[$i]' where vehicle_name='$vehicle_name_mor[$i]'";
		$result2 = mysql_query($query2,$DbConnection);
	}
}

?>