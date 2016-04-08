<?php
require_once ('lib/nusoap.php'); 

$lat_f = array(array());                        									
$lng_f = array(array());
$speed_f = array(array());
$datetime_f = array(array());
$common_arr = array(array());

$test="";

function get_gps($userid, $passwd_encrypted, $imei_arr) 
{
	global $lat_f;                        									
	global $lng_f;
	global $speed_f;
	global $datetime_f;
	//global $common_arr;
	global $test;
	
	$HOST = "localhost";
	$DBASE = "iespl_vts_beta";
	$USER = "root";
	$PASSWD = "mysql";
	//echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
	$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
	mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
	
	//$userid = "demo";
	//$passwd = "fe01ce2a7fbac8fafaed7c982a04e229";
	//$passwd_encrypted = "fe01ce2a7fbac8fafaed7c982a04e229";
	
	$query1 = "SELECT account_id FROM account WHERE user_id='$userid' AND password='$passwd_encrypted' AND status=1";
	$result1 = mysql_query($query1, $DbConnection);
	if($row1 = mysql_fetch_object($result1))
	{
		$account_id = $row1->account_id;
	}

	for($i=0;$i<sizeof($imei_arr);$i++)
	{
		$device_str.= $imei_arr[$i].",";
	}
	$device_str = substr($device_str, 0, -1);
	
	$query2 = "SELECT vehicle_assignment.device_imei_no FROM vehicle_assignment,vehicle_grouping WHERE ".
			"vehicle_assignment.device_imei_no IN($device_str) AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
			"vehicle_grouping.account_id = '$account_id' AND vehicle_assignment.status=1 AND vehicle_grouping.status=1";
	$result2 = mysql_query($query2,$DbConnection);

	while($row=mysql_fetch_object($result2))
	{
		$imei_db[] = $row->device_imei_no;
	}
	
	//$imei_db[] = '862170014373920';
	//$imei_db[] = '862170018317427';

	$back_dir = "/var/www/html/itrack_vts";

	for($i=0;$i<sizeof($imei_db);$i++)
	{		
		$common_arr1 = get_vehicle_last_data($imei_db[$i],$i);
		//$imei_temp = "".$imei_db[$i];
		$common_arr2[] = $common_arr1;
	}
	$common_arr3[$userid]=$common_arr2;	
	//global $lat_f;                        									
	//global $lng_f;
	//global $speed_f;
	//global $datetime_f;
	
	/*for($i=0;$i<sizeof($imei_db);$i++)
	{
		$imei_str = array("IMEI" => array($imei_db[$i]));
		for($j=0;$j<sizeof($lat_f[$imei_db[$i]]);$i++)
		{
			$lat_f.$lng_f.$speed_f.$datetime_f)
		}
	}*/
	return $common_arr3;
	//$arr_tmp[] = "hello"; 
	//return $arr_tmp;	
	//return array("UserID" => $param1, "IMEI" => array($param3[0]));
}
 
$server = new soap_server();
$namespace = "http://www.itracksolution.co.in/web_service_soap/gps_info/index.php";
	$server->wsdl->schemaTargetNamespace = $namespace;
	$server->configureWSDL("get_gps");
// $server ->configureWSDL('server', 'urn:server'); //this line causes to 'no result'
// $server ->wsdl->schemaTargetNamespace = 'urn:server'; //this line causes to 'no result'

$server->register("get_gps");
$server->service($HTTP_RAW_POST_DATA);
//####################################

