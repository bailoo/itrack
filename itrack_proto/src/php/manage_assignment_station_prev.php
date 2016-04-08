<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];  
	echo "edit##"; 
	include_once('tree_hierarchy_information.php');
	include_once('manage_radio_account.php');
	echo'<br>
	<center>		
		<input type="button" value="Enter" onclick="javascript:manage_edit_prev(\'src/php/manage_station_vehicle_assignment.php\');">&nbsp;			
	</center>';
	include_once('manage_loading_message.php');	
?>
  