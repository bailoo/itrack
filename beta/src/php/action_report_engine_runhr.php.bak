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

	for($i=0;$i<$vsize;$i++)
	{
		$vehicle_info=get_vehicle_info($root,$vserial[$i]);
		$vehicle_detail_local=explode(",",$vehicle_info);
		//echo "vehicle_detail_local=".$vehicle_detail_local[7]."<br>";
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
			//echo "temperature=".$parameterizeData->temperature."<br>";
			
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
			
			if(count($SortedDataObject->deviceDatetime)>0)
			{
				$prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
				for($obi=0;$obi<$prevSortedSize;$obi++)
				{
					$finalVNameArr[$dataCnt]=$vehicle_detail_local[0]; // store vehicle name
					$finalVSerialArr[$dataCnt]=$vserial[$i];
					$finalDateTimeArr[$dataCnt]=$SortedDataObject->deviceDatetime[$obi];				
					$finalIODataArr[$dataCnt]=$SortedDataObject->engineIOData[$obi];
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
					$finalVNameArr[$dataCnt]=$vehicle_detail_local[0]; // store vehicle name
					$finalVSerialArr[$dataCnt]=$vserial[$i];
					$finalDateTimeArr[$dataCnt]=$sortObjTmp->deviceDatetime[$obi];					
					$finalIODataArr[$dataCnt]=$sortObjTmp->engineIOData[$obi];
					$dataCnt++;
				}
			}
			$SortedDataObject=null;			
			$sortObjTmp=null;
			$UnsortedDataObject =null;
			$parameterizeData=null;
			
		}
		else
		{
			$SortedDataObject=null;			
			$sortObjTmp=null;
			$UnsortedDataObject =null;
			$parameterizeData=null;
		}
	}
	$reportTitle="Engine Run Hour Report ";
	$displayFormat="Engine Run Hour (hr:min:sec)";
	
	//print_r($finalVNameArr);
