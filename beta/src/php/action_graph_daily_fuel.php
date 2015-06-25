<?php 
  include_once('lib/BUG.php');
  include_once('lib/VTSFuel.php');
  include_once('lib/VTSMySQL.php');
  include_once('util_php_mysql_connectivity.php');
  include_once('util_session_variable.php');
?>
<?php 
//date_default_timezone_set('Asia/Calcutta');
$vehicle_id = $_POST['vehicle_id_local']; 
$startDate = str_replace('/','-',$_POST['start_date']);
$endDate = str_replace('/','-',$_POST['end_date']);

//echo "startDate=".$startDate."endDate=".$endDate."vehicle_id=".$vehicle_id."<br>";

$fuel_data = VTSFuel::getFuelData($DbConnection, $vehicle_id, $startDate, $endDate);
// BUG::debug("Fuel Data   : " . sizeof($fuel_data));
// BUG::printArray("Fuel Data", $fuel_data);

$max_fuel_io1 = VTSFuel::$max_fuel_io;
$max_fuel_value1 = VTSFuel::$max_fuel_value;

$fuel=array();
if(sizeof($fuel_data)>0)
{
	$time_list = VTSFuel::getFuelTimeSamples($startDate, $endDate);	
	
	$datetimeNowTS = strtotime(date("Y:m:d H:i:s", time()));    
	
	if(sizeof($time_list)>0)
	{
		foreach($time_list as $datetime)
		{
			$datetimeTS = strtotime($datetime);
			if($datetimeTS <= $datetimeNowTS)
			{
				$fuel_datetime = VTSFuel::interpolateFuelData($fuel_data, $datetime);
				if($fuel_datetime >= 0)
				{
				  $fuel_datetime_1 = $fuel_datetime;
				  
				  if($fuel_datetime_1 > $max_fuel_value1) 
				  {
				    $fuel_datetime_1 = $max_fuel_value1;
          }
					
          //$fuel[$datetimeTS] = $fuel_datetime_1;
          $fuel[$datetime] = $fuel_datetime_1;
					
					//if(($_SERVER['HTTP_X_FORWARDED_FOR']) == "172.26.48.189") echo "<br>Fuel:".$fuel[$datetimeTS];
				}
			}
		}
	}
}
?>
<?php 
$vname = VTSMySQL::getVehicleNameOfVehicle($DbConnection, $vehicle_id);
$imei = VTSMySQL::getIMEIOfVehicle($DbConnection, $vehicle_id);

echo "<input type='hidden' id='vehicle_id_local' value=".$vehicle_id.">";


	echo'<div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>';
  echo '<div style="height:5px;"></div>
        <center>
          <form method="post" name="thisform" onSubmit="javascript:return validate_form(thisform)">
            <table>
              <tr>
                <td>
                    <b><font size=2><b>Fuel Graph</font></b>
                </td>
              </tr>
            </table>
             <div style="height:17px;"></div>
            <table>
              <tr>
                <td>
                    <b><font color="blue" size=2><b>Vehicle Name ('.$vname.')</b></font>&nbsp; <font color=red>('.$imei.')</font> : <font color="green" size=2><b>Date ('.$startDate.' To '.$endDate.')</font></b>
                </td>
              </tr>
            </table> 
            <div style="height:10px;"></div>
            <table align="center" border=0 cellpadding="3" cellspacing="2">
              <tr>                
                <td  class="text">
                  Select Date
                </td>
                </td>
                <td>
                  <input type="text" id="date1" name="start_date" value="'.$startDate.'" size="12" maxlength="19">             
                  <a href=javascript:NewCal_SD("date1","yyyymmdd",false,24)>
                    <img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
                  </a>
                  &nbsp;&nbsp;&nbsp;End Date  
                  <input type="text" id="date2" name="end_date" value="'.$endDate.'" size="12" maxlength="19">					
                  <a href=javascript:NewCal("date2","yyyymmdd",false,24)>
                    <img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
                  </a>
                </td>
                <td>
                  <input type="button" onclick="javascript:action_graph_daily_fuel(this.form);" value="Update">&nbsp;
                </td>
              </tr>
            </table>
          </center>
        </form>'; 	
if(sizeof($fuel)>0)
{
	$session_name = "FUEL_" . time();
	$_SESSION[$session_name] = $fuel;
	print "<center><img src=\"graph/GraphDateLine.php?s=".$session_name."\"></center>";
}
else
{
	print "<center><h3><font color='red'>No Fuel Data Found!</font></h3></center>";
}
echo'<br><center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';
?>
