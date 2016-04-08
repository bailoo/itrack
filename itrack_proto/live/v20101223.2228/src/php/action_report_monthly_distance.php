<?php

include_once('SessionVariable.php');
include_once("PhpMysqlConnectivity.php");

$size_suid=sizeof($suid);
$lastday=date('t',mktime(0,0,0,$month,1,$year));		// get last day
//echo $lastday;
$size=count($vehicleid);
$size=$size-1;

////////////////////////////////////////////////////////////////////////////

function calculate_mileage($lat1, $lat2, $lon1, $lon2, &$distance) 
{	
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

if($size)
{
for($i=0;$i<$size;$i++)				//UPPER LOOP		
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

	for($j=1;$j<=$lastday;$j++)					//fetch speed from tablename, find speed_sum & avg_speed
	{		
		////////////// daily Query

		//Calculate Distances
		
		if($j<=9)
			$Query5="select Latitude,Longitude from ".$tablename." where VehicleID='$vehicleid[$i]' and DateTime Like '".$year."-".$month."-0".$j."%' order by DateTime ASC";
		else
			$Query5="select Latitude,Longitude from ".$tablename." where VehicleID='$vehicleid[$i]' and DateTime Like '".$year."-".$month."-".$j."%'  order by DateTime ASC";
		
		//echo $Query5;
		//$Result = '';
		$Result5=mysql_query($Query5,$DbConnection);
		$num_row5 = mysql_num_rows($Result5);

		//echo "Q=".$Query5."<br>";
		//echo "R=".$Result."<br>";
		//echo "N=".$num_row5."<br>";
		
		if($num_row5>0)
		{		
			$d=0;
			$dist = 0;
			$distance = 0;

			while($row5=mysql_fetch_object($Result5))
			{				
				$latitude[$d] = $row5->Latitude;
				$longitude[$d] = $row5->Longitude;	
				//$dt = $row5->DateTime;
				
				if($latitude[$d]=="" || $latitude[$d]=="-" || $latitude[$d]=="0" || $longitude[$d]=="" || $longitude[$d]=="-" || $longitude[$d]=="0")
				{
				}
				else
				{
					if($d>0)
					{				
						calculate_mileage($latitude[$d-1],$latitude[$d],$longitude[$d-1],$longitude[$d],&$distance);
					}
					$dist = $dist + $distance;
					
					/*if($j==16)
						echo "<br>dist=".$dist." latp=".$latitude[$d]." lngp=".$longitude[$d]." lat2=".$latitude[$d-1]." lng2=".$longitude[$d-1]." DATETIME=".$dt;*/
					$d++;
				}
			}

			 //echo "dist=".$dist;
		
			$daily_distance[$i][$j] = round($dist,2);			//Avg speed 
			//echo "daily=".$daily_distance."<br>";

			$sum_distance = $sum_distance + $daily_distance[$i][$j];
			//echo " d=".$daily_distance[$i][$j];							
		}
		
	} // j closed
	
	$total_distance[$i] = $sum_distance;
	$total_distance[$i] = round($total_distance[$i],2);
	
	//echo " total_dist=".$total_distance[$i];

} // size closed 
}
else if($size_suid)
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

	for($j=1;$j<=$lastday;$j++)					//fetch speed from tablename, find speed_sum & avg_speed
	{		
		////////////// daily Query

		//Calculate Distances
		
		if($j<=9)
			$Query5="select Latitude,Longitude from ".$tablename." where VehicleID='$vehicleid[$i]' and DateTime Like '".$year."-".$month."-0".$j."%'";
		else
			$Query5="select Latitude,Longitude from ".$tablename." where VehicleID='$vehicleid[$i]' and DateTime Like '".$year."-".$month."-".$j."%'";
		
		
		//$Result = '';
		$Result5=mysql_query($Query5,$DbConnection);
		$num_row5 = mysql_num_rows($Result5);

		//echo "Q=".$Query5."<br>";
		//echo "R=".$Result."<br>";
		//echo "N=".$num_row5."<br>";
		
		if($num_row5>0)
		{		
			$d=0;
			$dist = 0;
			$distance = 0;

			while($row5=mysql_fetch_object($Result5))
			{				
				$latitude[$d] = $row5->Latitude;
				$longitude[$d] = $row5->Longitude;				
				
				if($latitude[$d]=="" || $latitude[$d]=="-" || $latitude[$d]=="0" || $longitude[$d]=="" || $longitude[$d]=="-" || $longitude[$d]=="0")
				{
				}
				else
				{
					if($d>0)
					{				
						calculate_mileage($latitude[$d-1],$latitude[$d],$longitude[$d-1],$longitude[$d],&$distance);
					}
					$dist = $dist + $distance;				
					
					$d++;
				}
			}

			 //echo "dist=".$dist;
			
				$daily_distance[$i][$j] = round($dist,2);			//Avg speed 
				//echo "daily=".$daily_distance."<br>";

				$sum_distance = $sum_distance + $daily_distance[$i][$j];
				//echo " d=".$daily_distance[$i][$j];							
		}
		
	} // j closed
	
	$total_distance[$i] = $sum_distance;
	$total_distance[$i] = round($total_distance[$i],2);
	
	//echo " total_dist=".$total_distance[$i];

} // size closed 
}


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
								<td height=10 STYLE="background-color:#f0f7ff" class="text" align="center">Monthly Distance Report</td>
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
echo'
						<form method = "post" action="distrp_pdf.php?size='.$size.'" target="_blank">

							<table align="center">
								<tr>
									<td class="text"><b>Distance Details(km):</b></td>
									<td>&nbsp;</td>
									<td class="text"><b>'.$m1.'-'.$year.'</td>
								</tr>
							</table>
