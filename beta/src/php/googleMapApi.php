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
  // width: 13%;  
   white-space: nowrap;
 }
  .live_td_css2 {
   color: blue;
   //background-color: white;
   font-family: 'Lucida Grande', 'Arial', sans-serif;
   font-size: 11px;
   text-align: left;
  // width: 55%;     
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
			var jsChillingPlant=".$cPlant.";
			//alert('js='+jsChillingPlant);
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
									contentString='<table width=\"80%\" border=\"0\">'+
										'<tr>'+
										'<td class=\"live_td_css1\">Vehicle Name</td>'+
										'<td width=\"1%\">:</td>'+
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
										'<!--<tr>'+
										'<td class=\"live_td_css1\">Day Max Speed</td>'+
										'<td>:</td>'+
										'<td class=\"live_td_css2\">'+maxSpeed+'</td>'+ 
										'<tr>-->'+
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
                                
                                function arrayHasOwnIndex(array, prop) {
    return array.hasOwnProperty(prop) && /^0$|^[1-9]\d*$/.test(prop) && prop <= 4294967294; // 2^32 - 2
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
                                                //alert('feature_id_map='+feature_id_map);
						if(feature_id_map==1)
						{						
							var client_type_combo=document.getElementById('station_chk').value;
							//if(document.getElementById('station_search_text').value=='')
							{			
								/*if(client_type_combo=='0')
								{*/
									var customer_min_distance;
									var customer_distance_arr=new Array();
									var customer_print_str=new Array();										
									
                                                                        for (key in uniqueCustomerParseJson) 
                                                                        {
                                                                            if (arrayHasOwnIndex(uniqueCustomerParseJson, key)) 
                                                                            {
                                                                                var customerArrDetail=uniqueCustomerParseJson[key].split('^');
                                                                                var customer_distance = calculate_distance(lat, customerArrDetail[0], lng, customerArrDetail[1]);
                                                                                customer_distance_arr[i]=customer_distance;
                                                                                customer_print_str[customer_distance]=key;
                                                                                //alert('len='+uniqueCustomerParseJson[key]);                                                                            
                                                                            }
                                                                        }
                                                                        //var customer_distance_arr=new Array();
                                                                        //var customer_print_str=new Array();
                                                                        customer_distance_arr.sort();
                                                                        customer_min_distance=customer_distance_arr[0];
                                                                        var customer_print_str1=customer_print_str[customer_min_distance];
                                                                        ///customer_plant_str='<tr><td>Near From Customer</td><td>:</td><td>'+customer_distance_arr[0]+'From '+customer_print_str[customer_distance_arr[0]]+'</td></tr>';
													
								/*}
								else if(client_type_combo=='1')
								{*/
									
									var plant_min_distance;		
									
                                                                        var plant_distance_arr=new Array();
                                                                        var plant_print_str=new Array();
                                                                      
                                                                        for (key in uniqueCustomerParseJson) 
                                                                        {
                                                                            if (arrayHasOwnIndex(uniquePlantParseJson, key)) 
                                                                            {
                                                                                var plantArrDetail=uniquePlantParseJson[key].split('^');
                                                                                var customer_distance = calculate_distance(lat, plantArrDetail[0], lng, plantArrDetail[1]);
                                                                                customer_distance_arr[i]=customer_distance;
                                                                                customer_print_str[customer_distance]=key;
                                                                                //alert('len='+uniquePlantParseJson[key]);                                                                            
                                                                            }
                                                                        }
                                                                        plant_distance_arr.sort();
                                                                        plant_min_distance=plant_distance_arr[0];
                                                                        var plant_print_str1=customer_print_str[plant_min_distance];
                                                                        //customer_plant_str='<tr><td>Place From Plant</td><td>:</td><td>'+plant_distance_arr[0]+'From '+plant_print_str[plant_distance_arr[0]]+'</td></tr>';

									//alert('plant_min_distance='+plant_min_distance+'customer_min_distance='+customer_min_distance);
									if(plant_min_distance==undefined && customer_min_distance!=undefined)
									{
										//alert('in if');
										customer_plant_str='<tr><td class=\"live_td_css1\">Near From Customer</td><td>:</td><td class=\"live_td_css2\">'+customer_min_distance+ ' From '+customer_print_str1+'</td></tr>';
									}
									else if(customer_min_distance==undefined && plant_min_distance!=undefined)
									{
										//alert('in else if 1');
										customer_plant_str='<tr><td class=\"live_td_css1\">Near From Plant</td><td>:</td><td class=\"live_td_css2\">'+plant_min_distance+' From '+plant_print_str1+'</td></tr>';
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
											customer_plant_str='<tr><td class=\"live_td_css1\">Near From Plant</td><td>:</td><td class=\"live_td_css2\">'+plant_min_distance+' From '+plant_print_str1+'</td></tr>';
										}
										else if(customer_min_distance<plant_min_distance)
										{					
											customer_plant_str='<tr><td class=\"live_td_css1\">Near From Customer</td><td>:</td><td class=\"live_td_css2\">'+customer_min_distance+' From '+customer_print_str1+'</td></tr>';
										}
									}
								//}
							}
							if(document.getElementById('station_search_text').value!='')
							{	
								var search_text = document.getElementById('station_search_text').value;
								if(client_type_combo=='select')
								{
									alert('Please select customer');
									return false;
								}
								else
								{
                                                                    if(client_type_combo=='0')
                                                                    {
                                                                        if(uniqueCustomerParseJson[search_text]!=undefined)
                                                                        {
                                                                            var customerArrDetail=uniqueCustomerParseJson[search_text].split('^');
                                                                            var customer_distance = calculate_distance(lat, customerArrDetail[0], lng, customerArrDetail[1]);	
                                                                            customer_plant_str1='<tr><td class=\"live_td_css1\">Place From Customer</td><td>:</td><td class=\"live_td_css2\">'+customer_distance+' From '+customerArrDetail[2]+':'+search_text+'</td></tr>';
                                                                        }
                                                                    }
                                                                    if(client_type_combo=='1')
                                                                    {	
                                                                        if(uniquePlantParseJson[search_text]!=undefined)
                                                                        {
                                                                            var plantArrDetail=uniquePlantParseJson[search_text].split('^');
                                                                            var customer_distance = calculate_distance(lat, plantArrDetail[0], lng, plantArrDetail[1]);	
                                                                            customer_plant_str1='<tr><td class=\"live_td_css1\"> Place From Plant</td><td>:</td><td class=\"live_td_css2\">'+customer_distance+' From '+plantArrDetail[2]+':'+search_text+'</td></tr>';
                                                                        }	
                                                                    }
								}
							}
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
									'<td width=\"1%\">:</td>'+
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
										if(jsChillingPlant==1)
										{
											contenttmpstr='<em>Add </em><select id=landmark_type onchange=display_landmark_type(this.value)><option value=landmark>Landmark</option><option value=0>Customer</option><option value=1>Plant</option><option value=2>Chilling Plant</option></select></em>';	
										}
										else
										{
											contenttmpstr='<em>Add </em><select id=landmark_type onchange=display_landmark_type(this.value)><option value=landmark>Landmark</option><option value=0>Customer</option><option value=1>Plant</option></select></em>';
										}
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
								scaledSize: new google.maps.Size(10, 10)
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
					deleteOverlays();
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
								position: position,	 map: map, icon: icon
							});	
						}
						else
						{
							  
						//alert('in else');
							var marker = new google.maps.Marker
							({
								position: position,	 map: map,	icon: {path: google.maps.SymbolPath.CIRCLE},
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
					else if((document.thisform.geofence_feature.checked==true) && (document.getElementById('category').value!=5))
					{
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
					function deleteOverlays() 
					{
						for (var i = 0; i < markers.length; i++) 
						{
							markers[i].setMap(null);
						}
					}
				}
				
				function setMultipleMarker_play(map,latarr, lngarr,datetimearr,vSerial,vName,vNumber,speed,ioStr,dTravel,mSecond)
				{ 
					deleteOverlays();
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
					
					if(landmark_str!='')
					{
					getLandMarkNew(landmark_str);
					}
					
					var icon;
					var feature_id_map = document.getElementById('station_flag_map').value;				
					
					i=0;
					setTimeout(function() {
							playgoogle();
						 }, i );
					function playgoogle()
					{
						var play_stop=document.getElementById('play_stop').value;
						var bounds =new google.maps.LatLngBounds();
						if(i==latarr.length-1 || play_stop=='Track Play'){
								map.setCenter(latlngbounds.getCenter());
								map.fitBounds(latlngbounds);
								
								if(document.getElementById('category').value=='5')
								{
									document.getElementById('vehicle_milstone').value='vehicle_zoom';			
									show_milestones(map);		
								}
								else if((document.thisform.geofence_feature.checked==true) && (document.getElementById('category').value!=5))
								{
									show_geofence(map_canvas);				
								}
								document.getElementById('play_stop').value='Track Play';
							return false;
						}
						//alert('Firts');
						 icon_flag=0;
				
							if(i==0)
							{
								icon='images/start_marker.png';
								icon_flag=1;
								//alert('in i='+i);
							}
							else if(i==(latarr.length-1))
							{
								icon='images/stop_marker.png';
								icon_flag=1;
								//alert('not in i='+i);
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
								
							}
							var position;
							var lat_tmp=latarr[i];
							var lng_tmp=lngarr[i];
							position=new google.maps.LatLng(latarr[i], lngarr[i]);				
							latlngbounds.extend(position);	
							bounds.extend(position);	
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
							  // alert('in ');
								var marker = new google.maps.Marker
									({
										position: position,	 map: map, icon: icon
									});
								
							}
							else
							{
								  
							//alert('in else');
								var marker = new google.maps.Marker
								({
									position: position,	 map: map,	icon: {path: google.maps.SymbolPath.CIRCLE},
									title:title
								});
							}
								 
											
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
							
							
								markers.push(marker);
								markers.push(line);						
								line.setMap(map);
								
								
								map.setCenter(bounds.getCenter());
								//map.fitBounds(bounds);
								map.setZoom(14);
								if(document.getElementById('category').value=='5')
								{
									document.getElementById('vehicle_milstone').value='vehicle_zoom';			
									show_milestones(map);		
								}
								else if((document.thisform.geofence_feature.checked==true) && (document.getElementById('category').value!=5))
								{
									show_geofence(map_canvas);				
								}
								
						    if(i>0){
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
					     
						i++;
						setTimeout(function() {
							playgoogle();
						 }, mSecond);
												
					}
					
					
					function deleteOverlays() 
					{
						for (var i = 0; i < markers.length; i++) 
						{
							markers[i].setMap(null);
						}
					}
				}
				
				function setMultipleMarkerPerson(map,latarr, lngarr,datetimearr,vSerial,vName,vNumber,speed,dTravel)
				{ 
					deleteOverlays(); 
					if((latarr.length ==1) && (!latarr[0]) && (!lngarr[0]))
					{
						alert('Sorry! Either -GPS or Data Not Found');
						exit;
					}
					var mouse_action = document.forms[0].mouse_action.value; // for google map mouseOver and click option
					var latlngbounds = new google.maps.LatLngBounds();
					var image;
					image = {
								  url: 'images/person_1.png',
								  size: new google.maps.Size(25, 25),
								scaledSize: new google.maps.Size(25, 25)
								};
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
					for(var i=0;i<latarr.length;i++)
					{
						if(i==0)
						{
							image='images/start_marker.png';
						}
						else if(i==(latarr.length-1))
						{
							image='images/stop_marker.png';
						}
						else
						{
							image = {
								  url: 'images/person_1.png',
								  size: new google.maps.Size(25, 25),
								scaledSize: new google.maps.Size(25, 25)
								};
						}
						var position;
						var lat_tmp=latarr[i];
						var lng_tmp=lngarr[i];
						position=new google.maps.LatLng(latarr[i], lngarr[i]);				
						latlngbounds.extend(position);				
						//var icon='images/person_1.png';					
						var title='abc';
						var dateTime=datetimearr[i];
						var vSerialLocal=vSerial[i];
						var vNameLocal=vName[i];
						var vNumberLocal=vNumber[i];
						var speedLocal=speed[i];						
						var dTravelLocal=dTravel[i];
						var marker = new google.maps.Marker
						({
							position: position,	 map: map, icon: image
						});					
							 markers.push(marker);
										
						google.maps.event.addListener
						(
							marker, mouse_action, infoCallbackPersonTrack(lat_tmp,lng_tmp,dateTime,vSerialLocal,vNameLocal,vNumberLocal,speedLocal,marker,dTravelLocal)
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
					function deleteOverlays() 
					{
						for (var i = 0; i < markers.length; i++) 
						{
							markers[i].setMap(null);
						}
					}
				}
				
				function setPersonMultipleMarkerLast(map,latarr,lngarr,datetimearr,vSerial,vName,vNumber)
				{  
					//alert('hi1');				
					 deleteOverlays();
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
					var image = {
								  url: 'images/person_1.png',
								  size: new google.maps.Size(25, 25),
								scaledSize: new google.maps.Size(25, 25)
								};
					for(var i=0;i<latarr.length;i++)
					{
						var position;
						var lat_tmp=latarr[i];
						var lng_tmp=lngarr[i];
						//alert('lat='+lat_tmp+'lng='+lng_tmp);
						position=new google.maps.LatLng(latarr[i], lngarr[i]);				
						latlngbounds.extend(position);					
						//var icon='images/person_1.png';						
						var title='abc';
						var dateTime=datetimearr[i];
						var vSerialLocal=vSerial[i];
						var vNameLocal=vName[i];
						var vNumberLocal=vNumber[i];						
						var marker = new google.maps.Marker
						({
							position: position,	 map: map, icon: image
						});					
							markers.push(marker);					
						google.maps.event.addListener
						(						
							marker, mouse_action, infoCallbackPerson(lat_tmp,lng_tmp,dateTime,vSerialLocal,vNameLocal,vNumberLocal,marker)
						);					
					}
					
					map.setCenter(latlngbounds.getCenter());					
					if(latarr.length>0 && latarr.length<2)
					{
						map.setZoom(10);
					}
					else
					{
						map.fitBounds(latlngbounds);
					}
					function deleteOverlays() 
					{
						for (var i = 0; i < markers.length; i++) 
						{
							markers[i].setMap(null);
						}
					}					
				}
				function setMultipleMarkerLast(map,latarr,lngarr,datetimearr,vSerial,vName,vNumber,speed,ioStr,vType,dMaxSpeed,lHaltSpeed)
				{  
					//alert('hi1');				
					 deleteOverlays();
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
					
					for(var i=0;i<latarr.length;i++)
					{
						var position;
						var lat_tmp=latarr[i];
						var lng_tmp=lngarr[i];
						position=new google.maps.LatLng(latarr[i], lngarr[i]);				
						latlngbounds.extend(position);
						var icon1=setIconType(vType[i]);
						
						var title='abc';
						var dateTime=datetimearr[i];
						var vSerialLocal=vSerial[i];
						var vNameLocal=vName[i];
						var vNumberLocal=vNumber[i];
						var speedLocal=speed[i];
						var ioStrLocal=ioStr[i];
						var dMaxSpeedLocal=dMaxSpeed[i];
						var lHaltSpeedLocal=lHaltSpeed[i];
						var marker = new google.maps.Marker
						({
							position: position,	 map: map, icon: icon1
						});					
							markers.push(marker);					
						google.maps.event.addListener
						(						
							marker, mouse_action, infoCallback(lat_tmp,lng_tmp,dateTime,vSerialLocal,vNameLocal,vNumberLocal,speedLocal,ioStrLocal,marker,dMaxSpeedLocal,lHaltSpeedLocal)
						);					
					}
					
					map.setCenter(latlngbounds.getCenter());					
					if(latarr.length>0 && latarr.length<2)
					{
						map.setZoom(10);
					}
					else
					{
						map.fitBounds(latlngbounds);
					}
					if(document.getElementById('category').value=='5')
					{
						document.getElementById('vehicle_milstone').value='vehicle_zoom';			
						show_milestones(map);		
					}
					else if((document.thisform.geofence_feature.checked==true) && (document.getElementById('category').value!=5))
					{
						show_geofence(map_canvas);				
					}
					function deleteOverlays() 
					{
						for (var i = 0; i < markers.length; i++) 
						{
							markers[i].setMap(null);
						}
					}					
				}
				function setMultipleMarkerLive(map,latarr,lngarr,datetimearr,vSerial,vName,vNumber,speed,ioStr,vType,dMaxSpeed,lHaltSpeed,vRoute,vStatus)
				{  
					//alert('hi1');				
					deleteOverlays();									
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
					var latlngbounds = new google.maps.LatLngBounds();
					var clicked_vehicle_list='';
					clicked_vehicle_list=clicked_vehicle_list+'<table>';
					var img;
					if(vStatus == 'Running')
					{
						img = '<img src=images/live/live_vehicle.gif width=8px height=8px>&nbsp;';
					}
					else if(vStatus == 'Idle')
					{
						img = '<img src=images/live/lp_vehicle1.gif width=8px height=8px>&nbsp;';
					}
					else                                                                                                                                                                                                                              
					{
						img = '<img src=images/live/lp_vehicle2.gif width=8px height=8px>&nbsp;';
					}
					for(var i=0;i<latarr.length;i++)
					{
						if(vRoute!='')
						{
							clicked_vehicle_list=clicked_vehicle_list+'<tr><td>'+img+'</td><td>'+vName[i]+'&nbsp;&nbsp;('+vRoute+')<td></tr>';
						}
						else
						{
							clicked_vehicle_list=clicked_vehicle_list+'<tr><td>'+img+'</td><td>'+vName[i]+'<td></tr>';
						}
						var position;
						var lat_tmp=latarr[i];
						var lng_tmp=lngarr[i];
						position=new google.maps.LatLng(latarr[i], lngarr[i]);				
						latlngbounds.extend(position);
						var icon1=setIconType(vType[i]);
						
						var title='abc';
						var dateTime=datetimearr[i];
						var vSerialLocal=vSerial[i];
						var vNameLocal=vName[i];
						var vNumberLocal=vNumber[i];
						var speedLocal=speed[i];
						var ioStrLocal=ioStr[i];
						var dMaxSpeedLocal=dMaxSpeed[i];
						var lHaltSpeedLocal=lHaltSpeed[i];
						var marker = new google.maps.Marker
						({
							position: position,	 map: map, icon: icon1
						});					
							markers.push(marker);					
						google.maps.event.addListener
						(						
							marker, 'click', infoCallbackLive(lat_tmp,lng_tmp,dateTime,vSerialLocal,vNameLocal,vNumberLocal,speedLocal,ioStrLocal,marker,dMaxSpeedLocal,lHaltSpeedLocal)
						);					
					}
					
					clicked_vehicle_list=clicked_vehicle_list+'</table>';
					
					map.setCenter(latlngbounds.getCenter());
					map.fitBounds(latlngbounds);
					function deleteOverlays() 
					{
						for (var i = 0; i < markers.length; i++) 
						{
							markers[i].setMap(null);
						}
					}					
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
				function setMultipleMarker_1(map,latarr, lngarr,datetimearr,vSerial,vName,vNumber,speed,ioStr)
				{  					
					if((latarr.length ==1) && (!latarr[0]) && (!lngarr[0]))
					{
						alert('Sorry! Either -GPS or Data Not Found');
						exit;
					}					
					var latlngbounds = new google.maps.LatLngBounds();
                                        //alert('hi2');
					for(var i=0;i<latarr.length;i++)
					{
						var position;
						var lat_tmp=latarr[i];
						var lng_tmp=lngarr[i];
						position=new google.maps.LatLng(latarr[i], lngarr[i]);				
						latlngbounds.extend(position);				
						var icon='http://test1.itracksolution.co.in/hospitalonwheel/honwheel/app/webroot/img/icon1/help.png';					
						var title='abc';
                                                var dateTime=datetimearr[i];
                                                var vSerialLocal=vSerial[i];
                                                var vNameLocal=vName[i];
                                                var vNumberLocal=vNumber[i];
                                                var speedLocal=speed[i];
                                                var ioStrLocal=ioStr[i];
						var marker = new google.maps.Marker
						({
							position: position,	 map: map, icon: icon
						});					
												
						google.maps.event.addListener
						(
							marker, 'click', infoCallback(lat_tmp,lng_tmp,dateTime,vSerialLocal,vNameLocal,vNumberLocal,speedLocal,ioStrLocal,marker)
						);
						
						
						/*var line = new google.maps.Polyline
						({
							path: [new google.maps.LatLng(latarr[i], lngarr[i]),new google.maps.LatLng(latarr[i+1], lngarr[i+1])],
							strokeColor: '#ff0000',
							strokeOpacity: 1.0,
							strokeWeight: 1.5
						});
						line.setMap(map);*/					
					}
					map.setCenter(latlngbounds.getCenter());
					map.fitBounds(latlngbounds);				
					/*for(var i=1;i<latarr.length;i++)
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
					}*/
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
	function addMultipleMarker_play($map_id, $latarr,$lngarr,$datetimearr,$vehicle_serial_arr,$vehicle_name_arr,$speed_arr,$vehicle_number_arr,$io_str_last,$d_travel_str,$play_interval)
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
			$marker .= "setMultipleMarker_play({$map_id},{$latJs},{$lngJS},{$dateTimeJs},{$vSerialJs},{$vNameJs},{$vNumberJs},{$speedJs},{$ioStr},{$dTravelJs} ,{$play_interval}".")";
		}		
		$marker .= "</script>";
		return $marker;
	}
	
	function addMultipleMarkerPerson($map_id, $latarr,$lngarr,$datetimearr,$vehicle_serial_arr,$vehicle_name_arr,$speed_arr,$vehicle_number_arr,$d_travel_str)
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
			$dTravelJs=json_encode($d_travel_str);                      
			//echo "in if<br>";
			$marker .= "setMultipleMarkerPerson({$map_id},{$latJs},{$lngJS},{$dateTimeJs},{$vSerialJs},{$vNameJs},{$vNumberJs},{$speedJs},{$dTravelJs}".")";
		}		
		$marker .= "</script>";
		return $marker;
	}
	function addMultipleMarkerLast($map_id, $latarr,$lngarr,$datetimearr,$vehicle_serial_arr,$vehicle_name_arr,$speed_arr,$vehicle_number_arr,$io_str_arr,$vehilce_type_arr,$day_max_speed_arrt,$last_halt_time_arr)
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
			$ioStrJs=json_encode($io_str_arr);
			$vehilceTypeJs=json_encode($vehilce_type_arr);
			$dayMaxSpeedJs=json_encode($day_max_speed_arrt);
			$lastHaltTimeJs=json_encode($last_halt_time_arr);
		
                       
			//echo "in if<br>";
			$marker .= "setMultipleMarkerLast({$map_id},{$latJs},{$lngJS},{$dateTimeJs},{$vSerialJs},{$vNameJs},{$vNumberJs},{$speedJs},{$ioStrJs},{$vehilceTypeJs},{$dayMaxSpeedJs},{$lastHaltTimeJs}".")";
		}		
		$marker .= "</script>";
		return $marker;
	}
	
	function addMultipleMarkerLive($map_id, $latarr,$lngarr,$datetimearr,$vehicle_serial_arr,$vehicle_name_arr,$speed_arr,$vehicle_number_arr,$io_str_arr,$vehilce_type_arr,$day_max_speed_arrt,$last_halt_time_arr,$vroute_arr,$vstatus_arr)
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
			$ioStrJs=json_encode($io_str_arr);
			$vehilceTypeJs=json_encode($vehilce_type_arr);
			$dayMaxSpeedJs=json_encode($day_max_speed_arrt);
			$lastHaltTimeJs=json_encode($last_halt_time_arr);
			$vRouteJs=json_encode($vroute_arr); 
			$vStatusJs=json_encode($vstatus_arr); 		
			//echo "in if<br>";
			$marker .= "setMultipleMarkerLive({$map_id},{$latJs},{$lngJS},{$dateTimeJs},{$vSerialJs},{$vNameJs},{$vNumberJs},{$speedJs},{$ioStrJs},{$vehilceTypeJs},{$dayMaxSpeedJs},{$lastHaltTimeJs},{$vRouteJs},{$vStatusJs}".")";
		}		
		$marker .= "</script>";
		return $marker;
	}
	
	function addPersonMultipleMarkerLast($map_id, $latarr,$lngarr,$datetimearr,$vehicle_serial_arr,$vehicle_name_arr,$vehicle_number_arr)
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
			//echo "in if<br>";
			//echo "latJS=".$latJs."<br>";
			$marker .= "setPersonMultipleMarkerLast({$map_id},{$latJs},{$lngJS},{$dateTimeJs},{$vSerialJs},{$vNameJs},{$vNumberJs}".")";
		}		
		$marker .= "</script>";
		return $marker;
	}
        
        function addMultipleMarker_1($map_id, $latarr,$lngarr,$datetimearr,$vehicle_serial_arr,$vehicle_name_arr,$speed_arr,$vehicle_number_arr,$io_str_arr)
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
                        $ioStrJs=json_encode($io_str_arr);
                       
			//echo "in if<br>";
			$marker .= "setMultipleMarker_1({$map_id},{$latJs},{$lngJS},{$dateTimeJs},{$vSerialJs},{$vNameJs},{$vNumberJs},{$speedJs},{$ioStrJs}".")";
		}		
		$marker .= "</script>";
		return $marker;
	}
	
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
        
        function test_123($obj_12,$combinelatlng_str,$latarr,$lngarr,$datetimearr,$vehicleserialarr,$vehiclenamearr,$vehiclenumberarr,$speedarr,$iostrarr){
            
                $combinelatlng_str1=$combinelatlng_str;
                $latarr1=json_encode($latarr);                
                $lngarr1=json_encode($lngarr);
                $datetimearr1=json_encode($datetimearr);
                $vehicleserialarr1=json_encode($vehicleserialarr);
                $vehiclenamearr1=json_encode($vehiclenamearr);
                $vehiclenumberarr1=json_encode($vehiclenumberarr);
                $speedarr1=json_encode($speedarr);
                $iostrarr1=json_encode($iostrarr);
                
                echo $obj_12->script('jquery.js');                
             
			echo"<script type='text/javascript'>                            
                             var xml_address_flag=0;
                              var location_arr=new Array();
                            var lat_arr=new Array();
                            var lng_arr=new Array();
                            var datetime_arr=new Array();
                            var vserial_arr=new Array();
                            var vname_arr=new Array();
                            var vnumber_arr=new Array();
                            var speed_arr=new Array();    
                            var iostr_arr=new Array();
                            var i=0;
                            var address_cnt=0;
                            var delay = 0;
                                test_123('{$combinelatlng_str1}',{$latarr1},{$lngarr1},{$datetimearr1},{$vehicleserialarr1},{$vehiclenamearr1},{$vehiclenumberarr1},{$speedarr1},{$iostrarr1}".")
                               //alert('tmp_test');
                             function test_123(combinelatlngstr,latarr,lngarr,datetimearr,vserialarr,vnamearr,vnumberarr,speedarr,iostrarr)
                             {
                                for(var u=0;u<latarr.length;u++)
                                {
                                    //alert('k='+k);
                                     lat_arr[u]=latarr[u];
                                    lng_arr[u]=lngarr[u];
                                    datetime_arr[u]=datetimearr[u];
                                    vserial_arr[u]=vserialarr[u];
                                    vname_arr[u]=vnamearr[u];
                                    vnumber_arr[u]=vnumberarr[u];
                                    speed_arr[u]=speedarr[u];
                                    iostr_arr[u]=iostrarr[u];
                                }
                                 //alert('combinelatlngstr='+combinelatlngstr);
                                Geocode(combinelatlngstr);                                   
                             }
                             
                            function Geocode(data)
                            {		
                                    i=0;
                                    splitData = data.split(':');
                                    GeocodeNext();        
                            }
                            function GeocodeNext()
                            {
                                    $('#progress').html((i+1).toString() + ' of ' + splitData.length);
                                    var geocoder = new google.maps.Geocoder();";
                                   echo'var splitLatLng = splitData[i].replace("\r", "").split(",");
                                   
                                    // if no commas, try tab
                                    if (splitLatLng.length == 1) 
                                    {
                                           splitLatLng = splitData[i].replace("\r", "").split("\t");
                                           separator = "\t";
                                    }
                                    else
                                    {';
                                          echo"
                                              separator = ',';
                                            var latLng = new google.maps.LatLng(splitLatLng[0], splitLatLng[1]);
                                           // alert('latlng='+latLng);
                                            geocoder.geocode({'latLng': latLng}, function(results, status) 
                                            {
                                                    var foundAddress = false;
                                                    if (status == google.maps.GeocoderStatus.OK) 
                                                    {
                                                            if (results) 
                                                            {
                                                                    var user_lat = '';
                                                                    var user_lng = '';
                                                                    var google_lat = '';
                                                                    var google_lng = '';
                                                                    var distance = '';
                                                                    //getAddressComponent(result, 'country');
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

                                                                                    var distance = calculate_distance(user_lat, google_lat, user_lng, google_lng); 
                                                                                    addLocation(latLng, results[j], distance,user_lat,user_lng,splitLatLng[2]);
                                                                                    foundAddress = true;
                                                                                    break;
                                                                            }
                                                                    }
                                                                    if (!foundAddress) 
                                                                    {
                                                                            xml_address_flag=1;
                                                                            var user_lat = latLng.lat();
                                                                            var user_lng = latLng.lng();
                                                                            var distance='';
                                                                            var address='';
                                                                            addResult(latLng, address, errorMsg,distance,user_lat,user_lng,splitLatLng[2]);
                                                                            //alert('user_lat='+user_lat+'user_lng='+user_lng+'address='+results[j].formatted_address);
                                                                    }
                                                            }
                                                    }
                                                    else 
                                                    {
                                                            // addResult(latLng, errorMsg, errorMsg);				
                                                            var errorMsg = 'Unknown error';
                                                            switch (status) 
                                                            {
                                                                    case google.maps.GeocoderStatus.ZERO_RESULTS : errorMsg = 'No results'; break;
                                                                    case google.maps.GeocoderStatus.OVER_QUERY_LIMIT : errorMsg = 'Over query limit'; break;
                                                                    case google.maps.GeocoderStatus.REQUEST_DENIED : errorMsg = 'Request denied'; break;
                                                                    case google.maps.GeocoderStatus.INVALID_REQUEST : errorMsg = 'Invalid request'; break;
                                                            }
                                                            if (status != google.maps.GeocoderStatus.OVER_QUERY_LIMIT) 
                                                            {
                                                                    // alert('in if');
                                                                    xml_address_flag=1;
                                                                    var user_lat = latLng.lat();
                                                                    var user_lng = latLng.lng();
                                                                    var distance='';
                                                                    var address='';
                                                                    addResult(latLng, address, errorMsg,distance,user_lat,user_lng,splitLatLng[2]);
                                                                    //alert('user_lat='+user_lat+'user_lng='+user_lng+'address='+results[j].formatted_address);
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
                                                            //$('#delay').html('Delay is ' + delay.toString() + ' ms');
                                                            //$('#delay').html('Delay is ' + delay.toString() + ' ms');
                                                            setTimeout('GeocodeNext()', delay);
                                                    }
                                                    else 
                                                    {  
                                                        var poststr='lat='+lat_arr+
                                                        '&lng='+lng_arr+
                                                        '&address='+location_arr+
                                                        '&datetime='+datetime_arr+
                                                        '&vserial='+vserial_arr+
                                                        '&vname='+vname_arr+
                                                        '&vnumber='+vnumber_arr+
                                                        '&vspeed='+speed_arr+
                                                        '&vio='+iostr_arr; 
                                                        //alert('poststr='+poststr);
                                                            $.ajax({
                                                            type:'POST',
                                                            url:'/hospitalonwheel/honwheel/Logins/home_textreport1',
                                                            data: poststr,                             
                                                            success: function(response){
                                                                //console.log(response);
                                                                //alert(response);
                                                                document.getElementById('dummy_div').style.display='';
                                                                 document.getElementById('map_home').style.display='none';
                                                                 $('#dummy_div').html(response);                                       
                                                                // document.getElementById('dummy_div').innerHTML=responsedatas;
                                                            },
                                                            error: function() 
                                                            {
                                                                alert('An unexpected error has occurred! Please try later.');
                                                            }
                                                        });	
                                                    }
                                            });
                                    }
                            }
                             

                            /* var poststr='varr='+dum;                            
                           // alert (poststr);
                            $.ajax({
                                    type:'POST',
                                    url:'/hospitalonwheel/honwheel/Logins/home_textreport1',
                                    data: poststr,                             
                                    success: function(response){
                                        //console.log(response);
                                        //alert(response);
                                        document.getElementById('dummy_div').style.display='';
                                         document.getElementById('map_home').style.display='none';
                                         $('#dummy_div').html(response);                                       
                                        // document.getElementById('dummy_div').innerHTML=responsedatas;
                                    },
                                    error: function() 
                                    {
                                        alert('An unexpected error has occurred! Please try later.');
                                    }
                                });
                                alert('length='+abc.length)
                               var aaa=abc;  */                             
                            
                            
	function buildAddress(result) {
			return getAddressComponent(result, 'street_number') + separator + 
				getAddressComponent(result, 'route') + separator +
				getAddressComponent(result, 'postal_town') + separator +
				getAddressComponent(result, 'administrative_area_level_2') + separator +
				getAddressComponent(result, 'postal_code') + separator +
				getAddressComponent(result, 'country');
				;
		}
		
		function addLocation(latLng, result, distance,user_lat,user_lng,cnt) 
		{
			// add address components if selected
			var address = '\'' + result.formatted_address + '\'';
			//alert('tmp_address='+address);
			//if ($('#addressComponents').is(':checked'))     //COMMENTED FOR TESTING
				//address = buildAddress(result);			
			addResult(latLng, address, result.formatted_address, distance,user_lat,user_lng,cnt);
		}
			
		function addResult(latLng, address, formattedAddress, distance,user_lat,user_lng,cnt) {
     // alert('latLng'+latLng);
      //document.getElementById('geocodedPostcodes').value +=
        //latLng.lat() + separator + latLng.lng() + separator + address + ':';
       // alert('xml_address_flag='+xml_address_flag);
		if(xml_address_flag==0)
		{
			//if((address.indexOf('NH') ==-1) && (address.indexOf('National Highway') ==-1) && (address.indexOf('State Highway') ==-1) && (address.indexOf('SH') ==-1))
			{
				address2=distance + ' km from ' + address + ':'; 
                                location_arr[address_cnt]=address2;
                                address_cnt++;
				//alert('address_with_distance='+address2)	
				// document.getElementById('geocodedPostcodes').value += address2;  				
			}
			/*else
			{		
				//alert('distance='+distance+'address2='+address2);
				//alert('latLng='+latLng);
				 var strURL='get_location_tmp_file.php?point_test='+latLng;
				//alert('strurl:'+strURL);
				var req = getXMLHTTP();
				req.open('GET', strURL, false); //third parameter is set to false here
				req.send(null);  
				var place_name_temp_param = req.responseText; 
				//alert('place_name_temp_param1='+place_name_temp_param);
				place_name_temp_param =place_name_temp_param.split(':');
				//alert('user_lat='+user_lat+'lat='+place_name_temp_param[1]+'user_lng='+user_lng+'log='+place_name_temp_param[2]);
				var distance = calculate_distance(user_lat, place_name_temp_param[1], user_lng, place_name_temp_param[2]);
				//alert('distance='+distance);
				address2 = distance+' km from '+place_name_temp_param[0]+'#'+deviceimeino+ ':';	
				//alert('ifeslseaddress2='+address2);
				//alert('address_with_distance_xml='+address2)
				
				 document.getElementById('geocodedPostcodes').value += address2;  	
				
			}*/
		}
		/*else if(xml_address_flag==1)
		{
			
			 var strURL='get_location_tmp_file.php?point_test='+latLng;
			//alert('strurl:'+strURL);
			var req = getXMLHTTP();
			req.open('GET', strURL, false); //third parameter is set to false here
			req.send(null);  
			var place_name_temp_param = req.responseText; 
			//alert('place_name_temp_param1='+place_name_temp_param);
			place_name_temp_param =place_name_temp_param.split(':');
		
			//alert('user_lat='+user_lat+'lat='+place_name_temp_param[1]+'user_lng='+user_lng+'log='+place_name_temp_param[2]);
			var distance = calculate_distance(user_lat, place_name_temp_param[1], user_lng, place_name_temp_param[2]);
			//alert('distance='+distance);
			address2 = distance+' km from '+place_name_temp_param[0]+'#'+deviceimeino+':';	
			//alert('ifeslseaddress2='+address2);
			//alert('address_with_distance_xml='+address2)			
			 document.getElementById('geocodedPostcodes').value += address2;		
		}*/
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
                            </script>";
	}
        
        function halt_location_with_data($obj_12,$combinelatlngstr,$imeiarr,$vnamearr,$latarr,$lngarr,$avltimearr,$deptimearr,$intemparr,$outtemparr,$durationarr,$date1,$date2,$user_interval){
                $combinelatlng_str1=$combinelatlngstr;
                $imeiarr1=json_encode($imeiarr);                
                $vnamearr1=json_encode($vnamearr);
                $latarr1=json_encode($latarr);                
                $lngarr1=json_encode($lngarr);
                $avltimearr1=json_encode($avltimearr);
                $deptimearr1=json_encode($deptimearr);
                $intemparr1=json_encode($intemparr);
                $outtemparr1=json_encode($outtemparr);
                $durationarr1=json_encode($durationarr);
                //echo "lat=".$latarr." lng=".$lngarr."<br>";

                echo $obj_12->script('jquery.js'); 
                    echo"                      
                        <script type='text/javascript'>                            
                            var xml_address_flag=0;
                            var location_arr=new Array();
                            var lat_arr=new Array();
                            var lng_arr=new Array();
                            var imei_arr=new Array();
                            var vname_arr=new Array();
                            var avltime_arr=new Array();                           
                            var deptime_arr=new Array();
                            var in_temp_arr=new Array();    
                            var out_temp_arr=new Array();
                            var duration_arr=new Array();
                            var date1;
                            var date2;
                            var user_interval;
                            var splitData;
                            
                            var i=0;
                            var address_cnt=0;
                            var delay = 0;
                             var map;
                       
                            var address2='';

                            window.onload = function() 
                            {
                                    var latlng = new google.maps.LatLng(54.559322587438636, -4.1748046875);
                                    var options = 
                                    {
                                            zoom: 6,
                                            center: latlng,
                                            mapTypeId: google.maps.MapTypeId.ROADMAP
                                    };
                                    map = new google.maps.Map(document.getElementById('map_canvas'), options);
                            }
                                halt_location_with_data1('{$combinelatlng_str1}',{$imeiarr1},{$vnamearr1},{$latarr1},{$lngarr1},{$avltimearr1},{$deptimearr1},{$intemparr1},{$outtemparr1},{$durationarr1},'{$date1}','{$date2}','{$user_interval}'".");
                              // alert('tmp_test');
                             function halt_location_with_data1(combinelatlngstr,imeiarr,vnamearr,latarr,lngarr,avltimearr,deptimearr,intemparr,outtemparr,durationarr,startdate,enddate,userinterval)
                             {
                                date1=startdate;
                                date2=enddate;
                                user_interval=userinterval;
                                for(var u=0;u<latarr.length;u++)
                                {                                
                                    lat_arr[u]=latarr[u];
                                    lng_arr[u]=lngarr[u];
                                    imei_arr[u]=imeiarr[u];
                                    vname_arr[u]=vnamearr[u];
                                    avltime_arr[u]=avltimearr[u];                           
                                    deptime_arr[u]=deptimearr[u];
                                    in_temp_arr[u]=intemparr[u];    
                                    out_temp_arr[u]=outtemparr[u];
                                    duration_arr[u]=durationarr[u];                                 
                                    // alert('tmp_test'+u);
                                }
                                //alert('combinelatlngstr='+combinelatlngstr);
                                Geocode(combinelatlngstr);                                   
                             }
                           
                            function Geocode(data)
                            {	
                               // alert('in geocode');
                                    i=0;
                                    splitData = data.split(':');
                                    GeocodeNext();        
                            }
                            function GeocodeNext()
                            {                                
                                 $('#progress').html((i+1).toString() + ' of ' + splitData.length);
                                    var geocoder = new google.maps.Geocoder();";
                                   echo'var splitLatLng = splitData[i].replace("\r", "").split(",");
                                   
                                    // if no commas, try tab
                                    if (splitLatLng.length == 1) 
                                    {
                                           splitLatLng = splitData[i].replace("\r", "").split("\t");
                                           separator = "\t";
                                    }
                                    else
                                    {';
                                          echo"
                                              separator = ',';
                                            var latLng = new google.maps.LatLng(splitLatLng[0], splitLatLng[1]);
                                           // alert('latlng='+latLng);
                                            geocoder.geocode({'latLng': latLng}, function(results, status) 
                                            {
                                                    var foundAddress = false;
                                                    if (status == google.maps.GeocoderStatus.OK) 
                                                    {
                                                            if (results) 
                                                            {
                                                                    var user_lat = '';
                                                                    var user_lng = '';
                                                                    var google_lat = '';
                                                                    var google_lng = '';
                                                                    var distance = '';
                                                                    //getAddressComponent(result, 'country');
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

                                                                                    var distance = calculate_distance(user_lat, google_lat, user_lng, google_lng); 
                                                                                    addLocation(latLng, results[j], distance,user_lat,user_lng,splitLatLng[2]);
                                                                                    foundAddress = true;
                                                                                    break;
                                                                            }
                                                                    }
                                                                    if (!foundAddress) 
                                                                    {
                                                                            xml_address_flag=1;
                                                                            var user_lat = latLng.lat();
                                                                            var user_lng = latLng.lng();
                                                                            var distance='';
                                                                            var address='';
                                                                            addResult(latLng, address, errorMsg,distance,user_lat,user_lng,splitLatLng[2]);
                                                                            //alert('user_lat='+user_lat+'user_lng='+user_lng+'address='+results[j].formatted_address);
                                                                    }
                                                            }
                                                    }
                                                    else 
                                                    {
                                                            // addResult(latLng, errorMsg, errorMsg);				
                                                            var errorMsg = 'Unknown error';
                                                            switch (status) 
                                                            {
                                                                    case google.maps.GeocoderStatus.ZERO_RESULTS : errorMsg = 'No results'; break;
                                                                    case google.maps.GeocoderStatus.OVER_QUERY_LIMIT : errorMsg = 'Over query limit'; break;
                                                                    case google.maps.GeocoderStatus.REQUEST_DENIED : errorMsg = 'Request denied'; break;
                                                                    case google.maps.GeocoderStatus.INVALID_REQUEST : errorMsg = 'Invalid request'; break;
                                                            }
                                                            if (status != google.maps.GeocoderStatus.OVER_QUERY_LIMIT) 
                                                            {
                                                                    // alert('in if');
                                                                    xml_address_flag=1;
                                                                    var user_lat = latLng.lat();
                                                                    var user_lng = latLng.lng();
                                                                    var distance='';
                                                                    var address='';
                                                                    addResult(latLng, address, errorMsg,distance,user_lat,user_lng,splitLatLng[2]);
                                                                    //alert('user_lat='+user_lat+'user_lng='+user_lng+'address='+results[j].formatted_address);
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
                                                            //$('#delay').html('Delay is ' + delay.toString() + ' ms');
                                                            //$('#delay').html('Delay is ' + delay.toString() + ' ms');
                                                            setTimeout('GeocodeNext()', delay);
                                                    }
                                                    else 
                                                    { 
                                                        var poststr1='lat='+lat_arr+
                                                        '&lng='+lng_arr+
                                                        '&google_place='+location_arr+
                                                        '&imei='+imei_arr+
                                                        '&vname='+vname_arr+
                                                        '&arr_time='+avltime_arr+
                                                        '&dep_time='+deptime_arr+
                                                        '&in_temp='+in_temp_arr+
                                                        '&out_temp='+out_temp_arr+
                                                        '&duration='+duration_arr+
                                                        '&title=actionhaltreport1'+ 
                                                        '&date1='+date1+
                                                        '&date2='+date2+
                                                        '&user_interval='+user_interval;
                                                        //alert('poststr='+poststr1);
                                                            $.ajax({
                                                                type: 'POST',
                                                                url:'/hospitalonwheel/honwheel/Reports/vehiclereport_submit',
                                                                data: poststr1,                             
                                                                success: function(response){
                                                                    //console.log(response);
                                                                     //document.getElementById('portal_vehicle_information').style.display='';
                                                                     //document.getElementById('map_home').style.display='none';
                                                                   //alert(response);
                                                                    $('#formentry').html(response);

                                                                    // document.getElementById('dummy_div').innerHTML=responsedatas;
                                                                },
                                                                error: function() 
                                                                {
                                                                    alert('An unexpected error has occurred! Please try later.');
                                                                }
                                                         });
                                                    }
                                            });
                                    }
                            }
                             

                            /* var poststr='varr='+dum;                            
                           // alert (poststr);
                            $.ajax({
                                    type:'POST',
                                    url:'/hospitalonwheel/honwheel/Logins/home_textreport1',
                                    data: poststr,                             
                                    success: function(response){
                                        //console.log(response);
                                        //alert(response);
                                        document.getElementById('dummy_div').style.display='';
                                         document.getElementById('map_home').style.display='none';
                                         $('#dummy_div').html(response);                                       
                                        // document.getElementById('dummy_div').innerHTML=responsedatas;
                                    },
                                    error: function() 
                                    {
                                        alert('An unexpected error has occurred! Please try later.');
                                    }
                                });
                                //alert('length='+abc.length)
                               var aaa=abc;  */                             
                            
                            
	function buildAddress(result) {
			return getAddressComponent(result, 'street_number') + separator + 
				getAddressComponent(result, 'route') + separator +
				getAddressComponent(result, 'postal_town') + separator +
				getAddressComponent(result, 'administrative_area_level_2') + separator +
				getAddressComponent(result, 'postal_code') + separator +
				getAddressComponent(result, 'country');
				;
		}
		
		function addLocation(latLng, result, distance,user_lat,user_lng,cnt) 
		{
			// add address components if selected
			var address = '\'' + result.formatted_address + '\'';
			//alert('tmp_address='+address);
			//if ($('#addressComponents').is(':checked'))     //COMMENTED FOR TESTING
				//address = buildAddress(result);			
			addResult(latLng, address, result.formatted_address, distance,user_lat,user_lng,cnt);
		}
			
		function addResult(latLng, address, formattedAddress, distance,user_lat,user_lng,cnt) {
     // alert('latLng'+latLng);
      //document.getElementById('geocodedPostcodes').value +=
        //latLng.lat() + separator + latLng.lng() + separator + address + ':';
       // alert('xml_address_flag='+xml_address_flag);
		if(xml_address_flag==0)
		{
			//if((address.indexOf('NH') ==-1) && (address.indexOf('National Highway') ==-1) && (address.indexOf('State Highway') ==-1) && (address.indexOf('SH') ==-1))
			{
				address2=distance + ' km from ' + address + ':'; 
                                location_arr[address_cnt]=address2;
                                address_cnt++;
				//alert('address_with_distance='+address2)	
				// document.getElementById('geocodedPostcodes').value += address2;  				
			}
			/*else
			{		
				//alert('distance='+distance+'address2='+address2);
				//alert('latLng='+latLng);
				 var strURL='get_location_tmp_file.php?point_test='+latLng;
				//alert('strurl:'+strURL);
				var req = getXMLHTTP();
				req.open('GET', strURL, false); //third parameter is set to false here
				req.send(null);  
				var place_name_temp_param = req.responseText; 
				//alert('place_name_temp_param1='+place_name_temp_param);
				place_name_temp_param =place_name_temp_param.split(':');
				//alert('user_lat='+user_lat+'lat='+place_name_temp_param[1]+'user_lng='+user_lng+'log='+place_name_temp_param[2]);
				var distance = calculate_distance(user_lat, place_name_temp_param[1], user_lng, place_name_temp_param[2]);
				//alert('distance='+distance);
				address2 = distance+' km from '+place_name_temp_param[0]+'#'+deviceimeino+ ':';	
				//alert('ifeslseaddress2='+address2);
				//alert('address_with_distance_xml='+address2)
				
				 document.getElementById('geocodedPostcodes').value += address2;  	
				
			}*/
		}
		/*else if(xml_address_flag==1)
		{
			
			 var strURL='get_location_tmp_file.php?point_test='+latLng;
			//alert('strurl:'+strURL);
			var req = getXMLHTTP();
			req.open('GET', strURL, false); //third parameter is set to false here
			req.send(null);  
			var place_name_temp_param = req.responseText; 
			//alert('place_name_temp_param1='+place_name_temp_param);
			place_name_temp_param =place_name_temp_param.split(':');
		
			//alert('user_lat='+user_lat+'lat='+place_name_temp_param[1]+'user_lng='+user_lng+'log='+place_name_temp_param[2]);
			var distance = calculate_distance(user_lat, place_name_temp_param[1], user_lng, place_name_temp_param[2]);
			//alert('distance='+distance);
			address2 = distance+' km from '+place_name_temp_param[0]+'#'+deviceimeino+':';	
			//alert('ifeslseaddress2='+address2);
			//alert('address_with_distance_xml='+address2)			
			 document.getElementById('geocodedPostcodes').value += address2;		
		}*/
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
                            </script>";
	}
     
        
        function travel_location_with_data($obj_12,$combinelatlngstart_str,$combinelatlngend_str,$imei,$vname,$lat_start,$lng_start,$lat_end,$lng_end,$landmarkaddress1,$landmarkaddress2,$time1,$time2,$distance_travel,$travel_time,$date1,$date2,$threshold)
        {       
                $imeiarr1=  json_encode($imei);                
                $vnamearr1=json_encode($vname);
                
           
                $latstartarr1=json_encode($lat_start);                
                $lngstartarr1=json_encode($lng_start);
                $latendarr1=json_encode($lat_end);                
                $lngendarr1=json_encode($lng_end);
                $landmarkaddress1_1=json_encode($landmarkaddress1);
                $landmarkaddress2_1=json_encode($landmarkaddress2);
                $time1arr1=json_encode($time1);
                $time2arr1=json_encode($time2);
                $distance_travelarr1=json_encode($distance_travel);
                $travel_timearr1=json_encode($travel_time);
                
                //echo "lat=".$latarr." lng=".$lngarr."<br>";

                echo $obj_12->script('jquery.js'); 
                    echo"                      
                        <script type='text/javascript'>                            
                            var xml_address_flag=0;
                            var locationstart_arr=new Array();
                            var locationend_arr=new Array();
                            var lat_start_arr=new Array();
                            var lng_start_arr=new Array();
                            var lat_end_arr=new Array();
                            var lng_end_arr=new Array();
                            var imei_arr=new Array();
                            var vname_arr=new Array();
                            var landmark_address1_arr=new Array();
                            var landmark_address2_arr=new Array();
                            var time1_arr=new Array();                           
                            var time2_arr=new Array();
                            var distance_travel_arr=new Array();    
                            var travel_time_arr=new Array();
                            var date1;
                            var date2;
                            var threshold1;
                            var splitData;
                            var travel_address_flag='';
                            var endlatlngstr;
                            
                            var i=0;
                            var address_cnt=0;
                            var delay = 0;
                             var map;
                       
                            var address2='';

                            window.onload = function() 
                            {
                                    var latlng = new google.maps.LatLng(54.559322587438636, -4.1748046875);
                                    var options = 
                                    {
                                            zoom: 6,
                                            center: latlng,
                                            mapTypeId: google.maps.MapTypeId.ROADMAP
                                    };
                                    map = new google.maps.Map(document.getElementById('map_canvas'), options);
                            }
                               travel_location_with_data1('{$combinelatlngstart_str}','{$combinelatlngend_str}',{$imeiarr1},{$vnamearr1},{$latstartarr1},{$lngstartarr1},{$latendarr1},{$lngendarr1},{$landmarkaddress1_1},{$landmarkaddress2_1},{$time1arr1},{$time2arr1},{$distance_travelarr1},{$travel_timearr1},'{$date1}','{$date2}','{$threshold}'".");
                              //travel_location_with_data1('{$combinelatlngstart_str}','{$combinelatlngend_str}',{$imeiarr1},{$vnamearr1},{$landmarkaddress1_1},{$landmarkaddress2_1},{$time1arr1},{$time2arr1},{$distance_travelarr1},{$travel_timearr1},'{$date1}','{$date2}','{$threshold}'".");
                               // alert('tmp_test');
                             // 
                                //function travel_location_with_data1(combinelatlngstart_str,combinelatlngend_str,imeiarr,vnamearr,landmarkaddress1_1,landmarkaddress2_1,time1arr1,time2arr1,distance_travelarr1,travel_timearr1,startdate,enddate,threshold)
                               function travel_location_with_data1(combinelatlngstart_str,combinelatlngend_str,imeiarr,vnamearr,latstartarr1,lngstartarr1,latendarr1,lngendarr1,landmarkaddress1_1,landmarkaddress2_1,time1arr1,time2arr1,distance_travelarr1,travel_timearr1,startdate,enddate,threshold)
                                {
                                    endlatlngstr=combinelatlngend_str;
                                    date1=startdate;
                                    date2=enddate;
                                    threshold1=threshold;
                                    for(var u=0;u<imeiarr.length;u++)
                                    { 
                                        lat_start_arr[u]=latstartarr1[u];
                                        lng_start_arr[u]=lngstartarr1[u];
                                        lat_end_arr[u]=latendarr1[u];
                                        lng_end_arr[u]=lngendarr1[u];
                                        imei_arr[u]=imeiarr[u];
                                        vname_arr[u]=vnamearr[u];
                                        landmark_address1_arr[u]=landmarkaddress1_1[u];                           
                                        landmark_address2_arr[u]=landmarkaddress2_1[u];
                                        time1_arr[u]=time1arr1[u];    
                                        time2_arr[u]=time2arr1[u];
                                        distance_travel_arr[u]=distance_travelarr1[u]; 
                                        travel_time_arr[u]=travel_timearr1[u]; 
                                        // alert('tmp_test'+u);
                                    }
                                    //alert('combinelatlngstr='+combinelatlngstr);
                                    Geocode(combinelatlngstart_str,'startaddress');                                
                             }
                           
                            function Geocode(data,tempval)
                            {
                                //alert('tempval='+tempval);
                                travel_address_flag=tempval;
                               // alert('in geocode');
                                    i=0;
                                    splitData = data.split(':');
                                    GeocodeNext();        
                            }
                            function GeocodeNext()
                            {                                
                                 $('#progress').html((i+1).toString() + ' of ' + splitData.length);
                                    var geocoder = new google.maps.Geocoder();";
                                   echo'var splitLatLng = splitData[i].replace("\r", "").split(",");
                                   
                                    // if no commas, try tab
                                    if (splitLatLng.length == 1) 
                                    {
                                           splitLatLng = splitData[i].replace("\r", "").split("\t");
                                           separator = "\t";
                                    }
                                    else
                                    {';
                                          echo"
                                              separator = ',';
                                            var latLng = new google.maps.LatLng(splitLatLng[0], splitLatLng[1]);
                                           // alert('latlng='+latLng);
                                            geocoder.geocode({'latLng': latLng}, function(results, status) 
                                            {
                                                    var foundAddress = false;
                                                    if (status == google.maps.GeocoderStatus.OK) 
                                                    {
                                                            if (results) 
                                                            {
                                                                    var user_lat = '';
                                                                    var user_lng = '';
                                                                    var google_lat = '';
                                                                    var google_lng = '';
                                                                    var distance = '';
                                                                    //getAddressComponent(result, 'country');
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

                                                                                    var distance = calculate_distance(user_lat, google_lat, user_lng, google_lng); 
                                                                                    addLocation(latLng, results[j], distance,user_lat,user_lng,splitLatLng[2]);
                                                                                    foundAddress = true;
                                                                                    break;
                                                                            }
                                                                    }
                                                                    if (!foundAddress) 
                                                                    {
                                                                            xml_address_flag=1;
                                                                            var user_lat = latLng.lat();
                                                                            var user_lng = latLng.lng();
                                                                            var distance='';
                                                                            var address='';
                                                                            addResult(latLng, address, errorMsg,distance,user_lat,user_lng,splitLatLng[2]);
                                                                            //alert('user_lat='+user_lat+'user_lng='+user_lng+'address='+results[j].formatted_address);
                                                                    }
                                                            }
                                                    }
                                                    else 
                                                    {
                                                            // addResult(latLng, errorMsg, errorMsg);				
                                                            var errorMsg = 'Unknown error';
                                                            switch (status) 
                                                            {
                                                                    case google.maps.GeocoderStatus.ZERO_RESULTS : errorMsg = 'No results'; break;
                                                                    case google.maps.GeocoderStatus.OVER_QUERY_LIMIT : errorMsg = 'Over query limit'; break;
                                                                    case google.maps.GeocoderStatus.REQUEST_DENIED : errorMsg = 'Request denied'; break;
                                                                    case google.maps.GeocoderStatus.INVALID_REQUEST : errorMsg = 'Invalid request'; break;
                                                            }
                                                            if (status != google.maps.GeocoderStatus.OVER_QUERY_LIMIT) 
                                                            {
                                                                    // alert('in if');
                                                                    xml_address_flag=1;
                                                                    var user_lat = latLng.lat();
                                                                    var user_lng = latLng.lng();
                                                                    var distance='';
                                                                    var address='';
                                                                    addResult(latLng, address, errorMsg,distance,user_lat,user_lng,splitLatLng[2]);
                                                                    //alert('user_lat='+user_lat+'user_lng='+user_lng+'address='+results[j].formatted_address);
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
                                                            //$('#delay').html('Delay is ' + delay.toString() + ' ms');
                                                            //$('#delay').html('Delay is ' + delay.toString() + ' ms');
                                                            setTimeout('GeocodeNext()', delay);
                                                    }
                                                    else 
                                                    { 
                                                     
                                                        if(travel_address_flag=='startaddress')
                                                        {
                                                             Geocode(endlatlngstr,'endaddress');
                                                        } 
                                                       else if(travel_address_flag=='endaddress')
                                                       {
                                                            var poststr1='lat_start_arr='+lat_start_arr+
                                                            '&lng_start_arr='+lng_start_arr+
                                                            '&lat_end_arr='+lat_end_arr+
                                                            '&lng_end_arr='+lng_end_arr+
                                                            '&locationstart_arr='+locationstart_arr+
                                                            '&locationend_arr='+locationend_arr+
                                                            '&imei='+imei_arr+
                                                            '&vname='+vname_arr+
                                                            '&landmark_address1_arr='+landmark_address1_arr+
                                                            '&landmark_address2_arr='+landmark_address2_arr+
                                                            '&time1_arr='+time1_arr+
                                                            '&time2_arr='+time2_arr+
                                                            '&distance_travel_arr='+distance_travel_arr+
                                                            '&travel_time_arr='+travel_time_arr+
                                                            '&title=actiontravelreport1'+ 
                                                            '&date1='+date1+
                                                            '&date2='+date2+
                                                            '&threshold='+threshold1;
                                                            //alert('poststr='+poststr1);
                                                                $.ajax({
                                                                    type: 'POST',
                                                                    url:'/hospitalonwheel/honwheel/Reports/vehiclereport_submit',
                                                                    data: poststr1,                             
                                                                    success: function(response){
                                                                        //console.log(response);
                                                                         //document.getElementById('portal_vehicle_information').style.display='';
                                                                         //document.getElementById('map_home').style.display='none';
                                                                       //alert(response);
                                                                        $('#formentry').html(response);

                                                                        // document.getElementById('dummy_div').innerHTML=responsedatas;
                                                                    },
                                                                    error: function() 
                                                                    {
                                                                        alert('An unexpected error has occurred! Please try later.');
                                                                    }
                                                             });
                                                        }
                                                    }
                                            });
                                    }
                            }
                             

                            /* var poststr='varr='+dum;                            
                           // alert (poststr);
                            $.ajax({
                                    type:'POST',
                                    url:'/hospitalonwheel/honwheel/Logins/home_textreport1',
                                    data: poststr,                             
                                    success: function(response){
                                        //console.log(response);
                                        //alert(response);
                                        document.getElementById('dummy_div').style.display='';
                                         document.getElementById('map_home').style.display='none';
                                         $('#dummy_div').html(response);                                       
                                        // document.getElementById('dummy_div').innerHTML=responsedatas;
                                    },
                                    error: function() 
                                    {
                                        alert('An unexpected error has occurred! Please try later.');
                                    }
                                });
                                //alert('length='+abc.length)
                               var aaa=abc;  */                             
                            
                            
	function buildAddress(result) {
			return getAddressComponent(result, 'street_number') + separator + 
				getAddressComponent(result, 'route') + separator +
				getAddressComponent(result, 'postal_town') + separator +
				getAddressComponent(result, 'administrative_area_level_2') + separator +
				getAddressComponent(result, 'postal_code') + separator +
				getAddressComponent(result, 'country');
				;
		}
		
		function addLocation(latLng, result, distance,user_lat,user_lng,cnt) 
		{
			// add address components if selected
			var address = '\'' + result.formatted_address + '\'';
			//alert('tmp_address='+address);
			//if ($('#addressComponents').is(':checked'))     //COMMENTED FOR TESTING
				//address = buildAddress(result);			
			addResult(latLng, address, result.formatted_address, distance,user_lat,user_lng,cnt);
		}
			
		function addResult(latLng, address, formattedAddress, distance,user_lat,user_lng,cnt) {
     // alert('latLng'+latLng);
      //document.getElementById('geocodedPostcodes').value +=
        //latLng.lat() + separator + latLng.lng() + separator + address + ':';
       // alert('xml_address_flag='+xml_address_flag);
		if(xml_address_flag==0)
		{
			//if((address.indexOf('NH') ==-1) && (address.indexOf('National Highway') ==-1) && (address.indexOf('State Highway') ==-1) && (address.indexOf('SH') ==-1))
			{
				address2=distance + ' km from ' + address + ':';
                                //alert('travel_address_flag='+travel_address_flag);
                                if(travel_address_flag=='startaddress')
                                {
                                    locationstart_arr[address_cnt]=address2;                                   
                                }
                                else if(travel_address_flag=='endaddress')
                                {
                                    locationend_arr[address_cnt]=address2;
                                }
                                address_cnt++;
				//alert('address_with_distance='+address2)	
				// document.getElementById('geocodedPostcodes').value += address2;  				
			}
			/*else
			{		
				//alert('distance='+distance+'address2='+address2);
				//alert('latLng='+latLng);
				 var strURL='get_location_tmp_file.php?point_test='+latLng;
				//alert('strurl:'+strURL);
				var req = getXMLHTTP();
				req.open('GET', strURL, false); //third parameter is set to false here
				req.send(null);  
				var place_name_temp_param = req.responseText; 
				//alert('place_name_temp_param1='+place_name_temp_param);
				place_name_temp_param =place_name_temp_param.split(':');
				//alert('user_lat='+user_lat+'lat='+place_name_temp_param[1]+'user_lng='+user_lng+'log='+place_name_temp_param[2]);
				var distance = calculate_distance(user_lat, place_name_temp_param[1], user_lng, place_name_temp_param[2]);
				//alert('distance='+distance);
				address2 = distance+' km from '+place_name_temp_param[0]+'#'+deviceimeino+ ':';	
				//alert('ifeslseaddress2='+address2);
				//alert('address_with_distance_xml='+address2)
				
				 document.getElementById('geocodedPostcodes').value += address2;  	
				
			}*/
		}
		/*else if(xml_address_flag==1)
		{
			
			 var strURL='get_location_tmp_file.php?point_test='+latLng;
			//alert('strurl:'+strURL);
			var req = getXMLHTTP();
			req.open('GET', strURL, false); //third parameter is set to false here
			req.send(null);  
			var place_name_temp_param = req.responseText; 
			//alert('place_name_temp_param1='+place_name_temp_param);
			place_name_temp_param =place_name_temp_param.split(':');
		
			//alert('user_lat='+user_lat+'lat='+place_name_temp_param[1]+'user_lng='+user_lng+'log='+place_name_temp_param[2]);
			var distance = calculate_distance(user_lat, place_name_temp_param[1], user_lng, place_name_temp_param[2]);
			//alert('distance='+distance);
			address2 = distance+' km from '+place_name_temp_param[0]+'#'+deviceimeino+':';	
			//alert('ifeslseaddress2='+address2);
			//alert('address_with_distance_xml='+address2)			
			 document.getElementById('geocodedPostcodes').value += address2;		
		}*/
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
                            </script>";
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

