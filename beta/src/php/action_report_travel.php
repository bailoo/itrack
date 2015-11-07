<html>
<head>
	<link rel="StyleSheet" href="../css/newwindow.css">
	<script src="location_js/site.js" type="text/javascript"></script>
	<script src="http://www.google.com/uds/api?file=uds.js&amp;v=2.0&amp;key="AIzaSyA9SrKxfDId98hLt4eqlV0CjtvC0X7O4u4" type="text/javascript"></script>
	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
	      <script src="http://open.mapquestapi.com/sdk/js/v7.1.s/mqa.toolkit.js?key=Fmjtd|luu821u229%2C20%3Do5-94anl0"></script>
	<script type="text/javascript">
	/* <![CDATA[ */

	var map;
	var address2="";	
	var user_lat = "";
	var user_lng = "";
	var xml_address_flag=0;
	var place_name_temp_param="";
	var distance_tmp=0;
	var address_flag=0;
	var tmp_distance_diff=0;
	
    var splitDataStart;
    var i=0;
	function GeocodeStart(data)
	{
		//alert("data1="+data);
		// split data
		i=0;
		document.getElementById("geocodedPostcodesStart").value = "";
			//alert("data1="+data);
		//var data = document.getElementById("postcodes").value;
		//var data = <?php echo "'".$point."'"; ?>;
		//alert(data);
		splitDataStart = data.split(":");
		//alert("in1");
		GeocodeNextStart();
	
		//alert("in2");		
	}	
	
	var separatorstart = ",";
	var delay = 0;
	var startinc=0;
	function GeocodeNextStart()
	{
		//alert("in geocode=");
		$('#progress').html((i+1).toString() + " of " + splitDataStart.length);		
		var splitLatLng = splitDataStart[i].replace("\r", "").split(",");	
		if (splitLatLng.length == 1) 
		{
			splitLatLng = splitDataStart[i].replace("\r", "").split("\t");
			separatorstart = "\t";
		}
		else
		{
			separatorstart = ",";
			var latLng = "("+splitLatLng[0]+","+splitLatLng[1]+")";
			//alert("in else");
			//alert("latLng="+latLng);
				//alert("latLng="+latLng);
				/*if(account_id_session=="212")
				 {
					alert("xml_latLng="+latLng);
				 }*/
				var strURL="select_landmark_travel.php?lm_lat="+splitLatLng[0]+"&lm_lng="+splitLatLng[1];
				//alert("strurl:"+strURL);
				var req = getXMLHTTP();
				req.open("GET", strURL, false); //third parameter is set to false here
				req.send(null);  				
				address2 = req.responseText+":"; 
				if(address2==":")
				{
				if(address_flag==0)
				{
                                   // alert("latlng="+latLng);
					var strURL="get_location_tmp_file.php?point_test="+latLng;
					//alert("strurl:"+strURL);
					var req = getXMLHTTP();
					req.open("GET", strURL, false); //third parameter is set to false here
					req.send(null);  
					place_name_temp_param = req.responseText; 
					//alert("place_name_temp_param1="+place_name_temp_param);
					place_name_temp_param =place_name_temp_param.split(":");		
				}
				user_lat = splitLatLng[0];
				user_lng = splitLatLng[1];
				
				var distance = calculate_distance(user_lat, place_name_temp_param[1], user_lng, place_name_temp_param[2]);
				address2 = distance+" km from "+place_name_temp_param[0]+ ":";
				tmp_distance_diff=distance_tmp-distance;
				//alert('diff='+Math.abs(tmp_distance_diff));
				if(Math.abs(tmp_distance_diff)<=2)
				{
					address_flag=1;
				}
				else
				{
					distance_tmp=distance;
					address_flag=0;
				}					
				distance_tmp=distance;
				//}
				//alert("ifeslseaddress2="+address2);
				//alert("address_with_distance_xml="+address2)
                            }
				 document.getElementById("geocodedPostcodesStart").value += address2; 
				  if (i < splitDataStart.length-1) 
				{	
					i++;
					//GeocodeNextStart();
					setTimeout("GeocodeNextStart()", 0);
					//GeocodeNextStart();			
				}
				else 
				{
					GeocodeEnd(document.getElementById("geopointend").value)				
				}
    }
    }
	
	var splitDataEnd;
    var i=0;
	var xml_address_flag_end=0;
	address2="";
	user_lat="";
	user_lng="";
	place_name_temp_param="";
	distance_tmp=0;
	address_flag=0;
	tmp_distance_diff=0;

	function GeocodeEnd(data)
	{
		//alert("data="+data);
		// split data
	i=0;
		document.getElementById("geocodedPostcodesEnd").value = "";
			//alert("data1="+data);
		//var data = document.getElementById("postcodes").value;
		//var data = <?php echo "'".$point."'"; ?>;
		//alert(data);
		splitDataEnd = data.split(":");
		GeocodeNextEnd();        
	}	
	
	//var separator = ",";
	//var delay = 0;
	function GeocodeNextEnd()
	{
		//alert("in geocode=");
		$('#progress').html((i+1).toString() + " of " + splitDataEnd.length);
		var geocoder = new google.maps.Geocoder();
		var splitLatLng = splitDataEnd[i].replace("\r", "").split(",");
		// if no commas, try tab
		if (splitLatLng.length == 1) 
		{
			splitLatLng = splitDataEnd[i].replace("\r", "").split("\t");
			separator = "\t";
		}
		else
		{
			separator = ",";
			var latLng = "("+splitLatLng[0]+","+splitLatLng[1]+")";
			//alert("in else 1");
			var strURL="select_landmark_travel.php?lm_lat="+splitLatLng[0]+"&lm_lng="+splitLatLng[1];
			//alert("strurl:"+strURL);
			var req = getXMLHTTP();
			req.open("GET", strURL, false); //third parameter is set to false here
			req.send(null);  				
			address2 = req.responseText+":";
			if(address2==":")
			{	
			if(address_flag==0)
			{
				var strURL="get_location_tmp_file.php?point_test="+latLng;
				//alert("strurl:"+strURL);
				var req = getXMLHTTP();
				req.open("GET", strURL, false); //third parameter is set to false here
				req.send(null);  
				place_name_temp_param = req.responseText; 
				//alert("place_name_temp_param1="+place_name_temp_param);
				place_name_temp_param =place_name_temp_param.split(":");
			}

				//alert('lat='+place_name_temp_param[1]+"lng="+place_name_temp_param[2]);
				user_lat = splitLatLng[0];
				user_lng = splitLatLng[1];
				/*if(account_id_session=="212")					 {
					alert("xml_user_lat="+user_lat+"lat="+place_name_temp_param[1]+"user_lng="+user_lng+"log="+place_name_temp_param[2]);
				 }*/
				//alert("user_lat="+user_lat+"lat="+place_name_temp_param[1]+"user_lng="+user_lng+"log="+place_name_temp_param[2]);
				var distance = calculate_distance(user_lat, place_name_temp_param[1], user_lng, place_name_temp_param[2]);
				//alert("distance="+distance);
				address2 = distance+" km from "+place_name_temp_param[0]+ ":";
				tmp_distance_diff=distance_tmp-distance;
				//alert('diff='+Math.abs(tmp_distance_diff));
				if(Math.abs(tmp_distance_diff)<=2)
				{
					address_flag=1;
				}
				else
				{
					distance_tmp=distance;
					address_flag=0;
				}					
				distance_tmp=distance;
				//}
				//alert("ifeslseaddress2="+address2);
				//alert("address_with_distance_xml="+address2)
				
				 document.getElementById("geocodedPostcodesEnd").value += address2; 
			
				if (i < splitDataEnd.length-1) 
				{
					i++;
					setTimeout("GeocodeNextEnd()", 0);		
				}			
				if(i == (splitDataEnd.length-1))
				{ 
					setTimeout("pageSubmit()", 4000);					
				}	
		  
			   
					
		}
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
		function pageSubmit()
		{
			document.forms[0].submit();	
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
<table class="processrequest" id="popup_message">
	<tr>
		<td>
			Process Request Please Wait......
		</td>
	</tr>
</table>';
//error_reporting(-1);
//ini_set('display_errors', 'On');
set_time_limit(3000);	
date_default_timezone_set("Asia/Kolkata"); 
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
$root=$_SESSION["root"];
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('data.php');
include_once("sortXmlData.php");
include_once("getXmlData.php");
//set_time_limit(300);
include_once("calculate_distance.php");
include_once("util.hr_min_sec.php");


$DEBUG = 0;
$v_size=count($vehicle_serial);
if($DEBUG) echo "vsize=".$v_size;

$device_str= $_POST["vehicleserial_prev"];
$vserial = explode(':',$device_str);
$vsize=sizeof($vserial);

$date1 = $_REQUEST["start_date"]." 00:00:00";;
$date2 =  $_REQUEST["end_date"]." 23:59:59";;

$date1 = str_replace('/', '-', $date1);  
$date2 = str_replace('/', '-', $date2); 

$date_1 = explode(" ",$date1);
$date_2 = explode(" ",$date2);
$datefrom = $date_1[0];
$dateto = $date_2[0];
$datetime_threshold = $_POST['threshold'];
$datetime_threshold=$datetime_threshold*60;
$sortBy='h';
$firstDataFlag=0;
$endDateTS=strtotime($date2);
$dataCnt=0;	

$requiredData="All";

$parameterizeData=new parameterizeData();
$ioFoundFlag=0;	
$parameterizeData->latitude="d";
$parameterizeData->longitude="e";
$parameterizeData->speed="f";
	
get_All_Dates($datefrom, $dateto, $userdates);    
$date_size = sizeof($userdates); 
$CurrentLat = 0.0;
$CurrentLong = 0.0;
$LastLat = 0.0;
$LastLong = 0.0;
$firstData = 0;
$start_time_flag = 0;
$distance_total = 0;
$distance_threshold = 0.200;
$distance_error = 0.100;
$distance_incriment =0.0;
$firstdata_flag =0;
$start_point_display =0;
$j=0;
$haltFlag==True;
$distance_travel=0;
for($i=0;$i<$vsize;$i++)
{
    //echo"vSerial=".$vserial[$i]."<br>";
    $vehicle_info=get_vehicle_info($root,$vserial[$i]);
    $vehicle_detail_local=explode(",",$vehicle_info);
    for($di=0;$di<=($date_size-1);$di++)
    {
        $SortedDataObject=new data();
        readFileXmlNew($vserial[$i],$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
        //var_dump($SortedDataObject);
        if(count($SortedDataObject->deviceDatetime)>0)
	{            
            $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
            if(count($SortedDataObject->deviceDatetime)>0)
            {
                for($obi=0;$obi<$prevSortedSize;$obi++)
                {
                    $DataValid = 0;
                    $lat = $SortedDataObject->latitudeData[$obi];
                    $lng = $SortedDataObject->longitudeData[$obi];
                    if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
                    {
                        $DataValid = 1;
                    }
                    if($DataValid==1)
                    {
                        $datetime=$SortedDataObject->deviceDatetime[$obi];
                        $speed = $SortedDataObject->speedData[$obi];

                        if($firstdata_flag==0)
                        {                                
                                $firstdata_flag = 1;
                                $haltFlag=True;
                                $distance_travel=0;                                    

                                $lat_S = $lat;
                                $lng_S = $lng;
                                $datetime_S = $datetime;
                                $datetime_travel_start = $datetime_S;              		
                                $lat_travel_start = $lat_S;
                                $lng_travel_start = $lng_S;                  
                                $start_point_display =0;  
                                $last_time = $datetime;
                                $last_time1 = $datetime;
                                $latlast = $lat;
                                $lnglast =  $lng;  
                                $max_speed	=0.0;								
                        }           	              	
                        else
                        {           
                                $lat_E = $lat;
                                $lng_E = $lng; 
                                $datetime_E = $datetime; 
                                calculate_distance($lat_S, $lat_E, $lng_S, $lng_E, $distance_incriment);								         		
                                $tmp_time_diff = (double)(strtotime($datetime) - strtotime($last_time)) / 3600;                

                                calculate_distance($latlast, $lat_E, $lnglast, $lng_E, $distance1);
                                $tmp_time_diff1 = ((double)( strtotime($datetime) - strtotime($last_time1) )) / 3600; 

                                if($tmp_time_diff1>0)
                                {
                                        $tmp_speed = ((double) ($distance_incriment)) / $tmp_time_diff;
                                        $tmp_speed1 = ((double) ($distance1)) / $tmp_time_diff1;
                                }
                                else
                                {
                                        $tmp_speed1 = 1000.0; //very high value
                                }

                                if($tmp_speed<300.0)
                                {
                                        $speeed_data_valid_time = $datetime;
                                }

                                if(( strtotime($datetime) - strtotime($speeed_data_valid_time) )>300) //data high speed for 5 mins
                                {
                                        $lat_S = $lat_E;
                                        $lng_S = $lng_E;
                                        $last_time = $datetime;
                                }

                                $last_time1 = $datetime;
                                $latlast = $lat_E;
                                $lnglast =  $lng_E;
                                //echo"maxspeed=".$max_speed."speed=".$speed."<br>";
                                if(($max_speed<$speed) && ($speed<200))
                                {
                                        $max_speed = $speed;
                                }


                                if($tmp_speed<300.0 && $tmp_speed1<300.0 && $distance_incriment>0.1 && $tmp_time_diff>0.0 && $tmp_time_diff1>0)
                                {
                                        if($haltFlag==True)
                                        {
                                                $datetime_travel_start = $datetime_E;
                                                $lat_travel_start = $lat_E;
                                                $lng_travel_start = $lng_E;
                                                $distance_travel = 0;
                                                $distance_total = 0;
                                                $distance_incrimenttotal = 0;
                                                $max_speed = 0.0;
                                                $haltFlag = False;
                                        }
                                        $distance_total += $distance_incriment;
                                        $distance_travel += $distance_incriment;
                                        $lat_S = $lat_E;
                                        $lng_S = $lng_E;
                                        $last_time = $datetime_E;
                                        $datetime_S = $datetime_E;

                                        $start_point_display =1;
                                        //$distance_incrimenttotal += $distance_incriment;
                                        // echo $datetime_E . " -- " . $lat_E .",". $lng_E . "\tDelta Distance = " . $distance_incriment . "\tTotal Distance = " . $distance_total . "\n";
                                }

                                $datetime_diff = strtotime($datetime_E) - strtotime($datetime_S);

                                //if(($distance_total>$distance_error) && ($datetime_diff > $datetime_threshold) && ($haltFlag==False))
                                //if(($distance_total>$distance_error) && ($datetime_diff > $datetime_threshold) && ($haltFlag==False))
                                if(($datetime_diff > $datetime_threshold) && ($haltFlag==False))
                                {

                                        //echo "maxspeed=".$max_speed."<br>";
                                        //newHalt($datetime_S, $datetime_E);
                                        $datetime_travel_end = $datetime_S;

                                        //echo "start_date1=".$datetime_travel_start."end_date1=".$datetime_travel_end."<br>";
                                        $lat_travel_end = $lat_S;
                                        $lng_travel_end = $lng_S;

                                        $travel_dur =  strtotime($datetime_travel_end) - strtotime($datetime_travel_start);                                                    
                                        $hms = secondsToTime($travel_dur);
                                        $travel_time_this = $hms[h].":".$hms[m].":".$hms[s];
                                        //echo "avg_speed=".$distance_travel."travel_dur=".$travel_dur."<br>";
                                        $avg_speed = $distance_travel/($travel_dur/3600);
                                        $distance_travel = round($distance_travel,2);
                                        $avg_speed = round($avg_speed,2);
                                        //echo "avg_speed=".$avg_speed."<br>";
                                        if($max_speed<$avg_speed)
                                        {
                                                $max_speed = $avg_speed;
                                        }

                                        $imei[]=$vserial[$i];
                                        $vname[]=$vehicle_detail_local[0];
                                        $time1[]=$datetime_travel_start;
                                        $time2[]=$datetime_travel_end;
                                        $lat_start[]=$lat_travel_start;
                                        $lng_start[]=$lng_travel_start;
                                        $lat_end[]=$lat_travel_end;
                                        $lng_end[]=$lng_travel_end;
                                        $distance_travelled[]=$distance_travel;
                                        $travel_time[]=$travel_time_this;
                                        $maxSpeed[]=$max_speed;
                                        $avgSpeed[]=$avg_speed;

                                        $haltFlag = True;
                                        $j=0;
                                }
                        }
                    }
                }
            }
            /*if($haltFlag==false)
            {
                    //newHalt($datetime_S, $datetime_E);
                    $datetime_travel_end = $datetime_E;
                    $lat_travel_end = $lat_S;
                    $lng_travel_end = $lng_S;
                    
                    $imei[]=$vserial[$i];
                    $vname[]=$finalVNameArr[$i];
                    $time1[]=$datetime_travel_start;
                    $time2[]=$datetime_travel_end;
                    $lat_start[]=$lat_travel_start;
                    $lng_start[]=$lng_travel_start;
                    $lat_end[]=$lat_travel_end;
                    $lng_end[]=$lng_travel_end;
                    $distance_travelled[]=$distance_travel;
                    $travel_time[]=$travel_time_this;
                    $maxSpeed[]=$maxSpeed;
                    $avgSpeed[]=$avg_speed;
             
                    $datetime_travel_start = $datetime_E;
                    $lat_travel_start = $lat_E;
                    $lng_travel_start = $lng_E; 
                    $distance_travel = 0;
                    // exit;
                    $datetime_S = $datetime_E;
                    $distance_total = 0;
                    $distance_incrimenttotal = 0;
                    $haltFlag = True;
            }*/
            $SortedDataObject=null;	
	}
    }		
}
$parameterizeData=null;	
$o_cassandra->close();

echo'<form method="post" action="action_report_travel_1.php" target="_self">
	<div id="progress"></div>
	<div id="delay"></div>';	
	echo'<br>';
	$threshold = $datetime_threshold/60;
	$vsize = sizeof($imei);
	$point_start = '"';
	$point_end = '"';
	$imei_str="";
	$vname_str="";
	$time1_str="";
	$time2_str="";
	$lat_start_str="";
	$lng_start_str="";
	$lat_end_str="";
	$lng_end_str="";
	$distance_travelled_str="";
	$travel_time_str=""; 
	$maxSpeedStr="";
	$avgSpeedStr="";
	unlink($xml_path);          
	for($i=0;$i<$vsize;$i++)
	{
		$imei_str=$imei_str.$imei[$i].":";
		$vname_str=$vname_str.$vname[$i].":";
		$time1_str=$time1_str.$time1[$i].",";
		$time2_str=$time2_str.$time2[$i].",";
		$lat_start_str=$lat_start_str.$lat_start[$i].":";
		$lng_start_str=$lng_start_str.$lng_start[$i].":";
		$lat_end_str=$lat_end_str.$lat_end[$i].":";
		$lng_end_str=$lng_end_str.$lng_end[$i].":";
		$distance_travelled_str=$distance_travelled_str.$distance_travelled[$i].":";
		$travel_time_str=$travel_time_str.$travel_time[$i].",";	
		$maxSpeedStr=$maxSpeedStr.$maxSpeed[$i].",";
		$avgSpeedStr=$avgSpeedStr.$avgSpeed[$i].",";		
		$lat_start[$i] = substr($lat_start[$i], 0, -1);
		$lng_start[$i] = substr($lng_start[$i], 0, -1);
		$coord_start = $lat_start[$i].",".$lng_start[$i]; 
		if($i==0)
		{
			$point_start = $point_start.$coord_start;   
		}
		else
		{
			$point_start = $point_start.":".$coord_start;   
		}
		$lat_end[$i] = substr($lat_end[$i], 0, -1);
		$lng_end[$i] = substr($lng_end[$i], 0, -1);
		$coord_end = $lat_end[$i].",".$lng_end[$i]; 
		if($i==0)
		{
			$point_end = $point_end.$coord_end;   
		}
		else
		{
			$point_end = $point_end.":".$coord_end;   
		}
	}
	//echo "imei1=".$imei_str."<br>vname1=".$vname_str."<br>lat1=".$lat_str."<br>lng1=".$lng_str."<br>arr_time1=".$arr_time1."<br>dep_time_str=".$dep_time_str."<br>duration_str=".$duration_str;
	echo'<textarea id="geocodedPostcodesStart" name="geocodedPostcodesStart" cols="40" rows="20" style="display:none"></textarea>
	<textarea id="geocodedPostcodesEnd" name="geocodedPostcodesEnd" cols="40" rows="20" style="display:none"></textarea>
	<input type="hidden" name="imei_prev" value="'.$imei_str.'">
	<input type="hidden" name="vname_prev" value="'.$vname_str.'">
	<input type="hidden" name="lat_start_prev" value="'.$lat_start_str.'">
	<input type="hidden" name="lng_start_prev" value="'.$lng_start_str.'">
	<input type="hidden" name="lat_end_prev" value="'.$lat_end_str.'">
	<input type="hidden" name="lng_end_prev" value="'.$lng_end_str.'">
	<input type="hidden" name="time1_prev" value="'.$time1_str.'">
	<input type="hidden" name="time2_prev" value="'.$time2_str.'">
	<input type="hidden" name="distance_travelled_prev" value="'.$distance_travelled_str.'">
	<input type="hidden" name="travel_time_prev" value="'.$travel_time_str.'">	
	<input type="hidden" name="max_speed_prev" value="'.$maxSpeedStr.'">
	<input type="hidden" name="avg_speed_prev" value="'.$avgSpeedStr.'">
	<input type="hidden" name="threshold" value="'.$threshold.'">				
	<input type="hidden" name="date1" value="'.$date1.'">
	<input type="hidden" name="date2" value="'.$date2.'">';
	if($point_start!='"')
	{
		$point_start = $point_start.'"';
	}
	if($point_end!='"')
	{
		$point_end = $point_end.'"';
		echo'<input type="hidden" id="geopointend" value='.$point_end.'>';
	}
	
	//echo "<br>pt=".$point_start."<br>";
	//echo "<br>pt=".$point_end."<br>"; 

	
	
	if($point_start!='"')
	{
		$flag=call_geocode_start($point_start);
	}
	else
	{
		 echo'<script type="text/javascript">  
				document.getElementById("popup_message").style.display="none";			
			</script>';
		echo'
			<table width="100%">
				<tr>
					<td>
						<table align="center">
							<tr>
								<td class="text" align="center">
									<b><font color="red">No Travel Report Found For Selected Vehicles</font></b>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
	}
	function call_geocode_start($point_start)
	{   
		
		echo'<script type="text/javascript">          
				GeocodeStart('.$point_start.'); 
			</script>';
		return 1;			
	} 
	echo'</form>'; 	
														
	/*if($report_type=='Person')
	{
		///// 1.CONVERT DATE TIME IN DD, MM, YYYY FORMA
		$datestr = explode(' ',$arr_time[$i]);
		$date_tmp = $datestr[0];
		$time_tmp = $datestr[1];

		$date_substr = explode('-',$date_tmp);
		$year = $date_substr[0];
		$month = $date_substr[1];
		$day = $date_substr[2];

		$display_datetime = $day."-".$month."-".$year." ".$time_tmp;
		$arr_time[$i] = $display_datetime;

		///// 2.CONVERT DATE TIME IN DD, MM, YYYY FORMAT
		$datestr = explode(' ',$dep_time[$i]);
		$date_tmp = $datestr[0];
		$time_tmp = $datestr[1];

		$date_substr = explode('-',$date_tmp);
		$year = $date_substr[0];
		$month = $date_substr[1];
		$day = $date_substr[2];

		$display_datetime = $day."-".$month."-".$year." ".$time_tmp;
		$dep_time[$i] = $display_datetime;                
		///////////////////////////////////////////////      
	} */            
	//include("get_location_test.php");
	
?>	
	
</body>
</html>				
