<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
	echo "add##"; 	
	include_once('tree_hierarchy_information.php');
	echo"<table width='90%'>
			<tr>
				<td>
					<div style='height:;overflow:auto'>";
						include_once('manage_radio_account.php');		
				echo"</div>
				</td>
			</tr>
		</table>";
	
	echo'
	<center>  
		<table border="0" align=center class="" cellspacing="1" cellpadding="1">
			<tr>
				<td> <label for="gn">Group Name</label></td>
				<td>&nbsp;:&nbsp;</td>
				<td> <input type="text" name="group_name" id="group_name" class="form-control"> </td>
			</tr>	
			<tr>
				<td><label for="rem">Remark</label></td>
				<td>&nbsp;:&nbsp;</td>
				<td><textarea name="remark" id="remark" class="form-control"></textarea></td>
			</tr>    								
			<tr>    
                                <td align="center"  colspan="2"></td>
				<td align="center"  colspan="1">
				<div style="height:10px"></div>
				<input type="button" class="btn btn-default" Onclick="javascript:action_manage_group(\'add\')" value="Enter" id="enter_button">
				&nbsp;
				<input type="reset" class="btn btn-default" value="Clear">
				</td>
			</tr>
		</table>';	
	echo'</center>';
?>
	
