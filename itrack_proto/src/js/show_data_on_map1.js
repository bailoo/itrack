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


function show_data_on_map(report_format)
{
	var time_zone=document.thisform.time_zone.value;
	var access="1";   /////set access temporarily
	var time_interval=document.thisform.interval.value;  
	var startdateDoc=document.thisform.start_date.value;		
	var enddateDoc=document.thisform.end_date.value;
	
	var startdate = startdateDoc.replace('/', '-');
	startdate = startdate.replace('/', '-');

	var enddate = enddateDoc.replace('/', '-');
	enddate = enddate.replace('/', '-');  
	var imeino1=document.thisform.elements['vehicleserial[]'];		
	
	var display_mode=document.thisform.mode;   /////// 1=last_postoin  and 2=track 
	var vid;
	vid = "";
	for(i=0;i<display_mode.length;i++)
	{if(display_mode[i].checked){var mode=display_mode[i].value;}}

	var num1=0;   var count=0;    var cnt=0

	var dt = DateCheck_1(mode);
	if(dt==true)
	{ 
		if(imeino1.length!=undefined)
		{
			for(i=0;i<imeino1.length;i++)
			{
				if(imeino1[i].checked)
				{
					if(cnt==0)
					{vid =  vid + imeino1[i].value;cnt=1}
					else
					{vid = vid+ "," + imeino1[i].value;}
					count=count+1;
					num1 = 1;
				}
			}
		}
		else
		{
			if(imeino1.checked)
			{vid=vid + imeino1.value;num1 = 1;}
		}
		if(num1==0)
		{
			alert("Please Select At Least One Vehicle");							
			return false;  			
		}
		else if(mode==2 && count>1)
		{
			alert("Please Select One Vehicle For Track");
			return false;
		}
		 
		else(num1==1)
		{			 
			if(report_format=="map_report")
			{
				startup_var = 1;
				var status;
				var time_interval;
				var pt_for_zoom;
				var zoom_level;	
				var n=new Array();

				if(document.forms[0].pt_for_zoom.value==1 && document.forms[0].zoom_level.value==1)
				{status = "ON";}
				else{status = "OFF";  pt_for_zoom = "0";  zoom_level = "0";} 

				//initialize(); 
				var diffdate;
				var difftype;

				if(time_zone=="IST")
				{
					diffdate = 0;
					difftype = 0;
				}
				else if(time_zone=="GMT")
				{
					diffdate = 19800000;
					difftype = 1;
				}
			
				load(vid,mode,startdate,enddate,pt_for_zoom,zoom_level,status,access,time_interval);		
			
			} 
			else if(report_format=="text_report")
			{
				var date = new Date();
				var dest = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
				
				/*var poststr = "vserial=" + encodeURI(vid)+
				"&mode="+encodeURI(mode)+
				"&start_date="+startdate+
				"&end_date="+enddate+
				"&time_zone="+encodeURI(time_zone)+
				"&xml_file="+encodeURI(dest);*/

				//alert(vid)
        if(mode==1)
				{
					document.ld.action="src/php/Last_data_prev.php?vserial="+vid+"&startdate="+startdate+"&enddate="+enddate+"&mode="+mode+"&time_interval="+time_interval+"&time_zone="+time_zone+"&xml_file="+dest;
					document.ld.target="_blank";
					document.ld.submit();
					//makePOSTRequest('src/php/Last_data_prev.php', poststr);
				}
				else if(mode==2)
				{
					
					document.fd.action="src/php/Full_data_prev.php?vserial="+vid+"&startdate="+startdate+"&enddate="+enddate+"&mode="+mode+"&time_interval="+time_interval+"&time_zone="+time_zone+"&xml_file="+dest;
					document.fd.target="_blank";
					document.fd.submit();
					//makePOSTRequest('src/php/Full_data_prev.php', poststr);
				}
			} 
		}
	}
}

