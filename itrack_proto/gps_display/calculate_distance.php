<?php
//distance 1146.2996977844934

$lat1="26.45832";
$lat2="26.46786";
$lon1="80.4416";
$lon2="80.44593";

calculate_distance($lat1, $lat2, $lon1, $lon2, &$distance);
echo "<br>distance=". $distance*1000;

function calculate_distance($lat1, $lat2, $lon1, $lon2, &$distance) 
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
