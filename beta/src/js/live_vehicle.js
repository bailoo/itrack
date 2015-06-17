////////////////////////////////////////////////////////////////////////////////
/*var startup_var;
var newurl;
newurl=0;*/

// XML FUNCTION ALREADY DEFINED IN GETLASTPOSITION
var startup_var;
var newurl;
newurl=0;
var delaycnt=0;

var thisdest;
var thismode;
var thisaccess;
var timer;
var TryCnt;


var dist;
dist = 0;
var tmp_dist;
tmp_dist=0;


/// load xml code

var browser=navigator.appName;
var b_version=navigator.appVersion;
var version=parseFloat(b_version);

function loadXML(xmlFile)
{
  //alert("in loadxml");
	var xmlhttp=false;
	var status = false;
	var xmlDoc=null;
	/*@cc_on @*/
	/*@if (@_jscript_version >= 5)
	// JScript gives us Conditional compilation, we can cope with old IE versions.
	// and security blocked creation of the objects.
	 try {
	  xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	 } catch (e) {
	  try {
	   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	  } catch (E) {
	   xmlhttp = false;
	  }
	 }
	@end @*/
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		try {
			xmlhttp = new XMLHttpRequest();
		} catch (e) {
			xmlhttp=false;
		}
	}
	if (!xmlhttp && window.createRequest) {
		try {
			xmlhttp = window.createRequest();
		} catch (e) {
			xmlhttp=false;
		}
	}
  
  newurl++;	
	xmlFile=xmlFile+"?newurl="+newurl; 
	xmlhttp.open("GET",xmlFile,false);
	xmlhttp.send(null);
	var finalStr = xmlhttp.responseText
	//var finalStr = modify_xml_text(txtStr)	 
	//alert(txtStr.length);	  
	//alert("finalStr="+finalStr.length);	
	if (window.DOMParser)
	{
		//alert("in window.DOMparser:"+finalStr);
		parser=new DOMParser();
		xmlDoc=parser.parseFromString(finalStr,"text/xml");
	}
	else // Internet Explorer
	{
		try
		{
		  //alert("T3:"+finalStr);
      xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
  		xmlDoc.async="false";
  		status = xmlDoc.loadXML(finalStr);
  	}
  	catch(e1)
  	{
  	 //alert("T1");
  	 xmlDoc=null;
    }
    //alert("T2:"+status);
    if(status==false)
	  {
	   xmlDoc=null;
    }
	} 
	
	  //alert("xmlDoc="+xmlDoc);
	  return xmlDoc;
};

function verify()    /// for Internet Explorer
{
 // 0 Object is not initialized
 // 1 Loading object is loading data
 // 2 Loaded object has loaded data
 // 3 Data from object can be worked with
 // 4 Object completely initialized
 if (xmldoc.readyState != 4)
 {
   return false;
 }
}

////////////////////////////

function movingVehicle_prev(vserial)
{
	if (GBrowserIsCompatible()) 
  {	  			
  		//alert("in GBrowserIsCompatible")
  		map.clearOverlays();	
  }	
  if((browser=="Microsoft Internet Explorer") && (version>=4)) // for internet xeplorer
	{
		alert("Retrieving data ..... plz wait!");
	}

	//alert("In Moving V");
	document.getElementById('prepage').style.visibility='visible';	
	//alert("In Moving Visible="+document.getElementById('prepage').style.visibility);
	//alert(" DIST IN movingVehicle_prev ="+dist+ " tmp_dist="+tmp_dist);
	//alert("in mprev");
	document.form1.mapcontrol_startvar.value=1;
	startup_var = 1;
	map.clearOverlays();
	
	document.forms[0].vehicleSerial.value = vserial;
	//alert("vserial in moving vehicle ="+document.forms[0].vehicleSerial.value);
  /// REFRESH VALUES
	dist= 0;
	tmp_dist = 0;
	document.form1.vid2.value ="";
	document.form1.lat.value ="";
	document.form1.lng.value ="";
	lm = 0;
	
	geocoder = null;
	address = null;
 	ad=0;
	address1=0;	
	//alert("before load")

	movingVehicle();

	//alert("MOVING1");
	//auto_refresh();
	//alert("after load");
}

