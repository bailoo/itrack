<?php

set_time_limit(50000);
//set_time_limit(0);
//$back_dir1 = "../../../";
$abspath = "/var/www/html/vts/beta/src/php/";
//$back_dir1 = "../";
include_once($abspath."/common_xml_element.php");
require_once $abspath."excel_lib/class.writeexcel_workbook.inc.php";
require_once $abspath."excel_lib/class.writeexcel_worksheet.inc.php";
//include_once('util_session_variable.php');
//include_once('util_php_mysql_connectivity.php');
include_once($abspath."get_all_dates_between.php");
include_once($abspath."sort_xml.php");
include_once($abspath."calculate_distance.php");
include_once("get_daily_data.php");
include_once($abspath."util.hr_min_sec.php");

$DBASE = "iespl_vts_beta";
$USER = "root";
$PASSWD = "mysql";
//$HOST = "111.118.181.156";
include_once("../../database_ip.php");
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Could Not Connect to Server");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
//echo "\nDB=".$DbConnection;	
	
function tempnam_sfx($path, $suffix)
{
  do
  {
     //$file = $path."/".mt_rand().$suffix;
     $file = $path.$suffix;
     $fp = @fopen($file, 'x');
  }
  while(!$fp);

  fclose($fp);
  return $file;
}


//################ SET VARIABLE ##############/

$current_month=date('m');
$current_year=date('Y');

if($current_month==1)
{
  $previous_month=12;
  $previous_year = $current_year -1;
}
else
{
  $previous_month=$current_month-1;
  $previous_year = $current_year;
}

if($previous_month<10)
{
  $previous_month = "0".$previous_month;
}
//COMMENT THIS BLOCK -TEMPORARY CODE FOR TESTING
//$previous_month = $current_month;
//$previous_year = $current_year;
///////////////////////////////////////////////


$total_days=date('t',mktime(0,0,0,$previous_month,1,$previous_year));		// TOTAL DAYS IN PREVIOUS MONTH

echo "\nPREV_MONTH1=".$previous_month." TOTAL DAYS=".$total_days; 
$prev_date_title = $previous_year."_".$previous_month;
echo "PREV=".$prev_date_title;

switch($previous_month) 
{
  case 1 :
      $stringmonth = "January";
      break;
  case 2 :
      $stringmonth = "February";
      break;
  case 3 :
      $stringmonth = "March";
      break;
  case 4 :
      $stringmonth = "April";
      break;
  case 5 :
      $stringmonth = "May";
      break;
  case 6 :
      $stringmonth = "June";
      break;
  case 7 :
      $stringmonth = "July";
      break;
  case 8 :
      $stringmonth = "August";
      break;
  case 9 :
      $stringmonth = "September";
      break;
  case 10 :
      $stringmonth = "October";
      break;
  case 11 :
      $stringmonth = "November";
      break;
  case 12 :
      $stringmonth = "December";
      break;
}
   
//echo "\nPREV_MONTH2=".$stringmonth;
//############### VARIABLE CLOSED ############/
         
/////********* CREATE EXCEL FILE *******************///////
$filename_title = 'MONTHLY_WOCKHARDT_VTS_REPORT_1_'.$prev_date_title;
//ONLINE
$file_path = "/var/www/html/vts/beta/src/php/monthly_report/wockhardt/report1/excel_data/".$filename_title;

//OFFLINE
//$file_path = "D:\\SERVER_GO4HOSTING/ITRACKSOLUTION.CO.IN/MAIL_SERVICE/WOCKHARDT/REPORT_MONTHLY/REPORT1/excel_data/".$filename_title;
  
echo "\nfile_path=".$file_path;
$fname = tempnam_sfx($file_path, ".xls");    

//echo "\nPREV_MONTH2=".$stringmonth;
////********** CREATE EXCEL WORKBOOK  ****************//////
//echo "\nfname=".$fname;  
$workbook =& new writeexcel_workbook($fname);                     //******* ADD WORKBOOK
$worksheet =& $workbook->addworksheet("WORKSHEET1");
//$worksheet->set_row(0, 25); 

# Create a border format
$border1 =& $workbook->addformat();
$border1->set_bold();
$border1->set_size(8);

