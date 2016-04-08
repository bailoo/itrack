<?php
	set_time_limit(0);
        include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$js_function_name = "manage_show_file";    // FUNCTION NAME  
	echo '<center><br><b>				
			<form name="manage1">
				<fieldset style="width:800px">
					<legend><strong>Manage Invoice Material </strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>
									<input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_add_invoice_material.php\')"/> Add Invoice Material.&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;									
									<input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_edit_delete_rawmilkInvoiceMaterial.php\')"/> Edit Invoice Material. &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									
								</td>
							</tr>
						</table>     
				</fieldset>
				<div style"display:none;" id="edit_div"> </div>
			</form>
		</center>';  
?>  