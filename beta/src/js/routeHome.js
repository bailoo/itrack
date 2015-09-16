function switch_vehicle_selection(disp_value)
{
  //alert("dvalue="+disp_value);  
  var imeino1 = document.thisform.elements['vehicleserial[]'];
      
  if(imeino1.length!=undefined)
  {
  	//alert("len="+imeino1.length);  	
    for(var i=0;i<imeino1.length;i++)
  	{          
      if(i==0)
      {
        var vchecktmp = "vcheckbox";
        var vradiotmp = "vradio";        
      }
      else
      {
        var vchecktmp = "vcheckbox"+i;
        var vradiotmp = "vradio"+i;
      }
      
      //alert(vchecktmp+" ,"+vradiotmp+" disp_value="+disp_value);
      if(disp_value == 1)
      {
        //alert("1");        
        document.getElementById('all').style.display='';
        document.getElementById(vchecktmp).style.display='';
        document.getElementById(vradiotmp).style.display='none';
		document.getElementById('google_play').style.display='none';
      }
      else if(disp_value == 2)
      {
        //alert("2");
        document.getElementById('all').style.display='none';
        document.getElementById(vradiotmp).style.display='';
        document.getElementById(vchecktmp).style.display='none';
		document.getElementById('google_play').style.display='';
      }
    }
  }
  else
  {
    if(disp_value == 1)
    {
      //alert("1:"+document.getElementById("vcheckbox").display);
      
      document.getElementById("vcheckbox").style.display='';
      document.getElementById("vradio").style.display='none';
    }
    else if(disp_value == 2)
    {
      //alert("2"+document.getElementById("vradio").display);
      document.getElementById("vradio").style.display='';
      document.getElementById("vcheckbox").style.display='none';
    }    
  }  
}

/*
	function home_select_by_entity(options)
	{
		var poststr = "display_type1=" + encodeURI(options);
		makePOSTRequest('src/php/home_entity_selection_information.php', poststr);
	}*/
