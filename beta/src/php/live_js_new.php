<?php 
include_once("user_type_setting.php"); 
?>
<script type="text/javascript">
//LIVE JS MODULE
var label_type = "<?php echo $report_type; ?>";

var RouteNMCustomer=new Array(); // Route Number Evening Customer Type
var RouteMCustomerLat=new Array();
var RouteMCustomerLng=new Array();
var RouteMCustomerStationNo=new Array();
var RouteMCustomerNo=new Array();
var RouteMCustomerType=new Array();

var RouteNECustomer=new Array(); // Route Number Evening Customer Type
var RouteECustomerLat=new Array();
var RouteECustomerLng=new Array();
var RouteECustomerStationNo=new Array();
var RouteECustomerNo=new Array();	
var RouteECustomerType=new Array();
var markers = new Array();

uniqueRouteMorningParseJson = JSON.parse( <?php echo json_encode($_SESSION['uniqueRouteArrMorningNew']); ?> );
uniqueRouteEveningParseJson=JSON.parse( <?php echo json_encode($_SESSION['uniqueRouteArrEveningNew']); ?> );
var uniqueRouteParseJson = JSON.parse( <?php echo json_encode($_SESSION['uniqueRouteTransporters']); ?> );

var liveDataDisplay=[[]];




var pt = new Array();
var imei1 = new Array();
var vname1 = new Array();
var speed1 = new Array();
var fuel1 = new Array();
var datetime1 = new Array();
var marker1 = new Array();
var day_max_speed1 = new Array();
var day_max_speed_time1 = new Array();
var last_halt_time1 = new Array();
var imei_iotype_arr = new Array();

var lat_customer_tmp=new Array();
var lng_customer_tmp=new Array();
var matched_customer_tmp=new Array();
var remark_tmp = new Array();
var transporter_tmp = new Array();
var transporter = "-";
var remark = "-";
  
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
var infowindow;

var trail_flag = false;
var route_div_flag = 0;

function select_all_routes()
{	
	var obj1 = document.forms[0];
	//alert("route_limit="+document.getElementById('route_limit').value);
	//alert("c1"+obj.all);  	
	if(obj1.all_route.checked)
	{	
		var obj;
		//alert("route_shift_ev="+route_shift);
		if(route_shift==1)
		{
			//alert("ev");
			obj = obj1.elements['route_ev[]'];
		}
		else if(route_shift==2)
		{
			//alert("mor");
			obj = obj1.elements['route_mor[]'];
		}
		//alert("obj="+obj.length);
		if(obj.length!=undefined)
		{
			//alert("obj.length="+obj.length);
			for (var i=0;i<obj.length;i++)
			{
				obj[i].checked=true;
			}
		}
	}
	else
	{
		var obj;		
		if(route_shift==1)
		{
			obj = obj1.elements['route_ev[]'];
		}
		else if(route_shift==2)
		{
			obj = obj1.elements['route_mor[]'];
		}
		
		//alert("obj="+obj+" ,objlen="+obj.length);
		if(obj.length!=undefined)
		{
			//alert("obj.length="+obj.length);
			for (var i=0;i<obj.length;i++)
			{
				obj[i].checked=false;
			}
		}
	}
}
	
function show_live_div()
{
    //var obj = document.getElementByName('live_opt');
	//alert("show before");
	var obj = document.forms[0].live_opt;
	//alert("obj="+obj);		
		
	if(obj.length!=undefined)
	{
		//alert("obj.length="+obj.length);		
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].checked==true)
			{
				//alert("checked");
				var radio_value = obj[i].value;
				//alert("radio_value="+radio_value);
				if(radio_value=="2")	//IF ROUTE
				{
					route_div_flag = 1;
					document.getElementById('show_route_opt').style.display='';					
					document.getElementById('show_vehicle').style.display='none';
					document.getElementById('sel_all_vehicle').style.display='none';
					document.getElementById('sel_all_route').style.display='';					
				}
				else					//IF VEHICLE
				{			
					route_div_flag = 0;					
					document.getElementById('show_route_opt').style.display='none';					
					document.getElementById('show_route_ev').style.display='none';					
					document.getElementById('show_route_mor').style.display='none';					
					document.getElementById('show_vehicle').style.display='';
					document.getElementById('sel_all_vehicle').style.display='';
					document.getElementById('sel_all_route').style.display='none';										
				}								
			}	  
		}
	}	
}

var route_shift = 0;

function show_all_routes()
{    
	//var obj = document.getElementByName('live_opt');
	//alert("show before");
	var obj = document.forms[0].route_opt;
	//alert("obj="+obj);
	if(obj.length!=undefined)
	{
		//alert("obj.length="+obj.length);
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].checked==true)
			{
				//alert("checked");
				var radio_value = obj[i].value;
				//alert("radio_value="+radio_value);
				if(radio_value=="2")
				{					
					route_shift = 2;
					document.getElementById('show_route_mor').style.display='';
					document.getElementById('show_route_ev').style.display='none';
				}
				else
				{					
					route_shift = 1;
					document.getElementById('show_route_mor').style.display='none';
					document.getElementById('show_route_ev').style.display='';					
				}								
			}	  
		}
	}	
}
		
function show_live_vehicles()
{
    //alert("Show live vehicles");
  var display_type="single";
	var poststr = "display_type1=" + encodeURI(display_type);      
	//alert(poststr);
  makePOSTRequest('src/php/module_live_vehicles.php', poststr);
}

function show_live_vehicles_hide_div()
{
	document.getElementById("blackout_4").style.visibility = "visible";
	document.getElementById("divpopup_4").style.visibility = "visible";
	document.getElementById("blackout_4").style.display = "block";
	document.getElementById("divpopup_4").style.display = "block";
}

function show_main_home_vehicle(display_type)
{
	//alert("display_type="+display_type);
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
			makePOSTRequest('src/php/module_live_vehicles.php', poststr);
		}	
		else
		{				
			var poststr = "display_type1=" + encodeURI(display_type)+
						   "&category="+document.getElementById("category").value;			
			makePOSTRequest('src/php/module_selection_information.php', poststr);	
		}
	}	
}

function show_vehicle_display_option()
{
	document.getElementById("blackout_1").style.visibility = "visible";
	document.getElementById("divpopup_1").style.visibility = "visible";
	document.getElementById("blackout_1").style.display = "block";
	document.getElementById("divpopup_1").style.display = "block"; 
}
var poly;
var flightPath;
var polyline;
var routeMarkerStart;
var routeMarkerEnd;
//var routeMarker = new Array();
var gmarkersCustomer = new Array();
var markerCustomer;		
    
