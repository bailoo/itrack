<?php
//include_once('../util_session_variable.php');
//include_once('../util_php_mysql_connectivity.php');

include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

set_time_limit(0);

include_once("../get_all_dates_between.php");
include_once("../sort_xml.php");
include_once("../calculate_distance.php");
include_once("../report_title.php");
include_once("../read_filtered_xml.php");
/*include_once("get_all_dates_between.php");
include_once("sort_xml.php");
include_once("calculate_distance.php");
include_once("report_title.php");
include_once("read_filtered_xml.php"); */


$csv_string = "";
$overall_dist = 0.0;
$csv_string_arr = array();

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
$vserial = explode(':',$device_str);   */
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
  write_distance_report_xml($device_imei_no, $vid, $vehicle_name, $user_interval);
}
    

$sno = 0;
function write_distance_report_xml($vserial, $vid, $vname, $user_interval)
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
    $title = $vname[$i]." (".$vserial[$i]."): Distance Report- From DateTime : ".$startdate."-".$enddate;
  	$csv_string = $csv_string.$title."\n";
    $csv_string = $csv_string."SNo,StartTime,EndTime,Distance (km)\n";
  		
    get_distance_xml_data($vserial[$i], $vid[$i], $vname[$i], $startdate, $enddate, $user_interval);
	
	  $csv_string = $csv_string.'Total,'.$startdate.','.$enddate.','.$overall_dist."\n\n"; 
    //echo "\n".$csv_string;
    $csv_string_arr[$i] = $csv_string;    
  	//echo   "t2".' '.$i;
	}  	
}

