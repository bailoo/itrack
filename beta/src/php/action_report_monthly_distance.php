<?php
	//error_reporting(-1);
	//ini_set('display_errors', 'On');
	set_time_limit(300);	
	date_default_timezone_set("Asia/Kolkata");
	include_once("main_vehicle_information_1.php");
	include_once('Hierarchy.php');
	$root=$_SESSION["root"];
	include_once('util_session_variable.php');
	include_once('xmlParameters.php');
	include_once("report_title.php");
	include_once('parameterizeData.php');
	include_once('data.php');
	include_once("sortXmlData.php");
	include_once("getXmlData.php");
	include_once("calculate_distance.php");
	
	$DEBUG = 0;
	$month = $_POST['month'];
	$year = $_POST['year'];
	
	//echo "<br>year=".$year." ,month=".$month;

	$lastday=date('t',mktime(0,0,0,$month,1,$year));		// get last day
	$device_str = $_POST['vehicleserial'];
	$vserial = $device_str;
	$day_opt1 = $_POST['day_opt'];
	$daystmp = $_POST['days'];
	$days1 = explode(':',$daystmp);   
	$daysize = sizeof($days1);
	$timetmp1 = 0;
	$breakflag = 0;
	
	$vehicle_info=get_vehicle_info($root,$vserial);
	$vehicle_detail_local=explode(",",$vehicle_info);	

	
	$sortBy='h';
	$firstDataFlag=0;
	
	$dataCnt=0;	
	$userInterval = "0";
	$requiredData="All";
	
	$parameterizeData=new parameterizeData();
	$ioFoundFlag=0;
	
	$parameterizeData->latitude="d";
	$parameterizeData->longitude="e";
	$tdi=0; /// two d increament
	//echo "day opt=".$day_opt1."<br>";
	
	if($day_opt==2)
	{
		$lastday=$daysize;
	}
	
	$finalVNameArr[$tdi]=$vehicle_detail_local[0];
	//echo "<br>DAYOPT,lastday=".$lastday;
	$finalEachDayDataArr=array();
	$dataCnt=0;
	for($j=1;$j<=$lastday;$j++)
	{
		$dataFoundFlag=0;
		if($day_opt==1)
		{
			$k=$j;
		}
		else if($day_opt==2)
		{
			$k=$days1[$j-1];
			$todayInc=$j-1;
		}
		////////////// daily Query			
		if($k<=9)
		{
	
			if($day_opt==1)
			{
				$date = $year."-".$month."-0".$k;
			}
			else if($day_opt==2)
			{
				
				$date = $year."-".$month."-0".$days1[$todayInc];
				//echo "date1=".$date."<br>";
			}
		}
		else
		{
			//echo "in else<br>";
			if($day_opt==1)
			{
				$date = $year."-".$month."-".$k;
			}
			else if($day_opt==2)
			{
				//echo "day=".$days1[$k]."<br>";				
				$date = $year."-".$month."-".$days1[$todayInc];
			}
			
		}
		
		$datefrom=$date;
		$dateto=$date;
		
		$date1 = $date." 00:00:00";
		$date2 = $date." 23:59:59";	

		$endDateTS=strtotime($date2);
		//echo "<br>A:date1=".$date1." ,date2=".$date2;
		$LastSortedDate = getLastSortedDate($vserial,$datefrom,$dateto);
		$SortedDataObject=new data();
		$UnSortedDataObject=new data();
		
		if(($LastSortedDate+24*60*60)>=$endDateTS) //All sorted data
		{	
			//echo "in if1";
			$type="sorted";
			readFileXml($vserial,$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
		}
		else if($LastSortedDate==null) //All Unsorted data
		{
			//echo "in if2";
			$type="unSorted";
			//echo"st=".$datefrom."ed=".$dateto."<br>";
			readFileXml($vserial,$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
		}
		else //Partially Sorted data
		{
			$LastSDate=date("Y-m-d",$LastSortedDate+24*60*60);
			//echo "in else";
			$type="sorted";					
			readFileXml($vserial,$date1,$date2,$datefrom,$LastSDate,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
		
			$type="unSorted";
			readFileXml($vserial,$date1,$date2,$LastSDate,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
		}		
	
		/*echo "udt1=".$UnSortedDataObject->deviceDatetime[0]."<br>";
		echo "udt2=".$UnSortedDataObject->deviceDatetime[1]."<br>";
	
		echo "dt3=".$SortedDataObject->deviceDatetime[2]."<br>";
		echo "dt4=".$SortedDataObject->deviceDatetime[3]."<br>";*/
		if(count($SortedDataObject->deviceDatetime)>0)
		{
			$prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
			for($obi=0;$obi<$prevSortedSize;$obi++)
			{			
				$finalDateTimeArr[$dataCnt][]=$SortedDataObject->deviceDatetime[$obi];
				$finalLatitudeArr[$dataCnt][]=$SortedDataObject->latitudeData[$obi];
				$finalLongitudeArr[$dataCnt][]=$SortedDataObject->longitudeData[$obi];		
				$dataFoundFlag=1;
			}
		}
		if(count($UnSortedDataObject->deviceDatetime)>0)
		{
			$sortObjTmp=sortData($UnSortedDataObject,$sortBy,$parameterizeData);
			$sortedSize=sizeof($sortObjTmp->deviceDatetime);
			/*echo "dt1=".$sortObjTmp->deviceDatetime[0]."<br>";
			echo "dt2=".$sortObjTmp->deviceDatetime[1]."<br>";
			echo "dt1=".$sortObjTmp->latitudeData[0]."<br>";
			echo "dt2=".$sortObjTmp->latitudeData[1]."<br>";*/
			for($obi=0;$obi<$sortedSize;$obi++)
			{				
				$finalDateTimeArr[$dataCnt][]=$sortObjTmp->deviceDatetime[$obi];	
				$finalLatitudeArr[$dataCnt][]=$sortObjTmp->latitudeData[$obi];
				$finalLongitudeArr[$dataCnt][]=$sortObjTmp->longitudeData[$obi];
				$dataFoundFlag=1;
			}
		}		
		$SortedDataObject=null;			
		$sortObjTmp=null;
		$UnsortedDataObject =null;
		
		if($dataFoundFlag==1)
		{
			$dataCnt++;
		}
		//echo "date1=".$date1."date2=".$date2."<br>";			
	}
	$parameterizeData=null;
	
$reportTitle="Monthly Distance Report ";
$displayFormat="Daily Distance (km)";
	
//print_r($finalLatitudeArr);
//echo "tdi=".$tdi."<br>";
if(count($finalLatitudeArr)>0)
{
$csv_string = "";
echo'<form  name="text_data_report" method="post" target="_blank">'.
		report_title($reportTitle,$date1,$date2).'
			<center>
				<div style="overflow: auto;height: 300px; width: 620px;">';
					//$reportSize=sizeof($finalVNameArr);
					//echo "vsize=".$dataCnt."<br>";
					$sno=1;
					$pdfi=0;
				    $sum_dailydist =0;
					for($i=0;$i<$dataCnt;$i++)
					{
						$innerSize=0;
						$innerSize=sizeof($finalDateTimeArr[$i]);
						//echo "innerSize=".$innerSize."<br>";
						/*for($j=0;$j<$innerSize;$j++)
						{
							echo "vehcileName=".$finalDateTimeArr[$i][$j]."<br>";
						}*/
						if($i==0)
						{
							$csvtitle1=$reportTitle." Report :- ".$vehicle_detail_local[0]." 
										(".$vserial." )  DateTime :".$date1." - ".$date2." )";
								echo"<input TYPE=\"hidden\" VALUE=\"$csvtitle1\" NAME=\"title[$i]\">";
								$csv_string = $csv_string.$csvtitle1."\n";
								$csv_string = $csv_string."SNo,Date,".$reportTitle."\n";
								$title=$finalVNameArr[$i]." &nbsp;
								
							<font color=red>
								(".$vserial.")
							</font>";
							echo'<br>
							<table align="center">
								<tr>
									<td class="text" align="center">
										<b>'.$title.'</b> 
										<div style="height:8px;">
										</div>
									</td>
								</tr>
							</table>
							<table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
								<tr>
									<td class="text" align="left">
										<b>SNo</b>
									</td>
									<td class="text" align="left">
										<b>Date</b>
									</td>											
									<td class="text" align="left">
										<b>'.$reportTitle.'</b>
									</td>								
								</tr>';  
						}
						$daily_dist=0;
						$distance=0.0;
						$distance1=0.0;
						$tmp_time_diff=0;
						$tmp_time_diff1=0;
						$prevDateFlag="OFF";
						$lat_prev_date="";
						$lng_prev_date="";		
						$last_time1_prev=""; 
						$latlast_prev="";
						$lnglast_prev="";
						$lat="";
						$lng="";
						$datetime="";
						$last_time=null;
						$tmp_speed=0;
						for($j=0;$j<$innerSize;$j++)
						{
							$lat=$finalLatitudeArr[$i][$j];
							$lng = $finalLongitudeArr[$i][$j];
							$datetime=$finalDateTimeArr[$i][$j];
							
							if($j==0)
							{
								if($prevDateFlag=="OFF")
								{
									//echo "in if";
									$lat1 = $lat;
									$lng1 = $lng;
									$last_time1 = $datetime; 
									$latlast = $lat;
									$lnglast =  $lng; 
								}									
								else if($prevDateFlag=="ON")
								{
									//echo "in else if";
									$lat1 = $lat_prev_date;
									$lng1 = $lng_prev_date;
									$last_time1 = $last_time1_prev; 
									$latlast = $latlast_prev;
									$lnglast =  $lnglast_prev;
									//echo "latlast=".$latlast."lnglast=".$lnglast."last_time1=".$last_time1."<br><br>";
								} 
							}
							else
							{								                                   
								$lat2 = $lat;
								$lng2 = $lng; 
								calculate_distance($lat1, $lat2, $lng1, $lng2, $distance);
								//echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance;
								if($distance>2000)
								{
									$distance=0;
									$lat1 = $lat2;
									$lng1 = $lng2;
								}		
								$tmp_time_diff1 = ((double) (strtotime($datetime) - strtotime($last_time1))) / 3600;

								calculate_distance($latlast, $lat2, $lnglast, $lng2, $distance1);

								if($tmp_time_diff1>0)
								{
									$tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
									$last_time1 = $datetime;
									$latlast = $lat2;
									$lnglast =  $lng2;        					
								}
								$tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time) )) / 3600;

								//if($tmp_speed <3000 && $distance>0.1)
								//echo "\nTmpSpeed=".$tmp_speed." ,distance=".$distance." ,tmp_time_diff=".$tmp_time_diff;

								if($tmp_speed<500 && $distance>0.1 && $tmp_time_diff>0)
								{		              
									$daily_dist= (float) ($daily_dist + $distance);																															
									$lat1 = $lat2;
									$lng1 = $lng2;
									$last_time = $datetime;
									$lastdatalat=$lat2;
									$lastdatalng=$lng2;
									$lastdatetime=$datetime;
								}							
							}						
						}
						$lat_prev_date=$lastdatalat;
						$lng_prev_date=$lastdatalng;			
						$last_time1_prev = $lastdatetime; 
						$latlast_prev = $lastdatalat;
						$lnglast_prev =  $lastdatalng;
						if($daily_dist!=0)
						{
							$prevDateFlag="ON";
						}
						//echo "lat2=".$lat_prev_date."datetime=".$last_time1_prev."<br>";
						//echo "prevDateFlag=".$prevDateFlag."<br>";
						if($daily_dist==0)
						{
							$latlast_prev = "";
							$lnglast_prev =  "";
							$prevDateFlag="OFF";
						}
						$daily_dist = round($daily_dist,2);
						$onlyDate=explode(" ",$finalDateTimeArr[$i][$j-1]);
						
					echo'<tr>
							<td class="text" align="left" width="4%">
								<b>'.$sno.'</b>
							</td>';										
							echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$pdfi][$sno][SNo]\">";
							$csv_string = $csv_string.$sno;	
							
							echo'<td class="text" align="left">
									'.$onlyDate[0].'
								</td>';									
							echo"<input TYPE=\"hidden\" VALUE=\"$onlyDate[0]\" NAME=\"temp[$pdfi][$sno][Date]\">";
							$csv_string = $csv_string.','.$onlyDate[0];										
							
							$finalDistance=round($daily_dist,2);
							$sum_dailydist=$sum_dailydist+$daily_dist;
							echo'<td class="text" align="left">
									'.$finalDistance.'
								</td>';	
							echo"<input TYPE=\"hidden\" VALUE=\"$finalDistance\" NAME=\"temp[$pdfi][$sno][$displayFormat]\">";
							$csv_string = $csv_string.','.$finalDistance;
						echo'</tr>';
							$csv_string=$csv_string."\n";
						$sno++;				
					}
				
					  echo'<tr style="height:20px;background-color:lightgrey">
							<td class="text">
								<strong>Total<strong>&nbsp;
							</td>
							<td class="text">
								<strong></strong>
							</td>
							<td class="text">
								<font color="red">
									<strong>'.$sum_dailydist.'</strong>
								</font>
							</td>
						</tr>';
					echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$pdfi][$sno][SNo]\">
					<input TYPE=\"hidden\" VALUE=\"-\" NAME=\"temp[$pdfi][$sno][Date]\">
					<input TYPE=\"hidden\" VALUE=\"$sum_dailydist\" NAME=\"temp[$pdfi][$sno][$displayFormat]\">";
      $csv_string = $csv_string.'Total,-,'.$sum_dailydist."\n"; 
				echo'</table>
				</div>
			</center>
			<input TYPE="hidden" VALUE="full data" NAME="csv_type">
			<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">
			<br><center>
				<input type="button" onclick="javascript:report_pdf_csv(\'src/php/report_getpdf_type4.php?size='.$dataCnt.'\');" value="Get PDF" class="noprint">&nbsp;
				<input type="button" onclick="javascript:report_pdf_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
				<!--<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;-->
		</form>';						
}
else
{
	print"<center><FONT color=\"Red\" size=2><strong>No ".$reportTitle." Record Found</strong></font></center>";
}


echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';	
?>							 					
