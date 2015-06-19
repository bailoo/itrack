<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];

set_time_limit(200);
include_once('common_xml_element.php');
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

$device_str = $_POST['vehicleserial'];
//echo "<br>devicestr=".$device_str;
$vserial = explode(':',$device_str);
$vsize=count($vserial);

$date1 = $_POST['start_date'];
$date2 = $_POST['end_date'];

$date1 = str_replace("/","-",$date1);
$date2 = str_replace("/","-",$date2);


if($vsize>0)
{
  for($i=0;$i<$vsize;$i++)
  {
    /*$query = "SELECT vehicle_name FROM vehicle WHERE ".
    " vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$vserial[$i]' AND status=1) AND status=1";
    //echo $query."<br>";
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $vname[$i] = $row->vehicle_name;*/
    $vehicle_info=get_vehicle_info($root,$vserial[$i]);
	//echo "vehicle_info=".$vehicle_info."<br>";
    $vehicle_detail_local=explode(",",$vehicle_info);	
    $vname[$i] = $vehicle_detail_local[0];
  }
  
  $current_dt = date("Y_m_d_H_i_s");	
  $xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";
  write_area_report_xml($vserial, $vname, $date1, $date2, $xmltowrite);
}

function write_area_report_xml($vserial, $vname, $startdate, $enddate, $xmltowrite)
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
     get_area_xml_data($vserial[$i], $vname[$i], $startdate, $enddate, $xmltowrite);
    //echo   "t2".' '.$i;
	}  
 
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}


