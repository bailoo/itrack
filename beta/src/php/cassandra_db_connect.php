<?php
    require_once 'Cassandra/Cassandra.php';
    $o_cassandra = new Cassandra();
    $s_server_host = '52.74.33.255'; // Localhost
    //$s_server_host = '52.74.144.159'; // Localhost
    $i_server_port = 9042;
    $s_server_username = ''; // We don't have username
    $s_server_password = ''; // We don't have password
    $s_server_keyspace = 'gps';
    $o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
?>