<?php 
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
set_time_limit(300);
include_once("android_get_location_lp_track_report.php");
include_once("android_calculate_distance.php");
include_once("android_check_with_range.php");
include_once("androidPointLocation.php");
//include_once("get_location.php");
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
function get_halt_xml_data_prev($vehicleserialWithIo,$startDate,$endDate,$userInterval)
{

$device_str= $vehicleserialWithIo;
//$device_str="862170018371961:862170018369908:# , ,";
$device_str = explode('#',$device_str);
//echo "device_str1=".$device_str[0]."<br>";
//echo "device_str2=".$device_str[1]."<br>";
$vserial = explode(':',substr($device_str[0],0,-1));
$iotype_element = explode(',',substr($device_str[1],0,-1));

$geo_id_str= $_REQUEST["geo_id"];
$geo_id1 = explode(':',$geo_id_str);


$date1 = $startDate;
$date2 =  $endDate;

/*$date1 = "2013/11/01 13:58:21";
$date2 = "2013/11/04 13:58:24";*/

$date1 = str_replace('/', '-', $date1);  
$date2 = str_replace('/', '-', $date2); 

$date_1 = explode(" ",$date1);
$date_2 = explode(" ",$date2);
$datefrom = $date_1[0];
$dateto = $date_2[0];
$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);

$current_date = date("Y-m-d");

$k1 = 0;
$m_start=0;

$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);
$user_interval = $userInterval;
//$user_interval = "15";
//read_halt_xml($xml_path, &$imei, &$vname, &$lat, &$lng, &$arr_time, &$dep_time, &$in_temperature, &$out_temperature, &$duration);	

global $halt_report_data;
$halt_report_data=array();
for($i=0;$i<sizeof($vserial);$i++)
{ 
    
    
	//echo   "<br>vserial[i] =".$vserial[$i];
	$Query="SELECT vehicle.vehicle_name,vehicle.vehicle_type,vehicle.vehicle_number FROM vehicle USE INDEX(v_vehicleid_status)".
		",vehicle_assignment USE INDEX(va_vehicleid_status,va_vehicleid_imei_status) WHERE vehicle.vehicle_id=vehicle_assignment.".
		"vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_assignment.device_imei_no='$vserial[$i]'";
	$Result=mysql_query($Query,$DbConnection);
	$Row=mysql_fetch_row($Result);
	get_halt_xml_data($vserial[$i], $iotype_element[$i], $Row[0], $date1, $date2, $user_interval, $xmltowrite);
}
	return json_encode($halt_report_data); 	
}


