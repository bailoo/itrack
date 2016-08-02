<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml/DTD/xhtml1-strict.dtd">
<html>  
  <head> 
           <!-- Bootstrap core CSS -->
    <link href="src/thirdparty/ast_bs/dist/css/bootstrap.css" rel="stylesheet">
    <link href="src/thirdparty/ast_bs/dist/css/simple-sidebar.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="src/thirdparty/ast_bs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="src/thirdparty/ast_bs/dashboard.css" rel="stylesheet">
    <link href="src/thirdparty/ast_bs/theme.css" rel="stylesheet">
	
	<!-- Bootstrap theme -->
    <link href="src/thirdparty/ast_bs/dist/css/bootstrap-theme.min.css" rel="stylesheet">
   
    <!--<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
    <!--<script type="text/javascript" src="src/js/jquery-2.1.1.min.js"></script>--> 
    <script src="src/thirdparty/ast_bs/dist/js/bootstrap-toggle.js"></script>  
    <!-- Custom Fonts -->
    <link href="src/thirdparty/ast_bs/bower_components/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
     
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
        <?php
//echo "ddd".$_SESSION['drop_down_menu_js_type'];
/*if($_SESSION['drop_down_menu_js_type']=='undefined')
{
 ?>
<script>
    window.onload = function () 
    {        
     manage_show_file( <?php echo "'" .$_SESSION['drop_down_menu_file'] . "'"; ?> );
    }
</script>
 <?php
}*/

if($_SESSION['drop_down_menu_module']=="report.htm")
{
	
  //if($_SESSION['drop_down_menu_js_type']=='report_common_prev')
  if($_SESSION['drop_down_menu_report_type_upload']=='')
  {
    ?>
   <script>
   //alert("test");
      ///////// this is for data log by person map popup window
       var dataLogImei="<?php echo $dataLogImei; ?>";
    //alert("dataLogImei="+dataLogImei);
       var start_date_map="<?php echo $start_date_map; ?>";
       var end_date_map="<?php echo $end_date_map; ?>";
   if(dataLogImei=="")
   {
       window.onload = function () 
       { 
           //alert("un");
        <?php echo  $_SESSION['drop_down_menu_js_type']; ?>( <?php echo "'" .$_SESSION['drop_down_menu_file'] . "', '".$_SESSION['drop_down_menu_title_report'] . "'"; ?> );
       }
   }
   else
   {
		  var result= "<?php echo $account_id; ?>";        
        var poststr = "account_id_local="+result+
        "&vehicle_display_option=all"+
        "&title1=data log"+
        "&dataLogImei="+dataLogImei+
        "&start_date_map="+start_date_map+
        "&end_date_map="+end_date_map+
        "&options_value=all";
        //alert("poststr="+poststr);
        showManageLoadingMessage();
        makePOSTRequest('src/php/datalog_between_dates.htm',poststr);
		
   }
   </script>
    <?php
	unset($dataLogImei);
	unset($start_date_map);
	unset($end_date_map);
  }
  else
  {
       ?>
   <script>
       window.onload = function () 
       {  
          // alert("fn");
        <?php echo  $_SESSION['drop_down_menu_js_type']; ?>( <?php echo "'" .$_SESSION['drop_down_menu_file'] . "', '".$_SESSION['drop_down_menu_title_report'] . "', '".$_SESSION['drop_down_menu_report_type_upload'] . "'  "; ?> );
       }
   </script>
    <?php
  }
}
else
{//final else
 ?>
   <script>
      // alert("test");
      ///////// this is for data log by person map popup window
       var dataLogImei="<?php echo $dataLogImei; ?>";
	  // alert("dataLogImei="+dataLogImei);
       var start_date_map="<?php echo $start_date_map; ?>";
       var end_date_map="<?php echo $end_date_map; ?>";
   if(dataLogImei=="")
   {
       window.onload = function () 
       { 
           //alert("un");
        <?php echo  $_SESSION['drop_down_menu_js_type']; ?>( <?php echo "'" .$_SESSION['drop_down_menu_file'] . "', '".$_SESSION['drop_down_menu_title_report'] . "'"; ?> );
       }
   }
   else
   {
		  var result= "<?php echo $account_id; ?>";        
          var poststr = "account_id_local="+result+
        "&vehicle_display_option=all"+
        "&title1=data log"+
        "&dataLogImei="+dataLogImei+
        "&start_date_map="+start_date_map+
        "&end_date_map="+end_date_map+
        "&options_value=all";
        //alert("poststr="+poststr);
        //showManageLoadingMessage();
        makePOSTRequest('src/php/datalog_between_dates.htm',poststr);
		
   }
   </script>
    <?php
	unset($dataLogImei);
	unset($start_date_map);
	unset($end_date_map);
}
?>
</body>
            
</html>