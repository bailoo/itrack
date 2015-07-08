<?php

//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
$sheet1_row = 2;
$sheet2_row = 2;

$sts_date_sel = array();
$xml_date_sel = array();
$lat_sel = array();
$lng_sel = array();
$speed_sel = array();
$door_open1_arr = array();
$door_open2_arr = array();
//$door_open3_arr = array(array());

$Lat_Final = "";
$Lng_Final = "";
$Customer_Final = "";
$DistVar_Final = "";
$CLat_Final = "";
$CLng_Final = "";
$CPlantNo_Final = "";
$CDistVar_Final = "";
$style_green = array(
'fill' => array(
'type' => PHPExcel_Style_Fill::FILL_SOLID,
 'color' => array('argb' => 'f4fef4')            //green
//'text' => array('argb' => 'FFFC64')
)
);

function get_halt_xml_data($startdate_tmp, $enddate, $read_excel_path) {
//echo "\nEnddate	=".$enddate." ,time1_ev=".$time1_ev." ,time2_ev=".$time2_ev;	
global $abspath;
echo "\nSD=" . $startdate_tmp . " ,ED=" . $enddate . " ,Time1=" . $time1_ev;

global $SNo;      //SENT FILE
global $LRNo;
global $Vehicle;
global $FAT_kg;
global $SNF_kg;
global $QTY;
global $DriverName;
global $TranspoterName;
global $Initial_MilkAge;
global $Chilling_Plant;
//global $Chilling_PlantName;
global $Target_Plant;   //$StationNo
//global $Target_PlantName;
global $Manual_DisptachTime;
global $Plant_OutDate;    //$DepartureDate
global $Plant_OutTime;    //$DepartureTime	
global $Plant_InDate;    //$ArrivalDate
global $Plant_InTime;    //$ArrivalTime
global $GPRS_DispatchTime;
global $Manual_CloseTime;
global $GPRS_CloseTime;
global $Est_UnloadTime;
global $Diff_inCloseTime;
global $Plant_HaltTime;    //$HaltDuration
global $Server_CloseTime;
global $Transportation_Age;
global $Final_MilkAge;
global $Target_ArrivalTime;   //$ScheduleDateTime
global $Delay_InArrival;    //$Delay
global $IMEI;
global $Lat;
global $Lng;
global $DistVar;
global $C_Lat;
global $C_Lng;
global $C_DistVar;
global $Status;

global $vehicle_no;
global $create_date;

global $customer_no_total;    //##### PLANTS TOTAL
global $station_coord_total;
global $distance_variable_total;

global $transporter_id;
global $chilling_plant_no;    //##### CHILLING PLANT TOTAL
global $chilling_plant_coord_total;
global $chilling_plant_distvar_total;

//#################
global $Lat_Final;
global $Lng_Final;
global $Customer_Final;
global $DistVar_Final;  //### PLANT 
global $CLat_Final;
global $CLng_Final;
global $CPlantNo_Final;
global $CDistVar_Final;  //### CPLANT
//#######################
global $door1_bin;
global $door2_bin;
global $door1_open_flag;
global $door1_close_flag;
global $door2_open_flag;
global $door2_close_flag;
global $d1;
global $d2;
global $DBSNO;
//#######################

global $objPHPExcel_1;

$current_halt_time = 0;
$objPHPExcel_1 = null;
//$objPHPExcel_1 = new PHPExcel();
//###### RELOAD SHEET
$objPHPExcel_1 = null;
$objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);

//####### RECREATE EXTRA SHEETS #####################
//###################################################	
$header_font = array(
'font' => array(
'bold' => true,
 'color' => array('rgb' => '000000'), //RED
'size' => 10
//'name'  => 'Verdana'
));

$date_tmp1 = explode(" ", $startdate_tmp);
$date_tmp2 = explode(" ", $enddate);
$report_date1 = $date_tmp1[0];
$report_time1 = $date_tmp1[1];
$report_date2 = $date_tmp2[0];
$report_time2 = $date_tmp2[1];
    
//echo "\nSD=".$startdate." ,ED=".$enddate." ,read_excel_path=".$read_excel_path." ,VehicleSize=".sizeof($Vehicle);
echo "\nSizeVehicle=" . sizeof($Vehicle);
//###### CASSANDRA BLOCK1 ###########
global $o_cassandra;
global $sts_date_sel_arr;
global $xml_date_sel_arr;
global $lat_sel_arr;
global $lng_sel_arr;
global $speed_sel_arr;
global $door_open1_arr;
global $door_open2_arr;
//global $door_open3_arr;

$date1 = $startdate_tmp;
$date2 = $enddate;
$datefrom = $report_date1;
$dateto = $report_date2;

$userInterval = 0;

$sortBy = 'g';
$firstDataFlag = 0;
$endDateTS = strtotime($date2);
$dataCnt = 0;
//$userInterval = "0";
$requiredData = "All";

$parameterizeData = new parameterizeData();
$ioFoundFlag = 0;

$parameterizeData = new parameterizeData();
$parameterizeData->latitude = "d";
$parameterizeData->longitude = "e";
//###### CASSANDRA BLOCK1 CLOSED    

