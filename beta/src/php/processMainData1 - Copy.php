<?php
function readFileXml($vSerial,$vName,$startDate,$endDate,$userInterval,$parametersArr,$xmlParameterArr,$processDataBy,$dataValid,$firstDataFlag)
{

	$date_1 = explode(" ",$startDate);
	$date_2 = explode(" ",$endDate);
	$datefrom = $date_1[0];
	$dateto = $date_2[0];
	$timefrom = $date_1[1];
	$timeto = $date_2[1];
	get_All_Dates($datefrom, $dateto, &$userdates);
	$date_size = sizeof($userdates);
	//print_r($userdates);

	for($i=0;$i<=($date_size-1);$i++)
	{
		//echo "userid=".$userdates[$i]."<br>";
		/*if($date_size==1)
		{
			$startdate1 = $startdate;
			$enddate1 = $enddate;
		}
		else if($i==0)
		{
			$startdate1 = $startdate;
			$enddate1 = $userdates[$i]." 23:59:59";
		}
		else if($i==($date_size-1))
		{
			$startdate1 = $userdates[$i]." 00:00:00";
			$enddate1 = $enddate;
		}
		else
		{
			$startdate1 = $userdates[$i]." 00:00:00";
			$enddate1 = $userdates[$i]." 23:59:59";
		}*/
		if($userdates[$i]==date("Y-m-d"))
		{
			//echo "in if";
			readTodayXml($vSerial,$vName,$startDate,$endDate,$userInterval,$parametersArr,$xmlParameterArr,$processDataBy,$dataValid,$firstDataFlag,$userdates[$i],$date_size);
		}
		else
		{
			//echo "in readSortXml<br>";
			readSortXml($vSerial,$vName,$startDate,$endDate,$userInterval,$parametersArr,$xmlParameterArr,$processDataBy,$dataValid,$firstDataFlag,$userdates[$i],$date_size);
		}
	}
}

