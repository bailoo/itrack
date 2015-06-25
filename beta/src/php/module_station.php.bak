<?php    
	//include("user_type_setting.php");
	
	
	
    echo '
    <tr>
      <td>
      <table width="100%" class="module_left_menu">
        <tr>
          <!--<td>
            <em>Show 
			<font color="green">
			<select id="station_sel">
			<option value="0" selected>Customer</option>
			<option value="1">Plant</option>
			</font></em> &nbsp;     
            <input type="checkbox" id="station_chk" onclick="javascript:visible_station();"/>
          </td> -->
		   <td>
             Show 
          </td>
		   <td>
            :
          </td>
		   <td>
            <!--<select id="station_chk" onchange="javascript:visible_station();">-->
			<select id="station_chk">	
				<option value="select">Select</option>
				<option value="0">Customer</option>
				<option value="1">Plant</option>';
				if($account_id=='231')
				{
				echo'<option value="2">Route Customer Morning</option>
				<option value="3">Route Customer Evening</option>
				<!--<option value="4">Route Plant Morning</option>
				<option value="5">Route Plant Evening</option>-->';
				}
				if($flag_chilling_plant)
				{
					echo'<option value="4">Chilling Plant</option>';
				}
				
				echo'
				<!--<option value="none">None</option>-->				
			</select>
          </td>
        </tr>
        
        <tr>
          <td colspan="3">
            <em>Search <font color="green">Station</font></em> &nbsp;     
            <input type="text" id="station_search_text" size="10" onkeypress="return runScriptEnter_station(event)"/>
          </td>
        </tr>
        
      </table>
    </td>
    </tr>
    ';          
?>
