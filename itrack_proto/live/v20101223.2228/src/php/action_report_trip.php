<?php
//distance calculator :
//http://www.daftlogic.com/projects-google-maps-distance-calculator.htm

include_once("SessionVariable.php");
include_once("PhpMysqlConnectivity.php");

include_once("check_with_range.php");

//$vehicleid = $_POST['vehicleid'];
//$tripid = $_POST['tripid'];
//$startdate=$_POST['StartDate'];
//$enddate=$_POST['EndDate'];

$case = $_POST['case'];
//echo "<br>case=".$case;

//$V_NO = $_POST['vehiclestatus'];	// get serials
$V_NO_PREV = $_POST['vehiclestatus'];

$vserial_arr = $_POST['vserial_arr'];
$vehiclename_arr = $_POST['vehiclename_arr'];
$lat_arr = $_POST['lat_arr'];
$lng_arr = $_POST['lng_arr'];
$datetime_arr = $_POST['datetime_arr'];

$vserial_1 = explode(",",$vserial_arr);
$vname_1 = explode(",",$vehiclename_arr);
$lat_1 = explode(",",$lat_arr);
$lng_1 = explode(",",$lng_arr);
$datetime_1 = explode(",",$datetime_arr);

//$size = sizeof($vserial);
//echo "size=".$size;

$total_records = sizeof($vserial_1);
//$V_NO_SIZE = sizeof($V_NO);
//echo "<br>TOTAL REC=".$total_records."<br>";


$total_vehiclestatus = sizeof($V_NO_PREV);

$p = 0 ;
$flag = 0 ;

for($i=0;$i<$total_vehiclestatus;$i++)
{
	for($j=0;$j<$total_records;$j++)
	{
		if($V_NO_PREV[$i] == $vserial_1[$j])
		{
			$V_NO[$p] = $V_NO_PREV[$i];
			$p++;	
			break;			
		}
	}
}


$V_NO_SIZE = sizeof($V_NO);

//echo "<Br>V_NO_SIZE=".$V_NO_SIZE." total_rec=".$total_records;

/////GET RECORDS FOR INDIVIDUAL VEHICLE
/*
for($j=0;$j<$total_records;$j++)
{	
	echo "<br>vserial_1=".$vserial_1[$j].", vname=".$vname_1[$j];
} 

echo "<br><br>NEXT";

for($i=0; $i< $V_NO_SIZE; $i++)
{
	echo "<br>";
	for($j=0;$j<$total_records;$j++)
	{
		//echo "<br>V_NO=".$V_NO[$i]." vserial_1=".$vserial_1[$j]."<br><br>";
		
		if($V_NO[$i] == $vserial_1[$j])
			echo "<br>vname=".$vname_1[$j];
	}
}*/


for($i=0; $i<$V_NO_SIZE; $i++)
{
	$k=0;
	//echo "<Br><br>VNo=".$V_NO[$i]."<br>";
	
	for($j=0;$j<$total_records; $j++)
	{
		if($V_NO[$i] == $vserial_1[$j])
		{
			$vserial_2[$i][$k] = $vserial_1[$j];
			$vname_2[$i][$k] = $vname_1[$j];
			$lat_2[$i][$k] = $lat_1[$j];
			$lng_2[$i][$k] = $lng_1[$j];
			$datetime_2[$i][$k] = $datetime_1[$j];			
			
			/*echo "<br>vserial=".$vserial_2[$i][$k];
			echo "<br>vname=".$vname_2[$i][$k];
			echo "<br>lat=".$lat_2[$i][$k];
			echo "<br>lng=".$lng_2[$i][$k];
			echo "<br>datetime=".$datetime_2[$i][$k];*/
		
			$k++;
		}		
	}
	//echo "<br>k=".$k;
}

//echo "<br><br>";

