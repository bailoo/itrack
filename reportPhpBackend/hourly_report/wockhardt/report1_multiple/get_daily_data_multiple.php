<?php
//set_time_limit(600);
$DEBUG =0; 

function get_daily_data($imei, $vname, $startdate, $enddate, $op_date1, $op_date2, $geo_point, $base_station_coord)
{  
	$abspath = "/var/www/html/vts/beta/src/php";
	include($abspath."/common_xml_element_for_function.php");

	//echo "<br>DailyData2";
	for($k=0;$k<sizeof($geo_point);$k++)
	{    
		$firstdata_flag_halt[$k] = 0;
		$halt_flag[$k] = 0;
		$total_halt_time[$k] = 0;
		$total_nof_halt[$k] = 0;
		$valid_location[$k] = 0;
	}  
  
	//####### BASE STATION CODE
	$coord = explode(",",$base_station_coord);
	$lat_bs = $coord[0];
	$lng_bs = $coord[1]; 
	//echo "BS=".$base_station_coord." ,Latbs=".$lat_bs." ,lng_bs=".$lng_bs;
	$outtime_bs_ = false;
	$intime_bs = false;
	$start_distance_trip = false;
	$trip_complete = false; 
	$daily_distance_bs = 0;
	//$distance_bs_total = 0; 
	$in_once = false;
	
	$base_station_out_time = "";
	$distance_bs_total = "";
	$base_station_in_time = "";	
	$base_station_string = "";
	//#########################
  
  
	//$back_dir = "../../../../../..";
	$back_dir = "/var/www/html/itrack_vts";
	$abspath_current = "/mnt/volume3";
	$abspath_sorted = "/mnt/volume4";			
	  
	$fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;
	$linetowrite="";
	$firstdata_flag_dist =0;
	//$firstdata_flag_halt =0;
  	  
	$date_1 = explode(" ",$startdate);
	$date_2 = explode(" ",$enddate);

	$datefrom = $date_1[0];
	$dateto = $date_2[0];
	$timefrom = $date_1[1];
	$timeto = $date_2[1];

	get_All_Dates($datefrom, $dateto, &$userdates);

	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);
	$interval = 300;   //5 mins
	$flag_file_found =0; 									   
	$haltflag = false;  
  
	for($i=0;$i<=($date_size-1);$i++)       //FOR SINGLE DAY
	{   
		//echo " debug2:";
		$flag_file_found =0; 	
		//#####DEFINE VARIABLES  
		$daily_dist = 0;
		$total_dist = 0;
		$ophr_dist = 0;
		$non_ophr_dist = 0;

		$total_nof_halt = 0;
		$total_halt_time = 0;
		$avg_halt_time = 0;
		//#############      
        
		$xml_current = $abspath_current."/current_data/xml_data/".$userdates[$i]."/".$imei.".xml";	    		
		//echo "\nxml_path=".$xml_current;

		if (file_exists($xml_current))      
		{		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = $abspath_sorted."/".$userdates[$i]."/".$imei.".xml";
			$CurrentFile = 0;
		}
			
		//echo "<br>xml_file =".$xml_file;	    	
		if (file_exists($xml_file)) 
		{			
			//echo " debug3:";
			//echo "\nSorted xml file exists";
			$t=time();
			$xml_original_tmp = $back_dir."/xml_tmp/original_xml/tmp_".$imei."_".$t."_".$i.".xml";
										  
			if($CurrentFile == 0)
			{
				//echo "\nONE<br>";
				copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "\nTWO<br>";
				$xml_unsorted = $back_dir."/xml_tmp/unsorted_xml/tmp_".$imei."_".$t."_".$i."_unsorted.xml";

				copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
				SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}
      
			$total_lines = count(file($xml_original_tmp));
			//echo "\nTotal lines orig=".$total_lines;

			$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
			//$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
			$logcnt=0;
			$DataComplete=false;
					  
			$vehicleserial_tmp=null;
			$format =2;
      
			if (file_exists($xml_original_tmp)) 
			{      
				//echo "\nXML orig exists";
				$flag_file_found =1; 	
				//echo "\nOriginal file exists";
				$daily_dist =0;
				// $firstdata_flag =0;

				while(!feof($xml))          // WHILE LINE != NULL
				{
					$DataValid = 0;
					//echo fgets($file). "<br />";
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			

					if(strlen($line)>20)
					{
						$linetmp =  $line;
					}
  				
					$linetolog =  $logcnt." ".$line;
					$logcnt++;
					//fwrite($xmllog, $linetolog);
	
					//SWITCH MASTER VARIABLES
					set_master_variable($userdates[$i]);
					
					if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
					{
						$format = 1;
						$fix_tmp = 1;
					}
                
					else if(strpos($line,''.$vc.'="0"'))
					{
						$format = 1;
						$fix_tmp = 0;
					}
  				
					if( (preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{ 
						$lat_value = explode('=',$lat_match[0]);
						$lng_value = explode('=',$lng_match[0]);
						//echo " lat_value=".$lat_value[1];         
						if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
						{
							$DataValid = 1;
						}
					}
          
					//if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
					if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
					{
						$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
						$xml_date = $datetime;
					}				
					//echo "Final0=".$xml_date." datavalid=".$DataValid;
          
					if($xml_date!=null)
					{				  
						//echo "<br>".$xml_date.",".$startdate.",".$enddate.",".$DataValid; 					
						if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
						{							           	
							//echo "<br>One";             
							/*$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);              
							//echo "Status=".$status.'<BR>';
							//echo "test1".'<BR>';
							if($status==0)
							{
								continue;
							}*/                                                                                       
							$status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
							if($status==0)
							{
								continue;               
							}
							//echo "test6".'<BR>';
							$status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
							if($status==0)
							{
								continue;
							}     
								   
							/*$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
							$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);*/
							$vserial = $imei;
							
							$lat_tmp1 = explode("=",$lat_tmp[0]);
							$lat = preg_replace('/"/', '', $lat_tmp1[1]);

							$lng_tmp1 = explode("=",$lng_tmp[0]);
							$lng = preg_replace('/"/', '', $lng_tmp1[1]);                               
							//echo "<br>first=".$firstdata_flag;                                        
              
							//########### DISTANCE SECTION ############//
							if($firstdata_flag_dist==0)
							{
								//echo "<br>FirstData";
								$firstdata_flag_dist = 1;
								$lat1_dist = $lat;
								$lng1_dist = $lng;
								$last_time1_dist = $datetime;
								$latlast = $lat;
								$lnglast =  $lng;                                                                       													                 	                                                                        													                 	
							}           	
							else
							{                           
								$time2_dist = $datetime;											
								$date_secs2_dist = strtotime($time2_dist);	

								$lat2_dist = $lat;
								$lng2_dist = $lng;  

								$distance = 0;
								calculate_distance($lat1_dist, $lat2_dist, $lng1_dist, $lng2_dist, &$distance);
								//echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance; 						
								$tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1_dist)) / 3600;

								calculate_distance($latlast, $lat2_dist, $lnglast, $lng2_dist, &$distance1);
                
								if($tmp_time_diff1>0)
								{
									$tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
									$last_time1_dist = $datetime;

									$latlast = $lat2_dist;
									$lnglast =  $lng2_dist;           					
								}

								$tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time_dist) )) / 3600;
                                
								//if($tmp_speed <3000 && $distance>0.1)
								//echo "\nTMPSPEED=".$tmp_speed;
								if($tmp_speed<500 && $distance>0.1 && $tmp_time_diff>0)
								{		              
									//echo "\nIn Distance";
									$daily_dist = (double) ($daily_dist + $distance);	
									$daily_dist = round($daily_dist,2);

									//echo "\nDIST::datetime=".$datetime." ,op_date1=".$op_date1." ,op_date2=".$op_date2." \ndistance=".$distance;
									if( ($datetime >=$op_date1) && ($datetime <=$op_date2) )
									{
										$ophr_dist = (double) ($ophr_dist + $distance);
										$ophr_dist = round($ophr_dist,2); 
									}
									//echo "\n\nDailyDist=".$daily_dist." ,OpDist=".$ophr_dist;							                          				                                       
									///////////////////////////////////////////////////////////																							
									$lat1_dist = $lat2_dist;
									$lng1_dist = $lng2_dist;

									$last_time_dist = $datetime;			
								}							                               
							}
							//############# DISTANCE SECTION CLOSED #############//
              
                            
							//############# HALT SECTION #######################//
							for($k=0;$k<sizeof($geo_point);$k++)
							{
								$coord = explode(',',$geo_point[$k]);
								$lat_g = trim($coord[0]);
								$lng_g = trim($coord[1]);  

								if($firstdata_flag_halt[$k]==0)
								{
									//echo "<br>FirstData";
									$halt_flag[$k] = 0;
									$firstdata_flag_halt[$k] = 1;

									$lat_ref[$k] = $lat;
									$lng_ref[$k] = $lng;       
									$datetime_ref[$k] = $datetime;                 	
									$date_secs1_halt[$k] = strtotime($datetime_ref[$k]);
									$date_secs1_halt[$k] = (double)($date_secs1_halt[$k] + $interval);                 
									//echo "<br>lat_tmp=".$lat_tmp[0]." ,lat_tmp1=".$lat_tmp1[1];             	
								}           	             	
								else
								{           
									//echo "<br>Next";               
									$lat_cr[$k] = $lat;
									$lng_cr[$k] = $lng;
									$datetime_cr[$k] = $datetime;										
									$date_secs2_halt[$k] = strtotime($datetime_cr[$k]);

									//echo "<br>str=".$lat_ref.", ".$lat_cr.", ".$lng_ref." ,".$lng_cr.", ". $datetime_cr;
									$distance = 0;
									//calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);                
									if($lat_g!="" && $lng_g!="")
									{
										//echo "\nDIST::datetime=".$datetime." ,op_date1=".$op_date1." ,op_date2=".$op_date2." \ndistance=".$distance;
										calculate_distance($lat_ref[$k], $lat_g, $lng_ref[$k], $lng_g, &$distance);
									}               
                	
									//if( ($distance > 0.200) || ($f== $total_lines-2) )
									if( ($distance > 0.0100) || ($f == $total_lines-2) )
									{
										//echo "\n\nHALT1::lat_ref=".$lat_ref." ,lng_ref=".$lng_ref." ,lat_g=".$lat_g." ,lng_g=".$lng_g." distance=".$distance;
										//echo "<br>In dist ".$distance." lat_ref ".$lat_ref." lng_ref ".$lng_ref." lat_cr ".$lat_cr." lng_cr ".$lng_cr."<br>";
										if ($halt_flag[$k] == 1)
										{				
											//echo "\n\nHALT::lat_ref=".$lat_ref." ,lng_ref=".$lng_ref." ,lat_g=".$lat_g." ,lng_g=".$lng_g;
											//echo "\nIn Halt2";
											$starttime = strtotime($datetime_ref[$k]);              				 
											$stoptime = strtotime($datetime_cr[$k]);
											//echo "<br>StartTime-".$starttime." ,EndTime-".$stoptime;            					
											$halt_dur =  ($stoptime - $starttime);

											/*$halt_dur =  ($stoptime - $starttime)/3600;

											$halt_duration = round($halt_dur,2);										
											$total_min = $halt_duration * 60;            
											$total_min1 = $total_min;            					
											//echo "<br>toatal_min=".$total_min1."user-interval=".$user_interval;

											$hr = (int)($total_min / 60);
											$minutes = $total_min % 60;										           
											$hrs_min = $hr.".".$minutes; */

											//if( ($total_min1 >= $user_interval) || ($f== $total_lines-2))
											//echo "\nhalt_dur=".$halt_dur." ,interval=".$interval;                      
											if( ($halt_dur >= $interval) || ($f == $total_lines-2))
											{
												$valid_location[$k] = 1;

												$date_secs1[$k] = strtotime($datetime_cr[$k]);
												$date_secs1[$k] = (double)($date_secs1[$k] + $interval);

												$total_halt_time[$k] = $total_halt_time[$k] + $halt_dur;
												$total_nof_halt[$k]++;
											}		// IF TOTAL MIN										
										}   //IF HALT FLAG
              			
										$lat_ref[$k] = $lat_cr[$k];
										$lng_ref[$k] = $lng_cr[$k];
										$datetime_ref[$k] = $datetime_cr[$k];
										
										$halt_flag[$k] = 0;
									}
									else
									{            			
										//echo "<br>normal flag set";
										$halt_flag[$k] = 1;
									}					                              
								}  //ELSE CLOSED
							} //FOR GEO_POINT CLOSED
							//############# HALT SECTION CLOSED ################//  
              
              
							//############ BASE STATION SECTION ##################//
							$distance_bs = 0;
							$distance1_bs = 0;
							$distance_bs_main = 0;

							calculate_distance($lat, $lat_bs, $lng, $lng_bs, &$distance_bs_main);
							if(($distance_bs_main < 0.2) && (!$start_distance_trip) && (!$trip_complete) )
							{
								//echo "\nIN-1";
								$outtime_bs = false;
								$intime_bs = true;
								$start_distance_trip = false;                 
							}
							if( ($intime_bs) && (!$outtime_bs) && ($distance_bs_main > 0.2) && (!$start_distance_trip) && (!$trip_complete) )
							{
								//echo "\nOUT-1";
								$outtime_bs = true;
								$intime_bs = false;
								$start_distance_trip = true;

								$lat1_dist_bs = $lat;
								$lng1_dist_bs = $lng;
								$last_time1_dist_bs = $datetime;
								$latlast_bs = $lat;
								$lnglast_bs =  $lng;  
					
								$base_station_out_time = $datetime;                   
							}
							if( ($outtime_bs) && (!$intime_bs) && ($start_distance_trip) && (!$trip_complete) )
							{
								//echo "\nIn Distance1";
								$time2_dist_bs = $datetime;											
								$date_secs2_dist_bs = strtotime($time2_dist_bs);	

								$lat2_dist_bs = $lat;
								$lng2_dist_bs = $lng;  

								$distance_bs = 0;               
                
								calculate_distance($lat1_dist_bs, $lat2_dist_bs, $lng1_dist_bs, $lng2_dist_bs, &$distance_bs);
								//echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance; 						
								$tmp_time_diff1_bs = (double)(strtotime($datetime) - strtotime($last_time1_dist_bs)) / 3600;

								calculate_distance($latlast_bs, $lat2_dist_bs, $lnglast_bs, $lng2_dist_bs, &$distance1_bs);

								//echo "\ntmp_time_diff1_bs=".$tmp_time_diff1_bs;

								if($tmp_time_diff1_bs>0)
								{
									//echo "\n\n\ndatetime=".$datetime." ,last_time1_dist_bs=".$last_time1_dist_bs." ,::distance_bs=".$distance_bs." ,distance1_bs=".$distance1_bs." ,tmp_time_diff1_bs=".$tmp_time_diff1_bs;
									$tmp_speed_bs = ((double) ($distance1_bs)) / ((double)$tmp_time_diff1_bs);
									//echo "\ntmp_speed_bs=".$tmp_speed_bs;
									$last_time1_dist_bs = $datetime;
								
									$latlast_bs = $lat2_dist_bs;
									$lnglast_bs =  $lng2_dist_bs;           					
								}
        				
								$tmp_time_diff_bs = ((double)( strtotime($datetime) - strtotime($last_time_dist_bs) )) / 3600;
                                
								//if($tmp_speed <3000 && $distance>0.1)
								//echo "\nTMPSPEED=".$tmp_speed_bs." ,distance_bs=".$distance_bs." ,tmp_time_diff=".$tmp_time_diff_bs;
								
								if($tmp_speed_bs<500 && $distance_bs>0.1 && $distance_bs<50 && $tmp_time_diff_bs>0)
								{		              
									$daily_distance_bs = (double) ($daily_distance_bs + $distance_bs);
									//echo "\nIn Distance2=".$daily_distance_bs;

									$lat1_dist_bs = $lat2_dist_bs;
									$lng1_dist_bs = $lng2_dist_bs;
									$last_time_dist_bs = $datetime;	
								}
							}
              
							if(($distance_bs_main < 0.2) && ($start_distance_trip) && (!$trip_complete) )
							{
								//echo "\nOUT-2";
								if(!$haltflag)
								{
									$prev_halttime = strtotime($datetime);
									$haltflag = true;
								}
								$current_halttime = strtotime($datetime);

								$time_diff = $current_halttime - $prev_halttime;

								if($time_diff > 120)
								{
									$outtime_bs = false;
									$intime_bs = true;
								}                
							}              
							if( ($intime_bs) &&(!$outtime_bs) && ($start_distance_trip) && (!$trip_complete) )
							{
								//echo "\nIN-2";
								$start_distance_trip = false;
								$outtime_bs = false;
								$intime_bs = false;

								$base_station_in_time = $datetime;
						//		$trip_complete = true;
								$distance_bs_total = $daily_distance_bs;
								
								//##### COMBINE MULTIPLE IN-OUT STRING
								if($daily_distance_bs ==0)
								{
								  $base_station_out_time = "-";
								  $base_station_in_time = "-";
								  $distance_bs_total = "-"; 
								}
								if($base_station_out_time =="")
								{
									$base_station_out_time = "-";
								}
								if($base_station_in_time =="")
								{
									$base_station_in_time = "-";
								}
								if($distance_bs_total =="")
								{
									$distance_bs_total = "-"; 
								}								
								
								$base_station_string = $base_station_string."".$base_station_out_time.",".$base_station_in_time.",".$daily_distance_bs."@";
								
								//##### RESET VARIABLES
								$daily_distance_bs = 0;		
								$distance_bs_total ="";								
								$base_station_out_time = "";
								$base_station_in_time = "";								
							}
							//############ BASE STATION SECTION CLOSED ###########//     

						//echo " debug6:";
						} // $xml_date_current >= $startdate closed
						//echo " debug7:";
					}   // if xml_date!null closed  				
					//$j++;
				}   // while closed
			} // if original_tmp closed   
			//echo " debug8:";      			
		} // if (file_exists closed
		//echo " debug9:";
	}  // for closed 
	
    //echo " debug10:";
    //WRITE DAILY DISTANCE DATA
    $total_dist = $daily_dist;
    $non_ophr_dist = $total_dist - $ophr_dist;						          						
       
    //#####GET TOTAL HALT AND AVERAGE HALT ########//
    if( sizeof($total_halt_time)>0 )               //IF HALT ALERT OCCUR
    {      
      $multiple_halt = 0;
      $multiple_halt_time = 0;
      for($k=0;$k<sizeof($geo_point);$k++)
      {
        if($valid_location[$k] == 1)
        {                      
          $multiple_halt_time += $total_halt_time[$k];
          $multiple_halt += $total_nof_halt[$k];                            
        }
      }
    }                    
    
    //############################################//   
    if($multiple_halt>0)
    {
      $avg_halt_time = $multiple_halt_time / $multiple_halt;
    }
    /*
    $tmpdata = explode("#", $daily_data);
    $total_dist = $tmpdata[0];
    $ophr_dist = $tmpdata[1];
    $non_ophr_dist = $tmpdata[2];
    $total_nof_halt = $tmpdata[3];
    $avg_halt_time = $tmpdata[4];
    */       
    if($flag_file_found)
    { 	    
      fclose($xml); 
      unlink($xml_original_tmp);
    }
    //echo " debug11:";

	$base_station_string = substr($base_station_string, 0, -1);	//ELIMINATE EXTRA COLON (:)	
	//echo "\nBaseStation_String=".$base_station_string;
	
	//$daily_data = $total_dist."#".$ophr_dist."#".$non_ophr_dist."#".$total_nof_halt."#".$total_halt_time."#".$avg_halt_time."#".$base_station_out_time."#".$base_station_in_time."#".$distance_bs_total;
	$daily_data = $total_dist."#".$ophr_dist."#".$non_ophr_dist."#".$total_nof_halt."#".$total_halt_time."#".$avg_halt_time."#".$base_station_string;
    echo "\nDailyData INSIDE FUNCTION=".$daily_data;                    
    return $daily_data;	
}
                 							
?>
						
