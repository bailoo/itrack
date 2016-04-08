<?php

set_time_limit(600);

include_once('alert_util_php_mysql_connectivity.php');
include_once("../../calculate_distance.php");

$alert_imei = array();
$alert_vname = array();
$alert_msg = array();

//GET AND SET LAST PROCESSING TIME
$current_date = date("Y-m-d");
$current_datetime = date("Y-m-d H:i:s");

$file_last_time = "intermediate_last_time.txt";
$xml_last_time = @fopen($file_last_time, "r") or $fexist = 0;  

$last_time = $current_date." 00:00:00"; 

if (file_exists($file_last_time)) 
{              
  while(!feof($xml_last_time))          // WHILE LINE != NULL
	{
		$last_time = fgets($xml_last_time);
		echo "\nLastTime=".$last_time;
  }
}       
      		
$fp_last = fopen('intermediate_last_time.txt', 'w');
fwrite($fp_last, $current_datetime);
fclose($fp_last);
//////////////////////////////////

$query = "SELECT DISTINCT device_imei_no FROM vehicle_assignment WHERE vehicle_id IN(SELECT vehicle_id FROM schedule_assignment WHERE ".
					"CURDATE( ) >= date_from AND CURDATE( ) <= date_to AND status=1) AND status=1";	        //GET DISTINCT IMEI

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
 
  $query2 = "SELECT * FROM schedule_assignment WHERE CURDATE( ) >= date_from AND CURDATE( ) <= date_to AND vehicle_id IN ( SELECT vehicle_id FROM vehicle_assignment WHERE device_imei_no = '$vserial[$i]' AND status =1)";
	echo $query2."\n";
  $result2 = mysql_query($query2);
	while($row2 = mysql_fetch_object($result2))
	{
    //echo "\nCURRENT DATE MATCHED";
    $vehicle_id = $row2->vehicle_id;
    $base_station_id = $row2->base_station_id;
    $by_day = $row2->by_day;
    $day = $row2->day;
    $Intermediate_halt_time = $row2->Intermediate_halt_time;
    
    $valid_day = false;
    
    if($by_day ==1)
    {
      $day_db = explode(",",$day);
      
      $timestmp = strtotime($current_date);
      $weekday = date("w",$timestmp);     //0 =SUN, 6=SAT
      
      for($z=0;$z<sizeof($day_db);$z++)                     //CHECKS EVERYTIME FOR ONE RECORD AND BREAKS- INNER LOOP
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
      echo "\nVALID DATE FOUND";
      $query3 = "SELECT landmark_coord FROM landmark WHERE landmark_id='$base_station_id'";
      //echo "Q3=".$query3."\n";
      $result3 = mysql_query($query3,$DbConnection);
      $row3 = mysql_fetch_object($result3);
      $landmark_coord = $row3->landmark_coord;
            
      $intermediate_time_tmp = explode(",",$Intermediate_halt_time);
      
      for($z=0;$z<sizeof($intermediate_time_tmp);$z++)
      {
        $schedule_time_tmp = $current_date." ".$intermediate_time_tmp[$z].":00";
        //echo "\nschedule_time_tmp=".$schedule_time_tmp." ,last_time=".$last_time." ,current_datetime=".$current_datetime." \nlandmark_coord=".$landmark_coord;
        if( ($schedule_time_tmp>=$last_time) && ($schedule_time_tmp<=$current_datetime) )
        {          
          echo "\nSCHEDULE TIME MATCHED";
          get_alert_intermediate_data($vserial[$i], $vname, $landmark_coord);
          break;
        }           
      }
    }  // IF VALID DAY CLOSED
  } //WHILE CLOSED    
} // FOR CLOSED

//SEND EMAIL
send_email(); 


function get_alert_intermediate_data($imei, $vname, $landmark_coord)
{
  global $alert_imei;
  global $alert_vname;
  global $alert_msg;
  
  $coord = explode(',',$landmark_coord);
  $lat2 = trim($coord[0]);
  $lng2 = trim($coord[1]);  
                
  $back_path = "../../../../..";
  
  $xml_file = $back_path."/xml_vts/xml_last/".$imei.".xml";
	$file = file_get_contents($xml_file);
  if(!strpos($file, "</t1>")) 
  {
    usleep(1000);
  }		
  
  $t=time();
  $rno = rand();			
	$xml_original_tmp = $back_path."/xml_tmp/original_xml/tmp_".$imei."_".$t."_".$rno.".xml";		
	copy($xml_file,$xml_original_tmp); 
	    
	if (file_exists($xml_original_tmp))
	{
		//echo "<br>exist2";
    $fexist =1;
		$fp = fopen($xml_original_tmp, "r") or $fexist = 0;   
		$total_lines =0;
		$total_lines = count(file($xml_original_tmp));
		//echo "<br>total_lines=".$total_lines;
		$c =0;
		while(!feof($fp)) 
		{
			$line = fgets($fp);
			$c++;				
			
			if(strlen($line)>15)
			{
				if ( (preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) && (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
				{
            $status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
            $datetime_tmp1 = explode("=",$datetime_tmp[0]);
            $datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
            $xml_date = $datetime;
            
            /*$status = preg_match('/speed="[^"]+/', $line, $speed_tmp);
            $speed_tmp1 = explode("=",$speed_tmp[0]);
            $speed = preg_replace('/"/', '', $speed_tmp1[1]); */
            
            $status = preg_match('/lat="[^"]+/', $line, $lat_tmp);
            $lat_tmp1 = explode("=",$lat_tmp[0]);
            $lat = preg_replace('/"/', '', $lat_tmp1[1]);
            
            $status = preg_match('/lng="[^"]+/', $line, $lng_tmp);
            $lng_tmp1 = explode("=",$lng_tmp[0]);
            $lng = preg_replace('/"/', '', $lng_tmp1[1]);
                       
            calculate_distance($lat,$lat2,$lng,$lng2,&$distance);
            if($distance < 1) //KM : VECHILE DID NOT MOVED
            {
              echo "\nTIME VIOLATED";
              $msg = "VEHICLE:".$vname." did not move -(DateTime:".$xml_date.")";
              $alert_imei[] = $imei;
              $alert_vname[] = $vname;
              $alert_msg[] = $msg;
            }					             
        } //INNER IF
      }  // OUTER IF
    } // WHILE CLOSED
  } // if (file_exists($xml_original_tmp)) CLOSED
}  //FUNCTION CLOSED


function send_email()
{
  global $alert_imei;
  global $alert_vname;
  global $alert_msg;
  
  $alert_string = "";
  
  for($i=0;$i<sizeof($alert_imei);$i++)
  {
    $alert_string = $alert_string.$alert_msg[$i]."\n";    
  }
  
  //$alert_string = "vehicle test did not move at 2012-07-21 14:30:00";
  if( (sizeof($alert_imei)>0) && ($alert_string!=""))
  {
    $to = 'sanchan@wockhardtfoundation.org';   
    //$to = 'vaibhavrspl@gmail.com';
    //$to = 'rizwan@iembsys.com'; 
    //define the subject of the email 
    $subject = 'INTERMEDIATE HALT TIME ALERT';
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
}
                 							
?>
						
