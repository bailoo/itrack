<?php  
	include_once('Hierarchy.php');		
	include_once('util_session_variable.php');	
	include_once('util_php_mysql_connectivity.php');	
	include_once('user_type_setting.php');
	
	$root=$_SESSION['root'];	
	$DEBUG=0;	
	$post_action_type = $_POST['action_type'];		
	if($DEBUG) 
	{
            echo "post_action_type=".$post_action_type;
	}
	$parent_account_ids=array();

	$action_type_local;
	//echo "action_type=".$post_action_type."<br>";
	if($post_action_type=="add" || $post_action_type=="add_register")
	{	
            $local_account_ids=$_POST['account_ids'];	
            $local_account_ids1=explode(",",$local_account_ids);
            $account_size=sizeof($local_account_ids1);		
            $post_vehicle_name=$_POST['vehicle_name'];	
            $post_vehicle_number=$_POST['vehicle_number'];
            $post_sim_number=$_POST['sim_number'];	
            $post_mobile_number=$_POST['mobile_number'];
            $post_manufacturer_name=$_POST['manufacturer_name'];
            $post_max_speed=$_POST['max_speed'];
            $post_vehicle_tag=$_POST['vehicle_tag'];	
            $post_vehicle_type=$_POST['vehicle_type'];	
            $post_category=$_POST['category'];
            //echo "post_cat=".$post_category;	
            if($DEBUG)
            {
                echo "Vehicle Name = ".$post_vehicle_name." (Length: ".strlen($post_vehicle_name).") <br>";	
                echo "Vehicle Number = ".$post_vehicle_number." (Length: ".strlen($post_vehicle_number).") <br>";
                echo "MAX Speed = ".$post_max_speed." (Length: ".strlen($post_max_speed).") <br>";	
                echo "Vehicle Tag = ".$post_vehicle_tag." (Length: ".strlen($post_vehicle_tag).") <br>";
                echo "Vehicle Type = ".$post_vehicle_type." (Length: ".strlen($post_vehicle_type).") <br>";
            }
		
            $count=getVehicleNumRow($post_vehicle_name,1,$DbConnection);		
            if($count > 0)
            {
                    $message = $report_type." Name already exist!";
            }
            else
            {			
			$result=insertVehicleRecord($post_vehicle_name,$post_vehicle_type,$post_vehicle_tag,$post_max_speed,$post_category,$post_vehicle_number,$post_sim_number,$post_mobile_number,$post_manufacturer_name,1,$account_id,$date,$DbConnection);
			if($result)
			{				
				$vehicle_id=getVehicleId($post_vehicle_name,1,$DbConnection);				
				$result1=insertVehicleGrouping($vehicle_id,$account_size,$local_account_ids1,$account_id,$date,1,$DbConnection);
				if($result1)
				{
					for($i=0;$i<($account_size-1);$i++)
					{
						if($i==0)
						{
							$account_string2=explode(",",$account_string1[$i]);
							$account_id_local1=$account_string2[0];
							$account_status1=$account_string2[1];
							//echo"account_id_local=".$account_id_local1."<br>";			
							break;
						}
					}					
					$acc=assign_to_till_root($account_id);					
					$accSize=sizeof($acc);					
					$result2=insertVehicleGrouping($vehicle_id,$accSize,$acc,$account_id,$date,1,$DbConnection);					
					
					$message = "<center>
										<br>
										<FONT color=\"green\">
											<strong>
												".$report_type." Detail Added Successfully!
											</strong>
										</font>
									</center>";
					$result_status="success";
				}					
				else
				{
					$message = "<center>
									<br>
									<FONT color=\"red\">
										<strong>
											Sorry! Unable to process request.
										</strong>
									</font>
								</center>";
				}				
			}
		}		
	}
	
	if($post_action_type=="register" || $post_action_type=="add_register")            ////////// ASSIGN
	{ 
		$device = $_POST['device_imei_no'];
		$vname = $_POST['vehicle_name']; 	
		$local_account_id = $_POST['local_account_id1'];		
		
		$vehicle_display_option = $_POST['vehicle_display_option1']; 	
		$options_value = $_POST['options_value1'];
		
		echo'<input type="hidden" id="vehicle_display_option" value='.$vehicle_display_option.'>
			<input type="hidden" id="options_value" value='.$options_value.'>
			<input type="hidden" id="local_account_id" value='.$local_account_id.'>';
		//$query ="SELECT vehicle_id FROM vehicle WHERE vehicle_name='$vname' AND vehicle_id IN (SELECT vehicle_id from vehicle_grouping WHERE account_id=$local_account_id AND status=1) AND status=1";     
		$vid=getVehileIdByVehicleName($vname,$status,$DbConnection);
		
		if($vid!="")
		{
			$result=insertVehicleAssignment($vid,$device,$account_id,$date,1,$DbConnection); 	
			if($result)
			{
				$message="<center>
							<br><br>
								<FONT color=\"green\">
									<strong>
										Device And ".$report_type." Assigned Successfully
									</strong>
								</font>
						</center>";
				$result_status="success";
			}
			else
			{
				$message="<center>
							<br><br>
							<FONT color=\"red\">
								<strong>
									Unable to Assign Device to ".$report_type."
								</strong>
							</font>
						</center>";
			} 
		} 
	}
  
	else if($post_action_type=="deregister")           ///////// DE ASSIGN
	{
		$device1 = $_POST['device']; 	
		$device = explode(',',$device1);     
		if($DEBUG) 
		{
			echo "device=".$device." device=".$device." size=".sizeof($device);
		}

		for($i=0;$i<sizeof($device);$i++)
		{
			$result=updateVehicleAssignment($account_id,$date,$device[$i],$DbConnection);
		}               

		if($result)
		{
			$message="<center>
						<br><br>
							<FONT color=\"green\">
								<strong>
									Device and ".$report_type." De-Registered Successfully
								</strong>
							</font>
					</center>";
		$result_status="success";
		}
		else
		{
			$message="<center>
					<br><br>
						<FONT color=\"red\">
							<strong>
								Unable to De De-Registered Device and ".$report_type."
							</strong>
						</font>
					</center>";
		}    
	}
	
	else if($post_action_type == "edit_action")                 ////////// EDIT
	{
		//$edit_account_id = $_POST['edit_account_id'];
		$vehicle_id_edit = $_POST['vehicle_id'];
		$vehicle_name_edit = $_POST['vehicle_name'];
		$vehicle_number_edit = $_POST['vehicle_number'];
		$max_speed_edit = $_POST['max_speed'];
		$vehicle_tag_edit = $_POST['vehicle_tag']; 
		$vehicle_type_edit = $_POST['vehicle_type']; 
		$category = $_POST['category1'];  
		$post_sim_number=$_POST['sim_number'];	
		$post_mobile_number=$_POST['mobile_number'];
                $post_manufacturer_name=$_POST['manufacturer_name'];

		$result=updateVehicleDetail($vehicle_name_edit,$vehicle_number_edit,$post_sim_number,$post_mobile_number,$post_manufacturer_name,$max_speed_edit,$vehicle_tag_edit,$vehicle_type_edit,$category,$account_id,$date,$vehicle_id_edit,1,$DbConnection);
		
		if($result)
		{
			$message = "<center>
						<br><br>
							<FONT color=\"green\">
								<strong>
									".$report_type." Detail Updated Successfully
								</strong>
							</font>
					</center>";
			$result_status="success";		  
		}
		else 
		{
		$message="<center>
					<br><br>
						<FONT color=\"green\">
							<strong>
								Unable to Update ".$report_type." Detail
							</strong>
						</font>
				</center>";  
		}
	}
	
	else if($post_action_type == "delete")              //////////// DELETE
	{   
		$vehicle_id_local = $_POST['manage_vehicle_id'];
		//echo"vehicle_id_local=".$vehicle_id_local;
		$vehicleIdResult=getVAVehicleId($vehicle_id_local,1,$DbConnection);		
		if($vehicleIdResult!="")
		{
			$message="<center>
						<br><br>
							<FONT color=\"red\">
								<strong>
									This ".$report_type." has been Assigned to Device! Deassign First!
								</strong>
							</font>
					</center>";  
		}
		else
		{
			$result1=deleteVehicle($account_id,$date,1,$vehicle_id_local,$DbConnection);			
			$result2=deleteVehicleGrouping($account_id,$date,1,$vehicle_id_local,$DbConnection);				
			if($result1 && $result2)
			{			
				$message = "<center>
							<br><br>
								<FONT color=\"red\">
									<strong>
										Selected ".$report_type." Deleted Successfully
									</strong>
								</font>
							</center>";
				$result_status="success";
			}
			else
			{
				$message="<center>
							<br><br>
								<FONT color=\"red\">
									<strong>
										Unable to Delete ".$report_type." Detail
									</strong>
								</font>
						</center>";
			}
		}    
	}
  
  
	else if($post_action_type == "assign")
	{		
		$account_string = $_POST['account_string1'];
		//echo "account_string=".$account_string."<br>";
		$account_string1=explode(":",$account_string);
		$account_size=sizeof($account_string1);		
		$vehicle_ids = $_POST['vehicle_ids1'];		
		//echo"vehicle_ids=".$vehicle_ids."<br>";
		for($i=0;$i<($account_size-1);$i++)
		{
			$account_string2=explode(",",$account_string1[$i]);
			$account_id_local=$account_string2[0];			
			$account_status=$account_string2[1];	
			//if($account_id_local==2)
			//echo "account_status=".$account_status."<br>"."account_id=".$account_id_local."<br>";
			$statusResult=getVehicleGroupingStatus($vehicle_ids,$account_id_local,$DbConnection);
				//if($account_id_local==2)
			//echo "statusResult=".$statusResult."<br>";
			if($statusResult!='NDF')
			{				
				$status_local=$statusResult;
			}
				//if($account_id_local==2)
			//echo "status_local=".$status_local."<br>";
			
			if(($statusResult=='NDF' && $account_status==1) || ($account_status==0 && $status_local==1) || ($account_status==1 && $status_local==0))
			{
				if($account_status==1)
				{
					if($statusResult=='NDF')
					{
						$result=insertVGSingleRecord($vehicle_ids,$account_id_local,1,$account_id,$date,$DbConnection);
						if($result)
						{
							$message="Assigned/Deassigned action performed successfully";
						}
						else
						{
							$message="Unable to process this request";
						}
					}
					else
					{
						$result=updateVehicleGrouping($date,$vehicle_ids,1,$account_id_local,$DbConnection);						
						if($result)
						{
							$message="Assigned/Deassigned action performed successfully";
						}
						else
						{
							$message="Unable to process this request";
						}
					}
					
					$deviceImeiNo=getVADeviceImeiNo($vehicle_ids,1,$DbConnection);
					//echo "deviceImeiNo=".$deviceImeiNo."<br>";
					if($deviceImeiNo!="")
					{
						$numrows=getDANumRowRecord($deviceImeiNo,$account_id_local,$DbConnection);						
						if($numrows==0)
						{							
							$result=insertDeviceAssignment($deviceImeiNo,$account_id_local,1,$account_id,$date,$DbConnection);
							if($result)
							{
								$message="Assigned/Deassigned action performed successfully";
							}
							else
							{
								$message="Unable to process this request";
							}
						}
						else
						{							
							$result=updateDeviceAssignment($deviceImeiNo,1,$account_id_local,$DbConnection);					
							if($result)
							{
								$message="Assigned/Deassigned action performed successfully";
							}
							else
							{
								$message="Unable to process this Request";
							}
						}
					}	
				}
				else if($account_status==0)
				{
					$result=updateVehicleGrouping($date,$vehicle_ids,0,$account_id_local,$DbConnection);
					
					if($result)
					{
						$message="Assigned/Deassigned action performed successfully";
					}
					else
					{
						$message="Unable to process this request";
					}
					
					
					$deviceImeiNo=getVADeviceImeiNo($vehicle_ids,1,$DbConnection);
					//echo "deviceImeiNo=".$deviceImeiNo."<br>";
			
					if($deviceImeiNo!="")
					{
						$numrows2=getDANumRowRecord($deviceImeiNo,$account_id_local,$DbConnection);					
						if($numrows2!=0)
						{
							//echo "in num row 2<br>";
							$result=updateDeviceAssignment($deviceImeiNo,0,$account_id_local,$DbConnection);												
						}
					}
				}
			}		
		}  
		$account_string = $_POST['account_string1'];
		//echo "account_string=".$account_string."<br>";
		$account_string1=explode(":",$account_string);
		
		for($i=0;$i<($account_size-1);$i++)
		{
			if($i==0)
			{
				$account_string2=explode(",",$account_string1[$i]);
				$account_id_local1=$account_string2[0];
				$account_status1=$account_string2[1];
				//echo"account_id_local=".$account_id_local1."<br>";			
				break;
			}
		}
		
		$acc=assign_to_till_root($account_id_local1);	
		//echo "acc=".sizeof($acc)."<br>";
		
		for($i=0;$i<sizeof($acc);$i++)
		{
			$account_id_local=$acc[$i];		
						
			$statusResult=getVehicleGroupingStatus($vehicle_ids,$account_id_local,$DbConnection);		
			if($statusResult!='NDF')
			{				
				$status_local=$statusResult;
			}
						
			if(($statusResult=='NDF' && $account_status==1) || ($account_status==0 && $status_local==1) || ($account_status==1 && $status_local==0))
			{
				if($account_status==1)
				{
					if($statusResult=='NDF')
					{						
						$result=insertVGSingleRecord($vehicle_ids,$account_id_local,1,$account_id,$date,$DbConnection);						
						if($result)
						{
							$message="Assigned/Deassigned action performed successfully";
						}
						else
						{
							$message="Unable to process this request";
						}
					}
					else
					{				
						$result=updateVehicleGrouping($date,$vehicle_ids,1,$account_id_local,$DbConnection);
						if($result)
						{
							$message="Assigned/Deassigned action performed successfully";
						}
						else
						{
							$message="Unable to process this request";
						}
					}
					
					
					$deviceImeiNo=getVAVehicleId($vehicle_ids,1,$DbConnection);
					if($deviceImeiNo!="")
					{	
						$numrows=getDANumRowRecord($deviceImeiNo,$account_id_local,$DbConnection);					
						if($numrows==0)
						{
							$result=insertDeviceAssignment($deviceImeiNo,$account_id_local,1,$account_id,$date,$DbConnection);
							if($result)
							{
								$message="Assigned/Deassigned action performed successfully";
							}
							else
							{
								$message="Unable to process this request";
							}
						}
						else
						{
							$result=updateDeviceAssignment($device_imeino_local,1,$account_id_local,$DbConnection);
							if($result)
							{
								$message="Assigned/Deassigned action performed successfully";
							}
							else
							{
								$message="Unable to process this request";
							}
						}
					}	
				}
			}
		}		
	}  
  
    if($result_status=="success")
	{
		/*unset($root);
		unset($final_group_array);
		include_once("get_group.php");
		include_once('tree_hierarchy.php');
		$group_cnt=0;		
		$final_group_array=GetGroup_3($root,$DbConnection);
		$_SESSION['final_group_array'] = $final_group_array; */

	}
	function assign_to_till_root($account_id_local1)
	{
		global $DbConnection;	
		global $parent_account_ids;	 
		global $acc_size;
	
		$admin_id=getAccountAdminId($account_id_local1,$DbConnection);		
		$function_account_id=getAccountIdByAdminId($admin_id,$DbConnection);
		
		if($function_account_id==0)
		{
			return $parent_account_ids;
		}
		else
		{			
			$final_account_id=assign_to_till_root($function_account_id,$vehicle_ids);			
			$parent_account_ids[]=$function_account_id;
			//echo"acc1=".$function_account_id."<br>"."acc1=".$function_account_id."<br>"."acc1=".$parent_account_ids."<br>";				
			return $parent_account_ids;					
		}
	} 
	//include('childToRoot.php');	

	echo' <br>
		<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
			<tr>
				<td colspan="3" align="center"><b>'.$message.'</b></td>    
			</tr>
		</table>';
	if($post_action_type=="register")
	{		
		echo'<center>
				<a href="javascript:show_vehicle_register(\'manage\',\'device_vehicle_register\');" class="back_css">
					<b>&nbsp;<b>Back</b>
				</a>
			</center>'; 		
	}
	else if($post_action_type=="add_register" || $post_action_type=="add")
	{
		echo'<center>
				<a href="javascript:show_vehicle_register(\'manage\',\'add_vehicle\');" class="back_css">
					&nbsp;<b>Back</b>
				</a>
			</center>'; 
	}
	else
	{
		echo'<center>
				<a href="javascript:show_option(\'manage\',\'vehicle\');" class="back_css">
					&nbsp;<b>Back</b>
				</a>
			</center>'; 
	}
	//include_once("manage_vehicle.php");
?>
        
