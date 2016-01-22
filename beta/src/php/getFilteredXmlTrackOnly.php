<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
//set_time_limit(3000);
set_time_limit(300);	
date_default_timezone_set("Asia/Kolkata");
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('user_type_setting.php');	
include_once('calculate_distance.php');
include_once('googleMapApi.php');
//echo "one";
include_once('xmlParameters.php');
include_once('parameterizeData.php');	
include_once('data.php');		
include_once("getXmlData.php");	
	

$flag_play = $_REQUEST['flag_play'];
$play_interval = $_REQUEST['play_interval']; 

$vserial1 = $_REQUEST['vserial'];
$vserial = explode(',',$vserial1) ;
$vsize=sizeof($vserial);
$date1 = $_REQUEST['startdate'];
$date2 = $_REQUEST['enddate'];
$date1 = str_replace("/","-",$date1);
$date2 = str_replace("/","-",$date2);
$date_1 = explode(" ",$date1);
$date_2 = explode(" ",$date2);
$datefrom = $date_1[0];
$dateto = $date_2[0];
$userInterval = "0";
$sortBy="h";
$firstDataFlag=0;
$requiredData="All";
$endDateTS=strtotime($date2);
$parameterizeData=null;
$parameterizeData=new parameterizeData();
$parameterizeData->messageType='a';
$parameterizeData->version='b';
$parameterizeData->fix='c';
$parameterizeData->latitude='d';
$parameterizeData->longitude='e';
$parameterizeData->speed='f';
$parameterizeData->cellName='ci';
$parameterizeData->supVoltage='r';
$parameterizeData->dayMaxSpeed='s';
$parameterizeData->lastHaltTime='u';
$parameterizeData->io1='i';
$parameterizeData->io2='j';
$parameterizeData->io3='k';
$parameterizeData->io4='l';
$parameterizeData->io5='m';
$parameterizeData->io6='n';
$parameterizeData->io7='o';
$parameterizeData->io8='p';

$time_interval1 = $_REQUEST['time_interval']; 
$minlat = 180; 
$maxlat = -180;
$minlong = 180;
$maxlong = -180;
$maxPoints = 1000;
$file_exist = 0;	
$tmptimeinterval = strtotime($enddate) - strtotime($startdate);	
if($time_interval1=="auto")
{
        $timeinterval =   ($tmptimeinterval/$maxPoints);
        $distanceinterval = 0.1; 
}
else
{
        if($tmptimeinterval>86400)
        {
                $timeinterval =   $time_interval1;		
                $distanceinterval = 0.3;
        }
        else
        {
                $timeinterval =   $time_interval1;
                $distanceinterval = 0.02;
        }
}
//$distanceinterval=0.0;


get_All_Dates($datefrom, $dateto, $userdates);    
$date_size = sizeof($userdates);
        
