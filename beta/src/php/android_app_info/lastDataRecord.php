<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
set_time_limit(2000);
require_once "lib/nusoap.php"; 

 $pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
//print_r($pathInPieces);
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2];
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3];
//echo "pathToRoot=".$pathToRoot."<br>";
	//====cassamdra //////////////
   include_once($pathToRoot.'/beta/src/php/xmlParameters.php');
    include_once($pathToRoot.'/beta/src/php/parameterizeData.php'); /////// for seeing parameters
    include_once($pathToRoot.'/beta/src/php/lastRecordData.php');   
    include_once($pathToRoot.'/beta/src/php/getDeviceDataTest.php');
    
    
       
    if(!empty($_POST['userId']))
    {
	$imeiNo=$_POST['imeiNo'];
        $userId=$_POST['userId'];	
        $password=md5($_POST['password']);	
        $query="SELECT account.account_id FROM account WHERE ".
			"account.user_id='$userId' AND account.password='$password' AND account.status=1";
	
	$result = mysql_query($query, $DbConnection);
	$count = mysql_num_rows($result);	
	// echo"count=".$count;

	if($count <= 0)
	{
            deliverResponse(400,'Not a Registered User',NULL);
        }
        else
        {
            getLiveDeviceData($imeiNo);     
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
	$responseArr['jsonResponse']=$response;
	
	$jsonResponse=json_encode($responseArr);
	echo $jsonResponse;	
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
                            'status'=>$status
                        );
	}                    	
    }
    if(count($liveData)==0)
    {
        deliverResponse(400,'No Data Found',NULL);       
    }
    else
    {
       $jsonResponse=json_encode($liveData); 
    }
}
?>