<?php
    //error_reporting(-1);
    //ini_set('display_errors', 'On');
    set_time_limit(300);	
    date_default_timezone_set("Asia/Kolkata");
    include_once("main_vehicle_information_1.php");
    include_once('Hierarchy.php');
    $root=$_SESSION["root"];
    include_once('util_session_variable.php');
   
    include_once("report_title.php");
    
    include_once('xmlParameters.php');
    include_once('parameterizeData.php');
    include_once('data.php');   
    include_once("getXmlData.php");
    
    include_once("util.hr_min_sec.php");

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
    $userInterval = "0";
    $requiredData="All";	
    $parameterizeData=new parameterizeData();
    $ioFoundFlag=0;
   
    
    
    get_All_Dates($datefrom, $dateto, $userdates);    
    $date_size = sizeof($userdates);
    
    for($i=0;$i<$vsize;$i++)
    {
        $vehicle_info=get_vehicle_info($root,$vserial[$i]);
        $vehicle_detail_local=explode(",",$vehicle_info);
        //echo "vehicle_detail_local=".$vehicle_detail_local[7]."<br>";
        
        $ioArr=explode(":",$vehicle_detail_local[7]);
        //print_r($vehicle_detail_local[7]);
        $ioArrSize=sizeof($ioArr);
        
        for($z=0;$z<$ioArrSize;$z++)
        {
            $tempIo=explode("^",$ioArr[$z]);
            //echo "io=".$ioArr[$z]."<br>";
            //echo "ioTypeName=".$tempIo[1]."<br>";
            if($tempIo[1]=="engine")
            {
                $ioFoundFlag=1;
                //echo "ioPlace=".$finalIoArr[$tempIo[0]]."<br>";
                $parameterizeData->engineRunHr=$finalIoArr[$tempIo[0]];
            }
            if($tempIo[1]=="engine_type")
            {
                $finalInverseType=1;
            }
            else
            {
                $finalInverseType=0;
            }
        }
        //echo "finalInverseType=".$finalInverseType."<br>";
        //echo "tmpio=".$parameterizeData->temperature."<br>";
        if($ioFoundFlag==1)
        {
            $firstdata_flag =0;
            $runhr_duration =0 ;
           
            $StartFlag=0;
            $continuous_running_flag =0;
            
            for($di=0;$di<=($date_size-1);$di++)
            {
               
                $SortedDataObject=null;
                $SortedDataObject=new data();
                if($date_size==1)
                {
                    $dateRangeStart=$date1;
                    $dateRangeEnd=$date2;
                }
                else if($di==($date_size-1))
                {
                    $dateRangeStart=$userdates[$di]." 00:00:00";
                    $dateRangeEnd=$date2;
                }
                else if($di==0)
                {
                    $dateRangeStart=$date1;
                    $dateRangeEnd=$userdates[$di]." 23:59:59";
                }
                else
                {
                   $dateRangeStart=$userdates[$di]." 00:00:00";
                    $dateRangeEnd=$userdates[$di]." 23:59:59";
                }
                deviceDataBetweenDates($vserial[$i],$dateRangeStart,$dateRangeEnd,$sortBy,$parameterizeData,$SortedDataObject);
             
                if(count($SortedDataObject->deviceDatetime)>0)
                {
                    $c = -1;
                    $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
                    for($obi=0;$obi<$prevSortedSize;$obi++)
                    {
                        $c++;
                        $datetime =$SortedDataObject->deviceDatetime[$obi];  
                        $engine_count = $SortedDataObject->engineIOData[$obi];                                                                            	                         
                       /* if($finalInverseType==0)
                        {
                            //echo "engine_count".$engine_count."<br>";
                            if($engine_count>500)
                            {
                                $continuous_running_flag = 1;
                            }

                            if($engine_count>500 && $StartFlag==0)  //500
                            {                						
                                $time1 = $datetime;
                                //echo "datetime=".$time1."<br>";
                                $StartFlag = 1;
                               // echo "in start flag<br>";
                            } 
                            else if( ($engine_count<500 && $StartFlag==1) || ( ($c==($prevSortedSize-1)) && ($continuous_running_flag ==1) ))   //500
                            {
                                $StartFlag = 2;
                                //echo "in end flag<br>";
                            }
                        }
                        else
                        {*/
                            //echo "engine_count else ".$engine_count."<br>";
                            if($engine_count<500)
                            {
                                $continuous_running_flag = 1;
                            }

                            if($engine_count<500 && $StartFlag==0)  //500
                            {                						
                                $time1 = $datetime;
                                $StartFlag = 1;
                            } 
                            else if( ($engine_count>500 && $StartFlag==1) || ( ($c==($prevSortedSize-1)) && ($continuous_running_flag ==1) ) )   //500
                            {
                                $StartFlag = 2;
                            }
                        //}
                        $time2 = $datetime;
                        //echo "time1=".$time1."time2=".$time2."<br>";

                        if($StartFlag == 2)
                        {
                            $StartFlag=0;
                            $runtime = strtotime($time2) - strtotime($time1);
                            //echo "runtime=".$runtime."<br>";
                            if($runtime > 60)
                            {
                                //echo "runtime1=".$runtime."<br>";
                               $imei[]=$vserial[$i];
                               $vname[]=$vehicle_detail_local[0];
                               $dateFromDisplay[]=$time1;
                               $dateToDisplay[]=$time2;
                               $engine_runhr[]=$runtime;
                            } 
                        } 
                    }
                    if($StartFlag == 1)
                    {
                        $StartFlag=0;
                        $runtime = strtotime($time2) - strtotime($time1);
                        //echo "<br>runtime=".$runtime;
                        //$runhr_duration = strtotime($runtime);
                        if($runtime > 60)
                        {
                            $imei[]=$vserial[$i];
                            $vname[]=$vehicle_detail_local[0];
                            $dateFromDisplay[]=$time1;
                            $dateToDisplay[]=$time2;
                            $engine_runhr[]=$runtime;
                         } 
                    }
                }
            }			
        }
    }
	$o_cassandra->close();
        $parameterizeData=null;
	
