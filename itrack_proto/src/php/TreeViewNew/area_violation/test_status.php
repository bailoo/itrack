<?php

include_once("pointLocation.php");

//polygon

$geo_coord = "26.502684520711796,80.2547836303711 26.50422073120105,80.3042221069336 26.470419357288968,80.37700653076172 26.41324171518935,80.39588928222656 26.395098795423742,80.33100128173828 26.44521550725826,80.25066375732422";

//points

//////////  OUT ///////

//$lat =" 26.441526676222832";
//$lng ="80.24139404296875";

//$lat ="26.395713856356135";
//$lng ="80.28053283691406";

//$lat ="26.39386866372847";
//$lng ="80.3982925415039";

//$lat ="26.514973629473804";
//$lng ="80.2276611328125";

//$lat ="26.476873114207926";
//$lng ="80.21392822265625";


///////// IN  /////////
//$lat ="26.47472190212698";
//$lng ="80.28671264648438";

//$lat ="26.436915471334792";
//$lng ="80.33374786376953";

//$lat ="26.432918944502042";
//$lng ="80.29632568359375";

//$lat ="26.472570649819907";
//$lng ="80.3543472290039";

//$lat ="26.463657890351797";
//$lng ="80.30799865722656";

//$lat ="26.48455568608595";
//$lng ="80.25993347167969";

//$lat ="26.420928532545656";
//$lng ="80.38215637207031";

//$geo_coord = "27.556981920338316,79.43115234375 26.22444694563432,79.51904296875 25.82956108605351,80.33203125 26.755420897359123,81.5625 27.029770731463536,80.68359375 27.44004046509707,79.78271484375";

check_with_range($lat, $lng, $geo_coord, &$status);
echo "<br>status=".$status;