$border2 =& $workbook->addformat();
$border2->set_size(8);
/*$border1->set_color('white');
$border1->set_bold();
$border1->set_size(10);
$border1->set_pattern(0x1);
//$border1->set_fg_color('black');
//$border1->set_border_color('black');
$border1->set_top(6);
$border1->set_bottom(6);
$border1->set_left(6);
$border1->set_align('center');
$border1->set_align('vcenter');
$border1->set_merge(); # This is the key feature */

/////********************** 
 
//$report_title = "WOCKHARDT TEST";
//$worksheet->write ($r, 0, $report_title, $border1);

//$days=31;

$r=0;
$c=0;
$b=0;

for($b=0;$b<2;$b++)
{
  $worksheet->write_blank($r, $b,$border1);
}        

$c = $c+2;

  
for($i=1;$i<=$total_days;$i++)
{
  //echo "\nc=".$c;   
  switch($i) 
  {
    case 1 :
        $stringday = "Ist";
        break;
    case 2 :
        $stringday = "2nd";
        break;
    case 3 :
        $stringday = "3rd";
        break;
    case 4 :
        $stringday = "4rth";
        break;
    case 5 :
        $stringday = "5th";
        break;
    case 6 :
        $stringday = "6th";
        break;
    case 7 :
        $stringday = "7th";
        break;
    case 8 :
        $stringday = "8th";
        break;
    case 9 :
        $stringday = "9th";
        break;
    case 10 :
        $stringday = "10th";
        break;
    case 11 :
        $stringday = "11th";
        break;
    case 12 :
        $stringday = "12th";
        break;
    case 13 :
        $stringday = "13th";
        break;
    case 14 :
        $stringday = "14th";
        break;
    case 15 :
        $stringday = "15th";
        break;
    case 16 :
        $stringday = "16th";
        break;
    case 17 :
        $stringday = "17th";
        break;
    case 18 :
        $stringday = "18th";
        break;
    case 19 :
        $stringday = "19th";
        break;
    case 20 :
        $stringday = "20th";
        break;
    case 21 :
        $stringday = "21st";
        break;
    case 22 :
        $stringday = "22nd";
        break;
    case 23 :
        $stringday = "23rd";
        break;
    case 24 :
        $stringday = "24rth";
        break;
    case 25 :
        $stringday = "25th";
        break;
    case 26 :
        $stringday = "26th";
        break;
    case 27 :
        $stringday = "27th";
        break;
    case 28 :
        $stringday = "28th";
        break;
    case 29 :
        $stringday = "29th";
        break;
    case 30 :
        $stringday = "30th";
        break;
    case 31 :
        $stringday = "31st";
        break;               
  }

  $space1="                                                            ";
  $space2="                                                                                 ";
    
  $caption = $space1."MTD FOR -".$stringday." ".$stringmonth.$space2;
  $worksheet->write($r,$c, $caption, $border1);
  //$worksheet->set_column($c, $c, 10);
  $c++;

  $worksheet->write_blank($r, $c,$border1);
  $c++;
  $worksheet->write_blank($r, $c,$border1);
  $c++;
  $worksheet->write_blank($r, $c,$border1);
  $c++; 
  $worksheet->write_blank($r, $c,$border1);
  $c++;  
}

$space1="                                                    ";
$space2="                                                                                                     ";

$caption = $space2."Total ".$stringmonth.$space2;
$worksheet->write($r,$c, $caption, $border1);
$c++;
$worksheet->write_blank($r, $c,$border1);
$c++;
$worksheet->write_blank($r, $c,$border1);
$c++;
$worksheet->write_blank($r, $c,$border1);
$c++;
$worksheet->write_blank($r, $c,$border1);
$c++;
$worksheet->write_blank($r, $c,$border1);
$c++;
$worksheet->write_blank($r, $c,$border1);
$c++;
$worksheet->write_blank($r, $c,$border1);
$c++;

$r++;
$c=0;
$worksheet->write($r,$c, "Sr.No", $border1);
$worksheet->set_column($c, $c, 6);
$c++;
$worksheet->write($r,$c, "Vehicle Name", $border1);
$worksheet->set_column($c, $c, 15);
$c++;

