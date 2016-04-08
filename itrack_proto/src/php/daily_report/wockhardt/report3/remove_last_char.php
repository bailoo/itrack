<?php
$rno = "_".rand();
echo "test".$rno."\n";

$string = "10,11,12,15,16,,";
echo $string . "\n";
 
// rtrim function
echo "rtrim: " . rtrim($string, ",");
 
echo "\n";

?>