<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once("calculate_distance.php");    
include_once("report_title.php");

include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('data.php');
include_once("getXmlData.php");

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
get_All_Dates($datefrom, $dateto, $userdates);    
$date_size = sizeof($userdates);

$sortBy='h';
$requiredData="All";

$parameterizeData=new parameterizeData();
$parameterizeData->latitude="d";
$parameterizeData->longitude="e";
$parameterizeData->speed="f";

if($vsize>0)
{
    for($i=0;$i<sizeof($vserial);$i++)
    {    
        $vehicle_info=get_vehicle_info($root,$vserial[$i]);
        //echo "vehicle_info=".$vehicle_info."<br>";
        $vehicle_detail_local=explode(",",$vehicle_info);	
        
        
       
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;	
	$firstdata_flag =0; 
        $j = 0;
	$total_dist = 0;
        
        $max_speed =  getMaxSpeed($vserial[$i],1,$DbConnection);
	//echo "maxSpeed=".$max_speed."<br>";
	 
        for($di=0;$di<=($date_size-1);$di++)
        { 
            $SortedDataObject=new data();
            readFileXmlNew($vserial[$i],$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
            //var_dump($SortedDataObject);
            $logcnt=0;
            $format =2;      
            $datetime = null;
            $hrs_min = null;
            $j=0; 
            $v=0;
            $f = 0;    
            if(count($SortedDataObject->deviceDatetime)>0)
            {
                $startflag = 0;
                $endflag = 0;
                $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
                for($obi=0;$obi<$prevSortedSize;$obi++)
                {
                    $DataValid = 0;
                    $lat = $SortedDataObject->latitudeData[$obi];
                    $lng = $SortedDataObject->longitudeData[$obi];
                    if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
                    {
                        $DataValid = 1;
                    }
                    if(($DataValid==1) && ($SortedDataObject->deviceDatetime[$obi] > $date1 && $SortedDataObject->deviceDatetime[$obi] < $date2))
                    { 
                        $speed = $SortedDataObject->speedData[$obi];            				                          
                        $datetime = $SortedDataObject->deviceDatetime[$obi];    
                        if($startflag == 1)
                        {
                            $tmptime1 = $violation_start_time;
                            $tmptime1 = strtotime($tmptime1);
                            $tmptime2 = $datetime;
                            $tmptime2 = strtotime($tmptime2);
                            $difftime = $tmptime2 - $tmptime1;
                            //echo "<br>diff=".$difftime." ,speed=".$speed." ,max_speed=".$max_speed;
                        }              
                        if( ($speed > $max_speed) && ($startflag==0) )
                        {
                            $speed_violated_time="";
                            $violation_start_time = $datetime;
                            $startflag = 1;
                            $endflag = 0;
                            //if($DEBUG)
                            //echo  "<br>v_start_time=".$violation_start_time;
                        }
                        else if( ( (($speed < $max_speed) && ($startflag == 1) ) || ($i==$total_lines-1) ) && ($difftime >= 60) )
                        {
                            $time1 = strtotime($violation_start_time); 
                            $time2 = strtotime($datetime);
                            $speed_violated_time = $speed_violated_time + ($time2 - $time1);   
                            //echo "<br>violated=".$speed_violated_time;
                            $endflag=1;
                        }
              
                        if(($speed_violated_time && $startflag == 1 && $endflag == 1) && ($difftime >= 60))
                        {			  
                            $violated_dur =  ($speed_violated_time)/3600;     
                            $violated_dur = round($violated_dur,2);										
                            $total_min = $violated_dur * 60;           
                            $hr = (int)($total_min / 60);
                            if($hr<10)
                            {
                                  $hr='0'.$hr;
                            }
                            $minutes = $total_min % 60;
                            if($minutes<10)
                            {
                                $minutes='0'.$minutes;
                            }				  
                            $hrs_min = $hr.":".$minutes; 	

                            $violated_time = $hrs_min;  // STORE IN SHIFT ARRAY	
                            $startflag = 0;
                            $endflag = 0;
            	  
                            $violation_end_time = $datetime;
                            $imei[]=$vserial[$i];
                            $vname[]= $vehicle_detail_local[0];
                            $time1Arr[]=$violation_start_time;
                            $time2Arr[]=$violation_end_time;
                            $violatedTimeArr[]=$violated_time;             	  
                        }
                    }
                }
            }
        }
    }
    $o_cassandra->close();
    $parameterizeData=null;	
}


include("MapWindow/MapWindow_jsmodule.php");	
?>
	
<script type="text/javascript">

	//function MapWindow(vname,datetime,lat,lng)
	function MapWindow(vname,datetime,lat,lng)
	{
		//alert(vname+" "+datetime+" "+lat+" "+lng);	
		//test2(vname,datetime,lat,lng);			
		document.getElementById("window").style.display = '';
		load_vehicle_on_map(vname,datetime,lat,lng);							
	}
		
</script>	

<?php
	
include("MapWindow/floating_map_window.php");

report_title("Speed violation Report",$date1,$date2);
	
echo '<center>';
echo '<div align="center" style="width:100%;height:450px;overflow:auto;">'; 

$j=-1;

for($i=0;$i<sizeof($imei);$i++)
{				
  /*echo "<br>a".$i."=".$vname[$i];
  echo "<br>lat".$i."=".$lat[$i];
  echo "<br>lng".$i."=".$lng[$i];
  echo "<br>arrival_time".$i."=".$arrival_time[$i];
  echo "<br>dep_time".$i."=".$dep_time[$i];
  echo "<br>duration".$i."=".$duration[$i]; */
        
  if(($i===0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
  {
    $k=0;
    $j++;
    $sno = 1;
    $title="Speed Violation Report : ".$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
    $vname1[$j][$k] = $vname[$i];
    $imei1[$j][$k] = $imei[$i];
    
    echo'
    <br><table align="center">
    <tr>
    	<td class="text" align="center"><b>'.$title.'</b> <div style="height:6px;"></div></td>
    </tr>
    </table>
    <table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
    <tr>
        <td class="text" align="left" width="5%"><b>SNo</b></td>										
        <td class="text" align="left"><b>Time1</b></td>
        <td class="text" align="left"><b>Time2</b></td>
        <td class="text" align="left"><b>Violated duration(hrs:min)</b></td>				
    </tr>';  							
  }
   								
echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';
  echo'<td class="text" align="left">'.$time1Arr[$i].'</td>';		
	echo'<td class="text" align="left">'.$time2Arr[$i].'</td>';			
	echo'<td class="text" align="left">'.$violatedTimeArr[$i].'</td>';					
	echo'</tr>';							
	
        $time1_pdf[$j][$k] = $time1Arr[$i];
        $time2_pdf[$j][$k] = $time2Arr[$i];
        $violated_time_pdf[$j][$k] = $violatedTimeArr[$i];
	
	//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
  if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
  {
    $no_of_data[$j] = $k;
    echo '</table>';
  }    		
	
	$k++;   
  $sno++;    			  				
}
//echo '</table>';							
//PDF CODE

$size_vserial = sizeof($imei);
echo '<form method="post" target="_blank">';
$csv_string = "";

for($x=0;$x<=$j;$x++)
{												
    for($y=0;$y<=$no_of_data[$x];$y++)
    {          
      $time1_pdf_tmp = $time1_pdf[$x][$y];
      $time2_pdf_tmp = $time2_pdf[$x][$y];
      $violated_time_pdf_tmp =  $violated_time_pdf[$x][$y];
              
      if($y==0 && $time1_pdf_tmp!="" && $time2_pdf_tmp!="" && $violated_time_pdf_tmp!="")
      {
      	//echo "<br>Report-x=".$x.",y=".$y;
        $title="Speed Violation Report : ".$vname1[$x][$y]." (".$imei1[$x][$y].")";
      	//echo "<br>pl=".$pdf_place_ref;
      	$csv_string = $csv_string.$title."\n";
      	$csv_string = $csv_string."SNo,Timefrom,Timeto, Violated Duration (Hrs.min)\n";      	
        echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";
      }
      														
      if($time1_pdf_tmp!="" && $time2_pdf_tmp!="" && $violated_time_pdf_tmp!="")
      {
        $sno_1 = $y+1;										
        echo"<input TYPE=\"hidden\" VALUE=\"$sno_1\" NAME=\"temp[$x][$y][SNo]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$time1_pdf_tmp\" NAME=\"temp[$x][$y][Time From]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$time2_pdf_tmp\" NAME=\"temp[$x][$y][Time To]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$violated_time_pdf_tmp\" NAME=\"temp[$x][$y][Violated Duration(hrs:min)]\">"; 
        $csv_string = $csv_string.$sno_1.','.$time1_pdf_tmp.','.$time2_pdf_tmp.','.$violated_time_pdf_tmp."\n"; 
      }     	          
    }		
}


if($size_vserial==0)
{						
	print"<center><FONT color=\"Red\" size=2><strong>No Speed violation found</strong></font></center>";
	//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
	echo'<br><br>';
}	
else
{
  echo'<input TYPE="hidden" VALUE="speed violation" NAME="csv_type">';
  echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
  echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type2.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;
  <input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
  <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';
}
echo '</form>';

/*if($size_vserial>0)
{
	echo'
			<table align="center">
				<tr><td align="center"><input type="submit" value="Get PDF" class="noprint">&nbsp;
        <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;</td></tr>
			</table>
		</form>
	';
}
else if($size_vserial==0)
{
		print"<center><FONT color=\"Red\"><strong>No Speed Voilation in This Duration</strong></font></center>";
		echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=SpeedViolation.php\">";
} */

unlink($xml_path);

echo '</center>';
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';	 
?>