<?php

echo "Evening file";
set_time_limit(18000);

include_once("../../database_ip.php");
$DBASE = "iespl_vts_beta";
$USER = "root";
$PASSWD = "mysql";
$account_id = "322";
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
include_once($abspath."/weekly_report/plant_distance/mumbai/get_master_detail.php");
include_once($abspath."/weekly_report/plant_distance/mumbai/mail_action_report_distance.php");


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

$station_id = array();
$type = array();
$plant_no = array();
$station_name = array();
$station_coord = array();
$distance_variable = array();
$google_location = array();
$csv_string_distance_arr = array();
$vserial = array();
$vname = array();

$csv_string_dist = "";                //INITIALISE  DISTANCE VARIABLES
$csv_string_dist_arr = array();
$sno_dist = 0;
$overall_dist = 0.0;

$csv_string_halt = "";                //INITIALISE  HALT VARIABLES
$csv_string_halt_arr = array();
$total_halt_dur = 0;
$sno_halt = 0;

$csv_string_travel = "";              //INITIALISE  TRAVEL VARIABLES
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
                    "vehicle_grouping.account_id='$account_id' AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1 and vehicle.vehicle_id IN(3599)";
*/
/*
$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".
                    "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
                    "vehicle_grouping.account_id='$account_id' AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1 limit 4";
*/				
//$result_assignment1 = null;
//echo "\n".$query_assignment."\n";
$result_assignment1 = mysql_query($query_assignment,$DbConnection);

while($row_assignment1 = mysql_fetch_object($result_assignment1))
{
   $vehicle_id_a = $row_assignment1->vehicle_id;
   $vname[] = $row_assignment1->vehicle_name;
   //echo "\nvid=".$vehicle_id_a." ,tmpv=".$tmpv;
   
   $query_imei = "SELECT device_imei_no FROM vehicle_assignment WHERE vehicle_id ='$vehicle_id_a' AND status=1";
   $result_imei = mysql_query($query_imei, $DbConnection);
   $row_imei = mysql_fetch_object($result_imei);
   $vserial[] = $row_imei->device_imei_no;
   $vid[] = $vehicle_id_a;  
}

/*$device_str = $_POST['vehicleserial'];
//echo "<br>devicestr=".$device_str;
$vserial = explode(':',$device_str);   */
$vsize=count($vserial);

echo "\nVsize=".$vsize;

$date = date('Y-m-d');
//$previous_date = date('Y-m-d', strtotime($date .' -7 day'));
//$current_date = $date;

include_once("../../date_fortnightly.php");
//$previous_date = "2013-07-16";
//$current_date = "2013-07-31";

//////////////////////////////////////////
$startdate = $previous_date." 00:00:00";               //TIME 1 Week 00 TO 12PM
$enddate = $current_date." 12:00:00"; 

//$startdate = "2013-08-23 00:00:00";               //TIME 1 Week 00 TO 12PM
//$enddate = "2013-08-24 20:00:00"; 
/////////////////////////////////////////
echo "\nStartDate=".$startdate." ,EndDate=".$enddate;

$date1 = $startdate;
$date2 = $enddate;

//######## GET MASTER DETAIL ################
$vehicle_t = array();
$transporter = array();
get_master_detail($account_id);
//######### MASTER DETAIL CLOSED ############


//##### GET STATION COORDINATES -PLANT ###############
$query2 = "SELECT DISTINCT station_id,type,customer_no,station_name,station_coord,distance_variable,google_location FROM station WHERE ".
            "user_account_id='$account_id' AND type=1 AND status=1";

$result2 = mysql_query($query2,$DbConnection); 

while($row2 = mysql_fetch_object($result2))
{
  $station_id[] = $row2->station_id;
  $type[] = $row2->type;
  $plant_no[] = $row2->customer_no;
  $station_name[] = $row2->station_name;
  //$station_coord_tmp =  $row2->station_coord;
  $station_coord[] = $row2->station_coord;
  $distance_variable[] = $row2->distance_variable;
}   

