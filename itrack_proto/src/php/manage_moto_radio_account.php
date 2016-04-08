<?php
	$ColumnNo;
	$RowNo;
	$MaxColumnNo;

	$MaxColumnNo=0;
	$ColumnNo=0;
	$RowNo=0;
	echo "<br>
	<div style='height:230px;overflow:auto'>
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
			vehicle_radio_account_hierarchy($root);
	echo"</table></div>";

?>