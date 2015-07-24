<?php

echo "Evening file";
set_time_limit(18000);
//include_once('util_session_variable.php');
//include_once('util_php_mysql_connectivity.php');

//$HOST = "111.118.181.156";
include_once("../../database_ip.php");
$DBASE = "iespl_vts_beta";
$USER = "root";
$PASSWD = "mysql";
$account_id = "322";
//echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

$abspath = "/var/www/html/vts/beta/src/php";
include_once($abspath."/common_xml_element.php");
include_once($abspath."/get_all_dates_between.php");
include_once($abspath."/sort_xml.php");
include_once($abspath."/calculate_distance.php");
include_once($abspath."/report_title.php");
include_once($abspath."/read_filtered_xml.php");
//include_once($abspath."/get_location.php");
include_once($abspath."/user_type_setting.php");
include_once($abspath."/select_landmark_report.php");
include_once($abspath."/area_violation/check_with_range.php");
include_once($abspath."/area_violation/pointLocation.php");
require_once $abspath."/excel_lib/class.writeexcel_workbook.inc.php";
require_once $abspath."/excel_lib/class.writeexcel_worksheet.inc.php";
include_once($abspath."/util.hr_min_sec.php");
include_once($abspath."/daily_report/motherdairy/mumbai/get_master_detail.php");
//echo "\nAfter Include";
//include_once($abspath."/get_location_lp_track_report.php");

//include_once($abspath."/mail_action_report_distance_1.php");
//include_once("mail_action_report_halt2.php");
//include_once($abspath."/mail_action_report_travel_1.php");

function tempnam_sfx($path, $suffix)
{
	$file = $path.$suffix;
	/*do
	{
		$file = $path.$suffix;
		$fp = @fopen($file, 'x');
	}
	while(!$fp);
	fclose($fp);*/
	return $file;
}

//echo "report4\n";
$csv_string_dist = "";                //INITIALISE  DISTANCE VARIABLES
$csv_string_dist_arr = array();
$sno_dist = 0;
$overall_dist = 0.0;

$csv_string_halt = "";                //INITIALISE  HALT VARIABLES
$csv_string_halt_arr = array();
$total_halt_dur = 0;
$sno_halt = 0;

$csv_string_travel = "";                //INITIALISE  TRAVEL VARIABLES
$csv_string_travel_arr = array();
$total_travel_dist = 0;
$total_travel_time = 0;
$sno_travel = 0;


$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".
                    "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
                    "vehicle_grouping.account_id='$account_id' AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1";			

/*
$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".
                    "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
                    "vehicle_grouping.account_id='$account_id' AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1 and vehicle.vehicle_id IN(1406)";					
*/
/*
$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".
                    "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
                    "vehicle_grouping.account_id='$account_id' AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1 limit 10";					
*/
				
//echo "\nquery=".$query_assignment."\n";
$result_assignment = mysql_query($query_assignment,$DbConnection);

while($row_assignment = mysql_fetch_object($result_assignment))
{
   $vehicle_id_a = $row_assignment->vehicle_id;
   $vehicle_name[] = $row_assignment->vehicle_name;
   
   $query_imei = "SELECT device_imei_no FROM vehicle_assignment WHERE vehicle_id ='$vehicle_id_a' AND status=1";
   $result_imei = mysql_query($query_imei, $DbConnection);
   $row_imei = mysql_fetch_object($result_imei);
   $device_imei_no[] = $row_imei->device_imei_no;
   $vid[] = $vehicle_id_a;  
}

/*$device_str = $_POST['vehicleserial'];
//echo "<br>devicestr=".$device_str;
$vserial = explode(':',$device_str);   */
$vsize=count($device_imei_no);

echo "\nVsize=".$vsize;

$date = date('Y-m-d');
$previous_date = date('Y-m-d', strtotime($date .' -1 day'));
	//$previous_date = date('Y-m-d', strtotime($date .' -3 day'));
	//$current_date = date('Y-m-d', strtotime($date .' -2 day'));

$current_date = $date;
/////////////////////////////////////////////
/*
//###### TEMPORARY ASSIGNMENT FOR TESTING
$previous_date = date('Y-m-d', strtotime($date .' -2 day'));
$current_date =  date('Y-m-d', strtotime($date .' -1 day'));
//#######################################
*/

//////////////////////////////////////////
$startdate = $previous_date." 08:00:00";               //TIME 8AM TO 8AM
$enddate = $current_date." 08:00:00"; 
/////////////////////////////////////////

$date1 = $startdate;
$date2 = $enddate;
$user_interval = "2";   //FIVE MINUTES

//$user_interval = 30*60;
//echo "user_interval=".$user_interval."<br>";
///////////////////////////////////////////////////////////////////////////////
//echo "<br>vsiz=".$vsize;

//############################################
$customer_visited = array();
global $shift;
$shift = array();
global $point;
$point = array();
global $timing;
$timing = array();
global $vehicle_t;
$vehicle_t = array();
global $transporter;
$transporter = array();

global $route_input;
$route_input = array();
global $vehicle_input;
$vehicle_input = array();
global $customer_input;
$customer_input = array();
global $customer_name_input;
$customer_name_input = array();
global $transporter_input;
$transporter_input = array();
global $shift_input;
$shift_input = array();

global $relative_plant_input;
global $relative_customer_input;
global $relative_customer_name_input;
global $relative_transporter_input;
global $relative_route_input;

$relative_plant_input = array();
$relative_customer_input = array();
$relative_customer_name_input = array();
$relative_transporter_input = array();
$relative_route_input = array();

$RouteNo2 = array();
$Plant2 = array();
$ScheduleInTime2 = array();
$ScheduleOutTime2 = array();

echo "\nbefore";
//######## GET MASTER DETAIL ################
$shift_time = "ZPME";
get_master_detail($account_id, $shift_time);        //GET SHIFT, POINT, TIMING, AND INPUT FILE RECORDS
//######### MASTER DETAIL CLOSED ############

global $min_date_ev;
$min_date_ev = "";
global $max_date_ev;
$max_date_ev = "";

//$startdate = $min_date_ev;
//$enddate = $max_date_ev; 

echo "\nAfter";
for($k=0;$k<sizeof($shift);$k++)
{    
  $customer_visited[$k] = 0;
}

$customer_input_string = "";
$customer_input_string2 = "";

echo "\nSize_Customer=".sizeof($customer_input);

for($k=0;$k<sizeof($customer_input);$k++)
{
	if($k==0)
	{
		$customer_input_string = $customer_input_string."".$customer_input[$k];
		$customer_input_string2 = $customer_input_string2." customer_no like '".$customer_input[$k]."@%'";
	}
	else
	{
		$customer_input_string = $customer_input_string.",".$customer_input[$k];
		$customer_input_string2 = $customer_input_string2." OR customer_no like '".$customer_input[$k]."@%'";
	}
}

$station_id = array();
$type = array();
$customer_no = array();
$station_name = array();
$station_coord = array();
$distance_variable = array();
$google_location = array();


//##### GET STATION COORDINATES -CUSTOMER ###############
$query2 = "SELECT DISTINCT station_id,type,customer_no,station_name,station_coord,distance_variable,google_location FROM station WHERE ".
            "user_account_id='$account_id' AND (customer_no IN(".$customer_input_string.") OR ".$customer_input_string2.") AND type=0 AND status=1";


