<?php
//include_once('util_session_variable.php');
//include_once('util_php_mysql_connectivity.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

set_time_limit(300);

include_once("../get_all_dates_between.php");
include_once("../sort_xml.php");
include_once("../calculate_distance.php");
include_once("../read_filtered_xml.php");
include_once("../get_location.php");
include_once("../report_title.php");
include_once("../user_type_setting.php");
include_once("../select_landmark_report.php");
include_once("../util.hr_min_sec.php");

/*
include_once("get_all_dates_between.php");
include_once("sort_xml.php");
include_once("calculate_distance.php");
include_once("read_filtered_xml.php");
include_once("get_location.php");
include_once("report_title.php");
include_once("user_type_setting.php");
include_once("select_landmark_report.php");
include_once("util.hr_min_sec.php");*/


$csv_string = "";
$overall_dist = 0.0;
$csv_string_arr = array();
$sno = 0;

$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM ".
            "alert,vehicle,vehicle_assignment,alert_assignment WHERE ".
            "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND ".         
            "vehicle_assignment.vehicle_id = alert_assignment.vehicle_id AND ".
            "vehicle_assignment.vehicle_id = alert_assignment.vehicle_id AND ".            
            "alert.alert_id = alert_assignment.alert_id AND ".
            "alert.alert_name = 'report' AND ".                         
            "vehicle_assignment.status=1 AND ".
            "alert_assignment.status=1";
//echo $query_assignment."\n";
$result_assignment = mysql_query($query_assignment,$DbConnection);

while($row_assignment = mysql_fetch_object($result_assignment))
{
   $vehicle_id_a = $row_assignment->vehicle_id;
   $vehicle_name[] = $row_assignment->vehicle_name;
   
   $query_imei = "SELECT device_imei_no FROM vehicle_assignment WHERE vehicle_id ='$vehicle_id_a' AND status=1";
   $result_imei = mysql_query($query_imei, $DbConnection);
   $row_imei = mysql_fetch_object($result_imei);
   $device_imei_no[] = $row_imei->device_imei_no;
   $vid[] = $vehicle_id_a;  
}

/*$device_str = $_POST['vehicleserial'];
//echo "<br>devicestr=".$device_str;
$vserial = explode(':',$device_str);*/
$vsize=count($device_imei_no); 

$current_date = date("Y-m-d");
$startdate = $current_date." 00:00:00";
$enddate = date("Y-m-d H:i:s");

$date1 = $startdate;
$date2 = $enddate;

$user_interval = "30";

///////////////////////////////////////////////////////////////////////////////
if($vsize>0)
{
  write_travel_report_xml($device_imei_no, $vid, $vehicle_name, $user_interval);
}

function write_travel_report_xml($vserial, $vid, $vname, $threshold)
{
  global $DbConnection;
  global $startdate;
  global $enddate;
  global $sno;
  global $overall_dist;

  $maxPoints = 1000;
	$file_exist = 0;
	
  global $csv_string;
  global $csv_string_arr; 	


  for($i=0;$i<sizeof($vserial);$i++)
	{  	        
    $csv_string = "";
    $query1 = "SELECT vehicle_name FROM vehicle WHERE ".
    "vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$vserial[$i]' AND status=1) AND status=1";
    //echo "<br>".$query1;
    //echo "<br>DB=".$DbConnection;
    $result = mysql_query($query1,$DbConnection);
    $row = mysql_fetch_object($result);
    $vname[$i] = $row->vehicle_name;  
	  //echo "\n vname=".$vname[$i];
	 
  	$overall_dist = 0.0;
    $sno = 1;
    $title = $vname[$i]." (".$vserial[$i]."): Travel Report- From DateTime : ".$startdate."-".$enddate;
  	$csv_string = $csv_string.$title."\n";
    $csv_string = $csv_string."SNo,StartTime,EndTime,StartPlace,EndPlace,Distance Travelled(km),Travel Time(H:m:s)\n";
  		
    get_travel_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $threshold);
	
	  //$csv_string = $csv_string.'Total,'.$startdate.','.$enddate.','.$overall_dist."\n\n"; 
    //echo "\n".$csv_string;
    $csv_string_arr[$i] = $csv_string;    
  	//echo   "t2".' '.$i;
	}	
}