function movingVehicle()
{	
	//alert("in mnext");
	//alert(" DIST IN movingVehicle ="+dist+ " tmp_dist="+tmp_dist);
	tmp_dist = 0;

	var dmode;
	var startdate;
	var enddate;
	var status;
	var pt_for_zoom;
	var zoom_level;
 
 	///////////// get date ////////////////////////
 	var currentDate2 = new Date;
 	var yr = currentDate2.getFullYear();
	var mnt =  currentDate2.getMonth()+1;
	var dt =  currentDate2.getDate();
	var hr = currentDate2.getHours();
	var min = currentDate2.getMinutes();
	var sec = currentDate2.getSeconds();
 	if(mnt>0&&mnt<10)
		mnt = "0"+mnt ;
 	if(dt>0&&dt<10)
		dt = "0"+dt;
 	if(hr>0&&hr<10)
		hr = "0"+hr;
 	if(min>0&&min<10)
		min = "0"+min;
 	if(sec>0&&sec<10)
		sec = "0"+sec;
 	startdate = yr+"-"+mnt+"-"+dt+" 00:00:00";
	enddate = yr+"-"+mnt+"-"+dt+" "+hr+":"+min+":"+sec;

	// pass zoom parameters
 	if(document.forms[0].pt_for_zoom.value==1 && document.forms[0].zoom_level.value==1)
	{
		status = "ON";
	}
	else
	{
		status = "OFF";
		pt_for_zoom = "0";
		zoom_level = "0";
	}
	
	var vid;
	vid = document.forms[0].vehicleSerial.value;
	//alert(vid+".xml");
 	////////// CALL MAIN FUNCTION ///////////////
	//alert("before load call");
	//alert("st="+startdate+" ed="+enddate);
	var access = document.forms[0].access.value;
	load(vid,dmode,startdate,enddate,pt_for_zoom,zoom_level,status,access);
 	document.form1.current_vehicle=1;

	//alert("MOVING2");
}

 ////////////////function load /////////////////////////////////////////////
function load(vid,dmode,startdate,enddate,pt_for_zoom,zoom_level,status,access)
{
	//alert("in load");
	Load_MovingData(vid,startdate,enddate,pt_for_zoom,zoom_level,status,access);	
	//alert("MOVING3");
	auto_refresh();
}

 
var dist_array = new Array();

function Load_MovingData(vid,startdate,enddate,pt_for_zoom,zoom_level,status,access)
{  
	if (GBrowserIsCompatible()) 
  {	  			
  		//alert("in GBrowserIsCompatible")
  		//map.clearOverlays();	
  		//alert('user_date='+user_dates.length+'vehicleserial='+vehicleSerial);
  		if(vid!=null)
  		{
  		 //alert('check');
        var date = new Date();
        // COPY ORIGINAL XML FILE        
        var dest = "xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml"
       
        dmode = 1; 
        thisdest = dest;        
        thisaccess = access;
        //var dest = "xml_tmp/filtered_xml/tmp_1295185453465.xml" ;
        //alert("d="+dest);        
        
        // MAKE FILTERED COPY        
        var poststr = "xml_file=" + encodeURI( dest )+
                "&mode=" + encodeURI( dmode )+
                "&vserial=" + encodeURI( vid )+
                "&startdate=" + encodeURI( startdate )+
                "&enddate=" + encodeURI( enddate );                       
                                                                   
        makePOSTRequest('get_filtered_xml.php', poststr);
        //alert("after req1");
        TryCnt =0;
        clearTimeout(timer);
        timer = setTimeout('displayInfo()',1000);
        //alert("after req2");	 
      } // if vid closed
   } //is compatible closed
} //function load1 closed


