<?php

function get_location($latitude,$longitude,$altitude,&$placename,$DbConnection)
{
  //echo "get loc";
  $key = "ABQIAAAA4X57IQ2fU51rCR3EYs0iQhSgwT_vv1TnouX4-SVL175XfuoGThR_0YmaCf5_qy1_r-huo-rmu_V4LQ";
	
	//$latitude = round($latitude,5);
	//$longitude = round($longitude,5);
	
	get_google_loc($latitude,$longitude,$altitude,&$placename,$DbConnection,$key,$tableid);
  //$placename = reversegeo($latitude,$longitude,$altitude,$key);					
}

/*
function reversegeo($ilatt,$ilonn, $alt, $key)
{  
  $url1='http://maps.googleapis.com/maps/api/geocode/json?latlng='.$ilatt.','.$ilonn.'&sensor=false';
  //echo $url1."<br>";
  
  $ch1 = curl_init();
  curl_setopt($ch1, CURLOPT_URL, $url1);
  curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
  //curl_setopt($ch1, CURLOPT_REFERER, 'http://www.mywebsiteurl.com/Trackfiles/report.php');
  //$body1 = curl_exec($ch1);
  if(($body1 = curl_exec($ch1)) === false) {
    echo 'Curl error: ' . curl_error($ch);
  }        
  curl_close($ch1);
  $json1 = json_decode($body1);
  
  $add=$json1->results[0]->formatted_address;
  
  $latitude2 = $json1->results[0]->geometry->location->lat;
  $longitude2 = $json1->results[0]->geometry->location->lng;    
  
  calculate_distance_local($ilatt,$latitude2,$ilonn,$longitude2,&$distance_1);
  
  //echo "<br>ilatt=".$ilatt." ,lat2=".$latitude2." ,ilng=".$ilonn." ,long2=".$longitude2." ,dist=".$distance_1;          
  
	$distance_1 = round($distance_1,2);
	if($distance_1 > 50 )
	{
		$distance_1 = "-";
		$placename = "-";
	}
	else
	{
		$place_str = $distance_1." km from ".$add;
		$placename = $place_str;
	}
      
  return $placename;
}
*/


function get_google_loc($latitude,$longitude,$altitude,&$placename,$DbConnection,$key,$tableid)
{
	//$latitude = round($latitude,5);
	//$longitude = round($longitude,5);
	$point = $latitude.','.$longitude;	

  //$Request = "http://209.85.175.147/maps/geo?q=" . $point . "&output=xml&key=$key";
	//$Request = "http://209.85.129.104/maps/geo?q=" . $point . "&output=xml&key=$key";
	$Request = "http://maps.google.com/maps/geo?q=".$point."&output=xml&sensor=true_or_false&key=$key";
	//$Request = "http://maps.googleapis.com/maps/geo?q=" . $point . "&output=xml&sensor=true_or_false&key=$key";	
	//$Request = "http://74.125.227.20/maps/geo?q=" . $point . "&output=xml&key=$key";	  
	
	//$Request = str_replace("%3A",":",implode("/",array_map("rawurlencode",explode("/",$Request))));
	//echo "<br>req=".$Request."<br>";


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
	if($distance_1 > 50 )
	{
		$distance_1 = "-";
		$placename = "-";
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

?>
