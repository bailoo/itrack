<?php
$DEBUG = 0;
echo "Evening file";
set_time_limit(18000);
ini_set('auto_detect_line_endings',TRUE);
//include_once('util_session_variable.php');
//include_once('util_php_mysql_connectivity.php');

//$HOST = "111.118.181.156";
include_once("../../database_ip.php");
$DBASE = "iespl_vts_beta";
$USER = "root";
$PASSWD = "mysql";
$account_id = "568";
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
include_once($abspath."/daily_report/motherdairy/tanker/get_master_detail_tanker.php");
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
                    "vehicle_grouping.account_id='$account_id' AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1 and vehicle.vehicle_id IN(2663,3727,3033)";
*/
/*
$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".
                    "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
                    "vehicle_grouping.account_id='$account_id' AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1 limit 4";		
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

//$date = date('Y-m-d');
//$previous_date = date('Y-m-d', strtotime($date .' -1 day'));		//CHECK 12 JUNE EVENING REPORT PENDING
//$current_date = $date;

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
global $date1_input;
$date1_input = array();
global $date2_input;
$date2_input = array();
global $customer_input;
$customer_input = array();
global $transporter_input;
$transporter_input = array();
global $shift_input;
$shift_input = array();

global $relative_plant_input;
global $relative_customer_input;
global $relative_transporter_input;
global $relative_route_input;

$relative_plant_input = array();
$relative_customer_input = array();
$relative_transporter_input = array();
$relative_route_input = array();

/*global $min_date_ev;
$min_date_ev = "";
global $max_date_ev;
$max_date_ev = "";*/



echo "\nbefore";
//######## GET MASTER DETAIL ################
//$account_id="231";
$shift_time = "ZPME";
get_master_detail($account_id, $shift_time);        //GET SHIFT, POINT, TIMING, AND INPUT FILE RECORDS
//######### MASTER DETAIL CLOSED ############

//$startdate = date("Y-m-d H:i:s", strtotime($min_date_ev));
//$enddate = date("Y-m-d H:i:s", strtotime($max_date_ev));

$startdate = $min_date_ev;
$enddate = $max_date_ev; 

$date1 = $startdate;
$date2 = $enddate;
$user_interval = "1";   //1 MINUTES

echo "\nSD=".$startdate." ,enddate=".$enddate;

echo "\nAfter";
for($k=0;$k<sizeof($shift);$k++)
{    
  $customer_visited[$k] = 0;
}

$customer_input_string = "";
$customer_input_string2 = "";


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

//echo "\n".$query2;
			/*			
$query2 = "SELECT DISTINCT station_id,type,customer_no,station_name,station_coord,distance_variable,google_location FROM station WHERE ".
            "user_account_id='$account_id' AND customer_no IN(71189) AND type=0 AND status=1";			
//echo "\nQ1=".$query2;
*/

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

//##### GET STATION COORDINATES -PLANT ###############
$query2 = "SELECT DISTINCT station_id,type,customer_no,station_name,station_coord,distance_variable,google_location FROM station WHERE ".
            "user_account_id='$account_id' AND type=1 AND status=1";
/*			
$query2 = "SELECT DISTINCT station_id,type,customer_no,station_name,station_coord,distance_variable,google_location FROM station WHERE ".
            "user_account_id='$account_id' AND customer_no IN(71189) AND type=1 AND status=1";			
//echo "\nQ2=".$query2;
*/
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
  include_once("mail_action_report_halt2_tanker.php"); 
  //echo "\nAfter MAIL ACTION"; 
  write_report($device_imei_no, $vid, $vehicle_name, $user_interval);
  //echo "\nStations Size After ::".sizeof($station_id); 
}

