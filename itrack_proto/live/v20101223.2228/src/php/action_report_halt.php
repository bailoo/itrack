<?php
include_once('SessionVariable.php');
include_once("PhpMysqlConnectivity.php");

$vehicleid= $_POST["vehicleid"];
$vehicleid_size=sizeof($vehicleid);
for($i=0;$i<$vehicleid_size;$i++)
{
//echo"vehicleid=".$vehicleid[$i];
	if($i==0)
	{
		$query_for_v_serial="select VehicleSerial from vehicle where VehicleID='$vehicleid[$i]' and Status='ON'";
	}
	else
	{
		$query_for_v_serial=$query_for_v_serial." UNION select VehicleSerial from vehicle where VehicleID='$vehicleid[$i]' and Status='ON'";
	}
}
//echo "<br>query=".$query_for_v_serial;
	$result_query=mysql_query($query_for_v_serial,$DbConnection);
	//echo"resutl=".$result_query;
	$v_size=0;
	while($result_row=mysql_fetch_object($result_query))
	{
		$vehicle_serial[$v_size]=$result_row->VehicleSerial;
		//echo"vehicle_serial=".$vehicle_serial[$v_size];
		$v_size++;
	}

$date1 = $_POST["StartDate"];
$date2 =  $_POST["EndDate"];

$date_1 = explode(" ",$date1);
$date_2 = explode(" ",$date2);


$datefrom = $date_1[0];
$dateto = $date_2[0];

//echo "<br>datefrom=".$datefrom." dateto=".$dateto;

$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);


/////// GET ALL DATES BETWEEN DATEFROM AND DATETO
get_All_Dates($datefrom, $dateto, &$userdates);
//echo "<br>".sizeof($userdates);


date_default_timezone_set("Asia/Calcutta");
$current_date = date("Y-m-d");
//print "<br>CurrentDate=".$current_date;


$date_size = sizeof($userdates);

//echo "<br>datesize=".$date_size."<br> v_size=".$v_size;
$k1 = 0;
$m_start=0;

$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);


$user_interval = $_POST['user_interval'];

//$interval=$user_interval*60;

//echo "vehicle_id=".$vehicleid."user-interval=".$user_interval;

//////////////////////  GET XML ///////////////////////////////

