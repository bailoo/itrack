<?php
function read_last_processed_time($type,$route_type)
{	
    global $account_id;
    global $IMEI;
    global $last_processed_time;
    
    $IMEI_uniq = array_unique($IMEI);
    
    foreach($IMEI_uniq as $imei) {
        $query = "SELECT last_time FROM last_processed_time WHERE account_id='$account_id' AND imei='$imei' AND type='$type' AND routetype='$route_type'";
        $result = mysql_query($query,$DbConnection);
        while($row = mysql_fetch_object($result)) {
            $last_processed_time[$imei] = $row->last_time;
        }
    }
}
?>