<?php

/* error_reporting(-1);
  ini_set('display_errors', 'On'); */
set_time_limit(300);
date_default_timezone_set("Asia/Kolkata");
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_php_mysql_connectivity.php');
include_once('util_session_variable.php');
include_once("report_title.php");
include_once("calculate_distance.php");

function get_All_Dates($fromDate, $toDate, &$userdates) {
    $dateMonthYearArr = array();
    $fromDateTS = strtotime($fromDate);
    $toDateTS = strtotime($toDate);

    for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24)) {
        // use date() and $currentDateTS to format the dates in between
        $currentDateStr = date("Y-m-d", $currentDateTS);
        $dateMonthYearArr[] = $currentDateStr;
        //print $currentDateStr.�<br />�;
    }
    $userdates = $dateMonthYearArr;
}

//====cassamdra //////////////
/* include_once('xmlParameters.php');
  include_once('parameterizeData.php'); /////// for seeing parameters
  include_once('data.php');
  include_once("getXmlData.php"); */
////////////////////////

$DEBUG = 0;
$device_str = $_POST['vehicleserial'];
//echo "<br>devicestr=".$device_str;
$vserial = explode(':', $device_str);
$vsize = count($vserial);

$date1 = $_POST['start_date'];
$date2 = $_POST['end_date'];
$date1 = str_replace("/", "-", $date1);
$date2 = str_replace("/", "-", $date2);

////////// cassandra //////////
$date_1 = explode(" ", $date1);
$date_2 = explode(" ", $date2);
$datefrom = $date_1[0];
$dateto = $date_2[0];

get_All_Dates($datefrom, $dateto, $userdates);
$date_size = sizeof($userdates);

//## MAKE Dates
$time_1 = explode(":", $date_1[1]);
$time_2 = explode(":", $date_2[1]);

$t1 = intval($time_1[0]);
$time1_hr = "";

$t2 = intval($time_2[0]);
//echo "<br>t2=".$t2;
$time2_hr = "";

$multiple_date_flag = false;

/*if (getenv('REMOTE_ADDR')=='103.210.29.74'){
echo "<br>D1=".$date_1[0]." ,D2=".$date_2[0];
}*/


//echo "<br>D1=".$date_1[0]." ,D2=".$date_2[0];
if($date_1[0]==$date_2[0]) {
for ($i = $t1+1; $i <= $t2; $i++) {

    if ($i > 0) {
        $hr = $i;

        if ($i <= 9) {
            $hr = "0" . $hr;
        }

        $time1_hr.= "HR_" . $hr . ",";
    }
}
$time1_hr = substr($time1_hr, 0, -1);
$time1_hr_fields = explode(",", $time1_hr);
//====================================
    $dateA = $date_1[0];
    $dateB = $date_2[0];
    $multiple_date_flag = false;

/*if (getenv('REMOTE_ADDR')=='103.210.29.74'){
echo "<br>SameDay";
}*/


} else {
for ($i = $t1+1; $i <= 24; $i++) {

    if ($i > 0) {
        $hr = $i;

        if ($i <= 9) {
            $hr = "0" . $hr;
        }

        $time1_hr.= "HR_" . $hr . ",";
    }
}
$time1_hr = substr($time1_hr, 0, -1);
$time1_hr_fields = explode(",", $time1_hr);
//====================================	

for ($i = 1; $i <= $t2; $i++) {

    $hr = $i;

    if ($i <= 9) {
        $hr = "0" . $hr;
    }

   // $time2_hr.= "HR_" . $hr . ",";
   $time2_hr= "HR_" . $hr;
}
//$time2_hr = substr($time2_hr, 0, -1);
//$time2_hr_fields = explode(",", $time2_hr);
//=====================================

/*if (getenv('REMOTE_ADDR')=='103.210.29.74'){
echo "T1=".$time1_hr."<br>";
echo "T2=".$time2_hr."<br>";
echo "t2=".$t2."<br>";
}*/
    //$dateA = date('Y-m-d', strtotime($date1 . ' -1 day'));
    //$dateB = date('Y-m-d', strtotime($date2 . ' +1 day'));

    $dateA = $date_1[0];
    $dateB = $date_2[0];
 
    $multiple_date_flag = true;

}  //else closed

