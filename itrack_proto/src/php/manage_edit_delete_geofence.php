<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');
	$DEBUG=0;
	$parameter_type="geofence";
	echo "edit##";
	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	//echo "coomon_id=".$common_id1;	
	//common_id
?>
<br> 
<table border="0" class="manage_interface" cellspacing="2" cellpadding="2">
    <tr>
        <td>&nbsp;Geo Name&nbsp;:&nbsp;
          <select name="geo_id" id="geo_id" onchange="show_geo_coord(manage1);">
          <option value="select">Select</option>';
          <?php				
                $data=getGeoIDGeoNameGeofence($common_id1,$DbConnection);				
				foreach($data as $dt)
				{					
					$geo_id=$dt['geo_id']; 
					$geo_name=$dt['geo_name'];
					echo'<option value='.$geo_id.'>'.$geo_name.'</option>';
				}
  				?>
  		    </select>
      </td>
   </tr>
   <tr>                          
      <td>
         <div id="coord_area" style="display:none">
           <table class="manage_interface">         					         
             <tr>                          
                <td>Geo Name</td>
                <td>:</td>
                <td><input type="text" name="geo_name" id="geo_name" onkeyup="manage_availability(this.value, 'geofence')" onmouseup="manage_availability(this.value, 'geofence')" onchange="manage_availability(this.value, 'geofence')"></td>                                
             </tr> 
             <tr>                          
                <td>Geo Coord</td>
                <td>:</td>
                <td><textarea readonly="readonly" style="width:350px;height:60px" name="geo_coord" id="geo_coord" onclick="javascript:showCoordinateInterface('geofencing');"></textarea>                             
                    <!--&nbsp;<a href="javascript:showCoordinateInterface(\'edit_geofencing\');">Edit</a>--></td>                                
             </tr>
            </table>
          </div>
    </td>                                
   </tr>                               
  <tr>
  	<td colspan="3" align="center"><input type="button" id="enter_button" value="Update" onclick="javascript:action_manage_geofence('edit')"/>&nbsp;<input type="button" value="Delete" onclick="javascript:action_manage_geofence('delete')"/></td>
  </tr>
  </table>
  
  <?php 
    include_once('availability_message_div.php');
  ?>