<?php
	include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  include_once("util.fuel_calibration.php");
  
	$content = $_GET['content'];
	//echo "content=".$content;
	$string = explode(",", $content);
	$imei = $string[0];
	$fuel = $string[1];  	 
	
  $queryc = "SELECT filename as fname FROM fuelcalib_file_assignment WHERE vehicle_id=(SELECT vehicle_id FROM vehicle_assignment ".
  "WHERE device_imei_no='$imei' AND status=1) AND status=1";
  
  $resc = mysql_query($queryc, $DbConnection);
  $rowc = mysql_fetch_object($resc);          
  $calibfile = $rowc->fname; 

  //echo "<br>query_c=".$queryc." ,fuel=".$fuel." calibfile=".$calibfile."<br>";
  
  if($fuel>30 && $fuel<4096)
  {
  	$fuel_level = get_calibrated_fuel_level($fuel, $calibfile); 
  }	
  else
  {
    $fuel_level = 0;
  }
	
	$fuel_level = round($fuel_level,2);
	echo $fuel_level;
	
?>