function write_report($vserial, $vid, $vname, $user_interval)
{
	global $DEBUG;
	echo "\nIn Report";
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
	global $date1_input;
	global $date2_input;
	global $customer_input;	
	global $shift_input;	
	global $transporter_input;
  
	$maxPoints = 1000;
	$file_exist = 0;
    	    
	//global $csv_string;
	//global $csv_string_arr;  
	//echo "\nTotal Vehicles=".sizeof($vserial);

	for($i=0;$i<sizeof($vserial);$i++)
	{
		$in_min_date = "3000-00-00 00:00:00";
		$in_max_date = "0000-00-00 00:00:00";
		
		$out_min_date = "3000-00-00 00:00:00";		
		$out_max_date = "0000-00-00 00:00:00";							
				
		//echo "\nSizeofVInput=".sizeof($vehicle_input)." ,vname=".$vname[$i];		
		for($j=0;$j<sizeof($vehicle_input);$j++)
		{			
			//echo "\nvname[i]=".$vname[$i]." ,vehicle_input[j]=".$vehicle_input[$j];			
			if( trim($vname[$i]) == trim($vehicle_input[$j]) )
			{
				//echo "\nMATCHED";
				$in_date_csv_secs = strtotime($date1_input[$j]);
				$in_min_date_secs = strtotime($in_min_date);
				$in_max_date_secs = strtotime($in_max_date);
				$out_date_csv_secs = strtotime($date2_input[$j]);
				$out_min_date_secs = strtotime($out_min_date);
				$out_max_date_secs = strtotime($out_max_date);
														
				if($in_date_csv_secs < $in_min_date_secs)				//IN DATE
				{
					$in_min_date = $date1_input[$j];
				}
				if($in_date_csv_secs > $in_max_date_secs)
				{
					$in_max_date = $date1_input[$j];
				}
												
				if($out_date_csv_secs < $out_min_date_secs)				//OUT DATE
				{															
					$out_min_date = $date2_input[$j]; 
				}
				if($out_date_csv_secs > $out_max_date_secs)
				{
					$out_max_date = $date2_input[$j];
				}										
			}			
		} //INNER FOR
		
		$date1_min[$i] = $in_min_date;
		$date1_max[$i] = $in_max_date;
		$date2_min[$i] = $out_min_date;
		$date2_max[$i] = $out_max_date;
		
	} //OUTER FOR	
 
	for($i=0;$i<sizeof($vserial);$i++)
	{  	            
		/*$query1 = "SELECT vehicle_name FROM vehicle WHERE ".
		"vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
		"WHERE device_imei_no='$vserial[$i]' AND status=1) AND status=1";
		//echo "<br>".$query1;
		//echo "<br>DB=".$DbConnection;
		$result = mysql_query($query1,$DbConnection);
		$row = mysql_fetch_object($result);				
		$vname[$i] = $row->vehicle_name;  */
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
	 
		get_halt_xml_data($vserial[$i], $vid[$i], $vname[$i], $date1_min[$i], $date2_max[$i], $user_interval, $report_shift);
			
		//echo "\n\nHALT::vserial->  ".$vid[$i]." vname->".$vname[$i].", ".$csv_string_halt;
		$csv_string_halt_arr[$i] = $csv_string_halt;
		if($DEBUG) echo "\nCSV_STRING_HALT=".$csv_string_halt."\n";
		
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
    
$cdate = date('Y-m-d');
//if($vehicle_id_final!="")
{
	//$inc_serial=$i+1;
	$inc_serial = rand();
	$filename_title = 'BETA_VTS_HALT_REPORT_(MOTHER_TANKER)_'.$cdate."_".$inc_serial;
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
	$worksheet2->write($r, 12, "Halt Duration (Hr:min:sec)", $text_format);		//10
	$worksheet2->write($r, 13, "ReportDate1", $text_format);					//11
	$worksheet2->write($r, 14, "ReportTime1", $text_format);					//12
	$worksheet2->write($r, 15, "ReportDate2", $text_format);					//13
	$worksheet2->write($r, 16, "ReportTime2", $text_format);					//14
	$worksheet2->write($r, 17, "Transporter(M)", $text_format);					//15
	$worksheet2->write($r, 18, "Transporter(I)", $text_format);					//16
	$worksheet2->write($r, 19, "Plant", $text_format);							//17
	$worksheet2->write($r, 20, "Km", $text_format);								//18

	$r++;

	//for($p=0;$p<sizeof($sheet2)-1;$p++)
	
	if($DEBUG) echo "\nSizeofCsv=".sizeof($csv_string_halt_arr);
	
	for($p=0;$p<sizeof($csv_string_halt_arr);$p++)
	{
		//echo "vehicle_name=".$vname_label[$p]."<br><br>escalation_id=".$escalation_id[$i]."<br><br>csv_string_dist_final".$sheet2[$p]."<br><br>";
		//$report_title_halt = "Halt Report -Vehicle:".$vname_label[$p]." -($date1 to $date2)";   //COMMENTED ON REQ
		//echo "report_title_halt=".$report_title_halt."<br>";			              
		//$r++;
				
		//############### REBUILD ARRAY - ELIMINATE MULTIPLE HALT ENTRIES ###################//
		//####### TEMPORARY ASSIGNMENT		
		
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
				$hrs_min_temp[] = $sheet2_data_tmp[10];
				$report_date1_temp[] = $sheet2_data_tmp[11];
				$report_time1_temp[] = $sheet2_data_tmp[12];
				$report_date2_temp[] = $sheet2_data_tmp[13];
				$report_time2_temp[] = $sheet2_data_tmp[14];
				$transporter_name_master_temp[] = $sheet2_data_tmp[15];
				$transporter_name_input_temp[] = $sheet2_data_tmp[16];
				$relative_plant_temp[] = $sheet2_data_tmp[17];
				$km_temp[] = $sheet2_data_tmp[18];					
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
	if($DEBUG) echo "\nsizeof(vname_temp)=".sizeof($vname_temp);
	
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
		$hrs_min[$n] = $hrs_min_temp[$i];
		$report_date1[$n] = $report_date1_temp[$i];
		$report_time1[$n] = $report_time1_temp[$i];
		$report_date2[$n] = $report_date2_temp[$i];
		$report_time2[$n] = $report_time2_temp[$i];
		$transporter_name_master[$n] = $transporter_name_master_temp[$i];
		$transporter_name_input[$n] = $transporter_name_input_temp[$i];
		$relative_plant[$n] = $relative_plant_temp[$i];
		$km[$n] = $km_temp[$i];												
		
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
	
	for($i=0;$i<sizeof($vname);$i++)
	{						
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
			if($hrs_min[$i]=="")	$hrs_min[$i]="-";
			if($report_date1[$i]=="")	$report_date1[$i]="-";
			if($report_time1[$i]=="")	$report_time1[$i]="-";
			if($report_date2[$i]=="")	$report_date2[$i]="-";
			if($report_time2[$i]=="")	$report_time2[$i]="-";
			if($transporter_name_master[$i]=="")	$transporter_name_master[$i]="-";
			if($transporter_name_input[$i]=="")	$transporter_name_input[$i]="-";
			if($relative_plant[$i]=="")	$relative_plant[$i]="-";			
			if($km[$i]=="")	$km[$i]="-";
			
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
						
			//###### GET RELATIVE PLANT, KMs DETAIL OF INPUT EVENING FILE			
			$relative_tmp_string = "";
			$relative_plants = "-";			
			$relative_transporters = "-";
			$relative_routes = "-";
			$flag1 = false;
											
			if($type_str[$i]!="Plant")
			{
				//*************************PD
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
								
				$relative_tmp_string = binary_plant_search($customer_at_the_rateA[0], $relative_customer_input, $relative_plant_input, $relative_transporter_input, $relative_route_input);				
				$relative_tmp_string_arr = explode(":",$relative_tmp_string);
				//echo "\nRelativeTMPStr=".$relative_tmp_string."\n";				
				$relative_plants = $relative_tmp_string_arr[0];
				$relative_transporters = $relative_tmp_string_arr[1];
				$relative_routes = $relative_tmp_string_arr[2];		
				//echo "\nrelative_plants1=".$relative_plants." ,relative_transporters=".$relative_transporters." ,relative_routes=".$relative_routes;
			}
			$km[$i] = round($km[$i],2);
			//#########################################################################
			
			//$relative_plants = "-";		//COMMENT IT -TEMPORARY
			//$km[$i] = "-";
			/*
			if($substr_count == 0)
			{											
				$csv_string_halt_final = $csv_string_halt_final.$vname[$i].','.$sno[$i].','.$station_no[$i].','.$type_str[$i].','.$route_no[$i].','.$report_shift[$i].','.$arrivale_time[$i].','.$depature_time[$i].','.$schedule_in_time[$i].','.$time_delay[$i].','.$hrs_min[$i].','.$report_date1[$i].','.$report_time1[$i].','.$report_date2[$i].','.$report_time2[$i].','.$transporter_name_master[$i].','.$transporter_name_input[$i].','.$relative_plants.','.$km[$i];
				$substr_count =1;  
			}
			else
			{
				$csv_string_halt_final = $csv_string_halt_final."#".$vname[$i].','.$sno[$i].','.$station_no[$i].','.$type_str[$i].','.$route_no[$i].','.$report_shift[$i].','.$arrivale_time[$i].','.$depature_time[$i].','.$schedule_in_time[$i].','.$time_delay[$i].','.$hrs_min[$i].','.$report_date1[$i].','.$report_time1[$i].','.$report_date2[$i].','.$report_time2[$i].','.$transporter_name_master[$i].','.$transporter_name_input[$i].','.$relative_plants.','.$km[$i]; 
			}
			*/
			//echo "\nrelative_plants2=".$relative_plants." ,relative_transporters=".$relative_transporters." ,relative_routes=".$relative_routes;
			
			if($substr_count == 0)
			{											
				$csv_string_halt_final = $csv_string_halt_final.$vname[$i].','.$sno[$i].','.$station_no[$i].','.$type_str[$i].','.$relative_routes.','.$report_shift[$i].','.$arrivale_time[$i].','.$depature_time[$i].','.$schedule_in_time[$i].','.$time_delay[$i].','.$hrs_min[$i].','.$report_date1[$i].','.$report_time1[$i].','.$report_date2[$i].','.$report_time2[$i].','.$transporter_name_master[$i].','.$relative_transporters.','.$relative_plants.','.$km[$i];
				$substr_count =1;  
			}
			else
			{
				$csv_string_halt_final = $csv_string_halt_final."#".$vname[$i].','.$sno[$i].','.$station_no[$i].','.$type_str[$i].','.$relative_routes.','.$report_shift[$i].','.$arrivale_time[$i].','.$depature_time[$i].','.$schedule_in_time[$i].','.$time_delay[$i].','.$hrs_min[$i].','.$report_date1[$i].','.$report_time1[$i].','.$report_date2[$i].','.$report_time2[$i].','.$transporter_name_master[$i].','.$relative_transporters.','.$relative_plants.','.$km[$i]; 
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
	//################# MAIN REPORT #########################//
	//#######################################################//
	$data_flag = false;
	
	//echo "\nCSV_STRING=".$csv_string_halt_final."\n";
	
	$tmpclr=0;
	$prevVehicle="";
	$sheet2_row = explode('#',$csv_string_halt_final);        
	
	$prev_vehicles_arr = array();
	$prev_customers_arr = array();
	$p = 0;
	$EntryCnt=0;
	
	$plant_trip_flag = false;
	
	$vehicle_out_plant = "";
	$plant_out = "";
	$vehicle_out_distance = 0.0;

	$vehicle_in_plant = "";
	$plant_in = "";
	$vehicle_in_distance = 0.0;
	
	$route_no_str = "";
	$transporter_m_str = "";
	$transporter_i_str = "";
	
	if($DEBUG) echo "\nsizeof(sheet2_row)=".sizeof($sheet2_row);
	
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
			
			//######## OFF THE PLANT DISTANCE FLAG 
			$plant_trip_flag = false;
			
			if( ($vehicle_out_plant == $vehicle_in_plant) && ($vehicle_out_plant!="" && $plant_out!="" && $vehicle_in_plant!="" && $plant_in!=""))
			{
				//### STORE PLANT DISTANCE INFORMATION
				$vehicle_pd[] = $vehicle_out_plant;
				$plant_out_pd[] = $plant_out;
				$plant_out_time_pd[] = $plant_out_time;
				$plant_in_pd[] = $plant_in;
				$plant_in_time_pd[] = $plant_in_time;
				$distance_pd[] = ($vehicle_in_distance - $vehicle_out_distance);
				$route_pd[] = $route_no_str;
				$transporter_m_pd[] = $transporter_m_str;
				$transporter_i_pd[] = $transporter_i_str;
				
				//##RESET VARIABLES
				$vehicle_out_plant = "";
				$plant_out = "";
				$plant_out_time = "";
				$vehicle_out_distance = 0.0;

				$vehicle_in_plant = "";
				$plant_in = "";
				$plant_in_time = "";
				$vehicle_in_distance = 0.0;
				$route_no_str ="";
				$transporter_m_str = "";
				$transporter_i_str = "";
			}
			$route_no_str = "";

			//####################################
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
						
		//######## CODE FOR PLANT DISTANCE				
		if( (trim($sheet2_data[3]) == "Plant") && (!$plant_trip_flag) )
		{
				$vehicle_out_plant = $sheet2_data[0];
				$plant_out = $sheet2_data[2];
				$plant_out_time = $sheet2_data[7];	//DEPARTURE TIME
				$vehicle_out_distance = $sheet2_data[18];
				$plant_trip_flag = true;
		}
		
		if( (trim($sheet2_data[3]) == "Customer") && ($plant_trip_flag) )
		{
			$route_no_str = $route_no_str.$sheet2_data[4]."/";
			$transporter_m_str = $transporter_m_str.$sheet2_data[15]."/";
			$transporter_i_str = $transporter_i_str.$sheet2_data[16]."/";
		}
		
		if( (trim($sheet2_data[3]) == "Plant") && ($plant_trip_flag) )
		{
			if($vehicle_out_plant == $sheet2_data[0])
			{
				$vehicle_in_plant = $sheet2_data[0];
				$plant_in = $sheet2_data[2];
				$plant_in_time = $sheet2_data[6];	//ARRIVAL TIME
				$vehicle_in_distance = $sheet2_data[18];				
			}
		}
		//################################		
		
		if($tmpclr==1)
		{		
			//echo"RED\n";
			$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color' => 0x0A,'pattern' => 1,'border' => 1));
			$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color' => 0x0A,'pattern' => 1,'border' => 1));
			$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x0A,'pattern' => 1,'border'   => 1));
			$color = "red";
		}
		else if ( ($sheet2_data[15]!=$sheet2_data[16]) && (trim($sheet2_data[3])!="Plant") )				//TRANSOPORTER NOT MATCHED(15 AND 16-LIGHT BLUE)
		{								
			//echo"BLUE\n";
			$transporter_arr = explode("/",$sheet2_data[16]);
			$transporter_arr1 = explode("/",$sheet2_data[15]);
			$transporterMatch=false;
			for($i=0;$i<sizeof($transporter_arr);$i++)
			{
				for($j=0;$j<sizeof($transporter_arr1);$j++)
				{
					//echo "T1=".$transporter_arr1[$j].",T2=".$transporter_arr[$i]."\n";
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
				//BLUE
				$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color' => 0x2C,'pattern' => 1,'border' => 1));
				$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color' => 0x2C,'pattern' => 1,'border' => 1));
				$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x2C,'pattern' => 1,'border'   => 1));
				$color = "blue";				
			}
			else if($repeat_vehicle_customer)
			{
				//echo"YELLOW\n";
				$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color' => 0x2B,'pattern' => 1,'border' => 1));
				$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color' => 0x2B,'pattern' => 1,'border' => 1));
				$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x2B,'pattern' => 1,'border'   => 1));
				$color = "yellow";				
			}
			else
			{
				$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color' => 0x2D,'pattern' => 1,'border' => 1));
				$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color' => 0x2D,'pattern' => 1,'border' => 1));
				$excel_normal_format =& $workbook->addformat(array('fg_color' =>0x2D,'pattern' => 1,'border'   => 1));
				$color = "pink";
			}			
		}		
		else if( ($repeat_vehicle_customer))
		{
			//echo"YELLOW\n";
			$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color' => 0x2B,'pattern' => 1,'border' => 1));
			$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color' => 0x2B,'pattern' => 1,'border' => 1));
			$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x2B,'pattern' => 1,'border'   => 1));
			$color = "yellow";
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
			$color = "white";
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
						$tmp_date1 = "1970-01-01 02:30:00";
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
				else if($m==10)	//HALT DURATION
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
				else if($m==12 || $m==14)	//REPORT TIME
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
				else if($m==11 || $m==13)	//REPORT DATE
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
			$vehicle_arr[$q] = $sheet2_data[0];	    //vehicle no
			$customer_arr[$q] = $sheet2_data[2];	//customer no
			$type_arr[$q] = $sheet2_data[3];
			$record_arr[$q] = $sheet2_row[$q];			
			$color_arr[$q] = $color;				
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
	$worksheet2->write($r, 18, "Transporter(I)", $text_red_format);
	$worksheet2->write($r, 19, "Plant", $text_red_format);
	$worksheet2->write($r, 20, "", $text_format);
	
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
					$customer_at_the_rate2 = explode("@", $customer_no[$m]);
				}
				else
				{
					$customer_at_the_rate2[0] = $customer_no[$m];
				}

				if(trim($customer_at_the_rate2[0]) == trim($relative_customer_input[$m2]))
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
			$worksheet2->write($r, 18, $transporter_i, $text_red_format);
			$worksheet2->write($r, 19, $plant_i, $text_red_format);
			$worksheet2->write($r, 20, "", $text_format);		
			$r++;         
		}
    }
		
    //####### IN MASTER NOT IN DATABASE : NOT VISITED
	for($m=0;$m<sizeof($relative_customer_input);$m++)		//COUNTER VARIABLES:: $M = MASTER, $N = VISITED, $D = DATABASE
    {    
		$found = false;
		for($n=0;$n<sizeof($customer_no);$n++)
		{
			$pos_c = strpos($customer_no[$n], "@");
			//echo "\nPOS=".$pos;

			if($pos_c !== false)
			{
				//echo "\nNegative Found";
				$customer_at_the_rate3 = explode("@", $customer_no[$n]);
			}
			else
			{
				$customer_at_the_rate3[0] = $customer_no[$n];
			}
			
			if( trim($relative_customer_input[$m]) == trim($customer_at_the_rate3[0]) )
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

		
	//########## WRITE RED AND BLUE CUSTOMERS -AGAIN #####################
	//$customer_arr[$q] = $sheet2_data[2];	//customer no
	//$record_arr[$q] = $sheet2_row[$q];			
	//$color_arr[$q] = $color;				
			
	for($x = 1; $x < sizeof($customer_arr); $x++) 
	{
		$tmp_vehicle_arr = $vehicle_arr[$x];
		$tmp_customer_arr = $customer_arr[$x];
		$tmp_type_arr = $type_arr[$x];
		$tmp_record_arr = $record_arr[$x];
		$tmp_color_arr = $color_arr[$x];		
		///////////      				

		$pos_c1 = strpos($tmp_customer_arr, "@");
		//echo "\nPOS=".$pos;

		if($pos_c1 !== false)
		{
			//echo "\nNegative Found";
			$customer_tmp1_A = explode("@", $tmp_customer_arr);
		}
		else
		{
			$customer_tmp1_A[0] = $tmp_customer_arr;
		}		
		
		$z = $x - 1;
		$done = false;
		while($done == false)
		{
			$customer_tmp1 = $customer_arr[$z];

			$pos_c2 = strpos($customer_tmp1, "@");
			//echo "\nPOS=".$pos;

			if($pos_c2 !== false)
			{
				//echo "\nNegative Found";
				$customer_tmp1_B = explode("@", $customer_tmp1);
			}
			else
			{
				$customer_tmp1_B[0] = $customer_tmp1;
			}
			
			if ($customer_tmp1_B[0] >$customer_tmp1_A[0])
			{
				$vehicle_arr[$z + 1] = $vehicle_arr[$z];
				$customer_arr[$z + 1] = $customer_arr[$z];
				$type_arr[$z + 1] = $type_arr[$z];
				$record_arr[$z + 1] = $record_arr[$z];
				$color_arr[$z + 1] = $color_arr[$z];				
				//////////////////
				$z = $z - 1;
				if ($z < 0)
				{
					$done = true;
				}
			}
			else
			{
				$done = true;
			}
		} //WHILE CLOSED

		$vehicle_arr[$z + 1] = $tmp_vehicle_arr;
		$customer_arr[$z + 1] = $tmp_customer_arr;
		$type_arr[$z + 1] = $tmp_type_arr;
		$record_arr[$z + 1] = $tmp_record_arr;
		$color_arr[$z + 1] = $tmp_color_arr;
	}
	
	//###### AFTER SORTING ALL CUSTOMERS
	for($i=0;$i<sizeof($customer_arr);$i++)
	{	
		$Datatowrite=true;
		if(($color_arr[$i] == "white") || ($color_arr[$i] == "pink") || ($color_arr[$i] == "yellow"))
		{
			$Datatowrite=false;
		}
		for($j=$i+1;j<sizeof($customer_arr);$j++)
		{
			if($customer_arr[$j]!=$customer_arr[$i])
			{
				break;
			}
			if( ($color_arr[$j] == "white") || ($color_arr[$j] == "pink") || ($color_arr[$j] == "yellow"))
			{
				$Datatowrite = false;
			}
		}
		
		if($Datatowrite == true)
		{
			for($k=$i;$k<$j;$k++)
			{
				if( ($color_arr[$k] == "red") || ($color_arr[$k] == "blue") )
				{
					$data_flag = false;
					$sheet3_data = explode(',',$record_arr[$k]);
					$c = 0;
					
					if($color_arr[$k] == "red")
					{
						//echo"RED\n";
						$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color' => 0x0A,'pattern' => 1,'border' => 1));
						$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color' => 0x0A,'pattern' => 1,'border' => 1));
						$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x0A,'pattern' => 1,'border'   => 1));
						$color = "red";
					}
					else if($color_arr[$k] == "blue")
					{			
						//BLUE
						$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color' => 0x2C,'pattern' => 1,'border' => 1));
						$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color' => 0x2C,'pattern' => 1,'border' => 1));
						$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x2C,'pattern' => 1,'border'   => 1));
						$color = "blue";
					}
					
					for($m=0;$m<sizeof($sheet3_data);$m++)
					{           
						if( ($sheet3_data[$m]!="-") && ($sheet3_data[$m]!="") && ($sheet3_data[$m]!=" ") && ($sheet3_data[$m]!=null) )
						{					
							if($m==6 || $m==7)	//ARRIVAL, DEPARTURE DATE AND TIME
							{
								$datetime_tmp = explode(" ",$sheet3_data[$m]);									
								
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
								}
							}
							else if($m==8)	//SCHEDULE TIME
							{
								$tmp_date = "1970-01-01";
								$tmp_date = $tmp_date." ".$sheet3_data[$m].":00";
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
								//echo "\nDELAY::sheet3_data[m]=".$sheet3_data[$m];
								$pos = strpos($sheet3_data[$m], "-");
								//echo "\nPOS=".$pos;
								
								if($pos !== false)
								{
									//echo "\nNegative Found";
									$sheet3_data[$m] = str_replace("-", "", $sheet3_data[$m]);
								}					
								$tmp_date = $tmp_date." ".$sheet3_data[$m];
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
							else if($m==10)	//HALT DURATION
							{
								$tmp_date = "1970-01-01";
								$tmp_date = $tmp_date." ".$sheet3_data[$m];
								//echo "\nHalt Duration10=".$tmp_date;
								$time_obj4 = strtotime($tmp_date);
								$time_obj4 = $time_obj4 + 19800;	//ADD 5:30
								//$date_tmp = date('d/m/Y',$date_obj);
								$excel_time4= $time_obj4 / 86400 + 25569;
								$worksheet2->write($r,$c, $excel_time4, $excel_time_format);										
								$c++;										
							}
							else if($m==12 || $m==14)	//REPORT TIME
							{																			
								$tmp_date = "1970-01-01";
								$tmp_date = $tmp_date." ".$sheet3_data[$m];
								//echo "\nReportTime12=".$tmp_date;
								$time_obj3 = strtotime($tmp_date);
								$time_obj3 = $time_obj3 + 19800;
								//$date_tmp = date('d/m/Y',$date_obj);
								$excel_time3= $time_obj3 / 86400 + 25569;
								$worksheet2->write($r,$c, $excel_time3, $excel_time_format);										
								$c++;										
							}				
							else if($m==11 || $m==13)	//REPORT DATE
							{																			
								$date_obj2 = strtotime($sheet3_data[$m]);
								//$date_tmp = date('d/m/Y',$date_obj);
								$excel_date2 = intval( ($date_obj2+86400) / 86400 + 25569);
								$worksheet2->write($r,$c, $excel_date2, $excel_date_format);					
								$c++;					
							}				
							else
							{
								$worksheet2->write($r,$c, $sheet3_data[$m],$excel_normal_format);
								$c++;
							}
						}
						else
						{
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
						$data_flag = true;     
					}			
					if($data_flag)
					{				
						$r++;
					}
					break;
				} //IF COLOR FOUND CLOSED
			}
		}
		$i = $j-1;
		
	} //CUSTOMERS CLOSED
 
	/*$c=0;
	$worksheet2->write($r,$c, "-",$excel_normal_format);
	$r++;
	$c=0;
	$worksheet2->write($r,$c, "-",$excel_normal_format);
	$r++;
		
	for($i=0;$i<sizeof($customer_arr);$i++)
	{
		$c=0;
		$worksheet2->write($r,$c, $customer_arr[$i],$excel_normal_format);
		$r++;
	}*/

