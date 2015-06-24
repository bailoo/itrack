<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$account_id_local=$_POST['setting_account_id'];
	$tmp = explode(',',$account_id_local);
	//echo $tmp[0].','.$tmp[1].'<BR>';
	$account_id1 = $tmp[0];
	$group_id1 = $tmp[1];
  
	$query1="SELECT password FROM account WHERE account_id='$account_id_local'";
	$result1=mysql_query($query1,$DbConnection);
	$row1=mysql_fetch_object($result1);
  echo'<form method = "post"  name="setting">
			<input type="hidden" name="local_account_id" id="local_account_id" value="'.$account_id1.'">';
     echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
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
			<td align="center" colspan="3"><input type="button" onclick="javascript:action_setting_password(setting)" value="Change Password" id="enter_button">&nbsp;<input type="reset" value="Clear"></td>
		</tr>
    </table>
  </form>';
  echo'<center><a href="javascript:show_option(\'setting\',\'account_password_prev\');" class="back_css">&nbsp;<b>Back</b></a></center>';
?>
