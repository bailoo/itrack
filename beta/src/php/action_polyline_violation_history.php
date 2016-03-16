<style>
   /* html, body{
margin:0;
padding:0;
height:100%;
}*/
section {
position: relative;
border: 1px solid #000;
padding-top: 37px;
/*background: #500;*/
}
section.positioned {
position: absolute;
top:100px;
left:100px;
width:800px;
box-shadow: 0 0 15px #333;
}
.container {
overflow-y: auto;
height: 350px;
}
/*table {
border-spacing: 0;
width:100%;
}*/
.tablescroll {
border-spacing: 0;
width:100%;
}
.tablescroll td + td {
border-left:1px solid #eee;
}
.tablescroll td, th {
border-bottom:1px solid #eee;
/*background: #ddd;*/
color: #000;
padding: 5px 15px;
}
.tablescroll th {
height: 0;
line-height: 0;
padding-top: 0;
padding-bottom: 0;
color: transparent;
border: none;
white-space: nowrap;
}
.tablescroll th div{
position: absolute;

background: transparent;
/*color: #fff;*/
color: black;
padding: 9px 25px;
top: 0;
margin-left: -25px;
line-height: normal;
border-left: 1px solid #800;
}
.tablescroll th:first-child div{
border: none;
}
</style> 
<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');

//==========include libraray and class and function==============//

include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once("report_title.php");

$device_str = $_POST['vehicleserial'];
$device = explode(':',$device_str);
//echo "device_str=".$device_str."<br>";
$vsize = sizeof($device);


$date1 = $_POST['start_date'];
$date2 = $_POST['end_date'];
$date1 = str_replace("/","-",$date1);
$date2 = str_replace("/","-",$date2);
$date_1 = explode(" ",$date1);
$date_2 = explode(" ",$date2);
$datefrom = $date_1[0];
$dateto = $date_2[0];

echo'<form method="post" target="_blank">';
	 $title='Polyline Voilation History';
	 echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
				
        $csv_string = "";
        $csv_string = $csv_string." Polyline Voilation History\n";
        $csv_string = $csv_string."SNo,VehicleNo,ImeiNo,Route,FirstVoilationTime,FirstVoilationDetail,FirstVoilationLocation,LastVoilationTime,LastVoilationDetail,LastVoilationLocation";

	echo'<br><br>
		<table border=0 width = 100% cellspacing=2 cellpadding=0>
			<tr>
				<td height=10 class="report_heading" align="center">
					Polyline Voilation History
				</td>
			</tr>
		</table>
		<br>';
	echo'<table border=1 rules=all width="95%" align="center" cellspacing=0 cellpadding=3>';
        echo'<tr class=header style="background-color:silver;" rules=all>
                <td class="text" align="left">
                        <b>&nbsp;SNo</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;VehicleNo</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;ImeiNo</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;Route</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;FirstVoilationTime</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;FirstVoilationDetail</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;FirstVoilationLocation</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;LastVoilationTime</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;LastVoilationDetail</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;LastVoilationLocation</b>
                </td>
                                
        ';
	$csv_string=$csv_string." \n";				
        echo'</tr>';
	
        $conditionStr="";       
        for($i=0;$i<$vsize;$i++)			
        {
                $conditionStr=$conditionStr." polyline_voilation_history.imeino='$device[$i]' OR ";
               
        }       
        $conditionStr = substr($conditionStr,0,-3);
        
        //echo $conditionStr;
      $data=array();
      $query ="SELECT * from polyline_voilation_history USE INDEX(ime) where ($conditionStr) and status=0 and accountid= $account_id and create_date >='$date1' and create_date <='$date2'  ";       
      echo $query;
      $result = @mysql_query($query, $DbConnection);		 
      while($row = mysql_fetch_object($result))
      {
         
            $data[]=array('vehicleno'=>$row->vehicleno,'imeino'=>$row->imeino,'route'=>$row->route,'first_voilation_time'=>$row->first_voilation_time,'first_voilation_details'=>$row->first_voilation_details,'first_voilation_location'=>$row->first_voilation_location,'last_voilation_time'=>$row->last_voilation_time,'last_voilation_details'=>$row->last_voilation_details,'last_voilation_location'=>$row->last_voilation_location);
      } 
      //print_r($data);
      $j=0;
      foreach($data as $rowdata)
      {
          $j++;
          $csv_string=$csv_string.$j.",".$rowdata['vehicleno'].",".$rowdata['imeino'].",".str_replace(",", "",$rowdata['route'] ).",".str_replace(",", "",$rowdata['first_voilation_time'] ).",".str_replace(",", "",$rowdata['first_voilation_details'] ).",".str_replace(",", "",$rowdata['first_voilation_location'] ).",".str_replace(",", "",$rowdata['last_voilation_time'] ).",".str_replace(",", "",$rowdata['last_voilation_details'] ).",".str_replace(",", "",$rowdata['last_voilation_location'] );
          echo'<tr>
                <td class="text" align="left">
                        <b>&nbsp;'.$j.'</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;'.$rowdata['vehicleno'].'</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;'.$rowdata['imeino'].'</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;'.$rowdata['route'].'</b>
                </td>
                <td class="text" align="left">
                         <b>&nbsp;'.$rowdata['first_voilation_time'].'</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;'.$rowdata['first_voilation_details'].'</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;'.$rowdata['first_voilation_location'].'</b>
                </td>
                <td class="text" align="left">
                         <b>&nbsp;'.$rowdata['last_voilation_time'].'</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;'.$rowdata['last_voilation_details'].'</b>
                </td>
                <td class="text" align="left">
                        <b>&nbsp;'.$rowdata['last_voilation_location'].'</b>
                </td>
                                
        ';
	$csv_string=$csv_string." \n";			
        echo'</tr>';
      }
        
	echo'
	</table>
	<center>
	<input TYPE="hidden" VALUE="vehicle" NAME="csv_type">
	<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">			
	<!--<input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size='.$vsize.'\');" value="Get PDF" class="noprint">
	&nbsp;-->
	<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">
	&nbsp;
	</center>
</form>';
?>
				