<?php
//echo "INGET11";
//include_once("read_data_cassandra_db.php");     //##### INCLUDE CASSANDRA API
$isReport=isset($isReport)?$isReport:0;
$isReport2=isset($isReport2)?$isReport2:0;

if($isReport) 
{
   include_once("../../../phpApi/Cassandra/Cassandra.php");     //##### INCLUDE CASSANDRA API*/
   include_once("../../../phpApi/libLog.php");     //##### INCLUDE CASSANDRA API*/    
} 
else if($isReport2) 
{
   include_once("../../../../phpApi/Cassandra/Cassandra.php");     //##### INCLUDE CASSANDRA API*/
   include_once("../../../../phpApi/libLog.php");     //##### INCLUDE CASSANDRA API*/    
} 
else 
{      
   // echo "in else";
    include_once("../../../phpApi/Cassandra/Cassandra.php");     //##### INCLUDE CASSANDRA API
    include_once("../../../phpApi/libLog.php");     //##### INCLUDE CASSANDRA API*/    //##### INCLUDE CASSANDRA API*/
}
//echo "EXISTS=".file_exists("../../../../../phpApi/libLog.php")."<br>";
include_once("data.php");

$o_cassandra = new Cassandra();	
$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
if(!empty($_POST['functionName']))
{
	$functionName=$_POST['functionName'];
        $vSerial=$_POST['vSerial'];
        $dateRangeStart=$_POST['dateRangeStart'];
        $dateRangeEnd=$_POST['dateRangeEnd'];
        $sortBy=$_POST['sortBy'];
        $parameterizeData=$_POST['parameterizeData'];
        
        if($functionName=="deviceDataBetweenDates")
        {
            $response=deviceDataBetweenDates($vSerial, $dateRangeStart, $dateRangeEnd , $sortBy, $parameterizeData);
            
        }
	//echo "jsonDecodedData=".$jsonData."<br>";
	//$response=getJsonSampleData($jsonData);
	if($response=="fail")
	{
		deliverResponse(400,'Data Not Not Fetched',NULL);
	}
 else {
     echo $jsonResponse;
 }
}
else
{
	deliverResponse(400,'Invalid Request',NULL);
}

function deliverResponse($status,$statusMessage,$response)
{
	header("HTTP/1.1 $status $statusMessage");
	$responseArr['status']=$status;
	$responseArr['statusMessage']=$statusMessage;
	$responseArr['jsonResponse']=$response;
	
	$jsonResponse=json_encode($responseArr);
	echo $jsonResponse;	
}

