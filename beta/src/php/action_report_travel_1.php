<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	set_time_limit(300);	
	include_once("calculate_distance.php");
	include_once("user_type_setting.php");
	include_once("select_landmark_report.php");
	include_once("report_title.php");
	include_once("util.hr_min_sec.php");
    $j=-1;   
	
	$google_place_start=explode(":",$geocodedPostcodesStart);
	$google_place_end=explode(":",$geocodedPostcodesEnd);
	/*echo "imei1=".$imei_prev."<br>vname1=".$vname_prev."<br>lat1=".$lat_start_prev."<br>lng1=".$lng_start_prev."<br>lat_end_str=".$lat_end_prev.
		 "<br>lng_end_str=".$lng_end_prev."<br>time1_str=".$time1_str."<br>time2_str=".$time2_str."<br>google_place_start=".$geocodedPostcodesStart.
		 "<br>google_place_end=".$geocodedPostcodesEnd."<br>distance_travelled_prev=".$distance_travelled_prev."<br>travel_time_prev=".$travel_time_prev."<br>";*/
		
	$imei=explode(":",$imei_prev);
	$vname=explode(":",$vname_prev);
	$lat_start=explode(":",$lat_start_prev);
	$lng_start=explode(":",$lng_start_prev);
	$lat_end=explode(":",$lat_end_prev);
	$lng_end=explode(":",$lng_end_prev);
	$time1=explode(",",$time1_prev);
	$time2=explode(",",$time2_prev);
	$distance_travelled=explode(":",$distance_travelled_prev);		
	$travel_time=explode(",",$travel_time_prev);
	$max_speed=explode(",",$max_speed_prev);
	$avg_speed=explode(",",$avg_speed_prev);
	
	//print_r($avg_speed);
	$vsize = sizeof($imei); 
?>
<html>
	<title> Travel Report </title>
	 <head> 
		<script type="text/javascript" src="../js/calculate_distance.js"></script>
		<link rel="stylesheet" type="text/css" href="gm_minimap_in_infowindow_files/mapStyle.css">	
		<style type="text/css">
		@import url("http://www.google.com/uds/css/gsearch.css");
		@import url("http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css");
		}
		</style>
		<?php
		include_once("main_google_key.php");
		?>		
		<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0" type="text/javascript"></script>
		<script src="http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js" type="text/javascript"></script> 
		<script src="./src/js/dragzoom/gzoom.js" type="text/javascript"></script>	
		<script type="text/javascript">
			var lnmark= new GIcon();
			lnmark.image = 'landmark.png';
			lnmark.iconSize= new GSize(10, 10);
			lnmark.iconAnchor= new GPoint(9, 34);
			lnmark.infoWindowAnchor= new GPoint(5, 1);
		</script>
		<?php		
		echo '<script type="text/javascript" src="../js/newwindow_shortmap.js"></script>';
		if($account_id == "230" || $account_id == "231" || $account_id == "232" || $account_id == "265" || $account_id == "322" || $account_id == "419" || $account_id == "420" || $account_id == "421" || $account_id == "422"|| $account_id == "423"|| $account_id == "424"|| $account_id == "425"|| $account_id == "426"|| $account_id == "427"|| $account_id == "428"|| $account_id == "429")  
		{
			echo'
			<script type="text/javascript" src="../js/newwindow_mapstation.js"></script>  
			<script type="text/javascript" language="javascript">	
			function map_window_station_prev(customer,station_id,station_name,lat,lng)
			{
				//test2(vname,datetime,lat,lng);			
				document.getElementById("window").style.display = "";
				load_stations(customer,station_id,station_name,lat,lng);							
			}
			</script>';
		}

		?>
		
		<script type="text/javascript">
			//function MapWindow(vname,datetime,lat,lng)
			function map_window(vname,date1,date2,lat,lng,type)
			{
				//alert(vname+" "+datetime+" "+lat+" "+lng);	
				//test2(vname,datetime,lat,lng);			
				document.getElementById("window").style.display = '';
				load_vehicle_on_mini_map(vname,date1,date2,lat,lng,type);							
			}
	  
			/*function map_window(vname,datefrom,dateto,lat,lng)
			{
				//alert(vname+" "+datetime+" "+lat+" "+lng);	
				//test2(vname,datetime,lat,lng);			
				document.getElementById("window").style.display = '';
				load_vehicle_on_map(vname,datefrom,dateto,lat,lng);							
			}*/  		
		</script>
		<link rel="StyleSheet" href="../css/newwindow.css">	
		<script language="javascript" src="../js/drag.js"></script>   
		<script language="javascript" src="../js/report.js"></script>		
	 </head>
