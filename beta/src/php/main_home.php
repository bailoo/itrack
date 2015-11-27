<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml/DTD/xhtml1-strict.dtd">
<html>  
  <head> 

     <?php	
		// include('main_google_key.php'); 			
		include('map_js_css.php');
		include('common_js_css.php');
		include('util_calculate_distance_js.php');	
		//echo"<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>";
		echo"<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places'></script>";
		echo'<script type="text/javascript" src="src/js/markerwithlabel.js"></script>';
		include('googleMapApi.php');
		//include('main_frame_part1.php');          
     ?>      
  </head>
  
<body class="body_part" topmargin="0">

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
    include('module_frame_header.php');
    include('main_frame_part3.php');
    include('module_home_menu.php');
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
    include('module_home_body.php');    
    include('main_frame_part5.php');
		    
	 echo ' <form name="divForm">';		
			echo'<div id="blackout_1"> </div>
    <div id="divpopup_1">
	 <table border="0" class="main_page" width="100%">
  <tr>
	<td class="manage_interfarce" align="right" colspan="7"><a href="#" onclick="javascript:close_vehicle_display_option()" class="hs3"><img src="images/close.png" type="image" style="border-style:none;"></a>&nbsp;&nbsp;</td> 													
 </tr>	</table>
		<div id="selection_information" style="height:85%;width:100%;overflow:auto;display:none;"></div>
              <br><center> <input type="button" name="submit" value="Enter" onclick="javascript:display_vehicle_according_divoption(this.form)"></center>
	</div>';
echo'<div id="blackout_2"> </div>
    <div id="divpopup_2">
	<table border="0" class="module_left_menu" width="100%">
          <tr>
            <td class="manage_interfarce" align="right" colspan="7"><a href="#" onclick="javascript:close_portal_vehicle_information()" class="hs3"><img src="Images/close.png" type="image" style="border-style:none;"></a>&nbsp;&nbsp;</td> 													
    	  </tr>
	</table>
	<div id="portal_vehicle_information" style="display:none;"></div>        
    </div>
	</form>';
	?> 
	<div id="debugDiv" style="display:none;"></div>
</body>
            
</html>