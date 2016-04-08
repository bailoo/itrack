<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('common_xml_element.php');
include_once("tripinfo/check_with_range.php");    
include_once("read_filtered_xml.php"); 
$DEBUG =0;

$t=time();
$xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$t.".xml";

//include_once("tripinfo/get_filtered_xml_zone.php");      // WRITE SORTED XML , FINAL XML NAME STORED IN 'xmltowirte' VARIABLE
include_once("tripinfo/get_filtered_xml_milestone.php");      // WRITE SORTED XML , FINAL XML NAME STORED IN 'xmltowirte' VARIABLE
//$vehicleserial= $_POST['vserial'];
$case = $_POST['case'];
$group_id_mining = $_POST['group_id_local'];

//$startdate = str_replace('/','-',$startdate);
//$enddate = str_replace('/','-',$enddate);
//echo "<br>v:".$v.",m:".$m.",g:".$group_id_mining.",st=".$startdate.",ed=".$enddate;

//read_track_xml($xmltowrite, &$datetime, &$vehicleserial, &$vehiclename, &$lat, &$lng, &$alt, &$vehicletype, &$speed, &$fuel, &$cumdist);
read_tripdata_xml($xmltowrite, &$datetime, &$vehicleserial, &$vehiclename, &$lat, &$lng, &$speed);
$size = sizeof($vehicleserial);
//echo "<br>size=".$size;

unlink($xmltowrite);

$j=-1;
$k=0;

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
	$final_vname[$j][$k] = $vehiclename[$i];
	$final_lat[$j][$k] = $lat[$i];		
	$final_lng[$j][$k] = $lng[$i];	
	$final_datetime[$j][$k] = $datetime[$i];
  $final_speed[$j][$k] = $speed[$i];	
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

//echo "<br>vnosize=".$V_NO_SIZE; 
			  				
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
										echo'<td height=10 class="header" align="center"><strong>Trip Summary Report - ('.$startdate.' - '.$enddate.')</strong><br></td>';							
								?>
							</tr>
						</table>						
								
<?php
/////////////////////// LOOP FOR NO_OF_DATA //////////////////////////////////////////////////////

echo'<table border=0 width="50%"cellspacing="0" cellpadding="0" align="center" rules=all>';
/*echo '<tr bgcolor="#C4E3FF" style="height:22px;">';
echo '<td align="center" style="font-size:17px;background-color:#C4E3FF;" colspan="8" align="center"><strong>FINAL OUTPUT</strong></td>';	
echo '</tr>';*/

echo '<tr>';
echo '<td align="center" style="font-size:12px;background-color:#FFFF00;" width="25px"><strong>DATE</strong></td>';
echo '<td align="center" style="font-size:12px;background-color:#CCFFCC;" width="50px"><strong>'.$date.'</strong></td>';		
echo '<td align="center" style="font-size:15px;background-color:#FFFF00;" align="center" colspan="2"><strong>TATA MINES</strong></td>';		
echo '</tr>';

echo '<tr>';
echo '<td align="center" style="background-color:#CC99FF;"><strong></strong></td>';
echo '<td align="center" style="background-color:#CC99FF;"><strong></strong></td>';	
echo '<td align="center" style="font-size:12px;background-color:#00CCFF;" width="100px;"><strong>Total Trip</strong></td>';	
echo '<td align="center" style="font-size:12px;background-color:#C0C0C0;" align="center"><strong>Speed violation(Hrs:min) > 40km</strong></td>';		
echo '</tr>';	

echo '<tr>';
echo '<td align="center" style="font-size:12px;background-color:#FF6600;"><strong>Serial</strong></td>';
echo '<td align="center" style="font-size:12px;background-color:#FF00FF;"><strong>Vehicle</strong></td>';	
echo '<td width="30px" style="font-size:12px;background-color:#FF6600;"><strong></strong></td>';	
echo '<td width="30px" style="font-size:12px;background-color:#FF00FF;"><strong></strong></td>';	
echo '</tr>';
  	
$total_trip_cnt = 0;

