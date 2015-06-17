<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

set_time_limit(0);

include_once("get_all_dates_between.php");
include_once("sort_xml.php");
include_once("calculate_distance.php");
include_once("report_title.php");
include_once("read_filtered_xml.php");
include_once("select_landmark_report.php");
include_once("util.hr_min_sec.php");
//include("get_location.php");

include_once("area_violation/check_with_range.php");
include_once("area_violation/pointLocation.php");
include("user_type_setting.php");

$counter = 0;
//////////////////////////////////////////  GET UNIQUE DEVICE IMEI NUMBERS /////////////////////////////////////////////////////

$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM ".
            "vehicle,vehicle_assignment,alert_assignment,visit_area_assignment WHERE ".
            "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND ".
            "vehicle_assignment.vehicle_id = visit_area_assignment.vehicle_id AND ".
            "visit_area_assignment.vehicle_id = alert_assignment.vehicle_id AND ".            
            "vehicle_assignment.status=1 AND visit_area_assignment.status=1";

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

$vsize=sizeof($device_imei_no);

if($vsize>0)
{  
  $current_dt = date("Y_m_d_H_i_s");	
  $xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";
  write_visit_area_report_xml($device_imei_no, $vid, $vehicle_name);
}

function write_visit_area_report_xml($vserial, $vid, $vname)
{
  $maxPoints = 1000;
	$file_exist = 0;

	for($i=0;$i<sizeof($vserial);$i++)
	//for($i=0;$i<1;$i++)
	{  	
     //echo   "<br>vserial[i] =".$vserial[$i];
     $current_date = date("Y-m-d");
     $startdate = $current_date." 00:00:00";
     $enddate = $current_date." 23:00:00";
     //echo "<br>STR=".$vserial[$i].", ".$vid[$i].", ".$vname[$i].", ".$startdate.", ".$enddate;
     get_visit_area_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate);
	}  
}

