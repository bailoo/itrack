<?php 
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
include_once("android_common_xml_element.php");
set_time_limit(300);
include_once("android_get_all_dates_between.php");
include_once("android_sort_xml.php");
include_once("android_get_location_lp_track_report.php");
include_once("android_calculate_distance.php");
include_once("android_check_with_range.php");
include_once("androidPointLocation.php");
//include_once("get_location.php");
include_once("util_android_hr_min_sec.php");
include_once("android_new_xml_string_io.php");

$v_size=count($vehicle_serial);
$device_str= $_POST["vehicleserialWithIo"];
//$device_str="862170018371961:862170018369908:# , ,";
$device_str = explode('#',$device_str);
//echo "device_str1=".$device_str[0]."<br>";
//echo "device_str2=".$device_str[1]."<br>";
$vserial = explode(':',substr($device_str[0],0,-1));
$iotype_element = explode(',',substr($device_str[1],0,-1));

$geo_id_str= $_REQUEST["geo_id"];
$geo_id1 = explode(':',$geo_id_str);


$date1 = $_REQUEST["startDate"];
$date2 =  $_REQUEST["endDate"];

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
$user_interval = $_POST['userInterval'];
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
	

function get_halt_xml_data($vehicle_serial, $iotype_element_1 , $vname_local, $startdate,$enddate,$user_interval, $xmltowrite)
{
	global $halt_report_data;
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
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

	for($i=0;$i<=($date_size-1);$i++)
	{
		//if($userdates[$i] == $current_date)
		//{	
		$xml_current = "../../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
    		
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
		if (file_exists($xml_file)) 
		{			
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
			$xml = @fopen($xml_original_tmp, "r") or $fexist = 0; 		
			$logcnt=0;
			$DataComplete=false;                  
			$vehicleserial_tmp=null;      
			$f=0;      
			if (file_exists($xml_original_tmp)) 
			{
				set_master_variable($userdates[$i]);
				while(!feof($xml))          // WHILE LINE != NULL
				{
					$DataValid = 0;			
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE		
					//echo"<textarea>".$line."</textarea>";
					if(strlen($line)>20)
					{
						$linetmp =  $line;
					}  	
					
					$linetolog =  $logcnt." ".$line;
					$logcnt++;					
					//echo "vc=".$vc."<br>";
					if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
					{
						$format = 1;
						$fix_tmp = 1;
					} 
					//echo "vc=".$vc."<br>";					
					if(strpos($line,''.$vc.'="0"'))
					{
						$format = 1;
						$fix_tmp = 0;
					}
					else
					{
						$format = 2;
					}  
					//echo "vc=".$vd."ve=".$ve."<br>";
					if((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{ 
						$lat_value = explode('=',$lat_match[0]);
						$lng_value = explode('=',$lng_match[0]);			       
						if((strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
						{
							$DataValid = 1;
						}
					} 
					
					if(($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
					{
						$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);						
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
						$xml_date = $datetime;
					}				
				         
					if($xml_date!=null)
					{

						if(($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
						{							           	
						               
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
							
							$status = preg_match('/'.$vf.'="[^" ]+/', $line, $speed_tmp);
							if($status==0)
							{
							  continue;
							}  
							//echo "vc=".$vd."ve=".$ve."<br>";
							$io_typ_value=explode(":",$iotype_element_1);
							$io_cnt=count($io_typ_value);
							//echo "vc1=".$vd."ve1=".$ve."<br>";
							if($io_cnt>0)
							{
								for($j=0;$j<sizeof($io_typ_value);$j++)
								{
									$io_typ_value1=explode("^",$io_typ_value[$j]);
									$tmp_io="io".$io_typ_value1[0];	
									$tmp_io=get_io_to_new_method($userdates[$i],$old_xml_date,$tmp_io);	
									if($io_typ_value1[1]=="temperature")
									{
										$status = preg_match('/'.$tmp_io.'="[^" ]+/', $line, $temperature_tmp);																	
										$temperature_tmp1 = explode("=",$temperature_tmp[0]);
										$temperature = preg_replace('/"/', '',$temperature_tmp1[1]);							
									}																	
								}
							}
						//echo "vc2=".$vd."ve2=".$ve."<br>";
							if($firstdata_flag==0)
							{							
								$halt_flag = 0;
								$firstdata_flag = 1;								
								$vserial=$vehicle_serial;						
								$lat_tmp1 = explode("=",$lat_tmp[0]);							
								$lat_ref = preg_replace('/"/', '', $lat_tmp1[1]);
								$lng_tmp1 = explode("=",$lng_tmp[0]);
								$lng_ref = preg_replace('/"/', '', $lng_tmp1[1]);							
								$datetime_ref = $datetime;
								$tmp_ref = $temperature;						                 	
								$date_secs1 = strtotime($datetime_ref);						
								$date_secs1 = (double)($date_secs1 + $interval);  						           	
							}           	
						               	
							else
							{ 				              
								$lat_tmp1 = explode("=",$lat_tmp[0]);                           //  GET NEXT RECO
								$lat_cr = preg_replace('/"/', '', $lat_tmp1[1]);
								$lng_tmp1 = explode("=",$lng_tmp[0]);
								$lng_cr = preg_replace('/"/', '', $lng_tmp1[1]);
								$datetime_cr = $datetime;
								$tmp_cr = $temperature;																	
								$date_secs2 = strtotime($datetime_cr);						
								calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);							
								if($distance > 0.150)
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
	echo json_encode($halt_report_data);  
?>			
