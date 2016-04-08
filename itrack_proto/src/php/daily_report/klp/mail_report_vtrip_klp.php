<?php
//include_once('util_session_variable.php');
//include_once('util_php_mysql_connectivity.php');

set_time_limit(800);

$DBASE = "iespl_vts_beta";
$USER = "root";
$PASSWD = "mysql";
//$HOST = "localhost";
include_once("../database_ip.php");
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Could Not Connect to Server");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");


$abspath = "/var/www/html/vts/beta/src/php/";
include_once($abspath."/common_xml_element.php");
require_once $abspath."excel_lib/class.writeexcel_workbook.inc.php";
require_once $abspath."excel_lib/class.writeexcel_worksheet.inc.php";
include_once($abspath."get_all_dates_between.php");
include_once($abspath."sort_xml.php");
include_once($abspath."calculate_distance.php");
include_once($abspath."util.hr_min_sec.php");

$base_station_name = array(); //TOTAL INDEPENDENT STATIONS
$base_station_coord = array();
//$trip_datetime = array(array());
$departure_time = array();
$arrival_time = array();
$trip_departure_time = array();
$trip_arrival_time = array();
$meantime_distance_tmp = array();
$meantime_distance = array();
//$total_trip = array(array());
$trip_vname = array();
$trip_count = 0;
//$trip_time = "";

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

/*$vserial[] = "359231031741174";
$vserial[] = "862170010570248";
$vserial[] = "862170010546396";

$startdate = "2012-08-20 00:00:00";
$enddate = "2012-08-20 23:59:59";*/

//##### GET IMEI DETAIL          //ACCOUNT_ID =715 (=klp)
$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".
        "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
        "vehicle_grouping.account_id=715 AND vehicle.status=1 AND vehicle_assignment.status=1";                                                         

//echo "\nquery=".$query_assignment."\n";
$result_assignment = mysql_query($query_assignment,$DbConnection);

while($row_assignment = mysql_fetch_object($result_assignment))
{
   $vid = $row_assignment->vehicle_id;
   $vname[] = $row_assignment->vehicle_name;
   
   $query_imei = "SELECT device_imei_no FROM vehicle_assignment WHERE vehicle_id ='$vid' AND status=1";
   $result_imei = mysql_query($query_imei, $DbConnection);
   $row_imei = mysql_fetch_object($result_imei);
   $vserial[] = $row_imei->device_imei_no;  
}
//#####################

$date = date('Y-m-d');
$previous_date = date('Y-m-d', strtotime($date .' -1 day'));

$startdate = $previous_date." 00:00:00";
$enddate = $previous_date." 23:59:59"; 


trip_report_xml($vserial, $vname, $startdate, $enddate);

