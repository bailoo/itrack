<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

set_time_limit(400);
//include_once("get_all_dates_between.php");
//include_once("sort_xml.php");
include_once("get_location.php");
include_once("calculate_distance.php");

$group_id1 = $_REQUEST['groupid'];
$user_id1 = $_REQUEST['userid'];
$password1 = $_REQUEST['password'];
$imeis1 = $_REQUEST['imeis'];

$DEBUG = 0;

//#### VALIDATE USER #######//
$query = "SELECT account_id FROM account WHERE account_id IN(SELECT account_id FROM account_feature WHERE field23=1) AND ".
          " user_id='$user_id1' AND password='$password1' AND group_id='$group_id1' AND status=1";
echo $query;
$result = mysql_query($query,$DbConnection);
$numrows = mysql_num_rows($result);

if($numrows)
{
  $imei_arr = explode(',',$imeis1);
  
  echo "#RESPONSE#UserID-$user_id1 ";  
      
  for($i=0;$i<sizeof($imei_arr);$i++)
  {
    $query = "SELECT vehicle_name FROM vehicle WHERE ".
    " vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$imei_arr[$i]' AND status=1) AND status=1";
    //echo $query;
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $vname = $row->vehicle_name;
  	get_vehicle_last_data($imei_arr[$i], $vname);
  }
}
else
{
  echo "Error:Unauthorized user";
}
   
function get_vehicle_last_data($imei, $vname)
{
  //date_default_timezone_set('Asia/Calcutta');
  $current_time = date('Y-m-d H:i:s');	
  $xml_file = "../../../xml_vts/xml_last/".$imei.".xml";
	 
	if (file_exists($xml_file)) 
	{		
    //echo "<br>exist1";		
		$t=time();
    $xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$imei."_".$t."_".$i.".xml";

    copy($xml_file,$xml_original_tmp);
	
		if (file_exists($xml_original_tmp))
		{
			//echo "<br>exist2";			
      $fexist =1;
			$fp = fopen($xml_original_tmp, "r") or $fexist = 0;   
			$total_lines =0;
			$total_lines = count(file($xml_original_tmp));

			//echo "<br>total_lines=".$total_lines;
			$c =0;
			// read some data
            
      while(!feof($fp)) 
			{
				$line = fgets($fp);
			
				if ( (preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) && (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
				{
            $status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
            $datetime_tmp1 = explode("=",$datetime_tmp[0]);
            $datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
            $xml_date = $datetime;
            
            $status = preg_match('/last_halt_time="[^"]+/', $line, $last_halt_time_tmp);
            $last_halt_time1 = explode("=",$last_halt_time_tmp[0]);
            $last_halt_time = preg_replace('/"/', '', $last_halt_time1[1]);              
            
            $status = preg_match('/speed="[^"]+/', $line, $speed_tmp);
            $speed_tmp1 = explode("=",$speed_tmp[0]);
            $speed = preg_replace('/"/', '', $speed_tmp1[1]);
            
            $status = preg_match('/lat="[^"]+/', $line, $lat_tmp);
            $lat_tmp1 = explode("=",$lat_tmp[0]);
            $lat = preg_replace('/"/', '', $lat_tmp1[1]);
            
            $status = preg_match('/lng="[^"]+/', $line, $lng_tmp);
            $lng_tmp1 = explode("=",$lng_tmp[0]);
            $lng = preg_replace('/"/', '', $lng_tmp1[1]);
            
            $alt="0";
            get_location($lat,$lng,$alt,&$place,$DbConnection);
            
            echo " & vbCrLf &";
            echo ' #IMEI~'.$imei.'^Vehicle~'.$vname.'^CurrentTime~'.$datetime.'^Lat~'.$lat.'^Lng~'.$lng.'^LastHaltTime~'.$last_halt_time.'^Location~'.$place;                                                       
				}																			
		 }			
		 fclose($fp);
		 unlink($xml_original_tmp);							
		}	
	}
}		 
?>