for ($i = 0;$i < sizeof($Vehicle);$i++) {

    $sts_date_sel = array();
    $xml_date_sel = array();
    $lat_sel = array();
    $lng_sel = array();
    $speed_sel = array();
    $door_open1_arr = array();
    $door_open2_arr = array();

    //###### CASSANDRA BLOCK2 OPEN #########
    //echo "\nReadSno:" . $i . " ,imei2=" . $IMEI[$i] . " ,datefrom=" . $datefrom . " ,dateto=" . $dateto;

    $ioFoundFlag = 0;
    $ioArrSize = sizeof($ioArr);

    $io_door1 = get_io($IMEI[$i], 'door_open');
    $io_door2 = get_io($IMEI[$i], 'door_open2');
    //$io_door3 = get_io($IMEI[$i], 'door_open3');
    if ($io_door1 == 'io1') { //////door
    $io_door1 = 'i';
    } else if ($io_door1 == 'io2') {
    $io_door1 = 'j';
    } else if ($io_door1 == 'io3') {
    $io_door1 = 'k';
    } else if ($io_door1 == 'io4') {
    $io_door1 = 'l';
    } else if ($io_door1 == 'io5') {
    $io_door1 = 'm';
    } else if ($io_door1 == 'io6') {
    $io_door1 = 'n';
    } else if ($io_door1 == 'io7') {
    $io_door1 = 'o';
    } else if ($io_door1 == 'io8') {
    $io_door1 = 'p';
    }
    //######## door2
    if ($io_door2 == 'io1') {
    $io_door2 = 'i';
    } else if ($io_door2 == 'io2') {
    $io_door2 = 'j';
    } else if ($io_door2 == 'io3') {
    $io_door2 = 'k';
    } else if ($io_door2 == 'io4') {
    $io_door2 = 'l';
    } else if ($io_door2 == 'io5') {
    $io_door2 = 'm';
    } else if ($io_door2 == 'io6') {
    $io_door2 = 'n';
    } else if ($io_door2 == 'io7') {
    $io_door2 = 'o';
    } else if ($io_door2 == 'io8') {
    $io_door2 = 'p';
    }
    //##################

    if($io_door1!="") {
        $parameterizeData->doorOpen1 = $finalIoArr[$io_door1];
    }
    if($io_door2!="") {
        $parameterizeData->doorOpen2 = $finalIoArr[$io_door2];
    }
    /* if($io_door3!="")
      {
      $parameterizeData->doorOpen3=$finalIoArr[$io_door3];
      } */

    /* echo "doorOpen1=".$parameterizeData->doorOpen1."<br>";
      echo "doorOpen2=".$parameterizeData->doorOpen2."<br>";
      echo "doorOpen3=".$parameterizeData->doorOpen3."<br>"; */

    $LastSortedDate = getLastSortedDate($IMEI[$i], $datefrom, $dateto);
    $SortedDataObject = new data();
    $UnSortedDataObject = new data();

    if(($LastSortedDate+24*60*60)>=$endDateTS) { //All sorted data
        //echo "in if1";
        $type = "sorted";
        readFileXml($vserial[$i], $date1, $date2, $datefrom, $dateto, $userInterval, $requiredData, $sortBy, $type, $parameterizeData, $firstDataFlag, $SortedDataObject);
    }
    else if($LastSortedDate==null) { //All Unsorted data
        //echo "in if2";
        $type = "unSorted";
        readFileXml($vserial[$i], $date1, $date2, $datefrom, $dateto, $userInterval, $requiredData, $sortBy, $type, $parameterizeData, $firstDataFlag, $UnSortedDataObject);
    }
    else  { //Partially Sorted data
        $LastSDate = date("Y-m-d", $LastSortedDate+24*60*60);
        //echo "in else";
        $type = "sorted";
        readFileXml($vserial[$i], $date1, $date2, $datefrom, $LastSDate, $userInterval, $requiredData, $sortBy, $type, $parameterizeData, $firstDataFlag, $SortedDataObject);

        $type = "unSorted";
        readFileXml($vserial[$i], $date1, $date2, $LastSDate, $dateto, $userInterval, $requiredData, $sortBy, $type, $parameterizeData, $firstDataFlag, $UnSortedDataObject);
    }
    if(count($SortedDataObject->deviceDatetime)>0) {
        //echo "in sorted=".$SortedDataObject->deviceDatetime."<br><br><br><br><br><br>";
        $prevSortedSize = sizeof($SortedDataObject->deviceDatetime);
        for($obi = 0;$obi<$prevSortedSize; $obi++) {
            $xml_date_sel_arr[] = $SortedDataObject->deviceDatetime[$obi];
            $lat_sel_arr[] = $SortedDataObject->latitudeData[$obi];
            $lng_sel_arr[] = $SortedDataObject->longitudeData[$obi];

            if($parameterizeData->doorOpen1!=null) {
                $door_open1_arr[] = $SortedDataObject->doorOpen1Data[$obi];
            }
            if($parameterizeData->doorOpen2!=null) {
                $door_open2_arr[] = $SortedDataObject->doorOpen2Data[$obi];
            }
            if($parameterizeData->doorOpen3!=null) {
                $door_open3_arr[] = $SortedDataObject->doorOpen3Data[$obi];
            }
            $dataCnt++;
        }
    }
    if(count($UnSortedDataObject->deviceDatetime)>0) {
        $sortObjTmp = sortData($UnSortedDataObject, $sortBy, $parameterizeData);
        //var_dump($sortObjTmp);
        /* echo"sdt1=".$sortObjTmp->deviceDatetime[0]."<br>";
          echo "sdt2=".$sortObjTmp->deviceDatetime[1]."<br>";
          echo "sdo1=".$sortObjTmp->doorOpen1Data[0]."<br>";
          echo "sdo1=".$sortObjTmp->doorOpen1Data[1]."<br>";
          echo "sdo2=".$sortObjTmp->doorOpen2Data[0]."<br>";
          echo "sdo2=".$sortObjTmp->doorOpen2Data[1]."<br>";
          echo "sdo3=".$sortObjTmp->doorOpen3Data[0]."<br>";
          echo "sdo3=".$sortObjTmp->doorOpen3Data[1]."<br>"; */
          //echo "<br><br>";
          $sortedSize = sizeof($sortObjTmp->deviceDatetime);
        for($obi = 0;$obi<$sortedSize;$obi++) {
            $xml_date_sel_arr[] = $sortObjTmp->deviceDatetime[$obi];
            $lat_sel_arr[] = $sortObjTmp->latitudeData[$obi];
            $lng_sel_arr[] = $sortObjTmp->longitudeData[$obi];
            if($parameterizeData->doorOpen1!=null) {
                $door_open1_arr[] = $sortObjTmp->doorOpen1Data[$obi];
            }
            if($parameterizeData->doorOpen2!=null) {
                $door_open2_arr[] = $sortObjTmp->doorOpen2Data[$obi];
            }
            if($parameterizeData->doorOpen3!=null) {
                $door_open3_arr[] = $sortObjTmp->doorOpen3Data[$obi];
            }
            $dataCnt++;
        }
    }
    //echo"size=".$innerSize."<br>";
    $SortedDataObject = null;
    $sortObjTmp = null;
    $UnsortedDataObject = null;
    $parameterizeData->doorOpen1 = null;
    $parameterizeData->doorOpen2 = null;
    $parameterizeData->doorOpen3 = null;
    //######## CASSANDRA BLOCK2 CLOSED
    
    if (($Plant_OutDate[$i] != "") && ($Plant_OutTime[$i] != "")) { //## IF OUT FOUND KEEP OLD RECORDS
        continue;
    }
    $nogps = true;
    $nodata = true;
    //$manual_dispatch_before_6am = false;
    //####### CONDITION WHERE INVOICE CREATE DATE IS AFTER 6AM AND MANUAL DISPATCH IS BEFORE 6 AM
    $startdate = $startdate_tmp;

    $create_date_tmp = $create_date[$i];
    $sno_db1 = $sno_db[$i];
    //echo "\nVehicle=".$vehicle_no[$i];
    //echo "\nCreateDate=".$create_date_tmp." ,Startdate=".$startdate." ,ManualDispatchTime=".$Manual_DisptachTime[$i];
    /* if( ((strtotime($create_date_tmp) > strtotime($startdate)) && (strtotime($Manual_DisptachTime[$i]) < strtotime($startdate))) || 
      ((strtotime($create_date_tmp) > strtotime($startdate)) && (strtotime($Manual_DisptachTime[$i]) > strtotime($startdate))) )
      {
      //$tmp_startdate = strtotime($Manual_DisptachTime[$i]) - 7200;	//minus 2 hours
      $tmp_startdate = strtotime($Manual_DisptachTime[$i]) - 32400;	//minus 9 hours
      $tmp_startdate = date("Y-m-d H:i:s", $tmp_startdate);
      //echo "\nTmpDate=".$tmp_startdate;
      //$manual_dispatch_before_6am = true;
      $startdate = $tmp_startdate;
      } */

    //######## DECLARE VARIABLES.
    $io_door1 = "";
    $io_door2 = "";
    $p_in1 = false;
    $p_in2 = false;
    $p_out1 = false;
    $p_out2 = false;
    $p_in_code = "";
    $p_in_flag1 = false;

    $cp_in = false;
    $cp_out = false;
    $cplant_status_local[$i] = 0;

    $Lat_Final = "";
    $Lng_Final = "";
    $Customer_Final = "";
    $DistVar_Final = "";
    $CLat_Final = "";
    $CLng_Final = "";
    $CPlantNo_Final = "";
    $CDistVar_Final = "";

    $nodata_last = false;
    $plant_valid = false;

    $cplant_valid = false;
    $cp_dispatch_flag = false;

    $final_in = false;
    $final_out = false;


    if (($Plant_InDate[$i] != "") && ($Plant_InTime[$i] != "")) {
        $final_in = true;
    }
    
    $lat_ref = 0.0;
    $lng_ref = 0.0;
    $lat_ref1 = 0.0;
    $lng_ref1 = 0.0;
    $lat_cr = 0.0;
    $lng_cr = 0.0;
    $latc_ref = 0.0;
    $lngc_ref = 0.0;
    $latc_ref1 = 0.0;
    $lngc_ref1 = 0.0;
    $latc_cr = 0.0;
    $lngc_cr = 0.0;

    echo "<br>Vehicle=" . $i . "," . $Vehicle[$i];
    $row = $i + 2;
    //###### GET LAST HALT TIME
    $vehicle_serial = $IMEI[$i];
    $interval = 60;

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

    $delay = "-";

    /*$date_tmp1 = explode(" ", $startdate);
    $date_tmp2 = explode(" ", $enddate);
    $report_date1 = $date_tmp1[0];
    $report_time1 = $date_tmp1[1];
    $report_date2 = $date_tmp2[0];
    $report_time2 = $date_tmp2[1];*/

    global $DbConnection;
    global $account_id;
    $sno = 1;
    global $csv_string_halt;

    $fix_tmp = 1;
    $xml_date_latest = "1900-00-00 00:00:00";
    $CurrentLat = 0.0;
    $CurrentLong = 0.0;
    $LastLat = 0.0;
    $LastLong = 0.0;
    $firstData = 0;
    $distance = 0.0;
    $linetowrite = "";

    $arrivale_time = "";
    $depature_time = "";
    $hrs_min = "";

    $date_1 = explode(" ", $startdate);
    $datefrom = $date_1[0];
    $timefrom = $date_1[1];
    $date_2 = explode(" ", $enddate);
    $dateto = $date_2[0];
    $timeto = $date_2[1];
    $cum_dist = 0;

    //date_default_timezone_set("Asia/Calcutta");
    $current_datetime = date("Y-m-d H:i:s");
    $current_date = date("Y-m-d");

    $substr_count = 0;

    $AddEntryinrReport = false;
    //$f=0;
    //echo "\nTEST4";
    //for($d=0;$d<=($date_size-1);$d++)
    $fpoint = true;
    $firstdata_flag = 0;

    //for ($d = ($date_size - 1); $d >= 0; $d--) {
    for ($i = ((sizeof($xml_date_sel_arr)) - 1);$i >= 0;$i--) {
        //echo "<br>Date=".$userdates[$d];
        if ($cp_dispatch_flag) { //##### BREAK IF CP DISPATCH IS FOUND
            //echo "<br>B2:";
            break;
        }
        $DataComplete = false;
        $vehicleserial_tmp = null;
        $f = 0;
        $tmp = 0;

        //$p_in = false;					
        //$p_out = false;
        //####################
        //$sts_date_sel = "";
        $xml_date_sel = "";
        $lat_sel = "";
        $lng_sel = "";
        $speed_sel = "";
        $door1_sel = "";
        $door2_sel = "";
        $nodata = false;

        //$sts_date_sel = $sts_date;
        $xml_date_sel = $xml_date_sel_arr[$i];
        $lat_sel = $lat_sel_arr[$i];
        $lng_sel = $lng_sel_arr[$i];
        //$speed_sel = $speed_sel_arr[$IMEI[$i]];

        if($door_open1_arr[$i]!="") {
            $door1_io_val = $door_open1_arr[$i];
        }
        if($door_open2_arr[$i]!="") {
            $door2_io_val = $door_open2_arr[$i];
        }

        //########## STORE VEHICLE COUNTER																	  					
        $nodata = false;
        //echo "\nNodata2=".$nodata;
        $datetime = $xml_date_sel[$i];;

        //echo "<br>Door1=".$door1_io_val." ,Door2=".$door2_io_val;
        //################ CHECK DATA => REVERSE - FIRST PLANT OUT->IN (OUTTIME), IN->OUT (INTIME)
        if ((strtotime($datetime) > strtotime($startdate)) && (strtotime($datetime) < strtotime($enddate))) {
            //echo "<br>ValidData";
            if ($firstdata_flag == 0) {
                //echo "<br>FirstFlag";
                $halt_flag = 0;
                $halt_cflag = 0;
                $firstdata_flag = 1;

                $vserial = $vehicle_serial;
                $lat_ref = $lat_sel;
                $lng_ref = $lng_sel;

                $latc_ref = $lat_sel;
                $lngc_ref = $lng_sel;

                if ($lat_ref != "" && $lng_ref != "") {
                    $nogps = false;
                }
                $datetime_ref = $datetime;
                $datetimec_ref = $datetime;

                $cum_dist = 0;

                //###### FOR IRREGULAR DATA FILTER CODE
                $last_time1 = $datetime;
                $latlast = $lat_ref;
                $lnglast = $lng_ref;

                if (check_all_plants($lat_ref, $lng_ref, $lat_ref, $lng_ref) == true) {
                    $p_in = true;
                    $p_in_code = $Customer_Final;
                    //echo "\nFIRST TIME PLANT IN:".$Customer_Final;
                }
                //////##############################     	
                } else {
                //######### CHECK HALT IN 
                $lat_cr = $lat_sel;
                $lng_cr = $lng_sel;
                $datetime_cr = $datetime;

                $latc_cr = $lat_sel;
                $lngc_cr = $lng_sel;
                $datetimec_cr = $datetime;

                //$date_secs2 = strtotime($datetime_cr);																		
                calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, $distance);
                if ($distance > 2000) {
                    $distance = 0;
                    $lat_ref = $lat_cr;
                    $lng_ref = $lng_cr;
                }
                //###### FOR IRREGULAR DATA FILTER CODE
                //$tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;
                $tmp_time_diff1 = (double) (strtotime($last_time1) - strtotime($datetime)) / 3600;
                calculate_distance($latlast, $lat_cr, $lnglast, $lng_cr, $distance1);

                //echo "<br>Distance1=".$distance1." ,tmp_time_diff1=".$tmp_time_diff1;								
                if ($tmp_time_diff1 > 0.0) {
                    //echo "<br>InSpd2";
                    $tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
                    //echo "<br>TmpSpd2=".$tmp_speed;
                    $last_time1 = $datetime;
                    $latlast = $lat_cr;
                    $lnglast = $lng_cr;
                }
                //$tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time) )) / 3600;
                //$tmp_time_diff = ((double)( strtotime($last_time) - strtotime($datetime) )) / 3600;
                //#######################################									
                //echo "<br>TmpSpeed=".$tmp_speed." ,distance=".$distance." ,tmp_time_diff=".$tmp_time_diff1;
                //if($tmp_speed<500.0 && $distance>0.1 && $tmp_time_diff1>0.0)
                if ($tmp_speed < 500.0 && $tmp_time_diff1 > 0.0) {
                    //echo "\nValid Data";
                    if (($distance <= 0.3) && ($halt_flag == 0) && ( (strtotime($datetime_ref) - strtotime($datetime_cr)) > 120)) {
                        //echo "\nIn Halt=".$datetime;
                        $current_halt_time = 0;

                        $halt_flag = 1;
                        $lat_ref1 = $lat_cr;
                        $lng_ref1 = $lng_cr;
                        $datetime_ref = $datetime_cr;
                        //echo "<br>HALT SET, datetime_ref=".$datetime_ref;
                    } else if ($distance > 0.3) {
                        //###### FOR IRREGULAR DATA FILTER CODE
                        //$out_time = $datetime_ref;
                        calculate_distance($Lat_Final, $lat_sel, $Lng_Final, $lng_sel, $distance_plant);

                        //echo "<br>CustomerFinal=".$Customer_Final." ,distance_plant=".$distance_plant." ,DistVar_Final=".$DistVar_Final." ,date_time_ref=".$datetime_cr." ,date_sel=".$datetime;
                        $out_time = "";
                        if ($distance_plant > $DistVar_Final) {
                            //$out_time = $datetime_ref;
                            $out_time = $datetime_cr;
                        }
                        //echo "<br>OutHalt=".$datetime_ref." ,RESET,datetime=".$datetime;
                        //if($tmp_speed<500.0 && $tmp_time_diff>0.0)
                        {
                            $last_time = $datetime;
                            //$datetime_ref= $datetime_cr;
                            //######################################	
                        }
                        $lat_ref = $lat_cr;
                        $lng_ref = $lng_cr;
                        $datetime_ref = $datetime_cr;    //modified   				
                        $halt_flag = 0;
                    }

                    //######## CHECK HALT IN CLOSED
                    //########********* PLANT AND CHILLING PLANT CONDITIONS *******#########
                    //##1. ####### CHECK FOR PLANT IN -OUT						
                    //									echo "\nVehicle=".$Vehicle[$i]="., datetime_ref=".$datetime_ref." ,datetime_cr=".$datetime_cr;
                    if (($halt_flag == 1) && ((strtotime($datetime_ref) - strtotime($datetime_cr)) >= 14400) && (!$p_in1)) {
                        //echo "\nVehicle1=".$Vehicle[$i].", datetime_ref=".$datetime_ref." ,datetime_cr=".$datetime_cr;
                        $halt_plant_flag = check_all_plants($lat_ref1, $lng_ref1, $lat_cr, $lng_cr);
                        //echo "<br>Halt Plant-All Flag=".$halt_plant_flag." ,Customer_Final=".$Customer_Final;
                        if ($halt_plant_flag) {
                            $p_in1 = true;
                            if ((!$p_in) || (($p_in_code != $Customer_Final) && ($Customer_Final != ''))) {      //###### PLANT OUT 
                                $dif1 = strtotime($datetime_ref) - strtotime($datetime_cr);
                                //echo "\nVehicle2=".$Vehicle[$i].", dif=".$dif1." ,datetime_ref=".$datetime_ref." ,datetime_cr=".$datetime_cr;											
                                //echo "\nOUT FOUND:".$datetime_ref;
                                $plant_outtime_local[$i] = $datetime_ref;
                                update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle[$i], $i, $Customer_Final, $Target_ArrivalTime[$i], $startdate, $enddate, $plant_intime_local[$i], $plant_outtime_local[$i], $hrs_min, "2");
                                $final_out = true;
                            }
                        }
                    }

                    //echo "<br>P_OUT:".$p_out2." ,p_in1=".$p_in1." ,halt_flag=".$halt_flag; 
                    if ((!$p_out2) && ($p_in1)) {   //######### PLANT IN 
                        //echo "<br>P_OUT";
                        if (($halt_flag == 0)) {
                            //echo "<br>datetime_ref=".$datetime_ref." ,datetime_cr=".$datetime_cr." ,datetime=".$datetime." ,date_out=".$date_out;
                            //echo "<br>LatFinal=".$Lat_Final." ,LngFinal=".$Lng_Final." ,Lat_sel=".$lat_sel." ,lng_sel=".$lng_sel;
                            //		calculate_distance($Lat_Final, $lat_sel, $Lng_Final, $lng_sel, &$distance_plant);
                            //echo "<br>CustomerFinal=".$Customer_Final." ,distance_plant=".$distance_plant." ,DistVar_Final=".$DistVar_Final." ,date_time_ref=".$datetime_cr." ,date_sel=".$datetime;
                            //		if($distance_plant > $DistVar_Final)
                            if ($out_time != "") {
                                //echo "\nIN FOUND:".$out_time;
                                //$plant_intime_local[$i] = $datetime;
                                $p_out2 = true;

                                //##### RECORD PLANT IN
                                //$plant_intime_local[$i] = $datetime;
                                $plant_intime_local[$i] = $out_time;
                                $out_time = "";

                                if ($plant_outtime_local[$i] == "") {
                                    $hrs_min = "";
                                } else {
                                    $deptime = strtotime($plant_outtime_local[$i]);
                                    $arrtime = strtotime($plant_intime_local[$i]);
                                    $hms_2 = secondsToTime($deptime - $arrtime);
                                    $hrs_min = $hms_2[h] . ":" . $hms_2[m] . ":" . $hms_2[s];
                                }

                                $final_in = true;
                                update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle[$i], $i, $Customer_Final, $Target_ArrivalTime[$i], $startdate, $enddate, $plant_intime_local[$i], $plant_outtime_local[$i], $hrs_min, "1");

                                //######################
                            }
                        }
                    }

                    //##2. ####### CHECK CHILLING PLANT OUT
                    //if(($cplant_status_local[$i]==0) && (!$cp_in) && ($p_out2))	//CHILLING PLANT
                    if (($cplant_status_local[$i] == 0) && ($p_out2)) { //CHILLING PLANT
                        //echo "<br>DISPATCH FOUND1:Vehicle:".$Vehicle[$i]." ,halt_flag=".$halt_flag;
                        $halt_cplant_flag = false;
                        if ($halt_flag == 1) {
                            //echo "<br>ChillingBefore";
                            //$halt_cplant_flag = check_all_chilling_plants($transporter_id[$i], $lat_ref1, $lng_ref1, $lat_cr, $lng_cr);
                            $halt_cplant_flag = check_all_chilling_plants($transporter_id[$i], $lat_ref1, $lng_ref1, $lat_cr, $lng_cr);
                            //echo "<br>ChillingAfter:".$halt_cplant_flag;
                        }
                        if ($halt_cplant_flag) {
                            //echo "<br>DispatchFinal";
                            $cplant_status_local[$i] = 1;
                            $cplant_outtime_local[$i] = $datetime; //####### CHILLING PLANT OUT TIME

                            if ($plant_intime_local[$i] != "") {
                                //echo "<br>CHILLING PLANT OUT FOUND:".$plant_outtime_local[$i];
                                //echo "\nDispatchTime=".$cplant_outtime_local[$i]." ,CPlantNo_Final=".$CPlantNo_Final." ,PIN=".$plant_intime_local[$i]." ,POUT=".$plant_outtime_local[$i];
                                update_cplant($i, $objPHPExcel_1, $cplant_outtime_local[$i], $CPlantNo_Final, $plant_outtime_local[$i], $plant_intime_local[$i]);

                                //################################
                                $cp_in = true;

                                $cplant_status_local[$i] == 1;
                                $cplant_valid = true;

                                //###### BREAK THE PRCOESS -ONCE DISPATCH FOUND
                                $cp_dispatch_flag = true;
                                //echo "<br>B1:";
                                break;
                                //########################
                            }
                        }
                    }
                    //######## CHILLING PLANT IN-OUT CLOSED
                    //########## DOOR OPEN AND CLOSE ###################							
                    //###### DOOR1 OPEN
                    if ((!$door1_open_flag) && ($door1_io_val > 250)) {
                        //echo "<br>Door1First";
                        $start_time_door1_open = $datetime;
                        $door1_open_flag = true;
                    } else if (($door1_open_flag) && (!$d1) && ($door1_io_val > 250)) {
                        if ((strtotime($datetime) - strtotime($start_time_door1_open)) > 60) {
                            //$door1_str .= $start_time_door1_open.",";
                            //echo "<br>In Door1Val";
                            $door1_bin[$Vehicle[$i]][] = $start_time_door1_open;
                            $d1 = true;
                            $door1_close_flag = false;
                        }
                    }
                    //##### DOOR1 CLOSE
                    else if ((!$door1_close_flag) && ($door1_open_flag) && ($door1_io_val < 250)) {
                        $start_time_door1_close = $datetime;
                        $door1_close_flag = true;
                    } else if (($door1_close_flag) && ($door1_open_flag) && ($d1) && ($door1_io_val < 250)) {
                        if ((strtotime($datetime) - strtotime($start_time_door1_close)) > 60) {
                            //$door1_str .= $start_time_door1_close.",";
                            $door1_bin[$Vehicle[$i]][] = $start_time_door1_close;
                            $d1 = false;
                            $door1_open_flag = false;
                        }
                    }

                    //####### DOOR2 OPEN
                    if ((!$door2_open_flag) && ($door2_io_val > 250)) {
                        $start_time_door2_open = $datetime;
                        $door2_open_flag = true;
                    } else if (($door2_open_flag) && (!$d2) && ($door2_io_val > 250)) {
                        if ((strtotime($datetime) - strtotime($start_time_door2_open)) > 60) {
                            //$door2_str .= $start_time_door2_open.",";
                            $door2_bin[$Vehicle[$i]][] = $start_time_door2_open;
                            $d2 = true;
                            $door2_close_flag = false;
                        }
                    }
                    //###### DOOR2 CLOSE
                    else if ((!$door2_close_flag) && ($door2_open_flag) && ($door2_io_val < 250)) {
                        $start_time_door2_close = $datetime;
                        $door2_close_flag = true;
                    } else if (($door2_close_flag) && ($door2_open_flag) && ($d2) && ($door2_io_val < 250)) {
                        if ((strtotime($datetime) - strtotime($start_time_door2_close)) > 60) {
                            //$door2_str .= $start_time_door2_close.",";
                            $door2_bin[$Vehicle[$i]][] = $start_time_door2_close;
                            $d2 = false;
                            $door2_open_flag = false;
                        }
                    }
                } //### filter data closed
                //#### CLOSED DOOR OPEN/CLOSED #######       
            }  //ELSE CLOSED
        } //IF PLANT VALID FOUND
    }
    $f++;

    //### STORE LAST HALT TIME OF VEHICLE	
    $last_vehicle_name[] = $Vehicle[$i];
    $last_halt_time_new[] = $current_halt_time;
    //echo "<br>CurrentHaltTime:".$Vehicle[$i]." :".$current_halt_time;		
    //echo "\nNoData=".$nodata." ,Vehicle=".$Vehicle[$i];		
    //######## FILL DOOR VALUES
    fill_door_values($objPHPExcel_1, $row, $door1_bin, $door2_bin, $Vehicle[$i]);
    //######## DOOR CLOSED		

    if ($final_out &&!$final_in) {
        $plant_intime_local[$i] = $datetime_cr;
        update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle[$i], $i, $Customer_Final, $Target_ArrivalTime[$i], $startdate, $enddate, $plant_intime_local[$i], $plant_outtime_local[$i], $hrs_min, "1");
    }
    //######## UPDATE VEHICLE IN DATABASE
    $departure_tmp1 = $Plant_OutDate[$i] . " " . $Plant_OutTime[$i];
    $arrival_tmp1 = $Plant_InDate[$i] . " " . $Plant_InTime[$i];
    //echo "<br>DPT=".$departure_tmp1." ,ARR_TIME=".$arrival_tmp1;
    update_invoice_database($departure_tmp1, $arrival_tmp1, $GPRS_DispatchTime[$i], $Customer_Final, $CPlantNo_Final, $DBSNO[$i]); //UNCOMMENT IT
    //###### UPDATE REMARK
    $msg = "";
    if ($nodata) {
        $msg = "INACTIVE";
        update_nogps($objPHPExcel_1, $msg, $i);
    } else if (!$nogps ||!$nodata) {   //GPS FOUND
        $msg = "";
    } else if ($nogps) {  //FIRST TIME : GPS NOT FOUND
        $msg = "NO GPS";
        update_nogps($objPHPExcel_1, $msg, $i);
    }
    echo '\nmsg=' . $msg;
    } //##### EXCEL VEHICLE LOOP CLOSED
    //echo date('H:i:s') , " Write to Excel2007 format" , EOL;
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel_1, 'Excel2007');
    $objWriter->save($read_excel_path);
    echo date('H:i:s'), " File written to ", $read_excel_path, EOL;

    echo "\nHALT CLCLOSED";
}

