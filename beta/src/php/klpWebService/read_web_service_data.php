<?php
set_time_limit(18000);
//include_once("../database_ip.php");
$HOST = "itrackdb.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
$DBASE = "iespl_vts_beta";
//$USER = "root";
$USER = "bailoo";
$PASSWD = 'neon04$VTS';
$account_id = "715";
if($account_id == "715") 
{
    $user_name = "klp_out";
}
//if($account_id == "231") $user_name = "delhi@";
echo "\nDBASE=".$DBASE." ,USER=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

// Pull in the NuSOAP code
require_once('lib/nusoap.php');
// Create the client instance
$client = new nusoap_client('http://27.251.75.182/ItrackWebService/Service.asmx?wsdl', true);
// Check for an error
$err = $client->getError();
if ($err) 
{
    // Display the error
    echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
    // At this point, you know the call that follows will fail
}
// Call the SOAP method
//$date1="12/03/2014";
//$date2="13/03/2014";

$date = date('Y-m-d');
$previous_date = date('Y-m-d', strtotime($date .' -1 day'));

$parts1 = explode('-',$previous_date);
$date1 = $parts1[2] . '/' . $parts1[1] . '/' . $parts1[0];

$parts2 = explode('-',$date);
$date2 = $parts2[2] . '/' . $parts2[1] . '/' . $parts2[0];

/*date_default_timezone_set('Asia/Calcutta');
$date2=date("Y-m-d H:i:s");
$curentDTseconds=strtotime($date2);

$previousDateDuration=$curentDTseconds-(6*60*60);


$date1=date("Y-m-d H:i:s",$previousDateDuration);*/
//echo "date1=".$date1."date2=".$date2."<br>";

//$date1="01/10/2015";
//$date2="01/10/2015";
//$previous_date = date('Y-m-d', strtotime($date .' -1 day'));

$result = $client->call('GateInOut', array('FromDate' => $date1,'ToDate'=>$date2));
//$result = $client->call('GateInOut', array('FromDate' => '01/01/2014','ToDate'=>'02/01/2014'));
// Check for a fault
if ($client->fault) 
{
    echo '<h2>Fault</h2><pre>';
    print_r($result);
    echo '</pre>';
} 
else 
{
    // Check for errors
    $err = $client->getError();
    if ($err) 
    {
        // Display the error
        echo '<h2>Error</h2><pre>' . $err . '</pre>';
    } 
    else 
    {
        // Display the result
        //echo '<h2>Result</h2><pre>';
        //print_r($result);
        $wSKlpDataArr=$result['GateInOutResult']['diffgram']['GateInOut']['Table'];
        for($i=0;$i<sizeof($wSKlpDataArr);$i++)
        { 
            $locationIdArr=explode(",",$wSKlpDataArr[$i]['CUSTOMER']);                
            $uniqueWSKey=$locationIdArr[1]."#".$wSKlpDataArr[$i]['VEHICLE_NO'];
            //echo "uniqueWSKey=".$uniqueWSKey."<br>";
            $wSData[$uniqueWSKey]=array(
                                        "vehicleName"=>$wSKlpDataArr[$i]['VEHICLE_NO'],
                                        "customerNo"=>$wSKlpDataArr[$i]['CONTAINER_NO'],
                                        "locationIds"=>$wSKlpDataArr[$i]['CUSTOMER'],
                                        "customerInDatetime"=>$wSKlpDataArr[$i]['TARGET_IN_DATE'],
                                        "customerOutDatetime"=>$wSKlpDataArr[$i]['TARGET_OUT_DATE'],
                                        "icdInDatetime"=>$wSKlpDataArr[$i]['GATE_IN_DATE'],
                                        "icdOutDatetime"=>$wSKlpDataArr[$i]['GATE_OUT_DATE']
                                     );
        }
        //print_r($wSKlpDataArr);
        echo '</pre>';
    }
}


$query = "SELECT sno,vehicle_name,container_no,icd_out_datetime,icd_code,icd_in_datetime,factory_ea_datetime,".
        "factory_ed_datetime,factory_code,actual_icd_out_datetime,actual_icd_in_datetime,remark FROM icd_webservice_data ".
        "WHERE icd_in_datetime!='' AND account_id=715";
