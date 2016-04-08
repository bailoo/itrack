<?php
  echo'
  <center>
  	<table border=0 width = 100% cellspacing=2 cellpadding=0>
  		<tr>
  			<td height=10 class="report_heading" align="center">Exception Timing</td>
  		</tr>
  	</table>';
echo '
	<form name="file_upload_form" target="_blank" method="post" enctype="multipart/form-data" action="src/php/action_report_upload_files.php">
		<input type="hidden" name="upload_file_type" value="exception_timing_file">
		<table border="0" class="manage_interface">
			<tr>
				<td>
					Upload Data File 
				</td>
				<td>
					:
				</td>
				<td>
					<input name="filetmp" id="file" size="27" type="file" />&nbsp;(csv/xls/xlsx format)
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center">				
				&nbsp;<input type="submit" value="Upload File" id="enter_button"/>
				&nbsp;<input type="reset"" value="Clear" />   			
				</td>
			</tr>
		</table>    
	</form> 
';  
?>
