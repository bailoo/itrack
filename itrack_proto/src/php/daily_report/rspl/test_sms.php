<?php  
//echo "\nTEST"; 
set_time_limit(1800);

/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

//$file = "../../../setup/mysql.php"; if(file_exists($file)) { include_once($file); }
//$HOST = "111.118.181.156";
include_once("../database_ip.php");
$DBASE = "iespl_vts_beta";
$USER = "root";
$PASSWD = "mysql";
//echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

//echo "\nAfter Connection";
$abspath = "/var/www/html/vts/beta/src/php";

include_once($abspath."/get_all_dates_between.php");
include_once($abspath."/sort_xml.php");
include_once($abspath."/calculate_distance.php");
include_once($abspath."/report_title.php");
include_once($abspath."/read_filtered_xml.php");
include_once($abspath."/select_landmark_report.php");
include_once($abspath."/util.hr_min_sec.php");
include_once($abspath."/get_location_lp_track_report.php");
//include("get_location.php");

include_once($abspath."/area_violation/check_with_range.php");
include_once($abspath."/area_violation/pointLocation.php");
include($abspath."/user_type_setting.php");
/*
require_once $abspath."/excel_lib/class.writeexcel_workbook.inc.php";
require_once $abspath."/excel_lib/class.writeexcel_worksheet.inc.php";
*/
require_once $abspath.'/PHPExcel/IOFactory.php';
//echo "\nAfter include";
//get_report_location("26.34534","80.34234",&$placename);
//echo "<br>place=".$placename;

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
$objPHPExcel2 = null;
$objWriter = null;
$objWriter2 = null;
//####################### CODE FOR UPDATING SECOND FILE -REPORT2 #############################
$monthtmp2 = explode('-',$previous_date);
$month_report2 = $monthtmp2[0]."-".$monthtmp2[1];
$day_report2 = $monthtmp2[2];
$filename_title2 = 'RSPL_VEHICLE_STATUS_REPORT_'.$month_report2.".xlsx";

$objPHPExcel2 = new PHPExcel();  //write new file
$destpath2 = "/daily_report_ln/rspl/excel_reports_test/".$filename_title2;
//echo "\nDestpath2=".$destpath2;

if (file_exists($destpath2)) 				//LOAD IF EXISTS
{
	$objPHPExcel2 = PHPExcel_IOFactory::createReader('Excel2007');
	$objPHPExcel2 = $objPHPExcel2->load($destpath2); // Empty Sheet
}
$objPHPExcel2->getActiveSheet()->setTitle('DAILY -RSPL VEHICLE STATUS');

include_once("report2_header.php");

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

$user_interval = "5";                                

//echo "\nTest3";
/////********* CREATE EXCEL(XLSX FILE) FILE *******************///////
$rno = rand();
$filename_title = 'DAILY_RSPL_VTS_REPORT_'.$previous_date.'_'.$rno;
//echo "\nfilename1=".$filename_title."\n";
$file_path = "/var/www/html/vts/beta/src/php/download/".$filename_title;
$fname = tempnam_sfx($file_path, ".xlsx");

//echo "\nFname=".$fname;
$objPHPExcel = new PHPExcel();  //write new file
$objPHPExcel->getActiveSheet()->setTitle('DAILY VTS REPORT-RSPL');

$report_title = "VTS TRACKING REPORT :RSPL -Date:".$previous_date;

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

//########## SET FONT STYLE -BLACK
$styleFontBlack = array(
'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => '000000'), //BLACK
	'size'  => 10
	//'name'  => 'Verdana'
));

//########## SET FONT STYLE -RED
$styleFontRed = array(
'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => 'FF0000'), //RED
	'size'  => 10
	//'name'  => 'Verdana'
));

//echo "\nAfter style";
$r =1;
//### SET FIRST ROW HEIGHT
$cell_range = 'A'.$r.':F'.$r;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells($cell_range);
$objPHPExcel->getActiveSheet()->getStyle($cell_range)->applyFromArray($styleBgYellow);
$objPHPExcel->getActiveSheet()->getRowDimension($r)->setRowHeight(20);
//$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($r)->setWidth(60);

$cell = 'A'.$r;
$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($report_title);
$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);	//SET TITLE

//####### SET CELL WIDTHS
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(2)->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(3)->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(5)->setWidth(80);

