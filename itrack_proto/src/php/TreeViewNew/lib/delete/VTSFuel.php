<?php

class VTSFuel
{
	public static function calibrationDbToDisplay($calibration)
	{
		$calibration_to_display_1 = str_replace(";", " ;\n", $calibration);
		$calibration_to_display = str_replace(",", " , ", $calibration_to_display_1);
		return $calibration_to_display;
	}
	public static function checkCalibration($calibration)
	{
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
		$regex_calibration_format = '/\d+,\d+;/';
		$calibration_extra_data = preg_replace($regex_calibration_format, "", $calibration_without_ignore_char);
		if(strlen($calibration_extra_data) != 0) { return array(); }
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
