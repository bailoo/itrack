<?php
	//error_reporting(-1);
	//ini_set('display_errors', 'On');
	set_time_limit(3000);	
	date_default_timezone_set("Asia/Kolkata");
	include_once("main_vehicle_information_1.php");
	include_once('Hierarchy.php');
	$root=$_SESSION["root"];
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('xmlParameters.php');
	include_once("report_title.php");
	include_once('parameterizeData.php');
	include_once('data.php');
	include_once("sortXmlData.php");
	include_once("getXmlData.php");
	include_once("calculate_distance.php");
	include_once("get_location.php");
	include_once("select_landmark_report.php");
	
	$DEBUG =0;
	$device_str = $_POST['vehicleserial'];
	//echo "<br>devicestr=".$device_str;
	$vserial = explode(':',$device_str);
	$vsize=count($vserial);

	$date1 = $_POST['start_date'];
	$date2 = $_POST['end_date'];
	$date1 = str_replace("/","-",$date1);
	$date2 = str_replace("/","-",$date2);
	$date_1 = explode(" ",$date1);
	$date_2 = explode(" ",$date2);
	$datefrom = $date_1[0];
	$dateto = $date_2[0];
	
	$userInterval = "0";
	
	$sortBy='h';
	$firstDataFlag=0;
	$endDateTS=strtotime($date2);
	$dataCnt=0;	
	//$userInterval = "0";
	$requiredData="All";
	
	$parameterizeData=new parameterizeData();
	$ioFoundFlag=0;
	
	$parameterizeData->latitude="d";
	$parameterizeData->longitude="e";
	$parameterizeData->speed="f";
	
	$finalVNameArr=array();

	for($i=0;$i<$vsize;$i++)
	{
		$dataCnt=0;
		$vehicle_info=get_vehicle_info($root,$vserial[$i]);
		$vehicle_detail_local=explode(",",$vehicle_info);
		$finalVNameArr[$i]=$vehicle_detail_local[0];
		//echo "vehcileName=".$finalVNameArr[$i]." vSerial=".$vehicle_detail_local[0]."<br>";
			
		$LastSortedDate = getLastSortedDate($vserial[$i],$datefrom,$dateto);
		$SortedDataObject=new data();
		$UnSortedDataObject=new data();
		
		if(($LastSortedDate+24*60*60)>=$endDateTS) //All sorted data
		{	
			//echo "in if1";
			$type="sorted";
			readFileXml($vserial[$i],$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
		}
		else if($LastSortedDate==null) //All Unsorted data
		{
			//echo "in if2";
			$type="unSorted";
			readFileXml($vserial[$i],$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
		}
		else //Partially Sorted data
		{
			$LastSDate=date("Y-m-d",$LastSortedDate+24*60*60);
			//echo "in else";
			$type="sorted";					
			readFileXml($vserial[$i],$date1,$date2,$datefrom,$LastSDate,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
		
			$type="unSorted";
			readFileXml($vserial[$i],$date1,$date2,$LastSDate,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
		}
	
		/*echo "udt1=".$UnSortedDataObject->deviceDatetime[0]."<br>";
		echo "udt2=".$UnSortedDataObject->deviceDatetime[1]."<br>";	
		echo "udt1=".$UnSortedDataObject->speedData[0]."<br>";
		echo "udt2=".$UnSortedDataObject->speedData[1]."<br>";
		echo "<br><br>";*/
		
		if(count($SortedDataObject->deviceDatetime)>0)
		{
			/*echo "psdt1=".$SortedDataObject->deviceDatetime[0]."<br>";
		echo "psdt2=".$SortedDataObject->deviceDatetime[1]."<br>";	
		echo "psp1=".$SortedDataObject->speedData[0]."<br>";
		echo "psp2=".$SortedDataObject->speedData[1]."<br>";
		echo "<br><br>";*/
			$prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
			for($obi=0;$obi<$prevSortedSize;$obi++)
			{			
				$finalDateTimeArr[$i][]=$SortedDataObject->deviceDatetime[$obi];
				$finalLatitudeArr[$i][]=$SortedDataObject->latitudeData[$obi];
				$finalLongitudeArr[$i][]=$SortedDataObject->longitudeData[$obi];
				$finalSpeedArr[$i][]=$SortedDataObject->speedData[$obi];				
				///$dataCnt++;
			}
		}
		if(count($UnSortedDataObject->deviceDatetime)>0)
		{
			$sortObjTmp=sortData($UnSortedDataObject,$sortBy,$parameterizeData);
			//var_dump($sortObjTmp);
			/*echo"sdt1=".$sortObjTmp->deviceDatetime[0]."<br>";
			echo "sdt2=".$sortObjTmp->deviceDatetime[1]."<br>";	
			echo "ss1=".$sortObjTmp->speedData[0]."<br>";
			echo "ss2=".$sortObjTmp->speedData[1]."<br>";
			echo "<br><br>";*/
			$sortedSize=sizeof($sortObjTmp->deviceDatetime);
			for($obi=0;$obi<$sortedSize;$obi++)
			{				
				$finalDateTimeArr[$i][]=$sortObjTmp->deviceDatetime[$obi];	
				$finalLatitudeArr[$i][]=$sortObjTmp->latitudeData[$obi];
				$finalLongitudeArr[$i][]=$sortObjTmp->longitudeData[$obi];	
				$finalSpeedArr[$i][]=$sortObjTmp->speedData[$obi];
				//$dataCnt++;
			}
		}
		$SortedDataObject=null;			
		$sortObjTmp=null;
		$UnsortedDataObject =null;
			
	}
	$parameterizeData=null;	
	
	
	$tagArr=array("Summary Report From DateTime","SNo","Vehicle Name","IMEI",
			"Start Date","End Date","Start Location",
			"End Location","Total Distance (km)","Journey Time (hr.min)",
			"Halt (hr.min)-1 hr interval","Track","Summary Report");
	
	
	report_title($tagArr[0],$date1,$date2);
	
	//print_r($finalVNameArr);
if(count($finalVNameArr)>0)
{
$csv_string = "";
echo'<form  name="text_data_report" method="post" target="_blank">'.
		report_title($tagArr[0],$date1,$date2).'
			<center>
				<div style="overflow:auto;height:420px;width:950px;">
					<table border=1 style="width:2000px;" bordercolor="#e5ecf5" align="center" cellspacing="0" cellpadding="0">';
						echo '<tr valign="top">
								<td class="text" align="left" width="100px"><strong>'.$tagArr[0].'</strong></td>
								<td class="text" align="left" width="100px"><strong>'.$tagArr[1].'</strong></td>
								<td class="text" align="left" width="100px"><strong>'.$tagArr[2].'</strong></td>
								<td class="text" align="left" width="120px"><strong>'.$tagArr[3].'</strong></td>
								<td class="text" align="left" width="120px"><strong>'.$tagArr[4].'</strong></td>
								<td class="text" align="left" width="300px"><strong>'.$tagArr[5].'</strong></td>
								<td class="text" align="left" width="300px"><strong>'.$tagArr[6].'</strong></td>
								<td class="text" align="left" width="50px"><strong>'.$tagArr[7].'</td>
								<td class="text" align="left" width="50px"><strong>'.$tagArr[8].'</td>
								<td class="text" align="left" width="310px"><strong>'.$tagArr[9].'</strong></td>
								<td class="text" align="left"><strong>'.$tagArr[10].'</strong></td>
							</tr>';
							$csv_string="";
							$title= $tagArr[0]." : ".$date1."-".$date2;
							$csv_string = $csv_string.$title."\n";
							$csv_string = $csv_string.$tagArr[0].",".$tagArr[1].",".$tagArr[2].",".
										$tagArr[3].",".$tagArr[4].",".$tagArr[5].",".$tagArr[6].",".
										$tagArr[7].",".$tagArr[8].",".$tagArr[9].",".$tagArr[10]."\n";
							echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
					$sCnt=0;
					$final_maxspeed_tmp=0;
					$user_interval = 60;
					$pdfi=0;
					for($i=0;$i<$vsize;$i++)
					{
						
						$innerSize=0;
						$innerSize=sizeof($finalDateTimeArr[$i]);	
					
						$total_dist = 0.0;
						$overall_dist = 0.0;
						$firstdata_flag=0;
						$last_time=0;
						$distance =0.0;													
						$total_dist = 0.0;
						$sum_interval_dist = 0.0;
						for($j=0;$j<$innerSize;$j++)
						{
							$lat = $finalLatitudeArr[$i][$j];						
							$lng = $finalLongitudeArr[$i][$j];							
							$speed = $finalSpeedArr[$i][$j];
							$datetime=$finalDateTimeArr[$i][$j];							
							$xml_date=$datetime;
							$firstData = 0;
							if($firstdata_flag==0)
							{					
								$firstdata_flag = 1;
								$lat1 = $lat;
								$lng1 = $lng;
								$interval = (double)$user_interval*60;							

								$time1 = $datetime;					
								$date_secs1 = strtotime($time1);					
								//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
								$date_secs1 = (double)($date_secs1 + $interval); 
								$date_secs2 = 0;             
								//echo "<br>FirstData:".$date_secs1;                 	
							}
							else
							{  
								$time2 = $datetime;											
								$date_secs2 = strtotime($time2);	
								$vserial=$vehicle_serial;														                                      													      					
								$lat2 = $lat;      				        					
								$lng2 = $lng;
								calculate_distance($lat1, $lat2, $lng1, $lng2, $distance);
								//echo "distance=".$distance."<br>";
								
								$tmp_time_diff = (strtotime($datetime) - strtotime($last_time)) / 3600;
								$tmp_speed = $distance / $tmp_time_diff;
								$last_time = $datetime;  
								
								if($tmp_speed<3000 && $distance>0.1)
								{														
									$total_dist = (float) ($total_dist + $distance);
									//echo "distance=".$total_dist."<br>";
									$lat1 = $lat2;
									$lng1 = $lng2;	    						
								}

								if($date_secs2 >= $date_secs1)
								{
									//echo "in interval<br>";
									//echo "interval=".$interval." dist=".$total_dist."<br>";
									if(($interval>=1800) && ($total_dist<0.2))
									{
										$total_dist = 0.0;
									} 
									else
									{
										$total_dist = round($total_dist,3);
									}
									//echo "dist=".$total_dist."<br>";
									$sum_interval_dist = $sum_interval_dist + $total_dist; 
									$time1 = $datetime;
									$date_secs1 = strtotime($time1);
									$date_secs1 = (double)($date_secs1 + $interval);   						                  
									$total_dist = 0.0;															
								}  //if datesec2                                         		        		
							}  // else closed						
						}								
						if(($interval>=1800) && ($total_dist<0.2))
						{
							$total_dist = 0.0;
						}
						else
						{
							$total_dist = round($total_dist,3);
						}
						//echo"total_dist=".$total_dist."<br>";
						$sum_interval_dist = $sum_interval_dist + $total_dist;
						//echo"sum_interval_dist=".$sum_interval_dist."<br>";					
						$time1 = $datetime;
						$date_secs1 = strtotime($time1);
						$date_secs1 = (double)($date_secs1 + $interval);		
						$total_dist = 0.0;					
						
						$partial_dist = 0;
						$interval_dist = 0;
						$loc_count = 0;
						$halt_flag = 0;

						$halt_total =0;
						$overall_dist = $overall_dist + $sum_interval_dist;
						$interval_dist = ( (round($overall_dist,2)) / 4);
						
						$counter_common = 0;
						$counter_halt = 0;
						$counter_track = 0;

						$lat_ref_track_prev = "";   // TO PREVENT DUPLICATE LOCATION
						$lng_ref_track_prev = "";
						$lat_cr_track_prev ="";
						$lng_cr_track_prev ="";
						$firstdata_flag=0;
						$last_time=0;
						$distance=0;
						$halt_str="";
						for($j=0;$j<$innerSize;$j++)
						{
							$lat = $finalLatitudeArr[$i][$j];						
							$lng = $finalLongitudeArr[$i][$j];							
							$speed = $finalSpeedArr[$i][$j];
							$datetime=$finalDateTimeArr[$i][$j];							
							$xml_date=$datetime;
							
							if($firstdata_flag==0)
							{
								//echo "<br>FirstData";
								$halt_flag = 0;
								$firstdata_flag = 1;

								$lat_ref_halt = $lat;
								$lng_ref_halt = $lng;

								$lat_ref_track = $lat;
								$lng_ref_track = $lng;

								$datetime_ref_halt = $datetime;
								$datetime_ref_track = $datetime;                
								//echo "<br>datetime_ref=".$datetime_ref." ,dt0=".$datetime_tmp[0];                	
								$date_secs1 = strtotime($datetime_ref_halt);
								$date_secs1 = (double)($date_secs1 + $interval);  

								$date_start = $datetime_ref_halt;      // GET FIRST DETAIL
								$lat_start = $lat_ref_halt;
								$lng_start = $lng_ref_halt;                
								//echo "<br>lat_tmp=".$lat_tmp[0]." ,lat_tmp1=".$lat_tmp1[1];             	
							}           	
							//echo "<br>k2=".$k2."<br>";             	
							else
							{           
								//echo "<br>Next";               
								$lat_cr_halt = $lat;  
								$lng_cr_halt = $lng;

								$lat_cr_track = $lat;  
								$lng_cr_track = $lng;                  

								$datetime_cr_halt = $datetime;              		               											
								$date_secs2 = strtotime($datetime_cr_halt);

								$date_end = $datetime_cr_halt;         // GET LAST DETAIL
								//echo "<br>enddate=".$enddate;
								$lat_end = $lat_cr_halt;
								$lng_end = $lng_cr_halt;

								$datetime_cr_track = $datetime;             		
								//echo "<br>str=".$lat_ref_halt.", ".$lng_ref_halt.", ".$lat_cr_halt." ,".$lng_cr_halt;
								calculate_distance($lat_ref_halt, $lat_cr_halt, $lng_ref_halt, $lng_cr_halt, $distance);
								//echo "<br>dist=".$distance;

								$tmp_time_diff = (strtotime($datetime) - strtotime($last_time)) / 3600;
								$tmp_speed = $distance / $tmp_time_diff;

								$last_time = $datetime;                

								if($tmp_speed<3000 && $distance>0.1)
								{														
									$total_dist = (float) ($total_dist + $distance);
									//echo "<br>IN TOTAL DIST=".$total_dist;                                      
									$partial_dist = $partial_dist +  $distance;
								}

								if( ($distance > 0.200) || ($f== $total_lines-2) )
								{
									if ($halt_flag == 1)
									{
										$arrivale_time=$datetime_ref_halt;
										$starttime = strtotime($datetime_ref_halt);

										//$stoptime = strtotime($datetime_cr);  
										$stoptime = strtotime($datetime_cr_halt);
										$depature_time=$datetime_cr_halt;
										//echo "<br>".$starttime." ,".$stoptime;

										$halt_dur =  ($stoptime - $starttime)/3600;

										$halt_duration = round($halt_dur,2);										
										$total_min = $halt_duration * 60;            
										$total_min1 = $total_min;

										//echo "<br>toatal_min=".$total_min1."user-interval=".$user_interval;            
										$hr = (int)($total_min / 60);
										$minutes = $total_min % 60;										

										$hrs_min = $hr.".".$minutes;
										if( ($total_min1 >= $user_interval) || ($f== $total_lines-2))
										{
											$halt_detail = $arrivale_time." to ".$depature_time." -> ".$hrs_min;
											$halt_total = $halt_total + ($stoptime - $starttime);											
											if($counter_common>0)
											{
												if($counter_halt==0)
												{                            
													$halt_str = $halt_str.$halt_detail;
													$counter_halt = 1;
												}
												else
												{
													$halt_str = $halt_str."#".$halt_detail;
												}
											}  
											$date_secs1 = strtotime($datetime_cr_halt);
											$date_secs1 = (double)($date_secs1 + $interval); 				          						 
										}												
									} // IF HALT FLAG

									$lat_ref_halt = $lat_cr_halt;
									$lng_ref_halt = $lng_cr_halt;
									$datetime_ref_halt = $datetime_cr_halt;

									$halt_flag = 0;              			
								} // if dist > 0.200            		
								else
								{
									$halt_flag = 1;
								}	            		

								if( ($partial_dist >= $interval_dist) && ($loc_count<6) )
								{                													
									//echo "<br>in Track";                      
									$track_flag =1;

									if( (round($lat_ref_track,3) == round($lat_ref_track_prev,3)) && (round($lng_ref_track,3) == round($lng_ref_track_prev,3)) && (round($lat_cr_track,3) == round($lat_cr_track_prev,3)) && (round($lng_cr_track,3) == round($lng_cr_track_prev,3)) )
									{
										$track_flag = 0;                       
									}
									
									//echo "track_flag=".$track_flag."<br>";

									if($track_flag)
									{
										$track_detail = $lat_ref_track.",".$lng_ref_track." ".$lat_cr_track.",".$lng_cr_track;

										/*if($counter==0)
										{
										$track_str = $track_str.$track_detail;
										}*/
										if($counter_common>0)
										{
											if($counter_track==0)
											{
												$track_str = $track_str.$track_detail;
												$counter_track = 1;
											}
											else
											{
												$track_str = $track_str."#".$track_detail;
											}
										}
									}	

									$lat_ref_track_prev = $lat_ref_track;
									$lng_ref_track_prev = $lng_ref_track;
									$lat_cr_track_prev = $lat_cr_track;
									$lng_cr_track_prev = $lng_cr_track;                                                            

									$partial_dist = 0;
									$loc_count++;
									//echo "<br>track_str=".$track_str;				          						 			                                                       			
								}
								//$lat_ref_halt = $lat;
								//$lng_ref_halt = $lng;
								$counter_common++;
							}		// else closed	
						}
						$time1 = strtotime($date_start);
						$time2 = strtotime($date_end);
						$diff_total = $time2 - $time1;
						$diff_time =  ($diff_total)/3600;
						$hr = (int)($diff_time / 60);
						$minutes = $diff_time % 60;
						$halt_time =  ($halt_total)/3600;
						$hr = (int)($halt_time / 60);
						$minutes = $halt_time % 60;
						$journey_diff = ($diff_total - $halt_total);
						$journey_time =  ($journey_diff)/3600;
						//echo "<br>JT:".$journey_diff;
						$journey_time = round($journey_time,2);										
						$total_min = $journey_time * 60;     
						$hr = (int)($total_min / 60);
						$minutes = $total_min % 60;										  
						$hrs_min = $hr.".".$minutes;        // journey time
						$overall_dist = round($overall_dist,2);
						
						if($halt_str == "")
						{
							$halt_str = "No Halt Found";
						}
						
						
						if($track_str == "")
						{
							$track_str = $lat_ref_track.",".$lng_ref_track." ".$lat_cr_track.",".$lng_cr_track;
						}
						
						//echo "track_str=".$track_str."<br>";
						//$summary_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" startdate=\"".$date_start."\" enddate=\"".$date_end."\" startlat=\"".$lat_start."\" startlng=\"".$lng_start."\" endlat=\"".$lat_end."\" endlng=\"".$lng_end."\" total_dist=\"".$overall_dist."\" journey_time=\"".$hrs_min."\" halt_str=\"".$halt_str."\" track_str=\"".$track_str."\"/>";						          						
						//echo"<textarea>".$summary_data."</textarea>";
						$lt1 = $lat_start;
						$lng1 = $lng_start;
						$alt1 = "-";
						
						$lat_start="";
						$lng_start="";
						$place="";
						
						$landmark="";
						get_landmark($lt1,$lng1,$landmark);    // CALL LANDMARK FUNCTION							
						$place = $landmark;						
						if($place=="")
						{
							get_location($lt1,$lng1,$alt1,$place);    // CALL GOOGLE LOCATION FUNCTION
						}						
						$start_loc=$place;						
						$lt1 = $lat_end;
						$lng1 = $lng_end;
						$alt1 = "-";						
						$lat_end="";
						$lng_end="";
						
						$landmark="";
						$place="";
						get_landmark($lt1,$lng1,$landmark);    // CALL LANDMARK FUNCTION
						$place = $landmark;
						if($place=="")
						{
							get_location($lt1,$lng1,$alt1,$place);    // CALL GOOGLE LOCATION FUNCTION
						}
						//echo "P:".$place;    
						$end_loc = $place;
						
						 $halt_detail_pdf = $halt_str; 
						$halt_detail = str_replace("to","<font color=green>to</font>",$halt_str);
						$halt_detail = str_replace("->","<font color=red>-></font>",$halt_detail);
						$halt_detail = str_replace("#","<br>",$halt_detail);
						$halt_str="";
						
						$track1 = explode("#",$track_str);
						$track_str_tmp ="";    
						for($za=0;$za<sizeof($track1);$za++)
						{
							$track2 = explode(" ",$track1[$za]);
							for($zb=0;$zb<sizeof($track2);$zb++)
							{
								$track3 = explode(",",$track2[$zb]);

								$lt1 = $track3[0];
								$lng1 = $track3[1];
								$alt1="-";
								$landmark="";
								get_landmark($lt1,$lng1,$landmark);    // CALL LANDMARK FUNCTION
								$place = $landmark;

								if($place=="")
								{
									get_location($lt1,$lng1,$alt1,$place);    // CALL GOOGLE LOCATION FUNCTION
								}  		

								$place2 = $place;

								//echo "lt1=".$lt1." ,lng1=".$lng1." pl=".$place2;        
								if($zb==(sizeof($track1)-1))
								{
									//echo "<br>end";
									$track_str_tmp = $track_str_tmp.$place2;
								}
								else
								{
									//echo "<br>else";
									$track_str_tmp = $track_str_tmp.$place2."->";
								}                                       
							}
						} 
						$lt1="";
						$lng1="";
						$atl1="-";
						$track_str_tmp = substr($track_str_tmp, 0, strlen($track_str_tmp)-2); 
						$location_track = str_replace("->","<font color=red>-></font>",$track_str_tmp);	
    	
						$sno = $i+1;            
						echo '<tr valign="top">';
						echo '<td class="text" align="left">'.$sno.'</td>';
						echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$pdfi][$i][$tagArr[0]]\">";
						$csv_string = $csv_string.$sno;	
						
						echo '<td class="text" align="left">'.$finalVNameArr[$i].'</td>';						
						echo"<input TYPE=\"hidden\" VALUE=\"$finalVNameArr[$i]\" NAME=\"temp[$pdfi][$i][$tagArr[1]]\">";
						$csv_string = $csv_string.$finalVNameArr[$i];
						
						echo '<td class="text" align="left">'.$vserial[$i].'</td>';						
						echo"<input TYPE=\"hidden\" VALUE=\"$vserial[$i]\" NAME=\"temp[$pdfi][$i][$tagArr[2]]\">";
						$csv_string = $csv_string.$vserial[$i];
						
						echo '<td class="text" align="left">'.$date_start.'</td>';
						echo"<input TYPE=\"hidden\" VALUE=\"$date_start\" NAME=\"temp[$pdfi][$pdfi][$tagArr[3]]\">";
						$csv_string = $csv_string.$date_start;
						
						echo '<td class="text" align="left">'.$date_end.'</td>';
						echo"<input TYPE=\"hidden\" VALUE=\"$date_end\" NAME=\"temp[$pdfi][$i][$tagArr[4]]\">";
						$csv_string = $csv_string.$date_end;
						
						echo '<td class="text" align="left">'.$start_loc.'</td>';
						echo"<input TYPE=\"hidden\" VALUE=\"$start_loc\" NAME=\"temp[$pdfi][$pdfi][$tagArr[5]]\">";
						$csv_string = $csv_string.$start_loc;
						
						echo '<td class="text" align="left">'.$end_loc.'</td>';
						echo"<input TYPE=\"hidden\" VALUE=\"$end_loc\" NAME=\"temp[$pdfi][$i][$tagArr[6]]\">";
						$csv_string = $csv_string.$end_loc;
						$tmpSumDist=round($sum_interval_dist,2);
						echo '<td class="text" align="left">'.$tmpSumDist.'</td>';
						echo"<input TYPE=\"hidden\" VALUE=\"$tmpSumDist\" NAME=\"temp[$pdfi][$i][$tagArr[7]]\">";
						$csv_string = $csv_string.$tmpSumDist;
						
						echo '<td class="text" align="left">'.$journey_time.'</td>';
						echo"<input TYPE=\"hidden\" VALUE=\"$journey_time\" NAME=\"temp[$pdfi][$pdfi][$tagArr[8]]\">";
						$csv_string = $csv_string.$journey_time;
						
						echo '<td class="text" align="left">'.$halt_detail.'</td>';
						echo"<input TYPE=\"hidden\" VALUE=\"$halt_detail\" NAME=\"temp[$pdfi][$i][$tagArr[9]]\">";
						$csv_string = $csv_string.$halt_detail;
						
						echo '<td class="text" align="left">'.$location_track.'</td>'; 
						echo"<input TYPE=\"hidden\" VALUE=\"$location_track\" NAME=\"temp[$pdfi][$i][$tagArr[10]]\">";
						$csv_string = $csv_string.$location_track;						
						echo'</tr>';
					}
					echo"</table>
					</div>
				</center>"; 
	echo'<input TYPE="hidden" VALUE="full data" NAME="csv_type">';
	echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
	echo'<br><center>
	<input type="button" onclick="javascript:report_pdf_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;
	<input type="button" onclick="javascript:report_pdf_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
	<!--<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;-->
	</form>';						
}
else
{
	print"<center><FONT color=\"Red\" size=2><strong>No ".$tagArr[11]." Record Found</strong></font></center>";
}


echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';	
?>							 					