function deviceDataBetweenDates($vSerial, $dateRangeStart, $dateRangeEnd , $sortBy, $parameterizeData)
{
    global $o_cassandra; 
    $imei = $vSerial;
    $HH = '23';
   if ($sortBy == "h")
   {
        $deviceTime=TRUE;
   }
   else if($sortBy == "g")
   {
        $deviceTime=FALSE;
   }
    //echo "deviceTime=".$deviceTime."<br>";
    //echo "dateToData=".$dateToData."<br>";
    //echo "requiredData=".$requiredData."<br>";
    //echo "imei=".$imei."<br>"; 
	
    if($parameterizeData->orderBy=="DESC")
    {
      $orderAsc = FALSE;
    }
    else
    {
        $orderAsc = TRUE;
    }
    $st_results = getImeiDateTimes($o_cassandra, $imei, $dateRangeStart, $dateRangeEnd, $deviceTime, $orderAsc);

    //var_dump($st_results);
    if(count($item->a)>0)
    {
        $dataObject=new data();
    foreach($st_results as $item) {
        $msg_type = $item->a;                 
        $ver = $item->b;              
        $fix = $item->c;
        $lat = $item->d;
        $lng = $item->e;
        $speed = $item->f;
        
        $ax = $item->ax;
        $ay = $item->ay;
        $az = $item->az;
        $mx = $item->mx;
        $my = $item->my;
        $mz = $item->mz;
        $bx = $item->bx;
        $by = $item->by;
        $bz = $item->bz;
        
        
        $datetime_server = str_replace('@',' ',$item->g);
        $datetime_device = str_replace('@',' ',$item->h);              
        $io1 = $item->i;
        $io2 = $item->j;
        $io3 = $item->k;
        $io4 = $item->l;
        $io5 = $item->m;
        $io6 = $item->n;
        $io7 = $item->o;
        $io8 = $item->p;
        $sig_str = $item->q;
        $sup_v = $item->r;
        $ci = $item->i;
          
                  
        $dataObject->serverDatetime[] = $datetime_server;
        $dataObject->deviceDatetime[] = $datetime_device;

        if ($parameterizeData->messageType != null) 
        {
            $dataObject->messageTypeData[] = $msg_type;
        }

        if ($parameterizeData->version != null) 
        {
            $dataObject->versionData[] = $ver;
        }

        if ($parameterizeData->fix != null) {
            $dataObject->fixData[] = $fix;
        }
        if ($parameterizeData->cellName != null) 
        {
            $dataObject->cellNameData[] = $ci;
        }
        if ($parameterizeData->supVoltage != null) 
        {
            $dataObject->supVoltageData[] = $sup_v;
        }
        if ($parameterizeData->io1 != null) 
        {
            $dataObject->io1Data[] = $io1;
        }
        if ($parameterizeData->io2 != null) 
        {
            $dataObject->io2Data[] = $io2;
        }
        if ($parameterizeData->io3 != null) 
        {
            $dataObject->io3Data[] = $io3;
        }
        if ($parameterizeData->io4 != null) 
        {
            $dataObject->io4Data[] = $io4;
        }
        if ($parameterizeData->io5 != null) 
        {
            $dataObject->io5Data[] = $io5;
        }
        if ($parameterizeData->io6 != null) 
        {
            $dataObject->io6Data[] = $io6;
        }
        if ($parameterizeData->io7 != null) 
        {
            $dataObject->io7Data[] = $io7;
        }
        if ($parameterizeData->io8 != null) 
        {
            $dataObject->io8Data[] = $io8;
        }

        if ($parameterizeData->dayMaxSpeed != null) 
        {
            $dataObject->dayMaxSpeedData[] = $day_max_spd;
        }
        if ($parameterizeData->dayMaxSpeed != null) 
        {
            $dataObject->dayMaxSpeedTime[] = $day_max_spd_time;
        }            
        if ($parameterizeData->lastHaltTime != null) 
        {
            $dataObject->lastHaltTimeData[] = $last_halt_time;
        }

        if ($parameterizeData->axParam != null) 
        {
            $dataObject->axParamData[] = $ax;
        }
        if ($parameterizeData->ayParam != null) 
        {
            $dataObject->ayParamData[] = $ay;
        }
        if ($parameterizeData->azParam != null) 
        {
            $dataObject->azParamData[] = $az;
        }
        if ($parameterizeData->mxParam != null) 
        {
            $dataObject->mxParamData[] = $mx;
        }
        if ($parameterizeData->myParam != null) 
        {
            $dataObject->myParamData[] = $my;
        }
        if ($parameterizeData->mzParam != null) 
        {
            $dataObject->mzParamData[] = $mz;
        }
        if ($parameterizeData->bxParam != null) 
        {
            $dataObject->bxParamData[] = $bx;
        }
        if ($parameterizeData->byParam != null) 
        {
            $dataObject->byParamData[] = $by;
        }
        if ($parameterizeData->bzParam != null) 
        {
            $dataObject->bzParamData[] = $bz;
        }

        if ($parameterizeData->latitude != null && $parameterizeData->longitude != null) 
        {
           //echo "lat=".$lat."lng=".$lng."<br>";
            $dataObject->latitudeData[] = $lat;
            $dataObject->longitudeData[] = $lng;
        }

        if ($parameterizeData->speed != null) 
        {
            $dataObject->speedData[] = $speed;
        }
        if ($parameterizeData->doorOpen1 != null) 
        {
            $doorOpen1 = $parameterizeData->doorOpen1;
            $dataObject->doorOpen1Data[] = $item->$doorOpen1;
        }
        if ($parameterizeData->doorOpen2 != null) 
        {
            $doorOpen2 = $parameterizeData->doorOpen2;
            $dataObject->doorOpen2Data[] = $item->$doorOpen2;
        }
        if ($parameterizeData->doorOpen3 != null) 
        {
            $doorOpen3 = $parameterizeData->doorOpen3;
            $dataObject->doorOpen3Data[] = $item->$doorOpen3;
        }

        if ($parameterizeData->acRunHr != null) 
        {
            $acRunHr = $parameterizeData->acRunHr;
            $dataObject->acIOData[] = $item->$acRunHr;
        }

        if ($parameterizeData->engineRunHr != null) 
        {
            $engineRunHr = $parameterizeData->engineRunHr;
            $dataObject->engineIOData[] = $item->$engineRunHr;
        }
        
        if ($parameterizeData->flowRate != null) {
            $flowRate = $parameterizeData->flowRate;
            $dataObject->flowRateData[] = $item->$flowRate;
        }               
        if ($parameterizeData->dispensing1 != null) {
            $dispensing1 = $parameterizeData->dispensing1;
            $dataObject->dispensing1Data[] = $item->$dispensing1;
        }

        if ($parameterizeData->dispensing2 != null) {
            $dispensing2 = $parameterizeData->dispensing2;
            $dataObject->dispensing2Data[] = $item->$dispensing2;
        }
        if ($parameterizeData->dispensing3 != null) {
            $dispensing3 = $parameterizeData->dispensing3;
            $dataObject->dispensing3Data[] = $item->$dispensing3;
        }                     
    }
    return $dataObject;
    }
 else {
        return "fail";
    }
    
    
    
}



