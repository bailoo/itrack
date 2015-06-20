<?php
function get_vehicle_db_detail()
{
	global $DbConnection;
	global $account_id;
	global $vehicle_id;
	global $vehicle_name;
	global $imei;	

	$query = "SELECT vehicle.vehicle_id,vehicle.vehicle_name,vehicle_assignment.device_imei_no FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".	
	"vehicle_grouping.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle.vehicle_id AND ".
	"vehicle_assignment.status=1 AND vehicle.status=1 AND vehicle_grouping.account_id='$account_id'";
	//echo $query."\n";
	$result = mysql_query($query,$DbConnection);
	
	while($row = mysql_fetch_object($result))
	{
	  $vehicle_id[] = $row->vehicle_id;
	  $vehicle_name[] = $row->vehicle_name;
	  $imei[] = $row->device_imei_no;
	}	
}
?>  