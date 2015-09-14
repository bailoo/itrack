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
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                return "boundary";
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) { 
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x']; 
                if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
                    return "boundary";
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++; 
                }
            } 
        } 
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

$pointLocation = new pointLocation();

//$points = array("26.50591N 80.46592E","27.66433N 82.47004E", "26.61155N 80.64102E","26.47579N 80.21186E");

$r = 6378;
 
$lat = "27.66433N";
$lng = "82.47004E";

$lngA = $r * ( cos((pi()/180)*$lat) * cos((pi()/180)*$lng) );
$ltA = $r * ( cos((pi()/180)*$lat) * sin((pi()/180)*$lng) );
//$pt = $ltA." ".$lngA;

$pt = $ltA." ".$lngA;
//echo "SINGLE PT=".$pt;
$points = array($pt);

$lat0 = "27.556981920338316";
$long0 = "79.43115234375";

$lat1 = "26.22444694563432";
$long1 = "79.51904296875";

$lat2 = "25.82956108605351";
$long2 = "80.33203125";

$lat3 = "26.755420897359123";
$long3 = "81.5625";

$lat4 = "27.029770731463536";
$long4 = "80.68359375";

$lat5 = "27.44004046509707";
$long5 = "79.78271484375";

$lat6 = "27.44104046509707";
$long6 = "79.78371484375";


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

//echo "<br>4.COORD=".$coord0." ".$coord1. " : ".$coord2. " : ".$coord3."<br><br>"; 

$polygon = array($coord0,$coord1,$coord2,$coord3,$coord4,$coord5,$coord6);

//$polygon = array("27.556981920338316 79.43115234375","26.22444694563432 79.51904296875","25.82956108605351 80.33203125","26.755420897359123 81.5625","27.029770731463536 80.68359375","27.44004046509707 79.78271484375");
//$points = array("26.50591N 80.46592E","27.66433N 82.47004E", "26.61155N 80.64102E","26.47579N 80.21186E");

//$polygon = array("27.556981920338316 79.43115234375","26.22444694563432 79.51904296875","25.82956108605351 80.33203125","26.755420897359123 81.5625","27.029770731463536 80.68359375","27.44004046509707 79.78271484375");
//$points = array("26.50591N 80.46592E","27.66433N 82.47004E", "26.61155N 80.64102E","26.47579N 80.21186E");


foreach($points as $key => $point) {
    echo "$key ($point) is " . $pointLocation->pointInPolygon($point, $polygon) . "<br>";
}
/**** example closed **/

?> 