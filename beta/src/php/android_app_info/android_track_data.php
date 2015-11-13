<?php
    set_time_limit(2000);
    include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
    include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
    include_once("android_calculate_distance.php");
    include_once("util_android_hr_min_sec.php");
    require_once "lib/nusoap.php"; 
    $pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
    //print_r($pathInPieces);
    $pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2];
    //$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3];
    //echo "pathToRoot=".$pathToRoot."<br>";
            //====cassamdra //////////////
    include_once($pathToRoot.'/beta/src/php/xmlParameters.php');
    include_once($pathToRoot.'/beta/src/php/parameterizeData.php'); /////// for seeing parameters
    include_once($pathToRoot.'/beta/src/php/data.php');   
    include_once($pathToRoot.'/beta/src/php/getDeviceDataTest.php');

   /*$vehicleserialWithIo="862170017134329:#,";
    $startDate="2015/08/06 00:00:00";
    $endDate="2015/08/06 16:38:36";
    $reportType="V";
    $userInterval="60";
    $result=getTrackDeviceData( $reportType,$vehicleserialWithIo,$startDate,$endDate,$userInterval);
    echo $result; */
    function getTrackDeviceData($reportType,$vehicleserialWithIo,$startDate,$endDate,$userInterval)
    {
        global $DbConnection;  
        $reportType=$reportType;
        $device_str= $vehicleserialWithIo;
        //$device_str="862170018369908:# ,";
        $device_str = explode('#',$device_str);
        //echo "device_str1=".$device_str[0]."<br>";
        //echo "device_str2=".$device_str[1]."<br>";
        $vserial = explode(':',substr($device_str[0],0,-1));
        $iotype_element = explode(',',substr($device_str[1],0,-1));
        $startdate = $startDate;
        $enddate = $endDate; 

        /*$startdate = "2013/11/05 10:58:57";
        $enddate = "2013/11/06 10:58:59"; */

        $startdate = str_replace('/', '-', $startdate);  
        $enddate = str_replace('/', '-', $enddate); 
        $time_interval1 = $userInterval; 
        //$time_interval1="900";
        //$time_interval1=1;
        //include_once("sort_xml.php");
        $minlat = 180; 
        $maxlat = -180;
        $minlong = 180;
        $maxlong = -180;
        $maxPoints = 1000;
        $file_exist = 0;	
        $tmptimeinterval = strtotime($enddate) - strtotime($startdate);

        if($time_interval1=="auto")
        {
            $timeinterval =   ($tmptimeinterval/$maxPoints);
            $distanceinterval = 0.1; 
        }
        else
        {
            if($tmptimeinterval>86400)
            {
                $timeinterval =   $time_interval1;		
                $distanceinterval = 0.3;
            }
            else
            {
                $timeinterval =   $time_interval1;
                $distanceinterval = 0.02;
            }
        } 

        global $track_report_data;
        $track_report_data=array();
			
        for($i=0;$i<sizeof($vserial);$i++)
        {		
            $Query="SELECT vehicle.vehicle_name,vehicle.vehicle_type,vehicle.vehicle_number FROM vehicle USE INDEX(v_vehicleid_status)".
                    ",vehicle_assignment USE INDEX(va_vehicleid_status,va_vehicleid_imei_status) WHERE vehicle.vehicle_id=vehicle_assignment.".
                    "vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_assignment.device_imei_no='$vserial[$i]'";
            $Result=mysql_query($Query,$DbConnection);
            $Row=mysql_fetch_row($Result);
            getTrack($vserial[$i],$Row[0],$Row[1],$startdate,$enddate,$iotype_element[$i],$timeinterval,$distanceinterval);
        }
        global $o_cassandra;
        $o_cassandra->close();
        return json_encode($track_report_data); 
    }
    
    function getTrack($vehicle_serial,$vname,$vehicle_number,$startdate,$enddate,$iotype_element_1,$timeinterval,$distanceinterval)
    {
        global $track_report_data;
        $sortBy="h";
        $requiredData="All";
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
        $CurrentLat = 0.0;
        $CurrentLong = 0.0;
        $LastLat = 0.0;
        $LastLong = 0.0;
        $firstData = 0;
        $distance =0.0;
        $linetowrite="";
        $date_1 = explode(" ",$startdate);
        $date_2 = explode(" ",$enddate);

        $dateRangeStart = $date_1[0];
        $dateRangeEnd = $date_2[0];
        $timefrom = $date_1[1];
        $timeto = $date_2[1];

        get_All_Dates($dateRangeStart, $dateRangeEnd, $userdates);    
        $date_size = sizeof($userdates);

        date_default_timezone_set("Asia/Calcutta");
        $current_datetime = date("Y-m-d H:i:s");
        $current_date = date("Y-m-d");
        //print "<br>CurrentDate=".$current_date;
		
        $ioArr=explode(":",$iotype_element_1);
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
        for($i=0;$i<=($date_size-1);$i++)
        {
            $SortedDataObject=null;
            $SortedDataObject=new data();
           
            if($date_size==1)
            {
                $dateRangeStart=$date1;
                $dateRangeEnd=$date2;
            }
            else if($di==($date_size-1))
            {
                $dateRangeStart=$userdates[$i]." 00:00:00";
                $dateRangeEnd=$date2;
            }
            else if($di==0)
            {
                $dateRangeStart=$date1;
                $dateRangeEnd=$userdates[$i]." 23:59:59";
            }
            else
            {
               $dateRangeStart=$userdates[$i]." 00:00:00";
                $dateRangeEnd=$userdates[$i]." 23:59:59";
            }
            deviceDataBetweenDates($vehicle_serial,$dateRangeStart,$dateRangeEnd,$sortBy,$parameterizeData,$SortedDataObject);            
            $t=time();
            if(count($SortedDataObject->deviceDatetime)>0)
            {
                //echo "in file exist 2<br>";
                $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
                // echo "dataSize=".$prevSortedSize."<br>";
                $last_rec=0;
                for($obi=0;$obi<$prevSortedSize;$obi++)
                {
                    //echo fgets($file). "<br />";
                    $DataValid = 0;
                    $lat = $SortedDataObject->latitudeData[$obi];
                    $lng = $SortedDataObject->longitudeData[$obi];
                    if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
                    {
                        $DataValid = 1;
                    }
                    $datetime=$SortedDataObject->deviceDatetime[$obi];
                    if ($DataValid==1)
                    {
                        $last_rec = $obi;
                        //echo "lat=".$CurrentLat." lng=".$CurrentLat."<br><br>";
                        $xml_date_current = $datetime;
                        $CurrentLat = $lat ;
                        $CurrentLong =$lng;

                        if((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval)
                        {
                             //echo "Final2";
                            if($firstData==1)
                            {
                                if($minlat>$CurrentLat)
                                {
                                    $minlat = $CurrentLat;
                                }
                                if($maxlat<$CurrentLat)
                                {
                                    $maxlat = $CurrentLat;
                                }
					
                                if($minlong>$CurrentLong)
                                {
                                    $minlong = $CurrentLong;
                                }
                                if($maxlong<$CurrentLong)
                                {
                                    $maxlong = $CurrentLong;
                                }                
                                $tmp1lat = substr($CurrentLat,0,-1);
                                $tmp2lat = substr($LastLat,0,-1);
                                $tmp1lng = substr($CurrentLong,0,-1);
                                $tmp2lng = substr($LastLong,0,-1);  							
                                // echo  "Coord: ".$tmp1lat.' '.$tmp2lat.' '.$tmp1lng.' '.$tmp2lng.'<BR>'; 
                                //echo "lastDate=".$LastDTForDif."<br>";
                                $LastDTForDiffTS=strtotime($LastDTForDif);									
                                $dateDifference=($CurrentDTForDiffTmp-$LastDTForDiffTS)/3600;
                                $dateDifference_1=round($dateDifference,5);
                                //echo" dateDifference=".round($dateDifference,5)."<br>";
                                //echo  "dateDifference: ".$dateDifference.'<BR>'; 									
                                calculate_distance($tmp1lat,$tmp2lat,$tmp1lng,$tmp2lng,$distance);									
                                $overSpeed=$distance/$dateDifference_1;
                            }
                            if($distance<$distanceinterval)
                            {                                   
                                $LastDTForDif=$xml_date_current;
                            }
                                
                            if(($distance>=$distanceinterval) || ($firstData==0))
                            {
                                if($overSpeed<200)
                                {  
                                    $xml_date_last = $xml_date_current;
                                    $LastLat =$CurrentLat;
                                    $LastLong =$CurrentLong;
                               
                                    $finalDistance = $finalDistance + $distance;
                                    $io_typ_value=explode(":",$iotype_element_1);
                                    $io_cnt=count($io_typ_value);
                                    $speed_local =$SortedDataObject->speedData[$obi];
                                    //echo "vc1=".$vd."ve1=".$ve."<br>";
                                    if($ioFoundFlag==1)
                                    {
                                        $temperature=$SortedDataObject->temperatureIOData[$obi];                
                                    }
                                    else
                                    {
                                        $temperature="0.0";
                                    }

                                    $track_report_data[]=array("deviceImeiNo"=>$vehicle_serial,"vehicleName"=>$vname,"vehicleNumber"=>$vehicle_number,"datetime"=>$datetime,"temperature"=>$temperature,"cumdist"=>round($finalDistance,2),"latitude"=>$lat,"longitude"=>$lng,"speed"=>$speed_local);
                                    $firstData = 1; 
                                    $distance=0;
                                }
                            }
                        }
                    }
                }
                if(($overSpeed<200) && ($last_rec!=0))
                {
                    if($ioFoundFlag==1)
                    {
                        $temperature=$SortedDataObject->temperatureIOData[$last_rec];                
                    }
                    else
                    {
                        $temperature="0.0";
                    }
                    $finalDistance = $finalDistance + $distance;
                    $track_report_data[]=array("deviceImeiNo"=>$vehicle_serial,"vehicleName"=>$vname,"vehicleNumber"=>$vehicle_number,"datetime"=>$SortedDataObject->deviceDatetime[$last_rec],"temperature"=>$temperature,"cumdist"=>round($finalDistance,2),"latitude"=>$SortedDataObject->latitudeData[$last_rec],"longitude"=>$SortedDataObject->longitudeData[$last_rec],"speed"=>$SortedDataObject->speedData[$last_rec]);
                }  
            } // if original_tmp exist closed 
        }
    }
    $server = new soap_server();
    $server->register("getTrackDeviceData");
    $server->service($HTTP_RAW_POST_DATA);
	
?>

