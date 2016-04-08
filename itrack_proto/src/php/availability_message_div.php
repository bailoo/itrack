<?php ?>
<div id="blackout"></div>
<div id="divpopup">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#c3c3c3">							
			<tr>
				<td class="manage_interfarce" align="center">
            <!--<a href="javascript:manage_draw_geofencing_route()" class="hs3">Draw</a>&nbsp;|&nbsp;-->								
            <a href="javascript:clear_initialize()" class="hs3">Clear/Refresh</a>&nbsp;|&nbsp;
            <a href="#" onclick="javascript:return save_route_or_geofencing()" class="hs3">OK</a>&nbsp;|&nbsp;
						<a href="#" onclick="javascript:return close_div()" class="hs3">Close</a>								
				</td> 													
			</tr>           			
			<tr>
				<td colspan="5" valign="top" align="justify">
					<div id="map_div" style="width:707px; height:397px; position: relative; background-color: rgb(229, 227, 223);" class="ukseries_div_map"></div>							
				</td>
			</td>
   </tr>							
  </table>
</div>
<input type="hidden" id="close_geo_route_coord" name="close_geo_route_coord">
<input type="hidden" id="route_event" name="route_event"><input type="hidden" id="close_route_coord" name="close_route_coord">
<div id="available_message" align="center"></div>
