<?php
include_once('mainElementArr.php');
$parameters='6,7,17';
$parametersArr=explode(",",$parameters);
function readFileXml($vSerial,$startDate,$endDate,$parametersArr,$interval,$processDataBy,$cnt,$dataValid,$firstDataFlag)
{
	echo "in function";
	global $parametersArr;
	global $xmlParameterArr;
	$mainElementObject=new mainElementArr();
	
	$date_1 = explode(" ",$startDate);
	$date_2 = explode(" ",$endDate);

	$datefrom = $date_1[0];
	$dateto = $date_2[0];
	$timefrom = $date_1[1];
	$timeto = $date_2[1];
	get_All_Dates($datefrom, $dateto, &$userdates);
	$date_size = sizeof($userdates);

	for($i=0;$i<=($date_size-1);$i++)
	{
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
		
		$xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	

		if(file_exists($xml_current))      
		{		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = "../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}
		
		//$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
		if($dataValid=="yes")
		{
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
						$mainElementObject->cDeviceDatetime[$cnt] = $datetime;
					}
					else if($processDataBy=="serverDatetime")
					{
						$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
						$xml_date = $datetime;
						$mainElementObject->cServerDatetime[$cnt] = $datetime;
					}
					
					$lat_tmp1 = explode("=",$lat_tmp[0]);
					$mainElementObject->cLatitude[$cnt]=preg_replace('/"/', '', $lat_tmp1[1]);				
					$lng_tmp1 = explode("=",$lng_tmp[0]);		
					$mainElementObject->cLongitude[$cnt]= preg_replace('/"/', '', $lng_tmp1[1]);
					
				}
			}
		}
		else if($dataValid=="no")
		{
			if (file_exists($xml_file)) 
			{
				if($i==($date_size-1))
				{
					$total_lines = count(file($file_exists));
				}
				//echo "<br>Total lines orig=".$total_lines;
				while(!feof($xml))          // WHILE LINE != NULL
				{
					$c++;
					$DataValid = 0;
					//echo fgets($file). "<br />";
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			

					if(strlen($line)>20)
					{
						$linetmp =  $line;
					}

					$xml = @fopen($file_exists, "r") or $fexist = 0;
					if($processDataBy=="datetime")
					{
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
							if($processDataBy=="datetime")
							{	
								$mainElementObject->cDeviceDatetime[$cnt] = $datetime;
							}
							else if($processDataBy=="serverDatetime")
							{				
								$mainElementObject->cServerDatetime[$cnt] = $datetime;
							}
						
							$firstDataFlag = 1;
							//echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
							$interval = (double)$user_interval*60;							

							$time1 = $datetime;					
							$date_secs1 = strtotime($time1);					
							//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
							$date_secs1 = (double)($date_secs1 + $interval); 
							$date_secs2 = 0; 
							if($xmlParameterArr[$parametersArr[17]]=='r') /// for suply voltage
							{
								$status = preg_match('/'.$vr.'="[^" ]+/', $line, $supv_tmp);      				
								//echo "Status2=".$status.'<BR>';
								if($status==0)
								{
									continue;               
								}     

								$supv_tmp1 = explode("=",$supv_tmp[0]);
								$mainElementObject->cSuplyVoltage[$cnt] = preg_replace('/"/', '', $supv_tmp1[1]);
							}
							else if($xmlParameterArr[$parametersArr[17]]=='ab')
							{
							
							}
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
									$mainElementObject->cDeviceDatetime[$cnt] = $datetime;
								}
								else if($processDataBy=="serverDatetime")
								{				
									$mainElementObject->cServerDatetime[$cnt] = $datetime;
								}
								if($xmlParameterArr[$parametersArr[17]]=='r') /// for suply voltage
								{
									$status = preg_match('/'.$vr.'="[^" ]+/', $line, $supv_tmp);      				
									//echo "Status2=".$status.'<BR>';
									if($status==0)
									{
										continue;               
									}     

									$supv_tmp1 = explode("=",$supv_tmp[0]);
									$mainElementObject->cSuplyVoltage[$cnt] = preg_replace('/"/', '', $supv_tmp1[1]);
								}
								else if($xmlParameterArr[$parametersArr[17]]=='ab')
								{
								
								}		
							}
						}
						if(($c==($total_lines-1)) && ($i==($date_size-1)))
						{
							if($processDataBy=="datetime")
							{	
								$mainElementObject->cDeviceDatetime[$cnt] = $datetime;
							}
							else if($processDataBy=="serverDatetime")
							{				
								$mainElementObject->cServerDatetime[$cnt] = $datetime;
							}
							if($xmlParameterArr[$parametersArr[17]]=='r') /// for suply voltage
							{
								$status = preg_match('/'.$vr.'="[^" ]+/', $line, $supv_tmp);      				
								//echo "Status2=".$status.'<BR>';
								if($status==0)
								{
									continue;               
								} 
								$supv_tmp1 = explode("=",$supv_tmp[0]);
								$mainElementObject->cSuplyVoltage[$cnt] = preg_replace('/"/', '', $supv_tmp1[1]);
							}
							else if($xmlParameterArr[$parametersArr[17]]=='ab')
							{

							}
						} 		
					}
				}
			}
		}
	}
	return $mainElementObject;
}


$tmpObj=readFileXml('862170018316412','2015-02-17 00:00:00','2015-02-17 12:00:00','10',$parametersArr,$parametersArr,'0','datetime','no','0');
//var_dump($tmpObj);
?>