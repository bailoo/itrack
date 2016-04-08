<?php
set_time_limit(0);
$abspath = "/var/www/html/vts/beta/src/php";
include_once($abspath."/common_xml_element.php");
include_once($abspath."/util_php_mysql_connectivity_mail.php");
//set_time_limit(500);

include_once($abspath."/get_all_dates_between.php");
include_once($abspath."/sort_xml.php");
include_once($abspath."/calculate_distance.php");
include_once($abspath."/report_title.php");
include_once($abspath."/read_filtered_xml.php");
include_once($abspath."/get_location.php");
include_once($abspath."/user_type_setting.php");
include_once($abspath."/select_landmark_report.php");
include_once($abspath."/area_violation/check_with_range.php");
include_once($abspath."/area_violation/pointLocation.php");
require_once $abspath."/excel_lib/class.writeexcel_workbook.inc.php";
require_once $abspath."/excel_lib/class.writeexcel_worksheet.inc.php";
include_once($abspath."/util.hr_min_sec.php");

$timetmp1 = 0;
$breakflag = 0;
/*$current_date = date("Y-m-d");
$startdate = $current_date." 09:00:00";
$enddate = $current_date." 18:00:00";*/

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

//GET PREVIOUS MONTH
$current_month=date('m');
//$current_month=1;
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

//CHANGE TEMPORARILY FOR CURRENT MONTH

$previous_month = $current_month;
$previous_year = $current_year;
$total_days = 25; 
//$total_days = 2;
//$total_days=date('t',mktime(0,0,0,$previous_month,1,$previous_year));		// TOTAL DAYS IN PREVIOUS MONTH
////////////////  

$vserial_global = array();
$vname_global = array();
$vid_global = array();
$date_global = array(array());
$daily_dist_global = array(array());
$daily_halt_global = array(array());

$daily_travel_startime = array(array());
$daily_travel_endtime = array(array());

$total_distance_global = array();
$total_halt_global = array();

$daily_dist_tmp =0;
$daily_halt_tmp =0;
$daily_travel_startime_tmp =0;
$daily_travel_endtime_tmp =0;
$travel_flag = 0;
   
echo "previous_month=".$previous_month." ,previous_year=".$previous_year." ,total_days=".$total_days;

$user_interval = "30";            

$account_id = "325";  //ITS2012,  manish2012

/*$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle,vehicle_assignment,alert,alert_assignment WHERE ".
                    "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = alert_assignment.vehicle_id AND ".
                    "alert_assignment.alert_id = alert.alert_id AND alert.alert_name='visit_report' AND ".
                    "vehicle.status=1 AND vehicle_assignment.status=1 AND alert_assignment.status=1";*/
                    
$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".
        "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
        "vehicle_grouping.account_id=325 AND vehicle.status=1 AND vehicle_assignment.status=1";
 
//echo "query=".$query_assignment;           
$result_assignment = mysql_query($query_assignment,$DbConnection);

$v=0;
$vehicle_id_tmp ="";

while($row_assignment = mysql_fetch_object($result_assignment))
{
B
  $vehicle_id_a = $row_assignment->vehicle_id;
  $vehicle_name[] = $row_assignment->vehicle_name;
  
  $query_imei = "SELECT device_imei_no FROM vehicle_assignment WHERE vehicle_id ='$vehicle_id_a' AND status=1";
  //echo "\nquery_imei=".$query_imei;
  $result_imei = mysql_query($query_imei, $DbConnection);
  $row_imei = mysql_fetch_object($result_imei);
  $device_imei_no[] = $row_imei->device_imei_no;
  $vid[] = $vehicle_id_a;
   
  if($v==0) 
    $vehicle_id_tmp = $vehicle_id_tmp.$vehicle_id_a;
  else
    $vehicle_id_tmp = $vehicle_id_tmp.",".$vehicle_id_a;  

  $v++;     
}

$vsize=sizeof($device_imei_no);
echo "\nvsize=".$vsize;

if($vsize>0)
{  
  monthly_surya_report_prev_xml($device_imei_no, $vehicle_name, $vid);
}

