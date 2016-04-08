<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $DEBUG=0;
  
  $query = "SELECT superuser, user FROM account WHERE account_id='$account_id'";   	  
  if($DEBUG==1)
    print_query($query);	
  $result = @mysql_query($query, $DbConnection);      	
  $row = mysql_fetch_object($result);
 
  $user = $row->user;
  $superuser = $row->superuser;
  
  // GET USER DEVICES + NOT ASSIGNED 
  $query = "SELECT account_id from account WHERE superuser='$superuser' and user='$user' and grp='admin'";
  if($DEBUG==1)
    print_query($query);
    
  $result = @mysql_query($query, $DbConnection);
  $row = mysql_fetch_object($result);     	
  $user_account_id = $row->account_id;
  
  $query = "SELECT device_imei_no FROM device_lookup WHERE ".
  "device_imei_no IN(SELECT device_imei_no FROM device_sales_info ".
  "WHERE user_account_id='$user_account_id') AND ".
  "device_imei_no NOT IN(SELECT device_imei_no FROM vehicletable WHERE status='1')";
  if($DEBUG==1)
    print_query($query);
      
  $result = @mysql_query($query, $DbConnection);
  $d=0;
  $device=null;
  while($row = mysql_fetch_object($result))
  {
    $device[$d] = $row->device_imei_no;
    if($DEBUG) print_message($d,$device[$d]);
    $d++;
  }
  //print_query($query);  
  
  // GET ACCOUNT VEHICLES + NOT ASSIGNED
  $query = "SELECT VehicleID,VehicleName FROM vehicle WHERE ".
  "VehicleID IN(SELECT vehicle_id FROM vehicle_grouping WHERE ".
  "vehicle_group_id =(SELECT vehicle_group_id FROM ".
  "account_detail WHERE account_id='$account_id') AND ".
  "vehicle_id IN(SELECT VehicleID from vehicle WHERE VehicleSerial IS NULL))";
  
  if($DEBUG==1)
    print_query($query);  
  
  $result = @mysql_query($query, $DbConnection);
      	
  $v=0;
  while($row = mysql_fetch_object($result))
  {
    $vid[$v] = $row->VehicleID;
    $vname[$v] = $row->VehicleName;
    $v++;
  }
      
echo'<center>

<form method = "post"  name="thisform">
  	
  <fieldset class="manage_fieldset">
		<legend><strong>Device Vehicle Assignment</strong></legend>		
        
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
    <td>
  <fieldset class="manage_fieldset">
		<legend>Select Device</legend>		
		
    <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
        
        <tr>
            <td><input type="radio" name="device_option" value="1" onclick="javascript:this.form.imei_no.disabled=false;this.form.imei_no2.disabled=true"></td>
            <td>Search</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="imei_no" id="imei_no" onFocus="javascript:thisform.device_option[0].checked=true;" onkeyup="manage_availability(this.value, \'imei_no\', \'existing_in_user\')" onmouseup="manage_availability(this.value, \'imei_no\', \'existing_in_user\')" onchange="manage_availability(this.value, \'imei_no\', \'existing_in_user\')">
            </td>
        </tr> 
        
        <tr valign="top">
            <td><input type="radio" name="device_option" value="2" onclick="javascript:this.form.imei_no2.disabled=false;this.form.imei_no.disabled=true"></td>
            <td>Select</td>
             <td>&nbsp;:&nbsp;</td>
            <td>';              
              if($d==0)
              {
                  echo '<font color=red>No device Found</font>';
              }
              else
              {
                echo'<select name="imei_no2" id="imei_no2" size="6" onFocus="javascript:thisform.device_option[1].checked=true;">';              
                	        
                for($i=0; $i<$d; $i++)
                {
                  echo'<option value="'.$device[$i].'" onclick="document.getElementById(\'enter_button\').disabled=false;">'.$device[$i].'</option>';
                }                                                            
                echo'</select>';
              }          
            echo'</td>
        </tr>
    </table>
    </fieldset>  
    </td>
    
   <td>
   <fieldset class="manage_fieldset">
		<legend>Select Vehicle to Assign</legend>		
   
    <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
        
        <tr>
            <td><input type="radio" name="vehicle_option" value="1" onclick="javascript:this.form.vname.disabled=false;this.form.vid2.disabled=true"></td>
            <td>Search</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="vname" id="vname" onFocus="javascript:thisform.vehicle_option[0].checked=true;" onkeyup="manage_availability(this.value, \'vname\', \'existing_not_assigned\')" onmouseup="manage_availability(this.value, \'vname\', \'existing_not_assigned\')" onchange="manage_availability(this.value, \'vname\', \'existing_not_assigned\')">
            </td>
        </tr> 
        
        <tr valign="top">
            <td><input type="radio" name="vehicle_option" value="2" onclick="javascript:this.form.vid2.disabled=false;this.form.vname.disabled=true"></td>
            <td>Select</td>
             <td>&nbsp;:&nbsp;</td>
            <td>';
              if($v == 0)
              { 
                echo '<font color=red>No vehicle Found</font>';
              }
              else
              {           
                echo'<select name="vid2" id="vid2" size="6" onFocus="javascript:thisform.vehicle_option[1].checked=true;">';             
                                     
                 for($i=0;$i<$v;$i++)
                 {
                    echo '<option value="'.$vid[$i].'" onclick="document.getElementById(\'enter_button\').disabled=false;">'.$vname[$i].'</option>';
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
			<td align="center" colspan="3"><br><input type="button" id="enter_button" disabled="true" Onclick="javascript:return action_manage_device_vehicle_assignment(thisform)" value="Assign">&nbsp;<input type="reset" value="Cancel"></td>
		</tr>
		</table>
    
    </fieldset>  
   </form>
  <div id="available_message" align="center"></div>
  </center>
  ';
   
?>  