<body>
<?php   
$threshold1 = $threshold;
	$threshold = $threshold/60;
	$param1 = $date1;
   // $param2 = $date2."&nbsp;-Interval:".round($threshold,3)." mins";
    $param2 = $date2."&nbsp;-Interval:".$threshold1." mins";
	echo'<table>
			<tr>
				<td height="5px">
				</td>
			</tr>
		</table>';
	echo "<center>";
			newwindow_reporttitle("Travel Report",$param1,$param2);
	echo "</center>";		
    echo'<div style="overflow: auto;height: 450px;" align="center">';       
	$alt ="-";
	$j=-1;  
	$total_distance=0.0;
	$total_time = 0;

	for($i=0;$i<(sizeof($imei)-1);$i++)
	{				
		//echo "<br>i=".$i;
		/*echo "<br>a".$i."=".$vname[$i];
		echo "<br>lat".$i."=".$lat[$i];
		echo "<br>lng".$i."=".$lng[$i];
		echo "<br>arrival_time".$i."=".$arrival_time[$i];
		echo "<br>dep_time".$i."=".$dep_time[$i];
		echo "<br>duration".$i."=".$duration[$i]; */
		//echo "avg_spped=".$avg_speed[$i]."<br>";
            
		if(($i===0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
		{
			$k=0;
			$j++;
			$sno = 1;
			$title="Travel Report : ".$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
			$vname1[$j][$k] = $vname[$i];
			$imei1[$j][$k] = $imei[$i]; 
		echo'<table>
				<tr>
					<td height="10px">
					</td>
				</tr>
			</table>
			<table align="center">
				<tr>
					<td class="text" align="center">
						<b>'.$title.'</b> <div style="height:8px;"></div>
					</td>
				</tr>
			</table>
			<table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3 class="menu">	
				<tr style="background-color:lightgrey">
					<td class="text" align="left" width="4%">
						<b>SNo</b>
					</td>
					<td class="text" align="left">
						<b>Start Time</b>
					</td>
					<td class="text" align="left">
						<b>End Time</b>
					</td>
					<td class="text" align="left">
						<b>Start Place</b>
					</td>
					<td class="text" align="left">
						<b>End Place</b>
					</td>
					<td class="text" align="left">
						<b>Distance Travelled(km)</b>
					</td>
					<td class="text" align="left">
						<b>Travel Time(H:m:s)</b>
					</td>
					<td class="text" align="left">
						<b>Max Speed (km/hr)</b>
					</td>
					<td class="text" align="left">
						<b>Average Speed(km/hr)</b>
					</td>
				</tr>';  								
		}
            							                    
      /*if($report_type=='Person')
      {
        ///// 1.CONVERT DATE TIME IN DD, MM, YYYY FORMA
        $datestr = explode(' ',$arr_time[$i]);
        $date_tmp = $datestr[0];
        $time_tmp = $datestr[1];
        
        $date_substr = explode('-',$date_tmp);
        $year = $date_substr[0];
        $month = $date_substr[1];
        $day = $date_substr[2];
        
        $display_datetime = $day."-".$month."-".$year." ".$time_tmp;
        $arr_time[$i] = $display_datetime;
        
        ///// 2.CONVERT DATE TIME IN DD, MM, YYYY FORMAT
        $datestr = explode(' ',$dep_time[$i]);
        $date_tmp = $datestr[0];
        $time_tmp = $datestr[1];
        
        $date_substr = explode('-',$date_tmp);
        $year = $date_substr[0];
        $month = $date_substr[1];
        $day = $date_substr[2];
        
        $display_datetime = $day."-".$month."-".$year." ".$time_tmp;
        $dep_time[$i] = $display_datetime;                
        ///////////////////////////////////////////////      
      } */
            
     	//include("get_location_test.php");               	
		//location 1
		$lt1 = $lat_start[$i];
		$lng1 = $lng_start[$i];
		$alt1 = "-";
		$landmark="";
		get_landmark($lt1,$lng1,$landmark);    // CALL LANDMARK FUNCTION
		$place1 = $landmark;
		if($place1=="")
		{		
			$place1 = preg_replace('/भारत गणराज्य/', '', $google_place_start[$i]);
			$place1 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '' , $place1);
			//get_location($lt1,$lng1,$alt1,&$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
		}   
		//location2
		$lt2 = $lat_end[$i];
		$lng2 = $lng_end[$i];
		$alt2 = "-";		 
		$landmark="";
		get_landmark($lt2,$lng2,$landmark);    // CALL LANDMARK FUNCTION
		$place2 = $landmark;
		if($place2=="")
		{		
			$place2 = preg_replace('/भारत गणराज्य/', '', $google_place_end[$i]);
			$place2 = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '' , $place2);
		}   		
		//echo "P:".$place1;
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
		echo'<td class="text" align="left"><b>'.$travel_time[$i].'</b></td>	
		<td class="text" align="left"><b>'.$max_speed[$i].'</b>
		<td class="text" align="left"><b>'.$avg_speed[$i].'</b>
		</tr>';	
		$total_distance=$total_distance+$distance_travelled[$i];

		$tmptime = explode(':',$travel_time[$i]);
		$time_secs = (((int)$tmptime[0]) * 60 * 60) + (((int)$tmptime[1]) * 60) + ((int)$tmptime[2]);
		$total_time = $total_time + $time_secs;

		//echo "<br>msg1";
		$time1_a[$j][$k] =  $time1[$i];					
		$time2_a[$j][$k] = $time2[$i];				
		$location1_a[$j][$k] = $location1;
		$location2_a[$j][$k] = $location2;        
		$distance_travelled1[$j][$k] = $distance_travelled[$i];
		$travel_time1[$j][$k] = $travel_time[$i];
		$max_speed1[$j][$k] = $max_speed[$i];
		$avg_speed1[$j][$k] = $avg_speed[$i];
		
		if((($i>=0) && ($imei[$i+1] != $imei[$i])))
		{
			$no_of_data[$j] = $k;
			//$total_count = $i+1;			
			//$average_time_tmp = $total_time / $total_count;
			$hms = secondsToTime($total_time);
			$average_time = $hms[h].":".$hms[m].":".$hms[s];

		echo'<tr>
				<td class="text" align="right" colspan="5"><b>Total </b></td>
				<td class="text" align="left"><b>'.$total_distance.'</b>						
				<td class="text" align="left"><b>'.$average_time.'</b>				
			</tr>';
			$total_distance=0.0;
	echo'</table><br>';
			$total_count = 0;
			$total_time = 0;
		}
		$k++;   
		$sno++;      				  				
	}
    if($j==0)
      echo '</table>
	  </div><br>';	
		echo '<form method="post" target="_blank">';		
				$csv_string = ""; 
							
				for($x=0;$x<=$j;$x++)
				{
					$total_distance_pdf_csv=0.0;
					$total_time = 0;

					for($y=0;$y<=$no_of_data[$x];$y++)
					{
						$alt_ref="-";
						//echo "<br>arr_time1[$x][$y]=".$arr_time1[$x][$y];                    
						$pdf_time1 = $time1_a[$x][$y];
						$pdf_time2 = $time2_a[$x][$y];
						$pdf_place1 = str_replace('"'," ",$location1_a[$x][$y]);
						$pdf_place2 = str_replace('"'," ",$location2_a[$x][$y]);
						$pdf_distance = $distance_travelled1[$x][$y];
						$pdf_travel_time = $travel_time1[$x][$y]; 
						$pdf_max_speed = $max_speed1[$x][$y]; 
						$pdf_avg_speed = $avg_speed1[$x][$y]; 												
						$total_distance_pdf_csv=$total_distance_pdf_csv+$pdf_distance;
						
						$tmptime = explode(':',$travel_time1[$x][$y]);
						$time_secs = (((int)$tmptime[0]) * 60 * 60) + (((int)$tmptime[1]) * 60) + ((int)$tmptime[2]);
						$total_time = $total_time + $time_secs;

						if($y==0)
						{
							$title="Travel Report : ".$vname1[$x][$y]." (".$imei1[$x][$y].") (Interval:".$threshold." mins)";
							//echo "<br>pl=".$pdf_place_ref;
							$csv_string = $csv_string.$title."\n";
							$csv_string = $csv_string."SNo,StartTime,EndTime,StartPlace,EndPlace,Distance Travelled(km), Travel Time(H:m:s),Max Speed(km/hr),Average Speed(km/hr)\n";            

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
						echo"<input TYPE=\"hidden\" VALUE=\"$pdf_max_speed\" NAME=\"temp[$x][$y][Max Speed (km/hr)]\">"; 
						echo"<input TYPE=\"hidden\" VALUE=\"$pdf_avg_speed\" NAME=\"temp[$x][$y][Average Speed (km/hr)]\">"; 	

						/// CODE FOR CSV
						$pdf_place1 = str_replace(',',':',$pdf_place1);
						$pdf_place2 = str_replace(',',':',$pdf_place2);
						//echo "<br>".$pdf_place_ref;
						$csv_string = $csv_string.$sno_1.','.$pdf_time1.','.$pdf_time2.','.$pdf_place1.','.$pdf_place2.','.$pdf_distance.','.$pdf_travel_time.','.$pdf_max_speed.','.$pdf_avg_speed."\n"; 
						////////////////////////////////////         	
					}	//inner for
					$m = $y+1;

					//$total_count = $y+1;			
					//$average_time_tmp = $total_time / $total_count;
					$hms = secondsToTime($total_time);
					$average_time = $hms[h].":".$hms[m].":".$hms[s];
					
					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$m][SNo]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$m][Start Time]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$m][End Time]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$m][Start Place]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"Total Distance\" NAME=\"temp[$x][$m][End Place]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$total_distance_pdf_csv\" NAME=\"temp[$x][$m][Distance Travelled (km)]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$average_time\" NAME=\"temp[$x][$m][Travel Time (H:m:s)]\">";						

					$csv_string = $csv_string.',,,,Total,'.$total_distance_pdf_csv.','.$average_time."\n"; 
					$total_count = 0;
					$total_time = 0;	
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
					echo'<br><center><input type="button" onclick="javascript:report_csv(\'report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;
					<input type="button" onclick="javascript:report_csv(\'report_csv.php\');" value="Get CSV" class="noprint">&nbsp;</center>
					<!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';
				}
		echo '</form>';    
    /*if($report_type=='Person')
    {
      echo'<form method = "post" name="csv_form" action="src/php/report_csv.php" target="_blank">';
        echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';        
      echo '</form>';
    }	*/  
     
?>	