if($ioFoundFlag==0)
{
	print"<br><center><FONT color=\"Red\" size=2><strong>No ".$reportTitle." Record Found For Selected Vehicle</strong></font></center>";
}
else if(count($finalVNameArr)>0)
{
$j=-1;
$single_data_flag=1;
$csv_string = "";
echo'<form  name="text_data_report" method="post" target="_blank">'.
		report_title($reportTitle,$date1,$date2).'
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
							$csvtitle1=$reportTitle." :- ".$finalVNameArr[$i]." (".$finalVSerialArr[$i]." )  DateTime :".$date1." - ".$date2." )";
							echo"<input TYPE=\"hidden\" VALUE=\"$csvtitle1\" NAME=\"title[$x]\">";
							$csv_string = $csv_string.$csvtitle1."\n";
							$csv_string = $csv_string."SNo,Start Date Time,End Date Time,".$displayFormat."\n";
							$title=$finalVNameArr[$i]." &nbsp;							
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
											<b>Start Date Time</b>
										</td>	
										<td class="text" align="left">
											<b>End Date Time</b>
										</td>
										<td class="text" align="left">
											<b>'.$displayFormat.'</b>
										</td>								
									</tr>'; 
							$StartFlag=0;
							$continuous_running_flag=0;
							$sum_runhr =0;								
							$total_runhr =0;
						}
						if($finalIODataArr[$i]>500)
						{
							$continuous_running_flag = 1;
						}

						if($finalIODataArr[$i]>500 && $StartFlag==0)  //500
						{                						
							$time1 = $finalDateTimeArr[$i];
							$StartFlag = 1;
						} 
						else if(($finalIODataArr[$i]<500 && $StartFlag==1))    //500
						{
							$StartFlag = 2;
						}
						$time2 = $finalDateTimeArr[$i];
						
						if($StartFlag == 2)
						{
							$StartFlag=0;
							$runtime = strtotime($time2) - strtotime($time1);
							$sum_runhr = $sum_runhr + $runtime;
							if($runtime > 60)
							{							
							echo'<tr>
									<td class="text" align="left" width="4%">
										<b>'.$sno.'</b>
									</td>';
							echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$j][$k][SNo]\">";
								$csv_string = $csv_string.$sno;
				
								echo'<td class="text" align="left">
										'.$time1.'
									</td>';
									echo"<input TYPE=\"hidden\" VALUE=\"$time1\" NAME=\"temp[$j][$k][Start Date Time]\">";
									$csv_string = $csv_string.','.$time1;
							
								echo'<td class="text" align="left">
										'.$time2.'
									</td>';
									echo"<input TYPE=\"hidden\" VALUE=\"$time2\" NAME=\"temp[$j][$k][End Date Time]\">";
									$csv_string = $csv_string.','.$time2;
								
								$hms1 = secondsToTime($runtime);
								$duration1 = $hms1[h].":".$hms1[m].":".$hms1[s];
								echo'<td class="text" align="left">
										'.$duration1.'
									</td>';
									echo"<input TYPE=\"hidden\" VALUE=\"$duration1\" NAME=\"temp[$j][$k][$displayFormat]\">";
									$csv_string = $csv_string.','.$duration1;
							echo'</tr>';
							$csv_string=$csv_string."\n";
							$k++;
							$sno++;	
							}
					
						//echo "imeiFirst=".$imei[$i+1]."imeiSecond=".$imei[$i]."<br>";
						}
						if(($i>0) && ($finalVSerialArr[$i+1] != $finalVSerialArr[$i]) )
						{							
							if($continuous_running_flag ==1)
							{
								//echo "fsno=".$sno."<br>";
								$StartFlag=0;
								$runtime = strtotime($time2) - strtotime($time1);
								$sum_runhr = $sum_runhr + $runtime;
								echo'<tr>
									<td class="text" align="left" width="4%">
										<b>'.$sno.'</b>
									</td>';
							echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$j][$k][SNo]\">";
								$csv_string = $csv_string.$sno;
				
								echo'<td class="text" align="left">
										'.$time1.'
									</td>';
									echo"<input TYPE=\"hidden\" VALUE=\"$time1\" NAME=\"temp[$j][$k][Start Date Time]\">";
									$csv_string = $csv_string.','.$time1;
							
								echo'<td class="text" align="left">
										'.$time2.'
									</td>';
									echo"<input TYPE=\"hidden\" VALUE=\"$time2\" NAME=\"temp[$j][$k][End Date Time]\">";
									$csv_string = $csv_string.','.$time2;
								
								$hms1 = secondsToTime($runtime);
								$duration1 = $hms1[h].":".$hms1[m].":".$hms1[s];
								echo'<td class="text" align="left">
										'.$duration1.'
									</td>';
									echo"<input TYPE=\"hidden\" VALUE=\"$duration1\" NAME=\"temp[$j][$k][$displayFormat]\">";
									$csv_string = $csv_string.','.$duration1;
							echo'</tr>';
							$csv_string=$csv_string."\n";
							$k++;
							$sno++;	
							}
							else if($StartFlag == 1)
							{
								echo "sfsno=".$sno."<br>";
								$StartFlag=0;
								$runtime = strtotime($time2) - strtotime($time1);
								$sum_runhr = $sum_runhr + $runtime;
								echo'<tr>
									<td class="text" align="left" width="4%">
										<b>'.$sno.'</b>
									</td>';
							echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$j][$k][SNo]\">";
								$csv_string = $csv_string.$sno;
				
								echo'<td class="text" align="left">
										'.$time1.'
									</td>';
									echo"<input TYPE=\"hidden\" VALUE=\"$time1\" NAME=\"temp[$j][$k][Start Date Time]\">";
									$csv_string = $csv_string.','.$time1;
							
								echo'<td class="text" align="left">
										'.$time2.'
									</td>';
									echo"<input TYPE=\"hidden\" VALUE=\"$time2\" NAME=\"temp[$j][$k][End Date Time]\">";
									$csv_string = $csv_string.','.$time2;
								
								$hms1 = secondsToTime($runtime);
								$duration1 = $hms1[h].":".$hms1[m].":".$hms1[s];
								echo'<td class="text" align="left">
										'.$duration1.'
									</td>';
									echo"<input TYPE=\"hidden\" VALUE=\"$duration1\" NAME=\"temp[$j][$k][$displayFormat]\">";
									$csv_string = $csv_string.','.$finalIODataArr[$i];
							echo'</tr>';
							$csv_string=$csv_string."\n";
							$k++;
							$sno++;
							}
							$hms_2 = secondsToTime($sum_runhr);                   
							$total_runhr = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
							$single_data_flag = 0;
						echo'<tr style="height:20px;background-color:lightgrey">
								<td class="text">
									<strong>Total<strong>&nbsp;
								</td>
								<td class="text">
									<strong>'.$date1.'</strong>
								</td>	
								<td class="text">
									<strong>'.$date2.'</strong>
								</td>
								<td class="text">
									<font color="red">
										<strong>'.$total_runhr.'</strong>
									</font>
								</td>
							</tr>
						</table><br>';
						echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$j][$k][SNo]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$j][$k][Start Date Time]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$j][$k][End Date Time]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$total_runhr\" NAME=\"temp[$j][$k][$displayFormat]\">";
		
						$csv_string = $csv_string."\nTotal,".$date1.",".$date2.",".$total_runhr."\n\n"; 
						} 
									
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
}
else
{
	print"<center><FONT color=\"Red\" size=2><strong>No ".$reportTitle." Record Found</strong></font></center>";
}

echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';		
?>							 					
