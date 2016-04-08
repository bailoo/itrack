<?php
include_once('UTIL.php');
include_once('BUG.php');
//include_once('VTSXMLRead.php');
include_once('VTSMySQL.php');
class VTSVehicleData
{
	public static function getSpeedData($db, $vehicle_id, $startDateTime, $endDateTime)
	{
		$imei = VTSMySQL::getIMEIOfVehicle($db, $vehicle_id);
		$fields = 'f';
		$filedsMin = '0';
		$fieldsMax = '300';

		//$fieldsDataAll = VTSXMLRead::getVTSFieldsData($db, $vehicle_id, $imei, $startDateTime, $endDateTime, $fields, $filedsMin, $fieldsMax);
		$fieldsDataAll = VTSXMLRead::getVTSFieldsData($imei, $startDateTime, $endDateTime, $fields, $filedsMin, $fieldsMax, 0,'h');
		//$fieldsDataAll = VTSXMLRead::getVTSFieldsData($imei, $startDateTime, $endDateTime, $fields, $filedsMin, $fieldsMax);
		//echo "<br>fielddata2=".$fieldsDataAll[$fields];
    return $fieldsDataAll[$fields];
	}
	
	public static function getTemperatureData($db, $vehicle_id, $startDateTime, $endDateTime)
	{
            $imei = VTSMySQL::getIMEIOfVehicle($db, $vehicle_id);
            $io = VTSMySQL::getIO($db,$imei,'temperature');
			//echo"io=".$io."<br>";
			if($io=='io1')
			{
				$io='i';
			}
			if($io=='io2')
			{
				$io='j';
			}
			if($io=='io3')
			{
				$io='k';
			}
			if($io=='io4')
			{
				$io='l';
			}
			if($io=='io5')
			{
				$io='m';
			}
			if($io=='io6')
			{
				$io='n';
			}
			if($io=='io7')
			{
				$io='o';
			}
			if($io=='io8')
			{
				$io='p';
			}
            $fields = $io;
            $filedsMin = '-30';
            $fieldsMax = '70';

            //$fieldsDataAll = VTSXMLRead::getVTSFieldsData($db, $vehicle_id, $imei, $startDateTime, $endDateTime, $fields, $filedsMin, $fieldsMax);
            $fieldsDataAll = VTSXMLRead::getVTSFieldsData($imei, $startDateTime, $endDateTime, $fields, $filedsMin, $fieldsMax, 0,'h');
            //$fieldsDataAll = VTSXMLRead::getVTSFieldsData($imei, $startDateTime, $endDateTime, $fields, $filedsMin, $fieldsMax);
            //echo "<br>fielddata2=".$fieldsDataAll[$fields];
            return $fieldsDataAll[$fields];
	}

	public static function getDeviceDistanceData($imei, $startDateTime, $endDateTime)
	{
		$fields = 'distance';
		$filedsMin = '-';
		$fieldsMax = '-';

		$fieldsDataAll = VTSXMLRead::getVTSFieldsData($imei, $startDateTime, $endDateTime, $fields, $filedsMin, $fieldsMax);
		return $fieldsDataAll[$fields];
	}

	public static function getIOData($imei, $datetime_start, $datetime_end, $io)
	{
		$fields = 'io' . $io;
		$filedsMin = '0';
		$fieldsMax = '4096';

		$fieldsDataAll = VTSXMLRead::getVTSFieldsData($imei, $startDateTime, $endDateTime, $fields, $filedsMin, $fieldsMax);
		return $fieldsDataAll[$fields];
	}

	public static function getVoltageData($imei, $datetime_start, $datetime_end)
	{
		$fields = 'sup_v';
		$filedsMin = '-';
		$fieldsMax = '-';

		$fieldsDataAll = VTSXMLRead::getVTSFieldsData($imei, $startDateTime, $endDateTime, $fields, $filedsMin, $fieldsMax);
		return $fieldsDataAll[$fields];
	}

	public static function getSatelliteData($imei, $datetime_start, $datetime_end)
	{
		$fields = 'no_of_sat';
		$filedsMin = '-';
		$fieldsMax = '-';

		$fieldsDataAll = VTSXMLRead::getVTSFieldsData($imei, $startDateTime, $endDateTime, $fields, $filedsMin, $fieldsMax);
		return $fieldsDataAll[$fields];
	}
}

?>
