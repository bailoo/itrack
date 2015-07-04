<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
set_time_limit(3000);	
date_default_timezone_set("Asia/Kolkata");
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');

include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once("calculate_distance.php");
include_once("report_title.php");
include_once("select_landmark_report.php");
include_once("util.hr_min_sec.php");
//include("get_location.php");

include_once("area_violation/check_with_range.php");
include_once("area_violation/pointLocation.php");
include("user_type_setting.php");
include_once("report_title.php");

include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('data.php');
include_once("getXmlData.php");

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
$requiredData="All";
$parameterizeData=new parameterizeData();
$parameterizeData->latitude="d";
$parameterizeData->longitude="e";

get_All_Dates($datefrom, $dateto, $userdates);    
$date_size = sizeof($userdates);

for($i=0;$i<$vsize;$i++)
{
    $dataCnt=0;
    //echo "vs=".$vserial[$i]."<br>";
    $vehicle_info=get_vehicle_info($root,$vserial[$i]);
    $vehicle_detail_local=explode(",",$vehicle_info);
    $finalVNameArr[$i]=$vehicle_detail_local[0];
    //echo "vehcileName=".$finalVNameArr[$i]." vSerial=".$vehicle_detail_local[0]."<br>";
    
    $CurrentLat = 0.0;
    $CurrentLong = 0.0;
    $LastLat = 0.0;
    $LastLong = 0.0;
    $firstData = 0;
    $distance =0.0;
    $firstdata_flag =0;
    $j = 0;
    $total_dist = 0;
    $query_geo = "SELECT geo_coord FROM geofence WHERE geo_id IN(SELECT geo_id FROM geo_assignment WHERE status=1 AND ".
                "vehicle_id IN (SELECT vehicle_id FROM vehicle_assignment WHERE device_imei_no='$vehicle_serial' AND status=1)) AND ".
                "user_account_id='$account_id' AND status=1";                          


    $res_geo = mysql_query($query_geo,$DbConnection);
    if($row_geo = mysql_fetch_object($res_geo))
    {
        $geo_coord_tmp = $row_geo->geo_coord;
        $geo_coord = base64_decode($geo_coord_tmp);

        $geo_coord = str_replace('),(',' ',$geo_coord);
        $geo_coord = str_replace('(','',$geo_coord);
        $geo_coord = str_replace(')','',$geo_coord);
        $geo_coord = str_replace(', ',',',$geo_coord);
    }
    $outflag=0; 
    
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
                    $finalDateTimeArr[$i][$dataCnt]=$SortedDataObject->deviceDatetime[$obi];
                    $finalLatitudeArr[$i][$dataCnt]=$SortedDataObject->latitudeData[$obi];
                    $finalLongitudeArr[$i][$dataCnt]=$SortedDataObject->longitudeData[$obi];
                         
                    if($geo_coord!="")
                    {                
                        check_with_range($lat, $lng, $geo_coord, $status_geo);                                
                        if(($status_geo==false ) || ($status_geo==''))
                        {
                            if($outflag==0)
                            {
                                $outflag = 1; 
                                $time1 = $datetime;   							
                                $starttime = strtotime($time1);
                                $lat1 = $lat; 
                                $lng1 = $lng; 
                            }										
                        } 
                        else if(($status_geo==true) && ($outflag==1))
                        {
                            $outflag = 0; 
                            $time2 = $datetime;   							
                            $stoptime = strtotime($time2);
                            $violated_dur = $stoptime - $starttime;
                            $hms = secondsToTime($violated_dur);
                            $duration = $hms[h].":".$hms[m].":".$hms[s]; 
                            
                            $imei_report[]= $vserial[$i];
                            $vname_report[] = $vehicle_detail_local[0];
                            $lat_report[] = $lat1;
                            $lng_report[] = $lng1;
                            $datefrom_report[] = $time1;
                            $dateto_report[] = $time2;
                            $duration_report[] = $duration;			                		                     
                        }
                    } // if geo_coord!="" closed
                    //echo "<br>Astatus=".$status." ,time2=".$time2.", outflag=".$outflag;	
                }
            }
            if($outflag==1)
            {
                    $imei_report[]= $vserial[$i];
                    $vname_report[] = $vehicle_detail_local[0];
                    $lat_report[] = $lat1;
                    $lng_report[] = $lng1;
                    $datefrom_report[] = $time1;
                    $dateto_report[] = $time2;
                    $duration_report[] = $duration;	
            }
            $SortedDataObject=null;	
        }
    }
}
$o_cassandra->close();
$parameterizeData=null;	
	

