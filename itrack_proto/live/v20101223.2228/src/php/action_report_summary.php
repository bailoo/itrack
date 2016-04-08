<?php
include_once('SessionVariable.php');
include_once("PhpMysqlConnectivity.php");

if($access=='Zone')
{
	include("get_mining_location.php");
}
else
{
	include("get_location.php");
}


$user_interval = 60;     
			
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


//$user_interval = $_POST['user_interval'];

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
				//echo "<br>xml_date=".$xmldate;

				//echo "<br>date=".$arr[9] ." $ ".$date1." $ ".$date2;
				//echo "<br>date=".$xmldate ." $ ".$date1tmp." $ ".$date2tmp;				
			
				if( ($xmldate >=$date1tmp) && ($xmldate <=$date2tmp) && ($xmldate!="" || $xmldate!=0) && ($arr[5]!="") && ($arr[6]!="") )
				{						
					$all_vehicle_records[$k1] = $arr[0]."$".$arr[1]."$".$arr[2]."$".$arr[3]."$".$arr[4]."$".$arr[5]."$".$arr[6]."$".$arr[7]."$".$arr[8]."$".$arr[9];
					//echo "<br>IN".$vehicle_records[$k1];
					
          /*if($test_vts=='debug')
          {    
            echo "<br>Lat=".$arr[5];
            echo "<br>Lng=".$arr[6]."<br>";
          }*/
					
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
  $k2 = 0;		
	//echo "<br>k1=".$k1."<br>";

	for($j=0;$j<$k1;$j++)
	{	
		$main_arr = explode("$",$all_vehicle_records[$j]);
		
		$vehicle_serial1 = $main_arr[1];

		if($vehicle_serial[$i] == $vehicle_serial1)	
		{
       $vehicle_records[$i][$k2]  = $all_vehicle_records[$j];
    		if($test_vts=='debug')
        {    
          echo "<br>v1=".$vehicle_records[$i][$k2]." i=".$i." ,k2=".$k2;          
        }         	
      //echo "<br>v1=".$vehicle_records[$i][$k2];	
			$k2++;
		}
		//echo $vehicle_records[$i]."<br>";
	}
	
	$k2_limit[$i] = $k2;
}
	
//////////////// GET TOTAL DISTANCE ////////////////
	if($test_vts=='debug')
  { 
      echo "<br> SECOND LOOP<br><br>";
  }

for($i=0;$i<$v_size;$i++)
{
	$main_arr = explode("$",$vehicle_records[$i][0]);
	$lat_ref = $main_arr[5];
	$lng_ref = $main_arr[6];
	
	$next_coord = 0;
	
	$total_dist[$i] = 0;           // SET TOTAL DIST TO ZERO
	
	/*if($test_vts=='debug')
	{
    echo "<br>k2===".$k2;
  }*/
	
	for($j=1; $j<$k2_limit[$i]; $j++)
	{
		$main_arr = explode("$",$vehicle_records[$i][$j]);
		$lat_cr = $main_arr[5];
		$lng_cr = $main_arr[6];
		$datetime_cr = $main_arr[9];
		
 		if($test_vts=='debug')
    {    
        echo "<br>v2=".$vehicle_records[$i][$j]." i=".$i." ,j=".$j;
    }   
		  
    calculate_mileage($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);    
    //echo "<br>lat1=".$lat_ref." lat2=".$lat_cr." | lng1=".$lng_ref." lng2=".$lng_cr;	
	
    //echo "<br>dist=".$distance;
    
    if (($distance > 0.200) || ($j == $k2_limit[$i] -1))
		{
			$total_dist[$i] = $total_dist[$i] + $distance;      // TOTAL DISTANCE			      
						
			//echo "<br>total_dist=". $total_dist[$i];
    }
    
      $lat_ref =  $lat_cr;
      $lng_ref =  $lng_cr;
  }	
}
		 		
/////////////// GET RECORDS WHICH ARE MORE THAN 0.05 KM FROM PREVIOUS POINT

$alt ="-";

