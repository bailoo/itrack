<?php
$k1 = 0;
$m_start=0;
$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);
function get_travel_xml_data($vehicle_serial, $vid, $vname, $startdate,$enddate,$datetime_threshold)
{
	$datetime_threshold = $datetime_threshold * 60;
	$fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$linetowrite="";	
	$datetime_S="";
	$datetime_E="";
	$place1="";
	$place2="";
	$distance_travel="";
	$distance_travel="";
	$travel_time="";	
	$datefrom = $startdate;
	$dateto = $enddate;		
	$startdate = $startdate." 00:00:00";
	$enddate = $enddate." 23:59:59";
	
	get_All_Dates($datefrom, $dateto, &$userdates);
	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	$date_size = sizeof($userdates);  
	$substr_count = 0;  
	
  $back_dir = "/var/www/html/itrack_vts";
  
  for($i=0;$i<=($date_size-1);$i++)
	{
		$xml_current = $back_dir."/xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
		if (file_exists($xml_current))      
		{		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = $back_dir."/sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}		
		//echo "<br>xml in get_halt_xml_data =".$xml_file;	    	
		if (file_exists($xml_file)) 
		{			
			//echo "<br>file_exists1";     
			$t=time();
			$xml_original_tmp = $back_dir."/xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
			//$xml_log = "../../../xml_tmp/filtered_xml/tmp_".$current_datetime1.".xml";
			//echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";									      
			if($CurrentFile == 0)
			{
				copy($xml_file,$xml_original_tmp);
			}
			else
			{
				$xml_unsorted = $back_dir."/xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
				//echo  "<br>".$xml_file." <br>".$xml_unsorted."<br><br>";				        
				copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
				SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}      
			$total_lines = count(file($xml_original_tmp));
			$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;	
			$logcnt=0;
			$DataComplete=false;
			$vehicleserial_tmp=null;
			$f =0;      
			if (file_exists($xml_original_tmp)) 
			{      
				//echo "<br>In FileExists";        
				$start_time_flag = 0;
				$distance_total = 0;
				$distance_threshold = 0.200;
				$distance_error = 0.100;
				$distance_incriment =0.0;
				$firstdata_flag =0;
				$start_point_display =0;

				$haltFlag==True;
				$distance_travel=0;                        
				//echo "<br>file_exists2";                
				while(!feof($xml))          // WHILE LINE != NULL
				{
					$DataValid = 0;			
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE	
					if(strlen($line)>20)
					{
						$linetmp =  $line;
					}  				
					$linetolog =  $logcnt." ".$line;
					$logcnt++;
					if(strpos($line,'Fix="1"'))     // RETURN FALSE IF NOT FOUND
					{
						$format = 1;
						$fix_tmp = 1;
					}                
					if(strpos($line,'Fix="0"'))
					{
						$format = 1;
						$fix_tmp = 0;
					}
					else
					{
						$format = 2;
					}  				
					if((preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{ 
						$lat_value = explode('=',$lat_match[0]);
						$lng_value = explode('=',$lng_match[0]);						     
						if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
						{
							$DataValid = 1;
						}
					}				
					if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
					{
						$datetime = get_xml_data('/datetime="[^"]+"/', $line);
						$xml_date = $datetime;
					}			         
					if($xml_date!=null)
					{				  					
						if(($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
						{							           	                            
							$vserial = get_xml_data('/vehicleserial="[^"]+"/', $line);  
							$lat = get_xml_data('/lat="\d+\.\d+[NS]\"/', $line);
							$lng = get_xml_data('/lng="\d+\.\d+[EW]\"/', $line);                          
							if($firstdata_flag==0)
							{                                
								$firstdata_flag = 1;
								$haltFlag=True;
								$distance_travel=0;
								$lat_S = $lat;
								$lng_S = $lng;
								$datetime_S = $datetime;
								$datetime_travel_start = $datetime_S;
								$lat_travel_start = $lat_S;
								$lng_travel_start = $lng_S;
								$start_point_display =0;                  	                             	
							}           	              	
							else
							{           
								$lat_E = $lat;
								$lng_E = $lng; 
								$datetime_E = $datetime;
								calculate_distance($lat_S, $lat_E, $lng_S, $lng_E, &$distance_incriment);								
								if($distance_incriment > $distance_error)
								{ 
									if($haltFlag==True)
									{
										$datetime_travel_start = $datetime_E;
										$haltFlag = False;
									}
									$distance_total += $distance_incriment;
									$distance_travel += $distance_incriment;
									$lat_S = $lat_E;
									$lng_S = $lng_E;
									$datetime_S = $datetime_E;
									$start_point_display =1;
								}           				
								$datetime_diff = strtotime($datetime_E) - strtotime($datetime_S);         				
								if(($distance_total>$distance_error) && ($datetime_diff > $datetime_threshold) && ($haltFlag==False))
								{
									/*if($f>15000)
									break;*/						
									$datetime_travel_end = $datetime_E;
									$lat_travel_end = $lat_S;
									$lng_travel_end = $lng_S; 
									newTravel($vserial, $vname, $datetime_travel_start, $datetime_travel_end, $distance_travel, $lat_travel_start, $lng_travel_start, $lat_travel_end, $lng_travel_end, $distance_travel, $fh, $linetowrite, $substr_count);
									$substr_count =1;          			    
									$datetime_travel_start = $datetime_E;
									$lat_travel_start = $lat_E;
									$lng_travel_start = $lng_E;           				    

									$distance_travel = 0;
									// exit;
									$datetime_S = $datetime_E;
									$distance_total = 0;
									$distance_incrimenttotal = 0;
									$haltFlag = True;          					
								}
							}
						}
					} 
					$f++;
				}
			}      
			fclose($xml);            
			unlink($xml_original_tmp);
		}
	}
	//fclose($fh);
	//fclose($xmllog);
}

function newTravel($vserial, $vname, $datetime_S, $datetime_E, $distance, $lat_travel_start, $lng_travel_start, $lat_travel_end, $lng_travel_end, $distance_travel, $fh, $linetowrite, $substr_count)
{
	$sno =1;
	global $csv_string_travel;
	global $total_travel_dist;
	global $total_travel_time;
	global $DbConnection;
	$travel_dur =  strtotime($datetime_E) - strtotime($datetime_S);                                                    
	$hms = secondsToTime($travel_dur);
	$travel_time = $hms[h].":".$hms[m].":".$hms[s];
	$distance_travel = round($distance_travel,2);
	//echo "\t\t\t\tTravel : " . $datetime_S . " to " . $datetime_E . "( " . $distance . " )<br>";        
	$alt1 = "-";
	$alt2 ="-";
	$landmark="";
	get_landmark($lat_travel_start,$lng_travel_start,&$landmark);    // CALL LANDMARK FUNCTION
	$place1 = $landmark;
	if($place1=="")
	{
		get_location($lat_travel_start,$lng_travel_start,$alt1,&$place1,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
	}
	//location2								 
	$landmark="";
	get_landmark($lat_travel_end,$lng_travel_end,&$landmark);    // CALL LANDMARK FUNCTION
	$place2 = $landmark;
	if($place2=="")
	{
		get_location($lat_travel_end,$lng_travel_end,$alt2,&$place2,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
	}        		
	$place1 = str_replace(',',':',$place1);
	$place2 = str_replace(',',':',$place2);
	if($substr_count == 0)
	{	
		$csv_string_travel = $csv_string_travel.$sno.','.$datetime_S.','.$datetime_E.','.$place1.','.$place2.','.$distance_travel.','.$travel_time;
	}
	else
	{
		$csv_string_travel = $csv_string_travel."#".$sno.','.$datetime_S.','.$datetime_E.','.$place1.','.$place2.','.$distance_travel.','.$travel_time;
	}
	$total_travel_dist = $total_travel_dist + $distance_travel;
	$total_travel_time = $total_travel_time + $travel_dur;
$sno++;    
} 

function get_xml_data($reg, $line)
{
	$data = "";
	if(preg_match($reg, $line, $data_match))
	{
		$data = explode_i('"', $data_match[0], 1);
	}
	return $data;
}

function explode_i($reg, $str, $i)
{
	$tmp = explode($reg, $str);
	return $tmp[$i];
}      
?>					
