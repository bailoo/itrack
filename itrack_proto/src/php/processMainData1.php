<?php

function readFileXml($vSerial,$startDate,$endDate,$xmlFromDate,$xmlToDate,$userInterval,$parametersArr,$xmlParameterArr,$processDataBy,$dataValid,$firstDataFlag,$type,&$dataObject)
{
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
					$dataObject->ceviceDatetime[$cntCThis] = $datetime;
				}
				else if($processDataBy=="serverDatetime")
				{
					$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
					$datetime_tmp1 = explode("=",$datetime_tmp[0]);
					$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
					$xml_date = $datetime;
					$dataObject->serverDatetime[$cntCThis] = $datetime;
				}
				
				$lat_tmp1 = explode("=",$lat_tmp[0]);
				$dataObject->latitude[$cntCThis]=preg_replace('/"/', '', $lat_tmp1[1]);				
				$lng_tmp1 = explode("=",$lng_tmp[0]);		
				$dataObject->longitude[$cntCThis]= preg_replace('/"/', '', $lng_tmp1[1]);					
			}
		}
	}
	else if($dataValid=="no")
	{
		
		//echo "xmlFromDate=".$xmlFromDate."xmlToDate=".$xmlToDate."<br>";
		//echo "type=".$type."<br>";
		get_All_Dates($xmlFromDate, $xmlToDate, &$userdates);
		$date_size = sizeof($userdates);
		print_r($userdates);
		for($i=0;$i<=($date_size-1);$i++)
		{
			if($type=="unSorted")
			{
				$filePath="../../../xml_vts/xml_data/".$userdates[$i]."/".$vSerial.".xml";
			}
			else if($type=="sorted")
			{
				$filePath="../../../sorted_xml_data/".$userdates[$i]."/".$vSerial.".xml";
			}
			//echo "filePath=".$filePath."<br>";
			if(file_exists($filePath)) 
			{				
				//echo "<br>in exist";
				$xml = @fopen($filePath, "r") or $fexist = 0;
				if($i==($date_size-1))
				{
					$total_lines = count(file($filePath));
				}
				//echo "<br>Total lines orig=".$total_lines;
				while(!feof($xml))          // WHILE LINE != NULL
				{
					//$c++;
					$DataValid = 0;
					//echo fgets($file). "<br />";
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE		
					//echo "<textarea>".$line."</textarea>";
					
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
						$status = preg_match('/'.$xmlParameterArr[$parametersArr[2]].'="[^" ]+/', $line, $supv_tmp);      				
									//echo "Status2=".$status.'<BR>';
									if($status==0)
									{
										continue;               
									} 
						if($firstDataFlag==0)
						{
							//echo "in first<br>";
							/*if($processDataBy=="datetime")
							{	
								$dataObject->deviceDatetime[] = $datetime;
							}
							else if($processDataBy=="serverDatetime")
							{				
								$dataObject->serverDatetime[] = $datetime;
							}*/
						
							$firstDataFlag = 1;
							//echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
							$interval = (double)$userInterval*60;							

							$time1 = $datetime;					
							$date_secs1 = strtotime($time1);					
							//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
							$date_secs1 = (double)($date_secs1 + $interval); 
							$date_secs2 = 0; 
							
							/*if($xmlParameterArr[$parametersArr[2]]=='r') /// for suply voltage
							{
								$status = preg_match('/'.$xmlParameterArr[$parametersArr[2]].'="[^" ]+/', $line, $supv_tmp);      				
								//echo "Status2=".$status.'<BR>';
								if($status==0)
								{
									continue;               
								}     

								$supv_tmp1 = explode("=",$supv_tmp[0]);
								$dataObject->suplyVoltage[$cntCThis] = preg_replace('/"/', '', $supv_tmp1[1]);
				
							}
							else if($xmlParameterArr[$parametersArr[17]]=='ab')
							{
							
							}*/
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
									$dataObject->deviceDatetime[] = $datetime;
								}
								else if($processDataBy=="serverDatetime")
								{				
									$dataObject->serverDatetime[] = $datetime;
								}
							
								if($xmlParameterArr[$parametersArr[2]]=='r') /// for suply voltage
								{
									$supv_tmp1 = explode("=",$supv_tmp[0]);
									$dataObject->suplyVoltage[] = preg_replace('/"/', '', $supv_tmp1[1]);
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
								$dataObject->deviceDatetime[] = $datetime;
							}
							else if($processDataBy=="serverDatetime")
							{				
								$dataObject->serverDatetime[] = $datetime;
							}							
							if($xmlParameterArr[$parametersArr[2]]=='r') /// for suply voltage
							{
								$status = preg_match('/'.$xmlParameterArr[$parametersArr[2]].'="[^" ]+/', $line, $supv_tmp);      				
								//echo "Status2=".$status.'<BR>';
								if($status==0)
								{
									continue;               
								} 
								$supv_tmp1 = explode("=",$supv_tmp[0]);
								$dataObject->suplyVoltage[] = preg_replace('/"/', '', $supv_tmp1[1]);
							}
							else if($xmlParameterArr[$parametersArr[17]]=='ab')
							{

							}
							//$cnt++;
						} 		
					}				
				}
			}
		}

	}
}

function sortData($sortObject, $processDataBy,$parametersArr,$xmlParameterArr)
{
	if($processDataBy=="datetime")
	{
	//echo "sizeOf=".sizeof($sortObject->deviceDatetime)."<br>";
		$i=sizeof($sortObject->deviceDatetime);
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
				$tmpDatetime = $sortObject->deviceDatetime[$x];
				$value=$sortObject->deviceDatetime[$x];
			}
			else if($processDataBy=="serverDatetime")
			{
			}
			$tmpSupVoltage = $sortObject->suplyVoltage[$x];			
		
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
					$sortObject->deviceDatetime[$z + 1] = $sortObject->deviceDatetime[$z];
					$sortObject->suplyVoltage[$z + 1] = $sortObject->suplyVoltage[$z];					
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
			$sortObject->deviceDatetime[$z + 1] = $tmpDatetime;
			$sortObject->suplyVoltage[$z + 1] = $tmpSupVoltage;			
		}	   
	} 
	return $sortObject;
}     // FUNCTION CLOSED

function getLastSortedDate($vserial, $datefrom,$dateto)
{
	//echo "dateFrom=".$datefrom."datetoe=".$dateto."<br>";
	$fromDateTS = strtotime($datefrom);
	$toDateTS = strtotime($dateto);
	for ($currentDateTS = $toDateTS; $currentDateTS >= $fromDateTS; $currentDateTS -= (60 * 60 * 24)) 
	{
		$currentDateStr = date("Y-m-d",$currentDateTS);		
		$xml_file = "../../../sorted_xml_data/".$currentDateStr."/".$vserial.".xml";	
		//echo "xmlFile=".$xml_file."<br>";
		if(file_exists($xml_file))
		{
			//echo "in if<br>";
			return $currentDateTS;
		}
	}	
	return null;
}
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