function trip_report_xml($vserial, $vname, $startdate, $enddate)
{
	global $DbConnection;
	global $trip_count;
	global $trip_time;

	global $base_station_name;
	global $base_station_coord;

	global $departure_time;
	global $arrival_time;

	global $trip_departure_time;
	global $trip_arrival_time;
	global $mdistance;
	global $meantime_distance_tmp;
	global $meantime_distance;    	

	//global $trip_datetime;
	//global $total_trip;
	global $trip_vname;  

	$maxPoints = 1000;
	$file_exist = 0;

	
	$query_station = "SELECT landmark.landmark_name, landmark.landmark_coord FROM landmark WHERE landmark.status=1 AND landmark.account_id=715";			  	   
	//echo $query_station."\n, DBCON=".$DbConnection;

	$result_station = mysql_query($query_station,$DbConnection);

	$base_station_name_temp = "";
	$base_station_coord_temp = "";
		
	if($row_station = mysql_fetch_object($result_station))
	{    
	  $base_station_name_temp = $row_station->landmark_name; 
	  $base_station_coord_temp = $row_station->landmark_coord;
	  //$distance_variable_temp = $row_station->distance_variable;
	  $distance_variable_temp = 0.1;
	  //echo "\n ONE: TRIP base_station_name=".$base_station_name_temp."\n";
	} 
		
	for($i=0;$i<sizeof($vserial);$i++)
	{  	
		$departure_time = array();
		$arrival_time = array();
		$meantime_distance_tmp = array();		
				     
		//echo "\nIN loop";
		$trip_count = 0;
		$trip_time = "";

		//$distance_variable_tmp = $row2->distance_variable;
		//$distance_variable = 1;    
		if($base_station_name_temp!="")
		{
			$base_station_coord_temp = str_replace(', ',',',$base_station_coord_temp);
			
			$mdistance = 0.0;
			get_trip_data($vserial[$i], $vname[$i], $startdate, $enddate, $base_station_coord_temp, $distance_variable_temp);
			//echo "\n TRIP base_station_name=".$base_station_name_temp."\n";

			//$total_trip[$i][$j] = $trip_count;    
			/*echo "\nSizeMeanTimeDist=".sizeof($meantime_distance_tmp)." ,DepartureTime=".sizeof($departure_time);

			for($k=0;$k<sizeof($meantime_distance_tmp);$k++)
			{
				echo "\nMeanTimeDist0=".$meantime_distance_tmp[$k];
			}*/		
			
			for($j=0;$j<sizeof($departure_time);$j++)
			{
				//echo "\n trip_departure_time=".$departure_time[$j]." ,arrival_time=".$arrival_time[$j];      
				$trip_vname[] = $vname[$i];
				$base_station_name[] = $base_station_name_temp;
			
				if($departure_time[$j]=="")
				  $departure_time[$j] = "No DepartureTime found";
				
				$trip_departure_time[] = $departure_time[$j];		//TRIP DEPARTURE TIME
				
				if($arrival_time[$j]=="")
				  $arrival_time[$j] = "No ArrivalTime found";

				$trip_arrival_time[] = $arrival_time[$j];			//TRIP ARRIVAL TIME
				$meantime_distance[] = $meantime_distance_tmp[$j];
				//echo "\nMeanTimeDist1=".$meantime_distance_tmp[$j];
			}
		  
			if(sizeof($departure_time)==0 && sizeof($arrival_time)==0)
			{
				$trip_vname[] = $vname[$i];
				$base_station_name[] = $base_station_name_temp;
				$trip_departure_time[] = "No DepartureTime found";
				$trip_arrival_time[] = "No ArrivalTime found";
				$meantime_distance[] = $meantime_distance_tmp[$j];				
			}
		}                
	}   
    
  /*for($i=0;$i<sizeof($trip_vname);$i++)
	{
    echo "\nVehicle:".$trip_vname[$i]." ,base_station_name=".$base_station_name[$i]." ,trip_dep=".$trip_departure_time[$i]." ,trip_arr=".$trip_arrival_time[$i]."\n";     
  }*/
} //FUNCTION CLOSED 
  
/////########### CREATE EXCEL FILE ###########///////

$rno = rand();
$filename_title = 'DAILY_VTS_REPORT_(KLP)_'.$previous_date.'_'.$rno;
//echo "\nfilename=".$filename_title;
$file_path = "/var/www/html/vts/beta/src/php/download/".$filename_title;
$fname = tempnam_sfx($file_path, ".xls");
$workbook =& new writeexcel_workbook($fname);                     //******* ADD WORKBOOK

$border1 =& $workbook->addformat();                               //******* DEFINE BORDER STYLE
$border1->set_color('white');
$border1->set_bold();
$border1->set_size(9);
$border1->set_pattern(0x1);
$border1->set_fg_color('green');
$border1->set_border_color('yellow');

$text_format1 =& $workbook->addformat(array(                      //******* DEFINE TEXT STYLE
			  bold    => 1,
			  //italic  => 1,                       
			  color   => 'blue',
			  size    => 10,
			  //font    => 'Comic Sans MS'
		  ));
		  
$text_format2 =& $workbook->addformat(array(                      //******* DEFINE TEXT STYLE
			  bold    => 1,
			  //italic  => 1,                       
			  color   => 'red',
			  size    => 10,
			  //font    => 'Comic Sans MS'
		  ));              

$text_format3 =& $workbook->addformat();
$text_format3->set_bold();
$text_format3->set_size(8);

																				  
$blank_format = & $workbook->addformat();                       //******* DEFINE BLANK STYLE
$blank_format->set_color('white');
$blank_format->set_bold();
$blank_format->set_size(12);
$blank_format->set_merge();
/////**********************
//echo "\nafter blank format";        

$imei_msg = "";
$vids ="";

$r=1;
$c=0;
$worksheet_title = "DAILY VTS REPORT(KLP)";
$worksheet =& $workbook->addworksheet($worksheet_title);  

$report_title = "DAILY REPORT (KLP)- Date :( ".$startdate." to ".$enddate." )";
$worksheet->write ($r, 0, $report_title, $border1);

