<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$js_function_name = "manage_show_file";    // FUNCTION NAME  
	echo '<center>
			<form name="manage1">
				<fieldset class="manage_fieldset">
					<legend><strong>Invoice</strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>
									<input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_add_invoice.php\')"/> Add &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_edit_invoice_prev.php\');document.getElementById(\'automatic\').style.display=\'none\';"/> Edit &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<!--<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_assignment_station_prev.php\');document.getElementById(\'automatic\').style.display=none;document.getElementById(\'manual\').style.display=none;"/> Assign &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_station_prev.php\');document.getElementById(\'automatic\').style.display=none;document.getElementById(\'manual\').style.display=none;"/> De-Assign &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_delete_station_prev.php\');document.getElementById(\'automatic\').style.display=none;document.getElementById(\'manual\').style.display=none;"/> Delete &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;-->
								</td>
							</tr>
						</table>     
				</fieldset>
				<div style"display:none;" id="edit_div"> </div>
			</form>';
				
    echo '<div style="height:5px;display:none;" id="automatic">

    <form id="file_upload_form" name="file_upload_form" target="_blank" method="post" enctype="multipart/form-data" action="src/php/action_manage_invoice_upload.php">
      
    <table border="0" class="manage_interface">
    	<tr>
        <td>Upload Invoice Data </td><td>:</td>
        <td><input name="file" id="file" size="27" type="file" />(&nbsp; .xlsx file)</td>
      </tr>
      
      <tr>
        <td colspan="3" align="center">
          <input type="hidden" name="action_type"/>
          <input type="hidden" name="local_account_id"/>
          <br>
          <input type="button" value="Save" id="enter_button" onclick="javascript:return action_manage_invoice_upload(\'add\')"/>&nbsp;<input type="reset"" value="Clear" />         
          <iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
        </td>
      </tr>
    </table>    
    </form>    

    </div>'; 			
		echo'</center>';
		
?>  