function monthly_surya_report_prev_xml($vserial, $vname, $vid)
{
  //echo "\nIn function prev";  
  global $previous_month;
  global $previous_year; 	
	global $total_days;
	
	global $daily_dist_tmp;
  global $daily_halt_tmp;
  global $daily_travel_startime_tmp;
  global $daily_travel_endtime_tmp;	
  
  global $vserial_global;
  global $vname_global;
  global $vid_global;
  global $date_global;
  global $daily_dist_global;
  global $daily_halt_global;   
  global $total_distance_global; 
  global $total_halt_global;
  
  global $travel_flag;
  global $daily_travel_startime;
  global $daily_travel_endtime;                 
  	
  if($previous_month<10)
    $previous_month = "0".$previous_month;
        
  
  for($i=0;$i<sizeof($vserial);$i++)  
	{  	

    $tmp_serial = $i+1;
    echo "\nSERIAL=".$tmp_serial." ,TOTAL=".sizeof($vserial)."\n";

    $vserial_global[$i] = $vserial[$i];
    $vname_global[$i] = $vname[$i];
    $vid_global[$i] = $vid[$i];
          
    $total_dist_tmp = 0;
    $total_halt_tmp = 0;
        
    for($j=0;$j<$total_days;$j++)
    {
      $daily_dist_tmp =0;             //RESET VARIABLES FOR INDIVIDUAL DAY
      $daily_halt_tmp =0;     
      $travel_flag = 0;
      $daily_travel_startime_tmp = "";
      $daily_travel_endtime_tmp ="";	
            
      $day = $j+1;
      
      if($j<9)
        $date = $previous_year."-".$previous_month."-0".$day;
      else
        $date = $previous_year."-".$previous_month."-".$day;
      
      //$date1 = $date." 09:00:00";
      //$date2 = $date." 18:00:00";
      $date1 = $date." 00:00:00";
      $date2 = $date." 23:59:59";      
      
      echo "\nIMEI=".$vserial[$i]." ,Date1=".$date1." ,Date2=".$date2;
      get_monthly_surya_report_xml($vserial[$i], $vname[$i], $date1, $date2);      

      $date_global[$i][$j] = $date;
      $daily_dist_global[$i][$j] = $daily_dist_tmp;
      $daily_halt_global[$i][$j] = $daily_halt_tmp; 
      $total_dist_tmp += $daily_dist_tmp;
      $total_halt_tmp += $daily_halt_tmp;       
      
      //GET DAILY TRAVEL RECORDS
      $daily_travel_startime[$i][$j] = $daily_travel_startime_tmp;
      $daily_travel_endtime[$i][$j] = $daily_travel_endtime_tmp;
      
      echo ": daily_dist=".$daily_dist_global[$i][$j]." ,daily_halt=".$daily_halt_global[$i][$j]." ,travel_startime=".$daily_travel_startime[$i][$j]." ,travel_endtime=".$daily_travel_endtime[$i][$j];      
      //////////////////////////              
    } 
    
    $total_distance_global[$i] = $total_dist_tmp; 
    $total_halt_global[$i] = $total_halt_tmp; 
    
    echo "\nTotal_dist=".$total_distance_global[$i]." ,Total_halt=".$total_halt_global[$i];              
	}     
}

/*$k1 = 0;
$m_start=0;
$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2); */

