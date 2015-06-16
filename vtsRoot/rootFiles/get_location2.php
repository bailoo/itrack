<?php

$latitude = "26.2369";
$longitude = "80.34986";
$DbConnection = "-";

  
get_location($latitude,$longitude,$altitude,&$placename,$DbConnection);

echo "place=".$placename;


function get_location($latitude,$longitude,$altitude,&$placename,$DbConnection)
{
  //$key = "ABQIAAAA4X57IQ2fU51rCR3EYs0iQhSgwT_vv1TnouX4-SVL175XfuoGThR_0YmaCf5_qy1_r-huo-rmu_V4LQ";
	
	$latitude = round($latitude,5);
	$longitude = round($longitude,5);
	
  ///JSON CODE
  $geoCodeURL = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=false"; 
  /*$result = json_decode(file_get_contents($geoCodeURL), true);

  print_r($result); */
  
  $raw=@file_get_contents($geoCodeURL);
  $json_data=json_decode($raw);
 
  if ($json_data->status=="OK") 
  {
    $fAddress=explode(",", $json_data->results[count($json_data->results)-2]->formatted_address);  //this is human readable address --> getting province name
    //var_dump($json_data->results);  //dumping result
    
    //echo "<br><br>size=".sizeof($json_data->results);            
    //echo "<br>RESULT=".$json_data->results[0]->formatted_address;
    $address = $json_data->results[0]->formatted_address;
    $latitude2 = $json_data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    $longitude2 = $json_data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
    
    calculate_distance_local($latitude,$latitude2,$longitude,$longitude2,&$distance_1);
    
    $distance_1 = round($distance_1,2);
    //echo "<br>msgloc=".$distance_1;
    
    if($distance_1 > 50 )
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
    //if no result found, status would be ZERO_RESULTS
    //echo "<br>NO RESULT=".$json_data->status;
    $placename = "-";  
  }	
	
}


function calculate_distance_local($lat1, $lat2, $lon1, $lon2, &$distance) 
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
