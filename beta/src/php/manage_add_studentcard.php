<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];  
	echo "add##";
	include_once('tree_hierarchy_information.php');
	include_once('manage_checkbox_account.php');   
 ?>  
	<br>
	<table border="0" class="manage_interface">  			
		<tr>
			<td>Number</td>
			<td> :</td>
			<td><input type="text" name="add_studentcard_name" id="add_studentcard_name" onkeyup="manage_availability(this.value, 'studentcard')" onmouseup="manage_availability(this.value, 'studentcard')" onchange="manage_availability(this.value, 'studentcard')"></td>
		</tr>   		
		 		
		<tr>
			<td colspan="3" align="center">
				<input type="button" id="enter_button" value="Save" onclick="javascript:return action_manage_studentcard('add')"/>&nbsp;<input type="reset"" value="Clear" />
			</td>
		</tr>
	</table>
	<?php
		include_once('availability_message_div.php');
	?>

  