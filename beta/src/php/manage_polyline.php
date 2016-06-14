<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$js_function_name = "manage_show_file";    // FUNCTION NAME  
	/*
        echo '<center>
			<form name="manage1">
				<fieldset class="manage_fieldset">
					<legend><strong>Ployline(Route)</strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>
									<input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_add_polyline.php\')"/> AddPolyline &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_edit_polyline_prev.php\')"/> EditPolyline &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_assignment_polyline_prev.php\')"/> AssignVehicle &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_polyline_prev.php\')"/> De-AssignVehicle &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                                                                            <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_register_polyline_prev.php\')"/> RegisterPolyline &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
								</td>
							</tr>
						</table>     
				</fieldset>
				<div style"display:none;" id="edit_div"> </div>
			</form>
		</center>'; 
        */
        echo" <form name='manage1'>
            <ol class='breadcrumb'>
                <li><a href='#'>Manage</a></li>
                <li class='active'><b>Ployline(Route)&nbsp;</b>
                 <div id='tab' class='btn-group' data-toggle='buttons' >
                 ";
                echo'
                   <a onclick="'.$js_function_name.'(\'src/php/manage_add_polyline.php\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                    <input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_add_polyline.php\')"/><i class="fa fa-plus-square" aria-hidden="true"></i> Add Polyline
                   </a>
                   <a onclick="'.$js_function_name.'(\'src/php/manage_edit_polyline_prev.php\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
		    <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_edit_polyline_prev.php\')"/><i class="fa fa-pencil" aria-hidden="true"></i> Edit Polyline
                   </a>
                   <a onclick="'.$js_function_name.'(\'src/php/manage_assignment_polyline_prev.php\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
		    <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_assignment_polyline_prev.php\')"/><i class="fa fa-link" aria-hidden="true"></i>  Assign Vehicle
                   </a>
                   <a onclick="'.$js_function_name.'(\'src/php/manage_deassignment_polyline_prev.php\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
		    <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_polyline_prev.php\')"/><i class="fa fa-chain-broken" aria-hidden="true"></i> De-Assign Vehicle
                   </a>
                   <a onclick="'.$js_function_name.'(\'src/php/manage_register_polyline_prev.php\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
		    <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_register_polyline_prev.php\')"/><i class="fa fa-check-square-o" aria-hidden="true"></i> Register Polyline
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
