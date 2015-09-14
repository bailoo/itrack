<?php  
//echo "\nTEST"; 
set_time_limit(25000);

/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

//$file = "../../../setup/mysql.php"; if(file_exists($file)) { include_once($file); }
//$HOST = "111.118.181.156";
include_once("../database_ip.php");
$DBASE = "iespl_vts_beta";
$USER = "root";
//$PASSWD = 'mysql';
$PASSWD = 'neon04$VTS';
//echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

$isReport = true;
//echo "\nAfter Connection";
$abspath = "/var/www/html/vts/beta/src/php";
//$abspath = "C:\\xampp/htdocs/itrack/beta/src/php";
//include_once($abspath."/common_xml_element.php");
//include_once($abspath."/get_all_dates_between.php");
//include_once($abspath."/sort_xml.php");
include_once($abspath."/calculate_distance.php");
include_once($abspath."/report_title.php");
include_once($abspath."/read_filtered_xml.php");
include_once($abspath."/select_landmark_report.php");
include_once($abspath."/util.hr_min_sec.php");
include_once($abspath."/get_location_lp_track_report_2.php");

include_once($abspath . '/xmlParameters.php');
//echo "\nD2";
include_once($abspath . '/parameterizeData.php');
//echo "\nD3";
include_once($abspath . '/data.php');
//echo "\nD4";
include_once($abspath . '/sortXmlData.php');
//echo "\nD5:" . $abspath;
//$tmp = $abspath.'/getXmlData.php';
//if(file_exists($tmp)){echo "File Exists2";} else {"Does not exist";}
include_once($abspath . '/getXmlData.php');

if ("Exists=" . file_exists($abspath . "/mail_api/mailgun-php/attachment_mailgun.php"));
include_once($abspath . "/mail_api/mailgun-php/attachment_mailgun.php");

//include("get_location.php");

//include_once($abspath."/area_violation/check_with_range.php");
//include_once($abspath."/area_violation/pointLocation.php");
include($abspath."/user_type_setting.php");
/*
require_once $abspath."/excel_lib/class.writeexcel_workbook.inc.php";
require_once $abspath."/excel_lib/class.writeexcel_worksheet.inc.php";
*/
require_once $abspath.'/PHPExcel/IOFactory.php';
//echo "\nAfter include";
//get_report_location("26.34534","80.34234",&$placename);
//echo "<br>place=".$placename;
echo "\nTEST";
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

$date = date('Y-m-d');
$previous_date = date('Y-m-d', strtotime($date .' -1 day'));
//$previous_date = "2012-07-23";
//echo "\nPREV_DATE=".$previous_date;
$objPHPExcel = null;
//$objPHPExcel2 = null;
$objWriter = null;
//$objWriter2 = null;
//####################### CODE FOR UPDATING SECOND FILE -REPORT2 #############################
$monthtmp2 = explode('-',$previous_date);
$month_report2 = $monthtmp2[0]."-".$monthtmp2[1];
$day_report2 = $monthtmp2[2];
//$filename_title2 = 'RSPL_VEHICLE_STATUS_REPORT_'.$month_report2.".xlsx";

//$objPHPExcel2 = new PHPExcel();  //write new file
//$destpath2 = "/daily_report_ln/rspl/excel_reports/".$filename_title2;
//$destpath2 = "/daily_report_ln/rspl/excel_reports_test/".$filename_title2;
//echo "\nDestpath2=".$destpath2;

/*if (file_exists($destpath2)) 				//LOAD IF EXISTS
{
	$objPHPExcel2 = PHPExcel_IOFactory::createReader('Excel2007');
	$objPHPExcel2 = $objPHPExcel2->load($destpath2); // Empty Sheet
}
$objPHPExcel2->getActiveSheet()->setTitle('DAILY -RSPL VEHICLE STATUS');
*/

//include_once("report2_header.php");

//############################################################################################

//echo "\nTest2";
$total_days=date('t',mktime(0,0,0,$previous_date));		// TOTAL DAYS IN PREVIOUS MONTH
////////////////  

$vserial_global = array();
$vname_global = array();
$vid_global = array();
$date_global = array();
$daily_dist_global = array();
$daily_halt_global = array();

$daily_dist_tmp =0;
$daily_halt_tmp =0;

$nodata_flag = 1;
$nogps_flag = 1;
$data_flag = 0;

$user_interval = "5";                                

//echo "\nTest3";
/////********* CREATE EXCEL(XLSX FILE) FILE *******************///////
$rno = rand();
$filename_title = 'DAILY_RSPL_VTS_REPORT_'.$previous_date.'_'.$rno;
//echo "\nfilename1=".$filename_title."\n";
$file_path = $abspath."/download/".$filename_title;
$fname = tempnam_sfx($file_path, ".xlsx");

echo "\nFname=".$fname;
$sheet_title = "DAILY VTS REPORT-RSPL_".$previous_date;
$objPHPExcel = new PHPExcel();  //write new file
$objPHPExcel->getActiveSheet()->setTitle();

//$report_title = "VTS TRACKING REPORT :RSPL -Date:".$previous_date;
/*
//## SET STYLE ARRAYS - BG-YELLOW
$styleBgYellow = array(			
	'fill' 	=> array(
		'type'		=> PHPExcel_Style_Fill::FILL_SOLID,								
		'color'		=> array('argb' => 'FFFF00')		//YELLOW
		//'text' => array('argb' => 'FFFC64')
	),
	'borders' => array(
		'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
		'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
	)
);

//## SET STYLE ARRAYS - BG-GREEN
$styleBgGreen = array(			
	'fill' 	=> array(
		'type'		=> PHPExcel_Style_Fill::FILL_SOLID,	
		'color'		=> array('argb' => '008000')		//YELLOW		
	),
	'borders' => array(
		'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
		'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
));

//########## SET FONT STYLE -WHITE
$styleFontWhite = array(
'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => 'FFFFFF'), //WHITE
	'size'  => 10
	//'name'  => 'Verdana'
));
*/
//########## SET FONT STYLE -BLACK
$styleFontBlack = array(
'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => '000000'), //BLACK
	'size'  => 10
	//'name'  => 'Verdana'
));
/*
$styleFontEditedPink = array(			
	'fill' 	=> array(
		'type'		=> PHPExcel_Style_Fill::FILL_SOLID,	
		'color'		=> array('argb' => 'FFD2E8')		//PINK		
	)
);

//########## SET FONT STYLE -RED
$styleFontRed = array(
'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => 'FF0000'), //RED
	'size'  => 10
	//'name'  => 'Verdana'
));
*/
//echo "\nAfter style";
$r =1;
//### SET FIRST ROW HEIGHT
/*$cell_range = 'A'.$r.':F'.$r;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
//$objPHPExcel->getActiveSheet()->getStyle($cell_range)->applyFromArray($styleBgYellow);
$objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(20);
//$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($r)->setWidth(60);

$cell = 'A'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($report_title);
//$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);	//SET TITLE

//####### SET CELL WIDTHS
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(6)->setWidth(80);

$r++;*/

