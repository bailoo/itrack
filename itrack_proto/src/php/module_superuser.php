<?php include_once("user_type_setting.php");?>

<tr>
  <td>
    <table border="0" class='module_left_menu' width="100%">
      <tr>
		 <td align='right' width='10%'>
			<a href="#" style="text-decoration:none;" onclick="show_vehicle_display_option()">
				<img src="images/icon/display.png" width='15' height='15' style="border:none;">
			</a>
		</td>
          <td>
			<a href="#" onclick="show_vehicle_display_option()" class="hs2">
				<b><?php echo $report_type;?> Display Options</b>
			</a>
		  </td>
      </tr>
    </table>
    <div id="blackout_1"> </div>
    <div id="divpopup_1">
    <?php
	$js_function_name="home_select_by_entity";
 echo'
 <table border="0" class="main_page" width="100%">
          <tr>
            <td class="manage_interfarce" align="right" colspan="7"><a href="#" onclick="javascript:close_vehicle_display_option()" class="hs3"><img src="images/close.png" type="image" style="border-style:none;"></a>&nbsp;&nbsp;</td> 													
    	 </tr>	
          <tr>';
		  if($report_type!="Person")
		  {
             echo' <td><input type="radio" name="vehicle_details_option" id="group" onclick="javascript:'.$js_function_name.'(\'group\')">&nbsp;Select By Group</td>';
			}
		echo'<td><input type="radio" name="vehicle_details_option" id="user" onclick="javascript:'.$js_function_name.'(\'user\')">&nbsp;Select By User</td>
              <td><input type="radio" name="vehicle_details_option" id="user_type" onclick="javascript:'.$js_function_name.'(\'user_type\')">&nbsp;Select By User Type</td>
              <td><input type="radio" name="vehicle_details_option" id="vehicle_tag" onclick="javascript:'.$js_function_name.'(\'vehicle_tag\')">&nbsp;Select By Tag</td>
              <td><input type="radio" name="vehicle_details_option" id="vehicle_type" onclick="javascript:'.$js_function_name.'(\'vehicle_type\')">&nbsp;Select By Type</td>';
			 if($report_type!="Person")
			{  
              echo'<td><input type="radio" name="vehicle_details_option" id="vehicle" onclick="javascript:'.$js_function_name.'(\'vehicle\')">&nbsp;Select Vehicle</td>
			  
              <td><input type="radio" name="vehicle_details_option" id="all_vehicle" value="all_veh" id="all_vehicle" onclick="javascript:show_enter_button()">&nbsp;Select All Vehicle</td>';
			 }
			 else
			 {
			  echo'<td><input type="radio" name="vehicle_details_option" id="vehicle" onclick="javascript:'.$js_function_name.'(\'vehicle\')">&nbsp;Select Person</td>
			  
              <td><input type="radio" name="vehicle_details_option" id="all_vehicle" value="all_veh" id="all_vehicle" onclick="javascript:show_enter_button()">&nbsp;Select All Person</td>';
			 }
          echo'</tr>
      </table>	
	 <div id="all_vehicle_1" style="display:none;">
		<br>
		<center><input type="hidden" name="all_vehicle_type" id="all_vehicle_type" value="all_vehicle">
		<input type="button" name="submit" value="Display All Vehicle" onclick="javascript:display_vehicle_according_divoption(this.form)"></center>
	</div>	
      <div id="selection_information" style="display:none;"></div>';
    ?>      
    </div>
	<div id="blackout_2"> </div>
    <div id="divpopup_2">
	<table border="0" class="module_left_menu" width="100%">
          <tr>
            <td class="manage_interfarce" align="right" colspan="7"><a href="#" onclick="javascript:close_portal_vehicle_information()" class="hs3"><img src="Images/close.png" type="image" style="border-style:none;"></a>&nbsp;&nbsp;</td> 													
    	  </tr>
	</table>
    <?php
     echo'<div id="portal_vehicle_information" style="display:none;"></div>';
    ?>      
    </div>

  </td>
</tr>