echo "<br>totaldays=".$total_days;

for($i=1;$i<=$total_days;$i++)
{
  //echo "\nday".$i;
  $worksheet->write($r,$c, "Total Dist", $border1);
  $worksheet->set_column($c, $c, 9);
  $c++;
  $worksheet->write($r,$c, "Op Hr Dist", $border1);
  $worksheet->set_column($c, $c, 9);
  $c++;
  $worksheet->write($r,$c, "Non Op Hr Dist", $border1);
  $worksheet->set_column($c, $c, 13);
  $c++;
  $worksheet->write($r,$c, "Total No of Halts", $border1);
  $worksheet->set_column($c, $c, 14);
  $c++;
  $worksheet->write($r,$c, "Avg Halt time", $border1);
  $worksheet->set_column($c, $c, 12);
  $c++;  
}

$worksheet->write($r,$c, "Total Dist", $border1);
$worksheet->set_column($c, $c, 9);
$c++;
$worksheet->write($r,$c, "Op Hr Dist", $border1);
$worksheet->set_column($c, $c, 9);
$c++;
$worksheet->write($r,$c, "Non Op Hr Dist", $border1);
$worksheet->set_column($c, $c, 13);
$c++;
$worksheet->write($r,$c, "Avg Total Dist", $border1);
$worksheet->set_column($c, $c, 13);
$c++;
$worksheet->write($r,$c, "Avg Op hr Dist", $border1);
$worksheet->set_column($c, $c, 13);
$c++;
$worksheet->write($r,$c, "Avg Non Op hr Dist", $border1);
$worksheet->set_column($c, $c, 15);
$c++;
$worksheet->write($r,$c, "Total No of Halts", $border1);
$worksheet->set_column($c, $c, 13);
$c++;
$worksheet->write($r,$c, "Avg Halt Time", $border1);
$worksheet->set_column($c, $c, 12);
$c++;
 

//################## ADD DATA TO EXCEL FIELD #####################//
/*if($previous_month < 9)
{
  $previous_month = "0".$previous_month;
}*/

$prev_month_str = $previous_year."-".$previous_month;
      
$prev_date_tmp1 = $previous_year."-".$previous_month."-01 00:00:00";
$prev_date_tmp2 = $previous_year."-".$previous_month."-".$total_days." 23:59:59";

$query1 = "SELECT account_id FROM account WHERE user_id='wockhardt'";
$result1 = mysql_query($query1,$DbConnection);
$row1 = mysql_fetch_object($result1);
$account_id = $row1->account_id;

$query2 = "SELECT vehicle_id FROM vehicle_grouping WHERE account_id='$account_id'";
$result2 = mysql_query($query2,$DbConnection);
$vehicle_id_str ="";
while($row2 = mysql_fetch_object($result2))
{
  $vehicle_id_str = $vehicle_id_str.$row2->vehicle_id.",";
}
$vehicle_id_str = substr($vehicle_id_str, 0, -1);
//$vehicle_id_str ="1141";
//$vehicle_id_str = "1091,1125";
//$vehicle_id_str = "1086"; //FOR TESTING


$prev_date_tmp1_q = explode(' ',$prev_date_tmp1);
$prev_date_tmp2_q = explode(' ',$prev_date_tmp2);

 
$query = "SELECT vehicle_assignment.device_imei_no, schedule_assignment.vehicle_id, schedule_assignment.min_operation_time, ".
        "schedule_assignment.max_operation_time, schedule_assignment.date_from, schedule_assignment.date_to,".
        "schedule_assignment.by_day, schedule_assignment.day, schedule_assignment.location_id FROM ".
        "schedule_assignment, vehicle_assignment WHERE vehicle_assignment.vehicle_id = schedule_assignment.vehicle_id AND ".
        "vehicle_assignment.vehicle_id IN($vehicle_id_str) AND ".
        "schedule_assignment.date_from <='$prev_date_tmp1_q[0]' AND schedule_assignment.date_to >= '$prev_date_tmp2_q[0]' AND ".
        "schedule_assignment.status=1";
