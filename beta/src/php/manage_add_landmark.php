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
echo'
<div style="height:5px;"></div>
<table border="0" class="manage_interface">
   <tr><td>Name</td><td>Coord (latitude,longitude)</td><td>Zoom Level1</td></tr>
   <tr>
		<td><input type="text" id="landmark_name" onkeyup="manage_availability(this.value, \'landmark\')" onmouseup="manage_availability(this.value, \'landmark\')" onchange="manage_availability(this.value,\'landmark\')"></td>
		<td><input type="text" id="landmark_point" size="37">&nbsp;<a href="javascript:showCoordinateInterface(\'landmark\');">Get by map</a></td>
    <td> 
      &nbsp;<select name="select_zoom_level" id="select_zoom_level">
        <option value="select">Select</option><option value="5">National zoom level-1</option><option value="6">National zoom level-2</option>
        <option value="7">State level-1</option><option value="8">State level-2</option><option value="9">City level-1</option>
        <option value="10">City level-2</option><option value="11">Town level</option><option value="13">Tehsil level</option>
        <option value="15">Street level-1</option><option value="16">Street level-2</option>
      </select> 
    </td>	
  </tr>
	<tr>
		<td colspan="3" align="center"><input type="button" value="Save" id="enter_button" onclick="javascript:return action_manage_landmark(\'add\')"/>&nbsp;<input type="reset"" value="Clear" /></td>
	</tr>
</table>
<div id="available_message" align="center"></div> 
<div id="blackout"> </div>
<div id="divpopup">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">							
			<tr>
				<td class="manage_interfarce" align="right"><a href="#" onclick="javascript:return close_landmark_div()" class="hs3">Close</a></td> 													
			</tr> 
			<tr>
				<td colspan="5" valign="top" align="justify"><div id="landmark_map" style="width:707px; height:397px; position: relative; background-color: rgb(229, 227, 223);" class="ukseries_div_map"></div></td>
			</tr>							
  </table>
</div>	
';
include_once('manage_loading_message.php');
?>