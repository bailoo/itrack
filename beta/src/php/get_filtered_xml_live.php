<?php
set_time_limit(1000);
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('calculate_distance.php');
include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('lastRecordData.php');
include_once("getXmlData.php");	

//echo "in if";
$pathtowrite = $_REQUEST['xml_file']; 
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

$fh = fopen($pathtowrite, 'w') or die("can't open file 1"); // new
fwrite($fh, "<t1>");  
fclose($fh);
//$vserial_arr = explode(',',$vserial);
$vname1 ="";
//echo "t1=";
//echo"vsize=".$vsize."<br>";
for($i=0;$i<$vsize;$i++)
{
	$tmp = explode('#',$vserial[$i]);
	$imei = $tmp[0];
	$last_time = $tmp[1];
	$vehicle_info=get_vehicle_info($root,$imei);
	$vehicle_detail_local=explode(",",$vehicle_info);
	$LastRecordObject=new lastRecordData();	
	//echo "imei=".$imei."<br>";
	$LastRecordObject=getLastRecord($imei,$sortBy,$parameterizeData);
        //echo "getOBJ";
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
	
		//var_dump($LastRecordObject);
		$linetmp="\n".'<x a="'.$LastRecordObject->messageTypeLR[0].'" b="'.$LastRecordObject->versionLR[0].'" c="'.$LastRecordObject->fixLR[0].'" d="'.$LastRecordObject->latitudeLR[0].'" e="'.$LastRecordObject->longitudeLR[0].'" f="'.$LastRecordObject->speedLR[0].'" g="'.$LastRecordObject->serverDatetimeLR[0].'" h="'.$LastRecordObject->deviceDatetimeLR[0].'" i="'.$LastRecordObject->io1LR[0].'" j="'.$LastRecordObject->io2LR[0].'" k="'.$LastRecordObject->io3LR[0].'" l="'.$LastRecordObject->io4LR[0].'" m="'.$LastRecordObject->io5LR[0].'" n="'.$LastRecordObject->io6LR[0].'" o="'.$LastRecordObject->io7LR[0].'" p="'.$LastRecordObject->io8LR[0].'" q="'.$LastRecordObject->sigStrLR[0].'" r="'.$LastRecordObject->suplyVoltageLR[0].'" s="'.$LastRecordObject->dayMaxSpeedLR[0].'" t="'.$LastRecordObject->dayMaxSpeedTimeLR[0].'" u="'.$LastRecordObject->lastHaltTimeLR[0].'" v="'.$imei.'" w="'.$vehicle_detail_local[0].'" x="'.$vehicle_detail_local[2].'" y="'.$vehicle_detail_local[1].'" aa="'.$status.'"/>,';
		//get_vehicle_last_data($current_date, $imei, $last_time, $vehicle_detail_local[0],$vehicle_detail_local[1], $pathtowrite);
		//echo "<textarea>".$linetmp."</textarea>";
		$fh = fopen($pathtowrite, 'a') or die("can't open file pathtowrite"); //append
		//$fh = fopen($pathtowrite, 'w') or die("can't open file 1");

		fwrite($fh, $linetmp);  
		fclose($fh);
	}
	$LastRecordObject=null;
}

$fh = fopen($pathtowrite, 'a') or die("can't open file 2"); //append
fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
fwrite($fh, "\n</t1>");  
fclose($fh);
?>
