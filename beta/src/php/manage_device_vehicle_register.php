<?php
	include_once('Hierarchy.php');	
	include_once('util_session_variable.php');	
	include_once('util_php_mysql_connectivity.php');
	include("user_type_setting.php");  
	
	$account_id_local1 = $_POST['account_id_local'];	
	$vehicle_display_option1 = $_POST['vehicle_display_option'];	
	$options_value1 = $_POST['options_value'];			
	if($options_value1!="all")
	{	
		$options_value2=explode(",",$options_value1);		
		$option_size=sizeof($options_value2);		
	}

	$option_string="";	
	$dataDIN=getDeviceImeiNoDAVAArray($account_id_local1,$DbConnection);	
	$dataVIVN=getVehicleIDVehicleNameVVG($account_id_local1,$DbConnection);
 
echo'<form name="manage1">
		<input type="hidden" id="local_account_id" value='.$account_id_local1.'>
		<input type="hidden" id="vehicle_display_option" value='.$vehicle_display_option1.'>
		<input type="hidden" id="options_value" value='.$options_value1.'>
		<br>
		<center>
		<table border=0 width="100%">
		<tr>
			<td align="center">
				<table width=90% >
					<tr>
					 <td>
						<fieldset class="assignment_manage_fieldset">
							<legend>
							<strong>';                  
							if($report_type=='Person')
							echo 'Device Person Registration';
							else
							echo 'Device Vehicle Registration';

							echo'</strong>								                
							</legend>	
							<div style="height:480px;overflow:auto">
									<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2" width=100%>
									<tr>
										<td valign="top">
											<fieldset class="">
												<legend>Select Device</legend>		
													<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">        
														<tr>
															<!--<td><input type="radio" name="left_search" value="1" onclick="javascript:this.form.ls.disabled=false;this.form.lo.disabled=true"></td>-->
															<td>By Search</td>
															<td>&nbsp;:&nbsp;</td>
															<td>
																<input type="text" class="form-control" name="ls" id="ls" onFocus="javascript:thisform.left_search[0].checked=true;" onkeyup="manage_availability(this.value, \'device_assignment\')" onmouseup="manage_availability(this.value,\'device_assignment\')" onchange="manage_availability(this.value, \'device_assignment\')">
															</td>
														</tr> 

														<tr valign="top">
															<!--<td><input type="radio" class="form-control" name="left_search" value="2" onclick="javascript:this.form.lo.disabled=false;this.form.ls.disabled=true"></td>-->
															<td>By Select</td>
															<td>&nbsp;:&nbsp;</td>
															<td valign="top">
																<table border="0" class="manage_interface">';              
																	if(sizeof($dataDIN)==0)
																	{
																		echo '<font color=red>No device Found</font>';
																	}
																	else
																	{
																		foreach($dataDIN as $dt)
																		{																			
																			$device_imei_no=$dt['device_imei_no'];
																			echo'
																			<tr>
																				<td>
																					<label class="radio-inline"><input type="radio" class="form-control" name="lo" value="'.$device_imei_no.'" onclick="javascript:set_device_option(\'ls\',\'lo\');">'.$device_imei_no.'</label>
																				</td>
																			</tr>';
																		} 
																	}          
																	echo'
																</table>
															</td>
														</tr>
													</table>
											</fieldset>  
										</td>    
									<td valign="top">
										<fieldset class="assignment_manage_fieldset">
											<legend>';
												if($report_type=='Person')
												echo'Select Person to Assign';
												else
												echo'Select Vehicle to Assign';
											echo'</legend>  

											<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">        
												<tr>
													<!--<td><input type="radio" class="form-control" name="right_search" value="1" onclick="javascript:this.form.rs.disabled=false;this.form.ro.disabled=true"></td>-->
													<td>By Search</td>
													<td>&nbsp;:&nbsp;</td>
													<td>
														<input type="text" class="form-control" name="rs" id="rs" onFocus="javascript:thisform.right_search[0].checked=true;" onkeyup="manage_availability(this.value, \'vehicle_assignment\')" onmouseup="manage_availability(this.value,\'vehicle_assignment\')" onchange="manage_availability(this.value, \'vehicle_assignment\')">
													</td>
												</tr>         
												<tr valign="top">
													<!--<td><input type="radio" class="form-control" name="right_search" value="2" onclick="javascript:this.form.ro.disabled=false;this.form.rs.disabled=true"></td>-->
													<td> By Select</td>
													<td>&nbsp;:&nbsp;</td>
													<td>
														<table class="manage_interface" border="0" align="center">';
															if($options_value1!="all")
															{
																for($i=0;$i<$option_size;$i++)
																{
																	echo'
																	<tr>
																		<td>'.$options_value2[$i].'</td>
																	</tr>';	
																	
																	$dataVIVNN=getVehicleIDVehicleNameVVGNext($vehicle_display_option1,$options_value2[$i],$DbConnection);
																	$flag=0;
																	$vehicle_cnt=0;
																	foreach($data as $dt)
																	{
																		$vehicle_id=$dt['vehicle_id'];	
																		$vehicle_name=$dt['vehicle_name'];
																		echo'<tr>
																				<td><input type="radio" name="ro" name="vehicle_id" value="'.$vehicle_id.','.$vehicle_name.'" onclick="javascript:set_vehicle_option(\'rs\',\'ro\');">'.$vehicle_name.'</td>
																			</tr>';								
																	}									
																}
															}
															else if($options_value1=="all")
															{
																if(sizeof($dataVIVN)==0)
																{
																	if($report_type=='Person')
																	echo '<font color=red>No Person Found</font>';
																	else
																	echo '<font color=red>No Vehicle Found</font>';
																}
																else
																{
																	foreach($dataVIVN as $dt)
																	{
																		$vehicle_id=$dt['vehicle_id'];	
																		$vehicle_name=$dt['vehicle_name'];
																		echo'
																		<tr>
																			<td>
																				<input type="radio"  name="ro" name="vehicle_id" value="'.$vehicle_id.','.$vehicle_name.'" onclick="javascript:set_vehicle_option(\'rs\',\'ro\');">'.$vehicle_name.'
																			</td>
																		</tr>';									
																	}
																}																
															}
															echo'
														</table> ';										  
														echo'
													</td>
												</tr>
											</table> 

										</fieldset>
									   </td>
									</tr>      
							
								</table>
							</div>
							<table>
								<tr>
									<td height="5px"></td>
								</tr>
							</table>
							  <center><input type="button" class="btn btn-default" name="enter_button" id="enter_button" Onclick="javascript:return action_manage_vehicle(manage1,\'register\')" value="Register">&nbsp;<input type="reset" class="btn btn-default" value="Cancel" onclick="javascript:thisform.enter_button.disabled=true;"></center>
					   </fieldset> 
					 </td>
				</tr>
			</table>
		</td>
	</tr>
 </table>
		<!--<a href="javascript:show_option(\'manage\',\'vehicle\');" class="back_css">&nbsp;<b>Back</b></a>-->
		<div id="available_message" align="center"></div>				
		</center>
	</form>
  ';	
  echo'<center><a href="javascript:show_option(\'manage\',\'vehicle\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 
include_once('manage_loading_message.php');
	?>