function displayInfo()
{
  //alert("In displayInfo");  
  var lat_arr = new Array();
  var lng_arr = new Array();
  var vid_arr = new Array();
  var vehiclename_arr = new Array();
  var speed_arr = new Array();
  var datetime_arr = new Array();
  var place_arr = new Array();
  var fuel_arr = new Array();
  var vehicletype_arr = new Array();
  var Final_DateTime=new Array();  
  var xml_data; 
  var DataReceived = false;               
  
  try
  {
    var bname = navigator.appName;          
    /*if (bname == "Microsoft Internet Explorer")
    {
      alert("Wait for data, please use Mozilla for better compatibility");
    }//alert(bname);   */
              
    var xmlObj = null;        
    //alert("thisdest="+thisdest);
    //thisdest = "xml_tmp/filtered_xml/tmp_1297258704848.xml";
    //alert("thisdest="+thisdest);
    xmlObj = loadXML(thisdest);
     
    //alert("xmObj="+xmlObj);
    
    if (bname == "Microsoft Internet Explorer")
    {
      //alert("In IE:"+xmlObj);
      if(xmlObj!=null)	
      {                                      
        xml_data = xmlObj.documentElement.getElementsByTagName("marker");
        DataReceived = true;
      } 
      else
      {
        if(TryCnt<=100)
        {
          TryCnt++;
          clearTimeout(timer);
          timer = setTimeout('displayInfo()',1000);
        }
      }                                
    }
    else
    {
      //alert("In Mozilla");
      xml_data = xmlObj.documentElement.getElementsByTagName("marker");
      //alert(xml_data);
      var xml_data1 = xmlObj.getElementsByTagName("t1");
      if(xml_data1.length>0)
      {
        //alert("A");
        DataReceived = true;
      }
      else
      {
        //alert("B:"+TryCnt);
        if(TryCnt<=100)
        {
          TryCnt++;
          clearTimeout(timer);
          timer = setTimeout('displayInfo()',1000);
        }
      }
    }   
    //alert("xml_data="+xml_data);             
	}
	catch(err)
	{
		alert("file not found");
	}	
    
  /*if (bname == "Microsoft Internet Explorer")
  {
    alert("Data Received");
  }	*/								
				
	//alert("xml_data len="+xml_data.length);    
  if((((xml_data.length==0) || (xml_data.length==undefined)) && (DataReceived==true)) || (TryCnt>=100))
	{	
	  alert("No Data Found");
    document.getElementById('prepage').style.visibility='hidden';	
    clearTimeout(timer);	
    //var poststr = "dest=" + encodeURI( thisdest );
	  //makePOSTRequest('del_xml.php', poststr);								
	}
	else  if(DataReceived==true)
	{	
	  clearTimeout(timer);	
		var len2=0;
    for (var k = 0; k < xml_data.length; k++) 
		{																													
			//alert("t11111111==="+t1[i].getAttribute("datetime"));						
			lat_tmp = xml_data[k].getAttribute("lat");
			lng_tmp = xml_data[k].getAttribute("lng");	
						
			lat_arr[len2] = xml_data[k].getAttribute("lat");
			lng_arr[len2] = xml_data[k].getAttribute("lng");
			vid_arr[len2] = xml_data[k].getAttribute("vehicleserial");
			vehiclename_arr[len2] = xml_data[k].getAttribute("vehiclename");
			speed_arr[len2] = Math.round(xml_data[k].getAttribute("speed")*100)/100;
			if(speed_arr[len2]<=3)
			{
				speed_arr[len2] = 0;
			}
			fuel_arr[len2] = xml_data[k].getAttribute("io8");	
      if(fuel_arr[len2] <30)
			{
				fuel_arr[len2] =0;
			}											
			vehicletype_arr[len2] = xml_data[k].getAttribute("vehicletype");
			datetime_arr[len2] =  xml_data[k].getAttribute("datetime");
			//alert("lt=="+lat_arr[len2]+"lng_arr(len2) ="+lng_arr[len2]+"vid arr="+vid_arr[len2]);
			len2++;		
		}	//XML LEN LOOP CLOSEDhaa
		
    //var poststr = "dest=" + encodeURI( thisdest );
	  //makePOSTRequest('del_xml.php', poststr);
	   
	  if(vid_arr.length>0 && lat_arr.length>0 && lng_arr.length>0)
    {	      
      clearTimeout(timer);
      //alert("data found");
      document.form1.status.value = "["+ vid_arr[0]+ "]"+"-("+lat_arr[0]+","+lng_arr[0]+")";
      //alert("LPCOUNT="+lp_count);
      //alert("before track markers "+len2+" "+lat_arr+" "+lng_arr+" "+vid_arr+" "+vehiclename_arr+" "+speed_arr+" "+datetime_arr+"  VTYPE="+vehicletype_arr);				
      var flag=1;
      getxml_MovingData(flag,lat_arr[0],lng_arr[0],vid_arr[0],vehiclename_arr[0],speed_arr[0],datetime_arr[0],fuel_arr[0],fuel_arr[0],vehicletype_arr[0],thisaccess);	
      //alert("K");
      document.getElementById('prepage').style.visibility='hidden';	
    }					
	} // ELSE CLOSED       		
}


