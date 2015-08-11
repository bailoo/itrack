<?php  
	/*//include_once('Hierarchy.php');		
	include_once('util_session_variable.php');	
	include_once('util_php_mysql_connectivity.php');	
	include_once('user_type_setting.php');
	include_once('coreDb.php');
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
		$chillplant = $_POST['chillplant'];		
		$transporter = $_POST['transporter'];	
			
		$default_sno=getDefaultSnoTransChillingPlantAssign($transporter,1,$DbConnection);
		if($default_sno!="")
		{
			//$row=mysql_fetch_object($result);
			//$default_sno = $row->sno;								
			$result = updateTransChillingPlantAssingment($chillplant,$account_id,$date,$default_sno,$transporter,$DbConnection);
			if($result)
			{
				$message="Assigned action performed successfully";
			}
			else
			{
				$message="Unable to process this request";
			}
		}
		else
		{
			$query = "INSERT INTO transporter_chilling_plant_assignment (customer_no,account_id,status,create_id,create_date) VALUES('$chillplant',$transporter,1,$account_id,'$date')";
			$result = mysql_query($query, $DbConnection);
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
	//########## ASSIGN CLOSED
	
	//######### DEASSIGN OPENS
	else if($post_action_type == "deassign")
	{		
		$transporter = $_POST['transporter'];		
		$result = updateTransChillingPlantAssign(0,$transporter,1,$DbConnection);
		if($result)
		{
			$message="De-Assigned action performed successfully";
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

	echo'<center><a href="javascript:show_option(\'manage\',\'default_chilling_plant\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 

	//include_once("manage_vehicle.php");*/
?>
        
<?php  
	include_once('Hierarchy.php');		
	include_once('util_session_variable.php');	
	include_once('util_php_mysql_connectivity.php');	
	include_once('user_type_setting.php');
        include_once('coreDb.php');
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
		$chillplant = $_POST['chillplant'];		
		$transporter = $_POST['transporter'];	
		$default_sno=getDefaultSnoTransChillingPlantAssign($transporter,1,$DbConnection);
		if($default_sno!="")
		{
			//$row=mysql_fetch_object($result);
			//$default_sno = $row->sno;								
			$result = updateTransChillingPlantAssingment($chillplant,$account_id,$date,$default_sno,$transporter,$DbConnection);
			if($result)
			{
				$message="Assigned action performed successfully";
			}
			else
			{
				$message="Unable to process this request";
			}
		}
                /*
		$query ="SELECT	* FROM transporter_chilling_plant_assignment WHERE account_id=$transporter  AND status=1";
		$result = mysql_query($query, $DbConnection);
		$numrows = mysql_num_rows($result);	
		if($numrows!=0)
		{
			$row=mysql_fetch_object($result);
			$default_sno = $row->sno;
			$query ="UPDATE transporter_chilling_plant_assignment SET  customer_no='$chillplant' ,edit_id=$account_id ,edit_date='$date' WHERE sno = $default_sno AND account_id=$transporter ";					
			$result = mysql_query($query, $DbConnection);
			if($result)
			{$message="Assigned action performed successfully";}
			else
			{$message="Unable to process this request";}
		}*/
		else
		{
			$query = "INSERT INTO transporter_chilling_plant_assignment (customer_no,account_id,status,create_id,create_date) VALUES('$chillplant',$transporter,1,$account_id,'$date')";
			$result = mysql_query($query, $DbConnection);
			if($result)
			{$message="Assigned action performed successfully";}
			else
			{$message="Unable to process this request";}
		}
		
		 
		
	} 
	//########## ASSIGN CLOSED
	
	//######### DEASSIGN OPENS
	else if($post_action_type == "deassign")
	{		
		/*$transporter = $_POST['transporter'];
		
		$query ="UPDATE transporter_chilling_plant_assignment SET status=0  WHERE  account_id=$transporter and status=1";		
			//echo $query;
			$result = mysql_query($query, $DbConnection);
			if($result)
			{$message="De-Assigned action performed successfully";}
			else
			{$message="Unable to process this request";}*/
                $transporter = $_POST['transporter'];		
		$result = updateTransChillingPlantAssign(0,$transporter,1,$DbConnection);
		if($result)
		{
			$message="De-Assigned action performed successfully";
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

	echo'<center><a href="javascript:show_option(\'manage\',\'default_chilling_plant\');" class="back_css">&nbsp;<b>Back</b></a></center>'; 

	//include_once("manage_vehicle.php");
?>
        