function get_visit_area_xml_data($vehicle_serial, $vid, $vname, $startdate, $enddate)
{
	//echo "<br>A";
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
	
	$visit_area_status = array();

	//get_All_Dates($datefrom, $dateto, &$userdates);
	$today_date = $datefrom;

  //echo "<br>B";
	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	//$date_size = sizeof($userdates);

	//$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append

  //echo "<br>C";
	$j = 0;
	$total_dist = 0;
 									
	global $DbConnection;
	global $account_id;
	global $counter;
  $counter++;
  ////////////////////////////////////////////////////////
  $query1 = "SELECT visit_area.visit_area_id,visit_area.visit_area_coord FROM ".
  "visit_area,visit_area_assignment,vehicle_assignment WHERE ".
  "schedule_date ='$today_date' AND ".
  "visit_area_assignment.visit_area_id = visit_area.visit_area_id AND ".
  "visit_area_assignment.vehicle_id = vehicle_assignment.vehicle_id AND ".
  "vehicle_assignment.device_imei_no='$vehicle_serial' AND ".
  "visit_area.status=1 AND vehicle_assignment.status=1 AND visit_area.status=1";
  //echo "<br>q1=".$query1;
  $result1 = mysql_query($query1,$DbConnection);
  
  while($row1 = mysql_fetch_object($result1))
  {
    $visit_area_id[] = $row1->visit_area_id;        
    $visit_area_coord_tmp = $row1->visit_area_coord;
		$visit_area_coord1 = base64_decode($visit_area_coord_tmp);
		
    $visit_area_coord1 = str_replace('),(',' ',$visit_area_coord1);
    $visit_area_coord1 = str_replace('(','',$visit_area_coord1);
    $visit_area_coord1 = str_replace(')','',$visit_area_coord1);
    $visit_area_coord1 = str_replace(', ',',',$visit_area_coord1);
    
    //echo  "<br>C=".$visit_area_coord1;
    $visit_area_coord[] = $visit_area_coord1;    
  }
     
  $outflag=0;          	
  
  $xml_current = "../../../xml_vts/xml_data/".$today_date."/".$vehicle_serial.".xml";	
  		

	//echo "<br>xml in get_halt_xml_data =".$xml_file;	   	
  if (file_exists($xml_current)) 
	{			
    $t=time();
    $xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$counter.".xml";
    //echo "<br>path=".$xml_original_tmp;
			//echo "<br>ONE<br>";
    $xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$counter."_unsorted.xml";
		//echo  "<br>xml_file=".$xml_file." <br>".$xml_unsorted."<br><br>";
		        
    copy($xml_current,$xml_unsorted);        // MAKE UNSORTED TMP FILE
    SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
		unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
    
    $total_lines = count(file($xml_original_tmp));
    //echo "<br>Total lines orig=".$total_lines;
    
    
    $xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
    //$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
    $logcnt=0;
    $DataComplete=false;                  
    $vehicleserial_tmp=null;
    $format =2;
    
    $datetime = null;
    $hrs_min = null;
    $j=0; 
    $v=0;
    $f = 0;
      
    if (file_exists($xml_original_tmp)) 
    {
      //echo "<br>file exists";                      
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
					//preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
					$status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
					$datetime_tmp1 = explode("=",$datetime_tmp[0]);
          $datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
					//echo "<br>str3tmp[0]=".$str3tmp[0];
					$xml_date = $datetime;
				}				
        //echo "Final0=".$xml_date." datavalid=".$DataValid;
        
        if($xml_date!=null)
				{				  
          //echo "<br>".$xml_date.",".$startdate.",".$enddate.",".$DataValid;
          //$lat = $lat_value[1] ;
					//$lng = $lng_value[1];
					
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

            //$vehiclename_tmp1 = explode("=",$vehiclename_tmp[0]);
            //$vname = preg_replace('/"/', '', $vehiclename_tmp1[1]);
            
            $lat_tmp1 = explode("=",$lat_tmp[0]);
            $lat = preg_replace('/"/', '', $lat_tmp1[1]);
            
            $lng_tmp1 = explode("=",$lng_tmp[0]);
            $lng = preg_replace('/"/', '', $lng_tmp1[1]);    
                       
                                          
            for($v=0;$v<sizeof($visit_area_coord);$v++)
            {
                $status_geo = false;
                check_with_range($lat, $lng, $visit_area_coord[$v], &$status_geo);                                
                //echo "<br><br>geo_status=".$status_geo;                    
                
                if($status_geo)      // VEHICLE OUTSIDE OF VISIT AREA 
                {
                  //echo "<br>VISIT IN";                 
                  $visit_area_status[$v] = $visit_area_id[$v]; 
                }       		                            		                     
            }             	                              										                               
					} // $xml_date_current >= $startdate closed
				}   // if xml_date!null closed
			 $j++;
			 $f++;
      }   // while closed
    } // if original_tmp closed   
			
   fclose($xml);            
	 unlink($xml_original_tmp);   
	} // if (file_exists closed	                                         
	
  
	for($k=0;$k<sizeof($visit_area_coord);$k++)
	{	   
     $flag_v = 0; 
     for($m=0;$m<sizeof($visit_area_status);$m++)
     {
        if($visit_area_status[$m] == $visit_area_id[$k])
        {
          echo "<br>In:".$vid.":".$vname." K=".$k." ,visit_area_id=".$visit_area_id[$k];
          
          $query_v = "UPDATE visit_area_assignment SET enter_status=1 WHERE ".
                  "visit_area_id='$visit_area_id[$k]' AND vehicle_id='$vid' AND schedule_date='$datefrom' AND status=1";
          //echo "<br>q_v=".$query_v;
          $result_v = mysql_query($query_v,$DbConnection);
                
          $flag_v =1;     
        }
     }
    
    if($flag_v ==0)
    { 
      echo "<br>Out:".$vid.":".$vname." K=".$k.",visit_area_id=".$visit_area_id[$k];       
    }
  }
} // FUNCTION CLOSED


//************ MAKE ENTRY TO "mail_info_visit_area" ************/////////////////

$current_date_1 = date("Y-m-d");
//$query1 = "SELECT visit_area_id,vehicle_id from visit_area_assignment WHERE schedule_date='$current_date_1' AND enter_status='0'";
$query1 = "SELECT visit_area_id,vehicle_id from visit_area_assignment WHERE schedule_date='$current_date_1' AND ".
          "enter_status IS NULL AND status=1";
//echo "<br>query1=".$query1;
$result1 = mysql_query($query1,$DbConnection);

