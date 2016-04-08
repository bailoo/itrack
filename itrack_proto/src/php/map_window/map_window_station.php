<script type="text/javascript" src="src/js/extlargemapcontrol.js"></script>
<script type="text/javascript" src="src/js/calculate_distance.js"></script>
<script type="text/javascript" src="src/js/mapwindow_station.js"></script>
<script type="text/javascript" src="src/js/resources/map.js"></script>

<link rel="stylesheet" type="text/css" href="gm_minimap_in_infowindow_files/mapStyle.css">	
<link rel="stylesheet" href="div_mes_box1.css">	

<style type="text/css">
 @import url("http://www.google.com/uds/css/gsearch.css");
 @import url("http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css");
 }
</style>


<?php
  include_once("src/php/main_google_key.php");
?>

<script src="./src/js/labeledmarker.js"></script>

<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0" type="text/javascript"></script>
<script src="http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js" type="text/javascript"></script> 	

<script src="./src/js/dragzoom/gzoom.js" type="text/javascript"></script>
<script type="text/javascript" src="src/js/pdmarker.js"></script>

<script type="text/javascript" language="javascript">

function map_window_station_prev(customer,station_id,station_name,lat,lng)
{
//	alert(customer+','+station_id+','+station_name+','+lat+','+lng);
  //alert(vname+" "+datetime+" "+lat+" "+lng);	
	//test2(vname,datetime,lat,lng);			
	document.getElementById("window").style.display = '';
	load_stations(customer,station_id,station_name,lat,lng);							
}

</script>