/* 
	//##### ADD SECOND SHEET FOR PLANT DISTANCE ######
 	$worksheet3 =& $workbook->addworksheet('Distance Report');	
	$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x09,'pattern' => 1,'border' => 1));
	
	$r=0;   //row 
			
	$worksheet3->write($r, 0, "Vehicle", $text_format);	
	$worksheet3->write($r, 1, "Out Plant", $text_format);
	$worksheet3->write($r, 2, "Out Date", $text_format);
	$worksheet3->write($r, 3, "Out Time", $text_format);		 
	$worksheet3->write($r, 4, "In Plant", $text_format);
	$worksheet3->write($r, 5, "In Date", $text_format);
	$worksheet3->write($r, 6, "In Time", $text_format);
	$worksheet3->write($r, 7, "Distance(km)", $text_format);
	$worksheet3->write($r, 8, "Routes", $text_format);
	$worksheet3->write($r, 9, "Transporters(M)", $text_format);
	$worksheet3->write($r, 10, "Transporters(I)", $text_format);
	$r++;
	
	//$sno = 1;
	for($i=0;$i<sizeof($vehicle_pd);$i++)
	{
		$route_str = "";
		$transporter_m_str = "";
		$transporter_i_str = "";
		
		if($distance_pd[$i]>0)
		{
			$out_date_arr = explode(" ",$plant_out_time_pd[$i]);
			$in_date_arr = explode(" ",$plant_in_time_pd[$i]);
			
			$route_arr = explode("/",$route_pd[$i]);
			$unique_route_arr = array_unique($route_arr);
			
			foreach ($unique_route_arr as $key1 => $val1) 	//## value1,value2 = count
			{
				$route_str .= $val1."/";
			}
				
			$transporter_m_arr = explode("/",$transporter_m_pd[$i]);
			$unique_transporter_m_arr = array_unique($transporter_m_arr);
			
			foreach ($unique_transporter_m_arr as $key1 => $val1) 	//## value1,value2 = count
			{
				$transporter_m_str .= $val1."/";
			}
			
			
			$transporter_i_arr = explode("/",$transporter_i_pd[$i]);
			$unique_transporter_i_arr = array_unique($transporter_i_arr);
			
			foreach ($unique_transporter_i_arr as $key1 => $val1) 	//## value1,value2 = count
			{
				$transporter_i_str .= $val1."/";
			}						
			
			echo "\nRouteStr=".$route_str;
			$route_str = substr($route_str, 0, -2);
			$transporter_m_str = substr($transporter_m_str, 0, -2);
			$transporter_i_str = substr($transporter_i_str, 0, -2);
			
			$c = 0;			
			$worksheet3->write($r,$c, $vehicle_pd[$i], $excel_normal_format);
			$c++;			
			$worksheet3->write($r,$c, $plant_out_pd[$i], $excel_normal_format);	
			$c++;					
			$worksheet3->write($r,$c, $out_date_arr[0], $excel_normal_format);	
			$c++;
			$worksheet3->write($r,$c, $out_date_arr[1], $excel_normal_format);	
			$c++;						
			$worksheet3->write($r,$c, $plant_in_pd[$i], $excel_normal_format);	
			$c++;			
			$worksheet3->write($r,$c, $in_date_arr[0], $excel_normal_format);	
			$c++;
			$worksheet3->write($r,$c, $in_date_arr[1], $excel_normal_format);	
			$c++;			
			$worksheet3->write($r,$c, $distance_pd[$i], $excel_normal_format);
			$c++;			
			$worksheet3->write($r,$c, $route_str, $excel_normal_format);
			$c++;
			$worksheet3->write($r,$c, $transporter_m_str, $excel_normal_format);
			$c++;
			$worksheet3->write($r,$c, $transporter_i_str, $excel_normal_format);
			$c++;
			
			$r++;
		}
	}	
	echo "\nSecond Sheet Closed";
	//######### SECOND SHEET CLOSED ###################
	//#################################################
*/	
	
	//######### THIRD SHEET OPENS #####################
	$route_i = $route_input;
	$customer_i = $customer_input;		
		
	for($x = 1; $x < sizeof($customer_i); $x++) 
	{
		$tmp_customer_i = $customer_i[$x];
		$tmp_route_i = $route_i[$x];		
		///////////      				

		$z = $x - 1;
		$done = false;
		while($done == false)
		{
			$customer_tmp1 = $customer_i[$z];

			if ($customer_tmp1 >$tmp_customer_i)
			{
				$customer_i[$z + 1] = $customer_i[$z];							
				$route_i[$z + 1] = $route_i[$z];
				//////////////////
				$z = $z - 1;
				if ($z < 0)
				{
					$done = true;
				}
			}
			else
			{
				$done = true;
			}
		} //WHILE CLOSED

		$customer_i[$z + 1] = $tmp_customer_i;		
		$route_i[$z + 1] = $tmp_route_i;
                    
	}  // FOR CLOSED
	
	//##### INITIALIZE VISIT COUNTERS (ATLEAST ONE FOR EACH)
	$unique_cusomer_input = array_unique($customer_i);
	
	for($i=0;$i<sizeof($customer_arr);$i++)						//#### GENERATED IN REPORT
	{
		if( ($type_arr[$i]!="Plant") && (($color_arr[$i] == "white") || ($color_arr[$i] == "pink")) )
		{
			$customer_arr2[] = $customer_arr[$i];
			$vehicle_arr2[] = $vehicle_arr[$i];
		}
	}
	
	$unique_customer_report = array_unique($customer_arr2);
	
	foreach ($unique_cusomer_input as $key1 => $val1) 	//## value1,value2 = count
	{
		//if($val1 == "1004465") echo "\nFound1 1004465";	
		$customer_visit_input[$val1] += 1;
		//if($val1 == "1004465") echo "\nValueAfter1=".$customer_visit_input[$val1];
	}
	foreach ($unique_customer_report as $key2 => $val2) 	//## value1,value2 = count
	{
		$customer_visit_report[$val2] += 1;
	}
	
	/*for($i=0;$i<sizeof($unique_cusomer_input);$i++)						//#### INPUT MASTER
	{								
	}	
	for($i=0;$i<sizeof($unique_customer_report);$i++)						//#### GENERATED IN REPORT
	{		
		$customer_visit_report[$unique_customer_report[$i]] += 1;	
	}*/	
	
	//######## STORE VISIT COUNTERS
	$customer_prev = "";
	$route_prev = "";	
	
	for($i=0;$i<sizeof($customer_i);$i++)						//#### INPUT MASTER
	{				
		if( ($customer_i[$i] == $customer_prev) && ($route_id[$i]!=$route_prev) )
		{
			//if($customer_i[$i] == "1004465") echo "\nFound2 1004465";	
			//if($customer_i[$i] == "1004465") echo "\nValueBefore=".$customer_visit_input[$customer_i[$i]];
			$customer_visit_input[$customer_i[$i]] += 1;
			//if($customer_i[$i] == "1004465") echo "\nValueAfter2=".$customer_visit_input[$customer_i[$i]];
		}
		$customer_prev = $customer_i[$i];
		$route_prev = $route_i[$i];		
	}

	$customer_prev = "";
	$vehicle_prev = "";
	
	for($i=0;$i<sizeof($customer_arr2);$i++)						//#### GENERATED IN REPORT
	{
		//if($customer_arr[$i] == "70845") echo "\nFound1 70845";			
		if( ($customer_arr2[$i] == $customer_prev) && ($vehicle_arr2[$i]!=$vehicle_prev) )
		{
			$customer_visit_report[$customer_arr2[$i]] += 1;
		}
		$customer_prev = $customer_arr2[$i];
		$vehicle_prev = $vehicle_arr2[$i];			
	}
		
	foreach ($customer_visit_report as $customer_key1 => $value1) 	//## value1,value2 = count
	{
		$visit_match = false;		
		//echo $customer_key1." ,".$value1."\n";
		//if($customer_key1 == "70845") echo "\nFound2 70845";		
		foreach($customer_visit_input as $customer_key2 => $value2)
		{
			//if($customer_key1 == "1004465" && $customer_key2 == "1004465") echo "\nC1=".$customer_key1." ,V1=".$value1." ,C2=".$customer_key2." ,V2=".$value2."\n";			
			if($customer_key1 == $customer_key2)
			{
				if($value1 == $value2)
				{
					//if($customer_key1 == "1004465" && $customer_key2 == "1004465") echo "\nMatched";
					$visit_match = true;					
				}
				$value2_tmp = $value2;
				break;
			}
		}
		if(!$visit_match)	//IF NOT MATCHED
		{
			//echo "\nvalue1=".$value1." ,value2=".$value2_tmp;
			$mismatch_visit_customer[] = $customer_key1;
			$expected_visit[] = $value2_tmp;
			$report_visit[] = $value1;
		}
	}	

	//## WRITE SHEET3
 	$worksheet4 =& $workbook->addworksheet('Mismatch Visit');	
	$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x09,'pattern' => 1,'border' => 1));
	
	$r=0;   //row 
			
	$worksheet4->write($r, 0, "Customers", $text_format);							//0
	$worksheet4->write($r, 1, "Expected Visit", $text_format);
	$worksheet4->write($r, 2, "Report Visit", $text_format);
	$r++;
	
	//$sno = 1;
	for($i=0;$i<sizeof($mismatch_visit_customer);$i++)
	{
		if(($expected_visit[$i]>0) && ($report_visit[$i] < $expected_visit[$i]) )
		{
			$c = 0;			
			$worksheet4->write($r,$c, $mismatch_visit_customer[$i], $excel_normal_format);
			$c++;
			$worksheet4->write($r,$c, $expected_visit[$i], $excel_normal_format);
			$c++;
			$worksheet4->write($r,$c, $report_visit[$i], $excel_normal_format);
			$c++;
			$r++;
		}
	}		
	echo "\nThird Sheet closed";
	//############ THIRD SHEET CLOSED #################
	//#################################################
	
	$workbook->close(); //CLOSE WORKBOOK
	echo "\nWORKBOOK CLOSED"; 

 
	########### SEND MAIL ##############//
	//$to = 'rizwan@iembsys.com';
	$to = 'Amit.Patel@motherdairy.com,Vijay.Singh@motherdairy.com,vivek.chahal@motherdairy.com,ashish@iembsys.co.in';
	$subject = 'BETA::VTS_HALT_REPORT_(MOTHER_TANKER)_'.$cdate;
	$message = 'BETA::VTS_HALT_REPORT_(MOTHER_TANKER)_'.$cdate."<br><br><font color=red size=1>*** This is an automatically generated email by the system on specified time, please do not reply ***</font>";
	$random_hash = md5(date('r', time()));  
	$headers = "From: support@iembsys.co.in\r\n";
	//$headers .= "Cc: rizwan@iembsys.com";  
	$headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com,support1@iembsys.com,support2@iembsys.com";
	//$headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com";
	$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
	$filename_title = $filename_title.".xls";
	$file_path = $fullPath.".xls";

	//echo "\nFILE PATH=".$file_path;  
	include_once("send_mail_api.php");
	//################################//
		
	//############# CREATE FOLDER AND BACKUP FILES ########
	$sourcepath = $file_path;
	$dirPath = "excel_reports/".$previous_date;
	//echo "\nDirPath=".$dirPath;
	mkdir ($dirPath, false);
	@chmod($dirPath, 0777);
	$destpath = $dirPath."/".$filename_title;

	@chmod($destpath, 0777);
	//echo "\nSourcePath=".$sourcepath." ,DestPath=".$destpath;
	copy($sourcepath,$destpath);
	//########## BACKUP FILES CLOSED #######################
	
	unlink($file_path); 
	
	ini_set('auto_detect_line_endings',FALSE);
}
 
?>
