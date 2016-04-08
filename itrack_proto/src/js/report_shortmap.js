function load_vehicle_on_mini_map(vname,date1,date2,lat,lng,type)
{	
	//alert(vname+" "+arr_datetime+" lt="+lat+" lng="+lng);	
	//getxmlData_LP(vname,datetime,lat,lng);
	//document.getElementById('prepage').style.visibility='hidden';	

	var point;	
	//alert("V0="+vname+" "+datetime+" "+lat+" "+lng);		
	
	map = new GMap2(document.getElementById("map"));	
	
	// ADD CONTROLS ////		
	map.removeMapType(G_SATELLITE_MAP);
	//var mapTypeControl = new GMapTypeControl();
	//map.addControl(new GLargeMapControl());
	map.addControl(new GOverviewMapControl());	
	
	map.addMapType(G_SATELLITE_MAP);	
	//map.addMapType(G_SATELLITE_3D_MAP);
	var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10,10));
	
	var mapControl = new GMapTypeControl();
	map.addControl(mapControl, topRight);
	
	map.addControl(new GLargeMapControl());
		
	var opts2 = {
	  zoomInBtnTitle : "zoomIn",
	  zoomOutBtnTitle : "zoomOut",
	  moveNorthBtnTitle : "moveNorth",
	  moveSouthBtnTitle : "moveSouth",
	  moveEastBtnTitle : "moveEast",
	  moveWestBtnTitle : "moveWest",
	  homeBtnTitle : "home"
	};
	
	//var extLargeMapControl2 = new ExtLargeMapControl(opts2);
	//map.addControl(extLargeMapControl2); 
	
	/*else 
	{ 
	 if (browserName=="Microsoft Internet Explorer")
	 {
	  //alert("Hi, Explorer User!");
	 }
	}*/

	
	map.addControl(new GScaleControl());
	//alert("before search");
	//var search=map.addControl(new google.maps.LocalSearch(), new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(5,50)));

	////////////////////
	
	var bounds = new GLatLngBounds();
	
	//alert("map="+map);
	//alert("5 "+vname+" "+arr_datetime+" "+lat+" "+lng);	

	point = new GLatLng(parseFloat(lat),parseFloat(lng));

	bounds.extend(point); 
	
	var center = bounds.getCenter(); 					
	var zoom = map.getBoundsZoomLevel(bounds)-9; 
	//alert(center+":"+zoom);	
	map.setCenter(center,zoom);
			
	//////////////////////////////////////////////
		
	var j = 0;
	var colr = ["#00FF66","#0066FF","#FF0000","#33FFFF","#FF33CC","#9966FF","#FF9900","#FFFF00"];
	var point,marker;

	
	point = new GLatLng(parseFloat(lat),parseFloat(lng));
	//datetime = datetime_arr[i];		
	
	place=0;
	
	//alert("V1="+vname+" "+arr_datetime+" "+lat+" "+lng);

	marker = CreateVehicleMarker(vname,date1,date2,lat,lng,type);	
	map.addOverlay(marker);

	var zoom;
	var event = 0;
	var newzoomlevel=0;		
	
	/*getLandMark(event,newzoomlevel);
	
	////////////////////// CALL GET LANDMARK ON EVENT LISTENER FOR LAST POSITION //////////////////////////
	GEvent.addListener(map, 'zoomend',function (oldzoomlevel,newzoomlevel) 
	{
		var event =1;
		getLandMark(event,newzoomlevel);
	}); //GEvent addListener*/												

} //function load1() closed


//////////////////////////////////////////////////////////////////////////////////////////////

var pt = new Array();
var lat1 = 0;
var lng1 = 0;
var lat2 = 0;
var lng2 = 0;
var coord;
var vname = new Array();
var mm;
var rect;	

function CreateVehicleMarker(vname,date1,date2,lat,lng,type)
{	
	var point = new GLatLng(parseFloat(lat),parseFloat(lng));

	var lvIcon1= new GIcon();
	lvIcon1.image = 'images/light_v1.png';
	lvIcon1.iconSize= new GSize(28, 25);
	//baseIcon.shadowSize= newGSize(37, 34);
	lvIcon1.iconAnchor= new GPoint(9, 34);
	lvIcon1.infoWindowAnchor= new GPoint(5, 1);	


	var marker = new GMarker(point, lvIcon1);

	map.addOverlay(marker);

	PlotMarkerWithAddress(marker,point,vname,date1,date2,type);

	GEvent.addListener(marker, 'mouseover', function()
	{
		//alert("in LP add listener");				
		PlotMarkerWithAddress(marker,point,vname,date1,date2,type);
	});

	return marker;		
}				

function miniMapZoomEnd(oldZ,newZ) 
{
	showMinimapRect(this);
}

function miniMapMoveEnd() 
{
	showMinimapRect(this);
}

