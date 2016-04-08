<html>  
	<head>      
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
	</body>            
</html>