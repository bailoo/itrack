<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$js_function_name = "manage_show_file_1";    // FUNCTION NAME
	$action_type1=$_POST['action_type'];
	//echo "action_type1=".$action_type1;
  	echo '<center>
	     <div style="height:10px"> </div> 			
				<fieldset class="manage_fieldset">
					<legend><strong>Callibration</strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>';
								if($action_type1=="add")
								{
									echo'<input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\')" checked/> Add
									   &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')"/> Edit / Delete
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')"/> Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')"/> De-Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;';
								}
                else if($action_type1=="edit_delete")
                {                	
                  	echo'<input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\')"/> Add
									   &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')" checked/> Edit / Delete
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')"/> Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')"/> De-Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;';
								}
								else if($action_type1=="assign")
                {
                  	echo'<input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\',\'add\')"/> Add
									   &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')"/> Edit / Delete
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')" checked/> Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')"/> De-Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;';
                }
                else if($action_type1=="de-assign")
                {
								 	echo'<input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\',\'add\')"/> Add
									   &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')"/> Edit / Delete
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')"/> Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')" checked/> De-Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;';
								}
								else if($action_type1=="")
								{
                    	echo'<input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\',\'add\')"/> Add
									   &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')"/> Edit / Delete
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')"/> Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')"/> De-Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;';
                }
                echo'</td>
							</tr>
						</table>     
				</fieldset>		
		</center>';  
?>  