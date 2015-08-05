<?php 
include_once("user_type_setting.php"); 
?>
<script type="text/javascript">
//LIVE JS MODULE
var label_type = "<?php echo $report_type; ?>";
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

var customerIcon= new GIcon();
customerIcon.image = 'images/customer_images/star_green.png';
customerIcon.iconSize = new GSize(10, 10);
customerIcon.iconAnchor = new GPoint(2, 7);
customerIcon.infoWindowAnchor = new GPoint(3, 10);

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

uniqueRouteMorningParseJson = JSON.parse( <?php echo json_encode($_SESSION['uniqueRouteArrMorningNew']); ?> );
uniqueRouteEveningParseJson=JSON.parse( <?php echo json_encode($_SESSION['uniqueRouteArrEveningNew']); ?> );
var uniqueRouteParseJson = JSON.parse( <?php echo json_encode($_SESSION['uniqueRouteTransporters']); ?> );

var liveDataDisplay;




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
var imei_iotype_arr=new Array();

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
/*function deselect_route()
{
	var obj = document.forms[0].route;
	//alert("obj="+obj);
	if(obj.length!=undefined)
	{
		//alert("obj.length="+obj.length);
		for (var i=0;i<obj.length;i++)
		{
			obj[i].checked==false;
		}
	}
}*/
		
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


