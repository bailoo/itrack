
var startup_var;
var newurl;
newurl=0;

//alert("in userfunction.js");

function getData()
{
	//alert("in getData");
	//alert("In get Data ="+document.forms[0]);
	var i=0;
	
	var startdate;
	var enddate;
	
	startdate = document.forms[0].StartDate.value;				
	enddate = document.forms[0].EndDate.value;
	
	//alert(" st="+startdate+" ed="+enddate);

	//alert(document.forms[0].elements['vehiclestatus[]'].value);

	var s = document.forms[0].elements['vehiclestatus[]'];
	var len = s.length;		
	//alert("s[i].value="+s.value);
	
	var vid;
	vid = "";
	var i = 0;
	var cnt=0;

	if(len==undefined)
	{
		vid = s.value;
	}
	
	for(i=0;i<len;i++)
	{
		if(cnt==0)
		{
		vid =  vid + s[i].value;
		cnt=1;
		}
		else
		{
			vid = vid+ "," + s[i].value;
		}			
	}
	
	//alert('vid='+vid+" st="+startdate+" ed="+enddate);
	load_xml(vid,startdate,enddate)		
}


////////////////////////// FUNCTION TO DISPLAY ALL DATES BETWEEN TWO DATES ////////////////////////////

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


var browser=navigator.appName;
var b_version=navigator.appVersion;
var version=parseFloat(b_version);

