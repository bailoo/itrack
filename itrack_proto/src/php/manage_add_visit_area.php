<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
  
	echo "add##"; 
	include_once('tree_hierarchy_information.php');
	include_once('manage_checkbox_account.php'); 
	echo'
				<table border="0" class="manage_interface">
					<tr>
						<td>Name</td><td>:</td>
						<td><input type="text" name="add_geo_name" id="add_geo_name" onkeyup="manage_availability(this.value, \'visit_area\')" onmouseup="manage_availability(this.value,\'visit_area\')" onchange="manage_availability(this.value, \'visit_area\')"></td>
					</tr>
					<tr>
						<td>Visit Area Coord</td><td>:</td>
						<td><textarea readonly="readonly" style="width:350px;height:60px" name="geo_coord" id="geo_coord" readonly onclick="javascript:showCoordinateInterface(\'geofencing\');"></textarea>
                <a href="#" Onclick="javascript:add_geo_manually();">Enter Manually</a></td>&nbsp;            
            </td>	
					</tr>
					<tr>
						<td colspan="3" align="center"><input type="button" id="enter_button" value="Save" onclick="javascript:action_manage_visit_area(\'add\')"/>&nbsp;<input type="reset"" value="Clear" /></td>
					</tr>
				</table>';								
				
	include_once('availability_message_div.php');
?>
  