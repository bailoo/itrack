<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$js_function_name = "manage_show_file";    // FUNCTION NAME  
	/*echo '<center>
			<form name="manage1">
				<fieldset class="manage_fieldset">
					<legend><strong>Geofence</strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>
									<input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_add_geofence.php\')"/> Add &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_edit_geofence_prev.php\')"/> Edit &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_assignment_geofence_prev.php\')"/> Assign &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_geofence_prev.php\')"/> De-Assign &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
								</td>
							</tr>
						</table>     
				</fieldset>
				<div style"display:none;" id="edit_div"> </div>
			</form>
		</center>'; */
        echo" <form name='manage1'>
            <ol class='breadcrumb' style='background-color:#f7fafc;padding:1px 15px;border-radius:0px'>
                
                <li class='active'><b>Geofence</b>&nbsp;&nbsp;&nbsp;
                 <div id='tab' class='btn-group' data-toggle='buttons' >
                 ";
         echo'
                   <a onclick="'.$js_function_name.'(\'src/php/manage_add_geofence.php\');" class="btn btn-default" data-toggle="tab" style="padding: 1px 12px;">
                    <input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_add_geofence.php\')"/><i class="fa fa-plus-square" aria-hidden="true"></i> Add
                   </a>
                   <a onclick="'.$js_function_name.'(\'src/php/manage_edit_geofence_prev.php\');" class="btn btn-default" data-toggle="tab" style="padding: 1px 12px;">
		    <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_edit_geofence_prev.php\')"/><i class="fa fa-pencil" aria-hidden="true"></i> Edit
                   </a>
                   <a onclick="'.$js_function_name.'(\'src/php/manage_assignment_geofence_prev.php\');" class="btn btn-default" data-toggle="tab" style="padding: 1px 12px;">
		    <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_assignment_geofence_prev.php\')"/><i class="fa fa-link" aria-hidden="true"></i> Assign
                   </a>
                   <a onclick="'.$js_function_name.'(\'src/php/manage_deassignment_geofence_prev.php\');" class="btn btn-default" data-toggle="tab" style="padding: 1px 12px;">
		    <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_geofence_prev.php\')"/><i class="fa fa-chain-broken" aria-hidden="true"></i> De-Assign
                   </a>
                  </div>
                 </li>
            </ol>
                <center>
                    <div style"display:none;" id="edit_div"> </div>	
                </center>
            </form>
            ';
include_once('manage_loading_message.php');
 
?>  
