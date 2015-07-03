<?php
    include_once('util_session_variable.php');
    include_once('util_php_mysql_connectivity.php');
    if(file_exists($file_path))
    {
        unlink($file_path);
        echo "report_delete_file##".$tr_id;

    }
    else
    {
        echo "failure";
    }
?>
