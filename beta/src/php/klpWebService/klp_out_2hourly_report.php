<?php 
//set_time_limit(3600);
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);

$HOST = "itrackdb.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
$DBASE = "iespl_vts_beta";
//$USER = "root";
$USER = "bailoo";
$PASSWD = 'neon04$VTS';
$account_id = "715";
//echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
set_time_limit(10000);
echo "\nAfter Connection";

$abspath = "/var/www/html/vts/beta/src/php";
//$abspath = "C:\\xampp/htdocs/itrackDevelop/beta/src/php";
include_once($abspath.'/calculate_distance.php');
include_once($abspath.'/xmlParameters.php');
include_once($abspath.'/parameterizeData.php');
include_once($abspath.'/data.php');
include_once($abspath.'/getDeviceDataTest.php');

include_once("action_klp_out.php");
$date = date('Y-m-d');
$current_time = date('Y-m-d H:i:s');


$query1 = "SELECT DISTINCT customer_no,station_coord FROM station WHERE ".
            "user_account_id='$account_id' AND status=1 AND type=0";
//echo $query2;
$result1 = mysql_query($query1,$DbConnection); 
$cusotmerCoordDataObj = new stdClass();
while($row1 = mysql_fetch_object($result1))
{
    //echo "in while";
    $cusotmerCoordDataObj->icdCoord[$row1->customer_no]=$row1->station_coord;
} 

//var_dump($cusotmerCoordDataObj);
//echo "<br><br><br><br>";

$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name,vehicle_assignment.device_imei_no FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".
                    "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
                    "vehicle_grouping.account_id='$account_id' AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1";					
$result_assignment = mysql_query($query_assignment,$DbConnection);

while($row_assignment = mysql_fetch_object($result_assignment))
{
    $vehicle_name_db[$row_assignment->vehicle_name] = $row_assignment->device_imei_no;
}
//include_once("read_klp_db_input.php");

$query = "SELECT sno,vehicle_name,container_no,icd_out_datetime,icd_code,icd_in_datetime,factory_ea_datetime,".
        "factory_ed_datetime,factory_code,actual_icd_out_datetime,actual_icd_in_datetime,remark FROM icd_webservice_data ".
        "WHERE icd_in_datetime!='' AND account_id=715";
//echo "Query=".$query."<br>";
$result = mysql_query($query,$DbConnection);
$numRows=mysql_num_rows($result);

date_default_timezone_set('Asia/Calcutta');
$enddate=date("Y-m-d H:i:s");
//echo "enddate=".$enddate."<br>";
while($row=mysql_fetch_object($result))
{
    $getDatetimeQuery="SELECT last_execution_datetime FROM vehicle_last_execution_datetime WHERE vehicle_name="
            . "'$row->vehicle_name'";
    $resultGetDatetime=mysql_query($getDatetimeQuery,$DbConnection);
    $rowGetDatetime=  mysql_fetch_row($resultGetDatetime);
    $startdate=$rowGetDatetime[0];    
    
   // echo "startdate=".$startdate."<br>";
    
    $wSInputDataObj = new stdClass();        
    $wSInputDataObj->vehicleName=$row->vehicle_name;
    $wSInputDataObj->imeiNo=$vehicle_name_db[$row->vehicle_name];
    
    $wLlocationIdArr=explode(",",$row->factory_code);
    //echo "factoryCode=".$wLlocationIdArr[1]."<br>";
    $wSInputDataObj->locationIds=$wLlocationIdArr[0];
    $wSInputDataObj->containerName=$row->container_no;
    $wSInputDataObj->icdOutDateTime=$row->icd_out_datetime;
    $wSInputDataObj->actualIcdOutDatetime=$row->actual_icd_out_datetime;
    $wSInputDataObj->actualIcdInDatetime=$row->actual_icd_in_datetime;
    $wSInputDataObj->customerInDatetime=$row->factory_in_datetime;
    $wSInputDataObj->customerOutDatetime=$row->factory_out_datetime;
    
    //var_dump($wSInputDataObj);
    
    //echo "<br><br>";
    //var_dump($icdCoordDataObj);
    
   // echo "<br><br>";
   // var_dump($cusotmerCoordDataObj);
    $customerCoord=$cusotmerCoordDataObj->icdCoord[$wLlocationIdArr[0]];
    if($customerCoord!="")
    {  
        $icdCoord='26.45718N,80.24565E';
        //echo "coord=".$customerCoord."<br>";
        getDataToFillDetail($icdCoord,$customerCoord,$wSInputDataObj, $startdate, $enddate,$DbConnection);
       // break;
    }
   
} 

?>
