<?php
include_once('main_vehicle_information_1.php');
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('common_xml_element.php');
include_once("select_landmark_report.php");
include_once('calculate_distance.php');
include_once('get_location_lp_track_report_2.php');

$pathtowrite = $_REQUEST['xml_file']; 
$mode = $_REQUEST['mode'];
$vserial = $_REQUEST['vserial'];
$vdispatch_time = $_REQUEST['vdispatch_time'];
$vtarget_time = $_REQUEST['vtarget_time'];
$vplant_number = $_REQUEST['vplant_number'];
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
for($i=0;$i<sizeof($vserial_arr);$i++)
{
	$query_vehicle = "SELECT vehicle_assignment.device_imei_no, vehicle.vehicle_name, vehicle.vehicle_type FROM vehicle_assignment, vehicle WHERE  vehicle_assignment.device_imei_no = '$vserial_arr[$i]' AND vehicle.status =1 AND vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.status=1";
	//echo $query_vehicle."<br>";
	$resultvehicle=mysql_query($query_vehicle,$DbConnection);
	$rowv=mysql_fetch_object($resultvehicle);
	$vehicle_name=$rowv->vehicle_name;
	$vtype=$rowv->vehicle_type;
	$imei = $vserial_arr[$i];
	
	$dispatch_time=$vdispatch_time_arr[$i];
	$target_time=$vtarget_time_arr[$i];
	$plant_number=$vplant_number_arr[$i];
	//echo $dispatch_time.'/'.$target_time."/".$plant_number."<br>";
	//echo"Before".$vehicle_name;
	get_vehicle_last_data($current_date, $imei, $last_time, $vehicle_name,$vtype,$dispatch_time,$target_time,$plant_number, &$liveXmlData);
	/*$tmp = explode('#',$vserial_arr[$i]);
	$imei = $tmp[0];	
	$vehicle_route_arr[]=$tmp[1];		//13
	//$last_time = $tmp[1];
	$vehicle_info=get_vehicle_info($root,$imei);
	//echo "vehicle_info=".$vehicle_info."<br>";
	$vehicle_detail_local=explode(",",$vehicle_info);
	$imei_ios[$imei] = $vehicle_detail_local[7];	
	//echo "veserial=".$imei_ios[$imei]."<br>";
	get_vehicle_last_data($current_date, $imei, $last_time, $vehicle_detail_local[0],$vehicle_detail_local[1], &$liveXmlData);
	*/
}

//echo "<textarea>".$liveXmlData."</textarea>";

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
	
	/*$io_str="";
	
	if($imei_ios[$vehicle_serial]!="tmp_str")
	{
		//echo "<br>IO_TYPE_VAL=".$imei_ios[$vehicle_serial];
		$iotype_iovalue_str=explode(":",$imei_ios[$vehicle_serial]);
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
				$iotype_iovalue_str1[1]="Temperature";
				
				if($io_values!="")
				{
					if($io_values>=-30 && $io_values<=70)
					{					
						$io_str=$io_str.$iotype_iovalue_str1[1].' : <font color=red>'.preg_replace('/[^0-9-]/s','.',$io_values).'</font>, ';						
					}
					else
					{
						$io_str=$io_str.'Temperature : -, ';	
					
					}
				}
				else
				{
					//echo "in if 2";
					$io_str=$io_str.'Temperature : -, ';
				}
			}
			else if($iotype_iovalue_str1[1]=="engine")
			{
				$iotype_iovalue_str1[1] = "Engine";
				if($io_values!="")
				{
					if($io_values < 500)
					{
						$io_str=$io_str.$iotype_iovalue_str1[1].' : <font color=red>OFF</font>, ';					
					}
					else if($io_values > 500)
					{
						$io_str=$io_str.$iotype_iovalue_str1[1].' : <font color=green>ON</font>, ';					
					}						
				}
				else
				{
					$io_str=$io_str.$iotype_iovalue_str1[1].' : -, ';						
				}			
			}
			else if($iotype_iovalue_str1[1]=="ac")
			{
				$iotype_iovalue_str1[1] = "AC";
				if($io_values!="")
				{
					if($io_values > 500)
					{
						$io_str=$io_str.$iotype_iovalue_str1[1].' : <font color=red>OFF</font>, ';					
					}
					else if($io_values > 500)
					{
						$io_str=$io_str.$iotype_iovalue_str1[1].' : <font color=green>ON</font>, ';					
					}						
				}
				else
				{
					$io_str=$io_str.$iotype_iovalue_str1[1].' : -, ';						
				}			
			}		
			else if($iotype_iovalue_str1[1]!="")
			{
				if($io_values!="")
				{
					$io_str=$io_str.$iotype_iovalue_str1[1].' : '.preg_replace('/[^0-9-]/s','.',$io_values).', ';					
				}
				else
				{
					$io_str=$io_str.$iotype_iovalue_str1[1].' : -,';						
				}			
			}				
		}
	}
	$io_str = substr_replace($io_str, "", -1);
	$io_str_last[]=$io_str;     //8*/
//echo "<br>IOSTR=".$io_str;

//echo "<br>".$io_str1;		
}

//print_r($lat_arr_last);
//print_r($lng_arr_last);
//print_r($io_str_last);
//print_r($day_max_speed_time_arr);
//print_r($vehilce_status_arr);
//$vehilce_status_arr

$query1="SELECT user_type_id FROM account_feature WHERE account_id='$account_id'";
if($DEBUG){print $query1;}
$result1=mysql_query($query1,$DbConnection);
$row1=mysql_fetch_object($result1);
//echo "usrtyoe=".$row1->user_type_id;
$user_type_id=substr($row1->user_type_id,-1);
if($user_type_id=="6")
{
	$type="P";		//15
}
else
{
	$type="V";
}

//echo "<br>LatARR_Size1=".sizeof($lat_arr_last);
	/*echo "$lat_arr_last,$lng_arr_last,$datetime_arr_last,$vserial_arr_last,$vehiclename_arr_last,$speed_arr_last,$vehiclenumber_arr_last,$io_str_last,$vehilce_type_arr,$day_max_speed_arr_last,$day_max_speed_time_arr,$last_halt_time_arr_last,$vehicle_route_arr,$vehilce_status_arr,$type";
*/
?>
