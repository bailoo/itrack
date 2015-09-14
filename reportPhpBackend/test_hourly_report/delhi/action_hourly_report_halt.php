<?php

$sheet1_row = 2;
$sheet2_row = 2;

$sts_date_sel = array();
$xml_date_sel = array();
$lat_sel = array();
$lng_sel = array();
$speed_sel = array();

function get_halt_xml_data($startdate, $enddate, $read_excel_path, $time1_ev, $time2_ev, $shift_1, $difftime,$route_type) {    
    //###### OPEN CASSANDRA CONNECTION
    global $last_processed_time;
    $o_cassandra = openCassandraConnection();
    //##### DEBUG MSG
    $title = "delhi";
    $debug_msg = "";   
    
    echo "\nInAction";
    //echo "\nEnddate	=".$enddate." ,time1_ev=".$time1_ev." ,time2_ev=".$time2_ev;	
    //$date_curr = explode(' ',$enddate);
    //$date_curr_tmp = $date_curr[0]." 03:00:00";
    global $va, $vb, $vc, $vd, $ve, $vf, $vg, $vh, $vi, $vj, $vk, $vl, $vm, $vn, $vo, $vp, $vq, $vr, $vs, $vt, $vu, $vv, $vw, $vx, $vy, $vz, $vaa, $vab;
    global $old_xml_date;
    global $DEBUG_OFFLINE;
    global $DEBUG_ONLINE;
    global $LOG;
    global $abspath;
    echo "\nSD=" . $startdate . " ,ED=" . $enddate . " ,Time1=" . $time1_ev;
    global $Vehicle;   //SENT FILE
    global $SNo;
    global $StationNo;
    global $Type;
    global $RouteNo;
    global $ReportShift;
    global $HourBand;
    global $ArrivalDate;
    global $ArrivalTime;
    global $DepartureDate;
    global $DepartureTime;
    global $ScheduleTime;
    global $Delay;
    global $HaltDuration;
    global $Remark;
    global $ReportDate1;
    global $ReportTime1;
    global $ReportDate2;
    global $ReportTime2;
    global $TransporterM;
    global $TransporterI;
    global $Plant;
    //global $Km;
    //echo "\nDebug1";
    global $Lat;
    global $Lng;
    global $DistVar;
    global $IMEI;
    global $RouteType;
    global $NO_GPS;
    global $VisitStatus;
    //global $PlantIn;

    /* global $PlantLat;
      global $PlantLng; */
    global $PlantCoord;
    global $PlantDistVar;
    global $Status;
    global $SecondaryVehicle;

    global $PlantInDate;
    global $PlantInTime;
    global $PlantOutDate;
    global $PlantOutTime;

    global $PlantOutScheduleTime;
    global $PlantOutDelay;

    global $objPHPExcel_1;

    global $last_vehicle_name;
    global $last_halt_time;
    global $last_halt_time_new;

    //######## SORTED ROUTES
    global $Vehicle_CI;
    global $StationNo_CI;
    global $RouteNo_CI;
    global $RouteType_CI;
    global $ArrivalTime_CI;
    global $TransporterI_CI;

    global $unmapped_customers;
    
    $date_tmp1 = explode(" ", $startdate);
    $date_tmp2 = explode(" ", $enddate);
    $report_date1 = $date_tmp1[0];
    $report_time1 = $date_tmp1[1];
    $report_date2 = $date_tmp2[0];
    $report_time2 = $date_tmp2[1];    

    $current_halt_time = 0;
    $objPHPExcel_1 = null;
    //$objPHPExcel_1 = new PHPExcel();

    $objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);
    //echo "\nDebug2:".$read_excel_path;
    //##### REMOVE EXTRA SHEETS
    $objPHPExcel_1->removeSheetByIndex(2);
    $objPHPExcel_1->removeSheetByIndex(1);

    //echo date('H:i:s') , " Write to Excel2007 format" , EOL;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
    $objWriter->save($read_excel_path);
    //echo date('H:i:s'), " File written to ", $read_excel_path, EOL;

    //###### RELOAD SHEET
    $objPHPExcel_1 = null;
    $objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);

    //####### RECREATE EXTRA SHEETS #####################
    //###################################################	
    /* $header_font = array(
      'font'  => array(
      'bold'  => true,
      'color' => array('rgb' => '000000'), //RED
      'size'  => 10
      //'name'  => 'Verdana'
      )); */
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

    $objPHPExcel_1->createSheet();
    $objPHPExcel_1->setActiveSheetIndex(1)->setTitle('Route Completed');

    $objPHPExcel_1->createSheet();
    $objPHPExcel_1->setActiveSheetIndex(2)->setTitle('Route Pending');
    //echo "\nSecond tab";
    $row = 1;
    //###### HEADER
    $col_tmp = 'A' . $row;
    $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp, "Vehicle");
    $objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
    $col_tmp = 'B' . $row;
    $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp, "Route");
    $objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
    $col_tmp = 'C' . $row;
    $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp, "CustomerCompleted(All)");
    $objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
    $col_tmp = 'D' . $row;
    $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp, "Transporter(I)");
    $objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
    $col_tmp = 'E' . $row;
    $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp, "RouteType");
    $objPHPExcel_1->getActiveSheet(1)->getStyle($col_tmp)->applyFromArray($header_font);
    $row++;
    //#### SECOND TAB CLOSED ##################################################################	
    //############################### THIRD TAB ###############################################
    //echo "\nThird tab";
    $row = 1;
    //####### DEFINE HEADER
    $col_tmp = 'A' . $row;
    $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, "Vehicle");
    $objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
    $col_tmp = 'B' . $row;
    $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, "Route");
    $objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
    $col_tmp = 'C' . $row;
    $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, "Customer Completed");
    $objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
    $col_tmp = 'D' . $row;
    $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, "Customer Incompleted");
    $objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);

    $col_tmp = 'E' . $row;
    $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, "Unmapped Customers");
    $objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);

    $col_tmp = 'F' . $row;
    $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, "Transporter(I)");
    $objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
    $col_tmp = 'G' . $row;
    $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, "RouteType");
    $objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);
    $col_tmp = 'H' . $row;
    $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, "Remark");
    $objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($header_font);

    $row++;
    //######## EXTRA SHEET CLOSED
    //echo "\nSD=".$startdate." ,ED=".$enddate." ,read_excel_path=".$read_excel_path." ,VehicleSize=".sizeof($Vehicle);
    //echo "\nSizeVehicle=" . sizeof($Vehicle);
    
   //###### CASSANDRA BLOCK1 ###########
    global $sts_date_sel;
    global $xml_date_sel;
    global $lat_sel;
    global $lng_sel;
    global $speed_sel;

    $date1 = $startdate;
    $date2 = $enddate;
    $datefrom = $report_date1;
    $dateto = $report_date2;

    $userInterval = 0;

    $sortBy = 'h';
    $firstDataFlag = 0;
    $endDateTS = strtotime($date2);
    $dataCnt = 0;
    //$userInterval = "0";
    $requiredData = "All";

    $parameterizeData = new parameterizeData();
    $ioFoundFlag = 0;

    $parameterizeData->latitude = "d";
    $parameterizeData->longitude = "e";
    $parameterizeData->speed = "f";

    $finalVNameArr = array();
    echo "\nSIZEV=" . sizeof($Vehicle);
    //###### CASSANDRA BLOCK1 CLOSED
    
    for ($i = 0; $i < sizeof($Vehicle); $i++) {
        
        //$device_all_date_time = array();    //ALL DEVICE TIME
        
        if($last_processed_time[$IMEI[$i]]!="") {
            $date1 = $last_processed_time[$IMEI[$i]];
        } 
      
        echo "\nVehicle=" . $i . "::" . $Vehicle[$i];        
        //######### CASSANDRA BLOCK2 OPENS
        $sts_date_sel = array();
        $xml_date_sel = array();
        $lat_sel = array();
        $lng_sel = array();
        $speed_sel = array();

        //##### DEBUG MSG
        $msg = "\nReadSno:" . $i . " ,imei=" . $IMEI[$i] . " ,date1=" . $date1 . " ,date2=" . $date2;
        
        if($LOG) {$debug_msg.=$msg."\n";}
        //echo $msg; 
                
        $dataCnt = 0;
        $LastSortedDate = null;
        $SortedDataObject = new data();
        $UnSortedDataObject = new data();

        readDataBetweenDatetime($IMEI[$i], $date1, $date2, $userInterval, $requiredData, $sortBy, $type, $parameterizeData, $firstDataFlag, $o_cassandra, $SortedDataObject);

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
            //echo "\nSortedSize=".$sortedSize;
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
        $msg = "\nVehicle=".$Vehicle[$i]." ,SizeXmlDate=".sizeof($xml_date_sel);
        if($LOG) {$debug_msg.=$msg."\n";}
        //echo $msg;

        ######## CASSANDRA BLOCK2 CLOSED
        
        $lat_ref = 0.0;
        $lng_ref = 0.0;
        $lat_ref1 = 0.0;
        $lng_ref1 = 0.0;
        $lat_cr = 0.0;
        $lng_cr = 0.0;
        /* $plant_lat_local[$Vehicle[$i]][$RouteNo[$i]] = $PlantLat[$i];
          $plant_lng_local[$Vehicle[$i]][$RouteNo[$i]] = $PlantLng[$i]; */
        $plant_coord_local[$Vehicle[$i]][$RouteNo[$i]] = $PlantCoord[$i];
        $plant_distvar_local[$Vehicle[$i]][$RouteNo[$i]] = $PlantDistVar[$i];
        $plant_status_local[$Vehicle[$i]][$RouteNo[$i]] = $Status[$i];

        $plant_indate_local[$Vehicle[$i]][$RouteNo[$i]] = $PlantInDate[$i];
        $plant_outdate_local[$Vehicle[$i]][$RouteNo[$i]] = $PlantOutDate[$i];

        $plant_intime_local[$Vehicle[$i]][$RouteNo[$i]] = $PlantInTime[$i];
        $plant_outtime_local[$Vehicle[$i]][$RouteNo[$i]] = $PlantOutTime[$i];

        //echo "\nPlantLatLocal=".$plant_lat_local[$Vehicle[$i]][$RouteNo[$i]];		
        //$userdates = array();
        $nodata = true;
        $nodata_last = true;
        $nogps = true;
        
        $row = $i + 2;
        //###### GET LAST HALT TIME
        $vehicle_serial = $IMEI[$i];
        $interval = 60;
        for ($h = 0; $h < sizeof($last_vehicle_name); $h++) {
            if (trim($Vehicle[$i]) == trim($last_vehicle_name[$h])) {
                $last_halt_time_excel = $last_halt_time[$h];
                //echo "<br>LastHaltExcel Found:".$last_halt_time_excel;
                /* if($last_halt_time_excel > 0)
                  {
                  $interval = ($interval - $last_halt_time_excel);
                  } */
                break;
            }
        }

        //echo "1\n";
        //get jcnt
        $j = $i;
        while ($Vehicle[$j] == $Vehicle[$i]) {
            $j++; //J LIMIT
        }

        $last_flag = false;
        if (sizeof($last_vehicle_name)) {
            $last_flag = true;
            //echo "\nLastFlagTrue";
        }
        $halt_time_sec = 0;

        //echo "2\n";
        global $last_halt_sec_global;
        //$startdate = "2013-05-12 16:51:49";
        //$enddate = "2013-05-12 21:24:04";	
        include_once($abspath . "/util.hr_min_sec.php");
        //echo "\nHALT function before1";
        global $customer_visited;
        global $shift;
        global $point;
        global $timing;
        global $vehicle_t;
        global $transporter;

        global $route_input;
        global $customer_input;
        global $vehicle_input;
        global $shift_input;
        global $transporter_input;

        $delay = "-";
        $schedule_time = "-";
        $route_no = "-";
        $transporter_name_master = "-";
        $transporter_name_input = "-";

        $date_tmp1 = explode(" ", $startdate);
        $date_tmp2 = explode(" ", $enddate);
        $report_date1 = $date_tmp1[0];
        $report_time1 = $date_tmp1[1];
        $report_date2 = $date_tmp2[0];
        $report_time2 = $date_tmp2[1];

        //echo "\nReportDate1=".$date_tmp1;
        //echo "3\n";
        //$abspath = "D:\\test_app";
        //include_once($abspath."/get_location_lp_track_report.php");		
        global $DbConnection;
        global $account_id;
        global $geo_id1;
        $sno = 1;
        global $csv_string_halt;
        global $overall_dist;
        global $total_halt_dur;

        global $station_id;
        global $type;
        global $customer_no;
        global $station_name;
        global $station_coord;
        global $distance_variable;
        global $google_location;

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
            $halt_once = false;

            $p_in = false;
            $p_out = false;

            for ($y = 0; $y < sizeof($xml_date_sel); $y++) {          // WHILE LINE != NULL                
                //echo "\nXML_DATE_SEL=".$datetime." ,time1=".$time1_ev." ,enddate=".$enddate;
                //########## STORE VEHICLE COUNTER																	  					
                $nodata = false;
                //echo "\nNodata2=".$nodata;

                $datetime = $xml_date_sel[$y];
                //$device_all_date_time[] = $datetime;

//                if ((strtotime($datetime) > strtotime($time1_ev)) && (strtotime($datetime) < strtotime($enddate))) {
		if ((strtotime($datetime) < strtotime($enddate))) {
                    $nodata_last = false;

                    //echo "\nStatus=".$plant_status_local[$Vehicle[$i]][$RouteNo[$i]]." ,lat=".$plant_lat_local[$Vehicle[$i]][$RouteNo[$i]]." ,lng=".$plant_lng_local[$Vehicle[$i]][$RouteNo[$i]];						
                    $plant_valid = false;
                    //echo "\nPLANT_STATUS=".$plant_status_local[$Vehicle[$i]][$RouteNo[$i]];

                    if ($plant_status_local[$Vehicle[$i]][$RouteNo[$i]] == 0) {
                        //echo "\nPlantZero";
                        //check(plantcord,current cord);
                        $total_coords = explode('#', $plant_coord_local[$Vehicle[$i]][$RouteNo[$i]]);
                        $tmp_radius = explode('#', $plant_distvar_local[$Vehicle[$i]][$RouteNo[$i]]);

                        for ($p = 0; $p < sizeof($total_coords); $p++) {
                            $tmp_coord = explode(',', $total_coords[$p]);

                            //echo "\nplant_coord[0]=".$tmp_coord[0].", plan_coord[1]=".$tmp_coord[1]." ## lat_sel[y]=".$lat_sel[$y]." ,lng_sel[y]=".$lng_sel[$y];
                            /* if($DEBUG_OFFLINE)
                              {
                              $distance_plant = calculate_distance(trim($tmp_coord[0]), $lat_sel[$y], trim($tmp_coord[1]), $lng_sel[$y]);
                              }
                              else
                              { */
                            calculate_distance(trim($tmp_coord[0]), $lat_sel[$y], trim($tmp_coord[1]), $lng_sel[$y], $distance_plant);
                            //}							
                            //echo "\nPlantLat=".$plant_lat_local[$Vehicle[$i]][$RouteNo[$i]]." ,PlantLng=".$plant_lng_local[$Vehicle[$i]][$RouteNo[$i]]." ,LatSel=".$lat_sel[$y]." ,LngSel=".$lng_sel[$y];
                            //echo "\nDistance1=".$distance_plant." ,tmp_radius[p]=".$tmp_radius[$p];
                            if ($distance_plant < $tmp_radius[$p]) {
                                //echo "\nIN:PLANT-1:".$datetime;
                                //$plant_status_local[$Vehicle[$i]][$RouteNo[$i]] = 1;
                                //echo "<br>PlantINTimePrev=".$plant_intime_local[$Vehicle[$i]][$RouteNo[$i]];
                                if ($plant_intime_local[$Vehicle[$i]][$RouteNo[$i]] == "") {
                                    //echo "\nIN:Plant2=".$datetime;
                                    //if( ($shift_1=="ZPMM") || (($shift_1=="ZPME") && ($plant_time_ev=="currentday")) || (($shift_1=="ZPME") && ($plant_time_ev=="nextday") && (strtotime($datetime) < strtotime($date_curr_tmp))))
                                    if ((($shift_1 == "ZPMM") && ($difftime > 10800 && $difftime < 43200)) || ( ($shift_1 == "ZPME") && ($difftime > 43200 || $difftime < 10800) )) {
                                    //if( (($shift_1=="ZPMM") && ($difftime>10800 && $difftime<43200)) || ($shift_1=="ZPME"))           //echo "\nPlantIN";
                                        $plant_intime_local[$Vehicle[$i]][$RouteNo[$i]] = $datetime;
                                        $plant_status_local[$Vehicle[$i]][$RouteNo[$i]] = 1;
                                        $p_in = true;
                                    }
                                }

                                $plant_valid = true;
                                break;
                            }
                        }

                        //## CHECK SECONDARY VEHICLS
                        for ($s = 0; $s < sizeof($SecondaryVehicle); $s++) {
                            if (trim($Vehicle[$i]) == trim($SecondaryVehicle[$s])) {
                                $plant_status_local[$Vehicle[$i]][$RouteNo[$i]] = 2;
                                $plant_valid = true;
                                break;
                            }
                        }

                        if (!$plant_valid) {
                            //echo "\nContinue";
                            $nogps = false;
                            continue;
                        }
                    } else if ($plant_status_local[$Vehicle[$i]][$RouteNo[$i]] == 1) {
                        $plant_in_datetime = $plant_indate_local[$Vehicle[$i]][$RouteNo[$i]] . " " . $plant_intime_local[$Vehicle[$i]][$RouteNo[$i]];
                        $plant_out_datetime = $plant_outdate_local[$Vehicle[$i]][$RouteNo[$i]] . " " . $plant_outtime_local[$Vehicle[$i]][$RouteNo[$i]];

                        if ((($plant_outtime_local[$Vehicle[$i]][$RouteNo[$i]] == "") && ($plant_intime_local[$Vehicle[$i]][$RouteNo[$i]] != "")) && (strtotime($datetime) > strtotime($plant_in_datetime))) {
                            //echo "\nPlantIntime".$plant_in_datetime;
                            //echo "\nPlant One";
                            $total_coords = explode('#', $plant_coord_local[$Vehicle[$i]][$RouteNo[$i]]);
                            $tmp_radius = explode('#', $plant_distvar_local[$Vehicle[$i]][$RouteNo[$i]]);

                            for ($p = 0; $p < sizeof($total_coords); $p++) {
                                $tmp_coord = explode(',', $total_coords[$p]);

                                //echo "\nplant_coord[0]=".$tmp_coord[0].", plan_coord[1]=".$tmp_coord[1]." ## lat_sel[y]=".$lat_sel[$y]." ,lng_sel[y]=".$lng_sel[$y];
                                /* if($DEBUG_OFFLINE)
                                  {
                                  $distance_plant = calculate_distance(trim($tmp_coord[0]), $lat_sel[$y], trim($tmp_coord[1]), $lng_sel[$y]);
                                  }
                                  else
                                  { */
                                calculate_distance(trim($tmp_coord[0]), $lat_sel[$y], trim($tmp_coord[1]), $lng_sel[$y], $distance_plant);
                                //}
                                //echo "\nPlantLat=".$plant_lat_local[$Vehicle[$i]][$RouteNo[$i]]." ,PlantLng=".$plant_lng_local[$Vehicle[$i]][$RouteNo[$i]]." ,LatSel=".$lat_sel[$y]." ,LngSel=".$lng_sel[$y];
                                //echo "\nVehicle=".$Vehicle[$i]." ,Distance1=".$distance_plant." ,tmp_radius[p]=".$tmp_radius[$p];
                                if ($distance_plant > $tmp_radius[$p]) {
                                    //echo "\nOUT-Plant2:".$datetime." ,plant_indate=".$plant_in_datetime;
// if( ($shift_1=="ZPMM") || (($shift_1=="ZPME") && ($plant_time_ev=="currentday")) || (($shift_1=="ZPME") && ($plant_time_ev=="nextday") && (strtotime($datetime) < strtotime($date_curr_tmp))))											
                                    if ((($shift_1 == "ZPMM") && ($difftime > 10800 && $difftime < 43200)) || ( ($shift_1 == "ZPME") && ($difftime > 43200 || $difftime < 10800) )) {
//if( (($shift_1=="ZPMM") && ($difftime>10800 && $difftime<43200)) || ($shift_1=="ZPME") )

                                        $plant_outtime_local[$Vehicle[$i]][$RouteNo[$i]] = $datetime;

                                        $p_out = true;
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    if ($firstdata_flag == 0) {
                        $halt_flag = 0;
                        $firstdata_flag = 1;

                        //$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
                        //$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);						
                        $vserial = $vehicle_serial;
                        $lat_ref = $lat_sel[$y];
                        $lng_ref = $lng_sel[$y];

                        if ($lat_ref != "" && $lng_ref != "") {
                            $nogps = false;
                        }
                        $datetime_ref = $datetime;
                        $cum_dist = 0;

                        //###### FOR IRREGULAR DATA FILTER CODE
                        $last_time1 = $datetime;
                        $latlast = $lat_ref;
                        $lnglast = $lng_ref;
                        //////##############################
                        //$date_secs1 = strtotime($datetime_ref);							
                        //$date_secs1 = (double)($date_secs1 + $interval);      	
                    } else {
                        //echo "<br>Next";               
                        //GET NEXT RECO
                        $lat_cr = $lat_sel[$y];
                        $lng_cr = $lng_sel[$y];
                        $datetime_cr = $datetime;
                        $date_secs2 = strtotime($datetime_cr);

                        /* if($DEBUG_OFFLINE)
                          {
                          $distance = calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr);
                          }
                          else
                          { */
                        calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, $distance);
                        //}
                        //if(($distance > 0.0100) || ($f== $total_lines-2) )
                        //echo "\nF=".$f." ,total_lines=".$total_lines;										
                        //###### FOR IRREGULAR DATA FILTER CODE
                        $tmp_time_diff1 = (double) (strtotime($datetime) - strtotime($last_time1)) / 3600;

                        /* if($DEBUG_OFFLINE)
                          {
                          $distance1 = calculate_distance($latlast, $lat_cr, $lnglast, $lng_cr);
                          }
                          else
                          { */
                        calculate_distance($latlast, $lat_cr, $lnglast, $lng_cr, $distance1);
                        //}
                        //echo "<br>Distance=".$distance1;

                        if ($tmp_time_diff1 > 0) {
                            $tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
                            //if($tmp_speed==0) echo "\nDistance1=".$distance1." ,tmp_time_diff1=".$tmp_time_diff1." ,latlast=".$latlast." ,lnglast=".$lnglast." ,lat_cr=".$lat_cr." ,lng_cr=".$lng_cr;
                            $last_time1 = $datetime;
                            $latlast = $lat_cr;
                            $lnglast = $lng_cr;
                        }
                        $tmp_time_diff = ((double) ( strtotime($datetime) - strtotime($last_time) )) / 3600;
                        //#######################################
                        //if (($halt_flag == 1) && ($distance > 0.100))
                        {
                            //echo "\n\nIF HALT, datetime=".$datetime." ,ArrivalTime=".$datetime_ref." ,DepartureTime=".$datetime_cr;
                            $arrivale_time = $datetime_ref;
                            $starttime = strtotime($datetime_ref);
                            $stoptime = strtotime($datetime_cr);
                            $depature_time = $datetime_cr;

                            $halt_dur = ($stoptime - $starttime);  //THIS IS USED AT RUNTIME, COMMENT HERE LATER
                            $hms_2 = secondsToTime($halt_dur);
                            $hrs_min = $hms_2[h] . ":" . $hms_2[m] . ":" . $hms_2[s];

                            $AddEntryinrReport = true;
                            //check for all vehicles with j as i to jcnt and check for customer where arrival is not there

                            for ($k = $i; $k < $j; $k++) {

                                if (($PlantInTime[$k] == "") || ($PlantOutTime[$k] == "")) {
                                    //UPDATE PLANT
                                    update_plant($k, $objPHPExcel_1, $read_excel_path, $p_in, $plant_intime_local[$Vehicle[$i]][$RouteNo[$i]], $p_out, $plant_outtime_local[$Vehicle[$i]][$RouteNo[$i]], $startdate, $enddate);
                                }

                                //echo "\nArrivalTime[$k]=".$ArrivalTime[$k]." ,DepartureTime[$k]=".$DepartureTime[$k];
                                if (($ArrivalTime[$k] != "") && ($DepartureTime[$k] == "")) {
                                    $arrtime_str = $ArrivalDate[$k] . " " . $ArrivalTime[$k];
                                    $deptime = strtotime($depature_time);
                                    $arrtime = strtotime($arrtime_str);
                                    $halt_dur = ($deptime - $arrtime);  //THIS IS USED AT RUNTIME, COMMENT HERE LATER
                                    $hms_2 = secondsToTime($halt_dur);
                                    $hrs_min = $hms_2[h] . ":" . $hms_2[m] . ":" . $hms_2[s];
                                    //echo "\nDepartureFound:Time=".$datetime;

                                    update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle[$k], $k, $StationNo[$k], $Lat[$k], $Lng[$k], $ScheduleTime[$k], $DistVar[$k], $Remark[$k], $startdate, $enddate, $lat_cr, $lng_cr, $lat_cr, $lng_cr, $arrivale_time, $depature_time, $RouteNo[$k], $hrs_min, $Type[$k], $plant_status_local[$Vehicle[$i]][$RouteNo[$i]], 2, $p_in, $plant_intime_local[$Vehicle[$i]][$RouteNo[$i]], $p_out, $plant_outtime_local[$Vehicle[$i]][$RouteNo[$i]]);
                                }
                            }

                            $last_halt_time_excel = 0;
                            $halt_flag = 0;
                        }
                        if (($distance <= 0.100) && ($halt_flag == 0) && ( (strtotime($datetime_cr) - strtotime($datetime_ref)) < ($interval - $last_halt_time_excel))) {
                            $current_halt_time = $current_halt_time + (strtotime($datetime_cr) - strtotime($datetime_ref));
                            //echo "<br>HaltContinued:".$current_halt_time;
                        }
                        //else if(($distance <= 0.100) && ($halt_flag == 0) && ( (strtotime($datetime_cr)-strtotime($datetime_ref))>($interval-$last_halt_time_excel)) )    // IF VEHICLE STOPS FOR 2 MINS 
                        else if ((($distance <= 0.100) && ($halt_flag == 0) && ( (strtotime($datetime_cr) - strtotime($datetime_ref)) > ($interval - $last_halt_time_excel))) || ($speed_sel[$y] < 5.0)) {
                            for ($k = $i; $k < $j; $k++) {
                                if ($ArrivalTime[$k] == "") {
                                    //echo "\nArrivalFound:".$Vehicle[$k]." ,Station=".$StationNo[$k];
                                    update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle[$k], $k, $StationNo[$k], $Lat[$k], $Lng[$k], $ScheduleTime[$k], $DistVar[$k], $Remark[$k], $startdate, $enddate, $lat_ref1, $lng_ref1, $lat_cr, $lng_cr, $datetime_ref, $depature_time, $RouteNo[$k], $hrs_min, $Type[$k], $plant_status_local[$Vehicle[$i]][$RouteNo[$i]], 1, $p_in, $plant_intime_local[$Vehicle[$i]][$RouteNo[$i]], $p_out, $plant_outtime_local[$Vehicle[$i]][$RouteNo[$i]]);
                                }
                            }
                            $current_halt_time = 0;
                            $halt_once = 1;
                            //echo "\nHALT FLAG SET, datetime=".$datetime;
                            $halt_flag = 1;
                            $lat_ref1 = $lat_cr;
                            $lng_ref1 = $lng_cr;
                            $datetime_ref = $datetime_cr;
                            //echo "<br>HaltOver:".$current_halt_time;							
                        } else if ($distance > 0.100) {
                            //###### FOR IRREGULAR DATA FILTER CODE
                            if ($tmp_speed < 500.0 && $tmp_time_diff > 0.0) {
                                $cum_dist = $cum_dist + $distance;
                                //echo "\nTmp_speed=".$tmp_speed." ,tmp_time_diff=".$tmp_time_diff." ,cum_dist=".$cum_dist;
                                //echo "<br>dist greater than 0.025: dist=".$total_dist." time2=".$time2;											
                                $last_time = $datetime;
                                $datetime_ref = $datetime_cr;

                                //$lat_ref = $lat_cr;
                                //$lng_ref = $lng_cr;																					
                                //#######################################																						
                            }
                            $lat_ref = $lat_cr;
                            $lng_ref = $lng_cr;
                            $datetime_ref = $datetime_cr;    //modified   				
                            $halt_flag = 0;   //modified										
                        }
                    }  //else closed
                }
                $f++;
            }   // CLOSED- SORTED DATA FOR LOOP
            
            //#### GET MAX TIME OF CURRENT VEHICLE
            //$max_device_time = max($device_all_date_time);
            $max_device_time = $datetime;
            echo "\nMaxDeviceTime=".$max_device_time." type=".$shift_1." routetype=".$route_type;
            update_last_processed_time($shift_1,$max_device_time, $IMEI[$i],$route_type);
        }

        /* //######## COLOR BLANK ENTRIES
          for($k=$i;$k<$j;$k++)
          {
          $row2 = $k+2;
          $pos_c = strpos($StationNo[$k], "@");
          if($pos_c !== false)
          {
          //echo "\nNegative Found";
          $customer_at_the_rate1 = explode("@", $StationNo[$k]);
          }
          else
          {
          $customer_at_the_rate1[0] = $StationNo[$k];
          }
          //else if($VisitStatus[trim($Vehicle)][trim($customer_at_the_rate1[0])]==1)
          if(($VisitStatus[trim($RouteNo[$k])][trim($customer_at_the_rate1[0])]==1) && ($ArrivalTime[$k]==""))
          {
          cellColor('A'.$row2.':AN'.$row2, 'FFD2E8');
          }
          } */
        //############################
        //### STORE LAST HALT TIME OF VEHICLE		
        $last_vehicle_name[] = $Vehicle[$i];
        $last_halt_time_new[] = $current_halt_time;
        //echo "<br>CurrentHaltTime:".$Vehicle[$i]." :".$current_halt_time;
        //echo "\nNoData=".$nodata." ,Vehicle=".$Vehicle[$i];

        $msg = "";
        if ($nodata) {
            $msg = "INACTIVE";
        } else if (!$nogps) {   //GPS FOUND
            //update_nogps($objPHPExcel_1, "0", $i);
            $NO_GPS[$i] = "0";
            $msg = "";
        } else if (($NO_GPS[$i] == "") && ($nogps)) {  //FIRST TIME : GPS NOT FOUND
            //update_nogps($objPHPExcel_1, "1", $i);
            $NO_GPS[$i] = "1";
            $msg = "NO GPS";
        }

        $Remark[$i] = $msg;
        //update_remark($objPHPExcel_1, $msg, $i);

        //######### UPDATE EXTRA SHEETS
        $customer_visited = array();
        $customer_unvisited = array();

        $store = true;
        for ($k = $i; $k < $j; $k++) {
            /* if($Vehicle[$k]==":")
              {
              $store=false;
              }
              if($store) */
            //{
            $Vehicle_CI[] = $Vehicle[$k];
            $StationNo_CI[] = $StationNo[$k];
            $RouteNo_CI[] = $RouteNo[$k];
            $RouteType_CI[] = $RouteType[$k];
            //echo "<br>RouteStore=".$RouteNo[$k];
            $ArrivalTime_CI[] = $ArrivalTime[$k];
            $TransporterI_CI[] = $TransporterI[$k];
            //}
        }

        if ($j > $i) {
            $i = $j - 1;
        }
    } //##### EXCEL VEHICLE LOOP CLOSED
    
    ######## CLOSE CASSANDRA CONNECTION	
    closeCassandraConnection($o_cassandra);
     
//exit(0);
    //##### DEBUG MSG
    if(!$DEBUG_OFFLINE && !$DEBUG_ONLINE && $LOG) {
        write_log($title,$difftime);
    }
 

   ######## CASSANDRA BLOCK3 CLOSED	
                #    
    //######## COLOR BLANK ENTRIES
    for ($i = 0; $i < sizeof($Vehicle); $i++) {
        $j = $i;
        while ($Vehicle[$j] == $Vehicle[$i]) {
            $j++;   //J LIMIT
        }

        for ($k = $i; $k < $j; $k++) {
            $row2 = $k + 2;
            $pos_c = strpos($StationNo[$k], "@");
            if ($pos_c !== false) {
                //echo "\nNegative Found";
                $customer_at_the_rate1 = explode("@", $StationNo[$k]);
            } else {
                $customer_at_the_rate1[0] = $StationNo[$k];
            }
            //else if($VisitStatus[trim($Vehicle)][trim($customer_at_the_rate1[0])]==1)

            if (($VisitStatus[trim($RouteNo[$k])][trim($customer_at_the_rate1[0])] == 1) && ($ArrivalTime[$k] == "")) {
                //echo "\nIn UpdateColor";
                cellColor('A' . $row2 . ':AN' . $row2, 'FFD2E8');
            }
        }

        if ($j > $i) {
            $i = $j - 1;
        }
    }
    //####### END COLOR- BLANK ENTRIES	
    //######### CALL SORT ROUTES FUNCTION
    sort_all_routes();

    //######## CI LOGIC
    /* $Vehicle_CI = $Vehicle;
      $StationNo_CI = $StationNo;
      $RouteNo_CI = $RouteNo;
      $ArrivalTime_CI = $ArrivalTime; */

    //######## AFTER ALL ROUTES SORTING
    //echo "<br>Size:RouteNo_CI=".sizeof($RouteNo_CI);

    $t = 0;
    $size = 0;
    $match = false;
    for ($i = 0; $i < sizeof($RouteNo_CI); $i++) {
        $j = $i;

        //echo "<br>MAIN::i=".$i." ,j=".$j." ,RouteNo_CI[$i]=".$RouteNo_CI[$i];		
        while ($RouteNo_CI[$j] == $RouteNo_CI[$i]) {
            //echo "<br>BEFORE:".$t;
            //echo "<br>ROUTE::i=".$i." ,j=".$j." ,VehicleCI[$j]=".$Vehicle_CI[$j]." ,RouteNo_CI[$j]=".$RouteNo_CI[$j]." ,RouteNo_CI[$i]=".$RouteNo_CI[$i];;
            if ($ArrivalTime_CI[$j] != "") {
                //echo "<br>Arrival Found";
                $RouteFill[$RouteNo_CI[$j]][] = $StationNo_CI[$j];
            }
            //####### GET THE UNIQUE TOTAL VEHICLE TOTAL
            if (sizeof($VehicleTotal[$RouteNo_CI[$j]]) == 0) {
                //echo "<br>IF IN:i=".$i." ,j=".$j;
                $VehicleTotal[$RouteNo_CI[$j]][] = $Vehicle_CI[$j];
                $t++;
            } else {
                $size = sizeof($VehicleTotal[$RouteNo_CI[$j]]);
                //echo "<br>SizeOfVehicleTotal=".sizeof($VehicleTotal[$RouteNo_CI[$j]]);				
                $match = false;
                for ($k = 0; $k < $size; $k++) {
                    //echo "<br>Vehicle_CI[j]=".$Vehicle_CI[$j]." ,VehicleTotal=".$VehicleTotal[$RouteNo_CI[$j]][$k];
                    if (($Vehicle_CI[$j]) == ($VehicleTotal[$RouteNo_CI[$j]][$k])) {
                        //echo "<br>Add-Vehicle";
                        $match = true;
                        break;
                    }
                }
                if (!$match) {
                    //echo "<br>Vehicle_CI MatchFalse=".$Vehicle_CI[$j];
                    $VehicleTotal[$RouteNo_CI[$j]][] = $Vehicle_CI[$j];
                }
                //####### GET THE UNIQUE TOTAL CUSTOMER_TOTAL
                //echo "<br>Else:i=".$i." ,j=".$j." ,SizeOfCustomerTotal=".sizeof($CustomerTotal[$RouteNo_CI[$j]]);
            }

            $t = 0;
            $size = 0;
            if (sizeof($CustomerTotal[$RouteNo_CI[$j]]) == 0) {
                $CustomerTotal[$RouteNo_CI[$j]][] = $StationNo_CI[$j];
                $t++;
            } else {
                $size = sizeof($CustomerTotal[$RouteNo_CI[$j]]);
                $match = false;
                for ($k = 0; $k < $size; $k++) {
                    //echo "<br>StationNo_CI[$j]=".$StationNo_CI[$j]." ,CustomerTotal[$RouteNo_CI[$j]][$k]=".$CustomerTotal[$RouteNo_CI[$j]][$k];
                    if (trim($StationNo_CI[$j]) == trim($CustomerTotal[$RouteNo_CI[$j]][$k])) {
                        $match = true;
                        break;
                        //echo "<br>Add-Customer:".$t;
                        //$t++;
                    }
                }
                if (!$match) {
                    $CustomerTotal[$RouteNo_CI[$j]][] = $StationNo_CI[$j];
                }
            }
            $j++; //J LIMIT
            //echo "<br>AFTER-1:".$t;			
        }

        //####### GET THE ROUTE TOTAL				
        $RouteTotal[] = $RouteNo_CI[$i];
        $RouteTypeTotal[$RouteNo_CI[$i]] = $RouteType_CI[$i];
        $TransporterITotal[$RouteNo_CI[$i]] = $TransporterI_CI[$i];
        //echo "<br>RouteType=".$RouteTypeTotal[$RouteNo_CI[$i]]." ,TPT=".$TransporterITotal[$RouteNo_CI[$i]];

        if ($j > $i) {
            $i = $j - 1;
        }
        //echo "<br>AFTER-2:".$t;		
    }

    //echo "<br>T=".$t;
    //###### WRITE TO EXCEL : COMPLETED/INCOMPLETED CUSTOMERS
    global $sheet1_row;
    global $sheet2_row;
    //echo "<br>SizeRouteTotal:Final=".sizeof($RouteTotal);
    for ($i = 0; $i < sizeof($RouteTotal); $i++) {
        $vehicle_str = "";
        $remark_str = "";
        $customer_completed_str = "";
        $customer_incompleted_str = "";

        $size_customer = 0;
        $size_customer = sizeof($CustomerTotal[$RouteTotal[$i]]);
        //echo "<br>SizeCustomerTotal:Final=".$size_customer;
        for ($j = 0; $j < $size_customer; $j++) {
            $complete = false;
            $size_route_fill = 0;
            $size_route_fill = sizeof($RouteFill[$RouteTotal[$i]]);
            //echo "<br>Size:RouteFill=".$size;

            $cust_total = "";
            if (strpos($CustomerTotal[$RouteTotal[$i]][$j], '@') !== false) {
                $cust_total_tmp = explode('@', $CustomerTotal[$RouteTotal[$i]][$j]);
                //$cust_total = $cust_total_tmp[1];
                $cust_total = $cust_total_tmp[0];
            } else {
                $cust_total = $CustomerTotal[$RouteTotal[$i]][$j];
            }

            for ($k = 0; $k < $size_route_fill; $k++) { //k=customer
                $cust_arrived = "";
                //if(strpos($CustomerTotal[$RouteTotal[$i]][$j],'@') !== false)
                if (strpos($RouteFill[$RouteTotal[$i]][$k], '@') !== false) {
                    $cust_arrived_tmp = explode('@', $RouteFill[$RouteTotal[$i]][$k]);
                    //$cust_arrived = $cust_arrived_tmp[1];
                    $cust_arrived = $cust_arrived_tmp[0];
                } else {
                    $cust_arrived = $RouteFill[$RouteTotal[$i]][$k];
                }
                //echo "<br>Customer1=".$cust_arrived." ,Customer2=".$cust_total;
                if (trim($cust_arrived) == trim($cust_total)) { //MATCH CUSTOMER
                    //echo "<br>Completed:".$cust_total;
                    $customer_completed_str.= $cust_total . ",";
                    $complete = true;
                    break;
                }
            }
            if (!$complete) {
                //echo "<br>InCompleted:".$cust_total;
                $customer_incompleted_str.= $cust_total . ",";
            }
        }

        $size_vehicle = 0;
        $size_vehicle = sizeof($VehicleTotal[$RouteTotal[$i]]);

        $no_remark = true;
        for ($j = 0; $j < $size_vehicle; $j++) {
            $vehicle_str.= $VehicleTotal[$RouteTotal[$i]][$j] . "/";

            //### GET SELECTED REMARK
            $remark_final = "-";
            for ($v = 0; $v < sizeof($Remark); $v++) {
                if ((trim($Vehicle[$v]) == trim($VehicleTotal[$RouteTotal[$i]][$j])) && ($Remark[$v] != "")) {
                    $remark_final = $Remark[$v];
                    $no_remark = false;
                    break;
                }
            }
            $remark_str.= $remark_final . "/";
        }

        if ($no_remark) {
            $remark_str = "";
        }
        //echo "<br>vehicle_str=".$vehicle_str;
        //echo "<br>customer_completed_str=".$customer_completed_str;
        //echo "<br>customer_incompleted_str=".$customer_incompleted_str;
        if ($vehicle_str != "") {
            $vehicle_str = substr($vehicle_str, 0, -1);
        }
        if ($remark_str != "") {
            $remark_str = substr($remark_str, 0, -1);
        }
        if ($customer_completed_str != "") {
            $customer_completed_str = substr($customer_completed_str, 0, -1);
        }
        if ($customer_incompleted_str != "") {
            $customer_incompleted_str = substr($customer_incompleted_str, 0, -1);
        }
        //######## FILL COMPLETED /INCOMPLETED SHEET
        $customer_completed_str = implode(',', array_unique(explode(',', $customer_completed_str)));
        $customer_incompleted_str = implode(',', array_unique(explode(',', $customer_incompleted_str)));

        $row1 = 2;
        $row2 = 2;
        //######### FILL SHEET2
        if (($customer_completed_str != "") && ($customer_incompleted_str == "")) {
            //echo "<br>ONE";
            $col_tmp = 'A' . $sheet1_row;
            $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp, $vehicle_str);
            $col_tmp = 'B' . $sheet1_row;
            $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp, $RouteTotal[$i]);
            $col_tmp = 'C' . $sheet1_row;
            $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp, $customer_completed_str);
            $col_tmp = 'D' . $sheet1_row;
            $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp, $TransporterITotal[$RouteTotal[$i]]);
            $col_tmp = 'E' . $sheet1_row;
            $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp, $RouteTypeTotal[$RouteTotal[$i]]);
            $sheet1_row++;
        }
        //########## FILL SHEET3
        if ($customer_incompleted_str != "") {
            //echo "<br>TWO";
            $col_tmp = 'A' . $sheet2_row;
            $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, $vehicle_str);
            $col_tmp = 'B' . $sheet2_row;
            $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, $RouteTotal[$i]);
            $col_tmp = 'C' . $sheet2_row;
            $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, $customer_completed_str);
            $col_tmp = 'D' . $sheet2_row;
            $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, $customer_incompleted_str);

            $RouteTotal[$i] = trim($RouteTotal[$i]);
            //echo "\nBeforeUC=".$unmapped_customers[$RouteTotal[$i]];
            $unmapped_customers_1 = substr($unmapped_customers[$RouteTotal[$i]], 0, -1);
            //echo "\nRouteTotal[i]=".$RouteTotal[$i]." ,unmapped_cust=".$unmapped_customers_1;
            if ($unmapped_customers_1) {
                $col_tmp = 'E' . $sheet2_row;
                $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, $unmapped_customers_1);
            }

            $col_tmp = 'F' . $sheet2_row;
            $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, $TransporterITotal[$RouteTotal[$i]]);
            $col_tmp = 'G' . $sheet2_row;
            $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, $RouteTypeTotal[$RouteTotal[$i]]);
            $col_tmp = 'H' . $sheet2_row;
            $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, $remark_str);
            $sheet2_row++;
        }
    }
    //####### WRITE TO EXCEL :COMPLETED/INCOMPLETED CLOSED ##############
    //############# UPDATE NOT FOUND RED-ROUTE
    global $RedRoute;
    global $RedCustomer;
    $styleFontRed = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'FF0000'), //RED
            'size' => 10
        //'name'  => 'Verdana'
    ));

    $sheet2_row++;
    //####### ADD THE NOT ADDED ROUTE FROM FIRST SHEET
    $customer_str = "";
    //echo "\nSizeRedRoute=".sizeof($RedRoute);
    for ($m = 0; $m < sizeof($RedRoute); $m++) {
        //echo "<br>RED_ROUTE=".$RedRoute[$m];
        $col_tmp = 'B' . $sheet2_row;
        $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, $RedRoute[$m]);
        $objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($styleFontRed);
        $col_tmp = 'D' . $sheet2_row;
        $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp, $RedCustomer[$m]);
        $objPHPExcel_1->getActiveSheet(2)->getStyle($col_tmp)->applyFromArray($styleFontRed);
        $sheet2_row++;
    }
    //echo "\nCSV_STRING_HALT=".$csv_string_halt;
    //####### UPDATE EXTRA SHEET	
    //echo date('H:i:s') , " Write to Excel2007 format" , EOL;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
    $objWriter->save($read_excel_path);
    echo date('H:i:s'), " File written to ", $read_excel_path, EOL;

    echo "\n!! Process-Completed !!";
}

