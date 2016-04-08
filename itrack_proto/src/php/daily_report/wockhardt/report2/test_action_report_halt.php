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
		$('#progress').html((i+1).toString() + " of " + splitData.length);
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
					 var strURL="get_location_tmp_file.php?point_test="+latLng;			
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
				 var strURL="get_location_tmp_file.php?point_test="+latLng;
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
echo '
<table class="processrequest" id="processrequestid">
	<tr>
		<td>
			Process Request Please Wait......
		</td>
	</tr>
</table>';
?>
<?php 
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];
//set_time_limit(300);
include_once("get_all_dates_between.php");
include_once("sort_xml.php");
include_once("get_location_lp_track_report.php");
include_once("calculate_distance.php");
include_once("read_filtered_xml.php");
include_once("area_violation/check_with_range.php");
include_once("area_violation/pointLocation.php");

//include_once("get_location.php");

include_once("util.hr_min_sec.php");


$DEBUG = 0;
$v_size=count($vehicle_serial);
if($DEBUG) echo "vsize=".$v_size;

$device_str= $_POST["vehicleserial_prev"];
$device_str = explode('#',$device_str);
//echo "device_str1=".$device_str[0]."<br>";
//echo "device_str2=".$device_str[1]."<br>";
$vserial = explode(':',$device_str[0]);
$iotype_element = explode(',',$device_str[1]);

$geo_id_str= $_REQUEST["geo_id"];
$geo_id1 = explode(':',$geo_id_str);


$date1 = $_REQUEST["start_date"];
$date2 =  $_REQUEST["end_date"];

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
$user_interval = $_POST['user_interval'];

for($i=0;$i<sizeof($vserial);$i++)
{
	$vehicle_info=get_vehicle_info($root,$vserial[$i]);
	$vehicle_detail_local=explode(",",$vehicle_info);	
	$vname[$i] = $vehicle_detail_local[0];
}

$current_dt = date("Y_m_d_H_i_s");	
$xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";
write_halt_report_xml($vserial, $iotype_element, $vname, $date1, $date2, $user_interval, $xmltowrite);

function write_halt_report_xml($vserial, $iotype_element, $vname, $startdate, $enddate, $user_interval, $xmltowrite)
{ 

	$maxPoints = 1000;
	$file_exist = 0;
	$fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
	fwrite($fh, "<t1>");  
	fclose($fh);
	//$i=0;
	for($i=0;$i<sizeof($vserial);$i++)
	{  	
		//echo   "<br>vserial[i] =".$vserial[$i];
		get_halt_xml_data($vserial[$i], $iotype_element[$i], $vname[$i], $startdate,$enddate,$user_interval, $xmltowrite);
		//echo   "t2".' '.$i;
	}
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}

