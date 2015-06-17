<?php 
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

$root=$_SESSION['root'];
$vehicleid=array();
$vehicle_cnt;	
$td_cnt=0;	
  	//date_default_timezone_set('Asia/Calcutta');		
	$current_date = date('Y-m-d');
//////////////// FUNCTIONS ////////////////////////////////////////////		
/*function common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$option_name)
{	
  //date_default_timezone_set('Asia/Calcutta');
  $current_date = date('Y-m-d');

  $xml_file = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
  // echo "xml_file=". $xml_file;	
	//echo $vehicleSerial[$i];
	if(file_exists($xml_file))
	{								
    echo'<option VALUE="'.$vehicle_imei.'">'.$vehicle_name.' * </option>';
	}
	else
	{							
    echo'<option VALUE="'.$vehicle_imei.'">'.$vehicle_name.'</option>';
	}	  
	//echo '<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$vehicle_id.')">'.$vehicle_name.'&nbsp;('.$option_name.')</A>';
} */
  function common_display_vehicle($vehicle_name_arr,$vehicleid_arr,$color)
  {
      global $type;
      foreach($vehicle_name_arr as $vehicle)
      { 
          if($color=="green")
          {
              echo'<option VALUE="'.$vehicleid_arr[$vehicle].'">'.$vehicle.' * </option>';		
          }
          else
          {
            echo'<option VALUE="'.$vehicleid_arr[$vehicle].'">'.$vehicle.'</option>';		
          }
        }     
  }
						
function get_all_vehicle($local_account_id,$div_option_values)
{
	//echo "<br>accnode=".$AccountNode." ,div_option_values".$div_option_values;
  global $root;
  global $vehicleid;
	global $vehicle_cnt;  

	$vehicle_cnt=0;
	//$user_name = GetUserName($AccountNode,$div_option_values);	
  if($div_option_values == "all")
    PrintAllVehicle($root, $local_account_id);
}	
	   	
function PrintAllVehicle($root, $local_account_id)
{
  global $vehicleid;
	global $vehicle_cnt;
	global $td_cnt;
	$type = 0;
	global $current_date;
	$vehicle_name_arr=array();
//	$imei_arr=array();
	$vehicleid_arr=array();
	$vehicle_color=array(); 
	
	if($root->data->AccountID==$local_account_id)
	{
	  $td_cnt =0;
		for($j=0;$j<$root->data->VehicleCnt;$j++)
		{			    
			$vehicle_id = $root->data->VehicleID[$j];
			$vehicle_name = $root->data->VehicleName[$j];
			$vehicle_imei = $root->data->DeviceIMEINo[$j];
			
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
				 // $td_cnt++;
					//common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
				
						$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
						//echo "<br>xml_current=".$xml_current;
					if (file_exists($xml_current))
					{
					 $color="green";
					 $vehicle_name_arr[$color][] =$vehicle_name; 
					 //$imei_arr[$color][$vehicle_name]=$vehicle_imei;
					 $vehicleid_arr[$color][$vehicle_name]=$vehicle_id;
					}
					else
					{ 
            $color="gray";      					  
						$vehicle_name_arr[$color][] =$vehicle_name; 
					 // $imei_arr[$color][$vehicle_name]=$vehicle_imei;
					  $vehicleid_arr[$color][$vehicle_name]=$vehicle_id;
					}							
				}
			}
		}
	}
	$color="green";common_display_vehicle($vehicle_name_arr[$color],$vehicleid_arr[$color],$color);
  $color="gray"; common_display_vehicle($vehicle_name_arr[$color],$vehicleid_arr[$color],$color);
	$ChildCount=$root->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
	 PrintAllVehicle($root->child[$i],$local_account_id);
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
		
function get_vehicle_tag_vehicle ($local_account_id,$div_option_values)
{
	global $vehicleid;
	global $vehicle_cnt;
  global $root;  

	$vehicle_tag =explode(",",$div_option_values);
	$sizeofvehicletag=sizeof($vehicle_tag);

	for($j=0;$j<$sizeofvehicletag;$j++)
	{
		$vehicle_cnt=0;			
		PrintVehicleTag($root, $local_account_id,$vehicle_tag[$j]);
	}
}		
	
