<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
set_time_limit(3000);	
date_default_timezone_set("Asia/Kolkata");
include_once("main_vehicle_information_1.php");
/*include_once('lib/BUG.php');
include_once('lib/Util.php');
include_once('lib/VTSFuel.php');
include_once('lib/VTSMySQL.php');*/
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
include_once("util.hr_min_sec.php");

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
	$filter_flag1 = $_POST['filter_flag'];
	$userInterval = $_POST['user_interval'];
	//echo "userInterval=".$userInterval."<br>";
	$sortBy='h';
	$firstDataFlag=0;
	$endDateTS=strtotime($date2);
	//$dataCnt=0;	

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
		//echo "vs=".$vserial[$i]."<br>";
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
		echo "udt2=".$UnSortedDataObject->speedData[1]."<br>";*/
		
		/*echo "sodt1=".$SortedDataObject->deviceDatetime[0]."<br>";
		echo "sodt2=".$SortedDataObject->deviceDatetime[1]."<br>";	
		echo "sodt1=".$SortedDataObject->speedData[0]."<br>";
		echo "sodt2=".$SortedDataObject->speedData[1]."<br>";
		echo "<br><br>";*/
		
		if(count($SortedDataObject->deviceDatetime)>0)
		{
			//echo "in sorted=".$SortedDataObject->deviceDatetime."<br><br><br><br><br><br>";
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
				$finalDateTimeArr[$i][$dataCnt]=$sortObjTmp->deviceDatetime[$obi];	
				$finalLatitudeArr[$i][$dataCnt]=$sortObjTmp->latitudeData[$obi];
				$finalLongitudeArr[$i][$dataCnt]=$sortObjTmp->longitudeData[$obi];	
				$finalSpeedArr[$i][$dataCnt]=$sortObjTmp->speedData[$obi];
				$dataCnt++;				
			}
		}
		$innerSize=sizeof($finalDateTimeArr[$i]);
		//echo"size=".$innerSize."<br>";
		$SortedDataObject=null;			
		$sortObjTmp=null;
		$UnsortedDataObject =null;
			
	}
	$parameterizeData=null;	
	


/*for($i=0;$i<$vsize;$i++)
{
	$vehicle_info=get_vehicle_info($root,$vserial[$i]);
	$vehicle_detail_local=explode(",",$vehicle_info);	
	$imeis[] = $vehicle_detail_local[7];
                                                      
}

$startDateTime = $date1;
$endDateTime = $date2;

$startDate = date('Y-m-d', strtotime($startDateTime));
$endDate = date('Y-m-d', strtotime($endDateTime));
$endDate1 = date('Y-m-d', strtotime($endDate)+(1*24*60*60));
$timeIntervalTS = $user_interval*60;

$time_list = UTIL::getAllTimes($startDateTime, $endDateTime, $timeIntervalTS);
$datetime_now = date("Y:m:d H:i:s", time()); 	
//$vsize1 =0; 	

foreach($imeis as $imei_local)
{ 	      
  $fuel_data = VTSFuel::getFuelData($DbConnection, $imei_local, $startDate, $endDate1);		
	if(sizeof($time_list)>0)
	{
		foreach($time_list as $datetime)
		{
			if(strtotime($datetime) <= strtotime($datetime_now))
			{
				$fuel_datetime =  VTSFuel::interpolateFuelData($fuel_data, $datetime);
				if($fuel_datetime >= 0)
				{
					$fuel[$imei_local][$datetime] = $fuel_datetime;
				}
			}
		}
	}		
 //$vsize1++;	
}
                      
$imei_size =0;  
   
foreach($imeis as $imei_local)
{ 
  $k=0;
  $imei_size1 =1;    $fuel_size =0;
  foreach($fuel[$imei_local] as $datetime => $fuel_imei)
  {
    $fuel_size=1;
  }
  if($fuel_size)
  {  
    $vname_local = VTSMySQL::getVehicleNameOfVehicle($DbConnection, $imei_local);
    $imei_number = VTSMySQL::getIMEIOfVehicle($DbConnection, $imei_local);                   
    foreach($fuel[$imei_local] as $datetime => $fuel_imei)
    {       
      //if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.160")
      //echo '<br>'.$datetime.' ,'.$fuel_imei;        
      if($k>0)
      {
        $fuel_imei_array[] = $imei_number;
        $fuel_array[] = $fuel_imei;
        $fuel_date[] = $datetime;
        
        if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.160")
        echo '<br>BEFORE PRINT : '.$datetime.' ,'.$fuel_imei;         
      }
      $k++;        
    }
  } // if fuel_size closed
}
////////////////////////////////
*/
//echo "<br>".$date1." ,".$date2." ,user_interval".$user_interval;

