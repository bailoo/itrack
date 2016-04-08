<?php	  
  $DEBUG =0;
  
  $root_dir = getcwd();
  
  //$dist_path = $root_dir."/calculate_distance.php";
  //include_once($dist_path);
  include_once("calculate_distance.php");
    
  //$xmltowrite = $_REQUEST['xml_file']; 
	$mode = $_REQUEST['mode'];
	$vserial1 = $_REQUEST['vserial'];
	if($DEBUG) echo "<br>vserial=".$vserial1;
	
	$startdate = $_REQUEST['start_date'];
	$enddate = $_REQUEST['end_date'];
	
	//////////// start for popup windwo///////////////
	echo'<input type="hidden" id="date1" name="StartDate" value="'.$startdate.'" >';
	echo'<input type="hidden" id="date2" name="StartDate" value="'.$enddate.'" >';
	///////////// end for popup window////////////////
	
  $startdate = str_replace('/','-',$startdate);
  $enddate = str_replace('/','-',$enddate);  
	//$time_interval1 = $_REQUEST['time_interval'];  	
	$vserial2 = explode(':',$vserial1) ;
  //echo "<br>rootdir=".$root_dir.'<br>xmltowrite='.$xmltowrite;
	
  set_time_limit(300);	
	
  for($i=0;$i<sizeof($vserial2);$i++)
  {
    $query = "SELECT vehicle_name FROM vehicle WHERE vehicle_id IN (SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$vserial2[$i]' AND status=1) AND status=1";
    if($DEBUG) echo "<br>".$query."<br>";
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $vname[$i] = $row->vehicle_name;
  }		

  //$path_sorted = $root_dir."/sort_xml.php";
  //include_once($path_sorted);  
  include_once("sort_xml.php");	  	
	if($DEBUG) echo   "<br>".$xmltowrite.' '.$mode.' '.$vserial1.' '.$startdate.' '.$enddate.' '.$time_interval1."<br>";
	  
  $minlat = 180; 
  $maxlat = -180;
  $minlong = 180;
  $maxlong = -180; 
	
	$maxPoints = 10000;
	$file_exist = 0;
	
	/*$tmptimeinterval = strtotime($enddate) - strtotime($startdate);
	
	//echo  "1".$time_interval1.'<BR>';
  
  if($time_interval1=="auto")
	{
	   $timeinterval =   ($tmptimeinterval/$maxPoints);
     $distanceinterval = 0.1; 
   //echo  "2".$timeinterval.' '.$distanceinterval.'<BR>';
  }
  else
  {
    if($tmptimeinterval>86400)
    {
      $timeinterval =   ($tmptimeinterval/$maxPoints);
      $distanceinterval = 0.3;
    }
    else
    {
      $timeinterval =   $time_interval1;
      $distanceinterval = 0.02;
    }
  } */
  
  //echo "<br>distint=".$distanceinterval." ,time_interval=".$time_interval;
  $timeinterval =   5;
  $distanceinterval = 0.02;

  //if($mode==2)
  //{
  	//echo "<br>xmltowrite=:".$xmltowrite;
    $fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
  	fwrite($fh, "<t1>");  
  	fclose($fh);

  	//$i=0;
	global $final_vs_arr;
	$final_vs_arr=array();
	global $final_engine_runhr_arr;
	$engine_runhr_arr=array();
	global $final_speed_arr;
	$final_speed_arr=array();
	global $final_distance_arr;
	$final_distance_arr=array();
  
  for($i=0;$i<sizeof($vserial2);$i++)
  	{  	
		//echo   "<br>t1".' '.$i;
		global $trip_vehicle_status;
		global $sum_engine_runhr;
		global $final_maxspeed_tmp;
		global $trip_total_dist;
		$trip_vehicle_status="";
		$sum_engine_runhr=0;
		$final_maxspeed_tmp=0;
		$trip_total_dist=0.0;
		
		getTrack($vserial2[$i],$vname[$i],$startdate,$enddate,$xmltowrite,$timeinterval,$distanceinterval);
		$final_vs_arr[$vserial2[$i]]=$trip_vehicle_status;
		$final_speed_arr[$vserial2[$i]]=$final_maxspeed_tmp;
		$final_distance_arr[$vserial2[$i]]=$trip_total_dist;
		$hms_2 = secondsToTime($sum_engine_runhr); 
		$engine_runhr_arr[$vserial2[$i]]=$hms_2[h].":".$hms_2[m].":".$hms_2[s];
		
      //echo   "t2".' '.$i;
  	}    	
  	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
  	fwrite($fh, "\n</t1>");  
  	fclose($fh);
  //}


