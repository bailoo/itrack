
<html>  
  <head>      
     <?php  
        include('main_google_key.php');
    	include('live_js_css.php');
    	//echo'<script language="javascript" src="src/js/live.js"></script>';
        include('util_calculate_distance_js.php');	
        include('coreDb.php');
       //include('main_frame_part1.php');	
     ?>
      <!-- Bootstrap core CSS -->
    <link href="src/thirdparty/ast_bs/dist/css/bootstrap.css" rel="stylesheet">   
    <!--<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
    <script type="text/javascript" src="src/js/jquery-2.1.1.min.js"></script>
    <script src="src/thirdparty/ast_bs/dist/js/bootstrap-toggle.js"></script>  
    <!-- Custom Fonts -->
    <link href="src/thirdparty/ast_bs/bower_components/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
     
<script>  
	 

//var exists = isFile("src/css/module_hide_show_div.css");
//alert("exists="+exists);	 
	   
	   function isFile(str){
var O= AJ();
if(!O) return false;
try
{
O.open("HEAD", str, false);
O.send(null);
return (O.status==200) ? true : false;
}
catch(er)
{
return false;
}
}
function AJ()
{
var obj;
if (window.XMLHttpRequest)
{
obj= new XMLHttpRequest();
}
else if (window.ActiveXObject)
{
try
{
obj= new ActiveXObject('MSXML2.XMLHTTP.3.0');
}
catch(er)
{
obj=false;
}
}
return obj;
}
</script>

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
	if($flag_station)
    {
		 echo '<input type="hidden" id="station_flag_map" value="1"/>';
		
		//### GET ALL EVENING ROUTES FROM DATABASE
		$query_assigned = "SELECT vehicle_name,route_name_ev,route_name_mor FROM route_assignment2 WHERE user_account_id='$account_id' AND status=1";
		$result_assigned = mysql_query($query_assigned,$DbConnection);
		
		//$vname_assigned = array();
		//$route_assigned = array();
		$i=0;
		$j=0;
		while($row=mysql_fetch_object($result_assigned))
		{
			$vname_assigned_ev = $row->vehicle_name;
			$route_assigned_ev = $row->route_name_ev;
			$vname_assigned_mor = $vname_assigned_ev;
			$route_assigned_mor = $row->route_name_mor;
									
			if($route_assigned_ev!="") 
			{
				$vname_id_ev = 'vname_ev'.$i;
				$route_id_ev = 'route_ev'.$i;		
				echo "<input type='hidden' id='".$vname_id_ev."' value='".$vname_assigned_ev."'/>";
				echo "<input type='hidden' id='".$route_id_ev."' value='".$route_assigned_ev."'/>";
					
				$i++;
			}
			if($route_assigned_mor!="") 
			{
				$vname_id_mor = 'vname_mor'.$j;
				$route_id_mor = 'route_mor'.$j;		
				echo "<input type='hidden' id='".$vname_id_mor."' value='".$vname_assigned_mor."'/>";
				echo "<input type='hidden' id='".$route_id_mor."' value='".$route_assigned_mor."'/>";
				
				$j++;
			}
		}
		echo "<input type='hidden' id='route_limit_ev' value='".$i."'/>";
		echo "<input type='hidden' id='route_limit_mor' value='".$j."'/>";
		//#################################		
	}
	else
	{
		echo '<input type="hidden" id="station_flag_map" value="0"/>';
	}
	
	
	//echo "LIVE_COLOR=".$live_color;	
	if($live_color != "")
	{
	  $flag_live_color = 1;
	  //echo "COLOR_SET";	  
	  echo '<input type="hidden" id="live_color_flag" value="1"/>';
	  echo '<input type="hidden" id="live_color_code" value="'.$live_color.'"/>';   	  
	}	
	else
	{
		echo '<input type="hidden" id="live_color_flag" value="0"/>';
	}	
	//#################	
    
    //echo"<script language='javascript'>show_live_vehicles()</script>";
    //echo"<script language='javascript'>initialize()</script>";          
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
  <?php
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
	';
	echo"<script language='javascript'>show_live_vehicles()</script>";
    echo"<script language='javascript'>initialize()</script>";
	echo "<input type='hidden' name='lacStr' id='lacStr'>";
	echo "<input type='hidden' name='final_loc_request' id='final_loc_request' value='-1'>";
	echo "<input type='hidden' name='js_action' id='js_action' value='js1'>";	
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

function set_ref_values(jsActionNo)
{
	//alert(document.getElementById("final_loc_request").value);
	if(document.getElementById("final_loc_request").value=="0")
	{
		return false;
	}
	movingVehicle('js2');
}

function auto_refresh(jsActionNo)
{	
	//alert("in autorefresh-JSaction="+jsActionNo);
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
	/*if(interval>1)
	{	
		clearTimeout(timer1);
		timer1=setTimeout('set_ref_values()',interval);
	}*/
	if(interval>1)
	{	
		//alert(">1");
		clearTimeout(timer1);
		timer1=setTimeout(function() {set_ref_values(jsActionNo);}, interval);
		//timer1=setTimeout('set_ref_values('+jsActionNo+')',interval);
	}	
}
</script>	
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