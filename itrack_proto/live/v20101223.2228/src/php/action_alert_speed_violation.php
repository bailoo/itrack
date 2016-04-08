<?php
include_once('SessionVariable.php');
include_once("PhpMysqlConnectivity.php");

$uid=$_POST['rep_uid'];
$size=sizeof($vehicleid);
$size=$size-1;

$s_date=$_POST['StartDate'];
$end_date=$_POST['EndDate'];
$radio_button=$_POST['rec'];

$end_timestamp=strtotime($end_date);
$end_date2=explode(" ",$end_date);
$end_date1=explode("/",$end_date2[0]);

$et_y=$end_date1[0];
$et_m=$end_date1[1];
$et_d=$end_date1[2];
$et_d_1='01';

$EndDate=$et_y."-".$et_m."-".$et_d_1;

$et_d_s=$et_d*86400;
$enddate1=$end_timestamp-$et_d_s;
$enddate2=date('Y-m-d H:i:s',$enddate1);

date_default_timezone_set('Asia/Calcutta');
$Current_Date_Time=date('Y/m/d H:i:s');
$Current_Date_Only=explode(" ",$Current_Date_Time);				
$current_date_only=explode("/",$Current_Date_Only[0]);

$c_y=$current_date_only[0];
$c_m=$current_date_only[1];

$start_date=explode(" ",$s_date);				
$Start_date_only=explode("/",$start_date[0]);
$s_y=$Start_date_only[0];
$s_m=$Start_date_only[1];

$startdate = str_replace("/","-",$s_date);
$enddate5 = str_replace("/","-",$end_date);
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
	
	<?php
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
</head>

<body bgcolor="white">
	<?php
	
		include("MapWindow/floating_map_window.php");

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
								<td height=10 STYLE="background-color:#f0f7ff" class="text" align="center">Speed violation Report</td>
							</tr>
						</table>
						<?php
							if($access=="0")
								include("set_height.php");
							else if($access=="1" || $access=="-2" || $access=="Zone")						
								include("set_user_height.php");
						?>			
						
<?php