function DateCheck_1(b)
{
	var currentTime = new Date();
	var month = currentTime.getMonth() + 1
	var day = currentTime.getDate()
	var year = currentTime.getFullYear() 
  
	  var startdate1;
	  var enddate1;

	  if(b==1 || b==2)
	  {
  		startdate1=document.thisform.start_date.value;		
  		enddate1=document.thisform.end_date.value;
		}
		else
		{
			startdate1 = "";
			enddate1 = "";  
	  }
	
	//alert(" b="+b+" startdate="+startdate1+" enddate="+enddate1);
	
	var startlen = startdate1.length;
	var endlen = enddate1.length;

	if(((startlen > 0)&&(startlen < 10))||((endlen > 0)&&(endlen < 10)))
	{
		alert("Incorrect date  format...enter yyyy-mm-dd");
		return false;
	}

	if(startlen > 0)
	{
		var startday = startdate1.substr(8,2);
		var startmonth = startdate1.substr(5,2);
		var startyr = startdate1.substr(0,4);
		
	//alert("startday="+ startday +" startmonth="+startmonth+" startyr="+startyr);
		if(startyr > year)
		{
			alert("Incorrect Date From Value...Please Enter Again");
			document.thisform.start_date.focus();
			return false;
		}
		if(year == startyr)
		{
			if(startmonth == month)
				if(startday > day)
				{
					alert("Incorrect Date From Value...Please Enter Again");
					document.thisform.end_date.focus();
					return false;
				}
			if(startmonth > month)
			{
				alert("Incorrect Date From Value...Please Enter Again");
				document.thisform.start_date.focus();	
				return false;
			}
		}
		var leapyr=0;
		if(startyr%4 == 0)
		{
			if(startyr%100 != 0)
			{
				leapyr = 1;
			}
			else
			{
				if(startyr%400 == 0)
					leapyr = 1;
				else
					leapyr = 0;
			}
		}
		if((leapyr == 1)&&(startmonth == "02"))
		{
			if(startday > 29)
			{
				alert("Incorrect Date From Value...Please Enter Again");
				document.thisform.start_date.focus();
				return false;
			}
		}
		if((leapyr == 0)&&(startmonth == "02"))
		{
			if(startday > 28)
			{
				alert("Incorrect Date From Value...Please Enter Again");
				document.thisform.start_date.focus();
				return false;
			}
		}
		if((startmonth == "04")||(startmonth == "06")||(startmonth == "09")||(startmonth == "11"))
		{
			if(startday > 30)
			{
				alert("Incorrect Date From Value...Please Enter Again");
				document.thisform.start_date.focus();						
				return false;
			}
		}
	}

	if(endlen > 0)
	{
		var endday = enddate1.substr(8,2);
		var endmonth = enddate1.substr(5,2);
		var endyr = enddate1.substr(0,4);
		if(endyr > year)
		{
			alert("Incorrect Date To Value...Please Enter Again");
			document.thisform.end_date.focus();
			return false;
		}
		if(year == endyr)
		{
			if(endmonth == month)
				if(endday > day)
				{
					alert("Incorrect Date To Value...Please Enter Again");
					document.thisform.end_date.focus();
					return false;
				}
			if(endmonth > month)
			{
				alert("Incorrect Date To Value...Please Enter Again");
				document.thisform.end_date.focus();	
				return false;
			}
		}
		var leapyr=0;
		if(endyr%4 == 0)
		{
			if(endyr%100 != 0)
			{
				leapyr = 1;
			}
			else
			{
				if(endyr%400 == 0)
					leapyr = 1;
				else
					leapyr = 0;
			}
		}
		if((leapyr == 1)&&(endmonth == "02"))
		{
			if(endday > 29)
			{
				alert("Incorrect Date To Value...Please Enter Again");
				document.thisform.end_date.focus();
				return false;
			}
		}
		if((leapyr == 0)&&(endmonth == "02"))
		{
			if(endday > 28)
			{
				alert("Incorrect Date To Value...Please Enter Again");
				document.thisform.end_date.focus();
				return false;
			}
		}
		if((endmonth == "04")||(endmonth == "06")||(endmonth == "09")||(endmonth == "11"))
		{
			if(endday > 30)
			{
				alert("Incorrect Date To Value...Please Enter Again");
				document.thisform.end_date.focus();
				return false;
			}
		}
	}
	if((startlen > 0)&&(endlen > 0))
	{
		if(startyr > endyr)
		{
			alert("Incorrect Duration Entered...Please Enter Again");
			document.thisform.start_date.focus();
			return false;
		}
		if(startyr == endyr)
		{

			if(endmonth == startmonth)
				if(startday > endday)
				{
					alert("Incorrect Duration Entered...Please Enter Again");
					document.thisform.start_date.focus();
					return false;
				}
			if(startmonth > endmonth)
			{
				alert("Incorrect Duration Entered...Please Enter Again");
				document.thisform.start_date.focus();
				return false;
			}
		}
	}
	return true;
}	


	function CalculateActualDate(date,diffdate,difftype)
	{
		datetime = date;
		var date_ist_gmt1=datetime.split(" ");
		var date_ist_gmt2=date_ist_gmt1[1].split(":");
		var date_ist_gmt3=date_ist_gmt1[0].split("/");
		
		var d = new Date();
		var year1= d.getYear();
	
		d.setDate(date_ist_gmt3[2]);
		d.setMonth(date_ist_gmt3[1]);
		d.setYear(date_ist_gmt3[0]);
		d.setHours(date_ist_gmt2[0]);
		d.setMinutes(date_ist_gmt2[1]);
		d.setSeconds(date_ist_gmt2[2]);

		var datetime1=d.getTime();
		if(difftype==1)
		{				
			var datetime2=datetime1-diffdate;
		}
		else if(difftype==0)
		{
			var datetime2=datetime1+diffdate;
		}
	
		var getfulldate=new Date();
		getfulldate.setTime(datetime2);
		var year=getfulldate.getYear();
		
		var Final_year=0;

		if(year>2000)    
		Final_year=year;
		else
		Final_year=year+1900;  

		var month=getfulldate.getMonth();
		if(month<=9)
		{
			month='0'+month;
		}
		var day=getfulldate.getDate();
		if(day<=9)
		{
			day='0'+day;
		}
		var hour=getfulldate.getHours();
		if(hour<=9)
		{
			hour='0'+hour;
		}
		var minute=getfulldate.getMinutes();
		if(minute<=9)
		{
			minute='0'+minute;
		}
	
		var second=getfulldate.getSeconds();
		if(second<9)
		{
			second='0'+second;
		}

		datetime=Final_year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;
		//alert("datetime="+datetime);	
		return  datetime;    
	}

	function y2k(number)
	{ 
		return (number < 1000) ? number + 1900 : number; 
	}
	function padout(number)
	{ 
		return (number < 10) ? '0' + number : number;
	}

	function showDates(startYear1,startMonth1,startDay1,endYear1,endMonth1,endDay1) 
	{
		var all_dates = new Array();
		var ad=0;	
		startDate1 = new Date(startYear1,startMonth1 - 1,startDay1);
		endDate1 = new Date(endYear1,endMonth1 - 1,endDay1); 
		var tmp;

		for (;;) 
		{
			if(startDate1<=endDate1)
			{
				tmp = (y2k(startDate1.getYear()) + '-' + padout(startDate1.getMonth() + 1) + '-' + padout(startDate1.getDate()));	
				all_dates[ad] = tmp;
				ad++;
				startDate1 = new Date(startDate1.getTime() + 1*24*60*60*1000);
			}
			else if (startDate1 > endDate1) 
			{
				return all_dates;
			}			

		}	
	}
	
	function load(vserial,dmode,startdate,enddate,pt_for_zoom,zoom_level,status,access,time_interval)
	{
		document.getElementById('prepage').style.visibility='visible';
		Load_Data(vserial,startdate,enddate,pt_for_zoom,zoom_level,status,access,dmode,time_interval);
	
		GEvent.addListener(map,"click", function(overlay,point) 
		{ 											
			var ltlng;   ////////// for display lat long on click while ltlng set="show"
			if(document.forms[0].latlng.checked == true)
			{
				ltlng = document.forms[0].latlng.value="show";		
			}
			else
			{
				ltlng = document.forms[0].latlng.value="";
			}
					
			if(ltlng=="show")
			{
				var myHtml = "<font size='2' color='#000000'>The GPoint value is: " + map.fromLatLngToDivPixel(point) + "<br>"+point + "<br>" + "<center>at zoom level " + map.getZoom()+"</font></center>";
				map.openInfoWindow(point, myHtml);
			}
		}); //GEvent.addListener closed 
	}

	var browser=navigator.appName;
	var b_version=navigator.appVersion;
	var version=parseFloat(b_version);

	function loadXML(xmlFile)
	{
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

var dist_array = new Array();

var last_point_arr2 = new Array();
var last_vid_arr2 = new Array();
var last_vehiclename_arr2 = new Array();
var last_speed_arr2 = new Array();
var last_datetime_arr2 = new Array();
//var last_place_arr = new Array();
var last_fuel_level_arr2 = new Array();
var last_fuel_litres_arr2 = new Array();
//var last_vehicletype_arr = new Array();
var last_marker_arr2 = new Array();
var vIcon_arr2 = new Array();


function getxmlData_Track(len2,flag1,lat_arr,lng_arr,vid_arr,vehiclename_arr,speed_arr,datetime_arr,fuel_arr, vehicletype_arr, access)
{
	//alert("v00="+vehiclename_arr[0]);
  if(vid_arr.length<=0)
	{
		flag1=0;
	}
	
	if(flag1)
	{
		var point;
		
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
			
			if(len2>1 && len2<6)
				var zoom = map.getBoundsZoomLevel(bounds)-3; 
			else if(len2>6 && len2<16)
				var zoom = map.getBoundsZoomLevel(bounds)-2; 
			else if(len2>16 && len2<25)
				var zoom = map.getBoundsZoomLevel(bounds)-1; 
			else
				var zoom = map.getBoundsZoomLevel(bounds); 	
						
			if(access=="Zone")
			{
				show_milestones();		
			}
			else
			{
				map.setCenter(center,zoom);
			}
			
			startup_var = 0;
		}
						
    track_markers(lat_arr,lng_arr,vid_arr,vehiclename_arr,speed_arr,datetime_arr,fuel_arr,vehicletype_arr,len2,mm);

		var zoom;
		var event = 0;
		var newzoomlevel=0;
		
		getLandMark(event,newzoomlevel);
		
		///////////////////// CALL GET LANDMARK ON EVENT LISTENER FOR TRACK //////////////////////////
		GEvent.addListener(map, 'zoomend',function (oldzoomlevel,newzoomlevel) 
		{			
			var event =1;
			getLandMark(event,newzoomlevel);
		}); //GEvent addListener		
	}//if flag1 closed				
} //FUNCTION getxmlDataTrack


