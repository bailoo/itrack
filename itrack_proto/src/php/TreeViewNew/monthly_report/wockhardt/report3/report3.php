<?php
set_time_limit(36000);
//set_time_limit(0);
//$back_dir1 = "../../../";
//$back_dir1 = "../";
$abspath = "/var/www/html/vts/beta/src/php/";
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
//OR $total_days = date("t", mktime(0,0,0, date("n") - 1));  //GET TOTAL DAYS IN LAST MONTH

echo "\nPREV_MONTH1=".$previous_month." TOTAL DAYS=".$total_days; 

$prev_date_title = $previous_year."_".$previous_month;

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
/////********* CREATE EXCEL FILE *******************///////
$filename_title = 'MONTHLY_WOCKHARDT_VTS_REPORT_3_'.$prev_date_title;  
//$file_path = "/var/www/html/vts/beta/src/php/download/".$filename_title;
//ONLINE
$file_path = "/var/www/html/vts/beta/src/php/monthly_report/wockhardt/report3/excel_data/".$filename_title;

//OFFLINE
//$file_path = "D:\\SERVER_GO4HOSTING/ITRACKSOLUTION.CO.IN/MAIL_SERVICE/WOCKHARDT/REPORT_MONTHLY/REPORT3/excel_data/".$filename_title;

$fname = tempnam_sfx($file_path, ".xls");    

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

//$date = '2012-07-25 12:21:00';
//echo "\nTIME SHIFT=".date('h:i A', strtotime($date));
$border1->set_align('center');
$border1->set_align('vcenter');
$border1->set_align('vjustify');
$border1->set_text_wrap();

$border2->set_align('center');
$border2->set_align('vcenter');
$border2->set_align('vjustify');
$border2->set_text_wrap();

$r=0;
$c=0;

$worksheet->write($r,$c, "Sr.No", $border1);
$worksheet->set_column($c, $c, 6);
$c++;
$worksheet->write($r,$c, "Van No", $border1);
$worksheet->set_column($c, $c, 25);
$c++;
$worksheet->write($r,$c, "Location", $border1);
$worksheet->set_column($c, $c, 15);
$c++;
$worksheet->write($r,$c, "No Movement Date", $border1);
$worksheet->set_column($c, $c, 35);
$c++;
$worksheet->write($r,$c, "No Device Reporting", $border1);
$worksheet->set_column($c, $c, 35);
$c++;         


$prev_month_str = $previous_year."-".$previous_month;
//echo "\nprev_month_str=".$prev_month_str;

$query1 = "SELECT account_id FROM account WHERE user_id='wockhardt' and status=1";
$result1 = mysql_query($query1,$DbConnection);
$row1 = mysql_fetch_object($result1);
$account_id = $row1->account_id;

$query2 = "SELECT vehicle_id FROM vehicle_grouping WHERE account_id='$account_id' and status=1";
$result2 = mysql_query($query2,$DbConnection);
$vehicle_id_str ="";
while($row2 = mysql_fetch_object($result2))
{
  $vehicle_id_str = $vehicle_id_str.$row2->vehicle_id.",";
}
$vehicle_id_str = substr($vehicle_id_str, 0, -1);

//$vehicle_id_str = "1087";       //FOR TESTING
//$vehicle_id_str = "1135";
//$vehicle_id_str = "1121,1122";

/*$query = "SELECT DISTINCT vehicle_assignment.device_imei_no, schedule_assignment.vehicle_id FROM vehicle_assignment, schedule_assignment WHERE ".
        "vehicle_assignment.vehicle_id IN($vehicle_id_str) AND vehicle_assignment.vehicle_id = schedule_assignment.vehicle_id AND ".
        "vehicle_assignment.status=1 AND schedule_assignment.status=1"; */
$query = "SELECT DISTINCT vehicle_assignment.device_imei_no,vehicle_assignment.vehicle_id FROM vehicle_assignment WHERE vehicle_assignment.vehicle_id IN($vehicle_id_str) AND vehicle_assignment.status=1";
echo "\n".$query;
//echo "\nDBCon=".$DbConnection;
$result = mysql_query($query,$DbConnection);

while($row = mysql_fetch_object($result))
{
  $vid_tmp = $row->vehicle_id;
  $imei[] = $row->device_imei_no;
  
  $query1 = "SELECT vehicle_name FROM vehicle WHERE vehicle_id='$vid_tmp' AND status=1";
  //echo "\n".$query1."\n";
  
  $result1 = mysql_query($query1,$DbConnection);
  if($row1 = mysql_fetch_object($result1))
  {
    $vname_tmp = $row1->vehicle_name;
    //echo "\nvnamex=".$vname_tmp;   
    $vname[] = $vname_tmp;
  }         
}

$r++;     //LOOP THROUGH ROWS

