<?php
	//ini_set('memory_limit', '-1');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 
	//echo "<br>TEST_ACTION";
	$postPars = array('escalation_id' , 'escalation_ids', 'landmark_id', 'distance_variable', 'action_type' , 'local_account_ids' , 'person_name', 'person_mob', 'person_email' , 'other_detail', 'alert_id', 'vehicle_ids', 'escalation_serial_number', 'duration', 'sms_status', 'mail_status');
	include_once('action_post_data.php');
	include_once('coreDb.php'); 
	$pd = new PostData();
	//echo "<br>LOCAL ACCID=".$local_account_ids;
	$post_account_id = $local_account_ids;
	//echo "<br>action_type=".$pd->data[action_type];
  
	$DEBUG=0;   
	// $setgetpostvalue_obj=new setgetpostvalue();    		
	$local_account_ids=$setgetpostvalue_obj->localAccoutIDS;
	$account_size=sizeof($local_account_ids);
	$old_value= Array();
	$new_value=Array();
	$field_name=Array();
	$table_name="escalation"; 

	$person_flag = 0;
	$flag_assign = 0;	
	
	if($pd->data[action_type]=="add") 
	{
		$action_type="add";  

		$person_name1=$pd->data[person_name];
		$person_mob1=$pd->data[person_mob];
		$person_email1=$pd->data[person_email];
		$other_detail1=$pd->data[other_detail]; 

		$local_account_ids=explode(",",$pd->data[local_account_ids]);
		$account_size=sizeof($local_account_ids);
		
		$max_no=getEscalatinMaxCnt($DbConnection);	
		//echo "maxNo=".$max_no."<br>";
		if($max_no=="")
		{
			$max_no=1;
		}
		
		$rowResult=getEscalationSingleRow($person_mob1,$person_name1,1,$DbConnection);
    
		if($rowResult!="")
		{
			$action_perform= $person_name1." ,Mob:".$person_mob1;
			$person_flag = 1;
		}
		else
		{ 
			getMysqlBegin();
			$result1=insertEscalation($max_no,$person_name1,$person_mob1,$person_email1,$other_detail1,1,$account_id,$date,$DbConnection);
			if($result1)
			{
				$result2=insertEscalationGrouping($max_no,$account_size,$local_account_ids,1,$account_id,$date,$DbConnection); // M=Multiple
				if($result1 && $result2)
				{
					getMysqlCommit();
					$flag=1;
					$action_perform="Added";
				}	
				else
				{
					getMysqlRollback();
				}
			}
		}
	}
	else
	{
		$flag=2;
	} 
 
	if($pd->data[action_type]=="edit")
	{	
		$file_name="src/php/manage_edit_delete_escalation.php"; ///////for previous page
		$action_type="edit_delete";
		$escalation_id=$pd->data[escalation_id]; 
		$person_name=$pd->data[person_name];
		$person_mob=$pd->data[person_mob];
		$person_email=$pd->data[person_email];
		$other_detail=$pd->data[other_detail];
		
		$result=updateEscalation($person_name,$person_mob,$person_email,$other_detail,$account_id,$date,$escalation_id,$DbConnection);
		
		if($result)
		{
			$flag=1;
			$action_perform="Updated";
		} 
		else
		{
			$flag=2;
		}    
	}
	
	else if ($pd->data[action_type]=="delete")
	{
		$file_name="src/php/manage_edit_delete_escalation.php"; ///////for previous page
		$action_type="edit_delete"; 
		$calibration_id=$pd->data[calibration_id];		
		$rowResult=getAAEscalationId($escalation_id,1,$DbConnection); // AA=> Alert Assignment		
		
		if($rowResult!=0)
		{
			$delete_flag=1; 
		}
		else
		{
			$result=deleteEscalation($account_id,$date,$escalation_id,1,$DbConnection);			
			$file_name="src/php/manage_edit_delete_escalation.php"; ///////for previous page  		
			if($result)
			{
				$flag=1;
				$action_perform="Deleted";
			}
		}
	}
	else if($pd->data[action_type]=="assign_prev")
	{
		$flag_assign = 1; 
		echo '<FORM name="form_next" method="POST">';
		$file_name="src/php/manage_escalation_alert_assignment.php"; ///////for previous page
		$action_type="assign_prev";
		//$local_vehicle_ids=explode(",",$pd->data[vehicle_ids]);
		$local_alert_id = $pd->data[alert_id];
		$local_vehicle_ids_tmp=explode(",",$pd->data[vehicle_ids]);
		$vehicle_size_tmp=sizeof($local_vehicle_ids_tmp);		
		$local_duration = $pd->data[duration];    
		if($local_duration == "")
		{
			$local_duration = 0;
		}
		echo '<input type="hidden" name="alert_id" value="'.$local_alert_id.'">';
		echo '<input type="hidden" name="duration" value="'.$local_duration.'">';
		echo '<input type="hidden" name="account_id_hidden" id="account_id_hidden" value="'.$post_account_id.'">';    
		//$local_sms_tmp=explode(",",$pd->data[sms_status]);
		//$local_mail_tmp=explode(",",$pd->data[mail_status]);	
		//$local_duration = $pd->data[duration];				
		$firststr = 1;
		$alert_id_tmp2 ="";
		$duration_tmp2 ="";
		$msgtmp ="";
		$flag3 =0;
		$alert_name=getAlertAlertName($local_alert_id,1,$DbConnection);		
		
    
		$vehicle_id_str = "";
		for($i=0;$i<$vehicle_size_tmp;$i++)
		{
			echo '<input type="hidden" name="vehicle_id[]" value="'.$local_vehicle_ids_tmp[$i].'">';
			$vehicle_id_str = $vehicle_id_str.$local_vehicle_ids_tmp[$i].","; 
		}
  	
		$vehicle_id_str = substr($vehicle_id_str, 0, -1);

		$alertData=getVehicleDetailForAlert($vehicle_id_str,1,$DbConnection);
		
		$alert_vname = "";
		
		foreach($alertData as $adu)
		{  
			$vname_db = $adu['vehicle_name'];
			//echo "<br>vname_db=".$vname_db;
			$alert_vname = $alert_vname.$vname_db."<font color=red>,</font>";
		}
		
		$alert_vname = substr($alert_vname, 0, -1);         
		//echo "<br>LOCAL ACCOUNT_ID=".$local_account_ids;         
		echo'<TABLE align="center">
				<tr>
					<td>';
						$alert_type1 = "";
						$alert_type2 = "";
						if($pd->data[sms_status] == 1)  
						{
							echo'<input type="hidden" name="sms_status" value="1">';
							$alert_type1 = "SMS";
						}
						else
						{
							echo'<input type="hidden" name="sms_status" value="0">';
							$alert_type1 = "-";    
						}
			
						if($pd->data[mail_status] == 1)
						{
							echo'<input type="hidden" name="mail_status" value="1">';
							$alert_type2 = "MAIL";
						}
						else
						{
							echo'<input type="hidden" name="mail_status" value="0">';
							$alert_type2 = "-";      
						}        
					echo"<br>
							<center>
								<div style='width:650px;height:200px;overflow:auto;'>
									<strong>
										ALERT DETAIL &nbsp;:&nbsp;
										<span style='color:red;font-size:10px;'>
											".$alert_name."
										</span>
										<span style='color:blue;font-size:10px;'>
											(".$alert_type1.",".$alert_type2.") 
										</span>
									</strong>
									&nbsp;:&nbsp;
									<span style='color:green;font-size:10px;'>
										".$alert_vname."
									</span>
								</div>
							</center>
						<br>";
				echo'</td>
				</tr>';
			echo'<tr>
					<td>
						<div style="width=300px;height:300px;overflow:auto;">';         			
						if($alert_name == "landmark")
						{
						echo"<fieldset class='manage_cal_vehicle'>
								<legend>
									<strong>
										Landmark
									</strong>
								</legend> 
								<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>
									<tr>
										<td>
											Select landmark
										</td>
										<td>
											&nbsp;:&nbsp;
										</td>
										<td>";
										$landmarkData=getLandmarkDetail($post_account_id,1,$DbConnection);
									echo'<select name="landmark" id="landmark">;
											<option value="select">
												Select
											</option>';            
											foreach($landmarkData as $lDUnit)
											{
												echo'<option value="'.$lDUnit['landmark_id'].'">
														'.$lDUnit['landmark_name'].'
													</option>';            
											}
										echo'</select>
										</td>
										<td>
											&nbsp;&nbsp;&nbsp;Enter Distance
										</td>
										<td>
											&nbsp;:&nbsp;
										</td>
										<td>
											<input type="text" name="distance_variable"/>&nbsp;(km)
										</td>
									</tr>
								</table>
							</fieldset>';	
        }
        else
        {
          echo '<input type="hidden" name="landmark" value="-"/>';
          echo '<input type="hidden" name="distance_variable" value="-"/>';
        }
					echo"<table>
							<tr> 
								<td>          
									<fieldset class='manage_cal_vehicle'>
										<legend>
											<strong>
												Person
											</strong>
										</legend>		
										<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'>";	
										$row_result=getEscalationDetail($post_account_id,1,$DbConnection);												
										if($row_result!=0)
										{
										echo"<tr>
												<td>
													<INPUT TYPE='checkbox' name='all' Onclick='javascript:select_all_escalation(this.form);'>
														Select All
												</td>
												</tr>";					
											$i=0;
											foreach($row_result as $eSU)
											{									
												$escalation_id= $eSU['escalation_id'];
												$person_name=$eSU['person_name'];
												if($i==0)
												{
												echo"<tr>";
												}
													echo"<td>
															&nbsp<INPUT TYPE='checkbox' name='escalation_id[]' VALUE='$escalation_id'/>
															<font color='blue' size='2'>
																".$eSU['person_name']."&nbsp;&nbsp;&nbsp;
															</font>"."												
														</td>";																								
												if($i==8)
												{
													echo"</tr>";
													$i=0;
												}
												$i++;
											}
										}
										else
										{
											echo"<font color='blue' size='2'>
													NO ESCALATION DETAIL FOUND
												</font>";
										}
										   echo'</div>
											</td>
										</tr>
									</table>
								</fieldset>  
							</td>																													
						</tr>	    
					</table>	      	
				</div>
			</td>
		</tr>
	</TABLE>
</FORM>
<center>
		<input type="button" Onclick="javascript:return action_manage_escalation(\'assign\')" value="ASSIGN ALERT">
</center>';
    echo '<br>
			<center>
				<div id="loading_status" style="display:none;"></div>
			</center>
			<center>
				<br>
				<div id="AssignedStatus" style="display:none;"></div>
			</center>'; 
			$common_str="action_type=".$action_type."&common_id=".$pd->data[local_account_ids];
			echo"<input type='hidden' id='common_id' value='".$common_str."'>";
		echo'<center>
				<a href="javascript:manage_action_edit_prev(\''.$file_name.'\');" class="back_css">
					&nbsp;<b>Back</b>
				</a>
			</center>';                            
		include_once('manage_loading_message.php');
	}
  //ASSIGN PREV CLOSED
	else if($pd->data[action_type]=="assign")
	{
		$insert_ok = 0;
		$flag_assign = 1;
		echo "alert_assign##";
		$file_name="src/php/manage_escalation_alert_assignment.php"; ///////for previous page
		$action_type="assign";

		//### GET POST DATA
		$local_alert_id = $pd->data[alert_id];    
		$local_duration = $pd->data[duration];
		$local_sms = $pd->data[sms_status];
		$local_mail = $pd->data[mail_status];
		$local_landmark_id = $pd->data[landmark_id];
		$local_distance_variable = $pd->data[distance_variable];    
		//if($local_landmark_id == "-") $local_landmark_id = "NULL";
		//if($local_distance_variable == "-") $local_distance_variable = "NULL";
		$local_vehicle_ids = explode(",",$pd->data[vehicle_ids]);
		$vehicle_size_tmp = sizeof($local_vehicle_ids);
		$local_escalation_ids = explode(",",$pd->data[escalation_ids]);
		$escalation_size_tmp = sizeof($local_escalation_ids);
		/////#############			   	                			
		$firststr = 1;
		$alert_id_tmp2 ="";
		$duration_tmp2 ="";
		$msgtmp ="";
		$flag3 =0;
		$status = 0;    
		//echo "<br>esclation_size=".$escalation_size_tmp." ,vehicle_size=".$vehicle_size_tmp;
		$assignment_string = ""; 
		$msgtmp = "";
    
		for($i=0;$i<$escalation_size_tmp;$i++)
		{
			for($j=0;$j<$vehicle_size_tmp;$j++)
			{
				if($local_landmark_id != "-")
				{
					$landmark_name= getLandmarkName($local_landmark_id,1,$DbConnection);					
				} 
				
				$alert_name = getAlertAlertName($local_alert_id,1,$DbConnection);			
				$person_name =  getEscalationPersonName($local_escalation_ids[$i],1,$DbConnection);				
				$vehicle_name = getVehicleNameByVid($local_vehicle_ids[$j],1,$DbConnection);
				
				if($local_landmark_id != "-")
				{
					$query_exist ="SELECT alert_id,vehicle_id,escalation_id,alert_duration FROM alert_assignment WHERE alert_id='$local_alert_id' ".
					"AND vehicle_id='$local_vehicle_ids[$j]' AND escalation_id = '$local_escalation_ids[$i]' AND landmark_id='$local_landmark_id' ".
					"AND status=1";
				}
				else
				{
					$query_exist ="SELECT alert_id,vehicle_id,escalation_id,alert_duration FROM alert_assignment WHERE alert_id='$local_alert_id' ".
					"AND vehicle_id='$local_vehicle_ids[$j]' AND escalation_id = '$local_escalation_ids[$i]' AND status=1";          
				}				
				$numrows = getAlertAssignment($query_exist,$DbConnection);
        
				if($numrows>0)
				{          
					//echo $query1."<br>".$query2."<br>".$query3."<br>";         
					$duration_string = "";
					if($local_duration != "0")
					{
						$duration_string = "(".$local_duration." &nbsp;mins)";
					}
          
					if($local_landmark_id != "-")
					{
						$msgtmp=$msgtmp."<br>
								<font color='red'>
									Alert already been assigned :".$vehicle_name." ->
									".$alert_name."->"
									.$duration_string.":landmark->"
									.$landmark_name." [".$person_name."]
								</font>";            
					}
					else
					{
						$msgtmp=$msgtmp."<br>
									<font color='red'>
										Alert already been assigned :".$vehicle_name.
										" ->".$alert_name."->"
										.$duration_string.": [".$person_name."]
									</font>";            
					}
                    
				  $flag3 =1;
				}
				else
				{
					$assignment_string = $assignment_string.$msgtmp;
					if($local_landmark_id != "-")
					{          
						$assignment_string = $assignment_string."<br><strong>AlertName:<font color=green>".$alert_name."</font>,".
						"Person Name:<font color=green>".$person_name."</font>,".
						"Vehicle Name:<font color=green>".$vehicle_name."</font>,".
						"Landmark Name:<font color=green>".$landmark_name."</font></strong>";
					}
					else
					{
						$assignment_string = $assignment_string."<br><strong>AlertName:
							<font color=green>
								".$alert_name."
							</font>,".
							"Person Name:
							<font color=green>
								".$person_name."
							</font>,".
							"Vehicle Name:
							<font color=green>
								".$vehicle_name."
							</font>";            
					}                                                  
					$msgtmp = "";

					if($local_landmark_id != "-")
					{          
						$query2="INSERT INTO alert_assignment(escalation_id,vehicle_id,alert_id,alert_duration,sms_status,mail_status,landmark_id, distance_variable, edit_id,edit_date,status) VALUES".
						"($local_escalation_ids[$i],$local_vehicle_ids[$j],$local_alert_id,$local_duration,$local_sms,$local_mail,$local_landmark_id, $local_distance_variable, $account_id,'$date',1)";
					}
					else
					{
						$query2="INSERT INTO alert_assignment(escalation_id,vehicle_id,alert_id,alert_duration,sms_status,mail_status,landmark_id, distance_variable, edit_id,edit_date,status) VALUES".
						"($local_escalation_ids[$i],$local_vehicle_ids[$j],$local_alert_id,$local_duration,$local_sms,$local_mail,NULL, NULL, $account_id,'$date',1)";            
					}
							
					$result=insertAlertAssignment($query2,$DbConnection);					
					if($result)
					{					
						$insert_ok = 1;  
					}
				}
			} // inner loop 
		} // outer loop       
		$assignment_string = substr($assignment_string, 0, -1);
		if($insert_ok) 
		{
			echo"<table align='center'>
					<tr>
						<td align='left'>
							<strong>
								<font color=green>
									Current Alert Assigned :-
								</font>
							</strong>
							<br><br>
								".$assignment_string."
							<br>
						</td>
					</tr>
				</table>";      
		}     
	}
	
	else if($pd->data[action_type]=="deassign")
	{
		$file_name="src/php/manage_escalation_alert_deassignment.php"; ///////for previous page
		$action_type="de-assign";
		$escalation_serial_number_1=explode(",",$pd->data[escalation_serial_number]);
		$escalation_size=sizeof($escalation_serial_number_1); 	 		
		$old_value= Array();   $new_value=Array();   $field_name=Array(); $table_name="alert_assignment";  
		for($i=0;$i<$escalation_size;$i++)
		{	
			
			$result=updateAlertAssignment($escalation_serial_number_1[$i],$account_id,$date,1,$DbConnection);
			
			//$geo_id1= $veh_geo_id2[1];
			/*$old_value[]= "1";
			$new_value[]="0";
			$field_name[]= "status";         
			$ret_result=track_table($escalation_serial_number_1[$i],$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);*/
		}		
		//if($ret_result=="success" && $result){$flag=1;$action_perform="De-Assigned";} 
		if($result)
		{
			$flag=1;
			$action_perform="De-Assigned";
		} 			
	}
     
	if($flag==1)
	{
		$msg = "Escalation ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else if($flag_assign)
	{
		$msg = "";
	}
	else if($person_flag)
	{
		$msg = "Person ".$action_perform." has already been added";
		$msg_color = "red";
	}  
	else if($flag==2 && !$flag_assign)
	{	  
		$msg = "Escalation input is not correct.Please enter correct input.";
		$msg_color = "green";				
	}
	else if($delete_flag==1)
	{
		$msg = "This escalation has already been Assigned to Vehicle! Deassign First!";
		$msg_color = "red";	
	}
	else
	{ 	 
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
		
	if($flag3)
	{
		$msg = $msg."<br>".$msgtmp."<br>";
	}
  
	if($flag==2)
	{	      
		$common_str="escalation_id=".$pd->data[escalation_id]."
					&action_type=".$action_type."&local_account_ids=".
					$pd->data[local_account_ids]."&common_id=".
					$pd->data[local_account_ids]."&person_name=".
					$pd->data[person_name]."&person_mob=".
					$pd->data[person_mob]."&person_email=".
					$pd->data[person_email]."&other_detail=".
					$pd->data[other_detail];
		//echo"common_str=".$common_str;
		echo"<input type='hidden' id='common_id' value='".$common_str."'>";
	}
    
  echo "<center>
			<br><br>
			<strong>
				<FONT color=\"".$msg_color."\" size=\"2\">
					".$msg."<br><br>
				</font>
			</strong>
		</center>";
	if($pd->data[action_type]=="add")
	{
		$common_str="action_type=".$pd->data[action_type]; 
		echo"<input type='hidden' id='common_id' value='".$common_str."'>";
		echo'<center>
				<a href="javascript:show_option_with_value(\'manage\',\'add_escalation\');" class="back_css">
					&nbsp;<b>Back</b>
				</a>
			</center>';
	}
	else if(!$flag_assign)
	{
		$common_str="action_type=".$action_type."&common_id=".$pd->data[local_account_ids]; 
		echo"<input type='hidden' id='common_id' value='".$common_str."'>";
		echo'<center>
				<a href="javascript:manage_action_edit_prev(\''.$file_name.'\');" class="back_css">
					&nbsp;
					<b>Back</b>
				</a>
			</center>';
	}
   
?>
        
