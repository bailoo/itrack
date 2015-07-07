<?php
set_time_limit(18000);
//include_once('util_session_variable.php');
//include_once('util_php_mysql_connectivity.php');

//$HOST = "localhost";
include_once("../database_ip.php");
$DBASE = "iespl_vts_beta";
$USER = "root";
$PASSWD = "mysql";
$account_id = "226";
$sub_account_id = "395";
if($account_id == "226") $user_name = "wockhardt";
echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

$abspath = "/var/www/html/vts/beta/src/php";
//$abspath = "D:\\test_app";
include_once($abspath."/common_xml_element.php");
include_once($abspath."/get_all_dates_between.php");
include_once($abspath."/sort_xml.php");
include_once($abspath."/calculate_distance.php");
include_once($abspath."/report_title.php");
include_once($abspath."/read_filtered_xml.php");
include_once($abspath."/user_type_setting.php");
	//include_once($abspath."/select_landmark_report.php");
require_once $abspath."/excel_lib/class.writeexcel_workbook.inc.php";
require_once $abspath."/excel_lib/class.writeexcel_worksheet.inc.php";
include_once($abspath."/util.hr_min_sec.php");
//include_once($abspath."/hourly_report/".$user_name."/get_master_detail.php");

//### IMPORT XLSX LIBRARY
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once $abspath.'/PHPExcel/IOFactory.php';

$objPHPExcel_1 = null;

echo "TEST1";
//include_once("read_master_file.php"); 
include_once("read_sent_file.php");
include_once("read_violated_file.php");
include_once("update_sent_file.php");
include_once("create_hrly_excel_file.php");
include_once("create_violated_hrly_excel_msg.php");
include_once("action_hourly_report_wockhardt.php");

include_once("create_last_halt_time.php");
include_once("read_last_halt_time.php");
include_once("read_last_processed_time.php");
include_once("update_last_halt_time.php");

include_once("update_last_processed_time.php");
include_once("delete_file.php");

$sent_root_path = $abspath."/hourly_report/".$user_name."/sent_file";
$sent_violated_root_path = $abspath."/hourly_report/".$user_name."/sent_violated_file";

$sent_file_path = $sent_root_path."/HOURLY_MAIL_VTS_REPORT_WOCKHARDT.xlsx";
$sent_violated_path = $sent_violated_root_path."/HOURLY_MAIL_VTS_REPORT_WOCKHARDT.xlsx";

$last_processed_time_path = $sent_root_path."/last_processed_time.xlsx";
$last_halt_time_path = $sent_root_path."/last_halt_time.xlsx";

$sent_sub_vehicle_path = $sent_root_path."/sub_account_vehicle_list.xlsx";

echo "TEST2";
include_once("get_village_db_detail.php");
include_once("get_vehicle_db_detail.php");
//include_once("process_data.php");

$date = date('Y-m-d');
//$next_date = date('Y-m-d', strtotime($date .' +1 day'));		//CHECK 12 JUNE EVENING REPORT PENDING

$unchanged = true;
//######## MAKE SHIFTS
//$date = "2014-02-13";
$current_date1 = $date." 08:00:00";
$current_date2 = $date." 19:00:00";
//$current_date2 = $date." 23:00:00";

//$date_to = $date." 22:00:00";

$current_time = date('Y-m-d H:i:s');
//$current_time = "2014-02-13 14:00:00";

$shift_ev = false;
$valid_shift = false;

//############## CHECK VALID SHIFT #############################
//echo "\ncurrent_time=".$current_time.",shift_ev_date1=".$shift_ev_date1.", shift_ev_date2=".$shift_ev_date2;

//echo "\ncurrent_time=".$current_time.",valid_shift_date1=".$date_from.", valid_shift_date2=".$date_to;
if( ($current_time >= $current_date1) && ($current_time <= $current_date2) )
{
	$valid_shift = true;
	echo "\nValid-Shift";
}
else
{
	//## DELETE MORNING FILE -IF SHIFT IS OVER		 
	//$shift = "mor";			
	if(file_exists($sent_file_path)) delete_file($sent_file_path);		
	//delete_file($sent_file_path); //MOVE FILE BY CREATING DATE FOLDER
	if(file_exists($last_processed_time_path)) delete_file($last_processed_time_path);
	if(file_exists($last_halt_time_path)) delete_file($last_halt_time_path);
}
//############ VALID SHIFT CLOSED ################################


echo "\nSTART".$valid_shift;
$transporter_m = array();
$vehicle_m = array();	
//########################## MORNING SHIFT STARTS #########################
//#########################################################################
//#### INITIALIZE ARRAYS
$unchanged = true;

/*$shift = array();			//MASTER FILE
$expected_customer_csv = array();
$expected_time_csv = array();
$vehicle_t = array();
$transporter = array();
$all_routes = array();

$route_input = array();
$vehicle_input = array();
$customer_input = array();
$shift_input = array();
$transporter_input = array();*/

$min_date_ev = array();
$max_date_ev = array();
$min_date_mor = array();
$max_date_mor = array();

