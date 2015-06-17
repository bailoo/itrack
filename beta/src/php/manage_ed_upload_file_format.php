<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
	include_once('tree_hierarchy_information.php');
	$DEBUG=0;  
	echo "add##"; 
	echo"<table width='70%' align='center'>
			<tr>
				<td><br>
					<fieldset class='assignment_manage_fieldset'>
						<legend>
							<strong>Accounts</strong>
						</legend>
						<div style='height:200px;overflow:auto'>";
							include_once('manage_radio_account.php'); 
					echo"</div>
					</fieldset>
				</td>
			</tr>
		</table>";
echo'<table border="0" align=center class="manage_interface" cellspacing="3" cellpadding="3">        
		<tr>
			<td colspan=4>
				<table>
					<tr>
						<td>
							<input type="radio" name="upload_format_type"  value="master" onclick=javascript:show_format_type_file(this.value);>
						</td>
						<td>Master</td>
						<td>
							<input type="radio" name="upload_format_type"  value="get_report" onclick=javascript:show_format_type_file(this.value);>
						</td>
						<td>Get Report</td>
					</tr>
				</table>
			</td>
		</tr>			
	</table>
	<div id="upload_format_child_file" style="display:none"></div>	
	<div id="show_child_filename_fields" style="display:none"></div>
	<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2" width="27%"> 		
		<tr>                    									
			<td align="center" colspan="3">
				<input type="button" id="enter_button" Onclick="javascript:return action_manage_upload_file_format(\'edit\');" value="Enter">
				<input type="button" id="delete_button" Onclick="javascript:return action_manage_upload_file_format(\'delete\');" value="Delete">
				&nbsp;<input type="reset" value="Clear">
			</td>
		</tr>
	</table>';  
  ?>
