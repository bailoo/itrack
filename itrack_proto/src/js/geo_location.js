
function initialize() 
{
	alert("abc");
	document.getElementById("map").style.display="";
	if (GBrowserIsCompatible())
	{	  
		map = new GMap2(document.getElementById("map"));		
	
		var mining_test=document.getElementById("category").value;
		//alert("test"+mining_test);
		if(mining_test=='5' || (document.getElementById("mining_user").value==5))
		{show_milestones();}
		else /////// for other users
		{
			map.setCenter(new GLatLng(22.755920681486405, 78.2666015625), 5);
			map.enableContinuousZoom();
		}			  
		if(document.thisform.GEarthStatus.value == 1) //////////for google earth
		{
			var mapui = map.getDefaultUI();
			mapui.maptypes.physical = false;
			map.setUI(mapui);			
			map.removeMapType(G_SATELLITE_MAP);
			map.removeMapType(G_HYBRID_MAP);			
			map.setMapType(G_SATELLITE_3D_MAP);
		}	
		else ///////// for other format
		{		
			map.removeMapType(G_SATELLITE_MAP);			
			map.addControl(new GOverviewMapControl());			
			map.addMapType(G_SATELLITE_MAP);	
			//map.addMapType(G_SATELLITE_3D_MAP);
			var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10,10));			
			var mapControl = new GMapTypeControl();
			map.addControl(mapControl, topRight);				
			var opts2 =
			{
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

			var boxStyleOpts1 = 
			{
				opacity: .2,
				border: "2px solid red"
			}

			var otherOpts1 = 
			{
				buttonHTML: "<img src='src/dragzoom/zoom-button.gif' />",
				buttonZoomingHTML: "<img src='src/dragzoom/zoom-button-activated.gif' />",
				buttonStartingStyle: {width: '24px', height: '24px'}
			};

			var callbacks =
			{
				buttonclick: function(){GLog.write("Looks like you activated DragZoom!")},
				dragstart: function(){GLog.write("Started to Drag . . .")},
				dragging: function(x1,y1,x2,y2){GLog.write("Dragging, currently x="+x2+",y="+y2)},
				dragend: function(nw,ne,se,sw,nwpx,nepx,sepx,swpx){GLog.write("Zoom! NE="+ne+";SW="+sw)}
			};	

			map.addControl(new GScaleControl()) ; 
			//alert("before search");
			//var search=map.addControl(new google.maps.LocalSearch(), new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(5,50)));
			var bottomLeft = new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(10,10));	
			map.addControl(new DragZoomControl(boxStyleOpts1, otherOpts1), new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(65,65)));	
			
			//map.addControl(new DragZoomControl(boxStyleOpts, otherOpts), new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(65,65)));
			GEvent.addListener(map, 'zoomend',function (oldzoomlevel,newzoomlevel) 
			{
				var bounds = map.getBounds();
			});		

			if(navigator.userAgent.indexOf("Firefox")==-1)
			{				
				GSearch.setOnLoadCallback(initialize);
			}	
		
		} 										  
	}
} 