$r++;
$r++;	

//GET XML_DATA
function get_daily_rspl_report_xml($vehicle_serial, $vname, $startdate, $enddate)
{
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

	//global $daily_halt_tmp;	

	get_All_Dates($datefrom, $dateto, &$userdates);

	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);

	//$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append

	//$j = 0;
	$total_dist = 0; 									  
	global $timetmp1;
	global $breakflag; 
	global $user_interval;   
	//echo "\nDate size=".$date_size;

	$nodata_flag = 1;
	$nogps_flag = 1;
  
	for($i=0;$i<=($date_size-1);$i++)
	{
		//echo "\nIn Date Loop";
		//echo "<br>time=".$timetmp1;
		$timetmp2 = date("Y-m-d H:i:s");	
		$timetmp2 = strtotime($timetmp2);    
		$difftmp = ($timetmp2 - $timetmp1);
		//echo "<br>diff=".$difftmp;

		$daily_dist = 0; 
		$daily_halt = 0;

		$abspath = "/var/www/html/itrack_vts";

		$xml_current = $abspath."/xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	    		
		//echo "<br>xml_current=".$xml_current;

		if (file_exists($xml_current))      
		{		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = $abspath."/sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}
		
		//echo "<br>xml_file =".$xml_file;	    	
		if (file_exists($xml_file)) 
		{			
			$nodata_flag = 0;             //SET NO DATA OFF  
			//echo "\nfile_exists xml_file";
			$t=time();
			$xml_original_tmp = $abspath."/xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
			//echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
								  
			if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
				copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "<br>TWO<br>";
				$xml_unsorted = $abspath."/xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
				//echo  "<br>".$xml_file." <br>".$xml_unsorted."<br><br>";
				        
				copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
				SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}
      
			$total_lines = count(file($xml_original_tmp));
			//echo "<br>Total lines orig=".$total_lines;

			$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  

			$logcnt=0;
			$DataComplete=false;
					  
			$vehicleserial_tmp=null;
			$format =2;      
      
			if (file_exists($xml_original_tmp)) 
			{              
				//echo "\nFileExists";
				//$daily_dist =0;
				//  $firstdata_flag =0;

				while(!feof($xml))          // WHILE LINE != NULL
				{
					$DataValid = 0;
					//echo fgets($file). "<br />";
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			

					if(strlen($line)>20)
					{
						$linetmp =  $line;
					}

					$linetolog =  $logcnt." ".$line;
					$logcnt++;
					//fwrite($xmllog, $linetolog);

					if(strpos($line,'fix="1"'))     // RETURN FALSE IF NOT FOUND
					{
						$format = 1;
						$fix_tmp = 1;
					}

					else if(strpos($line,'fix="0"'))
					{
						$format = 1;
						$fix_tmp = 0;
					}

					if( (preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{ 
						$lat_value = explode('=',$lat_match[0]);
						$lng_value = explode('=',$lng_match[0]);
						//echo " lat_value=".$lat_value[1];         
						if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
						{
							$DataValid = 1;
							$nogps_flag = 0;     // SET NO GPS FLAG OFF
						}
					}
          
					//if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
					if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
					{
						//preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
						//echo "<br>str3tmp[0]=".$str3tmp[0];
						$status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
						$xml_date = $datetime;
					}				
					//echo "Final0=".$xml_date." datavalid=".$DataValid;

					if($xml_date!=null)
					{				    					
						if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
						{							           	
						//echo "<br>One";             
						$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
						//echo "Status=".$status.'<BR>';
						//echo "test1".'<BR>';
						if($status==0)
						{
							continue;
						}

						$status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
						if($status==0)
						{
							continue;               
						}
						//echo "test6".'<BR>';
						$status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
						if($status==0)
						{
							continue;
						}     
						$lat_tmp1 = explode("=",$lat_tmp[0]);
						$lat = preg_replace('/"/', '', $lat_tmp1[1]);

						$lng_tmp1 = explode("=",$lng_tmp[0]);
						$lng = preg_replace('/"/', '', $lng_tmp1[1]); 

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
							/*
							//********* HALT LOGIC BEGINS
							$lat2_halt = $lat;
							$lng2_halt = $lng;                
							$time2_halt = $datetime;
							 
							calculate_distance($lat1_halt, $lat2_halt, $lng1_halt, $lng2_halt, &$distance_halt);
						
							//if( ($distance > 0.200) || ($f== $total_lines-2) )          			
							//echo "\nlat1_halt=".$lat1_halt.", lat2_halt=".$lat2_halt.", lng1_halt=".$lng1_halt.", lng2_halt=".$lng2_halt.", distance_halt=".$distance_halt;

							if( ($distance_halt > 0.0100) || ($f== $total_lines-2) )
							{
								//echo "\nIn distance";
								//echo "<br>In dist ".$distance." lat_ref ".$lat_ref." lng_ref ".$lng_ref." lat_cr ".$lat_cr." lng_cr ".$lng_cr."<br>";
								if ($halt_flag == 1)
								{				
									//echo "\nIn Halt1";
									$arrivale_time = $time1_halt;
									$starttime = strtotime($time1_halt);

									//$stoptime = strtotime($datetime_cr);  
									$stoptime = strtotime($time2_halt);
									$depature_time = $time2_halt;
									//echo "<br>".$starttime." ,".$stoptime;

									$halt_dur =  ($stoptime - $starttime);

									//echo "\nHalt Dur=".$halt_dur." ,interval=".$interval." ,time1_halt=".$time1_halt." ,time2_halt=".$time2_halt;

									if( ($halt_dur >= $interval) || ($f== $total_lines-2))
									{
									//echo "<br>In Halt else";
									$daily_halt = $daily_halt + $halt_dur; 
									//$total_halt_vehicle = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" lat=\"".$lat_ref."\" lng=\"".$lng_ref."\" arr_time=\"".$arrivale_time."\" dep_time=\"".$depature_time."\" duration=\"".$halt_dur."\"/>";						          						
									//echo "<br>total halt vehicle=".$total_halt_vehicle;
									$linetowrite = $total_halt_vehicle; // for distance       // ADD DISTANCE
									fwrite($fh, $linetowrite); 

									//$date_secs1 = strtotime($datetime_cr);
									//$date_secs1 = (double)($date_secs1 + $interval);                                                   
									}		// IF TOTAL MIN										
									}   //IF HALT FLAG

									$lat1_halt = $lat2_halt;
									$lng1_halt = $lng2_halt;
									$time1_halt = $time2_halt;

									$halt_flag = 0;
									}
									else
									{            			
									//echo "<br>normal flag set";
									$halt_flag = 1;
									}					                                              							  							
										//********* HALT LOGIC CLOSED
								*/
						
								//********* DISTANCE LOGIC BEGINS
								$time2 = $datetime;											
								$date_secs2 = strtotime($time2);	

								$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
								$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);

								$lat2_dist = $lat;
								$lng2_dist = $lng;  

								calculate_distance($lat1_dist, $lat2_dist, $lng1_dist, $lng2_dist, &$distance);
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
						} // $xml_date_current >= $startdate closed
					}   // if xml_date!null closed
					//$j++;
				}   // while closed
			} // if original_tmp closed         
			
			//WRITE DAILY DISTANCE DATA
			/*$daily_distance_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" date=\"".$userdates[$i]."\" daily_dist=\"".$daily_dist."\"/>";						          						
			//echo "<br><br>".$daily_distance_data;
			$linetowrite = $daily_distance_data; // for distance       // ADD DISTANCE
			fwrite($fh, $linetowrite); */ 		

			//$daily_halt_tmp = $daily_halt;     
			fclose($xml);            
			unlink($xml_original_tmp);
		} // if (file_exists closed      

		if($nodata_flag)
		{
			$daily_dist_tmp = -1;   //NO DATA      
		}
		else if($nogps_flag)
		{
			$daily_dist_tmp = -2;   //NO GPS
		}
		else
		{
			$daily_dist_tmp = $daily_dist;
		}
	}  // for closed

	//echo "\nDailyDistance=".$daily_dist_tmp;
	$last_lat[] = $lat;
	$last_lng[] = $lng;  
	//echo "Test1";
	//fclose($fh);
}
/////////////////////////////////////////////
//echo "\nfour";
//## CHECK EXCEPTINON ACCOUNTS
$query_exception = "SELECT account_id FROM exception_report WHERE exception_id =1 AND status=1";
$result_exception = mysql_query($query_exception, $DbConnection);

