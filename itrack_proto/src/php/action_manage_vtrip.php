<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$DEBUG=0;
	
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['local_account_ids'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);

	$old_value= Array();		$new_value=Array();			$field_name=Array();		$table_name="station"; 
  
	if($action_type1=="assign")
	{
		$local_vehicle_ids = $_POST['vehicle_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);
		//echo "vehicle_size=".$vehicle_size."<br>";		
		$local_station_id = $_POST['station_id'];
		
		$query_string1="INSERT INTO vtrip_assignment(vehicle_id,base_station_id,create_id,create_date,status) VALUES";

		for($i=0;$i<$vehicle_size;$i++)
		{
			//echo "local_geofenc_id=".$local_geofenc_id[$i]."<br>";
			if($i==$vehicle_size-1)
			{
				$query_string2.="($local_vehicle_ids[$i],$local_station_id,$account_id,'$date',1);";
			}
			else
			{
				$query_string2.="($local_vehicle_ids[$i],$local_station_id,$account_id,'$date',1),";
			}
		}
		$query=$query_string1.$query_string2; 
		//echo "query=".$query;
	
		if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Assigned";} 		
	}
	else if($action_type1=="deassign")
	{
		$local_vehicle_ids = $_POST['vehicle_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_vehicle_ids=explode(",",$local_vehicle_ids);
		$vehicle_size=sizeof($local_vehicle_ids);	
		
		$old_value= Array();   $new_value=Array();   $field_name=Array(); $table_name="station_assignment";

    for($i=0;$i<$vehicle_size;$i++)
		{	
      $local_all_ids=explode(":",$local_vehicle_ids[$i]);
      $vehicle_id = $local_all_ids[0];
      $station_id = $local_all_ids[1];
        			
      $query="UPDATE vtrip_assignment SET status=0,edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$vehicle_id' AND base_station_id='$station_id' AND status=1";
			//echo $query;
      $result=mysql_query($query,$DbConnection); 
			//$station_id1= $veh_geo_id2[1];
			$old_value[]= "1";
			$new_value[]="0";
			$field_name[]= "status";         
			$ret_result=track_table($local_vehicle_ids[$i],$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		}
		
		if($ret_result=="success" && $result){$flag=1;$action_perform="De-Assigned";} 	
	}
 
	if($flag==1)
	{
		$msg = "Station ".$action_perform." Successfully";
		$msg_color = "green";				
	}
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
  
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'vtrip\');" class="back_css">&nbsp;<b>Back</b></a></center>';                 
  
?>
        