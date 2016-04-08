<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	$DEBUG=0; 
	$common_id1=$_POST['common_id'];
	$query ="SELECT geo_id,geo_name from geofence WHERE user_account_id=$common_id1 AND status=1";
	//echo "query=".$query;
	$result = mysql_query($query, $DbConnection);
	$num_rows=mysql_num_rows($result);


	$vehicle_cnt=0;
	$group_cnt=0;
	$no_vehice_cnt=0; //////// in case of no one vehicle exist in geofence
	$flag=1;
	if($num_rows>0)
	{
		while($row=mysql_fetch_object($result))
		{
			$geo_id=$row->geo_id;
			$geo_name=$row->geo_name;
			$query="SELECT vehicle_id,vehicle_name from vehicle WHERE vehicle_id IN (SELECT vehicle_id from geo_assignment WHERE geo_id='$geo_id' AND status=1) AND status=1";
			//echo "query=".$query."<br>";
			$result = mysql_query($query, $DbConnection);
			$num_rows=mysql_num_rows($result);
			
			if($num_rows>0)
			{
				while($row=mysql_fetch_object($result))
				{				
					$vehicle_id[$group_cnt][$vehicle_cnt]=$row->vehicle_id;
					$vehicle_name[$group_cnt][$vehicle_cnt]=$row->vehicle_name;
					$geo_id1[$group_cnt]=$geo_id;
					$geo_name1[$group_cnt]=$geo_name;					
					$vehicle_cnt++;
				}
				$group_cnt++;
			}
			else
			{
				$geo_name2[$no_vehice_cnt]=$geo_name;
				$no_vehice_cnt++;
			}
		
		} 
	}
	else
	{
		$flag=0;
	}
	
	echo'<br><form name="manage1"> 
			<center> 	
				<fieldset class="manage_fieldset">
					<legend>
						<strong>Geofence Vehicle De Assignment<strong>
					</legend>	';
				echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">';
					if($flag==1)
					{ 
						if($group_cnt>0) //////////when vehicle found in geofence
						{
							for($i=0;$i<$group_cnt;$i++)
							{
								echo'<tr valign="top">
										<td><font color="blue" size="2">Geofence Name-></font><font color="green" size="2">'.$geo_name1[$i].'</font></td>						
									</tr>'; 
									for($j=0;$j<$vehicle_cnt;$j++)
									{
									echo"<tr>
											<td>
												<input type='checkbox' name='vehicle_id[]' value='".$vehicle_id[$i][$j]."'>".$vehicle_name[$i][$j]."
											</td>
										</tr>";		
									}		
							}
						}
						if($no_vehice_cnt>0)
						{
							for($i=0;$i<$no_vehice_cnt;$i++)
							{
							echo'<tr valign="top">
									<td><font color="blue" size="2">Geofence Name-></font><font color="green" size="2">'.$geo_name2[$i].'</font></td>						
								</tr>
								<tr valign="top">
									<td>
										NO VEHICLE FOUND IN THIS ACCOUNT
									</td>
								</tr>';	
							}
						}
					}
					else
					{
					echo'<tr valign="top">
							<td>
								NO GEOFENCE THIS ACCOUNT
							</td>
						</tr>';		
					}
				echo "</table>
				<br>
					<input type='button' id='enter_button' Onclick='javascript:return action_manage_geofence(\"deassign\")'\ value='De-Assign'>
				</fieldset> 
				 <center><a href=\"javascript:show_option('manage','geofence');\" class=\"back_css\">&nbsp;<b>Back</b></a></center> 
			</center>			
		</form>";
   
?>  