//GET WOCKHARDT XML_DATA
function get_monthly_surya_report_xml($vehicle_serial, $vname, $startdate, $enddate)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	
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
	global $daily_halt_tmp;	

	//TRAVEL REPORT VARIABLE
	$datetime_S_travel="";
	$datetime_E="";
	$distance_travel="";
	$distance_travel="";
	$travel_time="";
	$substr_count = 0;	
	////////////////////////	

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
	$back_dir = "/var/www/html/itrack_vts";
	$abspath_current = "/mnt/volume3";
	$abspath_sorted = "/mnt/volume4";			
  
  
	for($i=0;$i<=($date_size-1);$i++)
	{
		//echo "\nIn Date Loop";
    //echo "<br>time=".$timetmp1;
    $timetmp2 = date("Y-m-d H:i:s");	
    $timetmp2 = strtotime($timetmp2);    
    $difftmp = ($timetmp2 - $timetmp1);
    //echo "<br>diff=".$difftmp;
    
    /*if($difftmp >= 200)
    {
      $breakflag=1;
      //echo "<center><font color=red>Data too large please select less duration/days</font></center>";
      break;
    }*/
  
    $daily_dist = 0; 
    $daily_halt = 0;
       
    $xml_current = $abspath_current."/current_data/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	    		
    //echo "<br>xml_current=".$xml_current;
    
    if (file_exists($xml_current))      
    {		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = $abspath_sorted."/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}
		
		//echo "<br>xml_file =".$xml_file;	    	
    if (file_exists($xml_file)) 
		{			
		  //echo "\nfile_exists xml_file";
      $t=time();
      $xml_original_tmp = $back_dir."/xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
      //echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
									      
      if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
        copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "<br>TWO<br>";
				$xml_unsorted = $back_dir."/xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
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
      
      $f=0;
      
      if (file_exists($xml_original_tmp)) 
      {      
		//SWITCH MASTER VARIABLES
		set_master_variable($userdates[$i]);        
		//echo "\nFileExists";
        //$daily_dist =0;
        // $firstdata_flag =0;                
		//TRAVEL VARIABLES
		$start_time_flag = 0;
		$distance_total_travel = 0;
		$distance_threshold_travel = 0.200;
		$distance_error_travel = 0.100;
		$start_point_display =0;
		$haltFlagTravel==True;
		$distance_travel=0;     
        //////////////////                   
        
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
				//preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
				//echo "<br>str3tmp[0]=".$str3tmp[0];
				$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
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
               
			  $vserial = $vehicle_serial;			   
              //echo "<br>first=".$firstdata_flag;                                        
              if($firstdata_flag==0)
              {
                //echo "<br>FirstData";
                $firstdata_flag = 1;
                $halt_flag = 0;
                $lat1_dist = $lat;
                $lng1_dist = $lng;
                
                $lat1_halt = $lat;
                $lng1_halt = $lng;
                $time1_halt = $datetime;
                
                $interval = $user_interval*60;		//30 mins interval
                $last_time1 = $datetime;
                
                //DEFINE TRAVEL VARIABLES
				$haltFlagTravel=True;
				$distance_travel=0;
				$lat_S_travel = $lat;
				$lng_S_travel = $lng;
				$datetime_S_travel = $datetime;
				$datetime_travel_start = $datetime_S_travel;
				$lat_travel_start = $lat_S_travel;
				$lng_travel_start = $lng_S_travel;
				$start_point_display_travel =0;         
                ////////////////////////                                                                        													                 	
            	}           	
            	//echo "<br>k2=".$k2."<br>";              	
              else
              {                           
  				//********* HALT LOGIC BEGINS
				$lat2_halt = $lat;
				$lng2_halt = $lng;                
				$time2_halt = $datetime;

				calculate_distance($lat1_halt, $lat2_halt, $lng1_halt, $lng2_halt, &$distance_halt);
              	
          			//if( ($distance > 0.200) || ($f== $total_lines-2) )          			
                //echo "\nlat1_halt=".$lat1_halt.", lat2_halt=".$lat2_halt.", lng1_halt=".$lng1_halt.", lng2_halt=".$lng2_halt.", distance_halt=".$distance_halt;

                if( ($distance_halt > 0.150) || ($f == $total_lines-10) )
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
                        /*$total_halt_vehicle = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" lat=\"".$lat_ref."\" lng=\"".$lng_ref."\" arr_time=\"".$arrivale_time."\" dep_time=\"".$depature_time."\" duration=\"".$halt_dur."\"/>";						          						
                        //echo "<br>total halt vehicle=".$total_halt_vehicle;
                        $linetowrite = $total_halt_vehicle; // for distance       // ADD DISTANCE
                        fwrite($fh, $linetowrite); */ 
                        
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
                
                //********* DISTANCE LOGIC BEGINS
					$time2 = $datetime;											
					$date_secs2 = strtotime($time2);	

					/*$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
					$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);*/
					
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
                                
                //****** TRAVEL LOGIC STARTS
                $lat_E_travel = $lat;
				$lng_E_travel = $lng; 
				$datetime_E_travel = $datetime;
				calculate_distance($lat_S_travel, $lat_E_travel, $lng_S_travel, $lng_E_travel, &$distance_incriment_travel);								
				if($distance_incriment_travel > $distance_error_travel)
				{ 
					//echo "ONE";
					if($haltFlagTravel==True)
					{
						$datetime_travel_start = $datetime_E_travel;
						$haltFlagTravel = False;
					}
					$distance_total_travel += $distance_incriment_travel;
					$distance_travel += $distance_incriment_travel;
					$lat_S_travel = $lat_E_travel;
					$lng_S_travel = $lng_E_travel;
					$datetime_S_travel = $datetime_E_travel;
					$start_point_display_travel =1;
				}           				
				$datetime_diff = strtotime($datetime_E_travel) - strtotime($datetime_S_travel);         				
				if(($distance_total_travel>$distance_error_travel) && ($datetime_diff > $datetime_threshold) && ($haltFlagTravel==False))
				{
					//echo "TWO";
					/*if($f>15000)
					break;*/						
				   //echo "\nIN TRAVEL";
					$datetime_travel_end = $datetime_E_travel;
					$lat_travel_end = $lat_S_travel;
					$lng_travel_end = $lng_S_travel; 
					newTravel($datetime_travel_start, $datetime_travel_end, $distance_travel, $lat_travel_start, $lng_travel_start, $lat_travel_end, $lng_travel_end, $distance_travel, $substr_count);
					$substr_count =1;          			    
					$datetime_travel_start = $datetime_E_travel;
					$lat_travel_start = $lat_E_travel;
					$lng_travel_start = $lng_E_travel;           				    

					$distance_travel = 0;
					// exit;
					$datetime_S_travel = $datetime_E_travel;
					$distance_total_travel = 0;
					$distance_incrimenttotal = 0;
					$haltFlagTravel = True;          					
								}                
					//****** TRAVEL LOGIC CLOSED	                					                               
				}
  					} // $xml_date_current >= $startdate closed
  				}   // if xml_date!null closed
  			 //$j++;
  			 $f++;
        }   // while closed
      } // if original_tmp closed         
			
		//WRITE DAILY DISTANCE DATA
		/*$daily_distance_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" date=\"".$userdates[$i]."\" daily_dist=\"".$daily_dist."\"/>";						          						
		//echo "<br><br>".$daily_distance_data;
		$linetowrite = $daily_distance_data; // for distance       // ADD DISTANCE
		fwrite($fh, $linetowrite); */ 		

		$daily_dist_tmp = $daily_dist;
		$daily_halt_tmp = $daily_halt;

		fclose($xml);            
		unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 
	//echo "Test1";
	//fclose($fh);
}


