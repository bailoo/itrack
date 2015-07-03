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
	$sortBy="h"; /////// device date time	
	$firstDataFlag=0;
	$endDateTS=strtotime($date2);
	
	$parameterizeData=new parameterizeData();
	$parameterizeData->batteryVoltage='r';
	
for($i=0;$i<$vsize;$i++)
{	
	$dataCnt=0;
	$LastSortedDate = getLastSortedDate($vserial[$i],$datefrom,$dateto);
	//echo "lastSortedDate=".$LastSortedDate."<br>";
	$SortedDataObject=new data();
	$UnSortedDataObject=new data();
	//echo "in for";
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
	//echo "<br><br>";
	//var_dump($SortedDataObject);
	
	$vehicle_info=get_vehicle_info($root,$vserial[$i]);
	$vehicle_detail_local=explode(",",$vehicle_info);
	$finalVNameArr[$i]=$vehicle_detail_local[0];
	//echo '<br><br>';
	//print_r($vehicle_detail_local);
	
	if(count($SortedDataObject->deviceDatetime)>0)
	{
		$prevSortedSize=sizeof($SortedDataObject->dateFrom);
		for($obi=0;$obi<$prevSortedSize;$obi++)
		{
			$finalVNameArr[$i][$dataCnt]=$vehicle_detail_local[0]; // store vehicle name
			$finalVSerialArr[$i][$dataCnt]=$vserial[$i];
			$finalLatitudeArr[$i][$dataCnt]=$SortedDataObject->latitudeData[$obi];
			$finalLongitudeArr[$i][$dataCnt]=$SortedDataObject->longitudeData[$obi];	
			$finalDatetimeArr[$i][$dataCnt]=$SortedDataObject->deviceDatetime[$obi];			
			$dataCnt++;
		}
	}
	
	if(count($UnSortedDataObject->deviceDatetime)>0)
	{
		//echo "<br><br>In if";
		$sortObjTmp=sortData($UnSortedDataObject,$sortBy,$parameterizeData);
		//echo "<br><br>";
		
		//var_dump($sortObjTmp);
		$sortedSize=sizeof($sortObjTmp->deviceDatetime);
		
		for($obi=0;$obi<$sortedSize;$obi++)
		{
			$finalVNameArr[$i][$dataCnt]=$vehicle_detail_local[0]; // store vehicle name
			$finalVSerialArr[$i][$dataCnt]=$vserial[$i];
			$finalLatitudeArr[$i][$dataCnt]=$sortObjTmp->latitudeData[$obi];
			$finalLongitudeArr[$i][$dataCnt]=$sortObjTmp->longitudeData[$obi];	
			$finalDateTimeArr[$i][$dataCnt]=$sortObjTmp->deviceDatetime[$obi];		
			$dataCnt++;
		}
	}
	$sortObjTmp=null;
	$UnsortedDataObject =null;
	$SortedDataObject=null;	
}






$no_gps_interval = $_POST['no_gps_interval'];
$no_data_interval = $_POST['no_data_interval'];

$skip_nogps_interval = (double)$no_gps_interval*60;
$skip_nodata_interval = (double)$no_data_interval*60;

//$imei_datagap = array();
$vname_datagap = array();
//$imei_datagap = array();
//$vname_datagap = array(array());

$t1_no_gps = array(array());       // t1_no_gps
$t2_no_gps = array(array());

$t1_no_data = array(array());      // t1_no_data
$t2_no_data = array(array());

$tdiff_no_gps = array(array());
$tdiff_no_data = array(array());  
//error_reporting(-1);
//ini_set('display_errors', 'On');
for($i=0;$i<$vsize;$i++)
{
	$innerSize=sizeof($finalDateTimeArr[$i]);
	$firstdata_flag=0;
	for($j=0;$j<$innerSize;$j++)
	{
		$dateSwitch1=explode(" ",$finalDateTimeArr[$i][$j]);
		$dateSwitch2=explode(" ",$finalDateTimeArr[$i][$j+1]);
		$dateSwitchEnd=explode(" ",$finalDateTimeArr[$i][$innerSize-1]);
		if($dateSwitch1[0]!=$dateSwitch2[0])
		{
			$valid_data = false;
			$whole_day_nogps =0;
			$no_gps_found = false;
		}
		if($j==0)
		{
			//echo "<br>START";
			$startdate_local = $date1;
			$enddate_local = $dateSwitch1[0]." 23:59:59";

			if($dateSwitch1[0]==$dateSwitchEnd[0]) // if only you want to access single day data
			{
				$enddate_local = $date2;
			}		  
		}
		else if( $j == ($date_size-1) )
		{
			// echo "<br>END";
			$startdate_local = $dateSwitch1[0]." 00:00:00";
			$enddate_local = $date2;
		}
		else
		{
			//  echo "<br>MID";
			$startdate_local = $dateSwitch1[0]." 00:00:00";
			$enddate_local = $dateSwitch1[0]." 23:59:59";          
		}
		
		if($dateSwitch1[0]!=$dateSwitch2[0])
		{
			$t1 = $startdate_local;
			$t2 = $enddate_local;

			$t1NoData = $startdate_local;
			$t2NoData = $enddate_local;
		}
		
		//echo "<br>line=".$line;      		
		$lat = $finalLatitudeArr[$i][$j];
		$lng = $finalLongitudeArr[$i][$j];
		$xml_date = $finalDateTimeArr[$i][$j];
		//$speed = get_xml_data('/'.$vf.'="[^"]+"/', $line);
		$gps_valid = 0;	
		if(strlen($lat)>2 && strlen($lng)>2) 
		{
			$gps_valid = 1; 
		}
		if($firstdata_flag==0)
		{
			if($xml_date!=$dateSwitch1[0]." 00:00:00") //// this is for first datagap report
			{
				//echo "in if date=".$userdates[$i]."<br>";
				$tdiff_nodata=strtotime($xml_date)-strtotime($date1);
				if($tdiff_nodata>$skip_nodata_interval) /// for consitdoring first record of every date
				{
					$t1_no_data[$imei][] = $date1;
					$t2_no_data[$imei][] = $xml_date;
					$tdiff_no_data[$imei][] = $tdiff_nodata;
				}
			}
			$firstdata_flag=1;
			$prev_xml_date=$xml_date;
			$t1NoData= $xml_date;
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
				}
				$t2 = $xml_date;
			}						
			
			$valid_data = true;
			$prev_xml_date = $xml_date;
			$t1NoData= $xml_date;
			////////////////// 
		}
		
		//####### CHECK END TIME DATAGAP
		if($dateSwitch1[0]!=$dateSwitch2[0])
		{
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
				//#######################
				//echo $no_data;
			}
		}
	}
}

      
 /////////// NO DATA OPENS ///////////////////////  
 
//////////// DISPLAY MODULE ////////////  
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

		echo '<tr valign="top">';
		echo'<td class="text" align="left">'.$t1_no_data[$vserial[$i]][$j].'</td>';
		echo'<td class="text" align="left">'.$t2_no_data[$vserial[$i]][$j].'</td>';
		echo'<td class="text" align="left">'.$diff_nodata.'</td>';
		echo '</tr>';		
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

			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$i][$j][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$tmp_nogps_sd\" NAME=\"temp[$i][$j][Start Date Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$tmp_nogps_ed\" NAME=\"temp[$i][$j][End Date Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$diff_nogps\" NAME=\"temp[$i][$j][Difference(H:m:s)]\">";
			$csv_string = $csv_string.$sno.','.$tmp_nogps_sd.','.$tmp_nogps_ed.','.$diff_nogps."\n";		
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