//######## UPDATE VEHICLE
function update_vehicle_status($objPHPExcel_1, $read_excel_path, $Vehicle, $k, $Customer_Final, $ScheduleTime, $startdate, $enddate, $arrivale_time, $depature_time, $hrs_min, $status) {
//echo "\nPlantStatus=".$plant_status;
//echo "\nUPDATE VEHICLE::".$read_excel_path.", ".$Vehicle.", ".$k.", ".$StationNo.", ".$Lat.", ".$Lng.", ".$ScheduleTime.", ".$DistVar.", ".$Remark.", ".$startdate.", ".$enddate.", ".$status_entered;	
//global $ScheduleDate;
global $DEBUG_OFFLINE;
global $unchanged;

global $SNo;      //SENT FILE
global $LRNo;
//global $Vehicle;
global $FAT_kg;
global $SNF_kg;
global $QTY;
global $DriverName;
global $TranspoterName;
global $Initial_MilkAge;
global $Chilling_Plant;
//global $Chilling_PlantName;
//global $Target_Plant;			//$StationNo
//global $Target_PlantName;
global $Manual_DisptachTime;
global $GPRS_DispatchTime;
global $Plant_InDate;    //$ArrivalDate
global $Plant_InTime;    //$ArrivalTime
global $Manual_CloseTime;
global $GPRS_CloseTime;
global $Est_UnloadTime;
global $Diff_inCloseTime;
global $Plant_OutDate;    //$DepartureDate
global $Plant_OutTime;    //$DepartureTime
global $Plant_HaltTime;    //$HaltDuration
global $Server_CloseTime;
global $Transportation_Age;
global $Final_MilkAge;
global $Target_ArrivalTime;   //$ScheduleDateTime
global $Delay_InArrival;    //$Delay
global $IMEI;
//global $Lat;
//global $Lng;
//global $DistVar;
/* global $C_Lat;
  global $C_Lng;
  global $C_DistVar; */

global $sno_db;
global $transporter_m;
global $vehicle_m;
global $style_green;

//#######################
/* global $door1_open_str; global $door2_open_str; global $door1_close_str; global $door2_close_str;
  global $door1_open_flag;global $door1_close_flag;global $door2_open_flag;global $door2_close_flag; global $d1; global $d2; */
//#######################
//#######################	
$place = "-";
$station_no = "-";
//$transporter_name = "-";
$schedule_time = "-";
$delay = "-";


//##########################################			
$row = $k + 2;
$report_time1 = explode(' ', $startdate);
$report_time2 = explode(' ', $enddate);

//########## UPDATE LAST HALT TIME 
$last_halt_sec_global = 0;

/* $pos_c = strpos($station_no, "@");
  if($pos_c !== false)
  {
  //echo "\nNegative Found";
  $customer_at_the_rate1 = explode("@", $station_no);
  }
  else
  {
  $customer_at_the_rate1[0] = $station_no;
  } */

$valid_halt = false;
$route_no = "";
$final_date = "";
//echo "\nScheduleInTime=".$schedule_in_time;	
//$in_time_str_excel = $final_date." ".$ScheduleTime;
//$in_time_str_excel = $ScheduleDate[$k]." ".$ScheduleTime;
$in_time_str_excel = $ScheduleTime;
$in_time_str = $arrivale_time;

//echo "<br>ArrivalTime=".$arrivale_time." ,ScheduleTime=".$in_time_str_excel;	
if ($ScheduleTime != "") {
$time1 = strtotime($in_time_str) - strtotime($in_time_str_excel);
//echo "\nin_time_str=".$in_time_str." ,in_time_str_excel=".$in_time_str_excel." ,DiffTime=".$time1;

$time_delay = $time1 / 60;
}

if ($time_delay == "") {
$time_delay = "-";
}
if ($route_no == "") {
$route_no = "-";
}
if ($hrs_min == "") {
$hrs_min = "-";
}
//######## FINAL UPDATION																						
$arrival_time1 = explode(' ', $arrivale_time);
$depature_time1 = explode(' ', $depature_time);
//echo "<br>ArrivalTime=".$arrivale_time." ,status_entered=".$status_entered." ,entered_station=".$entered_station;	
//echo "<br>PlantInDate=".$Plant_InDate[$k]." ,Plant_InTime=".$Plant_InTime[$k];
//echo "\nStatus=".$status." ,PlantInDate=".$Plant_InDate[$k]." ,Plant_InTime=".$Plant_InTime[$k];

if (($status == "1") && ($Plant_InDate[$k] == "") && ($Plant_InTime[$k] == "")) {   //##### UPDATE PLANT IN TIME
//echo "\nIn Arrival-1";
$col_tmp = 'N' . $row;
$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $Customer_Final);

$col_tmp = 'R' . $row;
$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $arrival_time1[0]);
$objPHPExcel_1->getActiveSheet()->getStyle($col_tmp)->applyFromArray($style_green);

