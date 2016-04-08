<?php
$pathtowrite_2="schedule_location_data/schedule_location.xml";
//echo "path=".$pathtowrite."<br>";
$fh12 = fopen($pathtowrite_2, 'w') or die("can't open file 3"); // new
fwrite($fh12, "<t1>");  
fclose($fh12);
?>