function readTodayXml($vSerial,$vName,$startDate,$endDate,$userInterval,$parametersArr,$xmlParameterArr,$processDataBy,$dataValid,$firstDataFlag,$xmlFileDate,$date_size)
{
	global $todayDataObject;
	global $cntCThis;

	//$xml_file = "C:\\xampp/htdocs/xml_vts/xml_data/".$xmlFileDate."/".$vSerial.".xml";
	$xml_file = "../../../xml_vts/xml_data/".$xmlFileDate."/".$vSerial.".xml";	
	//echo "xmlFile=".$xml_file." dataValid=".$dataValid."<br>";
	if($dataValid=="yes")
	{
		//echo "in if";
		//echo "data valid=".$dataValid."<br>";
		if((preg_match('/'.$xmlParameterArr[$parametersArr[3]].'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$xmlParameterArr[$parametersArr[4]].'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)))
		{ 
			$lat_value = explode('=',$lat_match[0]);
			$lng_value = explode('=',$lng_match[0]);
			//echo " lat_value=".$lat_value[1];         
			if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
			{
				if($processDataBy=="datetime")
				{
					$status = preg_match('/'.$xmlParameterArr[$parametersArr[1]].'="[^"]+/', $line, $datetime_tmp);
					$datetime_tmp1 = explode("=",$datetime_tmp[0]);
					$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);		
					$todayDataObject->cDeviceDatetime[$cntCThis] = $datetime;
				}
				else if($processDataBy=="serverDatetime")
				{
					$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
					$datetime_tmp1 = explode("=",$datetime_tmp[0]);
					$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
					$xml_date = $datetime;
					$todayDataObject->cServerDatetime[$cntCThis] = $datetime;
				}
				
				$lat_tmp1 = explode("=",$lat_tmp[0]);
				$todayDataObject->cLatitude[$cntCThis]=preg_replace('/"/', '', $lat_tmp1[1]);				
				$lng_tmp1 = explode("=",$lng_tmp[0]);		
				$todayDataObject->cLongitude[$cntCThis]= preg_replace('/"/', '', $lng_tmp1[1]);					
			}
		}
	}
	else if($dataValid=="no")
	{
		//echo "suplyVoltage=".$xmlParameterArr[$parametersArr[2]]."<br>";
		//echo "xmlFile1=".$xml_file."<br>";
		if(file_exists($xml_file)) 
		{
			//echo "<br>in exist";
			$xml = @fopen($xml_file, "r") or $fexist = 0;
			if($i==($date_size-1))
			{
				$total_lines = count(file($xml_file));
			}
			//echo "<br>Total lines orig=".$total_lines;
			while(!feof($xml))          // WHILE LINE != NULL
			{
				//$c++;
				$DataValid = 0;
				//echo fgets($file). "<br />";
				$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			

				if(strlen($line)>20)
				{
					$linetmp =  $line;
				}

				//echo "processDataBy=".$processDataBy."<br>";
				if($processDataBy=="datetime")
				{
					//echo "datetime=".$xmlParameterArr[$parametersArr[1]]."<br>";
					$status = preg_match('/'.$xmlParameterArr[$parametersArr[1]].'="[^"]+/', $line, $datetime_tmp);
				}
				else if($processDataBy=="serverDatetime")
				{
					$status = preg_match('/'.$xmlParameterArr[$parametersArr[0]].'="[^"]+/', $line, $datetime_tmp);
				}
				
				$datetime_tmp1 = explode("=",$datetime_tmp[0]);
				$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);			
				if(($datetime >= $startDate && $datetime <= $endDate) && ($datetime!="-") && ($datetime!=null))
				{
					if($firstDataFlag==0)
					{
						//echo "in first<br>";
						if($processDataBy=="datetime")
						{	
							$todayDataObject->cDeviceDatetime[$cntCThis] = $datetime;
						}
						else if($processDataBy=="serverDatetime")
						{				
							$todayDataObject->cServerDatetime[$cntCThis] = $datetime;
						}
						$todayDataObject->cVehicleName[$cntCThis] = $vName;
						$todayDataObject->cVehicleSerial[$cntCThis] = $vSerial;
						$firstDataFlag = 1;
						//echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
						$interval = (double)$userInterval*60;							

						$time1 = $datetime;					
						$date_secs1 = strtotime($time1);					
						//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
						$date_secs1 = (double)($date_secs1 + $interval); 
						$date_secs2 = 0; 
						
						if($xmlParameterArr[$parametersArr[2]]=='r') /// for suply voltage
						{
							$status = preg_match('/'.$xmlParameterArr[$parametersArr[2]].'="[^" ]+/', $line, $supv_tmp);      				
							//echo "Status2=".$status.'<BR>';
							if($status==0)
							{
								continue;               
							}     

							$supv_tmp1 = explode("=",$supv_tmp[0]);
							$todayDataObject->cSuplyVoltage[$cntCThis] = preg_replace('/"/', '', $supv_tmp1[1]);
			
						}
						else if($xmlParameterArr[$parametersArr[17]]=='ab')
						{
						
						}
						//echo "mainElementObject=".$mainElementObject->cSuplyVoltage[$cnt]."<br>";
						$cntCThis++;
					} 
					else
					{
						$time2 = $datetime;											
						$date_secs2 = strtotime($time2);	
						//echo "<br>Next".$date_secs2;      					
						if($date_secs2 >= $date_secs1)
						{
							if($processDataBy=="datetime")
							{	
								$todayDataObject->cDeviceDatetime[$cntCThis] = $datetime;
							}
							else if($processDataBy=="serverDatetime")
							{				
								$todayDataObject->cServerDatetime[$cntCThis] = $datetime;
							}
							$todayDataObject->cVehicleName[$cntCThis] = $vName;
							$todayDataObject->cVehicleSerial[$cntCThis] = $vSerial;
							if($xmlParameterArr[$parametersArr[2]]=='r') /// for suply voltage
							{
								$status = preg_match('/'.$xmlParameterArr[$parametersArr[2]].'="[^" ]+/', $line, $supv_tmp);      				
								//echo "Status2=".$status.'<BR>';
								if($status==0)
								{
									continue;               
								}     

								$supv_tmp1 = explode("=",$supv_tmp[0]);
								$todayDataObject->cSuplyVoltage[$cntCThis] = preg_replace('/"/', '', $supv_tmp1[1]);
							}
							else if($xmlParameterArr[$parametersArr[17]]=='ab')
							{
							
							}
								//reassign time1
      						$time1 = $datetime;
      						$date_secs1 = strtotime($time1);
      						$date_secs1 = (double)($date_secs1 + $interval);
							$cntCThis++;
						}
					}
					if(($c==($total_lines-1)) && ($i==($date_size-1)))
					{
						if($processDataBy=="datetime")
						{	
							$todayDataObject->cDeviceDatetime[$cnt] = $datetime;
						}
						else if($processDataBy=="serverDatetime")
						{				
							$todayDataObject->cServerDatetime[$cnt] = $datetime;
						}
						$todayDataObject->cVehicleName[$cnt] = $vName;
						$todayDataObject->cVehicleSerial[$cnt] = $vSerial;
						if($xmlParameterArr[$parametersArr[2]]=='r') /// for suply voltage
						{
							$status = preg_match('/'.$xmlParameterArr[$parametersArr[2]].'="[^" ]+/', $line, $supv_tmp);      				
							//echo "Status2=".$status.'<BR>';
							if($status==0)
							{
								continue;               
							} 
							$supv_tmp1 = explode("=",$supv_tmp[0]);
							$todayDataObject->cSuplyVoltage[$cnt] = preg_replace('/"/', '', $supv_tmp1[1]);
						}
						else if($xmlParameterArr[$parametersArr[17]]=='ab')
						{

						}
						$cnt++;
					} 		
				}
			}
		}
	}
}