for($y=0; $y<$V_NO_SIZE; $y++)
{	
	//echo "<br><br>";
	/// INDIVIDUAL VEHICLE ARRAY SIZE
	$array_size1 = sizeof($vserial_2[$y]);		//get 2d array size
	//echo "<br>arr1_size=".$array_size1."</br>";
	
	////// STORE RECORDS TEMPORARILY IN 1D ARRAY
	for($k=0;$k<$array_size1;$k++)
	{
		$vserial_3[$k] = $vserial_2[$y][$k];
		$vname_3[$k] = $vname_2[$y][$k];
		$lat_3[$k] = $lat_2[$y][$k];
		$lng_3[$k] = $lng_2[$y][$k];
		$datetime_3[$k] = $datetime_2[$y][$k];
	}
	
	//$array_size2 = sizeof($vserial_3);	// get 1d array size
	
	//echo "<br><br>array_size2=".$array_size2." K=".$k."<br>";			/// XXXXXX
	///////////////// SORT IN ASCENDING ORDER ////////////////////
	
	for($i = 0; $i < $k; $i++) {
	
	  for($j = 0; $j < $k; $j++) {
	  
		if($datetime_3[$i] < $datetime_3[$j]) 
		{
		  $temp_dt = $datetime_3[$i];
		  $datetime_3[$i] = $datetime_3[$j];
		  $datetime_3[$j] = $temp_dt;

		  $temp_vserial = $vserial_3[$i];
		  $vserial_3[$i] = $vserial_3[$j];
		  $vserial_3[$j] = $temp_vserial;

		  $temp_vname = $vname_3[$i];
		  $vname_3[$i] = $vname_3[$j];
		  $vname_3[$j] = $temp_vname;

		  $temp_lat = $lat_3[$i];
		  $lat_3[$i] = $lat_3[$j];
		  $lat_3[$j] = $temp_lat;

		  $temp_lng = $lng_3[$i];
		  $lng_3[$i] = $lng_3[$j];
		  $lng_3[$j] = $temp_lng;		  		  
		}
	  } // for 1 closed
	}  // for 2 closed*/
	
	////////////////////////////////////////////////////////////////

	/// STORE RECORDS AGAIN IN 2D ARRAY 
	//echo "<br<br>Vehicle=".$V_NO[$y]."<br>";
	for($m = 0; $m < $k; $m++)
	{
		$final_vserial[$y][$m] = $vserial_3[$m];
		$final_vname[$y][$m] = $vname_3[$m];
		$final_lat[$y][$m] = $lat_3[$m];
		$final_lng[$y][$m] = $lng_3[$m];
		$final_datetime[$y][$m] = $datetime_3[$m];	

		/*$sno = $m+1;
		echo "<br><strong>SNo: ".$sno."</strong>";
		echo "-vserial=".$final_vserial[$y][$m];
		echo " vname=".$final_vname[$y][$m];
		echo " lat=".$final_lat[$y][$m];
		echo " lng=".$final_lng[$y][$m];
		echo " datetime=".$final_datetime[$y][$m]."<br><br>";*/
	}
	//echo "<br><br>";
	
}	// vehicle size closed
		

include("TripInfo/pointLocation.php");

	
/*** Example ***
$pointLocation = new pointLocation();

$cc1 = "51.665287 5.229492,52.475643 9.975586,52.207158 14.501953,49.802069 16.743164";
$cc2 = explode(",",$cc1);

echo "<br>coord=".$cc2[0].",".$cc2[1].",".$cc2[2].",".$cc2[3]."<br>";

//$polygon = array("51.665287 5.229492","52.475643 9.975586","52.207158 14.501953","49.802069 16.743164");
//$points = array("48.9725N 8.3496S");

$clat ="48.9725";
$clong = "8.3496";
$pt = $clat." ".$clong;

$polygon = array($cc2[0],$cc2[1],$cc2[2],$cc2[3]);
$points = array($pt);

foreach($points as $key => $point) {
    echo "$key ($point) is " . $pointLocation->pointInPolygon($point, $polygon) . "<br>";
	
	$found = $pointLocation->pointInPolygon($point, $polygon);
	 if($found=="inside")
		$valid = true;
	 else
		$valid = false;
		
	if($valid == false)
		echo "false";
	if($valid == true)
		echo "true";
		
}
**** example closed **/

?>


<HTML>
<title>ItrackSolution TripInfo</title></
<head>

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
		if($access =="Zone")
			include('usermenu.php');
	?>
		<td valign="top">
			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
				<TR>
					<TD>
						<br>
						<table border=0 width = 100% cellspacing=2 cellpadding=0>
							<tr>
								<?php								
									if($case=="trip")
										echo'<td height=10 class="header" align="center"><strong>Trip Details</strong></td>';
									if($case=="movement")
										echo'<td height=10 class="header" align="center"><strong>Vehicle Movement</strong></td>';								
								?>
							</tr>
						</table>
						<div style="height:5px"></div>
						<?php
														
							if($access=="Zone")
								include("set_user_height.php");
								
/////////////////////// LOOP FOR NO_OF_DATA //////////////////////////////////////////////////////

