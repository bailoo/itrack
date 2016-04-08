<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];

set_time_limit(300);

include_once("get_all_dates_between.php");
include_once("sort_xml.php");
include_once("calculate_distance.php");
include_once("report_title.php");
include_once("report_get_parsed_string.php");
include_once("read_filtered_xml.php");


$imei = array();
$vname = array();
$datetime = array();
$load_status1 = array();
$location = array();
$load = array();
$load_status2 = array();


$DEBUG = 0;

$device_str = $_POST['vehicleserial'];
//echo "<br>devicestr=".$device_str;
$vserial = explode(':',$device_str);
$vsize=count($vserial);

$date_1 = $_POST['start_date'];
$date_2 = $_POST['end_date'];

$date_1 = str_replace("/","-",$date_1);
$date_2 = str_replace("/","-",$date_2);

$user_interval = $_POST['user_interval'];
//echo "vsize=".$vsize;
////////////////////////////////////////////////////////////////////////////

/*function calculate_mileage($lat1, $lat2, $lon1, $lon2, &$distance) 
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
} */
/////////////////////////////////////XML CODE STARTS//////////////////////////////////////

if($vsize>0)
{
  for($i=0;$i<$vsize;$i++)
  {
    /*$query = "SELECT vehicle_name FROM vehicle WHERE ".
    " vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$vserial[$i]' AND status=1) AND status=1";
    //echo $query;
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $vname1[$i] = $row->vehicle_name;*/
    $vehicle_info=get_vehicle_info($root,$vserial[$i]);
    $vehicle_detail_local=explode(",",$vehicle_info);	
    $vname1[$i] = $vehicle_detail_local[0];
  }  
  $current_dt = date("Y_m_d_H_i_s");	
  $xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";
  load_cell_prev($vserial, $vname1, $date_1, $date_2, $user_interval, $xmltowrite);
}

function load_cell_prev($vserial, $vname1, $startdate, $enddate, $user_interval, $xmltowrite)
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
     get_load_cell_data($vserial[$i], $vname1[$i], $startdate, $enddate, $user_interval, $xmltowrite);
    //echo   "t2".' '.$i;
	}  
 
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}


function get_load_cell_data($vehicle_serial, $vname1, $startdate, $enddate, $user_interval, $xmltowrite)
{	  
  global $imei;
  global $vname;
  global $datetime;
  global $load_status1;
  global $location;
  global $load;
  global $load_status2;
  
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

	
  $fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append
  //echo "<br>xml=".$xmltowrite." ,fh=".$fh;
  
	global $DbConnection;
  $count = 0;
	$j = 0;	
      
  //echo "<br>V=".$vehicle_serial;
  $query = "SELECT * FROM load_cell_2 WHERE imei='$vehicle_serial' AND status=1 AND datetime_1 BETWEEN '$startdate' AND '$enddate'";
  //echo $query;
  $result = mysql_query($query,$DbConnection);
  
  while($row=mysql_fetch_object($result))
  {    
    //echo "<br>IF";
    $vname[] = $vname1;
    $imei[] = $vehicle_serial;
    $datetime[] = $row->datetime_1;      
    $load_status1[] = $row->load_status1;
    $location[] = $row->location;
    $load[] = $row->load_1;
    $load_status2[] = $row->load_status2;        
  }
        
 /* $load_data = "\n< marker imei=\"".$vehicle_serial."\" vname=\"".$vname."\" datetime=\"".$date_load."\" load_status1=\"".$load_status1."\" location=\"".$location."\" load=\"".$load."\" load_status2=\"".$load_status2."\"/>";						          						      
  echo $load_data;
  $linetowrite = $load_data; // for distance       // ADD DISTANCE
  fwrite($fh, $linetowrite);*/                    

//echo "Test1";
	fclose($fh);
}

////////////////////////////// XML CODE ENDS ////////////////////////////////////////////

