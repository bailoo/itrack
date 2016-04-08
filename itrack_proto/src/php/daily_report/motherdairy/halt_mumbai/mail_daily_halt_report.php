<?php
set_time_limit(3000);
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

include_once($abspath."/mail_action_report_distance_1.php");
include_once($abspath."/mail_action_report_halt_motherdairy.php");
include_once($abspath."/mail_action_report_travel_1.php");

function tempnam_sfx($path, $suffix)
{
	do
	{
		$file = $path.$suffix;
		$fp = @fopen($file, 'x');
	}
	while(!$fp);
	fclose($fp);
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

/*$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM ".
            "alert,vehicle,vehicle_assignment,alert_assignment WHERE ".
            "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND ".         
            "vehicle_assignment.vehicle_id = alert_assignment.vehicle_id AND ".
            "vehicle_assignment.vehicle_id = alert_assignment.vehicle_id AND ".            
            "alert.alert_id = alert_assignment.alert_id AND ".
            "alert.alert_name = 'report' AND ".                         
            "vehicle_assignment.status=1 AND ".
            "alert_assignment.status=1";  */
                        
$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".
                    "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
                    "vehicle_grouping.account_id=322 AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1";              

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

$startdate = $previous_date." 00:00:00";
$enddate = $previous_date." 23:59:59"; 
 
//$current_date = date("Y-m-d");
//$startdate = $current_date." 00:00:00";
//$enddate = date("Y-m-d H:i:s");

$date1 = $startdate;
$date2 = $enddate;
$user_interval = "5";   //FIVE MINUTES

//$user_interval = 30*60;

//echo "user_interval=".$user_interval."<br>";
///////////////////////////////////////////////////////////////////////////////
echo "<br>vsiz=".$vsize;

if($vsize>0)
{
  write_report($device_imei_no, $vid, $vehicle_name, $user_interval);
}
    

function write_report($vserial, $vid, $vname, $user_interval)
{
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
  
  $maxPoints = 1000;
  $file_exist = 0;
  
  //global $csv_string;
  //global $csv_string_arr;  
  echo "\nTOTAL_VSIZE=".sizeof($vserial);
  	
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
		$overall_dist = 0.0;
		$sno_dist = 1;
		
		/*get_distance_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $user_interval);	
		$csv_string_dist = $csv_string_dist.'#Total,'.$startdate.','.$enddate.','.$overall_dist; 
		echo "\nDISTANCE::vserial->  ".$vid[$i]." vname->".$vname[$i]." ".$csv_string_dist;
		$csv_string_dist_arr[$i] = $csv_string_dist;
		$csv_string_dist="";    
		$sno_halt = 1; */
		
		get_halt_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $user_interval);
		
                echo "\nSERIAL=".$i;
		$hms_2 = secondsToTime($total_halt_dur);
		$hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];	
		$csv_string_halt = $csv_string_halt.'#Total,-,-,-,'.$hrs_min;
		$total_halt_dur = 0;
		//echo "\n\nHALT::vserial->  ".$vid[$i]." vname->".$vname[$i].", ".$csv_string_halt;
		$csv_string_halt_arr[$i] = $csv_string_halt;
		$csv_string_halt="";   
		
    /*$sno_travel = 1;
		
		get_travel_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $user_interval);
		$hms_2 = secondsToTime($total_travel_time);
		$hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];	
		
		$csv_string_travel = $csv_string_travel.'#Total,-,-,-,-,'.$total_travel_dist.','.$hrs_min;
		$total_travel_time = 0;
		$total_travel_dist = 0;
		echo "\nTRAVEL::vserial->  ".$vid[$i]." vname->".$vname[$i].", ".$csv_string_travel;
		$csv_string_travel_arr[$i] = $csv_string_travel; 
		$csv_string_travel="";  */  
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
  		$csv_string_dist_final = $csv_string_dist_final.$csv_string_dist_arr[$j]."@";
  		$csv_string_halt_final = $csv_string_halt_final.$csv_string_halt_arr[$j]."@";
  		$csv_string_travel_final = $csv_string_travel_final.$csv_string_travel_arr[$j]."@"; 		       		
    }
    $vehicle_id_final = substr($vehicle_id_final, 0, -1);
    $vehicle_name_final = substr($vehicle_name_final, 0, -1);
    $device_imei_no_final = substr($device_imei_no_final, 0, -1);
	  $csv_string_dist_final = substr($csv_string_dist_final, 0, -1);
    $csv_string_halt_final = substr($csv_string_halt_final, 0, -1);
    $csv_string_travel_final = substr($csv_string_travel_final, 0, -1); 
    
	//echo "\ncsv_string_dist_final=".$csv_string_dist_final;
	//echo "\ncsv_string_halt_final=".$csv_string_halt_final;
	//echo "\ncsv_string_travel_final=".$csv_string_travel_final;                   
    
  if($vehicle_id_final!="")
  {
		$inc_serial=$i+1;
		$filename_title = 'VTS_DAILY_HALT_REPORT_MOTHERDAIRY'.$previous_date."_".$inc_serial;
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
			color   => 'blue',
			size    => 10,
			//font    => 'Comic Sans MS'
		));
												
    $blank_format = & $workbook->addformat();
    $blank_format->set_color('white');
    $blank_format->set_bold();
    $blank_format->set_size(12);
    $blank_format->set_merge();
    			
		$vname_label = explode(',',$vehicle_name_final);     //**TOTAL VEHICLES 
		
		/*
    $worksheet1 =& $workbook->addworksheet('Distance Report');
		$sheet1 = explode('@',$csv_string_dist_final);                 
		$r=0;   //row		
		
    for($p=0;$p<sizeof($sheet1);$p++)
		{		
			//echo "vehicle_name=".$vname_label[$p]."<br><br>escalation_id=".$escalation_id[$i]."<br><br>csv_string_dist_final".$sheet1[$p]."<br><br>";
			$report_title_dist = "Distance Report -Vehicle:".$vname_label[$p]." -($date1 to $date2)";
			//echo "\nreport_title_dist=".$report_title_dist."<br>";
			$worksheet1->write ($r, 0, $report_title_dist, $border1);
			for($b=1;$b<=9;$b++)
			{
				$worksheet1->write_blank($r, $b,$border1);
			}                              
			$r++;           
			$worksheet1->write($r, 0, "SNo", $text_format);
			$worksheet1->write($r, 1, "StartTime", $text_format);
			$worksheet1->write($r, 2, "StopTime", $text_format);
			$worksheet1->write($r, 3, "Distance", $text_format);  
			$r++;          
			$sheet1_row = explode('#',$sheet1[$p]);     
			for($q=0;$q<sizeof($sheet1_row);$q++)
			{
				$sheet1_data = explode(',',$sheet1_row[$q]);
				$sheet1_data_main_string="";
				for($m=0;$m<sizeof($sheet1_data);$m++)
				{
					$worksheet1->write($r,$m, $sheet1_data[$m]);
					$sheet1_data_main_string=$sheet1_data_main_string.$sheet1_data[$m].",";  // for debug
				}				
				//echo "vehicle_name=".$vname_label[$p]."&nbsp;&nbsp;escalation_id=".$escalation_id[$i]."&nbsp;&nbsp;sheet1_data_main_string=".$sheet1_data_main_string."<br>"; // for debug							
				$r++; 
			}        
		}  */
	
		$worksheet2 =& $workbook->addworksheet('Halt Report');
		$sheet2 = explode('@',$csv_string_halt_final);
		$r=0;   //row 
                
		for($p=0;$p<sizeof($sheet2);$p++)
		{
			//echo "vehicle_name=".$vname_label[$p]."<br><br>escalation_id=".$escalation_id[$i]."<br><br>csv_string_dist_final".$sheet2[$p]."<br><br>";
			$report_title_halt = "Halt Report -Vehicle:".$vname_label[$p]." -($date1 to $date2)";
			//echo "report_title_halt=".$report_title_halt."<br>";			
			$worksheet2->write      ($r, 0, $report_title_halt, $border1);
			for($b=1;$b<=6;$b++)
			{
				$worksheet2->write_blank($r, $b, $border1);
			}                   
			$r++;
			$worksheet2->write($r, 0, "SNo", $text_format);
			$worksheet2->write($r, 1, "Location", $text_format);
			$worksheet2->write($r, 2, "Arrival Time", $text_format);
			$worksheet2->write($r, 3, "Departure Time", $text_format);
			$worksheet2->write($r, 4, "Halt Duration (Hr:min:sec)", $text_format); 
			$worksheet2->write($r, 5, "Latitude", $text_format);
			$worksheet2->write($r, 6, "Longitude", $text_format);
			$r++;					
			$sheet2_row = explode('#',$sheet2[$p]);        
			for($q=0;$q<sizeof($sheet2_row);$q++)
			{
				$sheet2_data_main_string="";
				$sheet2_data = explode(',',$sheet2_row[$q]);
				for($m=0;$m<sizeof($sheet2_data);$m++)
				{           
					$worksheet2->write($r,$m, $sheet2_data[$m]);
					$sheet2_data_main_string=$sheet2_data_main_string.$sheet2_data[$m].",";      
				}
				//echo "sheet2_data_main_string=".$sheet2_data_main_string."<br>";
				$r++; 
			} 
		}
		
		/*
    $worksheet3 =& $workbook->addworksheet('Travel Report');
		$sheet3 = explode('@',$csv_string_travel_final);
		$r=0;   //row  
		for($p=0;$p<sizeof($sheet3);$p++)
		{
			//echo "vehicle_name=".$vname_label[$p]."<br><br>escalation_id=".$escalation_id[$i]."<br><br>csv_string_traval_final".$sheet3[$p]."<br><br>";
			$report_title_travel = "Travel Report -Vehicle:".$vname_label[$p]." -($date1 to $date2)";		
			//echo "report_title_travel=".$report_title_travel."<br>";
			$worksheet3->write($r, 0, $report_title_travel, $border1);
			for($b=1;$b<=10;$b++)
			{
				$worksheet3->write_blank($r, $b,$border1);
			} 
			$r++;
			$worksheet3->write($r, 0, "SNo", $text_format);
			$worksheet3->write($r, 1, "StartTime", $text_format);
			$worksheet3->write($r, 2, "EndTime", $text_format);
			$worksheet3->write($r, 3, "StartPlace", $text_format);
			$worksheet3->write($r, 4, "EndPlace", $text_format);
			$worksheet3->write($r, 5, "Distance Travelled(km)", $text_format);
			$worksheet3->write($r, 6, "Travel Time(H:m:s)", $text_format);  
			$r++;          
			$sheet3_row = explode('#',$sheet3[$p]);       
			for($q=0;$q<sizeof($sheet3_row);$q++)
			{
				//echo "sheet3_data=".$sheet3_row[$q]."<br>";
				$sheet3_data_main_string="";
				$sheet3_data = explode(',',$sheet3_row[$q]);
				for($m=0;$m<sizeof($sheet3_data);$m++)
				{           
					$worksheet3->write($r,$m, $sheet3_data[$m]);
					$sheet3_data_main_string=$sheet3_data_main_string.$sheet3_data[$m].",";      
				}
				//echo "sheet3_data_main_string=".$sheet3_data_main_string."<br>";
				$r++; 
			} 
		}  */
   
  $workbook->close(); //CLOSE WORKBOOK
  //echo "\nWORKBOOK CLOSED"; 
  
  //########### SEND MAIL ##############//
  //$to = 'rizwan@iembsys.com';
  $to = 'jyoti.jaiswal@iembsys.com';
  $subject = 'VTS_HALT_REPORT_MOTHERDAIRY_MUMBAI_'.$previous_date;
  $message = 'VTS_HALT_MOTHERDAIRY_MUMBAI_'.$previous_date; 
  $random_hash = md5(date('r', time()));  
  $headers = "From: support@iembsys.co.in\r\n";
  //$headers .= "Cc: rizwan@iembsys.com";
  $headers .= "Cc: ashish@iembsys.co.in"; 
  $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
  $filename_title = $filename_title.".xls";
  $file_path = $fullPath.".xls";
  
  //echo "\nFILE PATH=".$file_path;  
  include_once("send_mail_api.php");
  //####################################//
  
  unlink($file_path); 
}
 
?>
