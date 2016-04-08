<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('lib/VTSMySQL.php');
$vehicle_id_local=$_POST['common_id'];

$imei = VTSMySQL::getIMEIOfVehicle($DbConnection, $vehicle_id_local);
$calibration = VTSMySQL::getCalibrationOfVehicle($DbConnection, $vehicle_id_local);
$calibration_length=strlen($calibration);
if($calibration_length>25)
{
  $calibration1= substr($calibration,0,25)."...";
}
else
{
  $calibration1 = $calibration;
}

$io = VTSMySQL::getFuelIOOfVehicle($DbConnection, $vehicle_id_local);
$vname = VTSMySQL::getVehicleNameOfVehicle($DbConnection, $vehicle_id_local);
echo "report_vehicle_details##";
//echo "vehicle_id_local=".$vehicle_id_local."<br>"; 
//echo "vehicle_name=".$vname."calibration=".$calibration."io_to_fuel=".$io_to_fuel."imei=".$imei."<br>"; 
echo"<fieldset class='report_fieldset'>
	<legend>Vehicle Details</legend> 
      <table width = 100%>
        <tr>
          <td align='right'>Vehicle Name</td>
          <td>:</td>
          <td>".$vname."</td>
        </tr>
        <tr>
          <td align='right'>IMEI</td>
          <td>:</td>
          <td>".$imei."</td>
        </tr>
        <tr>
          <td align='right'>Calibration Data</td>
          <td>:</td>
          <td>".$calibration1."</td>
        </tr>
        <tr>
          <td align='right'>IO</td>
          <td>:</td>
          <td>".$io."</td>
        </tr>
      </table>
      </fieldset>";
?>
