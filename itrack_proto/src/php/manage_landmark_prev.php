<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
	include_once('tree_hierarchy_information.php');
	include_once('manage_display_accounts.php'); 
	echo'<br>
		<center>
			<input type="button" value="Enter"  id="enter_button" onclick="javascript:return validation_landmark_user(\'src/php/manage_landmark.php\');">&nbsp;        
		</center>
	</form>';  
?>   
 