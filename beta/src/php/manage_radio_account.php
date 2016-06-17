<?php
	$ColumnNo;	$RowNo;	$MaxColumnNo;	$MaxColumnNo=0;	$ColumnNo=0;	$RowNo=0;
	echo "<br>
  <table width=100%>
			<tr>
				<td>	
  <div style='height:370px;overflow:auto'>	
			<!--<table border=1 cellspacing=0 cellpadding=0 class='module_tree_hierarchy' align='center' rules='all'>-->
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
				radio_account_hierarchy($root);	
		echo"</table>
  </div>
  </td>
  </tr>
  </table>";
?>