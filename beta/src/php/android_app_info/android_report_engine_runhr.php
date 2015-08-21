<?php 
error_reporting(-1);
ini_set('display_errors', 'On');
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
set_time_limit(300);
include_once("android_get_location_lp_track_report.php");
include_once("android_calculate_distance.php");
include_once("android_check_with_range.php");
include_once("androidPointLocation.php");
//include_once("get_location.php");
include_once("util_android_hr_min_sec.php");

require_once "lib/nusoap.php"; 

$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//print_r($pathInPieces);
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2];
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3];
//echo "pathToRoot=".$pathToRoot."<br>";
//====cassamdra //////////////
include_once($pathToRoot.'/beta/src/php/xmlParameters.php');
include_once($pathToRoot.'/beta/src/php/parameterizeData.php'); /////// for seeing parameters
include_once($pathToRoot.'/beta/src/php/data.php');   
include_once($pathToRoot.'/beta/src/php/getDeviceDataTest.php');
    
    
$vehicleserialWithIo="865733024480519::#2^engine,,";
$startDate="2015/08/21 00:00:00";
$endDate="2015/08/21 12:11:17";

$result=getEngineRunHour($vehicleserialWithIo,$startDate,$endDate);
print_r($result);

function getEngineRunHour($vehicleserialWithIo,$startDate,$endDate)
{
global $DbConnection;
$device_str= $vehicleserialWithIo;
//$device_str="862170018371961:862170018369908:# , ,";
$device_str = explode('#',$device_str);
//echo "device_str1=".$device_str[0]."<br>";
//echo "device_str2=".$device_str[1]."<br>";
$vserial = explode(':',substr($device_str[0],0,-1));
$iotype_element = explode(',',substr($device_str[1],0,-1));

$geo_id_str= $_REQUEST["geo_id"];
$geo_id1 = explode(':',$geo_id_str);


$date1 = $startDate;
$date2 =  $endDate;

/*$date1 = "2013/11/01 13:58:21";
$date2 = "2013/11/04 13:58:24";*/

$date1 = str_replace('/', '-', $date1);  
$date2 = str_replace('/', '-', $date2); 

$date_1 = explode(" ",$date1);
$date_2 = explode(" ",$date2);
$datefrom = $date_1[0];
$dateto = $date_2[0];
$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);

$current_date = date("Y-m-d");

$k1 = 0;
$m_start=0;

$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);
$user_interval = $userInterval;
//$user_interval = "15";
//read_halt_xml($xml_path, &$imei, &$vname, &$lat, &$lng, &$arr_time, &$dep_time, &$in_temperature, &$out_temperature, &$duration);	

global $engineRunHrData;
$engineRunHrData=array();
for($i=0;$i<sizeof($vserial);$i++)
{ 
    
    
	//echo   "<br>vserial[i] =".$vserial[$i];
	$Query="SELECT vehicle.vehicle_name,vehicle.vehicle_type,vehicle.vehicle_number FROM vehicle USE INDEX(v_vehicleid_status)".
		",vehicle_assignment USE INDEX(va_vehicleid_status,va_vehicleid_imei_status) WHERE vehicle.vehicle_id=vehicle_assignment.".
		"vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_assignment.device_imei_no='$vserial[$i]'";
	$Result=mysql_query($Query,$DbConnection);
	$Row=mysql_fetch_row($Result);
	getEngineRunHourData($vserial[$i], $iotype_element[$i], $Row[0], $date1, $date2);
}
	return json_encode($engineRunHrData); 	
}


