<?php
    //error_reporting(-1);
    //ini_set('display_errors', 'On');
    set_time_limit(300);	
    date_default_timezone_set("Asia/Kolkata");
    include_once("main_vehicle_information_1.php");
    include_once('Hierarchy.php');
    $root=$_SESSION["root"];
    include_once('util_session_variable.php');
    include_once('util_php_mysql_connectivity.php');
    $report = "door open";
    include_once("calculate_distance.php");
    include_once("report_title.php");	
    include_once("util.hr_min_sec.php");

    include_once('xmlParameters.php');
    include_once("report_title.php");
    include_once('parameterizeData.php');
    include_once('data.php');
    include_once("sortXmlData.php");
    include_once("getXmlData.php");

    $DEBUG = 0;
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
    $dateRange1 = $date_1[0];
    $dateRange2 = $date_2[0];

    $userInterval = "0";
    $requiredData="All";
    $sortBy="h";
    $firstDataFlag=0;
    $endDateTS=strtotime($date2);
    $dataCnt=0;

    $finalVNameArr=array();	
    $parameterizeData=new parameterizeData();
    $parameterizeData->latitude="d";
    $parameterizeData->longitude="e";
    
    get_All_Dates($dateRange1, $dateRange2, $userdates);    
    $date_size = sizeof($userdates);
    for($i=0;$i<$vsize;$i++)
    {
        $dataCnt=0;
        //echo "vs=".$vserial[$i]."<br>";
        $vehicle_info=get_vehicle_info($root,$vserial[$i]);
        //echo "vehicleInfo=".$vehicle_info."<br>";
        $vehicle_detail_local=explode(",",$vehicle_info);
        $finalVNameArr[$i]=$vehicle_detail_local[0];
        //echo "vehcileName=".$finalVNameArr[$i]." vSerial=".$vehicle_detail_local[0]."<br>";

        $ioArr=explode(":",$vehicle_detail_local[7]);
        $ioFoundFlag=0;
        $ioArrSize=sizeof($ioArr);		
        for($z=0;$z<$ioArrSize;$z++)
        {
            $tempIo=explode("^",$ioArr[$z]);
            //echo "io=".$ioArr[$z]."<br>";
            if($tempIo[1]=="door_open")
            {			
                    $parameterizeData->doorOpen1=$finalIoArr[$tempIo[0]];
            }
            if($tempIo[1]=="door_open2")
            {		
                    $parameterizeData->doorOpen2=$finalIoArr[$tempIo[0]];
            }
            if($tempIo[1]=="door_open3")
            {			
                    $parameterizeData->doorOpen3=$finalIoArr[$tempIo[0]];
            }
        }
        
     
	$B3=0;
	$B2=0;
	$B1=0;
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;
	$firstdata_flag =0;   
        $runhr_duration =0 ;
	$flag =0;

	$StartFlag=0;
	$StartFlag2=0;
	$continuous_running_flag =0;
	$continuous_running_flag2 =0;
	$continuous_running_flag3=0;

	$previous_date = $startdate;
	
        for($di=0;$di<=($date_size-1);$di++)
        {
            //echo "userdate=".$userdates[$di]."<br>";
            $SortedDataObject=new data();
            readFileXmlNew($vserial[$i],$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
            //var_dump($SortedDataObject);
            if(count($SortedDataObject->deviceDatetime)>0)
            {
                $closeDataFlag=1;
		$closeDataFlag2=1;
		$closeDataFlag3=1;
                $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
                for($obi=0;$obi<$prevSortedSize;$obi++)
                {
                    $xml_date=$SortedDataObject->deviceDatetime[$obi];
                    if($xml_date>$date1 && $xml_date<$date2)
                    { 
                        $finalDateTimeArr[$i][$dataCnt]=$SortedDataObject->deviceDatetime[$obi];
                        $finalLatitudeArr[$i][$dataCnt]=$SortedDataObject->latitudeData[$obi];
                        $finalLongitudeArr[$i][$dataCnt]=$SortedDataObject->longitudeData[$obi];
                        if($parameterizeData->doorOpen1!=null)
                        {
                          
                            $datetime = $xml_date;  
                            $door_count = $SortedDataObject->doorOpen1Data[$obi]; 
                            if($door_count>=250)
                            {
                                $continuous_running_flag = 1;
                            }
                            if($door_count>250 && $StartFlag==0)  
                            {                						
                                $time1 = $datetime;
                                $StartFlag = 1;
                            } 
                            
                            else if(($door_count<250 && $StartFlag==1) || ($continuous_running_flag ==1))   //500
                            {
                                $StartFlag = 2;
                            }
                            //echo "door_count=".$door_count." StartFlag=".$StartFlag." continuous_running_flag=".$continuous_running_flag."<br>";
                            $time2 = $datetime;

                            if($StartFlag == 2)
                            {
                                $StartFlag=0;
                                $runtime = strtotime($time2) - strtotime($time1);
                                if($runtime > 60)
                                {
                                    //echo "in door open";
                                    $lat_tmp1 = explode("=",$lat_tmp[0]);  
                                    $lat = preg_replace('/"/', '', $lat_tmp1[1]);

                                    $lng_tmp1 = explode("=",$lng_tmp[0]);  
                                    $lng = preg_replace('/"/', '', $lng_tmp1[1]);
                                    $closeDataFlag=0;
                                    $doorOpenArrB1['imei'.$B1] =$vserial[$i];
                                    $doorOpenArrB1['vname'.$B1] =$vname;
                                    $doorOpenArrB1['lat'.$B1] =$lat;
                                    $doorOpenArrB1['lng'.$B1] =$lng;
                                    $doorOpenArrB1['datefrom'.$B1] =$time1;
                                    $doorOpenArrB1['dateto'.$B1] =$time2;
                                    $doorOpenArrB1['door_open'.$B1] =$runtime;
                                    $doorOpenArrB1['door_open_type'.$B1] ="DO1";
                                    $B1++;
                                } 
                            }
                        }		
                        if($parameterizeData->doorOpen2!=null)
                        {
                            $datetime = $xml_date;  
                            $door_count2 = $SortedDataObject->doorOpen2Data[$obi];;                                                                            	                         

                            if($door_count2>=250)
                            {
                                $continuous_running_flag2 = 1;
                            }
                            if($door_count2>250 && $StartFlagB2==0)  
                            {                						
                                $timeB1 = $datetime;
                                $StartFlagB2 = 1;
                            }								
                            //else if( ($door_count<350 && $StartFlag==1) || ( ($c==($total_lines-1)) && ($continuous_running_flag ==1) ) )   //500
                            else if(($door_count2<250 && $StartFlagB2==1) || ($continuous_running_flag2 ==1))   //500
                            {
                                $StartFlagB2 = 2;
                            }
                            //echo "doocount=".$door_count2." StartFlagB2=".$StartFlagB2." continuous_running_flag2=".$continuous_running_flag2."<br>";
                            $timeB2 = $datetime;

                            if($StartFlagB2 == 2)
                            {									
                                $StartFlagB2=0;
                                $runtimeB = strtotime($timeB2) - strtotime($timeB1);
                                if($runtimeB > 60)
                                { 
                                    $closeDataFlag2=0;
                                    $doorOpen2Flag=1;
                                    $lat_tmp1 = explode("=",$lat_tmp[0]);  
                                    $lat = preg_replace('/"/', '', $lat_tmp1[1]);

                                    $lng_tmp1 = explode("=",$lng_tmp[0]);  
                                    $lng = preg_replace('/"/', '', $lng_tmp1[1]);

                                    $doorOpenArrB2['imeib'.$B2] =$vserial[$i];
                                    $doorOpenArrB2['vnameb'.$B2] =$vname;
                                    $doorOpenArrB2['latb'.$B2] =$lat;
                                    $doorOpenArrB2['lngb'.$B2] =$lng;
                                    $doorOpenArrB2['datefromb'.$B2] =$timeB1;
                                    $doorOpenArrB2['datetob'.$B2] =$timeB2;
                                    $doorOpenArrB2['door_openb'.$B2] =$runtimeB;
                                    $doorOpenArrB2['door_open_typeb'.$B2] ="DO2";									
                                    $B2++;
                                } 
                            }
                        }
                        if($parameterizeData->doorOpen3!=null)
                        {
                            $datetime = $xml_date;  
                            $door_count3 = $SortedDataObject->doorOpen3Data[$obi];   
                            //echo "door_count=".$door_count3."<br>";

                            if($door_count3>=250)
                            {
                                $continuous_running_flag3 = 1;
                            }

                            //if($door_count>350 && $StartFlag==0) 
                            if($door_count3>250 && $StartFlagB3==0)  
                            {                						
                                $timeB3 = $datetime;
                                $StartFlagB3 = 1;
                            }								
                            
                            else if(($door_count3<250 && $StartFlagB3==1) || ($continuous_running_flag3 ==1))   //500
                            {
                                $StartFlagB3 = 2;
                            }
                            //echo "doocount=".$door_count2." StartFlagB2=".$StartFlagB2." continuous_running_flag2=".$continuous_running_flag2."<br>";
                            $timeB4 = $datetime;
                            //echo "StartFlagB3=".$StartFlagB3."<br>";
                            if($StartFlagB3 == 2)
                            {									
                                $StartFlagB3=0;
                                $runtimeB = strtotime($timeB4) - strtotime($timeB3);
                                //echo "runtimeB=".$runtimeB."<br>";
                                if($runtimeB > 60)
                                { 
                                    //echo "in door open7";
                                    $closeDataFlag3=0;
                                    $doorOpen3Flag=1;
                                    $lat_tmp1 = explode("=",$lat_tmp[0]);  
                                    $lat = preg_replace('/"/', '', $lat_tmp1[1]);

                                    $lng_tmp1 = explode("=",$lng_tmp[0]);  
                                    $lng = preg_replace('/"/', '', $lng_tmp1[1]);

                                    $doorOpenArrB3['imeib'.$B3] =$vserial[$i];
                                    $doorOpenArrB3['vnameb'.$B3] =$vname;
                                    $doorOpenArrB3['latb'.$B3] =$lat;
                                    $doorOpenArrB3['lngb'.$B3] =$lng;
                                    $doorOpenArrB3['datefromb'.$B3] =$timeB3;
                                    $doorOpenArrB3['datetob'.$B3] =$timeB4;
                                    $doorOpenArrB3['door_openb'.$B3] =$runtimeB;
                                    $doorOpenArrB3['door_open_typeb'.$B3] ="DO3";
                                            //print_r($doorOpenArrB3);
                                    $B3++;
                                } 
                            }
                        }
                    }
                }
                if($StartFlag == 1)
                {
                    //echo "in StartFlag<br>";
                    $StartFlag=0;
                    $runtime = strtotime($time2) - strtotime($time1);
                    //echo "<br>runtime=".$runtime;
                    //$runhr_duration = strtotime($runtime);
                    if($runtime > 60)
                    {					
                        $lat_tmp1 = explode("=",$lat_tmp[0]);  
                        $lat = preg_replace('/"/', '', $lat_tmp1[1]);

                        $lng_tmp1 = explode("=",$lng_tmp[0]);  
                        $lng = preg_replace('/"/', '', $lng_tmp1[1]);
                        $closeDataFlag=0;
                        $doorOpenArrB1['imei'.$B1] =$vserial[$i];
                        $doorOpenArrB1['vname'.$B1] =$vname;
                        $doorOpenArrB1['lat'.$B1] =$lat;
                        $doorOpenArrB1['lng'.$B1] =$lng;
                        $doorOpenArrB1['datefrom'.$B1] =$time1;
                        $doorOpenArrB1['dateto'.$B1] =$time2;
                        $doorOpenArrB1['door_open'.$B1] =$runtime;
                        $doorOpenArrB1['door_open_type'.$B1] ="DO1";
                        $B1++;									
                    }
                }
                if($ioDoorOpen!="")
                {
                    if($StartFlagB2 == 1)
                    {
                        $StartFlagB2=0;			
                        $runtimeB = strtotime($timeB2) - strtotime($timeB1);
                        //echo "<br>runtime=".$runtime;
                        //$runhr_duration = strtotime($runtime);
                        if($runtimeB > 60)
                        {
                            $closeDataFlag2=0;
                            $doorOpen2Flag=1;
                            $lat_tmp1 = explode("=",$lat_tmp[0]);  
                            $lat = preg_replace('/"/', '', $lat_tmp1[1]);

                            $lng_tmp1 = explode("=",$lng_tmp[0]);  
                            $lng = preg_replace('/"/', '', $lng_tmp1[1]);

                            $doorOpenArrB2['imeib'.$B2] =$vserial[$i];
                            $doorOpenArrB2['vnameb'.$B2] =$vname;
                            $doorOpenArrB2['latb'.$B2] =$lat;
                            $doorOpenArrB2['lngb'.$B2] =$lng;						
                            $doorOpenArrB2['datefromb'.$B2] =$timeB1;
                            $doorOpenArrB2['datetob'.$B2] =$timeB2;
                            $doorOpenArrB2['door_openb'.$B2] =$runtimeB;
                            $doorOpenArrB2['door_open_typeb'.$B2] ="DO2";
                            //print_r($doorOpenArrB2);
                            $B2++;
                        } 
                    }
                }
                if($ioDoorOpen3!="")
                {				
                    if($StartFlagB3 == 1)
                    {
                        $StartFlagB3=0;			
                        $runtimeB = strtotime($timeB4) - strtotime($timeB3);
                        //echo "<br>runtime=".$runtime;
                        //$runhr_duration = strtotime($runtime);
                        if($runtimeB > 60)
                        {
                            //echo "in door open5<br>";
                            $closeDataFlag3=0;
                            $doorOpen3Flag=1;
                            $lat_tmp1 = explode("=",$lat_tmp[0]);  
                            $lat = preg_replace('/"/', '', $lat_tmp1[1]);

                            $lng_tmp1 = explode("=",$lng_tmp[0]);  
                            $lng = preg_replace('/"/', '', $lng_tmp1[1]);

                            $doorOpenArrB3['imeib'.$B3] =$vserial[$i];
                            $doorOpenArrB3['vnameb'.$B3] =$vname;
                            $doorOpenArrB3['latb'.$B3] =$lat;
                            $doorOpenArrB3['lngb'.$B3] =$lng;						
                            $doorOpenArrB3['datefromb'.$B3] =$timeB3;
                            $doorOpenArrB3['datetob'.$B3] =$timeB4;
                            $doorOpenArrB3['door_openb'.$B3] =$runtimeB;
                            $doorOpenArrB3['door_open_typeb'.$B3] ="DO3";
                            //print_r($doorOpenArrB3);
                            //echo "in door open7<br>";
                            //print_r($doorOpenArrB2);
                            $B3++;
                        } 
                    }				
                }
            }
            $SortedDataObject=null;
        }
        $parameterizeData->doorOpen1=null;
        $parameterizeData->doorOpen2=null;
        $parameterizeData->doorOpen3=null;		
    }
    
    for($k=0;$k<$size_feature_session;$k++)
    {
        //echo "feature_name_session=".$feature_name_session[$k]."<br>";
        if($feature_name_session[$k] == "door_open")
        {
            $door_open_first = 1;
        }

        if($feature_name_session[$k] == "door_open2")
        {
            $door_open_second = 1;
        }

        if($feature_name_session[$k] == "door_open3")
        {
            $door_open_third = 1;
        }
    }
    $m1=date('M',mktime(0,0,0,$month,1));
    echo'<center>';   
    report_title("Delivery Door",$date1,$date2);   
    echo'<div style="overflow: auto;height: 285px;" align="center">';			                      

    if(count($doorOpenArrB1)>0)
    {
        for($z=0;$z<$B1;$z++)
        {
            $imei[]=$doorOpenArrB1['imei'.$z];
            $vname[]=$doorOpenArrB1['vname'.$z];
            $latArr[]=$doorOpenArrB1['lat'.$z];
            $lngArr[]=$doorOpenArrB1['lng'.$z];
            $datefrom[]=$doorOpenArrB1['datefrom'.$z];
            $dateto[]=$doorOpenArrB1['dateto'.$z];
            $door_open[]=$doorOpenArrB1['door_open'.$z];
        }
    } 
    $j=-1;
    $k=0;
    $single_data_flag=1;		
    $do1cnt=count($imei);
    $type="halt";
    //echo "\nimei_size=".sizeof($imei)." ,door_open=".$door_open;
    echo"<table width='98%' border=0>
            <tr>";
            if((count($imei)>0) && ($door_open_first==1))
            { 
            echo"<td valign='top'><br>";
                for($i=0;$i<sizeof($imei);$i++)
                {	    						                  
                    if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
                    {
                        //echo "in if inf";
                        $k=0;                                              
                        $j++;
                        $sum_door_open =0;
                        $sum_door_close=0;
                        $total_door_open[$j] =0;
                        $sno = 1;
                        $title='Delivery Door : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
                        $vname1[$j][$k] = $vname[$i];
                        $imei1[$j][$k] = $imei[$i];

                        $noEntry=explode("@",$door_open[$i]);
                        $noEntryFlag=0;
                        //echo "noEntry".$noEntry."<br>";
                        if($noEntry[1]=="noneClose")
                        {	
                            //echo "in if";
                            $noEntryFlag=1;
                        echo'<table align="center">
                                <tr>
                                    <td class="text" align="center">
                                        <b>'.$title.'</b> <div style="height:8px;"></div>
                                    </td>
                                </tr>
                            </table>
                            <table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
                                <tr>
                                    <td class="text" align="left">
                                        <b>SNo</b>
                                    </td>
                                    <td class="text" align="left">
                                        <b>Start Time</b>
                                    </td>
                                    <td class="text" align="left">
                                        <b>End Time</b>
                                    </td>
                                    <td class="text" align="left">
                                        <b>Door Status (hr:min:sec)</b>
                                    </td>								
                                </tr>
                                <tr>
                                    <td class="text" align="left" width="4%">
                                        <b>'.$sno.'</b>
                                    </td>
                                    <td class="text" align="left">
                                        '.$date1.'
                                    </td>
                                    <td class="text" align="left">
                                        '.$date2.'
                                    </td>';
                                    $nms1 = secondsToTime($noEntry[0]);
                                    $nduration1 = $nms1[h].":".$nms1[m].":".$nms1[s];			
                                echo'<td class="text" align="left">
                                        &nbsp;<font color="green"><b>Close</b>&nbsp;['.$nduration1.']
                                    </td>
                                </tr>
                            </table>';	
                            $datefrom1[$j][$k] = $date1;	
                            $dateto1[$j][$k] = $date2;
                            $door_open1[$j][$k] = $door_open[$i];
                        }
                        else
                        {						
                        echo'<table align="center">
                                <tr>
                                    <td class="text" align="center">
                                        <b>'.$title.'</b> <div style="height:8px;"></div>
                                    </td>
                                </tr>
                            </table>
                            <table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
                                <tr>
                                    <td class="text" align="left">
                                        <b>SNo</b>
                                    </td>
                                    <td class="text" align="left">
                                        <b>Start Time</b>
                                    </td>
                                    <td class="text" align="left">
                                        <b>End Time</b>
                                    </td>
                                    <td class="text" align="left">
                                        <b>Door Status (hr:min:sec)</b>
                                    </td>								
                                </tr>';												
                            echo'<tr>
                                    <td class="text" align="left" width="4%">
                                        <b>'.$sno.'</b>
                                    </td>';        												
                                echo'<td class="text" align="left">
                                        '.$date1.'
                                    </td>
                                    <td class="text" align="left">
                                        '.$datefrom[$i].'
                                    </td>';
                                    $closeFirstDuration=strtotime($datefrom[$i])-strtotime($date1);	
                                    $sum_door_close = $sum_door_close + $closeFirstDuration;
                                    $fms1 = secondsToTime($closeFirstDuration);
                                    $fduration1 = $fms1[h].":".$fms1[m].":".$fms1[s];			
                                echo'<td class="text" align="left">
                                        &nbsp;<font color="green"><b>Close</b>&nbsp;['.$fduration1.']
                                    </td>
                                </tr>';
                                $sno++;
                            }
                        }
                        if($noEntryFlag==0)
                        {
                            $sum_door_open = $sum_door_open + $door_open[$i];							  
                        echo'<tr>
                                <td class="text" align="left" width="4%">
                                    <b>'.$sno.'</b>
                                </td>
                                <td class="text" align="left">
                                    '.$datefrom[$i].'
                                </td>
                                <td class="text" align="left">
                                    '.$dateto[$i].'
                                </td>';
                                $hms1 = secondsToTime($door_open[$i]);
                                $duration1 = $hms1[h].":".$hms1[m].":".$hms1[s];
                                $lt_tmp = substr($latArr[$i], 0, -1);
                                $lng_tmp = substr($lngArr[$i], 0, -1);
                            echo'<td class="text" align="left">
                                    <a href="javascript:map_window(\''.$vname[$i].'\',\''.$datefrom[$i].'\',\''.$dateto[$i].'\',\''.$lt_tmp.'\',\''.$lng_tmp.'\',\''.$type.'\');" style="text-decoration:none">
                                        &nbsp;<font color="red"><b>Open</b>&nbsp;['.$duration1.']
                                    </a>
                                </td>';					
                        echo'</tr>';
                            $doorCloseStartDate=$dateto[$i];
                            $nu=$i+1;
                            //echo "i=".$nu."<br>";
                            $doorCloseEndDate=$datefrom[$nu];
                            if($doorCloseEndDate!="")
                            {
                                $sno++;
                                if($doorCloseEndDate>$doorCloseStartDate)
                                {
                                    $closeDuration=strtotime($doorCloseEndDate)-strtotime($doorCloseStartDate);	
                                }
                                else
                                {
                                    $closeDuration=strtotime($doorCloseStartDate)-strtotime($doorCloseEndDate);
                                }
                                $sum_door_close = $sum_door_close + $closeDuration;		
                                $chms1 = secondsToTime($closeDuration);
                                $cduration1 = $chms1[h].":".$chms1[m].":".$chms1[s];	

                        echo"<tr>
                                <td class='text' align='left' width='4%'>
                                    <b>".$sno."</b>
                                </td>
                                <td class='text'>
                                    ".$doorCloseStartDate."
                                </td>
                                <td class='text'>
                                    ".$doorCloseEndDate."
                                </td>
                                <td class='text'>
                                    &nbsp;<font color='green'><b>Close</b>&nbsp;[".$cduration1."]
                                </td>
                            </tr>";
                            }				
                            $datefrom1[$j][$k] = $datefrom[$i];	
                            $dateto1[$j][$k] = $dateto[$i];										
                            $door_open1[$j][$k] = $duration1;

                            if($i==(sizeof($imei)-1))
                            {
                                $sno++;
                                $lastStartDate=$dateto[$i];
                                $lastEndDate=$date2;
                                $closeLastDuration=strtotime($lastEndDate)-strtotime($lastStartDate);	
                                $sum_door_close = $sum_door_close + $closeLastDuration;
                                $lms1 = secondsToTime($closeLastDuration);
                                $lduration1 = $lms1[h].":".$lms1[m].":".$lms1[s];
                            echo"<tr>
                                    <td class='text' align='left' width='4%'>
                                        <b>".$sno."</b>
                                    </td>
                                    <td class='text'>
                                        ".$lastStartDate."
                                    </td>
                                    <td class='text'>
                                        ".$lastEndDate."
                                    </td>
                                    <td class='text'>
                                        &nbsp;<font color='green'><b>Close</b>&nbsp;[".$lduration1."]
                                    </td>
                                </tr>";
                            }
                        //echo "imei1=".$imei[$i]."imei2=".$imei[$i+1]."i=".$i."<br>";
                        //if((($i>0) && ($imei[$i+1] != $imei[$i])))
                        if($imei[$i+1] != $imei[$i])
                        {       
                        //echo "<br>IN";
                        $single_data_flag = 0;
                        echo'<tr style="height:20px;background-color:lightgrey">
                                <td class="text">
                                    <strong>Total<strong>&nbsp;
                                </td>
                                <td class="text">
                                    <strong>'.$date1.'</strong>
                                </td>	
                                <td class="text">
                                    <strong>'.$date2.'</strong></td>';
                                    //if($k>1)
                                    {        
                                      $hms_2 = secondsToTime($sum_door_open);                   
                                      $total_door_open[$j] = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
                                      $chms_2 = secondsToTime($sum_door_close);                   
                                      $ctotal_door_open[$j] = $chms_2[h].":".$chms_2[m].":".$chms_2[s];
                                      //echo "<br>total_engine_runhr[j]=".$total_engine_runhr[$j]; 
                                    }
                            echo'<td class="text">
                                    <font color="red">
                                            <strong>Open->'.$total_door_open[$j].'</font>,<font color="green">close&nbsp;['.$ctotal_door_open[$j].']</strong>
                                    </font>
                                </td>
                            </tr>
                        </table><br>';        
                        $no_of_data[$j] = $k;
                        }
                    }
                    $k++;   
                    $sno++;                       							  		
                }
                if($noEntryFlag==0)
                {
                    //echo "in if";
                    if($single_data_flag)
                    {
                    // echo "in if1";
                    echo'<tr style="height:20px;background-color:lightgrey">
                            <td class="text">
                                <strong>Total<strong>&nbsp;
                            </td>
                            <td class="text">
                                <strong>'.$date1.'</strong>
                            </td>	
                            <td class="text">
                                <strong>'.$date2.'</strong>
                            </td>';							   
                            $hms_2 = secondsToTime($sum_door_open);
                            $total_door_open[$j] = $hms_2[h].":".$hms_2[m].":".$hms_2[s];

                        echo'<td class="text">
                                <font color="red">
                                    <strong>'.$total_door_open[$j].'</strong>
                                </font>
                            </td>';
                    echo'</tr>
                    </table>'; 
                    $no_of_data[$j] = $k; 
                    }
                }
                echo"</td>";
            }
					$imei=null;
					$vname=null;
					$datefrom=null;
					$dateto=null;
					$door_open=null;
					$type=null;
					$doorOpen2Flag;
                                        if(count($doorOpenArrB2)>0)
                                        {
                                            //$doorOpen2Size=sizeof($doorOpenArrB2);				
                                            for($y=0;$y<$B2;$y++)
                                            {
                                                $imei[]=$doorOpenArrB2['imeib'.$y];
                                                $vname[]=$doorOpenArrB2['vnameb'.$y];
                                                $latArr[]=$doorOpenArrB2['latb'.$y];
                                                $lngArr[]=$doorOpenArrB2['lngb'.$y];
                                                $datefrom[]=$doorOpenArrB2['datefromb'.$y];
                                                $dateto[]=$doorOpenArrB2['datetob'.$y];
                                                $door_open[]=$doorOpenArrB2['door_openb'.$y];
                                            }
                                        }
					//echo "dooropen2=".$doorOpen2Flag."<br>";
					if(($doorOpen2Flag==1 || $closeDataFlag2==1) && ($door_open_second==1))
					{
                                            if(count($imei)>0)
                                            {
							echo"<td valign='top'>";
							$j2=-1;
							$k2=0;
							$single_data_flag2=1;
							//print_r($imei);			
							//echo "\nimei_size=".sizeof($imei)." ,door_open=".$door_open;  
							for($i=0;$i<sizeof($imei);$i++)
							{	
								//echo "imei1=".$imei[$i]."<br>";
								if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
								{
									$k2=0;                                              
									$j2++;
									$sum_door_open2 =0;
									$sum_door_close2 =0;
									/*$sum_engine_runmin =0;
									$sum_engine_runsec =0; */
									$total_door_open2[$j2] =0;
									$total_door_close2[$j2] =0;

									$sno = 1;
									$title='Manhole Door : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
									$vname1[$j2][$k2] = $vname[$i];
									$imei1[$j2][$k2] = $imei[$i];
									$noEntry=explode("@",$door_open[$i]);
									$noEntryFlag=0;
									//echo "noEntry".$noEntry."<br>";
									if($noEntry[1]=="noneClose")
									{
										//echo "noEntry2".$noEntry."<br>";
										$noEntryFlag=1;
									echo'<table align="center">
											<tr>
												<td class="text" align="center">
													<b>'.$title.'</b> <div style="height:8px;"></div>
												</td>
											</tr>
										</table>
										<table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
											<tr>
												<td class="text" align="left">
													<b>SNo</b>
												</td>
												<td class="text" align="left">
													<b>Start Time</b>
												</td>
												<td class="text" align="left">
													<b>End Time</b>
												</td>
												<td class="text" align="left">
													<b>Door Status (hr:min:sec)</b>
												</td>								
											</tr>
											<tr>
												<td class="text" align="left" width="4%">
													<b>'.$sno.'</b>
												</td>
												<td class="text" align="left">
													'.$date1.'</td>
												<td class="text" align="left">
													'.$date2.'
												</td>';
												$nms1 = secondsToTime($noEntry[0]);
												$nduration1 = $nms1[h].":".$nms1[m].":".$nms1[s];			
											echo'<td class="text" align="left">
													&nbsp;<font color="green"><b>Close</b>&nbsp;['.$nduration1.']
												</td>
											</tr>
										</table>';	
										$datefrom2[$j2][$k2] = $date1;	
										$dateto2[$j2][$k2] = $date2;
										$door_open2[$j2][$k2] = $door_open[$i];
									}
									else
									{
									echo'<br>
										<table align="center">
											<tr>
												<td class="text" align="center">
													<b>'.$title.'</b> 
													<div style="height:8px;"></div>
												</td>
											</tr>
										</table>
										<table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
											<tr>
												<td class="text" align="left">
													<b>SNo</b>
												</td>
												<td class="text" align="left">
													<b>Start Time </b>
												</td>
												<td class="text" align="left">
													<b>End Time </b>
												</td>
												<td class="text" align="left">
													<b>Door Status (hr:min:sec)</b>
												</td>								
											</tr>';  
											echo'<tr>
													<td class="text" align="left" width="4%">
														<b>'.$sno.'</b>
													</td>
													<td class="text" align="left">
														'.$date1.'
													</td>
													<td class="text" align="left">
														'.$datefrom[$i].'
													</td>';
													$closeFirst1Duration=strtotime($datefrom[$i])-strtotime($date1);	
													$sum_door_close2 = $sum_door_close2 + $closeFirst1Duration;
													$f1ms1 = secondsToTime($closeFirst1Duration);
													$f1duration1 = $f1ms1[h].":".$f1ms1[m].":".$f1ms1[s];			
												echo'<td class="text" align="left">
														&nbsp;<font color="green"><b>Close</b>&nbsp;['.$f1duration1.']
													</td>
												</tr>';
											$sno++;
									}	
								}
								if($noEntryFlag==0)
								{					
									$sum_door_open2 = $sum_door_open2 + $door_open[$i];							  
									echo'<tr>
											<td class="text" align="left" width="4%">
												<b>'.$sno.'</b>
											</td>
											<td class="text" align="left">
												'.$datefrom[$i].'
											</td>
											<td class="text" align="left">
												'.$dateto[$i].'
											</td>';
											$hms1 = secondsToTime($door_open[$i]);
											$duration1 = $hms1[h].":".$hms1[m].":".$hms1[s];			
										echo'<td class="text" align="left">
												<a href="javascript:map_window(\''.$vname[$i].'\',\''.$datefrom[$i].'\',\''.$dateto[$i].'\',\''.$latArr[$i].'\',\''.$lngArr[$i].'\',\''.$type.'\');" style="text-decoration:none">
													&nbsp;<font color="red"><b>Open</b>&nbsp;['.$duration1.']</font>
												</a>
											</td>
										</tr>';
										$door2CloseStartDate=$dateto[$i];
										$nu=$i+1;
										//echo "i=".$nu."<br>";
										$door2CloseEndDate=$datefrom[$nu];
										if($door2CloseEndDate!="")
										{
											$sno++;
											if($door2CloseEndDate>$door2CloseStartDate)
											{
												$door2CloseDuration=strtotime($door2CloseEndDate)-strtotime($door2CloseStartDate);	
											}
											else
											{
												$door2CloseDuration=strtotime($door2CloseStartDate)-strtotime($door2CloseEndDate);	
											}
											
											$d2hms1 = secondsToTime($door2CloseDuration);
											$d2duration1 = $d2hms1[h].":".$d2hms1[m].":".$d2hms1[s];	
											$sum_door_close2 = $sum_door_close2 + $door2CloseDuration;	
											
									echo"<tr>
											<td class='text' align='left' width='4%'>
												<b>".$sno."</b>
											</td>
											<td class='text'>
												".$door2CloseStartDate."
											</td>
											<td class='text'>
												".$door2CloseEndDate."
											</td>
											<td class='text'>&nbsp;
												<font color='green'>
													<b>Close</b>
												</font>
												&nbsp;->[".$d2duration1."]
											</td>
										</tr>";
										}
										if($i==(sizeof($imei)-1))
										{
											$sno++;
											$last1StartDate=$dateto[$i];
											$last1EndDate=$date2;
											$closeLast1Duration=strtotime($last1EndDate)-strtotime($last1StartDate);	
											$sum_door_close2 = $sum_door_close2 + $closeLast1Duration;
											$l1ms1 = secondsToTime($closeLast1Duration);
											$l1duration1 = $l1ms1[h].":".$l1ms1[m].":".$l1ms1[s];
										echo"<tr>
												<td class='text' align='left' width='4%'>
													<b>".$sno."</b>
												</td>
												<td class='text'>
													".$last1StartDate."
												</td>
												<td class='text'>
													".$last1EndDate."
												</td>
												<td class='text'>&nbsp;
													<font color='green'><b>Close</b></font>
													&nbsp;[".$l1duration1."]
												</td>
											</tr>";
										}			
						
										$datefrom2[$j2][$k2] = $datefrom[$i];	
										$dateto2[$j2][$k2] = $dateto[$i];										
										$door_open2[$j2][$k2] = $duration1;
										//if((($i>0) && ($imei[$i+1] != $imei[$i])))
										if($imei[$i+1] != $imei[$i])
										{       
										//echo "<br>IN";
										$single_data_flag2 = 0;
										echo'<tr style="height:20px;background-color:lightgrey">
												<td class="text">
													<strong>Total<strong>&nbsp;
												</td>
												<td class="text">
													<strong>'.$date1.'</strong>
												</td>	
												<td class="text">
													<strong>'.$date2.'</strong>
												</td>';	
												$hms_2 = secondsToTime($sum_door_open2);                   
												$total_door_open2[$j2] = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
												$d2hms_2 = secondsToTime($sum_door_close2);                   
												$total_door_close2[$j2] = $d2hms_2[h].":".$d2hms_2[m].":".$d2hms_2[s];							
											echo'<td class="text">
													<font color="red">
														<strong>Open->'.$total_door_open2[$j2].'</font>,<font color="green">close &nbsp;['.$total_door_close2[$j2].']</strong>						
													</font>
												</td>';
										echo'</tr>'; 
									echo '</table><br>';        
									$no_of_data2[$j2] = $k2;
										}
								}
								//echo "<br>OUT";
								$k2++;   
								$sno++;                       							  		
							}
							if($noEntryFlag==0)
							{
								//echo "in if2";
								if($single_data_flag2)
								{
									echo'<tr style="height:20px;background-color:lightgrey">
											<td class="text">
												<strong>Total<strong>&nbsp;
											</td>
											<td class="text">
												<strong>'.$date1.'</strong>
											</td>	
											<td class="text">
												<strong>'.$date2.'</strong>
											</td>';	
											$hms_2 = secondsToTime($sum_door_open2);
											$total_door_open2[$j2] = $hms_2[h].":".$hms_2[m].":".$hms_2[s]; 
										echo'<td class="text">
												<font color="red">
													<strong>'.$total_door_open2[$j2].'</strong>
												</font>
											</td>';
									echo'</tr>
									</table><br>'; 
									$no_of_data2[$j2] = $k2; 
								}
							}
							echo"</td>";																																										
						}		
					}
					$imei=null;
					$vname=null;
					$datefrom=null;
					$dateto=null;
					$door_open=null;
					$type=null;
					$doorOpen3Flag;
                                        if(count($doorOpenArrB3)>0)
                                        {
                                            //$doorOpen2Size=sizeof($doorOpenArrB2);				
                                            for($y=0;$y<$B3;$y++)
                                            {
                                                 $imei[]=$doorOpenArrB3['imeib'.$y];
                                                $vname[]=$doorOpenArrB3['vnameb'.$y];
                                                $latArr[]=$doorOpenArrB3['latb'.$y];
                                                $lngArr[]=$doorOpenArrB3['lngb'.$y];
                                                $datefrom[]=$doorOpenArrB3['datefromb'.$y];
                                                $dateto[]=$doorOpenArrB3['datetob'.$y];
                                                $door_open[]=$doorOpenArrB3['door_openb'.$y];
                                            }
                                        }
					//echo "dooropen2=".$doorOpen2Flag."<br>";
					if(($doorOpen3Flag==1 || $closeDataFlag3==1) && ($door_open_third==1))
					{
						if(count($imei)>0)
						{
							echo"<td valign='top'>";
							$j3=-1;
							$k3=0;
							$single_data_flag3=1;
							//print_r($imei);			
							//echo "\nimei_size=".sizeof($imei)." ,door_open=".$door_open;  
							for($i=0;$i<sizeof($imei);$i++)
							{	
								//echo "imei1=".$imei[$i]."<br>";
								if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
								{
									$k3=0;                                              
									$j3++;
									$sum_door_open3 =0;
									$sum_door_close3 =0;
									/*$sum_engine_runmin =0;
									$sum_engine_runsec =0; */
									$total_door_open3[$j3] =0;
									$total_door_close3[$j3] =0;

									$sno = 1;
									$title='Manhole Door2 : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
									$vname3[$j3][$k3] = $vname[$i];
									$imei3[$j3][$k3] = $imei[$i];
									$noEntry=explode("@",$door_open[$i]);
									$noEntryFlag=0;
									//echo "noEntry".$noEntry."<br>";
									if($noEntry[1]=="noneClose")
									{
										//echo "noEntry2".$noEntry."<br>";
										$noEntryFlag=1;
									echo'<table align="center">
											<tr>
												<td class="text" align="center">
													<b>'.$title.'</b> <div style="height:8px;"></div>
												</td>
											</tr>
										</table>
										<table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
											<tr>
												<td class="text" align="left">
													<b>SNo</b>
												</td>
												<td class="text" align="left">
													<b>Start Time</b>
												</td>
												<td class="text" align="left">
													<b>End Time</b>
												</td>
												<td class="text" align="left">
													<b>Door Status (hr:min:sec)</b>
												</td>								
											</tr>
											<tr>
												<td class="text" align="left" width="4%">
													<b>'.$sno.'</b>
												</td>
												<td class="text" align="left">
													'.$date1.'</td>
												<td class="text" align="left">
													'.$date2.'
												</td>';
												$nms3 = secondsToTime($noEntry[0]);
												$nduration3 = $nms3[h].":".$nms3[m].":".$nms3[s];			
											echo'<td class="text" align="left">
													&nbsp;<font color="green"><b>Close</b>&nbsp;['.$nduration3.']
												</td>
											</tr>
										</table>';	
										$datefrom3[$j3][$k3] = $date1;	
										$dateto3[$j3][$k3] = $date2;
										$door_open3[$j3][$k3] = $door_open[$i];
									}
									else
									{
									echo'<br>
										<table align="center">
											<tr>
												<td class="text" align="center">
													<b>'.$title.'</b> 
													<div style="height:8px;"></div>
												</td>
											</tr>
										</table>
										<table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
											<tr>
												<td class="text" align="left">
													<b>SNo</b>
												</td>
												<td class="text" align="left">
													<b>Start Time </b>
												</td>
												<td class="text" align="left">
													<b>End Time </b>
												</td>
												<td class="text" align="left">
													<b>Door Status (hr:min:sec)</b>
												</td>								
											</tr>';  
											echo'<tr>
													<td class="text" align="left" width="4%">
														<b>'.$sno.'</b>
													</td>
													<td class="text" align="left">
														'.$date1.'
													</td>
													<td class="text" align="left">
														'.$datefrom[$i].'
													</td>';
													$closeFirst3Duration=strtotime($datefrom[$i])-strtotime($date1);	
													$sum_door_close3 = $sum_door_close3 + $closeFirst3Duration;
													$f1ms3 = secondsToTime($closeFirst3Duration);
													$f1duration3 = $f1ms3[h].":".$f1ms3[m].":".$f1ms3[s];			
												echo'<td class="text" align="left">
														&nbsp;<font color="green"><b>Close</b>&nbsp;['.$f1duration3.']
													</td>
												</tr>';
											$sno++;
									}	
								}
								if($noEntryFlag==0)
								{					
									$sum_door_open3 = $sum_door_open3 + $door_open[$i];							  
									echo'<tr>
											<td class="text" align="left" width="4%">
												<b>'.$sno.'</b>
											</td>
											<td class="text" align="left">
												'.$datefrom[$i].'
											</td>
											<td class="text" align="left">
												'.$dateto[$i].'
											</td>';
											$hms3 = secondsToTime($door_open[$i]);
											$duration3 = $hms3[h].":".$hms3[m].":".$hms3[s];			
										echo'<td class="text" align="left">
												<a href="javascript:map_window(\''.$vname[$i].'\',\''.$datefrom[$i].'\',\''.$dateto[$i].'\',\''.$latArr[$i].'\',\''.$lngArr[$i].'\',\''.$type.'\');" style="text-decoration:none">
													&nbsp;<font color="red"><b>Open</b>&nbsp;['.$duration3.']</font>
												</a>
											</td>
										</tr>';
										$door3CloseStartDate=$dateto[$i];
										$nu=$i+1;
										//echo "i=".$nu."<br>";
										$door3CloseEndDate=$datefrom[$nu];
										if($door3CloseEndDate!="")
										{
											$sno++;
											if($door3CloseEndDate>$door3CloseStartDate)
											{
												$door3CloseDuration=strtotime($door3CloseEndDate)-strtotime($door3CloseStartDate);	
											}
											else
											{
												$door3CloseDuration=strtotime($door3CloseStartDate)-strtotime($door3CloseEndDate);	
											}
											
											$d2hms3 = secondsToTime($door3CloseDuration);
											$d2duration3 = $d2hms3[h].":".$d2hms3[m].":".$d2hms3[s];	
											$sum_door_close3 = $sum_door_close3 + $door3CloseDuration;	
											
									echo"<tr>
											<td class='text' align='left' width='4%'>
												<b>".$sno."</b>
											</td>
											<td class='text'>
												".$door3CloseStartDate."
											</td>
											<td class='text'>
												".$door3CloseEndDate."
											</td>
											<td class='text'>&nbsp;
												<font color='green'>
													<b>Close</b>
												</font>
												&nbsp;->[".$d2duration3."]
											</td>
										</tr>";
										}
										if($i==(sizeof($imei)-1))
										{
											$sno++;
											$last3StartDate=$dateto[$i];
											$last3EndDate=$date2;
											$closeLast3Duration=strtotime($last3EndDate)-strtotime($last3StartDate);	
											$sum_door_close3 = $sum_door_close3 + $closeLast3Duration;
											$l1ms3 = secondsToTime($closeLast3Duration);
											$l1duration3 = $l1ms3[h].":".$l1ms3[m].":".$l1ms3[s];
										echo"<tr>
												<td class='text' align='left' width='4%'>
													<b>".$sno."</b>
												</td>
												<td class='text'>
													".$last3StartDate."
												</td>
												<td class='text'>
													".$last3EndDate."
												</td>
												<td class='text'>&nbsp;
													<font color='green'><b>Close</b></font>
													&nbsp;[".$l1duration3."]
												</td>
											</tr>";
										}			
						
										$datefrom3[$j3][$k3] = $datefrom[$i];	
										$dateto3[$j3][$k3] = $dateto[$i];										
										$door_open3[$j3][$k3] = $duration1;
										//if((($i>0) && ($imei[$i+1] != $imei[$i])))
										if($imei[$i+1] != $imei[$i])
										{       
										//echo "<br>IN";
										$single_data_flag3 = 0;
										echo'<tr style="height:20px;background-color:lightgrey">
												<td class="text">
													<strong>Total<strong>&nbsp;
												</td>
												<td class="text">
													<strong>'.$date1.'</strong>
												</td>	
												<td class="text">
													<strong>'.$date2.'</strong>
												</td>';	
												$hms_3 = secondsToTime($sum_door_open3);                   
												$total_door_open3[$j3] = $hms_3[h].":".$hms_3[m].":".$hms_3[s];
												$d2hms_3 = secondsToTime($sum_door_close3);                   
												$total_door_close3[$j3] = $d2hms_3[h].":".$d2hms_3[m].":".$d2hms_3[s];							
											echo'<td class="text">
													<font color="red">
														<strong>Open->'.$total_door_open3[$j3].'</font>,<font color="green">close &nbsp;['.$total_door_close3[$j3].']</strong>						
													</font>
												</td>';
										echo'</tr>'; 
									echo '</table><br>';        
									$no_of_data3[$j3] = $k3;
										}
								}
								//echo "<br>OUT";
								$k3++;   
								$sno++;                       							  		
							}
							if($noEntryFlag==0)
							{
								//echo "in if2";
								if($single_data_flag3)
								{
									echo'<tr style="height:20px;background-color:lightgrey">
											<td class="text">
												<strong>Total<strong>&nbsp;
											</td>
											<td class="text">
												<strong>'.$date1.'</strong>
											</td>	
											<td class="text">
												<strong>'.$date2.'</strong>
											</td>';	
											$hms_3 = secondsToTime($sum_door_open3);
											$total_door_open3[$j3] = $hms_3[h].":".$hms_3[m].":".$hms_3[s]; 
										echo'<td class="text">
												<font color="red">
													<strong>'.$total_door_open3[$j3].'</strong>
												</font>
											</td>';
									echo'</tr>
									</table><br>'; 
									$no_of_data3[$j3] = $k3; 
								}
							}
							echo"</td>";																																										
						}
		
					}
					
			echo "</tr>
		</table>
	</div>";
	echo '
		<table align="center" border=0>
			<tr>';
				
				if(($do1cnt>0) && ($door_open_first==1))
				{
				echo'<td>						                
						<input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">
					</td>
					<td>
					&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
					</td>';
				}
				if(($doorOpen2Flag==1 || $closeDataFlag2==1) && ($door_open_second==1))
				{
				echo'<td>					               
					<input type="button" onclick="javascript:report_csv_2(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF 2" class="noprint">
				</td>
				<td>
				&nbsp;<input type="button" onclick="javascript:report_csv_2(\'src/php/report_csv_2.php\');" value="Get CSV 2" class="noprint">&nbsp;
				</td>';
				}
				
				if(($doorOpen3Flag==1 || $closeDataFlag3==1) && ($door_open_third==1))
				{
				echo'<td>					               
					<input type="button" onclick="javascript:report_csv_3(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF 3" class="noprint">
				</td>
				<td>
				&nbsp;<input type="button" onclick="javascript:report_csv_3(\'src/php/report_csv_3.php\');" value="Get CSV 3" class="noprint">&nbsp;
				</td>';
				}
			echo'</tr>
			</table>';  
	
	echo'<form method = "post" target="_blank">';	
			$csv_string = "";
			//echo "<br>j=".$j;	
			$pui=1;
			for($x=0;$x<=$j;$x++)
			{								
				$title = $vname1[$x][0]." (".$imei1[$x][0]."): Delivery Door- From DateTime : ".$date1."-".$date2;
				$csv_string = $csv_string.$title."\n";
				$csv_string = $csv_string."SNo,Start Time From,End Time,Door Status(hrs.min)\n";
				echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
				
				$sno=0;
				//echo "<br>noofdata=".$no_of_data[$x];
				
				for($y=0;$y<=$no_of_data[$x];$y++)
				{
					$pdfDoorOpen=explode("@",$door_open1[$x][$y]);
					//print_r($pdfDoorOpen);
					//echo "pdfDoorOpen=".$pdfDoorOpen[1]."<br>";
					$pdfNoEntry=0;
					if($pdfDoorOpen[1]=='noneClose') //////////// data is coming no any door open found
					{
						//echo "in if";
						$pdfNoEntry=1;
						$sno++;	
						$datetmp1 = $datefrom1[$x][$y];	
						$datetmp2 = $dateto1[$x][$y];

						$ncDuration=strtotime($datetmp2)-strtotime($datetmp1);
						$nchms1 = secondsToTime($ncDuration); ///f ->first c->close d->duration 
						$ncduration = "Close [".$nchms1[h].":".$nchms1[m].":".$nchms1[s]."]";
				
						//echo "<br>x=".$x." ,y=".$y." ,temp_runhr=".$engine_runhr_tmp;
						//echo "dt=".$datetmp1;								
						echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][Start Time]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][End Time]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$ncduration\" NAME=\"temp[$x][$y][Door Status (hr:min:sec)]\">";
						$csv_string = $csv_string.$sno.','.$datetmp1.','.$datetmp2.','.$ncduration."\n"; 
					}
					//$k=$j-1;
					if($pdfNoEntry==0)
					{
						$sno++;	
						$datetmp1 = $datefrom1[$x][$y];	
						$datetmp2 = $dateto1[$x][$y];										
						$door_open_tmp = "Open [".$door_open1[$x][$y]."]";
						//echo "<br>x=".$x." ,y=".$y." ,temp_runhr=".$engine_runhr_tmp;
						//echo "dt=".$datetmp1;	
						
						/////// this is first close entry ///////////////////
						if($y==0)
						{
							$firstCloseDuration=strtotime($datetmp1)-strtotime($date1);
							$fcdhms1 = secondsToTime($firstCloseDuration); ///f ->first c->close d->duration 
							$fcd1 = "Close [".$fcdhms1[h].":".$fcdhms1[m].":".$fcdhms1[s]."]";
							
							echo"<input TYPE=\"hidden\" VALUE=\"$pui\" NAME=\"temp[$x][$pui][SNo]\">"; 
							echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$pui][Start Time]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$pui][End Time]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$fcd1\" NAME=\"temp[$x][$pui][Door Status (hr:min:sec)]\">";
							$csv_string = $csv_string.$pui.','.$datetmp1.','.$datetmp2.','.$fcd1."\n"; 
						}
						
						///////////////////////////////////
						$pui++;
						echo"<input TYPE=\"hidden\" VALUE=\"$pui\" NAME=\"temp[$x][$pui][SNo]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$pui][Start Time]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$pui][End Time]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$door_open_tmp\" NAME=\"temp[$x][$pui][Door Status (hr:min:sec)]\">";
						$csv_string = $csv_string.$pui.','.$datetmp1.','.$datetmp2.','.$door_open_tmp."\n"; 
						$hiddenCloseStartDate=$dateto1[$x][$y];
						$hnu=$y+1;
						//echo "i=".$nu."<br>";
						$hiddenCloseEndDate=$datefrom1[$x][$hnu];
						//echo "a1=".$hiddenCloseStartDate." a2=".$hiddenCloseEndDate."<br>";
						if($hiddenCloseEndDate!="")
						{
							//$tmps=$sno+1;
							$sno++;
							$pui++;
							$hiddenCloseDuration=strtotime($hiddenCloseEndDate)-strtotime($hiddenCloseStartDate);	
							$hidhms1 = secondsToTime($hiddenCloseDuration);
							$hduration1 = "Close [".$hidhms1[h].":".$hidhms1[m].":".$hidhms1[s]."]";							
							echo"<input TYPE=\"hidden\" VALUE=\"$pui\" NAME=\"temp[$x][$pui][SNo]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$hiddenCloseStartDate\" NAME=\"temp[$x][$pui][Start Time]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$hiddenCloseEndDate\" NAME=\"temp[$x][$pui][End Time]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$hduration1\" NAME=\"temp[$x][$pui][Door Status (hr:min:sec)]\">";
							$csv_string = $csv_string.$pui.','.$hiddenCloseStartDate.','.$hiddenCloseEndDate.','.$hduration1."\n";
							//$m = $y+1;
						}
						if($y==($no_of_data[$x]))
						{
							$pui++;
							
							$displayDate=$dateto1[$x][$y];
							$lastCloseDuration=strtotime($date2)-strtotime($dateto1[$x][$y]);							
							$lcdhms1 = secondsToTime($lastCloseDuration); ///f ->first c->close d->duration 
							$lcd1 = "Close [".$lcdhms1[h].":".$lcdhms1[m].":".$lcdhms1[s]."]";
							
							$hiddenCloseDuration=strtotime($hiddenCloseEndDate)-strtotime($hiddenCloseStartDate);	
							$hidhms1 = secondsToTime($hiddenCloseDuration);
							$hduration1 = "Close [".$hidhms1[h].":".$hidhms1[m].":".$hidhms1[s]."]";							
							echo"<input TYPE=\"hidden\" VALUE=\"$pui\" NAME=\"temp[$x][$pui][SNo]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$displayDate\" NAME=\"temp[$x][$pui][Start Time]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$pui][End Time]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$lcd1\" NAME=\"temp[$x][$pui][Door Status (hr:min:sec)]\">";
							$csv_string = $csv_string.$pui.','.$dateto1[$x][$y].','.$date2.','.$lcd1."\n";
							//$m = $y+1;
						}
					}																								
				}
				if($pdfNoEntry==0)
				{
					$pui++;
					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$pui][SNo]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$pui][Start Time]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$pui][End Time]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$pui][Door Status (hr:min:sec)]\">";	
					$pui++;								
					$finalTotalDoorOpen="Open ".$total_door_open[$x]." Close ".$ctotal_door_open[$x]."";
					echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$pui][SNo]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$pui][Start Time]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$pui][End Time]\">";
					echo"<input TYPE=\"hidden\" VALUE=\"$finalTotalDoorOpen\" NAME=\"temp[$x][$pui][Door Status (hr:min:sec)]\">";
					$csv_string = $csv_string."\nTotal,".$date1.",".$date2.",".$finalTotalDoorOpen."\n\n";  
				}
			}
				echo'<input TYPE="hidden" VALUE="door_open" NAME="csv_type">
				<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">
			</form>';
				
				
		if($doorOpen2Flag==1 || $closeDataFlag2==1)
		{
		echo'<form method="post" target="_blank">';	
				$csv_string1 = "";
				//echo "<br>j=".$j;	
				$puia=1;
				for($x=0;$x<=$j2;$x++)
				{								
					$title = $vname1[$x][0]." (".$imei1[$x][0]."): Manhole Door- From DateTime : ".$date1."-".$date2;
					$csv_string1 = $csv_string1.$title."\n";
					$csv_string1 = $csv_string1."SNo,Start Time From,End Time,Door Open(hrs.min)\n";
					echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
					
					$sno=0;
					//echo "<br>noofdata=".$no_of_data[$x];
					
					for($y=0;$y<=$no_of_data2[$x];$y++)
					{
						$pdfDoor2Open=explode("@",$door_open2[$x][$y]);
						//print_r($pdfDoor2Open);
						$pdfNo2Entry=0;
						if($pdfDoor2Open[1]=='noneClose') //////////// data is coming no any door open found
						{
							$pdfNo2Entry=1;
							$sno++;	
							$datetmp1 = $datefrom2[$x][$y];	
							$datetmp2 = $dateto2[$x][$y];

							$nc1Duration=strtotime($datetmp2)-strtotime($datetmp1);
							$nc1hms1 = secondsToTime($nc1Duration); ///f ->first c->close d->duration 
							$nc1aduration = "Close [".$nc1hms1[h].":".$nc1hms1[m].":".$nc1hms1[s]."]";
							
							echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo1]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][Start Time1]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][End Time1]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$nc1aduration\" NAME=\"temp[$x][$y][Door Open1 (hr:min:sec)]\">";
					  
							$csv_string1 = $csv_string1.$sno.','.$datetmp1.','.$datetmp2.','.$nc1aduration."\n"; 
						}
						//$k=$j-1;
						if($pdfNo2Entry==0)
						{
							$sno++;								
							$datetmp1 = $datefrom2[$x][$y];	
							$datetmp2 = $dateto2[$x][$y];										
							$door_open_tmp ="Open [".$door_open2[$x][$y]."]";
							//echo "door_open_tmp=".$door_open_tmp."<br>";
							//echo "<br>x=".$x." ,y=".$y." ,temp_runhr=".$engine_runhr_tmp;
							
							if($y==0)
							{
								$firstCloseDuration=strtotime($datetmp1)-strtotime($date1);
								$fcdhms1 = secondsToTime($firstCloseDuration); ///f ->first c->close d->duration 
								$fcd1 = "Close [".$fcdhms1[h].":".$fcdhms1[m].":".$fcdhms1[s]."]";								
								echo"<input TYPE=\"hidden\" VALUE=\"$puia\" NAME=\"temp[$x][$puia][SNo1]\">"; 
								echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$puia][Start Time1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$puia][End Time1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$fcd1\" NAME=\"temp[$x][$puia][Door Status1 (hr:min:sec)]\">";
								$csv_string1 = $csv_string1.$puia.','.$datetmp1.','.$datetmp2.','.$fcd1."\n"; 
							}
							
							$puia++;
							//echo "dt=".$datetmp1;								
							echo"<input TYPE=\"hidden\" VALUE=\"$puia\" NAME=\"temp[$x][$puia][SNo1]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$puia][Start Time1]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$puia][End Time1]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$door_open_tmp\" NAME=\"temp[$x][$puia][Door Status1 (hr:min:sec)]\">";
					  
							$csv_string1 = $csv_string1.$puia.','.$datetmp1.','.$datetmp2.','.$door_open_tmp."\n"; 
							$hidden1CloseStartDate=$dateto2[$x][$y];
							$h1nu=$y+1;
							//echo "i=".$nu."<br>";
							$hidden1CloseEndDate=$datefrom2[$x][$h1nu];
							//echo "hidden1CloseEndDate=".$hidden1CloseEndDate."<br>";
							if($hidden1CloseEndDate!="")
							{	
								//$sno++;
								$puia++;
								$hidden1CloseDuration=strtotime($hidden1CloseEndDate)-strtotime($hidden1CloseStartDate);	
								$hid1hms1 = secondsToTime($hidden1CloseDuration);
								$h1duration1 = "Close [".$hid1hms1[h].":".$hid1hms1[m].":".$hid1hms1[s]."]";	
									
								echo"<input TYPE=\"hidden\" VALUE=\"$puia\" NAME=\"temp[$x][$puia][SNo1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$hidden1CloseStartDate\" NAME=\"temp[$x][$puia][Start Time1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$hidden1CloseEndDate\" NAME=\"temp[$x][$puia][End Time1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$h1duration1\" NAME=\"temp[$x][$puia][Door Status1 (hr:min:sec)]\">";
								$csv_string1 = $csv_string1.$puia.','.$hidden1CloseStartDate.','.$hidden1CloseEndDate.','.$h1duration1."\n";
							}
							if($y==($no_of_data2[$x]))
							{
								$puia++;
								
								$displayDate=$dateto2[$x][$y];
								$lastCloseDuration=strtotime($date2)-strtotime($dateto2[$x][$y]);							
								$lcdhms1 = secondsToTime($lastCloseDuration); ///f ->first c->close d->duration 
								$lcd1 = "Close [".$lcdhms1[h].":".$lcdhms1[m].":".$lcdhms1[s]."]";
								
								$hiddenCloseDuration=strtotime($hiddenCloseEndDate)-strtotime($hiddenCloseStartDate);	
								$hidhms1 = secondsToTime($hiddenCloseDuration);
								$hduration1 = "Close [".$hidhms1[h].":".$hidhms1[m].":".$hidhms1[s]."]";							
								echo"<input TYPE=\"hidden\" VALUE=\"$puia\" NAME=\"temp[$x][$puia][SNo1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$displayDate\" NAME=\"temp[$x][$puia][Start Time1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$puia][End Time1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$lcd1\" NAME=\"temp[$x][$puia][Door Status1 (hr:min:sec)]\">";
								$csv_string1 = $csv_string1.$puia.','.$dateto2[$x][$y].','.$date2.','.$lcd1."\n";
								//$m = $y+1;
							}
						}
					}		
					if($pdfNo2Entry==0)
					{
						$puia++;
						echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$puia][SNo1]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$puia][Start Time1]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$puia][End Time1]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$puia][Door Status1 (hr:min:sec)]\">";									
						$puia++;
												
						$final1TotalDoorOpen="Open [".$total_door_open2[$x]."]=>Close [".$total_door_close2[$x]."]";
						//echo "final1TotalDoorOpen=".$final1TotalDoorOpen."<br>";
						echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$puia][SNo1]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$puia][Start Time1]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$puia][End Time1]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$final1TotalDoorOpen\" NAME=\"temp[$x][$puia][Door Status1 (hr:min:sec)]\">";
						$csv_string1 = $csv_string1."\nTotal,".$date1.",".$date2.",".$final1TotalDoorOpen."\n\n";  
					}
				}
				echo '<input TYPE="hidden" VALUE="door_open2" NAME="csv_type2">
					<input TYPE="hidden" VALUE="'.$csv_string1.'" NAME="csv_string2"> 
				</form>';
		}
		if($doorOpen3Flag==1 || $closeDataFlag3==1)
		{
		echo'<form method="post" target="_blank">';	
				$csv_string3 = "";
				//echo "<br>j=".$j;	
				$puia=1;
				for($x=0;$x<=$j3;$x++)
				{								
					$title = $vname3[$x][0]." (".$imei3[$x][0]."): Manhole Door- From DateTime : ".$date1."-".$date2;
					$csv_string3 = $csv_string3.$title."\n";
					$csv_string3 = $csv_string3."SNo,Start Time From,End Time,Door Open(hrs.min)\n";
					echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
					
					$sno=0;
					//echo "<br>noofdata=".$no_of_data[$x];
					
					for($y=0;$y<=$no_of_data3[$x];$y++)
					{
						$pdfDoor3Open=explode("@",$door_open3[$x][$y]);
						//print_r($pdfDoor2Open);
						$pdfNo3Entry=0;
						if($pdfDoor3Open[1]=='noneClose') //////////// data is coming no any door open found
						{
							$pdfNo3Entry=1;
							$sno++;	
							$datetmp1 = $datefrom3[$x][$y];	
							$datetmp2 = $dateto3[$x][$y];

							$nc1Duration=strtotime($datetmp2)-strtotime($datetmp1);
							$nc1hms1 = secondsToTime($nc1Duration); ///f ->first c->close d->duration 
							$nc1aduration = "Close [".$nc1hms1[h].":".$nc1hms1[m].":".$nc1hms1[s]."]";
							
							echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo1]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][Start Time1]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][End Time1]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$nc1aduration\" NAME=\"temp[$x][$y][Door Open1 (hr:min:sec)]\">";
					  
							$csv_string3 = $csv_string3.$sno.','.$datetmp1.','.$datetmp2.','.$nc1aduration."\n"; 
						}
						//$k=$j-1;
						if($pdfNo3Entry==0)
						{
							$sno++;								
							$datetmp1 = $datefrom3[$x][$y];	
							$datetmp2 = $dateto3[$x][$y];										
							$door_open_tmp ="Open [".$door_open3[$x][$y]."]";
							//echo "door_open_tmp=".$door_open_tmp."<br>";
							//echo "<br>x=".$x." ,y=".$y." ,temp_runhr=".$engine_runhr_tmp;
							
							if($y==0)
							{
								$firstCloseDuration=strtotime($datetmp1)-strtotime($date1);
								$fcdhms1 = secondsToTime($firstCloseDuration); ///f ->first c->close d->duration 
								$fcd1 = "Close [".$fcdhms1[h].":".$fcdhms1[m].":".$fcdhms1[s]."]";								
								echo"<input TYPE=\"hidden\" VALUE=\"$puia\" NAME=\"temp[$x][$puia][SNo1]\">"; 
								echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$puia][Start Time1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$puia][End Time1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$fcd1\" NAME=\"temp[$x][$puia][Door Status1 (hr:min:sec)]\">";
								$csv_string3 = $csv_string3.$puia.','.$datetmp1.','.$datetmp2.','.$fcd1."\n"; 
							}
							
							$puia++;
							//echo "dt=".$datetmp1;								
							echo"<input TYPE=\"hidden\" VALUE=\"$puia\" NAME=\"temp[$x][$puia][SNo1]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$puia][Start Time1]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$puia][End Time1]\">";
							echo"<input TYPE=\"hidden\" VALUE=\"$door_open_tmp\" NAME=\"temp[$x][$puia][Door Status1 (hr:min:sec)]\">";
					  
							$csv_string3 = $csv_string3.$puia.','.$datetmp1.','.$datetmp2.','.$door_open_tmp."\n"; 
							$hidden1CloseStartDate=$dateto3[$x][$y];
							$h1nu=$y+1;
							//echo "i=".$nu."<br>";
							$hidden1CloseEndDate=$datefrom3[$x][$h1nu];
							//echo "hidden1CloseEndDate=".$hidden1CloseEndDate."<br>";
							if($hidden1CloseEndDate!="")
							{	
								//$sno++;
								$puia++;
								$hidden1CloseDuration=strtotime($hidden1CloseEndDate)-strtotime($hidden1CloseStartDate);	
								$hid1hms1 = secondsToTime($hidden1CloseDuration);
								$h1duration1 = "Close [".$hid1hms1[h].":".$hid1hms1[m].":".$hid1hms1[s]."]";	
									
								echo"<input TYPE=\"hidden\" VALUE=\"$puia\" NAME=\"temp[$x][$puia][SNo1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$hidden1CloseStartDate\" NAME=\"temp[$x][$puia][Start Time1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$hidden1CloseEndDate\" NAME=\"temp[$x][$puia][End Time1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$h1duration1\" NAME=\"temp[$x][$puia][Door Status1 (hr:min:sec)]\">";
								$csv_string3 = $csv_string3.$puia.','.$hidden1CloseStartDate.','.$hidden1CloseEndDate.','.$h1duration1."\n";
							}
							if($y==($no_of_data3[$x]))
							{
								$puia++;
								
								$displayDate=$dateto3[$x][$y];
								$lastCloseDuration=strtotime($date2)-strtotime($dateto3[$x][$y]);							
								$lcdhms1 = secondsToTime($lastCloseDuration); ///f ->first c->close d->duration 
								$lcd1 = "Close [".$lcdhms1[h].":".$lcdhms1[m].":".$lcdhms1[s]."]";
								
								$hiddenCloseDuration=strtotime($hiddenCloseEndDate)-strtotime($hiddenCloseStartDate);	
								$hidhms1 = secondsToTime($hiddenCloseDuration);
								$hduration1 = "Close [".$hidhms1[h].":".$hidhms1[m].":".$hidhms1[s]."]";							
								echo"<input TYPE=\"hidden\" VALUE=\"$puia\" NAME=\"temp[$x][$puia][SNo1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$displayDate\" NAME=\"temp[$x][$puia][Start Time1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$puia][End Time1]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$lcd1\" NAME=\"temp[$x][$puia][Door Status1 (hr:min:sec)]\">";
								$csv_string3 = $csv_string3.$puia.','.$dateto2[$x][$y].','.$date2.','.$lcd1."\n";
								//$m = $y+1;
							}
						}
					}		
					if($pdfNo3Entry==0)
					{
						$puia++;
						echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$puia][SNo1]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$puia][Start Time1]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$puia][End Time1]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$puia][Door Status1 (hr:min:sec)]\">";									
						$puia++;
												
						$final1TotalDoorOpen="Open [".$total_door_open3[$x]."]=>Close [".$total_door_close3[$x]."]";
						//echo "final1TotalDoorOpen=".$final1TotalDoorOpen."<br>";
						echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$puia][SNo1]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$puia][Start Time1]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$puia][End Time1]\">";
						echo"<input TYPE=\"hidden\" VALUE=\"$final1TotalDoorOpen\" NAME=\"temp[$x][$puia][Door Status1 (hr:min:sec)]\">";
						$csv_string3 = $csv_string3."\nTotal,".$date1.",".$date2.",".$final1TotalDoorOpen."\n\n";  
					}
				}
				echo '<input TYPE="hidden" VALUE="door_open3" NAME="csv_type3">
					<input TYPE="hidden" VALUE="'.$csv_string3.'" NAME="csv_string3"> 
				</form>';
		}  
include("map_window/floating_map_window.php");
	echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>'; 
?> 
					 					

	
	