//######## UPDATE VEHICLE
function update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle, $k, $StationNo, $Lat, $Lng, $ScheduleTime, $DistVar, $Remark, $startdate, $enddate, $lat_ref1, $lng_ref1, $lat_cr, $lng_cr, $arrivale_time, $depature_time, $RouteNo, $hrs_min, $Type, $plant_status, $status_entered, $p_in, $plant_indatetime, $p_out, $plant_outdatetime) {
    //echo "\nPlantStatus=".$plant_status;
    //echo "\nUPDATE VEHICLE::".$read_excel_path.", ".$Vehicle.", ".$k.", ".$StationNo.", ".$Lat.", ".$Lng.", ".$ScheduleTime.", ".$DistVar.", ".$Remark.", ".$startdate.", ".$enddate.", ".$status_entered;	
    global $ScheduleDate;
    global $DEBUG_OFFLINE;
    global $unchanged;
    global $ArrivalDate;
    global $DepartureDate;
    global $ArrivalTime;
    global $DepartureTime;
    global $VisitStatus;

    global $transporter_m;
    global $vehicle_m;

    //#######################	
    $place = "-";
    $station_no = "-";
    //$transporter_name = "-";
    $schedule_time = "-";
    $delay = "-";
    $entered_station = 0;

    $lat_g = trim($Lat);
    $lng_g = trim($Lng);

    if ($DistVar == 0) {
        $DistVar = 0.1;
    }

    $distance_station = 0;
    if (($lat_g != "") && ($lng_g != "") && ($StationNo != "")) {
        //echo "\nDIST::datetime=".$datetime." ,op_date1=".$op_date1." ,op_date2=".$op_date2." \ndistance=".$distance;
        /* if($StationNo=="71762")
          {
          echo "\nlat_ref1=".$lat_ref1.",lng_ref1=".$lng_ref1
          } */

        /* if($DEBUG_OFFLINE)
          {
          //$distance_station1=calculate_distance($lat_ref1, $lat_g, $lng_ref1, $lng_g);
          //$distance_station2=calculate_distance($lat_cr, $lat_g, $lng_cr, $lng_g);
          }
          else
          { */
        calculate_distance($lat_ref1, $lat_g, $lng_ref1, $lng_g, $distance_station1);
        calculate_distance($lat_cr, $lat_g, $lng_cr, $lng_g, $distance_station2);
        //}


        if ($distance_station1 < $distance_station2) {
            $distance_station = $distance_station1;
        } else {
            $distance_station = $distance_station2;
        }
        //echo "\nVehicle entered in station=CustomerNo=".$StationNo." ,arrivale_time=".$arrivale_time." ,lat_ref1=".$lat_ref1." ,lng_ref1=".$lng_ref1." ,lat_cr=".$lat_cr." ,lng_cr=".$lng_cr."dist=".$distance_station.", distvar=".$DistVar;  
        //echo "\n1=CustomerNo=".$StationNo." ,arrivale_time=".$arrivale_time." ,lat_ref1=".$lat_ref1." ,lng_ref1=".$lng_ref1." ,lat_cr=".$lat_cr." ,lng_cr=".$lng_cr."dist=".$distance_station.", distvar=".$DistVar;  
        /* if($status_entered==2)
          {
          if($StationNo=="70803")
          {
          echo "\nlat_ref1=".$lat_ref1.",lng_ref1=".$lng_ref1
          echo "\nlat_cr=".$lat_cr.",lng_cr=".$lng_ref1;;
          echo "\ndistance_station=".$distance_station.", distance_variable=".$DistVar;
          }
          } */

        if ($distance_station < $DistVar) {
            $station_no = $StationNo;
            //echo "\nAtStation:".$station_no." ,distance_station=".$distance_station;
            //$customer_visited[] = $station_no;
            //$customer_type[] = $Type[$i];
            $entered_station = 1;
            //break;
        }
    }

    //##########################################			
    $row = $k + 2;

    $report_time1 = explode(' ', $startdate);
    $report_time2 = explode(' ', $enddate);

    //########## UPDATE LAST HALT TIME 
    $last_halt_sec_global = 0;

    $pos_c = strpos($station_no, "@");
    if ($pos_c !== false) {
        //echo "\nNegative Found";
        $customer_at_the_rate1 = explode("@", $station_no);
    } else {
        $customer_at_the_rate1[0] = $station_no;
    }

    //echo "<br>CustomerWrite=".$customer_at_the_rate1[0];

    $valid_halt = false;
    $route_no = "";

    //$schedule_in_time_tmp = $ScheduleTime;							
    //echo "\nSchedule Matched";												  
    //$start_date_tmp = explode(" ",$startdate);
    //$end_date_tmp = explode(" ",$enddate);	
    //$schedule_in_time = $schedule_in_time_tmp;
    //$tmp_time_input = $start_date_tmp[0]." ".$start_date_tmp[1];
    //$tmp_time_schedule = $start_date_tmp[0]." ".$schedule_in_time;
    //$tmp_input = strtotime($tmp_time_input);
    //$tmp_schedule = strtotime($tmp_time_schedule);

    $final_date = "";
    /* if($tmp_input <= $tmp_schedule)
      {
      $final_date = $start_date_tmp[0];
      }
      else
      {
      $final_date = $end_date_tmp[0];
      } */
    /*
      $cdate = date('Y-m-d');
      $pdate = date('Y-m-d', strtotime($date .' -1 day'));
      //$cdate = '2014-08-28';
      //$pdate = '2014-08-27';
      $cdatetime1 = strtotime(date('00:00:00'));
      $cdatetime2 = strtotime(date($ScheduleTime));
      $cdatetime3 = strtotime(date('H:i:s'));
      $difftime = $cdatetime2 - $cdatetime1;
      $difftime_c = $cdatetime3 - $cdatetime1;

      if( ($difftime > 43200) && ($difftime_c > 43200) )		//## > 12 PM current day, else prev day
      {
      $final_date = $cdate;
      }
      else
      {
      $final_date = $pdate;
      } */

    //echo "\nScheduleInTime=".$schedule_in_time;	
    //$in_time_str_excel = $final_date." ".$ScheduleTime;
    $in_time_str_excel = $ScheduleDate[$k] . " " . $ScheduleTime;
    $in_time_str = $arrivale_time;

    //echo "\nArrivalTime=".$arrivale_time." ,ScheduleDateTime=".$in_time_str_excel." ,ScheduleTime=".$ScheduleTime;
	
    if ($ScheduleTime != "") {
        $time1 = strtotime($in_time_str) - strtotime($in_time_str_excel);
	
        //echo "\nin_time_str=".$in_time_str." ,in_time_str_excel=".$in_time_str_excel." ,DiffTime=".$time1;

        /* if($time1>0)
          {
          //$hms_3 = secondsToTime($time1);
          //$time_delay = $hms_3[h].":".$hms_3[m].":".$hms_3[s];
          $time_delay = $time1/60;
          }
          else
          {
          //$time1 = abs($time1);
          //$hms_4 = secondsToTime($time1);
          //$time_delay = $hms_4[h].":".$hms_4[m].":".$hms_4[s];
          //$time_delay = "-".$time_delay;
          $time_delay = "-".$time1/60;
          } */
	//echo "\nTime1A=".$time1;
        $time_delay = $time1 / 60;
	//echo "\nTime1B=".$time_delay;
    }

    /* if($schedule_in_time=="")
      {
      $schedule_in_time = "-";
      } */
    if ($time_delay == "") {
        $time_delay = "-";
    }
    if ($route_no == "") {
        $route_no = "-";
    }
    if ($hrs_min == "") {
        $hrs_min = "-";
    }
    ///####################### GET SHEDULE TIME AND DELAY CLOSED #############################	
    if ($Type == "Plant") {
        $schedule_in_time = "-";
    }
    //$cum_dist = $cum_dist + $distance;	
    if ($vname != $prev_vehicle) {
        $cum_dist = 0;
    }

    //######## FINAL UPDATION																						
    $arrival_time1 = explode(' ', $arrivale_time);
    $depature_time1 = explode(' ', $depature_time);
    //echo "\nArrivalTime[$i]=".$ArrivalTime[$i];

    if (($status_entered == 1) && ($entered_station == 1)) {//###### CHECK FOR ALL (ARRIVAL AND DEPARTURE)
        //echo "\nEnteredStation";
        //##UPDATE ARRIVAL																																			
        $hour_band = explode(':', $arrival_time1[1]);
        $col_tmp = 'H' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $hour_band[0]);

        $col_tmp = 'I' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $arrival_time1[0]);

        $col_tmp = 'J' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $arrival_time1[1]);

        $col_tmp = 'O' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, round($time_delay, 2));

        //$col_tmp = 'M'.$row;
        //$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $hrs_min);			

        $col_tmp = 'AJ' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $report_time1[0]);

        $col_tmp = 'AK' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $report_time1[1]);

        $col_tmp = 'AL' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $report_time2[0]);

        $col_tmp = 'AM' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $report_time2[1]);

        //####### FILL COLOR IF CUSTOMER REVISITED
        //if($VisitStatus[trim($Vehicle)][trim($customer_at_the_rate1[0])] =="")
        if ($VisitStatus[trim($RouteNo)][trim($customer_at_the_rate1[0])] == "") {
            $col_tmp = 'AN' . $row;
            $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, "1");
            $VisitStatus[trim($RouteNo)][trim($customer_at_the_rate1[0])] = "1";
        }
        //else if($VisitStatus[trim($Vehicle)][trim($customer_at_the_rate1[0])]==1)
        else if ($VisitStatus[trim($RouteNo)][trim($customer_at_the_rate1[0])] == 1) {
            cellColor('A' . $row . ':AN' . $row, 'FFFF99');
        }
        //########################################
        $ArrivalDate[$k] = $arrival_time1[0];
        $ArrivalTime[$k] = $arrival_time1[1];
        $unchanged = false;
        //echo "\nEntered=".$plant_status;
        //###############																						
    }
    if (($status_entered == 2) && ($entered_station == 0)) {//####### CHECK FOR DEPARTURE
        //echo "\nDepartureWrite";
        //##UPDATE DEPARTURE
        $col_tmp = 'K' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $depature_time1[0]);

        $col_tmp = 'L' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $depature_time1[1]);

        $col_tmp = 'P' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $hrs_min);

        $col_tmp = 'AJ' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $report_time1[0]);

        $col_tmp = 'AK' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $report_time1[1]);

        $col_tmp = 'AL' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $report_time2[0]);

        $col_tmp = 'AM' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $report_time2[1]);

        $DepartureDate[$k] = $depature_time1[0];
        $DepartureTime[$k] = $depature_time1[1];

        $unchanged = false;
        //echo "\nIF ARRIVAL NOT NULL";												
    }

    $col_tmp = 'AI' . $row;
    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $plant_status);

    /* //### PLANT IN,OUT TIME	
      if($p_in)
      {
      //echo "<br>PLANT_IN_TIME_UPDATE";
      $plant_in_time = explode(' ',$plant_indatetime);
      $col_tmp = 'AF'.$row;
      $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $plant_in_time[0]);

      $col_tmp = 'AG'.$row;
      $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $plant_in_time[1]);
      }
      else if($p_out)
      {
      $plant_out_time = explode(' ',$plant_outdatetime);
      $col_tmp = 'AH'.$row;
      $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $plant_out_time[0]);

      $col_tmp = 'AI'.$row;
      $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $plant_out_time[1]);
      }
      //########## PLANT IN OUT TIME CLOSED */

    $prev_vehicle = $vname;
    //echo "\nSerial=".$sno;
    $sno++;
    //echo "\nHALT COMPLETED";
    $entered_station = 0;
    //break;                            											
    //$entered_station = 0;                            							
}

