<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

set_time_limit(0);

include_once("calculate_distance.php");
include_once("get_location.php");

$query_location = "SELECT sno,station_coord FROM station WHERE status=1";
$result = mysql_query($query_location, $DbConnection);
$counter = 0;
while($row = mysql_fetch_object($result))
{
    $sno = $row->sno;
    $coord = $row->station_coord;
    
    $geo_coord = explode(',',$coord);
    $lt1 = trim($geo_coord[0]);
    $lng1 = trim($geo_coord[1]);
    //echo "<br>lat=".$lt1." ,lng=".$lng1;
    $alt1 = "-";
    
    $query_select = "SELECT google_location FROM station where sno='$sno'";
    $result_select = mysql_query($query_select, $DbConnection);

    $row_select = mysql_fetch_object($result_select);
    $q_location = $row_select->google_location;

    if($q_location!="-" &&  $q_location!="")
    {
    $placename ="-";
    get_location($lt1,$lng1,$alt1,&$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION    
    $placename = $place;
    
    
    $query_update = "UPDATE station SET google_location='$placename' WHERE sno='$sno'";
    //echo "<br>".$query_update;
    mysql_query($query_update,$DbConnection);
    }

    $counter++;
    
    echo "<br>added=".$counter." ,sno=".$sno;    
}        	
														     	
?>					