function readSortXml($vSerial,$vName,$startDate,$endDate,$userInterval,$parametersArr,$xmlParameterArr,$processDataBy,$dataValid,$firstDataFlag,$xmlFileDate,$date_size)
{
	global $postDataObject;	
	global $cntPThis;
	//$xml_file = "C:\\xampp/htdocs/sorted_xml_data/".$xmlFileDate."/".$vSerial.".xml";	
	$xml_file = "../../../sorted_xml_data/".$xmlFileDate."/".$vSerial.".xml";	
	//echo "xml_file=".$xml_file."<br>";
	if($dataValid=="yes")
	{
		//echo "in if";
		//echo "data valid=".$dataValid."<br>";
		if((preg_match('/'.$xmlParameterArr[$parametersArr[3]].'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$xmlParameterArr[$parametersArr[4]].'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)))
		{ 
			$lat_value = explode('=',$lat_match[0]);
			$lng_value = explode('=',$lng_match[0]);
			//echo " lat_value=".$lat_value[1];         
			if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
			{
				if($processDataBy=="datetime")
				{
					$status = preg_match('/'.$xmlParameterArr[$parametersArr[1]].'="[^"]+/', $line, $datetime_tmp);
					$datetime_tmp1 = explode("=",$datetime_tmp[0]);
					$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);		
					$postDataObject->pDeviceDatetime[$cntPThis] = $datetime;
				}
				else if($processDataBy=="serverDatetime")
				{
					$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
					$datetime_tmp1 = explode("=",$datetime_tmp[0]);
					$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
					$xml_date = $datetime;
					$postDataObject->pServerDatetime[$cntPThis] = $datetime;
				}
				
				$lat_tmp1 = explode("=",$lat_tmp[0]);
				$postDataObject->cLatitude[$cntPThis]=preg_replace('/"/', '', $lat_tmp1[1]);				
				$lng_tmp1 = explode("=",$lng_tmp[0]);		
				$postDataObject->pLongitude[$cntPThis]= preg_replace('/"/', '', $lng_tmp1[1]);					
			}
		}
	}
	else if($dataValid=="no")
	{
		//echo "suplyVoltage=".$xmlParameterArr[$parametersArr[2]]."<br>";
		//echo "xmlFile1=".$xml_file."<br>";
		if(file_exists($xml_file)) 
		{
			$xml = @fopen($xml_file, "r") or $fexist = 0;
			if($i==($date_size-1))
			{
				$total_lines = count(file($xml_file));
			}
			//echo "<br>Total lines orig=".$total_lines;
			while(!feof($xml))          // WHILE LINE != NULL
			{
				//$c++;
				$DataValid = 0;
				//echo fgets($file). "<br />";
				$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			

				if(strlen($line)>20)
				{
					$linetmp =  $line;
				}

				//echo "processDataBy=".$processDataBy."<br>";
				if($processDataBy=="datetime")
				{
					//echo "datetime=".$xmlParameterArr[$parametersArr[1]]."<br>";
					$status = preg_match('/'.$xmlParameterArr[$parametersArr[1]].'="[^"]+/', $line, $datetime_tmp);
				}
				else if($processDataBy=="serverDatetime")
				{
					$status = preg_match('/'.$xmlParameterArr[$parametersArr[0]].'="[^"]+/', $line, $datetime_tmp);
				}
				
				$datetime_tmp1 = explode("=",$datetime_tmp[0]);
				$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);			
				if(($datetime >= $startDate && $datetime <= $endDate) && ($datetime!="-") && ($datetime!=null))
				{
					if($firstDataFlag==0)
					{
						//echo "in first<br>";
						if($processDataBy=="datetime")
						{	
							$postDataObject->pDeviceDatetime[$cntPThis] = $datetime;
						}
						else if($processDataBy=="serverDatetime")
						{				
							$postDataObject->pServerDatetime[$cntPThis] = $datetime;
						}
						$postDataObject->pVehicleSerial[$cntPThis] = $vSerial;
						$postDataObject->pVehicleName[$cntPThis] = $vName;
						$firstDataFlag = 1;
						//echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
						$interval = (double)$userInterval*60;							

						$time1 = $datetime;					
						$date_secs1 = strtotime($time1);					
						//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
						$date_secs1 = (double)($date_secs1 + $interval); 
						$date_secs2 = 0; 
						
						if($xmlParameterArr[$parametersArr[2]]=='r') /// for suply voltage
						{
							$status = preg_match('/'.$xmlParameterArr[$parametersArr[2]].'="[^" ]+/', $line, $supv_tmp);      				
							//echo "Status2=".$status.'<BR>';
							if($status==0)
							{
								continue;               
							}     

							$supv_tmp1 = explode("=",$supv_tmp[0]);
							$postDataObject->pSuplyVoltage[$cntPThis] = preg_replace('/"/', '', $supv_tmp1[1]);
			
						}
						else if($xmlParameterArr[$parametersArr[17]]=='ab')
						{
						
						}
						//echo "mainElementObject=".$mainElementObject->cSuplyVoltage[$cnt]."<br>";
						$cntPThis++;
					} 
					else
					{
						$time2 = $datetime;											
						$date_secs2 = strtotime($time2);	
						//echo "<br>Next".$date_secs2;      					
						if($date_secs2 >= $date_secs1)
						{
							if($processDataBy=="datetime")
							{	
								$postDataObject->pDeviceDatetime[$cntPThis] = $datetime;
							}
							else if($processDataBy=="serverDatetime")
							{				
								$postDataObject->pServerDatetime[$cntPThis] = $datetime;
							}
							$postDataObject->pVehicleSerial[$cntPThis] = $vSerial;
							$postDataObject->pVehicleName[$cntPThis] = $vName;
							if($xmlParameterArr[$parametersArr[2]]=='r') /// for suply voltage
							{
								$status = preg_match('/'.$xmlParameterArr[$parametersArr[2]].'="[^" ]+/', $line, $supv_tmp);      				
								//echo "Status2=".$status.'<BR>';
								if($status==0)
								{
									continue;               
								}     

								$supv_tmp1 = explode("=",$supv_tmp[0]);
								$postDataObject->pSuplyVoltage[$cntPThis] = preg_replace('/"/', '', $supv_tmp1[1]);
							}
							else if($xmlParameterArr[$parametersArr[17]]=='ab')
							{
							
							}
							$time1 = $datetime;
      						$date_secs1 = strtotime($time1);
      						$date_secs1 = (double)($date_secs1 + $interval);
							$cntPThis++;
						}
					}
					if(($c==($total_lines-1)) && ($i==($date_size-1)))
					{
						if($processDataBy=="datetime")
						{	
							$postDataObject->pDeviceDatetime[$cntPThis] = $datetime;
						}
						else if($processDataBy=="serverDatetime")
						{				
							$postDataObject->pServerDatetime[$cntPThis] = $datetime;
						}
						$postDataObject->pVehicleSerial[$cntPThis] = $vSerial;
						$postDataObject->pVehicleName[$cntPThis] = $vName;
						if($xmlParameterArr[$parametersArr[2]]=='r') /// for suply voltage
						{
							$status = preg_match('/'.$xmlParameterArr[$parametersArr[2]].'="[^" ]+/', $line, $supv_tmp);      				
							//echo "Status2=".$status.'<BR>';
							if($status==0)
							{
								continue;               
							} 
							$supv_tmp1 = explode("=",$supv_tmp[0]);
							$postDataObject->pSuplyVoltage[$cntPThis] = preg_replace('/"/', '', $supv_tmp1[1]);
						}
						else if($xmlParameterArr[$parametersArr[17]]=='ab')
						{

						}
						$cntPThis++;
					} 		
				}
			}
		}
	}
}

