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
    
    $reportType=explode(",",$_POST['reportType']);
    $speed_flag=0;
    $timetmp1 = 0;
    $breakflag = 0;
    
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
    
    get_All_Dates($datefrom, $dateto, $userdates);    
    $date_size = sizeof($userdates); 
    
    for($i=0;$i<$vsize;$i++)
    {       
        $CurrentLat = 0.0;
        $CurrentLong = 0.0;
        $LastLat = 0.0;
        $LastLong = 0.0;
        $firstData = 0;
        $start_time_flag = 0;
        $distance_total = 0;
        $daily_dist=0;
        $distance_threshold = 0.200;
        $datetime_threshold = 300;
        $distance_error = 0.100;
        $distance_incriment =0.0;
        $firstdata_flag =0;
        $start_point_display =0;
        $j=0;
        $haltFlag=True;
        $distance_travel=0;
        $vehicle_info=get_vehicle_info($root,$vserial[$i]);
        $vehicle_detail_local=explode(",",$vehicle_info);      
        
        for($di=0;$di<=($date_size-1);$di++)
        {
            $SortedDataObject=null;
            $SortedDataObject=new data();
            readFileXmlNew($vserial[$i],$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
           if(count($SortedDataObject->deviceDatetime)>0)
            {               
                $sortedSize=sizeof($SortedDataObject->deviceDatetime);                 
                for($obi=0;$obi<$sortedSize;$obi++)
                {
                   $DataValid=0;
                   $lat = $SortedDataObject->latitudeData[$obi];                           
                   $lng = $SortedDataObject->longitudeData[$obi];                       
                   // echo "lat=".$lat." lng=".$lng." datetime=".$datetime." speed=".$speed."<br>";
                    if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
                    {
                        $DataValid = 1;
                    }
                    if(($DataValid==1))
                    {
                        $speed = $SortedDataObject->speedData[$obi];
                        $datetime=$SortedDataObject->deviceDatetime[$obi];
                        if($firstdata_flag==0)
                        {                                
                           $firstdata_flag = 1;
                            $distance_travel=0;                                    

                            $lat_S = $lat;
                            $lng_S = $lng;
                            $datetime_S = $datetime;
                            $datetime_travel_start = $datetime_S; 
                            $datetime_E=$datetime;
                            $lat_travel_start = $lat_S;
                            $lng_travel_start = $lng_S;                  
                            $start_point_display =0;
                            $last_time = $datetime;
                            $last_time1 = $datetime;
                            $latlast = $lat;
                            $lnglast =  $lng;  
                            $max_speed	=0.0;								
                        }           	              	
                        else
                        {           
                            $lat_E = $lat;
                            $lng_E = $lng;
                            $datetime_prev = $datetime_E;
                            $datetime_E = $datetime; 

                            calculate_distance($lat_S, $lat_E, $lng_S, $lng_E, $distance_incriment);								         		
                            $tmp_time_diff = (double)(strtotime($datetime) - strtotime($last_time)) / 3600;                
                            //echo "1 tmp_time_diff=".$tmp_time_diff." last_time=".$last_time." datetime".$datetime." distance_incriment=".$distance_incriment." lat_S=".$lat_S." lng_S=".$lng_S." lat_E=".$lat_E." lng_E=".$lng_E."<br>";       
                            calculate_distance($latlast, $lat_E, $lnglast, $lng_E, $distance1);
                            $tmp_time_diff1 = ((double)( strtotime($datetime) - strtotime($last_time1) )) / 3600; 
                            //echo "2 tmp_time_diff1=".$tmp_time_diff1." last_time1=".$last_time1." datetime".$datetime." distance1=".$distance1." latlast=".$latlast." lnglast=".$lnglast." lat_E=".$lat_E." lng_E=".$lng_E."<br>";       
                            if($tmp_time_diff1>0)
                            {
                                $tmp_speed = ((double) ($distance_incriment)) / $tmp_time_diff;
                                $tmp_speed1 = ((double) ($distance1)) / $tmp_time_diff1;
                            }
                            else
                            {
                                $tmp_speed1 = 1000.0; //very high value
                            }

                            if($tmp_speed<300.0)
                            {
                                $speeed_data_valid_time = $datetime;
                            }
                            
                            /*if($distance_incriment>20)
                            {
                                echo "datetime_E=".$datetime_E." distance_incriment=".$distance_incriment." latS=".$lat_S." latE=".$lat_E." lngs=".$lng_S." lngE=".$lng_E."_datetimeL=".$last_time." speed=".$speed."<br>";
                            }*/
                            //echo "3 tmp_speed=".$tmp_speed." tmp_speed1=".$tmp_speed1."<br>";       
                            if(( strtotime($datetime) - strtotime($speeed_data_valid_time) )>300) //data high speed for 5 mins
                            {
                                    $lat_S = $lat_E;
                                    $lng_S = $lng_E;
                                    $last_time = $datetime;
                            }

                            $last_time1 = $datetime;
                            $latlast = $lat_E;
                            $lnglast =  $lng_E;
                            //echo"maxspeed=".$max_speed."speed=".$speed."<br>";
                            if(($max_speed<$speed) && ($speed<200))
                            {
                                $max_speed = $speed;
                            }

                            //echo "tmpSpeed=".round($tmp_speed,2)."tmpSpeed1=".round($tmp_speed1,2)."distanceIncreament=".$distance_incriment."tmpTimeDiff=".$tmp_time_diff." tmpTimeDiff1=".$tmp_time_diff1."<br>";								
                            if(round($tmp_speed,2)<300.0 && round($tmp_speed1,2)<300.0 && $distance_incriment>0.1 && $tmp_time_diff>0.0 && $tmp_time_diff1>0)
                            {
                                    //echo "halt out<br>";
                                    if($haltFlag==True)
                                    {
                                            //echo "halt in<br>";
                                            $datetime_travel_start = $datetime_prev;
                                            //echo "datetime_travel_start=".$datetime_travel_start."<br>";
                                            $lat_travel_start = $lat_E;
                                            $lng_travel_start = $lng_E;
                                            $distance_travel = 0;
                                            $distance_total = 0;
                                            $distance_incrimenttotal = 0;
                                            $haltFlag = False;
                                    }
                                    //echo "datetime_E=".$datetime_E." distance_incriment=".$distance_incriment." latS=".$lat_S." latE=".$lat_E." lngs=".$lng_S." lngE=".$lng_E."_Edatetime=".$datetime." speed=".$speed."<br>";
                                  
                                    $distance_total += $distance_incriment;
                                    $distance_travel += $distance_incriment;
                                    $daily_dist += $distance_incriment;
                                    $lat_S = $lat_E;
                                    $lng_S = $lng_E;
                                    $last_time = $datetime_E;
                                    $datetime_S = $datetime_E;

                                    //echo "dateTime=".$datetime_S."<br>";

                                    $start_point_display =1;
                                    //$distance_incrimenttotal += $distance_incriment;
                                    // echo $datetime_E . " -- " . $lat_E .",". $lng_E . "\tDelta Distance = " . $distance_incriment . "\tTotal Distance = " . $distance_total . "\n";
                            }

                            $datetime_diff = strtotime($datetime_E) - strtotime($datetime_S);
                            //echo "daily_dist=".$daily_dist."<br><br>";	
                            if(($datetime_diff > $datetime_threshold) && ($haltFlag==False))
                            {
                                    //echo "datetime_E=".$datetime_E." datetime_S=".$datetime_S."<br>";
                                    //echo "datetime_travel_start=".$datetime_travel_start." datetime_diff=".$datetime_diff."<br>";
                                    $datetime_travel_end = $datetime_S;
                                    //echo "datetime_travel_start=".$datetime_travel_start." datetime_travel_end=".$datetime_travel_end."<br>";
                                    $daily_travel_time += strtotime($datetime_travel_end) - strtotime($datetime_travel_start);
                                    //echo "daily_dist=".$daily_dist." daily_travel_time=".$daily_travel_time."<br>";
                                    $haltFlag = True;
                                    $j=0;
                            }
                        }
                    }
                }
                if($haltFlag==False)
                {
                    $datetime_travel_end = $datetime_S;
                    $daily_travel_time += strtotime($datetime_travel_end) - strtotime($datetime_travel_start);
                }
                if($daily_travel_time>0)
                {		
                    //echo"daily_dist=".$daily_dist."daily_travel_time".$daily_travel_time."<br>";
                    $daily_avg_speed = ($daily_dist/$daily_travel_time)*3600;
                    $daily_avg_speed = round($daily_avg_speed,2);
                    //echo"daily_dist=".$daily_avg_speed."<br>";
                }
                else
                {
                    $daily_avg_speed=0;
                }
                $daily_dist = round($daily_dist,2);

               // echo "dailyAvgSpeed=".$daily_avg_speed." max_speed=".$max_speed."<br>";

                if($daily_avg_speed>$max_speed)
                {
                    $daily_max_speed = $daily_avg_speed;
                }
                else
                {
                    $daily_max_speed = $max_speed;
                }

                if($daily_dist==0)
                {
                    $daily_max_speed=0;
                    $daily_avg_speed=0;
                }
                $imei[]=$vserial;
                $vname[]=$vehicle_detail_local[0];
                $mdate[]=$date;
                $distanceDisplayArr[]=$daily_dist;
                $maxSpeedDisplayArr[]=$daily_max_speed;
                $avgSpeedDisplayArr[]=$daily_avg_speed;

                $daily_dist = 0;
                $daily_travel_time=0;
                //$firstdata_flag=0;
                $daily_avg_speed=0;
                $daily_max_speed=0;
                $max_speed=0;
            }
        }    	
    }
    $o_cassandra->close();
    $parameterizeData=null;
    
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
	
		echo'<td class="text" align="left"><b>Average Speed (km/hr)</b></td>
		<td class="text" align="left"><b>Max Speed (km/hr)</b></td>';
	
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
						
