<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">  
  <head> 
    <!-- Bootstrap core CSS -->
    <link href="src/thirdparty/ast_bs/dist/css/bootstrap.css" rel="stylesheet">   
    <!--<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
    <!--<script type="text/javascript" src="src/js/jquery-2.1.1.min.js"></script>--> 
    <script src="src/thirdparty/ast_bs/dist/js/bootstrap-toggle.js"></script>  
    <!-- Custom Fonts -->
    <link href="src/thirdparty/ast_bs/bower_components/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
     
	<?php
		include('main_google_key.php'); 
		include('common_js_css.php');
		include('manage_js_css.php');  
                //==updated on 13032015=========
		include('polyline_map_js_css.php');
		include('util_calculate_distance_js.php');
		//==updated on 13032015=========
		echo"<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places'></script>";
		echo'<script type="text/javascript" src="src/js/markerwithlabel.js"></script>';
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
      

      
  
 /*
Force table width to 100%
*/
 table.table-fixedheader {
    width: 100%;   
}
/*
Set table elements to block mode.  (Normally they are inline).
This allows a responsive table, such as one where columns can be stacked
if the display is narrow.
*/
 table.table-fixedheader, table.table-fixedheader>thead, table.table-fixedheader>tbody, table.table-fixedheader>thead>tr, table.table-fixedheader>tbody>tr, table.table-fixedheader>thead>tr>th, table.table-fixedheader>tbody>td {
    display: block;
}
table.table-fixedheader>thead>tr:after, table.table-fixedheader>tbody>tr:after {
    content:' ';
    display: block;
    visibility: hidden;
    clear: both;
}
/*
When scrolling the table, actually it is only the tbody portion of the
table that scrolls (not the entire table: we want the thead to remain
fixed).  We must specify an explicit height for the tbody.  We include
100px as a default, but it can be overridden elsewhere.

Also, we force the scrollbar to always be displayed so that the usable
width for the table contents doesn't change (such as becoming narrower
when a scrollbar is visible and wider when it is not).
*/
 table.table-fixedheader>tbody {
    overflow-y: scroll;
    height: 100px;
    
}
/*
We really don't want to scroll the thead contents, but we want to force
a scrollbar to be displayed anyway so that the usable width of the thead
will exactly match the tbody.
*/
 table.table-fixedheader>thead {
    overflow-y: scroll;    
}
/*
For browsers that support it (webkit), we set the background color of
the unneeded scrollbar in the thead to make it invisible.  (Setting
visiblity: hidden defeats the purpose, as this alters the usable width
of the thead.)
*/
 table.table-fixedheader>thead::-webkit-scrollbar {
    background-color: inherit;
}


table.table-fixedheader>thead>tr>th:after, table.table-fixedheader>tbody>tr>td:after {
    content:' ';
    display: table-cell;
    visibility: hidden;
    clear: both;
}

/*
We want to set <th> and <td> elements to float left.
We also must explicitly set the width for each column (both for the <th>
and the <td>).  We set to 20% here a default placeholder, but it can be
overridden elsewhere.
*/

 table.table-fixedheader>thead tr th, table.table-fixedheader>tbody tr td {
    float: left;    
    word-wrap:break-word;     
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
       <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->        
        <script src="src/thirdparty/ast_bs/dist/js/bootstrap.min.js"></script>        
        <!-- Menu Toggle Script -->
        <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
        
       
     $(document).ready(function(){
      // add 30 more rows to the table
      var row = $('table#mytable > tbody > tr:first');
      var row2 = $('table#mytable2 > tbody > tr:first');
      for (i=0; i<30; i++) {
        $('table#mytable > tbody').append(row.clone());
        $('table#mytable2 > tbody').append(row2.clone());
      }

      // make the header fixed on scroll
      $('.table-fixed-header').fixedHeader();
    });
        
      </script>
  </body>
            
</html>