function showRouteOnLiveMap(polylineId, shift)                                                    
{ 
    //alert("polylineId="+polylineId+",shift="+shift);
    var testVal=document.getElementById("routeJsonData").value;
    var routeArr = JSON.parse(testVal);
    //alert("routeName="+routeArr[polylineId].polylineCoord);

    var coord=routeArr[polylineId].polylineCoord;
    var route_name=routeArr[polylineId].polylineName;
	//alert("coord="+coord);
    if(coord!="")
    {
       //alert(polyline) 
        /*if(polyline!=undefined && routeMarker.length >0)
        {
            map.removeOverlay(polyline);
            for(var z=0;z<routeMarker.length;z++) {                
                map.removeOverlay(routeMarker[z]);
            }
        }*/ 
		//alert("route_name="+route_name);		
		//alert("polyline="+polyline+"routeMarkerStart="+routeMarkerStart+"routeMarkerEnd="+routeMarkerEnd);
        if(polyline!=undefined && routeMarkerStart!=undefined && routeMarkerEnd!=undefined)
        {
			//alert("in if");
			polyline.setMap(null);
			routeMarkerStart.setMap(null);
			routeMarkerEnd.setMap(null);
        }
        if(gmarkersCustomer.length>0) 
		{
            for(var z=0;z<gmarkersCustomer.length;z++) 
			{
				gmarkersCustomer[z].setMap(null);           
            }
        }
         //map.clearOverlays(polyline);
        //routeMarker = new Array();
        gmarkersCustomer = new Array();

        var coord_test = (((((coord.split('),(')).join(':')).split('(')).join('')).split(')')).join(''); 
        var coord1 = coord_test.split(":");
        var latlngbounds = new google.maps.LatLngBounds();
        var polygonCoords=new Array();
	//var polygonCoords = new google.maps.MVCArray(); // collects coordinates
    	for(var z=0;z<coord1.length;z++)
    	{
            var coord2 = coord1[z].split(",");
            //alert("lat="+parseFloat(coord2[0])+"lng="+parseFloat(coord2[1]));
            polygonCoords[z] = new google.maps.LatLng(parseFloat(coord2[0]),parseFloat(coord2[1]));
            if(z==0)
            {
                var pointThis = new google.maps.LatLng(parseFloat(coord2[0]),parseFloat(coord2[1]));
				
				var startImage = {
				  url: 'images/start_marker.png'
				};
				routeMarkerStart = new google.maps.Marker({
					position: pointThis,
					map:map,
					icon:startImage
				});
				 
				google.maps.event.addListener
				(
					routeMarkerStart, 'click', GetRouteInfo(route_name)
				);
				markers.push(routeMarkerStart);
            }
            if(z==(coord1.length-1))
            {
			
				var stopImage = {
				  url: 'images/stop_marker.png'
				};
				routeMarkerEnd = new google.maps.Marker({
					position:  polygonCoords[z],
					map:map,
					icon:stopImage
				});
				google.maps.event.addListener
				(
					routeMarkerEnd, 'click', GetRouteInfo(route_name)
				);
				markers.push(routeMarkerEnd);
            }
            latlngbounds.extend(new google.maps.LatLng(parseFloat(coord2[0]),parseFloat(coord2[1])));
			//tmpVal=[polygonCoords[z]];
			
    	}
		polyline = new google.maps.Polyline
		({
			path:polygonCoords,
			strokeColor: '#ff0000',
			strokeOpacity: 1.0,
			strokeWeight: 1.5
		});	
		markers.push(polyline);						
		polyline.setMap(map);
		
        map.setCenter(latlngbounds.getCenter());
		map.fitBounds(latlngbounds);
       /*polyline = new GPolyline(polygonCoords, '#808080', 6);
        map.setCenter(latlngbounds.getCenter());
        map.addOverlay(polyline);
        // map.removeOverlay(polyline);*/
    }
    
    //###### PLOT CUSTOMERS    
    var search_text = route_name;
    //alert("searchText="+search_text);

    if(shift=="2") {
            uniqueRouteMorningParseJson = JSON.parse( <?php echo json_encode($_SESSION['uniqueRouteArrMorningNew']); ?> );

            var routeLength=0;
            var tmpCnt=0;
            var routeTmpFlag = false;
            var routeLength=0;
            routeLength=uniqueRouteMorningParseJson.length;

            if(parseInt(routeLength)>0)
            {
                    //alert("in if");
                    for(var i=0;i<routeLength;i++)
                    {
                            //search_text = search_text.trim();
                            //alert("route1="+uniqueRouteMorningParseJson[i]['routeNo']+" route2="+search_text);
                            if(search_text.trim() == uniqueRouteMorningParseJson[i]['routeNo'].trim())                                        
                            {							   
                                    routeTmpFlag=true;
                                    route_no = uniqueRouteMorningParseJson[i]['routeNo'];
                                    lat = uniqueRouteMorningParseJson[i]['lat'];
                                    lng = uniqueRouteMorningParseJson[i]['lng'];
                                    //rFoundStationName[tmpCnt] = uniqueRouteMorningParseJson[i]['stationName'];
                                    station_no = uniqueRouteMorningParseJson[i]['customerNo'];
                                    type = uniqueRouteMorningParseJson[i]['type'];

/*alert("Customer="+station_no);
alert("lat="+lat);
alert("lng="+lng);
alert("route_no="+route_no);*/

                                    if(lat!="-" && lng!="-")
                                    {
/*alert("Customers:"+station_no);
alert("lat2="+lat);
alert("lng2="+lng);
alert("route_no2="+route_no);*/
                                            marker = CreateCustomerMarker(lat,lng,station_no,type,route_no);		
                                            gmarkersCustomer.push(marker);
                                    }			
                                    //tmpCnt++;
                            }    
                    } 	
            }
    } else if(shift=="1") {
            uniqueRouteEveningParseJson=JSON.parse( <?php echo json_encode($_SESSION['uniqueRouteArrEveningNew']); ?> );	

            var routeLength=0;
            var tmpCnt=0;
            var routeTmpFlag = false;
            var routeLength=0;
            routeLength=uniqueRouteEveningParseJson.length;

            if(parseInt(routeLength)>0)
            {
                    //alert("routelen="+routeLength);
                    for(var i=0;i<routeLength;i++)
                    {
                            //search_text = search_text.trim();
                            //alert("route1="+uniqueRouteEveningParseJson[i]['routeNo']);
                            /*if(i==0) {
                                uniqueRouteEveningParseJson[i]['routeNo'] = '@210521';
                            }*/
                            
                            if(search_text.trim() == uniqueRouteEveningParseJson[i]['routeNo'].trim())                                        
                            {	
                                    //alert("match_found");
                                    routeTmpFlag=true;
                                    route_no = uniqueRouteEveningParseJson[i]['routeNo'];
                                    lat = uniqueRouteEveningParseJson[i]['lat'];
                                    lng = uniqueRouteEveningParseJson[i]['lng'];
                                    //rFoundStationName[tmpCnt] = uniqueRouteMorningParseJson[i]['stationName'];
                                    station_no = uniqueRouteEveningParseJson[i]['customerNo'];
                                    type = uniqueRouteEveningParseJson[i]['type'];

                                    if(lat!="-" && lng!="-")
                                    {
                                        //lat="26.65675";
                                        //lng="80.87897";
                                        //alert("beforeCustomerPlot, lat="+lat+" ,lng="+lng+", station_no="+station_no+" ,type="+type+" ,route_no="+route_no);
                                        marker = CreateCustomerMarker(lat,lng,station_no,type,route_no);
                                        //alert("marker="+marker);
                                        gmarkersCustomer.push(marker);
                                        //alert("afterCustomerPlot");
                                    }			
                                    //tmpCnt++;
                            }    
                    } 	
            }	
    }
    
    for(var m=0;m<gmarkersCustomer.length;m++) {
        //alert("gm="+gmarkersCustomer[m]);
		gmarkersCustomer[m].setMap(null);       
    }

} //### showRouteOnLiveMap closed

function GetRouteInfo(route_no)
{
	return function() 
	{
		alert("Route No : "+route_no);
		/*var data_str = "<tr><strong>Route No</strong></font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2><strong>"+route_no +"</strong></font></td><td></tr>";
			
		var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="height:100px;" align=left><table cellpadding=0 cellspacing=0>'+data_str+'</table></div>');
		var infoTabs = [tab1];
		marker1.openInfoWindowTabsHtml(infoTabs);*/
	};
}	

function display_vehicle_according_divoption(obj)
{ 
	var div_option_values=tree_validation(obj);
	if(div_option_values!=false)
	{			
		close_vehicle_display_option();
		document.getElementById("live_all_vehicle").checked=false;
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
	makePOSTRequest('src/php/module_live_vehicles.php', poststr);
}

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
	var obj=document.form1;
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


var poststr_route='';
function filter_live_vehicle(obj,jsActionNo)
{ 
	var result ="";
	if(route_div_flag ==1)
	{
		var s_vehicle =document.forms[0].elements['live_vehicles[]'];
		var obj_r;
		//alert("route_shift1="+route_shift);
		if(route_shift ==1)
		{
			obj_r=document.forms[0].elements['route_ev[]'];
		}
		else if(route_shift ==2)
		{
			obj_r=document.forms[0].elements['route_mor[]'];
		}
		
		var result_r=checkbox_selection(obj_r);
		var str1 = result_r.split(',');
		
		var k=0;		
		var final_route_str = '';
		for(var i=0;i<str1.length;i++)
		{			
			var str2 = str1[i].split(':');
			var route = str2[0];
			var route_tmp = route.split('/');
			for(var p=0;p<route_tmp.length;p++) 
			{
				final_route_str = final_route_str+""+route.trim()+",";
			}
			var r_vehicle = str2[1];
			if(s_vehicle.length!=undefined)
			{
				for (var j=0;j<s_vehicle.length;j++)
				{																	
					//s_vehicle_tmp = s_vehicle_tmp.substring(0, s_vehicle_tmp.length - 1);
					var str_vehicle_final = s_vehicle[j].value.split('*');
					var s_vehicle_tmp = str_vehicle_final[0];
					//alert("sV="+trim(s_vehicle_tmp)+" ,rV="+trim(r_vehicle));
					
					if(trim(s_vehicle_tmp) == trim(r_vehicle))
					{
						//alert("matched vehicle");
						if(k==0)
						{
							result = result +""+trim(s_vehicle_tmp)+"#";
						}
						else
						{
							result = result +","+trim(s_vehicle_tmp)+"#";
						}
						k++;
						break;
					}
				}
			}
		}
                
		final_route_str = final_route_str.replace(/\//g, "-");
		poststr_route = "shift=" + encodeURI( route_shift )+
				"&route_names=" + encodeURI( final_route_str );                   
	}
	else
	{
		var obj=document.forms[0].elements['live_vehicles[]'];
		result=checkbox_selection(obj);
	}
	//alert(result);                                 
                                                                   
	//alert("poststr1="+poststr);
	//makePOSTRequestMap('src/php/get_polyline_detail.php', poststr);


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
	if(s1.length>200)
	{
		alert("Please select maximum 150 Vehicles at a time");
		return false;
	} 

	//document.getElementById('ref_time').style.display= '';

	//time_int = document.forms[0].autoref_combo.value;     // TIME INT VALUE AFTER
	//alert(time_int);
	if(s1.length>1)
	{
		if(time_int == 0)
		{
			document.getElementById('ref_time').innerHTML = "Refresh Time : (disabled)";
		}		
		else
		{			
			if(time_int<=120)
			{				
				document.getElementById('ref_time').innerHTML = "Refresh Time : ("+2+" mins)";
			}
			else
			{
				time_int = time_int / 60;
				document.getElementById('ref_time').innerHTML = "Refresh Time : ("+time_int+" mins)";
			}
		}
	}
	else
	{
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
	}
  
	imei_data = result;

	close_popup();
	liveDataDisplay=[[]];
	document.getElementById('prepage').style.visibility='visible';
	
	for (var i = 0; i < markers.length; i++) 
	{
		markers[i].setMap(null);
	}
    
  
	pt = null;
	imei1 = null;
	vname1 = null;
	speed1 = null;
	fuel1 = null;
	datetime1 = null;
	marker1 = null;
	day_max_speed1 = null;
	day_max_speed_time1 = null;
	last_halt_time1 = null;  

	pt = new Array();        //  RE-Initialise marker array vairables
	imei1 = new Array();
	vname1 = new Array();
	speed1 = new Array();
	fuel1 = new Array();
	datetime1 = new Array();
	marker1 = new Array();
	day_max_speed1 = new Array();
	day_max_speed_time1 = new Array();
	last_halt_time1 = new Array();   

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
	movingVehicle_prev(jsActionNo);    // FOR MOVING VEHICLE FOR NOW 
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
	
	map = new google.maps.Map(document.getElementById('map'), {
    center: {lat:23.674712836608773, lng: 77.783203125},
	scaleControl: true,
    zoom: 5,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });

  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  var markerThis = [];
  // [START region_getplaces]
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markerThis.
    markerThis.forEach(function(marker) {
      marker.setMap(null);
    });
    markerThis = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      markerThis.push(new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      }));

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });
}


var startup_var;    
var newurl;      ///////for change url every time
newurl=0;
var delaycnt=0;
var thisdest;
var thisdest2;
var thismode;
var thisaccess;
var timer;
var TryCnt;
var TryCnt2;
var MAX_TIMELIMIT=1000;


var dist;
dist = 0;
var tmp_dist;
tmp_dist=0;

/// load xml code



