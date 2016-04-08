<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');
	$DEBUG=0;
	
	$action_type1 = $_POST['action_type'];
	
	if($action_type1=="add") 
	{ 
		$material_name1 = trim($_POST['material_name']);		
		$material_code1 = trim($_POST['material_code']);
		
		$countval= getRawMilkInvoiceMaterial($material_name1,$DbConnection);
		if($countval > 0)
		{
                    echo "This Material Name already Exist in Database";
		} 
		else
                {
                    $result=insertRawMilkInvoiceMaterial($material_name1,$material_code1,1,$account_id,$account_id,$date,$DbConnection);          	  
                    if($result)
                    {
                            $flag=1;
                            $action_perform="Added";
                    } 
                }
	  
	}
  
	else if($action_type1=="edit")
	{
		$snoid1 = $_POST['snoid'];    
		$material_name1 = trim($_POST['material_name']);		
		$material_code1 = trim($_POST['material_code']);
                
		$result=updateRawMilkInvoiceMaterial($material_name1,$material_code1,$account_id,$date,$snoid1,$DbConnection); 		
		if($result)
		{
			$flag=1;
			$action_perform="Updated";
		} 
		  
	}
	else if ($action_type1=="delete")
	{
		// $type="edit_delete";
		$geo_id1 = $_POST['geo_id'];
		 		
		$result=deleteGeofence($account_id,$date,0,$geo_id1,1,$DbConnection); 		
		if($result)
		{
			$flag=1;
			$action_perform="Deleted";
		}
	}
	
	if($flag==1)
	{
		$msg = "Invoice Material ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
  
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'invoice_raw_milk_material_prev\');" class="back_css">&nbsp;<b>Back</b></a></center>';                 
  
?>
        