function getxml_MovingData(flag1,lat_1,lng_1,vid_1,vehiclename_1,speed_1,datetime_1,fuel_level_1,fuel_litres_1,vehicletype_1,access)
{
	//alert("in getxmlData "+flag1+" "+lat_1+" "+lng_1+" "+vid_1+" "+vehiclename_1+" "+speed_1+" "+datetime_1+" "+fuel_level_1+" "+fuel_litres_1+" "+vehicletype_1);
 	var point;
	
	if(startup_var == 1)
	{
		map.clearOverlays();
		var bounds = new GLatLngBounds();
 		point = new GLatLng(parseFloat(lat_1),
						parseFloat(lng_1));
 		bounds.extend(point); 
		
		var center = bounds.getCenter(); 
		
		var zoom = map.getBoundsZoomLevel(bounds)-7; 
		
		//alert("center="+center+" zoom="+zoom);
		map.setCenter(center,zoom); 
		
		startup_var = 0;
	}	
		
 	if(vid_1.length<=0)
	{
		flag1=0;
	}	
	//alert("mapcontrol_startvar="+document.form1.mapcontrol_startvar.value);

	if(document.form1.mapcontrol_startvar.value == 1)
	{
		document.form1.mapcontrol_startvar.value = 0
		//alert(zoom_level);
		/*map.removeMapType(G_SATELLITE_MAP);
		map.enableContinuousZoom();
		map.addControl(new GLargeMapControl());
		map.addControl(new GOverviewMapControl());			
		map.addControl(new GScaleControl()) ; 		
		var mapTypeControl = new GMapTypeControl();
		var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10,10));	
		//map.addControl(mapTypeControl, topRight);
		///////////
		map.addMapType(G_SATELLITE_MAP);
		var mapControl = new GMapTypeControl();
		map.addControl(mapControl, topRight);   */
		//////////
		///////////////draggable zoom
		/////////////////////////////		
	}

	// FUNCTION CALL TO MAKE TRACK ON EVENT
 	if(flag1)
	{		
		//// FUNCTION CALL TO CREATE REST TRACK MARKERS /////////////////////		
		//alert("before track_markers call");
		Moving_DataMarkers(lat_1, lng_1, vid_1, vehiclename_1, speed_1, datetime_1, fuel_level_1, fuel_litres_1, vehicletype_1);
 		var zoom;
		var event = 0;
		var newzoomlevel=0;
		
		document.getElementById('prepage').style.visibility='hidden';	
														
	}//if flag1 closed		
 	//document.getElementById('prepage').style.visibility='visible';								
		//////////////////////////////////////////////////////////////			
} //FUNCTION getxmlDataTrack
 ///////////////////////track markers////////////////////////////////////