$col_tmp = 'S' . $row;
$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $arrival_time1[1]);
$objPHPExcel_1->getActiveSheet()->getStyle($col_tmp)->applyFromArray($style_green);

$col_tmp = 'X' . $row;
$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $hrs_min);
$objPHPExcel_1->getActiveSheet()->getStyle($col_tmp)->applyFromArray($style_green);

$col_tmp = 'AC' . $row;
$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, round($time_delay, 2));


$objPHPExcel_1->getActiveSheet()->getStyle($col_tmp)->applyFromArray($style_green);

//if(($C_Lat[$k]!="") && ($C_Lng[$k]!=""))
//{
/* if($Est_UnloadTime[$k]!="")
  {
  $Est_UnloadTime_sec = $Est_UnloadTime[$k]*60;
  $gprs_close_time_sec = strtotime($arrivale_time) + $Est_UnloadTime_sec;
  $gprs_close_time = date('Y-m-d H:i:s',$gprs_close_time_sec);
  $col_tmp = 'Q'.$row;
  $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $gprs_close_time);			//## GPRS CLOSE TIME

  $diff_close_time = (strtotime($Manual_CloseTime[$k]) - strtotime($gprs_close_time))/60;		//## DIFF IN CLOSE TIME
  $col_tmp = 'S'.$row;
  $objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $diff_close_time);
  } */
