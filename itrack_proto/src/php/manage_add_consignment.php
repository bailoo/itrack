<?php
	include_once('Hierarchy.php');		
	include_once('util_session_variable.php');	
	include_once('util_php_mysql_connectivity.php'); 
	include_once('user_type_setting.php');
	$root=$_SESSION['root'];	
	$DEBUG=0;
	echo'<div style="height:10px"> </div>';
	include_once('manage_consignment.php');
	echo"<form name='manage1'>";
	echo'<div id="portal_vehicle_information">';
	  include_once('moto_tree_hierarchy_information.php');	
	  include_once('manage_moto_radio_account.php');
	$start_date=date("Y/m/d 00:00:00");	
	$end_date=date("Y/m/d H:i:s");	
	echo "</div>";
	echo'<div style="height:10px"> </div>
				<table border="0" class="manage_interface" align="center">
					<tr>
						<td valign="top">
							Consignment Name
						</td>
						<td>:</td>
						<td colspan=2>';						
						echo'<input type="text" id="consignment_name" >';						
					echo'</td>
					</tr>
					<tr>
						<td valign="top">
							Consignment Code
						</td>
						<td>:</td>
						<td colspan=2>';						
						echo'<input type="text" id="consignment_code" onkeyup="manage_availability(this.value, \'consignment\')" onmouseup="manage_availability(this.value,\'consignment\')" onchange="manage_availability(this.value, \'consignment\')">';
					echo'</td>
					</tr>					
					<tr>
						<td valign="top">
							Pickup Type
						</td>
						<td>:</td>
						<td colspan=2>
							<select id="pickup_type" onchange="javascript:manage_show_plant_customer(this.value,\'pickup_type\',\'pickup_point_id\')">
								<option value="select">
									Select
								</option>
								<option value="0">
									Customer
								</option>
								<option value="1">
									Plant
								</option>	
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top">
							Pickup Point Id
						</td>
						<td>:</td>
						<td colspan=2>
							<select id="pickup_point_id">
								<option value="select">Select</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top">
							Pickup Date Time
						</td>
						<td>:</td>
						<td colspan=2>
							<input type="text" id="date1" name="start_date" size="10" maxlength="19">
							<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
								<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
							</a>
						</td>
					</tr>
					<tr>
						<td valign="top">
							Delivery Type
						</td>
						<td>:</td>
						<td colspan=2>
							<select id="delivery_type" onchange="javascript:manage_show_plant_customer(this.value,\'delivery_type\',\'delivery_point_id\')">
								<option value="select">Select</option>
								<option value="0">
									Customer
								</option>
								<option value="1">
									Plant
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top">
							Delivery Point
						</td>
						<td>:</td>
						<td colspan=2>
							<select id="delivery_point_id">
								<option value="select">Select</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top">
							Delivery Date Time
						</td>
						<td>:</td>
						<td colspan=2>
							<input type="text" id="date2" name="end_date" size="10" maxlength="19">					
							<a href=javascript:NewCal("date2","yyyymmdd",true,24)>
								<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
							</a>
						</td>
					</tr>
						<tr>
						<td valign="top">
							Distance
						</td>
						<td>:</td>
						<td colspan=2>
							<input type="text" id="distance" size="10">							
						</td>
					</tr>
				</table>				
				<div id="moto_manage_vehicle_blackout"> </div>
				<div id="moto_manage_vehicle_divpopup">';	
					echo' <table border="0" class="main_page" width="100%">
							<tr>
								<td class="manage_interfarce" align="right" colspan="7">
									<a href="#" onclick="javascript:close_vehicle_display_option(\'moto_manage_vehicle\')" class="hs3">
										<img src="images/close.png" type="image" style="border-style:none;">
									</a>&nbsp;&nbsp;
								</td> 													
							</tr>
							<tr>
								<td>
									<div id="moto_display_vehicle" style="display:none;"></div>
								</td>
							</tr>
					</table>
				</div>
				<center>
					<input type="button" Onclick="javascript:moto_show_vehicle(0)" value="Add With Register" id="enter_button">
				</center>
				<div id="available_message" align="center"></div>
			</form>';			
	include_once('availability_message_div.php'); 			
?>