function Moving_DataMarkers(lat_1, lng_1, vid_1, vehiclename_1, speed_1, datetime_1, fuel_level_1, fuel_litres_1, vehicletype_1)
{	
	//alert("in track_markers  "+lat_1+" "+lng_1+" "+vid_1+" "+vehiclename_1+" "+speed_1+" "+datetime_1+" "+fuel_level_1+" "+fuel_litres_1+" "+vehicletype_1);
	//alert(" DIST IN Moving_DataMarkers ="+dist+ " tmp_dist="+tmp_dist);
	var j = 0;
	var colr = ["#00FF66","#0066FF","#FF0000","#33FFFF","#FF33CC","#9966FF","#FF9900","#FFFF00"];
	var i,vehiclename,speed,point,datetime,vehicletype,marker,polyline,last;
 	//var vid1=0;
	//var vid2=0;
	var pt = new Array();
	var value;
	var poly = new Array();
 	var lastmarker = 0;
	//var len = t1.length;
	var p = 0;
	var fuel_level=0;
	var fuel_litres=0;
	
	//alert("len2 in track markers="+len2);
 	//alert("in len2 condition i="+i);						
	vehiclename = vehiclename_1;
	vehicletype = vehicletype_1;
	//alert("vname in track M ="+vehiclename);		
	
	speed = speed_1;
	if(speed<=3)
		speed = 0;
 	point = new GLatLng(parseFloat(lat_1),
	parseFloat(lng_1));
	datetime = datetime_1;	
	//place = place_arr(i);	
	fuel_level = fuel_level_1;	
	fuel_litres = fuel_litres_1;	
 	pt[i] = point;					
	//lastpoint= pt[i];
 	place=0;
	//alert("ic="+i);
 	//alert(" i="+i+" gmarkersC="+gmarkersC+"mm="+mm+" "+point+" "+vehiclename+" "+speed+" "+datetime+"place="+place+"dist="+dist+" fuel_level="+fuel_level+" fuel_litres="+fuel_litres+" p="+p);
	//alert("marker="+marker);
	
	p++;
 	/*var last_marker = document.forms[0].last_marker.value;
 	//alert("last_marker to remove ="+last_marker);
 	if(last_marker!="")
	{
		alert("in remove");
		//map.removeOverlay(last_marker);
	}*/
 	marker = Create_MovingDataMarkers(point, vehiclename, speed, datetime, fuel_level, fuel_litres, vehicletype, p);	
	//alert(" marker after="+marker);
	
  var ic= new GIcon();
  ic.image = 'moving_truck.png';
  ic.iconSize= new GSize(14, 17);
  //baseIcon.shadowSize= newGSize(37, 34);
  ic.iconAnchor= new GPoint(8, 20);
  ic.infoWindowAnchor= new GPoint(0, 0);
		
  //alert("ic"+ic+ "point="+point)
  var tmpIcon = new GIcon(ic);
	var markertmp = new GMarker(point, tmpIcon);
	//alert("mtmp="+markertmp);
	map.addOverlay(markertmp);
	
	if(last_marker!=undefined)
	{
		map.removeOverlay(last_marker);	
		//alert("marker="+marker+" last_m="+last_marker);
	}
 	map.addOverlay(marker);
 	//alert(" lm = "+lm);
	//if(document.form1.last_marker.value == "")
 	if(lm=="" || lm ==0)
	{
		//alert("In first If");
 		document.form1.vid2.value = vid_1;
		document.form1.lat.value = lat_1;
		document.form1.lng.value = lng_1;
		//alert(document.form1.vid2.value+" "+document.form1.lat.value+" "+document.form1.lng.value);
		//alert(lat_1+" "+lng_1);
		lm=1;
	}
	else
	{
		var vid2 = document.form1.vid2.value;
		var prev_lat = document.form1.lat.value;
		var prev_lng = document.form1.lng.value;
 		//alert("OUTSIDE  IF vid1="+vid_1+"vid2="+vid2+" lat_1="+lat_1+" prev_lat="+prev_lat+" lng_1="+lng_1+" prev_lng="+prev_lng);
 		if( (vid_1 == vid2) && (lat_1!=prev_lat) && (lng_1 != prev_lng) )
		{																					
 			//alert("Polyline");
			polyline = new GPolyline([
			new GLatLng(parseFloat(prev_lat),
												parseFloat(prev_lng)),
			new GLatLng(parseFloat(lat_1),
												parseFloat(lng_1))], '#FF0000', 3,1);	
 			//alert("INSIDE  second IF vid1="+vid_1+"vid2="+vid2+" lat_1="+lat_1+" prev_lat="+prev_lat+" lng_1="+lng_1+" prev_lng="+prev_lng);
 			//alert("poly="+polyline);
			map.addOverlay(polyline);	
			
			document.form1.vid2.value = vid_1;
			document.form1.lat.value = lat_1;
			document.form1.lng.value = lng_1;			
		}
			//alert("y1="+year1+"y2="+year2+"m1="+month1+"m2="+month2+"d1="+day1+"d2="+day2+"h1="+hr1+"h2="+hr2);  					
	} //else closed
	
	//document.forms[0].last_marker.value = marker;
	//alert("last_marker added ="+document.form1.last_marker.value);
 	last_marker = marker;
		
} //track markers closed
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////

var pt = new Array();
//var p = 0;
var lat1 = 0;
var lng1 = 0;
var lat2 = 0;
var lng2 = 0;
var coord;
var vname = new Array();
//var gmarkersC;
var mm;
var vIcon;
var rect;

