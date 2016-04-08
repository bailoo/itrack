<?php

function check_with_range($lat, $lng, $geo_coord, &$status_geo)
{	
	//echo "<br>geoc=".$geo_coord."<br>";	
	//echo "<BR>In check with range- lat=".$lat. " lng=".$lng." $geo_coord=".$geo_coord;
	$pointLocation = new pointLocation();

	//echo "<br>coord=".$coordinates."<br><br>";
	$coord = explode(" ",$geo_coord);
	
	/*echo "<br>coord=";
	for($i=0;$i<sizeof($coord);$i++)
	{
		echo "<br>".$coord[$i];
	}*/

	$r = 6378;
	
	//////////// NEW CODE ////////////////////////////
	//echo "<br>passing point=".$lat.",".$lng."<br><br>";
	
	////SINGLE POINT
	//echo "lat=".$lat." lng=".$lng."<br>";
	$lngA = $r * ( cos((pi()/180)*$lat) * cos((pi()/180)*$lng) );
	$latA = $r * ( cos((pi()/180)*$lat) * sin((pi()/180)*$lng) );
	
	
	$pt = $latA." ".$lngA;
	//echo "<br>SINGLE PT=".$pt;
	$points = array($pt);	
	//echo "<br>points=".$points."<br>";
	//print_r($points);
	//echo "<br><br>";
	//////////////////////////////////////////////////

	// GET POLYGON COORDINATE SIZE
	$coord_size = sizeof($coord);
	//echo "<br>coordsize=".$coord_size;
	
	//echo "<BR><BR>COORD SIZE=".$coord_size."<BR><BR><BR>";
	
	for($i=0;$i<$coord_size;$i++)
	{
		$c = explode(",",$coord[$i]);
		$lat = $c[0];
		$long = $c[1];
		
		$lng = $r * ( cos((pi()/180)*(float)$lat) * cos((pi()/180)*(float)$long) );
		$lt = $r * ( cos((pi()/180)*(float)$lat) * sin((pi()/180)*(float)$long) );
		
		$coord1 = $lt." ".$lng;
		//print_r($c);
		//echo"<BR>coord=".$coord[$i]." i=".$i." lat=".$lat." long=".$long;
		//echo"<BR>coord1=".$coord1[$i]." i=".$i;
		$polygon[] = $coord1;
	}
	//echo"<BR>";
	//print_r($polygon);
	foreach($points as $key => $point) {
		//echo "point=".$point."<br>";
		//echo "$key ($point) is " . $pointLocation->pointInPolygon($point, $polygon) . "<br>";
		//echo "<br>point=".$point." polygon=".sizeof($polygon);
		//echo "<br>point=".$point." polygon=".sizeof($polygon);
		$found = $pointLocation->pointInPolygon($point, $polygon);
		//echo "<br>Found=".$found;
		 if($found=="inside")
			$status_geo = true;
		 else
			$status_geo = false;	
	}
	//echo "<BR>VALID=".$status_geo;
}	

?>