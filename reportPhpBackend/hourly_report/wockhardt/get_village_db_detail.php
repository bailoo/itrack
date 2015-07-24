<?php
function get_village_db_detail()
{
	global $DbConnection;
	global $account_id;
	global $vehicle_id;
	global $vehicle_name;
	global $imei;
	
	global $current_date1;
	global $current_date2;
	global $date_from;
	global $date_to;
	global $min_operation_time;
	global $max_operation_time;
	global $by_day;
	global $day;		
	global $location_name;
	global $geo_point;
	global $expected_vl_halt_duration;
	global $min_halt_time;
	global $max_halt_time;	
	global $base_station_id;
	global $base_station_name;
	global $base_station_coord;
	//global $base_station_expected_deptime;
	//global $base_station_expected_arrtime;
				
	$vehicle_id_str = "";

	//echo "\nSizeVID=".sizeof($vehicle_id);
	for($i=0;$i<sizeof($vehicle_id);$i++)
	{
	  //echo "\nVID=".$vehicle_id[$i];
	  $vehicle_id_str = $vehicle_id_str.$vehicle_id[$i].",";
	}
	$vehicle_id_str = substr($vehicle_id_str, 0, -1);
	//echo "\nVIDSTR=".$vehicle_id_str;
	//##### READ MAIN MASTER FILE
	$query = "SELECT schedule_assignment.vehicle_id, schedule_assignment.min_operation_time, ".
			"schedule_assignment.max_operation_time, schedule_assignment.date_from, schedule_assignment.date_to,".
			"schedule_assignment.by_day, schedule_assignment.day, schedule_assignment.location_id, schedule_assignment.base_station_id,".
			"schedule_assignment.min_halt_time, schedule_assignment.max_halt_time FROM ".
			"schedule_assignment, vehicle_assignment WHERE vehicle_assignment.vehicle_id = schedule_assignment.vehicle_id AND ".
			"vehicle_assignment.vehicle_id IN($vehicle_id_str) AND ".
			"schedule_assignment.date_from <='$current_date1' AND schedule_assignment.date_to >= '$current_date2' AND ".
			"schedule_assignment.status=1 AND vehicle_assignment.status=1";
			
			 // AND vehicle_assignment.device_imei_no='359231039587421'
	//echo "\nQUERY_MAIN=".$query."\n";
	//echo "\nDBCon=".$DbConnection;
	$result = mysql_query($query,$DbConnection);
	
	while($row = mysql_fetch_object($result))
	{
		$vid_tmp = $row->vehicle_id;
	  
		for($i=0;$i<sizeof($vehicle_id);$i++)
		{	  	  		  
			if($vid_tmp == $vehicle_id[$i])
			{				
				$date_from_tmp = $row->date_from;
				$date_to_tmp = $row->date_to;
				$min_operation_time_tmp = $row->min_operation_time;
				$max_operation_time_tmp = $row->max_operation_time;
				$min_halt_time_tmp = $row->min_halt_time;
				$max_halt_time_tmp = $row->max_halt_time;
				//$expected_vl_halt_duration[$vid_tmp][] = $min_halt_time_tmp.",".$max_halt_time_tmp;
				$by_day_tmp = $row->by_day;
				$day_tmp = $row->day;  
				$location_id_tmp = $row->location_id;
				$base_station_id_tmp = $row->base_station_id;
				  
				$loc1 = explode(",",$location_id_tmp);
				$minhalt1 = explode(",",$min_halt_time_tmp);
				$maxhalt1 = explode(",",$max_halt_time_tmp);
				
				for($j=0;$j<sizeof($loc1);$j++)
				{
					$query2 = "SELECT location_name,geo_point FROM schedule_location WHERE location_id ='$loc1[$j]' AND status=1";
					//echo "\nGEO_POINT_QUERY=".$query2;
					$result2 = mysql_query($query2,$DbConnection);
					if($row2 = mysql_fetch_object($result2))
					{
						$location_name[$vid_tmp][] = $row2->location_name; 
						$geo_point[$vid_tmp][] = $row2->geo_point;
						
						//STORE OTHER VARIABLES
						$date_from[$vid_tmp][] = $date_from_tmp;
						$date_to[$vid_tmp][] = $date_to_tmp;
						$min_operation_time[$vid_tmp][] = $min_operation_time_tmp;
						$max_operation_time[$vid_tmp][] = $max_operation_time_tmp;
						$min_halt_time[$vid_tmp][] = $minhalt1[$j];
						$max_halt_time[$vid_tmp][] = $maxhalt1[$j];
						//$expected_vl_halt_duration[$vid_tmp][] = $min_halt_time_tmp.",".$max_halt_time_tmp;
						$by_day[$vid_tmp][] = $by_day_tmp;
						$day[$vid_tmp][] = $day_tmp;  
						//$location_id_tmp = $location_id_tmp;
						//$base_station_id_tmp = $row->base_station_id;						
					} 

					$query3 = "SELECT landmark_id,landmark_name,landmark_coord FROM landmark WHERE landmark_id ='$base_station_id_tmp' AND status=1";
					//echo $query3."\n";
					$result3 = mysql_query($query3,$DbConnection);
					if($row3 = mysql_fetch_object($result3))
					{
						$base_station_id[$vid_tmp][] = $row3->landmark_id; 
						$base_station_name[$vid_tmp][] = $row3->landmark_name;
						$base_station_coord[$vid_tmp][] = $row3->landmark_coord;
						//$base_station_expected_deptime[$vid_tmp][] = $row3->landmark_id;
						//$base_station_expected_arrtime[$vid_tmp][] = $row3->landmark_id;
						//echo "\nBSCOORD=".$base_station_coord[$y];  
					}					
				}
				break;
			}
		}
	}
}
?>  