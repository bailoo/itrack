<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  //echo"choose_account##";
  $target_filename = $_POST['target_file'];
  $js_function_name="select_by_entity";
  echo'
	<br>
	<input type="hidden" id="file_name" value="'.$target_filename.'">';
?>	
<table align="center" border="0" class="module_left_menu">   
       <?php 
echo'	   <tr>
              <td><input type="radio" name="vehicle_details_option" id="group" onclick="javascript:'.$js_function_name.'(\'group\')">&nbsp;Select By Group</td>
			  <td><input type="radio" name="vehicle_details_option" id="user" onclick="javascript:'.$js_function_name.'(\'user\')">&nbsp;Select By User</td>
              <td><input type="radio" name="vehicle_details_option" id="user_type" onclick="javascript:'.$js_function_name.'(\'user_type\')">&nbsp;Select By User Type</td>
              <td><input type="radio" name="vehicle_details_option" id="vehicle_tag" onclick="javascript:'.$js_function_name.'(\'vehicle_tag\')">&nbsp;Select By Vehicle Tag</td>
              <td><input type="radio" name="vehicle_details_option" id="vehicle_type" onclick="javascript:'.$js_function_name.'(\'vehicle_type\')">&nbsp;Select By Vehicle Type</td>
              <!--<td><input type="radio" name="vehicle_details_option" id="vehicle" onclick="javascript:'.$js_function_name.'(\'tree_vehicle\')">&nbsp;Select Vehicle</td>-->
              <!--<td><input type="radio" name="vehicle_details_option" id="all_vehicle" value="all_veh" id="all_manage_vehicle" onclick="javascript:show_manage_enter_button()">&nbsp;Select All Vehicle</td>-->
          </tr>
      </table>  
    
    <div id="portal_vehicle_information" style="display:none;"></div>	
   ';
?>      
    


  