////### UPDATE HALT CLOSED

function update_plant($k, $objPHPExcel_1, $read_excel_path, $p_in, $plant_indatetime, $p_out, $plant_outdatetime, $startdate, $enddate) {
    global $PlantOutScheduleDate;
    global $PlantOutScheduleTime;
    global $PlantOutDelay;

    //### PLANT IN,OUT TIME
    global $PlantInTime;
    global $PlantOutTime;
    $time_delay = "-";

    $row = $k + 2;
    //echo "\nPlantSchedule1=".$PlantOutScheduleTime[$k]." ,k=".$k." ,row=".$row;

    if (($p_in) && ($PlantInTime[$k] == "")) {
        //echo "\nPLANT_IN";
        $plant_in_time = explode(' ', $plant_indatetime);
        $col_tmp = 'R' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $plant_in_time[0]);

        $col_tmp = 'S' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $plant_in_time[1]);
        $PlantInTime[$k] = $plant_in_time[1];
    }
    if (($p_out) && ($PlantOutTime[$k] == "")) {
        //echo "\nPlantOut";
        $plant_out_time = explode(' ', $plant_outdatetime);
        $col_tmp = 'T' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $plant_out_time[0]);

        $col_tmp = 'U' . $row;
        $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $plant_out_time[1]);

        $PlantOutTime[$k] = $plant_out_time[1];
        //########## PLANT IN OUT TIME CLOSED		

        /* $final_date = "";		
          //echo "\nScheduleInTime=".$schedule_in_time;
          $out_time_str_excel = $final_date." ".$PlantOutScheduleTime[$k]; */
        $out_time_str_excel = $PlantOutScheduleDate[$k] . " " . $PlantOutScheduleTime[$k];
        $out_time_str = $plant_outdatetime;

        //echo "\nPlantSchedule2=".$PlantOutScheduleTime[$k]." ,k=".$k." ,row=".$row;
        if ($PlantOutScheduleTime[$k] != "") {
            $time1 = strtotime($out_time_str) - strtotime($out_time_str_excel);

            $time_delay = $time1 / 60;

            $col_tmp = 'X' . $row;
            $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, round($time_delay, 2));
            $PlantOutDelay[$k] = $time_delay;
        }
        //### PLANT DELAY CLOSED		
    }
}

