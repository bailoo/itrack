<?php

class UTIL
{
	public static function debug($msg)
	{
		print "DEBUG: " . $msg . "<br>\n";
	}

	public static function debug_no_nl($msg)
	{
		print $msg;
	}

	public static function get_All_Dates($fromDate, $toDate)
	{
		$dateMonthYearArr = array();
		$fromDateTS = strtotime($fromDate);
		$toDateTS = strtotime($toDate);

		for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24))
		{
			// use date() and $currentDateTS to format the dates in between
			$currentDateStr = date("Y-m-d",$currentDateTS);
			$dateMonthYearArr[] = $currentDateStr;
		}

		return $dateMonthYearArr;
	}

	public static function get_All_Times($fromTime, $toTime, $intervalTS)
	{
		$allTimeArray = array();
		$fromTimeTS = strtotime($fromTime);
		$toTimeTS = strtotime($toTime);

		for ($currentTimeTS = $fromTimeTS; $currentTimeTS <= $toTimeTS; $currentTimeTS += $intervalTS)
		{
			$currentTimeStr = date("Y-m-d H:i:s",$currentTimeTS);
			$allTimeArray[] = $currentTimeStr;
		}

		if($currentTimeTS!=$toTimeTS+$intervalTS)
		{
			$currentTimeStr = date("Y-m-d H:i:s",$toTimeTS);
			$allTimeArray[] = $currentTimeStr;
		}

		return $allTimeArray;
	}

	public static function get_All_Bin_Edges($fromTime, $toTime, $intervalTS)
	{
		$allTimeArray = array();
		$fromTimeTS = strtotime($fromTime);
		$toTimeTS = strtotime($toTime);

		$currentTimeStr = date("Y-m-d H:i:s",$fromTimeTS);
		$allTimeArray[] = $currentTimeStr;

		for ($currentTimeTS = $fromTimeTS+$intervalTS; $currentTimeTS <= $toTimeTS; $currentTimeTS += $intervalTS)
		{
			// use date() and $currentTimeTS to format the dates in between
			$currentTimeStr = date("Y-m-d H:i:s",$currentTimeTS-1);
			$allTimeArray[] = $currentTimeStr;
			$currentTimeStr = date("Y-m-d H:i:s",$currentTimeTS);
			$allTimeArray[] = $currentTimeStr;
		}

		if($currentTimeTS!=$toTimeTS+$intervalTS)
		{
			$currentTimeStr = date("Y-m-d H:i:s",$toTimeTS);
			$allTimeArray[] = $currentTimeStr;
		}

		return $allTimeArray;
	}

	public static function processed_plot_data($array)
	{
		$data = "";
		foreach ($array as $i => $value)
		{
			if(strlen($value)==0)
			{
				$value = "0";
			}
			$data = $data . " " .  $value;
		}
		return $data;
	}

	public static function explode_i($reg, $str, $i)
	{
		$tmp = explode($reg, $str);
		return $tmp[$i];
	}

	public static function get_xml_data($reg, $line)
	{
		$data = "";
		if(preg_match($reg, $line, $data_match))
		{
			$data = self::explode_i('"', $data_match[0], 1);
		}
		return $data;
	}

	public static function get_array_key($array, $value)
	{
		$array_keys = array_keys($array, $value);
		return $array_keys[0];
	}

	public static function linear_fit_x2y($x, $line)
	{
		$keys = array_keys($line);
		$x1 = $keys[0];
		$x2 = $keys[1];
		$y1 = $line[$x1];
		$y2 = $line[$x2];
		$y = $y1 + ($y2 - $y1) * ($x - $x1) / ($x2 - $x1);
		return $y;
	}

	public static function linear_fit_y2x($y, $line)
	{
		$keys = array_keys($line);
		$x1 = $keys[0];
		$x2 = $keys[1];
		$y1 = $line[$x1];
		$y2 = $line[$x2];
		$x = $x1 + ($x2 - $x1) * ($y - $y1) / ($y2 - $y1);
		return $x;
	}

	public static function round($x, $i)
	{
		$y = round($x,$i);

		$y_exp = explode(".", $y);
		$y_int = $y_exp[0];

		if(sizeof($y_exp) > 1)
		{
			$y_frac = $y_exp[1];
		}
		else
		{
			$y_frac = "";
		}

		if(strlen($y_frac) < $i)
		{
			$y_frac .= self::repeat_str("0", ($i - strlen($y_frac)));
		}

		if($i>0)
		{
			$y_round = $y_int . "." . $y_frac;
		}
		else
		{
			$y_round = $y_int;
		}
		return $y_round;
	}

	public static function repeat_str($str, $n)
	{
		$result = "";
		for($i=0; $i<$n; $i++)
		{
			$result .= $str;
		}
		return $result;
	}

	public static function sortDateTime($data)
	{
		foreach($data as $datetime => $value)
		{
			$data_datetime[strtotime($datetime)] = $datetime;
		}

		$data_datetimeTS = array_keys($data_datetime);
		sort($data_datetimeTS);

		foreach($data_datetimeTS as $datetimeTS)
		{
			$value = $data[$data_datetime[$datetimeTS]];
			$data_sort[$data_datetime[$datetimeTS]] = $value;
			$data_sortTS[$datetimeTS] = $value;
		}

		$dataSort['datetime'] = $data_sort;
		$dataSort['datetimeTS'] = $data_sortTS;
		return $dataSort;
	}

	public static function sort_datetime($data)
	{
		$dataSort = self::sortDateTime($data);
		return $dataSort['datetime'];
	}

	public static function sort_datetimeTS($data)
	{
		$dataSort = self::sortDateTime($data);
		return $dataSort['datetimeTS'];
	}

}

?>
