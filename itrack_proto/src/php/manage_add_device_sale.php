<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  echo "add##";
  
  $DEBUG = 0;
  
  //$query = "SELECT device_sales_info.device_imei_no,account.user WHERE account.user IN ( SELECT device_sales_info.user_account_id from device_sales_info WHERE create_id='$account_id' AND status='1')";
  $query = "SELECT device_imei_no FROM device_sales_info  WHERE create_id='$account_id' AND status='1'";
  if($DEBUG == 1)
    print_query($query);
      
  $result = mysql_query($query,$DbConnection);
  $j=0;                   
  while($row = mysql_fetch_object($result))
  {
    $device_imei[$j] = $row->device_imei_no;
    //$user_acc[$j] = $row->user;
    //if($DEBUG == 1)
      //echo $device_imei[$j]." ,".$user_accs[$j]."<br>";
    $j++;
  }
    
?>   

  <fieldset class="manage_fieldset">
		<legend><strong>Add Device Sale</strong></legend>		
        
    <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
        
        <tr>
            <td>Device IMEI No</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="imei_no" id="imei_no" onkeyup="manage_availability(this.value, 'imei_no', 'existing#device_sale')" onmouseup="manage_availability(this.value, 'imei_no', 'existing#device_sale')" onchange="manage_availability(this.value, 'imei_no', 'existing#device_sale')">
            </td>
        </tr> 
        
        <tr>
            <td>Super USer</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="super_user" id="super_user" onkeyup="manage_availability(this.value , 'super_user', 'existing#device_sale')" onmouseup="manage_availability(this.value , 'super_user', 'existing#device_sale')" onchange="manage_availability(this.value , 'super_user', 'existing#device_sale')">
      </td>
        </tr>
        
        <tr>
            <td>User</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="user" id="user" onkeyup="manage_availability(this.value, 'user', 'existing#device_sale')" onmouseup="manage_availability(this.value, 'user', 'existing#device_sale')" onchange="manage_availability(this.value, 'user', 'existing#device_sale')">
            </td>
        </tr>
        
       <!-- <tr>
            <td>QOS</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" size="2px" name="qos" id="qos" onkeyup="manage_availability(this.value, 'qos', 'existing#device_sale')" onmouseup="manage_availability(this.value, 'qos', 'existing#device_sale')" onchange="manage_availability(this.value, 'qos', 'existing#device_sale')">
            </td>
        </tr>-->
                
			  <tr>                    									
					<td align="center" colspan="3"><br><input type="button" name="enter_button" id="enter_button" Onclick="javascript:action_manage_device_sale(thisform, 'add')" value="Sell">&nbsp;<input type="reset" value="Cancel"></td>
				</tr>
    </table>
    </fieldset>  
  