function filter_live_vehicle(obj,jsActionNo)
{  
	//alert("filter live vehicle");
	var result ="";
	if(route_div_flag ==1)
	{
		var s_vehicle =document.forms[0].elements['live_vehicles[]'];
		
		//var obj = document.forms[0].live_opt;
		//alert("obj="+obj);		
		//var result_v =checkbox_selection(obj);
		//var s_vehicle = result_v.split(',');
		
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
		
		//alert("obj_r="+obj_r)
		var result_r=checkbox_selection(obj_r);
		var str1 = result_r.split(',');
		
		//alert("size_v="+s_vehicle.length+" ,route_len="+str1.length);
		var k=0;		
		for(var i=0;i<str1.length;i++)
		{			
			var str2 = str1[i].split(':');
			var route = str2[0];
			var r_vehicle = str2[1];
			//alert("str1="+str1+" ,route="+route+" ,r_v="+r_vehicle);
			
			if(s_vehicle.length!=undefined)
			{
				//alert("obj.length="+s_vehicle.length);
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
		//alert("result in route="+result);
	}
	else
	{
		var obj=document.forms[0].elements['live_vehicles[]'];
		result=checkbox_selection(obj);
		//alert("result in vehicle="+result);
	}
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
	if(s1.length>50)
	{
		alert("Please select maximum 50 Vehicles at a time");
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
		//alert("DomP");
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
	  //alert("xmlDoc="+xmlDoc);
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

function movingVehicle_prev(jsActionNo)
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
	marker_type = lvIcon1;
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
					
					Load_MovingData_Map(startdate,enddate,pt_for_zoom,zoom_level,status);
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

function Load_MovingData_Map(startdate,enddate,pt_for_zoom,zoom_level,status)
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
        var dest = "../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml"
       
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
        
        //alert("length1="+liveDataDisplay.length);
        
		    //makePOSTRequestMap('src/php/get_filtered_xml.php', poststr);			
			  thisdest = "../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
        TryCnt =0;
        //clearTimeout(timer);
        timer = setTimeout('displayInfo_live()',1000);        
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

function displayInfo_live()
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
        //alert("t11111111==="+liveDataDisplay[k].getAttribute("datetime"));												
        lat_tmp = liveDataDisplay[k]['latitudeLR'];
        lng_tmp = liveDataDisplay[k]['longitudeLR'];	

        lat_arr[len2] = liveDataDisplay[k]['latitudeLR'];
        lng_arr[len2] = liveDataDisplay[k]['longitudeLR'];
        vid_arr[len2] = liveDataDisplay[k]['deviceImeiNo'];
        vehiclename_arr[len2] = liveDataDisplay[k]['vehicleName'];
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
        getxml_MovingData(len2, flag, lat_arr, lng_arr, vid_arr, vehiclename_arr, speed_arr, datetime_arr, fuel_arr, running_status_arr, day_max_speed_arr, day_max_speed_time_arr, last_halt_time_arr,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr);	  //HERE ARR IS JUST NORMAL VARIABLE NOT ARRAY
        //alert("K");
        document.getElementById('prepage').style.visibility='hidden';	
    }     		
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

function getxml_MovingData(len2, flag1, lat_arr, lng_arr, vid_arr, vehiclename_arr, speed_arr, datetime_arr, fuel_arr, running_status_arr, day_max_speed_arr,day_max_speed_time_arr,last_halt_time_arr,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr)
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
    	Moving_DataMarkers(lat_arr,lng_arr,vid_arr,vehiclename_arr,speed_arr,datetime_arr, fuel_arr, len2, running_status_arr, day_max_speed_arr,day_max_speed_time_arr,last_halt_time_arr,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr);

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

function Moving_DataMarkers(lat_arr,lng_arr,vid_arr,vehiclename_arr,speed_arr,datetime_arr, fuel_arr, len2, running_status_arr, day_max_speed_arr,day_max_speed_time_arr,last_halt_time_arr,io1_arr,io2_arr,io3_arr,io4_arr,io5_arr,io6_arr,io7_arr,io8_arr)
{	
	var gmarkersA = new Array();      
	var gmarkersB = new Array();  
	var gmarkersC = new Array();  
	
	var j = 0;
	var colr = ["#00FF66","#0066FF","#FF0000","#33FFFF","#FF33CC","#9966FF","#FF9900","#FFFF00"];
	var i,vehiclename,speed,point,datetime,place,marker,polyline,last,running_status1, day_max_speed,day_max_speed_time,last_halt_time;
  
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
	
	vlist ="<table style='font-size:11px;'><tr style='background-color:004D96;color:#FFFFFF'><td align=center><strong>LIVE TRACKING LEGEND</strong></td></tr></table><br><table style='font-size:10px;' CELLPADDING=0 CELLSPACING=0><tr><td></td></tr>";
	  
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
    var tmp=1;
  	for (i = 0; i < len2; i++) 
	{
		tmp=tmp+i;
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
		
		//alert("point recieved="+point);
		pt[i] = point;
		place=0;				
    
	// PLOT LABEL
	//var label_detail = "<font color=#006600><strong>"+vehiclename+"</strong></font><font color=#FF0000><strong>["+running_status1+"]</strong></font>";

	/*if(running_status1 == "Running")
	{
	  var label_detail = "<font color=blue><strong>"+vehiclename+"</strong></font><font color=red><strong>("+running_status1+")</strong></font>";
	}
	else
	{
	  var label_detail = "<font color=blue><strong>"+vehiclename+"</strong></font><font color=red><strong>("+running_status1+")</strong></font>";      
	}*/
		
	var label_detail = "<div style='width:80px;'>"+vehiclename+"</div>";
	//var label_detail = vehiclename;        
				   
	//var label = new ELabel(new GLatLng(43.9,-79.5), "Utopia", "style2");
	//map.addOverlay(label);
	  
	//FILTER INVALID DATETIME
	/*var date1string = datetime;
	var date1tmp = date1string.replace(/-/g,"/");
	//alert("date1tmp="+date1tmp);

	var previou_datetime = new Date(date1tmp);//yyyy-mm-dd format
	//var date2 = new Date('2012/08/09 11:50:10');
	var current_datetime = new Date();       
	/////////////////////////

	var d1 = (previou_datetime.getTime())/(1000*60);
	var d2 = (current_datetime.getTime())/(1000*60);	
	//alert("d1="+d1+" ,d2="+d2);

	var timediff = Math.abs(d2 - d1);
	//alert("diff="+diff);

	if(timediff > 8)
	{
		//alert("IDLE");
		running_status1 == "Stop";
	} */
   /*   
    if(running_status1 == "Running")
    {
      var label = new ELabel(point, label_detail, "style1");
    }
    else if(running_status1 == "Idle")
    {
      var label = new ELabel(point, label_detail, "style2"); 
    }
    else
    {
      var label = new ELabel(point, label_detail, "style3"); 
    } 
	*/
	//violet = #EE82EE
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

	if(running_status1 == "Running")
	{
	  label = new ELabel(point, label_detail, "style1");
	}
	else if(running_status1 == "Idle")
	{
	  label = new ELabel(point, label_detail, "style2"); 
	}
	else
	{
	  label = new ELabel(point, label_detail, "style3"); 
	} 	
	
	//############## COLOR SETTING CODE  ###########
		
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

		//document.getElementById('someElementId').className = 'cssClass';
		//$('<style>.style4 {color: white; background-color: '+font_color+';} input::-webkit-outer-spin-button: {display: none;}</style>').appendTo('head');
		label = new ELabel(point, label_detail, "style"+tmp); 		
	}
    
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
        		
            if(document.getElementById('trail_path_real').checked)
            {
              map.addOverlay(polyline);		//COMMENTED TO PREVENT TRAIL
            }
            
            //ADD DIRECTION ARROWS
			lat1 = point_prev[j].lat();
			lng1 = point_prev[j].lng();

			lat2 = point.lat();
			lng2 = point.lng();	

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
                         
            var distance = calculate_distance(point_prev[j].lat(), point.lat(), point_prev[j].lng(), point.lng());
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
				label_prev[j] = label;
				date_prev[j] = datetime;
					angle_prev[j] = angle_deg;
			}
      		
			trail_flag = true;      		
            		
			break;
    	}
	  }
	}    
	//
    //alert("font_color2="+font_color);                                                                                                                          
    marker = Create_MovingDataMarkers(angle_deg, point, vid, vehiclename, speed, datetime, fuel, len2, font_color, gmarkersC,p, running_status1, total_dist, day_max_speed, day_max_speed_time, last_halt_time,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8);
        
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