function check_with_range($lat, $lng, $geo_coord, &$status)
{
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

	////SINGLE POINT 
	$lngA = $r * ( cos((pi()/180)*$lat) * cos((pi()/180)*$lng) );
	$ltA = $r * ( cos((pi()/180)*$lat) * sin((pi()/180)*$lng) );
	//$pt = $ltA." ".$lngA;
	echo "<br>lt=".$lat." lng=".$lng;
	
	$pt = $ltA." ".$lngA;
	echo "SINGLE PT=".$pt;
	$points = array($pt);	
	//echo "<br>pointssssssssssssss=".$points;
	//////////////////////////////////////////////////

	// GET POLYGON COORDINATE SIZE
	$coord_size = sizeof($coord);
	echo "<br>coordsize=".$coord_size;
	
	//echo "<BR><BR>COORD SIZE=".$coord_size."<BR><BR><BR>";
	
	if($coord_size == 4)
	{	
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;

		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;

		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3);

		// NEW CODE CLOSED	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3]);
	}
	else if($coord_size == 5)
	{
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];

		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];
		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );
		
		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;

		//echo "<br>5.COORD=".$coord0." : ".$coord1. " : ".$coord2. " : ".$coord3. " : ".$coord4."<br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4);

		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4]);
	}
	else if($coord_size == 6)
	{
		$c0 = explode(",",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];

		$c1 = explode(",",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(",",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(",",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(",",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];
		
		$c5 = explode(",",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];		
		
		echo "<br>lat0=".$lat0;
		echo "<br>lng0=".$long0;
		
		echo "<br>lat1=".$lat1;
		echo "<br>lng1=".$long1;
		
		echo "<br>lat2=".$lat2;
		echo "<br>lng2=".$long2;
		
		echo "<br>lat3=".$lat3;
		echo "<br>lng3=".$long3;
		
		echo "<br>lat4=".$lat4;
		echo "<br>lng4=".$long4;
		
		echo "<br>lat5=".$lat5;
		echo "<br>lng5=".$long5;
			
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );
		
		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );
		
				
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;

		echo "<br>6.COORD=".$coord0." : ".$coord1. " : ".$coord2. " : ".$coord3. " : ".$coord4." : ".$coord5."<br>"; 
		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5);
		
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5]);
	}
	else if($coord_size == 7)
	{
		$c0 = explode(",",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];

		$c1 = explode(",",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(",",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(",",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(",",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];
		
		$c5 = explode(",",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];		
		
		$c6 = explode(",",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];	
		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );
		
		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );
		
		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

			
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;

		//echo "<br>6.COORD=".$coord0." : ".$coord1. " : ".$coord2. " : ".$coord3. " : ".$coord4." : ".$coord5. " : ".$coord6."<br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6);	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6]);
	}
	else if($coord_size == 8)
	{
	
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];

		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];
		
		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];		
		
		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];	
		
		$c7 = explode(" ",$coord[7]);
		$lat7 = $c7[0];
		$long7 = $c7[1];	
		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );
		
		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );
		
		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );
		
		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

				
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;

		//echo "<br>6.COORD=".$coord0." : ".$coord1. " : ".$coord2. " : ".$coord3. " : ".$coord4." : ".$coord5."<br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7);		
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7]);
	}
	else if($coord_size == 9)
	{
	
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];

		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];
		
		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];		
		
		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];	
		
		$c7 = explode(" ",$coord[7]);
		$lat7 = $c7[0];
		$long7 = $c7[1];	
		
		$c8 = explode(" ",$coord[8]);
		$lat8 = $c8[0];
		$long8 = $c8[1];			
		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );
		
		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );
		
		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );
		
		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );
				
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;

		//echo "<br>6.COORD=".$coord0." : ".$coord1. " : ".$coord2. " : ".$coord3. " : ".$coord4." : ".$coord5."<br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8);		
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8]);
	}
	else if($coord_size == 10)
	{
	
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];

		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];
		
		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];		
		
		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];	
		
		$c7 = explode(" ",$coord[7]);
		$lat7 = $c7[0];
		$long7 = $c7[1];	
		
		$c8 = explode(" ",$coord[8]);
		$lat8 = $c8[0];
		$long8 = $c8[1];			
		
		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];			
		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );
		
		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );
		
		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );
		
		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );
		
		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );		
				
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;

		//echo "<br>6.COORD=".$coord0." : ".$coord1. " : ".$coord2. " : ".$coord3. " : ".$coord4." : ".$coord5."<br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9);		
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9]);
	}
	
	else if($coord_size == 11)
	{
	
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;

		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10);	
	
	
	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10]);
	}
	
	else if($coord_size == 12)
	{
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(" ",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$lng11 = $r * ( cos((pi()/180)*(float)$lat11) * cos((pi()/180)*(float)$long11) );
		$lt11 = $r * ( cos((pi()/180)*(float)$lat11) * sin((pi()/180)*(float)$long11) );		
		
		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;
		$coord11 = $lt11." ".$lng11;

		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11);		
	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10],$coord[11]);
	}
	else if($coord_size == 13)
	{	
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(" ",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		$c12 = explode(" ",$coord[12]);
		$lat12 = $c12[0];
		$long12 = $c12[1];		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$lng11 = $r * ( cos((pi()/180)*(float)$lat11) * cos((pi()/180)*(float)$long11) );
		$lt11 = $r * ( cos((pi()/180)*(float)$lat11) * sin((pi()/180)*(float)$long11) );		
		
		$lng12 = $r * ( cos((pi()/180)*(float)$lat12) * cos((pi()/180)*(float)$long12) );
		$lt12 = $r * ( cos((pi()/180)*(float)$lat12) * sin((pi()/180)*(float)$long12) );		

		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;
		$coord11 = $lt11." ".$lng11;
		$coord12 = $lt12." ".$lng12;

		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12);		
	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10],$coord[11],$coord[12]);
	}
	else if($coord_size == 14)
	{
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(" ",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		$c12 = explode(" ",$coord[12]);
		$lat12 = $c12[0];
		$long12 = $c12[1];		
		
		$c13 = explode(" ",$coord[13]);
		$lat13 = $c13[0];
		$long13 = $c13[1];				
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$lng11 = $r * ( cos((pi()/180)*(float)$lat11) * cos((pi()/180)*(float)$long11) );
		$lt11 = $r * ( cos((pi()/180)*(float)$lat11) * sin((pi()/180)*(float)$long11) );		
		
		$lng12 = $r * ( cos((pi()/180)*(float)$lat12) * cos((pi()/180)*(float)$long12) );
		$lt12 = $r * ( cos((pi()/180)*(float)$lat12) * sin((pi()/180)*(float)$long12) );		
		
		$lng13 = $r * ( cos((pi()/180)*(float)$lat13) * cos((pi()/180)*(float)$long13) );
		$lt13 = $r * ( cos((pi()/180)*(float)$lat13) * sin((pi()/180)*(float)$long13) );				

		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;
		$coord11 = $lt11." ".$lng11;
		$coord12 = $lt12." ".$lng12;
		$coord13 = $lt13." ".$lng13;

		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12,$coord13);		
	
	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10],$coord[11],$coord[12],$coord[13]);
	}
	else if($coord_size == 15)
	{
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(" ",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		$c12 = explode(" ",$coord[12]);
		$lat12 = $c12[0];
		$long12 = $c12[1];		
		
		$c13 = explode(" ",$coord[13]);
		$lat13 = $c13[0];
		$long13 = $c13[1];		

		$c14 = explode(" ",$coord[14]);
		$lat14 = $c14[0];
		$long14 = $c14[1];				
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$lng11 = $r * ( cos((pi()/180)*(float)$lat11) * cos((pi()/180)*(float)$long11) );
		$lt11 = $r * ( cos((pi()/180)*(float)$lat11) * sin((pi()/180)*(float)$long11) );		
		
		$lng12 = $r * ( cos((pi()/180)*(float)$lat12) * cos((pi()/180)*(float)$long12) );
		$lt12 = $r * ( cos((pi()/180)*(float)$lat12) * sin((pi()/180)*(float)$long12) );		
		
		$lng13 = $r * ( cos((pi()/180)*(float)$lat13) * cos((pi()/180)*(float)$long13) );
		$lt13 = $r * ( cos((pi()/180)*(float)$lat13) * sin((pi()/180)*(float)$long13) );				

		$lng14 = $r * ( cos((pi()/180)*(float)$lat14) * cos((pi()/180)*(float)$long14) );
		$lt14 = $r * ( cos((pi()/180)*(float)$lat14) * sin((pi()/180)*(float)$long14) );				

		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;
		$coord11 = $lt11." ".$lng11;
		$coord12 = $lt12." ".$lng12;
		$coord13 = $lt13." ".$lng13;
		$coord14 = $lt14." ".$lng14;

		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12,$coord13,$coord14);			
	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10],$coord[11],$coord[12],$coord[13],$coord[14]);
	}
	else if($coord_size == 16)
	{
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(" ",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		$c12 = explode(" ",$coord[12]);
		$lat12 = $c12[0];
		$long12 = $c12[1];		
		
		$c13 = explode(" ",$coord[13]);
		$lat13 = $c13[0];
		$long13 = $c13[1];		

		$c14 = explode(" ",$coord[14]);
		$lat14 = $c14[0];
		$long14 = $c14[1];	

		$c15 = explode(" ",$coord[15]);
		$lat15 = $c15[0];
		$long15 = $c15[1];				
		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$lng11 = $r * ( cos((pi()/180)*(float)$lat11) * cos((pi()/180)*(float)$long11) );
		$lt11 = $r * ( cos((pi()/180)*(float)$lat11) * sin((pi()/180)*(float)$long11) );		
		
		$lng12 = $r * ( cos((pi()/180)*(float)$lat12) * cos((pi()/180)*(float)$long12) );
		$lt12 = $r * ( cos((pi()/180)*(float)$lat12) * sin((pi()/180)*(float)$long12) );		
		
		$lng13 = $r * ( cos((pi()/180)*(float)$lat13) * cos((pi()/180)*(float)$long13) );
		$lt13 = $r * ( cos((pi()/180)*(float)$lat13) * sin((pi()/180)*(float)$long13) );				

		$lng14 = $r * ( cos((pi()/180)*(float)$lat14) * cos((pi()/180)*(float)$long14) );
		$lt14 = $r * ( cos((pi()/180)*(float)$lat14) * sin((pi()/180)*(float)$long14) );				

		$lng15 = $r * ( cos((pi()/180)*(float)$lat15) * cos((pi()/180)*(float)$long15) );
		$lt15 = $r * ( cos((pi()/180)*(float)$lat15) * sin((pi()/180)*(float)$long15) );				

		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;
		$coord11 = $lt11." ".$lng11;
		$coord12 = $lt12." ".$lng12;
		$coord13 = $lt13." ".$lng13;
		$coord14 = $lt14." ".$lng14;
		$coord15 = $lt15." ".$lng15;

		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12,$coord13,$coord14,$coord15);			
	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10],$coord[11],$coord[12],$coord[13],$coord[14],$coord[15]);
	}
	else if($coord_size == 17)
	{
	
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(" ",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		$c12 = explode(" ",$coord[12]);
		$lat12 = $c12[0];
		$long12 = $c12[1];		
		
		$c13 = explode(" ",$coord[13]);
		$lat13 = $c13[0];
		$long13 = $c13[1];		

		$c14 = explode(" ",$coord[14]);
		$lat14 = $c14[0];
		$long14 = $c14[1];	

		$c15 = explode(" ",$coord[15]);
		$lat15 = $c15[0];
		$long15 = $c15[1];		

		$c16 = explode(" ",$coord[16]);
		$lat16 = $c16[0];
		$long16 = $c16[1];				
		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$lng11 = $r * ( cos((pi()/180)*(float)$lat11) * cos((pi()/180)*(float)$long11) );
		$lt11 = $r * ( cos((pi()/180)*(float)$lat11) * sin((pi()/180)*(float)$long11) );		
		
		$lng12 = $r * ( cos((pi()/180)*(float)$lat12) * cos((pi()/180)*(float)$long12) );
		$lt12 = $r * ( cos((pi()/180)*(float)$lat12) * sin((pi()/180)*(float)$long12) );		
		
		$lng13 = $r * ( cos((pi()/180)*(float)$lat13) * cos((pi()/180)*(float)$long13) );
		$lt13 = $r * ( cos((pi()/180)*(float)$lat13) * sin((pi()/180)*(float)$long13) );				

		$lng14 = $r * ( cos((pi()/180)*(float)$lat14) * cos((pi()/180)*(float)$long14) );
		$lt14 = $r * ( cos((pi()/180)*(float)$lat14) * sin((pi()/180)*(float)$long14) );				

		$lng15 = $r * ( cos((pi()/180)*(float)$lat15) * cos((pi()/180)*(float)$long15) );
		$lt15 = $r * ( cos((pi()/180)*(float)$lat15) * sin((pi()/180)*(float)$long15) );				

		$lng16 = $r * ( cos((pi()/180)*(float)$lat16) * cos((pi()/180)*(float)$long16) );
		$lt16 = $r * ( cos((pi()/180)*(float)$lat16) * sin((pi()/180)*(float)$long16) );				

		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;
		$coord11 = $lt11." ".$lng11;
		$coord12 = $lt12." ".$lng12;
		$coord13 = $lt13." ".$lng13;
		$coord14 = $lt14." ".$lng14;
		$coord15 = $lt15." ".$lng15;
		$coord16 = $lt16." ".$lng16;


		echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12,$coord13,$coord14,$coord15,$coord16);			
	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10],$coord[11],$coord[12],$coord[13],$coord[14],$coord[15],$coord[16]);
	}
	else if($coord_size == 18)
	{
	
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(" ",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		$c12 = explode(" ",$coord[12]);
		$lat12 = $c12[0];
		$long12 = $c12[1];		
		
		$c13 = explode(" ",$coord[13]);
		$lat13 = $c13[0];
		$long13 = $c13[1];		

		$c14 = explode(" ",$coord[14]);
		$lat14 = $c14[0];
		$long14 = $c14[1];	

		$c15 = explode(" ",$coord[15]);
		$lat15 = $c15[0];
		$long15 = $c15[1];		

		$c16 = explode(" ",$coord[16]);
		$lat16 = $c16[0];
		$long16 = $c16[1];		

		$c17 = explode(" ",$coord[17]);
		$lat17 = $c17[0];
		$long17 = $c17[1];			
		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$lng11 = $r * ( cos((pi()/180)*(float)$lat11) * cos((pi()/180)*(float)$long11) );
		$lt11 = $r * ( cos((pi()/180)*(float)$lat11) * sin((pi()/180)*(float)$long11) );		
		
		$lng12 = $r * ( cos((pi()/180)*(float)$lat12) * cos((pi()/180)*(float)$long12) );
		$lt12 = $r * ( cos((pi()/180)*(float)$lat12) * sin((pi()/180)*(float)$long12) );		
		
		$lng13 = $r * ( cos((pi()/180)*(float)$lat13) * cos((pi()/180)*(float)$long13) );
		$lt13 = $r * ( cos((pi()/180)*(float)$lat13) * sin((pi()/180)*(float)$long13) );				

		$lng14 = $r * ( cos((pi()/180)*(float)$lat14) * cos((pi()/180)*(float)$long14) );
		$lt14 = $r * ( cos((pi()/180)*(float)$lat14) * sin((pi()/180)*(float)$long14) );				

		$lng15 = $r * ( cos((pi()/180)*(float)$lat15) * cos((pi()/180)*(float)$long15) );
		$lt15 = $r * ( cos((pi()/180)*(float)$lat15) * sin((pi()/180)*(float)$long15) );				

		$lng16 = $r * ( cos((pi()/180)*(float)$lat16) * cos((pi()/180)*(float)$long16) );
		$lt16 = $r * ( cos((pi()/180)*(float)$lat16) * sin((pi()/180)*(float)$long16) );				

		$lng17 = $r * ( cos((pi()/180)*(float)$lat17) * cos((pi()/180)*(float)$long17) );
		$lt17 = $r * ( cos((pi()/180)*(float)$lat17) * sin((pi()/180)*(float)$long17) );				

		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;
		$coord11 = $lt11." ".$lng11;
		$coord12 = $lt12." ".$lng12;
		$coord13 = $lt13." ".$lng13;
		$coord14 = $lt14." ".$lng14;
		$coord15 = $lt15." ".$lng15;
		$coord16 = $lt16." ".$lng16;
		$coord17 = $lt17." ".$lng17;


		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12,$coord13,$coord14,$coord15,$coord16,$coord17);			
	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10],$coord[11],$coord[12],$coord[13],$coord[14],$coord[15],$coord[16],$coord[17]);
	}
	else if($coord_size == 19)
	{

		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(" ",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		$c12 = explode(" ",$coord[12]);
		$lat12 = $c12[0];
		$long12 = $c12[1];		
		
		$c13 = explode(" ",$coord[13]);
		$lat13 = $c13[0];
		$long13 = $c13[1];		

		$c14 = explode(" ",$coord[14]);
		$lat14 = $c14[0];
		$long14 = $c14[1];	

		$c15 = explode(" ",$coord[15]);
		$lat15 = $c15[0];
		$long15 = $c15[1];		

		$c16 = explode(" ",$coord[16]);
		$lat16 = $c16[0];
		$long16 = $c16[1];		

		$c17 = explode(" ",$coord[17]);
		$lat17 = $c17[0];
		$long17 = $c17[1];		

		$c18 = explode(" ",$coord[18]);
		$lat18 = $c18[0];
		$long18 = $c18[1];				
		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$lng11 = $r * ( cos((pi()/180)*(float)$lat11) * cos((pi()/180)*(float)$long11) );
		$lt11 = $r * ( cos((pi()/180)*(float)$lat11) * sin((pi()/180)*(float)$long11) );		
		
		$lng12 = $r * ( cos((pi()/180)*(float)$lat12) * cos((pi()/180)*(float)$long12) );
		$lt12 = $r * ( cos((pi()/180)*(float)$lat12) * sin((pi()/180)*(float)$long12) );		
		
		$lng13 = $r * ( cos((pi()/180)*(float)$lat13) * cos((pi()/180)*(float)$long13) );
		$lt13 = $r * ( cos((pi()/180)*(float)$lat13) * sin((pi()/180)*(float)$long13) );				

		$lng14 = $r * ( cos((pi()/180)*(float)$lat14) * cos((pi()/180)*(float)$long14) );
		$lt14 = $r * ( cos((pi()/180)*(float)$lat14) * sin((pi()/180)*(float)$long14) );				

		$lng15 = $r * ( cos((pi()/180)*(float)$lat15) * cos((pi()/180)*(float)$long15) );
		$lt15 = $r * ( cos((pi()/180)*(float)$lat15) * sin((pi()/180)*(float)$long15) );				

		$lng16 = $r * ( cos((pi()/180)*(float)$lat16) * cos((pi()/180)*(float)$long16) );
		$lt16 = $r * ( cos((pi()/180)*(float)$lat16) * sin((pi()/180)*(float)$long16) );				

		$lng17 = $r * ( cos((pi()/180)*(float)$lat17) * cos((pi()/180)*(float)$long17) );
		$lt17 = $r * ( cos((pi()/180)*(float)$lat17) * sin((pi()/180)*(float)$long17) );				

		$lng18 = $r * ( cos((pi()/180)*(float)$lat18) * cos((pi()/180)*(float)$long18) );
		$lt18 = $r * ( cos((pi()/180)*(float)$lat18) * sin((pi()/180)*(float)$long18) );				

		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;
		$coord11 = $lt11." ".$lng11;
		$coord12 = $lt12." ".$lng12;
		$coord13 = $lt13." ".$lng13;
		$coord14 = $lt14." ".$lng14;
		$coord15 = $lt15." ".$lng15;
		$coord16 = $lt16." ".$lng16;
		$coord17 = $lt17." ".$lng17;
		$coord18 = $lt18." ".$lng18;

		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12,$coord13,$coord14,$coord15,$coord16,$coord17,$coord18);			
	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10],$coord[11],$coord[12],$coord[13],$coord[14],$coord[15],$coord[16],$coord[17],$coord[18]);
	}
	else if($coord_size == 20)
	{
	
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(" ",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		$c12 = explode(" ",$coord[12]);
		$lat12 = $c12[0];
		$long12 = $c12[1];		
		
		$c13 = explode(" ",$coord[13]);
		$lat13 = $c13[0];
		$long13 = $c13[1];		

		$c14 = explode(" ",$coord[14]);
		$lat14 = $c14[0];
		$long14 = $c14[1];	

		$c15 = explode(" ",$coord[15]);
		$lat15 = $c15[0];
		$long15 = $c15[1];		

		$c16 = explode(" ",$coord[16]);
		$lat16 = $c16[0];
		$long16 = $c16[1];		

		$c17 = explode(" ",$coord[17]);
		$lat17 = $c17[0];
		$long17 = $c17[1];		

		$c18 = explode(" ",$coord[18]);
		$lat18 = $c18[0];
		$long18 = $c18[1];		

		$c19 = explode(" ",$coord[19]);
		$lat19 = $c19[0];
		$long19 = $c19[1];				
		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$lng11 = $r * ( cos((pi()/180)*(float)$lat11) * cos((pi()/180)*(float)$long11) );
		$lt11 = $r * ( cos((pi()/180)*(float)$lat11) * sin((pi()/180)*(float)$long11) );		
		
		$lng12 = $r * ( cos((pi()/180)*(float)$lat12) * cos((pi()/180)*(float)$long12) );
		$lt12 = $r * ( cos((pi()/180)*(float)$lat12) * sin((pi()/180)*(float)$long12) );		
		
		$lng13 = $r * ( cos((pi()/180)*(float)$lat13) * cos((pi()/180)*(float)$long13) );
		$lt13 = $r * ( cos((pi()/180)*(float)$lat13) * sin((pi()/180)*(float)$long13) );				

		$lng14 = $r * ( cos((pi()/180)*(float)$lat14) * cos((pi()/180)*(float)$long14) );
		$lt14 = $r * ( cos((pi()/180)*(float)$lat14) * sin((pi()/180)*(float)$long14) );				

		$lng15 = $r * ( cos((pi()/180)*(float)$lat15) * cos((pi()/180)*(float)$long15) );
		$lt15 = $r * ( cos((pi()/180)*(float)$lat15) * sin((pi()/180)*(float)$long15) );				

		$lng16 = $r * ( cos((pi()/180)*(float)$lat16) * cos((pi()/180)*(float)$long16) );
		$lt16 = $r * ( cos((pi()/180)*(float)$lat16) * sin((pi()/180)*(float)$long16) );				

		$lng17 = $r * ( cos((pi()/180)*(float)$lat17) * cos((pi()/180)*(float)$long17) );
		$lt17 = $r * ( cos((pi()/180)*(float)$lat17) * sin((pi()/180)*(float)$long17) );				

		$lng18 = $r * ( cos((pi()/180)*(float)$lat18) * cos((pi()/180)*(float)$long18) );
		$lt18 = $r * ( cos((pi()/180)*(float)$lat18) * sin((pi()/180)*(float)$long18) );				

		$lng19 = $r * ( cos((pi()/180)*(float)$lat19) * cos((pi()/180)*(float)$long19) );
		$lt19 = $r * ( cos((pi()/180)*(float)$lat19) * sin((pi()/180)*(float)$long19) );				

		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;
		$coord11 = $lt11." ".$lng11;
		$coord12 = $lt12." ".$lng12;
		$coord13 = $lt13." ".$lng13;
		$coord14 = $lt14." ".$lng14;
		$coord15 = $lt15." ".$lng15;
		$coord16 = $lt16." ".$lng16;
		$coord17 = $lt17." ".$lng17;
		$coord18 = $lt18." ".$lng18;
		$coord19 = $lt19." ".$lng19;

		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12,$coord13,$coord14,$coord15,$coord16,$coord17,$coord18,$coord19);			
	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10],$coord[11],$coord[12],$coord[13],$coord[14],$coord[15],$coord[16],$coord[17],$coord[18],$coord[19]);
	}
	else if($coord_size == 21)
	{
	
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(" ",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		$c12 = explode(" ",$coord[12]);
		$lat12 = $c12[0];
		$long12 = $c12[1];		
		
		$c13 = explode(" ",$coord[13]);
		$lat13 = $c13[0];
		$long13 = $c13[1];		

		$c14 = explode(" ",$coord[14]);
		$lat14 = $c14[0];
		$long14 = $c14[1];	

		$c15 = explode(" ",$coord[15]);
		$lat15 = $c15[0];
		$long15 = $c15[1];		

		$c16 = explode(" ",$coord[16]);
		$lat16 = $c16[0];
		$long16 = $c16[1];		

		$c17 = explode(" ",$coord[17]);
		$lat17 = $c17[0];
		$long17 = $c17[1];		

		$c18 = explode(" ",$coord[18]);
		$lat18 = $c18[0];
		$long18 = $c18[1];		

		$c19 = explode(" ",$coord[19]);
		$lat19 = $c19[0];
		$long19 = $c19[1];	

		$c20 = explode(" ",$coord[20]);
		$lat20 = $c20[0];
		$long20 = $c20[1];			
		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$lng11 = $r * ( cos((pi()/180)*(float)$lat11) * cos((pi()/180)*(float)$long11) );
		$lt11 = $r * ( cos((pi()/180)*(float)$lat11) * sin((pi()/180)*(float)$long11) );		
		
		$lng12 = $r * ( cos((pi()/180)*(float)$lat12) * cos((pi()/180)*(float)$long12) );
		$lt12 = $r * ( cos((pi()/180)*(float)$lat12) * sin((pi()/180)*(float)$long12) );		
		
		$lng13 = $r * ( cos((pi()/180)*(float)$lat13) * cos((pi()/180)*(float)$long13) );
		$lt13 = $r * ( cos((pi()/180)*(float)$lat13) * sin((pi()/180)*(float)$long13) );				

		$lng14 = $r * ( cos((pi()/180)*(float)$lat14) * cos((pi()/180)*(float)$long14) );
		$lt14 = $r * ( cos((pi()/180)*(float)$lat14) * sin((pi()/180)*(float)$long14) );				

		$lng15 = $r * ( cos((pi()/180)*(float)$lat15) * cos((pi()/180)*(float)$long15) );
		$lt15 = $r * ( cos((pi()/180)*(float)$lat15) * sin((pi()/180)*(float)$long15) );				

		$lng16 = $r * ( cos((pi()/180)*(float)$lat16) * cos((pi()/180)*(float)$long16) );
		$lt16 = $r * ( cos((pi()/180)*(float)$lat16) * sin((pi()/180)*(float)$long16) );				

		$lng17 = $r * ( cos((pi()/180)*(float)$lat17) * cos((pi()/180)*(float)$long17) );
		$lt17 = $r * ( cos((pi()/180)*(float)$lat17) * sin((pi()/180)*(float)$long17) );				

		$lng18 = $r * ( cos((pi()/180)*(float)$lat18) * cos((pi()/180)*(float)$long18) );
		$lt18 = $r * ( cos((pi()/180)*(float)$lat18) * sin((pi()/180)*(float)$long18) );				

		$lng19 = $r * ( cos((pi()/180)*(float)$lat19) * cos((pi()/180)*(float)$long19) );
		$lt19 = $r * ( cos((pi()/180)*(float)$lat19) * sin((pi()/180)*(float)$long19) );	

		$lng20 = $r * ( cos((pi()/180)*(float)$lat20) * cos((pi()/180)*(float)$long20) );
		$lt20 = $r * ( cos((pi()/180)*(float)$lat20) * sin((pi()/180)*(float)$long20) );				
		

		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;
		$coord11 = $lt11." ".$lng11;
		$coord12 = $lt12." ".$lng12;
		$coord13 = $lt13." ".$lng13;
		$coord14 = $lt14." ".$lng14;
		$coord15 = $lt15." ".$lng15;
		$coord16 = $lt16." ".$lng16;
		$coord17 = $lt17." ".$lng17;
		$coord18 = $lt18." ".$lng18;
		$coord19 = $lt19." ".$lng19;
		$coord20 = $lt20." ".$lng20;

		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12,$coord13,$coord14,$coord15,$coord16,$coord17,$coord18,$coord19,$coord20);			
	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10],$coord[11],$coord[12],$coord[13],$coord[14],$coord[15],$coord[16],$coord[17],$coord[18],$coord[19],$coord[20]);
	}
	else if($coord_size == 22)
	{
	
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(" ",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		$c12 = explode(" ",$coord[12]);
		$lat12 = $c12[0];
		$long12 = $c12[1];		
		
		$c13 = explode(" ",$coord[13]);
		$lat13 = $c13[0];
		$long13 = $c13[1];		

		$c14 = explode(" ",$coord[14]);
		$lat14 = $c14[0];
		$long14 = $c14[1];	

		$c15 = explode(" ",$coord[15]);
		$lat15 = $c15[0];
		$long15 = $c15[1];		

		$c16 = explode(" ",$coord[16]);
		$lat16 = $c16[0];
		$long16 = $c16[1];		

		$c17 = explode(" ",$coord[17]);
		$lat17 = $c17[0];
		$long17 = $c17[1];		

		$c18 = explode(" ",$coord[18]);
		$lat18 = $c18[0];
		$long18 = $c18[1];		

		$c19 = explode(" ",$coord[19]);
		$lat19 = $c19[0];
		$long19 = $c19[1];	

		$c20 = explode(" ",$coord[20]);
		$lat20 = $c20[0];
		$long20 = $c20[1];		

		$c21 = explode(" ",$coord[21]);
		$lat21 = $c21[0];
		$long21 = $c21[1];			
		
		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$lng11 = $r * ( cos((pi()/180)*(float)$lat11) * cos((pi()/180)*(float)$long11) );
		$lt11 = $r * ( cos((pi()/180)*(float)$lat11) * sin((pi()/180)*(float)$long11) );		
		
		$lng12 = $r * ( cos((pi()/180)*(float)$lat12) * cos((pi()/180)*(float)$long12) );
		$lt12 = $r * ( cos((pi()/180)*(float)$lat12) * sin((pi()/180)*(float)$long12) );		
		
		$lng13 = $r * ( cos((pi()/180)*(float)$lat13) * cos((pi()/180)*(float)$long13) );
		$lt13 = $r * ( cos((pi()/180)*(float)$lat13) * sin((pi()/180)*(float)$long13) );				

		$lng14 = $r * ( cos((pi()/180)*(float)$lat14) * cos((pi()/180)*(float)$long14) );
		$lt14 = $r * ( cos((pi()/180)*(float)$lat14) * sin((pi()/180)*(float)$long14) );				

		$lng15 = $r * ( cos((pi()/180)*(float)$lat15) * cos((pi()/180)*(float)$long15) );
		$lt15 = $r * ( cos((pi()/180)*(float)$lat15) * sin((pi()/180)*(float)$long15) );				

		$lng16 = $r * ( cos((pi()/180)*(float)$lat16) * cos((pi()/180)*(float)$long16) );
		$lt16 = $r * ( cos((pi()/180)*(float)$lat16) * sin((pi()/180)*(float)$long16) );				

		$lng17 = $r * ( cos((pi()/180)*(float)$lat17) * cos((pi()/180)*(float)$long17) );
		$lt17 = $r * ( cos((pi()/180)*(float)$lat17) * sin((pi()/180)*(float)$long17) );				

		$lng18 = $r * ( cos((pi()/180)*(float)$lat18) * cos((pi()/180)*(float)$long18) );
		$lt18 = $r * ( cos((pi()/180)*(float)$lat18) * sin((pi()/180)*(float)$long18) );				

		$lng19 = $r * ( cos((pi()/180)*(float)$lat19) * cos((pi()/180)*(float)$long19) );
		$lt19 = $r * ( cos((pi()/180)*(float)$lat19) * sin((pi()/180)*(float)$long19) );	

		$lng20 = $r * ( cos((pi()/180)*(float)$lat20) * cos((pi()/180)*(float)$long20) );
		$lt20 = $r * ( cos((pi()/180)*(float)$lat20) * sin((pi()/180)*(float)$long20) );				
		
		$lng21 = $r * ( cos((pi()/180)*(float)$lat21) * cos((pi()/180)*(float)$long21) );
		$lt21 = $r * ( cos((pi()/180)*(float)$lat21) * sin((pi()/180)*(float)$long21) );				

		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;
		$coord11 = $lt11." ".$lng11;
		$coord12 = $lt12." ".$lng12;
		$coord13 = $lt13." ".$lng13;
		$coord14 = $lt14." ".$lng14;
		$coord15 = $lt15." ".$lng15;
		$coord16 = $lt16." ".$lng16;
		$coord17 = $lt17." ".$lng17;
		$coord18 = $lt18." ".$lng18;
		$coord19 = $lt19." ".$lng19;
		$coord20 = $lt20." ".$lng20;
		$coord21 = $lt21." ".$lng21;

		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12,$coord13,$coord14,$coord15,$coord16,$coord17,$coord18,$coord19,$coord20,$coord21);			
	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10],$coord[11],$coord[12],$coord[13],$coord[14],$coord[15],$coord[16],$coord[17],$coord[18],$coord[19],$coord[20],$coord[21]);
	}
	else if($coord_size == 23)
	{
	
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(" ",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		$c12 = explode(" ",$coord[12]);
		$lat12 = $c12[0];
		$long12 = $c12[1];		
		
		$c13 = explode(" ",$coord[13]);
		$lat13 = $c13[0];
		$long13 = $c13[1];		

		$c14 = explode(" ",$coord[14]);
		$lat14 = $c14[0];
		$long14 = $c14[1];	

		$c15 = explode(" ",$coord[15]);
		$lat15 = $c15[0];
		$long15 = $c15[1];		

		$c16 = explode(" ",$coord[16]);
		$lat16 = $c16[0];
		$long16 = $c16[1];		

		$c17 = explode(" ",$coord[17]);
		$lat17 = $c17[0];
		$long17 = $c17[1];		

		$c18 = explode(" ",$coord[18]);
		$lat18 = $c18[0];
		$long18 = $c18[1];		

		$c19 = explode(" ",$coord[19]);
		$lat19 = $c19[0];
		$long19 = $c19[1];	

		$c20 = explode(" ",$coord[20]);
		$lat20 = $c20[0];
		$long20 = $c20[1];		

		$c21 = explode(" ",$coord[21]);
		$lat21 = $c21[0];
		$long21 = $c21[1];		

		$c22 = explode(" ",$coord[22]);
		$lat22 = $c22[0];
		$long22 = $c22[1];			
		
		
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$lng11 = $r * ( cos((pi()/180)*(float)$lat11) * cos((pi()/180)*(float)$long11) );
		$lt11 = $r * ( cos((pi()/180)*(float)$lat11) * sin((pi()/180)*(float)$long11) );		
		
		$lng12 = $r * ( cos((pi()/180)*(float)$lat12) * cos((pi()/180)*(float)$long12) );
		$lt12 = $r * ( cos((pi()/180)*(float)$lat12) * sin((pi()/180)*(float)$long12) );		
		
		$lng13 = $r * ( cos((pi()/180)*(float)$lat13) * cos((pi()/180)*(float)$long13) );
		$lt13 = $r * ( cos((pi()/180)*(float)$lat13) * sin((pi()/180)*(float)$long13) );				

		$lng14 = $r * ( cos((pi()/180)*(float)$lat14) * cos((pi()/180)*(float)$long14) );
		$lt14 = $r * ( cos((pi()/180)*(float)$lat14) * sin((pi()/180)*(float)$long14) );				

		$lng15 = $r * ( cos((pi()/180)*(float)$lat15) * cos((pi()/180)*(float)$long15) );
		$lt15 = $r * ( cos((pi()/180)*(float)$lat15) * sin((pi()/180)*(float)$long15) );				

		$lng16 = $r * ( cos((pi()/180)*(float)$lat16) * cos((pi()/180)*(float)$long16) );
		$lt16 = $r * ( cos((pi()/180)*(float)$lat16) * sin((pi()/180)*(float)$long16) );				

		$lng17 = $r * ( cos((pi()/180)*(float)$lat17) * cos((pi()/180)*(float)$long17) );
		$lt17 = $r * ( cos((pi()/180)*(float)$lat17) * sin((pi()/180)*(float)$long17) );				

		$lng18 = $r * ( cos((pi()/180)*(float)$lat18) * cos((pi()/180)*(float)$long18) );
		$lt18 = $r * ( cos((pi()/180)*(float)$lat18) * sin((pi()/180)*(float)$long18) );				

		$lng19 = $r * ( cos((pi()/180)*(float)$lat19) * cos((pi()/180)*(float)$long19) );
		$lt19 = $r * ( cos((pi()/180)*(float)$lat19) * sin((pi()/180)*(float)$long19) );	

		$lng20 = $r * ( cos((pi()/180)*(float)$lat20) * cos((pi()/180)*(float)$long20) );
		$lt20 = $r * ( cos((pi()/180)*(float)$lat20) * sin((pi()/180)*(float)$long20) );				
		
		$lng21 = $r * ( cos((pi()/180)*(float)$lat21) * cos((pi()/180)*(float)$long21) );
		$lt21 = $r * ( cos((pi()/180)*(float)$lat21) * sin((pi()/180)*(float)$long21) );	

		$lng22 = $r * ( cos((pi()/180)*(float)$lat22) * cos((pi()/180)*(float)$long22) );
		$lt22 = $r * ( cos((pi()/180)*(float)$lat22) * sin((pi()/180)*(float)$long22) );			

		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;
		$coord11 = $lt11." ".$lng11;
		$coord12 = $lt12." ".$lng12;
		$coord13 = $lt13." ".$lng13;
		$coord14 = $lt14." ".$lng14;
		$coord15 = $lt15." ".$lng15;
		$coord16 = $lt16." ".$lng16;
		$coord17 = $lt17." ".$lng17;
		$coord18 = $lt18." ".$lng18;
		$coord19 = $lt19." ".$lng19;
		$coord20 = $lt20." ".$lng20;
		$coord21 = $lt21." ".$lng21;
		$coord22 = $lt22." ".$lng22;

		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12,$coord13,$coord14,$coord15,$coord16,$coord17,$coord18,$coord19,$coord20,$coord21,$coord22);			
	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10],$coord[11],$coord[12],$coord[13],$coord[14],$coord[15],$coord[16],$coord[17],$coord[18],$coord[19],$coord[20],$coord[21],$coord[22]);
	}
	else if($coord_size == 24)
	{
	
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(" ",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		$c12 = explode(" ",$coord[12]);
		$lat12 = $c12[0];
		$long12 = $c12[1];		
		
		$c13 = explode(" ",$coord[13]);
		$lat13 = $c13[0];
		$long13 = $c13[1];		

		$c14 = explode(" ",$coord[14]);
		$lat14 = $c14[0];
		$long14 = $c14[1];	

		$c15 = explode(" ",$coord[15]);
		$lat15 = $c15[0];
		$long15 = $c15[1];		

		$c16 = explode(" ",$coord[16]);
		$lat16 = $c16[0];
		$long16 = $c16[1];		

		$c17 = explode(" ",$coord[17]);
		$lat17 = $c17[0];
		$long17 = $c17[1];		

		$c18 = explode(" ",$coord[18]);
		$lat18 = $c18[0];
		$long18 = $c18[1];		

		$c19 = explode(" ",$coord[19]);
		$lat19 = $c19[0];
		$long19 = $c19[1];	

		$c20 = explode(" ",$coord[20]);
		$lat20 = $c20[0];
		$long20 = $c20[1];		

		$c21 = explode(" ",$coord[21]);
		$lat21 = $c21[0];
		$long21 = $c21[1];		

		$c22 = explode(" ",$coord[22]);
		$lat22 = $c22[0];
		$long22 = $c22[1];		

		$c23 = explode(" ",$coord[23]);
		$lat23 = $c23[0];
		$long23 = $c23[1];				
			
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$lng11 = $r * ( cos((pi()/180)*(float)$lat11) * cos((pi()/180)*(float)$long11) );
		$lt11 = $r * ( cos((pi()/180)*(float)$lat11) * sin((pi()/180)*(float)$long11) );		
		
		$lng12 = $r * ( cos((pi()/180)*(float)$lat12) * cos((pi()/180)*(float)$long12) );
		$lt12 = $r * ( cos((pi()/180)*(float)$lat12) * sin((pi()/180)*(float)$long12) );		
		
		$lng13 = $r * ( cos((pi()/180)*(float)$lat13) * cos((pi()/180)*(float)$long13) );
		$lt13 = $r * ( cos((pi()/180)*(float)$lat13) * sin((pi()/180)*(float)$long13) );				

		$lng14 = $r * ( cos((pi()/180)*(float)$lat14) * cos((pi()/180)*(float)$long14) );
		$lt14 = $r * ( cos((pi()/180)*(float)$lat14) * sin((pi()/180)*(float)$long14) );				

		$lng15 = $r * ( cos((pi()/180)*(float)$lat15) * cos((pi()/180)*(float)$long15) );
		$lt15 = $r * ( cos((pi()/180)*(float)$lat15) * sin((pi()/180)*(float)$long15) );				

		$lng16 = $r * ( cos((pi()/180)*(float)$lat16) * cos((pi()/180)*(float)$long16) );
		$lt16 = $r * ( cos((pi()/180)*(float)$lat16) * sin((pi()/180)*(float)$long16) );				

		$lng17 = $r * ( cos((pi()/180)*(float)$lat17) * cos((pi()/180)*(float)$long17) );
		$lt17 = $r * ( cos((pi()/180)*(float)$lat17) * sin((pi()/180)*(float)$long17) );				

		$lng18 = $r * ( cos((pi()/180)*(float)$lat18) * cos((pi()/180)*(float)$long18) );
		$lt18 = $r * ( cos((pi()/180)*(float)$lat18) * sin((pi()/180)*(float)$long18) );				

		$lng19 = $r * ( cos((pi()/180)*(float)$lat19) * cos((pi()/180)*(float)$long19) );
		$lt19 = $r * ( cos((pi()/180)*(float)$lat19) * sin((pi()/180)*(float)$long19) );	

		$lng20 = $r * ( cos((pi()/180)*(float)$lat20) * cos((pi()/180)*(float)$long20) );
		$lt20 = $r * ( cos((pi()/180)*(float)$lat20) * sin((pi()/180)*(float)$long20) );				
		
		$lng21 = $r * ( cos((pi()/180)*(float)$lat21) * cos((pi()/180)*(float)$long21) );
		$lt21 = $r * ( cos((pi()/180)*(float)$lat21) * sin((pi()/180)*(float)$long21) );	

		$lng22 = $r * ( cos((pi()/180)*(float)$lat22) * cos((pi()/180)*(float)$long22) );
		$lt22 = $r * ( cos((pi()/180)*(float)$lat22) * sin((pi()/180)*(float)$long22) );			

		$lng23 = $r * ( cos((pi()/180)*(float)$lat23) * cos((pi()/180)*(float)$long23) );
		$lt23 = $r * ( cos((pi()/180)*(float)$lat23) * sin((pi()/180)*(float)$long23) );			

		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;
		$coord11 = $lt11." ".$lng11;
		$coord12 = $lt12." ".$lng12;
		$coord13 = $lt13." ".$lng13;
		$coord14 = $lt14." ".$lng14;
		$coord15 = $lt15." ".$lng15;
		$coord16 = $lt16." ".$lng16;
		$coord17 = $lt17." ".$lng17;
		$coord18 = $lt18." ".$lng18;
		$coord19 = $lt19." ".$lng19;
		$coord20 = $lt20." ".$lng20;
		$coord21 = $lt21." ".$lng21;
		$coord22 = $lt22." ".$lng22;
		$coord23 = $lt23." ".$lng23;

		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 

		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12,$coord13,$coord14,$coord15,$coord16,$coord17,$coord18,$coord19,$coord20,$coord21,$coord22,$coord23);			
	
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10],$coord[11],$coord[12],$coord[13],$coord[14],$coord[15],$coord[16],$coord[17],$coord[18],$coord[19],$coord[20],$coord[21],$coord[22],$coord[23]);
	}
	else if($coord_size == 25)
	{
	
		$c0 = explode(" ",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
		$c1 = explode(" ",$coord[1]);
		$lat1 = $c1[0];
		$long1 = $c1[1];

		$c2 = explode(" ",$coord[2]);
		$lat2 = $c2[0];
		$long2 = $c2[1];

		$c3 = explode(" ",$coord[3]);
		$lat3 = $c3[0];
		$long3 = $c3[1];
		
		$c4 = explode(" ",$coord[4]);
		$lat4 = $c4[0];
		$long4 = $c4[1];

		$c5 = explode(" ",$coord[5]);
		$lat5 = $c5[0];
		$long5 = $c5[1];

		$c6 = explode(" ",$coord[6]);
		$lat6 = $c6[0];
		$long6 = $c6[1];

		$c7 = explode(" ",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(" ",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(" ",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(" ",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(" ",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		$c12 = explode(" ",$coord[12]);
		$lat12 = $c12[0];
		$long12 = $c12[1];		
		
		$c13 = explode(" ",$coord[13]);
		$lat13 = $c13[0];
		$long13 = $c13[1];		

		$c14 = explode(" ",$coord[14]);
		$lat14 = $c14[0];
		$long14 = $c14[1];	

		$c15 = explode(" ",$coord[15]);
		$lat15 = $c15[0];
		$long15 = $c15[1];		

		$c16 = explode(" ",$coord[16]);
		$lat16 = $c16[0];
		$long16 = $c16[1];		

		$c17 = explode(" ",$coord[17]);
		$lat17 = $c17[0];
		$long17 = $c17[1];		

		$c18 = explode(" ",$coord[18]);
		$lat18 = $c18[0];
		$long18 = $c18[1];		

		$c19 = explode(" ",$coord[19]);
		$lat19 = $c19[0];
		$long19 = $c19[1];	

		$c20 = explode(" ",$coord[20]);
		$lat20 = $c20[0];
		$long20 = $c20[1];		

		$c21 = explode(" ",$coord[21]);
		$lat21 = $c21[0];
		$long21 = $c21[1];		

		$c22 = explode(" ",$coord[22]);
		$lat22 = $c22[0];
		$long22 = $c22[1];		

		$c23 = explode(" ",$coord[23]);
		$lat23 = $c23[0];
		$long23 = $c23[1];		

		$c24 = explode(" ",$coord[24]);
		$lat24 = $c24[0];
		$long24 = $c24[1];				
			
		
		//// NEW CODE
		$lng0 = $r * ( cos((pi()/180)*(float)$lat0) * cos((pi()/180)*(float)$long0) );
		$lt0 = $r * ( cos((pi()/180)*(float)$lat0) * sin((pi()/180)*(float)$long0) );

		$lng1 = $r * ( cos((pi()/180)*(float)$lat1) * cos((pi()/180)*(float)$long1) );
		$lt1 = $r * ( cos((pi()/180)*(float)$lat1) * sin((pi()/180)*(float)$long1) );

		$lng2 = $r * ( cos((pi()/180)*(float)$lat2) * cos((pi()/180)*(float)$long2) );
		$lt2 = $r * ( cos((pi()/180)*(float)$lat2) * sin((pi()/180)*(float)$long2) );

		$lng3 = $r * ( cos((pi()/180)*(float)$lat3) * cos((pi()/180)*(float)$long3) );
		$lt3 = $r * ( cos((pi()/180)*(float)$lat3) * sin((pi()/180)*(float)$long3) );
		
		$lng4 = $r * ( cos((pi()/180)*(float)$lat4) * cos((pi()/180)*(float)$long4) );
		$lt4 = $r * ( cos((pi()/180)*(float)$lat4) * sin((pi()/180)*(float)$long4) );

		$lng5 = $r * ( cos((pi()/180)*(float)$lat5) * cos((pi()/180)*(float)$long5) );
		$lt5 = $r * ( cos((pi()/180)*(float)$lat5) * sin((pi()/180)*(float)$long5) );

		$lng6 = $r * ( cos((pi()/180)*(float)$lat6) * cos((pi()/180)*(float)$long6) );
		$lt6 = $r * ( cos((pi()/180)*(float)$lat6) * sin((pi()/180)*(float)$long6) );

		$lng7 = $r * ( cos((pi()/180)*(float)$lat7) * cos((pi()/180)*(float)$long7) );
		$lt7 = $r * ( cos((pi()/180)*(float)$lat7) * sin((pi()/180)*(float)$long7) );

		$lng8 = $r * ( cos((pi()/180)*(float)$lat8) * cos((pi()/180)*(float)$long8) );
		$lt8 = $r * ( cos((pi()/180)*(float)$lat8) * sin((pi()/180)*(float)$long8) );

		$lng9 = $r * ( cos((pi()/180)*(float)$lat9) * cos((pi()/180)*(float)$long9) );
		$lt9 = $r * ( cos((pi()/180)*(float)$lat9) * sin((pi()/180)*(float)$long9) );

		$lng10 = $r * ( cos((pi()/180)*(float)$lat10) * cos((pi()/180)*(float)$long10) );
		$lt10 = $r * ( cos((pi()/180)*(float)$lat10) * sin((pi()/180)*(float)$long10) );		
		
		$lng11 = $r * ( cos((pi()/180)*(float)$lat11) * cos((pi()/180)*(float)$long11) );
		$lt11 = $r * ( cos((pi()/180)*(float)$lat11) * sin((pi()/180)*(float)$long11) );		
		
		$lng12 = $r * ( cos((pi()/180)*(float)$lat12) * cos((pi()/180)*(float)$long12) );
		$lt12 = $r * ( cos((pi()/180)*(float)$lat12) * sin((pi()/180)*(float)$long12) );		
		
		$lng13 = $r * ( cos((pi()/180)*(float)$lat13) * cos((pi()/180)*(float)$long13) );
		$lt13 = $r * ( cos((pi()/180)*(float)$lat13) * sin((pi()/180)*(float)$long13) );				

		$lng14 = $r * ( cos((pi()/180)*(float)$lat14) * cos((pi()/180)*(float)$long14) );
		$lt14 = $r * ( cos((pi()/180)*(float)$lat14) * sin((pi()/180)*(float)$long14) );				

		$lng15 = $r * ( cos((pi()/180)*(float)$lat15) * cos((pi()/180)*(float)$long15) );
		$lt15 = $r * ( cos((pi()/180)*(float)$lat15) * sin((pi()/180)*(float)$long15) );				

		$lng16 = $r * ( cos((pi()/180)*(float)$lat16) * cos((pi()/180)*(float)$long16) );
		$lt16 = $r * ( cos((pi()/180)*(float)$lat16) * sin((pi()/180)*(float)$long16) );				

		$lng17 = $r * ( cos((pi()/180)*(float)$lat17) * cos((pi()/180)*(float)$long17) );
		$lt17 = $r * ( cos((pi()/180)*(float)$lat17) * sin((pi()/180)*(float)$long17) );				

		$lng18 = $r * ( cos((pi()/180)*(float)$lat18) * cos((pi()/180)*(float)$long18) );
		$lt18 = $r * ( cos((pi()/180)*(float)$lat18) * sin((pi()/180)*(float)$long18) );				

		$lng19 = $r * ( cos((pi()/180)*(float)$lat19) * cos((pi()/180)*(float)$long19) );
		$lt19 = $r * ( cos((pi()/180)*(float)$lat19) * sin((pi()/180)*(float)$long19) );	

		$lng20 = $r * ( cos((pi()/180)*(float)$lat20) * cos((pi()/180)*(float)$long20) );
		$lt20 = $r * ( cos((pi()/180)*(float)$lat20) * sin((pi()/180)*(float)$long20) );				
		
		$lng21 = $r * ( cos((pi()/180)*(float)$lat21) * cos((pi()/180)*(float)$long21) );
		$lt21 = $r * ( cos((pi()/180)*(float)$lat21) * sin((pi()/180)*(float)$long21) );	

		$lng22 = $r * ( cos((pi()/180)*(float)$lat22) * cos((pi()/180)*(float)$long22) );
		$lt22 = $r * ( cos((pi()/180)*(float)$lat22) * sin((pi()/180)*(float)$long22) );			

		$lng23 = $r * ( cos((pi()/180)*(float)$lat23) * cos((pi()/180)*(float)$long23) );
		$lt23 = $r * ( cos((pi()/180)*(float)$lat23) * sin((pi()/180)*(float)$long23) );			

		$lng24 = $r * ( cos((pi()/180)*(float)$lat24) * cos((pi()/180)*(float)$long24) );
		$lt24 = $r * ( cos((pi()/180)*(float)$lat24) * sin((pi()/180)*(float)$long24) );			

		
		$coord0 = $lt0." ".$lng0;
		$coord1 = $lt1." ".$lng1;
		$coord2 = $lt2." ".$lng2;
		$coord3 = $lt3." ".$lng3;
		$coord4 = $lt4." ".$lng4;
		$coord5 = $lt5." ".$lng5;
		$coord6 = $lt6." ".$lng6;
		$coord7 = $lt7." ".$lng7;
		$coord8 = $lt8." ".$lng8;
		$coord9 = $lt9." ".$lng9;
		$coord10 = $lt10." ".$lng10;
		$coord11 = $lt11." ".$lng11;
		$coord12 = $lt12." ".$lng12;
		$coord13 = $lt13." ".$lng13;
		$coord14 = $lt14." ".$lng14;
		$coord15 = $lt15." ".$lng15;
		$coord16 = $lt16." ".$lng16;
		$coord17 = $lt17." ".$lng17;
		$coord18 = $lt18." ".$lng18;
		$coord19 = $lt19." ".$lng19;
		$coord20 = $lt20." ".$lng20;
		$coord21 = $lt21." ".$lng21;
		$coord22 = $lt22." ".$lng22;
		$coord23 = $lt23." ".$lng23;
		$coord24 = $lt24." ".$lng24;

		//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 
		$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12,$coord13,$coord14,$coord15,$coord16,$coord17,$coord18,$coord19,$coord20,$coord21,$coord22,$coord23,$coord24);				
		//$polygon = array($coord[0],$coord[1],$coord[2],$coord[3],$coord[4],$coord[5],$coord[6],$coord[7],$coord[8],$coord[9],$coord[10],$coord[11],$coord[12],$coord[13],$coord[14],$coord[15],$coord[16],$coord[17],$coord[18],$coord[19],$coord[20],$coord[21],$coord[22],$coord[23],$coord[24]);
	}
	
	//echo "<br>sizeof point=".sizeof($points)." sizeof polygon=".sizeof($polygon);
	/*foreach ($polygon as $value) {
	
	echo "<br>PP=".$value."";
	
	}	*/
	
	echo "<br>pt=".$pt;
	
	foreach($points as $key => $point) {
		//echo "$key ($point) is " . $pointLocation->pointInPolygon($point, $polygon) . "<br>";
		//echo "<br>point=".$point." polygon=".$polygon;
		$found = $pointLocation->pointInPolygon($point, $polygon);
		 if($found=="inside")
			$status = true;
		 else
			$status = false;	
	}
	//echo "<BR>VALID=".$status;
}	

?>