function showMinimapRect(detailMap,point) 
{
	if (rect)
	{
		map.removeOverlay(rect);
	}
	var bounds = detailMap.getBounds();
	var polyPoints = [	bounds.getSouthWest(),
						new GLatLng(bounds.getSouthWest().lat(),bounds.getNorthEast().lng()),
						bounds.getNorthEast(),
						new GLatLng(bounds.getNorthEast().lat(),bounds.getSouthWest().lng()),
						bounds.getSouthWest()
					]

	rect = new GPolygon(polyPoints, '#ff0000', 2, 1, '', 0.5);	
	map.addOverlay(rect);

}

function mapIWClose() 
{
	if (rect)
	{
		map.removeOverlay(rect);
	}
}

/////////// CODE FOR SELECTING LANDMARK ON ZOOM ///////////////////////////////////////

function getLandMark(event,newzoomlevel)
{
	var newzoomlevel= map.getZoom();	
	//alert("In landmark");
	
	GDownloadUrl("src/php/select_landmark.php", function(data) {
								
		var xml = GXml.parse(data);
		var lnmark_data = xml.documentElement.getElementsByTagName("marker");	
		//alert("landmark length="+lnmark_data.length);
		var i;
		var landmark;	
		var markerL;
		var zoomlevel;
		var point;	
																							
		for(i=0; i <lnmark_data.length; i++) 
		{									 					
			point = new GLatLng(parseFloat(lnmark_data[i].getAttribute("lat")),
			parseFloat(lnmark_data[i].getAttribute("lng")));				

			zoomlevel = lnmark_data[i].getAttribute("zoomlevel");					
			landmark = lnmark_data[i].getAttribute("landmark");
													
			//alert("zoomlevel="+zoomlevel+" , newzoomlevel="+newzoomlevel);
			if(zoomlevel == newzoomlevel || zoomlevel<newzoomlevel)
			{
				markerL = ShowMarker(point, landmark);	
				map.addOverlay(markerL);
			}
			
		}																																
	});	 // GDownload url closed		
}

//////////////////// PLOT LAST MARKERS WITH ADDRESSES ////////////////////////////////////////////////