function movingVehicle_prev(jsActionNo)
{
	//alert("MOVING1");
    //alert("In Moving Visible="+document.getElementById('prepage').style.visibility);
	//alert("in mprev");
	//alert(" DIST IN movingVehicle_prev ="+dist+ " tmp_dist="+tmp_dist);
	
	document.form1.mapcontrol_startvar.value=1;
	startup_var = 1;
	
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

	movingVehicle(jsActionNo);
	
	//auto_refresh();
	//alert("after load");
}

function movingVehicle(jsActionNo)
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
	load_live(dmode,startdate,enddate,pt_for_zoom,zoom_level,status,jsActionNo);
 	document.form1.current_vehicle=1;
	//alert("MOVING2");
}


function select_mode_dropdown(frm)
{
	var mode_select_value = document.getElementById('mode_selector').value;
	//alert("ModeSel="+mode_select_value);
	if(mode_select_value=="1")
	{
		var obj = document.forms[0].mode;
		if(obj.length!=undefined)
		{		
			for (var i=0;i<obj.length;i++)
			{
				if(i==0)
				{
					obj[i].checked=true;
					break;
				}
			}
		}
	}
	else if(mode_select_value=="2")
	{
		var obj = document.forms[0].mode;
		if(obj.length!=undefined)
		{		
			for (var i=0;i<obj.length;i++)
			{
				if(i==1)
				{
					obj[i].checked=true;
					break;
				}				
			}
		}			
	}
	filter_live_vehicle(frm,'js2');
}
 ////////////////function load /////////////////////////////////////////////
function load_live(dmode,startdate,enddate,pt_for_zoom,zoom_level,status,jsActionNo)
{
	//alert("in load");
	//marker_type = lvIcon1;
	//Load_MovingData(startdate,enddate,pt_for_zoom,zoom_level,status);	
	//alert("MOVING3");
	var obj = document.forms[0].mode;
	if(obj.length!=undefined)
	{		
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].checked==true)
			{
				var radio_value = obj[i].value;					
				
				if(radio_value=="map")	//IF ROUTE
				{
					//alert("Map_Mode="+radio_value);
					document.getElementById('text_col').style.display = 'none';
					document.getElementById('map_col').style.display = '';
					document.getElementById('vlist_col').style.display = '';
					
					var options= document.getElementById('mode_selector').options;
					for (var j= 0, n= options.length; j < n ; j++) 
					{
						if (options[j].value=='1') 
						{
							document.getElementById("mode_selector").selectedIndex = j;
						}			
					}
					
					getDataForMap(startdate,enddate,pt_for_zoom,zoom_level,status);
					break;
				}
				else if(radio_value=="text")
				{
					//alert("Text_Mode="+radio_value);
					document.getElementById('map_col').style.display = 'none';
					document.getElementById('vlist_col').style.display = 'none';
					document.getElementById('text_col').style.display = '';
					
					var options= document.getElementById('mode_selector').options;
					for (var j= 0, n= options.length; j < n ; j++) 
					{
						if (options[j].value=='2') 
						{
							document.getElementById("mode_selector").selectedIndex = j;
						}			
					}						
					Load_MovingData_Text(startdate,enddate,pt_for_zoom,zoom_level,status,jsActionNo);
					break;
				}
			}
		}
	}			
	auto_refresh(jsActionNo);
}

 
var dist_array = new Array();

function getDataForMap(startdate,enddate,pt_for_zoom,zoom_level,status)
{  
dmode = 1;         
	// MAKE FILTERED COPY        
	var poststr = "mode=" + encodeURI( dmode )+
			"&vserial=" + encodeURI( imei_data )+
			"&startdate=" + encodeURI( startdate )+
			"&enddate=" + encodeURI( enddate );                       
                                                                   
	//alert("poststr1="+poststr);
	ajaxPostRequestForData('src/php/getLiveData.php', poststr); 
} //function load1 closed
function Load_MovingData_Text(startdate,enddate,pt_for_zoom,zoom_level,status,jsActionNo)
{  
        if(imei_tmp!=null)
        {  
            var date = new Date();        
            var dest = "../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
            dmode = 1; 
            thisdest = dest; 
			if(jsActionNo=='js1')
			{
				document.getElementById("lacStr").value="";
				document.getElementById("js_action").value="js1";
			}
            var poststr = "xml_file=" + encodeURI( dest )+
                    "&mode=" + encodeURI( dmode )+
                    "&vserial=" + encodeURI( imei_data )+
                    "&startdate=" + encodeURI( startdate )+
                    "&enddate=" + encodeURI( enddate )+
					"&lacStr=" + encodeURI( document.getElementById('lacStr').value )+
					"&title="+encodeURI( document.getElementById('js_action').value );	
//alert("poststr="+poststr);					
					$.ajax({
						type: "POST",
						url:'src/php/get_filtered_xml_text_live_main.php',
						data: poststr,
						success: function(response)
						{       
							//document.getElementById('dummy_div').style.display='none';    						
							//document.getElementById('prepage').style.visibility='hidden';
							//alert("response="+response);
							document.getElementById('text_col').style.display='';
							$("#text_col_content").html(response);
							
							//document.getElementById('text_col_content').innerHTML = result;
							blink("blinkMe","green","white",500);
						},
						error: function()
						{
							alert('An unexpected error has occurred! Please try later.');
						}
					});			
					//makePOSTRequestText('src/php/get_filtered_xml_text_live_main.php', poststr); 					
        } // if vid closed
    } //function load1 closed
/*function test()
{
  alert("testOK");
}*/

/*function displayInfo2()
{
  alert("in displayInfo2");
}*/
var clicked_vehicle_list='';	
function displayInfo_live()
{  
    //alert("in displayInfo main");
    var lat_arr = new Array();
    var lng_arr = new Array();
    var vid_arr = new Array();
    var vehiclename_arr = new Array();
    var vehiclenumber_arr = new Array();
    var speed_arr = new Array();
    var datetime_arr = new Array();
    var place_arr = new Array();
    var fuel_arr = new Array();
    var vehicletype_arr = new Array();
    var running_status_arr = new Array();
    var Final_DateTime=new Array();

    var day_max_speed_arr = new Array();
    var day_max_speed_time_arr = new Array();
    var last_halt_time_arr = new Array(); 

    var io1_arr=new Array(); 
    var io2_arr=new Array(); 
    var io3_arr=new Array(); 
    var io4_arr=new Array(); 
    var io5_arr=new Array(); 
    var io6_arr=new Array(); 
    var io7_arr=new Array(); 
    var io8_arr=new Array(); 
    //alert('liveLenghtnew='+liveDataDisplay.length);
  
    var len2=0;

    /////////////// GET IO ///////////////
    var imei = liveDataDisplay[0]['deviceImeiNo']		
    var str = imei+",temperature";  
		
    for(var k = 0; k < liveDataDisplay.length; k++) 
    {																													
        //alert("t11111111==="+liveDataDisplay[k]['deviceDatetimeLR']);												
        lat_tmp = liveDataDisplay[k]['latitudeLR'];
        lng_tmp = liveDataDisplay[k]['longitudeLR'];	

        lat_arr[len2] = liveDataDisplay[k]['latitudeLR'];
        lng_arr[len2] = liveDataDisplay[k]['longitudeLR'];
        vid_arr[len2] = liveDataDisplay[k]['deviceImeiNo'];
        vehiclename_arr[len2] = liveDataDisplay[k]['vehicleName'];
        vehiclenumber_arr[len2] = liveDataDisplay[k]['vehilceNumber'];        
        //alert("v000="+vehiclename_arr[len2] );
        speed_arr[len2] = Math.round(liveDataDisplay[k]['speedLR']*100)/100;
        if( (speed_arr[len2]<=3) || (speed_arr[len2]>200))
        {
                speed_arr[len2] = 0;
        }
        io1_arr[len2]=liveDataDisplay[k]['io1LR'];
        io2_arr[len2]=liveDataDisplay[k]['io2LR'];
        io3_arr[len2]=liveDataDisplay[k]['io3LR'];
        io4_arr[len2]=liveDataDisplay[k]['io4LR'];
        io5_arr[len2]=liveDataDisplay[k]['io5LR'];
        io6_arr[len2]=liveDataDisplay[k]['io6LR'];
        io7_arr[len2]=liveDataDisplay[k]['io7LR'];
        io8_arr[len2]=liveDataDisplay[k]['io8LR'];
                   											
        vehicletype_arr[len2] = liveDataDisplay[k]['vehilceType'];
        datetime_arr[len2] =  liveDataDisplay[k]['deviceDatetimeLR'];
        running_status_arr[len2] = liveDataDisplay[k]['status'];

        if(label_type!="Person")
        {
                day_max_speed_arr[len2] =  liveDataDisplay[k]['dayMaxSpeedLR'];
                day_max_speed_time_arr[len2] =  liveDataDisplay[k]['dayMaxSpeedTimeLR'];
                last_halt_time_arr[len2] =  liveDataDisplay[k]['lastHaltTimeLR'];

                if(day_max_speed_arr[len2] > 200)
                {
                        day_max_speed_arr[len2] = "";
                        day_max_speed_time_arr[len2] ="";
                }			  
        }			
        //alert("Status1="+running_status_arr[len2]);
        //alert("lt=="+lat_arr[len2]+"lng_arr(len2) ="+lng_arr[len2]+"vid arr="+vid_arr[len2]);
        len2++;		
    }	//XML LEN LOOP CLOSEDhaa
    if(len2>0)
    {	      
        //alert("record found");
        clearTimeout(timer);
        //alert("data found");
        //document.form1.status.value = "["+ vid_arr[0]+ "]"+"-("+lat_arr[0]+","+lng_arr[0]+")";
        //alert("LPCOUNT="+lp_count);
        //alert("before plottin markers "+len2+" "+lat_arr+" "+lng_arr+" "+vid_arr+" "+vehiclename_arr+" "+speed_arr+" "+datetime_arr+"  VTYPE="+vehicletype_arr);				
        var flag=1;
		processMapMarkers(len2, flag, lat_arr, lng_arr, vid_arr, vehiclename_arr,vehiclenumber_arr, speed_arr, datetime_arr, fuel_arr, running_status_arr, day_max_speed_arr, day_max_speed_time_arr, last_halt_time_arr,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr);	  //HERE ARR IS JUST NORMAL VARIABLE NOT ARRAY
       // getxml_MovingData(len2, flag, lat_arr, lng_arr, vid_arr, vehiclename_arr, speed_arr, datetime_arr, fuel_arr, running_status_arr, day_max_speed_arr, day_max_speed_time_arr, last_halt_time_arr,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr);	  //HERE ARR IS JUST NORMAL VARIABLE NOT ARRAY
        //alert("K");
        document.getElementById('prepage').style.visibility='hidden';	
    }     		
}

