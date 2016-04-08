<?php
	//error_reporting(-1);
	//ini_set('display_errors', 'On');
	set_time_limit(300);	
	date_default_timezone_set("Asia/Kolkata");
	include_once("main_vehicle_information_1.php");
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('xmlParameters.php');
	include_once("report_title.php");
	include_once('parameterizeData.php');
	include_once('data.php');
	include_once("sortXmlData.php");
	include_once("getXmlData.php");
	include_once("calculate_distance.php");
	
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
	
	$sortBy='h';
	$firstDataFlag=0;
	$endDateTS=strtotime($date2);
	$dataCnt=0;	

	$userInterval = $_POST['user_interval'];
	//echo "userInterval=".$userInterval."<br>";
	$requiredData="All";
	
	$parameterizeData=new parameterizeData();
	$ioFoundFlag=0;
	
	$parameterizeData->latitude="d";
	$parameterizeData->longitude="e";
	
	$finalVNameArr=array();

	for($i=0;$i<$vsize;$i++)
	{
		$dataCnt=0;
		$vehicle_info=get_vehicle_info($root,$vserial[$i]);
		$vehicle_detail_local=explode(",",$vehicle_info);
		$finalVNameArr[$i]=$vehicle_detail_local[0];		
			
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
		
		//var_dump($UnSortedDataObject);		
		//var_dump($SortedDataObject);	
		//echo "<br><br>";
		
		if(count($SortedDataObject->deviceDatetime)>0)
		{
			$prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
			for($obi=0;$obi<$prevSortedSize;$obi++)
			{			
				$finalDateTimeArr[$i][$dataCnt]=$SortedDataObject->deviceDatetime[$obi];
				$finalLatitudeArr[$i][$dataCnt]=$SortedDataObject->latitudeData[$obi];
				$finalLongitudeArr[$i][$dataCnt]=$SortedDataObject->longitudeData[$obi];		
				$dataCnt++;
			}
		}
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
				$dataCnt++;
			}
		}
		$SortedDataObject=null;			
		$sortObjTmp=null;
		$UnsortedDataObject =null;
		
	}
	$parameterizeData=null;
	$o_cassandra->close();
	//print_r($finalDateTimeArr);
	$imei=array();
	$vname=array();
	$dateFromDisplay=array();
	$dateToDisplay=array();
	$distanceDisplay=array();
	for($i=0;$i<$vsize;$i++)
	{
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
		$total_dist = 0.0;
		$innerSize=sizeof($finalDateTimeArr[$i]);
		for($j=0;$j<$innerSize;$j++)
		{
			$lat = $finalLatitudeArr[$i][$j];
			$lng = $finalLongitudeArr[$i][$j];
			$datetime=$finalDateTimeArr[$i][$j];			
			//echo "<br>first=".$firstdata_flag;                                        
			if($firstdata_flag==0)
			{					
				$firstdata_flag = 1;

				$lat1 = $lat;
				$lng1 = $lng;

				//echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
				$interval = (double)$userInterval*60;							

				$time1 = $datetime;					
				$date_secs1 = strtotime($time1);					
				//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
				$date_secs1 = (double)($date_secs1 + $interval); 
				$date_secs2 = 0;  
				$last_time1 = $datetime;
				$latlast = $lat;
				$lnglast =  $lng;
				//echo "<br>FirstData:".$date_secs1." ".$time1;                 	
			} 
			else
			{
				$time2 = $datetime;											
				$date_secs2 = strtotime($time2);
																		                                      													      					
				$lat2 = $lat;      				        					
				$lng2 = $lng; 
				calculate_distance($lat1, $lat2, $lng1, $lng2, $distance);
				if($distance>2000)
				{
					$distance=0;
					$lat1 = $lat2;
					$lng1 = $lng2;
				}
				$tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;
				calculate_distance($latlast, $lat2, $lnglast, $lng2, $distance1);
				$tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time) )) / 3600;
			
				if($tmp_time_diff1>0)
				{									
					$tmp_speed = ((double) ($distance)) / $tmp_time_diff;
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
			
				if((strtotime($datetime) - strtotime($speeed_data_valid_time) )>300) //data high speed for 5 mins
				{
					$lat1 = $lat2;
					$lng1 = $lng2;
					$last_time = $datetime;
				}			
				$last_time1 = $datetime;
				$latlast = $lat2;
				$lnglast =  $lng2;
				
				if($tmp_speed<300.0 && $tmp_speed1<300.0 && $distance>0.1 && $tmp_time_diff>0.0 && $tmp_time_diff1>0)
				{								
					$total_dist = (double)( $total_dist + $distance );				
					$daily_dist= (double) ($daily_dist + $distance);	
					$daily_dist = round($daily_dist,2);	
					$lat1 = $lat2;
					$lng1 = $lng2;
					$last_time = $datetime;					                		    						
				}

				//echo "date_secs2=".$date_secs2."date_secs1=".$date_secs1."<br>";
				if( ($date_secs2 >= $date_secs1))// || ($f == $total_lines-5))
				{ 
					$imei[]=$vserial[$i];
					$vname[]=$finalVNameArr[$i];
					$dateFromDisplay[]=$time1;
					$dateToDisplay[]=$time2;
					$distanceDisplay[]=$total_dist;
					$time1 = $datetime;
					$date_secs1 = strtotime($time1);
					$date_secs1 = (double)($date_secs1 + $interval);   						                  
					$total_dist = 0.0;				
				}  //if datesec2                                                 									                               
			}   // else closed   
		}
		//if(($xml_date >= $enddate1))
		{
			$imei[]=$vserial[$i];
			$vname[]=$finalVNameArr[$i];
			$dateFromDisplay[]=$time1;
			$dateToDisplay[]=$time2;
			$distanceDisplay[]=$total_dist;
			//reassign time1
			$time1 = $datetime;
			$date_secs1 = strtotime($time1);
			$date_secs1 = (double)($date_secs1 + $interval);		    									    						    						
			//echo "<br>datesec1=".$datetime;    						                  
			$total_dist = 0.0;	 
			$lat1 = $lat2;
			$lng1 = $lng2;
		}		
	}
