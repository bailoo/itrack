<?php
//echo "MAIL ACTION";
function get_halt_xml_data($vehicle_serial, $vid, $vname, $startdate, $enddate, $user_interval, $report_shift)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;

	//$startdate = "2013-05-12 16:51:49";
	//$enddate = "2013-05-12 21:24:04";	
	
	$abspath = "/var/www/html/vts/beta/src/php";
	include_once($abspath."/util.hr_min_sec.php");
	//echo "\nHALT function before1";
	global $customer_visited;
	global $shift;
	global $point;
	global $timing;
	global $vehicle_t;
	global $transporter;
	
	global $route_input;
	global $customer_input;
	global $vehicle_input;
	global $shift_input;
	global $transporter_input;
	
	global $route_type_input;
	global $relative_route_type_input;	
	
	$delay = "-";
	$schedule_time = "-";
	$route_no = "-";
	$transporter_name_master ="-";
	$transporter_name_input ="-";

	$date_tmp1 = explode(" ",$startdate);
	$date_tmp2 = explode(" ",$enddate); 
	$report_date1 = $date_tmp1[0];
	$report_time1 = $date_tmp1[1];
	$report_date2 = $date_tmp2[0];
	$report_time2 = $date_tmp2[1];

	//echo "\nReportDate1=".$date_tmp1;
  
	$abspath = "/var/www/html/vts/beta/src/php";
	include_once($abspath."/get_location_lp_track_report.php");
	
	global $DbConnection;
	global $account_id;
	global $geo_id1;
	$sno =1;
	global $csv_string_halt;
	global $overall_dist;
	global $total_halt_dur;

	global $station_id;
	global $type;
	global $customer_no;
	global $station_name;
	global $station_coord;
	global $distance_variable;
	global $google_location;

	for($k=0;$k<sizeof($station_coord);$k++)       //INITIALISE VARIABLES
	{    
		$halt_flag[$k] = 0;
		$firstdata_flag_halt[$k] = 0; 
		$substr_count[$k] =0;  
		//$total_halt_time[$k] = 0;
	}
	
	$interval=$user_interval*60;
	//echo "interval=".$interval."<br>";
	$fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;
	$linetowrite="";
	$firstdata_flag =0;
		
	$arrivale_time="";
	$depature_time="";
	$hrs_min="";
	
	$date_1 = explode(" ",$startdate);
	$datefrom = $date_1[0];
	$timefrom = $date_1[1];
	$date_2 = explode(" ",$enddate);	
	$dateto = $date_2[0];
	$timeto = $date_2[1];
	$cum_dist = 0;
	
	get_All_Dates($datefrom, $dateto, &$userdates);
	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");

	$date_size = sizeof($userdates);
	$substr_count =0;
	
	$back_dir = "/var/www/html/itrack_vts";
	//$back_dir_current = "/mnt/volume3";
	//$back_dir_sorted = "/mnt/volume4";
	$AddEntryinrReport = false;
	//$f=0;
	include("/var/www/html/vts/beta/src/php/common_xml_path.php");  
	for($i=0;$i<=($date_size-1);$i++)
	{	
		$xml_current = $xml_data."/".$userdates[$i]."/".$vehicle_serial.".xml";
		if (file_exists($xml_current))      
		{  
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = $sorted_xml_data."/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}		
		//echo "<br>xml in get_halt_xml_data =".$xml_file;	
    	
		if (file_exists($xml_file)) 
		{			
			$t=time();
			//echo "t=".$t."<br>";
			$xml_original_tmp = $back_dir."/xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";									      
			if($CurrentFile == 0)
			{
				copy($xml_file,$xml_original_tmp);
			}
			else
			{
				$xml_unsorted = $back_dir."/xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
				copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
				SortFile($xml_unsorted, $xml_original_tmp, $userdates[$i]);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}      
			
			$total_lines = count(file($xml_original_tmp));		      
			$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
			$logcnt=0;
			$DataComplete=false;                  
			$vehicleserial_tmp=null;      
			$f=0;

			//echo "\nxml_original_tmp=".$xml_original_tmp;
      
			if (file_exists($xml_original_tmp)) 
			{      
				//SWITCH MASTER VARIABLES
				set_master_variable($userdates[$i]);

				//echo "\nFile Exist";
				$halt_once = false;
        
				while(!feof($xml))          // WHILE LINE != NULL
				{
					//echo "\nIN WHILE";
					$DataValid = 0;			
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
					if(strpos($line,''.$vc.'="0"'))
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
						if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
						{
							$DataValid = 1;
						}
					}       					
					if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
					{					
						$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
						$xml_date = $datetime;
					}       

					 //echo "\nXML_DATE=".$xml_date." ,DataValid=".$DataValid." ,vehicle_serial=".$vehicle_serial;   

					if($xml_date!=null)
					{
						//echo "\nStartDate=".$startdate." ,EndDate=".$enddate;
						if(($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
						{
							//echo "\nIN DATE";

							/*$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);						
							if($status==0)
							{
								//echo "\nStatus0";
								continue;
							}*/
							//echo "<textarea>".$line."</textarea>"; 
							//$line = '<marker msgtype="NORMAL" vehicleserial="354776033544005" vehiclename="SJ DKL-4005" ver="v1.34" fix="1" lat="26.60652N" lng="80.20758E" alt="123.0" speed="0.000000" datetime="2011-01-25 03:16:23" fuel="-" vehicletype="Heavy" no_of_sat="09" cellname="" distance="0.0" io1="7" io2="6" io3="387" io4="10" io5="6" io6="6" io7="4" io8="2" sig_str="0" sup_v="0.000000" speed_a="0" geo_in_a="0" geo_out_a="0" stop_a="0" move_a="0" lowv_a="0"/>';  
							/*$status = preg_match('/vehiclename="[^"]+/', $line, $vehiclename_tmp);          
							//echo "<br>vname=".$vehiclename_tmp[0];
							if($status==0)
							{
								continue;
							} */                
							$status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
							if($status==0)
							{
								//echo "\nStatus1";
								continue;               
							}
							//echo "test6".'<BR>';
							$status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
							if($status==0)
							{
								//echo "\nStatus2";
								continue;
							}                 
							$status = preg_match('/'.$vf.'="[^" ]+/', $line, $speed_tmp);
							if($status==0)
							{
								//echo "\nStatus3";
								continue;
							}      
							       
							if($firstdata_flag==0)
							{							
								$halt_flag = 0;
								$firstdata_flag = 1;

								/*$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
								$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);*/							
								$vserial = $vehicle_serial;
								$lat_tmp1 = explode("=",$lat_tmp[0]);							
								$lat_ref = preg_replace('/"/', '', $lat_tmp1[1]);
								$lng_tmp1 = explode("=",$lng_tmp[0]);
								$lng_ref = preg_replace('/"/', '', $lng_tmp1[1]);
							
								$datetime_ref = $datetime;	
								$cum_dist = 0;			

								//###### FOR IRREGULAR DATA FILTER CODE
								$last_time1 = $datetime;
								$latlast = $lat_ref;
								$lnglast =  $lng_ref;
								//////##############################
								//$date_secs1 = strtotime($datetime_ref);							
								//$date_secs1 = (double)($date_secs1 + $interval);      	
							}                 	
							else
							{           
								/*if($lat_cr == "28.70165N")
								{
									echo "\nLatRef_Found0=".$lat_cr;
								}*/
								//echo "<br>Next";               
								$lat_tmp1 = explode("=",$lat_tmp[0]);                           //  GET NEXT RECO
								$lat_cr = preg_replace('/"/', '', $lat_tmp1[1]);
																								
								$lng_tmp1 = explode("=",$lng_tmp[0]);
								$lng_cr = preg_replace('/"/', '', $lng_tmp1[1]);							
								$datetime_cr = $datetime;																		
								$date_secs2 = strtotime($datetime_cr);	
								calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);
								//if(($distance > 0.0100) || ($f== $total_lines-2) )
								//echo "\nF=".$f." ,total_lines=".$total_lines;										
								
								if($f <= ($total_lines-10))
								{																	
									//###### FOR IRREGULAR DATA FILTER CODE
									$tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;

									calculate_distance($latlast, $lat_cr, $lnglast, $lng_cr, &$distance1);
									if($tmp_time_diff1>0)
									{
										$tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
										//if($tmp_speed==0) echo "\nDistance1=".$distance1." ,tmp_time_diff1=".$tmp_time_diff1." ,latlast=".$latlast." ,lnglast=".$lnglast." ,lat_cr=".$lat_cr." ,lng_cr=".$lng_cr;
										$last_time1 = $datetime;
										$latlast = $lat_cr;
										$lnglast =  $lng_cr;
									}
									$tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time) )) / 3600;
									//#######################################
						
									//if($lat_cr == "28.70165N")
									//{
										//$difference = strtotime($datetime_cr)-strtotime($datetime_ref);
										//echo "\nLatRef_Prev=".$lat_cr." ,distance=".$distance." ,HaltFlag=".$halt_flag;										
										//echo " ,DateTimeCr=".$datetime_cr." ,DateTimeRef=".$datetime_ref." ,difference=".$difference." , interval=".$interval." \n";
									//}									
									//echo "\nInCondition ,halt_flag=".$halt_flag." ,distance=".$distance;									
									if (($halt_flag == 1) && ($distance > 0.100))
									{								
										//echo "\n\nIF HALT, datetime=".$datetime." ,ArrivalTime=".$datetime_ref." ,DepartureTime=".$datetime_cr;
										$arrivale_time = $datetime_ref;
										$starttime = strtotime($datetime_ref);										  
										$stoptime = strtotime($datetime_cr);
										$depature_time=$datetime_cr;
																				
										$halt_dur = ($stoptime - $starttime);		//THIS IS USED AT RUNTIME, COMMENT HERE LATER
										$hms_2 = secondsToTime($halt_dur);
										$hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
										
										$AddEntryinrReport=true;
									}
									else if(($halt_flag == 1) && ($f == ($total_lines-10)))
									{										
										//echo "\nELSEIF HALT, datetime=".$datetime." ,ArrivalTime=".$datetime_ref;
										$arrivale_time = $datetime_ref;
										$starttime = strtotime($datetime_ref);										  
										/*$depature_time="-";
										$halt_dur = "-";
										$hrs_min = "-";*/
										$AddEntryinrReport=true;
									}
									else if(($distance <= 0.100) && ($halt_flag == 0) && ( (strtotime($datetime_cr)-strtotime($datetime_ref))>$interval) )    // IF VEHICLE STOPS FOR 2 MINS 
									{            													
										$halt_once =1;
										//echo "\nHALT FLAG SET, datetime=".$datetime;
										$halt_flag = 1;
										$lat_ref1 = $lat_cr;
										$lng_ref1 = $lng_cr;
									}
									else if ($distance > 0.100)
									{									
										//###### FOR IRREGULAR DATA FILTER CODE
										if($tmp_speed<500.0 && $tmp_time_diff>0.0)
										{																											
											$cum_dist = $cum_dist + $distance;						                          
											//echo "\nTmp_speed=".$tmp_speed." ,tmp_time_diff=".$tmp_time_diff." ,cum_dist=".$cum_dist;
											//echo "<br>dist greater than 0.025: dist=".$total_dist." time2=".$time2;											
											$last_time = $datetime;
											$datetime_ref= $datetime_cr;	

											//$lat_ref = $lat_cr;
											//$lng_ref = $lng_cr;																					
											//#######################################																						
										}	
										$lat_ref = $lat_cr;
										$lng_ref = $lng_cr;										
									}									
                              										              			
								}  //IF F<TOTAL_LINES-10
								/*else if((strtotime($datetime_cr)-strtotime($datetime_ref))>$interval)    // IF VEHICLE STOPS FOR 2 MINS 
								{            			
									echo "halt flag set\n";
									$halt_flag = 1;
								}	*/
							}  //else closed
						} // $xml_date_current >= $startdate closed
					}   // if xml_date!null closed
					if(($xml_date >= $enddate) && ($halt_flag == 1) && ($AddEntryinrReport==false))
					{
						//echo "\nELSEIF HALT1>XML_DATE, datetime=".$datetime;
						$arrivale_time = $datetime_ref;
						$starttime = strtotime($datetime_ref);										  
						/*$depature_time="-";
						$halt_dur = "-";
						$hrs_min = "-";*/
						$AddEntryinrReport=true;
					}
					
					if($AddEntryinrReport)
					{										
						//echo "\nIN ADDENTRY, datetime=".$datetime;
						$place = "-";												
						$station_no = "-";
						$transporter_name = "-";
						$schedule_time = "-";
						$delay = "-";
						$entered_station = 0;
					
						for($k=0;$k<sizeof($station_coord);$k++)
						{												
							$coord = explode(',',$station_coord[$k]);
							$lat_g = trim($coord[0]);
							$lng_g = trim($coord[1]);  
						  
							if($distance_variable[$k] == 0)
							{
								$distance_variable[$k] = 0.1;
							}    
								
							$distance_station = 0;              
							if( ($lat_g!="") && ($lng_g!="") && ($customer_no[$k]!="") )
							{
								//echo "\nDIST::datetime=".$datetime." ,op_date1=".$op_date1." ,op_date2=".$op_date2." \ndistance=".$distance;
								calculate_distance($lat_ref1, $lat_g, $lng_ref1, $lng_g, &$distance_station1);
								calculate_distance($lat_cr, $lat_g, $lng_cr, $lng_g, &$distance_station2);
								
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
								/*if($customer_no[$k]=="865")
								{
									echo "\nlat_ref=".$lat_ref." ,lng_ref=".$lng_ref." :: lat_cr=".$lat_cr." ,lng_cr=".$lng_cr." :: lat_g=".$lat_g." ,lng_g=".$lng_g." ,distance_station=".$distance_station." ,distance_variable[k]=".$distance_variable[$k]." ,datetime=".$datetime." ,imei=".$vehicle_serial;
								}*/
								
								if($distance_station < $distance_variable[$k])
								{
									//echo "\nVehicle entered in station=CustomerNo=".$customer_no[$k];  
									$station_no = $customer_no[$k];																								
									
									/*if($station_no=="")
									{
									$station_no = "-";
									} */                       
									$customer_visited[] = $station_no;
									$customer_type[] = $type[$k];
									$entered_station = 1;
									//break;
								}
							}								
		
							//##########################################                        
							if($entered_station)
							{
								$pos_c = strpos($station_no, "@");
								if($pos_c !== false)
								{
									//echo "\nNegative Found";
									$customer_at_the_rate1 = explode("@", $station_no);											
								}
								else
								{
									$customer_at_the_rate1[0] = $station_no;
								}
										
								//echo "\nHALT OCCURED";
								$type_str = "";
								$time_delay="";
								
								if($type[$k]=="0") 
								{
									$type_str = "Customer";
								} 
								else if($type[$k] == "1") 
								{
									$type_str = "Plant";
								}
								
								//echo "\nVehicle=".$vname.=" ,Station_no=".$station_no." ,Type=".$type_str." ,ArrivalTime=".$arrivale_time." ,DepartureTime=".$depature_time."\n";												
								////################ GET SHEDULE TIME AND DELAY ##########################################
								//echo "\nSizeofShift=".sizeof($shift);

								$valid_halt = false;
					
								for($n=0;$n<sizeof($transporter);$n++)
								{
									if($vname == $vehicle_t[$n])
									{
										//echo "\nTransporter Matched";
										$transporter_name_master = $transporter[$n];
										break;                                  
									}
								}
								
								for($n=0;$n<sizeof($transporter_input);$n++)
								{
									if($vname == $vehicle_input[$n])
									{
										//echo "\nTransporter Matched";
										$transporter_name_input = $transporter_input[$n];
										break;                                  
									}
								}												
								
								$route_no="";
								$route_type="";

								if($type[$k]=="0")
								{
									for($n=0;$n<sizeof($route_input);$n++)
									{
										/*if($station_no =='1009957@k')
										{
											echo "\nstation_no=".$station_no." customer_input=".$customer_input[$n]." ,customer_at_the_rate1[0]=".$customer_at_the_rate1[0];
											//echo "\nreport_shift=".$report_shift." shift_input[n]=".$shift_input[$n];
										}*/
									
										if( (trim($customer_at_the_rate1[0]) == trim($customer_input[$n])) && ($report_shift == $shift_input[$n]) )
										{
											//echo "\nInRoute";
											//echo "\ncustomer Matched";
											if($route_no=="")
											{
												$route_no = $route_input[$n];
												$route_type = $route_type_input[$n];
											}
											else
											{
												$route_no = $route_no.'/'.$route_input[$n];
												$route_type = $route_type.'/'.$route_type_input[$n];												
											}																
										}
									} 
								}
																														
								/////////////////////////////////////////                             
								//echo "report_shift=".$report_shift." sizeof(shift)=".sizeof($shift);
								for($m=0;$m<sizeof($shift);$m++)
								{
									$schedule_shift_tmp = $shift[$m];
									$schedule_point_tmp = $point[$m];
									$schedule_in_time_tmp = $timing[$m];

									/*echo "\nSchedule_in_time_tmp=".$schedule_in_time_tmp;
									echo "\nSchedule_shift_tmp=".$schedule_shift_tmp." ,report_shift=".$report_shift;
									echo " ,Schedule_point_tmp=".$schedule_point_tmp." ,station_no=".$station_no;*/

									if( ($schedule_shift_tmp == $report_shift) && (trim($schedule_point_tmp) == trim($customer_at_the_rate1[0])) )
									{                              
										//echo "\nSchedule Matched";												  
										$start_date_tmp = explode(" ",$startdate);
										$end_date_tmp = explode(" ",$enddate);
										
										$schedule_in_time = $schedule_in_time_tmp;
										$tmp_time_input = $start_date_tmp[0]." ".$start_date_tmp[1];
										$tmp_time_schedule = $start_date_tmp[0]." ".$schedule_in_time;
										
										$tmp_input = strtotime($tmp_time_input);
										$tmp_schedule = strtotime($tmp_time_schedule);

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
										
										$in_time_str_excel = $final_date." ".$schedule_in_time_tmp;
										$in_time_str = $arrivale_time;														
										
										if($schedule_in_time!="")
										{
											$time1 = strtotime($in_time_str) - strtotime($in_time_str_excel);
										
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

										/*$date_arrival_tmp = explode(" ",$arrivale_time);
										$schedule_time_tmp =  $date_arrival_tmp[0]." ".$schedule_timing_tmp;                       
										$delay_tmp = strtotime($arrivale_time) - strtotime($schedule_time_tmp); */														
										//$delay_hms = secondsToTime($delay_tmp);
										//$delay = $delay_hms[h].":".$delay_hms[m].":".$delay_hms[s];
										break;                                   
									}
								}
			 
								//echo "\nSTATION_NO=".$station_no." ,TimeDelay=".$time_delay;
								
								if($schedule_in_time=="")
								{
									$schedule_in_time = "-";
								}
								
								if($time_delay=="")
								{
									$time_delay = "-";
								}
								
								if($route_no=="")
								{
									$route_no="-";
								}
								if($route_type=="")
								{
									$route_type="-";
								}
								if($hrs_min=="")
								{
									$hrs_min = "-";
								}
								///####################### GET SHEDULE TIME AND DELAY CLOSED #############################

								//echo "\nHrsMinHalt=".$hrs_min." ,customer_no[k]=".$customer_no[$k];
								
								if($type_str=="Plant")
								{
									$schedule_in_time = "-";
								}
								
								//$cum_dist = $cum_dist + $distance;
								
								if($vname!=$prev_vehicle)
								{
									$cum_dist = 0;
								}
								
								//echo "\nRouteNo=".$route_no." ,transporter_name_master=".$transporter_name_master." ,transporter_name_input=".$transporter_name_input;
								
								if($substr_count == 0)
								{											
									$csv_string_halt = $csv_string_halt.$vname.','.$sno.','.$station_no.','.$type_str.','.$route_no.','.$report_shift.','.$arrivale_time.','.$depature_time.','.$schedule_in_time.','.$time_delay.','.$hrs_min.','.$report_date1.','.$report_time1.','.$report_date2.','.$report_time2.','.$transporter_name_master.','.$transporter_name_input.',-,'.$cum_dist.','.$route_type;
									$substr_count =1;  
								}
								else
								{
									$csv_string_halt = $csv_string_halt."#".$vname.','.$sno.','.$station_no.','.$type_str.','.$route_no.','.$report_shift.','.$arrivale_time.','.$depature_time.','.$schedule_in_time.','.$time_delay.','.$hrs_min.','.$report_date1.','.$report_time1.','.$report_date2.','.$report_time2.','.$transporter_name_master.','.$transporter_name_input.',-,'.$cum_dist.','.$route_type;
								}
			  
								$prev_vehicle = $vname;
								//echo "\nSerial=".$sno;
								$sno++;
								//$date_secs1 = strtotime($datetime_cr);
								//$date_secs1 = (double)($date_secs1 + $interval);
								//echo "\nHALT COMPLETED";
								$entered_station = 0;
								//break;                            											
								//$entered_station = 0;
							} //IF ENTERED STATION                             
						}  //FOR LOOP CLOSED
						
						$AddEntryinrReport=false;	
						$lat_ref = $lat_cr;
						$lng_ref = $lng_cr;
						$datetime_ref= $datetime_cr;            				
						$halt_flag = 0;			
						
						$arrivale_time="";
						$depature_time="";						

					}   //IF ADD ENTRYINRrEPORT
					$f++;
				}   // while closed
			} // if original_tmp closed 
			//echo "vehicle_name=".$vname."csv_string_halt==".$csv_string_halt."<br>";
			fclose($xml);            
		//	unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed
		
	//echo "\nCSV_STRING_HALT=".$csv_string_halt;

echo "\nHALT CLCLOSED";
}	
?>
