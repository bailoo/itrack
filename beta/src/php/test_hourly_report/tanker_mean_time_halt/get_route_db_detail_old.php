<?php
function get_route_db_detail($shift)
{
	global $DbConnection;
	global $account_id;
	global $vehicle_name_rdb;		//VEHICLE ROUTE DETAIL
	global $route_name_rdb;
	//global $remark_rdb;
	global $vehicle_imei_rdb;

	if($shift == "ZPME")
	{
		$query = "SELECT DISTINCT vehicle_name,route_name_ev FROM route_assignment2 WHERE user_account_id='$account_id' AND NOT(route_name_ev='') AND status=1 Order By route_name_ev DESC";	
		//$query = "SELECT DISTINCT vehicle_name,route_name_ev,remark_ev FROM route_assignment2 WHERE user_account_id='$account_id' AND NOT(route_name_ev='') AND status=1 AND route_name_ev='206013' OR route_name_ev='226573' Order By route_name_ev DESC";	
		
		$result = mysql_query($query,$DbConnection); 

		while($row = mysql_fetch_object($result))
		{
			$vehicle_name_rdb[] = $row->vehicle_name;
			$route_name_rdb[] = $row->route_name_ev;
			//$remark_rdb[] = $row->remark_ev;
			$query2 = "SELECT DISTINCT vehicle_assignment.device_imei_no FROM vehicle_assignment,vehicle WHERE vehicle_assignment.vehicle_id = vehicle.vehicle_id AND ".
			" vehicle.vehicle_name = '$row->vehicle_name' AND vehicle_assignment.status=1";				
			$result2 = mysql_query($query2,$DbConnection); 
			$row2 = mysql_fetch_object($result2);
			$vehicle_imei_rdb[] = $row2->device_imei_no;
		} 
	}	
	else if($shift == "ZPMM")
	{
		//$query = "SELECT DISTINCT vehicle_name,route_name_mor,remark_mor FROM route_assignment2 WHERE user_account_id='$account_id' AND NOT(route_name_mor='') AND status=1 Order By route_name_mor DESC";				
		$query = "SELECT DISTINCT vehicle_name,route_name_mor FROM route_assignment2 WHERE user_account_id='$account_id' AND NOT(route_name_mor='') AND status=1 Order By route_name_mor DESC";				
		echo $query;
		$result = mysql_query($query,$DbConnection); 
		while($row = mysql_fetch_object($result))
		{
		  $vehicle_name_rdb[] = $row->vehicle_name;
		  $route_name_rdb[] = $row->route_name_mor;
		  //$remark_rdb[] = $row->remark_mor;
		  $query2 = "SELECT DISTINCT vehicle_assignment.device_imei_no FROM vehicle_assignment,vehicle WHERE vehicle_assignment.vehicle_id = vehicle.vehicle_id AND ".
			" vehicle.vehicle_name = '$row->vehicle_name' AND vehicle_assignment.status=1";				
			$result2 = mysql_query($query2,$DbConnection); 
			$row2 = mysql_fetch_object($result2);
			$vehicle_imei_rdb[] = $row2->device_imei_no;
		} 
	}
}
?>  