<?php


class GoogleMapHelper{
    
	//DEFAULT MAP OPTIONS (method map())
	var $defaultId = "map_canvas";								// Map canvas ID
	var $defaultWidth = "800px";								// Width of the map
	var $defaultHeight = "800px";								// Height of the map
	var $defaultStyle = "style";								// CSS style for the map canvas
	var $defaultZoom = 6;									// Default zoom
	var $defaultType = 'HYBRID';								// Type of map (ROADMAP, SATELLITE, HYBRID or TERRAIN)
	var $defaultCustom = "";								// Any other map option not mentioned before and available for the map.
																						// For example 'mapTypeControl: true' (http://code.google.com/apis/maps/documentation/javascript/controls.html)
	var $defaultLatitude = 40.69847032728747;						// Default latitude if the browser doesn't support localization or you don't want localization
	var $defaultLongitude = -73.9514422416687;						// Default longitude if the browser doesn't support localization or you don't want localization
	var $defaultLocalize = true;								// Boolean to localize your position or not
	var $defaultMarker = true;								// Boolean to put a marker in the position or not
	var $defaultMarkerTitle = 'My Position';						// Default marker title (HTML title tag)
	//var $defaultMarkerIcon = 'http://google-maps-icons.googlecode.com/files/home.png'; 	// Default icon of the marker
	var $defaultMarkerShadow = '';								// Default shadow for the marker icon
	var $defaultInfoWindow = true;								// Boolean to show an information window when you click the marker or not
	var $defaultWindowText = 'My Position';							// Default text inside the information window

	//DEFAULT MARKER OPTIONS (method addMarker())
	var $defaultInfoWindowM = true;								// Boolean to show an information window when you click the marker or not
	var $defaultWindowTextM = 'Marker info window';						// Default text inside the information window
	var $defaultmarkerTitleM = "Title";							// Default marker title (HTML title tag)
	//var $defaultmarkerIconM = "http://maps.google.com/mapfiles/marker.png";			// Default icon of the marker
	//var $defaultmarkerShadowM = "http://maps.google.com/mapfiles/shadow50.png";		// Default shadow for the marker icon

	//DEFAULT DIRECTIONS OPTIONS (method getDirections())
	var $defaultTravelMode = "DRIVING";							// Default travel mode (DRIVING, BICYCLING, TRANSIT, WALKING)
	var $defaultDirectionsDiv = null;							// Div ID to dump the step by step directions

	//DEFAULT POLYLINES OPTION (method addPolyline())
	var $defaultStrokeColor = "#FF0000";					// Line color
	var $defaultStrokeOpacity = 1.0;							// Line opacity 0.1 - 1
	var $defaultStrokeWeight = 2;							// Line Weight in pixels

	
	public function map($options = null,$cArr,$pArr)
	{
            
		if( $options != null )
		{
			extract($options);
		}
		$width=$div['width'];
		$height=$div['height'];
		
		if( !isset($id) )		$id = $this->defaultId;
		if( !isset($width) )		$width = $this->defaultWidth;
		if( !isset($height) )	$height = $this->defaultHeight;
		if( !isset($style) )		$style = $this->defaultStyle;
		if( !isset($zoom) )		$zoom = $this->defaultZoom;
		if( !isset($type) )		$type = $this->defaultType;
		if( !isset($custom) )		$custom = $this->defaultCustom;
		if( !isset($localize) )		$localize = $this->defaultLocalize;
		if( !isset($marker) )		$marker = $this->defaultMarker;
		if( !isset($markerIcon) ) 	$markerIcon = isset($this->defaultMarkerIcon)?$this->defaultMarkerIcon:'';
		if( !isset($markerShadow) )	$markerShadow = $this->defaultMarkerShadow;
		if( !isset($markerTitle) ) 	$markerTitle = $this->defaultMarkerTitle;
		if( !isset($infoWindow) ) 	$infoWindow = $this->defaultInfoWindow;
		if( !isset($windowText) ) 	$windowText = $this->defaultWindowText;
		if( !isset($lat) ) 	$latitude = $this->defaultInfoWindow;
		if( !isset($lng) ) 	$longitude = $this->defaultWindowText;
		
		$cPlant=$options['chillingPlant'];
		echo"	
		<style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
      .controls {
        margin-top: 16px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        background-color: #fff;
        padding: 0 11px 0 13px;
        width: 200px;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        text-overflow: ellipsis;
      }

      #pac-input:focus {
        border-color: #4d90fe;
        margin-left: -1px;
        padding-left: 14px;  /* Regular padding-left + 1. */
        width: 201px;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }
}

    </style>
