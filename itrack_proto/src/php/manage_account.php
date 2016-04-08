<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
	include_once('tree_hierarchy_information.php');
		echo"<table width='70%' align='center'>
			<tr>
				<td><br>
					<fieldset class='assignment_manage_fieldset'>
						<legend>
							<strong>Accounts</strong>
						</legend>
						<div style='height:350px;overflow:auto'>";
							include_once('manage_radio_group_account.php'); 
					echo"</div>
					</fieldset>
				</td>
			</tr>
		</table>";
	echo'<br><center>
			<input type="button" value="Update"  id="enter_button" onclick="javascript:return validation_user(\'src/php/manage_edit_account.php\',\'edit\');">&nbsp;        
			<input type="button" value="Create New" onclick="javascript:validation_user(\'src/php/manage_add_account.php\',\'add\');">&nbsp;
			<input type="button" value="Delete Account" id="enter_button" Onclick="javascript:return validation_user(\'\',\'delete\')">&nbsp';
			include_once('manage_loading_message.php');
	echo'</center>
	</form>';
?>