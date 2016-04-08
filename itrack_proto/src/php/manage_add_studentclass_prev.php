<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('user_type_setting.php');
	
	$root=$_SESSION['root'];  
	echo "add##";
	//include_once('tree_hierarchy_information.php');
	//echo"<form name='manage1'>";
	echo'<div id="portal_vehicle_information">';	
	include_once('manage_checkbox_account_new.php');  	
	echo'</div>';
	//include_once('manage_radio_account.php'); 
  echo'<br>
	<center>		
		<input type="button" value="Enter" onclick="javascript:manage_edit_prev_checkbox(\'src/php/manage_add_studentclass.php\');">&nbsp;			
	</center>';
	//</form>
	
?>       
				


  