<?php
	include_once('SessionVariable.php');
	include_once("PhpMysqlConnectivity.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link href="styles.css" rel="stylesheet" type="text/css">

	<title>
		Welcome user- <?php echo $UserName;?>
	</title>
	<style type="text/css">
		.ft{
			  color:#FFFFFF;
			  font-family:font-family: Arial, Helvetica, sans-serif; ;
			  font-size:11px;
			  font-style:normal;
			}
		.ft1{
			  color:#000000;
			  font-family:font-family: Arial, Helvetica, sans-serif; ;
			  font-size:14px;
			  font-style:normal;
			}
		.ft2{
			  color:#000000;
			  font-family:font-family: Arial, Helvetica, sans-serif; ;
			  font-size:11px;
			  font-style:normal;
			}
		.style6 {
		         color: #2B9CFF; font-family: Vfont-family: Arial, Helvetica, sans-serif;; font-size: 9px; font-style: normal;
				 }
          body {
	            background-repeat: no-repeat;
               }
       .style12 {
	            color: #000000; font-family: font-family: Arial, Helvetica, sans-serif;; font-size: 9px; font-style: normal; 
	           }			
	</style>
   
	<link rev="made" href="mailto:contact@rainforestnet.com"> 
	<link rel="stylesheet" href="rfnet.css">
	<link rel="stylesheet" type="text/css" href="gm_minimap_in_infowindow_files/mapStyle.css">	

	<script type="text/javascript" language="javascript" src="datetimepicker_sd.js"></script>
	<script type="text/javascript" language="javascript" src="datetimepicker.js"></script>

	<script type="text/javascript">
		if (document.images)
		{
			calimg= new Image(16,16); 
			calimg.src="images/cal.gif"; 
		}
	</script>

	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
	<link rel="shortcut icon" href="images/iesicon.ico">
	<style type="text/css" media="screen">
		.gmnoscreen
		{
			display:none
		}
	</style>
	<style type="text/css" media="print">
		.gmnoprint
		{
			display:none
		}
	</style>

	<style type="text/css" media="screen">
		.gmnoscreen
		{
			display:none
		}
	</style>
	
	<style type="text/css" media="print">
		.gmnoprint
		{
			display:none
		}
	</style>
      
	<style type="text/css">
	  @import url("http://www.google.com/uds/css/gsearch.css");
	  @import url("http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css");
	  }
	</style>

	
  <?php
    include_once("main_google_key.php");
  ?>	
	

	<script src="route_map/BDCCPolyline.js" type="text/javascript"></script>
	<script src="route_map/BDCCArrow.js" type="text/javascript"></script>
	<script src="dragzoom/gzoom.js" type="text/javascript"></script>
	<script type="text/javascript" src="pdmarker.js"></script>
	<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0" type="text/javascript"></script>
	<script src="http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js" type="text/javascript"></script> 


	<script type="text/javascript" language="javascript">
		
		var currentDate = new Date;
		
		var lvIcon1A = new GIcon(); 
		lvIcon1A.image = 'light_v1.png';
		lvIcon1A.iconSize = new GSize(28, 25);
		lvIcon1A.iconAnchor = new GPoint(6, 20);
		lvIcon1A.infoWindowAnchor = new GPoint(10, 25);

		var lvIcon2A= new GIcon();
		lvIcon2A.image = 'light_v2.png';
		lvIcon2A.iconSize= new GSize(28, 25);
		lvIcon2A.iconAnchor= new GPoint(9, 34);
		lvIcon2A.infoWindowAnchor= new GPoint(5, 1);

		var lvIcon3A= new GIcon();
		lvIcon3A.image = 'light_v3.png';
		lvIcon3A.iconSize= new GSize(28, 25);
		lvIcon3A.iconAnchor= new GPoint(9, 34);
		lvIcon3A.infoWindowAnchor= new GPoint(5, 1);

		var hvIcon1A= new GIcon();
		hvIcon1A.image = 'heavy_v1.png';
		hvIcon1A.iconSize= new GSize(28, 25);
		//baseIcon.shadowSize= newGSize(37, 34);
		hvIcon1A.iconAnchor= new GPoint(8, 20);
		hvIcon1A.infoWindowAnchor= new GPoint(0, 0);


		var hvIcon2A= new GIcon();
		hvIcon2A.image = 'heavy_v2.png';
		hvIcon2A.iconSize= new GSize(28, 25);
		//baseIcon.shadowSize= newGSize(37, 34);
		hvIcon2A.iconAnchor= new GPoint(9, 34);
		hvIcon2A.infoWindowAnchor= new GPoint(5, 1);

		var hvIcon3A= new GIcon();
		hvIcon3A.image = 'heavy_v3.png';
		hvIcon3A.iconSize= new GSize(28, 25);
		//baseIcon.shadowSize= newGSize(37, 34);
		hvIcon3A.iconAnchor= new GPoint(9, 34);
		hvIcon3A.infoWindowAnchor= new GPoint(5, 1);
		
		//////////////////////////////////////////////////
		var lvIcon1 = new GIcon(); 
		lvIcon1.image = 'moving_car.png';
		lvIcon1.iconSize = new GSize(12, 22);
		lvIcon1.iconAnchor = new GPoint(6, 20);
		lvIcon1.infoWindowAnchor = new GPoint(10, 25);

		var lvIcon2= new GIcon();
		lvIcon2.image = 'moving_car.png';
		lvIcon2.iconSize= new GSize(12, 22);
		lvIcon2.iconAnchor= new GPoint(9, 34);
		lvIcon2.infoWindowAnchor= new GPoint(5, 1);

		var lvIcon3= new GIcon();
		lvIcon3.image = 'moving_car.png';
		lvIcon3.iconSize= new GSize(12, 22);
		lvIcon3.iconAnchor= new GPoint(9, 34);
		lvIcon3.infoWindowAnchor= new GPoint(5, 1);

		var hvIcon1= new GIcon();
		hvIcon1.image = 'moving_truck.png';
		hvIcon1.iconSize= new GSize(14, 17);
		//baseIcon.shadowSize= newGSize(37, 34);
		hvIcon1.iconAnchor= new GPoint(8, 20);
		hvIcon1.infoWindowAnchor= new GPoint(0, 0);


		var hvIcon2= new GIcon();
		hvIcon2.image = 'moving_truck.png';
		hvIcon2.iconSize= new GSize(14, 17);
		//baseIcon.shadowSize= newGSize(37, 34);
		hvIcon2.iconAnchor= new GPoint(9, 34);
		hvIcon2.infoWindowAnchor= new GPoint(5, 1);

		var hvIcon3= new GIcon();
		hvIcon3.image = 'moving_truck.png';
		hvIcon3.iconSize= new GSize(14, 17);
		//baseIcon.shadowSize= newGSize(37, 34);
		hvIcon3.iconAnchor= new GPoint(9, 34);
		hvIcon3.infoWindowAnchor= new GPoint(5, 1);

		var add;
		var map;
		var geocoder = null;
		var address = null;

		var rg;
		var point;
		var lm;
		lm=0;	

	function initialize() 
	{
		if(GBrowserIsCompatible())
		{	
			//alert("Initialising");
			//map.clearOverlays();
			map = new GMap2(document.getElementById("map"));
			map.removeMapType(G_SATELLITE_MAP);
			map.setCenter(new GLatLng(23.725011735951796, 77.7392578125), 5);
			map.enableContinuousZoom();
	
			GEvent.addListener(map,"click", function(overlay,point)
			{
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
					var myHtml = "<font size='2'>The GPoint value is: " + map.fromLatLngToDivPixel(point)+"<br>"+point + "<br>"+ "<center>at zoom level " + map.getZoom()+"</font></center>";
					map.openInfoWindow(point, myHtml);
				}
			}); //GEvent.addListener closed 	
				
			map.addControl(new GLargeMapControl());
			var mapTypeControl = new GMapTypeControl();
			var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10,10));	
			
			///////////
			map.addMapType(G_SATELLITE_MAP);
			var mapControl = new GMapTypeControl();
			map.addControl(mapControl, topRight);
			//////////

			map.addControl(new GScaleControl()) ; 
			      
      var search=map.addControl(new google.maps.LocalSearch(), new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(10,20)));
			var bottomLeft = new GControlPosition(G_ANCHOR_BOTTOM_LEFT, new GSize(10,10));

			GEvent.addListener(map, 'zoomend',function (oldzoomlevel,newzoomlevel) 
			{
				var bounds = map.getBounds();
			});

			
  		var boxStyleOpts = {
  		  opacity: .2,
  		  border: "2px solid red"
  		}
  		var otherOpts = {
  		  buttonHTML: "<img src='dragzoom/zoom-button.gif' />",
  		  buttonZoomingHTML: "<img src='dragzoom/zoom-button-activated.gif' />",
  		  buttonStartingStyle: {width: '24px', height: '24px'}
  		};
  		var callbacks = {
  		  buttonclick: function(){GLog.write("Looks like you activated DragZoom!")},
  		  dragstart: function(){GLog.write("Started to Drag . . .")},
  		  dragging: function(x1,y1,x2,y2){GLog.write("Dragging, currently x="+x2+",y="+y2)},
  		  dragend: function(nw,ne,se,sw,nwpx,nepx,sepx,swpx){GLog.write("Zoom! NE="+ne+";SW="+sw)}
  		};
  		map.addControl(new DragZoomControl(boxStyleOpts, otherOpts), new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(65,65)));      
             
      if(navigator.userAgent.indexOf("Firefox")==-1)
			{				
				GSearch.setOnLoadCallback(initialize);
			}												  
		} //GBrowserIs compatible closed
	}  // function closed

	function doBlink()
	{
		var blink = document.all.tags("BLINK")
		for (var i=0; i<blink.length; i++)
		blink[i].style.visibility = blink[i].style.visibility == "" ? "hidden" : "" 
	}

	function startBlink()
	{
		if (document.all)
		setInterval("doBlink()",500)
	}
		window.onload = startBlink;
	</script>

	<script src="live_vehicle.js" type="text/javascript"></script>
	<script src="live_lastpos.js" type="text/javascript"></script>
	<!--<script src="test_moving_vehicles.js" type="text/javascript"></script>-->
	<script type="text/javascript" src="resources/map.js"></script>
	<script type="text/javascript">
  /*
	var ad=0;
	var place;
	var address1=0;

	function PlotMarkerWithAddress(point, Icon, marker, vehiclename, speed, datetime, dist,fuel_litres, fuel_level)
	{
		 var accuracy;
		 var largest_accuracy;	   
		 var delay = 100;	 
		 var geocoder = new GClientGeocoder();
		 geocoder.getLocations(point, function (result)
		 {
			 if (result.Status.code == G_GEO_SUCCESS) // OR !=200
			 {
			   var j;
			   for (var i=0; i<result.Placemark.length; i++)
			   {
					accuracy = result.Placemark[i].AddressDetails.Accuracy;			           
					if(i==0)
					{
						largest_accuracy = accuracy; 
						j = i;
					}
					else 
					{
						if(largest_accuracy < accuracy)
						{
							largest_accuracy = accuracy;					
							j = i;					
						}
					}			
			   }
			var address = result.Placemark[j];
			address1 = address.address;	   
			var google_point = new GLatLng(address.Point.coordinates[1],address.Point.coordinates[0]);
			var distance = calculate_distance(point.y, google_point.y, point.x, google_point.x); 	
			var address2 = distance+" km from "+address1;
			var place = address2;
			var tab1 = new GInfoWindowTab("Info", '<div id="tab1" class="bubble" align=left><table cellpadding=0 cellspacing=0><tr><td><font size=2 color=#000000>Vehicle</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+vehiclename + '</font></td><td></td></tr><tr><td><font size=2 color=#000000>Speed</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+speed+' kmph</font></td></tr><tr><td><font size=2 color=#000000>Date & Time</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+datetime+'</font></td></tr> <tr><td><font size=2 color=#000000>Place</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+place+'</font></td></tr><tr><td><font size=2 color=#000000>Distance travelled</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+dist+' km</font></td></tr><tr><td><font size=2 color=#000000>Fuel</font></td><td>&nbsp;:&nbsp;</td><td><font color=blue size=2>'+fuel_litres+' litres ('+fuel_level+' %)</td></tr><tr><td colspan=3><font color=blue size=2>( '+point.y+', '+point.x+' )</font></td></tr></table></div>');	
			var tab2 = new GInfoWindowTab("Location", '<div id="detailmap"></div>');
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

			var topLeft = new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(5,35));
			var mapControl2 = new GSmallMapControl();		
			GEvent.addListener(detailMap, "zoomend", miniMapZoomEnd);
			GEvent.addListener(detailMap, "moveend", miniMapMoveEnd);
			detailMap.addControl(mapControl2, topLeft);

			var CopyrightDiv = dMapDiv.firstChild.nextSibling;
			var CopyrightImg = dMapDiv.firstChild.nextSibling.nextSibling;
			CopyrightDiv.style.display = "none"; 
			CopyrightImg.style.display = "none";
			var marker3 = new GMarker(point,Icon);		
			detailMap.addOverlay(marker3);
			showMinimapRect(detailMap,marker3);
		 }
	  });
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
	}  */
	</script> 

	<script language="javascript" type="text/javascript">
		function load_vehicles()
		{ 
		   getScriptPage();		  
		}	
		function handleHttpResponse() 
		{
			if (http.readyState == 4) 
			{		   
        document.getElementById('loading_msg').style.display='none';	 
        document.getElementById('pop_content1').innerHTML = http.responseText;	
			}
		}
		
		function getHTTPObject()
		{
			var xmlhttp;
			/*@cc_on
			@if (@_jscript_version >= 5)
				try {
					xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
					try {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (E) {
						xmlhttp = false;
					}
				}
			@else
			xmlhttp = false;
			@end @*/
			if (!xmlhttp && typeof XMLHttpRequest != 'undefined') 
			{
				try
				{
					xmlhttp = new XMLHttpRequest();
				} 
				catch (e)
				{
					xmlhttp = false;
				}
			}
		return xmlhttp;
		}
		var http = getHTTPObject(); // We create the HTTP Object

		function getScriptPage()
		{
			http.open("GET", "live_show_vehicle.php", true);
			http.onreadystatechange = handleHttpResponse;		
			http.send(null);
		}
	</script>