//GET XML_DATA
function get_daily_rspl_report_xml($vehicle_serial, $vname, $startdate)
{
    //global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
    //global $old_xml_date;

    //echo "<br>vs=".$vehicle_serial." ,vname=".$vname." ,startdate=".$startdate." ,enddate=".$enddate." ,xmltowrite=".$xmltowrite;
    //echo "\nIn function -wockhardt report xml main";
    $fix_tmp = 1;
    $xml_date_latest="1900-00-00 00:00:00";
    $CurrentLat = 0.0;
    $CurrentLong = 0.0;
    $LastLat = 0.0;
    $LastLong = 0.0;
    $firstData = 0;
    $distance =0.0;
    $linetowrite="";
    $firstdata_flag =0;     //INITIALISE FIRST FLAG
    $breakflag = 0;
    $date_1 = explode(" ",$startdate);
    $date_2 = explode(" ",$enddate);

    $datefrom = $date_1[0];
    $dateto = $date_2[0];
    $timefrom = $date_1[1];
    $timeto = $date_2[1];

    global $daily_dist_tmp;

    global $last_lat;
    global $last_lng;

    global $nodata_flag;
    global $nogps_flag;
    global $data_flag;	

    //global $daily_halt_tmp;	
    //get_All_Dates($datefrom, $dateto, &$userdates);

    //date_default_timezone_set("Asia/Calcutta");
    $current_datetime = date("Y-m-d H:i:s");
    $current_date = date("Y-m-d");
    //print "<br>CurrentDate=".$current_date;
    //$date_size = sizeof($userdates);

    //$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append

    //$j = 0;
    $total_dist = 0; 									  
    global $timetmp1;
    global $breakflag; 
    global $user_interval;   
    //echo "\nstartdate=".$startdate." ,enddate=".$enddate." ,Date size=".$date_size;	

    //###### CASSANDRA BLOCK1 ###########
     global $o_cassandra;
     global $sts_date_sel;
     global $xml_date_sel;
     global $lat_sel;
     global $lng_sel;
     global $speed_sel;

     $date1 = $startdate;
     $date2 = $enddate;
     $datefrom = $report_date1;
     $dateto = $report_date2;

     $userInterval = 0;

     $sortBy = 'g';
     $firstDataFlag = 0;
     $endDateTS = strtotime($date2);
     $dataCnt = 0;
     //$userInterval = "0";
     $requiredData = "All";

     $parameterizeData = new parameterizeData();
     $ioFoundFlag = 0;

     $parameterizeData->latitude = "d";
     $parameterizeData->longitude = "e";
     $parameterizeData->speed = "f";

     $finalVNameArr = array();
     //###### CASSANDRA BLOCK1 CLOSED   	

    $sts_date_sel = array();
    $xml_date_sel = array();
    $lat_sel = array();
    $lng_sel = array();
    $speed_sel = array();

    //echo "\nReadSno:" . $i . " ,imei2=" . $IMEI[$i] . " ,datefrom=" . $datefrom . " ,dateto=" . $dateto;
    $dataCnt = 0;
    //$vehicle_info=get_vehicle_info($root,$vserial[$i]);
    //$vehicle_detail_local=explode(",",$vehicle_info);
    //$finalVNameArr[$i]=$vehicle_detail_local[0];
    //echo "vehcileName=".$finalVNameArr[$i]." vSerial=".$vehicle_detail_local[0]."<br>";
    //echo "userdate=".$userdates[$di]."<br>";
    $SortedDataObject=new data();
    readFileXmlNew($vehicle_serial,$startdate,$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
    //var_dump($SortedDataObject);
    //echo "\nvehicle_serial=".$vehicle_serial." ,STARTDATE0=".$startdate;
    if(count($SortedDataObject->deviceDatetime)>0)
    {
        //echo "\nSTARTDATE1=".$startdate;
        $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
        for($obi=0;$obi<$prevSortedSize;$obi++)
        {	
            //echo "\nSTARTDATE2=".$startdate;
            /*$lat = $SortedDataObject->latitudeData[$obi];
            $lng = $SortedDataObject->longitudeData[$obi];
            if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
            {
                $DataValid = 1;
            }
            if($DataValid==1 && ($SortedDataObject->deviceDatetime[$obi]>$date1 && $SortedDataObject->deviceDatetime[$obi]<$date2))
            {*/
                $sts_date_sel[] = $SortedDataObject->serverDatetime[$obi];
                $xml_date_sel[] = $SortedDataObject->deviceDatetime[$obi];
                $lat_sel[] = $SortedDataObject->latitudeData[$obi];
                $lng_sel[] = $SortedDataObject->longitudeData[$obi];
                $speed_sel[] = $SortedDataObject->speedData[$obi];
            //} 
        }    
    }
    $SortedDataObject = null;
    $parameterizeData = null;    
              
    ######## CASSANDRA BLOCK2 CLOSED
    //echo "\nIn Date Loop";
    //echo "<br>time=".$timetmp1;
    $timetmp2 = date("Y-m-d H:i:s");	
    $timetmp2 = strtotime($timetmp2);    
    $difftmp = ($timetmp2 - $timetmp1);
    //echo "<br>diff=".$difftmp;
    $daily_dist = 0; 
    $daily_halt = 0;

    $nodata_flag = 0;             //SET NO DATA OFF  

    $logcnt=0;
    $DataComplete=false;

    $vehicleserial_tmp=null;
    $format =2;      

    $size = sizeof($xml_date_sel);
    $firstdata_flag = 0;
    
    for($i=0;$i<$size;$i++)
    {
        $DataValid = 0;

        $DataValid = 1;
        $nogps_flag = 0;     // SET NO GPS FLAG OFF

        $datetime = $xml_date_sel[$i];
        $xml_date = $datetime;		

        if($xml_date!=null)
        {
            $data_flag = 1;                      

            $lat = $lat_sel[$i];
            $lng = $lng_sel[$i];

            /*$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
            $vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);*/
            $vserial = $vehicle_serial;
            //echo "<br>first=".$firstdata_flag;                                        
            if($firstdata_flag==0)
            {
                //echo "<br>FirstData";
                $firstdata_flag = 1;
                //$halt_flag = 0;
                $lat1_dist = $lat;
                $lng1_dist = $lng;

                //$lat1_halt = $lat;
                //$lng1_halt = $lng;
                //$time1_halt = $datetime;

                $interval = $user_interval*60;		//30 mins interval
                $last_time1 = $datetime;                                                        													                 	
            }           	
            //echo "<br>k2=".$k2."<br>";              	
            else
            {                           
                //********* DISTANCE LOGIC BEGINS
                $time2 = $datetime;											
                $date_secs2 = strtotime($time2);	

                $lat2_dist = $lat;
                $lng2_dist = $lng;  

                calculate_distance($lat1_dist, $lat2_dist, $lng1_dist, $lng2_dist, $distance);
                //echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance;

                $tmp_time_diff1 = (strtotime($datetime) - strtotime($last_time1)) / 3600;
                if($tmp_time_diff1>0)
                {
                        $tmp_speed = $distance / $tmp_time_diff1;
                        $last_time1 = $datetime;
                }
                $tmp_time_diff = (strtotime($datetime) - strtotime($last_time)) / 3600;

                //if($tmp_speed <3000 && $distance>0.1)
                if($tmp_speed<500 && $distance>0.1 && $tmp_time_diff>0)
                {		              
                    //echo "\nIndistance";
                    $daily_dist= (float) ($daily_dist + $distance);	
                    $daily_dist = round($daily_dist,2);							                          

                    ///////////////////////////////////////////////////////////																							
                    $lat1_dist = $lat2_dist;
                    $lng1_dist = $lng2_dist;

                    $last_time = $datetime;			
                }	
                //**** DISTANCE LOGIC CLOSED						                               
            }
        }   // if xml_date!null closed
                            //$j++;
    }   // FOR closed  

    if($nodata_flag)
    {
            $daily_dist_tmp = -1;   //NO DATA      
    }
    else if($nogps_flag)
    {
            $daily_dist_tmp = -2;   //NO GPS
    }
    else if($data_flag)
    {
            $daily_dist_tmp = $daily_dist;
    }

    //echo "\nDailyDistance=".$daily_dist_tmp;
    $last_lat[] = $lat;
    $last_lng[] = $lng;  
    //echo "Test1";
    //fclose($fh);
}
/////////////////////////////////////////////
//OPEN	
/*$cell = 'A'.$r;																	//ACCOUNT DETAIL
$bg_cell = 'A'.$r.':C'.$r;
//$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgYellow);
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($user_name);				
//$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);
$r++;*/

//$bg_cell = 'A'.$r.':C'.$r;
//$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgGreen);
$cell = 'A'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('SNO');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);

