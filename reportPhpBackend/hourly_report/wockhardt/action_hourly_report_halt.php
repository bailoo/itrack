<?php
$sheet1_row = 2;
$sheet2_row = 2;
function get_halt_xml_data($startdate, $enddate, $read_excel_path)
{	
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	global $village_violate_msg;

	global $violate_flag;	//#### GLOBAL VIOLATE FLAG
	echo "\nSD=".$startdate." ,ED=".$enddate;
	global $Vehicle;			//SENT FILE
	global $SNo;
	global $StationNo;
	global $Type;
	global $RouteNo;
	global $ReportShift;
	global $ArrivalDate;
	global $ArrivalTime;
	global $DepartureDate;
	global $DepartureTime;
	global $ScheduleTime;
	global $Delay;
	global $HaltDuration;
	global $Remark;
	global $ReportDate1;
	global $ReportTime1;
	global $ReportDate2;
	global $ReportTime2;
	global $TransporterM;
	global $TransporterI;
	global $Plant;
	//global $Km;
	global $Lat;
	global $Lng;
	global $DistVar;
	global $IMEI;
	global $objPHPExcel_1;
	
	global $last_vehicle_name;
	global $last_halt_time;

	$current_halt_time =0;
	$objPHPExcel_1 = null;	
	//$objPHPExcel_1 = new PHPExcel();
	
	$objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);	
	//sent_file/HOURLY_MAIL_VTS_HALT_REPORT_MORNING(MOTHER_DELHI).xlsx
	//echo "\nOBJPHPExcel=".$objPHPExcel_1;
	//for($i=0;$i<sizeod($Vehicle);$i++)
	//{
		//read(vehicle i XML from last time to cuurent time);
		//get last halt duration;
		//while(end of file)
		//{
			//read line 
			//$j=$i;
			//while($Vehicle[$j]==$Vehicle[$i])
			//{
				//if($DepartureTime[$i] != "")
				//{
				//	if(($ArrivalTime[$i]=="") && halt is true)
				//	{
				//		check for arrival;
				//		update array id required;
				//	}
				//	else if( ($DepartureTime[$i]=="") && ($ArrivalTime[$i]!=""))
				//	{
				//		check for depature;
				//		update array id required;
				//	}
			//	}
			//	j++;
		//	}
			
	//	}
		
	//	update halt of this vehicle;
	//	//### STORE LAST PROCESSED DETAIL (VEHICLE AND HALT TIME)
	//	$last_vehicle_name[] =$Vehicle[$i];
	//	$last_halt_time[] =$current_halt_time;	
		
	//	$i = $i+$j;
	//}	
	//## update_last_prcoes_time 
	
	//echo "\nvehicle_serial=".$vehicle_serial.", vid=".$vid.", vname=".$vname.", startdate=".$startdate.", enddate=".$enddate.", interval=".$interval.", report_shift=".$report_shift;
	//############ GET GLOBAL VARIABLES	
		
	//echo "\nSD=".$startdate." ,ED=".$enddate." ,read_excel_path=".$read_excel_path." ,VehicleSize=".sizeof($Vehicle);
	echo "\nSizeVehicle=".sizeof($Vehicle);	
	for($i=0;$i<sizeof($Vehicle);$i++)
	{		
		$violate_flag = true;
		$halt_dur = 0;
		
		$nodata = true;
		$nogps = true;
		echo "\nVehicle=".$i.",".$Vehicle[$i];
		$row = $i+2;
		//###### GET LAST HALT TIME
		$vehicle_serial = $IMEI[$i];
		$interval = 60;
		for($h=0;$h<sizeof($last_vehicle_name);$h++)
		{
			if($Vehicle[$i] == $last_vehicle_name[$h])
			{	
				$last_halt_time_excel = $last_halt_time[$h];
				/*if($last_halt_time_excel > 0)
				{			
					$interval = ($interval - $last_halt_time_excel);
				}	*/
				break;
			}
		}
		
		//echo "1\n";
		//get jcnt
		$j=$i;
		while($Vehicle[$j]==$Vehicle[$i])
		{
			$j++;	//J LIMIT
		}			
		
		$last_flag = false;
		if(sizeof($last_vehicle_name))
		{
			$last_flag = true;
			//echo "\nLastFlagTrue";
		}
		$halt_time_sec = 0;
		
		//echo "2\n";
		global $last_halt_sec_global;
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
		//echo "3\n";
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

		/*for($k=0;$k<sizeof($station_coord);$k++)       //INITIALISE VARIABLES
		{    
			$halt_flag[$k] = 0;
			$firstdata_flag_halt[$k] = 0; 
			$substr_count[$k] =0;  
			//$total_halt_time[$k] = 0;
		}*/
		
		//$interval=$user_interval*60;
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
		//echo "\nTEST3";
		get_All_Dates($datefrom, $dateto, &$userdates);
		//date_default_timezone_set("Asia/Calcutta");
		$current_datetime = date("Y-m-d H:i:s");
		$current_date = date("Y-m-d");

		$date_size = sizeof($userdates);
		$substr_count =0;
		
		$back_dir = "/var/www/html/itrack_vts";
		$back_dir_current = "/mnt/volume3";
		$back_dir_sorted = "/mnt/volume4";
		$AddEntryinrReport = false;
		//$f=0;
		//echo "\nTEST4";
		
		//echo "\nDateSize=".$date_size;
		for($d=0;$d<=($date_size-1);$d++)
		{	
			$xml_current = $back_dir_current."/current_data/xml_data/".$userdates[$d]."/".$vehicle_serial.".xml";
			if (file_exists($xml_current))      
			{  
				$xml_file = $xml_current;
				$CurrentFile = 1;
			}		
			else
			{
				$xml_file = $back_dir_sorted."/".$userdates[$d]."/".$vehicle_serial.".xml";
				$CurrentFile = 0;
			}		
			//echo "\nxml_file=".$xml_file;	
			
			if (file_exists($xml_file)) 
			{			
				$t=time();
				//echo "t=".$t."<br>";
				$xml_original_tmp = $back_dir."/xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$d.".xml";									      
				if($CurrentFile == 0)
				{
					copy($xml_file,$xml_original_tmp);
				}
				else
				{
					$xml_unsorted = $back_dir."/xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$d."_unsorted.xml";
					copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
					SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
					unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
				}      
				
				$total_lines = count(file($xml_original_tmp));		      
				$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
				$logcnt=0;
				$DataComplete=false;                  
				$vehicleserial_tmp=null;      
				$f=0;

				$tmp=0;
				//echo "\nxml_original_tmp=".$xml_original_tmp;		  
				if (file_exists($xml_original_tmp)) 
				{      
					//echo "\nFile Exist";
					$halt_once = false;
			
					//if($tmp==0){echo "in loop1";}
					
					//SWITCH MASTER VARIABLES
					set_master_variable($userdates[$d]);
					
					//if($tmp==0){echo "in loop2"; $tmp=1;}
					
					while(!feof($xml))          // WHILE LINE != NULL
					{
						//echo "\nline";
						//########## STORE VEHICLE COUNTER												
						
						//echo "\nDepartureTime IS NULL";
							$DataValid = 0;			
							$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE	
							if(strlen($line)>20)
							{
								$linetmp =  $line;
							}  				
							$linetolog =  $logcnt." ".$line;
							$logcnt++;
							//fwrite($xmllog, $linetolog);
							
							//echo "\nvc=".$vc." ,vd=".$vd." ,ve=".$ve." ,vh=".$vh;
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
									$nodata = false;
									//echo "\nIN DATE";

									//$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);						
									//if($status==0)
									//{
										//echo "\nStatus0";
										//continue;
									//}
									//echo "<textarea>".$line."</textarea>"; 
									//$line = '<marker msgtype="NORMAL" vehicleserial="354776033544005" vehiclename="SJ DKL-4005" ver="v1.34" fix="1" lat="26.60652N" lng="80.20758E" alt="123.0" speed="0.000000" datetime="2011-01-25 03:16:23" fuel="-" vehicletype="Heavy" no_of_sat="09" cellname="" distance="0.0" io1="7" io2="6" io3="387" io4="10" io5="6" io6="6" io7="4" io8="2" sig_str="0" sup_v="0.000000" speed_a="0" geo_in_a="0" geo_out_a="0" stop_a="0" move_a="0" lowv_a="0"/>';  
											 
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

										//$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
										//$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);						
										$vserial = $vehicle_serial; 
										$lat_tmp1 = explode("=",$lat_tmp[0]);							
										$lat_ref = preg_replace('/"/', '', $lat_tmp1[1]);
										$lng_tmp1 = explode("=",$lng_tmp[0]);
										$lng_ref = preg_replace('/"/', '', $lng_tmp1[1]);
									
										if($lat_ref!="" && $lng_ref!="")
										{
											$nogps = false;
										}
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
											//check for all vehicles with j as i to jcnt and check for customer where arrival is not there
											
											for($k=$i;$k<$j;$k++)
											{
												//echo "\nArrivalTime[$k]=".$ArrivalTime[$k]." ,DepartureTime[$k]=".$DepartureTime[$k];
												if(($ArrivalTime[$k]!="") && ($DepartureTime[$k] == ""))
												{															
													$arrtime_str = $ArrivalDate[$k]." ".$ArrivalTime[$k];
													$deptime = strtotime($depature_time);
													$arrtime = strtotime($arrtime_str);
													$halt_dur = ($deptime - $arrtime);		//THIS IS USED AT RUNTIME, COMMENT HERE LATER
													$hms_2 = secondsToTime($halt_dur);
													$hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
													//echo "\nDepartureFound";
													update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle[$k],$k,$StationNo[$k],$Lat[$k],$Lng[$k],$ScheduleTime[$k],$DistVar[$k],$Remark[$k],$startdate,$enddate,$lat_ref1, $lng_ref1, $lat_cr, $lng_cr,$arrivale_time,$depature_time,$RouteNo[$k],$hrs_min,$Type[$k],2);
												}
											}													
											
											$last_halt_time_excel = 0;
											$halt_flag = 0;
										}
										else if(($distance <= 0.100) && ($halt_flag == 0) && ( (strtotime($datetime_cr)-strtotime($datetime_ref))<($interval-$last_halt_time_excel)) )
										{
											$current_halt_time = $current_halt_time + (strtotime($datetime_cr)-strtotime($datetime_ref));
										}									
										else if(($distance <= 0.100) && ($halt_flag == 0) && ( (strtotime($datetime_cr)-strtotime($datetime_ref))>($interval-$last_halt_time_excel)) )    // IF VEHICLE STOPS FOR 2 MINS 
										{            													
											for($k=$i;$k<$j;$k++)
											{
												if($ArrivalTime[$k]=="")
												{																														
													update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle[$k],$k,$StationNo[$k],$Lat[$k],$Lng[$k],$ScheduleTime[$k],$DistVar[$k],$Remark[$k],$startdate,$enddate,$lat_ref1, $lng_ref1, $lat_cr, $lng_cr,$datetime_ref,$depature_time,$RouteNo[$k],$hrs_min,$Type[$k],1);
												}
											}												
											$current_halt_time = 0;
											$halt_once =1;
											//echo "\nHALT FLAG SET, datetime=".$datetime;
											$halt_flag = 1;
											$lat_ref1 = $lat_cr;
											$lng_ref1 = $lng_cr;
											$datetime_ref = $datetime_cr;										
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
									}  //else closed
								} // $xml_date_current >= $startdate closed
							}   // if xml_date!null closed
							
							if(($xml_date >= $enddate) && ($halt_flag == 1) && ($AddEntryinrReport==false))
							{
								/*//echo "\nELSEIF HALT1>XML_DATE, datetime=".$datetime;
								$arrivale_time = $datetime_ref;
								$starttime = strtotime($datetime_ref);*/									  
								/*$depature_time="-";
								$halt_dur = "-";
								$hrs_min = "-";*/
								$AddEntryinrReport=true;
							}
							
							if($AddEntryinrReport)
							{
								//if(($violate_flag) && ($halt_dur > 900))
								echo "\nViolated";
								if(($violate_flag) && ($halt_dur > 120))
								{
									$village_violate_msg.= "<br><font color='purple' size=1>*Vehicle:</font><font color='blue' size=1>".$Vehicle."</font><font color='purple' size=1> Violated the Specified Location</font>";
								}
							}
							
							$f++;
					}   // OUTER WHILE CLOSED
					
				} // if original_tmp closed 
				//echo "vehicle_name=".$vname."csv_string_halt==".$csv_string_halt."<br>";
				fclose($xml);            
			//	unlink($xml_original_tmp);
			} // if (file_exists closed
		}  // for closed
				
		//### STORE LAST HALT TIME OF VEHICLE
		$last_vehicle_name[] =$Vehicle[$i];
		$last_halt_time[] =$current_halt_time;	
		
		$msg = "";
		if($nodata)
		{
			$msg = "INACTIVE";
		}
		else if($nogps)
		{
			$msg = "NOGPS";
		}
		update_remark($objPHPExcel_1, $msg, $i);
		
		//######### UPDATE EXTRA SHEETS
		$customer_visited = array();
		$customer_unvisited = array();
		for($k=$i;$k<$j;$k++)
		{
			//######## COUNT VISITED CUSTOMERS
			$pos_c1 = strpos($StationNo[$k], "@");
			//echo "\nPOS=".$pos;
			if($pos_c1 !== false)
			{
				//echo "\nNegative Found";
				$customer_tmp1_A = explode("@", $StationNo[$k]);
			}
			else
			{
				$customer_tmp1_A[0] = $StationNo[$k];
			}		
				
			if($DepartureTime[$k]!="")
			{																																
				$customer_visited[] = $customer_tmp1_A[0];
			}
			else
			{
				$unvisited_flag = true;
				for($m=0;$m<sizeof($customer_visited);$m++)
				{
					if($customer_visited[$m]==$customer_tmp1_A[0])
					{
						$unvisited_flag = false;
					}
				}
				if($unvisited_flag)
				{
					$customer_unvisited[] = $customer_tmp1_A[0];
				}
			}
		}
		//echo "\nVehicle=".$Vehicle[$i]." ,Route=".$RouteNo[$i]." ,vc=".sizeof($customer_visited)." ,uc=".sizeof($customer_unvisited);
		update_extra_sheets($objPHPExcel_1,$i,$Vehicle[$i],$RouteNo[$i],$customer_visited,$customer_unvisited);
		if($j>$i)
		{
			$i=$j-1;
		}
		
	} //##### EXCEL VEHICLE LOOP CLOSED
		
	//echo "\nCSV_STRING_HALT=".$csv_string_halt;
	//####### UPDATE EXTRA SHEET	
	
	echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($read_excel_path);
	echo date('H:i:s') , " File written to " , $read_excel_path , EOL;
	
