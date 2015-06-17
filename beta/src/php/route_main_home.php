<html>  
  <head>      
     <?php	
		// include('main_google_key.php'); 			
		include('route_map_js_css.php');
		include('route_common_js_css.php');
		include('util_calculate_distance_js.php');	
		//echo"<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>";
		echo"<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places'></script>";
		echo'<script type="text/javascript" src="src/js/markerwithlabel.js"></script>';
		include('routeGoogleMapApi.php');
		//include('main_frame_part1.php');          
     ?>  
	 <script>
	 function ChangeText(oFileInput, sTargetID) {
   
    document.getElementById(sTargetID).value = oFileInput.value;
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
            
	include('main_frame_part2.php');
    include('route_module_frame_header.php');
    include('main_frame_part3.php');
    include('route_module_home_menu.php');
    include('main_frame_part4.php');
	if($size_utype_session>1)
  	{
  		echo"<input type='hidden' id='mining_user' value=''>";
  		echo"<script language='javascript'>show_main_home_vehicle('default')</script>";
  	}
  	else if($size_utype_session==1)
  	{
  	
  		if($user_typeid_array[0]==5)
  		{
  			echo"<input type='hidden' id='mining_user' value='".$user_typeid_array[0]."'>";
  		}
  		else
  		{
  			echo"<input type='hidden' id='mining_user' value=''>";
  		}  		
  		echo"<script language='javascript'>show_main_home_vehicle('single')</script>";
	}
    include('module_route_home_body.php');
echo '<div id="map_home"/>';    
    include('main_frame_part5.php');
	

  ?>	
</body>
            
</html>