function processMapMarkers(len2, flag, lat_arr, lng_arr, vid_arr, vehiclename_arr,vehiclenumber_arr, speed_arr, datetime_arr, fuel_arr, running_status_arr, day_max_speed_arr, day_max_speed_time_arr, last_halt_time_arr,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr)	  //HERE ARR IS JUST NORMAL VARIABLE NOT ARRAY
{
	deleteOverlays();
	var j = 0;
	var i,vehiclename,vehiclenumber,speed,point,datetime,place,marker,polyline,last,running_status1, day_max_speed,day_max_speed_time,last_halt_time;
  
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
	
	clicked_vehicle_list="";
	clicked_vehicle_list=clicked_vehicle_list+"<table style='font-size:11px;'><tr style='background-color:004D96;color:#FFFFFF'><td align=center><strong>LIVE TRACKING LEGEND</strong></td></tr></table><br><table style='font-size:10px;' CELLPADDING=0 CELLSPACING=0><tr><td></td></tr>";
	if(!(document.getElementById('trail_path').checked))  // IF TRAIL PATH NOT CHECKED, CLEAR PREVIOUS OVERLAYS
	{
		for (var i = 0; i < markers.length; i++) 
		{
			markers[i].setMap(null);
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
			marker_prev[j].setMap(null);	
		}
	    trail_flag = false;	
	}			
  
	var tmp=1;
  	var img;
	var icon1;
	var right_vehicle_style;
	
	var latlngbounds = new google.maps.LatLngBounds();	
	for (i = 0; i < len2; i++) 
	{
		tmp=tmp+i;
		var total_dist = 0;
	
		vid = vid_arr[i];
		imei = vid_arr[i];
		vehiclename = vehiclename_arr[i];
                vehiclenumber = vehiclenumber_arr[i];
		speed = speed_arr[i];
		if(speed<=3)
		{
			speed = 0;
		}
		point = new google.maps.LatLng(parseFloat(lat_arr[i]),parseFloat(lng_arr[i]));			
		datetime = datetime_arr[i];		
		fuel = fuel_arr[i];	
		//vehicletype = vehicletype_arr[i];
		running_status1 = running_status_arr[i];

		day_max_speed = day_max_speed_arr[i];
		day_max_speed_time = day_max_speed_time_arr[i];
		last_halt_time = last_halt_time_arr[i];
		io_1=io1_arr[i];
		io_2=io2_arr[i];
		io_3=io3_arr[i];
		io_4=io4_arr[i];
		io_5=io5_arr[i];
		io_6=io6_arr[i];
		io_7=io7_arr[i];
		io_8=io8_arr[i];
		pt[i] = point;
		place=0;		
	
		var position=new google.maps.LatLng(parseFloat(lat_arr[i]), parseFloat(lng_arr[i]));
		//alert("position="+position);
			
		if(document.getElementById('trail_path').checked)
		{
			//plotAngleForTrailPath(point,vehiclename);	
			//alert("In trail path,vid_prev="+vid_prev.length);   
			//alert("In trail path,vid_prev="+vid_prev.length);
			for(var j=0;j<vid_prev.length;j++)
			{
				//alert("in for");
				if(vid_prev[j] == vid)
				{
					//marker_prev[j].setMap(null);
					//label_prev[j].setMap(null);
					
					var lattmplive1 = point_prev[j].lat();
					var lngtmplive1 = point_prev[j].lng();
																   
					//alert('lattmplive1='+lattmplive1+'lngtmplive1='+lngtmplive1);
					lattmplive2 = point.lat();
					lngtmplive2 = point.lng();
					//alert('lat2='+lat2+'lng2='+lng2);                                                 
					var yaxis = (parseFloat(lattmplive1) + parseFloat(lattmplive2))/2;
					var xaxis = (parseFloat(lngtmplive1) + parseFloat(lngtmplive2))/2;
					//alert('yaxis='+yaxis+'xaxis='+xaxis);
					var angle_t = Math.atan( (parseFloat(lattmplive2)-parseFloat(lattmplive1))/(parseFloat(lngtmplive2)-parseFloat(lngtmplive1)) );
					var angle_deg = 360 * angle_t/(2 * Math.PI);
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

					
				   // alert('image='+image);
					//position=new google.maps.LatLng(yaxis, xaxis);
					//alert('position='+position);											
					//alert("trail_flag="+trail_flag);
					
			
					if(document.getElementById('trail_path_real').checked)
					{
						var line = new google.maps.Polyline
						({
							path: [point_prev[j], point],
							strokeColor: '#ff0000',
							strokeOpacity: 1.0,
							strokeWeight: 1.5
						});	
						markers.push(line);						
						line.setMap(map);
					}
			
					var distance = calculate_distance(point_prev[j].lat(), point.lat(), point_prev[j].lng(), point.lng());
					distance += dist_prev[j];
					total_dist += distance;
			
					//alert('point1='+point_prev[j]+'point2='+position);
					if(point_prev[j]!=point)
					{
						point_prev[j] = point;
						vid_prev[j] = vid;
						dist_prev[j] = total_dist;
						//marker_prev[j] = marker1[j];

						date_prev[j] = datetime;
						angle_prev[j] = angle_deg;
						//break;
					}
					trail_flag = true;
					break;
				}	
			}
		} 
		plotLiveMarkers(lat_arr[i],lng_arr[i],p,angle_deg,running_status1,position,icon1,point,imei,vehiclename,vehiclenumber,speed,datetime,fuel,total_dist, day_max_speed, day_max_speed_time, last_halt_time,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8,tmp)
                latlngbounds.extend(position);
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
		if(!trail_flag)
		{		          
			point_prev[i] = point;
			vid_prev[i] = vid;
			dist_prev[i] = total_dist;
			marker_prev[i] = marker1[i];  		
			date_prev[i] = datetime;  		
		}	
	}
        if(startup_var==1)
        {
	map.setCenter(latlngbounds.getCenter());
	map.fitBounds(latlngbounds);
        startup_var=0;
        }
	marker1[i]=marker;
	clicked_vehicle_list += "</table>";  
  
  // SHOW VEHICLES IN DIV
	document.getElementById('examplePanel2').innerHTML = clicked_vehicle_list;
	function deleteOverlays() 
	{
		for (var i = 0; i < markers.length; i++) 
		{
			markers[i].setMap(null);
		}
	}
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
				position: point,	 map: map, icon: icon1, title:'landmark'
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
	markers.push(markerL);
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
		infowindow.open(map, markerL);					 						
	};
}

