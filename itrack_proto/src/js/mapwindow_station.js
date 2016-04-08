
var station_icon= new GIcon();
station_icon.image = 'images/station.png';
station_icon.iconSize= new GSize(8, 8);
station_icon.iconAnchor= new GPoint(7, 30);
station_icon.infoWindowAnchor= new GPoint(5, 1);

function load_stations(customer,station_id,station_name,lat,lng)
{	
	//alert("load");
  //alert(customer+" "+station_name+" lt="+lat+" lng="+lng);	

	var point;	
	//alert("V0="+customer+" "+datetime+" "+lat+" "+lng);			
	map = new GMap2(document.getElementById("map"));	
	//alert("map:"+map);
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

	
	map.addControl(new GScaleControl());
	//alert("before search");
	//var search=map.addControl(new google.maps.LocalSearch(), new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(5,50)));
	////////////////////
	
	var bounds = new GLatLngBounds();	
	//alert("map="+map);
	//alert("5 "+customer+" "+station_name+" "+lat+" "+lng);	

	point = new GLatLng(parseFloat(lat),parseFloat(lng));

	bounds.extend(point); 
	
	//alert("point:"+point);
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
	
	//alert("V1="+customer+" "+station_name+" "+lat+" "+lng);

	marker = CreateVehicleMarkerStation(customer,station_id, station_name,lat,lng);	
	map.addOverlay(marker);

	var zoom;
	var event = 0;
	var newzoomlevel=0;		
	
} //function load1() closed


//////////////////////////////////////////////////////////////////////////////////////////////

var pt = new Array();
var lat1 = 0;
var lng1 = 0;
var lat2 = 0;
var lng2 = 0;
var coord;
var customer = new Array();
var mm;
var rect;	

function CreateVehicleMarkerStation(customer,station_id,station_name,lat,lng)
{	
	//alert("In Create marker:customer="+customer+" ,station_id="+station_id+" ,station_name="+station_name+" ,lat="+lat+" ,lng="+lng);
  var point = new GLatLng(parseFloat(lat),parseFloat(lng));    

	var marker = new GMarker(point, station_icon);	
	map.addOverlay(marker);

	PlotStationMarkerWithAddress(marker,point,station_id,station_name,customer);

	GEvent.addListener(marker, 'mouseover', function()
	{
		//alert("in LP add listener");				
		PlotStationMarkerWithAddress(marker,point,station_id,station_name,customer);
	});

	return marker;		
}				


function update_option(display_type)
{
  //alert("display_type="+display_type);
  if(display_type =="0")
  {
    document.getElementById('div_display').style.display='';
    document.getElementById('div_update').style.display='none';
    document.getElementById('div_delete').style.display='none';
  }
  else if(display_type == "1")
  {
    document.getElementById('div_update').style.display='';
    document.getElementById('div_display').style.display='none';
    document.getElementById('div_delete').style.display='none';
  }
  else if(display_type == "2")
  {
    document.getElementById('div_update').style.display='none';
    document.getElementById('div_display').style.display='none';
    document.getElementById('div_delete').style.display='';
  }     
}

//////////////////// PLOT LAST MARKERS WITH ADDRESSES ////////////////////////////////////////////////                                                      
function PlotStationMarkerWithAddress(marker,point,station_id,station_name,customer_no) 
{					
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		
	var myHtml = '<div style="height:150px;width:250px;"><div><select name="select_option" onchange="update_option(this.value);"><option value="0">Select Action</option><option value="1">Update</option><option value="2">Delete</option></select></div><br><div id="div_update" style="display:none;" class="bubble" align=left><table cellpadding=0 cellspacing=0 class="text" size="10"><tr><td><font size=1 color=#000000>station_name</font></td><td>&nbsp;:&nbsp;</td><td><input type="text" size="10" id="station_name" value="'+station_name+'"></td><td></td></tr><tr><td><font size=1 color=#000000>Customer No</font></td><td>&nbsp;:&nbsp;</td><td><input type="text" size="10" id="customer_no" value="'+customer_no+'">(digits only)</td></tr><tr><td colspan=4><br><font color=blue size=1>( '+point.lat()+', '+point.lng()+' )</font></td></tr></table><br><input type="button" value="Update Station" onclick="update_station('+station_id+',1)"/></div><div id="div_delete" style="display:none;" class="bubble" align=left><table cellpadding=0 cellspacing=0 class="text"><tr><td><font size=1 color=#000000>station_name</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+station_name + '</font></td><td></td></tr><tr><td><font size=1 color=#000000>Customer No</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+customer_no+' '+'&nbsp;&nbsp;</font></td></tr><tr><td colspan=5><br><font color=blue size=1>( '+point.lat()+', '+point.lng()+' )</font></td></tr></table><br><input type="button" value="Delete this station" onclick="update_station('+station_id+',2)"/></div><div id="div_display" class="bubble" align=left><table cellpadding=0 cellspacing=0 class="text"><tr><td><font size=1 color=#000000>station_name</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+station_name + '</font></td><td></td></tr><tr><td><font size=1 color=#000000>Customer No</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+customer_no+' '+'&nbsp;&nbsp;</font></td></tr><tr><td colspan=5><br><font color=blue size=1>( '+point.lat()+', '+point.lng()+' )</font></td></tr></table><br></div></div>';
	//var myHtml = '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0 class="text" ><tr><td><font size=1 color=#000000>station_name</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+station_name + '</font></td><td></td></tr><tr><td><font size=1 color=#000000>Customer No</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=1>'+customer+' '+'&nbsp;&nbsp;</font></td></tr><tr><td colspan=3><font color=blue size=1>( '+point.y+', '+point.x+' )</font></td></tr></table></div>';
	//alert("marker="+marker+" html="+myHtml);
	
  marker.openInfoWindow(myHtml);
}

function update_station(station_id, type)
{		
    //alert("type="+type);
    if(type == "1")
    {
      var station_name = document.getElementById('station_name').value;
      var customer_no = document.getElementById('customer_no').value;
      
      var strURL="src/php/ajax_update_station.php?station_id="+station_id+"&station_name="+station_name+"&customer_no="+customer_no+"&type=1";	
      //alert("s1="+strURL);  
  		var req = getXMLHTTP();
  		req.open("GET", strURL, false); //third parameter is set to false here
  		req.send(null);
  		var result = req.responseText;		// JavaScript Document
  		//alert(result);
      if(result=="success1")
  		{
        alert("Station updated successfully");    
        document.getElementById(station_id).innerHTML = "<font color=blue><strong>Updated</strong></font>";
      }
      else
      {
        alert("Unable to update Station");
      }    
    }
    else if(type == "2")
    {
      var strURL="src/php/ajax_update_station.php?station_id="+station_id+"&type=2";	
      //alert("s2="+strURL);    
  		var req = getXMLHTTP();
  		req.open("GET", strURL, false); //third parameter is set to false here
  		req.send(null);
  		var result = req.responseText;		// JavaScript Document
  		//alert(result);
      if(result=="success2")
  		{
        alert("Station deleted successfully");    
        document.getElementById(station_id).innerHTML = "<font color=red><strong>Deleted</strong></font>";
      }
      else
      {
        alert("Unable to delete Station");
      }
    } 
}