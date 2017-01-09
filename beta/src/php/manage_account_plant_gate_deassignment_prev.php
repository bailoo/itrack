<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include("user_type_setting.php");
	
	$root=$_SESSION['root'];  
	$js_function_name="manage_select_by_entity";	
	echo "deassign##"; 
	
	$DEBUG=0;
	$common_id1 = $account_id;
			
	echo '<br><div align="center">';
	echo '<strong>Select Gate User &nbsp;:&nbsp;</strong> <select name="plant_gate" id="plant_gate" Onchange="javascript:return show_gate_plant(manage1,\'deassign\')"> ';
	echo '<option value="0">Select Plant account</option>';
	
	$dataAU=getAccountIDGateKeeperID($DbConnection);
	foreach($dataAU as $dt) 
	{
		$account_id_sub = $dt['account_id_sub' ];
		$user_id_sub = $dt['user_id_sub' ];
		echo '<option value="'.$account_id_sub.'">'.$user_id_sub.'</option>';
	}
	echo '</select>';
		
	
	
	echo '<br><div id="gate_plant"></div> ';
			
	include_once('manage_loading_message.php');
?>
  