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
	$DEBUG = 0;
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
/*if($getDataBy==1)
{
	$sortBy="g";
}
 else 
{
  $sortBy="h";  
 }*/
        $sortBy="h";  
 //echo "sortBy=".$sortBy."<br>";
	$firstDataFlag=0;
	$endDateTS=strtotime($date2);
	$dataCnt=0;	
	
	$parameterizeData=new parameterizeData();
        get_All_Dates($datefrom, $dateto, $userdates);    
        $date_size = sizeof($userdates); 
	for($i=0;$i<$vsize;$i++)
	{
		$vehicle_info=get_vehicle_info($root,$vserial[$i]);
		$vehicle_detail_local=explode(",",$vehicle_info);
		//echo "vehicle_detail_local=".$vehicle_detail_local[7]."<br>";
		$ioArr=explode(":",$vehicle_detail_local[7]);
		$ioFoundFlag=0;
		$ioArrSize=sizeof($ioArr);
		for($z=0;$z<$ioArrSize;$z++)
		{
			$tempIo=explode("^",$ioArr[$z]);
			//echo "io=".$ioArr[$z]."<br>";
			if($tempIo[1]=="temperature")
			{
				$ioFoundFlag=1;
				$parameterizeData->temperature=$finalIoArr[$tempIo[0]];
			}
		}
		//echo "tmpio=".$parameterizeData->temperature."<br>";
		if($ioFoundFlag==1)
		{
                    $CurrentLat = 0.0;
                    $CurrentLong = 0.0;
                    $LastLat = 0.0;
                    $LastLong = 0.0;
                    $firstData = 0;
                    $distance =0.0;
                    $firstdata_flag =0;
                    
                     for($di=0;$di<=($date_size-1);$di++)
                     {  
                        $SortedDataObject=new data();
                        readFileXmlNew($vserial[$i],$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject); 
                        if($sortBy=="h")  /// for device datetime
			{
                            if(count($SortedDataObject->deviceDatetime)>0)
                            {
                                //echo "in if 1<br>";
                                $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
                                for($obi=0;$obi<$prevSortedSize;$obi++)
                                {
                                    $datetime=$SortedDataObject->deviceDatetime[$obi];
                                    $temperature = $SortedDataObject->temperatureIOData[$obi]; 					  
                                    if($firstdata_flag==0)
                                    {					
                                        $firstdata_flag = 1;
                                        $interval = (double)$userInterval*60;	     
                                        $time1 = $datetime;					
                                        $date_secs1 = strtotime($time1);					
                                        //echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
                                        $date_secs1 = (double)($date_secs1 + $interval); 
                                        $date_secs2 = 0; 
                                        $tmpPrev=$temperature;
                                        $dateTimePrev=strtotime($datetime);
                                    }
                                    else
                                    {                           					
                                        $time2 = $datetime;											
                                        $date_secs2 = strtotime($time2);
                                        $tmpNext=$temperature;
                                        $dateTimeNext=strtotime($datetime);
                                        $tmpDiff=$tmpNext-$tmpPrev;
                                        $tmpFlag=1;
                                        if((($dateTimeNext-$dateTimePrev)<60) && (abs($tmpDiff)>10))
                                        {
                                            $tmpFlag=0;
                                        }

                                        if(($date_secs2 >= $date_secs1) && $tmpFlag==1)
                                        {
                                            //echo "time1=".$time1." time2=".$time2." tmpDiff=".$tmpDiff." tmpNext=".$tmpNext." tmpPrev=".$tmpPrev." datediff=".($dateTimeNext-$dateTimePrev)."<br>";
                                            if($temperature>=-30 && $temperature<=70)
                                            {
                                                //echo "temperature1=".$temperature." doublet=".(double)$temperature."<br>";
                                                $temperature = preg_replace('/[^0-9-]/s', '.', $temperature);
                                                $temperaturetmp=substr_count($temperature, '.');
                                                if($temperaturetmp<=1)
                                                {
                                                    $imei[]=$vserial[$i];
                                                    $vname[]=$vehicle_detail_local[0];
                                                    $dateFromDisplay[]=$time1;
                                                    $dateTodisplay[]=$time2;
                                                    $temperatureDisplay[]=$temperature;
                                                    $fieldsData[$datetime] = $temperature;
                                                    $time1 = $datetime;
                                                    $date_secs1 = strtotime($time1);
                                                    $date_secs1 = (double)($date_secs1 + $interval);
                                                }
                                            }     															
                                        }  //if datesec2 
                                        $tmpPrev=$tmpNext;
                                        $dateTimePrev=$dateTimeNext;

                                    }   // else closed 
                                }
                                $SortedDataObject=null;	
                            }
                        } 
                        if($sortBy=="g")  /// for device datetime
			{
                            if(count($SortedDataObject->serverDatetime)>0)
                            {
                                $sortObjTmp=sortData($SortedDataObject,$sortBy,$parameterizeData);
                                //var_dump($sortObjTmp);
                                $sortedSize=sizeof($sortObjTmp->serverDatetime);
                                for($obi=0;$obi<$sortedSize;$obi++)
                                {
                                    $datetime=$sortObjTmp->serverDatetime[$obi];
                                    $temperature = $SortedDataObject->temperatureIOData[$obi]; 					  
                                    if($firstdata_flag==0)
                                    {					
                                        $firstdata_flag = 1;
                                        $interval = (double)$userInterval*60;	     
                                        $time1 = $datetime;					
                                        $date_secs1 = strtotime($time1);					
                                        //echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
                                        $date_secs1 = (double)($date_secs1 + $interval); 
                                        $date_secs2 = 0; 
                                        $tmpPrev=$temperature;
                                        $dateTimePrev=strtotime($datetime);
                                    }
                                    else
                                    {                           					
                                        $time2 = $datetime;											
                                        $date_secs2 = strtotime($time2);
                                        $tmpNext=$temperature;
                                        $dateTimeNext=strtotime($datetime);
                                        $tmpDiff=$tmpNext-$tmpPrev;
                                        $tmpFlag=1;
                                        if((($dateTimeNext-$dateTimePrev)<60) && (abs($tmpDiff)>10))
                                        {
                                            $tmpFlag=0;
                                        }

                                        if(($date_secs2 >= $date_secs1) && $tmpFlag==1)
                                        {
                                            //echo "time1=".$time1." time2=".$time2." tmpDiff=".$tmpDiff." tmpNext=".$tmpNext." tmpPrev=".$tmpPrev." datediff=".($dateTimeNext-$dateTimePrev)."<br>";
                                            if($temperature>=-30 && $temperature<=70)
                                            {
                                                //echo "temperature1=".$temperature." doublet=".(double)$temperature."<br>";
                                                $temperature = preg_replace('/[^0-9-]/s', '.', $temperature);
                                                $temperaturetmp=substr_count($temperature, '.');
                                                if($temperaturetmp<=1)
                                                {
                                                    $imei[]=$vserial[$i];
                                                    $vname[]=$vehicle_detail_local[0];
                                                    $dateFromDisplay[]=$time1;
                                                    $dateTodisplay[]=$time2;
                                                    $temperatureDisplay[]=$temperature; 
                                                    $time1 = $datetime;
                                                    $date_secs1 = strtotime($time1);
                                                    $date_secs1 = (double)($date_secs1 + $interval);
                                                }
                                            }     															
                                        }  //if datesec2 
                                        $tmpPrev=$tmpNext;
                                        $dateTimePrev=$dateTimeNext;

                                    }   // else closed 
                                }
                                $SortedDataObject=null;	
                            }
                        }                      
                     }			
		}
		else
		{
                    $SortedDataObject=null;		
		}
	}
        $o_cassandra->close();
        //print_r($finalSpeedArr);
        $parameterizeData=null;

