<?php
function get_route_db_detail($shift,$route_type)
{
    global $DbConnection;
    global $account_id;
    global $UniqueVehicle;
    global $VehicleIMEI;

    foreach($UniqueVehicle as $vehicle) {
        //$remark_rdb[] = $row->remark_mor;
        $query2 = "SELECT DISTINCT vehicle_assignment.device_imei_no FROM vehicle_assignment,vehicle,vehicle_grouping WHERE vehicle_assignment.vehicle_id = vehicle.vehicle_id AND ".
              " vehicle.vehicle_name = '$vehicle' AND vehicle_assignment.status=1 AND vehicle_grouping.vehicle_id=vehicle_assignment.vehicle_id AND vehicle_grouping.status=1 AND vehicle_grouping.account_id='$account_id'";				
        //echo "\n".$query2;
        $result2 = mysql_query($query2,$DbConnection); 			
        $numrows = mysql_num_rows($result2);

	//echo "\nNumrows=".$numrows;
        if($numrows>0)
        {
            $row2 = mysql_fetch_object($result2);
            $VehicleIMEI[$vehicle] = trim($row2->device_imei_no);
	//	echo "\nVehicleIMEI[$vehicle]=".$VehicleIMEI[$vehicle];
        }
	else
	{
	     $VehicleIMEI[$vehicle] = $vehicle;
	}
    }	
}
?>  
