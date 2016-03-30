<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <style type="text/css">
        body {
        font-family: Verdana, Arial, sans serif;
        font-size: 11px;
        margin: 0px;
		width: 100%;
		height: 100%;
		margin: 0;
		padding: 0;
      }
	  table.directions{
	  	border: 0px solid;border-color:gray;
		height: 100%;
      }
      table.directions th {
		/*background-color:#9ECFD8;*/
		font-size: 11px;
      }
	  
      img {
        color: #000000;
      }
	 
	 
	    
	
    </style>
	
	<!-- Edit Below -->
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="csstab/css/animate.min.css"></script>
	<link href='csstab/css/animate.min.css' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="csstab/css/style.css" />
	<script src="../file/js/modernizr.js"></script>
</head>
<body>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>
	
	<link rel="stylesheet" type="text/css" href="datetimepicker-master/jquery.datetimepicker.css"/>
	<script src="datetimepicker-master/jquery.js"></script>
	<script src="datetimepicker-master/build/jquery.datetimepicker.full.js"></script>
	<script src="http://momentjs.com/downloads/moment.min.js"></script>
	<script>
	$.datetimepicker.setLocale('en');
	
	jQuery(function(){
		
		
		
	 jQuery('#datepicker_from').datetimepicker({
	 
	  onShow:function( ct ){
	   this.setOptions({
		    minDate:'-1970/01/01', // yesterday is minimum date
	  maxDate:'+1970/01/02', // and tommorow is maximum date calendar
		maxDate:jQuery('#datepicker_to').val()?jQuery('#datepicker_to').val():false
	   })
	  }
	 });
	 jQuery('#datepicker_to').datetimepicker({
	  
	  onShow:function( ct ){
	   this.setOptions({
		minDate:jQuery('#datepicker_from').val()?jQuery('#datepicker_from').val():false
	   })
	  }
	 
	 });
	});
	</script>
	
    <script type="text/javascript">
        var source, destination,source_destination; 
        var directionsDisplay;
        var directionsService = new google.maps.DirectionsService();
        google.maps.event.addDomListener(window, 'load', function () {
            new google.maps.places.SearchBox(document.getElementById('txtSource'));
            new google.maps.places.SearchBox(document.getElementById('txtDestination'));
            directionsDisplay = new google.maps.DirectionsRenderer({ 'draggable': true });
        });

		google.maps.event.addDomListener(window, 'load', initialize);
		var map;
		
		var check_drag=0;
		function initialize() {
			//var x_height1 =  screen.height;
			var x_height1 =  $(window).height();
			var per_val= (x_height1*6.3)/100;
			//alert (per_val);
			document.getElementById("pre_route_chk").checked = false; 
			var x_height =  (x_height1-per_val) + "px";
			//var x_height =  (x_height1) + "px";
			document.getElementById('dvMap').setAttribute("style","height:"+x_height);
			map = new google.maps.Map(document.getElementById('dvMap'), {
			  center: {lat: 21.0000, lng: 78.0000},
			  zoom: 5
			});
			
			google.maps.event.addListener(directionsDisplay, 'directions_changed', function() {
				if(check_drag==0)
				{
					// do nothing
				}
				else if(check_drag==1)
				{
					//alert("change");			  
					AddRoute(directionsDisplay.directions);
				}
						 
			    
			});

		}
		
		
		var markers=new Array();
		var infoWindow ;
		var prev_infoWindow =false; 
		var gmarkers = [];
		
		function deleteOverlays() 
		{
			for (var i = 0; i < markers.length; i++) 
			{
				markers[i].setMap(null);
			}
		}
		
		function removeMarkers(){
			for(i=0; i<gmarkers.length; i++){
				gmarkers[i].setMap(null);
			}
		}
		
		function AddRoute(result) {
			var bounds = new google.maps.LatLngBounds();
			var fromDate= document.getElementById('datepicker_from').value;
			var toDate= document.getElementById('datepicker_to').value;
			removeMarkers();
			markers=new Array();
			gmarkers = [];
			pointsArray = result.routes[0].overview_path;
			//alert(pointsArray.length);
			var end_tail=pointsArray.length;
			lat_tot1=pointsArray[0].lat();
			lon_tot1=pointsArray[0].lng();
			lat_tot2=pointsArray[end_tail-1].lat();
			lon_tot2=pointsArray[end_tail-1].lng();
			var tot_dist_cal=calculate_distance(lat_tot1, lat_tot2, lon_tot1, lon_tot2);
			var chk_distance=10.0;
			if(tot_dist_cal < 10.0)
			{
				chk_distance=2.0;
			}
			else if(tot_dist_cal > 50.0 && tot_dist_cal <= 100.0)
			{
				chk_distance=10.0;
			}
			else if(tot_dist_cal > 100.0 && tot_dist_cal <= 500.0)
			{
				chk_distance=20.0;
			}
			else if(tot_dist_cal > 500.0 && tot_dist_cal <= 1000.0)
			{
				chk_distance=50.0;
			}
			else if(tot_dist_cal > 1000.0 )
			{
				chk_distance=100.0;
			}
			//alert(tot_dist_cal);
			var i = 0;
			var j = 0;
			var lat1="";var lon1="";var lat2="";var lon2="";
			var flag_show_wr=0;
			var contentString=new Array();
			var strURL_tmp_later_send=new Array();
			for (j = 0; j < pointsArray.length; j++)
			{
				if(j==0)
				{
					lat1=pointsArray[j].lat();
					lon1=pointsArray[j].lng();
					lat2=pointsArray[j].lat();
					lon2=pointsArray[j].lng();
					flag_show_wr=1;
				}
				else
				{
					lat2=pointsArray[j].lat();
					lon2=pointsArray[j].lng();
					var dist_cal=calculate_distance(lat1, lat2, lon1, lon2);
					if(dist_cal > chk_distance || j == (end_tail-1) )
					{
						lat1=pointsArray[j].lat();
						lon1=pointsArray[j].lng();
						flag_show_wr=1;
					}
					else
					{
						flag_show_wr=0;
					}
				}
				
				if(flag_show_wr==1)
				{
					//--to to code for geting weather data---------//
					//var strURL="client_receiver_weather.php?lat="+pointsArray[j].lat()+"&lon="+pointsArray[j].lng();
					var strURL="json_manipulator.php?lat="+pointsArray[j].lat()+"&lon="+pointsArray[j].lng()+"&fromDate="+fromDate+"&toDate="+toDate;												
					var req = getXMLHTTP();
					req.open("GET", strURL, false); //third parameter is set to false here
					req.send(null);  
					var place_name_temp_param = req.responseText; 							
					//place_name_temp_param =place_name_temp_param.split(":");	
					place_name_temp_param =place_name_temp_param.split("~`");						
					//var distance = calculate_distance(user_lat, place_name_temp_param[1], user_lng, place_name_temp_param[2]);							
					/*var address="";
					if(place_name_temp_param[0]!="")
					{
						address = place_name_temp_param[0];
					}
					else
					{
						address = "Unknown Location";
					}*/
					 //alert(address);   
					//-----------------------------------------------//
					i++;
					var myLatLng = {lat: pointsArray[j].lat(), lng: pointsArray[j].lng()};
					var position = new google.maps.LatLng(pointsArray[j].lat(), pointsArray[j].lng());
					strURL_tmp_later_send[i]="get_location.php?lat="+pointsArray[j].lat()+"&lon="+pointsArray[j].lng();
					bounds.extend(position);
					contentString[i] = place_name_temp_param[0];
				

					markers = new google.maps.Marker ({
												position:myLatLng,
												map: map,
												icon:'icon/'+place_name_temp_param[1]
												});
					
					google.maps.event.addListener(markers, 'click', (function(markers, i) {
						
						return function() {	
							if( prev_infoWindow ) {
							   prev_infoWindow.close();
							}
							var req_tmp = getXMLHTTP();
							req_tmp.open("GET", strURL_tmp_later_send[i], false); //third parameter is set to false here
							req_tmp.send(null);  
							var place_name = req_tmp.responseText;
							//alert(place_name);
							//infoWindow.setContent(contentString);
							infowindow = new google.maps.InfoWindow({
								content: "<center> <table  class='table-style-heading'><tr><th>Forecasting at "+place_name+"</th></tr></table></center><br>"+contentString[i]
							});
							infowindow.open(map, markers);
							prev_infoWindow=infowindow;
						}
					}) (markers, i));
					gmarkers.push(markers);
					map.fitBounds(bounds);
				}
				
												
			}
			
		}
		
		///////////////////////////////////////////////////////////////////////////////////////
        function GetRoute() {
			check_drag=0;
			deleteOverlays();
            var india = new google.maps.LatLng(21.0000, 78.0000);
            var mapOptions = {
                zoom: 12,
                center: india
            };
			
			
            map = new google.maps.Map(document.getElementById('dvMap'), mapOptions);
            directionsDisplay.setMap(map);
            directionsDisplay.setPanel(document.getElementById('dvPanel'));
			
			var bounds = new google.maps.LatLngBounds();
			
			var fromDate= document.getElementById('datepicker_from').value;
			var toDate= document.getElementById('datepicker_to').value;

            //*********DIRECTIONS AND ROUTE**********************//
			var x = document.getElementById("pre_route_chk").checked;
			if(x==false)
			{
				source = document.getElementById("txtSource").value;
				destination = document.getElementById("txtDestination").value;
			}
            else
			{
				source_destination=document.getElementById("selSource").value;
				//alert(source_destination);
				if(source_destination!='select')
				{
					var source_destination_tmp=source_destination.split(":");
					source=source_destination_tmp[0];
					destination=source_destination_tmp[1];
				}
			}
			
			//alert(source);
			//alert(destination);
            var request = {
                origin: source,
                destination: destination,
				//origin: "33.661565,73.041330",
				//destination: "33.662502,73.044061",
                travelMode: google.maps.TravelMode.DRIVING,
				provideRouteAlternatives:true
            };
            directionsService.route(request, function (response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
					//alert(status);
                    directionsDisplay.setDirections(response);
					check_drag=1;
					pointsArray = response.routes[0].overview_path;
					//alert(pointsArray.length);
					var end_tail=pointsArray.length;
					lat_tot1=pointsArray[0].lat();
					lon_tot1=pointsArray[0].lng();
					lat_tot2=pointsArray[end_tail-1].lat();
					lon_tot2=pointsArray[end_tail-1].lng();
					var tot_dist_cal=calculate_distance(lat_tot1, lat_tot2, lon_tot1, lon_tot2);
					var chk_distance=10.0;
					if(tot_dist_cal < 10.0)
					{
						chk_distance=2.0;
					}
					else if(tot_dist_cal > 50.0 && tot_dist_cal <= 100.0)
					{
						chk_distance=10.0;
					}
					else if(tot_dist_cal > 100.0 && tot_dist_cal <= 500.0)
					{
						chk_distance=20.0;
					}
					else if(tot_dist_cal > 500.0 && tot_dist_cal <= 1000.0)
					{
						chk_distance=50.0;
					}
					else if(tot_dist_cal > 1000.0 )
					{
						chk_distance=100.0;
					}
					//alert(tot_dist_cal);
					var i = 0;
					var j = 0;
					var lat1="";var lon1="";var lat2="";var lon2="";
					var flag_show_wr=0;
					var contentString=new Array();
					var strURL_tmp_later_send=new Array();
					//var infoWindow = new google.maps.InfoWindow();
					for (j = 0; j < pointsArray.length; j++)
					{
						
						//alert(pointsArray[j].lat());
						//arrayToBeReturned[i] = pointsArray[j].lat();
						//i++;
						//arrayToBeReturned[i] = pointsArray[j].lng();
						//i++;
						//alert(pointsArray[j].lng());
						if(j==0)
						{
							lat1=pointsArray[j].lat();
							lon1=pointsArray[j].lng();
							lat2=pointsArray[j].lat();
							lon2=pointsArray[j].lng();
							flag_show_wr=1;
						}
						else
						{
							lat2=pointsArray[j].lat();
							lon2=pointsArray[j].lng();
							var dist_cal=calculate_distance(lat1, lat2, lon1, lon2);
							if(dist_cal > chk_distance || j == (end_tail-1) )
							{
								lat1=pointsArray[j].lat();
								lon1=pointsArray[j].lng();
								flag_show_wr=1;
							}
							else
							{
								flag_show_wr=0;
							}
						}
						
						if(flag_show_wr==1)
						{
							//--to to code for geting weather data---------//
							//var strURL="client_receiver_weather.php?lat="+pointsArray[j].lat()+"&lon="+pointsArray[j].lng()+"&fromDate="+fromDate+"&toDate="+fromDate;							
							var strURL="json_manipulator.php?lat="+pointsArray[j].lat()+"&lon="+pointsArray[j].lng()+"&fromDate="+fromDate+"&toDate="+toDate;							
							var req = getXMLHTTP();
							req.open("GET", strURL, false); //third parameter is set to false here
							req.send(null);  
							var place_name_temp_param = req.responseText;
							//alert(place_name_temp_param);
							//place_name_temp_param =place_name_temp_param.split(":");	
							place_name_temp_param =place_name_temp_param.split("~`");								
							//var distance = calculate_distance(user_lat, place_name_temp_param[1], user_lng, place_name_temp_param[2]);							
							/*var address="";
							if(place_name_temp_param[0]!="")
							{
							    address = place_name_temp_param[0];
							}
							else
							{
								address = "Unknown Location";
							}*/
                             //alert(place_name_temp_param[0]);   
							//-----------------------------------------------//
							i++;
							var myLatLng = {lat: pointsArray[j].lat(), lng: pointsArray[j].lng()};
							var position = new google.maps.LatLng(pointsArray[j].lat(), pointsArray[j].lng());
							strURL_tmp_later_send[i]="get_location.php?lat="+pointsArray[j].lat()+"&lon="+pointsArray[j].lng();
							bounds.extend(position);
							
							/*contentString[i] = '<table bgcolor="white"><tr><th align=left bgcolor="white">Temp.</th><td align=left>24 deg.Cel</td></tr>'+
							'<tr><th align=left bgcolor="white">Status.</th><td align=left>Heavy Rainfall</td></tr>'+							
							'<tr><th align=left bgcolor="white">Lat</th><td align=left>'+pointsArray[j].lat()+'</td></tr>'+
							'<tr><th align=left bgcolor="white">Lng</th><td align=left>'+pointsArray[j].lng()+'</td></tr>'+
							'</table>';*/
							//alert(place_name_temp_param[0]);
							contentString[i] = place_name_temp_param[0];
							
							//Create and open InfoWindow.
							/*infowindow = new google.maps.InfoWindow({
										content: contentString
									});*/
							if(place_name_temp_param[1]!="")
							{
								markers = new google.maps.Marker ({
														position:myLatLng,
														map: map,
														icon:'icon/'+place_name_temp_param[1]
														});
														
							}
							else{
								markers = new google.maps.Marker ({
														position:myLatLng,
														map: map,
														icon:'icon/sunny.png'
														});
														
							}
							
							
							
							google.maps.event.addListener(markers, 'click', (function(markers, i) {
							
							
								return function() {	
									if( prev_infoWindow ) {
									   prev_infoWindow.close();
									}
									//alert(strURL_tmp_later_send);
									var req_tmp = getXMLHTTP();
									req_tmp.open("GET", strURL_tmp_later_send[i], false); //third parameter is set to false here
									req_tmp.send(null);  
									var place_name = req_tmp.responseText;
									//alert(place_name);
									//infoWindow.setContent(contentString);
									infowindow = new google.maps.InfoWindow({
										content: "<center> <table  class='table-style-heading'><tr><th>Forecasting at "+place_name+"</th></tr></table></center><br>"+contentString[i]
									});
									
									//infowindow.setOptions({maxWidth: 1000});
									infowindow.open(map, markers);									
									prev_infoWindow = infowindow;
								}
							}) (markers, i));
							
					
							// Push your newly created marker into the array:
							gmarkers.push(markers);
							
							map.fitBounds(bounds);
							
							
						}
						
														
					}
					
                }
            });

            //*********DISTANCE AND DURATION**********************//
            var service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix({
                origins: [source],
                destinations: [destination],
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC,
				provideRouteAlternatives:true,
                avoidHighways: false,
                avoidTolls: false
            }, function (response, status) {
                if (status == google.maps.DistanceMatrixStatus.OK && response.rows[0].elements[0].status != "ZERO_RESULTS") {
                    var distance = response.rows[0].elements[0].distance.text;
                    var duration = response.rows[0].elements[0].duration.text;
                    var dvDistance = document.getElementById("dvDistance");
                    dvDistance.innerHTML = "";
                    dvDistance.innerHTML += "Distance: " + distance + "<br />";
                    dvDistance.innerHTML += "Duration:" + duration;
					
                } else {
                    alert("Unable to find the distance via road.");
                }
            });
			
			
			
			
        }
		
		/*function infoCallback(lat,lng,markerL) 
		{				
			return function() 
			{
				alert('in click'+markerL);
				var contentString='';
				var weather_status='Heavy Rainfall';
				if (infowindow) infowindow.close();
				infowindow = new google.maps.InfoWindow();
				//var latlng = new google.maps.LatLng(lat, lng);
				contentString='<table>'+
				'<tr>'+
				'<td class=\"live_td_css1\">Weathers Condition</td>'+
				'<td>:</td>'+
				'<td class=\"live_td_css2\">'+weather_status+'</td>'+
			   '</tr>'+
				'<tr>'+
				'<td class=\"live_td_css1\">Coordinates</td>'+
				'<td>:</td>'+
				'<td class=\"live_td_css2\">'+lat+','+lng+'</td>'+
			   '</tr>'+										   							
				'</table>';
					
				
					
				infowindow.setContent(contentString);
				alert('icontentString'+contentString);				
				infowindow.open(dvMap, markerL);
				alert('icontentString'+contentString);
				alert('dvMap'+dvMap);
			};
		}*/
				
		function addStepMarkers(result) {
		  polyline = new google.maps.Polyline({
			path: [],
			strokeColor: '#FF0000',
			strokeWeight: 3
		  });
		  infowindow.close();
		  for (var i=gmarkers.length-1;i>=0;i--) {
			gmarkers[i].setMap(null);
			gmarkers.pop();
		  }
		  if (startLocation && startLocation.marker) startLocation.marker.setMap(null);
		  if (endLocation && endLocation.marker) endLocation.marker.setMap(null);
		  var startLocation = new Object();
		  var endLocation = new Object();
		  var bounds = new google.maps.LatLngBounds();
		  for (var i=0;i<stepMarkers.length;i++) {
			stepMarkers[i].setMap(null);
		  }
		  stepMarkers = [];
		  for (var h = 0; h < result.routes.length; h++) {
			var route = result.routes[h];
			 alert("processing "+route.legs.length+" legs");
			// For each route, display summary information.
			var legs = route.legs;
				 for (i=0;i<legs.length;i++) {
				   if (i == 0) { 
					 startLocation.latlng = legs[i].start_location;
					 startLocation.address = legs[i].start_address;
					 startLocation.marker = createMarker(legs[i].start_location,"start",legs[i].start_address,"green");
				   } else { 
					 waypts[i] = new Object();
					 waypts[i].latlng = legs[i].start_location;
					 waypts[i].address = legs[i].start_address;
					 waypts[i].marker = createMarker(legs[i].start_location,"waypoint"+i,legs[i].start_address,"yellow");
				   }
				   endLocation.latlng = legs[i].end_location;
				   endLocation.address = legs[i].end_address;
				   var steps = legs[i].steps;
				   // alert("processing "+steps.length+" steps");
				   for (j=0;j<steps.length;j++) {
					 var nextSegment = steps[j].path;
					 var stepText = "";
					 if (j>0) stepText = "<a href='javascript:google.maps.event.trigger(stepMarkers["+(j-1)+"], \"click\")'>Prev<\/a>"
					 if (j<(steps.length-1)) {
					   if (stepText != "") { stepText += " - "; }
					   stepText += "<a href='javascript:google.maps.event.trigger(stepMarkers["+(j+1)+"], \"click\")'>Next<\/a>"
					 }
					 stepText = steps[j].instructions+"<br>"+stepText;
					 stepMarkers.push(createMarker(steps[j].start_location,"step",stepText,"blue"));
					 stepMarkers[stepMarkers.length-1].step_instructions = steps[j].instructions;
					 // alert("processing "+nextSegment.length+" points");
					 for (k=0;k<nextSegment.length;k++) {
					   polyline.getPath().push(nextSegment[k]);
					   bounds.extend(nextSegment[k]);
					 }
				   }
			 }
		   }
		    //alert("[1]polyline contains "+polyline.getPath().getLength()+" points");
		   return polyline;
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

		var distance = 6378.1 * 2 * Math.atan2(Math.sqrt(temp),Math.sqrt(1-temp));

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
		
		
		function myRoute()
		{
			var x = document.getElementById("pre_route_chk").checked;
			//alert(x);
			var sourcetype="";var destinationtype="";
			if(x==true)
			{
				//sourcetype='<select id="selSource" style="width: 180px" ><option value="select">Select Route Name</option> <option value="26.513629572238813, 80.24019241333008:26.48597690563101, 80.29186248779297">IIT Kanpur-Rawatpur</option> <option value="26.50241568176453, 80.25306701660156:26.856618973987032, 80.93524932861328">Kalyanpur-Lucknow</option>  </select>';
				//document.getElementById("sourceTypeSel").innerHTML=sourcetype;
				document.getElementById("sourceTypeName").innerHTML="Route Name:&nbsp;";
				
				//destinationtype='<select id="txtDestination" style="width: 180px" ><option value="select">Select Destination</option><option value="26.48597690563101, 80.29186248779297">Rawatpur</option><option value="26.856618973987032, 80.93524932861328">Lucknow</option>  </select>';
				//document.getElementById("destinationType").innerHTML=destinationtype;
				document.getElementById("sourceTypeSel").style.display='';
				document.getElementById("sourceType").style.display='none';
				document.getElementById("destinationTypeName").style.display='none';
				document.getElementById("destinationType").style.display='none';
				
			}
			else
			{
				document.getElementById("sourceTypeName").innerHTML="Source:&nbsp;";
				//destinationtype='<input type="text" id="txtDestination" value="Kanpur Central, Khanpur, Uttar Pradesh, India" style="width: 180px" />';
				//document.getElementById("destinationType").innerHTML=destinationtype;
				document.getElementById("sourceTypeSel").style.display='none';
				document.getElementById("sourceType").style.display='';
				document.getElementById("destinationTypeName").style.display='';
				document.getElementById("destinationType").style.display='';
				
			}
		}
		
    </script>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" class="directions">
		<tbody>
			<tr>
				<td align=left bgcolor="#FFE39E"  style='border-bottom: 1px solid;border-color:gray;width:20%'><div style="font-size: 12px;color:navy;"><img  src="icon/iespl.jpg" style='height:25px'>&nbsp;Welcome:<b><?php echo $user_name; ?></b>&nbsp;</div>
				 
				</td>
				<td colspan="1" align=left bgcolor="white"  style='border-bottom: 1px solid;border-color:gray;'>
					<table class="directions" bgcolor="white"  >
						<tr>
							<td><input type="hidden"  id="pre_route_chk" onclick="myRoute()" /><!--<input type="checkbox"  id="pre_route_chk" onclick="myRoute()" />--></td><th align="left"><!--MyRoutes&nbsp;--></th>
							
							<th align="left">
								<div id=sourceTypeName>Source:&nbsp;</div></th>
							<td>
								<div id=sourceTypeSel style='display:none;'>
									<select id="selSource" style="width: 180px" >
										<option value="select">Select Route Name</option>
										<!--<option value="26.513629572238813, 80.24019241333008:26.48597690563101, 80.29186248779297">IIT Kanpur-Rawatpur</option> 
										<option value="26.50241568176453, 80.25306701660156:26.856618973987032, 80.93524932861328">Kalyanpur-Lucknow</option> -->
										<?php
										
										foreach($serial_chk as $sn)
										{
											echo "<option value='$from_city_name[$sn]:$to_city_name[$sn]' > $from_city_name[$sn] - $to_city_name[$sn]</option>";
											echo $from_city_name[$sn].":".$to_city_name[$sn]."<br>";
										}
										?>
									</select>
								</div>
								<div id=sourceType><input type="text" id="txtSource" value="IIT Kanpur, Kalyanpur, Kanpur, Uttar Pradesh, India" style="width: 180px" /></div>
							</td>
							<th align="left"><div id=destinationTypeName>Destination:&nbsp;</th><td><div id=destinationType><input type="text" id="txtDestination" value="Kanpur Central, Khanpur, Uttar Pradesh, India" style="width: 180px" /></div></td>
							
							<th align="left">StartDate:&nbsp;</th><td><input type="text" id="datepicker_from" style="width: 70px" /></td>
							<th align="left">EndDate:&nbsp;</th><td><input type="text" id="datepicker_to" style="width: 70px" /></td>
							
							<td><input type="button" value="Get Route Forecast" onclick="GetRoute()" /></td>
							
						</tr>
					</table>
				</td>
				
			</tr>
			
			<tr>
				<td colspan="2">
					<div id="dvDistance">
					</div>
				</td>
			</tr>
			<tr>
				<tbody>
					<!--<tr><th>Map</th><th>Formatted Directions</th></tr>-->
					<!--<tr><th colspan=2>Map</th></tr>-->
					<tr>
						<td width=100% valign="top" colspan=2>
							<div id="dvMap" style="width: 100%; position: relative; background-color: rgb(229, 227, 223); overflow: hidden;">
							</div>
						</td>
						<!--<td valign="top">
							<div id="dvPanel" style="overflow: scroll; width: auto; height: 500px; direction: ltr;background-color:ghostwhite;font-size: 11px;">
								
							</div>
						</td>-->
						
							<div id="dvPanel" style="display:none;overflow: scroll; width: auto; direction: ltr;background-color:ghostwhite;font-size: 11px;">
								
							</div>
						
					</tr>
				</tbody>
			</tr>
			<tr>
				<td colspan=2> <div style='border-bottom: 1px solid;border-color:gray;' ></div></td>
			</tr>
			
			<!--<tr>
				<td colspan=1  bgcolor="#FFFACD">
					<table width=100%  style='border: 1px solid;border-color:gray;'>
						<tr valign=top style="text-align:center;font-weight:bold;color:navy;">
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/rainy.gif" style="height:35px"><br>Rainy</td>
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/showers.gif" style="height:35px"><br>Showers</td>
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/fair.gif" style="height:35px"><br>Fair</td>
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/fog.gif" style="height:35px"><br>Fog</td>
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/cloudy.gif" style="height:35px"><br>Cloudy</td>
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/blizzard.gif" style="height:35px"><br>Blizzard</td>
							
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/sunny.gif" style="height:35px"><br>Sunny</td>
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/hazy.gif" style="height:35px"><br>Hazy</td>
							
							
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/chance-storm.gif" style="height:35px"><br>Strom</td>
							
							
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/partly-cloudy.gif" style="height:35px"><br>Par.Cloudy</td>
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/m-cloudy.gif" style="height:35px"><br>m-Cloudy</td>
							
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/thunder-storm.gif" style="height:35px"><br>Thunder</td>
							
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/snow.gif" style="height:35px"><br>SnowFall</td>
							
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/wind.gif" style="height:35px"><br>Wind</td>
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/freezing-rain.gif" style="height:35px"><br>Freezing</td>
							<td bgcolor="#FFFACD"><img  src="bubble/icon-set/GIF/50x50/flurries.gif" style="height:35px"><br>Flurries</td>
							
							
						</tr>
					</table>
				</td>
				<td>
					<table width=100% style='border-bottom: 1px solid;border-color:gray;' >
						<tr>
							<td align=right><img  src="icon/logo.png" style='height:51px'></td>
						
							<td><span style="font-size: 14px;color:green;font-weight:bold;font-family:Comic Sans MS, cursive, sans-serif"><i>Nimbu<i></span>-<span style="font-size: 14px;color:red;font-weight:bold;"><i>Mirchi<i></span>&nbsp;</td>
						</tr>
					</table>
				</td>
			<tr>-->
		</tbody>
    </table>
    <br />
</body>
</html>
<?php
/*										
foreach($serial_chk as $sn)
{
	echo $from_city_name[$sn].":".$to_city_name[$sn]."<br>";
}*/
?>