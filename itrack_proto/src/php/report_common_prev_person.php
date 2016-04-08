<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];  
	$js_function_name="report_select_by_entity";
	$file_name="src/php/report_assigned_vehicle_option.php";
  $filename1=$_POST['filename']; 
  //echo "filename1=".$filename1; 
  echo'<form name="report1">';
	
  include_once('tree_hierarchy_information.php');
	include_once('report_radio_account.php');

	echo'
	<center>
	 <!--
  <table <table border=0 cellspacing=0 cellpadding=0 class="module_left_menu">
		<tr>
			<td>
				<input type="radio" name="vehicle_display_option" value="all">&nbsp;All
			</td>
			<td>
				<input type="radio" name="vehicle_display_option" value="vehicle_type" onclick="javascript:'.$js_function_name.'(\''.$file_name.'\',\'vehicle_type\')">&nbsp;Select By Vehicle Type
			</td>
			<td>
				<input type="radio" name="vehicle_display_option" value="vehicle_tag" onclick="javascript:'.$js_function_name.'(\''.$file_name.'\',\'vehicle_tag\')">&nbsp;Select By Vehicle Tag
			</td>
		</tr>
	</table>
	-->
	<div align="center" id="portal_vehicle_information" style="display:none;"></div>
			
		<input type="button" value="Enter" onclick="javascript:report_display_person(\''.$filename1.'\');">&nbsp;			
	</form>
  </center>';
?>