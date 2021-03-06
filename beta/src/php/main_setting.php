<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml/DTD/xhtml1-strict.dtd">
<html>  
	<head>
           <!-- Bootstrap core CSS -->
    <link href="src/thirdparty/ast_bs/dist/css/bootstrap.css" rel="stylesheet">    
    <!--<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
   <!-- <script type="text/javascript" src="src/js/jquery-2.1.1.min.js"></script>-->
    <script src="src/thirdparty/ast_bs/dist/js/bootstrap-toggle.js"></script>  
    <!-- Custom Fonts -->
    <link href="src/thirdparty/ast_bs/bower_components/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
     
	<?php
		include('common_js_css.php');
		include('setting_js_css.php');
	//include('main_frame_part1.php');
	?>
	</head>

	<body class="body_part" topmargin="0" onresize="javascript:resize('manage')" onload="javascript:resize('manage')"> 
		<?php 
			include('main_frame_part2.php');
			include('module_frame_header.php');
			include('main_frame_part3.php');
			include('module_setting_menu.php');
			include('main_frame_part4.php');
			include('module_setting_body.php');
			include('main_frame_part5.php');
			include_once('manage_loading_message.php');
		?>	
            <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        
          <script src="src/thirdparty/ast_bs/dist/js/bootstrap.min.js"></script>
        <!--<script src="src/thirdparty/ast_bs/dist/js/jquery.js"></script>-->
        <!-- Menu Toggle Script -->
        <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
        
       
           
       
      </script>
	</body>            
</html>