$cell = 'B'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('VEHICLE');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);

$cell = 'C'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('GROUP / USER');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);

$cell = 'D'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('DISTANCE(KM) / STATUS');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);

$cell = 'E'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('Latitude');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);

$cell = 'F'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('Longitude');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);

$cell = 'G'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('Google Location');
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);
$r++;


//echo "\nfour";
//## CHECK EXCEPTINON ACCOUNTS
$query_exception = "SELECT account_id FROM exception_report WHERE exception_id =1 AND status=1";
$result_exception = mysql_query($query_exception, $DbConnection);

if($row_exception = mysql_fetch_object($result_exception))
{
	$exception_account = $row_exception->account_id;
}
//echo "\nEX=".$exception_account;
$query_account = "SELECT account_id,user_id FROM account WHERE group_id='0001' AND user_id NOT IN('rspl') AND account_id NOT IN($exception_account) AND status=1";
//echo "\nQ=".$query_account;
/*$query_account = "SELECT account_id,user_id FROM account WHERE group_id='0001' AND user_id NOT IN('rspl') AND account_id IN(13) AND account_id NOT IN($exception_account) AND status=1";*/

//$query_account = "SELECT account_id,user_id FROM account WHERE group_id='0001' AND user_id NOT IN('rspl') AND account_id NOT IN($exception_account) AND user_id='rspl_sagar' AND status=1";
//$query_account = "SELECT account_id,user_id FROM account WHERE group_id='0001' AND user_id NOT IN('rspl') AND account_id NOT IN($exception_account) AND status=1 limit 1";
//$query_account = "SELECT account_id,user_id FROM account WHERE group_id='0001' AND user_id NOT IN('rspl') AND user_id IN('ASM','accidental') AND account_id NOT IN($exception_account) AND status=1";

$result_account = mysql_query($query_account,$DbConnection);

//echo "\n".$query_account."\n";
$total_vehicles = 0;
$total_inactive_vehicles = 0;
$total_nogps_vehicles = 0;
$accidental_vehicles = 0;
$miscellaneous_vehicles = 0;
$discontinued_vehicles = 0;

