<?php
$sts_date_sel = array();
$xml_date_sel = array();
$lat_sel = array();
$lng_sel = array();
$speed_sel = array();

echo "MAIL ACTION";

//echo "MAIL ACTION";
function get_halt_xml_data($vehicle_serial, $vid, $vname, $startdate, $enddate, $user_interval, $report_shift)
{
    //###### OPEN CASSANDRA CONNECTION
   $o_cassandra = openCassandraConnection();

    global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
    global $old_xml_date;	
    //echo $vehicle_serial.",". $vid." ,".$vname." ,".$startdate." ,".$enddate." ,".$user_interval." ,".$report_shift;
    //$startdate = "2013-05-12 16:51:49";
    //$enddate = "2013-05-12 21:24:04";	

    $abspath = "/var/www/html/vts/beta/src/php";
    include_once($abspath."/util.hr_min_sec.php");
    //echo "\nHALT function before1";
    global $customer_visited;
    global $shift;
    global $point;
    global $timing;
    global $vehicle_t;
    global $transporter;

    global $route_input;
    global $customer_input;
    global $vehicle_input;
    global $shift_input;
    global $transporter_input;

    global $RouteNo2;
    global $Plant2;
    global $ScheduleInTime2;
    global $ScheduleOutTime2;
    $customer_out_time = "";
    $plant_in_time = "";

    $delay = "-";
    $schedule_time = "-";
    $route_no = "-";
    $transporter_name_master ="-";
    $transporter_name_input ="-";

    $date_tmp1 = explode(" ",$startdate);
    $date_tmp2 = explode(" ",$enddate); 
    $report_date1 = $date_tmp1[0];
    $report_time1 = $date_tmp1[1];
    $report_date2 = $date_tmp2[0];
    $report_time2 = $date_tmp2[1];

    //echo "\nReportDate1=".$date_tmp1;

    $abspath = "/var/www/html/vts/beta/src/php";
    include_once($abspath."/get_location_lp_track_report.php");

    global $DbConnection;
    global $account_id;
    global $geo_id1;
    $sno =1;
    global $csv_string_halt;
    global $overall_dist;
    global $total_halt_dur;

    global $station_id;
    global $type;
    global $customer_no;
    global $station_name;
    global $station_coord;
    global $distance_variable;
    global $google_location;

    for($k=0;$k<sizeof($station_coord);$k++)       //INITIALISE VARIABLES
    {    
        $halt_flag[$k] = 0;
        $firstdata_flag_halt[$k] = 0; 
        $substr_count[$k] =0;  
        //$total_halt_time[$k] = 0;
    }

    $interval=$user_interval*60;
    //echo "interval=".$interval."<br>";
    $fix_tmp = 1;
    $xml_date_latest="1900-00-00 00:00:00";
    $CurrentLat = 0.0;
    $CurrentLong = 0.0;
    $LastLat = 0.0;
    $LastLong = 0.0;
    $firstData = 0;
    $distance =0.0;
    $linetowrite="";
    $firstdata_flag =0;

    $arrivale_time="";
    $depature_time="";
    $hrs_min="";

    $date_1 = explode(" ",$startdate);
    $datefrom = $date_1[0];
    $timefrom = $date_1[1];
    $date_2 = explode(" ",$enddate);	
    $dateto = $date_2[0];
    $timeto = $date_2[1];
    $cum_dist = 0;

    $current_datetime = date("Y-m-d H:i:s");
    $current_date = date("Y-m-d");

   //###### CASSANDRA BLOCK1 ###########
    global $sts_date_sel;
    global $xml_date_sel;
    global $lat_sel;
    global $lng_sel;
    global $speed_sel;

    $date1 = $startdate;
    $date2 = $enddate;
    $datefrom = $report_date1;
    $dateto = $report_date2;

    $userInterval = 0;

    $sortBy = 'h';
    $firstDataFlag = 0;
    $endDateTS = strtotime($date2);
    $dataCnt = 0;
    //$userInterval = "0";
    $requiredData = "All";

    $parameterizeData = new parameterizeData();
    $ioFoundFlag = 0;

    $parameterizeData->latitude = "d";
    $parameterizeData->longitude = "e";
    $parameterizeData->speed = "f";

    $finalVNameArr = array();
    //###### CASSANDRA BLOCK1 CLOSED
          
    //$device_all_date_time = array();    //ALL DEVICE TIME

    //######### CASSANDRA BLOCK2 OPENS
    $sts_date_sel = array();
    $xml_date_sel = array();
    $lat_sel = array();
    $lng_sel = array();
    $speed_sel = array();

    //##### DEBUG MSG
    $msg = "\nReadSno:" . $i . " ,imei=" . $vehicle_serial . " ,date1=" . $date1 . " ,date2=" . $date2;

    if($LOG) {$debug_msg.=$msg."\n";}
    //echo $msg; 

    $dataCnt = 0;
    $LastSortedDate = null;
    $SortedDataObject = new data();
    $UnSortedDataObject = new data();

    readDataBetweenDatetime($vehicle_serial, $date1, $date2, $userInterval, $requiredData, $sortBy, $type, $parameterizeData, $firstDataFlag, $o_cassandra, $SortedDataObject);
    //echo "\nCount=".count($SortedDataObject->deviceDatetime);
   
    //exit(0);
    if (count($SortedDataObject->deviceDatetime) > 0) {
            //$sortObjTmp = sortData($UnSortedDataObject, $sortBy, $parameterizeData);
            //echo "::Data Read";
            //var_dump($sortObjTmp);
            /* echo"sdt1=".$sortObjTmp->deviceDatetime[0]."<br>";
              echo "sdt2=".$sortObjTmp->deviceDatetime[1]."<br>";
              echo "ss1=".$sortObjTmp->speedData[0]."<br>";
              echo "ss2=".$sortObjTmp->speedData[1]."<br>";
              echo "<br><br>"; */
            $sortedSize = sizeof($SortedDataObject->deviceDatetime);
            //echo "\nSortedSize=".$sortedSize;
            for ($obi = 0; $obi < $sortedSize; $obi++) {
                /* $finalDateTimeArr[$IMEI[$i]][]=$SortedDataObject->deviceDatetime[$obi];
                  $finalLatitudeArr[$IMEI[$i]][]=$SortedDataObject->latitudeData[$obi];
                  $finalLongitudeArr[$IMEI[$i]][]=$SortedDataObject->longitudeData[$obi];
                  $finalSpeedArr[$IMEI[$i]][]=$SortedDataObject->speedData[$obi]; */
                //echo "\nSTORED";
                $sts_date_sel[] = $SortedDataObject->serverDatetime[$obi];
                $xml_date_sel[] = $SortedDataObject->deviceDatetime[$obi];
                $lat_sel[] = $SortedDataObject->latitudeData[$obi];
                $lng_sel[] = $SortedDataObject->longitudeData[$obi];
                $speed_sel[] = $SortedDataObject->speedData[$obi];
            }
	}        
        
        $SortedDataObject = null;
        $sortObjTmp = null;
        $UnsortedDataObject = null;     
        
        //##### DEBUG MSG
        $msg = "\nIMEI=".$vehicle_serial." ,SizeXmlDate=".sizeof($xml_date_sel);
        if($LOG) {$debug_msg.=$msg."\n";}
        //echo $msg;

        ######## CASSANDRA BLOCK2 CLOSED
        
	$AddEntryinrReport = false;
	if (sizeof($xml_date_sel) > 0) {
            for ($y = 0; $y < sizeof($xml_date_sel); $y++) {
            {           
                //echo "\nData Exist";
                $DataValid = 0;
                $halt_once = false;
                $xml_date = $xml_date_sel[$y];
		$datetime = $xml_date;

                        //echo "\nIN WHILE";                     				        
                        if( (strlen($lat_sel[$y])>5) && ($lat_sel[$y]!="-") && (strlen($lng_sel[$y])>5) && ($lng_sel[$y]!="-") )
                        {
                                $DataValid = 1;
                        }
                         //echo "\nXML_DATE=".$xml_date." ,DataValid=".$DataValid." ,vehicle_serial=".$vehicle_serial;   

                        if($xml_date!=null)
                        {
                            //echo "\nStartDate=".$startdate." ,EndDate=".$enddate;
                            if(($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
                            {							       
                                if($firstdata_flag==0)
                                {							
                                    //echo "\nInFirst";
				    $halt_flag = 0;
                                    $firstdata_flag = 1;

                                    /*$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
                                    $vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);*/							
                                    $vserial = $vehicle_serial;

                                    $lat_ref = $lat_sel[$y];
                                    $lng_ref = $lng_sel[$y];

                                    $datetime_ref = $datetime;	
                                    $cum_dist = 0;			

                                    //###### FOR IRREGULAR DATA FILTER CODE
                                    $last_time1 = $datetime;
                                    $latlast = $lat_ref;
                                    $lnglast =  $lng_ref;
                                    //////##############################
                                    //$date_secs1 = strtotime($datetime_ref);							
                                    //$date_secs1 = (double)($date_secs1 + $interval);      	
                                }                 	
                                else
                                {           
                                    $lat_cr = $lat_sel[$y];
                                    $lng_cr = $lng_sel[$y];							
                                    $datetime_cr = $datetime;																		
                                    $date_secs2 = strtotime($datetime_cr);	
                                    calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, $distance);
																	
                                    //###### FOR IRREGULAR DATA FILTER CODE
                                    $tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;

                                    calculate_distance($latlast, $lat_cr, $lnglast, $lng_cr, $distance1);
                                    if($tmp_time_diff1>0)
                                    {
                                        $tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
                                        //if($tmp_speed==0) echo "\nDistance1=".$distance1." ,tmp_time_diff1=".$tmp_time_diff1." ,latlast=".$latlast." ,lnglast=".$lnglast." ,lat_cr=".$lat_cr." ,lng_cr=".$lng_cr;
                                        $last_time1 = $datetime;
                                        $latlast = $lat_cr;
                                        $lnglast =  $lng_cr;
                                    }
                                    $tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time) )) / 3600;
                                    //#######################################

                                    //echo "\nInCondition ,halt_flag=".$halt_flag." ,distance=".$distance;									
                                    if (($halt_flag == 1) && ($distance > 0.100))
                                    {								
                                        //echo "\n\nIF HALT, datetime=".$datetime;
                                        $arrivale_time = $datetime_ref;
                                        $starttime = strtotime($datetime_ref);										  
                                        $stoptime = strtotime($datetime_cr);
                                        $depature_time=$datetime_cr;

                                        $halt_dur = ($stoptime - $starttime);		//THIS IS USED AT RUNTIME, COMMENT HERE LATER
                                        $hms_2 = secondsToTime($halt_dur);
                                        $hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];

                                        $AddEntryinrReport=true;
                                    }
                                    else if(($halt_flag == 1) && ($f == ($total_lines-10)))
                                    {										
                                        //echo "\nELSEIF HALT, datetime=".$datetime;
                                        $arrivale_time = $datetime_ref;
                                        $starttime = strtotime($datetime_ref);										  
                                        /*$depature_time="-";
                                        $halt_dur = "-";
                                        $hrs_min = "-";*/
                                        $AddEntryinrReport=true;
                                    }
                                    else if(($distance <= 0.100) && ($halt_flag == 0) && ( (strtotime($datetime_cr)-strtotime($datetime_ref))>$interval) )    // IF VEHICLE STOPS FOR 2 MINS 
                                    {            													
                                        $halt_once =1;
                                        //echo "\nHALT FLAG SET, datetime=".$datetime;
                                        $halt_flag = 1;
                                        $lat_ref1 = $lat_cr;
                                        $lng_ref1 = $lng_cr;
                                    }
                                    else if ($distance > 0.100)
                                    {									
                                        //###### FOR IRREGULAR DATA FILTER CODE
                                        if($tmp_speed<500.0 && $tmp_time_diff>0.0)
                                        {																											
                                            $cum_dist = $cum_dist + $distance;						                          
                                            //echo "\nTmp_speed=".$tmp_speed." ,tmp_time_diff=".$tmp_time_diff." ,cum_dist=".$cum_dist;
                                            //echo "<br>dist greater than 0.025: dist=".$total_dist." time2=".$time2;											
                                            $last_time = $datetime;
                                            $datetime_ref= $datetime_cr;	

                                            //$lat_ref = $lat_cr;
                                            //$lng_ref = $lng_cr;																					
                                            //#######################################																						
                                        }	
                                        $lat_ref = $lat_cr;
                                        $lng_ref = $lng_cr;										
                                    }									
                                }  //else closed
			} // $xml_date_current >= $startdate closed
                    }   // if xml_date!null closed					
					
                    if($AddEntryinrReport)
                    {										
                        //echo "\nIN ADDENTRY, datetime=".$datetime;
                        $place = "-";												
                        $station_no = "-";
                        $transporter_name = "-";
                        $schedule_time = "-";
                        $delay = "-";
                        $entered_station = 0;

                        for($k=0;$k<sizeof($station_coord);$k++)
                        {												
                            $coord = explode(',',$station_coord[$k]);
                            $lat_g = trim($coord[0]);
                            $lng_g = trim($coord[1]);  

                            if($distance_variable[$k] == 0)
                            {
                                $distance_variable[$k] = 0.1;
                            }    

                            $distance_station = 0;              
                            if( ($lat_g!="") && ($lng_g!="") && ($customer_no[$k]!="") )
                            {
                                //echo "\nDIST::datetime=".$datetime." ,op_date1=".$op_date1." ,op_date2=".$op_date2." \ndistance=".$distance;
                                calculate_distance($lat_ref1, $lat_g, $lng_ref1, $lng_g, $distance_station1);
                                calculate_distance($lat_cr, $lat_g, $lng_cr, $lng_g, $distance_station2);

                                /*if($customer_no[$k]=="1105")
                                {
                                        echo "\nDIST::datetime=".$datetime.",distance_station1=".$distance_station1.",distance_station2=".$distance_station2;
                                }*/

                                if($distance_station1<$distance_station2)
                                {
                                        $distance_station=$distance_station1;
                                }
                                else
                                {
                                        $distance_station=$distance_station2;
                                }
                                /*if($customer_no[$k]=="865")
                                {
                                        echo "\nlat_ref=".$lat_ref." ,lng_ref=".$lng_ref." :: lat_cr=".$lat_cr." ,lng_cr=".$lng_cr." :: lat_g=".$lat_g." ,lng_g=".$lng_g." ,distance_station=".$distance_station." ,distance_variable[k]=".$distance_variable[$k]." ,datetime=".$datetime." ,imei=".$vehicle_serial;
                                }*/

                                if($distance_station < $distance_variable[$k])
                                {
                                        //echo "\nVehicle entered in station=CustomerNo=".$customer_no[$k];  
                                        $station_no = $customer_no[$k];
                                        /*if($station_no=="")
                                        {
                                        $station_no = "-";
                                        } */                       
                                        $customer_visited[] = $station_no;
                                        $customer_type[] = $type[$k];
                                        $entered_station = 1;
                                        //break;
                                }
                            }								

                            //##########################################                        
                            if($entered_station)
                            {
                                $pos_c = strpos($station_no, "@");
                                if($pos_c !== false)
                                {
                                    //echo "\nNegative Found";
                                    $customer_at_the_rate1 = explode("@", $station_no);											
                                }
                                else
                                {
                                    $customer_at_the_rate1[0] = $station_no;
                                }

                                //echo "\nHALT OCCURED";
                                $type_str = "";
                                $time_delay="";

                                if($type[$k]=="0")
                                {
                                    $type_str = "Customer";
                                } 
                                else if($type[$k] == "1") 
                                {
                                    $type_str = "Plant";
                                }

                                //echo "\nVehicle=".$vname.=" ,Station_no=".$station_no." ,Type=".$type_str." ,ArrivalTime=".$arrivale_time." ,DepartureTime=".$depature_time."\n";												
                                ////################ GET SHEDULE TIME AND DELAY ##########################################
                                //echo "\nSizeofShift=".sizeof($shift);

                                $valid_halt = false;

                                for($n=0;$n<sizeof($transporter);$n++)
                                {
                                    if(trim($vname) == trim($vehicle_t[$n]))
                                    {
                                        //echo "\nTransporter Matched";
                                        $transporter_name_master = $transporter[$n];
                                        break;                                  
                                    }
                                }

                                for($n=0;$n<sizeof($transporter_input);$n++)
                                {
                                    if(trim($vname) == trim($vehicle_input[$n]))
                                    {
                                        //echo "\nTransporter Matched";
                                        $transporter_name_input = $transporter_input[$n];
                                        break;                                  
                                    }
                                }												

                                //$route_no="";
                                $route_no = $route_input[$vname];
                                //echo "\nRoute1=".$route_no;

                                /////////////////////////////////////////                             
                                //echo "report_shift=".$report_shift." sizeof(shift)=".sizeof($shift);
                                //######### CUSTOMER SCHEDULE
                                $schedule_in_time_tmp1 ="";
                                if($type_str=="Customer")
                                {
                                    for($m=0;$m<sizeof($shift);$m++)
                                    {
                                        $schedule_shift_tmp = $shift[$m];
                                        $schedule_point_tmp = $point[$m];
                                        $schedule_in_time_tmp = $timing[$m];
                                        $schedule_in_time_tmp1 = $schedule_in_time_tmp;	//CUSTOMER SCHEDULE
                                        /*echo "\nSchedule_in_time_tmp=".$schedule_in_time_tmp;
                                        echo "\nSchedule_shift_tmp=".$schedule_shift_tmp." ,report_shift=".$report_shift;
                                        echo " ,Schedule_point_tmp=".$schedule_point_tmp." ,station_no=".$station_no;*/

                                        if( ($schedule_shift_tmp == $report_shift) && ($schedule_point_tmp == $station_no) )
                                        {                              
                                            //echo "\nSchedule Matched";												  
                                            $start_date_tmp = explode(" ",$startdate);
                                            $end_date_tmp = explode(" ",$enddate);

                                            $schedule_in_time = $schedule_in_time_tmp;

                                            $tmp_time_input = $start_date_tmp[0]." ".$start_date_tmp[1];
                                            $tmp_time_schedule = $start_date_tmp[0]." ".$schedule_in_time;

                                            $tmp_input = strtotime($tmp_time_input);
                                            $tmp_schedule = strtotime($tmp_time_schedule);

                                            $final_date = "";
                                            if($tmp_input <= $tmp_schedule)
                                            {
                                                $final_date = $start_date_tmp[0];
                                            }								
                                            else
                                            {
                                                $final_date = $end_date_tmp[0];
                                            }

                                            //echo "\nScheduleInTime=".$schedule_in_time;

                                            $in_time_str_excel = $final_date." ".$schedule_in_time_tmp;
                                            $in_time_str = $arrivale_time;														

                                            if($schedule_in_time!="")
                                            {
                                                $time1 = strtotime($in_time_str) - strtotime($in_time_str_excel);

                                                //echo "\nin_time_str=".$in_time_str." ,in_time_str_excel=".$in_time_str_excel." ,DiffTime=".$time1;

                                                if($time1>0)
                                                {															
                                                    $hms_3 = secondsToTime($time1);
                                                    $time_delay = $hms_3[h].":".$hms_3[m].":".$hms_3[s];
                                                }
                                                else
                                                {
                                                    //time_delay = "00:00:00";
                                                    $time1 = abs($time1);
                                                    $hms_4 = secondsToTime($time1);								
                                                    $time_delay = $hms_4[h].":".$hms_4[m].":".$hms_4[s];
                                                    $time_delay = "-".$time_delay;																					
                                                }
                                            }														

                                            /*$date_arrival_tmp = explode(" ",$arrivale_time);
                                            $schedule_time_tmp =  $date_arrival_tmp[0]." ".$schedule_timing_tmp;                       
                                            $delay_tmp = strtotime($arrivale_time) - strtotime($schedule_time_tmp); */														
                                            //$delay_hms = secondsToTime($delay_tmp);
                                            //$delay = $delay_hms[h].":".$delay_hms[m].":".$delay_hms[s];
                                            break;                                   
                                        }
                                    }
                                }

                                //####### PLANT SCHEDULE
                                else if($type_str=="Plant")
                                {
                                    for($m=0;$m<sizeof($ScheduleInTime2);$m++)
                                    {																			
                                        $schedule_point_tmp2 = $Plant2[$m];
                                        $schedule_in_time_tmp1 = $ScheduleInTime2[$m];	 //PLANT IN SCHEDULE
                                        //$schedule_out_time_tmp2 = $ScheduleOutTime2[$m]; //PLANT OUT SCHEDULE

                                        /*echo "\nSchedule_in_time_tmp=".$schedule_in_time_tmp;
                                        echo "\nSchedule_shift_tmp=".$schedule_shift_tmp." ,report_shift=".$report_shift;
                                        echo " ,Schedule_point_tmp=".$schedule_point_tmp." ,station_no=".$station_no;*/
                                        //##### GET BOTH ROUTE NUMBER
                                        $tmp_route = explode('(',$vname);
                                        $route_from_vehicle = trim($tmp_route[0]);
                                        $route_master = trim($RouteNo2[$m]);
                                        //echo "\nvname=".$vname.",route_from_vehicle[0]=".$route_from_vehicle." ,route_master=".$route_master;
                                        //echo "\nschedule_point_tmp2=".$schedule_point_tmp2." ,station_no=".$station_no." ,route_no=".$route_no." ,route_master=".$route_master;
                                        //if( ($schedule_point_tmp2 == $station_no) && ($route_from_vehicle == $route_master) )
                                        //if(($schedule_point_tmp2 == $station_no) && (trim($vname)==trim($route_master)) )
                                        if(($schedule_point_tmp2 == $station_no) && (trim($route_no)==trim($route_master)) )
                                        {                              
                                            //echo "\nPlantSchedule Matched";												  
                                            $start_date_tmp = explode(" ",$startdate);
                                            $end_date_tmp = explode(" ",$enddate);

                                            //###### GET PLANT -IN DELAY
                                            $schedule_in_time = $schedule_in_time_tmp1;				

                                            if($schedule_in_time!="")
                                            {
                                                //echo "\nIn plant time_delay:".$schedule_in_time;
                                                $tmp_time_input = $start_date_tmp[0]." ".$start_date_tmp[1];
                                                $tmp_time_schedule = $start_date_tmp[0]." ".$schedule_in_time;

                                                $tmp_input = strtotime($tmp_time_input);
                                                $tmp_schedule = strtotime($tmp_time_schedule);

                                                $final_date = "";
                                                if($tmp_input <= $tmp_schedule)
                                                {
                                                    $final_date = $start_date_tmp[0];
                                                }								
                                                else
                                                {
                                                    $final_date = $end_date_tmp[0];
                                                }

                                                //echo "\nScheduleInTime=".$schedule_in_time;											
                                                $in_time_str_excel = $final_date." ".$schedule_in_time;
                                                $in_time_str = $arrivale_time;																	

                                                $time1 = strtotime($in_time_str) - strtotime($in_time_str_excel);

                                                //echo "\nin_time_str=".$in_time_str." ,in_time_str_excel=".$in_time_str_excel." ,DiffTime=".$time1;
                                                if($in_time_str!="")
                                                {
                                                    if($time1>0)
                                                    {															
                                                        $hms_3 = secondsToTime($time1);
                                                        $time_delay = $hms_3[h].":".$hms_3[m].":".$hms_3[s];
                                                    }
                                                    else
                                                    {
                                                        //time_delay = "00:00:00";
                                                        $time1 = abs($time1);
                                                        $hms_4 = secondsToTime($time1);								
                                                        $time_delay = $hms_4[h].":".$hms_4[m].":".$hms_4[s];
                                                        $time_delay = "-".$time_delay;																				
                                                    }
                                                }
                                                //echo "\ntime_delayFinal=".$time_delay;
                                            }

                                            break;                                   
                                        }
                                    }
                                }								

                                //echo "\nSTATION_NO=".$station_no." ,TimeDelay=".$time_delay;								
                                if($schedule_in_time=="")
                                {
                                        $schedule_in_time = "-";
                                }								
                                if($time_delay=="")
                                {
                                        $time_delay = "-";
                                }								
                                if($route_no=="")
                                {
                                        $route_no="-";
                                }
                                if($hrs_min=="")
                                {
                                        $hrs_min = "-";
                                }

                                if($vname!=$prev_vehicle)
                                {
                                    $cum_dist = 0;
                                }

                                $valid_record = 0;

                                if($type_str=="Customer")
                                {
                                    $customer_in_time = $arrivale_time;
                                    //echo "\nCIN=".$customer_in_time;
                                    //echo ", Route2=".$route_no;
                                    $valid_record = 1;
                                }
                                else if($type_str=="Plant")
                                {
                                    if((strtotime($arrivale_time) > strtotime($customer_in_time)) && ($customer_in_time!=""))
                                    {
                                        //echo "\nPlantIN:".$arrivale_time." ,OUT=".$depature_time;
                                        $plant_in_time = $arrivale_time;
                                        $valid_record = 1;
                                    }
                                }
                                if($valid_record==1)
                                {
                                    if($substr_count == 0)
                                    {											
                                        $csv_string_halt = $csv_string_halt.$vname.','.$sno.','.$station_no.','.$type_str.','.$route_no.','.$report_shift.','.$arrivale_time.','.$depature_time.','.$schedule_in_time_tmp1.','.$time_delay.','.$hrs_min.','.$report_date1.','.$report_time1.','.$report_date2.','.$report_time2.','.$cum_dist;
                                        $csv_string_halt = $csv_string_halt.$vname.','.$sno.','.$station_no.','.$type_str.','.$route_no.','.$report_shift.','.$arrivale_time.','.$depature_time.','.$schedule_in_time_tmp1.','.$time_delay.','.$schedule_in_time_tmp2.','.$schedule_out_time_tmp2.','.$time_delay_p1.','.$time_delay_p2.','.$hrs_min.','.$report_date1.','.$report_time1.','.$report_date2.','.$report_time2.','.$transporter_name_master.','.$transporter_name_input.',-,'.$cum_dist.",-";

                                        $substr_count =1;  
                                    }
                                    else
                                    {
                                        $csv_string_halt = $csv_string_halt."#".$vname.','.$sno.','.$station_no.','.$type_str.','.$route_no.','.$report_shift.','.$arrivale_time.','.$depature_time.','.$schedule_in_time_tmp1.','.$time_delay.','.$hrs_min.','.$report_date1.','.$report_time1.','.$report_date2.','.$report_time2.','.$cum_dist;
                                    }
                                }

                                $prev_vehicle = $vname;
                                //echo "\nSerial=".$sno;
                                $sno++;
                                //$date_secs1 = strtotime($datetime_cr);
                                //$date_secs1 = (double)($date_secs1 + $interval);
                                //echo "\nHALT COMPLETED";
                                $entered_station = 0;
                                //break;                            											
                                //$entered_station = 0;
                        } //IF ENTERED STATION                             
                    }  //FOR LOOP CLOSED

                    $AddEntryinrReport=false;	
                    $lat_ref = $lat_cr;
                    $lng_ref = $lng_cr;
                    $datetime_ref= $datetime_cr;            				
                    $halt_flag = 0;

                    $arrivale_time="";
                    $depature_time="";						

                }   //IF ADD ENTRYINRrEPORT		
                   //echo "\nvehicle_name=".$vname."csv_string_halt==".$csv_string_halt."<br>";            
            }  // for closed
        }
    }    
    ######## CLOSE CASSANDRA CONNECTION	
    closeCassandraConnection($o_cassandra);

    //echo "\nCSV_STRING_HALT=".$csv_string_halt;

    echo "\nHALT CLCLOSED";
}	
?>
