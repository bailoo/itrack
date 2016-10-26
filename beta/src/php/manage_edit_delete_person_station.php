<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');  
		include_once('coreDb.php');  
	echo "edit_div_station1##"; 
	//$account_id_local1=$_POST['account_id_local'];
	//echo "<input type='hidden' id='account_id_local' value=".$account_id_local1.">";	
	//echo "account_id_local=".$account_id_local1;
?>
	<input type="hidden" name="prev_landmark_point" id="prev_landmark_point">  
	<table border="0" class="manage_interface" cellspacing="2" cellpadding="2">
		<tr>
			<td align="right">Station Name</td><td>:</td>
			<td>
				<?php
				////////for testing
				//$query="select * from station where account_id='$account_id_local' and status='1'";
				//echo "query=".$query;
				//$query="select * from station where user_account_id='$account_id_local' and status='1'";
				//echo $query;
				$data=getDetailAllStationPerson($account_id_local,$station_type1,$DbConnection); /// for debug
				//print_r($data);
                                //exit();
				
				?>
				<select name="station_id" id="station_id" onchange="javascript:show_person_station_coord(manage1);">
				<option value="select">Select</option>
				<?php							
				foreach($data as $dt)
				{
					$station_id=$dt['station_id'];
					$customer_no=$dt['customer_no'];
					$station_name=$dt['station_name'];
					$distance_variable=$dt['distance_variable'];
					$type=$dt['type'];				
					
					echo '<option value='.$station_id.'>'.$customer_no.'</option>';
				}
				?>
				</select>
				<?php echo "
        <input type='hidden' id='zoom_level' value='8' />
        <input type='hidden' id='set_zoom_level' value=".$zoom_level.">";?>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<div id="coord_area" style="display:none">
					<table class="manage_interface">                   					         
						<tr>                          
							<td><font color=red>Station Type</font></td><td>:</td>
							<td><!--<input type="text" name="landmark_name" id="landmark_name" onkeyup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onmouseup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onchange="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')">-->
							<input type="text" name="station_type" id="station_type" readonly></td>                                
						</tr> 

						<tr>                          
							<td>Station Name</td><td>:</td>
							<td><!--<input type="text" name="landmark_name" id="landmark_name" onkeyup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onmouseup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onchange="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')">-->
							<!--<input type="text" name="station_name" id="station_name" onkeyup="manage_availability(this.value, 'station')" onchange="manage_availability(this.value, 'landmark')">-->
							<input type="text" name="station_name" id="station_name" onkeyup="manage_availability(this.value, 'station_person')">
							</td>                                
						</tr> 
						
						<tr>                          
							<td>Customer Number</td><td>:</td>
							<td><!--<input type="text" name="landmark_name" id="landmark_name" onkeyup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onmouseup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onchange="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')">-->
							<input type="text" name="customer_no" id="customer_no"></td>                                
						</tr> 						
						<tr>                          
							<td>Station Coord</td><td>:</td>
							<td>
								<!--<input type="text" name="landmark_point" id="landmark_point" size="37" onclick="javascript:showCoordinateInterface('landmark');" readonly>-->                           
								<input type="text" name="landmark_point" id="landmark_point" size="37">
								&nbsp;<a href="javascript:showCoordinateInterface('landmark');">Edit</a>
							</td>                                
						</tr>
						<tr>                          
							<td>Distance variable</td><td>:</td>
							<td><!--<input type="text" name="landmark_name" id="landmark_name" onkeyup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onmouseup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onchange="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')">-->
							<input type="text" name="distance_variable" id="distance_variable"></td>                                
						</tr>                                 					
					</table>
				</div>
			</td>
		</tr>        
		<tr>
			<td colspan="3" align="center">
				<input type="button" value="Update" id="enter_button" onclick="javascript:return action_manage_person_station('edit')"/>&nbsp;
				<!--<input type="button" value="Delete" onclick="javascript:action_manage_station('delete')"/>-->
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