function PlotMarkerWithAddress(marker,point,vehiclename,date1,date2,type) {

 var accuracy;
 var largest_accuracy;	   
 var delay = 100;

 //alert(point);
 
 var geocoder = new GClientGeocoder();

   geocoder.getLocations(point, function (result) {

	 if (result.Status.code == G_GEO_SUCCESS) // OR !=200
     {
		//alert("K");
		var j; 
	    //j=0;
       // Loop through the results, looking for the one with Accuracy = 1

	   for (var i=0; i<result.Placemark.length; i++)
       {
		    accuracy = result.Placemark[i].AddressDetails.Accuracy;

		   // alert(" accuracy="+accuracy+" i="+i);         
			if(i==0)
			{
				largest_accuracy = accuracy; 
				j = i;
			}

			else 
		    {	
			    //alert(" largest accuracy="+largest_accuracy+" accuracy="+accuracy+" i="+i);
				if(largest_accuracy < accuracy)
				{
					largest_accuracy = accuracy;
					//alert("i="+i);
					j = i;
					//alert("j1========="+j);
				}
			}
       }
	   //i=i-1;
	   
		// alert("j2="+j);
		//alert("LACC====="+largest_accuracy+ " j="+j+" result.len="+result.Placemark.length);

		var address = result.Placemark[j];
		address1 = address.address;	   
		//alert("addddddddd=="+address1);

		// setTimeout('wait()',10000);
		var google_point = new GLatLng(address.Point.coordinates[1],address.Point.coordinates[0]); 

		//Spliting the latitude 
		//alert("latlng.y======="+latlng.y+" latlng.x="+latlng.x);
		//alert("google_point.y======="+google_point.y+" google_point.x="+google_point.x);

		var distance = calculate_distance(point.lat(), google_point.lat(), point.lng(), google_point.lng()); 
		//alert("dist="+distance);
		var address2 = distance+" km from "+address1;		
		var place;
		
		///////////////////////////// SELECT LANDMARK OR GOOGLE PLACE CODE /////////////////////////////////////////////////////
		// IF DISTANCE CALCULATED THROUGH FILE IS LESS THAN 1 KM THEN DISPLAY LANDMARK OTHERWISE DISPLAY GOOGLE PLACE /////////
		
		var lt_original = point.lat();
		var lng_original = point.lng();
		var str = lt_original+","+lng_original;
		
		//var access2=document.myform.access.value;
			//alert('access='+str);

		//if(access2=="Zone")
		//{
			//var strURL="select_mining_landmark.php?content="+str;
		//}
		//else
		//{
			var strURL="src/php/select_landmark_marker.php?content="+str;
		//}

		var req = getXMLHTTP();
		req.open("GET", strURL, false); //third parameter is set to false here
		req.send(null);
		var landmark = req.responseText;
		
		//alert("landmark="+landmark);
		//return req.responseText;
		if(landmark!="")
			place = landmark;
		else
			place = address2;
			
		
	
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
		//alert(place);

		/*alert("[Original latlng="+latlng+"]   <br>  [Google PT="+point+"]   largest_accuracy"+largest_accuracy+"			address1="+address1);*/
		//alert("add before="+address1);

		//alert("Icon="+Icon+" map="+map+" marker="+marker+ " actionmrkr="+action_marker+"  vname="+vehiclename+" spd="+speed+" dt="+datetime+" dist="+dist+" fuelltr="+fuel_litres+" fuel_level="+fuel_level);
		var myHtml = '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0 class="text" ><tr><td><font size=1 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=1 color=#000000>Start Date</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+date1+' '+'&nbsp;&nbsp;</font></td></tr><tr><td><font size=1 color=#000000>End Date</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+date2+' '+'&nbsp;&nbsp;</font></td></tr><tr><td><font size=1 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=red size=1>'+place+'</font></td></tr><tr><td colspan=3><font color=blue size=1>( '+point.lat()+', '+point.lng()+' )</font></td></tr></table></div>';
		/*if(type=="halt")
      var myHtml = '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0 class="text" ><tr><td><font size=1 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=1 color=#000000>Start Date</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+date1+' '+'&nbsp;&nbsp;</font></td></tr><tr><td><font size=1 color=#000000>Departure DateTime</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+date2+' '+'&nbsp;&nbsp;</font></td></tr><tr><td><font size=1 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=red size=1>'+place+'</font></td></tr><tr><td colspan=3><font color=blue size=1>( '+point.lat()+', '+point.lng()+' )</font></td></tr></table></div>';
    else
      var myHtml = '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0 class="text" ><tr><td><font size=1 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=1 color=#000000>End Date</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+date1+' '+'&nbsp;&nbsp;</font></td></tr><tr><td><font size=1 color=#000000>DateTime2</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+date2+' '+'&nbsp;&nbsp;</font></td></tr><tr><td><font size=1 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=red size=1>'+place+'</font></td></tr><tr><td colspan=3><font color=blue size=1>( '+point.lat()+', '+point.lng()+' )</font></td></tr></table></div>';
	*/	
		//var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>DateTime</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td colspan=3><font color=blue size=2>( '+point.y+', '+point.x+' )</font></td></tr></table></div>');

		//var html = new GInfoWindowTab("Info", '<div id="tab1" class="bubble">Click the "Location" tab to see the minimap</div>');
		//var tab2 = new GInfoWindowTab("Location", '<div id="detailmap" style="height:50px;"></div>');

		//alert(" tab1="+tab1+" tab2="+tab2);
		//var infoTabs = [tab1,tab2];

		//alert(" marker="+marker+" infoTabs="+infoTabs);
		//marker.openInfoWindowTabsHtml(infoTabs);
		
		marker.openInfoWindow(myHtml);

		/*var dMapDiv = document.getElementById("detailmap");
		var detailMap = new GMap2(dMapDiv);
		detailMap.setCenter(point , 12);

		detailMap.removeMapType(G_SATELLITE_MAP);																

		var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(0,0));	
		detailMap.addMapType(G_SATELLITE_MAP);
		var mapControl = new GMapTypeControl();
		detailMap.addControl(mapControl, topRight);

		var topLeft = new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(5,35));
		var mapControl2 = new GSmallMapControl();
		//detailMap.addControl(new GSmallMapControl());
		GEvent.addListener(detailMap, "zoomend", miniMapZoomEnd);
		GEvent.addListener(detailMap, "moveend", miniMapMoveEnd);
		detailMap.addControl(mapControl2, topLeft);

		var CopyrightDiv = dMapDiv.firstChild.nextSibling;
		var CopyrightImg = dMapDiv.firstChild.nextSibling.nextSibling;
		CopyrightDiv.style.display = "none"; 
		CopyrightImg.style.display = "none";
		var marker3 = new GMarker(point,Icon);
		//alert("point ="+point+" mrk3="+marker3);
		detailMap.addOverlay(marker3);

		showMinimapRect(detailMap,marker3);*/
     }
  });
}

