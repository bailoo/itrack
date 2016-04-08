<?php
set_time_limit(0);
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once("../get_all_dates_between.php");
include_once("../sort_xml.php");
include_once("../calculate_distance.php");
include_once("../report_title.php");
include_once("../read_filtered_xml.php");
include_once("../get_location.php");
include_once("../user_type_setting.php");
include_once("../select_landmark_report.php");
include_once("../area_violation/check_with_range.php");
include_once("../area_violation/pointLocation.php");
require_once "../excel_lib/class.writeexcel_workbook.inc.php";
require_once "../excel_lib/class.writeexcel_worksheet.inc.php";
include_once("../util.hr_min_sec.php");

include_once("../mail_action_report_distance_1.php");
include_once("../mail_action_report_halt_1.php");
include_once("../mail_action_report_travel_1.php");

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
$sno_halt = 0;


$csv_string_travel = "";                //INITIALISE  TRAVEL VARIABLES
$csv_string_travel_arr = array();
$sno_travel = 0;


/*$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM ".
            "alert,vehicle,vehicle_assignment,alert_assignment WHERE ".
            "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND ".         
            "vehicle_assignment.vehicle_id = alert_assignment.vehicle_id AND ".
            "vehicle_assignment.vehicle_id = alert_assignment.vehicle_id AND ".            
            "alert.alert_id = alert_assignment.alert_id AND ".
            "alert.alert_name = 'report' AND ".                         
            "vehicle_assignment.status=1 AND ".
            "alert_assignment.status=1"; */

//userid: KIRAN
//account_id: 347
//EMAIL ID : kiran.jintikar@itc.in

