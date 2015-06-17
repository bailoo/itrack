<?php
  include_once('util_session_variable.php');
  include_once("util_php_mysql_connectivity.php");	

  $acid[]=2;
  $acid[]=5;
  $acid[]=9;
  $acid[]=17;
  $acid[]=18;
  $acid[]=20;
  $acid[]=14;
  $acid[]=11;
  $acid[]=15;
  $acid[]=16;
  $acid[]=13;
  $acid[]=10;
  $acid[]=12;
  $acid[]=22;  
    
  for($i=0;$i<sizeof($acid);$i++)
  {
    $query = "SELECT vehicle_id, device_imei_no FROM vehicle_assignment WHERE vehicle_id IN(SELECT vehicle_id FROM vehicle_grouping WHERE "
    "account_id ='$acid[$i]' AND status=1";
    $result = mysql_query($query,$DbConnection);
    $row = mysql_fetch_object($result);
    
    $vehicle_id = $row->vehicle_id;
    $device_imei_no = $row->device_imei_no; 
    $acidtmp = $acid[$i];
    
    $query = "INSERT INTO device_manufacturing_info values("
    
  
  mysql_close($DbConnection); 
?>