function Create_MovingDataMarkers(angle_deg, point, imei, vehiclename, speed, datetime, fuel, len2, font_color, gmarkersC,p, running_status, total_dist, day_max_speed, day_max_speed_time, last_halt_time,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8) 
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
  	day_max_speed1[p] = new Array();
  	day_max_speed_time1[p] = new Array();
  	last_halt_time1[p] = new Array();	
	
	//var lt_1 = Math.round(point.lat()*100000)/100000; 
	//var ln_1 = Math.round(point.lng()*100000)/100000;
	
	var lat = point.lat(); 
	var lng = point.lng();	

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
  
  	//vlist += "<tr><td><a href='#' style='text-decoration:none;' Onclick='javascript:Prev_PlotLastMarkerWithAddress(\""+vIcon+"\",\""+lat+"\",\""+lng+"\",\""+p+"\",\""+imei+"\",\""+vehiclename+"\",\""+speed+"\",\""+datetime+"\",\""+fuel+"\",\""+running_status+"\",\""+total_dist+"\");'>"+img+"<font color=#006600>"+vehiclename+"</font>&nbsp;&nbsp;<font color=red>("+running_status+")</font></a></td></tr><tr><td><a href='#' style='text-decoration:none;' Onclick='javascript:Prev_PlotLastMarkerWithAddress(\""+vIcon+"\",\""+lat+"\",\""+lng+"\",\""+p+"\",\""+imei+"\",\""+vehiclename+"\",\""+speed+"\",\""+datetime+"\",\""+fuel+"\",\""+running_status+"\",\""+total_dist+"\");'><font color=blue>("+imei+")</font></a></td></tr><tr><td>&nbsp;</td></tr>";
			
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
		vlist += "<tr><td><a href='#' style='text-decoration:none;' Onclick='javascript:Prev_PlotLastMarkerWithAddress(\""+vIcon+"\",\""+lat+"\",\""+lng+"\",\""+p+"\",\""+imei+"\",\""+vehiclename+"\",\""+speed+"\",\""+datetime+"\",\""+fuel+"\",\""+day_max_speed+"\",\""+day_max_speed_time+"\",\""+last_halt_time+"\",\""+running_status+"\",\""+total_dist+"\",\""+route+"\",\""+io_1+"\",\""+io_2+"\",\""+io_3+"\",\""+io_4+"\",\""+io_5+"\",\""+io_6+"\",\""+io_7+"\",\""+io_8+"\");'>"+img+"<font color="+font_color+">"+vehiclename+"</font>&nbsp;<font color=red>("+route+")</font>&nbsp;<font color=blue>["+running_status+"]</font></a></td></tr><tr><td><a href='#' style='text-decoration:none;' Onclick='javascript:Prev_PlotLastMarkerWithAddress(\""+vIcon+"\",\""+lat+"\",\""+lng+"\",\""+p+"\",\""+imei+"\",\""+vehiclename+"\",\""+speed+"\",\""+datetime+"\",\""+fuel+"\",\""+day_max_speed+"\",\""+day_max_speed_time+"\",\""+last_halt_time+"\",\""+running_status+"\",\""+total_dist+"\",,\""+route+"\"\""+io_1+"\",\""+io_2+"\",\""+io_3+"\",\""+io_4+"\",\""+io_5+"\",\""+io_6+"\",\""+io_7+"\",\""+io_8+"\");'></a></td></tr><tr><td>&nbsp;</td></tr>";
	}
	else
	{
		vlist += "<tr><td><a href='#' style='text-decoration:none;' Onclick='javascript:Prev_PlotLastMarkerWithAddress(\""+vIcon+"\",\""+lat+"\",\""+lng+"\",\""+p+"\",\""+imei+"\",\""+vehiclename+"\",\""+speed+"\",\""+datetime+"\",\""+fuel+"\",\""+day_max_speed+"\",\""+day_max_speed_time+"\",\""+last_halt_time+"\",\""+running_status+"\",\""+total_dist+"\",\""+route+"\",\""+io_1+"\",\""+io_2+"\",\""+io_3+"\",\""+io_4+"\",\""+io_5+"\",\""+io_6+"\",\""+io_7+"\",\""+io_8+"\");'>"+img+"<font color="+font_color+">"+vehiclename+"</font>&nbsp;&nbsp;<font color=blue>["+running_status+"]</font></a></td></tr><tr><td><a href='#' style='text-decoration:none;' Onclick='javascript:Prev_PlotLastMarkerWithAddress(\""+vIcon+"\",\""+lat+"\",\""+lng+"\",\""+p+"\",\""+imei+"\",\""+vehiclename+"\",\""+speed+"\",\""+datetime+"\",\""+fuel+"\",\""+day_max_speed+"\",\""+day_max_speed_time+"\",\""+last_halt_time+"\",\""+running_status+"\",\""+total_dist+"\",\""+route+"\",\""+io_1+"\",\""+io_2+"\",\""+io_3+"\",\""+io_4+"\",\""+io_5+"\",\""+io_6+"\",\""+io_7+"\",\""+io_8+"\");'></a></td></tr><tr><td>&nbsp;</td></tr>";
	}
  	//alert("vlist="+vlist);
  	//////////////////////////////
  
  	if(running_status == "Running")
  	{
    	//PlotLastMarkerWithAddress(point, vIcon, marker, imei, vehiclename, speed,datetime, fuel, running_status, total_dist, day_max_speed, day_max_speed_time, last_halt_time,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8);
  	}
  
  	GEvent.addListener(marker, 'mouseover', function()
  	{			
  		PlotLastMarkerWithAddress(point, vIcon, marker, imei, vehiclename, speed,datetime, fuel, running_status, total_dist, route, day_max_speed, day_max_speed_time, last_halt_time,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8);
  	});  

	return marker;		
}				

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