for($i=0;$i<$date_size;$i++)
{
	//echo "<br>Date=".$userdates[$i];
	//echo "<br>Date".$userdates[$i];

	for($j=0;$j<$v_size;$j++)
	{	
//echo "vehicleid=".$vehicleid[$j];	
		$m_end=0;
		//echo "--Vserial=".$vehicleid[$j]." | Date=".$userdates[$i]."<br>";
		//var xml_file = "xml_vts/xml_data/"+user_dates[d]+"/"+vehicleSerial[v]+".xml";
/*if($vehicleid[$j]==501)
{
echo $vehicleid[$j]="359231030152902";	
}*/	
//echo"vehicle_serial=".$vehicle_serial[$j];
	
		if($userdates[$i] == $current_date)
		{			
			//echo "in else";
			$xml_file = "xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial[$j].".xml";						
		}		
		else
		{
			$xml_file = "sorted_xml_data/".$userdates[$i]."/".$vehicle_serial[$j].".xml";	
			//echo "not equal";
			//echo "<br>xml_file=".$xml_file;
		}

		$file_exist = 0;
		//echo "<br>xml_file=".$xml_file;
		
		if (file_exists($xml_file)) {
		   $xml = simplexml_load_file($xml_file);
		   $file_exist = 1;
		   //echo "The file $filename exists";
		} else {
		    //echo "The file $filename does not exist";			
		   continue;
		}
				
		//echo "<br>xml_file=".$xml_file;		
		
		if($file_exist)
		{
			//echo "<br>xml_file=".$xml_file;
			$marker_size = sizeof($xml->marker);
			$attrib_size2 = sizeof($xml->marker[0]->attributes());

			//echo "<br>att[0]value=".$xml->marker[0]->attributes();
			//echo "<br>attsize=".$attrib_size2."<br>";
			//echo "<br>markersize=".$marker_size."<br>";

			$m_start = $k1;
			
			for($k=0;$k<$marker_size; $k++)
			{
				$m=0;
				foreach($xml->marker[$k]->attributes() as $a => $b)
				{
					$arr[$m] = $b;
					$m++;
					//echo $a ."=".$b."<br>"; 	 
					//echo $a,'="',$b,"\"</br>";
				}
				
				//echo "<br>OUT=".$arr[0]."<br>";
				
				$xmldate = strtotime($arr[9]);

				//echo "<br>date=".$arr[9] ." $ ".$date1." $ ".$date2;
				//echo "<br>date=".$xmldate ." $ ".$date1tmp." $ ".$date2tmp;				
			
				if( ($xmldate >=$date1tmp) && ($xmldate <=$date2tmp) && ($xmldate!="") )
				{						
					$all_vehicle_records[$k1] = $arr[0]."$".$arr[1]."$".$arr[2]."$".$arr[3]."$".$arr[4]."$".$arr[5]."$".$arr[6]."$".$arr[7]."$".$arr[8]."$".$arr[9];
					//echo "<br>IN".$vehicle_records[$k1];
					
					$k1++;						
					
					/*$vehicleserial = $arr[0];
					$vehiclename =  $arr[1];
					$lat[$j] = $arr[2];
					$lng[$j] = $arr[3];
					$speed[$j] = $arr[4];
					$datetime[$j] = $arr[5];
					$fuel[$j] = $arr[6];
					$vehicletype[$j] = $arr[7];	*/			
				}
			}
			
			
			$m_end = $k1;
			
			// BEFORE SORTING
			//echo "<br><br>BEFORE SORTING<br>";
			
			/*for($m=$m_start;$m<$m_end;$m++)
			{
				echo "M=".$all_vehicle_records[$m]."<br>";
			}*/
			////// SORT DATA IF -CURRENT FOLDER DATE /////////////
			
						
			if($userdates[$i] == $current_date)
			{
				//echo "Current<br>";
				for($x = $m_start; $x < $m_end; $x++) {
				
					$main_arr = explode("$",$all_vehicle_records[$x]);									
					
					//$vehicleserial[$x] = $main_arr[0];
					//$vehiclename[$x] = $main_arr[1];
					//$lat[$x] = $main_arr[2];
					//$lng[$x] = $main_arr[3];
					//$speed[$x] = $main_arr[4];
					$datetime[$x] = $main_arr[9];
					//$fuel[$x] = $main_arr[6];
					//$vehicletype[$x] = $main_arr[7];					  
				  
					for($y = $m_start; $y < $m_end; $y++) {
							
						//echo "<br>dtx=".$datetime[$x]." dtx=".$datetime[$y]."<br>";

						$main_arr = explode("$",$all_vehicle_records[$y]);																	
						$datetime[$y] = $main_arr[9];
															
						$dtx = strtotime($datetime[$x]); 
						$dty = strtotime($datetime[$y]); 

						if($dtx < $dty) 
						{
						  //echo "IN<BR<BR>";						  
						  $hold = $all_vehicle_records[$x];
						  $all_vehicle_records[$x] = $all_vehicle_records[$y];
						  $all_vehicle_records[$y] = $hold;					  						  						  						  
						}
					}
				}
			}  /// SORTING CLOSED
		 
		} // if $file_exist closed
	} //INNER LOOP CLOSED
} // OUTER LOOP CLOSED


/////// MAKE INDIVIDUAL VEHICLE ARRAYS   ///////////

//echo "vsize=".$v_size."<br>";

$k2=0;

for($i=0;$i<$v_size;$i++)
{
	$k2=0;
	
	//echo "<br>k1=".$k1."<br>";

	for($j=0;$j<$k1;$j++)
	{	
		$main_arr = explode("$",$all_vehicle_records[$j]);
		
		$vehicle_serial1 = $main_arr[1];

		if($vehicle_serial[$i] == $vehicle_serial1)	
		{
			$vehicle_records[$i][$k2]  = $all_vehicle_records[$j];		
			$k2++;
		}
		//echo $vehicle_records[$i]."<br>";
	}
}

		
/////////////// GET RECORDS WHICH ARE MORE THAN 0.05 KM FROM PREVIOUS POINT