//$vehicle_name_rdb = array();		//VEHICLE ROUTE DETAIL
//$vehicle_imei_rdb = array();
//$route_name_rdb = array();
$vehicle_id = array();
$vehicle_name = array();
$imei = array();

$date_from = array(array());
$date_to = array(array());
$min_operation_time = array(array());
$max_operation_time = array(array());
$by_day = array(array());
$day = array(array());
//$location_id_tmp = array();
//$base_station_id_tmp = array();
$vname = array(array());
$location_name = array(array());
$min_halt_time = array(array());
$max_halt_time = array(array());
$geo_point = array(array());
$base_station_id = array(array());
$base_station_name = array(array());
$base_station_coord = array(array());
//$base_station_expected_deptime = array(array());
//$base_station_expected_arrtime = array(array());
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

/*$Vehicle = array();			//SENT FILE
$SNo = array();
$StationNo = array();
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
$IMEI = array();*/

$VehicleName = array();
$SNo = array();
$VehicleID = array();	
$BaseStation = array();	
$BSCoordinate = array();	
$BSExpectedDeptTime = array();	
$BSExpectedArrTime = array();	
$VillageName = array();	
$VLCoordinate = array();	
$VLExpectedMinHaltDuration = array();	
$VLExpectedMaxHaltDuration = array();
$ActualBSDeptTime = array();	
$ActualBSArrTime = array();	
$DelayBSDept = array();	
$DelayBSArr	= array();
$ActualVLArrTime = array();	
$ActualVLDeptTime = array();	
$DelayVLArr = array();	
$DelayVLDept = array();	
$VLHaltDuration = array();
$VLHaltViolation = array();
$TotalDistanceTravelled = array();	
$IMEI = array();	
$Remark = array();
$SubVehicles = array();	//READ EVERYTIME
$sub_account_vehicles = array(); //DB TO FILE

$last_vehicle_name = array();		//LAST PROCESSED FILE
$last_halt_time = array();

//$last_time = $current_time;
$last_time_processed ="";
$csv_string_halt_final = "";

//$vehicle_arr = array();
//$customer_arr = array();
//$route_arr = array();

$total_route = array();
$total_customer = array();

//$type_arr = array();

$violate_flag = true;

$village_violate_msg = "";
$sub_village_violate_msg="";

$villag_flag = false;