if($case=="trip")
{
	echo'<table border=0 width="100%"cellspacing=0 cellpadding=0><tr><td align="center"><div style="width:800px;overflow:auto" align="center"><table border=1 width="80%" rules=all bordercolor="#689FFF" align="center" cellspacing=0 cellpadding=3>';
	echo '<tr bgcolor="#C4E3FF" style="height:22px;color:#0000FF;">';
	echo '<td class="text"><strong>SL NO</strong></td>';
	echo '<td class="text"><strong>Vehicle No.</strong></td>';
	
	$Query = "Select * from milestone where ZoneID='$zoneid' and MileStoneType='BS' and Status!='REMOVE' ORDER BY MileStoneSerial";
	//echo $Query;
	$Result1 = mysql_query($Query, $DbConnection);

	//echo $Query;
	$num_results1 = mysql_num_rows($Result1);
	
	//echo "<br>num_res=".$num_results;	
	if($num_results1)
	{
		$i=0;
		while($row1 = mysql_fetch_object($Result1))
		{	
			// GET ALL OS
			$Query2 = "Select * from milestone where ZoneID='$zoneid' and MileStoneType='OS' and Status!='REMOVE' ORDER BY MileStoneSerial";
			//echo $Query;
			$Result2 = mysql_query($Query2, $DbConnection);
			$num_results2 = mysql_num_rows($Result2);
			
			while($row2 = mysql_fetch_object($Result2))
			{
				$BS = $row1->MileStoneName;
				$OS = $row2->MileStoneName;
				
				echo '<td class="text"><strong>'.$BS.' -'.$OS.'</strong></td>';	
				$total_ms_col_.$i = 0;
				$i++;
			}
		}
		
		$overall_trip_cnt = 0;
		$m_total = "";
		$columns = 0;
	}		
	echo '<td class="text"><strong>TOTAL TRIPS</strong></td>';	
	echo'</tr>'; 	
}

$total_trip_cnt = 0;