//echo "vsize=".$vsize;
///////////////////////////////////////////////////////////////////////////////


$sCnt=0;
for($i=0;$i<$vsize;$i++)
{
	//echo "vserial=".$vserial[$i]."<br>";
	//echo "in for<br>";
	$firstData = 0;
	$distance =0;
	$firstdata_flag =0;
	$sCnt=0;
	$total_dist = 0.0;
	
	$total_test_time =0;
	$speed_threshold = 1;
	$start_runflag = 0;
	$stop_runflag = 1;
	$total_speed = 0.0;
	$r1 =0;
	$r2 =0;
	$last_time=0;
	$runtime_start = array();
	$runtime_stop = array();
	
	$innerSize=0;
	$innerSize=sizeof($finalDateTimeArr[$i]);
	//echo"size=".$innerSize."<br>";
	
	for($j=0;$j<$innerSize;$j++)
	{
		$lat = $finalLatitudeArr[$i][$j];						
		$lng = $finalLongitudeArr[$i][$j];							
		$speed = $finalSpeedArr[$i][$j];
		$xml_date=$finalDateTimeArr[$i][$j];	
		if($firstdata_flag==0)
		{					
			$firstdata_flag = 1;
			$lat1 = $lat;
			$lng1 = $lng; 
			//echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
			$interval = (double)$userInterval*60;
			//echo "intervalFirst=".$interval."<br>";
			$time1 = $xml_date;					
			$date_secs1 = strtotime($time1);					
			//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
			$date_secs1 = (double)($date_secs1 + $interval); 
			$date_secs2 = 0; 

			if( ($speed > $speed_threshold) && ($start_runflag==0) )   // START
			{
				//echo "<br>start condition1";
				$runtime_start[$r1] = $xml_date;
				$r1++;
				$start_runflag = 1;
				$stop_runflag = 0;                        				  
			}
		}    	
		else
		{                           					               
			$time2 = $xml_date;											
			$date_secs2 = strtotime($time2);	
			//echo "<br>Next".$date_secs2;													                                

			$lat2 = $lat;
			$lng2 = $lng; 

			calculate_distance($lat1, $lat2, $lng1, $lng2, $distance);

			$tmp_time_diff = (strtotime($time2) - strtotime($last_time)) / 3600;
			if($tmp_time_diff>0)
			{
				$tmp_speed = $distance / $tmp_time_diff;
			} 

			//echo "<br>tmp_speed=".$tmp_speed." ,distance=".$distance." ,tmp_time_diff=".$tmp_time_diff."imei=".$vserial[$i]."<br>";               
			if($tmp_speed<500 && $distance>0.1 && $tmp_time_diff>0)
			{														
				$total_dist = (double) ($total_dist + $distance);
				//echo "total_distance1=".$total_dist ."<br>";
			
				//echo "\ntotal dist=".$total_dist;
				//echo "<br>dist greater than 0.025: dist=".$total_dist." time2=".$time2;
				$lat1 = $lat2;
				$lng1 = $lng2;

				$last_time = $time2;   			
				////// TMP CLOSED	////////////////////////////////////////		    						
			}	                    					
	                    				  
			if(($speed < $speed_threshold) && ($stop_runflag ==0) )   // STOP 
			{
				//echo ", stop<br>";
				$runtime_stop[$r2] = $xml_date;
				$r2++;
				$stop_runflag = 1;
				$start_runflag = 0;      				  
			}

			if($speed > $speed_threshold && ($start_runflag ==0) && ($distance>0.1)  )    // START
			{
				//echo "<br>start";
				$runtime_start[$r1] = $xml_date;
				$r1++;
				$start_runflag =1;
				$stop_runflag = 0;      				  
			}
			//echo "date_secs2=".$date_secs2." date_secs1=".$date_secs1." diff=".($date_secs2-$date_secs1)."<br>";
			if($date_secs2 >= $date_secs1)
			{   
			//echo "time1=".$time1." time2=".$time2."total_distance=".$total_dist."<br>";
				//echo "total_distance1=".$total_dist ."<br>";
				if(sizeof($runtime_start) == 0)
				{
					$total_runtime =0;
				}
				
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
					$runtime = strtotime($runtime_stop[$m]) - strtotime($runtime_start[$m]);
					$total_runtime = $total_runtime + $runtime;
					//echo "<br>A:runtime=".$runtime." ,total_runtime=".$total_runtime;                    
				} 
				//echo "total_distance2=".$total_dist."<br>";
				//echo "interval=".$interval."<br>";
				if(($interval>=1800) && ($total_dist<0.2))
				{
					$total_dist = 0.0;
				} 
				else
				{
					$total_dist = round($total_dist,3);
				}
				//echo "total_distance3=".$total_dist."<br>";
				$avg_speed_this = ($total_dist / $total_runtime)*3600;
				//echo "avg_speed_this=".$avg_speed_this."<br>";
				if($avg_speed_this<150)
				{
					$imei[]=$vserial[$i];
					//print_r($imei);
					$vname[]=$finalVNameArr[$i];
					$dateRromRead[]=$time1;
					$dateToRead[]=$time2;
					$avg_speed[]=$avg_speed_this;
					$runtimeRead[]=$total_runtime;
					$distanceRead[]=$total_dist;
				}  		

				//reassign time1
				$time1 = $xml_date;
				$date_secs1 = strtotime($time1);
				$date_secs1 = (double)($date_secs1 + $interval);		    									    						    						
				//echo "<br>datesec1=".$datetime;    						                  
				$total_speed = 0.0;
				$avg_speed_this = 0.0;
				$total_dist = 0.0;

				$runtime_start = array();
				$runtime_stop = array();

				$start_runflag = 0;
				$stop_runflag = 1;

				$total_runtime =0; 

				$r1 = 0;
				$r2 = 0; 															
			}  //if datesec2                       									                               
		}   // else closed 
		if(($xml_date > $date2) && ($xml_date!="-"))
		{
			//echo "in if<br>";
			if(sizeof($runtime_start) == 0)
			{
				$total_runtime =0;
			}
		
			if( ((sizeof($runtime_stop)) == (sizeof($runtime_start)-1)) )   
			{
				$runtime_stop[$r2] = $xml_date;
				$stop_runflag = 1;
				$start_runflag = 0;                 
				$r2++;
			}
			$total_runtime = 0;	

			for($m=0;$m<(sizeof($runtime_start));$m++)
			{
				//echo "<br>B:run1=".$runtime_stop[$m]." ,run2=".$runtime_start[$m]."<br>";                   
				$runtime = strtotime($runtime_stop[$m]) - strtotime($runtime_start[$m]);
				$total_runtime = $total_runtime + $runtime;
				//echo "<br>B:runtime=".$runtime." ,total_runtime=".$total_runtime;                    
			}                             
			if(($interval>=1800) && ($total_dist<0.2))
			{
				$total_dist = 0.0;
			}
			else
			{
				$total_dist = round($total_dist,3);
			}
			$avg_speed_this = ($total_dist / $total_runtime)*3600;

			if($avg_speed_this<150)
			{      				
				$imei[]=$vserial[$i];
				//print_r($imei);
				$vname[]=$finalVNameArr[$i];
				$dateRromRead[]=$time1;
				$dateToRead[]=$time2;
				$avg_speed[]=$avg_speed_this;
				$runtimeRead[]=$total_runtime;
				$distanceRead[]=$total_dist;
			} 
			//reassign time1
			$time1 = $xml_date;
			$date_secs1 = strtotime($time1);
			$date_secs1 = (double)($date_secs1 + $interval);		

			$total_speed = 0.0;
			$avg_speed_this = 0.0;
			$total_dist = 0.0;

			$runtime_start = array();
			$runtime_stop = array();

			$start_runflag = 0;
			$stop_runflag = 1;

			$total_runtime =0; 

			$r1 = 0;
			$r2 = 0;              

			break;
		}
		if(($j==($innerSize-1)))
		{
			if($avg_speed_this<150)
			{      			
				$imei[]=$vserial[$i];
				//print_r($imei);
				$vname[]=$finalVNameArr[$i];
				$dateRromRead[]=$time1;
				$dateToRead[]=$time2;
				$avg_speed[]=$avg_speed_this;
				$runtimeRead[]=$total_runtime;
				$distanceRead[]=$total_dist;
			}  
		} 
	}
}

