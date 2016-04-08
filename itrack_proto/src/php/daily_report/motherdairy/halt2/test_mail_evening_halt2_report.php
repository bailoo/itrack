<?php

echo "Evening file";
set_time_limit(18000);
//include_once('util_session_variable.php');
//include_once('util_php_mysql_connectivity.php');

$HOST = "localhost";
$DBASE = "iespl_vts_beta";
$USER = "root";
$PASSWD = "mysql";
//echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

$abspath = "/var/www/html/vts/beta/src/php";

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
include_once($abspath."/daily_report/motherdairy/halt2/get_master_detail.php");
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
                    "vehicle_grouping.account_id=231 AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1";              

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

$date = date('Y-m-d');
$previous_date = date('Y-m-d', strtotime($date .' -1 day'));
$current_date = $date;
/////////////////////////////////////////////

//$previous_date = date('Y-m-d', strtotime($date .' -3 day'));
//$current_date =  date('Y-m-d', strtotime($date .' -2 day'));


//////////////////////////////////////////
$startdate = $previous_date." 15:00:00";               //TIME 6PM TO 6AM
$enddate = $current_date." 06:00:00"; 
/////////////////////////////////////////

$date1 = $startdate;
$date2 = $enddate;
$user_interval = "2";   //FIVE MINUTES

//$user_interval = 30*60;
//echo "user_interval=".$user_interval."<br>";
///////////////////////////////////////////////////////////////////////////////
//echo "<br>vsiz=".$vsize;

//##############################################
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
global $shift_input;
$shift_input = array();

echo "\nbefore";
//######## GET MASTER DETAIL ################
$account_id="231";
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

