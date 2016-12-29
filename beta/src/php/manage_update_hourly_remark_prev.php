<?php

//error_reporting(-1);
//ini_set('display_errors', 'On');
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

//include_once('manage_hierarchy_header1.php');
$root = $_SESSION['root'];

echo'<input type="hidden" id="account_id_hidden" value=' . $common_id1 . '>';
echo'<input type="hidden" id="shift" value=' . $shift1 . '>';

echo "<center><h4>Update Hourly Remark</h4></center><br>";
echo"
    <form name='manage1' method='post'>					
		
        <div style='overflow:auto;width:950px;' align='center'>
        <table border=1 cellspacing=4 cellpadding=4 class='module_left_menu' rules='all' bordercolor='gray' align='center'>";
            echo '<tr bgcolor="#d3d3d3" align="left">                      
                <td>
                    <strong>Shift</strong>
                    <select name="shift" id="shift">
                        <option value="ZPMM">Morning</option>
                        <option value="ZPME1">Evening-Cash</option>
                        <option value="ZPME2">Evening-Focal</option>
                    </select>
                </td>						
                <td>
                    <strong>Select Date</strong>
                    <input type="text" id="date1" name="date1" size="10" maxlength="19">
				
                    <a href=javascript:NewCal_SD("date1","yyyymmdd",false,24)>
                            <img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
                    </a>                    
                </td>
                <td>
                    &nbsp;&nbsp;<input type="button" id="enter_button" name="enter_button" Onclick="javascript:show_hourly_routes(1);" value="Show Hourly Routes">
                </td>
        </tr>';              
        echo'</table>
        </div>
        <br>      
</form>
  <div id="result_hourly_remark" style="display:none;"></div>';

?>  
