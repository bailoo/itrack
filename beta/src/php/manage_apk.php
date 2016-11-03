<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 

	$js_function_name = "manage_show_file";    // FUNCTION NAME  
	/*echo '<center>
			<form name="manage1">
				<fieldset class="manage_fieldset">
					<legend><strong>Schedule Visit</strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_add_visit_person_prev.php\')"/> Add Schedule  &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_edit_visit_person_prev.php\')"/> Edit Schedule  &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<!--
                  <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_assignment_route_prev.php\')"/> Assign &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_route_prev.php\')"/> De-Assign &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
								  -->
                </td>
							</tr>
						</table>     
				</fieldset>
				<div style"display:none;" id="edit_div"> </div>
			</form>
		</center>'; 
        */
        echo'<form name="manage1">
     <ol class="breadcrumb">
                <li><a href="#">Manage</a></li>
                <li class="active"><b>Manage APK</b> 
                    <div id="tab" class="btn-group" data-toggle="buttons" >
                        <a onclick="'.$js_function_name.'(\'src/php/manage_assign_apk_prev.php\')" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                           <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_assign_apk_prev.php\')"/><i class="fa fa-plus-square" aria-hidden="true"></i> Assign APK
                        </a>
                        <!--<a onclick="'.$js_function_name.'(\'src/php/manage_assign_apk_prev.php\')" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                          <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_assign_apk_prev.php\')"/><i class="fa fa-pencil" aria-hidden="true"></i> Edit Schedule
                        </a>-->                  
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