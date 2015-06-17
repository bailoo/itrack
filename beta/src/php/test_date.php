
<?php
/*
date_default_timezone_set('Asia/Calcutta');

$script_tz = date_default_timezone_get();
echo "SCRIPT_TZ=".$script_tz;

if (strcmp($script_tz, ini_get('date.timezone'))){
    echo 'Script timezone differs from ini-set timezone.';
} else {
    echo 'Script timezone and ini-set timezone match.';
}
*/

$current_time = date('Y/m/d H:i:s');
echo "<br>CURRENT_TIME=".$current_time;
?>