$message1 = "";
$message2 = "";
$message3 = "";
$message4 = "";
//#################### IF SHIFT MORNING #########################
//$valid_shift = true; //comment
if($valid_shift)
{
	echo "\nMOR";
	//######## READ EVENING SENT FILE #############		
	if(file_exists($last_processed_time_path))
	{
		echo "\nLast Processed";
		read_last_processed_time($last_processed_time_path);
		read_last_halt_time($last_halt_time_path);
		//read_all_routes($account_id,"ZPMM");
		$Last_Time = $last_time_processed;
	}
	else
	{
		echo "\nElse";
		$Last_Time = $current_date1;
		//$Last_Time = "2013-10-07 19:00:00";
	}
			
	if (!file_exists($sent_file_path))
	{
		echo "\nCreateFile:Wockhardt";
		$last_processed_time = "";
		
		echo "\n".$last_processed_time_path."\n".$current_time."\n".$sent_file_path;
		get_vehicle_db_detail();		
		echo "\n1";
		get_village_db_detail();
		echo "\n2";
		
		//######## CREATE VEHICLE FILE
		create_hrly_sub_vehicles($sent_sub_vehicle_path);
		
		$objPHPExcel_1 = null;
		create_hrly_excel($sent_file_path, $Last_Time, $current_time);
		create_last_halt_time($last_halt_time_path);		
		echo "\nAfterCreatehourlyExcel";
	}

	$objPHPExcel_1 = null;	
	read_sent_file($sent_file_path);
	read_sub_vehicles($sent_sub_vehicle_path);
	echo "\nAfter ReadSentFile";
	get_halt_xml_data($Last_Time,$current_time, $sent_file_path);
	echo "\nAfter Data Process";
		
	//######### UPDATE LAST TIME PROCESSED -ALWAYS UPDATED #############	
	update_last_processed_time($last_processed_time_path, $current_time);
	update_last_halt_time($last_halt_time_path);
	echo "\nAfter Last ProcessedDetail:Morning";
	//#### LAST TIME PROCESSED CLOSED #############	
		
	//###### READ AGAIN ORIGINAL FILE AND SEND VIOLATED FIELDS
	$VehicleName = array();		//INITIALIZE VARIABLES AGAIN
	$SNo = array();
	$VehicleID = array();	
	$BaseStation = array();	
	$BSCoordinate = array();	
	$BSExpectedDeptTime = array();	
	$BSExpectedArrTime = array();	
	$VillageName = array();	
	$VLCoordinate = array();	
	$VLExpectedMinHaltDuration = array();	
	$VLExpectedMaxHaltDuration = array();
	$ActualBSDeptTime = array();	
	$ActualBSArrTime = array();	
	$DelayBSDept = array();	
	$DelayBSArr	= array();
	$ActualVLArrTime = array();	
	$ActualVLDeptTime = array();	
	$DelayVLArr = array();	
	$DelayVLDept = array();	
	$VLHaltDuration = array();
	$VLHaltViolation = array();
	$TotalDistanceTravelled = array();	
	$IMEI = array();
	$ReportRunDate = array();
	$ReportRunTime1	= array();
	$ReportRunTime2 = array();		
	$Remark = array();
	
	read_violated_records($sent_file_path);
	create_violated_hrly_excel_msg($sent_violated_path);		
	
	//############ SEND EMAIL -ALL USERS ##############
	//$to = 'asomvanshi@wockhardtfoundation.org,snangare@wockhardtfoundation.org,nbandikallu@wockhardtfoundation.org,viswabakthi@gmail.com,aloks@wockhardtfoundation.org';
	//$to = 'shams.parwez@iembsys.com';
	$to = 'asomvanshi@wockhardtfoundation.org,snangare@wockhardtfoundation.org,nbandikallu@wockhardtfoundation.org';
	$time_1 = date('Y-m-d H:i:s');
	$time_2 = strtotime($time_1);
	//$message = "";
		
	//echo "\nmessage1=".$message1;
	//echo "\nmessage2=".$message2;
	if($message1!="")
	{
		$current_time = date('Y-m-d H:i:s');
		$current_date = date('Y-m-d');
		$date_s = $current_date." 08:00:00";
		$date_e = $current_date." 10:00:00";
		
		if( ($current_time > $date_s) && ($current_time < $date_e) )
		{
			$subject = "WOCKHARDT_BS_DELAYED_ALERT_VTS_".$time_1."_".$time_2;
			$message1 .= "<br><br><font color=red size=1>*** This is an automatically generated email by the system on specified time, please do not reply ***</font>";
			$random_hash = md5(date('r', time()));
			$headers = "From: support@iembsys.co.in\r\n";
			$headers .= "Cc: hourlyreport4@gmail.com";
			$headers .= "\r\nContent-Type: text/html; charset=iso-8859-1; boundary=\"PHP-mixed-".$random_hash."\""; 
			mail($to, $subject, $message1, $headers);
		}
	}
	if($message2!="")
	{
		$subject = "WOCKHARDT_VIOLATION_ALERT_VTS_".$time_1."_".$time_2;
		$message2 .= "<br><br><font color=red size=1>*** This is an automatically generated email by the system on specified time, please do not reply ***</font>";
		$random_hash = md5(date('r', time()));
		$headers = "From: support@iembsys.co.in\r\n";
		$headers .= "Cc: hourlyreport4@gmail.com";
		$headers .= "\r\nContent-Type: text/html; charset=iso-8859-1; boundary=\"PHP-mixed-".$random_hash."\""; 
		mail($to, $subject, $message2, $headers);
	}	
	//######################################


	//###### MAIL SUB ACCOUNT VEHICLES ALERT TO DIFFERENT EMAIL_ID
	//############ SEND EMAIL ##############
	//$to = 'asomvanshi@wockhardtfoundation.org,snangare@wockhardtfoundation.org,ceo@wockhardtfoundation.org,nbandikallu@wockhardtfoundation.org';
	$to = 'ceo@wockhardtfoundation.org,viswabakthi@gmail.com,aloks@wockhardtfoundation.org';
	$time_1 = date('Y-m-d H:i:s');
	$time_2 = strtotime($time_1);
	//$message = "";
		
	//echo "\nmessage3=".$message3;
	//echo "\nmessage4=".$message4;
	if($message3!="")
	{
		$current_time = date('Y-m-d H:i:s');
		$current_date = date('Y-m-d');
		$date_s = $current_date." 08:00:00";
		$date_e = $current_date." 10:00:00";
		
		if( ($current_time > $date_s) && ($current_time < $date_e) )
		{
			$subject = "WOCKHARDT_BS_DELAYED_ALERT_VTS_".$time_1."_".$time_2;
			$message3 .= "<br><br><font color=red size=1>*** This is an automatically generated email by the system on specified time, please do not reply ***</font>";
			$random_hash = md5(date('r', time()));
			$headers = "From: support@iembsys.co.in\r\n";
			$headers .= "Cc: hourlyreport4@gmail.com";
			$headers .= "\r\nContent-Type: text/html; charset=iso-8859-1; boundary=\"PHP-mixed-".$random_hash."\""; 
			mail($to, $subject, $message3, $headers);
		}
	}
	if($message4!="")
	{
		$subject = "WOCKHARDT_VIOLATION_ALERT_VTS_".$time_1."_".$time_2;
		$message4 .= "<br><br><font color=red size=1>*** This is an automatically generated email by the system on specified time, please do not reply ***</font>";
		$random_hash = md5(date('r', time()));
		$headers = "From: support@iembsys.co.in\r\n";
		$headers .= "Cc: hourlyreport4@gmail.com";
		$headers .= "\r\nContent-Type: text/html; charset=iso-8859-1; boundary=\"PHP-mixed-".$random_hash."\""; 
		mail($to, $subject, $message4, $headers);
	}	
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

function get_halt_information()
{
}
?>
