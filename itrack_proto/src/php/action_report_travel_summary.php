<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];

set_time_limit(600);

include_once("get_all_dates_between.php");
include_once("sort_xml.php");
include_once("calculate_distance.php");
include_once("read_filtered_xml.php");
include_once("get_location.php");
include_once("report_title.php");
include_once("user_type_setting.php");
include_once("select_landmark_report.php");
include_once("util.hr_min_sec.php");

$DEBUG = 0;

$v_size=count($vehicle_serial);

if($DEBUG) echo "vsize=".$v_size;

$device_str= $_POST["vehicleserial"];
$vserial = explode(':',$device_str);
//echo $vserial[0];
//$vehicleid_size=sizeof($vehicleid);

$date1 = $_POST["start_date"];
$date2 =  $_POST["end_date"];

$date1 = str_replace('/', '-', $date1);  
$date2 = str_replace('/', '-', $date2); 

$date_1 = explode(" ",$date1);
$date_2 = explode(" ",$date2);

//$datefrom = $date_1[0];
//$dateto = $date_2[0];

$datefrom = $date1;
$dateto = $date2;

//echo "<br>datefrom=".$datefrom." dateto=".$dateto;
$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);

//date_default_timezone_set("Asia/Calcutta");
$current_date = date("Y-m-d");
//print "<br>CurrentDate=".$current_date;
//$date_size = sizeof($userdates);
//echo "<br>datesize=".$date_size."<br> v_size=".$v_size;
$k1 = 0;
$m_start=0;

$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);

$threshold = $_POST['threshold'];
$threshold = $threshold * 60;
//echo "threshold:".$threshold;


for($i=0;$i<sizeof($vserial);$i++)
{
    /*$query = "SELECT vehicle_name FROM vehicle WHERE ".
    " vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$vserial[$i]' AND status=1) AND status=1";
    //echo $query;
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $vname[$i] = $row->vehicle_name;*/
    $vehicle_info=get_vehicle_info($root,$vserial[$i]);
    $vehicle_detail_local=explode(",",$vehicle_info);	
    $vname[$i] = $vehicle_detail_local[0];
}

$current_dt = date("Y_m_d_H_i_s");	
$xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";

write_travel_report_xml($vserial, $vname, $date1, $date2, $threshold, $xmltowrite);

function write_travel_report_xml($vserial, $vname, $startdate, $enddate, $threshold, $xmltowrite)
{
  $maxPoints = 1000;
	$file_exist = 0;

	$fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
	fwrite($fh, "<t1>");  
	fclose($fh);

	//$i=0;
	for($i=0;$i<sizeof($vserial);$i++)
	{  	
     //echo   "<br>vserial[i] =".$vserial[$i];
     get_travel_xml_data($vserial[$i], $vname[$i], $startdate,$enddate,$threshold, $xmltowrite);
    //echo   "t2".' '.$i;
	}  
 
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}

