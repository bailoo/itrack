<?php
$sheet1_row = 2;
$sheet2_row = 2;
//$userdates = array();
function get_halt_xml_data($startdate, $enddate, $read_excel_path)
{
	global $current_date1;
	global $current_date2;

	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	
	global $village_violate_msg;
	global $sub_village_violate_msg;
	global $villag_flag;
	global $violate_flag;	//#### GLOBAL VIOLATE FLAG
	
	echo "\nSD=".$startdate." ,ED=".$enddate;
	global $VehicleName;
	global $SNo;
	global $VehicleID;
	global $BaseStation;	
	global $BSCoordinate;	
	global $BSExpectedDeptTime;
	global $BSExpectedArrTime;	
	global $VillageName;	
	global $VLCoordinate;	
	global $VLExpectedMinHaltDuration;	
	global $VLExpectedMaxHaltDuration;
	global $ActualBSDeptTime;	
	global $ActualBSArrTime;	
	global $DelayBSDept;	
	global $DelayBSArr;
	global $ActualVLArrTime;
	global $ActualVLDeptTime;	
	global $DelayVLArr;	
	global $DelayVLDept;	
	global $VLHaltDuration;
	global $VLHaltViolation;	
	global $TotalDistanceTravelled;	
	global $IMEI;	
	global $Remark;
	global $objPHPExcel_1;
	global $SubVehicles;
	
	
	global $last_vehicle_name;
	global $last_halt_time;
	$total_dist = 0.0;
	$current_halt_time =0;
	$objPHPExcel_1 = null;	
	//$objPHPExcel_1 = new PHPExcel();
	$objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);	
	
	//echo "\nvehicle_serial=".$vehicle_serial.", vid=".$vid.", vname=".$vname.", startdate=".$startdate.", enddate=".$enddate.", interval=".$interval.", report_shift=".$report_shift;
	//############ GET GLOBAL VARIABLES	
		
	//echo "\nSD=".$startdate." ,ED=".$enddate." ,read_excel_path=".$read_excel_path." ,VehicleSize=".sizeof($Vehicle);
	echo "\nSizeVehicle=".sizeof($VehicleName);	
	for($i=0;$i<sizeof($VehicleName);$i++)
	{		
		$violate_flag = true;
		$villag_flag = false;
		$halt_dur = 0;
		$local_violate_flag = false;
		
		$total_dist = 0.0;
		$nodata = true;
		$nogps = true;		
		$row = $i+2;
		//###### GET LAST HALT TIME
		$vehicle_serial = $IMEI[$i];
		//echo "\nVehicle=".$i.",".$VehicleName[$i]." ,vehicle_serial=".$vehicle_serial;
		$interval = 60;
		for($h=0;$h<sizeof($last_vehicle_name);$h++)
		{
			if($VehicleName[$i] == $last_vehicle_name[$h])
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
		while($VehicleName[$j]==$VehicleName[$i])
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
		//$abspath = "D:\\test_app";
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
//		$abspath = "/var/www/html/vts/beta/src/php";
//		include_once($abspath."/get_location_lp_track_report.php");
		
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
		//global $userdates;
		//$userdates = array();		
		get_All_Dates($datefrom, $dateto, &$userdates);
		//get_All_Dates($datefrom, $dateto);
		//date_default_timezone_set("Asia/Calcutta");
		$current_datetime = date("Y-m-d H:i:s");
		$current_date = date("Y-m-d");

		$date_size = sizeof($userdates);
		$substr_count =0;
		
		$back_dir = "/var/www/html/itrack_vts";
		//$back_dir = "D:\\itrack_vts";
		//$back_dir_current = "/mnt/volume3";
		//$back_dir_sorted = "/mnt/volume4";
		$AddEntryinrReport = false;
		//$f=0;
		//echo "\nTEST4";
		
		//echo "\nDateSize=".$date_size;
		include("/var/www/html/vts/beta/src/php/common_xml_path.php");
		//include("D:\\test_app/common_xml_path.php");
		
		for($d=0;$d<=($date_size-1);$d++)
		{	
			$xml_current = $xml_data."/".$userdates[$d]."/".$vehicle_serial.".xml";
			//echo "<br>xml_current=".$xml_current;	
			if (file_exists($xml_current))      
			{  
				//echo "<br>EXISTSSSSS";
				$xml_file = $xml_current;
				$CurrentFile = 1;
			}		
			else
			{
				//echo "<br>DOES NOT EXISTSSSSS";
				$xml_file = $sorted_xml_data."/".$userdates[$d]."/".$vehicle_serial.".xml";
				$CurrentFile = 0;
			}		
			//echo "<br>xml_file=".$xml_file;	
			
			if (file_exists($xml_file)) 
			{			
				$t=time();
				//echo "t=".$t."<br>";
				$xml_original_tmp = $back_dir."/xml_tmp/original_xml/tmp".$vehicle_serial."_".$t."_".$d.".xml";
				copy($xml_file,$xml_original_tmp);

				//$total_lines = count(file($xml_original_tmp));		      
				//echo "\nTotalLines=".$total_lines;
				$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
				
				if (file_exists($xml_original_tmp)) 
				{
					//echo "<br>FileExistOriginal";
					//$vserial_sel[] = array(); 					
					$sts_date_sel = array();
					$xml_date_sel = array();				
					$lat_sel = array(); 					
					$lng_sel = array(); 					
					$speed_sel = array();
					//echo "<br>Original File Exist###########";
					
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
						/*if(strlen($line)>20)
						{
							$linetmp =  $line;
						}  				
						$linetolog =  $logcnt." ".$line;
						$logcnt++;*/
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
							//echo "<br>ValidData";
							//echo "<br>VG=".$vg;
							//echo "<textarea>".$line."</textarea>";							
							$status = preg_match('/'.$vg.'="[^"]+/', $line, $sts_tmp);
							$sts_tmp1 = explode("=",$sts_tmp[0]);
							$sts = preg_replace('/"/', '', $sts_tmp1[1]);	
							$sts_date = $sts;	
							//echo "<br>STS_DATE1=".$sts_date;
							
							$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
							$datetime_tmp1 = explode("=",$datetime_tmp[0]);
							$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
							$xml_date = $datetime;
						}       

						 //echo "\nXML_DATE=".$xml_date." ,DataValid=".$DataValid." ,vehicle_serial=".$vehicle_serial;   
						//echo "<br>STS_DATE=".$sts_date;
						if($sts_date!=null)
						{								
							//echo "<br>IN_STS";
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

								//$vserial_sel[] = $vehicle_serial; 
								$sts_date_sel[] = $sts_date;
								$xml_date_sel[] = $xml_date;
								$lat_tmp1 = explode("=",$lat_tmp[0]);							
								$lat_sel[] = preg_replace('/"/', '', $lat_tmp1[1]);
								$lng_tmp1 = explode("=",$lng_tmp[0]);
								$lng_sel[] = preg_replace('/"/', '', $lng_tmp1[1]);
								$speed_tmp1 = explode("=",$speed_tmp[0]);
								$speed_sel[] = preg_replace('/"/', '', $speed_tmp1[1]);
							}
						}															
					}
					fclose($xml);
				}
			}

			//###### SORT THE ARRAYS
			//echo "<br>SizeXmlDateSel=".sizeof($xml_date_sel);
			for($x = 1; $x < sizeof($xml_date_sel); $x++) 
			{
				$value = $xml_date_sel[$x];

				$tmp_datetime = $xml_date_sel[$x];
				$tmp_sts = $sts_date_sel[$x];					
				$tmp_lat = $lat_sel[$x];
				$tmp_lng = $lng_sel[$x];
				$tmp_speed = $speed_sel[$x];
									
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
			}			
			//###### SORTING CLOSED						
			/*
			if($sts_date!=null)
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

			$lat_tmp1 = explode("=",$lat_tmp[0]);							
			$lat = preg_replace('/"/', '', $lat_tmp1[1]);
			$lng_tmp1 = explode("=",$lng_tmp[0]);
			$lng = preg_replace('/"/', '', $lng_tmp1[1]);
			$vserial = $vehicle_serial; 
			*/

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
					
					//echo "<br>DateTime=".$datetime." ,Enddate=".$enddate;
					if(($datetime>$current_date1) && ($datetime<$current_date2))
					{							
						if($firstdata_flag==0)
						{							
							//############### IF DISTANCE SECTION ###############
							$lat1_dist = $lat_sel[$y];
							$lng1_dist = $lng_sel[$y];

							//echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;															
							$time1_dist = $datetime;					
							//$date_secs1_dist = strtotime($time1);					
							//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
							//$date_secs1 = (double)($date_secs1 + $interval); 
							//$date_secs2 = 0;  
							$last_time1_dist = $datetime;
							$latlast_dist = $lat_sel[$y];
							$lnglast_dist = $lng_sel[$y];										
							//############### IF DISTANCE SECTION CLOSED ########
							
							//############### IF HALT SECTION ###################
							$halt_flag = 0;
							$firstdata_flag = 1;

							//$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
							//$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);																																
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
							//////######## HALT SECTION IF CLOSED ##############										
							//$date_secs1 = strtotime($datetime_ref);							
							//$date_secs1 = (double)($date_secs1 + $interval);      	
						}                 	
						else
						{    								
							//################ ELSE DISTANCE SECTION ###########
							$time2_dist = $datetime;
							
							//$date_secs2 = strtotime($time2);	
							$vserial=$vehicle_serial;													                                      													      					
							$lat2_dist = $lat_sel[$y];      				        					
							$lng2_dist = $lng_sel[$y];  
							calculate_distance($lat1_dist, $lat2_dist, $lng1_dist, $lng2_dist, &$distance);
							//$distance = calculate_distance($lat1_dist, $lat2_dist, $lng1_dist, $lng2_dist);
							//echo "<br>DISTANCE=".$distance." ,DATETIME=".$datetime." ,last_time1=".$last_time1;
							$tmp_time_diff1_dist = (double)(strtotime($datetime) - strtotime($last_time1_dist)) / 3600;
							calculate_distance($latlast_dist, $lat2_dist, $lnglast_dist, $lng2_dist, &$distance1);
							//$distance1 = calculate_distance($latlast_dist, $lat2_dist, $lnglast_dist, $lng2_dist);
							if($tmp_time_diff1_dist>0)
							{
								$tmp_speed_dist = ((double) ($distance1)) / $tmp_time_diff1_dist;
								$last_time1_dist = $datetime;
								$latlast_dist = $lat2_dist;
								$lnglast_dist =  $lng2_dist;
							}
							$tmp_time_diff_dist = ((double)( strtotime($datetime) - strtotime($last_time_dist) )) / 3600;
							if(($tmp_speed_dist<500.0) && ($distance>0.1) && ($tmp_time_diff_dist>0.0))
							{														
								$total_dist = (double)($total_dist + $distance);											
								$total_dist = round($total_dist,2);					
								//echo "\nTotal Dist=".$total_dist;
																			
								$lat1_dist = $lat2_dist;
								$lng1_dist = $lng2_dist;
								$last_time_dist = $datetime;											
							}
							//################ ELSE DISTANCE SECTION CLOSED ####

							//################ ELSE HALT SECTION ###############
							//echo "<br>Next";               
							/*$lat_tmp1 = explode("=",$lat_tmp[0]);                           //  GET NEXT RECO
							$lat_cr = preg_replace('/"/', '', $lat_tmp1[1]);																						
							$lng_tmp1 = explode("=",$lng_tmp[0]);
							$lng_cr = preg_replace('/"/', '', $lng_tmp1[1]);*/							
							
							$lat_cr = $lat_sel[$y];
							$lng_cr = $lng_sel[$y];
							$datetime_cr = $datetime;																		
							$date_secs2 = strtotime($datetime_cr);	
							$distance =0.0;
							calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);
							//$distance = calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr);
							//if(($distance > 0.0100) || ($f== $total_lines-2) )
							//echo "\nF=".$f." ,total_lines=".$total_lines;										
																								
							//###### FOR IRREGULAR DATA FILTER CODE
							$tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;

							$distance1 =0.0;
							calculate_distance($latlast, $lat_cr, $lnglast, $lng_cr, &$distance1);
							//$distance = calculate_distance($latlast, $lat_cr, $lnglast, $lng_cr);
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
							//if($datetime>"2013-10-26 10:21:50") echo "\nD1:".$datetime;											
							//echo "<br>HaltFlag=".$halt_flag." ,Distance=".$distance;
							if (($halt_flag == 1) && ($distance > 0.100))
							{								
								//echo "<br>HALT FOUND";
								//if($datetime>"2013-10-26 10:21:50") echo "\nD2:".$datetime;
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
								//if($datetime>"2013-10-26 10:21:50") echo "\nE1:".$datetime;
								//echo "<br>SizeJ=".$j;
								for($k=$i;$k<$j;$k++)	//DEPARTURE
								{
									//echo "\nArrivalTime[$k]=".$ArrivalTime[$k]." ,DepartureTime[$k]=".$DepartureTime[$k];
									//if( (($ActualBSArrTime[$k]!="") && ($ActualBSDeptTime[$k] == "")) || (($ActualVLArrTime[$k]!="") && ($ActualVLDeptTime[$k] == "")) )												
									if(($ActualBSDeptTime[$k] == "") && ($ActualVLArrTime[$k]=="")) //## BASE STATION
									{															
										//if($datetime>"2013-10-26 10:21:50") echo "\nE2:".$datetime;
										
										$date_tmp = explode(' ',$startdate);
										$arrtime_str = $date_tmp[0]." ".$ActualBSDeptTime[$k];
										$deptime = strtotime($depature_time);
										
										//$tmp_expected_dept = $date_tmp[0]." ".$BSExpectedDeptTime[$k].":00";													
										$tmp_expected_dept = $date_tmp[0]." 09:30:00";
										$expected_dept = strtotime($tmp_expected_dept);																									
										
										$delay = "";
										/*if($deptime < $expected_dept)
										{											
											$diff = $expected_dept - $deptime;
											$dept_violate = secondsToTime($diff);														
											$dept_violate1 = "-".$dept_violate[h].":".$dept_violate[m];														
										}*/
										/*if(trim($VehicleName[$k])=="Parsa Sumali-UP")
										{
											echo "<br>DEPT TIME=".$deptime;
											echo "<br>EXP TIME=".$expected_dept;
										}*/
										if($deptime > $expected_dept)
										{
											//echo "<br>DEPT VIOLATED";
											$diff = $deptime - $expected_dept;
											$dept_violate = secondsToTime($diff);														
											$dept_violate1 = $dept_violate[h].":".$dept_violate[m];														
										}																																																				
										$hrs_min = "-";
										$hms_violate1 = "-";
										//echo "\nBSDeptDist=".$total_dist;
										//echo "\nBS_DEPARTURE:".$datetime.",VehicleName=".$VehicleName[$k]." ,BSName=".$BaseStation[$k]." ,BSCoord=".$BSCoordinate[$k].",Dist=".$total_dist;
										update_vehicle_status($objPHPExcel_1, $read_excel_path, $VehicleName[$k],$k,$VillageName[$k],$VLCoordinate[$k],$BaseStation[$k],$BSCoordinate[$k],$VLExpectedMinHaltDuration[$k],$VLExpectedMaxHaltDuration[$k],$BSExpectedDeptTime[$k],$BSExpectedArrTime[$k],$startdate,$enddate,$lat_ref1, $lng_ref1, $lat_cr, $lng_cr,$arrivale_time,$depature_time,$hrs_min,$hms_violate1,$dept_violate1,$total_dist,4);
									}
									
									//if(($ActualBSDeptTime[$k]!="") && ($ActualVLArrTime[$k]!="") && ($ActualVLDeptTime[$k] == ""))	//###VILLAGE
									if(($ActualBSDeptTime[$k]!="")&&($ActualVLArrTime[$k]!="") && ($ActualVLDeptTime[$k] == ""))	//###VILLAGE
									{															
										/*if($datetime>"2013-10-26 10:21:50")
										{													
											echo "\nE31:".$datetime." ,ActualVLArrTime=".$ActualVLArrTime[$k];
											echo "\nE32:".$datetime." ,depature_time=".$depature_time;
											echo "\nE33:".$datetime." ,depature_time=".$arrtime_str;
											echo "\nE34:".$datetime." ,VLExpectedMinHaltDuration=".$VLExpectedMinHaltDuration[$k];
											echo "\nE35:".$datetime." ,VLExpectedMaxHaltDuration=".$VLExpectedMaxHaltDuration[$k];
										}*/
										
										$date_tmp = explode(' ',$startdate);
										$arrtime_str = $date_tmp[0]." ".$ActualVLArrTime[$k];
										$deptime = strtotime($depature_time);
										$arrtime = strtotime($arrtime_str);
										$halt_dur = ($deptime - $arrtime);		//THIS IS USED AT RUNTIME, COMMENT HERE LATER
										$hms_2 = secondsToTime($halt_dur);
										//if($datetime>"2013-10-26 10:21:50") echo "\nE36";
										//$hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
										$hrs_min = $hms_2[h].":".$hms_2[m];
										//echo "\nDepartureFound";
										//echo "\nARR=".$arrtime_str." ,DEP=".$depature_time." ,EXP_MIN=".$VLExpectedMinHaltDuration[$k]." ,EXP_MX=".$VLExpectedMaxHaltDuration[$k];
										$tmp_minhalt = explode(':',$VLExpectedMinHaltDuration[$k]);
										$tmp_maxhalt = explode(':',$VLExpectedMaxHaltDuration[$k]);
										$seconds_min_halt = $tmp_minhalt[0] * 3600 + $tmp_minhalt[1]*60 + 0;
										$seconds_max_halt = $tmp_maxhalt[0] * 3600 + $tmp_maxhalt[1]*60 + 0;													
										//if($datetime>"2013-10-26 10:21:50") echo "\nE37";
										$hms_violate = "";
										//echo "\nhalt_dur=".$halt_dur." ,seconds_min_halt=".$seconds_min_halt." ,seconds_max_halt=".$seconds_max_halt;
										if($halt_dur < $seconds_min_halt)
										{														
											$diff = $seconds_min_halt - $halt_dur;
											$hms_violate = secondsToTime($diff);														
											$hms_violate1 = "-".$hms_violate[h].":".$hms_violate[m];
											//echo "\nhms_violateA1=".$hms_violate1;
										}
										else if($halt_dur > $seconds_max_halt)
										{
											$diff = $halt_dur - $seconds_max_halt;
											$hms_violate = secondsToTime($diff);														
											$hms_violate1 = $hms_violate[h].":".$hms_violate[m];															
											//echo "\nhms_violateA2=".$hms_violate1;
										}																																																				
										$dept_violate1 = "-";
										//echo "\nVILLDist=".$total_dist;													
										//echo "\nVIL_DEPARTURE:".$datetime.",VehicleName=".$VehicleName[$k].",VLName=".$VillageName[$k]." ,VLCoord=".$VLCoordinate[$k].",Dist=".$total_dist;
										update_vehicle_status($objPHPExcel_1, $read_excel_path, $VehicleName[$k],$k,$VillageName[$k],$VLCoordinate[$k],$BaseStation[$k],$BSCoordinate[$k],$VLExpectedMinHaltDuration[$k],$VLExpectedMaxHaltDuration[$k],$BSExpectedDeptTime[$k],$BSExpectedArrTime[$k],$startdate,$enddate,$lat_ref1, $lng_ref1, $lat_cr, $lng_cr,$arrivale_time,$depature_time,$hrs_min,$hms_violate1,$dept_violate1,$total_dist,2);
									}
								}													
								
								$last_halt_time_excel = 0;
								$halt_flag = 0;
							}
							else if(($distance <= 0.100) && ($halt_flag == 0) && ( (strtotime($datetime_cr)-strtotime($datetime_ref))<($interval-$last_halt_time_excel)) )
							{
								//echo "<br>ONE";
								//if($datetime>"2013-10-26 10:21:50") echo "\nD3:".$datetime;
								$current_halt_time = $current_halt_time + (strtotime($datetime_cr)-strtotime($datetime_ref));
							}									
							else if(($distance <= 0.100) && ($halt_flag == 0) && ( (strtotime($datetime_cr)-strtotime($datetime_ref))>($interval-$last_halt_time_excel)) )    // IF VEHICLE STOPS FOR 2 MINS 
							{            													
								//echo "<br>TWO";
								//if($datetime>"2013-10-26 10:21:50") echo "\nD4:".$datetime;
								//echo "<br>SizeJ=>=".$j;
								for($k=$i;$k<$j;$k++)	//ARRIVAL
								{
									//if($ActualBSArrTime[$k]=="" || $ActualVLArrTime=="")
									if(($ActualBSDeptTime[$k]!="")&& ($ActualVLArrTime[$k]!=""))
									{																														
										$hrs_min = "-";
										$hms_violate1 ="-";
										$dept_violate = "-";
										
										//echo "\nBS_ARRIVAL:".$datetime.",VehicleName=".$VehicleName[$k].",VLName=".$VillageName[$k]." ,VLCoord=".$VLCoordinate[$k].",Dist=".$total_dist;
										update_vehicle_status($objPHPExcel_1, $read_excel_path, $VehicleName[$k],$k,$VillageName[$k],$VLCoordinate[$k],$BaseStation[$k],$BSCoordinate[$k],$VLExpectedMinHaltDuration[$k],$VLExpectedMaxHaltDuration[$k],$BSExpectedDeptTime[$k],$BSExpectedArrTime[$k],$startdate,$enddate,$lat_ref1, $lng_ref1, $lat_cr, $lng_cr,$arrivale_time,$depature_time,$hrs_min,$hms_violate1,$dept_violate,$total_dist,3);
										//update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle[$k],$k,$VillageName[$k],$Lat[$k],$Lng[$k],$ScheduleTime[$k],$DistVar[$k],$Remark[$k],$startdate,$enddate,$lat_ref1, $lng_ref1, $lat_cr, $lng_cr,$datetime_ref,$depature_time,$RouteNo[$k],$hrs_min,$Type[$k],1);
									}
									if(($ActualBSDeptTime[$k]!="")&& ($ActualVLArrTime[$k]==""))
									{																														
										$hrs_min = "-";
										$hms_violate1 ="-";
										$dept_violate = "-";
										
										//echo "\nVIL_ARRIVAL:".$datetime.",VehicleName=".$VehicleName[$k].",VLName=".$VillageName[$k]." ,VLCoord=".$VLCoordinate[$k].",Dist=".$total_dist;
										//echo "\nVIL_ARRIVAL";
										update_vehicle_status($objPHPExcel_1, $read_excel_path, $VehicleName[$k],$k,$VillageName[$k],$VLCoordinate[$k],$BaseStation[$k],$BSCoordinate[$k],$VLExpectedMinHaltDuration[$k],$VLExpectedMaxHaltDuration[$k],$BSExpectedDeptTime[$k],$BSExpectedArrTime[$k],$startdate,$enddate,$lat_ref1, $lng_ref1, $lat_cr, $lng_cr,$arrivale_time,$depature_time,$hrs_min,$hms_violate1,$dept_violate,$total_dist,1);
										//update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle[$k],$k,$VillageName[$k],$Lat[$k],$Lng[$k],$ScheduleTime[$k],$DistVar[$k],$Remark[$k],$startdate,$enddate,$lat_ref1, $lng_ref1, $lat_cr, $lng_cr,$datetime_ref,$depature_time,$RouteNo[$k],$hrs_min,$Type[$k],1);
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
								//echo "<br>THREE";
								//if($datetime>"2013-10-26 10:21:50") echo "\nD5:".$datetime;
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
								$datetime_ref = $datetime_cr;    //modified   				
								$halt_flag = 0;										
							}
							//############ ELSE HALT SECTION CLOSED ##############
						}  //else closed							
					
						/*if(($xml_date_sel[$y] >= $enddate) && ($halt_flag == 1) && ($AddEntryinrReport==false))
						{																  
							$AddEntryinrReport=true;
						}*/
					
						if(($AddEntryinrReport) && ($villag_flag))
						{
							//echo "<br>NOT violated";
							//if(($violate_flag) && ($halt_dur > 900))								
							if(($violate_flag) && ($halt_dur > 900))
							{									
								$local_violate_flag = true;
								$vehicle_name_violate = $VehicleName[$i];
							}
						}									
					} // if original_tmp closed 
					$f++;
				} // CLOSED- SORTED DATA FOR LOOP
		
				if($local_violate_flag)
				{
					if($SubVehicles[$vehicle_name_violate]!="")
					{ 
						$sub_village_violate_msg.='<TR> 
							<TD style="color:black;font-size:14px;font-weight:bold;" align="left">Vehicle:</TD>
							<TD style="color:blue;font-size:14px;" align="left">'.$vehicle_name_violate.'</TD>
							<TD style="color:red;font-size:14px;" align="left">Violated the Specified Route</TD>							
						</TR>';					
					}
					//echo "<br>Violated";
					//$village_violate_msg.= "<br><font color='purple' size='1'>*Vehicle:</font><font color='blue' size='1'>".$vehicle_name_violate."</font><font color='purple' size='1'> Violated the Specified Location</font>";
					$v_msg = 
					'<TR> 
						<TD style="color:black;font-size:14px;font-weight:bold;" align="left">Vehicle:</TD>
						<TD style="color:blue;font-size:14px;" align="left">'.$vehicle_name_violate.'</TD>
						<TD style="color:red;font-size:14px;" align="left">Violated the Specified Route</TD>							
					</TR>';
					$village_violate_msg.= $v_msg;
				}		
			} // if xml_date sel closed 
			//echo "vehicle_name=".$vname."csv_string_halt==".$csv_string_halt."<br>";			         
			//	unlink($xml_original_tmp);
		}  // CLOSED- DATE FOR LOOP
				
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
		
		/*
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
		*/
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
//function update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle, $k, $StationNo, $Lat, $Lng, $ScheduleTime, $DistVar, $Remark, $startdate,$enddate, $lat_ref1, $lng_ref1, $lat_cr, $lng_cr, $arrivale_time,$depature_time, $RouteNo, $hrs_min, $Type, $status_entered)
/*function update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle,$k,$VillageName,$VLCoordinate,$BaseStation,$BSCoordinate,$VLExpectedMinHaltDuration,$VLExpectedMaxHaltDuration,$BSExpectedDeptTime,$BSExpectedArrTime,$startdate,$enddate,$lat_ref1, $lng_ref1, $lat_cr, $lng_cr,$arrivale_time,$depature_time,$hrs_min,$hms_violate,$dept_violate,$total_dist,$status_entered)
{
}*/
function update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle,$k,$VillageName,$VLCoordinate,$BaseStation,$BSCoordinate,$VLExpectedMinHaltDuration,$VLExpectedMaxHaltDuration,$BSExpectedDeptTime,$BSExpectedArrTime,$startdate,$enddate,$lat_ref1, $lng_ref1, $lat_cr, $lng_cr,$arrivale_time,$depature_time,$hrs_min,$hms_violate,$dept_violate,$total_dist,$status_entered)
{										
	global $violate_flag;
	global $villag_flag;
	//echo "\nUPDATE VEHICLE::".$read_excel_path.", ".$Vehicle.", ".$k.", ".$StationNo.", ".$Lat.", ".$Lng.", ".$ScheduleTime.", ".$DistVar.", ".$Remark.", ".$startdate.", ".$enddate.", ".$status_entered;	
	$DEBUG =1;	
	//echo "\nStatusEntered=".$status_entered;
	
	global $unchanged;
	global $ActualBSDeptTime;
	global $ActualBSArrTime;
	global $TotalDistanceTravelled;
	global $DelayBSDept;
	//global $DelayBSArr;
	global $VLHaltDuration;

	global $ActualVLArrTime;
	global $ActualVLDeptTime;	
	//if($DEBUG) echo "\nA";
	$row = $k+2;	
	$report_time1 = explode(' ',$startdate);
	$report_time2 = explode(' ',$enddate);
	
	$last_halt_sec_global = 0;						
	$valid_halt = false;												
	//if($DEBUG) echo "\nB";
	if($time_delay=="")
	{
		$time_delay = "-";
	}	
	if($hrs_min=="")
	{
		$hrs_min = "-";
	}																									
	$arrival_time1 = explode(' ',$arrivale_time);
	$depature_time1 = explode(' ',$depature_time);		
	//if($DEBUG) echo "\nC";
	//######### CHECK FOR ARRIVAL AND DEPARTURE OF BASE STATION
	if($status_entered=="3" || $status_entered=="4")
	{
		$place = "-";												
		$station_no = "-";
		//$transporter_name = "-";
		$schedule_time = "-";
		$delay = "-";
		$entered_station = 0;
										
		$BScoord = explode(',',$BSCoordinate);
		$lat_g = trim($BScoord[0]);
		$lng_g = trim($BScoord[1]);										
	  
		if($DistVar == 0)
		{
			//$DistVar = 0.2;
			$DistVar = 10;
		}    
		
		$distance_station = 0;              
		if( ($lat_g!="") && ($lng_g!="") && ($BaseStation!="") )
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
			if($distance_station < $DistVar)
			{			
				$entered_station = 1;
			}
		}								

		//echo "\nArrivalTime[$i]=".$ArrivalTime[$i];
		
		if(($status_entered==3)	&& ($entered_station==1))//###### CHECK FOR ALL (ARRIVAL AND DEPARTURE) : BASE STATION
		{
			//echo "\nARRIVAL-BS:dist=".$total_dist;		
			$col_tmp = 'M'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $arrival_time1[1]);		

			$col_tmp = 'T'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $total_dist);		
						
			$col_tmp = 'V'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[0]);

			$col_tmp = 'W'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[1]);

			$col_tmp = 'X'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time2[1]);
				
			$ActualVLArrTime[$k] = $arrival_time1[1];
			$TotalDistanceTravelled[$k] = $total_dist;			
			$unchanged = false;				
			//###############																						
		}
		if(($status_entered==4)	&& ($entered_station==0))//####### CHECK FOR DEPARTURE : BASE STATION
		{
			//echo "\nDepartureBS:dist=".$total_dist;
			$col_tmp = 'L'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $depature_time1[1]);							

			$col_tmp = 'N'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $dept_violate);							

			$col_tmp = 'T'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $total_dist);		
			
			$col_tmp = 'V'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[0]);

			$col_tmp = 'W'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[1]);

			$col_tmp = 'X'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time2[1]);
			
			$ActualBSDeptTime[$k] = $depature_time1[1];
			$DelayBSDept[$k] = $dept_violate;		
			$TotalDistanceTravelled[$k] = $total_dist;
			$unchanged = false;
			//echo "\nIF ARRIVAL NOT NULL";												
		}
	}
	//######### BASE STATION ARRIVAL DEPARTURE CLOSED ##############	
	
	//#########################################################
	//######## CHECK ARRIVAL DEPARTURE VILLAGE ################
	if($status_entered=="1" || $status_entered=="2")
	{
		$villag_flag = true;
		$place = "-";												
		$station_no = "-";
		//$transporter_name = "-";
		$schedule_time = "-";
		$delay = "-";
		$entered_station = 0;
										
		$vilcoord = explode(',',$VLCoordinate);
		$lat_g = trim($vilcoord[0]);
		$lng_g = trim($vilcoord[1]);										
		//echo "\nLT=".$lat_g." ,lng=".$lng_g;
		if($DistVar == 0)
		{
			//$DistVar = 0.2;
			$DistVar = 5;
		}    
		
		$distance_station = 0;              
		if( ($lat_g!="") && ($lng_g!="") && ($VillageName!="") )
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
			//echo "\nDistStation=".$distance_station." ,DistVar=".$DistVar;
			if($distance_station < $DistVar)
			{			
				$entered_station = 1;
				$violate_flag = false;
				//echo "\nIN VILLAGE";
			}
		}								
		//if($DEBUG) echo "\nD";
		//##########################################	
		//########## UPDATE LAST HALT TIME 
		$last_halt_sec_global = 0;						

		$valid_halt = false;												
		
		if($time_delay=="")
		{
			$time_delay = "-";
		}	
		if($hrs_min=="")
		{
			$hrs_min = "-";
		}
					
		//######## FINAL UPDATION																						
		$arrival_time1 = explode(' ',$arrivale_time);
		$depature_time1 = explode(' ',$depature_time);	
		//echo "\nArrivalTime[$i]=".$ArrivalTime[$i];
		//if($DEBUG) echo "\nE";
		if(($status_entered==1)	&& ($entered_station==1))//###### CHECK FOR ALL (ARRIVAL AND DEPARTURE) : VILLAGE
		{
			//echo "\nARRIVAL VILLAGE:dist=".$total_dist;		
			$col_tmp = 'P'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $arrival_time1[1]);		

			$col_tmp = 'T'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $total_dist);		
			
			//$col_tmp = 'M'.$row;
			//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $hrs_min);			

			$col_tmp = 'V'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[0]);

			$col_tmp = 'W'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[1]);

			$col_tmp = 'X'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time2[1]);
				
			$ActualVLArrTime[$k] = $arrival_time1[1];
			$TotalDistanceTravelled[$k] = $total_dist;		
			$unchanged = false;				
			//###############																						
		}
		if(($status_entered==2)	&& ($entered_station==0))//####### CHECK FOR DEPARTURE : VILLAGE
		{
			//echo "\nDepartureVILLAGE:dist=".$total_dist;
			$col_tmp = 'Q'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $depature_time1[1]);

			$col_tmp = 'R'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $hrs_min);			

			$col_tmp = 'S'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $hms_violate);						

			$col_tmp = 'T'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $total_dist);		
			
			$col_tmp = 'V'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[0]);

			$col_tmp = 'W'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time1[1]);

			$col_tmp = 'X'.$row;
			$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $report_time2[1]);
			
			$ActualVLDeptTime[$k] = $depature_time1[1];
			$VLHaltDuration[$k] = $hrs_min;			
			$TotalDistanceTravelled[$k] = $total_dist;		
			$VLHaltViolation[$k] = $hms_violate;		
			$unchanged = false;
			//echo "\nIF ARRIVAL NOT NULL";												
		}
	}
	//if($DEBUG) echo "\nF";
	//######### VILLAGE ARRIVAL DEPARTURE CLOSED ##############
	                         						
}
////### UPDATE HALT CLOSED

/*function update_extra_sheets($objPHPExcel_1,$i,$Vehicle,$RouteNo,$customer_visited,$customer_unvisited)
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
}*/

function update_remark($objPHPExcel_1, $msg, $i)
{										
	//echo "\nInUpdateRemark";
	$row = $i+2;
	$col_tmp = 'Y'.$row;
	$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $msg);	
}

?>
