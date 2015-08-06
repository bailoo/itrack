<?php
set_time_limit(2000);
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
include_once("android_common_xml_element.php");
include_once("android_calculate_distance.php");
include_once("android_get_all_dates_between.php");
include_once("android_new_xml_string_io.php");
include_once("util_android_hr_min_sec.php");
$reportType= $_POST["reportType"];
if($reportType=="V")
{
include_once("android_sort_xml.php");
}
else if($reportType=="P")
{
include_once("android_sort_person_xml.php");
}

$device_str= $_POST["vehicleserialWithIo"];
//$device_str="862170018369908:# ,";
$device_str = explode('#',$device_str);
//echo "device_str1=".$device_str[0]."<br>";
//echo "device_str2=".$device_str[1]."<br>";
$vserial = explode(':',substr($device_str[0],0,-1));
$iotype_element = explode(',',substr($device_str[1],0,-1));
$startdate = $_POST['startDate'];
$enddate = $_POST['endDate']; 

/*$startdate = "2013/11/05 10:58:57";
$enddate = "2013/11/06 10:58:59"; */

$startdate = str_replace('/', '-', $startdate);  
$enddate = str_replace('/', '-', $enddate); 
$time_interval1 = $_POST['userInterval']; 
//$time_interval1="900";
//$time_interval1=1;
//include_once("sort_xml.php");
$minlat = 180; 
$maxlat = -180;
$minlong = 180;
$maxlong = -180;
$maxPoints = 1000;
$file_exist = 0;	
$tmptimeinterval = strtotime($enddate) - strtotime($startdate);

if($time_interval1=="auto")
{
	$timeinterval =   ($tmptimeinterval/$maxPoints);
	$distanceinterval = 0.1; 
}
else
{
	if($tmptimeinterval>86400)
	{
		$timeinterval =   $time_interval1;		
		$distanceinterval = 0.3;
	}
	else
	{
		$timeinterval =   $time_interval1;
		$distanceinterval = 0.02;
	}
} 

global $track_report_data;
$track_report_data=array();
			
