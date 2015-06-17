<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];
include_once('common_xml_element.php');
include_once("get_all_dates_between.php");
include_once("sort_xml.php");
include_once("calculate_distance.php");
include_once("read_filtered_xml.php");     
include_once("report_title.php");

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
	//echo "vehicle_info=".$vehicle_info."<br>";
    $vehicle_detail_local=explode(",",$vehicle_info);	
    $vname[$i] = $vehicle_detail_local[0];
  } 
  $current_dt = date("Y_m_d_H_i_s");	
  $xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";
  write_speed_violation_report_xml($vserial, $vname, $date1, $date2, $xmltowrite);
}

function write_speed_violation_report_xml($vserial, $vname, $startdate, $enddate, $xmltowrite)
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
     get_speed_violation_xml_data($vserial[$i], $vname[$i], $startdate, $enddate, $xmltowrite);
    //echo   "t2".' '.$i;
	}  
 
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}

function get_speed_violation_xml_data($vehicle_serial, $vname, $startdate, $enddate, $xmltowrite)
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

  $query_geo = "select max_speed from vehicle WHERE vehicle_id = (SELECT vehicle_id FROM vehicle_assignment WHERE ".
  " device_imei_no ='$vehicle_serial' AND status=1) AND status=1";
	//echo $query_geo;
  $res_geo = mysql_query($query_geo,$DbConnection);
	if($row_geo = mysql_fetch_object($res_geo))
	{
		$max_speed = $row_geo->max_speed;
	}
	//$max_speed = 25;
  //echo "<br>maxspd=".$max_speed;  
  
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
		  //$current_datetime1 = date("Y_m_d_H_i_s");
		  $t=time();
      //$xml_original_tmp = "xml_tmp/original_xml/tmp_".$vehicle_serial."_".$current_datetime1.".xml";
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
        //$xml_unsorted = "xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$current_datetime1."_unsorted.xml";
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
      
      
      if (file_exists($xml_original_tmp)) 
      { 
        $speed_violated_time="";
        $startflag = 0;
        $endflag = 0;
        
        /*if($_SESSION['test_vts']=='debug')
        {
          $DEBUG = 1;
        }*/     
		set_master_variable($userdates[$i]);
        $i=0;
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
              
              /*$status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
              if($status==0)
              {
                continue;               
              }
              //echo "test6".'<BR>';
              $status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
              if($status==0)
              {
                continue;
              } */    
                                       
              //echo "test4".'<BR>';
              /*$status = preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $datetime_tmp);
              if($status==0)
              {
                continue;
              } */
              
              $status = preg_match('/'.$vf.'="[^" ]+/', $line, $speed_tmp);
              if($status==0)
              {
                continue;
              }            
                             						
              //$time2 = $datetime;											
  						//$date_secs2 = strtotime($time2);	
  						
  			
              $vserial = $vehicle_serial;
              
              //$vehiclename_tmp1 = explode("=",$vehiclename_tmp[0]);
              //$vname = preg_replace('/"/', '', $vehiclename_tmp1[1]);							
  						
              $speed_tmp1 = explode("=",$speed_tmp[0]);
              $speed = preg_replace('/"/', '', $speed_tmp1[1]);
              
              ///////// FIXING SPEED PROBLEM ///////////            
              /*$speed_str = $speed;
              $speed_tmp = "";
              for ($x = 0, $y = strlen($speed_str); $x < $y; $x++) 
              {
                  if($speed_str[$x]>='0' && $speed_str[$x]<='9')
                  {
                    $speed_tmp = $speed_tmp.$speed_str[$x];
                  }      
                  else
                  {
                    $speed_tmp = $speed_tmp.".";
                  }  
              }
              $speed = $speed_tmp; */ 
              //$speed[$i] = round($speed[$i],2);  
              //echo "speed=".$speed_tmp;    
              ///////////////////////////////////////////                                                                           
                								
  						/*$lat_tmp1 = explode("=",$lat_tmp[0]);
              $lat = preg_replace('/"/', '', $lat_tmp1[1]);
              
              $lng_tmp1 = explode("=",$lng_tmp[0]);
              $lng = preg_replace('/"/', '', $lng_tmp1[1]); */             				                          
                              
              if($startflag == 1)
              {
                $tmptime1 = $violation_start_time;
                $tmptime1 = strtotime($tmptime1);
                $tmptime2 = $datetime;
                $tmptime2 = strtotime($tmptime2);
                $difftime = $tmptime2 - $tmptime1;
                //echo "<br>diff=".$difftime." ,speed=".$speed." ,max_speed=".$max_speed;
              }
              
              if( ($speed > $max_speed) && ($startflag==0) )
              {
              	$speed_violated_time="";
                $violation_start_time = $datetime;
              	$startflag = 1;
              	$endflag = 0;
              	//if($DEBUG)
              	//echo  "<br>v_start_time=".$violation_start_time;
              }
              else if( ( (($speed < $max_speed) && ($startflag == 1) ) || ($i==$total_lines-1) ) && ($difftime >= 60) )
              {
              	$time1 = strtotime($violation_start_time); 
              	$time2 = strtotime($datetime);
              	$speed_violated_time = $speed_violated_time + ($time2 - $time1);   
              	//echo "<br>violated=".$speed_violated_time;
                $endflag=1;
              }
              
            	if(($speed_violated_time && $startflag == 1 && $endflag == 1) && ($difftime >= 60))
            	{			  
            	  $violated_dur =  ($speed_violated_time)/3600;     
            	  $violated_dur = round($violated_dur,2);										
            	  $total_min = $violated_dur * 60;           
            	  $hr = (int)($total_min / 60);
            	  $minutes = $total_min % 60;										      
            	  $hrs_min = $hr.":".$minutes; 	
            	  
            	  $violated_time = $hrs_min;  // STORE IN SHIFT ARRAY	
            	  $startflag = 0;
            	  $endflag = 0;
            	  
                $violation_end_time = $datetime;
                                
                $speedv_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" time1=\"".$violation_start_time."\" time2=\"".$violation_end_time."\" violated_time=\"".$violated_time."\"/>";						          						
                //echo "<br>speed_data=".$speedv_data;
                $linetowrite = $speedv_data; // for distance       // ADD DISTANCE
                fwrite($fh, $linetowrite);              	  
            	}                 		                                         						                               
  					} // $xml_date_current >= $startdate closed
  				}   // if xml_date!null closed
  				$i++;
        }   // while closed
      }  // if original_tmp closed
			
     fclose($xml);            
		 unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 
	
	//echo "Test1";
	fclose($fh);
}
include("MapWindow/MapWindow_jsmodule.php");	
?>
	
