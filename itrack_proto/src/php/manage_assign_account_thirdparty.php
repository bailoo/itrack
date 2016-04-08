<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
	include_once('tree_hierarchy_information.php');
	//print_r($root);
	
		echo"<form name='manage1'>
		<table width='70%' align='center'>
			<tr>
				<td align=center>
					<fieldset class='assignment_manage_fieldset'>
						<legend>
							<strong>Accounts Source</strong>
						</legend>
						<select name='source_account' id='source_account' onchange='show_destination_account(this.value);'>
						";
							
							selectbox_account_hierarchy($root);	
							 
					echo"</select>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td>
					<div id='destination_account' />
				</td>
			</tr>
			<!--<tr>
				<td>
					<fieldset class='assignment_manage_fieldset'>
						<legend>
							<strong>Accounts Destination &nbsp;&nbsp;<input type=checkbox onclick=select_all_destination_account>Select All</strong>
						</legend>
						<table width=100%>
						
						<tr>
						";
							$counter=0;
							checkbox_thirdparty_account_hierarchy($root);							 
						echo"
						</tr>
						</table>
					</fieldset>
				</td>
			</tr>-->
		</table>";
	
			include_once('manage_loading_message.php');
	echo'</center>
	</form>';
?>