for($b=1;$b<=5;$b++)
{
$worksheet->write_blank($r, $b,$border1);
}  
$r++;
$worksheet->write($r, $c, "SNo", $text_format1);
$c++;
$worksheet->write($r, $c, "Vehicle", $text_format1);
$worksheet->set_column($c, $c, 16);
$c++;
$worksheet->write($r, $c, "Base Station", $text_format1);
$worksheet->set_column($c, $c, 16);
$c++;
$worksheet->write($r, $c, "Departure Time", $text_format1);
$worksheet->set_column($c, $c, 16);
$c++; 
$worksheet->write($r, $c, "Arrival Time", $text_format1);
$worksheet->set_column($c, $c, 16);
$c++;
$worksheet->write($r, $c, "MeanTime Distance(Km)", $text_format1);
$worksheet->set_column($c, $c, 16);
$c++;      

$r++;                         
	
for($i=0;$i<sizeof($trip_vname);$i++)
{       
	$c =0;
	$sno_tmp = $i + 1;  
	//echo "\nBEFORE WRITE, trip_vname=".$trip_vname[$i]." ,base_station_name=".$base_station_name[$i]." ,trip_departure_time=".$trip_departure_time[$i]." ,trip_arrival_time=".$trip_arrival_time[$i];
	$worksheet->write($r,$c, $sno_tmp, $text_format3);
	$c++;
	$worksheet->write($r,$c, $trip_vname[$i],$text_format3);
	$worksheet->set_column($c, $c, 16);
	$c++;
	$worksheet->write($r,$c, $base_station_name[$i],$text_format3);
	$worksheet->set_column($c, $c, 16);
	$c++;      
	$worksheet->write($r,$c, $trip_departure_time[$i],$text_format3);
	$worksheet->set_column($c, $c, 16);
	$c++;
	$worksheet->write($r,$c, $trip_arrival_time[$i],$text_format3);
	$worksheet->set_column($c, $c, 16);
	$c++;        
	$worksheet->write($r,$c, $meantime_distance[$i],$text_format3);
	$worksheet->set_column($c, $c, 16);
	$c++;	
			   
	$r++;  
	//echo "\n SHEET CLOSED\n";          
}  
$workbook->close(); //CLOSE WORKBOOK
//echo "\nWORKBOOK CLOSED";
/////////////////////////////////////////////////////        

//########### SEND MAIL ##############//
//$to = 'rizwan@iembsys.com';
$to = 'aviral@klpl.in';
$subject = 'DAILY VTS_REPORT_(KLP)_'.$previous_date;
$message = 'DAILY VTS_REPORT_(KLP)_'.$previous_date; 
$random_hash = md5(date('r', time()));  
$headers = "From: support@iembsys.co.in\r\n";
//$headers .= "Reply-To: taseen@iembsys.com\r\n"; 
$headers .= "Cc: jyoti.jaiswal@iembsys.com,rizwan@iembsys.com";
//$headers .= "Cc: rizwan@iembsys.com";
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
$filename_title = $filename_title.".xls";
$file_path = $file_path.".xls";
//echo "\nFILE PATH=".$file_path;

include_once("send_mail_api.php");
//####################################//

unlink($file_path);
     

