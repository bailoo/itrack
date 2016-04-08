<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('common_xml_element.php');
	include_once("tripinfo/check_with_range.php");    
	include_once("read_filtered_xml.php");
	include_once("util.hr_min_sec.php");
	include_once("get_io.php");
	$DEBUG =0;
	$t=time();
	$xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$t.".xml";
	include_once("tripinfo/get_filtered_xml_milestone_new.php");      // WRITE SORTED XML , FINAL XML NAME STORED IN 'xmltowirte' VARIABLE
	//include_once("get_filtered_xml_milestone.php");
	//$vehicleserial= $_POST['vserial'];
	$case = $_POST['case'];
	echo'<input type="hidden" id="case" value="'.$case.'">';	
	$group_id_mining = $_POST['group_id_local'];
	echo'<input type="hidden" id="group_id_local" value="'.$group_id_mining.'">';
	//$startdate = str_replace('/','-',$startdate);
	//$enddate = str_replace('/','-',$enddate);
	//echo "<br>v:".$v.",m:".$m.",g:".$group_id_mining.",st=".$startdate.",ed=".$enddate;
	//read_track_xml($xmltowrite, &$datetime, &$vehicleserial, &$vehiclename, &$lat, &$lng, &$alt, &$vehicletype, &$speed, &$fuel, &$cumdist);
	read_tripdata_xml($xmltowrite, &$datetime, &$vehicleserial, &$vehiclename, &$lat, &$lng, &$speed);
	//print_r($vehicleserial);
	$size = sizeof($vehicleserial);
	if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.160") $DEBUG =1; 

	//if($DEBUG) echo "<br>size".$size;
	//unlink($xmltowrite);

	$j=-1;
	$k=0;

	/*for($i=0;$i<$size;$i++)
	{
		if($DEBUG) echo "<br>xml_lng=".$lat[$i]." ,xml_lng=".$lng[$i]." ,xml_datetime=".$datetime[$i];
	}*/

	for($i=0;$i<$size;$i++)
	{								              
		//echo "<br>in loop";
		if(($i===0) || (($i>0) && ($vehicleserial[$i-1] != $vehicleserial[$i])) )
		{
			$k=0;                                              
			$j++;   
			$V_NO[$j] = $vehiclename[$i]; 
		}
		$final_vserial[$j][$k] = $vehicleserial[$i];
		//echo "vserial=".$final_vserial[$j][$k]."<br>";
		$final_vname[$j][$k] = $vehiclename[$i];
		$final_lat[$j][$k] = $lat[$i];		
		$final_lng[$j][$k] = $lng[$i];	
		$final_datetime[$j][$k] = $datetime[$i];  
		//if($DEBUG) echo "<br>final_lng=".$final_lat[$j][$k]." ,final_lng=".$final_lng[$j][$k]." ,final_datetime=".$final_datetime[$j][$k];	
		//echo "<br>".$final_vname[$j][$k];	  
		$k++; 
		//echo "<br>v1".$vehicleserial[$i+1]." ,v=".$vehicleserial[$i];
		if( (($i>0) && ($vehicleserial[$i+1] != $vehicleserial[$i])) )
		{
			$data_size[$j] = $k;
			//echo "<br>data_size=".$data_size[$j];
		}  
	} 
	$V_NO_SIZE = $j+1;
	//if($DEBUG) echo "<br>datasize=".$data_size[$j]."<br>vnosize=".$V_NO_SIZE."<br>";									
	include("tripinfo/pointLocation.php");	
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
	<body bgcolor="white">
		<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
			<TR>
				<TD>
					<br>
						<table border=0 width = 100% cellspacing=2 cellpadding=0>
							<tr>
								<?php								
									if($case=="trip")
										echo'<td height=10 class="header" align="center"><strong>Trip Details - ('.$startdate.' - '.$enddate.')</strong></td>';
									if($case=="movement")
										echo'<td height=10 class="header" align="center"><strong>Trip Vehicle Movement- ('.$startdate.' - '.$enddate.')</strong></td>';								
								?>
							</tr>
						</table>
