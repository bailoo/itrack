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


//$vserial_arr = explode(',',$vserial);
$vname1 ="";
//echo "t1=";
//echo"vsize=".$vsize."<br>";
$data=array();
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
                
            $data[]=array(
                            'messageTypeLR'=>$LastRecordObject->messageTypeLR[0],
                            'versionLR'=>$LastRecordObject->versionLR[0],
                            'fixLR'=>$LastRecordObject->fixLR[0],
                            'latitudeLR'=>round($LastRecordObject->latitudeLR[0],7),
                            'longitudeLR'=>round($LastRecordObject->longitudeLR[0],7),
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
                            'status'=>$status
                        );
	}
	$LastRecordObject=null;
}
echo json_encode($data);
?>
