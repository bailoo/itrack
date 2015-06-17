<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

set_time_limit(180);

include("user_type_setting.php");

$account_id_local1 = $_POST['account_id_local'];

//echo "user_account_id=".$account_id;

$query = "SELECT * FROM station WHERE user_account_id='$account_id_local1' AND status=1";
//$query = "SELECT * FROM station WHERE status=1 limit 100";
$result = mysql_query($query,$DbConnection);
$numrows = mysql_num_rows($result);

$title = "<font size=3><strong>Station- Record</strong></font><br>(search individual field, select blank to see all records)<br>";
echo'



<br><table align="center">
<tr>
	<td class="text" align="center">'.$title.'<div style="height:8px;"></div></td>
</tr>
</table> 

<div style="height:520px;overflow:auto;">   

<form method = "post" target="_blank">

<table border="1" id="table_id1" width="85%" rules="all" bordercolor="#e5ecf5" align="center" cellspacing="0" cellpadding="1">	
 <tr bgcolor="#C9DDFF" align="left"> 
	<th class="text"><b><font size="2">SNo</font></b></td>   
        <th class="text"><b><font size="2">Type</font></b></td>
	<th class="text"><b><font size="2">Customer Number</font></b></td>  
	<th class="text" width="10%"><b><font size="2">Station Name</font></b></td>
	<th class="text"><b><font size="2">Create Date</font></b></td>
	<th class="text"><b><font size="2">Distance Variable</font></b></td>
	<th class="text"><b><font size="2">Map</font></b></td>
	<th class="text"><b><font size="2"></font></b></td>
 </tr>';			
 
$title= "STATION RECORD : MOTHERDELHI";
$csv_string = $csv_string.$title."\n\n";
$csv_string = $csv_string."SNo,Station Name,Customer No, Lat, Long\n";

$sno =1;
 	
while($row = mysql_fetch_object($result))
{
    $type = $row->type;
    $station_id = $row->station_id;
    $customer_no = $row->customer_no;
    $station_name = $row->station_name;
    $create_date = $row->create_date;
    $distance_variable = $row->distance_variable;
    $station_coord = $row->station_coord;
    $coord = explode(",",$station_coord);
    //echo "<br>coor[0]=".$coord[0]." ,coor[1]=".$coord[1];
    if($type=="0")
    {
	$type="Customer";
    }
    else if($type =="1")
    {
	$type="Plant";
    } 
    if ($sno%2==0)
    {
      echo '<tr bgcolor="#F7FCFF" align="left">';
    }										
    else 
    {
      echo '<tr bgcolor="#E8F6FF" align="left">';	
    }
    echo'<td class="text">'.$sno.'</td>';
    echo'<td class="text">'.$type.'</td>';
    echo'<td class="text">'.$customer_no.'</td>';
    //echo'<td class="text">'.$station_name.' ('.$snodb.' )</td>';
    echo'<td class="text">'.$station_name.'</td>';
    echo'<td class="text">'.$create_date.'</td>';
    echo'<td class="text">'.$distance_variable.'</td>';
    //echo'<td class="text" align="left"><a href="javascript:map_window_station(\''.$customer_no.'\',\''.$station_id.'\',\''.$station_name.'\',\''.trim($coord[0]).'\',\''.trim($coord[1]).'\');"><font color="green">Map</font></a></td>';
    echo'<td class="text" align="left"><span id="'.$station_id.'"><a href="javascript:map_window_station_prev(\''.$customer_no.'\',\''.$station_id.'\',\''.$station_name.'\',\''.trim($coord[0]).'\',\''.trim($coord[1]).'\');" style="text-decoration:none;"><font color="green">view</font></a></span></td>';
    echo '<td class="text"></td>';
    echo'</tr>';
    
    //if($sno<150)
    $station_name = str_replace(',',':',$station_name);
    $csv_string = $csv_string.$sno.','.$station_name.','.$customer_no.','.$coord[0].','.$coord[1]."\n";
          
    $sno++;
}

//echo $csv_string."<br>";

echo '</table>';

echo '<br><center>
        <input TYPE="hidden" VALUE="station_record" NAME="csv_type">
        <input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';
                         
        if( ($account_id_local1 == "231") || ($account_id_local1 == "723") || ($account_id_local1 == "1115") || ($account_id_local1 == "568") || ($account_id_local1 == "715") )
        {
          echo '<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Optimized CSV Report" class="noprint"></center>';
        }

echo '</form>';
echo '</div>';
?>