function get_halt_xml_data($vehicle_serial, $iotype_element_1 , $vname_local, $startdate,$enddate,$user_interval, $xmltowrite)
{
	global $halt_report_data;
	
	$interval = $user_interval*60; 
	global $DbConnection;
	global $account_id;
	global $geo_id1;
	$halt_flag = 0;
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
	$date_1 = explode(" ",$startdate);
	$date_2 = explode(" ",$enddate);

	$dateRangeStart = $date_1[0];
	$dateRangeEnd = $date_2[0];
	$timefrom = $date_1[1];
	$timeto = $date_2[1];

	 get_All_Dates($dateRangeStart, $dateRangeEnd, $userdates);    
        $date_size = sizeof($userdates);
	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	
         $parameterizeData=new parameterizeData();
    $ioFoundFlag=0;
    global $o_cassandra;

    $parameterizeData->latitude="d";
    $parameterizeData->longitude="e";
    $parameterizeData->speed="f";
    
    $ioArr=explode(":",$iotype_element_1);
    $ioFoundFlag=0;
    $ioArrSize=sizeof($ioArr);
    for($z=0;$z<$ioArrSize;$z++)
    {
        $tempIo=explode("^",$ioArr[$z]);
        //echo "io=".$ioArr[$z]."<br>";
        if($tempIo[1]=="temperature")
        {
            $ioFoundFlag=1;
            $parameterizeData->temperature=$finalIoArr[$tempIo[0]];
        }
    }

	for($i=0;$i<=($date_size-1);$i++)
	{
            $SortedDataObject=new data();
            readFileXmlNew($vserial[$i],$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
            //var_dump($SortedDataObject);	    	
		if(count($SortedDataObject->deviceDatetime)>0)
		{			
			$t=time();
					
			$logcnt=0;
			$DataComplete=false;                  
			$vehicleserial_tmp=null;      
			$f=0;      
			if(count($SortedDataObject->deviceDatetime)>0)
                        {
			
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
						               
							
                                                        if($ioFoundFlag==1)
                                                        {
                                                            $temperature=$SortedDataObject->temperatureIOData[$obi];                
                                                        }
                                                        else
                                                        {
                                                            $temperature="0.0";
                                                        }
						//echo "vc2=".$vd."ve2=".$ve."<br>";
							if($firstdata_flag==0)
							{							
								$halt_flag = 0;
								$firstdata_flag = 1;								
								$vserial=$vehicle_serial;						
														
								$lat_ref = $lat;							
								$lng_ref = $lng;							
								$datetime_ref = $datetime;
								$tmp_ref = $temperature;						                 	
								$date_secs1 = strtotime($datetime_ref);						
								$date_secs1 = (double)($date_secs1 + $interval);  						           	
							}           	
						               	
							else
							{ 				              
                                                            $lat_cr = $lat;							
                                                            $lng_cr = $lng;
                                                            $datetime_cr = $datetime;
                                                            $tmp_cr = $temperature;																	
                                                            $date_secs2 = strtotime($datetime_cr);						
                                                            calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);							
								if($distance > 0.1)
								{									
									if ($halt_flag == 1)
									{
										$arrivale_time=$datetime_ref;
										$tmp_arr=$tmp_ref;
										$starttime = strtotime($datetime_ref);									  
										$stoptime = strtotime($datetime_cr);
										$depature_time=$datetime_cr;
										$tmp_dep=$tmp_cr;									
										$halt_dur =  ($stoptime - $starttime);										
										if($halt_dur >= $interval)
										{
											if((sizeof($geo_id1)>0) && ($geo_id1[0]!="") )
											{                                                                                            
												$exclude_flag = 1;
												$geo_status = 1;
												for($j=0;$j<sizeof($geo_id1);$j++)
												{                                                                                                    
													include('android_halt_exclusion.php');
													if($geo_coord!="")
													{                
														check_with_range($lat_ref, $lng_ref, $geo_coord, &$geo_status);
														//echo "<Br>geo_status1:".$geo_status;                                        
													}
													if($geo_status == 1)
													{
														$exclude_flag = 0;
													}                                     
												}										
												if(($geo_status==false) && ($exclude_flag==1))
												{ 
													if($tmp_arr=="" && $tmp_dep=="")
													{
														$tmp_arr="0.0";
														$tmp_dep="0.0";
													}
													else
													{
														if($tmp_arr<-30 || $tmp_arr>70)
														{
															$tmp_arr="-";
														}
														if($tmp_dep<-30 || $tmp_dep>70)
														{
															$tmp_dep="-";
														}
													}
													$hms1 = secondsToTime($halt_dur);
													$duration_tmp = $hms1[h].":".$hms1[m].":".$hms1[s];	
													$latLng=$lat_ref.",".$lng_ref;
													$halt_report_data[]=array("deviceImeiNo"=>$vserial,"vehicleName"=>$vname_local,"arrivalTime"=>$arrivale_time,"departureTime"=>$depature_time,"inTemperature"=>$tmp_arr,"outTemperature"=>$tmp_dep,"haltDuration"=>$halt_dur,"latitudeLongitude"=>$latLng);		
												  

													$date_secs1 = strtotime($datetime_cr);
													$date_secs1 = (double)($date_secs1 + $interval);										
												}  // IF STATUS  
											} // SIZE OF GEO_ID
											else
											{												
												if($tmp_arr=="" && $tmp_dep=="")
												{
													$tmp_arr="0.0";
													$tmp_dep="0.0";
												}
												else
												{
													if($tmp_arr<-30 || $tmp_arr>70)
													{
														$tmp_arr="-";
													}
													if($tmp_dep<-30 || $tmp_dep>70)
													{
														$tmp_dep="-";
													}
												}
												$latLng=$lat_ref.",".$lng_ref;
												$hms1 = secondsToTime($halt_dur);
												$duration_tmp = $hms1[h].":".$hms1[m].":".$hms1[s];	
												$halt_report_data[]=array("deviceImeiNo"=>$vserial,"vehicleName"=>$vname_local,"arrivalTime"=>$arrivale_time,"departureTime"=>$depature_time,"inTemperature"=>$tmp_arr,"outTemperature"=>$tmp_dep,"haltDuration"=>$duration_tmp,"latitudeLongitude"=>$latLng);		
												$date_secs1 = strtotime($datetime_cr);
												$date_secs1 = (double)($date_secs1 + $interval);                              
											}                       
										}		// IF TOTAL MIN										
									}   //IF HALT FLAG
									$lat_ref = $lat_cr;
									$lng_ref = $lng_cr;
									$datetime_ref= $datetime_cr;
									$tmp_ref= $tmp_cr;
									

									$halt_flag = 0;
								}
								else if(((strtotime($datetime_cr)-strtotime($datetime_ref))>60) && ($halt_flag != 1))
								{            			
									//echo "<br>normal flag set "." datetime_cr ".$datetime_cr."<br>";
									
									$halt_flag = 1;
								}
							}
						} // $xml_date_current >= $startdate closed
					}   // if xml_date!null closed
					$f++;
				}   // while closed	
			} // if original_tmp closed       
			fclose($xml);            
			unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 

	if ($halt_flag == 1)
	{
		$arrivale_time=$datetime_ref;
		$tmp_arr=$tmp_ref;
		$starttime = strtotime($datetime_ref);	  
		$stoptime = strtotime($datetime_cr);
		$depature_time=$datetime_cr;
		$tmp_dep=$tmp_cr;	
		$halt_dur =  ($stoptime - $starttime);
		
		if((sizeof($geo_id1)>0) && ($geo_id1[0]!="") )
		{                                                                                            
			$exclude_flag = 1;
			$geo_status = 1;
			for($j=0;$j<sizeof($geo_id1);$j++)
			{ 
				$query_geo = "SELECT geo_coord FROM geofence WHERE user_account_id='$account_id' AND geo_id='$geo_id1[$j]' AND status=1";                    
				$res_geo = mysql_query($query_geo,$DbConnection);
				if($row_geo = mysql_fetch_object($res_geo))
				{
					$geo_coord_tmp = $row_geo->geo_coord;
					$geo_coord = base64_decode($geo_coord_tmp);
					$geo_coord = str_replace('),(',' ',$geo_coord);
					$geo_coord = str_replace('(','',$geo_coord);
					$geo_coord = str_replace(')','',$geo_coord);
					$geo_coord = str_replace(', ',',',$geo_coord);
				}  
				
				if($geo_coord!="")
				{                
					check_with_range($lat_ref, $lng_ref, $geo_coord, &$geo_status);
					//echo "<Br>geo_status1:".$geo_status;                                        
				}
				if($geo_status == 1)
				{
					$exclude_flag = 0;
				}                                    
			}	// FOR LOOP
		
			if(($geo_status==false) && ($exclude_flag==1))
			{
				if($tmp_arr=="" && $tmp_dep=="")
				{
					$tmp_arr="0.0";
					$tmp_dep="0.0";
				}
				else
				{
					if($tmp_arr<-30 || $tmp_arr>70)
					{
						$tmp_arr="-";
					}
					if($tmp_dep<-30 || $tmp_dep>70)
					{
						$tmp_dep="-";
					}
				}
				$latLng=$lat_ref.",".$lng_ref;
				$hms1 = secondsToTime($halt_dur);
				$duration_tmp = $hms1[h].":".$hms1[m].":".$hms1[s];					
				$halt_report_data[]=array("deviceImeiNo"=>$vserial,"vehicleName"=>$vname_local,"arrivalTime"=>$arrivale_time,"departureTime"=>$depature_time,"inTemperature"=>$tmp_arr,"outTemperature"=>$tmp_dep,"haltDuration"=>$duration_tmp,"latitudeLongitude"=>$latLng);		
				$date_secs1 = strtotime($datetime_cr);
				$date_secs1 = (double)($date_secs1 + $interval);
				//break;
			}  // IF STATUS  
		} // SIZE OF GEO_ID
		else
		{			
			if($tmp_arr=="" && $tmp_dep=="")
			{
				$tmp_arr="0.0";
				$tmp_dep="0.0";
			}
			else
			{
				if($tmp_arr<-30 || $tmp_arr>70)
				{
					$tmp_arr="-";
				}
				if($tmp_dep<-30 || $tmp_dep>70)
				{
					$tmp_dep="-";
				}
			}
			$latLng=$lat_ref.",".$lng_ref;
			$hms1 = secondsToTime($halt_dur);
			$duration_tmp = $hms1[h].":".$hms1[m].":".$hms1[s];
			$halt_report_data[]=array("deviceImeiNo"=>$vserial,"vehicleName"=>$vname_local,"arrivalTime"=>$arrivale_time,"departureTime"=>$depature_time,"inTemperature"=>$tmp_arr,"outTemperature"=>$tmp_dep,"haltDuration"=>$duration_tmp,"latitudeLongitude"=>$latLng);		
												 
			$date_secs1 = strtotime($datetime_cr);
			$date_secs1 = (double)($date_secs1 + $interval);                              
		}                       										
	}   //IF HALT FLAG
}  
$server = new soap_server();
$server->register("get_halt_xml_data_prev");
$server->service($HTTP_RAW_POST_DATA);
?>			