function get_trip_data($vehicle_serial, $vname, $startdate, $enddate, $base_station_coord, $distance_variable)
{
	$abspath = "/var/www/html/vts/beta/src/php";
	include($abspath."/common_xml_element_for_function.php");

	//echo "\nvserial=".$vehicle_serial." ,vname=".$vname." ,st=".$startdate." ,ed=".$enddate;
	global $trip_count;
	//global $trip_time;
	global $departure_time;
	global $arrival_time;
	global $meantime_distance_tmp;	

	//## DECLARE VARIABLES
	$mdistance = 0.0;
	$entered_flag = 0;
	$exited_flag = 1;
	$trip_progress = 0;
	$trip_complete = 0;
	$re_entered_flag = 0;
	$start_trip = false;
	////////////////////// 

	$geo_data = explode(',',$base_station_coord);
	$geo_lat = $geo_data[0];
	$geo_lng = $geo_data[1];  
     
	$fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;
	$linetowrite="";
	$firstdata_flag =0;
	$date_1 = explode(" ",$startdate);
	$date_2 = explode(" ",$enddate);

	$datefrom = $date_1[0];
	$dateto = $date_2[0];
	$timefrom = $date_1[1];
	$timeto = $date_2[1];

	get_All_Dates($datefrom, $dateto, &$userdates);

	////date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);

	$j = 0;
	$total_dist = 0.0;
 									
	$abspath = "/var/www/html/itrack_vts";
	//$abspath_current = "/mnt/volume3";
	//$abspath_sorted = "/mnt/volume4";	
  
	$firstdata_flag_dist = 0;
	$flag_out = false;
  
	include("/var/www/html/vts/beta/src/php/common_xml_path.php");
	for($i=0;$i<=($date_size-1);$i++)
	{
		$startdate1 = $startdate;
		$enddate1 = $enddate;
		  
		$xml_current = $xml_data."/".$userdates[$i]."/".$vehicle_serial.".xml";	
    		
		if (file_exists($xml_current))      
		{		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = $sorted_xml_data."/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}
		
		//echo "<br>xml in xml_data =".$xml_file;	    	
		if (file_exists($xml_file)) 
		{			
			$t=time();
			$xml_original_tmp = $abspath."/xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
											  
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
			//echo "\nTotal lines orig=".$total_lines;

			$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
			//$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
			$logcnt=0;
			$DataComplete=false;                  
			$vehicleserial_tmp=null;
			$format =2;
			$c = -1;			

			if (file_exists($xml_original_tmp)) 
			{              
				//echo "\nexist original";        
				while(!feof($xml))          // WHILE LINE != NULL
				{
					$c++;
					$DataValid = 0;
					//echo "<br>line";
					//echo fgets($file). "<br />";
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
						
					if(strlen($line)>20)
					{
						$linetmp =  $line;
					}
						
					$linetolog =  $logcnt." ".$line;
					$logcnt++;
					//fwrite($xmllog, $linetolog);
		  						
					//SWITCH MASTER VARIABLES
					set_master_variable($userdates[$i]);

					if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
					{
						$format = 1;
						$fix_tmp = 1;
					}
							
					else if(strpos($line,''.$vc.'="0"'))
					{
						$format = 1;
						$fix_tmp = 0;
					}			
					
					if( (preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{ 
						$lat_value = explode('=',$lat_match[0]);
						$lng_value = explode('=',$lng_match[0]);
						//echo " lat_value=".$lat_value[1];         
						if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
						{
							$DataValid = 1;
						}
					}
			  
					//if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
					if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
					{					
						$status = preg_match('/'.$vd.'="[^"]+/', $line, $datetime_tmp);
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
						$xml_date = $datetime;
					}				
					//echo "Final0=".$xml_date." datavalid=".$DataValid;
				  
					if($xml_date!=null)
					{				  
						//echo "<br>".$xml_date.",".$startdate.",".$enddate.",".$DataValid;
						if( ($xml_date >= $startdate1 && $xml_date <= $enddate1) && ($xml_date!="-") && ($DataValid==1) )
						{							           	
							//echo "<br>One";             
							/*$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
							//echo "Status=".$status.'<BR>';
							//echo "test1".'<BR>';
							if($status==0)
							{
								continue;
							}*/                  
							$status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
							if($status==0)
							{
							  continue;               
							}
							//echo "test6".'<BR>';
							$status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
							if($status==0)
							{
							  continue;
							}     
																			  
							$lat_tmp1 = explode("=",$lat_tmp[0]);
							$lat = preg_replace('/"/', '', $lat_tmp1[1]);

							$lng_tmp1 = explode("=",$lng_tmp[0]);
							$lng = preg_replace('/"/', '', $lng_tmp1[1]);

							//############################
							check_with_range_landmark($lat, $lng, $geo_lat, $geo_lng, $distance_variable, &$status_geo);
							//echo "\n status_geo=".$status_geo." ,lat1=".$lat." ,lng=".$lng." ,geo_lat=".$geo_lat." ,geo_lng=".$geo_lng;

							if( ($status_geo) && ($exited_flag) && (!$trip_progress) )							//IN LANDMARK / PLANT
							{
								//echo "\nEntered:vname=".$vname." ,datetime=".$datetime;
								$entered_flag = 1;
								$exited_flag = 0; 															
							}
							else if( ($status_geo) && ($exited_flag) && ($trip_progress) )							//IN LANDMARK / PLANT
							{
								//echo "\nRe-Entered:vname=".$vname." ,datetime=".$datetime;
								$re_entered_flag = 1;
								$exited_flag = 0; 														
							}							
							
							if($entered_flag)		//EXITED FROM LANDMARK / PLANT
							{                
								//echo "\nEnter Flag";								
								
								if(!$status_geo)
								{									
									//echo "\nExited, In Trip_progress:datetime=".$datetime;
									//$departure_time[] = $datetime;
									//echo "\nVehicle:".$vname." ,DEP TIME=".$datetime;
									
									//$entered_flag = 0;									
									if(!$flag_out)
									{
										$outime1 = strtotime($datetime);
										$flag_out = true;
									}

									$outime2 = strtotime($datetime);
									$diff = $outime2 - $outime1;
										
									//echo "\nDiff=".$diff;									
									if($diff > 120)
									{										
										//echo "\nDIFF=".$diff;										
										if(!$start_trip)
										{
											//echo "\nDeparture";
											$departure_time[] = $datetime;
											$start_trip = true;
										}										
										//########### DISTANCE SECTION ############//
										if($firstdata_flag_dist==0)
										{
											//echo "\nFirstData";
											$firstdata_flag_dist = 1;
											$lat1_dist = $lat;
											$lng1_dist = $lng;
											$last_time1_dist = $datetime;
											$latlast = $lat;
											$lnglast =  $lng;     
											$mdistance = 0.0;												
										}           	
										else
										{                           
											//echo "\nNextData";
											$time2_dist = $datetime;											
											$date_secs2_dist = strtotime($time2_dist);	

											$lat2_dist = $lat;
											$lng2_dist = $lng;  

											$distance = 0;
											calculate_distance($lat1_dist, $lat2_dist, $lng1_dist, $lng2_dist, &$distance);
											//echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance; 						
											$tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1_dist)) / 3600;

											calculate_distance($latlast, $lat2_dist, $lnglast, $lng2_dist, &$distance1);

											if($tmp_time_diff1>0)
											{
												$tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
												$last_time1_dist = $datetime;

												$latlast = $lat2_dist;
												$lnglast =  $lng2_dist;           					
											}

											$tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time_dist) )) / 3600;

											//if($tmp_speed <3000 && $distance>0.1)
											//echo "\nTMPSPEED=".$tmp_speed." ,distance=".$distance." ,tmp_time_diff=".$tmp_time_diff;
											
											if($tmp_speed<500 && $distance>0.1 && $tmp_time_diff>0)
											{		              
												//echo "\nIn Distance";
												$mdistance = (double) ($mdistance + $distance);	
												$mdistance = round($mdistance,2);											//echo "\n\nDailyDist=".$daily_dist." ,OpDist=".$ophr_dist;							                          				                                       
												///////////////////////////////////////////////////////////																							
												$lat1_dist = $lat2_dist;
												$lng1_dist = $lng2_dist;

												$last_time_dist = $datetime;	
											}							                               
										}
										//############# DISTANCE SECTION CLOSED #############//								  							  
									}
									
									$trip_progress = 1;	
									$exited_flag = 1;
								}
							}

							if($trip_progress && $re_entered_flag)			//ENTERED AGAIN IN LANDMARK / PLANT
							{
								//echo "\nTrip Completed1";
								$trip_complete = 1;
							}

							if($trip_complete)
							{
								//echo "\nTrip completed:vname=".$vname." ,mdistance=".$mdistance." ,datetime=".$datetime." ,trip_progress=".$trip_progress." ,re_enteredflag=".$re_entered_flag;
								$trip_count++;
								//$trip_time = $trip_time.$datetime.",";
								$arrival_time[] = $datetime;
								$start_trip = false;
								//echo "\nVehicle:".$vname." ,ARR TIME=".$datetime;
								$entered_flag = 0;
								$exited_flag = 0;
								$re_entered_flag = 0;
								$trip_progress = 0;
								$trip_complete = 0;
								$meantime_distance_tmp[] = $mdistance;								
								$firstdata_flag_dist=0;					
								/*for($k=0;$k<sizeof($meantime_distance_tmp);$k++)
								{
									echo "\nMeanTimeDist2=".$meantime_distance_tmp[$k];
								}*/
								//echo "\nTRIP COUNT=".$trip_count;                 
							}
				
							//############################                        				                               									                               			               				    				
						} // $xml_date_current >= $startdate closed    			
										
					}   // if xml_date!null closed
														
					$j++;
											
					////////// FINAL DIST CLOSED          		
				}   // while closed
			} // if original_tmp closed     	
											
			fclose($xml);            
			unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 
}


function check_with_range_landmark($lat1, $lng1, $lat2, $lng2, $distance_var, &$status_geo)
{
  calculate_distance($lat1, $lat2, $lng1, $lng2, &$distance);
  //echo "\ndistance=".$distance;
  //if($distance < 1)
  if($distance < $distance_var)	  
  {                                                        
	  $status_geo = true; 
  }  
  else
  {
	  $status_geo = false;
  }
}

?>
