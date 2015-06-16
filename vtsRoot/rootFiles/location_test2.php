<?php
  //$Request = "http://74.125.227.20/maps/geo?q=26.2369,80.34986&output=xml&key=ABQIAAAA4X57IQ2fU51rCR3EYs0iQhSgwT_vv1TnouX4-SVL175XfuoGThR_0YmaCf5_qy1_r-huo-rmu_V4LQ";
  $lat = "26.2369";
  $lng = "80.34986";
  
  $geoCodeURL = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=false"; 
  /*$result = json_decode(file_get_contents($geoCodeURL), true);

  print_r($result); */
  
  $raw=@file_get_contents($geoCodeURL);
  $json_data=json_decode($raw);
 
  if ($json_data->status=="OK") {
            $fAddress=explode(",", $json_data->results[count($json_data->results)-2]->formatted_address);  //this is human readable address --> getting province name
            var_dump($json_data->results);  //dumping result
            
            echo "<br><br>size=".sizeof($json_data->results);
            for($i=0;$i<sizeof($json_data->results);$i++)
            {
              echo "<br>RESULT=".$json_data->results[$i]->formatted_address;
              $lat = $json_data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
              $long = $json_data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
              echo "<br>LAT=".$lat;
              echo "<br>LNG=".$lng;
              
            }
            
            //echo "<br>LAT=".$json_data->results[0]->lat;
            //echo "<br>LNG=".$json_data->results[0]->lng;
  } else {
            //if no result found, status would be ZERO_RESULTS
            echo "<br>NO RESULT=".$json_data->status;  
  }
      
  /*$Request = "http://74.125.227.20/maps/geo?q=26.2369,80.34986&output=xml&key=ABQIAAAA4X57IQ2fU51rCR3EYs0iQhSgwT_vv1TnouX4-SVL175XfuoGThR_0YmaCf5_qy1_r-huo-rmu_V4LQ";
  echo $Request."<br>";
 	 
  $page = file_get_contents($Request);
  echo htmlspecialchars(file_get_contents($Request));
  
  //echo "<br>page=".$page;
	$xml = new SimpleXMLElement($page);
  //echo "<br>xml=".$xml;		
	$size = sizeof( $xml->Response->Placemark);
	echo "<br>Result size=".$size;*/
	
?>
