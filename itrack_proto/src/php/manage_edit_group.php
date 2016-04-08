<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	$value = $_POST['edit'];
	$tmp = explode(',',$value);
	//echo $tmp[0].','.$tmp[1].'<BR>';
	$account_id1 = $tmp[0];
	$group_id1 = $tmp[1];
	$DEBUG = 0;

	echo"<input type='hidden' name='edit_account_id' id='edit_account_id' value='$account_id1'>"; 
	   
	$row = getGroupNameRemarkGroup($group_id1,$DbConnection); 
	$group_name = $row[0];
	$remark = $row[1];  

echo'  
<center>
<form name="manage1">
  <input type="hidden" name="group_id" id="group_id" value="'.$group_id1.'">
  
  <fieldset class="manage_fieldset">
		<legend><strong>Edit Group<strong></legend>		
      
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
    <tr>
      <td>Group Name</td>
      <td>&nbsp;:&nbsp;</td>
      <td> 
      <input type="text" name="group_name" id="group_name" value="'.$group_name.'"> </td>
    </tr>	
    <tr>
      <td>Remark</td>
      <td>&nbsp;:&nbsp;</td>
      <td> <input type ="text" name="remark" id="remark" value="'.$remark.'"> </td>
    </tr>     								
    <tr>                    									
      <td align="center"  colspan="3">
        <div style="height:10px"></div>
        <input type="button" Onclick="javascript:action_manage_group(manage1, \'edit\')" value="Update" id="id="u_d_enter_button"">&nbsp;
        <input type="reset" value="Clear">&nbsp;
        <input type="button" Onclick="javascript:action_manage_group(manage1, \'delete\')" value="Delete Vehicle" id="id="u_d_enter_button"">
      </td>
    </tr>
  </table>
  </fieldset><br>
   <a href="javascript:show_option(\'manage\',\'group\');" class="back_css">&nbsp;<b><< Back</b></a>';
   include_once('manage_loading_message.php');
  echo'</form>
</center>
';
?>