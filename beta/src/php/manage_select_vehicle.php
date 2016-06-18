<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('user_type_setting.php');
	$account_id_local1 = $_POST['account_id_local'];	
	$vehicle_display_option1 = $_POST['vehicle_display_option'];	
	$options_value1 = $_POST['options_value'];
	echo "<input type='hidden' id='account_id_local' value='".$account_id_local1."'>";
	
	if($options_value1!="all")
	{	
		$options_value2=explode(",",$options_value1);		
		$option_size=sizeof($options_value2);		
	}

	$option_string="";		
	echo'<form name="manage1" method="post">
		<table width="100%">
			<tr>
				<td align="center">
					<table border=0>
						<tr>
							<td>
								<fieldset class="manage_fieldset_new" id="exist">
									<legend><strong>Existing '.$report_type.'</strong></legend>							
										<div style="height:430px;overflow:auto">
											<table class="manage_interface" border="0" align="center">';
												if($options_value1!="all")
												{
													for($i=0;$i<$option_size;$i++)
													{
														echo'<tr>
																<td>'.$options_value2[$i].'</td>
															</tr>';	
														//$query="SELECT * from vehicle WHERE vehicle_id IN (SELECT vehicle_id FROM vehicle_grouping WHERE account_id=$account_id_local1 and status=1) AND $vehicle_display_option1='$options_value2[$i]' AND status='1'";
														$query="SELECT vehicle.* from vehicle,vehicle_grouping WHERE vehicle.vehicle_id=vehicle_grouping.vehicle_id AND".
																" vehicle_grouping.account_id=$account_id_local1 AND vehicle.$vehicle_display_option1='$options_value2[$i]'".
																" AND vehicle_grouping.status=1 AND vehicle.status=1";
														//echo "query=".$query;
														$result=mysql_query($query,$DbConnection);
														$flag=0;
														$vehicle_cnt=0;
														echo'<tr>';
														$tr_count=0;
														while($row=mysql_fetch_object($result))
														{											
															if($tr_count>=4)
															{
																echo "</tr><tr>";
																$tr_count=0;
															}
															echo'<td>
																	<input type="radio" class="form-control" name="vehicle_id" value="'.$row->vehicle_id.'">
																</td>
																<td>
																	'.$row->vehicle_name.'
																</td>';
															$tr_count++;
														}
														echo"</tr>";
													}
												}
												else if($options_value1=="all")
												{
													//$query="SELECT * from vehicle WHERE vehicle_id IN (SELECT vehicle_id FROM vehicle_grouping WHERE account_id=$account_id_local1 and status=1) AND status='1'";
													$query="SELECT vehicle.* FROM vehicle,vehicle_grouping WHERE vehicle.vehicle_id=vehicle_grouping.vehicle_id".
														" AND vehicle_grouping.account_id=$account_id_local1 AND vehicle_grouping.status=1 AND vehicle.status=1";
											
													//echo "query=".$query."<br>";
													$result=mysql_query($query,$DbConnection);
													$flag=0;
													$vehicle_cnt=0;
													echo'<tr>';
													$tr_count=0;
													while($row=mysql_fetch_object($result))
													{										
														if($tr_count>=4)
														{
															echo "</tr><tr>";
															$tr_count=0;
														}
														echo'<td>
																<input type="radio" class="form-control" name="vehicle_id" value="'.$row->vehicle_id.'">
															</td>
															<td>
																'.$row->vehicle_name.'
															</td>';
														$tr_count++;
														
													}
													echo"</tr>";
												}
													
										echo'</table> 
										</div>									
								</fieldset>	
							</td>
						</tr>
					</table>					
				</td>
			</tr>
		</table>
	<center>
		<input type="button" class="btn btn-default" Onclick="javascript:action_manage_vehicle(this.form, \'edit\')" value="Edit">&nbsp;
		<input type="button" class="btn btn-default" Onclick="javascript:action_manage_vehicle(this.form, \'delete\')" value="Delete">
	<center>
	</form><br>
		<center><a href="javascript:show_option(\'manage\',\'vehicle\');" class="back_css">&nbsp;<b>Back</b></a></center>';
	include_once('manage_loading_message.php');
	?>