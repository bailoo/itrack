<?php

$geocodes_string = $_POST['geocodedPostcodes'];
$geocodes_string = substr($geocodes_string, 0, -1);
$geocodes_string = str_replace('"','',$geocodes_string);

echo "\nLOCATION=".$geocodes_string;

?>