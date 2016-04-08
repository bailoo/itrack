<?php 
function get_vehicle_id($AccountNode,$imei)
{ 
	$flag=0;
	for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
	{ 		
		$vehicle_imei_no = $AccountNode->data->DeviceIMEINo[$j];		
		if($vehicle_imei_no==$imei)
		{
			$vehicle_info_local=$AccountNode->data->VehicleID[$j];
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

function get_vehicle_name($AccountNode,$vid_func)
{ 
	$flag=0;
	for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
	{ 		
		$vehicle_id_local = $AccountNode->data->VehicleID[$j];		
		if($vehicle_id_local==$vid_func)
		{
			$vehicle_info_local=$AccountNode->data->VehicleName[$j];
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
?>
