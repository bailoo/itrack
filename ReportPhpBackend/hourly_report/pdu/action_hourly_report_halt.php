<?php
$sheet1_row = 2;
$sheet2_row = 2;
//$userdates = array();
$halt_out_flag1 = false;
$halt_in_flag1 = false;

$min_temp1=0;
$min_datetime1="";
$max_temp1=0;
$max_datetime1="";

function get_halt_xml_data($startdate, $enddate, $read_excel_path, $min_max_temperature_path, $time1_ev, $time2_ev)
{
	echo "\nEnddate	=".$enddate." ,time1_ev=".$time1_ev." ,time2_ev=".$time2_ev;
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	
	echo "\nSD=".$startdate." ,ED=".$enddate;
	global $Vehicle;			//SENT FILE
	global $SNo;
	global $StationNo;
	global $Type;
	//global $RouteNo;
	global $ReportShift;
	global $ArrivalDate;
	global $ArrivalTime;
	global $DepartureDate;
	global $DepartureTime;
	global $ScheduleTime;
	global $Delay;
	global $HaltDuration;
	
	global $InTemp;
	global $OutTemp;	
	global $MinTemp;
	global $MinTempDate;
	global $MinTempTime;
	global $MaxTemp;
	global $MaxTempDate;
	global $MaxTempTime;
	
	global $Remark;
	global $ReportDate1;
	global $ReportTime1;
	global $ReportDate2;
	global $ReportTime2;
	global $TransporterM;
	global $TransporterI;
	global $Plant;
	//global $Km;
	//echo "\nDebug1";
	global $Lat;
	global $Lng;
	global $DistVar;
	global $IMEI;
	global $RouteType;	
	global $NO_GPS;

	global $objPHPExcel_1;
	global $objPHPExcel_2;
	
	global $last_vehicle_name;
	global $last_halt_time;
	global $last_halt_time_new;
	
	//######## SORTED ROUTES
	global $Vehicle_CI;
	global $StationNo_CI;
	global $RouteNo_CI;
	global $RouteType_CI;
	global $ArrivalTime_CI;
	global $TransporterI_CI;
	
	//##### GET TEMPERATURE
	global $LastTempVehicle;	
	global $LastMinTemp;
	global $LastMinDate;
	global $LastMinTime;
	global $LastMaxTemp;
	global $LastMaxDate;
	global $LastMaxTime;

	global $halt_out_flag1;
	global $halt_in_flag1;
	global $min_temp1;
	global $min_datetime1;
	global $max_temp1;
	global $max_datetime1;
	
	$current_halt_time =0;
	$objPHPExcel_1 = null;	
	//$objPHPExcel_1 = new PHPExcel();
	
	$objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);
	//echo "\nDebug2:".$read_excel_path;
	//##### REMOVE EXTRA SHEETS
	$objPHPExcel_1->removeSheetByIndex(2);
	$objPHPExcel_1->removeSheetByIndex(1);
	
	//echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($read_excel_path);
	echo date('H:i:s') , " File written to " , $read_excel_path , EOL;
		
	//###### RELOAD SHEET
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);
	
	//####### TEMPERATURE WRITER
	$objPHPExcel_2 = null;		
	$objPHPExcel_2 = PHPExcel_IOFactory::load($min_max_temperature_path);	
	//####### TEMPERATURE CLOSED
	
	//####### RECREATE EXTRA SHEETS #####################	
	$header_font = array(
	'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => '000000'), //RED
	'size'  => 10
	//'name'  => 'Verdana'
	));
	
	$objPHPExcel_1->createSheet();
	$objPHPExcel_1->setActiveSheetIndex(1)->setTitle('Customer Completed');
		
	$objPHPExcel_1->createSheet();
	$objPHPExcel_1->setActiveSheetIndex(2)->setTitle('Customer Pending');		
	echo "\nSecond tab";
	$row =1;
	//###### HEADER
	$col_tmp = 'A'.$row;
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "Vehicle");
	$objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'B'.$row;
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "CustomerCompleted(All)");
	$objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'C'.$row;
	$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , "Transporter(I)");
	$objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
	$row++;						
	//#### SECOND TAB CLOSED ##################################################################	
	
	//############################### THIRD TAB ###############################################
	echo "\nThird tab";
	$row =1;
	//####### DEFINE HEADER
	$col_tmp = 'A'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Vehicle");
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'B'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Customer Completed");
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'C'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Customer Incompleted");					
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$col_tmp = 'D'.$row;
	$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , "Transporter(I)");					
	$objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
	$row++;	
	//######## EXTRA SHEET CLOSED
		
	//echo "\nSD=".$startdate." ,ED=".$enddate." ,read_excel_path=".$read_excel_path." ,VehicleSize=".sizeof($Vehicle);
	echo "\nSizeVehicle=".sizeof($Vehicle);	
	$v=0;
	for($i=0;$i<sizeof($Vehicle);$i++)
	{	
		$lat_ref = 0.0;$lng_ref = 0.0;$lat_ref1 = 0.0;$lng_ref1 = 0.0;$lat_cr =0.0;$lng_cr =0.0;	
		//echo "<br>LAST1=".$LastMinTemp[$v];
		//$temperature="";
		$io = get_io($IMEI[$i],'temperature');
		//echo "<br>IO=".$io;
		$min_temp1 = 0; $min_datetime1="";
		$max_temp1 = 0; $max_datetime1="";
		$halt_in_flag1 = false;
		$halt_out_flag1 = false;
		//$userdates = array();
		$nodata = true;
		$nodata_last = true;
		$nogps = true;
		echo "<br>Vehicle=".$i.",".$Vehicle[$i];
		$row = $i+2;
		//###### GET LAST HALT TIME
		$vehicle_serial = $IMEI[$i];
		$interval = 60;
		for($h=0;$h<sizeof($last_vehicle_name);$h++)
		{
			if(trim($Vehicle[$i]) == trim($last_vehicle_name[$h]))
			{	
				$last_halt_time_excel = $last_halt_time[$h];
				//echo "<br>LastHaltExcel Found:".$last_halt_time_excel;
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
		
		//$abspath = "D:\\test_app";
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
		
		//$interval=$user_interval*60;
		//echo "interval=".$interval."<br>";
		$fix_tmp = 1;
		$xml_date_latest="1900-00-00 00:00:00";
		$CurrentLat = 0.0;
		$CurrentLong = 0.0;
		$LastLat = 0.0;
		$LastLong = 0.0;
		$firstData = 0;
		$first_temp = true;
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
		//global $userdates;
		//$userdates = array();
		get_All_Dates($datefrom, $dateto, &$userdates);
		//get_All_Dates($datefrom, $dateto);
		//date_default_timezone_set("Asia/Calcutta");
		$current_datetime = date("Y-m-d H:i:s");
		$current_date = date("Y-m-d");

		$date_size = sizeof($userdates);
		$substr_count =0;
		
		//$back_dir = "D:\\itrack_vts";
		$back_dir = "/var/www/html/itrack_vts";
		//$back_dir_current = "/mnt/volume3";
		//$back_dir_sorted = "/mnt/volume4";
		$AddEntryinrReport = false;
		//$f=0;
		//echo "\nTEST4";
		
		//echo "\nDateSize=".$date_size;
		//include("D:\\test_app/common_xml_path.php");
		include("/var/www/html/vts/beta/src/php/common_xml_path.php");
		for($d=0;$d<=($date_size-1);$d++)
		{	
			$xml_current = $xml_data."/".$userdates[$d]."/".$vehicle_serial.".xml";
			if (file_exists($xml_current))      
			{  
				$xml_file = $xml_current;
				$CurrentFile = 1;
			}		
			else
			{
				$xml_file = $sorted_xml_data."/".$userdates[$d]."/".$vehicle_serial.".xml";
				$CurrentFile = 0;
			}		
			//echo "\nxml_file=".$xml_file;	
			
			//######### STORE MEANINGFUL DATA BETWEEN TWO STS DATES
			if (file_exists($xml_file)) 
			{	
				//echo "<br>FileExists1";
				$nodata=false;		
				$t=time();
				//echo "t=".$t."<br>";
				$xml_original_tmp = $back_dir."/xml_tmp/original_xml/tmp".$vehicle_serial."_".$t."_".$d.".xml";
				copy($xml_file,$xml_original_tmp);

				//$total_lines = count(file($xml_original_tmp));		      
				//echo "\nTotalLines=".$total_lines;
				$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
				
				if (file_exists($xml_original_tmp)) 
				{
					//echo "<br>FileExists2";
					//$vserial_sel[] = array(); 					
					$sts_date_sel = array();
					$xml_date_sel = array();				
					$lat_sel = array(); 					
					$lng_sel = array(); 					
					$speed_sel = array();
					$temp_sel = array();
									
					//SWITCH MASTER VARIABLES
					set_master_variable($userdates[$d]);
					
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
							$status = preg_match('/'.$vg.'="[^"]+/', $line, $sts_tmp);
							$sts_tmp1 = explode("=",$sts_tmp[0]);
							$sts = preg_replace('/"/', '', $sts_tmp1[1]);	
							$sts_date = $sts;		
							
							$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
							$datetime_tmp1 = explode("=",$datetime_tmp[0]);
							$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
							$xml_date = $datetime;								
						}       

						 //echo "\nXML_DATE=".$xml_date." ,DataValid=".$DataValid." ,vehicle_serial=".$vehicle_serial;   

						if($sts_date!=null)
						{								
							//echo "\nStartDate=".$startdate." ,EndDate=".$enddate;
							//if(($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
							if(($sts_date >= $startdate && $sts_date <= $enddate) && ($sts_date!="-") && ($DataValid==1) )
							{
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
								if($io=='io1')
								{
									$io='i';
								}
								if($io=='io2')
								{
									$io='j';
								}
								if($io=='io3')
								{
									$io='k';
								}
								if($io=='io4')
								{
									$io='l';
								}
								if($io=='io5')
								{
									$io='m';
								}
								if($io=='io6')
								{
									$io='n';
								}
								if($io=='io7')
								{
									$io='o';
								}
								if($io=='io8')
								{
									$io='p';
								}
								
								$status = preg_match('/'.$io.'=\"[^"]+/', $line, $temperature_count_tmp);
								//echo "<br>Status=".$status." io=".$io;

								//$vserial_sel[] = $vehicle_serial; 
								$sts_date_sel[] = $sts_date;
								$xml_date_sel[] = $xml_date;
								$lat_tmp1 = explode("=",$lat_tmp[0]);							
								$lat_sel[] = preg_replace('/"/', '', $lat_tmp1[1]);
								$lng_tmp1 = explode("=",$lng_tmp[0]);
								$lng_sel[] = preg_replace('/"/', '', $lng_tmp1[1]);
								$speed_tmp1 = explode("=",$speed_tmp[0]);
								$speed_sel[] = preg_replace('/"/', '', $speed_tmp1[1]);								
								$temperature_count1 = explode("=",$temperature_count_tmp[0]);
								$temp_sel[] = preg_replace('/"/', '', $temperature_count1[1]);								
							}
						}						
					}
					
					fclose($xml);   					
				}
			}
			
			//###### SORT THE ARRAYS
			for($x = 1; $x < sizeof($xml_date_sel); $x++) 
			{
				$value = $xml_date_sel[$x];

				$tmp_datetime = $xml_date_sel[$x];
				$tmp_sts = $sts_date_sel[$x];					
				$tmp_lat = $lat_sel[$x];
				$tmp_lng = $lng_sel[$x];
				$tmp_speed = $speed_sel[$x];
				$tmp_temperature = $temp_sel[$x];			
									
				$z = $x - 1;
				$done = false;
				while($done == false)
				{
					$date_tmp1 = $xml_date_sel[$z];						

					if ($date_tmp1 >$value)
					{
						$xml_date_sel[$z + 1] = $xml_date_sel[$z];
						$sts_date_sel[$z + 1] = $sts_date_sel[$z];
						$lat_sel[$z + 1] = $lat_sel[$z];
						$lng_sel[$z + 1] = $lng_sel[$z];
						$speed_sel[$z + 1] = $speed_sel[$z];
						$temp_sel[$z + 1] = $temp_sel[$z];
						
						$z = $z - 1;
						if ($z < 0)
						{
							$done = true;
						}
					}
					else
					{
						$done = true;
					}
				}                
				$xml_date_sel[$z + 1] = $tmp_datetime;
				$sts_date_sel[$z + 1] = $tmp_sts;
				$lat_sel[$z + 1] = $tmp_lat;
				$lng_sel[$z + 1] = $tmp_lng;
				$speed_sel[$z + 1] = $tmp_speed;
				$temp_sel[$z + 1] = $tmp_temperature;	
			}			
			//###### SORTING CLOSED
			
			//##### CLOSED STS SORTED MEANINGFUL DATA ##########################
			//##################################################################			
			
			$total_lines = sizeof($xml_date_sel);		      				
			$DataComplete=false;                  
			$vehicleserial_tmp=null;      
			$f=0;
			$tmp=0;
				
			if (sizeof($xml_date_sel)>0) 
			{      
				//echo "\nFile Exist";
				$halt_once = false;
				
				for($y=0;$y<sizeof($xml_date_sel);$y++)          // WHILE LINE != NULL
				{
					//echo "\nline";
					//########## STORE VEHICLE COUNTER																	  					
					$nodata = false;															 					
					$datetime = $xml_date_sel[$y];	
					
					if((strtotime($datetime) > strtotime($time1_ev)) && (strtotime($datetime) < strtotime($enddate)))
					{		
						$nodata_last = false;			
						$temperature = $temp_sel[$y];						
						if($temperature>=-30 && $temperature<=70)
						{
							$temperature = $temp_sel[$y];
							//echo "<br>Temperature=".$temperature;
						}
						else
						{
							$temperature = 0.0;
						}
						
						if($firstdata_flag==0)
						{							
							$halt_flag = 0;
							$firstdata_flag = 1;

							//$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
							//$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);						
							$vserial = $vehicle_serial; 												
							$lat_ref = $lat_sel[$y];						
							$lng_ref = $lng_sel[$y];
						
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
							//echo "<br>TemperatureM=".$temperature;
							/*$min_temp1 = $temperature; 
							$min_datetime1 = $datetime;
							$max_temp1 = $temperature;
							$max_datetime1 = $datetime;				
								
                                                        $tmpPrev=$temperature;
                                                        $dateTimePrev=strtotime($datetime);*/																
						}                 	
						else
						{    								

                                                     	$tmpNext=$temperature;
                                                        $dateTimeNext=strtotime($datetime);
                                                        $tmpDiff=$tmpNext-$tmpPrev;
                                                        $tmpFlag=1;
                                                        if((($dateTimeNext-$dateTimePrev)<60) && (abs($tmpDiff)>10))
                                                        {
                                                           $tmpFlag=0;
                                                        }

							if($tmpFlag==1)
                                                        {

                                                           $temperature = preg_replace('/[^0-9-]/s', '.', $temperature);
	                                                   $temperaturetmp=substr_count($temperature, '.');
                                                           if($temperaturetmp<=1)
                                                            {
									
								if($first_temp)
								{
									$min_temp1 = $temperature;
                                                        		$min_datetime1 = $datetime;
	                                                        	$max_temp1 = $temperature;
	                                                        	$max_datetime1 = $datetime;
									$first_temp = true;
								}
								else
								{



									if( ($halt_out_flag1 && !$halt_in_flag1) || (!$halt_out_flag1 && !$halt_in_flag1) ) //#### IF -MINIMUM ONE HALT OUT OCCURED ||
									{																			//###  IF -NO HALT IN AND OUT OCCURED
								

										if($temperature < $min_temp1)
										{									
											$min_temp1 = $temperature;
											$min_datetime1 = $datetime;
											//echo "<br>temperatureMin=".$temperature." ,min_temp1=".$min_temp1;
										}		
										if($temperature > $max_temp1)
										{									
											$max_temp1 = $temperature;
											$max_datetime1 = $datetime;
											//echo "<br>temperatureMax=".$temperature." ,min_temp1=".$max_temp1;
										}
									}
								}
							}
							}							
							//echo "<br>Next";
							//GET NEXT RECO
							$lat_cr = $lat_sel[$y];																					
							$lng_cr = $lng_sel[$y];
							$datetime_cr = $datetime;																		
							
							$date_secs2 = strtotime($datetime_cr);
							calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);
							//$distance = calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr);
							//if(($distance > 0.0100) || ($f== $total_lines-2) )
							//echo "\nF=".$f." ,total_lines=".$total_lines;										
																								
							//###### FOR IRREGULAR DATA FILTER CODE
							$tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;

							calculate_distance($latlast, $lat_cr, $lnglast, $lng_cr, &$distance1);
							//$distance1 = calculate_distance($latlast, $lat_cr, $lnglast, $lng_cr);
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
												
							//if (($halt_flag == 1) && ($distance > 0.100))
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
									if(($ArrivalTime[$k]!="") && ($DepartureTime[$k] == "")) //DEPARTURE
									{															
										//echo "<br>DepartureFound:temperature=".$temperature;
										$arrtime_str = $ArrivalDate[$k]." ".$ArrivalTime[$k];
										$deptime = strtotime($depature_time);
										$arrtime = strtotime($arrtime_str);
										$halt_dur = ($deptime - $arrtime);		//THIS IS USED AT RUNTIME, COMMENT HERE LATER
										$hms_2 = secondsToTime($halt_dur);
										$hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
										//echo "\nDepartureFound";
																			
										update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle[$k],$k,$StationNo[$k],$Lat[$k],$Lng[$k],$ScheduleTime[$k],$DistVar[$k],$Remark[$k],$startdate,$enddate,$lat_cr, $lng_cr, $lat_cr, $lng_cr,$arrivale_time,$depature_time,$hrs_min,$Type[$k],2,$temperature);
										//$halt_out_flag1 = true;
										//$halt_in_flag1 = false;
										
										//###### UPDATE TEMPERATURE ON EVERY DEPARTURE
										/*$min_temp1 = $temperature; 
										$min_datetime1 = $datetime;
										$max_temp1 = $temperature;
										$max_datetime1 = $datetime;*/
									}
								}													
								
								$last_halt_time_excel = 0;
								$halt_flag = 0;
							}
							if(($distance <= 0.100) && ($halt_flag == 0) && ( (strtotime($datetime_cr)-strtotime($datetime_ref))<($interval-$last_halt_time_excel)) )
							{							
								$current_halt_time = $current_halt_time + (strtotime($datetime_cr)-strtotime($datetime_ref));
								//echo "<br>HaltContinued:".$current_halt_time;
							}									
							//else if(($distance <= 0.100) && ($halt_flag == 0) && ( (strtotime($datetime_cr)-strtotime($datetime_ref))>($interval-$last_halt_time_excel)) )    // IF VEHICLE STOPS FOR 2 MINS 
							else if((($distance <= 0.100) && ($halt_flag == 0) && ( (strtotime($datetime_cr)-strtotime($datetime_ref))>($interval-$last_halt_time_excel))) || ($speed_sel[$y]<5.0))
							{							
								for($k=$i;$k<$j;$k++)
								{
									if($ArrivalTime[$k]=="") //ARRIVAL
									{																						
										//echo "<br>LastMinTempA[v]=".$LastMinTemp[$v];
										if($LastMinTemp[$v]!="")	//##### READ LAST TEMPERATURE FROM FILE
										{
											$min_temp1 = $LastMinTemp[$v];											
											$min_datetime1 = $LastMinDate[$v]." ".$LastMinTime[$v];
											$max_temp1 = $LastMaxTemp[$v];
											$max_datetime1 = $LastMaxDate[$v]." ".$LastMaxTime[$v];											
										}
										/*else if($halt_out_flag1) 	//##### READ IF -NOT IN FILE AND NOT HALT OUT OCCURED
										{
											$min_temp1 = $temperature;											
											$min_datetime1 = $datetime;
											$max_temp1 = $temperature;
											$max_datetime1 = $datetime;										
										}*/										
										//echo "<br>MIN_TEMP2=".$min_temp1." ,maxtemp=".$max_temp1;										
										//echo "<br>ArrivalFound";
										update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle[$k],$k,$StationNo[$k],$Lat[$k],$Lng[$k],$ScheduleTime[$k],$DistVar[$k],$Remark[$k],$startdate,$enddate,$lat_ref1, $lng_ref1, $lat_cr, $lng_cr,$datetime_ref,$depature_time,$hrs_min,$Type[$k],1,$temperature);
									}
								}												
								$current_halt_time = 0;
								$halt_once =1;
								//echo "\nHALT FLAG SET, datetime=".$datetime;
								$halt_flag = 1;
								$lat_ref1 = $lat_cr;
								$lng_ref1 = $lng_cr;
								$datetime_ref = $datetime_cr;
								//echo "<br>HaltOver:".$current_halt_time;							
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
								$datetime_ref= $datetime_cr;    //modified   				
								$halt_flag = 0;			//modified										
							}									
						}  //else closed
					}
						$f++;
				}   // CLOSED- SORTED DATA FOR LOOP
				
			} // if original_tmp closed 
				//echo "vehicle_name=".$vname."csv_string_halt==".$csv_string_halt."<br>";			         
			//	unlink($xml_original_tmp);
		}  // CLOSED- DATE FOR LOOP
				
		//### STORE LAST HALT TIME OF VEHICLE		
		$last_vehicle_name[] =$Vehicle[$i];
		$last_halt_time_new[] =$current_halt_time;	
		//echo "<br>CurrentHaltTime:".$Vehicle[$i]." :".$current_halt_time;
		
		$msg = "";
		if($nodata)
		{
			$msg = "INACTIVE";
		}
		else if(!$nogps)			//GPS FOUND
		{
			update_nogps($objPHPExcel_1, "0", $i);
			$NO_GPS[$i] = "0";
			$msg = "";
		}
		else if(($NO_GPS[$i]=="") && ($nogps))		//FIRST TIME : GPS NOT FOUND
		{
			update_nogps($objPHPExcel_1, "1", $i);
			$NO_GPS[$i] = "1";
			$msg = "NO GPS";
		}
	
		$Remark[$i] = $msg;
		update_remark($objPHPExcel_1, $msg, $i);
		
		//######### UPDATE EXTRA SHEETS
		$customer_visited = array();
		$customer_unvisited = array();
		
		$store=true;
		for($k=$i;$k<$j;$k++)
		{			
			/*if($Vehicle[$k]==":")
			{
				$store=false;
			}
			if($store)*/
			//{
				$Vehicle_CI[] = $Vehicle[$k];
				$StationNo_CI[] = $StationNo[$k];
				//$RouteNo_CI[] = $RouteNo[$k];
				//$RouteType_CI[] = $RouteType[$k];
				//echo "<br>RouteStore=".$RouteNo[$k];
				$ArrivalTime_CI[] = $ArrivalTime[$k];	
				$TransporterI_CI[] = $TransporterI[$k];
			//}
		}
				
		//################ UPDATE LAST MIN MAX TEMP
		//echo "<br>TEMP_MAIN=".$temperature." ,datetime=".$datetime." ,LastMinTemp=".$LastMinTemp[$v];
		//echo "<br>MinTemp1=".$min_temp1." ,max_temp1=".$max_temp1;
		
		//####### UPDATE CONDITIONS
		//### 1. IF OUT && NOT IN
		//### 2. IF NO OUT && NO IN && EXIST IN FILE
		//### 3. IF IN && NO OUT - MAKE IT BLANK
		
		if( ($halt_out_flag1 && !$halt_in_flag1) || ((!$halt_in_flag1 && !$halt_out_flag1) && ($LastMinTemp[$v]!="")) )
		{
			echo "<br>IN TEMPERATURE FILE UPDATE::NO HALT IN AND OUT";
			update_last_temperature($min_temp1, $min_datetime1, $max_temp1, $max_datetime1, $v);
		}
		else if($halt_in_flag1 && !$halt_out_flag1)
		{
			echo "<br>IN TEMPERATURE FILE UPDATE::BLANK, HALT IN NO HALT OUT";
			$min_temp1="";
			$min_datetime1="";
			$max_temp1="";
			$max_datetime1="";
			update_last_temperature($min_temp1, $min_datetime1, $max_temp1, $max_datetime1, $v);
		}
		//#########################################
		
		if($j>$i)
		{
			$i=$j-1;
		}
		$v++;
		
	} //##### EXCEL VEHICLE LOOP CLOSED			
	
	
	//######### CALL SORT ROUTES FUNCTION
	sort_all_vehicle();
	//echo "<br>Size:RouteNo_CI=".sizeof($RouteNo_CI);

	//###### WRITE TO EXCEL : COMPLETED/INCOMPLETED CUSTOMERS
	global $sheet1_row;
	global $sheet2_row;
	//echo "<br>SizeRouteTotal:Final=".sizeof($RouteTotal);
	
	for($i=0;$i<sizeof($Vehicle_CI);$i++)
	{
		$vehicle_str=""; $remark_str=""; $customer_completed_str=""; $customer_incompleted_str="";

		$j=$i;
		while($Vehicle_CI[$j]==$Vehicle_CI[$i])
		{
			if($ArrivalTime_CI[$j]!="")
			{
				$customer_completed_str.=$StationNo_CI[$j].",";
			}
			else if($ArrivalTime_CI[$j]=="")
			{
				$customer_incompleted_str.= $StationNo_CI[$j].",";
			}		
			$j++;	//J LIMIT
		}	
		
		if($vehicle_str!="") {$vehicle_str = substr($Vehicle_CI[$i], 0, -1);}
		//if($remark_str!="") {$remark_str = substr($remark_str, 0, -1);}
		if($customer_completed_str!="") {$customer_completed_str = substr($customer_completed_str, 0, -1);}
		if($customer_incompleted_str!="") {$customer_incompleted_str = substr($customer_incompleted_str, 0, -1);}
		//######## FILL COMPLETED /INCOMPLETED SHEET
		$customer_completed_str = implode(',', array_unique(explode(',', $customer_completed_str)));
		$customer_incompleted_str = implode(',', array_unique(explode(',', $customer_incompleted_str)));
		
		$row1=2;
		$row2=2;			
		//######### FILL SHEET2
		if(($customer_completed_str!="") && ($customer_incompleted_str==""))
		{
			//echo "<br>ONE";
			$col_tmp = 'A'.$sheet1_row;
			$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , $Vehicle_CI[$i]);
			$col_tmp = 'B'.$sheet1_row;
			$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , $customer_completed_str);
			$col_tmp = 'C'.$sheet1_row;
			$objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , $TransporterI_CI[$i]);			
			$sheet1_row++;
		}
		//########## FILL SHEET3
		if($customer_incompleted_str!="")
		{
			//echo "<br>TWO";
			$col_tmp = 'A'.$sheet2_row;
			$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $Vehicle_CI[$i]);
			$col_tmp = 'B'.$sheet2_row;
			$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $customer_completed_str);
			$col_tmp = 'C'.$sheet2_row;
			$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $customer_incompleted_str);					
			$col_tmp = 'D'.$sheet2_row;
			$objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $TransporterI_CI[$i]);	
			$sheet2_row++;
		}
		
		if($j>$i)
		{
			$i=$j-1;
		}		
	}
	//####### WRITE TO EXCEL :COMPLETED/INCOMPLETED CLOSED ##############
	$customer_str="";
	//echo "\nSizeRedRoute=".sizeof($RedRoute);	
	//echo date('H:i:s') , " Write to Excel2007 format" , EOL;
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
	$objWriter->save($read_excel_path);
	echo date('H:i:s') , " File written to " , $read_excel_path , EOL;
	
	//####### SAVE TEMPERATURE WRITER
	$objWriter2 = PHPExcel_IOFactory::createWriter($objPHPExcel_2, 'Excel2007');
	$objWriter2->save($min_max_temperature_path);
	echo date('H:i:s') , " File written to " , $min_max_temperature_path , EOL;	
	
	echo "\nHALT CLCLOSED";
}	