function get_halt_xml_data($vehicle_serial, $iotype_element_1 , $vname, $startdate,$enddate,$user_interval, $xmltowrite)
{
	//echo "vserial=".$vehicle_serial." iotype_element_1=".$iotype_element_1."<br>";
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
	$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append
	for($i=0;$i<=($date_size-1);$i++)
	{
		//if($userdates[$i] == $current_date)
		//{	
		$xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
    		
		if (file_exists($xml_current))      
		{		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = "../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}		
		//echo "<br>xml in get_halt_xml_data =".$xml_file;	    	
		if (file_exists($xml_file)) 
		{			
			$t=time();
			$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
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
				$xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
				//echo  "<br>".$xml_file." <br>".$xml_unsorted."<br><br>";
				copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
				SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}
      
			$total_lines = count(file($xml_original_tmp));
			//echo "<br>Total lines orig=".$total_lines;      
			$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
			//$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
			$logcnt=0;
			$DataComplete=false;                  
			$vehicleserial_tmp=null;      
			$f=0;      
			if (file_exists($xml_original_tmp)) 
			{      
				while(!feof($xml))          // WHILE LINE != NULL
				{
					$DataValid = 0;
					//echo fgets($file). "<br />";
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE	
					//echo "line=".$line."<br>";
					//echo '<textarea>'.$line.'</textarea>'; 
					if(strlen($line)>20)
					{
						$linetmp =  $line;
					}  				
					$linetolog =  $logcnt." ".$line;
					$logcnt++;
					//fwrite($xmllog, $linetolog);  
					if(strpos($line,'Fix="1"'))     // RETURN FALSE IF NOT FOUND
					{
						$format = 1;
						$fix_tmp = 1;
					}                
					if(strpos($line,'Fix="0"'))
					{
						$format = 1;
						$fix_tmp = 0;
					}
					else
					{
						$format = 2;
					}  				
					if((preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{ 
						$lat_value = explode('=',$lat_match[0]);
						$lng_value = explode('=',$lng_match[0]);
						//echo " lat_value=".$lat_value[1];         
						if((strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
						{
							$DataValid = 1;
						}
					} 
					//echo"datavalid=".$DataValid; 
					//if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
					if(($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
					{
						//preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
						//echo "<br>str3tmp[0]=".$str3tmp[0];
						$status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
						//echo "datetmp=".$datetime_tmp[0]."<br>";
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);	
						$xml_date = $datetime;
					}				
				         
					if($xml_date!=null)
					{				  
						//echo "<br>xml_date=".$xml_date.",startdate=".$startdate.",enddate=".$enddate.",DataValid=".$DataValid;
						//$lat = $lat_value[1] ;
						//$lng = $lng_value[1];

						if(($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
						{							           	
							//echo "<br>One";             
							$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
							//echo "Status=".$status.'<BR>';
							//echo "test1".'<BR>';
							if($status==0)
							{
								continue;
							}
							//echo "<textarea>".$line."</textarea>"; 
							//$line = '<marker msgtype="NORMAL" vehicleserial="354776033544005" vehiclename="SJ DKL-4005" ver="v1.34" fix="1" lat="26.60652N" lng="80.20758E" alt="123.0" speed="0.000000" datetime="2011-01-25 03:16:23" fuel="-" vehicletype="Heavy" no_of_sat="09" cellname="" distance="0.0" io1="7" io2="6" io3="387" io4="10" io5="6" io6="6" io7="4" io8="2" sig_str="0" sup_v="0.000000" speed_a="0" geo_in_a="0" geo_out_a="0" stop_a="0" move_a="0" lowv_a="0"/>';  
							/*$status = preg_match('/vehiclename="[^"]+/', $line, $vehiclename_tmp);          
							//echo "<br>vname=".$vehiclename_tmp[0];
							if($status==0)
							{
							  continue;
							} */                
							$status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
							if($status==0)
							{
							  continue;               
							}
							//echo "test6".'<BR>';
							$status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
							if($status==0)
							{
							  continue;
							}     
							
							$status = preg_match('/speed="[^" ]+/', $line, $speed_tmp);
							if($status==0)
							{
							  continue;
							}  
						
							$io_typ_value=explode(":",$iotype_element_1);
							//echo " iotype_element_1=".$iotype_element_1."<br>";							
							for($j=0;$j<sizeof($io_typ_value);$j++)
							{
								$io_typ_value1=explode("^",$io_typ_value[$j]);
								$tmp_io="io".$io_typ_value1[0];										
								if($io_typ_value1[1]=="temperature")
								{
									//echo " io=".$io_typ_value1[0]."<br>";
									$status = preg_match('/io'.$io_typ_value1[0].'="[^" ]+/', $line, $temperature_tmp);
									//echo " temperature_tmp=".$temperature_tmp."<br>";								
									$temperature_tmp1 = explode("=",$temperature_tmp[0]);
									$temperature = preg_replace('/"/', '',$temperature_tmp1[1]);
									//echo " temperature=".$temperature."<br>";
								}																	
							}
						
							//echo "test4".'<BR>';
							/*$status = preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $datetime_tmp);
							if($status==0)
							{
								continue;
							}*/ 

							// HALT LOGIC   /////////////                             
							//echo "<br>first=".$firstdata_flag;
							if($firstdata_flag==0)
							{
								//echo "<br>FirstData";
								$halt_flag = 0;
								$firstdata_flag = 1;
								$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
								$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);
								//$vehiclename_tmp1 = explode("=",$vehiclename_tmp[0]);
								//$vname = preg_replace('/"/', '', $vehiclename_tmp1[1]);
								//echo "<br>vname=".$vehiclename_tmp1[1];
								$lat_tmp1 = explode("=",$lat_tmp[0]);
								//echo "<br>lat_tmp=".$lat_tmp[0]." ,lat_tmp1=".$lat_tmp1;
								$lat_ref = preg_replace('/"/', '', $lat_tmp1[1]);
								$lng_tmp1 = explode("=",$lng_tmp[0]);
								$lng_ref = preg_replace('/"/', '', $lng_tmp1[1]);
								//$datetime_tmp1 = explode("=",$datetime_tmp[0]);
								$datetime_ref = $datetime;								
								
								$tmp_ref = $temperature;
								//echo "<br>datetime_ref=".$datetime_ref." ,dt0=".$datetime_tmp[0];                  	
								$date_secs1 = strtotime($datetime_ref);
								//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
								$date_secs1 = (double)($date_secs1 + $interval);  
								//echo "<br>lat_tmp=".$lat_tmp[0]." ,lat_tmp1=".$lat_tmp1[1];             	
							}           	
							//echo "<br>k2=".$k2."<br>";                	
							else
							{           
								//echo "<br>Next";               
								$lat_tmp1 = explode("=",$lat_tmp[0]);                           //  GET NEXT RECO
								$lat_cr = preg_replace('/"/', '', $lat_tmp1[1]);
								$lng_tmp1 = explode("=",$lng_tmp[0]);
								$lng_cr = preg_replace('/"/', '', $lng_tmp1[1]);

								//$datetime_tmp1 = explode("=",$datetime_tmp[0]);
								$datetime_cr = $datetime;
								$tmp_cr = $temperature;
								//$time2 = $datetime[$i][$j];											
								$date_secs2 = strtotime($datetime_cr);
								//echo "<br>str=".$lat_ref.", ".$lat_cr.", ".$lng_ref." ,".$lng_cr.", ". $datetime_cr;
								calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);
								//echo "<br>distance=".$distance."<br>";
								//if( ($distance > 0.200) || ($f== $total_lines-2) )
								///if(($distance > 0.150) || ($f == $total_lines-10) )
								if($distance > 0.150)
								{
									//echo "<br>In dist ".$distance." lat_ref ".$lat_ref." lng_ref ".$lng_ref." lat_cr ".$lat_cr." lng_cr ".$lng_cr." datetime_cr ".$datetime_cr." datetime_ref ".$datetime_ref."<br>";
									if ($halt_flag == 1)
									{				
										//echo "<br>In Halt1";
										//echo "<br>datetime_ref=".$datetime_ref;
										$arrivale_time=$datetime_ref;
										$tmp_arr=$tmp_ref;
										$starttime = strtotime($datetime_ref);
										//$stoptime = strtotime($datetime_cr);  
										$stoptime = strtotime($datetime_cr);
										$depature_time=$datetime_cr;
										$tmp_dep=$tmp_cr;
										//echo "<br>".$starttime." ,".$stoptime;
										$halt_dur =  ($stoptime - $starttime);
										/*$halt_dur =  ($stoptime - $starttime)/3600;
										$halt_duration = round($halt_dur,2);										
										$total_min = $halt_duration * 60;
										$total_min1 = $total_min;
										//echo "<br>toatal_min=".$total_min1."user-interval=".$user_interval;
										$hr = (int)($total_min / 60);
										$minutes = $total_min % 60;	
											$hrs_min = $hr.".".$minutes; */
										//if( ($total_min1 >= $user_interval) || ($f== $total_lines-2))
										//echo "<br>halt_dur=".$halt_dur." ,interval=".$interval;
										if($halt_dur >= $interval)
										{
											//echo "<br>In Halt2";
											//echo "<br>sizeof geoc:".sizeof($geo_id);                            
											//echo "<br> geo_id:size:".sizeof($geo_id1)." ,geoid=".$geo_id1[0];
											if((sizeof($geo_id1)>0) && ($geo_id1[0]!="") )
											{                                                                                            
												$exclude_flag = 1;
												$geo_status = 1;
												for($j=0;$j<sizeof($geo_id1);$j++)
												{                                                                                                    
													include('action_halt_exclusion.php');
													if($geo_coord!="")
													{                
														check_with_range($lat_ref, $lng_ref, $geo_coord, &$geo_status);
														//echo "<Br>geo_status1:".$geo_status;                                        
													}
													if($geo_status == 1)
													{
														$exclude_flag = 0;
													}                                                                                          
													//echo "<br>geo_status=".$geo_status;                                
													//echo "<br>geo_id[i]=".$geo_id1[$j]." ,geo_status=".$geo_status;                                      
												}	// FOR LOOP
												//echo "<BR>geo_status2:".$geo_status." ,exclude_flag:".$exclude_flag;
												if(($geo_status==false) && ($exclude_flag==1))
												{                                                														
													//echo "<br>Print:".$lat_ref.",".$lng_ref;
													//echo "<br>Print_1:".$tmp_arr.",".$tmp_dep;
													//echo "<br>Print:".$tmp_arr.",".$tmp_dep."<br>";
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
													$total_halt_vehicle = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" lat=\"".$lat_ref."\" lng=\"".$lng_ref."\" arr_time=\"".$arrivale_time."\" dep_time=\"".$depature_time."\" in_temp=\"".$tmp_arr."\" out_temp=\"".$tmp_dep."\" duration=\"".$halt_dur."\"/>";						          						
													//echo "<br>total halt vehicle=".$total_halt_vehicle;
													$linetowrite = $total_halt_vehicle; // for distance       // ADD DISTANCE
													fwrite($fh, $linetowrite);  

													$date_secs1 = strtotime($datetime_cr);
													$date_secs1 = (double)($date_secs1 + $interval);
													//break;
												}  // IF STATUS  
											} // SIZE OF GEO_ID
											else
											{
												//echo "<br>In Halt else";
													//echo "<br>Print:".$lat_ref.",".$lng_ref;
												//echo "<br>Print:".$tmp_arr.",".$tmp_dep."<br>";
												
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
												$total_halt_vehicle = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" lat=\"".$lat_ref."\" lng=\"".$lng_ref."\" arr_time=\"".$arrivale_time."\" dep_time=\"".$depature_time."\" in_temp=\"".$tmp_arr."\" out_temp=\"".$tmp_dep."\" duration=\"".$halt_dur."\"/>";						          						
												//echo "<br>total halt vehicle=".$total_halt_vehicle;
												$linetowrite = $total_halt_vehicle; // for distance       // ADD DISTANCE
												fwrite($fh, $linetowrite);  

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
								//echo "<br>datetime_cr=".$datetime_cr." ,datetime_ref=".$datetime_ref." ,distance=".$distance." ,f=".$f." ,total_lines-2=".$total_lines;					                              
								// HALT LOGIC CLOSED 
								//echo "<br>total halt vehicle=".$total_halt_vehicle;
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
		//echo "<br>In Halt1";
		//echo "<br>datetime_ref=".$datetime_ref;
		$arrivale_time=$datetime_ref;
		$tmp_arr=$tmp_ref;
		$starttime = strtotime($datetime_ref);
		//$stoptime = strtotime($datetime_cr);  
		$stoptime = strtotime($datetime_cr);
		$depature_time=$datetime_cr;
		$tmp_dep=$tmp_cr;
		//echo "<br>".$starttime." ,".$stoptime;
		$halt_dur =  ($stoptime - $starttime);
		
		if((sizeof($geo_id1)>0) && ($geo_id1[0]!="") )
		{                                                                                            
			$exclude_flag = 1;
			$geo_status = 1;
			for($j=0;$j<sizeof($geo_id1);$j++)
			{                                                                                                    
				include('action_halt_exclusion.php');
				if($geo_coord!="")
				{                
					check_with_range($lat_ref, $lng_ref, $geo_coord, &$geo_status);
					//echo "<Br>geo_status1:".$geo_status;                                        
				}
				if($geo_status == 1)
				{
					$exclude_flag = 0;
				}                                                                                          
				//echo "<br>geo_status=".$geo_status;                                
				//echo "<br>geo_id[i]=".$geo_id1[$j]." ,geo_status=".$geo_status;                                      
			}	// FOR LOOP
			//echo "<BR>geo_status2:".$geo_status." ,exclude_flag:".$exclude_flag;
			if(($geo_status==false) && ($exclude_flag==1))
			{                                                														
				//echo "<br>Print:".$lat_ref.",".$lng_ref;
				//echo "<br>Print_1:".$tmp_arr.",".$tmp_dep;
				//echo "<br>Print:".$tmp_arr.",".$tmp_dep."<br>";
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
				$total_halt_vehicle = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" lat=\"".$lat_ref."\" lng=\"".$lng_ref."\" arr_time=\"".$arrivale_time."\" dep_time=\"".$depature_time."\" in_temp=\"".$tmp_arr."\" out_temp=\"".$tmp_dep."\" duration=\"".$halt_dur."\"/>";						          						
				//echo "<br>total halt vehicle=".$total_halt_vehicle;
				$linetowrite = $total_halt_vehicle; // for distance       // ADD DISTANCE
				fwrite($fh, $linetowrite);  

				$date_secs1 = strtotime($datetime_cr);
				$date_secs1 = (double)($date_secs1 + $interval);
				//break;
			}  // IF STATUS  
		} // SIZE OF GEO_ID
		else
		{
			//echo "<br>In Halt else";
				//echo "<br>Print:".$lat_ref.",".$lng_ref;
			//echo "<br>Print:".$tmp_arr.",".$tmp_dep."<br>";
			
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
			$total_halt_vehicle = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" lat=\"".$lat_ref."\" lng=\"".$lng_ref."\" arr_time=\"".$arrivale_time."\" dep_time=\"".$depature_time."\" in_temp=\"".$tmp_arr."\" out_temp=\"".$tmp_dep."\" duration=\"".$halt_dur."\"/>";						          						
			//echo "<br>total halt vehicle=".$total_halt_vehicle;
			$linetowrite = $total_halt_vehicle; // for distance       // ADD DISTANCE
			fwrite($fh, $linetowrite);  

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
echo'<form method="post" action="action_report_halt_1.php" target="_self">  
		<div id="progress"></div>
		<div id="delay"></div>';		
		$size_vserial = sizeof($vserial); 
		///////////////////  READ HALT XML 	//////////////////////////////				                      
		$xml_path = $xmltowrite;
		//echo "<br>xml_path=".$xml_path;
		$place_name_arr=array();
		//read_halt_xml($xml_path, &$imei, &$vname, &$lat, &$lng, &$arr_time, &$dep_time, &$duration, &$place_name_arr);	
		read_halt_xml($xml_path, &$imei, &$vname, &$lat, &$lng, &$arr_time, &$dep_time, &$in_temperature, &$out_temperature, &$duration);	
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
			<textarea id="geocodedPostcodes" name="geocodedPostcodes" cols="4" rows="1" style="visibility:hidden;"></textarea>
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
			if($size==0)
			{
				echo"<table align='center'>
						<tr>
							<td>
								<b>
									<font color='red'>
										No Halt Found For This Vehicle
									</font>
								</b>
							</td>
						</tr>
					</table>";
			echo'<script type="text/javascript">          
					document.getElementById("processrequestid").style.display="none"; 
				</script>';					
			}
			else
			{
				call_geocode($point);
			}

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