function Prev_PlotLastMarkerWithAddress(vIcon, lat, lng, mcounter, imei, vehiclename, speed, datetime, fuel, day_max_speed, day_max_speed_time, last_halt_time, running_status, total_dist,route,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8)
{
	remark = "-";
	transporter = "-";
	lat = trim(lat);
	lng = trim(lng);
	imei = trim(imei);
	vehiclename = trim(vehiclename);
	speed = trim(speed);
	datetime = trim(datetime);
	fuel = trim(fuel);
	running_status = trim(running_status);

	day_max_speed = trim(day_max_speed);
	day_max_speed_time = trim(day_max_speed_time); 
	last_halt_time = trim(last_halt_time);      

	var point = new GLatLng(parseFloat(lat),parseFloat(lng));  	

	var feature_id_map = document.getElementById('station_flag_map').value;
	if(feature_id_map == 1)
	{
		/*var flag_new = 1;
		for(var i=0;i<imei_clicked.length;i++)
		{
			if(imei == imei_clicked[i])
			{
				flag_new = 0;
			}
		}
			
		if(flag_new == 1)
		{*/
			//Load_Data2(route,lat,lng,vehiclename);
			imei_clicked.push(imei);
		//}
		
		//########## STORE MARKER FOR RE-REFRESH AFTER CUSTOMER PLOTTED		
		point_after = point;
		vIcon_after = vIcon;
		marker1_after = marker1[mcounter];
		imei_after = imei;
		vehiclename_after = vehiclename;
		speed_after = speed;
		datetime_after = datetime;
		fuel_after = fuel;
		running_status_after = running_status;
		total_dist_after = total_dist;
		route_after = route;
		day_max_speed_after = day_max_speed;
		day_max_speed_time_after = day_max_speed_time;
		last_halt_time_after = last_halt_time;
		io_1_after = io_1;
		io_2_after = io_2;
		io_3_after = io_3;
		io_4_after = io_4;
		io_5_after = io_5;
		io_6_after = io_6;
		io_7_after = io_7;
		io_8_after = io_8;		
		//#############################################################
	}
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

	PlotLastMarkerWithAddress(point, vIcon, marker1[mcounter], imei, vehiclename, speed, datetime, fuel, running_status, total_dist, route, day_max_speed, day_max_speed_time, last_halt_time,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8);
}

var ad=0;
var place;
var address1=0;

