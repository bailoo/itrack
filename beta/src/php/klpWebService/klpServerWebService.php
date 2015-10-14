<?php
$HOST = "itrackdb.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
$DBASE = "iespl_vts_beta";
$USER = "bailoo";
$PASSWD = 'neon04$VTS';


$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

require_once "lib/nusoap.php";

function getVehicleDbData($vehicleName)
{
	global $DbConnection;
	//$dataArray[]=array('vehicleName'=>vehicleName);
   $Query="SELECT * FROM icd_webservice_data WHERE vehicle_name='$vehicleName' AND ".
           "icd_in_datetime!='0000-00-00 00:00:00'";
		  // $dataArray[]=array('query'=>$Query);
   $Result=mysql_query($Query,$DbConnection);
   while($Row=mysql_fetch_object($Result))
   {
        $dataArray[]=array(
                            'vehicleName'=>$Row->vehicle_name,
                            'icdInDatetime'=>$Row->actual_icd_in_datetime,
                            'icdOutDatetime'=>$Row->actual_icd_out_datetime,
                            'customerInDatetime'=>$Row->factory_in_datetime,
                            'customerOutDatetime'=>$Row->factory_out_datetime
                        );
   }
   return $dataArray;
}

$server = new soap_server();
$server->register("getVehicleDbData");
$server->service($HTTP_RAW_POST_DATA);

