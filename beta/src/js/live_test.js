
//LIVE JS MODULE
var lvIcon1 = new GIcon(); 
lvIcon1.image = 'images/live/live_vehicle.gif';
lvIcon1.iconSize = new GSize(8, 8);
lvIcon1.iconAnchor = new GPoint(2, 7);
lvIcon1.infoWindowAnchor = new GPoint(3, 10);

var lvIcon2 = new GIcon(); 
lvIcon2.image = 'images/live/lp_vehicle1.gif';
lvIcon2.iconSize = new GSize(8, 8);
lvIcon2.iconAnchor = new GPoint(2, 7);
lvIcon2.infoWindowAnchor = new GPoint(3, 10);

var lvIcon3 = new GIcon(); 
lvIcon3.image = 'images/live/lp_vehicle2.gif';
lvIcon3.iconSize = new GSize(8, 8);
lvIcon3.iconAnchor = new GPoint(2, 7);
lvIcon3.infoWindowAnchor = new GPoint(3, 10);

var lnmark= new GIcon();
lnmark.image = 'images/landmark.png';
lnmark.iconSize= new GSize(10, 10);
lnmark.iconAnchor= new GPoint(9, 34);
lnmark.infoWindowAnchor= new GPoint(5, 1);

var arrowIcon = new GIcon();
arrowIcon.iconSize = new GSize(20,19);
arrowIcon.iconAnchor = new GPoint(10,10);
arrowIcon.infoWindowAnchor = new GPoint(3,10);

var pt = new Array();
var imei1 = new Array();
var vname1 = new Array();
var speed1 = new Array();
var fuel1 = new Array();
var datetime1 = new Array();
var marker1 = new Array();
  
var map;
var marker_type;

var imei_tmp="";
var date_tmp ="";
var moving_status =0;
var imei_data;

var point_prev = new Array();  // FOR RUNNING PATH
var vid_prev = new Array();
var dist_prev = new Array();
var marker_prev = new Array();
var label_prev = new Array();
var date_prev = new Array();
var angle_prev = new Array();

var trail_flag = false;
		
function show_live_vehicles()
{
	document.getElementById("load_status").style.display ='';
  var display_type="single";
	var poststr = "display_type1=" + encodeURI(display_type);      
	//alert(poststr);
  makePOSTRequest('src/php/module_live_vehicles.php', poststr);
}

function filter_live_vehicle(obj)
{  
  //alert("filter live vehicle");
  var obj=document.forms[0].elements['live_vehicles[]'];
  var result=checkbox_selection(obj);
  //alert(result);
  
  var s1 = result.split(',');
  var time_int;
  //alert("s1 len="+s1.length);
  
  time_int = document.forms[0].autoref_combo.value;    // TIME INT VALUE BEFORE
  
  if(s1.length>1)
  {
    //alert(time_int);
    if(time_int>0 && time_int < 120)
    {
      document.forms[0].autoref_combo.value = 120;
    } 
  }      
  if(s1.length>30)
  {
    alert("Please select maximum 30 Vehicles at a time");
    return false;
  } 
 
  //document.getElementById('ref_time').style.display= '';
  
  //time_int = document.forms[0].autoref_combo.value;     // TIME INT VALUE AFTER
  //alert(time_int);
  if(time_int == 0)
  {
    document.getElementById('ref_time').innerHTML = "Refresh Time : (disabled)";
  }
  else if(time_int >0 && time_int< 60)
  {    
    document.getElementById('ref_time').innerHTML = "Refresh Time : ("+time_int+" secs)";
  }
  else
  {
    time_int = time_int / 60;
    document.getElementById('ref_time').innerHTML = "Refresh Time : ("+time_int+" mins)";
  }
  
  imei_data = result;
  //var refresh_rate = document.forms[0].autoref_combo.value;
  //alert("result="+result);  
  //var s1 = result.split(',');
  
  close_popup();
  //alert("s1.len="+s1.length);
  
	if (GBrowserIsCompatible()) 
  {	  			
  		//alert("in GBrowserIsCompatible")
  		map.clearOverlays();	
  }	
  /*if((browser=="Microsoft Internet Explorer") && (version>=4)) // for internet xeplorer
	{
		alert("Retrieving data ..... plz wait!");
	} */ 
  document.getElementById('prepage').style.visibility='visible';
    
  /*if(s1.length>0)
  {
  	//alert("In Moving V");  		
      
    for(var i=0;i<s1.length;i++)
    {
      var s2 = s1[i].split('#');
      imei_tmp = s2[0];
      date_tmp = s2[1];       
         
      alert("In Moving:"+imei_tmp);
      //alert("date_tmp1="+date_tmp);
      movingVehicle_prev(result);    // FOR MOVING VEHICLE FOR NOW       
      //LP_prev('.$stopped_vimei[$i].')              
    }
  }   */  
  
  pt = null;
  imei1 = null;
  vname1 = null;
  speed1 = null;
  fuel1 = null;
  datetime1 = null;
  marker1 = null;
  
  pt = new Array();        //  RE-Initialise marker array vairables
  imei1 = new Array();
  vname1 = new Array();
  speed1 = new Array();
  fuel1 = new Array();
  datetime1 = new Array();
  marker1 = new Array(); 
  
  point_prev = null;
  vid_prev = null;
  dist_prev = null;
  marker_prev = null;
  label_prev = null;
  angle_prev = null;
  trail_flag = false;
    
  point_prev = new Array();   //  RE-Initialise trail array vairables
  vid_prev = new Array();
  dist_prev = new Array();
  marker_prev = new Array();
  label_prev = new Array();
  angle_prev = new Array();
  date_prev = new Array(); 
  
  movingVehicle_prev();    // FOR MOVING VEHICLE FOR NOW 
}

function auto_refresh_moving_data()
{
	/*if (GBrowserIsCompatible()) 
  {	  			
  		//alert("in GBrowserIsCompatible")
  		//map.clearOverlays();	
  } */ 
  movingVehicle();
}

