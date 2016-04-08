<?php
//include_once('../util_session_variable.php');
//include_once('../util_php_mysql_connectivity.php');   

set_time_limit(0);

include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

//echo "report1\n";
//FOR DISTANCE
include_once("../get_all_dates_between.php");
include_once("../sort_xml.php");
include_once("../calculate_distance.php");
include_once("../report_title.php");
include_once("../read_filtered_xml.php");
///////////

//echo "report2\n";

//FOR HALT 
include_once("../get_location.php");
include_once("../user_type_setting.php");
include_once("../select_landmark_report.php");
include_once("../area_violation/check_with_range.php");
include_once("../area_violation/pointLocation.php");

require_once "../excel_lib/class.writeexcel_workbook.inc.php";
require_once "../excel_lib/class.writeexcel_worksheet.inc.php";
///////////

//echo "report3\n";
//FOR TRAVEL
include_once("../util.hr_min_sec.php");
///////////

//INCLUDE REPORT FUNCTIONS
include_once("../mail_action_report_distance.php");
include_once("../mail_action_report_halt.php");
include_once("../mail_action_report_travel.php");

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


$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM ".
            "alert,vehicle,vehicle_assignment,alert_assignment WHERE ".
            "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND ".         
            "vehicle_assignment.vehicle_id = alert_assignment.vehicle_id AND ".
            "vehicle_assignment.vehicle_id = alert_assignment.vehicle_id AND ".            
            "alert.alert_id = alert_assignment.alert_id AND ".
            "alert.alert_name = 'report' AND ".                         
            "vehicle_assignment.status=1 AND ".
            "alert_assignment.status=1";
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
    $title = $vname[$i]." (".$vserial[$i]."): Distance Report- From DateTime : ".$startdate."-".$enddate;
  	$csv_string_dist = $csv_string_dist.$title.":";
    $csv_string_dist = $csv_string_dist."SNo,StartTime,EndTime,Distance (km)#";
  		
    get_distance_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $user_interval);
	
	  $csv_string_dist = $csv_string_dist.'Total,'.$startdate.','.$enddate.','.$overall_dist; 

    $csv_string_dist_arr[$i] = $csv_string_dist;
    /////
    
  	
    //GET HALT DATA
    $sno_halt = 1;
    $title = $vname[$i]." (".$vserial[$i]."): Halt Report- From DateTime : ".$startdate."-".$enddate;
  	$csv_string_halt = $csv_string_halt.$title.":";
    $csv_string_halt = $csv_string_halt."SNo,Location,Arrival Time,Departure Time,Halt Duration (Hrs.min)#";
  		
    get_halt_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $user_interval);
	
    $csv_string_halt_arr[$i] = $csv_string_halt;    
    /////

    
  	//GET TRAVEL DATA
    $sno_travel = 1;
    $title = $vname[$i]." (".$vserial[$i]."): Travel Report- From DateTime : ".$startdate."-".$enddate;
  	$csv_string_travel = $csv_string_travel.$title."\n";
    $csv_string_travel = $csv_string_travel."SNo,StartTime,EndTime,StartPlace,EndPlace,Distance Travelled(km),Travel Time(H:m:s)#";

    get_travel_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $user_interval);

    $csv_string_travel_arr[$i] = $csv_string_travel; 
    /////        
    
    echo "\ndist_csv=".$csv_string_dist_arr[$i]."\n halt_csv=".$csv_string_halt_arr[$i]." \ntravel_csv=".$csv_string_travel_arr[$i];
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


//date_default_timezone_set('Asia/Calcutta');	
$last_datetime = date("Y-m-d H:i:s");
$last_datetime_t = strtotime($last_datetime);

//CREATE VTS XLS REPORT FILE
//$path ="C:\\Program Files/Apache Software Foundation/Apache2.2/htdocs/phpExcelWriter/test_write_excel/tmp/$filename_title";

//INITIALISE PARAMETERS FOR EXCEL SHEET
$filename_title = "VTS_REPORT_$date1 - $date2.xls";
$fullPath = "/var/www/html/vts/test/src/php/download/".$filename_title;
$fname = fopen($fullPath, 'w') or die("can't open file");

$workbook =& new writeexcel_workbook($fname);

