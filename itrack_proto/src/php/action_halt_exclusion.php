<?php
  //echo "<br>Halt exclusion";
  
  /*$query_geo = "SELECT geo_coord FROM geofence WHERE geo_id IN(SELECT geo_id FROM geo_assignment WHERE status=1 AND ".
                "vehicle_id IN (SELECT vehicle_id FROM vehicle_assignment WHERE device_imei_no='$vehicle_serial' AND status=1)) AND ".
                "user_account_id='$account_id' AND geo_id='$geo_id1[$j]' AND status=1";*/
                
  $query_geo = "SELECT geo_coord FROM geofence WHERE user_account_id='$account_id' AND geo_id='$geo_id1[$j]' AND status=1";                    
    	
  //echo "<br>".$query_geo;
  
  $res_geo = mysql_query($query_geo,$DbConnection);
	if($row_geo = mysql_fetch_object($res_geo))
	{
		$geo_coord_tmp = $row_geo->geo_coord;
		$geo_coord = base64_decode($geo_coord_tmp);
		
    $geo_coord = str_replace('),(',' ',$geo_coord);
    $geo_coord = str_replace('(','',$geo_coord);
    $geo_coord = str_replace(')','',$geo_coord);
    $geo_coord = str_replace(', ',',',$geo_coord);
	}   	

?>