for($i=0;$i<$v_size;$i++)
{
	$c =0;	
	$location_track[$i] = "";           // SET TRACK LOCATION TO ZERO  
  $location_tmp = "";
  $loc_count = 0;
  $place ="";
  $partial_dist = 0;
  $interval_dist = 0;
   
	$main_arr = explode("$",$vehicle_records[$i][0]);
	$vserial = $main_arr[1];
	$vname = $main_arr[2];
	$lat_ref = $main_arr[5];
	$lng_ref = $main_arr[6];	
	
	$datetime_ref = $main_arr[9];
	
	$vehiclename[$i] = $vname;     // VEHICLENAME
  $start_date[$i] = $datetime_ref;   // START DATE TIME
	$start_lat[$i] =  $lat_ref;
	$start_lng[$i] =  $lng_ref;

  //echo "<br>StartDate===".$start_date[$i];
   
	$halt_flag = 0;
	$date_secs1 = strtotime($datetime_ref);
	//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
	$date_secs1 = (double)($date_secs1 + $interval);
	
	/// GET EQUI-DIST INTERVAL
  $interval_dist = ( (round($total_dist[$i],2)) / 4);
  
  if($test_vts=='debug')
  {
    //echo "<br>Totaldist=".$total_dist[$i]." Interval=".$interval_dist[$i];
  }
  
  get_location($start_lat[$i],$start_lng[$i],$alt,&$place,$DbConnection);    // GET START LOCATION
  $start_loc[$i] =  $place;           // START LOCATION
  
  if(!($place=="" || $place=="-") )
  {
    $location_tmp = $location_tmp . $place."<font color=red>-></font>";
  }
  //$location_track[$i] =  $location_tmp;
  
	//echo "interval dist=".$interval_dist[$i]." stlat=".$start_lat[$i]." stlong=".$start_lng[$i]." start_loc=".$start_loc[$i]."<br>";

  if($test_vts=='debug')
  {    
    echo "<br>k2_limit1=".$k2_limit[$i];          
  }    
        
  for($j=1; $j<$k2_limit[$i]; $j++)
	{		
    //echo"in j"."<br>";
		//echo "SS=".$vehicle_records[$i][$j]."<br>";
		$main_arr = explode("$",$vehicle_records[$i][$j]);
	
		//$vehicleserial = $main_arr[0];
		//$vehiclename = $main_arr[1];
		$lat_cr = $main_arr[5];
		$lng_cr = $main_arr[6];
		$datetime_cr = $main_arr[9];
		//echo "<br>DateCR1=".$datetime_cr;
		
		//$time2 = $datetime[$i][$j];											
		$date_secs2 = strtotime($datetime_cr);
				
    calculate_mileage($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);
    
    $partial_dist = $partial_dist +  $distance;  
    
    if($test_vts=='debug')
    {    
      //echo "<br>interval_dist=".$interval_dist." partial_dist=".$partial_dist;
    }   
    
    if( ($partial_dist >= $interval_dist) && ($loc_count<6) )
    {
        if($test_vts=='debug')
        {
        echo "<br>INSIDE";
        }
        get_location($lat_cr,$lng_cr,$alt,&$place,$DbConnection);
        
        if(!($place=="" || $place=="-") )
        {
        $location_tmp = $location_tmp . $place."<font color=red>-></font>";
        }
       
       $partial_dist = 0;
       $loc_count++;
    }
      
    ///  CODE FOR HALT
    
    if($test_vts=='debug')
    {    
      echo "<br>k2_limit2=".$k2_limit[$i];          
    }    
        
    if (($distance > 0.200) || ($j == ($k2_limit[$i] -1) ) )
		{				
      //echo "<br>In dist ".$distance." lat_ref ".$lat_ref." lng_ref ".$lng_ref." lat_cr ".$lat_cr." lng_cr ".$lng_cr."<br>";
			if ($halt_flag == 1)
			{				
				//echo "<br>In flag1";
				//duration
				$arrivale_time=$datetime_ref;
				$starttime = strtotime($datetime_ref);				
				
        if($test_vts=='debug')
        {    
          echo "<br>k2_limit3=".$k2_limit[$i];          
        }    
          
        if ($j == ($k2_limit[$i] -1) )
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
				
				//////// GET DATETIME DIFFERENCE IN HR.MIN (HALT)  ////////////
        $halt_dur =  ($stoptime - $starttime)/3600;
			
				$halt_duration[$j] = round($halt_dur,2);										
				$total_min = $halt_duration[$j] * 60;

				$total_min1[$j] = $total_min;
				
				//echo "toatal_min=".$total_min1[$j]."user-interval=".$user_interval;

				$hr = (int)($total_min / 60);
				$minutes = $total_min % 60;										

				$hrs_min = $hr.".".$minutes;
				////////////////////////////////////////////////////////
				
				if($total_min1[$j] >= $user_interval )
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
			
        if($test_vts=='debug')
        {    
          echo "<br>k2_limit4=".$k2_limit[$i];          
        }    
  
      if($j==($k2_limit[$i]-1))         // LAST RECORD
      {
         //echo "<br>DateCR2=".$datetime_cr;
         
        $end_date[$i] = $datetime_ref;      // END DATE TIME
        $end_lat[$i] =  $lat_ref;
        $end_lng[$i] =  $lng_ref;
        
        get_location($end_lat[$i],$end_lng[$i],$alt,&$place,$DbConnection);    // GET START LOCATION
        $end_loc[$i] =  $place;     //  END LOCATION
             
        if($test_vts=='debug')
        {				
          //echo "<br>ED=". $end_date[$i]." SD=".$start_date[$i];
          //echo "<br>c1=(". $start_lat[$i].",".$end_lat[$i].")  c2=(". $end_lat[$i].",".$end_lng[$i].")";
        }
				
        //////// GET DATETIME DIFFERENCE IN HR.MIN (JOURNEY TIME) ////////////
        $end_date1 = strtotime($end_date[$i]);
        $start_date1 = strtotime($start_date[$i]);
        
        $j_diff =  ($end_date1 - $start_date1)/3600;

        if($test_vts=='debug')        
        {
          //echo "<br> j_diff=".$j_diff;
        }
			
				$j_diff = round($j_diff,2);										
				$total_min = $j_diff * 60;		
        
        if($test_vts=='debug')
        {
          //echo "<br> total_min=".$total_min;
        }	

				$hr = (int)($total_min / 60);
				$minutes = $total_min % 60;										

				$journey_time[$i] = $hr.".".$minutes;   
				//////////////////////////////////////////////////////////////////////

        if(!($place=="" || $place=="-") )
        {
          $location_tmp = $location_tmp . $place;  
        }   
        
        if($test_vts=='debug')
        {
         // echo "<br>location_tmp =".$location_tmp;
        }
        
        if($location_tmp)
        {
          $location_track[$i] = $location_tmp;    // MULTIPLE LOCATION STRING
        }
        else
        {
          $location_track[$i] = "-";
        }
      }    
			
			$halt_flag = 0;
		}
		else
		{
			//echo "in else";
			$halt_flag = 1;
		}			
		$date_secs1 = strtotime($datetime_cr);
		$date_secs1 = (double)($date_secs1 + $interval);        			
				
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
								<td height=10 STYLE="background-color:#f0f7ff" class="text" align="center">Summary Report</td>
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

			if($v_size>0)
			{
				  //echo "<br>user_interval1=".$user_interval;
				
					echo '<center>';
				
						//echo '<input type="hidden" name="uid1" value="'.$uid.'"';
						
						for($i=0;$i<$vehicleid_size;$i++)
						{
							//echo "<br>vid=".$vehicleid[$i];
							echo '<input type="hidden" name="vehicleid[]" value="'.$vehicleid[$i].'"';
						}						
						
						echo '<input type="hidden" name="StartDate" value="'.$date1.'"';
						echo '<input type="hidden" name="EndDate" value="'.$date2.'"';

				echo '</center>';																
					
					$alt ="-";
					
					for($i=0;$i<$v_size;$i++)
					{				
						//echo "<br>counter=".sizeof($counter[$i]);
						$halt_tmp ='';						
            
            // GET HALT DETAIL
           
            /*if($test_vts=='debug')
            {    
              echo "<br>k2_limit6=".$k2_limit[$i];          
            }*/    
             
            for($j=0;$j<$k2_limit[$i];$j++)
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
													
              if($hrs_min_1[$i][$j])
              {
                $halt_tmp = $halt_tmp.$arrival_time_1[$i][$j].'<font color=green> to </font>'.$depature_time_1[$i][$j].'<font color=red> -> </font>'.$hrs_min_1[$i][$j].'<br>';														
						  }
            }	            	
            	$halt[$i] = $halt_tmp;	
              
              //echo "halt=".$halt[$i].'<br><br>';	
					}
															                    
        // PRINT REPORT
        
        echo '<table border=1 width="95%" bordercolor="#e5ecf5" align="center" cellspacing="0" cellpadding="0">';
       
        echo '<tr valign="top">';
        echo '<td class="text" align="left"><strong>Vehiclename</strong></td>';
        echo '<td class="text" align="left" width="70px"><strong>StartDate</strong></td>';
        echo '<td class="text" align="left" width="70px"><strong>EndDate</strong></td>';
        echo '<td class="text" align="left" width="105px"><strong>Start Location</strong></td>';
        echo '<td class="text" align="left" width="105px"><strong>End Location</strong></td>';    
        echo '<td class="text" align="left" width="50px"><strong>Total Distance (km)</strong></td>';
        echo '<td class="text" align="left" width="50px"><strong>Journey Time (hr.min)</strong></td>';
        echo '<td class="text" align="left" width="320px"><strong>Halt (hr.min)-1 hr interval</strong></td>';  
        echo '<td class="text" align="left"><strong>Track</strong></td>';  
        echo '</tr>';
          
        for($i=0;$i<$v_size;$i++)
        {         
          echo'<tr valign="top">';          
         
          $sd_arr = explode(" ",$start_date[$i]);
          $ed_arr = explode(" ",$end_date[$i]);
          
          $sd =  $sd_arr[0];
          $st =  $sd_arr[1];
          $ed =  $ed_arr[0];
          $et =  $ed_arr[1];
          
          echo '<td class="text" align="left">'.$vehiclename[$i].'</td>';
          echo '<td class="text" align="left">'.$sd.'<br>'.$st.'</td>';
          echo '<td class="text" align="left">'.$ed.'<br>'.$et.'</td>';
          echo '<td class="text" align="left">'.$start_loc[$i].'</td>';
          echo '<td class="text" align="left">'.$end_loc[$i].'</td>';
          echo '<td class="text" align="left">'.round($total_dist[$i],2).'</td>';
          echo '<td class="text" align="left">'.$journey_time[$i].'</td>';
          echo '<td class="text" align="left">'.$halt[$i].'</td>';  
          echo '<td class="text" align="left">'.$location_track[$i].'</td>';                                                
              
          echo '</tr>';            
        }
       echo '</table>';
              
       	echo'<form method="post" action="getpdf3.php?size='.$v_size.'" target="_blank">'; 
        //PDF CODE        
								
				$title="Summary Report : (".$date1 .' to '.$date2.")";						
				echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
        
        for($i=0;$i<$v_size;$i++)
				{												
					//$alt_ref="-";
          $vehiclename1 = $vehiclename[$i];
          $start_date1=$start_date[$i];
          $end_date1 = $end_date[$i];
          $start_loc1 = $start_loc[$i];
          $end_loc1 = $end_loc[$i];
          $total_dist1 = round($total_dist[$i],2);
          $journey_time1 = $journey_time[$i];
          $halt1 = $halt[$i];  
                    
          $halt1 = str_replace("<font color=green>"," ",$halt1);
          $halt1 = str_replace("<font color=red>"," ",$halt1);
          $halt1 = str_replace("</font>"," ",$halt1);
          $halt1 = str_replace("<br>","             ",$halt1);
          
          $location_track1 = $location_track[$i];      
          $location_track1 = str_replace("<font color=red>"," ",$location_track1);	
          $location_track1 = str_replace("</font>"," ",$location_track1);												
					   										
                     																					
					$sno_1 = $i+1;										
					echo"<input TYPE=\"hidden\" VALUE=\"$sno_1\" NAME=\"temp[$i][SNo]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$vehiclename1\" NAME=\"temp[$i][Vehiclename]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$start_date1\" NAME=\"temp[$i][StartDate]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$end_date1\" NAME=\"temp[$i][EndDate]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$start_loc1\" NAME=\"temp[$i][Start Location]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$end_loc1\" NAME=\"temp[$i][End Location]\">";	
          echo"<input TYPE=\"hidden\" VALUE=\"$total_dist1\" NAME=\"temp[$i][Total Distance (km)]\">";	
          echo"<input TYPE=\"hidden\" VALUE=\"$journey_time1\" NAME=\"temp[$i][Journey Time (hr.min)]\">";	
          echo"<input TYPE=\"hidden\" VALUE=\"$halt1\" NAME=\"temp[$i][Halt (hr.min)-1 hr interval]\">";	
          echo"<input TYPE=\"hidden\" VALUE=\"$location_track1\" NAME=\"temp[$i][Track]\">";						
					}
				}			                  										
				echo'<br><center><input type="submit" value="Get Report" class="noprint">&nbsp;<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;';
				echo'</form>';
				
				echo'</td></tr></table></div>';										
						
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
