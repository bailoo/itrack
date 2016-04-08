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
  
  $query = "SELECT route_id,route_name FROM route WHERE ".
  "user_account_id='$user_account_id' AND ".
  "route_id NOT IN(SELECT route_id FROM vehicle WHERE route_id!=NULL) AND status='1'"; 

  if($DEBUG==1)
    print_query($query);
      
  $result = @mysql_query($query, $DbConnection);
  $r_count=0;
  
  while($row = mysql_fetch_object($result))
  {
    $route_id[$r_count] = $row->route_id;
    $route_name[$r_count] = $row->route_name;
    $r_count++;
  }
  //print_query($query);  
  
  // GET ACCOUNT VEHICLES + NOT ASSIGNED
  $query = "SELECT VehicleID,VehicleName FROM vehicle WHERE ".
  "VehicleID IN(SELECT vehicle_id FROM vehicle_grouping WHERE ".
  "vehicle_group_id =(SELECT vehicle_group_id FROM ".
  "account_detail WHERE account_id='$account_id') AND ".
  "vehicle_id IN(SELECT VehicleID from vehicle WHERE route_id IS NULL))";
  
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
		<legend><strong>Route Assignment</strong></legend>		
        
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
    <td>
  <fieldset class="manage_fieldset">
		<legend>Select Route</legend>		
		
    <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
        
        <tr>
            <td><input type="radio" name="route_option" value="1" onclick="javascript:this.form.route_name.disabled=false;this.form.route_id2.disabled=true"></td>
            <td>Search</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="route_name" id="route_name" onFocus="javascript:thisform.route_option[0].checked=true;" onkeyup="manage_availability(this.value, \'route_name\', \'existing_in_user\')" onmouseup="manage_availability(this.value, \'route_name\', \'existing_in_user\')" onchange="manage_availability(this.value, \'route_id\', \'existing_in_user\')">
            </td>
        </tr> 
        
        <tr valign="top">
            <td><input type="radio" name="route_option" value="2" onclick="javascript:this.form.route_id2.disabled=false;this.form.route_name.disabled=true"></td>
            <td>Select</td>
             <td>&nbsp;:&nbsp;</td>
            <td>';              
              if($r_count==0)
              {
                  echo '<font color=red>No Route Found</font>';
              }
              else
              {
                echo'<select name="route_id2" id=""route_id2" size="6" onFocus="javascript:thisform.route_option[1].checked=true;">';              
                	        
                for($i=0; $i<$r_count; $i++)
                {
                  echo'<option value="'.$route_id[$i].'" onclick="document.getElementById(\'enter_button\').disabled=false;">'.$route_name[$i].'</option>';
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
			<td align="center" colspan="3"><br><input type="button" id="enter_button" disabled="true" Onclick="javascript:return action_manage_route_assignment(thisform)" value="Assign">&nbsp;<input type="reset" value="Cancel"></td>
		</tr>
		</table>
    
    </fieldset>  
   </form>
  <div id="available_message" align="center"></div>
  </center>
  ';
   
?>  