function select_all_vehicles()
{
	//alert("K:"+obj);  	
  var obj = document.forms[0];
  //alert("c1"+obj.all);  	
	if(obj.all.checked)
	{
		var i;
		var s = obj.elements['live_vehicles[]'];
		//alert("s1="+s);

		if(s.length!=undefined)
		{
      for(i=0;i<s.length;i++)
  			s[i].checked=true;
		}
		else
		{
		  s.checked=true;
    }  		
	}
	
	if(!obj.all.checked)
	{  		
    var i;
		var s = obj.elements['live_vehicles[]'];
		//alert("s2="+s);
		
		if(s.length!=undefined)
		{      
      for(i=0;i<s.length;i++)
  			s[i].checked=false;
  	}
  	else
  	{
  	  s.checked=false;
    }
	}
}   
///////////////////////////////////////////////////////////////


function initialize() 
{
	//alert("initialize");
  document.getElementById("map").style.display="";
	//alert(document.getElementById("map").style.display);
	
  if (GBrowserIsCompatible())
	{	  
		//alert("is compatible");
    map = new GMap2(document.getElementById("map"));		
	
		//alert("map:"+map);
    //var mining_test=document.getElementById("category").value;
		//alert("test"+mining_test);
		/*if(mining_test=='5' || (document.getElementById("mining_user").value==5))
		{show_milestones();}
		else /////// for other users   */
		//{
			map.setCenter(new GLatLng(23.674712836608773, 77.783203125), 5);
			map.enableContinuousZoom();
		//}			  
		/*if(document.thisform.GEarthStatus.value == 1) //////////for google earth
		{
			var mapui = map.getDefaultUI();
			mapui.maptypes.physical = false;
			map.setUI(mapui);			
			map.removeMapType(G_SATELLITE_MAP);
			map.removeMapType(G_HYBRID_MAP);			
			map.setMapType(G_SATELLITE_3D_MAP);
		}*/	
		//else ///////// for other format
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


var startup_var;    
var newurl;      ///////for change url every time
newurl=0;
var delaycnt=0;
var thisdest;
var thismode;
var thisaccess;
var timer;
var TryCnt;
var MAX_TIMELIMIT=1000;


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
	//alert("Please wait sending request...");
	
  var xmlhttp=false;
	var status = false;
	var xmlDoc=null;		
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') 
	{
		try
		{
			xmlhttp = new XMLHttpRequest();
		} 
		catch (e)
		{
			xmlhttp=false;
		}
	}
	if (!xmlhttp && window.createRequest)
	{
		try 
		{
			xmlhttp = window.createRequest();
		} 
		catch (e)
		{
			xmlhttp=false;
		}
	}
  
	newurl++;	
	xmlFile=xmlFile+"?newurl="+newurl; 
	xmlhttp.open("GET",xmlFile,false);
	xmlhttp.send(null);
	var finalStr = xmlhttp.responseText	
	//alert("final_str="+finalStr);	
	if (window.DOMParser)
	{
		parser=new DOMParser();
		xmlDoc=parser.parseFromString(finalStr,"text/xml");
	}
	else // Internet Explorer
	{
		try
		{
			xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
			xmlDoc.async="false";
			status = xmlDoc.loadXML(finalStr);
		}
		catch(e1)
		{
		 xmlDoc=null;
		}
		if(status==false)
	    {
			xmlDoc=null;
		}
	}                 
	  return xmlDoc;
};	

function verify()    //for Internet Explorer
{
	if (xmldoc.readyState != 4)
	{
		return false;
	}
}

////////////////////////////

function movingVehicle_prev()
{
	//alert("MOVING1");
  //alert("In Moving Visible="+document.getElementById('prepage').style.visibility);
	//alert(" DIST IN movingVehicle_prev ="+dist+ " tmp_dist="+tmp_dist);
	//alert("in mprev");
	
	document.form1.mapcontrol_startvar.value=1;
	startup_var = 1;
	//map.clearOverlays();
	
	//document.forms[0].vehicleSerial.value = imei_tmp;
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
	
	//auto_refresh();
	//alert("after load");
}

function movingVehicle()
{	
	//alert("in moving2");
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
	
	//var vid;
	//vid = document.forms[0].vehicleSerial.value;
	//alert(vid+".xml");
 	////////// CALL MAIN FUNCTION ///////////////
	//alert("before load call");
	//alert("st="+startdate+" ed="+enddate);
	//var access = document.forms[0].access.value;
	load_live(dmode,startdate,enddate,pt_for_zoom,zoom_level,status);
 	document.form1.current_vehicle=1;
	//alert("MOVING2");
}


 ////////////////function load /////////////////////////////////////////////
function load_live(dmode,startdate,enddate,pt_for_zoom,zoom_level,status)
{
	//alert("in load");
	marker_type = lvIcon1;
  Load_MovingData(startdate,enddate,pt_for_zoom,zoom_level,status);	
	//alert("MOVING3");
	auto_refresh();
}

 
var dist_array = new Array();

function Load_MovingData(startdate,enddate,pt_for_zoom,zoom_level,status)
{  
	//alert("In load moving data");
	
  if (GBrowserIsCompatible()) 
  {	  			
  		//alert("in GBrowserIsCompatible")
  		//map.clearOverlays();	
  		//alert('user_date='+user_dates.length+'vehicleserial='+vehicleSerial);
  		if(imei_tmp!=null)
  		{
  		 //alert('check');
        var date = new Date();
        // COPY ORIGINAL XML FILE        
        var dest = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml"
       
        dmode = 1; 
        thisdest = dest;        
        //thisaccess = access;
        //var dest = "xml_tmp/filtered_xml/tmp_1295185453465.xml" ;
        //alert("d="+dest);        
        
        // MAKE FILTERED COPY        
        var poststr = "xml_file=" + encodeURI( dest )+
                "&mode=" + encodeURI( dmode )+
                "&vserial=" + encodeURI( imei_data )+
                "&startdate=" + encodeURI( startdate )+
                "&enddate=" + encodeURI( enddate );                       
                                                                   
        //alert("poststr1="+poststr);
        makePOSTRequestMap('src/php/get_filtered_xml_live.php', poststr);
        
		    //makePOSTRequestMap('src/php/get_filtered_xml.php', poststr);			
			  thisdest = "./../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
        TryCnt =0;
        clearTimeout(timer);
        timer = setTimeout('displayInfo()',1000);        
         //displayInfo();
        //alert("poststr2="+poststr);
        //alert("after req1");        
        
        //TryCnt =0;
        //clearTimeout(timer);
        
        //alert("before displayinfo1");
        //timer = setTimeout('displayInfo()',3000);
        //timer = setTimeout('displayInfo()',1000);
        //setTimeout('displayInfo2()',1000);               
        //alert("after displayinfo1");
       // timer = setTimeout('shams()',1000);
        //timer = setTimeout('shams()',1000);
        //alert("after displayinfo");
        //alert("after req2");	 
      } // if vid closed
   } //is compatible closed
} //function load1 closed

/*function test()
{
  alert("testOK");
}*/

/*function displayInfo2()
{
  alert("in displayInfo2");
}*/

function displayInfo()
{  
  //alert("in displayInfo main");
  var lat_arr = new Array();
  var lng_arr = new Array();
  var vid_arr = new Array();
  var vehiclename_arr = new Array();
  var speed_arr = new Array();
  var datetime_arr = new Array();
  var place_arr = new Array();
  var fuel_arr = new Array();
  var vehicletype_arr = new Array();
  var running_status_arr = new Array();
  var Final_DateTime=new Array(); 
  
  /*var lat_arr = "";
  var lng_arr = "";
  var vid_arr = "";
  var vehiclename_arr = "";
  var speed_arr = "";
  var datetime_arr = "";
  var place_arr = "";
  var fuel_arr = "";
  var vehicletype_arr = "";
  var Final_DateTime="";*/  
      
  var xml_data; 
  var DataReceived = false; 
   
  try
  {
    var bname = navigator.appName;
    //alert("bname="+bname);      
    /*if (bname == "Microsoft Internet Explorer")
    {
      alert("Wait for data, please use Mozilla for better compatibility");
    }//alert(bname);   */
              
    var xmlObj = null;        
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
        if(TryCnt<=MAX_TIMELIMIT)
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
      //alert("marker length :"+xml_data.length);
      //var xml_data1 = xmlObj.getElementsByTagName("t1");
      var xml_data1 = xmlObj.documentElement.getElementsByTagName("a1");
	    // alert("length 2:"+xml_data1.length);
      if(xml_data1.length>0)
      {
         /* if (GBrowserIsCompatible()) 
          {	  			  		      
  		      map.clearOverlays();
  		    } */
              
        //alert("A");
        DataReceived = true;
      }
      else
      {
        //alert("B:"+TryCnt);
        if(TryCnt<=MAX_TIMELIMIT)
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
		//alert("file not found");
	}	
    
  /*if (bname == "Microsoft Internet Explorer")
  {
    alert("Data Received");
  }	*/								
				
	//alert("xml data length="+xml_data.length);
	
  if((((xml_data.length==0) || (xml_data.length==undefined)) && (DataReceived==true)) || (TryCnt>=MAX_TIMELIMIT))
	{	
	  //alert("No Data Found");
    document.getElementById('prepage').style.visibility='hidden';	
    clearTimeout(timer);	
    var poststr = "dest=" + encodeURI( thisdest );
	  makePOSTRequest('src/php/del_xml.php', poststr);								
	}
	else  if(DataReceived==true)
	{	
	  //alert("data recieved");
	  
    clearTimeout(timer);
		var len2=0;
		
    /////////////// GET IO ///////////////
    var imei = xml_data[0].getAttribute("vehicleserial")		
		var str = imei+",fuel"; 
         
    //// GET VNAME //////////////
   
    var strURL="src/php/map_get_vname.php?imei="+imei;
    //alert("strurl:"+strURL);
    var req = getXMLHTTP();
    req.open("GET", strURL, false); //third parameter is set to false here
    req.send(null);  
    var vname = req.responseText; 
             
    ////GET IO /////////////////
    strURL="src/php/map_get_io.php?content="+str;
    //alert("strurl:"+strURL);
    req = getXMLHTTP();
    req.open("GET", strURL, false); //third parameter is set to false here
    req.send(null);  
    var io = req.responseText;
    //var io="io8";
    //var io="1";
    //var vname="test";
    //alert("vname="+vname+" ,io1="+io); 
    
    if(io=="")
      io="io8";       
    //alert("io2="+io);   
               
    //alert("io2="+io); 
    /////////////////////////////////////////////		
		//alert("xml data len="+xml_data.length);
		
		for (var k = 0; k < xml_data.length; k++) 
		{																													
			//alert("t11111111==="+xml_data[k].getAttribute("datetime"));						
			lat_tmp = xml_data[k].getAttribute("lat");
			lng_tmp = xml_data[k].getAttribute("lng");	
						
			lat_arr[len2] = xml_data[k].getAttribute("lat");
			lng_arr[len2] = xml_data[k].getAttribute("lng");
			vid_arr[len2] = xml_data[k].getAttribute("vehicleserial");
			vehiclename_arr[len2] = xml_data[k].getAttribute("vname");
			//alert("v000="+vehiclename_arr[len2] );
			speed_arr[len2] = Math.round(xml_data[k].getAttribute("speed")*100)/100;
			if( (speed_arr[len2]<=3) || (speed_arr[len2]>200))
			{
				speed_arr[len2] = 0;
			}
			
      fuel_arr[len2] = xml_data[k].getAttribute(io);	
			if(fuel_arr[len2] <30)
			{
				fuel_arr[len2] =0;
			}											
			vehicletype_arr[len2] = xml_data[k].getAttribute("vehicletype");
			datetime_arr[len2] =  xml_data[k].getAttribute("datetime");
			running_status_arr[len2] = xml_data[k].getAttribute("running_status");
			//alert("Status1="+running_status_arr[len2]);
			//alert("lt=="+lat_arr[len2]+"lng_arr(len2) ="+lng_arr[len2]+"vid arr="+vid_arr[len2]);
			len2++;		
		}	//XML LEN LOOP CLOSEDhaa
		
	  //var poststr = "dest=" + encodeURI( thisdest );
    //makePOSTRequest('src/php/del_xml.php', poststr);
	   
	  /*var y1,m1,d1,hr1,min1,sec1;
	  var y2,m2,d2,hr2,min2,sec2;	   
	      
    // XML VDATE
    var tmp1 = datetime_arr.split(' ');
    var tmp2 = tmp1[0].split('-');
    y1 = tmp2[0];
    m1 = tmp2[1];
    d1 = tmp2[2];
    
    var tmp3 = tmp1[1].split(':');
    hr1 = tmp3[0];
    min1 = tmp3[1];
    sec1 = tmp3[2];
    
    // LOGIN VDATE
    tmp1 = date_tmp.split(' ');
    tmp2 = tmp1[0].split('-');
    y2 = tmp2[0];
    m2 = tmp2[1];
    d2 = tmp2[2];
    
    tmp3 = tmp1[1].split(':');
    hr2 = tmp3[0];
    min2 = tmp3[1];
    sec2 = tmp3[2];
    
    alert("D1="+y1+"-"+m1+"-"+d1+" "+hr1+":"+min1+":"+sec1);
    alert("D2="+y2+"-"+m2+"-"+d2+" "+hr2+":"+min2+":"+sec2);
    
    var Date1 = new Date(y1, m1, d1, hr1, min1, sec1);
    var Date2 = new Date(y2, m2, d2, hr2, min2, sec2);
    
    alert("date1="+Date1+" ,date2="+Date2); */          
     	  
    //if(vid_arr.length>0 && lat_arr.length>0 && lng_arr.length>0)
    //if((len2>0) && (Date1 > Date2))   // MATCH FOR LATEST DATA
    if(len2>0)
    {	      
      //alert("record found");
      clearTimeout(timer);
      //alert("data found");
      //document.form1.status.value = "["+ vid_arr[0]+ "]"+"-("+lat_arr[0]+","+lng_arr[0]+")";
      //alert("LPCOUNT="+lp_count);
      //alert("before plottin markers "+len2+" "+lat_arr+" "+lng_arr+" "+vid_arr+" "+vehiclename_arr+" "+speed_arr+" "+datetime_arr+"  VTYPE="+vehicletype_arr);				
      var flag=1;
      getxml_MovingData(len2, flag, lat_arr, lng_arr, vid_arr, vehiclename_arr, speed_arr, datetime_arr, fuel_arr, running_status_arr);	  //HERE ARR IS JUST NORMAL VARIABLE NOT ARRAY
      //alert("K");
      document.getElementById('prepage').style.visibility='hidden';	
    }					
	} // ELSE CLOSED       		
}

function getxml_MovingData(len2, flag1, lat_arr, lng_arr, vid_arr, vehiclename_arr, speed_arr, datetime_arr, fuel_arr, running_status_arr)
{
	if(vid_arr.length<=0)
	{
		flag1=0;
	}	
	
	if(flag1)
	{
		var point;	
		//alert("startup="+startup_var);
		if(startup_var == 1)
		{
			var bounds = new GLatLngBounds();
			for(var z=0;z<lat_arr.length;z++)
			{
				point = new GLatLng(parseFloat(lat_arr[z]),
							parseFloat(lng_arr[z]));

				bounds.extend(point); 
			}			
			var center = bounds.getCenter(); 			
			
			if(len2>0 && len2<2)
				var zoom = map.getBoundsZoomLevel(bounds)-7; 
			else if(len2>2 && len2<6)
				var zoom = map.getBoundsZoomLevel(bounds)-1; 
			else
				var zoom = map.getBoundsZoomLevel(bounds)-1;							
			/*if(access=="Zone")
			{
				//alert("access="+access+"ms="+ms);
				show_milestones();
				//alert("access="+access);
			//map.setCenter(center,zoom); 
			}
			else
			{*/
				map.setCenter(center,zoom);
			//}
			startup_var = 0;
		}		
		
		//alert("L0");
    Moving_DataMarkers(lat_arr,lng_arr,vid_arr,vehiclename_arr,speed_arr,datetime_arr, fuel_arr, len2, running_status_arr);

		var zoom;
		var event = 0;
		var newzoomlevel=0;		
		
		//alert("L1");
    getLandMark1(event,newzoomlevel);
		
		////////////////////// CALL GET LANDMARK ON EVENT LISTENER FOR LAST POSITION //////////////////////////
		GEvent.addListener(map, 'zoomend',function (oldzoomlevel,newzoomlevel) 
		{
			var event =1;
			getLandMark1(event,newzoomlevel);
		}); //GEvent addListener												
	}//if flag1 closed			
 	//document.getElementById('prepage').style.visibility='visible';								
		//////////////////////////////////////////////////////////////			
} //FUNCTION getxmlDataTrack
 ///////////////////////track markers////////////////////////////////////

var vlist = "";

function Moving_DataMarkers(lat_arr,lng_arr,vid_arr,vehiclename_arr,speed_arr,datetime_arr, fuel_arr, len2, running_status_arr)
{	
	var gmarkersA = new Array();      
	var gmarkersB = new Array();  
	var gmarkersC = new Array();  
	
	var j = 0;
	var colr = ["#00FF66","#0066FF","#FF0000","#33FFFF","#FF33CC","#9966FF","#FF9900","#FFFF00"];
	var i,vehiclename,speed,point,datetime,place,marker,polyline,last,running_status1;

	var vid1=0;
	var vid2=0;
	var pt = new Array();
	var value = new Array();
	var poly = new Array();
	var dist = 0;
	var lastmarker = 0;
	var p = 0;
	var fuel=0;
  var lat1,lat2,lng1,lng2,coord;	  
	
  vlist ="<table style='font-size:11px;'><tr style='background-color:004D96;color:#FFFFFF'><td align=center><strong>LIVE TRACKING LEGEND</strong></td></tr></table><br><table style='font-size:11px;'><tr><td></td></tr>";
  
  if(!(document.getElementById('trail_path').checked))  // IF TRAIL PATH NOT CHECKED, CLEAR PREVIOUS OVERLAYS
  {
  	if (GBrowserIsCompatible()) 
    {	  			
    		//alert("in GBrowserIsCompatible")
    		map.clearOverlays();	
    }	    
  }		
      
  if(!(document.getElementById('trail_path').checked))
  {		
    //alert("in reint2");	 	  
    point_prev = null;
	  vid_prev = null;
	  dist_prev = null;
	  marker_prev = null;
	  label_prev = null;
	  angle_prev =null;
	  date_prev = null;	  
    
    point_prev = new Array();           
		vid_prev = new Array();
		dist_prev = new Array(); 
		marker_prev = new Array();
		label_prev = new Array();
		 angle_prev = new Array(); 
		date_prev = new Array();
		
    for(var j=0;j<vid_prev.length;j++)
    {
       map.removeOverlay(marker_prev[j]);
       map.removeOverlay(label_prev[j]);
    }	
    
    trail_flag = false;	
	}			
    
  for (i = 0; i < len2; i++) 
	{		
    var total_dist = 0;
    
    vid = vid_arr[i];
		vehiclename = vehiclename_arr[i];		
		speed = speed_arr[i];
		if(speed<=3)
			speed = 0;

		point = new GLatLng(parseFloat(lat_arr[i]),parseFloat(lng_arr[i]));		
		
    datetime = datetime_arr[i];		
		fuel = fuel_arr[i];	
		//vehicletype = vehicletype_arr[i];
		running_status1 = running_status_arr[i];
		//alert("point recieved="+point);
		pt[i] = point;
		place=0;				
    
    // PLOT LABEL
    //var label_detail = "<font color=#006600><strong>"+vehiclename+"</strong></font><font color=#FF0000><strong>["+running_status1+"]</strong></font>";
    
    if(running_status1 == "Running")
    {
      var label_detail = "<font color=blue><strong>"+vehiclename+"</strong></font><font color=red><strong>("+running_status1+")</strong></font>";
    }
    else
    {
      var label_detail = "<font color=blue><strong>"+vehiclename+"</strong></font><font color=red><strong>("+running_status1+")</strong></font>";      
    }        
    
    var label = new ELabel(point, label_detail, "style1"); 
    map.removeOverlay(label)
    label.pixelOffset=new GSize(10,10);
    map.addOverlay(label);
    // PLOT LABEL CLOSED
        
    if(document.getElementById('trail_path').checked)
    {
      //alert("In trail path,vid_prev="+vid_prev.length);
      for(var j=0;j<vid_prev.length;j++)
      {
        //alert("vid_prev="+vid_prev+" ,vid="+vid);
        
        if(vid_prev[j] == vid)
        {
          //alert("In Del Marker :"+marker_prev[j]+" ,pt="+point_prev[j]);          
          map.removeOverlay(marker_prev[j]);
          map.removeOverlay(label_prev[j]);
          //alert("Deleted Date:"+date_prev[j]+" pt_prev="+point_prev[j]+" ,point="+point);          
          
          //if(running_status1=="Running")
          //{
            // POLYLINE CODE
                var polyline = new GPolyline([
          		  point_prev[j],
          		  point
        		], "#ff0000", 2);
        		
            //map.addOverlay(polyline);		//COMMENTED TO PREVENT TRAIL
            
            //ADD DIRECTION ARROWS
            lat1 = point_prev[j].y;
        		lng1 = point_prev[j].x;
        
        		lat2 = point.y;
        		lng2 = point.x;	
        
        		var yaxis = (lat1 + lat2)/2;
        		var xaxis = (lng1 + lng2)/2;
        				
        		coord = new GLatLng(yaxis,xaxis);
        		var angle_t = Math.atan( (lat2-lat1)/(lng2-lng1) );
        		var angle_deg = 360 * angle_t/(2 * Math.PI);
        
        		if((lng2-lng1)<0)
        		{
        				angle_deg = 180 + angle_deg;
						//alert("one");
        		}
        		else if((lat2-lat1)<0)
        		{
        				angle_deg = 360 + angle_deg;
						//alert("two");
        		}
        
        		angle_deg = Math.round(angle_deg,0);				
				//alert("angle="+angle_deg);
        		
				if( (isNaN(angle_deg)) && !(isNaN(angle_prev[j])) )
				{
					angle_deg = angle_prev[j];
				}
				/*var IconArrow = new GIcon(); 
        		
        		//var a=
        		//alert("val="+a);
        		IconArrow.image = "images/arrow_images/"+angle_deg+'.png';
        		IconArrow.iconSize = new GSize(20, 19);
        		IconArrow.iconAnchor = new GPoint(10, 10);	*/		
        
        		//var marker2 = new GMarker(coord, IconArrow);       		
		       // map.addOverlay(marker2);		//COMMENTED TO PREVENT TRAIL ANGEL
            /////// DIRECTION ARROW CLOSED
            
             
            var distance = calculate_distance(point_prev[j].y, point.y, point_prev[j].x, point.x);
            distance += dist_prev[j];
            total_dist += distance;
          //}
              		
          //alert("tot_dist="+total_dist);
          // REINITIALISE VARIABLES
          //map.removeOverlay(marker_prev[j]);
          //map.removeOverlay(label_prev[j]);
          //alert("Deleted Date:"+date_prev[j]);

          if(point_prev[j]!=point)
          {
            point_prev[j] = point;
        		vid_prev[j] = vid;
        		dist_prev[j] = total_dist;
        		//marker_prev[j] = marker1[j];
        		label_prev[j] =label;
        		date_prev[j] = datetime;
				angle_prev[j] = angle_deg;
      		}
      		
          trail_flag = true;      		
            		
          break;
    		}
      }
    }    
    //
                                                                                                                              
    marker = Create_MovingDataMarkers(angle_deg, point, vid, vehiclename, speed, datetime, fuel, len2,gmarkersC,p, running_status1, total_dist);
        
    // POST ASSIGNMENT OF MARKER_PREV
    if(document.getElementById('trail_path').checked)
    {
      //alert("In trail path,vid_prev="+vid_prev.length);
      for(var j=0;j<vid_prev.length;j++)
      {
        //alert("vid_prev="+vid_prev+" ,vid="+vid);
        if(vid_prev[j] == vid)
        {
          marker_prev[j] = marker1[p];
        }
      }
    }
    ////////////////////////////////
            						
		gmarkersA.push(marker);

		if(i==len2-1)
		{
		  /*alert("in reint1");
		  point_prev.clear();
		  vid_prev.clear();
		  dist_prev.clear();
		  marker_prev.clear();
		  label_prev.clear();*/
		  
      /*point_prev = new Array();
		  vid_prev = new Array();
		  dist_prev = new Array();
		  marker_prev = new Array();
		  label_prev = new Array();*/		  
          			
      for(var m=0;m<gmarkersA.length;m++)
			map.addOverlay(gmarkersA[m]);				
			document.getElementById('prepage').style.visibility='hidden';					
		}
		
		//point_prev[] = point;
		//vid_prev[] = vid;
		
    //if((document.getElementById('trail_path').checked) && (!trail_flag))
    if(!trail_flag)
    {		          
      //alert("In update:"+marker1[p]+" ,"+point);
      //alert("Stored first time in array");
      point_prev[i] = point;
  		vid_prev[i] = vid;
  		dist_prev[i] = total_dist;
  		marker_prev[i] = marker1[i];
  		label_prev[i] = label;
      date_prev[i] = datetime;  		
		}
		
		p++;
	} //for i loop closed	
	
  vlist += "</table>";  
  
  // SHOW VEHICLES IN DIV
  document.getElementById('examplePanel2').innerHTML = vlist;  
  ///////////////////////
    
  /*if(!(document.getElementById('trail_path').checked))
  {		
    //alert("in reint2");	 	  
    point_prev = null;
	  vid_prev = null;
	  dist_prev = null;
	  marker_prev = null;
	  label_prev = null;
	  date_prev = null;	  
    
    point_prev = new Array();           
		vid_prev = new Array();
		dist_prev = new Array(); 
		marker_prev = new Array();
		label_prev = new Array();
		date_prev = new Array();
		
    for(var j=0;j<vid_prev.length;j++)
    {
       map.removeOverlay(marker_prev[j]);
       map.removeOverlay(label_prev[j]);
    }	
    
    trail_flag = false;	
	}	*/		
		
} //track markers closed
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////


var lat1 = 0;
var lng1 = 0;
var lat2 = 0;
var lng2 = 0;
var coord;
pt = new Array();
imei1 = new Array();
vname1 = new Array();
speed1 = new Array();
fuel1 = new Array();
datetime1 = new Array();
marker1 = new Array();
//var mm;
var rect;	

function Create_MovingDataMarkers(angle_deg, point, imei, vehiclename, speed, datetime, fuel, len2, gmarkersC,p, running_status, total_dist) 
{		
	total_dist = Math.round((total_dist)*100)/100;
  //alert("in createMarkerLP");	
	//var vIcon;
      		        			
  if(isNaN(angle_deg))
  {
    //alert("No Angle found");
	  if(running_status == "Running")
	  {
		vIcon= new GIcon(lvIcon1);    //BLINK DOT ICON
	  }
	  else if(running_status == "Idle")
	  {
		vIcon= new GIcon(lvIcon2);
	  }
	  else
	  {
		vIcon= new GIcon(lvIcon3);
	  }		
  }
  else
  {
  	arrowIcon.image = "images/arrow_images/"+angle_deg+'.png';
	vIcon= new GIcon(arrowIcon);
  }
  
/*	
  if(running_status == "Running")
  {
    vIcon= new GIcon(lvIcon1);    //BLINK DOT ICON
  }
  else if(running_status == "Idle")
  {
    vIcon= new GIcon(lvIcon2);
  }
  else
  {
    vIcon= new GIcon(lvIcon3);
  }
 */
 
 //vIcon = IconArrow;
	
	pt[p] = point;
	imei1[p] = imei;
	vname1[p] = vehiclename;
	speed1[p] = speed;
	datetime1[p] = datetime;
	fuel1[p] = fuel;
	
	//var lt_1 = Math.round(point.y*100000)/100000; 
	//var ln_1 = Math.round(point.x*100000)/100000;
	
	var lat = point.y; 
	var lng = point.x;	

	var marker = new GMarker(point, vIcon);
	marker1[p] = marker;
                  
  
  var img = "";
  ////CONCAT GLOBAL VLIST STRING
  if(running_status == "Running")
  {
    img = "<img src=./images/live/live_vehicle.gif width=8px height=8px>&nbsp;";
  }
  else if(running_status == "Idle")
  {
    img = "<img src=./images/live/lp_vehicle1.gif width=8px height=8px>&nbsp;";
  }
  else                                                                                                                                                                                                                              
  {
    img = "<img src=./images/live/lp_vehicle2.gif width=8px height=8px>&nbsp;";
  }
 
  vlist += "<tr><td><a href='#' style='text-decoration:none;' Onclick='javascript:Prev_PlotLastMarkerWithAddress(\""+vIcon+"\",\""+lat+"\",\""+lng+"\",\""+p+"\",\""+imei+"\",\""+vehiclename+"\",\""+speed+"\",\""+datetime+"\",\""+fuel+"\",\""+running_status+"\",\""+total_dist+"\");'>"+img+"<font color=#006600>"+vehiclename+"</font>&nbsp;&nbsp;<font color=red>("+running_status+")</font></a></td></tr><tr><td><a href='#' style='text-decoration:none;' Onclick='javascript:Prev_PlotLastMarkerWithAddress(\""+vIcon+"\",\""+lat+"\",\""+lng+"\",\""+p+"\",\""+imei+"\",\""+vehiclename+"\",\""+speed+"\",\""+datetime+"\",\""+fuel+"\",\""+running_status+"\",\""+total_dist+"\");'><font color=blue>("+imei+")</font></a></td></tr><tr><td>&nbsp;</td></tr>";
  //alert("vlist="+vlist);
  //////////////////////////////
  
  if(running_status == "Running")
  {
    PlotLastMarkerWithAddress(point, vIcon, marker, imei, vehiclename, speed,datetime, fuel, running_status, total_dist);
  }
  
  GEvent.addListener(marker, 'mouseover', function()
  {			
  	PlotLastMarkerWithAddress(point, vIcon, marker, imei, vehiclename, speed,datetime, fuel, running_status, total_dist);
  });  

	return marker;		
}				

/////////////////////////////////////////////////////////////////////////////////////////////////////

function Prev_PlotLastMarkerWithAddress(vIcon, lat, lng, mcounter, imei, vehiclename, speed, datetime, fuel, running_status, total_dist)
{
  lat = trim(lat);
  lng = trim(lng);
  imei = trim(imei);
  vehiclename = trim(vehiclename);
  speed = trim(speed);
  datetime = trim(datetime);
  fuel = trim(fuel);
  running_status = trim(running_status);
  
  var point = new GLatLng(parseFloat(lat),parseFloat(lng));
  /*var vIcon;
  
  if(running_status == "Running")
  {
    vIcon= new GIcon(lvIcon1);    //BLINK DOT ICON
  }
  else
  {
    vIcon= new GIcon(lvIcon2);
  } */
  //var marker = new GMarker(point, vIcon);  
  
  PlotLastMarkerWithAddress(point, vIcon, marker1[mcounter], imei, vehiclename, speed, datetime, fuel, running_status, total_dist);
}

var ad=0;
var place;
var address1=0;

////////////////////////// PLOT TRACK MARKERS WITH ADDRESSES ////////////////////////////////////////
function PlotLastMarkerWithAddress(point, Icon, marker, imei, vehiclename, speed,datetime, fuel, running_status, total_dist) 
{
 //alert("IN PLOT:"+point+":"+Icon+":"+marker+":"+imei+":"+vehiclename+":"+speed+":"+datetime+":"+fuel+":"+running_status);
 
 //:(17.66392, 75.8931):[object Object]:[object Object]:359231030166217:ACC-BC-95-100-R4:0:2011-12-16 11:23:43:0
 
 //var Icon= new GIcon(lvIcon1);
 //var marker = new GMarker(point, Icon);
 //alert("vIcon="+Icon+" ,marker="+marker);
 
 var accuracy;
 var largest_accuracy;	   
 var delay = 100;

 var geocoder = new GClientGeocoder();
 var address_tmp;
 var address1_tmp;
 var BadAddress=0;

 geocoder.getLocations(point, function (result) {

 if (result.Status.code == G_GEO_SUCCESS) // OR !=200
 {
	 var j;
   //j=0;
   // Loop through the results, looking for the one with Accuracy = 1

   for (var i=0; i<result.Placemark.length; i++)
   {
    accuracy = result.Placemark[i].AddressDetails.Accuracy;
    
    address_tmp = result.Placemark[i];
    address1_tmp = address_tmp.address;

	    //alert("address1_tmp="+address1_tmp+" accuracy="+accuracy);         
		if(i==0)
		{
			largest_accuracy = accuracy; 
			j = i;

			if ((address1_tmp.indexOf("NH") !=-1) || (address1_tmp.indexOf("National Highway") !=-1) || (address1_tmp.indexOf("State Highway") !=-1))
			{
				BadAddress = 1;
			}
		}

		else 
	    {	
		   //alert(" largest accuracy="+largest_accuracy+" accuracy="+accuracy+" i="+i);
			if((largest_accuracy < accuracy) || ((BadAddress == 1) && (accuracy>2)))
			{
				largest_accuracy = accuracy;
				//alert("i="+i);
				j = i;
				//alert("j1========="+j);
				if ((address1_tmp.indexOf("NH") !=-1) || (address1_tmp.indexOf("National Highway") !=-1) || (address1_tmp.indexOf("State Highway") !=-1))
				{
					BadAddress = 1;
				}
				else
				{
					BadAddress = 0;
				}
			}
		}
   }
	   //i=i-1;	   
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
		var place;
		
		///////////////////////////// SELECT LANDMARK OR GOOGLE PLACE CODE /////////////////////////////////////////////////////
		/// IF DISTANCE CALCULATED THROUGH FILE IS LESS THAN 1 KM THEN DISPLAY LANDMARK OTHERWISE DISPLAY GOOGLE PLACE /////////
		
		var lt_original = point.y;
		var lng_original = point.x;
		var str = lt_original+","+lng_original;
		
		//var access2=document.thisform.access.value;
			//alert('access='+str);

		//if(access2=="Zone")
		//{
		//	var strURL="src/php/select_mining_landmark.php?content="+str;
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

		
    // GET FUEL LEVE LAST POSITION
		str = imei+","+fuel;		
    strURL="src/php/map_fuel_calibration.php?content="+str;	
        
		var req = getXMLHTTP();
		req.open("GET", strURL, false); //third parameter is set to false here
		req.send(null);
		var fuel_level = req.responseText;		
    //////////////////////    
  	//alert("before plot");
    //if(label_type!="Person")
  	//{
  	 //var tab_str = '<div id="tab1" class="bubble" style="height:150px;" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>IMEI</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+imei + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td><font size=2 color=#000000>Status</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+running_status+'</font></td></tr></table></div>';
  	 //alert(tab_str);
    	//var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="height:150px;" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>IMEI</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+imei + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td colspan=3><font color=blue size=2>( '+point.y+', '+point.x+' )</font></td></tr></table></div>');
  	 if((document.getElementById('trail_path').checked) && (running_status=="Running"))
  	 {
  	   if(total_dist==0)
  	   {
  	     total_dist = "less than 1";
  	   }
  	   var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="height:150px;" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>IMEI</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+imei + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td><font size=2 color=#000000>Status</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+running_status+'</font></td></tr><tr><td><font size=2 color=#000000>Distance Covered</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+total_dist+'&nbsp;km</font></td></tr></table></div>');
     }
     else
     {
      var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="height:150px;" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>IMEI</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+imei + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td><font size=2 color=#000000>Status</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+running_status+'</font></td></tr></table></div>');
     }     
    //}
  	/*else
  	{
  		//var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Person</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td colspan=3><font color=blue size=2>( '+point.y+', '+point.x+' )</font></td></tr></table></div>');
  		var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="height:100px;" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Person</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>IMEI</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+imei + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr></table></div>');
  	} */
		//var html = new GInfoWindowTab("Info", '<div id="tab1" class="bubble">Click the "Location" tab to see the minimap</div>');
		//var tab2 = new GInfoWindowTab("Location", '<div id="detailmap" style="height:150px;"></div>');

		//alert(" tab1="+tab1+" tab2="+tab2);
		//var infoTabs = [tab1,tab2];
		var infoTabs = [tab1];

		//alert(" marker="+marker+" infoTabs="+infoTabs);
		marker.openInfoWindowTabsHtml(infoTabs);
		//alert("after plot");

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

		showMinimapRect(detailMap,marker3);   */
     }
  });
}
 //////////////////////////////////////////////////////////////////////////////////////////////////////

function trim(value) 
{
  value = value.replace(/^\s+/,'');
  value = value.replace(/\s+$/,'');
  return value;
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

function LP_prev(vserial)
{
	//alert("lp prev");
  if (GBrowserIsCompatible()) 
  {	  			
  		//alert("in GBrowserIsCompatible")
  		map.clearOverlays();	
  }	
  if((browser=="Microsoft Internet Explorer") && (version>=4)) // for internet xeplorer
	{
		alert("Retrieving data ..... plz wait!");
	}

	//alert("In Moving Visible="+document.getElementById('prepage').style.visibility);
	document.getElementById('prepage').style.visibility='visible';	
	//alert("In Moving Visible="+document.getElementById('prepage').style.visibility);
  //alert(" DIST IN movingVehicle_prev ="+dist+ " tmp_dist="+tmp_dist);
	//alert("in mprev");
	document.form1.mapcontrol_startvar.value=1;
	startup_var = 1;
	map.clearOverlays();
	
	document.forms[0].vehicleSerial.value = vserial;
	//alert("vserial in moving vehicle ="+document.forms[0].vehicleSerial.value);
  // REFRESH VALUES
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

	LP_Vehicle();

	//alert("MOVING1");
	//auto_refresh();
	//alert("after load");
}

function LP_Vehicle()
{	
	//alert("in LP vehicle");
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
	//var access = document.forms[0].access.value;
	load_lp(vid,dmode,startdate,enddate,pt_for_zoom,zoom_level,status);
 	document.form1.current_vehicle=1;

	//alert("MOVING2");
}

 ////////////////function load /////////////////////////////////////////////
function load_lp(vid,dmode,startdate,enddate,pt_for_zoom,zoom_level,status)
{
	//alert("in load");
	marker_type = lvIcon2;
  Load_MovingData(vid,startdate,enddate,pt_for_zoom,zoom_level,status);
  clearTimeout(timer);		
}


//////////////////////////////////////////////////////////////////////////////////////////////////////
/*function calculate_distance(lat1, lat2, lon1, lon2) 
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
}*/ 
 
 
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

function checkbox_selection(obj)
{
	var flag=0;
	var cnt=0;
	var id="";
	if(obj.length!=undefined)
	{
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].checked==true)
			{				
				if(cnt==0)
				{
					id= id + obj[i].value;
					cnt=1;
				}
				else
				{
					id=id +","+ obj[i].value;
				}
				flag=1;
			}	  
		}
	}
	else
	{
		if(obj.checked==true)
		{
			id=obj.value;
			flag=1;
		}
	}
	if(flag==0)
	{
		alert("Please Select Atleast One Option");
		return false;
	}
	else
	{	  
		return id;
	}
}


//////////////////  MAKE POST REQUEST  ///////////////////////

   var http_request = false;
   function makePOSTRequestMap(url, parameters) 
   {
      //alert("IN POST REQ");
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
      
      http_request.onreadystatechange = alertContentsMap;
      http_request.open('POST', url, true);
      http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http_request.setRequestHeader("Content-length", parameters.length);
      http_request.setRequestHeader("Connection", "close");
      http_request.send(parameters);
   }
   
  function alertContentsMap()
  {
    //alert("IN alert CNT");
    if (http_request.readyState == 4) 
    {
       if (http_request.status == 200) 
       {
          result = http_request.responseText;
          //alert(result);
       }
    }
  } 
  
  
  /////////// CODE FOR SELECTING LANDMARK ON ZOOM ///////////////////////////////////////

function getLandMark1(event,newzoomlevel)
{
	//alert("landmark");
  var newzoomlevel= map.getZoom();				
	GDownloadUrl("src/php/select_landmark.php", function(data)
    {								
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
