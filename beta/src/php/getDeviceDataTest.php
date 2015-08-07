<?php
//echo "INGET11";
//include_once("read_data_cassandra_db.php");     //##### INCLUDE CASSANDRA API
if($isReport) {
   include_once("../../../phpApi/Cassandra/Cassandra.php");     //##### INCLUDE CASSANDRA API*/
   include_once("../../../phpApi/libLog.php");     //##### INCLUDE CASSANDRA API*/    
} else if($isReport2) {
   include_once("../../../../phpApi/Cassandra/Cassandra.php");     //##### INCLUDE CASSANDRA API*/
   include_once("../../../../phpApi/libLog.php");     //##### INCLUDE CASSANDRA API*/    
} else {  
   $pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//print_r($pathInPieces);
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3];
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2];
    include_once($pathToRoot."/phpApi/Cassandra/Cassandra.php");     //##### INCLUDE CASSANDRA API
  
    include_once($pathToRoot."/phpApi/libLogNew.php");     //##### INCLUDE CASSANDRA API*/    //##### INCLUDE CASSANDRA API*/
}
//echo "EXISTS=".file_exists("../../../../../phpApi/libLog.php")."<br>";
$o_cassandra = new Cassandra();	
$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);

function read_data_between_datetime($vSerial, $startDate, $endDate, $userInterval, $requiredData, $sortBy, $type, $parameterizeData, $firstDataFlag, &$dataObject) {
	
    global $o_cassandra; 
    $imei = $vSerial;
    $date = $startDate;
    $HH = '23';
    //echo "\nSDA=".$startDate." ,endDate=".$endDate;
    $dateminute1 = str_replace(' ', '-', $startDate);
    $dateminute1 = str_replace(':', '-', $dateminute1);

    $dateminute2 = str_replace(' ', '-', $endDate);
    $dateminute2 = str_replace(':', '-', $dateminute2);

   //echo "imie=".$imei."<br>";
   //echo "sortBy=".$sortBy."<br>";
   if ($sortBy == "h")
   {
        $deviceTime=TRUE;
   }
   else if($sortBy == "g")
   {
        $deviceTime=FALSE;
   }
    //echo "deviceTime=".$deviceTime."<br>";
    //echo "imei=".$imei."<br>"; 
	
    $orderAsc = TRUE;
    $st_results = getImeiDateTimes($o_cassandra, $imei, $startDate, $endDate, $deviceTime, $orderAsc);

    //var_dump($st_results);
    //$params = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r');
            // TRUE for ascending, otherwise descending (default) 
    //$st_obj = gpsParser($st_results,$params,$dataType,$orderAsc);
    //print_r($st_obj);
    // $st_obj = gpsParser($st_results);
    //print_r($st_obj);

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

        if ($parameterizeData->latitude != null && $parameterizeData->longitude != null) {
             if ((strlen($lat) > 5) && ($lat != "-") && (strlen($lng) > 5) && ($lng != "-")) {
                $DataValid = 1;
            }
            //}
            //echo "DataValid=".$DataValid."<br>";
            if ($DataValid == 0) {
                continue;
            }
        }

       // echo "<br>DataValid=".$DataValid;
        if ($parameterizeData->dataLog != null) {
           
                $switchDatetime = $datetime_device;
             
        } else {
            if ($sortBy == "g") { /// server time
                $switchDatetime = $datetime_server;
            } else if ($sortBy == "h") { ////// device time
                $switchDatetime = $datetime_device;
            }
        }

       // echo "switchDatetime=".$switchDatetime."<br>";
		// echo "startDate=".$startDate."<br>";
		 	// echo "endDate=".$endDate."<br>";
        if (($switchDatetime >= $startDate && $switchDatetime <= $endDate) && ($switchDatetime != "-") && ($switchDatetime != null)) {
            //echo "requiredData=".$requiredData." speed=".$parameterizeData->speed."<br>";
            if ($requiredData == "All") {
                $dataObject->serverDatetime[] = $datetime_server;
                $dataObject->deviceDatetime[] = $datetime_device;

                if ($parameterizeData->messageType != null) {

                    $dataObject->messageTypeData[] = $msg_type;
                }

                if ($parameterizeData->version != null) {
                    $dataObject->versionData[] = $ver;
                }

                if ($parameterizeData->fix != null) {
                    $dataObject->fixData[] = $fix;
                }
                if ($parameterizeData->cellName != null) {
                    $dataObject->cellNameData[] = $ci;
                }
                if ($parameterizeData->supVoltage != null) {
                    $dataObject->supVoltageData[] = $sup_v;
                }
                if ($parameterizeData->io1 != null) {
                    $dataObject->io1Data[] = $io1;
                }
                if ($parameterizeData->io2 != null) {
                    $dataObject->io2Data[] = $io2;
                }
                if ($parameterizeData->io3 != null) {
                    $dataObject->io3Data[] = $io3;
                }
                if ($parameterizeData->io4 != null) {
                    $dataObject->io4Data[] = $io4;
                }
                if ($parameterizeData->io5 != null) {
                    $dataObject->io5Data[] = $io5;
                }
                if ($parameterizeData->io6 != null) {
                    $dataObject->io6Data[] = $io6;
                }
                if ($parameterizeData->io7 != null) {
                    $dataObject->io7Data[] = $io7;
                }
                if ($parameterizeData->io8 != null) {
                    $dataObject->io8Data[] = $io8;
                }

                if ($parameterizeData->dayMaxSpeed != null) {
                    $dataObject->dayMaxSpeedData[] = $day_max_spd;
                }
                if ($parameterizeData->dayMaxSpeed != null) {
                    $dataObject->dayMaxSpeedTime[] = $day_max_spd_time;
                }            
                if ($parameterizeData->lastHaltTime != null) {
                    $dataObject->lastHaltTimeData[] = $last_halt_time;
                }

                if ($parameterizeData->axParam != null) {
                    $dataObject->axParamData[] = $ax;
                }
                if ($parameterizeData->ayParam != null) {
                    $dataObject->ayParamData[] = $ay;
                }
                if ($parameterizeData->azParam != null) {
                    $dataObject->azParamData[] = $az;
                }
                if ($parameterizeData->mxParam != null) {
                    $dataObject->mxParamData[] = $mx;
                }
                if ($parameterizeData->myParam != null) {
                    $dataObject->myParamData[] = $my;
                }
                if ($parameterizeData->mzParam != null) {
                    $dataObject->mzParamData[] = $mz;
                }
                if ($parameterizeData->bxParam != null) {
                    $dataObject->bxParamData[] = $bx;
                }
                if ($parameterizeData->byParam != null) {
                    $dataObject->byParamData[] = $by;
                }
                if ($parameterizeData->bzParam != null) {
                    $dataObject->bzParamData[] = $bz;
                }

                if ($parameterizeData->latitude != null && $parameterizeData->longitude != null) {
                    $dataObject->latitudeData[] = $lat;
                    $dataObject->longitudeData[] = $lng;
                }

                if ($parameterizeData->speed != null) {
                    //echo "<br>In Speed";
					//echo "speed=".$speed."<br>";
					//echo "speed=".$speed."<br>";
                    $dataObject->speedData[] = $speed;
                }
                if ($parameterizeData->doorOpen1 != null) {
                    $doorOpen1 = $parameterizeData->doorOpen1;
                    $dataObject->doorOpen1Data[] = $item->$doorOpen1;
                }
                if ($parameterizeData->doorOpen2 != null) {
                    $doorOpen2 = $parameterizeData->doorOpen2;
                    $dataObject->doorOpen2Data[] = $item->$doorOpen2;
                }
                if ($parameterizeData->doorOpen3 != null) {
                    $doorOpen3 = $parameterizeData->doorOpen3;
                    $dataObject->doorOpen3Data[] = $item->$doorOpen3;
                }

                if ($parameterizeData->acRunHr != null) {
                    $acRunHr = $parameterizeData->acRunHr;
                    $dataObject->acIOData[] = $item->$acRunHr;
                }

                if ($parameterizeData->engineRunHr != null) {
                    $engineRunHr = $parameterizeData->engineRunHr;
                    $dataObject->engineIOData[] = $item->$engineRunHr;
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

                        //$time1 = $switchDatetime;
                        $date_secs1 = strtotime($switchDatetime);
                        $date_secs1 = (double) ($date_secs1 + $interval);
                    }
                }
            }          
               
        }
    }
	//var_dump($dataObject); 
}

