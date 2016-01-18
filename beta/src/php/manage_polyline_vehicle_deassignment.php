<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
        include_once('coreDb.php');
	$DEBUG=0; 
	$common_id1=$_POST['common_id'];
        
	/*$query ="SELECT polyline_id,polyline_name from polyline WHERE user_account_id=$common_id1 AND status=1";
	//echo "query=".$query;
	$result = mysql_query($query, $DbConnection);
	$num_rows=mysql_num_rows($result);*/
        $num_data=getPolylineName($common_id1,$DbConnection);

	$vehicle_cnt=0;
	$polyline_cnt=0;
	$no_vehice_cnt=0; //////// in case of no one vehicle exist in geofence
	$flag=1;
        /*
	if($num_rows>0)
	{
		while($row=mysql_fetch_object($result))
		{
			$polyline_id=$row->polyline_id;
			$polyline_name=$row->polyline_name;
			$query="SELECT vehicle_id,vehicle_name from vehicle WHERE vehicle_id IN (SELECT vehicle_id from polyline_assignment WHERE polyline_id='$polyline_id' AND status=1) AND status=1";
			//echo "query=".$query."<br>";
			$result_1 = mysql_query($query, $DbConnection);
			$num_rows_1=mysql_num_rows($result_1);
			
			if($num_rows_1>0)
			{
				while($row_1=mysql_fetch_object($result_1))
				{				
					$vehicle_id[$polyline_cnt][$vehicle_cnt]=$row_1->vehicle_id;
					$vehicle_name[$polyline_cnt][$vehicle_cnt]=$row_1->vehicle_name;
					$polyline_id1[$polyline_cnt]=$polyline_id;
					$polyline_name1[$polyline_cnt]=$polyline_name;					
					$vehicle_cnt++;
				}  		
			}
			else
			{
				$polyline_name2[$no_vehice_cnt]=$polyline_name;
				$no_vehice_cnt++;
			}
		  $polyline_cnt++;
		} 
	}
	else
	{
		$flag=0;
	}*/
	if(count($num_data)>0)
        {
            foreach($num_data as $row)
            {
                $polyline_id=$row['polyline_id'];
		$polyline_name=$row['polyline_name'];
                $num_data1=getVehicleIdPolylineassignment($polyline_id,$DbConnection);
                if(count($num_data1)>0)
                {
                    foreach($num_data1 as $row_1)
                    {				
                            $vehicle_id[$polyline_cnt][$vehicle_cnt]=$row_1['vehicle_id'];
                            $vehicle_name[$polyline_cnt][$vehicle_cnt]=$row_1['vehicle_name'];
                            $polyline_id1[$polyline_cnt]=$polyline_id;
                            $polyline_name1[$polyline_cnt]=$polyline_name;					
                            $vehicle_cnt++;
                    }  
                }
                else
                {
                    $polyline_name2[$no_vehice_cnt]=$polyline_name;
		    $no_vehice_cnt++;
                }
                 $polyline_cnt++;
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
						<strong>Polyline/Route Vehicle De Assignment<strong>
					</legend>	';
				echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">';
					if($flag==1)
					{ 
						if($vehicle_cnt>0) //////////when vehicle found in geofence
						{
							for($i=0;$i<$polyline_cnt;$i++)
							{
  							 if($polyline_name1[$i]!="")
  							 {
  								echo'<tr valign="top">
  										<td><font color="blue" size="2">Polyline Name-></font><font color="green" size="2">'.$polyline_name1[$i].'</font></td>						
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
									<td><font color="blue" size="2">Polyline Name-></font><font color="green" size="2">'.$polyline_name2[$i].'</font></td>						
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
								NO POLYLINE/ROUTE AVAILABLE FOR THIS ACCOUNT
							</td>
						</tr>';		
					}
				echo "</table>
				<br>
					<input type='button' id='enter_button' Onclick='javascript:return action_manage_polyline(\"deassign\")'\ value='De-Assign'>
				</fieldset> 
				 <center><a href=\"javascript:show_option('manage','polyline');\" class=\"back_css\">&nbsp;<b>Back</b></a></center> 
			</center>			
		</form>";
   
?>  