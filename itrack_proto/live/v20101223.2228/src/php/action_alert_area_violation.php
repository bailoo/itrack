<?php

include_once('SessionVariable.php');
include_once("PhpMysqlConnectivity.php");

include_once("AreaViolation/check_with_range.php");
include_once("AreaViolation/pointLocation.php");


$startdate = $_POST['StartDate'];
$enddate = $_POST['EndDate'];


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

$vehicleid = $_POST['vehicleid'];

$size_vid = sizeof($vehicleid);
$size_vid = $size_vid-1;

//echo "size=".$size_vid." v=".$vehicleid[0]."<br>";

//////////// SELECT VEHICLE RECORDS  ////////////////

if($size_vid>0)
{	
	include("get_location.php");
	
	echo'<br><form method="post" action="getpdf2.php?size='.$size_vid.'" target="_blank">';
		
	$vdata_found=0;
	
	for($i=0;$i<$size_vid;$i++)
	{
		$Querystr="SELECT VehicleID,TableID FROM vehicletable WHERE VehicleID= '$vehicleid[$i]'";
		$Result = @mysql_query($Querystr, $DbConnection);
		$row = @mysql_fetch_object($Result);

		$tableids= $row->TableID;				//tableid
		$vehicleid1= $row->VehicleID;

		$Query = "Select TableName from `table` WHERE TableID='$tableids'";	
		$Result = @mysql_query($Query, $DbConnection);
		$row = @mysql_fetch_object($Result);
		$tablename= $row->TableName;
		
		//echo "t=".$tablename;
		$Query="Select VehicleID,DeviceIMEINo,DateTime,Latitude,Longitude,Altitude from $tablename WHERE VehicleID='$vehicleid1' AND DateTime between '$startdate' and '$enddate' order by DateTime Asc";	
				
		$Result = @mysql_query($Query,$DbConnection);	
		$num_rows = @mysql_num_rows($Result);

		////////////////////////////////////////////////////
		$name_query = "select VehicleName from vehicle where VehicleID='$vehicleid1'";
		$name_res = mysql_query($name_query,$DbConnection);
		$row_name = mysql_fetch_object($name_res);
		
		$vehiclename[$i]=$row_name->VehicleName;
				
		//echo $Query;
		
		if($num_rows>0)
		{
			$x=0;			
			
			while($row=@mysql_fetch_object($Result))
			{						
				$vehicle_id[$x]=$row->VehicleID;					
				
				$latitude[$x]=$row->Latitude;												
				$latitude[$x]=$row->Latitude;
				$longitude[$x]=$row->Longitude;
				
				$date[$x]=$row->DateTime;
				$x++;
			}			
		}
		
		$query_geo = "select LatLongCoord from geofencing_record where VehicleID='$vehicleid[$i]'";
		$res_geo = mysql_query($query_geo,$DbConnection);
		if($row_geo = mysql_fetch_object($res_geo))
		{
			$geo_coord = $row_geo->LatLongCoord;
		}
		
		
		$v=0;
		$v_flag=0;
		
		//echo "<br>query_geo=".$query_geo;
		//echo "<br>geocoord=".$geo_coord."<br>";
		
		//echo "<br>x=".$x."<br>";
		if($geo_coord!="")
		{
			for($j=0;$j<$x;$j++)
			{	
				//echo "<br>lat=".$latitude[$j]." lng=".$longitude[$j]." <br>geocoord=".$geo_coord."<br>";
				/////////////CHECK THE DATA IN GEOFENCE AREA//////////////////////////
				check_with_range($latitude[$j], $longitude[$j], $geo_coord, &$status);
				//////////////////////////////////////////////////////////////////////
				
				if($status==0)		// -IF VEHICLE IS OUTSIDE  [VIOLATION RETURNS 0 OR 1 ( 0 = OUTSIDE OF THE AREA )] 
				{
					//echo "<br>Status=".$status;					
					
					if($v_flag==0)	// - IF VEHICLE WAS INSIDE EARLIER v_flag = 0 for inside
					{					
						$datetime[$i][$v] = $date[$j];				
						$lat[$i][$v] = $latitude[$j];
						$long[$i][$v] = $longitude[$j];
						$alt = "-";
						$lat1 = $lat[$i][$v];
						$long1 = $long[$i][$v];
						//echo "<br>lt=".$lat1." lng=".$long1;
						get_location($lat1,$long1,$alt,&$place,$DbConnection);
						$placename[$i][$v] = $place;
						
						
						// if($v>0)		// IF DATA IS > 0
						// {
							// $date_prev = $datetime[$i][$v-1];		// GET PREVIOUS DATE
							// $date_next = $datetime[$i][$v];			// GET CURRENT DATE
							// echo "<br>PREV DATE=".$date_prev." <BR>NEXT DATE=".$date_next."<br>";			
							
							// $time1 = strtotime($date_prev);
							// $time2 = strtotime($date_next);
							
							//GET DURATION OF AREA VIOLATION ////
							// $av_dur =  ($time2 - $time1)/3600;	 	// GET DIFFERENCE (HR.MINS)
							// $av_dur= round($av_dur,2);										
							// $total_min = $av_dur * 60;																		
		
							// $hr = (int)($total_min / 60);
							// $minutes = $total_min % 60;										
		
							// echo "<br>hr=".$hr." min=".$minutes;	
							
							// $hrs_min[$i][$v-1] = $hr.".".$minutes;																		
						// }		
						
						$v++;				//COUNTER
						$v_flag =1;
						$vdata_found=1;		//FOR DISPLAY			
						
					}
					else if ($j==($x-1) )
					{
						$v_flag = 0;
						$date_prev = $datetime[$i][$v-1];		// GET PREVIOUS DATE
						$date_next = $date[$j];			// GET CURRENT DATE
						//echo "<br>PREV DATE=".$date_prev." <BR>NEXT DATE=".$date_next."<br>";			
						
						$time1 = strtotime($date_prev);
						$time2 = strtotime($date_next);
						
						////GET DURATION OF AREA VIOLATION ////
						$av_dur =  ($time2 - $time1)/3600;	 	// GET DIFFERENCE (HR.MINS)
						$av_dur= round($av_dur,2);										
						$total_min = $av_dur * 60;																		

						$hr = (int)($total_min / 60);
						$minutes = $total_min % 60;										

						//echo "<br>hr=".$hr." min=".$minutes;	
						
						$hrs_min[$i][$v-1] = $hr.".".$minutes;
					}				
				}	
				else if($status==1)		// IF VEHICLE IS INSIDE THE AREA
				{
					if($v_flag==1)	// - IF VEHICLE WAS INSIDE EARLIER v_flag = 0 for inside
					{	
						$v_flag = 0;
						$date_prev = $datetime[$i][$v-1];		// GET PREVIOUS DATE
						$date_next = $date[$j];			// GET CURRENT DATE
						//echo "<br>PREV DATE=".$date_prev." <BR>NEXT DATE=".$date_next."<br>";			
						
						$time1 = strtotime($date_prev);
						$time2 = strtotime($date_next);
						
						////GET DURATION OF AREA VIOLATION ////
						$av_dur =  ($time2 - $time1)/3600;	 	// GET DIFFERENCE (HR.MINS)
						$av_dur= round($av_dur,2);										
						$total_min = $av_dur * 60;																		

						$hr = (int)($total_min / 60);
						$minutes = $total_min % 60;										

						//echo "<br>hr=".$hr." min=".$minutes;	
						
						$hrs_min[$i][$v-1] = $hr.".".$minutes;
					}
				}
			}
		}
					
		$v_count[$i] = $v;			
	}
}	

