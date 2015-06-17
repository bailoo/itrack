<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];  
	echo "edit##"; 
	include_once('tree_hierarchy_information.php');
	echo"<table width='70%'>
			<tr>
				<td>
					<fieldset class='assignment_manage_fieldset'>
					<legend>
						<strong>User Names</strong>
					</legend>
					<div style='height:350px;overflow:auto'>";
						include_once('manage_radio_account.php');
						echo"</div>
				</td>
			</tr>
		</table>";
	echo'<br>
	<center>		
		<input type="button" value="Enter" onclick="javascript:manage_edit_prev(\'src/php/manage_edit_delete_route.php\');">&nbsp;			
	</center>';
?>
  