//print_r($imei);
echo '<center>';        
  echo'<br>';
  report_title("Performance Report",$date1,$date2);
  
	echo'<div style="overflow: auto;height: 300px; width: 700px;" align="center">';

  $j=-1;
  $k=0;
  $fuel_final = 0;			             
              
$dataSize=sizeof($imei);
  for($i=0;$i<$dataSize;$i++)
	{								              
    if(($i===0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
    {
      $k=0;                                              
      $j++;
      
      $sum_avgspeed =0;
      $sum_runtime =0;
      $sum_dist =0;      
      $total_distance[$j] =0;
      
      $sno = 1;
      $title='Performance Report : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
      $vname1[$j][$k] = $vname[$i];
      $imei1[$j][$k] = $imei[$i];   
      //echo  "vname1=".$vname1[$j][$k]." j=".$j." k=".$k;
      
      echo'
      <br><table align="center">
      <tr>
      	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
      </tr>
      </table>
      <table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
      <tr>
			<td class="text" align="left"><b>SNo</b></td>
			<td class="text" align="left"><b>Start Time</b></td>
			<td class="text" align="left"><b>End Time</b></td>
      <!--<td class="text" align="left"><b>Fuel (litre)</b></td>-->			
			<td class="text" align="left"><b>Average Speed (km/hr)</b></td>
			<td class="text" align="left"><b>Runtime (hr:m:s)</b></td>
			<td class="text" align="left"><b>Distance (km)</b></td>								
      </tr>';  								
    }                                                                        		
		
    //echo "<br>runtime[i]=".$runtime[$i]." ,sumruntime=".$sum_runtime;                  
    //if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.160")
       //echo '<br>Outside: i='.$i.' ,date:'.$dateto[$i].' ,fuel='.$fuel_final;
      //echo "fileterFlag=".$filter_flag1." distance=".$distanceRead[$i]."<br>";  
    if( (($filter_flag1 == "true") && ($distanceRead[$i] > 0)) || ($filter_flag1 == "false") )
    {  
      /*for($m=0;$m < sizeof($fuel_imei_array); $m++)
      {

        if($imei[$i]==$fuel_imei_array[$m])
        {
          if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.160")
            echo '::IN CONDITION<br>';
                    
          for($n=$m;$n<sizeof($fuel_imei_array);$n++)
          {
            if($imei[$i]!=$fuel_imei_array[$n])
            {
              break;
            }
            else
            {
              if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.160")
                echo '<br>In else fuel_date[n]='.$fuel_date[$n].' ,fuel='.$fuel_array[$n].'<br>';
                              
              if( ( strtotime($fuel_date[$n])>= strtotime($datefrom[$i]) ) && ( strtotime($fuel_date[$n])<= strtotime($dateto[$i]) ) )
              {
                $fuel_final = $fuel_array[$n];
                
                if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.160")
                  echo '<br>final fuel='.$fuel_final;
                  
                break;                
              }
            }
          }
        }
      }*/
        
      if($avg_speed[$i]>0)
      {
        //$fuel_final = $fuel_array[$i];
        $hms = secondsToTime($runtimeRead[$i]);
        $runtime_str = $hms[h].":".$hms[m].":".$hms[s];
        
        $sum_avgspeed = $sum_avgspeed + $avg_speed[$i];
      
        $sum_dist = $sum_dist + $distanceRead[$i];
        $sum_runtime = $sum_runtime + $runtimeRead[$i];  
      }
      else
      {
        $runtime_str = "00:00:00";
        /*if($fuel_final == 0)
        {
          $fuel_final = $fuel_array[$i];
        }*/
      }
                  
    //echo "<br>FF=".$filter_flag1;                         
      echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
  		echo'<td class="text" align="left">'.$dateRromRead[$i].'</td>';		
      echo'<td class="text" align="left">'.$dateToRead[$i].'</td>';
     // echo'<td class="text" align="left">'.round($fuel_final,2).'</td>';
      echo'<td class="text" align="left">'.round($avg_speed[$i],2).'</td>';
      echo'<td class="text" align="left">'.$runtime_str.'</td>';					
  		echo'<td class="text" align="left">'.round($distanceRead[$i],2).'</td>';					
  		echo'</tr>';
              		
  		//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];      
      $datefrom1[$j][$k] = $dateRromRead[$i];	
      $dateto1[$j][$k] = $dateToRead[$i];
     // $fuel_array1[$j][$k] = round($fuel_final,2);
      $avg_speed1[$j][$k] = round($avg_speed[$i],2);
      $runtime1[$j][$k] = $runtime_str;										
      $distance1[$j][$k] = round($distanceRead[$i],2);  
    } // filter flag closed	
    			    				  				
	
	  if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
    {
      //$fuel_final = 0;
      echo 
      '<tr style="height:20px;background-color:lightgrey">
      <td class="text"><strong>Total<strong>&nbsp;</td>
			<td class="text"><strong>'.$date1.'</strong></td>	
      <td class="text"><strong>'.$date2.'</strong></td>';									
      
			if($k>0)
			{
				//echo  "<br>sum_avgspeed=".$sum_avgspeed."<br>";
				//$total_avgspeed[$j] = round($sum_avgspeed,2);
        $total_distance[$j] = round($sum_dist,2);
        
        $hms_total = secondsToTime($sum_runtime);
        $runtime_total[$j] = $hms_total[h].":".$hms_total[m].":".$hms_total[s];        
        //$hms = secondsToTime($sum_runtime);
        //$total_runtime[$j] = $hms[h].":".$hms[m].":".$hms[s];         
        //echo  "<br>total_avgspeed[$j]=".$total_avgspeed[$j]."<br>";
        
  			$total_avgspeed[$j] = round((($total_distance[$j]/$sum_runtime)*3600),2);
        echo'<!--<td class="text"><font color="red"><strong></strong></font></td>-->';
        echo'<td class="text"><font color="red"><strong>'.$total_avgspeed[$j].'</strong></font></td>';
        echo'<td class="text"><font color="red"><strong>'.$runtime_total[$j].'</strong></font></td>';
        echo'<td class="text"><font color="red"><strong>'.$total_distance[$j].'</strong></font></td>';        
			}
													

			echo'</tr>'; 
      echo '</table>';
      
      $no_of_data[$j] = $k;
		}  
		
    if( ($filter_flag1 == "true" && $distanceRead[$i] > 0) || (($filter_flag1 == "false")) )
    {    
      $k++;   
      $sno++;
    }                       							  		
 }
 
  echo "</div>";     

	echo'<form method = "post" target="_blank">';
	
  $csv_string = "";
	
  for($x=0;$x<=$j;$x++)
	{										
    $title = $vname1[$x][0]." (".$imei1[$x][
    0]."): Performance Report- From DateTime : ".$date1."-".$date2;
		$csv_string = $csv_string.$title."\n";
   // $csv_string = $csv_string."SNo,Start DateTime,End DateTime, Fuel(litre), Average Speed(km/hr), Runtime(h:m:s), Distance (km)\n";
   $csv_string = $csv_string."SNo,Start DateTime,End DateTime, Average Speed(km/hr), Runtime(h:m:s), Distance (km)\n"; 
	echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
		
		$sno=0;
		for($y=0;$y<$no_of_data[$x];$y++)
		{
			//$k=$j-1;
			$sno++;
                    
      $datetmp1 = $datefrom1[$x][$y];	
			$datetmp2 = $dateto1[$x][$y];
			//$fuel_tmp = $fuel_array1[$x][$y];
			$avgspeed_tmp = $avg_speed1[$x][$y];
      $runtimetmp = $runtime1[$x][$y];										
			$disttmp = $distance1[$x][$y];
			
			//echo "dt=".$datetmp1;								
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][DateTime From]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][DateTime To]\">";
			//echo"<input TYPE=\"hidden\" VALUE=\"$fuel_tmp\" NAME=\"temp[$x][$y][Fuel (litre)]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$avgspeed_tmp\" NAME=\"temp[$x][$y][Average Speed (km/hr)]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$runtimetmp\" NAME=\"temp[$x][$y][Runtime (h:m:s)]\">";			
			echo"<input TYPE=\"hidden\" VALUE=\"$disttmp\" NAME=\"temp[$x][$y][Distance (km)]\">";
      
      //$csv_string = $csv_string.$sno.','.$datetmp1.','.$datetmp2.','.$fuel_tmp.','.$avgspeed_tmp.','.$runtimetmp.','.$disttmp."\n";      																	
		$csv_string = $csv_string.$sno.','.$datetmp1.','.$datetmp2.','.$avgspeed_tmp.','.$runtimetmp.','.$disttmp."\n";      																	
		}
    
    $csv_string = $csv_string.'Total,'.$date1.','.$date2.','.$total_fuel[$x].','.$total_avgspeed[$x].','.$runtime_total[$x].','.$total_distance[$x]."\n";    		

		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime From]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime To]\">";
    //echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Fuel (litre)]\">";		
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Average Speed (km/hr)]\">";
    echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Runtime (h:m:s)]\">";		
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Distance (km)]\">";									
		
		$m = $y+1;								
						
		echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][DateTime From]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][DateTime To]\">";
		//echo"<input TYPE=\"hidden\" VALUE=\"$total_fuel[$x]\" NAME=\"temp[$x][$m][Fuel (litre)]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$total_avgspeed[$x]\" NAME=\"temp[$x][$m][Average Speed (km/hr)]\">";
    echo"<input TYPE=\"hidden\" VALUE=\"$runtime_total[$x]\" NAME=\"temp[$x][$m][Runtime (h:m:s)]\">";		
		echo"<input TYPE=\"hidden\" VALUE=\"$total_distance[$x]\" NAME=\"temp[$x][$m][Distance (km)]\">";																																										
	}																						  
      
  echo'	
    <table align="center">
		<tr>
			<td>';
      
  		if(sizeof($imei)==0)
  		{						
  			print"<center><FONT color=\"Red\" size=2><strong>No Record Found</strong></font></center>";
  			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
  			echo'<br><br>';
  		}	
  		else
  		{
        echo'<input TYPE="hidden" VALUE="performance" NAME="csv_type">';
        echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
        echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
        <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';      
      }
                  
      echo'</td>		
    </tr>
		</table>
		</form>
 ';
					 
unlink($xml_path);				 
					 	
echo '</center>';	
echo'<br><center>
		<a href="javascript:showReportPrevPage(\'report_performance.htm\',\''.$selected_account_id.'\',\''.$selected_options_value.'\',\''.$s_vehicle_display_option.'\');" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';		 
?>
