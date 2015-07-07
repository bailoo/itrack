<?php

//ini_set('display_errors',1);

set_time_limit(360000);

//### DEBUG BOOLEAN
$DEBUG_OFFLINE = false;
$DEBUG_ONLINE = false;
$CREATE_MASTER = false;
$isReport = true;
//#################
$DBASE = "iespl_vts_beta";
if ($DEBUG_OFFLINE) {
    $HOST = "localhost";
} else if ($DEBUG_ONLINE) {
    include_once("../database_ip.php");
} else {
    include_once("../database_ip.php");
}
$USER = "root";
if ($DEBUG_OFFLINE) {
    $PASSWD = "mysql";
} else {
    $PASSWD = 'neon04$VTS';
}
$account_id = "829";
if ($account_id == "829")
    $user_name = "raw_milk_db3";
//if($account_id == "231") $user_name = "delhi@";
//echo "\nDBASE=".$DBASE." ,USER=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST, $USER, $PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db($DBASE, $DbConnection) or die("could not find DB");

date_default_timezone_set("Asia/Kolkata");
if ($DEBUG_OFFLINE) {
    //$abspath = "D:\\test_app";
    $abspath = "C:\\xampp/htdocs/itrack/beta/src/php";
    $report_path = "C:\\xampp/htdocs/itrack/ReportPhpBackend";
} else if ($DEBUG_ONLINE) {
    $abspath = "/var/www/html/vts/beta/src/php";
    $report_path = "/mnt/itrack/ReportPhpBackend";
} else {
    $abspath = "/var/www/html/vts/beta/src/php";
    $report_path = "/mnt/itrack/ReportPhpBackend";
}
echo "<br>AbsPath=" . $abspath;
//echo "\nD1";
include_once($abspath . '/xmlParameters.php');
//echo "\nD2";
include_once($abspath . '/parameterizeData.php');
//echo "\nD3";
include_once($abspath . '/data.php');
//echo "\nD4";
include_once($abspath . '/sortXmlData.php');
//echo "\nD5:" . $abspath;
//$tmp = $abspath.'/getXmlData.php';
//if(file_exists($tmp)){echo "File Exists2";} else {"Does not exist";}
include_once($abspath . '/getXmlData.php');

include_once($abspath . "/get_all_dates_between.php");
include_once($abspath . "/sort_xml.php");
include_once($abspath . "/calculate_distance.php");
include_once($abspath . "/report_title.php");
include_once($abspath . "/read_filtered_xml.php");
include_once($abspath . "/user_type_setting.php");
//include_once($abspath."/select_landmark_report.php");
//include_once($abspath."/area_violation/check_with_range.php");
//include_once($abspath."/area_violation/pointLocation.php");
//require_once $abspath."/excel_lib/class.writeexcel_workbook.inc.php";
//require_once $abspath."/excel_lib/class.writeexcel_worksheet.inc.php";
include_once($abspath . "/util.hr_min_sec.php");
include_once($abspath . "/get_io.php");
//include_once($abspath."/hourly_report/".$user_name."/get_master_detail.php");
//### IMPORT XLSX LIBRARY
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);
if ("Exists=" . file_exists($abspath . "/mail_api/mailgun-php/attachment_mailgun.php"));
include_once($abspath . "/mail_api/mailgun-php/attachment_mailgun.php");

define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once $abspath . '/PHPExcel/IOFactory.php';

$objPHPExcel_1 = null;

echo "TEST1";
include_once("read_sent_file.php");
include_once("read_pending_invoice_db.php");
include_once("create_pending_invoice.php");
include_once("action_hourly_report_halt.php");
include_once("update_previous_invoice.php");

$sent_root_path = $report_path . "/daily_report/motherdairy/" . $user_name . "/sent_file";
echo "\nSent_RootPath=" . $sent_root_path;

$morning_sent_file_path = $sent_root_path . "/RAW_MILK_INVOICE_REPORT.xlsx";

//echo "TEST2";
$cdatetime1 = date('Y-m-d'); //###UNCOMMENT IT ORIGINAL
//$cdatetime1 = "2015-02-23";
//$cdatetime1 = '2015-02-08';
//$ctime1 = date(' H:i:s');
//$ctime1 = ' 10:30:00';
$ctime1 = ' 13:30:00';
$cdatetime = $cdatetime1 . " 00:00:00";
$cdatetime_db = $cdatetime1 . $ctime1;
//$cdatetime = '2014-11-14 10:30:00';
$pdate = date('Y-m-d', strtotime($cdatetime . ' -1 day'));
$pdatetime = $pdate . " 00:00:00";

