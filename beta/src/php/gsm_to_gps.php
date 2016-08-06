<?php

/*$mcc = 260;
$mnc = 2;
$lac = 10250;
$cellid = 26511;*/

//$gps_data = get_gps($mcc, $mnc, $lac, $cellid);
//echo $gps_data;

function get_gps($mcc, $mnc, $lac, $cellid) {
    $gps_data = "";
    $key = "f0d88cf6-3a80-4f12-830d-f4b8fd1f06b0";

    ///JSON CODE
    //$URL = "http://opencellid.org/cell/get?key=".$key."&mcc=".$mcc."&mnc=".$mnc."&lac=".$lac."&cellid=".$cellid."&format=json"; 
    $URL = "http://opencellid.org/cell/get?key=$key&mcc=$mcc&mnc=$mnc&lac=$lac&cellid=$cellid&format=json";

    $raw = @file_get_contents($URL);
    $json_data = json_decode($raw);

    //var_dump($json_data);
    echo "<br>";
    //$json = '{"foo-bar": 12345}';
    //$obj = json_decode($json);
    //echo "lon=". $json_data->{'lon'}; // 12345

    $gps_data = $json_data->{'lat'} . "," . $json_data->{'lon'};
    return $gps_data;
}