function Create_MovingDataMarkers(point, vehiclename, speed, datetime, fuel_level, fuel_litres, vehicletype, p) 
{	
	//alert(" DIST IN Moving_DataMarkers ="+dist+ " tmp_dist="+tmp_dist);
	//alert("in create marker track,point="+point+" ,vehiclename="+vehiclename+" ,speed="+speed+" ,datetime="+datetime+" ,fuel_level="+fuel_level+" ,fuel_litres="+fuel_litres+" ,vehicletype="+vehicletype+" ,p="+p);
 	var lat1,lat2,lng1,lng2;
 	if(fuel_level==null)
		fuel_level=0;
 	if(fuel_litres==null)
		fuel_litres=0;
 	
  if(p==0)
	{
		//gmarkersC = []; 
		mm = new GMarkerManager(map, {borderPadding:1});
	}	
 	
  //alert("vtype="+vehicletype+" lvIcon1="+lvIcon1); 
  if(vehicletype=="Light")
		vIcon = new GIcon(lvIcon1);
 	else if(vehicletype=="Heavy")
		vIcon = new GIcon(hvIcon1);
 	//alert("vIcon="+vIcon);
	 vname[p] = vehiclename;
		
	//if(newurl>1)
	{
		var prev_lat = document.form1.lat.value;
		var prev_lng = document.form1.lng.value;
 		if(prev_lat && prev_lng)
		{
			//// CODE FOR DIRECTION ARROW
			lat1 = parseFloat(prev_lat);
			lng1 = parseFloat(prev_lng);	
			
			lat2 = point.y;
			lng2 = point.x;
 			if(lat1!=lat2 && lng1!=lng2)
			{
				//alert("lt1= "+lat1+"  lt2= "+lat2+"  &  lng1= "+lng1+"  lng2= "+lng2+ "coord=="+coord);
 				var yaxis = (lat1 + lat2)/2;
				var xaxis = (lng1 + lng2)/2;
						
				coord = new GLatLng(yaxis,xaxis);
 				//alert("coord=="+coord);
 				var angle_t = Math.atan( (lat2-lat1)/(lng2-lng1) );
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
 				var IconArrow = new GIcon();    
				IconArrow.image = angle_deg+'.png';
				IconArrow.iconSize = new GSize(20, 19);
				IconArrow.iconAnchor = new GPoint(10, 10);
 				var marker2 = new GMarker(coord, IconArrow);
				//alert("mrk==="+marker2);
 				//gmarkersC.push(marker2);
				map.addOverlay(marker2);
 				//alert(" dist before="+dist+" tmp_dist="+tmp_dist);
				//alert("lat1="+lat1+" lat2="+lat2+" lng1="+lng1+" lng2="+lng2);
				tmp_dist = calculate_distance(lat1,lat2,lng1,lng2);	
				//alert(" dist before="+dist+" tmp_dist="+tmp_dist);
				
				tmp_dist = parseFloat(tmp_dist);
				tmp_dist = Math.round(tmp_dist * 100)/100; 
				
				dist = dist + tmp_dist;
				dist = parseFloat(dist);
				dist = Math.round(dist*100)/100;
			}
		}
	}
 	var lt_1 = Math.round(point.y*100000)/100000; 
	var ln_1 = Math.round(point.x*100000)/100000;
 	var marker = new GMarker(point, vIcon);
	var marker3 = new GMarker(point,vIcon);
 	//var html ="<table cellpadding=0 cellspacing=0><tr><td><font size=2><b>Vehicle</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color='red' size=2><b>"+vehiclename + "</b></font></td><td></td></tr><tr><td><font size=2><b>Speed</b></font></td><td>&nbsp;:&nbsp;</td><td><font color='red' size=2><b>"+speed+" kmph</b></font></td></tr><tr><td><font size=2><b>Date & Time</b></font></td><td>&nbsp;:&nbsp;</td><td><font color='red' size=2><b>"+datetime+"</b></font></td></tr> <tr><td><font size=2><b>Place</b></font></td><td>&nbsp;:&nbsp;</td><td><font color='red' size=2><b>"+place+"</b></font></td></tr><tr><td><font size=2><b>Distance travelled</b></font></td><td>&nbsp;:&nbsp;</td><td><font color='red' size=2><b>"+dist+" km</b></font></td></tr><tr><td colspan=3><font color='blue' size=2><b>( "+lt_1+", "+ln_1+" )</b></font></td></tr></table>";
 	//alert("tmp_dist="+tmp_dist);
	if (tmp_dist > 0)
	{
		PlotCurrentMarkerWithAddress(point, vIcon, marker, vehiclename, speed,datetime, dist,fuel_litres, fuel_level);		
		//tmp_dist = 0;
	}
	
	GEvent.addListener(marker, 'mouseover', function() {	
 	/////////////////////// PLOT MARKER CODE - call function ///////////////////////////////////////////////////////		
		PlotCurrentMarkerWithAddress(point, vIcon, marker, vehiclename, speed, datetime, dist,fuel_litres, fuel_level);
 	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	});	
	
	//alert("marker="+marker);
	return marker;		
}				


 ////////info landmark window table//////////////
