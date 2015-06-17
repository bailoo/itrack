<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">  
  <head> 
	<?php
		include('main_google_key.php'); 
		include('common_js_css.php');
		include('manage_js_css.php');      
	?>  
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=drawing"></script>
    <style type="text/css">
      #map, html, body 
	  {
        padding: 0;
        margin: 0;
        height: 100%;
      }

      #panel 
	  {
        width: 200px;
        font-family: Arial, sans-serif;
        font-size: 13px;
        float: right;
        margin: 10px;
      }

      #color-palette 
	  {
        clear: both;
      }

      .color-button 
	  {
        width: 14px;
        height: 14px;
        font-size: 0;
        margin: 2px;
        float: left;
        cursor: pointer;
      }

      #delete-button 
	  {
        margin-top: 5px;
      }
    </style>
	<script type="text/javascript">
		var drawingManager;
		var selectedShape;
		var colors = ['#1E90FF', '#FF1493', '#32CD32', '#FF8C00', '#4B0082'];
		var selectedColor;
		var colorButtons = {};
	</script>
  </head>
  
  <body class="yui-skin-sam" topmargin="0" onresize="javascript:resize('manage')" onload="javascript:resize('manage')"> 
    <?php 
  
      include('main_frame_part2.php');
      include('module_frame_header.php');
      include('main_frame_part3.php');
      include('module_manage_menu.php');
      include('main_frame_part4.php');
      include('module_manage_body.php');
      include('main_frame_part5.php');
	include_once('manage_loading_message.php');
    ?>	
  </body>
            
</html>