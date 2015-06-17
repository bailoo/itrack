<?php
	//error_reporting(-1);
	//ini_set('display_errors', 'On');
	set_time_limit(300);	
	date_default_timezone_set("Asia/Kolkata");
	include_once("main_vehicle_information_1.php");
	include_once('Hierarchy.php');
	$root=$_SESSION["root"];
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$report = "door open";
	include_once("calculate_distance.php");
	include_once("report_title.php");	
	include_once("util.hr_min_sec.php");
	
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

	$userInterval = "0";
	$requiredData="All";
	$sortBy="h";
	$firstDataFlag=0;
	$endDateTS=strtotime($date2);
	$dataCnt=0;
	
	$finalVNameArr=array();	
	$parameterizeData=new parameterizeData();
	$parameterizeData->latitude="d";
	$parameterizeData->longitude="e";
	for($i=0;$i<$vsize;$i++)
	{
		$dataCnt=0;
		//echo "vs=".$vserial[$i]."<br>";
		$vehicle_info=get_vehicle_info($root,$vserial[$i]);
		//echo "vehicleInfo=".$vehicle_info."<br>";
		$vehicle_detail_local=explode(",",$vehicle_info);
		$finalVNameArr[$i]=$vehicle_detail_local[0];
		//echo "vehcileName=".$finalVNameArr[$i]." vSerial=".$vehicle_detail_local[0]."<br>";
		
		$ioArr=explode(":",$vehicle_detail_local[7]);
		$ioFoundFlag=0;
		$ioArrSize=sizeof($ioArr);		
		for($z=0;$z<$ioArrSize;$z++)
		{
			$tempIo=explode("^",$ioArr[$z]);
			//echo "io=".$ioArr[$z]."<br>";
			if($tempIo[1]=="door_open")
			{			
				$parameterizeData->doorOpen1=$finalIoArr[$tempIo[0]];
			}
			if($tempIo[1]=="door_open2")
			{		
				$parameterizeData->doorOpen2=$finalIoArr[$tempIo[0]];
			}
			if($tempIo[1]=="door_open3")
			{			
				$parameterizeData->doorOpen3=$finalIoArr[$tempIo[0]];
			}
		}
		/*echo "doorOpen1=".$parameterizeData->doorOpen1."<br>";
		echo "doorOpen2=".$parameterizeData->doorOpen2."<br>";
		echo "doorOpen3=".$parameterizeData->doorOpen3."<br>";*/
			
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
	
		/*echo "udt1=".$UnSortedDataObject->deviceDatetime[0]."<br>";
		echo "udt2=".$UnSortedDataObject->deviceDatetime[1]."<br>";	
		echo "sdo1=".$UnSortedDataObject->doorOpen1Data[0]."<br>";
		echo "sdo1=".$UnSortedDataObject->doorOpen1Data[1]."<br>";
		echo "sdo2=".$UnSortedDataObject->doorOpen2Data[0]."<br>";
		echo "sdo2=".$UnSortedDataObject->doorOpen2Data[1]."<br>";
		echo "sdo3=".$UnSortedDataObject->doorOpen3Data[0]."<br>";
		echo "sdo3=".$UnSortedDataObject->doorOpen3Data[1]."<br>";*/
		
		/*echo "sodt1=".$SortedDataObject->deviceDatetime[0]."<br>";
		echo "sodt2=".$SortedDataObject->deviceDatetime[1]."<br>";	
		echo "sdo1=".$SortedDataObject->doorOpen1Data[0]."<br>";
		echo "sdo1=".$SortedDataObject->doorOpen1Data[1]."<br>";
		echo "sdo2=".$SortedDataObject->doorOpen2Data[0]."<br>";
		echo "sdo2=".$SortedDataObject->doorOpen2Data[1]."<br>";
		echo "sdo3=".$SortedDataObject->doorOpen3Data[0]."<br>";
		echo "sdo3=".$SortedDataObject->doorOpen3Data[1]."<br>";
		echo "<br><br>";*/
		
		if(count($SortedDataObject->deviceDatetime)>0)
		{
			//echo "in sorted=".$SortedDataObject->deviceDatetime."<br><br><br><br><br><br>";
			$prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
			for($obi=0;$obi<$prevSortedSize;$obi++)
			{			
				$finalDateTimeArr[$i][$dataCnt]=$SortedDataObject->deviceDatetime[$obi];
				$finalLatitudeArr[$i][$dataCnt]=$SortedDataObject->latitudeData[$obi];
				$finalLongitudeArr[$i][$dataCnt]=$SortedDataObject->longitudeData[$obi];
				if($parameterizeData->doorOpen1!=null)
				{
					$finalDoorOpen1Arr[$i][$dataCnt]=$SortedDataObject->doorOpen1Data[$obi];
				}		
				if($parameterizeData->doorOpen2!=null)
				{
					$finalDoorOpen2Arr[$i][$dataCnt]=$SortedDataObject->doorOpen2Data[$obi];
				}
				if($parameterizeData->doorOpen3!=null)
				{
					$finalDoorOpen3Arr[$i][$dataCnt]=$SortedDataObject->doorOpen3Data[$obi];
				}			
				$dataCnt++;
			}
		}
		if(count($UnSortedDataObject->deviceDatetime)>0)
		{
			$sortObjTmp=sortData($UnSortedDataObject,$sortBy,$parameterizeData);
			//var_dump($sortObjTmp);
			/*echo"sdt1=".$sortObjTmp->deviceDatetime[0]."<br>";
			echo "sdt2=".$sortObjTmp->deviceDatetime[1]."<br>";	
			echo "sdo1=".$sortObjTmp->doorOpen1Data[0]."<br>";
			echo "sdo1=".$sortObjTmp->doorOpen1Data[1]."<br>";
			echo "sdo2=".$sortObjTmp->doorOpen2Data[0]."<br>";
			echo "sdo2=".$sortObjTmp->doorOpen2Data[1]."<br>";
			echo "sdo3=".$sortObjTmp->doorOpen3Data[0]."<br>";
			echo "sdo3=".$sortObjTmp->doorOpen3Data[1]."<br>";*/
			//echo "<br><br>";
			$sortedSize=sizeof($sortObjTmp->deviceDatetime);
			for($obi=0;$obi<$sortedSize;$obi++)
			{				
				$finalDateTimeArr[$i][$dataCnt]=$sortObjTmp->deviceDatetime[$obi];
				$finalLatitudeArr[$i][$dataCnt]=$sortObjTmp->latitudeData[$obi];
				$finalLongitudeArr[$i][$dataCnt]=$sortObjTmp->longitudeData[$obi];
				if($parameterizeData->doorOpen1!=null)
				{
					$finalDoorOpen1Arr[$i][$dataCnt]=$sortObjTmp->doorOpen1Data[$obi];
				}		
				if($parameterizeData->doorOpen2!=null)
				{
					$finalDoorOpen2Arr[$i][$dataCnt]=$sortObjTmp->doorOpen2Data[$obi];
				}
				if($parameterizeData->doorOpen3!=null)
				{
					$finalDoorOpen3Arr[$i][$dataCnt]=$sortObjTmp->doorOpen3Data[$obi];
				}
				$dataCnt++;				
			}
		}
		$innerSize=sizeof($finalDateTimeArr[$i]);
		//echo"size=".$innerSize."<br>";
		$SortedDataObject=null;			
		$sortObjTmp=null;
		$UnsortedDataObject =null;
		$parameterizeData->doorOpen1=null;
		$parameterizeData->doorOpen2=null;
		$parameterizeData->doorOpen3=null;		
	}   
	
	//print_r($finalDateTimeArr);
	
			$type="halt";
			for($i=0;$i<$vsize;$i++)
			{
				$innerSize=0;
				$innerSize=sizeof($finalDoorOpen1Arr[$i]);
				//echo"size=".$innerSize."<br>";
				if(count($finalDoorOpen1Arr[$i])>0)
				{
					//echo "in if";					
					$firstdata_flag =0;
					$runhr_duration =0 ;
					$flag =0;

					$StartFlag=0;
					$continuous_running_flag =0;	
					$closeDataFlag=1;
					$sum_door_open =0;
					$sum_door_close=0;
					$lastTime2=0;
					$dateMatch="";
					for($j=0;$j<$innerSize;$j++)
					{
						if($j==0)
						{
							$dateMatch=explode(" ",$finalDateTimeArr[$i][$j]);
						}
						$door_count=$finalDoorOpen1Arr[$i][$j];
						$datetime=$finalDateTimeArr[$i][$j];
						
													
						//echo "doorcount=".$door_count." time1=".$datetime."<br>";
						if($door_count>=250)
						{
							$continuous_running_flag = 1;
						}

						if($door_count>250 && $StartFlag==0)  
						{                						
							$time1 = $datetime;
							$StartFlag = 1;
							
						} 							
						else if(($door_count<250 && $StartFlag==1) || ( ($j==($innerSize-1)) && ($continuous_running_flag ==1) ))   //500
						{
							//echo "StartFlag=".$StartFlag."<br>";
							$StartFlag = 2;
						}
						
						$time2 = $datetime;
				
						if($StartFlag == 2)
						{
							//echo "in startFlag2=";
							$StartFlag=0;
							$runtime = strtotime($time2) - strtotime($time1);
							if($runtime > 60)
							{
								$runtime = strtotime($date2) - strtotime($date1);
								$hms1 = secondsToTime($runtime);
								$runTimeFormat = $hms1[h].":".$hms1[m].":".$hms1[s];
								
								$closeDataFlag=0;
								$lat=$finalLatitudeArr[$i][$j];
								$lng=$finalLongitudeArr[$i][$j];
								
								$dO1VSerial[] = $vserial[$i];
								$dO1VName[] = $finalVNameArr[$i];
								$dO1Lat[] = $lat;
								$dO1Lng[] = $lng;
								$dO1Time1[] = $time1;
								$dO1Time2[] = $time2;
								$dO1RunTime[] =$runTimeFormat;	
							} 
						}
						if(!preg_match("/$dateMatch[0]/i", $datetime))
						{
							$dateMatch=explode(" ",$finalDateTimeArr[$i][$j]);
							if($StartFlag == 1)
							{
								//echo "in StartFlag<br>";
								$StartFlag=0;
								$runtime = strtotime($time2) - strtotime($time1);
								//echo "<br>runtime=".$runtime;
								//$runhr_duration = strtotime($runtime);
								if($runtime > 60)
								{	
									$runtime = strtotime($date2) - strtotime($date1);
									$hms1 = secondsToTime($runtime);
									$runTimeFormat = $hms1[h].":".$hms1[m].":".$hms1[s];
									
									$closeDataFlag=0;
									$lat=$finalLatitudeArr[$i][$j];
									$lng=$finalLongitudeArr[$i][$j];
									
									$dO1VSerial[] = $vserial[$i];
									$dO1VName[] = $finalVNameArr[$i];
									$dO1Lat[] = $lat;
									$dO1Lng[] = $lng;
									$dO1Time1[] = $time1;
									$dO1Time2[] = $time2;
									$dO1RunTime[] =$runTimeFormat;								
								}
							}
						}
					}
					if($closeDataFlag==1)
					{
						$runtime = strtotime($date2) - strtotime($date1);
						$hms1 = secondsToTime($runtime);
						$runTimeFormat = $hms1[h].":".$hms1[m].":".$hms1[s];
					
						$dO1VSerial[] = $vserial[$i];
						$dO1VName[] = $finalVNameArr[$i];
						$dO1Lat[] = "";
						$dO1Lng[] ="";
						$dO1Time1[] = $date1;
						$dO1Time2[] = $date2;
						$dO1RunTime[] =$runTimeFormat;
					}
				}
	/*if($parameterizeData->doorOpen2!=null)
	{
		for($j=0;$j<$innerSize;$j++)
		{
		}
	}
	if($parameterizeData->doorOpen2!=null)
	{
		for($j=0;$j<$innerSize;$j++)
		{
		}
	}*/
}

