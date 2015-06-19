<?php
function get_report_location($lat,$lng,&$placename)
{	
	/*$geoCodeURL = "http://nominatim.openstreetmap.org/reverse?format=xml&lat=".$lat."&lon=".$lng."&zoom=18&addressdetails=1";
	$xml=@simplexml_load_file($geoCodeURL);	
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
	}*/

	$latitude = round($lat,5);
	$longitude = round($lng,5);
	/*$url="http://nominatim.openstreetmap.org/reverse?format=json&lat=".trim($latitude)."&lon=".trim($longitude)."&zoom=18&addressdetails=1";
	$json = file_get_contents($url);
	$data = json_decode($json, TRUE);
	$address=preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '',$data['display_name']);*/

$curl_handle=curl_init();
curl_setopt($curl_handle, CURLOPT_URL,'http://nominatim.openstreetmap.org/reverse?format=json&lat='.trim($latitude).'&lon='.trim($longitude).'&zoom=18&addressdetails=1');
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
$json = curl_exec($curl_handle);
$data = json_decode($json, TRUE);
$address=preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '',$data['display_name']);

	if($address!="")
	{
		//calculate_distance_local($latitude,$data['lat'],$longitude,$data['lon'],&$distance_1);
		calculate_report_distance($lat,$data['lat'],$lng,$data['lon'],$distance_1);

		$distance_1 = round($distance_1,2);
		if($distance_1 > 50)
		{
			$distance_1 = "-";
			$placename = "-";
			//$placename = $address;
		}
		else
		{
			$place_str = $distance_1." km from ".$address;
			$placename = $place_str;
		}
	}
	else
	{
		$endpoint="http://maps.googleapis.com/maps/api/geocode/json?latlng=".trim($lat).",".trim($lng)."&sensor=false";
 
		$raw=@file_get_contents($endpoint);
		$json_data=json_decode($raw);
 
		if ($json_data->status=="OK") 
		{
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
			calculate_report_distance($lat,$lat_local,$lng,$lon_local,$distance);
			//$placename=round($distance,2)." km from ".$xml->result;
			$placename=round($distance,2)." km from ".$fAddress;
		}
		else
		{
			$placename="-";
		}
		
	}
curl_close($curl_handle);
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