echo "\nQUERY_MAIN=".$query."\n";
//echo "\nDBCon=".$DbConnection;
$result = mysql_query($query,$DbConnection);

while($row = mysql_fetch_object($result))
{
  $vid_tmp = $row->vehicle_id;
  $imei[] = $row->device_imei_no;
  $date_from[] = $row->date_from;
  $date_to[] = $row->date_to;
  $min_operation_time[] = $row->min_operation_time;
  $max_operation_time[] = $row->max_operation_time;
  $by_day[] = $row->by_day;
  $day[] = $row->day;
  $location_id_tmp = $row->location_id;
  
  $query1 = "SELECT vehicle_name FROM vehicle WHERE vehicle_id='$vid_tmp' AND status=1";
  //echo "\n".$query1."\n";
  
  $result1 = mysql_query($query1,$DbConnection);
  if($row1 = mysql_fetch_object($result1))
  {
    $vname_tmp = $row1->vehicle_name;
    //echo "\nvnamex=".$vname_tmp;   
    $vname[] = $vname_tmp;
  } 
  
    
  $query2 = "SELECT geo_point FROM schedule_location WHERE location_id IN($location_id_tmp) AND status=1";
  //echo "\nQQQQQQQQQQ2=".$query2;
  $result2 = mysql_query($query2,$DbConnection);
  while($row2 = mysql_fetch_object($result2))
  {
    $geo_point[] = $row2->geo_point; 
  }   
}

$r++;     //LOOP THROUGH ROWS

