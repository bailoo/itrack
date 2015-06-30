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
	$google_place=explode(":",$geocodedPostcodes);
	//$google_place=$_SESSION['place_name_halt_arr1'];	
	//unset($_SESSION['place_name_halt_arr1']);
	/*echo "imei1=".$imei_prev."<br>vname1=".$vname_prev."<br>lat1=".$lat_prev."<br>lng1=".$lng_prev."<br>arr_time1=".$arr_time_prev.
		 "<br>dep_time_str=".$dep_time_prev."<br>duration_str=".$duration_prev."google_place=".$geocodedPostcodes."<br>";*/
	$imei=explode(":",$imei_prev);
	$vname=explode(":",$vname_prev);
	$lat=explode(":",$lat_prev);
	$lng=explode(":",$lng_prev);
	$arr_time=explode(",",$arr_time_prev);
	$dep_time=explode(",",$dep_time_prev);
	$in_tmp=explode(",",$in_tmp_prev);
	$out_tmp=explode(",",$out_tmp_prev);
	$duration=explode(":",$duration_prev);			
    $vsize = sizeof($imei); 
?>
<html>
	<title> Halt Report </title>
	 <head> 
		<script type="text/javascript" src="../js/calculate_distance.js"></script>
		<link rel="stylesheet" type="text/css" href="gm_minimap_in_infowindow_files/mapStyle.css">	
		<style type="text/css">
		@import url("http://www.google.com/uds/css/gsearch.css");
		@import url("http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css");
	
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
		<script type="text/javascript" src="../js/newwindow_shortmap.js?<?php echo time();?>"></script>
		<?php		
		//echo '<script type="text/javascript" src="../js/newwindow_shortmap.js"></script>';
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
		$param1 = $date1;
		$param2 = $date2." &nbsp;-Interval:".$user_interval." mins";
		echo'<table>
				<tr>
					<td height="5px">
					</td>
				</tr>
			</table>';
		echo "<center>";
				newwindow_reporttitle("Halt Report",$param1,$param2);
		echo "</center>";
		echo'<div style="overflow: auto;height: 450px;" align="center">';  
		$alt ="-";
                //error_reporting(-1);