$serial = 1;

//for($x=1;$x<2;$x++) 
echo "\nSize=".sizeof($imei);
for($x=0;$x<sizeof($imei);$x++)                            //IMEI LOOP
{            
  $tmpsno = $x+1;
  echo "\nSERIAL=".$tmpsno." ,LEN=".sizeof($imei)."\n";

  $no_movement_date_string ="";
  $no_device_reporting_string ="";    
  
  $c=0;
  $worksheet->write($r,$c, $serial, $border2);        //SERIAL
  $c++;
  $worksheet->write($r,$c, $vname[$x], $border2);    //VEHICLE NAME
  $c++;
  $worksheet->write($r,$c, "", $border2);            
  $c++;                     
          
  //for($i=15;$i<=15;$i++)                            //DATE LOOP
  for($i=1;$i<=$total_days;$i++)
  {
    echo "\n imei=".$imei[$x], ",DAY=".$i;
    if($i<=9)
      $date_tmp =  $previous_year."-".$previous_month."-0".$i;
    else
      $date_tmp =  $previous_year."-".$previous_month."-".$i;
        
    $date1 = $date_tmp." 00:00:00"; 
    $date2 = $date_tmp." 23:59:59";        
             
    add_data_to_field($imei[$x], $vname[$x], $date1, $date2);
        
    /*
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
              add_data_to_field($imei[$x], $vname[$x], $date1, $date2, $op_date1, $op_date2, $border1, $border2, $worksheet);          
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
          add_data_to_field($imei[$x], $vname[$x], $date1, $date2, $op_date1, $op_date2, $border1, $border2, $worksheet);
        }
     // } //INNER IF CLOSED
    }  //OUTER IF CLOSED
    else
    {
      //echo "\nDAY -NOT SPECIFIED";             
      add_data_to_field($imei[$x], $vname[$x], $date1, $date2, $op_date1, $op_date2, $border1, $border2, $worksheet);   
    }*/   
  } // DAY LOOP CLOSED
  
  //ADD DATA      
  //echo "\nDate=".$date1." ,R1=".$r." ,C1=".$c;        
  
  //$no_movement_date_string = substr($no_movement_date_string, 0, -1);
  //$no_device_reporting_string = substr($no_device_reporting_string, 0, -1);
  
  $no_movement_date_string = rtrim($no_movement_date_string, ",");
  $no_device_reporting_string = rtrim($no_device_reporting_string, ",");
    
  $worksheet->write($r,$c, $no_movement_date_string, $border2);                    //NO MOVEMENT REPORT
  $c++;      
  $worksheet->write($r,$c, $no_device_reporting_string, $border2);        //NO DEVICE REPORTING
  $c++;
  
  //echo "\nNO DEVICE STRING1=".$no_device_reporting_string;
  
  $r++;
  $serial++;
  
  $no_movement_date_string ="";
  $no_device_reporting_string ="";
      
} //IMEI CLOSED
                  
$workbook->close(); //CLOSE WORKBOOK
echo "\nWORKBOOK CLOSED";


//########### SEND MAIL ##############//
$rno = "_".rand();
$to = 'jyoti.jaiswal@iembsys.com';
//$to = 'sanchan@wockhardtfoundation.org';
//$to = 'ashish@iembsys.co.in';   
$subject = $filename_title." (".$prev_month_str.")".$rno;
$message = $filename_title." (".$prev_month_str.")".$rno; 
$random_hash = md5(date('r', time()));  
$headers = "From: support@iembsys.co.in\r\n";
//$headers .= "Reply-To: taseen@iembsys.com\r\n"; 
$headers .= "Cc: rizwan@iembsys.com";
//$headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com";
//$headers .= "Cc: rizwan@iembsys.com";
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
$filename_title = $filename_title.".xls";
$file_path = $file_path.".xls";

echo "\nFILE PATH=".$file_path;

include_once("send_mail_api.php");
//####################################//

unlink($file_path);  


function add_data_to_field($imei, $vname, $date1, $date2)
{         
    global $no_movement_date_string;
    global $no_device_reporting_string;    
    //####### CALL COMBINED FUNCTION ########//
    $daily_data = get_daily_data($imei, $vname, $date1, $date2, $op_date1, $op_date2);
    //#######################################//    
    echo "\nDailyData=".$daily_data;
    $tmpdata = explode("#", $daily_data);
    
    if($tmpdata[0]!="-")
    {
      $day_tmp1 = explode('-',$tmpdata[0]);
      $no_movement_date_string = $no_movement_date_string.$day_tmp1[2].", ";
    }
    
    if($tmpdata[1]!="-")
    {    
      $day_tmp2 = explode('-',$tmpdata[1]);
      $no_device_reporting_string = $no_device_reporting_string.$day_tmp2[2].", ";
    }         
}    
?>
