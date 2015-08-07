<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
set_time_limit(800);
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
    
    
    /*$vehicleserialWithIo="862170017134329:#,";
    $startDate="2015/08/06 00:00:00";
    $endDate="2015/08/06 16:38:36";
    //$userInterval="30";
    $result=getLiveDeviceData($vehicleserialWithIo,$startDate,$endDate,$userInterval);
    echo $result;*/
function getLiveDeviceData($vehicleserialWithIo)
{
    $device_str= $vehicleserialWithIo;
    global $o_cassandra;
    //$device_str="862170018324168:862170018322923:#1^fuel:7^engine:5^door_open:2^fuel_voltage:6^fuel_lead,1^fuel:7^engine:5^door_open:2^fuel_voltage:6^fuel_lead,";
    $device_str = explode('#',$device_str);
    //echo "device_str1=".$device_str[0]."<br>";
    //echo "device_str2=".$device_str[1]."<br>";
    $vesrial_2 = explode(':',substr($device_str[0],0,-1));
    $iotype_element = explode(',',substr($device_str[1],0,-1));

    /*$vesrial_1=substr($vserial,0,-1);
    $vesrial_2=explode(",",$vesrial_1);*/

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

    for($i=0;$i<sizeof($vesrial_2);$i++)
    {
        $sub_str="";
        $io_str="";
        $t=time();
        $rno = rand();
        $LastRecordObject=new lastRecordData();	
	//echo "imei=".$imei."<br>";
	$LastRecordObject=getLastRecord($vesrial_2[$i],$sortBy,$parameterizeData);
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
                            'status'=>$status
                        );
          
            $xml_date = $LastRecordObject->deviceDatetimeLR[0];
            $speed = $LastRecordObject->speedLR[0];
            if($speed=='-')
            {
                    $speed="0.0";
            }
            $lat = $LastRecordObject->latitudeLR[0];
            $lng = $LastRecordObject->longitudeLR[0];					
            $day_max_speed = $LastRecordObject->dayMaxSpeedLR[0];
            if($day_max_speed=='')
            {
                    $day_max_speed="0.0";
            }					
            $day_max_speed_time = $LastRecordObject->dayMaxSpeedTimeLR[0];
            $last_halt_time = $LastRecordObject->lastHaltTimeLR[0];  
					
            $io1= $LastRecordObject->io1LR[0];
            $io2= $LastRecordObject->io2LR[0];
            $io3= $LastRecordObject->io3LR[0];
            $io4= $LastRecordObject->io4LR[0];
            $io5= $LastRecordObject->io5LR[0];
            $io6= $LastRecordObject->io6LR[0];
            $io7= $LastRecordObject->io7LR[0];
            $io8= $LastRecordObject->io8LR[0];
            //echo "io8=".$io8."<br>";
																										 
            $xml_date_sec = strtotime($xml_date);
            $last_halt_time_sec = strtotime($last_halt_time);			
            $current_time_sec = strtotime($current_time);
            //$diff = ($current_time_sec - $xml_date_sec);   // IN SECONDS
            $diff = ($current_time_sec - $last_halt_time_sec); 

            if($speed>=5 && $diff <=600)
            {
              $status = "Running";
              //echo "<br>Running";
            } 
            else
            {
              $status = "Stopped";
            }

            $io_typ_value=explode(":",$iotype_element[$i]);				
            $io_cnt=count($io_typ_value);
            if($io_cnt>0)
            {						
                for($ui=0;$ui<sizeof($io_typ_value);$ui++)
                {
                    $iotype_iovalue_str1=explode("^",$io_typ_value[$ui]);	
                    //echo "io_name=".$iotype_iovalue_str1[1]."io_value=".$iotype_iovalue_str1[0]."<br>";
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
                        if($io_values!="")
                        {
                            if($io_values>=-30 && $io_values<=70)
                            {
                                //echo "in if";
                                $io_str=$io_str.$iotype_iovalue_str1[1].":".$io_values."@";
                            }
                            else
                            {
                                //echo "in if 1";									
                                $io_str=$io_str."Temperature:-@";
                            }
                        }
                        else
                        {
                            //echo "in if 2";
                            $io_str=$io_str."Temperature:-@";
                        }
                    }
                    else if($iotype_iovalue_str1[1]!="")
                    {
                        //echo "engine".$iotype_iovalue_str1[1]."<br>";
                        if(trim($iotype_iovalue_str1[1])=="engine")
                        {
                            if($io_values<=350)
                            {
                                $io_str=$io_str.$iotype_iovalue_str1[1].":Off@";										
                            }
                            else
                            {
                                $io_str=$io_str.$iotype_iovalue_str1[1].":ON@";										
                            }
                        }
                        else if($iotype_iovalue_str1[1]=="door_open")
                        {
                            if($io_values<=350)
                            {
                                $io_str=$io_str.$iotype_iovalue_str1[1].":Close@";										
                            }
                            else
                            {
                                $io_str=$io_str.$iotype_iovalue_str1[1].":Open@";										
                            }
                        }
                        else if($iotype_iovalue_str1[1]=="fuel_lead")
                        {
                            if($io_values<=350)
                            {					
                                $io_str=$io_str.$iotype_iovalue_str1[1].":Close@";										
                            }
                            else
                            {
                                $io_str=$io_str.$iotype_iovalue_str1[1].":Open@";	
                            }
                        }
                        else
                        {
                            if($io_values!="")
                            {
                                $io_str=$io_str.$iotype_iovalue_str1[1].":".$io_values."@";										
                            }
                            else
                            {
                                $io_str=$io_str.$iotype_iovalue_str1[1].":-@";										
                            }
                        }
                    }			
                }
            }					
            $io_str=substr($io_str,0,-1);
            $sub_str=$sub_str.$xml_date.",".$speed.",".$lat.",".$lng.",".$day_max_speed.",".$day_max_speed_time.",".$last_halt_time.",".$status.",".$io_str;				
	}
        if($sub_str=="")
	{
            $sub_str="No Data Found";
	}
        $LastRecordObject=null;
	$final_str=$final_str.$sub_str."#";        	
    }
    return $final_str;
}
$server = new soap_server();
$server->register("getLiveDeviceData");
$server->service($HTTP_RAW_POST_DATA);
?>