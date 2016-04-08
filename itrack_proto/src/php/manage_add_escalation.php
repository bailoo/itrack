<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');  
	$root=$_SESSION['root']; 
	$postPars = array('escalation_id' , 'action_type' , 'local_account_ids' , 'person_name', 'person_mob', 'person_email' , 'other_detail');
	include_once('action_post_data.php');
	$pd = new PostData();
	echo'<div style="height:10px"> </div>';
	//include_once('manage_escalation.php');
	$local_account_ids=explode(",",$pd->data[local_account_ids]);
	$account_size=sizeof($local_account_ids); 

	echo"<form name='manage1'>";
	echo'<div id="portal_vehicle_information">';	
	include_once('manage_checkbox_account_new.php');
	echo'</div>';	

	echo'<div style="height:10px"> </div>
				<table border="0" class="manage_interface" align="center">
					<tr>
						<td valign="top">Person Name</td>
            <td>:</td>
            <td colspan=2>';
          if($pd->data[person_name]!="")
          {
						  echo'<input type="text" value="'.$pd->data[person_name].'" id="person_name">';
					 }
					 else
					 {
					   echo'<input type="text" id="person_name">';
           }
           echo'</td>
          </tr>
          
					<tr>
						<td valign="top">Person Mobile No</td>
            <td>:</td>
            <td colspan=2>';
          if($pd->data[person_mob]!="")
          {
						  echo'<input type="text" value="'.$pd->data[person_mob].'" id="person_mob">';
					 }
					 else
					 {
					   echo'<input type="text" id="person_mob">';
           }
           echo'</td>
          </tr>
          
					<tr>
						<td valign="top">Person Email</td>
            <td>:</td>
            <td colspan=2>';
          if($pd->data[person_email]!="")
          {
						  echo'<input type="text" value="'.$pd->data[person_email].'" id="person_email">';
					 }
					 else
					 {
					   echo'<input type="text" id="person_email">';
           }
           echo'</td>
          </tr>          
                    
					<tr>
						<td valign="top">Other Detail</td>
            <td valign="top">:</td>
						<td valign="top">';
            if($pd->data[other_detail]!="")
            {
              echo'<textarea  style="width:350px;height:60px" id="other_detail">'.$pd->data[other_detail].'</textarea>';
            }
            else
            {
              echo'<textarea  style="width:350px;height:60px" id="other_detail"></textarea>';
            }                               
            echo'</td>
            <td valign="top"></td>	
					</tr>
					<tr>
						<td colspan="4" align="center"><input type="button" id="enter_button" value="Save" onclick="javascript:action_manage_escalation(\'add\')"/>&nbsp;<input type="reset"" value="Clear" /></td>
					</tr>
				</table>
			</form>';
		//include_once('availability_message_div.php'); 	
		 echo'<center><a href="javascript:show_option(\'manage\',\'escalation\');" class="back_css">&nbsp;<b>Back</b></a></center>';
			include_once('manage_loading_message.php');
		?>
