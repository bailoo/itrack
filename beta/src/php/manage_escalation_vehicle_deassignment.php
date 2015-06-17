<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');  
	$DEBUG=0;	
	$postPars = array('action_type' , 'common_id');
	include_once('action_post_data.php');
  $pd = new PostData();
	$common_id1=$pd->data[common_id];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	include_once('manage_calibration.php');
	$query ="SELECT calibration_id,calibration_name from calibration WHERE calibration_id IN( SELECT calibration_id from calibration_grouping where account_id=$common_id1 AND status=1) AND status=1";
	//echo "query=".$query;
	$result = mysql_query($query, $DbConnection);
	$num_rows=mysql_num_rows($result); 

	$vehicle_cnt=0;
	$calibration_cnt=0;
	$no_vehice_cnt=0; //////// in case of no one vehicle exist in geofence
	$flag=1;
	if($num_rows>0)
	{
		while($row=mysql_fetch_object($result))
		{
		 //echo "in while <br>";
			$calibration_id=$row->calibration_id;
			$calibration_name=$row->calibration_name;
			$query="SELECT vehicle_id,vehicle_name from vehicle WHERE vehicle_id IN (SELECT vehicle_id from calibration_vehicle_assignment WHERE calibration_id='$calibration_id' AND status=1) AND status=1";
		  //echo "query=".$query."<br>";
			$result_1 = mysql_query($query, $DbConnection);
			$num_row_child=mysql_num_rows($result_1);
      //echo "num_rows=".$num_row_child."<br>";			
			if($num_row_child>0)
			{
				while($row_1=mysql_fetch_object($result_1))
				{				
					$vehicle_id[$calibration_cnt][$vehicle_cnt]=$row_1->vehicle_id;
					$vehicle_name[$calibration_cnt][$vehicle_cnt]=$row_1->vehicle_name;
					$calibration_id1[$calibration_cnt]=$calibration_id;
					$calibration_name1[$calibration_cnt]=$calibration_name;					
					$vehicle_cnt++;
				}
       // $calibration_cnt++; 				
			}
			else
			{
				$calibration_name2[$no_vehice_cnt]=$calibration_name;
				$no_vehice_cnt++;
			}
		   $calibration_cnt++; 
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
						<strong>Calibration Vehicle De Assignment<strong>
					</legend>	';
				echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">';
					if($flag==1)
					{
            //echo "cali_cnt=".$calibration_cnt."<br>"; 
						if($vehicle_cnt>0) //////////when vehicle found in geofence
						{
							for($i=0;$i<$calibration_cnt;$i++)
							{
							   if($calibration_name1[$i]!="")
							   {
								echo'<tr valign="top">
										<td><font color="blue" size="2">Calibration Name-></font><font color="green" size="2">'.$calibration_name1[$i].'</font></td>						
									</tr>'; 
									}
									for($j=0;$j<$vehicle_cnt;$j++)
									{
									 if($vehicle_id[$i][$j]!="")
									 {
									echo"<tr>
											<td>
												<input type='checkbox' name='vehicle_id[]' value='".$vehicle_id[$i][$j]."'>".$vehicle_name[$i][$j]."
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
									<td><font color="blue" size="2">Calibration Name-></font><font color="green" size="2">'.$calibration_name2[$i].'</font></td>						
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
								NO CALIBRATION THIS ACCOUNT FOR DE-ASSIGN
							</td>
						</tr>';		
					}
				echo "</table>
				<br>
					<input type='button' id='enter_button' Onclick='javascript:return action_manage_calibration(\"deassign\")'\ value='De-Assign'>
				</fieldset> 				
			</center>			
		</form>";
   
?>  