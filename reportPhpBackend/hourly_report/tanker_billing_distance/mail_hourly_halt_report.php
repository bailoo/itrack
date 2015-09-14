<?php

set_time_limit(360000);
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/

date_default_timezone_set("Asia/Kolkata");
//### DEBUG BOOLEAN
global $DEBUG_OFFLINE;
$DEBUG_OFFLINE = false;
$DEBUG_ONLINE = false;
$CREATE_MASTER = false;
$MAIN_DEBUG = false;
$LOG = false;
//#################
$isReport = true;

include_once("../../db_connection.php");
//$HOST = "localhost";
$account_id = "568";
if ($account_id == "568")
    $user_name = "tanker";
//if($account_id == "231") $user_name = "delhi@";
echo "\nDBASE=".$DBASE." ,USER=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST, $USER, $PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db($DBASE, $DbConnection) or die("could not find DB");

date_default_timezone_set("Asia/Kolkata");
if ($DEBUG_OFFLINE) {    
    $abspath = "D:\\itrack/beta/src/php";
    $report_path = "D:\\MOTHERDELHI_REPORT";   
} else if ($DEBUG_ONLINE) {
    $abspath = "/var/www/html/vts/beta/src/php";
    $report_path = "/mnt/itrack/reportPhpBackend";
} else {
    $abspath = "/var/www/html/vts/beta/src/php";
    $report_path = "/mnt/itrack/reportPhpBackend";
}
//echo "<br>AbsPath=" . $abspath;
include_once($abspath . "/common_xml_element.php");
//echo "\nD1";
include_once($abspath . '/ioParameters.php');
//echo "\nD2";
include_once($abspath . '/dataParameters.php');
//echo "\nD3";
include_once($abspath . '/dataArrays.php');
if (file_exists($tmp)) {
    echo "File Exists1";
} else {
    "Does not exist";
}
//echo "\nD4";
include_once($abspath . '/sortXmlData.php');
//echo "\nD5:" . $abspath;
//$tmp = $abspath.'/getXmlData.php';
//if(file_exists($tmp)){echo "File Exists2";} else {"Does not exist";}
include_once($abspath . '/getDeviceData.php');
//echo "\nD6";
include_once($abspath . "/calculate_distance.php");
include_once($abspath . "/report_title.php");
include_once($abspath . "/read_filtered_xml.php");
include_once($abspath . "/user_type_setting.php");
echo "\nD7";
include_once($abspath . "/util.hr_min_sec.php");

define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once $abspath . '/PHPExcel/IOFactory.php';

$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
$cacheSettings = array('memoryCacheSize' => '1028MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$objPHPExcel_1 = null;

//echo "TEST1";
include_once("read_sent_file.php");
include_once("create_hrly_excel_file.php");

$date = date('Y-m-d');
$pdate = date('Y-m-d', strtotime($date .' -1 day'));
$directory_date = "2015-09-02";
$filename = "DISTANCE_16_31.xls";
//$filename = "DISTANCE_test.xls";
$read_file_path = $abspath . "/gps_report/" . $account_id . "/upload/".$directory_date."/".$filename;
$write_file_path = $abspath . "/gps_report/" . $account_id . "/download/".$directory_date."/".$filename;
echo "\nSent_RootPath=" . $write_file_path;

include_once("get_route_db_detail.php");
include_once("action_distance_report.php");
echo "\nRouteDataProcessed:".$read_file_path."\n";
//include_once("process_data.php");

//#### INITIALIZE ARRAYS -EV-SHIFT1
$TripDate = array();
$DCSM_NAME = array();
$Route = array();
$VehicleNo = array();
$ActivityTimeForWeightOut = array();
$ActivityTimeForWeightIn = array();
$UniqueVehicle = array();
$VehicleIMEI = array();

echo "\nFileExist=".file_exists($read_file_path);

if (file_exists($read_file_path)) {
    
    echo "\nFileExistsTrue";
    read_sent_file($read_file_path);
    get_route_db_detail();
    echo "\nAfterGetRouteDB";
}

$objPHPExcel_1 = null;

get_distance_data($write_file_path);
echo "\nAfter Data Process";     

clearstatcache();
?>


