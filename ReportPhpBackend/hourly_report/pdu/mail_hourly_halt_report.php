<?php
set_time_limit(360000);

//include_once("../database_ip.php");
$DBASE = "iespl_vts_beta";
//$HOST = "localhost";
$USER = "root";
$PASSWD = "mysql";
$account_id = "718";
if($account_id == "718") $user_name = "pdu";
echo "\nDBASE=".$DBASE." ,USER=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

//$abspath = "D:\\test_app";
$abspath = "/var/www/html/vts/beta/src/php";
include_once($abspath."/common_xml_element.php");
include_once($abspath."/get_all_dates_between.php");
include_once($abspath."/sort_xml.php");
include_once($abspath."/calculate_distance.php");
include_once($abspath."/report_title.php");
include_once($abspath."/read_filtered_xml.php");
include_once($abspath."/user_type_setting.php");
include_once($abspath."/get_io.php");
//include_once($abspath."/select_landmark_report.php");
//include_once($abspath."/area_violation/check_with_range.php");
//include_once($abspath."/area_violation/pointLocation.php");
//require_once $abspath."/excel_lib/class.writeexcel_workbook.inc.php";
//require_once $abspath."/excel_lib/class.writeexcel_worksheet.inc.php";
include_once($abspath."/util.hr_min_sec.php");
//include_once($abspath."/hourly_report/".$user_name."/get_master_detail.php");

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once $abspath.'/PHPExcel/IOFactory.php';

$objPHPExcel_1 = null;

echo "TEST1";
include_once("read_master_file.php"); 
include_once("read_sent_file.php");
//include_once("update_sent_file.php");
include_once("create_hrly_excel_file.php");
include_once("action_hourly_report_halt.php");

include_once("create_last_halt_time.php");
include_once("read_last_halt_time.php");
include_once("read_last_processed_time.php");
include_once("update_last_halt_time.php");

include_once("update_last_processed_time.php");
include_once("delete_file.php");

$sent_root_path = $abspath."/hourly_report/".$user_name."/sent_file";
echo "\nSent_RootPath=".$sent_root_path;

$evening_sent_file_path1 = $sent_root_path."/HOURLY_MAIL_VTS_HALT_REPORT_EVENING_MOTHER_PDU.xlsx";
//$evening_sent_file_path2 = $sent_root_path."/HOURLY_MAIL_VTS_HALT_REPORT_EVENING_MOTHER_DELHI_FOCAL_ROUTE.xlsx";
$morning_sent_file_path = $sent_root_path."/HOURLY_MAIL_VTS_HALT_REPORT_MORNING_MOTHER_PDU.xlsx";

$evening_last_processed_time_path1 = $sent_root_path."/evening_last_processed_time_1.xlsx";
//$evening_last_processed_time_path2 = $sent_root_path."/evening_last_processed_time_2.xlsx";
$morning_last_processed_time_path = $sent_root_path."/morning_last_processed_time.xlsx";

$evening_last_halt_time_path1 = $sent_root_path."/evening_last_halt_time_1.xlsx";
$evening_last_min_max_temperature_path = $sent_root_path."/min_max_temp.xlsx";
//$evening_last_halt_time_path2 = $sent_root_path."/evening_last_halt_time_2.xlsx";
$morning_last_halt_time_path = $sent_root_path."/morning_last_halt_time.xlsx";

//echo "TEST2";
//include_once("get_customer_db_detail.php");
include_once("get_route_db_detail.php");
//include_once("process_data.php");

$date = date('Y-m-d');
//$date = "2014-07-18";
$pdate = date('Y-m-d', strtotime($date .' -1 day'));		//CHECK 12 JUNE EVENING REPORT PENDING
//$pdate = "2014-02-06";
$unchanged = true;
//######## MAKE TWO SHIFTS
$shift_ev_date1 = $pdate." 21:00:00";
$shift_ev_date2 = $pdate." 23:59:59";
$shift_ev_date3 = $date." 00:00:00";
$shift_ev_date4 = $date." 12:00:00";
//$shift_ev_date4 = $date." 12:00:00";

$current_time = date('Y-m-d H:i:s');
//$current_time = $date." 11:45:00";

#$ev_run_start_time1 = $date." 20:00:00";
//$ev_run_start_time1 = $date." 23:00:00";
$ev_run_start_time1 = $date." 05:00:00";
//$ev_run_start_time2 = $date." 22:00:00";
//$mor_run_start_time = $date." 10:00:00";

$shift_ev1 = false;
$shift_ev2 = false;
$shift_mor = false;

//$time1 = $pdate." 21:00:00";
//## MAKE START AND END TIME TO ELIMINATE OLD DATES

$cdate = date('Y-m-d');
$pdate = date('Y-m-d', strtotime($date .' -1 day'));
$cdatetime1 = strtotime(date('00:00:00'));
$cdatetime2 = strtotime(date('H:i:s'));
$difftime = $cdatetime2 - $cdatetime1;