echo "\nHALT CLCLOSED";
}	

//######## UPDATE VEHICLE
function update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle, $k, $StationNo, $Lat, $Lng, $ScheduleTime, $DistVar, $Remark, $startdate,$enddate, $lat_ref1, $lng_ref1, $lat_cr, $lng_cr, $arrivale_time,$depature_time, $RouteNo, $hrs_min, $Type, $status_entered)
{										
	//echo "\nUPDATE VEHICLE::".$read_excel_path.", ".$Vehicle.", ".$k.", ".$StationNo.", ".$Lat.", ".$Lng.", ".$ScheduleTime.", ".$DistVar.", ".$Remark.", ".$startdate.", ".$enddate.", ".$status_entered;	
	global $violate_flag;
	
	global $unchanged;
	global $ArrivalTime;
	global $DepartureTime;
	
	global $transporter_m;
	global $vehicle_m;

	//#######################	
	$place = "-";												
	$station_no = "-";
	//$transporter_name = "-";
	$schedule_time = "-";
	$delay = "-";
	$entered_station = 0;
									
	$lat_g = trim($Lat);
	$lng_g = trim($Lng);										
  
	if($DistVar == 0)
	{
		$DistVar = 0.1;
	}    
	
	$distance_station = 0;              
	if( ($lat_g!="") && ($lng_g!="") && ($StationNo!="") )
	{
		//echo "\nDIST::datetime=".$datetime." ,op_date1=".$op_date1." ,op_date2=".$op_date2." \ndistance=".$distance;
		calculate_distance($lat_ref1, $lat_g, $lng_ref1, $lng_g, &$distance_station1);
		calculate_distance($lat_cr, $lat_g, $lng_cr, $lng_g, &$distance_station2);
		
		if($distance_station1<$distance_station2)
		{
			$distance_station=$distance_station1;
		}
		else
		{
			$distance_station=$distance_station2;
		}
		
		//echo "\nVehicle entered in station=CustomerNo=".$StationNo." ,arrivale_time=".$arrivale_time." ,lat_ref1=".$lat_ref1." ,lng_ref1=".$lng_ref1." ,lat_cr=".$lat_cr." ,lng_cr=".$lng_cr."dist=".$distance_station.", distvar=".$DistVar;  
		//echo "\n1=CustomerNo=".$StationNo." ,arrivale_time=".$arrivale_time." ,lat_ref1=".$lat_ref1." ,lng_ref1=".$lng_ref1." ,lat_cr=".$lat_cr." ,lng_cr=".$lng_cr."dist=".$distance_station.", distvar=".$DistVar;  
		//echo "\ndistance_station=".$distance_station.", distance_variable=".$DistVar;
		if($distance_station < $DistVar)
		{			
			$station_no = $StationNo;																								
			//$customer_visited[] = $station_no;
			//$customer_type[] = $Type[$i];
			$entered_station = 1;
			$violate_flag = false;	//#### VEHICLE ENTERED IN SPECIFIED LOCATION
			//break;
		}
	}								

	//##########################################			
	$row = $k+2;
	
	$report_time1 = explode(' ',$startdate);
	$report_time2 = explode(' ',$enddate);
	
	//########## UPDATE LAST HALT TIME 
	$last_halt_sec_global = 0;
	
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

	$valid_halt = false;												
	$route_no="";
		
	$schedule_in_time_tmp = $ScheduleTime;							
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
	if($hrs_min=="")
	{
		$hrs_min = "-";
	}
	///####################### GET SHEDULE TIME AND DELAY CLOSED #############################	
	if($Type=="Plant")
	{
		$schedule_in_time = "-";
	}	
	//$cum_dist = $cum_dist + $distance;	
	if($vname!=$prev_vehicle)
	{
		$cum_dist = 0;
	}
				
	//######## FINAL UPDATION																						
	$arrival_time1 = explode(' ',$arrivale_time);
	$depature_time1 = explode(' ',$depature_time);	
	//echo "\nArrivalTime[$i]=".$ArrivalTime[$i];
	
	if(($status_entered==1)	&& ($entered_station==1))//###### CHECK FOR ALL (ARRIVAL AND DEPARTURE)
	{
		//echo "\nEnteredStation";
		//##UPDATE ARRIVAL																																			
		//echo "\nIF ARRIVAL NULL, arrival_time1[0]=".$arrival_time1[0]." ,arrival_time1[1]=".$arrival_time1[1]." row=".$row;
		//echo "\ndepature_time1".$depature_time1[0]." ,depature_time1[1]=".$depature_time1[1];
		//echo "\nhrs_min".$hrs_min." ,time_delay=".$time_delay;
		//echo "\nobjPHPExcel_1=".$objPHPExcel_1;				
		$col_tmp = 'G'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $arrival_time1[0]);

		$col_tmp = 'H'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $arrival_time1[1]);

		//$col_tmp = 'I'.$row;
		//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $depature_time1[0]);

		//$col_tmp = 'J'.$row;
		//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $depature_time1[1]);
		
		$col_tmp = 'L'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $time_delay);		

		//$col_tmp = 'M'.$row;
		//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $hrs_min);			

		$col_tmp = 'O'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[0]);

		$col_tmp = 'P'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[1]);

		$col_tmp = 'Q'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time2[0]);

		$col_tmp = 'R'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time2[1]);
			
		$ArrivalTime[$k] = $arrival_time1[1];				
		$unchanged = false;				
		//###############																						
	}
	if(($status_entered==2)	&& ($entered_station==0))//####### CHECK FOR DEPARTURE
	{
		//echo "\nDepartureWrite";
		//##UPDATE DEPARTURE
		$col_tmp = 'I'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $depature_time1[0]);

		$col_tmp = 'J'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $depature_time1[1]);
		
		//$col_tmp = 'L'.$row;
		//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $time_delay);	

		$col_tmp = 'M'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $hrs_min);			

		$col_tmp = 'O'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[0]);

		$col_tmp = 'P'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[1]);

		$col_tmp = 'Q'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time2[0]);

		$col_tmp = 'R'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time2[1]);

		//$col_tmp = 'S'.$row;
		//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $transporter_name_master);

		//$col_tmp = 'T'.$row;
		//$plant = "-";
		//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $plant);		
		$DepartureTime[$k] = $depature_time1[1];
		
		$unchanged = false;
		//echo "\nIF ARRIVAL NOT NULL";												
	}
	
	$prev_vehicle = $vname;
	//echo "\nSerial=".$sno;
	$sno++;
	//echo "\nHALT COMPLETED";
	$entered_station = 0;
	//break;                            											
	//$entered_station = 0;                            							
}
////### UPDATE HALT CLOSED

