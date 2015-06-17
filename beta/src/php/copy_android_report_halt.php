<html>
<head>
	<link rel="StyleSheet" href="../css/newwindow.css">
	<script src="location_js/site.js" type="text/javascript"></script>
	<script src="http://www.google.com/uds/api?file=uds.js&amp;v=2.0&amp;key="AIzaSyA9SrKxfDId98hLt4eqlV0CjtvC0X7O4u4" type="text/javascript"></script>
	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
	<script type="text/javascript">
	/* <![CDATA[ */
	var map;
	var address2="";
	var xml_address_flag=0;
	window.onload = function() 
	{
		var latlng = new google.maps.LatLng(54.559322587438636, -4.1748046875);
		var options = 
		{
			zoom: 6,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map(document.getElementById("map"), options);
	}
    var splitData;
    var i=0;
	function Geocode(data)
	{
		//alert("data="+data);
		// split data
		i=0;
		document.getElementById("geocodedPostcodes").value = "";
			//alert("data1="+data);
		//var data = document.getElementById("postcodes").value;
		//var data = <?php echo "'".$point."'"; ?>;
		//alert(data);
		splitData = data.split(":");
		GeocodeNext();        
	}
	var separator = ",";
	var delay = 0;
	function GeocodeNext()
	{
		//alert("in geocode=");
		//$('#progress').html((i+1).toString() + " of " + splitData.length);
		var geocoder = new google.maps.Geocoder();
		var splitLatLng = splitData[i].replace("\r", "").split(",");
		// if no commas, try tab
		if (splitLatLng.length == 1) 
		{
			splitLatLng = splitData[i].replace("\r", "").split("\t");
			separator = "\t";
		}
		else
		{
			separator = ",";
			var latLng = new google.maps.LatLng(splitLatLng[0], splitLatLng[1]);
			geocoder.geocode({'latLng': latLng}, function(results, status) 
			{
				var foundAddress = false;
				if (status == google.maps.GeocoderStatus.OK) 
				{
					if (results) 
					{						
						var user_lat = "";
						var user_lng = "";
						var google_lat = "";
						var google_lng = "";
						var distance = "";
						//getAddressComponent(result, "country");			
						for (var j=0; j<results.length; j++) 
						{ 							
							if ((results[j].types[0] == 'street_address') || (results[j].types[0] == 'route')) 
							{
								//alert(results[j].geometry.location.lat());
								user_lat = latLng.lat();
								user_lng = latLng.lng();
								//alert("user_lat="+user_lat+"user_lng="+user_lng);
								
								google_lat = results[j].geometry.location.lat();
								google_lng = results[j].geometry.location.lng();
								//alert("google_lat="+user_lat+"google_lng="+google_lng);
								var distance = calculate_distance(user_lat, google_lat, user_lng, google_lng); 
								//alert("user_lat="+distance);
								//alert("address="+results[j].formatted_address);
								//alert("user_lat="+user_lat+" ,user_lng="+user_lng+" ,google_lat="+google_lat+" ,google_lng="+google_lng+" ,dist="+distance);
								//var address2 = distance+" km from "+address1;	
								addLocation(latLng, results[j], distance,user_lat,user_lng);
								foundAddress = true;
								break;
							}
						}
						if (!foundAddress) 
						{
							xml_address_flag=1;
							var user_lat = latLng.lat();
							var user_lng = latLng.lng();
							var distance="";
							var address="";
							addResult(latLng, address, errorMsg,distance,user_lat,user_lng);
						}
					}
				}
				else 
				{
					var errorMsg = "Unknown error";
					switch (status) 
					{
						case google.maps.GeocoderStatus.ZERO_RESULTS : errorMsg = "No results"; break;
						case google.maps.GeocoderStatus.OVER_QUERY_LIMIT : errorMsg = "Over query limit"; break;
						case google.maps.GeocoderStatus.REQUEST_DENIED : errorMsg = "Request denied"; break;
						case google.maps.GeocoderStatus.INVALID_REQUEST : errorMsg = "Invalid request"; break;
					}
					if (status != google.maps.GeocoderStatus.OVER_QUERY_LIMIT) 
					{
						// alert("in if");
						xml_address_flag=1;
						var user_lat = latLng.lat();
						var user_lng = latLng.lng();
						var distance="";
						var address="";
						addResult(latLng, address, errorMsg,distance,user_lat,user_lng);
					}
				}
				if (i < splitData.length-1 || (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT)) 
				{
					//alert("status="+status+"GeocoderStatus="+google.maps.GeocoderStatus.OVER_QUERY_LIMIT);
					if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT)
					{
						//alert("delay="+delay);
						delay += 100;
					}
					else 
					{
						
						i++;
						if (delay > 0)
						delay -= 100;
					}
					//alert("Delay is " + delay.toString());
					//$('#delay').html("Delay is " + delay.toString() + " ms");
					//$('#delay').html("Delay is " + delay.toString() + " ms");
					setTimeout("GeocodeNext()", delay);
				}
				else 
				{          
					//$('#progress').html("Complete");
					document.forms[0].submit();	
				}
			});
		}
    }

	function getAddressComponent(result, component) 
	{
		//alert("result="+result);
		for (var i=0; i<result.address_components.length; i++) 
		{
			var comp = result.address_components[i];
			for (j=0; j<comp.types.length; j++) 
			{
				if (comp.types[j] == component)
				return comp.long_name;
			}
		}
		return "";
	}
		
		function buildAddress(result) 
		{
			return getAddressComponent(result, "street_number") + separator + 
			getAddressComponent(result, "route") + separator +
			getAddressComponent(result, "postal_town") + separator +
			getAddressComponent(result, "administrative_area_level_2") + separator +
			getAddressComponent(result, "postal_code") + separator +
			getAddressComponent(result, "country");			
		}		
		function addLocation(latLng, result, distance,user_lat,user_lng) 
		{
			// add address components if selected
			var address = "\"" + result.formatted_address + "\"";
			//alert("addl="+address);
			//if ($("#addressComponents").is(":checked"))     //COMMENTED FOR TESTING
			//address = buildAddress(result);
			addResult(latLng, address, result.formatted_address, distance,user_lat,user_lng);
		}			
		function addResult(latLng, address, formattedAddress, distance , user_lat,user_lng) 
		{
			if(xml_address_flag==0)
			{
				if((address.indexOf("NH") ==-1) && (address.indexOf("National Highway") ==-1) && (address.indexOf("State Highway") ==-1) && (address.indexOf("SH") ==-1))
				{
					address2=distance + " km from " + address + ":"; 				
					 document.getElementById("geocodedPostcodes").value += address2;  		
				}
				else
				{
					 var strURL="android_get_location_tmp_file.php?point_test="+latLng;			
					var req = getXMLHTTP();
					req.open("GET", strURL, false); //third parameter is set to false here
					req.send(null);  
					var place_name_temp_param = req.responseText; 
					//alert("place_name_temp_param1="+place_name_temp_param);
					place_name_temp_param =place_name_temp_param.split(":");
					//alert("user_lat="+user_lat+"lat="+place_name_temp_param[1]+"user_lng="+user_lng+"log="+place_name_temp_param[2]);
					var distance = calculate_distance(user_lat, place_name_temp_param[1], user_lng, place_name_temp_param[2]);
					//alert("distance="+distance);
					address2 = distance+" km from "+place_name_temp_param[0]+ ":";	
					//alert("ifeslseaddress2="+address2);
					//alert("address_with_distance_xml="+address2)
					
					 document.getElementById("geocodedPostcodes").value += address2;  	
					/*if(account_id_session=="212")
					 {
						alert("xml_1eslseaddress2="+address2);
						alert("xml_1geocodedPostcodes="+document.getElementById("geocodedPostcodes").value);
					 }*/
				}
			}
			else if(xml_address_flag==1)
			{
				//alert("distance="+distance+"address2="+address2);
				//alert("latLng="+latLng);
				/*if(account_id_session=="212")
				 {
					alert("xml_latLng="+latLng);
				 }*/
				 var strURL="android_get_location_tmp_file.php?point_test="+latLng;
				//alert("strurl:"+strURL);
				var req = getXMLHTTP();
				req.open("GET", strURL, false); //third parameter is set to false here
				req.send(null);  
				var place_name_temp_param = req.responseText; 
				//alert("place_name_temp_param1="+place_name_temp_param);
				place_name_temp_param =place_name_temp_param.split(":");
				/*if(account_id_session=="212")
				 {
					alert("xml_user_lat="+user_lat+"lat="+place_name_temp_param[1]+"user_lng="+user_lng+"log="+place_name_temp_param[2]);
				 }*/
				//alert("user_lat="+user_lat+"lat="+place_name_temp_param[1]+"user_lng="+user_lng+"log="+place_name_temp_param[2]);
				var distance = calculate_distance(user_lat, place_name_temp_param[1], user_lng, place_name_temp_param[2]);
				//alert("distance="+distance);
				address2 = distance+" km from "+place_name_temp_param[0]+ ":";	
				//alert("ifeslseaddress2="+address2);
				//alert("address_with_distance_xml="+address2)
				
				 document.getElementById("geocodedPostcodes").value += address2; 
				/*if(account_id_session=="212")
				 {
					alert("xml_eslseaddress2="+address2);
					alert("xml_geocodedPostcodes="+document.getElementById("geocodedPostcodes").value);
				 }*/
			}
			
		}
    
		function calculate_distance(lat1, lat2, lon1, lon2) 
		{
			lat1 = (lat1/180)*Math.PI;
			lon1 = (lon1/180)*Math.PI;
			lat2 = (lat2/180)*Math.PI;
			lon2 = (lon2/180)*Math.PI;
			var delta_lat = lat2 - lat1;
			var delta_lon = lon2 - lon1;
			var temp = Math.pow(Math.sin(delta_lat/2.0),2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(delta_lon/2.0),2);
			var distance = 3956 * 2 * Math.atan2(Math.sqrt(temp),Math.sqrt(1-temp));
			distance = distance*1.609344;
			distance=Math.round(distance*100)/100;
			return distance;
		} 
		var http_request=false;
	function getXMLHTTP()
	{
		http_request=false;
		if (window.XMLHttpRequest)
		{
			http_request = new XMLHttpRequest();
		} 
		else if (window.ActiveXObject) 
		{
			http_request = new ActiveXObject("Microsoft.XMLHTTP");
		}
		return http_request;
	}
    /* ]]> */
  </script>  
