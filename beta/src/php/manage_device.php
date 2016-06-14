<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('user_type_setting.php');
	$page_debug=0;
	$DEBUG = 0;
	if($page_debug==1)
	{
	echo'<br>
      <table border="1" class="manage_interface" align="center">
				<tr>
					<td><b>Add Files</b></td>	<td><b>Assignment/De-Assignment Files</b></td>
				</tr>
				<tr>
					<td>manage_add_device.php</td>	<td>manage_device_assignment.php</td>	 
				</tr>
				<tr>
					<td>action_manage_device.php</td>	<td>manage_show_account.php</td>	
				</tr>
				<tr>
					<td>&nbsp;</td>	<td>action_manage_device.php</td>	 
				</tr>				
		 </table>';
	}
	$query = "SELECT field8 FROM account_feature WHERE account_id='$account_id'"; 	
	if($DEBUG == 1)
	print_query($query);
	$result = mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);
	$device_permission=$row[0];
  /*echo"
      <form method ='post' name='manage1'>
        <center>
        <fieldset class='manage_fieldset'>
          <legend> 
            <strong>
      ";
              if($report_type=="Person")
              {
                echo"Mobile";
              }
              else
              {
                echo"Device";
              }
      echo"
            </strong>
          </legend>
          ";
          if($device_permission==1)
          {
            echo'<input type="radio" name="option" value="new" onclick="javascript:;manage_show_file(\'src/php/manage_add_device.php\');"> &nbsp;New';
          }							
      echo'
                <input type="radio" name="option" value="new" onclick="javascript:manage_show_file(\'src/php/manage_device_assignment.php\');">
                Assignment/De-Assignment
          ';
          if($report_type!="Person")
          {
            echo'
                <input type="radio" name="option" value="new" onclick="javascript:manage_show_file(\'src/php/manage_edit_device_account.php\');">
                Edit';
          }
      echo
      '							
            </fieldset>
            <div align="center" id="edit_div" style="display:none;"></div>
            <div id="available_message" style="display:none;" align="center"></div>				
          </center>	
        </form>
      ';*/
     echo"
      <form method ='post' name='manage1'>
        <ol class='breadcrumb'>
                <li><a href='#'>Manage</a></li>
                ";
                        if($report_type=="Person")
                        {
                          echo"<li class='active'><b>Mobile</b> ";
                        }
                        else
                        {
                           echo"<li class='active'><b>Device</b> ";
                        } 
                        
                        echo'
                         <div id="tab" class="btn-group" data-toggle="buttons" >
                            ';
                            if($device_permission==1)
                            {
                              echo'
                            <a onclick="javascript:manage_show_file(\'src/php/manage_add_device.php\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                               <input type="radio" name="option" value="new" onclick="javascript:manage_show_file(\'src/php/manage_add_device.php\');"> <i class="fa fa-plus-square" aria-hidden="true"></i>New
                            </a>';
                            }						
                            echo'
                                <a onclick="javascript:manage_show_file(\'src/php/manage_device_assignment.php\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                                  <input type="radio" name="option" value="new" onclick="javascript:manage_show_file(\'src/php/manage_device_assignment.php\');">
                                  <i class="fa fa-link" aria-hidden="true"></i> Assignment&nbsp; <i class="fa fa-chain-broken" aria-hidden="true"></i> De-Assignment
                                </a>
                            ';
                            if($report_type!="Person")
                            {
                              echo'
                                  <a onclick="javascript:manage_show_file(\'src/php/manage_edit_device_account.php\');" class="btn btn-default" data-toggle="tab" style="padding: 3px 12px;">
                                  <input type="radio" name="option" value="new" onclick="javascript:manage_show_file(\'src/php/manage_edit_device_account.php\');">
                                  <i class="fa fa-pencil" aria-hidden="true"></i>Edit
                                  </a>
                                  ';
                            }
                            echo'
                    </div>
                </li>
            </ol>
           <center>
            <div align="center" id="edit_div" style="display:none;"></div>
            <div id="available_message" style="display:none;" align="center"></div>				
          </center>	
        </form>
      ';
 ?>  