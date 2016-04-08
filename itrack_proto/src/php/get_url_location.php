<?php

$latitude = $_REQUEST['lat'];
$longitude = $_REQUEST['lng'];
get_location($latitude,$longitude);

function get_location($lat,$lng)
{
  $geoCodeURL = "http://nominatim.openstreetmap.org/reverse?format=xml&lat=".$lat."&lon=".$lng."&zoom=18&addressdetails=1";
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
  }
  $lat=round($lat,4);
  $lng=round($lng,4);
  $lat_local = round(floatval($lat_local),4);
  $lon_local = round(floatval($lon_local),4);
  
  //echo "lat1=".$lat."lng1=".$lng."lat2=".$lat_local."long2=".$lon_local."<br>";
  $distance="";
  calculate_distance_1($lat,$lat_local,$lng,$lon_local,&$distance);
  $distance = round($distance,2);
  $placename=$distance." km from ".$xml->result;
  echo $placename;
}



function calculate_distance_1($lat1, $lat2, $lon1, $lon2, &$distance)
{
  $lat1 = deg2rad($lat1);
  $lon1 = deg2rad($lon1);
  
  $lat2 = deg2rad($lat2);
  $lon2 = deg2rad($lon2);
  
  $delta_lat = $lat2 - $lat1;
  $delta_lon = $lon2 - $lon1;
  
  $temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
  $distance = 6378.1 * 2 * atan2(sqrt($temp),sqrt(1-$temp));
}
/*
$latitude = $_REQUEST['lat'];
$longitude = $_REQUEST['lng'];
get_location($latitude,$longitude);

function get_location($latitude,$longitude)
{
  $latitude = round($latitude,5);
	$longitude = round($longitude,5);
	
  ///JSON CODE
  $geoCodeURL = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=false"; 
  
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
  
  echo $placename;
 
}


function get_location2($latitude,$longitude,$altitude,&$placename)
{
  //echo "\nInlocation2";
  //$key = "ABQIAAAA4X57IQ2fU51rCR3EYs0iQhSgwT_vv1TnouX4-SVL175XfuoGThR_0YmaCf5_qy1_r-huo-rmu_V4LQ";
  //$key = "AIzaSyDtbgNQLkQqg5hIhbvBeHBeT98krvJEvfA";
  $key = "AIzaSyDoOnQ2JW-xK4qX9Zj665Cgv5XWbQc-KBw";
	
	$latitude = round($latitude,5);
	$longitude = round($longitude,5);
	//$latitude = round($latitude,5);
	//$longitude = round($longitude,5);
	$point = $latitude.','.$longitude;	

  //$Request = "http://209.85.175.147/maps/geo?q=26.2369,80.34986&output=xml&key=AIzaSyC6rUKRZKFGsn1dN0K0gRkqFNYq5wahQ7M";
  $Request = "http://209.85.175.147/maps/geo?q=" . $point . "&output=xml&key=$key";
	//$Request = "http://209.85.129.104/maps/geo?q=" . $point . "&output=xml&key=$key";
	//$Request = "http://maps.google.com/maps/geo?q=".$point."&output=xml&sensor=true_or_false&key=$key";
	//$Request = "http://maps.googleapis.com/maps/geo?q=" . $point . "&output=xml&sensor=true_or_false&key=$key";	
	//$Request = "http://74.125.227.20/maps/geo?q=" . $point . "&output=xml&key=$key";	  	
	//$Request = str_replace("%3A",":",implode("/",array_map("rawurlencode",explode("/",$Request))));
	//echo $Request."<br>";
 	 
  $page = file_get_contents($Request);
  //echo htmlspecialchars(file_get_contents($Request));
  
  //echo "<br>page=".$page;
	$xml = new SimpleXMLElement($page);
  //echo "<br>xml=".$xml;		
	$size = sizeof( $xml->Response->Placemark);
	//echo "<br>size=".$size;
	
	$temp = 0;
	$j = 0;
	$acc =0;
	$h_acc = 0;
	
	global $test_vts;
	//date_default_timezone_set('Asia/Calcutta');
	$datetime=date('Y/m/d H:i:s');		
	$flag = 0;
	
  for($i=0;$i<$size;$i++)
	{				
		foreach($xml->Response->Placemark[$i]->AddressDetails->attributes() as $a => $b)
		{					
			$acc = (int)$b;	
							
			if($acc>=4)
			{
				$coordinates = $xml->Response->Placemark[$i]->Point->coordinates;
				$coordinatesSplit = split(",", $coordinates);					
				$latitude2 = $coordinatesSplit[1];
				$longitude2 = $coordinatesSplit[0];
				$address = $xml->Response->Placemark[$i]->address;
			
				if($address)
				{										
					$flag=1;
					$h_acc = $acc;
				}
			} //acc closed

			if($acc>$temp)
			{
				$temp = $acc;
				$j=$i;
				$acc =0;
			} //if closed

		} //for each closed
	} // for closed
	
	///////////////// IF ADDRESS FOUND WITH ACCURACY >4					
	//echo "in if1";
	$coordinates = $xml->Response->Placemark[$j]->Point->coordinates;
	$coordinatesSplit = split(",", $coordinates);
	// Format: Longitude, Latitude, Altitude
	$latitude2 = $coordinatesSplit[1];
	$longitude2 = $coordinatesSplit[0];
	$address = $xml->Response->Placemark[$j]->address;	
			
	//echo "address=".$address;
	//echo "<br>point1=(".$latitude." ,".$longitude.") | point2=(".$latitude2." ,".$longitude2.")";	
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
*/


?>
