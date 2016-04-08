<script type="text/javascript" src="src/js/extlargemapcontrol.js"></script>
<script type="text/javascript" src="src/js/calculate_distance.js"></script>
<script type="text/javascript" src="src/js/mapwindow_halt_functions.js"></script>
<script type="text/javascript" src="src/js/resources/map.js"></script>

<link rel="stylesheet" type="text/css" href="gm_minimap_in_infowindow_files/mapStyle.css">	
<link rel="stylesheet" href="div_mes_box1.css">	

<style type="text/css">
 @import url("http://www.google.com/uds/css/gsearch.css");
 @import url("http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css");
 }
</style>


<?php
  include_once("src/php/main_google_key.php");
?>

<script src="./src/js/labeledmarker.js"></script>

<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0" type="text/javascript"></script>
<script src="http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js" type="text/javascript"></script> 	

<script src="./src/js/dragzoom/gzoom.js" type="text/javascript"></script>
<script type="text/javascript" src="src/js/pdmarker.js"></script>


<script type="text/javascript" language="javascript">
//alert("halt map window JS");

function map_window(vname,arr_datetime,dept_datetime,lat,lng)
{
	//alert(vname+" "+datetime+" "+lat+" "+lng);	
	//test2(vname,datetime,lat,lng);			
	document.getElementById("window").style.display = '';
	load_halt_vehicle_on_map(vname,arr_datetime,dept_datetime,lat,lng);							
}
		
