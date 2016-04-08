<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];  
	$js_function_name="report_select_by_entity";
	$file_name="src/php/dialog_search.php";
  $filename1=$_POST['filename']; 
  //echo "filename1=".$filename1; 
  echo'<form name="report1">';	
  include_once('tree_hierarchy_information.php');
	include_once('report_radio_account.php');

	echo'<br>
	<center>			
		<br>
		<input type="button" value="Enter" onclick="javascript:report_search_vehicle(\''.$filename1.'\');">&nbsp;			
	</form>
  </center>';
?>