function pretty(a) 
{
	return '<table border="0" cellpadding="0" cellspacing="0"><tr><td width="100%" class="EWTitle" nowrap>' + a + '</td></tr>' +
		   '<tr><td nowrap></td></tr></table>';
}
 
///////////Show landmark window marker////////////////
function ShowMarker(point, landmark) 
{		
	//alert("pt="+point);
	//alert("lnmrk="+landmark);
 	var Icon= new GIcon(lnmark);
	var marker = new GMarker(point,Icon);
	var marker2 = new GMarker(point,Icon);
 	var lat = Math.round((point.y)*100000)/100000;
	var lng = Math.round((point.x)*100000)/100000;
 	//var iwform = pretty('<center>LANDMARK <br>'
		//+ '  <font color="blue" size=3><strong>'+landmark+'</strong></font></center><br>');
 	var iwform = '<table bgcolor="#EEEFF0" border="0"><tr><td><table border="0" cellpadding=1 cellspacing=0><tr><td>&nbsp;</td></tr><tr><td><font size=3 color=#000000><b>LANDMARK</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=red size=3><b>'+landmark + '</b></font></td><td></td></tr><tr><td>&nbsp;</td></tr><tr><td><font size=2 color=#000000><b>Latitude</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lat+'</font></td></tr><tr><td></td></tr><tr><td><font size=2 color=#000000><b>Longitude</b></font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lng+'</font></td></tr></table></td></tr></table>';
        
	// ========== Open the EWindow instead of a Google Info Window ==========
	GEvent.addListener(marker, "click", function() {
	  //ew.openOnMarker(marker,iwform);
	///////////////////////MINI MAP CODE////////////////////
 	var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=center><br><font color=#000000'+iwform+'</font></div>');
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
  /////////////////////////////////////////////////////////////////////////////////////////////////////
	});     
 	 // ========== Close the EWindow if theres a map click ==========
	GEvent.addListener(map, "click", function(overlay,point) {
		if (!overlay) {
		  ew.hide();
		}
	});
 	return marker;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////

var ad=0;
var place;
var address1=0;

////////////////////////// PLOT TRACK MARKERS WITH ADDRESSES ////////////////////////////////////////
function PlotCurrentMarkerWithAddress(point, Icon, marker, vehiclename, speed, datetime, dist,fuel_litres, fuel_level) 
{
 //alert("In PlotCurrenMarkerWithAdd");
 var accuracy;
 var largest_accuracy;	   
 var delay = 100;
 var geocoder = new GClientGeocoder();
 
 geocoder.getLocations(point, function (result) {
 if (result.Status.code == G_GEO_SUCCESS) // OR !=200
 {
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
			   // alert(" largest accuracy="+largest_accuracy+" accuracy="+accuracy+" i="+i);
				if(largest_accuracy < accuracy)
				{
					largest_accuracy = accuracy;
					//alert("i="+i);
					j = i;
					//alert("j1========="+j);
				}
			}
    }
	   
	  // alert("j2="+j);
	  // alert("largest_accuracy="+largest_accuracy+ " j="+j+" result="+result);
 		var address = result.Placemark[j];
		address1 = address.address;	   
		//alert("addddddddd=="+address1);
 		// setTimeout('wait()',10000);
		var google_point = new GLatLng(address.Point.coordinates[1],address.Point.coordinates[0]); 
 		//Spliting the latitude 
		//alert("latlng.y======="+latlng.y+" latlng.x="+latlng.x);
		//alert("google_point.y======="+google_point.y+" google_point.x="+google_point.x);
 		var distance = calculate_distance(point.y, google_point.y, point.x, google_point.x); 
		//alert("dist="+distance);
		var address2 = distance+" km from "+address1;
 		var place = address2;
		//alert(place);
 		/*alert("[Original latlng="+latlng+"]   <br>  [Google PT="+point+"]   largest_accuracy"+largest_accuracy+"			address1="+address1);*/
		//alert("add before="+address1+ ", address2="+place);
 		//alert("point="+point+" ,Icon="+Icon+" map="+map+" marker="+marker+ " vname="+vehiclename+" spd="+speed+" dt="+datetime+" dist="+dist+" fuelltr="+fuel_litres+" fuel_level="+fuel_level);
 		var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+'</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td><font size=2 color=#000000>Distance travelled</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+dist+' km</font></td></tr><tr><td><font size=2 color=#000000>Fuel</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+fuel_litres+' litres ('+fuel_level+' %)</td></tr><tr><td colspan=3><font color=blue size=2>( '+point.y+', '+point.x+' )</font></td></tr></table></div>');
 		//var html = new GInfoWindowTab("Info", '<div id="tab1" class="bubble">Click the "Location" tab to see the minimap</div>');
		var tab2 = new GInfoWindowTab("Location", '<div id="detailmap" style="height:150px;"></div>');
 		//alert(" tab1="+tab1+" tab2="+tab2);
		var infoTabs = [tab1,tab2];
				
    //alert(" marker="+marker+" infoTabs="+infoTabs);
		marker.openInfoWindowTabsHtml(infoTabs);
 		var dMapDiv = document.getElementById("detailmap");
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
 		showMinimapRect(detailMap,marker3);   
     }
  });
}
 //////////////////////////////////////////////////////////////////////////////////////////////////////
