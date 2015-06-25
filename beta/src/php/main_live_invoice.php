<html>  
  <head>      
     <?php  
        include('main_google_key.php');
    	include('live_invoice_js_css.php');    	
        include('util_calculate_distance_js.php'); 		
       		
        $tot_vehicle_live_array=explode(",",substr($_POST['tot_vehicle_live'],0,-1));
		$tot_vehicle_live="";
		foreach($tot_vehicle_live_array as $tvl )
		{
			$tot_vehicle_live .=$tvl.":";
		}
		$tot_vehicle_live1=substr($tot_vehicle_live,0,-1);
		//echo 'Vehilce='.$tot_vehicle_live;
     ?>
	
  </head>
  
<body class="body_part" topmargin="0" onresize="javascript:resize()" onload="javascript:resize();">  
  
  <?php 
    include('main_frame_part2_invoice.php');
    //include('module_frame_header_dashboard.php');
    include('main_frame_part3_live_invoice.php');
    //include('module_home_menu.php');
    //include('module_live_menu.php');    
    //include('main_frame_part4.php');
    include('module_home_body_live_invoice.php');    
    include('main_frame_part5_invoice.php');
	
	
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
	echo '<input type="hidden" id="file_switch" value="'.$live_dash.'"/>';
	
	
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
<div style='display:none;'> 
 <?php
	include('module_live_invoice_vehicle_div.php');
  ?>
  <?php
  
echo"</div>";

  echo'<div id="blackout_1"> </div>
    <div id="divpopup_1">
	 <table border="0" class="main_page" width="100%">
  <tr>
	<td class="manage_interfarce" align="right" colspan="7"><a href="#" onclick="javascript:close_vehicle_display_option()" class="hs3"><img src="images/close.png" type="image" style="border-style:none;"></a>&nbsp;&nbsp;</td> 													
 </tr>	</table>
		<div id="selection_information" style="display:none;"></div>
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
	echo"<script language='javascript'>show_live_vehicles_invoice('".$tot_vehicle_live1."')</script>";
   // echo"<script language='javascript'>initialize()</script>";
	echo "<input type='hidden' name='lacStr' id='lacStr'>";
	echo "<input type='hidden' name='final_loc_request' id='final_loc_request' value='-1'>";
	echo "<input type='hidden' name='js_action' id='js_action' value='js1'>";	
	?>
  
</FORM>    		