for($i=0;$i<sizeof($imei);$i++)
{	    						                  
	if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
	{
	}
	
	if($imei[$i+1] != $imei[$i])
	{       
		//echo "<br>IN";
		$single_data_flag = 0;
		echo'<tr style="height:20px;background-color:lightgrey">
				<td class="text">
					<strong>Total<strong>&nbsp;
				</td>
				<td class="text">
					<strong>'.$date1.'</strong>
				</td>	
				<td class="text">
					<strong>'.$date2.'</strong></td>';
					//if($k>1)
					{        
					  $hms_2 = secondsToTime($sum_door_open);                   
					  $total_door_open[$j] = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
					  $chms_2 = secondsToTime($sum_door_close);                   
					  $ctotal_door_open[$j] = $chms_2[h].":".$chms_2[m].":".$chms_2[s];
					  //echo "<br>total_engine_runhr[j]=".$total_engine_runhr[$j]; 
					}
			echo'<td class="text">
					<font color="red">
						<strong>Open->'.$total_door_open[$j].'</font>,<font color="green">close->'.$ctotal_door_open[$j].'</strong>
					</font>
				</td>
			</tr>
		</table>';        
		$no_of_data[$j] = $k;
	}
}
//print_r($dO1Time1);
 
 $parameterizeData=null;	
include("map_window/floating_map_window.php");
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';	
?> 
					 					
