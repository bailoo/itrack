<?php

$DEBUG =0; 

function get_daily_data($imei, $vname, $startdate, $enddate, $op_date1, $op_date2, $min_halt_time, $max_halt_time, $nonpoi_halt_time, $geo_point, $geo_name)
{
        global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
        global $old_xml_date;
	
	//echo "\nSizeGeo=".sizeof($geo_point)." ,op_date1=".$op_date1;
	//echo "\nFUNCTION1::imei=".$imei.",vname=".$vname.",startdate=".$startdate.",enddate=".$enddate.",opdate1=".$op_date1.",optdate2=".$op_date2.",geopt_size=".sizeof($geo_point);  
	//echo "\ndebug1:";
	/*$geo_coord = explode(',',$geo_point);
	$lat_g = trim($geo_coord[0]);
	$lng_g = trim($geo_coord[1]); */
	//echo "\nstartdate=".$startdate.",enddate=".$enddate." ,optime1=".$op_date1.",optime2=".$op_date2;
	//$op_date2 = "2013-08-16 12:00:00";
	
	$nonpoi_halt_time = $nonpoi_halt_time.":0";
	$non_poi_halt_seconds = get_seconds($nonpoi_halt_time);
	
	$firstdata_flag_halt = 0;
	/*for($k=0;$k<sizeof($geo_point);$k++)
	{    
		$firstdata_flag_halt[$k] = 0;
		$halt_flag[$k] = 0;		
		$total_halt_time[$k] = 0;
		$total_nof_halt[$k] = 0;
		$valid_location[$k] = 0;
		//echo "\nINIT SET k=".$k;
	}*/
	//echo "\nINIT CLOSED";
	
	$flag_violated_d1 = 0;
	$flag_violated_d2 = 0;            

	$non_poi_halt_status = 1;
	$firstdata_flag_non_poi = 0;
	$halt_flag_non_poi = 0;
	$total_nof_halt_non_poi = 0;       
  
	//$back_dir = "../../../../../..";
	$back_dir = "/var/www/html/itrack_vts";
	//$abspath_current = "/mnt/volume3";
	//$abspath_sorted = "/mnt/volume4";				
	
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
	include("/var/www/html/vts/beta/src/php/common_xml_path.php");
	for($i=0;$i<=($date_size-1);$i++)
	{   
		$f=0;
		//echo " debug2:";
		$flag_file_found =0; 	
		//#####DEFINE VARIABLES  
		$daily_dist = 0;
		//$total_dist = 0;
		$ophr_dist = 0;
		//$non_ophr_dist = 0;

		//$total_nof_halt = 0;
		//$total_halt_time = 0;
		//$avg_halt_time = 0;
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
				//echo "<br>ONE<br>";
				copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "<br>TWO<br>";
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
				//echo " debug4:";
				$flag_file_found =1; 	
				//echo "\nOriginal file exists";
				$daily_dist =0;
				// $firstdata_flag =0;

                                //SWITCH MASTER VARIABLES
                                 set_master_variable($userdates[$i]);
					
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

							//if($f > 15768) echo "\nF-Reg=".$f;
														
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
								//###INITIALIZE IF NOT VIOLATED
								$flag_violated_d1 = 0;
								$flag_violated_d2 = 0;
								$datetime_ref_d1 = $datetime;                 	
								$date_ref_d1_sec = strtotime($datetime_ref_d1);  
								$datetime_ref_d2 = $datetime;                 	
								$date_ref_d2_sec = strtotime($datetime_ref_d2);  								
							}           	
							else
							{                           
								$time2_dist = $datetime;							
								//$date_secs2_dist = strtotime($time2_dist);	

								$lat2_dist = $lat;
								$lng2_dist = $lng;  

								$distance = 0;
								calculate_distance($lat1_dist, $lat2_dist, $lng1_dist, $lng2_dist, &$distance);
								//echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance;

								$tmp_time_diff1 = (strtotime($datetime) - strtotime($last_time1_dist)) / 3600;
								
								$distance1 = 0;
								calculate_distance($latlast, $lat2_dist, $lnglast, $lng2_dist, &$distance1);
								 
								if($tmp_time_diff1>0)
								{
									//$tmp_speed = $distance / $tmp_time_diff1;
									$tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
									$last_time1_dist = $datetime;
									$latlast = $lat2_dist;
									$lnglast = $lng2_dist;  									
								}
								$tmp_time_diff = (strtotime($datetime) - strtotime($last_time_dist)) / 3600;
									
								//if($tmp_speed <3000 && $distance>0.1)
								//echo "\nTmpSpd=".$tmp_speed." ,Dist=".$distance." ,tmptimediff=".$tmp_time_diff;								
								if($tmp_speed<500 && $distance>0.1 && $tmp_time_diff>0)
								{		              
									//echo "\nIn Distance:f=".$f." ,total_lines=".$total_lines." ,datetime=".$datetime;
									$daily_dist= (float) ($daily_dist + $distance);	
									$daily_dist = round($daily_dist,2);

									//echo "\nDIST::datetime=".$datetime." ,op_date1=".$op_date1." ,op_date2=".$op_date2." \ndistance=".$distance;
									if( ($datetime >=$op_date1) && ($datetime <=$op_date2) )
									{
										//echo "\nOPrDist";
										$ophr_dist = (float) ($ophr_dist + $distance);
										$ophr_dist = round($ophr_dist,2);

										//#### RESET TIME DEVIATION FLAG ######//
										$flag_violated_d1 = 0;
										$flag_violated_d2 = 0;            
										//####################################//         
									}
									else
									{
										$flag_violated_d1 = 1;
										$flag_violated_d2 = 1; 
									}
                  
									//######### TIME DEVIATION FROM OP_DATE ##########//
									//echo "\nDatetime=".$datetime." ,Optdate1=".$op_date1;
									if($datetime < $op_date1)
									{
										//echo "\nTimeV1=".$flag_violated_d2." ,interval=".$interval;
										$flag_violated_d1 = 1;

										//echo "\nElse1";
										$flag_violated_d1 = 1;
										$datetime_cr_d1 = $datetime;                 	
										$date_cr_d1_sec = strtotime($datetime_cr_d1);

										$violated_dur =  ($date_cr_d1_sec - $date_ref_d1_sec);             					

										//echo "\nViolatedDur1=".$violated_dur." ,interval=".$interval;
										if($violated_dur >= $interval)
										{
											//echo "\nInTime Deviation1";
											$hms_2 = secondsToTime($violated_dur);
											$violated_dur_formated = $hms_2[h].":".$hms_2[m].":".$hms_2[s];

											$time_violation1[] = $datetime_ref_d1." to ".$datetime_cr_d1." (".$violated_dur_formated.")";
											
											$flag_violated_d1 = 0;
											$flag_violated_d2 = 0;
											$datetime_ref_d1 = $datetime;                 	
											$date_ref_d1_sec = strtotime($datetime_ref_d1);  
											$datetime_ref_d2 = $datetime;                 	
											$date_ref_d2_sec = strtotime($datetime_ref_d2);											
										}                                                                         
									}                  
									//echo "\nDatetime=".$datetime." ,Optdate2=".$op_date2;
									else if($datetime > $op_date2)
									{
										//echo "\nTimeV2=".$flag_violated_d2." ,interval=".$interval;
										$flag_violated_d2 = 1;
																				           
										//echo "\nElse2";
										$flag_violated_d2 = 1;
										$datetime_cr_d2 = $datetime;                 	
										$date_cr_d2_sec = strtotime($datetime_cr_d2);

										$violated_dur =  ($date_cr_d2_sec - $date_ref_d2_sec);             					
																	 
										//echo "\nViolatedDur2=".$violated_dur." ,interval=".$interval;
										if($violated_dur >= $interval)
										{
											//echo "\nInTime Deviation2";
											$hms_2 = secondsToTime($violated_dur);
											$violated_dur_formated = $hms_2[h].":".$hms_2[m].":".$hms_2[s];

											$time_violation2[] = $datetime_ref_d2." to ".$datetime_cr_d2." (".$violated_dur_formated.")";
											
											$flag_violated_d1 = 0;
											$flag_violated_d2 = 0;
											$datetime_ref_d1 = $datetime;                 	
											$date_ref_d1_sec = strtotime($datetime_ref_d1);  
											$datetime_ref_d2 = $datetime;                 	
											$date_ref_d2_sec = strtotime($datetime_ref_d2);											
										}                                               
									}
									else
									{
										//###INITIALIZE IF NOT VIOLATED
										$flag_violated_d1 = 0;
										$flag_violated_d2 = 0;
										$datetime_ref_d1 = $datetime;                 	
										$date_ref_d1_sec = strtotime($datetime_ref_d1);  
										$datetime_ref_d2 = $datetime;                 	
										$date_ref_d2_sec = strtotime($datetime_ref_d2); 										
									}
									//##########################################                 
									//echo "\n\nDailyDist=".$daily_dist." ,OpDist=".$ophr_dist;							                          				                                       
									///////////////////////////////////////////////////////////																							
									$lat1_dist = $lat2_dist;
									$lng1_dist = $lng2_dist;

									$last_time_dist = $datetime;			
								}							                               
							}
							//############# DISTANCE SECTION CLOSED #############//
							                            
							//############# HALT SECTION #######################//							
							if($firstdata_flag_halt==0)
							{							
								$halt_flag = 0;
								$firstdata_flag_halt = 1;						
						
								$lat_ref_halt = $lat;
								$lng_ref_halt = $lng;
							
								$datetime_ref_halt = $datetime;			

								//###### FOR IRREGULAR DATA FILTER CODE
								$last_time1_halt = $datetime;
								$latlast_halt = $lat_ref;
								$lnglast_halt =  $lng_ref; 	
							}                 	
							else
							{           
								$lat_cr_halt = $lat;	
								$lng_cr_halt = $lng;							
								$datetime_cr_halt = $datetime;																		
									
								calculate_distance($lat_ref_halt, $lat_cr_halt, $lng_ref_halt, $lng_cr_halt, &$distance_halt);									
								
								if($f <= ($total_lines-10))
								{																	
									//###### FOR IRREGULAR DATA FILTER CODE
									$tmp_time_diff1_halt = (double)(strtotime($datetime) - strtotime($last_time1_halt)) / 3600;

									calculate_distance($latlast_halt, $lat_cr_halt, $lnglast_halt, $lng_cr_halt, &$distance1_halt);
									if($tmp_time_diff1_halt>0)
									{
										$tmp_speed_halt = ((double) ($distance1_halt)) / $tmp_time_diff1_halt;
										//if($tmp_speed==0) echo "\nDistance1=".$distance1." ,tmp_time_diff1=".$tmp_time_diff1." ,latlast=".$latlast." ,lnglast=".$lnglast." ,lat_cr=".$lat_cr." ,lng_cr=".$lng_cr;
										$last_time1_halt = $datetime;
										$latlast_halt = $lat_cr;
										$lnglast_halt =  $lng_cr;
									}
									$tmp_time_diff_halt = ((double)( strtotime($datetime) - strtotime($last_time_halt) )) / 3600;
									//#######################################
														
									//echo "\nInCondition ,halt_flag=".$halt_flag." ,distance=".$distance;									
									if (($halt_flag == 1) && ($distance_halt > 0.100))
									{								
										//echo "\n\nIF HALT, datetime=".$datetime." ,ArrivalTime=".$datetime_ref_halt." ,DepartureTime=".$datetime_cr_halt;
										$arrivale_time_halt = $datetime_ref_halt;
										$starttime_halt = strtotime($datetime_ref_halt);										  
										$stoptime_halt = strtotime($datetime_cr_halt);
										$depature_time_halt = $datetime_cr_halt;
																				
										$halt_dur = ($stoptime_halt - $starttime_halt);		//THIS IS USED AT RUNTIME, COMMENT HERE LATER
										//$hms_2 = secondsToTime($halt_dur);
										//$hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
										
										$AddEntryinrReport_halt = true;
									}
									else if(($halt_flag == 1) && ($f == ($total_lines-10)))
									{										
										//echo "\nELSEIF HALT, datetime=".$datetime." ,ArrivalTime=".$datetime_ref_halt;
										$arrivale_time_halt = $datetime_ref_halt;
										$starttime_halt = strtotime($datetime_ref_halt);										  
										$AddEntryinrReport_halt = true;
									}
									else if(($distance_halt <= 0.100) && ($halt_flag == 0) && ( (strtotime($datetime_cr_halt)-strtotime($datetime_ref_halt))>$interval) )    // IF VEHICLE STOPS FOR 2 MINS 
									{            													
										$halt_once =1;
										//echo "\nHALT FLAG SET, datetime=".$datetime;
										$halt_flag = 1;
										$lat_ref1_halt = $lat_cr_halt;
										$lng_ref1_halt = $lng_cr_halt;
									}
									else if ($distance_halt > 0.100)
									{									
										//###### FOR IRREGULAR DATA FILTER CODE
										if($tmp_speed_halt<500.0 && $tmp_time_diff_halt>0.0)
										{																																					
											$last_time_halt = $datetime;
											$datetime_ref_halt = $datetime_cr_halt;																						
											//#######################################																						
										}	
										$lat_ref_halt = $lat_cr_halt;
										$lng_ref_halt = $lng_cr_halt;										
									}									
                              										              			
								}  //IF F<TOTAL_LINES-10
							}  //else closed
							//############## HALT CLOSED ###############//
					
							/*//for($k=0;$k<sizeof($geo_point);$k++)
							for($k=0;$k<=0;$k++)
							{
								$coord = explode(',',$geo_point[$k]);
								echo "\ngeo_name[$k]=".$geo_name[$k];
								$lat_g = trim($coord[0]);
								$lng_g = trim($coord[1]);  

								if($firstdata_flag_halt[$k]==0)
								{
									//echo "\nFirstDataBefore:halt_flag[$k]=".$halt_flag[$k];
									$halt_flag[$k] = 0;
									$firstdata_flag_halt[$k] = 1;

									$lat_ref[$k] = $lat;
									$lng_ref[$k] = $lng;       
									$datetime_ref[$k] = $datetime;   
									//echo "\nFirstDataAfter:halt_flag[$k]=".$halt_flag[$k];									
									//$date_secs1_halt[$k] = strtotime($datetime_ref[$k]);
									//$date_secs1_halt[$k] = (double)($date_secs1_halt[$k] + $interval);                 
									//echo "<br>lat_tmp=".$lat_tmp[0]." ,lat_tmp1=".$lat_tmp1[1];             	
								}           	             	
								else
								{           
									//echo "<br>Next";               
									$lat_cr[$k] = $lat;
									$lng_cr[$k] = $lng;
									$datetime_cr[$k] = $datetime;										
									//$date_secs2_halt[$k] = strtotime($datetime_cr[$k]);

									//echo "<br>str=".$lat_ref.", ".$lat_cr.", ".$lng_ref." ,".$lng_cr.", ". $datetime_cr;
									$distance = 0;
									//calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);                
									if($lat_g!="" && $lng_g!="")
									{										
										calculate_distance($lat_ref[$k], $lat_g, $lng_ref[$k], $lng_g, &$distance);
										//echo "\nDIST::datetime1=".$datetime." ,datetime_ref[k]=".$datetime_ref[$k]." ,datetime_cr[k]=".$datetime_cr[$k]." ,lat_ref[k]=".$lat_ref[$k]." ,lat_g=".$lat_g." ,lng_ref[k]=".$lng_ref[$k]." ,lng_g=".$lng_g." distance=".$distance;
									}               
															
									//if( ($distance > 0.0100) || ($f == $total_lines-2) )
									//echo "\nDIST_before::".$distance;
									//if(($distance > 0.150) || ($f == $total_lines-10) )									
									
									if($distance > 1.0)
									{
										//echo "\n\nHALT1::lat_ref=".$lat_ref[$k]." ,lng_ref=".$lng_ref[$k]." ,lat_g=".$lat_g." ,lng_g=".$lng_g." distance=".$distance;
										//echo "<br>In dist ".$distance." lat_ref ".$lat_ref." lng_ref ".$lng_ref." lat_cr ".$lat_cr." lng_cr ".$lng_cr."<br>";										
										if($k==1 || $k==2 || $k ==0)
										{
											echo "\nCHECK: halt_flag[$k]=".$halt_flag[$k];	//problem-k1,k2,k0 = 0
										}
										if ($halt_flag[$k] == 1)
										{															
											echo "\n\nHALT2::k=".$k." ,lat_ref=".$lat_ref[$k]." ,lng_ref=".$lng_ref[$k]." ,lat_g=".$lat_g." ,lng_g=".$lng_g;
											
											//echo "\nIn Halt1, datetime=".$datetime;
											$starttime = strtotime($datetime_ref[$k]);	 
											$stoptime = strtotime($datetime_cr[$k]);
											echo "\nStartTime-".$datetime_ref[$k]." ,EndTime-".$datetime_cr[$k];            					
											$halt_dur =  ($stoptime - $starttime);             					

											//if( ($total_min1 >= $user_interval) || ($f== $total_lines-2))
											echo "\nhalt_dur=".$halt_dur." ,interval=".$interval;                      
											if( ($halt_dur >= $interval) || ($f == $total_lines-2))
											{
												//echo "\nIn Halt3";
												$valid_location[$k] = 1;
												//##RESET NON POI HALT FLAG
												$non_poi_halt_status = 0;
												$firstdata_flag_non_poi = 0;
												$halt_flag_non_poi = 0;   
												//echo "\nIn Halt3";												
												//#####################//                     

												//$date_secs1[$k] = strtotime($datetime_cr[$k]);
												//$date_secs1[$k] = (double)($date_secs1[$k] + $interval);
												//echo "\nhalt_dur=".$halt_dur." ,k=".$k;
												//$total_halt_time[$k] =  10;												
												$total_halt_time[$k] = $total_halt_time[$k] + $halt_dur.",";
												//echo "\nhalt_dur=".$halt_dur." ,TotalHaltTime=".$total_halt_time[$k]." ,total_nof_halt[k]=".$total_nof_halt[$k];												
												$total_nof_halt[$k]++;                                                                                 
											}		// IF TOTAL MIN										
										}   //IF HALT FLAG
							
										$lat_ref[$k] = $lat_cr[$k];
										$lng_ref[$k] = $lng_cr[$k];
										$datetime_ref[$k] = $datetime_cr[$k];
										
								//		$halt_flag[$k] = 0;
										//echo "\nDIST::datetime2=".$datetime." ,datetime_ref[k]=".$datetime_ref[$k]." ,datetime_cr[k]=".$datetime_cr[$k]." ,lat_ref[k]=".$lat_ref[$k]." ,lat_g=".$lat_g." ,lng_ref[k]=".$lng_ref[$k]." ,lng_g=".$lng_g." distance=".$distance;										
									}
									//else if((strtotime($datetime_cr[$k])-strtotime($datetime_ref[$k]))>60)
									else if(((strtotime($datetime_cr[$k])-strtotime($datetime_ref[$k]))>60) && ($halt_flag[$k] == 0))
									{            			
										$halt_flag[$k] = 1;
										if($k==1 || $k==2 || $k ==0)
										{
											echo "\nSET: halt_flag[$k]=".$halt_flag[$k];	//k0,k2 = 1
										}
									}					                              
								}  //ELSE CLOSED
							} //FOR GEO_POINT CLOSED
							//echo "\nDIST::datetime3=".$datetime;
							*/							
							
							//############ HALT SECTION CLOSED ##########################//
														
							//###### CHECK NON POI HALT ###########//
							if( ($non_poi_halt_status) && (($datetime>=$op_date1) && ($datetime<=$op_date2)) )
							{
								if($firstdata_flag_non_poi==0)
								{
									//echo "<br>FirstData";
									$halt_flag_non_poi = 0;
									$firstdata_flag_non_poi = 1;

									$lat_ref_non_poi = $lat;
									$lng_ref_non_poi = $lng;       
									$datetime_ref_non_poi = $datetime;                 	
									//$date_secs1_halt_non_poi = strtotime($datetime_ref_non_poi);
									//$date_secs1_halt_non_poi = (double)($date_secs1_halt_non_poi + $interval);                 
									//echo "<br>lat_tmp=".$lat_tmp[0]." ,lat_tmp1=".$lat_tmp1[1];             	
								}           	             	
								else
								{           
									//echo "<br>Next";               
									$lat_cr_non_poi = $lat;
									$lng_cr_non_poi = $lng;
									$datetime_cr_non_poi = $datetime;										
									//$date_secs2_halt_non_poi = strtotime($datetime_cr_non_poi);

									//echo "<br>str=".$lat_ref.", ".$lat_cr.", ".$lng_ref." ,".$lng_cr.", ". $datetime_cr;
									$distance = 0;
									//calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);                
									if($lat_cr_non_poi!="" && $lng_cr_non_poi!="")
									{
										//echo "\nDIST::datetime=".$datetime." ,op_date1=".$op_date1." ,op_date2=".$op_date2." \ndistance=".$distance;
										calculate_distance($lat_ref_non_poi, $lat_cr_non_poi, $lng_ref_non_poi, $lng_cr_non_poi, &$distance);
									}               
															
									//if( ($distance > 0.0100) || ($f == $total_lines-2) )
									if(($distance > 0.150) || ($f == $total_lines-10) )
									{
										//echo "\n\nHALT1::lat_ref=".$lat_ref." ,lng_ref=".$lng_ref." ,lat_g=".$lat_g." ,lng_g=".$lng_g." distance=".$distance;
										//echo "<br>In dist ".$distance." lat_ref ".$lat_ref." lng_ref ".$lng_ref." lat_cr ".$lat_cr." lng_cr ".$lng_cr."<br>";
										if ($halt_flag_non_poi == 1)
										{				
											//echo "\n\nHALT2::lat_ref=".$lat_ref." ,lng_ref=".$lng_ref." ,lat_g=".$lat_g." ,lng_g=".$lng_g;											
											//echo "\nIn Non POI Halt1";
											$starttime = strtotime($datetime_ref_non_poi);              				 
											$stoptime = strtotime($datetime_cr_non_poi);
											//echo "<br>StartTime-".$starttime." ,EndTime-".$stoptime;            					
											$halt_dur_non_poi =  ($stoptime - $starttime);             					

											//if( ($total_min1 >= $user_interval) || ($f== $total_lines-2))
											//echo "\nhalt_dur=".$halt_dur." ,interval=".$interval;                      
											if($non_poi_halt_seconds > 60)      //minimum 1 min
											{
												if( ($halt_dur_non_poi >= $non_poi_halt_seconds) || ($f == $total_lines-2))
												{                                                 
													//echo "\nIn Non POI Halt3";
													//$date_secs1_non_poi = strtotime($datetime_cr_non_poi);
													//$date_secs1_non_poi = (double)($date_secs1_non_poi + $interval);

													//$halt_from_non_poi[] = $datetime_ref_non_poi
													//$halt_to_non_poi[] = $datetime_cr_non_poi;
													//$total_halttime_non_poi = $total_halt_time_non_poi + $halt_dur_non_poi;
													$hms_2 = secondsToTime($halt_dur_non_poi);
													$dur_non_poi_formated = $hms_2[h].":".$hms_2[m].":".$hms_2[s];

													$non_poi_string[] = $datetime_ref_non_poi." => ".$datetime_cr_non_poi." (".$dur_non_poi_formated.")";                                                          
													//$total_nof_halt_non_poi++;                                                                                 
												}		// IF TOTAL MIN
											}										
										}   //IF HALT FLAG
							
										$lat_ref_non_poi = $lat_cr_non_poi;
										$lng_ref_non_poi = $lng_cr_non_poi;
										$datetime_ref_non_poi = $datetime_cr_non_poi;

										$halt_flag_non_poi = 0;
									}
									else if(((strtotime($datetime_cr_non_poi)-strtotime($datetime_ref_non_poi))>60) && ($halt_flag_non_poi == 0))
									{            			
										//echo "<br>normal flag set";
										$halt_flag_non_poi = 1;
									}					                              
								}  //ELSE CLOSED                
							}
							//###### NON POI HALT CLOSED ##########//									
							
							//############# HALT SECTION CLOSED ################//                
							//echo " debug6:";
						} // $xml_date_current >= $startdate closed
					}   // if xml_date!null closed
					
					//############## REMAINING HALT SECTION ##################					
					if(($xml_date >= $enddate) && ($halt_flag == 1) && ($AddEntryinrReport_halt ==false))
					{
						//echo "\nELSEIF HALT1>XML_DATE, datetime=".$datetime;
						$arrivale_time_halt = $datetime_ref_halt;
						$starttime_halt = strtotime($datetime_ref_halt);										  
						$AddEntryinrReport_halt =true;
					}
					
					if($AddEntryinrReport_halt)
					{										
						$non_poi_halt_status = 1;	//SET TO TRUE INITIALLY	
						//echo "\nIN ADDENTRY, datetime=".$datetime;												
						$station_no = "-";
						$entered_station_halt = 0;
					
						for($k=0;$k<sizeof($geo_point);$k++)
						{												
							$coord = explode(',',$geo_point[$k]);
							$lat_g = trim($coord[0]);
							$lng_g = trim($coord[1]);  
						  
							/*if($distance_variable[$k] == 0)
							{
								$distance_variable[$k] = 2;
							}*/
							$distance_variable[$k] = 1;							
								
							$distance_station = 0;              
							if( ($lat_g!="") && ($lng_g!="") )
							{
								//echo "\nDIST::datetime=".$datetime." ,op_date1=".$op_date1." ,op_date2=".$op_date2." \ndistance=".$distance;
								calculate_distance($lat_ref1_halt, $lat_g, $lng_ref1_halt, $lng_g, &$distance_station1);
								calculate_distance($lat_cr_halt, $lat_g, $lng_cr_halt, $lng_g, &$distance_station2);
								
								/*if($customer_no[$k]=="1105")
								{
									echo "\nDIST::datetime=".$datetime.",distance_station1=".$distance_station1.",distance_station2=".$distance_station2;
								}*/
								
								if($distance_station1<$distance_station2)
								{
									$distance_station=$distance_station1;
								}
								else
								{
									$distance_station=$distance_station2;
								}
								
								if($distance_station < $distance_variable[$k])
								{
									//echo "\nVehicle entered in station=CustomerNo=".$customer_no[$k];  
									//$station_no = $customer_no[$k];
									$valid_location[$k] = 1;
									
									//###### RESET NON POI
									$non_poi_halt_status = 0;
									$firstdata_flag_non_poi = 0;
									$halt_flag_non_poi = 0;
									$lat_ref_non_poi = $lat;
									$lng_ref_non_poi = $lng;
									$datetime_ref_non_poi = $datetime;
									//echo "\nDatetime=".$datetime; 									
									//######################
									/*if($station_no=="")
									{
									$station_no = "-";
									} */                       
									//$customer_visited[] = $station_no;
									//$customer_type[] = $type[$k];
									$total_halt_time[$k] = $total_halt_time[$k] + $halt_dur.",";
									$total_nof_halt[$k]++;
									$entered_station_halt = 1;
									//break;
								}
							}								
		
							//##########################################                        
							if($entered_station_halt)
							{			  								
								$entered_station_halt = 0;
							} //IF ENTERED STATION                             
						}  //FOR LOOP CLOSED
						
						$AddEntryinrReport_halt = false;	
						$lat_ref_halt = $lat_cr_halt;
						$lng_ref_halt = $lng_cr_halt;
						$datetime_ref_halt= $datetime_cr_halt;			
						$halt_flag = 0;			
						
					}   //IF ADD ENTRYINRrEPORT
					//############## HALT CLOSED #####################
				//$j++;
				$f++;
				//if($f > 15768) echo "\nF2-Reg=".$f;
				}   // while closed
			} // if original_tmp closed   
				//echo " debug8:";      			
		} // if (file_exists closed
				//echo " debug9:";
	}  // for closed 

	//echo " debug10:";
	//WRITE DAILY DISTANCE DATA
	//$total_dist = $daily_dist;
	//$non_ophr_dist = $total_dist - $ophr_dist;						          						

	//######### TIME DEVIATION FROM OPERATIONAL TIME #######//
	$time_deviation1 = "";
	$time_deviation2 = "";

	//echo "\nSizeTimeViolation=".sizeof($time_violation1);
	
	for($i=0;$i<sizeof($time_violation1);$i++)
	{
		$time_deviation1 = $time_deviation1.$time_violation1[$i].", ";
	}
	$time_deviation1 = substr($time_deviation1, 0, -1);

	for($i=0;$i<sizeof($time_violation2);$i++)
	{
		$time_deviation2 = $time_deviation2.$time_violation2[$i].", ";
	}
	$time_deviation2 = substr($time_deviation2, 0, -1);    


	//############# ROUTE DEVIATION & HALT TIME DEVIATION#########//
	$multiple_halt = 0;
	$multiple_halt_time = 0;
	$route_deviation_counter = "";
	$halttime_deviation_counter = "";

	//echo "\nMinHalt=".$min_halt_time." ,MaxHaltTime=".$max_halt_time;
	$min_halt_time_a = explode(',',$min_halt_time);
	$max_halt_time_a = explode(',',$max_halt_time);

	//echo "\nSizeGeo2=".sizeof($geo_point);
	for($k=0;$k<sizeof($geo_point);$k++)
	{
		//echo "\nValid_loc[k]=".$valid_location[$k];		
		if($valid_location[$k] == 1)
		{
			//echo "\nValidLoc:".$total_halt_time[$k]." ,min_halt_time_a[k]=".$min_halt_time_a[$k]." ,max_halt_time_a[k]=".$max_halt_time_a[$k];
			//$multiple_halt_time += $total_halt_time[$k];
			//$multiple_halt += $total_nof_halt[$k];

			$min_halt_time_a_tmp = $min_halt_time_a[$k].":0";
			$max_halt_time_a_tmp = $max_halt_time_a[$k].":0";

			$min_halt_sec = get_seconds($min_halt_time_a_tmp);
			$max_halt_sec = get_seconds($max_halt_time_a_tmp);
				
			//$total_halt_time[$k] = substr($total_halt_time[$k], 0, -1);
			//echo "\ntotal_halt_time[k]=".$total_halt_time[$k]." ,min_halt_time_a[k]=".$min_halt_time_a[$k]." ,min_halt_sec=".$min_halt_sec." ,max_halt_sec=".$max_halt_sec;
			
			if( ($total_halt_time[$k] < $min_halt_sec) || ($total_halt_time[$k] > $max_halt_sec) )
			{
				/*if($total_halt_time[$k] < $min_halt_sec)
				{
					echo "\nOne";
					$diff = $min_halt_sec -$total_halt_time[$k];
				}
				else
				{
					echo "\nTwo";
					$diff = $total_halt_time[$k] - $max_halt_sec;
				}*/				
				
				$hms_2 = secondsToTime($total_halt_time[$k]);
				$hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
				//echo "\nDiff=".$diff." ,hrs_min=".$hrs_min;
										
				$halttime_deviation_counter = $halttime_deviation_counter.$geo_name[$k]."=>".$hrs_min.", ";
			}                        
		}
		else
		{
			//echo "\nInvalid";
			//echo "\nInValidLoc-geoname=".$geo_name[$k];
			$route_deviation_counter = $route_deviation_counter.$geo_name[$k].", ";
		} 
		//echo "\nK=".$k." ,size=".sizeof($geo_point);		
	}

	//echo "\nONE";
	$halttime_deviation_counter = substr($halttime_deviation_counter, 0, -1);
	//echo "\nTWO";
	$route_deviation_counter = substr($route_deviation_counter, 0, -1);
	//echo "\nTHREE";

	//echo "\nRouteDevCounter=".$route_deviation_counter;
	//###########NON POI HALT #############//
	$non_poi_string_final = "";

	//echo "\nSizeNONPOI=".sizeof($non_poi_string);
	for($i=0;$i<sizeof($non_poi_string);$i++)
	{
		$non_poi_string_final = $non_poi_string_final.$non_poi_string[$i].", ";
	}                                                                               
	$non_poi_string_final = substr($non_poi_string_final, 0, -1);
	//#####################################//

	if($flag_file_found)
	{ 	    
		fclose($xml); 
		unlink($xml_original_tmp);
	}
	//echo " debug11:";

	$daily_data = $time_deviation1."#".$time_deviation2."#".$route_deviation_counter."#".$halttime_deviation_counter."#".$non_poi_string_final;
	//$daily_data = "232.45435#34234.435#34324.787#34234234.687657#324324.6785";
	//echo "\nDailyData INSIDE FUNCTION=".$daily_data;                    
	return $daily_data;	
}


function get_seconds($time_format) 
{
    /*$hours = substr($time, 0, -6);
    $minutes = substr($time, -5, 2);
    $seconds = substr($time, -2);*/
    $time = explode(':',$time_format);
    $hours = $time[0];
    $minutes = $time[1];
    $seconds = $time[2];
    //echo "\ntime=".$hours."::".$minutes;    
    $seconds = ($hours * 3600) + ($minutes * 60) + ($seconds);
    return $seconds; 
}
                 							
?>
						