for($v=0; $v<$V_NO_SIZE; $v++)
{
  //$speedv_count =0;
  //$flag = 1;
  
  $speed_violated_time = 0;
  $flag = 0;
  $max_speed = 40; 	  
		
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
    $speed[$i] = "";		
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
	if($DEBUG) echo "<BR><br>NOOF_DATA=".$no_of_data;

	////// STORE RECORDS TEMPORARILY IN 1D ARRAY
	
	for($i=0;$i<$no_of_data;$i++)
	{		
    ///STORE RECORDS IN 1D ARRAYS
		$vserial[$i] = $final_vserial[$v][$i];
		$vname[$i] = $final_vname[$v][$i];
		$lat[$i] = $final_lat[$v][$i];
		$lng[$i] = $final_lng[$v][$i];
		$datetime[$i] = $final_datetime[$v][$i];
    $speed[$i] = $final_speed[$v][$i];		
			
		if( ($lat[$i]!="-" || $lng[$i]!="-") || ($lat[$i]!="" || $lng[$i]!="") )
		{
			// CALL VALID_MILESTONE FUNCTION
			check_valid_milestone($vserial[$i], $lat[$i], $lng[$i], &$milestone_serial, &$milestone_name, &$milestone_type, &$valid, $group_id_mining, $DbConnection);
			
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

				$i=$j;		
			}  // if valid closed
		
      // CALCULATE SPEED VIOLATION
      
      if( ($speed[$i] > $max_speed) && ($flag==0) )
      {
      	$violation_start_time = $datetime[$i];
      	$flag = 1;
      }
      else if( ($speed[$i] < $max_speed) && ($flag==1) )
      {
      	$time1 = strtotime($violation_start_time); 
      	$time2 = strtotime($datetime[$i]);
      	$speed_violated_time = $speed_violated_time + ($time2 - $time1);   
      	//echo "<br>violated=".$speed_violated_time;         
      	$flag = 0;
      } 		    
    
        /*
      if($flag == 1 && $speedv_count==0)
      {
        //echo "<br>speed=".$speed[$i];
        if($speed[$i]>40)
        {
          $speedv_count = $speedv_count + 1;
        }
        //$time1 = strtotime($violation_start_time); 
        //$time2 = strtotime($datetime[$i]);
        //$speed_violated_time = $speed_violated_time + ($time2 - $time1);   	 
      }
      else
      {
         if($speed[$i]<=40)
         {
            $flag1 = 0;
         }
         else
         {
           $flag1 = 1;
           $speedv_count = $speedv_count + 1;
         }       
      }   */   
      // SPEED VIOLATION CLOSED
    
    } //if lat lng closed
	
	}  ////////////////////// MAIN no_of_data LOOP CLOSED  //////////////////////////////////////////////////////

	/*$Query = "Select VehicleName from vehicle where VehicleSerial='$V_NO[$v]'";
	echo "<br>".$Query;
	$Result = mysql_query($Query, $DbConnection);
	
	if($row = mysql_fetch_object($Result))
	{
		$V_NAME = $row->VehicleName;
	} */
		
	if($speed_violated_time)
	{			  
	  $violated_dur =  ($speed_violated_time)/3600;     
	  $violated_dur = round($violated_dur,2);										
	  $total_min = $violated_dur * 60;           
	  $hr = (int)($total_min / 60);
	  $minutes = $total_min % 60;										      
	  $hrs_min = $hr.":".$minutes; 	
	  
	  $violated_time = $hrs_min;  // STORE IN SHIFT ARRAY	
	} 
	else
	{
	  $violated_time = 0;
	}
      
  $V_NAME = $V_NO[$v];	
    	
  //$V_NAME = $vehiclename[$v];	
  
  echo '<tr bgcolor=#CDF7F7>';
  //check_trip_count($v,$V_NAME, $total_milestone_vserial, $total_milestone_vname, $total_milestone_lat, $total_milestone_lng, $total_milestone_serial, $total_milestone_name, $total_milestone_type, $total_milestone_datetime, &$overall_trip_cnt, $group_id_mining, $DbConnection ,$total_milestone_mark,$total_milestone_datetype, &$trip);  	
  check_trip_count($v,$V_NAME, $total_milestone_vserial, $total_milestone_vname, $total_milestone_lat, $total_milestone_lng, $total_milestone_serial, $total_milestone_name, $total_milestone_type, $total_milestone_datetime, &$overall_trip_cnt, $group_id_mining, $DbConnection ,$total_milestone_mark,$total_milestone_datetype);
  echo'<td class="text"><strong>'.$violated_time.'</strong></td>';
  echo '</tr>';
	/*if($flag == 1 && $speed_violated_time==0)
	{
		$time1 = strtotime($violation_start_time); 
		$time2 = strtotime($datetime[$i]);
		$speed_violated_time = $speed_violated_time + ($time2 - $time1);   	 
	}
	
	if($speed_violated_time)
	{	  
	  $violated_time ="";
    $violated_dur =  ($speed_violated_time)/3600;     
	  $violated_dur = round($violated_dur,2);										
	  $total_min = $violated_dur * 60;           
	  $hr = (int)($total_min / 60);
	  $minutes = $total_min % 60;										      
	  $hrs_min = $hr.":".$minutes; 	
	  
	  $violated_time = $hrs_min;  // STORE IN SHIFT ARRAY		  
	} 
	else
	{
	  $violated_time = 0;
	} */    

  /*echo "<tr style='height:20px;' bgcolor='#E2BAF5'>";
  echo'<td class="text"><strong>'.$overall_trip_cnt.'</strong></td>';
  echo'<td class="text"><strong>'.$overall_trip_cnt.'</strong></td>';
  echo'<td class="text"><strong>'.$overall_trip_cnt.'</strong></td>';   
  echo '</tr>';*/
} // V_NO_SIZE CLOSED


echo '</table>';

///////// CHECK_VALID_MILESTONE FUNCTION - ENTRY POINT ////////////////

