<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
echo'<div style="height:5px;"></div>
  <form method = "post"  name="thisform">
     <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
         <tr>
          <td colspan="3" align="center"><b>Add Vehicle</b><div style="height:5px;"></div></td>    
        </tr>';   
    echo'        									
				<tr>
						<td>Device</td>
						<td>&nbsp;:&nbsp;</td>
						<td>
              <select name="device_id" id="device_id">
                <option value="'.$d1.'">device 1</option>
                <option value="'.$d2.'">device 2</option>
                <option value="'.$d3.'">device 3</option>
              </select>
            </td>
				</tr>
        <tr>
						<td>Vehicle</td>
						<td>&nbsp;:&nbsp;</td>
						<td>
              <select name="device_id" id="device_id">
                <option value="'.$v1.'">vehicle 1</option>
                <option value="'.$v2.'">vehicle 2</option>
                <option value="'.$v3.'">vehicle 3</option>
              </select>
            </td>
				</tr>								
				
			  <tr>                    									
					<td align="center"  colspan="3"><div style="height:10px"></div><input type="button" Onclick="javascript:return show_add_account_res(thisform)" value="Enter" id="enter_button">&nbsp;<input type="reset" value="Clear"></td>
				</tr>
    </table>
  </form>
  ';
?>