//########### GET LAST LOCATION INFORMATION
function get_vehicle_last_data($imei,$i)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	//global $common_arr;
	$temp_arr = array();
	global $lat_f;                        									
	global $lng_f;
	global $speed_f;
	global $datetime_f;	
	global $test;
	//date_default_timezone_set('Asia/Calcutta');
	$current_time = date('Y-m-d H:i:s');
	$current_date_this = date('Y-m-d');
	
	$xml_file = "/var/www/html/vts/xml_vts/xml_last/".$imei.".xml";
	
	/*if (file_exists($xml_file))
	{
		$test="true".$xml_file;
	}
	else
	{
		$test="false".$xml_file;
	}*/
	
	$file = file_get_contents($xml_file);
	if(!strpos($file, "</t1>")) 
	{
		usleep(1000);
	}		 
	$t=time();
	$rno = rand();			
	$xml_original_tmp = "/var/www/html/vts/xml_tmp/original_xml/tmp_".$imei."_".$t."_".$rno.".xml";		
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
		//set_master_variable($current_date_this);
		$va="a";
		$vb="b";
		$vc="c";
		$vd="d";
		$ve="e";
		$vf="f";
		$vg="g";
		$vh="h";
		$vi="i";
		$vj="j";
		$vk="k";
		$vl="l";
		$vm="m";
		$vn="n";
		$vo="o";
		$vp="p";
		$vq="q";
		$vr="r";
		$vs="s";
		$vt="t";
		$vu="u";
		$vv="v";
		$vw='w';
		$vx='x';
		$vy='y';
		$vz="z";
		$vaa="aa";
		$vab="ab";
		while(!feof($fp)) 
		{
			$line = fgets($fp);
			//echo "line=".$line;
			$c++;				
			//echo"vd=".$vd;
			if(strlen($line)>15)
			{
				if ( (preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
				{
					$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
					$datetime_tmp1 = explode("=",$datetime_tmp[0]);
					$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
					$xml_date = $datetime;
					
					$status = preg_match('/'.$vf.'="[^"]+/', $line, $speed_tmp);
					$speed_tmp1 = explode("=",$speed_tmp[0]);
					$speed = preg_replace('/"/', '', $speed_tmp1[1]);
					
					$status = preg_match('/'.$vd.'="[^"]+/', $line, $lat_tmp);
					$lat_tmp1 = explode("=",$lat_tmp[0]);
					$lat = preg_replace('/"/', '', $lat_tmp1[1]);
					
					$status = preg_match('/'.$ve.'="[^"]+/', $line, $lng_tmp);
					$lng_tmp1 = explode("=",$lng_tmp[0]);
					$lng = preg_replace('/"/', '', $lng_tmp1[1]);
								
					/*$status = preg_match('/'.$vs.'="[^"]+/', $line, $day_max_speed_tmp);
					$day_max_speed_tmp1 = explode("=",$day_max_speed_tmp[0]);
					$day_max_speed = preg_replace('/"/', '', $day_max_speed_tmp1[1]);

					$status = preg_match('/'.$vt.'="[^"]+/', $line, $day_max_speed_time_tmp);
					$day_max_speed_time_tmp1 = explode("=",$day_max_speed_time_tmp[0]);
					$day_max_speed_time = preg_replace('/"/', '', $day_max_speed_time_tmp1[1]);

					$status = preg_match('/'.$vu.'="[^"]+/', $line, $last_halt_time_tmp);
					$last_halt_time_tmp1 = explode("=",$last_halt_time_tmp[0]);
					$last_halt_time = preg_replace('/"/', '', $last_halt_time_tmp1[1]);*/                                                                 
																										 
					$xml_date_sec = strtotime($xml_date);
					$last_halt_time_sec = strtotime($last_halt_time);			
					$current_time_sec = strtotime($current_time);
					
					/*$lat_f[$imei][] = $lat;                        									
					$lng_f[$imei][] = $lng;
					$speed_f[$imei][] = $speed;
					$datetime_f[$imei][] = $xml_date;*/
					$temp_arr['IMEI']= $imei; 
					$temp_arr['LAT']= $lat;                        									
					$temp_arr['LNG']=$lng;
					$temp_arr['SPEED']=$speed;
					$temp_arr['DEVICE_TIME']=$xml_date;
					//echo "line2=".$line2;
					//$line2 = "\n".$line.' w="'.$vname.'" y="'.$vtype.'" aa="'.$status.'"/>';                          									
				}																			
			}			
		}		
		//echo "<br>pathtowrite1:".$pathtowrite."<br>";			
		 fclose($fp);
		 unlink($xml_original_tmp);							
	}
	return $temp_arr;
}
?>