';
if($size<3)
{
				echo '		<table align="center" border="1" rules=all  width="25%" bordercolor="#FFFFFF"					cellspacing=0 cellpadding=4 >
							<tr>
							 <td>	
							<div style="overflow: auto;height: 350px; width: 200px;" align="center">
							<table border=1  width="25%" bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=4>
					';
}					
else
{
				echo '		<table align="center" border="1" rules=all  width="60%" bordercolor="#FFFFFF"					cellspacing=0 cellpadding=4 >
							<tr>
							 <td>
							<div style="overflow: auto;height: 350px; width: 620px;" align="center">
							<table border=1  width="55%" bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>					
					';
}

				echo'	
							<tr>
								<td rowspan=2 class="text" align="center"><b>Date</b></td>
				';

							for($i=0;$i<$size;$i++)
							{
								echo'
								<td class="text" align="center"><b>'.$vehiclename[$i].'</b></td>
								';
							}
echo'
							</tr>


							<tr>
							';
							for($i=0;$i<$size;$i++)
							{
								echo'								
								<td class="text" align="center"><b>Distance (Km)</b></td>
								';
							}
							
							//monthly details

							for($i=1;$i<=$lastday;$i++)
							{
								$date = $i."/".$month;
								echo'
								<tr>
									<td class="text" align="center">'.$date.'</td>
									';
									for($j=0;$j<$size;$j++)
									{
										echo'										
										<td class="text" align="center">'.$daily_distance[$j][$i].'</td>
										';																	
									}
									

								echo'</tr>';
							}

							echo'
								<tr><td class="text" align="center">
								<strong>Total</strong>&nbsp;</td>
							';
							
							for($i=0;$i<$size;$i++)
							{
							echo'<td class="text" align="center"><strong>'.$total_distance[$i].'&nbsp;Kms</strong></td>';			
							}

							echo'</tr></table></div></td></tr></table>';

							for($i=0;$i<$size;$i++)
							{
								$title=$vehiclename[$i].": Distance Details(km) : ".$m1."-".$year;
								echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$i]\">";
								for($j=1;$j<=$lastday+1;$j++)
								{
									$k=$j-1;
									
									$date = $j."/".$month;									
									$distance = $daily_distance[$i][$j];
									$total_dist = $total_distance[$i];									

									//echo $date." ".$avgspd." ".$maxspd."<BR>";
									if($j<$lastday+1)
									{
									echo"<input TYPE=\"hidden\" VALUE=\"$date\" NAME=\"temp[$i][$k][Date]\">";						
									echo"<input TYPE=\"hidden\" VALUE=\"$distance\" NAME=\"temp[$i][$k][Distance (Km)]\">";	
									}
									
									if($j==$lastday+1)
									{												
										echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$i][$k][Date]\">";
										echo"<input TYPE=\"hidden\" VALUE=\"$total_dist\" NAME=\"temp[$i][$k][Distance (Km)]\">";	
									}
								}																
																	
							}											
							
?>
							
							<table align="center">
							<tr>
								<td><input type="submit" value="Get PDF" class="noprint">&nbsp;<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;</td>
							</tr>
							</table>
							</form>
						</div>
					 </TD>
				 </TR>
			</TABLE>
		</td>

	</tr>
</TABLE>
</BODY>
</HTML>