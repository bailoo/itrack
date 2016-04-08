<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');

require_once "lib/nusoap.php";
$client = new nusoap_client("http://ecodrivesolution.com/src/php/klpWebService/klpServerWebService.php");
$error = $client->getError();
if ($error) {
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}
 
 $vName=$_POST['vehicleName'];
 
 $result = $client->call('getVehicleDbData', array('vehicleName' => $vName));
//$result = $client->call("getProd", array("category" => "books"));
 
if ($client->fault) {
    echo "<h2>Fault</h2><pre>";
    print_r($result);
    echo "</pre>";
}
else {
    $error = $client->getError();
    if ($error) 
	{
        echo "<h2>Error</h2><pre>" . $error . "</pre>";
    }
    else 
	{	
	echo "<h2>Result</h2><pre>";
		print_r($result);
        echo "</pre>";
    }
}
?>