$result = mysql_query($query,$DbConnection);
while($row=mysql_fetch_object($result))
{
    $locationIdDbArr=explode(",",$row->factory_code);                
    $uniqueDBKey=$locationIdDbArr[1]."#".$row->vehicle_name;
    //echo "uniqueDbKey=".$uniqueDBKey."<br>";
    $dBData[$uniqueDBKey] = array(
                                        "vehicleName"=>$row->vehicle_name,
                                        "customerNo"=>$row->container_no,
                                        "locationIds"=>$row->factory_code,
                                        "customerInDatetime"=>$row->factory_ea_datetime,
                                        "customerOutDatetime"=>$row->factory_ed_datetime,
                                        "icdInDatetime"=>$row->icd_in_datetime,
                                        "icdOutDatetime"=>$row->icd_out_datetime
                                     );
}
$icd_code_global = "KLPL/ICD Panki";
$current_date = date('Y-m-d H:i:s');
foreach($wSData as $key => $wSValue)
{
    //echo"vNamew=".$wSData[$key]['vehicleName']."<br>";
    //print_r($wSValue);
    //echo "<br><br>";
    //echo"vNameS=".$dBData[$key]['vehicleName']."<br>";
    if($wSData[$key]['icdInDatetime']!="")
    {
        $dateObj= new DateTime(str_replace("/","-",$wSData[$key]['icdInDatetime']));
        $wSIcdInDatetime=$dateObj->format('Y-m-d H:i:s'); 
    }
    if((count($dBData[$key])==0) && ($wSValue['icdOutDatetime'])!=$dBData[$key]['icdOutDatetime'])
    {
        $updateIcdOutDatetime=$wSValue['icdOutDatetime'];
        $updateVehicleName=$wSValue['vehicleName'];
        $queryUpdate = "UPDATE icd_webservice_data SET icd_in_datetime='$updateIcdOutDatetime' WHERE vehicle_name=".
                "'$updateVehicleName'";
        //echo "<br$updateVehicleName$query_update;
        $resultUpdate = mysql_query($queryUpdate,$DbConnection);
    }
    if((count($dBData[$key])==0) || (($dBData[$key]['icdInDatetime']!="0000-00-00 00:00:00") && ($wSIcdInDatetime>$dBData[$key]['icdInDatetime'])))
    {
        //echo "dbCnt=".count($dBData[$key])."<br>";
        //echo "dbdateIn=".$dBData[$key]['icdInDatetime']."<br>";
        //echo "wsIn=".str_replace("/","-",$wSData[$key]['icdInDatetime'])."<br>";
        $vehicleName=$wSData[$key]['vehicleName'];
        $customerNo=$wSData[$key]['customerNo'];
        $dateObj = new DateTime(str_replace("/","-",$wSData[$key]['icdOutDatetime']));

        $icdOutDatetime=$dateObj->format('Y-m-d H:i:s');
        //echo "icdOutDatetime=".$icdOutDatetime."<br>";
        $locationIds=$wSData[$key]['locationIds'];
     
        $dateObj =new DateTime(str_replace("/","-",$wSData[$key]['customerInDatetime']));
        $customerInDatetime=$dateObj->format('Y-m-d H:i:s');
        $dateObj=new DateTime(str_replace("/","-",$wSData[$key]['customerOutDatetime']));
        $customerOutDatetime=$dateObj->format('Y-m-d H:i:s');
        //echo "vehicleName=".$wSData[$key]['vehicleName']."inDate=".$wSData[$key]['icdInDatetime']."<br>";
        
        
        $queryInsert = "INSERT INTO icd_webservice_data("
                . "vehicle_name,container_no,icd_code,icd_out_datetime,factory_code,factory_ea_datetime,"
                . "factory_ed_datetime,icd_in_datetime,account_id,create_date,status,remark) "
                . "values('$vehicleName','$customerNo','$icd_code_global','$icdOutDatetime','$locationIds',"
                . "'$customerInDatetime','$customerOutDatetime','$wSIcdInDatetime','$account_id',"
                . "'$current_date',1,'-')";
        //echo "Query=".$queryInsert."<br><br>";
       $resultInsert = mysql_query($queryInsert, $DbConnection);
       
       $queryInsert1 = "INSERT INTO vehicle_last_execution_datetime("
                . "vehicle_name,last_execution_datetime) "
                . "VALUES('$vehicleName','$customerOutDatetime')";
        //echo "Query=".$queryInsert."<br><br>";
       $resultInsert1 = mysql_query($queryInsert1, $DbConnection);
       
    }
    else 
    {
        if(($dBData[$key]['icdInDatetime']!="0000-00-00 00:00:00") && ($wSData[$key]['icdInDatetime']!=""))
        {
            $updateIcdOutDatetime=$wSValue['icdOutDatetime'];
            $updateVehicleName=$wSValue['vehicleName'];
            $queryUpdate1 = "UPDATE icd_webservice_data SET icd_in_datetime='$updateIcdOutDatetime' WHERE vehicle_name=".
                    "'$updateVehicleName'";
            //echo "<br$updateVehicleName$query_update;
            $resultUpdate1 = mysql_query($queryUpdate1,$DbConnection); 
        }
    }
}


?>
