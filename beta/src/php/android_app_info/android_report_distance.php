<?php
    
    //error_reporting(-1);
//ini_set('display_errors', 'On');
set_time_limit(3000);
$pathInPieces = explode(DIRECTORY_SEPARATOR ,dirname(__FILE__));
print_r($pathInPieces);
//$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2]."/".$pathInPieces[3];
$pathToRoot=$pathInPieces[0]."/".$pathInPieces[1]."/".$pathInPieces[2];
if(file_exists($pathToRoot."/phpApi/libLogNew.php"))
{
    echo "true<br>";
}
else 
{
    echo "false<br>";
}
echo "<br><br>";
$pathToRoot=$pathInPieces[1]."/".$pathInPieces[2];
if(file_exists($pathToRoot."/phpApi/libLogNew.php"))
{
    echo "true1<br>";
}
else 
{
    echo "false1<br>";
}
 echo "fileExists=".file_exists($pathToRoot."/phpApi/libLogNew.php");
//echo "pathToRoot=".$pathToRoot."<br>";
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
include_once("android_calculate_distance.php");
require_once "lib/nusoap.php"; 

//====cassamdra //////////////
    include_once($pathToRoot.'/beta/src/php/xmlParameters.php');
    include_once($pathToRoot.'/beta/src/php/parameterizeData.php'); /////// for seeing parameters
    include_once($pathToRoot.'/beta/src/php/data.php');   
    include_once($pathToRoot.'/beta/src/php/getDeviceDataTest.php');
    
////////////////////////

    $deviceImeiNo="862170017134329";
            $startDate="2015/08/06 00:00:00";
            $endDate="2015/08/06 16:38:36";
            $userInterval="60";
$result=getDistanceDeviceData($deviceImeiNo, $startDate, $endDate, $userInterval);
echo $result;

$DEBUG = 0;		
global $distance_data;
$distance_data=array();	
function getDistanceDeviceData($deviceImeiNo, $startDate, $endDate, $userInterval)
{
    $parameterizeData=new parameterizeData();
    $ioFoundFlag=0;
    global $o_cassandra;
     $requiredData="All";
     $sortBy='h';
    $parameterizeData->latitude="d";
    $parameterizeData->longitude="e";
    global $DbConnection;
	$Query="SELECT vehicle.vehicle_name,vehicle.vehicle_type,vehicle.vehicle_number FROM vehicle USE INDEX(v_vehicleid_status)".
	",vehicle_assignment USE INDEX(va_vehicleid_status,va_vehicleid_imei_status) WHERE vehicle.vehicle_id=vehicle_assignment.".
	"vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_assignment.device_imei_no='$deviceImeiNo'";
	//echo "Query=".$Query."<br>";
    $Result=mysql_query($Query,$DbConnection);
	$Row=mysql_fetch_row($Result);
	$vname=$Row[0];
        echo "vname=".$vname."<br>";
}
?>