function getEngineRunHourData($vehicle_serial, $iotype_element_1 , $vname_local, $startdate,$enddate)
{
    global $engineRunHrData;
    global $finalIoArr;
    $requiredData="All";
    $sortBy='h';
    
    $date_1 = explode(" ",$startdate);
    $date_2 = explode(" ",$enddate);
    $dateRangeStart = $date_1[0];
    $dateRangeEnd = $date_2[0];
    $timefrom = $date_1[1];
    $timeto = $date_2[1];

    get_All_Dates($dateRangeStart, $dateRangeEnd, $userdates);    
    $date_size = sizeof($userdates);
    //print_r($userdates);
    
	
    $parameterizeData=new parameterizeData();
    $ioFoundFlag=0;
    global $o_cassandra;
    
    $ioArr=explode(":",$iotype_element_1);
    $ioFoundFlag=0;
    $ioArrSize=sizeof($ioArr);
    for($z=0;$z<$ioArrSize;$z++)
    {
        $tempIo=explode("^",$ioArr[$z]);
        //echo "io=".$tempIo[0]."<br>";
        if($tempIo[1]=="engine")
        {
            $ioFoundFlag=1;
            //echo "ioplace=".$finalIoArr[$tempIo[0]]."<br>";
            $parameterizeData->engineRunHr=$finalIoArr[$tempIo[0]];
        }
    }
 $firstdata_flag =0;
            $runhr_duration =0 ;
           
            $StartFlag=0;
            $continuous_running_flag =0;
             if($ioFoundFlag==1)
        {
    for($i=0;$i<=($date_size-1);$i++)
    {
        $SortedDataObject=null;
        $SortedDataObject=new data();
        if($date_size==1)
        {
            $dateRangeStart=$startdate;
            $dateRangeEnd=$enddate;
        }
        else if($i==($date_size-1))
        {
            $dateRangeStart=$userdates[$i]." 00:00:00";
            $dateRangeEnd=$enddate;
        }
        else if($i==0)
        {
            $dateRangeStart=$startdate;
            $dateRangeEnd=$userdates[$i]." 23:59:59";
        }
        else
        {
           $dateRangeStart=$userdates[$i]." 00:00:00";
            $dateRangeEnd=$userdates[$i]." 23:59:59";
        }
        //echo "vSerial=".$vehicle_serial." dateRangeStart=".$dateRangeStart." dateRangeEnd=".$dateRangeEnd."<br>";
        deviceDataBetweenDates($vehicle_serial,$dateRangeStart,$dateRangeEnd,$sortBy,$parameterizeData,$SortedDataObject);
        //var_dump($SortedDataObject);
        if(count($SortedDataObject->deviceDatetime)>0)
        {
            $c = -1;
            $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
            for($obi=0;$obi<$prevSortedSize;$obi++)
            {
                $c++;
                $datetime =$SortedDataObject->deviceDatetime[$obi];  
                $engine_count = $SortedDataObject->engineIOData[$obi];  
                //echo "engineCnt=".$engine_count."<br>";
               /* if($finalInverseType==0)
                {
                    //echo "engine_count".$engine_count."<br>";
                    if($engine_count>500)
                    {
                        $continuous_running_flag = 1;
                    }

                    if($engine_count>500 && $StartFlag==0)  //500
                    {                						
                        $time1 = $datetime;
                        //echo "datetime=".$time1."<br>";
                        $StartFlag = 1;
                       // echo "in start flag<br>";
                    } 
                    else if( ($engine_count<500 && $StartFlag==1) || ( ($c==($prevSortedSize-1)) && ($continuous_running_flag ==1) ))   //500
                    {
                        $StartFlag = 2;
                        //echo "in end flag<br>";
                    }
                }
                else
                {*/
                    //echo "engine_count else ".$engine_count."<br>";
                    if($engine_count<500)
                    {
                        $continuous_running_flag = 1;
                    }
                    if($engine_count<500 && $StartFlag==0)  //500
                    {                						
                        $time1 = $datetime;
                        $StartFlag = 1;
                    } 
                    else if( ($engine_count>500 && $StartFlag==1) || ( ($c==($prevSortedSize-1)) && ($continuous_running_flag ==1) ) )   //500
                    {
                        $StartFlag = 2;
                    }
                //}
                $time2 = $datetime;
                //echo "time1=".$time1."time2=".$time2."<br>";

                if($StartFlag == 2)
                {
                    $StartFlag=0;
                    $continuous_running_flag = 0;
                    $runtime = strtotime($time2) - strtotime($time1);
                    //echo "runtime=".$runtime."<br>";
                    if($runtime > 60)
                    {
                       /*//echo "time1=".$time1." time2=".$time2."<br>";
                       //echo "runtime1=".$runtime."<br>";
                       $imei[]=$vserial[$i];
                       $vname[]=$vehicle_detail_local[0];
                       $dateFromDisplay[]=$time1;
                       $dateToDisplay[]=$time2;
                       $engine_runhr[]=$runtime;*/
                        $hms1 = secondsToTime($runtime);
                        $duration = $hms1[h].":".$hms1[m].":".$hms1[s];
                       $engineRunHrData[]=array("deviceImeiNo"=>$vehicle_serial,"vehicleName"=>$vname_local,"dateFrom"=>$time1,"dateTo"=>$time2,"engineRunHr"=>$duration);		
                    } 
                } 
            }
            if($StartFlag == 1)
            {
                $StartFlag=0;
                $runtime = strtotime($time2) - strtotime($time1);
                //echo "<br>runtime=".$runtime;
                //$runhr_duration = strtotime($runtime);
                if($runtime > 60)
                {
                    /*//echo "time1=".$time1." time2=".$time2."<br>";
                    $imei[]=$vserial[$i];
                    $vname[]=$vehicle_detail_local[0];
                    $dateFromDisplay[]=$time1;
                    $dateToDisplay[]=$time2;
                    $engine_runhr[]=$runtime;*/
                    $hms1 = secondsToTime($runtime);
                        $duration = $hms1[h].":".$hms1[m].":".$hms1[s];
                       $engineRunHrData[]=array("deviceImeiNo"=>$vehicle_serial,"vehicleName"=>$vname_local,"dateFrom"=>$time1,"dateTo"=>$time2,"engineRunHr"=>$duration);		
                 } 
            }
        }
    }  // for closed 
        }
}  
$server = new soap_server();
$server->register("getEngineRunHour");
$server->service($HTTP_RAW_POST_DATA);
?>			
