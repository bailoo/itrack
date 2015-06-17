<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
	echo"choose_account##";
	$target_file=$_POST['target_file'];

	$target_file1=explode(",",$target_file);
	$file_name="'".$target_file1[0]."'";
	$action_type="'".$target_file1[1]."'";
	//echo "file_name=".$file_name."action_type=".$action_type;
	include_once('tree_hierarchy_information.php');
	
	if($action_type=="add")
	{
		include_once('manage_checkbox_account.php'); 
	}
	else if($action_type=="edit")
	{
		include_once('manage_radio_account.php'); 
	}
	echo'<br>
		<center>		
			<input type="button" value="Enter" onclick="javascript:validate_account('.$file_name.','.$action_type.');">&nbsp;			
		</center>
	</form>';
?>