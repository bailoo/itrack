<?php  
//echo "\nTEST"; 
set_time_limit(3600);
$DEBUG = 0;
//if($DEBUG) echo "1";

/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
//define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

//$file = "../../../setup/mysql.php"; if(file_exists($file)) { include_once($file); }
//$HOST = "111.118.181.156";
include_once("../database_ip.php");
$DBASE = "iespl_vts_beta";
$USER = "root";
$PASSWD = "mysql";
$account_id = "715";
//echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

//echo "\nAfter Connection";
$abspath = "/var/www/html/vts/beta/src/php";
include_once($abspath."/common_xml_element.php");
include_once($abspath."/get_all_dates_between.php");
include_once($abspath."/sort_xml.php");
include_once($abspath."/calculate_distance.php");
include_once($abspath."/report_title.php");
include_once($abspath."/read_filtered_xml.php");
include_once($abspath."/select_landmark_report.php");
include_once($abspath."/util.hr_min_sec.php");
if($DEBUG) echo "\n2";
include_once($abspath."/get_location_lp_track_report.php");
//include("get_location.php");
include($abspath."/user_type_setting.php");

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
//date_default_timezone_set("Asia/Kolkata");
//require_once 'util_session_variable.php';
//require_once $abspath.'/PHPExcel2/Classes/PHPExcel/IOFactory.php';
require_once $abspath.'/PHPExcel/IOFactory.php';

$vehicle_name = array();					
$icd_code = array();												
$icd_outdate = array();										
$icd_outtime = array();														
$factory_code = array();										
$factory_expected_arrival_date = array();
$factory_expected_arrival_time = array();													
$icd_indate = array();
$remark = array();					
										

$date = date('Y-m-d');
$current_time = date('Y-m-d H:i:s');

$icd_code_db = array();
$icd_coord_db = array();
$icd_name_db = array();

$factory_code_db = array();
$factory_coord_db = array();
$factory_name_db = array();
$customer_name_db = array();	//NOTE : CUSTOMER = FACTORY
$expected_arrival_db = array();

//##### GET ICD (PLANT) COORDINATES ###############
$query1 = "SELECT DISTINCT station_id,type,customer_no,station_name,station_coord,distance_variable FROM station WHERE ".
            "user_account_id='$account_id' AND type=1 AND status=1";
$result1 = mysql_query($query1,$DbConnection); 

while($row1 = mysql_fetch_object($result1))
{  
  $icd_code_db[] = $row1->customer_no;
  $icd_name_db[] = $row1->station_name;
  $icd_coord_db[] = $row1->station_coord;
  //$distance_variable[] = $row1->distance_variable;  
}  

if($DEBUG) echo "\n3";
//##### GET FACTORY (CUSTOMER) COORDINATES ###############
$query2 = "SELECT DISTINCT station_id,type,customer_no,station_name,station_coord,distance_variable FROM station WHERE ".
            "user_account_id='$account_id' AND type=0 AND status=1";
//echo $query2;
$result2 = mysql_query($query2,$DbConnection); 

while($row2 = mysql_fetch_object($result2))
{
  $factory_code_db[] = $row2->customer_no;
  $factory_name_db[] = $row2->station_name;
  $customer_name_db[] = $row2->station_name;
  $factory_coord_db[] = $row2->station_coord;
  //$distance_variable[] = $row2->distance_variable;
}    

include_once("read_klp_db_input.php");
include_once("action_klp_out.php");
//include_once("read_klp_db_input_test.php");
//include_once("action_klp_out_test.php");

//echo "\nSizeV=".sizeof($vehicle_name)." ,sizeICDCode=".sizeof($icd_code);
$rno = rand();
$cmonth = date('Y-m-d H:i');
$filename_title = '2HOURLY_KLP_VTS_REPORT_'.$cmonth;
//echo "\nfilename1=".$filename_title."\n";
$file_path = $abspath."/daily_report/klp_out/send_file/".$filename_title.".xlsx";

