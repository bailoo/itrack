<?php

class UTIL
{
	public static function getAllDates($fromDate, $toDate)
	{
		$dateMonthYearArr = array();
		$fromDateTS = strtotime($fromDate);
		$toDateTS = strtotime($toDate);

		for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24))
		{
			$currentDateStr = date("Y-m-d",$currentDateTS);
			$dateMonthYearArr[] = $currentDateStr;
		}

		return $dateMonthYearArr;
	}

	public static function getAllTimes($fromTime, $toTime, $intervalTS)
	{
		$allTimeArray = array();
		$fromTimeTS = strtotime($fromTime);
		$toTimeTS = strtotime($toTime);

		$currentTimeStr = date("Y-m-d H:i:s",$fromTimeTS);
		$allTimeArray[] = $currentTimeStr;

		for ($currentTimeTS = $fromTimeTS+$intervalTS; $currentTimeTS <= $toTimeTS; $currentTimeTS += $intervalTS)
		{
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

	public static function processedPlotData($array)
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

	public static function explodeI($reg, $str, $i)
	{
		$tmp = explode($reg, $str);
		return $tmp[$i];
	}

	public static function getXMLData($reg, $line)
	{
		$data = "";
		if(preg_match($reg, $line, $dataMatch))
		{
			$data = self::explodeI('"', $dataMatch[0], 1);
		}
		return $data;
	}

	public static function getArrayKey($array, $value)
	{
		$arrayKeys = array_keys($array, $value);
		return $arrayKeys[0];
	}

	public static function linearFitX2Y($x, $line)
	{
		$keys = array_keys($line);
		$x1 = $keys[0];
		$x2 = $keys[1];
		$y1 = $line[$x1];
		$y2 = $line[$x2];
		$y = $y1 + ($y2 - $y1) * ($x - $x1) / ($x2 - $x1);
		return $y;
	}

	public static function linearFitY2X($y, $line)
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

		$yExp = explode(".", $y);
		$yInt = $yExp[0];

		if(sizeof($yExp) > 1)
		{
			$yFrac = $yExp[1];
		}
		else
		{
			$yFrac = "";
		}

		if(strlen($yFrac) < $i)
		{
			$yFrac .= self::repeatStr("0", ($i - strlen($yFrac)));
		}

		if($i>0)
		{
			$yRound = $yInt . "." . $yFrac;
		}
		else
		{
			$yRound = $yInt;
		}
		return $yRound;
	}

	public static function repeatStr($str, $n)
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
			$dataDatetime[strtotime($datetime)] = $datetime;
		}

		$dataDatetimeTS = array_keys($dataDatetime);
		sort($dataDatetimeTS);

		foreach($dataDatetimeTS as $datetimeTS)
		{
			$value = $data[$dataDatetime[$datetimeTS]];
			$dataSortT[$dataDatetime[$datetimeTS]] = $value;
			$dataSortTS[$datetimeTS] = $value;
		}

		$dataSort['datetime'] = $dataSortT;
		$dataSort['datetimeTS'] = $dataSortTS;
		return $dataSort;
	}

	public static function sortDateTimeArray($dataArray)
	{
		$dataKeys = array_keys($dataArray);
		$dataValues = array_values($dataArray);

		foreach($dataValues[0] as $datetime => $value)
		{
			$dataDatetime[strtotime($datetime)] = $datetime;
		}

		$dataDatetimeTS = array_keys($dataDatetime);
		sort($dataDatetimeTS);

		foreach($dataDatetimeTS as $datetimeTS)
		{
			foreach($dataKeys as $dataKey)
			{
				$value = $dataArray[$dataKey][$dataDatetime[$datetimeTS]];
				$dataSort[$dataKey]['datetime'][$dataDatetime[$datetimeTS]] = $value;
				$dataSort[$dataKey]['datetimeTS'][$datetimeTS] = $value;
			}
		}
		return $dataSort;
	}

	public static function sortDatetimeT($data)
	{
		$dataSort = self::sortDateTime($data);
		return $dataSort['datetime'];
	}

	public static function sortDatetimeTS($data)
	{
		$dataSort = self::sortDateTime($data);
		return $dataSort['datetimeTS'];
	}

}

?>
