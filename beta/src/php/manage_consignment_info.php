<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 
	include_once('user_type_setting.php');	
	$js_function_name = "manage_show_file";    // FUNCTION NAME
	$page_debug=0;
  	if($page_debug==1)
	{
		echo'<br><table border="1" class="manage_interface" align="center">
				<tr>
					<td><b>Add Files</b></td>
                                        <td><b>Edit Files</b></td>	
				</tr>
				<tr>
					<td>manage_add_consignment_info.php</td>
                                        <td>manage_edit_consignment_prev.php</td>	 
				</tr>			
			</table>';
	}
			
echo'<center>
	<form name="manage1">
		<fieldset class="manage_fieldset">
			<legend><strong>Consignment</strong></legend>
				<table border="0" class="manage_interface" align="center">
					<tr>
						<td>
							<input type="radio" name="new_exist" value="new" '.$check_status.' onclick="'.$js_function_name.'(\'src/php/manage_add_consignment_info.php\')"/> Add &nbsp;&nbsp;&nbsp;
							<!--<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_edit_consignment_prev.php\')"/> Edit / Delete&nbsp;&nbsp;&nbsp;-->
						</td>
					</tr>
				</table>     
		</fieldset>
		<div style"display:none;" id="edit_div"> </div>
	</form>
</center>'; 
?>
	
