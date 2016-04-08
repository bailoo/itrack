<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');    
    
echo '<center>
  <form name="thisform">
    <fieldset class="manage_fieldset">
  		<legend><strong>Landmark</strong></legend>            
        <table border="0" class="manage_interface" align="center">        
          <tr>
            <td>
                <input type="radio" name="new_exist" value="new" onclick="new_exist_route_geo_lanmark(\'landmark\')"/> New 
                <input type="radio" name="new_exist" value="exist" onclick="new_exist_route_geo_lanmark(\'landmark\')"/> Exist
            </td>
          </tr>
        </table>   
    		<table class="manage_interface" align="center">
    			<tr>
    				<td>
						  <table border="0" class="manage_interface" cellspacing="2" cellpadding="2" id="exist_fieldset" style="display:none;">
		            <tr>
                  <td align="right">Landmark Name</td><td>:</td>
                  <td><select name="landmark_id" id="landmark_id" onchange="show_landmark_coord(thisform);">
                    <option value="select">Select</option>';
    								$query="select * from landmark where create_id='$account_id' and status='1'";
    								$result=mysql_query($query,$DbConnection);            							
    								while($row=mysql_fetch_object($result))
    								{
    								  $landmark_id=$row->landmark_id;
    								  $landmark_name=$row->landmark_name;
    								  //$zoom_level=$row->zoom_level;
    								  echo '<option value='.$landmark_id.'>'.$landmark_name.'</option>';
    								}
								  echo '</select>
                  </td>
					     </tr>
				       <tr>
			           <td colspan="3">
  					       <div id="coord_area" style="display:none">
  					         <table class="manage_interface">         					         
                       <tr>                          
                          <td>Landmark Name</td><td>:</td>
                          <td><input type="text" name="edit_landmark_name" id="edit_landmark_name" onkeyup="manage_availability(this.value, \'edit_landmark_name\', \'existing_in_user\')" onmouseup="manage_availability(this.value, \'edit_landmark_name\', \'existing_in_user\')" onchange="manage_availability(this.value, \'edit_landmark_name\', \'existing_in_user\')"></td>                                
                       </tr> 
                       <tr>                          
                          <td>Landmark Coord</td><td>:</td>
                          <td>
                              <input type="text" name="edit_landmark_point" id="edit_landmark_point" size="37" onclick="javascript:showCoordinateInterface(\'landmark\');" readonly>                             
                              &nbsp;<a href="javascript:showCoordinateInterface(\'edit_landmark\');">Edit</a>
                          </td>                                
                       </tr>                                   
                       <input type="hidden" name="prev_landmark_point" id="prev_landmark_point">                             
                      <tr>                          
                          <td>Zoom Level</td><td>:</td>
                          <td> 
                              <select name="edit_zoom_level" id="edit_zoom_level">
                                <option value="select">Select</option>
                                <option value="5">National zoom level-1</option>
                                <option value="6">National zoom level-2</option>
                                <option value="7">State level-1</option>
                                <option value="8">State level-2</option>
                                <option value="9">City level-1</option>
                                <option value="10">City level-2</option>
                                <option value="11">Town level</option>
                                <option value="13">Tehsil level</option>
                                <option value="15">Street level-1</option>
                                <option value="16">Street level-2</option>
                              </select>
                          </td>                                
                      </tr>
                    </table>
                </div>
              </td>
            </tr>        
            <tr>
							<td colspan="3" align="center">
								<input type="button" value="Update" id="u_d_enter_button" onclick="javascript:return action_manage_landmark(\'edit\')"/>&nbsp;<input type="button" value="Delete" onclick="javascript:action_manage_add_landmark(\'delete\')"/>
							</td>
						</tr>
          </table>
					<table border="0" class="manage_interface" style="display:none;" id="new_fieldset">
					   <tr><td>Name</td><td>Coord</td><td>Zoom Level</td></tr>
					   <tr>
							<td><input type="text" name="add_landmark_name" id="add_landmark_name" onkeyup="manage_availability(this.value, \'add_landmark_name\', \'existing_not_assigned\')" onmouseup="manage_availability(this.value, \'add_landmark_name\', \'existing_not_assigned\')" onchange="manage_availability(this.value, \'add_landmark_name\', \'existing_not_assigned\')"></td>
							<td><input type="text" name="landmark_point" id="landmark_point" onclick="javascript:showCoordinateInterface(\'landmark\');" readonly size="37"></td>
				      <td> 
                <select name="zoom_level" id="zoom_level">
                  <option value="select">Select</option><option value="5">National zoom level-1</option><option value="6">National zoom level-2</option>
                  <option value="7">State level-1</option><option value="8">State level-2</option><option value="9">City level-1</option>
                  <option value="10">City level-2</option><option value="11">Town level</option><option value="13">Tehsil level</option>
                  <option value="15">Street level-1</option><option value="16">Street level-2</option>
                </select> 
              </td>
            </tr>
						<tr>
							<td colspan="3" align="center"><input type="button" value="Save" id="enter_button" onclick="javascript:return action_manage_landmark(\'add\')"/>&nbsp;<input type="reset"" value="Clear" /></td>
						</tr>
      	</table>					   
			</td>
			<td>
	        <div id="blackout"> </div>
          <div id="divpopup">';
        echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#c3c3c3">							
    						<tr>
                  <td class="manage_interfarce" align="right"><a href="#" onclick="javascript:return close_landmark_div()" class="hs3">Close</a></td> 													
    						</tr> 
    						<tr>
    							<td colspan="5" valign="top" align="justify"><div id="landmark_map" style="width:707px; height:397px; position: relative; background-color: rgb(229, 227, 223);" class="ukseries_div_map"></div></td>
    			     </tr>							
    		    </table>
          </div>						
	    </td>
   </tr>
</table> 
<input type="hidden" id="geo_event" name="geo_event"><input type="hidden" id="close_geo_coord" name="close_geo_coord">
    </fieldset>
</form>
<div id="available_message" align="center"></div>
</center>';   
?>  