<?php								
/////////////////////// LOOP FOR NO_OF_DATA //////////////////////////////////////////////////////
if($DEBUG) echo "<br>Case=".$case;
if($case=="trip")
{	
echo'<table border=0 width="100%"cellspacing=0 cellpadding=0>
		<tr>
			<td align="center">
				<div style="width:800px;overflow:auto" align="center">
					<table border=1 width="80%" rules=all bordercolor="#689FFF" align="center" cellspacing=0 cellpadding=3>
						<tr bgcolor="#C4E3FF" style="height:22px;color:#0000FF;">
				       		<td class="text">
								<strong>SL NO</strong>
							</td>
							<td class="text">
								<strong>Vehicle No.</strong>
							</td>
							<td class="text">
								<strong>IEMI NO.</strong>
							</td>
							<td class="text">
								<strong>STATUS.</strong>
							</td>
							<td class="text">
								<strong> RUN HOUR.</strong>
							</td>
							<td class="text">
								<strong>DISTANCE TRAVEL</strong>
							</td>
							';	
							//$Query = "Select * from milestone where ZoneID='$zoneid' and MileStoneType='BS' and Status!='REMOVE' ORDER BY MileStoneSerial";
							$Query = "SELECT * FROM milestone_assignment WHERE group_id='$group_id_mining' AND milestone_type='BS' and status=1 ORDER BY milestone_id";
							//echo "Q1=".$Query."<br>";
							$Result1 = mysql_query($Query, $DbConnection);						
							$num_results1 = mysql_num_rows($Result1);	
							//echo "num_resQ1=".$num_results1."<br>";
							if($num_results1)
							{
								$i=0;
								while($row1 = mysql_fetch_object($Result1))
								{	
									// GET ALL OS
									//$Query2 = "Select * from milestone where ZoneID='$zoneid' and MileStoneType='OS' and Status!='REMOVE' ORDER BY MileStoneSerial";			
									$Query2 = "SELECT * FROM milestone_assignment WHERE group_id='$group_id_mining' AND milestone_type='OS' and status=1 ORDER BY milestone_id";
									$Result2 = mysql_query($Query2, $DbConnection);
									$num_results2 = mysql_num_rows($Result2);
									while($row2 = mysql_fetch_object($Result2))
									{
										$BS = $row1->milestone_name;
										$OS = $row2->milestone_name;											
										//echo '<td class="text"><strong>'.$BS.' -'.$OS.'</strong></td>';	
										$total_ms_col_.$i = 0;
										$i++;
									}
								}
								
								$overall_trip_cnt = 0;
								$m_total = "";
								$columns = 0;
							}		
							echo '<td class="text"><strong>TOTAL TRIPS</strong></td>';								
							echo '<td class="text"><strong>MAXIMUM SPEED</strong></td>';						
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
	//$size1 = sizeof($vserial);
	$size1 = $data_size[$v];
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
	//$no_of_data = sizeof($final_vserial[$v]);		//get 2d array size
	$no_of_data = $data_size[$v];
	//if($DEBUG) echo "<BR><br>NOOF_DATA=".$no_of_data;
	////// STORE RECORDS TEMPORARILY IN 1D ARRAY	
	for($i=0;$i<$no_of_data;$i++)
	{		
		///STORE RECORDS IN 1D ARRAYS
		
		$vserial[$i] = $final_vserial[$v][$i];	
		$vname[$i] = $final_vname[$v][$i];
		$lat[$i] = $final_lat[$v][$i];
		$lng[$i] = $final_lng[$v][$i];
		$datetime[$i] = $final_datetime[$v][$i];
		if($i==($no_of_data-1))
		{
			//echo "vehicleserail=".$final_vserial[$v][$i]."<br>";
			$vehicle_serail_final=$final_vserial[$v][$i];
			$tvs=$final_vs_arr[$final_vserial[$v][$i]]; //trip vehicle status
			$engine_runhr_total  =$engine_runhr_arr[$final_vserial[$v][$i]];	
			$speed_total   =$final_speed_arr[$final_vserial[$v][$i]];
			$distance_total=$final_distance_arr[$final_vserial[$v][$i]];
			//echo "engine_runhr_total=".$engine_runhr_total."<br>";
		}

		//if($DEBUG) echo "<br>outer : lat=".$lat[$i]." ,lng=".$lng[$i]." ,datetime=".$datetime[$i];
		
		if(($lat[$i]!="-" && $lng[$i]!="-") && ($lat[$i]!="" && $lng[$i]!=""))
		{
			//if($DEBUG) echo "<br>inner : lat=".$lat[$i]." ,lng=".$lng[$i]." ,datetime=".$datetime[$i];			
			// CALL VALID_MILESTONE FUNCTION
			//if($DEBUG) echo "<br>check1<br>";
			check_valid_milestone($vserial[$i], $lat[$i], $lng[$i], &$milestone_serial, &$milestone_name, &$milestone_type, &$valid, $group_id_mining, $DbConnection);						
			//if($DEBUG) echo "<br>inner : lat=".$lat[$i]." ,lng=".$lng[$i]." ,milestone_name=".$milestone_name.", datetime=".$datetime[$i]." ,valid=".$valid;
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
					check_leave_milstone($vserial[$j], $lat[$j], $lng[$j], $milestone_serial, $milestone_name, $milestone_type, &$valid, $group_id_mining, $DbConnection);
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
				//$i=$j;		 // COMMENTED ON 15 SEPT
			}  // if valid closed
		} //if lat lng closed	
	}  ////////////////////// MAIN no_of_data LOOP CLOSED  //////////////////////////////////////////////////////
	/*$Query = "Select VehicleName from vehicle where VehicleSerial='$V_NO[$v]'";
	echo "<br>".$Query;
	$Result = mysql_query($Query, $DbConnection);
	
	if($row = mysql_fetch_object($Result))
	{
		$V_NAME = $row->VehicleName;
	} */	
	$V_NAME = $V_NO[$v];	
	/*for($i=0;$i<sizeof($total_milestone_vserial);$i++)
	{
		echo "<br>TTTTTTtotal_milestone_type=".$total_milestone_type[$i];
	}	*/	
	//echo "<br>vsize=".$v.size." totalMS=".sizeof($total_milestone_vserial);
	///////// CHECK TRIP COUNT //////////
	if($case=="trip")
	{
		//echo "<br>Total milestone=".sizeof($total_milestone_vserial);
		check_trip_count($v,$tvs,$vehicle_serail_final,$engine_runhr_total,$speed_total,$distance_total,$V_NAME, $total_milestone_vserial, $total_milestone_vname, $total_milestone_lat, $total_milestone_lng, $total_milestone_serial, $total_milestone_name, $total_milestone_type, $total_milestone_datetime, &$overall_trip_cnt, $group_id_mining, $DbConnection ,$total_milestone_mark,$total_milestone_datetype);
	}
} // V_NO_SIZE CLOSED


