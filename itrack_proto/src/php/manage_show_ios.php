<?php
	include_once('util_session_variable.php');		
	include_once('util_php_mysql_connectivity.php'); 
	$device_imei_no = $_POST['common_id'];		
	echo"portal_vehicle_information##";	
	$query="SELECT * FROM feature_mapping WHERE feature_type='hw' AND status='1'";
	$result=mysql_query($query,$DbConnection);
	$io_size=0;
	while($row=mysql_fetch_object($result))
	{
		$io_feature_id[$io_size]=$row->feature_id;
		$io_feature_name[$io_size]=$row->feature_name;
		$io_size++;			
	}
	$query="SELECT io FROM device_manufacturing_info where device_imei_no=$device_imei_no";
	//echo "query=".$query;
	$result=mysql_query($query,$DbConnection);
	echo'<table border="0" class="manage_interface">
			<tr>
				<td><input type="checkbox" name="io_all" value='.$io_id.'/ onclick="javascript:io_all_check(this.form);">Select All</td>
			</tr>';					
				while($row=mysql_fetch_object($result))
				{
				
					$io_feature_id_1=$row->io;
					$io_feature_id_2=explode(",",$io_feature_id_1);
					$cnt=0;
					echo"<tr>";
					for($i=0;$i<$io_size;$i++)
					{
						$flag=0;
						for($j=0;$j<sizeof($io_feature_id_2);$j++)
						{
							if($io_feature_id_2[$j]==$io_feature_id[$i])
							{
								$flag=1;
								break;
							}						
						}
						if($cnt==6)
						{
							echo"<tr></tr>";
							$cnt=0;
						}
						if($flag==1)
						{				
					echo'
							<td><input type="checkbox" name="io_name[]" value="'.$io_feature_id[$i].'" checked/>'.$io_feature_name[$i].'</td>';
						}
						else
						{
					echo'
							<td><input type="checkbox" name="io_name[]" value="'.$io_feature_id[$i].'"/>'.$io_feature_name[$i].'</td>
						';
						}	
						$cnt++;
					}	
					echo"</tr>";
				}		
		echo'</table>';
	
?>