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
		//$query = "SELECT DISTINCT route_assignment2.vehicle_name,route_assignment2.route_name_ev FROM route_assignment2,vehicle WHERE route_assignment2.user_account_id='$account_id' AND NOT(route_assignment2.route_name_ev='') AND route_assignment2.vehicle_name LIKE CONCAT(vehicle.vehicle_name) AND route_assignment2.status=1 Order By route_assignment2.route_name_ev DESC";	
		$query = "SELECT DISTINCT route_assignment2.vehicle_name,route_assignment2.route_name_ev FROM route_assignment2,vehicle WHERE route_assignment2.user_account_id='$account_id' AND NOT(route_assignment2.route_name_ev='') AND route_assignment2.vehicle_name=vehicle.vehicle_name AND route_assignment2.status=1 Order By route_assignment2.route_name_ev DESC";	
		//$query = "SELECT DISTINCT vehicle_name,route_name_ev,remark_ev FROM route_assignment2 WHERE user_account_id='$account_id' AND NOT(route_name_ev='') AND status=1 AND route_name_ev='206013' OR route_name_ev='226573' Order By route_name_ev DESC";	
		//echo "EV:".$query;
		$result = mysql_query($query,$DbConnection); 

		while($row = mysql_fetch_object($result))
		{
			//$remark_rdb[] = $row->remark_ev;
			$query2 = "SELECT DISTINCT vehicle_assignment.device_imei_no FROM vehicle_assignment,vehicle,vehicle_grouping WHERE vehicle_assignment.vehicle_id = vehicle.vehicle_id AND ".
			" vehicle.vehicle_name = '$row->vehicle_name' AND vehicle_assignment.status=1 AND vehicle_grouping.vehicle_id=vehicle_assignment.vehicle_id AND vehicle_grouping.status=1 AND vehicle_grouping.account_id=231";				
			//echo "\n".$query2;
			$result2 = mysql_query($query2,$DbConnection); 			
			$numrows = mysql_num_rows($result2);
			//echo "\nNUM=".$numrows;
			if($numrows>0)
			{				
				$row2 = mysql_fetch_object($result2);
				$vehicle_name_rdb[] = $row->vehicle_name;
				$route_name_rdb[] = $row->route_name_ev;
				$vehicle_imei_rdb[] = $row2->device_imei_no;
			}
		} 
	}	
	else if($shift == "ZPMM")
	{		
		//$query = "SELECT DISTINCT route_assignment2.vehicle_name,route_assignment2.route_name_mor FROM route_assignment2,vehicle WHERE route_assignment2.user_account_id='$account_id' AND NOT(route_assignment2.route_name_mor='') AND route_assignment2.vehicle_name LIKE CONCAT(vehicle.vehicle_name) AND route_assignment2.status=1 Order By route_assignment2.route_name_mor DESC";				
		$query = "SELECT DISTINCT route_assignment2.vehicle_name,route_assignment2.route_name_mor FROM route_assignment2,vehicle WHERE route_assignment2.user_account_id='$account_id' AND NOT(route_assignment2.route_name_mor='') AND route_assignment2.vehicle_name=vehicle.vehicle_name AND route_assignment2.status=1 Order By route_assignment2.route_name_mor DESC";				
//		$query = "SELECT DISTINCT route_assignment3.vehicle_name,route_assignment3.route_name_mor FROM route_assignment3,vehicle WHERE route_assignment3.user_account_id='$account_id' AND NOT(route_assignment3.route_name_mor='') AND route_assignment3.vehicle_name IN('DL1LT1405','DL1LT1595') AND route_assignment3.vehicle_name=vehicle.vehicle_name AND route_assignment3.status=1 Order By route_assignment3.route_name_mor DESC";				
		//$query = "SELECT DISTINCT route_assignment2.vehicle_name,route_assignment2.route_name_mor FROM route_assignment2,vehicle WHERE route_assignment2.user_account_id='$account_id' AND NOT(route_assignment2.route_name_mor='') AND route_assignment2.vehicle_name IN('HR61A6121','DL1LS4308','DL1LG6582') AND route_assignment2.vehicle_name LIKE CONCAT(vehicle.vehicle_name) AND route_assignment2.status=1 Order By route_assignment2.route_name_mor DESC";				
		//$query = "SELECT DISTINCT route_assignment2.vehicle_name,route_assignment2.route_name_mor FROM route_assignment2,vehicle WHERE route_assignment2.user_account_id='$account_id' AND NOT(route_assignment2.route_name_mor='') AND route_assignment2.vehicle_name IN('DL1LS4308') AND route_assignment2.vehicle_name LIKE CONCAT(vehicle.vehicle_name) AND route_assignment2.status=1 Order By route_assignment2.route_name_mor DESC";				
		//$query = "SELECT DISTINCT route_assignment2.vehicle_name,route_assignment2.route_name_mor FROM route_assignment2,vehicle WHERE route_assignment2.user_account_id='$account_id' AND NOT(route_assignment2.route_name_mor='') AND route_assignment2.vehicle_name LIKE CONCAT(vehicle.vehicle_name) AND route_assignment2.status=1 Order By route_assignment2.route_name_mor DESC limit 5";				
		//echo "<br>MOR:".$query;
		$result = mysql_query($query,$DbConnection); 
		while($row = mysql_fetch_object($result))
		{
		  //$remark_rdb[] = $row->remark_mor;
		  $query2 = "SELECT DISTINCT vehicle_assignment.device_imei_no FROM vehicle_assignment,vehicle,vehicle_grouping WHERE vehicle_assignment.vehicle_id = vehicle.vehicle_id AND ".
			" vehicle.vehicle_name = '$row->vehicle_name' AND vehicle_assignment.status=1 AND vehicle_grouping.vehicle_id=vehicle_assignment.vehicle_id AND vehicle_grouping.status=1 AND vehicle_grouping.account_id=231";				
			//echo "<br>".$query2;
			$result2 = mysql_query($query2,$DbConnection); 			
			$numrows = mysql_num_rows($result2);
			if($numrows>0)
			{
				$row2 = mysql_fetch_object($result2);
				$vehicle_name_rdb[] = $row->vehicle_name;
				$route_name_rdb[] = $row->route_name_mor;
				//echo "<br>IMEI=".$row2->device_imei_no;
				$vehicle_imei_rdb[] = trim($row2->device_imei_no);
			}
		} 
	}
}
?>  
