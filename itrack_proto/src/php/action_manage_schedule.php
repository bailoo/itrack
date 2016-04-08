<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');
	$DEBUG=0;	
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['account_id_local'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);	
	$assign_flag = 0;

	//echo "action_type=".$action_type1."<br>";
  
	if($action_type1=="add") 
	{ 
		$location_name1 = trim($_POST['location_name']);
		$geo_point1 = $_POST['geo_point'];
		
		$max_no= getScheduleAssignmentMaxSerial($DbConnection);
		if($max_no=="")
		{
			$max_no=1;
		}		    
		$result=insertScheduleLocation($account_size,$local_account_ids,$max_no,$location_name1,$geo_point1,1,$account_id,$date,$DbConnection);          	  
		if($result)
		{
			$flag=1;
			$action_perform="Added";
		}     
	}
  
	else if($action_type1=="edit")
	{
		$location_id1 = trim($_POST['location_id']);
		$location_name1 = trim($_POST['location_name']);
		$geo_point1 = $_POST['geo_point'];		
		
		$result=updateScheduleLocation($location_name1,$geo_point1,$account_id,$date,$location_id1,$DbConnection); 
		if($result)
		{
			$flag=1;
			$action_perform="Updated";
		} 
	}
	else if ($action_type1=="delete")
	{
		// $type="edit_delete";
		$location_id1 = $_POST['location_id'];    
		$result=deleteScheduleLocation($account_id,$date,0,$location_id1,1,$DbConnection); 		
		if($result)
		{
			$flag=2;
			$action_perform="Deleted";
			
		}
	}
	else if($action_type1=="assign")
	{
		$day_str_1=substr($day_str,0,-1);		
		$NumRows=getDataCheckInScheduleAssignment($vehicle_id,$date_from,$date_to,$DbConnection);
		if($NumRows>0)
		{
			$flag=3;			
		}
		else
		{
			if($by_day==0)
			{
				$NumRows=getDataCheckInScheduleAssignment2($vehicle_id,$date_from,$date_to,$DbConnection);
				if($NumRows>0)
				{
					$flag=3;					
				}
			}
			else
			{
				$NumRows=getDataCheckInScheduleAssignment3($vehicle_id,$date_from,$date_to,$DbConnection);
				if($NumRows>0)
				{
					while($Row=mysql_fetch_object($Result))
					{
						$days[]=$Row->day;
					}				
				}
				$Status = MatchDays($days,$day_str_1);
				if($Status==TRUE)
				{
					$flag=3;				
				}				
			}
		}
		if($flag==3)
		{
			$action_perform="Assignment already added for this vehicle between enter dates.";	
		}
		else
		{
			$min_max_halt_locations_1=substr($min_max_halt_locations,0,-1);
			$min_halt_time=substr($min_halt_time,0,-1);
			$max_halt_time=substr($max_halt_time,0,-1);
			$intermediate_time_1=substr($intermediate_time,0,-1);
						
			$Result=insertScheduleAssignment($vehicle_id,$min_max_halt_locations_1,$base_station_id,$date_from,$date_to,$by_day,$day_str_1,$min_operation_time,$max_operation_time,$min_halt_time,$max_halt_time,$minimum_distance,$maximum_distance,$intermediate_time_1,$nonpoi_halt_time,$account_id,$date,1,$DbConnection);
			if($Result)
			{
				$flag=4;
				$action_perform="Assignment Added Successfully";
			}
		}						
	}
	
	function MatchDays($day_arr,$day_str_arr)
	{
		for($i=0;$i<sizeof($day_arr);$i++)
		{	
			$day_arr_1=explode(",",$day_arr[$i]);
			$day_str_arr_1=explode(",",$day_str_arr);	
			for($j=0;$j<sizeof($day_str_arr_1);$j++)
			{		
				for($k=0;$k<sizeof($day_arr_1);$k++)
				{
					if($day_str_arr_1[$j]==$day_arr_1[$k])
					{
						return TRUE;
						break;
					}
				}			
			}					
		}	
	}
	
 
	if($flag==1)
	{
		$msg = "Location ".$action_perform." Successfully";
		$msg_color = "green";				
	}
	else if($flag==2)
	{
		$msg = "Location Area ".$action_perform." Successfully";
		$msg_color = "green";	
	}
	else if($flag==3)
	{
		$msg=$action_perform;
		$msg_color = "red";	
	}
	else if($flag==4)
	{
		$msg=$action_perform;
		$msg_color = "green";	
	}
	else
	{
		if(!$assign_flag)
		{
			$msg = "Sorry! Unable to process request.";
			$msg_color = "red";
		}		
	}
  
	if(!$assign_flag)
	{
		echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
		echo'<center><a href="javascript:show_option(\'manage\',\'schedule\');" class="back_css">&nbsp;<b>Back</b></a></center>';
	}                 
  
?>
        