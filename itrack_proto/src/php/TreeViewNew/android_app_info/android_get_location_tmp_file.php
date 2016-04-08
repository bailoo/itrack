<?php
	$point_1=$_GET['point_test'];
	$point_1=substr($point_1,0,-1);
	$point_1=substr($point_1,1,-1);
	$point_1=explode(",",$point_1);
	$geoCodeURL = "http://nominatim.openstreetmap.org/reverse?format=xml&lat=".$point_1[0]."&lon=".$point_1[1]."&zoom=18&addressdetails=1";
	$xml=@simplexml_load_file($geoCodeURL);	
	foreach($xml->result[0]->attributes() as $a => $b) 
	{
		if($a=="lat")
		{
			$lat=$b;
		}
		else if($a=="lon")
		{
			$lon=$b;
		}
	}	
	 round(floatval($lat_local),4);
	echo $xml->result.":".$lat.":".$lon;
?>