<script type="text/javascript">

	//function MapWindow(vname,datetime,lat,lng)
	function MapWindow(vname,datetime,lat,lng)
	{
		//alert(vname+" "+datetime+" "+lat+" "+lng);	
		//test2(vname,datetime,lat,lng);			
		document.getElementById("window").style.display = '';
		load_vehicle_on_map(vname,datetime,lat,lng);							
	}
		
</script>	

<?php
	
include("MapWindow/floating_map_window.php");

/*function calculate_mileage($lat1, $lat2, $lon1, $lon2, &$distance) 
{
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

	$distance = $distance*1.609344;
	//echo "distance=".$distance;
} */

report_title("Speed violation Report",$date1,$date2);
	
echo '<center>';
echo '<div align="center" style="width:100%;height:450px;overflow:auto;">'; 

///////////////////  READ SPEED XML 	//////////////////////////////				                      
$xml_path = $xmltowrite;
//echo "<br>xml_path=".$xml_path;
read_speed_violation_xml($xml_path, &$imei, &$vname, &$time1, &$time2, &$violated_time);
//convert_in_two_dimension
//echo "<br><br>size, vname=".sizeof($imei);
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
    $title="Speed Violation Report : ".$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
    $vname1[$j][$k] = $vname[$i];
    $imei1[$j][$k] = $imei[$i];
    
    echo'
    <br><table align="center">
    <tr>
    	<td class="text" align="center"><b>'.$title.'</b> <div style="height:6px;"></div></td>
    </tr>
    </table>
    <table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
    <tr>
				<td class="text" align="left" width="5%"><b>SNo</b></td>										
				<td class="text" align="left"><b>Time1</b></td>
				<td class="text" align="left"><b>Time2</b></td>
				<td class="text" align="left"><b>Violated duration(hrs:min)</b></td>				
    </tr>';  							
  }
        							                    
  /*if($access=='Zone')
  {
  	include("get_mining_location.php");		
  }
  else
  {
  	include("get_location.php");
  }
          	
	$lt1 = $lat[$i];
	$lng1 = $lng[$i];
	$alt1 = "-";								
	 
  if($access=='Zone')
	{
		get_location($lt1,$lng1,$alt1,&$place,$zoneid,$DbConnection);
	}
	else
	{
		get_location($lt1,$lng1,$alt1,&$place,$DbConnection);
	} 

	$placename[$i] = $place;	
														
	echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';
										
	if($placename[$i]=="")
	{
		echo'<td class="text" align="left">&nbsp;</td>';
	}
	else
	{																													
		//echo "lat=".$latitude[$j];
    $lt_tmp = substr($lat[$i], 0, -1);
    $lng_tmp = substr($lng[$i], 0, -1);
    
	  echo'<td class="text" align="left">'.$placename[$i].'</b>&nbsp;<a href="javascript:MapWindow(\''.$vname[$i].'\',\''.$datetime[$i].'\','.$lt_tmp.','.$lng_tmp.');"><font color="green">Show location</font></a></td>';
  }	*/
  								
	echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';
  echo'<td class="text" align="left">'.$time1[$i].'</td>';		
	echo'<td class="text" align="left">'.$time2[$i].'</td>';			
	echo'<td class="text" align="left">'.$violated_time[$i].'</td>';					
	echo'</tr>';							
	
	$time1_pdf[$j][$k] = $time1[$i];
	$time2_pdf[$j][$k] = $time2[$i];
  $violated_time_pdf[$j][$k] = $violated_time[$i];
	
	//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
  if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
  {
    $no_of_data[$j] = $k;
    echo '</table>';
  }    		
	
	$k++;   
  $sno++;    			  				
}
//echo '</table>';							
//PDF CODE