while($row_account = mysql_fetch_object($result_account))
{	
    $account_id = $row_account->account_id;
    $user_id = $row_account->user_id;
    //echo "\nAccountID=".$account_id;

    $query_name = "SELECT name FROM account_detail WHERE account_id='$account_id'";
    $result_name = mysql_query($query_name,$DbConnection);

    $row_name = mysql_fetch_object($result_name);
    $user_name = $row_name->name;

    $query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name,vehicle_grouping.create_date,vehicle_grouping.edit_date FROM vehicle,vehicle_assignment,vehicle_grouping WHERE vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND vehicle_grouping.account_id='$account_id' AND vehicle_grouping.status=1 AND vehicle.status=1 AND vehicle_assignment.status=1"; 

    //echo "\nquery_assignment=".$query_assignment."\n";
    $result_assignment = mysql_query($query_assignment,$DbConnection);

    $v=0;
    $vehicle_id_tmp ="";

    $j=0;
    $vname = array();
    $vserial = array();
    $vid = array();	

    while($row_assignment = mysql_fetch_object($result_assignment))
    {
        $vehicle_id_a = $row_assignment->vehicle_id;
        $vname[$j] = $row_assignment->vehicle_name;
        $create_date = $row_assignment->create_date;
        $edit_date = $row_assignment->edit_date;

        $query_imei = "SELECT device_imei_no FROM vehicle_assignment WHERE vehicle_id ='$vehicle_id_a' AND status=1";
        //echo "\nquery_imei=".$query_imei;
        $result_imei = mysql_query($query_imei, $DbConnection);

        if($row_imei = mysql_fetch_object($result_imei))
        {
            $vserial[$j] = $row_imei->device_imei_no;
            if($account_id != "1727")
            {
                    $all_imei[] = $vserial[$j];		//ALL IMEIS
            }
            $vid[$j] = $vehicle_id_a;
            $create_date_tmp[$j] = $create_date;
            $edit_date_tmp[$j] = $edit_date;

            if($v==0) 
              $vehicle_id_tmp = $vehicle_id_tmp.$vehicle_id_a;
            else
              $vehicle_id_tmp = $vehicle_id_tmp.",".$vehicle_id_a;  

            //echo "\n".$vid[$j]." ,".$vname[$j]." ,".$vserial[$j];
            $j++;
            $v++;     
        }
    }

    $vsize = sizeof($vserial);
    if($account_id == "1646")	//ACCIDENTAL
    {
       $accidental_vehicles = $vsize;
    }	
    if($account_id == "1647")	//MISCELLANEOUS
    {
       $miscellaneous_vehicles = $vsize;
    }
    if($account_id == "1727")	//MISCELLANEOUS
    {
       $discontinued_vehicles = $vsize;
    }	

    $inactive_vehicle_counter = 0;
    $nogps_vehicle_counter = 0;

    $last_lat = array();
    $last_lng = array();

    $sno = 1;

//	$bg_cell = 'A'.$r.':F'.$r;
//	$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgGreen);
    //echo "\nSizeV=".$vsize;
    $date1 = $previous_date;  
    //$r++;
    $vstatus = "";
    $user_len[] = $user_name;

    echo "\nVsize=".$vsize;
    for($i=0;$i<$vsize;$i++)  
    {
        //echo "\nV1:i=".$i." ,vsize=".$vsize;
        $total_dist_tmp = 0;
        $total_halt_tmp = 0;

        $daily_dist_tmp =0;             //RESET VARIABLES FOR INDIVIDUAL DAY
        //$daily_halt_tmp =0;

        $nodata_flag = 1;
        $nogps_flag = 1;
        $data_flag = 0;
        //CALL FUNCTION
        get_daily_rspl_report_xml($vserial[$i], $vname[$i], $date1);
        //echo "\nV2";

        $cell = 'A'.$r;
        $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($sno);
        //$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);

        $cell = 'B'.$r;
        $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($vname[$i]);
        //$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);

        $cell = 'C'.$r;						//ACCOUNT DETAIL
        //$bg_cell = 'A'.$r.':C'.$r;
        //$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgYellow);		
        $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($user_name);		
        //$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);


        //echo "\nV3";
        //$daily_dist_tmp = -1;
        //echo "\nDailyDisttmp=".$daily_dist_tmp;

        if($nodata_flag)
        {
           //echo "\nV4";
           if($account_id == "1646")		//ACCIDENTAL
           {
               $daily_dist_tmp = "ACCIDENTAL";
           }
           else
           {
                $daily_dist_tmp = "INACTIVE";
                $inactive_vehicle_counter++;
                if($account_id != "1727")
                {
                        $all_inactive[] = $vserial[$i];	//ALL INACTIVES
                }
                $vstatus = "NA";
                $sms_string_inactive[$user_name][] = $vname[$i];
           }

            $cell = 'D'.$r;
            if($account_id==1646 || $account_id==1647 || $account_id==1727) {
                    $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($user_name);
            } else {			
                    $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($daily_dist_tmp);
            }
                //$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontRed);	
                //echo "\nV5";			
        }    
        else if($nogps_flag)
        {
            //echo "\nV6";
            $daily_dist_tmp = "No GPS";
            $nogps_vehicle_counter++;
            if($account_id != "1727")
            {
                    $all_nogps[] = $vserial[$i];	//ALL NO GPS
            }
            $vstatus = "NG";	
            $sms_string_nogps[$user_name][] = $vname[$i];

            $cell = 'D'.$r;
            if($account_id==1646 || $account_id==1647 || $account_id==1727) {
                    $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($user_name);
            }
            else {			
                    $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($daily_dist_tmp);
            }
            //$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontRed);
            //echo "\nV6";			
        }
        else if($data_flag)
        {
                //echo "\nV7";
                $vstatus = "AC";
                $cell = 'D'.$r;
                if($account_id==1646 || $account_id==1647 || $account_id==1727) {
                        $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($user_name);
                }
                else {
                        $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($daily_dist_tmp);
                }
                //$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);
                //echo "\nV8";
        }
        else
        {
                $cell = 'D'.$r;
                if($account_id==1646 || $account_id==1647 || $account_id==1727) {
                        $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($user_name);
                }
                else {			
                        $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($daily_dist_tmp);
                }                    
        }

        //echo "Lat=".$last_lat[$i]." ,lng=".$last_lng[$i];
        if($last_lat[$i]!="" && $last_lng[$i]!="")
        {
          $last_lat[$i] = substr_replace($last_lat[$i] ,"",-1);
          $last_lng[$i] = substr_replace($last_lng[$i] ,"",-1);
        }			

        $cell = 'E'.$r;
        $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($last_lat[$i]);
        //$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);

        $cell = 'F'.$r;
        $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($last_lng[$i]);
        //$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);		

        //echo "\nV9";
        //####### GET GOOGLE LOCATION
        $lttmp = $last_lat[$i];
        $lngtmp = $last_lng[$i];

//		echo "\nLat=".$lttmp." ,Lng=".$lngtmp;
        $placename1 = "";

        if($lttmp!="" && $lngtmp!="")
        {
            $lttmp = substr($lttmp, 0, -1);
            $lngtmp = substr($lngtmp, 0, -1);
            $landmark = "";
            get_landmark($lttmp,$lngtmp,&$landmark);
            if($landmark!="")
            {
                //echo "\nLNMRK1=".$landmark;
                $placename1 = $landmark;
            }
            else
            {
                if($lttmp!="" && $lngtmp!="")
                {
                  get_report_location($lttmp,$lngtmp,&$placename1);
//		  echo "<br>PL1=".$placename1;
                  $placename1 = preg_replace('/भारत गणराज्य/', '' , $placename1);
                  $placename1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '' , $placename1);
                  //echo "<br>PL3=".$placename1;
                }
            }
        }	

        $cell = 'G'.$r;
        $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($placename1);
        //$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);				
        //echo "\nV10";

        //$worksheet->write($r, 5, "Halt(H:m:s)", $text_format);  
        //######## FILL BG COLORS IF CREATE OR EDIT DATE <= 24 HRS
/*		$flag_update = false;
        $current_date = date('Y-m-d H:i:s');
        $diff_create_date = strtotime($current_date) - strtotime($create_date_tmp[$i]);

        //echo "\nVehicleID:".$vid[$i]." ,DiffCreateDate=".$diff_create_date." ,CurrentDate=".$current_date." ,CreateDate=".$create_date_tmp[$i];
        if($diff_create_date <= 86400)
        {
                //echo "\nInCreateDate";
                $flag_update = true;
        }
        if($edit_date_tmp[$i]!="")
        {
                $diff_edit_date = strtotime($current_date) - strtotime($edit_date_tmp[$i]);				
                //echo "\nInEditDate, Diff=".$diff_edit_date;

                if($diff_edit_date <= 86400)
                {
                        $flag_update = true;
                }
        }										
        if($flag_update)
        {
                //echo "\nFlagUpdateTrue";
                $bg_cell = 'A'.$r.':F'.$r;
                $objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleFontEditedPink);
        }
*/
        //######## FILL BG COLORS CLOSED

        $r++;  
        //$daily_halt_global[$i] = $daily_halt_tmp;
        //echo "\nIMEI=".$vserial[$i]." ,Date1=".$date1." ,Date2=".$date2." dist=".$daily_dist_tmp;  
        $sno++; 

        //######### CODE FOR REPORT2 FILE
        if($i == 0)
        {
                //echo "\nV11";
                //######## SET SUBGROUP IN COLUMN -B
                /*$subgroup_cell = 'B'.$r2;
                $objPHPExcel2->getActiveSheet()->getCell($subgroup_cell)->setValue($user_name);
                $objPHPExcel2->getActiveSheet()->getStyle($subgroup_cell)->applyFromArray($styleText);*/			 
                //echo "\nV12";
        }

        //######## SET VEHICLE IN COLUMN -C
        /*$vehicle_cell = 'C'.$r2;
        $objPHPExcel2->getActiveSheet()->getCell($vehicle_cell)->setValue($vname[$i]);
        $objPHPExcel2->getActiveSheet()->getStyle($vehicle_cell)->applyFromArray($styleText);*/

        //echo "\nV13";
        //######## SET STATUS :DECIDE COLUMN BY THE DAY
        /*
        switch($day_report2)
        {
          case "01" :
                  $vstatus_cell = $c1.$r2;
                  break;
          case "02" :
                  $vstatus_cell = $c2.$r2;
                  break;
          case "03" :
                  $vstatus_cell = $c3.$r2;
                  break;
          case "04" :
                  $vstatus_cell = $c4.$r2;
                  break;
          case "05" :
                  $vstatus_cell = $c5.$r2;
                  break;
          case "06" :
                  $vstatus_cell = $c6.$r2;
                  break;
          case "07" :
                  $vstatus_cell = $c7.$r2;
                  break;
          case "08" :
                  $vstatus_cell = $c8.$r2;
                  break;
          case "09" :
                  $vstatus_cell = $c9.$r2;
                  break;
          case "10" :
                  $vstatus_cell = $c10.$r2;
                  break;
          case "11" :
                  $vstatus_cell = $c11.$r2;
                  break;
          case "12" :
                  $vstatus_cell = $c12.$r2;
                  break;
          case "13" :
                  $vstatus_cell = $c13.$r2;
                  break;
          case "14" :
                  $vstatus_cell = $c14.$r2;
                  break;
          case "15" :
                  $vstatus_cell = $c15.$r2;
                  break;
          case "16" :
                  $vstatus_cell = $c16.$r2;
                  break;
          case "17" :
                  $vstatus_cell = $c17.$r2;
                  break;
          case "18" :
                  $vstatus_cell = $c18.$r2;
                  break;
          case "19" :
                  $vstatus_cell = $c19.$r2;
                  break;
          case "20" :
                  $vstatus_cell = $c20.$r2;
                  break;		
          case "21" :
                  $vstatus_cell = $c21.$r2;
                  break;
          case "22" :
                  $vstatus_cell = $c22.$r2;
                  break;
          case "23" :
                  $vstatus_cell = $c23.$r2;
                  break;
          case "24" :
                  $vstatus_cell = $c24.$r2;
                  break;
          case "25" :
                  $vstatus_cell = $c25.$r2;
                  break;
          case "26" :
                  $vstatus_cell = $c26.$r2;
                  break;
          case "27" :
                  $vstatus_cell = $c27.$r2;
                  break;
          case "28" :
                  $vstatus_cell = $c28.$r2;
                  break;
          case "29" :
                  $vstatus_cell = $c29.$r2;
                  break;
          case "30" :
                  $vstatus_cell = $c30.$r2;
                  break;
          case "31" :
                  $vstatus_cell = $c31.$r2;
                  break;				  
        }	*/					
        //$objPHPExcel2->getActiveSheet()->getCell($vstatus_cell)->setValue($vstatus);
        //$objPHPExcel2->getActiveSheet()->getStyle($vstatus_cell)->applyFromArray($styleText);			 

        //echo "\nV14";
        //###### CALCULATE TOTAL AC,NA,NG IN THIS ROW -READ ALL VALUES
        $count_ac = 0;
        $count_na = 0;
        $count_ng = 0;

        if (file_exists($destpath2)) 				//LOAD IF EXISTS
        {		
            //echo "\nV15";			
            /*foreach ($objPHPExcel2->setActiveSheetIndex(0)->getRowIterator() as $row2) 
            {
                    $cellIterator2 = $row2->getCellIterator();
                    $cellIterator2->setIterateOnlyExistingCells(false);
                    //echo "\nRow=".$r2;
                    foreach ($cellIterator2 as $cell2) 
                    {
                            if (!is_null($cell2)) 
                            {
                                    $column2 = $cell2->getColumn();
                                    $row2 = $cell2->getRow();
                                    //if($row>1 && $row<=50)
                                    //if($row > $sheet2_row_count)
                                    if($row2==$r2)
                                    {							
                                            //echo "\nCol2=".$r2;
                                            $status ="";

                                            if($column2=="D" || $column2=="E" || $column2=="F" || $column2=="G" || $column2=="H"
                                            || $column2=="I" || $column2=="J" || $column2=="K" || $column2=="L" || $column2=="M" || $column2=="N" || $column2=="O" || $column2=="P"
                                            || $column2=="Q" || $column2=="R" || $column2=="S" || $column2=="T" || $column2=="U" || $column2=="V" || $column2=="W" || $column2=="X"
                                            || $column2=="Y" || $column2=="Z" || $column2=="AA" || $column2=="AB" || $column2=="AC" || $column2=="AD" || $column2=="AE"
                                            || $column2=="AF" || $column2=="AG" || $column2=="AH")
                                            {
                                                    $col2 =$column2.$row2;

                                                    $status = $objPHPExcel2->getActiveSheet()->getCell($col2)->getValue();								
                                                    if($status == "AC") {$count_ac++;}
                                                    else if($status == "NA") {$count_na++;}
                                                    else if($status == "NG") {$count_ng++;}																							
                                                    //echo "\nV16-BEFORE, Status=".$status." ,col2=".$col2." ,count_ac=".$count_ac." ,count_na=".$count_na." ,count_ng=".$count_ng;						
                                            }
                                    }																
                            }		
                    }
            }*/
            /*if($vstatus == "AC") {$count_ac++;}		//INCREMENT CURRENT STATUS ALSO
            else if($vstatus == "NA") {$count_na++;}
            else if($vstatus == "NG") {$count_ng++;}*/
            //echo "\nV16-AFTER, col2=".$col2." ,count_ac=".$count_ac." ,count_na=".$count_na." ,count_ng=".$count_ng;			
        } //IF FILE EXIST CLOSED
        else
        {
            //## FOR THE FIRST TIME
            if($vstatus == "AC") {$count_ac++;}		//INCREMENT CURRENT STATUS ALSO
            else if($vstatus == "NA") {$count_na++;}
            else if($vstatus == "NG") {$count_ng++;}			
        }

        $vstatus = "";			

        $ac_total = "AI".$r2;
        $na_total = "AJ".$r2;
        $ng_total = "AK".$r2;
        //$remark = "AL".$r2;
        /*$objPHPExcel2->getActiveSheet()->getCell($ac_total)->setValue($count_ac);
        $objPHPExcel2->getActiveSheet()->getStyle($ac_total)->applyFromArray($styleText);

        $objPHPExcel2->getActiveSheet()->getCell($na_total)->setValue($count_na);
        $objPHPExcel2->getActiveSheet()->getStyle($na_total)->applyFromArray($styleText);

        $objPHPExcel2->getActiveSheet()->getCell($ng_total)->setValue($count_ng);
        $objPHPExcel2->getActiveSheet()->getStyle($ng_total)->applyFromArray($styleText);*/
        //echo "\nV17";

        //###############################
        $r2++;
    }     

    if($vsize==0)
    {
            $r2++;
    }
    /*$bg_cell = 'A'.$r.':C'.$r;
    $objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgGreen);

    $cell = 'A'.$r;
    $objPHPExcel->getActiveSheet()->getCell($cell)->setValue("TOTAL INACTIVE");
    $objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);

    $cell = 'C'.$r;
    $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($inactive_vehicle_counter);
    $objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);*/						
    //CLOSE 

    $total_vehicles = $total_vehicles + $vsize;
    $total_inactive_vehicles = $total_inactive_vehicles + $inactive_vehicle_counter;
    $total_nogps_vehicles = $total_nogps_vehicles + $nogps_vehicle_counter;
    //echo "\nLAST";
    //$r++;
    $parameterizeData=null;
    $o_cassandra->close();
    echo "\nUserId :".$user_name." completed -(Vehicles:".$vsize.")\n";
    //######## UPDATE ROW POINTER OF SECOND FILE
    //$r2++;	
    //##########################################
}  //ACCOUNT CLOSED