/*
var currentDate = new Date;

///////////////////// light vehicles icons ////////////////
var lvIcon1= new GIcon();
lvIcon1.image = 'light_v1.png';
lvIcon1.iconSize= new GSize(28, 25);
//baseIcon.shadowSize= newGSize(37, 34);
lvIcon1.iconAnchor= new GPoint(9, 34);
lvIcon1.infoWindowAnchor= new GPoint(5, 1);
//alert("K");
var lvIcon2= new GIcon();
lvIcon2.image = 'light_v2.png';
lvIcon1.iconSize= new GSize(28, 25);
//baseIcon.shadowSize= newGSize(37, 34);
lvIcon1.iconAnchor= new GPoint(9, 34);
lvIcon1.infoWindowAnchor= new GPoint(5, 1);

var lvIcon3= new GIcon();
lvIcon3.image = 'light_v3.png';
lvIcon3.iconSize= new GSize(28, 25);
//baseIcon.shadowSize= newGSize(37, 34);
lvIcon3.iconAnchor= new GPoint(9, 34);
lvIcon3.infoWindowAnchor= new GPoint(5, 1);

////// heavy vehicles icons //////////////////
var hvIcon1= new GIcon();
hvIcon1.image = 'heavy_v1.png';
hvIcon1.iconSize= new GSize(28, 25);
//baseIcon.shadowSize= newGSize(37, 34);
hvIcon1.iconAnchor= new GPoint(9, 34);
hvIcon1.infoWindowAnchor= new GPoint(5, 1);

var hvIcon2= new GIcon();
hvIcon2.image = 'heavy_v2.png';
hvIcon2.iconSize= new GSize(28, 25);
//baseIcon.shadowSize= newGSize(37, 34);
hvIcon2.iconAnchor= new GPoint(9, 34);
hvIcon2.infoWindowAnchor= new GPoint(5, 1);

var hvIcon3= new GIcon();
hvIcon3.image = 'heavy_v3.png';
hvIcon3.iconSize= new GSize(28, 25);
//baseIcon.shadowSize= newGSize(37, 34);
hvIcon3.iconAnchor= new GPoint(9, 34);
hvIcon3.infoWindowAnchor= new GPoint(5, 1);

////////////////////////////////////////////

var arrowIcon = new GIcon();
arrowIcon.iconSize = new GSize(24,24);
arrowIcon.iconAnchor = new GPoint(12,12);
arrowIcon.infoWindowAnchor = new GPoint(0,0);

var degreesPerRadian = 180.0 / Math.PI;

var lnmark= new GIcon();
lnmark.image = 'landmark.png';
lnmark.iconSize= new GSize(10, 10);
lnmark.iconAnchor= new GPoint(9, 34);
lnmark.infoWindowAnchor= new GPoint(5, 1);

var iconYellow = new GIcon();    
iconYellow.image = 'yellow_Marker1.png';
iconYellow.iconSize = new GSize(12, 20);
iconYellow.iconAnchor = new GPoint(6, 20);
iconYellow.infoWindowAnchor = new GPoint(5, 1);  

var iconRed = new GIcon(); 
iconRed.image = 'red_Marker1.png';
iconRed.iconSize = new GSize(12, 20);
iconRed.iconAnchor = new GPoint(6, 20);
iconRed.infoWindowAnchor = new GPoint(5, 1);

var iconGreen = new GIcon(); 
iconGreen.image = 'green_Marker1.png';
iconGreen.iconSize = new GSize(12, 20);
iconGreen.iconAnchor = new GPoint(6, 20);
iconGreen.infoWindowAnchor = new GPoint(5, 1);

var iconCurrent = new GIcon(); 
iconCurrent.image = 'current_Marker.png';
iconCurrent.iconSize = new GSize(17, 28);
iconCurrent.iconAnchor = new GPoint(8, 25);
iconCurrent.infoWindowAnchor = new GPoint(5, 1);

var add;
var map;
var geocoder = null;
var address = null;

var rg;
var point;
var lm;
lm=0;
var a;
//alert("lm="+lm);

var ge;
//google.load("maps", "2.x");
	

function initialize() {

  if (GBrowserIsCompatible()) {	
  
  		map = new GMap2(document.getElementById("map"));

		//alert(map);
		////////////////////////////////////////////////////////////////////////		
		//alert("earthstatus="+document.myform.GEarthStatus.value);
		var ms_coord1=document.forms[0].elements['ms_coord[]'];
		if(ms_coord1 || document.forms[0].Zone.value==1)
		{	
			show_milestones();
		}
		else
		{
			map.setCenter(new GLatLng(22.755920681486405, 78.2666015625), 5);
			map.enableContinuousZoom();
		}
			  
		if(document.myform.GEarthStatus.value == 1)
		{			
			/////////////////////
			var mapui = map.getDefaultUI();
			mapui.maptypes.physical = false;
			map.setUI(mapui);

			//map.setMapType(G_EARTH_3D_MAP);
			//map.setMapType(G_SATELLITE_3D_MAP);
			// add 'Earth' as one of the map types
			//map.addMapType(G_SATELLITE_3D_MAP); 
			
			map.removeMapType(G_SATELLITE_MAP);
			map.removeMapType(G_HYBRID_MAP);
			
			map.setMapType(G_SATELLITE_3D_MAP);
		//////////////////////
		}	
	
		else
		{
			////////////////////////////////////////////////////////////////////////			
			map.removeMapType(G_SATELLITE_MAP);
			//var mapTypeControl = new GMapTypeControl();
			//map.addControl(new GLargeMapControl());
			map.addControl(new GOverviewMapControl());	
			
			map.addMapType(G_SATELLITE_MAP);	
			//map.addMapType(G_SATELLITE_3D_MAP);
			var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10,10));
			
			var mapControl = new GMapTypeControl();
			map.addControl(mapControl, topRight);
				
			var opts2 = {
			  zoomInBtnTitle : "zoomIn",
			  zoomOutBtnTitle : "zoomOut",
			  moveNorthBtnTitle : "moveNorth",
			  moveSouthBtnTitle : "moveSouth",
			  moveEastBtnTitle : "moveEast",
			  moveWestBtnTitle : "moveWest",
			  homeBtnTitle : "home"
			};
			
			var extLargeMapControl2 = new ExtLargeMapControl(opts2);
			map.addControl(extLargeMapControl2); 
			
			//////// GET LATLNG  ////////////////////////////////
			GEvent.addListener(map,"click", function(overlay,point) {   			
														
				var ltlng;
				//alert("ltlng="+document.forms[0].latlng.checked);
				
				if(document.forms[0].latlng.checked == true)
				{
					ltlng = document.forms[0].latlng.value="show";		
				}
				else
				{
					ltlng = document.forms[0].latlng.value="";
				}
							
				if(ltlng)
				{
					var myHtml = "<font size='2' color='#000000'>The GPoint value is: " + map.fromLatLngToDivPixel(point)+"<br>"+point + "<br>"+ "<center>at zoom level " + map.getZoom()+"</font></center>";
					map.openInfoWindow(point, myHtml);
				}

			}); //GEvent.addListener closed 			
			///////////////////////////////////////////////////////
		
			//////////////////////////// CUSTOM DRAG ZOOM CODE /////////////////////////////////
			var boxStyleOpts1 = {
					  opacity: .2,
					  border: "2px solid red"
					}

			var otherOpts1 = {
					  buttonHTML: "<img src='src/dragzoom/zoom-button.gif' />",
					  buttonZoomingHTML: "<img src='src/dragzoom/zoom-button-activated.gif' />",
					  buttonStartingStyle: {width: '24px', height: '24px'}
					};

					var callbacks = {
					  buttonclick: function(){GLog.write("Looks like you activated DragZoom!")},
					  dragstart: function(){GLog.write("Started to Drag . . .")},
					  dragging: function(x1,y1,x2,y2){GLog.write("Dragging, currently x="+x2+",y="+y2)},
					  dragend: function(nw,ne,se,sw,nwpx,nepx,sepx,swpx){GLog.write("Zoom! NE="+ne+";SW="+sw)}
					};	

			map.addControl(new GScaleControl()) ; 
			//alert("before search");
			var search=map.addControl(new google.maps.LocalSearch(), new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(5,50)));
			var bottomLeft = new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(10,10));	
			map.addControl(new DragZoomControl(boxStyleOpts1, otherOpts1), new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(65,65)));	
			
			//map.addControl(new DragZoomControl(boxStyleOpts, otherOpts), new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(65,65)));
						
			///////////////////////////////////////////////////////////////////////
			
			GEvent.addListener(map, 'zoomend',function (oldzoomlevel,newzoomlevel) {

				var bounds = map.getBounds();
			});

			//checks if current browser is not firefox

			if(navigator.userAgent.indexOf("Firefox")==-1)
			{				
				GSearch.setOnLoadCallback(initialize);
			}	
		
		}  // else closed 
		///////////////////////////////////////////
										  
	} //GBrowserIs compatible closed      */

}  // function closed


	
</script>

