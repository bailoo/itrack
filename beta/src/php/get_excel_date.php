<?php
	
$date_decimal = $_GET['excel_date']; 

//$date_decimal = 41191 = 10/09/2012
// Numbers of days between January 1, 1900 and 1970 (including 19 leap years)
define("MIN_DATES_DIFF", 25569);
  
// Numbers of second in a day:
define("SEC_IN_DAY", 86400);   	

$timestamp = ($date_decimal - 25569) * 86400;            
$datef = excel2_date($timestamp);
                
  
function excel2_date($timestamp)
{
  echo date('Y-m-d',excel2timestamp($timestamp));
}

function excel2timestamp($excelDate)
{
   if ($excelDate <= MIN_DATES_DIFF)
      return 0;
 
   return  ($excelDate - MIN_DATES_DIFF) * SEC_IN_DAY;
} 
         
?>