/// FINAL TRIP SUM AT END
if($case=="trip")
{
					echo'<!--<tr style="height:20px;" bgcolor="#E2BAF5">
							<td class="text">
								<font color="red">
									<strong>TOTAL</strong>
								</font>
							</td>
							<td class="text">
								<strong>'.$V_NO_SIZE.'</strong>
							</td>
							<td class="text">
								<strong>'.$overall_trip_cnt.'</strong>
							</td>
						</tr>-->
					</table>
				</div>
			</td>
		</tr>
	</table>';
	$columns = 0;
}

///////// CHECK_VALID_MILESTONE FUNCTION - ENTRY POINT ////////////////

function check_valid_milestone($vserial, $lat, $lng, &$milestone_serial, &$milestone_name, &$milestone_type, &$valid, $group_id_mining, $DbConnection)
{	
	global $DEBUG;
  global $group_id_mining;
  //$Query = "Select * from milestone where ZoneID='$zoneid' and Status!='REMOVE' ORDER BY MileStoneSerial";
	$Query = "SELECT * FROM milestone_assignment WHERE group_id='$group_id_mining' and status=1 ORDER BY milestone_id";
	//if($DEBUG) echo "<br>Query=".$Query;
	$Result = mysql_query($Query, $DbConnection);

	//echo $Query;
	$num_results = mysql_num_rows($Result);	
	//if($DEBUG) echo "<br>num_res-VALID=".$num_results."<br>";
	
	if($num_results)
	{
		$m=0;
		while($row = mysql_fetch_object($Result))
		{
			$m_serial[$m] = $row->milestone_id;
			$m_name[$m] = $row->milestone_name;
			$m_type[$m] = $row->milestone_type;
			$coordinates_tmp = $row->coordinates;	
			$coordinates[$m] = base64_decode($coordinates_tmp);
      //if($DEBUG) echo "<br>C1=".$coordinates[$m]."<br>";				
			$m++;
		}
		
		for($n=0; $n<$m; $n++)      // loop for number of milestones
		{						
			//echo "<br>lat===".$lat." lng=".$lng." ms=".$m_serial[$n]." coord=".$coordinates[$n];			
			//echo "<br>Valid MSerial=".$m_serial[$n]."New Coord=".$coordinates[$n];
			check_with_range($lat, $lng, $m_serial[$n], $coordinates[$n], &$status);
			
			if($DEBUG) 
      {        
        //echo "<br><br>Regular ::lat=".$lat." ,lng=".$lng." ,m_name[m]=".$m_name[$n];
        echo "<br>lat=".$lat." ,lng=".$lng." ,m_name[m]=".$m_name[$n]." ,<br>coordinates=".$coordinates[$n]."<br>";
        
        /*if($m_name[$n]=="Stock 1")
        {
          echo "<br>Stock 1:: lat=".$lat." ,lng=".$lng." ,m_name[m]=".$m_name[$n]." ,<br>coordinates=".$coordinates[$n]."<br> ,check with range status=".$status;
        }
        else
        {
          echo "<br><br>Regular ::lat=".$lat." ,lng=".$lng." ,m_name[m]=".$m_name[$n]." ,<br>coordinates=".$coordinates[$n]."<br> ,check with range status=".$status;
        }*/
      }
                  			
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
		
		if($DEBUG) echo "<br>End<br>";
		
		//echo "<br>M=".$m;
	}  // if $num_results closed
	
} //funciton closed


///////// CHECK_LEAVE_MILESTONE FUNCTION -EXIT POINT ////////////////

function check_leave_milstone($vserial, $lat, $lng, $milestone_serial, $milestone_name, $milestone_type, &$valid, $group_id_mining, $DbConnection)
{
  global $DEBUG;
  //$Query = "Select * from milestone where MileStoneSerial='$milestone_serial' and Status!='REMOVE'";
	$Query = "SELECT * FROM milestone_assignment WHERE group_id='$group_id_mining' AND status=1 ORDER BY milestone_id";
  //if($DEBUG) echo "<br>Query4=".$Query."<br>";
	$Result = mysql_query($Query, $DbConnection);
	//echo $Query;
	$num_results = mysql_num_rows($Result);	
	//if($DEBUG) echo "<br>LEAVE-num_res=".$num_results."<br>";
	
	if($num_results)
	{
		if($row = mysql_fetch_object($Result))
		{
			$coordinates_tmp = $row->coordinates;	
			$coordinates1= base64_decode($coordinates_tmp);
      //if($DEBUG) echo "<br>C2=".$coordinates1."<br>";		
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
						
///////// CHECK_TRIP_COUNT FUNCTION  ////////////////

	function check_trip_count($v,$tvs, $vehicle_serail_final,$engine_runhr_total, $speed_total, $distance_total, $V_NAME, $total_milestone_vserial, $total_milestone_vname, $total_milestone_lat, $total_milestone_lng, $total_milestone_serial, $total_milestone_name, $total_milestone_type, $total_milestone_datetime, &$overall_trip_cnt, $group_id_mining, $DbConnection , $total_milestone_mark, $total_milestone_datetype)
	{		
		global $DEBUG;
		$m=0;
		$trip_count = 0;
		$m_size = sizeof($total_milestone_vserial);
		//echo "<br>Total Msize=".$m_size;
		$mid_trip = false;	
		////////// get number of Operating Stations  /////////
		$os_limit =0;
		$bs_limit = 0;	
		//$Query = "Select * from milestone where ZoneID='$zoneid' and Status!='REMOVE' ORDER BY MileStoneSerial";
		$Query = "SELECT * FROM milestone_assignment WHERE group_id='$group_id_mining' AND status=1 ORDER BY milestone_id";
		//if($DEBUG) echo "<br>Q5=".$Query."<br>";
		$Result1 = mysql_query($Query, $DbConnection);
		//echo $Query;
		$num_results1 = mysql_num_rows($Result1);
		//if($DEBUG) echo "<br>num_results1A=".$num_results1."<br>";
	
		while($row = mysql_fetch_object($Result1))
		{			
			if($row->milestone_type=='OS')
			{
				$OS_1[$os_limit] = $row->milestone_name;	
				$os_limit++;
			}
			else if($row->milestone_type=='BS')
			{
				$BS_1[$bs_limit] = $row->milestone_name;	
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
			
			$bs_name_tmp="";
			//echo "<br>";
			for($k=0; $k<$m_size; $k++)
			{				
			  //if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.160") 
			  //if($DEBUG) echo "<br>k=".$k.", totalmilestones=" .$total_milestone_name[$k]. ",  mname=".$total_milestone_vname[$k].",  mtype=".$total_milestone_type[$k].",   status=".$total_milestone_datetype[$k].",   mark=".$total_milestone_mark[$k].", lat=".$total_milestone_lat[$k].", lng=".$total_milestone_lng[$k].", Datetime=".$total_milestone_datetime[$k]."<BR><BR>";				
					
				if(($total_milestone_type[$k] == "BS") && ($mid_trip == false)  && ($total_milestone_datetype[$k] == "LEAVE"))
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
					$bs_name_tmp = $total_milestone_name[$k];
					//$total_milestone_mark[$k] == "TRUE";
				}		
				if(($start_trip == true) && ($total_milestone_type[$k] == "OS") && ($total_milestone_name[$k] == $OS_1[$i])  && ($total_milestone_datetype[$k] == "ENTER"))
				{
					//echo "<br><br>mid trip".$k;
					$mid_trip = true;
					$start_trip = false;
					$total_milestone_mark[$k] == "TRUE";				
					//echo "<br>in Mid trip";
				}						
				//echo "<br>tot-bs=".$total_milestone_type[$k];			
				//if( ($mid_trip == true) && ($total_milestone_type[$k] == "BS") && ($total_milestone_datetype[$k] == "ENTER") )
				if(($mid_trip == true) && ($total_milestone_type[$k] == "OS") && ($total_milestone_name[$k] == $OS_1[$i]) && ($total_milestone_datetype[$k] == "LEAVE") )
				{		
					//echo "<BR>In end trip"." NAME=".$start_milestone_name."<BR>BS lmit=".$bs_limit."<BR>";
					$end_trip = true;
					$mid_trip = false;
					$trip_count ++;	
					
					for($j=0;$j<$bs_limit;$j++)
					{
						if($BS_1[$j]==$bs_name_tmp)
						{
							//echo "<br>matched";
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
		echo '<td class="text">'.$tvs.'</td>';
		echo '<td class="text">'.$vehicle_serail_final.'</td>';		
		echo '<td class="text">'.$engine_runhr_total.'</td>';
		echo '<td class="text">'.$distance_total.'</td>';


		
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
		echo '<!--<td class="text">'.$TRIP_CNT[$i].'</td>-->';		// TRIP COUNT FOR INDIVIDUAL MILESTONE ( SINGLE COLUMN )	
		//$total_ms_col_.$j = $total_ms_col_.$j + $TRIP_CNT[$j];		// COLUMN WISE- TRIP SUM		
		$total_vehicle_trip = $total_vehicle_trip + $TRIP_CNT[$i];	// ROW WISE- TRIP SUM			
		//echo "<br>TRIP_CNT= ". $TRIP_CNT[$j];
		//echo "<br>total mscol = ". $total_ms_col_15;
		//echo "<br>total_vehicle_trip = ". $total_vehicle_trip."<br><br>";	
	}
	echo '<td class="text"><a href="#" onclick="javascript:action_report_trip_old(\''.$vehicle_serail_final.'\');">'.$total_vehicle_trip.'</a></td>';
	echo '<td class="text">'.$speed_total.'</td>'; // TOTAL TRIP COUNT FOR INDIVIDUAL VEHICLE	( SUM AT ROW END)
	echo '</tr>';
	
	global $overall_trip_cnt;								
	$overall_trip_cnt = $overall_trip_cnt + $total_vehicle_trip;	// OVERALL TRIP COUNT FOR ALL VEHICLES (TOTAL SUM)	
}

///////// CHECK_OVERALL_MOVEMENT FUNCTION  ////////////////
function overall_movement($V_NAME, $total_milestone_vserial, $total_milestone_vname, $total_milestone_lat, $total_milestone_lng, $total_milestone_serial, $total_milestone_name, $total_milestone_type, $total_milestone_datetime, $total_milestone_datetype)
{	
	global $DEBUG;
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
	
	$current_milestone_name = "";
	$prevoius_milestone_name = "";
	
  for($k=0; $k<$m_size; $k++)
	{
		$current_milestone_name = $total_milestone_name[$k];
		$type1 = $total_milestone_datetype[$k] ;
   	
		if(($current_milestone_name!=$prevoius_milestone_name) && ($type1!="LEAVE") )
		{      
      echo '<tr>';
			echo '<td class="text">'.$total_milestone_name[$k].'</td>';
			echo '<td class="text">'.$total_milestone_datetime[$k].'</td>';	
			echo '<td class="text">'.$total_milestone_type[$k].'</td>';
			//echo '<td class="text">'.$total_milestone_datetype[$k].'</td>';
			echo '</tr>';	
      $prevoius_milestone_name = 	$current_milestone_name;				      	
		}	
    else if($type1=="LEAVE")
    {
      //$prevoius_milestone_name="";
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
<div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>
<div id="tripLoadingBlackout"> </div>
<div id="tripLoadingDivPopUp">
	<div id='showResult'>
	</div>
</div>
</BODY>
</HTML>