</head>

<body style="background-color:#ffffff;" onload="javascript:load_vehicles();" topmargin="0"> 

	<FORM method="GET" name="form1">   
		<?php
			echo"
				<input TYPE=\"hidden\" VALUE=\"$UserID\" NAME=\"UserID\">
				<input TYPE=\"hidden\" VALUE=\"$UserName\" NAME=\"UserName\">					
			";
			echo"<input type='hidden' name='login_1' id='login_1' value='".$login."'>";
			echo'<input type="hidden" name="access" value="'.$access.'">';
		?>	
		<input type="hidden" name="lat">
		<input type="hidden" name="lng">
		<input type="hidden" name="vid2">
		<input type="hidden" name="last_marker">
		<input type="hidden" name="pt_for_zoom">
		<input type="hidden" name="zoom_level">
		<input type="hidden" name="current_vehicle">
		<input type="hidden" name="cvflag">
		<input type="hidden" name="mapcontrol_startvar">
		<input type="hidden" name="StartDate">
		<input type="hidden" name="EndDate">
		<input type="hidden" name="vehicleSerial">
		<input type="hidden" name="StartDate1">
		<input type="hidden" name="EndDate1">
		<?php echo'<input type="hidden" name="access" value="'.$access.'">'; ?>

		

	<table  border="0" CELLSPACING="0" CELLPADDING="0" style="background:border-spacing:none;border-collapse:collapse;" width="100%">
		<tr>
			<td>
				<table width="99%" border="0" bgcolor="GhostWhite" align="center">
					<tr>
						<?php
							if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == false))
						echo'<td><font color="#0042FF"><strong>USER</font>&nbsp;:&nbsp;<font color="#FF972D"><strong>'.$login.'</strong></font></td><td width="70%" align="right">';

							else if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == true))
							echo'<td><font color="#0042FF"><strong>USER</font>&nbsp;:&nbsp;<font color="#FF972D"><strong>'.$login.'</strong></font></td><td width="70%" align="right">';

						echo'<table><tr>';

						echo'<td>';
							if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == true))
							{
								//echo "IN IE";
								echo'<p id="prepage" style="position:absolute; font-family:arial; font-size:16;left:20%; top:10px; visibility:hidden"><font color=red>Loading....</font></p>';	
							}
							echo'</td>';

							echo'<td><font style="font-size:12px;color:#0042FF;" face="arial">Status : &nbsp; </font><input type="text" name="status" style="width:210px;height:13px;font-size:9px;color:#FF0000"/></td>';
							
							echo'<td><a href="MovingVehicles.php"><font style="font-size:12px;color:#0042FF;" face="arial">Refresh Page</font></a></td>';

							echo'<td><input type="checkbox" value="1" name="latlng"/><font style="font-size:12px;color:#0042FF;" face="arial">Show Latlng</font></td>';
							echo'
								<td><font color="#000000" style="font-size:12px;color:grey;" face="arial">Refresh data every:-</font>
								<select name="autoref_combo" onchange="auto_refresh()">
									 <option name="box0" value="0">disable</option>
									 <option name="box0" value="0"></option>
									 <option name="box5s" value="5">5 sec</option>
									 <option name="box10s" value="10">10 sec</option>
									 <option name="box20s" value="20">20 sec</option>
									 <option name="box30s" value="30" selected>30 sec</option>
									 <option name="box1m" value="60">1 min</option>
									 <option name="box2m" value="120">2 min</option>
									 <option name="box3m" value="180">3 min</option>
									 <option name="box4m" value="240">4 min</option>
									 <option name="box5m" value="300">5 min</option>
									 <option name="box10m" value="360">10 min</option>
								</select></td>	
						</tr></table>	
							';							
						?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr>
			<td>
				<TABLE BORDER="1" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#E2EFF5" bordercolor="grey" rules="all" width="99%" align="center">
					 <tr style="background: url(images/bg_2.gif); width=100%; height=100%">
						<td colspan="5" width="1024">	
								
						<?php
							
							$width = 1140;
              if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == false))
							{							
								$height = 540;
								echo'<div id="map" style="width:'.$width.'px; height:'.$height.'px; position: relative;" class="ukseries_div_map">';
							}

							else if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == true))
							{
								$width = $width-224;
								$height = $height;
								echo'<div id="map" style="width:'.$width.'px; height:'.$height.'px; position: relative;" class="ukseries_div_map">';								
							}							

							echo '
								<script type="text/javascript" language="JavaScript">								
									initialize();
								</script>
							';	
							
							if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == false))
							{
								//echo "IN MOZILLA";
								echo'<p id="prepage" style="position:absolute; font-family:arial; font-size:16; left:40%; top:220px; layer-background-color:#e5e3df; height:10%; width:20%; visibility:hidden"><img src="load_data.gif">	
								</p>';
							} 
				?>			
				
							</div>
						</td>
						<td valign="top" BGCOLOR="#FFFDF9">
							<div id="loading_msg"><br><blink><font color="green">Loading vehicles plz wait..</font></blink></div>
              <div id='pop_content1' style="width:200px; height:500px;overflow:auto;"></div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="99%" border="0" cellpadding="0" cellspacing="0" bgcolor="GhostWhite" align="center">
					<tr>
						<td>
							 <div style="height:3px;"></div>
						</td>
					</tr>
					<tr>
						<!--<td width="12%" height="23" align="center">
							<img src="images/green_Marker_bottom.png" alt="2">
								<span class="style1">
									<font style="size:8px;" color="#0042FF">Speed >20 kmph</font>
								</span>
						</td>
						<td width="16%">
							<img src="images/yellow_Marker_bottom.png" alt="4">
								<span class="style1">
									<font style="size:8px;" color="#0042FF">Speed between 1 and 20 kmph</font>
								</span>
						</td>
						<td width="10%">
							<img src="images/red_Marker_bottom.png" alt="3">
								<span class="style1">
									<font style="size:8px;" color="#0042FF">Speed <1 kmph</font>
								</span>
						</td>
						<td width="13%">
							<img src="images/blink_Marker.gif" alt="1" width="10" height="16">
								<span class="style1">
									<font style="size:8px;" color="#0042FF"> Vehicle current position</font>
								</span>
						</td>-->
						<td width="34%">
							<font style="size:8px;" color="#FF972D">&copy; Copyright Innovative Embedded Systems Pvt.Ltd. All Rights Reserved </font>		
						</td>				
					  </tr>
					  <tr>
						<td>
							<div style="height:3px;"></div>
						</td>
					  </tr>
				</table>
			</td>
		</tr>
	</table>
<script type="text/javascript">

var last_marker;

reset();
//alert("K");
function reset()
{
	//alert("K");
	//alert("document="+document.form1);
	document.forms[0].last_marker.value = "";
}


////// call autorefresh function //////
//auto_refresh();
///////////////////////////////////////

//movingVehicle();

var min2;
var end_date;

var currentDate1 = new Date;

var min = currentDate1.getMinutes();

min2 = min + 1;
var timer1;

//movingVehicle();	      //////////////////////

function set_ref_values()
{
	movingVehicle();	
}

function auto_refresh()
{	
	//alert("in autorefresh");
	var value = document.form1.autoref_combo.value;
	document.form1.cvflag.value=1;
		
	var interval;
	//interval=1;
	
  interval = value*1000;
	
	//alert("value="+value+" interval="+interval);
	if(interval>1)
	{	
		clearTimeout(timer1);
		timer1=setTimeout('set_ref_values()',interval);
	}
}
</script>	
	

</body>

</html>

