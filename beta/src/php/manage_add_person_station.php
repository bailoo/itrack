<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
  
	echo "add##"; 
	include_once('tree_hierarchy_information.php');
	include_once('manage_checkbox_account.php'); 
	
        
echo '<div style="height:5px;" align="center">

<!--<input type="radio" name="mode" id="mode" value="1" Onclick="javascript:show_station_mode(this.value);">&nbsp;Upload File &nbsp;&nbsp;-->
<input type="radio" name="mode" id="mode" value="2" Onclick="javascript:show_station_mode(this.value);">&nbsp;Enter Manually</div><br><br>';


echo '        
<div style="height:5px;display:none;" id="manual">

<table border="0" class="manage_interface">';


   echo'

    <td>Station No</td>
                <td>:</td>
    <td><input type="text" name="add_station_no" id="add_station_no" onkeyup="manage_availability(this.value, \'station_no_person\')" onmouseup="manage_availability(this.value, \'station_no_person\')" onchange="manage_availability(this.value,\'station_no_person\')"></td>
         </tr>

   <tr>
    <td>Name</td>
		<td>:</td>
    <td><input type="text" name="add_station_name" id="add_station_name"></td>
	 </tr>
  <tr>	
    <td>Coord (latitude,longitude)</td>
    <td>:</td>
    <td><input type="text" name="station_coord" id="landmark_point" size="37">&nbsp;<a href="javascript:showCoordinateInterface(\'landmark\');">Get by map</a></td>  
  </tr>
   
	<tr>
		<td colspan="3" align="center"><input type="button" value="Save" id="enter_button" onclick="javascript:return action_manage_person_station(\'add\')"/>&nbsp;<input type="reset"" value="Clear" /></td>
	</tr>
</table>
<div id="available_message" align="center"></div> 
<div id="blackout"> </div>
<div id="divpopup">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">							
        <tr>
                <td class="manage_interfarce" align="right"><a href="#" onclick="javascript:return close_landmark_div_station()" class="hs3">Close</a></td> 													
        </tr> 
        <tr>
                <td colspan="5" valign="top" align="justify">        
                <input type="hidden" id="prev_landmark_point"/>
                <input type="hidden" id="zoom_level" value="8"/>
<div id="landmark_map" style="width:707px; height:397px; position: relative; background-color: rgb(229, 227, 223);" class="ukseries_div_map"></div></td>
        </tr>							
  </table>
</div>

</div>
';	 
     							
				
	include_once('availability_message_div.php');
?>
  