function get_All_Dates($fromDate, $toDate, &$userdates)
{
	$dateMonthYearArr = array();
	$fromDateTS = strtotime($fromDate);
	$toDateTS = strtotime($toDate);

	for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24)) {
		// use date() and $currentDateTS to format the dates in between
		$currentDateStr = date("Y-m-d",$currentDateTS);
		$dateMonthYearArr[] = $currentDateStr;
	//print $currentDateStr.”<br />”;
	}

	$userdates = $dateMonthYearArr;
}


function getTrack($vehicle_serial,$vname,$startdate,$enddate,$xmltowrite,$timeinterval,$distanceinterval)
{
	//echo "In Track";
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	global $sum_engine_runhr;
	global $final_maxspeed_tmp;	
	global $trip_total_dist;
	global $trip_vehicle_status;
	//date_default_timezone_set('Asia/Calcutta');
	$current_time = date('Y-m-d H:i:s');
	$current_date_this = date('Y-m-d');

	$xml_file_live = "../../../xml_vts/xml_last/".$vehicle_serial.".xml";
	$file = file_get_contents($xml_file_live);
	if(!strpos($file, "</t1>")) 
	{
		usleep(1000);
	} 
	$t=time();
	$rno = rand();			
	$xml_original_tmp_live = "../../../xml_tmp/original_xml/tmp_".$imei."_".$t."_".$rno.".xml";		
	copy($xml_file_live,$xml_original_tmp_live); 
	    
	if (file_exists($xml_original_tmp_live))
	{
		//echo "<br>exist2";
		$fexist =1;
		$fp = fopen($xml_original_tmp_live, "r") or $fexist = 0;   
		$total_lines =0;
		$total_lines = count(file($xml_original_tmp_live));
		//echo "<br>total_lines=".$total_lines;
		$c =0;
		set_master_variable($current_date_this);
		while(!feof($fp)) 
		{
			$line = fgets($fp);
			//echo "line=".$line;
			$c++;				
			//echo"vd=".$vd;
			if(strlen($line)>15)
			{
				//echo "in if";
				if ((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
				{
				//	echo "in if 1";
					$status = preg_match('/'.$vf.'="[^"]+/', $line, $speed_tmp);
					$speed_tmp1 = explode("=",$speed_tmp[0]);
					$speed = preg_replace('/"/', '', $speed_tmp1[1]);
					
					$status = preg_match('/'.$vu.'="[^"]+/', $line, $last_halt_time_tmp);
					$last_halt_time_tmp1 = explode("=",$last_halt_time_tmp[0]);
					$last_halt_time = preg_replace('/"/', '', $last_halt_time_tmp1[1]);                                                                 
																										 
					$xml_date_sec = strtotime($xml_date);
					$last_halt_time_sec = strtotime($last_halt_time);			
					$current_time_sec = strtotime($current_time);
					
					//////////////////////////////////////////
					//$diff = ($current_time_sec - $xml_date_sec);   // IN SECONDS
					$diff = ($current_time_sec - $last_halt_time_sec); 
					//echo "speed=".$speed."diff=".$diff."<br>";
				   
					if($speed>=5 && $diff <=600)
					{
					  $trip_vehicle_status = "Running";
					  //echo   "trip_vehicle_status=".$trip_vehicle_status."<br>";
					  //echo "<br>Running";
					}
					else
					{
					  $trip_vehicle_status = "Stopped";
					   // echo   "trip_vehicle_status=".$trip_vehicle_status."<br>";
					}              									
				}																			
			}			
		}									
	}

	$dtotal_dist=0.0;
	$dfirstdata_flag=0;
	$continuous_running_flag =0;
	$io = get_io($vehicle_serial,'engine');
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
	$StartFlag=0;
	$datefrom = $date_1[0];
	$dateto = $date_2[0];
	$timefrom = $date_1[1];
	$timeto = $date_2[1];
	
	$firstdata_flag=0;

	$j = 0;
	get_All_Dates($datefrom, $dateto, &$userdates);
	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);

	$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append

	for($i=0;$i<=($date_size-1);$i++)
	{
		//if($userdates[$i] == $current_date)
		//{	
		$xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
    		
		if(file_exists($xml_current))      
		{    		
			//echo "in else";
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
      
			if(file_exists($xml_original_tmp)) 
			{
				/////////// for speed ////////////
				 $speed_threshold = 1;
				$start_runflag = 0;
				$stop_runflag = 1;
				$total_speed = 0.0;
				$r1 =0;
				$r2 =0;
				$StopTimeCnt = $xml_date;
				$StopStartFlag = 0;
				
				$runtime_start = array();
				$runtime_stop = array();
				/////////////////////////////////////////
				set_master_variable($userdates[$i]);
				if($userdates[$i]>$old_xml_date)
				{
					//echo "in if";
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
					//echo "io=".$io;
				}
				//echo "io=".$io."<br>";
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
					if(($xml_date_current!=null)  && ($DataValid==1) && (($fix_tmp==1) || ($fix_tmp == 2)))
					{
						$linetolog = $xml_date_current.' '.$firstData."\n";
						//fwrite($xmllog, $linetolog);
						//echo "Final1";
						$CurrentLat = $lat_value[1] ;
						$CurrentLong = $lng_value[1];  					
						//if( ($xml_date_current >= $startdate && $xml_date_current <= $enddate) && ($xml_date_current!="-"))
						if(($xml_date_current >= $startdate && $xml_date_current <= $enddate) && ((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval) && ($xml_date_current!="-") && $xml_date_current >= $xml_date_latest && $xml_date_current<=($userdates[$i]." 23:59:59"))
						{
							///////////////////////							
							$xml_date_latest = $xml_date_current;							
							$vserial=$vehicle_serial;
					
							preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match);
							$lat_un = explode('=',$lat_match[0]);
							$lat=$lat_un[1];
							$lat = preg_replace('/"/', '', $lat);  
							$dlat=$lat;
				
							preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match);
							$lng_un = explode('=',$lng_match[0]);
							$lng=$lng_un[1];
							$lng = preg_replace('/"/', '', $lng); 
							$dlng=$lng;					
							
							///////////////// speed ////////////////
							preg_match('/'.$vf.'="[^"]+"/', $line, $speed_match);   
							$speed_un = explode('=',$speed_match[0]);	
							$speed=$speed_un[1];
							$speed = preg_replace('/"/', '', $speed);							
                                       
							//echo "<br>first=".$firstdata_flag;                                        
							if($firstdata_flag==0)
							{
								//echo "<br>FirstData";
								$firstdata_flag = 1;
						
								$lat1 = $lat;
								$lng1 = $lng;                
						
								///////// FIXING SPEED PROBLEM ///////////            
								$speed_str = $speed;
								if($speed_str > 200)
									$speed_str =0;                
						
								$speed_tmp = "";
								for ($x = 0, $y = strlen($speed_str); $x < $y; $x++) 
								{
									if($speed_str[$x]>='0' && $speed_str[$x]<='9')
									{
										$speed_tmp = $speed_tmp.$speed_str[$x];
									}      
									else
									{
										$speed_tmp = $speed_tmp.".";
									}  
								}
								$speed = $speed_tmp;  
								$speed = round($speed,2);  
								//echo "speed=".$speed_tmp;    
								///////////////////////////////////////////                     
					
								$speed_arr[$j] = $speed;	 
					
								$time1 = $datetime;
								$date_secs1 = strtotime($time1);
								//echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
								$sinterval = (double)1*60*60;
								$date_secs1 = (double)($date_secs1 + $sinterval);  							
								//echo "<br>DateSec1 after=".$date_secs1;	      
						
								if( ($speed > $speed_threshold) && ($start_runflag==0) )   // START
								{
									//echo "<br>start condition1";
									$runtime_start[$r1] = $xml_date_current;
									$r1++;
									$start_runflag = 1;
									$stop_runflag = 0; 
									$StopStartFlag = 0;
								}                                  	
							} 
							//echo "<br>k2=".$k2."<br>";
							else
							{                           
								///////// FIXING SPEED PROBLEM ///////////            
								$speed_str = $speed;
								if($speed_str > 200)
									$speed_str =0;
									  
								$speed_tmp = "";
								for ($x = 0, $y = strlen($speed_str); $x < $y; $x++) 
								{
									if($speed_str[$x]>='0' && $speed_str[$x]<='9')
									{
										$speed_tmp = $speed_tmp.$speed_str[$x];
									}      
									else
									{
										$speed_tmp = $speed_tmp.".";
									}  
								}
								$speed = $speed_tmp;  
								$speed = round($speed,2);                                                                        
								$speed_arr[$j] = $speed;   
								///////////////////////////////////////////   
													
								$time2 = $datetime;											
								$date_secs2 = strtotime($time2);
					
								//echo "<br>speed=".$speed." ,time=".$time2;	

								$lat2 = $lat;
								$lng2 = $lng;
					
								calculate_distance($lat1, $lat2, $lng1, $lng2, &$distance);
					
								//if($distance>0.25)
								if($distance>0.1)
								{	                                     													
									$total_dist = (float) ($total_dist + $distance);	
									//echo "<br>dist greater than 0.025: dist=".$total_dist." time2=".$time2;
									$lat1 = $lat2;
									$lng1 = $lng2;
								
									//////// TMP VARIABLES TO CALCULATE LAST XML RECORD  //////
									$vname_tmp  = $vname;
									$vserial_tmp = $vserial;
									$time1_tmp = $time1;
									$time2_tmp = $time2;
									$total_dist_tmp = $total_dist;    			
									////// TMP CLOSED	////////////////////////////////////////		    						
								}
									
								//echo "<br>Else-speed=".$speed." ,start_runflag=".$start_runflag." ,stop_runflag=".$stop_runflag;                   
								if(($speed < $speed_threshold) && ($stop_runflag ==0))   // STOP 
								{
									if(((strtotime($xml_date_current) - strtotime($StopTimeCnt))>15) && ($StopStartFlag==1))
									{
										//echo ", stop<br>";
										$runtime_stop[$r2] = $xml_date_current;
										$r2++;
										$stop_runflag = 1;
										$start_runflag = 0;
									}
									else if($StopStartFlag==0)
									{
										$StopTimeCnt = $xml_date_current;
										$StopStartFlag = 1;
									}
								}
										  
								if($speed > $speed_threshold && ($start_runflag ==0) && ($distance>0.1)  )    // START
								{
									//echo "<br>start";
									$runtime_start[$r1] = $xml_date_current;
									$r1++;
									$start_runflag =1;
									$stop_runflag = 0;
									$StopStartFlag = 0;
								}                                  
																									
								if($date_secs2 >= $date_secs1)
								{
									//echo "<br>In SpeedAction";
									/////////
									if(sizeof($runtime_start) == 0)
										$total_runtime =0;
					  
									//echo "<br>SIZE1=".sizeof($runtime_start)." ,SIZE2=".sizeof($runtime_start);
					  
									//if( (sizeof($runtime_stop) == 0) && (sizeof($runtime_start)>0) )
									if( ((sizeof($runtime_stop)) == (sizeof($runtime_start)-1)) )  
									{
										//echo "<br>A:RunStop";
										$runtime_stop[$r2] = $xml_date_current;
										$stop_runflag = 1;
										$start_runflag = 0; 
										$r2++;
									}
					  
									$total_runtime = 0;
									for($m=0;$m<(sizeof($runtime_start));$m++)
									{
										//echo "<br>A:run1=".$runtime_stop[$m]." ,run2=".$runtime_start[$m]."<br>";                   
										$runtime = strtotime($runtime_stop[$m]) - strtotime($runtime_start[$m]);
										$total_runtime = $total_runtime + $runtime;
										//echo "<br>A:runtime=".$runtime." ,total_runtime=".$total_runtime;                    
									}                 
									//echo "<br>total_speed=".$total_speed." ,total_runtime1=".$total_runtime."<br>";
									//$total_test_time = $total_test_time + $total_runtime;
															
									if(($sinterval>=1800) && ($total_dist<3.0))
									{
										$total_dist = 0.0;
									} 
									else
									{
										$total_dist = round($total_dist,3);
									}
								
									$avg_speed = ($total_dist / $total_runtime)*3600;                  
									/////////
									//$avg_speed = array_sum($speed_arr)/sizeof($speed_arr);	

									$avg_speed = round($avg_speed,2);
									$max_speed = max($speed_arr);
									$max_speed = round($max_speed,2);
									
					  //echo "<BR><br>SPEED THRESHOLD=".$speed_threshold." ,TOTAL DISTANCE(km) = ".$total_dist."<BR>TOTAL RUNTIME(seconds)= ".$total_runtime." <BR>AVERAGE SPEED = ".$avg_speed." <BR>MAX SPEED = ".$max_speed." <BR>TOTAL SPEED = ".$total_speed_tmp." <BR>TIME1 = ".$time1." <BR>TIME2 = ".$time2."<BR>----------------------------------------------------------";							

									/*if($avg_speed ==0)
									{
										$max_speed = 0;
									}*/
									
									if( ($avg_speed > $max_speed) && ($max_speed > 2.0) )
									{
										$avg_speed = $max_speed - 2;
									}              
									else if( ($avg_speed > $max_speed) && ($max_speed > 0.2) && ($max_speed <= 2.0) )
									{								
										$avg_speed = $max_speed - 0.2;
									}							              							
									
									if($avg_speed<150)
									{
								
										if($avg_speed==0)
										{
											$max_speed=0;
										}
										if($final_maxspeed_tmp<$max_speed)
										{
											$final_maxspeed_tmp=$max_speed;
											//echo "final_maxspeed_tmp=".$final_maxspeed_tmp."<br>";								
										}
										
									}  		
					  
									//reassign time1
									$time1 = $datetime;
									$date_secs1 = strtotime($time1);
									$date_secs1 = (double)($date_secs1 + $sinterval);		
									$speed_arr = null;
									$j=0;

									$avg_speed = 0.0;
									$total_dist = 0.0;

									$runtime_start = array();
									$runtime_stop = array();

									$start_runflag = 0;
									$stop_runflag = 1;

									$total_runtime =0; 

									$r1 = 0;
									$r2 = 0;                  	 						                  				
								///////////////////////
								}											                               
							}
							///////////////////////////distance//////////////////
							//echo "<br>first=".$firstdata_flag;                                        
							if($dfirstdata_flag==0)
							{					
								$dfirstdata_flag = 1;
								$dlat1 = $dlat;
								$dlng1 = $dlng;

								//echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
								$dinterval = (double)$timeinterval*60;							

								$dtime1 = $datetime;					
								$ddate_secs1 = strtotime($dtime1);					
								//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
								$ddate_secs1 = (double)($ddate_secs1 + $dinterval); 
								$ddate_secs2 = 0;  
								$dlast_time1 = $datetime;
								$dlatlast = $dlat;
								$dlnglast =  $dlng;
								//echo "<br>FirstData:".$date_secs1." ".$time1;                 	
							}
							//echo "<br>k2=".$k2."<br>";							
							else
							{                      					
							
								$dtime2 = $datetime;											
								$ddate_secs2 = strtotime($dtime2);																					                                      													      					
								$dlat2 = $dlat;      				        					
								$dlng2 = $dlng; 
								calculate_distance($dlat1, $dlat2, $dlng1, $dlng2, &$ddistance);															
								$dtmp_time_diff1 = (double)(strtotime($datetime) - strtotime($dlast_time1)) / 3600;								
								calculate_distance($dlatlast, $dlat2, $dlnglast, $dlng2, &$ddistance1);								
								if($dtmp_time_diff1>0)
								{
									$dtmp_speed = ((double) ($ddistance1)) / $dtmp_time_diff1;
									$dlast_time1 = $datetime;
									$dlatlast = $dlat2;
									$dlnglast =  $dlng2;
								}
								$dtmp_time_diff = ((double)( strtotime($datetime) - strtotime($dlast_time) )) / 3600;								           
                             
								if($dtmp_speed<500.0 && $ddistance>0.1 && $dtmp_time_diff>0.0)
								{														
									$dtotal_dist = (double)( $dtotal_dist + $ddistance );
									$ddaily_dist= (double) ($ddaily_dist + $ddistance);	
									$ddaily_dist = round($ddaily_dist,2);							                          
									$dlat1 = $dlat2;
									$dlng1 = $dlng2;
									$dlast_time = $datetime;
									$dvname_tmp  = $dvname;
									$dvserial_tmp = $dvserial;
									$dtime1_tmp = $dtime1;
									$dtime2_tmp = $dtime2;
									$dtotal_dist_tmp = $dtotal_dist;                		    						
								} 
						
								if( ($ddate_secs2 >= $ddate_secs1))    // || ($f == $total_lines-5))
								{
									//echo "distance=".$dtotal_dist."<br>";
									$trip_total_dist=$trip_total_dist+round($dtotal_dist,2);									
									$dtime1 = $datetime;
									$ddate_secs1 = strtotime($dtime1);
									$ddate_secs1 = (double)($ddate_secs1 + $dinterval);		    									    						    						
									//echo "<br>datesec1=".$datetime;    						                  
									$dtotal_dist = 0.0;	 

									$dlat1 = $dlat2;
									$dlng1 = $dlng2;
									///////////////////////																
								}  //if datesec2      
     					
								//echo "<br>REACHED-3";		                                                                        									                               
							}   // else closed   
							//////////////////////////////////////////
							///////////// engine run hour report /////////////
							$status = preg_match('/'.$io.'=\"[^\"]+/', $line, $enginecount_tmp);
							$enginecount_tmp1 = explode("=",$enginecount_tmp[0]);  
							$engine_count = preg_replace('/"/', '', $enginecount_tmp1[1]); 
							
							 if($engine_count>500)
							{
								$continuous_running_flag = 1;
							}
							if($engine_count>500 && $StartFlag==0)  //500
							{                						
								$etime1 = $xml_date_current;
								
								$StartFlag = 1;
							} 
							else if( ($engine_count<500 && $StartFlag==1) || ( ($c==($total_lines-1)) && ($continuous_running_flag ==1) ) )   //500
							{
								$StartFlag = 2;
							}				
							$etime2 = $xml_date_current;
								
            
							if($StartFlag == 2)
							{
								$StartFlag=0;
								$runtime = strtotime($etime2) - strtotime($etime1);
								if($runtime > 60)
								{
									/*echo "time1=".$time1;
									echo "time2=".$time2;
									echo "runtime=".$runtime."<br>";*/
								  //echo "<br>runtime=".$runtime;
									//$runhr_duration = strtotime($runtime);
									/*$hr =  (int)(($runtime)/3600);	 
									//$runhr_duration = round($runhr_duration,2);
									$min =  ($runtime)%3600;
									$sec =  (int)(($min)%60);
									$min =  (int)(($min)/60); */									
									 $sum_engine_runhr = $sum_engine_runhr + $runtime;
									 //echo "sum_engine_runhr=".$sum_engine_runhr."<br>";
								} 
							} 
							/////////////////////End of Engine Run hour report//////////////
						
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
								calculate_distance($tmp1lat,$tmp2lat,$tmp1lng,$tmp2lng,&$distance_trip);
									
								//$linetolog = $CurrentLat.','.$CurrentLong.','.$LastLat.','.$LastLong.','.$distance.','.$xml_date_current.','.$xml_date_last.','.(strtotime($xml_date_current)-strtotime($xml_date_last)).','.$timeinterval.','.$distanceinterval.','.$enddate.','.$startdate."\n";
								//echo "distance=".$distance."<br>";									
								$overSpeed=$distance/$dateDifference_1;
								
								//fwrite($xmllog, $linetolog);
							}
							if($distance_trip<$distanceinterval)
							{
								$LastDTForDif=$xml_date_current;
							}
							/*if ( (((((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval) && ($distance_trip>=$distanceinterval)) || ($firstData==0)) && 
								(($xml_date_current<=$enddate) && ($xml_date_current>=$startdate))) || ($f==$total_lines-2) )*/
							if(($distance_trip>=$distanceinterval) || ($firstData==0))
							{  						
								//$xml_date_last = $xml_date_current;									
								if($overSpeed<200)
								{										
									$LastLat =$CurrentLat;
									$LastLong =$CurrentLong;									
									$linetolog = "Data Written\n";
									$LastDTForDif=$xml_date_current;
									$line = substr($line, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
									$finalDistance = $finalDistance + $distance_trip;										
									if($userdates[$i]<$old_xml_date)
									{
										//echo "in if 111";
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
										
										$linetowrite = "\n".$line.' w="'.$vname.'" z="'.round($finalDistance,2).'"/>';
									}
									else
									{
										$linetowrite = "\n".$line.' v="'.$vehicle_serial.'" w="'.$vname.'" z="'.round($finalDistance,2).'"/>';
									}
									//$linetowrite = "\n".$line.' cumdist="'.$finalDistance.'"./>'; // for distance       // ADD DISTANCE
									//$linetowrite = "\n".$line.'/>';
									//echo "<textarea>".$linetowrite."</textarea>";
									$firstData = 1;  
									//$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
									fwrite($fh, $linetowrite);  
								}
							}
						}
						/*else if(($xml_date_current > $enddate) && ($xml_date_current!="-") && ($DataValid==1) )
						{
							 //echo "in first";
							$linetolog = "Data Written1\n";
							//fwrite($xmllog, $linetolog);
							$line = substr($line, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
							//$linetowrite = "\n".$line.' distance="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE
							// $linetowrite = "\n".$line.'/>'; // for distance       // ADD DISTANCE
							if($userdates[$i]<$old_xml_date)
							{
								//echo "in if 111";
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
								
								$linetowrite = "\n".$line.' w="'.$vname.'"/>';
							}
							else
							{
								$linetowrite = "\n".$line.' v="'.$vehicle_serial.'" w="'.$vname.'"/>';
							}
						 fwrite($fh, $linetowrite);
						 $DataComplete=true;
						 break;
						}*/
					}
  				$f++;
				$j++;
				}   // while closed				
			} // if original_tmp exist closed       
			if($DataComplete==false)
			{
				//echo "in false";
				if((preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $linetmp, $lat_match)) &&  (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $linetmp, $lng_match)) )
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
					if($userdates[$i]<$old_xml_date)
					{
						//echo "in if 111";
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
						
						$linetowrite = "\n".$line.' w="'.$vname.'" z="'.round($finalDistance,2).'"/>';
					}
					else
					{
						$linetowrite = "\n".$line.' v="'.$vehicle_serial.'" w="'.$vname.'" z="'.round($finalDistance,2).'"/>';
					}
					//$linetowrite = "\n".$line.'"/>'; // for distance       // ADD DISTANCE
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

?>
