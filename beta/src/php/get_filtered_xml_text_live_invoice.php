<?php
include_once('main_vehicle_information_1.php');
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('common_xml_element.php');
include_once("select_landmark_report.php");
include_once('calculate_distance.php');
include_once('get_location_lp_track_report_2.php');
include_once('coreDb.php');
/*
include_once("../../../phpApi/Cassandra/Cassandra.php");     //##### INCLUDE CASSANDRA API
include_once("../../../phpApi/libLog.php");    
$o_cassandra = new Cassandra();	
$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
global $o_cassandra;
*/
include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('lastRecordData.php');
include_once("getXmlData.php");	

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
$sortBy="h";
//date_default_timezone_set('Asia/Calcutta');
$current_date=date("Y-m-d");
    ////////////////////////

$pathtowrite = $_REQUEST['xml_file']; 
$mode = $_REQUEST['mode'];
$vserial = $_REQUEST['vserial'];
$vdispatch_time = $_REQUEST['vdispatch_time'];
///echo $vdispatch_time."<br>";
$vtarget_time = $_REQUEST['vtarget_time'];
//echo $vtarget_time."<br>";
$vplant_number = $_REQUEST['vplant_number'];
//echo $vplant_number."<br>";
//echo "vserial=".$vserial."<br>";
$startdate = $_REQUEST['startdate'];
$enddate = $_REQUEST['enddate'];

set_time_limit(100);
//date_default_timezone_set('Asia/Calcutta');
$current_date=date("Y-m-d");

$vserial_arr = explode(',',$vserial);

$vdispatch_time_arr = explode(',',$vdispatch_time);
$vtarget_time_arr = explode(',',$vtarget_time);
$vplant_number_arr = explode(',',$vplant_number);

$vname1 ="";
//echo "t1=";
$liveXmlData="";
$data=array();
for($i=0;$i<sizeof($vserial_arr);$i++)
{       /*
	$query_vehicle = "SELECT vehicle_assignment.device_imei_no, vehicle.vehicle_name, vehicle.vehicle_type FROM vehicle_assignment, vehicle WHERE  vehicle_assignment.device_imei_no = '$vserial_arr[$i]' AND vehicle.status =1 AND vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.status=1";
	//echo $query_vehicle."<br>";
	$resultvehicle=mysql_query($query_vehicle,$DbConnection);
	$rowv=mysql_fetch_object($resultvehicle);
	$vehicle_name=$rowv->vehicle_name;
	$vtype=$rowv->vehicle_type;
        */
        $resultvehicle=getVehicleAndTypeFromVserial($vserial_arr[$i],$DbConnection);
        foreach($resultvehicle as $rowv ) //because it return single
        {
            $vehicle_name=$rowv['vehicle_name'];
            $vtype=$rowv['vehicle_type'];
        }
	$imei = $vserial_arr[$i];
	
	$dispatch_time=$vdispatch_time_arr[$i];
	$target_time=$vtarget_time_arr[$i];
	$plant_number=$vplant_number_arr[$i];
	/*//echo $dispatch_time.'/'.$target_time."/".$plant_number."<br>";
	//echo"Before".$vehicle_name;
	get_vehicle_last_data($current_date, $imei, $last_time, $vehicle_name,$vtype,$dispatch_time,$target_time,$plant_number, &$liveXmlData);	
         */
        
        $LastRecordObject=new lastRecordData();	
	//echo "imei=".$imei."<br>";
	$LastRecordObject=getLastRecord($imei,$sortBy,$parameterizeData);
        if(!empty($LastRecordObject))
	{
                //echo "inOBJ";
		$current_time = date('Y-m-d H:i:s');
		$last_halt_time_sec = strtotime($LastRecordObject->lastHaltTimeLR[0]);			
		$current_time_sec = strtotime($current_time);
		$diff = ($current_time_sec - $last_halt_time_sec); 
		
		if($LastRecordObject->speedLR[0]>=5 && $diff <=600)
		{
			$status = "Running";
		}
		else
		{
			$status = "Stopped";
		}
                
            $data[]=array(
                            'messageTypeLR'=>$LastRecordObject->messageTypeLR[0],
                            'versionLR'=>$LastRecordObject->versionLR[0],
                            'fixLR'=>$LastRecordObject->fixLR[0],
                            'latitudeLR'=>$LastRecordObject->latitudeLR[0],
                            'longitudeLR'=>$LastRecordObject->longitudeLR[0],
                            'speedLR'=>$LastRecordObject->speedLR[0],
                            'serverDatetimeLR'=>$LastRecordObject->serverDatetimeLR[0],
                            'deviceDatetimeLR'=>$LastRecordObject->deviceDatetimeLR[0],
                            'io1LR'=>$LastRecordObject->io1LR[0],
                            'io2LR'=>$LastRecordObject->io1LR[0],
                            'io3LR'=>$LastRecordObject->io1LR[0],
                            'io4LR'=>$LastRecordObject->io1LR[0],
                            'io5LR'=>$LastRecordObject->io1LR[0],
                            'io6LR'=>$LastRecordObject->io1LR[0],
                            'io7LR'=>$LastRecordObject->io1LR[0], 
                            'io8LR'=>$LastRecordObject->io1LR[0], 
                            'sigStrLR'=>$LastRecordObject->sigStrLR[0], 
                            'suplyVoltageLR'=>$LastRecordObject->suplyVoltageLR[0], 
                            'dayMaxSpeedLR'=>$LastRecordObject->dayMaxSpeedLR[0], 
                            'dayMaxSpeedTimeLR'=>$LastRecordObject->dayMaxSpeedTimeLR[0],
                            'lastHaltTimeLR'=>$LastRecordObject->lastHaltTimeLR[0],
                            'deviceImeiNo'=>$imei,
                            'vehicleName'=>$vehicle_detail_local[0],
                            'vehilceType'=>$vehicle_detail_local[2],
                            'vehilceNumber'=>$vehicle_detail_local[2],                               
                            'status'=>$status,
                            'dispatch_time'=>$dispatch_time,
                            'target_time'=>$target_time,
                            'plant_number'=>$plant_number
                        );
                        $lat_arr_last[]=$LastRecordObject->latitudeLR[0];
                        $lng_arr_last[]=$LastRecordObject->longitudeLR[0];
                        $vehiclename_arr_last[]=$vehicle_name;
                        $vserial_arr_last[]=$imei;
                        $speed_arr_last[]=$LastRecordObject->speedLR[0];
                        $datetime_arr_last[]=$LastRecordObject->deviceDatetimeLR[0];
                        $day_max_speed_arr_last[]=$LastRecordObject->dayMaxSpeedLR[0];
                        $last_halt_time_arr_last[]=$LastRecordObject->lastHaltTimeLR[0];
                        $vehilce_status_arr[]=$status;
                        $fault_status_arr[] = "";
                        $dispatch_time_arr[]=$dispatch_time;
                        $target_time_arr[]=$target_time;
                        $plant_number_arr[]=$plant_number;
	}
	$LastRecordObject=null;
}

