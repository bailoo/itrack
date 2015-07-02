<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');

include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');

include_once('calculate_distance.php');

include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('lastRecordData.php');
include_once("getXmlData.php");	


$mode = $_REQUEST['mode'];
$vserial1 = $_REQUEST['vserial'];
//echo"veserial=".$vserial1."<br>";
$vserial = explode(',',$vserial1) ;
$vsize=sizeof($vserial);
$startdate = $_REQUEST['startdate'];
$enddate = $_REQUEST['enddate'];
	
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

$vname_str ="";
$vnumber_str ="";
for($i=0;$i<$vsize;$i++)
{
    $tmp = explode('#',$vserial[$i]);
    $imei = $tmp[0];
    $last_time = $tmp[1];
    $vehicle_info=get_vehicle_info($root,$imei);
    $vehicle_detail_local=explode(",",$vehicle_info);
    $vname_str = $vname_str.$vehicle_detail_local[0].":";
    $vnumber_str = $vnumber_str.$vehicle_detail_local[2].":";
    $LastRecordObject=new lastRecordData();	
    //echo "imei=".$imei."<br>";
    $LastRecordObject=getLastRecord($imei,$sortBy,$parameterizeData);
    //echo "getOBJ";
    //echo "<br>";
   //var_dump($LastRecordObject);
   
	
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
        $datetime[]=$LastRecordObject->deviceDatetimeLR[0];
        $vehicleserial[]=$imei;
        $vehiclename[]=$vehicle_detail_local[0];
        $vehiclenumber[]=$vehicle_detail_local[2];
        $last_halt_time[]=$LastRecordObject->lastHaltTimeLR[0];
        $lat[]=$LastRecordObject->latitudeLR[0];
        $lng[]=$LastRecordObject->longitudeLR[0];
        $vehicletype[]=$vehicle_detail_local[1];
        $speed[]=$LastRecordObject->speedLR[0];
        $io1[]=$LastRecordObject->io1LR[0];
        $io1[]=$LastRecordObject->io2LR[0];
        $io1[]=$LastRecordObject->io3LR[0];
        $io1[]=$LastRecordObject->io4LR[0];
        $io1[]=$LastRecordObject->io5LR[0];
        $io1[]=$LastRecordObject->io6LR[0];
        $io1[]=$LastRecordObject->io7LR[0];
        $io1[]=$LastRecordObject->io8LR[0];    
        $last_halt_time[]=$LastRecordObject->lastHaltTimeLR[0];
        //$linetmp="\n".'<x a="'.$LastRecordObject->messageTypeLR[0].'" b="'.$LastRecordObject->versionLR[0].'" c="'.$LastRecordObject->fixLR[0].'" d="'.$LastRecordObject->latitudeLR[0].'" e="'.$LastRecordObject->longitudeLR[0].'" f="'.$LastRecordObject->speedLR[0].'" g="'.$LastRecordObject->serverDatetimeLR[0].'" h="'.$LastRecordObject->deviceDatetimeLR[0].'" i="'.$LastRecordObject->io1LR[0].'" j="'.$LastRecordObject->io2LR[0].'" k="'.$LastRecordObject->io3LR[0].'" l="'.$LastRecordObject->io4LR[0].'" m="'.$LastRecordObject->io5LR[0].'" n="'.$LastRecordObject->io6LR[0].'" o="'.$LastRecordObject->io7LR[0].'" p="'.$LastRecordObject->io8LR[0].'" q="'.$LastRecordObject->sigStrLR[0].'" r="'.$LastRecordObject->suplyVoltageLR[0].'" s="'.$LastRecordObject->dayMaxSpeedLR[0].'" t="'.$LastRecordObject->dayMaxSpeedTimeLR[0].'" u="'.$LastRecordObject->lastHaltTimeLR[0].'" v="'.$imei.'" w="'.$vehicle_detail_local[0].'" x="'.$vehicle_detail_local[2].'" y="'.$vehicle_detail_local[1].'" aa="'.$status.'"/>,';
       
    }
    $LastRecordObject=null;
}
$vname1=substr($vname_str,0,-1); /////////for last position text report
$vnumber1=substr($vnumber_str,0,-1); /////////for last position text report
?>
