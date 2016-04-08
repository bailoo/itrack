<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
    echo "edit##"; 	
	$js_function_name = "manage_show_file";    // FUNCTION NAME
	
	$account_id_local=$_POST['common_id'];
	//echo "<input type='hidden' id='account_id_local' value=".$account_id_local.">";
  //echo "account_id_local=".$account_id_local;  
  
	echo '<center>
				<fieldset class="manage_fieldset3">
					<legend><strong>Edit Option</strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>
							     <input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_edit_delete_station.php?account_id_local='.$account_id_local.'\')"/> Edit Detail &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_edit_distance_variable_prev.php?account_id_local='.$account_id_local.'\')"/> Edit Distance Variable&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
								</td>
							</tr>
						</table>     
				</fieldset>	
        <br>
        <div style="display:none;" id="edit_div_station1"> </div>	
  </center>';  
?>  