////////////////////////// PLOT TRACK MARKERS WITH ADDRESSES ////////////////////////////////////////
function PlotLastMarkerWithAddress(point, Icon, marker, imei, vehiclename, speed,datetime, fuel, running_status, total_dist, route, day_max_speed, day_max_speed_time, last_halt_time,io_1,io_2,io_3,io_4,io_5,io_6,io_7,io_8) 
{
	 //alert("IN PLOT:"+point+":"+Icon+":"+marker+":"+imei+":"+vehiclename+":"+speed+":"+datetime+":"+fuel+":"+running_status);
	 
	 //:(17.66392, 75.8931):[object Object]:[object Object]:359231030166217:ACC-BC-95-100-R4:0:2011-12-16 11:23:43:0
	 
	 //var Icon= new GIcon(lvIcon1);
	 //var marker = new GMarker(point, Icon);
	 //alert("vIcon="+Icon+" ,marker="+marker);	 	
	var accuracy;
	var largest_accuracy;	   
	var delay = 100;
	 
	var window_style1="style='color:#000000;font-family: arial, helvetica, sans-serif; font-size:11px;text-decoration:none;font-weight:bold;'";
	var window_style2="style='color:blue;font-family: arial, helvetica, sans-serif; font-size:11px;text-decoration:none;'";
	var io_str="";
	var window_height="135px";
	
	if(imei_iotype_arr[imei]!=undefined)
	{
		var iotype_iovalue_str=imei_iotype_arr[imei].split(":");
		if(iotype_iovalue_str.length==2)
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
		}
		for(var i=0;i<iotype_iovalue_str.length;i++)
		{
			var iotype_iovalue_str1=iotype_iovalue_str[i].split("^");
			//alert("iotype_iovalue_str1="+iotype_iovalue_str1[0]);	
			if(iotype_iovalue_str1[0]=="")
			{
				io_values="ioNotExist";
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
							io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">"+eval(io_values)+"</td></tr>";
						}
						else
						{
							io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";
						}
					}
					else
					{
						io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";
					}
				}
				else if(iotype_iovalue_str1[1]=="ac")
				{                                       
					if(eval(io_values)!="" && eval(io_values)!=undefined)
					{
							if(eval(io_values)>500)
							{
									io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">OFF</td></tr>";
							}
							else
							{
									io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">ON</td></tr>";
							}
					}
					else
					{
						io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";
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
						//alert("io="+getFinalIO[1]);
						if(getFinalIO[1]==1)
						{
							if(eval(io_values)<350)
							{
								io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">ON</td></tr>";
							}
							else
							{
								io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">Off</td></tr>";
							}
						}
						else
						{
							if(eval(io_values)<=350)
							{
									io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">Off</td></tr>";
							}
							else
							{
									io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">ON</td></tr>";
							}
						}
					}
					else
					{
						io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";
					}
				}
				else if(iotype_iovalue_str1[1]=="door_open")
				{                                       
					if(eval(io_values)!="" && eval(io_values)!=undefined)
					{
							if(eval(io_values)<250)
							{
									io_str=io_str+"<tr><td "+window_style1+">Delivery Door</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">Close</td></tr>";
							}
							else
							{
									io_str=io_str+"<tr><td "+window_style1+">Delivery Door</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">Open</td></tr>";
							}
					}
					else
					{
						io_str=io_str+"<tr><td "+window_style1+">Delivery Door</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";
					}
				}
				else if(iotype_iovalue_str1[1]=="door_open2")
				{                                       
					if(eval(io_values)!="" && eval(io_values)!=undefined)
					{
							if(eval(io_values)<250)
							{
									io_str=io_str+"<tr><td "+window_style1+">Manhole Door</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">Close</td></tr>";
							}
							else
							{
									io_str=io_str+"<tr><td "+window_style1+">Manhole Door</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">Open</td></tr>";
							}
					}
					else
					{
						io_str=io_str+"<tr><td "+window_style1+">Manhole Door</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";
					}
				}
				else if(iotype_iovalue_str1[1]=="door_open3")
				{                                       
					if(eval(io_values)!="" && eval(io_values)!=undefined)
					{
							if(eval(io_values)<250)
							{
									io_str=io_str+"<tr><td "+window_style1+">Manhole Door2</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">Close</td></tr>";
							}
							else
							{
									io_str=io_str+"<tr><td "+window_style1+">Manhole Door2</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">Open</td></tr>";
							}
					}
					else
					{
						io_str=io_str+"<tr><td "+window_style1+">Manhole Door2</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";
					}
				}

				else 
				{
					if(eval(io_values)!="" && eval(io_values)!=undefined)
					{					
						io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">"+eval(io_values)+"</td></tr>";
					}
					else
					{
						io_str=io_str+"<tr><td "+window_style1+">"+iotype_iovalue_str1[1]+"</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";
					}			
				}
			}
		}
	}

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
                       
                      nearest_customer_string = "<tr><td "+window_style1+">RouteNo</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">"+route+"</td></tr><tr><td "+window_style1+">Nearest Customer</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">"+e_customer_min_distance+" From "+e_customer_print_str+"</td></tr>";
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
                      nearest_customer_string = "<tr><td "+window_style1+">RouteNo</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">"+route+"</td></tr><tr><td "+window_style1+">Nearest Customer</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">"+m_customer_min_distance+" From "+m_customer_print_str+"</td></tr>";
                    }             
                }
		//################# GET TRANSPORTER AND REMARK #######################
                //alert('vehiclename='+vehiclename);
                if(morningFlag==1 || eveningFlag==1)
                {
		//transporter_remark_string = "<tr><td "+window_style1+">Transporter</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr><tr><td "+window_style1+">Remark</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">-</td></tr>";			
                transporter_remark_string = "<tr><td "+window_style1+">Transporter</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">"+uniqueRouteParseJson[vehiclename]+"</td></tr>";			
                }
    /*if(remark_tmp.length>0 && transporter_tmp.length>0)
		{
			var match_v = false;
			var sel_vehicle = "";
			var tpt_final = "";
			for(var j=0;j<transporter_tmp.length;j++)
			{				
				tpt_tmp = transporter_tmp[j].split(":");
				sel_vehicle = tpt_tmp[0];
				if(trim(sel_vehicle ) == trim(vehiclename))
				{
					match_v = true;
					tpt_final = tpt_tmp[1];
					break;
				}
			}
			if(match_v)
			{
				remark = remark_tmp[0];		//####### remark will be the same each case (Vehicle & Route has single remark)
				transporter = tpt_final;  //######### tpt string 
				transporter_remark_string = "<tr><td "+window_style1+">Transporter</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">"+transporter+"</td></tr><tr><td "+window_style1+">Remark</td><td>&nbsp;:&nbsp;</td><td "+window_style2+">"+remark+"</td></tr>";			
			}			
		}*/		
		//################# TRANSPORTER AND REMARK CLOSED ####################				
	}

 var geocoder = new GClientGeocoder();
 var address_tmp;
 var address1_tmp;
 var BadAddress=0;

 geocoder.getLocations(point, function (result) {

  var address2=""; // for getting the location from google map or xml
	if (result.Status.code == G_GEO_SUCCESS) // OR !=200
	{
		var j;
		for (var i=0; i<result.Placemark.length; i++)
		{
			accuracy = result.Placemark[i].AddressDetails.Accuracy;
			address_tmp = result.Placemark[i];
			address1_tmp = address_tmp.address;
			//alert("address1_tmp="+address1_tmp+"accuracy="+accuracy);
			if(accuracy!=0 && accuracy!=1 && accuracy!=2 && accuracy!=5 && accuracy!=7 && accuracy!=8)
			{
				if(accuracy==6)  /// this is street leve aprox accurate
				{					
					if((address1_tmp.indexOf("NH") ==-1) && (address1_tmp.indexOf("National Highway") ==-1) && (address1_tmp.indexOf("State Highway")==-1))
					{					
						address2=get_js_location(result.Placemark[i],point);
						break;
					}		 
				}		
				else if(accuracy==3) /////// this is country munciple level address 
				{			
					address2=get_js_location(result.Placemark[i],point);
					break;
				}
				else
				{
					if(accuracy==4) /////////// city,village level address
					{					
						address2=get_js_location(result.Placemark[i],point);
						break;			
					}					
				}
			}
		}		
	}  // if (result.Status.code == G_GEO_SUCCESS)  CLOSED
	else
	{
		address2 ="-";
	}
	if(address2=="" || address2=="-") // if address not come form google map then this block get address from xml
	{					
		address2=get_xml_location(point);
		//alert("xml_loacation_2="+address2);	
	}
		
	var place;
	
	///////////////////////////// SELECT LANDMARK OR GOOGLE PLACE CODE /////////////////////////////////////////////////////
	/// IF DISTANCE CALCULATED THROUGH FILE IS LESS THAN 1 KM THEN DISPLAY LANDMARK OTHERWISE DISPLAY GOOGLE PLACE /////////
	
	var lt_original = point.lat();
	var lng_original = point.lng();
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
    	//var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="height:150px;" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>IMEI</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+imei + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td colspan=3><font color=blue size=2>( '+point.lat()+', '+point.lng()+' )</font></td></tr></table></div>');
  	                       
      day_max_speed = day_max_speed.trim();
      day_max_speed = Math.round(day_max_speed*100)/100 + " kmph";
	  
	  // if(label_type!="Person")
  	  {
        if(day_max_speed =="0 kmph" || day_max_speed=="")
        {
          var day_max_speed_string = '';
        }
        else
        {          
          var day_max_speed_string = day_max_speed+' &nbsp;('+day_max_speed_time+')';
        }
                
        if((document.getElementById('trail_path').checked) && (running_status=="Running"))
        {
          if(total_dist==0)
          {
           total_dist = "less than 1";
          }
          var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="'+window_height+';" align=left><table cellpadding=0 cellspacing=0><tr><td '+window_style1+'>Vehicle</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+vehiclename + '</td><td></td></tr><tr><td '+window_style1+'>IMEI</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+imei + '</td><td></td></tr><tr><td '+window_style1+'>Speed</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+speed+' kmph</td></tr><tr><td '+window_style1+'>Date & Time</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+datetime+' '+'&nbsp;&nbsp;</td></tr><tr><td '+window_style1+'>Day Max Speed</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+day_max_speed_string+'</td></tr><tr><td '+window_style1+'>Last HaltTime</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+last_halt_time+'</td></tr><tr><td '+window_style1+'>Place</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+place+'</td></tr><tr><td '+window_style1+'>Status</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+running_status+'</td></tr><tr><td '+window_style1+'>Distance Covered</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+total_dist+'&nbsp;km</td></tr>'+io_str+''+nearest_customer_string+''+transporter_remark_string+'</table></div>');
        }
        else
        {
          var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="'+window_height+'" align=left><table cellpadding=0 cellspacing=0><tr><td '+window_style1+'>Vehicle</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+vehiclename + '</td><td></td></tr><tr><td '+window_style1+'>IMEI</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+imei+'</td><td></td></tr><tr><td '+window_style1+'>Speed</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+speed+' kmph</td></tr><tr><td '+window_style1+'>Date & Time</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+datetime+' '+'&nbsp;&nbsp;</td></tr> <tr><td '+window_style1+'>Day Max Speed</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+day_max_speed_string+'</td></tr><tr><td '+window_style1+'>Last HaltTime</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+last_halt_time+'</td></tr><tr><td '+window_style1+'>Place</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+place+'</td></tr><tr><td '+window_style1+'>Status</td><td>&nbsp;:&nbsp;</td><td '+window_style2+'>'+running_status+'</td></tr>'+io_str+''+nearest_customer_string+''+transporter_remark_string+'</table></div>');
        }
     }  
      
     /* if(label_type!="Person")
  	  {
        if(day_max_speed =="0 kmph" || day_max_speed=="")
        {
          var day_max_speed_string = '';
        }
        else
        {          
          var day_max_speed_string = day_max_speed+' &nbsp;('+day_max_speed_time+')';
        }
                
        if((document.getElementById('trail_path').checked) && (running_status=="Running"))
        {
          if(total_dist==0)
          {
           total_dist = "less than 1";
          }
          var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="height:170px;" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>IMEI</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+imei + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Day Max Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+day_max_speed_string+'</font></td></tr><tr><td><font size=2 color=#000000>Last HaltTime</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+last_halt_time+'</font></td></tr><tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td><font size=2 color=#000000>Status</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+running_status+'</font></td></tr><tr><td><font size=2 color=#000000>Temp</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+fuel+'</font></td><td></td></tr><tr><td><font size=2 color=#000000>Distance Covered</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+total_dist+'&nbsp;km</font></td></tr></table></div>');
        }
        else
        {
          var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="height:170px;" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>IMEI</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+imei + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Day Max Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+day_max_speed_string+'</font></td></tr><tr><td><font size=2 color=#000000>Last HaltTime</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+last_halt_time+'</font></td></tr><tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td><font size=2 color=#000000>Status</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+running_status+'</font></td></tr><tr><td><font size=2 color=#000000>Temp</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+fuel+'</font></td><td></td></tr></table></div>');
        }
     }*/     
    //}
  	/*else
  	{
  		//var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Person</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+' '+'&nbsp;&nbsp;</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td colspan=3><font color=blue size=2>( '+point.lat()+', '+point.lng()+' )</font></td></tr></table></div>');
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
     
  });
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
function ShowMarker(point, landmark) 
{		
	//alert("pt="+point);
	//alert("lnmrk="+landmark);
 	var Icon= new GIcon(lnmark);
	var marker = new GMarker(point,Icon);
	var marker2 = new GMarker(point,Icon);
 	var lat = Math.round((point.lat())*100000)/100000;
	var lng = Math.round((point.lng())*100000)/100000;
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


/*function checkbox_selection(obj)
{
	var result_1 = {};
	var flag=0;
	var cnt=0;
	var value_str="";
	if(obj.length!=undefined)
	{
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].checked==true)
			{				
				if(cnt==0)
				{			
					var value_tmp=obj[i].value;
					result_1[value_tmp]=value_tmp;
					cnt=1;
				}
				else
				{			
					var value_tmp=obj[i].value;
					result_1[value_tmp]=value_tmp;					
				}
				flag=1;
			}	  
		}
	}
	else
	{
		if(obj.checked==true)
		{
			value_str=obj.value;
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
		if(cnt==1)
		{
			for(id in result_1) 
			{				
				if(result_1.hasOwnProperty(id)) 
				{ 
					value_str=value_str+result_1[id]+",";
				}
			}
			var strLen = value_str.length;
			value_str = value_str.slice(0,strLen-1);		
		}
		//alert("value_str="+value_str);
		return value_str;
	}
}*/
function checkbox_selection(obj)
{
	imei_iotype_arr.length=0;
	var result_1 = {};
	var flag=0;
	var cnt=0;
	var value_str="";
	if(obj.length!=undefined)
	{
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
		if(obj.checked==true)
		{
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
		}
		return value_str;
	}
}

