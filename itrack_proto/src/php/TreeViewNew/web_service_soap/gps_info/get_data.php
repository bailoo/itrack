<?php
require_once ('lib/nusoap.php');
//$client = new nusoap_client("http://localhost/web_service_soap/gps_info_local/index.php?wsdl", true);
$client = new nusoap_client("http://www.itracksolution.co.in/web_service_soap/gps_info/");
 
$error = $client->getError();
if ($error) {
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}

//$result = $client->call("get_gps", array("userid" => $userid, "pass" => $pass, "imei" => $imei));
//$result = $client->call("get_gps", array("userid" => 'demo', "pass" => 'fe01ce2a7fbac8fafaed7c982a04e229', "imei" => array('359231030206633','359231030206633','359231030206633')));
$result = $client->call("get_gps", array("userid" => 'KTC', "pass" => 'a5c7473997a0505931be647ddd21f499', "imei" => array('862170018319671','862170017792555','862170018371375','862170017134329','862170018319671','862170018373363')));
//$result = $client->call("get_gps", array("KTC"), array("a5c7473997a0505931be647ddd21f499"), array("862170018319671")); 
//$result = $client->call("get_gps", $param);
//$result = $client->call("get_gps", $xml);
 
if ($client->fault) {
    echo "<h2>Fault</h2><pre>";
    print_r($result);
    echo "</pre>";
}
else {
    $error = $client->getError();
    if ($error) {
        echo "<h2>Error</h2><pre>" . $error . "</pre>";
    }
    else {
        //echo "<h2>GPS DATA RESPONSE :</h2><pre>";
		echo "<pre>";
        echo print_r($result);
		//echo $result;
        echo "</pre>";
    }
}
?>