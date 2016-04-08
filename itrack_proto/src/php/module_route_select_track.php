<?php
	$query1="SELECT time_zone from account_preference WHERE account_id='$account_id'";
	$result1=mysql_query($query1,$DbConnection);
	$row1=mysql_fetch_object($result1);
	$time_zone1=$row1->time_zone;

	//date_default_timezone_set('Asia/Calcutta');
	$StartDate=date('Y/m/d');
	$StartDate=$StartDate." 00:00:00";
	$EndDate=date('Y/m/d H:i:s');
?>
<tr>
  <td>
 <input type="hidden" id="date_switch">
    <table border='0' cellpadding='0' cellspacing='0' class="module_left_menu" width="100%" bgcolor="F7DA95">
      <!--<tr>        
        <td width="47%">	
			<input type="radio" name="mode" value="1" checked Onclick="javascript:switch_vehicle_selection(this.value);"> 
			Last Position
		</td>       
        <td valign="top">
			<input type="radio" name="mode" value="2" Onclick="javascript:switch_vehicle_selection(this.value);">
			Track &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php
			//if($account_id==2)
			{
			echo'<a href="#" onclick="show_current_last_data();" class="hs2">
					<img src="../../images/live/live_vehicle.gif" width="8px" hieght="8px" style="border:none;">
				</a> ';
			}
			?>
			</td>      
      </tr>-->
      <tr>        
        <td colspan="2">
          <table class="menu" border="0" cellpadding=2 cellspacing=2>
            <tr>
              <td>Date</td>
              <td>:</td>
              <td> 
                  <?php             
                    echo'<input type="text" id="date1" name="start_date" size="7">';
                  ?>
                  <a href=javascript:NewCal_SD("date1","yyyymmdd",false,24)>
					<img src="./images/cal.gif" width="15" height="15" border="0" alt="Pick a date" style='text-decoration:none'>
				</a>
			</td>
			<td>
			 <select name='interval'>
				<option value='auto'>Auto</option> 
				<option value="5">5 sec</option>
				<option value="30" selected>30 sec</option>
								<option value="60">1 min</option>
								<option value="120">2 min</option>
								<option value="240">4 min</option>
								<option value="300">5 min</option>
								<option value="600" selected>10 min</option>
								<option value="900">15 min</option>
								<option value="1200">20 min</option>
								<option value="1800">30 min</option>
								<option value="3600">60 min</option>								
			  </select>    
		  </td>
		   <td align="center">
				<input type="button" value="Map" Onclick="javascript:return show_data_on_map('map_report');">				
            </tr>
          </table>
         </td> 
      </tr>
      <!--<tr>        
        <td colspan="2">
          <table class="module_left_menu" width="100%">
            <tr>
              <td class="module_select_track1">End Date</td>
              <td class="module_select_track2" align="center">:</td>
              <td>  
                
                <?php             
                   //echo'<input type="text" id="date2" name="end_date" value="'.$EndDate.'" size="18" maxlength="19">';
                  ?> 
                 	<a href=javascript:NewCal("date2","yyyymmdd",true,24)>
											<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
										</a>
          		   
              </td>
            </tr>
          </table>
         </td> 
      </tr>-->
      <!--<tr>        
        <td colspan="2">
          <table border="0" class="module_left_menu" width="100%">
              <tr>
                <td class="module_select_track1">Time Zone</td>
                <td class="module_select_track2" align="center">:</td>
                <td>
                  <select name='time_zone' style="width:78%">
                    <? //echo '<option value="IST">'.$time_zone1.'</option>'; ?>
                    <!--<option value='time_zone1'>time_zone1</option>
                    <option value='time_zone2'>time_zone2</option>
                    <option value='time_zone3'>time_zone3</option>
                    <option value='time_zone4'>time_zone4</option>
                    <option value='time_zone5'>time_zone5</option>-->
                <!--  </select>
                </td>
              </tr>
            </table>
        </td>
      </tr>-->
      <!--<tr>        
        <td colspan="2">
          <table class="module_left_menu" width="100%">
              <tr>
                <td class="module_select_track1">Interval</td>
                <td class="module_select_track2" align="center">:</td>
                <td>
                   <select name='interval' style="width:78%">
                    <option value='auto'>Auto</option> 
                    <option value="5">5 sec</option>
                    <option value="30" selected>30 sec</option>
  									<option value="60">1 min</option>
  									<option value="120">2 min</option>
  									<option value="240">4 min</option>
  									<option value="300">5 min</option>
  									<option value="600">10 min</option>
  									<option value="900">15 min</option>
  									<option value="1200">20 min</option>
  									<option value="1800">30 min</option>
								    <option value="3600">60 min</option>								
                  </select>
                </td>
              </tr>
            </table>
        </td>
      </tr>-->
      <tr>        
        <td colspan="2">
          <table border="0" class="menu" width="100%"> 
            <!--<tr>-->          
              <!--<td align="center"><a href="#" style="text-decoration:none" class="map_text_bt" Onclick="javascript:return show_data_on_map('map_report');">Map</a></td>-->
             <!-- <td><a href="#" Onclick="javascript:return show_data_on_map('text_report');" style="text-decoration:none" class="map_text_bt">Report</a></td>--> 
            <!--</tr>-->
			<tr>
			<td id="google_play" style="display:none;" colspan=2 align=center>
				<fieldset>
					<a href="#" Onclick="javascript:return show_data_on_map('play_report');" style="text-decoration:none" class="map_text_bt">Track Play</a>
					Play Interval<select id="play_interval" name="play_interval" >
					<option value="1">1(Msec)</option>
					<option value="10">10(Msec)</option>					
					<option value="100">100(Msec)</option>	
					<option value="200" selected >200(Msec)</option>	
					<option value="300">300(Msec)</option>	
					<option value="400">400(Msec)</option>
					<option value="500">500(Msec)</option>					
					<option value="1000">1(sec)</option>
					<option value="2000">2(sec)</option>
					<option value="3000">3(sec)</option>	
					</select>
				</fieldset>
			  </td>
			</tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
