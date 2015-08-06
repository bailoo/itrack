<?php
set_time_limit(2000);
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
include_once("android_common_xml_element.php");
include_once("android_calculate_distance.php");
include_once("android_get_all_dates_between.php");
include_once("android_new_xml_string_io.php");
include_once("util_android_hr_min_sec.php");

$device_str= $_POST["vehicleserialWithIo"];
//$device_str="862170018368900:862170018371144:# , ,";
$device_str = explode('#',$device_str);
//echo "device_str1=".$device_str[0]."<br>";
//echo "device_str2=".$device_str[1]."<br>";
$vserial = explode(':',substr($device_str[0],0,-1));
$iotype_element = explode(',',substr($device_str[1],0,-1));
$startdate = $_POST['startDate'];
$enddate = $_POST['endDate']; 
/*$startdate = "2013/11/06 00:00:00";
$enddate = "2013/11/06 12:58:24";*/ 

$startdate = str_replace('/', '-', $startdate);  
$enddate = str_replace('/', '-', $enddate); 

global $last_location_report_data;
$last_location_report_data=array();
			
for($i=0;$i<sizeof($vserial);$i++)
{		
	$Query="SELECT vehicle.vehicle_name,vehicle.vehicle_type,vehicle.vehicle_number FROM vehicle USE INDEX(v_vehicleid_status)".
		",vehicle_assignment USE INDEX(va_vehicleid_status,va_vehicleid_imei_status) WHERE vehicle.vehicle_id=vehicle_assignment.".
		"vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_assignment.device_imei_no='$vserial[$i]'";
	$Result=mysql_query($Query,$DbConnection);
	$Row=mysql_fetch_row($Result);
	getLastPosition($vserial[$i],$Row[0],$Row[1],$Row[3],$startdate,$enddate,$iotype_element[$i]);
}

	function getLastPosition($vehicle_serial,$vname,$vtype,$vehicle_number,$startdate,$enddate,$iotype_element_1)
	{
		global $last_location_report_data;
		//echo "in function<br>";
		global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
		global $old_xml_date;		
		//echo "xml_Date=".$old_xml_date."<br>";	
		//echo "<br>".$vehicle_serial.", ,".$vname." ,".$startdate.", ".$enddate.", ".$xmltowrite."<br>";	
		$fix_tmp = 1;
		$xml_date_latest="1900-00-00 00:00:00";
		$linetowrite="";
		$dataValid = 0;
		$date_1 = explode(" ",$startdate);
		$date_2 = explode(" ",$enddate);

		$datefrom = $date_1[0];
		$dateto = $date_2[0];
		$timefrom = $date_1[1];
		$timeto = $date_2[1];

		get_All_Dates($datefrom, $dateto, &$userdates);	
		//echo "3:datefrom=".$datefrom.' '."dateto=".$dateto.' '."userdates=".$userdates[0].'<BR>';
		date_default_timezone_set("Asia/Calcutta");
		$current_datetime = date("Y-m-d H:i:s");
		$current_date = date("Y-m-d");
		//print "<br>CurrentDate=".$current_date;
		$date_size = sizeof($userdates);
		//echo "4:date_size=".$date_size.'<BR>';
		
	//echo "serial=".$vehicle_serial."<br>";

		$last_location_string = get_maxspd_halt($vehicle_serial);
		//echo "vc=".$vc."<br>";
		//echo "last_location_string=".$last_location_string."<br>";
		$data = explode(',',$last_location_string);
		
		$day_max_speed = $data[0];
		$day_max_speed_time = $data[1];
		$last_halt_time = $data[2];
		
		
		for($i=($date_size-1);$i>=0;$i--)
		{		
			//if($userdates[$i] == $current_date)
			//{	
			$xml_current = "../../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			//echo "xml_current=".$xml_current."<br>";
			if (file_exists($xml_current))      
			{ 
				$xml_file = $xml_current;						
			}		
			else
			{
				$xml_file = "../../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
			}	
			
			//echo "xml_current=".$xml_file."<br>";
			if (file_exists($xml_file)) 
			{
				$t=time();			
				$xml_original_tmp = "../../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";	
				//echo "xmo_file=".$xml_original_tmp." xml_file=".$xml_file."<br>";
				copy($xml_file,$xml_original_tmp); 
				$fexist =1;
				$xml = fopen($xml_original_tmp, "r") or $fexist = 0;               
				$format = 2;  
				//echo "in if<br>";
				if (file_exists($xml_original_tmp)) 
				{
					set_master_variable($userdates[$i]);
					//echo "exists<br>";
					while(!feof($xml))          // WHILE LINE != NULL
					{								
						$DataValid = 0;
						$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE
						//echo"<textarea>".$line."</textarea>";
						//echo $line;
					//echo "date1=".$userdates[$i]."<br>";
					
						
						//echo "vc=".$vc."<br>";
						
						if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
						{
							$fix_tmp = 1;
						}
						else if(strpos($line,''.$vc.'="0"'))
						{
							$fix_tmp = 0;
						}					
						if((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match) ) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)))
						{ 
							//echo "in lat<br>";
							$lat_value = explode('=',$lat_match[0]);
							$lng_value = explode('=',$lng_match[0]);
							if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
							{
								$DataValid = 1;
							}
						}
						/*echo "datavalid=".$DataValid;
						echo " line=".$line[0];
						echo " fix_tmp=".$fix_tmp;
						echo " length=".strlen($line);
						echo " line1=".$line[strlen($line)-3];*/
						if(($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($DataValid == 1))
						{
							//echo "in lng<br>";
							$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
							$datetime_tmp1 = explode("=",$datetime_tmp[0]);
							$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
							$xml_date_current = $datetime;				
						}
						//echo "xml_date_1=".$xml_date_current."<br>";
						if($xml_date_current!=null)
						{
							//echo"xml_date_current=".$xml_date_current." startdate=".$startdate." enddate=".$enddate." xml_date_current=".$xml_date_current."<br>";
							if(($xml_date_current >= $startdate && $xml_date_current <= $enddate) && ($xml_date_current!="-"))
							{
								if(($xml_date_current>$xml_date_latest) && (($xml_date_current<=$enddate) && ($xml_date_current>=$startdate)))
								{
									$xml_date_latest = $xml_date_current;
									$line = substr($line, 0, -2);	
									$lat_value_1 = preg_replace('/"/', '', $lat_value[1]);
									$lng_value_1= preg_replace('/"/', '', $lng_value[1]);
									$status = preg_match('/'.$vf.'="[^" ]+/', $line, $speed_tmp);
									$speed_tmp1 = explode("=",$speed_tmp[0]);
									$speed_local = preg_replace('/"/', '', $speed_tmp1[1]);
									//echo "in if 111";
									if($userdates[$i]<$old_xml_date)
									{
										//echo "in if 111";
										/*$line=str_replace("marker","x",$line);
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
										$line=str_replace("cellname=","ab=",$line);*/
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
										//echo "in if<br>";
										$linetowrite=$vehicle_serial."@".$vname."@".$vtype."@".$vehicle_number."@".$datetime."@".$temperature."@".$day_max_speed."@".$day_max_speed_time."@".$last_halt_time."@".$lat_value_1."@".$lng_value_1."@".$speed_local;
										//$last_location_report_data[]=array("deviceImeiNo"=>$vehicle_serial,"vehicleName"=>$vname,"vehicleType"=>$vtype,"vehicleNumber"=>$vehicle_number,"datetime"=>$datetime,"temperature"=>$temperature,"dayMaxSpeed"=>$day_max_speed,"dayMaxSpeedTime"=>$day_max_speed_time,"lastHaltTime"=>$last_halt_time);
										//print_r($last_location_report_data);
										//$linetowrite = "\n".$line.' s="'.$day_max_speed.'" t="'.$day_max_speed_time.'" u="'.$last_halt_time.'" w="'.$vname.'" x="'.$vehicle_number.'" y="'.$vtype.'"/>';
									}
									else
									{
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
										//echo "in if<br>";
										$linetowrite=$vehicle_serial."@".$vname."@".$vtype."@".$vehicle_number."@".$datetime."@".$temperature."@".$day_max_speed."@".$day_max_speed_time."@".$last_halt_time."@".$lat_value_1."@".$lng_value_1."@".$speed_local;
										//print_r($last_location_report_data);
										//$linetowrite = "\n".$line.' s="'.$day_max_speed.'" t="'.$day_max_speed_time.'" u="'.$last_halt_time.'" v="'.$vehicle_serial.'" w="'.$vname.'" x="'.$vehicle_number.'" y="'.$vtype.'"/>';
									}
									//$linetowrite = "\n".$line.' vname="'.$vname.'" vnumber="'.$vehicle_number.'"  vtype="'.$vtype.'"/>';
									//$linetowrite = "\n".$line.' s="'.$day_max_speed.'" t="'.$day_max_speed_time.'" u="'.$last_halt_time.'" v="'.$vehicle_serial.'" w="'.$vname.'" x="'.$vehicle_number.'" y="'.$vtype.'"/>';
										
									//$linetowrite = "\n".$line.' day_max_speed="'.$day_max_speed.'" day_max_speed_time="'.$day_max_speed_time.'" last_halt_time="'.$last_halt_time.'" vname="'.$vname.'" vehicle_number="'.$vehicle_number.'" vtype="'.$vtype.'"/>';
								}
							}
						}
					} // while closed
				}  // if original_tmp exist closed        
				if(strlen($linetowrite)!=0)
				{
					//echo "linetowrite=".$linetowrite."<br>";
					$linetowrite=explode("@",$linetowrite);
					
					$last_location_report_data[]=array("deviceImeiNo"=>$linetowrite[0],"vehicleName"=>$linetowrite[1],"vehicleType"=>$linetowrite[2],"vehicleNumber"=>$linetowrite[3],"datetime"=>$linetowrite[4],"temperature"=>$linetowrite[5],"dayMaxSpeed"=>$linetowrite[6],"dayMaxSpeedTime"=>$linetowrite[7],"lastHaltTime"=>$linetowrite[8],"latitude"=>$linetowrite[9],"longitude"=>$linetowrite[10],"speed"=>$linetowrite[11]);
					fclose($xml);
					unlink($xml_original_tmp);
					break;
				}
				fclose($xml);
				unlink($xml_original_tmp);
			} 
		} // Date closed
	}
	echo json_encode($last_location_report_data); 
	
	function get_maxspd_halt($imei)
	{
		//echo "in function<br>";
		global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
		global $old_xml_date;
		new_xml_variables();		
		$xml_file = "../../../../xml_vts/xml_last/".$imei.".xml";
		//echo "xml_file=".$xml_file."<br>";
		//echo "a=".$vs." b=".$vt."<br>";
		$file = file_get_contents($xml_file);
		if(!strpos($file, "</t1>")) 
		{
			usleep(1000);
		}		
		 
		$t=time();
		$rno = rand();			
		$xml_original_tmp = "../../../../xml_tmp/original_xml/tmp_".$imei."_".$t."_".$rno.".xml";		
		copy($xml_file,$xml_original_tmp); 

		if(file_exists($xml_original_tmp))
		{
			//echo "<br>exist2";
			$fexist =1;
			$fp = fopen($xml_original_tmp, "r") or $fexist = 0;   
			$total_lines =0;
			$total_lines = count(file($xml_original_tmp));
			//echo "<br>total_lines=".$total_lines;
			$c =0;
			while(!feof($fp)) 
			{
				$line = fgets($fp);
				$c++;				

				if(strlen($line)>15)
				{					
					if((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{
						//echo "in if";
						/*$status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
						$xml_date = $datetime; */          

						$status = preg_match('/'.$vs.'="[^"]+/', $line, $day_max_speed_tmp);
						//print_r($last_halt_time_tmp);
						$day_max_speed_tmp1 = explode("=",$day_max_speed_tmp[0]);
						$day_max_speed = preg_replace('/"/', '', $day_max_speed_tmp1[1]);

						$status = preg_match('/'.$vt.'="[^"]+/', $line, $day_max_speed_time_tmp);
						$day_max_speed_time_tmp1 = explode("=",$day_max_speed_time_tmp[0]);
						$day_max_speed_time = preg_replace('/"/', '', $day_max_speed_time_tmp1[1]);

						$status = preg_match('/'.$vu.'="[^"]+/', $line, $last_halt_time_tmp);
						//echo "ddd=".$last_halt_time_tmp[0]."<br>";
						//print_r($last_halt_time_tmp);
						$last_halt_time_tmp1 = explode("=",$last_halt_time_tmp[0]);
						$last_halt_time = preg_replace('/"/', '', $last_halt_time_tmp1[1]);
						//echo "last_halt_time=".$last_halt_time."<br>";
					}																			
				}			
			}

			fclose($fp);
			unlink($xml_original_tmp);

			if($day_max_speed > 200)
			{
				$day_max_speed = "0";
			}

			$day_max_speed = round($day_max_speed,2);
			$day_max_speed = $day_max_speed." km/hr";
			//echo "day_max_speed=".$day_max_speed."<br>";
			$data_string = $day_max_speed.",".$day_max_speed_time.",".$last_halt_time;		
		}
		return $data_string;  
	}

?>

