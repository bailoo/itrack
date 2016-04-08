<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
?>
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
set_time_limit(3000);	
date_default_timezone_set("Asia/Kolkata");
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('xmlParameters.php');
include_once("report_title.php");
include_once('parameterizeData.php');
include_once('data.php');
include_once("sortXmlData.php");
include_once("getXmlData.php");
include_once("calculate_distance.php");;
include_once("get_location_lp_track_report.php");

include_once("area_violation/check_with_range.php");
include_once("area_violation/pointLocation.php");
include_once("util.hr_min_sec.php");


$DEBUG = 0;
$v_size=count($vehicle_serial);
if($DEBUG) echo "vsize=".$v_size;

$device_str= $_POST["vehicleserial_prev"];
$device_str = explode('#',$device_str);
//echo "device_str1=".$device_str[0]."<br>";
//echo "device_str2=".$device_str[1]."<br>";
$vserial = explode(':',$device_str[0]);
$vsize=sizeof($vserial);
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

$userInterval = $_POST['user_interval'];




	$sortBy='h';
	$firstDataFlag=0;
	$endDateTS=strtotime($date2);
	$dataCnt=0;	
	//$userInterval = "0";
	$requiredData="All";
	
	$parameterizeData=new parameterizeData();
	$ioFoundFlag=0;
	
	$parameterizeData->latitude="d";
	$parameterizeData->longitude="e";
	$parameterizeData->speed="f";
	
	$finalVNameArr=array();

	for($i=0;$i<$vsize;$i++)
	{
		$dataCnt=0;
                //echo "vserial=".$vserial[$i]."<br>";
		$vehicle_info=get_vehicle_info($root,$vserial[$i]);
		$vehicle_detail_local=explode(",",$vehicle_info);
		$finalVNameArr[$i]=$vehicle_detail_local[0];
		//echo "vehcileName=".$finalVNameArr[$i]." vSerial=".$vehicle_detail_local[0]."<br>";
		
		$ioArr=explode(":",$vehicle_detail_local[7]);
		$ioFoundFlag=0;
		$ioArrSize=sizeof($ioArr);
		for($z=0;$z<$ioArrSize;$z++)
		{
			$tempIo=explode("^",$ioArr[$z]);
			//echo "io=".$ioArr[$z]."<br>";
			if($tempIo[1]=="temperature")
			{
				$ioFoundFlag=1;
				$parameterizeData->temperature=$finalIoArr[$tempIo[0]];
			}
		}
			
		$LastSortedDate = getLastSortedDate($vserial[$i],$datefrom,$dateto);
		$SortedDataObject=new data();
		$UnSortedDataObject=new data();
		
		if(($LastSortedDate+24*60*60)>=$endDateTS) //All sorted data
		{	
			//echo "in if1";
			$type="sorted";
			readFileXml($vserial[$i],$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
		}
		else if($LastSortedDate==null) //All Unsorted data
		{
			//echo "in if2";
			$type="unSorted";
			readFileXml($vserial[$i],$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
		}
		else //Partially Sorted data
		{
			$LastSDate=date("Y-m-d",$LastSortedDate+24*60*60);
			//echo "in else";
			$type="sorted";					
			readFileXml($vserial[$i],$date1,$date2,$datefrom,$LastSDate,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
		
			$type="unSorted";
			readFileXml($vserial[$i],$date1,$date2,$LastSDate,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
		}
	
		/*echo "udt1=".$UnSortedDataObject->deviceDatetime[0]."<br>";
		echo "udt2=".$UnSortedDataObject->deviceDatetime[1]."<br>";	
		echo "udt1=".$UnSortedDataObject->speedData[0]."<br>";
		echo "udt2=".$UnSortedDataObject->speedData[1]."<br>";
		echo "<br><br>";*/
		
		if(count($SortedDataObject->deviceDatetime)>0)
		{
			/*echo "psdt1=".$SortedDataObject->deviceDatetime[0]."<br>";
		echo "psdt2=".$SortedDataObject->deviceDatetime[1]."<br>";	
		echo "psp1=".$SortedDataObject->speedData[0]."<br>";
		echo "psp2=".$SortedDataObject->speedData[1]."<br>";
		echo "<br><br>";*/
			$prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
			for($obi=0;$obi<$prevSortedSize;$obi++)
			{			
				$finalDateTimeArr[$i][]=$SortedDataObject->deviceDatetime[$obi];
				$finalLatitudeArr[$i][]=$SortedDataObject->latitudeData[$obi];
				$finalLongitudeArr[$i][]=$SortedDataObject->longitudeData[$obi];
				$finalSpeedArr[$i][]=$SortedDataObject->speedData[$obi];
				if($ioFoundFlag==1)
				{
					$finalTemperatureArr[$i][]=$SortedDataObject->temperatureIOData[$obi];
				}
				else
				{
					$finalTemperatureArr[$i][]="0.0";
				}
				///$dataCnt++;
			}
		}
		if(count($UnSortedDataObject->deviceDatetime)>0)
		{
			$sortObjTmp=sortData($UnSortedDataObject,$sortBy,$parameterizeData);
			//var_dump($sortObjTmp);
			/*echo"sdt1=".$sortObjTmp->deviceDatetime[0]."<br>";
			echo "sdt2=".$sortObjTmp->deviceDatetime[1]."<br>";	
			echo "ss1=".$sortObjTmp->speedData[0]."<br>";
			echo "ss2=".$sortObjTmp->speedData[1]."<br>";
			echo "<br><br>";*/
			$sortedSize=sizeof($sortObjTmp->deviceDatetime);
			for($obi=0;$obi<$sortedSize;$obi++)
			{				
				$finalDateTimeArr[$i][]=$sortObjTmp->deviceDatetime[$obi];	
				$finalLatitudeArr[$i][]=$sortObjTmp->latitudeData[$obi];
				$finalLongitudeArr[$i][]=$sortObjTmp->longitudeData[$obi];	
				$finalSpeedArr[$i][]=$sortObjTmp->speedData[$obi];
				if($ioFoundFlag==1)
				{
					$finalTemperatureArr[$i][]=$sortObjTmp->temperatureIOData[$obi];
				}
				else
				{
					$finalTemperatureArr[$i][]="0.0";
				}
				//$dataCnt++;
			}
		}
		$SortedDataObject=null;			
		$sortObjTmp=null;
		$UnsortedDataObject =null;
			
	}
	$parameterizeData=null;	



for($i=0;$i<$vsize;$i++)
{
	//echo "vserial=".$vserial[$i]."<br>";
	$innerSize=0;
	$innerSize=sizeof($finalDateTimeArr[$i]);	
	
	$halt_flag = 0;	
	$firstData = 0;
	$distance =0.0;
	$firstdata_flag =0;
	$interval = $userInterval*60; 	
	for($j=0;$j<$innerSize;$j++)
	{
		$speed = $finalSpeedArr[$i][$j];
		$datetime=$finalDateTimeArr[$i][$j];
		$temperature=$finalTemperatureArr[$i][$j];
		
		if($firstdata_flag==0)
		{
			$halt_flag = 0;
			$firstdata_flag = 1;
			//$vserial=$vehicle_serial;					
			$lat_ref = $finalLatitudeArr[$i][$j];		
			$lng_ref = $finalLongitudeArr[$i][$j];			
			$datetime_ref = $datetime;
			$tmp_ref = $temperature;                  	
			$date_secs1 = strtotime($datetime_ref);
			$date_secs1 = (double)($date_secs1 + $interval);          	
		}           	
		//echo "<br>k2=".$k2."<br>";                	
		else
		{   
			$lat_cr = $finalLatitudeArr[$i][$j];		
			$lng_cr = $finalLongitudeArr[$i][$j];

			//$datetime_tmp1 = explode("=",$datetime_tmp[0]);
			$datetime_cr = $datetime;
			$tmp_cr = $temperature;
			//$time2 = $datetime[$i][$j];											
			$date_secs2 = strtotime($datetime_cr);
			//echo "<br>str=".$lat_ref.", ".$lat_cr.", ".$lng_ref." ,".$lng_cr.", ". $datetime_cr;
			calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, $distance);
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
					$halt_dur =  ($stoptime - $starttime);
				
					if($halt_dur >= $interval)
					{						
						if((sizeof($geo_id1)>0) && ($geo_id1[0]!="") )
						{                                                                                            
							$exclude_flag = 1;
							$geo_status = 1;
							for($j=0;$j<sizeof($geo_id1);$j++)
							{                                                                                                    
								include('action_halt_exclusion.php');
								if($geo_coord!="")
								{                
									check_with_range($lat_ref, $lng_ref, $geo_coord, $geo_status);
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
								$imei[]=$vserial[$i];
								$vname[]=$finalVNameArr[$i];
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
						
							$imei[]=$vserial[$i];
							$vname[]=$finalVNameArr[$i];
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
	}
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
					check_with_range($lat_ref, $lng_ref, $geo_coord, $geo_status);
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
				$imei[]=$vserial[$i];
				$vname[]=$finalVNameArr[$i];
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
			$imei[]=$vserial[$i];
			$vname[]=$finalVNameArr[$i];
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
	}   
}
?>
	                                                                                                                                                    
<?php
//echo''; 
echo'<form method="post" action="action_report_halt_1.php" target="_self">  
		<div id="progress"></div>
		<div id="delay"></div>';		
		$size_vserial = sizeof($vserial); 		
		$place_name_arr=array();
	
			//print_r($imei);
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
