<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
	$final_group_array=$_SESSION['final_group_array'];
	echo "edit##"; 
	include_once('tree_hierarchy_information.php');
	include_once('manage_radio_group_account.php');
	echo'<br>			
		<input type="button" value="Enter" onclick="javascript:manage_edit_prev(\'src/php/manage_edit_delete_account.php\');">&nbsp;			
		';
?>
  