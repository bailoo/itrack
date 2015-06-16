<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	
	//echo "AccountID=".$account_id;
	$Query = "SELECT landmark_name,landmark_coord FROM landmark WHERE account_id='$account_id' AND status=1";
	$result = mysql_query($Query,$DbConnection);
	while($row=mysql_fetch_object($result))
	{
		//echo "<br>L1=".$row->landmark_coord;
		$landmark_coord_tmp = preg_replace( '/\s+/', '', $row->landmark_coord);
		//echo "<br>L2=".$landmark_coord_tmp;
		$landmark_name[] = $row->landmark_name;
		$landmark_coord[] = $landmark_coord_tmp;
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>VTS-Shortest Route Finder</title>
    <style>
		html, body, #map-canvas {
			height: 100%;
			margin: 0px;
			padding: 0px
		}
		#panel1 {
			position: absolute;
			top: 5px;
			left: 35%;
			margin-left: -180px;
			z-index: 5;
			background-color: #fff;
			padding: 5px;
			border: 1px solid #999;
		}
		#panel2 {
			position: absolute;
			top: 40px;
			left: 35%;
			margin-left: -180px;
			z-index: 5;
			background-color: #fff;
			padding: 5px;
			border: 1px solid #999;
		}

		input.start_text {
			color: #d3d3d3;
			font-style: italic;
			}
			input.ready {
			color: black;
			font-style: normal;
		}		
		input.end_text {
			color: #d3d3d3;
			font-style: italic;
			}
			input.ready {
			color: black;
			font-style: normal;
		}		

    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>

var rendererOptions = {
  draggable: true
};
var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
var directionsService = new google.maps.DirectionsService();
var map;

var india = new google.maps.LatLng(21.0000, 78.0000);
   
var latLngmarkers = [];
//var latLngmarkers=new Array();
var listenerHandle='';

function initialize() {

	var mapOptions = {
	zoom: 5,
	center: india
	};
	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	directionsDisplay.setMap(map);
	directionsDisplay.setPanel(document.getElementById('directionsPanel'));

	google.maps.event.addListener(directionsDisplay, 'directions_changed', function() {
	computeTotalDistance(directionsDisplay.getDirections());
	});

	//calcRoute();  
	//########## GET COORDINATE ON CLICK
	//deleteLatLngOverlays();
	listenerHandle = google.maps.event.addListener(map, 'click', function(event)
	{
		//deleteLatLngOverlays();
		//alert("InfoWindow="+infowindow)
		//if (infowindow.open()) infowindow.close();
		var oCheckbox = document.getElementById('pick_latlng');
		//alert("oCheckbox="+oCheckbox);
		
		if (oCheckbox.checked == true)
		{			
			infowindow = new google.maps.InfoWindow();

			//var localmarker = new google.maps.Marker({position: event.latLng, map: map, icon: {path: google.maps.SymbolPath.CIRCLE,}});
			var localmarker = new google.maps.Marker({position: event.latLng, map: map, icon: {}});								
			// alert("k2="+event.latLng);                     
			var contentString='<table>'+
				'<tr>'+
				'<td class=live_td_css1>Lat/Long</td>'+
				'<td>:</td>'+
				'<td class=live_td_css2>'+event.latLng+'</td>'+
				'</tr>'+
				'</table>';
				latLngmarkers.push(localmarker);
			infowindow.setContent(contentString);
			infowindow.open(map, localmarker);
		}
	});				
}

// Sets the map on all markers in the array.
function clearMarkers() {
  
  //alert("len2="+latLngmarkers.length);
  for (var i = 0; i < latLngmarkers.length; i++) {
    //alert("len2="+latLngmarkers.length+" ,i="+i+" ,latLngmarkers[i]="+latLngmarkers[i]);
	latLngmarkers[i].setMap(null);
  }
}

// Deletes all markers in the array by removing references to them.
function deleteLatLngOverlays() {
//alert("len1="+latLngmarkers.length);
  clearMarkers();  
  /*deleteOverlays();
  
	function deleteOverlays()
	{
		for (var i = 0; i < latLngmarkers.length; i++)
		{
			latLngmarkers[i].setMap(null);
		}
	}*/
  
  //latLngmarkers = [];
  //alert("Before");
  latLngmarkers.length = null;
  //alert("After");
}

/*function deleteLatLngOverlays()
{
	alert("landmark_len="+latLngmarkers.length);
	for (var i = 0; i < latLngmarkers.length; i++)
	{
			//latLngmarkers[i].setMap(null);
			setAllMap(null);
			//latLngmarkers[i].overlayMapTypes.setAt( 0, null);
	}
}*/