for($i=0;$i<sizeof($vserial);$i++)
{		
	$Query="SELECT vehicle.vehicle_name,vehicle.vehicle_type,vehicle.vehicle_number FROM vehicle USE INDEX(v_vehicleid_status)".
		",vehicle_assignment USE INDEX(va_vehicleid_status,va_vehicleid_imei_status) WHERE vehicle.vehicle_id=vehicle_assignment.".
		"vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_assignment.device_imei_no='$vserial[$i]'";
	$Result=mysql_query($Query,$DbConnection);
	$Row=mysql_fetch_row($Result);
	getTrack($vserial[$i],$Row[0],$Row[1],$startdate,$enddate,$iotype_element[$i],$timeinterval,$distanceinterval);
}


	function getTrack($vehicle_serial,$vname,$vehicle_number,$startdate,$enddate,$iotype_element_1,$timeinterval,$distanceinterval)
	{
		global $track_report_data;
		//echo "in function<br>";		
		global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
		global $old_xml_date;	
		//echo "In Track";
		
		$fix_tmp = 1;
		$xml_date_latest="1900-00-00 00:00:00";
		$CurrentLat = 0.0;
		$CurrentLong = 0.0;
		$LastLat = 0.0;
		$LastLong = 0.0;
		$firstData = 0;
		$distance =0.0;
		$linetowrite="";
		$date_1 = explode(" ",$startdate);
		$date_2 = explode(" ",$enddate);

		$datefrom = $date_1[0];
		$dateto = $date_2[0];
		$timefrom = $date_1[1];
		$timeto = $date_2[1];

		get_All_Dates($datefrom, $dateto, &$userdates);

		date_default_timezone_set("Asia/Calcutta");
		$current_datetime = date("Y-m-d H:i:s");
		$current_date = date("Y-m-d");
		//print "<br>CurrentDate=".$current_date;
		$date_size = sizeof($userdates);
		//$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append

		for($i=0;$i<=($date_size-1);$i++)
		{
			//if($userdates[$i] == $current_date)
			//{	
			$xml_current = "../../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
			//echo "xml_current=".$xml_current."<br>";
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
			//echo "<br>xml_file=".$xml_file;			
			if (file_exists($xml_file)) 
			{
				//echo "in file exist 1<br>";
				set_master_variable($userdates[$i]);
				$t=time();
				//$current_datetime1 = date("Y_m_d_H_i_s");      
				//$xml_original_tmp = "xml_tmp/original_xml/tmp_".$current_datetime1.".xml";
				$xml_original_tmp = "../../../../xml_tmp/original_xml/tmp_".$t."_".$i.".xml";
				//$xml_log = "xml_tmp/filtered_xml/tmp_".$current_datetime1.".xml";
				//echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
				//copy($xml_file,$xml_original_tmp); 
											  
				if($CurrentFile == 0)
				{
					//echo "<br>ONE<br>";
					copy($xml_file,$xml_original_tmp);
				}
				else
				{
					//echo "<br>TWO<br>";
					//$xml_unsorted = "xml_tmp/unsorted_xml/tmp".$current_datetime1."_unsorted.xml";
					$xml_unsorted = "../../../../xml_tmp/unsorted_xml/tmp_".$t."_".$i."_unsorted.xml";
					//echo  "<br>".$xml_file." <br>".$xml_unsorted;				
					copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
					SortFile($xml_unsorted, $xml_original_tmp,$userdates[$i]);    // SORT FILE
					unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
				}      
				$f=0;  
				$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;
				$total_lines = count(file($xml_original_tmp)); 
		
				//$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
				$logcnt=0;
				$DataComplete=false;
				
				if (file_exists($xml_original_tmp)) 
				{
					//echo "in file exist 2<br>";
					while(!feof($xml))          // WHILE LINE != NULL
					{
						//echo fgets($file). "<br />";
						$DataValid = 0;
						$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE
						//echo "0:line:".$line;

						if(strlen($line)>20)
						{
							// $linetmp =  $line;
						}
					
						$linetolog =  $logcnt." ".$line;
						$logcnt++;
						//fwrite($xmllog, $linetolog);
						
						//echo "vc:".$vc;
						if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
						{
							$fix_tmp = 1;
						}                
						else if(strpos($line,''.$vc.'="0"'))
						{
							$fix_tmp = 0;
						}
						else
						{
							$fix_tmp = 2;
						}				
						if((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
						{ 
							$lat_value = explode('=',$lat_match[0]);
							$lng_value = explode('=',$lng_match[0]);							
							//echo " lat_value=".$lat_value[1];         
							if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
							{
								$DataValid = 1;
							}         
						}
						/*echo "datavalie=".$DataValid;
						echo "line1=".$line[strlen($line)-2];
						echo "fix_tmp=".$fix_tmp;*/
						$linetmp = "";
						//if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
						if(($line[0] == '<') && ($line[strlen($line)-2] == '>') && (($fix_tmp==1) || ($fix_tmp == 2))&& ($DataValid == 1) )        
						{
							//preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
							//echo "<br>str3tmp[0]=".$str3tmp[0];
							//$xml_date_current = $str3tmp[0];
							$linetmp =  $line;
							//echo "linetmp=".$linetmp;
							$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
							$datetime_tmp1 = explode("=",$datetime_tmp[0]);
							$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
							$xml_date_current = $datetime;										
						}  				
						//echo "Final0=".$xml_date_current." datavalid=".$DataValid;
			  
						if (($xml_date_current!=null)  && ($DataValid==1) && (($fix_tmp==1) || ($fix_tmp == 2)))
						{
							$linetolog = $xml_date_current.' '.$firstData."\n";
							//fwrite($xmllog, $linetolog);
							//echo "Final1";
							$CurrentLat = $lat_value[1] ;
							$CurrentLong = $lng_value[1];

							if(($xml_date_current >= $startdate && $xml_date_current <= $enddate) && ($xml_date_current!="-"))
							{
								//echo "Final2";
								if($firstData==1)
								{
									if($minlat>$CurrentLat)
									{
										$minlat = $CurrentLat;
									}
									if($maxlat<$CurrentLat)
									{
										$maxlat = $CurrentLat;
									}
					
									if($minlong>$CurrentLong)
									{
										$minlong = $CurrentLong;
									}
									if($maxlong<$CurrentLong)
									{
										$maxlong = $CurrentLong;
									}                
									$tmp1lat = round(substr($CurrentLat,1,(strlen($CurrentLat)-3)),4);
									$tmp2lat = round(substr($LastLat,1,(strlen($LastLat)-3)),4);
									$tmp1lng = round(substr($CurrentLong,1,(strlen($CurrentLong)-3)),4);
									$tmp2lng = round(substr($LastLong,1,(strlen($LastLong)-3)),4);  							
									//echo  "Coord: ".$tmp1lat.' '.$tmp2lat.' '.$tmp1lng.' '.$tmp2lng.'<BR>';             							
									calculate_distance($tmp1lat,$tmp2lat,$tmp1lng,$tmp2lng,&$distance);                
									$linetolog = $CurrentLat.','.$CurrentLong.','.$LastLat.','.$LastLong.','.$distance.','.$xml_date_current.','.$xml_date_last.','.(strtotime($xml_date_current)-strtotime($xml_date_last)).','.$timeinterval.','.$distanceinterval.','.$enddate.','.$startdate."\n";
									//fwrite($xmllog, $linetolog);
								}
								if((((((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval) && ($distance>=$distanceinterval)) || ($firstData==0)) && 
								(($xml_date_current<=$enddate) && ($xml_date_current>=$startdate))) || ($f==$total_lines-2) )
								{
									//echo "please wait..";
									$linetolog = "Data Written\n";
									//fwrite($xmllog, $linetolog);
									//echo "<br>FinalWrite";
									$xml_date_last = $xml_date_current;
									$LastLat =$CurrentLat;
									$LastLong =$CurrentLong;
									$line = substr($line, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
									$finalDistance = $finalDistance + $distance;
									$io_typ_value=explode(":",$iotype_element_1);
									$io_cnt=count($io_typ_value);
									$lat_value_1 = preg_replace('/"/', '', $lat_value[1]);
									$lng_value_1= preg_replace('/"/', '', $lng_value[1]);
									$status = preg_match('/'.$vf.'="[^"]+/', $line, $speed_tmp_local);
									$speed_tmp_local1 = explode("=",$speed_tmp_local[0]);
									$speed_local = preg_replace('/"/', '', $speed_tmp_local1[1]);
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
									if($userdates[$i]<$old_xml_date)
									{
										//echo "in replace 1";
										$line=str_replace("marker","x",$line);
										$line=str_replace("msgtype=","a=",$line);
										$line=str_replace("vehicleserial=","v=",$line);
										$line=str_replace("ver=","b=",$line);
										$line=str_replace("fix=","c=",$line);
										$line=str_replace("lat=","d=",$line);
										$line=str_replace("lng=","e=",$line);
										$line=str_replace("speed=","f=",$line);
										$line=str_replace("sts=","g=",$line);
										$line=str_replace("datetime=","h=",$line);
										$line=str_replace("io1=","i=",$line);
										$line=str_replace("io2=","j=",$line);
										$line=str_replace("io3=","k=",$line);
										$line=str_replace("io4=","l=",$line);
										$line=str_replace("io5=","m=",$line);
										$line=str_replace("io6=","n=",$line);
										$line=str_replace("io7=","o=",$line);
										$line=str_replace("io8=","p=",$line);
										$line=str_replace("sig_str=","q=",$line);
										$line=str_replace("sup_v=","r=",$line);
										$line=str_replace("day_max_speed=","s=",$line);
										$line=str_replace("day_max_speed_time=","t=",$line);
										$line=str_replace("last_halt_time=","u=",$line);
										$line=str_replace("cellname=","ab=",$line);
										
										$track_report_data[]=array("deviceImeiNo"=>$vehicle_serial,"vehicleName"=>$vname,"vehicleNumber"=>$vehicle_number,"datetime"=>$datetime,"temperature"=>$temperature,"cumdist"=>round($finalDistance,2),"latitude"=>$lat_value_1,"longitude"=>$lng_value_1,"speed"=>$speed_local);
										//$linetowrite = "\n".$line.' w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
									}
									else
									{
										$track_report_data[]=array("deviceImeiNo"=>$vehicle_serial,"vehicleName"=>$vname,"vehicleNumber"=>$vehicle_number,"datetime"=>$datetime,"temperature"=>$temperature,"cumdist"=>round($finalDistance,2),"latitude"=>$lat_value_1,"longitude"=>$lng_value_1,"speed"=>$speed_local);
										//$linetowrite = "\n".$line.' v="'.$vehicle_serial.'" w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
									}
									//echo "<br>finalDistance=".$finalDistance;
									//$linetowrite = "\n".$line.' cumdist="'.$finalDistance.'"./>'; // for distance       // ADD DISTANCE
									//$linetowrite = "\n".$line.'/>';
									//echo "<textarea>".$linetowrite."</textarea>";
									//echo "lintowrite=".$linetowrite;
									$firstData = 1;  
									//$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
									fwrite($fh, $linetowrite);  
								}
							}
							else if(($xml_date_current > $enddate) && ($xml_date_current!="-") && ($DataValid==1) )
							{
								//echo "in first";
								$linetolog = "Data Written1\n";
								//fwrite($xmllog, $linetolog);
								$line = substr($line, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
								//$linetowrite = "\n".$line.' distance="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
								// $linetowrite = "\n".$line.'/>'; // for distance       // ADD DISTANCE
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
								if($userdates[$i]<$old_xml_date)
								{
									$line=str_replace("marker","x",$line);
									$line=str_replace("msgtype=","a=",$line);
									$line=str_replace("vehicleserial=","v=",$line);
									$line=str_replace("ver=","b=",$line);
									$line=str_replace("fix=","c=",$line);
									$line=str_replace("lat=","d=",$line);
									$line=str_replace("lng=","e=",$line);
									$line=str_replace("speed=","f=",$line);
									$line=str_replace("sts=","g=",$line);
									$line=str_replace("datetime=","h=",$line);
									$line=str_replace("io1=","i=",$line);
									$line=str_replace("io2=","j=",$line);
									$line=str_replace("io3=","k=",$line);
									$line=str_replace("io4=","l=",$line);
									$line=str_replace("io5=","m=",$line);
									$line=str_replace("io6=","n=",$line);
									$line=str_replace("io7=","o=",$line);
									$line=str_replace("io8=","p=",$line);
									$line=str_replace("sig_str=","q=",$line);
									$line=str_replace("sup_v=","r=",$line);
									$line=str_replace("day_max_speed=","s=",$line);
									$line=str_replace("day_max_speed_time=","t=",$line);
									$line=str_replace("last_halt_time=","u=",$line);
									
									$track_report_data[]=array("deviceImeiNo"=>$vehicle_serial,"vehicleName"=>$vname,"vehicleNumber"=>$vehicle_number,"datetime"=>$datetime,"temperature"=>$temperature,"cumdist"=>round($finalDistance,2),"latitude"=>$lat_value_1,"longitude"=>$lng_value_1,"speed"=>$speed_local);
									//$linetowrite = "\n".$line.' w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
								}
								else
								{
									$track_report_data[]=array("deviceImeiNo"=>$vehicle_serial,"vehicleName"=>$vname,"vehicleNumber"=>$vehicle_number,"datetime"=>$datetime,"temperature"=>$temperature,"cumdist"=>round($finalDistance,2),"latitude"=>$lat_value_1,"longitude"=>$lng_value_1,"speed"=>$speed_local);
									//$linetowrite = "\n".$line.' v="'.$vehicle_serial.'" w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
								}//echo "lintowrite=".$linetowrite;
								fwrite($fh, $linetowrite);
								$DataComplete=true;
								break;
							}
						}
						$f++;
					}   // while closed
				} // if original_tmp exist closed 
		  
				if($DataComplete==false)
				{
					//echo "in false";
					if( (preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $linetmp, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $linetmp, $lng_match)) )
					{ 
						$lat_value = explode('=',$lat_match[0]);
						$lng_value = explode('=',$lng_match[0]);
						//echo " lat_value=".$lat_value[1];         
						if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
						{
							$DataValid = 1;
						} 
						else
						{
							$DataValid = 0;
						}
					}
					else
					{
						$DataValid = 0;
					}		
					if($DataValid == 1)
					{
						$linetolog = "Data Written2\n";
						//fwrite($xmllog, $linetolog);
						//echo "linetmp=".$linetmp;
						$line = substr($linetmp, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
						//$linetowrite = "\n".$line.' distance="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
						//$linetowrite = "\n".$line.'/>'; // for distance       // ADD DISTANCE
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
						if($userdates[$i]<$old_xml_date)
						{
							//echo "1in2";
							$line=str_replace("marker","x",$line);
							$line=str_replace("msgtype=","a=",$line);
							$line=str_replace("vehicleserial=","v=",$line);
							$line=str_replace("ver=","b=",$line);
							$line=str_replace("fix=","c=",$line);
							$line=str_replace("lat=","d=",$line);
							$line=str_replace("lng=","e=",$line);
							$line=str_replace("speed=","f=",$line);
							$line=str_replace("sts=","g=",$line);
							$line=str_replace("datetime=","h=",$line);
							$line=str_replace("io1=","i=",$line);
							$line=str_replace("io2=","j=",$line);
							$line=str_replace("io3=","k=",$line);
							$line=str_replace("io4=","l=",$line);
							$line=str_replace("io5=","m=",$line);
							$line=str_replace("io6=","n=",$line);
							$line=str_replace("io7=","o=",$line);
							$line=str_replace("io8=","p=",$line);
							$line=str_replace("sig_str=","q=",$line);
							$line=str_replace("sup_v=","r=",$line);
							$line=str_replace("day_max_speed=","s=",$line);
							$line=str_replace("day_max_speed_time=","t=",$line);
							$line=str_replace("last_halt_time=","u=",$line);
							$line=str_replace("cellname=","ab=",$line);							
							$track_report_data[]=array("deviceImeiNo"=>$vehicle_serial,"vehicleName"=>$vname,"vehicleNumber"=>$vehicle_number,"datetime"=>$datetime,"temperature"=>$temperature,"cumdist"=>round($finalDistance,2),"latitude"=>$lat_value_1,"longitude"=>$lng_value_1,"speed"=>$speed_local);
						}
						else
						{
							$track_report_data[]=array("deviceImeiNo"=>$vehicle_serial,"vehicleName"=>$vname,"vehicleNumber"=>$vehicle_number,"datetime"=>$datetime,"temperature"=>$temperature,"cumdist"=>round($finalDistance,2),"latitude"=>$lat_value_1,"longitude"=>$lng_value_1,"speed"=>$speed_local);
						}//echo "lintowrite=".$linetowrite;
						fwrite($fh, $linetowrite);
					}
				}         
				fclose($xml);            
				unlink($xml_original_tmp);
			} // if (file_exists closed
		}  // for closed 	
		//echo "Test1";
		fclose($fh);
	//fclose($xmllog);
	}	
	echo json_encode($track_report_data); 
?>