<style>
 .live_td_css1 {
   color: black;
   //background-color: white;
   font-family: 'Lucida Grande', 'Arial', sans-serif;
   font-size: 11px;
   font-weight: bold;
   text-align: left;
        
 //  border: 2px solid black;
   white-space: nowrap;
 }
  .live_td_css2 {
   color: blue;
   //background-color: white;
   font-family: 'Lucida Grande', 'Arial', sans-serif;
   font-size: 11px;
   text-align: left;
        
  // border: 2px solid black;
   white-space: nowrap;
 }
 </style>";
		$map="";
		$map .="<div id='$id' style='width:$width; height:$height; $style'></div>
		<!--<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>-->
               <!--<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false'></script> -->    
			<script>
			//alert('test');
				var markers = new Array();
				var markersIds = new Array();
				 var infowindow;
				var geocoder = new google.maps.Geocoder();
                                var map;
				";

		$map .= "
			var initialLocation;
			//alert('test');
			var tmpval11=".$cPlant.";
			var customerArr= JSON.parse(".$cArr.");
			var plantArr= JSON.parse(".$pArr.");			 
			 //alert('len='+uniqueCustomerParseJson['lat']);
			//alert('test='+tmpval11);
initialLocation = new google.maps.LatLng({$lat},{$lng});
//alert('INIT='+initialLocation);
		    var browserSupportFlag =  new Boolean();
		    var {$id};
		    var myOptions = {
			 scaleControl: true,
		      zoom: {$zoom},
center: initialLocation,
		      mapTypeId: google.maps.MapTypeId.{$type}
		      ".(($custom != "")? ",$custom" : "")."

		    };
		    {$id} = new google.maps.Map(document.getElementById('$id'), myOptions);	
			
var input = (document.getElementById('pac-input'));		
  map_canvas.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
  var searchBox = new google.maps.places.SearchBox((input)); 
  google.maps.event.addListener(searchBox, 'places_changed', function() {
    var places = searchBox.getPlaces();

    /*for (var i = 0, marker; marker = markers[i]; i++) {
      marker.setMap(null);
    }*/

    // For each place, get the icon, place name, and location.
    markers = [];
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0, place; place = places[i]; i++) {
      var image = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      var marker = new google.maps.Marker({
        map: map_canvas,
        icon: image,
        title: place.name,
        position: place.geometry.location
      });

      markers.push(marker);

      bounds.extend(place.geometry.location);
    }
    map_canvas.fitBounds(bounds);
	
  });

  // Bias the SearchBox results towards places that are within the bounds of the
  // current map's viewport.
  google.maps.event.addListener(map_canvas, 'bounds_changed', function() {
    var bounds = map_canvas.getBounds();
    searchBox.setBounds(bounds);
  });	
 
