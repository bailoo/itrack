<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$js_function_name = "manage_show_file";    // FUNCTION NAME  
         echo'<ol class="breadcrumb">
                <li><a href="#">Manage</a></li>
                <li class="active"><b>Managing ThirdParty Accounts</b>                  
                </li>
            </ol>';
	/*echo '<center>
			<form name="manage1">
				<fieldset class="manage_fieldset">
					<legend><strong>Managing ThirdParty Accounts</strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>
									<input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_assign_account_thirdparty.php\')"/> Assign/DeAssign ThirdParty &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									
								</td>
							</tr>
						</table>     
				</fieldset>
				<div style"display:none;" id="edit_div"> </div>
			</form>
		</center>'; */
         echo '<center>
			<form name="manage1">				
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>
									<input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_assign_account_thirdparty.php\')"/> Assign/DeAssign ThirdParty &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									
								</td>
							</tr>
						</table>     
				
				<div style"display:none;" id="edit_div"> </div>
			</form>
		</center>'; 
include_once('manage_loading_message.php');