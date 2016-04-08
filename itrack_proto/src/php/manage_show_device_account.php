<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];	  
	echo"portal_vehicle_information##";
	
	$LocalAccount=$_POST['LocalAccount'];       
	$CheckStatus = $_POST['CheckStatus'];
	$account_status1=$_POST['account_status_1'];	
	$account_status2=explode(":",$account_status1);
	$status_size=sizeof($account_status2);	
	$manage_id1=$_POST['manage_id']; ////// it is either device_imei_no or vehicle_id
	
	$common_id1=$_POST['common_id'];

	$action_name1=$_POST['action_name'];
	if($action_name1=="device")
	{	
		$query="SELECT user_account_id from device_assignment where device_imei_no=$manage_id1 and status=1";
	}
	else if($action_name1=="vehicle")
	{
		$query="SELECT account_id from vehicle_grouping where vehicle_id=$manage_id1 and status=1";
	}
	$result=mysql_query($query,$DbConnection);
	$acc_size=0;
	$ck_unck_size=0;
	$account_status_string="";
	while($row=mysql_fetch_object($result))
	{
		$accountid[$acc_size]=$row->user_account_id;
		$acc_size++;
	}	
	
	if($action_no1=="second")
	{	
		for($i=0;$i<($status_size-1);$i++)
		{
			$account_status3=explode(",",$account_status2[$i]);			
			$account_status[$ck_unck_size][0]=$account_status3[0];
			$account_status[$ck_unck_size][1]=$account_status3[1];
			$ck_unck_size++;	
		}
		VerifyAccountStatus($LocalAccount);
	}
	
	$ColumnNo;
	$RowNo;
	$MaxColumnNo;
	$MaxColumnNo=0; 
	$ColumnNo=0;
	$RowNo=0;
	if($action_no1!="second")
	{
		set_account_status($root);
	}
	
	echo "<br>
		<table border=1 cellspacing=0 cellpadding=0 class='module_left_menu' align='center' width='70%'>";
			account_column_count($root);
			echo"<tr>";
					for($i=0;$i<$MaxColumnNo;$i++)
					{
						echo"<td>&nbsp;".'Level'.$i."</td>";
					}
			echo"</tr>";
			$ColumnNo=0;
			$RowNo=0;
			$group_cnt=0;
			checkbox_account_hierarchy($root);
	echo'</table>
				<input type="button" Onclick="javascript:action_manage_device(\'assignment\')" value="Enter" id="enter_button">';
	
	function VerifyAccountStatus($AccountID)
	{
		global $CheckStatus;
		global $root;
		$CurrentTree = FindNode($root,$AccountID);
		//echo "check status=".$CheckStatus;
		if($CheckStatus=="true")
		{
			//echo "true1";
			ChangeStatusParent($CurrentTree,$CheckStatus);
		}
		else
		{
			//echo "false1";
			ChangeStatusChild($CurrentTree,$CheckStatus);
		}
	}
	
	function FindNode($AccountNode,$AccountID)
	{
		$account_id_local=$AccountNode->data->AccountID;		
		if($account_id_local==$AccountID)
		{
			return $AccountNode;
		}
		
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{     
			$tmp = FindNode($AccountNode->child[$i],$AccountID);
			if($tmp!=null)
			{
				return $tmp;
			}
		} 
		return null;	
	}
	
	function ChangeStatusParent($AccountNode,$status)
	{
		global $account_status;
		global $ck_unck_size;
		if($AccountNode->parents!=null)
		{
			$account_id_local=$AccountNode->parents->data->AccountID;
			for($i=0;$i<($ck_unck_size);$i++)
			{
				//echo "account_id_local1=".$account_status[$i][0]."account_id_local2=".$account_id_local."<br>";
				if($account_status[$i][0]==$account_id_local)
				{
					if($status==true)
					{
						$account_status[$i][0]=$account_id_local;
						$account_status[$i][1]=1;
						
					}
					else
					{
						$account_status[$i][0]=$account_id_local;
						$account_status[$i][1]=0;
					}
				}
			}
			ChangeStatusParent($AccountNode->parents,$status);
		}
		return;
	}
	
	function ChangeStatusChild($AccountNode,$status)
	{
		global $account_status;
		global $ck_unck_size;
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{ 
			$account_id_local = $AccountNode->child[$i]->data->AccountID;
			for($j=0;$j<($ck_unck_size);$j++)
			{
				if($account_status[$j][0]==$account_id_local)
				{
					if($status=="true")
					{
						$account_status[$j][0]=$account_id_local;
						$account_status[$j][1]=1;
					}
					else
					{
						$account_status[$j][0]=$account_id_local;
						$account_status[$j][1]=0;
					}
				}
			}
			$tmp = ChangeStatusChild($AccountNode->child[$i],$status);
		}
		return;
	}
	
	function set_account_status($AccountNode)
	{
		global $accountid;
		global $acc_size;
		global $account_status;
		global $ck_unck_size;
		$flag='OFF';
		
		$account_id_local=$AccountNode->data->AccountID;		
		for($i=0;$i<$acc_size;$i++)
		{
			if($accountid[$i]==$account_id_local)
			{
				$flag='ON';
			}
		}
		if($flag=='ON')
		{
			$account_status[$ck_unck_size][0]=$account_id_local;
			$account_status[$ck_unck_size][1]=1;
			$ck_unck_size++;
		}
		else
		{
			$account_status[$ck_unck_size][0]=$account_id_local;
			$account_status[$ck_unck_size][1]=0;
			$ck_unck_size++;
		}
		
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{     
			set_account_status($AccountNode->child[$i]);
		}  
	}
	
	function checkbox_account_hierarchy($AccountNode)
	{
		global $ColumnNo;
		global $RowNo;
		global $count;
		global $MaxColumnNo;
		global $accountid;
		global $acc_size;	
		global $account_status;
		global $ck_unck_size;		
		global $account_status_string;		
			 
		$flag='OFF';
		$assign_flag='OFF';		

		$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
		$account_id_local=$AccountNode->data->AccountID;
		$group_id_local=$AccountNode->data->AccountGroupID;
		$account_name=$AccountNode->data->AccountName;
		$ChildCount=$AccountNode->ChildCnt;		
 
		echo"<tr>";
		for($k=0;$k<$ColumnNo;$k++)
		{
			echo"<td>&nbsp;".''."</td>";
		}
		
		for($i=0;$i<$ck_unck_size;$i++)
		{			
			if($account_status[$i][0]==$account_id_local)
			{
				$flag=$account_status[$i][1];
			}
		}
		if($flag=='1')
		{
			$account_status_string=$account_status_string.$account_id_local.',1'.':';
			echo"<td>
						&nbsp;<INPUT TYPE='checkbox' name='manage_id[]' VALUE='$account_id_local' checked onclick='javascript:manage_checked_account(this);'><a href='tree_account_detail.php?account_id_local=$account_id_local'>".$account_name."</a>
					</td>";		
		}
		else
		{	
			$account_status_string=$account_status_string.$account_id_local.",0".":";
		echo"<td>&nbsp;                
				<INPUT TYPE='checkbox' name='manage_id[]' VALUE='$account_id_local' onclick='javascript:manage_checked_account(this);'><a href='tree_account_detail.php?account_id_local=$account_id_local'>".$account_name."</a>
			</td>";
		}
		for($l=($k+1);$l<$MaxColumnNo;$l++)
		{
			echo"<td>&nbsp;".''."</td>";
		}
		echo"</tr>";

		$ColumnNo++;
		$RowNo++;

		for($i=0;$i<$ChildCount;$i++)
		{     
			checkbox_account_hierarchy($AccountNode->child[$i]);
		}  
		$ColumnNo--;
	}
	
	 echo"<input type='hidden' name='account_status' id='account_status' value='$account_status_string'>";

	
	function account_column_count($AccountNode)
	{ 
		global $ColumnNo;
		global $RowNo;
		global $MaxColumnNo;
		global $count;  
		$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;
		$account_name=$AccountNode->data->AccountName;
		$ChildCount=$AccountNode->ChildCnt;

		$ColumnNo++;
		$RowNo++;

		if($MaxColumnNo<$ColumnNo)
		{
			$MaxColumnNo = $ColumnNo;
		}
		for($i=0;$i<$ChildCount;$i++)
		{
			account_column_count($AccountNode->child[$i]);
		}
		$ColumnNo--;
	}
?>