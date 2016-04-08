<?php
function sortData($sortObject,$sortBy,$parameterizeData)
{
	return $sortObject;
	/*if($sortBy=="h")
	{
		$i=sizeof($sortObject->deviceDatetime);
	}
	else if($sortBy=="g")
	{
		$i=sizeof($sortObject->serverDatetime);
	}
	
	for($x = 1; $x < $i; $x++) 
	{
		$tmpDeviceDT = $sortObject->deviceDatetime[$x];
		$tmpServerDT = $sortObject->serverDatetime[$x];
		//echo "latitude=".$parameterizeData->latitude." longitude=".$parameterizeData->longitude."<br>";
		if($parameterizeData->latitude!=null && $parameterizeData->longitude!=null)
		{
			$tmpLatitude = $sortObject->latitudeData[$x];
			$tmpLongitude = $sortObject->longitudeData[$x];
		}
		
		if($parameterizeData->speed!=null)
		{
			$tmpSpeed = $sortObject->speedData[$x];
		}
		if($parameterizeData->messageType!=null)
		{
			$tmpMessageType= $sortObject->messageTypeData[$x];
		}
		if($parameterizeData->version!=null)
		{
			$tmpVersion = $sortObject->versionData[$x];
		}
		if($parameterizeData->fix!=null)
		{
			$tmpFix= $sortObject->fixData[$x];
		}
		if($parameterizeData->batteryVoltage!=null)
		{
			$tmpbatteryVoltage = $sortObject->batteryVoltageData[$x];
		}
		if($parameterizeData->temperature!=null)
		{
			$tmpTemperature = $sortObject->temperatureIOData[$x];
		}
		if($parameterizeData->engineRunHr!=null)
		{
			$tmpEngineRunHr = $sortObject->engineIOData[$x];
		}		
		if($parameterizeData->acRunHr!=null)
		{
			$tmpACRunHr = $sortObject->acIOData[$x];
		}
		if($parameterizeData->doorOpen1!=null)
		{
			$tmpDoorOpen1 = $sortObject->doorOpen1Data[$x];
		}
		if($parameterizeData->doorOpen2!=null)
		{
			$tmpDoorOpen2 = $sortObject->doorOpen2Data[$x];
		}
		if($parameterizeData->doorOpen3!=null)
		{
			$tmpDoorOpen3 = $sortObject->doorOpen3Data[$x];
		}
		if($parameterizeData->cellName!=null)
		{
			$tmpCellName = $sortObject->cellNameData[$x];
		}
		if($parameterizeData->supVoltage!=null)
		{
			$tmpSupVoltage = $sortObject->supVoltageData[$x];
		}
		
		if($parameterizeData->io1!=null)
		{
			$tmpio1 = $sortObject->io1Data[$x];
		}
		if($parameterizeData->io2!=null)
		{
			$tmpio2 = $sortObject->io2Data[$x];
		}
		if($parameterizeData->io3!=null)
		{
			$tmpio3 = $sortObject->io3Data[$x];
		}
		if($parameterizeData->io4!=null)
		{
			$tmpio4 = $sortObject->io4Data[$x];
		}
		if($parameterizeData->io5!=null)
		{
			$tmpio5 = $sortObject->io5Data[$x];
		}
		if($parameterizeData->io6!=null)
		{
			$tmpio6 = $sortObject->io6Data[$x];
		}
		if($parameterizeData->io7!=null)
		{
			$tmpio7 = $sortObject->io7Data[$x];
		}
		if($parameterizeData->io8!=null)
		{
			$tmpio8 = $sortObject->io8Data[$x];
		}
		
		if($parameterizeData->dayMaxSpeed!=null)
		{
			$tmpDayMaxSpeed = $sortObject->dayMaxSpeedData[$x];
		}
		if($parameterizeData->lastHaltTime!=null)
		{
			$tmpLastHaltTime= $sortObject->lastHaltTimeData[$x];
		}
		
		if($parameterizeData->axParam!=null)
		{
			$tmpAxParam = $sortObject->axParamData[$x];
		}
		if($parameterizeData->ayParam!=null)
		{
			$tmpAyParam = $sortObject->ayParamData[$x];
		}
		if($parameterizeData->azParam!=null)
		{
			$tmpAzParam = $sortObject->azParamData[$x];
		}
		if($parameterizeData->mxParam!=null)
		{
			$tmpMxParam = $sortObject->mxParamData[$x];
		}
		if($parameterizeData->myParam!=null)
		{
			$tmpMyParam = $sortObject->myParamData[$x];
		}
		if($parameterizeData->mzParam!=null)
		{
			$tmpMzParam = $sortObject->mzParamData[$x];
		}
		if($parameterizeData->bxParam!=null)
		{
			$tmpBxParam = $sortObject->bxParamData[$x];
		}
		if($parameterizeData->byParam!=null)
		{
			$tmpByParam = $sortObject->byParamData[$x];
		}
		if($parameterizeData->bzParam!=null)
		{
			$tmpBzParam = $sortObject->bzParam[$x];
		}
		
		
		//echo "dateFrom=".$tmpDateFrom."<br>";
		if($sortBy=="h")
		{
			$value=$sortObject->deviceDatetime[$x];
		}
		else if($sortBy=="g")
		{
			$value=$sortObject->serverDatetime[$x];
		}
		
	
		$z = $x - 1;
		$done = false;
		while($done == false)
		{
			if($sortBy=="h")
			{
				$date_tmp1=$sortObject->deviceDatetime[$x];
			}
			else if($sortBy=="g")
			{
				$date_tmp1=$sortObject->serverDatetime[$x];
			}
			
			if ($date_tmp1>$value)
			{
				$sortObject->deviceDatetime[$z + 1] = $sortObject->deviceDatetime[$z];
				$sortObject->serverDatetime[$z + 1] = $sortObject->serverDatetime[$z];
				if($parameterizeData->messageType!=null)
				{
					$sortObject->messageTypeData[$z + 1] = $sortObject->messageTypeData[$z];
				}
				if($parameterizeData->version!=null)
				{
					$sortObject->versionData[$z + 1] = $sortObject->versionData[$z];
				}
				if($parameterizeData->fix!=null)
				{
					$sortObject->fixData[$z + 1] = $sortObject->fixData[$z];
				}				
				if($parameterizeData->latitude!=null && $parameterizeData->longitude!=null)
				{
					$sortObject->latitudeData[$z + 1] = $sortObject->latitudeData[$z];
					$sortObject->longitudeData[$z + 1] = $sortObject->longitudeData[$z];		
				}
				if($parameterizeData->speed!=null)
				{						
					$sortObject->speedData[$z + 1] = $sortObject->speedData[$z];	
				}
				
				if($parameterizeData->batteryVoltage!=null)
				{						
					$sortObject->batteryVoltageData[$z + 1] = $sortObject->batteryVoltageData[$z];	
				}
				if($parameterizeData->temperature!=null)
				{					
					$sortObject->temperatureIOData[$z + 1] = $sortObject->temperatureIOData[$z];				
				}
				if($parameterizeData->engineRunHr!=null)
				{
					$sortObject->engineIOData[$z + 1] = $sortObject->engineIOData[$z];					
				}
				if($parameterizeData->acRunHr!=null)
				{
					$sortObject->acIOData[$z + 1] = $sortObject->acIOData[$z];					
				}
				if($parameterizeData->doorOpen1!=null)
				{
					$sortObject->doorOpen1Data[$z + 1] = $sortObject->doorOpen1Data[$z];					
				}
				if($parameterizeData->doorOpen2!=null)
				{
					$sortObject->doorOpen2Data[$z + 1] = $sortObject->doorOpen2Data[$z];					
				}
				if($parameterizeData->doorOpen3!=null)
				{
					$sortObject->doorOpen3Data[$z + 1] = $sortObject->doorOpen3Data[$z];					
				}
				if($parameterizeData->cellName!=null)
				{
					$sortObject->cellNameData[$z + 1] = $sortObject->cellNameData[$z];					
				}
				if($parameterizeData->supVoltage!=null)
				{
					$sortObject->supVoltageData[$z + 1] = $sortObject->supVoltageData[$z];					
				}				
				
				if($parameterizeData->io1!=null)
				{
					$sortObject->io1Data[$z + 1] = $sortObject->io1Data[$z];	
				}
				if($parameterizeData->io2!=null)
				{
					$sortObject->io2Data[$z + 1] = $sortObject->io2Data[$z];	
				}
				if($parameterizeData->io3!=null)
				{
					$sortObject->io3Data[$z + 1] = $sortObject->io3Data[$z];	
				}
				if($parameterizeData->io4!=null)
				{
					$sortObject->io4Data[$z + 1] = $sortObject->io4Data[$z];	
				}
				if($parameterizeData->io5!=null)
				{
					$sortObject->io5Data[$z + 1] = $sortObject->io5Data[$z];	
				}				
				if($parameterizeData->io6!=null)
				{
					$sortObject->io6Data[$z + 1] = $sortObject->io6Data[$z];	
				}
				if($parameterizeData->io7!=null)
				{
					$sortObject->io7Data[$z + 1] = $sortObject->io7Data[$z];	
				}
				if($parameterizeData->io8!=null)
				{
					$sortObject->io8Data[$z + 1] = $sortObject->io8Data[$z];	
				}
				
				if($parameterizeData->dayMaxSpeed!=null)
				{
					$sortObject->dayMaxSpeedData[$z + 1] = $sortObject->dayMaxSpeedData[$z];	
				}
				if($parameterizeData->lastHaltTime!=null)
				{
					$sortObject->lastHaltTimeData[$z + 1] = $sortObject->lastHaltTimeData[$z];	
				}
				
				if($parameterizeData->axParam!=null)
				{
					$sortObject->axParamData[$z + 1] = $sortObject->axParamData[$z];	
				}
				if($parameterizeData->ayParam!=null)
				{
					$sortObject->ayParamData[$z + 1] = $sortObject->ayParamData[$z];
				}
				if($parameterizeData->azParam!=null)
				{
					$sortObject->azParamData[$z + 1] = $sortObject->azParamData[$z];
				}
				if($parameterizeData->mxParam!=null)
				{
					$sortObject->mxParamData[$z + 1] = $sortObject->mxParamData[$z];
				}
				if($parameterizeData->myParam!=null)
				{
					$sortObject->myParamData[$z + 1] = $sortObject->myParamData[$z];
				}
				if($parameterizeData->mzParam!=null)
				{
					$sortObject->mzParamData[$z + 1] = $sortObject->mzParamData[$z];
				}
				if($parameterizeData->bxParam!=null)
				{
					$sortObject->bxParamData[$z + 1] = $sortObject->bxParamData[$z];
				}
				if($parameterizeData->byParam!=null)
				{
					$sortObject->byParamData[$z + 1] = $sortObject->byParamData[$z];
				}
				if($parameterizeData->bzParam!=null)
				{
					$sortObject->bzParamData[$z + 1] = $sortObject->bzParamData[$z];
				}
				
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
		$sortObject->deviceDatetime[$z + 1] = $tmpDeviceDT;
		$sortObject->serverDatetime[$z + 1] = $tmpServerDT;
		if($parameterizeData->messageType!=null)
		{
			$sortObject->messageTypeData[$z + 1] = $tmpMessageType;
		}
		if($parameterizeData->version!=null)
		{
			$sortObject->versionData[$z + 1] = $tmpVersion;
		}
		if($parameterizeData->fix!=null)
		{
			$sortObject->fixData[$z + 1] = $tmpFix;
		}	
		if($parameterizeData->latitude!=null && $parameterizeData->longitude!=null)
		{
			$sortObject->latitudeData[$z + 1] = $tmpLatitude;
			$sortObject->longitudeData[$z + 1] = $tmpLongitude;	
		}
		if($parameterizeData->speed!=null)
		{
			$sortObject->speedData[$z + 1] = $tmpSpeed;
		}
		if($parameterizeData->batteryVoltage!=null)
		{
			$sortObject->batteryVoltageData[$z + 1] = $tmpbatteryVoltage;
		}				
		if($parameterizeData->temperature!=null)
		{
			$sortObject->temperatureIOData[$z + 1] = $tmpTemperature;
		}
		if($parameterizeData->engineRunHr!=null)
		{
			$sortObject->engineIOData[$z + 1] = $tmpEngineRunHr;
		}
		if($parameterizeData->acRunHr!=null)
		{
			$sortObject->acIOData[$z + 1] = $tmpACRunHr;
		}
		if($parameterizeData->doorOpen1!=null)
		{
			$sortObject->doorOpen1Data[$z + 1] = $tmpDoorOpen1;
		}
		if($parameterizeData->doorOpen2!=null)
		{
			$sortObject->doorOpen2Data[$z + 1] = $tmpDoorOpen2;
		}
		if($parameterizeData->doorOpen3!=null)
		{
			$sortObject->doorOpen3Data[$z + 1] = $tmpDoorOpen3;
		}
		if($parameterizeData->cellName!=null)
		{
			$sortObject->cellNameData[$z + 1] = $tmpCellName;
		}
		if($parameterizeData->supVoltage!=null)
		{
			$sortObject->supVoltageData[$z + 1] = $tmpSupVoltage;
		}
		
		if($parameterizeData->io1!=null)
		{
			$sortObject->io1Data[$z + 1] = $tmpio1;
		}
		if($parameterizeData->io2!=null)
		{
			$sortObject->io2Data[$z + 1] = $tmpio2;
		}
		if($parameterizeData->io3!=null)
		{
			$sortObject->io3Data[$z + 1] = $tmpio3;
		}
		if($parameterizeData->io4!=null)
		{
			$sortObject->io4Data[$z + 1] = $tmpio4;
		}
		if($parameterizeData->io5!=null)
		{
			$sortObject->io5Data[$z + 1] = $tmpio5;
		}
		if($parameterizeData->io6!=null)
		{
			$sortObject->io6Data[$z + 1] = $tmpio6;
		}
		if($parameterizeData->io7!=null)
		{
			$sortObject->io7Data[$z + 1] = $tmpio7;
		}
		if($parameterizeData->io8!=null)
		{
			$sortObject->io8Data[$z + 1] = $tmpio8;
		}
		
		if($parameterizeData->dayMaxSpeed!=null)
		{
			$sortObject->dayMaxSpeedData[$z + 1] = $tmpDayMaxSpeed;
		}
		if($parameterizeData->lastHaltTime!=null)
		{
			$sortObject->lastHaltTimeData[$z + 1] = $tmpLastHaltTime;
		}
		
		if($parameterizeData->axParam!=null)
		{
			$sortObject->axParamData[$z + 1] = $tmpAxParam;
		}
		if($parameterizeData->ayParam!=null)
		{
			$sortObject->ayParamData[$z + 1] = $tmpAyParam;
		}
		if($parameterizeData->azParam!=null)
		{
			$sortObject->azParamData[$z + 1] = $tmpAzParam;
		}
		if($parameterizeData->mxParam!=null)
		{
			$sortObject->mxParamData[$z + 1] = $tmpMxParam;
		}
		if($parameterizeData->myParam!=null)
		{
			$sortObject->myParamData[$z + 1] = $tmpMyParam;
		}
		if($parameterizeData->mzParam!=null)
		{
			$sortObject->mzParamData[$z + 1] = $tmpMzParam;
		}
		if($parameterizeData->bxParam!=null)
		{
			$sortObject->bxParamData[$z + 1] = $tmpBxParam;
		}
		if($parameterizeData->byParam!=null)
		{
			$sortObject->byParamData[$z + 1] = $tmpByParam;
		}
		if($parameterizeData->bzParam!=null)
		{
			$sortObject->bzParamData[$z + 1] = $tmpBzParam;
		}
	} 
	return $sortObject;*/
}   // FUNCTION CLOSED
?>