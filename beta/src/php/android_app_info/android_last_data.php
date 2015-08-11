<?php
set_time_limit(2000);
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
include_once("android_calculate_distance.php");
include_once("util_android_hr_min_sec.php");
require_once "lib/nusoap.php"; 

 $pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//print_r($pathInPieces);
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2];
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3];
//echo "pathToRoot=".$pathToRoot."<br>";
	//====cassamdra //////////////
   include_once($pathToRoot.'/beta/src/php/xmlParameters.php');
    include_once($pathToRoot.'/beta/src/php/parameterizeData.php'); /////// for seeing parameters
    include_once($pathToRoot.'/beta/src/php/data.php');   
    include_once($pathToRoot.'/beta/src/php/getDeviceDataTest.php');
    
    
    /*$vehicleserialWithIo="862170017134329:#,";
    $startDate="2015/08/06 00:00:00";
    $endDate="2015/08/06 16:38:36";
    $userInterval="30";
    $result=getHaltDeviceDataPrev($vehicleserialWithIo,$startDate,$endDate,$userInterval);
    echo $result;*/
function getLastDeviceDataPrev($vehicleserialWithIo,$startDate,$endDate)
{
    global $DbConnection;
$device_str= $_POST["vehicleserialWithIo"];
//$device_str="862170018368900:862170018371144:# , ,";
$device_str = explode('#',$device_str);
//echo "device_str1=".$device_str[0]."<br>";
//echo "device_str2=".$device_str[1]."<br>";
$vserial = explode(':',substr($device_str[0],0,-1));
$iotype_element = explode(',',substr($device_str[1],0,-1));
$startdate = $_POST['startDate'];
$enddate = $_POST['endDate']; 
/*$startdate = "2013/11/06 00:00:00";
$enddate = "2013/11/06 12:58:24";*/ 

$startdate = str_replace('/', '-', $startdate);  
$enddate = str_replace('/', '-', $enddate); 

global $last_location_report_data;
$last_location_report_data=array();
			
for($i=0;$i<sizeof($vserial);$i++)
{		
	$Query="SELECT vehicle.vehicle_name,vehicle.vehicle_type,vehicle.vehicle_number FROM vehicle USE INDEX(v_vehicleid_status)".
		",vehicle_assignment USE INDEX(va_vehicleid_status,va_vehicleid_imei_status) WHERE vehicle.vehicle_id=vehicle_assignment.".
		"vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_assignment.device_imei_no='$vserial[$i]'";
	$Result=mysql_query($Query,$DbConnection);
	$Row=mysql_fetch_row($Result);
	getLastPosition($vserial[$i],$Row[0],$Row[1],$Row[3],$startdate,$enddate,$iotype_element[$i]);
}
}
	function getLastPosition($vehicle_serial,$vname,$vtype,$vehicle_number,$startdate,$enddate,$iotype_element_1)
	{
               $requiredData="All";
     $sortBy='h';
		global $last_location_report_data;
		
		$dataValid = 0;
		$date_1 = explode(" ",$startdate);
		$date_2 = explode(" ",$enddate);

		$dateRangeStart = $date_1[0];
		$dateRangeEnd = $date_2[0];
		$timefrom = $date_1[1];
		$timeto = $date_2[1];

		get_All_Dates($dateRangeStart, $dateRangeEnd, $userdates);    
        $date_size = sizeof($userdates);
		date_default_timezone_set("Asia/Calcutta");
		$current_datetime = date("Y-m-d H:i:s");
		$current_date = date("Y-m-d");
	
		
            $parameterizeData=new parameterizeData();
            $ioFoundFlag=0;
            global $o_cassandra;

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

            $ioArr=explode(":",$iotype_element_1);
            $ioFoundFlag=0;
            $ioArrSize=sizeof($ioArr);
            for($z=0;$z<$ioArrSize;$z++)
            {
                $tempIo=explode("^",$ioArr[$z]);
                //echo "io=".$ioArr[$z]."<br>";
                if($tempIo[1]=="temperature")
                {
                    $ioFoundFlag=1;
                    $parameterizeData->temperature=$finalIoArr[$tempIo[0]];
                }
            }
	//echo "serial=".$vehicle_serial."<br>";

		$last_location_string = get_maxspd_halt($vehicle_serial);
		//echo "vc=".$vc."<br>";
		//echo "last_location_string=".$last_location_string."<br>";
		$data = explode(',',$last_location_string);
		
		$day_max_speed = $data[0];
		$day_max_speed_time = $data[1];
		$last_halt_time = $data[2];
		
		
		for($i=($date_size-1);$i>=0;$i--)
		{		
                    $LastDataObject=new lastDataObj();
                    getLastPositionXMl($vserial[$i],$date1,$date2,$dateRangeStart,$dateRangeEnd,$sortBy,$type,$parameterizeData,$LastDataObject);
                    //var_dump($SortedDataObject);
                    if(count($LastDataObject->deviceDatetime)>0)
                    {
                        $line = substr($line, 0, -2);	
                        $lat_value_1 = preg_replace('/"/', '', $lat_value[1]);
                        $lng_value_1= preg_replace('/"/', '', $lng_value[1]);
                        $speed_local =$speed =$LastDataObject->speedData[$obi];
									
                        if($ioFoundFlag==1)
                        {
                            $temperature=$LastDataObject->temperatureIOData[$obi];                
                        }
                        else
                        {
                            $temperature="0.0";
                        }
                        $linetmp=$linetmp.'<x a="'.$LastDataObject->messageTypeLD[0].'" b="'.$LastDataObject->versionLD[0].'" c="'.$LastDataObject->fixLD[0].'" d="'.$LastDataObject->latitudeLD[0].'" e="'.$LastDataObject->longitudeLD[0].'" f="'.$LastDataObject->speedLD[0].'" g="'.$LastDataObject->serverDatetimeLD[0].'" h="'.$LastDataObject->deviceDatetimeLD[0].'" i="'.$LastDataObject->io1LD[0].'" j="'.$LastDataObject->io2LD[0].'" k="'.$LastDataObject->io3LD[0].'" l="'.$LastDataObject->io4LD[0].'" m="'.$LastDataObject->io5LD[0].'" n="'.$LastDataObject->io6LD[0].'" o="'.$LastDataObject->io7LD[0].'" p="'.$LastDataObject->io8LD[0].'" q="'.$LastDataObject->sigStrLD[0].'" r="'.$LastDataObject->suplyVoltageLD[0].'" s="'.$LastDataObject->dayMaxSpeedLD[0].'" t="'.$LastDataObject->dayMaxSpeedTimeLD[0].'" u="'.$LastDataObject->lastHaltTimeLD[0].'" v="'.$vserial[$i].'" w="'.$vehicle_detail_local[0].'" x="'.$vehicle_detail_local[2].'" y="'.$vehicle_detail_local[1].'"/>,';
                        $linetowrite=$vehicle_serial."@".$vname."@".$vtype."@".$vehicle_number."@".$datetime."@".$temperature."@".$day_max_speed."@".$day_max_speed_time."@".$last_halt_time."@".$lat_value_1."@".$lng_value_1."@".$speed_local;
			
                        if(strlen($linetowrite)!=0)
                        {
                            //echo "linetowrite=".$linetowrite."<br>";
                            $linetowrite=explode("@",$linetowrite);

                            $last_location_report_data[]=array("deviceImeiNo"=>$linetowrite[0],"vehicleName"=>$linetowrite[1],"vehicleType"=>$linetowrite[2],"vehicleNumber"=>$linetowrite[3],"datetime"=>$linetowrite[4],"temperature"=>$linetowrite[5],"dayMaxSpeed"=>$linetowrite[6],"dayMaxSpeedTime"=>$linetowrite[7],"lastHaltTime"=>$linetowrite[8],"latitude"=>$linetowrite[9],"longitude"=>$linetowrite[10],"speed"=>$linetowrite[11]);
                            fclose($xml);
                            unlink($xml_original_tmp);
                            break;
                        }
                        fclose($xml);
                        unlink($xml_original_tmp);
                    } 
		} // Date closed
	}
	echo json_encode($last_location_report_data); 
	
	function get_maxspd_halt($imei)
	{
		//echo "in function<br>";
		global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
		global $old_xml_date;
		new_xml_variables();		
		$xml_file = "../../../../xml_vts/xml_last/".$imei.".xml";
		//echo "xml_file=".$xml_file."<br>";
		//echo "a=".$vs." b=".$vt."<br>";
		$file = file_get_contents($xml_file);
		if(!strpos($file, "</t1>")) 
		{
			usleep(1000);
		}		
		 
		$t=time();
		$rno = rand();			
		$xml_original_tmp = "../../../../xml_tmp/original_xml/tmp_".$imei."_".$t."_".$rno.".xml";		
		copy($xml_file,$xml_original_tmp); 

		if(file_exists($xml_original_tmp))
		{
			//echo "<br>exist2";
			$fexist =1;
			$fp = fopen($xml_original_tmp, "r") or $fexist = 0;   
			$total_lines =0;
			$total_lines = count(file($xml_original_tmp));
			//echo "<br>total_lines=".$total_lines;
			$c =0;
			while(!feof($fp)) 
			{
				$line = fgets($fp);
				$c++;				

				if(strlen($line)>15)
				{					
					if((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{
						//echo "in if";
						/*$status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
						$xml_date = $datetime; */          

						$status = preg_match('/'.$vs.'="[^"]+/', $line, $day_max_speed_tmp);
						//print_r($last_halt_time_tmp);
						$day_max_speed_tmp1 = explode("=",$day_max_speed_tmp[0]);
						$day_max_speed = preg_replace('/"/', '', $day_max_speed_tmp1[1]);

						$status = preg_match('/'.$vt.'="[^"]+/', $line, $day_max_speed_time_tmp);
						$day_max_speed_time_tmp1 = explode("=",$day_max_speed_time_tmp[0]);
						$day_max_speed_time = preg_replace('/"/', '', $day_max_speed_time_tmp1[1]);

						$status = preg_match('/'.$vu.'="[^"]+/', $line, $last_halt_time_tmp);
						//echo "ddd=".$last_halt_time_tmp[0]."<br>";
						//print_r($last_halt_time_tmp);
						$last_halt_time_tmp1 = explode("=",$last_halt_time_tmp[0]);
						$last_halt_time = preg_replace('/"/', '', $last_halt_time_tmp1[1]);
						//echo "last_halt_time=".$last_halt_time."<br>";
					}																			
				}			
			}

			fclose($fp);
			unlink($xml_original_tmp);

			if($day_max_speed > 200)
			{
				$day_max_speed = "0";
			}

			$day_max_speed = round($day_max_speed,2);
			$day_max_speed = $day_max_speed." km/hr";
			//echo "day_max_speed=".$day_max_speed."<br>";
			$data_string = $day_max_speed.",".$day_max_speed_time.",".$last_halt_time;		
		}
		return $data_string;  
	}

?>

