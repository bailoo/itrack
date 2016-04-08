<?php
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
include_once("android_common_xml_element.php");
set_time_limit(1000);
include_once("android_calculate_distance.php");
include_once("android_check_with_range.php");
include_once("android_get_all_dates_between.php");
include_once("android_new_xml_string_io.php");
include_once("android_sort_xml.php");
include_once("util_android_hr_min_sec.php");
include_once("android_new_xml_string_io.php");


$DEBUG = 0;
$v_size=count($vehicle_serial);
if($DEBUG) echo "vsize=".$v_size;

$device_str= $_POST["vehicleSerial"];
//$device_str="862170018369908:";
$device_str=substr($device_str,0,-1);
$vserial = explode(':',$device_str);
//echo $vserial[0];
//$vehicleid_size=sizeof($vehicleid);

$date1 = $_POST["startDate"];
$date2 =  $_POST["endDate"];

/*$date1 ="2013/11/01";
$date2 =  "2013/11/04";*/
//echo "date1=".$date1."date2=".$date2."<br>";

$date1 = str_replace('/', '-', $date1);  
$date2 = str_replace('/', '-', $date2); 

$date_1 = explode(" ",$date1);
$date_2 = explode(" ",$date2);

//$datefrom = $date_1[0];
//$dateto = $date_2[0];

$datefrom = $date1;
$dateto = $date2;

//echo "<br>datefrom=".$datefrom." dateto=".$dateto;
$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);

//date_default_timezone_set("Asia/Calcutta");
$current_date = date("Y-m-d");
//print "<br>CurrentDate=".$current_date;
//$date_size = sizeof($userdates);
//echo "<br>datesize=".$date_size."<br> v_size=".$v_size;
$k1 = 0;
$m_start=0;

$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);

//$threshold = $_POST['threshold'];
//$threshold = '15';
$threshold = $threshold * 60;
//echo "threshold:".$threshold;
global $travel_report_data;
$travel_report_data=array();

	for($i=0;$i<sizeof($vserial);$i++)
	{ 
		//echo "vseril=".$vserial[$i]."<br>";
     	$Query="SELECT vehicle.vehicle_name,vehicle.vehicle_type,vehicle.vehicle_number FROM vehicle USE INDEX(v_vehicleid_status)".
		",vehicle_assignment USE INDEX(va_vehicleid_status,va_vehicleid_imei_status) WHERE vehicle.vehicle_id=vehicle_assignment.".
		"vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_assignment.device_imei_no='$vserial[$i]'";
	$Result=mysql_query($Query,$DbConnection);
	$Row=mysql_fetch_row($Result);
     get_travel_xml_data($vserial[$i], $Row[0], $date1,$date2,$threshold);
    //echo   "t2".' '.$i;
	}  
 

