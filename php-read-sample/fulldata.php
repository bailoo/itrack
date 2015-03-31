<?php

	require_once 'Cassandra/Cassandra.php';
	
	$o_cassandra = new Cassandra();
	
	$s_server_host     = '52.74.33.255';    // Localhost
	$i_server_port     = 9042; 
	$s_server_username = '';  // We don't have username
	$s_server_password = '';  // We don't have password
	$s_server_keyspace = 'gps';  
	
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	
	
	$imei = '862170018323731';
	$date = '2015-01-01';
	$HH = '23';
	$dateminute1 = '2015-01-01-20-00';
	$dateminute2 = '2015-01-01-21-00';
	//echo "dateminute1 = $dateminute1\n dateminute2 = $dateminute2\n";
	//make sure the imeih exist in cassandra
	//$st_results = DBQueryDateHour($o_cassandra,$imei,$date,$HH);
	$st_results = DBQueryDateTimeSlice($o_cassandra,$imei,$dateminute1,$dateminute2);
	$st_obj = Parser($st_results);
	print_r($st_obj);

	// echo 'Execution time: '.$i_execution_time."\n";
	/*echo "\n";
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
	*/
	
	$o_cassandra->close();

	/***
	* Parses and Converts array returned by CQL to object
	*
	* @param array		Results of CQL
	*
	* @return object	Object with names of entities
	* return json_decode(json_encode($st_results),FALSE);
	*
	*/
	function Parser($st_results)
	{
		$st_obj = new stdClass();			
		$gps_params = array('a','b','c','d','e','f','g','i','j','k','l','m','n','o','p','q','r');

		$num = 0;
		foreach ($st_results as $row)
		{
			$st_obj->$num = new stdClass;
			$st_obj->$num->h = $row['dtime'];	// device time is stored as row key

			$i = 0;
			foreach (str_getcsv($row['data'], ";") as $gps_val)
			{
				$st_obj->$num->$gps_params[$i++] = $gps_val;
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
	function DBQueryDateHour($o_cassandra,$imei,$date,$HH)
	{
		$s_cql = "SELECT * FROM full_data 
			  where 
			  imeih = '$imei@$date@$HH'
			;";//imeih = '862170018323731@2015-01-01@23'
		$st_results = $o_cassandra->query($s_cql);// Launch the query
		return $st_results;
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
	function DBQueryDateTimeSlice($o_cassandra,$imei,$dateminute1,$dateminute2)
	{
		/* same hour */	
		if (substr($dateminute1,0,13) == substr($dateminute2,0,13))
		{	
			$date = substr($dateminute1,0,10);
			$HH = substr($dateminute1,11,2);
			$MM1 = substr($dateminute1,14,2);
			$MM2= substr($dateminute2,14,2);
			//echo "date = $date\n hh = $HH\n mm1 = $MM1 \n mm2 = $MM2\n";
			$s_cql = "SELECT * FROM full_data 
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
		elseif (substr($dateminute1,0,11) == substr($dateminute2,0,11))
		{
			$date = substr($dateminute1,0,10);
			$HH1 = substr($dateminute1,11,2);
			$HH2 = substr($dateminute2,11,2);
			$MM1 = substr($dateminute1,14,2);
			$MM2 = substr($dateminute2,14,2);
			//echo "date = $date\n hh1 = $HH1\n hh2 = $HH2\n mm1 = $MM1 \n mm2 = $MM2\n";

			$s_cql1 = "SELECT * FROM full_data 
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
					$hour = ($i < 10)?'0'.$i:$i;
					$imeih_list .= "'".$imei.'@'.$date.'@'.$hour."',";
				}
				$imeih_list = substr($imeih_list,0,-1) . ")";
				//echo "imeih_list = $imeih_list\n";

				$s_cql2 = "SELECT * FROM full_data
					where
					imeih IN $imeih_list
					;";
				$st_results2 = $o_cassandra->query($s_cql2);// Launch the query
				$st_results = array_merge($st_results, $st_results2);
				//echo "done 2\n";			
			}			

			$s_cql3 = "SELECT * FROM full_data 
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
	}