for($v=0; $v<$V_NO_SIZE; $v++)
{
	$x = 0;
	$y = 0;
	$t = 0;
	
	$i=0;
	$j=0;
	
	$size1 = sizeof($vserial);
	$size2 = sizeof($total_milestone_vserial);
	
	for($i=0;$i<$size1;$i++)
	{
		$vserial[$i] ="";
		$lat[$i] = "";
		$lng[$i] = "";
		$datetime[$i] = "";	
	}
		
	for($i=0;$i<$size2;$i++)
	{
		$total_milestone_vserial[$i] = "";
		$total_milestone_vname[$i] = "";
		$total_milestone_lat[$i] = "";
		$total_milestone_lng[$i] = "";
		$total_milestone_serial[$i] = "";
		$total_milestone_name[$i] = "";
		$total_milestone_type[$i] = "";
		$total_milestone_datetime[$i] = "";
		$total_milestone_datetype[$i] = "";
		$total_milestone_mark[$i] = "";
	}
				

	/// INDIVIDUAL VEHICLE ARRAY SIZE
	$no_of_data = sizeof($final_vserial[$v]);		//get 2d array size
	//echo "<BR><br>NOOF_DATA=".$no_of_data;

	////// STORE RECORDS TEMPORARILY IN 1D ARRAY
	
	for($i=0;$i<$no_of_data;$i++)
	{		
		///STORE RECORDS IN 1D ARRAYS
		$vserial[$i] = $final_vserial[$v][$i];
		$vname[$i] = $final_vname[$v][$i];
		$lat[$i] = $final_lat[$v][$i];
		$lng[$i] = $final_lng[$v][$i];
		$datetime[$i] = $final_datetime[$v][$i];	
			
		if( ($lat[$i]!="-" || $lng[$i]!="-") || ($lat[$i]!="" || $lng[$i]!="") )
		{
			// CALL VALID_MILESTONE FUNCTION
			check_valid_milestone($vserial[$i], $lat[$i], $lng[$i], &$milestone_serial, &$milestone_name, &$milestone_type, &$valid, $zoneid, $DbConnection);
			
			//echo "<br>valid=".$valid;
			
			if($valid == true)
			{
				//echo "<br>IN VALID";
				/*$entered_vserial[$x] = $vserial[$i];
				$entered_vname[$x] = $vname[$i];
				$entered_lat[$x] = $lat[$i];
				$entered_lng[$x] = $lng[$i];
				$entered_milestone_serial[$x] = $milestone_serial;
				$entered_milestone_name[$x] = $milestone_name;
				$entered_milestone_type[$x] = $milestone_type;
				$entered_datetime[$x] = $datetime[$i];
				$x++;	*/
				
				$total_milestone_vserial[$t] = $vserial[$i];
				$total_milestone_vname[$t] = $vname[$i];
				$total_milestone_lat[$t] = $lat[$i];
				$total_milestone_lng[$t] = $lng[$i];
				$total_milestone_serial[$t] = $milestone_serial;
				$total_milestone_name[$t] = $milestone_name;
				$total_milestone_type[$t] = $milestone_type;
				$total_milestone_datetime[$t] = $datetime[$i];
				$total_milestone_datetype[$t] = "ENTER";
				$total_milestone_mark[$t] = "FALSE";
				
				//echo "<br>Im Valid milestone- total_milestone_vname[".$t."]=".$total_milestone_vname[$t];
				$t++;
							
				for($j=$i+1;$j<$no_of_data;$j++)
				{
					$vserial[$j] = $final_vserial[$v][$j];
					$vname[$j] = $final_vname[$v][$j];
					$lat[$j] = $final_lat[$v][$j];
					$lng[$j] = $final_lng[$v][$j];
					$datetime[$j] = $final_datetime[$v][$j];
					
					check_leave_milstone($vserial[$j], $lat[$j], $lng[$j], $milestone_serial, $milestone_name, $milestone_type, &$valid, $zoneid, $DbConnection);
					
					//echo "<br>valid=".$valid;
					
					if($valid == true)
					{
						//echo "<br>IN LEAVE";
						/*$leave_vserial[$y] = $vserial[$i];
						$leave_vname[$y] = $vname[$i];
						$leave_lat[$y] = $lat[$i];
						$leave_lng[$y] = $lng[$i];
						$leave_milestone_serial[$y] = $milestone_serial;
						$leave_milestone_name[$y] = $milestone_name;
						$leave_milestone_type[$y] = $milestone_type;
						$leave_datetime[$y] = $datetime[$i];				
						$y++;*/			
						break;
					}
				}
								
				$total_milestone_vserial[$t] = $vserial[$j];
				$total_milestone_vname[$t] = $vname[$j];
				$total_milestone_lat[$t] = $lat[$j];
				$total_milestone_lng[$t] = $lng[$j];
				$total_milestone_serial[$t] = $milestone_serial;
				$total_milestone_name[$t] = $milestone_name;
				$total_milestone_type[$t] = $milestone_type;
				$total_milestone_datetime[$t] = $datetime[$j];
				$total_milestone_datetype[$t] = "LEAVE";
				$total_milestone_mark[$t] = "FALSE";
				
				//echo "<br>Im Leave milestone- total_milestone_vname[".$t."]=".$total_milestone_vname[$t];
				$t++;	

				$i=$j;		
			}  // if valid closed
		} //if lat lng closed
	
	}  ////////////////////// MAIN no_of_data LOOP CLOSED  //////////////////////////////////////////////////////

	$Query = "Select VehicleName from vehicle where VehicleSerial='$V_NO[$v]'";
	//echo "<br>".$Query;
	$Result = mysql_query($Query, $DbConnection);
	
	if($row = mysql_fetch_object($Result))
	{
		$V_NAME = $row->VehicleName;
	}
		
	/*for($i=0;$i<sizeof($total_milestone_vserial);$i++)
	{
		echo "<br>TTTTTTtotal_milestone_type=".$total_milestone_type[$i];
	}	*/
	
	//echo "<br>vsize=".$v.size." totalMS=".sizeof($total_milestone_vserial);
	///////// CHECK TRIP COUNT //////////
	if($case=="trip")
	{
		//echo "<br>Total milestone=".sizeof($total_milestone_vserial);
		check_trip_count($v,$V_NAME, $total_milestone_vserial, $total_milestone_vname, $total_milestone_lat, $total_milestone_lng, $total_milestone_serial, $total_milestone_name, $total_milestone_type, $total_milestone_datetime, &$overall_trip_cnt, $zoneid, $DbConnection ,$total_milestone_mark,$total_milestone_datetype);
	}
	
	///////// OVERALL MOVEMENT OF VEHICLE //////
	if($case=="movement")
	{
		overall_movement($V_NAME, $total_milestone_vserial, $total_milestone_vname, $total_milestone_lat, $total_milestone_lng, $total_milestone_serial, $total_milestone_name, $total_milestone_type, $total_milestone_datetime, $total_milestone_datetype);
	}
} // V_NO_SIZE CLOSED


/// FINAL TRIP SUM AT END

