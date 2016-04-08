<?php

  //include_once('util_session_variable.php');
  //include_once("util_php_mysql_connectivity.php");	
  function get_io($imei, $type)
  {
    global $DbConnection;
    $query = "SELECT io_assignment.".$type." FROM io_assignment,vehicle_assignment WHERE io_assignment.vehicle_id=vehicle_assignment.vehicle_id AND".
			" vehicle_assignment.device_imei_no='$imei' AND io_assignment.status=1 AND vehicle_assignment.status=1";
    //echo "<br>".$query;
    
    //if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.15")
    //echo "<br>query=".$query;
    
    $result = mysql_query($query,$DbConnection);
    $row = mysql_fetch_object($result);
    
    $iotype = $row->$type;  // io1 to io8
    $iotype = 'io'.$iotype;
    //mysql_close($DbConnection); 
    return $iotype;
  }  
  
?>