<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once("get_all_dates_between.php");
include_once("sort_xml_school.php");
include_once("calculate_distance.php");
include_once("read_filtered_xml.php");

$DEBUG = 1;
$device_str = $_POST['vehicleserial'];
$school_id = $_POST['school_id']; 
$vserial = explode(':',$device_str);
$vsize=count($vserial);
$date1 = $_POST['start_date'];
$date2 = $_POST['end_date'];
$date1 = str_replace("/","-",$date1);
$date2 = str_replace("/","-",$date2);


//Code to get list of shift and routes

for($l=0;$l<sizeof($vserial);$l++)
{
  //====Getting Vehicle ID from Table vehicle_assignment using list of device_imei_no ==================//
  //====and from table bus assignment to get shiftID and Bus Route ID===================================//
  //====***and from ShiftID to get Shift Name, Shift Start Time, Shift Stop Time from Shift Table===============//
  //====***and from RouteID to get  list of BusStopID from table busstop_assignment==================================//
  //====**from list of busstop ID to get arrivaltime from bus_arrival table and busstop_name,longitude,latitude from busstop===================================//
  $query1="SELECT vehicle_id from vehicle_assignment where device_imei_no='$vserial[$l]' AND status='1'";
	//echo "query1=".$query1."<br>";
	$result1=mysql_query($query1,$DbConnection);
	$row_result1=mysql_num_rows($result1);		
	if($row_result1!=null)
	{
		$row1=mysql_fetch_object($result1);
		$bus_id= $row1->vehicle_id;   
    //$query2="SELECT distinct bus_serial,shift.shift_id,shift.shift_name,busroute_id from bus_assignment,shift where bus_serial='$bus_id' AND bus_assignment.shift_id=shift.shift_id AND bus_assignment.status='1' AND shift.status='1'";
		$query2="SELECT * from bus_assignment where bus_serial=$bus_id AND school_id=$school_id AND status='1'";
    //echo "query2=".$query2."<br>";
    $result2=mysql_query($query2,$DbConnection);
    $row_result2=mysql_num_rows($result2);		
    if($row_result2!=null)
    {
    	while($row2=mysql_fetch_object($result2))
    	{									
    		$bus_serial=$row2->bus_serial;
    		$shift_id=$row2->shift_id;		
				$busroute_id=$row2->busroute_id;
				$query4="SELECT shift_name,shift_starttime,shift_stoptime from shift where shift_id='$shift_id' AND status='1'";
			  //echo "query=".$query4."<br>";
				$result4=mysql_query($query4,$DbConnection);
				$row_result4=mysql_num_rows($result4);
				if($row_result4!=null)
				{
					$result5 = mysql_fetch_object($result4);
					$shift_name = $result5->shift_name;
					$shift_starttime = $result5->shift_starttime;
					$shift_stoptime = $result5->shift_stoptime;
				}
        //$query3="SELECT distinct busstop.busstop_id,busstop.busstop_name,busstop.longitude,busstop.latitude,bus_arrival.arrival_time from busstop_assignment,busstop,bus_arrival where busstop_assignment.busroute_id='$busroute_id' AND bus_arrival.busstop_id=busstop_assignment.busstop_id AND busstop.busstop_id=busstop_assignment.busstop_id AND bus_arrival.status='1' AND busstop_assignment.status='1' AND busstop.status='1'";
  			//echo "query=".$query3."<br>";
				$query3="SELECT busstop_id from busstop_assignment where busroute_id='$busroute_id' AND status='1'";
  			$result3=mysql_query($query3,$DbConnection);
  			$row_result3=mysql_num_rows($result3);		
        if($row_result3!=null)
        {
          while($row3=mysql_fetch_object($result3))
          {
						$busstop_id=$row3->busstop_id;
						$query4="SELECT arrival_time from bus_arrival where bus_serial='$bus_id' AND busstop_id='$busstop_id' AND shift_id='$shift_id' AND status='1'";
						//echo "query4=".$query4."<br>";
						$result4=mysql_query($query4,$DbConnection);
						$row_result4=mysql_num_rows($result4);
						if($row_result4!=null)
						{
							$bus_arrival = mysql_fetch_object($result4)->arrival_time;
						}
						$query4="SELECT busstop_name,longitude,latitude from busstop where busstop_id='$busstop_id' AND status='1'";
						$result4=mysql_query($query4,$DbConnection);
						$row_result4=mysql_num_rows($result4);
						if($row_result4!=null)
						{
							$result5 = mysql_fetch_object($result4);
							$bus_lat = $result5->latitude;
							$bus_long = $result5->longitude;
							$busstop_name = $result5->busstop_name;
						}
				    //assignning it to an array
            $busstop_ids[]=$busstop_id; //this will be used in size in loop
            $busstop_names[]=$busstop_name;			
            $longitudes[]=$bus_long;
            $latitudes[]=$bus_lat;
            $arrival_times[]=$bus_arrival;
            $bus_ids[]=$bus_id;
            $shift_ids[]=$shift_id;
            $shift_names[]=$shift_name;
            $shift_starttimes[]=$shift_starttime;
            $shift_stoptimes[]=$shift_stoptime;
            $route_ids[]=$busroute_id;
            $bus_serials[]=$vserial[$l];
          }    
        }	// row_result3 close		
      }    
    } // row_result2 close     
  }// row_result1  close
}
/*
for($i=0;$i<sizeof($busstop_ids);$i++)
{
  echo "busstop_id=".$busstop_ids[$i]."<br>";
  echo "busstop_name=".$busstop_names[$i]."<br>";
  echo "longitude=".$longitudes[$i]."<br>";
  echo "latitude=".$latitudes[$i]."<br>";
  echo "arrival_time=".$arrival_times[$i]."<br>";
  echo "bus_id=".$bus_ids[$i]."<br>";
  echo "shift_id=".$shift_ids[$i]."<br>";
  echo "shift_name=".$shift_names[$i]."<br>";
  echo "shift_starttime=".$shift_starttimes[$i]."<br>";
  echo "shift_stoptime=".$shift_stoptimes[$i]."<br>";
  echo "route_id=".$route_ids[$i]."<br>";
  echo "bus_serial=".$bus_serials[$i]."<br>";
}
*/
////////////////////////////////////////////////////////////////////////////