//print_r($data);
//echo "<textarea>".$liveXmlData."</textarea>";


       
/*
$lineF=explode("@",substr($liveXmlData,0,-1));					
for($n=0;$n<sizeof($lineF);$n++)
{
	preg_match('/d="[^" ]+/', $lineF[$n], $lat_tmp);
	$lat_tmp1 = explode("=",$lat_tmp[0]);
	$lat = substr(preg_replace('/"/', '', $lat_tmp1[1]),0,-1);
	//echo "lat=".$lat."<br>";
	$lat_arr_last[]=$lat;		//1			

	preg_match('/e="[^" ]+/', $lineF[$n], $lng_tmp);
	$lng_tmp1 = explode("=",$lng_tmp[0]);
	$lng = substr(preg_replace('/"/', '', $lng_tmp1[1]),0,-1);
	//echo "lng=".$lng."<br>";
	$lng_arr_last[]=$lng;  	//2 

		
	
	preg_match('/h="[^"]+/', $lineF[$n], $datetime_tmp);
	$datetime_tmp1 = explode("=",$datetime_tmp[0]);
	$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
	$datetime_arr_last[]=$datetime;		//3
	// echo "datetime=".$datetime."<br>";

	preg_match('/v="[^"]+/', $lineF[$n], $vserial_tmp);
	$vserial_tmp1 = explode("=",$vserial_tmp[0]);
	$vehicle_serial = preg_replace('/"/', '', $vserial_tmp1[1]);
	$vserial_arr_last[]=$vehicle_serial;	//4
	// echo "vehicle_name1=".$vehicle_serial."<br>";

	preg_match('/w="[^"]+/', $lineF[$n], $vname_tmp);
	$vname_tmp1 = explode("=",$vname_tmp[0]);
	$vehicle_name = preg_replace('/"/', '', $vname_tmp1[1]);
	$vehiclename_arr_last[]=$vehicle_name;		//5
	//echo "vehicle_name=".$vehicle_name."<br>";
	
	$lttmp = substr($lat, 0, -1);
	$lngtmp = substr($lng, 0, -1);
	$landmark = "";	
	//get_landmark($lttmp,$lngtmp,&$landmark);
	get_report_location($lttmp,$lngtmp,&$landmark);
	//echo "\nLNMRK1=".$landmark." ,lt=".$lttmp." ,lng=".$lngtmp;
	if($landmark!="")
	{		
		$landmark_last[$vehicle_name] = $landmark;
	}

	preg_match('/x="[^"]+/', $lineF[$n], $vnumber_tmp);
	$vnumber_tmp1 = explode("=",$vnumber_tmp[0]);
	$vehicle_number = preg_replace('/"/', '', $vnumber_tmp1[1]);
	$vehiclenumber_arr_last[]=$vehicle_number;		//7
	//echo "vehicle_number=".$vehicle_number."<br>";

	preg_match('/f="[^"]+/', $lineF[$n], $speed_tmp);
	$speed_tmp1 = explode("=",$speed_tmp[0]);
	$speed = preg_replace('/"/', '', $speed_tmp1[1]);                               
	if( ($speed<=3) || ($speed>200))
	{
		$speed = 0;
	}
	$speed_arr_last[]=$speed;		//6
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

	preg_match('/r="[^"]+/', $lineF[$n], $sup_v_tmp);
	$sup_v_tmp1 = explode("=",$sup_v_tmp[0]);
	$sup_v= preg_replace('/"/', '', $sup_v_tmp1[1]);

	preg_match('/s="[^"]+/', $lineF[$n], $day_max_speed_tmp);
	$day_max_speed_tmp1 = explode("=",$day_max_speed_tmp[0]);
	$day_max_speed= preg_replace('/"/', '', $day_max_speed_tmp1[1]);
	$day_max_speed_arr_last[]=$day_max_speed;		//10
	
	//echo "day_max_speed=".$day_max_speed."<br>";
	preg_match('/t="[^"]+/', $lineF[$n], $day_max_speed_time_tmp);
	$day_max_speed_time_tmp1 = explode("=",$day_max_speed_time_tmp[0]);
	$day_max_speed_time= preg_replace('/"/', '', $day_max_speed_time_tmp1[1]);
	$day_max_speed_time_arr[]=$day_max_speed_time;		//11
	// echo "day_max_speed_time=".$day_max_speed_time."<br>";

	preg_match('/u="[^"]+/', $lineF[$n], $last_halt_time_tmp);
	$last_halt_time_tmp1 = explode("=",$last_halt_time_tmp[0]);
	$last_halt_time= preg_replace('/"/', '', $last_halt_time_tmp1[1]);
	$last_halt_time_arr_last[]=$last_halt_time;		//12

	preg_match('/y="[^"]+/', $lineF[$n], $vehilce_type_tmp);
	$vehilce_type_tmp1 = explode("=",$vehilce_type_tmp[0]);
	$vehilce_type= preg_replace('/"/', '', $vehilce_type_tmp1[1]);
	$vehilce_type_arr[]=$vehilce_type;		//9
	
	preg_match('/aa="[^"]+/', $lineF[$n], $vehilce_status_tmp);
	$vehilce_status_tmp1 = explode("=",$vehilce_status_tmp[0]);
	$vehilce_status= preg_replace('/"/', '', $vehilce_status_tmp1[1]);
	$vehilce_status_arr[]=$vehilce_status;		//14
	
	
	preg_match('/dp="[^"]+/', $lineF[$n], $dispatch_time_tmp);
	$dispatch_time_tmp1 = explode("=",$dispatch_time_tmp[0]);
	$dispatch_time= preg_replace('/"/', '', $dispatch_time_tmp1[1]);
	$dispatch_time_arr[]=$dispatch_time;
	
	preg_match('/trg="[^"]+/', $lineF[$n], $target_time_tmp);
	$target_time_tmp1 = explode("=",$target_time_tmp[0]);
	$target_time= preg_replace('/"/', '', $target_time_tmp1[1]);
	$target_time_arr[]=$target_time;
	
	preg_match('/pn="[^"]+/', $lineF[$n], $plant_number_tmp);
	$plant_number_tmp1 = explode("=",$plant_number_tmp[0]);
	$plant_number= preg_replace('/"/', '', $plant_number_tmp1[1]);
	$plant_number_arr[]=$plant_number;
	
	preg_match('/gps="[^"]+/', $lineF[$n], $gps_status_tmp);
	$gps_status_tmp1 = explode("=",$gps_status_tmp[0]);
	$gps_status= preg_replace('/"/', '', $gps_status_tmp1[1]);

	if($gps_status==0)
	{
		$fault_status_arr[] = "2";
	}
	else if($sup_v >=6 && $sup_v <=8)
	{
		$fault_status_arr[] = "1";
	}
        else if($sup_v <6)
        {
                $fault_status_arr[] = "4";
        }
	else
	{
		$fault_status_arr[] = "-";
	}
	
	
}

$user_type_id=getUserTypeIdAccountFeature($accountId,$DbConnection);
//$user_type_id=substr($row1->user_type_id,-1);
$user_type_id=substr($user_type_id,-1);
if($user_type_id=="6")
{
	$type="P";		//15
}
else
{
	$type="V";
}
*/
?>
