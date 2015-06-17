<?php 
function get_vehicle_info($AccountNode,$imei)
{ 
	$flag=0;
	for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
	{
		$vehicle_id_local = $AccountNode->data->VehicleID[$j];
		$vehicle_type_local = $AccountNode->data->VehicleType[$j];
		$vehicle_tag_local = $AccountNode->data->VehicleTag[$j];
		$vehicle_name_local = $AccountNode->data->VehicleName[$j];
		$vehicle_imei_no = $AccountNode->data->DeviceIMEINo[$j];
		$vehicle_number_1 = $AccountNode->data->VehicleNumber[$j];
		$mobile_number_1 = $AccountNode->data->MobileNumber[$j];
		$max_speed_local = $AccountNode->data->VehicleMaxSpeed[$j];
		$vehicle_fuel_voltage = $AccountNode->data->VehicleFuelVoltage[$j];
		$vehicle_tank_Capacity = $AccountNode->data->VehicleTankCapacity[$j];
		
		if($vehicle_imei_no==$imei)
		{	
			$iovalueandtypelocalarr = $AccountNode->data->DeviceIOTypeValue[$j];			
			//print_r($iovalueandtypearr);
			$tmp_iotype_str="";
			if($iovalueandtypelocalarr[$vehicle_imei_no]!="ionone")
			{
					$tmp_iotype_str=$iovalueandtypelocalarr[$vehicle_imei_no];					
			}
			else
			{
					$tmp_iotype_str="tmp_str";
					//echo "vehicle_name=".$vehicle_name." tmp_iotype_str1=".$tmp_iotype_str."<br>";
			}			
			$flag=1;
			$vehicle_info_local=$vehicle_name_local.",".$vehicle_type_local.",".$vehicle_number_1.",".$vehicle_tag_local.",".$max_speed_local.",".$vehicle_fuel_voltage.",".$vehicle_tank_Capacity.",".$tmp_iotype_str.",".$mobile_number_1;
			return $vehicle_info_local;			
		}
	}
	$ChildCount=$AccountNode->ChildCnt;
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
	}
}

function get_vehicle_number($AccountNode,$imei)
{ 
	$flag=0;
	for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
	{
		$vehicle_imei_no = $AccountNode->data->DeviceIMEINo[$j];
		$vehicle_number_1 = $AccountNode->data->VehicleNumber[$j];	
		
		if($vehicle_imei_no==$imei)
		{		
			$flag=1;
			$vehicle_info_local=$vehicle_number_1;
			return $vehicle_info_local;		
		}
	}
	$ChildCount=$AccountNode->ChildCnt;
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
	}
}

function single_account_vehicle_info($AccountNode,$imei,$coming_account_id)
{ 
	$flag=0;
	//echo "coming_account_id=".$coming_account_id."local_account_id=".$AccountNode->data->AccountID."<br>";
	if($coming_account_id==$AccountNode->data->AccountID)
	{
		for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
		{
			$vehicle_type_local = $AccountNode->data->VehicleType[$j];
			$vehicle_name_local = $AccountNode->data->VehicleName[$j];
			$vehicle_imei_no = $AccountNode->data->DeviceIMEINo[$j];
			$vehicle_number_1 = $AccountNode->data->VehicleNumber[$j];
                        $vehicle_id_1 = $AccountNode->data->VehicleID[$j];
			
			if($vehicle_imei_no==$imei)
			{
				$flag=1;
				//$vehicle_info_local=$vehicle_name_local.",".$vehicle_type_local.",".$vehicle_number_1;
				$vehicle_info_local=$vehicle_name_local.",".$vehicle_type_local.",".$vehicle_number_1.",".$vehicle_id_1;
                               // echo "vehicle_info_local=".$vehicle_info_local."<br>";
                                return $vehicle_info_local;		
			}
		}
	}
	$ChildCount=$AccountNode->ChildCnt;
	if($ChildCount==0)
	{
		return null;
	}
	for($i=0;$i<$ChildCount;$i++)
	{
		$vehicle_info_local = single_account_vehicle_info($AccountNode->child[$i],$imei,$coming_account_id);		
		if($vehicle_info_local!=null)
		{
			return $vehicle_info_local;
		}
	}
}
?>