function track_markers(lat_arr,lng_arr,vid_arr,vehiclename_arr,speed_arr,datetime_arr,fuel_arr, vehicletype_arr, len2,mm)
{
	var gmarkersA = new Array();      
	var gmarkersB = new Array();    
	var gmarkersC = new Array(); 
	
	var j = 0;
	var colr = ["#00FF66","#0066FF","#FF0000","#33FFFF","#FF33CC","#9966FF","#FF9900","#FFFF00"];
	var i,vehiclename,speed,point,datetime,place,marker,polyline,last;

	var vehicleserial;
	var vehicletype;
	var vid1=0;
	var vid2=0;
	var pt = new Array();
	var value = new Array();
	var poly = new Array();
	var dist = 0;
	var lastmarker = 0;

	var p = 0;
	var fuel=0;

	for (i = 0; i < len2; i++) 
	{	
		
		vehicleserial = vid_arr[i];
		vehiclename = vehiclename_arr[i];
		//alert("v0"+vehiclename);
    vehicletype = vehicletype_arr[i];		
		speed = speed_arr[i];
		if(speed<=3)
			speed = 0;
		point = new GLatLng(parseFloat(lat_arr[i]),
		parseFloat(lng_arr[i]));
		datetime = datetime_arr[i];	
	
		fuel = fuel_arr[i];	
		
		pt[i] = point;
		place=0;
		//alert("fuel0="+fuel);
    marker = CreateMarkerTrack(point, vehicleserial, vehiclename, speed, datetime, dist, fuel, vehicletype, len2, gmarkersC, p);
		p++;
		gmarkersA.push(marker);
		
		if(i==len2-1)
		{
			var dt =datetime.split(' ');
			var date1 = dt[0].split('-');
			
			var year1 = date1[0];
			var month1 = date1[1];
			var day1 = date1[2];

			var time1 = dt[1].split(':');	
			var hr1 = time1[0];
			var year2 = currentDate.getFullYear();
			var month2 = currentDate.getMonth()+1;	
			var day2 = currentDate.getDate();
			var hr2 = currentDate.getHours();

			if(month2<10)
			{
			month2="0"+month2;
			}
			
			if(hr2<10)
			{
			hr2="0"+hr2;	
			}

			if( (year1==year2)&&(month1==month2)&&(day1==day2)&&(hr1==hr2) )
			{
			last = 1;
			lastmarker = new PdMarker(pt[i],iconCurrent);
			//alert("Current marker="+lastmarker);
			lastmarker.blink(true,150);
			map.addOverlay(lastmarker);
			}			
			
			//////////***************** PLOT ALL TRACK MARKERS OF ALL VEHICLES *********//////////
			for(var m=0;m<gmarkersA.length;m++)
				map.addOverlay(gmarkersA[m]);

			/*if(last==1)
			{
				map.addOverlay(lastmarker);
			}*/
			
			document.getElementById('prepage').style.visibility='hidden';	
			////////////////////////////////////////////////////////////////
		}

		/*if(j==7)
		{
			j=0;
			dist = 0;
		}	*/
		
		if(i>=0&&i<=len2-1)
		{
			vid1 = vid_arr[i];
		}
		if(i>=0&&i<=len2-2)
		{
			vid2 = vid_arr[i+1];	
		}
		//if(vid1 == vid2)  ////// CHECK FOR SAME VEHICLE
		//{																					
			if( (i>=0)&&(i<=len2-2))
			{
				polyline = new GPolyline([
				new GLatLng(parseFloat(lat_arr[i]),parseFloat(lng_arr[i])),
				new GLatLng(parseFloat(lat_arr[i+1]),
				parseFloat(lng_arr[i+1]))], '#FF0000', 3,1);	
				map.addOverlay(polyline);
			}			
				value[i] = polyline.getLength();
				value[i] = value[i] / 1000;
				//var distance = Math.round(value[i]*100)/100; 
				dist = dist + value[i];
				dist = Math.round(dist*100)/100;																		
				
				var pt1 = new Array();
				var pt2 = new Array();

				var pt1 = new GLatLng(parseFloat(lat_arr[i]),
				parseFloat(lng_arr[i]));

			if(i>=0&&i<=len2-2)
			{
				var pt2 = new GLatLng(parseFloat(lat_arr[i+1]),
				parseFloat(lng_arr[i+1]));
			}

				var lt1 = pt1.y;
				var lng1 = pt1.x;

				var lt2 = pt2.y;
				var lng2 = pt2.x;				
	} //for i loop closed	
} //track markers closed