function plotLiveMarkers(lat,lng,p,angle_deg,running_status1,position,icon1,point,imei,vehiclename,vehiclenumber,speed,datetime,fuel,total_dist, day_max_speed, day_max_speed_time, last_halt_time,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8,tmp)
{
	//alert("in function");
	total_dist = Math.round((total_dist)*100)/100;
	var label_detail = "<div style='width:80px;'>"+vehiclename+"</div>";
	var font_color ="#000000";			
	var label;

	if(running_status1 == "Running")
	{
		font_color = "#008000";
	}
	else
	{
		font_color = "#FF0000";
	}
	var feature_id_live_color = document.getElementById('live_color_flag').value;
	if(feature_id_live_color == 1)
	{	
		var live_color_code = document.getElementById('live_color_code').value;
		//alert(live_color_code);
		var color_arr = live_color_code.split("@");
		var color_var1 = color_arr[0].split(":");
		var color_var2 = color_arr[1].split(":");
		var color_var3 = color_arr[2].split(":");
		var color_var4 = color_arr[3].split(":");
		
		var current_time = new Date().getTime() / 1000;		//seconds		
		current_time = parseInt(current_time);
		var xml_date_time = stringToDate(last_halt_time);
		var xml_date = xml_date_time.getTime() / 1000;
		xml_date = parseInt(xml_date);
		//alert("C1="+current_time+" ,xml_date="+xml_date+" ,color_var1[0]="+color_var1[0]+" ,color_var2[0]="+color_var2[0]+" ,color_var2[0]="+color_var3[0]);
		//alert("color_var1[1]="+color_var1[1]+" ,color_var2[1]="+color_var2[1]+" ,color_var2[1]="+color_var3[1]);
	
		var diff = (current_time - xml_date)/60;				
		
		//alert("running_status="+running_status1+" ,current_time="+current_time+" ,xml_date="+xml_date+" ,Cdate="+datetime+" ,diff="+diff+" ,color_var1[0]="+color_var1[0]);
		//if((running_status1 == "Running") || (diff < color_var1[0]) )
		if((running_status1 == "Running") || (diff < color_var1[0]) )
		{
			//alert("In Running");
			font_color = "#008000";
			running_status1 = "Running";
		}
		else if((diff>color_var1[0]) && ((diff<=color_var2[0]) || (color_var2[0]==null)))
		{
				font_color = color_var1[1];
		}
		else if((diff>color_var2[0]) && ((diff<=color_var3[0]) || (color_var3[0]==null)))
		{
				font_color = color_var2[1];
		}
		else if((diff>color_var3[0]))
		{
				font_color = color_var3[1];
		}
		
		//alert("font_color1="+font_color);	
		var style = document.createElement('style');
		style.type = 'text/css';
		//style.innerHTML = '.style'+tmp+' {font-size:10px;color:#ffffff; background-color: '+font_color+';} input::-webkit-outer-spin-button: {display: none;}';
		var bname = navigator.appName;
		if (bname == "Microsoft Internet Explorer")
		{
			style.cssText = '.style'+tmp+' {font-size:10px;color:#ffffff; background-color: '+font_color+';} input::-webkit-outer-spin-button: {display: none;}';
		}
		else
		{
			style.innerHTML = '.style'+tmp+' {font-size:10px;color:#ffffff; background-color: '+font_color+';} input::-webkit-outer-spin-button: {display: none;}';
		}
		document.getElementsByTagName('head')[0].appendChild(style);	
	}
	var marker = getMapMarker(angle_deg,running_status1,position,vehiclename,last_halt_time,tmp);
	
	var img=getLeftPanImage(running_status1);
	
	
	pt[p] = point;
	imei1[p] = imei;
	vname1[p] = vehiclename;
	speed1[p] = speed;
	datetime1[p] = datetime;
	fuel1[p] = fuel;
	day_max_speed1[p] = new Array();
	day_max_speed_time1[p] = new Array();
	last_halt_time1[p] = new Array();	
	
	var route="";
	var feature_id_map = document.getElementById('station_flag_map').value;
	if(feature_id_map == 1)
	{		
		var len_route;		
		var vname_id;
		var route_id;
			
		//alert("route_shift="+route_shift);		
		if(route_shift == 1)
		{
			len_route = document.getElementById('route_limit_ev').value;
			vname_id = "vname_ev";
			route_id = "route_ev";			
		}
		else if(route_shift == 2)
		{
			len_route = document.getElementById('route_limit_mor').value;
			vname_id = "vname_mor";
			route_id = "route_mor";			
		}
		
		//alert("lenroute="+len_route+" ,vname="+vname_id+" ,route_id="+route_id);
		for(var i=0;i<len_route;i++)
		{
			var vname_id_tmp = vname_id+i;
			var route_id_tmp = route_id+i;
			
			//alert("vname="+vname_id_tmp+" ,route_id="+route_id_tmp);			
			var master_vehicle = document.getElementById(vname_id_tmp).value;
			var master_route = document.getElementById(route_id_tmp).value;

			//alert("master_vehicle="+master_vehicle+" ,vehiclename="+vehiclename+" ,vname_id="+vname_id+" ,route_id="+route_id);
			if( trim(master_vehicle) == trim(vehiclename))
			{
				route = master_route;
				//alert("matched="+route);
				break;
			}
		}
		if(route=="") route = "NA";
		clicked_vehicle_list += "<tr><td><a href='#' style='text-decoration:none;' Onclick='javascript:Prev_PlotLastMarkerWithAddress(\""+angle_deg+"\",\""+lat+"\",\""+lng+"\",\""+marker+"\",\""+imei+"\",\""+vehiclename+"\",\""+vehiclenumber+"\",\""+speed+"\",\""+datetime+"\",\""+fuel+"\",\""+running_status1+"\",\""+total_dist+"\",\""+route+"\",\""+day_max_speed+"\",\""+day_max_speed_time+"\",\""+last_halt_time+"\",\""+io_1+"\",\""+io_2+"\",\""+io_3+"\",\""+io_4+"\",\""+io_5+"\",\""+io_6+"\",\""+io_7+"\",\""+io_8+"\",\""+tmp+"\");'>"+img+"<font color="+font_color+">"+vehiclename+"</font>&nbsp;<font color=red>("+route+")</font>&nbsp;<font color=blue>["+running_status1+"]</font></a></td></tr><tr><td><a href='#' style='text-decoration:none;' Onclick='javascript:Prev_PlotLastMarkerWithAddress(\""+lat+"\",\""+lng+"\",\""+marker+"\",\""+imei+"\",\""+vehiclename+"\",\""+speed+"\",\""+datetime+"\",\""+fuel+"\",\""+running_status1+"\",\""+total_dist+"\",\""+route+"\",\""+day_max_speed+"\",\""+day_max_speed_time+"\",\""+last_halt_time+"\",\""+io_1+"\",\""+io_2+"\",\""+io_3+"\",\""+io_4+"\",\""+io_5+"\",\""+io_6+"\",\""+io_7+"\",\""+io_8+"\",\""+tmp+"\");'></a></td></tr><tr><td>&nbsp;</td></tr>";
	}
	else
	{
		//alert("in else obj="+marker);
		
		clicked_vehicle_list += "<tr><td><a href='#' style='text-decoration:none;' Onclick='javascript:Prev_PlotLastMarkerWithAddress(\""+angle_deg+"\",\""+lat+"\",\""+lng+"\",\""+marker+"\",\""+imei+"\",\""+vehiclename+"\",\""+vehiclenumber+"\",\""+speed+"\",\""+datetime+"\",\""+fuel+"\",\""+running_status1+"\",\""+total_dist+"\",\""+route+"\",\""+day_max_speed+"\",\""+day_max_speed_time+"\",\""+last_halt_time+"\",\""+io_1+"\",\""+io_2+"\",\""+io_3+"\",\""+io_4+"\",\""+io_5+"\",\""+io_6+"\",\""+io_7+"\",\""+io_8+"\",\""+tmp+"\");'>"+img+"<font color="+font_color+">"+vehiclename+"</font>&nbsp;&nbsp;<font color=blue>["+running_status1+"]</font></a></td></tr><tr><td><a href='#' style='text-decoration:none;' Onclick='javascript:Prev_PlotLastMarkerWithAddress(\""+lat+"\",\""+lng+"\","+marker+"\",\""+imei+"\",\""+vehiclename+"\",\""+speed+"\",\""+datetime+"\",\""+fuel+"\",\""+running_status1+"\",\""+total_dist+"\",\""+route+"\",\""+day_max_speed+"\",\""+day_max_speed_time+"\",\""+last_halt_time+"\",\""+io_1+"\",\""+io_2+"\",\""+io_3+"\",\""+io_4+"\",\""+io_5+"\",\""+io_6+"\",\""+io_7+"\",\""+io_8+"\",\""+tmp+"\");'></a></td></tr><tr><td>&nbsp;</td></tr>";
	}
	
	google.maps.event.addListener
	(
		marker, 'click', infoCallbackLive(point, marker, imei, vehiclename,vehiclenumber, speed,datetime, fuel, running_status1, total_dist, route, day_max_speed, day_max_speed_time, last_halt_time,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8) 
	);
       
}

function getLeftPanImage(running_status1)
{
	if(running_status1 == "Running")
	{
		var img = '<img src=images/live/live_vehicle.gif width=8px height=8px>&nbsp;';
	}
	else if(running_status1 == "Idle")
	{
		 var img = '<img src=images/live/lp_vehicle1.gif width=8px height=8px>&nbsp;';	
	}
	else
	{
		var img = '<img src=images/live/lp_vehicle2.gif width=8px height=8px>&nbsp;';
	}
	return img;
}


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


function stringToDate(s)  {
  s = s.split(/[-: ]/);
  return new Date(s[0], s[1]-1, s[2], s[3], s[4], s[5]);
}

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
day_max_speed1 = new Array();
day_max_speed_time1 = new Array();
last_halt_time1 = new Array();
//var mm;
var rect;	


/////////////////////////////////////////////////////////////////////////////////////////////////////
var imei_clicked = new Array();
var point_after = "";
var vIcon_after = "";
var marker1_after = "";
var imei_after = "";
var vehiclename_after ="";
var speed_after ="";
var datetime_after ="";
var fuel_after ="";
var running_status_after ="";
var total_dist_after ="";
var route_after ="";
var day_max_speed_after ="";
var day_max_speed_time_after ="";
var last_halt_time_after ="";
var io_1_after ="";
var io_2_after ="";
var io_3_after ="";
var io_4_after ="";
var io_5_after ="";
var io_6_after ="";
var io_7_after ="";
var io_8_after ="";

