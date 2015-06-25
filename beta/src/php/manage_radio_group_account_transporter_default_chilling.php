<?php
	$ColumnNo;
	$RowNo;
	$MaxColumnNo;

	$MaxColumnNo=0;
	$ColumnNo=0;
	$RowNo=0;
	echo "<br>
	<form name='manage1'>
		<table border=1 cellspacing=0 cellpadding=0 class='module_tree_hierarchy' rules='all'>";
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
			radio_group_account_hierarchy_transporter_default_chilling($root);
	echo"</table>";

?>