$sdate = date('Y-m-d', strtotime($cdatetime . ' -3 day'));
$sdate_open = date('Y-m-d', strtotime($cdatetime . ' -7 day'));
$sdatetime_open = $sdate_open . " 00:00:00";

$sdatetime = $sdate . " 00:00:00";
$edatetime = $pdate . " 23:59:59";

//########### TEMPORARY DATES
//$pdatetime = '2014-12-02 06:00:00';
//$cdatetime = '2014-12-03 10:30:00';
//###########################
$vehicle_m = array();
//#### INITIALIZE ARRAYS
$shift_mor = true;

if ($shift_mor) {
    $sheet1_row = 2;
    $sheet2_row = 2;

    $unchanged = true;
    //$transporter_name = array(array());
    $transporter_name = array();
    $vehicle_imei = array();

    $customer_no = array();
    $station_name = array();
    $station_coord = array();
    $distance_variable = array();

    //###### DECLARATION FOR TRANSPORTER WISE -MULTIPLE CHILLING PLANT
    $transporter_id = array();
    $chilling_plant_no = array(array());
    $chilling_plant_coord_total = array(array());
    $chilling_plant_distvar_total = array(array());

    $customer_no_total = array();
    //$station_name_total = array();
    $station_coord_total = array();
    $distance_variable_total = array();

    $transporter_child_account = array(array());
    //####################################

    $sno_db = array();
    $lorry_no = array();
    $vehicle_no = array();
    $tanker_type = array();
    $email = array();
    $mobile = array();
    $qty_kg = array();
    $fat_percentage = array();
    $snf_percentage = array();
    $fat_kg = array();
    $snf_kg = array();
    $milk_age = array();
    $docket_no = array();
    $dispatch_time = array();
    $target_time = array();
    $validity_time = array();
    $plant_acceptance_time = array();
    $plant = array();
    $chilling_plant = array();
    $chilling_plant_coord = array();
    $chilling_plant_distvar = array();
    $driver_name = array();
    $driver_mobile = array();
    $unload_estimated_time = array();
    $unload_accept_time = array();
    $parent_account_id = array();
    $create_id = array();
    $create_date = array();
    $edit_id = array();
    $edit_date = array();
    $invoice_status = array();
    $close_type = array();
    $close_time = array();
    $system_time = array();
    $status = array();
    $plant_outtime_db = array();
    $plant_intime_db = array();
    $chilling_plant_outtime_db = array();
    $gprs_chilling_plant_db = array();
    $gprs_plant_db = array();

    $csv_string_dist = "";                //INITIALISE  DISTANCE VARIABLES
    $csv_string_dist_arr = array();
    $sno_dist = 0;
    $overall_dist = 0.0;

    $csv_string_halt = "";                //INITIALISE  HALT VARIABLES
    $csv_string_halt_arr = array();
    $total_halt_dur = 0;
    $sno_halt = 0;

    $user_interval = "1";   //1 MINUTES		

    $SNo = array();       //SENT FILE
    $LRNo = array();
    $Vehicle = array();
    $TankerType = array();
    $FAT_kg = array();
    $SNF_kg = array();
    $QTY = array();
    $DriverName = array();
    $TranspoterName = array();
    $Initial_MilkAge = array();
    $Chilling_Plant = array();
    //$Chilling_PlantName = array();
    $Target_Plant = array();   //$StationNo
    //$Target_PlantName = array();
    $Manual_DisptachTime = array();
    $GPRS_DispatchTime = array();
    $ArrivalDate = array();    //$ArrivalDate
    $ArrivalTime = array();    //$ArrivalTime
    $Manual_CloseTime = array();
    $GPRS_CloseTime = array();
    $Est_UnloadTime = array();
    $Diff_inCloseTime = array();
    //$DepartureDate = array();				//$DepartureDate
    //$DepartureTime = array();				//$DepartureTime
    $GPRS_DispatchTime = array();
    $Plant_InDate = array();    //$ArrivalDate
    $Plant_InTime = array();    //$ArrivalTime	
    $Plant_OutDate = array();   //$DepartureDate
    $Plant_OutTime = array();    //$DepartureTime

    $HaltDuration = array();    //$HaltDuration
    $Server_CloseTime = array();
    $Transportation_Age = array();
    $Final_MilkAge = array();
    $Target_ArrivalTime = array();   //$ScheduleDateTime
    $Delay_InArrival = array();    //$Delay
    $IMEI = array();
    /* $Lat = array();
      $Lng = array();
      $DistVar = array();
      $C_Lat = array();
      $C_Lng = array();
      $C_DistVar = array(); */
    $Door_Type = array();
    $Door1_OpenTime = array();
    $Door1_CloseTime = array();
    $Door2_OpenTime = array();
    $Door2_CloseTime = array();
    //$Status = array();
    //#######################
    $door1_bin = array(array());
    $door2_bin = array(array());
    $door1_open_flag = false;
    $door1_close_flag = false;
    $door2_open_flag = false;
    $door2_close_flag = false;
    $d1 = false;
    $d2 = false;
    $DBSNO = array();
    //#######################
    //####### INCLUDE PREVIOUS INVOICE VARIABLES
    include("temp_variables.php");

    $last_time_processed = "";
    $csv_string_halt_final = "";

    if (file_exists($morning_sent_file_path)) {
        unlink($morning_sent_file_path);
    }

    $objPHPExcel_1 = null;
    read_pending_invoice_db();
    echo "\nCreateFileRawMilk:Morning:Path=" . $morning_sent_file_path;
    $objPHPExcel_1 = null;
    create_pending_invoice($morning_sent_file_path);
    echo "\nAfter CreateFile";

    $objPHPExcel_1 = null;
    read_sent_file($morning_sent_file_path);
    echo "\nAfter ReadSentFile";

    get_halt_xml_data($sdatetime, $edatetime, $morning_sent_file_path);
    echo "\nAfter Data Process";

    //######### READ AND UPDATE FINAL RECORDS TO DATABASE
    /* include("temp_variables.php");
      read_update_records($morning_sent_file_path); */
    //############ SEND EMAIL :MORNING ##############
    //$to = 'rizwan@iembsys.com';			
    $to = 'Yogendra.Singh@motherdairy.com,rawmilk.control@gmail.com,Vijay.Singh@motherdairy.com';

    $time_1 = date('Y-m-d H:i:s');
    $time_2 = strtotime($time_1);

    $subject = "INVOICE_REPORT_(MOTHER_RAWMILK)_" . $time_1;
    $message = "INVOICE_REPORT_(MOTHER_RAWMILK)_" . $time_1 . "<br><br><font color=red size=1>*** This is an automatically generated email by the system on specified time, please do not reply ***</font>";
    $random_hash = md5(date('r', time()));
    $headers = "From: support@iembsys.co.in\r\n";
    $headers .= "Cc: hourlyreport4@gmail.com";
    //$headers .= "Cc: rizwan@iembsys.com";
    $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-" . $random_hash . "\"";
    $filename_title = "INVOICE_REPORT_(MOTHER_RAWMILK)_" . $time_1 . ".xlsx";
    $file_path = $morning_sent_file_path;
    //echo "\nFILE PATH:Mor=".$file_path;
    //### MAILGUN -Make the call to the client.
    $result = $mgClient->sendMessage($domain, array(
        'from' => 'Itrack <support@iembsys.co.in>',
        'to' => $to,
        //'cc'      => 'taseen@iembsys.com',
        'cc' => 'hourlyreport4@gmail.com',
        //'cc'      => 'hourlyreport4@gmail.com',
        // 'bcc'     => 'astaseen83@gmail.com',
        'subject' => $subject,
        'text' => $message,
        'html' => '<html></html>'
            ), array(
        'attachment' => array($file_path)
    ));

        //     include("send_mail_api.php");	
        //######################################
}

$last_halt_sec_global = 0;

function binary_plant_search($elem, $array, $array1, $array2, $array3) {  //elem = station to search, array = customer, array1 = plant
    $top = sizeof($array) - 1;
    $bot = 0;
    while ($top >= $bot) {
        $p = floor(($top + $bot) / 2);
        if ($array[$p] < $elem)
            $bot = $p + 1;
        elseif ($array[$p] > $elem)
            $top = $p - 1;
        else
            return $array1[$p] . ":" . $array2[$p] . ":" . $array3[$p]; //return TRUE;
    }
    return "-";
}
?>


