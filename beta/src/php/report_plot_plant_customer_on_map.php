<html>  
  <head>      
     <?php	
		// include('main_google_key.php');
		include('route_map_js_css.php');
		include('route_common_js_css.php');
		include('util_calculate_distance_js.php');
		//echo"<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>";
		echo"<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places'></script>";
		echo'<script type="text/javascript" src="src/js/jquery-1.3.2"></script>';
		echo'<script type="text/javascript" src="src/js/markerwithlabel.js"></script>';
		include('customerPlantGoogleMapApi.php');				
		//include('main_frame_part1.php');          
     ?>  
	<script>
		 function ChangeText(oFileInput, sTargetID) {   
			document.getElementById(sTargetID).value = oFileInput.value;		
		 }
		 
		 function show_hide_option(type) {
			var id = type;
			var row = type+"_row";
			//alert(id+" ,"+row);
			if(document.getElementById(id).checked) {
				document.getElementById(row).style.display = '';
			} else {
				document.getElementById(row).style.display = 'none';
			}
		 }
		 
		 function show_customer_plant_list() {
			var request = $.ajax({
				
				url: "src/php/show_transporters.php",
				type: "POST",
				data: "city_id_source="+city_id_source+"&city_id_dest="+city_id_dest+"&city_source="+city_source+"&city_dest="+city_dest+"&door_close="+door_close+"&refrigerated="+refrigerated+"&date1="+date1+"&category_id="+category_id+"&category="+category+"&item="+item+"&capacity="+capacity+"&vehicle_count="+vehicle_count,
				dataType: "html"
			});

			request.done(function(msg) {
				$("#transporter_box").html(msg);
			});

			request.fail(function(jqXHR, textStatus) {
				alert("Request failed: " + textStatus);
			});
		 }
	</script>  
  </head>
  <body class="body_part" topmargin="0"  onload="javascript:resize('home')"  onresize="javascript:resize('home')">

  <?php
    $flag_station = 0;
	$flag_visit_track = 0;
    
  	for($k=0;$k<$size_feature_session;$k++)
  	{
  		//$feature_id_session[$k];
  		if($feature_name_session[$k] == "station")
  		{
  		  $flag_station = 1;
  		  break;
		}
      //echo "<br>feature_name=".$feature_name_session[$k];
    }
	for($k=0;$k<$size_feature_session;$k++)
  	{
  		//$feature_id_session[$k];
  		if($feature_name_session[$k] == "visit_track")
  		{
  		  $flag_visit_track = 1;
  		  break;
		}
      //echo "<br>feature_name=".$feature_name_session[$k];
    }
    
    //$flag_station = 1;
    
    if($flag_station)
    {
      echo '<input type="hidden" id="station_flag_map" value="1"/>';
    }
    else
    {
      echo '<input type="hidden" id="station_flag_map" value="0"/>';
    }
	
	if($flag_visit_track)
    {
      echo '<input type="hidden" id="schedule_location_flag" value="1"/>';
    }
    else
    {
      echo '<input type="hidden" id="schedule_location_flag" value="0"/>';
    }
	$flag_chilling_plant = 0;
	for($k=0;$k<$size_feature_session;$k++)
	{
		//echo $feature_name_session[$k]."<br>";
		if($feature_name_session[$k] == "chilling_plant")
		{
			$flag_chilling_plant = 1;
		}
	}
	//echo "cPlant=".$flag_chilling_plant."<br>";
    echo '<input type="hidden" id="customer_list"/>';
	
	include('main_frame_part2.php');
    include('plant_customer_module_frame_header.php');
	//include('module_frame_header.php');
    include('main_frame_part3_plant.php');
    //include('route_module_home_menu.php');
    //include('main_frame_part4.php');
    include('module_customer_plant_home_body.php');
	include('module_filter_customer_plant.php');
	echo '<div id="map_home"/>';    
    include('main_frame_part5.php');
	

  ?>	
</body>
            
</html>