//}		
$Plant_InDate[$k] = $arrival_time1[0];
$Plant_InTime[$k] = $arrival_time1[1];
}
//else if(($status=="2") && ($Plant_InTime[$k]!=""))	//####### CHECK FOR DEPARTURE
else if ($status == "2") {
//echo "\nIn Departure-1";
//$plant_in_tmp = $Plant_InDate[$k]." ".$Plant_InTime[$k];
//echo "\nDepartureWrite";
if ($depature_time != "") {
//echo "\nIn Departure-2";
//##UPDATE DEPARTURE
$col_tmp = 'P' . $row;
$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $depature_time1[0]);
$objPHPExcel_1->getActiveSheet()->getStyle($col_tmp)->applyFromArray($style_green);

$col_tmp = 'Q' . $row;
$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $depature_time1[1]);
$objPHPExcel_1->getActiveSheet()->getStyle($col_tmp)->applyFromArray($style_green);

//$col_tmp = 'X'.$row;
//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $hrs_min);			
//###### UPDATE GPRS CLOSE TIME
//$col_tmp = 'S'.$row;
//$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp , $depature_time);
//##### UPDATE IN MYSQL DATABASE 						
/* global $DbConnection;
  $cdate = date('Y-m-d H:i:s');
  $query_update = "UPDATE invoice_mdrm SET close_time='$depature_time',system_time='$cdate',invoice_status='2',close_type='g' WHERE sno='$sno_db[$k]'";
  //echo "\nQueryUpdate=".$query_update;
  $result_update = mysql_query($query_update,$DbConnection); */

