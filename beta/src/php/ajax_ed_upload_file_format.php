<?php	
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');	
	
	if($upload_format_type=="master" || $upload_format_type=="get_report")
	{
		echo "upload_format_child_file##";
		if($upload_format_type=="master")
		{
			$Query="SELECT upload_format_id,format_name FROM master_file WHERE account_id='$account_id_local' AND status=1";
			$Result=mysql_query($Query,$DbConnection);		
		}
		else if($upload_format_type=="get_report")
		{
			$Query="SELECT upload_format_id,format_name FROM get_report_file WHERE account_id='$account_id_local' AND status=1";
			$Result=mysql_query($Query,$DbConnection);	
		}
		//echo "Query=".$Query."<br>";
		//echo "Result=".$Result."<br>";
		$NumRows=mysql_num_rows($Result);
		if($NumRows==0)
		{
			echo "<table align=center class='error_message'>
						<tr>
							<td>
								No $upload_format_type File Exit
							</td>
				</table>";
			
		}
		else
		{
		echo'<table border="0" align=center class="manage_interface" cellspacing="3" cellpadding="3">  		
				<tr>
					<td>
						Format Name
					</td>
					<td>:</td>
					<td>
						<select id="upload_format_id_select" onchange=javascript:show_upload_format_child_file(this.value,"'.$upload_format_type.'");>
							"<option value="select">Select</option>';
						while($Row=mysql_fetch_object($Result))
						{
							echo "<option value=".$Row->upload_format_id.">".$Row->format_name."</option>";
						}
					echo"</select>	
					</td>
				</tr>							
			</table>";
		}
	}
	else
	{
		echo "show_child_filename_fields##";
		if($format_id_type=="master")
		{
			$Query="SELECT format_name,no_of_files,file_names,file_ids FROM master_file WHERE upload_format_id='$upload_format_id' AND".
				   " account_id='$account_id_local' AND status=1";
			$Result=mysql_query($Query,$DbConnection);		
		}
		else if($format_id_type=="get_report")
		{
			$Query="SELECT format_name,no_of_files,file_names,file_ids FROM get_report_file WHERE upload_format_id='$upload_format_id' AND".
				   " account_id='$account_id_local' AND status=1";
			$Result=mysql_query($Query,$DbConnection);	
		}
		//echo "Query=".$Query."<br>";
		//echo "Result=".$Result."<br>";
		$NumRows=mysql_num_rows($Result);
		if($NumRows==0)
		{
			echo "<table align=center class='error_message'>
						<tr>
							<td>
								No $format_id_type File Exit
							</td>
				</table>";			
		}
		else
		{
			$Row=mysql_fetch_row($Result);
			$file_names=explode(",",$Row[2]);
			$file_ids=explode(",",$Row[3]);
			//echo "file_name=".$file_names."<br>";
			//echo "file_ids=".$file_ids."<br>";
			echo "<input type='hidden' id='pre_no_of_file' value='$Row[1]'>";
		echo'<table border="0" align=center class="manage_interface" cellspacing="3" cellpadding="3">  		
				<tr>
					<td>Upload Format ID</td>
					<td>&nbsp;:&nbsp;</td>
					<td>
						<input type="text" name="upload_format_id" id="upload_format_id" value="'.$upload_format_id.'" readonly >
					</td>
				</tr> 
				<tr>
					<td>File Format Name</td>
					<td>&nbsp;:&nbsp;</td>
					<td>
						<input type="text" name="file_format_name" id="file_format_name" value="'.$Row[0].'">				
					</td> 
				</tr>
				<tr>
					<td>No Of File Upload</td>
					<td>&nbsp;:&nbsp;</td>
					<td>
						<select id="no_of_files" onchange=javascript:show_edit_selected_filename_field(this.value);>
							<option value='.$Row[1].' select>'.$Row[1].'</option>							
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>										
						</select>
					</td>						
				</tr>
			</table>
		<div id="filename_fields">
			<table border="0" align=center class="manage_interface" cellspacing="3" cellpadding="3">';
				$j=0;
			for($i=0;$i<sizeof($file_names);$i++)
			{
				$j++;
			echo"<tr>
					<td>			
						File Name ".$j."
					</td>
					<td>
					:
					</td>
					<td>
						<input type='text' id='filename_fields_$j' value='$file_names[$i]' >
					</td>
					<td>			
						File ID ".$j."
					</td>
					<td>
					:
					</td>
					<td>
						<input type='text' id='file_id_$j' value='$file_ids[$i]' readonly>
					</td>
				</tr>";
			}							
		echo"</table>
		</div>";		
		}		
	}
?>