/*
  function update_extra_sheets($objPHPExcel_1,$i,$Vehicle,$RouteNo,$customer_visited_unique,$customer_unvisited_unique, $TransporterI, $RouteType)
  {
  //echo "\nVehicle=".$Vehicle." ,Route=".$RouteNo." ,sizeCV=".sizeof($customer_visited)." ,sizeCU=".sizeof($customer_unvisited);
  global $sheet1_row;
  global $sheet2_row;

  //echo "\nInUpdateExtraSheet";
  $row1=2;
  $row2=2;

  $all_completed = false;
  $valid_match = false;
  $customer_all_str = "";
  $customer_visited_str = "";
  $customer_unvisited_str = "";
  $vehicle_name_rdb1 = $vehicle_excel[$j];
  $route_name_rdb1 = $route_excel[$j];

  foreach($customer_visited_unique as $array_key1 => $array_value_1)		//##### TOTAL CUSTOMER VISITED
  {
  $customer_visited_str = $customer_visited_str.$array_value_1.",";
  }
  if($customer_visited_str!="") { $customer_visited_str = substr($customer_visited_str, 0, -1);}

  foreach($customer_unvisited_unique as $array_key2 => $array_value_2)		//##### TOTAL CUSTOMER VISITED
  {
  $customer_unvisited_str = $customer_unvisited_str.$array_value_2.",";
  }
  if($customer_unvisited_str!="") { $customer_unvisited_str = substr($customer_unvisited_str, 0, -1);}

  //####### UPDATE SHEET2 : ALL VISITED
  //echo "\ncustomer_visited_str=".$customer_visited_str." ,customer_unvisited_str=".$customer_unvisited_str;
  if((sizeof($customer_visited_unique)>0) && (sizeof($customer_unvisited_unique)==0))
  {
  //echo "\nValidSheet2";
  //######### FILL SHEET2
  $col_tmp = 'A'.$sheet1_row;
  $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , $Vehicle);
  $col_tmp = 'B'.$sheet1_row;
  $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , $RouteNo);
  $col_tmp = 'C'.$sheet1_row;
  $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , $customer_visited_str);
  $col_tmp = 'D'.$sheet1_row;
  $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , $TransporterI);
  $col_tmp = 'E'.$sheet1_row;
  $objPHPExcel_1->setActiveSheetIndex(1)->setCellValue($col_tmp , $RouteType);
  $sheet1_row++;
  }
  //####### UPDATE SHEET3 : PARTIAL VISITED
  if( (sizeof($customer_unvisited_unique)>0) && ($customer_unvisited_str!=""))
  {
  //echo "\nValidSheet3";
  //######### FILL SHEET3
  if($customer_completed!="") { $customer_completed = substr($customer_completed, 0, -1);}
  if($customer_incompleted!="") { $customer_incompleted = substr($customer_incompleted, 0, -1);}

  $col_tmp = 'A'.$sheet2_row;
  $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $Vehicle);
  $col_tmp = 'B'.$sheet2_row;
  $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $RouteNo);
  $col_tmp = 'C'.$sheet2_row;
  $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $customer_visited_str);
  $col_tmp = 'D'.$sheet2_row;
  $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $customer_unvisited_str);
  $col_tmp = 'E'.$sheet2_row;
  $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $TransporterI);
  $col_tmp = 'F'.$sheet2_row;
  $objPHPExcel_1->setActiveSheetIndex(2)->setCellValue($col_tmp , $RouteType);
  $sheet2_row++;
  }
  //#### EXTRA TAB CLOSED ###########
  } */

