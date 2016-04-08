<?php
	
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include("coreDb.php");  
	$root=$_SESSION['root'];
	
	include_once('tree_hierarchy_information.php');
	echo "source_vehicle##";
	
	$third_party_vehicle_id=array();	
	$third_party_vehicle_id=getVehicleIdThirdPartyVehicleAssign($account_id,$destination_account_id,$DbConnection);
	
	/*
	$querydesaid="SELECT vehicle_id from third_party_vehicle_assignment WHERE admin_account_id='$account_id' AND third_party_account_id='$destination_account_id' and status=1";
	//echo $querydesaid;
	$resultdesaid=mysql_query($querydesaid,$DbConnection);
	while($row=mysql_fetch_object($resultdesaid))
	{
		$third_party_vehicle_id[]=$row->vehicle_id;
	}
	*/
	
	//print_r( $third_party_vehicle_id);
	echo"
		<fieldset class='manage_cal_vehicle'>
				<legend>
					<strong>
						Vehicle
					</strong>
				</legend> 
				<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>
					<tr>
						<td colspan='3'>
							&nbsp;<INPUT TYPE='checkbox' name='all_vehicle' onclick='javascript:select_all_assigned_vehicle(this.form);'>
							<font size='2'>
								Select All
							</font>"."												
						</td>																														
					</tr>";
					get_user_vehicle($root,$account_id);
			echo'</table>	
			</fieldset>	
			
	<br><center>
	<input type="button" value="Update"  id="enter_button" onclick="javascript:return action_manage_vehicle_thirdparty(\'src/php/action_manage_vehicle_thirdparty.php\');">&nbsp;        
	';

	
	function get_user_vehicle($AccountNode,$account_id)
	{
		global $vehicleid;
		global $vehicle_cnt;
		global $td_cnt;
		global $DbConnection;
		if($AccountNode->data->AccountID==$account_id)
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
						/*$query="SELECT vehicle_id FROM escalation_alert_assignment WHERE vehicle_id='$vehicle_id' AND status='1'";
						//echo "query=".$query;
						$result=mysql_query($query,$DbConnection);
						$num_rows=mysql_num_rows($result);
						if($num_rows==0)
						{	*/						
							common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
						//}
						if($td_cnt==4)
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
			get_user_vehicle($AccountNode->child[$i],$account_id);
		}
	}
			
	function common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$option_name)
	{	
		//$td_cnt++;
		global $td_cnt;
		global $third_party_vehicle_id;
		//print_r($third_party_vehicle_id);
		if($td_cnt==1)
		{
			echo'<tr>';
		}
		
		//date_default_timezone_set('Asia/Calcutta');
		$current_date = date('Y-m-d');

		$xml_file = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
		//echo "xml_file=".$xml_file."<br>";
	
		if(file_exists($xml_file))
		{
			if (in_array($vehicle_id, $third_party_vehicle_id))
			{
				echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_id.'" checked></td>
			   <td class=\'text\'>
				 <font color="darkgreen">'.$vehicle_name.'('.$vehicle_id.')</font>                
			   </td>';
			}
			else
			{
				echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_id.'"></td>
			   <td class=\'text\'>
				 <font color="darkgreen">'.$vehicle_name.'('.$vehicle_id.')</font>                
			   </td>';
			}
		
		}
		else
		{
			if (in_array($vehicle_id, $third_party_vehicle_id))
			{
				echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_id.'" checked></td>
				<td class=\'text\'>
				  <font color="grey">'.$vehicle_name.'('.$vehicle_id.')</font>
				</td>';
			}
			else
			{
					echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_id.'"></td>
				<td class=\'text\'>
				  <font color="grey">'.$vehicle_name.'('.$vehicle_id.')</font>
				</td>';
			}
			
		}
		if($td_cnt==4)
		{ 
			echo'</tr>';
		}

	}
?>