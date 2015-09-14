<?php

$sheet1_row = 2;
$sheet2_row = 2;

$sts_date_sel = array();
$xml_date_sel = array();
$lat_sel = array();
$lng_sel = array();
$speed_sel = array();

function get_distance_data($write_file_path) {    
    //###### OPEN CASSANDRA CONNECTION    
    $o_cassandra = openCassandraConnection();
    //##### DEBUG MSG
    $title = "tanker";
    $debug_msg = "";   
    
    echo "\nInAction";
    //echo "\nEnddate	=".$enddate." ,time1_ev=".$time1_ev." ,time2_ev=".$time2_ev;	
    //$date_curr = explode(' ',$enddate);
    //$date_curr_tmp = $date_curr[0]." 03:00:00";
    global $DEBUG_OFFLINE;
    global $DEBUG_ONLINE;
    global $LOG;
    global $abspath;
    //echo "\nSD=" . $startdate . " ,ED=" . $enddate . " ,Time1=" . $time1_ev;
    global $VehicleIMEI;
    
    global $TripDate;
    global $DCSM_NAME;
    global $Route;
    global $VehicleNo;
    global $ActivityTimeForWeightOut;
    global $ActivityTimeForWeightIn;
    global $UniqueVehicle;
    global $VehicleIMEI;

    global $objPHPExcel_1;
    
    $objPHPExcel_1 = null;
    //$objPHPExcel_1 = new PHPExcel();

    $objPHPExcel_1 = PHPExcel_IOFactory::load($write_file_path);
    //echo "\nDebug2:".$write_file_path;
    $header_font = array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('argb' => 'd3d3d3')            //grey
        //'text' => array('argb' => 'FFFC64')
        ),
        'borders' => array(
            'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
        )
    );

    $row = 1;
    //###### HEADER
    /*$col_tmp = 'A' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, "Trip Date");
    $objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
    $col_tmp = 'B' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, "DCSM NAME");
    $objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
    $col_tmp = 'C' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, "Route");
    $objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
    $col_tmp = 'D' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, "Vehicle No");
    $objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
    $col_tmp = 'E' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, "Activity Time For Weight Out");
    $objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
    $col_tmp = 'F' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, "Activity Time For  Weight In");
    $objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);*/
    $col_tmp = 'G' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, "Distance");
    $objPHPExcel_1->getActiveSheet(0)->getStyle($col_tmp)->applyFromArray($header_font);
    
    $row++;
    //#### SECOND TAB CLOSED ##################################################################	
    
   //###### CASSANDRA BLOCK1 ###########
    global $sts_date_sel;
    global $xml_date_sel;
    global $lat_sel;
    global $lng_sel;
    global $speed_sel;

    $dataCnt = 0;
    //$userInterval = "0";
    $requiredData = "All";

    $parameterizeData = new parameterizeData();
    $ioFoundFlag = 0;

    $parameterizeData->latitude = "d";
    $parameterizeData->longitude = "e";
    $parameterizeData->speed = "f";

    $finalVNameArr = array();
    echo "\nSIZEV=" . sizeof($VehicleNo);
    //###### CASSANDRA BLOCK1 CLOSED
    
    for ($i = 0; $i < sizeof($VehicleNo); $i++) {
        
        echo "\nVehicle=" . $i . "::" . $VehicleNo[$i];
   
 
        $date1 = $TripDate[$i]." ".$ActivityTimeForWeightOut[$i];
        $date2 = $TripDate[$i]." ".$ActivityTimeForWeightIn[$i];


	if(strtotime($date1) > strtotime($date2)) {
		$nextdate = date('Y-m-d', strtotime($date1 .' +1 day'));
		$date2 = $nextdate." ".$ActivityTimeForWeightIn[$i];
	}

        $userInterval = 0;
        $sortBy = 'h';
        $firstDataFlag = 0;
        $endDateTS = strtotime($date2);
    
        //######### CASSANDRA BLOCK2 OPENS
        $sts_date_sel = array();
        $xml_date_sel = array();
        $lat_sel = array();
        $lng_sel = array();
        $speed_sel = array();

        //##### DEBUG MSG
        $msg = "\nReadSno:" . $i . " ,imei=" . $VehicleIMEI[$VehicleNo[$i]] . " ,date1=" . $date1 . " ,date2=" . $date2;
        
        if($LOG) {$debug_msg.=$msg."\n";}
        echo $msg; 
                
        $dataCnt = 0;
        $LastSortedDate = null;
        $SortedDataObject = new data();
        $UnSortedDataObject = new data();

        readDataBetweenDatetime($VehicleIMEI[$VehicleNo[$i]], $date1, $date2, $userInterval, $requiredData, $sortBy, $type, $parameterizeData, $firstDataFlag, $o_cassandra, $SortedDataObject);

        echo "\nCount=".count($SortedDataObject->deviceDatetime);
   
	//exit(0);
        if (count($SortedDataObject->deviceDatetime) > 0) {
            //$sortObjTmp = sortData($UnSortedDataObject, $sortBy, $parameterizeData);
            echo "::Data Read";
            //var_dump($sortObjTmp);
            /* echo"sdt1=".$sortObjTmp->deviceDatetime[0]."<br>";
              echo "sdt2=".$sortObjTmp->deviceDatetime[1]."<br>";
              echo "ss1=".$sortObjTmp->speedData[0]."<br>";
              echo "ss2=".$sortObjTmp->speedData[1]."<br>";
              echo "<br><br>"; */
            $sortedSize = sizeof($SortedDataObject->deviceDatetime);
            echo "\nSortedSize=".$sortedSize;
            for ($obi = 0; $obi < $sortedSize; $obi++) {
                /* $finalDateTimeArr[$IMEI[$i]][]=$SortedDataObject->deviceDatetime[$obi];
                  $finalLatitudeArr[$IMEI[$i]][]=$SortedDataObject->latitudeData[$obi];
                  $finalLongitudeArr[$IMEI[$i]][]=$SortedDataObject->longitudeData[$obi];
                  $finalSpeedArr[$IMEI[$i]][]=$SortedDataObject->speedData[$obi]; */
                //echo "\nSTORED";
                $sts_date_sel[] = $SortedDataObject->serverDatetime[$obi];
                $xml_date_sel[] = $SortedDataObject->deviceDatetime[$obi];
                $lat_sel[] = $SortedDataObject->latitudeData[$obi];
                $lng_sel[] = $SortedDataObject->longitudeData[$obi];
                $speed_sel[] = $SortedDataObject->speedData[$obi];
            }
	}
        /*else
        {
	    echo "\nContinue";
            continue;
        }*/
        
        $SortedDataObject = null;
        $sortObjTmp = null;
        $UnsortedDataObject = null;     
        
        //##### DEBUG MSG
       // $msg = "\nVehicle=".$Vehicle[$i]." ,SizeXmlDate=".sizeof($xml_date_sel);
       // if($LOG) {$debug_msg.=$msg."\n";}
        //echo $msg;

        ######## CASSANDRA BLOCK2 CLOSED
        
        $lat_ref = 0.0;
        $lng_ref = 0.0;
        $lat_ref1 = 0.0;
        $lng_ref1 = 0.0;
        $lat_cr = 0.0;
        $lng_cr = 0.0;
         
        $row = $i + 2;
 
        include_once($abspath . "/util.hr_min_sec.php");
        //echo "\nHALT function before1";
        //$interval=$user_interval*60;
        //echo "interval=".$interval."<br>";
        $fix_tmp = 1;
        $xml_date_latest = "1900-00-00 00:00:00";
        $CurrentLat = 0.0;
        $CurrentLong = 0.0;
        $LastLat = 0.0;
        $LastLong = 0.0;
        $firstData = 0;
        $distance = 0.0;
        $linetowrite = "";
        $firstdata_flag = 0;

        $arrivale_time = "";
        $depature_time = "";
        $hrs_min = "";
        $cum_dist = 0;
        //echo "\nTEST3";
	//date_default_timezone_set("Asia/Calcutta");
        $current_datetime = date("Y-m-d H:i:s");
        $current_date = date("Y-m-d");

        $date_size = sizeof($userdates);
        $substr_count = 0;

        $AddEntryinrReport = false;

        $total_lines = sizeof($xml_date_sel);
        //echo "\nAfter Sorting:size=".$total_lines;
        $DataComplete = false;
        $vehicleserial_tmp = null;
        $f = 0;
        $tmp = 0;

        //exit(0);
        
        if (sizeof($xml_date_sel) > 0) {
            //echo "\nFile ExistFinal";
            $firstdata_flag =0;
            $total_dist = 0.0;
            $userInterval = 1;

            for ($y = 0; $y < sizeof($xml_date_sel); $y++) {          // WHILE LINE != NULL                
                //echo "\nXML_DATE_SEL=".$datetime." ,time1=".$time1_ev." ,enddate=".$enddate;
                //########## STORE VEHICLE COUNTER																	  					
                $nodata = false;
                //echo "\nNodata2=".$nodata;

                $datetime = $xml_date_sel[$y];
                //$device_all_date_time[] = $datetime;

//                if ((strtotime($datetime) > strtotime($time1_ev)) && (strtotime($datetime) < strtotime($enddate))) {
		if ((strtotime($datetime) < strtotime($date2))) {
                    
                    if($firstdata_flag==0)
                    {					
                        $firstdata_flag = 1;

                        $lat1 = $lat_sel[$y];
                        $lng1 = $lng_sel[$y];
                        $last_time = $datetime;
                        $last_time1 = $datetime;
                        $latlast = $lat_sel[$y];
                        $lnglast = $lng_sel[$y];
                        //echo "<br>FirstData:".$date_secs1." ".$time1;                 	
                    }
                    else
                    {                           					
                        // echo "<br>Total lines orig=".$total_lines." ,c=".$c;
                        $time2 = $datetime;											
                        $date_secs2 = strtotime($time2);	

                        $lat2 = $lat_sel[$y];  				        					
                        $lng2 = $lng_sel[$y];
                        
                        calculate_distance($lat1, $lat2, $lng1, $lng2, $distance);
                        if($distance>2000)
                        {
                            $distance=0;
                            $lat1 = $lat2;
                            $lng1 = $lng2;
                        }
                        //echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance." ,datetime=".$datetime;
                        $tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;
                        calculate_distance($latlast, $lat2, $lnglast, $lng2, $distance1);
                        //echo "<br>latlast=".$latlast." ,lat2=".$lat2." ,lnglast=".$lnglast." ,lng2=".$lng2." ,distance1=".$distance1." , datetime=".$datetime."<br>";

                        $tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time) )) / 3600;

                        if($tmp_time_diff1>0)
                        {									
                            $tmp_speed = ((double) ($distance)) / $tmp_time_diff;
                            $tmp_speed1 = ((double) ($distance1)) / $tmp_time_diff1;
                        }
                        else
                        {
                            $tmp_speed1 = 1000.0; //very high value
                        }
                        if($tmp_speed<300.0)
                        {
                            $speeed_data_valid_time = $datetime;
                        }

                        if(( strtotime($datetime) - strtotime($speeed_data_valid_time) )>300) //data high speed for 5 mins
                        {
                            $lat1 = $lat2;
                            $lng1 = $lng2;
                            $last_time = $datetime;
                        }
                        $last_time1 = $datetime;
                        $latlast = $lat2;
                        $lnglast =  $lng2;

                        //echo "lat1=".$lat1."lng1=".$lng1."lat2=".$lat2." lng2=".$lng2."<br>";
                        //echo "datetime=".$datetime." distance=".$distance." total_dist=".$total_dist." tmpspeed=".$tmp_speed." tmpspeed1=".$tmp_speed1." tmp_time_diff=".$tmp_time_diff." tmp_time_diff1=".$tmp_time_diff1."<br>";

                        if($tmp_speed<300.0 && $tmp_speed1<300.0 && $distance>0.1 && $tmp_time_diff>0.0 && $tmp_time_diff1>0)
                        {								
                            $total_dist = (double)( $total_dist + $distance );                          
                            						                          
                            $lat1 = $lat2;
                            $lng1 = $lng2;
                            $last_time = $datetime;              		    						
                        }
                    }
                }
            }
        }
        
        $total_dist = round($total_dist,2);
	echo "\nTotalDistance=".$total_dist."\n";
        update_distance_status($objPHPExcel_1, $write_file_path, $TripDate[$i],$DCSM_NAME[$i],$Route[$i],$VehicleNo[$i],$ActivityTimeForWeightOut[$i],$ActivityTimeForWeightIn[$i], $total_dist, $i);
    } //##### EXCEL VEHICLE LOOP CLOSED
    
    ######## CLOSE CASSANDRA CONNECTION	
    closeCassandraConnection($o_cassandra);

    //echo date('H:i:s') , " Write to Excel2007 format" , EOL;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
    $objWriter->save($write_file_path);
    echo date('H:i:s'), " File written to ", $write_file_path, EOL;

    echo "\nHALT CLCLOSED";
}

//######## UPDATE DISTANCE
function update_distance_status($objPHPExcel_1, $write_file_path, $TripDate,$DCSM_NAME,$Route,$VehicleNo,$ActivityTimeForWeightOut,$ActivityTimeForWeightIn, $Distance, $k) {

    $row = $k + 2;
 
    /*$col_tmp = 'A' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $TripDate);
    
    $col_tmp = 'A' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $DCSM_NAME);
    
    $col_tmp = 'A' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $Route);

    $col_tmp = 'A' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $VehicleNo);

    $col_tmp = 'A' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $ActivityTimeForWeightOut);

    $col_tmp = 'A' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $ActivityTimeForWeightIn);*/

    $col_tmp = 'G' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $Distance);
}



function cellColor($cells, $color) {
    global $objPHPExcel_1;
    $objPHPExcel_1->getActiveSheet()->getStyle($cells)->getFill()
            ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => $color)
    ));
}

function write_log($title,$difftime) {
    global $debug_msg;
    $log_file = "/mnt/phpReportLog/".$title."_".$difftime.".txt";
    $file = fopen($log_file,"a");
    fwrite($file,$debug_msg);
    fclose($file);
}
?>