$m1=date('M',mktime(0,0,0,$month,1));
  
  echo'<center>';   
  report_title("Engine Run Hour Report",$date1,$date2);   
	echo'<div style="overflow: auto;height: 285px; width: 600px;" align="center">';
   
 
  			             
  $j=-1;
  $k=0;
    
  $single_data_flag=1;		
  $datefrom1=array();
 $dateto1=array();
 $engine_runhr1=array(); 
 //echo "vehicleSize=".sizeof($imei)."<br>";
 //print_r($dateFromDisplay);
  for($i=0;$i<sizeof($imei);$i++)
	{	    						                  
    if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
    {
      $k=0;                                              
      $j++;
      $sum_engine_runhr =0;
      /*$sum_engine_runmin =0;
      $sum_engine_runsec =0; */
      $total_engine_runhr[$j] =0;
      
      $sno = 1;
      $title='Engine Run Hour Report : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";;
      $vname1[$j][$k] = $vname[$i];
      $imei1[$j][$k] = $imei[$i];
      
      echo'
      <br><table align="center">
      <tr>
      	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
      </tr>
      </table>
      <table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
      <tr>
			<td class="text" align="left"><b>SNo</b></td>
			<td class="text" align="left"><b>Start Time </b></td>
			<td class="text" align="left"><b>End Time </b></td>
			<td class="text" align="left"><b>Engine Run Hour (hr:min:sec)</b></td>								
      </tr>';  								
    }                                                                        				
    
    $sum_engine_runhr = $sum_engine_runhr + $engine_runhr[$i];
            		              
    echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
		echo'<td class="text" align="left">'.$dateFromDisplay[$i].'</td>';		
    echo'<td class="text" align="left">'.$dateToDisplay[$i].'</td>';
    
    $hms1 = secondsToTime($engine_runhr[$i]);
    $duration1 = $hms1[h].":".$hms1[m].":".$hms1[s];
    			
		echo'<td class="text" align="left">'.$duration1.'</td>';					
		echo'</tr>';	          		
    
    $datefrom1[$j][$k] = $dateFromDisplay[$i];	
    $dateto1[$j][$k] = $dateToDisplay[$i];										
    $engine_runhr1[$j][$k] = $duration1;
    //echo "<br>engine_run=".$engine_runhr1[$j][$k]." ,i=".$i." ,j=".$j." ,k=".$k;
    //echo "<br>imei[i+1]=".$imei[$i+1]." ,ime[i]=".$imei[$i];     			    				  				
	
	  if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
    {       
        //echo "<br>IN";
        $single_data_flag = 0;
        echo '<tr style="height:20px;background-color:lightgrey">
        <td class="text"><strong>Total<strong>&nbsp;</td>
  			<td class="text"><strong>'.$date1.'</strong></td>	
        <td class="text"><strong>'.$date2.'</strong></td>';									
                         
        /*$quotient_min =  (int)($sum_engine_runsec / 60);
        $remainder_sec =  (int)($sum_engine_runsec % 60);
        $sum_engine_runmin =  (int) ($sum_engine_runmin + $quotient_min);
        $quotient_hr =  (int)($sum_engine_runmin / 60);
        $remainder_min =  (int)($sum_engine_runmin % 60);
        $sum_engine_runhr = (int) ($sum_engine_runhr + $quotient_hr);  */
        
  			//echo "<br>sum_engine_runhr=".$sum_engine_runhr." ,k=".$k;
        //if($k>1)
  			{        
          $hms_2 = secondsToTime($sum_engine_runhr);                   
          $total_engine_runhr[$j] = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
          //echo "<br>total_engine_runhr[j]=".$total_engine_runhr[$j]; 
        }  
        //echo "<br>sum_engine_runhr =".$sum_engine_runhr." ,sum_engine_runmin=".$sum_engine_runmin;
        //echo "<br>quotient_hr =".$quotient_hr." ,remainder_min=".$remainder_min;
        //$total_engine_runhr[$j] = $sum_engine_runhr.":".$remainder_min.":".$remainder_sec;
        //echo'<td class="text"><font color="red"><strong>'.$total_engine_runhr[$j].'</strong></font></td>';        
        
  			echo'<td class="text"><font color="red"><strong>'.$total_engine_runhr[$j].'</strong></font></td>';
  			echo'</tr>'; 
        echo '</table>';
        
        $no_of_data[$j] = $k;        
			//}
      /*else
      {
        $hms_2 = secondsToTime($sum_engine_runhr);
        $total_engine_runhr[$j] = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
         
         $total_engine_runhr[$j] = $engine_runhr[$i];
         echo'<td class="text"><font color="red"><strong>'.$total_engine_runhr[$j].'</strong></font></td>';
      }	*/																	
			
			//echo'</tr>'; 
      //echo '</table>';
      
      //$no_of_data[$j] = $k;
		}  
		
    //echo "<br>OUT";
    $k++;   
    $sno++;                       							  		
 }
 
 
