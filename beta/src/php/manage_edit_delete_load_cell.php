<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');  
	echo "edit##"; 
	$account_id_local=$_POST['common_id'];
	echo "<input type='hidden' id='account_id_local' value=".$account_id_local.">";
?>
	<input type="hidden" name="prev_landmark_point" id="prev_landmark_point">  
	<table border="0" class="manage_interface" cellspacing="2" cellpadding="2">
		<tr>
			<td align="right">Imei</td><td>:</td>
			<td>
				<?php
				////////for testing
				//$query="select * from station where account_id='$account_id_local' and status='1'";
				//echo "query=".$query;
				?>
				<select name="load_cell_id" id="station_id" onchange="javascript:show_load_cell(manage1);">
				<option value="select">Select</option>
				<?php
				
				$data=getDelailAllLoadCell($account_id_local,$DbConnection);            							
				foreach($data as $dt)
				{
					$load_cell_id=$dt['load_cell_id'];
					$date=$dt['date'];			
					$imei=$dt['imei'];
					$load=$dt['load'];
					//$zoom_level=$row->zoom_level;
					echo '<option value='.$load_cell_id.'>'.$imei.'</option>';
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
							<td>DateTime</td><td>:</td>
							<td><!--<input type="text" name="landmark_name" id="landmark_name" onkeyup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onmouseup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onchange="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')">-->
							<input type="text" name="date1" id="date1"></td>                                
						</tr>
            
						<tr>                          
							<td>IMEI</td><td>:</td>
							<td><!--<input type="text" name="landmark_name" id="landmark_name" onkeyup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onmouseup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onchange="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')">-->
							<input type="text" name="imei" id="imei" onkeyup="manage_availability(this.value, 'load_cell')" onchange="manage_availability(this.value, 'load_cell')"></td>                                
						</tr>
            
						<tr>                          
							<td>Load</td><td>:</td>
							<td><!--<input type="text" name="landmark_name" id="landmark_name" onkeyup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onmouseup="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')" onchange="manage_availability(this.value, 'edit_landmark_name', 'existing_in_user')">-->
							<input type="text" name="load" id="load"></td>                                
						</tr>                           
					                					
					</table>
				</div>
			</td>
		</tr>        
		<tr>
			<td colspan="3" align="center">
				<input type="button" value="Update" id="enter_button" onclick="javascript:return action_manage_load_cell('edit')"/>&nbsp;
				<input type="button" value="Delete" onclick="javascript:action_manage_load_cell('delete')"/>
			</td>
		</tr>
	</table>
	<div id="available_message" align="center"></div> 

	</div>