function readFileXmlNew($vSerial, $dateToData,  $requiredData, $sortBy, $parameterizeData, &$dataObject) {
	
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

    //var_dump($st_results);
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

                    //$time1 = $switchDatetime;
                    $date_secs1 = strtotime($switchDatetime);
                    $date_secs1 = (double) ($date_secs1 + $interval);
                }
            }
        }          
               
        
    }
	//var_dump($dataObject); 
}

function getLastPositionXMl($vSerial,$startDate,$endDate,$xmlFromDate,$xmlToDate,$sortBy,$type,$parameterizeData,&$dataObject)
{
   global $o_cassandra; 
   if ($sortBy == "h")
   {
        $deviceTime=TRUE;
   }
   else if($sortBy == "g")
   {
        $deviceTime=FALSE;
   }
 
    $lastRecordArr=$st_results = getLastSeen($o_cassandra,$vSerial);
    //var_dump($lastRecordArr);
    foreach($lastRecordArr as $itemLR) 
    {
        $last_halt_time = $itemLR->u;
        $day_max_spd=$itemLR->s;
    }
    
    //echo "last_halt_time=".$last_halt_time."day_max_spd=".$day_max_spd."<br>";
    
    $deviceTime = TRUE;	// TRUE for query on index dtime, otherwise stime	
    $orderAsc = TRUE;	// TRUE for ascending, otherwise descending (default)   
    $st_results = getImeiDateTimes($o_cassandra, $vSerial, $startDate, $endDate, $deviceTime, $orderAsc);
    
    foreach($st_results as $item) 
    {
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
        if ($parameterizeData->latitude != null && $parameterizeData->longitude != null) 
        {
            if ((strlen($lat) > 5) && ($lat != "-") && (strlen($lng) > 5) && ($lng != "-")) 
            {
                $DataValid = 1;
            }
            //echo "DataValid=".$DataValid."<br>";
            if ($DataValid == 0) 
            {
                    continue;
            }
        }
        if($DataValid==1)
        {
            $datetime_server1=$datetime_server;
            $datetime_device1=$datetime_device;

            if ($parameterizeData->messageType != null) 
            {
                $msg_type_1 = $msg_type;
            }
            if ($parameterizeData->version != null) 
            {
                $ver_1 = $ver;
            }
            if ($parameterizeData->fix != null) 
            {
                $fix_1 = $fix;
            }
            if ($parameterizeData->cellName != null) 
            {
                $ci1 = $ci;
            }
            if ($parameterizeData->supVoltage != null) 
            {
                $sup_v1 = $sup_v;
            }
            if ($parameterizeData->io1 != null) 
            {
                $io1_1 = $io1;
            }
            if ($parameterizeData->io2 != null) 
            {
                $io2_2 = $io2;
            }
            if ($parameterizeData->io3 != null) 
            {
                    $io3_3 = $io3;
            }
            if ($parameterizeData->io4 != null) 
            {
                    $io4_1 = $io4;
            }
            if ($parameterizeData->io5 != null) 
            {
                    $io5_1 = $io5;
            }
            if ($parameterizeData->io6 != null) 
            {
                    $io6_1 = $io6;
            }
            if ($parameterizeData->io7 != null) 
            {
                    $io7_1 = $io7;
            }
            if ($parameterizeData->io8 != null) 
            {
                    $io8_1 = $io8;
            }

            if ($parameterizeData->dayMaxSpeed != null) 
            {
                    $day_max_spd_1 = $day_max_spd;
            }
            if ($parameterizeData->dayMaxSpeedTime != null) 
            {
                    $day_max_spd_time_1 = $day_max_spd_time;
            }            
            if ($parameterizeData->lastHaltTime != null) 
            {
                    $last_halt_time_1 = $last_halt_time;
            }

            if ($parameterizeData->latitude != null && $parameterizeData->longitude != null) 
            {
                $lat_1 = $lat;
                $lng_1 = $lng;
            }

            if ($parameterizeData->speed != null) 
            {
                //echo "<br>In Speed";
                $speed_1 = $speed;
            }

            if ($parameterizeData->sigStr != null) 
            {
                    $sig_str_1 = $sig_str;
            }
            if ($parameterizeData->supVoltage != null) 
            {
                $sup_v_1 = $sup_v;
            }
        }
    }
    $dataObject->serverDatetimeLD[] = $datetime_server;
    $dataObject->deviceDatetimeLD[]=$datetime_device1;	 
    $dataObject->messageTypeLD[] = $msg_type_1;
    $dataObject->versionLD[] = $ver_1; 
    $dataObject->fixLD[] = $fix_1;	
    $dataObject->latitudeLD[]=$lat_1;
    $dataObject->longitudeLD[]=$lng_1;
    $dataObject->speedLD[] =$speed_1;	 
    $dataObject->io1LD[] = $io1_1;;  
    $dataObject->io2LD[] =$io2_1; 
    $dataObject->io3LD[] = $io3_1; 
    $dataObject->io4LD[] =$io4_1; 
    $dataObject->io5LD[]=$io5_1;  
    $dataObject->io6LD[] = $io6_1;  
    $dataObject->io7LD[] =$io7_1; 
    $dataObject->io8LD[] = $io8_1;	
    $dataObject->sigStrLD[] = $sig_str_1;
    $dataObject->suplyVoltageLD[] = $sup_v_1;	
    $dataObject->cellNameLD[] ='-';
    $dataObject->dayMaxSpeedLD[]=$day_max_spd_1;
    $dataObject->lastHaltTimeLD[]=$last_halt_time_1;
	
}


function getLastRecord($vSerial,$sortBy,$parameterizeData)
{
	global $o_cassandra; 
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

			if ($parameterizeData->latitude != null && $parameterizeData->longitude != null) {
				 if ((strlen($lat) > 5) && ($lat != "-") && (strlen($lng) > 5) && ($lng != "-")) {
					$DataValid = 1;
				}
				//}
				//echo "DataValid=".$DataValid."<br>";
				if ($DataValid == 0) {
					continue;
				}
			}

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
			if ($DataValid) 
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
        //print $currentDateStr.?<br />?;
    }
    $userdates = $dateMonthYearArr;    
}

?>
