<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once("calculate_distance.php");
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

$sortBy='h';
$firstDataFlag=0;
$requiredData="All";

$parameterizeData=new parameterizeData();
$parameterizeData->latitude="d";
$parameterizeData->longitude="e";
$ioFoundFlag=0;

if($vsize>0)
{
    get_All_Dates($datefrom, $dateto, $userdates);
    $date_size = sizeof($userdates);
   for($i=0;$i<$vsize;$i++)
    {
        $dataCnt=0;
        $vehicle_info=get_vehicle_info($root,$vserial[$i]);
        $vehicle_detail_local=explode(",",$vehicle_info);
       
        $ioArr=explode(":",$vehicle_detail_local[7]);		

        $ioArrSize=sizeof($ioArr);
        for($z=0;$z<$ioArrSize;$z++)
        {
            $tempIo=explode("^",$ioArr[$z]);
            //echo "io=".$ioArr[$z]."<br>";
            if($tempIo[1]=="engine")
            {
                $ioFoundFlag=1;
                $parameterizeData->engineRunHr=$finalIoArr[$tempIo[0]];
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
            $runhr_duration =0 ;
            $flag =0;  
            $StartFlag=0; 
            
            for($di=0;$di<=($date_size-1);$di++)
            {
                $SortedDataObject=new data();
                readFileXmlNew($vserial[$i],$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
                 if(count($SortedDataObject->deviceDatetime)>0)
                {                   
                    $sortedSize=sizeof($SortedDataObject->deviceDatetime);
                    for($obi=0;$obi<$sortedSize;$obi++)
                    {
                        $lat = $SortedDataObject->latitudeData[$obi];
                        $lng = $SortedDataObject->longitudeData[$obi];
                        if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
                        {
                            $DataValid = 1;
                        }
                        if($DataValid==1)
                        { 
              
                            $datetime = $SortedDataObject->deviceDatetime[$obi];   
                            $engine_count =$SortedDataObject->engineIOData[$obi];                                                                            	                         
                                   
                            if($engine_count>500 && $StartFlag==0)  //500
                            {                						
                                $time1 = $datetime;
                                $StartFlag = 1;
                            } 
                            else if($engine_count<500 && $StartFlag==1)   //500
                            {
                                $StartFlag = 2;
                            }
                            $time2 = $datetime;

                            if($StartFlag == 2)
                            {
                                $StartFlag=0;
                                $runtime = strtotime($time2) - strtotime($time1);
                                //echo "<br>runtime=".$runtime;
                                //$runhr_duration = strtotime($runtime);
                                $hr =  (int)(($runtime)/3600);	 
                                //$runhr_duration = round($runhr_duration,2);
                                $min =  ($runtime)%3600;
                                $sec =  (int)(($min)%60);
                                $min =  (int)(($min)/60);
                                
                                $imei[]=$vserial[$i];
                                $vname[]=$vehicle_detail_local[0];
                                $dateFromDisplay[]=$time1;
                                $dateToDisplay[]=$time2;
                                $engine_runhr[]=$hr.':'.$min.':'.$sec;
                            } 
                        } 
                    }
                    $SortedDataObject=null;
                }
            }
            if($StartFlag == 1)
            {
		$StartFlag=0;
		$runtime = strtotime($time2) - strtotime($time1);
		//echo "<br>runtime=".$runtime;
		//$runhr_duration = strtotime($runtime);
		$hr =  (int)(($runtime)/3600);	 
		//$runhr_duration = round($runhr_duration,2);
		$min =  ($runtime)%3600;
		$sec =  (int)(($min)%60);
		$min =  (int)(($min)/60);
  
		$imei[]=$vserial[$i];
                $vname[]=$vehicle_detail_local[0];
                $dateFromDisplay[]=$time1;
                $dateToDisplay[]=$time2;
                $engine_runhr[]=$hr.':'.$min.':'.$sec;
            }
          	
        }
    }
}
$parameterizeData=null;
$o_cassandra->close();

$m1=date('M',mktime(0,0,0,$month,1));
  
  echo'<center>';   
  report_title("SOS Report",$date1,$date2);   
	echo'<div style="overflow: auto;height: 285px; width: 600px;" align="center">';

  			             
  $j=-1;
  $k=0;
  $datefrom1=array();
  $dateto1=array();
  $engine_runhr1=array();
  for($i=0;$i<sizeof($imei);$i++)
	{								              
    if(($i===0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
    {
      $k=0;                                              
      $j++;
      $sum_engine_runhr =0;
      $sum_engine_runmin =0;
      $sum_engine_runsec =0;
      $total_engine_runhr[$j] =0;
      
      $sno = 1;
      $title='SOS Report : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
      $vname1[$j][$k] = $vname[$i];
      
      echo'
      <br><table align="center">
      <tr>
      	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
      </tr>
      </table>
      <table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
      <tr>
			<td class="text" align="left"><b>SNo</b></td>
			<td class="text" align="left"><b>DateTime From</b></td>
			<td class="text" align="left"><b>DateTime To</b></td>
			<td class="text" align="left"><b>SOS (hr:min:sec)</b></td>								
      </tr>';  								
    }                                                                        		
		
    $engine_runhr_str = explode(':',$engine_runhr[$i]);
    
    $hr = $engine_runhr_str[0];
    $min = $engine_runhr_str[1];
    $sec = $engine_runhr_str[2];
    
    //echo "<br>hr =".$hr." ,min=".$min." ,engine_runhr=".$engine_runhr[$i];      
    $sum_engine_runhr = $sum_engine_runhr + $hr;
    $sum_engine_runmin = $sum_engine_runmin + $min; 
    $sum_engine_runsec = $sum_engine_runmin + $sec;      
    
    //echo "<br>sum_engine_runhr =".$sum_engine_runhr." ,sum_engine_runmin=".$sum_engine_runmin;              		              
    echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
		echo'<td class="text" align="left">'.$dateFromDisplay[$i].'</td>';		
    echo'<td class="text" align="left">'.$dateToDisplay[$i].'</td>';			
		echo'<td class="text" align="left">'.$engine_runhr[$i].'</td>';					
		echo'</tr>';	          		
		//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
    
    $datefrom1[$j][$k] = $dateFromDisplay[$i];	
    $dateto1[$j][$k] = $dateToDisplay[$i];										
    $engine_runhr1[$j][$k] = $engine_runhr[$i];       			    				  				
	
	  if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
    {
      echo '<tr style="height:20px;background-color:lightgrey">
      <td class="text"><strong>Total<strong>&nbsp;</td>
			<td class="text"><strong>'.$date1.'</strong></td>	
      <td class="text"><strong>'.$date2.'</strong></td>';									
      
			if($k>0)
			{                  
        $quotient_min =  (int)($sum_engine_runsec / 60);
        $remainder_sec =  (int)($sum_engine_runsec % 60);
        $sum_engine_runmin =  (int) ($sum_engine_runmin + $quotient_min);
        $quotient_hr =  (int)($sum_engine_runmin / 60);
        $remainder_min =  (int)($sum_engine_runmin % 60);
        $sum_engine_runhr = (int) ($sum_engine_runhr + $quotient_hr);
        
        //echo "<br>sum_engine_runhr =".$sum_engine_runhr." ,sum_engine_runmin=".$sum_engine_runmin;
        //echo "<br>quotient_hr =".$quotient_hr." ,remainder_min=".$remainder_min;
        $total_engine_runhr[$j] = $sum_engine_runhr.":".$remainder_min.":".$remainder_sec;
			}																		
			echo'<td class="text"><font color="red"><strong>'.$total_engine_runhr[$j].'</strong></font></td>';
			echo'</tr>'; 
      echo '</table>';
		}  
		
    $k++;   
    $sno++;                       							  		
 }
 
 //echo "k=".$k;
 if($k==1)
 {							
    $total_engine_runhr[$j] = $engine_runhr[0];
    echo '<tr style="height:20px;background-color:lightgrey">
    <td class="text"><strong>Total<strong>&nbsp;</td>
    <td class="text"><strong>'.$date1.'</strong></td>	
    <td class="text"><strong>'.$date2.'</strong></td>';		
    echo'<td class="text"><font color="red"><strong>'.$total_engine_runhr[$j].'</strong></font></td>';
		echo'</tr>'; 
    echo '</table>';  		     
 }
 
 echo "</div>";     

	echo'<form method = "post" action="src/php/report_getpdf_type4.php?size='.$vsize.'" target="_blank">';
	
	for($x=0;$x<=$j;$x++)
	{															
		$title=$vname1[$x][0].": SOS Report(hrs:min:sec) From DateTime : ".$date1." to ".$date2;
		echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
		
		$sno=0;
		for($y=0;$y<$k;$y++)
		{
			//$k=$j-1;
			$sno++;
                    
      $datetmp1 = $datefrom1[$x][$y];	
			$datetmp2 = $dateto1[$x][$y];										
			$engine_runhr_tmp = $engine_runhr1[$x][$y];
			
			//echo "dt=".$datetmp1;								
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][DateTime From]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][DateTime To]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$engine_runhr_tmp\" NAME=\"temp[$x][$y][SOS(hrs.min)]\">";																	
		}		

		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime From]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime To]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Engine Run Hour(hrs.min)]\">";									
		
		$m = $y+1;								
		
		echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][DateTime From]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][DateTime To]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$total_engine_runhr[$x]\" NAME=\"temp[$x][$m][SOS(hrs.min)]\">";																																										
	}																						

	echo'	
    <table align="center">
		<tr>
			<td><input type="submit" value="Get PDF" class="noprint">&nbsp;
      <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;</td>
		</tr>
		</table>
		</form></center>
 ';  
    echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';       

                     
?>							 					