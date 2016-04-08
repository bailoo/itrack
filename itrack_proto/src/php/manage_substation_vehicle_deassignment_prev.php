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
	//include_once('tree_hierarchy_information.php');
	//include_once('manage_radio_account.php');
	for($k=0;$k<$size_feature_session;$k++)
	{
		if($feature_name_session[$k] == "substation")
		{
			$flag_substation = 1;
		}
		if($feature_name_session[$k] == "raw_milk")
		{
			$flag_raw_milk = 1;
		}
		if($feature_name_session[$k] == "hindalco_invoice")
		{
			$flag_hindalco_invoice = 1;
		}		
	}		

	
	$admin_id1 = getAdminId($account_id,$DbConnection);

	if($flag_substation)
	{
		
		$data = getDetailAllAcIDUIDNext($admin_id1,$DbConnection);		
		echo '<br><div align="center">';
		echo '<strong>Select SubStation User &nbsp;:&nbsp;</strong> <select name="substation_user" id="substation_user" Onchange="javascript:return show_substation_vehicles(manage1,\'deassign\')">';
		echo '<option value="0">Select Plant account</option>';
		foreach($data as $dt)
		{						
			$account_id_sub = $dt['account_id_sub'];
			$user_id_sub = $dt['user_id_sub'];
			echo '<option value="'.$account_id_sub.'">'.$user_id_sub.'</option>';
		}
		echo '</select>';
	}
	else if($flag_raw_milk || $flag_hindalco_invoice)
	{
		
		 //$query ="SELECT account.account_id,account.user_id FROM account,account_detail WHERE account.user_type='raw_milk' AND account.status=1 and account.account_id=account_detail.account_id AND account_detail.account_admin_id='$admin_id1'";
		 echo"<table width='70%' align='center'>
			<tr>
			<td><br>
			<fieldset class='assignment_manage_fieldset'>
			<legend>
			<strong>Accounts</strong>
			</legend>
			<div style='height:350px;overflow:auto'>";
		
			include_once('manage_radio_group_account_transporter.php');
			echo"</div>
			</fieldset>
			
			</td>
			</tr>
			</table>";
		 
	}
	/*	
	$result = mysql_query($query,$DbConnection);		
	echo '<br><div align="center">';
	echo '<strong>Select SubStation User &nbsp;:&nbsp;</strong> <select name="substation_user" id="substation_user" Onchange="javascript:return show_substation_vehicles(manage1,\'deassign\')">';
	echo '<option value="0">Select Plant account</option>';
	while($row = mysql_fetch_object($result))
	{
		$account_id_sub = $row->account_id;
		$user_id_sub = $row->user_id;
		echo '<option value="'.$account_id_sub.'">'.$user_id_sub.'</option>';
	}
	echo '</select>';
	*/	
	//$query="SELECT vehicle_id,vehicle_name from vehicle where vehicle_id IN (SELECT vehicle_id FROM vehicle_grouping WHERE account_id=$account_id and status=1)";
	//$query="SELECT vehicle_id,vehicle_name from vehicle where vehicle_id IN (SELECT vehicle_id FROM vehicle_grouping WHERE account_id=$account_id) and vehicle_id not in(Select vehicle_id from vehicle where status=0)";
//	echo "qyery=".$query;
	
	echo '<br><div id="substation_vehicles"></div>';
			
	include_once('manage_loading_message.php');
	echo"<input type='hidden' id='rawmilkplant_id' name='rawmilkplant_id' /></form>";
?>
  
