<?php
    //error_reporting(-1);
    //ini_set('display_errors', 'On');
    set_time_limit(3000);	
    date_default_timezone_set("Asia/Kolkata");
    include_once("main_vehicle_information_1.php");
    include_once('Hierarchy.php');
    $root=$_SESSION["root"];
    include_once('util_session_variable.php');
    include_once('util_php_mysql_connectivity.php');
    include_once("calculate_distance.php");
    include_once("report_title.php");
   
    include_once('xmlParameters.php');    
    include_once('parameterizeData.php');
    include_once('data.php');   
    include_once("getXmlData.php");

    include_once("get_location.php");
    include_once("select_landmark_report.php");

    $DEBUG =0;
    $device_str = $_POST['vehicleserial'];
    //echo "<br>devicestr=".$device_str;
    $vserial = explode(':',$device_str);
    $vsize=count($vserial);

    $date1 = $_POST['start_date'];
    $date2 = $_POST['end_date'];
    $date1 = str_replace("/","-",$date1);
    $date2 = str_replace("/","-",$date2);
    $date_1 = explode(" ",$date1);
    $date_2 = explode(" ",$date2);
    $datefrom = $date_1[0];
    $dateto = $date_2[0];

    $user_interval = 60;
	
    $sortBy='h'; 
    $requiredData="All";
    $parameterizeData=new parameterizeData();
    $parameterizeData->latitude="d";
    $parameterizeData->longitude="e";
    $parameterizeData->speed="f";

    $finalVNameArr=array();
    get_All_Dates($datefrom, $dateto, $userdates);    
    $date_size = sizeof($userdates);
    for($i=0;$i<$vsize;$i++)
    {
        $dataCnt=0;
        $vehicle_info=get_vehicle_info($root,$vserial[$i]);
        $vehicle_detail_local=explode(",",$vehicle_info);
       
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;	
	$firstdata_flag =0;
        $logcnt=0;
        $DataComplete=false;                  
        $vehicleserial_tmp=null;
        $format =2;
        $c = -1;

        $j = 0;
        $total_dist_this = 0.0;
        $sum_interval_dist = 0.0;
        
        for($di=0;$di<=($date_size-1);$di++)
        {
            //echo "userdate=".$userdates[$di]."<br>";
            $SortedDataObject=new data();
            readFileXmlNew($vserial[$i],$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
            //var_dump($SortedDataObject);
            if(count($SortedDataObject->deviceDatetime)>0)
            { 
                $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
                for($obi=0;$obi<$prevSortedSize;$obi++)
                {			
                    $datetime=$SortedDataObject->deviceDatetime[$obi]; 
                    $lat = $SortedDataObject->latitudeData[$obi];
                    $lng = $SortedDataObject->longitudeData[$obi];
                    if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
                    {
                        $DataValid = 1;
                    }
                    if($DataValid==1)
                    {           
                        if($firstdata_flag==0)
                        {					
                            $firstdata_flag = 1;  							
                            $lat1 = $lat;
                            $lng1 = $lng;
  							 
                            //echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
                            $interval = (double)$user_interval*60;							

                            $time1 = $datetime;					
                            $date_secs1 = strtotime($time1);					
                            //echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
                            $date_secs1 = (double)($date_secs1 + $interval); 
                            $date_secs2 = 0;
                         }
                         else
                         {
                            $time2 = $datetime;											
                            $date_secs2 = strtotime($time2);										                                      													      					
                            $lat2 = $lat;      				        					
                            $lng2 = $lng;
                            calculate_distance($lat1, $lat2, $lng1, $lng2, $distance);
                            
                            $tmp_time_diff = (strtotime($datetime) - strtotime($last_time)) / 3600;
                            $tmp_speed = $distance / $tmp_time_diff;            
                            $last_time = $datetime;               
            
                            if($tmp_speed<3000 && $distance>0.1)
                            {														
                                $total_dist_this = (float) ($total_dist_this + $distance);
                                $lat1 = $lat2;
                                $lng1 = $lng2;		    						
                            }	
                            if($date_secs2 >= $date_secs1)
                            {
                                if(($interval>=1800) && ($total_dist_this<0.2))
                                {
                                    $total_dist_this = 0.0;
                                } 
                                else
                                {
                                    $total_dist_this = round($total_dist_this,3);
                                }
                                $sum_interval_dist = $sum_interval_dist + $total_dist_this;
                                $time1 = $datetime;
                                $date_secs1 = strtotime($time1);
                                $date_secs1 = (double)($date_secs1 + $interval);  						                  
                                $total_dist_this = 0.0;															
                            }  //if datesec2                                         		        		
                        }  // else closed
                    }
                }
                $overall_dist = $overall_dist + $sum_interval_dist;
                $SortedDataObject=null;	
            }
        }
        
        
        
        $CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;
	$firstdata_flag =0;
        $j = 0;
	$total_dist_this = 0.0;       
        $partial_dist = 0;
        $interval_dist = 0;
        $loc_count = 0;
        $halt_flag = 0;

        $halt_total =0;

        $interval_dist = ( (round($overall_dist,2)) / 4);
        $counter_common = 0;
        $counter_halt = 0;
        $counter_track = 0;

        $lat_ref_track_prev = "";   // TO PREVENT DUPLICATE LOCATION
        $lng_ref_track_prev = "";
        $lat_cr_track_prev ="";
        $lng_cr_track_prev ="";
        
        for($di=0;$di<=($date_size-1);$di++)
        {
            //echo "userdate=".$userdates[$di]."<br>";
            $SortedDataObject=new data();
            readFileXmlNew($vserial[$i],$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
            //var_dump($SortedDataObject);
            
            $logcnt=0;
            $DataComplete=false; 
            $vehicleserial_tmp=null;
            $f=0;
            
            if(count($SortedDataObject->deviceDatetime)>0)
            { 
                $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
                for($obi=0;$obi<$prevSortedSize;$obi++)
                {			
                     	
                    $lat = $SortedDataObject->latitudeData[$obi];
                    $lng = $SortedDataObject->longitudeData[$obi];
                    if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
                    {
                        $DataValid = 1;
                    }
                    if($DataValid==1)
                    { 
                        $datetime=$SortedDataObject->deviceDatetime[$obi];                           
                        $finalSpeedArr[$i][]=$SortedDataObject->speedData[$obi];
                        if($firstdata_flag==0)
                        {
                            //echo "<br>FirstData";
                            $halt_flag = 0;
                            $firstdata_flag = 1;

                            $lat_ref_halt = $lat;
                            $lng_ref_halt = $lng;

                            $lat_ref_track = $lat;
                            $lng_ref_track = $lng;

                            $datetime_ref_halt = $datetime;
                            $datetime_ref_track = $datetime;                
                            //echo "<br>datetime_ref=".$datetime_ref." ,dt0=".$datetime_tmp[0];                	
                            $date_secs1 = strtotime($datetime_ref_halt);
                            $date_secs1 = (double)($date_secs1 + $interval);  

                            $date_start = $datetime_ref_halt;      // GET FIRST DETAIL
                            $lat_start = $lat_ref_halt;
                            $lng_start = $lng_ref_halt;                
                            //echo "<br>lat_tmp=".$lat_tmp[0]." ,lat_tmp1=".$lat_tmp1[1];             	
                        }           	
                        //echo "<br>k2=".$k2."<br>";             	
                        else
                        {           
                            //echo "<br>Next";               
                            $lat_cr_halt = $lat;  
                            $lng_cr_halt = $lng;

                            $lat_cr_track = $lat;  
                            $lng_cr_track = $lng;                  
    
                            $datetime_cr_halt = $datetime;              		               											
                            $date_secs2 = strtotime($datetime_cr_halt);
                            $date_end = $datetime_cr_halt;         // GET LAST DETAIL
                	
                            $lat_end = $lat_cr_halt;
                            $lng_end = $lng_cr_halt;
                            $datetime_cr_track = $datetime;             		
                            //echo "<br>str=".$lat_ref_halt.", ".$lng_ref_halt.", ".$lat_cr_halt." ,".$lng_cr_halt;
                            calculate_distance($lat_ref_halt, $lat_cr_halt, $lng_ref_halt, $lng_cr_halt, $distance);
              		             		
                            $tmp_time_diff = (strtotime($datetime) - strtotime($last_time)) / 3600;
                            $tmp_speed = $distance / $tmp_time_diff;
                            $last_time = $datetime;                
                  
                            if($tmp_speed<3000 && $distance>0.1)
                            {														
                                $total_dist_this = (float) ($total_dist_this + $distance);
                                //echo "<br>IN TOTAL DIST=".$total_dist_this;                                      
                                $partial_dist = $partial_dist +  $distance;
                            }
                            if( ($distance > 0.200) || ($f== $total_lines-2) )
                            {
                                if ($halt_flag == 1)
                                {
                                    $arrivale_time=$datetime_ref_halt;
                                    $starttime = strtotime($datetime_ref_halt); 
                                    $stoptime = strtotime($datetime_cr_halt);
                                    $depature_time=$datetime_cr_halt;
                                    $halt_dur =  ($stoptime - $starttime)/3600;              				
                                    $halt_duration = round($halt_dur,2);										
                                    $total_min = $halt_duration * 60;            
                                    $total_min1 = $total_min;          
                                    $hr = (int)($total_min / 60);
                                    $minutes = $total_min % 60;	
                                    $hrs_min = $hr.".".$minutes;
                                    if(($total_min1 >= $user_interval))                       
                                    {
                                        $halt_detail = $arrivale_time." to ".$depature_time." -> ".$hrs_min;
                                        //echo "<br>".$halt_detail;
                                        $halt_total = $halt_total + ($stoptime - $starttime);
                                        /*if($counter==0)
                                        {
                                            $halt_str_this = $halt_str_this.$halt_detail;
                                        }*/
                                        if($counter_common>0)
                                        {
                                            if($counter_halt==0)
                                            {                            
                                                $halt_str_this = $halt_str_this.$halt_detail;
                                                $counter_halt = 1;
                                            }
                                            else
                                            {
                                                $halt_str_this = $halt_str_this."#".$halt_detail;
                                            }
                                        } 
                                        $date_secs1 = strtotime($datetime_cr_halt);
                    			$date_secs1 = (double)($date_secs1 + $interval);                   
                                        //echo "<br>halt=".$halt_str_this;				          						 
                                    }												
              			} // IF HALT FLAG              			
                                $lat_ref_halt = $lat_cr_halt;
                                $lng_ref_halt = $lng_cr_halt;
                                $datetime_ref_halt = $datetime_cr_halt;
                                $halt_flag = 0;              			
                            } // if dist > 0.200            		
                            else
                            {
                                $halt_flag = 1;
                            } 
                            if(($partial_dist >= $interval_dist) && ($loc_count<6))
                            {                													
                                //echo "<br>in Track";                      
                                $track_flag =1;                      
                                if( (round($lat_ref_track,3) == round($lat_ref_track_prev,3)) && (round($lng_ref_track,3) == round($lng_ref_track_prev,3)) && (round($lat_cr_track,3) == round($lat_cr_track_prev,3)) && (round($lng_cr_track,3) == round($lng_cr_track_prev,3)) )
                                {
                                    $track_flag = 0;                       
                                }                      
                                if($track_flag)
                                {
                                    $track_detail = $lat_ref_track.",".$lng_ref_track." ".$lat_cr_track.",".$lng_cr_track;
                        
                                    /*if($counter==0)
                                    {
                                      $track_str_this = $track_str_this.$track_detail;
                                    }*/
                                    if($counter_common>0)
                                    {
                                        if($counter_track==0)
                                        {
                                            $track_str_this = $track_str_this.$track_detail;
                                            $counter_track = 1;
                                        }
                                        else
                                        {
                                            $track_str_this = $track_str_this."#".$track_detail;
                                        }
                                    }
                                }           
                                $lat_ref_track_prev = $lat_ref_track;
                                $lng_ref_track_prev = $lng_ref_track;
                                $lat_cr_track_prev = $lat_cr_track;
                                $lng_cr_track_prev = $lng_cr_track;  
                                $partial_dist = 0;
                                $loc_count++;				          						 			                                                       			
                            }
                            $counter_common++;
                        }	
                    }
                }
                $SortedDataObject=null; 
                $time1 = strtotime($date_start);
                $time2 = strtotime($date_end);
                $diff_total = $time2 - $time1;

                $diff_time =  ($diff_total)/3600;
                $hr = (int)($diff_time / 60);
                $minutes = $diff_time % 60;										   

                $halt_time =  ($halt_total)/3600;
                $hr = (int)($halt_time / 60);
                $minutes = $halt_time % 60;									  

                $journey_diff = ($diff_total - $halt_total);
                $journey_time_this =  ($journey_diff)/3600;
                $journey_time_this = round($journey_time_this,2);										
                $total_min = $journey_time_this * 60;     
                $hr = (int)($total_min / 60);
                $minutes = $total_min % 60;										  
                $hrs_min = $hr.".".$minutes;        // journey time
                $overall_dist = round($overall_dist,2);

                if($halt_str_this == "")
                {
                    $halt_str_this = "No Halt Found";
                }

                if($track_str_this == "")
                {
                    $track_str_this = $lat_ref_track.",".$lng_ref_track." ".$lat_cr_track.",".$lng_cr_track;
                }
                $imei[]=$vserial[$i];
                $vname[]=$vehicle_detail_local[0];
                $startdate[]=$date_start;
                $enddate[]=$date_end;
                $startlat[]=$lat_start;
                $startlng[]=$lng_start;
                $endlat[]=$lat_end;
                $endlng[]=$lng_end;
                $total_dist[]=$overall_dist;
                $journey_time[]=$hrs_min;
                $halt_str[]=$halt_str_this;
                $track_str[]=$track_str_this; 
            }
        }		
    }
    
    $parameterizeData=null;	
    $o_cassandra->close();	
	
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
		


<?php
include("MapWindow/floating_map_window.php");

report_title("Summary Report",$date1,$date2);
					
/*echo '<div style="overflow:auto;height:420px;width:98%">
<table border=1 width="98%" bordercolor="#e5ecf5" align="center" cellspacing="0" cellpadding="0">';

echo '<tr valign="top">';
echo '<td class="text" align="left"><strong>Vehiclename</strong></td>';
echo '<td class="text" align="left"><strong>IMEI</strong></td>';
echo '<td class="text" align="left" width="70px"><strong>StartDate</strong></td>';
echo '<td class="text" align="left" width="70px"><strong>EndDate</strong></td>';
echo '<td class="text" align="left" width="90px"><strong>Start Location</strong></td>';
echo '<td class="text" align="left" width="90px"><strong>End Location</strong></td>';    
echo '<td class="text" align="left" width="50px"><strong>Total Distance (km)</strong></td>';
echo '<td class="text" align="left" width="50px"><strong>Journey Time (hr.min)</strong></td>';
echo '<td class="text" align="left" width="318px"><strong>Halt (hr.min)-1 hr interval</strong></td>';  
echo '<td class="text" align="left"><strong>Track</strong></td>';  
echo '</tr>';*/

echo '<div style="overflow:auto;height:420px;width:100%;">
<!--<table border=1 style="width:2000px;" bordercolor="#e5ecf5" align="center" cellspacing="0" cellpadding="0">-->
<center><table cellspacing=0 cellpadding=0 class="table-bordered table-hover" style="width:95%" >';
echo '<thead class="alert-warning"><tr valign="top">';
echo '<th class="text" align="left" width="100px"><strong>Vehiclename</strong></th>';
echo '<th class="text" align="left" width="100px"><strong>IMEI</strong></th>';
echo '<th class="text" align="left" width="120px"><strong>StartDate</strong></th>';
echo '<th class="text" align="left" width="120px"><strong>EndDate</strong></th>';
echo '<th class="text" align="left" width="300px"><strong>Start Location</strong></th>';
echo '<th class="text" align="left" width="300px"><strong>End Location</strong></th>';
echo '<th class="text" align="left" width="50px"><strong>Total Distance (km)</strong></th>';
echo '<th class="text" align="left" width="50px"><strong>Journey Time (hr.min)</strong></th>';
echo '<th class="text" align="left" width="310px"><strong>Halt (hr.min)-1 hr interval</strong></th>';
echo '<th class="text" align="left"><strong>Track</strong></th>';
echo '</tr></thead>';

    
for($i=0;$i<sizeof($imei);$i++)
{								                                        
		$lt1 = $startlat[$i];
		$lng1 = $startlng[$i];
		$alt1 = "-";								
		 
    /*if($access=='Zone')
		{
			get_location($lt1,$lng1,$alt1,&$place,$zoneid,$DbConnection);
		}
		else
		{
			get_location($lt1,$lng1,$alt1,&$place,$DbConnection);
		} 

		$start_loc[$i] = $place;*/
    
    $landmark="";
    get_landmark($lt1,$lng1,$landmark);    // CALL LANDMARK FUNCTION
		
    $place = $landmark;
    
    if($place=="")
    {
      get_location($lt1,$lng1,$alt1,$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
    }
		
    //echo "P:".$place;    
    $start_loc[$i] = $place;	    	
              
		$lt1 = $endlat[$i];
		$lng1 = $endlng[$i];
		$alt1 = "-";								
		 
    /*if($access=='Zone')
		{
			get_location($lt1,$lng1,$alt1,&$place,$zoneid,$DbConnection);
		}
		else
		{
			get_location($lt1,$lng1,$alt1,&$place,$DbConnection);
		} 

		$end_loc[$i] = $place;*/
    
    $landmark="";
    get_landmark($lt1,$lng1,$landmark);    // CALL LANDMARK FUNCTION
		
    $place = $landmark;
    
    if($place=="")
    {
      get_location($lt1,$lng1,$alt1,$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
    }
		
    //echo "P:".$place;    
    $end_loc[$i] = $place;
        	          
    
    $halt_detail_pdf[$i] = $halt_str[$i]; 
    $halt_detail[$i] = str_replace("to","<font color=green>to</font>",$halt_str[$i]);
    $halt_detail[$i] = str_replace("->","<font color=red>-></font>",$halt_detail[$i]);
    $halt_detail[$i] = str_replace("#","<br>",$halt_detail[$i]);
    
    /*if( ($_SERVER['HTTP_X_FORWARDED_FOR']) == "172.26.48.189")
    {
      echo "<br>Track:".$track_str[$i];
    }*/    
    
    $track1 = explode("#",$track_str[$i]);
    
    $track_str_tmp ="";
    
    for($j=0;$j<sizeof($track1);$j++)
    {
      //echo "<br>".$track1[$j];
      $track2 = explode(" ",$track1[$j]);
      //echo "<br>size track2:".sizeof($track2);
      
      for($k=0;$k<sizeof($track2);$k++)
      {
        $track3 = explode(",",$track2[$k]);
        
        $lt1 = $track3[0];
        $lng1 = $track3[1];
        $alt1="-";
        
        /*if($access=='Zone')
    		{
    			get_location($lt1,$lng1,$alt1,&$place,$zoneid,$DbConnection);
    		}
    		else
    		{
    			get_location($lt1,$lng1,$alt1,&$place,$DbConnection);
    		} */
    		
        $landmark="";
        get_landmark($lt1,$lng1,$landmark);    // CALL LANDMARK FUNCTION
    		
        $place = $landmark;
        
        //echo "lt1=".$lt1." ,lng1=".$lng1." pl=".$place2;
        
        /*if( ($_SERVER['HTTP_X_FORWARDED_FOR']) == "172.26.48.189")
        {
          echo "<br>lat:".$lt1.",lng:".$lng1;
        }*/
                                        
        if($place=="")
        {
          get_location($lt1,$lng1,$alt1,$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
        }  		
        
        $place2 = $place;
        
        //echo "lt1=".$lt1." ,lng1=".$lng1." pl=".$place2;        
        if($k==(sizeof($track1)-1))
        {
          //echo "<br>end";
          $track_str_tmp = $track_str_tmp.$place2;
        }
        else
        {
          //echo "<br>else";
          $track_str_tmp = $track_str_tmp.$place2."->";
        }                
        //echo "<br>".$track_str_tmp;                                       
      }
    }
                          
    $track_str_tmp = substr($track_str_tmp, 0, strlen($track_str_tmp)-2); 

    //echo "<br>".$track_str_tmp;
    $location_track_pdf[$i] = $track_str_tmp;
    $location_track[$i] = str_replace("->","<font color=red>-></font>",$track_str_tmp);	
    	
    $sno = $i+1;            
    echo '<tr valign="top">';
    echo '<td class="text" align="left">'.$vname[$i].'</td>';
    echo '<td class="text" align="left">'.$imei[$i].'</td>';
    echo '<td class="text" align="left">'.$startdate[$i].'</td>';
    echo '<td class="text" align="left">'.$enddate[$i].'</td>';
    echo '<td class="text" align="left">'.$start_loc[$i].'</td>';
    echo '<td class="text" align="left">'.$end_loc[$i].'</td>';
    echo '<td class="text" align="left">'.$total_dist[$i].'</td>';
    echo '<td class="text" align="left">'.$journey_time[$i].'</td>';
    echo '<td class="text" align="left">'.$halt_detail[$i].'</td>';  
    echo '<td class="text" align="left">'.$location_track[$i].'</td>';                                                            		
		echo'</tr>';	          		
 }
 
 echo "</table></center></div>";     

 $csv_string = ""; 
 
 echo'<form name="sumForm" method = "post" target="_blank">';
	
  $title= "Summary Report From DateTime : ".$date1."-".$date2;
  $csv_string = $csv_string.$title."\n";
  $csv_string = $csv_string."SNo,Vehiclename,IMEI,StartDate,EndDate,Start Location,End Location,Total Distance (km),Journey Time (hr.min),Halt (hr.min)1 hr interval,Track\n";
  echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";

  for($i=0;$i<sizeof($imei);$i++)
  {								
    $sno = $i+1;							            
    echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$i][SNo]\">";
    echo"<input TYPE=\"hidden\" VALUE=\"$vname[$i]\" NAME=\"temp[$i][Vehiclename]\">";
    echo"<input TYPE=\"hidden\" VALUE=\"$imei[$i]\" NAME=\"temp[$i][IMEI]\">";
    echo"<input TYPE=\"hidden\" VALUE=\"$startdate[$i]\" NAME=\"temp[$i][StartDate]\">";
    echo"<input TYPE=\"hidden\" VALUE=\"$enddate[$i]\" NAME=\"temp[$i][EndDate]\">";
    echo"<input TYPE=\"hidden\" VALUE=\"$start_loc[$i]\" NAME=\"temp[$i][Start Location]\">";
   echo"<input TYPE=\"hidden\" VALUE=\"$end_loc[$i]\" NAME=\"temp[$i][End Location]\">";	
   echo"<input TYPE=\"hidden\" VALUE=\"$total_dist[$i]\" NAME=\"temp[$i][Total Distance (km)]\">";	
   echo"<input TYPE=\"hidden\" VALUE=\"$journey_time[$i]\" NAME=\"temp[$i][Journey Time (hr.min)]\">";	
   echo"<input TYPE=\"hidden\" VALUE=\"$halt_detail_pdf[$i]\" NAME=\"temp[$i][Halt (hr.min)-1 hr interval]\">";	
    echo"<input TYPE=\"hidden\" VALUE=\"$location_track_pdf[$i]\" NAME=\"temp[$i][Track]\">";
    
    $start_loc[$i] = str_replace(',',':',$start_loc[$i]);
    $end_loc[$i] = str_replace(',',':',$end_loc[$i]);
    $location_track_pdf[$i] = str_replace(',',':',$location_track_pdf[$i]);
    
    $csv_string = $csv_string.$sno.','.$vname[$i].','.$imei[$i].','.$startdate[$i].','.$enddate[$i].','.$start_loc[$i].','.$end_loc[$i].','.$total_dist[$i].','.$journey_time[$i].','.$halt_detail_pdf[$i].','.$location_track_pdf[$i]."\n";      						  																																					
  }																						

  echo'	
    <table align="center">
		<tr>
			<td>';
      
  		if(sizeof($imei)==0)
  		{						
  			print"<center><FONT color=\"Red\" size=2><strong>No Summary Record found</strong></font></center>";
  			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
  			echo'<br><br>';
  		}	
  		else
  		{
        echo'<input TYPE="hidden" VALUE="summary report" NAME="csv_type">';
        echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
        echo'<br><center><input type="button" class="btn btn-default btn-sm" onclick="javascript:report_csv(\'src/php/report_getpdf_type3_summary.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" class="btn btn-default btn-sm" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
        <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';      
      }
                  
      echo'</td>		
    </tr>
		</table>
		</form>
 ';             
          
echo '</center>';
	echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';                               					
?>						
				