for($i=0;$i<$v_size;$i++)
{
	$c =0;	
	$main_arr = explode("$",$vehicle_records[$i][0]);
	$vserial = $main_arr[1];
	$vname = $main_arr[2];
	$lat_ref = $main_arr[5];
	$lng_ref = $main_arr[6];
	$datetime_ref = $main_arr[9];
	$halt_flag = 0;
	$date_secs1 = strtotime($datetime_ref);
	//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
	$date_secs1 = (double)($date_secs1 + $interval);
	
	//echo "<br>k2=".$k2."<br>";
	
	for($j=1; $j<$k2; $j++)
	{
		//echo"in j"."<br>";
		//echo "SS=".$vehicle_records[$i][$j]."<br>";
		$main_arr = explode("$",$vehicle_records[$i][$j]);
	
		//$vehicleserial = $main_arr[0];
		//$vehiclename = $main_arr[1];
		$lat_cr = $main_arr[5];
		$lng_cr = $main_arr[6];
		$datetime_cr = $main_arr[9];
		
		//$time2 = $datetime[$i][$j];											
		$date_secs2 = strtotime($datetime_cr);
		
		//echo "<br>str=".$lat_ref.", ".$lat_cr.", ".$lng_ref." ,".$lng_cr.", ". $datetime_cr;
		calculate_mileage($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);
		//echo "<br>distance=".$distance;
		///if (($distance > 0.200) || ($j = $k2 -1))
		//if($date_secs2 >= $date_secs1)
		//{
			if (($distance > 0.200) || ($j == $k2 -1))
			{
				//echo "<br>In dist ".$distance." lat_ref ".$lat_ref." lng_ref ".$lng_ref." lat_cr ".$lat_cr." lng_cr ".$lng_cr."<br>";
				if ($halt_flag == 1)
				{				
					//echo "<br>In flag1";
					//duration
					$arrivale_time=$datetime_ref;
					$starttime = strtotime($datetime_ref);
					if ($j == $k2 -1)
					{
						$main_arr = explode("$",$vehicle_records[$i][$j]);
					}
					else
					{
						$main_arr = explode("$",$vehicle_records[$i][$j-1]);
					}
					//$stoptime = strtotime($datetime_cr);  
					$stoptime = strtotime($main_arr[9]);
					$depature_time=$main_arr[9];
					//echo "<br>".$starttime." ,".$stoptime;
					
					$halt_dur =  ($stoptime - $starttime)/3600;
				
					$halt_duration[$j] = round($halt_dur,2);										
					$total_min = $halt_duration[$j] * 60;

					$total_min1[$j] = $total_min;
					
					//echo "toatal_min=".$total_min1[$j]."user-interval=".$user_interval;

					$hr = (int)($total_min / 60);
					$minutes = $total_min % 60;										

					$hrs_min = $hr.".".$minutes;
					if($total_min1[$j] >= $user_interval)
					{														
						$total_halt_vehicle[$i][$c] = $vname."$".$lat_ref."$".$lng_ref."$".$arrivale_time."$".$depature_time."$".$hrs_min;						
						//echo "<br>C=".$counter[$i];
						$c++;
					}					
					//echo "<br>".$total_halt_vehicle[$i][$c]."<br>";									
				}
				$lat_ref = $lat_cr;
				$lng_ref = $lng_cr;
				$datetime_ref= $datetime_cr;
				
				$halt_flag = 0;
			}
			else
			{
				//echo "in else";
				$halt_flag = 1;
			}			
			$date_secs1 = strtotime($datetime_cr);
			$date_secs1 = (double)($date_secs1 + $interval);			
		//}
		
	}
	
	//Store Number of results for each vehicle
	$counter[$i] = $c;
}

///echo"c=".$c;
function get_All_Dates($fromDate, $toDate, &$userdates)
{
	$dateMonthYearArr = array();
	$fromDateTS = strtotime($fromDate);
	$toDateTS = strtotime($toDate);

	for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24)) {
		// use date() and $currentDateTS to format the dates in between
		$currentDateStr = date("Y-m-d",$currentDateTS);
		$dateMonthYearArr[] = $currentDateStr;
	//print $currentDateStr.”<br />”;
	}

	$userdates = $dateMonthYearArr;
}


function calculate_mileage($lat1, $lat2, $lon1, $lon2, &$distance) 
{	
	$lat1 = deg2rad($lat1);
	$lon1 = deg2rad($lon1);

	$lat2 = deg2rad($lat2);
	$lon2 = deg2rad($lon2);
	
	$delta_lat = $lat2 - $lat1;
	$delta_lon = $lon2 - $lon1;
	
	$temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
	$distance = 3956 * 2 * atan2(sqrt($temp),sqrt(1-$temp));
	
	$distance = $distance*1.609344;	
}

///////// display record

