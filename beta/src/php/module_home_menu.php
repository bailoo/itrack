
<table  width="100%" height="98%" cellspacing="0" cellspacing="0" style="background-color: rgb(253, 255, 210);" >
    <tr>
        <td valign="top">
<table border='0' width="100%" height="100%" cellspacing="0" cellspacing="0">
    <tr class="left_tr1">
      <td>
        <?php 
		//include('module_logo.php');  
		include('user_type_setting.php');
		?>       
      </td>
    </tr>    
  <tr class="left_tr2">
    <td valign="top">
      <div style="overflow-x:hidden;overflow-y:auto;" id="leftMenu">  
      <!--<form name="thisform">-->
  		<?php 
  			if(@$mining_user_type==1 && $account_id!=1)
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
            include('module_vehicle.php');                       
            include('module_select_track.php');
          //include('module_refresh.php');
            //include('module_mouse_action.php');
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
		/*	
            include('module_latlng.php');
            include('module_speed_symbol.php');
            
             if($flag_station==1)
            {
              include('module_station.php');
            }
            if($flag_visit_track==1)
            {
             include('module_schedule_location.php');
            }  
			 include('module_landmark.php');  
            */
        ?>
        </table>
   
       <?php
	//echo'<span style="position:absolute; margin-left:250px; top:87%;z-index:99;">';include('module_speed_symbol.php');echo'</span>';
        ?>
         <!--</form>-->
		<!--<form  name="fd" action="src/php/Full_data_prev.htm" method="post" target="_blank">	
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
		</form>-->
      </div>
    </td>
  </tr>  
  <tr class="left_tr3">
      <td>          
          <?php include('module_copyright.php');?>
      </td>
  </tr>
</table>
</td>
    </tr>
</table>   
  	
	
		  
	
