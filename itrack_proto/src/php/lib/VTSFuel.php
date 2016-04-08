<?php
include_once('UTIL.php');
include_once('BUG.php');
include_once('VTSMySQL.php');
include_once('VTSXMLRead_1.php');

class VTSFuel
{
  public static $time_bin_size = 600; // 10 minute average
	public static $fuel_io_global;
	public static $fuel_voltage_io_global;
	public static $max_fuel_io;
	public static $max_fuel_value;

	public static function getFuelData($db, $vehicle_id, $startDate, $endDate)
	{    	
    $imei = VTSMySQL::getIMEIOfVehicle($db, $vehicle_id);
		//BUG::printMessage("IMEI", $imei);
		if(strlen($imei)==0) { return array(); }

		$io = VTSMySQL::getFuelIOOfVehicle($db, $vehicle_id);
		//echo "io=".$io."<br>";
		self::$fuel_io_global = $io;
		
		$io_fuel_voltage = VTSMySQL::getFuelVoltageIOOfVehicle($db, $vehicle_id);
		self::$fuel_voltage_io_global = $io_fuel_voltage;		
		//BUG::printMessage("IO", $io);
		if(strlen($io)==0) { return array(); }

		$calibration = VTSMySQL::getCalibrationOfVehicle($db, $vehicle_id);
		//BUG::printMessage("Calibration", $calibration);
		if(strlen($calibration)==0) { return array(); }


		$io_to_fuel = self::getIO2Fuel($calibration);
		//BUG::printArray("IO to Fuel", $io_to_fuel);

		$fuelData = self::getFuelDataIter($imei, $startDate, $endDate, $io, $io_to_fuel);
	
		//BUG::printArray("Fuel Data", $fuelData);

		return $fuelData;
	}

	private static function getFuelDataIter($imei, $startDate, $endDate, $io, $io_to_fuel, $iter = 0)
	{
		$count_min = 5; // ignore time bin if data points are less than 45
		//BUG::debug("Iteration # : " . $iter);
		//BUG::debug("IMEI        : " . $imei);
		//BUG::debug("Date Start  : " . $startDate);
		//BUG::debug("Date End    : " . $endDate);
		//BUG::debug("IO Port     : " . $io);
		//BUG::debug("IO to Fuel  : " . sizeof($io_to_fuel));
		//BUG::debug("Count Min   : " . $count_min);
		//BUG::printArray("IO to Fuel", $io_to_fuel);

		if($iter > 7) { return array();}

		if($imei=="" || $startDate =="" || $endDate =="" || $io=="" || $io_to_fuel=="") {return array();}

		$io_min = 40;
		$io_max = 4096;
		if($io=="io1")
		{
			$io="i";
		}		
		else if($io=="io2")
		{
			$io="j";
		}
		else if($io=="io3")
		{
			$io="k";
		}
		else if($io=="io4")
		{
			$io="l";
		}
		else if($io=="io5")
		{
			$io="m";
		}
		else if($io=="io6")
		{
			$io="n";
		}
		else if($io=="io7")
		{
			$io="o";
		}
		else if($io=="io8")
		{
			$io="p";
		}
		$fields = $io; $filedsMin = $io_min; $fieldsMax = $io_max;
		$startDateTime = $startDate . " 00:00:00";
		$endDateTime = $endDate . " 23:59:59";
		//echo "fields=".$fields."<br>";
		
		$fieldsDataAll = VTSXMLRead::getVTSFieldsData($imei, $startDateTime, $endDateTime, $fields, $filedsMin, $fieldsMax);

		$io_data = $fieldsDataAll[$io]['datetime'];
		//BUG::debug("IO Data : " . sizeof($io_data));

		foreach($io_data as $datetime => $io_value)
		{
			$bin_time = self::getDateTimeBin($datetime);
			$io_count[$bin_time] ++;
			$io_sum[$bin_time] += $io_value;
		}

		if(sizeof($io_count) > 0)
		{
			// UTIL::debug("Synthesizing All Data ...");
			foreach($io_count as $key => $value)
			{
				if($value >= $count_min)
				{
					$io_avg[$key] = $io_sum[$key] / $io_count[$key];
					$fuel_avg[$key] = UTIL::round(UTIL::linearFitX2Y($io_avg[$key], $io_to_fuel),0);
				}
			}
		}
		//BUG::printArray("Fuel Average", $fuel_avg);
		//BUG::printArray("IO Average", $io_avg);

		if($iter==0)
		{
			$edge_time = $startDateTime;
		}
		else
		{
			$edge_time = $endDateTime;
		}
		// UTIL::debug("Edge Time   : " . $edge_time);

		$edge_fuel = self::interpolateFuelData($fuel_avg, $edge_time);

		if($edge_fuel == null)
		{
			$pastDate = date("Y-m-d", strtotime($startDate) - (60 * 60 * 24));
			$fuel_avg_past = self::getFuelDataIter($imei, $pastDate, $pastDate, $io, $io_to_fuel, ($iter + 1));
			$edge_fuel = self::interpolateFuelData($fuel_avg_past, $edge_time);
			if($edge_fuel == null)
			{
				$edge_fuel = "-1";
			}
			$fuel_avg[$edge_time] = $edge_fuel;
		}

		return $fuel_avg;
	}

