<?php
	include_once('util_session_variable.php');		
	include_once('util_php_mysql_connectivity.php');
	
  include_once("get_all_dates_between.php");
  include_once("sort_xml.php");
  include_once("calculate_distance.php");
	//echo "account_id_local=".$account_id_local."startdate=".$start_date;
	//$account_id = "696";
	//######### MAKE MASTER REPORT ###################/
	//GPS Installed	-	total devices 
  $query = "SELECT vehicle_assignment.device_imei_no FROM vehicle_assignment,vehicle_grouping WHERE ".
            "vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND vehicle_grouping.account_id='$account_id' AND vehicle_grouping.status=1 ".
            "AND vehicle_assignment.status=1";
  $result = mysql_query($query, $DbConnection);
  
  $gps_installed =0;
  while($row1 = mysql_fetch_object($result))
  {
    $gps_installed+= 1;
    $imei[] = $row1->device_imei_no;
  }  
  //echo "<br>gps_installed=".$gps_installed;
  //######### INITIALISE ALL VARIABLES
  $current_datetime = date("Y-m-d H:i:s");

  $reporting_vehicles = 0;
  $non_reporting = 0;
  $network_issue = 0;
  $device_issue = 0;
  
  $total_near_plants =0;
  $near_plants = array();
  $near_plants_id = array();
  $live_total_consignment = 0;
  $live_ontime_consignment = 0;
  $live_delayed_consignment = 0;
  $live_suspect_consignment = 0;
  
  $delivered_total_consignment = 0;
  $autopod_total_consignment = 0;
  $autopod_ontime_closed_trip = 0;
  $autopd_delayed_closed_trip =0;
  
  $cm_total_consignment = 0;  
  $cm_delayed_closed_trip = 0;
  $cm_delivery_performance = 0;
   
  $total_consignment_cm = 0;                      //cm= current_moth
  $ontime_trip_cm = 0;
  $delay_trip_cm = 0;
  $delivery_performance_cm = "0%";
  $avg_running_hr_trip_all_vehicles = 0;
  $avg_distance_trip_all_vehicles = 0;
  $avg_distance_all_vehicles = 0;
  $consignment_near_consignment_location = 0;
  
  
  $current_date = date("Y-m-d");
  $previous_date = date('Y-m-d', strtotime($date .' -1 day'));
  //echo "<br>st=".$start_date;
  $start_date = str_replace("/","-",$start_date);
  $previous_date = $start_date;

	for($i=0;$i<sizeof($imei);$i++)
	{  	    
		vehicle_status($account_id, $imei[$i], $previous_date);
	}
	
	$non_reporting = $network_issue + $device_issue;
  //### HERE GET TOTAL CONSIGNMENT
  /*$query = "SELECT vehicle_assignment.device_imei_no FROM vehicle_assignment,vehicle_grouping WHERE ".
            "vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND vehicle_grouping.account_id='$account_id' AND vehicle_grouping.status=1 ".
            "AND vehicle_assignment.status=1";
  $result = mysql_query($query, $DbConnection); */
	
	//for($i=0;$i<sizeof($consigment);$i++)
	//{

    $query_station = "SELECT DISTINCT pickup_point_id, delivery_point_id FROM consignment WHERE status=1";
    $result_station = mysql_query($query_station, $DbConnection);
    while($row_station = mysql_fetch_object($result_station))
    {
      $pickup_point_id = $row_station->pickup_point_id;
      $delivery_point_id = $row_station->delivery_point_id;
      $total_station_id[] = $pickup_point_id;
      $total_station_id[] = $delivery_point_id;  
    }

  	consignment($account_id, $previous_date);

		summary_report($account_id, $previous_date);
	//}    
  
  //1.########### vehicle_status
  function vehicle_status($account_id, $imei1, $previous_date)
  {
  	$reporting_vehicles_flag = false;
    $network_issue_flag = true; 
    $device_issue_flag = false; 
      	
  	global $reporting_vehicles;    
    global $network_issue;
    global $device_issue;
  	global $near_plants;
  	global $near_plants_id;
  	global $total_near_plants;
   									        
    $xml_reporting = "daily_vehicle_status/".$account_id."/".$previous_date."/vehicle_status.xml";	
    //echo "<br>xml_path=".$xml_reporting;
	
	  $data_valid = false;
      		
    if (file_exists($xml_reporting))      
    {		      
        $total_lines = count(file($xml_reporting));
        //echo "<br>Total lines orig=".$total_lines;
        
        $xml = @fopen($xml_reporting, "r") or $fexist = 0;  
          
        while(!feof($xml))          // WHILE LINE != NULL
        {
        		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
        		$data_valid = false;
				
        		if(strlen($line)>20)
        		{
        			$data_valid =  true;
            }
        				            				    				
            if($data_valid)
            {
                $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);
         				if($status==0)
        				{
        					continue;
        				}
      					
                $imei_tmp1 = explode("=",$imei_tmp[0]);
      					$imei2 = preg_replace('/"/', '', $imei_tmp1[1]);
                        			
      			    if($imei1 == $imei2)
                {
                  $network_issue_flag = false;
                  
                  $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
                  if($status)
                  {
                   $vname_tmp1 = explode("=",$vname_tmp[0]);
                   $vname = preg_replace('/"/', '', $vname_tmp1[1]);
                  }             
                  
                  $status = preg_match('/station_id="[^"]+/', $line, $station_id_tmp);
                  if($status)
                  {
                   $station_id_tmp1 = explode("=",$station_id_tmp[0]);
                   $station_id1 = preg_replace('/"/', '', $station_id_tmp1[1]);
                  }

                  $status = preg_match('/near_plant="[^"]+/', $line, $near_plant_tmp);
                  if($status)
                  {
                   $near_plant_tmp1 = explode("=",$near_plant_tmp[0]);
                   $near_plant1 = preg_replace('/"/', '', $near_plant_tmp1[1]);
                  }
                  
                  $status = preg_match('/last_time="[^"]+/', $line, $last_time_tmp);
                  if($status)
                  {
                   $last_time_tmp1 = explode("=",$last_time_tmp[0]);
                   $last_time = preg_replace('/"/', '', $last_time_tmp1[1]);
                  }
                  
                  $status = preg_match('/lat="[^"]+/', $line, $lat_tmp);
                  if($status)
                  {
                   $lat_tmp1 = explode("=",$lat_tmp[0]);
                   $lat = preg_replace('/"/', '', $lat_tmp1[1]);
                  }
                                
                  $status = preg_match('/lng="[^"]+/', $line, $lng_tmp);
                  if($status)
                  {
                   $lng_tmp1 = explode("=",$lng_tmp[0]);
                   $lng = preg_replace('/"/', '', $lng_tmp1[1]);
                  }
      					
                  //echo "<br>imei2=".$imei2." ,vname=".$vname." ,near_plant1=".$near_plant1." ,last_time=".$last_time." ,lat=".$lat." ,lng=".$lng;
				  
				          if($lat!="" && $lng!="")
                  {                
                    $reporting_vehicles_flag = true;
                    $network_issue_flag = false; 
                    $device_issue_flag = false;

          					if($station_id1!="" && $near_plant1!="")
          					{
          						$near_plants_id[] = $station_id1;
                      $near_plants[] = $near_plant1; 
                      //echo "<br>near_plant1=".$near_plant1." ,station_id1=".$station_id;         						
          					}
                    break;
                  }
        				  else
        				  {
          					$device_issue_flag = true;
          					break;
        				  }
                }  //if imei
             } //if data_valid closed		        			  		
          }   // while closed            	
      	    	      				
      fclose($xml);            
		 //unlink($xml_original_tmp);
		} // if (file_exists closed
  	  	
  	if($reporting_vehicles_flag)
  	{
      $reporting_vehicles += 1;
      //echo "<br>NearPlantAfter=".$near_plant1;
      
      if($near_plant1!="")
		    $total_near_plants+=1 ;		    
    }      
  	else if($network_issue_flag)
      $network_issue += 1;
      
  	else if($device_issue_flag)
      $device_issue += 1;  	
   } 
  
  //2.########### consignment
  function consignment($account_id, $previous_date)
  {
    global $live_total_consignment;
    global $live_ontime_consignment;
    global $live_delayed_consignment;
    global $live_suspect_consignment;
    
    global $delivered_total_consignment;
	
    global $autopod_total_consignment;
    global $autopod_ontime_closed_trip;
    global $autopd_delayed_closed_trip;
   	$data_valid = false;								        
    $xml_cons = "daily_vehicle_status/".$account_id."/".$previous_date."/consignment.xml";	
    //echo "xml2=".$xml_cons;
	
    if (file_exists($xml_cons))      
    {		      
        $xml = @fopen($xml_cons, "r") or $fexist = 0;  
          
        while(!feof($xml))          // WHILE LINE != NULL
        {
        		$data_valid = false;
				
				$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
        				
        		if(strlen($line)>20)
        		{
        			$data_valid =  true;
            }
        				            				    				
            if($data_valid)
            {
              /*$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
      				if($status==0)
      				{
      					continue;
      				}        			 
              
              $vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
    					$imei2 = preg_replace('/"/', '', $vehicleserial_tmp1[1]);
   					  */
    					//if($cs1 == $cs2)
    					//{
                  $status = preg_match('/consignment_id="[^"]+/', $line, $consignment_id_tmp);
          				if($status)
          				{       					
                    $consignment_tmp1 = explode("=",$consignment_tmp[0]);
          					$consignment_id = preg_replace('/"/', '', $consignment_tmp1[1]);
          				}
          				
                  $status = preg_match('/live_ontime="[^"]+/', $line, $live_ontime_tmp);
          				if($status)
          				{       					
                    $live_ontime_tmp1 = explode("=",$live_ontime_tmp[0]);
          					$live_ontime = preg_replace('/"/', '', $live_ontime_tmp1[1]);
          				}
                  $status = preg_match('/live_delayed="[^"]+/', $line, $live_delayed_tmp);
          				if($status)
          				{       					
                    $live_delayed_tmp1 = explode("=",$live_delayed_tmp[0]);
          					$live_delayed = preg_replace('/"/', '', $live_delayed_tmp1[1]);
          				}
                  $status = preg_match('/live_suspect="[^"]+/', $line, $live_suspect_tmp);
          				if($status)
          				{       					
                    $live_suspect_tmp1 = explode("=",$live_suspect_tmp[0]);
          					$live_suspect = preg_replace('/"/', '', $live_suspect_tmp1[1]);
          				}
                  $status = preg_match('/normal_delivered="[^"]+/', $line, $normal_delivered_tmp);
          				if($status)
          				{       					
                    $normal_delivered_tmp1 = explode("=",$normal_delivered_tmp[0]);
          					$normal_delivered = preg_replace('/"/', '', $normal_delivered_tmp1[1]);
          				}
                   $status = preg_match('/autopod_ontime_delivered="[^"]+/', $line, $autopod_ontime_delivered_tmp);
          				if($status)
          				{       					
                    $autopod_ontime_delivered_tmp1 = explode("=",$autopod_ontime_delivered_tmp[0]);
          					$autopod_ontime_delivered = preg_replace('/"/', '', $autopod_ontime_delivered_tmp1[1]);
          				}
                  $status = preg_match('/autopod_delay_delivered="[^"]+/', $line, $autopod_delay_delivered_tmp);
          				if($status)
          				{       					
                    $autopod_delay_delivered_tmp1 = explode("=",$autopod_delay_delivered_tmp[0]);
          					$autopod_delay_delivered = preg_replace('/"/', '', $autopod_delay_delivered_tmp1[1]);
          				}      				
  
                  if($live_ontime) 
                    $live_ontime_consignment+= 1;
                  
                  if($live_delayed)
                    $live_delayed_consignment+= 1;
                  
                  if($live_suspect)
                    $live_suspect_consignment+= 1;
                  
                  if($normal_delivered)
          					$delivered_total_consignment+= 1;
				  
				          if($autopod_ontime_delivered)
                    $autopod_ontime_closed_trip+= 1;
                    
                  if($autopod_delay_delivered)
                    $autopd_delayed_closed_trip+= 1;  					  
            } //if data_valid                    				        			  		
        }   // while closed    	
      	    	      				
      fclose($xml);            
		} // if (file_exists closed
  	  	  	
    $live_total_consignment = $live_ontime_consignment + $live_delayed_consignment;
    $autopod_total_consignment = $autopod_ontime_closed_trip + $autopd_delayed_closed_trip;
       
  }  
  
  //3.########### summary_report
  function summary_report($account_id, $previous_date)
  {
    global $total_consignment_cm;                      //cm= current_moth
    global $ontime_trip_cm;
    global $delay_trip_cm;
    global $delivery_performance_cm;
    global $avg_running_hr_trip_all_vehicles;
    global $avg_distance_trip_all_vehicles;
    global $avg_distance_all_vehicles;
    global $consignment_near_consignment_location;
 	  									        
    $xml_summary = "daily_vehicle_status/".$account_id."/".$previous_date."/summary_report.xml";	
    //echo "<br>xml_summary=".$xml_summary;
    
    $data_valid = false;
      		
    if (file_exists($xml_summary))      
    {		              
        //echo "Fileexist";
        $xml = @fopen($xml_summary, "r") or $fexist = 0;  
          
        while(!feof($xml))          // WHILE LINE != NULL
        {
         		 //echo "<br>line";
             $data_valid = false;
				
				    $line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
        				
        		if(strlen($line)>20)
        		{
        			$data_valid =  true;
            }
        				            				    				
            if($data_valid)
            {       				            				
                /*$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
    
        				if($status==0)
        				{
        					continue;
        				} */       			
      					
      					//if($imei1 == $imei2)
      					//{
                    
                  $status1 = preg_match('/total_consignment_cm="[^" ]+/', $line, $total_consignment_cm_tmp);
          				if($status1)
          				{       					
                    $total_consignment_cm_tmp1 = explode("=",$total_consignment_cm_tmp[0]);
          					$total_consignment_cm = preg_replace('/"/', '', $total_consignment_cm_tmp1[1]);
          				}
          				
          				//echo "<br>total_consignment_cm=".$total_consignment_cm;
          				
                  $status2 = preg_match('/ontime_trip_cm="[^" ]+/', $line, $ontime_trip_cm_tmp);
          				if($status2)
          				{       					
                    $ontime_trip_cm_tmp1 = explode("=",$ontime_trip_cm_tmp[0]);
          					$ontime_trip_cm = preg_replace('/"/', '', $ontime_trip_cm_tmp1[1]);
          				}
                  //echo "<br>ontime_trip_cm=".$ontime_trip_cm;
                  
                  $status3 = preg_match('/delay_trip_cm="[^" ]+/', $line, $delay_trip_cm_id_tmp);
          				if($status3)
          				{       					
                    $delay_trip_cm_tmp1 = explode("=",$delay_trip_cm_tmp[0]);
          					$delay_trip_cm = preg_replace('/"/', '', $delay_trip_cm_tmp1[1]);
          				}
                  //echo "<br>delay_trip_cm=".$delay_trip_cm;
                  
                  $status4 = preg_match('/delivery_performance_cm="[^" ]+/', $line, $delivery_performance_cm_tmp);
          				if($status4)
          				{       					
                    $delivery_performance_cm_tmp1 = explode("=",$delivery_performance_cm_tmp[0]);
          					$delivery_performance_cm = preg_replace('/"/', '', $delivery_performance_cm_tmp1[1]);
          				}
          				//echo "<br>delivery_performance_cm=".$delivery_performance_cm;
          				
                  $status5 = preg_match('/avg_running_hr_trip_all_vehicles="[^" ]+/', $line, $avg_running_hr_trip_all_vehicles_tmp);
          				if($status5)
          				{       					
                    $avg_running_hr_trip_all_vehicles_tmp1 = explode("=",$avg_running_hr_trip_all_vehicles_tmp[0]);
          					$avg_running_hr_trip_all_vehicles = preg_replace('/"/', '', $avg_running_hr_trip_all_vehicles_tmp1[1]);
          				}
          				//echo "<br>avg_running_hr_trip_all_vehicles=".$avg_running_hr_trip_all_vehicles;
          				
                  $status6 = preg_match('/avg_distance_trip_all_vehicles="[^" ]+/', $line, $avg_distance_trip_all_vehicles_tmp);
          				if($status6)
          				{       					
                    $avg_distance_trip_all_vehicles_tmp1 = explode("=",$avg_distance_trip_all_vehicles_tmp[0]);
          					$avg_distance_trip_all_vehicles = preg_replace('/"/', '', $avg_distance_trip_all_vehicles_tmp1[1]);
          				}
          				//echo "<br>avg_distance_trip_all_vehicles=".$avg_distance_trip_all_vehicles;
          				
                  $status7 = preg_match('/avg_distance_all_vehicles="[^" ]+/', $line, $avg_distance_all_vehicles_tmp);
          				if($status7)
          				{       					
                    $avg_distance_all_vehicles_tmp1 = explode("=",$avg_distance_all_vehicles_tmp[0]);
          					$avg_distance_all_vehicles = preg_replace('/"/', '', $avg_distance_all_vehicles_tmp1[1]);
          				}
          				//echo "<br>avg_distance_all_vehicles=".$avg_distance_all_vehicles;
          				
                  $status8 = preg_match('/consignment_near_consignment_location="[^" ]+/', $line, $consignment_near_consignment_location_tmp);
          				if($status8)
          				{       					
                    $consignment_near_consignment_location_tmp1 = explode("=",$consignment_near_consignment_location_tmp[0]);
          					$consignment_near_consignment_location = preg_replace('/"/', '', $consignment_near_consignment_location_tmp1[1]);
          				}
          				//echo "<br>consignment_near_consignment_location=".$consignment_near_consignment_location;
						
						      //echo "<br>Summary";
                 // break;                    					  
                //}
             } //if data_valid closed                    				        			  		
          }   // while closed 	      	    	      				
        fclose($xml);            
  	} // if (file_exists closed
  	  	       
  }       
  //Network Issue	-	No data
  //Device Issue	-	No GPS
  //Total Carriers	-	Vehicles entered in plants
  //Consigment	-
 
