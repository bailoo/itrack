<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$js_function_name = "manage_show_file";    // FUNCTION NAME    
  
  echo"<input type='hidden' id='prev_landmark_point'>";
	echo"<input type='hidden' id='zoom_level'>"; 
	/*
	echo '<center>
			<form name="manage1">
				<fieldset class="manage_fieldset">
					<legend><strong>Landmark</strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>
									<input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_add_landmark.php\')"/> Add &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_edit_landmark_prev.php\')"/> Edit&nbsp;/&nbsp;Delete&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</td>
							</tr>
						</table>     
				</fieldset>
				<div style"display:none;" id="edit_div"> </div>			
			</form>
		</center>'; */
         echo" <form name='manage1'>
            <ol class='breadcrumb' style='background-color:#f7fafc;padding:1px 15px;border-radius:0px'>
                
                <li class='active'><b>Landmark</b>&nbsp;&nbsp;&nbsp;
                 <div id='tab' class='btn-group' data-toggle='buttons' >
                 ";
                  echo'
                   <a onclick="'.$js_function_name.'(\'src/php/manage_add_landmark.php\');" class="btn btn-default" data-toggle="tab" style="padding: 1px 12px;">
                    <input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_add_landmark.php\')"/><i class="fa fa-plus-square" aria-hidden="true"></i> Add
                   </a>
                   <a onclick="'.$js_function_name.'(\'src/php/manage_edit_landmark_prev.php\');" class="btn btn-default" data-toggle="tab" style="padding: 1px 12px;">
		    <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_edit_landmark_prev.php\')"/><i class="fa fa-pencil" aria-hidden="true"></i> Edit
                   </a>
                  </div>
                 </li>
            </ol>
                <center>
                    <div style"display:none;" id="edit_div"> </div>	
                </center>
            </form>
            ';
?>  
 