/*function checkbox_selection(obj)
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
}*/


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
		  //alert("result="+result);
          liveDataDisplay = JSON.parse(result);
          displayInfo_live();
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
/////////// CODE FOR SELECTING LANDMARK ON ZOOM ///////////////////////////////////////

function getLandMark1(event,newzoomlevel)
{
	//alert("landmark");
   var newzoomlevel= map.getZoom();				
	GDownloadUrl("src/php/select_landmark_live_test.php", function(data)
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


//############# CODE FOR PLOTTING CUSTOMERS -STATION TYPE ACCOUNT ###############

function Load_Data2(route,lat,lng,vehiclename)
{    
	for(var mk=0;mk<marker_customer.length;mk++)
	{		
		map.removeOverlay(marker_customer[mk]);
	}
	mcounter = 0;
	//alert("LoadData2");
	if (GBrowserIsCompatible()) 
	{	  			
		//alert("in GBrowserIsCompatible")
		//map.clearOverlays();	
		//alert('user_date='+user_dates.length+'vehicleserial='+vehicleSerial);
		if(route!=null)
		{
			var date = new Date();
			// COPY ORIGINAL XML FILE        
			var dest2 = "../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
			//alert("dest2="+dest2);
			//var dest2 = "src/php/xml_tmp/filtered_xml/tmp_1296469048456.xml";
			thisdest2 = dest2;
			//thismode = dmode;
			//thisaccess2 = access2;

			// MAKE FILTERED COPY        
			var poststr = "xml_file_customer=" + encodeURI( dest2 )+
			"&route=" + encodeURI( route ) +
			"&vehicle=" + encodeURI( vehiclename );		

			//alert("poststr="+poststr);                                          
			makePOSTRequestMapCustomer2('src/php/get_route_customers.php', poststr);			
			TryCnt2 =0;
			clearTimeout(timer);
			timer = setTimeout('displayInfo2()',1000);
			//alert("After displayinfo2");
		} // if vid closed
	} //is compatible closed
	//alert("after function");
} //function load2 closed


function displayInfo2()
{
	var station_no;
	var lat;
	var lng;
	var lat_v;
	var lng_v;	
	var type;
	var route_no;
	var remark;
	var transporter;
	var vehicle;
	var xml_data; 
	var DataReceived = false;  

	var matched_customer ="";
	lat_customer_tmp = null;
	lng_customer_tmp = null;
	matched_customer_tmp = null;
	remark_tmp = null;
	transporter_tmp = null;	
	
	lat_customer_tmp=new Array();
	lng_customer_tmp=new Array();
	matched_customer_tmp=new Array();	
	remark_tmp = new Array();
	transporter_tmp = new Array();
		
	try
	{
		var bname = navigator.appName;		  			  
		var xmlObj = null;        
		//alert("thisdest2="+thisdest2);
		xmlObj = loadXML(thisdest2);

		//alert("xmObj2="+xmlObj);    
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
				if(TryCnt2<=MAX_TIMELIMIT)
				{
				  TryCnt2++;
				  clearTimeout(timer);
				  timer = setTimeout('displayInfo2()',1000);
				}
			}                                
		}
		else
		{
			//alert("In Mozilla");
			xml_data = xmlObj.documentElement.getElementsByTagName("marker");
			//alert("xml2 length:"+xml_data.length);
			//var xml_data1 = xmlObj.getElementsByTagName("t1");
			var xml_data1 = xmlObj.documentElement.getElementsByTagName("a1");
			//alert("DisplayInfo2_length:"+xml_data.length);
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
				  timer = setTimeout('displayInfo2()',1000);
				}
			}
		}   
		//alert("xml_data="+xml_data);             
	}
	catch(err)
	{
		alert("sorry! Unable to get Customer Information");
	}    								
				
	if((((xml_data.length==0) || (xml_data.length==undefined)) && (DataReceived==true)) || (TryCnt>=MAX_TIMELIMIT))
	{	
		//alert("No Customer Data Found");		
		clearTimeout(timer);	
		var poststr = "dest=" + encodeURI( thisdest2 );
		makePOSTRequestMapCustomer2('src/php/del_xml.php', poststr);								
	}
	else if(DataReceived==true)
	{	
		clearTimeout(timer);
		var len2=0;
			
		/////////////// GET IO ///////////////
		var lat="", lng ="";
		var marker;
		var gmarkersA = new Array();
		
		var lowest_dist = 0;		
		
		for (var k = 0; k < xml_data.length; k++) 
		{																																		
			lat = xml_data[k].getAttribute("lat");
			lng = xml_data[k].getAttribute("lng");			
			
			lat = lat.substring(0, lat.length - 1);
			lng = lng.substring(0, lng.length - 1);			
					
			station_no = xml_data[k].getAttribute("station_no");
			type = xml_data[k].getAttribute("type");
			route_no = xml_data[k].getAttribute("route_no");			
			remark = xml_data[k].getAttribute("remark");
			transporter = xml_data[k].getAttribute("transporter");	
			
			var point = new GLatLng(parseFloat(lat),parseFloat(lng));
			
			lat_customer_tmp[k] = lat;
			lng_customer_tmp[k] = lng;
			matched_customer_tmp[k] = station_no;
			remark_tmp[k] = remark;
			transporter_tmp[k] = transporter;			

			//alert("lat="+lat+" ,lat_v="+lat_v+" ,lng="+lng+" ,lng_v="+lng_v);
			//len2++;	
			//alert(point+station_no+","+type+","+route_no);
			
			//CREATE STORE MARKERS
			//alert("Before Call CustomerMarker");
			if(lat!="-" && lng!="-")
			{
				marker = CreateCustomerMarker(lat,lng,station_no,type,route_no);		
				gmarkersA.push(marker);
			}
			//#################### NEXT DATA CLOSED ##########################/
           			
		}	//XML LEN LOOP CLOSED
		
		//alert("lenC=="+lat_customer_tmp.length);		
		//###### PLOT MARKERS
		//alert("gmarkersA.length="+gmarkersA.length+" ,map="+map);
		for(var m=0;m<gmarkersA.length;m++)
			map.addOverlay(gmarkersA[m]);		
		//###################
		//alert("After Plot CustomerMarker");
		
		var poststr = "dest=" + encodeURI( thisdest2 );
		makePOSTRequestMapCustomer2('src/php/del_xml.php', poststr);	

		//######## REFRESH LAST CLICKED MARKER
		PlotLastMarkerWithAddress(point_after, vIcon_after, marker1_after, imei_after, vehiclename_after, speed_after, datetime_after, fuel_after, running_status_after, total_dist_after, route_after, day_max_speed_after, day_max_speed_time_after, last_halt_time_after,io_1_after,io_2_after,io_3_after,io_4_after,io_5_after,io_6_after,io_7_after,io_8_after);
		
		/*point_after = point;
		vIcon_after = vIcon;
		marker1_after = marker1[mcounter];
		imei_after = imei;
		vehiclename_after = vehiclename;
		speed_after = speed;
		datetime_after = datetime;
		fuel_after = fuel;
		running_status_after = running_status;
		total_dist_after = total_dist;
		route_after = route;
		day_max_speed_after = day_max_speed;
		day_max_speed_time_after = day_max_speed_time;
		last_halt_time_after = last_halt_time;
		io_1_after = io_1;
		io_2_after = io_2;
		io_3_after = io_3;
		io_4_after = io_4;
		io_5_after = io_5;
		io_6_after = io_6;
		io_7_after = io_7;
		io_8_after = io_8;*/	
		//#############################		
		//alert("Final");
						
	} //ELSE DATA RECIEVED       		
}

