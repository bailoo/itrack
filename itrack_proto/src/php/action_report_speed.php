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
	
    $userInterval = $_POST['user_interval'];

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
	

    get_All_Dates($datefrom, $dateto, $userdates);    
    $date_size = sizeof($userdates); 
    
    for($i=0;$i<$vsize;$i++)
    {
        $dataCnt=0;
        $vehicle_info=get_vehicle_info($root,$vserial[$i]);
        $vehicle_detail_local=explode(",",$vehicle_info);
       
        $total_speed_tmp = 0;
	$total_time_tmp = 0;
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;
	$firstdata_flag =0;
        $count = 0;
	$avg_speed = null;
	$max_speed = null;
	
	$total_avg_speed = null;
	$total_max_speed = null;
        
        for($di=0;$di<=($date_size-1);$di++)
        {            
            $logcnt=0;
            $DataComplete=false;
            $vehicleserial_tmp=null;
            
            $speed_threshold = 1;
            $start_runflag = 0;
            $stop_runflag = 1;
            $total_speed = 0.0;
            $r1 =0;
            $r2 =0;
            $j = 0;
            $StopTimeCnt = $xml_date;
            $StopStartFlag = 0;

            $runtime_start = array();
            $runtime_stop = array();
            //echo "userdate=".$userdates[$di]."<br>";
            $SortedDataObject=new data();
            readFileXmlNew($vserial[$i],$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
            //var_dump($SortedDataObject);
            
            if(count($SortedDataObject->deviceDatetime)>0)
            {
                $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
                for($obi=0;$obi<$prevSortedSize;$obi++)
                {                   
                    $lat = $SortedDataObject->latitudeData[$obi];
                    $lng = $SortedDataObject->longitudeData[$obi];
                    if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
                    {
                        $DataValid = 1;
                    }
                    if(($DataValid==1) && ($SortedDataObject->deviceDatetime[$obi] > $date1 && $SortedDataObject->deviceDatetime[$obi] < $date2))
                    {         	
                        $speed =$SortedDataObject->speedData[$obi];
                        $datetime =$SortedDataObject->deviceDatetime[$obi];
                        $xml_date = $datetime;
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
                            $speed_arr[$j] = $speed;
                            $time1 = $datetime;
                            $date_secs1 = strtotime($time1);
                            //echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
                            $interval = (double)$userInterval*60*60;
                            $date_secs1 = (double)($date_secs1 + $interval);  							
                            //echo "<br>DateSec1 after=".$date_secs1;	      

                            if( ($speed > $speed_threshold) && ($start_runflag==0) )   // START
                            {
                                //echo "<br>start condition1";
                                $runtime_start[$r1] = $xml_date;
                                $r1++;
                                $start_runflag = 1;
                                $stop_runflag = 0; 
                                $StopStartFlag = 0;
                            }                                  	
                        } 
                        else
                        {                           
                            ///////// FIXING SPEED PROBLEM ///////////            
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
                            $speed_arr[$j] = $speed;   
                            ///////////////////////////////////////////   

                            $time2 = $datetime;											
                            $date_secs2 = strtotime($time2);
			
                            //echo "<br>speed=".$speed." ,time=".$time2;	

                            $lat2 = $lat;
                            $lng2 = $lng;

                            calculate_distance($lat1, $lat2, $lng1, $lng2, $distance);

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
                                if(((strtotime($xml_date) - strtotime($StopTimeCnt))>15) && ($StopStartFlag==1))
                                {
                                    //echo ", stop<br>";
                                    $runtime_stop[$r2] = $xml_date;
                                    $r2++;
                                    $stop_runflag = 1;
                                    $start_runflag = 0;
                                }
                                else if($StopStartFlag==0)
                                {
                                    $StopTimeCnt = $xml_date;
                                    $StopStartFlag = 1;
                                }
                            }
								  
                            if($speed > $speed_threshold && ($start_runflag ==0) && ($distance>0.1)  )    // START
                            {
                                //echo "<br>start";
                                $runtime_start[$r1] = $xml_date;
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
                                {
                                    $total_runtime =0;
                                }
			  
                                //echo "<br>SIZE1=".sizeof($runtime_start)." ,SIZE2=".sizeof($runtime_start);

                                //if( (sizeof($runtime_stop) == 0) && (sizeof($runtime_start)>0) )
                                if( ((sizeof($runtime_stop)) == (sizeof($runtime_start)-1)) )  
                                {
                                    //echo "<br>A:RunStop";
                                    $runtime_stop[$r2] = $xml_date;
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

                                if(($interval>=1800) && ($total_dist<3.0))
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
                                    $imei[]=$vserial[$i];
                                    $vname[]=$vehicle_detail_local[0];
                                    $dateFromDisplay[]=$time1;
                                    $dateToDisplay[]=$time2;
                                    $avgSpeedDisplay[]=$avg_speed;
                                    $runtimeDisplay[]=$total_runtime;
                                    $maxSpeedDisplay[]=$max_speed;
                                    $distanceDisplay[]=$total_dist;
                                }  		
			  
                                //reassign time1
                                $time1 = $datetime;
                                $date_secs1 = strtotime($time1);
                                $date_secs1 = (double)($date_secs1 + $interval);		
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
                    }
                    ///$dataCnt++;
                    $count++;
                    $j++;
                }
                $SortedDataObject=null;
            }
        }		
    }

$o_cassandra->close();
//print_r($finalSpeedArr);
$parameterizeData=null;

$m1=date('M',mktime(0,0,0,$month,1));		
echo '<center>';
$size_vserial = sizeof($vserial);
report_title("Speed Report",$date1,$date2);    
echo'<div style="overflow: auto;height: 300px; width: 820px;" align="center">';		
$j=-1;
$k=0;
$final_maxspeed_tmp=0;
$datefrom1=array();
$dateto1=array();
$avgspeed1=array();
$maxspeed1=array();
$avg_speed_last=array();

for($i=0;$i<sizeof($imei);$i++)
{								              
    if(($i===0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
    {
        $k=0;        
        $sum_avgspeed =0;
        $sum_runtime =0;
        $sum_dist =0;      
        $total_distance[$j] =0;              
        $sum_avgspeed =0;                
        //$final_maxspeed_tmp =0;
        $j++;
        $total_avgspeed[$j] =0;
        $final_maxspeed[$j] =0;
        $sno = 1;
        $title="Speed Report : ".$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
        $vname1[$j][$k] = $vname[$i];
        $imei1[$j][$k] = $imei[$i];        
        echo'<br>
        <table align="center">
            <tr>
                <td class="text" align="center">
                    <b>'.$title.'</b> <div style="height:8px;"></div>
                </td>
            </tr>
        </table>
        <table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
            <tr>
                <td class="text" align="left"><b>SNo</b></td>
                <td class="text" align="left"><b>DateTime From</b></td>
                <td class="text" align="left"><b>DateTime To</b></td>
                <td class="text" align="left"><b>Avg Speed (km/hr)</b></td>	
                <td class="text" align="left"><b>Max Speed (km/hr)</b></td>	
            </tr>';  								
    }                                                                        		
			
    if($avgSpeedDisplay[$i] == 0)
    {
        $maxSpeedDisplay[$i] = 0;
    }
    //echo "<br>runtime=".$runtimeDisplay[$i];
    $sum_dist = $sum_dist + $distanceDisplay[$i];
    $sum_runtime = $sum_runtime + $runtimeDisplay[$i];
    $sum_avgspeed = $sum_avgspeed + $avgSpeedDisplay[$i];
			
    if( $final_maxspeed_tmp < $maxSpeedDisplay[$i] )
    {
        $final_maxspeed_tmp = $maxSpeedDisplay[$i];
    }
echo'<tr>
        <td class="text" align="left" width="4%"><b>'.$sno.'</b></td>
        <td class="text" align="left">'.$dateFromDisplay[$i].'</td>
        <td class="text" align="left">'.$dateToDisplay[$i].'</td>
        <td class="text" align="left">'.$avgSpeedDisplay[$i].'</td>
        <td class="text" align="left"><b>'.$maxSpeedDisplay[$i].'</b></td>
    </tr>';
    $datefrom1[$j][$k] = $dateFromDisplay[$i];	
    $dateto1[$j][$k] = $dateToDisplay[$i];										
    $avgspeed1[$j][$k] = $avgSpeedDisplay[$i];
    $maxspeed1[$j][$k] = $maxSpeedDisplay[$i];       			    				  				
		
    if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
    {
        echo'<tr style="height:20px;background-color:lightgrey">
                <td class="text"><strong>Total<strong>&nbsp;</td>
                <td class="text"><strong>'.$date1.'</strong></td>	
                <td class="text"><strong>'.$date2.'</strong></td>';
                if($k>0)
                {
                    //echo  "<br>sum_avgspeed=".$sum_avgspeed."<br>";
                    $total_distance[$j] = round($sum_dist,2);
                    $total_avgspeed[$j] = round(($sum_avgspeed/$k),2);
                     //echo  "<br>total_avgspeed[$j]=".$total_avgspeed[$j]."<br>";
                }
                else
                {
                    $total_avgspeed[$j] = "NA";
                }
				
                if($k>0)
                {																									
                    $final_maxspeed[$j] = round($final_maxspeed_tmp,2);
                }
                else
                {
                    $final_maxspeed[$j] = "NA";
                }								
														
            $avg_speed_last[$j] = round((($total_distance[$j]/$sum_runtime)*3600),2);
        echo'<td class="text">
                <font color="red">
                    <strong>'.$avg_speed_last[$j].'</strong>
                </font>
            </td>
            <td class="text">
                <font color="red">
                    <strong>'.$final_maxspeed[$j].'</strong>
                </font>
            </td>
        </tr>
    </table>';
        
    $no_of_data[$j] = $k;
    $final_maxspeed_tmp =0;
    }		
    $k++;   
    $sno++;                                   							  		
}
   
   echo "</div>";     

   $csv_string = "";   
	 
   echo'<form method = "post" target="_blank">';
		
		for($x=0;$x<=$j;$x++)
		{								
			$title=$vname1[$x][0]." (".$imei1[$x][0]." ): Speed Report- From DateTime : ".$date1."-".$date2;
			$csv_string = $csv_string.$title."\n";
			$csv_string = $csv_string."SNo,DateTime From,DateTime To,Avg Speed (km/hr),Max Speed (km/hr)\n";
			
      echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
			
			$sno=0;
			for($y=0;$y<=$no_of_data[$x];$y++)
			{
				//$k=$j-1;
				$sno++;
                      
        $datetmp1 = $datefrom1[$x][$y];	
				$datetmp2 = $dateto1[$x][$y];										
				$avgspd = $avgspeed1[$x][$y];
				$maxspd = $maxspeed1[$x][$y];
				
				//echo "dt=".$datetmp1;
				
				echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][DateTime From]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][DateTime To]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$avgspd\" NAME=\"temp[$x][$y][Avg Speed (km/hr)]\">";									
				echo"<input TYPE=\"hidden\" VALUE=\"$maxspd\" NAME=\"temp[$x][$y][Max Speed (km/hr)]\">";
        
        $csv_string = $csv_string.$sno.','.$datetmp1.','.$datetmp2.','.$avgspd.','.$maxspd."\n";									
			}		
			
      $csv_string = $csv_string.'Total,'.$date1.','.$date2.','.$avg_speed_last[$x].','.$final_maxspeed[$x]."\n";

			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime From]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime To]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Avg Speed (km/hr)]\">";									
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Max Speed (km/hr)]\">";	
			
			$m = $y+1;								
			
			echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][DateTime From]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][DateTime To]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$avg_speed_last[$x]\" NAME=\"temp[$x][$m][Avg Speed (km/hr)]\">";									
			echo"<input TYPE=\"hidden\" VALUE=\"$final_maxspeed[$x]\" NAME=\"temp[$x][$m][Max Speed (km/hr)]\">";																																		
		}																						


    echo '<br><center><font color=red>Note </font><font color=blue>: Avg Speed is treated as Zero if the distance travelled in 1 hour is less than 2.5 km</font></center>';
    
    echo'	
    <table align="center">
		<tr>
			<td>';
      
  		if(sizeof($imei)==0)
  		{						
  			print"<center><FONT color=\"Red\" size=2><strong>No Speed Record found</strong></font></center>";
  			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
  			echo'<br><br>';
  		}	
  		else
  		{
        echo'<input TYPE="hidden" VALUE="speed" NAME="csv_type">';
        echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
        echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
        <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';      
      }                  
      echo'</td>		
    </tr>
		</table>
		</form>
	 ';					 

echo'</center>';
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';
?>								

	