<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
  
	echo "add##"; 
	include_once('tree_hierarchy_information.php');
	include_once('manage_checkbox_account.php'); 
	/*echo'
				<table border="0" class="manage_interface">
					<tr>
						<td>Name</td><td>:</td>
						<td><input type="text" name="add_geo_name" id="add_geo_name" onkeyup="manage_availability(this.value, \'geofence\')" onmouseup="manage_availability(this.value,\'station\')" onchange="manage_availability(this.value, \'station\')"></td>
					</tr>
					<tr>
						<td>Coord</td><td>:</td>
						<td><textarea readonly="readonly" style="width:350px;height:60px" name="geo_coord" id="geo_coord" readonly onclick="javascript:showCoordinateInterface(\'geofencing\');"></textarea>
                <a href="#" Onclick="javascript:add_geo_manually();">Enter Manually</a></td>&nbsp;            
            </td>	
					</tr>
					<tr>
						<td colspan="3" align="center"><input type="button" id="enter_button" value="Save" onclick="javascript:action_manage_station(\'add\')"/>&nbsp;<input type="reset"" value="Clear" /></td>
					</tr>
				</table>';	*/
        
echo '<div style="height:5px;" align="center">

<input type="radio" name="mode" id="mode" value="1" Onclick="javascript:show_station_mode(this.value);">&nbsp;Upload File &nbsp;&nbsp;
<input type="radio" name="mode" id="mode" value="2" Onclick="javascript:show_station_mode(this.value);">&nbsp;Enter Manually</div><br><br>';
/*
echo '<div style="height:5px;display:none;" id="automatic">

    <form id="file_upload_form" name="file_upload_form" target="_blank" method="post" enctype="multipart/form-data" action="src/php/action_manage_station_upload.php">
      
    <table border="0" class="manage_interface">
    	<tr>
        <td>Upload Station Data </td><td>:</td>
        <td><input name="file" id="file" size="27" type="file" />(.xls :97-2003)</td>
      </tr>
      <tr>
        <td colspan="3" align="center">
          <input type="hidden" name="action_type"/>
          <input type="hidden" name="local_account_ids" value="'.$account_id_local.'"/>
          
          <input type="button" value="Save" id="enter_button" onclick="javascript:return action_manage_station_upload(\'add\')"/>&nbsp;<input type="reset"" value="Clear" />         
          <iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
        </td>
      </tr>
    </table>    
  </form>    

</div>'; */ 

echo '        
<div style="height:5px;display:none;" id="manual">

<table border="0" class="manage_interface">';

	/*	echo '<tr>
			<td>Select Route</td>
      <td>:</td>
			<td>
				<select name="route_id" id="route_id">
				<option value="select">Select</option>
        ';
				$query="select * from station_route where create_id='$account_id_local' and status='1'";
			echo $query;
	
        $result=mysql_query($query,$DbConnection);            							
				while($row=mysql_fetch_object($result))
				{
					$route_id=$row->route_id;
					$route_name=$row->route_name;				
					//$zoom_level=$row->zoom_level;
					echo '<option value='.$route_id.'>'.$route_name.'</option>';
				}
				echo'</select>			
			</td>
		</tr>'; */

   echo'

    <td>Station No</td>
                <td>:</td>
    <td><input type="text" name="add_station_no" id="add_station_no" onkeyup="manage_availability(this.value, \'station_no\')" onmouseup="manage_availability(this.value, \'station_no\')" onchange="manage_availability(this.value,\'station_no\')"></td>
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
    <td>File Type</td>
    <td>:</td>
    <td><select name="file_type"></option><option value="0" selected>Customer</option><option value="1">Plant</option></select></td>  
  </tr>  
	<tr>
		<td colspan="3" align="center"><input type="button" value="Save" id="enter_button" onclick="javascript:return action_manage_station(\'add\')"/>&nbsp;<input type="reset"" value="Clear" /></td>
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
  