function calculate_mileage($lat1, $lat2, $lon1, $lon2, &$distance) 
{	
	// used internally, this function actually performs that calculation to
	// determine the mileage between 2 points defined by lattitude and
	// longitude coordinates.  This calculation is based on the code found
	// at http://www.cryptnet.net/fsp/zipdy/
	// Convert lattitude/longitude (degrees) to radians for calculations
	$lat1 = deg2rad($lat1);
	$lon1 = deg2rad($lon1);
	$lat2 = deg2rad($lat2);
	$lon2 = deg2rad($lon2);
	// Find the deltas
	$delta_lat = $lat2 - $lat1;
	$delta_lon = $lon2 - $lon1;
	// Find the Great Circle distance
	$temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
	$distance = 3956 * 2 * atan2(sqrt($temp),sqrt(1-$temp));
	//convert into km
	$distance = $distance*1.609344;
	//echo "dist=".$distance;
	// return $distance;
} 

///////////////////////////////////////////////////////////////////////////////

if($vsize>0)
{
  $current_dt = date("Y_m_d_H_i_s");	
  $xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";
  //echo $xmltowrite;
  write_distance_report_xml($vserial, $date1, $date2, $user_interval, $xmltowrite);
}

function write_distance_report_xml($vserial, $startdate, $enddate, $user_interval, $xmltowrite)
{
  global $DbConnection;
  $maxPoints = 1000;
	$file_exist = 0;
	$fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
	fwrite($fh, "<t1>");  
	fclose($fh);
	//$i=0;
	for($i=0;$i<sizeof($vserial);$i++)
	{  	
     $query1 = "SELECT vehicle_name FROM vehicle WHERE ".
      " vehicle_id=(SELECT vehicle_id FROM vehicle_assignment ".
      "WHERE device_imei_no='$vserial[$i]' AND status=1) AND status=1";
      //echo $query1;
      //echo "<br>DB=".$DbConnection;
      $result = mysql_query($query1,$DbConnection);
      $row = mysql_fetch_object($result);
      $vname[$i] = $row->vehicle_name;
      //echo   "<br>vserial[i] =".$vserial[$i];
      get_distance_xml_data($vserial[$i],$vname[$i], $startdate, $enddate, $user_interval, $xmltowrite);
    //echo   "t2".' '.$i;
	}  
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}

