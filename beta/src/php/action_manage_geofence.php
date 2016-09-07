<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');
	$DEBUG=0;
	
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);
  
	if($action_type1=="add") 
	{ 
		$geo_name1 = trim($_POST['geo_name']);		
		$geo_coord1 = $_POST['geo_coord'];
		$geo_coord1=trim($geo_coord1);    
		$geo_coord1 = base64_encode($geo_coord1); 
		
		$max_no= getGeofenceMaxSerial($DbConnection);
		if($max_no=="")
		{
			$max_no=1;
		} 
			
		$result=insertGeofence($account_size,$local_account_ids,$max_no,$geo_name1,$geo_coord1,1,$account_id,$date,$DbConnection);          	  
		if($result)
		{
			$flag=1;
			$action_perform="Added";
		}    
	  
	}
  
	else if($action_type1=="edit")
	{
		//$type="edit_delete";
		$geo_id1 = $_POST['geo_id'];    
		$geo_name1 =trim($_POST['geo_name']);				
		$geo_coord1 =base64_encode(trim($_POST['geo_coord'])); 	          
				
		$result=updateGeofence($geo_name1,$geo_coord1,$account_id,$date,$geo_id1,$DbConnection); 		
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
            
            $foundCount=checkGeofenceAssignment($geo_id1,$DbConnection);
            echo "foundCount=".$foundCount."<br>";
            exit();
            if($foundCount==0)
            {
                $result=deleteGeofence($account_id,$date,0,$geo_id1,1,$DbConnection); 		
                if($result)
                {
                    $flag=1;
                    $action_perform="Deleted";
                }
            }
            else
            {
                $flag=2;               
            }
	}
	else if($action_type1=="assign")
	{
		$local_vehicle_ids = $_POST['vehicle_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);
		//echo "vehicle_size=".$vehicle_size."<br>";
		$local_geofence_id = $_POST['geofence_id'];		
	    
		$result=assignGeofence($vehicle_size,$local_geofence_id,$local_vehicle_ids,$account_id,$date,1,$DbConnection);          	  
		if($result)
		{
			$flag=1;
			$action_perform="Assigned";
		} 		
	}
	else if($action_type1=="deassign")
	{
		$local_vehicle_ids = $_POST['vehicle_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);
		
		$result=deassignGeofence($vehicle_size,0,$account_id,$date,$local_vehicle_ids,1,$DbConnection);
		if($result)
		{
			$flag=1;
			$action_perform="De-Assigned";
		} 	
	}
 
	if($flag==1)
	{
		$msg = "Geofence ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
	else if($flag==2)
	{
		$msg = "Geofence already assigned to vehicle. De-assign first than delete.";
		$msg_color = "red";		
	}
        else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
  
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'geofence\');" class="back_css">&nbsp;<b>Back</b></a></center>';                 
  
?>
        