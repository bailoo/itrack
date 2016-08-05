<?php

//error_reporting(-1);
//ini_set('display_errors', 'On');
//set_time_limit(3000);
set_time_limit(300);
date_default_timezone_set("Asia/Kolkata");

$imei = $_POST['imei'];
$date1 = $_POST['start_date'];
$date2 = $_POST['end_date'];

$d1 = date("Y/m/d 00:00:00");
$d2 = date("Y/m/d H:i:s");

if ($date1 == '') {
    $date1 = $d1;
}
if ($date2 == '') {
    $date2 = $d2;
}

echo'
    <html>
    <head>
    <script language="javascript" src="../../src/js/datetimepicker.js"></script>
    <script language="javascript" src="../../src/js/datetimepicker_sd.js"></script>
    </head>
    <body>
    
    <form name="cell" method="POST" action="index_cellid.php">
<table border=0 cellspacing=0 cellpadding=3 align="center">	
        <tr>
                <td  class="text"><b>Select IMEI : </b></td><td><input type="text" name="imei" value="'.$imei.'"></td>
                <td  class="text"><b>Select Duration : </b></td>
                <td>
                        <table>
                                <tr>
                                        <td  class="text">	</td>
                                        <td class="text">
                                                Start Date

                                <input type="text" id="date1" name="start_date" value="' . $date1 . '" size="20" maxlength="19">

                                                        <a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
                                                                <img src="../../images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
                                                        </a>
                                                                &nbsp;&nbsp;&nbsp;End Date

                                <input type="text" id="date2" name="end_date" value="' . $date2 . '" size="20" maxlength="19">

                                                        <a href=javascript:NewCal("date2","yyyymmdd",true,24)>
                                                                <img src="../../images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
                                                        </a>

                                        </TD>                              																	
                                        </td>
                                </tr>
                        </table>
                <td>
                <td>&nbsp;<input type="submit" value="SUBMIT"></td>
        </tr>										
</table>
</form>
<hr>
';

//include_once("main_vehicle_information_1.php");
//include_once('Hierarchy.php');
//include_once('util_session_variable.php');
//include_once('util_php_mysql_connectivity.php');
//include_once('user_type_setting.php');	
include_once('calculate_distance.php');
/* if (file_exists('C:\\xampp/htdocs/itrack_test_analytics/beta/src/php/googleMapApi_cellid.php')) { echo "TRUE"; }else{
  echo "false";
  } */
echo"<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDhLWXnQP-3SJ5WTazE878MSg2C1Q3Cmmc&libraries=places'></script>";
include_once('googleMapApi_cellid.php');
include_once("home_cellid.php");
//echo "one";
include_once("cassandraPath.php");
include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('data.php');
include_once("getXmlData.php");
include_once("gsm_to_gps.php");

//include_once("module_home_body");
//$flag_play = $_REQUEST['flag_play'];

/* $vserial1 = $_REQUEST['vserial'];
  $vserial = explode(',',$vserial1) ;
  $vsize=sizeof($vserial);
  $date1 = $_REQUEST['startdate'];
  $date2 = $_REQUEST['enddate']; */

/*$vserial[] = "868324020440140";
$date1 = "2016-08-04 18:00:00";
$date2 = "2016-08-04 18:05:00";*/

//echo "IMEI=".$imei." ,date1=".$date1." ,date2=".$date2;
$cell_id_existing = array();
$lat_existing = array();
$lng_existing = array();

if($imei!="") {
    $vserial[] = $imei;
}

