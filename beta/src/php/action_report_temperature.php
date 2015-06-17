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

	$sortBy=$dataBy;
	$firstDataFlag=0;
	$endDateTS=strtotime($date2);
	$dataCnt=0;	
	
	$parameterizeData=new parameterizeData();
	for($i=0;$i<$vsize;$i++)
	{
		$vehicle_info=get_vehicle_info($root,$vserial[$i]);
		$vehicle_detail_local=explode(",",$vehicle_info);
		//echo "vehicle_detail_local=".$vehicle_detail_local[7]."<br>";
		$ioArr=explode(":",$vehicle_detail_local[7]);
		$ioFoundFlag=0;
		$ioArrSize=sizeof($ioArr);
		for($z=0;$z<$ioArrSize;$z++)
		{
			$tempIo=explode("^",$ioArr[$z]);
			//echo "io=".$ioArr[$z]."<br>";
			if($tempIo[1]=="temperature")
			{
				$ioFoundFlag=1;
				$parameterizeData->temperature=$finalIoArr[$tempIo[0]];
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
				readFileXml($vserial[$i],$date1,$date2,$datefrom,$dateto,$userInterval,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
			}
			else if($LastSortedDate==null) //All Unsorted data
			{
				//echo "in if2";
				$type="unSorted";
				readFileXml($vserial[$i],$date1,$date2,$datefrom,$dateto,$userInterval,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
			}
			else //Partially Sorted data
			{
				$LastSDate=date("Y-m-d",$LastSortedDate+24*60*60);
				//echo "in else";
				$type="sorted";					
				readFileXml($vserial[$i],$date1,$date2,$datefrom,$LastSDate,$userInterval,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
			
				$type="unSorted";
				readFileXml($vserial[$i],$date1,$date2,$LastSDate,$dateto,$userInterval,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
			}
			//var_dump($UnSortedDataObject);
			//var_dump($SortedDataObject);
			//echo "databy=".$dataBy."<br>";
			if($dataBy=="h")  /// for device datetime
			{
				if(count($SortedDataObject->deviceDatetime)>0)
				{
					//echo "in if 1<br>";
					$prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
					for($obi=0;$obi<$prevSortedSize;$obi++)
					{
						$finalVNameArr[$dataCnt]=$vehicle_detail_local[0]; // store vehicle name
						$finalVSerialArr[$dataCnt]=$vserial[$i];
						$finalDateTimeArr[$dataCnt]=$SortedDataObject->deviceDatetime[$obi];				
						$finalIODataArr[$dataCnt]=$SortedDataObject->temperatureIOData[$obi];
						$dataCnt++;
					}
				}
				if(count($UnSortedDataObject->deviceDatetime)>0)
				{
					//echo "in if 2<br>";
					//var_dump($UnSortedDataObject);
					$sortObjTmp=sortData($UnSortedDataObject,$sortBy,$parameterizeData);
					//var_dump($sortObjTmp);
					$sortedSize=sizeof($sortObjTmp->deviceDatetime);
					for($obi=0;$obi<$sortedSize;$obi++)
					{
						$finalVNameArr[$dataCnt]=$vehicle_detail_local[0]; // store vehicle name
						$finalVSerialArr[$dataCnt]=$vserial[$i];
						$finalDateTimeArr[$dataCnt]=$sortObjTmp->deviceDatetime[$obi];					
						$finalIODataArr[$dataCnt]=$sortObjTmp->temperatureIOData[$obi];
						$dataCnt++;
					}
				}
			}
			else if($dataBy=="g") /// for server date time
			{
				if(count($SortedDataObject->serverDatetime)>0)
				{
					$sortObjTmp=sortData($SortedDataObject,$sortBy,$parameterizeData);
					//var_dump($sortObjTmp);
					$sortedSize=sizeof($sortObjTmp->serverDatetime);
					for($obi=0;$obi<$sortedSize;$obi++)
					{
						$finalVNameArr[$dataCnt]=$vehicle_detail_local[0]; // store vehicle name
						$finalVSerialArr[$dataCnt]=$vserial[$i];
						$finalDateTimeArr[$dataCnt]=$sortObjTmp->serverDatetime[$obi];				
						$finalIODataArr[$dataCnt]=$sortObjTmp->temperatureIOData[$obi];
						$dataCnt++;
					}
				}
				//var_dump($UnSortedDataObject);
				if(count($UnSortedDataObject->serverDatetime)>0)
				{
					$unSortedSize=sizeof($UnSortedDataObject->serverDatetime);
					for($obi=0;$obi<$unSortedSize;$obi++)
					{
						$finalVNameArr[$dataCnt]=$vehicle_detail_local[0]; // store vehicle name
						$finalVSerialArr[$dataCnt]=$vserial[$i];
						$finalDateTimeArr[$dataCnt]=$UnSortedDataObject->serverDatetime[$obi];
						$finalIODataArr[$dataCnt]=$UnSortedDataObject->temperatureIOData[$obi];
						$dataCnt++;
					}
				
				}
				$SortedDataObject=null;			
				$sortObjTmp=null;
				$UnsortedDataObject =null;
			}
		}
		else
		{
			$SortedDataObject=null;			
			$sortObjTmp=null;
			$UnsortedDataObject =null;
		}
	}
$reportTitle="Temperature";
	//print_r($finalVNameArr);
if($ioFoundFlag==0)
{
	print"<br><center><FONT color=\"Red\" size=2><strong>No Temperature Record Found For Selected Vehicle</strong></font></center>";
}
else if(count($finalVNameArr)>0)
{
echo'<form  name="text_data_report" method="post" target="_blank">'.
		report_title($reportTitle." Report",$date1,$date2).'
			<center>
				<div style="overflow: auto;height: 300px; width: 620px;">';
					$j=-1;
		
					$reportSize=sizeof($finalVNameArr);
					for($i=0;$i<$reportSize;$i++)
					{								              
						if(($i===0) || (($i>0) && ($finalVSerialArr[$i-1] != $finalVSerialArr[$i])) )
						{
							$k=0;                                              
							$j++;
							$sno = 1;
							$csvtitle1=$reportTitle." Report :- ".$finalVNameArr[$i]." 
										(".$finalVSerialArr[$i]." )  DateTime :".$date1." - ".$date2." )";
							echo"<input TYPE=\"hidden\" VALUE=\"$csvtitle1\" NAME=\"title[$x]\">";
							$csv_string = $csv_string.$csvtitle1."\n";
							$csv_string = $csv_string."SNo,DateTime,".$reportTitle."\n";
							$title=$finalVNameArr[$i]." &nbsp;
							
							<font color=red>
								(".$finalVSerialArr[$i].")
							</font>";
							echo'<br>
								<table align="center">
									<tr>
										<td class="text" align="center">
											<b>'.$title.'</b> 
											<div style="height:8px;">
											</div>
										</td>
									</tr>
								</table>
								<table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
									<tr>
										<td class="text" align="left">
											<b>SNo</b>
										</td>
										<td class="text" align="left">
											<b>Start Time</b>
										</td>										
										<td class="text" align="left">
											<b>'.$reportTitle.'</b>
										</td>								
									</tr>';  								
						}
						if($i>0)
						{
							$timeDiff=strtotime($finalDateTimeArr[$i])-strtotime($finalDateTimeArr[$i-1]);
							$tmpDiff=$finalIODataArr[$i]-$finalIODataArr[$i-1];
						}
						//echo "timeDiff=".$timeDiff." tmpDiff=".$tmpDiff." finalIODataArr=".$finalIODataArr[$i]."<br>";
						if(((!($timeDiff<60) && !(abs($tmpDiff)>10)) && ($finalIODataArr[$i]>=-30 && $finalIODataArr[$i]<=70)) || ($i==0))
						{
						echo'<tr>
								<td class="text" align="left" width="4%">
									<b>'.$sno.'</b>
								</td>';
							echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$j][$k][Sno]\">";
								$csv_string = $csv_string.','.$sno;
								echo'<td class="text" align="left">
									'.$finalDateTimeArr[$i].'
								</td>';
							echo"<input TYPE=\"hidden\" VALUE=\"$finalDateTimeArr[$i]\" NAME=\"temp[$j][$k][Date From]\">";
								$csv_string = $csv_string.','.$finalDateTimeArr[$i];
							
								$tmpValue=ltrim ($finalIODataArr[$i],'.');
							echo'<td class="text" align="left">
									'.$tmpValue.'
								</td>';	
								
							echo"<input TYPE=\"hidden\" VALUE=\"$tmpValue\" NAME=\"temp[$j][$k][$reportTitle]\">";
								$csv_string = $csv_string.','.$tmpValue;								
						echo'</tr>';
						$k++;   
						$sno++; 
						}
						$csv_string=$csv_string."\n";
						if(($i>0) && ($finalVSerialArr[$i+1] != $finalVSerialArr[$i]) )
						{	
							echo '</table>';
						}	                      							  		
					}
			echo "</div>
		</center>"; 
	echo'<input TYPE="hidden" VALUE="full data" NAME="csv_type">
		<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">
		<br><center>
		<input type="button" onclick="javascript:report_pdf_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;
		<input type="button" onclick="javascript:report_pdf_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
		<!--<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;-->
	</form>';						
}
else
{
	print"<center><FONT color=\"Red\" size=2><strong>No Temperature Record Found</strong></font></center>";
}
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';	 
?>