while($row1 = mysql_fetch_object($result1))        //ex. 12 times
{
  $visit_area_id_tmp = $row1->visit_area_id; 
  $vehicle_id_tmp = $row1->vehicle_id;
  
  $query2 = "SELECT DISTINCT escalation.person_name,escalation.person_email,".
            "escalation.escalation_id,alert.alert_id FROM ".
            "escalation,alert_assignment,alert,visit_area_assignment WHERE ".
            "escalation.escalation_id = alert_assignment.escalation_id AND ".            
            "alert.alert_name='visit_violation' AND ".
            "alert.alert_id = alert_assignment.alert_id AND ".                     
            "visit_area_assignment.visit_area_id = '$visit_area_id_tmp' AND ".
            "visit_area_assignment.enter_status IS NULL AND ".            
            "alert_assignment.vehicle_id = visit_area_assignment.vehicle_id AND ".
            "alert_assignment.mail_status = 1 AND ".
            "alert_assignment.vehicle_id = '$vehicle_id_tmp' AND ".
            "alert_assignment.status=1 AND ".
            "visit_area_assignment.status=1 AND ".
            "escalation.status=1 AND ".
            "alert.status=1";
  //echo "<br>q2=".$query2;
  $result2 = mysql_query($query2,$DbConnection);
  
  $k=0;
  while($row2 = mysql_fetch_object($result2))
  {
      /*$visit_area_id[] = $visit_area_id_tmp;
      $vehicle_id[] = $vehicle_id_tmp; 
      $alert_id[] = $row2->alert_id; 
      $escalation_id[] = $row2->escalation_id; 
      $person_name[] = $row2->person_name;
      $person_email[] = $row2->person_email;*/     
  
      $alert_id = $row2->alert_id; 
      $escalation_id = $row2->escalation_id; 
      $person_name = $row2->person_name;
      $person_email = $row2->person_email;    
      
      //echo "<br>visit_area_id=".$visit_area_id_tmp." ,vehicle_id=".$vehicle_id_tmp; 
      
      //NEW CODE
      $k=0;
      $vehicle_id_2 ="";
      $email_message = ""; 
    	$fileatt_final="";
    	$fileatt_type_final="";
    	$fileatt_name_final="";
            
      $query_vehicle = "SELECT vehicle_name FROM vehicle WHERE vehicle_id='$vehicle_id_tmp'";
      $result_vehicle = mysql_query($query_vehicle,$DbConnection);
      $row_vehicle = mysql_fetch_object($result_vehicle);    
      $vehicle_name_tmp = $row_vehicle->vehicle_name;
      
      
      $query_visit = "SELECT visit_area_name FROM visit_area WHERE visit_area_id='$visit_area_id_tmp'";
      $result_visit = mysql_query($query_visit,$DbConnection);
      $row_visit = mysql_fetch_object($result_visit);      
      $visit_area_name_tmp = $row_visit->visit_area_name;
        
      $msg = $vehicle_name_tmp." did not enter in -Visit Area->".$visit_area_name_tmp;            
        
      if($k==0)
      {    
        $vehicle_id_2 = $vehicle_id_2.$vehicle_id_tmp;
        /*$fileatt_final= $fileatt_final.$fileatt_final_tmp;
        $fileatt_type_final = $fileatt_type_final.$fileatt_type_final_tmp;
        $fileatt_name_final = $fileatt_name_final.$fileatt_name_final_tmp;*/
        $email_message = $email_message.$msg;
      }
      else
      { 
        $vehicle_id_2 = $vehicle_id_2.":".$vehicle_id_tmp;
        /*$fileatt_final = $fileatt_final.":".$fileatt_final_tmp;
        $fileatt_type_final = $fileatt_type_final.":".$fileatt_type_final_tmp;
        $fileatt_name_final = $fileatt_name_final.":".$fileatt_name_final_tmp;*/
        $email_message = $email_message.":".$msg;
      }
      $k++;        
    
      //SAVE FINAL MAIL CONTENT TO MAIL INFO TABLE	
    	$email_from="support@iembsys.co.in";
      //$email_from="rizwan@iembsys.com";
    	$email_subject="Visit Area violation:".$current_date_1;
    	//$email_message="Vehicle :".$vehicle_name;
    	$email_to="rizwan@iembsys.com";
      
      echo "<br>alert_id=".$alert_id;
      echo "<br>vehicle_id=".$vehicle_id_2;
      echo "<br>visit_area_id=".$visit_area_id_tmp;
      echo "<br>person_name=".$person_name;
      
      echo "<br>email_from=".$email_from;
      echo "<br>email_subject=".$email_subject;
      echo "<br>email_to=".$email_to;
      echo "<br>email_message=".$email_message;
      
      $query4 = "INSERT INTO mail_info_visit_area(alert_id,vehicle_id,escalation_id,visit_area_id,person_name,".
                "fileatt,fileatt_type,fileatt_name,email_from,email_subject,email_message,email_to,datetime_sent,status) VALUES(".
                "'$alert_id','$vehicle_id','$escalation_id','$visit_area_id','$person_name','$fileatt','$fileatt_type','$fileatt_name',".
                "'$email_from','$email_subject','$email_message','$email_to','$datetime_sent',1)";
                
      //echo "<br>q4=".$query4;
      $result4 = mysql_query($query4,$DbConnection);    
      //
  }  //INNER WHILE CLOSED
} //OUTER WHILE CLOSED           
  



