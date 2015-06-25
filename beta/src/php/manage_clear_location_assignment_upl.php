<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include_once('manage_hierarchy_header1.php');
	$root=$_SESSION['root'];
	$common_id1=$_POST['common_id'];
	//echo "common_id=".$common_id1;
	echo"<div style='height:10px'></div>";
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';	
	$Query="SELECT landmark_id,landmark_name FROM landmark WHERE account_id='$common_id1' AND status=1";
	$Result=mysql_query($Query,$DbConnection);
	echo '<center>
			<strong>
				 UPL Schedule Delete Assignment
			</strong>
			<br>
		</center>'; 
	echo"<br>			
			<form name='manage1' method='post'>
				<center>
					
						";									
								echo get_user_vehicle($root,$common_id1);
								echo"</select>
					
					<div style='height:10px'></div>
					<div id='clear_assigned_location'></div>";
				echo'<div id="intermediate_check_halt_time" style="display:none"></div>';	
				echo'<div style="height:10px"></div>
						<!--<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_schedule_upl(\'assign\')" value="Assign">
						&nbsp;
						<input type="reset" value="Cancel">	-->					
						<div style="height:10px"></div>
						<a href="javascript:show_option(\'manage\',\'schedule_upl\');" class="back_css">
							&nbsp;
							<b>
								Back
							</b>
						</a>
				</center>
			</form>';							
			
			function common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$option_name)
			{		
				global $td_cnt;
				if($td_cnt==1)
				{
					//echo'<tr>';
					echo '<select id="vehicle_id" name="vehicle_id" onChange="show_and_clear_assigned_location_upl(this.value)" >
					<option value="select">Select</option>
					';
				}
				date_default_timezone_set('Asia/Calcutta');
				$current_date = date('Y-m-d');
				$xml_file = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";			
				if(file_exists($xml_file))
				{
					echo'
						<option value="'.$vehicle_id.'">
							<font color="darkgreen">
								'.$vehicle_name.'
							</font>	
						</option>
						<!--<td align="left">
							<INPUT TYPE="radio"  name="vehicle_id" VALUE="'.$vehicle_id.'" onclick=javascript:show_and_clear_assigned_location_upl('.$vehicle_id.');>
						</td>
						<td class=\'text\'>
							<font color="darkgreen">
								'.$vehicle_name.'
							</font>
						
						</td>
						-->
						';
				}
				else
				{
					echo'
						<option value="'.$vehicle_id.'">
							<font color="grey">
								'.$vehicle_name.'
							</font>	
						</option>
						<!--
						<td align="left">
							<INPUT TYPE="radio"  name="vehicle_id" VALUE="'.$vehicle_id.'" onclick="javascript:show_and_clear_assigned_location_upl('.$vehicle_id.');">
						</td>
						<td class=\'text\'>
							<font color="grey">
								'.$vehicle_name.'
							</font>
							
						</td>
						-->
						';
				}
				if($td_cnt==7)
				{ 
					//echo'</tr>';
				}
			}

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
								$query="SELECT vehicle_id FROM visit_area_assignment WHERE vehicle_id='$vehicle_id' AND status='1'";
								//echo "query=".$query;
								$result=mysql_query($query,$DbConnection);
								$num_rows=mysql_num_rows($result);
								//if($num_rows==0)
								{							
									common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
								}
								/*if($td_cnt==7)
								{
									$td_cnt=0;
								}*/
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
?>  
