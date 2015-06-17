<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];  
	//echo "add##";
	echo '
  <center>
	<form name="setting">	
  '; 
	include_once('tree_hierarchy_information.php');
	//include_once('manage_checkbox_account.php');
	include_once('setting_display_accounts.php');
	//include_once('manage_radio_account.php'); 
  echo'<br>
	<center>		
		<input type="button" value="Enter" onclick="javascript:setting_edit_prev(\'src/php/setting_configure_alert.php\');">&nbsp;			
	</center>
	</form>
	</center>';
?>    

