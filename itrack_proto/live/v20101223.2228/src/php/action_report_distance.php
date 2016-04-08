<?php

include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');	

$vsize=count($vehicleid);

$StartDate = $_POST['start_date'];
$EndDate = $_POST['end_date'];

$user_interval = $_POST['user_interval'];
//echo "vsize=".$vsize;

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

if($vsize)
{
	for($i=0;$i<$vsize;$i++)				//UPPER LOOP		
	{								//fetch vehicle name, tablename="t".tableid (by vehicleid)
		$sum_distance = 0;

		$Query1="select VehicleName from vehicle where VehicleID='$vehicleid[$i]'";   
		$Result1=mysql_query($Query1,$DbConnection);
		$row1=mysql_fetch_object($Result1);
		$vehiclename[$i]=$row1->VehicleName;

		$Query2="select TableID from vehicletable where VehicleID='$vehicleid[$i]'";	 
		$Result2=mysql_query($Query2,$DbConnection);
		$row=mysql_fetch_object($Result2);
		$tableid=$row->TableID;

		$tablename = "t".$tableid;
			
		$Query5="select DateTime,Latitude,Longitude from ".$tablename." where VehicleID='$vehicleid[$i]' and DateTime between '$StartDate' and '$EndDate'  order by DateTime ASC";
			
		//echo $Query5;
		//$Result = '';
		$Result5=mysql_query($Query5,$DbConnection);
		$num_row5 = mysql_num_rows($Result5);

		//echo "Q=".$Query5."<br>";
		//echo "R=".$Result."<br>";
		//echo "N=".$num_row5."<br>";
			
		$j=0;
		
		if($num_row5>0)
		{					
			$dist = 0;
			//$distance = 0;			

			while($row5=mysql_fetch_object($Result5))
			{				
				$latitude[$j] = $row5->Latitude;
				$longitude[$j] = $row5->Longitude;	
				$datetime[$i][$j] = $row5->DateTime;				
				
				if($latitude[$j]=="" || $latitude[$j]=="-" || $latitude[$j]=="0" || $longitude[$j]=="" || $longitude[$j]=="-" || $longitude[$j]=="0")
				{
				}
				else
				{
					if($j>0)
					{				
						$latitude[$j-1] = round($latitude[$j-1],4);
						$latitude[$j] = round($latitude[$j],4);
						$longitude[$j-1] = round($longitude[$j-1],4);
						$longitude[$j] = round($longitude[$j],4);
						
						calculate_mileage($latitude[$j-1],$latitude[$j],$longitude[$j-1],$longitude[$j],&$distance);
					
						$distance1[$i][$j] = $distance;						
						
						//echo "<br>lt1=".$latitude[$j-1]." lt2=".$latitude[$j]." lng1=".$longitude[$j]." lng2=".$longitude[$j]."datetime=".$datetime[$i][$j]." dist=".$distance;					
						}
					
					$j++;
				}
			}	

			$data_length[$i] = $j;			
		}

	} // size closed 
}

/*else if($size_suid)
{
	for($i=0;$i<$size_suid;$i++)				//UPPER LOOP		
	{								//fetch vehicle name, tablename="t".tableid (by vehicleid)
		$sum_distance = 0;

		$Query1="select VehicleName from vehicle where VehicleID='$vehicleid[$i]'";   
		$Result1=mysql_query($Query1,$DbConnection);
		$row1=mysql_fetch_object($Result1);
		$vehiclename[$i]=$row1->VehicleName;

		$Query2="select TableID from vehicletable where VehicleID='$vehicleid[$i]'";	 
		$Result2=mysql_query($Query2,$DbConnection);
		$row=mysql_fetch_object($Result2);
		$tableid=$row->TableID;

		$tablename = "t".$tableid;

		$Query5="select Latitude,Longitude from ".$tablename." where VehicleID='$vehicleid[$i]' and DateTime between '$StartDate' and '$EndDate'";
			
			
		//$Result = '';
		$Result5=mysql_query($Query5,$DbConnection);
		$num_row5 = mysql_num_rows($Result5);

		//echo "Q=".$Query5."<br>";
		//echo "R=".$Result."<br>";
		//echo "N=".$num_row5."<br>";
		
		if($num_row5>0)
		{		
			$j=0;
			$dist[$i][$j] = 0;
			$distance = 0;

			while($row5=mysql_fetch_object($Result5))
			{				
				$latitude[$j] = $row5->Latitude;
				$longitude[$j] = $row5->Longitude;				
				
				if($latitude[$j]=="" || $latitude[$j]=="-" || $latitude[$j]=="0" || $longitude[$j]=="" || $longitude[$j]=="-" || $longitude[$j]=="0")
				{
				}
				else
				{
					if($j>0)
					{				
						calculate_mileage($latitude[$j-1],$latitude[$j],$longitude[$j-1],$longitude[$j],&$distance);
					}
					$dist[$i][$j] = $dist[$i][$j] + $distance;		//Single distance		
					
					$j++;
				}
			}
		}
	} // size closed 
}*/


