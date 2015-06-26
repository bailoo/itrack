<?php
set_time_limit(18000);
//include_once('util_session_variable.php');
//include_once('util_php_mysql_connectivity.php');

//$HOST = "111.118.181.156";
include_once("../database_ip.php");
$DBASE = "iespl_vts_beta";
//$HOST = "localhost";
$USER = "root";
$PASSWD = "mysql";
$account_id = "568";
if($account_id == "568") $user_name = "tanker";
echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
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
//include_once($abspath."/select_landmark_report.php");
//include_once($abspath."/area_violation/check_with_range.php");
//include_once($abspath."/area_violation/pointLocation.php");
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
include_once("read_master_file.php"); 
include_once("read_sent_file.php");
include_once("update_sent_file.php");
include_once("create_hrly_excel_file.php");
include_once("action_hourly_report_halt.php");

include_once("create_last_halt_time.php");
include_once("read_last_halt_time.php");
include_once("read_last_processed_time.php");
include_once("update_last_halt_time.php");

include_once("update_last_processed_time.php");
include_once("delete_file.php");

$sent_root_path = $abspath."/hourly_report/".$user_name."/sent_file";

$evening_sent_file_path = $sent_root_path."/HOURLY_MAIL_VTS_HALT_REPORT_EVENING_MOTHER_TANKER.xlsx";
$morning_sent_file_path = $sent_root_path."/HOURLY_MAIL_VTS_HALT_REPORT_MORNING_MOTHER_TANKER.xlsx";

$evening_last_processed_time_path = $sent_root_path."/evening_last_processed_time.xlsx";
$morning_last_processed_time_path = $sent_root_path."/morning_last_processed_time.xlsx";

$evening_last_halt_time_path = $sent_root_path."/evening_last_halt_time.xlsx";
$morning_last_halt_time_path = $sent_root_path."/morning_last_halt_time.xlsx";

echo "TEST2";
include_once("get_customer_db_detail.php");
include_once("get_route_db_detail.php");
//include_once("process_data.php");

$date = date('Y-m-d');
//$next_date = date('Y-m-d', strtotime($date .' +1 day'));		//CHECK 12 JUNE EVENING REPORT PENDING
//$date = "2013-12-14";
$unchanged = true;
//######## MAKE TWO SHIFTS
$shift_ev_date1 = $date." 15:00:00";
$shift_ev_date2 = $date." 23:59:59";
$shift_ev_date3 = $date." 00:00:00";
$shift_ev_date4 = $date." 07:00:00";

$shift_mor_date1 = $date." 03:00:00";
$shift_mor_date2 = $date." 19:00:00";
//$shift_mor_date2 = $date." 22:00:00";

$current_time = date('Y-m-d H:i:s');
//$current_time = $date." 13:00:00";

$ev_run_start_time = $date." 20:00:00";
$mor_run_start_time = $date." 10:00:00";

$shift_ev = false;
$shift_mor = false;

//############## CHECK VALID SHIFT #############################
//echo "\ncurrent_time=".$current_time.",shift_ev_date1=".$shift_ev_date1.", shift_ev_date2=".$shift_ev_date2;

if( (($current_time >= $shift_ev_date1) && ($current_time <= $shift_ev_date2) && ($current_time >= $ev_run_start_time)) || (($current_time >= $shift_ev_date3) && ($current_time <= $shift_ev_date4)) )
{	
	//if($current_time >= $ev_run_start_time)
	//{
		$shift_ev = true;
		echo "\nEv-Shift";
	//}
}
else
{
	//## DELETE EVENING FILE -IF SHIFT IS OVER
	$shift = "ev";		
	if(file_exists($evening_sent_file_path)) delete_file($evening_sent_file_path);
	if(file_exists($evening_last_processed_time_path)) delete_file($evening_last_processed_time_path);
	if(file_exists($evening_last_halt_time_path)) delete_file($evening_last_halt_time_path);
}

//echo "\ncurrent_time=".$current_time.",shift_mor_date1=".$shift_mor_date1.", shift_mor_date2=".$shift_mor_date2;
if( ($current_time >= $shift_mor_date1) && ($current_time <= $shift_mor_date2) )
{
	if($current_time >= $mor_run_start_time)
	{
		$shift_mor = true;
		echo "\nMor-Shift";
	}
}
else
{
	//## DELETE MORNING FILE -IF SHIFT IS OVER		 
	$shift = "mor";			
	if(file_exists($morning_sent_file_path)) delete_file($morning_sent_file_path);
	if(file_exists($morning_last_processed_time_path)) delete_file($morning_last_processed_time_path);
	if(file_exists($morning_last_halt_time_path)) delete_file($morning_last_halt_time_path);
}
//############ VALID SHIFT CLOSED ################################