function get_travel_xml_data($vehicle_serial, $vname, $startdate,$enddate,$datetime_threshold)
{
	//echo "in function<br>";
	global $travel_report_data;
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	$fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$linetowrite="";
	//$date_1 = explode(" ",$startdate);
	//$date_2 = explode(" ",$enddate);

	//$date_1 = explode(" ",$startdate);
	//$date_2 = explode(" ",$enddate);	

	//$datefrom = $date_1[0];
	//$dateto = $date_2[0];

	$datefrom = $startdate;
	$dateto = $enddate;		
	$startdate = $startdate." 00:00:00";
	$enddate = $enddate." 23:59:59";
	//$timefrom = $date_1[1];
	//$timeto = $date_2[1];
	//echo "dateto=".$dateto."dateFrom=".$datefrom."<br>";
	get_All_Dates($datefrom, $dateto, &$userdates);
	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);
	

	for($i=0;$i<=($date_size-1);$i++)
	{
		$xml_current = "../../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";   
//echo "userdates=".$userdates[$i]."<br>";
		if (file_exists($xml_current))      
		{		    		
				//echo "in else";
				$xml_file = $xml_current;
				$CurrentFile = 1;
			}		
			else
			{
				$xml_file = "../../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
				$CurrentFile = 0;
			}		
			//echo "<br>xml in get_halt_xml_data =".$xml_file;	    	
			if(file_exists($xml_file)) 
			{			
				//echo "<br>file_exists1";     
				$t=time();
				$xml_original_tmp = "../../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
				//$xml_log = "../../../xml_tmp/filtered_xml/tmp_".$current_datetime1.".xml";
				//echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
									      
				if($CurrentFile == 0)
				{
					//echo "<br>ONE<br>";
					copy($xml_file,$xml_original_tmp);
				}
				else
				{
					//echo "<br>TWO<br>";
					$xml_unsorted = "../../../../xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
					//echo  "<br>".$xml_file." <br>".$xml_unsorted."<br><br>";

					copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
					SortFile($xml_unsorted, $xml_original_tmp,$userdates[$i]);    // SORT FILE
					unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
				}
      
				$total_lines = count(file($xml_original_tmp));
				//echo "<br>Total lines orig=".$total_lines;      
				$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
				//$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
				$logcnt=0;
				$DataComplete=false;                  
				$vehicleserial_tmp=null;
				$f =0;      
				if (file_exists($xml_original_tmp)) 
				{  
					set_master_variable($userdates[$i]);
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
						//echo fgets($file). "<br />";
						$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
						if(strlen($line)>20)
						{
							$linetmp =  $line;
						}  				
						$linetolog =  $logcnt." ".$line;
						$logcnt++;
						//echo "vc=".$vc."<br>";
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
						if((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)))
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
							$datetime = get_xml_data('/'.$vh.'="[^"]+"/', $line);
							$xml_date = $datetime;
						}				
						//echo "Final0=".$xml_date." datavalid=".$DataValid." datetime=".$datetime."<br>";          
						if($xml_date!=null)
						{				  					
							if(($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1))
							{							           	                            
								//$vserial = get_xml_data('/vehicleserial="[^"]+"/', $line);
								$vserial = $vehicle_serial;								
								$lat = get_xml_data('/'.$vd.'="\d+\.\d+[NS]\"/', $line);
								$lng = get_xml_data('/'.$ve.'="\d+\.\d+[EW]\"/', $line);
								//$datetime = get_xml_data('/datetime="[^"]+"/', $line);             
                
								// HALT LOGIC   /////////////                             
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
									$last_time1 = $datetime;
									$latlast = $lat;
									$lnglast =  $lng;
                 	                             	
								}           	              	
								else
								{           
									$lat_E = $lat;
									$lng_E = $lng; 
									$datetime_E = $datetime;                                  									
									//$date_secs2 = strtotime($datetime_cr);                		
									//echo "<br>str=".$lat_ref.", ".$lat_cr.", ".$lng_ref." ,".$lng_cr.", ". $datetime_cr;              		
									//$distance_incriment = calculate_distance($lat_S, $lat_E, $lng_S, $lng_E);
									calculate_distance($lat_S, $lat_E, $lng_S, $lng_E, &$distance_incriment);
									//echo "<br>distance:".$distance;                	
									//echo "<br>next -time_start:".$time_start." ,time_start_sec:".$time_start_sec;            		
									$tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;                
									calculate_distance($latlast, $lat_E, $lnglast, $lng_E, &$distance1);
									if($tmp_time_diff1>0)
									{
										$tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
										$last_time1 = $datetime;
										$latlast = $lat_E;
										$lnglast =  $lng_E;
									}
									$tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time) )) / 3600;                                                
									/*echo "<br>tmp_time_diff1=".$tmp_time_diff1." ,tmp_speed=".$tmp_speed;                
									echo "<br>lat1=".$lat1." ,lat2=".$lng1;
									echo "<br>lng1=".$lat2." ,lng2=".$lng2; 
									echo "<br>tmp_time_diff=".$tmp_time_diff." , last_time=".$last_time; 
									echo "<br>daily_dist=".$daily_dist."<br>";  */             
                             
									if($tmp_speed<500.0 && $distance_incriment>0.1 && $tmp_time_diff>0.0)
									{														
										//if($distance_incriment > $distance_error)
										//{          					                      
										//echo "<br>dist";
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
										//$distance_incrimenttotal += $distance_incriment;
										// echo $datetime_E . " -- " . $lat_E .",". $lng_E . "\tDelta Distance = " . $distance_incriment . "\tTotal Distance = " . $distance_total . "\n";
									}
									/*else
									{
										if($start_point_display == 0)
										{
											//echo "<br>startpoint";
											//$haltFlag==True;
											//$distance_travel=0;                                    

											//$lat_S = $lat;
											//$lng_S = $lng;
											$datetime_S = $datetime;
											$datetime_travel_start = $datetime_S;

											//$lat_travel_start = $lat_S;
											//$lng_travel_start = $lng_S;                    
										}          				
									}*/          				      			
									// echo "Delta Distance = " . $distance_incriment;
									$datetime_diff = strtotime($datetime_E) - strtotime($datetime_S);          
									// echo "Total Distance = " . $distance_total . "\n";				
									//if(($distance_incrimenttotal<$distance_threshold) && ($datetime_diff > $datetime_threshold) && ($haltFlag==False))
									//echo "distance_total=".$distance_total." distance_error=".$distance_error." datetime_diff=".$datetime_diff." datetime_threshold=".$datetime_threshold." haltFlag=".$haltFlag."<br>";
									if(($distance_total>$distance_error) && ($datetime_diff > $datetime_threshold) && ($haltFlag==False))
									{
										//newHalt($datetime_S, $datetime_E);
										$datetime_travel_end = $datetime_E;
										$lat_travel_end = $lat_S;
										$lng_travel_end = $lng_S;
										newTravel($vserial, $vname, $datetime_travel_start, $datetime_travel_end, $distance_travel, $lat_travel_start, $lng_travel_start, $lat_travel_end, $lng_travel_end, $distance_travel);
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
							} // $xml_date_current >= $startdate closed
						}   // if xml_date!null closed
						$f++;
					}   // while closed
					
					if($haltFlag==false)
					{
						//newHalt($datetime_S, $datetime_E);
						$datetime_travel_end = $datetime_E;
						$lat_travel_end = $lat_S;
						$lng_travel_end = $lng_S;
						newTravel($vserial, $vname, $datetime_travel_start, $datetime_travel_end, $distance_travel, $lat_travel_start, $lng_travel_start, $lat_travel_end, $lng_travel_end, $distance_travel);
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
				} // if original_tmp closed       
				fclose($xml);            
				unlink($xml_original_tmp);
			} // if (file_exists closed
		}  // for closed 	
		//echo "Test1";
		fclose($fh);
		//fclose($xmllog);
	} 
	
	function newTravel($vserial, $vname, $datetime_S, $datetime_E, $distance, $lat_travel_start, $lng_travel_start, $lat_travel_end, $lng_travel_end, $distance_travel)
	{
		//echo "in function<br>";
		global $travel_report_data;
		$travel_dur =  strtotime($datetime_E) - strtotime($datetime_S);                                                    
		$hms = secondsToTime($travel_dur);
		$travel_time = $hms[h].":".$hms[m].":".$hms[s];
		$distance_travel = round($distance_travel,2);
		$travel_report_data[]=array("deviceImeiNo"=>$vserial,"vehicleName"=>$vname,"dateFrom"=>$datetime_S,"dateTo"=>$datetime_E,"latStart"=>$lat_travel_start,"lngStart"=>$lng_travel_start,"latEnd"=>$lat_travel_end,"lngEnd"=>$lng_travel_end,"distance_travelled"=>$distance_travel,"travelTime"=>$travel_time);  
		//print_r($travel_report_data);
	} 
  	echo json_encode($travel_report_data); 
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
			
