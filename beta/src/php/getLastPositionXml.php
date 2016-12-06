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
include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('lastDataObj.php');
include_once('lastRecordData.php');
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
$parameterizeData->io1='i';
$parameterizeData->io2='j';
$parameterizeData->io3='k';
$parameterizeData->io4='l';
$parameterizeData->io5='m';
$parameterizeData->io6='n';
$parameterizeData->io7='o';
$parameterizeData->io8='p';	
$parameterizeData->sigStr='q';
$parameterizeData->supVoltage='r';
$parameterizeData->dayMaxSpeed='s';
$parameterizeData->dayMaxSpeedTime='t';
$parameterizeData->lastHaltTime='u';
$parameterizeData->cellName='ab';	
$linetmp="";
$vname_str ="";
$vnumber_str ="";
for($i=0;$i<$vsize;$i++)
{
    $vehicle_info=get_vehicle_info($root,$vserial[$i]);
    $vehicle_detail_local=explode(",",$vehicle_info);
    $io_type_value[]=$vehicle_detail_local[7];    
    
    $vname_str = $vname_str.$vehicle_detail_local[0].":";
    $vnumber_str = $vnumber_str.$vehicle_detail_local[8].":";
   
    $LastSortedDate = getLastSortedDate($vserial[$i],$datefrom,$dateto);
    $LastDataObject =null;
    $LastDataObject=new lastDataObj();		
    $type="unSorted";
    //var_dump($LastDataObject);
    getLastPositionXMl($vserial[$i],$date1,$date2,$datefrom,$dateto,$sortBy,$type,$parameterizeData,$LastDataObject);
    //var_dump($LastDataObject);
    if ($LastDataObject->deviceDatetimeLD[0]!="")
    {       
        $linetmp=$linetmp.'<x a="'.$LastDataObject->messageTypeLD[0].'" b="'.$LastDataObject->versionLD[0].'" c="'.$LastDataObject->fixLD[0].'" d="'.$LastDataObject->latitudeLD[0].'" e="'.$LastDataObject->longitudeLD[0].'" f="'.$LastDataObject->speedLD[0].'" g="'.$LastDataObject->serverDatetimeLD[0].'" h="'.$LastDataObject->deviceDatetimeLD[0].'" i="'.$LastDataObject->io1LD[0].'" j="'.$LastDataObject->io2LD[0].'" k="'.$LastDataObject->io3LD[0].'" l="'.$LastDataObject->io4LD[0].'" m="'.$LastDataObject->io5LD[0].'" n="'.$LastDataObject->io6LD[0].'" o="'.$LastDataObject->io7LD[0].'" p="'.$LastDataObject->io8LD[0].'" q="'.$LastDataObject->sigStrLD[0].'" r="'.$LastDataObject->suplyVoltageLD[0].'" s="'.$LastDataObject->dayMaxSpeedLD[0].'" t="'.$LastDataObject->dayMaxSpeedTimeLD[0].'" u="'.$LastDataObject->lastHaltTimeLD[0].'" v="'.$vserial[$i].'" w="'.$vehicle_detail_local[0].'" x="'.$vehicle_detail_local[2].'" y="'.$vehicle_detail_local[1].'" z="'.$vehicle_detail_local[8].'" aa="'.$vehicle_detail_local[9].'"/>#';
    }	
}
		
