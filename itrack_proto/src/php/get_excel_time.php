<?php
	
$time_float = $_GET['excel_time'];
//$time_float = 0.583333333  = 14:00:00
// Numbers of days between January 1, 1900 and 1970 (including 19 leap years)
define("MIN_DATES_DIFF", 25569);
  
// Numbers of second in a day:
define("SEC_IN_DAY", 86400);   	

$timef = excel2_time($time_float);                        				  
              

function excel2_time($dec)
{
  $spd = 86400;            
  //$dec = 0.5833333333;
  //$dec = $time2;
  $secs = $spd * $dec;
  $time_f = strtotime('midnight') + $secs;
  echo date ('H:i:s',$time_f);

}
  
?>