function calcRoute(origin_val, dest_val) {
//alert("OriginVal="+origin_val+" ,dest_val="+dest_val);
  var request = {
    //origin: '26.448404713878634,80.33683776855469',
    //destination: '26.85807385117589, 80.94314575195312',
	origin: origin_val,
    destination: dest_val, 
	//travelMode: google.maps.TravelMode.DRIVING
	travelMode: google.maps.TravelMode.WALKING
	//travelMode: google.maps.TravelMode.BICYCLING
	//travelMode: google.maps.TravelMode.TRANSIT
  };
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
    }
  });
}

function computeTotalDistance(result) {
  var total = 0;
  var myroute = result.routes[0];
  for (var i = 0; i < myroute.legs.length; i++) {
    total += myroute.legs[i].distance.value;
  }
  total = total / 1000.0;
  document.getElementById('total').innerHTML = total + ' km';
}

google.maps.event.addDomListener(window, 'load', initialize);


function runScriptEnter_Route(e) 
{
	//alert("KeyCode="+e.keyCode);
	//if (typeof e == 'undefined' && window.event) { e = window.event; }
	if (e.keyCode == 13) 
	{
		var start = document.getElementById('start_txt').value;
		var end = document.getElementById('end_txt').value;	
		calcRoute(start,end);
	}
}

function runScriptSelect_Route() 
{
	var start = document.getElementById('start').value;
	var end = document.getElementById('end').value;	
	calcRoute(start,end);
}

function select_latlng()
{
    var oCheckbox = document.getElementById('pick_latlng');
	//alert("oCheckbox="+oCheckbox);
	
    if (oCheckbox.checked == true)
    {
		alert("True");
        //alert("Checkbox with name = " + oCheckbox.name + " and value =" +checkbox_val + " is checked");
    }
    else
    {
		alert("False");
        //alert("Checkbox with name = " + oCheckbox.name + " and value =" +checkbox_val + " is not checked");
    }
}

function toggle(which,text) 
{
	if(which.className == text)
	{
		which.value = "";
		which.className = "ready";
		return false;
	}
	if (which.className == "ready" && which.value == "") 
	{
		which.value = "Hit Enter After Text";
		which.className = text;
	}
}
	
    </script>
  </head>
  <body>
  
    <div id="panel1">
    	
	<b>Start: </b>
    <select id="start" onchange="runScriptSelect_Route();">
      <!--<option value="26.448404713878634,80.33683776855469">Kanpur</option>
      <option value="st louis, mo">St Louis</option>-->
	  <?php
	  for($i=0;$i<sizeof($landmark_name);$i++)
	  {
		echo "<option value=".$landmark_coord[$i].">".$landmark_name[$i]."</option>";
	  }
	  ?>
    </select>
    <b>End: </b>
    <select id="end" onchange="runScriptSelect_Route();">
      <!--<option value="26.85807385117589, 80.94314575195312">Lucknow</option>
      <option value="st louis, mo">St Louis</option>-->
	  <?php
	  for($i=0;$i<sizeof($landmark_name);$i++)
	  {
		echo "<option value=".$landmark_coord[$i].">".$landmark_name[$i]."</option>";
	  }
	  ?>
    </select>
    </div>

	<div id="panel2">
    <b>Start: </b>
    <input type="text" id="start_txt"  class="start_text" onkeypress="return runScriptEnter_Route(event);" onfocus="toggle(this,'start_text')"; onblur = "toggle(this,'start_text')" value="Hit Enter After Text">
      
    <b>End: </b>
    <input type="text" id="end_txt" class="end_text" onkeypress="return runScriptEnter_Route(event);" onfocus="toggle(this,'end_text')"; onblur = "toggle(this,'end_text')" value="Hit Enter After Text">
     
    </div>  
    <!--<div id="map-canvas" style="float:left;width:70%; height:100%;"></div>-->
	<div id="map-canvas" style="float:left;width:70%; height:100%;"></div>	
    <!--<div id="directionsPanel" style="float:right;width:30%;height 100%;overflow:auto;">-->
	<div id="directionsPanel" style="float:right;width:30%;height:100%;overflow:scroll;">
    <font color='blue'><strong>VTS-ROUTE FINDER (Optimum)</strong></font>
	<p><b>Total Distance: </b><span id="total"></span>&nbsp;&nbsp;<input type="checkbox" id="pick_latlng"><b>GetLanLng</b>
	&nbsp;&nbsp;<input type="button" id="clear" value="Clear" onclick="javascript:deleteLatLngOverlays();">
	</p>
	
    </div>
  </body>
</html>