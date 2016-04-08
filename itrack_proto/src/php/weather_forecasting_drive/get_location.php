<?php

header('Access-Control-Allow-Origin: *');


$latitude =trim($_GET['lat']);
$longitude=trim($_GET['lon']);

$curl_handle=curl_init();
curl_setopt($curl_handle, CURLOPT_URL,'http://nominatim.openstreetmap.org/reverse?format=json&lat='.$latitude.'&lon='.$longitude.'&zoom=18&addressdetails=1');
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
$json = curl_exec($curl_handle);
curl_close($curl_handle);
$data = json_decode($json, true);
//print_r($data);
$address=$data['display_name'];
echo $address;

 
 ?>