setMultipleMarkerLast(map_canvas,customerArr,plantArr);

		";
		if(isset($latitude) && isset($longitude)) 
                    $map .="setCenterMap(new google.maps.LatLng({$latitude}, {$longitude}));";
		/*else if(isset($address)) 
                    $map .="var centerLocation = geocodeAddress('{$address}','setCenter'); setCenterMap(centerLocation);";*/
		else 
                    $map .="setCenterMap(new google.maps.LatLng({$this->defaultLatitude}, {$this->defaultLongitude}));";
		$map .= "
			function localize(){
		        if(navigator.geolocation) { // Try W3C Geolocation method (Preferred)
		            browserSupportFlag = true;
		            navigator.geolocation.getCurrentPosition(function(position) {
		              initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
		              {$id}.setCenter(initialLocation);";
		              if (!preg_match('/^https?:\/\//', $markerIcon)) 
					  //$markerIcon = $this->webroot . IMAGES_URL . '/' . $markerIcon;
					  $markerIcon=''; 
					  if($marker) $map .= "setMarker({$id},'center',initialLocation,'{$markerTitle}','{$markerIcon}','{$markerShadow}','{$windowText}', ".($infoWindow? 'true' : 'false').");";

		            $map .= "}, function() {
		              handleNoGeolocation(browserSupportFlag);
		            });

		        } else if (google.gears) { // Try Google Gears Geolocation
					browserSupportFlag = true;
					var geo = google.gears.factory.create('beta.geolocation');
					geo.getCurrentPosition(function(position) {
						initialLocation = new google.maps.LatLng(position.latitude,position.longitude);
						{$id}.setCenter(initialLocation);";
					  	if($marker) $map .= "setMarker({$id},'center',initialLocation,'{$markerTitle}','{$markerIcon}','{$markerShadow}','{$windowText}', ".($infoWindow? 'true' : 'false').");";

		            $map .= "}, function() {
		              handleNoGeolocation(browserSupportFlag);
		            });
		        } else {
		            // Browser doesn't support Geolocation
		            browserSupportFlag = false;
		            handleNoGeolocation(browserSupportFlag);
		        }
		    }

		    function handleNoGeolocation(errorFlag) {
		        if (errorFlag == true) {
		          initialLocation = noLocation;
		          contentString = \"Error: The Geolocation service failed.\";
		        } else {
		          initialLocation = noLocation;
		          contentString = \"Error: Your browser doesn't support geolocation.\";
		        }
		        {$id}.setCenter(initialLocation);
		        {$id}.setZoom(3);
		    }";

		    $map .= "
			function setMarker(map, id, position, title, icon, dateTime, vehicleName,vehicleSerial,vehcleNumber, speed,ioStr){
                        //alert('dateTime='+dateTime);
				var index = markers.length;
				markersIds[markersIds.length] = id;
				markers[index] = new google.maps.Marker({
		            position: position,
		            map: map,
		            icon: icon,		         
		            title:title
		        });
                        var contentString='';
                        contentString='<table>'+
                                                '<tr>'+
                                                    '<td>Vehicle Name</td>'+
                                                    '<td>:</td>'+
                                                    '<td>'+vehicleName+'</td>'+
                                                   '</tr>'+
                                                   '<tr>'+
                                                    '<td>Vehicle Serial</td>'+
                                                    '<td>:</td>'+
                                                    '<td>'+vehicleSerial+'</td>'+
                                                   '</tr>'+
                                                    '<tr>'+
                                                    '<td>Vehicle Number</td>'+
                                                    '<td>:</td>'+
                                                    '<td>'+vehcleNumber+'</td>'+
                                                   '</tr>'+
                                                   '<tr>'+
                                                    '<td>Date Time</td>'+
                                                    '<td>:</td>'+
                                                    '<td>'+dateTime+'</td>'+ioStr+
                                                   '</tr>';
                                         
                                            
                                       
                                         var address_tmp='';
						//var latlng = new google.maps.LatLng(lat, lng);
						//alert('latlng='+latlng);
						 
                                                //alert('address_tmp='+address_tmp);
                                            
                     //alert('contentString='+contentString);
		     	if(contentString != ''){
			     	var infowindow = new google.maps.InfoWindow();
			     	google.maps.event.addListener(markers[index], 'click', function() {
                                                geocoder.geocode({'latLng': position}, function(results, status) 
						{
							//alert('in gecode');
							if (status == google.maps.GeocoderStatus.OK) 
							{
								//alert('in gecode 1');
								if(results[1]) 
								{
									contentString=contentString+'<tr>'+
																'<td>Address</td>'+
																'<td>:</td>'+
																'<td>'+results[1].formatted_address+'</td>'+
																'</tr>'+ioStr+
																'</table>';									
									infowindow.setContent(contentString);
									infowindow.open(map_canvas, markers[index]);
								} 
								else 
								{
									alert('No results found');
								}
							} 
							else 
							{
								alert('Geocoder failed due to: ' + status);
							}
						});						
        			});
		        }
		     }";
			  $map .= "
			  function setMultipleMarkerLast(map,customerArr,plantArr)
				{ 
					var customer_plant_list = '';
					//alert('hi1');				
					deleteOverlays();					
					var latlngbounds = new google.maps.LatLngBounds();
					var markerIcon= 'images/station.png'; 
					for (var key in customerArr)
					{
						//alert(key + ' --> ' + customerArr[key]);
						var splitCustomerData=customerArr[key].split('^');
						var position;
						var lat_tmp=splitCustomerData[0];
						var lng_tmp=splitCustomerData[1];
						//alert('lat='+lat_tmp+'lng_tmp='+lng_tmp);
						if(splitCustomerData[0]!='' && splitCustomerData[1]!='')
						{
						//alert('lat1='+lat_tmp+'lng_tmp='+lng_tmp);
						position=new google.maps.LatLng(splitCustomerData[0], splitCustomerData[1]);
						
						latlngbounds.extend(position);
						
						//alert('lat3='+lat_tmp+'lng_tmp='+lng_tmp);
						var title='abc';
						var customerName=splitCustomerData[2];
						//alert('customerName='+map);
						//alert('customerName='+map);	
						var marker = new google.maps.Marker
						({
							position: position,	 map: map, icon: markerIcon
						});	
					
							markers.push(marker);
						//show_marker_on_map(lat_tmp,lng_tmp,marker,key,customerName,map,1);
						//customerName = '\''+customerName+'\'';
//						customer_plant_list += '<a href=\"javascript:show_marker_on_map('+lat_tmp+','+lng_tmp+','+marker+','+key+','+customerName+','+map+',1);\" style=\"font-size:12px;text-decoration:none;\"><font color=blue><strong>'+key+'</strong></font>&nbsp;(<font color=green>'+customerName+'</font>)</a><br><br>';
//  show_marker_on_map(marker);
  
customer_plant_list += '<a href=\"javascript:show_marker_on_map('+map+');\" style=\"font-size:12px;text-decoration:none;\"><font color=blue><strong>'+key+'</strong></font>&nbsp;(<font color=green>'+customerName+'</font>)</a><br><br>';

						google.maps.event.addListener
						(
							//alert('customerName2='+map);
							marker, 'click', infoCallback(lat_tmp,lng_tmp,marker,key,customerName,map,1)
						);
						}
					}
                                        
					//alert('hi2');	
					var markerIcon= 'images/customer3.png';
					for (var key in plantArr)
					{
						//alert(key + ' --> ' + plantArr[key]);
						var splitPlantData=plantArr[key].split('^');
						var position;
						var lat_tmp=splitPlantData[0];
						var lng_tmp=splitPlantData[1];
						//alert('lat='+lat_tmp+'lng_tmp='+lng_tmp);
						if(splitPlantData[0]!='' && splitPlantData[1]!='')
						{
						//alert('lat1='+lat_tmp+'lng_tmp='+lng_tmp);
						position=new google.maps.LatLng(splitPlantData[0], splitPlantData[1]);
					
						latlngbounds.extend(position);
						
						//alert('lat3='+lat_tmp+'lng_tmp='+lng_tmp);
						var title='abc';
						var plantName=splitPlantData[2];
						//alert('plantName='+plantName);
						var marker = new google.maps.Marker
						({
							position: position,	 map: map, icon: markerIcon
						});	
					
						markers.push(marker);					
						//plantName = '\''+plantName+'\'';
                                       //         customer_plant_list += '<a href=\"javascript:show_marker_on_map('+lat_tmp+','+lng_tmp+','+marker+','+key+','+plantName+','+map+',2); \"  style=\"font-size:12px;text-decoration:none;\"><font color=red><strong>'+key+'</strong></font>&nbsp;(<font color=green>'+plantName+'</font>)</a><br><br>';
			                        //alert(customer_plant_list);                
                                                google.maps.event.addListener
						(						
							marker, 'click', infoCallback(lat_tmp,lng_tmp,marker,key,plantName,map,2)
						);
						}
					}
                                        document.getElementById('customer_list').value = customer_plant_list;
                                        
					map.setCenter(latlngbounds.getCenter());					
					if(latarr.length>0 && latarr.length<2)
					{
						map.setZoom(10);
					}
					else
					{
						map.fitBounds(latlngbounds);
					}	
                                        show_marker_on_map(marker);
					function deleteOverlays() 
					{
						for (var i = 0; i < markers.length; i++) 
						{
							markers[i].setMap(null);
						}
					}
					
					//### DISPLAY CUSTOMER PLANT -LISTING
                                        //alert(customer_plant_list);		
                                        
				}
                                
				function infoCallback(lat,lng,marker,stationNo,stationName,map,type) 
				{	
                                    var station_no_tag = '';
                                    var station_name_tag = '';
                                    if(type==1) {
                                            station_no_tag = 'CustomerNo';
                                            station_name_tag = 'CustomerName';
                                    } else if(type==2) {
                                            station_no_tag = 'PlantNo';
                                            station_name_tag = 'PlantName';						
                                    }

                                    return function() 
                                    {
                                        //alert('in function');
                                        var contentString='';
                                               if (infowindow) infowindow.close();
                                        infowindow = new google.maps.InfoWindow();

                                        //alert('in function'+infowindow);
                                        var latlng = new google.maps.LatLng(lat, lng);
                                        //alert('latlng='+latlng);									
                                        contentString='<table width=\"60%\" border=\"0\">'+
                                                '<tr>'+
                                                '<td style=\"live_td_css1\">Latitude</td>'+
                                                '<td>:</td>'+
                                                '<td class=\"live_td_css2\">'+lat+'</td>'+
                                           '</tr>'+
                                           '<tr>'+
                                                '<td class=\"live_td_css1\">Longitude</td>'+
                                                '<td>:</td>'+
                                                '<td class=\"live_td_css2\">'+lng+'</td>'+
                                           '</tr>'+
                                                '<tr>'+										
                                                '<td class=\"live_td_css1\">'+station_no_tag+'</td>'+
                                                '<td>:</td>'+
                                                '<td class=\"live_td_css2\">'+stationNo+'</td>'+
                                           '</tr>'+	
                                                '<tr>'+										
                                                '<td class=\"live_td_css1\">'+station_name_tag+'</td>'+
                                                '<td>:</td>'+
                                                '<td class=\"live_td_css2\">'+stationName+'</td>'+
                                           '</tr>'+										   
                                                '</table>';										
                                                        infowindow.setContent(contentString);
                                                        infowindow.open(map, marker);


                                            //contentString='';
                                    };
				}
				
                                
				function show_marker_on_map(marker) 
				{	
                                        alert('k='+marker);
					/*var station_no_tag = '';
					var station_name_tag = '';
					if(type==1) {
						station_no_tag = 'CustomerNo';
						station_name_tag = 'CustomerName';
					} else if(type==2) {
						station_no_tag = 'PlantNo';
						station_name_tag = 'PlantName';						
					}
					
                                        //alert('in function');
                                         var contentString='';
                                                if (infowindow) infowindow.close();
                                        infowindow = new google.maps.InfoWindow();

                                        //alert('in function'+infowindow);
                                        var latlng = new google.maps.LatLng(lat, lng);
                                        //alert('latlng='+latlng);									
                                        contentString='<table width=\"60%\" border=\"0\">'+
                                                '<tr>'+
                                                '<td style=\"live_td_css1\">Latitude</td>'+
                                                '<td>:</td>'+
                                                '<td class=\"live_td_css2\">'+lat+'</td>'+
                                           '</tr>'+
                                           '<tr>'+
                                                '<td class=\"live_td_css1\">Longitude</td>'+
                                                '<td>:</td>'+
                                                '<td class=\"live_td_css2\">'+lng+'</td>'+
                                           '</tr>'+
                                                '<tr>'+										
                                                '<td class=\"live_td_css1\">'+station_no_tag+'</td>'+
                                                '<td>:</td>'+
                                                '<td class=\"live_td_css2\">'+stationNo+'</td>'+
                                           '</tr>'+	
                                                '<tr>'+										
                                                '<td class=\"live_td_css1\">'+station_name_tag+'</td>'+
                                                '<td>:</td>'+
                                                '<td class=\"live_td_css2\">'+stationName+'</td>'+
                                           '</tr>'+										   
                                                '</table>';										
                                                        infowindow.setContent(contentString);
                                                        infowindow.open(map, marker);


                                        //contentString='';*/
				}
				
				function infoCallbackLive(lat,lng,dateTimeArr,vSerial,vName,vNumber,speed,ioStr,marker,maxSpeed,maxHaltTime) 
				{						
					return function() 
					{
						 var contentString='';
							if (infowindow) infowindow.close();
						infowindow = new google.maps.InfoWindow();
						var latlng = new google.maps.LatLng(lat, lng);
						alert('latlng='+latlng);
						var geocoder = new google.maps.Geocoder();
						geocoder.geocode({'latLng': latlng}, function(results, status) 
						{
							//alert('in gecode');						
							if (status == google.maps.GeocoderStatus.OK) 
							{
								//alert('in gecode 1');
								if(results) 
								{
									var google_lat = '';
									var google_lng = '';
									var distance = '';
									var tmp_address;
									for (var j=0; j<results.length; j++) 
									{ 
										// alert('type='+results[j].types[0]);
										if ((results[j].types[0] == 'street_address') || (results[j].types[0] == 'route')) 
										{
											google_lat = results[j].geometry.location.lat();
											google_lng = results[j].geometry.location.lng();								
											distance = calculate_distance(lat, google_lat, lng, google_lng);											
											tmp_address=distance+' Km From '+results[j].formatted_address;
										}
									}
									var str=lat+','+lng;
									var strURL='src/php/select_landmark_marker.php?content='+str;			

									var req = getXMLHTTP();
									req.open('GET', strURL, false); //third parameter is set to false here
									req.send(null);
									var landmark = req.responseText;
									if(landmark!='')
									{
										tmp_address=landmark;
									}
									contentString='<table>'+
											'<tr>'+
											'<td>Vehicle Name</td>'+
											'<td>:</td>'+
											'<td>'+vName+'</td>'+
										   '</tr>'+
										   '<tr>'+
											'<td>Imei</td>'+
											'<td>:</td>'+
											'<td>'+vSerial+'</td>'+
										   '</tr>'+
											'<tr>'+
											'<td>Driver Name/Mob </td>'+
											'<td>:</td>'+
											'<td>'+vNumber+'</td>'+
										   '</tr>'+
										   '<tr>'+
										   '<tr>'+
											'<td>Speed</td>'+
											'<td>:</td>'+
											'<td>'+speed+'</td>'+
										   '</tr>'+
										   '<tr>'+
											'<tr>'+                                                    
											'<td>Date Time</td>'+
											'<td>:</td>'+
											'<td>'+dateTimeArr+'</td>'+
										    '</tr>'+
										    '<tr>'+
											'<td>Address</td>'+
											'<td>:</td>'+
											'<td>'+tmp_address+'</td>'+ioStr+ 
										    '<tr>'+
											'<td>Day Max Speed</td>'+
											'<td>:</td>'+
											'<td>'+maxSpeed+'</td>'+ 
											'<tr>'+
											'<td>Last Halt Time</td>'+
											'<td>:</td>'+
											'<td>'+maxHaltTime+'</td>'+
											'</tr>'+
											'</table>'+
											'<b><font color=black size=2>('+lat+','+lng+')</font></b>';										
												infowindow.setContent(contentString);
												infowindow.open(map_canvas, marker);
								} 
								else 
								{
									alert('No results found');
								}
							} 
							else 
							{
								alert('Geocoder failed due to: ' + status);
							}
							//contentString='';
                                                        
						}); 	
                                                 
					};
                                       
				}
				
				function infoCallbackLandmark(landmark,lat,lng,markerL) 
				{				
					return function() 
					{
						//alert('in click');
						var contentString='';
						if (infowindow) infowindow.close();
						infowindow = new google.maps.InfoWindow();
						//var latlng = new google.maps.LatLng(lat, lng);
						contentString='<table>'+
						'<tr>'+
						'<td class=\"live_td_css1\">Landmark Name</td>'+
						'<td>:</td>'+
						'<td class=\"live_td_css2\">'+landmark+'</td>'+
					   '</tr>'+
						'<tr>'+
						'<td class=\"live_td_css1\">Coordinates</td>'+
						'<td>:</td>'+
						'<td class=\"live_td_css2\">'+lat+','+lng+'</td>'+
					   '</tr>'+										   							
						'</table>';
						//alert('icontentString'+contentString);
						//alert('map_canvas'+map_canvas);			
						infowindow.setContent(contentString);
						infowindow.open(map_canvas, markerL);					 						
					};
                                        
				}";			
				
			$map .= "</script>";
                                                             
                        
			return $map;                        
		}


	/*
	* Method addMarker
	*
	* This method puts a marker in the google map generated with the method map
	*
	*
	* @author Marc Fernandez <marc.fernandezg (at) gmail (dot) com>
	* @param $map_id - Id that you used to create the map (default 'map_canvas')
	* @param $id - Unique identifier for the marker
	* @param mixed $position - string with the address or an array with latitude and longitude
	* @param array $options - options array
	* @return string - will return all the javascript script to add the marker to the map
	*
	*/
	
	function addMultipleMarker($map_id, $latarr,$lngarr,$datetimearr,$vehicle_serial_arr,$vehicle_name_arr,$speed_arr,$vehicle_number_arr,$io_str_last,$d_travel_str)
	{
		//print_r($latarr);
		//print_r($lngarr);
		//echo "map_id=".$map_id."<br>";
		
		if($map_id == null) 
		{
			return null;
		}
		$geolocation = false;
		
			//echo "geolocation=".$geolocation."<br>";	
		$marker = "<script>";
		if(is_array($latarr) && is_array($lngarr))
		{
			$latJs=json_encode($latarr);
			$lngJS=json_encode($lngarr);
			$dateTimeJs=json_encode($datetimearr);
			$vSerialJs=json_encode($vehicle_serial_arr);
			$vNameJs=json_encode($vehicle_name_arr);
			$vNumberJs=json_encode($vehicle_number_arr);
			$speedJs=json_encode($speed_arr);
			$ioStr=json_encode($io_str_last);			
			$dTravelJs=json_encode($d_travel_str);                      
			//echo "in if<br>";
			$marker .= "setMultipleMarker({$map_id},{$latJs},{$lngJS},{$dateTimeJs},{$vSerialJs},{$vNameJs},{$vNumberJs},{$speedJs},{$ioStr},{$dTravelJs}".")";
		}		
		$marker .= "</script>";
		return $marker;
	}
	//Track Play

	function addMarker($map_id, $id, $position, $options = array())
	{
		if($id == null || $map_id == null || $position == null) return null;
		$geolocation = false;
		// Check if position is array and has the two necessary elements
		// or if is not array that the string is not empty
		if(is_array($position))
		{
			if(!isset($position["latitude"]) || !isset($position["longitude"]))
			{
				return null;
			}
			$latitude = $position["latitude"];
			$longitude = $position["longitude"];
			//$infoWindow = $position["infoWindow"];
			//$windowText = $options["windowText"];
                        $dateTime = $options["dateTime"];
                        $vehicleSerial = $options["vehicleSerial"];
                        $vehicleName = $options["vehicleName"];
                        $vehcleNumber = $options["vehcleNumber"];
                        $speed = $options["speed"];
                        $ioStr = $options["ioStr"];                       
			$markerTitle = $options["markerTitle"];
			$markerIcon = $options["markerIcon"];
                       // echo "dateTime=".$dateTime." vehcileSerial=".$vehicleSerial." latitude=".$latitude." longitude=".$longitude."<br>";
                         //echo "vehcleNumber=".$vehcleNumber." vehicleName=".$vehicleName."<br>";
                          //echo "ioStr=".$ioStr." speed=".$speed."<br>";
			//$markerShadow = $position["markerShadow"];
		}
		else
		{
			$geolocation = true;
		}

		extract($options);
		/*if(!isset($infoWindow))  
		{
			$infoWindow = $this->defaultInfoWindowM;
		}
		if(!isset($windowText))
		{
			$windowText = $this->defaultWindowTextM;
		}*/
		if(!isset($markerTitle))  
		{
			$markerTitle = $this->defaultmarkerTitleM;
		}
		if(!isset($markerIcon))  
		{		
			$markerIcon = $this->defaultmarkerIconM;
		}
		/*if(!isset($markerShadow)) 
		{
			$markerShadow = $this->defaultmarkerShadowM;
		}*/
		
		$marker = "<script>";
		if(!$geolocation)
		{    
                        echo "in if<br>";
			if (!preg_match("/[-+]?\b[0-9]*\.?[0-9]+\b/", $latitude) || !preg_match("/[-+]?\b[0-9]*\.?[0-9]+\b/", $longitude)) return null;
			if (!preg_match('/^https?:\/\//', $markerIcon)) $markerIcon = $this->webroot . IMAGES_URL . '/' . $markerIcon;
			$marker .= "setMarker({$map_id},'{$id}',new google.maps.LatLng($latitude, $longitude),'{$markerTitle}','{$markerIcon}','{$dateTime}','{$vehicleName}','{$vehicleSerial}','{$vehcleNumber}','{$speed}','{$ioStr}')";
		                                                                                    
                }
		else
		{
                          echo "in else<br>";
			if( empty($position) ) return null;
			if (!preg_match('/^https?:\/\//', $markerIcon)) $markerIcon = $this->webroot . IMAGES_URL . '/' . $markerIcon;
			$marker .= "geocodeAddress('{$position}', 'setMarker', {$map_id},'{$id}','{$markerTitle}','{$markerIcon}','{$markerShadow}','{$windowText}', ".($infoWindow? 'true' : 'false').")";
		}

		$marker .= "</script>";
		return $marker;
	}

	/*
	* Method getDirections
	*
	* This method gets the direction between two addresses or markers
	*
	*
	* @author Marc Fernandez <marc.fernandezg (at) gmail (dot) com>
	* @param $map_id - Id that you used to create the map (default 'map_canvas')
	* @param $id - Unique identifier for the directions
	* @param mixed $position - array with strings with the from and to addresses or from and to markers
	* @param array $options - options array
	* @return string - will return all the javascript script to add the directions to the map
	*
	*/
	function getDirections($map_id, $id, $position, $options = array()){
		if($id == null || $map_id == null || $position == null) return null;

		if( !isset($position["from"]) || !isset($position["to"]) )
			return null;

		if( $options != null )
		{
			extract($options);
		}
		if( !isset($travelMode) )			$travelMode = $this->defaultTravelMode;
		if( !isset($directionsDiv) )	$directionsDiv = $this->defaultDirectionsDiv;

		$directions = "
			<script>
			  var {$id}Service = new google.maps.DirectionsService();
			  var {$id}Display;
			  {$id}Display = new google.maps.DirectionsRenderer();
			  {$id}Display.setMap({$map_id});
			";
			if( $directionsDiv != null )
				$directions .= "{$id}Display.setPanel(document.getElementById('{$directionsDiv}'));";

			$directions .= "
			  var request = {
			    origin:'{$position["from"]}',
			    destination:'{$position["to"]}',
			    travelMode: google.maps.TravelMode.{$travelMode}
			  };
			  {$id}Service.route(request, function(result, status) {
			    if (status == google.maps.DirectionsStatus.OK) {
			      {$id}Display.setDirections(result);
			    }
			  });
			</script>
		";
		return $directions;
	}

	/*
	* Method addPolyline
	*
	* This method adds a line between 2 points
	*
	*
	* @author Marc Fernandez <marc.fernandezg (at) gmail (dot) com>
	* @param $map_id - Id that you used to create the map (default 'map_canvas')
	* @param $id - Unique identifier for the directions
	* @param mixed $position - array with start and end latitudes and longitudes
	* @param array $options - options array
	* @return string - will return all the javascript script to add the directions to the map
	*
	*/
	function addPolyline($map_id, $id, $position, $options = array()){
		if($id == null || $map_id == null || $position == null) return null;

		if( !isset($position["start"]) || !isset($position["end"]) )
			return null;

		if( $options != null )
		{
			extract($options);
		}
		if( !isset($strokeColor) )		$strokeColor = $this->defaultStrokeColor;
		if( !isset($strokeOpacity) )	$strokeOpacity = $this->defaultStrokeOpacity;
		if( !isset($strokeWeight) )		$strokeWeight = $this->defaultStrokeWeight;

		// Check if position is array and has the two necessary elements
		if( is_array($position["start"]) ){
			if( !isset($position["start"]["latitude"]) || !isset($position["start"]["longitude"]) )
				return null;
			$latitude_start = $position["start"]["latitude"];
			$longitude_start = $position["start"]["longitude"];
		}

		if( is_array($position["end"]) ){
			if( !isset($position["end"]["latitude"]) || !isset($position["end"]["longitude"]) )
				return null;
			$latitude_end = $position["end"]["latitude"];
			$longitude_end = $position["end"]["longitude"];
		}

		if( $options != null )
		{
			extract($options);
		}
		if( !isset($travelMode) )			$travelMode = $this->defaultTravelMode;
		if( !isset($directionsDiv) )	$directionsDiv = $this->defaultDirectionsDiv;
$polyline="";
		$polyline =$polyline."<script>";


		if (!preg_match("/[-+]?\b[0-9]*\.?[0-9]+\b/", $latitude_start) || !preg_match("/[-+]?\b[0-9]*\.?[0-9]+\b/", $longitude_start)) return null;
		$polyline = $polyline."var start = new google.maps.LatLng({$latitude_start}, {$longitude_start}); ";

		if (!preg_match("/[-+]?\b[0-9]*\.?[0-9]+\b/", $latitude_end) || !preg_match("/[-+]?\b[0-9]*\.?[0-9]+\b/", $longitude_end)) return null;

		$polyline =$polyline."var end = new google.maps.LatLng({$latitude_end}, {$longitude_end}); ";

		$polyline = $polyline."
				var poly = [
			    start,
			    end
			  ];
			  var {$id}Polyline = new google.maps.Polyline({
			    path: poly,
			    strokeColor: '{$strokeColor}',
			    strokeOpacity: {$strokeOpacity},
			    strokeWeight: {$strokeWeight}
			  });
			  {$id}Polyline.setMap({$map_id});

			</script>
			";
		return $polyline;
	}
        
        function test(){
            echo"<script>
                    call_tmp_function();
                    function call_tmp_function(){
//alert('hi');
        var myLatLng=''; var lat = '';  var lng = '';var contentString='';
        
        var markers = [];

        google.maps.event.addListener(map_canvas , 'click', function(event) {
                    deleteOverlays();
                    //myLatLng = event.latLng;
                    
                    lat = event.latLng.lat();
                    lng = event.latLng.lng();                      //alert(lat);
                    myLatLng = new google.maps.LatLng(lat,lng);
                    
                    contentString  = '<div style=height:10px></div><table>'
                                     +'<tr><td style=font-size:11px>'+lat+','+lng+'</td></tr>'               
                                     +'</table><div style=height:10px></div>';
        
         var infowindow = new google.maps.InfoWindow({
                     content: contentString
                 });
                    var marker = new google.maps.Marker({
                     position: myLatLng,
                     map: map_canvas,
                     title:'Uluru (Ayers Rock)'
                 });
                  markers.push(marker);

                     infowindow.open(map_canvas,marker);
                    

                      // alert(lat);        
                    document.getElementById('lat').value=lat;
                    document.getElementById('lng').value=lng;
                    

                            });
                // Deletes all markers in the array by removing references to them.
                function deleteOverlays() {                  
                  for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(null);
                  }
                
                }
              
   }
                 </script>";      
     
        }
        
        function picklatlng(){
echo"<script>
var markerlm2;
call_tmp_function();
function call_tmp_function(){
//alert('hi');
//alert('tes')
var myLatLng='';
var lat = '';
var lng = '';
var contentString='';
var markers = [];
var latLngTmp=document.getElementById('geo_cord').value;
if(latLngTmp=='')
{
tmpFunc(1);

}
else
{
var latLngTmp1=latLngTmp.split(',');
var latd = latLngTmp1[0];
var lngd = latLngTmp1[1];
//alert('lat='+latd+'lng='+lngd);
var myLatLngd = new google.maps.LatLng(latd,lngd);

var contentStringd = '<div style=height:10px></div><table>'
+'<tr><td style=font-size:11px>'+latd+','+lngd+'</td></tr>'
+'<tr><td style=font-size:11px;text-align:center><input type=button value=ok onclick=setinputfield('+latd+','+lngd+')></td></tr>'
+'</table><div style=height:10px></div>';

var infowindow = new google.maps.InfoWindow({
content: contentStringd
});
markerlm2 = new google.maps.Marker({
position: myLatLngd,
map: map_canvas,
title:'Uluru (Ayers Rock)'
});
markers.push(markerlm2);
infowindow.open(map_canvas,markerlm2);
tmpFunc(2);
}
// Deletes all markers in the array by removing references to them.

}
function setinputfield(lat,lng)
{
/// alert('test');
document.getElementById('geo_cord').value=lat+','+lng;
popup(0);
}
function tmpFunc(val)
{

google.maps.event.addListener(map_canvas , 'click', function(event) {
if(val==2)
{
markerlm2.setMap(null);
}
deleteOverlays();
//myLatLng = event.latLng;

lat = event.latLng.lat();
lng = event.latLng.lng();
//alert(lat);
myLatLng = new google.maps.LatLng(lat,lng);

contentString = '<div style=height:10px></div><table>'
+'<tr><td style=font-size:11px>'+lat+','+lng+'</td></tr>'
+'<tr><td style=font-size:11px;text-align:center><input type=button value=ok onclick=setinputfield('+lat+','+lng+')></td></tr>'
+'</table><div style=height:10px></div>';

var infowindow = new google.maps.InfoWindow({
content: contentString
});
var marker = new google.maps.Marker({
position: myLatLng,
map: map_canvas,
title:'Uluru (Ayers Rock)'
});
markers.push(marker);
infowindow.open(map_canvas,marker);
// alert(lat);

function deleteOverlays() {
for (var i = 0; i < markers.length; i++) {
markers[i].setMap(null);
}


}

});

}
</script>";

}

} 
?>

