<?php
	$ColumnNo;	$RowNo;	$MaxColumnNo;	$MaxColumnNo=0; $ColumnNo=0;	$RowNo=0;
	echo "<br>
		<table width=70%>
			<tr>
				<td>				
					<div style='height:300px;overflow:auto'>
						<table border=1 cellspacing=0 cellpadding=0 class='module_tree_hierarchy' align='center' rules='all'>";
							account_column_count($root);
							echo"<tr>";
									for($i=0;$i<$MaxColumnNo;$i++)
									{
										echo"<td>&nbsp;".'Level'.$i."</td>";
									}
							echo"</tr>";

							$ColumnNo=0;	$RowNo=0;	$group_cnt=0;
							checkbox_account_hierarchy($root);
					echo"</table>
					</div>
				</td>
			</tr>
		</table>";
?>