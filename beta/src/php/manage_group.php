<?php	
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$js_function_name = "manage_show_file";    // FUNCTION NAME  
	echo '
                <form name="manage1">
                    <div class="alert-info" style="background-color:#f7fafc;padding:1px 15px;border-radius:0px">
                    <table width=100% cellspacing=0 cellpading=0>
                        <tr>
                            <td width=10%><span class="active"><b>Group</b></span></td>
                            <td>
                               
                                <div id="tab" class="btn-group" data-toggle="buttons" style="margin-left:-50px">
                                        <a onclick="'.$js_function_name.'(\'src/php/manage_add_group.php\')" class="btn btn-default" data-toggle="tab" style="padding: 1px 12px;">
                                            <input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_add_group.php\')"/><i class="fa fa-plus-square" aria-hidden="true"></i> Add
                                        </a>
                                        <a onclick="'.$js_function_name.'(\'src/php/manage_edit_group_prev.php\')" class="btn btn-default" data-toggle="tab" style="padding: 1px 12px;">
                                            <input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_edit_group_prev.php\')"/><i class="fa fa-pencil" aria-hidden="true"></i> Edit &nbsp; <i class="fa fa-minus-square" aria-hidden="true"></i> Delete
                                        </a>
                                </div>
                               
                            </td>
                       </tr>
                    </table>
                  </div>
                
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