if($difftime > 72000)
{
        $time1 = $cdate." 21:00:00";
}
else
{
        $time1 = $pdate." 21:00:00";
}


//if( (($current_time >= $shift_ev_date1) && ($current_time <= $shift_ev_date2) && ($current_time >= $ev_run_start_time1) ) || (($current_time >= $shift_ev_date3) && ($current_time <= $shift_ev_date4)) )

if( ($current_time >= $ev_run_start_time1) && ($current_time <= $shift_ev_date4) )
{	
	$shift_ev1 = true;
	echo "\nEv-Shift";
}
else
{
	//## DELETE EVENING FILE -IF SHIFT IS OVER
	echo "\nDEL-EV:SHIFT1 FILES";
	$shift = "ev";		
	if(file_exists($evening_sent_file_path1)) delete_file($evening_sent_file_path1);
	if(file_exists($evening_last_processed_time_path1)) delete_file($evening_last_processed_time_path1);
	if(file_exists($evening_last_halt_time_path1)) delete_file($evening_last_halt_time_path1);
	if(file_exists($evening_last_min_max_temperature_path)) delete_file($evening_last_min_max_temperature_path);
}

//## CHECK ALREADY OPEN INSTANCE
if($shift_ev1)
{
	$result = exec("lsof +d $sent_root_path | grep -c -i HOURLY_MAIL_VTS_HALT_REPORT_EVENING_MOTHER_PDU.xlsx");
	if ($result == "1") {
		$shift_ev1 = false;
	}
}

echo "\nSTART";

$transporter_m = array();
$vehicle_m = array();

//########################## EVENING SHIFT STARTS #########################
//#### INITIALIZE ARRAYS -EV-SHIFT1

$sheet1_row = 2;
$sheet2_row = 2;
$unchanged = true;
	
$shift = array();			//MASTER FILE
$expected_customer_csv = array();
$expected_time_csv = array();
$vehicle_t = array();
$transporter = array();
$all_routes = array();
$all_customers = array();

$route_input = array();
$vehicle_input = array();
$customer_input = array();
$shift_input = array();
$transporter_input = array();

$min_date_ev = array();
$max_date_ev = array();
$min_date_mor = array();
$max_date_mor = array();

$relative_plant_input = array();
$relative_customer_input = array();
$relative_transporter_input = array();
$relative_route_input = array();

$customer_sel = array(array());
$customer_name_sel = array(array());
$plant_sel = array(array());
$transporter_sel = array(array());
$station_id = array(array());
$type = array(array());
$station_coord = array(array());
$distance_variable = array(array());

$expected_time_sel = array(array());	//FROM MASTER FILE

$vehicle_name_rdb = array();		//VEHICLE ROUTE DETAIL
$vehicle_imei_rdb = array();
$customer_name_rdb = array();
$route_name_rdb = array();
$route_type_rdb = array();
//$remark_rdb = array();

$csv_string_dist = "";                //INITIALISE  DISTANCE VARIABLES
$csv_string_dist_arr = array();
$sno_dist = 0;
$overall_dist = 0.0;

$csv_string_halt = "";                //INITIALISE  HALT VARIABLES
$csv_string_halt_arr = array();
$total_halt_dur = 0;
$sno_halt = 0;

$user_interval = "1";   //1 MINUTES		

$Vehicle = array();			//SENT FILE
$SNo = array();
$StationNo = array();
$StationName = array();
$Type = array();
$RouteNo = array();
$ReportShift = array();
$ArrivalDate = array();
$ArrivalTime = array();
$DepartureDate = array();
$DepartureTime = array();
$ScheduleTime = array();
$Delay = array();
$HaltDuration = array();

$InTemp = array();
$OutTemp = array();
$MinTemp = array();
$MinTempDate = array();
$MinTempTime = array();
$MaxTemp = array();
$MaxTempDate = array();
$MaxTempTime = array();

$Remark = array();
$ReportDate1 = array();
$ReportTime1 = array();
$ReportDate2 = array();
$ReportTime2 = array();
$TransporterM = array();
$TransporterI = array();
$Plant = array();
$Km = array();
$Lat = array();
$Lng = array();
$DistVar = array();
$IMEI = array();
$RouteType = array();
$NO_GPS = array();
//####################
$Vehicle_CI = array();
$StationNo_CI = array();
$RouteNo_CI = array();
$RouteType_CI = array();
$TransporterI_CI = array();	//Evening
$ArrivalTime_CI = array();
//####################
$LastTempVehicle = array();
$LastMinTemp = array();
$LastMinDate = array();
$LastMinTime = array();
$LastMaxTemp = array();
$LastMaxDate = array();
$LastMaxTime = array();
//####################

$RedRoute = array();
$RedCustomer = array();

