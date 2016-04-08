<?php
	//error_reporting(-1);
	//ini_set('display_errors', 'On');
	set_time_limit(3000);	
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
	
	$DEBUG =0;
	$month = $_POST['month'];
	$year = $_POST['year'];
	$daystmp = $_POST['days'];
        
	//echo "Month=".$month." ,Year=".$year.", Day=".$daystmp;
	/*$month = "01";
	$year = "2015";
	$daystmp = "1";*/

	$lastday=date('t',mktime(0,0,0,$month,1,$year)); 	//get last day OR echo cal_days_in_month(CAL_GREGORIAN, 06, 
	$device_str = $_POST['vehicleserial'];
	//$device_str = "359231030125239";        
	$vserial = explode(':',$device_str);
	$vsize=count($vserial);

	for($rti=0;$rti<sizeof($reportType);$rti++)
	{	
		if($reportType[$rti]=="speed")
		{
			$speed_flag=1;
		}		
	}	
	if($daystmp<=9)
	{
		$date = $year."-".$month."-0".$daystmp;
	}
	else
	{
		$date = $year."-".$month."-".$daystmp;
	}
	
	$datefrom=$date;
	$dateto=$date;
	
	$date1 = $date." 00:00:00";
	$date2 = $date." 23:59:59"; 
       // $date2 = $date." 11:59:59"; 
	
	$sortBy='h';
	$firstDataFlag=0;
	$endDateTS=strtotime($date2);
		
	$userInterval = "0";
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
		//echo "<br>Before-getLastSortedDate";
		$LastSortedDate = getLastSortedDate($vserial[$i],$datefrom,$dateto);
		//echo "<br>After-LastSortedDate=".$LastSortedDate;
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

		//echo "udt1=".$UnSortedDataObject->deviceDatetime[0]."<br>";
		//echo "udt2=".$UnSortedDataObject->deviceDatetime[1]."<br>";	
		//echo "udt1=".$UnSortedDataObject->speedData[0]."<br>";
		//echo "udt2=".$UnSortedDataObject->speedData[1]."<br>";
		//echo "<br><br>";

		if(count($SortedDataObject->deviceDatetime)>0)
		{
			$prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
			for($obi=0;$obi<$prevSortedSize;$obi++)
			{			
				$finalDateTimeArr[$i][$dataCnt]=$SortedDataObject->deviceDatetime[$obi];
				$finalLatitudeArr[$i][$dataCnt]=$SortedDataObject->latitudeData[$obi];
				$finalLongitudeArr[$i][$dataCnt]=$SortedDataObject->longitudeData[$obi];
				$finalSpeedArr[$i][$dataCnt]=$SortedDataObject->speedData[$obi];				
				$dataCnt++;
			}
		}
		//echo "<br>UnsortedDataObj=".count($UnSortedDataObject->deviceDatetime);
		if(count($UnSortedDataObject->deviceDatetime)>0)
		{
			$sortObjTmp=sortData($UnSortedDataObject,$sortBy,$parameterizeData);
			//var_dump($sortObjTmp);
			$sortedSize=sizeof($sortObjTmp->deviceDatetime);
			for($obi=0;$obi<$sortedSize;$obi++)
			{				
				$finalDateTimeArr[$i][$dataCnt]=$sortObjTmp->deviceDatetime[$obi];	
				$finalLatitudeArr[$i][$dataCnt]=$sortObjTmp->latitudeData[$obi];
				$finalLongitudeArr[$i][$dataCnt]=$sortObjTmp->longitudeData[$obi];	
				$finalSpeedArr[$i][$dataCnt]=$sortObjTmp->speedData[$obi];
				$dataCnt++;
			}  
		}
		$SortedDataObject=null;			
		$sortObjTmp=null;
		$UnsortedDataObject =null;

    }
	$o_cassandra->close();
    //echo "<br>After Storage2";
	
	$speed_flag=0;
	$timetmp1 = 0;
	$breakflag = 0;

    $parameterizeData=null;	
	for($i=0;$i<$vsize;$i++)
	{
		$fix_tmp = 1;
		$CurrentLat = 0.0;
		$CurrentLong = 0.0;
		$LastLat = 0.0;
		$LastLong = 0.0;
		$firstData = 0;
		$distance =0.0;
		$linetowrite="";
		$firstdata_flag =0;
		$breakflag = 0;
		$j = 0;
		$total_dist = 0; 
		$monthly_distance = null;
		$avg_speed = null;
		$max_speed = null;

		$total_avg_speed = null;
		$total_max_speed = null;	

		//echo "<br>Datesize=".$date_size;
		$travel_dist = 0.0;
		$total_travel_dist = 0.0;
		$run_start_flag = 0;
		$run_stop_flag = 0;
		$innerSize=sizeof($finalDateTimeArr[$i]);
		for($j=0;$j<$innerSize;$j++)
		{
			$lat = $finalLatitudeArr[$i][$j];
			$lng = $finalLongitudeArr[$i][$j];
			$speed = $finalSpeedArr[$i][$j];
			$datetime=$finalDateTimeArr[$i][$j];
			//echo "<br>first=".$firstdata_flag;                                        
			if($firstdata_flag==0)
			{
				//echo "<br>FirstData";
				$firstdata_flag = 1;
				$lat1 = $lat;
				$lng1 = $lng;
				$last_time1 = $datetime; 
				$latlast = $lat;
				$lnglast =  $lng;
				//####### SPEED CONDITION
				///////// FIXING SPEED PROBLEM ///////////   
				if($speed_flag==1)
				{	
					$speed_str = $speed;
					if($speed_str > 200)
					{
						$speed_str =0;   
					}
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
					$speed_arr[] = $speed;
					$datetimeSwitch1=explode(" ",$datetime);
				}
			//########### CLOSING SPEED CONDITION						
			} 
			else
			{ 
				$time2 = $datetime;	
				$vserial=$vehicle_serial;               
				$lat2 = $lat;
				$lng2 = $lng;
				calculate_distance($lat1, $lat2, $lng1, $lng2, $distance);
				if($distance>2000)
				{
					$distance=0;
					$lat1 = $lat2;
					$lng1 = $lng2;
				}
				//echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance;
				$tmp_time_diff1 = ((double) (strtotime($datetime) - strtotime($last_time1))) / 3600;
				calculate_distance($latlast, $lat2, $lnglast, $lng2, $distance1);
				if($tmp_time_diff1>0)
				{
					$tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
					$last_time1 = $datetime;
					$latlast = $lat2;
					$lnglast =  $lng2;        					
				}
				$tmp_time_diff =((double)( strtotime($datetime) - strtotime($last_time))) / 3600;
				//if($tmp_speed <3000 && $distance>0.1)
				//echo "\nTmpSpeed=".$tmp_speed." ,distance=".$distance." ,tmp_time_diff=".$tmp_time_diff;
				if($tmp_speed<500 && $distance>0.1 && $tmp_time_diff>0)
				{		              
					$daily_dist= (float) ($daily_dist + $distance);	
					if($speed>1)
					{
						$travel_dist += $distance;
					}							 																				
					$lat1 = $lat2;
					$lng1 = $lng2;
					$last_time = $datetime;
					$lastdatalat=$lat2;
					$lastdatalng=$lng2;
					$lastdatetime=$datetime;							  
				}
				//###### DISTANCE DATA CLOSED
				//######## SPEED DATA OPEN
				///////// FIXING SPEED PROBLEM ///////////  
				if($speed_flag==1)
				{
					$speed_str = $speed;
					if($speed_str > 200)
					{
						$speed_str =0;
					}									  
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
					$speed_arr[] = $speed;
					$max_speed = max($speed_arr);
					$max_speed = round($max_speed,2);
					//echo "<br>Distance=".$distance;
					if($speed>1 && !$run_start_flag && !$run_stop_flag && $distance > 0.1)
					{
						//echo "<br>RunStart";
						$run_start_flag = 1;
						$runtime_start = $datetime;
					}
					//else if(($speed<1) &&($run_start_flag && !$run_stop_flag) && (($distance<0.1) || (($i==($date_size-1)) && ($f==$total_lines-10))))
					else if(($speed<1) &&($run_start_flag && !$run_stop_flag) && (($distance<0.1)))
					{
						//echo "<br>IN StopFlag";
						$run_stop_flag = 1;
						$runtime_stop = $datetime;
					}
					else if($run_start_flag && $run_stop_flag)
					{								
						$runtime = strtotime($runtime_stop) - strtotime($runtime_start);
						//echo "<br>Runtime=".$runtime;
						$total_runtime = $total_runtime + $runtime;
						//echo "<br>Total_runtime=".$total_runtime;
						$run_start_flag = 0;
						$run_stop_flag = 0;
					}
				}
				$datetimeSwitch2=explode(" ",$datetime);
				//########## SPEED CLOSED
			}			
			if($datetimeSwitch2[0]!=$datetimeSwitch1[0])
			{
				$datetimeSwitch1=$datetimeSwitch2;
				$monthly_distance += $daily_dist;
				$total_travel_dist += $travel_dist;
				$daily_dist = 0;
			}
		}
		if($speed_flag==1)
		{
			$avg_speed = ($total_travel_dist / $total_runtime)*3600; 
			$avg_speed = round($avg_speed,2);
			
			if( ($avg_speed > $max_speed) && ($max_speed > 2.0) )
			{
				$avg_speed = $max_speed - 2;
			}              
			else if( ($avg_speed > $max_speed) && ($max_speed > 0.2) && ($max_speed <= 2.0) )
			{								
				$avg_speed = $max_speed - 0.2;
			}							              							
			
			//echo "<br>AVG_SPEED=".$avg_speed;
			if($avg_speed<150)
			{
				$monthly_avg_speed = $avg_speed;
			}							
			$monthly_max_speed = max($speed_arr);
			$monthly_avg_speed = round($monthly_avg_speed,2);
			$monthly_max_speed = round($monthly_max_speed,2);
		}
		else
		{
			$monthly_avg_speed="-";
			$monthly_max_speed="-";
		}
		
		$monthly_distance = round($monthly_distance,1);		
		$vname[] = $finalVNameArr[$i];
		$imei[] = $vserial[$i];
		$distanceDisplayArr[] = $monthly_distance;
		$avgSpeedDisplayArr[] = $monthly_avg_speed;
		$maxSpeedDisplayArr[] = $monthly_max_speed;		
	}
   
   
   
   
   
   $m1=date('M',mktime(0,0,0,$month,1));