$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".
                    "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
                    "vehicle_grouping.account_id=347 AND vehicle.status=1 AND vehicle_assignment.status=1";              
            
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
$current_date = date("Y-m-d");
$startdate = $current_date." 00:00:00";
$enddate = date("Y-m-d H:i:s");

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
  global $sno_halt;
    
  global $csv_string_travel;
  global $csv_string_travel_arr;
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
	  $csv_string_dist = $csv_string_dist.'#Total,-,-,'.$overall_dist; 
	//echo "vserial->  ".$vid[$i]." vname->".$vname[$i]."<br>".$csv_string_dist."<br>";
    $csv_string_dist_arr[$i] = $csv_string_dist;
	  $csv_string_dist="";    
    $sno_halt = 1;
    get_halt_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $user_interval);
    $csv_string_halt = $csv_string_halt.'#Total,-,-,-,-';
	//echo "<br><br>vserial->  ".$vid[$i]." vname->".$vname[$i]."<br>".$csv_string_halt."<br>";
    $csv_string_halt_arr[$i] = $csv_string_halt;
	  $csv_string_halt="";   
    $sno_travel = 1;
    get_travel_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $user_interval);
    $csv_string_travel = $csv_string_travel.'#Total,-,-,-,-,-,-';
	//echo "<br><br>vserial->  ".$vid[$i]." vname->".$vname[$i]."<br>".$csv_string_travel."<br>";
    $csv_string_travel_arr[$i] = $csv_string_travel; 
	  $csv_string_travel="";    
	}  	
}
      
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
				echo "\n".$query_last_datetime;
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
							echo "\nUPDATE1:".$query_update1;
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
					echo "\nUPDATE2:".$query_update2;
					$result_last_datetime2 = mysql_query($query_update2,$DbConnection);
				} 	
				break;   //STORE AND BREAK INNER LOOP
			}  // COMPARISON IF CLOSED
		}
	}
    //echo "vehicle_id_final=".$vehicle_id_final."<br>";
    if($match_case && $vehicle_id_final!="")
    {
  		$inc_serial=$i+1;
  		$filename_title = 'VTS_(KIRAN)_REPORT_'.$date1.'-'.$date2."_".$inc_serial;
  		echo "\nfilename=".$filename_title;
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
  
    	$query_name = "SELECT vehicle_id,vehicle_name FROM vehicle WHERE vehicle_id IN($vehicle_id_final) AND status=1";      
      $result_name = mysql_query($query_name,$DbConnection);
    	$vname_str ="";
      $v=0;
    
      while($row_name = mysql_fetch_object($result_name))
    	{
        $vid_temporary = $row_name->vehicle_id;
        $vname_temporary = $row_name->vehicle_name;          
        $query_imei = "SELECT device_imei_no FROM vehicle_assignment WHERE vehicle_id='$vid_temporary' AND status=1";      
        $result_imei = mysql_query($query_imei,$DbConnection);
        $row_imei = mysql_fetch_object($result_imei);
        $imei_no = $row_imei->device_imei_no;          
        if($v==0)
        {
          $vname_str = $vname_str.$vname_temporary."(".$imei_no.")";
        }
        else
        {
          $vname_str = $vname_str.",".$vname_temporary."(".$imei_no.")";
        }
        $v++;
      } 			
  		$vname_label = explode(',',$vname_str);     //**TOTAL VEHICLES 
  		
  		$worksheet1 =& $workbook->addworksheet('Distance Report');
  		$sheet1 = explode('@',$csv_string_dist_final);                 
  		$r=0;   //row		
		
      for($p=0;$p<sizeof($sheet1);$p++)
  		{		
  			//echo "vehicle_name=".$vname_label[$p]."<br><br>escalation_id=".$escalation_id[$i]."<br><br>csv_string_dist_final".$sheet1[$p]."<br><br>";
  			$report_title_dist = "Distance Report -Vehicle:".$vname_label[$p]." -($date1 to $date2)";
  			//echo "report_title_dist=".$report_title_dist."<br>";
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
  			$worksheet2->write($r, 4, "Halt Duration (Hrs.min)", $text_format); 
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
  		$email_message = ""; 
  		$fileatt_final="";
  		$fileatt_type_final="";
  		$fileatt_name_final="";        
  		//SAVE FINAL MAIL CONTENT TO MAIL INFO TABLE	  	
  		$current_dt_tmp1 = date("Y_m_d_H_i_s");
  		$datetime_sent = $last_datetime; 
  		$email_from_d="support@iembsys.co.in";
  		$email_subject_d="VTS Report:".$date1." -".$date2;
  		$email_message_d="VTS Report:".$date1." -".$date2." (".$vname_str.")";
  		$email_to_d = $person_email[$i];  //"rizwan@iembsys.com";    	
  		$fileatt_d = $fullPath.".xls";
  		$fileatt_type_d = "xls";      
  		$fileatt_name_d = $fileatt_d;      
  		/*echo "\nalert_id_final=".$alert_id_final;
  		echo "\nvehicle_id_final=".$vehicle_id_final;
  		echo "\nperson_name=".$person_name[$i];
  
  		echo "\nemail_from=".$email_from_d;
  		echo "\nemail_subject=".$email_subject_d;
  		echo "\nemail_to=".$email_to_d;
  		echo "\nemail_message=".$email_message_d."\n";*/  
  		/*$query4 = "INSERT INTO mail_info_report(alert_id,vehicle_id,escalation_id,person_name,".
  				"fileatt,fileatt_type,fileatt_name,email_from,email_subject,email_message,email_to,datetime_sent,status) VALUES(".
  				"'$alert_id_final','$vehicle_id_final','$escalation_id[$i]','$person_name[$i]','$fileatt_d','$fileatt_type_d','$fileatt_name_d',".
  				"'$email_from_d','$email_subject_d','$email_message_d','$email_to_d','$datetime_sent',1)";                
  		//echo "\n".$query4;
  		$result4 = mysql_query($query4,$DbConnection);  */ 
  		$workbook->close();	
    } //IF MATCH CASE CLOSED    
} //OUTER WHILE CLOSED 
 
?>
