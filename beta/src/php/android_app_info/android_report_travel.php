<?php
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  

set_time_limit(1000);
include_once("android_calculate_distance.php");
include_once("android_check_with_range.php");
include_once("util_android_hr_min_sec.php");
require_once "lib/nusoap.php"; 

 $pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//print_r($pathInPieces);
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3];
//echo "pathToRoot=".$pathToRoot."<br>";
	//====cassamdra //////////////
   include_once($pathToRoot.'/beta/src/php/xmlParameters.php');
    include_once($pathToRoot.'/beta/src/php/parameterizeData.php'); /////// for seeing parameters
    include_once($pathToRoot.'/beta/src/php/data.php');   
    include_once($pathToRoot.'/beta/src/php/getXmlData.php');

function getTraveDeviceDataPrev($vehicleSerial,$startDate,$endDate)
{
global $DbConnection;
$device_str= $vehicleSerial;
//$device_str="862170018369908:";
$device_str=substr($device_str,0,-1);
$vserial = explode(':',$device_str);
//echo $vserial[0];
//$vehicleid_size=sizeof($vehicleid);

$date1 = $startDate;
$date2 =  $endDate;

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
     getTravelDeviceData($vserial[$i], $Row[0], $date1,$date2,$threshold);
    //echo   "t2".' '.$i;
	} 
        
        return json_encode($travel_report_data); 
 
}
function getTravelDeviceData($vehicle_serial, $vname, $startdate,$enddate,$datetime_threshold)
{
    $requiredData="All";
     $sortBy='h';
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
        $parameterizeData=new parameterizeData();
    $ioFoundFlag=0;
    global $o_cassandra;

    $parameterizeData->latitude="d";
    $parameterizeData->longitude="e";
    $parameterizeData->speed="f";
	//$date_1 = explode(" ",$startdate);
	//$date_2 = explode(" ",$enddate);

	//$date_1 = explode(" ",$startdate);
	//$date_2 = explode(" ",$enddate);	

	//$datefrom = $date_1[0];
	//$dateto = $date_2[0];

	$dateRangeStart = $startdate;
	$dateRangeEnd = $enddate;		
	$startdate = $startdate." 00:00:00";
	$enddate = $enddate." 23:59:59";
	//$timefrom = $date_1[1];
	//$timeto = $date_2[1];
	//echo "dateto=".$dateto."dateFrom=".$datefrom."<br>";
	get_All_Dates($dateRangeStart, $dateRangeEnd, $userdates);    
        $date_size = sizeof($userdates);
	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	
	
	$start_time_flag = 0;
	$distance_total = 0;
	$distance_threshold = 0.200;
	$distance_error = 0.100;
	$distance_incriment =0.0;
	$firstdata_flag =0;
	$start_point_display =0;
	$j=0;
	$haltFlag==True;
	$distance_travel=0; 

	for($i=0;$i<=($date_size-1);$i++)
	{ $SortedDataObject=new data();
            readFileXmlNew($vehicle_serial,$userdates[$i],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
            //var_dump($SortedDataObject);	
		    	
			if(count($SortedDataObject->deviceDatetime)>0)
		{			
				//echo "<br>file_exists1";     
				$t=time();
				
				$DataComplete=false;                  
				$vehicleserial_tmp=null;
				$f =0;      
				if(count($SortedDataObject->deviceDatetime)>0) 
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
					$prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
                            for($obi=0;$obi<$prevSortedSize;$obi++)
                            {
						$DataValid = 0;
						$lat = $SortedDataObject->latitudeData[$obi];
                                $lng = $SortedDataObject->longitudeData[$obi];
                                if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
                                {
                                    $DataValid = 1;
                                }
				 $datetime=$SortedDataObject->deviceDatetime[$obi];
                                $xml_date=$datetime;				
				         
					if($xml_date!=null)
					{
                                                if($DataValid==1 && ($SortedDataObject->deviceDatetime[$obi]>$startdate && $SortedDataObject->deviceDatetime[$obi]<$enddate))
						{							           	                            
								
								//$vserial = get_xml_data('/vehicleserial="[^"]+"/', $line);
								$vserial = $vehicle_serial;								
								
								$speed = $SortedDataObject->speedData[$obi];
							
								                           
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
									$max_speed	=0.0;								
								}           	              	
								else
								{           
									$lat_E = $lat;
									$lng_E = $lng; 
									$datetime_E = $datetime; 
									calculate_distance($lat_S, $lat_E, $lng_S, $lng_E, $distance_incriment);								         		
									$tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;                
									
									calculate_distance($latlast, $lat_E, $lnglast, $lng_E, $distance1);
									$tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time) )) / 3600; 
									
									if($tmp_time_diff1>0)
									{
										$tmp_speed = ((double) ($distance_incriment)) / $tmp_time_diff;
										$tmp_speed1 = ((double) ($distance1)) / $tmp_time_diff1;
									}
									else
									{
										$tmp_speed1 = 1000.0; //very high value
									}
									                                               
									if($tmp_speed<300.0)
									{
										$speeed_data_valid_time = $datetime;
									}
									
									if(( strtotime($datetime) - strtotime($speeed_data_valid_time) )>300) //data high speed for 5 mins
									{
										$lat_S = $lat_E;
										$lng_S = $lng_E;
										$last_time = $datetime;
									}
    
									$last_time1 = $datetime;
									$latlast = $lat_E;
									$lnglast =  $lng_E;
									//echo"maxspeed=".$max_speed."speed=".$speed."<br>";
									if($max_speed<$speed)
									{
										$max_speed = $speed;
									}
									
																	
									if($tmp_speed<300.0 && $tmp_speed1<300.0 && $distance_incriment>0.1 && $tmp_time_diff>0.0 && $tmp_time_diff1>0)
									{
										if($haltFlag==True)
										{
											$datetime_travel_start = $datetime_E;
											$lat_travel_start = $lat_E;
											$lng_travel_start = $lng_E;
											$distance_travel = 0;
											$distance_total = 0;
											$distance_incrimenttotal = 0;
											$max_speed = 0.0;
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
									
									$datetime_diff = strtotime($datetime_E) - strtotime($datetime_S);
									        
									//if(($distance_total>$distance_error) && ($datetime_diff > $datetime_threshold) && ($haltFlag==False))
									//if(($distance_total>$distance_error) && ($datetime_diff > $datetime_threshold) && ($haltFlag==False))
									if(($datetime_diff > $datetime_threshold) && ($haltFlag==False))
									{
										
										//echo "maxspeed=".$max_speed."<br>";
										//newHalt($datetime_S, $datetime_E);
										$datetime_travel_end = $datetime_S;
									
										//echo "start_date1=".$datetime_travel_start."end_date1=".$datetime_travel_end."<br>";
										$lat_travel_end = $lat_S;
										$lng_travel_end = $lng_S;
										newTravel($vserial, $vname, $datetime_travel_start, $datetime_travel_end, $distance_travel, $lat_travel_start, $lng_travel_start, $lat_travel_end, $lng_travel_end, $distance_travel,$max_speed,$fh, $linetowrite);
										$haltFlag = True;
										$j=0;
									}
								}
							} // $xml_date_current >= $startdate closed
						}   // if xml_date!null closed
						$f++;
					}   // while closed
					
					if($haltFlag==false)
					{
						$datetime_travel_end = $datetime_E;
						$lat_travel_end = $lat_S;
						$lng_travel_end = $lng_S;
						//$max_speed = max($speed_arr);
						//$max_speed = round($max_speed,2);
						newTravel($vserial, $vname, $datetime_travel_start, $datetime_travel_end, $distance_travel, $lat_travel_start, $lng_travel_start, $lat_travel_end, $lng_travel_end, $distance_travel, $max_speed, $fh, $linetowrite);
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
			
