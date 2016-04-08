<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');  
	echo "edit##"; 
	$account_id_local=$_POST['common_id'];
	echo "<input type='hidden' id='account_id_local' value=".$account_id_local.">";
	echo"<input type='hidden' id='prev_geo_point'>";
?>
	<input type="hidden" name="prev_location_point" id="prev_location_point">  
	<table border="0" class="manage_interface" cellspacing="2" cellpadding="2">
		<tr>
			<td align="right">Location Name</td><td>:</td>
			<td>				
				<select name="location_id" id="location_id" onchange="javascript:show_location_coord_upl(manage1);">
							<option value="select">Select</option>
				<?php
					$data=getDetailAllScheduleUpl($account_id_local,$DbConnection);            							
					foreach($data as $dt)
					{
						$location_id=$dt['location_id'];
						$location_name=$dt['location_name'];			
						echo '<option value='.$location_id.'>'.$location_name.'</option>';
					}
				?>
				</select>			
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<div id="coord_area" style="display:none">
					<table class="manage_interface">         					         
						<tr>                          
							<td>Location Name</td><td>:</td>
							<td><!--<input type="text" name="landmark_name" id="landmark_name" onkeyup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onmouseup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onchange="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')">-->
							<input type="text" name="location_name" id="location_name" onkeyup="manage_availability(this.value, 'location')" onchange="manage_availability(this.value, 'location')"></td>                                
						</tr> 
						<tr>                          
							<td>Location Coord</td><td>:</td>
							<td>
								<input type="text" name="geo_point" id="geo_point" size="37" onclick="javascript:showCoordinateInterface('location');" readonly>                             
								&nbsp;<a href="javascript:showCoordinateInterface('location');">Edit</a>
							</td>                                
						</tr>
					</table>
				</div>
			</td>
		</tr>        
		<tr>
			<td colspan="3" align="center">
				<input type="button" value="Update" id="enter_button" onclick="javascript:return action_manage_schedule_upl('edit')"/>&nbsp;
				<input type="button" value="Delete" onclick="javascript:action_manage_schedule_upl('delete')"/>
			</td>
		</tr>
	</table>
	<div id="available_message" align="center"></div> 
	<div id="blackout"> </div>
	<div id="divpopup">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#c3c3c3">							
			<tr>
				<td class="manage_interfarce" align="right"><a href="#" onclick="javascript:return close_location_div()" class="hs3">Close</a></td> 													
			</tr> 
			<tr>
				<td colspan="5" valign="top" align="justify"><div id="location_map" style="width:707px; height:397px; position: relative; background-color: rgb(229, 227, 223);" class="ukseries_div_map"></div></td>
			</tr>							
		</table>
	</div>