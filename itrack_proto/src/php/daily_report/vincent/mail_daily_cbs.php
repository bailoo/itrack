<?php
set_time_limit(3000);
//include_once('util_session_variable.php');
//include_once('util_php_mysql_connectivity.php');

//$HOST = "111.118.181.156";
include_once("../database_ip.php");
$DBASE = "iespl_vts_beta";
$USER = "root";
$PASSWD = "mysql";
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
include_once($abspath."/get_location.php");
include_once($abspath."/user_type_setting.php");
include_once($abspath."/select_landmark_report.php");
include_once($abspath."/area_violation/check_with_range.php");
include_once($abspath."/area_violation/pointLocation.php");
require_once $abspath."/excel_lib/class.writeexcel_workbook.inc.php";
require_once $abspath."/excel_lib/class.writeexcel_worksheet.inc.php";
include_once($abspath."/util.hr_min_sec.php");

include_once($abspath."/mail_action_report_distance_1.php");
include_once($abspath."/mail_action_report_halt_1.php");
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
        "vehicle_grouping.account_id=640 AND vehicle.status=1 AND vehicle_assignment.status=1";                                                          

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
$user_interval = "30";

//$user_interval = 30*60;

//echo "user_interval=".$user_interval."<br>";
///////////////////////////////////////////////////////////////////////////////
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
		
		get_distance_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $user_interval);	
		$csv_string_dist = $csv_string_dist.'#Total,'.$startdate.','.$enddate.','.$overall_dist; 
		//echo "\nDISTANCE::vserial->  ".$vid[$i]." vname->".$vname[$i]." ".$csv_string_dist;
		$csv_string_dist_arr[$i] = $csv_string_dist;
		$csv_string_dist="";    
		$sno_halt = 1;
		
		get_halt_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $user_interval);
		
		$hms_2 = secondsToTime($total_halt_dur);
		$hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];	
		$csv_string_halt = $csv_string_halt.'#Total,-,-,-,'.$hrs_min;
		$total_halt_dur = 0;
		//echo "\n\nHALT::vserial->  ".$vid[$i]." vname->".$vname[$i].", ".$csv_string_halt;
		$csv_string_halt_arr[$i] = $csv_string_halt;
		$csv_string_halt="";   
		$sno_travel = 1;
		
		get_travel_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $user_interval);
		$hms_2 = secondsToTime($total_travel_time);
		$hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];	
		
		$csv_string_travel = $csv_string_travel.'#Total,-,-,-,-,'.$total_travel_dist.','.$hrs_min;
		$total_travel_time = 0;
		$total_travel_dist = 0;
		//echo "\nTRAVEL::vserial->  ".$vid[$i]." vname->".$vname[$i].", ".$csv_string_travel;
		$csv_string_travel_arr[$i] = $csv_string_travel; 
		$csv_string_travel="";    
	}  	
}

