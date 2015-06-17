<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
	include_once('util_session_variable.php');		
	include_once('util_php_mysql_connectivity.php');
	echo"<input type='hidden' id='source_location_name' value='".$source_location_name."'>";
	echo"<input type='hidden' id='destination_location_name' value='".$destination_location_name."'>";
	//echo "account_id_local=".$account_id_local."startdate=".$start_date;
	//echo "manage_id=".$manage_id."<br>";
?>

<html xmlns="http://www.w3.org/1999/xhtml"  xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title>
		<?php echo $title; ?>
	</title>
	<style>
	.headings
	{
		font-size: 9pt;	
		font-weight: bold;
		color:blue;
		text-align:center;
	}
	.main_tr
	{	
		font-size: 9pt;	
		font-weight: bold;
		text-align:left;
		background-color:#FFEBA5;
	}
	</style>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Maps JavaScript API Example: Directions + Static Map</title>
    <script src=" http://maps.google.com/?file=api&amp;v=2&amp;key=AIzaSyA9SrKxfDId98hLt4eqlV0CjtvC0X7O4u4"
      type="text/javascript"></script>
    <style type="text/css">
      body {
        font-family: Verdana, Arial, sans serif;
        font-size: 11px;
        margin: 2px;
      }
      table.directions th {
        background-color:#EEEEEE;
      }
      img {
        color: #000000;
      }
    </style>
    <script type="text/javascript">
 
    var map;
    var gdir;
    var geocoder = null;
    var addressMarker;

    function initialize() {
      if (GBrowserIsCompatible()) {      
        map = new GMap2(document.getElementById("map_route"));
        //map.setCenter(new GLatLng(22.755920681486405, 78.266601562), 5);
        var customUI = map.getDefaultUI();
        // Remove MapType.G_HYBRID_MAP
        customUI.maptypes.hybrid = false;
        map.setUI(customUI)
        gdir = new GDirections(map, document.getElementById("directions"));
        GEvent.addListener(gdir, "addoverlay", onGDirectionsLoad);
        GEvent.addListener(gdir, "error", handleErrors);
		var source_location=document.getElementById("source_location_name").value;
		var detination_location=document.getElementById("destination_location_name").value;
		//alert("source_data="+source_location+"destination_data="+detination_location);
        setDirections(source_location, detination_location, "india");
      }
    }
    
    function setDirections(fromAddress, toAddress, locale) {
      gdir.load("from: " + fromAddress + " to: " + toAddress,
                { "locale": locale });
    }

    function handleErrors(){
   if (gdir.getStatus().code == G_GEO_UNKNOWN_ADDRESS)
     alert("No corresponding geographic location could be found for one of the specified addresses. This may be due to the fact that the address is relatively new, or it may be incorrect.\nError code: " + gdir.getStatus().code);
   else if (gdir.getStatus().code == G_GEO_SERVER_ERROR)
     alert("A geocoding or directions request could not be successfully processed, yet the exact reason for the failure is not known.\n Error code: " + gdir.getStatus().code);
   else if (gdir.getStatus().code == G_GEO_MISSING_QUERY)
     alert("The HTTP q parameter was either missing or had no value. For geocoder requests, this means that an empty address was specified as input. For directions requests, this means that no query was specified in the input.\n Error code: " + gdir.getStatus().code);
   else if (gdir.getStatus().code == G_GEO_BAD_KEY)
     alert("The given key is either invalid or does not match the domain for which it was given. \n Error code: " + gdir.getStatus().code);
   else if (gdir.getStatus().code == G_GEO_BAD_REQUEST)
     alert("A directions request could not be successfully parsed.\n Error code: " + gdir.getStatus().code);
   else alert("An unknown error occurred.");
    }

  function onGDirectionsLoad(){ 
   var poly = gdir.getPolyline();
   if (poly.getVertexCount() > 100) {
     //alert("This route has too many vertices");
     return;
   }
   //var baseUrl = "http://maps.google.com/staticmap?";

   var params = [];
   var markersArray = [];
   markersArray.push(poly.getVertex(0).toUrlValue(5) + ",greena");
   markersArray.push(poly.getVertex(poly.getVertexCount()-1).toUrlValue(5) + ",greenb");
   params.push("markers=" + markersArray.join("|"));

   var polyParams = "rgba:0x0000FF80,weight:5|";
   var polyLatLngs = [];
   for (var j = 0; j < poly.getVertexCount(); j++) {
     polyLatLngs.push(poly.getVertex(j).lat().toFixed(5) + "," + poly.getVertex(j).lng().toFixed(5));
   }
   params.push("path=" + polyParams + polyLatLngs.join("|"));
   params.push("size=300x300");
   params.push("key=ABQIAAAAjU0EJWnWPMv7oQ-jjS7dYxSPW5CJgpdgO_s4yyMovOaVh_KvvhSfpvagV18eOyDWu7VytS6Bi1CWxw");

  /* baseUrl += params.join("&");

   var extraParams = [];
   extraParams.push("center=" + map.getCenter().lat().toFixed(6) + "," + map.getCenter().lng().toFixed(6));
   extraParams.push("zoom=" + map.getZoom());
   addImg(baseUrl + "&" + extraParams.join("&"), "staticMapOverviewIMG");

   var extraParams = [];
   extraParams.push("center=" + poly.getVertex(0).toUrlValue(5));
   extraParams.push("zoom=" + 15);
   addImg(baseUrl + "&" + extraParams.join("&"), "staticMapStartIMG");

   var extraParams = [];
   extraParams.push("center=" + poly.getVertex(poly.getVertexCount()-1).toUrlValue(5));
   extraParams.push("zoom=" + 15);
   addImg(baseUrl + "&" + extraParams.join("&"), "staticMapEndIMG");*/
}

function addImg(url, id) {
 var img = document.createElement("img");
 img.src = url;
 document.getElementById(id).innerHTML = "";
 document.getElementById(id).appendChild(img);
}

    </script>
	</head>
<body onload="javascript:initialize();">	
<table width="100%" border=0>
	<tr>
		<td>
			<table align="center" width="95%" class="menu" border="1" rules="all" bordercolor="black" border=1>
				<tr class="main_tr">
					<td align="center">
						432167890976
					</td>
					<td align="center">
						text 
					</td>
					<td align="center">
						kanpur
					</td>
					<td align="center">
						lucknow
					</td>
					<td align="center">
						18 Nov 2013 10:14
					</td>
					<td align="center" colspan="2">
						24 Nov 2013 10:14
					</td>
					<td align="center">
						some img
					</td>
				</tr>
				<tr class="main_tr">
					<td align="center" colspan="2">
						HR1234
					</td>
					<td align="left" colspan="2">
						tmp address
					</td>
					<td align="center">
						date symbol
					</td>
					<td align="center">
						21 Nov 2013 10:14
					</td>
					<td align="center">
						0:12
					</td>
					<td align="center">
						some img
					</td>
				</tr>
				<tr>
					<td align="center" colspan="8">
						<div id="map_route" style="width: 100%; height: 575px;"></div>
					</td>					
				</tr>				
			</table>
		</td>
	</tr>
</table>