function calculate_mileage($lat1, $lat2, $lon1, $lon2, &$distance) 
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
}
if($size>0)
{
	
	echo'<br><form method="post" action="getpdf2.php?size='.$size.'" target="_blank">';
	
	for($i=0;$i<$size;$i++)
	{
		$Querystr="SELECT VehicleID,TableID FROM vehicletable WHERE VehicleID= '$vehicleid[$i]'";
		$Result = @mysql_query($Querystr, $DbConnection);
		$row = @mysql_fetch_object($Result);

		$tableids= $row->TableID;				//tableid
		$vehicleids= $row->VehicleID;

		$Query = "Select TableName from `table` WHERE TableID='$tableids'";	
		$Result = @mysql_query($Query, $DbConnection);
		$row = @mysql_fetch_object($Result);
		$tablename= $row->TableName;

		if($c_y==$s_y && $c_m==$s_m && $c_y==$et_y && $c_m==$et_m)
		{			
		$Query="Select Speed,VehicleID,DateTime,Latitude,Longitude,Altitude from $tablename WHERE VehicleID='$vehicleids' AND DateTime between '$startdate' and '$enddate5'";	
		}
		else
		{
			if($c_y==$et_y && $c_m==$et_m)
			{
				if($c_y==$s_y && $c_y==$et_y && $c_m==$et_m)
				{
					$E_Date=$et_y."-".$et_m."-".$et_d_1." "."00:00:00";
					$endate3=explode(" ",$enddate2);
					$endate4=$endate3[0]." "."23:59:59";
					$yearly_table=$tablename."_".$s_y;
					$Query="Select Speed,VehicleID,DateTime,Latitude,Longitude,Altitude from $tablename WHERE VehicleID='$vehicleids' AND DateTime between '$E_Date' and '$enddate5'";								
					$Query=$Query."UNION ALL Select Speed,VehicleID,DateTime,Latitude,Longitude,Altitude from $yearly_table WHERE VehicleID='$vehicleids' AND DateTime between '$startdate' and '$endate4'";			
				}
				else if($c_y!=$s_y && $c_y==$et_y && $c_m==$et_m)
				{
					$E_Date=$et_y."-".$et_m."-".$et_d_1." "."00:00:00";
					$endate3=explode(" ",$enddate2);
					$endate4=$endate3[0]." "."23:59:59";
					$st=explode("-",$endate3[0]);
					$enddt1=$st[0]."-"."01"."-"."01"." "."00:00:00";
					$yearly_table1=$tablename."_".$st[0];
					$yearly_table2=$tablename."_".$s_y;
					$sdt2=$s_y."-"."12"."-"."31"." "."23:59:59";					
					$Query="Select Speed,VehicleID,DateTime,Latitude,Longitude,Altitude from $tablename WHERE VehicleID='$vehicleids' AND DateTime between '$E_Date' and '$enddate5'";
					$Query=$Query."UNION ALL Select Speed,VehicleID,DateTime,Latitude,Longitude,Altitude from $yearly_table1 WHERE VehicleID='$vehicleids' AND DateTime between '$enddt1' and '$endate4'";
					$Query=$Query."UNION ALL Select Speed,VehicleID,DateTime,Latitude,Longitude,Altitude from $yearly_table2 WHERE VehicleID='$vehicleids' AND DateTime between '$startdate' and '$sdt2'";				
				}
			}

			else if($s_y!=$et_y)
			{
				$enddt1=$s_y."-"."12"."-"."31"." "."23:59:59";
				$sdt2=$et_y."-"."01"."-"."01"." "."00:00:00";

				$yearly_table=$tablename."_".$s_y;
				$yearly_table1=$tablename."_".$et_y;
				
				$Query="Select Speed,VehicleID,DateTime,Latitude,Longitude,Altitude from $yearly_table WHERE VehicleID='$vehicleids' AND DateTime between '$startdate' and '$enddt1'";
				$Query=$Query."UNION ALL Select Speed,VehicleID,DateTime,Latitude,Longitude,Altitude from $yearly_table1 WHERE VehicleID='$vehicleids' AND DateTime between '$sdt2' and '$enddate5'";			
			}
			else if($s_y==$et_y)
			{
				$yearly_table=$tablename."_".$s_y;
				$Query="Select Speed,VehicleID,DateTime,Latitude,Longitude,Altitude from $yearly_table WHERE VehicleID='$vehicleids' AND DateTime between '$startdate' and '$enddate5'";			
			}
		}	
		$Result = @mysql_query($Query,$DbConnection);	
		$num_rows = @mysql_num_rows($Result);
		$Query2="Select VehicleName from vehicle WHERE VehicleID='$vehicleids'";	
		$Result2 = @mysql_query($Query2, $DbConnection);						
		if($row2=@mysql_fetch_object($Result2))
			$vehiclename[$i]=$row2->VehicleName;

		if($num_rows>0)
		{
			if($access=="Zone")
			{
				include_once("get_mining_location.php");
			}
			else
			{
				include_once("get_location.php");
			}

			$x=0;
			$j=0;
							
			while($row=@mysql_fetch_object($Result))
			{		
				$speed[$x]=$row->Speed;
				$vehicle_id[$x]=$row->VehicleID;				

				$Query2="Select * from vehicle WHERE VehicleID='$vehicle_id[$x]'";	
				$Result2 = @mysql_query($Query2, $DbConnection);
				$row2=@mysql_fetch_object($Result2);
				$latitude[$x]=$row->Latitude;
				$longitude[$x]=$row->Longitude;
				if($latitude[$x]=="-" || $latitude[$x]=="" || $latitude[$x]=="-" || $longitude[$x]=="" || $longitude[$x]=="0" || $longitude[$x]=="0")
				{
				}
				else
				{
					$maxspeed[$x] = $row2->MaxSpeed;				
					$altitude[$x]=$row->Altitude;				
					
					if($speed[$x]>$maxspeed[$x])
					{
						$speed_1[$j]=$speed[$x];
						$maxspeed_1[$j]=$maxspeed[$x];						
						if($radio_button == "gmt")
						{
							$datetime[$j]=$row->DateTime;			
							$start_timestamp=strtotime($datetime[$j]);
							$start_gmt_time=$start_timestamp-5400;
						
							$gmt_start_date=date('Y-m-d H:i:s',$start_gmt_time);
							date_default_timezone_set('Asia/Calcutta');
							$datetime1[$j]=$gmt_start_date;
						}
						else if($radio_button == "ist")
						{
							$datetime1[$j]=$row->DateTime;				
						}							
						//get_location($latitude[$x],$longitude[$x],$altitude[$x],&$placename[$j],$zoneid,$DbConnection);
						get_location($latitude[$x],$longitude[$x],$altitude[$x],&$placename[$j],$DbConnection);
						$lat_1[$j] = $latitude[$x];
						$lng_1[$j] = $longitude[$x];
						$place[$j]=$placename[$j];				
						$sno[$j]=$j+1;		
						$j++;						
					}
				}
				$x++;
			}	
		}					
		if($num_rows>0)
		{
					$title1="Speed Violation Report: From $startdate To $enddate5";	
					
echo'  
												
					<table align="center">
					<tr><td class="text"><b>'.$title1.'</b></td></tr>
					<tr><td class="text"><center><b>';echo "[ ".$vehiclename[$i]." ]"; echo '</b></center>
					<div style="height:8px;"></div></td></tr>
					</table>
					<table border=1 width="80%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=0>
					<tr>
						<td class="text" align="left" width="5%"><b>SNo</b></td>										
						<td class="text" align="left"><b>Location</b></td>
						<td class="text" align="left"><b>Speed</b></td>
						<td class="text" align="left"><b>Max Speed</b></td>
						<td class="text" align="left"><b>Date</b></td>
					</tr>
';
						$datetime2="";
					for($x=0;$x<$j;$x++)
					{
						if($datetime2!=$datetime1[$x])
						{
					echo'						
						<tr>
						<td class="text" align="left">'.$sno[$x].'</td>';										
						//echo'<td class="text" align="left">'.$place[$x].'</td>';
												
						if($place[$x]=="")
						{
							echo'<td class="text">&nbsp;-&nbsp;</td>';
						}
						else
						{
							echo'<td class="text">'.$place[$x].'<a href="javascript:MapWindow(\''.$vehiclename[$i].'\',\''.$datetime1[$x].'\',\''.$lat_1[$x].'\',\''.$lng_1[$x].'\');"><font color="green">&nbsp;(Show on map)</font></a></td>';
						}
							
						
						echo
						'<td class="text" align="left">'.$speed_1[$x].'</b></td>
						<td class="text" align="left">'.$maxspeed_1[$x].'</td>
						<td class="text" align="left">'.$datetime1[$x].'</td>
						</tr>';
						}

						$datetime2=$datetime1[$x];
					}

					echo'</table><br>';

					$title1="Speed Violation Report: From $startdate To $enddate [ ".$vehiclename[$i]." ]";			
					echo"<input TYPE=\"hidden\" VALUE=\"$title1\" NAME=\"title[$i]\">";								
					for($k=0;$k<=$j;$k++)
					{
						$serno = $sno[$k];
						$plc = $place[$k];
						$spd = $speed_1[$k];
						$mxspd = $maxspeed_1[$k];
						$dt = $datetime1[$k];								
						echo"<input TYPE=\"hidden\" VALUE=\"$serno\" NAME=\"temp[$i][$k][SNo]\">";				
						echo"<input TYPE=\"hidden\" VALUE=\"$plc\" NAME=\"temp[$i][$k][Location]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$spd\" NAME=\"temp[$i][$k][Speed]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$mxspd\" NAME=\"temp[$i][$k][Max Speed]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$dt\" NAME=\"temp[$i][$k][Date]\">";
					}

		} //num_row closed

	} //for closed
								
	if($num_rows>0)
	{
		echo'
						<table align="center">
							<tr><td align="center"><input type="submit" value="Get Report" class="noprint">&nbsp;<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;</td></tr>
						</table>
					</form>
		';
	}
	else if($num_rows<0)
	{
			print"<center><FONT color=\"Red\"><strong>No Speed Voilation in This Duration</strong></font></center>";
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=SpeedReport.php\">";
	}

}	// if closed					

						
	 
?>
						</div>
		&nbsp;			 </TD>
				 </TR>
			</TABLE>
		</td>

	</tr>
</TABLE>
</BODY>
</HTML>