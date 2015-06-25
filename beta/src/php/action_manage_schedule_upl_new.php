<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$DEBUG=0;	
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['account_id_local'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);	
	$assign_flag = 0;

	$old_value= Array();		
	$new_value=Array();			
	$field_name=Array();		
	$table_name="upl_locations"; 
	//echo "action_type=".$action_type1."<br>";
  
	if($action_type1=="add") 
	{ 
		$location_name1 = trim($_POST['location_name']);
		$geo_point1 = $_POST['geo_point'];

		$query ="select Max(sno)+1 as seiral_no from schedule_location_upl";  ///// for auto increament of landmark_id ///////////   
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$max_no= $row->seiral_no;
		if($max_no==""){$max_no=1;}

		$query_string1="INSERT INTO schedule_location_upl(account_id,location_id,location_name,geo_point,status,create_id,create_date) VALUES";

		for($i=0;$i<$account_size;$i++)
		{
			//echo "accout_id=".$local_account_ids[$i]."<br>";
			if($i==$account_size-1)
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$location_name1','$geo_point1','1','$account_id','$date');";
			}
			else
			{
				$query_string2.="('$local_account_ids[$i]','$max_no','$location_name1','$geo_point1','1','$account_id','$date'),";
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
		$location_id1 = trim($_POST['location_id']);
		$location_name1 = trim($_POST['location_name']);
		$geo_point1 = $_POST['geo_point'];		
		$new_value[]=$location_name1;
		$new_value[]=$geo_point1;           

		$query="SELECT * FROM schedule_location_upl where location_id='$location_id1' AND status='1'";
		echo "select_query=".$query."<br>";
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_object($result);
		$location_name2=$row->location_name;
		$old_value[] =$location_name2;
		$field_name[]="location_name";
		$geo_point2=$row->geo_point;         
		$old_value[] = $geo_point2;
		$field_name[]="geo_point"; 

		$query="UPDATE schedule_location_upl SET location_name='$location_name1',geo_point='$geo_point1',edit_id='$account_id',edit_date='$date' WHERE location_id='$location_id1'";
		echo "query=".$query."<br>";
		$result=mysql_query($query,$DbConnection); 
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
		$query="UPDATE schedule_location_upl SET edit_id='$account_id',edit_date='$date',status='0' WHERE location_id='$location_id1' AND status='1'"; 
		$result=mysql_query($query,$DbConnection);    
		$old_value[]="1";
		$new_value[]="0";
		$field_name[]="status";     
		$ret_result=track_table($location_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
		if($ret_result=="success"  && $result)
		{
			$flag=2;
			$action_perform="Deleted";
			
		}
	}
	else if($action_type1=="assign")
	{
		$day_str_1=substr($day_str,0,-1);
		$Query="SELECT * FROM schedule_assignment_upl WHERE by_day='0' AND vehicle_id='$vehicle_id' AND status=1 AND (((date_from<='$date_from') AND (date_to>='$date_from'))".
		" || ((date_from<='$date_to') AND (date_to>='$date_to'))) AND status=1";
		//echo "query=".$Query."<br>";
		$Result=mysql_query($Query,$DbConnection);
		$NumRows=mysql_num_rows($Result);
		if($NumRows>0)
		{
			$flag=3;			
		}
		else
		{
			if($by_day==0)
			{
				$Query="SELECT * FROM schedule_assignment_upl WHERE vehicle_id='$vehicle_id' AND (((date_from<='$date_from') AND (date_to>='$date_from')) OR ".
				"((date_from<='$date_to') AND (date_to>='$date_to'))) AND status=1";
				$Result=mysql_query($Query,$DbConnection);
				$NumRows=mysql_num_rows($Result);
				if($NumRows>0)
				{
					$flag=3;					
				}
			}
			else
			{
				$Query="SELECT * FROM schedule_assignment_upl WHERE by_day='1' AND vehicle_id='$vehicle_id' AND (((date_from<='$date_from') AND (date_to>=".
				"'$date_from')) OR ((date_from<='$date_to') AND (date_to>='$date_to'))) AND status=1";
				$Result=mysql_query($Query,$DbConnection);
				$NumRows=mysql_num_rows($Result);
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
			/*echo "min_max_halt_locations_1=".$min_max_halt_locations_1."min_halt_time=".$min_halt_time."max_halt_time=".$max_halt_time."
			intermediate_time_1=".$intermediate_time_1."<br>";*/					 
			/*$Query="INSERT INTO schedule_assignment(vehicle_id,location_id,base_station_id,date_from,date_to,by_day,day,min_operation_time,max_operation_time,rest_time,".
				   "min_halt_time,max_halt_time,min_distance_travelled,max_distance_travelled,Intermediate_halt_time,nonpoi_halt_time,create_id,create_date,status) VALUES(".
				   "'$vehicle_id','$min_max_halt_locations_1','$base_station_id','$date_from','$date_to','$by_day','$day_str_1','$min_operation_time','$max_operation_time',".
				   "'$allow_rest_time','$min_halt_time','$max_halt_time','$minimum_distance','$maximum_distance','$intermediate_time_1','$nonpoi_halt_time','$account_id','$date','1')";*/
			$Query="INSERT INTO schedule_assignment_upl(vehicle_id,location_id,base_station_id,date_from,date_to,by_day,day,min_operation_time,max_operation_time,".
				   "min_halt_time,max_halt_time,min_distance_travelled,max_distance_travelled,Intermediate_halt_time,nonpoi_halt_time,create_id,create_date,status) VALUES(".
				   "'$vehicle_id','$min_max_halt_locations_1','$base_station_id','$date_from','$date_to','$by_day','$day_str_1','$min_operation_time','$max_operation_time',".
				   "'$min_halt_time','$max_halt_time','$minimum_distance','$maximum_distance','$intermediate_time_1','$nonpoi_halt_time','$account_id','$date','1')";
			//echo "query=".$Query."<br>";
			
			$Result=mysql_query($Query,$DbConnection);
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
		echo'<center><a href="javascript:show_option(\'manage\',\'schedule_upl\');" class="back_css">&nbsp;<b>Back</b></a></center>';
	}                 
  
?>
        