echo '<center>';
// $speed_sortTS = UTIL::sort_datetimeTS($speed);
		$session_name = "SPEED_" . time();
		$_SESSION[$session_name] =  $fieldsData;
		print "<center><img src=\"graph/GraphDateLine.php?s=".$session_name."\"></center>";
  echo'<br>';
  report_title("Temperature Report",$date1,$date2);
  
	echo'<div style="overflow: auto;height: 300px; width: 620px;" align="center">';

  $j=-1;
  $k=0;
    $datefrom1=array();
     $dateto1=array();
     $temperature1=array();			             
  for($i=0;$i<sizeof($imei);$i++)
	{								              
    if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
    {
      $k=0;                                              
      $j++;
      $sum_dist =0;
      $total_distance[$j] =0;
      
      $sno = 1;
      $title='Temperature Report : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
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
			<td class="text" align="left"><b>Temperature</b></td>								
      </tr>';  								
    }                                                                        		
		
    //$sum_dist = $sum_dist + $distance[$i];
	              
    echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
		echo'<td class="text" align="left">'.$dateFromDisplay[$i].'</td>';		
    echo'<td class="text" align="left">'.$dateTodisplay[$i].'</td>';	
/*$temperaturethis=preg_replace("/&#?[A-Za-z]+;/i",".",$temperature[$i]);	
$temperaturethis1=iconv('UTF-8', 'UTF-8//IGNORE', $temperaturethis);
$ftemperature=substr_replace($temperaturethis1, '.', 2, -1);*/
		echo'<td class="text" align="left">'.ltrim ($temperatureDisplay[$i],'.').'</td>';					
		echo'</tr>';	          		
		//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
   
    $datefrom1[$j][$k] = $dateFromDisplay[$i];	
    $dateto1[$j][$k] = $dateTodisplay[$i];										
    //$temperature1[$j][$k] = round($distance[$i],2); 
    //$temperature1[$j][$k] = $ftemperature;  
$temperature1[$j][$k] = $temperatureDisplay[$i];       			    				  					
	
	  if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
    {
      /*echo '<tr style="height:20px;background-color:lightgrey">
      <td class="text"><strong>Total<strong>&nbsp;</td>
			<td class="text"><strong>'.$date1.'</strong></td>	
      <td class="text"><strong>'.$date2.'</strong></td>';									
      
			if($k>0)
			{
				//echo  "<br>sum_avgspeed=".$sum_avgspeed."<br>";
        $total_distance[$j] = round($sum_dist,2);
        //echo  "<br>total_avgspeed[$j]=".$total_avgspeed[$j]."<br>";
			}
													
			echo'<td class="text"><font color="red"><strong>'.round($total_distance[$j],2).'</strong></font></td>';
			echo'</tr>';*/ 
      echo '</table>';
      
      $no_of_data[$j] = $k;
		}  
		
    $k++;   
    $sno++;                       							  		
 }
 
  echo "</div>";     

	echo'<form method = "post" target="_blank">';
	
  $csv_string = "";
	
  for($x=0;$x<=$j;$x++)
	{								
		$title = $vname1[$x][0]." (".$imei1[$x][0]."): Temperature Report- From DateTime : ".$date1."-".$date2;
		$csv_string = $csv_string.$title."\n";
    $csv_string = $csv_string."SNo,Start Time,End Time,Temperature\n";
    echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
		
		$sno=0;
		for($y=0;$y<=$no_of_data[$x];$y++)
		{
			//$k=$j-1;
			$sno++;
                    
      $datetmp1 = $datefrom1[$x][$y];	
			$datetmp2 = $dateto1[$x][$y];										
			$temperature_tmp = ltrim($temperature1[$x][$y],'.');
			
			//echo "dt=".$datetmp1;								
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][DateTime From]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][DateTime To]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$temperature_tmp\" NAME=\"temp[$x][$y][Temperature]\">";
      
      $csv_string = $csv_string.$sno.','.$datetmp1.','.$datetmp2.','.$temperature_tmp."\n";      																	
		}		

		/*echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime From]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime To]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Distance (km)]\">";									
		
		$m = $y+1;								
		
		echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][DateTime From]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][DateTime To]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$total_distance[$x]\" NAME=\"temp[$x][$m][Distance (km)]\">";	*/																																									
	}																						

      
  echo'	
    <table align="center">
		<tr>
			<td>';
      
  		if(sizeof($imei)==0)
  		{						
  			print"<center><FONT color=\"Red\" size=2><strong>No Temperature Record Found</strong></font></center>";
  			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
  			echo'<br><br>';
  		}	
  		else
  		{
        echo'<input TYPE="hidden" VALUE="temperature" NAME="csv_type">';
        echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
        echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
        <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';      
      }
                  
      echo'</td>		
    </tr>
		</table>
		</form>
 ';				 
					 	
echo '</center>';	
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';	 
?>
