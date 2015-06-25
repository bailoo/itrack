<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 
	$js_function_name = "manage_show_file_1";    // FUNCTION NAME
	$action_type1=$_POST['action_type']; 
	//echo "Action Type=".$action_type1."<br>";
		echo '<center>
	     <div style="height:10px"> </div> 			
				<fieldset class="manage_fieldset">
					<legend><strong>Secondary Vehicle</strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>';
								if($action_type1=="assign")
								{
									echo'<input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_secondary_vehicle_assign.php\',\'assign\')" checked/> Assign
									&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_secondary_vehicle_deassign.php\',\'deassign\')"/> De-Assign
									';
								}
								else if($action_type1=="deassign")
								{                	
									echo'<input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_secondary_vehicle_assign.php\',\'assign\')"/> Assign
									&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_secondary_vehicle_deassign.php\',\'deassign\')" checked/> De-Assign
									';
								}
								else if($action_type1=="")
								{
									echo'<input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_secondary_vehicle_assign.php\',\'assign\')"/> Assign
									&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_secondary_vehicle_deassign.php\',\'deassign\')"/> De-Assign';
								}
						  echo'</td>
							</tr>
						</table>     
				</fieldset>		
		</center>';  
	?>
	