$worksheet1 =& $workbook->addworksheet('Distance Report');
$worksheet2 =& $workbook->addworksheet('Halt Report');
$worksheet3 =& $workbook->addworksheet('Travel Report');

$border1 =& $workbook->addformat();
$border1->set_color('white');
$border1->set_bold();
$border1->set_size(12);
$border1->set_pattern(0x1);
$border1->set_fg_color('green');
$border1->set_border_color('yellow');
$border1->set_top(5);
$border1->set_bottom(5);
$border1->set_left(5);
$border1->set_align('center');
$border1->set_align('vcenter');
$border1->set_merge(); # This is the key feature

$text_format =& $workbook->addformat(array(
                                            bold    => 1,
                                            //italic  => 1,
                                            color   => 'blue',
                                            size    => 10,
                                            //font    => 'Comic Sans MS'
                                        ));

//**FIRST SHEET -HEADING DISTANCE
$report_title_dist = "Distance Report :($date1 to $date2)";
$worksheet1->write      (0, 0, $report_title_dist, $border1);
$worksheet1->write_blank(0, 1,                 $border1);
$worksheet1->write_blank(0, 2,                 $border1);
$worksheet1->write_blank(0, 3,                 $border1);

$worksheet1->write('A2', "SNo", $text_format);
$worksheet1->write('B2', "StartTime", $text_format);
$worksheet1->write('C2', "StopTime", $text_format);
$worksheet1->write('D2', "Distance", $text_format);


//**SECOND SHEET -HEADING HALT
$report_title_dist = "Halt Report :($date1 to $date2)";
$worksheet2->write      (0, 0, $report_title_dist, $border1);
$worksheet2->write_blank(0, 1,                 $border1);
$worksheet2->write_blank(0, 2,                 $border1);
$worksheet2->write_blank(0, 3,                 $border1);
$worksheet2->write_blank(0, 4,                 $border1);

$worksheet2->write('A2', "SNo", $text_format);
$worksheet2->write('B2', "Location", $text_format);
$worksheet2->write('C2', "Arrival Time", $text_format);
$worksheet2->write('D2', "Departure Time", $text_format);
$worksheet2->write('E2', "Halt Duration (Hrs.min)", $text_format);


//**THIRD SHEET -HEADING TRAVEL
$report_title_dist = "Travel Report :($date1 to $date2)";
$worksheet3->write      (0, 0, $report_title_dist, $border1);
$worksheet3->write_blank(0, 1,                 $border1);
$worksheet3->write_blank(0, 2,                 $border1);
$worksheet3->write_blank(0, 3,                 $border1);
$worksheet3->write_blank(0, 4,                 $border1);
$worksheet3->write_blank(0, 5,                 $border1);
$worksheet3->write_blank(0, 6,                 $border1);

