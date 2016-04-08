<html>
<head>
</head>

<body>
<form name="t1" action="sum_time.php" method = "POST">
<center>
<br><br>
<textarea name="total_time"></textarea>
<br>
<input type="submit" value="GET SUM">
</center>
</form>
</body>
</html>

<?php
include_once("util.hr_min_sec.php");

$time_str = $_POST['total_time'];
//echo "<b>time_str=".$time_str;
$arr_str = explode("\n",$time_str);

$sum_time = 0;
$total = sizeof($arr_str);
for($i=0;$i<sizeof($arr_str);$i++)
{
	$tmp2 = "2013-07-05 ".$arr_str[$i];
	$tmp1 = "2013-07-05 00:00:00";
	$time2 = strtotime($tmp2);
	$time1 = strtotime($tmp1);
	$diff = $time2 - $time1;
	$sum_time = $sum_time + $diff;
}

//echo "\nSum=".$sum_time;
$hms_2 = secondsToTime($sum_time);
$hrs_min = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
//$hrs_min_avg = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
//$hrs_min_avg = (120*3600+44*60+16)/31;

$hrs_min_avg = ($hms_2[h]*3600+$hms_2[m]*60+$hms_2[s])/$total;
$hms_avg = secondsToTime($hrs_min_avg);
$hms_avg_str =$hms_avg[h].":".$hms_avg[m].":".$hms_avg[s];


echo "<br><br><center>SUM=<font color=red><strong>".$hrs_min."</strong></font>";
echo "<br><br><center>SUM AVERAGE=<font color=red><strong>".$hms_avg_str."</strong></font>";

?>