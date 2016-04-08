<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
  
	echo "add##"; 
	include_once('tree_hierarchy_information.php');
	include_once('manage_radio_account.php'); 
	echo' <br>

    	<fieldset class="report_fieldset">
    		<legend><strong>Add Transporter</strong></legend>	
				
        <table border="0" class="manage_interface">
					<tr>
						<td>Name</td><td>:</td>
						<td><input type="text" name="transporter_name" id="transporter_name" onkeyup="manage_availability(this.value, \'transporter\')" onmouseup="manage_availability(this.value,\'geofence\')" onchange="manage_availability(this.value, \'geofence\')"></td>
					</tr>
					<tr>
						<td>State</td><td>:</td>
						<td><input type="text" name="state" id="state"> &nbsp;(India)</td>	
					</tr>
					<tr>
						<td>City</td><td>:</td>
						<td><input type="text" name="city" id="city"></td>	
					</tr>
					<tr>
						<td>Address1</td><td>:</td>
						<td><textarea name="address1" id="address1"></textarea></td>	
					</tr>
					<tr>
						<td>Address2</td><td>:</td>
						<td><textarea name="address2" id="address2"></textarea></td>	
					</tr>				                                 					
					<tr>
						<td colspan="3" align="center"><input type="button" id="enter_button" value="Save" onclick="javascript:action_manage_transporter(\'add\')"/>&nbsp;<input type="reset"" value="Clear" /></td>
					</tr>
				</table>
        
    </fieldset>';
    
	include_once('availability_message_div.php');
?>
  