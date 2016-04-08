<?php 
	//include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//$root=$_SESSION['root'];  
	$DEBUG=0;
	$parameter_type="student";
	//echo "add##";
	$common_id1=$_POST['common_id'];
	
	
	$local_account_ids=explode(",",$common_id1);
	$account_size=sizeof($local_account_ids);
	$selected_account_id=$local_account_ids[$account_size-1];
	
	//echo'selected_account_id'.$selected_account_id;
	$group_id="";
	 $query="SELECT group_id from account where account_id='$selected_account_id' AND status='1'";
		//echo "query=".$query."<br>";
		$result=mysql_query($query,$DbConnection);
		$row_result=mysql_num_rows($result);		
		if($row_result!=null)
		{
		$row=mysql_fetch_object($result);
		$group_id=$row->group_id;
		}
		
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	echo'<input type="hidden" id="group_id_hidden" value='.$group_id.'>';
	echo'<input type="hidden" id="selected_account_id_hidden" value='.$selected_account_id.'>';
	//include_once('tree_hierarchy_information.php');
	//include_once('manage_radio_account.php'); 
	
 ?>  
	<br>
	<table width="100%">
	<tr>
	<td align="center">
	<fieldset class="manage_fieldset">
	<legend><strong>Add Driver<strong></legend>
	<table border="0" class="manage_interface">
	
		<tr>
			<td>Driver Name</td>
			<td> :</td>
			<td><input type="text" name="add_driver_name" id="add_driver_name" ></td>
		</tr>
		<tr>
			<td>DL Number (Heavy Vehicle)</td>
			<td> :</td>
			<td><input type="text" name="add_dlnumber" id="add_dlnumber" ></td>
		</tr>
		<tr>
			<td>DOB</td>
			<td> :</td>
			<td><input type="text" name="add_dob" id="add_dob" ></td>
		</tr>
		<tr>
			<td>Address</td>
			<td> :</td>
			<td><input type="text" name="add_address" id="add_address" ></td>
		</tr>

	
	<tr>
		<td colspan="3" align="center">
			<input type="button" id="enter_button" value="Save" onclick="javascript:return action_manage_busdriver('add')"/>&nbsp;<input type="reset" value="Clear" />
		</td>
	</tr>
	</table>
	
	</fieldset>
	</td>
</tr>
</table>
	<?php
		include_once('availability_message_div.php');
	?>

  