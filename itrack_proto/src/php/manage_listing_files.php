<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
	include_once('tree_hierarchy_information.php');
echo'<Form name="manage1" method="POST">';
echo"
		<input type='hidden' id='listing_account_id'>";
		echo"<table width='70%' align='center'>
			<tr>
				<td><br>
					<fieldset class='assignment_manage_fieldset'>
						<legend>
							<strong>Accounts</strong>
						</legend>
						<div style='height:350px;overflow:auto'>";
							include_once('manage_radio_account.php'); 
					echo"</div>
					</fieldset>
				</td>
			</tr>
		</table>";
	echo'<br><center>
			<input type="button" value="Enter"  id="enter_button" onclick=javascript:action_manage_listing_files();>&nbsp;        
			</center>
	</Form>';
?>