function update_extra_sheets($objPHPExcel_1,$i,$Vehicle,$RouteNo,$customer_visited,$customer_unvisited)
{
	//echo "\nVehicle=".$Vehicle." ,Route=".$RouteNo." ,sizeCV=".sizeof($customer_visited)." ,sizeCU=".sizeof($customer_unvisited);
	global $sheet1_row;
	global $sheet2_row;	
	//echo "\nInUpdateExtraSheet";
	
	$row1=2;
	$row2=2;

	$all_completed = false;
	$valid_match = false;		
	$customer_all_str = "";
	$customer_visited_str = "";
	$customer_unvisited_str = "";
	$vehicle_name_rdb1 = $vehicle_excel[$j];
	$route_name_rdb1 = $route_excel[$j];
	
	for($k=0;$k<sizeof($customer_visited);$k++)		//##### TOTAL CUSTOMER VISITED
	{							
		$customer_visited_str = $customer_visited_str.$customer_visited[$k].",";
	}
	if($customer_visited_str!="") { $customer_visited_str = substr($customer_visited_str, 0, -1);}
	
	for($k=0;$k<sizeof($customer_unvisited);$k++)		//##### TOTAL CUSTOMER VISITED
	{							
		$customer_unvisited_str = $customer_unvisited_str.$customer_unvisited[$k].",";
	}
	if($customer_unvisited_str!="") { $customer_unvisited_str = substr($customer_unvisited_str, 0, -1);}

	//####### UPDATE SHEET2 : ALL VISITED
	//echo "\ncustomer_visited_str=".$customer_visited_str." ,customer_unvisited_str=".$customer_unvisited_str;		
	if((sizeof($customer_visited)>0) && (sizeof($customer_unvisited)==0))
	{
		//echo "\nValidSheet2";
		//######### FILL SHEET2
		$col_tmp = 'A'.$sheet1_row;
		$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , $Vehicle);
		$col_tmp = 'B'.$sheet1_row;					
		$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , $RouteNo); 					
		$col_tmp = 'C'.$sheet1_row;
		$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , $customer_visited_str);
		$sheet1_row++;
	}
	//####### UPDATE SHEET3 : PARTIAL VISITED
	if( (sizeof($customer_unvisited)>0) && ($customer_unvisited_str!=""))
	{
		//echo "\nValidSheet3";
		//######### FILL SHEET3
		if($customer_completed!="") { $customer_completed = substr($customer_completed, 0, -1);}
		if($customer_incompleted!="") { $customer_incompleted = substr($customer_incompleted, 0, -1);}
		
		$col_tmp = 'A'.$sheet2_row;
		$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $Vehicle);
		$col_tmp = 'B'.$sheet2_row;					
		$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $RouteNo); 					
		$col_tmp = 'C'.$sheet2_row;
		$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $customer_visited_str);
		$col_tmp = 'D'.$sheet2_row;
		$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $customer_unvisited_str);					
		$sheet2_row++;	
	}								
	//#### EXTRA TAB CLOSED ###########	
}

function update_remark($objPHPExcel_1, $msg, $i)
{										
	//echo "\nInUpdateRemark";
	$row = $i+2;
	$col_tmp = 'N'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $msg);	
}

?>