$m1=date('M',mktime(0,0,0,$month,1));
						
	echo '<center>';
	
  /*echo'<form method = "post" action="SpeedReportAction.php" target="_self">';							
	
  echo'<br><SPAN STYLE="font-size: xx-small">Select Interval </SPAN><select name="user_interval" onChange="this.form.submit();">';			

	if($user_interval==1)
		echo '<option value="1" selected>1</option>';
	else
		echo '<option value="1">1</option>';

	if($user_interval==2)
		echo '<option value="2" selected>2</option>';
	else
		echo '<option value="2">2</option>';
								
	if($user_interval==3)
		echo '<option value="3" selected>3</option>';
	else
		echo '<option value="3">3</option>';

	if($user_interval==4)
		echo '<option value="4" selected>4</option>';
	else
		echo '<option value="4">4</option>';

	if($user_interval==5)
		echo '<option value="5" selected>5</option>';
	else
		echo '<option value="5">5</option>';

	if($user_interval==6)
		echo '<option value="6" selected>6</option>';
	else
		echo '<option value="6">6</option>';

	if($user_interval==7)
		echo '<option value="7" selected>7</option>';
	else
		echo '<option value="7">7</option>';

	if($user_interval==8)
		echo '<option value="8" selected>8</option>';
	else
		echo '<option value="8">8</option>';

	if($user_interval==9)
		echo '<option value="9" selected>9</option>';
	else
		echo '<option value="9">9</option>';

	if($user_interval==10)
		echo '<option value="10" selected>10</option>';
	else
		echo '<option value="10">10</option>';

	if($user_interval==11)
		echo '<option value="11" selected>11</option>';
	else
		echo '<option value="11">11</option>';

	if($user_interval==12)
		echo '<option value="12" selected>12</option>';
	else
		echo '<option value="12">12</option>';							

	echo'</select>&nbsp;<SPAN STYLE="font-size: xx-small"> hr/hrs</SPAN>';						

	for($i=0;$i<sizeof($vserial);$i++)
	{
		//echo "<br>vid=".$vehicleid[$i];
		echo '<input type="hidden" name="vehicleserial[]" value="'.$vserial[$i].'"';
	}
	//echo '<input type="hidden" name="vehicleid[]" value="-"';
	
	echo '<input type="hidden" name="start_date" value="'.$date1.'"';
	echo '<input type="hidden" name="end_date" value="'.$date2.'"';

	echo '</form>';
*/	
	        
   $size_vserial = sizeof($vserial);

    report_title("Load Cell Report",$date_1,$date_2);
		echo'<div style="overflow: auto;height: 300px; width: 620px;" align="center">';
		
		//echo '<div align="center" style="width:100%;height:450px;overflow:auto;">';                
		//echo '<table border="0" width="100%"><tr><td>';        								
			
    ///////////////////  READ SPEED XML 	//////////////////////////////				                      
    $xml_path = $xmltowrite;
    //echo "<br>xml_path=".$xml_path;
    //$load_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" datetime=\"".$date_load."\" load=\"".$load."\"/>"
		//read_load_cell_xml($xml_path, &$imei, &$vname, &$datetime, &$load_status1, &$location, &$load, &$load_status2);		
		//convert_in_two_dimension
    //echo "<br><br>size imei=".sizeof($imei);
		//////////////////////////////////////////////////////////////////////
    $j=-1;
    $k=0;
    			             
    for($i=0;$i<sizeof($imei);$i++)
		{								              
      if(($i===0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
      {
        $k=0;
        
        //$sum_avgspeed =0;
        //$sum_runtime =0;
        //$sum_dist =0;      
        //$total_distance[$j] =0;
              
        //$sum_avgspeed =0;                
        //$final_maxspeed_tmp =0;                                
        
        $j++;
        //$total_avgspeed[$j] =0;
        //$final_maxspeed[$j] =0;
        
        $sno = 1;
        $title="Load Cell Report : ".$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
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
				<td class="text" align="left"><b>SNo</b></td>
				<td class="text" align="left"><b>Time</b></td>
				<td class="text" align="left"><b>Date</b></td>
				<td class="text" align="left"><b>Status</b></td>
        <td class="text" align="left"><b>Location</b></td>
        <td class="text" align="left"><b>Load</b></td>
        <td class="text" align="left"><b>Status</b></td>		
        </tr>';  								
      }                                                                        		
			
      //echo "<br>runtime=".$runtime[$i];
      //$sum_dist = $sum_dist + $distance[$i];
      //$sum_runtime = $sum_runtime + $runtime[$i];
            
      //$sum_avgspeed = $sum_avgspeed + $avgspeed[$i];
			
			//if( $final_maxspeed_tmp < $maxspeed[$i] ) 
			 //$final_maxspeed_tmp = $maxspeed[$i];		
      
      $datetime_str = explode(' ',$datetime[$i]);
      //$time = $datetime_str[1]. "AM";
      $time = $datetime_str[1];
      $date = $datetime_str[0];
      
      echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        													
      echo'<td class="text" align="left">'.$time.'</td>';			
			echo'<td class="text" align="left">'.$date.'</td>';
      echo'<td class="text" align="left">'.$load_status1[$i].'</td>';
      echo'<td class="text" align="left">'.$location[$i].'</td>';
      echo'<td class="text" align="left">'.$load[$i].'</td>';											
      echo'<td class="text" align="left">'.$load_status2[$i].'</td>';
			echo'</tr>';	          		
			//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
      
      $time1[$j][$k] = $time;	
      $date1[$j][$k] = $date;	
      $load_status1[$j][$k] = $load_status1[$i];
      $location1[$j][$k] = $location[$i];
      $load1[$j][$k] = $load[$i];										      			    				  				
      $load_status2[$j][$k] = $load_status2[$i];
		
		  if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
      {
        /*echo '<tr style="height:20px;background-color:lightgrey">
        <td class="text"><strong><strong>&nbsp;</td>';        
				if($k>0)
				{
					//echo  "<br>sum_avgspeed=".$sum_avgspeed."<br>";
					$total_distance[$j] = round($sum_dist,2);
          $total_avgspeed[$j] = round(($sum_avgspeed/$k),2);
          //echo  "<br>total_avgspeed[$j]=".$total_avgspeed[$j]."<br>";
				}
				else
				{
					$total_avgspeed[$j] = "NA";
				}
				
				if($k>0)
				{																									
					$final_maxspeed[$j] = round($final_maxspeed_tmp,2);
				}
				else
				{
					$final_maxspeed[$j] = "NA";
				}																					
				//$avg_speed[$j] = round((($total_distance[$j]/$sum_runtime)*3600),2);
        echo $avg_speed[$j].'</strong></font></td>
				<td class="text"><font color="red"><strong>'.$final_maxspeed[$j].'</strong></font></td>';  
				echo'</tr>'; */	
        echo '</table>';
        
        $no_of_data[$j] = $k;
      
      //$final_maxspeed_tmp =0;
			}  
			
      $k++;   
      $sno++;                                   							  		
   }
   
   echo "</div>";     

   $csv_string = "";   
	 
   echo'<form method = "post" target="_blank">';
		
		for($x=0;$x<=$j;$x++)
		{								
			$title=$vname1[$x][0]." (".$imei1[$x][0]." ): Load Cell Report- From DateTime : ".$date1."-".$date2;
			$csv_string = $csv_string.$title."\n";
			$csv_string = $csv_string."SNo,Time,Date,Status,Location,Load,Status\n";
			
      echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
			
			$sno=0;
			for($y=0;$y<=$no_of_data[$x];$y++)
			{
				$sno++;
                      
        $datetmp1 = $datetime1[$x][$y];								
				$before_loadtmp1 = $before_load1[$x][$y];
				$after_loadtmp1 = $after_load1[$x][$y];
				
        $timetmp =$time1[$x][$y];	
        $datetmp = $date1[$x][$y];	
        $status1_tmp = $load_status1[$x][$y];
        $location_tmp = $location1[$x][$y];
        $load_tmp = $load1[$x][$y];										      			    				  				
        $status2_tmp = $load_status2[$x][$y];				
				
				echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$timetmp\" NAME=\"temp[$x][$y][Time]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$datetmp\" NAME=\"temp[$x][$y][Date]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$status1_tmp\" NAME=\"temp[$x][$y][Status]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$location_tmp\" NAME=\"temp[$x][$y][Location]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$load_tmp\" NAME=\"temp[$x][$y][Load]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$status2_tmp\" NAME=\"temp[$x][$y][Status]\">";				
        
        $csv_string = $csv_string.$sno.','.$timetmp.','.$datetmp.','.$status1_tmp.','.$location_tmp.','.$load_tmp.','.$status2_tmp."\n";									
			}		
			
      //$csv_string = $csv_string.'Total,'.$date1.','.$date2.','.$avg_speed[$x].','.$final_maxspeed[$x]."\n";

			/*echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime From]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime To]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Avg Speed (km/hr)]\">";									
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Max Speed (km/hr)]\">";	
			
			$m = $y+1;								
			
			echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][DateTime From]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][DateTime To]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$avg_speed[$x]\" NAME=\"temp[$x][$m][Avg Speed (km/hr)]\">";									
			echo"<input TYPE=\"hidden\" VALUE=\"$final_maxspeed[$x]\" NAME=\"temp[$x][$m][Max Speed (km/hr)]\">";*/																																		
		}																						


    echo'	
    <table align="center">
		<tr>
			<td>';
      
  		if(sizeof($imei)==0)
  		{						
  			print"<center><FONT color=\"Red\" size=2><strong>No Load Cell Record found</strong></font></center>";
  			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
  			echo'<br><br>';
  		}	
  		else
  		{
        echo'<input TYPE="hidden" VALUE="load_cell" NAME="csv_type">';
        echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
        echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
        <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';      
      }                  
      echo'</td>		
    </tr>
		</table>
		</form>
	 ';					 
//unlink($xml_path);
echo'</center>';
?>								