function Prev_PlotLastMarkerWithAddress(angle_deg,lat ,lng, marker, imei, vehiclename,vehiclenumber, speed,datetime, fuel, running_status1, total_dist, route, day_max_speed, day_max_speed_time, last_halt_time,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8,tmp) 
{
	var accuracy;
	var largest_accuracy;	   
	var delay = 100;	
	var window_height="135px";
		//alert("in function");
	var io_str="";
	if(imei_iotype_arr[imei]!=undefined)
	{
		io_str=getIoString(imei_iotype_arr[imei],io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8);
	}
	
	var position=new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
        
        var latlngbounds = new google.maps.LatLngBounds();
        latlngbounds.extend(new google.maps.LatLng(parseFloat(lat),parseFloat(lng)));
	
	var markerThis=getMapMarker(angle_deg,running_status1,position,vehiclename,last_halt_time,tmp);
	
	//alert("io_str"+io_str);
	var nearest_customer_string = "";
	var transporter_remark_string = "";
	transporter = "-";
	remark = "-";
		
	var feature_id_map = document.getElementById('station_flag_map').value;
		//alert('fid='+feature_id_map);
	if(feature_id_map == 1)
	{
		var obj=document.form1.route_opt;
		var morningFlag=0;
		var eveningFlag=0;
		for(var i=0;i<obj.length;i++)
		{		
			if(obj[i].checked==true)
			{
			   if(obj[i].value==1)
			   {
				 eveningFlag=1;
			   }
			   else if(obj[i].value==2)
			   {
				
				 morningFlag=1;
			   }
			}
		}        
		//alert("morningFlag="+morningFlag+"eveningFlag="+eveningFlag);
		
		var lt_v = parseFloat(lat);
		var lng_v = parseFloat(lng);
		//alert("lat="+lt_v+"lng="+lng_v);
		if(eveningFlag==1)
		{
			var eCustomerLength=uniqueRouteEveningParseJson.length;
			//alert("len="+eCustomerLength);
			var e_customer_min_distance;		
			if(eCustomerLength>0)
			{
				var e_customer_distance_arr=new Array();
				var e_customer_print_str=new Array();
				for(var i=0;i<eCustomerLength;i++)
				{
					/*if(i<3)
					{
						alert("latPrev="+lt_v+"lngPrev="+ RouteECustomerLat[i]);
					}*/
					var customer_distance = calculate_distance(lt_v, uniqueRouteEveningParseJson[i]['lat'], lng_v, uniqueRouteEveningParseJson[i]['lng']);
					e_customer_distance_arr[i]=customer_distance;
					e_customer_print_str[customer_distance]=uniqueRouteEveningParseJson[i]['customerNo'];
				}
				e_customer_distance_arr.sort();
				//alert("minDistance="+e_customer_distance_arr[0]);
				e_customer_min_distance=e_customer_distance_arr[0];
				var e_customer_print_str=e_customer_print_str[e_customer_min_distance];
			   
				nearest_customer_string = "<tr><td class='ioCustomerTransporterCss1'>RouteNo</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>"+route+"</td></tr><tr><td class='ioCustomerTransporterCss1'>Nearest Customer</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>"+e_customer_min_distance+" From "+e_customer_print_str+"</td></tr>";
			}                    
		}
		if(morningFlag==1)
		{
			var mCustomerLength=uniqueRouteMorningParseJson.length;
			var m_customer_min_distance;		
			if(mCustomerLength>0)
			{
				var m_customer_distance_arr=new Array();
				var m_customer_print_str=new Array();
				for(var i=0;i<mCustomerLength;i++)
				{					
					var customer_distance = calculate_distance(lt_v, uniqueRouteMorningParseJson[i]['lat'], lng_v, uniqueRouteMorningParseJson[i]['lng']);
					m_customer_distance_arr[i]=customer_distance;
					m_customer_print_str[customer_distance]=uniqueRouteMorningParseJson[i]['customerNo'];
				}
				m_customer_distance_arr.sort();
				//alert("minDistance="+m_customer_distance_arr[0]);
				m_customer_min_distance=m_customer_distance_arr[0];
			  var m_customer_print_str=m_customer_print_str[m_customer_min_distance];                        
			  nearest_customer_string = "<tr><td class='ioCustomerTransporterCss1'>RouteNo</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>"+route+"</td></tr><tr><td class='ioCustomerTransporterCss1'>Nearest Customer</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>"+m_customer_min_distance+" From "+m_customer_print_str+"</td></tr>";
			}             
		}
		//################# GET TRANSPORTER AND REMARK #######################
		//alert('vehiclename='+vehiclename);
		if(morningFlag==1 || eveningFlag==1)
		{
			//transporter_remark_string = "<tr><td class='ioCustomerTransporterCss1'>Transporter</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>-</td></tr><tr><td class='ioCustomerTransporterCss1'>Remark</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>-</td></tr>";			
			transporter_remark_string = "<tr><td class='ioCustomerTransporterCss1'>Transporter</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>"+uniqueRouteParseJson[vehiclename]+"</td></tr>";			
		}			
	}
       
	map.setCenter(latlngbounds.getCenter());
	map.fitBounds(latlngbounds);
        map.setZoom(map.getZoom()-10);
	 //var geocoder = new GClientGeocoder();
	 var address_tmp;
	 var address1_tmp;
	 var BadAddress=0;
	
	var contentString='';
	if (infowindow) infowindow.close();
	infowindow = new google.maps.InfoWindow();
	var latThis=parseFloat(lat);
	var lngThis=parseFloat(lng);
	var latlng = new google.maps.LatLng(latThis, lngThis);
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
						distance = calculate_distance(latThis, google_lat, lngThis, google_lng);
						var str=latThis+','+lngThis;
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
						'<td class=live_td_css2>'+vehiclename+'</td>'+
					   '</tr>'+
                                           '<tr>'+
						'<td class=live_td_css1>Vehicle Number</td>'+
						'<td>&nbsp;:&nbsp;</td>'+
						'<td class=live_td_css2>'+vehiclenumber+'</td>'+
					   '</tr>'+
					   '<tr>'+
						'<td class=live_td_css1>Imei</td>'+
						'<td>&nbsp;:&nbsp;</td>'+
						'<td class=live_td_css2>'+imei+'</td>'+
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
						'<td class=live_td_css2>'+datetime+'</td>'+
						'</tr>'+										   
						'<td class=live_td_css1>Day Max Speed</td>'+
						'<td>&nbsp;:&nbsp;</td>'+
						'<td class=live_td_css2>'+day_max_speed+'</td>'+ 
						'<tr>'+
						'<td class=live_td_css1>Last Halt Time</td>'+
						'<td>&nbsp;:&nbsp;</td>'+
						'<td class=live_td_css2>'+last_halt_time+'</td>'+
						'</tr>'+
						 '<tr>'+
						'<td class=live_td_css1>Place</td>'+
						'<td>&nbsp;:&nbsp;</td>'+
						'<td class=live_td_css2>'+tmp_address+'</td>'+io_str+ 
						'<tr>'+
						'<tr>'+
						'<td class=live_td_css1>Status</td>'+
						'<td>&nbsp;:&nbsp;</td>'+
						'<td class=live_td_css2>'+running_status1+'</td>'+
						'</tr>';											
						if(route!=null)
						{
							var route_tr=nearest_customer_string+transporter_remark_string;
						}
						contentString=contentString+route_tr;
						'</table>';
						//'<b><font color=black size=2>('+lat+','+lng+')</font></b>';
							//alert("in open window");						
							infowindow.setContent(contentString);
							infowindow.open(map, markerThis);
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
	//alert("marker3="+marker);

	//LeftPanPlotLastMarkerWithAddress(point, vIcon, marker1[mcounter], imei, vehiclename, speed, datetime, fuel, running_status, total_dist, route, day_max_speed, day_max_speed_time, last_halt_time,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8);
}
function getMapMarker(angle_deg,running_status1,position,vehiclename,last_halt_time,tmp)
{
	var imageIcon;
	var vehicleNameLabel;
	if(isNaN(angle_deg))
	{
		//alert("No Angle found");
		if(running_status1 == "Running")
		{				
			var imageIcon = {
				  url: 'images/live/live_vehicle.gif',
					size: new google.maps.Size(8, 8),
				scaledSize: new google.maps.Size(8, 8),
				anchor: new google.maps.Point(2, 7)
				};
		
			vehicleNameLabel='<table style=\"background-color: green;color:white;font-size: 11px; font-weight: bold;font-family: Lucida Grande, Arial, sans-serif;\" >'+
								'<tr>'+
									'<td>'+vehiclename+
									'</td>'+
								'</tr>'+
							'</table>';		
		}
		else if(running_status1 == "Idle")
		{
			var imageIcon = {
							url: 'images/live/lp_vehicle1.gif',
							size: new google.maps.Size(8, 8),
							scaledSize: new google.maps.Size(8, 8),
							anchor: new google.maps.Point(2, 7)
						};
				
			vehicleNameLabel='<table style=\"background-color: yellow;color:white;font-size: 11px; font-weight: bold;font-family: Lucida Grande, Arial, sans-serif;\" >'+
								'<tr>'+
									'<td>'+vehiclename+
									'</td>'+
								'</tr>'+
							'</table>';
		
		}
		else
		{
			var imageIcon = {
							url: 'images/live/lp_vehicle2.gif',
							size: new google.maps.Size(8, 8),
							scaledSize: new google.maps.Size(8, 8),
							anchor: new google.maps.Point(2, 7)
						};			
			vehicleNameLabel='<table style=\"background-color: red;color:white;font-size: 11px; font-weight: bold;font-family: Lucida Grande, Arial, sans-serif;\" >'+
								'<tr>'+
									'<td>'+vehiclename+
									'</td>'+
								'</tr>'+
							'</table>'; 
		}		
	}
	else
	{
		var imageIcon = 
		{
			url: 'images/arrow_images/'+angle_deg+'.png',
			size: new google.maps.Size(20, 20),
			scaledSize: new google.maps.Size(20, 20),
			anchor: new google.maps.Point(2, 7)
		};
		if(running_status1 == 'Running')
		{		
			vehicleNameLabel='<table style=\"background-color: green;color:white;font-size: 11px; font-weight: bold;font-family: Lucida Grande, Arial, sans-serif;\" >'+
						'<tr>'+
							'<td>'+vehiclename+
							'</td>'+
						'</tr>'+
					'</table>';							
		 }						 
		 else
		 {			
			vehicleNameLabel='<table style=\"background-color: red;color:white;font-size: 11px; font-weight: bold;font-family: Lucida Grande, Arial, sans-serif;\" >'+
									'<tr>'+
										'<td>'+vehiclename+
										'</td>'+
									'</tr>'+
								'</table>'; 			
		 }
	}
	
	var feature_id_live_color = document.getElementById('live_color_flag').value;
	if(feature_id_live_color == 1)
	{		
		vehicleNameLabel='<table class="style'+tmp+'">'+
									'<tr>'+
										'<td>'+vehiclename+
										'</td>'+
									'</tr>'+
								'</table>'; 		
	}
	
	var markerThis = new MarkerWithLabel({
			   position: position,
			   draggable: false,
			   icon:imageIcon,
			   map: map,
			   labelContent: vehicleNameLabel,
			   labelAnchor: new google.maps.Point(-12, 14)
			 });
	
	
	
	markers.push(markerThis);
	return markerThis;
}
function getIoString(imeiWithIo,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8)
{
	var iotype_iovalue_str=imeiWithIo.split(":");
	/*if(iotype_iovalue_str.length==2)
	{
		window_height="160px";
	}
	else if(iotype_iovalue_str.length==3)
	{
		window_height="200px";
	}
	else if(iotype_iovalue_str.length==4)
	{
		window_height="190px";
	}
	else if(iotype_iovalue_str.length==5)
	{
		window_height="205px";
	}
	else if(iotype_iovalue_str.length==6)
	{
		window_height="220px";
	}
	else if(iotype_iovalue_str.length==7)
	{
		window_height="235px";
	}
	else if(iotype_iovalue_str.length==8)
	{
		window_height="250px";
	}*/
	var io_str="";
	for(var i=0;i<iotype_iovalue_str.length;i++)
	{
		var iotype_iovalue_str1=iotype_iovalue_str[i].split("^");
		//alert("iotype_iovalue_str1="+iotype_iovalue_str1[0]);	
		if(iotype_iovalue_str1[0]=="")
		{
			var io_values="ioNotExist";
		}
		else
		{
			var io_values="io_"+iotype_iovalue_str1[0];	
		}
		if(io_values!="ioNotExist")
		{
			if(iotype_iovalue_str1[1]=="temperature")
			{					
				if(eval(io_values)!="" && eval(io_values)!=undefined)
				{
					if(eval(io_values)>=-30 && eval(io_values)<=70)
					{
						io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>"+eval(io_values)+"</td></tr>";
					}
					else
					{
						io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>-</td></tr>";
					}
				}
				else
				{
					io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>-</td></tr>";
				}
			}
			else if(iotype_iovalue_str1[1]=="ac")
			{                                       
				if(eval(io_values)!="" && eval(io_values)!=undefined)
				{
						if(eval(io_values)>500)
						{
								io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>OFF</td></tr>";
						}
						else
						{
								io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>ON</td></tr>";
						}
				}
				else
				{
					io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>-</td></tr>";
				}
			}
			else if(iotype_iovalue_str1[1]=="engine")
			{
				var getIOStr="src/php/get_io_ajax.php?content="+imei;
				var req = getXMLHTTP();
				req.open("GET", getIOStr, false); //third parameter is set to false here
				req.send(null);
				var getFinalIO = req.responseText;
				getFinalIO=getFinalIO.split("#");
				
				if(eval(io_values)!="" && eval(io_values)!=undefined)
				{
					
					if(eval(io_values)<500)
					{
							io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>ON</td></tr>";
					}
					else
					{
							io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>Off</td></tr>";
					}
				}
				else
				{
					io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>-</td></tr>";
				}
			}
			else if(iotype_iovalue_str1[1]=="door_open")
			{                                       
				if(eval(io_values)!="" && eval(io_values)!=undefined)
				{
						if(eval(io_values)<250)
						{
								io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>Delivery Door</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>Close</td></tr>";
						}
						else
						{
								io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>Delivery Door</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>Open</td></tr>";
						}
				}
				else
				{
					io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>Delivery Door</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>-</td></tr>";
				}
			}
			else if(iotype_iovalue_str1[1]=="door_open2")
			{                                       
				if(eval(io_values)!="" && eval(io_values)!=undefined)
				{
						if(eval(io_values)<250)
						{
								io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>Manhole Door</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>Close</td></tr>";
						}
						else
						{
								io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>Manhole Door</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>Open</td></tr>";
						}
				}
				else
				{
					io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>Manhole Door</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>-</td></tr>";
				}
			}
			else if(iotype_iovalue_str1[1]=="door_open3")
			{                                       
				if(eval(io_values)!="" && eval(io_values)!=undefined)
				{
						if(eval(io_values)<250)
						{
								io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>Manhole Door2</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>Close</td></tr>";
						}
						else
						{
								io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>Manhole Door2</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>Open</td></tr>";
						}
				}
				else
				{
					io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>Manhole Door2</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>-</td></tr>";
				}
			}
			else 
			{
				if(eval(io_values)!="" && eval(io_values)!=undefined)
				{					
					io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>"+eval(io_values)+"</td></tr>";
				}
				else
				{
					io_str=io_str+"<tr><td class='ioCustomerTransporterCss1'>"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>-</td></tr>";
				}			
			}
		}
	}
	return io_str;
}

