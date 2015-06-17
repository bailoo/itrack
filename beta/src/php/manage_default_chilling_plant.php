<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 
	include_once('user_type_setting.php');	
	$js_function_name = "manage_show_file";    // FUNCTION NAME
	$page_debug=0;
  		
echo'<center>
	<form name="manage1">
		<fieldset class="manage_fieldset">
			<legend><strong>Default Chilling Plant</strong></legend>
				<table border="0" class="manage_interface" align="center">
					<tr>
						<td>
							
							<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_default_chilling_plant_assignment_prev.php\')"/> Assignment &nbsp;&nbsp;&nbsp;
							<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_default_chilling_plant_deassignment_prev.php\')"/> De-Assignment
						</td>
					</tr>
				</table>     
		</fieldset>
		<div style"display:none;" id="edit_div"> </div>
	</form>
</center>'; 
?>
	
