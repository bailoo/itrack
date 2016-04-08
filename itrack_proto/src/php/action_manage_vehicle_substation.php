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
  
	if($post_action_type == "assign")
	{		
		$vehicle_string = $_POST['vehicle_id'];
		//echo "account_string=".$account_string."<br>";
		$vehicle_id_main =explode(":",$vehicle_string);
		$vehicle_size=sizeof($vehicle_id_main);		
		$account_id_substation = $_POST['substation_user'];		
		//echo"vehiclesize=".$vehicle_size."<br>";
		//echo"account_id_substation=".$account_id_substation."<br>";
		for($i=0;$i<$vehicle_size;$i++)
		{		
			
			$numrows = getNumRowVehicleGroupping ($vehicle_id_main[$i],$account_id_substation,$DbConnection);	

			if($numrows==0)
			{				
				$result = insertVehicleGroupingSingle($vehicle_id_main[$i],$account_id_substation,$account_id,$date,$DbConnection);
				if($result)
				{$message="Assigned action performed successfully";}
				else
				{$message="Unable to process this request";}
			}		
					
		
			$device_imeino_local =getVADeviceImeiNo($vehicle_id_main[$i],1,$DbConnection);
			if($device_imeino_local!="")
			{
				$numrows = getNumRowDeviceAssign($device_imeino_local,$account_id_substation,$DbConnection);
				if($numrows==0)
				{			
					$result =insertDeviceAssignment($device_imeino_local,$account_id_substation,1,$account_id,$date,$DbConnection);
					if($result)
					{$message="Assigned action performed successfully";}
					else
					{$message="Unable to process this request";}
				}
				else
				{
					$result = updateDeviceAssignment($device_imeino_local,1,$account_id_substation,$date,$DbConnection);
					if($result)
					{$message="Assigned action performed successfully";}
					else
					{$message="Unable to process this request";}
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
	} 
	//########## ASSIGN CLOSED
	
	//######### DEASSIGN OPENS
	else if($post_action_type == "deassign")
	{		
		$vehicle_string = $_POST['vehicle_id'];
		//echo "account_string=".$account_string."<br>";
		$vehicle_id_main =explode(":",$vehicle_string);
		$vehicle_size=sizeof($vehicle_id_main);		
		$account_id_substation = $_POST['substation_user'];		
		//echo"vehiclesize=".$vehicle_size."<br>";
		//echo"account_id_substation=".$account_id_substation."<br>";
		for($i=0;$i<$vehicle_size;$i++)
		{		
			
			$result = updateVehicleGrouping($date,$vehicle_id_main[$i],0,$account_id_substation,$DbConnection);
			
			
			$device_imeino_local = getVADeviceImeiNo($vehicle_id_main[$i],1,$DbConnection) ;
			if($device_imeino_local!="")
			{						
				$result2 = updateDeviceAssignment($device_imeino_local,0,$account_id_substation,$date,$DbConnection);	
			}			
		}
		
		if($result)
		{$message="DeAssigned action performed successfully";}
		else
		{$message="Unable to process this request";}
	} 
	//########### DEASSIGN CLOSED

	echo' <br>
			<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
				<tr>
					<td colspan="3" align="center"><b>'.$message.'</b></td>    
				</tr>
			</table>';

	echo'<center><a href="javascript:show_option(\'manage\',\'substation_vehicle\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 

	//include_once("manage_vehicle.php");
?>
        