<script type="text/javascript">
//alert('p');
document.getElementById('live_all_vehicle').checked=true;
//alert('a');
function test_1()
{
	///alert("hello");
}
function checkbox_selection(obj)
{
	var flag=0;
	var cnt=0;
	var id="";
	//alert("obj="+obj+" ,len="+obj.length+" checked="+obj.checked)
	if(obj.length!=undefined)
	{
		for (var i=0;i<obj.length;i++)
		{
			if(obj[i].checked==true)
			{				
				if(cnt==0)
				{
					id= id + obj[i].value;
					cnt=1;
				}
				else
				{
					id=id +","+ obj[i].value;
				}
				flag=1;
			}	  
		}
	}
	else
	{
		//alert("In else");
    if(obj.checked==true)
		{
			id=obj.value;
			flag=1;
		}
	}
	if(flag==0)
	{
		alert("Please Select Atleast One Option");
		return false;
	}
	else
	{	  
		return id;
	}
}
  function testText()
  {
    //alert("Processing");
    var currentDate2 = new Date;
 	var yr = currentDate2.getFullYear();
	var mnt =  currentDate2.getMonth()+1;
	var dt =  currentDate2.getDate();
	var hr = currentDate2.getHours();
	var min = currentDate2.getMinutes();
	var sec = currentDate2.getSeconds();
 	if(mnt>0&&mnt<10)
		mnt = "0"+mnt ;
 	if(dt>0&&dt<10)
		dt = "0"+dt;
 	if(hr>0&&hr<10)
		hr = "0"+hr;
 	if(min>0&&min<10)
		min = "0"+min;
 	if(sec>0&&sec<10)
		sec = "0"+sec;
 	startdate = yr+"-"+mnt+"-"+dt+" 00:00:00";
	enddate = yr+"-"+mnt+"-"+dt+" "+hr+":"+min+":"+sec;
	//alert("1");
	var obj=document.form1.elements['live_vehicles[]'];
	//var obj=document.getElementById('live_vehicles[]').value;
	//alert(obj);
	//alert(document.form1);
	if(obj == undefined)
	{	
		var no_trip_msg="<font style='font-size:15px;color:red'>&nbsp;No GPS Information Received.</font>";
		//alert(no_trip_msg);
		document.getElementById('text_col').style.display='';
		//alert('1');
		document.getElementById('text_col_content').innerHTML=no_trip_msg;		
		//alert('2');
	}
	//alert('2');
	var dispatch_time=document.form1.elements['dispatch_time[]'];
	var target_time=document.form1.elements['target_time[]'];
	var plant_number=document.form1.elements['plant_number[]'];
	//alert('3');
	var cnt1=0;
	var dispatch_time_list="";
	var target_time_list="";
	var plant_number_list="";
	if(dispatch_time.length!=undefined)
	{
		for (var k=0;k< dispatch_time.length;k++)
		{						
			if(cnt1==0)
			{
				dispatch_time_list= dispatch_time_list + dispatch_time[k].value;
				//alert("1");
				target_time_list= target_time_list + target_time[k].value;
				//alert("2");
				plant_number_list= plant_number_list + plant_number[k].value;
				//alert("3");
				cnt1=1;
			}
			else
			{
				dispatch_time_list=dispatch_time_list +","+ dispatch_time[k].value;
				target_time_list= target_time_list +","+ target_time[k].value;
				plant_number_list= plant_number_list +","+ plant_number[k].value;
			}			
				  
		}
	}
	else
	{		
		dispatch_time_list= dispatch_time_list + dispatch_time.value;
		//alert("1");
		target_time_list= target_time_list + target_time.value;
		//alert("2");
		plant_number_list= plant_number_list + plant_number.value;
		//alert("3");
	
	}
	
	
	
	
	
	  var result=checkbox_selection(obj);
	    //alert("3");
        if(result!=false)
        {  
			//alert(result);
            var date = new Date();        
            var dest = "../../../xml_tmp/filtered_xml/tmp_"+date.getTime()+".xml";
			//alert("dest="+dest);
            var dmode = 1; 			
            var poststr = "xml_file=" + encodeURI( dest )+
                    "&mode=" + encodeURI( dmode )+
                    "&vserial=" + encodeURI( result )+
					"&vdispatch_time=" + encodeURI( dispatch_time_list )+
					"&vtarget_time=" + encodeURI( target_time_list )+
					"&vplant_number=" + encodeURI( plant_number_list )+
                    "&startdate=" + encodeURI( startdate )+
                    "&enddate=" + encodeURI( enddate )+
					"&lacStr=" + encodeURI( document.getElementById('lacStr').value )+
					"&file_switch=" + encodeURI( document.getElementById('file_switch').value )+
					"&title="+encodeURI( document.getElementById('js_action').value );	
					//alert("poststr="+poststr);					
					$.ajax({
						type: "POST",
						url:'../php/get_filtered_xml_text_live_main_invoice.php',
						data: poststr,
						success: function(response)
						{ 
						  

								//document.getElementById('map_col').style.display = 'none';
							document.getElementById('vlist_col').style.display = 'none';
							document.getElementById('text_col').style.display = '';
					
					/*var options= document.getElementById('mode_selector').options;
					for (var j= 0, n= options.length; j < n ; j++) 
					{
						if (options[j].value=='2') 
						{
							document.getElementById("mode_selector").selectedIndex = j;
						}			
					}*/
							//document.getElementById('dummy_div').style.display='none';    						
							//document.getElementById('prepage').style.visibility='hidden';
							//Salert("response="+response);
							document.getElementById('text_col').style.display='';
							//alert("response="+response);
							$("#text_col_content").html(response);
							
							//document.getElementById('text_col_content').innerHTML = result;
							blink("blinkMe","green","white",500);
						},
						error: function()
						{
							alert('An unexpected error has occurred! Please try later.');
						}
					});			
					//makePOSTRequestText('src/php/get_filtered_xml_text_live_main.php', poststr); 					
        } // if vid closed
		
		//jsActionNo('js2');
		//alert("Moving vehicle called");
		var jsActionNo='js2';
	auto_refresh(jsActionNo);
	}
	
	
var last_marker;

reset();
//alert("K");
function reset()
{
	//alert("K");
	//alert("document="+document.form1);
	document.form1.last_marker.value = "";
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
	//alert("In setref");
	//alert(document.getElementById("final_loc_request").value);
	/*if(document.getElementById("final_loc_request").value=="0")
	{
		return false;
	}*/
	testText('js2');
}

function auto_refresh(jsActionNo)
{	
	//alert("in autorefresh-JSaction="+jsActionNo);
	//var value = document.form1.autoref_combo.value;
	var value = document.form1.autoref_combo.value;
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

</body>
            
</html>