//ini_set('display_errors', 'On');
	for($i=0;$i<(sizeof($imei)-1);$i++)
	{				
		/*echo "<br>a".$i."=".$vname[$i];
		echo "<br>lat".$i."=".$lat[$i];
		echo "<br>lng".$i."=".$lng[$i];
		echo "<br>arrival_time".$i."=".$arrival_time[$i];
		echo "<br>dep_time".$i."=".$dep_time[$i];
		echo "<br>duration".$i."=".$duration[$i];
		echo "i=".$i."imei=".$imei[$i-1]." imei=".$imei[$i]."<br>";*/
		if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
		{
			//echo "in if<br>";
			$k=0;
			$j++;
			$sno = 1;
			$sum_halt_hr =0;
			$total_halt_hr[$j] =0;
			$title="Halt Report : ".$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>-Interval:".$user_interval." mins";		
			$vname1[$j][$k] = $vname[$i];
			$imei1[$j][$k] = $imei[$i];
		
			echo'
				<table>
					<tr>
						<td height="10px">
						</td>
					</tr>
				</table>
				<table align="center" class="menu">
					<tr>
						<td class="text" align="center">
							<b>'.$title.'</b> 
							<div style="height:8px;"></div>
						</td>
					</tr>
				</table>				
				<table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3 class="menu">	
					<tr style="background-color:lightgrey">
						<td class="text" align="left" width="4%">
							<b>SNo</b>
						</td>
						<td class="text" align="left">
							<b>Location</b>
						</td>
						<td class="text" align="left">
							<b>Arrival Time</b>
						</td>
						<td class="text" align="left">
							<b>Departure Time</b>
						</td>';	
						//echo "in_tmp=".$in_tmp[$i]." out_tmp=".$out_tmp[$i]."<br>";
						if($in_tmp[$i]!="0.0" && $out_tmp[$i]!="0.0")
						{
						echo'<td class="text" align="left">
								<b>In Temp</b>
							</td>
							<td class="text" align="left">
								<b>Out Temp</b>
							</td>';
						}						
					echo'<td class="text" align="left">
							<b>Halt Duration (Hrs.min)</b>
						</td>
						<td class="text" align="left">
							<b>Latitude/Longitude</b>
						</td>
					</tr>';  								
		}            							                    
		$sum_halt_hr = $sum_halt_hr + $duration[$i];
		if($report_type=='Person')
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
			
			///// 2.CONVERT DATE TIME IN DD, MM, YYYY FORMA
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
		}            
     	//include("get_location_test.php");             	
		//echo "<br>Before One";			
		$lt1 = $lat[$i];
		$lng1 = $lng[$i];
		//echo "lat=".$lt1."lng=".$lng1."<br>";
		$alt1 = "-";		 
		$landmark="";
		//echo "before_landmark=".$landmark."<br>".$lt1.",".$lng1."<br>";
		get_landmark($lt1,$lng1,$landmark);    // CALL LANDMARK FUNCTION
		//echo "<br>after_landmark=".$landmark."<br>";
		$place = $landmark;

                if($place=="")
                {
                        $place = preg_replace('/भारत गणराज्य/', '', $google_place[$i]);
                        $place = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '' , $google_place[$i]);
                        //get_location($lt1,$lng1,$alt1,&$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
                }

		//echo "P:".$place;
		$placename[$i] = $place;
		echo'<tr>
				<td class="text" align="left" width="4%">
					<b>'.$sno.'</b>
				</td>';
				$lt_tmp = substr($lat[$i], 0, -1);
				$lng_tmp = substr($lng[$i], 0, -1);
				if($placename[$i]=="")
				{
			echo'<td class="text" align="left">
					&nbsp;
					<a href="javascript:map_window(\''.$vname[$i].'\',\''.$arr_time[$i].'\',\''.$dep_time[$i].'\',\''.$lt_tmp.'\',\''.$lng_tmp.'\',\''.$type.'\');">
						<font color="green">
							&nbsp;(Show on map)
						</font>
					</a>
				</td>';		
				}
				else
				{																													
					//echo "lat=".$latitude[$j];					
					$type = "halt";
					echo'<td class="text" align="left">
							'.$placename[$i].'&nbsp;
							<a href="javascript:map_window(\''.$vname[$i].'\',\''.$arr_time[$i].'\',\''.$dep_time[$i].'\',\''.$lt_tmp.'\',\''.$lng_tmp.'\',\''.$type.'\');">
								<font color="green">
									&nbsp;(Show on map)
								</font>
							</a>
						</td>';
					//echo'<td class="text" align="left">'.$placename[$i].'&nbsp;</td>';
				}
		echo'<td class="text" align="left">'.$arr_time[$i].'</td>';			
		echo'<td class="text" align="left">'.$dep_time[$i].'</td>';
		if($in_tmp[$i]!="0.0" && $out_tmp[$i]!="0.0")
		{
			echo'<td class="text" align="left">'.$in_tmp[$i].'</td>';
			echo'<td class="text" align="left">'.$out_tmp[$i].'</td>';
		}
		$hms1 = secondsToTime($duration[$i]);
		$duration_tmp = $hms1[h].":".$hms1[m].":".$hms1[s];
		echo'<td class="text" align="left"><b>'.$duration_tmp.'</b></td>';
		echo'<td class="text" align="left"><b>'.$lt_tmp.", ".$lng_tmp.'</b></td>';
		echo'</tr>';							

		$placename1[$j][$k] = $placename[$i];
		$arr_time1[$j][$k] =  $arr_time[$i];					
		$dep_time1[$j][$k] = $dep_time[$i];
		$in_tmp1[$j][$k] =  $in_tmp[$i];					
		$out_tmp1[$j][$k] = $out_tmp[$i];
		$duration1[$j][$k] = $duration_tmp;
		$lat_lng[$j][$k] = $lt_tmp."/".$lng_tmp;
		//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
		//echo "<br>Before Two";
		//echo "<br>i=".$i." ,k=".$k;
		//echo "<br>imei_next=".$imei[$i+1]." ,imei_prev=".$imei[$i]; 		
		//if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
		if(($imei[$i+1] != $imei[$i]))
		{
		echo '<tr style="height:20px;background-color:lightgrey">
				<td class="text"><strong>Total<strong>&nbsp;</td>
				<td class="text"><strong><strong>&nbsp;</td>
				<td class="text"><strong>'.$date1.'</strong></td>	
				<td class="text"><strong>'.$date2.'</strong></td>';
				if($in_tmp[$i]!="0.0" && $out_tmp[$i]!="0.0")
				{
				echo'<td class="text"><strong><strong>&nbsp;</td>
					<td class="text"><strong><strong>&nbsp;</td>'; 
				}
				
				///if($k>0)
				{                            
					$hms_2 = secondsToTime($sum_halt_hr);
					$total_halt_hr[$j] = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
				}
				echo'<td class="text"><font color="red"><strong>'.$total_halt_hr[$j].'</strong></font></td>
				<td class="text"><strong><strong>&nbsp;</td>
			</tr>
		</table>';
			$no_of_data[$j] = $k;
		} 
		$k++;   
		$sno++;                       	  				
	}		
	//echo "<br>After";
	echo "</div>";	
	/*if($k==1)
	{
		$no_of_data[$j] = $k;
		echo'<tr style="height:20px;background-color:lightgrey">
				<td class="text"><strong>Total<strong>&nbsp;</td>
				<td class="text"><strong><strong>&nbsp;</td>
				<td class="text"><strong>'.$date1.'</strong></td>	
				<td class="text"><strong>'.$date2.'</strong></td>
				<td class="text"><font color="red"><strong>'.$duration_tmp.'</strong></font></td>     
			</tr>
		</table>';
	}*/	
	//PDF CODE
	echo '<form method="post" target="_blank">';	
	$csv_string = "";       
	for($x=0;$x<=$j;$x++)
	{												
		for($y=0;$y<=$no_of_data[$x];$y++)
		{
			$alt_ref="-";        
			//echo "<br>arr_time1[$x][$y]=".$arr_time1[$x][$y];          
			$pdf_place_ref = str_replace('"'," ",$placename1[$x][$y]);
			$pdf_arrival_time = $arr_time1[$x][$y];
			$pdf_depature_time= $dep_time1[$x][$y];
			if($in_tmp1[$x][$y]!="0.0" && $in_tmp1[$x][$y]!="0.0")
			{
				$pdf_in_tmp = $in_tmp1[$x][$y];
				$pdf_out_tmp= $out_tmp1[$x][$y];
			}
			$pdf_halt_duration = $duration1[$x][$y]; 
			$pdf_lat_lng=$lat_lng[$x][$y];
			if($y==0)
			{
				$title="Halt Report : ".$vname1[$x][$y]." (".$imei1[$x][$y].")-Interval:".$user_interval." mins";
				//echo "<br>pl=".$pdf_place_ref;
				$csv_string = $csv_string.$title."\n";
				$csv_string = $csv_string."SNo,Place,Arrival Time,Departure Time,Halt Duration (Hrs.min),Latitude/Longitude\n";
				echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";
			}				
			$sno_1 = $y+1;										
			echo"<input TYPE=\"hidden\" VALUE=\"$sno_1\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$pdf_place_ref\" NAME=\"temp[$x][$y][Place]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$pdf_arrival_time\" NAME=\"temp[$x][$y][ArrivalTime]\">";			
			echo"<input TYPE=\"hidden\" VALUE=\"$pdf_depature_time\" NAME=\"temp[$x][$y][DepartureTime]\">";
			if($in_tmp1[$x][$y]!="0.0" && $in_tmp1[$x][$y]!="0.0")
			{
				echo"<input TYPE=\"hidden\" VALUE=\"$pdf_in_tmp\" NAME=\"temp[$x][$y][In Temp]\">";			
				echo"<input TYPE=\"hidden\" VALUE=\"$pdf_out_tmp\" NAME=\"temp[$x][$y][Out Temp]\">";
			}
			echo"<input TYPE=\"hidden\" VALUE=\"$pdf_halt_duration\" NAME=\"temp[$x][$y][Duration of Halt (hr.min)]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$pdf_lat_lng\" NAME=\"temp[$x][$y][Latitude/Longitude]\">";
			/// CODE FOR CSV
			$pdf_place_ref = str_replace(',',':',$pdf_place_ref);
			//echo "<br>".$pdf_place_ref;
			if($in_tmp1[$x][$y]!="0.0" && $in_tmp1[$x][$y]!="0.0")
			{
				$csv_string = $csv_string.$sno_1.','.$pdf_place_ref.','.$pdf_arrival_time.','.$pdf_depature_time.','.$pdf_in_tmp.','.$pdf_out_tmp.','.$pdf_halt_duration.','.$pdf_lat_lng."\n"; 
			}
			else
			{
				$csv_string = $csv_string.$sno_1.','.$pdf_place_ref.','.$pdf_arrival_time.','.$pdf_depature_time.','.$pdf_halt_duration.','.$pdf_lat_lng."\n"; 
			}
			
			
			
			//echo "csv_string1=".$csv_string."<br><br>";
			////////////////////////////////////         	
		}	//inner for
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Place]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][ArrivalTime]\">";		
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DepartureTime]\">";
		if($in_tmp1[$x][$y]!="0.0" && $in_tmp1[$x][$y]!="0.0")
		{
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][In Temp]\">";		
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Out Tmp]\">";
		}
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Duration of Halt (hr.min)]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Latitude/Longitude]\">";		
		$m = $y+1;
		echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][Place]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$m][ArrivalTime]\">";		
		echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][DepartureTime]\">";
		if($in_tmp1[$x][$y]!="0.0" && $in_tmp1[$x][$y]!="0.0")
		{
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$m][In Temp]\">";		
			echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$m][Out Temp]\">";
		}
		if($k==1)
		{
			$total_halt_hr[$x] = $duration_tmp;
		}
		echo"<input TYPE=\"hidden\" VALUE=\"$total_halt_hr[$x]\" NAME=\"temp[$x][$m][Duration of Halt (hr.min)]\">";	
		if($in_tmp1[$x][$y]!="0.0" && $in_tmp1[$x][$y]!="0.0")
		{
			$csv_string = $csv_string.'Total,,'.$date1.','.$date2.',,,'.$total_halt_hr[$x]."\n"; 
		}
		else
		{
			$csv_string = $csv_string.'Total,,'.$date1.','.$date2.','.$total_halt_hr[$x]."\n"; 
		}
		//echo "csv_string2=".$csv_string."<br><br>";
	} // outer for
	if(sizeof($vname)==0)
	{						
		print"<center><FONT color=\"Red\" size=2><strong>No Halt Found</strong></font></center>";
		//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
		echo'<br><br>';
	}	
	else
	{
		//echo "csv_string3=".$csv_string."<br><br>";
		echo'<input TYPE="hidden" VALUE="halt" NAME="csv_type">';
		echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
		echo'<br><center><input type="button" onclick="javascript:report_csv(\'report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;
		<input type="button" onclick="javascript:report_csv(\'report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
		<!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';
	}
 echo'</form>
 </td>
 </tr>
 </table>
 </div>'; 
include("map_window/floating_map_window.php"); 
  /*if($report_type=='Person')
  {
    echo'<form method = "post" name="csv_form" action="src/php/report_csv.php" target="_blank">';
      echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';        
    echo '</form>';
  }	*/    
  
    
?> 
</body>
</html>	
