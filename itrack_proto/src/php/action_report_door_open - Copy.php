<?php
	set_time_limit(300);
	include_once("main_vehicle_information_1.php");
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION["root"];
	include_once('common_xml_element.php');
	include_once("get_all_dates_between.php");
	$report = "door open";

	include_once("sort_xml.php");
	include_once("calculate_distance.php");
	include_once("report_title.php");
	include_once("read_filtered_xml.php");
	include_once("util.hr_min_sec.php");
	include_once("get_io.php");
	include_once("new_xml_string_io.php");

	$DEBUG = 0;

	$device_str = $_POST['vehicleserial'];
	//echo "<br>devicestr=".$device_str;
	$vserial = explode(':',$device_str);
	$vsize=count($vserial);

	$date1 = $_POST['start_date'];
	$date2 = $_POST['end_date']; 
	//echo "date1=".$date1." date2=".$date2."<br>";
	$date1 = str_replace("/","-",$date1);
	$date2 = str_replace("/","-",$date2);
	global $doorOpen2Flag;
	$doorOpen2Flag=0;

	if($vsize>0)
	{
		for($i=0;$i<$vsize;$i++)
		{		
			$vehicle_info=get_vehicle_info($root,$vserial[$i]);
			$vehicle_detail_local=explode(",",$vehicle_info);	
			$vname[$i] = $vehicle_detail_local[0];
		}  
		$current_dt = date("Y_m_d_H_i_s");	
		$xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";
		write_door_open_report_xml($vserial, $vname, $date1, $date2, $xmltowrite);
	}

	function write_door_open_report_xml($vserial, $vname, $startdate, $enddate, $xmltowrite)
	{
		$maxPoints = 1000;
		$file_exist = 0;

		$fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
		fwrite($fh, "<t1>");  
		fclose($fh);

		//$i=0;
		for($i=0;$i<sizeof($vserial);$i++)
		{  	
			//echo   "<br>vserial[i] =".$vserial[$i];
			get_door_open_xml_data($vserial[$i], $vname[$i], $startdate, $enddate, $xmltowrite);
			//echo   "t2".' '.$i;
		}  
	 
		$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
		fwrite($fh, "\n</t1>");  
		fclose($fh);
	}

