<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	$vehicle_id = $_POST['manage_vehicle_id'];  
	$DEBUG = 0;

	$dataV = getDetailAllVehicleArray($vehicle_id,$DbConnection);

	foreach($dataV as $dt)
	{
		$vehicle_id=$dt['vehicle_id'];
		$vehicle_name=$dt['vehicle_name'];
		$vehicle_number=$dt['vehicle_number'];
		$sim_number=$dt['sim_number'];
		$mobile_number=$dt['mobile_number'];
		$max_speed=$dt['max_speed'];
		$vehicle_tag=$dt['vehicle_tag'];
		$vehicle_type=$dt['vehicle_type'];
		
	}
  
	echo'<form name="manage1" method="post">
		<center>
			<input type="hidden" name="vehicle_id" id="vehicle_id_edit" value="'.$vehicle_id.'">
			<fieldset class="manage_fieldset">
				<legend>
					<strong>Edit Vehicle<strong></legend>
						<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
							<tr>
								<td>Name</td>
								<td>&nbsp;:&nbsp;</td>
								<td> 
								<input type="text" name="vehicle_name" id="vehicle_name_edit" value="'.$vehicle_name.'"> </td>
							</tr>	
							<tr>
								<td>Number</td>
								<td>&nbsp;:&nbsp;</td>
								<td> <input type ="text" name="vehicle_number" id="vehicle_number_edit" value="'.$vehicle_number.'"> </td>
							</tr>
							<tr>
								<td>Max Speed</td>
								<td>&nbsp;:&nbsp;</td>
								<td> <input type ="text" name="max_speed" id="max_speed_edit" value="'.$max_speed.'"> </td>
							</tr>  		
							<tr>
								<td>Tag</td>
								<td>&nbsp;:&nbsp;</td>
								<td> <input type ="text" name="vehicle_tag" id="vehicle_tag_edit" value="'.$vehicle_tag.'"> </td>
							</tr> 
							<tr>
								<td>Type</td>
								<td>&nbsp;:&nbsp;</td>
								<td>
									<select name="vehicle_type_edit" id="vehicle_type">';
										if($vehicle_type!="")
										echo'<option value="'.$vehicle_type.'" selected>'.ucfirst($vehicle_type).'</option>
											<option value="car">Car</option>
											<option value="truck">Truck</option>
											<option value="bus">Bus</option>
											<option value="motorbike">MotorBike</option>
									</select>
								</td>
							</tr> 								
							<tr>                    									
								<td align="center"  colspan="3">
								<div style="height:10px"></div>
								<input type="button" Onclick="javascript:action_manage_vehicle(this.form, \'edit_action\')" value="Enter">&nbsp;
								<input type="reset" value="Clear">&nbsp;								
								</td>
							</tr>
						</table>
					</fieldset>
				</center>
				<center><a href="javascript:show_option(\'manage\',\'vehicle\');" class="menuitem">&nbsp;<b>Back</b></a></center>
			</form>';
			
	?>