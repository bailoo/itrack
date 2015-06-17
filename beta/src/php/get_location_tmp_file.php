<?php
$point_1=$_GET['point_test'];
//echo "point=".$point_1."<br>";
$point_1=substr($point_1,0,-1);
$point_1=substr($point_1,1,-1);
//echo "point1=".$point_1."<br>";
$point_1=explode(",",$point_1);	
//$url="http://nominatim.openstreetmap.org/reverse?format=json&lat=".trim($point_1[0])."&lon=".trim($point_1[1])."&zoom=18&addressdetails=1";
$url="http://open.mapquestapi.com/geocoding/v1/reverse?key=Fmjtd|luur200a2q%2C25%3Do5-9ayw9y&location=".trim($point_1[0]).",".trim($point_1[1]);
$json = file_get_contents($url);
$data=json_decode($json);

$placeNameObj=$data->results[0]->locations[0];	
$placeName=$placeNameObj->adminArea5." ".$placeNameObj->adminArea4." ".$placeNameObj->adminArea3." ".$placeNameObj->adminArea1;

$latLngObj=$data->results[0]->locations[0]->displayLatLng;
$lat=$latLngObj->lat;
$lng=$latLngObj->lng;
//print_r($data);
//print_r($data);
echo $placeName.":".$lat.":".$lng;
?>
