<?php
	$MaxColumnNo=0; 
	$ColumnNo=0;
	$RowNo=0;
	$count=0;
	echo "<br>
	<!--<table border=1 cellspacing=0 cellpadding=0 class='module_left_menu' align='center' width='70%'>-->
         <table cellspacing=0 cellpadding=0 class='table-bordered table-hover module_tree_hierarchy' style='width:90%'>";
	FindColumnCnt($root);
	echo"<thead class='alert-warning'><tr>";
			for($i=0;$i<$MaxColumnNo;$i++)
			{
				echo"<th>&nbsp;".'Level'.$i."</th>";
			}
				echo"<th>&nbsp; Group Name</th>";
	echo"</tr></thead>";

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