//####### UNREGISTERD VEHICLES
$query_ur="SELECT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle INNER JOIN vehicle_grouping ON vehicle_grouping.vehicle_id=vehicle.vehicle_id AND ".
"vehicle_grouping.account_id=4 AND vehicle_grouping.status=1 LEFT JOIN  vehicle_assignment ON ".
"vehicle_grouping.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.status=1 WHERE vehicle_assignment.vehicle_id IS NULL;";
//echo "query=".$query;
$result_ur = mysql_query($query_ur,$DbConnection);
$flag=0;
$vehicle_cnt=0;
//$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);				
$r++;
while($row_ur=mysql_fetch_object($result_ur))
{
    //$vehicle_id=$row->vehicle_id;
    $vehicle_name_2=$row_ur->vehicle_name;
    $cell = 'B'.$r;
    $objPHPExcel->getActiveSheet()->getCell($cell)->setValue($vehicle_name_2);
    //$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);

    $cell = 'C'.$r;
    $objPHPExcel->getActiveSheet()->getCell($cell)->setValue("Unregistered Vehicle");

    $cell = 'D'.$r;
    $objPHPExcel->getActiveSheet()->getCell($cell)->setValue("Unregistered Vehicle");

    $r++;
}

//echo "\nAccount After-1";

/*
//######### WRITE SMS CODE #################
$sms_date = date('Y-m-d');
$sms_path = "/home/VTS/iespl_sms_sender/temp_files/send_messages/".$sms_date;
mkdir ($sms_path, false);
@chmod($sms_path, 0777);


//####### WRITE INACTIVE VEHICLES
$head1 = "NA:";
$inactive_sms_path = $sms_path."/inactive_rspl.xml";
$sms_string1 = "";
$pt1=1;	

$sts = date('Y-m-d H:i:s');
$phone1 ="9794836044";	//Kulvinder
$phone2 ="7897997345";  //Vaibhav
$phone3 ="8953991300";  //Agrij

$person1 ="Kulvinder";
$person2 ="Vaibhav";
$person3 ="Agrij";

$vid = "-";
$alert_id ="-";
$eid = "-";
$date ="-";
$person_name ="-";
echo "\nsizeof(sms_string_inactive)=".sizeof($sms_string_inactive);
$sms_string1 =$head1.$sms_string1;

$file1 = fopen($inactive_sms_path,"a");
fwrite($file1, "<t1>\n");
$count = 0;

for($i=0;$i<sizeof($user_len);$i++)
{
    $tmp = $user_len[$i];
    $sms_string1 = "";
    $sms_string1 = "NA-SG:".$tmp."=>";
    $count = 0;
    for($j=0;$j<sizeof($sms_string_inactive[$tmp]);$j++)
    {		
            $sms_string1 = $sms_string1.$sms_string_inactive[$tmp][$j].",";
            $count++;
            if($sms_string_inactive[$tmp][$j]!="Accidental")
            {
            if($count == 5)
            {
                    $sms_string1 = substr($sms_string1, 0, -1);
                    $tmp_data = "<marker phone=\"".$phone1."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string1."\" person=\"".$person1."\"/>\n";
                    $tmp_data .= "<marker phone=\"".$phone2."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string1."\" person=\"".$person2."\"/>\n";
                    $tmp_data .= "<marker phone=\"".$phone3."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string1."\" person=\"".$person3."\"/>\n";
                    fwrite($file1, $tmp_data);
                    $sms_string1 = "NA-SG:".$tmp."=>";
                    $count = 0;
            }
            else if($j == sizeof($sms_string_inactive[$tmp])-1)
            {
                    $sms_string1 = substr($sms_string1, 0, -1);
                    $tmp_data = "<marker phone=\"".$phone1."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string1."\" person=\"".$person1."\"/>\n";
                    $tmp_data .= "<marker phone=\"".$phone2."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string1."\" person=\"".$person2."\"/>\n";
                    $tmp_data .= "<marker phone=\"".$phone3."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string1."\" person=\"".$person3."\"/>\n";						
                    fwrite($file1, $tmp_data);
            }		
            }
            }
}
fwrite($file1, "</t1>");	
fclose($file1);


//####### WRITE NO-GPS VEHICLES
$head2 = "NG:";
$nogps_sms_path = $sms_path."/nogps_rspl.xml";
$sms_string2 = "";
$pt2=1;
$file2 = fopen($nogps_sms_path,"a");
fwrite($file2, "<t1>\n");

echo "\nsizeof(sms_string_nogps)=".sizeof($sms_string_nogps);
$sms_string2 =$head2.$sms_string2;

for($i=0;$i<sizeof($user_len);$i++)
{
    $tmp = $user_len[$i];
    $sms_string2 = "";
    $sms_string2 = "NG-SG:".$tmp."=>";
    $count = 0;
    for($j=0;$j<sizeof($sms_string_nogps[$tmp]);$j++)
    {		
        $sms_string2 = $sms_string2.$sms_string_nogps[$tmp][$j].",";
        $count++;
        if($sms_string_nogps[$tmp][$j]!="Accidental")
        {
        if($count == 5)
        {
                $sms_string2 = substr($sms_string2, 0, -1);
                $tmp_data = "<marker phone=\"".$phone1."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string2."\" person=\"".$person1."\"/>\n";
                $tmp_data .= "<marker phone=\"".$phone2."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string2."\" person=\"".$person2."\"/>\n";
                $tmp_data .= "<marker phone=\"".$phone3."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string2."\" person=\"".$person3."\"/>\n";
                fwrite($file2, $tmp_data);
                $sms_string2 = "NG-SG:".$tmp."=>";
                $count = 0;
        }
        else if($j == sizeof($sms_string_nogps[$tmp])-1)
        {
                $sms_string2 = substr($sms_string2, 0, -1);
                $tmp_data = "<marker phone=\"".$phone1."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string2."\" person=\"".$person1."\"/>\n";
                $tmp_data .= "<marker phone=\"".$phone2."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string2."\" person=\"".$person2."\"/>\n";
                $tmp_data .= "<marker phone=\"".$phone3."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string2."\" person=\"".$person3."\"/>\n";						
                fwrite($file2, $tmp_data);
        }		
        }
    }
}
fwrite($file2, "</t1>");
fclose($file2);	
//##########################################
*/
/*
$r++;

$unique_imei = array_unique($all_imei);
$unique_inactive = array_unique($all_inactive);
$unique_nogps = array_unique($all_nogps);

$count_imei = sizeof($unique_imei);
$count_inactive = sizeof($unique_inactive);
$count_nogps = sizeof($unique_nogps);

//echo "\nRow=".$r;
//###### TOTAL VEHICLES
$bg_cell = 'A'.$r.':B'.$r;
$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgGreen);
$cell = 'A'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue("TOTAL VEHICLES");
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontWhite);

$bg_cell = 'C'.$r.':C'.$r;
$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgYellow);
$cell = 'C'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($count_imei);
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);				

$r++;

//echo "\nAccount After-2";
//###### TOTAL INACTIVE VEHICLES
$bg_cell = 'A'.$r.':B'.$r;
//echo "\nBGcell=".$bg_cell;
$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgGreen);
$cell = 'A'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue("TOTAL *INACTIVE VEHICLES");
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontWhite);

$bg_cell = 'C'.$r.':C'.$r;
//echo "\nBGcell=".$bg_cell;
$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgYellow);
$cell = 'C'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($count_inactive);
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);				

$r++;

//###### TOTAL NO GPS VEHICLES
$bg_cell = 'A'.$r.':B'.$r;
//echo "\nBGcell=".$bg_cell;
$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgGreen);
$cell = 'A'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue("TOTAL *NO GPS VEHICLES");
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontWhite);

$bg_cell = 'C'.$r.':C'.$r;
//echo "\nBGcell=".$bg_cell;
$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgYellow);
$cell = 'C'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($count_nogps);
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);				

$r++;  

//echo "\nAccount After-3";
//##### TOTAL ACCIDENTAL VEHICLES
$bg_cell = 'A'.$r.':B'.$r;
$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgGreen);
$cell = 'A'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue("ACCIDENTAL");
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontWhite);

$bg_cell = 'C'.$r.':C'.$r;
$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgYellow);
$cell = 'C'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($accidental_vehicles);
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);
$r++;

//##### TOTAL MISCELLANEOUS VEHICLES
$bg_cell = 'A'.$r.':B'.$r;
$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgGreen);
$cell = 'A'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue("MISCELLANEOUS");
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontWhite);

$bg_cell = 'C'.$r.':C'.$r;
$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgYellow);
$cell = 'C'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($miscellaneous_vehicles);
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);
$r++;


//##### TOTAL DISCONTINUED VEHICLES
$bg_cell = 'A'.$r.':B'.$r;
$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgGreen);
$cell = 'A'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue("DISCONTINUED");
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontWhite);

$bg_cell = 'C'.$r.':C'.$r;
$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgYellow);
$cell = 'C'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($discontinued_vehicles);
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);
$r++;				
*/ 
//echo "\nFname=".$fname;
//######## SAVE XLSX FILE
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$abspath = "xlsx/abc.xlsx";
//echo "\nABS=".$abspath;
$objWriter->save($fname);
//$objWriter->save($file_path);
//echo "\nAccount After-4";

