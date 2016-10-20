<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');
	$DEBUG=0;
	
	$action_type1 = $_POST['action_type'];  
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
		   
  
  		$max_no= getStationPersonMaxSerial($DbConnection);
  		if($max_no=="")
		{
			$max_no=1;
		}
     
  		$result=insertStationPerson($account_size,$local_account_ids,$max_no,$station_no1,$station_name1,$station_coord1,$type1,1,$account_id,$date,$DbConnection);          	  
  		if($result)
		{
			$flag=1;
			$action_perform="Added";
		}   
	} 
	else if($action_type1=="edit")
	{		
		$geo_id1 = $_POST['station_id'];    
		$geo_name1 =trim($_POST['station_name']);
		$customer_no1 =trim($_POST['customer_no']);
		$distance_variable1 =trim($_POST['distance_variable']);
		
			   
		$geo_coord1 =$_POST['station_coord'];  
		$result=updateStationPerson($geo_name1,$geo_coord1,$customer_no1,$distance_variable1,$account_id,$date,$geo_id1,$DbConnection);
		if($result)
		{
			$flag=1;
			$action_perform="Updated";
		} 
		//}     
	}
	else if ($action_type1=="edit_dist_var")
	{
		$local_station_ids = $_POST['station_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_station_ids=explode(",",$local_station_ids);
		$station_size=sizeof($local_station_ids);
		$distance_variable1 = $_POST['distance_variable'];
					
		$result=updateStationPerson2($station_size,$distance_variable1,$date,$local_station_ids,$DbConnection);          	  
		if($result)
		{
			$flag=3;
			$action_perform="Distance Variable Updated";
		} 		
		
	} 	
	else if ($action_type1=="delete")
	{
		$local_station_ids = $_POST['station_ids'];
		//echo "local_vehicle_ids=".$local_vehicle_ids."<br>";
		$local_station_ids=explode(",",$local_station_ids);
		$station_size=sizeof($local_station_ids);		
				
		//echo $query;		
		$result=deletePersonStation($station_size,$local_station_ids,$DbConnection);          	  
		if($result)
		{
			$flag=2;
			$action_perform="Deleted";
		} 		
		
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
  echo'<center><a href="javascript:show_option(\'manage\',\'visit\');" class="back_css">&nbsp;<b>Back</b></a></center>';                 
  
?>
        