	public static function interpolateFuelData($fuel_data, $datetime)
	{
		$datetime_bin = self::getDateTimeBin($datetime);
		// UTIL::debug($datetime_bin);

		if(sizeof($fuel_data) > 0)
		{
			foreach($fuel_data as $fuel_time => $fuel_value)
			{
				if($fuel_time <= $datetime_bin)
				{
					$fuel_data_past[$fuel_time] = $fuel_value;
				}
			}
		}
		// print_r($fuel_data_past);

		if(sizeof($fuel_data_past) == 0) { return null; }

		$fuel_datetime = max(array_keys($fuel_data_past));
		$fuel = $fuel_data[$fuel_datetime];

		// UTIL::debug($fuel_datetime);
		// UTIL::debug($fuel);

		return $fuel;
	}

	public static function getDateTimeBin($datetime)
	{
		$time_bin_size = self::$time_bin_size;

		// $bin_time = preg_replace('/ /', '-', date("Y-m-d H:i:s", floor(strtotime($datetime)/$time_bin_size) * $time_bin_size + $time_bin_size / 2));
		$bin_time = date("Y-m-d H:i:s", floor(strtotime($datetime)/$time_bin_size) * $time_bin_size + $time_bin_size / 2);

		return $bin_time;
	}

	public static function getFuelTimeSamples($date_start, $date_end)
	{
		$time_bin_size = self::$time_bin_size;
		$time_list = UTIL::getAllBinEdges($date_start." 00:00:00", $date_end." 23:59:59", $time_bin_size);
		return $time_list;
	}

	public static function getIO2Fuel($calibration)
	{
		$calibration_array = explode(";", $calibration, -1);
		foreach($calibration_array as $calibration_data)
		{
			$calibration_data_array = explode(",", $calibration_data);
			$fuel = $calibration_data_array[0];
			$io = $calibration_data_array[1];
			$io_to_fuel[$io] = $fuel;
			self::$max_fuel_io = $calibration_data_array[1];
			self::$max_fuel_value = $calibration_data_array[0];
		}
		return $io_to_fuel;
	}

	public static function calibrationDbToDisplay($calibration)
	{
		$calibration_to_display_1 = str_replace(";", " ;\n", $calibration);
		$calibration_to_display = str_replace(",", " , ", $calibration_to_display_1);
		return $calibration_to_display;
	}
	public static function checkCalibration($calibration)
	{
	 //echo "calibration=".$calibration."<br>";
		$calibration_formated = self::checkCalibrationFormat($calibration);
		if(sizeof($calibration_formated) == 0) { return ""; }
		$calibration_result = self::checkCalibrationUnique($calibration_formated);
		if(sizeof($calibration_result) == 0) { return ""; }
		$fuel_monotonous = self::checkMonotonousY($calibration_result['io_to_fuel']);
		if(sizeof($fuel_monotonous) == 0) { return ""; }
		$io_monotonous = self::checkMonotonousY($calibration_result['fuel_to_io']);
		if(sizeof($io_monotonous) == 0) { return ""; }
		return $calibration_result['calibration'];
	}

	private static function checkCalibrationFormat($calibration)
	{
		$ignore_char = array("\r\n", "\n", "\r", "\t", " ");
		$calibration_without_ignore_char = str_replace($ignore_char, "", $calibration);
		//$regex_calibration_format = '/\d+,\d+;/';
		/*$calibration_extra_data = preg_replace($regex_calibration_format, "", $calibration_without_ignore_char);
		if(strlen($calibration_extra_data) != 0) { return array(); } */
		return $calibration_without_ignore_char;
	}

	private static function checkCalibrationUnique($calibration)
	{	 
		$calibration_array = explode(";", $calibration, -1);
		foreach($calibration_array as $calibration_data)
		{
			$calibration_data_array = explode(",", $calibration_data);
			$fuel[] = $calibration_data_array[0];
			$io[] = $calibration_data_array[1];
			$fuel_to_io[$calibration_data_array[0]] = $calibration_data_array[1];
			$io_to_fuel[$calibration_data_array[1]] = $calibration_data_array[0];
			self::$max_fuel_io = $calibration_data_array[1];
			self::$max_fuel_value = $calibration_data_array[0];
		}
		
		$fuel_sort_unique = array_unique($fuel);
		$io_sort_unique = array_unique($io);		
		sort($fuel_sort_unique);
		sort($io_sort_unique);

		$error1 = sizeof($fuel) - sizeof($fuel_sort_unique);
		$error2 = sizeof($io) - sizeof($io_sort_unique);
		if(($error1 != 0) || ($error2 != 0)) { return array(); }

		$calibration_final = "";
		foreach($fuel_sort_unique as $fuel_value)
		{
			$calibration_final .= $fuel_value . "," . $fuel_to_io[$fuel_value] . ";";
		}

		$calibration_result['calibration'] = $calibration_final;
		$calibration_result['fuel_to_io'] = $fuel_to_io;
		$calibration_result['io_to_fuel'] = $io_to_fuel;		
		return $calibration_result;
	}

	private static function checkMonotonousY($x_to_y)
	{
		$x = array_keys($x_to_y);
		sort($x);
		for($i=0; $i<(sizeof($x)-1); $i++)
		{
			$diff = $x_to_y[$x[$i+1]] - $x_to_y[$x[$i]];
			$diff_y[] = $diff;
			$diff_y_abs[] = abs($diff);
		}
		$error = array_sum($diff_y_abs) - abs(array_sum($diff_y));
		if($error != 0) { return array(); }
		return 0;
	}
}
?>
