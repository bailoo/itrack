﻿<?php
//echo "MAIL ACTION";
function get_halt_xml_data($vehicle_serial, $vid, $vname, $startdate, $enddate, $user_interval, $report_shift)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;	

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
	
	$delay = "-";
	$schedule_time = "-";
	$route_no = "-";
	$transporter_name ="-";

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
	
	get_All_Dates($datefrom, $dateto, &$userdates);
	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");

	$date_size = sizeof($userdates);
	$substr_count =0;
	
	$back_dir = "/var/www/html/itrack_vts";
	//$abspath_current = "/mnt/volume3";
	//$abspath_sorted = "/mnt/volume4";			
	
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
							/*$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
							$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);*/	
							$vserial = $vehicle_serial;
							
							if($firstdata_flag==0)
							{							
								$halt_flag = 0;
								$firstdata_flag = 1;						

								$lat_tmp1 = explode("=",$lat_tmp[0]);							
								$lat_ref = preg_replace('/"/', '', $lat_tmp1[1]);
								$lng_tmp1 = explode("=",$lng_tmp[0]);
								$lng_ref = preg_replace('/"/', '', $lng_tmp1[1]);
							
								$datetime_ref = $datetime;							                	
								//$date_secs1 = strtotime($datetime_ref);							
								//$date_secs1 = (double)($date_secs1 + $interval);      	
							}                 	
							else
							{           
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
									//echo "\nInCondition ,halt_flag=".$halt_flag." ,distance=".$distance;
									if (($halt_flag == 1) && ($distance > 0.150))
									{								
										//echo "IF HALT\n";
										$arrivale_time = $datetime_ref;
										$starttime = strtotime($datetime_ref);										  
										$stoptime = strtotime($datetime_cr);
										$depature_time=$datetime_cr;
										$halt_dur = ($stoptime - $starttime);
										$hms_2 = secondsToTime($halt_dur);
										$hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
										
										$AddEntryinrReport=true;
									}
									else if(($halt_flag == 1) && ($f == ($total_lines-10)))
									{
										//echo "ELSEIF HALT\n";
										$arrivale_time = $datetime_ref;
										$starttime = strtotime($datetime_ref);										  
										$depature_time="-";
										$halt_dur = "-";
										$hrs_min = "-";
										$AddEntryinrReport=true;
									}
									else if(($distance <= 0.150) && ($halt_flag == 0) && ( (strtotime($datetime_cr)-strtotime($datetime_ref))>$interval) )    // IF VEHICLE STOPS FOR 2 MINS 
									{            			
										$halt_once =1;
										//echo "HALT FLAG SET\n";
										$halt_flag = 1;
									}
									else if ($distance > 0.150)
									{
										$lat_ref = $lat_cr;
										$lng_ref = $lng_cr;
										$datetime_ref= $datetime_cr;
									}									
                              										
									if($AddEntryinrReport)
									{
															//echo "IN ADDENTRY\N";
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
												calculate_distance($lat_ref, $lat_g, $lng_ref, $lng_g, &$distance_station);
																																	
												if($distance_station < $distance_variable[$k])
												{
													//echo "\nVehicle entered in station=CustomerNo=".$customer_no[$k];  
													$station_no = $customer_no[$k];
													/*if($station_no=="")
													{
													$station_no = "-";
													} */                       
													//$customer_visited[] = $station_no;
													$customer_type[] = $type[$k];
													$entered_station = 1;
													//break;
												}
											}								
                        
											//##########################################                        
											if($entered_station)
											{
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
														  
												$valid_halt = false;
						            
												get_report_location($lat_cr,$lng_cr,&$location);
												$location = str_replace(',',':',$location);
												
												$location = preg_replace('/भारत गणराज्य/', '' , $location);
												$location = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '' , $location);												
												///####################### GET SHEDULE TIME AND DELAY CLOSED #############################

												$arrivale_time1 = explode(' ',$arrivale_time);
												$depature_time1 = explode(' ',$depature_time);
												
												if($substr_count == 0)
												{											
													$csv_string_halt = $csv_string_halt.','.$station_no.','.$vname.','.$location.','.$arrivale_time1[1].','.$depature_time1[1].','.$hrs_min;
													$substr_count =1;  
												}
												else
												{
													$csv_string_halt = $csv_string_halt."#".','.$station_no.','.$vname.','.$location.','.$arrivale_time1[1].','.$depature_time1[1].','.$hrs_min; 
												}
							  
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
										
									}   //IF ADD ENTRYINRrEPORT              			
								}  //IF F<TOTAL_LINES-10
								/*else if((strtotime($datetime_cr)-strtotime($datetime_ref))>$interval)    // IF VEHICLE STOPS FOR 2 MINS 
								{            			
									echo "halt flag set\n";
									$halt_flag = 1;
								}	*/
							}  //else closed
						} // $xml_date_current >= $startdate closed
					}   // if xml_date!null closed
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