/////////////////////////////////////////////////////////////////////////////
function newTravel($datetime_S_travel, $datetime_E_travel, $distance, $lat_travel_start, $lng_travel_start, $lat_travel_end, $lng_travel_end, $distance_travel, $substr_count)
{
	$sno =1;
	//global $csv_string_travel;
	global $total_travel_dist;
	global $total_travel_time;
	global $travel_flag;
	global $daily_travel_startime_tmp;
  global $daily_travel_endtime_tmp;

	$travel_dur =  strtotime($datetime_E_travel) - strtotime($datetime_S);                                                    
	$hms = secondsToTime($travel_dur);
	$travel_time = $hms[h].":".$hms[m].":".$hms[s];
	$distance_travel = round($distance_travel,2);
	//echo "\t\t\t\tTravel : " . $datetime_S . " to " . $datetime_E_travel . "( " . $distance . " )<br>";        
	/*
  $alt1 = "-";
	$alt2 ="-";
	$landmark="";
	get_landmark($lat_travel_start,$lng_travel_start,&$landmark);    // CALL LANDMARK FUNCTION
	$place1 = $landmark;
	if($place1=="")
	{
		get_location($lat_travel_start,$lng_travel_start,$alt1,&$place1,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
	}
	//location2								 
	$landmark="";
	get_landmark($lat_travel_end,$lng_travel_end,&$landmark);      // CALL LANDMARK FUNCTION
	$place2 = $landmark;
	if($place2=="")
	{
		get_location($lat_travel_end,$lng_travel_end,$alt2,&$place2,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
	}        		
	$place1 = str_replace(',',':',$place1);
	$place2 = str_replace(',',':',$place2);  
  */
	
	if($travel_flag ==0)
	{
    $daily_travel_startime_tmp = $datetime_S_travel;
    $travel_flag =1;
  }
  else
  {	
	 $daily_travel_endtime_tmp = $datetime_E_travel;
	}
	
  /*
  if($substr_count == 0)
	{	
		$csv_string_travel = $csv_string_travel.$sno.','.$datetime_S_travel.','.$datetime_E_travel.','.$place1.','.$place2.','.$distance_travel.','.$travel_time;
	}
	else
	{
		$csv_string_travel = $csv_string_travel."#".$sno.','.$datetime_S_travel.','.$datetime_E_travel.','.$place1.','.$place2.','.$distance_travel.','.$travel_time;
	}
	$total_travel_dist = $total_travel_dist + $distance_travel;
	$total_travel_time = $total_travel_time + $travel_dur;
  $sno++;    
  */
} 

