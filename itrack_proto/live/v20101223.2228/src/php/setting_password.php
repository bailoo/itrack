<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $query1="SELECT superuser,user,grp FROM account WHERE account_id='$account_id'";
  $result1=mysql_query($query1,$DbConnection);
  $row1=mysql_fetch_object($result1);
  $superuser=$row1->superuser;
  $user=$row1->user;
  $grp=$row1->grp;
  
  $query1="SELECT admin_id FROM account_detail WHERE account_id='$account_id'";
  $result1=mysql_query($query1,$DbConnection);
  $row1=mysql_fetch_object($result1);
  $admin_id=$row1->admin_id;
    
  $type_superuser = "hidden";
  $type_user = "hidden";
  $type_grp = "hidden";          
  
  if($superuser=="admin")
  { 
    $type_superuser = "text";
  }
  else if($user=="admin")
  {
    $type_user = "text";
  }
  else
  {
    $type_grp = "text";
  }

  echo'
  <form method = "post"  name="thisform">
     <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
         <tr>
          <td colspan="3" align="center"><b>Password</b><div style="height:5px;"></div></td>    
        </tr>              
			 <tr>
						<td>Old Password</td>
						<td>&nbsp;:&nbsp;</td>
						<td>
							<input type ="password" name="old_pass" id="old_pass">
						</td>
				</tr>
				<tr>
						<td>New Password</td>
						<td>&nbsp;:&nbsp;</td>
						<td>
							<input type ="password" name="new_pass" id="new_pass">
						</td>
				</tr> 
        									
				<tr>
						<td>Retype New Password</td>
						<td>&nbsp;:&nbsp;</td>
						<td><input type ="password" name="new_pass1" id="new_pass1"></td>
				</tr> 									
				
        <tr>
          <td></td>
        </tr>
        			  
        <tr>                    									
					<td align="center" colspan="3"><input type="button" onclick="javascript:action_setting_password(thisform)" value="Change Password" id="enter_button">&nbsp;<input type="reset" value="Clear"></td>
				</tr>
    </table>
  </form>';
?>
