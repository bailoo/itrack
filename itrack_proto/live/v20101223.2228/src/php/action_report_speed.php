<?php

include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

$vehicle_str = $_POST['vid'];

$vehicleid = explode(':',$vehicle_str);

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
	{	    		
    $Query1="select VehicleName from vehicle where VehicleID='$vehicleid[$i]'";   
		$Result1=mysql_query($Query1,$DbConnection);
		$row1=mysql_fetch_object($Result1);
		$vehiclename[$i]=$row1->VehicleName;

		$Query2="select TableID from vehicletable where VehicleID='$vehicleid[$i]'";
    //print_query($Query2);	 
		$Result2=mysql_query($Query2,$DbConnection);
		$row=mysql_fetch_object($Result2);
		$tableid=$row->TableID;
		
    $tablename = "t".$tableid;
			
		////////////// daily Query
		$Query3="select Distinct DateTime,Speed from ".$tablename." where VehicleID='$vehicleid[$i]' and Speed is NOT NULL and DateTime between '$StartDate' and '$EndDate' order by DateTime ASC";		
		//print_query($Query3);
		$Result3=mysql_query($Query3,$DbConnection);

		//echo "<br>numrows=".mysql_num_rows($Result3);
		$j=0;
		if(mysql_num_rows($Result3)>0)
		{			
			$sumspeed=0;
			while($row3=mysql_fetch_object($Result3))
			{
				$datetime[$i][$j] = $row3->DateTime;
				$speed[$i][$j] = $row3->Speed;
				
				$j++;
			}			
		}		

		$data_length[$i] = $j;	
		
	} // size closed
}

$m1=date('M',mktime(0,0,0,$month,1));
					
