<?php


class GoogleMapHelper{
	//DEFAULT MAP OPTIONS (method map())
	var $defaultId = "map_div";								// Map canvas ID
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

	function addPolylineMarker($map_id, $latarr,$lngarr,$datetimearr,$vehicle_serial_arr,$vehicle_name_arr,$speed_arr,$vehicle_number_arr,$io_str_last,$d_travel_str)
        {
            echo "map_id=".$map_id."<br>";
		
            if($map_id == null) 
            {
                    return null;
            }
             $polyline_marker="<script>";
            if(is_array($latarr) && is_array($lngarr))
            {
                    $latJs=json_encode($latarr);
                    $lngJS=json_encode($lngarr);
                    $polyline_marker .= "
			
			setPolylineMarker({$map_id},{$latJs},{$lngJS}".");";
                        
                    $polyline_marker .= "function setPolylineMarker(map,latarr, lngarr)
                        {
                            map_div = new google.maps.Map(document.getElementById('map_div'), 
                            {
                              zoom: 5,
                              center: new google.maps.LatLng(22.755920681486405, 78.2666015625),
                              mapTypeId: google.maps.MapTypeId.ROADMAP,
                              disableDefaultUI: true,
                              zoomControl: true
                            });
                            
                            if((latarr.length ==1) && (!latarr[0]) && (!lngarr[0]))
                            {
                                    alert('Sorry! Either -GPS or Data Not Found!!');
                                    exit;
                            }
                            flag_polyline=0;
                            var latlngbounds = new google.maps.LatLngBounds();
                            var polygonCoords=new Array();
                            for(var i=0;i<latarr.length;i++)
                            {
                                var lat_tmp=latarr[i];
                                var lng_tmp=lngarr[i];
                                polygonCoords[i] = new google.maps.LatLng(parseFloat(lat_tmp),parseFloat(lng_tmp));	 
                                latlngbounds.extend(new google.maps.LatLng(parseFloat(lat_tmp),parseFloat(lng_tmp)));
                            }
                            var polyOptions_temp = {
                            strokeColor: '#000000',
                            strokeOpacity: 1.0,
                            strokeWeight: 3,
                            editable: true
                            };
                            poly = new google.maps.Polyline(polyOptions_temp);
                            map_div.setCenter(latlngbounds.getCenter());
                            map_div.fitBounds(latlngbounds);
                            poly.setMap(map_div);	
		
                            //alert(flightPath);
		
                            flightPath = new google.maps.Polyline({
                            path: polygonCoords,
                            geodesic: true,
                            strokeColor: '#FF0000',
                            strokeOpacity: 1.0,
                            strokeWeight: 2
                            });
                           
                            flightPath.setMap(map_div);

                            setSelection(flightPath);
                        }";
                    $polyline_marker .= "</script>";
                    return $polyline_marker;
                   /* $z=0;
                   $polyline_marker.="
                            deleteOverlays();
                           var latlngbounds = new google.maps.LatLngBounds();
                           var polygonCoords=new Array();
                           polygonCoords[z] = new google.maps.LatLng(parseFloat(coord2[0]),parseFloat(coord2[1]));	 
                           latlngbounds.extend(new google.maps.LatLng(parseFloat(coord2[0]),parseFloat(coord2[1])));
                           ";
                  $z++ ;  */      
                    
            }
        }
	function addMultipleMarker($map_id, $latarr,$lngarr,$datetimearr,$vehicle_serial_arr,$vehicle_name_arr,$speed_arr,$vehicle_number_arr,$io_str_last,$d_travel_str)
	{
		//print_r($latarr);
		//print_r($lngarr);
		echo "map_id=".$map_id."<br>";
		
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
			$marker .= "
			var markers=new Array();
			var infowindow;
			setMultipleMarker({$map_id},{$latJs},{$lngJS},{$dateTimeJs},{$vSerialJs},{$vNameJs},{$vNumberJs},{$speedJs},{$ioStr},{$dTravelJs}".");";
		}	
		$marker .= "function setMultipleMarker(map,latarr, lngarr,datetimearr,vSerial,vName,vNumber,speed,ioStr,dTravel)
				{ 
				//alert('m='+map);
					//alert('length'+latarr.length);
					deleteOverlays();
					if((latarr.length ==1) && (!latarr[0]) && (!lngarr[0]))
					{
						alert('Sorry! Either -GPS or Data Not Found');
						exit;
					}
					var mouse_action='onClick';
					//var mouse_action = document.forms[0].mouse_action.value; // for google map mouseOver and click option
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
					//var feature_id_map = document.getElementById('station_flag_map').value; 
					 var feature_id_map=1;
					//alert('feature_id_map='+feature_id_map);
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
					
							if(speed[i]>=1 && speed[i]<=20)
							{
								icon='images/yellow_Marker1.png';
								icon_flag=1;
							}							
							else if(speed[i]>20)
							{
								icon='images/green_Marker1.png';
								icon_flag=1;
							}
							
							else if(speed[i]<1)
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
					/*if(document.getElementById('category').value=='5')
					{
						document.getElementById('vehicle_milstone').value='vehicle_zoom';			
						show_milestones(map);		
					}
					else if((document.manage1.geofence_feature.checked==true) && (document.getElementById('category').value!=5))
					{
						show_geofence(map_div);				
					}*/
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
								position: point,	 map: map_div, icon: icon1, title:'landmark'
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
						infowindow.open(map_div, markerL);					 						
					};
				}
				
				function infoCallbackTrack(lat,lng,dateTimeArr,vSerial,vName,vNumber,speed,marker,ioStrLocal,dTravel,feature_id_map) 
				{
					//alert('info');
					return function() 
					{
					//alert('infunction');
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
									contentString=contentString+'</tr><tr>'+								
									'<td class=\"live_td_css1\">Address</td>'+
									'<td>:</td>'+
									'<td class=\"live_td_css2\">'+tmp_address+'</td>'+	
									'</tr>'+									
								   '</tr></table></form>'+											
									'<b><font color=black size=2>('+lat+','+lng+')</font></b>';										
										infowindow.setContent(contentString);
										infowindow.open(map_div, marker);
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
				/*fun abs
				{
				deleteOverlays();
					function deleteOverlays() 
					{
						for (var i = 0; i < markers.length; i++) 
						{
							markers[i].setMap(null);
						}
					}
				}*/
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
				";	
		$marker .= "</script>";
		return $marker;
	}
}
?>

