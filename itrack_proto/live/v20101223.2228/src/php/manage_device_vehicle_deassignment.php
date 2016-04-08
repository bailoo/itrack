<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $DEBUG=0; 
  
  // GET ASSIGNED DEVICE VEHICLE PAIR
  $query = "SELECT VehicleID, VehicleSerial, VehicleName FROM vehicle WHERE ".
  "VehicleSerial IN(SELECT device_imei_no FROM vehicletable WHERE status='1') AND ".
  "create_id='$account_id'";

  if($DEBUG==1)
    print_query($query);
      
  $result = @mysql_query($query, $DbConnection);
  $d=0;
  while($row = mysql_fetch_object($result))
  {
    $vid[$d]= $row->VehicleID;
    $device[$d] = $row->VehicleSerial;
    $vname[$d] = $row->VehicleName;
    $d++;
  } 
        
echo'<center>

<form method = "post"  name="thisform">
  	
  <fieldset class="manage_fieldset">
		<legend><strong>Device Vehicle De Assignment<strong></legend>		
        
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
    <td>
  <fieldset class="manage_fieldset">
		<legend>Device Vehicle Pair</legend>		
		
    <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
                
        <tr valign="top">
            <td></td>
            <td>Select</td>
             <td>&nbsp;:&nbsp;</td>
            <td>'; 
              if($d==0)
              {
                  echo '<font color=red>No device Found</font>';
              }
              else
              {             
                echo'<select name="device" id="device" size="6" multiple="multiple">';              
                	        
                for($i=0; $i<$d; $i++)
                {
                  echo'<option value="'.$device[$i].'" onclick="document.getElementById(\'enter_button\').disabled=false;">'.$device[$i].'-'.$vname[$i].'</option>';
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
			<td align="center" colspan="3"><br><input type="button" id="enter_button" disabled="true" Onclick="javascript:return action_manage_device_vehicle_deassignment(thisform)" value="De Assign">&nbsp;<input type="reset" value="Cancel"></td>
		</tr>
		</table>
    
    </fieldset>  
   </form>
  <div id="available_message" align="center"></div>
  </center>
  ';
   
?>  