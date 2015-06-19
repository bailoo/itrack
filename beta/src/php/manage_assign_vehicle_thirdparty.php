<?php
	//include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include("coreDb.php");  
	//$root=$_SESSION['root'];
	
	//print_r($root);
	$thp=getTpaUidNameAr($account_id,$DbConnection);
	//print_r($thp);
		$selectionbox="
		<select name='destination_account_id' id='destination_account_id' onchange='show_source_vehicle(this.value);'>
		<option value='select'>Select</option>";
		if(count($thp)>0)
		{
			foreach($thp as $row)
			{
				$tid=$row['tid'];
				$tuser_id=$row['tuser_id'];
				$tname=$row['tname'];
				 
				$selectionbox.="<option value='$tid'>$tname($tuser_id)</option>";
			}
		}
		$selectionbox.="</select>";
	
	/*
	$querydesaid="SELECT third_party_account_assignment.third_party_account_id,account.user_id,account_detail.name from third_party_account_assignment,account,account_detail 
	WHERE third_party_account_assignment.admin_account_id='$account_id' and third_party_account_assignment.status=1 
	 AND account.account_id=third_party_account_assignment.third_party_account_id AND account_detail.account_id=account.account_id and account.status=1";
	 //echo $querydesaid;
	$resultdesaid=mysql_query($querydesaid,$DbConnection);
	while($row=mysql_fetch_object($resultdesaid))
	{		
		$tid=$row->third_party_account_id;
		$tuser_id=$row->user_id;
		$tname=$row->name;
		$selectionbox.="<option value='$tid'>$tname($tuser_id)</option>";
	}
	$selectionbox.="</select>";
	*/
		echo"<form name='manage1'>
		<table width='70%' align='center'>
			<tr>
				<td align=center>
					<fieldset class='assignment_manage_fieldset'>
						<legend>
							<strong>Accounts Destination</strong>
						</legend>
						
						";							
							echo $selectionbox;							 
					echo"
					</fieldset>
				</td>
			</tr>
			<tr>
				<td>
					<div id='source_vehicle' />
				</td>
			</tr>
			
		</table>";
	
			include_once('manage_loading_message.php');
	echo'</center>
	</form>';
?>