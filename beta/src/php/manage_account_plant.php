<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 
	include_once('user_type_setting.php');	
	$js_function_name = "manage_show_file";    // FUNCTION NAME
	$page_debug=0;
  	
/*echo'<center>
	<form name="manage1">
		<fieldset class="manage_fieldset">
			<legend><strong>Plant</strong></legend>
				<table border="0" class="manage_interface" align="center">
					<tr>
						<td>
							<!--manage_substation_vehicle_assignment_prev    manage_substation_vehicle_deassignment_prev-->
							<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_account_plant_assignment_prev.php\')"/> Assignment &nbsp;&nbsp;&nbsp;
							<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_account_plant_deassignment_prev.php\')"/> De-Assignment
						</td>
					</tr>
				</table>     
		</fieldset>
		<div style"display:none;" id="edit_div"> </div>
	</form>
</center>'; */
echo'<form name="manage1">
     <ol class="breadcrumb">
                <li><a href="#">Manage</a></li>
                <li class="active"><b>Plant Account</b> 
                    <div id="tab" class="btn-group" data-toggle="buttons" >
                        <a onclick="'.$js_function_name.'(\'src/php/manage_account_plant_assignment_prev.php\')" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                           <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_account_plant_assignment_prev.php\')"/><i class="fa fa-link" aria-hidden="true"></i> Assignment
                        </a>
                        <a onclick="'.$js_function_name.'(\'src/php/manage_account_plant_deassignment_prev.php\')" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                          <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_account_plant_deassignment_prev.php\')"/><i class="fa fa-chain-broken" aria-hidden="true"></i> De-Assignment
                        </a>
                    </div>
                </li>
            </ol>';
 echo'
	
            
			
                <center>
                    <div style"display:none;" id="edit_div"> </div>
                </center>
	</form>
';
?>
	