</head>
<body>

<?php 
include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
include_once("android_common_xml_element.php");
//set_time_limit(300);
include_once("android_get_all_dates_between.php");
include_once("android_sort_xml.php");
include_once("android_get_location_lp_track_report.php");
include_once("android_calculate_distance.php");
include_once("android_check_with_range.php");
include_once("androidPointLocation.php");
//include_once("get_location.php");
include_once("util_android_hr_min_sec.php");
include_once("android_new_xml_string_io.php");

$v_size=count($vehicle_serial);
//$device_str= $_POST["vehicleserial_prev"];
$device_str="359231030420325:862170018371961#,";
$device_str = explode('#',$device_str);
//echo "device_str1=".$device_str[0]."<br>";
//echo "device_str2=".$device_str[1]."<br>";
$vserial = explode(':',$device_str[0]);
$iotype_element = explode(',',$device_str[1]);

/*$geo_id_str= $_REQUEST["geo_id"];
$geo_id1 = explode(':',$geo_id_str);*/


/*$date1 = $_REQUEST["start_date"];
$date2 =  $_REQUEST["end_date"];*/

$date1 = "2013/11/04 00:00:00";
$date2 = "2013/11/04 11:45:33";

$date1 = str_replace('/', '-', $date1);  
$date2 = str_replace('/', '-', $date2); 