if($case=="trip")
{
	echo "<tr style='height:20px;' bgcolor='#E2BAF5'>";
	echo '<td class="text"><font color="red"><strong>TOTAL</strong></font></td>';	
	echo '<td class="text"><strong>'.$V_NO_SIZE.'</strong></td>';		

	// FINAL COLUMN SUM
	
	for($i = 0; $i< $columns; $i++)
	{									
		if($i == 0)
		{														
			//echo '<td class="text"><strong>A0='.$total_ms_col_0.'</strong></td>';	
			echo '<td class="text"><strong>'.$total_ms_col_0.'</strong></td>';	
		}
		else if($i == 1)
		{												
			//echo '<td class="text"><strong>A1='.$total_ms_col_1.'</strong></td>';	
			echo '<td class="text"><strong>'.$total_ms_col_1.'</strong></td>';	
		}
		else if($i == 2)
		{						
			//echo '<td class="text"><strong>A2='.$total_ms_col_2.'</strong></td>';	
			echo '<td class="text"><strong>'.$total_ms_col_2.'</strong></td>';	
		}
		else if($i == 3)
		{						
			//echo '<td class="text"><strong>A3='.$total_ms_col_3.'</strong></td>';	
			echo '<td class="text"><strong>'.$total_ms_col_3.'</strong></td>';	
		}
		else if($i == 4)
		{
			echo '<td class="text"><strong>'.$total_ms_col_4.'</strong></td>';	
		}
		else if($i == 5)
		{
			echo '<td class="text"><strong>'.$total_ms_col_5.'</strong></td>';	
		}
		else if($i == 6)
		{
			echo '<td class="text"><strong>'.$total_ms_col_6.'</strong></td>';	
		}
		else if($i == 7)
		{
			echo '<td class="text"><strong>'.$total_ms_col_7.'</strong></td>';	
		}
		else if($i == 8)
		{
			echo '<td class="text"><strong>'.$total_ms_col_8.'</strong></td>';	
		}
		else if($i == 9)
		{
			echo '<td class="text"><strong>'.$total_ms_col_9.'</strong></td>';	
		}
		else if($i == 10)
		{
			echo '<td class="text"><strong>'.$total_ms_col_10.'</strong></td>';	
		}
		else if($i == 11)
		{
			echo '<td class="text"><strong>'.$total_ms_col_11.'</strong></td>';	
		}
		else if($i == 12)
		{
			echo '<td class="text"><strong>'.$total_ms_col_12.'</strong></td>';	
		}
		else if($i == 13)
		{
			echo '<td class="text"><strong>'.$total_ms_col_13.'</strong></td>';	
		}
		else if($i == 14)
		{
			echo '<td class="text"><strong>'.$total_ms_col_14.'</strong></td>';	
		}
		else if($i == 15)
		{
			echo '<td class="text"><strong>'.$total_ms_col_15.'</strong></td>';	
		}
		//break;	
	}
		
	// OVERALL TRIP COUNT
	
	echo'<td class="text"><strong>'.$overall_trip_cnt.'</strong></td>';
	echo '</tr>';
	
	echo '</table></div></td></tr></table>';
	
	$columns = 0;
}

///////// CHECK_VALID_MILESTONE FUNCTION - ENTRY POINT ////////////////

function check_valid_milestone($vserial, $lat, $lng, &$milestone_serial, &$milestone_name, &$milestone_type, &$valid, $zoneid, $DbConnection)
{	
	$Query = "Select * from milestone where ZoneID='$zoneid' and Status!='REMOVE' ORDER BY MileStoneSerial";
	//echo "<br>valid=".$Query;
	$Result = mysql_query($Query, $DbConnection);

	//echo $Query;
	$num_results = mysql_num_rows($Result);
	
	//echo "<br>num_res-VALID=".$num_results;
	
	if($num_results)
	{
		$m=0;
		while($row = mysql_fetch_object($Result))
		{
			$m_serial[$m] = $row->MileStoneSerial;
			$m_name[$m] = $row->MileStoneName;
			$m_type[$m] = $row->MileStoneType;
			$coordinates[$m] = $row->Coordinates;		
			$m++;
		}
		
		for($n=0; $n<$m; $n++)
		{						
			//echo "<br>lat===".$lat." lng=".$lng." ms=".$m_serial[$n]." coord=".$coordinates[$n];			
			//echo "<br>Valid MSerial=".$m_serial[$n]."New Coord=".$coordinates[$n];
			check_with_range($lat, $lng, $m_serial[$n], $coordinates[$n], &$status);
			
			//echo "<br>status===".$status;
			
			if($status == true)
			{
				$valid = true;
				$milestone_serial = $m_serial[$n];
				$milestone_name = $m_name[$n];
				$milestone_type = $m_type[$n];
				break;
			}
			else
				$valid = false;
		}
		
		//echo "<br>M=".$m;
	}  // if $num_results closed
	
} //funciton closed


///////// CHECK_LEAVE_MILESTONE FUNCTION -EXIT POINT ////////////////

function check_leave_milstone($vserial, $lat, $lng, $milestone_serial, $milestone_name, $milestone_type, &$valid, $zoneid, $DbConnection)
{
	$Query = "Select * from milestone where MileStoneSerial='$milestone_serial' and Status!='REMOVE'";
	//echo "<br>leave=".$Query."<br><br>";
	$Result = mysql_query($Query, $DbConnection);
	//echo $Query;
	$num_results = mysql_num_rows($Result);	
	//echo "<br>LEAVE-num_res=".$num_results;
	
	if($num_results)
	{
		if($row = mysql_fetch_object($Result))
		{
			$coordinates1 = $row->Coordinates;
		}
				
		//echo "<br>Leave MSerial=".$milestone_serial."New Coord=".$coordinates1;
		check_with_range($lat, $lng, $milestone_serial, $coordinates1, &$status);	
		
		if($status == false)
		{
			//echo "<br>in status=".$status;
			$valid = true;
			//break;
		}
		else
		{
			$valid = false;
			//break;
		}
	}  // if $num_results closed
	//echo "<br>valid in leave=".$valid;
	
} // function closed