function sortTodayData($sortObject, $processDataBy,$parametersArr,$xmlParameterArr)
{
	if($processDataBy=="datetime")
	{
		//echo "sizeOf=".sizeof($sortObject->cDeviceDatetime)."<br>";
		$i=sizeof($sortObject->cDeviceDatetime);
	}
	else if($processDataBy=="serverDatetime")
	{
	}
	
	for($x = 1; $x < $i; $x++) 
	{
		if($xmlParameterArr[$parametersArr[2]]=='r') /// for suply voltage
		{
			$date_x=null;		
			$value = preg_replace('/"/', '', $date_x[1]);
			
			if($processDataBy=="datetime")
			{
				$tmpDatetime = $sortObject->cDeviceDatetime[$x];
				$value=$sortObject->cDeviceDatetime[$x];
			}
			else if($processDataBy=="serverDatetime")
			{
			}
			$tmpSupVoltage = $sortObject->cSuplyVoltage[$x];
			$tmpVName = $sortObject->cVehicleName[$x];
			$tmpVSerial = $sortObject->cVehicleSerial[$x];
			$z = $x - 1;
			$done = false;
			while($done == false)
			{					
				if($processDataBy=="datetime")
				{				
					$date_tmp1=$sortObject->cDeviceDatetime[$x];
				}
				else if($processDataBy=="serverDatetime")
				{
				}
				
				if ($date_tmp1>$value)
				{
					$sortObject->cVehicleName[$z + 1] = $sortObject->cVehicleName[$z];
					$sortObject->cVehicleSerial[$z + 1] = $sortObject->cVehicleSerial[$z];
					$sortObject->cDeviceDatetime[$z + 1] = $sortObject->cDeviceDatetime[$z];
					$sortObject->cSuplyVoltage[$z + 1] = $sortObject->cSuplyVoltage[$z];					
					$z = $z - 1;
					if ($z < 0)
					{
						$done = true;
					}
				}
				else
				{
					$done = true;
				}
			}
			$sortObject->cSuplyVoltage[$z + 1] = $tmpSupVoltage;
			$sortObject->cSuplyVoltage[$z + 1] = $tmpSupVoltage;
			$sortObject->cVehicleName[$z + 1] = $tmpVName;
			$sortObject->cVehicleSerial[$z + 1] = $tmpVSerial;	
		}	   
	} 
	return $sortObject;
}     // FUNCTION CLOSED

function get_All_Dates($fromDate, $toDate, &$userdates)
{
	$dateMonthYearArr = array();
	$fromDateTS = strtotime($fromDate);
	$toDateTS = strtotime($toDate);

	for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24)) {
		// use date() and $currentDateTS to format the dates in between
		$currentDateStr = date("Y-m-d",$currentDateTS);
		$dateMonthYearArr[] = $currentDateStr;
	//print $currentDateStr.”<br />”;
	}
	$userdates = $dateMonthYearArr;
}
?>