$date_1 = explode(" ",$date1);
$date_2 = explode(" ",$date2);
$datefrom = $date_1[0];
$dateto = $date_2[0];
$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);

$current_date = date("Y-m-d");

$k1 = 0;
$m_start=0;

$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);
//$user_interval = $_POST['user_interval'];
$user_interval = "15";
//read_halt_xml($xml_path, &$imei, &$vname, &$lat, &$lng, &$arr_time, &$dep_time, &$in_temperature, &$out_temperature, &$duration);	

global $imei,$vname,$lat,$lng,$arr_time,$dep_time,$in_temperature,$out_temperature,$duration;
$imei=array();
$vname=array();
$lat=array();
$lng=array();
$arr_time=array();
$dep_time=array();
$in_temperature=array();
$out_temperature=array();
$duration=array();

for($i=0;$i<sizeof($vserial);$i++)
{  	
	//echo   "<br>vserial[i] =".$vserial[$i];
	$Query="SELECT vehicle.vehicle_name,vehicle.vehicle_type,vehicle.vehicle_number FROM vehicle USE INDEX(v_vehicleid_status)".
		",vehicle_assignment USE INDEX(va_vehicleid_status,va_vehicleid_imei_status) WHERE vehicle.vehicle_id=vehicle_assignment.".
		"vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_assignment.device_imei_no='$vserial[$i]'";
	$Result=mysql_query($Query,$DbConnection);
	$Row=mysql_fetch_row($Result);
	get_halt_xml_data($vserial[$i], $iotype_element[$i], $Row[0], $date1, $date2, $user_interval, $xmltowrite);
}
	

