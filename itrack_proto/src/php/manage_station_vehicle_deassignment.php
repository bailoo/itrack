<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	$DEBUG=0; 
	$common_id1=$_POST['common_id'];
	$query ="SELECT station_id,station_name from station WHERE user_account_id=$common_id1 AND status=1";
	//echo "query=".$query;
	$result = mysql_query($query, $DbConnection);
	$num_rows=mysql_num_rows($result);


	$vehicle_cnt=0;
	$geo_cnt=0;
	$no_vehice_cnt=0; //////// in case of no one vehicle exist in geofence
	$flag=1;
	if($num_rows>0)
	{
		while($row=mysql_fetch_object($result))
		{
			$geo_id=$row->station_id;
			$geo_name=$row->station_name;
			$query="SELECT vehicle_id,vehicle_name from vehicle WHERE vehicle_id IN (SELECT vehicle_id from station_assignment WHERE station_id='$geo_id' AND status=1) AND status=1";
			//echo "query=".$query."<br>";
			$result_1 = mysql_query($query, $DbConnection);
			$num_rows_1=mysql_num_rows($result_1);
			
			if($num_rows_1>0)
			{
				while($row_1=mysql_fetch_object($result_1))
				{				
					$vehicle_id[$geo_cnt][$vehicle_cnt]=$row_1->vehicle_id;
					$vehicle_name[$geo_cnt][$vehicle_cnt]=$row_1->vehicle_name;
					$geo_id1[$geo_cnt]=$geo_id;
					$geo_name1[$geo_cnt]=$geo_name;					
					$vehicle_cnt++;
				}  		
			}
			else
			{
				$geo_name2[$no_vehice_cnt]=$geo_name;
				$no_vehice_cnt++;
			}
		  $geo_cnt++;
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
						<strong>Station Vehicle De Assignment<strong>
					</legend>	';
				echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">';
					if($flag==1)
					{ 
						if($vehicle_cnt>0) //////////when vehicle found in geofence
						{
							for($i=0;$i<$geo_cnt;$i++)
							{
  							 if($geo_name1[$i]!="")
  							 {
  								echo'<tr valign="top">
  										<td><font color="blue" size="2">Station Name-></font><font color="green" size="2">'.$geo_name1[$i].'</font></td>						
  									</tr>'; 
  								}
									for($j=0;$j<$vehicle_cnt;$j++)
									{
									 if($vehicle_id[$i][$j]!="")
									 {
  									$parameter = $vehicle_id[$i][$j].":".$geo_id1[$i];
                    echo"<tr>
  											<td>
  												<input type='checkbox' name='vehicle_id[]' value='".$parameter."'>".$vehicle_name[$i][$j]."  												                          
  											</td>
  										</tr>";
                    }		
									}		
							}
						}
						if($no_vehice_cnt>0)
						{
							for($i=0;$i<$no_vehice_cnt;$i++)
							{
							echo'<tr valign="top">
									<td><font color="blue" size="2">Station Name-></font><font color="green" size="2">'.$geo_name2[$i].'</font></td>						
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
								NO STATION AVAILABLE FOR THIS ACCOUNT
							</td>
						</tr>';		
					}
				echo "</table>
				<br>
					<input type='button' id='enter_button' Onclick='javascript:return action_manage_station(\"deassign\")'\ value='De-Assign'>
				</fieldset> 
				 <center><a href=\"javascript:show_option('manage','station');\" class=\"back_css\">&nbsp;<b>Back</b></a></center> 
			</center>			
		</form>";
   
?>  