///////// CHECK_WITH_RANGE FUNCTION  ////////////////

///////// CHECK_WITH_RANGE FUNCTION  ////////////////
// CALL CHECK WITH RANGE// 	check_with_range() FUNCTION


///////////////////////////////////////////////////////
							
///////// CHECK_TRIP_COUNT FUNCTION  ////////////////

function check_trip_count($v, $V_NAME, $total_milestone_vserial, $total_milestone_vname, $total_milestone_lat, $total_milestone_lng, $total_milestone_serial, $total_milestone_name, $total_milestone_type, $total_milestone_datetime, &$overall_trip_cnt, $zoneid, $DbConnection , $total_milestone_mark, $total_milestone_datetype)
{					
	$m=0;
	$trip_count = 0;
	$m_size = sizeof($total_milestone_vserial);
	//echo "<br>Total Msize=".$m_size;
	$mid_trip = false;
	
	////////// get number of Operating Stations  /////////

	$os_limit =0;
	$bs_limit = 0;
	
	$Query = "Select * from milestone where ZoneID='$zoneid' and Status!='REMOVE' ORDER BY MileStoneSerial";
	//echo "<br>".$Query." Dbcon=".$DbConnection;
	$Result1 = mysql_query($Query, $DbConnection);
	//echo $Query;
	$num_results1 = mysql_num_rows($Result1);
	
	while($row = mysql_fetch_object($Result1))
	{			
		if($row->MileStoneType=='OS')
		{
			$OS_1[$os_limit] = $row->MileStoneName;	
			$os_limit++;
		}
		else if($row->MileStoneType=='BS')
		{
			$BS_1[$bs_limit] = $row->MileStoneName;	
			$bs_limit++;
		}
	}

	for($i=0;$i<$bs_limit;$i++)
	{
		for($j=0;$j<$os_limit;$j++)
		{
			$BS_Trip[$i][$j]=0;
		}
		$BS_TotalTrip[$i]=0;
	}
	
	
	////*COMMENT/////////// Check Trip for each OS 
	//echo "<br>OS LIMIT=".$os_limit." msize=".$m_size."<BR>";
	
	/*for($k=0; $k<$m_size; $k++)
	{
		echo "<br>k=".$k.", totalmilestones=" .$total_milestone_name[$k]. ",  mname=".$total_milestone_vname[$k].",  mtype=".$total_milestone_type[$k].",   status=".$total_milestone_datetype[$k].",   mark=".$total_milestone_mark[$k]." Datetime=".$total_milestone_datetime[$k]."<BR><BR>";		
	}*/
	////////////////////////////////COMMENT*////////	
	
		
	for($i=0; $i<$os_limit; $i++)
	{
		$trip_count = 0;
		// Calculate trip from Base Station
		
		$start_trip = false;
		$end_trip = false;
		$mid_trip = false;
		
		//echo "<br>";
		for($k=0; $k<$m_size; $k++)
		{				
			
				/*echo "<br>k=".$k.", totalmilestones=" .$total_milestone_name[$k]. ",  mname=".$total_milestone_vname[$k].",  mtype=".$total_milestone_type[$k].",   status=".$total_milestone_datetype[$k].",   mark=".$total_milestone_mark[$k]." Datetime=".$total_milestone_datetime[$k]."<BR><BR>";*/	
			

			if( ($total_milestone_type[$k] == "BS") && ($mid_trip == false)  && ($total_milestone_datetype[$k] == "LEAVE"))
			{
				//echo "<br>in Start trip"," NAME=".$total_milestone_name[$k];				
				//echo "<br>OS_1=".$OS_1[$i];			
				
				$start_trip = true;
				$end_trip = false;
				
				$start_vehicle_serial = $total_milestone_vserial[$k];
				$start_milestone_serial = $total_milestone_serial[$k];
				$start_milestone_name = $total_milestone_name[$k];
				$start_milestone_type = $total_milestone_type[$k];
				$start_milestone_datetime =  $total_milestone_datetime[$k];
				//$total_milestone_mark[$k] == "TRUE";
			}
						
			if( ($start_trip == true) && ($total_milestone_type[$k] == "OS") && ($total_milestone_name[$k] == $OS_1[$i])  && ($total_milestone_datetype[$k] == "ENTER"))
			{
				$mid_trip = true;
				$start_trip = false;
				$total_milestone_mark[$k] == "TRUE";
				
				//echo "<br>in Mid trip";
			}
			
			//echo "<br>tot-bs=".$total_milestone_type[$k];
			
			if( ($mid_trip == true) && ($total_milestone_type[$k] == "BS") && ($total_milestone_datetype[$k] == "ENTER") )
			{		
				//echo "<BR>In end trip"." NAME=".$start_milestone_name."<BR><BR>";

				$end_trip = true;
				$mid_trip = false;
				$trip_count ++;	

				for($j=0;$j<$bs_limit;$j++)
				{
					if($BS_1[$j]==$total_milestone_name[$k])
					{
						$BS_Trip[$j][$i]++;
						$BS_TotalTrip[$j]++;
					}
				}
				//echo "<br><br>k=".$k;
				//echo "<br>in End trip=".$trip_count;
			}
		} // Inner loop closed for OS
		
		/// STORE TRIP DETAIL FOR EACH OS TO BS
		
		$BS[$m] = $start_milestone_name;	// GET THE ACTIVE BASE STATION
		$OS[$m] = $OS_1[$i];					// GET THE INDIVIDUAL OPERATING STATION

		$TYPE[$m] = $start_milestone_type;
		$TRIP_CNT[$m] = $trip_count;

		//echo "<br>TRIP end M =".$MS[$m];
		//echo "<br>Main TRIP_CNT =".$TRIP_CNT[$m]. " BS=".$BS[$m]." OS=".$OS[$m]. " Type=".$TYPE[$m]."<BR><BR>";
		$m++;
		
	} // Outer loop closed for OS

	//echo 'BS='.$bs_limit.' OS'.$os_limit.'<BR>';

	$m=0;

	for($i=0;$i<$bs_limit;$i++)
	{
		//echo 'BS='.$BS_1[$i].' '.$BS_TotalTrip[$i].'<BR>';
		for($j=0;$j<$os_limit;$j++)
		{
			//echo 'OS='.$OS_1[$j].' '.$BS_Trip[$i][$j].'<BR>';

		$BS[$m] = $BS_1[$i];	// GET THE ACTIVE BASE STATION
		$OS[$m] = $OS_1[$j];					// GET THE INDIVIDUAL OPERATING STATION

		//$TYPE[$m] = $start_milestone_type;
		$TRIP_CNT[$m] = $BS_Trip[$i][$j];
		$m++;

		}
	}
	

	//echo "<br>m=".$m;
	///////////// PRINT TRIP DATA ///////////
	
	$total_vehicle_trip = 0;
	
	echo '<tr>';
	echo '<td class="text">'.++$v.'</td>';
	echo '<td class="text">'.$V_NAME.'</td>';
	
	//echo "<br>M=".$m;	
	
	//for($i=0;$i<$m;$i++)
		//echo "<br><br><br>TOTAL MS in TRIP for one V=". $MS[$i];
			
	global $m_total;
	global $columns;
	$columns = $m;
	
	//$j=0;
	//$flag = 0;			
	//$BS2 = $row->MileStoneName;
	//$flag = 0;
	//echo "<br>MS2 original=".$MS2;		
	
	//echo "<br>m=".$m;
	
	for($i=0; $i<$m; $i++)
	{		
		$flag = 1;
		//echo "Before In condition MSNAME=".$MS[$j]." trpcnt:".$TRIP_CNT[$j];																	
		//echo "After In condition MSNAME=".$MS[$j]." trpcnt:".$TRIP_CNT[$j];
		
		echo '<td class="text">'.$TRIP_CNT[$i].'</td>';		// TRIP COUNT FOR INDIVIDUAL MILESTONE ( SINGLE COLUMN )
						
		if($i == 0)  
		{
			global $total_ms_col_0;
			$total_ms_col_0 = $total_ms_col_0 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";	// collect name information
			//echo "<br>total mscol_0 = ". $total_ms_col_0." m_total=".$m_total;
		}
		else if ($i == 1)
		{
			global $total_ms_col_1;
			$total_ms_col_1 = $total_ms_col_1 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_1 = ". $total_ms_col_1;
		}  
		else if ($i == 2)
		{
			global $total_ms_col_2;
			$total_ms_col_2 = $total_ms_col_2 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_2 = ". $total_ms_col_2;
		}
		else if ($i == 3)
		{
			global $total_ms_col_3;
			$total_ms_col_3 = $total_ms_col_3 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_3 = ". $total_ms_col_3;
		}
		else if ($i == 4)
		{
			global $total_ms_col_4;
			$total_ms_col_4 = $total_ms_col_4 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_4 = ". $total_ms_col_4;
		}
		else if ($i == 5)
		{
			global $total_ms_col_5;
			$total_ms_col_5 = $total_ms_col_5 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_5 = ". $total_ms_col_5;
		}
		else if ($i == 6)
		{
			global $total_ms_col_6;
			$total_ms_col_6 = $total_ms_col_6 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_6 = ". $total_ms_col_6;
		}
		else if ($i == 7)
		{
			global $total_ms_col_7;
			$total_ms_col_7 = $total_ms_col_7 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_7 = ". $total_ms_col_7;
		}
		else if ($i == 8)
		{
			global $total_ms_col_8;
			$total_ms_col_8 = $total_ms_col_8 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_8 = ". $total_ms_col_8;
		}
		else if ($i == 9)
		{
			global $total_ms_col_9;
			$total_ms_col_9 = $total_ms_col_9 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_9 = ". $total_ms_col_9;
		}
		else if ($i == 10)
		{
			global $total_ms_col_10;
			$total_ms_col_10 = $total_ms_col_10 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_10 = ". $total_ms_col_10;
		}
		else if ($i == 11)
		{
			global $total_ms_col_11;
			$total_ms_col_11 = $total_ms_col_11 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_11 = ". $total_ms_col_11;
		}
		else if ($i == 12)
		{
			global $total_ms_col_12;
			$total_ms_col_12 = $total_ms_col_12 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_12 = ". $total_ms_col_12;
		}
		else if ($i == 13)
		{
			global $total_ms_col_13;
			$total_ms_col_13 = $total_ms_col_13 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_13 = ". $total_ms_col_13;
		}
		else if ($i == 14)
		{
			global $total_ms_col_14;
			$total_ms_col_14 = $total_ms_col_14 + $TRIP_CNT[$i];
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_14 = ". $total_ms_col_14;
		}
		else if ($i == 15)
		{
			global $total_ms_col_15;	
			$total_ms_col_15 = $total_ms_col_15 + $TRIP_CNT[$i];	
			$m_total = $m_total . $BS[$i].",";
			//echo "<br>total mscol_15 = ". $total_ms_col_15;						
		}
		
		//$total_ms_col_.$j = $total_ms_col_.$j + $TRIP_CNT[$j];		// COLUMN WISE- TRIP SUM
		
		$total_vehicle_trip = $total_vehicle_trip + $TRIP_CNT[$i];	// ROW WISE- TRIP SUM	
		
		//echo "<br>TRIP_CNT= ". $TRIP_CNT[$j];
		//echo "<br>total mscol = ". $total_ms_col_15;
		//echo "<br>total_vehicle_trip = ". $total_vehicle_trip."<br><br>";	
	}
		
	echo '<td class="text">'.$total_vehicle_trip.'</td>';  // TOTAL TRIP COUNT FOR INDIVIDUAL VEHICLE	( SUM AT ROW END)
	echo '</tr>';
	
	global $overall_trip_cnt;								
	$overall_trip_cnt = $overall_trip_cnt + $total_vehicle_trip;	// OVERALL TRIP COUNT FOR ALL VEHICLES (TOTAL SUM)
	
}