function get_travel_xml_data($vehicle_serial, $vname, $startdate,$enddate,$datetime_threshold, $xmltowrite)
{
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

	$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append

	for($i=0;$i<=($date_size-1);$i++)
	{
    $xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
    		
    if (file_exists($xml_current))      
    {		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = "../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}
		
		//echo "<br>xml in get_halt_xml_data =".$xml_file;	    	
    if (file_exists($xml_file)) 
		{			
      //echo "<br>file_exists1";     
      $t=time();
      $xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
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
        $xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
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
	fclose($fh);
	//fclose($xmllog);
}                                                                                                                                                           


  function newTravel($vserial, $vname, $datetime_S, $datetime_E, $distance, $lat_travel_start, $lng_travel_start, $lat_travel_end, $lng_travel_end, $distance_travel, $fh, $linetowrite)
  {
    $travel_dur =  strtotime($datetime_E) - strtotime($datetime_S);                                                    
    $hms = secondsToTime($travel_dur);
    $travel_time = $hms[h].":".$hms[m].":".$hms[s];
    
    $distance_travel = round($distance_travel,2);
       
    //echo "\t\t\t\tTravel : " . $datetime_S . " to " . $datetime_E . "( " . $distance . " )<br>";
    $total_travel = "\n< marker imei=\"".$vserial."\" vname=\"".$vname."\" time1=\"".$datetime_S."\" time2=\"".$datetime_E."\" lat_start=\"".$lat_travel_start."\" lng_start=\"".$lng_travel_start."\" lat_end=\"".$lat_travel_end."\" lng_end=\"".$lng_travel_end."\" distance_travelled=\"".$distance_travel."\" travel_time=\"".$travel_time."\"/>";						          						
    //echo "<br>total travel =".$total_travel;
    $linetowrite = $total_travel; // for distance       // ADD DISTANCE
    fwrite($fh, $linetowrite); 
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
  
  /*function calculate_distance($lat1, $lat2, $lng1, $lng2)
  {
  	// debug("Function Input: " . $lat1 . "," . $lng1 . "\t" . $lat2 . "," . $lng2);
  
    $lat1 = gps_deg2rad($lat1);
    $lng1 = gps_deg2rad($lng1);
  
    $lat2 = gps_deg2rad($lat2);
    $lng2 = gps_deg2rad($lng2);
  
  	// debug("Radian Data: " . $lat1 . "," . $lng1 . "\t" . $lat2 . "," . $lng2);
  
    $delta_lat = $lat2 - $lat1;
    $delta_lng = $lng2 - $lng1;
  
    // Find the Great Circle distance
    $temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lng/2.0),2);
    // $distance = 6378.1 * 2 * atan2(sqrt($temp),sqrt(1-$temp));
    $distance = 6378.1 * 2 * atan2(sqrt($temp),sqrt(1-$temp));
  
    return $distance;
  }
  
  function gps_deg2rad($gps_deg)
  {
  	$direction = substr($gps_deg, -1);
  	if(($direction=="S") || ($direction=="W"))
  	{
  		$deg = "-" . substr($gps_deg, 0, -1);
  	}
  	else
  	{
  		$deg = "+" . substr($gps_deg, 0, -1);
  	}
  	$rad = deg2rad($deg);
  	// debug("GPS Deg: " . $gps_deg . "\tDeg: " . $deg . "\tRad: " . $rad);
  	return $rad;
  }*/
  
   echo '<center>';
    
		
    include("map_window/mapwindow_jsmodule.php");		
    include("map_window/floating_map_window.php");
    
    $size_vserial = sizeof($vserial);
        			
		echo'<br>';
		$threshold = $threshold/60;
		
    $param1 = $date1;
    $param2 = $date2."&nbsp;-Interval:".$threshold." mins";
    report_title("Travel Report",$param1,$param2);
		
    echo'<div style="overflow: auto;height: 480px;" align="center">';
       
		$alt ="-";
						
    ///////////////////  READ HALT XML 	//////////////////////////////				                      
    $xml_path = $xmltowrite;
    //echo "<br>xml_path=".$xml_path;
		read_travel_xml($xml_path, &$imei, &$vname, &$time1, &$time2, &$lat_start, &$lng_start, &$lat_end, &$lng_end, &$distance_travelled, &$travel_time);
		
    //convert_in_two_dimension
    //echo "<br><br>size, imei=".sizeof($imei);
		//////////////////////////////////////////////////////////////////////
    $j=-1;
    
    $vsize = sizeof($imei);
            
    for($i=0;$i<sizeof($imei);$i++)
		{				
      //echo "<br>i=".$i;
      /*echo "<br>a".$i."=".$vname[$i];
      echo "<br>lat".$i."=".$lat[$i];
      echo "<br>lng".$i."=".$lng[$i];
      echo "<br>arrival_time".$i."=".$arrival_time[$i];
      echo "<br>dep_time".$i."=".$dep_time[$i];
      echo "<br>duration".$i."=".$duration[$i]; */
            
      if(($i===0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
      {
        $k=0;
        $j++;
        $sno = 1;
        $title="Travel Report : ".$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
        $vname1[$j][$k] = $vname[$i];
        $imei1[$j][$k] = $imei[$i];               
        
        echo'
        <br><table align="center">
        <tr>
        	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
        </tr>
        </table>
        <table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
        <tr>
        	<td class="text" align="left" width="3%"><b>SNo</b></td>
        	<td class="text" align="left"><b>Start Time</b></td>
        	<td class="text" align="left"><b>End Time</b></td>
        	<td class="text" align="left"><b>Start Place</b></td>
          <td class="text" align="left"><b>End Place</b></td>
        	<td class="text" align="left"><b>Distance Travelled(km)</b></td>
        	<td class="text" align="left"><b>Travel Time(H:m:s)</b></td>
        	<td class="text" align="left"><b>Halt Time</b></td>
        	<td class="text" align="left"><b>Halt Duration(H:m:s)</b></td>
        </tr>';  								
      }
            
     	//include("get_location_test.php");      
              	
			//location 1
      $lt1 = $lat_start[$i];
			$lng1 = $lng_start[$i];
			$alt1 = "-";								
			 
      $landmark="";
      get_landmark($lt1,$lng1,&$landmark);    // CALL LANDMARK FUNCTION
  		
      $place1 = $landmark;
      
      if($place1=="")
      {
        get_location($lt1,$lng1,$alt1,&$place1,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
      }
      
			//location2
      $lt2 = $lat_end[$i];
			$lng2 = $lng_end[$i];
			$alt2 = "-";								
			 
      $landmark="";
      get_landmark($lt2,$lng2,&$landmark);    // CALL LANDMARK FUNCTION
  		
      $place2 = $landmark;
      
      if($place2=="")
      {
        get_location($lt2,$lng2,$alt2,&$place2,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
      }      
  		
      //echo "P:".$place;
      
      $location1 = $place1;
      $location2 = $place2;	
																
			echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';												
			echo'<td class="text" align="left">'.$time1[$i].'</td>';			
			echo'<td class="text" align="left">'.$time2[$i].'</td>';
      			
      //location1
      if($location1=="")
			{
				echo'<td class="text" align="left">-</td>';
			}
			else
			{																													
        //$lt_tmp = substr($lat_start[$i], 0, -1);
        //$lng_tmp = substr($lng_start[$i], 0, -1);
        //$type = "travel";
        //echo'<td class="text" align="left">'.$placename[$i].'&nbsp;<a href="javascript:map_window(\''.$vname[$i].'\',\''.$arr_time[$i].'\',\''.$dep_time[$i].'\',\''.$lt_tmp.'\',\''.$lng_tmp.'\',\''.$type.'\');"><font color="green">&nbsp;(Show on map)</font></a></td>';
        echo'<td class="text" align="left">'.$location1.'</td>';        
			}
      
      //location2
      if($location2=="")
			{
				echo'<td class="text" align="left">-</td>';
			}
			else
			{																													
        //$lt_tmp = substr($lat_end[$i], 0, -1);
        //$lng_tmp = substr($lng_end[$i], 0, -1);
        //$type = "travel";
        //echo'<td class="text" align="left">'.$placename[$i].'&nbsp;<a href="javascript:map_window(\''.$vname[$i].'\',\''.$arr_time[$i].'\',\''.$dep_time[$i].'\',\''.$lt_tmp.'\',\''.$lng_tmp.'\',\''.$type.'\');"><font color="green">&nbsp;(Show on map)</font></a></td>';
        echo'<td class="text" align="left">'.$location2.'</td>';        
			}      									
			
			echo'<td class="text" align="left"><b>'.$distance_travelled[$i].'</b></td>';
      echo'<td class="text" align="left"><b>'.$travel_time[$i].'</b></td>';
      
      
      //GET HALT TIME AND DURATION
      
      if($i < sizeof($imei)-1)
      {
        $halt_range[$i] = $time2[$i]." to ".$time1[$i+1];
        
        $halt_dur_tmp =  strtotime($time1[$i+1]) - strtotime($time2[$i]);                                                    
        $hms = secondsToTime($halt_dur_tmp);
        $halt_duration[$i] = $hms[h].":".$hms[m].":".$hms[s];
      }
                  
      echo'<td class="text" align="left">'.$halt_range[$i].'</td>';	
      echo'<td class="text" align="left"><b>'.$halt_duration[$i].'</b></td>';							
			echo'</tr>';							
			
			//echo "<br>msg1";
      $time1_a[$j][$k] =  $time1[$i];					
			$time2_a[$j][$k] = $time2[$i];				
      $location1_a[$j][$k] = $location1;
			$location2_a[$j][$k] = $location2;        
			$distance_travelled1[$j][$k] = $distance_travelled[$i];
			$travel_time1[$j][$k] = $travel_time[$i];
			
			$halt_range1[$j][$k] = $halt_range[$i];
			$halt_duration1[$j][$k] = $halt_duration[$i];
			//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
			
      //echo "<br>msg2";
      if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
      {
        $no_of_data[$j] = $k;
        echo '</table><br>';
      }    	
  		//echo "<br>msg3";
      	
			$k++;   
      $sno++;      				  				
		}
		
		//echo "<br>loop end=".$j;
		
    if($j==0)
      echo '</table><br>';							
		//PDF CODE

		echo '<form method="post" target="_blank">';
		
    $csv_string = "";    
          
    for($x=0;$x<=$j;$x++)
		{												
        for($y=0;$y<=$no_of_data[$x];$y++)
        {
          $alt_ref="-";
          
          //echo "<br>arr_time1[$x][$y]=".$arr_time1[$x][$y];                    
          $pdf_time1 = $time1_a[$x][$y];
          $pdf_time2 = $time2_a[$x][$y];
          $pdf_place1 = $location1_a[$x][$y];
          $pdf_place2 = $location2_a[$x][$y];
          $pdf_distance = $distance_travelled1[$x][$y];
          $pdf_travel_time = $travel_time1[$x][$y];
          
          $pdf_halt_range = $halt_range1[$x][$y];
          $pdf_halt_duration = $halt_duration1[$x][$y];          
          
          if($y==0)
          {
          	$title="Travel Report : ".$vname1[$x][$y]." (".$imei1[$x][$y].") (Interval:".$threshold." mins)";
          	//echo "<br>pl=".$pdf_place_ref;
          	$csv_string = $csv_string.$title."\n";
            $csv_string = $csv_string."SNo,StartTime,EndTime,StartPlace,EndPlace,Distance Travelled(km), Travel Time(H:m:s)\n";            
            
            echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";
          }
          														
          $sno_1 = $y+1;										
          echo"<input TYPE=\"hidden\" VALUE=\"$sno_1\" NAME=\"temp[$x][$y][SNo]\">";          
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_time1\" NAME=\"temp[$x][$y][Start Time]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_time2\" NAME=\"temp[$x][$y][End Time]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_place1\" NAME=\"temp[$x][$y][Start Place]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_place2\" NAME=\"temp[$x][$y][End Place]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_distance\" NAME=\"temp[$x][$y][Distance Travelled (km)]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_travel_time\" NAME=\"temp[$x][$y][Travel Time (H:m:s)]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_halt_range\" NAME=\"temp[$x][$y][Halt Range]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_halt_duration\" NAME=\"temp[$x][$y][Halt Duration (H:m:s)]\">";          
                
          /// CODE FOR CSV
          $pdf_place1 = str_replace(',',':',$pdf_place1);
          $pdf_place2 = str_replace(',',':',$pdf_place2);
          //echo "<br>".$pdf_place_ref;
          $csv_string = $csv_string.$sno_1.','.$pdf_time1.','.$pdf_time2.','.$pdf_place1.','.$pdf_place2.','.$pdf_distance.','.$pdf_travel_time.','.$pdf_halt_range.','.$pdf_halt_duration."\n"; 
          ////////////////////////////////////         	
        }	//inner for	
		} // outer for		
	
    	
		if(sizeof($imei)==0)
		{						
			print"<center><FONT color=\"Red\" size=2><strong>No Travel Record Found</strong></font></center>";
			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
			echo'<br><br>';
		}	
		else
		{
      echo'<input TYPE="hidden" VALUE="travel" NAME="csv_type">';
      echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
      echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;
      <input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
      <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';
    }
   	echo '</form>';


		echo'</td></tr></table>';   
    echo '</div>';
    
    /*if($report_type=='Person')
    {
      echo'<form method = "post" name="csv_form" action="src/php/report_csv.php" target="_blank">';
        echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';        
      echo '</form>';
    }	*/     
    
unlink($xml_path);    

echo '</center>';      	
?>					