echo "\nSizeImei=".sizeof($imei);
//for($x=0;$x<2;$x++) 
for($x=0;$x<sizeof($imei);$x++)                               //IMEI LOOP
{            
  //echo "\nvnamex=".$vname[$x];
  $tmpsno = $x+1;
  echo "\nSERIAL=".$tmpsno." ,LEN=".sizeof($imei)."\n";

  $serial = $x+1;
  $c=0;
  $worksheet->write($r,$c, $serial, $border2);               //SERIAL
  //$worksheet->set_column($c, $c, 6);
  $c++;
  $worksheet->write($r,$c, $vname[$x], $border2);            //VEHICLE NAME
  //$worksheet->set_column($c, $c, 15);
  $c++;
  
  $total_monthly_dist = 0;
  $total_monthly_ophr_dist = 0;
  $total_monthly_non_ophr_dist = 0;
  $total_monthly_avg_dist = 0;
  $total_monthly_avg_ophr_dist = 0;
  $total_monthly_avg_non_ophr_dist = 0;
  $total_monthly_halt = 0;
  $total_monthly_halt_time = 0;
  $total_monthly_avg_halt_time = 0;  
  
  echo "\nTotalDays=".$total_days;
  
  for($i=1;$i<=$total_days;$i++)                            //DATE LOOP  
  {
    echo "\n imei=".$imei[$x], ",DAY=".$i;
    if($i<9)
      $date_tmp =  $previous_year."-".$previous_month."-0".$i;
    else
      $date_tmp =  $previous_year."-".$previous_month."-".$i;
    
    $date1 = $date_tmp." 00:00:00"; 
    $date2 = $date_tmp." 23:59:59";        
         
    $op_date1 = $date_tmp." ".$min_operation_time[$x];
    $op_date2 = $date_tmp." ".$max_operation_time[$x];
    //echo "\nONE::opdate1=".$op_date1." ,op_date2=".$op_date2;
    
    $op_date1 = date('Y-m-d H:i:s',strtotime($op_date1));      //FORMAT WITH LEADING ZEROS
    $op_date2 = date('Y-m-d H:i:s',strtotime($op_date2));
    echo "\nTWO::opdate1=".$op_date1." ,op_date2=".$op_date2;
    
    if($by_day[$x] ==1)
    {    
      //echo "\nDAY[x]=".$day[$x];      
      //if( ($date_tmp >= $date_from[$x]) && ($date_tmp <= $date_to[$x]) )
      //{
        $day_db = explode(",",$day[$x]);     
              
        get_All_Dates($date_from[$x], $date_to[$x], &$userdates);   
        $date_size = sizeof($userdates);
      
        for($y=0;$y<=($date_size-1);$y++)                       //CHECKS EVERYTIME FOR ONE RECORD AND BREAKS- OUTER LOOP
      	{           
          $wflag = 0;
          //echo "\nUserDates[y]=".$userdates[$y];
          $timestmp = strtotime($userdates[$y]);
          $weekday = date("w",$timestmp);     //0 =SUN, 6=SAT
          //echo "\nWEEKDAY=".$weekday;
        
          for($z=0;$z<sizeof($day_db);$z++)                     //CHECKS EVERYTIME FOR ONE RECORD AND BREAKS- INNER LOOP
          {
            //echo "\nWEEKDAY=".$weekday." ,day_db=".$day_db[$z];
            if($weekday == $day_db[$z])
            {                            
              //echo "\nDAY SPECIFIED";
              add_data_to_field($imei[$x], $vname[$x], $date1, $date2, $op_date1, $op_date2, $geo_point, $border1, $border2, $worksheet);          
              $wflag = 1;
              break;
            }
          }       	 
          if($wflag)
          {
            break;
          }
        } //INNER FOR CLOSED
        
        if(!$wflag)       //CALL TO RETURN DUMMY RECORDS IN EXCEL FIELD
        {
          add_data_to_field($imei[$x], $vname[$x], $date1, $date2, $op_date1, $op_date2, $geo_point, $border1, $border2, $worksheet);
        }
     // } //INNER IF CLOSED
    }  //OUTER IF CLOSED
    else
    {
      //echo "\nDAY -NOT SPECIFIED";             
      add_data_to_field($imei[$x], $vname[$x], $date1, $date2, $op_date1, $op_date2, $geo_point, $border1, $border2, $worksheet);   
    }    
  } // DAY LOOP CLOSED
  
  //########### AFTER DAY LOOP- ADD MONTHLY TOTAL
  //echo "\nvname=".$vname[$x]." ,R=".$r." ,C=".$c;        
  $total_monthly_avg_dist = $total_monthly_dist /$total_days ;
  $total_monthly_avg_ophr_dist = $total_monthly_ophr_dist / $total_days;
  $total_monthly_avg_non_ophr_dist = $total_monthly_non_ophr_dist / $total_days;    
  
  if($total_monthly_halt > 0)
  {
    $total_monthly_avg_halt_time = $total_monthly_halt_time / $total_monthly_halt;
  }   
  
  $hms_2 = secondsToTime($total_monthly_avg_halt_time);
  $total_monthly_avg_halt_time_formated = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
  
  $worksheet->write($r,$c, round($total_monthly_dist,2), $border2);              
  //$worksheet->set_column($c, $c, 9);
  $c++;
  $worksheet->write($r,$c, round($total_monthly_ophr_dist,2), $border2);        
  //$worksheet->set_column($c, $c, 9);
  $c++;
  $worksheet->write($r,$c, round($total_monthly_non_ophr_dist,2), $border2);                
  //$worksheet->set_column($c, $c, 10);
  $c++;
  $worksheet->write($r,$c, round($total_monthly_avg_dist,2), $border2);          
  //$worksheet->set_column($c, $c, 10);
  $c++;  
  $worksheet->write($r,$c, round($total_monthly_avg_ophr_dist,2), $border2);            
  //$worksheet->set_column($c, $c, 9);
  $c++;
  $worksheet->write($r,$c, round($total_monthly_avg_non_ophr_dist,2), $border2);        
  //$worksheet->set_column($c, $c, 9);
  $c++;
  $worksheet->write($r,$c, round($total_monthly_halt,2), $border2);                
  //$worksheet->set_column($c, $c, 13);
  $c++;
  $worksheet->write($r,$c, round($total_monthly_avg_halt_time_formated,2), $border2);
  //$worksheet->set_column($c, $c, 13);
  $c++;           
      
  $r++;
  
  echo "\nDAYS COMPLETED: C=".$c." ,total_monthly_avg_dist=".$total_monthly_avg_dist." ,total_monthly_avg_ophr_dist=".$total_monthly_avg_ophr_dist." ,total_monthly_avg_non_ophr_dist=".$total_monthly_avg_non_ophr_dist;  
} // IMEI LOOP CLOSED  

