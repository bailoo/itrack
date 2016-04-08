<?php
set_time_limit(2000);
include_once("getFilteredXmlTrackOnly.php");      // WRITE SORTED XML , FINAL XML NAME STORED IN 'xmltowirte' VARIABLE
//include_once("read_filtered_xml.php");
//echo "xmltowrite=".$xmltowrite." startdate=".$startdate." enddate=".$enddate;
?>

<html>

<head>
 <script language="javascript" src="../js/jquery.js"></script>
 <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
  <script type="text/javascript">
  var map;
	var account_id_session="";
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

var setCoordFlag=0;
var prevLocationArr = [];
	function Geocode(data)
	{
		//alert("data="+data);
		// split data
		i=0;
		document.getElementById("geocodedPostcodes").value = "";
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
            var user_lat;
            var user_lng; 
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
			account_id_session = "<?php echo $account_id; ?>";
			var getGoogleLocation=true;
        user_lat = latLng.lat();
        user_lng = latLng.lng(); 
        if(user_lat=="0.0" && user_lng=="0.0")
        {
            address2 = "0.0,0.0:";
            
            document.getElementById("geocodedPostcodes").value += address2; 
        }
        else
        {           
            if(setCoordFlag==1)
            {
                for(var z=prevLocationArr.length-1;z>0;z--)
                {
                    //alert("Z="+z);
                    //alert("lat="+prevLocationArr[z].lat+"lat2="+user_lat+"lng="+prevLocationArr[z].lng+"lng="+lng;
                    var distanceCompare=calculate_distance(prevLocationArr[z].lat, user_lat, prevLocationArr[z].lng, user_lng);  
                    //alert("distancCmp="+distanceCompare);
                    if(distanceCompare<=0.1)
                    {  
                        getGoogleLocation=false;
                        document.getElementById("geocodedPostcodes").value += prevLocationArr[z].address;
                        break;
                    } 
                }                    
            }
            setCoordFlag=1;
        }
                        if(getGoogleLocation==true)
                        {
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
					if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) 
					{
						delay += 100;
					}
					else 
					{
						i++;
						if (delay > 0)
						delay -= 100;
					}
					//$('#delay').html("Delay is " + delay.toString() + " ms");
					//$('#delay').html("Delay is " + delay.toString() + " ms");
					setTimeout("GeocodeNext()", delay);
				}
				else 
				{          
					//$('#progress').html("Complete");
					/*if(account_id_session=="212")
					{
						//document.forms[0].submit();
					}
					else
					{
						document.forms[0].submit();	
					}*/
					document.forms[0].submit();	
				}
			});
                        }
                        else
                        {
                            if((i < splitData.length-1))
                            {
                                i++; 
                                //alert("i="+i);
                                //$('#delay').html("Delay is " + delay.toString() + " ms");
                                //$('#delay').html("Delay is " + delay.toString() + " ms");
                                setTimeout("GeocodeNext()", 100);
                            }
                            else 
                            {          
                                document.forms[0].submit();	
                            }
                        }
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
			//if((address.indexOf("NH") ==-1) && (address.indexOf("National Highway") ==-1) && (address.indexOf("State Highway") ==-1) && (address.indexOf("SH") ==-1))
			if((address.indexOf("NH") ==-1) && (address.indexOf("SH") ==-1))
			{
				address2=distance + " km from " + address + ":"; 
				
				//alert("address_with_distance="+address2)	
				 document.getElementById("geocodedPostcodes").value += address2;  
				 /*if(account_id_session=="212")
				 {
					alert("ifaddress2="+address2);
					alert("ifgeocodedPostcodes="+document.getElementById("geocodedPostcodes").value);
				 }*/
                                prevLocationArr.push({lat:user_lat,lng:user_lng,address:address2});
			}
			else
			{		
				//alert("distance="+distance+"address2="+address2);
				//alert("latLng="+latLng);
				 var strURL="get_location_tmp_file.php?point_test="+latLng;
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
				
				 document.getElementById("geocodedPostcodes").value += address2;
                                 prevLocationArr.push({lat:user_lat,lng:user_lng,address:address2});
                                 
			}
		}
		else if(xml_address_flag==1)
		{
			//alert("distance="+distance+"address2="+address2);
			//alert("latLng="+latLng);
			 var strURL="get_location_tmp_file.php?point_test="+latLng;
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
			
			 document.getElementById("geocodedPostcodes").value += address2; 
                          prevLocationArr.push({lat:user_lat,lng:user_lng,address:address2});
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
    /* ]]> */
  </script>  
</head>

<body>

<FONT color="darkgreen" size="3"><strong>Loading Records- Please wait .. </strong></font> 

<?php
echo'
  <form method="post" action="FullData.htm" target="_self">  
  <div id="progress"></div>
  <div id="delay"></div>
  <textarea id="geocodedPostcodes" name="geocodedPostcodes" cols="40" style="visibility:hidden;" rows="20"></textarea>';
      
  //read_lp_xml_imei($xmltowrite, &$vehicleserial, &$lat, &$lng); 

 // read_xml_imei($xmltowrite, &$vehicleserial, &$lat, &$lng);

 $size = sizeof($vehicleserial);
 //echo "size=".$size."<br>";

  /*$place_name_arr=array();
	for($i=0;$i<$size;$i++)
	{
		$lat[$i] = substr($lat[$i], 0, -1);
		$lng[$i] = substr($lng[$i], 0, -1);
		get_location_tmp($lat[$i],$lng[$i],&$placename);
		//echo "placename=".$placename."<br>";
		$place_name_arr[$i]=$placename;
	} 
	$_SESSION['place_name_arr1'] = $place_name_arr;*/
  //echo "<br>size:".$size;   

if($report_type=="Vehicle")
{
    if($dwt=="1")
    {	
        //echo "in if";
        $point = '"';
        for($i=0;$i<$size;$i++)
        {
            if((strlen($lat[$i])>5) && ($lat[$i]!="-") && (strlen($lng[$i])>5) && ($lng[$i]!="-"))
            {
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
        }         
        $point = $point.'"';
        //echo "<br>pt=".$point;  
        call_geocode($point); 
    } 
}
else
{
    if($dwt=="1")
    {	
        //echo "in if";
        $point = '"';
         //print_r($lat);
        for($i=0;$i<$size;$i++)
        {
            //if((strlen($lat[$i])>5) && ($lat[$i]!="-") && (strlen($lng[$i])>5) && ($lng[$i]!="-"))
            {
               /* $lat[$i] = substr($lat[$i]);
                $lng[$i] = substr($lng[$i]);*/

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
        }         
        $point = $point.'"';
        //echo "<br>pt=".$point;  
        call_geocode($point); 
    } 
}
  //serialize($vehicleserial)
  echo'
	<input type="hidden" name="startdate" value="'.$startdate.'">
	<input type="hidden" name="enddate" value="'.$enddate.'">
	<input type="hidden" name="time_interval" value="'.$time_interval1.'">
	<input type="hidden" name="text_report_io_element" value="'.$text_report_io_element.'">
	<input type="hidden" name="data_with_location" value="'.$dwt.'">
	<input type="hidden" name="data_with_category" value="'.$lastcategory.'">
	<input type="hidden" name="vehicleserial" value='.serialize($vehicleserial).'>
	<input type="hidden" name="lat" value='.serialize($lat).'>
	<input type="hidden" name="lng" value='.serialize($lng).'>
	<input type="hidden" name="datetimeXml" value='.base64_encode(serialize($datetimeXml)).'>
	<input type="hidden" name="vehiclename" value='.serialize($vehiclename).'>
	<input type="hidden" name="vehicletype" value='.serialize($vehicletype).'>
	<input type="hidden" name="speed" value='.serialize($speed).'>
	<input type="hidden" name="cumdist" value='.serialize($cumdist).'>';
	if($report_type=="Vehicle")
	{
	echo'<input type="hidden" name="io1" value='.serialize($io1).'>
	<input type="hidden" name="io2" value='.serialize($io2).'>
	<input type="hidden" name="io3" value='.serialize($io3).'>
	<input type="hidden" name="io4" value='.serialize($io4).'>
	<input type="hidden" name="io5" value='.serialize($io5).'>
	<input type="hidden" name="io6" value='.serialize($io6).'>
	<input type="hidden" name="io7" value='.serialize($io7).'>
	<input type="hidden" name="io8" value='.serialize($io8).'>';
	}
        else if($report_type=="Person")
        {
        echo'<input type="hidden" name="cellname" value='.serialize($cellname).'>
	<input type="hidden" name="serverDatetime" value='.base64_encode(serialize($serverDatetime)).'>
	<input type="hidden" name="ver" value='.base64_encode(serialize($ver)).'>';
        }
echo'</form>';
  if($dwt=="0")
  {
	echo'<script type="text/javascript" language="javascript">
		document.forms[0].submit();	
	</script>';
}
echo'
	
</body>
</html>   
';
 /*function get_location_tmp($lat,$lng,&$placename)
{	
	$geoCodeURL = "http://nominatim.openstreetmap.org/reverse?format=xml&lat=".$lat."&lon=".$lng."&zoom=18&addressdetails=1";
	$xml=@simplexml_load_file($geoCodeURL);	
	foreach($xml->result[0]->attributes() as $a => $b) 
	{
		if($a=="lat")
		{
			$lat_local=$b;
		}
		else if($a=="lon")
		{
			$lon_local=$b;
		}
	}
	$lat=round($lat,4);
	$lng=round($lng,4);	
	$lat_local = round(floatval($lat_local),4);
	$lon_local = round(floatval($lon_local),4);

	//echo "lat1=".$lat."lng1=".$lng."lat2=".$lat_local."long2=".$lon_local."<br>";
	$distance="";
	calculate_distance_1($lat,$lat_local,$lng,$lon_local,&$distance);
	$placename=round($distance,2)." km from ".$xml->result;
}



function calculate_distance_1($lat1, $lat2, $lon1, $lon2, &$distance) 
{
	$lat1 = deg2rad($lat1);
	$lon1 = deg2rad($lon1);

	$lat2 = deg2rad($lat2);
	$lon2 = deg2rad($lon2);
	
	$delta_lat = $lat2 - $lat1;
	$delta_lon = $lon2 - $lon1;
	
	$temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
	$distance = 6378.1  * 2 * atan2(sqrt($temp),sqrt(1-$temp));
}*/

  function call_geocode($point)
  {          
    echo '
    <script type="text/javascript">          
      Geocode('.$point.'); 
    </script>
    ';
  } 
?>
