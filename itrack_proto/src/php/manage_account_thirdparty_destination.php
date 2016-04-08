<?php
	
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include("coreDb.php");  
	$root=$_SESSION['root'];
	
	include_once('tree_hierarchy_information.php');
	echo "destination_account##";
	echo "
	<fieldset class='assignment_manage_fieldset'>
		<legend>
			<strong>Accounts Destination &nbsp;&nbsp;<input type=checkbox id='all' name='all' onclick=select_all_destination_account(this.form) >Select All</strong>
		</legend>
		<table width=100%>
		<tr>
		";
			$third_party_account_id=array();
			$third_party_account_id=getThirdPartyIDThirdPartyAccountAssignment($source_account_id,'1',$DbConnection);
			/*
			$querydesaid="SELECT third_party_account_id from third_party_account_assignment WHERE admin_account_id='$source_account_id' and status=1";
			$resultdesaid=mysql_query($querydesaid,$DbConnection);
			while($row=mysql_fetch_object($resultdesaid))
			{
				$third_party_account_id[]=$row->third_party_account_id;
			}
			*/
			//print_r( $third_party_account_id);
			$counter=0;
			checkbox_thirdparty_account_hierarchy($root,$third_party_account_id,$source_account_id);							 
		echo"
		</tr>
		</table>
	</fieldset>
	
	";
	echo'<br><center>
	<input type="button" value="Update"  id="enter_button" onclick="javascript:return action_manage_account_thirdparty(\'src/php/action_manage_account_thirdparty.php\');">&nbsp;        
	';
	function checkbox_thirdparty_account_hierarchy($AccountNode,$third_party_account_id,$source_account_id)
	{
		global $DbConnection;
		global $counter;
		$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
		$account_id_local=$AccountNode->data->AccountID;
		$group_id_local=$AccountNode->data->AccountGroupID;
		//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
		$account_name=$AccountNode->data->AccountName;
		$ChildCount=$AccountNode->ChildCnt;		
		if($account_id_local!='1' && $account_id_local!=$source_account_id)
		{
		
			/*
			$queryuid="SELECT user_id,group_id from account WHERE account_id='$account_id_local' and status=1";
			//echo "<br>".$queryuid;
			$resultuid=mysql_query($queryuid,$DbConnection);
			$rowuid=mysql_fetch_row($resultuid);
			$user_id=$rowuid[0];
			$group_id=$rowuid[1];*/
			$ugid_a=uidgidAccount($account_id_local,$DbConnection);			
			foreach($ugid_a as $ugid)
			{
				$user_id=$ugid['user_id'];
				$group_id=$ugid['group_id'];
			
			
				if($counter==5)
				{
					echo"</tr>";
					$counter=0;
				}
				if (in_array($account_id_local, $third_party_account_id))
				{	
					echo"  
						<td><input type=checkbox id=destination_account[] value='$account_id_local' checked>".$account_name."($user_id)$group_id</td>
					";
				}
				else
				{
					echo"  
						<td><input type=checkbox id=destination_account[] value='$account_id_local'>".$group_id." ".$account_name."($user_id)</td>
					";
				}
			}	
		}
		$counter++;
		for($i=0;$i<$ChildCount;$i++)
		{     
			checkbox_thirdparty_account_hierarchy($AccountNode->child[$i],$third_party_account_id,$source_account_id);
		}  
		
	}
?>