<html>  
  <head>      
     <?php  
        include('main_google_key.php');
    		include('live_js_css_test.php');
    		//echo'<script language="javascript" src="src/js/live.js"></script>';
        include('util_calculate_distance_js.php');	                        
       //include('main_frame_part1.php');
     ?>
  </head>
  
<body class="body_part" topmargin="0" onresize="javascript:resize()" onload="javascript:resize();">  
  
  <?php 
    include('main_frame_part2.php');
    include('module_frame_header.php');
    include('main_frame_part3_live.php');
    //include('module_home_menu.php');
    //include('module_live_menu.php');    
    //include('main_frame_part4.php');
    include('module_home_body_live.php');    
    include('main_frame_part5.php');
    
    echo"<script language='javascript'>show_live_vehicles()</script>";
    echo"<script language='javascript'>initialize()</script>";          
  ?>	

<FORM method="GET" name="form1"> 
 
        <input type="hidden" name="lat">
    		<input type="hidden" name="lng">
    		<input type="hidden" name="vid2">
    		<input type="hidden" name="last_marker">
    		<input type="hidden" name="pt_for_zoom">
    		<input type="hidden" name="zoom_level">
    		<input type="hidden" name="current_vehicle">
    		<input type="hidden" name="cvflag">
    		<input type="hidden" name="mapcontrol_startvar">
    		<input type="hidden" name="StartDate">
    		<input type="hidden" name="EndDate">
    		<input type="hidden" name="vehicleSerial">
    		<input type="hidden" name="StartDate1">
    		<input type="hidden" name="EndDate1">  		
  <?php
  include('module_live_vehicle_div.php');
  ?>
  
</FORM>    		

<script type="text/javascript">

var last_marker;

reset();
//alert("K");
function reset()
{
	//alert("K");
	//alert("document="+document.form1);
	document.forms[0].last_marker.value = "";
}

////// call autorefresh function //////
//auto_refresh();
///////////////////////////////////////

//movingVehicle();

var min2;
var end_date;

var currentDate1 = new Date;

var min = currentDate1.getMinutes();

min2 = min + 1;
var timer1;

//movingVehicle();	      //////////////////////

function set_ref_values()
{
	movingVehicle();	
}

function auto_refresh()
{	
	//alert("in autorefresh");
	//var value = document.form1.autoref_combo.value;
	var value = document.forms[0].autoref_combo.value;
	document.form1.cvflag.value=1;
		
	var interval;
	
  if(value == 0)
	{
	 interval = 0;
	 clearTimeout(timer1);
  }    
	//interval=1;
	
  interval = value*1000;
	
	//alert("value="+value+" interval="+interval);
	if(interval>1)
	{	
		clearTimeout(timer1);
		timer1=setTimeout('set_ref_values()',interval);
	}
}
</script>	

</body>
            
</html>