if($single_data_flag)
{
  echo '<tr style="height:20px;background-color:lightgrey">
  <td class="text"><strong>Total<strong>&nbsp;</td>
  <td class="text"><strong>'.$date1.'</strong></td>	
  <td class="text"><strong>'.$date2.'</strong></td>';									
                   
  /*$quotient_min =  (int)($sum_engine_runsec / 60);
  $remainder_sec =  (int)($sum_engine_runsec % 60);
  $sum_engine_runmin =  (int) ($sum_engine_runmin + $quotient_min);
  $quotient_hr =  (int)($sum_engine_runmin / 60);
  $remainder_min =  (int)($sum_engine_runmin % 60);
  $sum_engine_runhr = (int) ($sum_engine_runhr + $quotient_hr);  */
  
  //echo "<br>SINGLE::j=".$j." ,sum_engine=".$sum_engine_runhr;
  //if($k>1)
  {        
    $hms_2 = secondsToTime($sum_engine_runhr);
    $total_engine_runhr[$j] = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
  }  
  //echo "<br>sum_engine_runhr =".$sum_engine_runhr." ,sum_engine_runmin=".$sum_engine_runmin;
  //echo "<br>quotient_hr =".$quotient_hr." ,remainder_min=".$remainder_min;
  //$total_engine_runhr[$j] = $sum_engine_runhr.":".$remainder_min.":".$remainder_sec;
  //echo'<td class="text"><font color="red"><strong>'.$total_engine_runhr[$j].'</strong></font></td>';        
  
  echo'<td class="text"><font color="red"><strong>'.$total_engine_runhr[$j].'</strong></font></td>';
  echo'</tr>'; 
  echo '</table>';
  
  $no_of_data[$j] = $k; 
}

 
  echo "</div>";     

 	echo'<form method = "post" target="_blank">';
	
  $csv_string = "";
	//echo "<br>j=".$j;
  for($x=0;$x<=$j;$x++)
	{								
		$title = $vname1[$x][0]." (".$imei1[$x][0]."): Engine RunHour Report- From DateTime : ".$date1."-".$date2;
		$csv_string = $csv_string.$title."\n";
    $csv_string = $csv_string."SNo,Start Time From,End Time,Engine Run Hour(hrs.min)\n";
    echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
		
		$sno=0;
		//echo "<br>noofdata=".$no_of_data[$x];
		
    for($y=0;$y<=$no_of_data[$x];$y++)
		{
			//$k=$j-1;
			$sno++;
                    
      $datetmp1 = $datefrom1[$x][$y];	
			$datetmp2 = $dateto1[$x][$y];										
			$engine_runhr_tmp = $engine_runhr1[$x][$y];
			//echo "<br>x=".$x." ,y=".$y." ,temp_runhr=".$engine_runhr_tmp;
			
			//echo "dt=".$datetmp1;								
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][Start Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][End Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$engine_runhr_tmp\" NAME=\"temp[$x][$y][Engine Run Hour (hr:min:sec)]\">";
      
      $csv_string = $csv_string.$sno.','.$datetmp1.','.$datetmp2.','.$engine_runhr_tmp."\n";         																	
		}		

    echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Start Time]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][End Time]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Engine Run Hour (hr:min:sec)]\">";									
		
		$m = $y+1;								
		
		echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][Start Time]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][End Time]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$total_engine_runhr[$x]\" NAME=\"temp[$x][$m][Engine Run Hour (hr:min:sec)]\">";
		$csv_string = $csv_string."\nTotal,".$date1.",".$date2.",".$total_engine_runhr[$x]."\n\n";       
	
    //if($y>1)
		//{        		
    //}																																										
	}																						
      
  echo'	
    <table align="center">
		<tr>
			<td>';
      
  		if(sizeof($imei)==0)
  		{						
  			print"<center><FONT color=\"Red\" size=2><strong>Sorry! No Engine Run Hour Record Found</strong></font></center>";
  			echo'<br><br>';
  		}	
  		else
  		{
        echo'<input TYPE="hidden" VALUE="engine_runhr" NAME="csv_type">';
        echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
        echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
        <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';      
      }
                  
      echo'</td>		
    </tr>
		</table>
		</form>
 ';
	echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';			 
                     
?>							 					
