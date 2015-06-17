<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	if($upload_type=="master")
	{
		$Query="Select * FROM master_file WHERE account_id='$account_id' AND status=1";
		$Result=mysql_query($Query, $DbConnection);	
	}
	else if($upload_type=="get_report")
	{
		$Query="Select * FROM get_report_file WHERE account_id='$account_id' AND status=1";	
		$Result=mysql_query($Query, $DbConnection);	
	}
		//echo "Query=".$Query."<br>";	
	echo "<center>
		<form name='file_upload_form' target='_blank' method='post' enctype='multipart/form-data' action='src/php/action_report_upload_files.php'>
			<input type='hidden' name='upload_type' value='$upload_type'>
			<table>
				<tr>
					<td height='5px'>
					</td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=0>
				<tr>
					<td height=10 class='report_heading' align='center'>
						".$title."
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td height='5px'>
					</td>
				</tr>
			</table>
			<table border=0 cellspacing=2 cellpadding=0>
				<tr>";
			while($Row=mysql_fetch_object($Result))
			{
				echo"<td>
						<input type='radio' name='format_ids' value='$Row->upload_format_id:$Row->file_ids' onclick='javascript:return show_master_browse();'>
					</td>
					<td>
						".$Row->format_name."  &nbsp;&nbsp;&nbsp;
					</td>";
			}
			echo"</tr>
			</table>
			<table>
				<tr>
					<td height='5px'>
					</td>
				</tr>
			</table>";			
			$Result1=mysql_query($Query, $DbConnection);
			while($Row1=mysql_fetch_object($Result1))
			{
				$file_names=$Row1->file_names;
				//echo "<br>file_name=".$file_names;
				
				$file_names_1=explode(",",$file_names);
				$file_ids=$Row1->file_ids;
				//echo "file_ids=".$file_ids."<br>";
				$file_ids_1=explode(",",$file_ids);
			
				echo"<table border=0 cellspacing=2 cellpadding=0 id='master_child_table_$Row1->upload_format_id' style='display:none'>";
						$j=0;
						for($i=0;$i<sizeof($file_names_1);$i++)
						{
						
							$filenames='file_'.$Row1->upload_format_id."_".$file_ids_1[$i];
							//echo "files_name=".$filenames."<br>";
							echo "<tr>
									<td>
										".$file_names_1[$i]."
									</td>
									<td>
										&nbsp;:&nbsp;
									</td>
									<td>
										<input type='file' name='$filenames'>
									</td>
								  </tr>";
						}
						echo "</table>";			
			}
  echo'	<table>
				<tr>
					<td height="5px">
					</td>
				</tr>
			</table>
		<table border=0 width = 100% cellspacing=2 cellpadding=0>
				<tr>
					<td colspan="3" align="center">				
					&nbsp;<input type="submit" value="Upload File" id="enter_button"/>
					&nbsp;<input type="reset"" value="Clear" />   			
					</td>
				</tr>
			</table>
	</form> 
	</center>
';  
?>