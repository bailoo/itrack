<?php
	set_time_limit(3600);
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');
	$DEBUG=0;	
	$action_type1 = $_POST['action_type'];
	$old_value= Array();
	$new_value=Array();	
	$field_name=Array();
	$table_name="route_assignment2"; 
	//$local_account_id1 = $_POST['local_account_id'];	
	$local_account_id1=$account_id;
	/* checking if local_account_id1 is substation type */
	
	
	
	$row_verify=GetAccountInfo($local_account_id1,1,$DbConnection);

	$user_type=$row_verify[0];
	if($user_type=="substation")
	{
		$local_account_id1=$account_id;
	}

	if($action_type1=="assign")
	{
		//$shift1 = $_POST['shift'];
		//echo "<br>Shift=".$shift1;		
		$vehicles_str = substr($_POST['all_vehicles'], 0, -1);
		$vehicle_arr = explode(',',$vehicles_str);
		
		for($i=0;$i<sizeof($vehicle_arr);$i++)
		{
			$vehicle_db=getVehicleNameByVid($vehicle_arr[$i],1,$DbConnection);	
			
			$numrows=getNumRowRouteAssignment2($local_account_id1,$vehicle_db,1,$DbConnection);
			if($numrows==0)
			{
				insertRoutAssignment2Detail($vehicle_db,$local_account_id1,$account_id,$date,1,$DbConnection);				
			}	
		}
		
		$local_route_vehicle_ev = substr($_POST['route_vehicle_ev'], 0, -1);
		
		//echo "loc=".$local_route_vehicle_ev."<br>";
		$route_vehicle_ev = explode(',',$local_route_vehicle_ev);	
				
		if(count($route_vehicle_ev) > 0)
		{		
			//echo "<br>SHIFT EVENING";				
			//echo "\nSizeRoute=".sizeof($route_vehicle);
			for($i=0;$i<sizeof($route_vehicle_ev);$i++)
			{
				$str1 = explode(':',$route_vehicle_ev[$i]);
				$vehicleId = trim($str1[0]);
				//echo "vehicleId=".$vehicleId."<br>";
				$route_name = trim($str1[1]);
				$remark = trim($str1[2]);				
				$vehicle_db=getVehicleNameByVid($vehicleId,1,$DbConnection);				
				$numrows_check=getNumRowERouteAssignment2($local_account_id1,$vehicle_db,$route_name,$remark,1,$DbConnection);
				//echo "<br>numrows=".$numrows;
				if($numrows_check == 0)
				{
					updateERouteAssignment2($route_name,$remark,$date,$account_id,$local_account_id1,$vehicle_db,1,$DbConnection);					
				}
			}				
		}
		
		$local_route_vehicle_mor = substr($_POST['route_vehicle_mor'], 0, -1);
		$route_vehicle_mor = explode(',',$local_route_vehicle_mor);
		
		if(count($route_vehicle_mor) > 0)
		{		
			//echo "<br>SHIFT EVENING";				
			//echo "\nSizeRoute=".sizeof($route_vehicle);
			for($i=0;$i<sizeof($route_vehicle_mor);$i++)
			{
				$str1 = explode(':',$route_vehicle_mor[$i]);
				$vehicleId = trim($str1[0]);
				$route_name = trim($str1[1]);
				$remark = trim($str1[2]);		
							
				$vehicle_db=getVehicleNameByVid($vehicleId,1,$DbConnection);					
				$numrows_check=getNumRowMRouteAssignment2($local_account_id1,$vehicle_db,$route_name,$remark,1,$DbConnection);
				//echo "<br>numrows=".$numrows;
				if($numrows_check == 0)
				{
					updateMRouteAssignment2($route_name,$remark,$date,$account_id,$local_account_id1,$vehicle_db,1,$DbConnection);					
				}
			}			
		}
		$flag=1;
		$action_perform="Assigned";
	}
	/*else if($action_type1=="deassign")
	{
		$local_vehicle_ids = $_POST['vehicle_route'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);	
		
		//$old_value= Array();  
		//$new_value=Array();   
		//$field_name=Array(); 
		//$table_name="route_assignment2";

    	for($i=0;$i<$vehicle_size;$i++)
		{	
	      	$local_all_ids=explode(":",$local_vehicle_ids[$i]);
	      	$vehicle_name = trim($local_all_ids[0]);
	      	$route_name = trim($local_all_ids[1]);
        			
      		$query="UPDATE route_assignment2 SET status=0,edit_id='$account_id',edit_date='$date' WHERE vehicle_name='$vehicle_name' AND route_name='$route_name' AND user_account_id='$local_account_id1' AND status=1";
			//echo $query;
      		$result=mysql_query($query,$DbConnection); 
			//$station_id1= $veh_geo_id2[1];
			//$old_value[]= "1";
			//$new_value[]="0";
			//$field_name[]= "status";         
			//$ret_result=track_table($local_vehicle_ids[$i],$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		}
		
		if($result){$flag=1;$action_perform="De-Assigned";} 	
	}*/
 
	if($flag==1)
	{
		$msg = "Route ".$action_perform." Successfully";
		$msg_color = "green";				
	}	
  	else if($flag==3)
	{
		$msg = $action_perform;
		$msg_color = "green";		
	}
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
  
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'route\');" class="back_css">&nbsp;<b>Back</b></a></center>';                 
  
?>
        