//echo "\nQ1=".$query2;
$result2 = mysql_query($query2,$DbConnection); 

while($row2 = mysql_fetch_object($result2))
{
  $station_id[] = $row2->station_id;
  $type[] = $row2->type;
  $customer_no[] = $row2->customer_no;
  $station_name[] = $row2->station_name;
  //$station_coord_tmp =  $row2->station_coord;
  $station_coord[] = $row2->station_coord;
  $distance_variable[] = $row2->distance_variable;
  
  //$google_location[] = $row2->google_location;
  //$google_location[] = $placename1;
} 

//##### GET STATION COORDINATES -CUSTOMER ###############
$query2 = "SELECT DISTINCT station_id,type,customer_no,station_name,station_coord,distance_variable,google_location FROM station WHERE ".
            "user_account_id='$account_id' AND type=1 AND status=1";
//echo "\nQ2=".$query2;
$result2 = mysql_query($query2,$DbConnection); 

while($row2 = mysql_fetch_object($result2))
{
  $station_id[] = $row2->station_id;
  $type[] = $row2->type;
  $customer_no[] = $row2->customer_no;
  $station_name[] = $row2->station_name;
  //$station_coord_tmp =  $row2->station_coord;
  $station_coord[] = $row2->station_coord;
  $distance_variable[] = $row2->distance_variable;
  
  //$google_location[] = $row2->google_location;
  //$google_location[] = $placename1;
}   


if($vsize>0)
{
  echo "\nStations Size Before ::".sizeof($station_id); 
  include_once("mail_action_report_halt2.php"); 
  echo "\nAfter MAIL ACTION"; 
  write_report($device_imei_no, $vid, $vehicle_name, $user_interval);
  echo "\nStations Size After ::".sizeof($station_id); 
}

function write_report($vserial, $vid, $vname, $user_interval)
{
	//echo "\nIn Report:vname=".sizeof($vname);;
	global $DbConnection;
	global $startdate;
	global $enddate;
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
		
		$report_shift = "ZPMM";    
	 
		get_halt_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $user_interval, $report_shift);
			
		//echo "\n\nHALT::vserial->  ".$vid[$i]." vname->".$vname[$i].", ".$csv_string_halt;
		$csv_string_halt_arr[$i] = $csv_string_halt;
		//echo "\nCSV_STRING_HALT=".$csv_string_halt."\n";
		$csv_string_halt="";   
		
		$s = $i+1;
		echo "\nSerial = ".$s." :Vehicle:".$vname[$i]." ::Completed";
	}	
}

/*   
$vehicle_id_final = "";
$csv_string_dist_final ="";
$csv_string_halt_final ="";
$csv_string_travel_final =""; 
$vname_str ="";

 
for($j=0;$j<sizeof($vid);$j++)
{    
	$vehicle_id_final = $vehicle_id_final.$vid[$j].",";
	$vehicle_name_final = $vehicle_name_final.$vehicle_name[$j].",";
	$device_imei_no_final = $device_imei_no_final.$device_imei_no[$j].",";
	//$alert_id_final = $alert_id_final.$alert_db;
	//$csv_string_dist_final = $csv_string_dist_final.$csv_string_dist_arr[$j]."@";
	$csv_string_halt_final = $csv_string_halt_final.$csv_string_halt_arr[$j]."@";
	//$csv_string_travel_final = $csv_string_travel_final.$csv_string_travel_arr[$j]."@"; 		       		
}
$vehicle_id_final = substr($vehicle_id_final, 0, -1);
$vehicle_name_final = substr($vehicle_name_final, 0, -1);
$device_imei_no_final = substr($device_imei_no_final, 0, -1);
//$csv_string_dist_final = substr($csv_string_dist_final, 0, -1);
$csv_string_halt_final = substr($csv_string_halt_final, 0, -1);
//$csv_string_travel_final = substr($csv_string_travel_final, 0, -1); 
*/
    
function binary_plant_search($elem, $array, $array1, $array2, $array3, $array4) 	//elem = station to search, array = customer, array1 = plant
{
   $top = sizeof($array) -1;
   $bot = 0;
   while($top >= $bot) 
   {
      $p = floor(($top + $bot) / 2);
      if ($array[$p] < $elem) $bot = $p + 1;
      elseif ($array[$p] > $elem) $top = $p - 1;
      else return $array1[$p].":".$array2[$p].":".$array3[$p].":".$array4[$p];//return TRUE;
   }
   return "-";
}
    
