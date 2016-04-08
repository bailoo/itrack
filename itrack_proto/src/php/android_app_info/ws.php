<?php
/**
 * getLiveDeviceData method
 * @param string $who name of the person we'll say hello to
 * @return string $helloText the hello  string
 */
function getLiveDeviceData($who) 
{	
    return "Hello $who";
}

require_once("lib/nusoap.php");
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
                'getLiveDeviceData', 		 
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