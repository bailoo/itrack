<?php

class pointLocation {
   
   var $pointOnVertex = true; // Check if the point sits exactly on one of the vertices

    function pointLocation() {
    }                                                 
    
    function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        $this->pointOnVertex = $pointOnVertex;
        
        // Transform string coordinates into arrays with x and y values
        $point = $this->pointStringToCoordinates($point);
        $vertices = array();

		//echo "<br>POLY=".$polygon;
		
        foreach ($polygon as $vertex) {
            $vertices[] = $this->pointStringToCoordinates($vertex); 
        }
        
        // Check if the point sits exactly on a vertex
        if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
            return "vertex";
        }
        
        // Check if the point is inside the polygon or on the boundary
        $intersections = 0; 
        $vertices_count = count($vertices);
    
        for ($i=1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i-1]; 
            $vertex2 = $vertices[$i];
            
            if ($vertex1['x'] == $vertex2['x'] and $vertex1['x'] == $point['x'] and $point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] < max($vertex1['y'], $vertex2['y'])) { // Check if point is on an horizontal polygon boundary
                return "boundary";
            }
            if ($point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $vertex1['x'] != $vertex2['x']) { 
                 
                $xinters = ($point['x'] - $vertex1['x']) * ($vertex2['y'] - $vertex1['y']) / ($vertex2['x'] - $vertex1['x']) + $vertex1['y'];
                //echo "yinters:".$point['x']." "."xinters:".$xinters."\n" ;
            
                if ($xinters == $point['y']) { // Check if point is on the polygon boundary (other than horizontal)
                    return "boundary";
                }
                if ($vertex1['y'] == $vertex2['y'] || $point['y'] <= $xinters) {
                    $intersections++; 
                    //echo "i:".$i."\n" ;
                    //echo "x1:".$vertex1['y']." "."y1:".$vertex1['x']." "."x2:".$vertex2['y']." "."y2:".$vertex2['x']." " ;
                }
            } 
        }
        //echo "intersection:".$intersections."\n" ;
        // If the number of edges we passed through is even, then it's in the polygon. 
        if ($intersections % 2 != 0) {
            return "inside";
        } else {
            return "outside";
        }
    }
 
    
    function pointOnVertex($point, $vertices) {
        foreach($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
    }
            
    function pointStringToCoordinates($pointString) {
        $coordinates = explode(" ", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }   
}


/*** Example ***/
/*
$pointLocation = new pointLocation();

$polygon = array("51.665287 5.229492","52.475643 9.975586","52.207158 14.501953","49.802069 16.743164","47.181748 15.424805","46.278125 10.590820","47.002235 6.064453","48.943670 2.636719","51.665287 5.229492");
$points = array("48.972525 8.349609","50.000207 11.162109", "48.158269 14.458008","49.971951 15.073242");

foreach($points as $key => $point) {
    echo "$key ($point) is " . $pointLocation->pointInPolygon($point, $polygon) . "<br>";
}
*/


/**** example closed **/
/*
echo "<br>Pointlocation:<br>";
$pointLocation = new pointLocation();

//$polygon = array("28.54526364453035 77.1823239326477,28.5436143200556 77.18246340751648,28.543736842190288 77.18419075012207,28.543736842190288 77.18419075012207,28.545697176962896 77.1859073638916,28.545772573725532 77.18404054641724,28.54526364453035 77.1823239326477");
//$points = array("28.54392N 77.18331E");

//$polygon = array("26.4771804269356 80.19195556640625","26.497461251424316 80.26748657226562","26.501148289683943 80.33203125","26.46796068800286 80.37666320800781","26.41385667943026 80.38833618164062","26.41385667943026 80.27091979980469");
//$points = array("26.348652235693862 80.11848449707031");

$str = "28.5452473870438,77.18250954154428 28.54363576145385,77.18166196349557 28.542956939245496,77.18125104904175 28.542759488609708,77.1820192337691 28.542193524124013,77.18275308609009 28.54267466482152,77.18349981314532 28.544559150521547,77.18408346176147 28.544945562534572,77.18323588371277 28.5452473870438,77.18250954154428 28.545506328666278,77.18316185480944 28.545402657841674,77.18334424502245 28.54527071300823,77.18354809290759 28.545006822845505,77.1837841273009 28.544893726859073,77.18390214449755 28.544488465243845,77.18415963656298 28.544752356705256,77.18365967273712 28.5452473870438,77.18250954154428";

$coord = explode(" ",$str);
$r = 6378;
$lat = "28.54392N";
$lng = "77.18333E";
$lngA = $r * ( cos((pi()/180)*$lat) * cos((pi()/180)*$lng) );
$latA = $r * ( cos((pi()/180)*$lat) * sin((pi()/180)*$lng) );
//$pt = $ltA." ".$lngA;

$pt = $latA." ".$lngA;

//echo "<br>PT=".$pt."<br>";
$points = array($pt);	
	
		$c0 = explode(",",$coord[0]);
		$lat0 = $c0[0];
		$long0 = $c0[1];
		
		//echo "<br>coord[0]=".$coord[0]." lat0=".$lat0." long0=".$long0;
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

		$c7 = explode(",",$coord[7]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c8 = explode(",",$coord[8]);
		$lat3 = $c3[0];
		$long3 = $c3[1];

		$c9 = explode(",",$coord[9]);
		$lat9 = $c9[0];
		$long9 = $c9[1];

		$c10 = explode(",",$coord[10]);
		$lat10 = $c10[0];
		$long10 = $c10[1];
		
		$c11 = explode(",",$coord[11]);
		$lat11 = $c11[0];
		$long11 = $c11[1];		
		
		$c12 = explode(",",$coord[12]);
		$lat12 = $c12[0];
		$long12 = $c12[1];		
		
		$c13 = explode(",",$coord[13]);
		$lat13 = $c13[0];
		$long13 = $c13[1];		

		$c14 = explode(",",$coord[14]);
		$lat14 = $c14[0];
		$long14 = $c14[1];	

		$c15 = explode(",",$coord[15]);
		$lat15 = $c15[0];
		$long15 = $c15[1];		

		$c16 = explode(",",$coord[16]);
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

		//echo "<br>17.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3." : ".$coord4." : ".$coord5." : ".$coord6." : ".$coord7." : ".$coord8." : ".$coord9." : ".$coord10." : ".$coord11."<br><br>"; 
		//$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12,$coord13,$coord14,$coord15,$coord16);
    $polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6,$coord7,$coord8,$coord9,$coord10,$coord11,$coord12,$coord13,$coord14,$coord15,$coord16);			

    
//$polygon = array("28.5452473870438 77.18250954154428","28.54363576145385 77.18166196349557","28.542956939245496 77.18125104904175","28.542759488609708 77.1820192337691","28.542193524124013 77.18275308609009","28.54267466482152 77.18349981314532","28.544559150521547 77.18408346176147","28.544945562534572 77.18323588371277","28.5452473870438 77.18250954154428","28.545506328666278 77.18316185480944","28.545402657841674 77.18334424502245","28.54527071300823 77.18354809290759","28.545006822845505 77.1837841273009","28.544893726859073 77.18390214449755","28.544488465243845 77.18415963656298","28.544752356705256 77.18365967273712","28.5452473870438 77.18250954154428");
//$points = array("28.54392N 77.18333E");


foreach($points as $key => $point) {
    echo "$key ($point) is " . $pointLocation->pointInPolygon($point, $polygon) . "<br>";
}

*/
?> 