$last_vehicle_name = array();		//LAST PROCESSED FILE
$last_halt_time = array();

$last_time_processed ="";
$csv_string_halt_final = "";

if($shift_ev1)
{
	echo "\nEV1-PDU MAIL";
	//$route_type = "CASH";
	$route_type = "ALL";
	//######## READ EVENING SENT FILE #############		
	echo "\nLastProcessedFile=".$evening_last_processed_time_path1;
	if(file_exists($evening_last_processed_time_path1))
	{		
		echo "\nFile Exists";
		read_last_processed_time($evening_last_processed_time_path1);
		echo "\nLast ProcessedTime";
		read_last_halt_time($evening_last_halt_time_path1);
		//read_all_routes($account_id,"ZPME");
		echo "\nLast HaltTime";
		$Last_Time = $last_time_processed;
	}
	else
	{
		echo "\nFile DoesNot Exist";
		$Last_Time = $shift_ev_date1;
		//$Last_Time = "2013-10-07 15:00:00";
	}
			
	if (!file_exists($evening_sent_file_path1))
	{
		echo "\nCreateFile:Evening";
		$evening_last_processed_time = "";
		
		get_route_db_detail("ZPME");		
		//echo "\nSizeRoute=".sizeof($route_name_rdb);
		//get_customer_db_detail($account_id, "ZPME", $route_type);
		//echo "\nSizeAllRoutes=".sizeof($all_routes);
		$objPHPExcel_1 = null;
		create_hrly_excel($evening_sent_file_path1, $evening_last_min_max_temperature_path, "ZPME", $route_type);
		create_last_halt_time($evening_last_halt_time_path1, $route_type);		
		//echo "\n3";
	}
	
	$objPHPExcel_1 = null;	
	echo "\nEvFile1=".$evening_sent_file_path1;
	read_sent_file($evening_sent_file_path1, $evening_last_min_max_temperature_path);
	echo "\nAfter ReadSentFile";
	get_halt_xml_data($Last_Time,$current_time, $evening_sent_file_path1, $evening_last_min_max_temperature_path, $time1, $time2);
	echo "\nAfter Data Process";	
		
	//######### UPDATE LAST TIME PROCESSED -ALWAYS UPDATED #############	
	update_last_processed_time($evening_last_processed_time_path1, $current_time);
	update_last_halt_time($evening_last_halt_time_path1);
	echo "\nAfter Last ProcessedDetail:Evening";
	//#### LAST TIME PROCESSED CLOSED #############
	
	//############ SEND EMAIL ##############
	$to = 'Praveen.Chamoli@motherdairy.com,Trilok1.Singh@motherdairy.com,Jeromy.Mathew@motherdairy.com,ashish@iembsys.co.in';	
	//$to = 'rizwan@iembsys.com';
	//$to = 'jyoti.jaiswal@iembsys.com';
	//$to = 'gpsreporthourly@gmail.com'; 	
	$time_1 = date('Y-m-d H:i:s');
	$time_2 = strtotime($time_1);
	$msg = "";
	if($unchanged)
	{
		$msg = "UNCHANGED";
	}
	else
	{
		$msg = "CHANGED";
	}
	$subject = "HOURLY_MAIL_VTS_HALT_REPORT_(MOTHER_PDU)_".$msg."_".$time_1."_".$time_2;
	$message = "HOURLY_MAIL_VTS_HALT_REPORT_(MOTHER_PDU)_".$msg."_".$time_1."_".$time_2."<br><br><font color=red size=1>*** This is an automatically generated email by the system on specified time, please do not reply ***</font>";
	$random_hash = md5(date('r', time()));  
	$headers = "From: support@iembsys.co.in\r\n";
	$headers .= "Cc: hourlyreport4@gmail.com"; 
	//$headers .= "Cc: rizwan@iembsys.com";	
	//pass:8090025844
	//$headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com";
	$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 	
	$filename_title = "V2:HOURLY_MAIL_VTS_HALT_REPORT_EVENING_MOTHER_PDU_".$msg."_".$time_1."_".$time_2.".xlsx";	
	$file_path = $evening_sent_file_path1;

	//echo "\nFILE PATH:Ev=".$file_path; 	
	include("send_mail_api.php");	
	//######################################
}

//######### SHIFT EVENING CLOSED
$last_halt_sec_global = 0;

function binary_plant_search($elem, $array, $array1, $array2, $array3) 	//elem = station to search, array = customer, array1 = plant
{
   $top = sizeof($array) -1;
   $bot = 0;
   while($top >= $bot) 
   {
      $p = floor(($top + $bot) / 2);
      if ($array[$p] < $elem) $bot = $p + 1;
      elseif ($array[$p] > $elem) $top = $p - 1;
      else return $array1[$p].":".$array2[$p].":".$array3[$p];//return TRUE;
   }
   return "-";
}

?>


