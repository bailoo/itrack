<?php
//echo "MAIL ACTION";
//$userdates = array();
$sts_date_sel = array();
$xml_date_sel = array();
$lat_sel = array();
$lng_sel = array();
$speed_sel = array();
$temperature_arr = array();

function get_halt_xml_data($vehicle_serial, $vid, $vname, $startdate, $enddate, $user_interval, $report_shift)
{       
    //echo "\nBefore CAS";
    //###### OPEN CASSANDRA CONNECTION
    $o_cassandra = openCassandraConnection();
    //echo "\nAfter CAS";

    global $abspath;
    global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
    global $old_xml_date;

    $io = get_io($vehicle_serial,'temperature');
    echo "<br>IO=".$io;
    //$startdate = "2013-05-12 16:51:49";
    //$enddate = "2013-05-12 21:24:04";	

    //$abspath = "/var/www/html/vts/beta/src/php";
    //$abspath = "D:\\test_app";
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

    //$abspath = "/var/www/html/vts/beta/src/php";	
    //include_once($abspath."/get_location_lp_track_report.php");

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
    

    $ioFoundFlag = 0;
    $ioArrSize = sizeof($ioArr);

    $temperature1 = get_io($IMEI[$i], 'temperature');
    if ($temperature1 == 'io1') { //////door
    $temperature1 = 'i';
    } else if ($temperature1 == 'io2') {
    $temperature1 = 'j';
    } else if ($temperature1 == 'io3') {
    $temperature1 = 'k';
    } else if ($temperature1 == 'io4') {
    $temperature1 = 'l';
    } else if ($temperature1 == 'io5') {
    $temperature1 = 'm';
    } else if ($temperature1 == 'io6') {
    $temperature1 = 'n';
    } else if ($temperature1 == 'io7') {
    $temperature1 = 'o';
    } else if ($temperature1 == 'io8') {
    $temperature1 = 'p';
    }
    if($temperature1!="") {
        $parameterizeData->temperature = $finalIoArr[$temperature1];
    }

    if (count($UnSortedDataObject->deviceDatetime) > 0) {
      $sortObjTmp = sortData($UnSortedDataObject, $sortBy, $parameterizeData);
      //echo "::Data Read";
      //var_dump($sortObjTmp);
      /* echo"sdt1=".$sortObjTmp->deviceDatetime[0]."<br>";
        echo "sdt2=".$sortObjTmp->deviceDatetime[1]."<br>";
        echo "ss1=".$sortObjTmp->speedData[0]."<br>";
        echo "ss2=".$sortObjTmp->speedData[1]."<br>";
        echo "<br><br>"; */
      $sortedSize = sizeof($sortObjTmp->deviceDatetime);
      for ($obi = 0; $obi < $sortedSize; $obi++) {
          /* $finalDateTimeArr[$IMEI[$i]][]=$sortObjTmp->deviceDatetime[$obi];	
            $finalLatitudeArr[$IMEI[$i]][]=$sortObjTmp->latitudeData[$obi];
            $finalLongitudeArr[$IMEI[$i]][]=$sortObjTmp->longitudeData[$obi];
            $finalSpeedArr[$IMEI[$i]][]=$sortObjTmp->speedData[$obi]; */
          $sts_date_sel[] = $sortObjTmp->serverDatetime[$obi];
          $xml_date_sel[] = $sortObjTmp->deviceDatetime[$obi];
          $lat_sel[] = $sortObjTmp->latitudeData[$obi];
          $lng_sel[] = $sortObjTmp->longitudeData[$obi];
          $speed_sel[] = $sortObjTmp->speedData[$obi];

          if($parameterizeData->temperature!=null) {
              $temperature_arr[] = $sortObjTmp->temperatureIOData[$obi];
          }                
          //echo "\nSTS=".$sortObjTmp->serverDatetime[$obi]." ,DeviceDate=".$sortObjTmp->deviceDatetime[$obi]." ,Lat=".$sortObjTmp->latitudeData[$obi]." ,Lng=".$sortObjTmp->longitudeData[$obi];
          //$dataCnt++;
      }
  }
  $SortedDataObject = null;
  $sortObjTmp = null;
  $UnsortedDataObject = null;        
  ######## CASSANDRA BLOCK2 CLOSED
                
    //get_All_Dates($datefrom, $dateto, &$userdates);
    //global $userdates;
    //$userdates = array();
    //get_All_Dates($datefrom, $dateto);
    //date_default_timezone_set("Asia/Calcutta");
    $current_datetime = date("Y-m-d H:i:s");
    $current_date = date("Y-m-d");

    $date_size = sizeof($userdates);
    $substr_count =0;

    //$back_dir = "/var/www/html/itrack_vts";
    //$back_dir = "D:\\itrack_vts";
    //$back_dir_current = "/mnt/volume3";
    //$back_dir_sorted = "/mnt/volume4";
    $AddEntryinrReport = false;
    //$f=0;
    //include("/var/www/html/vts/beta/src/php/common_xml_path.php"); 
    //include("D:\\test_app/common_xml_path.php");
    //##### TEMPERATURE VARIABLES
    $temp_flag = false;
    $in_temp=0;$out_temp=0;
    $in_temp_date="";$in_temp_time="";$out_temp_date="";$out_temp_time="";
    $min_temp = 0;$max_temp=0;
    $min_temp_date ="";$min_temp_time="";$max_temp_date="";$max_temp_time="";
    //##### TEMPERATURE VARIABLES CLOSED
    //echo "<br>DATE_SIZE=".$date_size;
    $size = $xml_date_sel;
    for($i=0;$i<=($size-1);$i++)
    {	     					
        $datetime = $xml_date_sel[$i];	
        $xml_date = $datetime;     
        //echo "\nXML_DATE=".$xml_date." ,DataValid=".$DataValid." ,vehicle_serial=".$vehicle_serial;
        if($xml_date!=null)
        {
            //echo "\nStartDate=".$startdate." ,EndDate=".$enddate;
            if(($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") )
            {                                                							
                 $temperature = $temperature_arr[$i]; 							

                 if($firstdata_flag==0)
                 {							
                     $halt_flag = 0;
                     $firstdata_flag = 1;

                     /*$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
                     $vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);*/							
                     $vserial = $vehicle_serial;

                     $lat_ref = $lat_sel[$i];
                     $lng_ref = $lat_sel[$i];

                     $datetime_ref = $datetime;
                     //$in_temperature = $temperature;								
                     $cum_dist = 0;			

                     //###### FOR IRREGULAR DATA FILTER CODE
                     $last_time1 = $datetime;
                     $latlast = $lat_ref;
                     $lnglast =  $lng_ref;
                     //////##############################
                     //$date_secs1 = strtotime($datetime_ref);							
                     //$date_secs1 = (double)($date_secs1 + $interval);

                     //########### MIN MAX TEMPERATURE VARIABLES
                     $temp_min = 0.0; 
                     $temp_max = 0.0; 
                     $temp_start = false;
                     $temp_min_datetime = $datetime; 
                     $temp_max_datetime = $datetime;							
                 }                 	
                 else
                 {           
                     /*if($lat_cr == "28.70165N")
                     {
                          echo "\nLatRef_Found0=".$lat_cr;
                     }*/
                     //echo "<br>Next";               
                     $lat_tmp1 = explode("=",$lat_tmp[0]);                           //  GET NEXT RECO
                     $lat_cr = preg_replace('/"/', '', $lat_tmp1[1]);

                     $lng_tmp1 = explode("=",$lng_tmp[0]);
                     $lng_cr = preg_replace('/"/', '', $lng_tmp1[1]);							
                     $datetime_cr = $datetime;
                     //$out_temperature = $temperature;
                     $date_secs2 = strtotime($datetime_cr);	
                     calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, $distance);
                     //$distance = calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr);
                     //if(($distance > 0.0100) || ($f== $total_lines-2) )
                     //echo "\nF=".$f." ,total_lines=".$total_lines;										

                     if($f <= ($total_lines-10))
                     {																	
                         //###### FOR IRREGULAR DATA FILTER CODE
                         $tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;

                         calculate_distance($latlast, $lat_cr, $lnglast, $lng_cr, $distance1);
                         //$distance1 = calculate_distance($latlast, $lat_cr, $lnglast, $lng_cr);

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

                         //if($lat_cr == "28.70165N")
                         //{
                            //$difference = strtotime($datetime_cr)-strtotime($datetime_ref);
                            //echo "\nLatRef_Prev=".$lat_cr." ,distance=".$distance." ,HaltFlag=".$halt_flag;										
                            //echo " ,DateTimeCr=".$datetime_cr." ,DateTimeRef=".$datetime_ref." ,difference=".$difference." , interval=".$interval." \n";
                         //}									
                         //echo "\nInCondition ,halt_flag=".$halt_flag." ,distance=".$distance;									

                         //#### RECORD TEMPERATURE MIN MAX
                         if(($temp_start) && ($temperature>=-10.0 && $temperature<=80))
                         {
                            if($temperature < $temp_min)
                            {
                                $temp_min = $temperature;
                                $temp_min_datetime = $datetime;
                            }
                            if($temperature > $temp_max)
                            {
                                $temp_max = $temperature;
                                $temp_max_datetime = $datetime;
                            }																							
                         }
                         //###############################																															

                         if (($halt_flag == 1) && ($distance > 0.100))
                         {								
                             //echo "\n\nIF HALT, datetime=".$datetime." ,ArrivalTime=".$datetime_ref." ,DepartureTime=".$datetime_cr;
                             $arrivale_time = $datetime_ref;
                             $starttime = strtotime($datetime_ref);										  
                             $stoptime = strtotime($datetime_cr);
                             $depature_time=$datetime_cr;

                             $halt_dur = ($stoptime - $starttime);		//THIS IS USED AT RUNTIME, COMMENT HERE LATER
                             $hms_2 = secondsToTime($halt_dur);
                             $hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];

                             if($temperature>=-10.0 && $temperature<=80)
                             {
                                 $out_temperature = $temperature;

                                 $in_temperature_final = $in_temperature;
                                 $out_temperature_final = $out_temperature;											
                             }										

                             $AddEntryinrReport=true;																														

                             if((!$temp_start)&& ($temperature>=-10.0 && $temperature<=80))
                             {
                                 //###### INITIALIZE MIN MAX TEMPERATURE
                                 $temp_start = true;
                                 $temp_min = $temperature;
                                 $temp_max = $temperature;
                                 $temp_min_datetime = $datetime;
                                 $temp_max_datetime = $datetime;

                                 $temp_min_final = $temp_min;
                                 $temp_max_final = $temp_max;
                                 $temp_min_datetime_final = $temp_min_datetime;
                                 $temp_max_datetime_final = $temp_max_datetime;

                             }										
                             else if(($temp_start) && ($temperature>=-10.0 && $temperature<=80))
                             {
                                 //####### STORE TEMPERATURE MIN MAX
                                 $temp_min_final = $temp_min;
                                 $temp_max_final = $temp_max;
                                 $temp_min_datetime_final = $temp_min_datetime;
                                 $temp_max_datetime_final = $temp_max_datetime;

                                 //###### RESET MIN MAX TEMPERATURE
                                 $temp_min = $temperature;
                                 $temp_max = $temperature;
                                 $temp_min_datetime = $datetime;
                                 $temp_max_datetime = $datetime;									
                             }
                             //###############################												
                             }
                             else if(($halt_flag == 1) && ($f == ($total_lines-10)))
                             {										
                                 //echo "\nELSEIF HALT, datetime=".$datetime." ,ArrivalTime=".$datetime_ref;
                                 $arrivale_time = $datetime_ref;
                                 //$in_temperature_final = $in_temperature;

                                 $starttime = strtotime($datetime_ref);																		
                                 /*$depature_time="-";
                                 $halt_dur = "-";
                                 $hrs_min = "-";*/
                                 $AddEntryinrReport=true;
                             }
                             else if(($distance <= 0.100) && ($halt_flag == 0) && ( (strtotime($datetime_cr)-strtotime($datetime_ref))>$interval) )    // IF VEHICLE STOPS FOR 2 MINS 
                             {            													
                                 $halt_once =1;
                                 //echo "\nHALT FLAG SET, datetime=".$datetime;	//HALT
                                 $halt_flag = 1;
                                 $lat_ref1 = $lat_cr;
                                 $lng_ref1 = $lng_cr;
                                 if($temperature>=-10.0 && $temperature<=80)
                                 {										
                                         $in_temperature = $temperature;																		
                                 }
                             }
                             else if ($distance > 0.100)	//RUNNING -HALT OVER
                             {									
                                 //###### FOR IRREGULAR DATA FILTER CODE
                                 if($tmp_speed<500.0 && $tmp_time_diff>0.0)
                                 {																											
                                     $cum_dist = $cum_dist + $distance;						                          
                                     //echo "\nTmp_speed=".$tmp_speed." ,tmp_time_diff=".$tmp_time_diff." ,cum_dist=".$cum_dist;
                                     //echo "<br>dist greater than 0.025: dist=".$total_dist." time2=".$time2;											
                                     $last_time = $datetime;
                                     $datetime_ref= $datetime_cr;
                                     //$in_temperature = $out_temperature;											
                                     //$lat_ref = $lat_cr;
                                     //$lng_ref = $lng_cr;	

                                     /*
                                     //#### RECORD TEMPERATURE MIN MAX
                                     if(($temp_start) && ($temperature>=-10.0 && $temperature<=80))
                                     {
                                             if($temperature < $temp_min)
                                             {
                                                     $temp_min = $temperature;
                                                     $temp_min_datetime = $datetime;
                                             }
                                             if($temperature > $temp_max)
                                             {
                                                     $temp_max = $temperature;
                                                     $temp_max_datetime = $datetime;
                                             }																							
                                     }
                                     //###############################																						
                                     */
                                 }	
                                 $lat_ref = $lat_cr;
                                 $lng_ref = $lng_cr;										
                             }
                        }  //else closed
                    } // $xml_date_current >= $startdate closed
            }   // if xml_date!null closed
            if(($xml_date >= $enddate) && ($halt_flag == 1) && ($AddEntryinrReport==false))
            {
                //echo "\nELSEIF HALT1>XML_DATE, datetime=".$datetime;
                $arrivale_time = $datetime_ref;
                $starttime = strtotime($datetime_ref);
                $in_temperature_final = $in_temperature;						
                /*$depature_time="-";
                $halt_dur = "-";
                $hrs_min = "-";*/
                $AddEntryinrReport=true;
            }

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

                        //$distance_station1 = calculate_distance($lat_ref1, $lat_g, $lng_ref1, $lng_g);
                        //$distance_station2 = calculate_distance($lat_cr, $lat_g, $lng_cr, $lng_g);								

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
                            if($vname == $vehicle_t[$n])
                            {
                                //echo "\nTransporter Matched";
                                $transporter_name_master = $transporter[$n];
                                break;                                  
                            }
                        }

                        for($n=0;$n<sizeof($transporter_input);$n++)
                        {
                            if($vname == $vehicle_input[$n])
                            {
                                //echo "\nTransporter Matched";
                                $transporter_name_input = $transporter_input[$n];
                                break;                                  
                            }
                        }												

                        $route_no="";

                        if($type[$k]=="0")
                        {
                            for($n=0;$n<sizeof($route_input);$n++)
                            {
                                //echo "\nstation_no=".$station_no." customer_input=".$customer_input[$n];
                                //echo "\nreport_shift=".$report_shift." shift_input[n]=".$shift_input[$n];

                                if( ($station_no == $customer_input[$n]) && ($report_shift == $shift_input[$n]) )
                                {
                                    //echo "\ncustomer Matched";
                                    if($route_no=="")
                                    {
                                            $route_no = $route_input[$n];
                                    }
                                    else
                                    {
                                            $route_no = $route_no.'/'.$route_input[$n]; 
                                    }																
                                }
                            } 
                        }

                        /////////////////////////////////////////                             
                        //echo "report_shift=".$report_shift." sizeof(shift)=".sizeof($shift);
                        for($m=0;$m<sizeof($shift);$m++)
                        {
                            $schedule_shift_tmp = $shift[$m];
                            $schedule_point_tmp = $point[$m];
                            $schedule_in_time_tmp = $timing[$m];

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
                        ///####################### GET SHEDULE TIME AND DELAY CLOSED #############################

                        //echo "\nHrsMinHalt=".$hrs_min." ,customer_no[k]=".$customer_no[$k];

                        if($type_str=="Plant")
                        {
                                $schedule_in_time = "-";
                        }

                        //$cum_dist = $cum_dist + $distance;

                        if($vname!=$prev_vehicle)
                        {
                                $cum_dist = 0;
                        }																			

                        $temp_min_date_arr = explode(' ',$temp_min_datetime_final);
                        $temp_max_date_arr = explode(' ',$temp_max_datetime_final);


                        //#### ELIMINATE SPECIAL CHARACTERS
                        $temperaturethis=preg_replace("/&#?[A-Za-z]+;/i",".",$in_temperature_final);
                        $temperaturethis1=iconv('UTF-8', 'UTF-8//IGNORE', $temperaturethis);
                        $in_temperature_final=substr_replace($temperaturethis1, '.', 2, -1);

                        $temperaturethis=preg_replace("/&#?[A-Za-z]+;/i",".",$out_temperature_final);
                        $temperaturethis1=iconv('UTF-8', 'UTF-8//IGNORE', $temperaturethis);
                        $out_temperature_final=substr_replace($temperaturethis1, '.', 2, -1);

                        $temperaturethis=preg_replace("/&#?[A-Za-z]+;/i",".",$temp_min_final);
                        $temperaturethis1=iconv('UTF-8', 'UTF-8//IGNORE', $temperaturethis);
                        $temp_min_final=substr_replace($temperaturethis1, '.', 2, -1);

                        $temperaturethis=preg_replace("/&#?[A-Za-z]+;/i",".",$temp_max_final);
                        $temperaturethis1=iconv('UTF-8', 'UTF-8//IGNORE', $temperaturethis);
                        $temp_max_final=substr_replace($temperaturethis1, '.', 2, -1);


                        //echo "<br>OutTemp=".$out_temperature_final.', TempMin='.$temp_min_final." ,TempMax=".$temp_max_final;
                        if($substr_count == 0)
                        {											
                                //$csv_string_halt = $csv_string_halt.$vname.','.$sno.','.$station_no.','.$type_str.','.$route_no.','.$report_shift.','.$arrivale_time.','.$depature_time.','.$schedule_in_time.','.$time_delay.','.$hrs_min.','.$report_date1.','.$report_time1.','.$report_date2.','.$report_time2.','.$transporter_name_master.','.$transporter_name_input.',-,'.$cum_dist;
                                $csv_string_halt = $csv_string_halt.$vname.','.$sno.','.$station_no.','.$type_str.','.$route_no.','.$report_shift.','.$arrivale_time.','.$depature_time.','.$schedule_in_time.','.$time_delay.','.$hrs_min.','.$report_date1.','.$report_time1.','.$report_date2.','.$report_time2.','.$transporter_name_master.','.$transporter_name_input.',-,'.$cum_dist.','.$in_temperature_final.','.$out_temperature_final.','.$temp_min_final.','.$temp_min_date_arr[0].','.$temp_min_date_arr[1].','.$temp_max_final.','.$temp_max_date_arr[0].','.$temp_max_date_arr[1];
                                $substr_count =1;  
                        }
                        else
                        {
                                //$csv_string_halt = $csv_string_halt."#".$vname.','.$sno.','.$station_no.','.$type_str.','.$route_no.','.$report_shift.','.$arrivale_time.','.$depature_time.','.$schedule_in_time.','.$time_delay.','.$hrs_min.','.$report_date1.','.$report_time1.','.$report_date2.','.$report_time2.','.$transporter_name_master.','.$transporter_name_input.',-,'.$cum_dist;
                                $csv_string_halt = $csv_string_halt."#".$vname.','.$sno.','.$station_no.','.$type_str.','.$route_no.','.$report_shift.','.$arrivale_time.','.$depature_time.','.$schedule_in_time.','.$time_delay.','.$hrs_min.','.$report_date1.','.$report_time1.','.$report_date2.','.$report_time2.','.$transporter_name_master.','.$transporter_name_input.',-,'.$cum_dist.','.$in_temperature_final.','.$out_temperature_final.','.$temp_min_final.','.$temp_min_date_arr[0].','.$temp_min_date_arr[1].','.$temp_max_final.','.$temp_max_date_arr[0].','.$temp_max_date_arr[1];
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
                //$in_temperature= $out_temperature;            				
                $halt_flag = 0;			

                $arrivale_time="";
                $depature_time="";
                $in_temperature_final = "";
                $out_temperature_final = "";

            }   //IF ADD ENTRYINRrEPORT
            $f++;
        }   // while closed

    }  // for closed

    ######## CLOSE CASSANDRA CONNECTION	
    closeCassandraConnection($o_cassandra);
    
    //echo "<BR>CSV_STRING_HALT=><BR>".$csv_string_halt;
    echo "\nHALT CLCLOSED";
}	
?>
