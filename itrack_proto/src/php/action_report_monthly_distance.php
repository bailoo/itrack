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
    $m1=date('M',mktime(0,0,0,$month,1));
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
    $parameterizeData->speed="f";
    $tdi=0; /// two d increament
    //echo "day opt=".$day_opt1."<br>";

    if($day_opt==2)
    {
        $lastday=$daysize;
    }

    //$finalVNameArr[$tdi]=$vehicle_detail_local[0];
    //echo "<br>DAYOPT,lastday=".$lastday;
    $finalEachDayDataArr=array();
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
    for($jm=1;$jm<=$lastday;$jm++)
    {
        $dataFoundFlag=0;
        if($day_opt==1)
        {
            $k=$jm;
        }
        else if($day_opt==2)
        {
            $k=$days1[$jm-1];
            $todayInc=$jm-1;
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

            if($jm==1) ////////// this is for startdate diplay on final report on web browser
            {
                    $dateStartDisplay = $date." 00:00:00";
                   //echo"startDate=".$dateStartDisplay."<br>";
            }

            if($jm==$lastday) ////////// this is for enddate diplay on final report on web browser
            {
                    $dateEndDisplay = $date." ".date("H:i:s");
                    //echo"endDate=".$dateEndDisplay."<br>";
            }

            $SortedDataObject=new data();                
            //echo "date=".$date."<br>";
            readFileXmlNew($vserial,$date,$requiredData,$sortBy,$parameterizeData,$SortedDataObject);           
            $sortedSize=sizeof($SortedDataObject->deviceDatetime);
            
            if((count($SortedDataObject->deviceDatetime)>0) && ($date<=date('Y-m-d')))
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

                            if($tmp_speed<250.0)
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
                            
                            
                            
                            if(($tmp_time_diff>2.0) && ($tmp_time_diff1>2.0))
                            {
                                $SpeedThsld = 90.0;
                            }
                            else
                            {
                                $SpeedThsld = 250.0;
                            }

                            //echo "tmpSpeed=".round($tmp_speed,2)."tmpSpeed1=".round($tmp_speed1,2)."distanceIncreament=".$distance_incriment."tmpTimeDiff=".$tmp_time_diff." tmpTimeDiff1=".$tmp_time_diff1."<br>";								
                            if(round($tmp_speed,2)<$SpeedThsld && round($tmp_speed1,2)<$SpeedThsld && $distance_incriment>0.1 && $tmp_time_diff>0.0 && $tmp_time_diff1>0)
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
                $dailyDistDisplay[]=$daily_dist;
                $maxSpeedArr[]=$daily_max_speed;
                $avgSpeedArr[]=$daily_avg_speed;

                $daily_dist = 0;
                $daily_travel_time=0;
                //$firstdata_flag=0;
                $daily_avg_speed=0;
                $daily_max_speed=0;
                $max_speed=0;
            }  
        }       
	//$parameterizeData=null;	
        report_title("Monthly Distance",$dateStartDisplay,$dateEndDisplay);
    echo'<center>
            <div style="overflow: auto;height: 350px; width: 620px;" align="center">';
            $j=-1;
            $k=0;
            $final_maxspeed_tmp=0;
            $endtable=0;
            $daily_dist1=array();
            $maxSpeedArr1=array();
            $avgSpeedArr1=array();
            for($i=0;$i<sizeof($imei);$i++)
            {								              
                if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
                {
                    $k=0;                
                    $sum_dailydist =0;                

                    $j++;
                    $total_dailydist[$j] =0;

                    $sno = 1;
                    $title="Monthly Distance Report : ".$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>". "( ".$m1."-".$year."  )";
                    $vname1[$j][$k] = $vname[$i];
                    $imei1[$j][$k] = $imei[$i];
				
                    echo' <br><table align="center">
                    <tr>
                        <td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
                    </tr>
                    </table>
                    <table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
                    <tr>
                        <td class="text" align="left"><b>SNo</b></td>
                        <td class="text" align="left"><b>Date</b></td>
                        <td class="text" align="left"><b>Daily Distance (km)</b></td>
                        <td class="text" align="left"><b>Max Speed (km/hr)</b></td>
                        <td class="text" align="left"><b>Avg Speed (km/hr)</b></td>
                    </tr>';  								
                }  	
		$sum_dailydist = $sum_dailydist + $dailyDistDisplay[$i];

		echo'<tr><td class="text" align="left" width="12%"><b>'.$sno.'</b></td>';        												
		echo'<td class="text" align="left">'.$mdate[$i].'</td>';		
		echo'<td class="text" align="left">'.$dailyDistDisplay[$i].'</td>';
		echo'<td class="text" align="left">'.$maxSpeedArr[$i].'</td>';
		echo'<td class="text" align="left">'.$avgSpeedArr[$i].'</td>';		
		echo'</tr>';	          		
                //echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
      
		$date_pdf[$j][$k] = $mdate[$i];	
		$daily_dist1[$j][$k] = $dailyDistDisplay[$i];
		$maxSpeedArr1[$j][$k] = $maxSpeedArr[$i];
		$avgSpeedArr1[$j][$k] = $avgSpeedArr[$i];		
								
		if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
		{
			$endtable =1;        
			echo '<tr style="height:20px;background-color:lightgrey">
			<td class="text"><strong>Total<strong>&nbsp;</td>
			<td class="text"><strong></strong></td>';									
        
			if($k>0)
			{
                            //echo  "<br>sum_avgspeed=".$sum_avgspeed."<br>";
                            $total_dailydist[$j] = round(($sum_dailydist),2);
                            //echo  "<br>total_avgspeed[$j]=".$total_avgspeed[$j]."<br>";
			}
			else
			{
				$total_dailydist[$j] = "NA";
			}															

				echo'<td class="text"><font color="red"><strong>'.$total_dailydist[$j].'</strong></font></td>';
				echo'</tr>'; 
        echo '</table>';
		}  
			
      $k++;   
      $sno++;                       							  		
   }
   
   if(!$endtable)
   {
        echo '<tr style="height:20px;background-color:lightgrey">
        <td class="text"><strong>Total<strong>&nbsp;</td>
        <td class="text"><strong></strong></td>';								
        echo'<td class="text"><font color="red"><strong>'.round(($sum_dailydist),2).'</strong></font></td>';
				echo'</tr>'; 
        echo '</table>';    
   }
   
   echo "</div>";  
   echo'<br><br>';   

   $vsize = sizeof($imei);
   
   $csv_string = "";   
	 
   echo'<form method = "post" target="_blank">';
		
		for($x=0;$x<=$j;$x++)
		{								
			$title=$vname[$x]." (".$imei[$x]."): Monthly Distance Report(km) Date From ".$date1." To ".$date2;
			$csv_string = $csv_string.$title."\n";
			$csv_string = $csv_string."SNo,Date,Daily Distance (km),Max Speed(km/hr),Average Speed(km/hr)\n";
      echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
			
			$sno=0;
			for($y=0;$y<$k;$y++)
			{
				//$k=$j-1;
				$sno++;
                      
        $datetmp = $date_pdf[$x][$y];	
				$dailydisttmp = $daily_dist1[$x][$y];
				$maxSpeedTmp = $maxSpeedArr1[$x][$y];
				$avgSpeedTmp = $avgSpeedArr1[$x][$y];				
				//echo "dt=".$datetmp1;
				
				echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$datetmp\" NAME=\"temp[$x][$y][Date]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$dailydisttmp\" NAME=\"temp[$x][$y][Daily Distance]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$maxSpeedTmp\" NAME=\"temp[$x][$y][Max Speed]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$avgSpeedTmp\" NAME=\"temp[$x][$y][Avg Speed]\">";
        
        $csv_string = $csv_string.$sno.','.$datetmp.','.$dailydisttmp.','.$maxSpeedTmp.','.$avgSpeedTmp."\n"; 							
			}		

			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Date]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Daily Distance]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Max Speed]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Avg Speed]\">";
			
			$m = $y+1;								
			
			echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][Date]\">";
			if(!$endtable)
			$total_dailydist[$x] = $sum_dailydist;
      echo"<input TYPE=\"hidden\" VALUE=\"$total_dailydist[$x]\" NAME=\"temp[$x][$m][Daily Distance]\">";
      $csv_string = $csv_string.'Total,-,'.$sum_dailydist."\n"; 																																									
		}																						

    echo'	
      <table align="center">
  		<tr>
  			<td>';
        
    		if(sizeof($imei)==0)
    		{						
    			print"<center><FONT color=\"Red\" size=2><strong>No Distance Record</strong></font></center>";
    			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
    			echo'<br><br>';
    		}	
    		else
    		{
          echo'<input TYPE="hidden" VALUE="monthly_distance" NAME="csv_type">';
          echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
          echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
          <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';      
        }
                    
        echo'</td>		
      </tr>
  		</table>
  		</form>';             

echo '</center>';  
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';
?>