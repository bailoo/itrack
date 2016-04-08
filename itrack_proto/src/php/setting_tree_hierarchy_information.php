<?php 
	function get_parent_account_id($AccountNode,$function_account_id)
	{
		$parent_account_id=null;		
		if($AccountNode->data->AccountID==$function_account_id)
		{	
			$parent_account_id=$AccountNode->parents->data->AccountID;
			return $parent_account_id;					
		}
		
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{     
			$parent_account_id=get_parent_account_id($AccountNode->child[$i],$function_account_id);
			if($parent_account_id!=null)
			{
				return $parent_account_id;
			}
		}
	}
	
	function find_child($AccountNode,$function_account_id)
	{
		$tmp_child = null;
		if($AccountNode->data->AccountID==$function_account_id)
		{
			if($AccountNode->ChildCnt==0)
			{
				$tmp_child="No Child Found";
			}
			else
			{
				$tmp_child="Child Found";
			}			
			return $tmp_child;	
		}
		
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{     
			$tmp_child = find_child($AccountNode->child[$i],$function_account_id);
			if($tmp_child!=null)
			{
				return $tmp_child;
			}
		}	
	}
	
	function printgroups($root,$final_group_array,$account_id1,$group_id1)
	{
		if($group_id1!=null)
		{
			for($i=0;$i<sizeof($final_group_array);$i++)
			{
				if($final_group_array[$i][0]==$group_id1)
				{
					//printf final_group_array[i][1]
					echo"<option value='$final_group_array[$i][0]'>".$final_group_array[$i][1]."</option>	";	
				}
			}
		}
		else
		{
			echo"<option value=''>No Group</option>";
		}
		
		for($i=0;$i<sizeof($final_group_array);$i++)
		{
			if($final_group_array[$i][2]==$account_id1)
			{
				//printf final_group_array[i][1]
				echo"<option value='$final_group_array[$i][0]'>".$final_group_array[$i][1]."</option>	";
			}
		}
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

	function get_account_hierarchy($AccountNode)
	{
		global $ColumnNo;
		global $RowNo;
		global $count;
		global $MaxColumnNo;

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
		echo"<td>&nbsp;                
			<INPUT TYPE='radio' name='setting_user' VALUE='$account_id_local,$group_id_local'><a href='tree_account_detail.php?account_id_local=$account_id_local'>".$account_name."</a>
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
			get_account_hierarchy($AccountNode->child[$i]);
		}  
		$ColumnNo--;
	}
?>