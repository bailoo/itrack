<?php	
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$js_function_name = "manage_show_file";    // FUNCTION NAME  
	echo '
                <form name="manage1">
                    <ol class="breadcrumb">
                        <li><a href="#">Manage</a></li>
                        <li class="active"><b>Group</b>     
                                
                            <!--<p class="lead">Manage your Group</p>-->

                            <div id="tab" class="btn-group" data-toggle="buttons" >
                                    <a onclick="'.$js_function_name.'(\'src/php/manage_add_group.php\')" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                                        <input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_add_group.php\')"/> Add
                                    </a>
                                    <a onclick="'.$js_function_name.'(\'src/php/manage_edit_group_prev.php\')" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                                        <input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_edit_group_prev.php\')"/> Edit/Delete
                                    </a>

                            </div>

                                              
                                        
                        </li>
                    </ol>
				<!--<fieldset class="manage_fieldset">
					<legend><strong>Group</strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>
									<input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_add_group.php\')"/> Add &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_edit_group_prev.php\')"/> Edit&nbsp;/&nbsp;Delete &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
								</td>			
							</tr>
						</table>     
				</fieldset>-->
                                <center>
				<div style"display:none;" id="edit_div"> </div>
                                </center>
			</form>
		'; 
?>