$vsize = sizeof($vserial);
$date1 = str_replace("/", "-", $date1);
$date2 = str_replace("/", "-", $date2);
$date_1 = explode(" ", $date1);
$date_2 = explode(" ", $date2);
$datefrom = $date_1[0];
$dateto = $date_2[0];
$userInterval = "0";
$sortBy = "h";
$firstDataFlag = 0;
$requiredData = "All";
$endDateTS = strtotime($date2);
$parameterizeData = null;
$parameterizeData = new parameterizeData();
$parameterizeData->messageType = 'a';
$parameterizeData->version = 'b';
$parameterizeData->fix = 'c';
$parameterizeData->latitude = 'd';
$parameterizeData->longitude = 'e';
$parameterizeData->speed = 'f';
$parameterizeData->cellName = 'ci';
$parameterizeData->supVoltage = 'r';
$parameterizeData->dayMaxSpeed = 's';
$parameterizeData->lastHaltTime = 'u';
$parameterizeData->io1 = 'i';
$parameterizeData->io2 = 'j';
$parameterizeData->io3 = 'k';
$parameterizeData->io4 = 'l';
$parameterizeData->io5 = 'm';
$parameterizeData->io6 = 'n';
$parameterizeData->io7 = 'o';
$parameterizeData->io8 = 'p';
//$parameterizeData->ci='ci';

$time_interval1 = $_REQUEST['time_interval'];
$minlat = 180;
$maxlat = -180;
$minlong = 180;
$maxlong = -180;
$maxPoints = 1000;
$file_exist = 0;
$tmptimeinterval = strtotime($enddate) - strtotime($startdate);
if ($time_interval1 == "auto") {
    $timeinterval = ($tmptimeinterval / $maxPoints);
    $distanceinterval = 0.1;
} else {
    if ($tmptimeinterval > 86400) {
        $timeinterval = $time_interval1;
        $distanceinterval = 0.3;
    } else {
        $timeinterval = $time_interval1;
        $distanceinterval = 0.02;
    }
}
//$distanceinterval=0.0;


get_All_Dates($datefrom, $dateto, $userdates);
$date_size = sizeof($userdates);
//echo "<br>Vsize=".$vsize;

