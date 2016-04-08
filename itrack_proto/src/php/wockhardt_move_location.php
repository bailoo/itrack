<?php
include_once('util_php_mysql_connectivity.php');

$query = "select landmark_id,landmark_name,landmark_coord,zoom_level,distance_variable from landmark where account_id=226 AND status=1 AND landmark_name NOT like '%base%'";
echo "Q1=".$query."\n";
$result = mysql_query($query, $DbConnection);
$sno =1;
while($row = mysql_fetch_object($result))
{
	$landmark_id = $row->landmark_id;
	$landmark_name = $row->landmark_name;
	$landmark_coord = $row->landmark_coord;
	
	$result2 =null;
	$query2 = "INSERT INTO schedule_location(location_id,location_name,geo_point,account_id,status,create_id,create_date) values('$landmark_id','$landmark_name','$landmark_coord',226,1,226,'2013-08-16 11:00:00')";
	$result2 = mysql_query($query2,$DbConnection);
	//echo "\nQ2=".$query2;
	echo "\nInserted".$sno;
	$sno++;
}

$query3 = "UPDATE landmark SET status=0 WHERE account_id=226 AND status=1 AND landmark_name NOT like '%base%'";
$result3 = mysql_query($query3,$DbConnection);	

?>

