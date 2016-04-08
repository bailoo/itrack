<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	
	$root=$_SESSION['root'];
	
function get_group_vehicle($AccountNode,$div_option_values)
{
 // echo "<br>In function".$AccountNode->data->AccountID;
	global $vehicleid;
	global $vehicle_cnt;  

	$groupid =explode(",",$div_option_values);
	$sizeofgroup=sizeof($groupid);

	for($j=0;$j<$sizeofgroup;$j++)
	{
		PrintGroupVehicle($AccountNode,$groupid[$j]);
	}
}
			
function GetGroupName($AccountNode,$groupid)
{
	if(($AccountNode->data->AccountGroupID!=null) && ($AccountNode->data->AccountGroupID==$groupid))
	{
		return $AccountNode->data->AccountGroupName;
	}
	else
	{
		$ChildCount=$AccountNode->ChildCnt;			
		for($i=0;$i<$ChildCount;$i++)
		{ 
			$tmpGroupName = GetGroupName($AccountNode->child[$i],$groupid);
			if($tmpGroupName!=null)
			{
				return $tmpGroupName;
			}
		}
		return null;	
	}
}

function PrintGroupVehicle($AccountNode,$groupid)
{
	global $vehicleid;
	global $vehicle_cnt;
	global $td_cnt;
  
 // echo "account_id=".$AccountNode->data->AccountGroupID." group_id=".$groupid."<br>"; 	
	//global $DbConnection;
	if($AccountNode->data->AccountGroupID==$groupid)
	{
	  // echo "in if"; 		
    $td_cnt=0;
		for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
		{
			//echo "<br>loopv";
      $vehicle_id = $AccountNode->data->VehicleID[$j];
			$vehicle_name = $AccountNode->data->VehicleName[$j];
			$vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
		//	echo "<br>vimei=".$vehicle_imei." ,vehicleid=".$vehicle_id;
			
			if($vehicle_id!=null)
			{
				for($i=0;$i<$vehicle_cnt;$i++)
				{
					if($vehicleid[$i]==$vehicle_id)
					{
						break;
					}
				}			
				if($i>=$vehicle_cnt)
				{						
					//echo "<br>in if";
          $vehicleid[$vehicle_cnt]=$vehicle_id;
					$vehicle_cnt++;
					$td_cnt++;
					common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
				
					if($td_cnt==3)
          {
            $td_cnt=0;
          }
					//echo "td_count=".$td_cnt."<br>";
				//	echo "<br>vimei=".$vehicle_imei." ,vehicleid=".$vehicle_id;
									
				}
			}
		}
	}
	$ChildCount=$AccountNode->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
	PrintGroupVehicle($AccountNode->child[$i],$groupid);
	}
}	
	
function get_user_vehicle($AccountNode,$div_option_values)
{
	global $vehicleid;
	global $vehicle_cnt;  

	$userid =explode(",",$div_option_values);		
	$sizeofuserid=sizeof($userid);

	for($j=0;$j<$sizeofuserid;$j++)
	{
		$vehicle_cnt=0;
		$user_name =GetUserName($AccountNode,$userid[$j]);	
	PrintUserVehicle($AccountNode,$userid[$j]);
	}
}	
	   
function GetUserName($AccountNode,$userid)
{
	if(($AccountNode->data->AccountID!=null) && ($AccountNode->data->AccountID==$userid))
	{
	return $AccountNode->data->AccountName;
	}
	else
	{
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{
			$tmpUserName = GetUserName($AccountNode->child[$i],$userid);
			if($tmpUserName!=null)
			{
				return $tmpUserName;
			}
		}
		return null;	
	}
}
	
function PrintUserVehicle($AccountNode,$userid)
{
	global $vehicleid;
	global $vehicle_cnt;
	global $td_cnt;
	if($AccountNode->data->AccountID==$userid)
	{
	  $td_cnt =0;
		for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
		{			    
			$vehicle_id = $AccountNode->data->VehicleID[$j];
			$vehicle_name = $AccountNode->data->VehicleName[$j];
			$vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
			if($vehicle_id!=null)
			{
				for($i=0;$i<$vehicle_cnt;$i++)
				{
					if($vehicleid[$i]==$vehicle_id)
					{
					break;
					}
				}			
				if($i>=$vehicle_cnt)
				{
					$vehicleid[$vehicle_cnt]=$vehicle_id;
					$vehicle_cnt++;
				  $td_cnt++;
					common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
				
					if($td_cnt==3)
          {
            $td_cnt=0;
          }
				
				
				}
			}
		}
	}
	$ChildCount=$AccountNode->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
	PrintUserVehicle($AccountNode->child[$i],$userid);
	}
}

