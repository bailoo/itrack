<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$js_function_name = "manage_show_file";    // FUNCTION NAME  
	echo '<center>
			<form name="manage1">
				<fieldset class="manage_fieldset">
					<legend><strong>KlP INPUT</strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>									
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_klp_input_prev.php\');document.getElementById(\'automatic\').style.display=none;document.getElementById(\'manual\').style.display=none;"/> Assign &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<!--<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_route_prev.php\');document.getElementById(\'automatic\').style.display=none;document.getElementById(\'manual\').style.display=none;"/> De-Assign &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;-->									
								</td>
							</tr>
						</table>     
				</fieldset>
				<div style"display:none;" id="edit_div"> </div>
			</form>';
			
			
    echo '<div style="height:5px;display:none;" id="automatic"></div>';  
			
	echo'</center>';  
?>  