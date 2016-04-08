<?php

// include_once('UtilLib.php');
include_once('src/php/lib/UtilLib.php');

class VTS
{
	public static $time_bin_size = 600; // 10 minute average
	public static $xml_root = "/var/www/html/vts/xml";
	public static $xml_dirs = array("xml_current", "xml_past");

	public static function getFuelData($imei, $date_start, $date_end, $iter = 0)
	{
		$count_min = 45; // ignore time bin if data points are less than 45
		$xml_root = self::$xml_root;
		$xml_dirs = self::$xml_dirs;

		// UTIL::debug("Iteration # : " . $iter);
		// UTIL::debug("IMEI        : " . $imei);
		// UTIL::debug("Date Start  : " . $date_start);
		// UTIL::debug("Date End    : " . $date_end);
		// UTIL::debug("Count Min   : " . $count_min);
		// UTIL::debug("Count Min   : " . $count_min);

		if($iter > 7) { return array();}

		if($imei=="" || $date_start=="" || $date_end=="") {return array();}

		$io8_min = 50;
		$io8_max = 999;

		if($imei == "359231030206633")
		{
			$io8_to_fuel[50] = 35;
			$io8_to_fuel[650] = 5;
		}
		else
		{
			$io8_to_fuel[203] = 15;
			$io8_to_fuel[925] = 350;
		}

		$date_list = UTIL::get_All_Dates($date_start, $date_end);
		// UTIL::debug("Total Dates : " . sizeof($date_list));

		if(sizeof($date_list)<=0) {return array();}

		foreach($date_list as $i => $date)
		{
			// UTIL::debug($date . " ");
			foreach($xml_dirs as $xml_dir)
			{
				$file = $xml_root . "/" . $xml_dir . "/" . $date . "/" . $imei . ".xml";
				// UTIL::debug("XML Path " . $file . " ...");
				if(file_exists($file))
				{
					// UTIL::debug("Reading " . $file . " ...");
					// UTIL::debug_no_nl($date . " ");
					$xml = @fopen($file, "r");
					if($xml)
					{
						while(!feof($xml))
						{
							$line = fgets($xml, 4096);
							if(strpos($line,"marker"))
							{
								$io8 = UTIL::get_xml_data('/io8="[^"]+"/', $line);
								$datetime = UTIL::get_xml_data('/datetime="[^"]+"/', $line);
		
								if(($io8 > $io8_min) && ($io8 < $io8_max))
								{
									$bin_time = self::getDateTimeBin($datetime);
									$io8_count[$bin_time] ++;
									$io8_sum[$bin_time] += $io8;
								}
							}
						}
					}
					fclose($xml);
				}
			}
		}

		if(sizeof($io8_count) > 0)
		{
			// UTIL::debug("Synthesizing All Data ...");
			foreach($io8_count as $key => $value)
			{
				if($value >= $count_min)
				{
					$io8_avg[$key] = $io8_sum[$key] / $io8_count[$key];
					$fuel_avg[$key] = UTIL::round(UTIL::linear_fit_x2y($io8_avg[$key], $io8_to_fuel),0);
				}
			}
		}

		if($iter==0)
		{
			$edge_time = $date_list[0] . " 00:00:00";
		}
		else
		{
			$edge_time = $date_list[sizeof($date_list)-1] . " 23:59:59";
		}
		// UTIL::debug("Edge Time   : " . $edge_time);

		$edge_fuel = self::interpolateFuelData($fuel_avg, $edge_time);

		if($edge_fuel == null)
		{
			$date_past = date("Y-m-d", strtotime($date_start) - (60 * 60 * 24));
			// UTIL::debug("Date Past   : " . $date_past);
			$fuel_avg_past = self::getFuelData($imei, $date_past, $date_past, ($iter + 1));
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
		$time_list = UTIL::get_All_Bin_Edges($date_start." 00:00:00", $date_end." 23:59:59", $time_bin_size);
		return $time_list;
	}

	public static function getSpeedData($imei, $datetime_start, $datetime_end)
	{
		$xml_root = self::$xml_root;
		$xml_dirs = self::$xml_dirs;

		// UTIL::debug("IMEI        : " . $imei);
		// UTIL::debug("Time Start  : " . $datetime_start);
		// UTIL::debug("Time End    : " . $datetime_end);

		if($imei=="" || $datetime_start=="" || $datetime_end=="") {return array();}

		$date_list = UTIL::get_All_Dates(substr($datetime_start,0,10), substr($datetime_end,0,10));
		// UTIL::debug("Total Dates : " . sizeof($date_list));

		$speed_min = 0;
		$speed_max = 250;

		if(sizeof($date_list)<=0) {return array();}

		foreach($date_list as $i => $date)
		{
			foreach($xml_dirs as $xml_dir)
			{
				$file = $xml_root . "/" . $xml_dir . "/" . $date . "/" . $imei . ".xml";
				if(file_exists($file))
				{
					// UTIL::debug("Reading " . $file . " ...");
					// UTIL::debug_no_nl($date . " ");
					$xml = @fopen($file, "r");
					if($xml)
					{
						while(!feof($xml))
						{
							$line = fgets($xml, 4096);
							if(strpos($line,"marker"))
							{
								$speed = UTIL::get_xml_data('/speed="[^"]+"/', $line);
								$datetime = UTIL::get_xml_data('/datetime="[^"]+"/', $line);

								if(($speed > $speed_min) && ($speed < $speed_max))
								{
									$speed_data[$datetime] = $speed;
								}
							}
						}
					}
					fclose($xml);
				}
			}
		}
		// UTIL::debug("Speed Data  : " . sizeof($speed_data));

		return $speed_data;
	}

	public static function getDeviceDistanceData($imei, $datetime_start, $datetime_end)
	{
		$xml_root = self::$xml_root;
		$xml_dirs = self::$xml_dirs;

		// UTIL::debug("IMEI        : " . $imei);
		// UTIL::debug("Time Start  : " . $datetime_start);
		// UTIL::debug("Time End    : " . $datetime_end);

		if($imei=="" || $datetime_start=="" || $datetime_end=="") {return array();}

		$date_list = UTIL::get_All_Dates(substr($datetime_start,0,10), substr($datetime_end,0,10));
		// UTIL::debug("Total Dates : " . sizeof($date_list));

		if(sizeof($date_list)<=0) {return array();}

		foreach($date_list as $i => $date)
		{
			foreach($xml_dirs as $xml_dir)
			{
				$file = $xml_root . "/" . $xml_dir . "/" . $date . "/" . $imei . ".xml";
				if(file_exists($file))
				{
					// UTIL::debug("Reading " . $file . " ...");
					// UTIL::debug_no_nl($date . " ");
					$xml = @fopen($file, "r");
					if($xml)
					{
						while(!feof($xml))
						{
							$line = fgets($xml, 4096);
							if(strpos($line,"marker"))
							{
								$distance = UTIL::get_xml_data('/distance="[^"]+"/', $line);
								$datetime = UTIL::get_xml_data('/datetime="[^"]+"/', $line);

								$distance_data[$datetime] = $distance;
							}
						}
					}
					fclose($xml);
				}
			}
		}
		// UTIL::debug("Dist. Data  : " . sizeof($distance_data));

		return $distance_data;
	}

	public static function getIO8Data($imei, $datetime_start, $datetime_end)
	{
		$xml_root = self::$xml_root;
		$xml_dirs = self::$xml_dirs;

		// UTIL::debug("IMEI        : " . $imei);
		// UTIL::debug("Time Start  : " . $datetime_start);
		// UTIL::debug("Time End    : " . $datetime_end);

		if($imei=="" || $datetime_start=="" || $datetime_end=="") {return array();}

		$date_list = UTIL::get_All_Dates(substr($datetime_start,0,10), substr($datetime_end,0,10));
		// UTIL::debug("Total Dates : " . sizeof($date_list));

		$io8_min = 0;
		$io8_max = 999;

		if(sizeof($date_list)<=0) {return array();}

		foreach($date_list as $i => $date)
		{
			foreach($xml_dirs as $xml_dir)
			{
				$file = $xml_root . "/" . $xml_dir . "/" . $date . "/" . $imei . ".xml";
				if(file_exists($file))
				{
					// UTIL::debug("Reading " . $file . " ...");
					// UTIL::debug_no_nl($date . " ");
					$xml = @fopen($file, "r");
					if($xml)
					{
						while(!feof($xml))
						{
							$line = fgets($xml, 4096);
							if(strpos($line,"marker"))
							{
								$io8 = UTIL::get_xml_data('/io8="[^"]+"/', $line);
								$datetime = UTIL::get_xml_data('/datetime="[^"]+"/', $line);

								if(($io8 > $io8_min) && ($io8 < $io8_max))
								{
									$io8_data[$datetime] = $io8;
								}
							}
						}
					}
					fclose($xml);
				}
			}
		}
		// UTIL::debug("IO8 Data    : " . sizeof($io8_data));

		return $io8_data;
	}

	public static function getVoltageData($imei, $datetime_start, $datetime_end)
	{
		$xml_root = self::$xml_root;
		$xml_dirs = self::$xml_dirs;

		// UTIL::debug("IMEI        : " . $imei);
		// UTIL::debug("Time Start  : " . $datetime_start);
		// UTIL::debug("Time End    : " . $datetime_end);

		if($imei=="" || $datetime_start=="" || $datetime_end=="") {return array();}

		$date_list = UTIL::get_All_Dates(substr($datetime_start,0,10), substr($datetime_end,0,10));
		// UTIL::debug("Total Dates : " . sizeof($date_list));

		if(sizeof($date_list)<=0) {return array();}

		foreach($date_list as $i => $date)
		{
			foreach($xml_dirs as $xml_dir)
			{
				$file = $xml_root . "/" . $xml_dir . "/" . $date . "/" . $imei . ".xml";
				if(file_exists($file))
				{
					// UTIL::debug("Reading " . $file . " ...");
					// UTIL::debug_no_nl($date . " ");
					$xml = @fopen($file, "r");
					if($xml)
					{
						while(!feof($xml))
						{
							$line = fgets($xml, 4096);
							if(strpos($line,"marker"))
							{
								$voltage = UTIL::get_xml_data('/sup_v="[^"]+"/', $line);
								$datetime = UTIL::get_xml_data('/datetime="[^"]+"/', $line);

								$voltage_data[$datetime] = $voltage;
							}
						}
					}
					fclose($xml);
				}
			}
		}
		// UTIL::debug("Voltage Data: " . sizeof($voltage_data));

		return $voltage_data;
	}

	public static function getSatelliteData($imei, $datetime_start, $datetime_end)
	{
		$xml_root = self::$xml_root;
		$xml_dirs = self::$xml_dirs;

		// UTIL::debug("IMEI        : " . $imei);
		// UTIL::debug("Time Start  : " . $datetime_start);
		// UTIL::debug("Time End    : " . $datetime_end);

		if($imei=="" || $datetime_start=="" || $datetime_end=="") {return array();}

		$date_list = UTIL::get_All_Dates(substr($datetime_start,0,10), substr($datetime_end,0,10));
		// UTIL::debug("Total Dates : " . sizeof($date_list));

		if(sizeof($date_list)<=0) {return array();}

		foreach($date_list as $i => $date)
		{
			foreach($xml_dirs as $xml_dir)
			{
				$file = $xml_root . "/" . $xml_dir . "/" . $date . "/" . $imei . ".xml";
				if(file_exists($file))
				{
					// UTIL::debug("Reading " . $file . " ...");
					// UTIL::debug_no_nl($date . " ");
					$xml = @fopen($file, "r");
					if($xml)
					{
						while(!feof($xml))
						{
							$line = fgets($xml, 4096);
							if(strpos($line,"marker"))
							{
								$satellite = UTIL::get_xml_data('/no_of_sat="[^"]+"/', $line);
								$datetime = UTIL::get_xml_data('/datetime="[^"]+"/', $line);

								$satellite_data[$datetime] = $satellite;
							}
						}
					}
					fclose($xml);
				}
			}
		}
		// UTIL::debug("GPS Sat Data: " . sizeof($satellite_data));

		return $satellite_data;
	}


}


?>
