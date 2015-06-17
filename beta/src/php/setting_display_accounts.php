<?php
	$ColumnNo;
	$RowNo;
	$MaxColumnNo;
	$title1=$_POST['title'];
	echo "<div class='report_div_height'></div>
			<center><div class='report_title'><b>".$title1."</b></div></center>	
			<div class='report_div_height'></div>
		<div style='height:450px;overflow:auto'>
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
			radio_account_hierarchy($root);
	echo"</table>
	</div>";
?>