for($k=0;$k<sizeof($customer_input);$k++)
{
	if($k==0)
	{
		$customer_input_string = $customer_input_string."".$customer_input[$k];
	}
	else
	{
		$customer_input_string = $customer_input_string.",".$customer_input[$k];
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
            "user_account_id=231 AND customer_no IN(".$customer_input_string.") AND type=0 AND status=1";
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
            "user_account_id=231 AND type=1 AND status=1";
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
	global $customer_input;
	global $shift_input;	
  
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
	 
		get_halt_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $user_interval, $report_shift);
			
		//echo "\n\nHALT::vserial->  ".$vid[$i]." vname->".$vname[$i].", ".$csv_string_halt;
		$csv_string_halt_arr[$i] = $csv_string_halt;
		$csv_string_halt="";   
		
		$s = $i+1;
		echo "\nSerial = ".$s." :Vehicle:".$vname[$i]." ::Completed";
	}	
}

   
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
    
    
if($vehicle_id_final!="")
{
	//$inc_serial=$i+1;
	$inc_serial = rand();
	$filename_title = 'VTS_HALT2_REPORT_MOTHER_DELHI_EVENING_'.$previous_date."_".$inc_serial;
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
											
	$blank_format = & $workbook->addformat();
	$blank_format->set_color('white');
	$blank_format->set_bold();
	$blank_format->set_size(12);
	$blank_format->set_merge();
			
	$vname_label = explode(',',$vehicle_name_final);     //**TOTAL VEHICLES 		

	$worksheet2 =& $workbook->addworksheet('Halt Report');
	$sheet2 = explode('@',$csv_string_halt_final);
	$r=0;   //row 
			
	$worksheet2->write($r, 0, "Vehicle", $text_format);
	$worksheet2->write($r, 1, "SNo", $text_format);
	//$worksheet2->write($r, 2, "Location", $text_format);
	$worksheet2->write($r, 2, "Station No", $text_format);
	$worksheet2->write($r, 3, "Type", $text_format);
	$worksheet2->write($r, 4, "RouteNo", $text_format);
	$worksheet2->write($r, 5, "ReportShift", $text_format);
	$worksheet2->write($r, 6, "Arrival Date", $text_format);
	$worksheet2->write($r, 7, "Arrival Time", $text_format);
	$worksheet2->write($r, 8, "Departure Date", $text_format);
	$worksheet2->write($r, 9, "Departure Time", $text_format);
	$worksheet2->write($r, 10, "ScheduleTime", $text_format);
	$worksheet2->write($r, 11, "Delay", $text_format);		
	$worksheet2->write($r, 12, "Halt Duration (Hr:min:sec)", $text_format);
	$worksheet2->write($r, 13, "ReportDate1", $text_format);
	$worksheet2->write($r, 14, "ReportTime1", $text_format);
	$worksheet2->write($r, 15, "ReportDate2", $text_format);
	$worksheet2->write($r, 16, "ReportTime2", $text_format); 
	$worksheet2->write($r, 17, "Transporter", $text_format);
	//$worksheet2->write($r, 10, "Latitude", $text_format);
	//$worksheet2->write($r, 11, "Longitude", $text_format);
	$r++;

	for($p=0;$p<sizeof($sheet2)-1;$p++)
	{
		//echo "vehicle_name=".$vname_label[$p]."<br><br>escalation_id=".$escalation_id[$i]."<br><br>csv_string_dist_final".$sheet2[$p]."<br><br>";
		//$report_title_halt = "Halt Report -Vehicle:".$vname_label[$p]." -($date1 to $date2)";   //COMMENTED ON REQ
		//echo "report_title_halt=".$report_title_halt."<br>";			              
		//$r++;
		$data_flag = false;
		
		$sheet2_row = explode('#',$sheet2[$p]);        
		for($q=0;$q<sizeof($sheet2_row);$q++)
		{
		  $data_flag = false;
			$sheet2_data_main_string="";
			$sheet2_data = explode(',',$sheet2_row[$q]);
			$c = 0;
			for($m=0;$m<sizeof($sheet2_data);$m++)
			{           
				if( ($sheet2_data[$m]!="") && ($sheet2_data[$m]!=" ") && ($sheet2_data[$m]!=null) )
				{					
					if($m==6 || $m==7)
					{
					  $datetime_tmp = explode(" ",$sheet2_data[$m]);
					  $worksheet2->write($r,$c, $datetime_tmp[0]);
					  $c++;
					  $worksheet2->write($r,$c, $datetime_tmp[1]);
					  $c++;
					}
					else
					{
					  $worksheet2->write($r,$c, $sheet2_data[$m]);
					  $c++;
					}
				  $sheet2_data_main_string=$sheet2_data_main_string.$sheet2_data[$m].",";
				  $data_flag = true;
				}      
			}
			//echo "sheet2_data_main_string=".$sheet2_data_main_string."<br>";
			if($data_flag)
			{
				$r++;
			} 
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
    $r++;         


    $worksheet2->write($r, 0, "", $text_format);
    $worksheet2->write($r, 1, "", $text_format);
	//$worksheet2->write($r, 2, "Location", $text_format);
	$worksheet2->write($r, 2, "Customer Not Visited", $text_red_format);
	$worksheet2->write($r, 3, "Type", $text_red_format);
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
    $r++;         
            
    for($m=0;$m<sizeof($station_coord);$m++)
    {    
      $found = false;
      for($n=0;$n<sizeof($customer_visited);$n++)
      {
        if($customer_no[$m] == $customer_visited[$n])
        {
          $found = true;
          break;
        }
      }
      if(!$found)
      {
        if($type[$m]==0)
          $type[$m] = "Customer";
        else if($type[$m] ==1)
          $type[$m]= "Plant";
            		
		$worksheet2->write($r, 0, "", $text_format);
		$worksheet2->write($r, 1, "", $text_format);
		//$worksheet2->write($r, 2, "", $text_format);
		$worksheet2->write($r, 2, $customer_no[$m], $text_red_format);
		$worksheet2->write($r, 3, $type[$m], $text_red_format);
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
        $r++;         
      }
    }
    //########### WRITE CUSTOMER NOT VISITED CLOSED ###############    	
   
  $workbook->close(); //CLOSE WORKBOOK
  //echo "\nWORKBOOK CLOSED"; 
  
  //########### SEND MAIL ##############//
  $to = 'rizwan@iembsys.com';
  //$to = 'ashish@iembsys.co.in';
  //$to = 'Amit.Patel@motherdairy.com,Ravindra.Negi@motherdairy.com,Vijay.Singh@motherdairy.com,vivek.chahal@motherdairy.com';
  $subject = 'VTS_HALT2_REPORT_MOTHER_DELHI_EVENING_'.$previous_date;
  $message = 'VTS_HALT2_REPORT_MOTHER_DELHI_EVENING_'.$previous_date; 
  $random_hash = md5(date('r', time()));  
  $headers = "From: support@iembsys.co.in\r\n";
  //$headers .= "Cc: jyoti.jaiswal@iembsys.com,rizwan@iembsys.com,ashish@iembsys.co.in";
  //$headers .= "Cc: jyoti.jaiswal@iembsys.com,rizwan@iembsys.com,omvrat@iembsys.com,avanendra@iembsys.com"; 
  $headers .= "Cc: rizwan@iembsys.com";
  //$headers .= "Cc: Pradeep.Sharma@motherdairy.com,Vijay.Singh@motherdairy.com,rizwan@iembsys.com";
  //$headers .= "Cc: jyoti.jaiswal@iembsys.com";
  $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
  $filename_title = $filename_title.".xls";
  $file_path = $fullPath.".xls";
  
  //echo "\nFILE PATH=".$file_path;  
  include_once("send_mail_api.php");
  //################################//
  
  unlink($file_path); 
}
 
?>
