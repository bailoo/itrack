<?php	
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');	

	echo "filename_fields##
	<!--<input type='hidden' value=".$no_of_files." id='total_no_of_files'>-->";
	
	if($action_type=="add")
	{
	
	echo"<table border='0' align=center class='manage_interface' cellspacing='4' cellpadding='4'>";
		for($i=1;$i<=$no_of_files;$i++)
		{
		echo"<tr>
				<td>			
					File Name ".$i."
				</td>
				<td>
				:
				</td>
				<td>
					<input type='text' id='filename_fields_$i' >
				</td>
				<td>			
					File ID ".$i."
				</td>
				<td>
				:
				</td>
				<td>
					<input type='text' id='file_id_$i' >
				</td>
			</tr>";
		}
		echo"</table>";
	}
	else if($action_type=="edit")
	{
		$file_names_1=explode(",",$file_names);
		$file_ids_1=explode(",",$file_ids);
		$cnt=count($file_names_1);
		$j=0;
	echo"<table border='0' align=center class='manage_interface' cellspacing='4' cellpadding='4'>";
		for($i=1;$i<=$no_of_files;$i++)
		{	
			if($i<$cnt)
			{
			echo"<tr>
					<td>			
						File Name ".$i."
					</td>
					<td>
					:
					</td>
					<td>
						<input type='text' id='filename_fields_$i' value='$file_names_1[$j]'>
					</td>
					<td>			
						File ID ".$i."
					</td>
					<td>
					:
					</td>
					<td>
						<input type='text' id='file_id_$i' value='$file_ids_1[$j]' readonly>
					</td>
				</tr>";
				$j++;
			}
			else
			{
				echo"<tr>
					<td>			
						File Name ".$i."
					</td>
					<td>
					:
					</td>
					<td>
						<input type='text' id='filename_fields_$i'>
					</td>
					<td>			
						File ID ".$i."
					</td>
					<td>
					:
					</td>
					<td>
						<input type='text' id='file_id_$i'>
					</td>
				</tr>";
			}
		
		}
		echo"</table>";
	}
?>