/*for($i=0;$i<$v_size;$i++)
{
	
	echo'
	<table align="center">
	<tr>
		<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
	</tr>
	</table>
	<table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>
	<tr>
		<td class="text" align="left" width="4%"><b>SNo</b></td>
		<!--<td class="text" align="left"><b>Place</b></td>-->
		<td class="text" align="left"><b>Arrival</b></td>
		<td class="text" align="left"><b>Departure</b></td>
		<td class="text" align="left"><b>Duration of Halt (Hrs.min)</b></td>
	</tr>
	';
		//echo"c=".$c;				
	for($j=0;$j<$c;$j++)
	{		
		$data = explode("$",$total_halt_vehicle[$i][$j]);
		
		$sno = $j+1;
		$vname = $data[0];
		//echo"vname=".$vname;
		//$lat_ref = $data[1];
		//$lng_ref = $data[2];
		$arrival_time_1 = $data[3];
		$depature_time_1 = $data[4];
		$hrs_min = $data[5];

		
		echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';
			//echo'<td class="text" align="left"><b>Place</b></td>';
			//echo'<td class="text" align="left">'.$lat_ref.'</td>';
			//echo'<td class="text" align="left">'.$lng_ref.'</td>';
			echo'<td class="text" align="left">'.$arrival_time_1.'</td>';			
			echo'<td class="text" align="left">'.$depature_time_1.'</td>';
			echo'<td class="text" align="left"><b>'.$hrs_min.'</b></td>';
		echo'</tr>';
		
	}
	echo '</table>';
}*/


?>
<HTML>
<TITLE>Vehicle Tracking Pro-Innovative Embedded Systems Pvt. Ltd.</TITLE>
<head>
	<link rel="shortcut icon" href="./Images/iesicon.ico" >
	<LINK REL="StyleSheet" HREF="menu.css">
	
	<style type="text/css">
	@media print  { .noprint  { display: none; } }
	@media screen { .noscreen { display: none; } }
	</style>

	<script type=text/javascript src="menu.js"></script>

<?php

	include("MapWindow/MapWindow_halt_jsmodule.php");	

?>

	<script type="text/javascript">
	
		//function MapWindow(vname,datetime,lat,lng)
		function MapWindow(vname,arr_datetime,dept_datetime,lat,lng)
		{
			//alert(vname+" "+datetime+" "+lat+" "+lng);	
			//test2(vname,datetime,lat,lng);			
			document.getElementById("window").style.display = '';
			load_vehicle_on_map(vname,arr_datetime,dept_datetime,lat,lng);			
				
		}
				
	</script>	
		

</head>

<body bgcolor="white">


<?php
include("MapWindow/floating_map_window.php");
?>

	<?php
		if($access=="0")
		{
		  include('menu.php');
		}
		else if($access=="1")
		{
			if($login=="demouser")
			{
				include('liveusermenu.php');
			}
			else
			{
				include('usermenu.php');
			}
		}
		else if($access=="-2" || $access=="Zone")
		{
		  include('usermenu.php');
		}
		//include('usermenu.php');
	?>
		<td STYLE="background-color:white;width:85%;" valign="top">
			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
				<TR>
					<TD>
						<table border=0 width = 100% cellspacing=2 cellpadding=0>
							<tr>
								<td height=10 STYLE="background-color:#f0f7ff" class="text" align="center">Halt Report</td>
							</tr>
						</table>
						<?php
							if($access=="0")
								include("set_height.php");
							else if($access=="1" || $access=="-2" || $access=="Zone")
								include("set_user_height.php");
						?>
						<br>
	<br>
