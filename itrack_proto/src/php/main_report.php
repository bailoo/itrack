<html>  
  <head>      
     <?php
      //include('main_google_key.php'); 
      include('common_js_css.php');
	    include('report_js_css.php'); 
     ?>
  </head>
  
<body class="body_part" topmargin="0" onresize="javascript:resize()" onload="javascript:resize()">
  <?php 
    include("map_window/floating_map_window.php");
    include('main_frame_part2.php');
    include('module_frame_header.php');
    include('main_frame_part3.php');
    include('module_report_menu.php');
    include('main_frame_part4.php');
    include('module_report_body.php');
    include('main_frame_part5.php');
	include_once('manage_loading_message.php');
  ?>	
</body>
            
</html>