//if($vehicle_id_final!="")
{
	//$inc_serial=$i+1;
	$inc_serial = rand();
	$filename_title = 'BETA_VTS_HALT_REPORT_(MOTHER_MUMBAI)_'.$previous_date."_".$inc_serial;
	$fullPath = "/var/www/html/vts/beta/src/php/download/".$filename_title;
	$fname = tempnam_sfx($fullPath, ".xls");
	$workbook =& new writeexcel_workbook($fname);
	
	$border1 =& $workbook->addformat();
	$border1->set_color('white');
	$border1->set_bold();
	$border1->set_size(9);
	$border1->set_pattern(0x1);
	$border1->set_fg_color('green');
	$border1->set_border_color('yellow');
	
	$text_format =& $workbook->addformat(array(
		bold    => 1,
		//italic  => 1,
		//color   => 'blue',
		size    => 10,
		//font    => 'Comic Sans MS'
	));
	
	$text_red_format =& $workbook->addformat(array(
		bold    => 1,
		//italic  => 1,
		color   => 'red',
		size    => 10,
		//font    => 'Comic Sans MS'
	));	
	
	$text_blue_format =& $workbook->addformat(array(
		bold    => 1,
		//italic  => 1,
		color   => 'blue',
		size    => 10,
		//font    => 'Comic Sans MS'
	));			

	$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy'));
	
	$blank_format = & $workbook->addformat();
	$blank_format->set_color('white');
	$blank_format->set_bold();
	$blank_format->set_size(12);
	$blank_format->set_merge();
			
	$vname_label = explode(',',$vehicle_name_final);     //**TOTAL VEHICLES 		

	$worksheet2 =& $workbook->addworksheet('Halt Report');
	$sheet2 = explode('@',$csv_string_halt_final);
	$r=0;   //row 
			
	$worksheet2->write($r, 0, "Vehicle", $text_format);							//0
	$worksheet2->write($r, 1, "SNo", $text_format);								//1	
	$worksheet2->write($r, 2, "Station No", $text_format);						//2
	$worksheet2->write($r, 3, "Type", $text_format);							//3
	$worksheet2->write($r, 4, "RouteNo", $text_format);							//4
	$worksheet2->write($r, 5, "ReportShift", $text_format);						//5
	$worksheet2->write($r, 6, "Arrival Date", $text_format);					//6
	$worksheet2->write($r, 7, "Arrival Time", $text_format);
	$worksheet2->write($r, 8, "Departure Date", $text_format);					//7
	$worksheet2->write($r, 9, "Departure Time", $text_format);
	$worksheet2->write($r, 10, "ScheduleTime", $text_format);					//8
	$worksheet2->write($r, 11, "Delay (Mins)", $text_format);					//9		
	//######################################################
	$worksheet2->write($r, 12, "Plant Schedule InTime", $text_format);					//12
	$worksheet2->write($r, 13, "Plant Schedule OutTime", $text_format);					//13
	$worksheet2->write($r, 14, "Plant Delay In (Mins)", $text_format);					//14
	$worksheet2->write($r, 15, "Plant Delay Out (Mins)", $text_format);					//15
	//######################################################
	$worksheet2->write($r, 16, "Halt Duration (Hr:min:sec)", $text_format);		//16
	$worksheet2->write($r, 17, "ReportDate1", $text_format);					//17
	$worksheet2->write($r, 18, "ReportTime1", $text_format);					//18
	$worksheet2->write($r, 19, "ReportDate2", $text_format);					//19
	$worksheet2->write($r, 20, "ReportTime2", $text_format);					//20
	$worksheet2->write($r, 21, "Transporter(M)", $text_format);					//21
	$worksheet2->write($r, 22, "Transporter(I)", $text_format);					//22
	$worksheet2->write($r, 23, "Plant", $text_format);							//23
	$worksheet2->write($r, 24, "Km", $text_format);								//24
	$worksheet2->write($r, 25, "CustomerName", $text_format);					//25

	$r++;

	//for($p=0;$p<sizeof($sheet2)-1;$p++)
	for($p=0;$p<sizeof($csv_string_halt_arr);$p++)
	{
		//echo "vehicle_name=".$vname_label[$p]."<br><br>escalation_id=".$escalation_id[$i]."<br><br>csv_string_dist_final".$sheet2[$p]."<br><br>";
		//$report_title_halt = "Halt Report -Vehicle:".$vname_label[$p]." -($date1 to $date2)";   //COMMENTED ON REQ
		//echo "report_title_halt=".$report_title_halt."<br>";			              
		//$r++;
				
		//############### REBUILD ARRAY - ELIMINATE MULTIPLE HALT ENTRIES ###################//
		//####### TEMPORARY ASSIGNMENT		
		//echo "\nHaltARR=".$csv_string_halt_arr[$p];
		
		if($csv_string_halt_arr[$p]!="")
		{
			//echo "\nAllRows=".$csv_string_halt_arr[$p];		
			$sheet2_row_tmp = explode('#',$csv_string_halt_arr[$p]);	
			
			//echo "\nSizeSheetRowTmp=".sizeof($sheet2_row_tmp);			
			for($i=0;$i<sizeof($sheet2_row_tmp);$i++)
			{
				//$data_flag = false;
				//$sheet2_data_main_string="";
				//echo "\nSheetDataTmp=".$sheet2_row_tmp[$i];				
				$sheet2_data_tmp = explode(',',$sheet2_row_tmp[$i]);
			 				
				$vname_temp[] = $sheet2_data_tmp[0];
				$sno_temp[] = $sheet2_data_tmp[1];				
				//echo "\nSNO1=".$sheet2_data_tmp[1];
				$station_no_temp[] = $sheet2_data_tmp[2];
				$type_str_temp[] = $sheet2_data_tmp[3];
				$route_no_temp[] = $sheet2_data_tmp[4];
				$report_shift_temp[] = $sheet2_data_tmp[5];
				$arrivale_time_temp[] = $sheet2_data_tmp[6];
				$depature_time_temp[] = $sheet2_data_tmp[7];
				$schedule_in_time_temp[] = $sheet2_data_tmp[8];
				$time_delay_temp[] = $sheet2_data_tmp[9];
				
				//###############################################
				$pschedule_in_time_temp[] = $sheet2_data_tmp[10];
				$pschedule_out_time_temp[] = $sheet2_data_tmp[11];
				$ptime_delay_temp1[] = $sheet2_data_tmp[12];
				$ptime_delay_temp2[] = $sheet2_data_tmp[13];
				//###############################################
				
				$hrs_min_temp[] = $sheet2_data_tmp[14];
				$report_date1_temp[] = $sheet2_data_tmp[15];
				$report_time1_temp[] = $sheet2_data_tmp[16];
				$report_date2_temp[] = $sheet2_data_tmp[17];
				$report_time2_temp[] = $sheet2_data_tmp[18];
				$transporter_name_master_temp[] = $sheet2_data_tmp[19];
				$transporter_name_input_temp[] = $sheet2_data_tmp[20];
				$relative_plant_temp[] = $sheet2_data_tmp[21];
				$km_temp[] = $sheet2_data_tmp[22];
				$customer_name_temp[] = $sheet2_data_tmp[23];	
				//echo "\nT_INPUT=".$sheet2_data_tmp[16];
			  //$sheet2_data_main_string=$sheet2_data_main_string.$sheet2_data_tmp[$m].",";
			}
		}
	}
		
	for($i=0;$i<sizeof($vname_temp);$i++)
	{	
		$mark[$i] = 0;	
	}
	
	$n =0 ;
	
	echo "\n";
	for($i=0;$i<sizeof($vname_temp);$i++)
	{				
		//echo "\nMark[$i]=".$mark[$i]." ,i=".$i;
		
		if($mark[$i] == 1)
		{
			//echo "\nMARK_I_ONE=".$i."\n";
			continue;
		}

		$vname[$n] = $vname_temp[$i];
		$sno[$n] = $sno_temp[$i];
		//echo "\nSNO2=".$sno[$n];
		$station_no[$n] = $station_no_temp[$i];
		$type_str[$n] = $type_str_temp[$i];
		$route_no[$n] = $route_no_temp[$i];
		$report_shift[$n] = $report_shift_temp[$i];
		$arrivale_time[$n] = $arrivale_time_temp[$i];
		$depature_time[$n] = $depature_time_temp[$i];
		$schedule_in_time[$n] = $schedule_in_time_temp[$i];
		$time_delay[$n] = $time_delay_temp[$i];
		
		//###############################################
		$pschedule_in[$n] = $pschedule_in_time_temp[$i];
		$pschedule_out[$n] = $pschedule_out_time_temp[$i];
		$ptime_delay1[$n] = $ptime_delay_temp1[$i];
		$ptime_delay2[$n] = $ptime_delay_temp2[$i];
		//###############################################
		
		$hrs_min[$n] = $hrs_min_temp[$i];
		$report_date1[$n] = $report_date1_temp[$i];
		$report_time1[$n] = $report_time1_temp[$i];
		$report_date2[$n] = $report_date2_temp[$i];
		$report_time2[$n] = $report_time2_temp[$i];
		$transporter_name_master[$n] = $transporter_name_master_temp[$i];
		$transporter_name_input[$n] = $transporter_name_input_temp[$i];
		$relative_plant[$n] = $relative_plant_temp[$i];
		$km[$n] = $km_temp[$i];	
		$customer_name[$n] = $customer_name_temp[$i];	

		//echo "\nT_INPUT2=".$transporter_name_input[$n];
		
		//echo "\n\n";
		for($j=1;$j<100;$j++)
		{
			$ji_sum = $j + $i;
			
			if(($vname_temp[$ji_sum])!=$vname_temp[$ji_sum-1])
			{
				break;
			}			
			//echo "\nI=".$i." ,JI_SUM=".$ji_sum;
			
			if( $ji_sum < sizeof($vname_temp))
			{
				/*echo "\n\nONE::";
				echo "\narrivale_time_temp[ji_sum]=".$arrivale_time_temp[$ji_sum]." ,depature_time_temp[i]=".$depature_time_temp[$i];
				echo "\nvname_temp[$ji_sum]=".$vname_temp[$ji_sum]." ,vname_temp[$i]=".$vname_temp[$i];
				echo "\nstation_no_tmp[$ji_sum]=".$station_no_temp[$ji_sum]." ,station_no_temp[$i]=".$station_no_temp[$i];
				echo "\ntype_str_temp[$ji_sum]=".$type_str_temp[$ji_sum]." ,type_str_temp[$i]=".$type_str_temp[$i];
				echo "\nroute_no_temp[$ji_sum]=".$route_no_temp[$ji_sum]." ,route_no_temp[$i]=".$route_no_temp[$i]."\n";*/
				
				if( ($vname_temp[$ji_sum] == $vname_temp[$i]) && ($station_no_temp[$ji_sum] == $station_no_temp[$i]) && ($type_str_temp[$ji_sum] == $type_str_temp[$i]) && ($route_no_temp[$ji_sum] == $route_no_temp[$i]) )
				{
					//echo "\nFilter Vehicle Matched2";
					//echo "\narrivale_time_temp[ji_sum]=".$arrivale_time_temp[$ji_sum]." ,depature_time_temp[i]=".$depature_time_temp[$i];
					
					$diff = strtotime($arrivale_time_temp[$ji_sum]) - strtotime($depature_time_temp[$i]);
					//echo "\nDIF==".$diff;
					//if($diff < 600)
					if($diff < 3600)
					{
						/*echo "\n\nFOUND::i=".$i." ,j_sum=".$ji_sum."\n";
						echo "\narrivale_time_temp[ji_sum]=".$arrivale_time_temp[$ji_sum]." ,depature_time_temp[i]=".$depature_time_temp[$i];
						echo "\nvname_temp[$ji_sum]=".$vname_temp[$ji_sum]." ,vname_temp[$i]=".$vname_temp[$i];
						echo "\nstation_no_tmp[$ji_sum]=".$station_no_temp[$ji_sum]." ,station_no_temp[$i]=".$station_no_temp[$i];
						echo "\ntype_str_temp[$ji_sum]=".$type_str_temp[$ji_sum]." ,type_str_temp[$i]=".$type_str_temp[$i];
						echo "\nroute_no_temp[$ji_sum]=".$route_no_temp[$ji_sum]." ,route_no_temp[$i]=".$route_no_temp[$i];*/
						
						$depature_time[$n] = $depature_time_temp[$ji_sum];
						$depature_time_temp[$i] = $depature_time_temp[$ji_sum];
						$mark[$ji_sum] = 1;
					}
				}
			}
		}
		$n++;
	} 			
	//}

	//########### STORE FINAL ARRAY 
	$csv_string_halt_final = "";
	$substr_count = 0;
	//echo "\nFinal Size Vname=".sizeof($vname);
	echo "\n";
	$sno_tmp = 1;
	
	echo "VVSIZE=".sizeof($vname);
	for($i=0;$i<sizeof($vname);$i++)
	{						
		//echo "\nT_INPUT3=".$transporter_name_input[$i];
		//echo "\nSNO2=".$sno[$i];		
		$date_obj1 = strtotime($report_date1[$i]);
		$report_date1[$i] = date('d-m-Y',$date_obj1);
		//$report_date1[$i] = intval($date_obj1 / 86400 + 25569);			
		
		$date_obj2 = strtotime($report_date2[$i]);
		$report_date2[$i] = date('d-m-Y',$date_obj2);
		//$report_date2[$i] = intval($date_obj2 / 86400 + 25569);	
		
		$arrival_tmp = explode(" ",$arrivale_time[$i]);
		$departure_tmp = explode(" ",$depature_time[$i]);
		
		if($arrival_tmp[1]!="" || $departure_tmp[1]!="") 
		{	
			/*if(($vname_prev == $vname[$i]) && ($station_no_prev == $station_no[$i]) && ($type_str_prev == $type_str[$i]) && ($report_shift_prev == $report_shift[$i]) && ($route_no_prev == $route_no[$i]) && ($arrivale_time_prev == $arrivale_time[$i]))
			{
				continue;
			}*/
			if($route_no[$i]=="")	$route_no[$i]="-";
			if($arrivale_time[$i]=="") $arrivale_time[$i]="-";
			if($depature_time[$i]=="")	$depature_time[$i]="-";
			if($schedule_in_time[$i]=="")	$schedule_in_time[$i]="-";
			if($time_delay[$i]=="")	$time_delay[$i]="-";
			
			if($pschedule_in[$i] == "") $pschedule_in[$i]="-";
			if($pschedule_out[$i] == "") $pschedule_out[$i]="-";
			if($ptime_delay1[$i] == "") $ptime_delay1[$i]="-";
			if($ptime_delay2[$i] == "") $ptime_delay2[$i]="-";
			
			if($hrs_min[$i]=="")	$hrs_min[$i]="-";
			if($report_date1[$i]=="")	$report_date1[$i]="-";
			if($report_time1[$i]=="")	$report_time1[$i]="-";
			if($report_date2[$i]=="")	$report_date2[$i]="-";
			if($report_time2[$i]=="")	$report_time2[$i]="-";
			if($transporter_name_master[$i]=="")	$transporter_name_master[$i]="-";
			if($transporter_name_input[$i]=="")	$transporter_name_input[$i]="-";
			if($relative_plant[$i]=="")	$relative_plant[$i]="-";			
			if($km[$i]=="")	$km[$i]="-";
			if($customer_name[$i]=="")	$customer_name[$i]="-";
			
			if($vname_prev != $vname[$i])
			{
				$sno_tmp = 1;
				$sno[$i] = $sno_tmp;
			}
			else
			{
				$sno[$i] = $sno_tmp;
			}
						
			$halt_duration = strtotime($depature_time[$i]) - strtotime($arrivale_time[$i]);
			$hms_2 = secondsToTime($halt_duration);
			$hrs_min[$i] = $hms_2[h].":".$hms_2[m].":".$hms_2[s];			 			  
			
			//echo "\nSize:RelativeCustomer=".sizeof($relative_customer_input)." ,Size:RelativePlant=".sizeof($relative_plant_input);
			//echo "\nT_INPUT4=".$transporter_name_input[$i];
			
			//###### GET RELATIVE PLANT, KMs DETAIL OF INPUT EVENING FILE			
			$relative_tmp_string = "";
			$relative_plants = "-";
			$relative_customer_name = "-";				
			$relative_transporters = "-";
			$relative_routes = "-";
			$flag1 = false;
											
			if($type_str[$i]!="Plant")
			{
				$pos_c = strpos($station_no[$i], "@");
				if($pos_c !== false)
				{
					//echo "\nNegative Found";
					$customer_at_the_rateA = explode("@", $station_no[$i]);											
				}
				else
				{
					$customer_at_the_rateA[0] = $station_no[$i];
				}												

				$relative_tmp_string = binary_plant_search($customer_at_the_rateA[0], $relative_customer_input, $relative_customer_name_input, $relative_plant_input, $relative_transporter_input, $relative_route_input);				
				$relative_tmp_string_arr = explode(":",$relative_tmp_string);
				//echo "\nRelativeTMPStr=".$relative_tmp_string."\n";				
				$relative_customer_name = $relative_tmp_string_arr[0];
				$relative_plants = $relative_tmp_string_arr[1];
				$relative_transporters = $relative_tmp_string_arr[2];				
				$relative_routes = $relative_tmp_string_arr[3];
				//echo "\nrelative_plants1=".$relative_plants." ,relative_transporters=".$relative_transporters." ,relative_routes=".$relative_routes;
			}
			else
			{
				$relative_routes = $route_no[$i];
			}
			
			$km[$i] = round($km[$i],2);
			//#########################################################################
					
			//echo "\nrelative_plants2=".$relative_plants." ,relative_transporters=".$relative_transporters." ,relative_routes=".$relative_routes." ,relative_customer_name=".$relative_customer_name;			
			if($substr_count == 0)
			{											
				$csv_string_halt_final = $csv_string_halt_final.$vname[$i].','.$sno[$i].','.$station_no[$i].','.$type_str[$i].','.$relative_routes.','.$report_shift[$i].','.$arrivale_time[$i].','.$depature_time[$i].','.$schedule_in_time[$i].','.$time_delay[$i].','.$pschedule_in[$i].','.$pschedule_out[$i].','.$ptime_delay1[$i].','.$ptime_delay2[$i].','.$hrs_min[$i].','.$report_date1[$i].','.$report_time1[$i].','.$report_date2[$i].','.$report_time2[$i].','.$transporter_name_master[$i].','.$relative_transporters.','.$relative_plants.','.$km[$i].",".$relative_customer_name;
				$substr_count =1;  
			}
			else
			{
				$csv_string_halt_final = $csv_string_halt_final."#".$vname[$i].','.$sno[$i].','.$station_no[$i].','.$type_str[$i].','.$relative_routes.','.$report_shift[$i].','.$arrivale_time[$i].','.$depature_time[$i].','.$schedule_in_time[$i].','.$time_delay[$i].','.$pschedule_in[$i].','.$pschedule_out[$i].','.$ptime_delay1[$i].','.$ptime_delay2[$i].','.$hrs_min[$i].','.$report_date1[$i].','.$report_time1[$i].','.$report_date2[$i].','.$report_time2[$i].','.$transporter_name_master[$i].','.$relative_transporters.','.$relative_plants.','.$km[$i].",".$relative_customer_name; 
			}			
			
			//echo "\nCSV_STRING=".$csv_string_halt_final;
			
			$vname_prev = $vname[$i];
			$station_no_prev = $station_no[$i];
			$type_str_prev = $type_str[$i];
			$report_shift_prev = $report_shift[$i];
			$route_no_prev = $relative_routes;
			$arrivale_time_prev = $arrivale_time[$i];			
			$sno_tmp++;			
		}
	}
	//############################################################################################
	
	//$excel_date_format =& $workbook->addformat(array(num_format => ' d mmmm yyy'));
	//$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy'));
	$excel_time_format = & $workbook->addformat(array(num_format => 'hh:mm:ss'));
	
	//####### FINAL ASSIGNMENT
	$data_flag = false;
	
	//echo "\nCSV_STRING=".$csv_string_halt_final."\n";
	
	$tmpclr=0;
	$prevVehicle="";
	$sheet2_row = explode('#',$csv_string_halt_final);        
	
	$prev_vehicles_arr = array();
	$prev_customers_arr = array();
	$p = 0;
	$EntryCnt=0;
	
	for($q=0;$q<sizeof($sheet2_row);$q++)
	{
	    $data_flag = false;
		$sheet2_data_main_string="";
		$sheet2_data = explode(',',$sheet2_row[$q]);
		$c = 0;		
		$repeat_vehicle_customer = false;
		
		if($sheet2_data[0]!=$prevVehicle)
		{
			//echo"Test0\n";
			$tmpclr=0;
			$EntryCnt=0;
			$prev_vehicles_arr[$EntryCnt] = $sheet2_data[0];
			$prev_customers_arr[$EntryCnt] = $sheet2_data[2];			
		}
		else
		{
			$prev_vehicles_arr[$EntryCnt] = $sheet2_data[0];
			$prev_customers_arr[$EntryCnt] = $sheet2_data[2];
		}
		
		//echo "\nEntryCount=".$EntryCnt;		
		if($EntryCnt>0)
		{
			for($k=0;$k<$EntryCnt;$k++)
			{
				if( ($sheet2_data[0] == $prev_vehicles_arr[$k]) && ($sheet2_data[2] == $prev_customers_arr[$k]) )
				{
					$repeat_vehicle_customer = true;
					break;
				}
			}
		}
		$EntryCnt++;
				
		$prevVehicle = $sheet2_data[0];
		$prevCustomer = $sheet2_data[2];
		
		//echo "\nq=".$q." ,repeat_vehicle_customer=".$repeat_vehicle_customer." ,tmpclr0=".$tmpclr."\n";
				
		if($tmpclr==1)
		{		
			//echo"RED\n";
			$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color' => 0x0A,'pattern' => 1,'border' => 1));
			$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color' => 0x0A,'pattern' => 1,'border' => 1));
			$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x0A,'pattern' => 1,'border'   => 1));
		}
		else if ( ($sheet2_data[19]!=$sheet2_data[20]) && (trim($sheet2_data[3])!="Plant") )				//TRANSOPORTER NOT MATCHED(15 AND 16-LIGHT BLUE)
		{								
			//echo"BLUE\n";
			$transporter_arr = explode("/",$sheet2_data[20]);
			$transporter_arr1 = explode("/",$sheet2_data[19]);
			$transporterMatch=false;
			for($i=0;$i<sizeof($transporter_arr);$i++)
			{
				for($j=0;$j<sizeof($transporter_arr1);$j++)
				{
					//echo "\nTM=".$transporter_arr1[$j].",TI=".$transporter_arr[$i]."\n";
					if($transporter_arr1[$j]==$transporter_arr[$i])
					{
						//echo "Matched\n";
						$transporterMatch=true;
						break;
					}
				}
			}
			
			if($transporterMatch==false)
			{
				$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color' => 0x2C,'pattern' => 1,'border' => 1));
				$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color' => 0x2C,'pattern' => 1,'border' => 1));
				$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x2C,'pattern' => 1,'border'   => 1));	
			}
			else if($repeat_vehicle_customer)
			{
				//echo"YELLOW\n";
				$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color' => 0x2B,'pattern' => 1,'border' => 1));
				$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color' => 0x2B,'pattern' => 1,'border' => 1));
				$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x2B,'pattern' => 1,'border'   => 1));			
			}
			else
			{
				$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color' => 0x2D,'pattern' => 1,'border' => 1));
				$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color' => 0x2D,'pattern' => 1,'border' => 1));
				$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x2D,'pattern' => 1,'border'   => 1));
			}
			
		}		
		else if( ($repeat_vehicle_customer))
		{
			//echo"YELLOW\n";
			$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color' => 0x2B,'pattern' => 1,'border' => 1));
			$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color' => 0x2B,'pattern' => 1,'border' => 1));
			$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x2B,'pattern' => 1,'border'   => 1));			
		}		
		else
		{
			//echo"NORMAL\n";
			$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color' => 0x09,'pattern' => 1,'border' => 1));
			//echo"Test4\n";
			$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color' => 0x09,'pattern' => 1,'border' => 1));
			//echo"Test5\n";
			$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x09,'pattern' => 1,'border' => 1));
			//echo"Test6\n";
		}
		//echo "\nSHEET_COUNT=".sizeof($sheet2_data);
		
		for($m=0;$m<sizeof($sheet2_data);$m++)
		{           
			if( ($sheet2_data[$m]!="-") && ($sheet2_data[$m]!="") && ($sheet2_data[$m]!=" ") && ($sheet2_data[$m]!=null) )
			{					
				if($m==6 || $m==7)	//ARRIVAL, DEPARTURE DATE AND TIME
				{
					$datetime_tmp = explode(" ",$sheet2_data[$m]);									
					
					//echo "\nDATETIME=".$datetime_tmp[0];					
					if($datetime_tmp[0]!="-" && $datetime_tmp[1]!="-" && $datetime_tmp[0]!="" && $datetime_tmp[1]!="")
					{
						$date_obj1 = strtotime($datetime_tmp[0]);
						//$date_tmp = date('d/m/Y',$date_obj);
						$excel_date1 = intval( ($date_obj1+86400) / 86400 + 25569);
						$worksheet2->write($r,$c, $excel_date1, $excel_date_format);																						
						$c++;
						
						//$worksheet2->write($r,$c, $datetime_tmp[1]);												
						$tmp_date = "1970-01-01";
						$tmp_date = $tmp_date." ".$datetime_tmp[1];
						$time_obj1 = strtotime($tmp_date);
						$time_obj1 = $time_obj1 + 19800;
						//$date_tmp = date('d/m/Y',$date_obj);
						$excel_time1= $time_obj1 / 86400 + 25569;
						$worksheet2->write($r,$c, $excel_time1, $excel_time_format);										
						$c++;
						$tmp_date1 = "1970-01-01 05:30:00";
						if(($m==7) && ($current_date == $datetime_tmp[0]) && (strtotime($tmp_date)>strtotime($tmp_date1)) && ($tmpclr==0) && ($sheet2_data[3]=="Plant"))
						{
							//echo"Test3\n";
							$tmpclr=1;
						}
					}
				}
				else if($m==8)	//SCHEDULE TIME
				{
					$tmp_date = "1970-01-01";
					$tmp_date = $tmp_date." ".$sheet2_data[$m].":00";
					//echo "\nScheduleTime";
					$time_obj2 = strtotime($tmp_date);
					$time_obj2 = $time_obj2 + 19800;	//ADD 5:30
					//$date_tmp = date('d/m/Y',$date_obj);
					$excel_time2= $time_obj2 / 86400 + 25569;
					$worksheet2->write($r,$c, $excel_time2, $excel_time_format);										
					$c++;										
				}
				else if($m==9)	//DELAY TIME
				{
					$tmp_date = "1970-01-01";
					
					$pos = false;
					//echo "\nDELAY::sheet2_data[m]=".$sheet2_data[$m];
					$pos = strpos($sheet2_data[$m], "-");
					//echo "\nPOS=".$pos;
					
					if($pos !== false)
					{
						//echo "\nNegative Found";
						$sheet2_data[$m] = str_replace("-", "", $sheet2_data[$m]);
					}					
					$tmp_date = $tmp_date." ".$sheet2_data[$m];
					//echo "\nTMP_DATE9=".$tmp_date;
					$time_obj3 = strtotime($tmp_date);					
					$time_obj3 = $time_obj3 + 19800;	//ADD 5:30
					//IN MINUTES
					//$date_tmp = date('d/m/Y',$date_obj);
					if($pos !== false)
					{
						//echo "\nAdded Negative";
						//$excel_time3= "-".($time_obj3 / 86400 + 25569);
						//$excel_time3= "-".($time_obj3 / 86400 + 25569);
						$delay_mins = intval(-$time_obj3 / 60);
					}
					else
					{
						//echo "\nNo Negative Found";
						//$excel_time3= $time_obj3 / 86400 + 25569;
						$delay_mins = intval($time_obj3 / 60);
					}
					$worksheet2->write($r,$c, $delay_mins, $excel_normal_format);										
					$c++;										
				}
				else if($m==10)	//PLANT SCHEDULE IN-TIME
				{
					$tmp_date = "1970-01-01";
					$tmp_date = $tmp_date." ".$sheet2_data[$m].":00";
					//echo "\nScheduleTime";
					$time_obj2 = strtotime($tmp_date);
					$time_obj2 = $time_obj2 + 19800;	//ADD 5:30
					//$date_tmp = date('d/m/Y',$date_obj);
					$excel_time2= $time_obj2 / 86400 + 25569;
					$worksheet2->write($r,$c, $excel_time2, $excel_time_format);										
					$c++;										
				}
				else if($m==11)	// PLANT SCHEDULE OUT-TIME
				{
					$tmp_date = "1970-01-01";
					$tmp_date = $tmp_date." ".$sheet2_data[$m].":00";
					//echo "\nScheduleTime";
					$time_obj2 = strtotime($tmp_date);
					$time_obj2 = $time_obj2 + 19800;	//ADD 5:30
					//$date_tmp = date('d/m/Y',$date_obj);
					$excel_time2= $time_obj2 / 86400 + 25569;
					$worksheet2->write($r,$c, $excel_time2, $excel_time_format);										
					$c++;										
				}
				else if($m==12)	// PLANT DELAY IN-TIME
				{
					$tmp_date = "1970-01-01";
					
					$pos = false;
					//echo "\nDELAY::sheet2_data[m]=".$sheet2_data[$m];
					$pos = strpos($sheet2_data[$m], "-");
					//echo "\nPOS=".$pos;
					
					if($pos !== false)
					{
						//echo "\nNegative Found";
						$sheet2_data[$m] = str_replace("-", "", $sheet2_data[$m]);
					}					
					$tmp_date = $tmp_date." ".$sheet2_data[$m];
					//echo "\nTMP_DATE9=".$tmp_date;
					$time_obj3 = strtotime($tmp_date);					
					$time_obj3 = $time_obj3 + 19800;	//ADD 5:30
					//IN MINUTES
					//$date_tmp = date('d/m/Y',$date_obj);
					if($pos !== false)
					{
						//echo "\nAdded Negative";
						//$excel_time3= "-".($time_obj3 / 86400 + 25569);
						//$excel_time3= "-".($time_obj3 / 86400 + 25569);
						$delay_mins = intval(-$time_obj3 / 60);
					}
					else
					{
						//echo "\nNo Negative Found";
						//$excel_time3= $time_obj3 / 86400 + 25569;
						$delay_mins = intval($time_obj3 / 60);
					}
					$worksheet2->write($r,$c, $delay_mins, $excel_normal_format);										
					$c++;										
				}
				else if($m==13)	// PLANT DELAY OUT-TIME
				{
					$tmp_date = "1970-01-01";
					
					$pos = false;
					//echo "\nDELAY::sheet2_data[m]=".$sheet2_data[$m];
					$pos = strpos($sheet2_data[$m], "-");
					//echo "\nPOS=".$pos;
					
					if($pos !== false)
					{
						//echo "\nNegative Found";
						$sheet2_data[$m] = str_replace("-", "", $sheet2_data[$m]);
					}					
					$tmp_date = $tmp_date." ".$sheet2_data[$m];
					//echo "\nTMP_DATE9=".$tmp_date;
					$time_obj3 = strtotime($tmp_date);					
					$time_obj3 = $time_obj3 + 19800;	//ADD 5:30
					//IN MINUTES
					//$date_tmp = date('d/m/Y',$date_obj);
					if($pos !== false)
					{
						//echo "\nAdded Negative";
						//$excel_time3= "-".($time_obj3 / 86400 + 25569);
						//$excel_time3= "-".($time_obj3 / 86400 + 25569);
						$delay_mins = intval(-$time_obj3 / 60);
					}
					else
					{
						//echo "\nNo Negative Found";
						//$excel_time3= $time_obj3 / 86400 + 25569;
						$delay_mins = intval($time_obj3 / 60);
					}
					$worksheet2->write($r,$c, $delay_mins, $excel_normal_format);										
					$c++;										
				}				
				else if($m==14)	//HALT DURATION
				{
					$tmp_date = "1970-01-01";
					$tmp_date = $tmp_date." ".$sheet2_data[$m];
					//echo "\nHalt Duration10=".$tmp_date;
					$time_obj4 = strtotime($tmp_date);
					$time_obj4 = $time_obj4 + 19800;	//ADD 5:30
					//$date_tmp = date('d/m/Y',$date_obj);
					$excel_time4= $time_obj4 / 86400 + 25569;
					$worksheet2->write($r,$c, $excel_time4, $excel_time_format);										
					$c++;										
				}
				else if($m==16 || $m==18)	//REPORT TIME
				{																			
					$tmp_date = "1970-01-01";
					$tmp_date = $tmp_date." ".$sheet2_data[$m];
					//echo "\nReportTime12=".$tmp_date;
					$time_obj3 = strtotime($tmp_date);
					$time_obj3 = $time_obj3 + 19800;
					//$date_tmp = date('d/m/Y',$date_obj);
					$excel_time3= $time_obj3 / 86400 + 25569;
					$worksheet2->write($r,$c, $excel_time3, $excel_time_format);										
					$c++;										
				}				
				else if($m==15 || $m==17)	//REPORT DATE
				{																			
					$date_obj2 = strtotime($sheet2_data[$m]);
					//$date_tmp = date('d/m/Y',$date_obj);
					$excel_date2 = intval( ($date_obj2+86400) / 86400 + 25569);
					$worksheet2->write($r,$c, $excel_date2, $excel_date_format);					
					$c++;					
				}
				/*else if($m==7 || $m==9 || $m ==10 || $m ==11 || $m==12 || $m ==14 $m ==16)
				{
					$time_obj = strtotime($sheet2_data[$m]);
					//$date_tmp = date('d/m/Y',$date_obj);
					$excel_no3 = intval($time_obj / 86400 + 25569);
					$worksheet2->write($r,$c, $excel_no3, $excel_time_format);					
					$c++;										
				}*/				
				else
				{
					$worksheet2->write($r,$c, $sheet2_data[$m],$excel_normal_format);
					$c++;
				}
			}
			else
			{
				/*if($m==10)
				{
					echo "\nELSE-HaltValue=".$sheet2_data[$m];
				}
				*/
				//echo "::ELSE BLANK\n";
				if($m==6 || $m==7)
				{					
					$worksheet2->write($r,$c, "-",$excel_normal_format);
					$c++;
					$worksheet2->write($r,$c, "-",$excel_normal_format);
					$c++;					
				}
				else
				{
					$worksheet2->write($r,$c, "-",$excel_normal_format);
					$c++;	
				}										
			}
			$sheet2_data_main_string=$sheet2_data_main_string.$sheet2_data[$m].",";
			$data_flag = true;     
		}
		//echo "sheet2_data_main_string=".$sheet2_data_main_string."<br>";
		if($data_flag)
		{
			$r++;
		} 
	} 	
     
    //############ WRITE CUSTOMER NOT VISITED #####################
    $worksheet2->write($r, 0, "", $text_format);
    $worksheet2->write($r, 1, "", $text_format);
	//$worksheet2->write($r, 2, "Location", $text_format);
	$worksheet2->write($r, 2, "", $text_format);
	$worksheet2->write($r, 3, "", $text_format);
	$worksheet2->write($r, 4, "", $text_format);
	$worksheet2->write($r, 5, "", $text_format);
	$worksheet2->write($r, 6, "", $text_format);
	$worksheet2->write($r, 7, "", $text_format);
	$worksheet2->write($r, 8, "", $text_format);
	$worksheet2->write($r, 9, "", $text_format);
	$worksheet2->write($r, 10, "", $text_format);
	$worksheet2->write($r, 11, "", $text_format);		
	$worksheet2->write($r, 12, "", $text_format);
    $worksheet2->write($r, 13, "", $text_format);
    $worksheet2->write($r, 14, "", $text_format);
    $worksheet2->write($r, 15, "", $text_format);
    $worksheet2->write($r, 16, "", $text_format);
	$worksheet2->write($r, 17, "", $text_format);
	$worksheet2->write($r, 18, "", $text_format);
	$worksheet2->write($r, 19, "", $text_format);
	$worksheet2->write($r, 20, "", $text_format);
	$worksheet2->write($r, 21, "", $text_format);
	$worksheet2->write($r, 22, "", $text_format);
	$worksheet2->write($r, 23, "", $text_format);
    $r++;         


    $worksheet2->write($r, 0, "", $text_format);
    $worksheet2->write($r, 1, "", $text_format);
	//$worksheet2->write($r, 2, "Location", $text_format);
	$worksheet2->write($r, 2, "Customer Not Visited", $text_red_format);
	$worksheet2->write($r, 3, "Type", $text_red_format);
	$worksheet2->write($r, 4, "Route", $text_red_format);
	$worksheet2->write($r, 5, "", $text_format);
	$worksheet2->write($r, 6, "", $text_format);
	$worksheet2->write($r, 7, "", $text_format);
	$worksheet2->write($r, 8, "", $text_format);
	$worksheet2->write($r, 9, "", $text_format);
	$worksheet2->write($r, 10, "", $text_format);
	$worksheet2->write($r, 11, "", $text_format);		
	$worksheet2->write($r, 12, "", $text_format);
    $worksheet2->write($r, 13, "", $text_format);
    $worksheet2->write($r, 14, "", $text_format);
    $worksheet2->write($r, 15, "", $text_format);
    $worksheet2->write($r, 16, "", $text_format);
	$worksheet2->write($r, 17, "", $text_format);
	$worksheet2->write($r, 18, "", $text_format);
	$worksheet2->write($r, 19, "", $text_format);
	$worksheet2->write($r, 20, "", $text_format);
	$worksheet2->write($r, 21, "Transporter(I)", $text_red_format);
	$worksheet2->write($r, 22, "Plant", $text_red_format);
	$worksheet2->write($r, 23, "", $text_format);	
    $r++;         
            
    //####### IN DATABASE(PLOTTED) MAY OR MAY NOT IN MASTER : NOT VISITED
	for($m=0;$m<sizeof($station_coord);$m++)
    {    
		$found = false;

		$pos_c = strpos($customer_no[$m], "@");
		if($pos_c !== false)
		{
			//echo "\nNegative Found";
			$customer_at_the_rate = explode("@", $customer_no[$m]);
		}
		else
		{
			$customer_at_the_rate[0] = $customer_no[$m];
		}

		for($n=0;$n<sizeof($customer_visited);$n++)
		{
			$pos_c = strpos($customer_visited[$n], "@");
			if($pos_c !== false)
			{
				//echo "\nNegative Found";
				$customer_at_the_rate1 = explode("@", $customer_visited[$n]);
			}
			else
			{
				$customer_at_the_rate1[0] = $customer_visited[$n];
			}
			
			//if($customer_no[$m] == $customer_visited[$n])
			if(trim($customer_at_the_rate[0]) == trim($customer_at_the_rate1[0]))
			{
			  $found = true;
			  break;
			}
		}
		if(!$found)
		{
			if($type[$m]==0)
			$type_tmp = "Customer";
			else if($type[$m] ==1)
			$type_tmp= "Plant";

			$route_i = "-";
			$transporter_i = "-";
			$plant_i = "-";

			for($m2=0;$m2<sizeof($relative_customer_input);$m2++)
			{
				$pos_c = strpos($customer_no[$m], "@");
				//echo "\nPOS=".$pos;

				if($pos_c !== false)
				{
					//echo "\nNegative Found";
					$customer_at_the_rate = explode("@", $customer_no[$m]);
				}			

				if( ($customer_no[$m] == $relative_customer_input[$m2]) || (trim($customer_at_the_rate[0]) == trim($relative_customer_input[$m2])) )
				{		  
					$route_i = $relative_route_input[$m2];
					$transporter_i = $relative_transporter_input[$m2];
					$plant_i = $relative_plant_input[$m2];			  
					break;
				}
			}		
		  
			$worksheet2->write($r, 0, "", $text_format);
			$worksheet2->write($r, 1, "", $text_format);
			//$worksheet2->write($r, 2, "", $text_format);
			$worksheet2->write($r, 2, $customer_no[$m], $text_red_format);
			$worksheet2->write($r, 3, $type_tmp, $text_red_format);
			$worksheet2->write($r, 4, $route_i, $text_red_format);
			$worksheet2->write($r, 5, "", $text_format);
			$worksheet2->write($r, 6, "", $text_format);
			$worksheet2->write($r, 7, "", $text_format);
			$worksheet2->write($r, 8, "", $text_format);
			$worksheet2->write($r, 9, "", $text_format);
			$worksheet2->write($r, 10, "", $text_format);
			$worksheet2->write($r, 11, "", $text_format);		
			$worksheet2->write($r, 12, "", $text_format);
			$worksheet2->write($r, 13, "", $text_format);
			$worksheet2->write($r, 14, "", $text_format);
			$worksheet2->write($r, 15, "", $text_format);
			$worksheet2->write($r, 16, "", $text_format);
			$worksheet2->write($r, 17, "", $text_format);
			$worksheet2->write($r, 18, "", $text_format);
			$worksheet2->write($r, 19, "", $text_format);
			$worksheet2->write($r, 20, "", $text_format);
			$worksheet2->write($r, 21, $transporter_i, $text_red_format);
			$worksheet2->write($r, 22, $plant_i, $text_red_format);
			$worksheet2->write($r, 23, "", $text_format);
			$r++;         
		}
    }
		
     //####### IN MASTER NOT IN DATABASE : NOT VISITED
	for($m=0;$m<sizeof($relative_customer_input);$m++)					//COUNTER VARIABLES:: $M = MASTER, $N = VISITED, $D = DATABASE
    {    
		$found = false;
		for($n=0;$n<sizeof($customer_no);$n++)
		{
			$pos_c = strpos($customer_no[$m], "@");
			//echo "\nPOS=".$pos;

			if($pos_c !== false)
			{
				//echo "\nNegative Found";
				$customer_at_the_rate = explode("@", $customer_no[$m]);
			}
			else
			{
				$customer_at_the_rate[0] = $customer_no[$n];
			}
			
			if( ($relative_customer_input[$m] == $customer_at_the_rate[0]) || (trim($relative_customer_input[$m]) == trim($customer_at_the_rate[0])) )
			{		
			  $found = true;
			  break;
			}
		}		
		
		if(!$found)
		{			
			//echo "\nMasterCustomer=".$relative_customer_input[$m];
			/*if($type[$m]==0)
			  $type[$m] = "Customer";
			else if($type[$m] ==1)
			  $type[$m]= "Plant";*/
						
			$worksheet2->write($r, 0, "", $text_format);
			$worksheet2->write($r, 1, "", $text_format);
			//$worksheet2->write($r, 2, "", $text_format);
			$worksheet2->write($r, 2, intval($relative_customer_input[$m]), $text_blue_format);
			$worksheet2->write($r, 3, "Customer", $text_blue_format);
			$worksheet2->write($r, 4, $relative_route_input[$m], $text_blue_format);
			$worksheet2->write($r, 5, "", $text_format);
			$worksheet2->write($r, 6, "", $text_format);
			$worksheet2->write($r, 7, "", $text_format);
			$worksheet2->write($r, 8, "", $text_format);
			$worksheet2->write($r, 9, "", $text_format);
			$worksheet2->write($r, 10, "", $text_format);
			$worksheet2->write($r, 11, "", $text_format);		
			$worksheet2->write($r, 12, "", $text_format);
			$worksheet2->write($r, 13, "", $text_format);
			$worksheet2->write($r, 14, "", $text_format);
			$worksheet2->write($r, 15, "", $text_format);
			$worksheet2->write($r, 16, "", $text_format);
			$worksheet2->write($r, 17, "", $text_format);
			$worksheet2->write($r, 18, $relative_transporter_input[$m], $text_blue_format);
			$worksheet2->write($r, 19, $relative_plant_input[$m], $text_blue_format);
			$worksheet2->write($r, 20, "", $text_format);
			$r++;
		}
    }	
		
    //########### WRITE CUSTOMER NOT VISITED CLOSED ###############    	
   
	$workbook->close(); //CLOSE WORKBOOK
	//echo "\nWORKBOOK CLOSED"; 
 
	########### SEND MAIL ##############//
	//$to = 'rizwan@iembsys.com';
	$to = 'Logistics.Vashi@motherdairy.com, Hemanshu.Mundley@motherdairy.com, Anand.Arondekar@motherdairy.com, Vijay.Singh@motherdairy.com, ashish@iembsys.co.in';

	$subject = 'BETA::VTS_HALT_REPORT_(MOTHER_MUMBAI)_'.$previous_date;
	$message = 'BETA::VTS_HALT_REPORT_(MOTHER_MUMBAI)_'.$previous_date."<br><br><font color=red size=1>*** This is an automatically generated email by the system on specified time, please do not reply to this email***</font>"; 
	$random_hash = md5(date('r', time()));  
	$headers = "From: support@iembsys.co.in\r\n";
	//$headers .= "Cc: rizwan@iembsys.com";  
	$headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com,support1@iembsys.com,support2@iembsys.com";
	$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
	$filename_title = $filename_title.".xls";
	$file_path = $fullPath.".xls";

	//echo "\nFILE PATH=".$file_path;  
	include_once("send_mail_api.php");
	//################################//
/*	
	//############# CREATE FOLDER AND BACKUP FILES ########
	$sourcepath = $file_path;
	$dirPath = "excel_reports/".$previous_date;
	mkdir ($dirPath, 0755, false);
	$destpath = $dirPath."/".$filename_title;
	
	echo "\nSourcePath=".$sourcepath;
	echo "\nDestinationPath=".$destpath;
	
	copy($sourcepath,$destpath);	
	//########## BACKUP FILES CLOSED #######################
*/
	unlink($file_path); 
}
 
?>
