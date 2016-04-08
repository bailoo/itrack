<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$query1="SELECT vehicle_color from account_preference WHERE account_id='$account_id'";
	$result1=mysql_query($query1,$DbConnection);
	$row1=mysql_fetch_object($result1);
	$vehicle_color1=$row1->vehicle_color;
	$vcolor = explode(':',$vehicle_color1); //account_name:active:inactive
	$vcolor1 = "#".$vcolor[0];
	$vcolor2 = "#".$vcolor[1];
	$vcolor3 = "#".$vcolor[2];
	$root=$_SESSION['root'];
	$vehicleid=array();
	$vehicle_cnt;	
	$td_cnt=0;
	$type_tag;
	$common_id_local=$_POST['common_id'];		
	$group_status1=$_POST['group_status'];	
	$report_type_title=$_POST['report_type1'];

	if($report_type_title=="Trip Report") //////coming from session
	{
		$filename = 'report_trip.php'; 
	}
	else if($report_type_title=="Vehicle Movement Report") //////coming from session
	{
		$filename = 'report_trip_vehicle_movement.php'; 
	}
	else if($report_type_title=="Trip Summary Report") //////coming from session
	{
		$filename = 'report_trip_summary.php'; 
	}
	$current_date = date('Y-m-d');
	
	function common_display_vehicle($vehicle_name_arr,$vehicleid_or_imei,$color,$vehicle_type_arr)
	{
		global $type;
		global $report_station_halt_option;			
		if(sizeof($vehicle_name_arr)>0)
		{
			natcasesort($vehicle_name_arr);
			foreach($vehicle_name_arr as $vehicle)
			{ 
				$cnt++;          
				if($cnt==1)
				{
					echo'<tr>';
				}         		
				echo'<td align="left">
						<INPUT TYPE="radio"  name="vehicleserial" VALUE="'.$vehicleid_or_imei[$vehicle].'">
					</td>
					<td class=\'text\'> 				
						<font color="'.$color.'">
							'.$vehicle.'
						</font>';
						if($type==1)
						{
							echo'('.$vehicle_type_arr[$vehicle].')';
						}
				echo'</td>';         
				if($cnt==3)
				{
					echo'</tr><tr>';$cnt=0;
				}
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
		global $type_tag;
		global $vcolor1;
		global $vcolor2;
		global $vcolor3;
		global $title;	
		$type = 0;
		global $current_date;
		$vehicle_name_arr=array();
		$imei_arr=array();
		$vehicleid_or_imei_arr=array();
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
						$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
						if (file_exists($xml_current))
						{
							$color = $vcolor2;
							$vehicle_name_arr[$color][] =$vehicle_name;							
							$vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_id;
					
						}
						else
						{ 
							$color = $vcolor3;      					  
							$vehicle_name_arr[$color][] =$vehicle_name;							
							$vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_id;						
						}															
					}
				}
			}
		}
		$color = $vcolor2;common_display_vehicle($vehicle_name_arr[$color],$vehicleid_or_imei_arr[$color],$color,'');
		$color = $vcolor3; common_display_vehicle($vehicle_name_arr[$color],$vehicleid_or_imei_arr[$color],$color,'');
		$ChildCount=$root->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{ 
			PrintAllVehicle($root->child[$i],$local_account_id);
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
		global $type;
		global $vcolor1;
		global $vcolor2;
		global $vcolor3;  

		$type = 1;
		global $current_date;
		$vehicle_name_arr=array();
		$imei_arr=array();
		$vehicle_color=array();
		$vehicle_tag_arr=array();
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
							$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
							if (file_exists($xml_current))
							{
								$color = $vcolor2;
								$vehicle_name_arr[$color][] =$vehicle_name; 
								$imei_arr[$color][$vehicle_name]=$vehicle_id;
							}
							else
							{ 
								$color = $vcolor3;      					  
								$vehicle_name_arr[$color][] =$vehicle_name; 
								$imei_arr[$color][$vehicle_name]=$vehicle_id;
							}
							$vehicle_tag_arr[$color][$vehicle_name]=$vehicle_tag_local;
						}
					}
				}
			}  
		}
		$color = $vcolor2; common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color,$vehicle_tag_arr[$color]);
		$color = $vcolor3; common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color,$vehicle_tag_arr[$color]);
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
		global $type;
		$type =1;
		$vehicle_type =explode(",",$div_option_values);
		$sizeofvehicletype=sizeof($vehicle_type);
		for($j=0;$j<$sizeofvehicletype;$j++)
		{
			$vehicle_cnt=0;
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
		global $vcolor1;
		global $vcolor2;
		global $vcolor3;

		$vehicle_name_arr=array();
		$imei_arr=array();
		$vehicle_color=array();
		$vehicle_type_arr=array();
			
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
							$td_cnt++;
							//common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$vehicle_type_local);
							$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
							if (file_exists($xml_current))
							{
							 $color = $vcolor2;
							 $vehicle_name_arr[$color][] =$vehicle_name; 
							 $imei_arr[$color][$vehicle_name]=$vehicle_id; 					
							}
							else
							{ 
					  $color = $vcolor3;      					  
								$vehicle_name_arr[$color][] =$vehicle_name; 
							  $imei_arr[$color][$vehicle_name]=$vehicle_id;
							}
							   $vehicle_type_arr[$color][$vehicle_name]=$vehicle_type_local;
						}
					}
				}
			}
		}
		$color = $vcolor2; common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color,$vehicle_type_arr[$color]);
		$color = $vcolor3; common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color,$vehicle_type_arr[$color]);
		$ChildCount=$AccountNode->ChildCnt;
		//echo "<br>childcnt=".$ChildCount;
		for($i=0;$i<$ChildCount;$i++)
		{ 
			PrintVehicleType($AccountNode->child[$i],$local_account_id,$vehicletype);
		}
	}
?>
