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
include_once("util.hrminsec.php");
include_once("calculate_distance.php");
include_once('parameterizeData.php');
include_once('data.php');
include_once("sortXmlData.php");
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


$no_gps_interval = $_POST['no_gps_interval'];
$no_data_interval = $_POST['no_data_interval'];
$skip_nogps_interval = (double)$no_gps_interval*60;
$skip_nodata_interval = (double)$no_data_interval*60;

$vname_datagap = array();
$t1_no_gps = array(array());       // t1_no_gps
$battory_voltage_arr = array(array());       // t1_no_gps
$arial_distance_arr = array(array());       // t1_no_gps
$t2_no_gps = array(array());
$t1_no_data = array(array());      // t1_no_data
$t2_no_data = array(array());
$tdiff_no_gps = array(array());
$tdiff_no_data = array(array()); 

$sortBy="h"; /////// device date time	
$firstDataFlag=0;
$requiredData="All";
$endDateTS=strtotime($date2);

$parameterizeData=new parameterizeData();
$parameterizeData->latitude='d';
$parameterizeData->longitude='e';
$parameterizeData->batteryVoltage='r';

get_All_Dates($datefrom, $dateto, $userdates);    
$date_size = sizeof($userdates);

for($i=0;$i<$vsize;$i++)
{
    $skip_interval=$skip_nogps_interval;
    $vehicle_info=get_vehicle_info($root,$vserial[$i]);
    $vehicle_detail_local=explode(",",$vehicle_info);
    $vname_datagap[$i] = $vehicle_detail_local[0];
    $firstdata_flag=0;
    $current_datetime = date("Y-m-d H:i:s");
    $current_date = date("Y-m-d");
    
    for($di=0;$di<=($date_size-1);$di++)
    {
        $valid_data = false;
        $whole_day_nogps =0;
        $no_gps_found = false;
        
        if($di==0)
        {
            //echo "<br>START";
            $startdate_local = $date1;
            $enddate_local = $userdates[$di]." 23:59:59";
            if($date_size == 1)
            {
                $enddate_local = $date2;
            }		  
        }
        else if( $di == ($date_size-1) )
        {
            //echo "<br>END";
             $startdate_local = $userdates[$di]." 00:00:00";
             $enddate_local = $date2;
        }
        else
        {
            //echo "<br>MID";
            $startdate_local = $userdates[$di]." 00:00:00";
            $enddate_local = $userdates[$di]." 23:59:59";          
        }
        $startdate=$date1;
        $SortedDataObject=new data();
        readFileXmlNew($vserial[$i],$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
        //var_dump($SortedDataObject);
        if(count($SortedDataObject->deviceDatetime)>0)
	{
            $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
            $t1 = $startdate_local;
            $t2 = $enddate_local;

            $t1NoData = $startdate_local;
            $t2NoData = $enddate_local;
            $imei=$vserial[$i];
            for($obi=0;$obi<$prevSortedSize;$obi++)
            {                
                $lat=$SortedDataObject->latitudeData[$obi];
                $lng=$SortedDataObject->longitudeData[$obi];	
                $xml_date=$SortedDataObject->deviceDatetime[$obi];
                $supv = $SortedDataObject->batteryVoltageData[$obi];
                $gps_valid = 0;	
                if(strlen($lat)>2 && strlen($lng)>2) 
                {
                    $gps_valid = 1; 
                }
                if($firstdata_flag==0)
                {
                    if($xml_date!=$userdates[$di]." 00:00:00") //// this is for first datagap report
                    {
                        //echo "in if date=".$userdates[$i]."<br>";
                        $tdiff_nodata=strtotime($xml_date)-strtotime($startdate);
                        if($tdiff_nodata>$skip_nodata_interval) /// for consitdoring first record of every date
                        {
                            $t1_no_data[$imei][] = $startdate;
                            $t2_no_data[$imei][] = $xml_date;
                            $tdiff_no_data[$imei][] = $tdiff_nodata;
                        }
                    }
                    $firstdata_flag=1;
                    $prev_xml_date=$xml_date;
                    $t1NoData= $xml_date;
                    $last_lat_prev=$lat;
                    $last_lng_prev=$lng;
                    //echo "xmldate=".$prev_xml_date."<br>";
                }
                else
                {
                    $t2NoData = $xml_date;							
                    $xml_date_sec=strtotime($xml_date);
                    $prev_xml_date_sec=strtotime($prev_xml_date);

                    $tmp_diff=$xml_date_sec-$prev_xml_date_sec;
                    //echo "xml_date_sec=".$xml_date_sec."prev_xml_date_sec=".$prev_xml_date_sec."diff=".$tmp_diff."skip_nodata_interval=".$skip_nodata_interval."<br>";
                    if($tmp_diff>$skip_nodata_interval)
                    {
                        //echo "date1=".$prev_xml_date."date2=".$xml_date."<br>";
                        $tdiff_nodata=strtotime($xml_date)-strtotime($prev_xml_date);
                        $t1_no_data[$imei][] = $prev_xml_date;
                        $t2_no_data[$imei][] = $xml_date;
                        $tdiff_no_data[$imei][] = $tdiff_nodata;
                        //echo "in if<br>";
                    }
                    //if(($no_gps_found) && (($gps_valid) || ($tmp_diff>$skip_nodata_interval)))
                    if(($no_gps_found) && ($gps_valid))
                    {
                        $tdiff = strtotime($t2)-strtotime($t1);
                        //echo "<br>LOOP: T1 = ".$t1."\tT2 = ".$t2."\tTdiff = ".$tdiff."\tTskip = ".$skip_interval."<br>";
                        if( $tdiff >= $skip_interval) // a big interval found
                        {									
                            $t1_no_gps[$imei][] = $t1;
                            $t2_no_gps[$imei][] = $t2;
                            $battory_voltage_arr[$imei][] = $supv;       // t1_no_gps
                            calculate_distance($last_lat_prev, $lat, $last_lng_prev, $lng, $distance);
                            $arial_distance_arr[$imei][] = $distance;       // t1_no_gps
                            /*if($tmp_diff>$skip_nodata_interval)
                            {
                                    $t2_no_gps[$imei][] = $prev_xml_date;
                            }
                            else
                            {
                                    $t2_no_gps[$imei][] = $t2;
                            }*/
                            $tdiff_no_gps[$imei][] = $tdiff;								
                        }
                        $no_gps_found = false;
                    }
                    if(!$gps_valid)
                    {
                        if(!$no_gps_found)
                        {
                            $no_gps_found = true;
                            $t1 = $xml_date;
                            $t1_supv= $supv;
                            $t1_lat=$lat; //////// for calculating arial distance
                            $t1_lng=$lng;
                            
                        }
                        $t2 = $xml_date;
                        $t2_lat=$lat; //////// for calculating arial distance
                        $t2_lng=$lng;
                    }
                    $valid_data = true;
                    $prev_xml_date = $xml_date;
                    $t1NoData= $xml_date;
                    ////////////////// 
                }
            }
            //####### CHECK END TIME DATAGAP	
            $validDataFlag=0;
            if($valid_data)
            {
                    //$tdiff = strtotime($enddate_local)-strtotime($t2); ////////// for no gps 	
                    $tdiff = strtotime($t2)-strtotime($t1); ////////// for no gps 										
                    $tdiffNoData = strtotime($enddate_local)-strtotime($t2NoData); ////////// for no data
                    //echo "st1=".$enddate_local."st2=".$t2NoData."<br>";
                    //echo "tdiff=".$tdiff."<br>";
                    $validDataFlag=1;
            }
            else
            {
                    //echo "in else";
                    $tdiff = strtotime($enddate_local)-strtotime($t1);  /////////////for no gps
                    $tdiffNoData = strtotime($enddate_local)-strtotime($t1NoData); ///////////// for no data
            }

            if( $tdiffNoData >= $skip_interval) // a big interval found
            {

                    if($validDataFlag==1)
                    {
                            $t1_no_data[$imei][] = $t2NoData;
                    }
                    else
                    {
                            $t1_no_data[$imei][] =$t1NoData;
                    }
                    $t2_no_data[$imei][] = $enddate_local;
                    $tdiff_no_data[$imei][] = $tdiffNoData;	
                    //#######################
                    //echo $no_data;
                    $prev_xml_date=$enddate_local;
            }	

            if((!$gps_valid) && ($tdiff >= $skip_interval)) // a big interval found
            {
                    $t1_no_gps[$imei][] = $t1;									
                    $t2_no_gps[$imei][] = $t2;
                    $tdiff_no_gps[$imei][] = $tdiff;	
                    $battory_voltage_arr[$imei][] = $t1_supv;       // t1_no_gps
                    calculate_distance($t1_lat, $t2_lat, $t1_lng, $t2_lng, $distance);
                    $arial_distance_arr[$imei][] = $distance;
                    //#######################
                    //echo $no_data;
            }
             $SortedDataObject=null;
	}
    }	
}

$o_cassandra->close();
$parameterizeData=null;

$found = false;
echo '<div align="center"><strong>DATA GAP/ NOGPS Report-'.$date1.' to '.$date2.' </strong></div><br>';

echo '<table border=1 rules=all bordercolor="#e5ecf5" align="center" cellspacing=3 cellpadding=3 width="100%">
<tr>
<td>';
echo'<div style="overflow: auto;height: 410px;" align="center" width="100%">';							  
for($i=0;$i<$vsize;$i++)
{								                   
	//echo "<br>Size=".sizeof($t1_no_data[$vserial[$i]]);
	$sno = 1;
	$title='<br><br>DATA GAP : '.$vname_datagap[$i].' &nbsp;<font color=red>('.$vserial[$i].')</font>';

	echo'
	<table align="center" cellspacing=3 cellpadding=3 width="100%">
	<tr>
	<td class="text" align="center" style="height:8px;"><b>'.$title.'</b></td>
	</tr>
	</table>
	<table border=1 rules=all bordercolor="#e5ecf5" align="center" cellspacing=3 cellpadding=3 width="100%">	
	<tr height="3%">
		<td class="text" align="left"><b>Start</b></td>
		<td class="text" align="left"><b>End</b></td>
	<td class="text" align="left"><b>Difference(H:m:s)</b></td>          
	</tr>';  								

	for($j=0;$j<sizeof($t1_no_data[$vserial[$i]]);$j++)
	{		
		$found = true;
		$diff_nodata ="";
		
		if($tdiff_no_data[$vserial[$i]][$j]!="")
		  $diff_nodata = sec_to_time($tdiff_no_data[$vserial[$i]][$j]);

		echo'<tr valign="top">';
		echo'<td class="text" align="left">'.$t1_no_data[$vserial[$i]][$j].'</td>';
		echo'<td class="text" align="left">'.$t2_no_data[$vserial[$i]][$j].'</td>';
		echo'<td class="text" align="left">'.$diff_nodata.'</td>';
		echo'</tr>';		
	}
	echo'</table><br>';
}

if(!$found)
{
	echo "<br><div><center><font color=red><strong>Sorry No data gap found</strong></font></center></div>";
}
else
{
	echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;';
}
echo '</div>'; 
                
        
echo '</td>';
echo '<td>';
//########## NO GPS
$found = false;
echo'<div style="overflow: auto;height: 410px;" align="center" width="100%">';
for($i=0;$i<$vsize;$i++)
{								                   
	//echo "<br>Size=".sizeof($t1_no_gps[$vserial[$i]]);
	$sno = 1;
	$title='<br><br>NO GPS : '.$vname_datagap[$i].' &nbsp;<font color=red>('.$vserial[$i].')</font>';

	echo'
	<table align="center" cellspacing=3 cellpadding=3 width="100%">
	<tr>
	<td class="text" align="center" style="height:8px;"><b>'.$title.'</b></td>
	</tr>
	</table>
	<table border=1 rules=all bordercolor="#e5ecf5" align="center" cellspacing=3 cellpadding=3 width="100%">	
	<tr height="3%">
		<td class="text" align="left"><b>Start</b></td>
		<td class="text" align="left"><b>End</b></td>
	<td class="text" align="left"><b>Difference(H:m:s)</b></td>  
        <td class="text" align="left"><b>Battery Voltage</b></td>
        <td class="text" align="left"><b>Distance</b></td>
	</tr>';  								

	for($j=0;$j<sizeof($t1_no_gps[$vserial[$i]]);$j++)
	{		
		$found = true;
		$diff_nogps ="";
		
		if($tdiff_no_gps[$vserial[$i]][$j]!="")
		  $diff_nogps = sec_to_time($tdiff_no_gps[$vserial[$i]][$j]); 
		echo '<tr valign="top">';
		echo'<td class="text" align="left">'.$t1_no_gps[$vserial[$i]][$j].'</td>';
		echo'<td class="text" align="left">'.$t2_no_gps[$vserial[$i]][$j].'</td>';
		echo'<td class="text" align="left">'.$diff_nogps.'</td>';
                echo'<td class="text" align="left">'.$battory_voltage_arr[$i][$j].'</td>';
                echo'<td class="text" align="left">'.$arial_distance_arr[$i][$j].'</td>';
		echo '</tr>';		
	}
	echo'</table><br>';
}
if(!$found)
{
	echo "<br><div><center><font color=red><strong>Sorry No GPS Not found</strong></font></center></div>";
}
else
{
	echo'<br><center><input type="button" onclick="javascript:report_csv_2(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv_2(\'src/php/report_csv_2.php\');" value="Get CSV" class="noprint">&nbsp;';
}
echo '</div>';
echo '</td>';
echo '</tr></table>'; 			 
					
echo '</center>';	
	

echo'<form method = "post" target="_blank">';
$csv_string="";
for($i=0;$i<$vsize;$i++)
{								                   
	//echo "<br>Size=".sizeof($t1_no_data[$vserial[$i]]);
	  								
	if(count($t1_no_data[$vserial[$i]]))
	{
		$title = $vname_datagap[$i]." (".$vserial[$i]."): Data Gap Report- From DateTime : ".$date1."-".$date2;
		$csv_string = $csv_string.$title."\n";
		$csv_string = $csv_string."SNo,Start Date Time,End Date Time,Difference(H:m:s)\n";
		echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$i]\">";
		$sno = 1;
		for($j=0;$j<sizeof($t1_no_data[$vserial[$i]]);$j++)
		{
			$found = true;
			$diff_nodata ="";
			if($tdiff_no_data[$vserial[$i]][$j]!="")
			  $diff_nodata = sec_to_time($tdiff_no_data[$vserial[$i]][$j]);
			$tmp_dgap_sd=$t1_no_data[$vserial[$i]][$j];
			$tmp_dgap_ed=$t2_no_data[$vserial[$i]][$j];			
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$i][$j][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$tmp_dgap_sd\" NAME=\"temp[$i][$j][Start Date Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$tmp_dgap_ed\" NAME=\"temp[$i][$j][End Date Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$diff_nodata\" NAME=\"temp[$i][$j][Difference(H:m:s)]\">";
			$csv_string = $csv_string.$sno.','.$tmp_dgap_sd.','.$tmp_dgap_ed.','.$diff_nodata."\n"; 			
			$sno++;
		}
	}
}
 echo'<input TYPE="hidden" VALUE="data_gap" NAME="csv_type">';
echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';
echo'</form>';

echo'<form method = "post" target="_blank">';
$csv_string="";
for($i=0;$i<$vsize;$i++)
{								                   
	//echo "<br>Size=".sizeof($t1_no_gps[$vserial[$i]]);
	 								
	if(count($t1_no_gps[$vserial[$i]]))
	{
		$title = $vname_datagap[$i]." (".$vserial[$i]."): NO GPS Report- From DateTime : ".$date1."-".$date2;
		$csv_string = $csv_string.$title."\n";
		$csv_string = $csv_string."SNo,Start Date Time,End Date Time,Difference(H:m:s)\n";
		echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$i]\">";
		$sno = 1;
		for($j=0;$j<sizeof($t1_no_gps[$vserial[$i]]);$j++)
		{		
			$diff_nogps ="";			
			if($tdiff_no_gps[$vserial[$i]][$j]!="")
			$diff_nogps = sec_to_time($tdiff_no_gps[$vserial[$i]][$j]);		
			$tmp_nogps_sd=$t1_no_gps[$vserial[$i]][$j];
			$tmp_nogps_ed=$t2_no_gps[$vserial[$i]][$j];
                        
                        $bv_pdf_csv=$battory_voltage_arr[$vserial[$i]][$j];
			$distance_pfd_csv=$arial_distance_arr[$vserial[$i]][$j];

			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$i][$j][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$tmp_nogps_sd\" NAME=\"temp[$i][$j][Start Date Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$tmp_nogps_ed\" NAME=\"temp[$i][$j][End Date Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$diff_nogps\" NAME=\"temp[$i][$j][Difference(H:m:s)]\">";
                        echo"<input TYPE=\"hidden\" VALUE=\"$bv_pdf_csv\" NAME=\"temp[$i][$j][Battery Voltage]\">";
                        echo"<input TYPE=\"hidden\" VALUE=\"$distance_pfd_csv\" NAME=\"temp[$i][$j][Distance]\">";
			$csv_string = $csv_string.$sno.','.$tmp_nogps_sd.','.$tmp_nogps_ed.','.$diff_nogps.','.$bv_pdf_csv.','.$distance_pfd_csv."\n";		
			$sno++;
		}
	}
}
echo '<input TYPE="hidden" VALUE="no_gps" NAME="csv_type2">
<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string2">';
echo"</form>";
echo'</center>
	<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';		
  	
?>
