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


$sortBy="h"; /////// device date time	
$firstDataFlag=0;
$endDateTS=strtotime($date2);

$parameterizeData=new parameterizeData();
$parameterizeData->batteryVoltage='r';

get_All_Dates($datefrom, $dateto, $userdates);    
$date_size = sizeof($userdates);	

for($i=0;$i<$vsize;$i++)
{
    $vehicle_info=get_vehicle_info($root,$vserial[$i]);
    $vehicle_detail_local=explode(",",$vehicle_info);
    
    $CurrentLat = 0.0;
    $CurrentLong = 0.0;
    $LastLat = 0.0;
    $LastLong = 0.0;
    $firstData = 0;
    $distance =0.0;
    $total_dist = 0.0;
    $firstdata_flag =0;
    
    for($di=0;$di<=($date_size-1);$di++)
    {
        $SortedDataObject=new data();
        readFileXmlNew($vserial[$i],$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
        //var_dump($SortedDataObject);
        if(count($SortedDataObject->deviceDatetime)>0)
	{            
            $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
            for($obi=0;$obi<$prevSortedSize;$obi++)
            {
                $supv = $SortedDataObject->batteryVoltageData[$obi];
                $datetime = $SortedDataObject->deviceDatetime[$obi];
                //echo "battery_voltage=".$supv."<br>";
                //echo "battery_voltage=".$datetime."<br>";
                if($firstdata_flag==0)
                {					
                    $firstdata_flag = 1;
                    $interval = (double)$userInterval*60;
                    $time1 = $datetime;					
                    $date_secs1 = strtotime($time1);
                    $date_secs1 = (double)($date_secs1 + $interval); 
                    $date_secs2 = 0; 

                    $imei[]=$vserial[$i];
                    $vname[]=$vehicle_detail_local[0];
                    $datetimeDisplay[]=$time1;
                    $supVoltageDisplay[]=$supv;
                } 
                else
                {                           					
                    // echo "<br>Total lines orig=".$total_lines." ,c=".$c;
                    $time2 = $datetime;											
                    $date_secs2 = strtotime($time2);	
                    //echo "<br>Next".$date_secs2;
                    if($date_secs2 >= $date_secs1)
                    {	                                  						
                        $imei[]	=$vserial[$i];
                        $vname[]=$vehicle_detail_local[0];
                        $datetimeDisplay[]=$time2;
                        $supVoltageDisplay[]=$supv;
                        
                        $time1 = $datetime;
                        $date_secs1 = strtotime($time1);
                        $date_secs1 = (double)($date_secs1 + $interval);
                    }  //if datesec2 
                }   // else closed 
            }
            $SortedDataObject=null;	
	}
    }
	
}
$parameterizeData=null;
$o_cassandra->close();

//print_r($imei);

echo'<center><br>';
report_title("Battery Voltage Report",$date1,$date2);
echo'<div style="overflow: auto;height: 300px; width: 620px;" align="center">';	
	$j=-1;
	$k=0;			 
	for($i=0;$i<sizeof($imei);$i++)
	{								              
		if(($i===0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
		{
			$k=0;                                              
			$j++;      
			$sno = 1;
			$title='Battery Voltage : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
			$vname1[$j][$k] = $vname[$i];
			$imei1[$j][$k] = $imei[$i];       
			echo'
			<br>
			<table align="center">
				<tr>
					<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
				</tr>
			</table>
			<table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
				<tr>
					<td class="text" align="left"><b>SNo</b></td>
					<td class="text" align="left"><b>DateTime</b></td>			
					<td class="text" align="left"><b>Battery Voltage</b></td>								
				</tr>';  								
		}                                                                        		
	              
		echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
		echo'<td class="text" align="left">'.$datetimeDisplay[$i].'</td>';				
		echo'<td class="text" align="left">'.round($supVoltageDisplay[$i],2).'</td>';					
		echo'</tr>';	          		
		//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
    
		$datetime1[$j][$k] = $datetimeDisplay[$i];										
		$supv1[$j][$k] = round($supVoltageDisplay[$i],2);       			    				  				
	
		if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
		{
			echo '<tr style="height:20px;background-color:lightgrey">
			<td class="text"><strong><strong>&nbsp;</td>
			<td class="text"><strong></strong></td>	
			<td class="text"><strong></strong></td>';									

			echo'<td class="text"><font color="red"><strong></strong></font></td>';
			echo'</tr>'; 
			echo '</table>';
			$no_of_data[$j] = $k;
		} 
		$k++;   
		$sno++;                       							  		
	}
  echo'</div>
	<form method = "post" target="_blank">';
	$csv_string = "";
	for($x=0;$x<=$j;$x++)
	{								
		$title = $vname1[$x][0]." (".$imei1[$x][0]."): Battery Voltage- From DateTime : ".$date1."-".$date2." (interval:".$user_interval." mins)";
		$csv_string = $csv_string.$title."\n";
		$csv_string = $csv_string."SNo,DateTime,Battery Voltage\n";
		echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
		$sno=0;
		for($y=0;$y<=$no_of_data[$x];$y++)
		{
			//$k=$j-1;
			$sno++;                    
			$datetmp1 = $datetime1[$x][$y];									
			$supvtmp = $supv1[$x][$y];			
			//echo "dt=".$datetmp1;								
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][DateTime]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$supvtmp\" NAME=\"temp[$x][$y][Battery Voltage]\">";
			$csv_string = $csv_string.$sno.','.$datetmp1.','.$supvtmp."\n";      																	
		}
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Battery Voltage]\">";
		$m = $y+1;								
	}																						
      
  echo'	
    <table align="center">
		<tr>
			<td>';
      
				if(sizeof($imei)==0)
				{						
					print"<center><FONT color=\"Red\" size=2><strong>No Battery Voltage Record Found</strong></font></center>";
					//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
					echo'<br><br>';
				}	
				else
				{
					echo'<input TYPE="hidden" VALUE="Battery" NAME="csv_type">
					<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
					echo'<br>
						<center>
							<input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">
							&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
							<!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;
						</center>';      
				}
                  
      echo'</td>		
		</tr>
	</table>
	<br>
</form>';
	echo'</center>
	<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';	
//////// read filtered code
?>
