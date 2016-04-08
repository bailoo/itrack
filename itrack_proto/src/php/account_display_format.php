<?php
	function display_format($account_id_local,$group_id_local,$account_name)
	{
		global $MaxColumnNo;
		global $ColumnNo;
echo"<tr>";
		for($k=0;$k<$ColumnNo;$k++)
		{
			echo"<td>&nbsp;".''."</td>";
		}
		echo"<td>&nbsp;                
			<INPUT TYPE='radio' name='manage_user' VALUE='$account_id_local,$group_id_local'><a href='tree_account_detail.php?account_id_local=$account_id_local'>".$account_name."</a>
		</td>";
		for($l=($k+1);$l<$MaxColumnNo;$l++)
		{
			echo"<td>&nbsp;".''."</td>";
		}
		echo"</tr>";
	}
?>