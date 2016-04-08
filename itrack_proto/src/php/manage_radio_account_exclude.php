<?php

	$ColumnNo;	$RowNo;	$MaxColumnNo;	$MaxColumnNo=0;	$ColumnNo=0;	$RowNo=0;
        $self_account_id=$_POST['common_id'];
	echo "<br>
  <table width=70%>
			<tr>
				<td>	
  <div style='height:370px;overflow:auto'>	
			<table border=1 cellspacing=0 cellpadding=0 class='module_tree_hierarchy' align='center' rules='all'>";
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
				radio_account_hierarchy_exclude($root,$self_account_id);	
		echo"</table>
  </div>
  </td>
  </tr>
  </table>";
?>
