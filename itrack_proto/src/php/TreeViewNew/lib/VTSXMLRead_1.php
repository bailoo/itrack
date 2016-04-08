<?php
set_time_limit(300);	
date_default_timezone_set("Asia/Kolkata");
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('UTIL.php');
include_once('BUG.php');


include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('data.php');
include_once("sortXmlData.php");
include_once("getXmlData.php");	

class VTSXMLRead
{
	// public static $xmlRoot = "../xml";
	public static $xmlRoot = "/var/www/html/vts/xml";
	public static $xmlDirs = array("xml_current", "xml_past");
	
	public static function getVTSFieldsData($imei, $datetimeStart, $datetimeEnd, $fields, $fieldsMin = '-', $fieldsMax = '-', $isAllData = 0, $datetimeRef = 'h')
	{
		//echo "<br>In getVTSFieldData1111";
    global $speed_status;
    $xmlRoot = self::$xmlRoot;
		$xmlDirs = self::$xmlDirs;

		// BUG::debug("IMEI         : " . $imei);
		// BUG::debug("Time Start   : " . $datetimeStart);
		// BUG::debug("Time End     : " . $datetimeEnd);
		// BUG::debug("Fields       : " . $fields);
		// BUG::debug("Fields Min   : " . $fieldsMin);
		// BUG::debug("Fields Max   : " . $fieldsMax);
		// BUG::debug("is All Data  : " . $isAllData);
		// BUG::debug("Datetime Ref : " . $datetimeRef);

		$fieldDataNull = array();

		if(strlen($imei)==0 || strlen($datetimeStart)==0 || strlen($datetimeEnd)==0 || strlen($fields)==0 || strlen($fieldsMin)==0 || strlen($fieldsMax)==0 || strlen($isAllData)==0 || strlen($datetimeRef)==0) { return $fieldDataNull; }

		$fieldsArray    = explode(":", $fields);
		$fieldsCount    = sizeof($fieldsArray);

		if($fieldsMin=="-")
		{
			for($fieldIndex = 0 ; $fieldIndex < $fieldsCount ; $fieldIndex++)
			{
				$fieldsMinArray[] = "-";
			}
		}
		else
		{
			$fieldsMinArray = explode(":", $fieldsMin);
		}

		if($fieldsMax=="-")
		{
			for($fieldIndex = 0 ; $fieldIndex < $fieldsCount ; $fieldIndex++)
			{
				$fieldsMaxArray[] = "-";
			}
		}
		else
		{
			$fieldsMaxArray = explode(":", $fieldsMax);
		}
		// BUG::debug("Total Fields : " . sizeof($fieldsArray) . " / " . sizeof($fieldsMinArray) . " / " . sizeof($fieldsMaxArray));
		// BUG::debugArray("Fields Array", $fieldsArray);
		// BUG::debugArray("Fields Min Array", $fieldsMinArray);
		// BUG::debugArray("Fields Max Array", $fieldsMaxArray);

		if((sizeof($fieldsArray)!=sizeof($fieldsMinArray)) || (sizeof($fieldsArray)!=sizeof($fieldsMaxArray))) { return $fieldDataNull; }

		$datetimeStartTS = strtotime($datetimeStart);
		$datetimeEndTS = strtotime($datetimeEnd);

		if($datetimeEndTS<$datetimeStartTS) { return $fieldDataNull; }

		$datetimeEnd1 = date('Y-m-d H:i:s', strtotime($datetimeEnd)+(1*24*60*60));
		$dateList = UTIL::getAllDates(substr($datetimeStart,0,10), substr($datetimeEnd1,0,10));
		// BUG::debug("Total Dates : " . sizeof($dateList));

		if(sizeof($dateList)<=0) { return $fieldDataNull; }
		
		
		
	$date1 = $datetimeStart;
	$date2 = $datetimeEnd;
	//echo "date1=".$date1."date2=".$date2."<br>";
	/*$date1 = str_replace("/","-",$date1);
	$date2 = str_replace("/","-",$date2);*/
	$date_1 = explode(" ",$date1);
	$date_2 = explode(" ",$date2);
	$datefrom = $date_1[0];
	$dateto = $date_2[0];
	$userInterval = "0";
	$sortBy="h";
	$firstDataFlag=0;
	$requiredData="All";
	$endDateTS=strtotime($date2);

	$parameterizeData=new parameterizeData();
	$parameterizeData->messageType='a';
	$parameterizeData->version='b';
	$parameterizeData->fix='c';
	$parameterizeData->latitude='d';
	$parameterizeData->longitude='e';
	$parameterizeData->speed='f';
	$parameterizeData->cellName='ab';
	$parameterizeData->supVoltage='r';
	$parameterizeData->dayMaxSpeed='s';
	$parameterizeData->lastHaltTime='u';
	$parameterizeData->io1='i';
	$parameterizeData->io2='j';
	$parameterizeData->io3='k';
	$parameterizeData->io4='l';
	$parameterizeData->io5='m';
	$parameterizeData->io6='n';
	$parameterizeData->io7='o';
	$parameterizeData->io8='p';
	
	$vserial[]=$imei;
	//print_r($vserial);
	$vsize=sizeof($vserial);
	
	$finalVNameArr=array();
	$finalVTypeArr=array();	
	$finalVNumArr=array();
	
	$t=time();
	$pathtowrite = $xmlRoot."/xml_tmp/tmp_".$t.".xml";
	$fh = fopen($pathtowrite, 'w') or die("can't open file 1"); // new
	fwrite($fh, "<t1>");  
	//fclose($fh);
	for($i=0;$i<$vsize;$i++)
	{
		$dataCnt=0;
		//echo "vs=".$vserial[$i]."<br>";
		$vehicle_info=get_vehicle_info($root,$vserial[$i]);
		$vehicle_detail_local=explode(",",$vehicle_info);
		
		$finalVNameArr[$i]=$vehicle_detail_local[0];
		$finalVTypeArr[$i]=$vehicle_detail_local[1];
		$finalVNumArr[$i]=$vehicle_detail_local[2];
		//echo "vehcileName=".$finalVNameArr[$i]." vSerial=".$vehicle_detail_local[0]."<br>";
			
		$LastSortedDate = getLastSortedDate($vserial[$i],$datefrom,$dateto);
		$SortedDataObject=new data();
		$UnSortedDataObject=new data();
		
		if(($LastSortedDate+24*60*60)>=$endDateTS) //All sorted data
		{	
			//echo "in if1";
			$type="sorted";
			readFileXml($vserial[$i],$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,&$SortedDataObject);
		}
		else if($LastSortedDate==null) //All Unsorted data
		{
			//echo "in if2";
			$type="unSorted";
			readFileXml($vserial[$i],$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,&$UnSortedDataObject);
		}
		else //Partially Sorted data
		{
			$LastSDate=date("Y-m-d",$LastSortedDate+24*60*60);
			//echo "in else";
			$type="sorted";					
			readFileXml($vserial[$i],$date1,$date2,$datefrom,$LastSDate,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,&$SortedDataObject);
		
			$type="unSorted";
			readFileXml($vserial[$i],$date1,$date2,$LastSDate,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,&$UnSortedDataObject);
		}

		/*echo "udt1=".$UnSortedDataObject->deviceDatetime[0]."<br>";
		echo "udt2=".$UnSortedDataObject->deviceDatetime[1]."<br>";	
		echo "udt1=".$UnSortedDataObject->speedData[0]."<br>";
		echo "udt2=".$UnSortedDataObject->speedData[1]."<br>";
		echo "<br><br>";*/
		
		if(count($SortedDataObject->deviceDatetime)>0)
		{
			//echo "in sorted=".$SortedDataObject->deviceDatetime."<br><br><br><br><br><br>";
			$prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
			for($obi=0;$obi<$prevSortedSize;$obi++)
			{
				$linetowrite="\n".'<x a="'.$SortedDataObject->messageTypeData[$obi].'" b="'.$SortedDataObject->versionData[$obi].'" c="'.$SortedDataObject->fixData[$obi].'" d="'.$SortedDataObject->latitudeData[$obi].'" e="'.$SortedDataObject->longitudeData[$obi].'" f="'.$SortedDataObject->speedData[$obi].'" g="'.$SortedDataObject->serverDatetime[$obi].'" h="'.$SortedDataObject->deviceDatetime[$obi].'" i="'.$SortedDataObject->io1Data[$obi].'" j="'.$SortedDataObject->io2Data[$obi].'" k="'.$SortedDataObject->io3Data[$obi].'" l="'.$SortedDataObject->io4Data[$obi].'" m="'.$SortedDataObject->io5Data[$obi].'" n="'.$SortedDataObject->io5Data[$obi].'" o="'.$SortedDataObject->io6Data[$obi].'" p="'.$SortedDataObject->io7Data[$obi].'" q="'.$SortedDataObject->io8Data[$obi].'" r="'.$SortedDataObject->supVoltageData[$obi].'" v="'.$vserial[$i].'" w="'.$vehicle_detail_local[0].'" x="'.$vehicle_detail_local[2].'" y="'.$vehicle_detail_local[1].'"/>';
				fwrite($fh, $linetowrite);																																																																																									
			}
		}
		if(count($UnSortedDataObject->deviceDatetime)>0)
		{
			$sortObjTmp=sortData($UnSortedDataObject,$sortBy,$parameterizeData);
			//var_dump($sortObjTmp);
			/*echo"sdt1=".$UnSortedDataObject->deviceDatetime[0]."<br>";
			echo "sdt2=".$UnSortedDataObject->deviceDatetime[1]."<br>";	
			echo "ss1=".$UnSortedDataObject->speedData[0]."<br>";
			echo "ss2=".$UnSortedDataObject->speedData[1]."<br>";
			echo "<br><br>";*/
			$unsortedSize=sizeof($UnSortedDataObject->deviceDatetime);			
			for($obi=0;$obi<$unsortedSize;$obi++)
			{
				$linetowrite="\n".'<x a="'.$UnSortedDataObject->messageTypeData[$obi].'" b="'.$UnSortedDataObject->versionData[$obi].'" c="'.$UnSortedDataObject->fixData[$obi].'" d="'.$UnSortedDataObject->latitudeData[$obi].'" e="'.$UnSortedDataObject->longitudeData[$obi].'" f="'.$UnSortedDataObject->speedData[$obi].'" g="'.$UnSortedDataObject->serverDatetime[$obi].'" h="'.$UnSortedDataObject->deviceDatetime[$obi].'" i="'.$UnSortedDataObject->io1Data[$obi].'" j="'.$UnSortedDataObject->io2Data[$obi].'" k="'.$UnSortedDataObject->io3Data[$obi].'" l="'.$UnSortedDataObject->io4Data[$obi].'" m="'.$UnSortedDataObject->io5Data[$obi].'" n="'.$UnSortedDataObject->io5Data[$obi].'" o="'.$UnSortedDataObject->io6Data[$obi].'" p="'.$UnSortedDataObject->io7Data[$obi].'" q="'.$UnSortedDataObject->io8Data[$obi].'" r="'.$UnSortedDataObject->supVoltageData[$obi].'" v="'.$vserial[$i].'" w="'.$vehicle_detail_local[0].'" x="'.$vehicle_detail_local[2].'" y="'.$vehicle_detail_local[1].'"/>';
				fwrite($fh, $linetowrite);					
			}
		}
		$innerSize=sizeof($finalDateTimeArr[$i]);
		//echo"size=".$innerSize."<br>";
		$SortedDataObject=null;			
		$sortObjTmp=null;
		$UnsortedDataObject =null;
	}
		
	$fh = fopen($pathtowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);		
			
	$file = $pathtowrite;
	if(file_exists($file))
	{
		echo "file11=".$file."<br>";
		// BUG::debug("Reading " . $file . " ...");
		// BUG::debugNoNL($date . " ");
		$xml = @fopen($file, "r");
		if($xml)
		{
			while(!feof($xml))
			{
				$line = fgets($xml, 4096);
				if(strpos($line,"x"))
				{
					$datetime = UTIL::getXMLData('/'.$datetimeRef.'="[^"]+"/', $line);
					$datetimeTS = strtotime($datetime);
					if(($datetimeTS>=$datetimeStartTS) && ($datetimeTS<=$datetimeEndTS))
					{
						$isDataValid = 1;
						for($fieldIndex = 0 ; $fieldIndex < $fieldsCount ; $fieldIndex++)
						{
							$field = $fieldsArray[$fieldIndex];
							$fieldMin = $fieldsMinArray[$fieldIndex];
							$fieldMax = $fieldsMaxArray[$fieldIndex];
							if($field=="latlng")
							{
								$lat = UTIL::getXMLData('/d="\d+\.\d+[NS]\"/', $line);
								$lng = UTIL::getXMLData('/e="\d+\.\d+[EW]\"/', $line);
								if(strlen($lat)>2 && strlen($lng)>2)
								{
									$fieldValue = $lat.":".$lng;
								}
								else
								{
									$fieldValue = "";
								}
							}
							else
							{
								$fieldValue = UTIL::getXMLData('/'.$field.'="[^"]+"/', $line);
								//echo "<br>fieldValue=".$fieldValue;
								//echo "<br>fuel_voltage=".VTSFuel::$fuel_voltage_io_global;
							}
							if($fieldMin != '-' && $fieldValue < $fieldMin) { $isDataValid = 0; }
							if($fieldMax != '-' && $fieldValue > $fieldMax) { $isDataValid = 0; }
							if(strlen($fieldValue)==0)                      { $isDataValid = $isDataValid * $isAllData; }
							$fieldsValue[$field] = $fieldValue;
						}
						// BUG::debug("is Data Valide : " . $isDataValid);
						// BUG::debugArray("Fields Value", $fieldsValue);
						if($isDataValid == 1)
						{
							//echo "<br>fuel_voltage status";
							
							for($fieldIndex = 0 ; $fieldIndex < $fieldsCount ; $fieldIndex++)
							{
								$field = $fieldsArray[$fieldIndex];
								
								if(!$speed_status)
								{
									$io_status = explode('io',VTSFuel::$fuel_voltage_io_global);
								}
								//echo "<br>io_status[1]=".$io_status[1];

								if($io_status[1]!="")
								{
									$fuel_voltage_io = VTSFuel::$fuel_voltage_io_global;
									//echo "io=".$fuel_voltage_io."<br>";
									if($fuel_voltage_io=="io1")
									{
										$fuel_voltage_io="i";
									}		
									else if($fuel_voltage_io=="io2")
									{
										$fuel_voltage_io="j";
									}
									else if($fuel_voltage_io=="io3")
									{
										$fuel_voltage_io="k";
									}
									else if($fuel_voltage_io=="io4")
									{
										$fuel_voltage_io="l";
									}
									else if($fuel_voltage_io=="io5")
									{
										$fuel_voltage_io="m";
									}
									else if($fuel_voltage_io=="io6")
									{
										$fuel_voltage_io="n";
									}
									else if($fuel_voltage_io=="io7")
									{
										$fuel_voltage_io="o";
									}
									else if($fuel_voltage_io=="io8")
									{
										$fuel_voltage_io="p";
									}
									$fuel_voltage_value = UTIL::getXMLData('/'.$fuel_voltage_io.'="[^"]+"/', $line);
									//echo "<br>fuel_voltage_value=".$fuel_voltage_value;

									if( ($fieldsValue[$field]>$fieldsMin && $fieldsValue[$field]<$fieldsMax) && ($fuel_voltage_value>20 && $fuel_voltage_value<4096) )
									{
										//echo "<br>datetime=".$datetime." ,(NEW)adc count=".$fieldsValue[$field]." ,fuel_voltage=".$fuel_voltage_value;
										$new_adc_count = $fieldsValue[$field] / $fuel_voltage_value;
										$fieldsData[$field][$datetime] = $new_adc_count;
									}
								}
								else
								{ 
									if( ($fieldsValue[$field]>$fieldsMin  && $fieldsValue[$field]<$fieldsMax) )
									{
										//echo "<br>datetime=".$datetime." ,(OLD)adc count=".$fieldsValue[$field];
										$fieldsData[$field][$datetime] = $fieldsValue[$field];
									} 
								}											
								//echo "<br>fieldsData[field][datetime]=".$fieldsData[$field][$datetime];
							}
						}
					}
				}
			}
		}
		fclose($xml);
	}
		unlink($pathtowrite);			
		// BUG::debugArray("Fields Data", $fieldsData);
		// BUG::debug("Fields : " . sizeof($fieldsData));
		// BUG::debug("Field Data : " . sizeof($fieldsData[$fieldsArray[0]]));

		if(sizeof($fieldsData[$fieldsArray[0]])==0) { return $fieldDataNull; }

		// BUG::debug("Sorting Field Data");
		$fieldsDataSort = UTIL::sortDateTimeArray($fieldsData);
		//print_r($fieldsDataSort);
		return $fieldsDataSort;
}
	
