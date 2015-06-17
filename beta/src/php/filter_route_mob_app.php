<?php

function get_route_detail($file)
{
	//echo "\nfile=".$file;
	global $lat;
	global $lng;
	global $datetime;
		
	$source_path = "demo_group/raw_data/".$file;
	//echo "\nSourcePath=".$source_path;
	$row = 1;
	if (($handle = fopen($source_path, "r")) !== FALSE) {
	
		//echo "\nFileExists";
		$file_arr[] = $file;
		
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				//echo "<p> $num fields in line $row: <br /></p>\n";
				$row++;
				for ($c=0; $c < $num; $c++) {
					//echo $data[$c] . "<br />\n";
					if($c==0)
					{
						$lat[$file][] = $data[$c];
					}
					else if($c==1)
					{
						$lng[$file][] = $data[$c];
					}
					else if($c==2)
					{
						$date_tmp = str_replace('/','-',$data[$c]);
						$datetime[$file][] = $date_tmp;
					}							
				}
			}
		fclose($handle);
	}
	
	//#### FIRST LEVEL FILTER
	//$linetowrite="";
	for($i=0;$i<sizeof($file_arr);$i++)
	{				
		//echo "<br>first=".$firstdata_flag;                                        
		$linetowrite="";
		$firstdata_flag=0;
	
		for($j=0;$j<sizeof($lat[$file_arr[$i]]);$j++)
		{			
			/*$level_1_lat[$file_arr[$i]][] = $lat[$file_arr[$i]][$j];
			$level_1_lng[$file_arr[$i]][] = $lng[$file_arr[$i]][$j];
			$level_1_datetime[$file_arr[$i]][] = $datetime[$file_arr[$i]][$j];*/
			
			if($firstdata_flag==0)
			{					
				$firstdata_flag = 1;

				$lat1 = $lat[$file_arr[$i]][$j];
				$lng1 = $lng[$file_arr[$i]][$j];
				$last_time1 = $datetime[$file_arr[$i]][$j];
				$latlast = $lat[$file_arr[$i]][$j];
				$lnglast =  $lng[$file_arr[$i]][$j];
				//$linetowrite. = $lat[$file_arr[$i]][$j].",".$lng[$file_arr[$i]][$j];
				$level_1_lat[$file_arr[$i]][] = $lat[$file_arr[$i]][$j];
				$level_1_lng[$file_arr[$i]][] = $lng[$file_arr[$i]][$j];
				$level_1_datetime[$file_arr[$i]][] = $datetime[$file_arr[$i]][$j];
				
//				$linetowrite.= $lat[$file_arr[$i]][$j].",".$lng[$file_arr[$i]][$j].",".$datetime[$file_arr[$i]][$j]."\n";
				//echo "<br>FirstData:".$date_secs1." ".$time1;                 	
			}           	
			//echo "<br>k2=".$k2."<br>";		
			else
			{                           																		                                      													      					
				$lat2 = $lat[$file_arr[$i]][$j];      				        					
				$lng2 = $lng[$file_arr[$i]][$j]; 
				$distance = calculate_distance($lat1, $lat2, $lng1, $lng2);
				$tmp_time_diff1 = (double)(strtotime($datetime[$file_arr[$i]][$j]) - strtotime($last_time1)) / 3600;

				$distance1=calculate_distance($latlast, $lat2, $lnglast, $lng2);
				if($tmp_time_diff1>0)
				{
					$tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
					$last_time1 = $datetime[$file_arr[$i]][$j];
					$latlast = $lat2;
					$lnglast =  $lng2;
				}
				$tmp_time_diff = ((double)( strtotime($datetime[$file_arr[$i]][$j]) - strtotime($last_time) )) / 3600;									        
			 
				//echo "<br>Speed=".$tmp_speed." ,Distance=".$distance." ,tmp_time_diff=".$tmp_time_diff;
				if($tmp_speed<500.0 && $distance>0.05 && $tmp_time_diff>0.0)
				{
					//echo "<br>>50 meters";
					$lat1 = $lat2;
					$lng1 = $lng2;
					$last_time = $datetime[$file_arr[$i]][$j];
					
					//$linetowrite. = $lat[$file_arr[$i]][$j].",".$lng[$file_arr[$i]][$j];
					$level_1_lat[$file_arr[$i]][] = $lat[$file_arr[$i]][$j];
					$level_1_lng[$file_arr[$i]][] = $lng[$file_arr[$i]][$j];
					$level_1_datetime[$file_arr[$i]][] = $datetime[$file_arr[$i]][$j];

//					$linetowrite.= $lat[$file_arr[$i]][$j].",".$lng[$file_arr[$i]][$j].",".$datetime[$file_arr[$i]][$j]."\n";					
					////// TMP CLOSED	////////////////////////////////////////                  		    						
				}
			}
		}
		//$linetowrite = "\n<marker vname=\"".$vname."\" imei=\"".$vserial."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" distance=\"".$total_dist."\"/>";						          						
	}  

	$del_index = array(array());
	//####### CREATE IMEI DIRECTORY
	
	for($i=0;$i<sizeof($file_arr);$i++)
	{	
		for($j=0;$j<sizeof($level_1_lat[$file_arr[$i]]);$j++)
		{				
			$valid_point = true;
			for($k=0;$k<sizeof($del_index[$file_arr[$i]]);$k++)
			{
				if(($del_index[$file_arr[$i]][$k])==$j)
				{
					$valid_point = false;
					break;
				}
			}
			
			if($valid_point)
			{
				if($j<=(sizeof($level_1_lat[$file_arr[$i]])-3))
				{
					$start_lat=$level_1_lat[$file_arr[$i]][$j];
					$start_lng=$level_1_lng[$file_arr[$i]][$j];
					
					$lat1=$level_1_lat[$file_arr[$i]][$j+1];
					$lng1=$level_1_lng[$file_arr[$i]][$j+1];
					
					$lat2=$level_1_lat[$file_arr[$i]][$j+2];
					$lng2=$level_1_lng[$file_arr[$i]][$j+2];
					
					$lat3=$level_1_lat[$file_arr[$i]][$j+3];
					$lng3=$level_1_lng[$file_arr[$i]][$j+3];						
					
					$angle1 = get_angle($start_lat,$start_lng,$lat1,$lng1);
					$angle2 = get_angle($start_lat,$start_lng,$lat2,$lng2);
					$angle3 = get_angle($start_lat,$start_lng,$lat3,$lng3);
					
					if($angle1<90.0)
					{
						if(($angle2 > $angle1) && ($angle3 < $angle1))
						{
							$del_index[$file_arr[$i]][] = $j+1;
						}
					}			
					else if($angle1>90.0)	
					{
						if(($angle2 < $angle1) && ($angle3 < $angle1))
						{
							$del_index[$file_arr[$i]][] = $j+1;
						}
					}
				}
			}		
		}
		
		//###### STORE FINAL FILTERED ROUTES
		$linetowrite = "";
		$valid_point = true;
		
		for($j=0;$j<sizeof($level_1_lat[$file_arr[$i]]);$j++)
		{		
			$valid_point = true;
			for($k=0;$k<sizeof($del_index[$file_arr[$i]]);$k++)
			{
				if(($del_index[$file_arr[$i]][$k])==$j)
				{
					$valid_point = false;
					break;
				}
			}			
			if($valid_point)
			{		
				$linetowrite.= $level_1_lat[$file_arr[$i]][$j].",".$level_1_lng[$file_arr[$i]][$j].",".$level_1_datetime[$file_arr[$i]][$j]."\n";
			}
		}
		
		//######### WRITE TO FILTERED FOLDER
		//echo $file;
		$filtered_path = "demo_group/filtered_data/".$file_arr[$i];
		$file2 = fopen($filtered_path,"w");
		fwrite($file2, $linetowrite);		
	}
	//echo   	
}

function calculate_distance($lat1, $lat2, $lon1, $lon2) 
{	
	$lat1 = deg2rad($lat1);
	$lon1 = deg2rad($lon1);

	$lat2 = deg2rad($lat2);
	$lon2 = deg2rad($lon2);
	
	$delta_lat = $lat2 - $lat1;
	$delta_lon = $lon2 - $lon1;
	
	$temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
	$distance = 6378.1  * 2 * atan2(sqrt($temp),sqrt(1-$temp));
	return $distance;
}

function get_angle($lat1,$lng1,$lat2,$lng2)
{
	$yaxis = ($lat1 + $lat2)/2;
	$xaxis = ($lng1 + $lng2)/2;
	
	$angle_t = atan( ($lat2-$lat1)/($lng2-$lng1) );
	$angle_deg = 360 * $angle_t/(2 * pi());	

	if(($lng2-$lng1)<0)
	{
			$angle_deg = 180 + $angle_deg;
	}
	else if(($lat2-$lat1)<0)
	{
			$angle_deg = 360 + $angle_deg;
	}

	$angle_deg = round($angle_deg,2);
	
	//echo "AngleDeg=".$angle_deg;
	return $angle_deg;
}  
   
?>
