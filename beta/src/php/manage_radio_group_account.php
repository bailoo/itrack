<?php
	$ColumnNo;
	$RowNo;
	$MaxColumnNo;

	$MaxColumnNo=0;
	$ColumnNo=0;
	$RowNo=0;
	echo "<br>
	<form name='manage1'>
		<!--<table border=1 cellspacing=0 cellpadding=0 class='module_tree_hierarchy' rules='all'>-->
                <table cellspacing=0 cellpadding=0 class='table-bordered table-hover module_tree_hierarchy'>
                ";
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
			radio_group_account_hierarchy($root);
	echo"</table>";

?>