for($i=0;$i<$vsize;$i++)
{
    $dataCnt=0;
    //echo "vs=".$vserial[$i]."<br>";
    $vehicle_info=get_vehicle_info($root,$vserial[$i]);
    $vehicle_detail_local=explode(",",$vehicle_info);

    
		//echo "vehcileName=".$finalVNameArr[$i]." vSerial=".$vehicle_detail_local[0]."<br>";
    if($home_report_type=="map_report" || $home_report_type=="play_report")   /// map only
    { 
        $lineTmpTrack="";
        if($report_type=="Vehicle")
        {
            $CurrentLat = 0.0;
            $CurrentLong = 0.0;
            $LastLat = 0.0;
            $LastLong = 0.0;
            $LastDTForDiff = "";
            $firstData = 0;
            $distance =0.0;
   
            for($di=0;$di<=($date_size-1);$di++)
            {
                //echo "userdate=".$userdates[$di]."<br>";
                $SortedDataObject=null;
                $SortedDataObject=new data();
                if($date_size==1)
                {
                    $dateRangeStart=$date1;
                    $dateRangeEnd=$date2;
                }
                else if($di==($date_size-1))
                {
                    $dateRangeStart=$userdates[$di]." 00:00:00";
                    $dateRangeEnd=$date2;
                }
                else if($di==0)
                {
                    $dateRangeStart=$date1;
                    $dateRangeEnd=$userdates[$di]." 23:59:59";
                }
                else
                {
                   $dateRangeStart=$userdates[$di]." 00:00:00";
                    $dateRangeEnd=$userdates[$di]." 23:59:59";
                }
                deviceDataBetweenDates($vserial[$i],$dateRangeStart,$dateRangeEnd,$sortBy,$parameterizeData,$SortedDataObject);
                //var_dump($SortedDataObject);
                $last_rec=0;
                if(count($SortedDataObject->deviceDatetime)>0)
                { 
                    $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);                   
                    for($obi=0;$obi<$prevSortedSize;$obi++)
                    {
                        $DataValid = 0;
                        $CurrentLat = $SortedDataObject->latitudeData[$obi];
                        $CurrentLong = $SortedDataObject->longitudeData[$obi];
                        $datetime=$SortedDataObject->deviceDatetime[$obi];
                        if((strlen($CurrentLat)>5) && ($CurrentLat!="-") && (strlen($CurrentLong)>5) && ($CurrentLong!="-"))
                        {
                            $DataValid = 1;
                        }
                        if(($DataValid==1))
                        {
                            $last_rec = $obi;
                            //echo "lat=".$CurrentLat." lng=".$CurrentLat."<br><br>";
                            $xml_date_current = $datetime;
                            //echo "xml_date_current=".$xml_date_current."<br>";
                            if((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval)
                            {
                                $CurrentDTForDiffTmp=strtotime($datetime);
                                //echo "CurrentDTForDiffTmp=".$CurrentDTForDiffTmp."<br>";
                                if($firstData==1)
                                {
                                    if($minlat>$CurrentLat)
                                    {
                                        $minlat = $CurrentLat;
                                    }
                                    if($maxlat<$CurrentLat)
                                    {
                                        $maxlat = $CurrentLat;
                                    }					
                                    if($minlong>$CurrentLong)
                                    {
                                        $minlong = $CurrentLong;
                                    }
                                    if($maxlong<$CurrentLong)
                                    {
                                        $maxlong = $CurrentLong;
                                    }                
                                    $tmp1lat = substr($CurrentLat,0,-1);
                                    $tmp2lat = substr($LastLat,0,-1);
                                    $tmp1lng = substr($CurrentLong,0,-1);
                                    $tmp2lng = substr($LastLong,0,-1);  							
                                   // echo  "Coord: ".$tmp1lat.' '.$tmp2lat.' '.$tmp1lng.' '.$tmp2lng.'<BR>'; 
                                    //echo "lastDate=".$LastDTForDif."<br>";
                                    $LastDTForDiffTS=strtotime($LastDTForDif);									
                                    $dateDifference=($CurrentDTForDiffTmp-$LastDTForDiffTS)/3600;
                                    $dateDifference_1=round($dateDifference,5);
                                    //echo" dateDifference=".round($dateDifference,5)."<br>";
                                    //echo  "dateDifference: ".$dateDifference.'<BR>'; 									
                                    calculate_distance($tmp1lat,$tmp2lat,$tmp1lng,$tmp2lng,$distance);									
                                    $overSpeed=$distance/$dateDifference_1;
                                   
                                }
                                if($distance<$distanceinterval)
                                {                                   
                                    $LastDTForDif=$xml_date_current;
                                }
                                if($dateDifference_1>2.0)
                                {
                                    $cmpOverSpeed=90.0;
                                }
                                else
                                {
                                    $cmpOverSpeed=200.0;
                                }
                                /*if((((((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval) && ($distance>=$distanceinterval)) || ($firstData==0)) && 
                                (($xml_date_current<=$enddate) && ($xml_date_current>=$startdate))) || ($f==$total_lines-2) )*/
                                //echo "distance=".$distance." distanceinterval=".$distanceinterval."<br><br>";
                                if(($distance>=$distanceinterval) || ($firstData==0))
                                {
                                    //echo "distance1=".$distance." distanceinterval1=".$distanceinterval."<br><br>";
                                    if($overSpeed<$cmpOverSpeed)
                                    {                                       
                                        $xml_date_last = $xml_date_current;
                                        $LastLat =$CurrentLat;
                                        $LastLong =$CurrentLong;									
                                        //$linetolog = "Data Written\n";
                                        $LastDTForDif=$xml_date_current;
                                        
                                        $finalDistance = $finalDistance + $distance;	
                                        $linetowrite='<x a="'.$SortedDataObject->messageTypeData[$obi].'" b="'.$SortedDataObject->versionData[$obi].'" c="'.$SortedDataObject->fixData[$obi].'" d="'.$SortedDataObject->latitudeData[$obi].'" e="'.$SortedDataObject->longitudeData[$obi].'" f="'.$SortedDataObject->speedData[$obi].'" g="'.$SortedDataObject->serverDatetime[$obi].'" h="'.$SortedDataObject->deviceDatetime[$obi].'" i="'.$SortedDataObject->io1Data[$obi].'" j="'.$SortedDataObject->io2Data[$obi].'" k="'.$SortedDataObject->io3Data[$obi].'" l="'.$SortedDataObject->io4Data[$obi].'" m="'.$SortedDataObject->io5Data[$obi].'" n="'.$SortedDataObject->io6Data[$obi].'" o="'.$SortedDataObject->io7Data[$obi].'" p="'.$SortedDataObject->io8Data[$obi].'" q="'.$SortedDataObject->sigStrData[$obi].'" r="'.$SortedDataObject->supVoltageData[$obi].'" s="'.$SortedDataObject->dayMaxSpeedData[$obi].'" v="'.$vserial[$i].'" w="'.$vehicle_detail_local[0].'" x="'.$vehicle_detail_local[2].'" y="'.$vehicle_detail_local[1].'" z="'.round($finalDistance,2).'" za="'.$vehicle_detail_local[8].'"/>';
                                        $firstData = 1;  
                                        $lineTmpTrack=$lineTmpTrack.$linetowrite."@";
                                        $distance=0;
                                        //$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
                                        //fwrite($fh, $linetowrite); 
                                    }                                    
                                }
                            }
                        }
                    }
                }
            }
           if(($overSpeed<$cmpOverSpeed) && ($last_rec!=0))
            {
                $finalDistance = $finalDistance + $distance;	
                $linetowrite='<x a="'.$SortedDataObject->messageTypeData[$last_rec].'" b="'.$SortedDataObject->versionData[$last_rec].'" c="'.$SortedDataObject->fixData[$last_rec].'" d="'.$SortedDataObject->latitudeData[$last_rec].'" e="'.$SortedDataObject->longitudeData[$last_rec].'" f="'.$SortedDataObject->speedData[$last_rec].'" g="'.$SortedDataObject->serverDatetime[$last_rec].'" h="'.$SortedDataObject->deviceDatetime[$last_rec].'" i="'.$SortedDataObject->io1Data[$last_rec].'" j="'.$SortedDataObject->io2Data[$last_rec].'" k="'.$SortedDataObject->io3Data[$last_rec].'" l="'.$SortedDataObject->io4Data[$last_rec].'" m="'.$SortedDataObject->io5Data[$last_rec].'" n="'.$SortedDataObject->io6Data[$last_rec].'" o="'.$SortedDataObject->io7Data[$last_rec].'" p="'.$SortedDataObject->io8Data[$last_rec].'" q="'.$SortedDataObject->sigStrData[$last_rec].'" r="'.$SortedDataObject->supVoltageData[$last_rec].'" s="'.$SortedDataObject->dayMaxSpeedData[$last_rec].'" v="'.$vserial[$i].'" w="'.$vehicle_detail_local[0].'" x="'.$vehicle_detail_local[2].'" y="'.$vehicle_detail_local[1].'" z="'.round($finalDistance,2).'" za="'.$vehicle_detail_local[8].'"/>';
                //echo"<textarea>".$linetowrite."</textarea>"; 
                $lineTmpTrack=$lineTmpTrack.$linetowrite."@";            
            }
            /*$linetowrite='<x a="'.$SortedDataObject->messageTypeData[$obi].'" b="'.$SortedDataObject->versionData[$obi].'" c="'.$SortedDataObject->fixData[$obi].'" d="'.$SortedDataObject->latitudeData[$obi].'" e="'.$SortedDataObject->longitudeData[$obi].'" f="'.$SortedDataObject->speedData[$obi].'" g="'.$SortedDataObject->serverDatetime[$obi].'" h="'.$SortedDataObject->deviceDatetime[$obi].'" i="'.$SortedDataObject->io1Data[$obi].'" j="'.$SortedDataObject->io2Data[$obi].'" k="'.$SortedDataObject->io3Data[$obi].'" l="'.$SortedDataObject->io4Data[$obi].'" m="'.$SortedDataObject->io5Data[$obi].'" n="'.$SortedDataObject->io6Data[$obi].'" o="'.$SortedDataObject->io7Data[$obi].'" p="'.$SortedDataObject->io8Data[$obi].'" q="'.$SortedDataObject->sigStrData[$obi].'" r="'.$SortedDataObject->supVoltageData[$obi].'" s="'.$SortedDataObject->dayMaxSpeedData[$obi].'" v="'.$vserial[$i].'" w="'.$vehicle_detail_local[0].'" x="'.$vehicle_detail_local[2].'" y="'.$vehicle_detail_local[1].'" z="'.round($finalDistance,2).'"/>';
            $lineTmpTrack=$lineTmpTrack.$linetowrite."@";*/
            $io_type_value=$vehicle_detail_local[7];
            $lineF=explode("@",substr($lineTmpTrack,0,-1));					
            for($n=0;$n<sizeof($lineF);$n++)
            {
                preg_match('/d="[^" ]+/', $lineF[$n], $lat_tmp);
                $lat_tmp1 = explode("=",$lat_tmp[0]);
                $lat = substr(preg_replace('/"/', '', $lat_tmp1[1]),0,-1);
                //echo "lat=".$lat."<br>";
                $lat_arr_last[]=$lat;					

                preg_match('/e="[^" ]+/', $lineF[$n], $lng_tmp);
                $lng_tmp1 = explode("=",$lng_tmp[0]);
                $lng = substr(preg_replace('/"/', '', $lng_tmp1[1]),0,-1);
                //echo "lng=".$lng."<br>";
                $lng_arr_last[]=$lng;                    

                preg_match('/h="[^"]+/', $lineF[$n], $datetime_tmp);
                $datetime_tmp1 = explode("=",$datetime_tmp[0]);
                $datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
                $datetime_arr_last[]=$datetime;
                // echo "datetime=".$datetime."<br>";

                preg_match('/v="[^"]+/', $lineF[$n], $vserial_tmp);
                $vserial_tmp1 = explode("=",$vserial_tmp[0]);
                $vehicle_serial = preg_replace('/"/', '', $vserial_tmp1[1]);
                $vserial_arr_last[]=$vehicle_serial;
                // echo "vehicle_name1=".$vehicle_serial."<br>";

                preg_match('/w="[^"]+/', $lineF[$n], $vname_tmp);
                $vname_tmp1 = explode("=",$vname_tmp[0]);
                $vehicle_name = preg_replace('/"/', '', $vname_tmp1[1]);
                $vehiclename_arr_last[]=$vehicle_name;
                // echo "vehicle_name=".$vehicle_name."<br>";

                preg_match('/x="[^"]+/', $lineF[$n], $vnumber_tmp);
                $vnumber_tmp1 = explode("=",$vnumber_tmp[0]);
                $vehicle_number = preg_replace('/"/', '', $vnumber_tmp1[1]);
                $vehiclenumber_arr_last[]=$vehicle_number;
                //echo "vehicle_number=".$vehicle_number."<br>";

                preg_match('/f="[^"]+/', $lineF[$n], $speed_tmp);
                $speed_tmp1 = explode("=",$speed_tmp[0]);
                $speed = preg_replace('/"/', '', $speed_tmp1[1]);                               
                if( ($speed<=3) || ($speed>200))
                {
                        $speed = 0;
                }
                $speed_arr_last[]=$speed;
                //echo "speed=".$speed."<br>";
                preg_match('/i="[^"]+/', $lineF[$n], $io1_tmp);
                $io1_tmp1 = explode("=",$io1_tmp[0]);
                $io1= preg_replace('/"/', '', $io1_tmp1[1]);
                // echo "io1=".$io1."<br>";

                preg_match('/j="[^"]+/', $lineF[$n], $io2_tmp);
                $io2_tmp1 = explode("=",$io2_tmp[0]);
                $io2= preg_replace('/"/', '', $io2_tmp1[1]);
                // echo "io2=".$io2."<br>";

                preg_match('/k="[^"]+/', $lineF[$n], $io3_tmp);
                $io3_tmp1 = explode("=",$io3_tmp[0]);
                $io3= preg_replace('/"/', '', $io3_tmp1[1]);
                //echo "io3=".$io3."<br>";

                preg_match('/l="[^"]+/', $lineF[$n], $io4_tmp);
                $io4_tmp1 = explode("=",$io4_tmp[0]);
                $io4= preg_replace('/"/', '', $io4_tmp1[1]);
                //echo "io4=".$io4."<br>";

                preg_match('/m="[^"]+/', $lineF[$n], $io5_tmp);
                $io5_tmp1 = explode("=",$io5_tmp[0]);
                $io5= preg_replace('/"/', '', $io5_tmp1[1]);
                //echo "io5=".$io5."<br>";

                preg_match('/n="[^"]+/', $lineF[$n], $io6_tmp);
                $io6_tmp1 = explode("=",$io6_tmp[0]);
                $io6= preg_replace('/"/', '', $io6_tmp1[1]);
                //echo "io6=".$io6."<br>";

                preg_match('/o="[^"]+/', $lineF[$n], $io7_tmp);
                $io7_tmp1 = explode("=",$io7_tmp[0]);
                $io7= preg_replace('/"/', '', $io7_tmp1[1]);
                // echo "io7=".$io7."<br>";

                preg_match('/p="[^"]+/', $lineF[$n], $io8_tmp);
                $io8_tmp1 = explode("=",$io8_tmp[0]);
                $io8= preg_replace('/"/', '', $io8_tmp1[1]);
                // echo "io8=".$io8."<br>";

                preg_match('/s="[^"]+/', $lineF[$n], $day_max_speed_tmp);
                $day_max_speed_tmp1 = explode("=",$day_max_speed_tmp[0]);
                $day_max_speed= preg_replace('/"/', '', $day_max_speed_tmp1[1]);
                $day_max_speed_arr_last[]=$day_max_speed;
                // echo "day_max_speed=".$day_max_speed."<br>";

                /*preg_match('/t="[^"]+/', $lineF[$n], $day_max_speed_time_tmp);
                $day_max_speed_time_tmp1 = explode("=",$day_max_speed_time_tmp[0]);
                $day_max_speed_time= preg_replace('/"/', '', $day_max_speed_time_tmp1[1]);*/

                // echo "day_max_speed_time=".$day_max_speed_time."<br>";

                preg_match('/u="[^"]+/', $lineF[$n], $last_halt_time_tmp);
                $last_halt_time_tmp1 = explode("=",$last_halt_time_tmp[0]);
                $last_halt_time= preg_replace('/"/', '', $last_halt_time_tmp1[1]);
                $last_halt_time_arr_last[]=$last_halt_time;

                preg_match('/ y="[^"]+/', $lineF[$n], $vehilce_type_tmp);
                $vehilce_type_tmp1 = explode("=",$vehilce_type_tmp[0]);
                $vehilce_type= preg_replace('/"/', '', $vehilce_type_tmp1[1]);
                $vehilce_type_arr[]=$vehilce_type;

                preg_match('/z="[^"]+/', $lineF[$n], $distance_travel_tmp);
                $distance_travel_tmp1 = explode("=",$distance_travel_tmp[0]);
                $distance_travel= preg_replace('/"/', '', $distance_travel_tmp1[1]);
                $distance_travel_arr[]=$distance_travel;
                
                preg_match('/za="[^"]+/', $lineF[$n], $dmobno_tmp);
                $dmobno_tmp1 = explode("=",$dmobno_tmp[0]);
                $dmobno = preg_replace('/"/', '', $dmobno_tmp1[1]);
                $dMobileNoArr[]=$dmobno;

                $io_str="";
                if($io_type_value!="tmp_str")
                {
                    $iotype_iovalue_str=explode(":",$io_type_value);
                    for($i=0;$i<sizeof($iotype_iovalue_str);$i++)
                    {
                        $iotype_iovalue_str1=explode("^",$iotype_iovalue_str[$i]);							
                        if($iotype_iovalue_str1[0]=="1")
                        {
                            $io_values=$io1;
                        }
                        else if($iotype_iovalue_str1[0]=="2")
                        {
                            $io_values=$io2;
                        }
                        else if($iotype_iovalue_str1[0]=="3")
                        {
                            $io_values=$io3;
                        }
                        else if($iotype_iovalue_str1[0]=="4")
                        {
                            $io_values=$io4;
                        }
                        else if($iotype_iovalue_str1[0]=="5")
                        {
                            $io_values=$io5;
                        }
                        else if($iotype_iovalue_str1[0]=="6")
                        {
                            $io_values=$io6;
                        }
                        else if($iotype_iovalue_str1[0]=="7")
                        {
                            $io_values=$io7;
                        }
                        else if($iotype_iovalue_str1[0]=="8")
                        {
                            $io_values=$io8;
                        }
                        //echo "temperature=".$iotype_iovalue_str1[1]."<br>";
                        if($iotype_iovalue_str1[1]=="temperature")
                        {					
                            if($io_values!="")
                            {
                                if($io_values>=-30 && $io_values<=70)
                                {
                                    //echo "in if";
                                    $io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>".$io_values."</td></tr>";
                                }
                                else
                                {
                                    //echo "in if 1";
                                    $io_str=$io_str."<tr><td class='live_td_css1'>Temperature</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>-</td></tr>";
                                }
                            }
                            else
                            {
                                //echo "in if 2";
                                $io_str=$io_str."<tr><td class='live_td_css1'>Temperature</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>-</td></tr>";
                            }
                        }
                        else if($iotype_iovalue_str1[1]!="")
                        {
                            //echo "engine".$iotype_iovalue_str1[1]."<br>";
                            if(trim($iotype_iovalue_str1[1])=="engine")
                            {
                                $ioStr= get_io($vehicle_serial,'engine,engine_type');
                                $ioStr=explode("#",$ioStr);
                                if($ioStr[1]==1)
                                {
                                    if($io_values<350)
                                    {					
                                        $io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>On</td></tr>";
                                    }
                                    else
                                    {
                                        $io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>:</td><td class='live_td_css2'>Off</td></tr>";
                                    }
                                }
                                else
                                {
                                    if($io_values<=350)
                                    {					
                                        $io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>Off</td></tr>";
                                    }
                                    else
                                    {
                                        $io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>:</td><td class='live_td_css2'>ON</td></tr>";
                                    }
                                }
                            }
                            else if(trim($iotype_iovalue_str1[1])=="ac")
                            {
                                if($io_values>500)
                                {					
                                    $io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>Off</td></tr>";
                                }
                                else
                                {
                                    $io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>:</td><td class='live_td_css2'>ON</td></tr>";
                                }
                            }
                            else if($iotype_iovalue_str1[1]=="door_open")
                            {
                                //if($io_values<=350)
                                if($io_values<250)
                                {					
                                    $io_str=$io_str."<tr><td class='live_td_css1'>Delivery Door</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>Close</td></tr>";
                                }
                                else
                                {
                                    $io_str=$io_str."<tr><td class='live_td_css1'>Delivery Door</td><td>:</td><td class='live_td_css2'>Open</td></tr>";
                                }
                            }
                            else if($iotype_iovalue_str1[1]=="door_open2")
                            {
                                //if($io_values<=350)
                                if($io_values<250)
                                {					
                                    $io_str=$io_str."<tr><td class='live_td_css1'>Manhole Door</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>Close</td></tr>";
                                }
                                else
                                {
                                    $io_str=$io_str."<tr><td class='live_td_css1'>Manhole Door</td><td>:</td><td class='live_td_css2'>Open</td></tr>";
                                }
                            }
                            else if($iotype_iovalue_str1[1]=="door_open3")
                            {
                                //if($io_values<=350)
                                if($io_values<250)
                                {					
                                    $io_str=$io_str."<tr><td class='live_td_css1'>Manhole Door2</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>Close</td></tr>";
                                }
                                else
                                {
                                    $io_str=$io_str."<tr><td class='live_td_css1'>Manhole Door2</td><td>:</td><td class='live_td_css2'>Open</td></tr>";
                                }
                            }
                            else if($iotype_iovalue_str1[1]=="fuel_lead")
                            {
                                if($io_values<=350)
                                {					
                                    $io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>Close</td></tr>";
                                }
                                else
                                {
                                    $io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>:</td><td class='live_td_css2'>Open</td></tr>";
                                }
                            }
                            else
                            {
                                if($io_values!="")
                                {					
                                    $io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>".$io_values."</td></tr>";
                                }
                                else
                                {
                                    $io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>-</td></tr>";
                                }
                            }
                        }			
                    }
                }
                $io_str_last[]=$io_str;                                 
            }
            //print_r($io_str_last);
            //print_r($lng_arr_last);
            //print_r($io_str_last);
            $googleMapthisapi=new GoogleMapHelper();								
            //echo $googleMapthisapi->addMultipleMarker("map_canvas",$lat_arr_last,$lng_arr_last,$datetime_arr_last,$vserial_arr_last,$vehiclename_arr_last,$speed_arr_last,$vehiclenumber_arr_last,$io_str_last,$distance_travel_arr);
            if($flag_play==1)
            {
                //playing track					
                echo $googleMapthisapi->addMultipleMarker_play("map_canvas",$lat_arr_last,$lng_arr_last,$datetime_arr_last,$vserial_arr_last,$vehiclename_arr_last,$speed_arr_last,$vehiclenumber_arr_last,$io_str_last,$distance_travel_arr,$play_interval);
            }
            else
            {	
                //not playing track
                echo $googleMapthisapi->addMultipleMarker("map_canvas",$lat_arr_last,$lng_arr_last,$datetime_arr_last,$vserial_arr_last,$vehiclename_arr_last,$speed_arr_last,$vehiclenumber_arr_last,$io_str_last,$distance_travel_arr,$dMobileNoArr);
            }
            //echo "<textarea>".$lineTmpTrack."</textarea>";
        }
        else 
        {
            $CurrentLat = 0.0;
            $CurrentLong = 0.0;
            $LastLat = 0.0;
            $LastLong = 0.0;
            $firstData = 0;
            $distance =0.0;
            $lineTmpTrack="";
            for($di=0;$di<=($date_size-1);$di++)
            {
                //echo "userdate=".$userdates[$di]."<br>";
                $SortedDataObject=null;
                $SortedDataObject=new data();
                if($date_size==1)
                {
                    $dateRangeStart=$date1;
                    $dateRangeEnd=$date2;
                }
                else if($di==($date_size-1))
                {
                    $dateRangeStart=$userdates[$di]." 00:00:00";
                    $dateRangeEnd=$date2;
                }
                else if($di==0)
                {
                    $dateRangeStart=$date1;
                    $dateRangeEnd=$userdates[$di]." 23:59:59";
                }
                else
                {
                   $dateRangeStart=$userdates[$di]." 00:00:00";
                    $dateRangeEnd=$userdates[$di]." 23:59:59";
                }
                $last_rec=0;
                deviceDataBetweenDates($vserial[$i],$dateRangeStart,$dateRangeEnd,$sortBy,$parameterizeData,$SortedDataObject);
                //var_dump($SortedDataObject);
                if(count($SortedDataObject->deviceDatetime)>0)
                {
                    $logcnt=0;
                    $DataComplete=false;
                    $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
                    $logcnt=0;
                    $DataComplete=false;
                    for($obi=0;$obi<$prevSortedSize;$obi++)
                    {
                        $DataValid = 0;
                        $CurrentLat = $SortedDataObject->latitudeData[$obi];
                        $CurrentLong = $SortedDataObject->longitudeData[$obi];
                        $datetime=$SortedDataObject->deviceDatetime[$obi];
                        if((strlen($CurrentLat)>5) && ($CurrentLat!="-") && (strlen($CurrentLong)>5) && ($CurrentLong!="-"))
                        {
                            $DataValid = 1;
                        }
                        if(($DataValid==1) && !(strtotime($datetime)>(strtotime($SortedDataObject->serverDatetime[$obi])+10*60)))
                        { 
                            $last_rec = $obi;
                            $xml_date_current = $datetime;
                            if((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval)
                            {	
                                //echo "lat=".$CurrentLat." lng=".$CurrentLong."<br>";
                                $CurrentDTForDiffTmp=strtotime($datetime);
                                if($firstData==1)
                                {   
                                    $tmp1lat = round(substr($CurrentLat,1,(strlen($CurrentLat)-3)),4);
                                    $tmp2lat = round(substr($LastLat,1,(strlen($LastLat)-3)),4);
                                    $tmp1lng = round(substr($CurrentLong,1,(strlen($CurrentLong)-3)),4);
                                    $tmp2lng = round(substr($LastLong,1,(strlen($LastLong)-3)),4); 
                                    $LastDTForDiffTS=strtotime($LastDTForDif);									
                                    $dateDifference=($CurrentDTForDiffTmp-$LastDTForDiffTS)/3600;
                                    $dateDifference_1=round($dateDifference,5);
                                    //echo  "Coord: ".$tmp1lat.' '.$tmp2lat.' '.$tmp1lng.' '.$tmp2lng.'<BR>';             							
                                    calculate_distance($tmp1lat,$tmp2lat,$tmp1lng,$tmp2lng,$distance);  
                                    $overSpeed=$distance/$dateDifference_1;										
                                    //fwrite($xmllog, $linetolog);
                                }
                                if($distance<$distanceinterval)
                                {
                                    $LastDTForDif=$xml_date_current;
                                }
                                
                                if($dateDifference_1>2.0)
                                {
                                    $cmpOverSpeed=90.0;
                                }
                                else 
                                {
                                    $cmpOverSpeed=200.0;     
                                }
                                
                                if($overSpeed<$cmpOverSpeed)
                                {
                                    $speeed_data_valid_time = $datetime;
                                }
                                
                                if((strtotime($datetime) - strtotime($speeed_data_valid_time) )>300) //data high speed for 5 mins
                                {
                                    $LastLat =$CurrentLat;
                                    $LastLong =$CurrentLong;                                  
                                }
                                
                                if(($distance>=$distanceinterval) || ($firstData==0))
                                {
                                    $xml_date_last = $xml_date_current;									
                                    if($overSpeed<$cmpOverSpeed)
                                    {
                                        $LastLat =$CurrentLat;
                                        $LastLong =$CurrentLong;
                                        $LastDTForDif=$xml_date_current;
                                        $finalDistance = $finalDistance + $distance;                           
                                        $linetowrite='<x a="'.$SortedDataObject->messageTypeData[$obi].'" b="'.$SortedDataObject->versionData[$obi].'" c="'.$SortedDataObject->fixData[$obi].'" d="'.$SortedDataObject->latitudeData[$obi].'" e="'.$SortedDataObject->longitudeData[$obi].'" f="'.$SortedDataObject->speedData[$obi].'" g="'.$SortedDataObject->serverDatetime[$obi].'" h="'.$SortedDataObject->deviceDatetime[$obi].'" i="'.$SortedDataObject->io1Data[$obi].'" j="'.$SortedDataObject->io2Data[$obi].'" k="'.$SortedDataObject->io3Data[$obi].'" l="'.$SortedDataObject->io4Data[$obi].'" m="'.$SortedDataObject->io5Data[$obi].'" n="'.$SortedDataObject->io6Data[$obi].'" o="'.$SortedDataObject->io7Data[$obi].'" p="'.$SortedDataObject->io8Data[$obi].'" q="'.$SortedDataObject->sigStrData[$obi].'" r="'.$SortedDataObject->supVoltageData[$obi].'" s="'.$SortedDataObject->dayMaxSpeedData[$obi].'" v="'.$vserial[$i].'" w="'.$vehicle_detail_local[0].'" x="'.$vehicle_detail_local[8].'" y="'.$vehicle_detail_local[1].'" z="'.round($finalDistance,2).'"/>';
					$firstData = 1;  
                                        $lineTmpTrack=$lineTmpTrack.$linetowrite."@"; 
                                    }
                                }
                            }
                        }
                    }                    
                }
            }
            if(($overSpeed<$cmpOverSpeed) && ($last_rec!=0))
            {
                $finalDistance = $finalDistance + $distance;	
                $linetowrite='<x a="'.$SortedDataObject->messageTypeData[$last_rec].'" b="'.$SortedDataObject->versionData[$last_rec].'" c="'.$SortedDataObject->fixData[$last_rec].'" d="'.$SortedDataObject->latitudeData[$last_rec].'" e="'.$SortedDataObject->longitudeData[$last_rec].'" f="'.$SortedDataObject->speedData[$last_rec].'" g="'.$SortedDataObject->serverDatetime[$last_rec].'" h="'.$SortedDataObject->deviceDatetime[$last_rec].'" i="'.$SortedDataObject->io1Data[$last_rec].'" j="'.$SortedDataObject->io2Data[$last_rec].'" k="'.$SortedDataObject->io3Data[$last_rec].'" l="'.$SortedDataObject->io4Data[$last_rec].'" m="'.$SortedDataObject->io5Data[$last_rec].'" n="'.$SortedDataObject->io6Data[$last_rec].'" o="'.$SortedDataObject->io7Data[$last_rec].'" p="'.$SortedDataObject->io8Data[$last_rec].'" q="'.$SortedDataObject->sigStrData[$last_rec].'" r="'.$SortedDataObject->supVoltageData[$last_rec].'" s="'.$SortedDataObject->dayMaxSpeedData[$last_rec].'" v="'.$vserial[$i].'" w="'.$vehicle_detail_local[0].'" x="'.$vehicle_detail_local[8].'" y="'.$vehicle_detail_local[1].'" z="'.round($finalDistance,2).'"/>';
                //echo"<textarea>".$linetowrite."</textarea>"; 
                $lineTmpTrack=$lineTmpTrack.$linetowrite."@";            
            }
            //echo "<textarea>".$lineTmpTrack."</textarea>";
            //echo "lineTmpTrack=".$lineTmpTrack."<br>";
            $lineF=explode("@",substr($lineTmpTrack,0,-1));					
            for($n=0;$n<sizeof($lineF);$n++)
            {
                preg_match('/d="[^" ]+/', $lineF[$n], $lat_tmp);
                $lat_tmp1 = explode("=",$lat_tmp[0]);
                $lat = substr(preg_replace('/"/', '', $lat_tmp1[1]),0,-1);
                //echo "lat=".$lat."<br>";
                $lat_arr_last[]=$lat;					

                preg_match('/e="[^" ]+/', $lineF[$n], $lng_tmp);
                $lng_tmp1 = explode("=",$lng_tmp[0]);
                $lng = substr(preg_replace('/"/', '', $lng_tmp1[1]),0,-1);
                //echo "lng=".$lng."<br>";
                $lng_arr_last[]=$lng;                    

                preg_match('/h="[^"]+/', $lineF[$n], $datetime_tmp);
                $datetime_tmp1 = explode("=",$datetime_tmp[0]);
                $datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
                $datetime_arr_last[]=$datetime;
                // echo "datetime=".$datetime."<br>";

                preg_match('/v="[^"]+/', $lineF[$n], $vserial_tmp);
                $vserial_tmp1 = explode("=",$vserial_tmp[0]);
                $vehicle_serial = preg_replace('/"/', '', $vserial_tmp1[1]);
                $vserial_arr_last[]=$vehicle_serial;
                // echo "vehicle_name1=".$vehicle_serial."<br>";

                preg_match('/w="[^"]+/', $lineF[$n], $vname_tmp);
                $vname_tmp1 = explode("=",$vname_tmp[0]);
                $vehicle_name = preg_replace('/"/', '', $vname_tmp1[1]);
                $vehiclename_arr_last[]=$vehicle_name;
                // echo "vehicle_name=".$vehicle_name."<br>";

                preg_match('/x="[^"]+/', $lineF[$n], $vnumber_tmp);
                $vnumber_tmp1 = explode("=",$vnumber_tmp[0]);
                $vehicle_number = preg_replace('/"/', '', $vnumber_tmp1[1]);
                $vehiclenumber_arr_last[]=$vehicle_number;
                //echo "vehicle_number=".$vehicle_number."<br>";

                preg_match('/f="[^"]+/', $lineF[$n], $speed_tmp);
                $speed_tmp1 = explode("=",$speed_tmp[0]);
                $speed = preg_replace('/"/', '', $speed_tmp1[1]);                               
                if( ($speed<=3) || ($speed>200))
                {
                        $speed = 0;
                }
                $speed_arr_last[]=$speed;
                //echo "speed=".$speed."<br>";
                preg_match('/i="[^"]+/', $lineF[$n], $io1_tmp);
                $io1_tmp1 = explode("=",$io1_tmp[0]);
                $io1= preg_replace('/"/', '', $io1_tmp1[1]);
                // echo "io1=".$io1."<br>";

                preg_match('/j="[^"]+/', $lineF[$n], $io2_tmp);
                $io2_tmp1 = explode("=",$io2_tmp[0]);
                $io2= preg_replace('/"/', '', $io2_tmp1[1]);
                // echo "io2=".$io2."<br>";

                preg_match('/k="[^"]+/', $lineF[$n], $io3_tmp);
                $io3_tmp1 = explode("=",$io3_tmp[0]);
                $io3= preg_replace('/"/', '', $io3_tmp1[1]);
                //echo "io3=".$io3."<br>";

                preg_match('/l="[^"]+/', $lineF[$n], $io4_tmp);
                $io4_tmp1 = explode("=",$io4_tmp[0]);
                $io4= preg_replace('/"/', '', $io4_tmp1[1]);
                //echo "io4=".$io4."<br>";

                preg_match('/m="[^"]+/', $lineF[$n], $io5_tmp);
                $io5_tmp1 = explode("=",$io5_tmp[0]);
                $io5= preg_replace('/"/', '', $io5_tmp1[1]);
                //echo "io5=".$io5."<br>";

                preg_match('/n="[^"]+/', $lineF[$n], $io6_tmp);
                $io6_tmp1 = explode("=",$io6_tmp[0]);
                $io6= preg_replace('/"/', '', $io6_tmp1[1]);
                //echo "io6=".$io6."<br>";

                preg_match('/o="[^"]+/', $lineF[$n], $io7_tmp);
                $io7_tmp1 = explode("=",$io7_tmp[0]);
                $io7= preg_replace('/"/', '', $io7_tmp1[1]);
                // echo "io7=".$io7."<br>";

                preg_match('/p="[^"]+/', $lineF[$n], $io8_tmp);
                $io8_tmp1 = explode("=",$io8_tmp[0]);
                $io8= preg_replace('/"/', '', $io8_tmp1[1]);
                // echo "io8=".$io8."<br>";

                preg_match('/s="[^"]+/', $lineF[$n], $day_max_speed_tmp);
                $day_max_speed_tmp1 = explode("=",$day_max_speed_tmp[0]);
                $day_max_speed= preg_replace('/"/', '', $day_max_speed_tmp1[1]);
                $day_max_speed_arr_last[]=$day_max_speed;
                // echo "day_max_speed=".$day_max_speed."<br>";

                /*preg_match('/t="[^"]+/', $lineF[$n], $day_max_speed_time_tmp);
                $day_max_speed_time_tmp1 = explode("=",$day_max_speed_time_tmp[0]);
                $day_max_speed_time= preg_replace('/"/', '', $day_max_speed_time_tmp1[1]);*/

                // echo "day_max_speed_time=".$day_max_speed_time."<br>";

                preg_match('/u="[^"]+/', $lineF[$n], $last_halt_time_tmp);
                $last_halt_time_tmp1 = explode("=",$last_halt_time_tmp[0]);
                $last_halt_time= preg_replace('/"/', '', $last_halt_time_tmp1[1]);
                $last_halt_time_arr_last[]=$last_halt_time;

                preg_match('/y="[^"]+/', $lineF[$n], $vehilce_type_tmp);
                $vehilce_type_tmp1 = explode("=",$vehilce_type_tmp[0]);
                $vehilce_type= preg_replace('/"/', '', $vehilce_type_tmp1[1]);
                $vehilce_type_arr[]=$vehilce_type;

                preg_match('/z="[^"]+/', $lineF[$n], $distance_travel_tmp);
                $distance_travel_tmp1 = explode("=",$distance_travel_tmp[0]);
                $distance_travel= preg_replace('/"/', '', $distance_travel_tmp1[1]);
                $distance_travel_arr[]=$distance_travel;
            }
            //print_r($lat_arr_last);
            //print_r($lng_arr_last);
            //print_r($io_str_last);
            $googleMapthisapi=new GoogleMapHelper();								
            echo $googleMapthisapi->addMultipleMarkerPerson("map_canvas",$lat_arr_last,$lng_arr_last,$datetime_arr_last,$vserial_arr_last,$vehiclename_arr_last,$speed_arr_last,$vehiclenumber_arr_last,$distance_travel_arr);
        }
    }
    else   // for text track report
    {
        if($report_type=="Vehicle")
        {
            $CurrentLat = 0.0;
            $CurrentLong = 0.0;
            $LastLat = 0.0;
            $LastLong = 0.0;
            $LastDTForDiff = "";
            $firstData = 0;
            $distance =0.0;
            for($di=0;$di<=($date_size-1);$di++)
            {
                //echo "userdate=".$userdates[$di]."<br>";
                //echo "userdate=".$userdates[$di]."<br>";
                $SortedDataObject=null;
                $SortedDataObject=new data();
                if($date_size==1)
                {
                    $dateRangeStart=$date1;
                    $dateRangeEnd=$date2;
                }
                else if($di==($date_size-1))
                {
                    $dateRangeStart=$userdates[$di]." 00:00:00";
                    $dateRangeEnd=$date2;
                }
                else if($di==0)
                {
                    $dateRangeStart=$date1;
                    $dateRangeEnd=$userdates[$di]." 23:59:59";
                }
                else
                {
                   $dateRangeStart=$userdates[$di]." 00:00:00";
                    $dateRangeEnd=$userdates[$di]." 23:59:59";
                }
                deviceDataBetweenDates($vserial[$i],$dateRangeStart,$dateRangeEnd,$sortBy,$parameterizeData,$SortedDataObject);
                //var_dump($SortedDataObject);
                $last_rec=0;
                if(count($SortedDataObject->deviceDatetime)>0)
                {                   
                    $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);			
                    for($obi=0;$obi<$prevSortedSize;$obi++)
                    {
                        $DataValid = 0;
                        $CurrentLat = $SortedDataObject->latitudeData[$obi];
                        $CurrentLong = $SortedDataObject->longitudeData[$obi];
                        $datetime=$SortedDataObject->deviceDatetime[$obi];
                        
                       
                        if((strlen($CurrentLat)>5) && ($CurrentLat!="-") && (strlen($CurrentLong)>5) && ($CurrentLong!="-"))
                        {
                            $DataValid = 1;
                        }
                        if($DataValid==1)
                        {
                            $last_rec = $obi;
                            //echo "lat=".$CurrentLat." lng=".$CurrentLat."<br><br>";
                            $xml_date_current = $datetime;
                            //echo "xml_date_current=".$xml_date_current."<br>";
                           if((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval)
                           {
                                if($firstData==1)
                                {                                                   
                                    $tmp1lat = round(substr($CurrentLat,1,(strlen($CurrentLat)-3)),4);
                                    $tmp2lat = round(substr($LastLat,1,(strlen($LastLat)-3)),4);
                                    $tmp1lng = round(substr($CurrentLong,1,(strlen($CurrentLong)-3)),4);
                                    $tmp2lng = round(substr($LastLong,1,(strlen($LastLong)-3)),4);

                                    $LastDTForDiffTS=strtotime($LastDTForDif);	
                                    $tmpdifff=$CurrentDTForDiffTmp-$LastDTForDiffTS;								
                                    $dateDifference=($CurrentDTForDiffTmp-$LastDTForDiffTS)/3600;
                                    $dateDifference_1=round($dateDifference,5);
                                    //echo  "Lat=".$tmp1lat.' Lng='.$tmp1lng.' Lat2='.$tmp2lat.' Lng2='.$tmp2lng.'<BR>';             							
                                    calculate_distance($tmp1lat,$tmp2lat,$tmp1lng,$tmp2lng,$distance);                
                                    $linetolog = $CurrentLat.','.$CurrentLong.','.$LastLat.','.$LastLong.','.$distance.','.$xml_date_current.','.$xml_date_last.','.(strtotime($xml_date_current)-strtotime($xml_date_last)).','.$timeinterval.','.$distanceinterval.','.$enddate.','.$startdate."\n";
                                    $overSpeed=$distance/$dateDifference_1;
                                }								
                                if($distance<$distanceinterval)
                                {
                                    $LastDTForDif=$xml_date_current;
                                }
                                if($dateDifference_1>2.0)
                                {
                                    $cmpOverSpeed=90.0;
                                }
                                else 
                                {
                                    $cmpOverSpeed=200.0;
                                }
                                
                                /*if((((((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval) && ($distance>=$distanceinterval)) || ($firstData==0)) && 
                                (($xml_date_current<=$enddate) && ($xml_date_current>=$startdate))) || ($f==$total_lines-2) )*/
                                if(($distance>=$distanceinterval) || ($firstData==0))
                                {								
                                    if($overSpeed<$cmpOverSpeed)
                                    {
                                        $xml_date_last = $xml_date_current;
                                        $LastLat =$CurrentLat;
                                        $LastLong =$CurrentLong;
                                        $LastDTForDif=$xml_date_current;
                                        $line = substr($line, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
                                        $finalDistance = $finalDistance + $distance; 
                                        $vehicleserial[]=$vserial[$i];
                                        $lat[]=$CurrentLat;
                                        $lng[]=$CurrentLong; 
                                        $alt[]="";
                                        $datetimeXml[]=$SortedDataObject->deviceDatetime[$obi];
                                        $vehiclename[]=$vehicle_detail_local[0]; 
                                        $vehicletype[]=$vehicle_detail_local[1];
                                        $speed[]=$SortedDataObject->speedData[$obi];
                                        $cumdist[]=round($finalDistance,2);
                                        $io1[]=$SortedDataObject->io1Data[$obi];
                                        $io2[]=$SortedDataObject->io2Data[$obi];
                                        $io3[]=$SortedDataObject->io3Data[$obi];
                                        $io4[]=$SortedDataObject->io4Data[$obi];
                                        $io5[]=$SortedDataObject->io5Data[$obi]; 
                                        $io6[]=$SortedDataObject->io6Data[$obi];
                                        $io7[]=$SortedDataObject->io7Data[$obi]; 
                                        $io8[]=$SortedDataObject->io8Data[$obi];
                                        $firstData = 1; 
                                    }
                                }
                           }
                        }
                    }
                }
            }
            if(($overSpeed<$cmpOverSpeed) && ($last_rec!=0))
            {
                $xml_date_last = $xml_date_current;
                $LastLat =$CurrentLat;
                $LastLong =$CurrentLong;
                $LastDTForDif=$xml_date_current;
                $line = substr($line, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
                $finalDistance = $finalDistance + $distance; 
                $vehicleserial[]=$vserial[$i];
                $lat[]=$CurrentLat;
                $lng[]=$CurrentLong; 
                $alt[]="";
                $datetimeXml[]=$SortedDataObject->deviceDatetime[$obi];
                $vehiclename[]=$vehicle_detail_local[0]; 
                $vehicletype[]=$vehicle_detail_local[1];
                $speed[]=$SortedDataObject->speedData[$obi];
                $cumdist[]=round($finalDistance,2);
                $io1[]=$SortedDataObject->io1Data[$obi];
                $io2[]=$SortedDataObject->io2Data[$obi];
                $io3[]=$SortedDataObject->io3Data[$obi];
                $io4[]=$SortedDataObject->io4Data[$obi];
                $io5[]=$SortedDataObject->io5Data[$obi]; 
                $io6[]=$SortedDataObject->io6Data[$obi];
                $io7[]=$SortedDataObject->io7Data[$obi]; 
                $io8[]=$SortedDataObject->io8Data[$obi];           
            }
        }
        else
        {
            $CurrentLat = 0.0;
            $CurrentLong = 0.0;
            $LastLat = 0.0;
            $LastLong = 0.0;
            $firstData = 0;
            $distance =0.0;
            
            for($di=0;$di<=($date_size-1);$di++)
            {
                //echo "userdate=".$userdates[$di]."<br>";
               //echo "userdate=".$userdates[$di]."<br>";
                $SortedDataObject=null;
                $SortedDataObject=new data();
                if($date_size==1)
                {
                    $dateRangeStart=$date1;
                    $dateRangeEnd=$date2;
                }
                else if($di==($date_size-1))
                {
                    $dateRangeStart=$userdates[$di]." 00:00:00";
                    $dateRangeEnd=$date2;
                }
                else if($di==0)
                {
                    $dateRangeStart=$date1;
                    $dateRangeEnd=$userdates[$di]." 23:59:59";
                }
                else
                {
                   $dateRangeStart=$userdates[$di]." 00:00:00";
                    $dateRangeEnd=$userdates[$di]." 23:59:59";
                }
                deviceDataBetweenDates($vserial[$i],$dateRangeStart,$dateRangeEnd,$sortBy,$parameterizeData,$SortedDataObject);
                //var_dump($SortedDataObject);
                $last_rec=0;
                if(count($SortedDataObject->deviceDatetime)>0)
                {
                    $logcnt=0;                    
                    $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);			
                    for($obi=0;$obi<$prevSortedSize;$obi++)
                    {
                        $DataValid = 0;
                        $CurrentLat = $SortedDataObject->latitudeData[$obi];
                        $CurrentLong = $SortedDataObject->longitudeData[$obi];
                        $datetime=$SortedDataObject->deviceDatetime[$obi]; 
                        
                        if((strtotime($datetime)-strtotime($noGpsDate))>$timeinterval)
                        {
                            $noGpsDate=$datetime;
                            if($CurrentLat=="0.0" && $CurrentLong=="0.0")
                            {
                                //echo "in if";
                                $vehicleserial[]=$vserial[$i];
                                $lat[]=$CurrentLat;
                                $lng[]=$CurrentLong; 
                                $alt[]="";
                                $datetimeXml[]=$SortedDataObject->deviceDatetime[$obi];
                                $vehiclename[]=$vehicle_detail_local[0]; 
                                $vehicletype[]=$vehicle_detail_local[1];
                                $cellname[]=$SortedDataObject->cellNameData[$obi];
                                $serverDatetime[]=$SortedDataObject->serverDatetime[$obi];
                                $ver[]=$SortedDataObject->versionData[$obi];
                                $speed[]=0.0;
                                $cumdist[]=0.0;                           
                            }
                        }
                        if((strlen($CurrentLat)>5) && ($CurrentLat!="-") && (strlen($CurrentLong)>5) && ($CurrentLong!="-"))
                        {
                            $DataValid = 1;
                        }
                        if(($DataValid==1) && !(strtotime($datetime)>(strtotime($SortedDataObject->serverDatetime[$obi])+10*60)))
                        {
                            $last_rec = $obi;
                            //echo "lat=".$CurrentLat." lng=".$CurrentLat."<br><br>";
                            $xml_date_current = $datetime;
                            //echo "xml_date_current=".$xml_date_current."<br>";
                           if((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval)
                           {
                                if($firstData==1)
                                {                                                   
                                    $tmp1lat = round(substr($CurrentLat,1,(strlen($CurrentLat)-3)),4);
                                    $tmp2lat = round(substr($LastLat,1,(strlen($LastLat)-3)),4);
                                    $tmp1lng = round(substr($CurrentLong,1,(strlen($CurrentLong)-3)),4);
                                    $tmp2lng = round(substr($LastLong,1,(strlen($LastLong)-3)),4);

                                    $LastDTForDiffTS=strtotime($LastDTForDif);	
                                    $tmpdifff=$CurrentDTForDiffTmp-$LastDTForDiffTS;								
                                    $dateDifference=($CurrentDTForDiffTmp-$LastDTForDiffTS)/3600;
                                    $dateDifference_1=round($dateDifference,5);
                                    //echo  "Lat=".$tmp1lat.' Lng='.$tmp1lng.' Lat2='.$tmp2lat.' Lng2='.$tmp2lng.'<BR>';             							
                                    calculate_distance($tmp1lat,$tmp2lat,$tmp1lng,$tmp2lng,$distance);                
                                    $linetolog = $CurrentLat.','.$CurrentLong.','.$LastLat.','.$LastLong.','.$distance.','.$xml_date_current.','.$xml_date_last.','.(strtotime($xml_date_current)-strtotime($xml_date_last)).','.$timeinterval.','.$distanceinterval.','.$enddate.','.$startdate."\n";
                                    $overSpeed=$distance/$dateDifference_1;
                                }								
                                if($distance<$distanceinterval)
                                {
                                    $LastDTForDif=$xml_date_current;
                                }
                                if($dateDifference_1>2.0)
                                {
                                    $cmpOverSpeed=90.0;
                                }
                                else 
                                {
                                    $cmpOverSpeed=200.0;
                                }
                                if($overSpeed<$cmpOverSpeed)
                                {
                                    $speeed_data_valid_time = $datetime;
                                }
                                
                                if((strtotime($datetime) - strtotime($speeed_data_valid_time) )>300) //data high speed for 5 mins
                                {
                                    $LastLat =$CurrentLat;
                                    $LastLong =$CurrentLong;                                  
                                }
                                /*if((((((strtotime($xml_date_current)-strtotime($xml_date_last))>$timeinterval) && ($distance>=$distanceinterval)) || ($firstData==0)) && 
                                (($xml_date_current<=$enddate) && ($xml_date_current>=$startdate))) || ($f==$total_lines-2) )*/
                                if(($distance>=$distanceinterval) || ($firstData==0))
                                {								
                                    if($overSpeed<$cmpOverSpeed)
                                    {
                                        $xml_date_last = $xml_date_current;
                                        $LastLat =$CurrentLat;
                                        $LastLong =$CurrentLong;                           
                                        $finalDistance = $finalDistance + $distance;                               
                                        $vehicleserial[]=$vserial[$i];
                                        $lat[]= $CurrentLat;
                                        $lng[]=$CurrentLong; 
                                        $alt[]="";
                                        $datetimeXml[]=$xml_date_current;
                                        $vehiclename[]=$vehicle_detail_local[0]; 
                                        $vehicletype[]=$vehicle_detail_local[1];
                                        $speed[]=$SortedDataObject->speedData[$obi];
                                        $cellname[]=$SortedDataObject->cellNameData[$obi];
                                        $serverDatetime[]=$SortedDataObject->serverDatetime[$obi];
                                        $ver[]=$SortedDataObject->versionData[$obi];
                                        $cumdist[]=round($finalDistance,2);
                                        $firstData = 1; 
                                    }
                                }
                           }
                        }
                    }
                }
            }
            if(($overSpeed<$cmpOverSpeed) && ($last_rec!=0))
            {
                $xml_date_last = $xml_date_current;
                $LastLat =$CurrentLat;
                $LastLong =$CurrentLong;                           
                $finalDistance = $finalDistance + $distance;                               
                $vehicleserial[]=$vserial[$i];
                $lat[]= $CurrentLat;
                $lng[]=$CurrentLong; 
                $alt[]="";
                $datetimeXml[]=$xml_date_current;
                $vehiclename[]=$vehicle_detail_local[0]; 
                $vehicletype[]=$vehicle_detail_local[1];
                $speed[]=$SortedDataObject->speedData[$obi];
                $cellname[]=$SortedDataObject->cellNameData[$obi];
                $serverDatetime[]=$SortedDataObject->serverDatetime[$obi];
                $ver[]=$SortedDataObject->versionData[$obi];
                $cumdist[]=round($finalDistance,2);         
            }
        }
    }
}

$o_cassandra->close();

?>


