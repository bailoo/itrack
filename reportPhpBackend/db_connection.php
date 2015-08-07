<?php
    $DBASE = "iespl_vts_beta";
    if ($DEBUG_OFFLINE) {
        $USER = "root";
        $HOST = "localhost";
        $PASSWD = "mysql";
    } else {
        $USER = "bailoo";
        $HOST = "itrackdb.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
         $PASSWD = 'neon04$VTS';
    }
?>
