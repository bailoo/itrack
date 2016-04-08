<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $DEBUG =0;
  
  $edit_device_sale = $_POST['edit_device_sale'];
  
  echo 'edit##';
  
  $query = "SELECT device_sales_info.device_imei_no,account.superuser, account.user FROM ".
  "device_sales_info,account WHERE ".
  "account.account_id = device_sales_info.user_account_id AND ".
  "device_sales_info.device_imei_no='$edit_device_sale' AND ".
  "device_sales_info.create_id='$account_id' AND device_sales_info.status='1'";
  
  $result = mysql_query($query,$DbConnection);
  
  //if($DEBUG == 1) print_query($query);
  
  $row = mysql_fetch_object($result);
  $imei_no_2 = $row->device_imei_no;
  $superuser_2 = $row->superuser; 
  $user_2 = $row->user; 
  
  
  $query = "SELECT QOS FROM `table` WHERE TableID = (SELECT track_table_id FROM device_lookup WHERE ".
  "device_imei_no='$edit_device_sale')";
  
  if($DEBUG == 1) print_query($query);
  
  $result = mysql_query($query, $DbConnection);
  $row = mysql_fetch_object($result);
  $qos_2 = $row->QOS; 
  
  if($DEBUG == 1) echo "qos_2=".$qos_2;
      
  echo'
  <fieldset class="manage_fieldset">
		<legend><strong>Add Device Sale</strong></legend>		
        
    <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
        
        <tr>
            <td>Device IMEI No</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="imei_no" id="imei_no" value="'.$imei_no_2.'"  readOnly="true" onkeyup="manage_availability(this.value, \'imei_no\', \'existing\')" onmouseup="manage_availability(this.value, \'imei_no\', \'existing\')" onchange="manage_availability(this.value, \'imei_no\', \'existing\')">
            </td>
        </tr> 
        
        <tr>
            <td>Super USer</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="super_user" id="super_user" value="'.$superuser_2.'" readOnly="true" onkeyup="manage_availability(this.value , \'super_user\', \'existing\')" onmouseup="manage_availability(this.value , \'super_user\', \'existing\')" onchange="manage_availability(this.value , \'super_user\', \'existing\')">
      </td>
        </tr>
        
        <tr>
            <td>User</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="user" id="user" value="'.$user_2.'" readOnly="true" onkeyup="manage_availability(this.value, \'user\', \'existing\')" onmouseup="manage_availability(this.value, \'user\', \'existing\')" onchange="manage_availability(this.value, \'user\', \'existing\')">
            </td>
        </tr>
        
        <tr>
            <td>QOS</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" size="2px" name="qos" id="qos" value="'.$qos_2.'" onkeyup="manage_availability(this.value, \'qos\', \'existing\')" onmouseup="manage_availability(this.value, \'qos\', \'existing\')" onchange="manage_availability(this.value, \'qos\', \'existing\')">
            </td>
        </tr>
                
			  <tr>                    									
					<td align="center" colspan="3"><br>
          <input type="button" id="enter_button" Onclick="javascript:return action_manage_device_sale(thisform, \'edit\')" value="Update" disabled="true">&nbsp;
          <input type="reset" value="Cancel">&nbsp;
          <input type="button" id="enter_button" Onclick="javascript:return action_manage_device_sale(thisform, \'delete\')" value="Delete Sale">
          </td>
				</tr>
    </table>
    </fieldset>
   ';
 ?>  