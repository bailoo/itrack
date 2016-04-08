<?php
set_time_limit(600);

$abspath = "/var/www/html/vts/beta/src/php";

include_once('alert_util_php_mysql_connectivity.php');
include_once($abspath."/get_all_dates_between.php");
include_once($abspath."/calculate_distance.php");
include_once($abspath."/util.hr_min_sec.php");

$alert_vid_dist = array();
$alert_imei_dist = array();
$alert_vname_dist = array();

$alert_vid_halt = array();
$alert_imei_halt = array();
$alert_vname_halt = array();
$alert_distance = array();
$alert_halt_time = array();
$alert_total_halt = array();

//GET AND SET LAST PROCESSING TIME
$current_date = date("Y-m-d");
$current_datetime = date("Y-m-d H:i:s");

$date = date('Y-m-d');
$previous_date = date('Y-m-d', strtotime($date .' -1 day'));


$query = "SELECT DISTINCT device_imei_no FROM vehicle_assignment WHERE vehicle_id IN(SELECT vehicle_id FROM schedule_assignment WHERE ".
					"'$previous_date' >= date_from AND '$previous_date' <= date_to AND status=1) AND status=1";	        //GET DISTINCT IMEI

//echo $query."\n";
$result = mysql_query($query);
	
while($row = mysql_fetch_object($result))
{
  $vserial[] = $row->device_imei_no;
}

for($i=0;$i<sizeof($vserial);$i++)
{  	
  $query1 = "SELECT vehicle_name FROM vehicle WHERE ".
  " vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
  "WHERE device_imei_no='$vserial[$i]' AND status=1) AND status=1";
  //echo $query1;
  //echo "<br>DB=".$DbConnection;
  $result1 = mysql_query($query1,$DbConnection);
  $row1 = mysql_fetch_object($result1);
  $vname = $row->vehicle_name;  
 
	//$query2 = "select * from schedule_assignment where location_id!='' AND vehicle_id=1085 and status=1";  
  $query2 = "SELECT * FROM schedule_assignment WHERE '$previous_date' >= date_from AND '$previous_date' <= date_to AND vehicle_id IN ( SELECT vehicle_id FROM vehicle_assignment WHERE device_imei_no = '$vserial[$i]' AND status =1)";
  $result2 = mysql_query($query2);
  //echo $query2."\n";
	while($row2 = mysql_fetch_object($result2))
	{
    //echo "\nCURRENT DATE MATCHED";
    $vehicle_id = $row2->vehicle_id;
    $location_id = $row2->location_id;
    $base_station_id = $row2->base_station_id;
    $by_day = $row2->by_day;
    $day = $row2->day;
    $min_operation_time = $row2->min_operation_time;
    $max_operation_time = $row2->max_operation_time;
    $min_halt_time = $row2->min_halt_time;
    $max_halt_time = $row2->max_halt_time;
    $min_distance_travelled = $row2->min_distance_travelled;
    $max_distance_travelled = $row2->max_distance_travelled;
    
    $valid_day = false;
    
    if($by_day ==1)
    {
      $day_db = explode(",",$day);
      
      $timestmp = strtotime($current_date);
      $weekday = date("w",$timestmp);     //0 =SUN, 6=SAT
      
      for($z=0;$z<sizeof($day_db);$z++)   //CHECKS EVERYTIME FOR ONE RECORD AND BREAKS- INNER LOOP
      {
        //echo "\nWEEKDAY=".$weekday." ,day_db=".$day_db[$z];
        if($weekday == $day_db[$z])
        {                            
          //echo "\nDAY SPECIFIED";                  
          $valid_day = true;
          break;
        }
      }           
    }
    else
    {
      $valid_day = true;
    }
    
    if($valid_day)
    {
      echo "\nVALID DATE FOUND\n";
      $query3 = "SELECT geo_point FROM schedule_location WHERE location_id IN($location_id)";
      //echo "Q3=".$query3."\n";
      $result3 = @mysql_query($query3,$DbConnection);
      
      while($row3 = @mysql_fetch_object($result3))
      {
        $geo_point[] = $row3->geo_point;
      }            
      
      $date1 = $previous_date." 00:00:00";
      $date2 = $previous_date." 23:59:59";

      get_daily_report($vehicle_id, $vserial[$i], $vname, $date1, $date2, $min_operation_time ,$max_operation_time ,$min_halt_time, $max_halt_time, $min_distance_travelled, $max_distance_travelled, $geo_point);
    }  // IF VALID DAY CLOSED
  } //WHILE CLOSED    
} // FOR CLOSED

