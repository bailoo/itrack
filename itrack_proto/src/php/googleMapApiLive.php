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
		//echo "eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('7 m(a){2.3=a;2.8=V.1E('1u');2.8.4.C='I: 1m; J: 1g;';2.k=V.1E('1u');2.k.4.C=2.8.4.C}m.l=E 6.5.22();m.l.1Y=7(){n c=2;n h=t;n f=t;n j;n b;n d,K;n i;n g=7(e){p(e.1v){e.1v()}e.2b=u;p(e.1t){e.1t()}};2.1s().24.G(2.8);2.1s().20.G(2.k);2.11=[6.5.9.w(V,'1o',7(a){p(f){a.s=j;i=u;6.5.9.r(c.3,'1n',a)}h=t;6.5.9.r(c.3,'1o',a)}),6.5.9.o(c.3.1P(),'1N',7(a){p(h&&c.3.1M()){a.s=E 6.5.1J(a.s.U()-d,a.s.T()-K);j=a.s;p(f){6.5.9.r(c.3,'1i',a)}F{d=a.s.U()-c.3.Z().U();K=a.s.T()-c.3.Z().T();6.5.9.r(c.3,'1e',a)}}}),6.5.9.w(2.k,'1d',7(e){c.k.4.1c='2i';6.5.9.r(c.3,'1d',e)}),6.5.9.w(2.k,'1D',7(e){c.k.4.1c=c.3.2g();6.5.9.r(c.3,'1D',e)}),6.5.9.w(2.k,'1C',7(e){p(i){i=t}F{g(e);6.5.9.r(c.3,'1C',e)}}),6.5.9.w(2.k,'1A',7(e){g(e);6.5.9.r(c.3,'1A',e)}),6.5.9.w(2.k,'1z',7(e){h=u;f=t;d=0;K=0;g(e);6.5.9.r(c.3,'1z',e)}),6.5.9.o(2.3,'1e',7(a){f=u;b=c.3.1b()}),6.5.9.o(2.3,'1i',7(a){c.3.O(a.s);c.3.D(2a)}),6.5.9.o(2.3,'1n',7(a){f=t;c.3.D(b)}),6.5.9.o(2.3,'29',7(){c.O()}),6.5.9.o(2.3,'28',7(){c.D()}),6.5.9.o(2.3,'27',7(){c.N()}),6.5.9.o(2.3,'26',7(){c.N()}),6.5.9.o(2.3,'25',7(){c.16()}),6.5.9.o(2.3,'23',7(){c.15()}),6.5.9.o(2.3,'21',7(){c.13()}),6.5.9.o(2.3,'1Z',7(){c.L()}),6.5.9.o(2.3,'1X',7(){c.L()})]};m.l.1W=7(){n i;2.8.1r.1q(2.8);2.k.1r.1q(2.k);1p(i=0;i<2.11.1V;i++){6.5.9.1U(2.11[i])}};m.l.1T=7(){2.15();2.16();2.L()};m.l.15=7(){n a=2.3.z('Y');p(H a.1S==='P'){2.8.W=a;2.k.W=2.8.W}F{2.8.G(a);a=a.1R(u);2.k.G(a)}};m.l.16=7(){2.k.1Q=2.3.1O()||''};m.l.L=7(){n i,q;2.8.S=2.3.z('R');2.k.S=2.8.S;2.8.4.C='';2.k.4.C='';q=2.3.z('q');1p(i 1L q){p(q.1K(i)){2.8.4[i]=q[i];2.k.4[i]=q[i]}}2.1l()};m.l.1l=7(){2.8.4.I='1m';2.8.4.J='1g';p(H 2.8.4.B!=='P'){2.8.4.1k='1j(B='+(2.8.4.B*1I)+')'}2.k.4.I=2.8.4.I;2.k.4.J=2.8.4.J;2.k.4.B=0.1H;2.k.4.1k='1j(B=1)';2.13();2.O();2.N()};m.l.13=7(){n a=2.3.z('X');2.8.4.1h=-a.x+'v';2.8.4.1f=-a.y+'v';2.k.4.1h=-a.x+'v';2.k.4.1f=-a.y+'v'};m.l.O=7(){n a=2.1G().1F(2.3.Z());2.8.4.12=a.x+'v';2.8.4.M=a.y+'v';2.k.4.12=2.8.4.12;2.k.4.M=2.8.4.M;2.D()};m.l.D=7(){n a=(2.3.z('14')?-1:+1);p(H 2.3.1b()==='P'){2.8.4.A=2h(2.8.4.M,10)+a;2.k.4.A=2.8.4.A}F{2.8.4.A=2.3.1b()+a;2.k.4.A=2.8.4.A}};m.l.N=7(){p(2.3.z('1a')){2.8.4.Q=2.3.2f()?'2e':'1B'}F{2.8.4.Q='1B'}2.k.4.Q=2.8.4.Q};7 19(a){a=a||{};a.Y=a.Y||'';a.X=a.X||E 6.5.2d(0,0);a.R=a.R||'2c';a.q=a.q||{};a.14=a.14||t;p(H a.1a==='P'){a.1a=u}2.1y=E m(2);6.5.18.1x(2,1w)}19.l=E 6.5.18();19.l.17=7(a){6.5.18.l.17.1x(2,1w);2.1y.17(a)};',62,143,'||this|marker_|style|maps|google|function|labelDiv_|event|||||||||||eventDiv_|prototype|MarkerLabel_|var|addListener|if|labelStyle|trigger|latLng|false|true|px|addDomListener|||get|zIndex|opacity|cssText|setZIndex|new|else|appendChild|typeof|position|overflow|cLngOffset|setStyles|top|setVisible|setPosition|undefined|display|labelClass|className|lng|lat|document|innerHTML|labelAnchor|labelContent|getPosition||listeners_|left|setAnchor|labelInBackground|setContent|setTitle|setMap|Marker|MarkerWithLabel|labelVisible|getZIndex|cursor|mouseover|dragstart|marginTop|hidden|marginLeft|drag|alpha|filter|setMandatoryStyles|absolute|dragend|mouseup|for|removeChild|parentNode|getPanes|stopPropagation|div|preventDefault|arguments|apply|label|mousedown|dblclick|none|click|mouseout|createElement|fromLatLngToDivPixel|getProjection|01|100|LatLng|hasOwnProperty|in|getDraggable|mousemove|getTitle|getMap|title|cloneNode|nodeType|draw|removeListener|length|onRemove|labelstyle_changed|onAdd|labelclass_changed|overlayMouseTarget|labelanchor_changed|OverlayView|labelcontent_changed|overlayImage|title_changed|labelvisible_changed|visible_changed|zindex_changed|position_changed|1000000|cancelBubble|markerLabels|Point|block|getVisible|getCursor|parseInt|pointer'.split('|'),0,{}))";
			
	echo"	