$objPHPExcel = null;
if(file_exists($file_path))
{
	$objPHPExcel = PHPExcel_IOFactory::load($file_path);
}
else
{
	$objPHPExcel = new PHPExcel();
}

//require_once $abspath.'/PHPExcel/IOFactory.php';

$timetmp1 = 0;
$breakflag = 0;

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

if($DEBUG) echo "\n4 :sizeoffactorycode=".sizeof($factory_code_db);
//## DEFINE HEADER	

//########## SET FONT STYLE -WHITE
$styleFontBlue = array(
'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => '0000FF'), //BLUE
	'size'  => 10
	//'name'  => 'Verdana'
));

$styleFontGreen = array(
'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => '008000'), //BLUE
	'size'  => 10
	//'name'  => 'Verdana'
));

$styleFontWhite = array(
'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => 'FFFFFF'), //WHITE
	'size'  => 10
	//'name'  => 'Verdana'
));

$styleFontBlack = array(
'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => '000000'), //BLACK
	'size'  => 10
	//'name'  => 'Verdana'
));

$styleFontRed = array(
'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => 'FF0000'), //RED
	'size'  => 10
	//'name'  => 'Verdana'
));
//## SET STYLE ARRAYS - BG-YELLOW
$styleBgYellow = array(			
	'fill' 	=> array(
		'type'		=> PHPExcel_Style_Fill::FILL_SOLID,								
		'color'		=> array('argb' => 'FFFF00'),		//YELLOW
		'text' => array('argb' => 'FFFC64')
	),
	'borders' => array(
		'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
		'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
	)
);

$styleBgHeader = array(			
	'fill' 	=> array(
		'type'		=> PHPExcel_Style_Fill::FILL_SOLID,								
		'color'		=> array('argb' => 'F28A8B'),		//YELLOW
		//'text' => array('argb' => '008000')
	),
	'borders' => array(
		'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
		'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
	),
	'font'  => array(
		'bold'  => true,
		//'color' => array('rgb' => 'DBFFA3'), //BLUE
		'size'  => 10)	
);

//## SET STYLE ARRAYS - BG-GREEN
/*$styleBgGreen = array(			
	'fill' 	=> array(
		'type'		=> PHPExcel_Style_Fill::FILL_SOLID,	
		'color'		=> array('argb' => '008000')		//YELLOW		
	),
	'borders' => array(
		'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
		'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
));*/

$styleBgOut = array(			
	'fill' 	=> array(
		'type'		=> PHPExcel_Style_Fill::FILL_SOLID,	
		'color'		=> array('argb' => 'E5F0FF')		//YELLOW		
	),
	'font'  => array(
		'bold'  => true,
		//'color' => array('rgb' => 'F30D0D'), //BLUE
		'size'  => 10)		
);

$styleBgOut_AtICD = array(			
	'fill' 	=> array(
		'type'		=> PHPExcel_Style_Fill::FILL_SOLID,								
		'color'		=> array('argb' => 'E5F0FF'),		//YELLOW
		//'text' => array('argb' => 'F30D0D')
	),
	'font'  => array(
		'bold'  => true,
		'color' => array('rgb' => 'F30D0D'), //BLUE
		'size'  => 10)		
);

$styleBgOut_InfoNA = array(	
	'font'  => array(
		'bold'  => true,
		'color' => array('rgb' => 'F30D0D'), //BLUE
		'size'  => 10)		
);

$styleBgOut_OnWay = array(			
	'fill' 	=> array(
		'type'		=> PHPExcel_Style_Fill::FILL_SOLID,								
		'color'		=> array('argb' => 'E5F0FF'),		//YELLOW
		//'text' => array('argb' => '015713')
	),
	'font'  => array(
		'bold'  => true,
		'color' => array('rgb' => '015713'), //BLUE
		'size'  => 10)	
);

//######## HEADER CLOSED #############

