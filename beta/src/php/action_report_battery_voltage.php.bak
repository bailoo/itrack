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
	$dataCnt=0;
	$parameterizeData=new parameterizeData();
	$parameterizeData->batteryVoltage='r';
	
for($i=0;$i<$vsize;$i++)
{	
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
	//echo '<br><br>';
	//print_r($vehicle_detail_local);
	
	if(count($SortedDataObject->deviceDatetime)>0)
	{
		$prevSortedSize=sizeof($SortedDataObject->dateFrom);
		for($obi=0;$obi<$prevSortedSize;$obi++)
		{
			$finalVNameArr[$dataCnt]=$vehicle_detail_local[0]; // store vehicle name
			$finalVSerialArr[$dataCnt]=$vserial[$i];
			$finalDatetimeArr[$dataCnt]=$sortObjTmp->deviceDatetime[$obi];
			$finalbatteryVoltageArr[$dataCnt]=$sortObjTmp->batteryVoltageData[$obi];
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
			$finalVNameArr[$dataCnt]=$vehicle_detail_local[0]; // store vehicle name
			$finalVSerialArr[$dataCnt]=$vserial[$i];
			$finalDateTimeArr[$dataCnt]=$sortObjTmp->deviceDatetime[$obi];
			$finalIODataArr[$dataCnt]=$sortObjTmp->batteryVoltageData[$obi];
			$dataCnt++;
		}
	}
	$sortObjTmp=null;
	$UnsortedDataObject =null;
	$SortedDataObject=null;
	$parameterizeData=null;
}
//print_r($finalVNameArr);

 $j=-1;
 $reportTitle="Battery Voltage";
echo'<form  name="text_data_report" method="post" target="_blank">'.
		report_title($reportTitle." Report",$date1,$date2).'
			<center>
				<div style="overflow: auto;height: 300px; width: 620px;">';
					$reportSize=sizeof($finalVNameArr);
					for($i=0;$i<$reportSize;$i++)
					{								              
						if(($i===0) || (($i>0) && ($finalVSerialArr[$i-1] != $finalVSerialArr[$i])) )
						{
							$k=0;                                              
							$j++;
							$sno = 1;					
							$csvtitle1=$reportTitle." Report :- ".$finalVNameArr[$i]." (".$finalVSerialArr[$i]." )  DateTime :".$date1." - ".$date2." )";
							echo"<input TYPE=\"hidden\" VALUE=\"$csvtitle1\" NAME=\"title[$x]\">";
							$csv_string = $csv_string.$csvtitle1."\n";
							$csv_string = $csv_string."SNo,DateTime,Battery Voltage\n";
							$title='Battery Voltage : '.$finalVNameArr[$i]." &nbsp;
							
							<font color=red>
								(".$finalVSerialArr[$i].")
							</font>";
							echo'<table align="center">
									<tr>
										<td class="text" align="center">
											<b>'.$title.'</b> 
											<div style="height:8px;"></div>
										</td>
									</tr>
								</table>
								<table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
									<tr>
										<td class="text" align="left">
											<b>SNo</b>
										</td>
										<td class="text" align="left">
											<b>DateTime</b>
										</td>			
										<td class="text" align="left">
											<b>'.$reportTitle.'</b>
										</td>								
									</tr>';  								
						}                                                                        		
					  
								echo'<tr>
										<td class="text" align="left" width="4%">
											<b>'.$sno.'</b>
										</td>';
									echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$j][$k][Sno]\">";
									$csv_string = $csv_string.','.$sno;
				
								echo'<td class="text" align="left">
										'.$finalDateTimeArr[$i].'
									</td>';
									echo"<input TYPE=\"hidden\" VALUE=\"$finalDateTimeArr[$i]\" NAME=\"temp[$j][$k][Date Time]\">";
									$csv_string = $csv_string.','.$finalDateTimeArr[$i];
								echo'<td class="text" align="left">
										'.round($finalIODataArr[$i],2).'
									</td>';
									echo"<input TYPE=\"hidden\" VALUE=\"$finalIODataArr[$i]\" NAME=\"temp[$j][$k][".$reportTitle."]\">";
									$csv_string = $csv_string.','.$finalIODataArr[$i];
							echo'</tr>';
							$csv_string=$csv_string."\n";
							//echo "imeiFirst=".$imei[$i+1]."imeiSecond=".$imei[$i]."<br>";
							if(($i>0) && ($finalVSerialArr[$i+1] != $finalVSerialArr[$i]) )
							{	
							echo '</table>';
							} 
							$k++;
							$sno++;				
					}
  echo "</div>
  </center>"; 
	echo'<input TYPE="hidden" VALUE="full data" NAME="csv_type">';
	echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
	echo'<br><center>
	<input type="button" onclick="javascript:report_pdf_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;
	<input type="button" onclick="javascript:report_pdf_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
	<!--<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;-->
	</form>';
?>