//######## UPDATE VEHICLE
function update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle, $k, $StationNo, $Lat, $Lng, $ScheduleTime, $DistVar, $Remark, $startdate,$enddate, $lat_ref1, $lng_ref1, $lat_cr, $lng_cr, $arrivale_time,$depature_time, $hrs_min, $Type, $status_entered,$temperature)
{										
	//echo "\nUPDATE VEHICLE::".$read_excel_path.", ".$Vehicle.", ".$k.", ".$StationNo.", ".$Lat.", ".$Lng.", ".$ScheduleTime.", ".$DistVar.", ".$Remark.", ".$startdate.", ".$enddate.", ".$status_entered;	
	global $unchanged;
	global $ArrivalTime;
	global $DepartureTime;
	
	global $transporter_m;
	global $vehicle_m;

	global $halt_out_flag1;
	global $halt_in_flag1;

	global $min_temp1;
	global $min_datetime1;
	global $max_temp1;
	global $max_datetime1;	
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
		
		//$distance_station1 = calculate_distance($lat_ref1, $lat_g, $lng_ref1, $lng_g);
		//$distance_station2 = calculate_distance($lat_cr, $lat_g, $lng_cr, $lng_g);		
				
		if($distance_station1<$distance_station2)
		{
			$distance_station=$distance_station1;
		}
		else
		{
			$distance_station=$distance_station2;
		}	
		//echo "\ndistance_station=".$distance_station.", distance_variable=".$DistVar;
		if($distance_station < $DistVar)
		{			
			$station_no = $StationNo;																								
			//$customer_visited[] = $station_no;
			//$customer_type[] = $Type[$i];
			$entered_station = 1;
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
		//$temperature,$datetime,$min_temp1,$min_datetime1,$max_temp1,$max_datetime1
		$min_date = explode(' ',$min_datetime1);
		$max_date = explode(' ',$max_datetime1);
		
		$col_tmp = 'F'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $arrival_time1[0]);

		$col_tmp = 'G'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $arrival_time1[1]);
		
		//####### TEMPERATURE UPDATE
		$col_tmp = 'K'.$row;			//IN TEMP
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temperature);
		//$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);

		if($min_temp1!="")
		{
			$col_tmp = 'M'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $min_temp1);
			//$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);

			$col_tmp = 'N'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $min_date[0]);
			//$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);

			$col_tmp = 'O'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $min_date[1]);
			//$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
		}
		
		if($max_temp1!="")
		{
			$col_tmp = 'P'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $max_temp1);
			//$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);

			$col_tmp = 'Q'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $max_date[0]);
			//$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
			
			$col_tmp = 'R'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $max_date[1]);
		}
		//$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);		
		//#########################
		//$col_tmp = 'L'.$row;
		//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $time_delay);		

		$col_tmp = 'T'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[0]);

		$col_tmp = 'U'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[1]);

		$col_tmp = 'V'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time2[0]);

		$col_tmp = 'W'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time2[1]);
			
		$ArrivalTime[$k] = $arrival_time1[1];				
		$unchanged = false;			

		//###### TEMPERATURE FLAG
		$halt_in_flag1 = true;
		$halt_out_flag1 = false;		
		//###############																						
	}
	if(($status_entered==2)	&& ($entered_station==0))//####### CHECK FOR DEPARTURE
	{
		//$temperature,$datetime,$min_temp1,$min_datetime1,$max_temp1,$max_datetime1
		//$min_date = explode(' ',$min_datetime1);
		//$max_date = explode(' ',$max_datetime1);
		
		//echo "\nDepartureWrite";
		//##UPDATE DEPARTURE
		$col_tmp = 'H'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $depature_time1[0]);

		$col_tmp = 'I'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $depature_time1[1]);
		
		$col_tmp = 'J'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $hrs_min);			

		//####### TEMPERATURE UPDATE
		$col_tmp = 'L'.$row;			//## ONLY UPDATE OUT TEMP IN CASE OF DEPARTURE
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $temperature);
		//$objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);	
		//#########################
		
		$col_tmp = 'T'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[0]);

		$col_tmp = 'U'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[1]);

		$col_tmp = 'V'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time2[0]);

		$col_tmp = 'W'.$row;
		$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time2[1]);

		$DepartureTime[$k] = $depature_time1[1];
		
		$unchanged = false;
		
		//### RESET TEMPERATURE FLAG
		$halt_out_flag1 = true;
		$halt_in_flag1 = false;
		$min_temp1 = $temperature; 
		$min_datetime1 = $datetime;
		$max_temp1 = $temperature;
		$max_datetime1 = $datetime;
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

