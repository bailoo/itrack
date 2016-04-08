<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include("user_type_setting.php");
	
	$root=$_SESSION['root'];  
	$js_function_name="manage_select_by_entity";	
	echo "deassign##"; 
	include_once('tree_hierarchy_information.php');
	$DEBUG=0;
	$common_id1 = $account_id;	
	$admin_id1 = getAdminId($account_id,$DbConnection);

	
	echo"<table width='70%' align='center'>
			<tr>
			<td><br>
			<fieldset class='assignment_manage_fieldset'>
			<legend>
			<strong>Accounts</strong>
			</legend>
			<div style='height:350px;overflow:auto'>";
		
			include_once('manage_radio_group_account_transporter_default_chilling.php');
			
			echo"</div>
			</fieldset>
			
			</td>
			</tr>
			</table>";
	
			
	echo '<br><br><input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_default_chilling_plant(manage1,\'deassign\')" value="DeAssign">';
	echo"</form>";
?>
  