//########### SEND MAIL ##############//
//$to = 'rizwan@iembsys.com'; 
$to = 'agrijrspl@gmail.com,s.saurabh@rsplgroup.com';
$subject = 'VTS_REPORT_RSPL_'.$previous_date."/".$previous_year;
$message = 'VTS_REPORT_RSPL_'.$previous_date."/".$previous_year; 
$random_hash = md5(date('r', time()));  
$headers = "From: support@iembsys.co.in\r\n";
$headers .= "Cc: rizwan@iembsys.com,ashish@iembsys.co.in,jyoti.jaiswal@iembsys.com,support1@iembsys.com,support2@iembsys.com,support3@iembsys.com,support4@iembsys.com";
//$headers .= "Cc: rizwan@iembsys.com";
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
$filename_title = $filename_title.".xlsx";
$file_path = $file_path.".xlsx";
//echo "\nFILE PATH=".$file_path;

//### MAILGUN -Make the call to the client.
$result = $mgClient->sendMessage($domain, array(
    'from' => 'Itrack <support@iembsys.co.in>',
    'to' => $to,
    //'cc'      => 'rizwan@iembsys.com',
    'cc' => 'rizwan@iembsys.com,ashish@iembsys.co.in,jyoti.jaiswal@iembsys.com,support1@iembsys.com,support2@iembsys.com,support3@iembsys.com,support4@iembsys.com',
    //'cc'      => 'hourlyreport4@gmail.com',
    // 'bcc'     => 'astaseen83@gmail.com',
    'subject' => $subject,
    'text' => $message,
    'html' => '<html></html>'
        ), array(
    'attachment' => array($file_path)
));        
////include("send_mail_api.php");
//#####################################
//####################################//
unlink($file_path);