function get_distance_xml_data($vehicle_serial, $vid, $vname, $startdate, $enddate, $user_interval)
{
	//echo "<b>str=".$vehicle_serial." ,". $vid." ,". $vname." ,". $startdate." ,". $enddate." ,". $user_interval." ,". $xmltowrite;
	global $sno;
	global $csv_string;
	global $overall_dist;
	
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

	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);

	//$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append

	$j = 0;
	$total_dist = 0.0;
	global $overall_dist;
  $overall_dist = 0.0;
 									
  for($i=0;$i<=($date_size-1);$i++)
	{
	 	/*if($date_size==1)
	  {
      $startdate1 = $startdate;
      $enddate1 = $enddate;
    }
	  else if($i==0)
	  {
	     $startdate1 = $startdate;
	     $enddate1 = $userdates[$i]." 23:59:59";
    }
    else if($i==($date_size-1))
    {
      $startdate1 = $userdates[$i]." 00:00:00";
	    $enddate1 = $enddate;
    }
    else
    {
       $startdate1 = $userdates[$i]." 00:00:00";
	     $enddate1 = $userdates[$i]." 23:59:59";
    } */
    
    $startdate1 = $startdate;
    $enddate1 = $enddate;
      
    $xml_current = "../../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
    /*if($_SERVER["HTTP_X_FORWARDED_FOR"]=="172.26.48.189")
    {
      echo "stardate=".$startdate1." ,enddate1=".$enddate1." ,xml_current=".$xml_current;
    }*/
    		
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
		  //$current_datetime1 = date("Y_m_d_H_i_s");
      //$timeparts = explode(" ",microtime());
      //$currenttime = bcadd(($timeparts[0]*1000),bcmul($timeparts[1],1000));		  
      $t=time();
      $xml_original_tmp = "../../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
      //$xml_log = "xml_tmp/filtered_xml/tmp_".$current_datetime1.".xml";
      //echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
									      
      if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
        copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "<br>TWO<br>";
        //$xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$current_datetime1."_unsorted.xml";
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
      $format =2;
      $c = -1;
      

      if (file_exists($xml_original_tmp)) 
      {      
        //echo "<br>file exists";
        
        while(!feof($xml))          // WHILE LINE != NULL
      	{
      		$c++;
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
      			//$lat = $lat_value[1] ;
      			//$lng = $lng_value[1];    					
      			//echo "<br>xml_date=".$xml_date." ,end_date=".$enddate." ,data_valide=".$DataValid;
      			
            //if( ($xml_date >= $startdate1 && $xml_date <= $enddate1) && ($xml_date!="-") && ($DataValid==1) )
            if( ($xml_date >= $startdate1 && $xml_date <= $enddate1) && ($xml_date!="-") && ($DataValid==1) )
            //if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
      			{							           	
      				//echo "<br>One";             
      				$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
      				//echo "Status=".$status.'<BR>';
      				//echo "test1".'<BR>';
      				if($status==0)
      				{
      					continue;
      				}
      				//echo "<textarea>".$line."</textarea>"; 
      				//$line = '<marker msgtype="NORMAL" vehicleserial="354776033544005" vehiclename="SJ DKL-4005" ver="v1.34" fix="1" lat="26.60652N" lng="80.20758E" alt="123.0" speed="0.000000" datetime="2011-01-25 03:16:23" fuel="-" vehicletype="Heavy" no_of_sat="09" cellname="" distance="0.0" io1="7" io2="6" io3="387" io4="10" io5="6" io6="6" io7="4" io8="2" sig_str="0" sup_v="0.000000" speed_a="0" geo_in_a="0" geo_out_a="0" stop_a="0" move_a="0" lowv_a="0"/>';  
      				//$status = preg_match('/vehiclename="[^"]+/', $line, $vehiclename_tmp);          
      				//echo "<br>vname=".$vehiclename_tmp[0];
      				/*if($status==0)
      				{
      				  continue;
      				} */
                  
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
                                           
      				//echo "test4".'<BR>';
      				/*$status = preg_match('/datetime="[^" ]+/', $line, $datetime_tmp);
      				if($status==0)
      				{
      				  continue;
      				} */
                               
    					$lat_tmp1 = explode("=",$lat_tmp[0]);
    					$lat = preg_replace('/"/', '', $lat_tmp1[1]);
    
    					$lng_tmp1 = explode("=",$lng_tmp[0]);
    					$lng = preg_replace('/"/', '', $lng_tmp1[1]);
                      				
              //echo "<br>first=".$firstdata_flag;                                        
      				if($firstdata_flag==0)
      				{					
      					$firstdata_flag = 1;
      							
                $lat1 = $lat;
                $lng1 = $lng;
      							 
      					//echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
      					$interval = (double)$user_interval*60;							
      
      					$time1 = $datetime;					
      					$date_secs1 = strtotime($time1);					
      					//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
      					$date_secs1 = (double)($date_secs1 + $interval); 
                $date_secs2 = 0;             
                //echo "<br>FirstData:".$date_secs1;                 	
      				}           	
      				//echo "<br>k2=".$k2."<br>";
                  	
      				else
      				{                           					
               // echo "<br>Total lines orig=".$total_lines." ,c=".$c;
                $time2 = $datetime;											
      					$date_secs2 = strtotime($time2);	
      					//echo "<br>Next".$date_secs2;
      								
      					$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
      					$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);
      				  
      					//$vehiclename_tmp1 = explode("=",$vehiclename_tmp[0]);
      					//$vname = preg_replace('/"/', '', $vehiclename_tmp1[1]);														                                      													      					
      					$lat2 = $lat;      				        					
      					$lng2 = $lng;  
      				  
      					calculate_distance($lat1, $lat2, $lng1, $lng2, &$distance);
      								      					
                //if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")
                //echo '<br>Time:'.$datetime.' ,lat1='.$lat1.' ,lng1='.$lng1.', lat2='.$lat2.' ,lng2='.$lng2.' ,dist='.$distance.' totaldist='.$total_dist;                         
                //if($distance>0.025)                               
                $tmp_time_diff = (strtotime($datetime) - strtotime($last_time)) / 3600;
                if($tmp_time_diff>0)
                {
                  $tmp_speed = $distance / $tmp_time_diff;
                }
                
                $last_time = $datetime;                
                
                //if($tmp_speed<3000 && $distance>0.1)
                if($tmp_speed<500 && $distance>0.1 && $tmp_time_diff>0)
      					{														
                  $total_dist = (float) ($total_dist + $distance);
                                    	
                  //echo "<br>dist greater than 0.025: dist=".$total_dist." time2=".$time2;
      						$lat1 = $lat2;
      						$lng1 = $lng2;
      						
                  //////// TMP VARIABLES TO CALCULATE LAST XML RECORD  //////
                  $vname_tmp  = $vname;
                  $vserial_tmp = $vserial;
                  $time1_tmp = $time1;
                  $time2_tmp = $time2;
                  $total_dist_tmp = $total_dist;    			
                  ////// TMP CLOSED	////////////////////////////////////////		    						
      					}
      					// $total_dist = round($total_dist,2);							                             				  
      					//echo "<br>Next:time2=".$time2." ,Prev:time1=".$time1;
      					
                if($date_secs2 >= $date_secs1)
      					{	
                 /* echo "<br><br>1:data_sec2=".$date_secs2." <br>data_sec1=".$date_secs1;
                  echo "<br>time2=".$time2." <br>time1=".$time1;*/									
      						if(($interval>=1800) && ($total_dist<0.2))
      						{
      							$total_dist = 0.0;
      						} 
      						else
      						{
      							$total_dist = round($total_dist,3);
      						}
      						
      						//if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")      						
                  //echo '<br>In DistanceAction1: dist='.$total_dist.'<br><br>';
                                  						
      						//$distance_data = "\n<marker vname=\"".$vname."\" imei=\"".$vserial."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" distance=\"".$total_dist."\"/>";						          						
      						//echo "<br>distance_data=".$distance_data;
      						$csv_string = $csv_string.$sno.','.$time1.','.$time2.','.$total_dist."\n";
                  //echo "\ncsv1=".$csv_string."\n";
                  $overall_dist = $overall_dist + $total_dist;
                  $sno++;
                  //$linetowrite = $distance_data; // for distance       // ADD DISTANCE
      						//fwrite($fh, $linetowrite);  		
      					
      						//reassign time1
      						$time1 = $datetime;
      						$date_secs1 = strtotime($time1);
      						$date_secs1 = (double)($date_secs1 + $interval);		    									    						    						
      						//echo "<br>datesec1=".$datetime;    						                  
                  $total_dist = 0.0;	 						
      						///////////////////////																
      					}  //if datesec2 
                
                /////
                /*else if( $c == ($total_lines-2) )
                {
                  $total_dist = round($total_dist,3);
          				$distance_data = "\n<marker vname=\"".$vname."\" imei=\"".$vserial."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" distance=\"".$total_dist."\"/>";						          						
          				//echo "<br>speed_data=".$speed_data;
          				$linetowrite = $distance_data; // for distance       // ADD DISTANCE
          				fwrite($fh, $linetowrite);  
                  break;		                                   
                } */
                /////		              
                //echo "<br>Total lines orig=".$total_lines." ,c=".$c;                                          									                               
      				}   // else closed    				               				    				
      			} // $xml_date_current >= $startdate closed
      			
      			else if( ($xml_date > $enddate) && ($xml_date!="-") && ($DataValid==1) )
      			{
               //echo "<br><br>2:data_sec2=".$date_secs2." <br>data_sec1=".$date_secs1;
               //echo "<br>time2=".$time2." <br>time1=".$time1;			
              if(($interval>=1800) && ($total_dist<0.2))
      				{
      					$total_dist = 0.0;
      				}
      				else
      				{
      					$total_dist = round($total_dist,3);
      				}
              //echo "<br>EndData: dist=".$total_dist;
              	
      				//$distance_data = "\n<marker vname=\"".$vname."\" imei=\"".$vserial."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" distance=\"".$total_dist."\"/>";						          						
      				$csv_string = $csv_string.$sno.','.$time1.','.$time2.','.$total_dist."\n";
              //echo "\ncsv2=".$csv_string."\n";
              $overall_dist = $overall_dist + $total_dist;
              $sno++;
              //echo "<br>speed_data=".$speed_data;
      				//$linetowrite = $distance_data; // for distance       // ADD DISTANCE
      				//fwrite($fh, $linetowrite);  		
      			
      				//reassign time1
      				$time1 = $datetime;
      				$date_secs1 = strtotime($time1);
      				$date_secs1 = (double)($date_secs1 + $interval);		
      				$total_dist = 0.0;
      				break;
      			}                			
      		}   // if xml_date!null closed
              		    		    		
      		$j++;
      		    		
          ///// PRINT FINAL DISTANCE
          //echo "<br>Total lines orig=".$total_lines." ,c=".$c;
          /*if(($c==($total_lines-1)) && ($i==($date_size-1)))
          {

            $total_dist_tmp = round($total_dist_tmp,2); 
            
      			$distance_data = "\n<marker vname=\"".$vname_tmp."\" imei=\"".$vserial_tmp."\" datefrom=\"".$time1_tmp."\" dateto=\"".$time2_tmp."\" distance=\"".$total_dist_tmp."\"/>";						          						
      			//echo "<br>speed_data=".$speed_data;
      			$linetowrite = $distance_data; // for distance       // ADD DISTANCE
      			fwrite($fh, $linetowrite);  
          } */     
          ////////// FINAL DIST CLOSED          		
      	}   // while closed
      } // if original_tmp closed     	
    	    	      				
     fclose($xml);            
		 unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 
	
	//echo "Test1";
	//fclose($fh);
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


date_default_timezone_set('Asia/Calcutta');	
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
                                  "alert_id='$alert_db' AND escalation_id='$escalation_id[$i]'";
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
                                    "alert_id='$alert_db' AND escalation_id='$escalation_id[$i]'";
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
              $query_update2 = "insert into report_last_mail_status(last_datetime,vehicle_id,alert_id,escalation_id)".
                              "VALUES('$last_datetime','$vid_db','$alert_db','$escalation_id[$i]')";
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
      $download_file = "distance_report_".$current_dt_tmp1.$i.".csv";
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
      
      $email_from_d="iespltest@gmail.com";
    	$email_subject_d="Distance Report:".$date1." -".$date2;
    	$email_message_d="Distance Report:".$date1." -".$date2." (".$vname_str.")";
    	$email_to_d = $person_email[$i];  //"rizwan@iembsys.com";
    	
      $fileatt_d = $fullPath;
      $fileatt_type_d = "csv";
      $fileatt_name_d = "Distance Report:".$date1." -".$date2." (".$vname_str.").csv";	
      
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

echo "\n DISTANCE LOGIC CLOSED\n";        
		 
?>