?>
<input type="hidden" id="common_display_id">
<table width="100%">
	<tr>
		<td align="center">
			<table align="center" width="80%" class="menu" border="1" rules="all" bordercolor="black">
				<tr class="moto_headings">
					<td align="center">
						CMD Cumulative Report
					</td>
				</tr>
				<tr>
					<td>
						<table cellpadding=0 cellspacing=0 rules="all" border="1" width="100%">
							<tr>
								<td align="center" width="93%">
									 Gps Installed
								</td>
								<td align="right" width="7%">
									 <?php echo $gps_installed; ?>
								</td>
							</tr>
						</table>						
					</td>
				</tr>
				<tr>
					<td align="center">
						<table cellpadding=0 cellspacing=0 rules="all" border="1" width="100%">
							<tr>
								<td align="center" width="93%">
									 Reporting
								</td>
								<td align="right" width="7%">
									<?php echo $reporting_vehicles; ?>
								</td>
							</tr>
						</table>						
					</td>
				</tr>
				<tr onclick="javascript:report_show_hide_moto('non_reporting');">
					<td>
						<table cellpadding=0 cellspacing=0 border="1" bordercolor="black" width="100%" >
							<tr class="moto_exceptions">
								<td align="center" width="93%">
									 Non Reporting
								</td>
								<td align="right" width="7%">
								  <?php echo $non_reporting ?>								  
								</td>
							</tr>
						</table>
					</td>
	           <tr>
				<tr id='non_reporting'>
					<td>
						<table cellpadding=0 cellspacing=0 border="1" bordercolor="black" width="100%" >
							<tr>
								<td align="center" width="93%">
									 Network Issue
								</td>
								<td align="right" width="7%">
									<?php echo $network_issue; ?>
								</td>
							</tr>
							<tr>
								<td align="center" width="93%">
									 Device Issue
								</td>
								<td align="right" width="7%">
									<?php echo $device_issue; ?>
								</td>
							</tr>
							<tr>
								<td align="center" width="93%">
									Device working on Internal Battery (Device Tampered/Suspect Vehicle Under Maintenance)
								</td>
								<td align="right" width="7%">
									0
								</td>
							</tr>
						</table>
					</td>
	           <tr>	
				<tr onclick="javascript:report_show_hide_moto('heading_towards_plants');">
					<td>
						<table cellpadding=0 cellspacing=0 border="1" bordercolor="black" width="100%" >
							<tr class="moto_exceptions">
								<td align="center" width="93%">
									 Carrier Heading Towards Plants
								</td>
								<td align="right" width="7%">
								  <?php echo $total_near_plants ?>								  
								</td>
							</tr>
						</table>
					</td>
	           <tr>
				<tr id='heading_towards_plants'>
					<td>
						<table cellpadding=0 cellspacing=0 border="1" bordercolor="black" width="100%" >

              <tr>
                <td align="center" width="93%">
                  <strong>Carriers Available Near
                </td>
                <td align="right" width="7%">             
                </td>
              </tr>
    							
              <?php							              
              for($j=0;$j<sizeof($total_station_id);$j++)
              {
                $flag_found = false;
                $total_vehicles = 0;
                
                for($k=0;$k<sizeof($near_plants_id);$k++)
                {
                  if($total_station_id[$j]==$near_plants_id[$k])
                  {
                    $flag_found = true;
                    $near_plants_tmp = $near_plants[$k];
                    $total_vehicles++;
                  }
                }
                if($flag_found)
                {
                 echo'
                  <tr>
    								<td align="center" width="93%">
    									 	'.$near_plants_tmp.'
    								</td>
    								<td align="right" width="7%">
    									'.$total_vehicles.'
    								</td>
    							</tr>
    							';
                }
              }                            
						?>				
						</table>
					</td>
	           <tr>		
				<tr onclick="javascript:report_show_hide_moto('consignment_live')" class="moto_exceptions">
					<td>
						<table cellpadding=0 cellspacing=0 border="0" bordercolor="black" width="100%" class="moto_exceptions">
							<tr>
								<td align="center" width="93%">
									Total Number OF Consignment(Live)(A)
								</td>
								<td align="right" width="7%">
									<?php echo $live_total_consignment; ?>
								</td>
							</tr>
						</table>					
					</td>
				</tr>
				<tr id="consignment_live" style="display:none">
					<td>
						<table cellpadding=0 cellspacing=0 rules="all" border="1" width="100%">
							<tr>
								<td width="93%" align="center">
									Ontime (B)
								</td>
								<td width="7%" align="right">
									<?php echo $live_ontime_consignment; ?>
								</td>
							</tr>
							<tr>
								<td width="93%" align="center">
									Delayed (C)
								</td>
								<td width="7%" align="right">
									<?php echo $live_delayed_consignment; ?>
								</td>
							</tr>
							<tr>
								<td width="93%" align="center">
									Suspect Trans-Shipment (llanging trips) (D)
								</td>
								<td width="7%" align="right">
									<?php echo $live_suspect_consignment; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				
				<tr onclick="javascript:report_show_hide_moto('consignment_del')" class="moto_exceptions">
					<td>
						<table cellpadding=0 cellspacing=0 border="0" bordercolor="black" width="100%" class="moto_exceptions">
							<tr>
								<td align="center" width="93%">
									Total Number Of Consignment (Delivered)
								</td align="right">
								<td width="7%" align="right">
									<?php echo $delivered_total_consignment; ?>
								</td>
							</tr>
						</table>			
					</td>
				</tr>
				<!--<tr id="consignment_del" style="display:none">
					<td>
						<table cellpadding=0 cellspacing=0 rules="all" border="1" width="100%">
							<tr>
								<td align="center" width="93%">
									str
								</td>
								<td align="right" width="7%">
									111
								</td>
							</tr>
						</table>
					</td>
				</tr>-->
				<tr onclick="javascript:report_show_hide_moto('consignment_auto')" class="moto_exceptions">
					<td>
						<table cellpadding=0 cellspacing=0 border="0" bordercolor="black" width="100%" class="moto_exceptions">
							<tr>
								<td align="center" width="93%">
								Total Number Of Consignment (Delivered-Auto POD) (E)
								</td>
								<td align="right" width="7%">
									<?php echo $autopod_total_consignment; ?>
								</td>
							</tr>
						</table>			
					</td>
				</tr>				
				<tr id="consignment_auto" style="display:none">
					<td>
						<table cellpadding=0 cellspacing=0 rules="all" border="1" width="100%">
							<tr>
								<td align="center" width="93%">
									On Time Closed Trips (F)
								</td>
								<td align="right" width="7%">
									<?php echo $autopod_ontime_closed_trip; ?>
								</td>
							</tr>
							<tr>
								<td align="center" width="93%">
									Trips closed with Delay (G)
								</td>
								<td align="right" width="7%">
									<?php echo $autopd_delayed_closed_trip; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
								
				<tr onclick="javascript:report_show_hide_moto('consignment_mis_month')" class="moto_exceptions">
					<td>
						<table cellpadding=0 cellspacing=0 border="0" bordercolor="black" width="100%" class="moto_exceptions">
							<tr>
								<td align="center" width="93%">
								Total Number Of Consignment Mis Month (Delivered)
								</td>
								<td align="right" width="7%">
									<?php echo $total_consignment_cm; ?>
								</td>
							</tr>
						</table>			
					</td>
				</tr>				
				<tr id="consignment_mis_month" style="display:none">
					<td>
						<table cellpadding=0 cellspacing=0 rules="all" border="1"  width="100%">
							<tr>
								<td align="center" width="93%">
									On Time Closed Trips This Month (I)
								</td>
								<td align="right" width="7%">
									<?php echo $ontime_trip_cm; ?>
								</td>
							</tr>
							<tr>
								<td align="center" width="93%">
									Trips Closed with Delay this Month (J)
								</td>
								<td align="right" width="7%">
									<?php echo $delay_trip_cm; ?>
								</td>
							</tr>
							<tr>
								<td align="center" width="93%">
									Delivery Performance this Month (I*100H)(%)
								</td>
								<td align="right" width="7%">
									<?php echo $delivery_performance_cm; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				
				<tr onclick="javascript:report_show_hide_moto('trip_vehicles_running')" class="moto_exceptions">
					<td>
						<table cellpadding=0 cellspacing=0 border="0" bordercolor="black" width="100%" class="moto_exceptions">
							<tr>
								<td align="center" width="93%">
								Average Daily Running Time Hours On Trip Vehicles
								</td>
								<td align="right" width="7%">
									<?php echo $avg_running_hr_trip_all_vehicles; ?>
								</td>
							</tr>
						</table>			
					</td>
				</tr>				
				<!--<tr id="trip_vehicles_running" style="display:none">
					<td>
						<table cellpadding=0 cellspacing=0 rules="all" border="1"  width="100%">
							<tr>
								<td align="center" width="93%">
									str
								</td>
								<td align="right" width="7%">
									111
								</td>
							</tr>
						</table>
					</td>
				</tr>-->
				<tr onclick="javascript:report_show_hide_moto('trip_vehicles_distance')" class="moto_exceptions">
					<td>
						<table cellpadding=0 cellspacing=0 border="0" bordercolor="black" width="100%" class="moto_exceptions">
							<tr>
								<td align="center" width="93%">
								Average Distance Travled By All Vehicle Running(kms) (Trip Vehicles)
								</td>
								<td align="right" width="7%">
									<?php echo $avg_distance_trip_all_vehicles; ?>
								</td>
							</tr>
						</table>			
					</td>
				</tr>				
				<!--<tr id="trip_vehicles_distance" style="display:none">
					<td>
						<table cellpadding=0 cellspacing=0 rules="all" border="1"  width="100%">
							<tr>
								<td align="center" width="93%">
									str
								</td>
								<td align="right" width="7%">
									111
								</td>
							</tr>
						</table>
					</td>
				</tr>-->
				<tr onclick="javascript:report_show_hide_moto('trip_vehicles_distance_kms')" class="moto_exceptions">
					<td>
						<table cellpadding=0 cellspacing=0 border="0" bordercolor="black" width="100%" class="moto_exceptions">
							<tr>
								<td align="center" width="93%">
									Average Distance Travled By All Vehicle Including Stationary(kms) (On Trip Vehicles)
								</td>
								<td align="right" width="7%">
									<?php echo $avg_distance_all_vehicles; ?>
								</td>
							</tr>
						</table>			
					</td>
				</tr>				
				<!--<tr id="trip_vehicles_distance_kms" style="display:none">
					<td>
						<table cellpadding=0 cellspacing=0 rules="all" border="1"  width="100%">
							<tr>
								<td align="center" width="93%">
									str
								</td>
								<td align="right" width="7%">
									111
								</td>
							</tr>
						</table>
					</td>
				</tr>-->
				<tr onclick="javascript:report_show_hide_moto('trip_vehicles_distance_stationary')" class="moto_exceptions">
					<td>
						<table cellpadding=0 cellspacing=0 border="0" bordercolor="black" width="100%" class="moto_exceptions">
							<tr>
								<td align="center" width="93%">
								Consignment Near Consigner Location
								</td>
								<td align="right" width="7%">
									<?php echo $consignment_near_consignment_location; ?>
								</td>
							</tr>
						</table>			
					</td>
				</tr>				
				<!--<tr id="trip_vehicles_distance_stationary" style="display:none">
					<td>
						<table cellpadding=0 cellspacing=0 rules="all" border="1"  width="100%">
							<tr> 
								<td align="center" width="93%">
									str
								</td>
								<td align="right" width="7%">
									111
								</td>
							</tr>
						</table>
					</td>
				</tr>-->		
			</table>
		</td>
	</tr>
</table>