$vname1=substr($vname_str,0,-1); /////////for last position text report
$vnumber1=substr($vnumber_str,0,-1); /////////for last position text report		

	if($home_report_type=="map_report")
	{
            if($report_type=="Vehicle")
            {
                    //echo "<textarea>".$linetmp."</textarea>";
                            $lineF=explode("#",substr($linetmp,0,-1));					
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

                                    preg_match('/z="[^"]+/', $lineF[$n], $driver_name_tmp);
                                    $driver_name_tmp_tmp1 = explode("=",$driver_name_tmp[0]);
                                    $driver_name = preg_replace('/"/', '', $driver_name_tmp_tmp1[1]);
                                    $driverNameArr[]=$driver_name;
                                    
                                    preg_match('/aa="[^"]+/', $lineF[$n], $dmobno_tmp);
                                    $dmobno_tmp1 = explode("=",$dmobno_tmp[0]);
                                    $dmobno = preg_replace('/"/', '', $dmobno_tmp1[1]);
                                    $dMobileNoArr[]=$dmobno;

                                    $io_str="";
                                    if($io_type_value[$n]!="tmp_str")
                                    {
                                            $iotype_iovalue_str=explode(":",$io_type_value[$n]);

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
                                                                            if($io_values<=500)
                                                                            {					
                                                                                    $io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>&nbsp;:&nbsp;</td><td class='live_td_css2'>ON</td></tr>";
                                                                            }
                                                                            else
                                                                            {
                                                                                    $io_str=$io_str."<tr><td class='live_td_css1'>".$iotype_iovalue_str1[1]."</td><td>:</td><td class='live_td_css2'>Off</td></tr>";
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
                            //print_r($lat_arr_last);
                            //print_r($lng_arr_last);
                            //print_r($vehilce_type_arr);
                            $googleMapthisapi=new GoogleMapHelper();
                            echo $googleMapthisapi->addMultipleMarkerLast("map_canvas",$lat_arr_last,$lng_arr_last,$datetime_arr_last,$vserial_arr_last,$vehiclename_arr_last,$speed_arr_last,$vehiclenumber_arr_last,$io_str_last,$vehilce_type_arr,$day_max_speed_arr_last,$last_halt_time_arr_last,$driverNameArr,$dMobileNoArr);

            }
            else
            {
                //echo "<textarea>".$linetmp."</textarea>";
                $lineF=explode("#",substr($linetmp,0,-1));
                //echo "size=".sizeof($lineF)."<br>";
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
                    //echo "datetime=".$datetime."<br>";

                    preg_match('/v="[^"]+/', $lineF[$n], $vserial_tmp);
                    $vserial_tmp1 = explode("=",$vserial_tmp[0]);
                    $vehicle_serial = preg_replace('/"/', '', $vserial_tmp1[1]);
                    $vserial_arr_last[]=$vehicle_serial;
                    // echo "vehicle_name1=".$vehicle_serial."<br>";

                    preg_match('/w="[^"]+/', $lineF[$n], $vname_tmp);
                    $vname_tmp1 = explode("=",$vname_tmp[0]);
                    $vehicle_name = preg_replace('/"/', '', $vname_tmp1[1]);
                    $vehiclename_arr_last[]=$vehicle_name;
					
                    /*preg_match('/z="[^"]+/', $lineF[$n], $vnumber_tmp);
                    $vnumber_tmp1 = explode("=",$vnumber_tmp[0]);
                    $vnumber = preg_replace('/"/', '', $vnumber_tmp1[1]);
                    $vnumber_arr_last[]=$vnumber;*/
                    //echo "vehicle_name=".$vehicle_name."<br>";

                    /*preg_match('/z="[^"]+/', $lineF[$n], $dmobno_tmp);
                    $dmobno_tmp1 = explode("=",$dmobno_tmp[0]);
                    $dmobno = preg_replace('/"/', '', $dmobno_tmp1[1]);
                    $dMobileNoArr[]=$dmobno;
                    //echo "vehicle_name=".$vehicle_name."<br>";
                    
                    preg_match('/z="[^"]+/', $lineF[$n], $driver_name_tmp);
                    $driver_name_tmp_tmp1 = explode("=",$driver_name_tmp[0]);
                    $driver_name = preg_replace('/"/', '', $driver_name_tmp_tmp1[1]);
                    $driverNameArr[]=$driver_name;*/

                    preg_match('/aa="[^"]+/', $lineF[$n], $dmobno_tmp);
                    $dmobno_tmp1 = explode("=",$dmobno_tmp[0]);
                    $dmobno = preg_replace('/"/', '', $dmobno_tmp1[1]);
                    $dMobileNoArr[]=$dmobno;
                                    
                    }				
                    $googleMapthisapi=new GoogleMapHelper();							
                    echo $googleMapthisapi->addPersonMultipleMarkerLast("map_canvas",$lat_arr_last,$lng_arr_last,$datetime_arr_last,$vserial_arr_last,$vehiclename_arr_last,$dMobileNoArr);
		}
	}
	else
	{		
		if($report_type=="Vehicle")
		{
			$lineF=explode("#",substr($linetmp,0,-1));					
			for($n=0;$n<sizeof($lineF);$n++)
			{
				preg_match('/d="[^" ]+/', $lineF[$n], $lat_tmp);
				$lat_tmp1 = explode("=",$lat_tmp[0]);
				$lat[] = substr(preg_replace('/"/', '', $lat_tmp1[1]),0,-1);									

				preg_match('/e="[^" ]+/', $lineF[$n], $lng_tmp);
				$lng_tmp1 = explode("=",$lng_tmp[0]);
				$lng[] = substr(preg_replace('/"/', '', $lng_tmp1[1]),0,-1);			                   
				
				preg_match('/h="[^"]+/', $lineF[$n], $datetime_tmp);
				$datetime_tmp1 = explode("=",$datetime_tmp[0]);
				$datetimeXml[] = preg_replace('/"/', '', $datetime_tmp1[1]);
				

				preg_match('/v="[^"]+/', $lineF[$n], $vserial_tmp);
				$vserial_tmp1 = explode("=",$vserial_tmp[0]);
				$vehicleserial[] = preg_replace('/"/', '', $vserial_tmp1[1]);

				preg_match('/w="[^"]+/', $lineF[$n], $vname_tmp);
				$vname_tmp1 = explode("=",$vname_tmp[0]);
				$vehiclename[] = preg_replace('/"/', '', $vname_tmp1[1]);
			

				preg_match('/x="[^"]+/', $lineF[$n], $vnumber_tmp);
				$vnumber_tmp1 = explode("=",$vnumber_tmp[0]);
				$vehiclenumber[] = preg_replace('/"/', '', $vnumber_tmp1[1]);
                                
                                preg_match('/aa="[^"]+/', $lineF[$n], $dmobno_tmp);
                                $dmobno_tmp1 = explode("=",$dmobno_tmp[0]);
                                $dmobno = preg_replace('/"/', '', $dmobno_tmp1[1]);
                                $dMobileNoArr[]=$dmobno;
		

				preg_match('/f="[^"]+/', $lineF[$n], $speed_tmp);
				$speed_tmp1 = explode("=",$speed_tmp[0]);
				$speedLocal = preg_replace('/"/', '', $speed_tmp1[1]);                               
				if( ($speedLocal<=3) || ($speedLocal>200))
				{
					$speedLocal = 0;
				}
				$speed[]=$speedLocal;
				//echo "speed=".$speed."<br>";
				preg_match('/i="[^"]+/', $lineF[$n], $io1_tmp);
				$io1_tmp1 = explode("=",$io1_tmp[0]);
				$io1[]= preg_replace('/"/', '', $io1_tmp1[1]);
				// echo "io1=".$io1."<br>";

				preg_match('/j="[^"]+/', $lineF[$n], $io2_tmp);
				$io2_tmp1 = explode("=",$io2_tmp[0]);
				$io2[]= preg_replace('/"/', '', $io2_tmp1[1]);
				// echo "io2=".$io2."<br>";

				preg_match('/k="[^"]+/', $lineF[$n], $io3_tmp);
				$io3_tmp1 = explode("=",$io3_tmp[0]);
				$io3[]= preg_replace('/"/', '', $io3_tmp1[1]);
				//echo "io3=".$io3."<br>";

				preg_match('/l="[^"]+/', $lineF[$n], $io4_tmp);
				$io4_tmp1 = explode("=",$io4_tmp[0]);
				$io4[]= preg_replace('/"/', '', $io4_tmp1[1]);
				//echo "io4=".$io4."<br>";

				preg_match('/m="[^"]+/', $lineF[$n], $io5_tmp);
				$io5_tmp1 = explode("=",$io5_tmp[0]);
				$io5[]= preg_replace('/"/', '', $io5_tmp1[1]);
				//echo "io5=".$io5."<br>";

				preg_match('/n="[^"]+/', $lineF[$n], $io6_tmp);
				$io6_tmp1 = explode("=",$io6_tmp[0]);
				$io6[]= preg_replace('/"/', '', $io6_tmp1[1]);
				//echo "io6=".$io6."<br>";

				preg_match('/o="[^"]+/', $lineF[$n], $io7_tmp);
				$io7_tmp1 = explode("=",$io7_tmp[0]);
				$io7[]= preg_replace('/"/', '', $io7_tmp1[1]);
				// echo "io7=".$io7."<br>";

				preg_match('/p="[^"]+/', $lineF[$n], $io8_tmp);
				$io8_tmp1 = explode("=",$io8_tmp[0]);
				$io8[]= preg_replace('/"/', '', $io8_tmp1[1]);
				
				preg_match('/y="[^"]+/', $lineF[$n], $vehilce_type_tmp);
				$vehilce_type_tmp1 = explode("=",$vehilce_type_tmp[0]);
				$vehicletype[]= preg_replace('/"/', '', $vehilce_type_tmp1[1]);
			}
		}
		else
		{
			$lineF=explode("#",substr($linetmp,0,-1));					
			for($n=0;$n<sizeof($lineF);$n++)
			{
				preg_match('/d="[^" ]+/', $lineF[$n], $lat_tmp);
				$lat_tmp1 = explode("=",$lat_tmp[0]);
				$lat[] = substr(preg_replace('/"/', '', $lat_tmp1[1]),0,-1);									

				preg_match('/e="[^" ]+/', $lineF[$n], $lng_tmp);
				$lng_tmp1 = explode("=",$lng_tmp[0]);
				$lng[] = substr(preg_replace('/"/', '', $lng_tmp1[1]),0,-1);			                   
				
				preg_match('/h="[^"]+/', $lineF[$n], $datetime_tmp);
				$datetime_tmp1 = explode("=",$datetime_tmp[0]);
				$datetimeXml[] = preg_replace('/"/', '', $datetime_tmp1[1]);
				

				preg_match('/v="[^"]+/', $lineF[$n], $vserial_tmp);
				$vserial_tmp1 = explode("=",$vserial_tmp[0]);
				$vehicleserial[] = preg_replace('/"/', '', $vserial_tmp1[1]);

				preg_match('/w="[^"]+/', $lineF[$n], $vname_tmp);
				$vname_tmp1 = explode("=",$vname_tmp[0]);
				$vehiclename[] = preg_replace('/"/', '', $vname_tmp1[1]);
			

				/*preg_match('/x="[^"]+/', $lineF[$n], $vnumber_tmp);
				$vnumber_tmp1 = explode("=",$vnumber_tmp[0]);
				$vehiclenumber[] = preg_replace('/"/', '', $vnumber_tmp1[1]);*/
                                
                                preg_match('/aa="[^"]+/', $lineF[$n], $dmobno_tmp);
                                $dmobno_tmp1 = explode("=",$dmobno_tmp[0]);
                                $dmobno = preg_replace('/"/', '', $dmobno_tmp1[1]);
                                $vehiclenumber[]=$dmobno;
                                $dMobileNoArr[]=$dmobno;
                                

				preg_match('/f="[^"]+/', $lineF[$n], $speed_tmp);
				$speed_tmp1 = explode("=",$speed_tmp[0]);
				$speedLocal = preg_replace('/"/', '', $speed_tmp1[1]);                               
				if( ($speedLocal<=3) || ($speedLocal>200))
				{
					$speedLocal = 0;
				}
				$speed[]=$speedLocal;
				//echo "speed=".$speed."<br>";
				preg_match('/i="[^"]+/', $lineF[$n], $io1_tmp);
				$io1_tmp1 = explode("=",$io1_tmp[0]);
				$io1[]= preg_replace('/"/', '', $io1_tmp1[1]);
				// echo "io1=".$io1."<br>";

				preg_match('/j="[^"]+/', $lineF[$n], $io2_tmp);
				$io2_tmp1 = explode("=",$io2_tmp[0]);
				$io2[]= preg_replace('/"/', '', $io2_tmp1[1]);
				// echo "io2=".$io2."<br>";

				preg_match('/k="[^"]+/', $lineF[$n], $io3_tmp);
				$io3_tmp1 = explode("=",$io3_tmp[0]);
				$io3[]= preg_replace('/"/', '', $io3_tmp1[1]);
				//echo "io3=".$io3."<br>";

				preg_match('/l="[^"]+/', $lineF[$n], $io4_tmp);
				$io4_tmp1 = explode("=",$io4_tmp[0]);
				$io4[]= preg_replace('/"/', '', $io4_tmp1[1]);
				//echo "io4=".$io4."<br>";

				preg_match('/m="[^"]+/', $lineF[$n], $io5_tmp);
				$io5_tmp1 = explode("=",$io5_tmp[0]);
				$io5[]= preg_replace('/"/', '', $io5_tmp1[1]);
				//echo "io5=".$io5."<br>";

				preg_match('/n="[^"]+/', $lineF[$n], $io6_tmp);
				$io6_tmp1 = explode("=",$io6_tmp[0]);
				$io6[]= preg_replace('/"/', '', $io6_tmp1[1]);
				//echo "io6=".$io6."<br>";

				preg_match('/o="[^"]+/', $lineF[$n], $io7_tmp);
				$io7_tmp1 = explode("=",$io7_tmp[0]);
				$io7[]= preg_replace('/"/', '', $io7_tmp1[1]);
				// echo "io7=".$io7."<br>";

				preg_match('/p="[^"]+/', $lineF[$n], $io8_tmp);
				$io8_tmp1 = explode("=",$io8_tmp[0]);
				$io8[]= preg_replace('/"/', '', $io8_tmp1[1]);
				
				preg_match('/y="[^"]+/', $lineF[$n], $vehilce_type_tmp);
				$vehilce_type_tmp1 = explode("=",$vehilce_type_tmp[0]);
				$vehicletype[]= preg_replace('/"/', '', $vehilce_type_tmp1[1]);
			}
		}
	}
		
	
	

?>
