<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];  
	$js_function_name="manage_select_by_entity";
	$file_name="src/php/manage_unassigned_vehicle_option.php";
	echo "register##"; 
	include_once('tree_hierarchy_information.php');
	include_once('manage_radio_account.php');

	echo'<br>
	<center>	
	<div align="center" id="portal_vehicle_information" style="display:none;"></div>			
		<br>
		<input type="button" value="Enter" onclick="javascript:manage_edit_prev(\'src/php/manage_vehicle_deassignment.php\');">&nbsp;			
	</center>';
?>
  