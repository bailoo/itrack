<?php
	$file = "setup/mysql.php"; if(file_exists($file)) { include_once($file); }
	$file = "../../setup/mysql.php"; if(file_exists($file)) { include_once($file); }
        $file = "../../../setup/mysql.php"; if(file_exists($file)) { include_once($file); }
//	$HOST = "itrackdb.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
        //$HOST = "nimbumirchi.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
        $HOST = "localhost";
	// $DBASE = "iespl_vts_beta";
	// $USER = "root";
	// $PASSWD = "mysql";
	//$HOST = "db.itracksolution.co.in";
	//$HOST = "111.118.181.156";
	//$HOST = "111.118.182.147";
	
        $con = mysqli_connect($HOST,$USER,$PASSWD,$DBASE);


?>