$r=1;
$cdate = date('Y-m-d H:i');
//$cell = 'A'.$r;
//$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($report_title);
//$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);	//SET TITLE

//####### SET CELL WIDTHS
/*$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth(80);
*/
//if($DEBUG) echo "\n5";
$cell = 'A'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('VEHICLE NAME');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth(16);
$cell = 'B'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('CONTAINER NO');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth(16);
$cell = 'C'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('FACTORY NAME');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth(16);
$cell = 'D'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('CUSTOMER NAME');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth(16);
//if($DEBUG) echo "\n5A";
/*$cell = 'E'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('ICD NAME');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontGreen);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth(16);*/
$cell = 'E'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('ICD OUTDATE');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth(12);
$cell = 'F'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('ICD OUTTIME');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setWidth(12);
$cell = 'G'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('FACTORY INTDATE');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setWidth(18);
$cell = 'H'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('FACTORY INTIME');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(8)->setWidth(18);
//if($DEBUG) echo "\n5E";
$cell = 'I'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('FACTORY OUTDATE');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(9)->setWidth(18);
$cell = 'J'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('FACTORY OUTTIME');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(10)->setWidth(18);
$cell = 'K'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('ICD INDATE');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(10)->setWidth(18);
$cell = 'L'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('ICD INTIME');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(10)->setWidth(18);
$cell = 'M'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('CURRENT LOCATION');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(11)->setWidth(18);
//if($DEBUG) echo "\n5B";
$cell = 'N'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('DISTANCE TRAVELLED (km)');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(12)->setWidth(18);
//if($DEBUG) echo "\n5C";
$cell = 'O'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('STATUS');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(13)->setWidth(18);
//if($DEBUG) echo "\n5D";
/*$cell = 'P'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('FACTORY CODE');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(14)->setWidth(18);
*/
$cell = 'P'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('EXPECTED ARRIVAL DATE');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(15)->setWidth(22);
$cell = 'Q'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('EXPECTED ARRIVAL TIME');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(16)->setWidth(22);
$cell = 'R'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('EXPECTED DEPARTURE DATE');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(17)->setWidth(22);
$cell = 'S'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('EXPECTED DEPARTURE TIME');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(18)->setWidth(22);
//if($DEBUG) echo "\n5G";
$cell = 'T'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('DELAY (hh:mm:ss)');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(19)->setWidth(16);
//if($DEBUG) echo "\n5H";
$cell = 'U'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('HALT (hh:mm:ss)');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgHeader);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(20)->setWidth(16);
$r++;

//if($DEBUG) echo "\n5B";
//############################################################################################
$user_interval = "5";                                

//echo "\nTest3";
/////********* CREATE EXCEL(XLSX FILE) FILE *******************///////
/*
$rno = rand();
$filename_title = '2HOURLY_KLP_VTS_REPORT_'.$cdate.'_'.$rno;
//echo "\nfilename1=".$filename_title."\n";
$file_path = "/var/www/html/vts/beta/src/php/download/".$filename_title.".xlsx";
//$fname = tempnam_sfx($file_path, ".xlsx");
*/
$objPHPExcel->getActiveSheet()->setTitle('DAILY 2HOURLY REPORT- KLP');

$report_title = "VTS TRACKING REPORT :KLP (2HOURLY) -Date:".$cdate;
	
//if($DEBUG) echo "6:sizeofvname=".sizeof($vehicle_name);	
//echo "\nSizeV=".sizeof($vehicle_name)." ,sizeICDCode=".sizeof($icd_code)." ,sizeFactoryCode=".sizeof($factory_code_db)." ,icd_coord_db=".sizeof($icd_coord_db)." ,factory_coord_db=".sizeof($factory_coord_db);

