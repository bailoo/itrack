<?php

	/***
	* Prints Cassandra query results in HTML 
	*
	* @param array		Results of CQL
	*
	* @return		TRUE 
	*
	*/
	function printHTML($st_results)
	{
	
		echo "\n";
		echo 'Printing Top 10 rows:'."\n";
		
		echo '<table style="width:100%">';
		echo '<tr>';
		echo '<td>imeih</td>';
		echo '<td>DateTime</td>';
		echo '<td>Data</td>';
		echo'</tr>';
		
		foreach ($st_results as $row){
			echo '<tr>';
			foreach($row as $key=>$value){
					echo '<td>';
					echo $value;
					echo '</td>';
			}
			echo '</tr>';
		}
		
		echo'</table>';
	}

	/***
	* Runs CQL query on Cassandra datastore
	* 
	* @param object $o_cassandra	Cassandra object 
	* @param string $imei	IMEI
	* @param string $date	YYYY-MM-DD
	* 
	* @return array 	Results of the query 
	*/
	function dbQueryLastSeen($o_cassandra,$imei,$date)
	{
		$s_cql = "SELECT * FROM lastlog 
			  WHERE 
			  imei = '$imei'
			  AND day <= '$date'
			  ORDER BY day DESC
			  LIMIT 1
			  ;";
	
		$st_results = $o_cassandra->query($s_cql);
		return $st_results;
	}


	/***
	* Parses and Converts array returned by CQL to object
	*
	* @param array		Results of CQL
	* @param array		Param filter	
	* @param boolean	Full or Last Seen 
	*
	* @return object	Object with names of entities
	*
	* return json_decode(json_encode($st_results),FALSE);
	*/
	function gpsParser($st_results,$params,$datatype)
	{
		$st_obj = new stdClass();			
		$full_params = array('a','b','c','d','e','f','i','j','k','l','m','n','o','p','q','r','ci','ax','ay','az','mx','my','mz','bx','by','bz');
		$last_params = array('a','b','c','d','e','f','h','i','j','k','l','m','n','o','p','q','r','s','t','u','ci','ax','ay','az','mx','my','mz','bx','by','bz');
		$gps_params = ($datatype)?$full_params:$last_params;

		$num = 0;
		foreach ($st_results as $row)
		{
			$st_obj->$num = new stdClass;
			$st_obj->$num->g = date('Y-m-d@H:i:s',$row['stime']/1000-19800);	// device time is stored as row key as timestamp in milisecond
			if ($datatype) $st_obj->$num->h = $row['dtime'];

			$i = 0;
			foreach (str_getcsv($row['data'], ";") as $gps_val)
			{
				if (in_array($gps_params[$i], $params))
				{
					$st_obj->$num->$gps_params[$i] = $gps_val;
				}
				$i++;
			}
			$num++;
		}
		
		return $st_obj;
	}
	
	/***
	* Runs CQL query on Cassandra datastore
	* 
	* @param object $o_cassandra	Cassandra object 
	* @param string $imei	IMEI
	* @param string $date	YYYY-MM-DD
	* @param string $hour	HH
	* 
	* @return array 	Results of the query 
	*/
	function dbQueryDateHour($o_cassandra,$imei,$date,$HH)
	{
		$s_cql = "SELECT * FROM log 
			  where 
			  imeih = '$imei@$date@$HH'
			;";//imeih = '862170018323731@2015-01-01@23'
		$st_results = $o_cassandra->query($s_cql);// Launch the query
		return $st_results;
	}

	/***
	* Returns the list of imeih for different days 
	*
	* @param string $imei		IMEI
	* @param string $dateminute1	YYYY-MM-DD-HH-MM
	* @param string $dateminute2	YYYY-MM-DD-HH-MM
	* 
	* @return string	imeih list	
	*
	*/
	function getImeihlist($imei,$dateminute1,$dateminute2)
	{	
		$date1 = substr($dateminute1,0,10);
		$date2 = substr($dateminute2,0,10);
		$HH1 = substr($dateminute1,11,2);
		$HH2 = substr($dateminute2,11,2);
	
		$interval = new DateInterval('P1D');
		$start = new DateTime($date1);		
		$end = new DateTime($date2);
		$end->add($interval);	

		$period = new DatePeriod($start, $interval, $end);
		$imeih_list = "(";
		foreach ($period as $date)
		{
			$startHH = ($date1 == $date->format('Y-m-d'))?$HH1:0;
			$endHH = ($date2 == $date->format('Y-m-d'))?$HH2:23;
			for($i=$startHH; $i <= $endHH; $i++)
			{
				$hour = (strlen($i) < 2)?'0'.$i:$i;
				$imeih_list .= "'".$imei.'@'.$date->format('Y-m-d').'@'.$hour."',";
			}
		}
		$imeih_list = substr($imeih_list,0,-1) . ")";
		
		return $imeih_list;
	}	

	/***
	* Runs CQL query on Cassandra datastore
	* 
	* @param object $o_cassandra	Cassandra object 
	* @param string $imei		IMEI
	* @param string $dateminute1	YYYY-MM-DD-HH-MM
	* @param string $dateminute2	YYYY-MM-DD-HH-MM
	* 
	* @return array 	Results of the query 
	*/
	function dbQueryDateTimeSlice($o_cassandra,$imei,$dateminute1,$dateminute2)
	{
		/* same hour */	
		if (substr($dateminute1,0,13) == substr($dateminute2,0,13))
		{	
			$date = substr($dateminute1,0,10);
			$HH = substr($dateminute1,11,2);
			$MM1 = substr($dateminute1,14,2);
			$MM2= substr($dateminute2,14,2);
			//echo "date = $date\n hh = $HH\n mm1 = $MM1 \n mm2 = $MM2\n";
			$s_cql = "SELECT * FROM log 
				where 
			  	imeih = '$imei@$date@$HH'
				and
				dtime >= '$date $HH:$MM1:00'
				and
				dtime < '$date $HH:$MM2:00'
				;";
			$st_results = $o_cassandra->query($s_cql);// Launch the query
			return $st_results;
		}
		/* same day */
		elseif (substr($dateminute1,0,10) == substr($dateminute2,0,10))
		{
			$date = substr($dateminute1,0,10);
			$HH1 = substr($dateminute1,11,2);
			$HH2 = substr($dateminute2,11,2);
			$MM1 = substr($dateminute1,14,2);
			$MM2 = substr($dateminute2,14,2);
			//echo "date = $date\n hh1 = $HH1\n hh2 = $HH2\n mm1 = $MM1 \n mm2 = $MM2\n";

			$s_cql1 = "SELECT * FROM log 
				where 
			  	imeih = '$imei@$date@$HH1'
				and
				dtime >= '$date $HH1:$MM1:00'
				and
				dtime <= '$date $HH1:59:59'
				;";
			$st_results1 = $o_cassandra->query($s_cql1);// Launch the query
			$st_results = $st_results1;
			//echo "done 1\n";			
			
			if ($HH2 - $HH1 > 1)
			{		
				$imeih_list = "(";
				for($i=$HH1+1;$i<$HH2;$i++)
				{
					$hour = (strlen($i) < 2)?'0'.$i:$i;
					$imeih_list .= "'".$imei.'@'.$date.'@'.$hour."',";
				}
				$imeih_list = substr($imeih_list,0,-1) . ")";
				//echo "imeih_list = $imeih_list\n";

				$s_cql2 = "SELECT * FROM log
					where
					imeih IN $imeih_list
					;";
				$st_results2 = $o_cassandra->query($s_cql2);// Launch the query
				$st_results = array_merge($st_results, $st_results2);
				//echo "done 2\n";			
			}			

			$s_cql3 = "SELECT * FROM log 
				where 
			  	imeih = '$imei@$date@$HH2'
				and
				dtime >= '$date $HH2:00:00'
				and
				dtime <= '$date $HH2:$MM2:59'
				;";
			$st_results3 = $o_cassandra->query($s_cql3);// Launch the query
			$st_results = array_merge($st_results, $st_results3);

			return $st_results; 
		}
		/* different days */
		else
		{
			$imeih_list = getImeihlist($imei,$dateminute1,$dateminute2);
			//echo $imeih_list;
			$s_cql2 = "SELECT * FROM log
				where
				imeih IN $imeih_list
				;";
			$st_results = $o_cassandra->query($s_cql2);// Launch the query

			return $st_results; 
			//echo "done 1\n";			
			

		}
	}


?>
