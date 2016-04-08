<?php

$DEBUG =0; 
echo "\nActionFile";
function get_daily_data($imei, $vname, $startdate, $enddate, $icd_code_input, $customer_code_input, $expected_arrival_input, $expected_departure_input)
{
	//echo "\nSD=".$startdate." ,ED=".$enddate;
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;

	//echo "\nSD=".$startdate." ,ED=".$enddate;
	//echo "\nInAction";
	global $icd_code_db;
	global $icd_coord_db;	
	global $factory_code_db;
	global $factory_coord_db;
	//global $expected_arrival_db;
	//echo "\nSizeGeoICD=".sizeof($icd_coord)." ,SizeGeoICD=".sizeof($factory_coord);
	//echo "\nstartdate=".$startdate.",enddate=".$enddate;  
	
	//######## INITIALISE VARIABLES 
	$icd_out = false;
	$icd_out_time = "";
	$icd_in_time ="";
	$status_res = "";
	$total_dist = 0.0;
	$factory_halt_time =0;
	$factory_in_flag = false;
	$factory_code_final = "";
	$factory_in_once = false;
	$factory_in_time = "";
	$factory_out_time = "";
	$schedule_in_time ="-";
	$time_delay = "-";
	$current_lat = "";
	$current_lng = "";
	$AddEntryinrReport = false;
	//##############################
	
	$firstdata_flag_halt = 0;
	
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
  
	$flag_icd1 = true;
	$icd_out = true;
	//echo "<br>DateSize=".$date_size;
	for($i=0;$i<=($date_size-1);$i++)
	{   
		$f=0;
		//echo " debug2:";
		$flag_file_found =0; 	
		//#####DEFINE VARIABLES  
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
			
		//echo "\nxml_file =".$xml_file;	    	
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
					else
					{
						$format = 2;
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
							//echo "<br>Mathes";             
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
							//############# xml variable closed
							
							//$current_lat = $lat;
							//$current_lng = $lng;
							//############## CHECK ICD OUT ########################
							//#####################################################
							//echo "\nICD_COORD_DATA=".sizeof($icd_coord_db);
							for($k=0;$k<sizeof($icd_coord_db);$k++)
							{												
								//echo "\nICD_CODE_INPUT=".$icd_code_input." ,icd_code_db[$k]=".$icd_code_db[$k];
								if(trim($icd_code_input) == trim($icd_code_db[$k]))
								{
									//echo "\nICD Code matched";
									$coord = explode(',',$icd_coord_db[$k]);
									$lat_icd = trim($coord[0]);
									$lng_icd = trim($coord[1]);
									calculate_distance($lat, $lat_icd, $lng, $lng_icd, &$distance_icd);									
									//echo "\nDistanceICD=".$distance_icd;
									
									if(!$icd_out)
									{
										//echo "\nNOT OUT";
										$current_time = strtotime($datetime);
										$diff_icd_time1 = $current_time - $previous_time;
										if($flag_icd1)
										{
											$previous_time = strtotime($datetime);
											$flag_icd1 = false;
										}
									}									
									else if($icd_out)
									{
										//echo "\nICD OUT-1";
										$flag_icd1 = true;
										$time1_icd = strtotime($icd_out_time);
										$time2_icd = strtotime($datetime);
										$diff_icd_time2 = $time2_icd - $time1_icd;
									}
									
									if( ($distance_icd > 1) && ($diff_icd_time2>60) && (!$factory_in_once))
									{
										//echo "\nICD OUT-2";
										$icd_out = true;
										$icd_out_time = $datetime;
										$status_res = "On the way to Factory";
										//$total_dist = 0.0;
										break;
									}
									else if( ($distance_icd < 1) && ($diff_icd_time1>60) )
									{
										//echo "\nICD IN-1";
										$icd_out = false;
										$icd_in_time = $datetime;
										$status_res = "At ICD";										
										break;
									}
									else if( ($distance_icd < 1) && ($diff_icd_time1>60) && (!$icd_out) )
									{										
										//echo "\nICD IN-2";
										$icd_in_time = $datetime;
										$status_res = "At ICD";
										$total_dist = 0.0;
										break;
									}									
								}								
							}
							
							//########### IF ICD OUT FOUND #################
							if($icd_out)
							{
								//echo "\nOUT-FOUND";								
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
										$total_dist= (float) ($total_dist + $distance);	
										$total_dist = round($total_dist,2);     
										//####################################//         
			                                                        $current_lat = $lat;
                                			                        $current_lng = $lng;
 																			
										//echo "\n\nDailyDist=".$daily_dist." ,OpDist=".$ophr_dist;							                          				                                       
										///////////////////////////////////////////////////////////																							
										$lat1_dist = $lat2_dist;
										$lng1_dist = $lng2_dist;
										$last_time_dist = $datetime;			
									}							                               
								}
								//############# DISTANCE SECTION CLOSED #############//
															
								//############# HALT SECTION (IF HALT OCCURS ANYWHERE ################//							
								if($firstdata_flag_halt==0)
								{							
									$halt_flag = 0;
									$firstdata_flag_halt = 1;						
							
									$lat_ref_halt = $lat;
									$lng_ref_halt = $lng;
								
									$datetime_ref_halt = $datetime;			

									//###### FOR IRREGULAR DATA FILTER CODE
									$last_time1_halt = $datetime;
									$latlast_halt = $lat_ref_halt;
									$lnglast_halt =  $lng_ref_halt; 	
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
											$latlast_halt = $lat_cr_halt;
											$lnglast_halt =  $lng_cr_halt;
										}
										$tmp_time_diff_halt = ((double)( strtotime($datetime) - strtotime($last_time_halt) )) / 3600;
										//#######################################
															
										//echo "\nInCondition ,halt_flag=".$halt_flag." ,distance_halt=".$distance_halt;									
										if (($halt_flag == 1) && ($distance_halt > 0.100))
										{								
											//echo "\nlat_ref_halt=".$lat_ref_halt.", lat_cr_halt=".$lat_cr_halt.", lng_ref_halt=".$lng_ref_halt.", lng_cr_halt=".$lng_cr_halt.", distance_halt=".$distance_halt;
											//echo "\n\nIF HALT, datetime=".$datetime." ,ArrivalTime=".$datetime_ref_halt." ,DepartureTime=".$datetime_cr_halt;											
											$arrivale_time_halt = $datetime_ref_halt;
											$starttime_halt = strtotime($datetime_ref_halt);										  
											$stoptime_halt = strtotime($datetime_cr_halt);
											$depature_time_halt = $datetime_cr_halt;
											//echo "\nDEPT1=".$depature_time_halt;
																					
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
				
								//############ HALT SECTION CLOSED ##########################//
																					
								//###### NON POI HALT CLOSED ##########//									
							} // ICD OUT CLOSED
							//############# HALT SECTION CLOSED ################//                
							//echo " debug6:";
						} // $xml_date_current >= $startdate closed
					}   // if xml_date!null closed
					
					//############## REMAINING HALT SECTION ##################										
					
					//############# ON THE WAY TO ICD ####################
					if( ($factory_in_flag) && ($AddEntryinrReport_halt==false) )	//IF JUST WENT OUTSIDE OF THE FACTORY
					{

						for($k=0;$k<sizeof($factory_coord_db);$k++)
						{
                                                        //echo "\nFCI=".$customer_code_input." ,FCD=".$factory_code_db[$k];
                                                        if($customer_code_input == $factory_code_db[$k])
                                                        {
								//echo "\nFactoryCodeMatched";
								$coord = explode(',',$factory_coord_db[$k]);
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
									//echo "\nDistanceStation=".$distance_station." ,dist_var=".$distance_variable[$k];
									if($distance_station < $distance_variable[$k])
									{
										//echo "\nVehicle entered in station=CustomerNo=".$customer_no[$k]." ,datetime=".$datetime;  
										//######################
										/*$total_halt_time[$k] = $total_halt_time[$k] + $halt_dur.",";
										$total_nof_halt[$k]++;*/
										$entered_station_halt2 = 1;
										//break;
									}															
								}
							}
						}						
						if(!$entered_station_halt2)
						{
							//echo "\nFactoryOut::ON THE WAY TO ICD-1";
							$time1_factory = strtotime($factory_in_time);
							$time2_factory = strtotime($datetime);
							$diff_factory_time = $time2_factory - $time1_factory;
							
							if($diff_factory_time > 120)
							{
								$status_res = "On the way to ICD";
								//$factory_out_time = $datetime;
							
								//echo "\nDEPT_FACTORU=".$depature_time_halt;
								$factory_out_time = $depature_time_halt;
								$factory_in_flag = false;							
								//echo "\nON THE WAY TO ICD-2";
							}
						}
					}
					
					if( ($factory_in_flag) && ($AddEntryinrReport_halt) )	//IF INSIDE FACTORY ,SUM THE HALT TIME
					{
						$factory_halt_time = $factory_halt_time + $halt_dur;
					}
					//#####################################################
					
					/*if(($xml_date >= $enddate) && ($halt_flag == 1) && ($AddEntryinrReport_halt ==false))
					{
						//echo "\nELSEIF HALT1>XML_DATE, datetime=".$datetime;
						$arrivale_time_halt = $datetime_ref_halt;
						$starttime_halt = strtotime($datetime_ref_halt);										  
						$AddEntryinrReport_halt = true;
					}*/
					
					if(($AddEntryinrReport_halt) && ($factory_in_flag==false))		//##### IF HALT OCCURED
					{										
						//echo "\nHALT OCCURED=".$datetime;
						//", arrival_date=".$arrivale_time_halt." ,departure_date=".$depature_time_halt;
						$non_poi_halt_status = 1;	//SET TO TRUE INITIALLY	
						//echo "\nIN ADDENTRY, datetime=".$datetime;												
						$station_no = "-";
						$entered_station_halt = 0;
					
						//echo "\nFactoryCoord=".sizeof($factory_coord_db);						
						for($k=0;$k<sizeof($factory_coord_db);$k++)
						{												
							//echo "\nFCI=".$customer_code_input." ,FCD=".$factory_code_db[$k];
							if($customer_code_input == $factory_code_db[$k])
							{
								//echo "\nFactoryCodeMatched";
								$coord = explode(',',$factory_coord_db[$k]);
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
									
									//echo "\nDistanceStation=".$distance_station." ,dist_var=".$distance_variable[$k];
									if($distance_station < $distance_variable[$k])
									{
										//echo "\nVehicle entered in station=CustomerNo=".$customer_no[$k]." ,datetime=".$datetime;  
										//######################
										/*$total_halt_time[$k] = $total_halt_time[$k] + $halt_dur.",";
										$total_nof_halt[$k]++;*/										
										$entered_station_halt = 1;
										//break;
									}
								}								
			
								//################ IF ENTERED INTO THE FACTORY ################           
								//#######################################################       
								if($entered_station_halt)	
								{
									//echo "\nFactoryIN::".$datetime. " ,arrival_date=".$arrivale_time_halt;
									$entered_station_halt = 0;
									
									//######### FACTORY HALT
									//$factory_halt_time = $factory_halt_time + $halt_dur;
									//$total_nof_halt[$k]++;
									$status_res = "At Factory";
									$factory_in_flag = true;
									//$factory_in_time = $datetime;
									$factory_in_time = $arrivale_time_halt;
									$factory_in_once = true;
									$depature_time_halt = "";
									//$factory_halt_time = 0;
																		
									//######## GET DELAY
									//echo "\nSchedule Matched";												  
									$start_date_tmp = explode(" ",$startdate);
									$end_date_tmp = explode(" ",$enddate);
									
									//$schedule_in_time = $expected_arrival[$k];
									//$schedule_in_time = $expected_arrival_input;
									$tmp_time_input = $start_date_tmp[0]." ".$start_date_tmp[1];
									$tmp_input = strtotime($tmp_time_input);
									
									//$tmp_time_schedule = $start_date_tmp[0]." ".$schedule_in_time;
									//$tmp_schedule = strtotime($tmp_time_schedule);
									$tmp_schedule = strtotime($expected_arrival_input);

									$final_date = "";
									if($tmp_input <= $tmp_schedule)
									{
										$final_date = $start_date_tmp[0];
									}								
									else
									{
										$final_date = $end_date_tmp[0];
									}
									
									//echo "\nScheduleInTime=".$schedule_in_time;									
									//$in_time_str_excel = $final_date." ".$schedule_in_time;									
									$in_time_str = $datetime_ref_halt;														
								
									if($schedule_in_time!="")
									{
										$time1 = strtotime($in_time_str) - strtotime($expected_arrival_input);
									
										//echo "\nin_time_str=".$in_time_str." ,in_time_str_excel=".$in_time_str_excel." ,DiffTime=".$time1;										
										if($time1>0)
										{															
											$hms_3 = secondsToTime($time1);
											$time_delay = $hms_3[h].":".$hms_3[m].":".$hms_3[s];
										}
										else
										{
											//time_delay = "00:00:00";
											$time1 = abs($time1);
											$hms_4 = secondsToTime($time1);								
											$time_delay = $hms_4[h].":".$hms_4[m].":".$hms_4[s];
											$time_delay = "-".$time_delay;																					
										}
									}														
								
									if($schedule_in_time=="")
									{
										$schedule_in_time = "-";
									}
									
									if($time_delay=="")
									{
										$time_delay = "-";
									}									
									//######### DELAY CLOSED ##############
								} //IF ENTERED STATION                             
							} //if factory code matches
						}  //FOR LOOP CLOSED
						
						$AddEntryinrReport_halt = false;	
						$lat_ref_halt = $lat_cr_halt;
						$lng_ref_halt = $lng_cr_halt;
						$datetime_ref_halt= $datetime_cr_halt;			
						$halt_flag = 0;						
						
					}   //IF ADD ENTRYINRrEPORT					
					else if($AddEntryinrReport_halt)
					{
						$AddEntryinrReport_halt = false;	
						$lat_ref_halt = $lat_cr_halt;
						$lng_ref_halt = $lng_cr_halt;
						$datetime_ref_halt= $datetime_cr_halt;			
						$halt_flag = 0;							
					}
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

	//############## FINAL STRINGS ##############	
	
	if($status_res == "")
	{
		$status_res = "Information Not Available";
	}
	//$time_deviation2 = substr($time_deviation2, 0, -1);    
	//############# ROUTE DEVIATION & HALT TIME DEVIATION#########//
	if($flag_file_found)
	{ 	    
		fclose($xml); 
		unlink($xml_original_tmp);
	}
	//echo " debug11:";

	if($factory_halt_time>0)
	{
		$hms_3 = secondsToTime($factory_halt_time);
		$factory_halt_time = $hms_3[h].":".$hms_3[m].":".$hms_3[s];
		//$factory_halt_time = 0;
	}

	$current_coord = $current_lat.",".$current_lng;
    /*if($status_res=='At ICD')
	{
			$total_dist=0.0;
	}*/
	//echo "\nFactory_in_time=".$factory_in_time." ,factory_out_time=".$factory_out_time;
	if(($factory_in_time!="") && (trim($factory_out_time)==""))
	{
		//echo "\nAtFactory";
		$status_res = "At Factory";
	}
	$daily_data = $icd_out_time."#".$current_coord."#".$total_dist."#".$status_res."#".$factory_in_time."#".$factory_out_time."#".$schedule_in_time."#".$time_delay."#".$factory_halt_time;
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
						