//SEND EMAIL
send_email(); 


function get_daily_report($vid, $imei, $vname, $startdate, $enddate, $min_operation_time ,$max_operation_time ,$min_halt_time, $max_halt_time, $min_distance_travelled, $max_distance_travelled, $geo_point)
{
  global $alert_vid_dist;
  global $alert_imei_dist;
  global $alert_vname_dist;
  global $alert_vid_halt;
  global $alert_imei_halt;
  global $alert_vname_halt;
  global $alert_distance;
  global $alert_halt_time;
  global $alert_total_halt;
  
  for($k=0;$k<sizeof($geo_point);$k++)
  {    
    $firstdata_flag_halt[$k] = 0;
    $halt_flag[$k] = 0;
    $total_halt_time[$k] = 0;
    $total_nof_halt[$k] = 0;
    $valid_location[$k] = 0;
  }
                
  //$back_dir = "../../../..";
  $abspath = "/var/www/html/itrack_vts";
  $fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;
	$linetowrite="";
	$firstdata_flag_dist =0;
	$firstdata_flag_halt =0;
  	  
  $date_1 = explode(" ",$startdate);
	$date_2 = explode(" ",$enddate);

	$datefrom = $date_1[0];
	$dateto = $date_2[0];
	$timefrom = $date_1[1];
	$timeto = $date_2[1];

	get_All_Dates($datefrom, $dateto, &$userdates);

	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);
  $interval = 300;   //5 mins
  $flag_file_found =0; 									   
  
  for($i=0;$i<=($date_size-1);$i++)
	{   
    //echo " debug2:";
    $flag_file_found =0; 	
    //#####DEFINE VARIABLES  
    $daily_dist = 0;
    $total_dist = 0;
    $ophr_dist = 0;
    $non_ophr_dist = 0;
    $total_nof_halt = 0;
    $total_halt_time = 0;
    $avg_halt_time = 0;
    //#############      
        
    $xml_current = $abspath."/xml_vts/xml_data/".$userdates[$i]."/".$imei.".xml";	    		
    //echo "\nxml_path=".$xml_current;
    
    if (file_exists($xml_current))      
    {		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = $abspath."/sorted_xml_data/".$userdates[$i]."/".$imei.".xml";
			$CurrentFile = 0;
		}
		
		//echo "<br>xml_file =".$xml_file;	    	
    if (file_exists($xml_file)) 
		{			
		  //echo " debug3:";
      //echo "\nSorted xml file exists";
      $t=time();
      $xml_original_tmp = $abspath."/xml_tmp/original_xml/tmp_".$imei."_".$t."_".$i.".xml";
									      
      if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
        copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "<br>TWO<br>";
				$xml_unsorted = $abspath."/xml_tmp/unsorted_xml/tmp_".$imei."_".$t."_".$i."_unsorted.xml";
				        
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
      
      if (file_exists($xml_original_tmp)) 
      {      
        //echo " debug4:";
        $flag_file_found =1; 	
        //echo "\nOriginal file exists";
        $daily_dist =0;
        // $firstdata_flag =0;
                
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
            }
          }
          
          //if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
          if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
  				{
  					$status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
  					$datetime_tmp1 = explode("=",$datetime_tmp[0]);
            $datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
  					$xml_date = $datetime;
  				}				
          //echo "Final0=".$xml_date." datavalid=".$DataValid;
          
          if($xml_date!=null)
  				{				  
            //echo "<br>".$xml_date.",".$startdate.",".$enddate.",".$DataValid; 					
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
                           
							$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
              $vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);
                              
              $lat_tmp1 = explode("=",$lat_tmp[0]);
              $lat = preg_replace('/"/', '', $lat_tmp1[1]);
              
              $lng_tmp1 = explode("=",$lng_tmp[0]);
              $lng = preg_replace('/"/', '', $lng_tmp1[1]);                               
              //echo "<br>first=".$firstdata_flag;                                        
              
              //########### DISTANCE SECTION ############//
              if($firstdata_flag_dist==0)
              {
                //echo "<br>FirstData";
                $firstdata_flag_dist = 1;
                $lat1_dist = $lat;
                $lng1_dist = $lng;
                $last_time1_dist = $datetime;                                                        													                 	
            	}           	
              else
              {                           
  							$time2_dist = $datetime;											
  							$date_secs2_dist = strtotime($time2_dist);	
                                  
                $lat2_dist = $lat;
                $lng2_dist = $lng;  
                
  							$distance = 0;
                calculate_distance($lat1_dist, $lat2_dist, $lng1_dist, $lng2_dist, &$distance);
  							//echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance;
  							
                $tmp_time_diff1 = (strtotime($datetime) - strtotime($last_time1_dist)) / 3600;
        				if($tmp_time_diff1>0)
        				{
        					$tmp_speed = $distance / $tmp_time_diff1;
        					$last_time1_dist = $datetime;
        				}
        				$tmp_time_diff = (strtotime($datetime) - strtotime($last_time_dist)) / 3600;
                                
                //if($tmp_speed <3000 && $distance>0.1)
                if($tmp_speed<500 && $distance>0.1 && $tmp_time_diff>0)
      					{		              
                  //echo "\nIn Distance";
                  $daily_dist= (float) ($daily_dist + $distance);	
                  $daily_dist = round($daily_dist,2);
                  
                  //echo "\nDIST::datetime=".$datetime." ,op_date1=".$op_date1." ,op_date2=".$op_date2." \ndistance=".$distance;
                  if( ($datetime >=$op_date1) && ($datetime <=$op_date2) )
                  {
                    $ophr_dist = (float) ($ophr_dist + $distance);
                    $ophr_dist = round($ophr_dist,2); 
                  }
                  echo "\n\nDailyDist=".$daily_dist." ,OpDist=".$ophr_dist;							                          				                                       
     							///////////////////////////////////////////////////////////																							
                  $lat1_dist = $lat2_dist;
                  $lng1_dist = $lng2_dist;
                  
                  $last_time_dist = $datetime;			
                }							                               
              }
              //############# DISTANCE SECTION CLOSED #############//
              
              
              //############# HALT SECTION #######################//
              for($k=0;$k<sizeof($geo_point);$k++)
              {
                $coord = explode(',',$geo_point[$k]);
                $lat_g = trim($coord[0]);
                $lng_g = trim($coord[1]);  
                
                if($firstdata_flag_halt[$k]==0)
                {
                  //echo "<br>FirstData";
                  $halt_flag[$k] = 0;
                  $firstdata_flag_halt[$k] = 1;
    
                  $lat_ref[$k] = $lat;
                  $lng_ref[$k] = $lng;       
                  $datetime_ref[$k] = $datetime;                 	
                	$date_secs1_halt[$k] = strtotime($datetime_ref[$k]);
                	$date_secs1_halt[$k] = (double)($date_secs1_halt[$k] + $interval);                 
                  //echo "<br>lat_tmp=".$lat_tmp[0]." ,lat_tmp1=".$lat_tmp1[1];             	
              	}           	             	
                else
                {           
                  //echo "<br>Next";               
                  $lat_cr[$k] = $lat;
                  $lng_cr[$k] = $lng;
                  $datetime_cr[$k] = $datetime;										
              		$date_secs2_halt[$k] = strtotime($datetime_cr[$k]);
                		
              		//echo "<br>str=".$lat_ref.", ".$lat_cr.", ".$lng_ref." ,".$lng_cr.", ". $datetime_cr;
              		$distance = 0;
                  //calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);                
                  if($lat_g!="" && $lng_g!="")
                  {
                    //echo "\nDIST::datetime=".$datetime." ,op_date1=".$op_date1." ,op_date2=".$op_date2." \ndistance=".$distance;
                    calculate_distance($lat_ref[$k], $lat_g, $lng_ref[$k], $lng_g, &$distance);
                  }               
                	
            			//if( ($distance > 0.200) || ($f== $total_lines-2) )
            			if( ($distance > 0.0100) || ($f == $total_lines-2) )
            			{
            			  //echo "\n\nHALT1::lat_ref=".$lat_ref." ,lng_ref=".$lng_ref." ,lat_g=".$lat_g." ,lng_g=".$lng_g." distance=".$distance;
            				//echo "<br>In dist ".$distance." lat_ref ".$lat_ref." lng_ref ".$lng_ref." lat_cr ".$lat_cr." lng_cr ".$lng_cr."<br>";
            				if ($halt_flag[$k] == 1)
            				{				
              					//echo "\n\nHALT::lat_ref=".$lat_ref." ,lng_ref=".$lng_ref." ,lat_g=".$lat_g." ,lng_g=".$lng_g;
                        echo "\nIn Halt2";
              					$starttime = strtotime($datetime_ref[$k]);              				 
              					$stoptime = strtotime($datetime_cr[$k]);
              					echo "<br>StartTime-".$starttime." ,EndTime-".$stoptime;            					
              					$halt_dur =  ($stoptime - $starttime);
              					
                        /*$halt_dur =  ($stoptime - $starttime)/3600;
              				
              					$halt_duration = round($halt_dur,2);										
              					$total_min = $halt_duration * 60;            
              					$total_min1 = $total_min;            					
              					//echo "<br>toatal_min=".$total_min1."user-interval=".$user_interval;
              
              					$hr = (int)($total_min / 60);
              					$minutes = $total_min % 60;										           
              					$hrs_min = $hr.".".$minutes; */
              					              					
              					//if( ($total_min1 >= $user_interval) || ($f== $total_lines-2))
              					echo "\nhalt_dur=".$halt_dur." ,interval=".$interval;                      
                        if( ($halt_dur >= $interval) || ($f == $total_lines-2))
              					{
                            $valid_location[$k] = 1;
                                                    
                      			$date_secs1[$k] = strtotime($datetime_cr[$k]);
                      			$date_secs1[$k] = (double)($date_secs1[$k] + $interval);
                            
                            $total_halt_time[$k] = $total_halt_time[$k] + $halt_dur;
                            $total_nof_halt[$k]++;
                                                                                 
                        }		// IF TOTAL MIN										
              			}   //IF HALT FLAG
              			
            				$lat_ref[$k] = $lat_cr[$k];
            				$lng_ref[$k] = $lng_cr[$k];
            				$datetime_ref[$k] = $datetime_cr[$k];
            				
            				$halt_flag[$k] = 0;
              		}
            			else
            			{            			
                      //echo "<br>normal flag set";
                      $halt_flag[$k] = 1;
            			}					                              
                }  //ELSE CLOSED
              } //FOR GEO_POINT CLOSED
              //############# HALT SECTION CLOSED ################// 
             //echo " debug6:";
  					} // $xml_date_current >= $startdate closed
  					//echo " debug7:";
  				}   // if xml_date!null closed 				
  			 //$j++;
        }   // while closed
      } // if original_tmp closed   
      //echo " debug8:";      			
		} // if (file_exists closed
		//echo " debug9:";
	}  // for closed 
	
  //echo " debug10:";
  //WRITE DAILY DISTANCE DATA
  $total_dist = $daily_dist;
  /*$non_ophr_dist = $total_dist - $ophr_dist;						          						
  if($total_nof_halt>0)
  {
    $avg_halt_time = $total_halt_time / $total_nof_halt;
  } */
    
  if($flag_file_found)
  { 	    
    fclose($xml); 
    unlink($xml_original_tmp);
  }    
     
  if( ($total_dist < $min_distance_travelled) || ($total_dist > $max_distance_travelled))    //IF DISTANCE ALERT OCCUR
  {
    $alert_vid_dist[] = $vid;
    $alert_imei_dist[] = $imei;
    $alert_vname_dist[] = $vname;    
    $alert_distance[] = $total_dist;
    echo "\nvid=".$vid." ,alert_imei_dist=".$imei." ,vname=".$vname." ,total_dist=".$total_dist;
  }
  
  if( sizeof($total_halt_time)>0 )               //IF HALT ALERT OCCUR
  {
    $min_halt_time_a = explode(',',$min_halt_time);
    $max_halt_time_a = explode(',',$max_halt_time);
    
    for($k=0;$k<sizeof($geo_point);$k++)
    {
      if($valid_location[$k] == 1)
      {
        $min_halt_time_a_tmp = $min_halt_time_a[$k].":0";
        $max_halt_time_a_tmp = $max_halt_time_a[$k].":0";
        
        $min_halt_sec = get_seconds($min_halt_time_a_tmp);
        $max_halt_sec = get_seconds($max_halt_time_a_tmp);
        
        if( ($total_halt_time[$k] < $min_halt_sec) || ($total_halt_time[$k] > $max_halt_sec) )
        {
          $alert_vid_halt[] = $vid;
          $alert_imei_halt[] = $imei;
          $alert_vname_halt[] = $vname;             
          $alert_halt_time[] = $total_halt_time[$k];
          $alert_total_halt[] = $total_nof_halt[$k];                            
        }
      }
    }
  }                
    //echo "\nDailyData INSIDE FUNCTION=".$daily_data;                    
}  //FUNCTION CLOSED