if($row_exception = mysql_fetch_object($result_exception))
{
	$exception_account = $row_exception->account_id;
}
//echo "\nEX=".$exception_account;

//$query_account = "SELECT account_id,user_id FROM account WHERE group_id='0001' AND user_id NOT IN('rspl') AND account_id NOT IN($exception_account) AND status=1";
$query_account = "SELECT account_id,user_id FROM account WHERE group_id='0001' AND user_id NOT IN('rspl') AND account_id NOT IN($exception_account) AND status=1 limit 2";
//$query_account = "SELECT account_id,user_id FROM account WHERE group_id='0001' AND user_id NOT IN('rspl') AND user_id IN('ASM','accidental') AND account_id NOT IN($exception_account) AND status=1";

$result_account = mysql_query($query_account,$DbConnection);

//echo "\n".$query_account."\n";
$total_vehicles = 0;
$total_inactive_vehicles = 0;
$total_nogps_vehicles = 0;
$accidental_vehicles = 0;

while($row_account = mysql_fetch_object($result_account))
{
	$account_id = $row_account->account_id;
	$user_id = $row_account->user_id;

	$query_name = "SELECT name FROM account_detail WHERE account_id='$account_id'";
	$result_name = mysql_query($query_name,$DbConnection);

	$row_name = mysql_fetch_object($result_name);
	$user_name = $row_name->name;

	$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".
					  "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
					  "vehicle_grouping.account_id='$account_id' AND vehicle_grouping.status=1 AND vehicle.status=1 AND vehicle_assignment.status=1";              
											  
	//echo "\nquery_assignment=".$query_assignment."\n";
	$result_assignment = mysql_query($query_assignment,$DbConnection);

	$v=0;
	$vehicle_id_tmp ="";
	
	//OPEN   	
	
	$cell = 'A'.$r;																	//ACCOUNT DETAIL
	$bg_cell = 'A'.$r.':C'.$r;
	$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgYellow);
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($user_name);				
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);
	$r++;
		
	$bg_cell = 'A'.$r.':C'.$r;
	$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgGreen);
	$cell = 'A'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('SNO');
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontWhite);

	$cell = 'B'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('VEHICLE');
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontWhite);

	$cell = 'C'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('DISTANCE(KM) / STATUS');
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontWhite);

	$cell = 'D'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('Latitude');
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontWhite);

	$cell = 'E'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('Longitude');
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontWhite);

	$cell = 'F'.$r;
	$objPHPExcel->getActiveSheet()->getCell($cell)->setValue('Google Location');
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontWhite);
		
	$j=0;
	$vname = array();
	$vserial = array();
	$vid = array();	

	while($row_assignment = mysql_fetch_object($result_assignment))
	{    
		$vehicle_id_a = $row_assignment->vehicle_id;
		$vname[$j] = $row_assignment->vehicle_name;

		$query_imei = "SELECT device_imei_no FROM vehicle_assignment WHERE vehicle_id ='$vehicle_id_a' AND status=1";
		//echo "\nquery_imei=".$query_imei;
		$result_imei = mysql_query($query_imei, $DbConnection);
		$row_imei = mysql_fetch_object($result_imei);
		$vserial[$j] = $row_imei->device_imei_no;
		$all_imei[] = $vserial[$j];		//ALL IMEIS
		$vid[$j] = $vehicle_id_a;
		 
		if($v==0) 
		  $vehicle_id_tmp = $vehicle_id_tmp.$vehicle_id_a;
		else
		  $vehicle_id_tmp = $vehicle_id_tmp.",".$vehicle_id_a;  

		//echo "\n".$vid[$j]." ,".$vname[$j]." ,".$vserial[$j];
		$j++;
		$v++;     
	}
  
	$vsize = sizeof($vserial);
	if($account_id == "805")	//ACCIDENTAL
	{
		$accidental_vehicles = $vsize;
	}

	$inactive_vehicle_counter = 0;
	$nogps_vehicle_counter = 0;

	$last_lat = array();
	$last_lng = array();

	$sno = 1;
	
	$bg_cell = 'A'.$r.':F'.$r;
	$objPHPExcel->getActiveSheet()->getStyle($bg_cell)->applyFromArray($styleBgGreen);
	//echo "\nSizeV=".$vsize;
	
	$r++;
	$vstatus = "";
	$user_len[] = $user_name;
	
	for($i=0;$i<$vsize;$i++)  
	{  
		//echo "\nV1:i=".$i." ,vsize=".$vsize;
		$total_dist_tmp = 0;
		$total_halt_tmp = 0;

		$daily_dist_tmp =0;             //RESET VARIABLES FOR INDIVIDUAL DAY
		//$daily_halt_tmp =0;

		$date1 = $previous_date." 00:00:00";
		$date2 = $previous_date." 23:59:59";      
				
		//CALL FUNCTION
		get_daily_rspl_report_xml($vserial[$i], $vname[$i], $date1, $date2);
		//echo "\nV2";
		
		$cell = 'A'.$r;
		$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($sno);
		$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);

		$cell = 'B'.$r;
		$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($vname[$i]);
		$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);	

		//echo "\nV3";
		//$daily_dist_tmp = -1;
		//echo "\nDailyDisttmp=".$daily_dist_tmp;
		
		if($daily_dist_tmp == -1)
		{
		   //echo "\nV4";
		   if($account_id == "805")		//ACCIDENTAL
		   {
				$daily_dist_tmp = "ACCIDENTAL";
		   }
		   else
		   {
				$daily_dist_tmp = "INACTIVE";
				$inactive_vehicle_counter++;
				$all_inactive[] = $vserial[$i];	//ALL INACTIVES
				$vstatus = "NA";
				$sms_string_inactive[$user_name][] = $vname[$i];
		   }
		   
			$cell = 'C'.$r;
			$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($daily_dist_tmp);
			$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontRed);	
			//echo "\nV5";			
		}    
		else if($daily_dist_tmp == -2)
		{
			//echo "\nV6";
			$daily_dist_tmp = "No GPS";
			$nogps_vehicle_counter++;
			$all_nogps[] = $vserial[$i];	//ALL NO GPS
			$vstatus = "NG";	
			$sms_string_nogps[$user_name][] = $vname[$i];			
			
			$cell = 'C'.$r;
			$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($daily_dist_tmp);
			$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontRed);
			//echo "\nV6";			
		}
		else
		{
			//echo "\nV7";
			$vstatus = "AC";
			$cell = 'C'.$r;
			$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($daily_dist_tmp);
			$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);
			//echo "\nV8";
		}
		 
		//echo "Lat=".$last_lat[$i]." ,lng=".$last_lng[$i];
		if($last_lat[$i]!="" && $last_lng[$i]!="")
		{
		  $last_lat[$i] = substr_replace($last_lat[$i] ,"",-1);
		  $last_lng[$i] = substr_replace($last_lng[$i] ,"",-1);
		}			
		
		$cell = 'D'.$r;
		$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($last_lat[$i]);
		$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);

		$cell = 'E'.$r;
		$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($last_lng[$i]);
		$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);		
		
		//echo "\nV9";
		//####### GET GOOGLE LOCATION
		$lttmp = $last_lat[$i];
		$lngtmp = $last_lng[$i];

		$placename1 = "";
		if($lttmp!="" && $lngtmp!="")
		{
		  get_report_location($lttmp,$lngtmp,&$placename1);
		  //echo "<br>PL1=".$placename1;
		  $placename1 = preg_replace('/भारत गणराज्य/', '' , $placename1);
		  $placename1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '' , $placename1);
		  //echo "<br>PL3=".$placename1;
		}
				
		$cell = 'F'.$r;
		$objPHPExcel->getActiveSheet()->getCell($cell)->setValue($placename1);
		$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);				
		//echo "\nV10";

		//$worksheet->write($r, 5, "Halt(H:m:s)", $text_format);  
		$r++;  
		//$daily_halt_global[$i] = $daily_halt_tmp;
		//echo "\nIMEI=".$vserial[$i]." ,Date1=".$date1." ,Date2=".$date2." dist=".$daily_dist_tmp;  
		$sno++; 

		//######### CODE FOR REPORT2 FILE
		if($i == 0)
		{
			//echo "\nV11";
			//######## SET SUBGROUP IN COLUMN -B
			$subgroup_cell = 'B'.$r2;
			$objPHPExcel2->getActiveSheet()->getCell($subgroup_cell)->setValue($user_name);
			$objPHPExcel2->getActiveSheet()->getStyle($subgroup_cell)->applyFromArray($styleText);			 
			//echo "\nV12";
		}

		//######## SET VEHICLE IN COLUMN -C
		$vehicle_cell = 'C'.$r2;
		$objPHPExcel2->getActiveSheet()->getCell($vehicle_cell)->setValue($vname[$i]);
		$objPHPExcel2->getActiveSheet()->getStyle($vehicle_cell)->applyFromArray($styleText);
		
		//echo "\nV13";
		//######## SET STATUS :DECIDE COLUMN BY THE DAY
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
		}						
		$objPHPExcel2->getActiveSheet()->getCell($vstatus_cell)->setValue($vstatus);
		$objPHPExcel2->getActiveSheet()->getStyle($vstatus_cell)->applyFromArray($styleText);			 
		
		//echo "\nV14";
		//###### CALCULATE TOTAL AC,NA,NG IN THIS ROW -READ ALL VALUES
		$count_ac = 0;
		$count_na = 0;
		$count_ng = 0;

		if (file_exists($destpath2)) 				//LOAD IF EXISTS
		{		
			//echo "\nV15";			
			foreach ($objPHPExcel2->setActiveSheetIndex(0)->getRowIterator() as $row2) 
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
			}
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
					
		/*$ac_total = "AI".$r2;
		$na_total = "AJ".$r2;
		$ng_total = "AK".$r2;
		//$remark = "AL".$r2;
		$objPHPExcel2->getActiveSheet()->getCell($ac_total)->setValue($count_ac);
		$objPHPExcel2->getActiveSheet()->getStyle($ac_total)->applyFromArray($styleText);

		$objPHPExcel2->getActiveSheet()->getCell($na_total)->setValue($count_na);
		$objPHPExcel2->getActiveSheet()->getStyle($na_total)->applyFromArray($styleText);

		$objPHPExcel2->getActiveSheet()->getCell($ng_total)->setValue($count_ng);
		$objPHPExcel2->getActiveSheet()->getStyle($ng_total)->applyFromArray($styleText);
		//echo "\nV17";
		*/
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
	$objPHPExcel->getActiveSheet()->getStyle($cell)->applyFromArray($styleFontBlack);						
	//CLOSE 

	$total_vehicles = $total_vehicles + $vsize;
	$total_inactive_vehicles = $total_inactive_vehicles + $inactive_vehicle_counter;
	$total_nogps_vehicles = $total_nogps_vehicles + $nogps_vehicle_counter;
	//echo "\nLAST";*/
	$r++;
	$r++;
	$r++;
	
	echo "\nUserId :".$user_name." completed -(Vehicles:".$vsize.")\n";
 
	//######## UPDATE ROW POINTER OF SECOND FILE
	//$r2++;	
	//##########################################
}  //ACCOUNT CLOSED

