<?php
	set_time_limit(0);
        include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$js_function_name = "manage_show_file";    // FUNCTION NAME  
	echo '				
			<form name="manage1">
                        <div class="alert-info" style="background-color:#f7fafc;padding:1px 15px;border-radius:0px">
                        <table width=100% cellspacing=0 cellpading=0>
                            <tr>
                                <td width=10%><span class="active"><b>Manage Invoice Material</b></span></td>
                                <td>

                                    <div id="tab" class="btn-group" data-toggle="buttons" style="margin-left:-50px">
                                            <a onclick="'.$js_function_name.'(\'src/php/manage_add_invoice_material.php\')" class="btn btn-default" data-toggle="tab" style="padding: 1px 12px;">
                                                <input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_add_invoice_material.php\')"/> Add Invoice Material.&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;									
                                            </a>
                                            <a onclick="'.$js_function_name.'(\'src/php/manage_edit_delete_rawmilkInvoiceMaterial.php\')" class="btn btn-default" data-toggle="tab" style="padding: 1px 12px;">
                                                <input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_edit_delete_rawmilkInvoiceMaterial.php\')"/> Edit Invoice Material. &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                                            </a>
                                    </div>

                                </td>
                           </tr>
                        </table>
                      </div>
			<!--	<fieldset style="width:800px">
					<legend><strong>Manage Invoice Material </strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>
									<input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_add_invoice_material.php\')"/> Add Invoice Material.&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;									
									<input type="radio" name="new_exist" value="new" onclick="'.$js_function_name.'(\'src/php/manage_edit_delete_rawmilkInvoiceMaterial.php\')"/> Edit Invoice Material. &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									
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