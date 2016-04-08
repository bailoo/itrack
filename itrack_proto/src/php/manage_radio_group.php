<?php
	$MaxColumnNo=0; 
	$ColumnNo=0;
	$RowNo=0;
	$count=0;
	echo "<br>
	<table border=1 cellspacing=0 cellpadding=0 class='module_left_menu' align='center' width='70%'>";
	FindColumnCnt($root);
	echo"<tr>";
			for($i=0;$i<$MaxColumnNo;$i++)
			{
				echo"<td>&nbsp;".'Level'.$i."</td>";
			}
				echo"<td>&nbsp; Group Name</td>";
	echo"</tr>";

	$ColumnNo=0;
	$RowNo=0;
		
	function FindColumnCnt($AccountNode)
	{ 
		global $ColumnNo;
		global $RowNo;
		global $MaxColumnNo;
		global $count; 
		
		$ChildCount=$AccountNode->ChildCnt;
		$ColumnNo++;
		$RowNo++;
		if($MaxColumnNo<$ColumnNo)
		{
			$MaxColumnNo = $ColumnNo;
		}
		for($i=0;$i<$ChildCount;$i++)
		{
			FindColumnCnt($AccountNode->child[$i]);
		}
		$ColumnNo--;	
	}
	
	for($i=0;$i<sizeof($final_group_array);$i++)
	{
		$count=0;
		$account_node_count=0;	
		$group_id_local=$final_group_array[$i][0];
		$group_name_local=$final_group_array[$i][1];
		$parent_account_id_local=$final_group_array[$i][2];
		printaccounthierarchy($root,$group_id_local,$group_name_local,$parent_account_id_local);			
	}
		echo"</table>";

?>