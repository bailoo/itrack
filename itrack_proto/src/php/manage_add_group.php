<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
	echo "add##"; 	
	include_once('tree_hierarchy_information.php');
	echo"<table width='70%'>
			<tr>
				<td>
					<div style='height:295px;overflow:auto'>";
						include_once('manage_radio_account.php');		
				echo"</div>
				</td>
			</tr>
		</table>";
	
	echo'
	<center>  
		<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
			<tr>
				<td>Group Name</td>
				<td>&nbsp;:&nbsp;</td>
				<td> <input type="text" name="group_name" id="group_name"> </td>
			</tr>	
			<tr>
				<td>Remark</td>
				<td>&nbsp;:&nbsp;</td>
				<td><textarea name="remark" id="remark"></textarea></td>
			</tr>    								
			<tr>                    									
				<td align="center"  colspan="3">
				<div style="height:10px"></div>
				<input type="button" Onclick="javascript:action_manage_group(\'add\')" value="Enter" id="enter_button">
				&nbsp;
				<input type="reset" value="Clear">
				</td>
			</tr>
		</table>';	
	echo'</center>';
?>
	
