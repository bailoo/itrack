<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('user_type_setting.php');
	include_once('coreDb.php');

	$vehicle_id = $_POST['manage_vehicle_id']; 
	$account_id_local = $_POST['account_id_local1'];  
	$DEBUG = 0;
	
	 
	$row=getCategoryVehicle($vehicle_id,$DbConnection);
	$cat=$row[0];
  
	$row=getUserIdUserTypeNameUserType($cat,$DbConnection);
	$category_id  = $row[0];
	$category_name  = $row[1];
	
	$row_user_type_id=getUserTypeIdAccountFeature($account_id_local,$DbConnection);	
	
	$data=getDetailAllUserType($row_user_type_id,$DbConnection);
	$feature_size=0;	
	foreach($data as $dt)
	{
	    $user_type_id[$feature_size]=$dt['user_type_id'];
		$user_type_name[$feature_size]=$dt['user_type_name'];	
		$feature_size++;
	}
	
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
  echo"<input type='hidden' name='sim_account_id' id='sim_account_id' value='$account_id'>";
	echo'<form name="manage1" method="post">
		<center>
			<input type="hidden" name="vehicle_id" id="vehicle_id" value="'.$vehicle_id.'">
			<fieldset class="manage_fieldset">
				<legend>
					<strong>Edit '.$report_type.'<strong></legend>
						<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
							<tr>
								<td>Name</td>
								<td>&nbsp;:&nbsp;</td>
								<td> 
								<input type="text" name="vehicle_name" id="vehicle_name" value="'.$vehicle_name.'"> </td>
							</tr>	
							<tr>
								<td>Number</td>
								<td>&nbsp;:&nbsp;</td>
								<td> <input type ="text" name="vehicle_number" id="vehicle_number" value="'.$vehicle_number.'"> </td>
							</tr>
							<tr>
								<td>Sim Number</td>
								<td>&nbsp;:&nbsp;</td>
								<td> <input type ="text" name="sim_number" id="sim_number" value="'.$sim_number.'"> </td>
							</tr>
							<tr>
								<td>Mobile Number</td>
								<td>&nbsp;:&nbsp;</td>
								<td> <input type ="text" name="mobile_number" id="mobile_number" value="'.$mobile_number.'"> </td>
							</tr>';
							if($report_type!="Person")
							{
						echo'<tr>
								<td>Max Speed</td>
								<td>&nbsp;:&nbsp;</td>
								<td> <input type ="text" name="max_speed" id="max_speed" value="'.$max_speed.'"> </td>
							</tr>  		
							<tr>
								<td>Tag</td>
								<td>&nbsp;:&nbsp;</td>
								<td> <input type ="text" name="vehicle_tag" id="vehicle_tag" value="'.$vehicle_tag.'"> </td>
							</tr>';
							}
						echo'<tr>
								<td>Type</td>
								<td>&nbsp;:&nbsp;</td>
								<td>
									<select name="vehicle_type" id="vehicle_type">';
										if($vehicle_type!="")
										echo'<option value="'.$vehicle_type.'" selected>'.ucfirst($vehicle_type).'</option>
											<option value="car">Car</option>
											<option value="truck">Truck</option>
											<option value="bus">Bus</option>
											<option value="motorbike">MotorBike</option>
									</select>
								</td>
							</tr>';
					echo'<tr>
									<td>Category</td>
									<td>&nbsp;:&nbsp;</td>
									<td>											
										<select id="category">
                      <option value="select">Select</option>';
										for($i=0;$i<$feature_size;$i++)
										{
										  if($category_id==$user_type_id[$i])
										  {
											  echo"<option value='".$user_type_id[$i]."' selected>".$user_type_name[$i]."</option>";
										  }
										  else
										  {
											  echo"<option value='".$user_type_id[$i]."'>".$user_type_name[$i]."</option>";
										  }                    
										}																						
								echo'</select>													
									</td>
								</tr>';
							$temp_type="edit_action,".$report_type;							
						echo'<tr>                    									
								<td align="center"  colspan="3">
								<div style="height:10px"></div>
								<input type="button" Onclick="javascript:action_manage_vehicle(this.form, \''.$temp_type.'\')" value="Enter">&nbsp;
								<input type="reset" value="Clear">&nbsp;								
								</td>
							</tr>
						</table>
					</fieldset>
				</center>
				<center><a href="javascript:show_option(\'manage\',\'vehicle\');" class="back_css">&nbsp;<b>Back</b></a></center>
			</form>';
			include_once('manage_loading_message.php');
			
	?>