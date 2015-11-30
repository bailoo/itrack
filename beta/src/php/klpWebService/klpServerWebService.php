<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
$HOST = "itrackdb.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
$DBASE = "iespl_vts_beta";
$USER = "bailoo";
$PASSWD = 'neon04$VTS';


$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

//$vehicleName="UP78CN7842";

function getVehicleDbData($vehicleName)
{
    global $DbConnection;
    //$dataArray[]=array('vehicleName'=>vehicleName);
   $Query="SELECT * FROM icd_webservice_data WHERE vehicle_name='$vehicleName' ORDER BY sno DESC LIMIT 1";
   //echo "Query=".$Query."<br>";
		  // $dataArray[]=array('query'=>$Query);
   $Result=mysql_query($Query,$DbConnection);
   
   //echo "result=".$Result;
   while($Row=mysql_fetch_object($Result))
   {
        $dataArray[]=array(
                            'vehicleName'=>$Row->vehicle_name,
                            'icdInDatetime'=>$Row->actual_icd_in_datetime,
                            'icdOutDatetime'=>$Row->actual_icd_out_datetime,
                            'customerInDatetime'=>$Row->factory_in_datetime,
                            'customerOutDatetime'=>$Row->factory_out_datetime,
                            'distanceTravel'=>$Row->distance_travelled,
                            'twoHourlyDistance'=>$Row->hourly_distance
                        );
   }
   return $dataArray;
}

//$dataArr=getVehicleDbData($vehicleName);
//print_r($dataArr);

require_once "lib/nusoap.php";
$namespace = "http://tempuri.org";
// create a new soap server
$server = new soap_server();
// configure our WSDL
$server->configureWSDL("ELSService");
// set our namespace
$server->wsdl->schemaTargetNamespace = $namespace;
// register our WebMethod
$server->register(
                // method name:
                'getVehicleDbData', 		 
                // parameter list:
                array('name'=>'xsd:string'), 
                // return value(s):
                array('return'=>'xsd:string'),
                // namespace:
                $namespace,
                // soapaction: (use default)
                false,
                // style: rpc or document
                'rpc',
                // use: encoded or literal
                'encoded',
                // description: documentation for the method
                'A simple Hello World web method');
                
// Get our posted data if the service is being consumed
// otherwise leave this data blank.                
$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) 
                ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';

// pass our posted data (or nothing) to the soap service                    
$server->service($POST_DATA);                
exit();
?>