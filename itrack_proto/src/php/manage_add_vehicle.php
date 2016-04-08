<?php
	include_once('Hierarchy.php');		
	include_once('util_session_variable.php');	
	include_once('util_php_mysql_connectivity.php'); 
	include_once('user_type_setting.php');
	$root=$_SESSION['root'];	$DEBUG=0;	
	//echo "add##"; //////for ajax function
	echo"<form name='manage1'>";
	echo'<div id="portal_vehicle_information">';	
	include_once('manage_checkbox_account_new.php');  	
	echo'</div>';
	echo"<input type='hidden' name='sim_account_id' id='sim_account_id' value='$account_id'>";
	echo"<input type='hidden' name='add_account_id' id='add_account_id' value='$account_id1'>"; 
	echo'<table width="100%">
			<tr>
				<td align="center">
					<fieldset class="manage_fieldset">
						<legend>
							<strong>Add '.$report_type.'<strong></legend>
								<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">       
									<tr>
										<td>Name</td>
										<td>&nbsp;:&nbsp;</td>
										<td><input type="text" name="vehicle_name" id="vehicle_name" onkeyup="manage_availability(this.value, \'vehicle\')" onmouseup="manage_availability(this.value,\'vehicle\')" onchange="manage_availability(this.value, \'vehicle\')"></td>
									</tr>	
									<tr>
										<td>Vehicle Number</td>
										<td>&nbsp;:&nbsp;</td>
										<td> <input type ="text" name="vehicle_number" id="vehicle_number"> </td>
									</tr>
									<tr>
										<td>Sim Number</td>
										<td>&nbsp;:&nbsp;</td>
										<td> <input type ="text" name="sim_number" id="sim_number"> </td>
									</tr>
									<tr>
										<td>Mobile Number</td>
										<td>&nbsp;:&nbsp;</td>
										<td> <input type ="text" name="mobile_number" id="mobile_number"> </td>
									</tr>									
									';
								if($report_type!="Person")
								{
								echo'<tr>
										<td>Max Speed</td>
										<td>&nbsp;:&nbsp;</td>
										<td> <input type ="text" name="max_speed" id="max_speed"> </td>
									</tr>  		
									<tr>
										<td>Tag</td>
										<td>&nbsp;:&nbsp;</td>
										<td> <input type ="text" name="vehicle_tag" id="vehicle_tag"> </td>
									</tr>';
								}
								if($report_type=="Person")
								{ 
								echo'<tr>
										<td>Type</td>
										<td>&nbsp;:&nbsp;</td>
										<td>
											<select name="vehicle_type" id="vehicle_type">
												<option value="select">Select</option> 											
												<option value="person">Person</option>
											</select>
										</td>
									</tr>';
								}
								else
								{
								  echo'<tr>
										<td>Type</td>
										<td>&nbsp;:&nbsp;</td>
										<td>
											<select name="vehicle_type" id="vehicle_type">
												<option value="select">Select</option>												
												<option value="car">Car</option>
												<option value="truck">Truck</option>
												<option value="bus">Bus</option>
												<option value="person">Person</option>
												<option value="motorbike">Motorbike</option>
											</select>
										</td>
									</tr>';                
								}
									echo'<tr>
										<td>Category</td>
										<td>&nbsp;:&nbsp;</td>
										<td>											
											<select id="category">
												<option value="select">Select</option>											
											</select>													
										</td>
									</tr>';
							echo'<!--<tr>
									<td>Register</td>
									<td>&nbsp;:&nbsp;</td>
									<td>
										<select id="register_option" onchange="javascript:show_device_imei_no(this.value,\''.$report_type.'\');">
											<option value="select">Select</option>
											<option value="yes">YES</option>
											<option value="no">NO</option>
										</select>
									</td>
								</tr>-->';
								$temp_type="add,".$report_type;
							echo'<tr>                    									
									<td align="center"  colspan="3">
										<div style="height:10px"></div>
										<input type="button" Onclick="javascript:action_manage_vehicle(manage1, \''.$temp_type.'\')" value="Add" id="enter_button">										
										&nbsp;<input type="reset" value="Clear">
										<input type="button" Onclick="javascript:show_device_imei_no()" value="Add With Register" id="enter_button_1">
									</td>
								</tr>
							</table>
						</fieldset>
					</td>
				</tr>
			</table>
	<div id="available_message" align="center"></div>
	<div id="device_vehicle_assigment_blackout"> </div>
    <div id="device_vehicle_assigment_divpopup">';	
		echo' <table border="0" class="main_page" width="100%">
				<tr>
					<td class="manage_interfarce" align="right" colspan="7">
						<a href="#" onclick="javascript:close_vehicle_display_option(\'device_vehicle_assigment\')" class="hs3">
							<img src="images/close.png" type="image" style="border-style:none;">
						</a>&nbsp;&nbsp;
					</td> 													
				</tr>
				<tr>
					<td>
						<div id="display_device" style="display:none;"></div>
					</td>
				</tr>
		</table>
	</div>';
		echo'<center>
				<a href="javascript:show_option(\'manage\',\'vehicle\');" class="back_css">
					&nbsp;<b>Back</b>
				</a>
			</center>
	 </form>';
	 include_once('manage_loading_message.php');
	  ?>