if($vsize>0)
{
  echo "\nStations Size Before ::".sizeof($station_id); 
  include_once("mail_action_report_halt2.php"); 
  //echo "\nAfter MAIL ACTION"; 
  write_report($plant_no, $station_coord, $distance_variable);
  //echo "\nStations Size After ::".sizeof($station_id); 
}

function write_report($plant_no, $station_coord, $distance_variable)
{
	echo "\nIn Report";
	global $DbConnection;
	global $startdate;
	global $enddate;
	global $sno;
	global $overall_dist;
	global $csv_string_dist;
	global $csv_string_distance_arr;
	global $vserial;
	global $vname;
	global $sno_dist;
	global $overall_dist;
	
	echo "\nTotal Vehicles=".sizeof($vserial);  
	for($i=0;$i<sizeof($vserial);$i++)
	{  	            		 
		echo "\nVname=".$vname[$i];
		//GET DISTANCE DATA
		$csv_string_dist = "";
		$sno_dist = 1;		  		  
	 
		$distance_data = "";
		$distance_data = get_distance_data($vserial[$i], $vname[$i], $startdate, $enddate, $plant_no, $station_coord, $distance_variable);					
		$csv_string_distance_arr[$i] = $distance_data;
		//echo "\nDISTANCE_DATA2=".$distance_data."\n";		
		$s = $i+1;
		echo "\nSerial = ".$s." :Vehicle:".$vname[$i]." ::Completed";
	}	
}
    
//echo "\nT1";
$inc_serial = rand();
$filename_title = 'FORTNIGHTLY_DISTANCE_REPORT_(MOTHER_MUMBAI)_'.$current_date."_".$inc_serial;
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
//echo "\nT2";	
$text_format =& $workbook->addformat(array(
	bold    => 1,
	//italic  => 1,
	//color   => 'blue',
	size    => 10,
	//font    => 'Comic Sans MS'
));

$text_green_format =& $workbook->addformat(array(
	bold    => 1,
	//italic  => 1,
	color   => 'green',
	size    => 10,
	//font    => 'Comic Sans MS'
));	

//echo "\nT3";	

$text_blue_format =& $workbook->addformat(array(
	bold    => 1,
	//italic  => 1,
	color   => 'blue',
	size    => 10,
	//font    => 'Comic Sans MS'
));		

$excel_date_format =& $workbook->addformat(array(num_format => 'dd/mm/yyyy','fg_color' => 0x09,'pattern' => 1,'border' => 1));
$excel_time_format =& $workbook->addformat(array(num_format => 'hh:mm:ss','fg_color' => 0x09,'pattern' => 1,'border' => 1));
$excel_normal_format =& $workbook->addformat(array('fg_color' => 0x09,'pattern' => 1,'border' => 1));

$worksheet1 =& $workbook->addworksheet('Distance Report');

//echo "\nT4";
	
$r = 0;   	//row 
$c = 0;		//column
		
//echo "\nTest4";		

$worksheet1->write($r, $c, "Vehicle", $text_format);
$c++;
$worksheet1->write($r, $c, "Transporter", $text_format);
$c++;	
$worksheet1->write($r, $c, "Out Plant", $text_green_format);
$c++;
$worksheet1->write($r, $c, "Out Date", $text_format);
$c++;
$worksheet1->write($r, $c, "Out Time", $text_format);
$c++;
$worksheet1->write($r, $c, "In Plant", $text_format);
$c++;
$worksheet1->write($r, $c, "In Date", $text_format);
$c++;
$worksheet1->write($r, $c, "In Time", $text_format);
$c++;
$worksheet1->write($r, $c, "Distance", $text_format);
$c++;

$r++;

//echo "\nSizeDistance=".sizeof($csv_string_distance_arr);