<?php
						
			if($access=='Zone')
			{
				include("get_mining_location.php");
			}
			else
			{
				include("get_location.php");
			}

			if($v_size>0)
			{
				//echo "<br>user_interval1=".$user_interval;
				
					echo '<center>';
					echo'<form method="post" action="HaltReportAction.php" target="_self">';
					echo'<SPAN STYLE="font-size: xx-small">Select Intervals </SPAN><select name="user_interval" onChange="this.form.submit();">';
							echo '<option value="0">0</option>';

							if($user_interval==5)
								echo '<option value="5" selected>5</option>';
							else
								echo '<option value="5">5</option>';

							if($user_interval==10)
								echo '<option value="10" selected>10</option>';
							else
								echo '<option value="10">10</option>';
														
							if($user_interval==15)
								echo '<option value="15" selected>15</option>';
							else
								echo '<option value="15">15</option>';
							
							if($user_interval==20)
								echo '<option value="20" selected>20</option>';
							else
								echo '<option value="20">20</option>';
							
							if($user_interval==25)
								echo '<option value="25" selected>25</option>';
							else
								echo '<option value="25">25</option>';
							
							if($user_interval==30)
								echo '<option value="30" selected>30</option>';
							else
								echo '<option value="30">30</option>';
							
							if($user_interval==35)
								echo '<option value="35" selected>35</option>';
							else
								echo '<option value="35">35</option>';
							
							if($user_interval==40)
								echo '<option value="40" selected>40</option>';
							else
								echo '<option value="40">40</option>';
							
							if($user_interval==45)
								echo '<option value="45" selected>45</option>';
							else
								echo '<option value="45">45</option>';

							if($user_interval==50)
								echo '<option value="50" selected>50</option>';
							else
								echo '<option value="50">50</option>';

							if($user_interval==55)
								echo '<option value="55" selected>55</option>';
							else
								echo '<option value="55">55</option>';

							if($user_interval==60)
								echo '<option value="60" selected>60</option>';
							else
								echo '<option value="60">60</option>';

							if($user_interval==90)
								echo '<option value="90" selected>90</option>';
							else
								echo '<option value="90">90</option>';

							if($user_interval==120)
								echo '<option value="120" selected>120</option>';
							else
								echo '<option value="120">120</option>';

							if($user_interval==150)
								echo '<option value="150" selected>150</option>';
							else
								echo '<option value="150">150</option>';

							if($user_interval==180)
								echo '<option value="180" selected>180</option>';
							else
								echo '<option value="180">180</option>';

							if($user_interval==210)
								echo '<option value="210" selected>210</option>';
							else
								echo '<option value="210">210</option>';

							if($user_interval==240)
								echo '<option value="240" selected>240</option>';
							else
								echo '<option value="240">240</option>';

							if($user_interval==270)
								echo '<option value="270" selected>270</option>';
							else
								echo '<option value="270">270</option>';

							if($user_interval==300)
								echo '<option value="300" selected>300</option>';
							else
								echo '<option value="300">300</option>';

							if($user_interval==330)
								echo '<option value="330" selected>330</option>';
							else
								echo '<option value="330">330</option>';

							if($user_interval==360)
								echo '<option value="360" selected>360</option>';
							else
								echo '<option value="360">360</option>';


						echo'</select>&nbsp;<SPAN STYLE="font-size: xx-small"> mins</SPAN>';

						//echo '<input type="hidden" name="uid1" value="'.$uid.'"';
						

						for($i=0;$i<$vehicleid_size;$i++)
						{
							//echo "<br>vid=".$vehicleid[$i];
							echo '<input type="hidden" name="vehicleid[]" value="'.$vehicleid[$i].'"';
						}
						//echo '<input type="hidden" name="vehicleid[]" value="-"';
						
						echo '<input type="hidden" name="StartDate" value="'.$date1.'"';
						echo '<input type="hidden" name="EndDate" value="'.$date2.'"';

				echo '</form>';
				echo '</center>';
				
				echo '<div align="center" style="width:100%;height:450px;overflow:auto;">';
				echo '<table border="0"><tr><td>';
				
				echo'<form method="post" action="getpdf2.php?size='.$v_size.'" target="_blank">';
					
					$alt ="-";
					
					for($i=0;$i<$v_size;$i++)
					{				
						//echo "<br>counter=".sizeof($counter[$i]);
						for($j=0;$j<$counter[$i];$j++)
						{		
							$data = explode("$",$total_halt_vehicle[$i][$j]);								
							
							$sno = $j+1;								
							$lat_ref_1[$i][$j] = $data[1];
							$lng_ref_1[$i][$j] = $data[2];
							//$latitude[$j]=$lat_ref;
							//$longitude[$j]=$lng_ref;
							//$altitude[$j]="";
							//echo "lat=".$latitude[$j]."lng=".$longitude[$j];
							$vname_1[$i][$j] = $data[0];
							$arrival_time_1[$i][$j] = $data[3];
							$depature_time_1[$i][$j] = $data[4];
							$hrs_min_1[$i][$j] = $data[5];

							if($access=='Zone')
							{
								get_location($lat_ref_1[$i][$j],$lng_ref_1[$i][$j],$alt,&$placename1,$zoneid,$DbConnection);
								$placename[$i][$j]=$placename1;	
							}
							else
							{
								get_location($lat_ref_1[$i][$j],$lng_ref_1[$i][$j],$alt,&$placename1,$DbConnection);
								//echo "place_name1=".$placename1;
								$placename[$i][$j]=$placename1;
								//echo "place_name2=".$placename[$j];
							}
							
							if($j==0)
							{
								$title="Halt Report : ".$vname_1[$i][$j] ;
								echo'
								<br><table align="center">
								<tr>
									<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
								</tr>
								</table>
								<table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>
								<tr>
									<td class="text" align="left" width="4%"><b>SNo</b></td>
									<td class="text" align="left"><b>Place</b></td>
									<td class="text" align="left"><b>Arrival</b></td>
									<td class="text" align="left"><b>Departure</b></td>
									<td class="text" align="left"><b>Duration of Halt (Hrs.min)</b></td>
								</tr>
								';
							}

							
							echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';
								//echo'<td class="text" align="left"><b>Place</b></td>';
								//echo'<td class="text" align="left">'.$lat_ref.'</td>';
								//echo'<td class="text" align="left">'.$lng_ref.'</td>';
								//echo'<td class="text" align="left">'.$placename[$j].'</td>';
								
								
								if($placename[$i][$j]=="")
								{
									echo'<td class="text" align="left">&nbsp;</td>';
								}
								else
								{																													
									//echo "lat=".$latitude[$j];
									echo'<td class="text" align="left">'.$placename[$i][$j].'<a href="javascript:MapWindow(\''.$vname_1[$i][$j].'\',\''.$arrival_time_1[$i][$j].'\',\''.$depature_time_1[$i][$j].'\',\''.$lat_ref_1[$i][$j].'\',\''.$lng_ref_1[$i][$j].'\');"><font color="green">&nbsp;(Show on map)</font></a></td>';
								}									
								
								echo'<td class="text" align="left">'.$arrival_time_1[$i][$j].'</td>';			
								echo'<td class="text" align="left">'.$depature_time_1[$i][$j].'</td>';
								echo'<td class="text" align="left"><b>'.$hrs_min_1[$i][$j].'</b></td>';
							echo'</tr>';							
						}
						echo '</table>';
					}
										
					//PDF CODE

					for($i=0;$i<$v_size;$i++)
					{												
						$alt_ref="-";
													
						for($j=0;$j<$counter[$i];$j++)
						{	
							$pdf_place_ref = $placename[$i][$j];
							$pdf_arrival_time = $arrival_time_1[$i][$j];
							$pdf_depature_time= $depature_time_1[$i][$j];
							$pdf_halt_duration = $hrs_min_1[$i][$j];
							
							if($j==0)
							{
								$title="Halt Report : ".$vname_1[$i][$j];
								//echo "<br>pl=".$pdf_place_ref;
								echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";
							}
							
							//echo "<br>".$vname_1[$i][$j].",".$pdf_halt_duration;
																						
							$sno_1 = $j+1;										
							echo"<input TYPE=\"hidden\" VALUE=\"$sno_1\" NAME=\"temp[$i][$j][SNo]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$pdf_arrival_time\" NAME=\"temp[$i][$j][Arrival]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$pdf_place_ref\" NAME=\"temp[$i][$j][Place]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$pdf_depature_time\" NAME=\"temp[$i][$j][Departure]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$pdf_halt_duration\" NAME=\"temp[$i][$j][Duration of Halt]\">";
						
						//}  //Outer if closed
							//$sno_1++;
						}
						//echo "<br>".$title." ,".$pdf_place_ref;
						//echo "<br>";
					}			
				
				if($c==0)
				{						
					print"<center><FONT color=\"Red\" size=2><strong>No Halt Found</strong></font></center>";
					//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
					echo'<br><br>';
				}	
				
				echo'<br><center><input type="submit" value="Get Report" class="noprint">&nbsp;<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;';
				echo'</form>';
				
				echo'</td></tr></table></div>';
				
			}
							
			
			/*else
			{
				print"<center><FONT color=\"Red\"><strong>Please Select At Least One Vehicle</strong></font></center>";
				//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
			}*/			
?>						
					 </TD>
				 </TR>
			</TABLE>
		</td>

	</tr>
</TABLE>

<?php
mysql_close($DbConnection);
?>

</BODY>
</HTML>
