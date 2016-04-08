<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];
include_once('common_xml_element.php');
include_once("get_all_dates_between.php");
include_once("sort_xml.php");
include_once("calculate_distance.php");
include_once("report_title.php");
include_once("read_filtered_xml.php");

set_time_limit(1200);
$DEBUG =0;

$device_str = $_POST['vehicleserial'];
$vserial = explode(':',$device_str);
$vsize=count($vserial);
$date1 = str_replace('/','-',$_POST['start_date']);
$date2 = str_replace('/','-',$_POST['end_date']);
//echo "<br>size=".$size;

$timetmp1 = 0;
$breakflag = 0;

////////////////////////////////////////////////////////////////////////////
for($i=0;$i<$vsize;$i++)
{
    //echo "In loop";
	$vehicle_info=get_vehicle_info($root,$vserial[$i]);
	$vehicle_detail_local=explode(",",$vehicle_info);	
	$vname_tmp[$i] = $vehicle_detail_local[0];
}

$current_dt = date("Y_m_d_H_i_s");	
$xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";

write_monthly_distance_report_xml($vserial, $vname_tmp, $date1, $date2, $xmltowrite);


function write_monthly_distance_report_xml($vserial, $vname, $date1, $date2, $xmltowrite)
{
	global $daystmp;
	$maxPoints = 1000;
	$file_exist = 0;

	$fh = fopen($xmltowrite, 'w') or die("can't open file 3"); //new
	fwrite($fh, "<t1>");  
	fclose($fh);

	global $date1;
	global $date2; 
	global $month;
	global $year; 	
	global $daysize;
	global $days1;
	global $timetmp1;
	//$i=0;
	$timetmp1 = date("Y-m-d H:i:s");	
	$timetmp1 = strtotime($timetmp1);
  	
	for($i=0;$i<sizeof($vserial);$i++)
	{  	       
          //echo "<br>date1=".$date1." ,date2=".$date2;
          get_vehicle_violation_data($vserial[$i], $vname[$i], $date1, $date2, $xmltowrite);             
	}     
 
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}

