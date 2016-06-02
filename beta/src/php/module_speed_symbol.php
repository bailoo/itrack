<?php include_once("user_type_setting.php");

	if(@$person_user_type!=1 || (@$fleet_user_type==1 || @$mining_user_type==1 || @$courier_user_type==1 || @$school_user_type ==1 || @$pos_user_type==1))
	{
		echo'<td>';
	?>  
    <table width="250px" class='module_left_menu' bgcolor=''>
      <tr data-toggle="collapse" data-target="#filterbaloon"  class="panel-heading">
        <td class="panel panel-primary">
          <i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;Filter Speed Balloons&nbsp;<span class="glyphicon glyphicon-triangle-bottom">
        </td>
      </tr>
      <tr id="filterbaloon" class="collapse">
        <td>

        <span id="m1_on"><img src="images/yellow_Marker_bottom.png" OnClick="show_balloon_marker('1');show_data_on_map('map_report');" alt="4"></span>
        <span id="m1_off" style="display:none;"><img src="images/yellow_Marker_bottom_off.png" OnClick="javascript:show_balloon_marker('1');show_data_on_map('map_report');" width="12" height="18" alt="4"></span>
          	1 to 20 &nbsp;&nbsp;&nbsp;&nbsp; 
        <input type="hidden" name="m1" value="1"/>
          	
        <span id="m2_on"><img src="images/green_Marker_bottom.png" OnClick="javascript:show_balloon_marker('2');show_data_on_map('map_report');" alt="2"></span>
        <span id="m2_off" style="display:none;"><img src="images/green_Marker_bottom_off.png" OnClick="javascript:show_balloon_marker('2');show_data_on_map('map_report');" width="12" height="18" alt="2"></span>
        >20
        <input type="hidden" name="m2" value="1"/>
        
		 &nbsp;&nbsp;&nbsp;&nbsp;
        <span id="m3_on"><img src="images/red_Marker_bottom.png" OnClick="javascript:show_balloon_marker('3');show_data_on_map('map_report');" alt="3"></span>
        <span id="m3_off" style="display:none;"><img src="images/red_Marker_bottom_off.png" OnClick="javascript:show_balloon_marker('3');show_data_on_map('map_report');" width="12" height="18" alt="3"></span>
        <1
        <input type="hidden" name="m3" value="1"/>
		  &nbsp;&nbsp;&nbsp;&nbsp;        
        <!--<img src="images/blink_Marker.gif" OnClick="javascript:show_balloon_marker(4);" alt="1" width="15" height="21">-->
        <span id="m4_on"><img src="images/blink_Marker.gif" OnClick="javascript:show_balloon_marker('4');show_data_on_map('map_report');" alt="1" width="10" height="16"></span>
        <span id="m4_off" style="display:none;"><img src="images/blink_Marker_off.png" OnClick="javascript:show_balloon_marker('4');show_data_on_map('map_report');" alt="1" width="12" height="18"></span>
        Current
        <input type="hidden" name="m4" value="1"/>   
               
        </td>
      </tr>
    </table>
  <?php echo'</td>';}?>
  
  <?php 
  
  if(@$person_user_type==1)
  {
    echo'
    <input type="hidden" name="m1" value="2"/>
    <input type="hidden" name="m2" value="2"/>
    <input type="hidden" name="m3" value="2"/>
    <input type="hidden" name="m4" value="2"/>    
    ';
  } 
  ?> 
</tr>