//echo "\nAccount After-1";

//######### WRITE SMS CODE #################
$sms_date = date('Y-m-d');
$sms_path = "/home/VTS/iespl_sms_sender/temp_files/send_messages1/".$sms_date;
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
	$sms_string1 = "SG:".$tmp."=>";
	$count = 0;
	for($j=0;$j<sizeof($sms_string_inactive[$tmp]);$j++)
	{		
		$sms_string1 = $sms_string1.$sms_string_inactive[$tmp][$j].",";
		$count++;
		if($count == 5)
		{
			$sms_string1 = substr($sms_string1, 0, -1);
			$tmp_data = "<marker phone=\"".$phone1."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string1."\" person=\"".$person1."\"/>\n";
			$tmp_data .= "<marker phone=\"".$phone2."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string1."\" person=\"".$person2."\"/>\n";
			$tmp_data .= "<marker phone=\"".$phone3."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string1."\" person=\"".$person3."\"/>\n";
			fwrite($file1, $tmp_data);
			$sms_string1 = "SG:".$tmp."=>";
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
	$sms_string2 = "SG:".$tmp."=>";
	$count = 0;
	for($j=0;$j<sizeof($sms_string_nogps[$tmp]);$j++)
	{		
		$sms_string2 = $sms_string2.$sms_string_nogps[$tmp][$j].",";
		$count++;
		if($count == 5)
		{
			$sms_string2 = substr($sms_string2, 0, -1);
			$tmp_data = "<marker phone=\"".$phone1."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string2."\" person=\"".$person1."\"/>\n";
			$tmp_data .= "<marker phone=\"".$phone2."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string2."\" person=\"".$person2."\"/>\n";
			$tmp_data .= "<marker phone=\"".$phone3."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string2."\" person=\"".$person3."\"/>\n";
			fwrite($file2, $tmp_data);
			$sms_string2 = "SG:".$tmp."=>";
			$count = 0;
		}
		else if($j == sizeof($sms_string_nogps[$tmp])-1)
		{
			$sms_string2 = substr($sms_string1, 0, -1);
			$tmp_data = "<marker phone=\"".$phone1."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string2."\" person=\"".$person1."\"/>\n";
			$tmp_data .= "<marker phone=\"".$phone2."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string2."\" person=\"".$person2."\"/>\n";
			$tmp_data .= "<marker phone=\"".$phone3."\" vehicle_id=\"".$vid."\" alertid=\"".$alert_id."\" escalationid=\"".$eid."\" sts=\"".$sts."\" datetime=\"".$date."\" message=\"".$sms_string2."\" person=\"".$person3."\"/>\n";						
			fwrite($file2, $tmp_data);
		}		
	}
}
fwrite($file2, "</t1>");
fclose($file2);	
//##########################################

$r++;
$r++; 
/*
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
 
//echo "\nFname=".$fname;
//######## SAVE XLSX FILE
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$abspath = "xlsx/abc.xlsx";
//echo "\nABS=".$abspath;
$objWriter->save($fname);
//echo "\nAccount After-4";

//########### SEND MAIL ##############//
//$to = 'rizwan@iembsys.com'; 
$to = 'shweta.srivastava@rspl.net.in,amit.k@rspl.net.in,vaibhavmpec_knp@rediffmail.com,agrijrspl@gmail.com,kulvinder.singh@rspl.net.in';
$subject = 'VTS_REPORT_RSPL_'.$previous_date."/".$previous_year;
$message = 'VTS_REPORT_RSPL_'.$previous_date."/".$previous_year; 
$random_hash = md5(date('r', time()));  
$headers = "From: support@iembsys.co.in\r\n";
//$headers .= "Cc: rizwan@iembsys.com";
$headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com,support1@iembsys.com";
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
$filename_title = $filename_title.".xlsx";
$file_path = $file_path.".xlsx";
//echo "\nFILE PATH=".$file_path;

include("send_mail_api.php");
//####################################//
unlink($file_path);

//echo "\nBefore mail";
//############# CREATE FOLDER AND BACKUP FILES ########
$dirPath = "excel_reports/".$previous_date;
//echo "\nDirPath=".$dirPath;
mkdir ($dirPath, false);
@chmod($dirPath, 0777);

//###### SOURCE PATH1 ################
$sourcepath = $file_path;
$destpath = $dirPath."/".$filename_title;

@chmod($destpath, 0777);
//echo "\nSourcePath=".$sourcepath." ,DestPath=".$destpath;
copy($sourcepath,$destpath);
//####################################
//echo "\nAfter mail1";

//###### WRITE SOURCE PATH2 ################
$objWriter2 = PHPExcel_IOFactory::createWriter($objPHPExcel2, 'Excel2007');
//echo "\nDestPath2=".$destpath2;
$objWriter2->save($destpath2);
//####################################

//echo "\nAfter mail2";
//########### SEND MAIL ##############//
//$to = 'rizwan@iembsys.com'; 
$to = 'shweta.srivastava@rspl.net.in,amit.k@rspl.net.in,vaibhavmpec_knp@rediffmail.com,agrijrspl@gmail.com,kulvinder.singh@rspl.net.in';
$subject = 'RSPL_VEHICLE_STATUS_REPORT_'.$previous_date."/".$previous_year;
$message = 'RSPL_VEHICLE_STATUS_REPORT_'.$previous_date."/".$previous_year; 
$random_hash = md5(date('r', time()));  
$headers = "From: support@iembsys.co.in\r\n";
//$headers .= "Cc: rizwan@iembsys.com,ashish@iembsys.co.in";
$headers .= "Cc: ashish@iembsys.co.in,rizwan@iembsys.com,jyoti.jaiswal@iembsys.com,support1@iembsys.com";
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
$filename_title = $filename_title2;
$file_path = $destpath2;
//echo "\nFILE PATH2=".$file_path;

include("send_mail_api2.php");
//####################################//

//########## BACKUP FILES CLOSED #######################
*/
?>