$m1=date('M',mktime(0,0,0,$month,1));
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
	<script language="javascript">
	</script>
</head>

<body bgcolor="white">
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

	?>
		<td STYLE="background-color:white;width:85%;" valign="top">
			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
				<TR>
					<TD>
						<table border=0 width = 100% cellspacing=2 cellpadding=0>
							<tr>
								<td height=10 STYLE="background-color:#f0f7ff" class="text" align="center">Distance Report</td>
							</tr>
						</table>
						
						<?php
							if($access=="0")
								include("set_height.php");
							else if($access=="1" || $access=="-2" || $access=="Zone")
								include("set_user_height.php");
						?>
						<br>
	<?php
						echo '<center>';
						echo'
						<form method = "post" action="DistanceReportAction.php" target="_self">';
						
						echo'<table align="center">
							<tr>
								<td class="text"></td>
								<td>&nbsp;</td>
								<td class="text"><b>From DateTime : ('.$StartDate.') and ('.$EndDate.')</td>
							</tr>
						</table>';						
						
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
							
				 		  
						  echo'<br><table align="center" border="1" rules=all  width="40%" bordercolor="#FFFFFF"					cellspacing=0 cellpadding=4>
							<tr>
							 <td>	
							<div style="overflow: auto;height: 360px; width: 620px;" align="center">
							
							<table border=1  width="60%" bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=4>';

							$interval = (double)$user_interval*60*60;	//In Seconds
							
							for($i=0;$i<$vsize;$i++)
							{
								$sno=0;
								$k = 0;								
								
								$totald = 0;
								
								echo'
								
								<br><br><div align="center" style="font-size:12px;"><strong>'.$vehiclename[$i].'</strong></div><br>

								<table border=1 width="90%" rules=all bordercolor="#689FFF" align="center" cellspacing=0 cellpadding=3>
								<tr>								
								<td class="text" align="left"><b>SNo</b></td>
								<td class="text" align="left"><b>DateTime From</b></td>
								<td class="text" align="left"><b>DateTime To</b></td>
								<td class="text" align="left"><b>Distance(km)</b></td>								
								</tr>';
								
								$total_distance[$i] =0;
								
								if($data_length[$i]=="")
								{
									echo'<tr>
									<td class="text" align="left">-</td>
									<td class="text" align="left">-</td>
									<td class="text" align="left">-</td>										
									<td class="text" align="left">-</td>	
									</tr>';
								}
								else
								{								
									$m = 0;
									$dist_arr[$m++] =0;
									
									for($j=0;$j<$data_length[$i];$j++)
									{
										//echo "<br>dist=".$distance1[$i][$j];
										
										if($j==0)
										{
											$time1 = $datetime[$i][$j];
											$date_secs1 = strtotime($time1);
											//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
											$date_secs1 = (double)($date_secs1 + $interval);
											//echo "<br>DateSec1 after=".$date_secs1;
											
											$dist_arr[$m]= (float) ($dist_arr[$m] + $distance1[$i][$j]);	
											$totald = $totald[$t] + $dist_arr[$m];
											
											$m++;
										}									
										
										else
										{											
											$time2 = $datetime[$i][$j];											
											$date_secs2 = strtotime($time2);	
											//echo "<br>Time1=".$time1." date_secs2=".$date_secs2;	
											
											$dist_arr[$m]= (float) ($dist_arr[$m] + $distance1[$i][$j]);	
																																	
											$totald = $totald + $dist_arr[$m];											
											
											$m++;								
											//echo "<br>Date1=".$date_secs1." Date2=".$date_secs2;
											//echo "time1 =".$time1." time2=".$time2." DIFF=".(double)($date_secs2 - $date_secs1);
											
											if($date_secs2 >= $date_secs1)
											{												
												//echo "<br>TotalDist=".$totald[$t-1];	
												//$final_distance = max($dist_arr);
												$final_distance = $totald;
												$final_distance = $final_distance;																								
												
												$total_distance[$i] = $total_distance[$i] + $final_distance;																																			
												
												array_splice($dist_arr,0);		// RESIZE ARRAYSIZE TO ZERO
												$dist_arr[$m++] =0;
												$m =0;												
												
												$sno++;												
												
												$final_distance = round($final_distance,2);
												echo'
												<tr>
												<td class="text" align="left">'.$sno.'</td>
												<td class="text" align="left">'.$time1.'</td>
												<td class="text" align="left">'.$datetime[$i][$j].'</td>
												<td class="text" align="left">'.$final_distance.'</td>										
												</tr>
												';	
												//store values for pdf
												$datetime1[$i][$k] = $time1;
												$datetime2[$i][$k] = $datetime[$i][$j];
												$final_distance1[$i][$k] = $final_distance;
												$k++;																							
												
												//reassign time1
												$time1 = $datetime[$i][$j];
												$date_secs1 = strtotime($time1);
												$date_secs1 = (double)($date_secs1 + $interval);	
												$totald = 0;												
											}
										}
									}	
									
									$k_arr[$i] = $k;
								}		
								
								$total_distance[$i] = round($total_distance[$i],2);
								if($total_distance[$i] == 0)
								{
									$total_distance[$i] = "NA";
								}
								
								echo '<tr style="height:20px;background-color:lightgrey"><td class="text"><strong>Total<strong></td>
								<td class="text" colspan="2">&nbsp;&nbsp;<strong>'.$StartDate.'&nbsp;&nbsp;-&nbsp;&nbsp;'.$EndDate.'</strong></td>									
								<td class="text"><font color="red"><strong>'.$total_distance[$i].'</strong></font></td>';									
								echo'</tr></table>';																																								
							}
							
							echo'</td></tr></table>';
							echo'</div></td></tr></table>';
							
							for($i=0;$i<$vsize;$i++)
							{
								//echo "<br>vid=".$vehicleid[$i];
								echo '<input type="hidden" name="vehicleid[]" value="'.$vehicleid[$i].'"/>';
							}
							echo '<input type="hidden" name="vehicleid[]" value="-"/>';
							
							echo '<input type="hidden" name="StartDate" value="'.$StartDate.'"/>';
							echo '<input type="hidden" name="EndDate" value="'.$EndDate.'"/>';								
							
							echo'</form>';

							
							echo'<form method = "post" action="getpdf4.php?size='.$vsize.'" target="_blank">';
							
							for($i=0;$i<$vsize;$i++)
							{
								$title=$vehiclename[$i].": Distance Report(km) From DateTime : ".$StartDate."-".$EndDate;
								echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$i]\">";
								
								$sno=0;
								for($j=0;$j<$k_arr[$i];$j++)
								{
									//$k=$j-1;
									$sno++;
									$date1 = $datetime1[$i][$j];
									$date2 = $datetime2[$i][$j];
									$final_dst = $final_distance1[$i][$j];																				
																												
									echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$i][$j][SNo]\">";
									echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$i][$j][DateTime From]\">";
									echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$i][$j][DateTime To]\">";
									echo"<input TYPE=\"hidden\" VALUE=\"$final_dst\" NAME=\"temp[$i][$j][Distance (kms)]\">";									
								}	

								echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$i][$j][SNo]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$i][$j][DateTime From]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$i][$j][DateTime To]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$i][$j][Distance (kms)]\">";
								
								$m = $j+1;
								
								echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$i][$m][SNo]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$StartDate\" NAME=\"temp[$i][$m][DateTime From]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$EndDate\" NAME=\"temp[$i][$m][DateTime To]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$total_distance[$i]\" NAME=\"temp[$i][$m][Distance (kms)]\">";									
							}															
							
						echo'<table align="center">
						<tr>
							<td><input type="submit" value="Get PDF" class="noprint">&nbsp;<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;</td>
						</tr>
				</table>
	</form>
  </center>';
	
?>						