if($vsize>0) {
    //echo "entered";
for ($i = 0; $i < $vsize; $i++) {
    $dataCnt = 0;
    //echo "vsdfsdfs=".$vserial[$i]."<br>";
    //$vehicle_info=get_vehicle_info($root,$vserial[$i]);
    //$vehicle_detail_local=explode(",",$vehicle_info);
    $home_report_type = "map_report";
    $report_type = "Vehicle";

    //echo "vehcileName=".$finalVNameArr[$i]." vSerial=".$vehicle_detail_local[0]."<br>";
    if ($home_report_type == "map_report") {   /// map only
        //echo "in if";
        $lineTmpTrack = "";
        if ($report_type == "Vehicle") {
            //echo "in if 1";
            $CurrentLat = 0.0;
            $CurrentLong = 0.0;
            $LastLat = 0.0;
            $LastLong = 0.0;
            $LastDTForDiff = "";
            $firstData = 0;
            $distance = 0.0;

            for ($di = 0; $di <= ($date_size - 1); $di++) {
                //echo "userdate=".$userdates[$di]."<br>";
                $SortedDataObject = null;
                $SortedDataObject = new data();
                if ($date_size == 1) {
                    $dateRangeStart = $date1;
                    $dateRangeEnd = $date2;
                } else if ($di == ($date_size - 1)) {
                    $dateRangeStart = $userdates[$di] . " 00:00:00";
                    $dateRangeEnd = $date2;
                } else if ($di == 0) {
                    $dateRangeStart = $date1;
                    $dateRangeEnd = $userdates[$di] . " 23:59:59";
                } else {
                    $dateRangeStart = $userdates[$di] . " 00:00:00";
                    $dateRangeEnd = $userdates[$di] . " 23:59:59";
                }
                //echo "vserial=" . $vserial[$i] . "dateRangeStart" . $dateRangeStart . "dateRangeEnd=" . $dateRangeEnd . "<br>";
                deviceDataBetweenDates($vserial[$i], $dateRangeStart, $dateRangeEnd, $sortBy, $parameterizeData, $SortedDataObject);
                //print_r($SortedDataObject)."<br>";

                $last_rec = 0;
                if (count($SortedDataObject->deviceDatetime) > 0) {
                    $prevSortedSize = sizeof($SortedDataObject->deviceDatetime);
                    for ($obi = 0; $obi < $prevSortedSize; $obi++) {
                        $DataValid = 0;
                        $CurrentLat = $SortedDataObject->latitudeData[$obi];
                        $CurrentLong = $SortedDataObject->longitudeData[$obi];
                        $datetime = $SortedDataObject->deviceDatetime[$obi];
                        //echo "DT=".$SortedDataObject->deviceDatetime[$obi]." :::";
                        //echo "CELL=".$SortedDataObject->cellNameData[$obi]."<br>";
                        //echo "Lat=".$CurrentLat." ,DT=".$datetime." ,LNG=".$CurrentLong;
                        //if((strlen($CurrentLat)>5) && ($CurrentLat!="-") && (strlen($CurrentLong)>5) && ($CurrentLong!="-"))
                        //{
                        $DataValid = 1;
                        //}
                        if (($DataValid == 1)) {
                            $last_rec = $obi;
                            //echo "lat=".$CurrentLat." lng=".$CurrentLat."<br><br>";
                            $xml_date_current = $datetime;
                            //echo "xml_date_current=".$xml_date_current."<br>";
                            //if((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval)
                            {
                                $CurrentDTForDiffTmp = strtotime($datetime);
                                //echo "CurrentDTForDiffTmp=".$CurrentDTForDiffTmp."<br>";
                                /* if($firstData==1)
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
                                  if($dateDifference_1>2.0)
                                  {
                                  $cmpOverSpeed=90.0;
                                  }
                                  else
                                  {
                                  $cmpOverSpeed=200.0;
                                  } */
                                /* if((((((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval) && ($distance>=$distanceinterval)) || ($firstData==0)) && 
                                  (($xml_date_current<=$enddate) && ($xml_date_current>=$startdate))) || ($f==$total_lines-2) ) */
                                //echo "distance=".$distance." distanceinterval=".$distanceinterval."<br><br>";
                                //if(($distance>=$distanceinterval) || ($firstData==0))
                                {
                                    //echo "distance1=".$distance." distanceinterval1=".$distanceinterval."<br><br>";
                                    //if($overSpeed<$cmpOverSpeed)
                                    {
                                        $xml_date_last = $xml_date_current;
                                        $LastLat = $CurrentLat;
                                        $LastLong = $CurrentLong;
                                        //$linetolog = "Data Written\n";
                                        $LastDTForDif = $xml_date_current;

                                        $finalDistance = $finalDistance + $distance;
                                        $linetowrite = '<x a="' . $SortedDataObject->messageTypeData[$obi] . '" b="' . $SortedDataObject->versionData[$obi] . '" c="' . $SortedDataObject->fixData[$obi] . '" d="' . $SortedDataObject->latitudeData[$obi] . '" e="' . $SortedDataObject->longitudeData[$obi] . '" f="' . $SortedDataObject->speedData[$obi] . '" g="' . $SortedDataObject->serverDatetime[$obi] . '" h="' . $SortedDataObject->deviceDatetime[$obi] . '" i="' . $SortedDataObject->io1Data[$obi] . '" j="' . $SortedDataObject->io2Data[$obi] . '" k="' . $SortedDataObject->io3Data[$obi] . '" l="' . $SortedDataObject->io4Data[$obi] . '" m="' . $SortedDataObject->io5Data[$obi] . '" n="' . $SortedDataObject->io6Data[$obi] . '" o="' . $SortedDataObject->io7Data[$obi] . '" p="' . $SortedDataObject->io8Data[$obi] . '" q="' . $SortedDataObject->sigStrData[$obi] . '" r="' . $SortedDataObject->supVoltageData[$obi] . '" s="' . $SortedDataObject->dayMaxSpeedData[$obi] . '" v="' . $vserial[$i] . '" w="' . $vehicle_detail_local[0] . '" x="' . $vehicle_detail_local[2] . '" y="' . $vehicle_detail_local[1] . '" z="' . round($finalDistance, 2) . '" za="' . $vehicle_detail_local[8] . '" ci="' . $SortedDataObject->cellNameData[$obi] . '"/>';
                                        $firstData = 1;
                                        $lineTmpTrack = $lineTmpTrack . $linetowrite . "@";
                                        $distance = 0;
                                        //$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
                                        //fwrite($fh, $linetowrite); 
                                    }
                                }
                            }
                        }
                    }
                }
            }
            //if(($overSpeed<$cmpOverSpeed) && ($last_rec!=0))
            /* {
              $finalDistance = $finalDistance + $distance;
              $linetowrite='<x a="'.$SortedDataObject->messageTypeData[$last_rec].'" b="'.$SortedDataObject->versionData[$last_rec].'" c="'.$SortedDataObject->fixData[$last_rec].'" d="'.$SortedDataObject->latitudeData[$last_rec].'" e="'.$SortedDataObject->longitudeData[$last_rec].'" f="'.$SortedDataObject->speedData[$last_rec].'" g="'.$SortedDataObject->serverDatetime[$last_rec].'" h="'.$SortedDataObject->deviceDatetime[$last_rec].'" i="'.$SortedDataObject->io1Data[$last_rec].'" j="'.$SortedDataObject->io2Data[$last_rec].'" k="'.$SortedDataObject->io3Data[$last_rec].'" l="'.$SortedDataObject->io4Data[$last_rec].'" m="'.$SortedDataObject->io5Data[$last_rec].'" n="'.$SortedDataObject->io6Data[$last_rec].'" o="'.$SortedDataObject->io7Data[$last_rec].'" p="'.$SortedDataObject->io8Data[$last_rec].'" q="'.$SortedDataObject->sigStrData[$last_rec].'" r="'.$SortedDataObject->supVoltageData[$last_rec].'" s="'.$SortedDataObject->dayMaxSpeedData[$last_rec].'" v="'.$vserial[$i].'" w="'.$vehicle_detail_local[0].'" x="'.$vehicle_detail_local[2].'" y="'.$vehicle_detail_local[1].'" z="'.round($finalDistance,2).'" za="'.$vehicle_detail_local[8].'" c="'.$parameterizeData->cellName[$last_rec].'"/>';
              //echo"<textarea>".$linetowrite."</textarea>";
              $lineTmpTrack=$lineTmpTrack.$linetowrite."@";
              } */
            /* $linetowrite='<x a="'.$SortedDataObject->messageTypeData[$obi].'" b="'.$SortedDataObject->versionData[$obi].'" c="'.$SortedDataObject->fixData[$obi].'" d="'.$SortedDataObject->latitudeData[$obi].'" e="'.$SortedDataObject->longitudeData[$obi].'" f="'.$SortedDataObject->speedData[$obi].'" g="'.$SortedDataObject->serverDatetime[$obi].'" h="'.$SortedDataObject->deviceDatetime[$obi].'" i="'.$SortedDataObject->io1Data[$obi].'" j="'.$SortedDataObject->io2Data[$obi].'" k="'.$SortedDataObject->io3Data[$obi].'" l="'.$SortedDataObject->io4Data[$obi].'" m="'.$SortedDataObject->io5Data[$obi].'" n="'.$SortedDataObject->io6Data[$obi].'" o="'.$SortedDataObject->io7Data[$obi].'" p="'.$SortedDataObject->io8Data[$obi].'" q="'.$SortedDataObject->sigStrData[$obi].'" r="'.$SortedDataObject->supVoltageData[$obi].'" s="'.$SortedDataObject->dayMaxSpeedData[$obi].'" v="'.$vserial[$i].'" w="'.$vehicle_detail_local[0].'" x="'.$vehicle_detail_local[2].'" y="'.$vehicle_detail_local[1].'" z="'.round($finalDistance,2).'"/>';
              $lineTmpTrack=$lineTmpTrack.$linetowrite."@"; */
            $io_type_value = $vehicle_detail_local[7];
            $lineF = explode("@", substr($lineTmpTrack, 0, -1));
            for ($n = 0; $n < sizeof($lineF); $n++) {
                //echo "Line=" . $lineF[$n] . "<br>";
                /* $mcc=260;
                  $mnc=2;
                  $lac=10250;
                  $cellid=26511; */
                /* preg_match('/d="[^" ]+/', $lineF[$n], $lat_tmp);
                  $lat_tmp1 = explode("=", $lat_tmp[0]);
                  $lat = substr(preg_replace('/"/', '', $lat_tmp1[1]), 0, -1);
                  //echo "lat=".$lat."<br>";
                  //               $lat_arr_last[]=$lat;
                  $lat_arr_last[] = $gps_data[0];

                  preg_match('/e="[^" ]+/', $lineF[$n], $lng_tmp);
                  $lng_tmp1 = explode("=", $lng_tmp[0]);
                  $lng = substr(preg_replace('/"/', '', $lng_tmp1[1]), 0, -1);
                  //echo "lng=".$lng."<br>";
                  //               $lng_arr_last[]=$lng;
                  $lng_arr_last[] = $gps_data[1]; */

                preg_match('/h="[^"]+/', $lineF[$n], $datetime_tmp);
                $datetime_tmp1 = explode("=", $datetime_tmp[0]);
                $datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
                $datetime_arr_last[] = $datetime;
                // echo "datetime=".$datetime."<br>";

                preg_match('/v="[^"]+/', $lineF[$n], $vserial_tmp);
                $vserial_tmp1 = explode("=", $vserial_tmp[0]);
                $vehicle_serial = preg_replace('/"/', '', $vserial_tmp1[1]);
                $vserial_arr_last[] = $vehicle_serial;
                // echo "vehicle_name1=".$vehicle_serial."<br>";

                preg_match('/w="[^"]+/', $lineF[$n], $vname_tmp);
                $vname_tmp1 = explode("=", $vname_tmp[0]);
                $vehicle_name = preg_replace('/"/', '', $vname_tmp1[1]);
                $vehiclename_arr_last[] = $vehicle_name;
                // echo "vehicle_name=".$vehicle_name."<br>";

                preg_match('/x="[^"]+/', $lineF[$n], $vnumber_tmp);
                $vnumber_tmp1 = explode("=", $vnumber_tmp[0]);
                $vehicle_number = preg_replace('/"/', '', $vnumber_tmp1[1]);
                $vehiclenumber_arr_last[] = $vehicle_number;
                //echo "vehicle_number=".$vehicle_number."<br>";

                preg_match('/f="[^"]+/', $lineF[$n], $speed_tmp);
                $speed_tmp1 = explode("=", $speed_tmp[0]);
                $speed = preg_replace('/"/', '', $speed_tmp1[1]);
                if (($speed <= 3) || ($speed > 200)) {
                    $speed = 0;
                }
                $speed_arr_last[] = $speed;
                //echo "speed=".$speed."<br>";               
                preg_match('/s="[^"]+/', $lineF[$n], $day_max_speed_tmp);
                $day_max_speed_tmp1 = explode("=", $day_max_speed_tmp[0]);
                $day_max_speed = preg_replace('/"/', '', $day_max_speed_tmp1[1]);
                $day_max_speed_arr_last[] = $day_max_speed;
                // echo "day_max_speed=".$day_max_speed."<br>";

                /* preg_match('/t="[^"]+/', $lineF[$n], $day_max_speed_time_tmp);
                  $day_max_speed_time_tmp1 = explode("=",$day_max_speed_time_tmp[0]);
                  $day_max_speed_time= preg_replace('/"/', '', $day_max_speed_time_tmp1[1]); */

                // echo "day_max_speed_time=".$day_max_speed_time."<br>";

                preg_match('/u="[^"]+/', $lineF[$n], $last_halt_time_tmp);
                $last_halt_time_tmp1 = explode("=", $last_halt_time_tmp[0]);
                $last_halt_time = preg_replace('/"/', '', $last_halt_time_tmp1[1]);
                $last_halt_time_arr_last[] = $last_halt_time;

                preg_match('/ y="[^"]+/', $lineF[$n], $vehilce_type_tmp);
                $vehilce_type_tmp1 = explode("=", $vehilce_type_tmp[0]);
                $vehilce_type = preg_replace('/"/', '', $vehilce_type_tmp1[1]);
                $vehilce_type_arr[] = $vehilce_type;

                preg_match('/z="[^"]+/', $lineF[$n], $distance_travel_tmp);
                $distance_travel_tmp1 = explode("=", $distance_travel_tmp[0]);
                $distance_travel = preg_replace('/"/', '', $distance_travel_tmp1[1]);
                $distance_travel_arr[] = $distance_travel;

                preg_match('/za="[^"]+/', $lineF[$n], $dmobno_tmp);
                $dmobno_tmp1 = explode("=", $dmobno_tmp[0]);
                $dmobno = preg_replace('/"/', '', $dmobno_tmp1[1]);
                $dMobileNoArr[] = $dmobno;

                preg_match('/ci="[^"]+/', $lineF[$n], $cellID_tmp);
                $cellID_tmp1 = explode("=", $cellID_tmp[0]);
                $cell = preg_replace('/"/', '', $cellID_tmp1[1]);
                $cell_tmp = explode("$", $cell);               
                
                if($cell_id_existing[trim($cell)] != "") {
                    $lat_arr_last[] = $lat_existing[trim($cell)];
                    $lng_arr_last[] = $lng_existing[trim($cell)];                  
                } else {
                    $lac_tmp = hexdec($cell_tmp[2]);
                    $cellid_tmp = hexdec($cell_tmp[3]);

                    if( ($cell_tmp[0]!="") && ($cell_tmp[1]!="") && ($lac_tmp!="") && ($cellid_tmp!="")) {
                        $gps_data = explode(",", (get_gps($cell_tmp[0], $cell_tmp[1], $lac_tmp, $cellid_tmp)));
                        //echo "<br>CELL=".$cell." <br>MCC=".$gps_data[0]." ,MNC=".$gps_data[1]." ,LAC=".$gps_data[3]." ,CELLID=".$gps_data[4];
                        $lat_arr_last[] = $gps_data[0];
                        $lng_arr_last[] = $gps_data[1];

                        $cell_id_existing[trim($cell)] = 1;
                        $lat_existing[trim($cell)] = $gps_data[0];
                        $lng_existing[trim($cell)] = $gps_data[1];
                    } //else {
                         //echo "<br>CELL=".$cell." ,Datetime=".$datetime;                       
                    //}
                }
                
                $cellIDArr[] = $cell;
            }
            //print_r($io_str_last);
            //print_r($lng_arr_last);
            // print_r($io_str_last);
            //$gps_data = get_gps($mcc, $mnc, $lac, $cellid);
            //print_r($cellIDArr);
            //exit();
            if(sizeof($lat_arr_last)>0 && sizeof($lng_arr_last)>0) {
                $googleMapthisapi = new GoogleMapHelper();
                echo $googleMapthisapi->addMultipleMarker("map_canvas", $lat_arr_last, $lng_arr_last, $datetime_arr_last, $vserial_arr_last, $vehiclename_arr_last, $speed_arr_last, $cellIDArr);
            }
        }
    }
}
}

$o_cassandra->close();
?>


