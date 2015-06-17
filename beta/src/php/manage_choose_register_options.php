<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  echo"choose_account##";
  ?>
	<br>
	<input type='hidden' id='file_name' value='manage_device_vehicle_assignment.php'>
	<table align="center" border="0" class="module_left_menu">   
          <tr>
              <td><input type="radio" name="vehicle_details_option" id="group" onclick="javascript:select_manage_register_options('group')">&nbsp;Select By Group</td>
			  <td><input type="radio" name="vehicle_details_option" id="user" onclick="javascript:select_manage_register_options('user')">&nbsp;Select By User</td>
              <td><input type="radio" name="vehicle_details_option" id="user_type" onclick="javascript:select_manage_register_options('user_type')">&nbsp;Select By User Type</td>
              <td><input type="radio" name="vehicle_details_option" id="vehicle_tag" onclick="javascript:select_manage_register_options('vehicle_tag')">&nbsp;Select By Vehicle Tag</td>
              <td><input type="radio" name="vehicle_details_option" id="vehicle_type" onclick="javascript:select_manage_gregister_options('vehicle_type')">&nbsp;Select By Vehicle Type</td>
              <!--<td><input type="radio" name="vehicle_details_option" id="vehicle" onclick="javascript:select_manage_options('tree_vehicle')">&nbsp;Select Vehicle</td>-->
              <!--<td><input type="radio" name="vehicle_details_option" id="all_vehicle" value="all_veh" id="all_manage_vehicle" onclick="javascript:show_manage_enter_button()">&nbsp;Select All Vehicle</td>-->
          </tr>
      </table>
	  
	   <div id="all_vehicle_1" style="display:none;">
		<br>
		<center><input type="hidden" name="all_vehicle_type" id="all_vehicle_type" value="all_vehicle">
		<input type="button" name="submit" value="Display All Vehicle" onclick="javascript:display_vehicle_according_divoption(this.form)"></center>
	</div>	
      <div id="manage_selection_information" style="display:none;"></div>
	<div id="blackout_2"> </div>
    <div id="divpopup_2">
	<table border="0" class="module_left_menu" width="100%">
          <tr>
            <td class="manage_interfarce" align="right" colspan="7"><a href="#" onclick="javascript:close_portal_vehicle_information()" class="hs3"><img src="images/close.png" type="image" style="border-style:none;"></a>&nbsp;&nbsp;</td> 													
    	  </tr>
	</table>
    <?php
     echo'<div id="portal_vehicle_information" style="display:none;"></div>';
    ?>      
    </div>


  