for($i=0;$i<sizeof($vehicle_name);$i++)
{	
	$status_res ="";
	$total_dist_res = 0.0;
	$time_delay_res = "";
	$factory_halt_time_res ="";
	$placename1 = "";
	
	//echo "\nICDIN=".$icd_indate[$i];
	if($icd_indate[$i]=="")
	{
		//echo "\nIN";
		$query = "SELECT device_imei_no FROM vehicle_assignment WHERE vehicle_id = (SELECT vehicle_id FROM vehicle WHERE vehicle_name='$vehicle_name[$i]' AND status=1) AND status=1";
		//echo "\n".$query;
		$result = mysql_query($query,$DbConnection);
		if($row = mysql_fetch_object($result))
		{
			$imei = $row->device_imei_no;
		}
		$startdate = str_replace("/","-",$icd_outdate[$i])." ".$icd_outtime[$i];
		$enddate = $current_time;
		
		//$icd_code_input = $icd_code[$i];
		$icd_code_input = "klpl_icd_panki";
		//$factory_code_input_tmp = $factory_code[$i];
		//$factory_code_input_tmp1 = explode(',',$factory_code_input_tmp);
		//$factory_code_input = $factory_code_input_tmp1[0];
		
		$expected_arrival_input = str_replace("/","-",$factory_expected_arrival_date[$i])." ".$factory_expected_arrival_time[$i];		
		$expected_departure_input = str_replace("/","-",$factory_expected_departure_date[$i])." ".$factory_expected_departure_time[$i];
		
		//echo "\nIMEI=".$imei." ,vehicle_name=".$vehicle_name[$i]." ,startdate=".$startdate." ,enddate=".$enddate." ,icd_code_input=".$icd_code_input." ,factory_code_input=".$factory_code_input." ,expected_arrival_input=".$expected_arrival_input."\n";
		$daily_data = get_daily_data($imei, $vehicle_name[$i], $startdate, $enddate, $icd_code_input, $customer_code[$i], $expected_arrival_input, $expected_departure_input);
		
		//echo "\n".$daily_data;
		$daily_data_tmp = explode("#",$daily_data);
		//$icd_out_time_res1 = explode(' ',$daily_data_tmp[0]);
		$current_coord_res = $daily_data_tmp[1];
		$total_dist_res = $daily_data_tmp[2];
		$status_res = $daily_data_tmp[3];
		$factory_in_time_res1 = explode(' ',$daily_data_tmp[4]);
		$factory_out_time_res1 = explode(' ',$daily_data_tmp[5]);
		$schedule_in_time_res = $daily_data_tmp[6];
		$time_delay_res = $daily_data_tmp[7];
		$factory_halt_time_res = $daily_data_tmp[8];
		
		//####### GET GOOGLE LOCATION
		//echo "\nCOORD=".$current_coord_res;
		$coord_tmp = explode(",",$current_coord_res);
		$lttmp = $coord_tmp[0];
		$lngtmp = $coord_tmp[1];

		$placename1 = "";
		if($lttmp!="" && $lngtmp!="")
		{
  		  get_landmark($lttmp,$lngtmp,&$landmark);
		  if($landmark!="")
		  {
			$placename1 = $landmark;
		  }
		  else
		  {
		  	get_report_location($lttmp,$lngtmp,&$placename1);
		  	//echo "<br>PL1=".$placename1;
		  	$placename1 = preg_replace('/भारत गणराज्य/', '' , $placename1);
		  	$placename1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '' , $placename1);
		  	//echo "<br>PL3=".$placename1;
		  }
		}
	}

	//### PRINT RECORDS
	//echo "\nC1";	
	$in_flag = true;
	
	if($status_res == "Information Not Available")
	{
		$styleBgData = $styleBgOut_InfoNA;
	}
	else if(($icd_indate[$i]=="") && ($status_res!="Information Not Available"))
	{		
		$styleBgData = $styleBgOut;
		$in_flag=false;
	}
	else
	{
		//$status_res = "At ICD";		
		//$status_res = "";
		//$total_dist_res	= "";
		$styleBgData = $styleFontBlack;
		$in_flag = true;
	}
	
	$cell = 'A'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($vehicle_name[$i]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);

	$cell = 'B'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($container_no[$i]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);		

	$cell = 'C'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($factory_name[$i]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);		

	$cell = 'D'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($customer_name[$i]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);	
	//echo "\nC2";
	/*$cell = 'E'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($icd_code_input);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);*/
	
	//echo "\nC3";
	$cell = 'E'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($icd_outdate[$i]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);
	
	$cell = 'F'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($icd_outtime[$i]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);	
	$cell = 'G'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($factory_in_time_res1[0]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);

	//echo "\nC7";
	$cell = 'H'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($factory_in_time_res1[1]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);

	//echo "\nC8";
	$cell = 'I'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($factory_out_time_res1[0]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);	
	$cell = 'J'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($factory_out_time_res1[1]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);
	
	$cell = 'K'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($icd_indate[$i]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);

	//echo "\nC8";
	$cell = 'L'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($icd_intime[$i]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);
	//echo "\nC4";
	$cell = 'M'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($placename1);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);
	
	//echo "\nC5";
	$cell = 'N'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($total_dist_res);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);

	//echo "\nC6";
	if(!$in_flag)
	{
		if($status_res=="At ICD")
		{
			$cell = 'O'.$r;	
			$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($status_res);
			$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgOut_AtICD);	
		}
		else if(($status_res=="On the way to Factory") || ($status_res=="On the way to ICD") || ($status_res=="At Factory"))
		{
			$cell = 'O'.$r;
			$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($status_res);
			$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgOut_OnWay);
		}
	}
	else
	{	
		$cell = 'O'.$r;	
		$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($status_res);
		$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);
	}
	//echo "\nC7";
	/*$cell = 'P'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($factory_code[$i]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);	
	*/
	//echo "\nC8";
	//echo "\nC9";
	$cell = 'P'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue(str_replace("/","-",$factory_expected_arrival_date[$i]));
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);
	
	//echo "\nC10";
	$cell = 'Q'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($factory_expected_arrival_time[$i]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);	

	$cell = 'R'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue(str_replace("/","-",$factory_expected_departure_date[$i]));
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);

	//echo "\nC10";
	$cell = 'S'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($factory_expected_departure_time[$i]);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);

	//echo "\nC11";
	$cell = 'T'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($time_delay_res);
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);

	//echo "\nCl2";
	$cell = 'U'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($factory_halt_time_res);
	//$objPHPExcel->getActiveSheet()->getCell($cell)->setValue("");
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleBgData);	
	$r++;	
	
	echo "\nSNO=".($i+1)." ,Vehicle :".$vehicle_name[$i]." completed\n";
}
	