$worksheet3->write('A2', "SNo", $text_format);
$worksheet3->write('B2', "StartTime", $text_format);
$worksheet3->write('C2', "EndTime", $text_format);
$worksheet3->write('D2', "StartPlace", $text_format);
$worksheet3->write('E2', "EndPlace", $text_format);
$worksheet3->write('F2', "Distance Travelled(km)", $text_format);
$worksheet3->write('G2', "Travel Time(H:m:s)", $text_format);

		
for($i=0;$i<sizeof($escalation_id);$i++)     //TOTAL DISTINCT ESCALATION
{      
    echo "\n";
    $vehicle_id_final = "";
    $alert_id_final = "";
    $csv_string_dist_final ="";
    $csv_string_halt_final ="";
    $csv_string_travel_final ="";
    
    $query_vehicle = "SELECT vehicle_id,alert_id,alert_duration FROM alert_assignment WHERE alert_id IN(SELECT alert_id FROM alert WHERE ".
                      "alert_name='report' AND status=1) AND escalation_id='$escalation_id[$i]' AND status=1";
    //echo "\n".$query_vehicle;     
    $result_vehicle = mysql_query($query_vehicle,$DbConnection);    
                           
    $k=0;
    $match_case = 0;
    
    while($row = mysql_fetch_object($result_vehicle))      // LOOP FOR MULTIPLE ALERTS
    {
      $vid_db = $row->vehicle_id;
      $alert_db = $row->alert_id;      
      $duration_db = $row->alert_duration;
      $duration_db_secs = $duration_db * 3600;    //IN SECONDS
      //$duration_db_secs = $duration_db * 60;            
      $alert_id_final = $alert_db;
      echo "\nvid=".$vid_db;
      
      for($j=0;$j<sizeof($vid);$j++)
      {
        if($vid_db == $vid[$j])
        {
          echo "\nCondition :Vehicle Matched";
          $match_case = 1;
          $query_last_datetime = "SELECT last_datetime FROM report_last_mail_status WHERE vehicle_id ='$vid_db' AND ".
                                  "alert_id='$alert_db' AND escalation_id='$escalation_id[$i]'  AND report_type='distance'";
          echo "\n".$query_last_datetime;
          $result_last_datetime = mysql_query($query_last_datetime,$DbConnection);
          $numrows = mysql_num_rows($result_last_datetime);
          
          $excel_flag = 0;
          
          if($numrows > 0)
          {
             echo "\nCondition :Last DateTime Found";
             $row_dt = mysql_fetch_object($result_last_datetime);
             $last_datetime_db = $row_dt->last_datetime;
             
             $last_datetime_db_t = strtotime($last_datetime_db);
             
             $diff_time_secs = ($last_datetime_t - $last_datetime_db_t);
             
             echo "\nTime1=".$last_datetime." ,TimeDB=".$last_datetime_db;
             
             if($diff_time_secs >= $duration_db_secs)  //IF ALERT TIME DURATION SATISFIED  EG. 6HRS, 12 HRS, 24HRS, 1 WEEK ETC
             {             
               $excel_flag =1;
               
               echo "\nCondition :Time Duration Satisfied";
               if($k==0)
               {
                 $vehicle_id_final = $vehicle_id_final.$vid_db;
                 //$alert_id_final = $alert_id_final.$alert_db;
                 $csv_string_dist_final = $csv_string_dist_final.$csv_string_dist_arr[$j];
                 $csv_string_halt_final = $csv_string_halt_final.$csv_string_halt_arr[$j];
                 $csv_string_travel_final = $csv_string_travel_final.$csv_string_travel_arr[$j];
                 $k++;
               }
               else
               {
                 $vehicle_id_final = $vehicle_id_final.",".$vid_db;
                 //$alert_id_final = $alert_id_final.":".$alert_db;
                 $csv_string_dist_final = $csv_string_dist_final."$".$csv_string_dist_arr[$j];
                 $csv_string_halt_final = $csv_string_halt_final."$".$csv_string_halt_arr[$j];
                 $csv_string_travel_final = $csv_string_travel_final."$".$csv_string_travel_arr[$j]; 
                 $k++;             
               }
               //UPDATE LAST DATETIME STATUS
                $query_update1 = "UPDATE report_last_mail_status SET last_datetime='$last_datetime' WHERE vehicle_id ='$vid_db' AND ".
                                    "alert_id='$alert_db' AND escalation_id='$escalation_id[$i]' AND report_type='distance'";
                echo "\nUPDATE1:".$query_update1;
                $result_last_datetime1 = mysql_query($query_update1,$DbConnection);
              } 
              echo "\nvid_final1=".$vehicle_id_final;            
          }              
          else
          {
             $excel_flag =1;
             
             echo "\nElse Condition :New Datetime Inserted";
             if($k==0)
             {
               $vehicle_id_final = $vehicle_id_final.$vid_db;
               //$alert_id_final = $alert_id_final.$alert_db;
               $csv_string_dist_final = $csv_string_dist_final.$csv_string_dist_arr[$j];
               $csv_string_halt_final = $csv_string_halt_final.$csv_string_halt_arr[$j];
               $csv_string_travel_final = $csv_string_travel_final.$csv_string_travel_arr[$j];
               $k++;
             }
             else
             {
               $vehicle_id_final = $vehicle_id_final.",".$vid_db;
               //$alert_id_final = $alert_id_final.":".$alert_db;
               $csv_string_dist_final = $csv_string_dist_final."$".$csv_string_dist_arr[$j];
               $csv_string_halt_final = $csv_string_halt_final."$".$csv_string_halt_arr[$j];
               $csv_string_travel_final = $csv_string_travel_final."$".$csv_string_travel_arr[$j]; 
               $k++;             
             }
              //UPDATE LAST DATETIME STATUS
              $query_update2 = "insert into report_last_mail_status(last_datetime,vehicle_id,alert_id,report_type,escalation_id)".
                              "VALUES('$last_datetime','$vid_db','$alert_db','distance','$escalation_id[$i]')";
              echo "\nUPDATE2:".$query_update2;
              $result_last_datetime2 = mysql_query($query_update2,$DbConnection);
              
              echo "\nvid_final1=".$vehicle_id_final2;
          }                                        
          break;   //STORE AND BREAK INNER LOOP
        }  // COMPARISON IF CLOSED
      }   // FOR CLOSED
    }    //WHILE CLOSED                       
    //NEW CODE
    
      
    if($match_case && $vehicle_id_final!="")
    {
      //******************* WRITE TO EXCEL SHEET FILE***************************////
        //***OPEN -WRITE DISTANCE REPORT
        echo "\ncsv_string_dist_final=".$csv_string_dist_final;
        
        $sheet1 = explode('$',$csv_string_dist_final);
        $r=2;   //row
        echo "\nsize sheet1=".sizeof($sheet1);
        for($p=0;$p<sizeof($sheet1);$p++)
        {    
          $sheet1_row = explode('#',$sheet1[$p]);
          echo "\n\nsize sheet1_row=".sizeof($sheet1_row);
          for($q=0;$q<sizeof($sheet1_row);$q++)
          {
            $sheet1_data = explode(',',$sheet1_row[$q]);
            echo "\nsize sheet1_data=".sizeof($csv3);
            for($m=0;$m<sizeof($sheet1_data);$m++)
            {
              //  echo "<br>size csv3=".sizeof($csv3);
              $title = $sheet1_data[$m]." and ".$m;
              $worksheet1->write($r,$m, $title);
            }
            //  echo "<br>";              
            $r++; 
          }
          
          $worksheet1->write_blank($r, 1,                 $border1);
          $worksheet1->write_blank($r, 2,                 $border1);
          $worksheet1->write_blank($r, 3,                 $border1);
          $worksheet1->write_blank($r, 4,                 $border1);
          //  echo "<br><br>";           
        }
        //***CLOSED-WRITE DISTANCE REPORT 
                                           
                                           
        //***OPEN -WRITE HALT REPORT        
        echo "\ncsv_string_halt_final=".$csv_string_halt_final;
        $sheet2 = explode('$',$csv_string_halt_final);
        $r=2;   //row
        //echo "<br>size sheet1=".sizeof($sheet1);
        for($p=0;$p<sizeof($sheet2);$p++)
        {    
          $sheet1_row = explode('#',$sheet2[$p]);
          //echo "<br>size sheet1_row=".sizeof($sheet1_row);
          for($q=0;$q<sizeof($sheet2_row);$q++)
          {
            $sheet2_data = explode(',',$sheet2_row[$q]);
            //echo "<br>size sheet1_data=".sizeof($csv3);
            for($m=0;$m<sizeof($sheet2_data);$m++)
            {
              //  echo "<br>size csv3=".sizeof($csv3);
              $title = $sheet2_data[$m]." and ".$m;
              $worksheet2->write($r,$m, $title);
            }
            //  echo "<br>";              
            $r++; 
          }
          
          $worksheet2->write_blank($r, 1,                 $border1);
          $worksheet2->write_blank($r, 2,                 $border1);
          $worksheet2->write_blank($r, 3,                 $border1);
          $worksheet2->write_blank($r, 4,                 $border1);
          $worksheet2->write_blank($r, 5,                 $border1);          
          //  echo "<br><br>";           
        }                
        //***CLOSED-WRITE HALT REPORT
        
        
        //***OPEN -WRITE TRAVEL REPORT        
        echo "\ncsv_string_travel_final=".$csv_string_travel_final;
        $sheet3 = explode('$',$csv_string_travel_final);
        $r=2;   //row
        //echo "<br>size sheet1=".sizeof($sheet1);
        for($p=0;$p<sizeof($sheet3);$p++)
        {    
          $sheet3_row = explode('#',$sheet3[$p]);
          //echo "<br>size sheet1_row=".sizeof($sheet1_row);
          for($q=0;$q<sizeof($sheet3_row);$q++)
          {
            $sheet3_data = explode(',',$sheet3_row[$q]);
            //echo "<br>size sheet1_data=".sizeof($csv3);
            for($m=0;$m<sizeof($sheet3_data);$m++)
            {
              //  echo "<br>size csv3=".sizeof($csv3);
              $title = $sheet3_data[$m]." and ".$m;
              $worksheet3->write($r,$m, $title);
            }
            //  echo "<br>";              
            $r++; 
          }
          
          $worksheet3->write_blank($r, 1,                 $border1);
          $worksheet3->write_blank($r, 2,                 $border1);
          $worksheet3->write_blank($r, 3,                 $border1);
          $worksheet3->write_blank($r, 4,                 $border1);
          $worksheet3->write_blank($r, 5,                 $border1);          
          $worksheet3->write_blank($r, 6,                 $border1);
          $worksheet3->write_blank($r, 7,                 $border1);          
          //  echo "<br><br>";           
        }                
        //***CLOSED-WRITE TRAVEL REPORT        
                                   
      //******************* CLOSE EXCEL FILE************************************////                             
      
      $email_message = ""; 
    	$fileatt_final="";
    	$fileatt_type_final="";
    	$fileatt_name_final="";
        
      //SAVE FINAL MAIL CONTENT TO MAIL INFO TABLE	  	
      $current_dt_tmp1 = date("Y_m_d_H_i_s");
      $datetime_sent = $last_datetime;
      
      /*$download_file = "distance_report_".$current_dt_tmp1.$i.".csv";
      //$path = "/var/www/html/vts/test/src/php/download";
      $path = "/var/www/html/vts/beta/src/php/download";
      
      $fullPath = $path."/".$download_file;
      echo "\npath=".$fullPath; */
       
      //$fh1 = fopen($fullPath, 'w') or die("can't open file");
      //fwrite($fh1, $csv_string_final);
      //fclose($fh1);
      
      
      //SAVE FINAL MAIL CONTENT TO MAIL INFO TABLE	
    	//$email_from="support@iembsys.co.in";
    	$query_name = "SELECT vehicle_name FROM vehicle WHERE vehicle_id IN($vehicle_id_final) AND status=1";
    	$result_name = mysql_query($query_name,$DbConnection);
    	$vname_str ="";
      $v=0;
      while($row_name = mysql_fetch_object($result_name))
    	{
    	 if($v==0)
    	 {
    	   $vname_str = $vname_str.$row_name->vehicle_name;
       }
       else
       {
        $vname_str = $vname_str.",".$row_name->vehicle_name;
       }
       $v++;
      }
      
      $email_from_d="support@iembsys.co.in";
    	$email_subject_d="VTS Report:".$date1." -".$date2;
    	$email_message_d="VTS Report:".$date1." -".$date2." (".$vname_str.")";
    	$email_to_d = $person_email[$i];  //"rizwan@iembsys.com";
    	
      $fileatt_d = $fullPath;
      $fileatt_type_d = "csv";
      $fileatt_name_d = "VTS Report:".$date1." -".$date2." (".$vname_str.").csv";	
      
      echo "\nalert_id_final=".$alert_id_final;
      echo "\nvehicle_id_final=".$vehicle_id_final;
      echo "\nperson_name=".$person_name[$i];
      
      echo "\nemail_from=".$email_from_d;
      echo "\nemail_subject=".$email_subject_d;
      echo "\nemail_to=".$email_to_d;
      echo "\nemail_message=".$email_message_d."\n";      
      
      
      $query4 = "INSERT INTO mail_info_report(alert_id,vehicle_id,escalation_id,person_name,".
            "fileatt,fileatt_type,fileatt_name,email_from,email_subject,email_message,email_to,datetime_sent,status) VALUES(".
            "'$alert_id_final','$vehicle_id_final','$escalation_id[$i]','$person_name[$i]','$fileatt_d','$fileatt_type_d','$fileatt_name_d',".
            "'$email_from_d','$email_subject_d','$email_message_d','$email_to_d','$datetime_sent',1)";                
      //echo "\n".$query4;
      $result4 = mysql_query($query4,$DbConnection);    
    } //IF MATCH CASE CLOSED
    
} //OUTER WHILE CLOSED   

echo "\n DISTANCE LOGIC CLOSED\n";        
		 
?>