$Plant_OutDate[$k] = $depature_time1[0];
$Plant_OutTime[$k] = $depature_time1[1];
}
//echo "\nIF ARRIVAL NOT NULL";												
}
}

////### UPDATE HALT CLOSED
function update_cplant($k, $objPHPExcel_1, $cplant_outdatetime, $CPlantNo_Final, $plant_outdatetime, $arrivale_time) {
//### GPRS_DispatchTime/OutTime	
//global $objPHPExcel_1;
global $GPRS_DispatchTime;
global $style_green;
$row = $k + 2;

if ($GPRS_DispatchTime[$k] == "") {
//echo "<br>GPRS_DISPATCH FINAL:".$plant_outdatetime;
$col_tmp = 'M' . $row;
$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $CPlantNo_Final);
$objPHPExcel_1->getActiveSheet()->getStyle($col_tmp)->applyFromArray($style_green);

$col_tmp = 'T' . $row;
$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $cplant_outdatetime);
$objPHPExcel_1->getActiveSheet()->getStyle($col_tmp)->applyFromArray($style_green);

$GPRS_DispatchTime[$k] = $cplant_outdatetime;

$transportation_age = (strtotime($arrivale_time) - strtotime($GPRS_DispatchTime[$k])) / 60; //## TRANSPORTATION AGE
$col_tmp = 'Z' . $row;
$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, round($transportation_age, 1));
$objPHPExcel_1->getActiveSheet()->getStyle($col_tmp)->applyFromArray($style_green);
}
}

