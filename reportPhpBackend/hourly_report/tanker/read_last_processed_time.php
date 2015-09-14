<?php
function read_last_processed_time($type,$route_type)
{	
    global $DbConnection;
    global $account_id;
    global $IMEI;
    global $last_processed_time;

	echo "\nSIZE_IMEI1=".sizeof($IMEI);
    $IMEI_uniq = array_unique($IMEI);
	echo "\nSIZE_IMEI2=".sizeof($IMEI_uniq);
    
    foreach($IMEI_uniq as $imei) {
        $query = "SELECT last_time FROM last_processed_time WHERE account_id='$account_id' AND imei='$imei' AND type='$type' AND routetype='$route_type'";
	echo "\nQUERY=".$query;
        $result = mysql_query($query,$DbConnection);
        while($row = mysql_fetch_object($result)) {
		$tmp_time = $row->last_time;
	 	if($tmp_time=='0000-00-00 00:00:00'){
			$tmp_time = '';
		}
            $last_processed_time[$imei] = $tmp_time;
		echo "\nLastTimeQuery=".$tmp_time;
        }
    }
}
?>
