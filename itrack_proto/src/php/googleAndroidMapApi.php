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
				var newmarkers = new Array();
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
initialLocation = new google.maps.LatLng({$lat},{$lng});

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
				
				function infoCallbackTrack(lat,lng,marker) 
				{					
					return function() 
					{
						var contentString='';
						var contenttmpstr='';
						if (infowindow) 
						{
							infowindow.close();
						}
						infowindow = new google.maps.InfoWindow();
						var latlng = new google.maps.LatLng(lat, lng);
						//alert('latlng='+latlng);
						geocoder.geocode({'latLng': latlng}, function(results, status) 
						{						
							if (status == google.maps.GeocoderStatus.OK) 
							{
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
									contentString='<table>'+
									'<tr>'+								
									'<td class=live_td_css1>Address</td>'+
									'<td>:</td>'+
									'<td class=live_td_css2>'+tmp_address+'</td>'+	
									'</tr>'+
									'<tr>'+											
									'</table>'+
									'<font color=black size=2>('+lat+','+lng+')</font></b>';										
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
				
				function setMultipleMarker(map,latarr, lngarr,cmptype)
				{ 
					//alert('cmptype='+cmptype)
					if(cmptype==1)
					{
						//alert('inif');
						deleteOverlays();
					}
					if((latarr.length ==1) && (!latarr[0]) && (!lngarr[0]))
					{
						alert('Sorry! Either -GPS or Data Not Found');
						exit;
					}					
					var latlngbounds = new google.maps.LatLngBounds();						
					var icon;	
					//alert('len='+latarr.length);
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
							if(cmptype==1)
							{
								icon='images/yellow_Marker1.png';
								icon_flag=1;
							}
							else if(cmptype==2)
							{
								icon='images/iconDot.gif';
								icon_flag=1;							
							}
						}
						
						var position;
						var title='abc';
						var lat_tmp=latarr[i];
						var lng_tmp=lngarr[i];
						position=new google.maps.LatLng(latarr[i], lngarr[i]);				
						latlngbounds.extend(position);
						if(icon_flag==1)
						{
							var marker = new google.maps.Marker
							({
								position: position,	 map: map, icon: icon, title:title
							});	
						}
						markers.push(marker);
										
						google.maps.event.addListener
						(
							marker, 'click', infoCallbackTrack(lat_tmp,lng_tmp,marker)
						);
						if(cmptype==1)
						{
							var line = new google.maps.Polyline
							({
								path: [new google.maps.LatLng(latarr[i], lngarr[i]),new google.maps.LatLng(latarr[i-1], lngarr[i-1])],
								strokeColor: '#ff0000',
								strokeOpacity: 1.0,
								strokeWeight: 1.5
							});	
						}
						else if(cmptype==2)
						{
							var line = new google.maps.Polyline
							({
								path: [new google.maps.LatLng(latarr[i], lngarr[i]),new google.maps.LatLng(latarr[i-1], lngarr[i-1])],
								strokeColor: '#0F13FF',
								strokeOpacity: 1.0,
								strokeWeight: 1.5
							});
						}
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
	
	function addMultipleMarker($map_id, $latarr,$lngarr,$cmpType)
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
				$marker .= "setMultipleMarker({$map_id},{$latJs},{$lngJS},{$cmpType}".")";
			
		}		
		$marker .= "</script>";
		return $marker;
	}
       
}
?>