function get_vehicle_violation_data($vehicle_serial, $vname, $startdate, $enddate, $xmltowrite)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	//echo "<br>vs=".$vehicle_serial." ,vname=".$vname." ,startdate=".$startdate." ,enddate=".$enddate." ,xmltowrite=".$xmltowrite;
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
	$breakflag = 0;
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

	//$j = 0;
	$total_dist = 0; 									  
	global $timetmp1;
	global $breakflag;
	
	//## DEFINE VIOLATION VARIABLES
	$distance_valid_threshold = 0.1;
	$A1=null;
	$A2=null;
	$dist1=0;
	$dist2=0;
	$distance_threshold=2;	//km
	$angle_threshold1=20;
	$angle_threshold2 = 170;
	$turning_threshold = 500;	
	$previous_lat = "";
	$previous_lng = "";	
	$previous_time="";
	$ViolationFlag = false;
	$violation_time2 = "";
	$violation_start_time="";
	$violation_distance=0;
	//#############################
  
	for($i=0;$i<=($date_size-1);$i++)
	{
		//echo "<br>time=".$timetmp1;
		$timetmp2 = date("Y-m-d H:i:s");	
		$timetmp2 = strtotime($timetmp2);    
		$difftmp = ($timetmp2 - $timetmp1);		

		$daily_dist = 0;   
		include("/var/www/html/vts/beta/src/php/common_xml_path.php"); 
		$xml_current = $xml_data."/".$userdates[$i]."/".$vehicle_serial.".xml";	    		
		//echo "<br>xml_current=".$xml_current;
		if (file_exists($xml_current))      
		{		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			//$xml_file = "/mnt/volume4/".$userdates[$i]."/".$vehicle_serial.".xml";
			$xml_file = $sorted_xml_data."/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}
			
		//echo "<br>xml_file =".$xml_file;	    	
		if (file_exists($xml_file)) 
		{			
			//$current_datetime1 = date("Y_m_d_H_i_s");
			$t=time();
			$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";			
									  
			if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
				copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "<br>TWO<br>";
				$xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
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
      
			if (file_exists($xml_original_tmp)) 
			{      
				//echo "<br>FileExists";
				$daily_dist =0;
				//$firstdata_flag =0;
				//echo "<br>userdates=".$userdates[$i];
				set_master_variable($userdates[$i]);       
				while(!feof($xml))          // WHILE LINE != NULL
				{
					$valid_data = false;
					$DataValid = 0;
					//echo fgets($file). "<br />";
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
					//echo "<br>line=".$line;

					if(strlen($line)>20)
					{
						$linetmp =  $line;
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
						$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
						$xml_date = $datetime;
						//echo "<br>xml_date=".$xml_date;
					}				          
          
					if($xml_date!=null)
					{				             
						//echo "<br>xml_date=".$xml_date." ,startdate=".$startdate." ,enddate=".$enddate." ,userdates=".$userdates[$i]." ,DataValid=".$DataValid;
						$xml_date_only = explode(' ',$xml_date);					
						if( ($xml_date >= $startdate && $xml_date <= $enddate  && $xml_date >= $xml_date_latest && $xml_date<=($userdates[$i]." 23:59:59")) && ($xml_date!="-") && ($DataValid==1) )
						{
							//echo "<br>xml_data=".$xml_date;
							
							$xml_date_latest = $xml_date;
              
							$status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
							if($status==0)
							{
								continue;               
							}
							//echo "test1".'<BR>';
							$status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
							if($status==0)
							{
								continue;
							}                                                   
							//echo "test2".'<BR>';
							$lat_tmp1 = explode("=",$lat_tmp[0]);
							$lat = preg_replace('/"/', '', $lat_tmp1[1]);

							$lng_tmp1 = explode("=",$lng_tmp[0]);
							$lng = preg_replace('/"/', '', $lng_tmp1[1]); 
                              
							//echo "<br>first=".$firstdata_flag;                                        
							if($firstdata_flag==0)
							{								
								$firstdata_flag = 1;
								$lat1 = $lat;
								$lng1 = $lng;
								$last_time1 = $datetime;                
								$latlast = $lat;
								$lnglast =  $lng; 

								$previous_lat = $lat;
								$previous_lng = $lng;
								$previous_time = $datetime;
							}           	           	
							else
							{                           
								//echo "<br>In else";
								$current_lat = $lat;      				        					
								$current_lng = $lng; 
								//calculate_distance($lat1, $lat2, $lng1, $lng2, &$distance);
								//echo "<br>lat1=".$lat1." ,current_lat=".$current_lat." ,lng1=".$lng1." ,current_lng=".$current_lng;
								calculate_distance($lat1, $current_lat, $lng1, $current_lng, &$distance);								
								$tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;

								//calculate_distance($latlast, $lat2, $lnglast, $lng2, &$distance1);
								calculate_distance($latlast, $current_lat, $lnglast, $current_lng, &$distance1);
								//$d1 = $distance1;								
								//echo "<br>Distance=".$distance." ,Distance=".$distance;
								
								if($tmp_time_diff1>0)
								{
									$tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
									$last_time1 = $datetime;
									$latlast = $current_lat;
									$lnglast =  $current_lng;
								}
								$tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time) )) / 3600;
														                             
								if( ($tmp_speed<500.0) && ($distance>$distance_valid_threshold) && ($tmp_time_diff>0.0) )
								{
									//echo "<br>dist greater than 0.1";					
									//$total_dist = $total_dist + $distance;									
									$lat1 = $current_lat;
									$lng1 = $current_lng;
																										
									$last_time = $datetime;									
									//////// TMP VARIABLES TO CALCULATE LAST XML RECORD  //////
									$vname_tmp  = $vname;
									$vserial_tmp = $vserial;
									//$time1_tmp = $time1;
									//$time2_tmp = $time2;
									//$total_dist_tmp = $total_dist;
									$valid_data = true;									               		    						
								} 
								if($valid_data)
								{
									//echo "<br>ValidData";
									//####### LOGIC PART
									$angle = get_angle($previous_lat,$previous_lng,$current_lat,$current_lng);
									calculate_distance($previous_lat, $current_lat, $previous_lng, $current_lng, &$d1);
									
									//echo "<br>A6: A1=".$A1." ,datetime=".$datetime." ,angle=".$angle." ,previous_time=".$previous_time;
									//echo $angle."<br>";
									
									if($A1==null)
									{
										$A1 = $angle;
										$previous_lat = $current_lat;
										$previous_lng = $current_lng;
										$previous_time = $datetime;
										$violation_time1 = $datetime;
										
										//echo "<br>A1:".$A1." ,violation_time1=".$violation_time1." ,angle=".$angle." ,previous_time=".$previous_time;
									}
									else if($A2!=null)
									{
										if(abs($angle-$A2) < $angle_threshold1)
										{
											$dist2 += $d1;
											$previous_lat = $current_lat;
											$previous_lng = $current_lng;
											$previous_time = $datetime;
											
										}
										else
										{
											if($dist2 > $distance_threshold)
											{												
												$violation_time2 = $datetime;
												$violation_distance = $dist2;
												//echo "<br>A2:violation_time2=".$violation_time2." ,angle=".$angle." ,A2=".$A2;
												
												$A1=null;
												$A2=null;
												$dist1=null;
												$dist2=null;
												$turning_distance = 0;
												$ViolationFlag = true;
												//SendViolationAlert();
											}
											
											$A1=null;
											$A2=null;
											$dist1=null;
											$dist2=null;
											$turning_distance = 0;
										}
									}
									else if($A1!=null)
									{
										if(abs($angle-$A1) < $angle_threshold1)			//## STRAIGHT LINE
										{											
											$dist1+= $d1;
											$previous_lat = $current_lat;
											$previous_lng = $current_lng;
											$previous_time = $datetime;
											$A1 = $angle;
										}
										else if($dist1 < $distance_threshold)		//## TURNING BEFORE - < DIST THRESHOLD
										{
											$A1=null;
											$dist1 = null;
											$turning_distance = 0;						
											$previous_lat = $current_lat;
											$previous_lng = $current_lng;
											$previous_time = $datetime;
											//echo "<br>A3:datetime=".$datetime." ,angle=".$angle." ,A1=".$A1;
										}			
										else if(abs($angle - $A1) > $angle_threshold2)	//## TURNING AFTER -DIST THRESHOLD (ANGLE > THRESHOLD && DISTANCE > DIST_THRESHOLD) //### 170
										{
											$A2 = $angle;
											$dist2 =0;
											$previous_lat = $current_lat;
											$previous_lng = $current_lng;
											$previous_time = $datetime;
											$violation_start_time=$datetime;
											//echo "<br>A4:datetime=".$datetime." ,angle=".$angle." ,A2=".$A2;
										}
										else		// distance > 2km, greater than angle_threshold && less than angle_threshold2
										{
											$turning_dist += $d1;
											
											if($turning_dist > $turning_threshold)
											{
												$A1 = null;
												$dist1 =0;
												$previous_lat = $current_lat;
												$previous_lng = $current_lng;
												$previous_time = $datetime;
												//echo "<br>A5:datetime=".$datetime." ,angle=".$angle." ,A1=".$A1;
											}
										}			
									}
									//##### LOGIC CLOSED					                 
								}
							} // $xml_date_current >= $startdate closed
						}   // if xml_date!null closed
						//$j++;	
					}   // while closed
					if($ViolationFlag==true)
					{
					
						$violation_data = "\n< marker imei=\"".$vehicle_serial."\" vname=\"".$vname."\" violation_time1=\"".$violation_start_time."\" violation_time2=\"".$violation_time2."\" violation_distance=\"".$violation_distance."\"/>";						          						
						//echo "<br><br>Violation:".$violation_data." ,Fh=".$fh;
						$linetowrite = $violation_data; // for distance       // ADD DISTANCE
						fwrite($fh, $linetowrite);
						
						$ViolationFlag = false;
					}
				} // if original_tmp closed         
			
				//WRITE DAILY DISTANCE DATA				
              
				fclose($xml);            
				unlink($xml_original_tmp);
			} // if (file_exists closed
		}  // for closed 
		//echo "Test1";
	}
	
	if(($A2!=null) && ($dist2 > $distance_threshold))
	{												
		$violation_time2 = $datetime;
		$violation_distance = $dist2;
		//echo "<br>A2:violation_time2=".$violation_time2." ,angle=".$angle." ,A2=".$A2;
		
		$A1=null;
		$A2=null;
		$dist1=null;
		$dist2=null;
		$turning_distance = 0;
		$ViolationFlag = true;
		
		$violation_data = "\n< marker imei=\"".$vehicle_serial."\" vname=\"".$vname."\" violation_time1=\"".$violation_start_time."\" violation_time2=\"".$violation_time2."\" violation_distance=\"".$violation_distance."\"/>";						          						
		//echo "<br><br>Violation1:".$violation_data." ,Fh=".$fh;
		$linetowrite = $violation_data; // for distance       // ADD DISTANCE
		fwrite($fh, $linetowrite);
		
		$ViolationFlag = false;
		//SendViolationAlert();
	}
	fclose($fh);
}