function update_nogps($objPHPExcel_1, $msg, $i)
{										
	//echo "\nInUpdateRemark";
	$row = $i+2;
	$col_tmp = 'AD'.$row;

	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $msg);	
}

function update_remark($objPHPExcel_1, $msg, $i)
{										
	//echo "\nInUpdateRemark";
	$row = $i+2;
	$col_tmp = 'S'.$row;

	$halt_in_cell = 'G'.$row;
	$halt_in = $objPHPExcel_1->getActiveSheet(0)->getCell($halt_in_cell)->getValue();

	if($halt_in!="")
	{
		$msg = "";
	}

	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $msg);	
}

function update_last_temperature($LastMinTemp, $LastMinDateTime, $LastMaxTemp, $LastMaxDateTime, $i)
{											
	global $objPHPExcel_2;
	//echo "<br>LastMinTemp=".$LastMinTemp." ,LastMinDateTime=".$LastMinDateTime;
	
	$last_min_date_str = explode(' ',$LastMinDateTime);
	$last_max_date_str = explode(' ',$LastMaxDateTime);
	
	$row = $i+2;
	$col_tmp = 'B'.$row;
	$objPHPExcel_2->setActiveSheetIndex(0)->setCellValue($col_tmp , $LastMinTemp);

	$col_tmp = 'C'.$row;
	$objPHPExcel_2->setActiveSheetIndex(0)->setCellValue($col_tmp , $last_min_date_str[0]);	

	$col_tmp = 'D'.$row;
	$objPHPExcel_2->setActiveSheetIndex(0)->setCellValue($col_tmp , $last_min_date_str[1]);	

	$col_tmp = 'E'.$row;
	$objPHPExcel_2->setActiveSheetIndex(0)->setCellValue($col_tmp , $LastMaxTemp);	

	$col_tmp = 'F'.$row;
	$objPHPExcel_2->setActiveSheetIndex(0)->setCellValue($col_tmp , $last_max_date_str[0]);	

	$col_tmp = 'G'.$row;
	$objPHPExcel_2->setActiveSheetIndex(0)->setCellValue($col_tmp , $last_max_date_str[1]);	
}