echo '<center>';
echo'
<form method = "post" action="action_report_speed.php" target="_self">';
	
  echo'
  <table border=0 width = 100% cellspacing=2 cellpadding=0>
  <tr>
  	<td height=10 class="report_heading" align="center">Speed Report</td>
  </tr>
  </table><br>';
    
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
	//echo "Seconds=".$time_interval;
	
	for($i=0;$i<$vsize;$i++)
	{
		$sno=0;
		$k=0;
		$max_counter=0;
		
		echo'
	
		<br><br><div align="center" style="font-size:12px;"><strong>'.$vehiclename[$i].'</strong></div><br>

		<table border=1 width="90%" rules=all bordercolor="#689FFF" align="center" cellspacing=0 cellpadding=3>
		<tr>								
		<td class="text" align="left"><b>SNo</b></td>
		<td class="text" align="left"><b>DateTime From</b></td>
		<td class="text" align="left"><b>DateTime To</b></td>
		<td class="text" align="left"><b>Avg Speed (km/hr)</b></td>	
		<td class="text" align="left"><b>Max Speed (km/hr)</b></td>								
		</tr>';
		
		//echo "<br>data_length=".$data_length[$i];										
		$total_avg_speed[$i] =0;
		$total_max_speed[$i] =0;
		
		//echo "<br>i=".$i." data_length[i]=".$data_length[$i];
		
		if($data_length[$i]=="")
		{
			echo'<tr>
			<td class="text" align="left">-</td>
			<td class="text" align="left">-</td>
			<td class="text" align="left">-</td>	
			<td class="text" align="left">-</td>	
			<td class="text" align="left">-</td>									
			</tr>';									
		}
		else
		{								
			$m = 0;																		
			
			for($j=0;$j<$data_length[$i];$j++)
			{
				//echo '<br>datetime[i][j]='.$datetime[$i][$j];										
				if($j==0)
				{
					$time1 = $datetime[$i][$j];
					$date_secs1 = strtotime($time1);
					//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
					$date_secs1 = (double)($date_secs1 + $interval);
					//echo "<br>DateSec1 after=".$date_secs1;
					
					$speed_arr[$m++] = $speed[$i][$j];											
				}																			
				
				else
				{											
					$time2 = $datetime[$i][$j];											
					$date_secs2 = strtotime($time2);	

					//echo "<br>Time1=".$time1." date_secs2=".$date_secs2;												
					$speed_arr[$m++] = $speed[$i][$j];
					
					//echo "<br>Date1=".$date_secs1." Date2=".$date_secs2;
					//echo "time1 =".$time1." time2=".$time2." DIFF=".(double)($date_secs2 - $date_secs1);
					
					if($date_secs2 >= $date_secs1)
					{
						//echo "in";
						//echo "date_sec1=".$date_secs1." date_sec2=".$date_sec2." time_int=".$time_interval." datetime=".$datetime[$i][$j];										
						
						$avg_speed = array_sum($speed_arr)/sizeof($speed_arr);	
						$max_speed = max($speed_arr);
						
						$avg_speed = round($avg_speed,2);
						$max_speed = round($max_speed,2);
						
						$total_avg_speed[$i] = $total_avg_speed[$i] + $avg_speed;
						$total_max_speed[$max_counter++] = $max_speed;
																											
						array_splice($speed_arr,0);		// RESIZE ARRAYSIZE TO ZERO
						$m =0;
						
						$sno++;												
						
						echo'
						<tr>
						<td class="text" align="left">'.$sno.'</td>
						<td class="text" align="left" width="30%">'.$time1.'</td>												
						<td class="text" align="left" width="30%">'.$datetime[$i][$j].'</td>
						<td class="text" align="left">'.$avg_speed.'</td>	
						<td class="text" align="left">'.$max_speed.'</td>													
						</tr>
						';		

						//store values for pdf
						$datetime1[$i][$k] =  $time1;
						$datetime2[$i][$k] =  $datetime[$i][$j];
						$avg_speed1[$i][$k] = $avg_speed;
						$max_speed1[$i][$k] = $max_speed;
						$k++;												
						
						//reassign time1
						$time1 = $datetime[$i][$j];
						$date_secs1 = strtotime($time1);
						$date_secs1 = (double)($date_secs1 + $interval);
					}											
				}
			}	
			$k_arr[$i] = $k;																		
		}	
		
		echo '<tr style="height:20px;background-color:lightgrey"><td class="text"><strong>Total<strong>&nbsp;</td>
		<td class="text" colspan="2">&nbsp;&nbsp;<strong>'.$StartDate.'&nbsp;&nbsp;-&nbsp;&nbsp;'.$EndDate.'</strong></td>									
		<td class="text"><font color="red"><strong>';
		
		if($k_arr[$i]>0)
		{
			$total_avg_speed[$i] = round(($total_avg_speed[$i]/$k_arr[$i]),2);
		}
		else
		{
			$total_avg_speed[$i] = "NA";
		}
		
		if($k_arr[$i]>0)
		{									
			$final_max_speed[$i] = max($total_max_speed);									
			$final_max_speed[$i] = round($final_max_speed[$i],2);
		}
		else
		{
			$final_max_speed[$i] = "NA";
		}								
			
			
		echo $total_avg_speed[$i].'</strong></font></td>
		<td class="text"><font color="red"><strong>'.$final_max_speed[$i].'</strong></font></td></tr>';
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

	echo'<form method = "post" action="src/php/report_getpdf_type4.php?size='.$vsize.'" target="_blank">';
	
	for($i=0;$i<$vsize;$i++)
	{								
		$title=$vehiclename[$i].": Speed Report(km/hr) From DateTime : ".$StartDate."-".$EndDate;
		echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$i]\">";
		
		$sno=0;
		for($j=0;$j<$k_arr[$i];$j++)
		{
			//$k=$j-1;
			$sno++;
			$date1 = $datetime1[$i][$j];	
			$date2 = $datetime2[$i][$j];										
			$avgspd = $avg_speed1[$i][$j];
			$maxspd = $max_speed1[$i][$j];
			
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$i][$j][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$i][$j][DateTime From]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$i][$j][DateTime To]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$avgspd\" NAME=\"temp[$i][$j][Avg Speed (km/hr)]\">";									
			echo"<input TYPE=\"hidden\" VALUE=\"$maxspd\" NAME=\"temp[$i][$j][Max Speed (km/hr)]\">";									
		}		

		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$i][$j][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$i][$j][DateTime From]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$i][$j][DateTime To]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$i][$j][Avg Speed (km/hr)]\">";									
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$i][$j][Max Speed (km/hr)]\">";	
		
		$m = $j+1;								
		
		echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$i][$m][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$StartDate\" NAME=\"temp[$i][$m][DateTime From]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$EndDate\" NAME=\"temp[$i][$m][DateTime To]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$total_avg_speed[$i]\" NAME=\"temp[$i][$m][Avg Speed (km/hr)]\">";									
		echo"<input TYPE=\"hidden\" VALUE=\"$final_max_speed[$i]\" NAME=\"temp[$i][$m][Max Speed (km/hr)]\">";																																		
	}																						
					
	echo'<table align="center">
	<tr>
		<td><input type="submit" value="Get PDF" class="noprint">&nbsp;<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;</td>
	</tr>
	</table>
	</form>
</center>';
?>				