<style> 
  .labels {
     color: red;   
     font-family: 'Lucida Grande', 'Arial', sans-serif;
     font-size: 10px;
     font-weight: bold;
     text-align: center;
     width: auto;     
     border: 1px solid black;
     white-space: nowrap;
   }
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
				 var point_prev = new Array();  // FOR RUNNING PATH
				 var angle_prev = new Array();
				 var vname_prev =new Array();
				 var trail_flag = false;
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
		    var browserSupportFlag =  new Boolean();
		    var {$id};
		    var myOptions = {
		      zoom: {$zoom},
		      mapTypeId: google.maps.MapTypeId.{$type}
		      ".(($custom != "")? ",$custom" : "")."

		    };
		    {$id} = new google.maps.Map(document.getElementById('$id'), myOptions);
		";
		$map.="

			function setCenterMap(position){
		";
		if($localize) $map .= "localize();";
		else {
			$map .= "{$id}.setCenter(position);";
			//if (!preg_match('/^https?:\/\//', $markerIcon)) $markerIcon = $this->webroot . IMAGES_URL . '/' . $markerIcon;
			//if($marker) $map .= "setMarker({$id},'center',position,'{$markerTitle}','{$markerIcon}','{$markerShadow}','{$windowText}', ".($infoWindow? 'true' : 'false').");";
		}
		$map .="
			}
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
				function infoCallbackLive(lat,lng,dateTimeArr,vSerial,vName,vNumber,speed,ioStr,marker,maxSpeed,maxHaltTime,route,nearest_customer_string,transporter_remark_string,vehicleStatus) 
				{	
					return function() 
					{
						var contentString='';
						if (infowindow) infowindow.close();
						infowindow = new google.maps.InfoWindow();
						var latlng = new google.maps.LatLng(lat, lng);
						//alert('latlng='+latlng);
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
									for (var j=0; j<results.length; j++) 
									{ 
										//alert('type='+results[j].types[0]);
										if ((results[j].types[0] == 'street_address') || (results[j].types[0] == 'route')) 
										{
											google_lat = results[j].geometry.location.lat();
											google_lng = results[j].geometry.location.lng();								
											distance = calculate_distance(lat, google_lat, lng, google_lng);
											var str=lat+','+lng;
											var strURL='src/php/select_landmark_marker.php?content='+str;			
        
											var req = getXMLHTTP();
											req.open('GET', strURL, false); //third parameter is set to false here
											req.send(null);
											var landmark = req.responseText;
											if(landmark!='')
											{
												var tmp_address=landmark;
											}
											else
											{
												var tmp_address=distance+' Km From '+results[j].formatted_address;
											}
							contentString='<table cellspacing=0 cellpadding=0>'+
											'<tr>'+
											'<td class=live_td_css1>Vehicle Name</td>'+
											'<td>&nbsp;:&nbsp;</td>'+
											'<td class=live_td_css2>'+vName+'</td>'+
										   '</tr>'+
										   '<tr>'+
											'<td class=live_td_css1>Imei</td>'+
											'<td>&nbsp;:&nbsp;</td>'+
											'<td class=live_td_css2>'+vSerial+'</td>'+
										   '</tr>'+
											/*'<tr>'+
											'<td class=live_td_css1>Driver Name/Mob </td>'+
											'<td>&nbsp;:&nbsp;</td>'+
											'<td class=live_td_css2>'+vNumber+'</td>'+
										   '</tr>'+*/										  
										   '<tr>'+
											'<td class=live_td_css1>Speed</td>'+
											'<td>&nbsp;:&nbsp;</td>'+
											'<td class=live_td_css2>'+speed+'</td>'+
										   '</tr>'+
										   '<tr>'+
											'<tr>'+                                                    
											'<td class=live_td_css1>Date Time</td>'+
											'<td>&nbsp;:&nbsp;</td>'+
											'<td class=live_td_css2>'+dateTimeArr+'</td>'+
										    '</tr>'+										   
											'<td class=live_td_css1>Day Max Speed</td>'+
											'<td>&nbsp;:&nbsp;</td>'+
											'<td class=live_td_css2>'+maxSpeed+'</td>'+ 
											'<tr>'+
											'<td class=live_td_css1>Last Halt Time</td>'+
											'<td>&nbsp;:&nbsp;</td>'+
											'<td class=live_td_css2>'+maxHaltTime+'</td>'+
											'</tr>'+
											 '<tr>'+
											'<td class=live_td_css1>Place</td>'+
											'<td>&nbsp;:&nbsp;</td>'+
											'<td class=live_td_css2>'+tmp_address+'</td>'+ioStr+ 
										    '<tr>'+
											'<tr>'+
											'<td class=live_td_css1>Status</td>'+
											'<td>&nbsp;:&nbsp;</td>'+
											'<td class=live_td_css2>'+vehicleStatus+'</td>'+
											'</tr>';											
											if(route!=null)
											{
												var route_tr=nearest_customer_string+transporter_remark_string;
											}
											contentString=contentString+route_tr;
											'</table>';
											//'<b><font color=black size=2>('+lat+','+lng+')</font></b>';										
												infowindow.setContent(contentString);
												infowindow.open(map_canvas, marker);
										}
									}
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
						'<td>Landmark Name</td>'+
						'<td>:</td>'+
						'<td>'+landmark+'</td>'+
					   '</tr>'+
						'<tr>'+
						'<td>Coordinates</td>'+
						'<td>:</td>'+
						'<td>'+lat+','+lng+'</td>'+
					   '</tr>'+										   							
						'</table>';
						//alert('icontentString'+contentString);
						//alert('map_canvas'+map_canvas);			
						infowindow.setContent(contentString);
						infowindow.open(map_canvas, markerL);					 						
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
									size: new google.maps.Size(15, 15),
									scaledSize: new google.maps.Size(15, 15),
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
				
				function setMultipleMarkerLive(map,latarr,lngarr,datetimearr,vSerial,vName,vNumber,speed,ioStr,vType,dMaxSpeed,lHaltSpeed,vRoute,vStatus)
				{ 							
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
					var icon1;
					var right_vehicle_style;
					var vehicleNameLabel;
					var marker;
					var angle_deg;
					for(var i=0;i<latarr.length;i++)
					{	
						alert('vstatus='+vStatus[i]+'vRoute='+vRoute);
						var route='';
						var feature_id_map = document.getElementById('station_flag_map').value;						
						if(feature_id_map == 1)
						{
							var len_route;		
							var vname_id;
							var route_id;									
							if(route_shift == 1)
							{
								len_route = document.getElementById('route_limit_ev').value;
								vname_id = 'vname_ev';
								route_id = 'route_ev';			
							}
							else if(route_shift == 2)
							{
								len_route = document.getElementById('route_limit_mor').value;
								vname_id = 'vname_mor';
								route_id = 'route_mor';			
							}
					
							for(var iu=0;iu<len_route;iu++)
							{
								var vname_id_tmp = vname_id+iu;
								var route_id_tmp = route_id+iu;								
								//alert('vname='+vname_id_tmp+' ,route_id='+route_id_tmp);			
								var master_vehicle = document.getElementById(vname_id_tmp).value;
								var master_route = document.getElementById(route_id_tmp).value;
								//alert('master_vehicle='+master_vehicle+' ,vehiclename='+vName[i]+' ,vname_id='+vname_id+' ,route_id='+route_id);
								if( trim(master_vehicle) == trim(vName[i]))
								{
									route = master_route;
									//alert('matched='+route);
									break;
								}
							}
							if(route=='')
							{
								route = 'NA';
							}							
						}
						else
						{
							
						}
						if(vRoute[i]==null)
						{
							vRoute[i]='NA';
						}
						if(vStatus[i] == 'Running')
						{
							img = '<img src=images/live/live_vehicle.gif width=8px height=8px>&nbsp;';
							icon1 = {
								  url: 'images/live/live_vehicle.gif',
								    size: new google.maps.Size(8, 8),
								scaledSize: new google.maps.Size(8, 8),
								anchor: new google.maps.Point(0, 32)
								};
							right_vehicle_style= '\"font-size: 10px;color: green\"';
							vehicleNameLabel='<table style=\"background-color: green;color:white;font-size: 11px; font-weight: bold;font-family: Lucida Grande, Arial, sans-serif;\" >'+
												'<tr>'+
													'<td>'+vName[i]+
													'</td>'+
												'</tr>'+
											'</table>';
						}
						else if(vStatus[i] == 'Idle')
						{
							img = '<img src=images/live/lp_vehicle1.gif width=8px height=8px>&nbsp;';							
								icon1 = {
											url: 'images/live/lp_vehicle1.gif',
											size: new google.maps.Size(8, 8),
											scaledSize: new google.maps.Size(8, 8),
											anchor: new google.maps.Point(0, 32)
										};
							right_vehicle_style= '\"font-size: 10px;color: yellow\"';	
							vehicleNameLabel='<table style=\"background-color: yellow;color:white;font-size: 11px; font-weight: bold;font-family: Lucida Grande, Arial, sans-serif;\" >'+
												'<tr>'+
													'<td>'+vName[i]+
													'</td>'+
												'</tr>'+
											'</table>';
						}
						else                                                                                                                                                                                                                              
						{
							img = '<img src=images/live/lp_vehicle2.gif width=8px height=8px>&nbsp;';							
							icon1 = {
										url: 'images/live/lp_vehicle2.gif',
										size: new google.maps.Size(8, 8),
										scaledSize: new google.maps.Size(8, 8),
										anchor: new google.maps.Point(0, 12)
									};
							right_vehicle_style= '\"font-size: 10px;color: red\"';
							vehicleNameLabel='<table style=\"background-color: red;color:white;font-size: 11px; font-weight: bold;font-family: Lucida Grande, Arial, sans-serif;\" >'+
												'<tr>'+
													'<td>'+vName[i]+
													'</td>'+
												'</tr>'+
											'</table>';
						}
						
						var feature_id_map = document.getElementById('station_flag_map').value;				
						if(feature_id_map == 1)
						{
							Load_Data2(route,latarr[i],lngarr[i],vNameLocal);
							route_after = route;
						}
						
						var nearest_customer_string = '';
						var transporter_remark_string = '';
						transporter = '-';
						remark = '-';
						
						var feature_id_map = document.getElementById('station_flag_map').value;
						if(feature_id_map == 1)
						{
							//##################### GET NEAREST DISTANCE #######################
							var lowest_dist = 0;
							var matched_customer ='';
							var lt_v = latarr[i];
							var lng_v = lngarr[i];
						
							//alert('len='+lat_customer_tmp.length);
							//var m=0;
							for(var k=0;k<lat_customer_tmp.length;k++)
							{
								//if(trim(vehiclename) == trim(vehicle_tmp[k]))
								//{
									var distance = calculate_distance(lt_v,lat_customer_tmp[k],lng_v,lng_customer_tmp[k]);
									//alert('dist='+distance);
									if(k==0)
									{
										lowest_dist = distance;
										matched_customer = matched_customer_tmp[k];
									}
									else
									{
										if(distance < lowest_dist)
										{
											lowest_dist = distance;
											matched_customer = matched_customer_tmp[k];
										}
									}	
								//}
								//m++;
							}
							
							var lowest_dist = Math.round(lowest_dist*100)/100;
							var customer_dist = '<font color=green><strong>'+matched_customer+'</strong></font> ('+lowest_dist+' km)';
							//alert('customer_dist='+customer_dist)							
							nearest_customer_string = '<tr><td class=live_td_css1>Route No</td><td>&nbsp;:&nbsp;</td><td class=live_td_css2>'+route+'</td></tr><tr><td class=live_td_css1>Nearest Customer</td><td>&nbsp;:&nbsp;</td><td class=live_td_css2>'+customer_dist+'</td></tr>';
							//################# NEAREST DISTANCE CLOSED ##########################							
							//################# GET TRANSPORTER AND REMARK #######################
							transporter_remark_string = '<tr><td class=live_td_css1>Transporter</td><td>&nbsp;:&nbsp;</td><td class=live_td_css2>-</td></tr><tr><td class=live_td_css1>Remark</td><td>&nbsp;:&nbsp;</td><td class=live_td_css2>-</td></tr>';			
							if(remark_tmp.length>0 && transporter_tmp.length>0)
							{
								var match_v = false;
								var sel_vehicle = '';
								var tpt_final = '';
								for(var j=0;j<transporter_tmp.length;j++)
								{				
									tpt_tmp = transporter_tmp[j].split(':');
									sel_vehicle = tpt_tmp[0];
									if(trim(sel_vehicle ) == trim(vName[i]))
									{
										match_v = true;
										tpt_final = tpt_tmp[1];
										break;
									}
								}
								if(match_v)
								{
									remark = remark_tmp[0];		//####### remark will be the same each case (Vehicle & Route has single remark)
									transporter = tpt_final;  //######### tpt string 
									transporter_remark_string = '<tr><td class=live_td_css1>Transporter</td><td>&nbsp;:&nbsp;</td><td class=live_td_css2>'+transporter+'</td></tr><tr><td class=live_td_css1>Remark</td><td>&nbsp;:&nbsp;</td><td class=live_td_css2>'+remark+'</td></tr>';			
								}			
							}		
							//################# TRANSPORTER AND REMARK CLOSED ####################				
						}						
						var position;
						var lat_tmp=latarr[i];
						var lng_tmp=lngarr[i];
						position=new google.maps.LatLng(latarr[i], lngarr[i]);				
						
						
						var title='abc';
						var dateTime=datetimearr[i];
						var vSerialLocal=vSerial[i];
						var vNameLocal=vName[i];
						var vNumberLocal=vNumber[i];
						var speedLocal=speed[i];
						var ioStrLocal=ioStr[i];
						var dMaxSpeedLocal=dMaxSpeed[i];
						var lHaltSpeedLocal=lHaltSpeed[i];
																
						//alert('trail_flag='+trail_flag);
						if(trail_flag==false)
						{
							angle_deg='1';
							if(vStatus[i] == 'Running')
							{						
								marker = new MarkerWithLabel({
								   position: position,
								   draggable: true,
								   icon:icon1,
								   map: map,
								   labelContent: vehicleNameLabel,
								   labelAnchor: new google.maps.Point(-12, 38)
								 });				
							 }						 
							 else
							 {
								marker = new MarkerWithLabel({
								   position: position,
								   draggable: true,
								   icon:icon1,
								   map: map,
								   labelContent: vehicleNameLabel,
								   labelAnchor: new google.maps.Point(-12, 18)
								 });
							 }							 
							latlngbounds.extend(position);
							markers.push(marker);
						}							
								
						if(!(document.getElementById('trail_path').checked))
						{		
							//alert('in reint2');	 	  
							//point_prev = null;	
							point_prev = new Array(); 
							angle_prev = new Array(); 	
							vname_prev=new Array();
							trail_flag = false;	
						}							
							if(document.getElementById('trail_path').checked)
							{
								for(var j=0;j<point_prev.length;j++)
								{
									if(vname_prev[j]==vName[i])
									{
										var lattmplive1 = point_prev[j].lat();
										var lngtmplive1 = point_prev[j].lng();
																					   
										// alert('lattmplive1='+lattmplive1+'lngtmplive1='+lngtmplive1);
										lattmplive2 = position.lat();
										lngtmplive2 = position.lng();
										//alert('lat2='+lat2+'lng2='+lng2);                                                 
										var yaxis = (parseFloat(lattmplive1) + parseFloat(lattmplive2))/2;
										var xaxis = (parseFloat(lngtmplive1) + parseFloat(lngtmplive2))/2;
										//alert('yaxis='+yaxis+'xaxis='+xaxis);
										var angle_t = Math.atan( (parseFloat(lattmplive2)-parseFloat(lattmplive1))/(parseFloat(lngtmplive2)-parseFloat(lngtmplive1)) );
										angle_deg = 360 * angle_t/(2 * Math.PI);
										if((lngtmplive2-lngtmplive1)<0)
										{
											angle_deg = 180 + angle_deg;
										}
										else if((lattmplive2-lattmplive1)<0)
										{
											angle_deg = 360 + angle_deg;
										}
										angle_deg = Math.round(angle_deg,0);
										//alert('angle_degree='+angle_deg);

										var image = 
										{
											url: 'images/arrow_images/'+angle_deg+'.png'									
										};
									   // alert('image='+image);
										position=new google.maps.LatLng(yaxis, xaxis);
									   // alert('position='+position);											
										
										if(trail_flag==true)
										{
											if(vStatus[i] == 'Running')
											{						
												marker = new MarkerWithLabel({
												   position: position,
												   draggable: true,
												   icon:image,
												   map: map,
												   labelContent: vehicleNameLabel,
												   labelAnchor: new google.maps.Point(-12, 38)
												 });				
											 }						 
											 else
											 {
												marker = new MarkerWithLabel({
												   position: position,
												   draggable: true,
												   icon:image,
												   map: map,
												   labelContent: vehicleNameLabel,
												   labelAnchor: new google.maps.Point(-12, 18)
												 });
											 }																																	
											markers.push(marker);
										}
										
										if(document.getElementById('trail_path_real').checked)
										{
											var line = new google.maps.Polyline
											({
												path: [point_prev[j], position],
												strokeColor: '#ff0000',
												strokeOpacity: 1.0,
												strokeWeight: 1.5
											});	
											markers.push(line);						
											line.setMap(map);
										}
										//alert('point1='+point_prev[j]+'point2='+position);
										if(point_prev[j]!=position)
										{
											point_prev[j] = position;									
											angle_prev[j] = angle_deg;
											vname_prev[j] = vName[i];
											trail_flag = true;
											//break;
										}
										break;
									}
								}
							}
							if(feature_id_map==1)
							{
								
								//alert('feature_id_map='+feature_id_map+'route='+vRoute[i]+'route='+route);
								clicked_vehicle_list=clicked_vehicle_list+'<tr><td>'+img+'</td><td style='+right_vehicle_style+'><a href=\"#\" Onclick=liveMarkerWithRoute('+latarr[i]+','+lngarr[i]+',\''+encodeURI(datetimearr[i])+'\',\''+vSerial[i]+'\',\''+encodeURI(vName[i])+'\',\''+vNumber[i]+'\',\''+speed[i]+'\',\''+encodeURI(ioStr[i])+'\',\''+encodeURI(dMaxSpeed[i])+'\',\''+encodeURI(lHaltSpeed[i])+'\',\''+route+'\',\''+encodeURI(nearest_customer_string)+'\',\''+encodeURI(transporter_remark_string)+'\',\''+encodeURI(vStatus[i])+'\',\''+vRoute[i]+'\',\''+encodeURI(vehicleNameLabel)+'\',\''+trail_flag+'\','+angle_deg+') >'+vName[i]+'&nbsp;&nbsp;<font color=red>('+vRoute[i]+')&nbsp;</font><font color=blue>['+vStatus[i]+']</font></a><td></tr>';
								google.maps.event.addListener
								(
									marker, 'click', infoCallbackLive(lat_tmp,lng_tmp,dateTime,vSerialLocal,vNameLocal,vNumberLocal,speedLocal,ioStrLocal,marker,dMaxSpeedLocal,lHaltSpeedLocal,route,nearest_customer_string,transporter_remark_string,vStatus[i])
								);
							}
							else
							{
								route='';
								nearest_customer_string='';
								transporter_remark_string='';
								clicked_vehicle_list=clicked_vehicle_list+'<tr><td>'+img+'</td><td style='+right_vehicle_style+'><a href=\"#\" Onclick=liveMarkerWithRoute('+latarr[i]+','+lngarr[i]+',\''+encodeURI(datetimearr[i])+'\',\''+vSerial[i]+'\',\''+encodeURI(vName[i])+'\',\''+vNumber[i]+'\',\''+speed[i]+'\',\''+encodeURI(ioStr[i])+'\',\''+encodeURI(dMaxSpeed[i])+'\',\''+encodeURI(lHaltSpeed[i])+'\',\"\",\"\",\"\",\''+encodeURI(vStatus[i])+'\',\''+vRoute[i]+'\',\''+encodeURI(vehicleNameLabel)+'\',\''+trail_flag+'\','+angle_deg+') >'+vName[i]+'&nbsp;<font color=blue>['+vStatus[i]+']</font></a><td></tr>';
								google.maps.event.addListener
								(
									marker, 'click', infoCallbackLive(lat_tmp,lng_tmp,dateTime,vSerialLocal,vNameLocal,vNumberLocal,speedLocal,ioStrLocal,marker,dMaxSpeedLocal,lHaltSpeedLocal,route,nearest_customer_string,transporter_remark_string,vStatus[i])
								);							
							}
							if(trail_flag==false)
							{
								point_prev[i] = position;
								vname_prev[j]=vName[i];
							}
							latlngbounds.extend(position);
					}
					
					clicked_vehicle_list=clicked_vehicle_list+'</table>';
					//alert('clicked_vehicle_list='+clicked_vehicle_list);
				 document.getElementById('examplePanel2').innerHTML = clicked_vehicle_list;  
					
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
	
	function addMultipleMarker($map_id, $latarr,$lngarr,$datetimearr,$vehicle_serial_arr,$vehicle_name_arr,$speed_arr,$vehicle_number_arr,$d_travel_str)
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
			$marker .= "setMultipleMarker({$map_id},{$latJs},{$lngJS},{$dateTimeJs},{$vSerialJs},{$vNameJs},{$vNumberJs},{$speedJs},{$dTravelJs}".")";
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
	
	function addMultipleMarkerLive($map_id, $latarr,$lngarr,$datetimearr,$vehicle_serial_arr,$vehicle_name_arr,$speed_arr,$vehicle_number_arr,$io_str_arr,$vehilce_type_arr,$day_max_speed_arr,$day_max_speed_time_arr,$last_halt_time_arr,$vroute_arr,$vstatus_arr)
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
			$dayMaxSpeedJs=json_encode($day_max_speed_arr);
			$dayMaxSpeedTimeJs=json_encode($day_max_speed_time_arr);
			$lastHaltTimeJs=json_encode($last_halt_time_arr);
			$vRouteJs=json_encode($vroute_arr); 
			$vStatusJs=json_encode($vstatus_arr); 		
			//echo "vroute_arr".$vRouteJs."<br>";
			$marker .= "setMultipleMarkerLive({$map_id},{$latJs},{$lngJS},{$dateTimeJs},{$vSerialJs},{$vNameJs},{$vNumberJs},{$speedJs},{$ioStrJs},{$vehilceTypeJs},{$dayMaxSpeedJs},{$dayMaxSpeedTimeJs},{$lastHaltTimeJs},{$vRouteJs},{$vStatusJs}".")";
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

