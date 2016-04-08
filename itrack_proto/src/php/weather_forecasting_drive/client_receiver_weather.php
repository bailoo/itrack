<?php
/*
header('Access-Control-Allow-Origin: *');
$point_1=$_GET['point_test'];
//$point_1="(32.38263833333, 75.58866333333003)";
$point_1=substr($point_1,0,-1);
$point_1=substr($point_1,1,-1);
$point_1=explode(",",$point_1);

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
echo $address.':'.$data['lat'].':'.$data['lon'];*/

$latitude =trim($_GET['lat']);
$longitude=trim($_GET['lon']);

if($latitude =="26.503580000000003" && $longitude=="80.24886000000001" )
{
	echo ":wr_strom.png:";
}
if($latitude =="26.48956" && $longitude=="80.26333000000001" )
{
	echo ":wr_sunny.png:";
}
if($latitude =="26.476540000000004" && $longitude=="80.27359000000001" )
{
	echo ":wr_cloudy.png:";
}
else
{
	echo ":wr_cloudy.png:";
}




 
 ?>
