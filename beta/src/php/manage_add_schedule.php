<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];  
	echo "add##"; 
	//include_once('tree_hierarchy_information.php');	
    echo'<div id="portal_vehicle_information">';	
		include_once('manage_checkbox_account_new.php');  	
	echo'</div>'; 
	echo"<input type='hidden' id='prev_geo_point'>";
	echo'
	<div style="height:5px;"></div>
		<table border="0" class="manage_interface">
			<tr>
				<td>
					Name
				</td>
				<td>
					Coord (latitude,longitude)
				</td>
			</tr>
			<tr>
				<td>
					<input type="text" id="location_name" onkeyup="manage_availability(this.value, \'location\')" onmouseup="manage_availability(this.value, \'location\')" onchange="manage_availability(this.value,\'location\')">
				</td>
				<td>
					<input type="text" id="geo_point" size="37">&nbsp;<a href="javascript:showCoordinateInterface(\'location\');">Get by map</a>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
					<input type="button" value="Save" id="enter_button" onclick="javascript:return action_manage_schedule(\'add\')"/>
					&nbsp;<input type="reset"" value="Clear" />
				</td>
			</tr>
		</table>
	<div id="available_message" align="center"></div> 
	<div id="blackout"> </div>
	<div id="divpopup">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">							
			<tr>
				<td class="manage_interfarce" align="right">
					<a href="#" onclick="javascript:return close_location_div()" class="hs3">
						Close
					</a>
				</td> 													
			</tr> 
			<tr>
				<td colspan="5" valign="top" align="justify">
					<div id="location_map" style="width:707px; height:397px; position: relative; background-color: rgb(229, 227, 223);" class="ukseries_div_map">
					</div>
				</td>
			</tr>							
		</table>
	</div>	
';
?>
