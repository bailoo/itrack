<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	$account_id_local1 = $_POST['account_id_local'];	$vehicle_display_option1 = $_POST['vehicle_display_option'];	
	$options_value1 = $_POST['options_value'];
	
	if($options_value1!="all")
	{	
		$options_value2=explode(",",$options_value1);		$option_size=sizeof($options_value2);		
	}


	$option_string="";
		
	echo'<form name="manage1" method="post">
		<table width="100%">
			<tr>
				<td align="center">
					<table border=1>
						<tr>
							<td>
								<fieldset class="manage_fieldset" id="exist">
									<legend><strong>Existing Vehicle 1</strong></legend>		 
										<table class="manage_interface" border="0" align="center">';
											if($options_value1!="all")
											{
												for($i=0;$i<$option_size;$i++)
												{
													echo'<tr>
															<td>'.$options_value2[$i].'</td>
														</tr>';	
								
													$query="SELECT * from vehicle WHERE account_id='$account_id_local1' AND $vehicle_display_option1='$options_value2[$i]' AND status='1'";
													//echo "query=".$query;
													$result=mysql_query($query,$DbConnection);
													$flag=0;
													$vehicle_cnt=0;
													getVehicleIdVehicleNameDetailVehicel($account_id_local1,$vehicle_display_option1,$options_value2[$i],$DbConnection)
													$data=getDetailAllSchedule($account_id_local,$DbConnection);            							
													foreach($data as $dt)
													{
														$vehicle_id=$dt['vehicle_id'];
														$vehicle_name=$dt['vehicle_name'];
													echo'<tr>
															<td>
																<input type="radio" name="vehicle_id" value="'.$vehicle_id.'">
															</td>
															<td>
																'.$vehicle_name.'
															</td>
														</tr>';									
													}									
												}
											}
											else if($options_value1=="all")
											{
											
												$dataNext=getVehicleIdVehicleNameDetailVehicelNext($account_id_local1,$DbConnection);
												$flag=0;
												$vehicle_cnt=0;												           							
												foreach($data as $dt)
												{
													$vehicle_id=$dt['vehicle_id'];
													$vehicle_name=$dt['vehicle_name'];
												 echo'<tr>
														<td>
															<input type="radio" name="vehicle_id" value="'.$vehicle_id.'">
														</td>
														<td>
															'.$vehicle_name.'
														</td>
													</tr>';									
												}
											}									
									echo'</table>             
								</fieldset>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<center><input type="button" Onclick="javascript:action_manage_vehicle(this.form, \'edit\')" value="Edit">&nbsp;
        <input type="button" Onclick="javascript:action_manage_vehicle(this.form, \'delete\')" value="Delete"><center></form><br>
		<center><a href="javascript:show_option(\'manage\',\'vehicle\');" class="back_css">&nbsp;<b>Back</b></a></center>';
	
	
	?>