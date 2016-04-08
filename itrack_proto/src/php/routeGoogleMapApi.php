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

	
	public function map($options = null)
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
		//if( !isset($marker) )		$marker = $this->defaultMarker;
		if( !isset($markerIcon) ) 	$markerIcon = $this->defaultMarkerIcon;
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
   width: 60px;     
 //  border: 2px solid black;
   white-space: nowrap;
 }
  .live_td_css2 {
   color: blue;
   //background-color: white;
   font-family: 'Lucida Grande', 'Arial', sans-serif;
   font-size: 11px;
   text-align: left;
   width: 60px;     
  // border: 2px solid black;
   white-space: nowrap;
 }
 </style>";
		
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
				/*function geocodeAddress(address, action, map,markerId, markerTitle, markerIcon, markerShadow, windowText, showInfoWindow) {
				    geocoder.geocode( { 'address': address}, function(results, status) {
				      if (status == google.maps.GeocoderStatus.OK) {
				      	if(action =='setCenter'){
				      		setCenterMap(results[0].geometry.location);
				      	}
				      	if(action =='setMarker'){
				      		//return results[0].geometry.location;
				      		setMarker(map,markerId,results[0].geometry.location,markerTitle, markerIcon, markerShadow,windowText, showInfoWindow);
				      	}
				      	if(action =='addPolyline'){
				      		return results[0].geometry.location;
				      	}
				      } else {
				        alert('Geocode was not successful for the following reason: ' + status);
				        return null;
				      }
				    });
				}*/";

		$map .= "
			var initialLocation;
			//alert('test');
			var tmpval11=".$cPlant.";
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
			var mining_test=document.getElementById('category').value;
			//alert('mining_test='+mining_test);
			if(mining_test=='5' || (document.getElementById('mining_user').value==5))
			{
				//alert('in if');
				show_milestones(map_canvas);
			}
			else if(document.thisform.geofence_feature.checked==true)/////// for other users
			{
				show_geofence(map_canvas);				
			}
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
				function infoCallback(lat,lng,dateTimeArr,vSerial,vName,vNumber,speed,ioStr,marker,maxSpeed,maxHaltTime) 
				{	
					return function() 
					{
						 var contentString='';
							if (infowindow) infowindow.close();
						infowindow = new google.maps.InfoWindow();
						var latlng = new google.maps.LatLng(lat, lng);
						//alert('latlng='+latlng);
						
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
									var google_add_flag=0;
									for (var j=0; j<results.length; j++) 
									{ 
										// alert('type='+results[j].types[0]);
										if ((results[j].types[0] == 'street_address') || (results[j].types[0] == 'route')) 
										{
											google_lat = results[j].geometry.location.lat();
											google_lng = results[j].geometry.location.lng();								
											distance = calculate_distance(lat, google_lat, lng, google_lng);											
											tmp_address=distance+' Km From '+results[j].formatted_address;	
											google_add_flag=1;
										}
									}
									//alert('tmp_address='+tmp_address);
									if(google_add_flag==0) // if address not come form google map then this block get address from xml
									{	
										//alert('inif');
										tmp_address=get_xml_location(latlng);
										//alert('xml_loacation_2='+tmp_address);	
									}
									var str=lat+','+lng;
									var strURL='src/php/select_landmark_marker.php?content='+str;			

									var req = getXMLHTTP();
									req.open('GET', strURL, false); //third parameter is set to false here
									req.send(null);
									var landmark = req.responseText;
									//alert('landmark='+landmark);
									if(landmark!='')
									{
										tmp_address=landmark;
									}
									contentString='<table>'+
										'<tr>'+
										'<td class=\"live_td_css1\">Vehicle Name</td>'+
										'<td>:</td>'+
										'<td class=\"live_td_css2\">'+vName+'</td>'+
									   '</tr>'+
									   '<tr>'+
										'<td class=\"live_td_css1\">Imei</td>'+
										'<td>:</td>'+
										'<td class=\"live_td_css2\">'+vSerial+'</td>'+
									   '</tr>'+
										'<tr>'+
										'<td class=\"live_td_css1\">Driver Name/Mob </td>'+
										'<td>:</td>'+
										'<td class=\"live_td_css2\">'+vNumber+'</td>'+
									   '</tr>'+
									   '<tr>'+
									   '<tr>'+
										'<td class=\"live_td_css1\">Speed</td>'+
										'<td>:</td>'+
										'<td class=\"live_td_css2\">'+speed+'</td>'+
									   '</tr>'+
									   '<tr>'+
										'<tr>'+                                                    
										'<td class=\"live_td_css1\">Date Time</td>'+
										'<td>:</td>'+
										'<td class=\"live_td_css2\">'+dateTimeArr+'</td>'+
										'</tr>'+
										'<tr>'+
										'<td class=\"live_td_css1\">Address</td>'+
										'<td>:</td>'+
										'<td class=\"live_td_css2\">'+tmp_address+'</td>'+ioStr+ 
										'<tr>'+
										'<td class=\"live_td_css1\">Day Max Speed</td>'+
										'<td>:</td>'+
										'<td class=\"live_td_css2\">'+maxSpeed+'</td>'+ 
										'<tr>'+
										'<td class=\"live_td_css1\">Last Halt Time</td>'+
										'<td>:</td>'+
										'<td class=\"live_td_css2\">'+maxHaltTime+'</td>'+
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
				}
				
				function infoCallbackTrack(lat,lng,dateTimeArr,vSerial,vName,vNumber,speed,marker,ioStrLocal,dTravel,feature_id_map) 
				{					
					return function() 
					{
						var customer_plant_str='';
						var customer_plant_str1='';
						var feature_id_map = document.getElementById('station_flag_map').value;
						if(feature_id_map==1)
						{						
							var client_type_combo=document.getElementById('station_chk').value;
							//if(document.getElementById('station_search_text').value=='')
							{			
								/*if(client_type_combo=='0')
								{*/
									var customerDataLength=lat_customer.length;
									var customer_min_distance;	

									var customer_distance_arr=new Array();
									var customer_print_str=new Array();
										
									if(customerDataLength>0)
									{
										//var customer_distance_arr=new Array();
										//var customer_print_str=new Array();
										for(var i=0;i<customerDataLength;i++)
										{					
											var customer_distance = calculate_distance(lat, lat_customer[i], lng, lng_customer[i]);
											customer_distance_arr[i]=customer_distance;
											customer_print_str[customer_distance]=station_customer[i]+':'+customer_station_no[i];
										}
										customer_distance_arr.sort();
										customer_min_distance=customer_distance_arr[0];
										var customer_print_str1=customer_print_str[customer_min_distance];
										///customer_plant_str='<tr><td>Place From Customer</td><td>:</td><td>'+customer_distance_arr[0]+'From '+customer_print_str[customer_distance_arr[0]]+'</td></tr>';
									}				
								/*}
								else if(client_type_combo=='1')
								{*/
									var planDataLength=lat_plant.length;
									var plant_min_distance;		
									if(planDataLength>0)
									{
										var plant_distance_arr=new Array();
										var plant_print_str=new Array();
										for(var i=0;i<planDataLength;i++)
										{					
											var customer_distance = calculate_distance(lat, lat_plant[i], lng, lng_plant[i]);
											plant_distance_arr[i]=customer_distance;
											plant_print_str[customer_distance]=station_plant[i]+':'+customer_plant[i];
										}
										plant_distance_arr.sort();
										plant_min_distance=plant_distance_arr[0];
										var plant_print_str1=customer_print_str[plant_min_distance];
										//customer_plant_str='<tr><td>Place From Plant</td><td>:</td><td>'+plant_distance_arr[0]+'From '+plant_print_str[plant_distance_arr[0]]+'</td></tr>';
									}
									//alert('plant_min_distance='+plant_min_distance+'customer_min_distance='+customer_min_distance);
									if(plant_min_distance==undefined && customer_min_distance!=undefined)
									{
										//alert('in if');
										customer_plant_str='<tr><td class=\"live_td_css1\">Place From Customer</td><td>:</td><td class=\"live_td_css2\">'+customer_min_distance+ ' From '+customer_print_str1+'</td></tr>';
									}
									else if(customer_min_distance==undefined && plant_min_distance!=undefined)
									{
										//alert('in else if 1');
										customer_plant_str='<tr><td class=\"live_td_css1\">Place From Plant</td><td>:</td><td class=\"live_td_css2\">'+plant_min_distance+' From '+plant_print_str1+'</td></tr>';
									}
									else if(plant_min_distance==undefined && customer_min_distance==undefined)
									{
										//alert('in else if 2');
										customer_plant_str='';
									}
									else
									{
										//alert('else');
										if(plant_min_distance<customer_min_distance)
										{				
											customer_plant_str='<tr><td class=\"live_td_css1\">Place From Plant</td><td>:</td><td class=\"live_td_css2\">'+plant_min_distance+' From '+plant_print_str1+'</td></tr>';
										}
										else if(customer_min_distance<plant_min_distance)
										{					
											customer_plant_str='<tr><td class=\"live_td_css1\">Place From Customer</td><td>:</td><td class=\"live_td_css2\">'+customer_min_distance+' From '+customer_print_str1+'</td></tr>';
										}
									}
								//}
							}
							/*if(document.getElementById('station_search_text').value!='')
							{	
								var search_text = document.getElementById('station_search_text').value;
								if(client_type_combo=='select')
								{
									alert('Please select customer');
									return false;
								}
								else
								{
									if(client_type_combo=='1')
									{	
										if(station_counter_plant>0)
										{				
											for(var i=0;i<station_counter_plant;i++)
											{
												search_text = trim(search_text);
												station_name_plant[i] = trim(station_name_plant[i]);
												station_customer_plant[i] = trim(station_customer_plant[i]); 
												if( (search_text == station_name_plant[i]) || (search_text == station_customer_plant[i]) )
												{
													//alert('found');
													var customer_distance = calculate_distance(lat, station_lat_plant[i], lng, station_lng_plant[i]);							
													customer_plant_str1='<tr><td class=\"live_td_css1\"> Place From Plant</td><td>:</td><td class=\"live_td_css2\">'+customer_distance+' From '+station_name_plant[i]+':'+station_customer_plant[i]+'</td></tr>';
													break;					
												}    
											} 				
										}
									}
								}
							}*/
						}
						var contentString='';
						var contenttmpstr='';
						if (infowindow) infowindow.close();
						infowindow = new google.maps.InfoWindow();
						var latlng = new google.maps.LatLng(lat, lng);
						//alert('latlng='+latlng);
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
									/*for(var j=0; j<results.length; j++) 
									{ 
										// alert('type='+results[j].types[0]);
										if ((results[j].types[0] == 'street_address') || (results[j].types[0] == 'route')) 
										{
											//test_js();
											
											google_lat = results[j].geometry.location.lat();
											google_lng = results[j].geometry.location.lng();								
											distance = calculate_distance(lat, google_lat, lng, google_lng);
											tmp_address=distance+' Km From '+results[j].formatted_address;																					
										}
									}*/
									var google_add_flag=0;
									for (var j=0; j<results.length; j++) 
									{ 
										// alert('type='+results[j].types[0]);
										if ((results[j].types[0] == 'street_address') || (results[j].types[0] == 'route')) 
										{
											google_lat = results[j].geometry.location.lat();
											google_lng = results[j].geometry.location.lng();								
											distance = calculate_distance(lat, google_lat, lng, google_lng);											
											tmp_address=distance+' Km From '+results[j].formatted_address;	
											google_add_flag=1;
										}
									}
									//alert('tmp_address='+tmp_address);
									if(google_add_flag==0) // if address not come form google map then this block get address from xml
									{	
										//alert('inif');
										tmp_address=get_xml_location(latlng);
										//alert('xml_loacation_2='+tmp_address);	
									}
									var str = lat+','+lng;
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
									'<td class=\"live_td_css1\">Vehicle Name</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+vName+'</td>'+
								   '</tr>'+
								   '<tr>'+
									'<td class=\"live_td_css1\">Imei</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+vSerial+'</td>'+
								   '</tr>'+
									'<tr>'+
									'<td class=\"live_td_css1\">Driver Name/Mob </td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+vNumber+'</td>'+
								   '</tr>'+
								   '<tr>'+
								   '<tr>'+
									'<td class=\"live_td_css1\">Speed</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+speed+'</td>'+
								   '</tr>'+
								   '<tr>'+
									'<tr>'+                                                    
									'<td class=\"live_td_css1\">Date Time</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+dateTimeArr+'</td>'+
									'</tr>'+
									'<tr>'+
									'<tr>'+                                                    
									'<td class=\"live_td_css1\">Distance Travel</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+dTravel+'</td>'+
									'</tr>';
									contentString=contentString+ioStrLocal;
									contentString=contentString+'<tr>'+								
									'<td class=\"live_td_css1\">Address</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+tmp_address+'</td>'+	
									'</tr>'+
									'<tr>'+											
									'<td colspan=3>'+
									'</tr>'+customer_plant_str+customer_plant_str1+											
									'</table>'+
									'<br><form action=#>';
									if(feature_id_map==1)
									{
										contenttmpstr='<em>Add </em><select id=landmark_type onchange=display_landmark_type(this.value)><option value=landmark>Landmark</option><option value=station>Station</option></select></em>';
									}
									else
									{
										contenttmpstr='Add Landmark';
									}
									contentString=contentString+'<table><tr><td class=\"live_td_css1\">'+contenttmpstr+'</td>'+											
								   '<td>'+
								   '<span id =landmark_area>'+
									'<table>'+
									'<tr>'+
									'<td class=\"live_td_css1\">LandmarkName&nbsp;:&nbsp;<input type=text name=landmark_name id=landmark_name  size=10><input type=hidden name=landmark_point id=landmark_point value=\''+latlng+'\'></td>'+		 					
									'<td><input type=button value=SAVE id=save_lnmrk onclick=map_add_landmark(this.form)></td><td><span id=wait_lnmrk style=display:none><img src=images/map_add_landmark_loading.gif align=absmiddle>&nbsp;wait ...</span></td>'+
									'</tr></table>'+
									'</span>'+
									'</td>'+
									'<td>'+
									'<span id =station_area  style=display:none>'+			 
								   '<table><tr><td class=\"live_td_css1\">StationName &nbsp;:&nbsp;<input type=text name=station_name id=station_name  size=10></td><td class=\"live_td_css1\">CustomerNo&nbsp;:&nbsp;<input type=text name=customer_no id=customer_no size=10>Radius&nbsp;:&nbsp;<input type=text size=5 name=radius id=radius value=0.2>KM<input type=hidden name=landmark_point id=landmark_point value=\''+latlng+'\'></td>'+			 					
										 '<td><input type=button value=SAVE id=save_lnmrk onclick=map_add_station(this.form)></td><td><span id=wait_lnmrk style=display:none><img src=images/map_add_landmark_loading.gif align=absmiddle>&nbsp;wait ...</span></td>'+
										'</tr></table>'+
								   '</span>'+
								'</td>'+
								   '</tr></table></form>'+											
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
				
				
				function infoCallbackPersonTrack(lat,lng,dateTimeArr,vSerial,vName,vNumber,speed,marker,dTravel) 
				{	
					return function() 
					{
						 var contentString='';
							if (infowindow) infowindow.close();
						infowindow = new google.maps.InfoWindow();
						var latlng = new google.maps.LatLng(lat, lng);
						//alert('latlng='+latlng);
						geocoder.geocode({'latLng': latlng}, function(results, status) 
						{
							//alert('in gecode');						
							//if (status == google.maps.GeocoderStatus.OK) 
							{
								//alert('in gecode 1');
								if(results) 
								{
									var google_lat = '';
									var google_lng = '';
									var distance = '';
									var tmp_address;
									/*for (var j=0; j<results.length; j++) 
									{ 
										// alert('type='+results[j].types[0]);
										if ((results[j].types[0] == 'street_address') || (results[j].types[0] == 'route')) 
										{
											google_lat = results[j].geometry.location.lat();
											google_lng = results[j].geometry.location.lng();								
											distance = calculate_distance(lat, google_lat, lng, google_lng);
											tmp_address=distance+' Km From '+results[j].formatted_address;
										}
									}*/
									var google_add_flag=0;
									for (var j=0; j<results.length; j++) 
									{ 
										// alert('type='+results[j].types[0]);
										if ((results[j].types[0] == 'street_address') || (results[j].types[0] == 'route')) 
										{
											google_lat = results[j].geometry.location.lat();
											google_lng = results[j].geometry.location.lng();								
											distance = calculate_distance(lat, google_lat, lng, google_lng);											
											tmp_address=distance+' Km From '+results[j].formatted_address;	
											google_add_flag=1;
										}
									}
									//alert('tmp_address='+tmp_address);
									if(google_add_flag==0) // if address not come form google map then this block get address from xml
									{	
										//alert('inif');
										tmp_address=get_xml_location(latlng);
										//alert('xml_loacation_2='+tmp_address);	
									}
									var str=lat+','+lng;
									var strURL='src/php/select_landmark_marker.php?content='+str;									var req = getXMLHTTP();
									req.open('GET', strURL, false); //third parameter is set to false here
									req.send(null);
									var landmark = req.responseText;
									if(landmark!='')
									{
										tmp_address=landmark;
									}									
									contentString='<table>'+
									'<tr>'+
									'<td class=\"live_td_css1\">Person Name</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+vName+'</td>'+
								   '</tr>'+
								   '<tr>'+
									'<td class=\"live_td_css1\">Imei</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+vSerial+'</td>'+
								   '</tr>'+
									'<tr>'+
									'<td class=\"live_td_css1\">Mobile Number </td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+vNumber+'</td>'+
								   '</tr>'+
								   '<tr>'+
								   '<tr>'+
									'<td class=\"live_td_css1\">Speed</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+speed+'</td>'+
								   '</tr>'+
								   '<tr>'+
									'<tr>'+                                                    
									'<td class=\"live_td_css1\">Date Time</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+dateTimeArr+'</td>'+
									'</tr>'+
									'<tr>'+
									'<tr>'+                                                    
									'<td class=\"live_td_css1\">Distance Travel</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+dTravel+'</td>'+
									'</tr>'+
									'<tr>'+
									'<td class=\"live_td_css1\">Address</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+tmp_address+'</td></tr>'+										  
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
							/*else 
							{
								alert('Geocoder failed due to: ' + status);
							}*/
							//contentString='';
						}); 						
					};
				}

				function infoCallbackPerson(lat,lng,dateTimeArr,vSerial,vName,vNumber,marker) 
				{	
					return function() 
					{
						 var contentString='';
							if (infowindow) infowindow.close();
						infowindow = new google.maps.InfoWindow();
						var latlng = new google.maps.LatLng(lat, lng);
						//alert('latlng='+latlng);
						geocoder.geocode({'latLng': latlng}, function(results, status) 
						{
							//alert('in gecode');						
							//if (status == google.maps.GeocoderStatus.OK) 
							{
								//alert('in gecode 1');
								if(results) 
								{
										//alert('in gecode 2');
									var google_lat = '';
									var google_lng = '';
									var distance = '';
									var tmp_address;
									/*for (var j=0; j<results.length; j++) 
									{ 
										//alert('type='+results[j].types[0]);
										if ((results[j].types[0] == 'street_address') || (results[j].types[0] == 'route')) 
										{
											//alert('type='+results[j].types[0]);
											google_lat = results[j].geometry.location.lat();
											google_lng = results[j].geometry.location.lng();								
											distance = calculate_distance(lat, google_lat, lng, google_lng);
											tmp_address=distance+' Km From '+results[j].formatted_address;											
										}
									}*/
									var google_add_flag=0;
									for (var j=0; j<results.length; j++) 
									{ 
										// alert('type='+results[j].types[0]);
										if ((results[j].types[0] == 'street_address') || (results[j].types[0] == 'route')) 
										{
											google_lat = results[j].geometry.location.lat();
											google_lng = results[j].geometry.location.lng();								
											distance = calculate_distance(lat, google_lat, lng, google_lng);											
											tmp_address=distance+' Km From '+results[j].formatted_address;	
											google_add_flag=1;
										}
									}
									//alert('tmp_address='+tmp_address);
									if(google_add_flag==0) // if address not come form google map then this block get address from xml
									{	
										//alert('inif');
										tmp_address=get_xml_location(latlng);
										//alert('xml_loacation_2='+tmp_address);	
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
									'<td class=\"live_td_css1\">Person Name</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+vName+'</td>'+
								   '</tr>'+
								   '<tr>'+
									'<td class=\"live_td_css1\">Imei</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+vSerial+'</td>'+
								   '</tr>'+
									'<tr>'+
									'<td class=\"live_td_css1\">Mobile Number</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+vNumber+'</td>'+
								   '</tr>'+
								   '<tr>'+										                                               
									'<td class=\"live_td_css1\">Date Time</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+dateTimeArr+'</td>'+
									'</tr>'+
									'<tr>'+
									'<td class=\"live_td_css1\">Address</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+tmp_address+'</td>'+
									'</tr>'+										    
									'</table>'+
									'<b><font color=black size=2>('+lat+','+lng+')</font></b>';
									//alert('contentSring='+contentString);
										infowindow.setContent(contentString);
										infowindow.open(map_canvas, marker);
								} 
								else 
								{
									alert('No results found');
								}
							} 
							/*else 
							{
								alert('Geocoder failed due to: ' + status);
							}*/
							//contentString='';
						}); 						
					};
				}
				function getLandMarkNew(landmarkLocal)
				{
					//alert('In landmark');
					landmark_name_list = new Array();
					landmark_point_list = new Array();
					landmark_customer_list = new Array();
					landmark_marker_list = new Array();    	
					landmark_counter = 0;
					var i;
					var landmark;	
					var markerL;
					var zoomlevel;
					var point;
					var lnmark_data=landmarkLocal.split('#');
					//alert('landmark_data_length='+lnmark_data.length);
					var lnmark_data1;
					//var icon1='images/landmark.png';
					//var icon1='images/landmark.png';
					var icon1 = {
								  url: 'images/landmark.png',
								  size: new google.maps.Size(10, 10),
								scaledSize: new google.maps.Size(10, 10),
								};
					for(i=0; i<lnmark_data.length; i++) 
					{
						lnmark_data1=lnmark_data[i].split('@');	
						landmark=lnmark_data1[0];
						//alert('landmark_name='+landmark+'lat='+lnmark_data1[2]+'lng='+lnmark_data1[3]);
						point=new google.maps.LatLng(lnmark_data1[2], lnmark_data1[3]);
						if(lnmark_data1[2].length>6 && lnmark_data1[3].length>6)
						{
							markerL = new google.maps.Marker
							({
								position: point,	 map: map_canvas, icon: icon1, title:'landmark'
							});					
								// markers.push(marker);
											
							google.maps.event.addListener
							(
								markerL, 'click', infoCallbackLandmark(landmark,lnmark_data1[2],lnmark_data1[3],markerL)
							);	
						}
						//STORE VARIABLE IN ARRAYS FOR LANDMARK SEARCH
						landmark_name_list[landmark_counter] = landmark;  //landmark name
						landmark_point_list[landmark_counter] = lnmark_data1[2]+','+lnmark_data1[3];            					
						landmark_marker_list[landmark_counter] = markerL;
						landmark_counter++;    						
					}
				} 
				function setMultipleMarker(map,latarr, lngarr,datetimearr,vSerial,vName,vNumber,speed,ioStr,dTravel)
				{
					//alert('in set markeer');
					//deleteOverlays();
					if((latarr.length ==1) && (!latarr[0]) && (!lngarr[0]))
					{
						alert('Sorry! Either -GPS or Data Not Found');
						exit;
					}
					var mouse_action = document.forms[0].mouse_action.value; // for google map mouseOver and click option
					var latlngbounds = new google.maps.LatLngBounds();	
					var str='';
					var strURL='src/php/select_landmark.php?content='+str;        
					var req = getXMLHTTP();
					req.open('GET', strURL, false); //third parameter is set to false here
					req.send(null);
					var landmark_str = req.responseText;
					//alert('landmark_str='+landmark_str);
					if(landmark_str!='')
					{
					getLandMarkNew(landmark_str);
					}
					//alert('landmark_str='+landmark_str);
					var icon;
					 var feature_id_map = document.getElementById('station_flag_map').value; 
					// alert('feature_id_map='+feature_id_map);
					for(var i=0;i<latarr.length;i++)
					{
						icon_flag=0;					
						//icon='';
						if(i==0)
						{
							icon='images/start_marker.png';
							icon_flag=1;
						}
						else if(i==(latarr.length-1))
						{
							icon='images/stop_marker.png';
							icon_flag=1;
						}
						else
						{
					
							if((speed[i]>=1 && speed[i]<=20) && (document.forms[0].m1.value==1))
							{
								icon='images/yellow_Marker1.png';
								icon_flag=1;
							}							
							else if((speed[i]>20) && (document.forms[0].m2.value==1))
							{
								icon='images/green_Marker1.png';
								icon_flag=1;
							}
							
							else if((speed[i]<1) && (document.forms[0].m3.value==1))
							{
								icon='images/red_Marker1.png';
								icon_flag=1;
							}							
							/*else
							{
								icon='images/green_Marker1.png';
							}*/
						}
						var position;
						var lat_tmp=latarr[i];
						var lng_tmp=lngarr[i];
						position=new google.maps.LatLng(latarr[i], lngarr[i]);				
						latlngbounds.extend(position);												
						var title='abc';
						var dateTime=datetimearr[i];
						var vSerialLocal=vSerial[i];
						var vNameLocal=vName[i];
						var vNumberLocal=vNumber[i];
						var speedLocal=speed[i];
						var ioStrLocal=ioStr[i];
						var dTravelLocal=dTravel[i];
						if(icon_flag==1)
						{
							var marker = new google.maps.Marker
							({
								position: position,	 map: map, icon: icon, title:title
							});	
						}
						else
						{
							  
						//alert('in else');
							var marker = new google.maps.Marker
							({
								position: position,	 map: map,	icon: {path: google.maps.SymbolPath.CIRCLE,},
								title:title
							});
						}
							 markers.push(marker);
										
						google.maps.event.addListener
						(
							marker, mouse_action, infoCallbackTrack(lat_tmp,lng_tmp,dateTime,vSerialLocal,vNameLocal,vNumberLocal,speedLocal,marker,ioStrLocal,dTravelLocal,feature_id_map)
						);						
						
						var line = new google.maps.Polyline
						({
							path: [new google.maps.LatLng(latarr[i], lngarr[i]),new google.maps.LatLng(latarr[i-1], lngarr[i-1])],
							strokeColor: '#ff0000',
							strokeOpacity: 1.0,
							strokeWeight: 1.5
						});	
						markers.push(line);						
						line.setMap(map);
												
					}
					map.setCenter(latlngbounds.getCenter());
					map.fitBounds(latlngbounds);	
					if(document.getElementById('category').value=='5')
					{
						document.getElementById('vehicle_milstone').value='vehicle_zoom';			
						show_milestones(map);		
					}
					//else if((document.thisform.geofence_feature.checked==true) && (document.getElementById('category').value!=5))
                                        else if(document.thisform.geofence_feature.checked==true)
					{
                                            //alert('in geofence');
                                            show_geofence(map_canvas);				
					}
					for(var i=1;i<latarr.length;i++)
					{
						lat1 = latarr[i-1];
						lng1 = lngarr[i-1];
                                                
                                                // alert('lat='+lat1+'lng1='+lng1);

						lat2 = latarr[i];
						lng2 = lngarr[i];
                                                
                                                //alert('lat2='+lat2+'lng2='+lng2);
                                                   //var adddd=lat1 + lat2;
                                                   //alert('adddd='+adddd);
						var yaxis = (parseFloat(lat1) + parseFloat(lat2))/2;
						var xaxis = (parseFloat(lng1) + parseFloat(lng2))/2;
						//alert('yaxis='+yaxis+'xaxis='+xaxis);
						var angle_t = Math.atan( (parseFloat(lat2)-parseFloat(lat1))/(parseFloat(lng2)-parseFloat(lng1)) );
						var angle_deg = 360 * angle_t/(2 * Math.PI);

						if((lng2-lng1)<0)
						{
							angle_deg = 180 + angle_deg;
						}
						else if((lat2-lat1)<0)
						{
							angle_deg = 360 + angle_deg;
						}
						angle_deg = Math.round(angle_deg,0);
						//alert('angle_degree='+angle_deg);
						var image = 
						{
							url: 'images/arrow_images/'+angle_deg+'.png',
							size: new google.maps.Size(21, 21),
							scaledSize: new google.maps.Size(21, 21),							
							anchor: new google.maps.Point(10, 10)
						};
                                               // alert('image='+image);
						position=new google.maps.LatLng(yaxis, xaxis);
                                                 //alert('position='+position);
						var marker1 = new google.maps.Marker
						({
							position: position,
							map: map,
							icon: image
						});	
						markers.push(marker1);						
					}
					/*function deleteOverlays() 
					{
						for (var i = 0; i < markers.length; i++) 
						{
							markers[i].setMap(null);
						}
					}*/
				}
				
				function setIconType(vType)
				{
					var icon;
					if(vType=='car')
					{
						icon='images/light_v1.png';					
					}
					else if(vType=='truck')
					{
						icon='images/heavy_v2.png';					
					}
					else if(vType=='bus')
					{
						icon='images/bus.png';					
					}
					else if(vType=='motorbike')
					{
						icon='images/motorbike.png';					
					}
					return icon;
				}
				";
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