var pt = new Array();
var lat1 = 0;
var lng1 = 0;
var lat2 = 0;
var lng2 = 0;
var coord;
var vname = new Array();
//var gmarkersC;
var mm;	
var ew;


function CreateMarkerTrack(point, imei, vehiclename, speed, datetime, dist, fuel, vehicletype, len2, gmarkersC, p) 
{	
	//alert("In createmtrack="+vehiclename);
  /*if(p==0)
	{
		mm = new GMarkerManager(map, {borderPadding:1});
	}*/

	//alert("p outside condition="+p);
	
	pt[p] = point;
	vname[p] = vehiclename;

	if(p>0&&(vname[p]==vname[p-1]))
	{		
		//alert("p Inside condition="+p);
		
		lat1 = pt[p-1].y;
		lng1 = pt[p-1].x;

		lat2 = pt[p].y;
		lng2 = pt[p].x;	

		var yaxis = (lat1 + lat2)/2;
		var xaxis = (lng1 + lng2)/2;
				
		coord = new GLatLng(yaxis,xaxis);
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
		
		//var a=
		//alert("val="+a);
		IconArrow.image = "images/arrow_images/"+angle_deg+'.png';
		IconArrow.iconSize = new GSize(20, 19);
		IconArrow.iconAnchor = new GPoint(10, 10);

		var marker2 = new GMarker(coord, IconArrow);
		gmarkersC.push(marker2);
		//alert("(before p=len2-1), p="+p+" len2="+len2+" coord="+coord+" angle_deg="+angle_deg);
		
		if(p == len2-1)
		{
			//alert("inside p==len2-1");
			//mm.addMarkers(gmarkersC,0,17);
			//mm.refresh();

			for(var m=0;m<gmarkersC.length;m++)
				map.addOverlay(gmarkersC[m]);
		}		
	}	
	
	var lt_1 = Math.round(point.y*100000)/100000; 
	var ln_1 = Math.round(point.x*100000)/100000;

	//alert("p="+p+" len2="+len2);
	if(p==0)
	{
		var Icon= new GIcon(startIcon);
	}
	else if(p == len2-1)
	{
		var Icon= new GIcon(stopIcon);
	}
	else
	{
		if(speed<1)
		var Icon= new GIcon(iconRed);

		else if(speed>1&&speed<=20)
		var Icon= new GIcon(iconYellow);

		else if(speed>20)
		var Icon= new GIcon(iconGreen);

		else
		var Icon= new GIcon(iconRed);			
	}
		
	var marker;	
	marker = new GMarker(point, Icon);
	var action_marker;
		
	var Icon2= new GIcon(iconGreen);
	Icon2.image = 'green_Marker1.png';
	Icon2.iconSize = new GSize(14, 22);
	Icon2.iconAnchor = new GPoint(6, 20);
	Icon2.infoWindowAnchor = new GPoint(5, 1);
	action_marker = new GMarker(point, Icon2);

	startdate = document.thisform.start_date.value;
	enddate = document.thisform.start_date.value;
	
	if(document.thisform.GEarthStatus.value == 1)
	{	
		//alert("in if");	
		GEvent.addListener(marker, 'click', function()
		{
			//place = "-";						
			//alert("point="+point+"icon="+Icon+"marker="+marker+" fuel_litres="+fuel_litres+ " fuel_level="+fuel_level+"rad_but="+rad_but+"veiclename="+vehiclename+"speed="+speed+"datetime="+datetime);
			//alert("FUEL="+fuel);
      PlotTrackMarkerWithAddress(point, Icon, marker, imei, vehiclename, speed,datetime, dist, fuel, vehicletype);
			//PlotTrackMarkerWithAddress1(point, Icon, marker, vehiclename, speed,datetime, dist,fuel_litres, fuel_level,rad_but);
			map.addOverlay(action_marker);

		//alert("action_marker in mouseover="+action_marker);
		});	
	}
	
	else
	{	
		//alert("in else");	
		GEvent.addListener(marker, 'mouseover', function()
		{
			//alert("point="+point+"icon="+Icon+"marker="+marker+" fuel_litres="+fuel_litres+ " fuel_level="+fuel_level+"rad_but="+rad_but+"veiclename="+vehiclename+"speed="+speed+"datetime="+datetime);
			//alert("FUEL="+fuel);
      PlotTrackMarkerWithAddress(point, Icon, marker, imei, vehiclename, speed, datetime, dist, fuel, vehicletype);
			//PlotTrackMarkerWithAddress1(point, Icon, marker, vehiclename, speed,datetime, dist,fuel_litres, fuel_level,rad_but);
			map.addOverlay(action_marker);

		//alert("action_marker in mouseover="+action_marker);
		});	
	}	
	
	//alert("action_marker="+action_marker);

	GEvent.addListener(action_marker, 'mouseout', function() {				
		//alert("action_marker in mouseout"+action_marker);		
		map.removeOverlay(action_marker);
	////////////////////////////////////////////////////////////////////////////////
	});	
	///////////////////////////MOUSE OUT CLOSED/////////////////////////////////
	
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

	//alert('icon='+Icon);

	var marker = new GMarker(point,Icon);
	var marker2 = new GMarker(point,Icon);

	var lat = Math.round((point.y)*100000)/100000;
	var lng = Math.round((point.x)*100000)/100000;

	//var iwform = pretty('<center>LANDMARK <br>'
	//+'<font color="blue" size=3><strong>'+landmark+'</strong></font></center><br>');

	var iwform = '<table bgcolor="#EEEFF0" border="0"><tr><td><table border="0" cellpadding=1 cellspacing=0><tr><td>&nbsp;</td></tr><tr><td><font size=3 color=#000000><b>LANDMARK</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=red size=3><b>'+landmark + '</b></font></td><td></td></tr><tr><td>&nbsp;</td></tr><tr><td><font size=2 color=#000000><b>Latitude</b></font></td> <td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lat+'</font></td></tr><tr><td></td></tr><tr><td><font size=2 color=#000000><b>Longitude</b></font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+lng+'</font></td></tr></table></td></tr></table>';

	//alert("iwform="+iwform);
	
	/*GEvent.addListener(marker, 'click', function() {
	marker.openInfoWindowHtml(iwform);				
	});*/
        
	// ========== Open the EWindow instead of a Google Info Window ==========

	if(document.thisform.GEarthStatus.value == 1)
	{		
		GEvent.addListener(marker, "click", function() {
		//ew.openOnMarker(marker,iwform);

		///////////////////////MINI MAP CODE////////////////////

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
	///////////////////////////////////////////////////////////////
		});     
	}
	
	else
	{
		GEvent.addListener(marker, "mouseover", function() {
		//ew.openOnMarker(marker,iwform);

		///////////////////////MINI MAP CODE////////////////////
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
	///////////////////////////////////////////////////////////////
		});     
	}
	
	 // ========== Close the EWindow if theres a map click ==========
	GEvent.addListener(map, "click", function(overlay,point) {
		if (!overlay) {
		  ew.hide();
		}
	});

	return marker;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////

var last_point_arr = new Array();
var last_vid_arr = new Array();
var last_vehiclename_arr = new Array();
var last_speed_arr = new Array();
var last_datetime_arr = new Array();
//var last_place_arr = new Array();
var last_fuel_level_arr = new Array();
var last_fuel_litres_arr = new Array();
//var last_vehicletype_arr = new Array();
var last_marker_arr = new Array();
var vIcon_arr = new Array();
var counter;
counter=0;

///////////////////////////////// LAST POSITION DATA ////////////////////////////////////////////////

function Load_Data(vserial,startdate,enddate,pt_for_zoom,zoom_level,status,access,dmode,time_interval)
{  
   //alert("vid="+vid+" startdate="+startdate+" endate="+enddate+" pt_for_zoom="+pt_for_zoom+" access="+access+ " dmode="+dmode+" time_interval="+time_interval);
   //////// GET CURRENT DATE  /////////////////
  var d1 = new Date();			// CURRENT DATE IN MILLISECONDS
  var curr_date = d1.getDate();
  var curr_month = d1.getMonth();
  var curr_year = d1.getFullYear();
  var current_date;
  
  var month_tmp;
  var day_tmp;

	if(curr_month<9)
	{
		curr_month = curr_month+1;
		month_tmp = "0"+curr_month;
	}
	else
	{
		curr_month = curr_month+1;
		month_tmp = curr_month;
	}

	if(curr_date<10)
		day_tmp = "0"+curr_date;
	else
		day_tmp = curr_date;

	current_date = curr_year+"-"+month_tmp+"-"+day_tmp;
	var current_dtstr =	curr_year+"/"+month_tmp+"/"+day_tmp+" 23:59:59"; // USE THIS VARIABLE FOR 10 DAYS CONDITION IF ANY
	///////////////////////////////////////////////////	

	var dateopt = document.thisform.last_dateopt.value;

	if(dateopt==2)
	{		
		//alert("in date1 condition=");
		////////// GET DAY 10 DAYS BEFORE  /////////////////////////////////////
		var curr_timestamp = d1.getTime();			// = Current timestamp in milliseconds	

		var secs_oneday = 24*60*60;				// = 86400 secs
		var secs_tendays_before = 86400 * 10	// = 864000 secs
		var prev_timestamp = secs_tendays_before * 1000		// = 10 Days before in MilliSeconds 

		//alert("d2_tmp="+d2_tmp);

		var diff = curr_timestamp - prev_timestamp;
		
		var d2 = new Date(diff);

		var last_date = d2.getDate();
		var last_month = d2.getMonth();
		var last_year = d2.getFullYear();
		var last_datestr;

		var month_tmp2;
		var day_tmp2;

		if(last_month<10)
		{
			last_month = last_month+1
			month_tmp2 = "0"+last_month;
		}
		else
		{
			last_month = last_month+1
			month_tmp2 = last_month;
		}

		if(last_date<10)
			day_tmp2 = "0"+last_date;
		else
			day_tmp2 = last_date;

		last_datestr = last_year+"/"+month_tmp2+"/"+day_tmp2+ " 00:00:00";
		// DECLARE NEW START AND END DATE
		date1 = last_datestr;
		startdate = last_datestr;

		date2 = current_dtstr;
		enddate = current_dtstr;
	}


	  if (GBrowserIsCompatible()) 
	  {	  			
  		//alert("in GBrowserIsCompatible")
  		map.clearOverlays();	
  		//alert('user_date='+user_dates.length+'vehicleserial='+vehicleSerial);
  		if(vserial!=null)
  		{
  		 //alert('check');
        var date = new Date();
        // COPY ORIGINAL XML FILE        
		    var dest = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
		    //alert("dest="+dest);
        //var dest = "src/php/xml_tmp/filtered_xml/tmp_1296469048456.xml";
        thisdest = dest;
        thismode = dmode;
        thisaccess = access;
        //var dest = "xml_tmp/filtered_xml/tmp_1295185453465.xml" ;
        // alert("d="+dest);        
        
        // MAKE FILTERED COPY        
        var poststr = "xml_file=" + encodeURI( dest )+
                "&mode=" + encodeURI( dmode )+
                "&vserial=" + encodeURI( vserial )+
                "&startdate=" + encodeURI( startdate )+
                "&enddate=" + encodeURI( enddate )+                        
                "&time_interval=" + encodeURI(time_interval);
				
        //alert("poststr="+poststr);                                          
        //var str = "src/php/get_filtered_xml.php";
        //var exists = isFile(str);
        //alert("exists="+exists);
		    makePOSTRequestMap('src/php/get_filtered_xml.php', poststr);
        TryCnt =0;
        clearTimeout(timer);
        timer = setTimeout('displayInfo()',1000);	 
      } // if vid closed
   } //is compatible closed
} //function load1 closed


function isFile(str){
var O= AJ();
if(!O) return false;
try
{
O.open("HEAD", str, false);
O.send(null);
return (O.status==200) ? true : false;
}
catch(er)
{
return false;
}
}
function AJ()
{
var obj;
if (window.XMLHttpRequest)
{
obj= new XMLHttpRequest();
}
else if (window.ActiveXObject)
{
try
{
obj= new ActiveXObject('MSXML2.XMLHTTP.3.0');
}
catch(er)
{
obj=false;
}
}
return obj;
}


function displayInfo()
{
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
      //alert("length 1:"+xml_data.length);
      var xml_data1 = xmlObj.getElementsByTagName("t1");
	    // alert("length 2:"+xml_data1.length);
      if(xml_data1.length>0)
      {
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
		alert("file not found");
	}	
    
  /*if (bname == "Microsoft Internet Explorer")
  {
    alert("Data Received");
  }	*/								
				
	if((((xml_data.length==0) || (xml_data.length==undefined)) && (DataReceived==true)) || (TryCnt>=MAX_TIMELIMIT))
	{	
	  alert("No Data Found");
    document.getElementById('prepage').style.visibility='hidden';	
    clearTimeout(timer);	
    //var poststr = "dest=" + encodeURI( thisdest );
	  //makePOSTRequestMap('src/php/del_xml.php', poststr);								
	}
	else  if(DataReceived==true)
	{	
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
    //alert("io1="+io); 
    
    if(io=="")
      io="io8";       
    //alert("io2="+io);   
               
    //alert("io2="+io); 
    /////////////////////////////////////////////		
		
		for (var k = 0; k < xml_data.length; k++) 
		{																													
			//alert("t11111111==="+xml_data[k].getAttribute("datetime"));						
			lat_tmp = xml_data[k].getAttribute("lat");
			lng_tmp = xml_data[k].getAttribute("lng");	
						
			lat_arr[len2] = xml_data[k].getAttribute("lat");
			lng_arr[len2] = xml_data[k].getAttribute("lng");
			vid_arr[len2] = xml_data[k].getAttribute("vehicleserial");
			//vehiclename_arr[len2] = vname;
			vehiclename_arr[len2] = xml_data[k].getAttribute("vehiclename");
			//alert("v000="+vehiclename_arr[len2] );
			speed_arr[len2] = Math.round(xml_data[k].getAttribute("speed")*100)/100;			
			if( (speed_arr[len2]<=3) || (speed_arr[len2]>200) )
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
			//alert("lt=="+lat_arr[len2]+"lng_arr(len2) ="+lng_arr[len2]+"vid arr="+vid_arr[len2]);
			len2++;		
		}	//XML LEN LOOP CLOSEDhaa
		
		  //var poststr = "dest=" + encodeURI( thisdest );
	    //makePOSTRequest('src/php/del_xml.php', poststr);
	   
	    if(vid_arr.length>0 && lat_arr.length>0 && lng_arr.length>0)
		  {	
  		  //alert("LPCOUNT="+lp_count);
  		  //alert("before track markers "+len2+" "+lat_arr+" "+lng_arr+" "+vid_arr+" "+vehiclename_arr+" "+speed_arr+" "+datetime_arr+"  VTYPE="+vehicletype_arr);				
  		  if(thismode==1)
  		  {
  			 getxmlData_LP(len2,1,lat_arr,lng_arr,vid_arr,vehiclename_arr,speed_arr,datetime_arr,fuel_arr,vehicletype_arr,thisaccess)						//alert("len2 outside loop="+len2);
  		  }
  		  else if(thismode==2)
  		  {
  			 getxmlData_Track(len2,1,lat_arr,lng_arr,vid_arr,vehiclename_arr,speed_arr,datetime_arr,fuel_arr,vehicletype_arr,thisaccess)						//alert("len2 outside loop="+len2);
  		  }
  		  document.getElementById('prepage').style.visibility='hidden';	
		}					
	} // ELSE CLOSED       		
}

/*
function pausecomp(millis)
{
  var date = new Date();
  var curDate = null;  
  do 
  {
    curDate = null;
    curDate = new Date();
    var obj=document.getElementById("waittxt")
    obj.value+="Hello! wait";
  }
  while(curDate-date < millis);
} */

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getxmlData_LP(len2,flag1,lat_arr,lng_arr,vid_arr,vehiclename_arr,speed_arr,datetime_arr, fuel_arr, vehicletype_arr,access)
{
	//alert("In getxmlData_LP"+access);
	
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
			if(access=="Zone")
			{
				//alert("access="+access+"ms="+ms);
				show_milestones();
				//alert("access="+access);
			//map.setCenter(center,zoom); 
			}
			else
			{
				map.setCenter(center,zoom);
			}
			startup_var = 0;
		}		
		
		LP_markers(lat_arr,lng_arr,vid_arr,vehiclename_arr,speed_arr,datetime_arr, fuel_arr, vehicletype_arr,len2,mm);

		var zoom;
		var event = 0;
		var newzoomlevel=0;		
		
		getLandMark(event,newzoomlevel);
		
		////////////////////// CALL GET LANDMARK ON EVENT LISTENER FOR LAST POSITION //////////////////////////
		GEvent.addListener(map, 'zoomend',function (oldzoomlevel,newzoomlevel) 
		{
			var event =1;
			getLandMark(event,newzoomlevel);
		}); //GEvent addListener												
	}//if flag1 closed			
} //FUNCTION getxmlDataTrack