echo "\nSTART";

$transporter_m = array();
$vehicle_m = array();	
//########################## MORNING SHIFT STARTS #########################
//#########################################################################
//#### INITIALIZE ARRAYS
$sheet1_row = 2;
$sheet2_row = 2;

$unchanged = true;

$shift = array();			//MASTER FILE
$expected_customer_csv = array();
$expected_time_csv = array();
$vehicle_t = array();
$transporter = array();
$all_routes = array();

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
$plant_sel = array(array());
$transporter_sel = array(array());
$station_id = array(array());
$type = array(array());
$station_coord = array(array());
$distance_variable = array(array());

$expected_time_sel = array(array());	//FROM MASTER FILE

$vehicle_name_rdb = array();		//VEHICLE ROUTE DETAIL
$vehicle_imei_rdb = array();
$route_name_rdb = array();
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
$IMEI = array();

$last_vehicle_name = array();		//LAST PROCESSED FILE
$last_halt_time = array();
$last_halt_time_new = array();

//$last_time = $current_time;

$last_time_processed ="";
$csv_string_halt_final = "";

//$vehicle_arr = array();
//$customer_arr = array();
//$route_arr = array();

$total_route = array();
$total_customer = array();
//$type_arr = array();

//#################### IF SHIFT MORNING #########################
//$shift_mor = true; //comment
if($shift_mor)
{
	echo "\nMOR";
	//######## READ EVENING SENT FILE #############		
	if(file_exists($morning_last_processed_time_path))
	{
		echo "\nLast Processed";
		read_last_processed_time($morning_last_processed_time_path);
		echo "\nBefore Read LastHaltTime";
		read_last_halt_time($morning_last_halt_time_path);
		read_all_routes($account_id,"ZPMM");
		$Last_Time = $last_time_processed;
	}
	else
	{
		echo "\nElse";
		$Last_Time = $shift_mor_date1;
		//$Last_Time = "2013-10-07 19:00:00";
	}
			
	if (!file_exists($morning_sent_file_path))
	{
		echo "\nCreateFile:Morning";
		$morning_last_processed_time = "";
		
		get_route_db_detail("ZPMM");		
		echo "\nSizeRoute=".sizeof($route_name_rdb);
		get_customer_db_detail($account_id, "ZPMM");
		echo "\nSizeAllRoutes=".sizeof($all_routes);
		$objPHPExcel_1 = null;
		create_hrly_excel($morning_sent_file_path, "ZPMM");
		create_last_halt_time($morning_last_halt_time_path);		
		//echo "\n3";
	}

	$objPHPExcel_1 = null;	
	read_sent_file($morning_sent_file_path);
	echo "\nAfter ReadSentFile";
	get_halt_xml_data($Last_Time,$current_time, $morning_sent_file_path);
	echo "\nAfter Data Process";
		
	//######### UPDATE LAST TIME PROCESSED -ALWAYS UPDATED #############	
	update_last_processed_time($morning_last_processed_time_path, $current_time);
	update_last_halt_time($morning_last_halt_time_path);
	
	echo "\nAfter Last ProcessedDetail:Morning";
	//#### LAST TIME PROCESSED CLOSED #############
		
	//############ SEND EMAIL ##############
	//$to = 'rizwan@iembsys.com';	
	//$to = 'Amit.Patel@motherdairy.com';
	$to ='hourlyreportbvm@gmail.com';
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
	$subject = "HOURLY_MAIL_VTS_HALT_REPORT_MORNING(MOTHER_TANKER)_".$msg."_".$time_1."_".$time_2;
	$message = "HOURLY_MAIL_VTS_HALT_REPORT_MORNING(MOTHER_TANKER)_".$msg."_".$time_1."_".$time_2."<br><br><font color=red size=1>*** This is an automatically generated email by the system on specified time, please do not reply ***</font>";
	$random_hash = md5(date('r', time()));  
	$headers = "From: support@iembsys.co.in\r\n";
	$headers .= "Cc: hourlyreport4@gmail.com";
	//pass:8090025844  
	//$headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com,support1@iembsys.com,support2@iembsys.com";
	//$headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com";
	$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
	$filename_title = "HOURLY_MAIL_VTS_HALT_REPORT_MORNING_MOTHER_TANKER_".$msg."_".$time_1."_".$time_2.".xlsx";
	$file_path = $morning_sent_file_path;
	//echo "\nFILE PATH:Mor=".$file_path;
	include("send_mail_api.php");	
	//######################################	

}