$workbook->close(); //CLOSE WORKBOOK
echo "\nWORKBOOK CLOSED"; 


//########### SEND MAIL ##############//
$rno = "_".rand();
//$to = 'ashish@iembsys.co.in';
//$to = 'rizwan@iembsys.com';
//$to = 'sanchan@wockhardtfoundation.org';   
$to = 'jyoti.jaiswal@iembsys.com';
$subject = $filename_title." (".$prev_month_str.")".$rno;
$message = $filename_title." (".$prev_month_str.")".$rno; 
$random_hash = md5(date('r', time()));  
$headers = "From: support@iembsys.co.in\r\n";
//$headers .= "Reply-To: taseen@iembsys.com\r\n"; 
$headers .= "Cc: rizwan@iembsys.com";
//$headers .= "Cc: jyoti.jaiswal@iembsys.com";
//$headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com";
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
$filename_title = $filename_title.".xls";
$file_path = $file_path.".xls";

echo "\nFILE PATH=".$file_path;

include_once("send_mail_api.php");
//####################################//


unlink($file_path);  

  // FUNCTION ADD DATA TO FIELD
  
function add_data_to_field($imei, $vname, $date1, $date2, $op_date1, $op_date2, $geo_point, $border1, $border2, $worksheet)
{      
    global $worksheet;
    global $r;
    global $c;
    
    $total_dist = 0;
    $ophr_dist = 0;
    $non_ophr_dist = 0;
    $total_nof_halt = 0;
    $total_halt_time = 0;
    $avg_halt_time = 0; 
        
    global $total_monthly_dist;
    global $total_monthly_ophr_dist;
    global $total_monthly_non_ophr_dist;
    global $total_monthly_avg_dist;
    global $total_monthly_avg_ophr_dist;
    global $total_monthly_avg_non_ophr_dist;
    global $total_monthly_halt;
    global $total_monthly_avg_halt_time;    

    //####### CALL COMBINED FUNCTION ########//
    $daily_data = get_daily_data($imei, $vname, $date1, $date2, $op_date1, $op_date2, $geo_point);
    //#######################################//    
    $tmpdata = explode("#", $daily_data);
    $total_dist = $tmpdata[0];
    $ophr_dist = $tmpdata[1];
    $non_ophr_dist = $tmpdata[2];
    $total_nof_halt = $tmpdata[3];
    $total_halt_time = $tmpdata[4];
    $avg_halt_time = $tmpdata[5];    
    
    $hms_2 = secondsToTime($avg_halt_time);
    $avg_halt_time_formated = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
    
    //echo "\nDate=".$date1." ,R1=".$r." ,C1=".$c;        
    $worksheet->write($r,$c, round($total_dist,2), $border2);          //TOTAL DIST
    //$worksheet->set_column($c, $c, 8);
    $c++;
    $worksheet->write($r,$c, round($ophr_dist,2), $border2);            //AVG DIST
    //$worksheet->set_column($c, $c, 8);
    $c++;
    $worksheet->write($r,$c, round($non_ophr_dist,2), $border2);          //TOTAL NO OF HALTS
    //$worksheet->set_column($c, $c, 13);
    $c++;
    $worksheet->write($r,$c, round($total_nof_halt,2), $border2);       //AVG HALT TIME
    //$worksheet->set_column($c, $c, 13);
    $c++;
    $worksheet->write($r,$c, round($avg_halt_time_formated,2), $border2);       //AVG HALT TIME
    //$worksheet->set_column($c, $c, 13);
    $c++;
    
    echo "\n DATA=".$daily_data;
    //GET TOTAL MONTHLY REPORT
    $total_monthly_dist = $total_monthly_dist + $total_dist;
    $total_monthly_ophr_dist = $total_monthly_ophr_dist + $ophr_dist;
    $total_monthly_non_ophr_dist = $total_monthly_non_ophr_dist + $non_ophr_dist;
    $total_monthly_halt = $total_monthly_halt + $total_nof_halt;
    $total_monthly_halt_time = $total_monthly_halt_time + $total_halt_time;              
}   
                     
?>