/////////////////////// get_LastPosition ////////////////////////////////////

function LP_markers(lat_arr,lng_arr,vid_arr,vehiclename_arr,speed_arr,datetime_arr, fuel_arr, vehicletype_arr,len2,mm)
{
	//alert("In LP markers");
	
	var gmarkersA = new Array();      
	var gmarkersB = new Array();  
	var gmarkersC = new Array();  
	
	var j = 0;
	var colr = ["#00FF66","#0066FF","#FF0000","#33FFFF","#FF33CC","#9966FF","#FF9900","#FFFF00"];
	var i,vehiclename,speed,point,datetime,place,marker,polyline,last;

	var vid1=0;
	var vid2=0;
	var pt = new Array();
	var value = new Array();
	var poly = new Array();
	var dist = 0;
	var lastmarker = 0;
	var p = 0;
	var fuel=0;

	for (i = 0; i < len2; i++) 
	{
		vid = vid_arr[i];
		vehiclename = vehiclename_arr[i];		
		speed = speed_arr[i];
		if(speed<=3)
			speed = 0;

		point = new GLatLng(parseFloat(lat_arr[i]),
		parseFloat(lng_arr[i]));
		datetime = datetime_arr[i];		
		fuel = fuel_arr[i];	
		vehicletype = vehicletype_arr[i];
		pt[i] = point;
		place=0;		
		marker = CreateMarkerLP(point, vid, vehiclename, speed, datetime, fuel, vehicletype,len2,gmarkersC,p);
		p++;
		gmarkersA.push(marker);

		if(i==len2-1)
		{
			for(var m=0;m<gmarkersA.length;m++)
			map.addOverlay(gmarkersA[m]);				
			document.getElementById('prepage').style.visibility='hidden';					
		}

		///////////////////////////// PLOT CURRENT MARKER ////////////////////////////////////

		var dt = datetime.split(' ');	
		var date1 = dt[0].split('-');
		
		var year1 = date1[0];
		var month1 = date1[1];
		var day1 = date1[2];

		var time1 = dt[1].split(':');	
		var hr1 = time1[0];	

		var year2 = currentDate.getFullYear();
		var month2 = currentDate.getMonth()+1;	
		var day2 = currentDate.getDate();
		var hr2 = currentDate.getHours();
		
		if(month2<10)
		{
			month2="0"+month2;
		}
		
		if(hr2<10)
		{
			hr2="0"+hr2;
		}

	} //for i loop closed	
	
	//alert("i="+i+" len2="+len2);
		
} //track markers closed

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