//echo "<br>num_rows=".$num_rows."<br>.size=".$size_vid;
////////////////////////////////////////////////////

?>
<HTML>
<TITLE>Vehicle Tracking Pro-Innovative Embedded Systems Pvt. Ltd.</TITLE>
<head>
	<link rel="shortcut icon" href="./Images/iesicon.ico" >
	<LINK REL="StyleSheet" HREF="menu.css">
	<script type="text/javascript" src="menu.js"></script>
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
								<!--<td height=10 STYLE="background-color:#f0f7ff" class="text" align="center">Area Violation Report</td>-->
								<td height=10 STYLE="background-color:#f0f7ff" align="center"><font size="2"><strong>Area Violation Report</strong></font></td>
							</tr>
						</table>
					<?php
							if($access=="0")
								include("set_height.php");
							else if($access=="1" || $access=="-2")
								include("set_user_height.php");
						?>
						<br>
<?php					

						//echo "<br>num_rows=".$num_rows."<br>.size=".$size_vid;
						
					echo'
						<form  method="post" action="getpdf2.php?size='.$size.'" target="_blank">';

					
					if($vdata_found)
					{
											
						for($i=0;$i<$size_vid;$i++)
						{
							//$title1="Area Violation Report: From ".$startdate." To ".$enddate." [ ".$vehiclename[$i]." ]";
							echo "<br><center><font size='2'><strong>Vehicle : "." [ ".$vehiclename[$i]." ]</strong></font></center>";
							echo "<br><center><font size='1'>Time : (".$startdate.")to (".$enddate.")</font></center><br>";
								
							echo'
							<table border=1 width="90%" rules=rows bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>
							<tr>
								<td class="text" align="left"><b>SNo</b></td>
								<td class="text" align="left"><b>Date Time</b></td>								
								<td class="text" align="left"><b>Location</b></td>
								<td class="text" align="left"><b>Duration (hr.mins)</b></td>
								<!--<td class="text" align="left"><b>Latitude</b></td>
								<td class="text" align="left"><b>Longitude</b></td>-->
							</tr>
							';
							for($j=0;$j<$v_count[$i];$j++)
							{
								$s = $j+1;
								echo'						
								<tr>
								<td class="text" align="left">'.$s.'</td>
								<td class="text" align="left">'.$datetime[$i][$j].'</td>';
								
								if($placename[$i][$j]=="")
								{
									echo'<td class="text" align="left">&nbsp;-&nbsp;<a href="javascript:MapWindow(\''.$vehiclename[$j].'\',\''.$datetime[$i][$j].'\',\''.$lat[$i][$j].'\',\''.$long[$i][$j].'\');"><font color="green">&nbsp;(Show on map)</font></a></td>';
								}
								else
								{
									echo'<td class="text" align="left">'.$placename[$i][$j].'<a href="javascript:MapWindow(\''.$vehiclename[$j].'\',\''.$datetime[$i][$j].'\',\''.$lat[$i][$j].'\',\''.$long[$i][$j].'\');"><font color="green">&nbsp;(Show on map)</font></a></td>';
								}								
																							
								
								echo'<td class="text" align="left">'.$hrs_min[$i][$j].'</td>
								</tr>
								';
							}
							echo'</table><br>';
							//$title[$i]="Area Violation Report: From $startdate To $enddate [ ".$vehiclename[$i]." ]";
							$title="Area Violation Report: From ".$startdate." To ".$enddate." [ ".$vehiclename[$i]." ]";
							
							//$title1="Speed Violation Report: From $startdate To $enddate [ ".$vehiclename[$i]." ]";
							echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";
							

							for($j=0;$j<$v_count[$i];$j++)
							{
								$s = $j+1;
								$dt = $datetime[$i][$j];
								$pl = $placename[$i][$j];
								$dur = $hrs_min[$i][$j];
								//$lt = $lat[$i][$j];
								//$lng = $long[$i][$j];
								//echo "<br>lat=".$lat[$i][$j]." lng=".$long[$i][$j];
								echo"<input TYPE=\"hidden\" VALUE=\"$s\" NAME=\"temp[$i][$j][SNo]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$dt\" NAME=\"temp[$i][$j][Date]\">";							
								echo"<input TYPE=\"hidden\" VALUE=\"$pl\" NAME=\"temp[$i][$j][Location]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$dur\" NAME=\"temp[$i][$j][Duration (hr:mins)]\">";
								//echo"<input TYPE=\"hidden\" VALUE=\"$lt\" NAME=\"temp[$i][$j][Latitude]\">";
								//echo"<input TYPE=\"hidden\" VALUE=\"$lng\" NAME=\"temp[$i][$j][Longitude]\">";
								
								
								/*echo"<input TYPE=\"hidden\" VALUE=\"aaaa\" NAME=\"temp[$i][$j][SNo]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"bbbb\" NAME=\"temp[$i][$j][Date]\">";							
								echo"<input TYPE=\"hidden\" VALUE=\"ccc\" NAME=\"temp[$i][$j][Location]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"ddd\" NAME=\"temp[$i][$j][Latitude]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"eeee\" NAME=\"temp[$i][$j][Longitude]\">";*/
							}						
						}
							echo'												
							<table align="center">
								<tr><td align="center"><input type="submit" value="Get in PDF Form"></td></tr>
							</table>
							</form>
							';						
					}
					
					else if($geo_coord=="")
					{
						print"<center><FONT color=\"Red\"><strong>No GeoFence Area Defined for This Vehicle</strong></font></center>";
						//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=AreaReport.php\">";					
					}
					
					else
					{
						print"<center><FONT color=\"Red\"><strong>No Area Voilation in This Duration</strong></font></center>";
						//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=AreaReport.php\">";
					}
?>
						</div>
					 </TD>
				 </TR>
			</TABLE>
		</td>

	</tr>
</TABLE>
</BODY>
</HTML>