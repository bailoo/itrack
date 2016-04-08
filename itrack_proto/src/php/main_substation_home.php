<html>  
  <head>      
     <?php       	
    	include('map_js_css.php');
    	include('common_js_css.php');
        include('util_calculate_distance_js.php');			
		echo"<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places'></script>";
		echo'<script type="text/javascript" src="src/js/markerwithlabel.js"></script>';
		include('googleMapApi.php');     
		
		include('manage_js_css.php');   
     ?>      
  </head>
  
<body class="body_part" topmargin="0"  onload="javascript:resize('home')"  onresize="javascript:resize('home')">

  <?php
   
    //echo "AccountID".$account_id;      
	include('main_frame_substation1.php');
    include('module_frame_substation_header.php');
    include('main_frame_substation2.php');
    //include('module_home_menu.php');	
    include('main_frame_substation4.php');	
    //include('module_home_body.php'); 
	
    include('main_frame_substation3.php');
include_once('manage_loading_message.php');	
  ?>	
</body>
            
</html>