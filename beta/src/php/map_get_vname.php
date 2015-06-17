<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
	$imei1 = $_GET['imei'];
	
  $query = "SELECT vehicle_name FROM vehicle WHERE vehicle_id IN (SELECT vehicle_id FROM vehicle_assignment ".
  "WHERE device_imei_no='$imei1' AND status=1) AND status=1 ";
  //echo "<br>query_=".$query;
  $res = mysql_query($query, $DbConnection);
  $row = mysql_fetch_object($res);          
  $vname = $row->vehicle_name; 	
  
  echo $vname;
?>