function CreateMarkerLP(point, imei, vehiclename, speed, datetime, fuel, vehicletype, len2, gmarkersC, p) 
{
	//alert("in createMarkerLP");
	
	var vIcon;
	//alert("vehicle_type="+vehicletype);
	if(p==0)
	{
		mm = new GMarkerManager(map, {borderPadding:1});
		if(vehicletype=="Light")
			vIcon= new GIcon(lvIcon1);

		else if(vehicletype=="Heavy")
			vIcon= new GIcon(hvIcon2);
			
		else
			vIcon = new GIcon(lvIcon1);
			
			//alert("vIcon_0="+vIcon);
	}
	
	else if(p%2==0)
	{
		if(vehicletype=="Light")
			vIcon = new GIcon(lvIcon1);

		else if(vehicletype=="Heavy")
			vIcon = new GIcon(hvIcon2);
		
		else
			vIcon = new GIcon(lvIcon1);
			
				//alert("vIcon_1="+vIcon);
	}

	else 
	{
		if(vehicletype=="Light")
			vIcon = new GIcon(lvIcon3);

		else if(vehicletype=="Heavy")
			vIcon = new GIcon(hvIcon3);
		else
			vIcon = new GIcon(lvIcon1);
	}

	pt[p] = point;
	vname[p] = vehiclename;
	
	var lt_1 = Math.round(point.y*100000)/100000; 
	var ln_1 = Math.round(point.x*100000)/100000;

	var marker = new GMarker(point, vIcon);
	
	startdate = document.thisform.start_date.value;
	enddate = document.thisform.end_date.value;

	//last_vehicletype_arr[p] = vehicletype;
	last_marker_arr[p] = marker;
	vIcon_arr[p] = vIcon;
	
	//alert("GEarthStatus="+document.myform.GEarthStatus.value);
	
	if(document.thisform.GEarthStatus.value == 1)
	{		
	    //alert("in if");		
		GEvent.addListener(marker, 'click', function()
		{	
			PlotLastMarkerWithAddress(point, vIcon, marker, imei, vehiclename, speed,datetime, fuel);
		});	
	}
	
	else
	{ 
	    //alert("in else");	 
		GEvent.addListener(marker, 'mouseover', function()
		{			
			PlotLastMarkerWithAddress(point, vIcon, marker, imei, vehiclename, speed,datetime, fuel);
		});
	}

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

/*function getScriptPage2(str)
{
  var strURL="src/php/get_fuel_pro.php?content="+str;
  var req = getXMLHTTP();
  req.open("GET", strURL, false); //third parameter is set to false here
  req.send(null);
  return req.responseText;
} */

/////////// CODE FOR SELECTING LANDMARK ON ZOOM ///////////////////////////////////////

function getLandMark(event,newzoomlevel)
{
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

///////////////// SORT ARRAY DATE WISE //////////////////
	
var dateRE = /^(\d{2})[\/\- ](\d{2})[\/\- ](\d{4})/;
function dmyOrdA(a, b){
a = a.replace(dateRE,"$3$2$1");
b = b.replace(dateRE,"$3$2$1");
if (a>b) return 1;
if (a <b) return -1;
return 0; }
function dmyOrdD(a, b){
a = a.replace(dateRE,"$3$2$1");
b = b.replace(dateRE,"$3$2$1");
if (a>b) return -1;
if (a <b) return 1;
return 0; }
function mdyOrdA(a, b){
a = a.replace(dateRE,"$3$1$2");
b = b.replace(dateRE,"$3$1$2");
if (a>b) return 1;
if (a <b) return -1;
return 0; }
function mdyOrdD(a, b){
a = a.replace(dateRE,"$3$1$2");
b = b.replace(dateRE,"$3$1$2");
if (a>b) return -1;
if (a <b) return 1;
return 0; } 

///////////////////////////////////////////////////////////////////////////////////////////////////////



function isFile(str){
    var O= AJ();
    if(!O) return false;
    try
    {
        O.open("HEAD", str, false);
        O.send(null);
        return (O.status==200) ? true : false;
    }
    catch(er)
    {
        return false;
    }
}
function AJ()
{
    var obj;
    if (window.XMLHttpRequest)
    {
        obj= new XMLHttpRequest();
    }
    else if (window.ActiveXObject)
    {
        try
        {
            obj= new ActiveXObject('MSXML2.XMLHTTP.3.0');
        }
        catch(er)
        {
            obj=false;
        }
    }
    return obj;
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
   
///////////////////////////////////////////////////////////////////////// ////////////////////////  

function PlotLastMarkerWithAddress(point, Icon, marker, imei, vehiclename, speed,datetime, fuel) {

//alert("test");
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
					///alert("j1========="+j);
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
    
    var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td><font size=2 color=#000000>Fuel</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+fuel_level+' litres</td></tr><tr><td colspan=3><font color=blue size=2>( '+point.y+', '+point.x+' )</font></td></tr></table></div>');

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

var fuel_level=0;

function PlotTrackMarkerWithAddress(point, Icon, marker, imei, vehiclename, speed, datetime, dist, fuel, vehicletype) 
{
 //alert("fuel2="+fuel);
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
					///alert("j1========="+j);
				}
			}
			/*if (accuracy == 9) 
			{           
				var north = result.Placemark[i].LatLonBox.north;
				var south = result.Placemark[i].LatLonBox.south;
				var east = result.Placemark[i].LatLonBox.east;
				var west = result.Placemark[i].LatLonBox.west;
				var countrybounds = new GLatLngBounds(new GLatLng(south,west), new GLatLng(north,east));
			}
			// thecountry = result.Placemark[i].AddressDetails.Country.CountryNameCode;*/
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
		
		//var access2=document.myform.access.value;
		//alert('access='+str);

		/*if(access2=="Zone")
		{
			var strURL="select_mining_landmark.php?content="+str;
		}
		else
		{*/
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

		
    ///GET FUEL LEVEL IN TRACK///////////
		
		if(fuel>30 && fuel<4096)
		{
      str = imei+","+fuel;		
      strURL="src/php/map_fuel_calibration.php?content="+str;	
      //alert(strURL);
          
  		var req2 = getXMLHTTP();
  		req2.open("GET", strURL, false); //third parameter is set to false here
  		req2.send(null);
  		fuel_level = req2.responseText;	
    }	
    /////////////////////////////////////
    
    var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td><font size=2 color=#000000>Distance travelled</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+dist+' km</font></td></tr><tr><td><font size=2 color=#000000>Fuel</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+fuel_level+' litres </td></tr><tr><td colspan=3><font color=blue size=2>( '+point.y+', '+point.x+' )</font></td></tr></table></div>');

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