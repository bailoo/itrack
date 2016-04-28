<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];  
	$js_function_name="manage_select_by_entity";
	$file_name="src/php/manage_option_vehicles.php";
	echo "edit##"; 
	include_once('tree_hierarchy_information.php');
	echo"<table width='70%'>
            <tr>
                <td>
                    <fieldset class='assignment_manage_fieldset'>
                    <legend>
                            <strong>User Names</strong>
                    </legend>
                    <div style='height:350px;overflow:auto'>";
                            include_once('manage_radio_account.php');
                    echo"</div>
                </td>
            </tr>
        </table>";
	

	echo'<br>
	<center>	
	<div align="center" id="portal_vehicle_information" style="display:none;"></div>	
	<input type="button" value="Enter" onclick="javascript:manage_edit_prev(\'src/php/manage_edit_delete_trip.php\');">&nbsp;			
	</center>';
	include_once('manage_loading_message.php');
?>
  