function PrintVehicleTag($AccountNode, $local_account_id,$vehicletag)
{
	global $vehicleid;
	global $vehicle_cnt;
  global $td_cnt;
  global $current_date;
	$vehicle_name_arr=array();
	//$imei_arr=array();
	$vehicleid_arr=array();
	$vehicle_color=array();  

  if($AccountNode->data->AccountID == $local_account_id)
  {
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
  				///	$td_cnt++;
  					//common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
  					$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
  					if (file_exists($xml_current))
  					{
  					 $color="green";
  					 $vehicle_name_arr[$color][] =$vehicle_name; 
  					 //$imei_arr[$color][$vehicle_name]=$vehicle_imei;
  					 $vehicleid_arr[$color][$vehicle_name]=$vehicle_id;
  					 
  					}
  					else
  					{ 
              $color="gray";      					  
  						$vehicle_name_arr[$color][] =$vehicle_name; 
  					  //$imei_arr[$color][$vehicle_name]=$vehicle_imei;
  					  $vehicleid_arr[$color][$vehicle_name]=$vehicle_id;
  					}	
  				}
  			}
  		}
  	}  
  }
	 $color="green";common_display_vehicle($vehicle_name_arr[$color],$vehicleid_arr[$color],$color);
  $color="gray"; common_display_vehicle($vehicle_name_arr[$color],$vehicleid_arr[$color],$color);
	$ChildCount=$AccountNode->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
	 PrintVehicleTag($AccountNode->child[$i],$local_account_id,$vehicletag);
	}
}
	
function  get_vehicle_type_vehicle($local_account_id,$div_option_values)
{
	global $vehicleid;
	global $vehicle_cnt; 
  global $root; 

	$vehicle_type =explode(",",$div_option_values);
	$sizeofvehicletype=sizeof($vehicle_type);

	for($j=0;$j<$sizeofvehicletype;$j++)
	{
		$vehicle_cnt=0;			
		//echo "<br>acnode=".$local_account_id." ,vtype=".$vehicle_type[$j];
    PrintVehicleType($root, $local_account_id, $vehicle_type[$j]);			
	}
}
	
function PrintVehicleType($AccountNode, $local_account_id, $vehicletype)
{
	//echo "<br>Acnode1=".$AccountNode;    	
	//$ChildCount=$AccountNode->ChildCnt;
	//echo "<br>childcnt=".$ChildCount;	
  global $vehicleid;
	global $vehicle_cnt;
  global $td_cnt; 
  global $current_date;
	$vehicle_name_arr=array();
	//$imei_arr=array();
	$vehicleid_arr=array();
	$vehicle_color=array(); 
		
  if($AccountNode->data->AccountID == $local_account_id)
  {  
    for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
  	{
  		$vehicle_type_local = $AccountNode->data->VehicleType[$j];
  		if($vehicle_type_local==$vehicletype)
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
  					
  				//	common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
  					$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
  					if (file_exists($xml_current))
  					{
  					 $color="green";
  					 $vehicle_name_arr[$color][] =$vehicle_name; 
  					 //$imei_arr[$color][$vehicle_name]=$vehicle_imei;
  					 $vehicleid_arr[$color][$vehicle_name]=$vehicle_id;
  					 
  					}
  					else
  					{ 
              $color="gray";      					  
  						$vehicle_name_arr[$color][] =$vehicle_name; 
  					  //$imei_arr[$color][$vehicle_name]=$vehicle_imei;
  					  $vehicleid_arr[$color][$vehicle_name]=$vehicle_id;
  					}	
  				}
  			}
  		}
  	}
  }
  $color="green";common_display_vehicle($vehicle_name_arr[$color],$vehicleid_arr[$color],$color);
  $color="gray"; common_display_vehicle($vehicle_name_arr[$color],$vehicleid_arr[$color],$color);
	$ChildCount=$AccountNode->ChildCnt;
	//echo "<br>childcnt=".$ChildCount;
	for($i=0;$i<$ChildCount;$i++)
	{ 
	 //echo "<br>acnodechild=".$AccountNode->child[$i]." ,vtype=".$vehicletype;
   PrintVehicleType($AccountNode->child[$i],$local_account_id,$vehicletype);
	}
}    
	
?>