function fill_door_values($objPHPExcel_1, $row, $door1_bin, $door2_bin, $Vehicle) {
global $door1_open_flag;
global $door1_close_flag;
global $door2_open_flag;
global $door2_close_flag;
global $d1;
global $d2;

//######## DOOR :: SPLIT DOOR VALUES ##################		
$a[] = 'I';
$b[] = 'J';
$a[] = 'K';
$b[] = 'L';
$a[] = 'M';
$b[] = 'N';
$a[] = 'O';
$b[] = 'P';
$a[] = 'Q';
$b[] = 'R';
$a[] = 'S';
$b[] = 'T';
$a[] = 'U';
$b[] = 'V';
$a[] = 'W';
$b[] = 'X';
$a[] = 'Y';
$b[] = 'Z';
$aa[] = 'A';
$bb[] = 'B';
$aa[] = 'C';
$bb[] = 'D';
$aa[] = 'E';
$bb[] = 'F';
$aa[] = 'G';
$bb[] = 'H';
$aa[] = 'I';
$bb[] = 'J';
$aa[] = 'K';
$bb[] = 'L';
$aa[] = 'M';
$bb[] = 'N';
$aa[] = 'O';
$bb[] = 'P';
$aa[] = 'Q';
$bb[] = 'R';

$k = 0;
for ($i = 0;
$i < sizeof($door1_bin[$Vehicle]);
$i++) {
$col_tmp = 'A' . $a[$k] . $row;
$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $door1_bin[$Vehicle][$i]);
$i++;
$col_tmp = 'A' . $b[$k] . $row;
$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $door1_bin[$Vehicle][$i]);
$k++;
}