function calculate_distance(lat1, lat2, lon1, lon2) 
{
	// deg to rad
	lat1 = (lat1/180)*Math.PI;
	lon1 = (lon1/180)*Math.PI;
 	lat2 = (lat2/180)*Math.PI;
	lon2 = (lon2/180)*Math.PI;
 	//alert("in calculate mileage"+lat1+" lat2="+lat2+" lon1="+lon1+" lon2="+lon2);
 	// Find the deltas
	var delta_lat = lat2 - lat1;
	var delta_lon = lon2 - lon1;
 	// Find the Great Circle distance
	var temp = Math.pow(Math.sin(delta_lat/2.0),2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(delta_lon/2.0),2);
	
	//alert("temp="+temp);
 	var distance = 3956 * 2 * Math.atan2(Math.sqrt(temp),Math.sqrt(1-temp));
 	//convert into km
	distance = distance*1.609344;
 	distance=Math.round(distance*100)/100;
 	//alert("dist="+distance);
	return distance;
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

function getScriptPage2(num)
{
	//alert("num"+num);
  var strURL="get_fuel_pro.php?content="+num;
  var req = getXMLHTTP();
  req.open("GET", strURL, false); //third parameter is set to false here
  req.send(null);
  return req.responseText;
}
////////////////////////////////////////////////////////////////////////////////////


//////////////////  MAKE POST REQUEST  ///////////////////////

var http_request = false;
function makePOSTRequest(url, parameters) 
{
    http_request = false;
    if (window.XMLHttpRequest) 
    { 
       http_request = new XMLHttpRequest();
       if (http_request.overrideMimeType)
       {
       	// set type accordingly to anticipated content type
          //http_request.overrideMimeType('text/xml');
          http_request.overrideMimeType('text/html');
       }
    }
    else if (window.ActiveXObject) 
    { // IE
       try 
       {
          http_request = new ActiveXObject("Msxml2.XMLHTTP");
       }
       catch (e) 
       {
          try 
          {
             http_request = new ActiveXObject("Microsoft.XMLHTTP");
          }
          catch (e)
          {}
       }
    }
    if (!http_request) 
    {
       alert('Cannot create XMLHTTP instance');
       return false;
    }
    
    http_request.onreadystatechange = alertContents;
    http_request.open('POST', url, true);
    http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http_request.setRequestHeader("Content-length", parameters.length);
    http_request.setRequestHeader("Connection", "close");
    http_request.send(parameters);
}
 
function alertContents()
{
  if (http_request.readyState == 4) 
  {
     if (http_request.status == 200) 
     {
        result = http_request.responseText;
        //alert(result);
     }
  }
} 
   
///////////////////////////////////////////////////////////////////////// ////////////////////////  


