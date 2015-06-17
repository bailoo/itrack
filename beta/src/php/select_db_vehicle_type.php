<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');

	$imei1 = $_GET['imei'];

  $query = "SELECT vehicle_type FROM vehicle WHERE vehicle_id IN (SELECT vehicle_id FROM vehicle_assignment WHERE device_imei_no='$imei1' AND status=1) AND status=1";   
  //echo $query;
  $result = mysql_query($query,$DbConnection);
  
  if($row=mysql_fetch_object($result))
  {
  	$vtype = $row->vehicle_type;
  }
	echo $vtype;		
	
?>