//## Get Next and Previous Dates
$tmpd1 = $date_1[0] . " 00:00:00";
$tmpd2 = $date_2[0] . " 00:00:00";


for ($i = 0; $i < $vsize; $i++) {
    $dataCnt = 0;
    $total_dist = 0.0;
    $vehicle_info = get_vehicle_info($root, $vserial[$i]);
    $vehicle_detail_local = explode(",", $vehicle_info);

    //##BLOCK 1
   if (!$multiple_date_flag) {

/*if (getenv('REMOTE_ADDR')=='103.210.29.74'){
echo "Not multiple<br>";
}*/
    $QUERY1 = "SELECT imei,date," . $time1_hr . " FROM distance_log WHERE date ='$datefrom' AND imei='$vserial[$i]' ORDER BY date ASC";
    //echo "<br>QUERY1=".$QUERY1.", DB=".$DbConnection."<br>";
    $RESULT1 = mysql_query($QUERY1, $DbConnection);

    while ($ROW1 = mysql_fetch_object($RESULT1)) {

        $reportDate = $ROW1->date;
        //echo "\nSizeField=".sizeof($time1_hr_fields);
        for ($f = 0; $f < sizeof($time1_hr_fields); $f++) {
            $col = $time1_hr_fields[$f];
            //echo "<br>Col=".$col;
            $total_dist += $ROW1->$col;
            //echo "\nT=".$total_dist;
        }

        //echo "<br>Dist=".$total_dist." ,imei=".$vserial[$i];
    }
   }

//echo "<br>".getenv('REMOTE_ADDR');;


    if ($multiple_date_flag) {
        //##BLOCK 2
        $QUERY2 = "SELECT * FROM distance_log WHERE date BETWEEN '$dateA' AND '$dateB' AND imei='$vserial[$i]' ORDER BY date ASC";

/*if (getenv('REMOTE_ADDR')=='103.210.29.74'){
echo "MultipleDates<br>";
echo "<br>QUERY2=".$QUERY2."<br>";
}*/

        //echo "<br>QUERY2=".$QUERY2."<br>";
        $RESULT2 = mysql_query($QUERY2, $DbConnection);

        while ($ROW2 = mysql_fetch_object($RESULT2)) {

            $reportDate = $ROW2->date;

	     if($dateB==$reportDate) {
		for($d=1;$d<=$t2;$d++){
			if($d<=9) { $c1 = "0".$d; } else { $c1= $d; }
			$col = "HR_".$c1;
                //if (getenv('REMOTE_ADDR')=='103.210.29.74'){ echo $col."<br>"; }
			$total_dist+= $ROW2->$col;
		}

	     } else {
            $total_dist+= $ROW2->HR_01;
            $total_dist+= $ROW2->HR_02;
            $total_dist+= $ROW2->HR_03;
            $total_dist+= $ROW2->HR_04;
            $total_dist+= $ROW2->HR_05;
            $total_dist+= $ROW2->HR_06;
            $total_dist+= $ROW2->HR_07;
            $total_dist+= $ROW2->HR_08;
            $total_dist+= $ROW2->HR_09;
            $total_dist+= $ROW2->HR_10;
            $total_dist+= $ROW2->HR_11;
            $total_dist+= $ROW2->HR_12;
            $total_dist+= $ROW2->HR_13;
            $total_dist+= $ROW2->HR_14;
            $total_dist+= $ROW2->HR_15;
            $total_dist+= $ROW2->HR_16;
            $total_dist+= $ROW2->HR_17;
            $total_dist+= $ROW2->HR_18;
            $total_dist+= $ROW2->HR_19;
            $total_dist+= $ROW2->HR_20;
            $total_dist+= $ROW2->HR_21;
            $total_dist+= $ROW2->HR_22;
            $total_dist+= $ROW2->HR_23;
            $total_dist+= $ROW2->HR_24;
	}
        }
        //##BLOCK 3
        /*$QUERY3 = "SELECT imei,date," . $time2_hr . " FROM distance_log WHERE date ='$dateto' AND imei='$vserial[$i]' ORDER BY date ASC";

if (getenv('REMOTE_ADDR')=='103.210.29.74'){
echo "<br>QUERY3=".$QUERY3."<br>";
}

        //echo "<br>QUERY3<br>".$QUERY3;
        $RESULT3 = mysql_query($QUERY3, $DbConnection);

        while ($ROW3 = mysql_fetch_object($RESULT3)) {

            $reportDate = $ROW3->date;

            for ($f = 0; $f < sizeof($time2_hr_fields); $f++) {
                $col = $time2_hr_fields[$f];
                $total_dist+= $ROW3->$col;
            }
        }*/
    }

    $imei[] = $vserial[$i];
    $vname[] = $vehicle_detail_local[0];
    $dateDisplay[] = $reportDate;
    $distanceDisplay[] = $total_dist;
}

