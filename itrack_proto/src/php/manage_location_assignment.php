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
	echo '<center>
			<strong>
				 Wock Hardt Schedule Assignment
			</strong>
			<br>
		</center>'; 
	echo"<br>			
			<form name='manage1' method='post'>
				<center>
					<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>";
						get_user_vehicle($root,$common_id1);
					echo"</table>
					<div style='height:10px'></div>					
						<!--<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>					
							<tr>
								<td colspan='3'>
									&nbsp;&nbsp;<INPUT TYPE='checkbox' name='all_visit_area' onclick='javascript:select_all_visit_area(this.form);'>
									<font size='2'>
										Select All
									</font>"."												
								</td>																														
							</tr>
						</table>
						<div style='height:10px'></div>	
						<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>					
							<tr>";	
							$dataWL=getDetailAllWockhardLocation($common_id1,$DbConnection);					
							if(sizeof($dataWL)>0)
							{  
								$k=0;
								foreach($dataWL as $dt)
								{
									$location_id=$dt['location_id'];
									$location_name=$dt['location_name'];
									
									if($k==0)
									{
										echo"<tr>";
									}  
										echo"<td>
												&nbsp<INPUT TYPE='checkbox' name='visit_area_id[]' VALUE='$location_id'>
													<font color='blue' size='2'>
														".$location_name."&nbsp;&nbsp;&nbsp;
													</font>"."												
											</td>";				
										$k++;																																	

										if($k==8)
										{
											echo "</tr>";
											$k=0;
										}
								}
							}
							else
							{
								echo"<font color='blue' size='2'>NO VISIT AREA FOUND IN THIS ACCOUNT</font>";
							}
						echo"</td>
					</tr>
				</table>-->
				<div style='height:10px'></div>";
				echo'<table border="0" align=center class="menu" cellspacing="2" cellpadding="2"> 						
						<tr>
							<td>Date From</td>
							<td>&nbsp;:&nbsp;</td>
							<td>
								<input type="text" id="date1" value="" size="10" maxlength="19">
									<a href=javascript:NewCal_SD("date1","yyyymmdd",false,24)>
										<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
									</a>														
							</td>
							<td>
								<div style="width:8px"></div>
							</td>
							<td>Date to</td>
							<td>&nbsp;:&nbsp;</td>
							<td>
								<input type="text" id="date2" value="" size="10" maxlength="19">
									<a href=javascript:NewCal_SD("date2","yyyymmdd",false,24)>
										<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
									</a>												
							</td>
							<td>
								<div style="width:8px"></div>
							</td>
							<td valign="top">
								<input type="checkbox" name="day_check" onclick=show_date_option(this.checked)>
							</td>
							<td>
								By Day	
							</td>
						</tr>
					</table>
					<div style="height:3px"></div>
					<table border="0" align=center class="menu" cellspacing="2" cellpadding="2" width="40%"> 
						<tr id="day_tr" style="display:none"> 
							<td colspan="6">
								<table width="100%" border=0>
									<tr>
										<td align="center">
											<INPUT type="button" value="Add Row" onclick=addRow_schedule("dataTable") />&nbsp;
											<INPUT type="button" value="Delete Row" onclick=deleteRow("dataTable") />
										</td>
									</tr>
										<td align="center">
											<TABLE id="dataTable" border="0">
												<TR>
													<TD>&nbsp;&nbsp;
													Day&nbsp;&nbsp;&nbsp;&nbsp;
													</TD>
													<TD>
														&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;
													</TD>
													<TD>
														<SELECT name="country">
															<OPTION value="select">Select</OPTION>
															<OPTION value="1">Sunday</OPTION>
															<OPTION value="2">Monday</OPTION>
															<OPTION value="3">Tuesday</OPTION>
															<OPTION value="4">Wednesday</OPTION>
															<OPTION value="5">Thursday</OPTION>
															<OPTION value="6">Friday</OPTION>
															<OPTION value="7">Saturday</OPTION>
														</SELECT>
													</TD>
												</TR>
											</TABLE>	
										</td>										
									</tr>
								</table>
							</td>
						</tr>
						</table>
						<div style="height:3px"></div>
						<table border="0" align=center class="menu" cellspacing="2" cellpadding="2"> 	
						<tr>
							<td>Min Operation Time</td>
							<td>&nbsp;:&nbsp;</td>
							<td>
								<input type="text" id="min_ot_hr"  size="3" maxlength="19"  value="00">&nbsp;hr&nbsp;
								<input type="text" id="min_ot_minute" size="3" maxlength="19" value="00">&nbsp;min&nbsp;																								
							</td>
						</tr>
						<tr>
							<td>Max Operation Time</td>
							<td>&nbsp;:&nbsp;</td>
							<td>
								<input type="text" id="max_ot_hr" size="3" maxlength="19" value="23">&nbsp;hr&nbsp;
								<input type="text" id="max_ot_minute" size="3" maxlength="19" value="59">&nbsp;min&nbsp;																								
							</td>
						</tr>
						<!--<tr>
							<td>Allow Rest Time</td>
							<td>&nbsp;:&nbsp;</td>
							<td>
								<input type="text" id="rest_time_hr"  size="3" maxlength="19"  value="00">&nbsp;hr&nbsp;
								<input type="text" id="rest_time_min" size="3" maxlength="19" value="00">&nbsp;min&nbsp;																								
							</td>
						</tr>-->
												
						<tr>
							<td>Min Distance Travelled</td>
							<td>&nbsp;:&nbsp;</td>
							<td><input type="text" id="min_distance" size="7"></td>
						</tr>
						<tr>
							<td>Max Distance Travelled</td>
							<td>&nbsp;:&nbsp;</td>
							<td><input type="text" id="max_distance" size="7"></td>
						</tr>
						<tr>
							<td>Non Poi Halt Time</td>
							<td>&nbsp;:&nbsp;</td>
							<td>
								<input type="text" id="nonpoi_halt_hr" size="3" maxlength="19" value="00">&nbsp;hr&nbsp;
								<input type="text" id="nonpoi_halt_min" size="3" maxlength="19" value="00">&nbsp;min&nbsp;																								
							</td>
						</tr>
					</table>
					<table border="0" align=center class="menu" cellspacing="2" cellpadding="2">
						<tr>
							<td>No of Halt</td>
							<td>&nbsp;:&nbsp;</td>
							<td>
								<select id="halt_numbers" onchange=javascript:show_selected_halt_no(this.value);>
									<option value="select">Select</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
								</select>
							</td>						
						</tr>						
					</table>
					<div id="min_max_halt" style="display:none"></div>';
					echo'<table border="0" align=center class="menu" cellspacing="2" cellpadding="2">				
						<tr>
							<td>Base Station</td>
							<td>&nbsp;:&nbsp;</td>
							<td>
								<select id="base_station" onchange=javascript:show_intermediate_halt_tr(this.value);>
									<option value="select">Select</option>';
									$data=getDetailAllLandmarkNext($common_id1,$DbConnection);
									foreach($data as $dt)
									{
										$landmark_id=$dt['landmark_id'];
										$landmark_name=$dt['landmark_name'];
									
										echo'<option value='.$landmark_id.'>'.$landmark_name.'</option>';
									}
							echo'</select>
							</td>						
						</tr>			
						<tr id="intermediate_halt_tr" style="display:none">
							<td>No Of Intermediate Halt Check Time</td>
							<td>&nbsp;:&nbsp;</td>
							<td>
								<select id="intermediate_halt_numbers" name="intermediate_halt_numbers" onchange=javascript:show_selected_intermediate_halt_no(this.value);>
									<option value="select">Select</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>									
								</select>
							</td>						
						</tr>						
					</table>					
				<div id="intermediate_check_halt_time" style="display:none"></div>';			
				echo '<br>						
						<div style="height:10px"></div>
						<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_schedule(\'assign\')" value="Assign">
						&nbsp;
						<input type="reset" value="Cancel">
						<br>
						<div style="height:10px"></div>
						<a href="javascript:show_option(\'manage\',\'schedule\');" class="back_css">
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
					echo'<tr>';
				}
				date_default_timezone_set('Asia/Calcutta');
				$current_date = date('Y-m-d');
				$xml_file = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";			
				if(file_exists($xml_file))
				{
					echo'<td align="left">
							<INPUT TYPE="radio"  name="vehicle_id" VALUE="'.$vehicle_id.'">
						</td>
						<td class=\'text\'>
							<font color="darkgreen">
								'.$vehicle_name.'
							</font>
							<!--<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$vehicle_id.')">
								<font color="darkgreen">
									'.$vehicle_name.'
								</font>&nbsp;('.$option_name.')
							</A>-->
						</td>';
				}
				else
				{
					echo'<td align="left">
							<INPUT TYPE="radio"  name="vehicle_id" VALUE="'.$vehicle_id.'">
						</td>
						<td class=\'text\'>
							<font color="grey">
								'.$vehicle_name.'
							</font>
							<!--&nbsp;
								<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$vehicle_id.')">
									'.$vehicle_name.'&nbsp;('.$option_name.')
								</A>-->
						</td>';
				}
				if($td_cnt==6)
				{ 
					echo'</tr>';
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
														
								common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
								
								if($td_cnt==6)
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
?>  
