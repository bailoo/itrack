<?php
//set_time_limit(3000);	
date_default_timezone_set("Asia/Kolkata");
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('user_type_setting.php');	
include_once('calculate_distance.php');

include_once('xmlParameters.php');    
include_once('parameterizeData.php');   
include_once('data.php');
include_once("getXmlData.php");
include_once('googleMapApi.php');	

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
$parameterizeData->cellName='ab';
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
$distanceinterval=0.0;  
if($home_report_type=="map_report" || $home_report_type=="play_report")
{		
    if($report_type=="Vehicle")
    {
	for($i=0;$i<$vsize;$i++)
	{
            //echo "in for<br>";
            $lat_arr_last=array();
            $lng_arr_last=array();
            $datetime_arr_last=array();
            $vserial_arr_last=array();
            $vehiclename_arr_last=array();
            $vehiclenumber_arr_last=array();
            $speed_arr_last=array();
            $distance_travel_arr=array();
            $io_str_last=array();
           
            $lineTmpTrack="";
            $linetowrite="";
           
            $CurrentLat = 0.0;
            $CurrentLong = 0.0;
            $LastLat = 0.0;
            $LastLong = 0.0;
            $LastDTForDiff = "";
            $firstData = 0;
            $distance =0.0;
            $xml_date_current="";
            $xml_date_last="";
            $overSpeed=0;
            $finalDistance=0.0;
            $vehicle_info=get_vehicle_info($root,$vserial[$i]);
            $vehicle_detail_local=explode(",",$vehicle_info);
            $finalVName=$vehicle_detail_local[0];
            $finalVType=$vehicle_detail_local[1];
            $finalVNum=$vehicle_detail_local[2];            

            $LastSortedDate = getLastSortedDate($vserial[$i],$datefrom,$dateto);
            $SortedDataObject=null;
            $SortedDataObject=new data();
            readFileXmlNew($vserial[$i], $datefrom,  $requiredData, $sortBy, $parameterizeData,$SortedDataObject);	
           // var_dump($SortedDataObject);
            if(count($SortedDataObject->deviceDatetime)>0)
            {						
                $sortedSize=sizeof($SortedDataObject->deviceDatetime);
                //echo "dataSize=".$sortedSize."<br>";
                for($obi=0;$obi<$sortedSize;$obi++)
                {
                    $DataValid = 0;
                    $finalLatitude=$SortedDataObject->latitudeData[$obi];
                    $finalLongitude=$SortedDataObject->longitudeData[$obi];
                    if((strlen($finalLatitude)>5) && ($finalLatitude!="-") && (strlen($finalLongitude)>5) && ($finalLongitude!="-"))
                    {
                        $DataValid = 1;
                    }
                    if($DataValid==1)
                    { 
                        $datetime=$SortedDataObject->deviceDatetime[$obi];
                        $xml_date_current=$datetime;
                        if(strtotime($xml_date_current)-strtotime($xml_date_last)>$timeinterval)
                        {
                            $finalSDateTime=$SortedDataObject->serverDatetime[$obi];				

                            $finalSpeed=$SortedDataObject->speedData[$obi];                    
                            $finalMT=$SortedDataObject->messageTypeData[$obi];
                            $finalVer=$SortedDataObject->versionData[$obi];
                            $finalFix=$SortedDataObject->fixData[$obi];
                            $finalCN=$SortedDataObject->cellNameData[$obi];
                            $finalSS=isset($SortedDataObject->sigStrData[$obi])?$SortedDataObject->sigStrData[$obi]:'-';
                            $finalSV=$SortedDataObject->supVoltageData[$obi];
                            $finalDMSV=$SortedDataObject->dayMaxSpeedData[$obi];
                            $finalLHT=$SortedDataObject->lastHaltTimeData[$obi];
                            $finalio1=$SortedDataObject->io1Data[$obi];
                            $finalio2=$SortedDataObject->io2Data[$obi];
                            $finalio3=$SortedDataObject->io3Data[$obi];
                            $finalio4=$SortedDataObject->io4Data[$obi];
                            $finalio5=$SortedDataObject->io5Data[$obi];
                            $finalio6=$SortedDataObject->io6Data[$obi];
                            $finalio7=$SortedDataObject->io7Data[$obi];
                            $finalio8=$SortedDataObject->io8Data[$obi];                       	


                            $CurrentLat = $finalLatitude;
                            $CurrentLong = $finalLongitude;;

                            $CurrentDTForDiffTmp=strtotime($datetime);
                            if($firstData==1)
                            {                                           
                                $tmp1lat = round(substr($CurrentLat,1,(strlen($CurrentLat)-3)),4);
                                $tmp2lat = round(substr($LastLat,1,(strlen($LastLat)-3)),4);
                                $tmp1lng = round(substr($CurrentLong,1,(strlen($CurrentLong)-3)),4);
                                $tmp2lng = round(substr($LastLong,1,(strlen($LastLong)-3)),4);  							
                                //echo  "Coord: ".$tmp1lat.' '.$tmp2lat.' '.$tmp1lng.' '.$tmp2lng.'<BR>'; 
                                //echo "lastDate=".$LastDTForDif."<br>";
                                $LastDTForDiffTS=strtotime($LastDTForDif);									
                                $dateDifference=($CurrentDTForDiffTmp-$LastDTForDiffTS)/3600;
                                $dateDifference_1=round($dateDifference,5);
                                //echo" dateDifference=".round($dateDifference,5)."<br>";
                                //echo  "dateDifference: ".$dateDifference.'<BR>'; 									
                                calculate_distance($tmp1lat,$tmp2lat,$tmp1lng,$tmp2lng,$distance);
                                //$linetolog = $CurrentLat.','.$CurrentLong.','.$LastLat.','.$LastLong.','.$distance.','.$xml_date_current.','.$xml_date_last.','.(strtotime($xml_date_current)-strtotime($xml_date_last)).','.$timeinterval.','.$distanceinterval.','.$enddate.','.$startdate."\n";
                                //echo "distance=".$distance."<br>";									
                                $overSpeed=$distance/$dateDifference_1;
                                $overSpeed=isset($overSpeed)?$overSpeed:300;
                            }
                            //echo "distance=".$distance."distanceInterval=".$distanceinterval."<br>";
                            if($distance<$distanceinterval)
                            {
                                $LastDTForDif=$datetime;
                            }
                            if(($distance>=$distanceinterval) || ($firstData==0))
                            {
                                $xml_date_last=$xml_date_current;
                                if($overSpeed<200)
                                {
                                    $LastLat =$CurrentLat;
                                    $LastLong =$CurrentLong;									

                                    $LastDTForDif=$xml_date_current;
                                    //$line = substr($line, 0, -3);   // REMOVE LAST TWO /> CHARARCTER
                                    $finalDistance = $finalDistance + $distance;									

                                    $linetowrite='<x a="'.$finalMT.'" b="'.$finalVer.'" c="'.$finalFix.'" d="'.$finalLatitude.'" e="'.$finalLongitude.'" f="'.$finalSpeed.'" g="'.$finalSDateTime.'" h="'.$datetime.'" i="'.$finalio1.'" j="'.$finalio2.'" k="'.$finalio3.'" l="'.$finalio4.'" m="'.$finalio5.'" n="'.$finalio6.'" o="'.$finalio7.'" p="'.$finalio8.'" q="'.$finalSS.'" r="'.$finalSV.'" s="'.$finalDMSV.'" v="'.$vserial[$i].'" w="'.$finalVName.'" x="'.$finalVNum.'" y="'.$finalVType.'" z="'.round($finalDistance,2).'"/>';
                                    //echo "<textarea>".$linetowrite."</textarea>";
                                    //$linetowrite = "\n".$line.' v="'.$vehicle_serial.'" w="'.$vname.'" x="'.$vehicle_number.'" z="'.round($finalDistance,2).'"/>'; // for distance       // ADD DISTANCE

                                    $firstData = 1;  
                                    $lineTmpTrack=$lineTmpTrack.$linetowrite."@"; 
                                }
                            }
                        }
                    }
                }
            }
            //echo "<textarea>".$lineTmpTrack."</textarea><br><br>";
            $io_type_value=$vehicle_detail_local[7];
            //getTrackMap($vserial[0], $vehicle_detail_local[0], $vehicle_detail_local[2], $startdate,$enddate,$xmltowrite,$timeinterval,$distanceinterval,&$lineTmpTrack);
            //echo "linetmp=".$linetmp."<br>";
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


                preg_match('/u="[^"]+/', $lineF[$n], $last_halt_time_tmp);
                if(count($last_halt_time_tmp)>0)
                {
                $last_halt_time_tmp1 = explode("=",$last_halt_time_tmp[0]);
                $last_halt_time= preg_replace('/"/', '', $last_halt_time_tmp1[1]);
                $last_halt_time_arr_last[]=$last_halt_time;
                }
                else
                {
                $last_halt_time_arr_last[]='-';
                }
                

                preg_match('/ y="[^"]+/', $lineF[$n], $vehilce_type_tmp);
                $vehilce_type_tmp1 = explode("=",$vehilce_type_tmp[0]);
                $vehilce_type= preg_replace('/"/', '', $vehilce_type_tmp1[1]);
                $vehilce_type_arr[]=$vehilce_type;

                preg_match('/z="[^"]+/', $lineF[$n], $distance_travel_tmp);
                $distance_travel_tmp1 = explode("=",$distance_travel_tmp[0]);
                $distance_travel= preg_replace('/"/', '', $distance_travel_tmp1[1]);
                $distance_travel_arr[]=$distance_travel;

                $io_str="";
                if($io_type_value!="tmp_str")
                {
                    $iotype_iovalue_str=explode(":",$io_type_value);
                    for($ioi=0;$ioi<sizeof($iotype_iovalue_str);$ioi++)
                    {
                        $iotype_iovalue_str1=explode("^",$iotype_iovalue_str[$ioi]);							
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
                                    $io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>".$io_values."</td></tr>";
                                }
                                else
                                {
                                    $io_str=$io_str."<tr><td class='live_td_css1'>Temperature</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>-</td></tr>";
                                }
                            }
                            else
                            {
                                $io_str=$io_str."<tr><td class='live_td_css1'>Temperature</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>-</td></tr>";
                            }
                        }
                        else if($iotype_iovalue_str1[1]!="")
                        {
                            if(trim($iotype_iovalue_str1[1])=="engine")
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
            
            if($flag_play==1)
            {				
                echo $googleMapthisapi->addMultipleMarker_play("map_canvas",$lat_arr_last,$lng_arr_last,$datetime_arr_last,$vserial_arr_last,$vehiclename_arr_last,$speed_arr_last,$vehiclenumber_arr_last,$io_str_last,$distance_travel_arr,$play_interval);
            }
            else
            {
                echo $googleMapthisapi->addMultipleMarker("map_canvas",$lat_arr_last,$lng_arr_last,$datetime_arr_last,$vserial_arr_last,$vehiclename_arr_last,$speed_arr_last,$vehiclenumber_arr_last,$io_str_last,$distance_travel_arr,'-');// dase is mobile no
            }
	}
    }
}
?>