function getLastRecord($vSerial,$sortBy,$parameterizeData)
{
	global $o_cassandra; 
        //global $DbConnection;
	$imei = $vSerial;
	
	$st_results = getLastSeen($o_cassandra,$imei);
	//var_dump($st_results);
	//$params = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r');
	//$st_obj = gpsParser($st_results,$params,TRUE);
	// $st_obj = gpsParser($st_results);
	//print_r($st_obj);
	
	//if(!empty((array)$st_obj))
	{
		//echo "in if";
		foreach($st_results as $item) {
			$msg_type = $item->a;                 
			$ver = $item->b;              
			$fix = $item->c;
			$lat = $item->d;
			$lng = $item->e;
			$speed = $item->f;
			
			
			$datetime_server = str_replace('@',' ',$item->g);
			$datetime_device = str_replace('@',' ',$item->h);              
			$io1 = $item->i;
			$io2 = $item->j;
			$io3 = $item->k;
			$io4 = $item->l;
			$io5 = $item->m;
			$io6 = $item->n;
			$io7 = $item->o;
			$io8 = $item->p;
			$sig_str = $item->q;
			$sup_v = $item->r;
			
		   
			$DataValid = 0;
	//exit();

			/*if ($parameterizeData->latitude != null && $parameterizeData->longitude != null) {
				 if ((strlen($lat) > 5) && ($lat != "-") && (strlen($lng) > 5) && ($lng != "-")) {
					$DataValid = 1;
				}
				//}
				//echo "DataValid=".$DataValid."<br>";
				if ($DataValid == 0) {
					continue;
				}
			}*/

			//echo "<br>DataValid=".$DataValid;
			if ($parameterizeData->dataLog != null) {
			   
					$switchDatetime = $datetime_device;
				 
			} else {
				if ($sortBy == "g") { /// server time
					$switchDatetime = $datetime_server;
				} else if ($sortBy == "h") { ////// device time
					$switchDatetime = $datetime_device;
				}
			}

			//echo "switchDatetime=".$switchDatetime."<br>";
			//if ($DataValid) 
			{
				$dataObject->serverDatetimeLR[] = $datetime_server;
				$dataObject->deviceDatetimeLR[]=$datetime_device;
				$dataObject->messageTypeLR[] = $msg_type;
				$dataObject->versionLR[] = $ver;
				$dataObject->fixLR[] = $fix;
				$dataObject->latitudeLR[]= $lat;
				$dataObject->longitudeLR[] = $lng;
				$dataObject->speedLR[]=$speed;
				$dataObject->io1LR[] = $io1;
				$dataObject->io2LR[] = $io2;
				$dataObject->io3LR[] = $io3;
				$dataObject->io4LR[] = $io4;
				$dataObject->io5LR[] = $io5;
				$dataObject->io6LR[] = $io6;
				$dataObject->io7LR[] = $io7;
				$dataObject->io8LR[] = $io8;
				$dataObject->sigStrTLR[]=$sig_str;
				$dataObject->suplyVoltageLR[]=$sup_v;	
				$dataObject->dayMaxSpeedLR[]=$item->s;
				$dataObject->dayMaxSpeedTimeLR[]=$item->t;
				$dataObject->lastHaltTimeLR[]=$item->u;	
				$dataObject->cellNameLR[]='-';
			}
		}
	}
        //var_dump($dataObject);
	return $dataObject;
}

function getLastSortedDate($vserial, $datefrom, $dateto) {
    //echo "dateFrom=".$datefrom."datetoe=".$dateto."<br>";
   /*$fromDateTS = strtotime($datefrom);
    $toDateTS = strtotime($dateto);
    for ($currentDateTS = $toDateTS; $currentDateTS >= $fromDateTS; $currentDateTS -= (60 * 60 * 24)) {
        $currentDateStr = date("Y-m-d", $currentDateTS);
        $xml_file = "../../../sorted_xml_data/" . $currentDateStr . "/" . $vserial . ".xml";
        //echo "xmlFile=".$xml_file."<br>";
        if (file_exists($xml_file)) {
            //echo "in if<br>";
            return $currentDateTS;
        }
    }*/
   return null;
}

function get_All_Dates($fromDate, $toDate, &$userdates) {
    $dateMonthYearArr = array();
    $fromDateTS = strtotime($fromDate);
    $toDateTS = strtotime($toDate);

    for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24)) {
        // use date() and $currentDateTS to format the dates in between
        $currentDateStr = date("Y-m-d", $currentDateTS);
        $dateMonthYearArr[] = $currentDateStr;
        //print $currentDateStr.�<br />�;
    }
    $userdates = $dateMonthYearArr;    
}

?>
