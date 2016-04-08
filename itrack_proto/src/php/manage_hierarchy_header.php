<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	
	$root=$_SESSION['root'];
	//echo "<br>root=".$root->data->AccountID;
	
	$group=array(array());
	$group_cnt;
	$user_cnt=0;////////for users block to store first account id
	$first_account_id; ////////for users block to store first account id
	$vehicleid=array();
	$vehicle_cnt;
	$ColumnNo; 
	$RowNo;
	$MaxColumnNo;  
	$MaxColumnNo=0; 
	$ColumnNo=0;
	$RowNo=0;	
	$edit_account_id = $_POST['edit'];
	//$display_type = $_POST['display_type1']; 
	$common_div_option = $_POST['common_div_option1'];
	$div_option_values = $_POST['div_option_values1'];
	$field_name=$common_div_option;
	if($field_name=="group")
	{
		$field_name='account_id';
	}	
	$function_string='get_'.$common_div_option.'_vehicle';
	//echo "<br>function_str=".$function_string." ,divopt=".$div_option_values." ,commondiv=".$common_div_option." ,disptype=".$display_type;
	$group_cnt=0;	

$td_cnt=0;	
//////////////// FUNCTIONS ////////////////////////////////////////////
		
function common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$option_name)
{	
  //$td_cnt++;
  global $td_cnt;
  if($td_cnt==1)
  {
    echo'<tr>';
  }
  //date_default_timezone_set('Asia/Calcutta');
  $current_date = date('Y-m-d');

  $xml_file = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
  // echo "xml_file=". $xml_file;	
	//echo $vehicleSerial[$i];
	if(file_exists($xml_file))
	{								
   
 // echo "td_cnt=".$td_cnt."<br>";
	   echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_id.'"></td>
		  <td class=\'text\'>&nbsp;<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$vehicle_id.')"><font color="darkgreen">'.$vehicle_name.'</font>&nbsp;('.$option_name.')</A></td>';
  }
  else
  {
	   echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_imei.'"></td>
		  <td class=\'text\'>&nbsp;<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$vehicle_id.')">'.$vehicle_name.'&nbsp;('.$option_name.')</A></td>';
  }
  
	if($td_cnt==3)
	{ 
	   echo'</tr>';
	}

}
				
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
