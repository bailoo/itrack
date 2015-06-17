function resize(obj)
{ 
	initialize();
  if(obj=='home')
  { 
    var dv = document.getElementById("leftMenu");    
    divHeight =  $(window).height();
    dv.style.height = divHeight - 103;
  }
  else  
  { 
    var dv1 = document.getElementById("leftMenu_1");    
    divHeight =  $(window).height();
    dv1.style.height = divHeight - 25;
    
    var dv2 = document.getElementById("leftMenu_2");    
    divHeight =  $(window).height();
    dv2.style.height = divHeight - 103; 
  }         
}
  
function initialize()
{
  //document.getElementById("text").style.display="none";
  document.getElementById("map").style.display=true;
  if(GBrowserIsCompatible())
  {
		map = new GMap2(document.getElementById("map"));	
		map.setCenter(new GLatLng(22.755920681486405, 78.2666015625), 5);
		map.enableContinuousZoom();
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
			
			/*
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
					var myHtml = "<font size='2' color='#000000'>The GPoint value is: " + map.fromLatLngToDivPixel(point)+"<br>"+point + "<br>"+ "<center>at zoom level " +map.getZoom()+"</font></center>";
					//alert("map="+map+" point="+point);
					map.openInfoWindow(point, myHtml);
				}

			}); //GEvent.addListener closed */
			
			//////// GET LATLNG  ////////////////////////////////
			/*GEvent.addListener(map,"click", function(overlay,point) {   			
														
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

			}); //GEvent.addListener closed 	*/		
			///////////////////////////////////////////////////////
		
			//////////////////////////// CUSTOM DRAG ZOOM CODE /////////////////////////////////
			var boxStyleOpts1 = {
					 opacity: .2,
					  border: "2px solid red"
					}

			var otherOpts1 = {
					  buttonHTML: "<img src='../dragzoom/zoom-button.gif' />",
					  buttonZoomingHTML: "<img src='../dragzoom/zoom-button-activated.gif' />",
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
			//var search=map.addControl(new google.maps.LocalSearch(), new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(5,50)));
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
	} //GBrowserIs compatible closed			
}  // function closed		
  
function show_report()
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
}


function show_user(user_account)
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

	/////////////////////////////////////////////////////////////////////////////

	function addOption(selectbox,text,value)
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
	}


	function show_main_home_vehicle()
	{
		var display_type="default";
		var poststr = "display_type1=" + encodeURI(display_type);    
		makePOSTRequest('src/php/module_main_home_vehicle_chk.php', poststr);
	}

	function show_vehicle_display_option()
	{
		var vehicle_details_option1=document.thisform.vehicle_details_option;
		document.getElementById("all_vehicle_1").style.display="none"; 		
		for(var i=0;i<vehicle_details_option1.length;i++)
		{			
			vehicle_details_option1[i].checked=false;
		}	
		document.getElementById("selection_information").style.display="none";
		document.getElementById("blackout_1").style.visibility = "visible";
		document.getElementById("divpopup_1").style.visibility = "visible";
		document.getElementById("blackout_1").style.display = "block";
		document.getElementById("divpopup_1").style.display = "block"; 
	}

	function display_vehicle_according_divoption(obj)
	{ 
		if(document.getElementById("all_vehicle_type").value=="all_vehicle")
		{
			close_vehicle_display_option();
			var display_type="default";
			var poststr = "display_type1=" + encodeURI(display_type);
		}
		else
		{
			var common_div_option1=document.getElementById('common_div_option').value;
			var div_option_values=tree_validation();
			if(div_option_values!=false)
			{
			close_vehicle_display_option();
			var common_div_option1=document.getElementById('common_div_option').value;
			var poststr = "common_div_option1=" + encodeURI(common_div_option1) +
			"&div_option_values1=" + encodeURI(div_option_values);	
			}
		}	
		//alert("poststr="+poststr);
		makePOSTRequest('src/php/module_main_home_vehicle_chk.php', poststr);
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
	


	/*function open_win(value)
	{
	alert("val="+value);
	window.open("src/php/NextPage.php",value,'width=300,height=200');
	}*/	