function get_area_xml_data($vehicle_serial, $vname, $startdate, $enddate, $xmltowrite)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
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

	get_All_Dates($datefrom, $dateto, $userdates);

	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);

	$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append


	$j = 0;
	$total_dist = 0;
 									
	global $DbConnection;
	global $account_id;
  /*$query_geo = "select LatLongCoord from geofencing_record where ".
  "VehicleID IN(SELECT VehicleID FROM vehicle WHERE VehicleSerial='$vehicle_serial' ".
  "AND UserID='$login')";*/
  
  /*$query_geo = "select geo_coord from geofence where ".
  "geo_id =(SELECT geo_id FROM geo_assignment WHERE vehicle_id = (SELECT vehicle_id FROM vehicle_assignment WHERE ".
  " device_imei_no='$vehicle_serial' AND status=1))";*/
  
  $query_geo = "SELECT geo_coord FROM geofence WHERE geo_id IN(SELECT geo_id FROM geo_assignment WHERE status=1 AND ".
                "vehicle_id IN (SELECT vehicle_id FROM vehicle_assignment WHERE device_imei_no='$vehicle_serial' AND status=1)) AND ".
                "user_account_id='$account_id' AND status=1";   

	//echo "query=".$query_geo."<br>";
    	
              if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")
                echo '<br>$query_geo:'.$query_geo;                         


  $res_geo = mysql_query($query_geo,$DbConnection);
	if($row_geo = mysql_fetch_object($res_geo))
	{
		$geo_coord_tmp = $row_geo->geo_coord;
		$geo_coord = base64_decode($geo_coord_tmp);
		
    $geo_coord = str_replace('),(',' ',$geo_coord);
    $geo_coord = str_replace('(','',$geo_coord);
    $geo_coord = str_replace(')','',$geo_coord);
    $geo_coord = str_replace(', ',',',$geo_coord);
	}	
	//echo "<br>query_geo=".$query_geo." ,<br>geo_record=".$geo_coord;
  $outflag=0;          	
  
  for($i=0;$i<=($date_size-1);$i++)
	{  
    //if($userdates[$i] == $current_date)
		//{	
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
				SortFile($xml_unsorted, $xml_original_tmp,$userdates[$i]);    // SORT FILE
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
      
	    $datetime = null;
      $hrs_min = null;
      $j=0; 
      $v=0;
      $f = 0;     
      
      if (file_exists($xml_original_tmp)) 
      {
		set_master_variable($userdates[$i]);
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
				//echo "vc=".$vc."vd=".$vd."ve=".$ve."<br>";
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
        // echo "dataValid=".$DataValid."<br>";
          //if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
			if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
			{
				//preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
				$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
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
             /* $status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
              //echo "Status=".$status.'<BR>';
              //echo "test1".'<BR>';
              if($status==0)
              {
                continue;
              }*/
              //echo "<textarea>".$line."</textarea>"; 
              //$line = '<marker msgtype="NORMAL" vehicleserial="354776033544005" vehiclename="SJ DKL-4005" ver="v1.34" fix="1" lat="26.60652N" lng="80.20758E" alt="123.0" speed="0.000000" datetime="2011-01-25 03:16:23" fuel="-" vehicletype="Heavy" no_of_sat="09" cellname="" distance="0.0" io1="7" io2="6" io3="387" io4="10" io5="6" io6="6" io7="4" io8="2" sig_str="0" sup_v="0.000000" speed_a="0" geo_in_a="0" geo_out_a="0" stop_a="0" move_a="0" lowv_a="0"/>';  
              /*$status = preg_match('/vehiclename="[^"]+/', $line, $vehiclename_tmp);          
              //echo "<br>vname=".$vehiclename_tmp[0];
              if($status==0)
              {
                continue;
              } */
              //echo "vd=".$vd."<br>";
			 // echo "ve=".$ve."<br>";
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
              //echo "test4".'<BR>';
              /*$status = preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $datetime_tmp);
              if($status==0)
              {
                continue;
              }*/                          
              
             
			$vserial=$vehicle_serial;
              //$vehiclename_tmp1 = explode("=",$vehiclename_tmp[0]);
              //$vname = preg_replace('/"/', '', $vehiclename_tmp1[1]);
              
              $lat_tmp1 = explode("=",$lat_tmp[0]);
              $lat = preg_replace('/"/', '', $lat_tmp1[1]);
              
              $lng_tmp1 = explode("=",$lng_tmp[0]);
              $lng = preg_replace('/"/', '', $lng_tmp1[1]);    
                //echo "geocoord1=".$geo_coord."<br>";         
              if($geo_coord!="")
  		        {                
                check_with_range($lat, $lng, $geo_coord, $status_geo);                                
                //echo "<br><br>lat=".$lat.",lng=".$lng." <br> ,status=".$status;  
                
                
                if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")
                  echo '<br>geo_coord:'.$geo_coord.' ,lat='.$lat.' ,lng='.$lng.', $status='.$status_geo;                         
                                                                                                       						
                //$time2 = $datetime_tmp[0];           									
    						//$date_secs2 = strtotime($time2);                       
                
                if($status_geo==false && $outflag==0)
                {
                  $outflag = 1; 
                  $time1 = $datetime;   							
                  $starttime = strtotime($time1);
                  
                  $lat1 = $lat; 
                  $lng1 = $lng;                 
                }  
                
                else if( ($status_geo==true && $outflag==1) || ($f==$total_lines-2) )
                {
                  $outflag = 0; 
                  $time2 = $datetime;   							
                  $stoptime = strtotime($time2);
              		                
              		$violated_dur = $stoptime - $starttime;
              		/*$hr =  (int)(($violated_dur)/3600);	 
              		$min =  ($violated_dur)%3600;
              		$min =  (int)(($min)/60); 
              		$sec =  (int)(($min)%60);*/
                  
                  $hms = secondsToTime($violated_dur);
                  $duration = $hms[h].":".$hms[m].":".$hms[s];                          		
                  
                  //echo "<br>Bstatus=".$status." ,time2=".$time2.", outflag=".$outflag;	
                  //$area_violated_data = "\n< marker imei=\"".$vserial."\" vname=\"".$vname."\" lat=\"".$lat1."\" lng=\"".$lng1."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" duration=\"".$hr.':'.$min.':'.$sec."\"/>";
                  $area_violated_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" lat=\"".$lat1."\" lng=\"".$lng1."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" duration=\"".$duration."\"/>";
                  //echo "<br>area_violated_data=".$area_violated_data;
                  $linetowrite = $area_violated_data;
                  fwrite($fh, $linetowrite); 		                		                     
                }
              } // if geo_coord!="" closed
              //echo "<br>Astatus=".$status." ,time2=".$time2.", outflag=".$outflag;	            
                                  	                         																                              										                               
  					} // $xml_date_current >= $startdate closed
  				}   // if xml_date!null closed
  			 $j++;
  			 $f++;
        }   // while closed
      } // if original_tmp closed 
			
     fclose($xml);            
		 unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 	
	//echo "Test1";
	fclose($fh);
}

