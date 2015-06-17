<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 
	include_once('coreDb.php');	
	echo "edit##"; 
	$account_id_local=$_POST['common_id'];
	echo "<input type='hidden' id='account_id_local' value=".$account_id_local.">";
	
?>
	<input type="hidden" name="prev_landmark_point" id="prev_landmark_point">  
	<table border="0" class="manage_interface" cellspacing="2" cellpadding="2">
		<tr>
			<td align="right">Landmark Name</td><td>:</td>
			<td>
				<?php
					//$data=getDetailAllLandmark($account_id_local,$DbConnection); // for debug purpose
					//print_r($data);
				?>
				<select name="landmark_id" id="landmark_id" onchange="javascript:show_landmark_coord(manage1);">
				<option value="select">Select</option>
				<?php
				
				$data=getDetailAllLandmark($account_id_local,$DbConnection);            							
				foreach($data as $dt)
				{					
					$landmark_id=$dt['landmark_id'];
					$landmark_name=$dt['landmark_name'];
					$zoom_level=$dt['zoom_level'];
					$distance_variable=$dt['distance_variable'];
					
					echo '<option value='.$landmark_id.'>'.$landmark_name.'</option>';
				}
				?>
				</select>
				<?php echo "<input type='hidden' id='set_zoom_level' value=".$zoom_level.">";?>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<div id="coord_area" style="display:none">
					<table class="manage_interface">         					         
						<tr>                          
							<td>Landmark Name</td><td>:</td>
							<td><!--<input type="text" name="landmark_name" id="landmark_name" onkeyup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onmouseup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onchange="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')">-->
							<input type="text" name="landmark_name" id="landmark_name" onkeyup="manage_availability(this.value, 'landmark')" onchange="manage_availability(this.value, 'landmark')"></td>                                
						</tr> 
						<tr>                          
							<td>Landmark Coord</td><td>:</td>
							<td>
								<input type="text" name="landmark_point" id="landmark_point" size="37" onclick="javascript:showCoordinateInterface('landmark');" readonly>                             
								&nbsp;<a href="javascript:showCoordinateInterface('landmark');">Edit</a>
							</td>                                
						</tr>                     
						<tr>                          
							<td>Zoom Level</td><td>:</td>
							<td> 
								<select name="select_zoom_level" id="select_zoom_level">									
									<option value="5">National zoom level-1</option><option value="6">National zoom level-2</option>
									<option value="7">State level-1</option><option value="8">State level-2</option><option value="9">City level-1</option>
									<option value="10">City level-2</option><option value="11">Town level</option><option value="13">Tehsil level</option>
									<option value="15">Street level-1</option><option value="16">Street level-2</option>
								</select>
							</td>                                
						</tr>
						<!--<tr>                          
							<td>Distance Variable</td><td>:</td>
							<td><!--<input type="text" name="landmark_name" id="landmark_name" onkeyup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onmouseup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onchange="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')">-->
							<!--<input type="text" name="distance_variable" id="distance_variable"></td>                                
						</tr>-->
					</table>
				</div>
			</td>
		</tr>        
		<tr>
			<td colspan="3" align="center">
				<input type="button" value="Update" id="enter_button" onclick="javascript:return action_manage_landmark('edit')"/>&nbsp;
				<input type="button" value="Delete" onclick="javascript:action_manage_landmark('delete')"/>
			</td>
		</tr>
	</table>
	<div id="available_message" align="center"></div> 
	<div id="blackout"> </div>
	<div id="divpopup">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#c3c3c3">							
			<tr>
				<td class="manage_interfarce" align="right"><a href="#" onclick="javascript:return close_landmark_div()" class="hs3">Close</a></td> 													
			</tr> 
			<tr>
				<td colspan="5" valign="top" align="justify"><div id="landmark_map" style="width:707px; height:397px; position: relative; background-color: rgb(229, 227, 223);" class="ukseries_div_map"></div></td>
			</tr>							
		</table>
	</div>
<?php
	include_once('manage_loading_message.php');
?>