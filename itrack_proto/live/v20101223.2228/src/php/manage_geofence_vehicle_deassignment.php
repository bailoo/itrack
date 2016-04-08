<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $DEBUG=0; 
  
  // GET ASSIGNED DEVICE VEHICLE PAIR
  // $query = "SELECT vehicle.geo_id,vehicle.VehicleName,geofence.geo_name FROM vehicle,geofence WHERE vehicle.geo_id=geofence.geo_id AND geofence.create_id='$account_id'";
  $query = "SELECT VehicleID,VehicleName,vehicle.geo_id,geofence.geo_name FROM vehicle,geofence WHERE vehicle.geo_id=geofence.geo_id AND VehicleID IN (SELECT vehicle_id FROM vehicle_grouping WHERE vehicle_group_id IN (SELECT vehicle_group_id FROM account_detail WHERE account_id='$account_id'))";
  if($DEBUG==1) print_query($query);
      
  $result = @mysql_query($query, $DbConnection);
  $d_count=0;
  while($row = mysql_fetch_object($result))
  {
    //$vid[$d_count]= $row->VehicleID;
    $vehicle_id[$d_count] = $row->VehicleID;
    $geo_id[$d_count] = $row->geo_id;
    $geo_name[$d_count] = $row->geo_name;
    $vname[$d_count] = $row->VehicleName;
    $d_count++;
  } 
        
echo'<center>

<form method = "post"  name="thisform">  	
  <fieldset class="manage_fieldset">
		<legend><strong>Geofence Vehicle De Assignment<strong></legend>		
        
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
    <td>
  <fieldset class="manage_fieldset">
		<legend>Geofence Vehicle Pair</legend>		
		
    <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
                
        <tr valign="top">
            <td></td>
            <td>Select</td>
             <td>&nbsp;:&nbsp;</td>
            <td>'; 
              if($d_count==0)
              {
                  echo '<font color=red>No Geofence Found</font>';
              }
              else
              {             
                echo'<select name="veh_geo_id" id="veh_geo_id" size="6" multiple="multiple">';              
                	        
                for($i=0; $i<$d_count; $i++)
                {
                  echo'<option value="'.$vehicle_id[$i]."#".$geo_id[$i].'" onclick="document.getElementById(\'enter_button\').disabled=false;">'.$vname[$i].' - '.$geo_name[$i].'</option>';
                }                                                            
              echo'</select>';
              }          
            echo'</td>
        </tr>
    </table>
    </fieldset>  
    </td>
    </tr>
      
	  <tr>                    									
			<td align="center" colspan="3"><br><input type="button" id="enter_button" disabled="true" Onclick="javascript:return action_manage_geofence_vehicle_deassignment(thisform)" value="De Assign">&nbsp;<input type="reset" value="Cancel"></td>
		</tr>
		</table>
    
    </fieldset>  
   </form>
  <div id="available_message" align="center"></div>
  </center>
  ';
   
?>  