function get_door_open_xml_data($vehicle_serial, $vname, $startdate, $enddate, $xmltowrite)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	global $DbConnection;
	$io = get_io($vehicle_serial,'door_open');	
	$ioDoorOpen = get_io($vehicle_serial,'door_open2');
	// echo "door_open1=".$io."<br>";
	// echo "ioDoorOpen=".$ioDoorOpen."<br>";
	
	global $doorOpen2Flag;
	
	$B2=0;
	$B1=0;
	//echo "io=".$io."<br>";
  //if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.15")
  //echo "<br>io=".$io;
 
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

	$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append
  
	$runhr_duration =0 ;
	$flag =0;

	$StartFlag=0;
	$StartFlag2=0;
	$continuous_running_flag =0;
	$continuous_running_flag2 =0;

	$previous_date = $startdate;
		
	for($i=0;$i<=($date_size-1);$i++)
	{
		//$xml_current = "/mnt/volume3/current_data/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
		$xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
		if(file_exists($xml_current))
		{			
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			//$xml_file = "/mnt/volume4/".$userdates[$i]."/".$vehicle_serial.".xml";
			$xml_file = "../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}		
		//echo "<br>xml in get_xml_data =".$xml_file;
		if (file_exists($xml_file)) 
		{			     
			//$current_datetime1 = date("Y_m_d_H_i_s");
			$t=time();
			$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
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
				$xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
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
			$format =2;
			$j=0;     
			if (file_exists($xml_original_tmp)) 
			{       
				$c = -1;
				set_master_variable($userdates[$i]);
				if($userdates[$i]>$old_xml_date)
				{
					//echo "in if";
					if($io=='io1') //////door
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
					if($ioDoorOpen=='io1')
					{
						$ioDoorOpen='i';
					}
					if($ioDoorOpen=='io2')
					{
						$ioDoorOpen='j';
					}
					if($ioDoorOpen=='io3')
					{
						$ioDoorOpen='k';
					}
					if($ioDoorOpen=='io4')
					{
						$ioDoorOpen='l';
					}
					if($ioDoorOpen=='io5')
					{
						$ioDoorOpen='m';
					}
					if($ioDoorOpen=='io6')
					{
						$ioDoorOpen='n';
					}
					if($ioDoorOpen=='io7')
					{
						$ioDoorOpen='o';
					}
					if($ioDoorOpen=='io8')
					{
						$ioDoorOpen='p';
					}
					//echo "io=".$io;
				}
				while(!feof($xml))            // WHILE LINE != NULL
				{
					$c++;
					$DataValid = 0;
					//echo fgets($file). "<br />";
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
					//echo "<br>Line".$line;
					if(strlen($line)>20)
					{
						$linetmp =  $line;
					}  				
					$linetolog =  $logcnt." ".$line;
					$logcnt++;				
					//fwrite($xmllog, $linetolog);  
					if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
					{
						$format = 1;
						$fix_tmp = 1;
					}                
					else if(strpos($line,''.$vc.'="0"'))
					{
						$format = 1;
						$fix_tmp = 0;
					}
					//if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")
					//echo "format=".$format;
					if( (preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
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
					//if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE
					// if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )     
					if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )
					{
						preg_match('/'.$vh.'="[^"]+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
						//echo "<br>str3tmp[0]=".$str3tmp[0];
						//$xml_date = $str3tmp[0];
						$str3tmp1 = explode("=",$str3tmp[0]);  
						$xml_date = preg_replace('/"/', '', $str3tmp1[1]);            					
					}				
					//echo "<br>xml_date=".$xml_date." datavalid=".$DataValid;
			  
					if($xml_date!=null)
					{				  
						//echo "<br>".$xml_date.",".$startdate.",".$enddate.",".$DataValid;
						//$lat = $lat_value[1] ;
								//$lng = $lng_value[1];				
						//if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
						if( ($xml_date > $previous_date && $xml_date <= $enddate) && ($xml_date!="-") )
						{							           	
							//echo "<br>One";                          
							/*$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
							//echo "status1=".$status.'<BR>';
							if($status==0)
							{
								continue;
							}*/
		  
							$status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
							//echo "<br>status2=".$status;
							/*if($status==0)
							{
								continue;               
							} */
							//echo "test6".'<BR>';
		  
							$status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
							//echo "<br>status3=".$status;
							/*if($status==0)
							{
								continue;
							}*/          
							//echo "<br>Format=".$format;	
							//$status = preg_match('/io1="[^" ]+/', $line, $enginecount_tmp);
							//$str = "'/$io=\"[^\"]+/'";			  
				
							$io=get_io_to_new_method($userdates[$i],$old_xml_date,$io);
							//echo "io=".$io."<br>";
							$status = preg_match('/'.$io.'=\"[^\"]+/', $line, $doorcount_tmp);
														
							/*if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")
							{echo "<br>io=".$io." ,status=". $status;
							}*/
							//$status = preg_match('/'.$io.'="[^"]+/', $line, $enginecount_tmp);                        
							/*if($j<10)
							{
								echo '<textarea>'.$line.'</textarea><br>';
							}*/
							$j++;
							//echo "<br>status4=".$status;
							if($status==0)
							{
							continue;
							}
				  
							//echo "<br>Engine test";
							$datetime = $xml_date;           

							/*$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);  
							$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]); */                                           
							$vserial=$vehicle_serial;
							//$vehiclename_tmp1 = explode("=",$vehiclename_tmp[0]);  
							//$vname = preg_replace('/"/', '', $vehiclename_tmp1[1]);                     
				  
							$doorcount_tmp1 = explode("=",$doorcount_tmp[0]);  
							$door_count = preg_replace('/"/', '', $doorcount_tmp1[1]);                                                                            	                         
							//echo "<br>enginecount=".$engine_count;

							//$date_secs2 = strtotime($time2);                         	
							//if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.15")
							//echo "<br>engine_count=". $engine_count." ,c=".$c." ,total_lines=".$total_lines;              
							//if($door_count>350)
							if($door_count>=20)
							{
								$continuous_running_flag = 1;
							}

							//if($door_count>350 && $StartFlag==0) 
							if($door_count>20 && $StartFlag==0)  
							{                						
								$time1 = $datetime;
								$StartFlag = 1;
							} 
							//else if( ($door_count<350 && $StartFlag==1) || ( ($c==($total_lines-1)) && ($continuous_running_flag ==1) ) )   //500
							else if( ($door_count<20 && $StartFlag==1) || ( ($c==($total_lines-1)) && ($continuous_running_flag ==1) ) )   //500
							{
								$StartFlag = 2;
							}
							//echo "door_count=".$door_count." StartFlag=".$StartFlag." continuous_running_flag=".$continuous_running_flag."<br>";
							$time2 = $datetime;
					
							if($StartFlag == 2)
							{
								$StartFlag=0;
								$runtime = strtotime($time2) - strtotime($time1);
								if($runtime > 60)
								{
									
									//echo "<br>runtimeA=".$runtime;
									//$runhr_duration = strtotime($runtime);
									/*$hr =  (int)(($runtime)/3600);	 
									//$runhr_duration = round($runhr_duration,2);
									$min =  ($runtime)%3600;
									$sec =  (int)(($min)%60);
									$min =  (int)(($min)/60); */
									echo "in if<br>";
									$doorOpenArrB1['imei'.$B1] =$vserial;
									$doorOpenArrB1['vname'.$B1] =$vname;
									$doorOpenArrB1['datefrom'.$B1] =$time1;
									$doorOpenArrB1['dateto'.$B1] =$time2;
									$doorOpenArrB1['door_open'.$B1] =$runtime;
									$doorOpenArrB1['door_open_type'.$B1] ="DO1";
									
									/*$door_open_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" door_open=\"".$runtime."\"/>";						          						
									//echo "<br>".$engine_runhr_data;
									$linetowrite = $door_open_data; // for distance       // ADD DISTANCE
									fwrite($fh, $linetowrite);*/
									$B1++;
								} 
							}
							if($ioDoorOpen!="")
							{
								
								$ioDoorOpen=get_io_to_new_method($userdates[$i],$old_xml_date,$ioDoorOpen);		
								//echo "ioDoorOpen=".$ioDoorOpen."<br>";
								$status = preg_match('/'.$ioDoorOpen.'=\"[^\"]+/', $line, $doorcount2_tmp);
															
								/*if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")
								{echo "<br>io=".$io." ,status=". $status;
								}*/
								//$status = preg_match('/'.$io.'="[^"]+/', $line, $enginecount_tmp);                        
								/*if($j<10)
								{
									echo '<textarea>'.$line.'</textarea><br>';
								}*/
								$j++;
								//echo "<br>status4=".$status;
								if($status==0)
								{
									continue;
								}
					  
								//echo "<br>Engine test";
								$datetime = $xml_date;           

								/*$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);  
								$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]); */                                           
								$vserial=$vehicle_serial;
								//$vehiclename_tmp1 = explode("=",$vehiclename_tmp[0]);  
								//$vname = preg_replace('/"/', '', $vehiclename_tmp1[1]);                     
								
								$doorcount_tmp2 = explode("=",$doorcount2_tmp[0]);  
								$door_count2 = preg_replace('/"/', '', $doorcount_tmp2[1]);                                                                            	                         
								//echo "<br>enginecount=".$engine_count;
								//echo "dooropen2Value=".$door_count2."<br>";
								//$date_secs2 = strtotime($time2);                         	
								//if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.15")
								//echo "<br>engine_count=". $engine_count." ,c=".$c." ,total_lines=".$total_lines;              
								//if($door_count>350)
								if($door_count2>=20)
								{
									$continuous_running_flag2 = 1;
								}

								//if($door_count>350 && $StartFlag==0) 
								if($door_count2>20 && $StartFlagB2==0)  
								{                						
									$timeB1 = $datetime;
									$StartFlagB2 = 1;
								}								
								//else if( ($door_count<350 && $StartFlag==1) || ( ($c==($total_lines-1)) && ($continuous_running_flag ==1) ) )   //500
								else if( ($door_count2<20 && $StartFlagB2==1) || ( ($c==($total_lines-1)) && ($continuous_running_flag2 ==1) ) )   //500
								{
									$StartFlagB2 = 2;
								}
								//echo "doocount=".$door_count2." StartFlagB2=".$StartFlagB2." continuous_running_flag2=".$continuous_running_flag2."<br>";
								$timeB2 = $datetime;
						
								if($StartFlagB2 == 2)
								{									
									$StartFlagB2=0;
									$runtimeB = strtotime($timeB2) - strtotime($timeB1);
									//echo "runtime=".$runtimeB."<br>";
									if($runtimeB > 60)
									{
										//echo "in runtime B<br>";
										$doorOpen2Flag=1;										
										//echo "<br>runtime=".$runtime;
										//$runhr_duration = strtotime($runtime);
										/*$hr =  (int)(($runtime)/3600);	 
										//$runhr_duration = round($runhr_duration,2);
										$min =  ($runtime)%3600;
										$sec =  (int)(($min)%60);
										$min =  (int)(($min)/60); */
										echo "in if1<br>";
										$doorOpenArrB2['imeib'.$B2] =$vserial;
										$doorOpenArrB2['vnameb'.$B2] =$vname;
										$doorOpenArrB2['datefromb'.$B2] =$timeB1;
										$doorOpenArrB2['datetob'.$B2] =$timeB2;
										$doorOpenArrB2['door_openb'.$B2] =$runtimeB;
										$doorOpenArrB2['door_open_typeb'.$B2] ="DO2";
										//print_r($doorOpenArrB2);
										$B2++;
										//print_r($doorOpenArrB2);
										/*$doorOpenArrB['imei'] = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" door_open=\"".$runtime."\" type=\"".$B."\"/>";						          						
										//echo "<br>".$engine_runhr_data;
										$linetowrite = $door_open_data; // for distance       // ADD DISTANCE
										fwrite($fh, $linetowrite);*/
									} 
								}
							}
						}  // $xml_date_current >= $startdate closed
					}   // if xml_date!null closed  			
				}   // while closed
			} // if original_tmp closed 
	  
			if($StartFlag == 1)
			{
				//echo "in StartFlag<br>";
				$StartFlag=0;
				$runtime = strtotime($time2) - strtotime($time1);
				//echo "<br>runtime=".$runtime;
				//$runhr_duration = strtotime($runtime);
				if($runtime > 60)
				{
					echo "in second,B1=".$B1."<br>";
					/*$hr =  (int)(($runtime)/3600);	 
					//$runhr_duration = round($runhr_duration,2);
					$min =  ($runtime)%3600;
					$sec =  (int)(($min)%60);
					$min =  (int)(($min)/60); */
					$doorOpenArrB1['imei'.$B1] =$vserial;
					$doorOpenArrB1['vname'.$B1] =$vname;
					$doorOpenArrB1['datefrom'.$B1] =$time1;
					$doorOpenArrB1['dateto'.$B1] =$time2;
					$doorOpenArrB1['door_open'.$B1] =$runtime;
					$doorOpenArrB1['door_open_type'.$B1] ="DO1";
					$B1++;
					/*
					//$engine_runhr_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" engine_runhr=\"".$hr.':'.$min.':'.$sec."\"/>";      		
					$door_open_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" door_open=\"".$runtime."\"/>";
					//echo "<br>".$engine_runhr_data;
					$linetowrite = $door_open_data; // for distance       // ADD DISTANCE
					fwrite($fh, $linetowrite);*/					
				}
			}
			if($ioDoorOpen!="")
			{
				
				if($StartFlagB2 == 1)
				{
					$StartFlagB2=0;			
					$runtimeB = strtotime($timeB2) - strtotime($timeB1);
					//echo "<br>runtime=".$runtime;
					//$runhr_duration = strtotime($runtime);
					if($runtimeB > 60)
					{
						//echo "in second 1<br>";
						//echo "in door Open2<br>";
						$doorOpen2Flag=1;
						/*$hr =  (int)(($runtime)/3600);	 
						//$runhr_duration = round($runhr_duration,2);
						$min =  ($runtime)%3600;
						$sec =  (int)(($min)%60);
						$min =  (int)(($min)/60); */
					
						$doorOpenArrB2['imeib'.$B2] =$vserial;
						$doorOpenArrB2['vnameb'.$B2] =$vname;
						$doorOpenArrB2['datefromb'.$B2] =$timeB1;
						$doorOpenArrB2['datetob'.$B2] =$timeB2;
						$doorOpenArrB2['door_openb'.$B2] =$runtimeB;
						$doorOpenArrB2['door_open_typeb'.$B2] ="DO2";
						//print_r($doorOpenArrB2);
						$B2++;

						/*//$engine_runhr_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" engine_runhr=\"".$hr.':'.$min.':'.$sec."\"/>";      		
						$door_open_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" door_open=\"".$runtime."\"/>";
						//echo "<br>".$engine_runhr_data;
						$linetowrite = $door_open_data; // for distance       // ADD DISTANCE
						fwrite($fh, $linetowrite);*/
						/*$door_open_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" door_open=\"".$runtime."\"/>";
						//echo "<br>".$engine_runhr_data;
						$linetowrite = $door_open_data; // for distance       // ADD DISTANCE
						fwrite($fh, $linetowrite);*/
					} 
				}
				
			}
			
		
			fclose($xml);            
			//unlink($xml_original_tmp);
		} // if (file_exists closed		
		$previous_date = $userdates[$i]." 23:59:59";
	}  // for closed
	//$doorOpen1Size=sizeof($doorOpenArrB1);
	//print_r($doorOpenArrB1);
	
	for($z=0;$z<$B1;$z++)
	{
		
		//echo "door_open_type=".$doorOpenArrB1['door_open_type'.$i]."<br>";
		$door_open_data = "\n<marker imei=\"".$doorOpenArrB1['imei'.$z]."\" vname=\"".$doorOpenArrB1['vname'.$z]."\" datefrom=\"".$doorOpenArrB1['datefrom'.$z]."\" dateto=\"".$doorOpenArrB1['dateto'.$z]."\" door_open=\"".$doorOpenArrB1['door_open'.$z]."\" door_open_type=\"".$doorOpenArrB1['door_open_type'.$z]."\"/>";
		//echo "<br>".$engine_runhr_data;
		echo"<textarea>".$door_open_data."</textarea>";
		$linetowrite = $door_open_data; // for distance       // ADD DISTANCE
		fwrite($fh, $linetowrite);
	}
	//echo "dooropen=".$ioDoorOpen."<br>";
	//print_r($doorOpenArrB2);
	if($ioDoorOpen!="")
	{
		//$doorOpen2Size=sizeof($doorOpenArrB2);				
		for($y=0;$y<$B2;$y++)
		{
			//echo "imei=".$doorOpenArrB2['imeib'.$i]."<br>";
			$door_open_data = "\n<marker imei=\"".$doorOpenArrB2['imeib'.$y]."\" vname=\"".$doorOpenArrB2['vnameb'.$y]."\" datefrom=\"".$doorOpenArrB2['datefromb'.$y]."\" dateto=\"".$doorOpenArrB2['datetob'.$y]."\" door_open=\"".$doorOpenArrB2['door_openb'.$y]."\" door_open_type=\"".$doorOpenArrB2['door_open_typeb'.$y]."\"/>";
			//echo "<textarea>".$door_open_data."</textarea>";
			//echo "<br>".$engine_runhr_data;
			$linetowrite = $door_open_data; // for distance       // ADD DISTANCE
			fwrite($fh, $linetowrite);
		}
	}
	//echo "Test1";	
	fclose($fh);
}

	
	$m1=date('M',mktime(0,0,0,$month,1));
	echo'<center>';   
	report_title("Door Open Report",$date1,$date2);   
	echo'<div style="overflow: auto;height: 285px;" align="center">';			                      
	$xml_path = $xmltowrite;		
	read_dooropen_xml($xml_path, &$imei, &$vname, &$datefrom, &$dateto, &$door_open,'DO1');
	$j=-1;
	$k=0;
	$single_data_flag=1;		
	$do1cnt=count($imei);
	//echo "\nimei_size=".sizeof($imei)." ,door_open=".$door_open;
	echo"<table width='90%' border=1>
			<tr>";
	if(count($imei)>0)
	{ 
		echo "<td>";
		for($i=0;$i<sizeof($imei);$i++)
		{	    						                  
			if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
			{
				$k=0;                                              
				$j++;
				$sum_door_open =0;
				/*$sum_engine_runmin =0;
				$sum_engine_runsec =0; */
				$total_door_open[$j] =0;

				$sno = 1;
				$title='Door Open Report : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
				$vname1[$j][$k] = $vname[$i];
				$imei1[$j][$k] = $imei[$i];

				echo'
				<br><table align="center">
				<tr>
				<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
				</tr>
				</table>
				<table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
				<tr>
				<td class="text" align="left"><b>SNo</b></td>
				<td class="text" align="left"><b>Start Time</b></td>
				<td class="text" align="left"><b>End Time</b></td>
				<td class="text" align="left"><b>Door Open (hr:min:sec)</b></td>								
				</tr>';  								
			}
			$sum_door_open = $sum_door_open + $door_open[$i];							  
			echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
			echo'<td class="text" align="left">'.$datefrom[$i].'</td>';		
			echo'<td class="text" align="left">'.$dateto[$i].'</td>';
			$hms1 = secondsToTime($door_open[$i]);
			$duration1 = $hms1[h].":".$hms1[m].":".$hms1[s];			
			echo'<td class="text" align="left">&nbsp;<font color="red"><b>Open</b>->['.$duration1.']</td>';					
			echo'</tr>';	          		
			
			$doorCloseStartDate=$dateto[$i];
			$nu=$i+1;
			//echo "i=".$nu."<br>";
			$doorCloseEndDate=$datefrom[$nu];
			if($doorCloseEndDate!="")
			{
				$closeDuration=strtotime($doorCloseEndDate)-strtotime($doorCloseStartDate);	
				$chms1 = secondsToTime($closeDuration);
				$cduration1 = $chms1[h].":".$chms1[m].":".$chms1[s];	
				$sum_door_close = $sum_door_close + $closeDuration;	
				echo "<tr>
						<td class='text' align='left' width='4%'><b>".$sno."</b></td>
						<td class='text'>".$doorCloseStartDate."</td>
						<td class='text'>".$doorCloseEndDate."</td>
						<td class='text'>&nbsp;<font color='green'><b>Close</b>&nbsp;->[".$cduration1."]</td>
					</tr>";
			}
			
			
			$datefrom1[$j][$k] = $datefrom[$i];	
			$dateto1[$j][$k] = $dateto[$i];										
			$door_open1[$j][$k] = $duration1;
			
			
			//echo "<br>engine_run=".$engine_runhr1[$j][$k]." ,i=".$i." ,j=".$j." ,k=".$k;
			//echo "<br>imei[i+1]=".$imei[$i+1]." ,ime[i]=".$imei[$i];     			    				  				

			if((($i>0) && ($imei[$i+1] != $imei[$i])))
			{       
				//echo "<br>IN";
				$single_data_flag = 0;
				echo'<tr style="height:20px;background-color:lightgrey">
						<td class="text">
							<strong>Total<strong>&nbsp;
						</td>
						<td class="text">
							<strong>'.$date1.'</strong>
						</td>	
						<td class="text">
							<strong>'.$date2.'</strong></td>';
							//if($k>1)
							{        
							  $hms_2 = secondsToTime($sum_door_open);                   
							  $total_door_open[$j] = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
							  $chms_2 = secondsToTime($sum_door_close);                   
							  $ctotal_door_open[$j] = $chms_2[h].":".$chms_2[m].":".$chms_2[s];
							  //echo "<br>total_engine_runhr[j]=".$total_engine_runhr[$j]; 
							}
					echo'<td class="text">
							<font color="red">
								<strong>Open->'.$total_door_open[$j].'</font>,<font color="green">close->'.$ctotal_door_open[$j].'</strong>
							</font>
						</td>
					</tr>
				</table>';        
				$no_of_data[$j] = $k;
			}
			$k++;   
			$sno++;                       							  		
		}
		if($single_data_flag)
		{
		echo '<tr style="height:20px;background-color:lightgrey">
				<td class="text">
					<strong>Total<strong>&nbsp;
				</td>
				<td class="text">
					<strong>'.$date1.'</strong>
				</td>	
				<td class="text">
					<strong>'.$date2.'</strong>
				</td>';	
					   
				$hms_2 = secondsToTime($sum_door_open);
				$total_door_open[$j] = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
					
					echo'<td class="text">
							<font color="red">
								<strong>'.$total_door_open[$j].'</strong>
							</font>
						</td>';
			echo'</tr>
			</table>'; 
			$no_of_data[$j] = $k; 
		}
	}
	$imei=null;
	$vname=null;
	$datefrom=null;
	$dateto=null;
	$door_open=null;
	$type=null;
	global $doorOpen2Flag;
	//echo "dooropen2=".$doorOpen2Flag."<br>";
	if($doorOpen2Flag==1)
	{
		read_dooropen_xml($xml_path, &$imei, &$vname, &$datefrom, &$dateto, &$door_open,'DO2');		
		if(count($imei)>0)
		{
			echo"<td>";
			$j2=-1;
			$k2=0;
			$single_data_flag2=1;
			//print_r($imei);			
			//echo "\nimei_size=".sizeof($imei)." ,door_open=".$door_open;  
			for($i=0;$i<sizeof($imei);$i++)
			{	
				//echo "imei1=".$imei[$i]."<br>";
				if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
				{
					$k2=0;                                              
					$j2++;
					$sum_door_open2 =0;
					/*$sum_engine_runmin =0;
					$sum_engine_runsec =0; */
					$total_door_open2[$j2] =0;

					$sno = 1;
					$title='Door Open Report 2 : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";;
					$vname1[$j2][$k2] = $vname[$i];
					$imei1[$j2][$k2] = $imei[$i];

					echo'<br>
						<table align="center">
							<tr>
								<td class="text" align="center">
									<b>'.$title.'</b> 
									<div style="height:8px;"></div>
								</td>
							</tr>
						</table>
						<table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
							<tr>
								<td class="text" align="left">
									<b>SNo</b>
								</td>
								<td class="text" align="left">
									<b>Start Time </b>
								</td>
								<td class="text" align="left">
									<b>End Time </b>
								</td>
								<td class="text" align="left">
									<b>Door Open (hr:min:sec)</b>
								</td>								
							</tr>';  								
				}
				$sum_door_open2 = $sum_door_open2 + $door_open[$i];							  
				echo'<tr>
						<td class="text" align="left" width="4%">
							<b>'.$sno.'</b>
						</td>
						<td class="text" align="left">
							'.$datefrom[$i].'
						</td>
						<td class="text" align="left">
							'.$dateto[$i].'
						</td>';
						$hms1 = secondsToTime($door_open[$i]);
						$duration1 = $hms1[h].":".$hms1[m].":".$hms1[s];			
					echo'<td class="text" align="left">
							'.$duration1.'
						</td>';					
				echo'</tr>'; 
				$datefrom2[$j2][$k2] = $datefrom[$i];	
				$dateto2[$j2][$k2] = $dateto[$i];										
				$door_open2[$j2][$k2] = $duration1;
				if((($i>0) && ($imei[$i+1] != $imei[$i])))
				{       
					//echo "<br>IN";
					$single_data_flag2 = 0;
					echo'<tr style="height:20px;background-color:lightgrey">
							<td class="text">
								<strong>Total<strong>&nbsp;
							</td>
							<td class="text">
								<strong>'.$date1.'</strong>
							</td>	
							<td class="text">
								<strong>'.$date2.'</strong>
							</td>';	
							$hms_2 = secondsToTime($sum_door_open2);                   
							$total_door_open2[$j2] = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
						echo'<td class="text">
								<font color="red">
									<strong>'.$total_door_open2[$j2].'</strong>
								</font>
							</td>';
					echo'</tr>'; 
				echo '</table>';        
					$no_of_data2[$j2] = $k2;
				}
				//echo "<br>OUT";
				$k2++;   
				$sno++;                       							  		
			}
			if($single_data_flag2)
			{
				echo'<tr style="height:20px;background-color:lightgrey">
						<td class="text">
							<strong>Total<strong>&nbsp;
						</td>
						<td class="text">
							<strong>'.$date1.'</strong>
						</td>	
						<td class="text">
							<strong>'.$date2.'</strong>
						</td>';	
						$hms_2 = secondsToTime($sum_door_open2);
						$total_door_open2[$j2] = $hms_2[h].":".$hms_2[m].":".$hms_2[s]; 
					echo'<td class="text">
							<font color="red">
								<strong>'.$total_door_open2[$j2].'</strong>
							</font>
						</td>';
				echo'</tr>
				</table>'; 
				$no_of_data2[$j2] = $k2; 
			}
			echo"</td>";																																										
		}
	}
	echo "</tr>
	</table>";
	echo"</div>";
	
	
	echo'<form method = "post" target="_blank">';	
				$csv_string = "";
				//echo "<br>j=".$j;	
				for($x=0;$x<=$j;$x++)
				{								
					$title = $vname1[$x][0]." (".$imei1[$x][0]."): Door Open Report- From DateTime : ".$date1."-".$date2;
					$csv_string = $csv_string.$title."\n";
					$csv_string = $csv_string."SNo,Start Time From,End Time,Door Open(hrs.min)\n";
					echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
					
					$sno=0;
					//echo "<br>noofdata=".$no_of_data[$x];
					
					for($y=0;$y<=$no_of_data[$x];$y++)
					{
						//$k=$j-1;
						$sno++;
								
						$datetmp1 = $datefrom1[$x][$y];	
						$datetmp2 = $dateto1[$x][$y];										
						$door_open_tmp = $door_open1[$x][$y];
						//echo "<br>x=".$x." ,y=".$y." ,temp_runhr=".$engine_runhr_tmp;
						
						//echo "dt=".$datetmp1;								
						echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][Start Time]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][End Time]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$door_open_tmp\" NAME=\"temp[$x][$y][Door Open (hr:min:sec)]\">";
				  
						$csv_string = $csv_string.$sno.','.$datetmp1.','.$datetmp2.','.$door_open_tmp."\n";         																	
					}		

					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Start Time]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][End Time]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Door Open (hr:min:sec)]\">";									
					
					$m = $y+1;								
					
					echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][Start Time]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][End Time]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$total_door_open[$x]\" NAME=\"temp[$x][$m][Door Open (hr:min:sec)]\">";
					$csv_string = $csv_string."\nTotal,".$date1.",".$date2.",".$total_door_open[$x]."\n\n";  
				}
				echo'<input TYPE="hidden" VALUE="door_open" NAME="csv_type">
					<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">
			</form>';
				
				
		if($doorOpen2Flag==1)
		{
		echo'<form method = "post" target="_blank">';	
				$csv_string1 = "";
				//echo "<br>j=".$j;	
				for($x=0;$x<=$j2;$x++)
				{								
					$title = $vname1[$x][0]." (".$imei1[$x][0]."): Door Open Report- From DateTime : ".$date1."-".$date2;
					$csv_string1 = $csv_string1.$title."\n";
					$csv_string1 = $csv_string1."SNo,Start Time From,End Time,Door Open(hrs.min)\n";
					echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
					
					$sno=0;
					//echo "<br>noofdata=".$no_of_data[$x];
					
					for($y=0;$y<=$no_of_data2[$x];$y++)
					{
						//$k=$j-1;
						$sno++;
								
						$datetmp1 = $datefrom2[$x][$y];	
						$datetmp2 = $dateto2[$x][$y];										
						$door_open_tmp = $door_open2[$x][$y];
						//echo "<br>x=".$x." ,y=".$y." ,temp_runhr=".$engine_runhr_tmp;
						
						//echo "dt=".$datetmp1;								
						echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][Start Time]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][End Time]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$door_open_tmp\" NAME=\"temp[$x][$y][Door Open (hr:min:sec)]\">";
				  
						$csv_string1 = $csv_string1.$sno.','.$datetmp1.','.$datetmp2.','.$door_open_tmp."\n";         																	
					}		

					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Start Time]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][End Time]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Door Open (hr:min:sec)]\">";									
					
					$m = $y+1;								
					
					echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][Start Time]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][End Time]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$total_door_open2[$x]\" NAME=\"temp[$x][$m][Door Open (hr:min:sec)]\">";
					$csv_string1 = $csv_string1."\nTotal,".$date1.",".$date2.",".$total_door_open2[$x]."\n\n";  
				}
				echo '<input TYPE="hidden" VALUE="door_open2" NAME="csv_type2">
					<input TYPE="hidden" VALUE="'.$csv_string1.'" NAME="csv_string2"> 
				</form>';
		}
		echo 
		'
		<table align="center">
			<tr>';
				
				if($do1cnt>0)
				{
				echo'<td>						                
						<input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">
					</td>
					<td>
					&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
					</td>';
				}
				if($doorOpen2Flag==1)
				{
				echo'<td>					               
					<input type="button" onclick="javascript:report_csv_2(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF 2" class="noprint">
				</td>
				<td>
				&nbsp;<input type="button" onclick="javascript:report_csv_2(\'src/php/report_csv_2.php\');" value="Get CSV 2" class="noprint">&nbsp;
				</td>';
				}
			echo'</tr>
			</table>';  
unlink($xml_path);				 
                     
?>							 					
