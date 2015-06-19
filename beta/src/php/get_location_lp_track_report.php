<?php
function get_report_location($lat,$lng,&$placename)
{	
	//echo "\nLoc0";
	$geoCodeURL = "http://nominatim.openstreetmap.org/reverse?format=xml&lat=".$lat."&lon=".$lng."&zoom=18&addressdetails=1";
	$xml=@simplexml_load_file($geoCodeURL);	
	//echo "\nxml=".$xml;
	foreach($xml->result[0]->attributes() as $a => $b) 
	{		
		if($a=="lat")
		{
			$lat_local=$b;
		}
		else if($a=="lon")
		{
			$lon_local=$b;
		}
	}
	//echo "\nLoc0";
	$lat=round($lat,4);
	//echo "\nLoc1";
	$lng=round($lng,4);	
	//echo "\nLoc2";
	$lat_local = round(floatval($lat_local),4);
	$lon_local = round(floatval($lon_local),4);

	//echo "\nlat1=".$lat."lng1=".$lng."lat2=".$lat_local."long2=".$lon_local."<br>";
	$distance="";
	calculate_report_distance($lat,$lat_local,$lng,$lon_local,$distance);
	$placename=round($distance,2)." km from ".$xml->result;
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