function check_valid_milestone($vserial, $lat, $lng, &$milestone_serial, &$milestone_name, &$milestone_type, &$valid, $group_id_mining, $DbConnection)
{	
	//$Query = "Select * from milestone where ZoneID='$group_id_mining' and Status!='REMOVE' ORDER BY MileStoneSerial";
	$Query = "SELECT * FROM milestone_assignment WHERE group_id='$group_id_mining' AND status=1 ORDER BY milestone_id";
	if($DEBUG) echo "<br>valid=".$Query;
	$Result = mysql_query($Query, $DbConnection);

	//echo $Query;
	$num_results = mysql_num_rows($Result);	
	if($DEBUG) echo "<br>num_res-VALID=".$num_results;
	
	if($num_results)
	{
		$m=0;
		while($row = mysql_fetch_object($Result))
		{
			$m_serial[$m] = $row->milestone_id;
			$m_name[$m] = $row->milestone_name;
			$m_type[$m] = $row->milestone_type;			
      $coordinates_tmp = $row->coordinates;	
      $coordinates[$m]= base64_decode($coordinates_tmp);       		
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

function check_leave_milstone($vserial, $lat, $lng, $milestone_serial, $milestone_name, $milestone_type, &$valid, $group_id_mining, $DbConnection)
{
	//$Query = "Select * from milestone where MileStoneSerial='$milestone_serial' and Status!='REMOVE'";
	$Query = "SELECT * FROM milestone_assignment WHERE group_id='$group_id_mining' AND status=1 ORDER BY milestone_id";
	if($DEBUG) echo "<br>leave=".$Query."<br><br>";
	$Result = mysql_query($Query, $DbConnection);
	//echo $Query;
	$num_results = mysql_num_rows($Result);	
	if($DEBUG) echo "<br>LEAVE-num_res=".$num_results;
	
	if($num_results)
	{
		if($row = mysql_fetch_object($Result))
		{
      $coordinates_tmp = $row->coordinates;	
      $coordinates1= base64_decode($coordinates_tmp); 			
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

function check_trip_count($v, $V_NAME, $total_milestone_vserial, $total_milestone_vname, $total_milestone_lat, $total_milestone_lng, $total_milestone_serial, $total_milestone_name, $total_milestone_type, $total_milestone_datetime, &$overall_trip_cnt, $group_id_mining, $DbConnection , $total_milestone_mark, $total_milestone_datetype)
{					
	$m=0;
	$trip_count = 0;
	$m_size = sizeof($total_milestone_vserial);
	//echo "<br>Total Msize=".$m_size;
	$mid_trip = false;
	
	////////// get number of Operating Stations  /////////

	$os_limit =0;
	$bs_limit = 0;
	
	//$Query = "Select * from milestone where ZoneID='$group_id_mining' and Status!='REMOVE' ORDER BY MileStoneSerial";
	$Query = "SELECT * FROM milestone_assignment WHERE group_id='$group_id_mining' AND status=1 ORDER BY milestone_id";
	if($DEBUG) echo "<br>".$Query." Dbcon=".$DbConnection;
	$Result1 = mysql_query($Query, $DbConnection);
	//echo $Query;
	$num_results1 = mysql_num_rows($Result1);
	if($DEBUG) echo "<br>numres=".$num_results1;
	
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
				$bs_name_tmp = $total_milestone_name[$k];
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
			//if( ($mid_trip == true) && ($total_milestone_type[$k] == "BS") && ($total_milestone_datetype[$k] == "ENTER") )
			if( ($mid_trip == true) && ($total_milestone_type[$k] == "OS") && ($total_milestone_name[$k] == $OS_1[$i])  && ($total_milestone_datetype[$k] == "LEAVE"))
      {		
				//echo "<BR>In end trip"." NAME=".$start_milestone_name."<BR><BR>";
				$end_trip = true;
				$mid_trip = false;
				$trip_count ++;	

				for($j=0;$j<$bs_limit;$j++)
				{
					//if($BS_1[$j]==$total_milestone_name[$k])
					if($BS_1[$j]==$bs_name_tmp)
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
	
	echo '<td class="text">'.++$v.'</td>';
	echo '<td class="text">'.$V_NAME.'</td>';
			
	global $m_total;
	global $columns;
	$columns = $m;		
	
	//echo "<br>m=".$m;
	
	for($i=0; $i<$m; $i++)
	{		
		$flag = 1;
		$total_vehicle_trip = $total_vehicle_trip + $TRIP_CNT[$i];	// ROW WISE- TRIP SUM			
		//echo "<br>TRIP_CNT= ". $TRIP_CNT[$j];
		//echo "<br>total mscol = ". $total_ms_col_15;
		//echo "<br>total_vehicle_trip = ". $total_vehicle_trip."<br><br>";	
	}
		
	echo '<td class="text"><strong>'.$total_vehicle_trip.'<strong></td>';  // TOTAL TRIP COUNT FOR INDIVIDUAL VEHICLE	( SUM AT ROW END)
	
	//global $overall_trip_cnt;								
	//$overall_trip_cnt = $overall_trip_cnt + $total_vehicle_trip;	// OVERALL TRIP COUNT FOR ALL VEHICLES (TOTAL SUM)
	
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