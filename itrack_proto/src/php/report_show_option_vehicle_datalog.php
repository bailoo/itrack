<?php 
include_once('Hierarchy.php');	include_once('util_session_variable.php');	include_once('util_php_mysql_connectivity.php');
$root=$_SESSION['root'];	$vehicleid=array();		$vehicle_cnt;	$td_cnt=0;	$type_tag;

$account_id_local = $_POST['account_id_local1'];	
$vehicle_display_option = $_POST['vehicle_display_option1'];
$function_string='get_'.$vehicle_display_option.'_vehicle'; 	

$options_value1 = $_POST['options_value'];
$options_value2=explode(",",$options_value1);			
$option_size=sizeof($options_value2);
$option_string="";

$options_type1 = $_POST['options_type'];
echo"portal_vehicle_information##";

echo'<fieldset class="manage_fieldset">
			<legend><strong>'.strtoupper($options_type1).'</strong></legend>
		<table border=0  cellspacing=0 cellpadding=0>
			<tr>
				<td align="center">							
					<div style="overflow: auto;height:auto; width: 650px;" align="center">
						<table border=0 cellspacing=0 cellpadding=0 align="center">';					
							echo'<tr>
									<td height="10px" align="left" colspan="6" class=\'text\'>
										&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_vehicle(this.form);\'>
											&nbsp;&nbsp;Select All
									</td>
								</tr>';                 
										$function_string($account_id_local1,$options_value1);													
								echo'
						</table>
					</div>
				</td>
			</tr>
		</table>';
		
		//date_default_timezone_set('Asia/Calcutta');
		$StartDate=date('Y/m/d 00:00:00');
		$EndDate=date('Y/m/d H:i:s');						
	  
    echo'
			<br>
		 <table width="470" align="center" border="0">
					<TR valign="top" cellspacing="0" cellpadding="0" align="left"> 
						<TD  height="24" width="70">
							<font size="2">Date From</font></td>
							<td align="center"><input type="text" id="date1" name="StartDate" value="'.$StartDate.'" size="16" maxlength="19">
							</td>
							<td><a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)><img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>
						</TD>

						<TD  height="24" width="90" align="right">
							<font size="2">Date To</font></td>
							<td align="center"><input type="text" id="date2" name="EndDate" value="'.$EndDate.'" size="16" maxlength="19">
							</td>
							<td>
							<a href=javascript:NewCal("date2","yyyymmdd",true,24)><img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>
						</TD>
					<input type="hidden" name="date_id" value="1">		
					</TR>																	
				</table>    
    
    <br>
		<table border=0  cellspacing=0 cellpadding=0>
			<tr>
				<td align="right" class="text"><input type="radio" name="rec" value="10"></td>
				<td align="left" class="text" >last 10</td> 
				<td align="right" class="text"><input type="radio" name="rec" value="30"></td>
				<td align="left" class="text" >last 30</td>                                                   
				<td align="right" class="text">&nbsp;<input type="radio" name="rec" value="100"></td>
				<td align="left" class="text">last 100</td>                                                 
				<td align="right" class="text">&nbsp;<input type="radio" name="rec" value="all"></td>
				<td align="left" class="text" valign="top">all</td>
			</tr>
		</table>
		<br>';
	
		echo'<input type="button" value="Show Record" onclick="javascript:return action_report_datalog(this.form,\'specify_vehicle\');">
		<br>
		<div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>
	</fieldset>';	

//////////////// FUNCTIONS ////////////////////////////////////////////		
	function common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$option_name)
	{	
		global $type_tag;
		global $options_type1;
		global $td_cnt;
		if($td_cnt==1)
		{
			echo'<tr>';
		}
			//date_default_timezone_set('Asia/Calcutta');
			$current_date = date('Y-m-d');
			$xml_file = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
			if(file_exists($xml_file))
			{  
				echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicleserial[]" VALUE="'.$vehicle_imei.'"></td>
					<td class=\'text\'>
						&nbsp;<font color="green">';
								if($options_type1=='vehicle_name')
								{
									echo $vehicle_name;
								}
								else if($options_type1=="device_serial")
								{
									echo $vehicle_imei;
								}
								/*else  if($options_type1=="sim_no")
								{
									echo $vehicle_imei;
								}*/
							echo'</font>&nbsp;'; 
							if($type_tag==1) {echo'('.$option_name.')';} 
				echo'</td>';
			}
			else
			{
			   echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicleserial[]" VALUE="'.$vehicle_imei.'"></td>
				  <td class=\'text\'><font color="red">';
					if($options_type1=='vehicle_name')
					{
						echo $vehicle_name;
					}
					else if($options_type1=="device_serial")
					{
						echo $vehicle_imei;
					}
					/*else  if($options_type1=="sim_no")
					{
						echo $vehicle_imei;
					}*/
					if($type_tag==1) {echo'('.$option_name.')';}
			   echo'</font></td>';
			}
			if($td_cnt==3)
			{ 
				echo'</tr>';
			}
	}
						
function get_all_vehicle($local_account_id,$div_option_values)
{
	global $root;	global $vehicleid;	global $vehicle_cnt; 	$vehicle_cnt=0;
	if($div_option_values == "all")
	PrintAllVehicle($root, $local_account_id);
}	
	   	
function PrintAllVehicle($root, $local_account_id)
{
	global $vehicleid;	global $vehicle_cnt;	global $td_cnt;		global $type_tag;	$type_tag = 0;

	if($root->data->AccountID==$local_account_id)
	{
		$td_cnt =0;
		for($j=0;$j<$root->data->VehicleCnt;$j++)
		{			    
			$vehicle_id = $root->data->VehicleID[$j];	$vehicle_name = $root->data->VehicleName[$j];	$vehicle_imei = $root->data->DeviceIMEINo[$j];
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
	global $vehicleid;	global $vehicle_cnt;	global $root; 
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
	global $vehicleid;	global $vehicle_cnt;	global $td_cnt; 	global $type_tag;	$type_tag =1;
	if($AccountNode->data->AccountID == $local_account_id)
	{
		for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
		{
			$vehicle_tag_local = $AccountNode->data->VehicleTag[$j];
			if($vehicle_tag_local==$vehicletag)
			{
				$vehicle_id = $AccountNode->data->VehicleID[$j];	$vehicle_name = $AccountNode->data->VehicleName[$j];	
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
						common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$vehicle_tag_local);
						if($td_cnt==3)
						{
							$td_cnt=0;
						}
					}
				}
			}
		}  
	}
	$ChildCount=$AccountNode->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
		PrintVehicleTag($AccountNode->child[$i],$local_account_id,$vehicletag);
	}
}
	
function  get_vehicle_type_vehicle($local_account_id,$div_option_values)
{
	global $vehicleid;	global $vehicle_cnt; 	global $root; 	global $type_tag;	$type_tag =1;
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
	global $vehicleid;	global $vehicle_cnt;	global $td_cnt; 		
	if($AccountNode->data->AccountID == $local_account_id)
	{  
		for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
		{
			$vehicle_type_local = $AccountNode->data->VehicleType[$j];
			if($vehicle_type_local==$vehicletype)
			{
				$vehicle_id = $AccountNode->data->VehicleID[$j];	$vehicle_name = $AccountNode->data->VehicleName[$j];
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
						common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$vehicle_type_local);
					
						if($td_cnt==3)
						{
							$td_cnt=0;
						}
					}
				}
			}
		}
	}
	$ChildCount=$AccountNode->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
		PrintVehicleType($AccountNode->child[$i],$local_account_id,$vehicletype);
	}
}
?>
