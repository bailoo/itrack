<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include("user_type_setting.php");
	
	$root=$_SESSION['root'];  
	$js_function_name="manage_select_by_entity";	
	echo "assign##"; 
	
	$DEBUG=0;
	$common_id1 = $account_id;
	
	

	include_once('tree_hierarchy_information.php');
	
	//===Getting Chilling Plant
	$final_chillplant_list=array();
    $final_chillplant_name_list=array();
	$final_chillplant_list[0]="";
    $final_chillplant_name_list[0]="";
	
	
	//chilling plant
	/*$query_chillplant = "SELECT customer_no,station_name FROM station WHERE type=2 AND user_account_id='$account_id' AND status=1";
	$result_chillquery = mysql_query($query_chillplant,$DbConnection);
	while($rowchill=mysql_fetch_object($result_chillquery))
	{
		//echo $row->customer_no;
		$final_chillplant_list[]=$rowchill->customer_no;
		$final_chillplant_name_list[]=$rowchill->station_name;
	}*/
        $datarowchill=getCustomerNoStationNext($account_id,$DbConnection);
	foreach($datarowchill as $rowchill)
	{
		//echo $row->customer_no;
		$final_chillplant_list[]=$rowchill['final_chillplant_list'];
		$final_chillplant_name_list[]=$rowchill['final_chillplant_name_list'];
	}
	
		echo '<br><br><br>';
		$flag_substation = 0;
		$flag_raw_milk = 0;
		
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
                /*
		$query_parent_admin = "SELECT admin_id FROM account_detail WHERE account_id='$account_id'";
		$result_admin = mysql_query($query_parent_admin,$DbConnection);
		$row_admin = mysql_fetch_object($result_admin);
		$admin_id1 = $row_admin->admin_id;*/
                $admin_id1 =getAdminId($accountId,$DbConnection);
		
		
                if($flag_raw_milk ) //else if($flag_raw_milk || $flag_hindalco_invoice)
		{
			//$query = "SELECT account_id,user_id FROM account WHERE user_type='raw_milk' AND status=1";

			//$query = "SELECT account.account_id,account.user_id FROM account,account_detail WHERE account.user_type='raw_milk' AND account.status=1 and account.account_id=account_detail.account_id AND account_detail.account_admin_id='$admin_id1'";
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
			<tr>
				<td align=center>
					<fieldset class='assignment_manage_fieldset'>
						<legend>
						<strong>Select Chilling Plant</strong>
						</legend>
					<select name='chillplant' id='chillplant' style='width:370px;'>
					";
							$i=0;
							foreach($final_chillplant_list as $chillplantlist){
									if($i==0){
										echo"<option value='' >Select</option>";
									}
									else{
									echo"<option value=".$chillplantlist." >".$final_chillplant_name_list[$i]."(".$chillplantlist.")</option>";
										}
								$i++;
							}
						echo"
					</select>
				</fieldset>
				</td>
			</tr>
			</table>";
			echo '<br><br><input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_default_chilling_plant(manage1,\'assign\')" value="Assign">';
		}
		
		echo' <div align="center" id="portal_vehicle_information" style="display:none;"></div><br>			
	</center>';

		
	
			
	include_once('manage_loading_message.php');
?>
  