//echo "\nBefore mail";
//############# CREATE FOLDER AND BACKUP FILES ########
/*$dirPath = "excel_reports/".$previous_date;
//echo "\nDirPath=".$dirPath;
mkdir ($dirPath, false);
@chmod($dirPath, 0777);

//###### SOURCE PATH1 ################
$sourcepath = $file_path;
$destpath = $dirPath."/".$filename_title;

@chmod($destpath, 0777);
//echo "\nSourcePath=".$sourcepath." ,DestPath=".$destpath;
copy($sourcepath,$destpath);*/
//####################################
//echo "\nAfter mail1";

//###### WRITE SOURCE PATH2 ################
/*$objWriter2 = PHPExcel_IOFactory::createWriter($objPHPExcel2, 'Excel2007');
//echo "\nDestPath2=".$destpath2;
$objWriter2->save($destpath2);*/
//####################################

//echo "\nAfter mail2";
//########### SEND MAIL ##############//
//$to = 'rizwan@iembsys.com'; 
/*
$to = 'shweta.srivastava@rspl.net.in,vaibhavmpec_knp@rediffmail.com,agrijrspl@gmail.com,hitendra.kumar@rspl.net.in,s.saurabh@rsplgroup.com';
$subject = 'RSPL_VEHICLE_STATUS_REPORT_'.$previous_date."/".$previous_year;
$message = 'RSPL_VEHICLE_STATUS_REPORT_'.$previous_date."/".$previous_year; 
$random_hash = md5(date('r', time()));  
$headers = "From: support@iembsys.co.in\r\n";
$headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com,support1@iembsys.com,support2@iembsys.com,support3@iembsys.com,support4@iembsys.com";
//$headers .= "Cc: rizwan@iembsys.com";

$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
$filename_title = $filename_title2;
$file_path = $destpath2;

//echo "\nFILE PATH2=".$file_path;

include("send_mail_api2.php");
//####################################//

//########## BACKUP FILES CLOSED #######################
*/
?>
