<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $DEBUG = 0;
  
  $edit_device_sale = $_POST['edit_device_sale'];
  
  echo 'edit##';
  
  
  $data = getDetailAllDeviceSales($edit_device_sale,$account_id,$DbConnection);
  foreach($data as $dt)
	{
		$imei_no_2=$dt['imei_no_2'];
        $superuser_2=$dt['superuser_2'];
		$user_2=$dt['user_2'];				
	}    
  echo'
  <fieldset class="manage_fieldset">
		<legend><strong>Add Device Sale</strong></legend>		
        
    <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
        
        <tr>
            <td>Device IMEI No</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="imei_no" id="imei_no" value="'.$imei_no_2.'" readOnly="true" >
              <!-- onkeyup="manage_availability(this.value, \'imei_no\', \'existing\')" onmouseup="manage_availability(this.value, \'imei_no\', \'existing\')" onchange="manage_availability(this.value, \'imei_no\', \'existing\')">-->
            </td>
        </tr> 
        
        <tr>
            <td>Super USer</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="super_user" id="super_user" value="'.$superuser_2.'" readOnly="true" > 
              <!--onkeyup="manage_availability(this.value , \'super_user\', \'existing\')" onmouseup="manage_availability(this.value , \'super_user\', \'existing\')" onchange="manage_availability(this.value , \'super_user\', \'existing\')">-->
      </td>
        </tr>
        
        <tr>
            <td>User</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="user" id="user" value="'.$user_2.'" readOnly="true" >
              <!--onkeyup="manage_availability(this.value, \'user\', \'existing\')" onmouseup="manage_availability(this.value, \'user\', \'existing\')" onchange="manage_availability(this.value, \'user\', \'existing\')">-->
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