/*echo "<br>escalation_size=".sizeof($escalation_id);
 
for($i=0;$i<sizeof($escalation_id);$i++)      //eg 8 times
{
  $k=0;
  $vehicle_id_2 ="";
  $email_message = ""; 
	$fileatt_final="";
	$fileatt_type_final="";
	$fileatt_name_final="";
  //echo "<br>before q3";
  $query3 = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name,visit_area.visit_area_id,visit_area.visit_area_name FROM ".
            "vehicle,vehicle_assignment,alert_assignment,visit_area,visit_area_assignment WHERE ".
            "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND ".
            "vehicle_assignment.vehicle_id = visit_area_assignment.vehicle_id AND ".
            "visit_area_assignment.vehicle_id = alert_assignment.vehicle_id AND ".
            "alert_assignment.escalation_id='$escalation_id[$i]' AND ".
            "visit_area_assignment.vehicle_id='$vehicle_id[$i]' AND ".
            "visit_area_assignment.visit_area_id='$visit_area_id[$i]' AND ".
            "visit_area_assignment.enter_status IS NULL AND ".
            "vehicle_assignment.status=1 AND ".
            "visit_area_assignment.status=1 AND ".
            "alert_assignment.status=1 AND ".
            "visit_area.status=1 AND ".
            "vehicle.status=1";
  echo "<br>after q3=".$query3;
                        
  $result3 = mysql_query($query3,$DbConnection);
  $numrows3 = mysql_num_rows($result3);
  echo "<br>numrows3=".$numrows3;
   
  while($row3 = mysql_fetch_object($result3))
  {      
    $vehicle_id_tmp = $row3->vehicle_id;
    $vehicle_name_tmp = $row3->vehicle_name;
    
    $visit_area_id_tmp = $row3->visit_area_id;
    $visit_area_name_tmp = $row3->visit_area_name;  
    $msg = $vehicle_name_tmp." did not enter in -Visit Area->".$visit_area_name_tmp;            
    
    if($k==0)
    {    
      $vehicle_id_2 = $vehicle_id_2.$vehicle_id_tmp;
      $fileatt_final= $fileatt_final.$fileatt_final_tmp;
      $fileatt_type_final = $fileatt_type_final.$fileatt_type_final_tmp;
      $fileatt_name_final = $fileatt_name_final.$fileatt_name_final_tmp;
      $email_message = $email_message.$msg;
    }
    else
    { 
      $vehicle_id_2 = $vehicle_id_2.":".$vehicle_id_tmp;
      $fileatt_final = $fileatt_final.":".$fileatt_final_tmp;
      $fileatt_type_final = $fileatt_type_final.":".$fileatt_type_final_tmp;
      $fileatt_name_final = $fileatt_name_final.":".$fileatt_name_final_tmp;
      $email_message = $email_message.":".$msg;
    }
    $k++;        
  }
  
  //SAVE FINAL MAIL CONTENT TO MAIL INFO TABLE	
	$email_from="support@iembsys.co.in";
  //$email_from="rizwan@iembsys.com";
	$email_subject="Visit Area violation:".$current_date_1;
	//$email_message="Vehicle :".$vehicle_name;
	$email_to="rizwan@iembsys.com";
  
  //echo "<br>vehicle_id=".$vehicle_id;
  //echo "<br>email_message=".$email_message;
  
  $query4 = "INSERT INTO mail_info_visit_area(alert_id,vehicle_id,escalation_id,visit_area_id,person_name,".
            "fileatt,fileatt_type,fileatt_name,email_from,email_subject,email_message,email_to,datetime_sent,status) VALUES(".
            "'$alert_id','$vehicle_id','$escalation_id','$visit_area_id','$person_name','$fileatt','$fileatt_type','$fileatt_name',".
            "'$email_from','$email_subject','$email_message','$email_to','$datetime_sent',1)";
            
  //echo "<br>q4=".$query4;
  //$result4 = mysql_query($query4,$DbConnection);

}  //FOR CLOSED          
*/

             
include("phpmailer/smtp_email_wokhard.php");

?>
					