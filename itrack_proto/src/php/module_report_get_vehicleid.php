<?php

$query_vid = "SELECT VehicleID FROM vehicle WHERE VehicleSerial='$dev_id'";
$result_vid = mysql_query($query_vid,$DbConnection);
print_query($query_vid);

$row = mysql_fetch_object($result_id);
$vid = $row->VehicleID;

?>