function get_halt_xml_data($vehicle_serial, $iotype_element_1 , $vname_local, $startdate,$enddate,$user_interval, $xmltowrite)
{
	global $imei,$vname,$lat,$lng,$arr_time,$dep_time,$in_temperature,$out_temperature,$duration;
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	$interval = $user_interval*60; 
	global $DbConnection;
	global $account_id;
	global $geo_id1;
	$halt_flag = 0;
	$fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;
	$linetowrite="";
	$firstdata_flag =0;
	$date_1 = explode(" ",$startdate);
	$date_2 = explode(" ",$enddate);

	$datefrom = $date_1[0];
	$dateto = $date_2[0];
	$timefrom = $date_1[1];
	$timeto = $date_2[1];

	get_All_Dates($datefrom, $dateto, &$userdates);
	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);

	for($i=0;$i<=($date_size-1);$i++)
	{
		//if($userdates[$i] == $current_date)
		//{	
		$xml_current = "../../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
    		
		if (file_exists($xml_current))      
		{		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = "../../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}		
		//echo "<br>xml in get_halt_xml_data =".$xml_file;	    	
		if (file_exists($xml_file)) 
		{			
			$t=time();
			$xml_original_tmp = "../../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
			//$xml_log = "../../../xml_tmp/filtered_xml/tmp_".$current_datetime1.".xml";
			//echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
			if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
				copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "<br>TWO<br>";
				$xml_unsorted = "../../../../xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
				//echo  "<br>".$xml_file." <br>".$xml_unsorted."<br><br>";
				copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
				SortFile($xml_unsorted, $xml_original_tmp,$userdates[$i]);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}
      
			$total_lines = count(file($xml_original_tmp));	     
			$xml = @fopen($xml_original_tmp, "r") or $fexist = 0; 		
			$logcnt=0;
			$DataComplete=false;                  
			$vehicleserial_tmp=null;      
			$f=0;      
			if (file_exists($xml_original_tmp)) 
			{
				set_master_variable($userdates[$i]);
				while(!feof($xml))          // WHILE LINE != NULL
				{
					$DataValid = 0;			
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE		
					//echo"<textarea>".$line."</textarea>";
					if(strlen($line)>20)
					{
						$linetmp =  $line;
					}  	
					
					$linetolog =  $logcnt." ".$line;
					$logcnt++;					
					//echo "vc=".$vc."<br>";
					if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
					{
						$format = 1;
						$fix_tmp = 1;
					} 
					//echo "vc=".$vc."<br>";					
					if(strpos($line,''.$vc.'="0"'))
					{
						$format = 1;
						$fix_tmp = 0;
					}
					else
					{
						$format = 2;
					}  
					//echo "vc=".$vd."ve=".$ve."<br>";
					if((preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{ 
						$lat_value = explode('=',$lat_match[0]);
						$lng_value = explode('=',$lng_match[0]);			       
						if((strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
						{
							$DataValid = 1;
						}
					} 
					
					if(($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
					{
						$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);						
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
						$xml_date = $datetime;
					}				
				         
					if($xml_date!=null)
					{

						if(($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
						{							           	
						               
							$status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
							if($status==0)
							{
							  continue;               
							}
							//echo "test6".'<BR>';
							$status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
							if($status==0)
							{
							  continue;
							}     
							
							$status = preg_match('/'.$vf.'="[^" ]+/', $line, $speed_tmp);
							if($status==0)
							{
							  continue;
							}  
							/*echo "vc=".$vd."ve=".$ve."<br>";
							$io_typ_value=explode(":",$iotype_element_1);
							$io_cnt=count($io_typ_value);
							echo "vc1=".$vd."ve1=".$ve."<br>";
							if($io_cnt>0)
							{
								for($j=0;$j<sizeof($io_typ_value);$j++)
								{
									$io_typ_value1=explode("^",$io_typ_value[$j]);
									$tmp_io="io".$io_typ_value1[0];	
									$tmp_io=get_io_to_new_method($userdates[$i],$old_xml_date,$tmp_io);	
									if($io_typ_value1[1]=="temperature")
									{
										$status = preg_match('/'.$tmp_io.'="[^" ]+/', $line, $temperature_tmp);																	
										$temperature_tmp1 = explode("=",$temperature_tmp[0]);
										$temperature = preg_replace('/"/', '',$temperature_tmp1[1]);							
									}																	
								}
							}*/
						//echo "vc2=".$vd."ve2=".$ve."<br>";
							if($firstdata_flag==0)
							{							
								$halt_flag = 0;
								$firstdata_flag = 1;								
								$vserial=$vehicle_serial;						
								$lat_tmp1 = explode("=",$lat_tmp[0]);							
								$lat_ref = preg_replace('/"/', '', $lat_tmp1[1]);
								$lng_tmp1 = explode("=",$lng_tmp[0]);
								$lng_ref = preg_replace('/"/', '', $lng_tmp1[1]);							
								$datetime_ref = $datetime;
								$tmp_ref = $temperature;						                 	
								$date_secs1 = strtotime($datetime_ref);						
								$date_secs1 = (double)($date_secs1 + $interval);  						           	
							}           	
						               	
							else
							{ 				              
								$lat_tmp1 = explode("=",$lat_tmp[0]);                           //  GET NEXT RECO
								$lat_cr = preg_replace('/"/', '', $lat_tmp1[1]);
								$lng_tmp1 = explode("=",$lng_tmp[0]);
								$lng_cr = preg_replace('/"/', '', $lng_tmp1[1]);
								$datetime_cr = $datetime;
								$tmp_cr = $temperature;																	
								$date_secs2 = strtotime($datetime_cr);						
								calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);							
								if($distance > 0.150)
								{									
									if ($halt_flag == 1)
									{
										$arrivale_time=$datetime_ref;
										$tmp_arr=$tmp_ref;
										$starttime = strtotime($datetime_ref);									  
										$stoptime = strtotime($datetime_cr);
										$depature_time=$datetime_cr;
										$tmp_dep=$tmp_cr;									
										$halt_dur =  ($stoptime - $starttime);										
										if($halt_dur >= $interval)
										{
											if((sizeof($geo_id1)>0) && ($geo_id1[0]!="") )
											{                                                                                            
												$exclude_flag = 1;
												$geo_status = 1;
												for($j=0;$j<sizeof($geo_id1);$j++)
												{                                                                                                    
													include('android_halt_exclusion.php');
													if($geo_coord!="")
													{                
														check_with_range($lat_ref, $lng_ref, $geo_coord, &$geo_status);
														//echo "<Br>geo_status1:".$geo_status;                                        
													}
													if($geo_status == 1)
													{
														$exclude_flag = 0;
													}                                     
												}										
												if(($geo_status==false) && ($exclude_flag==1))
												{ 
													if($tmp_arr=="" && $tmp_dep=="")
													{
														$tmp_arr="0.0";
														$tmp_dep="0.0";
													}
													else
													{
														if($tmp_arr<-30 || $tmp_arr>70)
														{
															$tmp_arr="-";
														}
														if($tmp_dep<-30 || $tmp_dep>70)
														{
															$tmp_dep="-";
														}
													}
													$imei[]=$vserial;
													$vname[]=$vname_local;
													$lat[]=$lat_ref;
													$lng[]=$lng_ref;
													$arr_time[]=$arrivale_time;
													$dep_time[]=$depature_time;
													$in_temperature[]=$tmp_arr;
													$out_temperature[]=$tmp_dep;
													$duration[]=$halt_dur;  

													$date_secs1 = strtotime($datetime_cr);
													$date_secs1 = (double)($date_secs1 + $interval);										
												}  // IF STATUS  
											} // SIZE OF GEO_ID
											else
											{												
												if($tmp_arr=="" && $tmp_dep=="")
												{
													$tmp_arr="0.0";
													$tmp_dep="0.0";
												}
												else
												{
													if($tmp_arr<-30 || $tmp_arr>70)
													{
														$tmp_arr="-";
													}
													if($tmp_dep<-30 || $tmp_dep>70)
													{
														$tmp_dep="-";
													}
												}
												$imei[]=$vserial;
												$vname[]=$vname;
												$lat[]=$lat_ref;
												$lng[]=$lng_ref;
												$arr_time[]=$arrivale_time;
												$dep_time[]=$depature_time;
												$in_temperature[]=$tmp_arr;
												$out_temperature[]=$tmp_dep;
												$duration[]=$halt_dur;

												$date_secs1 = strtotime($datetime_cr);
												$date_secs1 = (double)($date_secs1 + $interval);                              
											}                       
										}		// IF TOTAL MIN										
									}   //IF HALT FLAG
									$lat_ref = $lat_cr;
									$lng_ref = $lng_cr;
									$datetime_ref= $datetime_cr;
									$tmp_ref= $tmp_cr;
									

									$halt_flag = 0;
								}
								else if(((strtotime($datetime_cr)-strtotime($datetime_ref))>60) && ($halt_flag != 1))
								{            			
									//echo "<br>normal flag set "." datetime_cr ".$datetime_cr."<br>";
									
									$halt_flag = 1;
								}
							}
						} // $xml_date_current >= $startdate closed
					}   // if xml_date!null closed
					$f++;
				}   // while closed	
			} // if original_tmp closed       
			fclose($xml);            
			unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 

	if ($halt_flag == 1)
	{
		$arrivale_time=$datetime_ref;
		$tmp_arr=$tmp_ref;
		$starttime = strtotime($datetime_ref);	  
		$stoptime = strtotime($datetime_cr);
		$depature_time=$datetime_cr;
		$tmp_dep=$tmp_cr;	
		$halt_dur =  ($stoptime - $starttime);
		
		if((sizeof($geo_id1)>0) && ($geo_id1[0]!="") )
		{                                                                                            
			$exclude_flag = 1;
			$geo_status = 1;
			for($j=0;$j<sizeof($geo_id1);$j++)
			{ 
				$query_geo = "SELECT geo_coord FROM geofence WHERE user_account_id='$account_id' AND geo_id='$geo_id1[$j]' AND status=1";                    
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
				
				if($geo_coord!="")
				{                
					check_with_range($lat_ref, $lng_ref, $geo_coord, &$geo_status);
					//echo "<Br>geo_status1:".$geo_status;                                        
				}
				if($geo_status == 1)
				{
					$exclude_flag = 0;
				}                                    
			}	// FOR LOOP
		
			if(($geo_status==false) && ($exclude_flag==1))
			{
				if($tmp_arr=="" && $tmp_dep=="")
				{
					$tmp_arr="0.0";
					$tmp_dep="0.0";
				}
				else
				{
					if($tmp_arr<-30 || $tmp_arr>70)
					{
						$tmp_arr="-";
					}
					if($tmp_dep<-30 || $tmp_dep>70)
					{
						$tmp_dep="-";
					}
				}
				$imei[]=$vserial;
				$vname[]=$vname_local;
				$lat[]=$lat_ref;
				$lng[]=$lng_ref;
				$arr_time[]=$arrivale_time;
				$dep_time[]=$depature_time;
				$in_temperature[]=$tmp_arr;
				$out_temperature[]=$tmp_dep;
				$duration[]=$halt_dur;

				$date_secs1 = strtotime($datetime_cr);
				$date_secs1 = (double)($date_secs1 + $interval);
				//break;
			}  // IF STATUS  
		} // SIZE OF GEO_ID
		else
		{			
			if($tmp_arr=="" && $tmp_dep=="")
			{
				$tmp_arr="0.0";
				$tmp_dep="0.0";
			}
			else
			{
				if($tmp_arr<-30 || $tmp_arr>70)
				{
					$tmp_arr="-";
				}
				if($tmp_dep<-30 || $tmp_dep>70)
				{
					$tmp_dep="-";
				}
			}
			$imei[]=$vserial;
			$vname[]=$vname_local;
			$lat[]=$lat_ref;
			$lng[]=$lng_ref;
			$arr_time[]=$arrivale_time;
			$dep_time[]=$depature_time;
			$in_temperature[]=$tmp_arr;
			$out_temperature[]=$tmp_dep;
			$duration[]=$halt_dur; 
			$date_secs1 = strtotime($datetime_cr);
			$date_secs1 = (double)($date_secs1 + $interval);                              
		}                       										
	}   //IF HALT FLAG
	//echo "Test1";
	fclose($fh);
	//fclose($xmllog);
}    
?>
	                                                                                                                                                    
<?php
//echo''; 
echo'<form method="post" action="android_report_halt_1.php" target="_self">  
		<!--<div id="progress"></div>-->
		<div id="delay"></div>';		
		
		$place_name_arr=array();
		
		
		unlink($xml_path);
	
			$size = sizeof($imei);
			//echo "<br>size:".$size; 
			$point = '"';
			$imei_str="";
			$vname_str="";
			$lat_str="";
			$lng_str="";
			$arr_time_str="";
			$dep_time_str="";
			$in_tmp_str="";
			$out_tmp_str="";
			$duration_str="";
			for($i=0;$i<$size;$i++)
			{			  	
				$imei_str=$imei_str.$imei[$i].":";
				$vname_str=$vname_str.$vname[$i].":";
				$lat_str=$lat_str.$lat[$i].":";
				$lng_str=$lng_str.$lng[$i].":";
				$arr_time_str=$arr_time_str.$arr_time[$i].",";
				$dep_time_str=$dep_time_str.$dep_time[$i].",";
				$in_tmp_str=$in_tmp_str.$in_temperature[$i].",";
				$out_tmp_str=$out_tmp_str.$out_temperature[$i].",";			
				$duration_str=$duration_str.$duration[$i].":";
				$lat[$i] = substr($lat[$i], 0, -1);
				$lng[$i] = substr($lng[$i], 0, -1);

				$coord = $lat[$i].",".$lng[$i]; 
				if($i==0)
				{
					$point = $point.$coord;   
				}
				else
				{
					$point = $point.":".$coord;   
				}
			}
			//echo "imei1=".$imei_str."<br>vname1=".$vname_str."<br>lat1=".$lat_str."<br>lng1=".$lng_str."<br>arr_time1=".$arr_time1."<br>dep_time_str=".$dep_time_str."<br>duration_str=".$duration_str;
		echo'<!--<textarea id="geocodedPostcodes" name="geocodedPostcodes" cols="40" rows="20" style="visibility:hidden;"></textarea>-->
			<!--<textarea id="geocodedPostcodes" name="geocodedPostcodes" cols="4" rows="1" style="visibility:hidden;"></textarea>-->
			<input type="hidden" id="geocodedPostcodes" name="geocodedPostcodes">
			<input type="hidden" name="imei_prev" value="'.$imei_str.'">
			<input type="hidden" name="vname_prev" value="'.$vname_str.'">
			<input type="hidden" name="lat_prev" value="'.$lat_str.'">
			<input type="hidden" name="lng_prev" value="'.$lng_str.'">
			<input type="hidden" name="arr_time_prev" value="'.$arr_time_str.'">
			<input type="hidden" name="dep_time_prev" value="'.$dep_time_str.'">
			<input type="hidden" name="in_tmp_prev" value="'.$in_tmp_str.'">
			<input type="hidden" name="out_tmp_prev" value="'.$out_tmp_str.'">
			<input type="hidden" name="duration_prev" value="'.$duration_str.'">
			<input type="hidden" name="user_interval" value="'.$user_interval.'">			
			<input type="hidden" name="date1" value="'.$date1.'">
			<input type="hidden" name="date2" value="'.$date2.'">';
			//$_SESSION['place_name_halt_arr1'] = $place_name_arr;
			$point = $point.'"';
			//echo "<br>pt=".$point;  			
				call_geocode($point);

			function call_geocode($point)
			{          
			echo'<script type="text/javascript">          
					Geocode('.$point.'); 
				</script>';
			}
/*echo'<script type="text/javascript" language="javascript">
		document.forms[0].submit();	
	</script>';*/			
	echo'</form>'; 	
?>	
</body>
</html>				