echo '<center>';
	  
  echo'<br>';
  report_title("Distance Report",$date1,$date2);
  
	echo'<div style="overflow: auto;height: 300px; width: 620px;" align="center">';
							

  $j=-1;
  $k=0;
  	//print_r($imei);	
$datefrom1=array(array());	
$dateto1=array(array());
$distance1=array(array());
  for($i=0;$i<sizeof($imei);$i++)
	{								              
    if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
    {
      $k=0;                                              
      $j++;
      $sum_dist =0.0;
      $total_distance[$j] =0;
      
      $sno = 1;
      $title='Distance Report : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
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
			<td class="text" align="left"><b>Start DateTime</b></td>
			<td class="text" align="left"><b>End DateTime</b></td>
			<td class="text" align="left"><b>Distance (km)</b></td>								
      </tr>';  								
    }                                                                        		
		
    $sum_dist = $sum_dist + $distance[$i];
	              
    echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
		echo'<td class="text" align="left">'.$dateFromDisplay[$i].'</td>';		
    echo'<td class="text" align="left">'.$dateToDisplay[$i].'</td>';

		echo'<td class="text" align="left">'.round($distanceDisplay[$i],2).'</td>';		
		
		echo'</tr>';	          		
		//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
    
    $datefrom1[$j][$k] = $dateFromDisplay[$i];	
    $dateto1[$j][$k] = $dateToDisplay[$i];	
	$distance1[$j][$k] = round($distanceDisplay[$i],2); 
	
	
	  if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
    {
      echo '<tr style="height:20px;background-color:lightgrey">
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
			echo'</tr>'; 
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
		$title = $vname1[$x][0]." (".$imei1[$x][0]."): Distance Report- From DateTime : ".$date1."-".$date2;
		$csv_string = $csv_string.$title."\n";
    $csv_string = $csv_string."SNo,Start DateTime,End DateTime,Distance (km)\n";
    echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
		
		$sno=0;
		for($y=0;$y<=$no_of_data[$x];$y++)
		{
			//$k=$j-1;
			$sno++;
                    
      $datetmp1 = $datefrom1[$x][$y];	
			$datetmp2 = $dateto1[$x][$y];										
			$disttmp = $distance1[$x][$y];
			
			//echo "dt=".$datetmp1;								
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][DateTime From]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][DateTime To]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$disttmp\" NAME=\"temp[$x][$y][Distance (km)]\">";
      
      $csv_string = $csv_string.$sno.','.$datetmp1.','.$datetmp2.','.$disttmp."\n";      																	
		}		

		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime From]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime To]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Distance (km)]\">";									
		
		$m = $y+1;								
		
		echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][DateTime From]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][DateTime To]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$total_distance[$x]\" NAME=\"temp[$x][$m][Distance (km)]\">";
    $csv_string = $csv_string.'Total,'.$date1.','.$date2.','.$total_distance[$x]."\n";																																										
	}																						

      
  echo'	
    <table align="center">
		<tr>
			<td>';
      
  		$vsize = sizeof($imei);
  		
      if($vsize==0)
  		{						
  			print"<center><FONT color=\"Red\" size=2><strong>No Distance Record</strong></font></center>";
  			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
  			echo'<br><br>';
  		}	
  		else
  		{
        echo'<input TYPE="hidden" VALUE="distance" NAME="csv_type">';
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
