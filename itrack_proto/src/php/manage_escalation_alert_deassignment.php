<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');  
	$DEBUG=0;	
	$postPars = array('action_type' , 'escalation_serial_number', 'common_id');
	include_once('action_post_data.php');
  $pd = new PostData();
	$common_id1=$pd->data[common_id];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	include_once('manage_escalation.php');
	$query ="SELECT escalation_id,person_name from escalation WHERE escalation_id IN( SELECT escalation_id from escalation_grouping where account_id=$common_id1 AND status=1) AND status=1";
	//echo "query1=".$query."<br>";
	$result = mysql_query($query, $DbConnection);
	$num_rows=mysql_num_rows($result); 

	$vehicle_cnt=0;
	$escalation_cnt=0;
	$no_vehice_cnt=0; //////// in case of no one vehicle exist in geofence
	$flag=1;
	$p=0;
  if($num_rows>0)
	{
		while($row=mysql_fetch_object($result))
		{
		 //echo "in while <br>";
			$escalation_id=$row->escalation_id;
			$person_name=$row->person_name;
			
      $escalation_id1[$p] = $escalation_id;
      $person_name_1[$p] = $person_name;			
			/*$query="SELECT person_name FROM escalation WHERE escalation_id= ".
      "(SELECT escalation_id FROM alert_assignment WHERE alert_id = (SELECT alert_id FROM alert_assignment AND status=1) AND
      "SELECT vehicle_name FROM vehicle WHERE vehicle_id =(SELECT vehicle_id from alert_assignment WHERE escalation_id='$escalation_id' AND status=1""*/
      
      $query = "SELECT vehicle.vehicle_name,alert.alert_name,alert_assignment.serial,alert_assignment.landmark_id,alert_assignment.alert_duration FROM vehicle,alert,alert_assignment ".
      "WHERE alert_assignment.escalation_id='$escalation_id' AND alert_assignment.vehicle_id = vehicle.vehicle_id ".
      "AND alert_assignment.alert_id = alert.alert_id AND alert_assignment.status=1"; 
			//$result_1 = mysql_query($query, $DbConnection);
			//echo "<br>q2=".$query;
			
      //$query="SELECT vehicle_id,vehicle_name from vehicle WHERE vehicle_id IN (SELECT vehicle_id from alert_assignment WHERE escalation_id='$escalation_id' AND status=1) AND status=1";
		  //echo "query=".$query."<br>";
			$result_1 = mysql_query($query, $DbConnection);
			$num_row_child=mysql_num_rows($result_1);
      //echo "num_rows=".$num_row_child."<br>";			
			if($num_row_child>0)
			{
				while($row_1=mysql_fetch_object($result_1))
				{									
          $landmark_id = $row_1->landmark_id;
          if($landmark_id!="")
          {
            $query_landmark = "SELECT landmark_name FROM landmark WHERE landmark_id='$landmark_id'";
            $result_landmark = mysql_query($query_landmark,$DbConnection);
            $row_landmark = mysql_fetch_object($result_landmark);
            $landmark_name_tmp = $row_landmark->landmark_name;
            $landmark_name[$p][$escalation_cnt] = $landmark_name_tmp;
          }
          $serial_1[$p][$escalation_cnt] = $row_1->serial;
          $alert_duration[$p][$escalation_cnt] = $row_1->alert_duration;
          $alert_1[$p][$escalation_cnt]=$row_1->alert_name;
          $vehicle_id[$p][$escalation_cnt]=$row_1->vehicle_id;
					$vehicle_name[$p][$escalation_cnt]=$row_1->vehicle_name;	
          
          //echo '<br>LOOP='.$serial_1[$p][$escalation_cnt].'>'.$vehicle_name[$p][$escalation_cnt].'->'.$alert_1[$p][$escalation_cnt].' ('.$alert_duration[$p][$escalation_cnt].')';          				
										
					$escalation_cnt++;
					$flag=1;
				}
       // $calibration_cnt++; 				
			}
			$p++;
			/*else
			{
				$person_name2[$no_vehice_cnt]=$person_name;
				$no_vehice_cnt++;
			} */
		  // $escalation_cnt++; 
		} 
	}
	else
	{
		$flag=0;
	}
	
	//echo "<br>P=".$p." ,Escalation_cnt=".$escalation_cnt;
	
	echo'<br><form name="manage1"> 
			<center> 	
				<fieldset class="manage_fieldset">
					<legend>
						<strong>Escalation Alert Vehicle De Assignment<strong>
					</legend>	';
				echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">';
					if($flag==1)
					{
            //echo "cali_cnt=".$calibration_cnt."<br>"; 
						if($p>0) //////////when vehicle found in geofence
						{
							for($i=0;$i<$p;$i++)
							{
							   if($person_name_1[$i]!="")
							   {
								  echo'<tr valign="top">
										<td><strong><font color="blue" size="2">Person Name-></font><font color="green" size="2">'.$person_name_1[$i].'</font></strong></td>						
									</tr>'; 
								 }
								 for($j=0;$j<$escalation_cnt;$j++)
								 {
  								 if($serial_1[$i][$j]!="")
  								 {
    								  if($alert_1[$i][$j] == "landmark")
    								  {
    								    $label = "";
                      }
                      else if($alert_1[$i][$j] == "low_battery")
                      {
                        $label = "volt";
                      }
                      else if($alert_1[$i][$j] == "report")
                      {
                        $label = "hrs";
                      }                      
                      else
                      {
                        $label = "min";
                      }
    								  
                      if($alert_duration[$i][$j]=="0")
                      {
                        $alert_duration[$i][$j] = "";
                        $label = "";
                      }
                      
                      if($landmark_name[$i][$j])
                      {
                        echo'<tr>
      										<td>
      											<input type="checkbox" name="escalation_serial_number[]" value="'.$serial_1[$i][$j].'"><font color=blue>'.$vehicle_name[$i][$j].'-></font>&nbsp;<font color=red>'.$alert_1[$i][$j].'</font>('.$landmark_name[$i][$j].'&nbsp;)';
      										echo'
                          </td>
      									</tr>';                        
                      }
                      else
                      { 
                        echo'<tr>
      										<td>
      											<input type="checkbox" name="escalation_serial_number[]" value="'.$serial_1[$i][$j].'"><font color=blue>'.$vehicle_name[$i][$j].'-></font>&nbsp;<font color=red>'.$alert_1[$i][$j].'</font>('.$alert_duration[$i][$j].'&nbsp;'.$label.')';
      										echo'
                          </td>
      									</tr>';
    									}
                   }	
								}		
							}
						}
					 if($no_vehice_cnt>0)
						{
							for($i=0;$i<$no_vehice_cnt;$i++)
							{
							echo'<tr valign="top">
									<td><font color="blue" size="2">Escalation Name-></font><font color="green" size="2">'.$person_name[$i].'</font></td>						
								</tr>
								<tr valign="top">
									<td>
										NO ALERT ASSIGNED FOR THIS ESCALATION
									</td>
								</tr>';	
							}
						}
					}
					else
					{
					echo'<tr valign="top">
							<td>
								NO ESCALATION FOR THIS ACCOUNT TO DE-ASSIGN
							</td>
						</tr>';		
					}
				echo "</table>
				<br>
					<input type='button' id='enter_button' Onclick='javascript:return action_manage_escalation(\"deassign\")'\ value='De-Assign'>
				</fieldset> 				
			</center>			
		</form>";
		include_once('manage_loading_message.php');
?>  