/*      
// SEND REPORT MAIL 
$query2 = "SELECT DISTINCT escalation.person_name,escalation.person_email,".
          "escalation.escalation_id,alert.alert_id FROM ".
          "escalation,alert_assignment,alert WHERE ".
          "escalation.escalation_id = alert_assignment.escalation_id AND ".            
          "alert.alert_name='report' AND ".
          "alert.alert_id = alert_assignment.alert_id AND ".                      
          "alert_assignment.mail_status = 1 AND ".            
          "alert_assignment.status=1 AND ".
          "escalation.status=1 AND ".
          "alert.status=1";
//echo "\n".$query2;
$result2 = mysql_query($query2,$DbConnection);

$k=0;
while($row2 = mysql_fetch_object($result2))
{    
    $escalation_id[] = $row2->escalation_id; 
    $person_name[] = $row2->person_name;
    $person_email[] = $row2->person_email;
}
//$border1->set_top(5);
//$border1->set_bottom(5);
//$border1->set_left(5);
//$border1->set_align('center');
//$border1->set_align('vcenter');
//$border1->set_merge(); # This is the key feature


for($i=0;$i<sizeof($escalation_id);$i++)     //TOTAL DISTINCT ESCALATION
{
	//date_default_timezone_set('Asia/Calcutta');	
	$last_datetime = date("Y-m-d H:i:s");
	$last_datetime_t = strtotime($last_datetime);
  $vehicle_id_final = "";
  $alert_id_final = "";
  $csv_string_dist_final ="";
  $csv_string_halt_final ="";
  $csv_string_travel_final ="";    
  $query_vehicle = "SELECT vehicle_id,alert_id,alert_duration FROM alert_assignment WHERE alert_id IN(SELECT alert_id FROM alert WHERE ".
                    "alert_name='report' AND status=1) AND escalation_id='$escalation_id[$i]' AND status=1";
  //echo "\n".$query_vehicle;     
  $result_vehicle = mysql_query($query_vehicle,$DbConnection);                          
  $l=0;
  $match_case = 0;
  $h=0;
  
	while($row = mysql_fetch_object($result_vehicle))      // LOOP FOR MULTIPLE ALERTS
	{
		$vid_db[$h] = $row->vehicle_id;
		$alert_db[$h] = $row->alert_id;      
		$duration_db[$h] = $row->alert_duration;
		$duration_db_secs[$h] = $duration_db[$h] * 3600;    //IN SECONDS
		//$duration_db_secs[$h] = 10;    //IN SECONDS
		//$duration_db_secs = $duration_db * 60;            
		$alert_id_final[$h] = $alert_db;
		//echo "\nvid=".$vid_db;
		$h++;
	}     
	for($j=0;$j<sizeof($vid);$j++)
	{	
		for($k=0;$k<$h;$k++)
		{
			if($vid[$j] == $vid_db[$k])
			{    
				$match_case = 1;
				$query_last_datetime = "SELECT last_datetime FROM report_last_mail_status WHERE vehicle_id ='$vid_db[$k]' AND ".
				"alert_id='$alert_db[$k]' AND escalation_id='$escalation_id[$i]'  AND report_type='combined'";
				//echo "\n".$query_last_datetime;
				$result_last_datetime = mysql_query($query_last_datetime,$DbConnection);
				$numrows = mysql_num_rows($result_last_datetime);          
				$excel_flag = 0;          
				if($numrows > 0)
				{					
					$row_dt = mysql_fetch_object($result_last_datetime);
					$last_datetime_db = $row_dt->last_datetime;             
					$last_datetime_db_t = strtotime($last_datetime_db);             
					$diff_time_secs = ($last_datetime_t - $last_datetime_db_t);  
					if($diff_time_secs >= $duration_db_secs[$k])  //IF ALERT TIME DURATION SATISFIED  EG. 6HRS, 12 HRS, 24HRS, 1 WEEK ETC
					{             
						$excel_flag =1; 
						if($l==0)
						{				
							$vehicle_id_final = $vehicle_id_final.$vid_db[$k];
							//$alert_id_final = $alert_id_final.$alert_db;
							$csv_string_dist_final = $csv_string_dist_final.$csv_string_dist_arr[$j];
							$csv_string_halt_final = $csv_string_halt_final.$csv_string_halt_arr[$j];
							$csv_string_travel_final = $csv_string_travel_final.$csv_string_travel_arr[$j];
							$l++;
						}
						else
						{
							$vehicle_id_final = $vehicle_id_final.",".$vid_db[$k];
							//$alert_id_final = $alert_id_final.":".$alert_db;
							$csv_string_dist_final = $csv_string_dist_final."@".$csv_string_dist_arr[$j];
							$csv_string_halt_final = $csv_string_halt_final."@".$csv_string_halt_arr[$j];
							$csv_string_travel_final = $csv_string_travel_final."@".$csv_string_travel_arr[$j]; 
							$l++;             
						}
							//UPDATE LAST DATETIME STATUS
							$query_update1 = "UPDATE report_last_mail_status SET last_datetime='$last_datetime' WHERE vehicle_id ='$vid_db[$k]' AND ".
							"alert_id='$alert_db[$k]' AND escalation_id='$escalation_id[$i]' AND report_type='combined'";
							//echo "\nUPDATE1:".$query_update1;
							$result_last_datetime1 = mysql_query($query_update1,$DbConnection);
					}                           
				}              
				else
				{
					$excel_flag =1;
					if($l==0)
					{				
						$vehicle_id_final = $vehicle_id_final.$vid_db[$k];
						//$alert_id_final = $alert_id_final.$alert_db;
						$csv_string_dist_final = $csv_string_dist_final.$csv_string_dist_arr[$j];
						$csv_string_halt_final = $csv_string_halt_final.$csv_string_halt_arr[$j];
						$csv_string_travel_final = $csv_string_travel_final.$csv_string_travel_arr[$j];
						//echo "\nCCCCCCCC1=".$csv_string_travel_final; 
						$l++;
					}
					else
					{
						$vehicle_id_final = $vehicle_id_final.",".$vid_db[$k];
						//$alert_id_final = $alert_id_final.":".$alert_db;
						$csv_string_dist_final = $csv_string_dist_final."@".$csv_string_dist_arr[$j];
						$csv_string_halt_final = $csv_string_halt_final."@".$csv_string_halt_arr[$j];
						$csv_string_travel_final = $csv_string_travel_final."@".$csv_string_travel_arr[$j];
						//echo "\nCCCCCCCC2=".$csv_string_travel_final; 
						$l++;             
					}	
					$query_update2 = "insert into report_last_mail_status(last_datetime,vehicle_id,alert_id,report_type,escalation_id)".
					"VALUES('$last_datetime','$vid_db[$k]','$alert_db[$k]','combined','$escalation_id[$i]')";
					//echo "\nUPDATE2:".$query_update2;
					$result_last_datetime2 = mysql_query($query_update2,$DbConnection);
				} 	
				break;   //STORE AND BREAK INNER LOOP
			}  // COMPARISON IF CLOSED
		}
	}
    //echo "vehicle_id_final=".$vehicle_id_final."<br>";
*/    
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
    
echo "\nVidFinal=".$vehicle_id_final;

  if($vehicle_id_final!="")
  {
		$inc_serial=$i+1;
		$filename_title = 'VTS_DAILYREPORT_CBS_'.$previous_date."_".$inc_serial;
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
		}
	
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
		}  
   
  $workbook->close(); //CLOSE WORKBOOK
  //echo "\nWORKBOOK CLOSED"; 
  
  //########### SEND MAIL ##############//
  //$to = 'rizwan@iembsys.com';
  $to = 'jyoti.jaiswal@iembsys.com'; 
  $subject = 'VTS_REPORT_CBS_'.$previous_date;
  $message = 'VTS_REPORT_CBS_'.$previous_date; 
  $random_hash = md5(date('r', time()));  
  $headers = "From: support@iembsys.co.in\r\n";
  //$headers .= "Reply-To: taseen@iembsys.com\r\n"; 
  //$headers .= "Cc: rizwan@iembsys.com";
  //$headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com";
  $headers .= "Cc: support1@iembsys.com,support2@iembsys.com";
  $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
  $filename_title = $filename_title.".xls";
  $file_path = $fullPath.".xls";
  
  //echo "\nFILE PATH=".$file_path;
  
  include_once("send_mail_api.php");
  //####################################//
  echo "\nMail Sent";
  
 // unlink($file_path); 
}
 
?>