for($i=0;$i<sizeof($csv_string_distance_arr);$i++)
{
	if($csv_string_distance_arr[$i]!="")
	{
		//echo "\nAllRows=".$csv_string_distance_arr[$p];		
		$sheet_row_tmp = explode('#',$csv_string_distance_arr[$i]);	
		//echo "\nSizeSheetRowTmp=".sizeof($sheet_row_tmp);							
		//echo "\nSheetRowtmp=".sizeof($sheet_row_tmp);
		$vehicle_match = "";
		for($v=0;$v<sizeof($vehicle_t);$v++)
		{
			if(trim($vehicle_t[$v]) == trim($vname[$i]))
			{
				$vehicle_match = $transporter[$v];
				break;
			}
		}
		
		for($j=0;$j<sizeof($sheet_row_tmp);$j++)
		{
			$c = 0;			
			$worksheet1->write($r,$c, $vname[$i], $excel_normal_format);
			$c++;
						
			$worksheet1->write($r,$c, $vehicle_match, $excel_normal_format);
			$c++;			
			
			//echo "\nSheetDataTmp=".$sheet2_row_tmp[$i];				
			$sheet_data_tmp = explode(',',$sheet_row_tmp[$j]);
									
			$plant_out = $sheet_data_tmp[0];
			$out_date = explode(" ",$sheet_data_tmp[1]);
			$plant_in = $sheet_data_tmp[2];
			$in_date = explode(" ",$sheet_data_tmp[3]);
			$distance = $sheet_data_tmp[4];
			
			$date_obj1 = strtotime($out_date[0]);
			$date1 = intval( ($date_obj1+86400) / 86400 + 25569);
			
			$tmp_date = "1970-01-01";
			$tmp_date = $tmp_date." ".$out_date[1];
			$time_obj1 = strtotime($tmp_date);
			$time_obj1 = $time_obj1 + 19800;
			//$date_tmp = date('d/m/Y',$date_obj);
			$time1= $time_obj1 / 86400 + 25569;
			
			$date_obj2 = strtotime($in_date[0]);
			$date2 = intval( ($date_obj2+86400) / 86400 + 25569);
			
			$tmp_date = "1970-01-01";
			$tmp_date = $tmp_date." ".$in_date[1];
			$time_obj1 = strtotime($tmp_date);
			$time_obj1 = $time_obj1 + 19800;
			//$date_tmp = date('d/m/Y',$date_obj);
			$time2= $time_obj1 / 86400 + 25569;			
			
			$worksheet1->write($r,$c, $plant_out, $excel_normal_format);
			$c++;
			$worksheet1->write($r,$c, $date1, $excel_date_format);
			$c++;
			$worksheet1->write($r,$c, $time1, $excel_time_format);
			$c++;
			$worksheet1->write($r,$c, $plant_in, $excel_normal_format);
			$c++;			
			$worksheet1->write($r,$c, $date2, $excel_date_format);
			$c++;
			$worksheet1->write($r,$c, $time2, $excel_time_format);
			$c++;
			$worksheet1->write($r,$c, round($distance,2), $excel_normal_format);
			$c++;
			$r++;			
		}		
	}
}

$workbook->close(); //CLOSE WORKBOOK
//echo "\nWORKBOOK CLOSED"; 

########### SEND MAIL ##############//
//$to = 'rizwan@iembsys.com';
$to = 'Logistics.Vashi@motherdairy.com, Hemanshu.Mundley@motherdairy.com, Anand.Arondekar@motherdairy.com, Vijay.Singh@motherdairy.com, ashish@iembsys.co.in';
$subject = 'FORTNIGHTLY_DISTANCE_REPORT_(MOTHER_MUMBAI)_'.$current_date;
$message = 'FORTNIGHTLY_DISTANCE_REPORT_(MOTHER_MUMBAI)_'.$current_date."<br><br><font color=red size=1>*** This is an automatically generated email by the system on specified time, please do not reply ***</font>";; 
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
$dirPath = "excel_reports/".$current_date;
//echo "\nDirPath=".$dirPath;
mkdir ($dirPath, false);
@chmod($dirPath, 0777);
$destpath = $dirPath."/".$filename_title;

@chmod($destpath, 0777);
//echo "\nSourcePath=".$sourcepath." ,DestPath=".$destpath;
copy($sourcepath,$destpath);
//########## BACKUP FILES CLOSED #######################

unlink($file_path); 
 
?>