	public static function getVTSFieldData($imei, $datetimeStart, $datetimeEnd, $field, $fieldMin = '-', $fieldMax = '-')
	{
		echo "<br>In getVTSFieldData444";
    $xmlRoot = self::$xmlRoot;
		$xmlDirs = self::$xmlDirs;

		// BUG::debug("IMEI        : " . $imei);
		// BUG::debug("Time Start  : " . $datetimeStart);
		// BUG::debug("Time End    : " . $datetimeEnd);
		// BUG::debug("Field       : " . $field);
		// BUG::debug("Field Min   : " . $fieldMin);
		// BUG::debug("Field Max   : " . $fieldMax);

		$fieldDataNull['datetime'] = array();
		$fieldDataNull['datetimeTS'] = array();

		if($imei=="" || $datetimeStart=="" || $datetimeEnd=="" || $field=="" || $fieldMin=="" || $fieldMax=="") { return $fieldDataNull; }

		$datetimeStartTS = strtotime($datetimeStart);
		$datetimeEndTS = strtotime($datetimeEnd);

		if($datetimeEndTS<$datetimeStartTS) { return $fieldDataNull; }

		$datetimeEnd1 = date('Y-m-d H:i:s', strtotime($datetimeEnd)+(1*24*60*60));
		$dateList = UTIL::getAllDates(substr($datetimeStart,0,10), substr($datetimeEnd1,0,10));
		// BUG::debug("Total Dates : " . sizeof($dateList));

		if(sizeof($dateList)<=0) { return $fieldDataNull; }

		foreach($dateList as $i => $date)
		{
			foreach($xmlDirs as $xmlDir)
			{
				$file = $xmlRoot . "/" . $xmlDir . "/" . $date . "/" . $imei . ".xml";
				if(file_exists($file))
				{
					// BUG::debug("Reading " . $file . " ...");
					// BUG::debugNoNL($date . " ");
					$xml = @fopen($file, "r");
					if($xml)
					{
						while(!feof($xml))
						{
							$line = fgets($xml, 4096);
							if(strpos($line,"marker"))
							{
								$datetime = UTIL::getXMLData('/datetime="[^"]+"/', $line);
								$datetimeTS = strtotime($datetime);
								if(($datetimeTS>=$datetimeStartTS) && ($datetimeTS<=$datetimeEndTS))
								{
									if($field=="latlng")
									{
										$lat = UTIL::getXMLData('/lat="\d+\.\d+[NS]\"/', $line);
										$lng = UTIL::getXMLData('/lng="\d+\.\d+[EW]\"/', $line);
										if(strlen($lat)>2 && strlen($lng)>2)
										{
											$fieldValue = $lat.":".$lng;
										}
										else
										{
											$fieldValue = "";
										}
									}
									else
									{
										$fieldValue = UTIL::getXMLData('/'.$field.'="[^"]+"/', $line);
									}
									
									//echo "<br>fieldValue=".$fieldValue;
									
									if(strlen($fieldValue)>0)
									{
										if($fieldMin == '-' || $fieldValue >= $fieldMin)
										{
											if($fieldMax == '-' || $fieldValue <= $fieldMax)
											{
												$fieldData[$datetime] = $fieldValue;
												
												//echo "<br>fieldData[datetime]=".$fieldData[$datetime];
											}
										}
									}
								}
							}
						}
					}
					fclose($xml);
				}
			}
		}
		// BUG::debug("Field Data  : " . sizeof($fieldData));

		if(sizeof($fieldData)<=0) { return $fieldDataNull; }

		// BUG::debug("Sorting Field Data");
		$fieldDataSort = UTIL::sortDateTime($fieldData);
		return $fieldDataSort;
	}
}


?>
