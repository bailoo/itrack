<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$DEBUG=0;
	
	$action_type1 = $_POST['action_type'];
	$old_value= Array();		$new_value=Array();			$field_name=Array();		$table_name="station"; 
  
	if($action_type1=="add") 
	{ 
    	$local_account_ids = $_POST['local_account_ids'];
	    $local_account_ids=explode(",",$local_account_ids);
	    $account_size=sizeof($local_account_ids);
      
      $station_name1 = trim($_POST['station_name']);
  
  		$station_coord1 = $_POST['station_coord'];
  		$route_id1 = $_POST['route_id'];
                $station_no1 = $_POST['station_no'];

  		$station_coord1=trim($station_coord1);
      
      $type = $_POST['file_id'];    
  
  		$query ="select Max(sno)+1 as serial_no from station";  ///// for auto increament of geo_id ///////////   
  		$result=mysql_query($query,$DbConnection);
  		$row=mysql_fetch_object($result);
  		$max_no= $row->serial_no;
  		if($max_no==""){$max_no=1;}
  
  		$query_string1="INSERT INTO station(user_account_id,station_id,customer_no,station_name,station_coord,type,status,create_id,create_date) VALUES";
  
  		for($i=0;$i<$account_size;$i++)
  		{
  			//echo "accout_id=".$local_account_ids[$i]."<br>";
  			if($i==$account_size-1)
  			{
  				$query_string2.="('$local_account_ids[$i]','$max_no','$station_no1','$station_name1','$station_coord1','$type','1','$account_id','$date');";
  			}
  			else
  			{
  				$query_string2.="('$local_account_ids[$i]','$max_no','$station_no1','$station_name1','$station_coord1','$type','1','$account_id','$date'),";
  			}
  		}
  		$query=$query_string1.$query_string2; 
  		//echo "query=".$query;
  		//}
  		if($DEBUG ==1 )print_query($query);     
  		$result=mysql_query($query,$DbConnection);          	  
  		if($result){$flag=1;$action_perform="Added";}   
	} 
	else if($action_type1=="edit")
	{		
    $geo_id1 = $_POST['station_id'];    
		$geo_name1 =trim($_POST['station_name']);
		$customer_no1 =trim($_POST['customer_no']);
		$distance_variable1 =trim($_POST['distance_variable']);
		
		$new_value[]=$geo_name1;
		/* $query="SELECT geo_name FROM geofence WHERE create_id='$account_id' AND geo_name='$geo_name1' AND user_account_id='$local_account_ids' AND status='1'";
		if($DEBUG ==1 )print_query($query);
		$result=mysql_query($query,$DbConnection);
		$num_rows=mysql_num_rows($result);      
		if($num_rows1>0)
		{$flag=1;}
		else
		{  */    
		$geo_coord1 =$_POST['station_coord'];     
		$new_value[]=$geo_coord1;           

		$query="SELECT * FROM station where station_id='$geo_id1' AND status='1'";
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$geo_name2=$row->geo_name;
		$old_value[] =$geo_name2;
		$field_name[]="station_name";
		$geo_coord2=$row->geo_coord;         
		$old_value[] = $geo_coord2;
		$field_name[]="station_coord";
		//$distance_variable2 =$row->distance_variable;
		//$old_value[] = $distance_variable2;
    //$field_name[]="distance_variable"; 

		$query="UPDATE station SET station_name='$geo_name1',station_coord='$geo_coord1',customer_no='$customer_no1',distance_variable='$distance_variable1',edit_id='$account_id',edit_date='$date' WHERE station_id='$geo_id1'";
		//echo "query=".$query;
		$result=mysql_query($query,$DbConnection); 

		$ret_result=track_table($geo_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
		if($ret_result=="success" && $result){$flag=1;$action_perform="Updated";} 
		//}     
	}
	else if ($action_type1=="edit_dist_var")
	{
    $local_station_ids = $_POST['station_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_station_ids=explode(",",$local_station_ids);
		$station_size=sizeof($local_station_ids);
		$distance_variable1 = $_POST['distance_variable'];
		
		$query_string1="UPDATE station SET distance_variable='$distance_variable1' WHERE station_id IN(";

		$update_str = "";
    for($i=0;$i<$station_size;$i++)
		{
			//echo "local_geofenc_id=".$local_geofenc_id[$i]."<br>";
			if($i==0)
			{
				$update_str= $update_str.$local_station_ids[$i];
			}
			else
			{
				$update_str= $update_str.",'".$local_station_ids[$i]."'";        
			}
		}
		$query=$query_string1.$update_str.")"; 
		
		//echo $query;		
    $result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=3;$action_perform="Distance Variable Updated";} 		
		
	} 	
	else if ($action_type1=="delete")
	{
    $local_station_ids = $_POST['station_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_station_ids=explode(",",$local_station_ids);
		$station_size=sizeof($local_station_ids);
		
		$query_string1="UPDATE station SET status=0 WHERE station_id IN(";

		$del_str = "";
    for($i=0;$i<$station_size;$i++)
		{
			//echo "local_geofenc_id=".$local_geofenc_id[$i]."<br>";
			if($i==0)
			{
				$del_str= $del_str.$local_station_ids[$i];
			}
			else
			{
				$del_str= $del_str.",'".$local_station_ids[$i]."'";        
			}
		}
		$query=$query_string1.$del_str.")"; 
		
		//echo $query;		
    $result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=2;$action_perform="Deleted";} 		
		
	}
	else if($action_type1=="assign")
	{
		$local_station_ids = $_POST['station_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_station_ids=explode(",",$local_station_ids);
		$station_size=sizeof($local_station_ids);
		//echo "vehicle_size=".$vehicle_size."<br>";
		$local_vehicle_id = $_POST['vehicle_id'];
		
		$query_string1="INSERT INTO station_assignment(station_id,vehicle_id,create_id,create_date,status) VALUES";

		for($i=0;$i<$station_size;$i++)
		{
			//echo "local_geofenc_id=".$local_geofenc_id[$i]."<br>";
			if($i==$station_size-1)
			{
				$query_string2.="($local_station_ids[$i],$local_vehicle_id,$account_id,'$date',1);";
			}
			else
			{
				$query_string2.="($local_station_ids[$i],$local_vehicle_id,$account_id,'$date',1),";
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
        			
      $query="UPDATE station_assignment SET status=0,edit_id='$account_id',edit_date='$date' WHERE vehicle_id='$vehicle_id' AND station_id='$station_id' AND status=1";
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
	else if($flag==2)
	{
		$msg = "Station Deleted Successfully";
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
  echo'<center><a href="javascript:show_option(\'manage\',\'station\');" class="back_css">&nbsp;<b>Back</b></a></center>';                 
  
?>
        