var ad=0;
var place;
var address1=0;


function infoCallbackLive(point, marker, imei, vehiclename,vehiclenumber, speed,datetime, fuel, running_status, total_dist, route, day_max_speed, day_max_speed_time, last_halt_time,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8) 
{
	 //alert("IN PLOT:"+point+":"+Icon+":"+marker+":"+imei+":"+vehiclename+":"+speed+":"+datetime+":"+fuel+":"+running_status);
	//alert("lat="+point.lat()+"lng="+point.lng());
	return function() 
	{
		var accuracy;
		var largest_accuracy;	   
		var delay = 100;
	 
		var window_style1="style='color:#000000;font-family: arial, helvetica, sans-serif; font-size:11px;text-decoration:none;font-weight:bold;'";
		var window_style2="style='color:blue;font-family: arial, helvetica, sans-serif; font-size:11px;text-decoration:none;'";
		var io_str="";
		var window_height="135px";
		//alert("in function");
		var io_str="";
		if(imei_iotype_arr[imei]!=undefined)
		{
			io_str=getIoString(imei_iotype_arr[imei],io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8);
		}
		//alert("io_str"+io_str);
		var nearest_customer_string = "";
		var transporter_remark_string = "";
		transporter = "-";
		remark = "-";
		
		var feature_id_map = document.getElementById('station_flag_map').value;
			//alert('fid='+feature_id_map);
		if(feature_id_map == 1)
		{
            var obj=document.form1.route_opt;
            var morningFlag=0;
            var eveningFlag=0;
            for(var i=0;i<obj.length;i++)
            {		
                if(obj[i].checked==true)
                {
                   if(obj[i].value==1)
                   {
                     eveningFlag=1;
                   }
                   else if(obj[i].value==2)
                   {
                    
                     morningFlag=1;
                   }
                }
            }        
            //alert("morningFlag="+morningFlag+"eveningFlag="+eveningFlag);
            
            var lt_v = point.lat();
            var lng_v = point.lng();
			//alert("lat="+lt_v+"lng="+lng_v);
			if(eveningFlag==1)
			{
				var eCustomerLength=uniqueRouteEveningParseJson.length;
				//alert("len="+eCustomerLength);
				var e_customer_min_distance;		
				if(eCustomerLength>0)
				{
					var e_customer_distance_arr=new Array();
					var e_customer_print_str=new Array();
					for(var i=0;i<eCustomerLength;i++)
					{
						/*if(i<3)
						{
							alert("latPrev="+lt_v+"lngPrev="+ RouteECustomerLat[i]);
						}*/
						var customer_distance = calculate_distance(lt_v, uniqueRouteEveningParseJson[i]['lat'], lng_v, uniqueRouteEveningParseJson[i]['lng']);
						e_customer_distance_arr[i]=customer_distance;
						e_customer_print_str[customer_distance]=uniqueRouteEveningParseJson[i]['customerNo'];
					}
					e_customer_distance_arr.sort();
					//alert("minDistance="+e_customer_distance_arr[0]);
					e_customer_min_distance=e_customer_distance_arr[0];
					var e_customer_print_str=e_customer_print_str[e_customer_min_distance];
				   
					nearest_customer_string = "<tr><td class='ioCustomerTransporterCss1'>RouteNo</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>"+route+"</td></tr><tr><td class='ioCustomerTransporterCss1'>Nearest Customer</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>"+e_customer_min_distance+" From "+e_customer_print_str+"</td></tr>";
				}                    
			}
                if(morningFlag==1)
                {
                    var mCustomerLength=uniqueRouteMorningParseJson.length;
                    var m_customer_min_distance;		
                    if(mCustomerLength>0)
                    {
                        var m_customer_distance_arr=new Array();
                        var m_customer_print_str=new Array();
                        for(var i=0;i<mCustomerLength;i++)
                        {					
                            var customer_distance = calculate_distance(lt_v, uniqueRouteMorningParseJson[i]['lat'], lng_v, uniqueRouteMorningParseJson[i]['lng']);
                            m_customer_distance_arr[i]=customer_distance;
                            m_customer_print_str[customer_distance]=uniqueRouteMorningParseJson[i]['customerNo'];
                        }
                        m_customer_distance_arr.sort();
                        //alert("minDistance="+m_customer_distance_arr[0]);
                        m_customer_min_distance=m_customer_distance_arr[0];
                      var m_customer_print_str=m_customer_print_str[m_customer_min_distance];                        
                      nearest_customer_string = "<tr><td class='ioCustomerTransporterCss1'>RouteNo</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>"+route+"</td></tr><tr><td class='ioCustomerTransporterCss1'>Nearest Customer</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>"+m_customer_min_distance+" From "+m_customer_print_str+"</td></tr>";
                    }             
                }
		//################# GET TRANSPORTER AND REMARK #######################
                //alert('vehiclename='+vehiclename);
                if(morningFlag==1 || eveningFlag==1)
                {
                    //transporter_remark_string = "<tr><td class='ioCustomerTransporterCss1'>Transporter</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>-</td></tr><tr><td class='ioCustomerTransporterCss1'>Remark</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>-</td></tr>";			
                    transporter_remark_string = "<tr><td class='ioCustomerTransporterCss1'>Transporter</td><td>&nbsp;:&nbsp;</td><td class='ioCustomerTransporterCss2'>"+uniqueRouteParseJson[vehiclename]+"</td></tr>";			
                }
			
	}

	 //var geocoder = new GClientGeocoder();
	 var address_tmp;
	 var address1_tmp;
	 var BadAddress=0;
	 
	 
	var contentString='';
	if (infowindow) infowindow.close();
	infowindow = new google.maps.InfoWindow();
	var latThis=point.lat();
	var lngThis=point.lng();
	var latlng = new google.maps.LatLng(latThis, lngThis);
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
						distance = calculate_distance(latThis, google_lat, lngThis, google_lng);
						var str=latThis+','+lngThis;
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
						'<td class=live_td_css2>'+vehiclename+'</td>'+
					   '</tr>'+
                                           '<tr>'+
						'<td class=live_td_css1>Vehicle Number</td>'+
						'<td>&nbsp;:&nbsp;</td>'+
						'<td class=live_td_css2>'+vehiclenumber+'</td>'+
					   '</tr>'+
					   '<tr>'+
						'<td class=live_td_css1>Imei</td>'+
						'<td>&nbsp;:&nbsp;</td>'+
						'<td class=live_td_css2>'+imei+'</td>'+
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
						'<td class=live_td_css2>'+datetime+'</td>'+
						'</tr>'+										   
						'<td class=live_td_css1>Day Max Speed</td>'+
						'<td>&nbsp;:&nbsp;</td>'+
						'<td class=live_td_css2>'+day_max_speed+'</td>'+ 
						'<tr>'+
						'<td class=live_td_css1>Last Halt Time</td>'+
						'<td>&nbsp;:&nbsp;</td>'+
						'<td class=live_td_css2>'+last_halt_time+'</td>'+
						'</tr>'+
						 '<tr>'+
						'<td class=live_td_css1>Place</td>'+
						'<td>&nbsp;:&nbsp;</td>'+
						'<td class=live_td_css2>'+tmp_address+'</td>'+io_str+ 
						'<tr>'+
						'<tr>'+
						'<td class=live_td_css1>Status</td>'+
						'<td>&nbsp;:&nbsp;</td>'+
						'<td class=live_td_css2>'+running_status+'</td>'+
						'</tr>';											
						if(route!=null)
						{
							var route_tr=nearest_customer_string+transporter_remark_string;
						}
						contentString=contentString+route_tr;
						'</table>';
						//'<b><font color=black size=2>('+lat+','+lng+')</font></b>';										
							infowindow.setContent(contentString);
							infowindow.open(map, marker);
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

function get_js_location(placemark,point)
{
	var address = placemark;
	address1 = address.address;		
	var google_point = new GLatLng(address.Point.coordinates[1],address.Point.coordinates[0]); 
	//alert("google_point.lat()======="+google_point.lat()+" google_point.lng()="+google_point.lng());
	var distance = calculate_distance(point.lat(), google_point.lat(), point.lng(), google_point.lng()); 
	//alert("dist="+distance);
	var address_local = distance+" km from "+address1;
	return address_local;
} 
function get_xml_location(point)
{
	var strURL="src/php/get_location_tmp_file.php?point_test="+point;
	//alert("strurl:"+strURL);
	var req = getXMLHTTP();
	req.open("GET", strURL, false); //third parameter is set to false here
	req.send(null);  
	var place_name_temp_param = req.responseText; 
	//alert("place_name_temp_param1="+place_name_temp_param);
	place_name_temp_param =place_name_temp_param.split(":");
	//alert("lat1="+point.lng()+"lng="+point.lat()+"lat2="+place_name_temp_param[1]+"log2="+place_name_temp_param[2]);
	var distance = calculate_distance(point.lat(), place_name_temp_param[1], point.lng(), place_name_temp_param[2]);
	//alert("distance="+distance);
	var address_local = distance+" km from "+place_name_temp_param[0];
	return address_local;
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
        //alert("checkbox selection1");
	imei_iotype_arr = new Array();
        //imei_iotype_arr.length=0;
        //alert("checkbox selection2");
	var result_1 = {};
	var flag=0;
	var cnt=0;
	var value_str="";
	if(obj.length!=undefined)
	{
            //alert("If defined");
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].checked==true)
			{				
				if(cnt==0)
				{			
					var value_1=obj[i].value;				
					result_1[value_1]=value_1;
					cnt=1;
				}
				else
				{			
					var value_1=obj[i].value;
				//	aler("value_str="+obj[i].value);
					result_1[value_1]=value_1;					
				}
				flag=1;
			}	  
		}
	}
	else
	{
            //("else");
		if(obj.checked==true)
		{
		//alert("true");
                var value_1=obj.value;
			result_1[value_1]=value_1;			
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
		if(flag==1)
		{
                    //alert("flag1");
			for(id in result_1) 
			{				
				if(result_1.hasOwnProperty(id)) 
				{			
					var vid_local=(result_1[id]).split("*");
					if(vid_local[1]!="tmp_str")
					{
						imei_iotype_arr[vid_local[0]]=vid_local[1];				
					}					
					//vid=vid+vid_local[0]+",";
					value_str=value_str+vid_local[0]+",";
				}
			}
			var strLen = value_str.length;
			value_str = value_str.slice(0,strLen-1);
                        //alert("val_str="+value_str);
		}
		return value_str;
	}
}


	var http_request = false;
	function ajaxPostRequestForData(url, parameters) 
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

		http_request.onreadystatechange = getAjaxResponseData;
		http_request.open('POST', url, true);
		http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		//http_request.setRequestHeader("Content-length", parameters.length);
		//http_request.setRequestHeader("Connection", "close");
		http_request.send(parameters);
                //alert("Closed");
	}
   
  function getAjaxResponseData()
  {
    //alert("in fun");
    //alert("IN alert CNT="+http_request.readyState);
    if (http_request.readyState == 4) 
    {
        //alert("IN alert CNT2="+http_request.status);
        if (http_request.status == 200) 
        {
            result = http_request.responseText;
            //document.getElementById("debugDiv").innerHTML=result;
            liveDataDisplay = JSON.parse(result);

            displayInfo_live();
            //alert("poststr="+poststr_route);
            var feature_id_map = document.getElementById('station_flag_map').value;
            if(feature_id_map == 1)
            {
                makePOSTRequestRoute('src/php/get_polyline_detail.php', poststr_route);
            }
            //alert("length="+liveDataDisplay.length);
        //alert("lat="+testJsonStr[0]['']);
        }
    }
  } 
  
   function makePOSTRequestText(url, parameters) 
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
        http_request.onreadystatechange = alertContentsText;
        http_request.open('POST', url, true);
        http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        http_request.setRequestHeader("Content-length", parameters.length);
        http_request.setRequestHeader("Connection", "close");
        http_request.send(parameters);
    }
   
    function alertContentsText()
    {
        if (http_request.readyState == 4) 
        {
            if (http_request.status == 200) 
            {
                result = http_request.responseText;
                //alert("response="+result);
				document.getElementById('text_col').style.display='';
				document.getElementById('text_col_content').innerHTML = result;
				blink("blinkMe","green","white",500);
            }
        }
    }  



