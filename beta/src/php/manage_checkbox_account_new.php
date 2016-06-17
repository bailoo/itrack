<?php
	include_once('Hierarchy.php');		include_once('util_session_variable.php');	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];	$LocalAccount=$_POST['LocalAccount'];    $post_local_account=$_POST['accounts_local'];	
	
	$CheckStatus = $_POST['CheckStatus'];	$action_no1=$_POST['action_no'];
	if($action_no1=="second")
	{
		echo "portal_vehicle_information##";
	}
	//echo "acc=".$post_local_account;
	$account_status1=$_POST['account_status_1'];	$account_status2=explode(":",$account_status1);		$status_size=sizeof($account_status2);	
	//echo"account_status=".$account_status1."<br>";
	$ck_unck_size=0;
	for($i=0;$i<($status_size-1);$i++)
	{
		$account_status3=explode(",",$account_status2[$i]);			
		$account_status[$ck_unck_size][0]=$account_status3[0];		$account_status[$ck_unck_size][1]=$account_status3[1];
		//echo"account_status=".$account_status[$ck_unck_size][0]."<br>";
		$ck_unck_size++;	
	}
	VerifyAccountStatus($LocalAccount);
	
	$ColumnNo;		$RowNo;		$MaxColumnNo;		$MaxColumnNo=0; 	$ColumnNo=0;	$RowNo=0;
	
	echo "<br>
		<table width='90%' align='center'>
			<tr>
				<td>
					<div style='height:250px;overflow:auto;'>
						<!--<table border=1 cellspacing=0 cellpadding=0 class='module_tree_hierarchy' align='center' rules='all'>-->
                                                <table cellspacing=0 cellpadding=0 class='table-bordered table-hover module_tree_hierarchy'>";
							account_column_count($root);
							echo"<thead class='alert-warning'><tr>";
									for($i=0;$i<$MaxColumnNo;$i++)
									{
										echo"<th>&nbsp;".'Level'.$i."</th>";
									}
							echo"</tr></thead>";
							$ColumnNo=0;
							$RowNo=0;
							$group_cnt=0;
							checkbox_account_hierarchy($root);
					echo'</table>
					</div><br>
				</td>
			</tr>
		</table>';
	
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
				//echo "account_id_local1=".$account_status[$i][0]."<br>";
				//echo "account_id_local2=".$account_id_local."<br>";
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
						&nbsp;<INPUT TYPE='checkbox' name='manage_id[]' VALUE='$account_id_local' checked onclick='javascript:manage_checked_account_1(this);'><a href='tree_account_detail.php?account_id_local=$account_id_local'>".$account_name."</a>
					</td>";		
		}
		else
		{	
			$account_status_string=$account_status_string.$account_id_local.",0".":";
		echo"<td>&nbsp;                
				<INPUT TYPE='checkbox' name='manage_id[]' VALUE='$account_id_local' onclick='javascript:manage_checked_account_1(this);'><!--<a href='tree_account_detail.php?account_id_local=$account_id_local'>-->".$account_name."<!--</a>-->
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
	
	 echo"<input type='hidden' name='account_status' id='account_status' value='$account_status_string'>";

	if($action_no1=="second")
	{
		echo "##";
		//echo "accs=".$post_local_account;
		$post_local_account1=explode(",",$post_local_account);
		$acc_size=sizeof($post_local_account1);
		for($i=0;$i<$acc_size;$i++)
		{
			if($i==($acc_size-1))
			{
				$account_id_second=$post_local_account1[$i];
			}
		}
		
		$query="SELECT user_type_id from account_feature where account_id='$account_id_second'";
		//echo "query=".$query."<br>";
		$result=mysql_query($query);
		$row=mysql_fetch_row($result);
		//echo "row=".$row[0]."<br>";
		$query="SELECT user_type_id,user_type_name from user_type WHERE user_type_id in($row[0]) and status=1";
		//echo "query=".$query."<br>";
		$result=mysql_query($query);
		 $i=0;
		 $msg_1="";
		 if($result!=null)
  		{
  			while($row=mysql_fetch_object($result))
  			{
				$user_type_id=$row->user_type_id;
  				$user_type_name=$row->user_type_name;	
				//echo "user_type_id=".$user_type_id."user_type_name=".$user_type_name;
				if($i==0)
				{	$msg_1=$msg_1.$user_type_id.":".$user_type_name;	}
				else
				{	$msg_1=$msg_1.",".$user_type_id.":".$user_type_name;	}
				$i++;         
  		   } 
		}
		 echo $msg_1;
	}
	
	
?>