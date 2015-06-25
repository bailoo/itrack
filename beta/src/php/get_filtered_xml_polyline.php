<?php
	//set_time_limit(3000);
	include_once('main_vehicle_information_1.php');
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('user_type_setting.php');	
	include_once('calculate_distance.php');	
	include_once('common_xml_element.php');
	include_once('googleMapApiPolyline.php');
	$xmltowrite = $_REQUEST['xml_file']; 	
	$mode = $_REQUEST['mode'];
	$vserial1 = $_REQUEST['vserial'];
	$startdate = $_REQUEST['startdate'];
	$enddate = $_REQUEST['enddate']; 
	$time_interval1 = $_REQUEST['time_interval']; 
	
	// for google play//
	$flag_play = $_REQUEST['flag_play'];
	$play_interval = $_REQUEST['play_interval']; 
	//echo "Crd=".$crd_data."mode=".$mode."<br>";
	//echo "xmltowrite=".$vserial1."<br>";
	//echo "xmltowrite=".$xmltowrite."<br>";
		
	$vserial = explode(',',$vserial1) ;   
	if($report_type	== "Vehicle")
	{
		include_once("sort_xml.php");
	}
	else
	{
		include_once("sort_xml_person.php");
	}
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
	
	
		
		$vehicle_info=get_vehicle_info($root,$vserial[0]);
		$vehicle_detail_local=explode(",",$vehicle_info);
		if($home_report_type=="map_report" || $home_report_type=="play_report")
		{
			$lineTmpTrack="";
			if($report_type=="Vehicle")
			{
				$io_type_value=$vehicle_detail_local[7];
				getTrackMap($vserial[0], $vehicle_detail_local[0], $vehicle_detail_local[2], $startdate,$enddate,$xmltowrite,$timeinterval,$distanceinterval,&$lineTmpTrack);
					//echo "linetmp=".$linetmp."<br>";
					$lineF=explode("@",substr($lineTmpTrack,0,-1));					
					for($n=0;$n<sizeof($lineF);$n++)
					{
						preg_match('/d="[^" ]+/', $lineF[$n], $lat_tmp);
						$lat_tmp1 = explode("=",$lat_tmp[0]);
						$lat = substr(preg_replace('/"/', '', $lat_tmp1[1]),0,-1);
						//echo "lat=".$lat."<br>";
						$lat_arr_last[]=$lat;					

						preg_match('/e="[^" ]+/', $lineF[$n], $lng_tmp);
						$lng_tmp1 = explode("=",$lng_tmp[0]);
						$lng = substr(preg_replace('/"/', '', $lng_tmp1[1]),0,-1);
						//echo "lng=".$lng."<br>";
						$lng_arr_last[]=$lng;                    
						
						preg_match('/h="[^"]+/', $lineF[$n], $datetime_tmp);
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
						$datetime_arr_last[]=$datetime;
						// echo "datetime=".$datetime."<br>";

						preg_match('/v="[^"]+/', $lineF[$n], $vserial_tmp);
						$vserial_tmp1 = explode("=",$vserial_tmp[0]);
						$vehicle_serial = preg_replace('/"/', '', $vserial_tmp1[1]);
						$vserial_arr_last[]=$vehicle_serial;
						// echo "vehicle_name1=".$vehicle_serial."<br>";

						preg_match('/w="[^"]+/', $lineF[$n], $vname_tmp);
						$vname_tmp1 = explode("=",$vname_tmp[0]);
						$vehicle_name = preg_replace('/"/', '', $vname_tmp1[1]);
						$vehiclename_arr_last[]=$vehicle_name;
						// echo "vehicle_name=".$vehicle_name."<br>";

						preg_match('/x="[^"]+/', $lineF[$n], $vnumber_tmp);
						$vnumber_tmp1 = explode("=",$vnumber_tmp[0]);
						$vehicle_number = preg_replace('/"/', '', $vnumber_tmp1[1]);
						$vehiclenumber_arr_last[]=$vehicle_number;
						//echo "vehicle_number=".$vehicle_number."<br>";

						preg_match('/f="[^"]+/', $lineF[$n], $speed_tmp);
						$speed_tmp1 = explode("=",$speed_tmp[0]);
						$speed = preg_replace('/"/', '', $speed_tmp1[1]);                               
						if( ($speed<=3) || ($speed>200))
						{
							$speed = 0;
						}
						$speed_arr_last[]=$speed;
						//echo "speed=".$speed."<br>";
						preg_match('/i="[^"]+/', $lineF[$n], $io1_tmp);
						$io1_tmp1 = explode("=",$io1_tmp[0]);
						$io1= preg_replace('/"/', '', $io1_tmp1[1]);
						// echo "io1=".$io1."<br>";

						preg_match('/j="[^"]+/', $lineF[$n], $io2_tmp);
						$io2_tmp1 = explode("=",$io2_tmp[0]);
						$io2= preg_replace('/"/', '', $io2_tmp1[1]);
						// echo "io2=".$io2."<br>";

						preg_match('/k="[^"]+/', $lineF[$n], $io3_tmp);
						$io3_tmp1 = explode("=",$io3_tmp[0]);
						$io3= preg_replace('/"/', '', $io3_tmp1[1]);
						//echo "io3=".$io3."<br>";

						preg_match('/l="[^"]+/', $lineF[$n], $io4_tmp);
						$io4_tmp1 = explode("=",$io4_tmp[0]);
						$io4= preg_replace('/"/', '', $io4_tmp1[1]);
						//echo "io4=".$io4."<br>";

						preg_match('/m="[^"]+/', $lineF[$n], $io5_tmp);
						$io5_tmp1 = explode("=",$io5_tmp[0]);
						$io5= preg_replace('/"/', '', $io5_tmp1[1]);
						//echo "io5=".$io5."<br>";

						preg_match('/n="[^"]+/', $lineF[$n], $io6_tmp);
						$io6_tmp1 = explode("=",$io6_tmp[0]);
						$io6= preg_replace('/"/', '', $io6_tmp1[1]);
						//echo "io6=".$io6."<br>";

						preg_match('/o="[^"]+/', $lineF[$n], $io7_tmp);
						$io7_tmp1 = explode("=",$io7_tmp[0]);
						$io7= preg_replace('/"/', '', $io7_tmp1[1]);
						// echo "io7=".$io7."<br>";

						preg_match('/p="[^"]+/', $lineF[$n], $io8_tmp);
						$io8_tmp1 = explode("=",$io8_tmp[0]);
						$io8= preg_replace('/"/', '', $io8_tmp1[1]);
						// echo "io8=".$io8."<br>";

						preg_match('/s="[^"]+/', $lineF[$n], $day_max_speed_tmp);
						$day_max_speed_tmp1 = explode("=",$day_max_speed_tmp[0]);
						$day_max_speed= preg_replace('/"/', '', $day_max_speed_tmp1[1]);
						$day_max_speed_arr_last[]=$day_max_speed;
						// echo "day_max_speed=".$day_max_speed."<br>";

						/*preg_match('/t="[^"]+/', $lineF[$n], $day_max_speed_time_tmp);
						$day_max_speed_time_tmp1 = explode("=",$day_max_speed_time_tmp[0]);
						$day_max_speed_time= preg_replace('/"/', '', $day_max_speed_time_tmp1[1]);*/

						// echo "day_max_speed_time=".$day_max_speed_time."<br>";

						preg_match('/u="[^"]+/', $lineF[$n], $last_halt_time_tmp);
						$last_halt_time_tmp1 = explode("=",$last_halt_time_tmp[0]);
						$last_halt_time= preg_replace('/"/', '', $last_halt_time_tmp1[1]);
						$last_halt_time_arr_last[]=$last_halt_time;

						preg_match('/ y="[^"]+/', $lineF[$n], $vehilce_type_tmp);
						$vehilce_type_tmp1 = explode("=",$vehilce_type_tmp[0]);
						$vehilce_type= preg_replace('/"/', '', $vehilce_type_tmp1[1]);
						$vehilce_type_arr[]=$vehilce_type;
						
						preg_match('/z="[^"]+/', $lineF[$n], $distance_travel_tmp);
						$distance_travel_tmp1 = explode("=",$distance_travel_tmp[0]);
						$distance_travel= preg_replace('/"/', '', $distance_travel_tmp1[1]);
						$distance_travel_arr[]=$distance_travel;

						$io_str="";
						if($io_type_value!="tmp_str")
						{
							$iotype_iovalue_str=explode(":",$io_type_value);

							for($i=0;$i<sizeof($iotype_iovalue_str);$i++)
							{
									$iotype_iovalue_str1=explode("^",$iotype_iovalue_str[$i]);							
									if($iotype_iovalue_str1[0]=="1")
									{
										$io_values=$io1;
									}
									else if($iotype_iovalue_str1[0]=="2")
									{
										$io_values=$io2;
									}
									else if($iotype_iovalue_str1[0]=="3")
									{
										$io_values=$io3;
									}
									else if($iotype_iovalue_str1[0]=="4")
									{
										$io_values=$io4;
									}
									else if($iotype_iovalue_str1[0]=="5")
									{
										$io_values=$io5;
									}
									else if($iotype_iovalue_str1[0]=="6")
									{
										$io_values=$io6;
									}
									else if($iotype_iovalue_str1[0]=="7")
									{
										$io_values=$io7;
									}
									else if($iotype_iovalue_str1[0]=="8")
									{
										$io_values=$io8;
									}
									//echo "temperature=".$iotype_iovalue_str1[1]."<br>";
									if($iotype_iovalue_str1[1]=="temperature")
									{					
										if($io_values!="")
										{
											if($io_values>=-30 && $io_values<=70)
											{
												//echo "in if";
												$io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>".$io_values."</td></tr>";
											}
											else
											{
												//echo "in if 1";
												$io_str=$io_str."<tr><td class='live_td_css1'>Temperature</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>-</td></tr>";
											}
										}
										else
										{
											//echo "in if 2";
											$io_str=$io_str."<tr><td class='live_td_css1'>Temperature</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>-</td></tr>";
										}
									}
									else if($iotype_iovalue_str1[1]!="")
									{
										//echo "engine".$iotype_iovalue_str1[1]."<br>";
										if(trim($iotype_iovalue_str1[1])=="engine")
										{
											if($io_values<=350)
											{					
												$io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>Off</td></tr>";
											}
											else
											{
												$io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>:</td><td class='live_td_css2'>ON</td></tr>";
											}
										}
										else if(trim($iotype_iovalue_str1[1])=="ac")
										{
											if($io_values>500)
											{					
												$io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>Off</td></tr>";
											}
											else
											{
												$io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>:</td><td class='live_td_css2'>ON</td></tr>";
											}
										}
										else if($iotype_iovalue_str1[1]=="door_open")
										{
											//if($io_values<=350)
											if($io_values<250)
											{					
												$io_str=$io_str."<tr><td class='live_td_css1'>Delivery Door</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>Close</td></tr>";
											}
											else
											{
												$io_str=$io_str."<tr><td class='live_td_css1'>Delivery Door</td><td>:</td><td class='live_td_css2'>Open</td></tr>";
											}
										}
										else if($iotype_iovalue_str1[1]=="door_open2")
										{
											//if($io_values<=350)
											if($io_values<250)
											{					
												$io_str=$io_str."<tr><td class='live_td_css1'>Manhole Door</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>Close</td></tr>";
											}
											else
											{
												$io_str=$io_str."<tr><td class='live_td_css1'>Manhole Door</td><td>:</td><td class='live_td_css2'>Open</td></tr>";
											}
										}
										else if($iotype_iovalue_str1[1]=="door_open3")
										{
											//if($io_values<=350)
											if($io_values<250)
											{					
												$io_str=$io_str."<tr><td class='live_td_css1'>Manhole Door2</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>Close</td></tr>";
											}
											else
											{
												$io_str=$io_str."<tr><td class='live_td_css1'>Manhole Door2</td><td>:</td><td class='live_td_css2'>Open</td></tr>";
											}
										}
										else if($iotype_iovalue_str1[1]=="fuel_lead")
										{
											if($io_values<=350)
											{					
												$io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>Close</td></tr>";
											}
											else
											{
												$io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>:</td><td class='live_td_css2'>Open</td></tr>";
											}
										}
										else
										{
											if($io_values!="")
											{					
												$io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>".$io_values."</td></tr>";
											}
											else
											{
												$io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>-</td></tr>";
											}
										}
									}			
							}
						}
						$io_str_last[]=$io_str;                                 
					}
					//print_r($io_str_last);
					//print_r($lng_arr_last);
					//print_r($io_str_last);
					
					
					$googleMapthisapi=new GoogleMapHelper();								
					echo $googleMapthisapi->addMultipleMarker("map_div",$lat_arr_last,$lng_arr_last,$datetime_arr_last,$vserial_arr_last,$vehiclename_arr_last,$speed_arr_last,$vehiclenumber_arr_last,$io_str_last,$distance_travel_arr);
					
			
			}
		}
	
	function getTrackMap($vehicle_serial,$vname,$vehicle_number,$startdate,$enddate,$xmltowrite,$timeinterval,$distanceinterval,&$lineTmpTrack)
	{
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
		$LastDTForDiff = "";
		$firstData = 0;
		$distance =0.0;
		$linetowrite="";
		$date_1 = explode(" ",$startdate);
		$date_2 = explode(" ",$enddate);
		$date_time_cmp=$date_2[0]." 23:59:59";
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
			
			$xml_current ="../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
		include("/var/www/html/vts/beta/src/php/common_xml_path.php");
		$xml_current =$xml_data."/".$userdates[$i]."/".$vehicle_serial.".xml";
		//echo"ghanta". $xml_current;
			//$xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	    		
			if (file_exists($xml_current))      
			{    		
				echo "in else";
				$xml_file = $xml_current;
				$CurrentFile = 1;
			}		
			else
			{
				$xml_file = "../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
				$CurrentFile = 0;
			}
			//echo "<br>xml_file=".$xml_file;			
			if (file_exists($xml_file)) 
			{	
				set_master_variable($userdates[$i]);
				$t=time();
				//$current_datetime1 = date("Y_m_d_H_i_s");      
				//$xml_original_tmp = "xml_tmp/original_xml/tmp_".$current_datetime1.".xml";
				$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$t."_".$i.".xml";
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
					$xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$t."_".$i."_unsorted.xml";
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
					while(!feof($xml))          // WHILE LINE != NULL
					{
						//echo fgets($file). "<br />";
						$DataValid = 0;
						$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE
						//echo "0:line:".$line;					
						$linetolog =  $logcnt." ".$line;
						$logcnt++;
						//fwrite($xmllog, $linetolog);
						
						//echo "vc:".$vc;
						/*if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
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
						}*/
						$fix_tmp = 1;				
						if((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
						{ 
							$lat_value = explode('=',$lat_match[0]);
							$lng_value = explode('=',$lng_match[0]);
							//echo " lat_value=".$lat_value[1];         
							if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") && (strrpos($lat_value[1], '.'))==3 && (strrpos($lng_value[1], '.'))==3)
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
						if($DataValid==1)
						{
							$break_flag=1;
							if(strtotime($datetime)>strtotime($date_time_cmp))
							{
								$break_flag=0;
							}						
							if($break_flag==1)
							{
								if($xml_date_current>=$enddate)
								{
									break;
								}
							}
						}
						//echo "Final0=".$xml_date_current." datavalid=".$DataValid;
			  
						if (($xml_date_current!=null)  && ($DataValid==1) && (($fix_tmp==1) || ($fix_tmp == 2)))
						{
							$linetolog = $xml_date_current.' '.$firstData."\n";
							//fwrite($xmllog, $linetolog);
							//echo "Final1";
							$CurrentLat = $lat_value[1] ;
							$CurrentLong = $lng_value[1];

							//if(($xml_date_current >= $startdate && $xml_date_current <= $enddate) && ($xml_date_current!="-"))
							//if(($xml_date_current >= $startdate && $xml_date_current <= $enddate) && ($xml_date_current!="-") && $xml_date_current >= $xml_date_latest && $xml_date_current<=($userdates[$i]." 23:59:59"))
							if(($xml_date_current >= $startdate && $xml_date_current <= $enddate) && ((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval) && ($xml_date_current!="-") && $xml_date_current >= $xml_date_latest && $xml_date_current<=($userdates[$i]." 23:59:59"))
							{								
								$xml_date_latest = $xml_date_current;
								//echo "Final2";
								$CurrentLat = $lat_value[1] ;
								$CurrentLong = $lng_value[1];
								$CurrentDTForDiffTmp=strtotime($datetime);
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
									//echo "lastDate=".$LastDTForDif."<br>";
									$LastDTForDiffTS=strtotime($LastDTForDif);									
									$dateDifference=($CurrentDTForDiffTmp-$LastDTForDiffTS)/3600;
									$dateDifference_1=round($dateDifference,5);
									//echo" dateDifference=".round($dateDifference,5)."<br>";
									//echo  "dateDifference: ".$dateDifference.'<BR>'; 									
									calculate_distance($tmp1lat,$tmp2lat,$tmp1lng,$tmp2lng,&$distance);
										
									//$linetolog = $CurrentLat.','.$CurrentLong.','.$LastLat.','.$LastLong.','.$distance.','.$xml_date_current.','.$xml_date_last.','.(strtotime($xml_date_current)-strtotime($xml_date_last)).','.$timeinterval.','.$distanceinterval.','.$enddate.','.$startdate."\n";
									//echo "distance=".$distance."<br>";									
									$overSpeed=$distance/$dateDifference_1;
									
									//fwrite($xmllog, $linetolog);
								}

								if($distance<$distanceinterval)
								{
									$LastDTForDif=$xml_date_current;
								}
								/*if((((((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval) && ($distance>=$distanceinterval)) || ($firstData==0)) && 
								(($xml_date_current<=$enddate) && ($xml_date_current>=$startdate))) || ($f==$total_lines-2) )*/
								if(($distance>=$distanceinterval) || ($firstData==0))
								{
									
									//fwrite($xmllog, $linetolog);
									//echo "<br>FinalWrite";
									$xml_date_last = $xml_date_current;
									
									if($overSpeed<200)
									{										
										$LastLat =$CurrentLat;
										$LastLong =$CurrentLong;									
										$linetolog = "Data Written\n";
										$LastDTForDif=$xml_date_current;
										$line = substr($line, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
										$finalDistance = $finalDistance + $distance;
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
											$linetowrite = "\n".$line.' w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
										}
										else
										{
											$linetowrite = "\n".$line.' v="'.$vehicle_serial.'" w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
										}
										//echo "<br>finalDistance=".$finalDistance;
										//$linetowrite = "\n".$line.' cumdist="'.$finalDistance.'"./>'; // for distance       // ADD DISTANCE
										//$linetowrite = "\n".$line.'/>';
										//echo "<textarea>".$linetowrite."</textarea>";
										//echo "lintowrite=".$linetowrite;
										$firstData = 1;  
										$lineTmpTrack=$lineTmpTrack.$linetowrite."@";
										//$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
										//fwrite($fh, $linetowrite); 
									}
								}
							}
							if((strlen($line)>20) && ($xml_date_current >= $startdate && $xml_date_current <= $enddate))
							{
								$linelast =  $line;
							}
							
						}
						$f++;
					}   // while closed
				} // if original_tmp exist closed 
				fclose($xml);            
				unlink($xml_original_tmp);
			} // if (file_exists closed
		}  // for closed
		$linelast=substr($linelast,0,-3);
		if($userdates[$i-1]<$old_xml_date)
		{
			//echo "Test2";
			//echo "in replace 1";
			$linelast=str_replace("marker","x",$linelast);
			$linelast=str_replace("msgtype=","a=",$linelast);
			$linelast=str_replace("vehicleserial=","v=",$linelast);
			$linelast=str_replace("ver=","b=",$linelast);
			$linelast=str_replace("fix=","c=",$linelast);
			$linelast=str_replace("lat=","d=",$linelast);
			$linelast=str_replace("lng=","e=",$linelast);
			$linelast=str_replace("speed=","f=",$linelast);
			$linelast=str_replace("sts=","g=",$linelast);
			$linelast=str_replace("datetime=","h=",$linelast);
			$linelast=str_replace("io1=","i=",$linelast);
			$linelast=str_replace("io2=","j=",$linelast);
			$linelast=str_replace("io3=","k=",$linelast);
			$linelast=str_replace("io4=","l=",$linelast);
			$linelast=str_replace("io5=","m=",$linelast);
			$linelast=str_replace("io6=","n=",$linelast);
			$linelast=str_replace("io7=","o=",$linelast);
			$linelast=str_replace("io8=","p=",$linelast);
			$linelast=str_replace("sig_str=","q=",$linelast);
			$linelast=str_replace("sup_v=","r=",$linelast);
			$linelast=str_replace("day_max_speed=","s=",$linelast);
			$linelast=str_replace("day_max_speed_time=","t=",$linelast);
			$linelast=str_replace("last_halt_time=","u=",$linelast);
			$linelast=str_replace("cellname=","ab=",$linelast);
			$linetowrite = "\n".$linelast.' w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
		}
		else
		{
			//echo "Test3";
			$linetowrite = "\n".$linelast.' v="'.$vehicle_serial.'" w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
		}	
		$lineTmpTrack=$lineTmpTrack.$linetowrite."@";		
	}

	function get_All_Dates($fromDate, $toDate, &$userdates)
	{
		$dateMonthYearArr = array();
		$fromDateTS = strtotime($fromDate);
		$toDateTS = strtotime($toDate);

		for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24)) 
		{
			// use date() and $currentDateTS to format the dates in between
			$currentDateStr = date("Y-m-d",$currentDateTS);
			$dateMonthYearArr[] = $currentDateStr;
			//print $currentDateStr.”<br />”;
		}
		$userdates = $dateMonthYearArr;
	}


?>