$size_vserial = sizeof($imei);
echo '<form method="post" target="_blank">';
$csv_string = "";

for($x=0;$x<=$j;$x++)
{												
    for($y=0;$y<=$no_of_data[$x];$y++)
    {          
      $time1_pdf_tmp = $time1_pdf[$x][$y];
      $time2_pdf_tmp = $time2_pdf[$x][$y];
      $violated_time_pdf_tmp =  $violated_time_pdf[$x][$y];
              
      if($y==0 && $time1_pdf_tmp!="" && $time2_pdf_tmp!="" && $violated_time_pdf_tmp!="")
      {
      	//echo "<br>Report-x=".$x.",y=".$y;
        $title="Speed Violation Report : ".$vname1[$x][$y]." (".$imei1[$x][$y].")";
      	//echo "<br>pl=".$pdf_place_ref;
      	$csv_string = $csv_string.$title."\n";
      	$csv_string = $csv_string."SNo,Timefrom,Timeto, Violated Duration (Hrs.min)\n";      	
        echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";
      }
      														
      if($time1_pdf_tmp!="" && $time2_pdf_tmp!="" && $violated_time_pdf_tmp!="")
      {
        $sno_1 = $y+1;										
        echo"<input TYPE=\"hidden\" VALUE=\"$sno_1\" NAME=\"temp[$x][$y][SNo]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$time1_pdf_tmp\" NAME=\"temp[$x][$y][Time From]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$time2_pdf_tmp\" NAME=\"temp[$x][$y][Time To]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$violated_time_pdf_tmp\" NAME=\"temp[$x][$y][Violated Duration(hrs:min)]\">"; 
        $csv_string = $csv_string.$sno_1.','.$time1_pdf_tmp.','.$time2_pdf_tmp.','.$violated_time_pdf_tmp."\n"; 
      }     	          
    }		
}


if($size_vserial==0)
{						
	print"<center><FONT color=\"Red\" size=2><strong>No Speed violation found</strong></font></center>";
	//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
	echo'<br><br>';
}	
else
{
  echo'<input TYPE="hidden" VALUE="speed violation" NAME="csv_type">';
  echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
  echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type2.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;
  <input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
  <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';
}
echo '</form>';

/*if($size_vserial>0)
{
	echo'
			<table align="center">
				<tr><td align="center"><input type="submit" value="Get PDF" class="noprint">&nbsp;
        <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;</td></tr>
			</table>
		</form>
	';
}
else if($size_vserial==0)
{
		print"<center><FONT color=\"Red\"><strong>No Speed Voilation in This Duration</strong></font></center>";
		echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=SpeedViolation.php\">";
} */

unlink($xml_path);

echo '</center>';
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';	 
?>