////////////////////////////////////
  function get_xml_data($reg, $line)
  {
  	$data = "";
  	if(preg_match($reg, $line, $data_match))
  	{
  		$data = explode_i('"', $data_match[0], 1);
  	}
  	return $data;
  }
  
  function explode_i($reg, $str, $i)
  {
  	$tmp = explode($reg, $str);
  	return $tmp[$i];
  }
  
  //date_default_timezone_set("Asia/Calcutta");
  $datetime_sent = date("Y-m-d H:i:s");
         
  /////********* CREATE EXCEL FILE *******************///////
  $rno = rand();
  $filename_title = 'MONTHLY_SURYA_PHARMA_VTS_REPORT_'.$previous_month.'_'.$previous_year.'_'.$rno;
  echo "\nfilename=".$filename_title;
  $file_path = "/var/www/html/vts/beta/src/php/download/".$filename_title;
  $fname = tempnam_sfx($file_path, ".xls");

  ////********** CREATE EXCEL WORKBOOK  ****************//////
  //echo "\nfname=".$fname;  
  $workbook =& new writeexcel_workbook($fname);                     //******* ADD WORKBOOK
  
  $border1 =& $workbook->addformat();                               //******* DEFINE BORDER STYLE
  $border1->set_color('white');
  $border1->set_bold();
  $border1->set_size(9);
  $border1->set_pattern(0x1);
  $border1->set_fg_color('green');
  $border1->set_border_color('yellow');
  
  //echo "\nafter border1";    
  /*$text_format =& $workbook->addformat(array(                      //******* DEFINE TEXT STYLE
                  bold    => 1,
                  //italic  => 1,                       
                  color   => 'blue',
                  size    => 10,
                  //font    => 'Comic Sans MS'
              ));  */
              
  $text_format =& $workbook->addformat();
  $text_format->set_color('blue');
  $text_format->set_bold();
  $text_format->set_align('left');
  $text_format->set_size(10);              
                                                                                
  $blank_format = & $workbook->addformat();                       //******* DEFINE BLANK STYLE
  $blank_format->set_color('white');
  $blank_format->set_bold();
  $blank_format->set_size(12);
  $blank_format->set_merge();
  /////**********************
  //echo "\nafter blank format";        
 
  $imei_msg = "";
  $vids ="";
     
  for($j=0;$j<sizeof($vserial_global);$j++)
  {        
    echo "\nSERIAL=".$j." ,TOTAL=".sizeof($vserial_global)."\n";      
    echo "\nVehicle Matched";
    include('create_excel_sheet_surya.php');
    
    /*if($imei_msg=="")
    {
      $imei_msg = $imei_msg.$vserial_global[$j];
      $vids = $vids.$vid_global[$j];
    }
    else
    {
      $imei_msg = $imei_msg.",".$vserial_global[$j];
      $vids = $vids.",".$vid_global[$j];
    } */
  }           
       
  echo "\nimei_msg=".$imei_msg." ,vids=".$vids;
  $workbook->close(); //CLOSE WORKBOOK
  echo "\nWORKBOOK CLOSED";
  /////////////////////////////////////////////////////        
 
  //########### SEND MAIL ##############//
  $to = 'rizwan@iembsys.com'; 
  $subject = "MONTHLY_SURYA_PHARMA_VTS_REPORT_(".$previous_month."/".$previous_year.")";
  $message = "MONTHLY_SURYA_PHARMA_VTS_REPORT_(".$previous_month."/".$previous_year.")";
  $random_hash = md5(date('r', time()));  
  $headers = "From: support@iembsys.co.in\r\n";
  //$headers .= "Reply-To: taseen@iembsys.com\r\n"; 
  //$headers .= "Cc: rizwan@iembsys.com";
  $headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com";
  //$headers .= "Cc: rizwan@iembsys.com";
  $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
  $filename_title = $filename_title.".xls";
  $file_path = $file_path.".xls";
  
  echo "\nFILE PATH=".$file_path;
  
  include_once("send_mail_api.php");
  //####################################//

//***CLOSED-WRITE MONTHLY SURYA REPORT 

?>
					
