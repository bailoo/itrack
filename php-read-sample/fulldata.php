<?php

	require_once 'Cassandra/Cassandra.php';
	
	
	$o_cassandra = new Cassandra();
	
	$s_server_host     = '52.74.33.255';    // Localhost
	$i_server_port     = 9042; 
	$s_server_username = '';  // We don't have username
	$s_server_password = '';  // We don't have password
	$s_server_keyspace = 'gps';  
	
	$o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
	
	
	$s_cql = "SELECT * FROM full_data 
			  where 
			  imeih = '862170011627815@2015-2-17@0';";//make sure the imeih exist in cassandra
	
	// Launch the query
	$st_results = $o_cassandra->query($s_cql);
	
	echo 'Execution time: '.$i_execution_time."\n";
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
	
	
	$o_cassandra->close();
	
	