function get_travel_xml_data($vehicle_serial, $vid, $vname, $startdate,$enddate,$datetime_threshold)
{
	//global $sno;
	//global $csv_string;
	//global $overall_dist;
	
	$fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$linetowrite="";
	//$date_1 = explode(" ",$startdate);
	//$date_2 = explode(" ",$enddate);
	
	//$date_1 = explode(" ",$startdate);
	//$date_2 = explode(" ",$enddate);	

	//$datefrom = $date_1[0];
	//$dateto = $date_2[0];
	
	$datefrom = $startdate;
	$dateto = $enddate;	
	
	$startdate = $startdate." 00:00:00";
	$enddate = $enddate." 23:59:59";
	//$timefrom = $date_1[1];
	//$timeto = $date_2[1];

	get_All_Dates($datefrom, $dateto, &$userdates);

	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);

	//$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append

	for($i=0;$i<=($date_size-1);$i++)
	{
    $xml_current = "../../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
    		
    if (file_exists($xml_current))      
    {		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = "../../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}
		
		//echo "<br>xml in get_halt_xml_data =".$xml_file;	    	
    if (file_exists($xml_file)) 
		{			
      //echo "<br>file_exists1";     
      $t=time();
      $xml_original_tmp = "../../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
      //$xml_log = "../../../xml_tmp/filtered_xml/tmp_".$current_datetime1.".xml";
      //echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
									      
      if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
        copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "<br>TWO<br>";
        $xml_unsorted = "../../../../xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
				//echo  "<br>".$xml_file." <br>".$xml_unsorted."<br><br>";
				        
        copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
        SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}
      
      $total_lines = count(file($xml_original_tmp));
      //echo "<br>Total lines orig=".$total_lines;
      
      $xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
      //$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
      $logcnt=0;
      $DataComplete=false;
                  
      $vehicleserial_tmp=null;
      $f =0;
      
      if (file_exists($xml_original_tmp)) 
      {      
        //echo "<br>In FileExists";
        
        $start_time_flag = 0;
        $distance_total = 0;
        $distance_threshold = 0.200;
        $distance_error = 0.100;
        $distance_incriment =0.0;
        $firstdata_flag =0;
        $start_point_display =0;
        
    		$haltFlag==True;
    		$distance_travel=0;                        
        //echo "<br>file_exists2";                
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
  
  				if(strpos($line,'Fix="1"'))     // RETURN FALSE IF NOT FOUND
  				{
  					$format = 1;
            $fix_tmp = 1;
  				}
                
  				if(strpos($line,'Fix="0"'))
  				{
  				  $format = 1;
  					$fix_tmp = 0;
  				}
  				else
  				{
  				  $format = 2;
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
            $datetime = get_xml_data('/datetime="[^"]+"/', $line);
  					$xml_date = $datetime;
  				}				
          //echo "Final0=".$xml_date." datavalid=".$DataValid;          
          if($xml_date!=null)
  				{				  					
  					if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
  					{							           	                            
                $vserial = get_xml_data('/vehicleserial="[^"]+"/', $line);  
            		$lat = get_xml_data('/lat="\d+\.\d+[NS]\"/', $line);
            		$lng = get_xml_data('/lng="\d+\.\d+[EW]\"/', $line);
            		//$datetime = get_xml_data('/datetime="[^"]+"/', $line);             
                
                // HALT LOGIC   /////////////                             
                if($firstdata_flag==0)
                {                                
                  $firstdata_flag = 1;
                  $haltFlag=True;
                  $distance_travel=0;                                    
    
              		$lat_S = $lat;
              		$lng_S = $lng;
              		$datetime_S = $datetime;
              		$datetime_travel_start = $datetime_S;
              		
              		$lat_travel_start = $lat_S;
              		$lng_travel_start = $lng_S;
                  
                  $start_point_display =0;                  	                             	
              	}           	              	
                else
                {           
                  $lat_E = $lat;
                  $lng_E = $lng;                        

                  $datetime_E = $datetime;                                  									
              		//$date_secs2 = strtotime($datetime_cr);                		
              		//echo "<br>str=".$lat_ref.", ".$lat_cr.", ".$lng_ref." ,".$lng_cr.", ". $datetime_cr;              		
              		//$distance_incriment = calculate_distance($lat_S, $lat_E, $lng_S, $lng_E);
              		calculate_distance($lat_S, $lat_E, $lng_S, $lng_E, &$distance_incriment);
              		//echo "<br>distance:".$distance;                	
            			//echo "<br>next -time_start:".$time_start." ,time_start_sec:".$time_start_sec; 
            			
          				if($distance_incriment > $distance_error)
          				{          					                      
                    //echo "<br>dist";
                    if($haltFlag==True)
          					{
          						$datetime_travel_start = $datetime_E;
          						$haltFlag = False;
          					}
          					$distance_total += $distance_incriment;
          					$distance_travel += $distance_incriment;
          					$lat_S = $lat_E;
          					$lng_S = $lng_E;
          					$datetime_S = $datetime_E;
          					$start_point_display =1;
          					//$distance_incrimenttotal += $distance_incriment;
          					// echo $datetime_E . " -- " . $lat_E .",". $lng_E . "\tDelta Distance = " . $distance_incriment . "\tTotal Distance = " . $distance_total . "\n";
          				}
          				/*else
          				{
                    if($start_point_display == 0)
                    {
                      //echo "<br>startpoint";
                      //$haltFlag==True;
                      //$distance_travel=0;                                    
        
                  		//$lat_S = $lat;
                  		//$lng_S = $lng;
                  		$datetime_S = $datetime;
                  		$datetime_travel_start = $datetime_S;
                  		
                  		//$lat_travel_start = $lat_S;
                  		//$lng_travel_start = $lng_S;                    
                    }          				
                  }*/
          				      			
          				// echo "Delta Distance = " . $distance_incriment;
          				$datetime_diff = strtotime($datetime_E) - strtotime($datetime_S);
          
          				// echo "Total Distance = " . $distance_total . "\n";				
          				//if(($distance_incrimenttotal<$distance_threshold) && ($datetime_diff > $datetime_threshold) && ($haltFlag==False))
          				if(($distance_total>$distance_error) && ($datetime_diff > $datetime_threshold) && ($haltFlag==False))
          				{
          					//echo "<br>In travel";
                    //newHalt($datetime_S, $datetime_E);
          					$datetime_travel_end = $datetime_E;
                    $lat_travel_end = $lat_S;
                    $lng_travel_end = $lng_S;
          					newTravel($vserial, $vname, $datetime_travel_start, $datetime_travel_end, $distance_travel, $lat_travel_start, $lng_travel_start, $lat_travel_end, $lng_travel_end, $distance_travel, $fh, $linetowrite);
          			    $datetime_travel_start = $datetime_E;
                		$lat_travel_start = $lat_E;
                		$lng_travel_start = $lng_E;           				    
          
          					$distance_travel = 0;
          					// exit;
          					$datetime_S = $datetime_E;
          					$distance_total = 0;
          					$distance_incrimenttotal = 0;
          					$haltFlag = True;          					
          				}
                }
  					} // $xml_date_current >= $startdate closed
  				}   // if xml_date!null closed
  				$f++;
  			}   // while closed
      } // if original_tmp closed 
      
      fclose($xml);            
			unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 	
	//echo "Test1";
	//fclose($fh);
	//fclose($xmllog);
}                                                                                                                                                           


  function newTravel($vserial, $vname, $datetime_S, $datetime_E, $distance, $lat_travel_start, $lng_travel_start, $lat_travel_end, $lng_travel_end, $distance_travel, $fh, $linetowrite)
  {
  	global $sno;
  	global $csv_string;
  	global $DbConnection;
      
    $travel_dur =  strtotime($datetime_E) - strtotime($datetime_S);                                                    
    $hms = secondsToTime($travel_dur);
    $travel_time = $hms[h].":".$hms[m].":".$hms[s];
    
    $distance_travel = round($distance_travel,2);
       
    //echo "\t\t\t\tTravel : " . $datetime_S . " to " . $datetime_E . "( " . $distance . " )<br>";        
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
    get_landmark($lat_travel_end,$lng_travel_end,&$landmark);    // CALL LANDMARK FUNCTION
		
    $place2 = $landmark;
    
    if($place2=="")
    {
      get_location($lat_travel_end,$lng_travel_end,$alt2,&$place2,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
    }      
    //////////////      		
    $place1 = str_replace(',',':',$place1);
    $place2 = str_replace(',',':',$place2);
    
    $csv_string = $csv_string.$sno.','.$datetime_S.','.$datetime_E.','.$place1.','.$place2.','.$distance_travel.','.$travel_time."\n";
    //echo "\ncsv1=".$csv_string."\n";
    //$overall_dist = $overall_dist + $total_dist;
    $sno++;    
  } 
  	
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
  

  // SEND REPORT MAIL 
$query2 = "SELECT DISTINCT escalation.person_name,escalation.person_email,".
          "escalation.escalation_id,alert.alert_id FROM ".
          "escalation,alert_assignment,alert WHERE ".
          "escalation.escalation_id = alert_assignment.escalation_id AND ".            
          "alert.alert_name='report' AND ".
          "alert.alert_id = alert_assignment.alert_id AND ".                      
          "alert_assignment.mail_status = 1 AND ".            
          "alert_assignment.status=1 AND ".
          "escalation.status=1 AND ".
          "alert.status=1";
echo "\n".$query2;
$result2 = mysql_query($query2,$DbConnection);

$k=0;
while($row2 = mysql_fetch_object($result2))
{    
    $escalation_id[] = $row2->escalation_id; 
    $person_name[] = $row2->person_name;
    $person_email[] = $row2->person_email;
}


//date_default_timezone_set('Asia/Calcutta');	
$last_datetime = date("Y-m-d H:i:s");
$last_datetime_t = strtotime($last_datetime);
		
for($i=0;$i<sizeof($escalation_id);$i++)     //TOTAL DISTINCT ESCALATION
{      
    echo "\n";
    $vehicle_id_final = "";
    $alert_id_final = "";
    $csv_string_final ="";
    
    $query_vehicle = "SELECT vehicle_id,alert_id,alert_duration FROM alert_assignment WHERE alert_id IN(SELECT alert_id FROM alert WHERE ".
                      "alert_name='report' AND status=1) AND escalation_id='$escalation_id[$i]' AND status=1";
    //echo "\n".$query_vehicle;     
    $result_vehicle = mysql_query($query_vehicle,$DbConnection);    
                           
    $k=0;
    $match_case = 0;
    
    while($row = mysql_fetch_object($result_vehicle))      // LOOP FOR MULTIPLE ALERTS
    {
      $vid_db = $row->vehicle_id;
      $alert_db = $row->alert_id;      
      $duration_db = $row->alert_duration;
      $duration_db_secs = $duration_db * 3600;    //IN SECONDS
      //$duration_db_secs = $duration_db * 60;            
      $alert_id_final = $alert_db;
      echo "\nvid=".$vid_db;
      
      for($j=0;$j<sizeof($vid);$j++)
      {
        if($vid_db == $vid[$j])
        {
          echo "\nCondition :Vehicle Matched";
          $match_case = 1;
          $query_last_datetime = "SELECT last_datetime FROM report_last_mail_status WHERE vehicle_id ='$vid_db' AND ".
                                  "alert_id='$alert_db' AND escalation_id='$escalation_id[$i]' AND report_type='travel'";
          echo "\n".$query_last_datetime;
          $result_last_datetime = mysql_query($query_last_datetime,$DbConnection);
          $numrows = mysql_num_rows($result_last_datetime);
          
          if($numrows > 0)
          {
             echo "\nCondition :Last DateTime Found";
             $row_dt = mysql_fetch_object($result_last_datetime);
             $last_datetime_db = $row_dt->last_datetime;
             
             $last_datetime_db_t = strtotime($last_datetime_db);
             
             $diff_time_secs = ($last_datetime_t - $last_datetime_db_t);
             
             echo "\nTime1=".$last_datetime." ,TimeDB=".$last_datetime_db;
             
             if($diff_time_secs >= $duration_db_secs)  //IF ALERT TIME DURATION SATISFIED  EG. 6HRS, 12 HRS, 24HRS, 1 WEEK ETC
             {             
               echo "\nCondition :Time Duration Satisfied";
               if($k==0)
               {
                 $vehicle_id_final = $vehicle_id_final.$vid_db;
                 //$alert_id_final = $alert_id_final.$alert_db;
                 $csv_string_final = $csv_string_final.$csv_string_arr[$j];
                 $k++;
               }
               else
               {
                 $vehicle_id_final = $vehicle_id_final.",".$vid_db;
                 //$alert_id_final = $alert_id_final.":".$alert_db;
                 $csv_string_final = $csv_string_final."\n".$csv_string_arr[$j]; 
                 $k++;             
               }
               //UPDATE LAST DATETIME STATUS
                $query_update1 = "UPDATE report_last_mail_status SET last_datetime='$last_datetime' WHERE vehicle_id ='$vid_db' AND ".
                                    "alert_id='$alert_db' AND escalation_id='$escalation_id[$i]' AND report_type='travel'";
                echo "\nUPDATE1:".$query_update1;
                $result_last_datetime1 = mysql_query($query_update1,$DbConnection);
              } 
              echo "\nvid_final1=".$vehicle_id_final;            
          }              
          else
          {
             echo "\nElse Condition :New Datetime Inserted";
             if($k==0)
             {
               $vehicle_id_final = $vehicle_id_final.$vid_db;
               //$alert_id_final = $alert_id_final.$alert_db;
               $csv_string_final = $csv_string_final.$csv_string_arr[$j];
               $k++;
             }
             else
             {
               $vehicle_id_final = $vehicle_id_final.",".$vid_db;
               //$alert_id_final = $alert_id_final.":".$alert_db;
               $csv_string_final = $csv_string_final."\n".$csv_string_arr[$j]; 
               $k++;             
             }
              //UPDATE LAST DATETIME STATUS
              $query_update2 = "insert into report_last_mail_status(last_datetime,vehicle_id,alert_id,report_type,escalation_id)".
                              "VALUES('$last_datetime','$vid_db','$alert_db','travel','$escalation_id[$i]')";
              echo "\nUPDATE2:".$query_update2;
              $result_last_datetime2 = mysql_query($query_update2,$DbConnection);
              
              echo "\nvid_final1=".$vehicle_id_final2;
          }                  
                             
          break;   //STORE AND BREAK INNER LOOP
        }  // COMPARISON IF CLOSED
      }   // FOR CLOSED
    }    //WHILE CLOSED                       
    //NEW CODE
    
      
    if($match_case && $vehicle_id_final!="")
    {
      $email_message = ""; 
    	$fileatt_final="";
    	$fileatt_type_final="";
    	$fileatt_name_final="";
        
      //SAVE FINAL MAIL CONTENT TO MAIL INFO TABLE	  	
      $current_dt_tmp1 = date("Y_m_d_H_i_s");
      $datetime_sent = $last_datetime;
      $download_file = "travel_report_".$current_dt_tmp1.$i.".csv";
      //$path = "/var/www/html/vts/test/src/php/download";
      $path = "/var/www/html/vts/beta/src/php/download";
      
      $fullPath = $path."/".$download_file;
      echo "\npath=".$fullPath;
       
      $fh1 = fopen($fullPath, 'w') or die("can't open file");
      fwrite($fh1, $csv_string_final);
      fclose($fh1);
      
      
      //SAVE FINAL MAIL CONTENT TO MAIL INFO TABLE	
    	//$email_from="support@iembsys.co.in";
    	$query_name = "SELECT vehicle_name FROM vehicle WHERE vehicle_id IN($vehicle_id_final) AND status=1";
    	$result_name = mysql_query($query_name,$DbConnection);
    	$vname_str ="";
      $v=0;
      while($row_name = mysql_fetch_object($result_name))
    	{
    	 if($v==0)
    	 {
    	   $vname_str = $vname_str.$row_name->vehicle_name;
       }
       else
       {
        $vname_str = $vname_str.",".$row_name->vehicle_name;
       }
       $v++;
      }
      
      $email_from_d="support@iembsys.co.in";
    	$email_subject_d="Travel Report:".$date1." -".$date2;
    	$email_message_d="Travel Report:".$date1." -".$date2." (".$vname_str.")";
    	$email_to_d = $person_email[$i];  //"rizwan@iembsys.com";
    	
      $fileatt_d = $fullPath;
      $fileatt_type_d = "csv";
      $fileatt_name_d = "Travel Report:".$date1." -".$date2." (".$vname_str.").csv";	
      
      echo "\nalert_id_final=".$alert_id_final;
      echo "\nvehicle_id_final=".$vehicle_id_final;
      echo "\nperson_name=".$person_name[$i];
      
      echo "\nemail_from=".$email_from_d;
      echo "\nemail_subject=".$email_subject_d;
      echo "\nemail_to=".$email_to_d;
      echo "\nemail_message=".$email_message_d."\n";      
      
      
      $query4 = "INSERT INTO mail_info_report(alert_id,vehicle_id,escalation_id,person_name,".
            "fileatt,fileatt_type,fileatt_name,email_from,email_subject,email_message,email_to,datetime_sent,status) VALUES(".
            "'$alert_id_final','$vehicle_id_final','$escalation_id[$i]','$person_name[$i]','$fileatt_d','$fileatt_type_d','$fileatt_name_d',".
            "'$email_from_d','$email_subject_d','$email_message_d','$email_to_d','$datetime_sent',1)";                
      //echo "\n".$query4;
      $result4 = mysql_query($query4,$DbConnection);    
    } //IF MATCH CASE CLOSED
    
} //OUTER WHILE CLOSED   

echo "\n TRAVEL LOGIC CLOSED\n";        
?>					
