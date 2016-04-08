<?php
  //$Request = "http://209.85.175.147/maps/geo?q=" . $point . "&output=xml&key=$key";
	//$Request = "http://209.85.129.104/maps/geo?q=" . $point . "&output=xml&key=$key";
	//$Request = "http://maps.google.com/maps/geo?q=".$point."&output=xml&sensor=true_or_false&key=$key";
	//$Request = "http://maps.googleapis.com/maps/geo?q=" . $point . "&output=xml&sensor=true_or_false&key=$key";	
	//$Request = "http://74.125.227.20/maps/geo?q=" . $point . "&output=xml&key=$key";	  	
  //$Request = "http://74.125.227.20/maps/geo?q=26.2369,80.34986&output=xml&key=AIzaSyC6rUKRZKFGsn1dN0K0gRkqFNYq5wahQ7M";
  $Request = "http://209.85.175.147/maps/geo?q=26.2369,80.34986&output=xml&key=AIzaSyCrvySVHBo9ikF0Pt6JLUZKHPpGbazGGgc";
  //$Request = "http://209.85.129.104/maps/geo?q=26.2369,80.34986&output=xml&key=AIzaSyCrvySVHBo9ikF0Pt6JLUZKHPpGbazGGgc";
  //$Request = "http://maps.google.com/maps/geo?q=26.2369,80.34986&output=xml&key=AIzaSyC6rUKRZKFGsn1dN0K0gRkqFNYq5wahQ7M";
  //$Request = "http://maps.googleapis.com/maps/geo?q=26.2369,80.34986&output=xml&key=AIzaSyC6rUKRZKFGsn1dN0K0gRkqFNYq5wahQ7M";
  //$Request = "http://74.125.227.20/maps/geo?q=26.2369,80.34986&output=xml&key=AIzaSyC6rUKRZKFGsn1dN0K0gRkqFNYq5wahQ7M";

  echo $Request."<br>";
 	 
  $page = file_get_contents($Request);
  echo htmlspecialchars(file_get_contents($Request));
  
  //echo "<br>page=".$page;
	$xml = new SimpleXMLElement($page);
  //echo "<br>xml=".$xml;		
	$size = sizeof( $xml->Response->Placemark);
	echo "<br>Result size=".$size;
?>