var mcount = 0;
var marker_customer = new Array();
//######### CUSTOMER MARKERS
function CreateCustomerMarker(lat,lng,station_no,type,route_no) 
{	
	var point = new GLatLng(parseFloat(lat),parseFloat(lng));
	
	//alert("Debug1="+point);
	var Icon;
	Icon= new GIcon(customerIcon);
			
	var marker;	
	marker = new GMarker(point, Icon);
	marker_customer[mcount] = marker;
	mcount++;
	var action_marker;
	//action_marker = "click";
		
	/*var Icon2= new GIcon(customerIcon);
	//Icon2.image = './images/customer_images/green_Marker1.png';
	//Icon2.iconSize = new GSize(14, 22);
	//Icon2.iconAnchor = new GPoint(6, 20);
	//Icon2.infoWindowAnchor = new GPoint(5, 1);
	action_marker = new GMarker(point, Icon2);*/
	//alert("Icon="+Icon+" ,point="+point+" ,marker="+marker);
	GEvent.addListener(marker, "click", function()
	{
		PlotCustomerMarker(point,Icon, marker, point,station_no,type,route_no);
		//map.addOverlay(action_marker);

	});	

	/*GEvent.addListener(action_marker, 'mouseout', function() {				
		//alert("action_marker in mouseout"+action_marker);		
		map.removeOverlay(action_marker);
	});	*/
	//alert("marker="+marker);
	//alert("Debug2");
	return marker;		
}

