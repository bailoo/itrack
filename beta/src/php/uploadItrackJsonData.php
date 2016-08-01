<?php
header("content-Type;application/json");
include_once("calculate_distance.php");

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

if(!empty($_POST['deviceData']))
{
        $keyData=$_POST['key'];
        if($keyData=='mother2016_itrack')
        {
            $jsonData=$_POST['deviceData'];	
            //echo "jsonDecodedData=".$jsonData."<br>";
            $response=getJsonSampleData($jsonData);
            if($response=="fail")
            {
                    deliverResponse(400,'Data Not Inserted',NULL);
            }
            else if($response=="success")
            {
                    deliverResponse(200,'Data Inserted Successfully',$response);
            }
        }
        else
        {
            deliverResponse(400,'Invalid Key Request',NULL);
        }
	
}
else
{
	deliverResponse(400,'Invalid Request',NULL);
}

function deliverResponse($status,$statusMessage,$response)
{
	header("HTTP/1.1 $status $statusMessage");
	$responseArr['status']=$status;
	$responseArr['statusMessage']=$statusMessage;
	$responseArr['dataResponse']=$response;
	
	$jsonResponse=json_encode($responseArr);
	echo $jsonResponse;	
}

function getJsonSampleData($jsonData)
{
	//echo "in function ";
    //$jsonData='{"VEHICLENO":"111231031744301","DATAELEMENTS":[{"DATAELEMENTS":{"LOCATION":"","HEADING":237.2,"SPEED":45,"LONGITUDE":76.6232,"DATETIME":"2016\/07\/29 16:01:41","IGNSTATUS":0,"LATITUDE":28.1179}}]}';
    //$jsonData='{"vno":"GJ06-N-1234","speed":"45","lng":"79.5827","lat":"26.03842","date":"2016-07-28 16:18:41","ignstatus":"0" }';
    $jsonDecodedData = json_decode($jsonData,true);
    /*
    $innerData=$jsonDecodedData['DATAELEMENTS'][0]['DATAELEMENTS'];
    $deviceImei=$jsonDecodedData['VEHICLENO'];
    $location=$innerData['LOCATION'];
    $heading=$innerData['HEADING'];
    $speed=$innerData['SPEED'];
    $lat=$innerData['LATITUDE'];
    $lng=$innerData['LONGITUDE'];
    $datetime=$innerData['DATETIME'];
    $datetime=str_replace("/","-",$datetime);
    $ignstatus=$innerData['IGNSTATUS'];
	*/


    $deviceImei=$jsonDecodedData['vno'];
    //$location=$jsonDecodedData['loc'];
    //$heading=$jsonDecodedData['heading'];
    $speed=$jsonDecodedData['speed'];
    $lat=$jsonDecodedData['lat'];
    $lng=$jsonDecodedData['lng'];
    $datetime=$jsonDecodedData['date'];
    $datetime=str_replace("/","-",$datetime);
    $ignstatus=$jsonDecodedData['ignstatus'];
    
    
    $MsgType="NORMAL";
    $Version="1.1";
    $Fix=1;
    $Latitude=$lat;
    $Longitude=$lng;
    $Speed=$speed;
    $DeviceDatetime=$datetime;	
    $IoValue1=$ignstatus;
    $IoValue2=0;
    $IoValue3=0;
    $IoValue4=0;
    $IoValue5=0;
    $IoValue6=0;
    $IoValue7=0;
    $IoValue8=0;
    $SignalStrength=0;
    $SupVoltage=0.0;
    $data=$MsgType.";".$Version.";".$Fix.";".$Latitude.";".$Longitude.";".$Speed.";".$IoValue1.";".$IoValue2.";".$IoValue3.";".$IoValue4.";".$IoValue5.";".$IoValue6.";".$IoValue7.";".$IoValue.";".$SignalStrength.";".$SupVoltage;
	//echo "data=".$data."<br>";
    insertFullData($deviceImei,$DeviceDatetime,$data);
	
    $lastHaltTime="";
    $dayMaxSpeed="";
    $dayMaxSpeedDt="";	
    $LastRecordObject=getLastRecord($deviceImei,$sortBy,$parameterizeData);
    if(count($LastRecordObject->serverDatetimeLR)>0)
    {
        for($i=0;$i<sizeof($LastRecordObject->serverDatetimeLR);$i++)
        {
            $latPrev=$LastRecordObject->latitudeLR[$i];
            $lngPrev=$LastRecordObject->longitudeLR[$i];
            $datetimePrev=$LastRecordObject->deviceDatetimeLR[$i];
            $lastHaltDtPrev=$LastRecordObject->lastHaltTimeLR[$i];
            $speedPrev=$LastRecordObject->speedLR[$i];
            $dayMaxSpeedPrev=$LastRecordObject->dayMaxSpeedLR[$i];
            $dayMaxSpeedDtPrev=$LastRecordObject->dayMaxSpeedTimeLR[$i];

            calculate_distance($lat, $latPrev, $lng, $lngPrev, $distance);
            $tmp_time_diff = ((double)( strtotime($datetime) - strtotime($datetimePrev) )) / 3600;
            $tmp_speed = ((double) ($distance)) / $tmp_time_diff;

            if($distance<0.1 && $tmp_speed<100)
            {
                $lastHaltTime=$datetimePrev;
            }
            else
            {
                $lastHaltTime=$lastHaltDtPrev;
            }

            if($speedPrev<$speed)
            {
                $dayMaxSpeed=$speed;
                $dayMaxSpeedDt=$datetimePrev;
            }
            else
            {
                $dayMaxSpeed=$dayMaxSpeedPrev;
                $dayMaxSpeedDt=$dayMaxSpeedDtPrev;
            }
        }
    }

    $cellname="device";	
    $dataLive=$MsgType.";".$Version.";".$Fix.";".$Latitude.";".$Longitude.";".$Speed.";".$DeviceDatetime.";".$IoValue1.";".$IoValue2.";".$IoValue3.";".$IoValue4.";".$IoValue5.";".$IoValue6.";".$IoValue7.";".$IoValue.";".$SignalStrength.";".$SupVoltage.";".$dayMaxSpeed.";".$dayMaxSpeedDt.";".$lastHaltTime.";".$cellname;
    //echo "datalive=".$dataLive."<br>";
	insertLiveData($deviceImei,$dataLive);

    //$dataArr[]=array('vehicleName'=>$vehicleName,'location'=>$location,'heading'=>$heading,'speed'=>$speed,"lat"=>$lat,'lng'=>$lng);
    $dataArr[]=array('vehicleName'=>$vehicleName,'speed'=>$speed,"lat"=>$lat,'lng'=>$lng);
	//print_r($dataArr);
    if(count($dataArr)>0)
    {
        return "success";
    }
    else
    {
        return "fail";
    }
}
?>