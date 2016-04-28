<?php
    include_once('Hierarchy.php');
    include_once('util_session_variable.php');
    include_once('util_php_mysql_connectivity.php');
    $root=$_SESSION['root'];  
    echo "add##"; 
    //include_once('tree_hierarchy_information.php');	
    //echo'<div id="portal_vehicle_information">';	
    //include_once('manage_checkbox_account_new.php');  
	$root=$_SESSION['root'];
	include_once('tree_hierarchy_information_trip.php');
		
	echo"<table width='70%' align='center'>
		<tr>
			<td><br>
				<fieldset class='assignment_manage_fieldset'>
					<legend>
						<strong>Accounts</strong>
					</legend>
					<div style='height:350px;overflow:auto'>";
						include_once('manage_radio_account.php'); 
				echo"</div>
				</fieldset>
			</td>
		</tr>
	</table>";

	echo '<br><div style"display:none;" id="vehicle_div"> </div><br>';
		
    echo'
    <div style="height:5px;"></div>
    <table border="0" class="manage_interface">
          
       <tr>
            <td><strong>Source Location Name</strong></td><td><input type="text" id="landmark1_name" ></td>
            <td><strong>Souce Coord</strong>(lat,long)</td><td><input type="text" id="landmark1_point" size="37">&nbsp;<a href="javascript:showCoordinateInterface(\'landmark1\');">Get by map</a></td>       
       </tr>
 
       <tr>
            <td><strong>Destination Location Name</strong></td><td><input type="text" id="landmark2_name" ></td>
            <td><strong>Destination Coord</strong>(lat,long</td><td><input type="text" id="landmark2_point" size="37">&nbsp;<a href="javascript:showCoordinateInterface(\'landmark2\');">Get by map</a></td>       
       </tr>

        <tr>
            <td><strong>Trip StartDate</strong></td><td>
            
                <input type="text" id="date1" name="trip_startdate" size="20" maxlength="19">
					
                <a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
                        <img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
                </a>

            </td>
            <td></td>       
       </tr>

        <tr>
           <td colspan="3" align="center"><br><input type="button" value="Save" id="enter_button" onclick="javascript:return action_manage_vts_trip(\'add\')"/>&nbsp;<input type="reset"" value="Clear" /></td>
        </tr>
    </table>
    <div id="available_message" align="center"></div> 
    <div id="blackout"> </div>
    <div id="divpopup">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">							
        <tr>
           <td class="manage_interfarce" align="right"><a href="#" onclick="javascript:return close_trip_div()" class="hs3">Close</a></td> 													
        </tr> 
        <tr>
           <td colspan="5" valign="top" align="justify"><div id="landmark_map" style="width:707px; height:397px; position: relative; background-color: rgb(229, 227, 223);" class="ukseries_div_map"></div></td>
        </tr>							
      </table>
    </div>	
    ';
    include_once('manage_loading_message.php');
?>