function PlotCustomerMarker(point,Icon, marker, point,station_no,type,route_no)
{
	//alert("IN PlotCustomerMarker");
	var window_style1="style='color:#000000;font-family: arial, helvetica, sans-serif; font-size:11px;text-decoration:none;font-weight:bold;'";
	var window_style2="style='color:blue;font-family: arial, helvetica, sans-serif; font-size:11px;text-decoration:none;'";
	var window_height=125;
	
	if(type=="0") 
	type = "Customer";
	var data_str = "<tr><td><font size=2 color=#000000><strong>Station No</strong></font></td><td>&nbsp;<strong>:</strong>&nbsp;</td><td><font color=blue size=2><strong>"+station_no +"</strong></font></td><td></tr><tr><td><font size=2 color=#000000><strong>Station Type</strong></font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2><strong>"+type +"</strong></font></td><td></tr><tr><td><font size=2 color=#000000><strong>Route No</strong></font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2><strong>"+route_no +"</strong></font></td><td></tr><tr><td><font size=2 color=#000000><strong>Point</strong></font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2><strong>"+point +"</strong></font></td><td></tr>";
		
	var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" style="height:100px;" align=left><table cellpadding=0 cellspacing=0>'+data_str+'</table></div>');
	var tab2 = new GInfoWindowTab("Location", '<div id="detailmap" style="height:160px;"></div>');

	//alert("tab1="+tab1);
	var infoTabs = [tab1];
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