//include("map_window/mapwindow_jsmodule.php");		
//include("map_window/floating_map_window.php");
  include("get_location_lp_track_report_2.php");	
echo '<center>';

report_title("Geofence violation Report",$date1,$date2);
echo '<div align="center" style="width:100%;height:450px;overflow:auto;">'; 					
///////////////////  READ SPEED XML 	//////////////////////////////				                      
$xml_path = $xmltowrite;
//echo "<br>xml_path=".$xml_path;
read_area_violation_xml($xml_path, $imei, $vname, $lat, $lng, $datefrom, $dateto, $duration);
//convert_in_two_dimension
//echo "<br><br>size, vname=".sizeof($vname);
//////////////////////////////////////////////////////////////////////
$j=-1;

for($i=0;$i<sizeof($imei);$i++)
{				
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
    $title="Geofence violation Report : ".$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
    $vname1[$j][$k] = $vname[$i];
    $imei1[$j][$k] = $imei[$i];
    
    echo'
    <br><table align="center">
    <tr>
    	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
    </tr>
    </table>
    <table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
    <tr>
				<td class="text" align="left" width="5%"><b>SNo</b></td>										
				<td class="text" align="left"><b>Location</b></td>
				<td class="text" align="left"><b>DateTime From</b></td>
				<td class="text" align="left"><b>DateTime To</b></td>
				<td class="text" align="left"><b>Duration (hr:min:sec)</b></td>				
    </tr>';  								
  }
        							                    
 
          	
	$lt1 = $lat[$i];
	$lng1 = $lng[$i];
	$alt1 = "-";								
	 
  /*if($access=='Zone')
	{
		get_location($lt1,$lng1,$alt1,&$place,$zoneid,$DbConnection);
	}
	else
	{
		get_location($lt1,$lng1,$alt1,&$place,$DbConnection);
	} 

	$placename[$i] = $place;*/
  
  ////////////////////////////
  $landmark="";
  get_landmark($lt1,$lng1,$landmark);    // CALL LANDMARK FUNCTION
	
  $place = $landmark;
  
  if($place=="")
  {
	echo "$lt1=".$$lt1."lng=".$lng1."<br>";
    get_report_location($lt1,$lng1,$place);
	echo "placename=".$place."<br>";
  }
	
  //echo "P:".$place;
  
  $placename[$i] = $place;
  ////////////////////////////  	
														
	echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';
											
  if($report_type='Person')
  {
    ///// 1.CONVERT DATE TIME IN DD, MM, YYYY FORMA
    $datestr = explode(' ',$datefrom[$i]);
    $date_tmp = $datestr[0];
    $time_tmp = $datestr[1];
    
    $date_substr = explode('-',$date_tmp);
    $year = $date_substr[0];
    $month = $date_substr[1];
    $day = $date_substr[2];
    
    $display_datetime = $day."-".$month."-".$year." ".$time_tmp;
    $datefrom[$i] = $display_datetime;
    
    ///// 2.CONVERT DATE TIME IN DD, MM, YYYY FORMA
    $datestr = explode(' ',$dateto[$i]);
    $date_tmp = $datestr[0];
    $time_tmp = $datestr[1];
    
    $date_substr = explode('-',$date_tmp);
    $year = $date_substr[0];
    $month = $date_substr[1];
    $day = $date_substr[2];
    
    $display_datetime = $day."-".$month."-".$year." ".$time_tmp;
    $dateto[$i] = $display_datetime;    
    ///////////////////////////////////////////////      
  }
        
  if($placename[$i]=="")
	{
		echo'<td class="text" align="left">&nbsp;</td>';
	} 
	else
	{																													
		//echo "lat=".$latitude[$j];
    $lt_tmp = substr($lat[$i], 0, -1);
    $lng_tmp = substr($lng[$i], 0, -1);
    $opt ="xyz";
    $datefromtmp = $datefrom[$i];
    $datetotmp = $dateto[$i];
    $type = "geofence_violation";
    //echo'<td class="text" align="left">'.$placename[$i].'&nbsp;<a href="javascript:map_window(\''.$vname[$i].'\',\''.$datefromtmp.'\',\''.$datetotmp.'\',\''.$lt_tmp.'\',\''.$lng_tmp.'\',\''.$type.'\');"><font color="green">Show location</font></a></td>';
    echo'<td class="text" align="left">'.$placename[$i].'&nbsp;</td>';
	}									
	echo'<td class="text" align="left">'.$datefrom[$i].'</td>';	
  echo'<td class="text" align="left">'.$dateto[$i].'</td>';		
	echo'<td class="text" align="left">'.$duration[$i].'</td>';								
	echo'</tr>';							
	
	$placename1[$j][$k] = $placename[$i];
	$datefrom1[$j][$k] = $datefrom[$i];
	$dateto1[$j][$k] = $dateto[$i];
  $duration1[$j][$k] = $duration[$i];
	
	//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
	$k++;   
  $sno++;      				  				
}

