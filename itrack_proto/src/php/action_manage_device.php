<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');	

	$DEBUG =0;	 $post_action_type = $_POST['action_type'];

	if($post_action_type =="add")
	{	
		$imei_no_1=$_POST['imei_no'];   
		$manufacturing_date_1=$_POST['manufacturing_date'];		
		$make_1=$_POST['make'];	 
		$io_ids_1=$_POST['io_ids'];
		$deviceImeiNo=getDeviceImeiNo($imei_no_1,1,$DbConnection);

		if($deviceImeiNo!="")
		{
			$message="<center><FONT color=\"blue\"><strong>This Device Already Exists</strong></font></center>";      
		}
		else
		{
			$result=insertDeviceMInfo($imei_no_1,$io_ids_1,$manufacturing_date_1,$make_1,$account_id,$date,$status,$DbConnection);			
			$result=insertDeviceAssignment($imei_no_1,1,1,$account_id,$date,$DbConnection);		
			if($result)
			{
				$message="<center>
							<FONT color=\"green\">
								<strong>
									Device Added Successfully
								</strong>
							</font>
						</center>";
			}
			else
			{
				$message="<center><FONT color=\"red\"><strong>Device Not Added Successfully</strong></font></center>";
			} 
		} 
	} 
	else if($post_action_type == "assign")
	{		
		$account_string = $_POST['account_string1'];
		//echo "account_string=".$account_string."<br>";
		$account_string1=explode(":",$account_string);
		$account_size=sizeof($account_string1);		
		$device_imei_no = $_POST['device_imei_no1'];
		
		for($i=0;$i<($account_size-1);$i++)
		{
			$account_string2=explode(",",$account_string1[$i]);
			$account_id_local=$account_string2[0];
			//echo"account_id=".$account_id_local."<br>";
			$account_status=$account_string2[1];
			//echo "account_status=".$account_status."<br>";
			$numrows=getDANumRowRecordNew($device_imei_no,$account_id_local,$DbConnection);	
			//echo "status_local=".$numrows."<br>";
			if($numrows!=0)
			{	
				$status_local=$numrows;
			}
			//echo "status_local=".$status_local."<br>";
			//($num_rows==0 && $account_status==1)  it's found no entry
			// $account_status==0 && $status_local==1) it is for deassignment
			// ($account_status==1 && $status_local==0) it is for assignment 
			
		
			if(($numrows==0 && $account_status==1) || ($account_status==0 && $status_local==1) || ($account_status==1 && $status_local==0))
			{
				if($account_status==1)
				{
					if($numrows==0)
					{
						$result = insertDeviceAssignment($device_imei_no,$account_id_local,1,$account_id,$date,$DbConnection);					
						if($result)
						{
							$message="Device Assigned/Deassigned action performed successfully";
						}
						else
						{
							$message="Unable to process this request";
						}
					}
					else
					{					
						$result=updateDeviceAssignment($device_imei_no,1,$account_id_local,$date,$DbConnection);					
						if($result)
						{
							$message="Device Assigned/Deassigned action performed successfully";
						}
						else
						{
							$message="Unable to process this request";
						}
					}
					$numrows=getVAVehicleIdByImeiNo($device_imei_no,1,$DbConnection); 				
					if($numrows!="")
					{						
						$vehicleid_local = $numrows;// this numrows hold return vehicle id
					
						$numrows = getVGVehicleId($vehicleid_local,$account_id_local,$DbConnection);
						if($numrows=="")
						{
							$result = insertVehicleGroupingSingle($vehicleid_local,$account_id_local,$account_id,$date,$DbConnection);
							if($result)
							{
								$message="Device Assigned/Deassigned action performed successfully";
							}
							else
							{
								$message="Unable to process this request";
							}
						}
						else
						{							
							$result = updateVehicleGrouping($date,$vehicleid_local,1,$account_id_local,$DbConnection);
							if($result)
							{
								$message="Device Assigned/Deassigned action performed successfully";
							}
							else
							{
								$message="Unable to process this request";
							}
						}
					}	
				}
				else if($account_status==0)
				{	
					//echo "in else";
					$result=updateDeviceAssignment($device_imei_no,0,$account_id_local,$date,$DbConnection);
					//echo "result=".$result."<br>";
					if($result)
					{
						$message="Device Assigned/Deassigned action performed successfully";
					}
					else
					{
						$message="Unable to process this request";
					}
					
					$numrows1=getVAVehicleId($device_imei_no,1,$DbConnection);
					if($numrows1!="")
					{
						$vehicleid_local = $numrows1; //this $numrows1 hold return vehicle id					
						$numrows2=getVGVehicleId($vehicleid_local,$account_id_local,$DbConnection);
						if($numrows2!="")
						{
							$result = updateVehicleGrouping($date,$vehicleid_local,0,$account_id_local,$DbConnection);						
						}
					}
				}
			}		
		} 
		
	}
	if($post_action_type =="edit")
	{	
		$device_imei_no1=$_POST['device_imei_no'];  
		$io_ids_1=$_POST['io_ids'];
		
		$result = updateDeviceManufacturingInfo($io_ids_1,$device_imei_no1,1,$DbConnection);
		if($result)
		{
			$message="<center><FONT color=\"blue\"><strong>Device updated successfully</strong></font></center>";      
		}
		else
		{
			$message="<center><FONT color=\"blue\"><strong>Sorry! Unable to process request!</strong></font></center>";	
		}
	}		
    
  echo' <br>
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
    <tr>
      <td colspan="3" align="center"><b>'.$message.'</td>    
    </tr>
  </table>';
  echo'<center><a href="javascript:show_option(\'manage\',\'device\');" class="back_css">&nbsp;<b>Back</b></a></center>';          	
  //include_once("manage_device.php");
	
?> 
	

