<?php

$latitude = $_REQUEST['lat'];
$longitude = $_REQUEST['lng'];
//echo "\nIn Get location";
//$latitude = "26.342342";
//$longitude = "80.342342";

get_location($latitude,$longitude);

function get_location($lat,$lng)
{
	//echo "\nIn Get locationFunction";
	$endpoint="http://maps.googleapis.com/maps/api/geocode/json?latlng=".trim($lat).",".trim($lng)."&sensor=false";
 
	$raw=@file_get_contents($endpoint);
	$json_data=json_decode($raw);
 
	//echo "\nJSON_Status=".$json_data->status;
	if ($json_data->status=="OK") {
        	  //$fAddress=explode(",", $json_data->results[count($json_data->results)-2]->formatted_address);  //this is human readable address --> getting province name
	         $fAddress= $json_data->results[0]->formatted_address;
		 // var_dump($json_data->results);  //dumping result

		$lat_local = $json_data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
		$lon_local = $json_data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

		$lat=round($lat,4);
		$lng=round($lng,4);	
		$lat_local = round(floatval($lat_local),4);
		$lon_local = round(floatval($lon_local),4);

		//echo "lat1=".$lat."lng1=".$lng."lat2=".$lat_local."long2=".$lon_local."<br>";
		$distance="";
		calculate_report_distance($lat,$lat_local,$lng,$lon_local,&$distance);
		//$placename=round($distance,2)." km from ".$xml->result;
		$placename=round($distance,2)." km from ".$fAddress;
	}
	echo $placename;
}

function calculate_report_distance($lat1, $lat2, $lon1, $lon2, &$distance) 
{
	$lat1 = deg2rad($lat1);
	$lon1 = deg2rad($lon1);

	$lat2 = deg2rad($lat2);
	$lon2 = deg2rad($lon2);
	
	$delta_lat = $lat2 - $lat1;
	$delta_lon = $lon2 - $lon1;
	
	$temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
	$distance = 6378.1  * 2 * atan2(sqrt($temp),sqrt(1-$temp));
}
?>
