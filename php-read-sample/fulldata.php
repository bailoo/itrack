<?php

	require_once 'Cassandra/Cassandra.php';
	
	
	$o_cassandra = new Cassandra();
	
	$s_server_host     = '127.0.0.1';    // Localhost
	$i_server_port     = 9042; 
	$s_server_username = '';  // We don't have username
	$s_server_password = '';  // We don't have password
	$s_server_keyspace = 'gps';  
	
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	
	
	$s_cql = "SELECT * FROM gps_full_data 
			  where 
			  imei = 'satuimei'
              and date_hour in ('2015-02-10 12:00:00');";
	
	// Launch the query
	$st_results = $o_cassandra->query($s_cql);
	echo 'Printing Top 10 rows:'."\n";
	
	echo '<table style="width:100%">';
	echo '<tr>';
	echo '<td>imei</td>';
	echo '<td>Date & Hour</td>';
	echo '<td>Device Time</td>';
	echo '<td>Lat</td>';
	echo '<td>Lon</td>';
	echo'</tr>';
	
	foreach ($st_results as $row){
		echo '<tr>';
		foreach($row as $key=>$value){
			if($key == 'imei' || $key == 'lat' || $key == 'lon'){
				echo '<td>';
				echo $value;
				echo '</td>';
			}else if($key == 'date_hour'){
				echo '<td>';
				$val_without_ms = $value/1000;
				echo date('d/m/y H:i:s',$val_without_ms);
				echo '</td>';
			}else if($key == 'device_time'){
				echo '<td>';
				$val_without_ms = $value/1000;
				echo date('d/m/y H:i:s',$val_without_ms);
				echo '</td>';
			}
		}
		echo '</tr>';
	}
	
	echo'</table>';
	
	
	/*
	echo '<td>Device Time</td>';
	echo '<td>Server Time</td>';
	echo '<td>Message Type</td>';
	echo '<td>Signal strength</td>';
	echo '<td>Speed</td>';
	echo '<td>Supply Voltage</td>';
	echo '<td>Fix</td>';
	echo '<td>Version</td>';
	echo '<td>io One</td>';
	echo '<td>io Two</td>';
	echo '<td>io Three</td>';
	echo '<td>io Four</td>';
	echo '<td>io Five</td>';
	echo '<td>io Six</td>';
	echo '<td>io Seven</td>';
	echo '<td>io Eight</td>';*/
	/*
	foreach ($st_results as $row){
		echo '<tr>'
	
				foreach($row as $key=>$value){
			echo '<td>'.$value.'</td>';
		}
	
		echo'</tr>';
	}
	*/
	
	$o_cassandra->close();
	
	