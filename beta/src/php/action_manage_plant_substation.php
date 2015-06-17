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

	//$action_type_local;
	//echo "action_type=".$post_action_type."<br>";
  
	if($post_action_type == "assign")
	{		
		$plant_string = $_POST['plant_id'];
		//echo "account_string=".$account_string."<br>";
		$plant_id_main =explode(":",$plant_string);
		$plant_size=sizeof($plant_id_main);		
		$account_id_substation = $_POST['plant_user'];		
		//echo"vehiclesize=".$vehicle_size."<br>";
		//echo"account_id_substation=".$account_id_substation."<br>";
		for($i=0;$i<$plant_size;$i++)
		{		
			/
			$numrows =getNumRowPlanUserAssingment($plant_id_main[$i],$account_id_substation,$DbConnection);	

			if($numrows==0)
			{
				$result = insertPlantUserAssingment($plant_id_main[$i],$account_id_substation,$account_id,$date,$DbConnection);
				if($result)
				{
					$message="Assigned action performed successfully";
				}
				else
				{
					$message="Unable to process this request";
				}
			}		
					
				
		}  
			
	} 
	//########## ASSIGN CLOSED
	
	//######### DEASSIGN OPENS
	else if($post_action_type == "deassign")
	{	
		//plant_id=2&plant_user=1195&action_type=deassign
		$plant_string = $_POST['plant_id'];
		//echo "account_string=".$account_string."<br>";
		$plant_id_main =explode(":",$plant_string);
		$plant_size=sizeof($plant_id_main);		
		$account_id_substation = $_POST['plant_user'];		
		//echo"vehiclesize=".$vehicle_size."<br>";
		//echo"account_id_substation=".$account_id_substation."<br>";
		for($i=0;$i<$plant_size;$i++)
		{		
			$result =updatePlantUserAssingmnet($date,$plant_id_main[$i],$account_id_substation,$DbConnection);
						
		}
		
		if($result)
		{
			$message="DeAssigned action performed successfully";
		}
		else
		{
			$message="Unable to process this request";
		}
	} 
	//########### DEASSIGN CLOSED

	echo' <br>
			<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
				<tr>
					<td colspan="3" align="center"><b>'.$message.'</b></td>    
				</tr>
			</table>';

	echo'<center><a href="javascript:show_option(\'manage\',\'account_plant\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 

	//include_once("manage_vehicle.php");
?>
        