function get_seconds($time)
{
  $array = explode(':', $time);
  $pt1[] = $array[0];
  $pt2 = explode("'", $array[1]);
  $time = array_merge($pt1, $pt2);
  $sec = $time[2];
  $min = $time[1]*60;
  $hour = $time[0]*60*60;
  $seconds = $sec+$min+$hour;
  return $seconds;
}


function send_email()
{
  global $previous_date;
  
  global $alert_vid_dist;
  global $alert_imei_dist;
  global $alert_vname_dist;
  global $alert_distance;
  
  global $alert_vid_halt;
  global $alert_imei_halt;
  global $alert_vname_halt;
  global $alert_halt_time;
  global $alert_total_halt;
        
  //####### SEND MAIL ALERT DISTANCE ######### //////  
  echo "\nIMEI SIZE =".sizeof($alert_imei_dist);
  
  for($i=0;$i<sizeof($alert_imei_dist);$i++)   
  {       
    $alert_msg_dist[$i] = $alert_vname_dist[$i]." moved ".$alert_distance[$i]." km\n"; 
    
    $query = "SELECT DISTINCT person_email FROM escalation WHERE escalation_id IN (SELECT escalation_id FROM alert_assignment WHERE ".
              "mail_status=1 AND vehicle_id='$alert_vid_dist[$i]' AND status=1) AND approved_status=1";
    $result = mysql_query($query);
    $row = mysql_fetch_object($result);
    $person_email[$i] = $row->person_email;     
  }

  $person_email_unique = array_unique($person_email);  // MAKE UNIQUE PERSON ARRAY
  
  while (list($key, $val) = each($person_email_unique))    //LOOP THROUGH UNIQUE PERSON ARRAY
  {
    //echo "$key => $val\n";
    $alert_string = "";
    
    for($i=0;$i<sizeof($alert_imei_dist);$i++)
    {
      if($val == $person_email[$i])
      {
        $alert_string = $alert_string.$alert_msg_dist[$i]."\n";
      }
    } 
    //$alert_string_final = substr($alert_string_final, 0, -1);
    
    echo "\nFinal MSG($val)=".$alert_string;
        
    //$alert_string = "vehicle test did not move at 2012-07-21 14:30:00";
    if( (sizeof($alert_imei_dist)>0) && ($alert_string!=""))
    {
      $to = $val;
      //$to = 'sanchan@wockhardtfoundation.org';   
      //$to = 'vaibhavrspl@gmail.com';
      //$to = 'rizwan@iembsys.com'; 
      //define the subject of the email 
      $subject = 'ALERT REPORT-'.$previous_date;
      $message = $alert_string; 
      //create a boundary string. It must be unique 
      //so we use the MD5 algorithm to generate a random hash 
      //$random_hash = md5(date('r', time())); 
      //define the headers we want passed. Note that they are separated with \r\n 
      $headers = "From: support@iembsys.co.in\r\n";
      //$headers .= "Reply-To: taseen@iembsys.com\r\n"; 
      $headers .= "Cc: rizwan@iembsys.com, jyoti.jaiswal@iembsys.com";
      //add boundary string and mime type specification 
      //$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";                 
      mail($to, $subject, $message, $headers);
    }
  } //WHILE CLOSED
  
  ///####### ALERT DISTANCE CLOSED ###########/////  
    
  
  //####### SEND MAIL ALERT IMEI HALT ######### //////                                                     
  for($i=0;$i<sizeof($alert_imei_halt);$i++)
  {   
    $hms = secondsToTime($alert_halt_time[$k]);
    $alert_halt_time_formated = $hms[h].":".$hms[m].":".$hms[s];

    $alert_msg_halt[$i] = $alert_vname_halt[$i]." halt time is ".$alert_halt_time_formated."\n";
    
    $query = "SELECT DISTINCT person_email FROM escalation WHERE escalation_id IN (SELECT escalation_id FROM alert_assignment WHERE ".
              "mail_status=1 AND vehicle_id='$alert_vid_halt[$i]' AND status=1) AND approved_status=1";
    $result = mysql_query($query);
    $row = mysql_fetch_object($result);
    $person_email[$i] = $row->person_email;          
  }  
  
  $person_email_unique = array_unique($person_email);  // MAKE UNIQUE PERSON ARRAY
  
  while (list($key, $val) = each($person_email_unique))    //LOOP THROUGH UNIQUE PERSON ARRAY
  {
    //echo "$key => $val\n";
    $alert_string = "";
    
    for($i=0;$i<sizeof($alert_imei);$i++)
    {
      if($val == $person_email[$i])
      {
        $alert_string = $alert_string.$alert_msg_halt[$i].",";
      }
    } 
    $alert_string = substr($alert_string, 0, -1);
    
    echo "\nFinal MSG($val)=".$alert_string;
        
    //$alert_string = "vehicle test did not move at 2012-07-21 14:30:00";
    if( (sizeof($alert_imei_halt)>0) && ($alert_string!=""))
    {
      $to = $val;
      //$to = 'sanchan@wockhardtfoundation.org';   
      //$to = 'rizwan@iembsys.com'; 
      //define the subject of the email 
      $subject = 'ALERT REPORT-'.$previous_date;
      $message = $alert_string; 
      //create a boundary string. It must be unique 
      //so we use the MD5 algorithm to generate a random hash 
      //$random_hash = md5(date('r', time())); 
      //define the headers we want passed. Note that they are separated with \r\n 
      $headers = "From: support@iembsys.co.in\r\n";
      //$headers .= "Reply-To: taseen@iembsys.com\r\n"; 
      $headers .= "Cc: rizwan@iembsys.com, jyoti.jaiswal@iembsys.com";
      //add boundary string and mime type specification 
      //$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";                 
      mail($to, $subject, $message, $headers);
    } 
  } //WHILE CLOSED
  
}
                 							
?>
						