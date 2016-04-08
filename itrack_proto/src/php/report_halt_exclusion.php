<?php

/*$query_geo = "SELECT geo_id,geo_name FROM geofence WHERE geo_id IN(SELECT geo_id FROM geo_assignment WHERE status=1) AND ".
              "user_account_id='$account_id' AND status=1";*/ 
              
$query_geo = "SELECT geo_id,geo_name FROM geofence WHERE user_account_id='$account_id' AND status=1";                   
  	
$res_geo = mysql_query($query_geo,$DbConnection);

echo '<table align="center">';

//echo '<tr><td><input type="checkbox" name="all_geo" onclick="javascript:AllGeo(this.form);">Select All</td></tr>';

$i=0;

while($row_geo = mysql_fetch_object($res_geo))
{
	$geo_id = $row_geo->geo_id;
	$geo_name = $row_geo->geo_name;
	
  echo '<tr><td><input type="checkbox" name="halt_geo_area[]" value="'.$geo_id.'"/>'.$geo_name.'</td></tr>';
  $i++;
} 

echo '</table>';

?>