function showMERoutes(value)
{
    if(value=="select")
	{
		alert("Please select one route");
		document.getElementById('morningRoute').style.display='none';
		document.getElementById('morningEvening').style.display='none';
                return false;
	}
	else if(value=="2")
	{
		var obj=document.thisform.elements['morningArr[]'];
		if(obj.length!=undefined)
		{
			for (var i=0;i<obj.length;i++)
			{
				if(obj[i].checked==true)
				{
					obj[i].checked=false;
									}	  
			}
		}
		else
		{				//alert("In else");
			if(obj.checked==true)
			{
				obj.checked=false;
			}
		}
		document.getElementById('morningRoute').style.display='';
		document.getElementById('morningEvening').style.display='none';
	}
	else if(value=="3")
	{
		var obj=document.thisform.elements['eveningArr[]'];
		if(obj.length!=undefined)
		{
			for (var i=0;i<obj.length;i++)
			{
				if(obj[i].checked==true)
				{
					obj[i].checked=false;
									}	  
			}
		}
		else
		{				//alert("In else");
			if(obj.checked==true)
			{
				obj.checked=false;
			}
		}
		document.getElementById('morningEvening').style.display='';
		document.getElementById('morningRoute').style.display='none';
	}
        
}
function initialize() 
{
  //alert("abc");
	//document.getElementById("map").style.display="";
	if (GBrowserIsCompatible())
	{	  
		map = new GMap2(document.getElementById("map"));		
	
		var mining_test=document.getElementById("category").value;
		//alert("test"+mining_test);
		if(mining_test=='5' || (document.getElementById("mining_user").value==5))
		{
			show_milestones();
		}
		else /////// for other users
		{
				map.setCenter(new GLatLng(22.755920681486405, 78.2666015625), 5);
				map.enableContinuousZoom();
			/*if(document.thisform.geofence_feature.checked==true)
			{
				show_geofence();
			}
			else
			{
				map.setCenter(new GLatLng(22.755920681486405, 78.2666015625), 5);
				map.enableContinuousZoom();
			}*/
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
function show_geofence(map)
{
    //alert("test");
	var req = getXMLHTTP();
	req.open("GET", "src/php/getGeofencePoints.php", false); //third parameter is set to false here
	req.send(null);
        //alert("response="+responseText);
	var data = req.responseText;
	var xml = xmlParse(data);		
	if(data!="Geofence Not Found")
	{
		var geo_point_data = xml.documentElement.getElementsByTagName("marker");
		var i;	
		var bounds_global = new google.maps.LatLngBounds();		
		var point_global;
                var infowindow;
		for(i=0; i<geo_point_data.length; i++) 
		{
                    if (infowindow) infowindow.close();
                    infowindow = new google.maps.InfoWindow();
			var polygon = new Array();		
			var p = 0;	
			var geo_name = geo_point_data[i].getAttribute("geo_name");
                        //alert("geoName1="+geo_name);
			var geo_points = geo_point_data[i].getAttribute("points");
			var geo_points1=geo_points.split(',');	
			var bounds = new google.maps.LatLngBounds();	
			var point;			
			for(var j=0;j<geo_points1.length;j++)
			{
				if(j==0)
				{
					//alert("in if"+i);
					var coord_global = geo_points1[j].split(" ");	
					//alert("coord="+coord);
					point_global = new google.maps.LatLng(parseFloat(coord_global[0]),parseFloat(coord_global[1]));		//alert("point="+point);
					bounds_global.extend(point_global);
				}
					
				var coord = geo_points1[j].split(" ");	
				//alert("coord="+coord);
				point = new google.maps.LatLng(parseFloat(coord[0]),parseFloat(coord[1]));		//alert("point="+point);
				bounds.extend(point);	
			}			
			var center = bounds.getCenter();	
			//var zoom = map.getBoundsZoomLevel(bounds)-5;	//map.setCenter(center,zoom);				
			var temp_center="";		
			temp_center=temp_center+"0"+center+"0";		
			var center2 = temp_center.split("(");	
			var center3 = center2[1].split(")");				
			var center4 = center3[0].split(",");	
			//var latlng = new GLatLng(center4[0], center4[1]);	
			var latlng = new google.maps.LatLng(center4[0], center4[1]);				
			var marker = new MarkerWithLabel({
			position: latlng,
			draggable: true,							
			map:map,
			icon: {path: google.maps.SymbolPath.CIRCLE,},
			labelContent: '<table><tr><td><font color="green" size=2><b>'+geo_name+'</b></font></td></tr></table>',
			labelAnchor: new google.maps.Point(-2, 12)
			});
			markers.push(marker);
			var point1;
			var bounds1 = new google.maps.LatLngBounds();				
			var geo_points2=geo_points.split(',');
			var lastpt = geo_points2.length-1;
			var pts = new Array();
                        var addListenersOnPolygon = function(polygon) {
  google.maps.event.addListener(polygon,'click', function(event) {
           // alert("geoName="+geofencePolygon.geoName);
           
           contentStr='<table>'+
						'<tr>'+
						'<td class=\"live_td_css1\">Geofence Name</td>'+
						'<td>:</td>'+
						'<td class=\"live_td_css2\">'+polygon.geoName+'</td>'+
					   '</tr></table>';
          infowindow.setContent(contentStr);
          if (event) {
             point = event.latLng;
          }
          infowindow.setPosition(point);
          infowindow.open(map);
          // map.openInfoWindowHtml(point,html); 
        }); 
                        }
                        
				
			for(var j=0;j<geo_points2.length;j++)
			{
				var points = geo_points2[j].split(" ");				
				pts[j] = new google.maps.LatLng(parseFloat(points[0]),parseFloat(points[1]));				
				if(j == lastpt)
				{
					j++;
					points = geo_points2[0].split(" ");											
					pts[j] = new google.maps.LatLng(parseFloat(points[0]),parseFloat(points[1]));
					var geofencePolygon = new google.maps.Polygon({
					paths: pts,
					strokeColor: '#FF0000',
					strokeOpacity: 0.8,
					strokeWeight: 2,
					fillColor: '#FF0000',
					fillOpacity: 0.0,
                                        geoName:geo_name
					});
					markers.push(geofencePolygon);
					geofencePolygon.setMap(map);
                                        addListenersOnPolygon(geofencePolygon);
					p++;	
				}	// if closed
			}
		}			
	}
}

function xmlParse(str) 
{
	if (typeof ActiveXObject != 'undefined' && typeof GetObject != 'undefined') 
	{
		var doc = new ActiveXObject('Microsoft.XMLDOM');
		doc.loadXML(str);
		return doc;
	}

	if (typeof DOMParser != 'undefined') 
	{
		return (new DOMParser()).parseFromString(str, 'text/xml');
	}
	return createElement('div', null);
}
function show_milestones(map)
{
	GDownloadUrl('src/php/getMiningGroupMap.php', function(data)
	{								
		var xml = GXml.parse(data);		
		var ms_data = xml.documentElement.getElementsByTagName('marker');			
		var i;	
		var bounds_global = new google.maps.LatLngBounds();						
		var point_global;																				
		for(i=0; i<ms_data.length; i++) 
		{	
			var polygon = new Array();		
			var p = 0;	
			//alert('i(a)='+i+'len='+ms_data.length);
			var ms_coord = ms_data[i].getAttribute('points');	
			var msname = ms_data[i].getAttribute('msname');		
			var mstype = ms_data[i].getAttribute('mstype');
			var ms_coord = ms_data[i].getAttribute('points');	
			//alert('ms_coord='+ms_coord);
			var ms_coord1=ms_coord.split(',');	
			var bounds = new google.maps.LatLngBounds();	
			var point;			
			for(var j=0;j<ms_coord1.length;j++)
			{
				if(j==0)
				{
					//alert('in if'+i);
					var coord_global = ms_coord1[j].split(' ');	
					//alert('coord='+coord);
					point_global = new google.maps.LatLng(parseFloat(coord_global[0]),parseFloat(coord_global[1]));		
					//alert('point_global='+point_global);
					bounds_global.extend(point_global);
				}
				
				var coord = ms_coord1[j].split(' ');	
				//alert('coord='+coord);
				point = new google.maps.LatLng(parseFloat(coord[0]),parseFloat(coord[1]));		
				//alert('point='+point);
				//bounds_global.extend(point);
				bounds.extend(point);				
			}
			//alert('value='+document.getElementById('vehicle_milstone').value);
			if(document.getElementById('vehicle_milstone').value!='vehicle_zoom')
			{
				//alert('in');
				if(i==(ms_data.length-1))
				{									
					map.setCenter(bounds_global.getCenter());
					map.setZoom(10);
					//map_canvas.setCenter(center_global,zoom_global);	
				}	
			}
			var center = bounds.getCenter();										
			var temp_center='';		
			temp_center=temp_center+'0'+center+'0';		
			var center2 = temp_center.split('(');	
			var center3 = center2[1].split(')');				
			var center4 = center3[0].split(',');	
			var latlng = new google.maps.LatLng(center4[0], center4[1]);							
		
			if(mstype=='BS')
			{
				var marker = new MarkerWithLabel({
			   position: latlng,
			   draggable: true,							
			   map:map,
			   icon: {path: google.maps.SymbolPath.CIRCLE,},
			   labelContent: '<table><tr><td><font color="green" size=2><b>'+msname+'</b></font></td></tr></table>',
			   labelAnchor: new google.maps.Point(-2, 12)
			 });
				
			}
			else if(mstype=='OS')
			{
				var marker = new MarkerWithLabel({
			   position: latlng,
			   draggable: true,							 
				map:map,
			   icon: {path: google.maps.SymbolPath.CIRCLE,},
			   labelContent: '<table><tr><td><font color="darkyellow" size=2><b>'+msname+'</b></font></td></tr></table>',
			   labelAnchor: new google.maps.Point(-2, 12)
			 });								
			}	
			markers.push(marker);
			
			var point1;
			var bounds1 = new GLatLngBounds();				
			var ms_coord1=ms_coord.split(',');
			var lastpt = ms_coord1.length-1;
			var pts = new Array();
			for(var j=0;j<ms_coord1.length;j++)
			{
				var points = ms_coord1[j].split(' ');			
				pts[j] = new google.maps.LatLng(parseFloat(points[0]),parseFloat(points[1]));
				if(j == lastpt)
				{
					j++;
					points = ms_coord1[0].split(' ');											
					pts[j] = new google.maps.LatLng(parseFloat(points[0]),parseFloat(points[1]));
					  var bermudaTriangle = new google.maps.Polygon({
						paths: pts,
						strokeColor: '#FF0000',
						strokeOpacity: 0.2,
						strokeWeight: 1,
						fillColor: '#FF0000',
						fillOpacity: 0.10
					  });
					  markers.push(bermudaTriangle);
					bermudaTriangle.setMap(map);														
					p++;	
				}	// if closed
			}								
		}																																
	});	 // GDownload url closed	
}

var latLngmarkers=new Array();
var listenerHandle='';
 function show_latlng() 
 {
	deleteLatLngOverlays();	
	if(document.forms[0].latlng.checked == true)
	{ 
		listenerHandle = google.maps.event.addListener(map_canvas, 'click', function(event) 
		{
			deleteLatLngOverlays();
			if (infowindow) infowindow.close();
			infowindow = new google.maps.InfoWindow();
			var localmarker = new google.maps.Marker({position: event.latLng, map: map_canvas, icon: {path: google.maps.SymbolPath.CIRCLE,}});
			var contentString='<table>'+
									'<tr>'+
										'<td class=live_td_css1>Lat/Long</td>'+
										'<td>:</td>'+
										'<td class=live_td_css2>'+event.latLng+'</td>'+
									'</tr>'+
								'</table>';	
			latLngmarkers.push(localmarker);
			infowindow.setContent(contentString);
			infowindow.open(map_canvas, localmarker);
		});
	}
	else
	{
		//alert("test");
		google.maps.event.removeListener(listenerHandle);
	}
	
	function deleteLatLngOverlays() 
	{
		for (var i = 0; i < latLngmarkers.length; i++) 
		{
			latLngmarkers[i].setMap(null);
		}
	}	
 }	
  
/*function show_report()
{
  document.getElementById("map").style.display="none";
  document.getElementById("text").style.display="";
  var xmlHttp = getXMLHttp();  
  xmlHttp.onreadystatechange = function()
  {
    if(xmlHttp.readyState == 4)
    {
      show_report_1(xmlHttp.responseText);
    }
  }
  xmlHttp.open("GET", "src/php/module_report_example.php", true);
  xmlHttp.send(null);
}
	
function show_report_1(responce)
{
  	document.getElementById('text').innerHTML=responce;
}*/


/*function show_user(user_account)
{
  //alert(document.getElementById("user_accountid").options[0].text)
  var w = document.thisform.user_accountid.selectedIndex;
  alert("w="+w)
  var selected_text = document.thisform.user_accountid.options[w].text;
  alert("sel="+selected_text);
  
  
  if(user_account!="select")
  {
    var xmlHttp = getXMLHttp();
   
    xmlHttp.onreadystatechange = function()
    {
      if(xmlHttp.readyState == 4)
      {
        show_user_1(xmlHttp.responseText);
      }
    }
  
    xmlHttp.open("GET", "src/php/module_user.php?user_account_1="+user_account, true);
    xmlHttp.send(null);
  }
  else
  {
    alert("Please select super user");
  }
}

	function show_user_1(response)
	{  
		remOption(document.getElementById("user_accountid"));
		var showdata = response;   					
		var strar = showdata.split(":");

		if(strar.length==1)
		{
			alert("No User Found For This Super User");
			document.getElementById("gid").focus();			
			remOption(document.getElementById("user_accountid"));
			//addOption(document.getElementById("user_accountid"), "admin","admin");
		}
		else if(strar.length>1)
		{									
			var j=0;
			for( var i=1;i<strar.length;i++)
			{
			j=i+1;							
			addOption(document.getElementById("user_accountid"), strar[j], strar[i]);
			i++;
			}
		}		
	}

	/////////////////////////////   SHOW GROUPS /////////////////////////////////

	function show_grp(grp_account)
	{
		var w = document.thisform.grp_accountid.selectedIndex;
		alert("w="+w)
		var selected_text = document.thisform.grp_accountid.options[w].text;
		alert("sel="+selected_text);

		if(grp_account!="select")
		{
			var xmlHttp = getXMLHttp();
			xmlHttp.onreadystatechange = function()
			{
				if(xmlHttp.readyState == 4)
				{
				show_grp_1(xmlHttp.responseText);
				}
			}  
			xmlHttp.open("GET", "src/php/module_grp.php?grp_account_1="+grp_account, true);
			xmlHttp.send(null);
		}
		else
		{
			alert("Please select user");
		}
	}

	function show_grp_1(response)
	{
		remOption(document.getElementById("grp_accountid"));
		//addOption(document.getElementById("grp_accountid"), "select","select");
		var showdata = response; 
		//alert(showdata);  					
		var strar = showdata.split(":");

		if(strar.length==1)
		{
			alert("No Group Found For This User");

			document.getElementById("grp_accountid").focus();			
			remOption(document.getElementById("grp_accountid"));
			//addOption(document.getElementById("grp_accountid"), "admin","admin");
		}
		else if(strar.length>1)
		{									
			var j=0;
			for( var i=1;i<strar.length;i++)
			{
			j=i+1;							
			addOption(document.getElementById("grp_accountid"), strar[j], strar[i]);
			i++;
			}
		}		
	}

	/////////////////////////////////////////////////////////////////////////////*/

	/*function addOption(selectbox,text,value)
	{
		var optn = document.createElement("OPTION");
		optn.text = text;
		optn.value = value; 
		selectbox.options.add(optn);
	}		

	function remOption(selectbox)
	{			
		var i;
		for(i=selectbox.options.length-1;i>=0;i--)
		{
			selectbox.remove(i);
		}
	}*/


	function show_main_home_vehicle(display_type)
	{
		//alert("in show main home");
		//alert("main home vehicle"+display_type);    
    document.getElementById("vehicle_milstone").value='';
		
    if(display_type=="default")
		{
      //alert("default");
      /*var display_mode=document.thisform.mode;
      for(var i=0;i<display_mode.length;i++)
      {    
        if(i==0)
        {
          display_mode[i].checked = true;
        }
      }*/ 			
      document.getElementById("category").value=document.getElementById("default_category").value;
		}
		//initialize();
		
		if(document.getElementById("user_type_option").value!="select")
		{
			if(display_type=="default" || document.getElementById("user_type_option").value=="all" || display_type=="single")
			{
				if(display_type=="default" && display_type!="single")
				{
					var category="&category="+document.getElementById("default_category").value;
					//alert("category="+category);
				}
				else if(document.getElementById("user_type_option").value=="all")
				{
					if(display_type=="single")
					{
						display_type="single";
					}
					else
					{
						display_type="default";
					}
					var category="&category=" + document.getElementById('category').value;
					//alert("category="+category);
				}
				var poststr = "display_type1=" + display_type+category;
				//alert("poststr="+poststr);
				makePOSTRequest('src/php/module_main_home_vehicle_chk.php', poststr);
			}	
			else
			{				
				var poststr = "display_type1=" + encodeURI(display_type)+
							   "&category="+document.getElementById("category").value;
				//alert("poststr="+poststr);
				makePOSTRequest('src/php/module_selection_information.php', poststr);	
			}
		}	
	}

	function show_vehicle_display_option()
	{
		/*var vehicle_details_option1=document.thisform.vehicle_details_option;
		document.getElementById("all_vehicle_1").style.display="none"; 		
		for(var i=0;i<vehicle_details_option1.length;i++)
		{			
			vehicle_details_option1[i].checked=false;
		}*/	
		//document.getElementById("selection_information").style.display="none";
		document.getElementById("blackout_1").style.visibility = "visible";
		document.getElementById("divpopup_1").style.visibility = "visible";
		document.getElementById("blackout_1").style.display = "block";
		document.getElementById("divpopup_1").style.display = "block"; 
	}

	function display_vehicle_according_divoption(obj)
	{ 
		var div_option_values=tree_validation(obj);
		if(div_option_values!=false)
		{	
			document.getElementById('vehicleloadmessage').style.display ="";
			document.getElementById('vehicleloadmessage1').style.display ="none";
			close_vehicle_display_option();
			var common_div_option1=document.getElementById('common_div_option').value;
			var poststr = "common_div_option1=" + encodeURI(common_div_option1) +
			"&div_option_values1=" + encodeURI(div_option_values)+	
			"&user_type_option1=" + document.getElementById('user_type_option').value+
			"&category=" + document.getElementById('category').value;
			remOption(document.getElementById("user_type_option"));
			addOption(document.getElementById("user_type_option"),'Select','select'); 
			addOption(document.getElementById("user_type_option"),'All','all'); 
			addOption(document.getElementById("user_type_option"),'By Group','group'); 
			addOption(document.getElementById("user_type_option"),'By User','user');
			addOption(document.getElementById("user_type_option"),'By Vehicle Tag','vehicle_tag'); 
			addOption(document.getElementById("user_type_option"),'By Vehicle Type','vehicle_type'); 
			//addOption(document.getElementById("user_type_option"),'By Vehicle','vehicle'); 
		}
		//alert("post_str="+poststr);
		makePOSTRequest('src/php/module_main_home_vehicle_chk.php', poststr);
	}
	/*function show_current_last_data()
	{
		imei_iotype_arr.length=0;	   		
		var display_mode=document.thisform.mode;   /////// 1=last_postoin  and 2=track 		
		var imeino1;
		var vid;
		var text_report_io_element="";
		vid = "";	
		for(i=0;i<display_mode.length;i++)
		{
			if(display_mode[i].checked)
			{
				var mode=display_mode[i].value;
				if(mode == 1)
				{  
					imeino1=document.thisform.elements['vehicleserial[]'];
				}
				else if(mode == 2)
				{
					imeino1=document.thisform.vehicleserial_radio; 
				}
			}
		}
		var num1=0; 
		if(mode==1)               // LAST POSITION
		{
			if(imeino1.length!=undefined)
			{
				for(i=0;i<imeino1.length;i++)
				{
					if(imeino1[i].checked)
					{						
						var value_tmp=imeino1[i].value;
						var vid_local=value_tmp.split("*");
						if(vid_local[1]!="tmp_str")
						{
							imei_iotype_arr[vid_local[0]]=vid_local[1];				
						}
						text_report_io_element=text_report_io_element+vid_local[1]+",";	
						//alert("text_report_io_element="+text_report_io_element+"vid_local="+vid_local[1]);
						vid=vid+vid_local[0]+",";
						num1 = 1;
					}
				}
			}
			else
			{
				if(imeino1.checked)
				{
					var value_tmp=imeino1.value;					
					var vid_local=value_tmp.split("*");
					if(vid_local[1]!="tmp_str")
					{
						imei_iotype_arr[vid_local[0]]=vid_local[1];				
					}
					text_report_io_element=text_report_io_element+vid_local[1]+",";				
					vid=vid+vid_local[0]+",";
					num1 = 1;
				}
			}
			if(num1==0)
			{
				alert("Please Select At Least One Vehicle");							
				return false;  			
			}
			var strIOElement = text_report_io_element.length;
				text_report_io_element = text_report_io_element.slice(0,strIOElement-1);
			var strLen = vid.length;
			vid = vid.slice(0,strLen-1);				
		}
		else if(mode==2)        // TRACK
		{
			if(imeino1.length!=undefined)
			{      
				for(i=0;i<imeino1.length;i++)
				{
					if(imeino1[i].checked)
					{
					
						var vid_local=(imeino1[i].value).split("*");							
						text_report_io_element=vid_local[1];						
						vid =  vid + vid_local[0];							
						num1 = 1;
					}
				}
			}
			else
			{
				if(imeino1.checked)
				{
					var vid_local=(imeino1.value).split("*");
					if(vid_local[1]!="tmp_str")
					{
						imei_iotype_arr[vid_local[0]]=vid_local[1];	
					}
					text_report_io_element=vid_local[1];						
					vid =  vid + vid_local[0];			
					num1 = 1;
				}        
			}      
			if(num1==0)
			{
				alert("Please Select At Least One Vehicle");							
				return false;  			
			}              	 
		}	
		var date = new Date();
		var dest = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
		if(document.thisform.location.checked==true)
		{
			var data_with_location="1";					
		}
		else
		{
			var data_with_location="0";
		}
		//alert("dest="+dest);
		document.cldr.xml_file.value=dest;
		document.cldr.vserial.value=vid;					
		document.cldr.text_report_io_element.value=text_report_io_element;		
		document.cldr.dwt.value=data_with_location;
		document.cldr.submit();
		/*document.cldr.action="src/php/cld_prev?tmpval1="+vid+"&tmpval2="+text_report_io_element+"&ltmpval3="+data_with_location+"&ltmpval4="+dest;
		document.cldr.target="_blank";
		document.cldr.submit();*/	
	//}

	function close_vehicle_display_option()
	{
		document.getElementById("blackout_1").style.visibility = "hidden";
		document.getElementById("divpopup_1").style.visibility = "hidden";
		document.getElementById("blackout_1").style.display = "none";
		document.getElementById("divpopup_1").style.display = "none";
		document.getElementById("blackout_2").style.visibility = "hidden";
		document.getElementById("divpopup_2").style.visibility = "hidden";
		document.getElementById("blackout_2").style.display = "none";
		document.getElementById("divpopup_2").style.display = "none";	
	}  

	function home_select_by_entity(display_type)
	{
		close_div_for_all_function()

		var poststr = "display_type1=" + encodeURI(display_type);
		//alert("patstr="+poststr);
		makePOSTRequest('src/php/module_selection_information.php', poststr);
	}

	

	function portal_vehicle_information(value)
	{
		document.getElementById("blackout_2").style.visibility = "visible";
		document.getElementById("divpopup_2").style.visibility = "visible";
		document.getElementById("blackout_2").style.display = "block";
		document.getElementById("divpopup_2").style.display = "block"; 
		var poststr = "vehicle_id=" + encodeURI(value);
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/portal_vehicle_information.php', poststr);
	}
	function close_portal_vehicle_information()
	{
		document.getElementById("blackout_2").style.visibility = "hidden";
		document.getElementById("divpopup_2").style.visibility = "hidden";
		document.getElementById("blackout_2").style.display = "none";
		document.getElementById("divpopup_2").style.display = "none";	
	}

	function main_vehicle_information(value)
	{
		document.getElementById("blackout_3").style.visibility = "visible";
		document.getElementById("divpopup_3").style.visibility = "visible";
		document.getElementById("blackout_3").style.display = "block";
		document.getElementById("divpopup_3").style.display = "block"; 
		var poststr = "vehicle_id=" + encodeURI(value);
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/main_vehicle_information.php', poststr);
	}
	function close_main_vehicle_information()
	{
		document.getElementById("blackout_3").style.visibility = "hidden";
		document.getElementById("divpopup_3").style.visibility = "hidden";
		document.getElementById("blackout_3").style.display = "none";
		document.getElementById("divpopup_3").style.display = "none";	
	}

	function close_vehicle_display_option1()
	{
		document.getElementById("blackout_2").style.visibility = "hidden";
		document.getElementById("divpopup_2").style.visibility = "hidden";
		document.getElementById("blackout_2").style.display = "none";
		document.getElementById("divpopup_2").style.display = "none";	
	}

	function close_div_for_all_function()
	{
		document.getElementById("blackout_2").style.visibility = "hidden";
		document.getElementById("divpopup_2").style.visibility = "hidden";
		document.getElementById("blackout_2").style.display = "none";
		document.getElementById("divpopup_2").style.display = "none";
	}
	function show_enter_button()
	{
		//alert("test");
		document.getElementById("all_vehicle_type").value="all_vehicle";
		document.getElementById("selection_information").style.display="none";
		document.getElementById("all_vehicle_1").style.display=""; 
		//alert("test_1");		
	}  

	function create_pdf()
	{	
		//alert("test");
		document.text_data_report.target = "_blank";
		document.text_data_report.action="src/php/report_getpdf_type3.php?size=<?php echo $size; ?>";
	}	

	function mail_report()
	{
		document.text_data_report.target = "_self";
		document.text_data_report.action="mail_lastdata_report.php?size=<?php echo $size; ?>";
	}

	function call()
	{	
		//alert("test");
		document.text_data_report.action="tempfiles/dl.php?filename=lastdatareport.csv";
		document.text_data_report.target="_blank";
		document.text_data_report.submit();	
	}

	//function MapWindow(vname,datetime,lat,lng)
	function MapWindow(vname,datetime,lat,lng)
	{
		//alert(vname+" "+datetime+" "+lat+" "+lng);	
		//test2(vname,datetime,lat,lng);			
		document.getElementById("window").style.display = '';
		load_vehicle_on_map(vname,datetime,lat,lng);							
	}
	
/////////////////////////////////////////////// VALIDATION //////////////////////////////
function tree_validation(obj)
{
	//alert("obj="+obj);
	var tree_option_id = "";	
	var options=obj.elements["home_array[]"];
	//alert("opt="+options);
	var num1=0;   var count=0;    var cnt=0		
	if(options.length!=undefined)
	{
		for(i=0;i<options.length;i++)
		{
			if(options[i].checked)
			{
				if(cnt==0)
				{
					tree_option_id =  tree_option_id + options[i].value;
					cnt=1
				}
				else
				{
					tree_option_id = tree_option_id+ "," + options[i].value;
				}
				num1 = 1;
			}
		}
	}
	else
	{
		if(options.checked)
		{
			tree_option_id=tree_option_id + options.value;
			num1 = 1;
		}
	}
	if(num1==0)
	{
		
		alert("Please Select At Least One Option");							
		return false;  			
	}
	
	//alert("value="+tree_option_id);
	return tree_option_id;
}

	function SelectAll(set_type)
	{
		//alert("set_type="+set_type);
		var obj=document.thisform;
		if(set_type=="vehicle")
		{
			var s=obj.elements['vehicleserial[]'];
			if(obj.all.checked)
			{
				var i;		
				for(i=0;i<s.length;i++)
					s[i].checked="true";		
			}
			else if(obj.all.checked==false)
			{
				var i;		
				for(i=0;i<s.length;i++)
					s[i].checked=false;
			} 
		}
		else if(set_type=="display_option")
		{
			var s=obj.elements['home_array[]'];		
			
      if(s.length!=undefined)
			{
        if(obj.all_1.checked)
  			{
  				var i;		
  				for(i=0;i<s.length;i++)
  					s[i].checked=true;		
  			}
  			else if(obj.all_1.checked==false)
  			{
  				var i;		
  				for(i=0;i<s.length;i++)
  					s[i].checked=false;
  			}
  		}
  		else
  		{
  		  if(obj.all_1.checked)  		  
          s.checked=true;
        else
          s.checked=false;
      }
		}
					
	}

	/*function open_win(value)
	{
	alert("val="+value);
	window.open("src/php/NextPage.php",value,'width=300,height=200');
	}*/	