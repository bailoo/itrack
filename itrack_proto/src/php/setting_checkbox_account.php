<?php
	$ColumnNo;
	$RowNo;
	$MaxColumnNo;

	$MaxColumnNo=0; 
	$ColumnNo=0;
	$RowNo=0;
	echo "<br>
		<table border=1 cellspacing=0 cellpadding=0 class='module_left_menu' align='center' width='70%'>";
			setting_account_column_count($root);
			echo"<tr>";
					for($i=0;$i<$MaxColumnNo;$i++)
					{
						echo"<td>&nbsp;".'Level'.$i."</td>";
					}
			echo"</tr>";

			$ColumnNo=0;
			$RowNo=0;
			$group_cnt=0;
			setting_checkbox_account_hierarchy($root);
	echo"</table>";
	
?>