echo '<center>';

echo'<br>';

echo'<form method = "post" target="_blank">';
report_title("History Distance Report", $date1, $date2);

echo'<div style="overflow: auto;height: 485px; width: 800px;" align="center">';

$sno = 1;
//print_r($imei);
$csv_string = "";
$title = 'History Distance Report:('.$datefrom." to ".$dateto.")";
$csv_string = $csv_string . $title . "\n";
$csv_string = $csv_string . "SNo,Date,Distance (km)\n";
echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";

 echo'
  <table class="table table-condened table-hover table-striped">
  <thead>
   <tr>
        <th class="text" align="left"><b>SNo</b></th>
        <th class="text" align="left"><b>Date</b></th>            
        <th class="text" align="left"><b>Distance (km)</b></th>	

  </tr></thead><tbody>';
 
 
for ($i = 0; $i < sizeof($imei); $i++) {
    $dist = round($distanceDisplay[$i], 2);
    echo'<tr>'
    . '<td class="text" align="left" width="4%"><b>' . $sno . '</b></td>';
    echo'<td class="text" align="left">' . $vname[$i] . '</td>';
    //echo'<td class="text" align="left">' . $dateDisplay[$i] . '</td>';
    echo'<td class="text" align="left">' . $dist . '</td>';
    echo'</tr>';
    
    echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$i][SNo]\">";
    echo"<input TYPE=\"hidden\" VALUE=\"$vname[$i]\" NAME=\"temp[$i][Vehicle]\">";
    //echo"<input TYPE=\"hidden\" VALUE=\"$dateDisplay[$i]\" NAME=\"temp[$x][$y][Date]\">";
    echo"<input TYPE=\"hidden\" VALUE=\"$dist\" NAME=\"temp[$i][Distance (km)]\">";

    $csv_string = $csv_string . $sno . ',' .$vname[$i]. ',' . $dist . "\n";
    
    $sno++;
}

echo '</tbody></table>';
echo "</div>";

echo'	
    <table align="center">
    <tr>
            <td>';

$vsize = sizeof($imei);

if ($vsize == 0) {
    print"<center><FONT color=\"Red\" size=2><strong>No Distance Record</strong></font></center>";
    //echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
    echo'<br><br>';
} else {
    echo'<input TYPE="hidden" VALUE="distance" NAME="csv_type">';
    echo'<input TYPE="hidden" VALUE="' . $csv_string . '" NAME="csv_string">';
    echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size=' . $vsize . '\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
        <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';
}

echo'</td>		
    </tr>
    </table>
    </form>
 ';

echo '</center>';
echo'<center>		
    <a href="javascript:showReportPrevPageNew();" class="back_css">
            &nbsp;<b>Back</b>
    </a>
    </center>';
?>