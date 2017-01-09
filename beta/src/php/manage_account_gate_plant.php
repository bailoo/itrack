<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 
	include_once('user_type_setting.php');	
	$js_function_name = "manage_show_file";    // FUNCTION NAME
	$page_debug=0;

echo'<form name="manage1">
     <ol class="breadcrumb" style="background-color:#f7fafc;padding:1px 15px;border-radius:0px">
                
                <li class="active"><b>Plant GateKeeper</b>&nbsp;&nbsp; 
                    <div id="tab" class="btn-group" data-toggle="buttons" >
                        <a onclick="'.$js_function_name.'(\'src/php/manage_account_plant_gate_assignment_prev.php\')" class="btn btn-default" data-toggle="tab" style="padding: 1px 12px;">
                           <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_account_plant_assignment_prev.php\')"/><i class="fa fa-link" aria-hidden="true"></i> Assignment
                        </a>
                        <a onclick="'.$js_function_name.'(\'src/php/manage_account_plant_gate_deassignment_prev.php\')" class="btn btn-default" data-toggle="tab" style="padding: 1px 12px;">
                          <input type="radio" name="new_exist" value="exist" onclick="'.$js_function_name.'(\'src/php/manage_account_plant_deassignment_prev.php\')"/><i class="fa fa-chain-broken" aria-hidden="true"></i> De-Assignment
                        </a>
                    </div>
                </li>
            </ol>';
 echo'
	
                <center>
                    <div style"display:none;" id="edit_div"> </div>
                </center>
	</form>
';
?>
	
