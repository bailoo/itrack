<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$js_function_name = "manage_show_file_1";    // FUNCTION NAME
	$action_type1=$_POST['action_type'];
	//echo "action_type1=".$action_type1;
  	/*echo '<center>
	     <div style="height:10px"> </div> 			
				<fieldset class="manage_fieldset">
					<legend><strong>Callibration</strong></legend>
						<table border="0" class="manage_interface" align="center">
							<tr>
								<td>';
								if($action_type1=="add")
								{
									echo'<input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\')" checked/> Add
									   &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')"/> Edit / Delete
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')"/> Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')"/> De-Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;';
								}
                else if($action_type1=="edit_delete")
                {                	
                  	echo'<input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\')"/> Add
									   &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')" checked/> Edit / Delete
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')"/> Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')"/> De-Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;';
								}
								else if($action_type1=="assign")
                {
                  	echo'<input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\',\'add\')"/> Add
									   &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')"/> Edit / Delete
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')" checked/> Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')"/> De-Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;';
                }
                else if($action_type1=="de-assign")
                {
								 	echo'<input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\',\'add\')"/> Add
									   &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')"/> Edit / Delete
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')"/> Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')" checked/> De-Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;';
								}
								else if($action_type1=="")
								{
                    	echo'<input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\',\'add\')"/> Add
									   &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')"/> Edit / Delete
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')"/> Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')"/> De-Assign 
                    &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;';
                }
                echo'</td>
							</tr>
						</table>     
				</fieldset>		
		</center>';
                */
         echo"  <ol class='breadcrumb'>
                <li><a href='#'>Manage</a></li>
                <li class='active'><b>Callibration&nbsp;</b>";
               echo'<div id="tab" class="btn-group" data-toggle="buttons" >
                   ';
                    if($action_type1=="add")
                    {
                       echo'
                           <a onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\');" class="btn btn-default active" data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\')" checked/><i class="fa fa-plus-square" aria-hidden="true"></i> Add
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')"/><i class="fa fa-pencil" aria-hidden="true"></i> Edit &nbsp;<i class="fa fa-minus-square" aria-hidden="true"></i> Delete
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')"/><i class="fa fa-link" aria-hidden="true"></i> Assign 
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')"/><i class="fa fa-chain-broken" aria-hidden="true"></i> De-Assign 
                           </a>
                        ';
                    }
                    else if($action_type1=="edit_delete")
                    {
                       echo'
                           <a onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\');" class="btn btn-default " data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\')" /><i class="fa fa-plus-square" aria-hidden="true"></i> Add
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\');" class="btn btn-default active" data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')" checked/><i class="fa fa-pencil" aria-hidden="true"></i> Edit &nbsp;<i class="fa fa-minus-square" aria-hidden="true"></i> Delete
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')"/><i class="fa fa-link" aria-hidden="true"></i> Assign 
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')"/><i class="fa fa-chain-broken" aria-hidden="true"></i> De-Assign 
                           </a>
                        ';
                    } 
                    else if($action_type1=="assign")
                    {
                       echo'
                           <a onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\');" class="btn btn-default " data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\')" /><i class="fa fa-plus-square" aria-hidden="true"></i> Add
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\');" class="btn btn-default " data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')" /><i class="fa fa-pencil" aria-hidden="true"></i> Edit &nbsp;<i class="fa fa-minus-square" aria-hidden="true"></i> Delete
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\');" class="btn btn-default active" data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')" checked/><i class="fa fa-link" aria-hidden="true"></i> Assign 
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')"/><i class="fa fa-chain-broken" aria-hidden="true"></i> De-Assign 
                           </a>
                        ';
                    } 
                    else if($action_type1=="de-assign")
                    {
                       echo'
                           <a onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\');" class="btn btn-default " data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\')" /><i class="fa fa-plus-square" aria-hidden="true"></i> Add
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\');" class="btn btn-default " data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')" /> <i class="fa fa-pencil" aria-hidden="true"></i> Edit &nbsp;<i class="fa fa-minus-square" aria-hidden="true"></i> Delete
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\');" class="btn btn-default " data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')" /><i class="fa fa-link" aria-hidden="true"></i> Assign 
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\');" class="btn btn-default active" data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')" checked/><i class="fa fa-chain-broken" aria-hidden="true"></i> De-Assign 
                           </a>
                        ';
                    }
                    else
                    {
                       echo'
                           <a onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\');" class="btn btn-default " data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="add" onclick="'.$js_function_name.'(\'src/php/manage_add_calibration.php\',\'add\')" /><i class="fa fa-plus-square" aria-hidden="true"></i> Add
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\');" class="btn btn-default " data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="edit_delete" onclick="'.$js_function_name.'(\'src/php/manage_edit_calibration_prev.php\',\'edit_delete\')" /> <i class="fa fa-pencil" aria-hidden="true"></i> Edit &nbsp;<i class="fa fa-minus-square" aria-hidden="true"></i> Delete
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\');" class="btn btn-default " data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="assign" onclick="'.$js_function_name.'(\'src/php/manage_assignment_calibration_prev.php\',\'assign\')" /><i class="fa fa-link" aria-hidden="true"></i> Assign 
                           </a>
                           <a onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                            <input type="radio" name="new_exist" value="de-assign" onclick="'.$js_function_name.'(\'src/php/manage_deassignment_calibration_prev.php\',\'de-assign\')" /><i class="fa fa-chain-broken" aria-hidden="true"></i> De-Assign 
                           </a>
                        ';
                    } 
                   echo'
                   </div>
                 </li>
            </ol>
        ';
?>  