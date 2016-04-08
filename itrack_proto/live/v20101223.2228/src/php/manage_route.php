<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php'); 
  $valign='valign="top"';   
    
echo'<center>
  <form name="thisform">
     <fieldset class="manage_fieldset">
    		<legend><strong>Route</strong></legend>     		
        <table border="0" class="manage_interface" align="center">
          <tr>
            <td>
                <input type="radio" name="new_exist" value="new" onclick="new_exist_route_geo_lanmark(\'route\')"/> New 
                <input type="radio" name="new_exist" value="exist" onclick="new_exist_route_geo_lanmark(\'route\')"/> Exist
            </td>
          </tr>
         </table>        
         
          		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          			<tr>
          				<td>
          						<table border="0" align="center" class="manage_interface" cellspacing="2" cellpadding="2" id="exist_fieldset" style="display:none;">
  					            <tr>
                            <td>&nbsp;Route Name&nbsp:&nbsp;
                              <select name="route_id" id="route_id" onchange="show_route_coord(thisform);">
                                <option value="select">Select</option>';
                								$query="select * from route where create_id='$account_id' and status='1'";
                								$result=mysql_query($query,$DbConnection);            							
                								while($row=mysql_fetch_object($result))
                								{
                								  $route_id=$row->route_id;
                								  $route_name=$row->route_name;                								 
                								  echo '<option value='.$route_id.'>'.$route_name.'</option>';
                								}
          						  echo '</select>
                            </td>
        					       </tr>
                          <tr>                          
                            <td>
            					       <div id="coord_area" style="display:none">
            					         <table class="manage_interface">          					         
                                 <tr>                          
                                    <td '.$valign.'>Route Name</td><td '.$valign.'>:</td>
                                    <td '.$valign.'><input type="text" name="edit_route_name" id="edit_route_name" onkeyup="manage_availability(this.value, \'edit_route_name\', \'existing_in_user\')" onmouseup="manage_availability(this.value, \'edit_route_name\', \'existing_in_user\')" onchange="manage_availability(this.value, \'edit_route_name\', \'existing_in_user\')"></td>                                
                                 </tr> 
                                 <tr>                          
                                    <td'.$valign.'>Route Coord</td><td '.$valign.'>:</td>
                                    <td '.$valign.'><textarea name="edit_route_coord" id="edit_route_coord" readonly="readonly" style="width:350px;height:60px" onclick="javascript:showCoordinateInterface(\'edit_route\');"> </textarea>                            
                                        <!--&nbsp;<a href="javascript:showCoordinateInterface(\'edit_route\');">Edit</a>--></td>                                
                                 </tr>
                                </table>
                              </div>
                          </td>                                
                         </tr>        
    					            <tr>
            								<td colspan="3" align="center">
            									<input type="button" id="u_d_enter_button" value="Update" onclick="javascript:return action_manage_route(\'edit\')"/>&nbsp;<input type="button" value="Delete" onclick="javascript:action_manage_add_route(\'delete\')"/>
            								</td>
            							</tr>
                        </table>   
      					                    					
            						<table border="0" class="manage_interface" align="center" id="new_fieldset" style="display:none;">
            						   <tr>
                              <td>Name</td><td> :</td>
                              <td '.$valign.'><input type="text" name="add_route_name" id="add_route_name" onkeyup="manage_availability(this.value, \'add_route_name\', \'existing_in_user\')" onmouseup="manage_availability(this.value, \'add_route_name\', \'existing_in_user\')" onchange="manage_availability(this.value, \'add_route_name\', \'existing_in_user\')"></td>
                          </tr>
            						   <tr>
            								 <td>Coord</td><td> :</td>
            								 <td><textarea readonly="readonly" style="width:350px;height:60px" name="route_coord" id="route_coord" onclick="javascript:showCoordinateInterface(\'route\');"></textarea></td>';	
            				echo'</tr>
            							<tr>
            								<td colspan="3" align="center">
            									<input type="button" id="enter_button" value="Save" onclick="javascript:return action_manage_route(\'add\')"/>&nbsp;<input type="reset"" value="Clear" />
            								</td>
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
                  								<div id="route_map" style="width:707px; height:397px; position: relative; background-color: rgb(229, 227, 223);" class="ukseries_div_map"></div>							
                  							</td>
                  						</td>
                  			   </tr>							
                  		    </table>
                        </div>						
  				          </td>
  			         </tr>
  		        </table>   
          <input type="hidden" id="route_event" name="route_event"><input type="hidden" id="close_route_coord" name="close_route_coord">
      </fieldset>
    </form>
      <div id="available_message" align="center"></div>
</center>';   
?>  