///////// CHECK_OVERALL_MOVEMENT FUNCTION  ////////////////

function overall_movement($V_NAME, $total_milestone_vserial, $total_milestone_vname, $total_milestone_lat, $total_milestone_lng, $total_milestone_serial, $total_milestone_name, $total_milestone_type, $total_milestone_datetime, $total_milestone_datetype)
{	
	echo'<table border=1 width="80%" rules=all bordercolor="#689FFF" align="center" cellspacing=0 cellpadding=3>
	
		<tr>
			<td height=10 class="text" align="center" colspan="5" colspan="3"> <br><br>Vehicle:'.$V_NAME.'<br></td>
		</tr>
		
		<tr bgcolor="#C4E3FF">										
			<td class="text" align="left"><b>Milestone Name</b></td>			
			<td class="text" align="left"><b>Date time</b></td>	
			<td class="text" align="left"><b>Type</b></td>				
		</tr>
		';	
		
	$m_size = sizeof($total_milestone_vserial);
	//echo "<br>m_size=".$m_size;
	
	for($k=0; $k<$m_size; $k++)
	{
		$current_milestone_name = $total_milestone_name[$k];
		$next_milestone_name = $total_milestone_name[$k+1];
	
		if( !($current_milestone_name == $next_milestone_name) && ($total_milestone_datetime[$k]!="") )
		{
			echo '<tr>';
			echo '<td class="text">'.$total_milestone_name[$k].'</td>';
			echo '<td class="text">'.$total_milestone_datetime[$k].'</td>';	
			echo '<td class="text">'.$total_milestone_type[$k].'</td>';
			echo '</tr>';		
		}
	}
	
	echo '</table>';
}								
						?>

						<br>

				
					 </TD>
				 </TR>
			</TABLE>
		</td>

	</tr>
</TABLE>
</BODY>
</HTML>