//########################## EVENING SHIFT STARTS #########################
//#########################################################################
//#### INITIALIZE ARRAYS

$sheet1_row = 2;
$sheet2_row = 2;
$unchanged = true;

$shift = array();			//MASTER FILE
$expected_customer_csv = array();
$expected_time_csv = array();
$vehicle_t = array();
$transporter = array();
$all_routes = array();

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
$plant_sel = array(array());
$transporter_sel = array(array());
$station_id = array(array());
$type = array(array());
$station_coord = array(array());
$distance_variable = array(array());

$expected_time_sel = array(array());	//FROM MASTER FILE

$vehicle_name_rdb = array();		//VEHICLE ROUTE DETAIL
$vehicle_imei_rdb = array();
$route_name_rdb = array();
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
$IMEI = array();

$last_vehicle_name = array();		//LAST PROCESSED FILE
$last_halt_time = array();

//$last_time = $current_time;

$last_time_processed ="";
$csv_string_halt_final = "";

//$vehicle_arr = array();
//$customer_arr = array();
//$route_arr = array();

//$shift_ev = true;
//$Last_Time = "2013-10-07 15:00:00";
//$current_time = "2013-10-07 21:55:00";
//$current_time = "2013-10-07 15:00:00";

if($shift_ev)
{
	echo "\nEV";
	//######## READ EVENING SENT FILE #############		
	if(file_exists($evening_last_processed_time_path))
	{		
		read_last_processed_time($evening_last_processed_time_path);
		echo "\nLast ProcessedTime";
		read_last_halt_time($evening_last_halt_time_path);
		read_all_routes($account_id,"ZPME");
		echo "\nLast HaltTime";
		$Last_Time = $last_time_processed;
	}
	else
	{
		echo "\nElse";
		$Last_Time = $shift_ev_date1;
		//$Last_Time = "2013-10-07 15:00:00";
	}
			
	if (!file_exists($evening_sent_file_path))
	{
		echo "\nCreateFile:Evening";
		$evening_last_processed_time = "";
		
		get_route_db_detail("ZPME");		
		echo "\nSizeRoute=".sizeof($route_name_rdb);
		get_customer_db_detail($account_id, "ZPME");
		echo "\nSizeAllRoutes=".sizeof($all_routes);
		$objPHPExcel_1 = null;
		create_hrly_excel($evening_sent_file_path, "ZPME");
		create_last_halt_time($evening_last_halt_time_path);		
		//echo "\n3";
	}
	
	$objPHPExcel_1 = null;	
	read_sent_file($evening_sent_file_path);
	echo "\nAfter ReadSentFile";
	get_halt_xml_data($Last_Time,$current_time, $evening_sent_file_path);
	echo "\nAfter Data Process";	
		
	//######### UPDATE LAST TIME PROCESSED -ALWAYS UPDATED #############	
	update_last_processed_time($evening_last_processed_time_path, $current_time);
	update_last_halt_time($evening_last_halt_time_path);
	echo "\nAfter Last ProcessedDetail:Evening";
	//#### LAST TIME PROCESSED CLOSED #############
	
	//############ SEND EMAIL ##############
	//$to = 'rizwan@iembsys.com';	
	//$to = 'Amit.Patel@motherdairy.com'; 
	$to ='hourlyreportbvm@gmail.com';
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
	$subject = "HOURLY_MAIL_VTS_HALT_REPORT_EVENING(MOTHER_TANKER)_".$msg."_".$time_1."_".$time_2;
	$message = "HOURLY_MAIL_VTS_HALT_REPORT_EVENING(MOTHER_TANKER)_".$msg."_".$time_1."_".$time_2."<br><br><font color=red size=1>*** This is an automatically generated email by the system on specified time, please do not reply ***</font>";
	$random_hash = md5(date('r', time()));  
	$headers = "From: support@iembsys.co.in\r\n";
	$headers .= "Cc: hourlyreport4@gmail.com";  
	//pass:8090025844
	//$headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com,support1@iembsys.com,support2@iembsys.com";
	//$headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com";
	$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 	
	$filename_title = "HOURLY_MAIL_VTS_HALT_REPORT_EVENING_MOTHER_TANKER_".$msg."_".$time_1."_".$time_2.".xlsx";	
	$file_path = $evening_sent_file_path;

	//echo "\nFILE PATH:Ev=".$file_path; 	
	include("send_mail_api.php");	
	//######################################
}
		