if($breakflag==1)
 echo "<br><center><font color=red>Data too large please select less duration/days/vehicle</font></center><br>";

echo'<br>';
//echo "<br>Date1=".$date1." ,Date2=".$date2;

  report_title("Daily Distance",$date1,$date2);
  $title="Daily Distance Report :  (".$daystmp."-".$m1."-".$year."  )";
  echo'<table align="center">
			<tr>
				<td class="text" align="center">
					<b>'.$title.'</b> 
					<div style="height:8px;"></div>
				</td>
			</tr>
		</table>';
echo'<center>
		<div style="overflow: auto;height: 350px; width: 620px;" align="center">';			                      
		$xml_path = $xmltowrite;
		$j=-1;
		$k=0;
		$final_maxspeed_tmp=0;
		$endtable=0;
		
	echo'
	<table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
	<tr>
		<td class="text" align="left"><b>SNo</b></td>
		<td class="text" align="left"><b>Vehicle</b></td>
		<td class="text" align="left"><b>Date</b></td>
		<!--<td class="text" align="left"><b>IMEI</b></td>-->
		<td class="text" align="left"><b>Distance (km)</b></td>';
	if($speed_flag==1)
	{
		echo'<td class="text" align="left"><b>Average Speed (km/hr)</b></td>
		<td class="text" align="left"><b>Max Speed (km/hr)</b></td>';
	}
	echo'</tr>'; 

	for($i=0;$i<sizeof($imei);$i++)
	{       
        $sno=$i+1;
		echo '<tr>';
		echo '<td class="text" align="left"><b>'.$sno.'</b></td>';
		echo '<td class="text" align="left"><b>'.$vname[$i].'</b></td>';
		echo '<td class="text" align="left"><b>'.$date.'</b></td>';
		//echo '<td class="text" align="left"><b>'.$imei[$i].'</b></td>';
		echo '<td class="text" align="left"><b>'.$distanceDisplayArr[$i].'</b></td>';
		if($speed_flag==1)
		{
			echo '<td class="text" align="left"><b>'.$avgSpeedDisplayArr[$i].'</b></td>';
			echo '<td class="text" align="left"><b>'.$maxSpeedDisplayArr[$i].'</b></td> ';
		}
		$sno++;      		
   }  
   echo"</div>
  </table>";  
   echo'<br>';   

   $vsize = sizeof($imei);
   

	$csv_string = "";   
	$csv_string = $csv_string.$title."\n";
	if($speed_flag==1)
	{
		//$csv_string = $csv_string."SNo,Vehicle,IMEI,Distance (km),Average Speed (km/hr),Max Speed (km/hr)\n";
		$csv_string = $csv_string."SNo,Vehicle,Date,Distance (km),Average Speed (km/hr),Max Speed (km/hr)\n";
	}
	else
	{
		//$csv_string = $csv_string."SNo,Vehicle,IMEI,Monthly Distance (km)\n";
		$csv_string = $csv_string."SNo,Vehicle,Date,Distance (km)\n";
	}
	echo'<form method ="post" target="_blank">';	
 echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";	
	for($i=0;$i<sizeof($imei);$i++)
	{
		$sno=$i+1;
		
		echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$i][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$vname[$i]\" NAME=\"temp[$i][Vehicle]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date\" NAME=\"temp[$i][Date]\">";
		//echo"<input TYPE=\"hidden\" VALUE=\"$imei[$i]\" NAME=\"temp[$i][IMEI]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$distanceDisplayArr[$i]\" NAME=\"temp[$i][Distance (km)]\">";
		if($speed_flag==1)
		{
			echo"<input TYPE=\"hidden\" VALUE=\"$avgSpeedDisplayArr[$i]\" NAME=\"temp[$i][Average Speed (km/hr)]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$maxSpeedDisplayArr[$i]\" NAME=\"temp[$i][Max Speed (km/hr)]\">";
		//	$csv_string=$csv_string.$sno.",".$vname[$i].",".$imei[$i].",".$distance[$i].",".$avg_speed[$i].",".$max_speed[$i]."\n";
			$csv_string=$csv_string.$sno.",".$vname[$i].",".$date.",".$distanceDisplayArr[$i].",".$avgSpeedDisplayArr[$i].",".$maxSpeedDisplayArr[$i]."\n";
		}
		else
		{
			//$csv_string=$csv_string.$sno.",".$vname[$i].",".$imei[$i].",".$distance[$i]."\n";
			$csv_string=$csv_string.$sno.",".$vname[$i].",".$date.",".$distanceDisplayArr[$i]."\n";
		}
		//echo "<br>CSVString=".$csv_string;
		$sno++;
	}

    echo'
	<center>
	<input TYPE="hidden" VALUE="vehicle" NAME="csv_type">
	<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">			
	<input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size='.$vsize.'\');" value="Get PDF" class="noprint">
	&nbsp;
	<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">
	&nbsp;
	</center>
</form>';             

echo '</center>'; 
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';              							
?>
						
