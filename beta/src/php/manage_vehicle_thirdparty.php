<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$js_function_name = "manage_show_file";    // FUNCTION NAME  
        echo'
            <form name="manage1">
            <ol class="breadcrumb">
                <li><a href="#">Manage</a></li>
                <li class="active"><b>Managing ThirdParty Vehicle</b> 
                    <div id="tab" class="btn-group" data-toggle="buttons" >
                            <a onclick="'.$js_function_name.'(\'src/php/manage_assign_vehicle_thirdparty.php\')" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                               <input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_assign_vehicle_thirdparty.php\')"/> Assign/DeAssign ThirdParty
                            </a>                                   
                    </div>

                </li>
            </ol>';
	/*echo '<center>
			<form name="manage1">
				<fieldset class="manage_fieldset">
					<legend><strong>Managing ThirdParty Vehicle</strong></legend>-->
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>
									<input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_assign_vehicle_thirdparty.php\')"/> Assign/DeAssign ThirdParty &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									
								</td>
							</tr>
						</table>     
				</fieldset>
				<div style"display:none;" id="edit_div"> </div>
			</form>
		</center>'; */
       echo '<center><div style"display:none;" id="edit_div"> </div></center>
			</form>';
include_once('manage_loading_message.php');