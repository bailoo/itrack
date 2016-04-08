<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');    
    
echo '<center>
<form name="thisform">
  <fieldset class="manage_fieldset">
		<legend><strong>Geofence</strong></legend>
    <table border="0" class="manage_interface" align="center">
      <tr>
        <td>
            <input type="radio" name="new_exist" value="new" onclick="new_exist_route_geo_lanmark(\'geofencing\')"/> New 
            <input type="radio" name="new_exist" value="exist" onclick="new_exist_route_geo_lanmark(\'geofencing\')"/> Exist
        </td>
      </tr>
    </table>         
        		<table>
        			<tr>
        				<td>';
                       $query="select * from geofence where create_id='$account_id'";
                			 $result=mysql_query($query,$DbConnection);
                			 if(!$result)
                			 {
                   echo '<table border="0" class="manage_interface" cellspacing="2" cellpadding="2" style="display:none;" id="exist_fieldset">
			                      <tr><td>You have not authority to update Geofence</td></tr>
			                  </table>';
                       }
                      else
                      {
                       echo'
        						    <table border="0" width="100%" align="center" class="manage_interface" cellspacing="2" cellpadding="2" style="display:none;" id="exist_fieldset">
						              <tr>
                              <td>&nbsp;Geo Name&nbsp;:&nbsp;
                                <select name="geo_id" id="geo_id" onchange="show_geo_coord(thisform);">
                                <option value="select">Select</option>';
                								$query="select * from geofence where create_id='$account_id' and status='1'";
                								$result=mysql_query($query,$DbConnection);            							
                								while($row=mysql_fetch_object($result))
                								{
                								  $geo_id=$row->geo_id;
                								  $geo_name=$row->geo_name;
                								  echo '<option value='.$geo_id.'>'.$geo_name.'</option>';
                								}
            								  echo '</select>
                            </td>
        					       </tr>
        					       <tr>                          
                            <td>
              					       <div id="coord_area" style="display:none">
              					         <table class="manage_interface">         					         
                                   <tr>                          
                                      <td>Geo Name</td>
                                      <td>:</td>
                                      <td><input type="text" name="edit_geo_name" id="edit_geo_name" onkeyup="manage_availability(this.value, \'edit_geo_name\', \'existing_in_user\')" onmouseup="manage_availability(this.value, \'edit_geo_name\', \'existing_in_user\')" onchange="manage_availability(this.value, \'edit_geo_name\', \'existing_in_user\')"></td>                                
                                   </tr> 
                                   <tr>                          
                                      <td>Geo Coord</td>
                                      <td>:</td>
                                      <td><textarea readonly="readonly" style="width:350px;height:60px" name="edit_geo_coord" id="edit_geo_coord" onclick="javascript:showCoordinateInterface(\'edit_geofencing\');"></textarea>                             
                                          <!--&nbsp;<a href="javascript:showCoordinateInterface(\'edit_geofencing\');">Edit</a>--></td>                                
                                   </tr>
                                  </table>
                                </div>
                          </td>                                
                         </tr>                               
  					            <tr>
          								<td colspan="3" align="center"><input type="button" id="u_d_enter_button" value="Update" onclick="javascript:action_manage_geofence(\'edit\')"/>&nbsp;<input type="button" value="Delete" onclick="javascript:action_manage_add_geofence(\'delete\')"/></td>
          							</tr>
                      </table> ';
                    }
                    echo'
      						<table border="0" class="manage_interface" style="display:none;" id="new_fieldset">
      						   <tr>
                        <td>Name</td><td>:</td>
                        <td><input type="text" name="add_geo_name" id="add_geo_name" onkeyup="manage_availability(this.value, \'add_geo_name\', \'existing_not_assigned\')" onmouseup="manage_availability(this.value, \'add_geo_name\', \'existing_not_assigned\')" onchange="manage_availability(this.value, \'add_geo_name\', \'existing_not_assigned\')"></td>
                      </tr>
      						   <tr>
      								 <td>Coord</td><td>:</td>
      								<td><textarea readonly="readonly" style="width:350px;height:60px" name="geo_coord" id="geo_coord" readonly onclick="javascript:showCoordinateInterface(\'geofencing\');"></textarea></td>';	
      				 echo'</tr>
      							<tr>
      								<td colspan="3" align="center"><input type="button" id="enter_button" value="Save" onclick="javascript:action_manage_geofence(\'add\')"/>&nbsp;<input type="reset"" value="Clear" /></td>
      							</tr>
      						</table>
				        </td>
				      <td>
				          <div id="blackout"> </div>
                  <div id="divpopup">';
                echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#c3c3c3">							
            						<tr>
              						<td class="manage_interfarce" align="center">
                              <a href="javascript:manage_draw_geofencing_route()" class="hs3">Draw Geofencing</a>&nbsp;|&nbsp;								
                              <a href="javascript:clear_initialize()" class="hs3">Clear/Refresh</a>&nbsp;|&nbsp;
                              <a href="#" onclick="javascript:return save_route_or_geofencing()" class="hs3">OK</a>&nbsp;|&nbsp;
              								<a href="#" onclick="javascript:return close_div()" class="hs3">Close</a>								
              						</td> 													
            						</tr>           			
            						<tr>
            							<td colspan="5" valign="top" align="justify">
            								<div id="geo_map" style="width:707px; height:397px; position: relative; background-color: rgb(229, 227, 223);" class="ukseries_div_map"></div>							
            							</td>
            						</td>
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