//echo "\nBefore Write:filpath=".$file_path; 
//echo "\nFname=".$fname;
//######## SAVE XLSX FILE
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$abspath = "xlsx/abc.xlsx";
//echo "\nABS=".$abspath;
$objWriter->save($file_path);
echo "\nAFTER WRITE";


//########### SEND MAIL ##############//
//$to = 'rizwan@iembsys.com';
$to = "amit.kumar@elogisol.com,amit.singh@elogisol.com, alam@klpl.in,ramphool@klpl.in ,vinay.rai@elogisol.com,aviral@klpl.in,Saurajit@klpl.in";
$subject = '2HOURLY_VTS_REPORT_KLP_OUT_'.$cdate;
$message = '2HOURLY_VTS_REPORT_KLP_OUT_'.$cdate; 
$random_hash = md5(date('r', time()));  
$headers = "From: support@iembsys.co.in\r\n";
$headers .= "Cc: hourlyreport4@gmail.com,klp2hourly@gmail.com";
//$headers .= "Cc: rizwan@iembsys.com";
//pass:8090025844
//$headers .= "Cc: ashish@iembsys.co.in,jyoti.jaiswal@iembsys.com,support1@iembsys.com,rizwan@iembsys.com";
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
$filename_title = $filename_title.".xlsx";
//echo "\nFILE PATH=".$file_path;

include("send_mail_api.php");
//####################################//

unlink($file_path);

?>