function loadXML(xmlFile)
{
	if((browser=="Microsoft Internet Explorer") && (version>=4)) // for internet xeplorer
	{
			var xmldoc = new ActiveXObject("MSXML2.DOMDocument");	 
	}
	else
	{
		xmlFile=xmlFile+"?newurl="+newurl;
		//alert(xmlFile);
		var xmlhttp = new window.XMLHttpRequest();
		xmlhttp.open("GET",xmlFile,false);
		xmlhttp.send(null);
		var xmldoc = xmlhttp.responseXML;
		//alert('xml_data='+xmldoc);
		return xmldoc;
		//alert('xmlObj='+xmlObj);
	}
    xmldoc.async = false; 
	if((browser=="Netscape"||browser=="Microsoft Internet Explorer") && (version>=4))  // for Internet Explorer
	{
		xmldoc.onreadystatechange=verify;	 
	}
	xmlFile=xmlFile+"?newurl="+newurl;
	newurl++;
    xmldoc.load(xmlFile);
	//xmlObj=xmldoc;
    return xmldoc; 
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


function load_xml(vid,startdate,enddate)
{
	//alert("in loadxml");
	
	var lat_arr = new Array();
	var lng_arr = new Array();
	var vid_arr = new Array();
	var vehiclename_arr = new Array();
	var speed_arr = new Array();
	var datetime_arr = new Array();
	var place_arr = new Array();
	var fuel_arr = new Array();

	var lat_arr1 = new Array();
	var lng_arr1 = new Array();
	var vid_arr1 = new Array();
	var vehiclename_arr1 = new Array();
	var speed_arr1 = new Array();
	var datetime_arr1 = new Array();
	var place_arr1 = new Array();
	var fuel_arr1 = new Array();
	
	var vehicle_count = new Array();
	
	var lat_prev;
	var lng_prev;
	var vid_prev;
	var vehiclename_prev;
	var speed_prev;
	var datetime_prev;
	var place_prev;
	var fuel_arr_prev;	

	var lt2;
	var lng2;

	var polyline;		

	var serverdt;
	var serverdt1;
	var xml_file;
	var xml_file_date;
	var vehicleSerial;
	var len2;
	var flag1;
	var xml_count;
	var k;
	var m;
	var distance;

	xml_count=0;

	len2=0;
	flag1=0;

	var flag_geo=1;
	var flag_route=1;	
	flag1=1;	

	date1 = startdate;
	date2 = enddate;
	
	vehicleSerial = vid.split(',');
	var sdate = date1.split(' ');	
	var date1 = sdate[0].split('/');

	var year1 = date1[0];
	var month1 = date1[1];
	var day1 = date1[2];

	var edate = date2.split(' ');	
	var date2 = edate[0].split('/');

	var year2 = date2[0];
	var month2 = date2[1];
	var day2 = date2[2];

	var user_dates = new Array();	
	user_dates = showDates(year1,month1,day1,year2,month2,day2);
	
	date1 = new Date(startdate);
	date2 = new Date(enddate);


	//map.clearOverlays();	
	if(user_dates.length>0 && vehicleSerial!="")
	{
		len2=0;
		var xml_data = 0;

		//////// GET CURRENT DATE  /////////////////
		var d1 = new Date();
		var curr_date = d1.getDate();
		var curr_month = d1.getMonth();
		var curr_year = d1.getFullYear();
		var current_date;

		var month_tmp;
		var day_tmp;

		if(curr_month<10)
		{
			curr_month = curr_month+1
			month_tmp = "0"+curr_month;
		}
		else
		{
			curr_month = curr_month+1
			month_tmp = curr_month;
		}

		if(curr_date<10)
			day_tmp = "0"+curr_date;
		else
			day_tmp = curr_date;

		current_date = curr_year+"-"+month_tmp+"-"+day_tmp;
		///////////////////////////////////////////


		for(var d=0;d<user_dates.length;d++)
		{
			for(var v=0;v<vehicleSerial.length;v++)
			{					
				//var xml_file = "xml_vts/xml_data/"+user_dates[d]+"/"+vehicleSerial[v]+".xml";	
				//	var xml_file = "xml_vts/xml_data/"+user_dates[d]+"/"+vehicleSerial[v]+".xml";	
			
				if(user_dates[d]==current_date)
					var xml_file = "xml_vts/xml_data/"+user_dates[d]+"/"+vehicleSerial[v]+".xml";
				else
					var xml_file = "sorted_xml_data/"+user_dates[d]+"/"+vehicleSerial[v]+".xml";

				url = xml_file;
				//alert("url="+url);

				var xmlObj = loadXML(url);
				//doc = XML.load(url);
				try
				{						
					xml_data = xmlObj.documentElement.getElementsByTagName("marker");					
				}
				catch(err)
				{
					//alert("file not found");
				}
				
				//alert("len="+xml_data.length);
				
				for (var x = 0; x < xml_data.length; x++) 
				{
					serverdt = xml_data[x].getAttribute("datetime");
					serverdt1 = new Date(serverdt.replace(/-/g,"/"));
					
					//alert(" serverdt1="+serverdt1+" date1="+date1+" date2="+date2);
					
					if(serverdt1 >= date1 && serverdt1 <= date2)
					{
						//alert("in condition");
						lat_arr1[len2] = xml_data[x].getAttribute("lat");
						lng_arr1[len2] = xml_data[x].getAttribute("lng");							
						vid_arr1[len2] = xml_data[x].getAttribute("vehicleserial");
						vehiclename_arr1[len2] = xml_data[x].getAttribute("vehiclename");
						speed_arr1[len2] = xml_data[x].getAttribute("speed");
						if(speed_arr1[len2]<=3)
							speed_arr1[len2] = 0;		
					
						datetime_arr1[len2] = serverdt;						
						
						/*var date_spl=datetime_arr1[len2].split(" ");					
						var date_spl_1=date_spl[1].split(":");							
						var date_spl_2=date_spl[0].split("-");	
					
						var d1 = new Date();
						var year1= d1.getYear();					
						d1.setDate(date_spl_2[2]);
						d1.setMonth(date_spl_2[1]);
						d1.setYear(date_spl_2[0]);
						d1.setHours(date_spl_1[0]);
						d1.setMinutes(date_spl_1[1]);
						d1.setSeconds(date_spl_1[2]);							
						var datetime1=d1.getTime();				
						var datetime2=datetime1-5400000;							
						var getfulldate=new Date();			

						getfulldate.setTime(datetime2);
						var year=getfulldate.getYear();
						year=year-100;
						var Final_year='200'+year;			
						var month=getfulldate.getMonth();
						if(month<=9)
						{
							month='0'+month;
						}
						var day=getfulldate.getDate();
						if(day<=9)
						{
							month='0'+day;
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
						if(rad_but=="GMT4")
						{
						datetime_arr1[len2]=Final_year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;							
						}*/
						
						fuel_arr1[len2] = xml_data[x].getAttribute("fuel");
						//alert(" before len2="+len2);
						len2++;	
				
					} // if user serverdate closed
				} // xml len closed
				//alert("vserial="+vid_arr1[len2-1]+" Last DateTime="+datetime_arr1[len2-1]);
			
				//vehicle_count[v] = len2-1;
				//alert(" vehicle_count="+vehicle_count[v]);
			}// VEHICLE ARRAY CLOSED

		} // DATE ARRAY CLOSED	
					
		
		//alert(" len3="+len2);
		//alert("vehicle count LEN ="+len2+" vid="+vid_arr1+" vname="+vehiclename_arr1+" lt_arr1="+lat_arr1+" lng_arr1="+lng_arr1);	

		document.forms[0].vserial_arr.value = vid_arr1;
		document.forms[0].vehiclename_arr.value = vehiclename_arr1;
		document.forms[0].lat_arr.value = lat_arr1;
		document.forms[0].lng_arr.value = lng_arr1;		
		document.forms[0].datetime_arr.value = datetime_arr1;		
		
		//alert("vserial="+document.forms[0].vserial_arr.value);
		document.forms[0].submit();
		/*var i=0;
		for(i=0;i<len2-1;i++)
		{
			getTripDetail(vid_arr1[i],lt_arr1[i],lng_arr[1]);
			
		}*/
	
	} // if user_dates and vid closed
}

//////////////////////////////////////////////////////////////////////////////////////////////
/*

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

function getTripDetail(vsno,lt,lng)
{
  var str=vsono+","+lt+","+lng
  var strURL="get_TripInfo.php?content="+str;
  var req = getXMLHTTP();
  req.open("GET", strURL, false); //third parameter is set to false here
  req.send(null);
  return req.responseText;
}
*/

