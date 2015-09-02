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
				//if((address.indexOf("NH") ==-1) && (address.indexOf("National Highway") ==-1) && (address.indexOf("State Highway") ==-1) && (address.indexOf("SH") ==-1))
				if(address!="")
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
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

set_time_limit(3000);	
date_default_timezone_set("Asia/Kolkata");  
include_once("calculate_distance.php");
include_once("report_title.php");

include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('lastRecordData.php');
include_once("getXmlData.php");    

$selectVehicleImei = $_POST['vehicleserial'];
$selectedAccountId = $_POST['selected_accountid'];  
$sortBy="h";    
//echo "deviceImeiNo=".$selectVehicleImei." selectedAccountId=".$selectedAccountId."<br>";  
$parameterizeData=new parameterizeData();
$parameterizeData->latitude='d';
$parameterizeData->longitude='e';
$LastRecordObject=new lastRecordData();	
$LastRecordObject=getLastRecord($selectVehicleImei,$sortBy,$parameterizeData);
//var_dump($LastRecordObject);
if(!empty($LastRecordObject))
{        
    $latFirst = $LastRecordObject->latitudeLR[0];
    $lngFirst = $LastRecordObject->longitudeLR[0];       
}
global $distanceFlag;
$distanceFlag=0;


global $deviceimeiArr;
global $vehiclenameArr;
global $latArr;
global $lngArr;
global $distanceArr;
    
$deviceimeiArr=array();
$vehiclenameArr=array();
$latArr=array();
$lngArr=array();
$distanceArr=array();
PrintAllVehicle($root, $selectedAccountId,$selectVehicleImei,$latFirst,$lngFirst);

function PrintAllVehicle($root, $local_account_id,$selectVehicleImei,$latFirst,$lngFirst)
{  
    global $vehicleid;
    global $serial;
    global $vehicle_cnt;
    global $distanceFlag;
    
    global $deviceimeiArr;
    global $vehiclenameArr;
    global $latArr;
    global $lngArr;
    global $distanceArr;
    //$distanceFlag=0;
    global $title;	
    $type = 0;
    $sortBy="h";
    global $current_date;
    $vehicle_name_arr=array();
    $imei_arr=array();
    $vehicleid_or_imei_arr=array();
    $vehicle_color=array();
    if($root->data->AccountID==$local_account_id)
    {
        $td_cnt =0;
        for($j=0;$j<$root->data->VehicleCnt;$j++)
        {			    
            $vehicle_id = $root->data->VehicleID[$j];
            $vehicle_name = $root->data->VehicleName[$j];
            $vehicle_imei = $root->data->DeviceIMEINo[$j];
            $mobile_number = $root->data->MobileNumber[$j];
            if($vehicle_id!=null)
            {
                for($i=0;$i<$vehicle_cnt;$i++)
                {
                    if($vehicleid[$i]==$vehicle_id)
                    {
                        break;
                    }
                }			
                if($i>=$vehicle_cnt)
                {
                    $vehicleid[$vehicle_cnt]=$vehicle_id;
                    $vehicle_cnt++;
                    ///echo "firstImei=".trim($selectVehicleImei)."SecondImei=".trim($vehicle_imei)."<br>";
                    if(trim($selectVehicleImei)!=trim($vehicle_imei))
                    {
                        $parameterizeData=null; 
                        $parameterizeData=new parameterizeData();
                        $parameterizeData->latitude='d';
                        $parameterizeData->longitude='e';
                        $LastRecordObject=null;
                        $LastRecordObject=new lastRecordData();	
                        //echo "imei=".$imei."<br>";
                        $LastRecordObject=getLastRecord($vehicle_imei,$sortBy,$parameterizeData);
                        //var_dump($LastRecordObject);
                        if(!empty($LastRecordObject))
                        {        
                            $latNext = $LastRecordObject->latitudeLR[0];
                            $lngNext = $LastRecordObject->longitudeLR[0]; 
                            calculate_distance($latFirst, $latNext, $lngFirst, $lngNext, $distance);
                            //echo "distance=".$distance."<br>";
                            if($distance<=100)
                            {                              
                                $deviceimeiArr[]=$vehicle_imei;
                                //echo "imie=".$vehicle_imei."<br>";
                                $distanceArr[]=round($distance,2);
                                $vehiclenameArr[]=$vehicle_name;
                                $latArr[]=$latNext;
                                $lngArr[]=$lngNext;
                            }
                        }                                            
                    }
                }
            }
        }
    }
	
    $ChildCount=$root->ChildCnt;
    for($i=0;$i<$ChildCount;$i++)
    { 
            PrintAllVehicle($root->child[$i],$local_account_id,$select_vehicle,$latFirst,$lngFirst);
    }
}

echo '
<table class="processrequest" id="processrequestid">
	<tr>
		<td>
			Process Request Please Wait......
		</td>
	</tr>
</table>';
echo'<form method="post" action="action_report_nearby_location_next.php" target="_self">  
        <div id="progress"></div>
        <div id="delay"></div>';
        $place_name_arr=array();
        //print_r($imei);
        $size = sizeof($deviceimeiArr);
        //echo "<br>size:".$size; 
        $point = '"';
        $imei_str="";
        $vname_str="";
        $lat_str="";
        $lng_str="";
        $distance_str="";
        for($i=0;$i<$size;$i++)
        {			  	
            $imei_str=$imei_str.$deviceimeiArr[$i].":";
            $vname_str=$vname_str.$vehiclenameArr[$i].":";          
            $distance_str=$distance_str.$distanceArr[$i].":";
           
            $lat = substr($latArr[$i], 0, -1);
            $lng = substr($lngArr[$i], 0, -1);            
            $lat_str=$lat_str.$lat.":";
            $lng_str=$lng_str.$lng.":";
            
            $coord = $lat.",".$lng;
            //echo "coord=".$coord."<br>";
            if($i==0)
            {
                $point = $point.$coord;   
            }
            else
            {
                $point = $point.":".$coord;   
            }
        }        
        echo'<textarea id="geocodedPostcodes" name="geocodedPostcodes" cols="4" rows="1" style="visibility:hidden;"></textarea>
        <input type="hidden" name="imei_prev" value="'.$imei_str.'">
        <input type="hidden" name="vname_prev" value="'.$vname_str.'">
        <input type="hidden" name="distance_prev" value="'.$distance_str.'">
        <input type="hidden" name="lat_prev" value="'.$lat_str.'">
        <input type="hidden" name="lng_prev" value="'.$lng_str.'">';
  
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
                                No Near Vehicle Found
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
echo'</form>'; 	
?>	
</body>
</html>				