//######### SHIFT EVENING CLOSED 

$last_halt_sec_global = 0;

function write_report($vserial, $vid, $vname, $startdate, $enddate)
{
	echo "\nIn WriteReport:vserial=".sizeof($vserial).", vname=".sizeof($vname).", startdate=".$startdate.", enddate=".$enddate;
	global $DbConnection;
	//global $startdate;
	//global $enddate;
	global $sno;
	global $overall_dist;

	global $csv_string_dist;
	global $csv_string_dist_arr;
	global $sno_dist;
	global $overall_dist;

	global $csv_string_halt;
	global $csv_string_halt_arr;
	global $total_halt_dur;
	global $sno_halt;

	global $csv_string_travel;
	global $csv_string_travel_arr;
	global $total_travel_dist;
	global $total_travel_time;
	global $sno_travel; 

	global $total_halt_dur; 

	global $customer_visited;
	global $shift;
	global $point;
	global $timing;
	global $vehicle_t;
	global $transporter;	

	global $route_input;
	global $vehicle_input;
	global $customer_input;	
	global $shift_input;	
	global $transporter_input;
  
	//####### GET LAST HALT DETAIL
	global $last_vehicle_name;		//LAST PROCESSED FILE	
	global $last_halt_time;
	
	global $last_halt_sec_global;
	
	$maxPoints = 1000;
	$file_exist = 0;
    	    
	//global $csv_string;
	//global $csv_string_arr;  
	echo "\nTotal Vehicles=".sizeof($vserial);
  
	for($i=0;$i<sizeof($vserial);$i++)
	{  	            
		$query1 = "SELECT vehicle_name FROM vehicle WHERE ".
		"vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
		"WHERE device_imei_no='$vserial[$i]' AND status=1) AND status=1";
		//echo "<br>".$query1;
		//echo "<br>DB=".$DbConnection;
		$result = mysql_query($query1,$DbConnection);
		$row = mysql_fetch_object($result);
		$vname[$i] = $row->vehicle_name;  
		//echo "\n vname=".$vname[$i];
		 
		//GET DISTANCE DATA
		$csv_string_dist = "";
		//$overall_dist = 0.0;
		$sno_dist = 1;		  		
		$total_halt_dur = 0;
		
		//echo "\nSerial = ".$s." :Vehicle:".$vname[$i]." ::Before";
		/*$shift = array();
		$point = array();
		$timing = array();*/
				
		$report_shift = "ZPME";
		$interval = 60;
				
		$last_vehicle_name;		//LAST PROCESSED FILE
		
		$last_halt_sec = 0;
		$interval = 60;
		
		//########## READ LAST HALT TIME
		$counter_update = 0;
		for($j=0;$j<sizeof($last_vehicle_name);$j++)
		{
			if(trim($vname[$i]) == trim($last_vehicle_name[$j]))
			{				
				$counter_update = $j;				
				break;
			}
		}
				
		if($counter_update > 0)
		{
			$last_halt_sec = $last_halt_time[$counter_update];
			if($last_halt_sec > 0)
			{			
				$interval = ($interval - $last_halt_sec);
			}	
		}
		
		echo "\nvserial=".$vserial[$i].", vid=".$vid[$i].", vname=".$vname[$i].", startdate=".$startdate.", enddate=".$enddate.", interval=".$interval.", report_shift=".$report_shift;
		
		get_halt_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $interval, $report_shift);
			
		//echo "\n\nHALT::vserial->  ".$vid[$i]." vname->".$vname[$i].", ".$csv_string_halt;
		$csv_string_halt_arr[$i] = $csv_string_halt;
		//echo "\nCSV_STRING_HALT=".$csv_string_halt_arr[$i]."\n";
		$csv_string_halt="";   
		
		$s = $i+1;
		echo "\nSerial = ".$s." :Vehicle:".$vname[$i]." ::Completed";
		
		//########## UPDATE LAST HALT TIME
		if($counter_update > 0)
		{						
			$last_halt_time[$counter_update] = $last_halt_sec_global;	
		}

		if(sizeof($last_vehicle_name)==0)
		{
			$last_vehicle_name[] = $vname[$i];		//LAST PROCESSED FILE
			$last_halt_time[] = $last_halt_sec_global;			
		}
	}	
}

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