//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
/*
function calculate_distance(lat1, lat2, lon1, lon2) 
{
	//alert("in calculate mileage"+lat1+" lat2="+lat2+" lon1="+lon1+" lon2="+lon2);	
	// deg to rad
	lat1 = (lat1/180)*Math.PI;
	lon1 = (lon1/180)*Math.PI;
	lat2 = (lat2/180)*Math.PI;
	lon2 = (lon2/180)*Math.PI;
	//alert("in calculate mileage"+lat1+" lat2="+lat2+" lon1="+lon1+" lon2="+lon2);
	// Find the deltas
	var delta_lat = lat2 - lat1;
	var delta_lon = lon2 - lon1;
	//alert("delta_lat="+delta_lat+"delta_lon="+delta_lon);
	// Find the Great Circle distance
	var temp = Math.pow(Math.sin(delta_lat/2.0),2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(delta_lon/2.0),2);	
	//alert("temp="+temp);
	var distance = 3956 * 2 * Math.atan2(Math.sqrt(temp),Math.sqrt(1-temp));
	//alert("dist="+distance);
	//convert into km
	distance = distance*1.609344;
	distance=Math.round(distance*100)/100;
	//alert("dist="+distance);
	return distance;
} */


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



/////////// CODE FOR SELECTING LANDMARK ON ZOOM ///////////////////////////////////////
/*
function getLandMark(event,newzoomlevel)
{
	var newzoomlevel= map.getZoom();	
			
	GDownloadUrl("select_landmark.php", function(data) {
								
		var xml = GXml.parse(data);
		var lnmark_data = xml.documentElement.getElementsByTagName("marker");	
		//alert("landmark length="+lnmark_data.length);
		var i;
		var landmark;	
		var markerL;
		var zoomlevel;
		var point;	
																							
		for(i=0; i <lnmark_data.length; i++) 
		{									 					
			point = new GLatLng(parseFloat(lnmark_data[i].getAttribute("lat")),
			parseFloat(lnmark_data[i].getAttribute("lng")));				

			zoomlevel = lnmark_data[i].getAttribute("zoomlevel");					
			landmark = lnmark_data[i].getAttribute("landmark");
													
			//alert("zoomlevel="+zoomlevel+" , newzoomlevel="+newzoomlevel);
			if(zoomlevel == newzoomlevel || zoomlevel<newzoomlevel)
			{
				markerL = ShowMarker(point, landmark);	
				map.addOverlay(markerL);
			}
			
		}																																
	});	 // GDownload url closed		
}
*/

function ShowMarker(point, landmark) 
{
	var Icon= new GIcon(lnmark);	
	var marker = new GMarker(point,Icon);
	var marker2 = new GMarker(point,Icon);
	var lat = Math.round((point.y)*100000)/100000;
	var lng = Math.round((point.x)*100000)/100000;

	var iwform = '<table bgcolor="#EEEFF0" border="0"><tr><td><table border="0" cellpadding=1 cellspacing=0><tr><td>&nbsp;</td></tr><tr><td><font size=3 color=#000000><b>LANDMARK</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=red size=3><b>'+landmark + '</b></font></td><td></td></tr><tr><td>&nbsp;</td></tr><tr><td><font size=2 color=#000000><b>Latitude</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lat+'</font></td></tr><tr><td></td></tr><tr><td><font size=2 color=#000000><b>Longitude</b></font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lng+'</font></td></tr></table></td></tr></table>';

	GEvent.addListener(marker, "mouseover", function() 
	{
		var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><font color=#000000'+iwform+'</font></div>');

		//var html = new GInfoWindowTab("Info", '<div id="tab1" class="bubble">Click the "Location" tab to see the minimap</div>');
		var tab2 = new GInfoWindowTab("Location", '<div id="detailmap" style="height:150px;"></div>');
		var infoTabs = [tab1,tab2];
		marker.openInfoWindowTabsHtml(infoTabs);	
		var dMapDiv = document.getElementById("detailmap");
		var detailMap = new GMap2(dMapDiv);
		detailMap.setCenter(point , 12);		
		detailMap.removeMapType(G_SATELLITE_MAP);
		var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(0,0));	
		detailMap.addMapType(G_SATELLITE_MAP);
		var mapControl = new GMapTypeControl();
		detailMap.addControl(mapControl, topRight);		
		var topLeft = new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(5,30));
		var mapControl2 = new GSmallMapControl();
		//detailMap.addControl(new GSmallMapControl());
		GEvent.addListener(detailMap, "zoomend", miniMapZoomEnd);
		GEvent.addListener(detailMap, "moveend", miniMapMoveEnd);
		detailMap.addControl(mapControl2, topLeft);
		var CopyrightDiv = dMapDiv.firstChild.nextSibling;
		var CopyrightImg = dMapDiv.firstChild.nextSibling.nextSibling;
		CopyrightDiv.style.display = "none"; 
		CopyrightImg.style.display = "none";
		detailMap.addOverlay(marker2);	
		showMinimapRect(detailMap,point);
	});    
	GEvent.addListener(map, "click", function(overlay,point) 
	{
		if (!overlay) 
		{
		  ew.hide();
		}
	});
	//alert("marker="+marker);
	return marker;
}

