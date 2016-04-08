<?php
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
	function vehicle_radio_account_hierarchy($AccountNode)
	{
		global $ColumnNo;
		global $RowNo;
		global $count;
		global $MaxColumnNo;

		$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
		$account_id_local=$AccountNode->data->AccountID;
		$group_id_local=$AccountNode->data->AccountGroupID;
		//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
		$account_name=$AccountNode->data->AccountName;
		$ChildCount=$AccountNode->ChildCnt;		
 
		echo"<tr>";
		for($k=0;$k<$ColumnNo;$k++)
		{
			echo"<td>&nbsp;".''."</td>";
		}
		echo"<td>&nbsp;                
			<INPUT TYPE='radio' name='manage_id' VALUE='$account_id_local' '><!--<a href='tree_account_detail.php?account_id_local=$account_id_local'>-->".$account_name."<!--</a>-->
		</td>";
		for($l=($k+1);$l<$MaxColumnNo;$l++)
		{
			echo"<td>&nbsp;".''."</td>";
		}
		echo"</tr>";

		$ColumnNo++;
		$RowNo++;

		for($i=0;$i<$ChildCount;$i++)
		{     
			vehicle_radio_account_hierarchy($AccountNode->child[$i]);
		}  
		$ColumnNo--;
	}	
?>