function get_user_type_vehicle($AccountNode,$div_option_values)
{
	global $vehicleid;
	global $vehicle_cnt;  

	$user_type =explode(",",$div_option_values);
	$sizeofusertype=sizeof($user_type);

	for($j=0;$j<$sizeofusertype;$j++)
	{
		$vehicle_cnt=0;		
		PrintUserTypeVehicle($AccountNode,$user_type[$j]);
	}
}	
		
function PrintUserTypeVehicle($AccountNode,$usertype)
{
	global $vehicleid;
	global $vehicle_cnt; 		
	global $td_cnt;
	if($AccountNode->data->AccountType==$usertype)
	{
	 $td_cnt=0;
		for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
		{			    
			$vehicle_id = $AccountNode->data->VehicleID[$j];
			$vehicle_name = $AccountNode->data->VehicleName[$j];
			$vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
			if($vehicle_id!=null)
			{
				for($i=0;$i<$vehicle_cnt;$i++)
				{
					if($vehicleid[$i]==$vehicle_id)
					{
						break;
					}
				}			
				if($i>=$vehicle_cnt)
				{
					$vehicleid[$vehicle_cnt]=$vehicle_id;
					$vehicle_cnt++;
					 $td_cnt++;
					common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
				
					if($td_cnt==3)
          {
            $td_cnt=0;
          }
				}
			}
		}
	}
	$ChildCount=$AccountNode->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
		PrintUserTypeVehicle($AccountNode->child[$i],$usertype);
	}
}

function get_vehicle_tag_vehicle ($AccountNode,$div_option_values)
{
	global $vehicleid;
	global $vehicle_cnt;  

	$vehicle_tag =explode(",",$div_option_values);
	$sizeofvehicletag=sizeof($vehicle_tag);

	for($j=0;$j<$sizeofvehicletag;$j++)
	{
		$vehicle_cnt=0;			
		PrintVehicleTag($AccountNode,$vehicle_tag[$j]);
	}
}		
	
function PrintVehicleTag($AccountNode,$vehicletag)
{
	global $vehicleid;
	global $vehicle_cnt;
  global $td_cnt; 
	for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
	{
		$vehicle_tag_local = $AccountNode->data->VehicleTag[$j];
		if($vehicle_tag_local==$vehicletag)
		{
			$vehicle_id = $AccountNode->data->VehicleID[$j];
			$vehicle_name = $AccountNode->data->VehicleName[$j];
			$vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];

			if($vehicle_id!=null)
			{
				for($i=0;$i<$vehicle_cnt;$i++)
				{
					if($vehicleid[$i]==$vehicle_id)
					{
					break;
					}
				}			
				if($i>=$vehicle_cnt)
				{
					$vehicleid[$vehicle_cnt]=$vehicle_id;
					$vehicle_cnt++;
					 $td_cnt++;
					common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
				
					if($td_cnt==3)
          {
            $td_cnt=0;
          }
				}
			}
		}
	}
	$ChildCount=$AccountNode->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
	PrintVehicleTag($AccountNode->child[$i],$vehicletag);
	}
}
	
function  get_vehicle_type_vehicle($AccountNode,$div_option_values)
{
	global $vehicleid;
	global $vehicle_cnt;  

	$vehicle_type =explode(",",$div_option_values);
	$sizeofvehicletype=sizeof($vehicle_type);

	for($j=0;$j<$sizeofvehicletype;$j++)
	{
		$vehicle_cnt=0;			
		PrintVehicleType($AccountNode,$vehicle_type[$j]);			
	}
}
	
function PrintVehicleType($AccountNode,$vehicletype)
{
	global $vehicleid;
	global $vehicle_cnt;
  global $td_cnt; 
	for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
	{
		if($AccountNode->data->VehicleType[$j]==$vehicletype)
		{
			$vehicle_id = $AccountNode->data->VehicleID[$j];
			$vehicle_name = $AccountNode->data->VehicleName[$j];
			$vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];			
			if($vehicle_id!=null)
			{
				for($i=0;$i<$vehicle_cnt;$i++)
				{
					if($vehicleid[$i]==$vehicle_id)
					{
					break;
					}
				}			
				if($i>=$vehicle_cnt)
				{
					$vehicleid[$vehicle_cnt]=$vehicle_id;
					$vehicle_cnt++;
					 $td_cnt++;
					common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
				
					if($td_cnt==3)
          {
            $td_cnt=0;
          }
				}
			}
		}
	}
	$ChildCount=$AccountNode->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
	PrintVehicleType($AccountNode->child[$i],$vehicletype);
	}
}    
	
?>
