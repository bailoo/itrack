<?php
//$date_format = seconds2human(90098);
//echo $date_format;

function seconds2human($ss) {
$s = $ss%60;
$m = floor(($ss%3600)/60);
$h = floor(($ss%86400)/3600);
$d = floor(($ss%2592000)/86400);
$M = floor($ss/2592000);

//return "$M months, $d days, $h hours, $m minutes, $s seconds";
return "$d days $h hours $m minutes";
}
?>