//### ROUTE
	var http_request2 = false;
	function makePOSTRequestRoute(url, parameters) 
	{
		//alert("IN POST REQ:url="+url+" ,param="+parameters);
		http_request2 = false;
		if (window.XMLHttpRequest) 
		{ 
			http_request2 = new XMLHttpRequest();
			if (http_request2.overrideMimeType)
			{
				// set type accordingly to anticipated content type
				//http_request.overrideMimeType('text/xml');
				http_request2.overrideMimeType('text/html');
			}
		}
		else if (window.ActiveXObject) 
		{ // IE
		try 
		{
		http_request2 = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e) 
		{
		try 
		{
		http_request2 = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (e)
		{}
		}
		}
		if (!http_request2) 
		{
		alert('Cannot create XMLHTTP instance');
		return false;
		}

		http_request2.onreadystatechange = alertContentsRoute;
		http_request2.open('POST', url, true);
		http_request2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		//http_request.setRequestHeader("Content-length", parameters.length);
		//http_request.setRequestHeader("Connection", "close");
		http_request2.send(parameters);
		//alert("End");
	}
   
  function alertContentsRoute()
  {
    //alert("IN alert CNT");
    if (http_request2.readyState == 4) 
    {
       if (http_request2.status == 200) 
       {
          result = http_request2.responseText;
           //alert("resultR="+result);
           var result1=result.split("##"); 
           //alert("res1="+result1[0].trim());
          if(result1[0].trim()=="live_polyline")
          {
            //alert("BeforeRes="+document.getElementById("selected_routes").style.display);
            document.getElementById("selected_routes").style.display="";  /////////for enabling coord input type in Existing option
            //document.getElementById('selected_routes').value=result1[1];
            document.getElementById('selected_routes').innerHTML = result1[1];
            //alert("InnerHtml="+document.getElementById('selected_routes').innerHTML);
          }
       }
    }
  } 



function CreateCustomerMarker(lat,lng,station_no,type,route_no) 
{	
	var pointThis = new new google.maps.LatLng(parseFloat(lat),parseFloat(lng));
	
	//alert("InCreateMarker="+point+" ,customerIcon="+customerIcon);
	var Icon;
	var imageThis='images/customer_plant_on_map/station.png';
	var marker = new google.maps.Marker({
					position: pointThis,
					icon:imageThis
				});
	markers.push(marker);
	var action_marker;
	
	google.maps.event.addListener
	(
		marker, 'click', PlotCustomerMarker(point,Icon, marker, point,station_no,type,route_no)
	);		
}

function PlotCustomerMarker(point,Icon, marker, point,station_no,type,route_no)
{
	return function ()
	{
		//alert('in click');
		type = "Customer";
		var contentString='';
		if (infowindow) infowindow.close();
		infowindow = new google.maps.InfoWindow();
		//var latlng = new google.maps.LatLng(lat, lng);
		contentString='<table class>'+
		'<tr>'+
		'<td>Station No</td>'+
		'<td>:</td>'+
		'<td>'+station_no+'</td>'+
	   '</tr>'+
		'<tr>'+
		'<td>Station Type</td>'+
		'<td>:</td>'+
		'<td>'+type+'</td>'+
	   '</tr>'+	
		'<tr>'+
		'<td>Route No</td>'+
		'<td>:</td>'+
		'<td>'+route_no+'</td>'+
	   '</tr>'+	
	   '<tr>'+
		'<td>Point</td>'+
		'<td>:</td>'+
		'<td>'+Point+'</td>'+
	   '</tr>'+	
		'</table>';
		//alert('icontentString'+contentString);
		//alert('map_canvas'+map_canvas);			
		infowindow.setContent(contentString);
		infowindow.open(map, marker);	
	};

}

//######## POST REQUEST CUSTOMER 2
var http_request2 = false;
function makePOSTRequestMapCustomer2(url, parameters) 
{
	//alert("IN POST REQ");
	http_request2 = false;
	if (window.XMLHttpRequest) 
	{ 
	 http_request2 = new XMLHttpRequest();
	 if (http_request2.overrideMimeType)
	 {
		// set type accordingly to anticipated content type
		//http_request2.overrideMimeType('text/xml');
		http_request2.overrideMimeType('text/html');
	 }
	}
	else if (window.ActiveXObject) 
	{ // IE
	 try 
	 {
		http_request2 = new ActiveXObject("Msxml2.XMLHTTP");
	 }
	 catch (e) 
	 {
		try 
		{
		   http_request2 = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch (e)
		{}
	 }
	}
	if (!http_request2) 
	{
	 alert('Cannot create XMLHTTP instance');
	 return false;
	}

	http_request2.onreadystatechange = alertContentsMapCustomer2;
	http_request2.open('POST', url, true);
	http_request2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_request2.setRequestHeader("Content-length", parameters.length);
	http_request2.setRequestHeader("Connection", "close");
	http_request2.send(parameters);
}

function alertContentsMapCustomer2()
{
	//alert("IN alert CNT");
	if (http_request2.readyState == 4) 
	{
	   if (http_request2.status == 200) 
	   {
		  result = http_request2.responseText;
		  //alert(result);
	   }
	}
}   
//################################
  
//############## CODE FOR STATION TYPE ACCOUNT CLOSED ################
</script>
