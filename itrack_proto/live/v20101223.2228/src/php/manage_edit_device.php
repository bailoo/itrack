<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $edit_device = $_POST['edit_device'];
  
  echo 'edit##'; 
  
  $DEBUG = 0;
  $query = "SELECT * FROM device_manufacturing_info WHERE device_id='$edit_device' AND create_id='$account_id'";
  $result = mysql_query($query,$DbConnection);
  
  if($DEBUG == 1)
    print_query($query);
  
  $row = mysql_fetch_object($result);
  $imei_no = $row->device_imei_no;
  $manufacturing_date = $row->manufacture_date;
  $make = $row->make;
  
echo'
  
<center>
  <fieldset>
		<legend><strong>Edit Device<strong></legend>		
      
   <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">        
        
        <tr>
            <td>Device IMEI No</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="imei_no_edit" id="imei_no_edit" value="'.$imei_no.'" readonly="true" onkeyup="manage_availability(this.value, \'imei_no\', \'new\')" onmouseup="manage_availability(this.value, \'imei_no\', \'new\')" onchange="manage_availability(this.value, \'imei_no\', \'new\')">
            </td>
        </tr> 
        
        <tr>
            <td>Manufacturing Date</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="manufacturing_date_edit" id="manufacturing_date_edit" value="'.$manufacturing_date.'" size="17" maxlength="19" Onclick="javascript:NewCal(\'manufacturing_date_edit\',\'yyyymmdd\',true,24)"> 
          	   <a href=javascript:NewCal("manufacturing_date_edit","yyyymmdd",true,24)>
                  <img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
                 </a>
      </td> 
        </tr>
        
        <tr>
            <td>Make</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="make_edit" id="make_edit" value="'.$make.'">
            </td>
        </tr>
			  <tr>                    									
					<td align="center" colspan="3">
          <input type="button" id="u_d_enter_button" Onclick="javascript:return action_manage_device(thisform, \'edit\')" value="Update">&nbsp;
          <input type="reset" value="Clear">&nbsp;
          <input type="button" id="u_d_enter_button" Onclick="javascript:return action_manage_device(thisform, \'delete\')" value="Delete Device">
         </td> 
				</tr>
    </table>   
  </fieldset>
</center>
';
?>