function get_angle($lat1,$lng1,$lat2,$lng2)
{
	$yaxis = ($lat1 + $lat2)/2;
	$xaxis = ($lng1 + $lng2)/2;
	
	$angle_t = atan( ($lat2-$lat1)/($lng2-$lng1) );
	$angle_deg = 360 * $angle_t/(2 * pi());	

	if(($lng2-$lng1)<0)
	{
		$angle_deg = 180 + $angle_deg;
	}
	else if(($lat2-$lat1)<0)
	{
		$angle_deg = 360 + $angle_deg;
	}

	$angle_deg = round($angle_deg,2);
	
	//echo "AngleDeg=".$angle_deg;
	return $angle_deg;
}
$m1=date('M',mktime(0,0,0,$month,1));

//if($breakflag==1)
// echo "<br><center><font color=red>Data too large please select less duration/days/vehicle</font></center><br>";

  echo'<br>';
  //report_title("Vehicle Reverse",$date1,$date2);

  echo'<center>
  <div style="overflow: auto;height: 350px; width: 620px;" align="center">
  ';
    ///////////////////  READ SPEED XML 	//////////////////////////////				                      
    $xml_path = $xmltowrite;
    //echo "<br>xml_path=".$xml_path;
	read_reverse_violation_data($xml_path, &$imei, &$vname, &$violation_time1, &$violation_time2, &$violation_distance);
    //echo "<br><br>size, vname=".sizeof($vname);
    //echo "<br>dt=".$mdate[0];
		//////////////////////////////////////////////////////////////////////
    $vsize = sizeof($imei);

    echo'<form method = "post" target="_blank">';
    
    $title="Vehicle Reverse Violation :  (".$date1."-".$date2."  )";
    $csv_string = "";   
		$csv_string = $csv_string.$title."\n";
		$csv_string = $csv_string."SNo,Vehicle,Time1,Time2,Violation Distance\n";
    echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
    
    echo'
    <center><strong>'.$title.'</strong></center>';
            			                  
    echo '<br><table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
    <tr>
  		<td class="text" align="left"><b>SNo</b></td>
  		<td class="text" align="left"><b>Vehicle</b></td>
  		<td class="text" align="left"><b>Time1</b></td>
  		<td class="text" align="left"><b>Time2</b></td>
		<td class="text" align="left"><b>Violation Distance (KM)</b></td>
    </tr>';        
    
    for($i=0;$i<$vsize;$i++)
	{								                                                    
		$sno = $i+1;
		$vname1[$i] = $vname[$i];
		$imei1[$i] = $imei[$i];               
		$violation_distance[$i] = round($violation_distance[$i]  ,2);
		echo'<tr><td class="text" align="left" width="12%"><b>'.$sno.'</b></td>';
		echo'<td class="text" align="left">'.$vname[$i].'</td>';        												
		echo'<td class="text" align="left">'.$violation_time1[$i].'</td>';		
		echo'<td class="text" align="left">'.$violation_time2[$i].'</td>';	
		echo'<td class="text" align="left">'.$violation_distance[$i].'</td>';			
		echo'</tr>';	         
        
		echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$i][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$vname[$i]\" NAME=\"temp[$i][Vehicle]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$violation_time1[$i]\" NAME=\"temp[$i][Time1]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$violation_time2[$i]\" NAME=\"temp[$i][Time2]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$violation_distance[$i]\" NAME=\"temp[$i][Violation Distance]\">";
		$csv_string = $csv_string.$sno.','.$vname[$i].','.$violation_time1[$i].','.$violation_time2[$i].','.$violation_distance[$i]."\n"; 							        					      								   
		$sno++;                       							  		
    }
    
    echo'<input TYPE="hidden" VALUE="vehicle_reverse" NAME="csv_type">';
    echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';  
    
    echo '</table>';
    echo "</div>";  
    echo'<br><br>';   
    
    if($vsize==0)
    {						
    	print"<center><FONT color=\"Red\" size=2><strong>No Vehicle Reverse violation Found</strong></font></center>";
    	echo'<br><br>';
    }	
    else
    {                     
      echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;';           
    }   
    
    echo '</form>';  		
//    unlink($xml_path);
    echo '</center>'; 
                 							
?>
						
