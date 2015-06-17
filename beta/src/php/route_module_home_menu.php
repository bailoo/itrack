<table border='0' width="100%" height="100%" cellspacing="0" cellspacing="0">
    <tr class="left_tr1">
      <td>
        <?php 
		include('module_logo.php');  
		include('user_type_setting.php');
		?>       
      </td>
    </tr>    
  <tr class="left_tr2">
    <td valign="top">
      <div style="overflow-x:hidden;overflow-y:auto;" id="leftMenu">  
      <form name="thisform" action='src/php/motherDairyRouteUpload.htm' target="_blank" method="POST" enctype="multipart/form-data">
  		<?php 
  			if($mining_user_type==1 && $account_id!=1)
  			{
  				HomeGetGroup($root);
  			}
  		?>
      <input type="hidden" name="last_dateopt"/>
      <input type="hidden" name="track_mode">
      <input type="hidden" name="last_pos_mode">
      <input type="hidden" name="pt_for_zoom">
      <input type="hidden" name="zoom_level">
      <input type="hidden" name="place_tmp">
      <input type="hidden" name="current_vehicle">
      <input type="hidden" name="cvflag">
      <input type="hidden" name="vid2">
      <input type="hidden" name="lat">
      <input type="hidden" name="lng">
      <input type="hidden" name="last_marker">
      <input type="hidden" name="action_marker">	
      <input type="hidden" name="veh_validation">
      <input type="hidden" name="GEarthStatus">	
      <input type="hidden" name="earthmode">					
      <input type="hidden" name="len2">		      
        <table border='0' width="100%" class='module_left_menu' cellspacing="0" cellpadding="0">
        <?php			
           // include('module_superuser.php');
            include('module_route_vehicle.php');                       
            include('module_route_select_track.php');
          //include('module_refresh.php');
           // include('module_mouse_action.php');       
		echo '<input type="hidden" name="location" checked>
		<input type="hidden" name="geofence_feature"> ';		
		/*echo'<tr>
				<td>
					<table border="0" class="module_left_menu">
						<tr>
							<td>
								Data With Location &nbsp;&nbsp;
								<input type="checkbox" name="location" checked>     
							</td>
						</tr>
						<tr>
							<td>
								Geofence &nbsp;&nbsp;
								<input type="checkbox" name="geofence_feature">     
							</td>
						</tr>
					</table>
				</td>
			</tr>';*/
			echo'<input type="hidden" name="latlng">';
			echo'<input type="hidden" name="m1" value="1"/>';
			echo'<input type="hidden" name="m2" value="1"/>';
			echo'<input type="hidden" name="m3" value="1"/>';
			echo'<input type="hidden" name="m4" value="1"/>';
			echo'<input type="hidden" name="mouse_action" value="click"/>';
		
           // include('module_latlng.php');
            //include('module_speed_symbol.php');
            
            /* if($flag_station==1)
            {
              include('module_station.php');
            }
            if($flag_visit_track==1)
            {
             include('module_schedule_location.php');
            }  
			 include('module_landmark.php'); */   
			 echo '
    <tr>
      <td>
      <table width="100%" class="menu">
        <tr>

		   <td>
             Routes 
          </td>
		   <td>
            :
          </td>
		   <td>
           <select id="station_chk" onchange="javascript:showMERoutes(this.value);">';
				if($flag_station==1)
				{
			echo'<option select="select">Select</option>
			<option value="2">Route Customer Morning</option>
				<option value="3">Route Customer Evening</option>';
				}
				echo'				
			</select>
          </td>
        </tr>
      </table>
    </td>
    </tr>'; 
	$routeMorningArr=$_SESSION['uniqueRouteArrMorning'];
	 echo '
    <tr id="morningRoute" style="display:none">
		<td>
			<div style="height:165px;overflow:auto">
			<table width="100%" class="module_left_menu">
				<tr>';
					foreach($routeMorningArr as $key=>$value)
					{
					echo"<tr>
							<td width='2%'>
								<input type='checkbox' name='morningArr[]' value=".$key." onclick='javascript:runScriptEnter_station(this.value,this.status);'>
							</td>
							<td>".$value."
							</td>
						</tr>";
					}
		echo'</table>
			</div>
		</td>
    </tr>';

	 $routeEveningArr=$_SESSION['uniqueRouteArrEvening'];
	 echo '
    <tr id="morningEvening" style="display:none">
		<td>
			<div style="height:165px;overflow:auto">
			<table width="100%" class="module_left_menu">
				<tr>';
					foreach($routeEveningArr as $key=>$value)
					{
					echo"<tr>
							<td width='2%'>
								<input type='checkbox' value=".$key." name='eveningArr[]' onclick='javascript:runScriptEnter_station(this.value,this.status);'>
							</td>
							<td>".$value."
							</td>
						</tr>";
					}
		echo'</table>
		</div>
		</td>
    </tr>'; 
	 echo '
    <tr>
		<td>
			<table class="menu" border=0 cellspacing=2 cellpadding=2>';
		echo'<tr>
				<td>
					<div style="width:90px">
						<input id="txt" type = "text" value = "Choose File" onclick ="javascript:document.getElementById(\'file\').click();" size=12>
						<input id = "file" type="file" style=\'visibility: hidden;\' name="routeFile" onchange="ChangeText(this, \'txt\');" style="width:10px"/>
					</div>
					</td>
					<td valign="top"> 
						<input type="submit" value="Upload">
					</td>
					<td valign="top">
						<input type="button" value="Show Route" onclick="showRouteCustomerLine()";>
					</td>
			</tr>';
		echo'</table>
		</td>
    </tr>'; 	
	/* echo '
    <tr>
		<td>
			<table class="menu" border=0>
				<tr>';
					
					echo'<tr>
							<td> <input type="button" value="Show Route" onclick="showRouteCustomerLine()";>
							</td>
						</tr>';
					
		echo'</table>
		</td>
    </tr>';*/
	
        ?>
        </table>	
		  <?php
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
	?>
         </form>
		<form  name="fd" action="src/php/Full_data_prev.htm" method="post" target="_blank">	
			<input type="hidden" name="xml_file"/>
			<input type="hidden" name="vserial">
			<input type="hidden" name="startdate">
			<input type="hidden" name="enddate">
			<input type="hidden" name="text_report_io_element">
			<input type="hidden" name="mode">
			<input type="hidden" name="time_interval">
			<input type="hidden" name="dwt">
			<input type="hidden" name="lastcategory">
		</form>	
		<form  name="ld" action="src/php/Last_data_prev.htm" method="post" target="_blank">	
			<input type="hidden" name="xml_file"/>
			<input type="hidden" name="vserial">
			<input type="hidden" name="startdate">
			<input type="hidden" name="enddate">
			<input type="hidden" name="text_report_io_element">
			<input type="hidden" name="mode">
			<input type="hidden" name="time_interval">
			<input type="hidden" name="dwt">
			<input type="hidden" name="lastcategory">
		</form>
		<form  name="cldr" action="src/php/cld_prev.htm" method="post" target="_blank">
			<input type="hidden" name="xml_file"/>
			<input type="hidden" name="vserial">			
			<input type="hidden" name="text_report_io_element">			
			<input type="hidden" name="dwt">
		</form>
      </div>
    </td>
  </tr>  
  <tr class="left_tr3">
      <td>          
          <?php include('module_copyright.php');?>
      </td>
  </tr>
</table>

  	
	
		  
	
