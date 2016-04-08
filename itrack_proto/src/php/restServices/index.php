<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
set_time_limit(2000);
$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2];

include_once($pathToRoot.'/beta/src/php/xmlParameters.php');
include_once($pathToRoot.'/beta/src/php/parameterizeData.php'); /////// for seeing parameters
include_once($pathToRoot.'/beta/src/php/lastRecordData.php');   
include_once($pathToRoot.'/beta/src/php/getDeviceDataTest.php');

require 'Slim/Slim.php';
$app = new Slim();

$app->get('/getLastRecord/:vehicleNo/:userId/:password','lastRecordData');
$app->run();

function lastRecordData($vehicleNo,$userId,$password)
{
    $password=md5($password);
    $status=1;
    $sql = "SELECT account_id FROM account WHERE user_id=:userId AND password=:password AND status=:status";
    try 
    {
        $db = getConnection();
        $stmt = $db->prepare($sql);  
        $stmt->bindParam("userId", $userId);
        $stmt->bindParam("password", $password);
        $stmt->bindParam("status", $status);
        $stmt->execute();		
        $rowCount=$stmt -> rowCount();
        if($rowCount==0)
        {
            deliverResponse(400,'Not a Registered User',NULL);
        }
        else
        {
            $db = getConnection();
            $stmt = $db->prepare("SELECT DISTINCT device_imei_no FROM vehicle_assignment".
                    " INNER JOIN vehicle ON vehicle.vehicle_id = vehicle_assignment.vehicle_id ".
                    "WHERE vehicle_assignment.status =:status AND vehicle.status=:status AND ".
                    "vehicle.vehicle_name=:vName");   
            $stmt->bindParam("vName", $vehicleNo);           
            $stmt->bindParam("status", $status);
            $stmt->execute();
            $result = $stmt -> fetch();
            if($result["device_imei_no"]!='')
            {
                getLiveDeviceData($result["device_imei_no"]);     
            }
            else 
            {
                deliverResponse(400,'Vehicle No Not Found',NULL);
            }
        }
        $db = null;         
    } 
    catch(PDOException $e) 
    {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }    
}

function getLiveDeviceData($imeiNo)
{
    global $o_cassandra;    
    $imeiNoArr = explode(',',$imeiNo);
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
    $final_str="";

    for($i=0;$i<sizeof($imeiNoArr);$i++)
    {
        $sub_str="";
        $io_str="";
        $t=time();
        $rno = rand();
        $LastRecordObject=null;	
        $LastRecordObject=new lastRecordData();	
	//echo "imei=".$imei."<br>";
	$LastRecordObject=getLastRecord($imeiNoArr[$i],$sortBy,$parameterizeData);
        //echo "getOBJ";
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
            $liveData[]=array(
                            
                            'latitude'=>$LastRecordObject->latitudeLR[0],
                            'longitude'=>$LastRecordObject->longitudeLR[0],
                            'speed'=>$LastRecordObject->speedLR[0],                            
                            'deviceDatetime'=>$LastRecordObject->deviceDatetimeLR[0],                            
                            'deviceImeiNo'=>$imeiNoArr[$i]
                        );
           /* $liveData[]=array(
                            'messageType'=>$LastRecordObject->messageTypeLR[0],
                            'version'=>$LastRecordObject->versionLR[0],
                            'fix'=>$LastRecordObject->fixLR[0],
                            'latitude'=>$LastRecordObject->latitudeLR[0],
                            'longitude'=>$LastRecordObject->longitudeLR[0],
                            'speed'=>$LastRecordObject->speedLR[0],
                            'serverDatetime'=>$LastRecordObject->serverDatetimeLR[0],
                            'deviceDatetime'=>$LastRecordObject->deviceDatetimeLR[0],
                            'io1'=>$LastRecordObject->io1LR[0],
                            'io2'=>$LastRecordObject->io1LR[0],
                            'io3'=>$LastRecordObject->io1LR[0],
                            'io4'=>$LastRecordObject->io1LR[0],
                            'io5'=>$LastRecordObject->io1LR[0],
                            'io6'=>$LastRecordObject->io1LR[0],
                            'io7'=>$LastRecordObject->io1LR[0], 
                            'io8'=>$LastRecordObject->io1LR[0], 
                            'sigStr'=>$LastRecordObject->sigStrLR[0], 
                            'suplyVoltage'=>$LastRecordObject->suplyVoltageLR[0], 
                            'dayMaxSpeed'=>$LastRecordObject->dayMaxSpeedLR[0], 
                            'dayMaxSpeedTime'=>$LastRecordObject->dayMaxSpeedTimeLR[0],
                            'lastHaltTime'=>$LastRecordObject->lastHaltTimeLR[0],
                            'deviceImeiNo'=>$imeiNoArr[$i],                               
                            'status'=>$status
                        );*/
	}                    	
    }
    if(count($liveData)==0)
    {
        deliverResponse(400,'No Data Found',NULL);       
    }
    else
    {
       $jsonResponse=json_encode($liveData); 
       echo $jsonResponse;
    }
}



function getConnection() 
{
    $dbhost='itrackdb.c4pqfsdaiccz.us-east-1.rds.amazonaws.com';
    $dbuser='bailoo';
    $dbpass='neon04$VTS';
    $dbname='iespl_vts_beta';    
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

function deliverResponse($status,$statusMessage,$response)
{
    header("HTTP/1.1 $status $statusMessage");
    $responseArr['status']=$status;
    $responseArr['statusMessage']=$statusMessage;
    $responseArr['jsonResponse']=$response;

    $jsonResponse=json_encode($responseArr);
    echo $jsonResponse;	
}

?>