echo '</table>';							
//PDF CODE

$size_vserial = sizeof($imei);
echo '<form method = "post" target="_blank">';
$csv_string = "";
    
for($x=0;$x<=$j;$x++)
{												
    for($y=0;$y<$k;$y++)
    {          
      $pdf_place_ref = $placename1[$x][$y];
      $pdf_datefrom = $datefrom1[$x][$y];
      $pdf_dateto = $dateto1[$x][$y];
      $pdf_duration =  $duration1[$x][$y];
              
      if($y==0)
      {
      	$title="Geofence Violation Report : ".$vname1[$x][$y]." (".$imei1[$x][$y].") (datefrom:".$date1." to dateto:".$date2.")";
      	//echo "<br>pl=".$pdf_place_ref;
      	$csv_string = $csv_string.$title."\n";
      	$csv_string = $csv_string."SNo,Location,Timefrom,Timeto, Duration (Hrs.min)\n";
        echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";
      }
      														
      $sno_1 = $y+1;										
      echo"<input TYPE=\"hidden\" VALUE=\"$sno_1\" NAME=\"temp[$x][$y][SNo]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$pdf_place_ref\" NAME=\"temp[$x][$y][Location]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$pdf_datefrom\" NAME=\"temp[$x][$y][DateTime From]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$pdf_dateto\" NAME=\"temp[$x][$y][DateTime To]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$pdf_duration\" NAME=\"temp[$x][$y][Duration (hr:min:sec)]\">";
      
      $pdf_place_ref = str_replace(',',':',$pdf_place_ref);
      
      $csv_string = $csv_string.$sno_1.','.$pdf_place_ref.','.$pdf_datefrom.','.$pdf_dateto.','.$pdf_duration."\n"; 
      ////////////////////////////////////         	          
    }		
}		
				
if($size_vserial==0)
{						
	print"<center><FONT color=\"Red\" size=2><strong>No Geofence violation found</strong></font></center>";
	//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
	echo'<br><br>';
}	
else
{
  echo'<input TYPE="hidden" VALUE="geofence violation" NAME="csv_type">';
  echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
  echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type2.php?size='.$size_vserial.'\');" value="Get PDF" class="noprint">&nbsp;
  <input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
  <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';
}
echo '</form>';
 	
unlink($xml_path);
echo '</center>';
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';
?>
					