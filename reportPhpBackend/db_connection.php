<?php
echo "DB";
	if($DB_test){
		$DBASE = "itrack_test";
	}else {
	    	$DBASE = "iespl_vts_beta";
	}

    if ($DEBUG_OFFLINE) {
        //$USER = "bailoo";
        //$HOST = "itrackdb.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
        //$PASSWD = 'neon04$VTS';

        $USER = "nimbumirchidb";
        $HOST = "nimbumirchi.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
        $PASSWD = 'nm#db0516';
 
    } else {
        $USER = "nimbumirchidb";
        $HOST = "nimbumirchi.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
        $PASSWD = 'nm#db0516';
    }
?>