$k = 0;
for ($i = 0;
$i < sizeof($door2_bin[$Vehicle]);
$i++) {
$col_tmp = 'B' . $aa[$k] . $row;
$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $door2_bin[$Vehicle][$i]);
$i++;
$col_tmp = 'B' . $bb[$k] . $row;
$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $door2_bin[$Vehicle][$i]);
$k++;
}
//##########################
//########### RESET DOOR VALUES
//$door1_open_str = ""; $door2_open_str = ""; $door1_close_str =""; $door2_close_str ="";
$door1_open_flag = false;
$door1_close_flag = false;
$door2_open_flag = false;
$door2_close_flag = false;
$d1 = false;
$d2 = false;
//#############################		
}

function check_all_plants($lat_ref1, $lng_ref1, $lat_cr, $lng_cr) {    //###### CHECKING WITH ALL PLANTS
global $Lat_Final;
global $Lng_Final;
global $Customer_Final;
global $DistVar_Final;
global $customer_no_total;    //##### PLANTS TOTAL
global $station_coord_total;
global $distance_variable_total;

for ($i = 0;
$i < sizeof($customer_no_total);
$i++) {
$p_coord = explode(',', $station_coord_total[$i]);
$lat_g = trim($p_coord[0]);
$lng_g = trim($p_coord[1]);

$DistVar = $distance_variable_total[$i];

if ($DistVar == 0) {
$DistVar = 1;
}

$distance_station = 0;
if (($lat_g != "") && ($lng_g != "")) {
calculate_distance($lat_ref1, $lat_g, $lng_ref1, $lng_g, $distance_station1);
calculate_distance($lat_cr, $lat_g, $lng_cr, $lng_g, $distance_station2);

if ($distance_station1 < $distance_station2) {
$distance_station = $distance_station1;
} else {
$distance_station = $distance_station2;
}
/* if($customer_no_total[$i]=="1400")
  {
  echo "<br>Distance=".$distance_station." ,DistVar=".$DistVar;
  } */

if ($distance_station < $DistVar) {
$Lat_Final = $lat_g;
$Lng_Final = $lng_g;
$Customer_Final = $customer_no_total[$i];
$DistVar_Final = $DistVar;
//echo "<br>DistanceALL=".$distance_station." ,DistVar=".$DistVar." ,Customer_Final=".$Customer_Final;
return true;
}
}
}

return false;
}

function check_individual_plant($lat_ref1, $lng_ref1, $lat_cr, $lng_cr, $lat_g, $lng_g, $DistVar) {
if ($DistVar == 0) {
$DistVar = 0.1;
}

$distance_station = 0;
if (($lat_g != "") && ($lng_g != "")) {
calculate_distance($lat_ref1, $lat_g, $lng_ref1, $lng_g, $distance_station1);
calculate_distance($lat_cr, $lat_g, $lng_cr, $lng_g, $distance_station2);

if ($distance_station1 < $distance_station2) {
$distance_station = $distance_station1;
} else {
$distance_station = $distance_station2;
}
//echo "\nDistance=".$distance_station." ,DistVar=".$DistVar;

if ($distance_station < $DistVar) {
//echo "<br>DistanceFinal=".$distance_station." ,DistVar=".$DistVar;
return true;
}
}

return false;
}

function check_all_chilling_plants($transporter_id, $lat_ref1, $lng_ref1, $lat_cr, $lng_cr) {    //###### CHECKING WITH ALL CHILLING PLANT
global $CLat_Final;
global $CLng_Final;
global $CPlantNo_Final;
global $CDistVar_Final;
global $chilling_plant_no;      //##### CHILLING PLANT TOTAL
global $chilling_plant_coord_total;
global $chilling_plant_distvar_total;

//echo "\nSize Chilling Plant=".sizeof($chilling_plant_no[$transporter_id])." ,TPT_ID=".$transporter_id;
for ($i = 0;
$i < sizeof($chilling_plant_no[$transporter_id]);
$i++) {
$cp_coord = explode(',', $chilling_plant_coord_total[$transporter_id][$i]);
$clat_g = trim($cp_coord[0]);
$clng_g = trim($cp_coord[1]);

$CDistVar = $chilling_plant_distvar_total[$transporter_id][$i];

if ($CDistVar == 0) {
$CDistVar = 0.1;
}

$distance_station = 0;
if (($clat_g != "") && ($clng_g != "")) {
calculate_distance($lat_ref1, $clat_g, $lng_ref1, $clng_g, $distance_station1);
calculate_distance($lat_cr, $clat_g, $lng_cr, $clng_g, $distance_station2);

if ($distance_station1 < $distance_station2) {
$distance_station = $distance_station1;
} else {
$distance_station = $distance_station2;
}
//echo "\nDistanceCH=".$distance_station." ,CDistVar=".$CDistVar;
if ($distance_station < $CDistVar) {
//echo "\nCHILLING HALT FOUND:".$chilling_plant_no[$transporter_id][$i];
$CLat_Final = $clat_g;
$CLng_Final = $clng_g;
$CPlantNo_Final = $chilling_plant_no[$transporter_id][$i];
$CDistVar_Final = $CDistVar;

return true;
}
}
}
return false;
}

function update_invoice_database($departure_time, $arrival_time, $dispatch_time, $p_final, $cp_final, $dbsno) {
global $DbConnection;

$query = "UPDATE invoice_mdrm SET plant_outtime='$departure_time',plant_intime='$arrival_time',chilling_plant_outtime='$dispatch_time',gprs_chilling_plant='$cp_final',gprs_plant='$p_final' WHERE sno='$dbsno'";
//echo "<br>".$query;
mysql_query($query, $DbConnection);
}

function update_nogps($objPHPExcel_1, $msg, $i) {
//echo "\nInUpdateRemark";
$row = $i + 2;
$col_tmp = 'AE' . $row;

$objPHPExcel_1->setActiveSheetIndex(0)->setCellValue($col_tmp, $msg);
}
?>
