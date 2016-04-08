<?php
//set_time_limit(600);
$DEBUG =0; 

//function get_distance_data($imei, $vname, $startdate, $enddate, $op_date1, $op_date2, $geo_point, $base_station_coord)
function get_distance_data($imei, $vname, $startdate, $enddate, $plant_no, $station_coord, $distance_variable)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	//echo "\nIMEI=".$imei.", vname=".$vname.", startdate=".$startdate.", enddate=".$enddate.", size(plant)=".sizeof($plant_no);
	/*for($k=0;$k<sizeof($station_coord);$k++)
	{    
		$firstdata_flag_halt[$k] = 0;
		$halt_flag[$k] = 0;
		$total_halt_time[$k] = 0;
		$total_nof_halt[$k] = 0;
		$valid_location[$k] = 0;
	}*/ 	
	//####### PLANT VARIABLE
	//echo "BS=".$base_station_coord." ,Latbs=".$lat_bs." ,lng_bs=".$lng_bs;
	$outtime_plant = false;
	$intime_plant = false;
	$start_distance_trip = false;
	$trip_complete = false; 
	$mean_distance_plant = 0;
	//$distance_bs_total = 0; 
	$in_once = false;
	
	$plant_out_time = "";
	$distance_plant_total = "";
	$plant_in_time = "";	
	$plant_string = "";
	//#########################
    
	//$back_dir = "../../../../../..";
	$back_dir = "/var/www/html/itrack_vts";
	//$back_dir_current = "/mnt/volume3";
	//$back_dir_sorted = "/mnt/volume4";
  
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
  
	$daily_dist = 0;
	//$total_dist = 0;
	//$ophr_dist = 0;

	$in_flag = false;
	$start_trip = false;
	$out_valid = false;
	$store = false;
	$trip_progress = false;
	include("/var/www/html/vts/beta/src/php/common_xml_path.php");							
	for($i=0;$i<=($date_size-1);$i++)       //FOR SINGLE DAY
	{   
		//echo " debug2:";
		$flag_file_found =0; 	
		//#####DEFINE VARIABLES  
		/*$daily_dist = 0;
		$total_dist = 0;
		$ophr_dist = 0;*/

		//#############              
		$xml_current = $xml_data."/".$userdates[$i]."/".$imei.".xml";	    		
		//echo "\nxml_path=".$xml_current;

		if (file_exists($xml_current))      
		{		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = $sorted_xml_data."/".$userdates[$i]."/".$imei.".xml";
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
				SortFile($xml_unsorted, $xml_original_tmp, $userdates[$i]);    // SORT FILE
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
				//SWITCH MASTER VARIABLES
				set_master_variable($userdates[$i]);

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
              
							/*
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

									$ophr_dist = (double) ($ophr_dist + $distance);
									$ophr_dist = round($ophr_dist,2); 
									//echo "\n\nDailyDist=".$daily_dist." ,OpDist=".$ophr_dist;							                          				                                       
									///////////////////////////////////////////////////////////																							
									$lat1_dist = $lat2_dist;
									$lng1_dist = $lng2_dist;

									$last_time_dist = $datetime;			
								}						                               
							}
							//############# DISTANCE SECTION CLOSED #############//                                          							
							*/
              
							//############ PLANT DISTANCE SECTION ##################//
							$distance_plant = 0;
							$distance1_plant = 0;
							$distance_plant_main = 0;

							$in_flag = false;
							for($k=0;$k<sizeof($station_coord);$k++)
							{												
								$coord = explode(',',$station_coord[$k]);
								$lat_plant = trim($coord[0]);
								$lng_plant = trim($coord[1]);  
															
								calculate_distance($lat, $lat_plant, $lng, $lng_plant, &$distance_plant_main);
								//echo "\nDistance=".$distance_plant_main." ,dist_var=".$distance_variable[$k]." ,lat=".$lat." ,lng=".$lng." ,lat_plant=".$lat_plant." ,lng_plant=".$lng_plant;
								
								if($distance_plant_main < $distance_variable[$k])
								{
									//echo "\nInPlant";
									$in_flag = true;
									$start_trip = true;
									$plant_in = $plant_no[$k];
									break;
								}
							}
								
							if(!$in_flag && $start_trip)
							{																
								//echo "\nIN";
								if(!$haltflag)
								{
									$prev_halttime = strtotime($datetime);
									$haltflag = true;
								}
								$current_halttime = strtotime($datetime);

								$time_diff = $current_halttime - $prev_halttime;

								if($time_diff > 120)
								{
									$out_valid = true;
								}
								
								if($out_valid)
								{
									//echo "\nOUT";
									if(!$store)
									{
										$plant_out = $plant_in;
										$plant_out_time = $datetime;
										$store = true;
										$trip_progress = true;
										
										//#### START DISTANCE
										$lat1_dist_plant = $lat;
										$lng1_dist_plant = $lng;
										$last_time1_dist_plant = $datetime;
										$latlast_plant = $lat;
										$lnglast_plant =  $lng;
										//###################
									}
									
									$lat2_dist_plant = $lat;
									$lng2_dist_plant = $lng;  

									$distance_plant = 0;               
					
									calculate_distance($lat1_dist_plant, $lat2_dist_plant, $lng1_dist_plant, $lng2_dist_plant, &$distance_plant);
									//echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance; 						
									$tmp_time_diff1_plant = (double)(strtotime($datetime) - strtotime($last_time1_dist_plant)) / 3600;

									calculate_distance($latlast_plant, $lat2_dist_plant, $lnglast_plant, $lng2_dist_plant, &$distance1_plant);

									//echo "\ntmp_time_diff1_bs=".$tmp_time_diff1_bs;
									if($tmp_time_diff1_plant>0)
									{
										//echo "\n\n\ndatetime=".$datetime." ,last_time1_dist_bs=".$last_time1_dist_bs." ,::distance_bs=".$distance_bs." ,distance1_bs=".$distance1_bs." ,tmp_time_diff1_bs=".$tmp_time_diff1_bs;
										$tmp_speed_plant = ((double) ($distance1_plant)) / $tmp_time_diff1_plant;
										//echo "\ntmp_speed_bs=".$tmp_speed_bs;
										$last_time1_dist_plant = $datetime;
									
										$latlast_plant = $lat2_dist_plant;
										$lnglast_plant =  $lng2_dist_plant;           					
									}
							
									$tmp_time_diff_plant = ((double)( strtotime($datetime) - strtotime($last_time_dist_plant) )) / 3600;
									
									//if($tmp_speed <3000 && $distance>0.1)
									//echo "\nTMPSPEED=".$tmp_speed_bs." ,distance_bs=".$distance_bs." ,tmp_time_diff=".$tmp_time_diff_bs;									
									//if($tmp_speed_plant<500.0 && $distance_plant>0.1 && $distance_plant<50 && $tmp_time_diff_plant>0)
									if($tmp_speed_plant<500.0 && $distance_plant>0.1 && $tmp_time_diff_plant>0.0)
									{		              										
										$mean_distance_plant = (double) ($mean_distance_plant + $distance_plant);
										//echo "\nmean_distance_plant=".$mean_distance_plant;

										$lat1_dist_plant = $lat2_dist_plant;
										$lng1_dist_plant = $lng2_dist_plant;
										$last_time_dist_plant = $datetime;	
									}																	
								}
							}
							
							if($in_flag && $start_trip && $trip_progress)
							{								
								//echo "\nFinal";
								$plant_in_time = $datetime;								
								$plant_string = $plant_string."".$plant_out.",".$plant_out_time.",".$plant_in.",".$plant_in_time.",".$mean_distance_plant."#";
								
								$in_flag = false;
								$start_trip = false;
								$out_valid = false;
								$store = false;
								$trip_progress = false;
								$haltflag = false;
 							    $mean_distance_plant = 0.0;
							}							
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
						          						           
    if($flag_file_found)
    { 	    
      fclose($xml); 
      unlink($xml_original_tmp);
    }
    //echo " debug11:";

	$plant_string = substr($plant_string, 0, -1);	//ELIMINATE EXTRA COLON (:)	
	//echo "\nPlantString=".$plant_string;
	
	$distance_data = $plant_string;
    //echo "\nDistance_Data INSIDE FUNCTION=".$distance_data;                    
    return $distance_data;	
}
                 							
?>
