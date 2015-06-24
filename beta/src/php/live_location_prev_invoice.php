<?php
/*echo "<br>LatARR_Size2=".sizeof($lat_arr_last);
echo "<br>sizeLat=".sizeof($lat_arr_last);
$lat_arr_last = json_encode($lat_arr_last);
$lng_arr_last = json_encode($lng_arr_last);
$vehiclename_arr_last = json_encode($vehiclename_arr_last);
$vserial_arr_last = json_encode($vserial_arr_last);
$speed_arr_last = json_encode($speed_arr_last);
$datetime_arr_last = json_encode($datetime_arr_last);
$day_max_speed_arr_last = json_encode($day_max_speed_arr_last);
$last_halt_time_arr_last = json_encode($last_halt_time_arr_last);
$io_str_last = json_encode($io_str_last);*/
?>
<?php
$for_action1=$_POST['title'];
//echo 'for_action1='.$for_action1."<br>";
?>
<!--<script language="javascript" src="../js/jquery.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>-->
<script type="text/javascript">	

	var map;
	var geocodedPostcodes;
	geocodedPostcodes = "";
	var account_id_session="";
	var address2="";
	var xml_address_flag=0;
	
	///######### PHP VARS ############
	var lat_arr_last;
	var lng_arr_last;
	var vehiclename_arr_last;
	var vserial_arr_last;
	var speed_arr_last;
	var datetime_arr_last;
	var day_max_speed_arr_last;
	var last_halt_time_arr_last;
	var io_str_last;
	var vehilce_status_arr;
	var fault_status_arr;
	
	function set_php_arrays(lat_arr_last1,lng_arr_last1,vehiclename_arr_last1,vserial_arr_last1,speed_arr_last1,datetime_arr_last1,day_max_speed_arr_last1,last_halt_time_arr_last1,io_str_last1,vehilce_status_arr1,fault_status_arr1)
	{
		document.getElementById("final_loc_request").value = "0";
		
		lat_arr_last = lat_arr_last1;
		lng_arr_last = lng_arr_last1;
		vehiclename_arr_last = vehiclename_arr_last1;
		vserial_arr_last = vserial_arr_last1;
		speed_arr_last = speed_arr_last1;
		datetime_arr_last = datetime_arr_last1;
		day_max_speed_arr_last = day_max_speed_arr_last1;
		last_halt_time_arr_last = last_halt_time_arr_last1;
		io_str_last = io_str_last1;
		vehilce_status_arr = vehilce_status_arr1;
		fault_status_arr = fault_status_arr1;
	}	
	/////////###################### ///
	/*window.onload = function() 
	{
		var latlng = new google.maps.LatLng(54.559322587438636, -4.1748046875);
		var options = 
		{
			zoom: 6,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map(document.getElementById("map"), options);
	}*/

    var splitData;
	var vehSplitData;
    var i=0;
	var v=0;
	var latlng;
	var tsstr="";
	var vehTdStr;

	function Geocode(data,vehfun)
	{
		//alert("InGeocode:"+data);
		//alert("InGeocode="+data);
		// split data
		i=0;
		v=0;
		//document.getElementById("geocodedPostcodes").value = "";
		//geocodedPostcodes = "";
		//var data = document.getElementById("postcodes").value;
		//var data = <?php echo "'".$point."'"; ?>;
		//alert(data);
		splitData = data.split(":");
		vehSplitData=vehfun.split(":");
		//document.getElementById('js_action').value='js2';
		//alert(splitData.length);		
		GeocodeNext();        
	}

    var separator = ",";
    var delay = 0;
	function GeocodeNext()
	{
	
		$('#progress').html((i+1).toString() + " of " + splitData.length);
		$('#vehicleDiv').html((v+1).toString() + " of " + vehSplitData.length);
		var geocoder = new google.maps.Geocoder();
		var splitLatLng = splitData[i].replace("\r", "").split(",");
		vehTdStr=vehSplitData[v].trim();
		//alert('vehTdStr='+vehTdStr);
		// if no commas, try tab
		//alert("len="+splitLatLng.length);
		if (splitLatLng.length == 1) 
		{
			splitLatLng = splitData[i].replace("\r", "").split("\t");
			separator = "\t";
		}
		else
		{
			separator = ",";
			var latLng = new google.maps.LatLng(splitLatLng[0], splitLatLng[1]);
			//alert("latlng="+latLng);
			account_id_session = "<?php echo $account_id; ?>";
			// alert("account="+account_id_session);
			if(account_id_session=="212")
			{
				//alert("latlng="+latLng+"data="+splitData[i]);
			}
			//alert("K2");
			geocoder.geocode({'latLng': latLng}, function(results, status) 
			{
				//alert("K3");
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
								xml_address_flag=0;
								//alert(results[j].geometry.location.lat());
								user_lat = latLng.lat();
								user_lng = latLng.lng();
								google_lat = results[j].geometry.location.lat();
								google_lng = results[j].geometry.location.lng();
								if(account_id_session=="212")
								{
									//alert("user_lat="+user_lat+"user_lng="+user_lng+"address="+results[j].formatted_address);
								}
								var distance = calculate_distance(user_lat, google_lat, user_lng, google_lng); 
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
							//alert("user_lat="+user_lat+"user_lng="+user_lng+"address="+results[j].formatted_address);
						}
					}
				}
				else 
				{
					// addResult(latLng, errorMsg, errorMsg);				
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
						//alert("user_lat="+user_lat+"user_lng="+user_lng+"address="+results[j].formatted_address);
					}
				}
				
				if((i < splitData.length-1) || (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT))
				{
					//alert("K1");
					if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) 
					{
						delay += 100;
					}
					else 
					{
						i++;
						v++;
						if (delay > 0)
						delay -= 100;
					}
					//$('#delay').html("Delay is " + delay.toString() + " ms");
					//$('#delay').html("Delay is " + delay.toString() + " ms");
					setTimeout("GeocodeNext()", delay);
				}
				else 
				{
					var jsAction='<?php echo $for_action1; ?>';
					//alert("jsAction="+jsAction);
					if(jsAction=='js1')
					{
						document.getElementById("lacStr").value=geocodedPostcodes;
						document.getElementById("js_action").value='js2';
					}
					document.getElementById("final_loc_request").value = "1";
					//
					//var vehicle = vehiclename_arr_last.split(",");
					//alert("geocodePostcodes="+geocodedPostcodes);
					/*var location = geocodedPostcodes.split(":");
					for(var i=0;i<vehiclename_arr_last.length;i++)
					{
						document.getElementById(vehiclename_arr_last[i]).innerHTML = location[i];
					}*/
				//############# CLOSED SEND DATA TO POST
				}
			});
		}
	}

	function getAddressComponent(result, component) {
		for (var i=0; i<result.address_components.length; i++) {
			var comp = result.address_components[i];
			for (j=0; j<comp.types.length; j++) {
				if (comp.types[j] == component)
					return comp.long_name;
			}
		}
		
		return "";
	}
	
	function buildAddress(result) {
		return getAddressComponent(result, "street_number") + separator + 
			getAddressComponent(result, "route") + separator +
			getAddressComponent(result, "postal_town") + separator +
			getAddressComponent(result, "administrative_area_level_2") + separator +
			getAddressComponent(result, "postal_code") + separator +
			getAddressComponent(result, "country");
			;
	}
		
    function addLocation(latLng, result, distance,user_lat,user_lng) {
			// add address components if selected
			var address = "\"" + result.formatted_address + "\"";
			//alert(address);
			//if ($("#addressComponents").is(":checked"))     //COMMENTED FOR TESTING
				//address = buildAddress(result);
			
			addResult(latLng, address, result.formatted_address, distance,user_lat,user_lng);
	}
			
	function addResult(latLng, address, formattedAddress, distance,user_lat,user_lng) {
     // alert("latLng"+latLng);
      //document.getElementById("geocodedPostcodes").value +=
        //latLng.lat() + separator + latLng.lng() + separator + address + ":";
		if(xml_address_flag==0)
		{
			if((address.indexOf("NH") ==-1) && (address.indexOf("National Highway") ==-1) && (address.indexOf("State Highway") ==-1) && (address.indexOf("SH") ==-1))
			{
				address2=distance + " km from " + address + ":"; 
				
				//alert("address_with_distance="+address2)	
				// document.getElementById("geocodedPostcodes").value += address2; 
				//alert("address1="+address2);				
				 if (document.getElementById(vehTdStr).innerHTML.indexOf("LM") ==-1)
				 {
					geocodedPostcodes += address2;
					document.getElementById(vehTdStr).innerHTML=address2;
				 }
				 else
				 {
					geocodedPostcodes += document.getElementById(vehTdStr).innerHTML + ":";
					document.getElementById(vehTdStr).innerHTML = document.getElementById(vehTdStr).innerHTML;
				 }				 				 
				 //alert("geocodedPostcodes1="+geocodedPostcodes);
				 /*if(account_id_session=="212")
				 {
					alert("ifaddress2="+address2);
					alert("ifgeocodedPostcodes="+document.getElementById("geocodedPostcodes").value);
				 }*/
			}
			else
			{		
				//alert("distance="+distance+"address2="+address2);
				//alert("latLng="+latLng);
				 var strURL="src/php/get_location_tmp_file.php?point_test="+latLng;
				//alert("strurl:"+strURL);
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
				
				// document.getElementById("geocodedPostcodes").value += address2;
				//alert('address='+address2);
				//alert('vehTdStr='+vehTdStr);
				
				 if (document.getElementById(vehTdStr).innerHTML.indexOf("LM") ==-1)
				 {
					geocodedPostcodes += address2;
					document.getElementById(vehTdStr).innerHTML=address2;
				 }
				 else
				 {
					geocodedPostcodes += document.getElementById(vehTdStr).innerHTML + ":";
					document.getElementById(vehTdStr).innerHTML = document.getElementById(vehTdStr).innerHTML;
				 }		
				 
				//alert("geocodedPostcodes1="+geocodedPostcodes);
				 //alert("address3="+address2);
			}
		}
		else if(xml_address_flag==1)
		{
			//alert("distance="+distance+"address2="+address2);
			//alert("latLng="+latLng);
			var strURL="src/php/get_location_tmp_file.php?point_test="+latLng;
			//alert("strurl:"+strURL);
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
			
			// document.getElementById("geocodedPostcodes").value += address2; 

			 if (document.getElementById(vehTdStr).innerHTML.indexOf("LM") ==-1)
			 {
				geocodedPostcodes += address2;
				document.getElementById(vehTdStr).innerHTML=address2;
			 }
			 else
			 {
				geocodedPostcodes += document.getElementById(vehTdStr).innerHTML + ":";
				document.getElementById(vehTdStr).innerHTML = document.getElementById(vehTdStr).innerHTML;
			 }		
		}
                  
        /*var marker = new google.maps.Marker({
          position: latLng,
          map: map,
          title:formattedAddress
      }); */
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

function mapOnly(vName)
{
	//alert("vName="+vName);
	document.getElementById("clickVehicle").value=vName;
	document.newMapOnly.submit();
}
    /* ]]> */
  </script>  
  
<?php
$lacStr=$_POST['lacStr'];
if($for_action!='js1')
{
?>
<script>
document.getElementById("lacStr").value='<?php echo $lacStr; ?>';
</script>
<?php
}
$lacStr=explode(":",$lacStr);
//echo "<br>sizeV=".sizeof($vehiclename_arr_last);
$lat_arr_last2 = json_encode($lat_arr_last);
$lng_arr_last2 = json_encode($lng_arr_last);
$vehiclename_arr_last2 = json_encode($vehiclename_arr_last);
$vserial_arr_last2 = json_encode($vserial_arr_last);
$speed_arr_last2 = json_encode($speed_arr_last);
$datetime_arr_last2 = json_encode($datetime_arr_last);
$day_max_speed_arr_last2 = json_encode($day_max_speed_arr_last);
$last_halt_time_arr_last2 = json_encode($last_halt_time_arr_last);
//$io_str_last2 = json_encode($io_str_last);
$vehilce_status_arr2 = json_encode($vehilce_status_arr);
$fault_status_arr2 = json_encode($fault_status_arr);

echo '
<script type="text/javascript">          
 // set_php_arrays('.$lat_arr_last2.','.$lng_arr_last2.','.$vehiclename_arr_last2.','.$vserial_arr_last2.','.$speed_arr_last2.','.$datetime_arr_last2.','.$day_max_speed_arr_last2.','.$last_halt_time_arr_last2.','.$io_str_last2.','.$vehilce_status_arr2.','.$fault_status_arr2.'); 
 set_php_arrays('.$lat_arr_last2.','.$lng_arr_last2.','.$vehiclename_arr_last2.','.$vserial_arr_last2.','.$speed_arr_last2.','.$datetime_arr_last2.','.$day_max_speed_arr_last2.','.$last_halt_time_arr_last2.','.$vehilce_status_arr2.','.$fault_status_arr2.'); 
 </script>	
';
	
$point = '"';
$vehStr = '"';
//$point = '';
/*$lat[] = "28.56456";
$lng[] = "80.345646";
$lat[] = "28.66456";
$lng[] = "80.645646";
$lat[] = "27.66456";
$lng[] = "80.645646";
$vsize = sizeof($lat);*/
	echo '
	<style type="text/css">
	<!--
	.style_v1 {
	font-size:12px;
	font-weight: bold;
	color: #000000;
	}
	.blink1 {
	font-size:12px;
	color: #FFFFFF;
	background-color:#008000;
	}
	.style_v2 {
	font-size:12px;
	}
	.style_v6 {
		font-size: 11px;
		font-weight: bold;
		color: #ff0000;
	}
	.style_v7 {
		font-size: 11px;
		font-weight: bold;
		color: #006600;
	}
	.style_v8 {
		font-size: 11px;
		font-weight: bold;
	}
	.style_v9 {font-size: 11px}
	.style_v11 {
		font-size: 11px;	
		color: #ffffff;
		font-weight: bold;
	}
	.blink2 {
		font-size: 11px;	
		color: #ff0000;
		font-weight: bold;
	}
	.style_v12 {
		font-size: 11px;
		color: #ff0000;
		font-weight: bold;
	}
	.style_v13 {
		font-size: 11px;
		font-weight: bold;
		color: #808080;
	}
	.style_v14 {
		font-size: 11px;
		font-weight: bold;
		color: #800080;
	}	
	
	.normal1 { background-color: #F8F8FF }
	.highlight1 { background-color:  #C6DEFF }
	.normal2 { background-color: #FFFDF9 }
	.highlight2 { background-color:  #C6DEFF }
	-->
	</style>
	';
	include_once("util.hr_min_sec.php");
echo'<form name="newMapOnly" method="POST" action="live_OMap.php" target="_blank">
<input type="hidden" id="clickVehicle" name="clickVehicle">
	<table width="100%" border="1" rules="all">
	  <tr class="style_v1">
		<td bgcolor="#CCCCCC">Sno</td>
		<td bgcolor="#CCCCCC">VehicleName</td>
		<!--<td bgcolor="#CCCCCC">IMEI</td>-->		
		<td bgcolor="#CCCCCC">Activity Time</td>
		<td bgcolor="#CCCCCC">Speed</td>
		<!--<td bgcolor="#CCCCCC">Day MaxSpeed</td>
		<td bgcolor="#CCCCCC">Last Halt Time </td>-->
		<!--<td bgcolor="#CCCCCC">IO Status </td>-->
		<td bgcolor="#CCCCCC">Location</td>
		<!--<td bgcolor="#CCCCCC">Fault</td>-->
		<td bgcolor="#CCCCCC">Movement Status</td>
		<td bgcolor="#CCCCCC">Trasporter</td>
		<td bgcolor="#CCCCCC">Tr Mobile</td>
		<td bgcolor="#CCCCCC">Dr Mobile</td>
		<td bgcolor="#CCCCCC">GPSvendor</td>
		<td bgcolor="#CCCCCC">Factory</td>
		<td bgcolor="#CCCCCC">TripStart</td>
		<td bgcolor="#CCCCCC">Route</td>
	  </tr>
	  ';

	//print_r($vehilce_status_arr);	   
	   
$runStatus=0;
for($i=0;$i<sizeof($vehiclename_arr_last);$i++)
{
		$sno=$i+1;
		
		if($count%2==0)
		{
			echo'<tr valign="top" bgcolor="#F8F8FF" onMouseOver="this.className=\'highlight1\'" onMouseOut="this.className=\'normal1\'">';
		}
		else
		{
			echo'<tr valign="top" bgcolor="#FFFDF9" onMouseOver="this.className=\'highlight2\'" onMouseOut="this.className=\'normal2\'">';
		} 		
         
		 $Queryvehicle_details="SELECT transporters.name,vehicle_gpsvendor_assignment.gps_vendor_name,transporters.factory,transporters.contact_no,trip_detail.driver_mobile,transporter_route.route_name,trip_detail.trip_startdate
		   FROM transporters,vehicle_transporter_assignment,vehicle_gpsvendor_assignment,trip_detail,transporter_route
		   WHERE transporters.transporter_id=vehicle_transporter_assignment.transporter_id
		   AND transporter_route.transporter_route_id =trip_detail.transporter_route_id
		    AND vehicle_gpsvendor_assignment.device_imei_no=vehicle_transporter_assignment.device_imei_no
		   AND trip_detail.imei_no=vehicle_gpsvendor_assignment.device_imei_no
		   AND vehicle_gpsvendor_assignment.device_imei_no='$vserial_arr_last[$i]'
		   AND transporters.Status=1 AND vehicle_transporter_assignment.status=1
		   AND vehicle_gpsvendor_assignment.status=1
		   AND trip_detail.trip_status=1 ";
		$resultVD=mysql_query($Queryvehicle_details,$DbConnection);
		$rowVD=mysql_fetch_object($resultVD);
		$transporter_name=$rowVD->name;
		$gps_vendor_name=$rowVD->gps_vendor_name;
		$factory_name=$rowVD->factory;
		$transporter_contact_no=$rowVD->contact_no;
		$driver_contact_no=$rowVD->driver_mobile;
		$route_name=$rowVD->route_name;
		$trip_startdate=$rowVD->trip_startdate;
		
		// echo "lat=".$lat_arr_last[$i]." lng=".$lng_arr_last[$i]."<br>";
		 
		echo '<td><span class="style_v8">'.$sno.'</td>';
		if(($lat_arr_last[$i]=="" || $lng_arr_last[$i]==""))
		{
			//echo "in 1<br>";
			echo '<td><span class="style_v13">'.$vehiclename_arr_last[$i].'</span></td>';
		}
		else if($vehilce_status_arr[$i]=="Running")
		{
			//echo "in 2<br>";
			echo '<td>
			<span class="style_v7">
			
			<a href="#" onclick="javascript:mapOnly(\''.$vserial_arr_last[$i].'\')">
						'.$vehiclename_arr_last[$i].'
					</a>			
			</span>			
			</td>';
		}
		else if($vehilce_status_arr[$i]=="Stopped")
		{
				
			//echo "in 3<br>";
		echo '<td>
				<span class="style_v12">
					<a href="#" onclick="javascript:mapOnly(\''.$vserial_arr_last[$i].'\')">
						'.$vehiclename_arr_last[$i].'
					</a>
				</span>
			</td>';
		}		
		else if($vehilce_status_arr[$i]=="NOD")
		{
			//echo "in 3<br>";
		echo '<td>
				<span class="style_v14">
					<a href="#" onclick="javascript:mapOnly(\''.$vserial_arr_last[$i].'\')">
						'.$vehiclename_arr_last[$i].'
					</a>
				</span>
			</td>';
		}
		/*else if($vehilce_status_arr[$i]=="INACTIVE")
		{
			//echo "in 3<br>";
		echo '<td>
				<span class="style_v8">		
					'.$vehiclename_arr_last[$i].'
				</span>
			</td>';
		}*/
		
		//echo '<td><span class="style_v6">'.$vserial_arr_last[$i].'</span></td>';
		//echo '<td><span class="style_v8">'.$speed_arr_last[$i].'</span></td>';
		echo '<td><span class="style_v8">'.$datetime_arr_last[$i].'</span></td>';
		echo '<td><span class="style_v8">'.$day_max_speed_arr_last[$i].'</span></td>';
		//echo '<td><span class="style_v8">'.$last_halt_time_arr_last[$i].'</span></td>';		
	
		//echo '<td><span class="style_v8">'.substr($io_str_last[$i],0,-1).'</span></td>';	
		
		if($landmark_last[$vehiclename_arr_last[$i]]!="")
		{
			//echo "in if<br>";
			echo '<td><span class="style_v9" id=\''.$vehiclename_arr_last[$i].'\'>'.$landmark_last[$vehiclename_arr_last[$i]].'-(LM)</span></td>';
		}
		else
		{
				//echo "in else<br>";
			echo '<td><span class="style_v9" id=\''.$vehiclename_arr_last[$i].'\'>'.$lacStr[$i].'</span></td>';
		}
		//echo '<td><span class="style_v8">'.$fault_status_arr[$i].'</span></td>';

		if($vehilce_status_arr[$i]=="Running")
		{
			echo'<td><span class="blink2">Running</span></td>';
		}
		else if($vehilce_status_arr[$i]=="Stopped")
		{
			$current_datetime_new=date('Y-m-d H:i:s');
			$halt_time=strtotime($current_datetime_new)-strtotime($datetime_arr_last[$i]);			
			$nms1 = secondsToTime($halt_time);
			$halt_time1 = $nms1[h]."hrs ".$nms1[m]."min";
			echo'<td><span class="style_v12"><strong>Stopped <font color="black">since['.$halt_time1.']</font></strong></span></td>';
		}
		else if($vehilce_status_arr[$i]=="NOD")
		{
			echo'<td><span class="style_v12"><strong>NOD</strong></span></td>';
		}
		else if($vehilce_status_arr[$i]=="NA")
		{
			echo'<td><span class="style_v13"><strong>NA</strong></span></td>';
		}		
	    echo '<td><span class="style_v8">'.$transporter_name.'</span></td>';
		echo '<td><span class="style_v8">'.$transporter_contact_no.'</span></td>';
		echo '<td><span class="style_v8">'.$driver_contact_no.'</span></td>';
		echo '<td><span class="style_v8">'.$gps_vendor_name.'</span></td>';		
		echo '<td><span class="style_v8">'.$factory_name.'</span></td>';
		echo '<td><span class="style_v8">'.$trip_startdate.'</span></td>';
		echo '<td><span class="style_v8">'.$route_name.'</span></td>';		
			
	echo'</tr>';
	//echo "<br>LatArr[$i]=".$lat_arr_last[$i];
	if($lat_arr_last[$i]=="" || $lng_arr_last[$i]=="")
	{
		//$lat_arr_last[$i] = "6.260697372";
		//$lng_arr_last[$i] = "80.5517575";		
	}
	else
	{
		$lat_arr_last[$i] = substr($lat_arr_last[$i], 0, -1);
		$lng_arr_last[$i] = substr($lng_arr_last[$i], 0, -1);
	}
	
	if(($lat_arr_last[$i]!="") || ($lng_arr_last[$i]!=""))
	{

		$coord = $lat_arr_last[$i].",".$lng_arr_last[$i];
		//echo "<br>Coord=".$coord;
		//echo"io status=".$io_str_last[$i]."<br>";
		//$point = $point.$coord.":";
		//$vehStr=$vehStr.$vehiclename_arr_last[$i].":";
		
		//echo "Coord=".$for_action;
		//echo"<br>vehilce_status_arr=".$vehilce_status_arr[$i]."<br>";
		if($for_action1=='js1')
		{
			$point = $point.$coord.":";
			$vehStr=$vehStr.$vehiclename_arr_last[$i].":";
		}
		else
		{
			if(trim($vehilce_status_arr[$i])=="Running")
			{
				$runStatus=1;
				$point = $point.$coord.":";
				$vehStr=$vehStr.$vehiclename_arr_last[$i].":";	
			}
			/*$runStatus=1;
			$point = $point.$coord.":";
			$vehStr=$vehStr.$vehiclename_arr_last[$i].":";*/
		}
	}
	else
	{
	}

	echo '</tr>';
	$count++;
}  

echo '</table>
</form>';

//echo "<br><span class='style_v6'>Fault lagend: </span><span class='style_v9'><strong>1</strong>-low battery, <strong>2</strong>-No GPS, <strong>3</strong>-No DATA, <strong>4</strong>-NO power</span>";
$point=substr($point,0,-1);	
$vehStr=substr($vehStr,0,-1);	
$point = $point.'"';
$vehStr = $vehStr.'"';

//echo "<br>pt=".$point;  

if($for_action1=='js1')
	{
		call_geocode($point,$vehStr); 
	}
	else
	{
		if($runStatus==1)
		{
			call_geocode($point,$vehStr); 
		}
	}


function call_geocode($point,$vehStr)
{          
	echo '
	<script type="text/javascript">          
	  Geocode('.$point.','.$vehStr.'); 
	</script>
	';
} 

//echo'<div id="progress"></div>';
echo'<div id="vehicleDiv" style="display:none"></div>
<div id="delay"></div>';
?>
