<?php
include_once('Hierarchy.php');	
include_once('util_session_variable.php');	
include_once('util_php_mysql_connectivity.php');
include_once('coreDb.php');

$DEBUG=0;	
$AccountNode = $_SESSION['root'];
$post_action_type = $_POST['action_type'];

if($post_action_type == "update")
{
    //$account_id1 = $account_id;
    $post_imei=trim($_POST['imei']);			
    $post_driver_name= $_POST['driver_name'];				
    $post_driver_mobile = $_POST['driver_mobile']; 
    date_default_timezone_set('Asia/Calcutta'); 
    $create_date = date('Y-m-d H:i:s');

    //echo "AccountID=".$account_id." ,Imei=".$post_imei." ,driverName=".$post_driver_name." ,Mobile=".$post_driver_mobile." ,CreateDate=".$create_date;
    $vehicle_id = get_vehicle_id($DbConnection, $imei);
    //echo "\nVehicleID=".$vehicle_id;
    $result1=updateVehicle_Detail($account_id,$vehicle_id,$post_driver_name,$post_driver_mobile,$post_imei,$DbConnection);
    $result2=insertVehicleDriverHistory_Detail($account_id,$vehicle_id, $post_imei,$post_driver_name,$post_driver_mobile,$create_date,$DbConnection);
    
    if($result1 && $result2) {
        update_vehicle_info($post_imei, $post_driver_name, $post_driver_mobile);
        echo"driver_updated##Driver info updated Successfully!";
    } else {
        echo "Error updating Driver Info!";
    }
}

//## UPDATE TREE WITH DRIVER DETAIL
function update_vehicle_info($imei, $driver_name, $driver_mobile)
{       global $AccountNode;
	$flag=0;
        //echo "<br>Size=".$AccountNode->data->VehicleCnt;
	for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
	{
            /*$vehicle_id_local = $AccountNode->data->VehicleID[$j];
            $vehicle_type_local = $AccountNode->data->VehicleType[$j];
            $vehicle_tag_local = $AccountNode->data->VehicleTag[$j];
            $vehicle_name_local = $AccountNode->data->VehicleName[$j];*/
            $vehicle_imei_no = $AccountNode->data->DeviceIMEINo[$j];
            /*$vehicle_number_1 = $AccountNode->data->VehicleNumber[$j];
            $driver_name_1 = $AccountNode->data->DriverName[$j];
            $mobile_number_1 = $AccountNode->data->MobileNumber[$j];
            $max_speed_local = $AccountNode->data->VehicleMaxSpeed[$j];
            $vehicle_fuel_voltage = $AccountNode->data->VehicleFuelVoltage[$j];
            $vehicle_tank_Capacity = $AccountNode->data->VehicleTankCapacity[$j];
            $manufacturer_name = $AccountNode->data->ManufacturerName[$j];*/

            if($vehicle_imei_no==$imei)
            {
                //echo "Matched=".$driver_name." ,EarlierDriver=".$AccountNode -> data -> DriverName[$j];
                $AccountNode -> data-> DriverName[$j] = $driver_name;
                $AccountNode -> data-> MobileNumber[$j] = $driver_mobile;
                //echo "<br>NewDriver=".$AccountNode -> data -> DriverName[$j];
            }
	}
	/*$ChildCount=$AccountNode->ChildCnt;
	if($ChildCount==0)
	{
		return null;
	}
	for($i=0;$i<$ChildCount;$i++)
	{
		$vehicle_info_local = get_vehicle_info($AccountNode->child[$i],$imei);
		
		if($vehicle_info_local!=null)
		{
			return $vehicle_info_local;
		}
	}*/
}