function get_distance_xml_data($vehicle_serial,$vehicle_name, $startdate, $enddate, $user_interval, $xmltowrite)
{
	global $busstop_ids;
	global $busstop_names;			
	global $longitudes;
	global $latitudes;
	global $arrival_times;
	global $bus_ids;
	global $shift_ids;
	global $shift_names;
	global $shift_starttimes;
	global $shift_stoptimes;
	global $route_ids;
	global $bus_serials;
 	
	$old_busstop_name="";
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
   //echo"xmltowrite".$xmltowrite; 
	$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append
	$j = 0;
	$total_dist = 0.0;	
  //echo"Datasize=".$date_size;						
  for($i=0;$i<=($date_size-1);$i++)
	{
	  //echo"hello";
	  //echo "<br>DATE SIZE=".$date_size-1;	
		//if($userdates[$i] == $current_date)
		//{			
    $xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
    if (file_exists($xml_current))      
    {		
			//echo "in else". $xml_current;	;
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
		  //$current_datetime1 = date("Y_m_d_H_i_s");
		  $t=time();
      //$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$current_datetime1.".xml";
      $xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
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
      $format =2;
      
      
      if (file_exists($xml_original_tmp)) 
      {   
        //echo"insideif<br>";   
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
            //  echo "<textarea>".$line."</textarea>"; 
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
                           
              //echo "<br>first=".$firstdata_flag;                                        
                                      
      				$time2 = $datetime;											
      				$date_secs2 = strtotime($time2);	
      							
      				$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
      				$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);
                    
      				//$vehiclename_tmp1 = explode("=",$vehiclename_tmp[0]);
      				//$vname = preg_replace('/"/', '', $vehiclename_tmp1[1]);
              $vname=$vehicle_name;//"My Vehicle";														                                
                      								
      				$lat_tmp1 = explode("=",$lat_tmp[0]);
      				$lat2 = preg_replace('/"/', '', $lat_tmp1[1]);
                    
      				$lng_tmp1 = explode("=",$lng_tmp[0]);
      				$lng2 = preg_replace('/"/', '', $lng_tmp1[1]);
              
              $CurrentLat = $lat2;
      	      $CurrentLong = $lng2;   
                  				
         
              // to calculate bus values
            //echo "<br>size of Bus report=".sizeof($busstop_ids);
             for($i=0;$i<sizeof($busstop_ids);$i++)
             {
             // echo"<br>Count".$i;
              if($bus_serials[$i]==$vserial)
              {
                //echo"get serial<br>";
                if($old_busstop_name != $busstop_names[$i])
				          {
				             //echo"get old<br>";
                    calculate_distance($CurrentLat, $latitudes[$i], $CurrentLong, $longitudes[$i], &$distance);
          				  if($distance > 0.300)
          				  {
  							        //echo"less<br>";
            					$shiftname="";
  							      $date1 = explode(' ',$xml_date);		
        							//for($m=0;$m<sizeof($busstop_ids);$m++)
        							{							
        								//$time_start=$date1[0]." ".$shift_starttimes[$m];
        								//$time_stop=$date1[0]." ".$shift_stoptimes[$m];
        								$time_start=$date1[0]." ".$shift_starttimes[$i];
        								$time_stop=$date1[0]." ".$shift_stoptimes[$i];
        							   //echo"<br>time_start :".$i."  ".$time_start;
        								//echo"<br>time_stop :".$i."  ".$time_stop;
        								if($xml_date > $time_start && $xml_date < $time_stop)
        								{
        									//$shiftname=$shift_names[$m];
        									$shiftname=$shift_names[$i];
        									//echo"<br>shiftname :".$i."  ".$shiftname;
        									//$busstopname=$busstop_names[$m];
        									//$arrivaltime=$arrival_times[$m];
        									$busstopname=$busstop_names[$i];
        									$arrivaltime=$arrival_times[$i];
        									
        								}
        							}
                    
        							if($shiftname!="")
        							{
                        //echo"insideshift<br>";
                        //$bus_data = "\n<marker vname=\"".$vname."\" date=\"".$date1[0]."\" busstop=\"".$busstopname."\" arrival=\"".$date1[1]."\"  scheduledtime=\"".$arrivaltime."\"  shift=\"".$shiftname."\"/>";		
                        $bus_data = "\n<marker vname=\"".$vname."\" date=\"".$date1[0]."\" busstop=\"".$busstopname."\" arrival=\"".$date1[1]."\"  scheduledtime=\"".$arrivaltime."\"  shift=\"".$shiftname."\" latlngpos=\"".$CurrentLat."-".$CurrentLat."\"/>";	
                        $linetowrite = $bus_data; // for Bus Performance 
                        fwrite($fh, $linetowrite); 
                        $old_busstop_name = $busstop_names[$i];
        							}
          				  }
					         }// code for old bus stop name closed
                }
              }
  					} // $xml_date_current >= $startdate closed
  				}   // if xml_date!null closed
  			 $j++;
        }   // while closed
      } // if original_tmp closed 
			
     fclose($xml);            
		 unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 
	//echo "Test1";
	fclose($fh);
}


  echo '<center>';

	
	  
  echo'<br><br>';
  echo'
	<div style="overflow: auto;height: 320px; width: 820px;" align="center">';	

  ///////////////////  READ SPEED XML 	//////////////////////////////				                      
  $xml_path = $xmltowrite;            
  //echo "<br>xml_path=".$xml_path;
  
  read_bus_xml($xml_path, &$vname, &$datefor, &$busstop, &$arrival, &$scheduledtime, &$shiftname, &$satelliteposition);
	//read_distance_xml($xml_path, &$vname, &$datefrom, &$dateto, &$distance);
	//convert_in_two_dimension
  //echo "<br><br>size, vname=".sizeof($vname);
	//////////////////////////////////////////////////////////////////////
  $j=-1;
  $k=0;
  			             
  for($i=0;$i<sizeof($vname);$i++)
	{								              
     //echo"hello";
    /*$arTime=$datefor[$i]+" "+$arrival[$i];
    $shTime=$datefor[$i]+" "+$scheduledtime[$i];
    
    $diff = abs(strtotime($arTime) - strtotime($shTime)); 
    
    echo 'diff = '.$diff; */
    
    // Extract $arrival H:m:ss
    $current_hour = substr($arrival[$i],0,2);
    $current_min = substr($arrival[$i],3,2);
    $current_seconds = substr($arrival[$i],6,2);
    
    // Extract $scheduledtime Date H:m:ss
    $ref_hour = substr($scheduledtime[$i],0,2);
    $ref_min = substr($scheduledtime[$i],3,2);
    $ref_seconds = substr($scheduledtime[$i],6,2);
    
    $hDf = $current_hour-$ref_hour;
    $mDf = $current_min-$ref_min;
    $sDf = $current_seconds-$ref_seconds;
    
    $time_diff="";

    //if($dDf<1){
      if($hDf>0){
        if($mDf<0){
        $mDf = 60 + $mDf;
        $hDf = $hDf - 1;
        //echo 
        $time_diff=$mDf . ' min late';
        } else {
        //echo 
        $time_diff=$hDf. ' hr ' . $mDf . ' min late';
        }
      } else {
          if($mDf>0){
          //echo 
          $time_diff=abs($hDf). ' hr ' .$mDf . ' min ' . $sDf . ' sec late';
          } else {
          //echo 
          $time_diff=abs($hDf). ' hr ' .abs($mDf) . ' min ' .$sDf . ' sec early';
          }
      }
     // } else {
     // echo $dDf . ' days ago';
     // }
    
    
    
    if(($i===0) || (($i>0) && ($vname[$i-1] != $vname[$i])) )
    {
      $k=0;                                              
      $j++;
      $sum_dist =0;
      $total_distance[$j] =0;
      
      $sno = 1;
      $title="Bus Performance Report : ".$vname[$i]." -From DateTime : ($date1) and ($date2)";
      $vname1[$j][$k] = $vname[$i];
      
      echo'
      <br><table align="center" width="100%">
      <tr>
      	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
      </tr>
      </table>
      <table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
      <tr style="height:20px;background-color:lightgrey">
			<td class="text" align="left"><b>SNo</b></td>
			<td class="text" align="left"><b>Bus Name</b></td>
			<td class="text" align="left"><b>Date</b></td>
			<td class="text" align="left"><b>Busstop</b></td>
      <td class="text" align="left"><b>Arrival Time</b></td>
			<td class="text" align="left"><b>Scheduled Arrival</b></td>
			
		<!--	<td class="text" align="left"><b>Time Difference</b></td>     -->
      <td class="text" align="left"><b>Shift</b></td>	
      <td class="text" align="left"><b>Satellite[Lat/Lng]</b></td>							
      </tr>';  								
    }                                                                        		
		
    //$sum_dist = $sum_dist + $distance[$i];
	 // &$busname, &$datefor, &$busstop, &$arrival, &$scheduledtime, &$shiftname
   
   

               
    echo'<tr><td class="text" align="left" width="7%"><b>'.$sno.'</b></td>';        												
		echo'<td class="text" align="left">'.$vname[$i].'</td>';		
    echo'<td class="text" align="left">'.$datefor[$i].'</td>';			
		echo'<td class="text" align="left">'.$busstop[$i].'</td>';	
    echo'<td class="text" align="left">'.$arrival[$i].'</td>';		
    echo'<td class="text" align="left">'.$scheduledtime[$i].'</td>';
   // echo'<td class="text" align="left">'.$time_diff.'</td>';			
		echo'<td class="text" align="left">'.$shiftname[$i].'</td>';	
    echo'<td class="text" align="left">'.$satelliteposition[$i].'</td>';					
		echo'</tr>';	          		
		//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
    
    $vname1[$j][$k] = $vname[$i];	
    $datefor1[$j][$k] = $datefor[$i];										
    $busstop1[$j][$k] = $busstop[$i];
    $arrival1[$j][$k] = $arrival[$i];	
    $scheduledtime1[$j][$k] = $scheduledtime[$i];
    $time_diff1[$j][$k]=$time_diff;										
    $shiftname1[$j][$k] = $shiftname[$i];       			    				  				
	  $latlong1[$j][$k]=$satelliteposition[$i];
	  if( (($i>0) && ($vname[$i+1] != $vname[$i])) )
    {       
      echo '</table>';
		}  
		
    $k++;   
    $sno++;                       							  		
 }
 
 echo "</div>";   
   
 echo'<br><br>';
 
	echo'<form method = "post" action="src/php/report_getpdf_type4.php?size='.$vsize.'" target="_blank">';
	
	for($x=0;$x<=$j;$x++)
	{								
		$title=$vname[$x].": Performance Report From DateTime : ".$date1."-".$date2;
		echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
		
		$sno=0;
		for($y=0;$y<$k;$y++)
		{
			//$k=$j-1;
			$sno++;
                 
      			
			$vnametmp = $vname1[$x][$y];		
      $datefortmp = $datefor1[$x][$y];										
      $busstoptmp = $busstop1[$x][$y];	
      $arrivaltmp = $arrival1[$x][$y];		
      $scheduledtimetmp = $scheduledtime1[$x][$y];
      $time_difftmp=$time_diff1[$x][$y];											
      $shiftnametmp = $shiftname1[$x][$y];	
      $latlongtmp=$latlong1[$x][$y];
							
			//echo "dt=".$datetmp1;								
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$vnametmp\" NAME=\"temp[$x][$y][Bus Name]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datefortmp\" NAME=\"temp[$x][$y][Date]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$busstoptmp\" NAME=\"temp[$x][$y][Busstop]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$arrivaltmp\" NAME=\"temp[$x][$y][Arrival Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$scheduledtimetmp\" NAME=\"temp[$x][$y][Scheduled Arrival]\">";
			//echo"<input TYPE=\"hidden\" VALUE=\"$time_difftmp\" NAME=\"temp[$x][$y][Time Difference]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$shiftnametmp\" NAME=\"temp[$x][$y][Shift]\">";	
      echo"<input TYPE=\"hidden\" VALUE=\"$latlongtmp\" NAME=\"temp[$x][$y][SatellitePosition]\">";																	
		}		
    																																											
	}																						

	echo'
    <table align="center">
		<tr>
			<td><input type="submit" value="Get PDF" class="noprint">&nbsp;<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;</td>
		</tr>
		</table>
		</form>
 ';
					 
unlink($xml_path);		
echo '</center>';			 
?>