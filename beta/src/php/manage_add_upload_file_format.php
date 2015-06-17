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
							<input type="radio" name="upload_format_type"  value="master">
						</td>
						<td>Master</td>
						<td>
							<input type="radio" name="upload_format_type"  value="get_report">
						</td>
						<td>Get Report</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>Upload Format ID</td>
			<td>&nbsp;:&nbsp;</td>
			<td>
				<input type="text" name="upload_format_id" id="upload_format_id" >
			</td>
		</tr> 
		<tr>
			<td>File Format Name</td>
			<td>&nbsp;:&nbsp;</td>
			<td>
				<input type="text" name="file_format_name" id="file_format_name">				
			</td> 
		</tr>
		<tr>
			<td>No Of File Upload</td>
			<td>&nbsp;:&nbsp;</td>
			<td>
				<select id="no_of_files" onchange=javascript:show_selected_filename_field(this.value);>
					<option value="select">Select</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="1">5</option>										
				</select>
			</td>						
		</tr>		
	</table>
	<div id="filename_fields" style="display:none"></div>
	<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2" width="27%"> 
		<tr>
			<td width="38%">Remark</td>
			<td width="4%">&nbsp;:&nbsp;</td>
			<td>
				<input type="text" name="remark" id="remark" size="6">				
			</td> 
		</tr>
		<tr>                    									
			<td align="center" colspan="3">
				<input type="button" id="enter_button" Onclick="javascript:return action_manage_upload_file_format(\'add\');" value="Enter">
				&nbsp;<input type="reset" value="Clear">
			</td>
		</tr>
	</table>';  
  ?>
