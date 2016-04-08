<?php
//$back_dir1 = "../../../";
//$back_dir1 = "../";
set_time_limit(3000);
//$DEBUG =1;
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

//echo "\n1";
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
	 //echo "\nFile=".$file;
     $fp = @fopen($file, 'x');
  }
  while(!$fp);

  fclose($fp);
  return $file;
}

//################ SET VARIABLE ##############/
$current_date = date("Y-m-d");
$start_time = date("Y-m-d")." 08:00:00";
$end_time = date("Y-m-d")." 16:00:00";  
//echo "\n2";
/////********* CREATE EXCEL FILE *******************///////

$filename_title = 'DAILY_WOCKHARDT_NO_DEVICE_REPORTING_'.$current_date."_".rand();  
//$file_path = "/var/www/html/vts/beta/src/php/download/".$filename_title;
//ONLINE
$file_path = "/var/www/html/vts/beta/src/php/daily_report/wockhardt/report3_8_4pm/excel_data/".$filename_title;

//OFFLINE
//$file_path = "D:\\SERVER_GO4HOSTING/ITRACKSOLUTION.CO.IN/MAIL_SERVICE/WOCKHARDT/REPORT_MONTHLY/REPORT3/excel_data/".$filename_title;
//echo "\nA";
$fname = tempnam_sfx($file_path, ".xls");    
//echo "\nB";
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

$border3 =& $workbook->addformat(array(color   => 'red',size    => 8));

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
//echo "\nC";
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
$worksheet->write($r,$c, "No Movement Date (8am to 4pm)", $border1);
$worksheet->set_column($c, $c, 35);
$c++;
$worksheet->write($r,$c, "No Device Reporting (8am to 4pm)", $border1);
$worksheet->set_column($c, $c, 35);
$c++;         

//echo "\n3";
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

$query = "SELECT DISTINCT vehicle_assignment.device_imei_no,vehicle_assignment.vehicle_id FROM vehicle_assignment WHERE vehicle_assignment.vehicle_id IN($vehicle_id_str) AND vehicle_assignment.status=1";
//echo "\n".$query;
//echo "\nDBCon=".$DbConnection;
$result = mysql_query($query,$DbConnection);

//echo "\n4";
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
//echo "\n5";
$r++;     //LOOP THROUGH ROWS

$serial = 1;

//for($x=0;$x<20;$x++) 
//echo "\nSizeIMEI=".sizeof($imei);
for($x=0;$x<sizeof($imei);$x++)                            //IMEI LOOP
{            
  $tmpsno = $x+1;
  //echo "\nSERIAL=".$tmpsno." ,LEN=".sizeof($imei)."\n";
  
  $no_movement_date_string ="";
  $no_device_reporting_string ="";    
  
  $c=0;
  $worksheet->write($r,$c, $serial, $border2);        //SERIAL
  $c++;
  $worksheet->write($r,$c, $vname[$x], $border2);    //VEHICLE NAME
  $c++;
  $worksheet->write($r,$c, "", $border2);            
  $c++;                     
                            
  add_data_to_field($imei[$x], $vname[$x], $start_time, $end_time);        
     
  //ADD DATA      
  //echo "\nDate=".$date1." ,R1=".$r." ,C1=".$c;          
  //$no_data_string = substr($no_data_string, 0, -1);
  //$no_device_reporting_string = substr($no_device_reporting_string, 0, -1);  
  //echo "\nNo MOVEMENT=".$no_movement_date_string." ,NO DEVICE REPORTING=".$no_device_reporting_string;
  if($no_movement_date_string!="")
  {
    $worksheet->write($r,$c, "No Movement", $border3);                    //NO MOVEMENT REPORT    
  }      
  $c++;
  if($no_device_reporting_string!="")
  {
    $worksheet->write($r,$c, "No Reporting", $border3);        //NO DEVICE REPORTING
  }
  $c++;
  $r++;
  $serial++;
  
  $no_movement_date_string ="";
  $no_device_reporting_string ="";
  echo "\nimei=".$imei[$x]." completed";
      
} //IMEI CLOSED
                  
$workbook->close(); //CLOSE WORKBOOK
//echo "\nWORKBOOK CLOSED";


function add_data_to_field($imei, $vname, $date1, $date2)
{         
    global $no_movement_date_string;
    global $no_device_reporting_string;    
    //####### CALL COMBINED FUNCTION ########//
    $daily_data = get_daily_data($imei, $vname, $date1, $date2);
    //#######################################//    
    //echo "\nDailyData=".$daily_data;
    $tmpdata = explode("#", $daily_data);
    
    if($tmpdata[0]!="-")
    {
      $no_movement_date_string = $no_movement_date_string.$tmpdata[0]."\n";
    }
    
    if($tmpdata[1]!="-")
    {    
      $no_device_reporting_string = $no_device_reporting_string.$tmpdata[1]."\n";
    }         
}   

  //########### SEND MAIL ##############//
  $to = 'rizwan@iembsys.com';
  //$to = 'sanchan@wockhardtfoundation.org';   
  $subject = $filename_title.$previous_date;
  $message = $filename_title.$previous_date; 
  $random_hash = md5(date('r', time()));  
  $headers = "From: support@iembsys.co.in\r\n";
  //$headers .= "Reply-To: taseen@iembsys.com\r\n"; 
  //$headers .= "Cc: rizwan@iembsys.com";
  $headers .= "Cc: jyoti.jaiswal@iembsys.com,support1@iembsys.com,support2@iembsys.com,support3@iembsys.com";
  //$headers .= "Cc: tanveerdad@gmail.com,rizwan@iembsys.com,jyoti.jaiswal@iembsys.com";
  $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
  $filename_title = $filename_title.".xls";  
  $file_path = $file_path.".xls";
  
  //echo "\nFILE PATH=".$file_path;
  
  include_once("send_mail_api.php");
  //####################################//
  
  unlink($file_path); 
 
?>
