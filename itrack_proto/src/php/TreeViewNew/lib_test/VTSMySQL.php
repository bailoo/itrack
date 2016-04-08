<?php

include_once('UTIL.php');
include_once('BUG.php');

class VTSMySQL
{
	public static function test($db)
	{
		$query="SELECT account_id,user_id,group_id,user_type,status FROM account WHERE group_id = '' LIMIT 10;";
		//BUG::printQuery($query);
		$result = mysql_query($query, $db);
		$count = mysql_num_rows($result);
		$row =mysql_fetch_object($result);

		$c=0;
		while ($row=mysql_fetch_object($result))
		{
			$data['count'][$c]       = $c++;
			$data['account_id'][$c]  = $row->account_id;
			$data['user_id'][$c]     = $row->user_id;
			$data['group_id'][$c]    = $row->group_id;
			$data['user_type'][$c]   = $row->user_type;
			$data['status'][$c]      = $row->status;
		}
		return $data;
	}

	public static function getIMEIOfVehicle($db, $vehicle_id)
	{
		$query="SELECT device_imei_no FROM vehicle_assignment WHERE vehicle_id='$vehicle_id' AND status='1';";
		//BUG::printQuery($query);
		$result = mysql_query($query, $db);
		$count = mysql_num_rows($result);
		if($count == 0) { return ""; }

		$row =mysql_fetch_object($result);
		$data = $row->device_imei_no;
		return $data;
	}

	public static function getFuelIOOfVehicle($db, $vehicle_id)
	{
		$query="SELECT fuel FROM io_assignment WHERE vehicle_id='$vehicle_id' AND status='1';";
		//BUG::printQuery($query);
		$result = mysql_query($query, $db);
		$count = mysql_num_rows($result);
		if($count == 0) { return ""; }

		$row =mysql_fetch_object($result);
		$data = $row->fuel;
		$data = "io".$data;
		return $data;
	}
	
	public static function getFuelVoltageIOOfVehicle($db, $vehicle_id)
	{
		$query="SELECT fuel_voltage FROM io_assignment WHERE vehicle_id='$vehicle_id' AND status='1';";
		//BUG::printQuery($query);
		$result = mysql_query($query, $db);
		$count = mysql_num_rows($result);
		if($count == 0) { return ""; }

		$row =mysql_fetch_object($result);
		$data = $row->fuel_voltage;
		$data = "io".$data;
		return $data;
	}	

	public static function getCalibrationOfVehicle($db, $vehicle_id)
	{
		$query="SELECT calibration_data FROM calibration WHERE calibration_id IN (SELECT calibration_id FROM calibration_vehicle_assignment WHERE vehicle_id='$vehicle_id' AND status='1');";
		//BUG::printQuery($query);
		$result = mysql_query($query, $db);
		$count = mysql_num_rows($result);
		if($count == 0) { return ""; }

		$row =mysql_fetch_object($result);
		$data = $row->calibration_data;
		return $data;
	}

	public static function getVehicleNameOfVehicle($db, $vehicle_id)
	{
		$query="SELECT vehicle_name FROM vehicle WHERE vehicle_id='$vehicle_id' AND status='1';";
		//BUG::printQuery($query);
		$result = mysql_query($query, $db);
		$count = mysql_num_rows($result);
		if($count == 0) { return ""; }

		$row =mysql_fetch_object($result);
		$data = $row->vehicle_name;
		return $data;
	}
}
?>
