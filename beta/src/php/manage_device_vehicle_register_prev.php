<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];  
	$js_function_name="manage_select_by_entity";
	$file_name="src/php/manage_unassigned_vehicle_option.php";
	echo "register##";
	if($account_id==1 || $account_id==4)
	{
		$height="350px";
	}
	else
	{
		$height="auto";
	}
	
	include_once('tree_hierarchy_information.php');
echo"<table width='90%' align='center'>
		<tr>
			<td><br>
				<fieldset class='assignment_manage_fieldset'>
				<legend>
					<strong>User Names</strong>
				</legend>
				<div style='350px;overflow:auto'>";
					include_once('manage_radio_account.php');
				echo"</div>
				</fieldset>
			</td>
		</tr>
	</table>";

	echo'<br>
	<center>
	<table <table border=0 cellspacing=0 cellpadding=0 class="module_left_menu">
		<tr>
			<td>
				<input type="radio" name="vehicle_display_option" value="all" onclick="javascript:temp()">&nbsp;All
			</td>
			<td>
				<input type="radio" name="vehicle_display_option" value="vehicle_type" onclick="javascript:'.$js_function_name.'(\''.$file_name.'\',\'vehicle_type\')">&nbsp;Select By Type
			</td>
			<td>
				<input type="radio" name="vehicle_display_option" value="vehicle_tag" onclick="javascript:'.$js_function_name.'(\''.$file_name.'\',\'vehicle_tag\')">&nbsp;Select By Tag
			</td>
		</tr>
	</table>
	<div align="center" id="portal_vehicle_information" style="display:none;"></div>
			
		<br>
		<input type="button" class="btn btn-default" value="Enter" onclick="javascript:manage_option_vehicle_prev(\'src/php/manage_device_vehicle_register.php\');">&nbsp;			
	</center>';
	include_once('manage_loading_message.php');
?>
  