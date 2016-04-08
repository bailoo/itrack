<?php

function check_with_range($lat, $lng, $m_serial, $coordinates, &$status)
{
	//echo "<BR>In check with range- lat=".$lat. " lng=".$lng." coord=".$coordinates;
	$pointLocation = new pointLocation();

	//echo "<br>coord=".$coordinates."<br><br>";
	$coord = explode(",",$coordinates);
	
	/*echo "<br>coord=";
	for($i=0;$i<sizeof($coord);$i++)
	{
		echo "<br>".$coord[$i];
	}*/

	$r = 6378;
	
	////SINGLE POINT 
	//$lngA = $r * ( cos((pi()/180)*$lat) * cos((pi()/180)*$lng) );
	//$ltA = $r * ( cos((pi()/180)*$lat) * sin((pi()/180)*$lng) );
	
	$lngA = $lng;
	$ltA = $lat;
	
	$pt = $ltA." ".$lngA;
	//echo "SINGLE PT=".$pt;
	$points = array($pt);	
	//////////////////////////////////////////////////

	// GET POLYGON COORDINATE SIZE
	$coord_size = sizeof($coord);	
	//echo "<BR><BR>COORD SIZE=".$coord_size."<BR><BR><BR>";
	$total_coord = array();
  
  for($i=0;$i<$coord_size;$i++)
  {
     $total_coord[] = $coord[$i];      
	}
	//echo $total_coord."<br>";

  $polygon = $total_coord;

	foreach($points as $key => $point) {
		//echo "$key ($point) is " . $pointLocation->pointInPolygon($point, $polygon) . "<br>";
		$found = $pointLocation->pointInPolygon($point, $polygon);
		 if($found=="inside")
			$status = true;
		 else
			$status = false;	
	}
	//echo "<BR>VALID=".$status;
}	

?>