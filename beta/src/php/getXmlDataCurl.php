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
    echo "in function";
    $functionName=$_POST['functionName'];
    $vSerial=$_POST['vSerial'];
    $dateRangeStart=$_POST['dateRangeStart'];
    $dateRangeEnd=$_POST['dateRangeEnd'];
    $dateTime=$_POST['dateTime'];
    $sortBy=$_POST['sortBy'];
    $parameterizeData=json_decode($_POST['paramObj']);
    echo json_encode($parameterizeData);

    if($functionName=="deviceDataBetweenDates")
    {
        echo "in if";
        echo "vSerial=".$vSerial."dateStart=".$dateRangeStart."endDate=".$dateRangeEnd."<br>";
       deviceDataBetweenDates($vSerial, $dateRangeStart, $dateRangeEnd , $sortBy, $parameterizeData);            
    }
    else if($functionName=="readFileXmlNew")
    {
       // return json_encode($parameterizeData);
       // echo "in if";
       $requiredData="All";
       readFileXmlNew($vSerial,$dateTime,$requiredData,$sortBy,$parameterizeData);
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
function readFileXmlNew($vSerial, $dateToData,  $requiredData, $sortBy, $parameterizeData) {
   // echo "vSerial=".$vSerial."<br>";
    //echo "dateToData=".$dateToData."<br>";
    $dataObject=null;
    $dataObject=new data();	
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
    /*echo "deviceTime=".$deviceTime."<br>";
    echo "dateToData=".$dateToData."<br>";
    echo "requiredData=".$requiredData."<br>";
    echo "imei=".$imei."<br>"; */
	
    $orderAsc = TRUE;
    $st_results = getLogByDate($o_cassandra, $imei, $dateToData, $deviceTime, $orderAsc);
if(count($st_results)>0)
    {
   // var_dump($st_results);
    foreach($st_results as $item) {
        $msg_type = $item->a;                 
        $ver = $item->b;
        //echo "ver=".$ver."<br>";
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
        $ci = $item->i;
        
        $ax = isset($item->ax)?$item->ax:'-';
        $ay = isset($item->ay)?$item->ay:'-';;
        $az = isset($item->az)?$item->az:'-';
        $mx = isset($item->mx)?$item->mx:'-';
        $my = isset($item->my)?$item->my:'-';
        $mz = isset($item->mz)?$item->mz:'-';
        $bx = isset($item->bx)?$item->bx:'-';;
        $by = isset($item->by)?$item->by:'-';
        $bz = isset($item->bz)?$item->bz:'-';
        
          
        if ($requiredData == "All") 
        {            
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
            $dataObject->dayMaxSpeedData[] = isset($day_max_spd)?$day_max_spd:'-';
        }
        if ($parameterizeData->dayMaxSpeed != null) 
        {
            $dataObject->dayMaxSpeedTime[] = isset($day_max_spd_time)?$day_max_spd_time:'-';
        }            
        if ($parameterizeData->lastHaltTime != null) 
        {
            $dataObject->lastHaltTimeData[] = isset($last_halt_time)?$last_halt_time:'-';
        }

        if ($parameterizeData->axParam != null) 
        {
            $dataObject->axParamData[] = $ax;
            //echo "ax=".$ax."<br>";
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

       if ($parameterizeData->speed != null) {
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
        else if ($requiredData != "All") {
            if ($firstDataFlag == 0) {
                $firstDataFlag = 1;
                $interval = (double) $userInterval * 60;
                //$time1 = $switchDatetime;					
                $date_secs1 = strtotime($switchDatetime);
                $date_secs1 = (double) ($date_secs1 + $interval);
                $date_secs2 = 0;
            } else {
                //$time2 = $switchDatetime;	
                //echo "parameter=".$parameterizeData->batteryVoltage."<br>";						
                $date_secs2 = strtotime($switchDatetime);

                if (($date_secs2 >= $date_secs1)) {
                    $dataObject->serverDatetime[] = $datetime_server;
                    $dataObject->deviceDatetime[] = $datetime_device;

                    if ($parameterizeData->batteryVoltage != null) {                          
                        $dataObject->batteryVoltageData[] = $sup_v;
                    }

                    if ($parameterizeData->temperature != null) {
                        $temp = $parameterizeData->temperature;
                        $dataObject->temperatureIOData[] = $item->$temp;
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

                    //$time1 = $switchDatetime;
                    $date_secs1 = strtotime($switchDatetime);
                    $date_secs1 = (double) ($date_secs1 + $interval);
                }
            }
        }          
               
        
    }
	//var_dump($dataObject);
    echo json_encode($dataObject);
}
 else
{
    deliverResponse(400,'Data Not Fetched',NULL);
}
}


function deviceDataBetweenDates($vSerial, $dateRangeStart, $dateRangeEnd , $sortBy, $parameterizeData)
{
    $dataObject=null;
    $dataObject=new data();
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
    echo "deviceTime=".$deviceTime."<br>";
    echo "dateToData=".$dateToData."<br>";
    echo "requiredData=".$requiredData."<br>";
    echo "imei=".$sortBy."<br>"; 
	
    if($parameterizeData->orderBy=="DESC")
    {
      $orderAsc = FALSE;
    }
    else
    {
        $orderAsc = TRUE;
    }
    $st_results = getImeiDateTimes($o_cassandra, $imei, $dateRangeStart, $dateRangeEnd, $deviceTime, $orderAsc);

    var_dump($st_results);
    if(count($st_results)>0)
    {
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
    echo json_encode($dataObject);
}
else
{
    deliverResponse(400,'Data Not Fetched',NULL);
}
}
?>
