<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
  
	echo "add##"; 
	
	echo' <br>

    	<fieldset class="report_fieldset">
    		<legend><strong>Add Raw Milk Invoice Material</strong></legend>	
				
        <table border="0" class="manage_interface">
					<tr>
						<td>Material Name</td><td>:</td>
						<td><input type="text" name="material_name" id="material_name" onkeyup="manage_availability(this.value, \'invoice_rawmilk\')" ></td>
					</tr>
					<tr>
						<td>Material Code</td><td>:</td>
						<td><input type="text" name="material_code" id="material_code"> </td>	
					</tr>
					<tr>
						<td colspan="3" align="center"><input type="button" id="enter_button" value="Save" onclick="javascript:action_manage_invoiceMaterial(\'add\')"/></td>
					</tr>
				</table>
        
    </fieldset>
<div id="available_message" align="center"></div>    
';
    
	
?>
  