//######### SORT WITH RESPECT TO CUSTOMERS ###########################
function sort_all_vehicle()
{
	global $Vehicle_CI;
	global $StationNo_CI;
	global $ArrivalTime_CI;
	global $TransporterI_CI;
	
	/*for($x = 0; $x < sizeof($RouteNo_CI); $x++) 
	{
		echo "<br>Vehicle:BEFORE_SORT=".$Vehicle_CI[$x];
	}*/
	for($x = 1; $x < sizeof($Vehicle_CI); $x++) 
	{
		$tmp_vehicle_ci = $Vehicle_CI[$x];
		$tmp_station_ci = trim($StationNo_CI[$x]);
		$tmp_arrival_ci = $ArrivalTime_CI[$x];
		$tmp_transporter_ci = $TransporterI_CI[$x];			
		///////////      				

		$z = $x - 1;
		$done = false;
		while($done == false)
		{
			$vehicle_tmp1 = $Vehicle_CI[$z];			
			//echo "<br>RouteTmp1=".$route_tmp1." ,tmp_route_ci=".$tmp_route_ci;
			if (trim($vehicle_tmp1) > trim($tmp_vehicle_ci))
			{
				$Vehicle_CI[$z + 1] = $Vehicle_CI[$z];
				$StationNo_CI[$z + 1] = trim($StationNo_CI[$z]);				
				$ArrivalTime_CI[$z + 1] = $ArrivalTime_CI[$z];
				$TransporterI_CI[$z + 1] = $TransporterI_CI[$z];				
				//////////////////
				$z = $z - 1;
				if ($z < 0)
				{
					$done = true;
				}
			}
			else
			{
				$done = true;
			}
		} //WHILE CLOSED

		$Vehicle_CI[$z + 1] = $tmp_vehicle_ci;
		$StationNo_CI[$z + 1] = $tmp_station_ci;		
		$ArrivalTime_CI[$z + 1] = $tmp_arrival_ci;
		$TransporterI_CI[$z + 1] = $tmp_transporter_ci;			
	}
}	

?>