function update_nogps($objPHPExcel_1, $msg, $i) {
    //echo "\nInUpdateRemark";
    $row = $i + 2;
    $col_tmp = 'AB' . $row;

    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $msg);
}

function update_remark($objPHPExcel_1, $msg, $i) {
    //echo "\nInUpdateRemark";
    $row = $i + 2;
    $col_tmp = 'Q' . $row;

    $halt_in_cell = 'I' . $row;
    $halt_in = $objPHPExcel_1->getActiveSheet(0)->getCell($halt_in_cell)->getValue();

    if ($halt_in != "") {
        $msg = "";
    }

    $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $msg);
}

//######### SORT WITH RESPECT TO ROUTES ###########################
function sort_all_routes() {
    global $Vehicle_CI;
    global $StationNo_CI;
    global $RouteNo_CI;
    global $RouteType_CI;
    global $ArrivalTime_CI;
    global $TransporterI_CI;

    /* for($x = 0; $x < sizeof($RouteNo_CI); $x++) 
      {
      echo "<br>Vehicle:BEFORE_SORT=".$Vehicle_CI[$x];
      } */
    for ($x = 1; $x < sizeof($RouteNo_CI); $x++) {
        $tmp_vehicle_ci = $Vehicle_CI[$x];
        $tmp_station_ci = trim($StationNo_CI[$x]);
        $tmp_route_ci = $RouteNo_CI[$x];
        $tmp_routetype_ci = $RouteType_CI[$x];
        $tmp_arrival_ci = $ArrivalTime_CI[$x];
        $tmp_transporter_ci = $TransporterI_CI[$x];
        ///////////      				

        $z = $x - 1;
        $done = false;
        while ($done == false) {
            $route_tmp1 = $RouteNo_CI[$z];
            //echo "<br>RouteTmp1=".$route_tmp1." ,tmp_route_ci=".$tmp_route_ci;
            if (trim($route_tmp1) > trim($tmp_route_ci)) {
                $Vehicle_CI[$z + 1] = $Vehicle_CI[$z];
                $StationNo_CI[$z + 1] = trim($StationNo_CI[$z]);
                $RouteNo_CI[$z + 1] = trim($RouteNo_CI[$z]);
                $RouteType_CI[$z + 1] = $RouteType_CI[$z];
                $ArrivalTime_CI[$z + 1] = $ArrivalTime_CI[$z];
                $TransporterI_CI[$z + 1] = $TransporterI_CI[$z];
                //////////////////
                $z = $z - 1;
                if ($z < 0) {
                    $done = true;
                }
            } else {
                $done = true;
            }
        } //WHILE CLOSED

        $Vehicle_CI[$z + 1] = $tmp_vehicle_ci;
        $StationNo_CI[$z + 1] = $tmp_station_ci;
        $RouteNo_CI[$z + 1] = $tmp_route_ci;
        $RouteType_CI[$z + 1] = $tmp_routetype_ci;
        $ArrivalTime_CI[$z + 1] = $tmp_arrival_ci;
        $TransporterI_CI[$z + 1] = $tmp_transporter_ci;
    }
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
