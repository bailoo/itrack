<?php
  echo'
  <center>
  	<table border=0 width = 100% cellspacing=2 cellpadding=0>
  		<tr>
  			<td height=10 class="report_heading" align="center">Upload Station Halt</td>
  		</tr>
  	</table>';
	echo '<table border="0" class="manage_interface">
			<tr>
				<td valign="top">
					<input type="radio" name="upload_format" onclick=javascript:show_upload_format("upload_format1");>
				</td>
				<td>
					Halt Report
				</td>
				<td>
					<input type="radio" name="upload_format" onclick=javascript:show_upload_format("upload_format2");>
				</td>
				<td>
					Distance Billing Report
				</td>
				<td>
					<input type="radio" name="upload_format" onclick=javascript:show_upload_format("upload_format3");>
				</td>
				<td>
					Schedule Report
				</td>				
			</tr>			
		</table>';
echo '
	<form name="file_upload_form" target="_blank" method="post" enctype="multipart/form-data" action="src/php/action_report_upload_files.php">
		<input type="hidden" name="format_type" id="format_type">
		<input type="hidden" name="upload_file_type" value="station_halt_file">
		<div id="upload_format1" style="display:none">
			<table border="0" class="manage_interface">
				<tr>
					<td>
						Upload Halt File 
					</td>
					<td>
						:
					</td>
					<td>
						<input name="haltReport" size="27" type="file" />(csv/xls/xlsx format)
					</td>
				</tr>
				<tr>
					<td colspan="3" align="center">				
					&nbsp;<input type="submit" value="Upload File" id="enter_button"/>
					&nbsp;<input type="reset"" value="Clear" />   			
					</td>
				</tr>
			</table>
		</div>
		<div id="upload_format2" style="display:none">
			<table border="0" class="manage_interface">
				<tr>
					<td>
						Upload Distance File 
					</td>
					<td>
						:
					</td>
					<td>
						<input name="format2_1" size="27" type="file" />(csv/xls/xlsx format)
					</td>
				</tr>
				<tr>
					<td>
						Upload Billing File 
					</td>
					<td>
						:
					</td>
					<td>
						<input name="format2_2" id="file" size="27" type="file" />(csv/xls/xlsx format)
					</td>
				</tr>
				<tr>
					<td colspan="3" align="center">				
					&nbsp;<input type="submit" value="Upload File" id="enter_button"/>
					&nbsp;<input type="reset"" value="Clear" />   			
					</td>
				</tr>
			</table>
		</div>
		<div id="upload_format3" style="display:none">
			<table border="0" class="manage_interface">
				<tr>
					<td>
						Upload Schedule File 
					</td>
					<td>
						:
					</td>
					<td>
						<input name="scheduleReport" id="file" size="27" type="file" />(csv/xls/xlsx format)
					</td>
				</tr>
				<tr>
					<td colspan="3" align="center">				
					&nbsp;<input type="submit" value="Upload File" id="enter_button"/>
					&nbsp;<input type="reset"" value="Clear" />   			
					</td>
				</tr>
			</table>
		</div>
	</form> 
';  
?>