include("map_window/mapwindow_jsmodule.php");		
include("map_window/floating_map_window.php");
	
echo '<center>';

report_title("Geofence violation Report",$date1,$date2);
echo '<div align="center" style="width:100%;height:450px;overflow:auto;">'; 					
///////////////////  READ SPEED XML 	//////////////////////////////				                      
//$xml_path = $xmltowrite;
//echo "<br>xml_path=".$xml_path;
//read_area_violation_xml($xml_path, &$imei, &$vname, &$lat, &$lng, &$datefrom, $dateto, &$duration);
//convert_in_two_dimension
//echo "<br><br>size, vname=".sizeof($vname);
//////////////////////////////////////////////////////////////////////
$j=-1;

for($i=0;$i<sizeof($imei_report);$i++)
{				
  /*echo "<br>a".$i."=".$vname_report[$i];
  echo "<br>lat_report".$i."=".$lat_report[$i];
  echo "<br>lng_report".$i."=".$lng_report[$i];
  echo "<br>arrival_time".$i."=".$arrival_time[$i];
  echo "<br>dep_time".$i."=".$dep_time[$i];
  echo "<br>duration_report".$i."=".$duration_report[$i]; */
        
  if(($i===0) || (($i>0) && ($imei_report[$i-1] != $imei_report[$i])) )
  {
    $k=0;
    $j++;
    $sno = 1;
    $title="Geofence violat_reportion Report : ".$vname_report[$i]." &nbsp;<font color=red>(".$imei_report[$i].")</font>";
    $vname_report1[$j][$k] = $vname_report[$i];
    $imei_report1[$j][$k] = $imei_report[$i];
    
    echo'
    <br><table align="center">
    <tr>
    	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
    </tr>
    </table>
    <table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
    <tr>
				<td class="text" align="left" width="5%"><b>SNo</b></td>										
				<td class="text" align="left"><b>Location</b></td>
				<td class="text" align="left"><b>DateTime From</b></td>
				<td class="text" align="left"><b>DateTime To</b></td>
				<td class="text" align="left"><b>Duration (hr:min:sec)</b></td>				
    </tr>';  								
  }
        							                    
  include("get_location.php"); 
          	
	$lt1 = $lat_report[$i];
	$lng_report1 = $lng_report[$i];
	$alt1 = "-";								
	 
  /*if($access=='Zone')
	{
		get_location($lt1,$lng_report1,$alt1,&$place,$zoneid,$DbConnection);
	}
	else
	{
		get_location($lt1,$lng_report1,$alt1,&$place,$DbConnection);
	} 

	$placename[$i] = $place;*/
  
  ////////////////////////////
  $landmark="";
  get_landmark($lt1,$lng_report1,$landmark);    // CALL LANDMARK FUNCTION
	
  $place = $landmark;
  
  if($place=="")
  {
    get_location($lt1,$lng_report1,$alt1,$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
  }
	
  //echo "P:".$place;
  
  $placename[$i] = $place;
  ////////////////////////////  	
														
	echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';
											
  if($report_type='Person')
  {
    ///// 1.CONVERT DATE TIME IN DD, MM, YYYY FORMA
    $datestr = explode(' ',$datefrom_report[$i]);
    $date_tmp = $datestr[0];
    $time_tmp = $datestr[1];
    
    $date_substr = explode('-',$date_tmp);
    $year = $date_substr[0];
    $month = $date_substr[1];
    $day = $date_substr[2];
    
    $display_datetime = $day."-".$month."-".$year." ".$time_tmp;
    $datefrom_report[$i] = $display_datetime;
    
    ///// 2.CONVERT DATE TIME IN DD, MM, YYYY FORMA
    $datestr = explode(' ',$dateto_report[$i]);
    $date_tmp = $datestr[0];
    $time_tmp = $datestr[1];
    
    $date_substr = explode('-',$date_tmp);
    $year = $date_substr[0];
    $month = $date_substr[1];
    $day = $date_substr[2];
    
    $display_datetime = $day."-".$month."-".$year." ".$time_tmp;
    $dateto_report[$i] = $display_datetime;    
    ///////////////////////////////////////////////      
  }
        
  if($placename[$i]=="")
	{
		echo'<td class="text" align="left">&nbsp;</td>';
	} 
	else
	{																													
		//echo "lat_report=".$lat_reportitude[$j];
    $lt_tmp = substr($lat_report[$i], 0, -1);
    $lng_report_tmp = substr($lng_report[$i], 0, -1);
    $opt ="xyz";
    $datefrom_reporttmp = $datefrom_report[$i];
    $dateto_reporttmp = $dateto_report[$i];
    $type = "geofence_violat_reportion";
    //echo'<td class="text" align="left">'.$placename[$i].'&nbsp;<a href="javascript:map_window(\''.$vname_report[$i].'\',\''.$datefrom_reporttmp.'\',\''.$dateto_reporttmp.'\',\''.$lt_tmp.'\',\''.$lng_report_tmp.'\',\''.$type.'\');"><font color="green">Show location</font></a></td>';
    echo'<td class="text" align="left">'.$placename[$i].'&nbsp;</td>';
	}									
	echo'<td class="text" align="left">'.$datefrom_report[$i].'</td>';	
  echo'<td class="text" align="left">'.$dateto_report[$i].'</td>';		
	echo'<td class="text" align="left">'.$duration_report[$i].'</td>';								
	echo'</tr>';							
	
	$placename1[$j][$k] = $placename[$i];
	$datefrom_report1[$j][$k] = $datefrom_report[$i];
	$dateto_report1[$j][$k] = $dateto_report[$i];
  $duration_report1[$j][$k] = $duration_report[$i];
	
	//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
	$k++;   
  $sno++;      				  				
}

echo '</table>';							
//PDF CODE

$size_vserial = sizeof($imei_report);
echo '<form method = "post" target="_blank">';
$csv_string = "";
    
for($x=0;$x<=$j;$x++)
{												
    for($y=0;$y<$k;$y++)
    {          
      $pdf_place_ref = $placename1[$x][$y];
      $pdf_datefrom_report = $datefrom_report1[$x][$y];
      $pdf_dateto_report = $dateto_report1[$x][$y];
      $pdf_duration_report =  $duration_report1[$x][$y];
              
      if($y==0)
      {
      	$title="Geofence Violat_reportion Report : ".$vname_report1[$x][$y]." (".$imei_report1[$x][$y].") (datefrom_report:".$date1." to dateto_report:".$date2.")";
      	//echo "<br>pl=".$pdf_place_ref;
      	$csv_string = $csv_string.$title."\n";
      	$csv_string = $csv_string."SNo,Location,Timefrom,Timeto, Duration (Hrs.min)\n";
        echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";
      }
      														
      $sno_1 = $y+1;										
      echo"<input TYPE=\"hidden\" VALUE=\"$sno_1\" NAME=\"temp[$x][$y][SNo]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$pdf_place_ref\" NAME=\"temp[$x][$y][Location]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$pdf_datefrom_report\" NAME=\"temp[$x][$y][DateTime From]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$pdf_dateto_report\" NAME=\"temp[$x][$y][DateTime To]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$pdf_duration_report\" NAME=\"temp[$x][$y][Duration (hr:min:sec)]\">";
      
      $pdf_place_ref = str_replace(',',':',$pdf_place_ref);
      
      $csv_string = $csv_string.$sno_1.','.$pdf_place_ref.','.$pdf_datefrom_report.','.$pdf_dateto_report.','.$pdf_duration_report."\n"; 
      ////////////////////////////////////         	          
    }		
}		
				
if($size_vserial==0)
{						
	print"<center><FONT color=\"Red\" size=2><strong>No Geofence violat_report found</strong></font></center>";
	//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
	echo'<br><br>';
}	
else
{
  echo'<input TYPE="hidden" VALUE="geofence violat_reportion" NAME="csv_type">';
  echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
  echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type2.php?size='.$size_vserial.'\');" value="Get PDF" class="noprint">&nbsp;
  <input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
  <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';
}
echo '</form>';

echo '</center>
	<center>		
            <a href="javascript:showReportPrevPageNew();" class="back_css">
                &nbsp;<b>Back</b>
            </a>
	</center>';
?>
					