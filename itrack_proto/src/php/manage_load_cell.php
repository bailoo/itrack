<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$js_function_name = "manage_show_file";    // FUNCTION NAME  
	echo '<center>
			<form name="manage1">
				<fieldset class="manage_fieldset">
					<legend><strong>Load Cell</strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>
									<input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_add_load_cell.php\')"/> Add&nbsp;&nbsp;&nbsp;&nbsp; 
									<input type="radio" name="new_exist" value="exist"/> Edit &nbsp;&nbsp;&nbsp;&nbsp; 
									</td>
							</tr>
						</table>     
				</fieldset>
				<div style"display:none;" id="edit_div"> </div>
			</form>';
			
		echo'</center>';  
?>  